<?php
defined('BASEPATH') or exit('No direct script access allowed');
setlocale(LC_ALL, 'id_ID');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class Mac_invoice extends CI_Controller
{

    // Tipe item yang mengurangi stok (harus persis sama dengan nilai di DB)
    // Jasa tidak masuk daftar ini sehingga tidak akan memicu pengurangan stok
    private $tipe_stokable = ['Sparepart', 'Pelumas', 'Bahan'];
 
    function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_mac_invoice');
        $this->load->model('backend/M_mac_inventory_stok'); // TAMBAHAN
        $this->M_login->getsecurity();
        date_default_timezone_set('Asia/Jakarta');
    }
 
    public function tanggal_indo($tanggal)
    {
        $bulan = [1=>'Januari','Februari','Maret','April','Mei','Juni',
                  'Juli','Agustus','September','Oktober','November','Desember'];
        $pecah = explode('-', $tanggal);
        return $pecah[2] . ' ' . $bulan[(int)$pecah[1]] . ' ' . $pecah[0];
    }

    private function convert_date_format($date_string)
    {
        if (empty($date_string)) return null;
        $parts = explode('-', $date_string);
        return count($parts) === 3 ? $parts[2].'-'.$parts[1].'-'.$parts[0] : $date_string;
    }

    public function index()
    {
        if (empty($this->session->userdata('cabang_id'))) {
            echo "
            <script>
                alert('Cabang belum diatur. Silakan hubungi Admin untuk melanjutkan.');
                window.location.href = '" . site_url('dashboard') . "';
            </script>";
            exit;
        }

        $is_nasional    = $this->session->userdata('is_nasional') ? true : false;
        $session_cabang = intval($this->session->userdata('cabang_id'));

        $data['is_nasional'] = $is_nasional;
        $data['cabang_id']   = $session_cabang;

        if ($is_nasional) {
            $data['list_cabang'] = $this->db
                ->where('status', 'aktif')
                ->where('id !=', 1)
                ->order_by('nama_cabang', 'ASC')
                ->get('mac_cabang')->result();
        } else {
            $data['list_cabang'] = [];
        }

        $data['title']     = "backend/mac_invoice/mac_invoice_list";
        $data['titleview'] = "Invoice";
        $this->load->view('backend/home', $data);
    }

    // ========== DATATABLES LIST ==========
    function get_list()
    {
        $filter_status     = $this->input->post('filter_status');
        $filter_payment    = $this->input->post('filter_payment');
        $filter_date_start = $this->input->post('filter_date_start');
        $filter_date_end   = $this->input->post('filter_date_end');
        $is_nasional       = $this->session->userdata('is_nasional') ? true : false;
        $session_cabang    = intval($this->session->userdata('cabang_id'));
        $filter_cabang     = intval($this->input->post('filter_cabang'));

        // Nasional: pakai filter jika dipilih, null = semua cabang
        // Cabang biasa: paksa pakai cabang sendiri
        $use_cabang_id = $is_nasional
            ? ($filter_cabang > 0 ? $filter_cabang : null)
            : $session_cabang;

        $list = $this->M_mac_invoice->get_datatables(
            $filter_status, $filter_payment,
            $filter_date_start, $filter_date_end,
            $use_cabang_id
        );
        
        $data = array();
        $no   = $_POST['start'];

        $akses    = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        $read     = $akses->view_level;
        $edit     = $akses->edit_level;
        $delete   = $akses->delete_level;

        // Cek username untuk tombol approve
        $username = $this->session->userdata('username');
        $can_approve = in_array($username, ['dwi', 'bhakti']);

        foreach ($list as $field) {
            $is_privileged = in_array($username, ['dwi', 'bhakti']);

            $action_read = '';
            if ($read == 'Y') {
                if ($is_privileged || !in_array($field->app_status, ['waiting', 'revised'])) {
                    $action_read = '<a href="mac_invoice/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="View PDF"><i class="fa fa-file-pdf"></i></a>&nbsp;';
                }
            }
            
            $action_edit = '';
            if ($edit == 'Y') {
                if ($is_privileged || !in_array($field->app_status, ['rejected'])) {
                    $action_edit = '<a href="mac_invoice/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;';
                }
            }

            $action_delete = '';
            if ($delete == 'Y') {
                if ($is_privileged || !in_array($field->app_status, ['rejected'])) {
                    $action_delete = '<a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;';
                }
            }

            if ($can_approve) {
                switch ($field->app_status) {
                    case 'approved':
                        $action_approve = $can_approve ? '<a onclick="approve_data(' . "'" . $field->id . "', '" . $field->app_status . "'" . ')" class="btn btn-success btn-circle btn-sm" title="Approved"><i class="fa fa-check-circle"></i></a>&nbsp;' : '';
                        break;

                    case 'revised':
                        $action_approve = $can_approve ? '<a onclick="approve_data(' . "'" . $field->id . "', '" . $field->app_status . "'" . ')" class="btn btn-primary btn-circle btn-sm" title="Revised"><i class="fa fa-sync"></i></a>&nbsp;' : '';
                        break;

                    case 'rejected':
                        $action_approve = $can_approve ? '<a onclick="approve_data(' . "'" . $field->id . "', '" . $field->app_status . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Rejected"><i class="fa fa-times-circle"></i></a>&nbsp;' : '';
                        break;

                    default: // waiting
                        $action_approve = $can_approve ? '<a onclick="approve_data(' . "'" . $field->id . "', '" . $field->app_status . "'" . ')" class="btn btn-secondary btn-circle btn-sm" title="Waiting"><i class="fa fa-clock"></i></a>&nbsp;' : '';
                        break;
                }
            } else {
                $action_approve = '';
            }

            if ($field->app_status == 'approved') {
                $action = $action_read;
            } else {
                $action = $action_read . $action_edit . $action_delete . $action_approve;

            }


            $no++;
            $row   = array();
            $row[] = $no;
            $row[] = $action;
            
            // Mencegah jika payment_status kosong, default ke 'unpaid'
            if ((!empty($field->payment_status))) {
                $payment_status = $field->payment_status;
            } else {
                $payment_status = 'unpaid';
            }

            if ($payment_status == 'paid') {
                $row[] = '<div class="text-center" style="cursor:pointer;" data-toggle="modal" data-target="#paymentDetailModal" data-id="' . $field->id . '" title="Detail Pembayaran"><span class="badge badge-success btn-paid" style="font-size:14px; padding:8px; box-shadow: 0 2px 4px rgba(0,0,0,0.2); border-radius: 100px;"><i class="fa fa-check-circle"></i></span></div>';
            } elseif ($payment_status == 'partial') {
                $row[] = '<div class="text-center" style="cursor:pointer;" data-toggle="modal" data-target="#paymentDetailModal" data-id="' . $field->id . '" title="Detail Pembayaran"><span class="badge badge-warning" style="font-size:14px; padding:8px; box-shadow: 0 2px 4px rgba(0,0,0,0.2); border-radius: 100px;"><i class="fa fa-adjust"></i></span></div>';
            } else {
                $row[] = '<div class="text-center"><button style="background-color: #e74a3b; border-color: #e74a3b; font-size:14px; padding:8px; box-shadow: 0 2px 4px rgba(0,0,0,0.2); border-radius: 100px;" class="btn-circle btn-sm" data-toggle="modal" data-target="#paymentDetailModal" data-id="' . $field->id . '" title="Detail Pembayaran"><i class="fas fa-times" style="color: white;"></i></button></div>';
            }
            $row[] = $field->invoice_number;
            $row[] = $field->customer_name;
            $row[] = $field->nopol;
            $row[] = $this->tanggal_indo($field->awal_service);
            $row[] = 'Rp ' . number_format($field->sub_total, 0, ',', '.');
            $row[] = $field->app_status;
            $row[] = date('d-m-Y H:i:s', strtotime($field->created_at));
            $row[] = ucfirst($field->created_by);
            $data[] = $row;
        }

        $output = array(
            "draw"            => $_POST['draw'],
            "recordsTotal"    => $this->M_mac_invoice->count_all(),
            "recordsFiltered" => $this->M_mac_invoice->count_filtered(
                $filter_status, $filter_payment,
                $filter_date_start, $filter_date_end,
                $use_cabang_id
            ),
            "data"            => $data,
        );
        echo json_encode($output);
    }

    // ========== APPROVE ==========
    public function approve()
    {
        $id             = intval($this->input->post('id'));
        $app_status     = $this->input->post('app_status');
        $username       = $this->session->userdata('username');
 
        if (!in_array($username, ['dwi', 'bhakti'])) {
            echo json_encode(['status'=>FALSE,'error'=>'Akses ditolak']); return;
        }
        if (!$id) {
            echo json_encode(['status'=>FALSE,'error'=>'Invalid ID']); return;
        }
        if (!in_array($app_status, ['approved','revised','rejected'])) {
            echo json_encode(['status'=>FALSE,'error'=>'Status tidak valid']); return;
        }
 
        // Cek status sebelumnya — jangan proses stok jika sudah pernah approved
        $invoice_lama = $this->db->select('app_status, invoice_number')
            ->where('id', $id)->get('mac_invoice')->row();
 
        // ============================================================
        // STOK KELUAR — hanya diproses saat pertama kali jadi approved
        // Jika sebelumnya sudah approved (misal ubah payment_status saja)
        // maka blok ini dilewati supaya tidak double-kurangi stok.
        // ============================================================

        if ($app_status === 'approved' && $invoice_lama->app_status !== 'approved') {
            $hasil = $this->_proses_stok_keluar($id);

            if ($hasil !== true) {
                echo json_encode([
                    'status' => FALSE,
                    'error'  => 'Stok tidak mencukupi: ' . implode(', ', $hasil),
                ]);
                return;
            }

            // ── TAMBAHKAN INI ──────────────────────────────────────────────
            // Hitung HPP setelah stok keluar tercatat di mac_transaksi
            $this->_hitung_hpp($id);
            // ──────────────────────────────────────────────────────────────
        }
        // ============================================================
 
        $this->db->where('id', $id)->update('mac_invoice', [
            'app_status'     => $app_status,
            'app_date'       => date('Y-m-d H:i:s'),
            'app_by'       => $username,
            'updated_at'     => date('Y-m-d H:i:s'),
        ]);
 
        echo json_encode(['status'=>TRUE,'message'=>'Status berhasil diubah']);
    }

     
    // ---------------------------------------------------------------
    // PRIVATE: PROSES STOK KELUAR (FIFO)
    // Loop setiap baris detail invoice yang stokable, kurangi stok
    // lewat M_mac_inventory_stok->tambah_stok_keluar().
    // Idempotent: cek sudah_diproses sebelum eksekusi.
    //
    // @return true | array  true jika semua berhasil,
    //                       array berisi nama item yang gagal jika ada
    // ---------------------------------------------------------------
 
    // private function _proses_stok_keluar($invoice_id)
    // {
    //     $invoice = $this->db->where('id', $invoice_id)->get('mac_invoice')->row();
    //     if (!$invoice) return true;

    //     $details    = $this->db->where('invoice_id', $invoice_id)
    //                     ->where('is_active', 1)
    //                     ->get('mac_invoice_detail')->result_array();
    //     $created_by = $this->session->userdata('id_user');
    //     $tanggal    = date('Y-m-d');

    //     // ============================================================
    //     // PASS 1 — VALIDASI SEMUA ITEM DULU, BELUM PROSES APAPUN
    //     // Jika ada 1 saja yang gagal, langsung return error
    //     // tanpa menyentuh stok satupun
    //     // ============================================================
    //     $item_to_process = []; // kumpulkan item yang lolos validasi

    //     foreach ($details as $row) {
    //         // Lewati jasa / non-stok
    //         if (empty($row['inventory_id'])) continue;
    //         if (!in_array($row['tipe_item'], $this->tipe_stokable)) continue;

    //         $qty = floatval($row['qty']);
    //         if ($qty <= 0) continue;

    //         // Lewati yang sudah pernah diproses
    //         if ($this->M_mac_inventory_stok->sudah_diproses('Invoice', $row['id'], 'Keluar')) {
    //             continue;
    //         }

    //         // Cek stok — jika tidak cukup langsung return, TIDAK proses yang lain
    //         $stok_tersedia = $this->M_mac_inventory_stok->get_stok($row['inventory_id']);
    //         if ($stok_tersedia < $qty) {
    //             return [
    //                 $row['item'] . ' — butuh ' . $qty . ', stok tersedia ' . $stok_tersedia
    //             ];
    //         }

    //         // Lolos validasi, masukkan ke antrian proses
    //         $item_to_process[] = $row;
    //     }

    //     // ============================================================
    //     // PASS 2 — SEMUA ITEM SUDAH LOLOS VALIDASI, BARU PROSES STOK
    //     // ============================================================
    //     $gagal = [];

    //     foreach ($item_to_process as $row) {
    //         $qty   = floatval($row['qty']);
    //         $hasil = $this->M_mac_inventory_stok->tambah_stok_keluar(
    //             (int) $row['inventory_id'],
    //                 $invoice->invoice_number,
    //                 $qty,
    //                 $tanggal,
    //                 'Invoice',
    //             (int) $row['id'],
    //             (int) $created_by
    //         );

    //         if ($hasil === false) {
    //             $gagal[] = $row['item'] . ' (gagal proses FIFO)';
    //         }
    //     }

    //     return empty($gagal) ? true : $gagal;
    // }

    private function _proses_stok_keluar($invoice_id)
    {
        $invoice   = $this->db->where('id', $invoice_id)->get('mac_invoice')->row();
        if (!$invoice) return true;

        // Ambil cabang dari invoice — bukan dari session
        // karena approve bisa dilakukan oleh user berbeda cabang
        $cabang_id = intval($invoice->cabang_id)
            ?: intval($this->session->userdata('cabang_id'));

        $details    = $this->db->where('invoice_id', $invoice_id)
                        ->where('is_active', 1)
                        ->get('mac_invoice_detail')->result_array();
        $created_by = $this->session->userdata('id_user');

        $item_to_process = [];

        foreach ($details as $row) {
            if (empty($row['inventory_id'])) continue;
            if (!in_array($row['tipe_item'], $this->tipe_stokable)) continue;

            $qty = floatval($row['qty']);
            if ($qty <= 0) continue;

            if ($this->M_mac_inventory_stok->sudah_diproses('Invoice', $row['id'], $cabang_id, 'Keluar')) {
                continue;
            }

            // Cek stok per cabang invoice
            $stok_tersedia = $this->M_mac_inventory_stok->get_stok(
                $row['inventory_id'],
                false,       // is_nasional
                $cabang_id
            );

            if ($stok_tersedia < $qty) {
                return [$row['item'] . ' — butuh ' . $qty . ', stok tersedia ' . $stok_tersedia];
            }

            $item_to_process[] = $row;
        }

        $gagal = [];
        foreach ($item_to_process as $row) {
            $hasil = $this->M_mac_inventory_stok->tambah_stok_keluar(
                (int) $row['inventory_id'],
                    $cabang_id,
                    floatval($row['qty']),
                    floatval($row['harga_jual']),
                    'Invoice',
                (int) $row['id'],
                (int) $created_by
            );

            if ($hasil !== true) {
                $gagal[] = $row['item'] . ' — ' . $hasil;
            }
        }

        return empty($gagal) ? true : $gagal;
    }

    /**
     * Hitung dan simpan HPP, Profit, Margin ke mac_invoice.
     * Dipanggil tepat setelah _proses_stok_keluar() berhasil.
     *
     * Logika:
     * - Total Penjualan = SUM(total) dari mac_invoice_detail (semua tipe: Jasa + Sparepart)
     * - HPP             = SUM(harga_beli_saat_transaksi * jumlah) dari mac_transaksi
     *                     WHERE referensi_tipe = 'Invoice' AND invoice_id = $invoice_id
     * - Profit          = Penjualan - HPP
     * - Margin          = (Profit / Penjualan) * 100
     *
     * Jasa tidak punya HPP (tidak ada di mac_transaksi) — otomatis 0,
     * sehingga margin Jasa murni = 100%.
     */
    private function _hitung_hpp($invoice_id)
    {
        // ── 1. TOTAL PENJUALAN ──────────────────────────────────────────
        $penjualan_row = $this->db->select_sum('total')
            ->where('invoice_id', $invoice_id)
            ->where('is_active', 1)
            ->get('mac_invoice_detail')->row();

        $total_penjualan = $penjualan_row ? floatval($penjualan_row->total) : 0;

        // ── 2. HPP BARANG DARI FIFO (mac_transaksi) ─────────────────────
        $hpp_barang_row = $this->db->select(
                'COALESCE(SUM(t.harga_beli_saat_transaksi * t.jumlah), 0) as total_hpp',
                FALSE
            )
            ->from('mac_transaksi t')
            ->join('mac_invoice_detail d', 'd.id = t.referensi_id', 'inner')
            ->where('t.referensi_tipe', 'Invoice')
            ->where('t.tipe', 'Keluar')
            ->where('d.invoice_id', $invoice_id)
            ->where('d.is_active', 1)
            ->get()->row();

        $hpp_barang = $hpp_barang_row ? floatval($hpp_barang_row->total_hpp) : 0;

        // ── 3. HPP JASA EXTERNAL ────────────────────────────────────────
        // harga_beli_jasa > 0 = external, = 0 = internal (tidak ada biaya)
        $hpp_jasa_row = $this->db->select_sum('harga_beli_jasa')
            ->where('invoice_id', $invoice_id)
            ->where('is_active', 1)
            ->where('tipe_item', 'Jasa')
            ->where('harga_beli_jasa >', 0)
            ->get('mac_invoice_detail')->row();

        $hpp_jasa = $hpp_jasa_row ? floatval($hpp_jasa_row->harga_beli_jasa) : 0;

        // ── 4. TOTAL HPP, PROFIT, MARGIN ────────────────────────────────
        $total_hpp     = $hpp_barang + $hpp_jasa;
        $profit        = $total_penjualan - $total_hpp;
        $margin_persen = $total_penjualan > 0
            ? round(($profit / $total_penjualan) * 100, 2)
            : 0;

        // ── 5. SIMPAN SNAPSHOT KE INVOICE ───────────────────────────────
        $this->db->where('id', $invoice_id)->update('mac_invoice', [
            'total_penjualan' => $total_penjualan,
            'total_hpp'       => $total_hpp,
            'profit'          => $profit,
            'margin_persen'   => $margin_persen,
            'updated_at'      => date('Y-m-d H:i:s'),
        ]);

        return [
            'total_penjualan' => $total_penjualan,
            'hpp_barang'      => $hpp_barang,
            'hpp_jasa'        => $hpp_jasa,
            'total_hpp'       => $total_hpp,
            'profit'          => $profit,
            'margin_persen'   => $margin_persen,
        ];
    }

    // ========== GET CUSTOMERS (untuk Select2) ==========
    function get_customers()
    {
        $search = $this->input->post('search');
        $this->db->select('id, customer_name, address')->from('mac_customer');
        if (!empty($search)) $this->db->like('customer_name', $search);
        $this->db->order_by('customer_name','ASC')->limit(30);
        echo json_encode($this->db->get()->result());
    }

    // GET INVENTORY BY KATEGORI — untuk select2 item di form invoice
    function get_inventory_by_kategori()
    {
        $kategori    = $this->input->post('kategori');
        $search      = $this->input->post('search');
        $cabang_id   = intval($this->session->userdata('cabang_id'));

        if (empty($kategori)) {
            echo json_encode([]); return;
        }

        $this->db->select("
            i.id, i.kode_produk, i.nama_produk, i.satuan,
            COALESCE(s.stok_saat_ini, 0) as stok,
            (
                SELECT harga_jual FROM mac_inventory_cabang
                WHERE inventory_id = i.id AND cabang_id = {$cabang_id}
                LIMIT 1
            ) as harga_jual
        ", FALSE)
        ->from('mac_inventory i')
        ->join('mac_inventory_stok s',
            's.inventory_id = i.id AND s.cabang_id = ' . $cabang_id,
            'left')
        ->where('i.kategori', $kategori)
        ->where('i.is_active', 1);

        if (!empty($search)) {
            $this->db->group_start()
                ->like('i.nama_produk', $search)
                ->or_like('i.kode_produk', $search)
                ->group_end();
        }

        $this->db->order_by('i.nama_produk', 'ASC');
        echo json_encode($this->db->get()->result());
    }

    // ========== READ / PDF ==========
    function read_form($id)
    {
        // ========== AMBIL DATA MASTER ==========
        $master   = $this->M_mac_invoice->get_by_id($id);
        $customer = $this->db->get_where('mac_customer', ['id' => $master->customer_id])->row();
        $items    = $this->db->get_where('mac_invoice_detail', ['invoice_id' => $id])->result();

        // ========== SETUP MPDF ==========
        $mpdf = new \Mpdf\Mpdf([
            'format'        => 'A4',
            'margin_top'    => 40,
            'margin_bottom' => 15,
        ]);

        $mpdf->SetAuthor('Mobile Auto Care');
        $mpdf->SetSubject('Invoice');
        $mpdf->SetCreator('System Mobile Auto Care');

        $path = FCPATH . 'assets/backend/img/kop_surat_mac.png';
        $mpdf->SetWatermarkImage($path, 1, [210, 297], [0, 0]);
        $mpdf->showWatermarkImage = true;
        $mpdf->watermarkImgBehind = true;

        // ========== KIRIM DATA KE VIEW ==========
        $data = array(
            'invoice_number' => $master->invoice_number,
            'customer_name'  => $customer ? $customer->customer_name : '',
            'address'        => $customer ? $customer->address : '',
            'pic'            => $master->pic,
            'lampiran'       => $master->lampiran,
            'nopol'          => $master->nopol,
            'tipe'           => $master->tipe,
            'km'             => $master->km,
            'lokasi_service' => $master->lokasi_service,
            'invoice_date'   => $master->invoice_date,
            'awal_service'   => $master->awal_service,
            'uraian'         => $master->uraian,
            'due_date'       => $master->due_date,
            'sub_total'      => $master->sub_total,
            'items'          => $items,
            'payment_status' => $master->payment_status
        );

        $mpdf->SetTitle($master->invoice_number . ' - ' . $master->nopol);
        $html = $this->load->view('backend/mac_invoice/mac_invoice_pdf', $data, TRUE);

        $mpdf->WriteHTML($html);
        $mpdf->Output($master->invoice_number . '.pdf', 'I');
    }

    // ========== FORM ADD ==========
    function add_form()
    {
        $data['id']         = 0;
        $data['title_view'] = "Invoice Form";
        $data['title']      = 'backend/mac_invoice/mac_invoice_form';
        $this->load->view('backend/home', $data);
    }

    // ========== FORM EDIT ==========
    function edit_form($id)
    {
        $data['id']         = $id;
        $data['title_view'] = "Edit Invoice";
        $data['title']      = 'backend/mac_invoice/mac_invoice_form';
        $this->load->view('backend/home', $data);
    }

    // ========== GENERATE INVOICE NUMBER ==========
    function generate_invoice_number()
    {
        $current_month = date('m');
        $current_year  = date('y');
        $prefix        = 'INVMAC' . $current_year . $current_month;

        // Ambil nomor urut terakhir berdasarkan prefix bulan ini
        $last = $this->db
            ->select_max('invoice_number')
            ->like('invoice_number', $prefix, 'after')
            ->where('is_active', 1)
            ->get('mac_invoice')
            ->row();

        $last_number = $last && $last->invoice_number
            ? intval(substr($last->invoice_number, strlen($prefix)))
            : 0;

        $sequence       = $last_number + 1;
        $invoice_number = $prefix . str_pad($sequence, 3, '0', STR_PAD_LEFT);

        echo json_encode(array('invoice_number' => $invoice_number));
    }

    // ========== GET DATA FOR EDIT ==========
    function get_data($id)
    {
        $master = $this->M_mac_invoice->get_by_id($id);
        $items  = $this->db->get_where('mac_invoice_detail', ['invoice_id' => $id])->result();

        // Ambil customer_name untuk ditampilkan di select2
        $customer = $this->db->get_where('mac_customer', ['id' => $master->customer_id])->row();
        $mekanik_list = $this->db->select('m.mekanik_id, mk.nama, mk.npk, m.level, m.kategori, m.nominal_per_mekanik')
        ->from('mac_invoice_mekanik m')
        ->join('mac_mekanik mk', 'mk.id = m.mekanik_id', 'left')
        ->where('m.invoice_id', $id)
        ->get()->result_array();


        $data = array(
            'id'              => $master->id,
            'invoice_number'  => $master->invoice_number,
            'customer_id'     => $master->customer_id,
            'customer_name'   => $customer ? $customer->customer_name : '',
            'pic'             => $master->pic,
            'lampiran'        => $master->lampiran,
            'nopol'           => $master->nopol,
            'tipe'            => $master->tipe,
            'km'              => $master->km,
            'lokasi_service'  => $master->lokasi_service,
            'invoice_date'    => $master->invoice_date,
            'awal_service'    => $master->awal_service,
            'due_date'        => $master->due_date,
            'sub_total'       => $master->sub_total,
            'uraian'          => $master->uraian,
            'created_by'      => $master->created_by,
            'mekanik'         => $mekanik_list,
            'kategori'        => $master->kategori,
            'level_insentif'  => $master->level_insentif,
            'total_penjualan' => floatval($master->total_penjualan),
            'total_hpp'       => floatval($master->total_hpp),
            'profit'          => floatval($master->profit),
            'margin_persen'   => floatval($master->margin_persen),
            'items'           => $items
        );
        echo json_encode($data);
    }

    public function get_jasa()
    {
        $search    = $this->input->post('search');
        $cabang_id = intval($this->session->userdata('cabang_id'));

        // Langsung filter by cabang_id — tidak perlu subquery
        $this->db->select('id, nama, paket, satuan, harga_beli, harga_jual, jenis')
            ->from('mac_jasa')
            ->where('is_active', 1)
            ->where('cabang_id', $cabang_id);

        if (!empty($search)) {
            $this->db->group_start()
                ->like('nama', $search)
                ->or_like('paket', $search)
                ->group_end();
        }

        $this->db->order_by('nama', 'ASC');
        echo json_encode($this->db->get()->result());
    }

    // GET STOK REAL-TIME — dipanggil saat item dipilih di form invoice
    public function get_stok_item()
    {
        $inventory_id    = intval($this->input->post('inventory_id'));
        $current_invoice = intval($this->input->post('invoice_id'));
        $cabang_id       = intval($this->session->userdata('cabang_id'));

        if (!$inventory_id) {
            echo json_encode(['stok' => 0, 'stok_fisik' => 0,
                'stok_pending' => 0, 'stok_minimal' => 0, 'satuan' => '']);
            return;
        }

        // Satuan dari mac_inventory
        $inv = $this->db->select('satuan')
            ->where('id', $inventory_id)
            ->get('mac_inventory')->row();

        // stok_minimal dari mac_inventory_cabang
        $this->db->reset_query();
        $inv_cabang = $this->db->select('stok_minimal')
            ->from('mac_inventory_cabang')
            ->where('inventory_id', $inventory_id)
            ->where('cabang_id', $cabang_id)
            ->get()->row();

        // Stok fisik cabang ini
        $this->db->reset_query();
        $stok = $this->db->select('stok_saat_ini')
            ->from('mac_inventory_stok')
            ->where('inventory_id', $inventory_id)
            ->where('cabang_id', $cabang_id)
            ->get()->row();

        $stok_fisik = $stok ? floatval($stok->stok_saat_ini) : 0;

        // Pending — hanya dari cabang yang sama
        $q = $this->db->select('COALESCE(SUM(d.qty), 0) as total_pending', FALSE)
            ->from('mac_invoice_detail d')
            ->join('mac_invoice i', 'i.id = d.invoice_id')
            ->where('d.inventory_id', $inventory_id)
            ->where('i.cabang_id', $cabang_id)
            ->where('i.is_active', 1)
            ->where('i.app_status !=', 'approved')
            ->where('i.app_status !=', 'rejected');

        if ($current_invoice) {
            $q->where('i.id !=', $current_invoice);
        }

        $pending      = $q->get()->row();
        $stok_pending = $pending ? floatval($pending->total_pending) : 0;
        $stok_efektif = max(0, $stok_fisik - $stok_pending);

        echo json_encode([
            'stok'         => $stok_efektif,
            'stok_fisik'   => $stok_fisik,
            'stok_pending' => $stok_pending,
            'stok_minimal' => $inv_cabang ? floatval($inv_cabang->stok_minimal) : 0,
            'satuan'       => $inv ? $inv->satuan : '',
        ]);
    }

    function get_id($id)
    {
        $data = $this->M_mac_invoice->get_by_id($id);
        echo json_encode($data);
    }

    // ---------------------------------------------------------------
    // ADD
    // CATATAN: inventory_id wajib dikirim dari form untuk item stokable
    // ---------------------------------------------------------------
 
    public function add()
    {
        $awal_service = $this->convert_date_format($this->input->post('awal_service'));
        $akhir_service = $this->convert_date_format($this->input->post('akhir_service'));
        $due_date     = $this->convert_date_format($this->input->post('due_date'));
        $sub_total    = str_replace('.', '', $this->input->post('sub_total_value')) ?: 0;
 
        $data = [
            'invoice_number' => $this->input->post('invoice_number'),
            'customer_id'    => intval($this->input->post('customer_id')) ?: null,
            'cabang_id'      => intval($this->session->userdata('cabang_id')),
            'pic'            => ucwords(strtolower($this->input->post('pic'))),
            'lampiran'       => $this->input->post('lampiran'),
            'jenis_kendaraan' => $this->input->post('jenis_kendaraan'),
            'nopol'          => strtoupper(trim(preg_replace('/\s+/', ' ', $this->input->post('nopol')))),
            'tipe'           => ucwords(strtolower($this->input->post('tipe'))),
            'uraian'         => $this->input->post('uraian'),
            'km'             => intval(str_replace('.', '', $this->input->post('km'))) ?: 0,
            'lokasi_service' => $this->input->post('lokasi_service'),
            'invoice_date'   => date('Y-m-d'),
            'awal_service'   => $awal_service,
            'akhir_service'  => $akhir_service,
            'due_date'       => $due_date,
            'sub_total'      => $sub_total,
            'is_active'      => 1,
            'created_at'     => date('Y-m-d H:i:s'),
            'created_by'     => $this->session->userdata('username'),
            'updated_at'     => date('Y-m-d H:i:s'),
        ];
 
        try {
            $invoice_id = $this->M_mac_invoice->save($data);
            if (!$invoice_id) throw new Exception('Failed to save invoice data');
 
            $this->_save_detail($invoice_id);

            // Tambahkan setelah $invoice_id berhasil disimpan di method add()
            $kategori    = $this->input->post('kategori');
            $level       = $this->input->post('level_insentif');
            $mekanik_ids = $this->input->post('mekanik_ids') ?: [];

            // Update kolom kategori & level di invoice
            $this->db->where('id', $invoice_id)->update('mac_invoice', [
                'kategori'       => $kategori ?: null,
                'level_insentif' => $level !== '' ? intval($level) : null,
            ]);

            // Simpan insentif mekanik
            $this->_save_insentif_mekanik($invoice_id, $kategori, $level, $mekanik_ids);
 
            echo json_encode(["status"=>TRUE,"message"=>"Data saved successfully"]);
        } catch (Exception $e) {
            if (!empty($invoice_id)) {
                $this->db->where('id', $invoice_id)->delete('mac_invoice');
            }
            echo json_encode(["status"=>FALSE,"error"=>$e->getMessage()]);
        }
    }
 
    // ---------------------------------------------------------------
    // UPDATE
    // Guard: tolak update jika sudah approved (stok sudah dikurangi)
    // ---------------------------------------------------------------
 
public function update()
    {
        $id = $this->input->post('id');
 
        // Guard: invoice yang sudah approved tidak boleh diedit
        // (stok sudah berubah, edit bisa menyebabkan inkonsistensi)
        $existing = $this->db->select('app_status')->where('id', $id)->get('mac_invoice')->row();
        if ($existing && $existing->app_status === 'approved') {
            echo json_encode(["status"=>FALSE,"error"=>"Invoice yang sudah approved tidak dapat diubah."]);
            return;
        }
 
        $awal_service = $this->convert_date_format($this->input->post('awal_service'));
        $akhir_service = $this->convert_date_format($this->input->post('akhir_service'));
        $due_date     = $this->convert_date_format($this->input->post('due_date'));
        $sub_total    = str_replace('.', '', $this->input->post('sub_total_value')) ?: 0;
 
        $data = [
            'invoice_number' => $this->input->post('invoice_number'),
            'customer_id'    => intval($this->input->post('customer_id')) ?: null,
            'pic'            => ucwords(strtolower($this->input->post('pic'))),
            'lampiran'       => $this->input->post('lampiran'),
            'jenis_kendaraan' => $this->input->post('jenis_kendaraan'),
            'nopol'          => strtoupper(trim(preg_replace('/\s+/', ' ', $this->input->post('nopol')))),
            'tipe'           => ucwords(strtolower($this->input->post('tipe'))),
            'uraian'         => $this->input->post('uraian'),
            'km'             => intval(str_replace('.', '', $this->input->post('km'))) ?: 0,
            'lokasi_service' => $this->input->post('lokasi_service'),
            'awal_service'   => $awal_service,
            'akhir_service'  => $akhir_service,
            'due_date'       => $due_date,
            'sub_total'      => $sub_total,
            'updated_at'     => date('Y-m-d H:i:s'),
        ];
 
        try {
            $this->db->where('id', $id)->update('mac_invoice', $data);
            $this->db->where('invoice_id', $id)->delete('mac_invoice_detail');
            $this->_save_detail($id);

            // Tambahkan di method update() setelah update berhasil
            $kategori    = $this->input->post('kategori');
            $level       = $this->input->post('level_insentif');
            $mekanik_ids = $this->input->post('mekanik_ids') ?: [];

            $this->db->where('id', $id)->update('mac_invoice', [
                'kategori'       => $kategori ?: null,
                'level_insentif' => $level !== '' ? intval($level) : null,
            ]);

            $this->_save_insentif_mekanik($id, $kategori, $level, $mekanik_ids);
 
            echo json_encode(["status"=>TRUE,"message"=>"Data updated successfully"]);
        } catch (Exception $e) {
            echo json_encode(["status"=>FALSE,"error"=>$e->getMessage()]);
        }
    }
 
    // ---------------------------------------------------------------
    // PRIVATE: simpan detail invoice (dipakai add & update)
    // inventory_id dikirim dari form untuk item stokable,
    // null untuk jasa / item tanpa stok
    // ---------------------------------------------------------------
 
    private function _save_detail($invoice_id)
    {
        $tipe_item_list  = $this->input->post('tipe_item')    ?: [];
        $item_list       = $this->input->post('item')         ?: [];
        $inventory_ids   = $this->input->post('inventory_id') ?: [];
        $biaya_clean     = $this->input->post('biaya_clean')  ?: [];
        $diskon_clean    = $this->input->post('diskon_clean') ?: [];
        $quantities      = $this->input->post('qty')          ?: [];
        $total_clean     = $this->input->post('total_clean')  ?: [];
        $harga_beli_jasa_list = $this->input->post('harga_beli_jasa') ?: [];
 
        foreach ($tipe_item_list as $i => $val) {
            if (empty($val)) continue;
 
            // inventory_id hanya relevan untuk item stokable
            $inventory_id = in_array($val, $this->tipe_stokable) && !empty($inventory_ids[$i])
                ? (int) $inventory_ids[$i]
                : null;
 
            $this->db->insert('mac_invoice_detail', [
                'invoice_id'   => $invoice_id,
                'inventory_id' => $inventory_id,
                'tipe_item'    => $val,
                'item'         => $item_list[$i]  ?? '',
                'biaya'        => intval(str_replace('.', '', $biaya_clean[$i]  ?? 0)), 'harga_beli_jasa' => ($val === 'Jasa') ? floatval($harga_beli_jasa_list[$i] ?? 0) : 0,
                'diskon'       => intval(str_replace('.', '', $diskon_clean[$i] ?? 0)),
                'qty'          => intval($quantities[$i] ?? 0),
                'total'        => intval(str_replace('.', '', $total_clean[$i]  ?? 0)),
                'is_active'    => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ]);
        }
    }

    // HAPUS update_payment() yang lama, GANTI dengan:
    public function add_payment()
    {
        $invoice_id = intval($this->input->post('invoice_id'));
        $tgl_bayar  = $this->input->post('tgl_bayar');
        $nominal    = intval(str_replace('.', '', $this->input->post('nominal')));
        // $metode     = $this->input->post('metode');
        $keterangan = $this->input->post('keterangan');

        if (!$invoice_id) {
            echo json_encode(['status' => FALSE, 'error' => 'ID tidak valid']); return;
        }
        if (empty($tgl_bayar)) {
            echo json_encode(['status' => FALSE, 'error' => 'Tanggal bayar wajib diisi']); return;
        }
        if ($nominal <= 0) {
            echo json_encode(['status' => FALSE, 'error' => 'Nominal harus lebih dari 0']); return;
        }

        // Konversi tanggal DD-MM-YYYY ke YYYY-MM-DD
        $tgl_db = implode('-', array_reverse(explode('-', $tgl_bayar)));

        $data = [
            'invoice_id' => $invoice_id,
            'tgl_bayar'  => $tgl_db,
            'nominal'    => $nominal,
            'metode'     => 'Transfer',
            'keterangan' => $keterangan ?: null,
            'created_by' => $this->session->userdata('id_user'),
            'created_at' => date('Y-m-d H:i:s'),
        ];

        // Handle upload bukti
        if (!empty($_FILES['bukti_cicilan']['name'])) {
            $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
            $max_size      = 5 * 1024 * 1024;

            if (!in_array($_FILES['bukti_cicilan']['type'], $allowed_types)) {
                echo json_encode(['status' => FALSE, 'error' => 'Format file tidak diizinkan. Hanya JPG, PNG, JPEG, PDF.']);
                return;
            }
            if ($_FILES['bukti_cicilan']['size'] > $max_size) {
                echo json_encode(['status' => FALSE, 'error' => 'Ukuran file melebihi 5 MB.']);
                return;
            }

            $this->load->library('upload', [
                'upload_path'   => 'assets/backend/document/mac_invoice_payment/',
                'allowed_types' => 'jpg|jpeg|png|pdf',
                'max_size'      => 5120,
                'encrypt_name'  => TRUE,
            ]);

            if ($this->upload->do_upload('bukti_cicilan')) {
                $data['bukti'] = $this->upload->data('file_name');
            } else {
                echo json_encode(['status' => FALSE, 'error' => strip_tags($this->upload->display_errors())]);
                return;
            }
        }

        $this->db->insert('mac_invoice_payment', $data);

        // Update payment_status di header invoice
        $this->_update_payment_status($invoice_id);

        echo json_encode(['status' => TRUE, 'message' => 'Pembayaran berhasil dicatat']);
    }

    public function delete_payment($payment_id)
    {
        $payment = $this->db->where('id', $payment_id)->get('mac_invoice_payment')->row();
        if (!$payment) {
            echo json_encode(['status' => FALSE, 'error' => 'Data tidak ditemukan']); return;
        }

        // Hapus file bukti jika ada
        if (!empty($payment->bukti)) {
            $path = FCPATH . 'assets/backend/document/mac_invoice_payment/' . $payment->bukti;
            if (file_exists($path)) @unlink($path);
        }

        $this->db->where('id', $payment_id)->delete('mac_invoice_payment');

        // Update payment_status di header invoice
        $this->_update_payment_status($payment->invoice_id);

        echo json_encode(['status' => TRUE]);
    }

    // PRIVATE: update payment_status header invoice berdasarkan total cicilan
    private function _update_payment_status($invoice_id)
    {
        $invoice = $this->db->select('sub_total')
            ->where('id', $invoice_id)->get('mac_invoice')->row();
        if (!$invoice) return;

        $total_bayar = $this->db->select_sum('nominal')
            ->where('invoice_id', $invoice_id)
            ->get('mac_invoice_payment')->row('nominal');

        $total_bayar = floatval($total_bayar);
        $sub_total   = floatval($invoice->sub_total);

        if ($total_bayar <= 0) {
            $status = 'unpaid';
        } elseif ($total_bayar >= $sub_total) {
            $status = 'paid';
        } else {
            $status = 'partial'; // cicilan sebagian
        }

        $this->db->where('id', $invoice_id)->update('mac_invoice', [
            'payment_status' => $status,
            'updated_at'     => date('Y-m-d H:i:s'),
        ]);
    }

    // GANTI get_payment_detail() yang lama:
    public function get_payment_detail()
    {
        $id  = intval($this->input->post('id'));
        $row = $this->db->select('id, invoice_number, customer_id, sub_total, payment_status, awal_service, due_date')
            ->where('id', $id)->get('mac_invoice')->row();

        if (!$row) { echo json_encode(['status' => FALSE]); return; }

        $customer = $this->db->select('customer_name')
            ->where('id', $row->customer_id)->get('mac_customer')->row();

        // Ambil semua cicilan
        $cicilan = $this->db->where('invoice_id', $id)
            ->order_by('tgl_bayar', 'ASC')
            ->order_by('created_at', 'ASC')
            ->get('mac_invoice_payment')->result_array();

        $total_bayar = array_sum(array_column($cicilan, 'nominal'));
        $sisa        = floatval($row->sub_total) - $total_bayar;

        echo json_encode([
            'status'         => TRUE,
            'id'             => $row->id,
            'invoice_number' => $row->invoice_number,
            'customer_name'  => $customer ? $customer->customer_name : '-',
            'sub_total'      => $row->sub_total,
            'payment_status' => $row->payment_status,
            'due_date'       => $row->due_date,
            'cicilan'        => $cicilan,
            'total_bayar'    => $total_bayar,
            'sisa'           => $sisa,
        ]);
    }

    // GET daftar mekanik untuk select2 di form invoice
    public function get_mekanik()
    {
        $search         = $this->input->post('search');
        $is_nasional    = $this->session->userdata('is_nasional') ? true : false;
        $session_cabang = (int) $this->session->userdata('cabang_id');

        $this->db->select('id, nama, npk, cabang_id')
            ->from('mac_mekanik');

        // Jika bukan nasional, hanya tampilkan mekanik cabang sendiri
        if (!$is_nasional) {
            $this->db->where('cabang_id', $session_cabang);
        }

        if (!empty($search)) {
            $this->db->group_start()
                ->like('nama', $search)
                ->or_like('npk', $search)
                ->group_end();
        }

        $this->db->order_by('nama', 'ASC');

        echo json_encode($this->db->get()->result());
    }

    // GET preview nominal insentif (dipanggil saat user pilih level/mekanik)
    public function get_preview_insentif()
    {
        $kategori      = $this->input->post('kategori');
        $level         = intval($this->input->post('level'));
        $jumlah_mekanik = intval($this->input->post('jumlah_mekanik'));

        if (empty($kategori) || $level < 0 || $jumlah_mekanik < 1) {
            echo json_encode(['status' => FALSE, 'nominal_total' => 0, 'nominal_per_mekanik' => 0]);
            return;
        }

        $config = $this->db->where('kategori', $kategori)
            ->where('level', $level)
            ->where('is_active', 1)
            ->get('mac_insentif')->row();

        $nominal_total       = $config ? floatval($config->nominal) : 0;
        $nominal_per_mekanik = $jumlah_mekanik > 0
            ? round($nominal_total / $jumlah_mekanik) : 0;

        echo json_encode([
            'status'              => TRUE,
            'nominal_total'       => $nominal_total,
            'nominal_per_mekanik' => $nominal_per_mekanik,
        ]);
    }

    // PRIVATE: simpan insentif mekanik saat invoice disave/update
    private function _save_insentif_mekanik($invoice_id, $kategori, $level, $mekanik_ids)
    {
        $this->db->where('invoice_id', $invoice_id)->delete('mac_invoice_mekanik');

        if (empty($mekanik_ids) || $kategori === '' || $level === '' || $level === null) return;

        $level = intval($level);

        // Ambil nominal dari config
        $config = $this->db->where('kategori', $kategori)
            ->where('level', $level)
            ->where('is_active', 1)
            ->get('mac_insentif')->row();

        $nominal_total = $config ? floatval($config->nominal) : 0;
        $jumlah        = count($mekanik_ids);

        if ($level == 7) {
            // Level 7 — nominal custom per mekanik dari POST
            $custom_insentif = $this->input->post('custom_insentif') ?: [];

            $batch = [];
            foreach ($mekanik_ids as $mekanik_id) {
                if (empty($mekanik_id)) continue;

                $nominal_custom = floatval($custom_insentif[$mekanik_id] ?? 0);

                // Guard: tidak boleh melebihi nominal_total (batas per mekanik)
                if ($nominal_total > 0 && $nominal_custom > $nominal_total) {
                    $nominal_custom = $nominal_total;
                }

                $batch[] = [
                    'invoice_id'          => $invoice_id,
                    'mekanik_id'          => intval($mekanik_id),
                    'level'               => $level,
                    'kategori'            => $kategori,
                    'nominal_total'       => $nominal_total, // batas max per mekanik
                    'nominal_per_mekanik' => $nominal_custom,
                    'created_at'          => date('Y-m-d H:i:s'),
                ];
            }
        } else {
            // Level 0-6 — nominal dibagi rata
            $nominal_per_mekanik = $jumlah > 0 ? round($nominal_total / $jumlah) : 0;

            $batch = [];
            foreach ($mekanik_ids as $mekanik_id) {
                if (empty($mekanik_id)) continue;
                $batch[] = [
                    'invoice_id'          => $invoice_id,
                    'mekanik_id'          => intval($mekanik_id),
                    'level'               => $level,
                    'kategori'            => $kategori,
                    'nominal_total'       => $nominal_total,
                    'nominal_per_mekanik' => $nominal_per_mekanik,
                    'created_at'          => date('Y-m-d H:i:s'),
                ];
            }
        }

        if (!empty($batch)) {
            $this->db->insert_batch('mac_invoice_mekanik', $batch);
        }
    }

    public function get_nominal_level7()
    {
        $rows = $this->db->select('kategori, nominal')
            ->where('level', 7)
            ->where('is_active', 1)
            ->get('mac_insentif')->result_array();

        $data = [];
        foreach ($rows as $r) {
            $data[$r['kategori']] = floatval($r['nominal']);
        }

        echo json_encode(['status' => TRUE, 'data' => $data]);
    }

    // ========== DELETE ==========
    function delete($id)
    {
        $this->db->where('invoice_id', $id);
        $this->db->update('mac_invoice_detail', ['is_active' => 0]);

        $this->db->where('id', $id);
        $this->db->update('mac_invoice', ['is_active' => 0]);
        echo json_encode(array("status" => TRUE));
    }

    public function export_excel()
    {
        $date_start = $this->input->get('date_start');
        $date_end   = $this->input->get('date_end');
        $status     = $this->input->get('status');
        $payment    = $this->input->get('payment');

        $this->db->select('a.invoice_number, b.customer_name, a.nopol, a.awal_service, a.invoice_date, a.uraian, a.sub_total AS grand_total, a.due_date, a.pic, a.payment_status, a.created_by');
        $this->db->from('mac_invoice a');
        $this->db->join('mac_customer b', 'a.customer_id = b.id', 'left');
        $this->db->where('a.is_active', 1);

        // Filter status jika ada
        if (!empty($status)) {
            $this->db->where('a.app_status', $status);
        }

        // Filter payment status jika ada
        if (!empty($payment)) {
            $this->db->where('a.payment_status', $payment);
        }

        // Filter periode
        if (!empty($date_start)) {
            $parts = explode('-', $date_start);
            $start = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
            $this->db->where('DATE(a.invoice_date) >=', $start);
        }
        if (!empty($date_end)) {
            $parts = explode('-', $date_end);
            $end   = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
            $this->db->where('DATE(a.invoice_date) <=', $end);
        }

        $this->db->order_by('a.created_at', 'DESC');
        $invoice = $this->db->get()->result();

        // Nama file menyertakan periode jika ada
        $filename = 'Invoice - Mobile Auto Care';
        if (!empty($date_start) && !empty($date_end)) {
            $filename .= ' (' . $date_start . ' sd ' . $date_end . ')';
        } elseif (!empty($date_start)) {
            $filename .= ' (Dari ' . $date_start . ')';
        } elseif (!empty($date_end)) {
            $filename .= ' (Sampai ' . $date_end . ')';
        }

        // Inisialisasi Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set judul kolom
        $sheet->setCellValue('A1', 'Periode');
        $sheet->setCellValue('B1', 'Invoice Date');
        $sheet->setCellValue('C1', 'Invoice Number');
        $sheet->setCellValue('D1', 'Nama Customer');
        $sheet->setCellValue('E1', 'Service Date');
        $sheet->setCellValue('F1', 'PIC');
        $sheet->setCellValue('G1', 'Uraian');
        $sheet->setCellValue('H1', 'Grand Total');
        $sheet->setCellValue('I1', 'Jatuh Tempo');
        $sheet->setCellValue('J1', 'Payment Status');
        $sheet->setCellValue('K1', 'Created By');

        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $sheet->setAutoFilter('A1:K1');

        $row = 2;
        foreach ($invoice as $data) {
            $sheet->setCellValue('A' . $row, '20' . substr($data->invoice_number, 6, 4));
            $sheet->setCellValue('B' . $row, Date::PHPToExcel(new DateTime($data->invoice_date)));
            $sheet->setCellValue('C' . $row, $data->invoice_number);
            $sheet->setCellValue('D' . $row, $data->customer_name);
            $sheet->setCellValue('E' . $row, Date::PHPToExcel(new DateTime($data->awal_service)));
            $sheet->setCellValue('F' . $row, $data->pic);
            $sheet->setCellValue('G' . $row, $data->uraian);
            $sheet->setCellValue('H' . $row, $data->grand_total);
            $sheet->setCellValue('I' . $row, Date::PHPToExcel(new DateTime($data->due_date)));
            $sheet->setCellValue('J' . $row, $data->payment_status);
            $sheet->setCellValue('K' . $row, ucfirst($data->created_by));
            $row++;
        }

        $sheet->getStyle('H2:H' . ($row - 1))->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('B2:B' . ($row - 1))->getNumberFormat()->setFormatCode('dd/mm/yyyy');
        $sheet->getStyle('E2:E' . ($row - 1))->getNumberFormat()->setFormatCode('dd/mm/yyyy');
        $sheet->getStyle('I2:I' . ($row - 1))->getNumberFormat()->setFormatCode('dd/mm/yyyy');

        $writer = new Xlsx($spreadsheet);

        if (ob_get_length()) ob_end_clean();

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    // public function rekap_insentif()
    // {
    //     $data['title']     = 'backend/mac_invoice/mac_rekap_insentif';
    //     $data['titleview'] = 'Rekap Insentif Mekanik';
    //     $this->load->view('backend/home', $data);
    // }

    // public function get_rekap_insentif()
    // {
    //     $bulan  = $this->input->post('bulan');
    //     $mekanik_id = intval($this->input->post('mekanik_id'));

    //     $this->db->select('
    //         mk.id as mekanik_id,
    //         mk.nama,
    //         mk.npk,
    //         mk.cabang,
    //         im.kategori,
    //         im.level,
    //         im.nominal_per_mekanik,
    //         i.invoice_number,
    //         i.nopol,
    //         i.awal_service,
    //         i.kategori as kategori_kendaraan
    //     ', FALSE)
    //     ->from('mac_invoice_mekanik im')
    //     ->join('mac_mekanik mk', 'mk.id = im.mekanik_id', 'left')
    //     ->join('mac_invoice i',  'i.id  = im.invoice_id',  'left')
    //     ->where('i.app_status', 'approved')
    //     ->where('i.is_active', 1);

    //     if (!empty($bulan)) {
    //         $this->db->where("DATE_FORMAT(i.awal_service, '%Y-%m') = '{$bulan}'", NULL, FALSE);
    //     }
    //     if ($mekanik_id) {
    //         $this->db->where('im.mekanik_id', $mekanik_id);
    //     }

    //     $this->db->order_by('mk.nama', 'ASC')
    //             ->order_by('i.awal_service', 'ASC');

    //     $data = $this->db->get()->result_array();

    //     // Group by mekanik untuk summary
    //     $summary = [];
    //     foreach ($data as $row) {
    //         $mid = $row['mekanik_id'];
    //         if (!isset($summary[$mid])) {
    //             $summary[$mid] = [
    //                 'mekanik_id' => $mid,
    //                 'nama'       => $row['nama'],
    //                 'npk'        => $row['npk'],
    //                 'cabang'     => $row['cabang'],
    //                 'total'      => 0,
    //                 'jumlah_invoice' => 0,
    //             ];
    //         }
    //         $summary[$mid]['total']          += floatval($row['nominal_per_mekanik']);
    //         $summary[$mid]['jumlah_invoice'] += 1;
    //     }

    //     echo json_encode([
    //         'status'  => TRUE,
    //         'detail'  => $data,
    //         'summary' => array_values($summary),
    //     ]);
    // }

    // public function export_rekap_insentif()
    // {
    //     $bulan      = $this->input->get('bulan');
    //     $mekanik_id = intval($this->input->get('mekanik_id'));

    //     $this->db->select('
    //         mk.nama, mk.npk, mk.cabang,
    //         im.kategori, im.level, im.nominal_per_mekanik,
    //         i.invoice_number, i.nopol, i.awal_service
    //     ', FALSE)
    //     ->from('mac_invoice_mekanik im')
    //     ->join('mac_mekanik mk', 'mk.id = im.mekanik_id', 'left')
    //     ->join('mac_invoice i',  'i.id  = im.invoice_id',  'left')
    //     ->where('i.app_status', 'approved')
    //     ->where('i.is_active', 1);

    //     if (!empty($bulan))  $this->db->where("DATE_FORMAT(i.awal_service, '%Y-%m') = '{$bulan}'", NULL, FALSE);
    //     if ($mekanik_id)     $this->db->where('im.mekanik_id', $mekanik_id);
    //     $this->db->order_by('mk.nama', 'ASC')->order_by('i.awal_service', 'ASC');

    //     $rows = $this->db->get()->result_array();

    //     $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    //     $sheet       = $spreadsheet->getActiveSheet();
    //     $sheet->setTitle('Rekap Insentif');

    //     // Judul
    //     $sheet->mergeCells('A1:I1');
    //     $sheet->setCellValue('A1', 'REKAP INSENTIF MEKANIK' . (!empty($bulan) ? ' - ' . $bulan : ''));
    //     $sheet->getStyle('A1')->applyFromArray([
    //         'font'      => ['bold' => true, 'size' => 13],
    //         'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
    //     ]);

    //     // Header
    //     $headers = ['No', 'Nama Mekanik', 'NPK', 'Cabang', 'Invoice', 'Nopol',
    //                 'Tgl Service', 'Level', 'Kategori', 'Insentif'];
    //     foreach ($headers as $i => $h) {
    //         $sheet->setCellValueByColumnAndRow($i + 1, 3, $h);
    //     }
    //     $sheet->getStyle('A3:J3')->applyFromArray([
    //         'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
    //         'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    //                 'startColor' => ['rgb' => '242D4A']],
    //     ]);

    //     $r = 4;
    //     $no = 1;
    //     $total = 0;
    //     foreach ($rows as $d) {
    //         $sheet->setCellValue('A' . $r, $no++);
    //         $sheet->setCellValue('B' . $r, $d['nama']);
    //         $sheet->setCellValue('C' . $r, $d['npk']);
    //         $sheet->setCellValue('D' . $r, $d['cabang']);
    //         $sheet->setCellValue('E' . $r, $d['invoice_number']);
    //         $sheet->setCellValue('F' . $r, $d['nopol']);
    //         $sheet->setCellValue('G' . $r, date('d-m-Y', strtotime($d['awal_service'])));
    //         $sheet->setCellValue('H' . $r, 'Level ' . $d['level']);
    //         $sheet->setCellValue('I' . $r, $d['kategori']);
    //         $sheet->setCellValue('J' . $r, floatval($d['nominal_per_mekanik']));
    //         $sheet->getStyle('J' . $r)->getNumberFormat()->setFormatCode('#,##0');
    //         $total += floatval($d['nominal_per_mekanik']);
    //         $r++;
    //     }

    //     // Total
    //     $sheet->mergeCells('A' . $r . ':I' . $r);
    //     $sheet->setCellValue('A' . $r, 'TOTAL');
    //     $sheet->setCellValue('J' . $r, $total);
    //     $sheet->getStyle('A' . $r . ':J' . $r)->applyFromArray([
    //         'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
    //         'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    //                 'startColor' => ['rgb' => '242D4A']],
    //     ]);
    //     $sheet->getStyle('J' . $r)->getNumberFormat()->setFormatCode('#,##0');

    //     foreach (range('A', 'J') as $col) {
    //         $sheet->getColumnDimension($col)->setAutoSize(true);
    //     }

    //     $filename = 'Rekap_Insentif' . (!empty($bulan) ? '_' . $bulan : '') . '.xlsx';
    //     if (ob_get_length()) ob_end_clean();
    //     header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    //     header('Content-Disposition: attachment; filename="' . $filename . '"');
    //     header('Cache-Control: max-age=0');
    //     (new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet))->save('php://output');
    //     exit;
    // }
}