<?php

use Mpdf\Tag\Center;

defined('BASEPATH') or exit('No direct script access allowed');

// Memuat file TCPDF dari folder third_party
require_once(APPPATH . 'third_party/TCPDF-main/tcpdf.php');

class T_cpdf2 extends TCPDf
{
    // Page header
    function Header()
    {
        // Logo
        $this->SetFont('helvetica', 'B', 12);
        $this->Image(base_url('assets/backend/img/Header.png'), 49, 5, 160, 30);
        $this->Ln(5);
    }

    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        // $this->SetY(-15);
        // helvetica italic 8
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        // $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
        $this->Image(base_url('assets/backend/img/Footer.png'), 0, 280, 210, 5);
    }
}

class Pu_invoice extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_pu_invoice');
        // $this->load->model('backend/M_notifikasi');
        $this->M_login->getsecurity();
        date_default_timezone_set('Asia/Jakarta');
    }

    function tgl_indo($tanggal)
    {
        $bulan = array(
            1 =>   'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );
        $pecahkan = explode('-', $tanggal);

        // variabel pecahkan 0 = tanggal
        // variabel pecahkan 1 = bulan
        // variabel pecahkan 2 = tahun

        return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
    }

    public function index()
    {
        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        ($akses->view_level == 'N' ? redirect('auth') : '');
        $data['add'] = $akses->add_level;
        $data['edit'] = $akses->edit_level;

        // $data['notif'] = $this->M_notifikasi->pending_notification();

        $data['title'] = "backend/pu_invoice/pu_invoice_list";
        $data['is_active'] = $this->db->select('is_active')
            ->from('pu_invoice')
            ->where('is_active', 0)
            ->get()
            ->row('is_active');
        $data['titleview'] = "Data Invoice";
        $this->load->view('backend/home', $data);
    }

    public function get_pdf()
    {
        $this->load->view('backend/prepayment_pu/prepayment_pdf');
    }

    function get_list()
    {
        // INISIAI VARIABLE YANG DIBUTUHKAN
        $fullname = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $this->session->userdata('id_user'))
            ->get()
            ->row('name');
        $list = $this->M_pu_invoice->get_datatables();
        $data = array();
        $no = $_POST['start'];

        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        $read = $akses->view_level;
        $edit = $akses->edit_level;
        $delete = $akses->delete_level;
        $print = $akses->print_level;

        //LOOPING DATATABLES
        foreach ($list as $field) {

            // MENENTUKAN ACTION APA YANG AKAN DITAMPILKAN DI LIST DATA TABLES
            $action_read = ($read == 'Y') ? '
            <button type="button" class="btn btn-info btn-circle btn-sm show-dropdown" data-id="' . $field->id . '" data-is_active="' . $field->is_active . '" title="Read">
                <i class="fa fa-eye"></i>
            </button>&nbsp;' : '';
            $action_edit = ($edit == 'Y') ? '&nbsp;<a href="pu_invoice/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;' : '';
            $action_delete = ($delete == 'Y') ? '<a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;' : '';
            // $action_print = ($print == 'Y') ? '
            // <button type="button" class="btn btn-success btn-circle btn-sm show-print-dropdown" data-id="' . $field->id . '" data-is_active="' . $field->is_active . '" title="Print">
            //     <i class="fas fa-file-pdf"></i>
            // </button>&nbsp;' : '';
            $action_payment = '<button type="button" class="btn btn-primary btn-circle btn-sm" onclick="showPaymentModal(' . $field->id . ')"><i class="fas fa-money-bill-wave"></i></button>';

            if ($field->is_active == 0) {
                $action = $action_read . $action_payment;
            } else {
                $action = $action_read . $action_edit . $action_delete . $action_payment;
            }

            if ($field->status == 0) {
                $status = 'Lunas';
            } else {
                $status = 'Belum Lunas';
            }

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $action;

            $kode_invoice = substr($field->kode_invoice, 0, 5) . substr($field->kode_invoice, 7, 6);
            $row[] = strtoupper($kode_invoice);
            $row[] = $field->ctc_nama;
            $row[] = $this->tgl_indo(date("Y-m-j", strtotime($field->tgl_invoice)));
            $row[] = $this->tgl_indo(date("Y-m-j", strtotime($field->tgl_tempo)));
            $row[] = $status;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_pu_invoice->count_all(),
            "recordsFiltered" => $this->M_pu_invoice->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    // UNTUK MENAMPILKAN FORM INVOICE
    public function read_invoice($id)
    {
        $data['id'] = $id;
        $data['invoice'] = $this->M_pu_invoice->getInvoiceData($id);
        $data['tgl_invoice'] = $this->tgl_indo(date("Y-m-j", strtotime($data['invoice']['tgl_invoice'])));
        $data['tgl_tempo'] = $this->tgl_indo(date("Y-m-j", strtotime($data['invoice']['tgl_tempo'])));
        $data['details'] = $this->db->get_where('pu_detail_invoice', ['invoice_id' => $id])->result_array();
        $data['total_tagihan'] = $this->db->select_sum('total')->get_where('pu_detail_invoice', ['invoice_id' => $id])->row()->total;
        $data['rekening'] = $this->db->get_where('pu_travel', ['id' => $data['invoice']['travel_id']])->row();

        // Mendapatkan total nominal_dibayar
        $this->db->select_sum('nominal_dibayar');
        $this->db->where('id_invoice', $id);
        $data['total_nominal_dibayar'] = $this->db->get('pu_kwitansi')->row()->nominal_dibayar;

        $data['title'] = 'backend/pu_invoice/pu_invoice_read';
        $data['title_view'] = 'Prepayment';
        $this->load->view('backend/home', $data);
    }

    // UNTUK MENAMPILKAN FORM KWITANSI
    public function read_kwitansi($id)
    {
        // $data['notif'] = $this->M_notifikasi->pending_notification();
        $data['id'] = $id;
        $data['invoice'] = $this->M_pu_invoice->getInvoiceData($id);
        $data['kwitansi'] = $this->db->get_where('pu_kwitansi', ['id_invoice' => $id])->result_array();
        $data['detail'] = $this->db->get_where('pu_detail_invoice', ['invoice_id' => $id])->result_array();
        $data['total_tagihan'] = $this->db->select_sum('total')->get_where('pu_detail_invoice', ['invoice_id' => $id])->row()->total;
        $data['tgl_invoice'] = $this->tgl_indo(date("Y-m-j", strtotime($data['invoice']['tgl_invoice'])));
        $data['title'] = 'backend/pu_invoice/pu_kwitansi_read';
        $data['title_view'] = 'Prepayment';
        $this->load->view('backend/home', $data);
    }

    // UNTUK MENAMPILKAN DETAIL KWITANSI
    public function get_kwitansi()
    {
        $id = $this->input->post('id');
        $id_invoice = $this->input->post('id_invoice');
        $data['kwitansi'] = $this->db->get_where('pu_kwitansi', ['id' => $id])->row();

        // Ambil data kwitansi sampai id sama dengan $id
        $this->db->where('id_invoice', $id_invoice);
        $this->db->where('id <=', $id);
        $data['detail'] = $this->db->get('pu_kwitansi')->result_array();

        // Mendapatkan total nominal_dibayar
        $this->db->select_sum('nominal_dibayar');
        $this->db->where('id_invoice', $id_invoice);
        $this->db->where('id <=', $id);
        $data['total_nominal_dibayar'] = $this->db->get('pu_kwitansi')->row()->nominal_dibayar;
        echo json_encode($data);
    }

    public function edit_data_kwitansi($id)
    {
        $data = $this->db->get_where('pu_kwitansi', ['id' => $id])->row();
        echo json_encode($data);
    }

    // UNTUK MENAMPILKAN FORM ADD
    public function add_form()
    {
        $data['id'] = 0;
        $data['title'] = 'backend/pu_invoice/pu_invoice_form';
        $data['title_view'] = 'Invoice Form';
        $data['rek_options'] = $this->M_pu_invoice->options()->result();
        $this->load->view('backend/home', $data);
    }

    public function get_kwitansi_dates($id_invoice)
    {
        $this->db->select('id, tanggal_pembayaran');
        $this->db->from('pu_kwitansi');
        $this->db->where('id_invoice', $id_invoice);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            echo json_encode($query->result_array());
        } else {
            echo json_encode(array(false, 'message' => 'No kwitansi found for this invoice.'));
        }
    }

    // MEREGENERATE KODE INVOICE
    public function generate_kode()
    {
        $date = $this->input->post('date');

        $kode = $this->M_pu_invoice->max_kode($date)->row();

        if (empty($kode->kode_invoice)) {
            $no_urut = 1;
        } else {
            $bln = substr($kode->kode_invoice, 5, 2);
            $no_urut = substr($kode->kode_invoice, 10) + 1;
        }
        $urutan = str_pad($no_urut, 4, "0", STR_PAD_LEFT);
        $year = substr($date, 8, 2);
        $month = substr($date, 3, 2);
        $data = 'INVPU' . $year . $month . $urutan;
        echo json_encode($data);
    }

    // UNTUK MENAMPILKAN FORM EDIT
    function edit_form($id)
    {
        // $data['notif'] = $this->M_notifikasi->pending_notification();
        $data['id'] = $id;
        $data['aksi'] = 'update';
        $data['title_view'] = "Edit Data Invoice";
        $data['title'] = 'backend/pu_invoice/pu_invoice_form';
        $data['rek_options'] = $this->M_pu_invoice->options()->result();
        $this->load->view('backend/home', $data);
    }

    function edit_data($id)
    {
        $data['master'] = $this->M_pu_invoice->get_by_id($id);
        $data['detail_invoice'] = $this->db->get_where('pu_detail_invoice', ['invoice_id' => $id])->result_array();
        echo json_encode($data);
    }

    function read_detail($id)
    {
        $data = $this->M_pu_invoice->get_by_id_detail($id);
        echo json_encode($data);
    }

    // MENAMBAHKAN DATA
    public function add()
    {
        // INSERT KODE PREPAYMENT SAAT SUBMIT
        $date = $this->input->post('tgl_invoice');

        $kode = $this->M_pu_invoice->max_kode($date)->row();

        if (empty($kode->kode_invoice)) {
            $no_urut = 1;
        } else {
            $bln = substr($kode->kode_invoice, 5, 2);
            $no_urut = substr($kode->kode_invoice, 10) + 1;
        }
        $urutan = str_pad($no_urut, 4, "0", STR_PAD_LEFT);
        $year = substr($date, 8, 2);
        $month = substr($date, 3, 2);

        $kode_invoice = 'INVPU' . $year . $month . $urutan;

        $data = array(
            'tgl_invoice' => date('Y-m-d', strtotime($this->input->post('tgl_invoice'))),
            'kode_invoice' => $kode_invoice,
            'tgl_tempo' => date('Y-m-d', strtotime($this->input->post('tgl_tempo'))),
            'ctc_nama' => $this->input->post('ctc_nama'),
            'ctc_alamat' => $this->input->post('ctc_alamat'),
            'detail_pesanan' => $this->input->post('pesanan_item'),
            'travel_id' => $this->input->post('rekening'),
            'created_at' => date('Y-m-d H:i:s')
        );

        if (!empty($this->input->post('jamaah_item'))) {
            $data['jamaah'] = $this->input->post('jamaah_item');
        } else {
            echo json_encode(array("status" => FALSE, "message" => "Jamaah tidak boleh kosong"));
        }

        if (!empty($_POST['catatan_item'])) {
            $data['keterangan'] = $this->input->post('catatan_item');
        }
        if (!empty($_POST['diskon'])) {
            $data['diskon'] = $this->input->post('diskon');
        }

        // INISIASI VARIABEL UNTUK MENGHITUNG TOTAL TAGIHAN
        $grand_total = 0;
        $deskripsi = $this->input->post('deskripsi[]');
        $jumlah = preg_replace('/\D/', '', $this->input->post('jumlah[]'));
        $harga = preg_replace('/\D/', '', $this->input->post('harga[]'));
        $satuan = $this->input->post('satuan[]');
        $total = preg_replace('/\D/', '', $this->input->post('total[]'));

        if (!empty($deskripsi) && !empty($jumlah) && !empty($satuan) && !empty($harga) && !empty($total)) {
            for ($i = 1; $i <= count($deskripsi); $i++) {
                $grand_total += (int)$total[$i]; // Menjumlahkan total
            }
            $data['total_tagihan'] = $grand_total;
        } else {
            echo json_encode(array("status" => FALSE, "message" => "Data detail invoice tidak boleh kosong"));
            exit();
        }

        $inserted = $this->M_pu_invoice->save($data);

        if ($inserted) {
            //PERULANGAN UNTUK INSER QUERY DETAIL PREPAYMENT
            if (!empty($deskripsi) && !empty($jumlah) && !empty($satuan) && !empty($harga) && !empty($total)) {
                for ($i = 1; $i <= count($deskripsi); $i++) {
                    $data3[] = array(
                        'invoice_id' => $inserted,
                        'deskripsi' => $deskripsi[$i],
                        'jumlah' => $jumlah[$i],
                        'satuan' => $satuan[$i],
                        'harga' => $harga[$i],
                        'total' => $total[$i]
                    );
                }
                $this->M_pu_invoice->save_detail2($data3);
            }
        }

        echo json_encode(array("status" => TRUE));
    }

    // ADD KWITANSI
    public function add_kwitansi()
    {
        $total_tagihan = $this->db->select('total_tagihan')
            ->from('pu_invoice')
            ->where('id', $this->input->post('invoice_id'))
            ->get()
            ->row('total_tagihan');

        $nominal_dibayar = preg_replace('/\D/', '', $this->input->post('nominal_dibayar'));

        // ERROR HANDLING UNTUK NOMINAL DIBAYAR
        if ($nominal_dibayar > $total_tagihan) {
            echo json_encode(array("status" => FALSE, "message" => "Nominal yang dibayarkan tidak sesuai dengan Invoice"));
            return;
        }

        $data = array(
            'id_invoice' => $this->input->post('invoice_id'),
            'tanggal_pembayaran' => date('Y-m-d', strtotime($this->input->post('tanggal_pembayaran'))),
            'status_pembayaran' => $this->input->post('status_pembayaran'),
            'nominal_dibayar' => $nominal_dibayar,
            'created_at' => date('Y-m-d H:i:s'),
        );

        if ($this->M_pu_invoice->save_kwitansi($data)) {
            // Update invoice setelah pembayaran
            $new_total = $total_tagihan - $nominal_dibayar;
            $invoice_update = array(
                'total_tagihan' => $new_total,
                'is_active' => 0,
                'status' => ($new_total == 0) ? 0 : 1,
            );

            $this->db->where('id', $this->input->post('invoice_id'));
            $this->db->update('pu_invoice', $invoice_update);

            echo json_encode(array("status" => TRUE, "message" => "Kwitansi berhasil disimpan"));
        } else {
            echo json_encode(array("status" => FALSE, "message" => "Data kwitansi tidak boleh kosong"));
        }
    }

    // UPDATE KWITANSI
    public function update_kwitansi()
    {
        $total_tagihan_now = $this->db->select('total_tagihan')
            ->from('pu_invoice')
            ->where('id', $this->input->post('invoice_id'))
            ->get()
            ->row('total_tagihan');

        $nominal_dibayar_ex = $this->db->select_sum('nominal_dibayar')
            ->from('pu_kwitansi')
            ->where('id', $this->input->post('tgl_update_pembayaran'))
            ->get()
            ->row('nominal_dibayar');

        $nominal_dibayar = preg_replace('/\D/', '', $this->input->post('nominal_dibayar'));
        if (empty($nominal_dibayar) || $nominal_dibayar == 0) {
            echo json_encode(array("status" => FALSE, "message" => "Nominal dibayar tidak boleh kosong atau 0"));
            return;
        }

        $data = array(
            'id_invoice' => $this->input->post('invoice_id'),
            'tanggal_pembayaran' => date('Y-m-d', strtotime($this->input->post('tanggal_pembayaran'))),
            'status_pembayaran' => $this->input->post('status_pembayaran'),
            'nominal_dibayar' => $nominal_dibayar,
        );

        $kwitansi_id = $this->input->post('tgl_update_pembayaran'); // diasumsikan sebagai ID kwitansi

        if (empty($kwitansi_id)) {
            echo json_encode(array("status" => FALSE, "message" => "ID kwitansi untuk update tidak ditemukan"));
            return;
        }

        $this->db->where('id', $kwitansi_id);
        $updated = $this->db->update('pu_kwitansi', $data);

        // Hitung ulang total_tagihan
        $new_total_tagihan = ($total_tagihan_now + $nominal_dibayar_ex) - $nominal_dibayar;

        if ($new_total_tagihan < 0) {
            echo json_encode(array("status" => FALSE, "message" => "Total tagihan tidak boleh negatif"));
            exit;
        }

        if ($updated) {
            $new_total = $new_total_tagihan;
            $invoice_update = array(
                'total_tagihan' => $new_total,
                'is_active' => 0,
                'status' => ($new_total == 0) ? 0 : 1,
                'is_active' => ($new_total == 0) ? 0 : 1,
            );

            $this->db->where('id', $this->input->post('invoice_id'));
            $this->db->update('pu_invoice', $invoice_update);

            echo json_encode(array("status" => TRUE, "message" => "Kwitansi berhasil diperbarui"));
        } else {
            echo json_encode(array("status" => FALSE, "message" => "Gagal memperbarui kwitansi"));
        }
    }


    // UPDATE DATA
    public function update()
    {
        // INSERT KODE PREPAYMENT SAAT SUBMIT
        $date = $this->input->post('tgl_invoice');

        $kode = $this->M_pu_invoice->max_kode($date)->row();

        if (empty($kode->kode_invoice)) {
            $no_urut = 1;
        } else {
            $bln = substr($kode->kode_invoice, 5, 2);
            $no_urut = substr($kode->kode_invoice, 10) + 1;
        }
        $urutan = str_pad($no_urut, 4, "0", STR_PAD_LEFT);
        $year = substr($date, 8, 2);
        $month = substr($date, 3, 2);

        $kode_invoice = 'INVPU' . $year . $month . $urutan;

        $data = array(
            'tgl_invoice' => date('Y-m-d', strtotime($this->input->post('tgl_invoice'))),
            'kode_invoice' => $kode_invoice,
            'tgl_tempo' => date('Y-m-d', strtotime($this->input->post('tgl_tempo'))),
            'ctc_nama' => $this->input->post('ctc_nama'),
            'ctc_alamat' => $this->input->post('ctc_alamat'),
            'detail_pesanan' => $this->input->post('pesanan_item'),
            'travel_id' => $this->input->post('rekening'),
            'created_at' => date('Y-m-d H:i:s')
        );

        if (!empty($this->input->post('jamaah_item'))) {
            $data['jamaah'] = $this->input->post('jamaah_item');
        } else {
            echo json_encode(array("status" => FALSE, "message" => "Jamaah tidak boleh kosong"));
        }

        if (!empty($_POST['catatan_item'])) {
            $data['keterangan'] = $this->input->post('catatan_item');
        }

        if (!empty($_POST['diskon'])) {
            $data['diskon'] = $this->input->post('diskon');
        }

        // INISIASI VARIABEL UNTUK MENGHITUNG TOTAL TAGIHAN
        $grand_total = 0;
        $deskripsi = $this->input->post('deskripsi[]');
        $jumlah = preg_replace('/\D/', '', $this->input->post('jumlah[]'));
        $harga = preg_replace('/\D/', '', $this->input->post('harga[]'));
        $satuan = $this->input->post('satuan[]');
        $total = preg_replace('/\D/', '', $this->input->post('total[]'));

        if (!empty($deskripsi) && !empty($jumlah) && !empty($satuan) && !empty($harga) && !empty($total)) {
            for ($i = 1; $i <= count($deskripsi); $i++) {
                $grand_total += (int)$total[$i]; // Menjumlahkan total
            }
            $data['total_tagihan'] = $grand_total;
        } else {
            echo json_encode(array("status" => FALSE, "message" => "Data detail invoice tidak boleh kosong"));
            exit();
        }

        //UPDATE DETAIL PREPAYMENT
        $id_detail = $this->input->post('hidden_id[]');
        // $invoice_id = $this->input->post('hidden_invoiceId[]');
        if ($this->db->update('pu_invoice', $data, ['id' => $this->input->post('id')])) {
            // UNTUK MENGHAPUS ROW YANG TELAH DIDELETE
            $deletedRows = json_decode($this->input->post('deleted_rows'), true);
            if (!empty($deletedRows)) {
                foreach ($deletedRows as $delRows) {
                    // Hapus row dari database berdasarkan ID
                    $this->db->where('id', $delRows);
                    $this->db->delete('pu_detail_invoice');
                }
            }

            //MELAKUKAN REPLACE DATA LAMA DENGAN YANG BARU
            for ($i = 1; $i <= count($_POST['deskripsi']); $i++) {
                // Set id menjadi NULL jika id_detail tidak ada atau kosong
                $id_invoice = !empty($id_detail[$i]) ? $id_detail[$i] : NULL;
                $data2[] = array(
                    'id' => $id_invoice,
                    'invoice_id' => $this->input->post('id'),
                    'deskripsi' => $deskripsi[$i],
                    'jumlah' => $jumlah[$i],
                    'satuan' => $satuan[$i],
                    'harga' => $harga[$i],
                    'total' => $total[$i]
                );
                // Menggunakan db->replace untuk memasukkan atau menggantikan data
                $this->db->replace('pu_detail_invoice', $data2[$i - 1]);
            }
        }
        echo json_encode(array("status" => TRUE));
    }

    // MENGHAPUS DATA
    function delete($id)
    {
        $this->M_pu_invoice->delete($id);
        echo json_encode(array("status" => TRUE));
    }

    //APPROVE DATA
    public function approve()
    {
        $data = array(
            'app_keterangan' => $this->input->post('app_keterangan'),
            'app_status' => $this->input->post('app_status'),
            'app_date' => date('Y-m-d H:i:s'),
        );

        // UPDATE STATUS DEKLARASI
        if ($this->input->post('app_status') === 'revised') {
            $data['status'] = 'revised';
        } elseif ($this->input->post('app_status') === 'approved') {
            $data['status'] = 'on-process';
        } elseif ($this->input->post('app_status') === 'rejected') {
            $data['status'] = 'rejected';
        }

        //UPDATE APPROVAL PERTAMA
        $this->db->where('id', $this->input->post('hidden_id'));
        $this->db->update('tbl_prepayment_pu', $data);

        echo json_encode(array("status" => TRUE));
    }

    function approve2()
    {
        $data = array(
            'app2_keterangan' => $this->input->post('app2_keterangan'),
            'app2_status' => $this->input->post('app2_status'),
            'app2_date' => date('Y-m-d H:i:s'),
        );

        // UPDATE STATUS DEKLARASI
        if ($this->input->post('app2_status') === 'revised') {
            $data['status'] = 'revised';
        } elseif ($this->input->post('app2_status') === 'approved') {
            $data['status'] = 'approved';
        } elseif ($this->input->post('app2_status') === 'rejected') {
            $data['status'] = 'rejected';
        }

        // UPDATE APPROVAL 2
        $this->db->where('id', $this->input->post('hidden_id'));
        $this->db->update('tbl_prepayment_pu', $data);

        echo json_encode(array("status" => TRUE));
    }

    // PRINTOUT TCPDF INVOICE
    public function generate_pdf_invoice($id)
    {
        // INISIAI VARIABLE
        $invoice = $this->M_pu_invoice->get_by_id($id);
        $invoice_details = $this->M_pu_invoice->get_detail($id);
        $invoice_rek = $this->db->where('id', $invoice->travel_id)->get('pu_travel')->row();
        $this->db->select_sum('nominal_dibayar');
        $this->db->where('id_invoice', $id);
        $total_nominal_dibayar = $this->db->get('pu_kwitansi')->row()->nominal_dibayar;

        // Initialize the TCPDF object
        $t_cpdf2 = new t_cpdf2('P', 'mm', 'A4', true, 'UTF-8', false);

        // Set document properties
        $t_cpdf2->SetCreator(PDF_CREATOR);
        $t_cpdf2->SetAuthor('Author Name');
        $t_cpdf2->SetTitle('Invoice Pengenumroh PDF');

        $t_cpdf2->SetMargins(15, 40, 15); // Margin kiri, atas (untuk header), kanan
        $t_cpdf2->SetAutoPageBreak(true, 15); // Penanganan otomatis margin bawah

        // Add a new page
        $t_cpdf2->AddPage();

        $t_cpdf2->SetY($t_cpdf2->getMargins()['top']);

        // Pilih font untuk isi
        $t_cpdf2->SetFont('Poppins-Bold', '', 15);
        $t_cpdf2->Cell(100, 10, 'PT. KOLABORASI PARA SAHABAT', 0, 0, 'L');

        $t_cpdf2->SetFont('Poppins-Bold', '', 22);
        $t_cpdf2->SetX(164);
        $t_cpdf2->Cell(100, 10, 'INVOICE', 0, 1, 'L');

        $t_cpdf2->SetFont('Poppins-Regular', '', 11);
        $t_cpdf2->SetX(114);
        $t_cpdf2->Cell(45, 9, 'No. Invoice', 0, 0, 'R');
        $t_cpdf2->Cell(20, 9, ':', 0, 0);
        $t_cpdf2->Cell(19, 9, $invoice->kode_invoice, 0, 0, 'R');

        $t_cpdf2->SetY($t_cpdf2->GetY());
        $t_cpdf2->SetX(114);
        $t_cpdf2->SetFont('Poppins-Regular', '', 10);
        $t_cpdf2->Cell(45, 20, 'Tanggal Invoice', 0, 0, 'R');
        $t_cpdf2->Cell(20, 20, ':', 0, 0);
        $t_cpdf2->Cell(19, 20, $this->tgl_indo(date('Y-m-j', strtotime($invoice->tgl_invoice))), 0, 0, 'R');
        $t_cpdf2->SetY($t_cpdf2->GetY() + 13);
        $t_cpdf2->SetX(114);
        $t_cpdf2->SetFont('Poppins-Bold', '', 14);
        $t_cpdf2->SetFont('Poppins-Regular', '', 10);
        $t_cpdf2->Cell(45, 5, 'Tanggal Jatuh Tempo', 0, 0, 'R');
        $t_cpdf2->Cell(20, 5, ':', 0, 0);
        $t_cpdf2->Cell(19, 5, $this->tgl_indo(date('Y-m-j', strtotime($invoice->tgl_tempo))), 0, 1, 'R');

        $total = 0;
        $diskon = $invoice->diskon;
        foreach ($invoice_details as $detail) {
            if ($diskon != 0) {
                $total += $detail->total - ($detail->total * $diskon / 100);
            } else {
                $total += $detail->total;
            }
        }

        $t_cpdf2->SetY($t_cpdf2->GetY() + 2);
        // HEADER DATA PEMESAN
        $t_cpdf2->SetFont('Poppins-Regular', '', 11);
        $t_cpdf2->SetFillColor(252, 118, 19);
        $t_cpdf2->SetTextColor(255, 255, 255);
        $t_cpdf2->SetX(15);
        $t_cpdf2->Cell(182, 10, 'Data Pemesan', 0, 0, 'L', true);
        $t_cpdf2->Cell(0, 10, 'Jumlah Yang Harus Dibayar', 0, 1, 'R', true);
        $t_cpdf2->SetTextColor(0, 0, 0);

        // CONTENT DDATA PEMESAN
        $t_cpdf2->SetFont('Poppins-Regular', '', 10);
        $t_cpdf2->SetY($t_cpdf2->GetY() + 2);
        $t_cpdf2->SetX(15);
        $t_cpdf2->Cell(0, 0, 'Kpd Yth.', 0, 1);

        $t_cpdf2->SetFont('Poppins-Regular', 'B', 15);
        $t_cpdf2->SetX(15);
        $t_cpdf2->Cell(0, 0, $invoice->ctc_nama, 0, 0);
        $t_cpdf2->Cell(0, 0, number_format($total, 0, ',', '.'), 0, 1, 'R');

        $t_cpdf2->SetFont('Poppins-Regular', '', 10);
        $t_cpdf2->SetY($t_cpdf2->GetY() + 1);
        $t_cpdf2->SetX(15);
        $t_cpdf2->Cell(0, 0, 'Alamat', 0, 0);
        $t_cpdf2->SetY($t_cpdf2->GetY());
        $t_cpdf2->SetX(29);
        $t_cpdf2->Cell(0, 0, ':', 0, 0);
        $t_cpdf2->SetY($t_cpdf2->GetY());
        $t_cpdf2->SetX(31);
        $t_cpdf2->MultiCell(58, 0, $invoice->ctc_alamat, 0, 'L');

        $t_cpdf2->SetY($t_cpdf2->GetY() + 2);
        // HEADER DETAIL JAMAAH
        $t_cpdf2->SetFont('Poppins-Regular', '', 11);
        $t_cpdf2->SetFillColor(252, 118, 19);
        $t_cpdf2->SetTextColor(255, 255, 255);
        $t_cpdf2->SetX(15);
        $t_cpdf2->Cell(182, 10, 'Data Jamaah', 0, 0, 'L', true);
        $t_cpdf2->Cell(0, 10, 'Detail Pesanan', 0, 1, 'R', true);
        $t_cpdf2->SetTextColor(0, 0, 0);

        // CONTENT DATA JAMAAH
        $t_cpdf2->SetFont('Poppins-Regular', '', 10);
        // $t_cpdf2->SetY($t_cpdf2->GetY() + 7);
        $t_cpdf2->writeHTMLCell(
            80,                    // Lebar sel
            0,                     // Tinggi sel (0 berarti tinggi dinamis)
            4,       // Posisi X
            $t_cpdf2->SetY($t_cpdf2->GetY() + 1.5),       // Posisi Y saat ini
            $invoice->jamaah,           // Konten HTML
            0,                     // Border (0 = tidak ada border)
            0,                     // Line break (1 = pindah ke baris baru setelah cell)
            false,                 // Fill (false = tidak ada latar belakang)
            true,                  // Auto padding
            'L',                   // Align (L = kiri)
            true                   // Konversi tag HTML
        );

        $t_cpdf2->SetFont('Poppins-Regular', '', 10);
        // $t_cpdf2->SetY($t_cpdf2->GetY() - 14);
        // $t_cpdf2->SetX(100);

        $t_cpdf2->writeHTMLCell(
            80,                    // Lebar sel
            0,                     // Tinggi sel (0 berarti tinggi dinamis)
            150,       // Posisi X
            $t_cpdf2->GetY(),       // Posisi Y saat ini
            $invoice->detail_pesanan,           // Konten HTML
            0,                     // Border (0 = tidak ada border)
            1,                     // Line break (1 = pindah ke baris baru setelah cell)
            false,                 // Fill (false = tidak ada latar belakang)
            true,                  // Auto padding
            'R',                   // Align (L = kiri)
            true                   // Konversi tag HTML
        );

        // $t_cpdf2->SetX(100);
        // $t_cpdf2->Cell(0, 0, 'Umroh 4 September 2025', 0, 1, 'R');
        // $t_cpdf2->SetX(100);
        // $t_cpdf2->Cell(0, 0, 'Plus City Tour Thaif - 9 Hari', 0, 1, 'R');
        // $t_cpdf2->SetX(100);
        // $t_cpdf2->Cell(0, 0, 'Kamar : Triple', 0, 1, 'R');

        $t_cpdf2->SetY($t_cpdf2->GetY() + 2);
        // HEADER DETAIL PEMESANAN
        $t_cpdf2->SetFont('Poppins-Regular', '', 11);
        $t_cpdf2->SetFillColor(252, 118, 19);
        $t_cpdf2->SetTextColor(255, 255, 255);
        $t_cpdf2->SetX(15);
        $t_cpdf2->Cell(182, 10, 'Detail Pemesanan', 0, 1, 'L', true);
        $t_cpdf2->SetTextColor(0, 0, 0);

        $tbl = <<<EOD
        <table border="1" cellpadding="3">
            <thead>
                <tr>
                    <th align="center" width="30%"><b>DESKRIPSI</b></th>
                    <th align="center" width="20%"><b>JUMLAH</b></th>
                    <th align="center" width="25%"><b>HARGA</b></th>
                    <th align="center" width="25%"><b>TOTAL</b></th>
                </tr>
            </thead>
            <tbody>
    EOD;
        foreach ($invoice_details as $detail) {
            $tbl .= '<tr>';
            $tbl .= '<td width="30%">' . $detail->deskripsi . '</td>';
            $tbl .= '<td width="20%" style="text-align: center">' . $detail->jumlah . '</td>';
            $tbl .= '<td width="25%" style="text-align: center">' . 'Rp. ' . number_format($detail->harga, 0, ',', '.') . '</td>';
            $tbl .= '<td width="25%" style="text-align: center">' . 'Rp. ' . number_format($detail->total, 0, ',', '.') . '</td>';
            $tbl .= '</tr>';
        }
        $tbl .= <<<EOD
    </tbody>
</table>
EOD;

        $t_cpdf2->writeHTMLCell(184, 0, 14, $t_cpdf2->GetY() + 4, $tbl, 0, 1, false, true, 'L', true);

        $table2 = <<<EOD
            <table>
            <tbody>
            EOD;

        // $table2 .= '<tr style="border: none;">';
        // $table2 .= '<td colspan="2"></td>';
        // $table2 .= '<td width="25%" style="border: 1px solid black; text-align: center"> <b>Diskon</b></td>';
        // $table2 .= '<td width="25%" style="border: 1px solid black; text-align: center"> ' . $invoice->diskon .  '%</td>';
        // $table2 .= '</tr>';
        $table2 .= '<tr style="border: none;">';
        $table2 .= '<td colspan="2" style="border: none;"></td>';
        $table2 .= '<td width="25%" style="border: 1px solid black; text-align: center"><b>Total</b></td>';
        $table2 .= '<td width="25%" style="border: 1px solid black; text-align: center"> ' . 'Rp. ' . number_format($total, 0, ',', '.') . '</td>';
        $table2 .= '</tr>';
        $table2 .= '<tr style="border: none;">';
        $table2 .= '<td colspan="2" style="border: none;"></td>';
        $table2 .= '<td width="25%" style="border: 1px solid black; text-align: center"><b>Total Dibayar</b></td>';
        $table2 .= '<td width="25%" style="border: 1px solid black; text-align: center"> - ' . 'Rp. ' . number_format($total_nominal_dibayar, 0, ',', '.') . '</td>';
        $table2 .= '</tr>';
        $table2 .= '<tr style="border: none;">';
        $table2 .= '<td colspan="2" style="border: none;"></td>';
        $table2 .= '<td width="25%" style="border: 1px solid black; text-align: center"><b>Sisa Tagihan</b></td>';
        $table2 .= '<td width="25%" style="border: 1px solid black; text-align: center">' . 'Rp. ' . number_format($total - $total_nominal_dibayar, 0, ',', '.') . '</td>';
        $table2 .= '</tr>';
        $table2 .=  <<<EOD
            <tbody>
            </table>
        EOD;

        $t_cpdf2->writeHTMLCell(184, 0, 14, $t_cpdf2->GetY(), $table2, 0, 1, false, true, 'L', true);

        $t_cpdf2->SetY($t_cpdf2->GetY() + 1);
        $t_cpdf2->SetX(13);
        $t_cpdf2->SetFont('Poppins-Bold', '', 11);
        $t_cpdf2->Cell(0, 8, 'Metode Pembayaran', 0, 1);
        $t_cpdf2->SetX(13);
        $t_cpdf2->SetFont('Poppins-Bold', '', 10);
        $t_cpdf2->Cell(0, 5, 'Bank : ' . $invoice_rek->nama_bank, 0, 1);
        $t_cpdf2->SetX(13);
        $t_cpdf2->Cell(0, 8, 'No. Rekening : ' . $invoice_rek->no_rek, 0, 1);

        $t_cpdf2->SetY($t_cpdf2->GetY() - 1);
        $t_cpdf2->SetX(13);

        $t_cpdf2->SetFont('Poppins-Bold', '', 11);
        $t_cpdf2->Cell(0, 5, 'a/n : ' . $invoice_rek->travel, 0, 1);

        $t_cpdf2->setY($t_cpdf2->GetY());
        $t_cpdf2->SetX(13);

        $t_cpdf2->SetFont('Poppins-Regular', '', 10);
        $t_cpdf2->Cell(19, 9, 'Catatan :', 0, 1);
        if ($invoice->keterangan != '') {
            $catatan = $invoice->keterangan;
        } else {
            $catatan = '';
        }

        $t_cpdf2->writeHTMLCell(0, 0, 13, $t_cpdf2->GetY(), $catatan, 0, 1, false, true, 'L', true);

        // Output PDF (tampilkan di browser)
        $t_cpdf2->Output('Invoice Pengenumroh.pdf', 'I'); // 'I' untuk menampilkan di browser
    }

    // TCPDF KWITANSI
    public function generate_pdf_kwitansi($id)
    {
        // INISIAI VARIABLE
        $kwitansi = $this->M_pu_invoice->get_kwitansi($id);
        $invoice = $this->M_pu_invoice->get_by_id($kwitansi->id_invoice);
        $invoice_details = $this->db->where('invoice_id', $kwitansi->id_invoice)->get('pu_detail_invoice')->result_array();
        $nominal_dibayar_rows = $this->db->select('tanggal_pembayaran, nominal_dibayar')
            ->from('pu_kwitansi')
            ->where('id_invoice', $invoice->id)
            ->where('id <=', $id)
            ->order_by('id', 'asc')
            ->get()
            ->result();
        // Jika ingin menjumlahkan semua nominal_dibayar:
        $total_dibayar = 0;
        foreach ($nominal_dibayar_rows as $row) {
            $total_dibayar += $row->nominal_dibayar;
        }

        // Initialize the TCPDF object
        $t_cpdf2 = new t_cpdf2('P', 'mm', 'A4', true, 'UTF-8', false);

        // Set document properties
        $t_cpdf2->SetCreator(PDF_CREATOR);
        $t_cpdf2->SetAuthor('Author Name');
        $t_cpdf2->SetTitle('Invoice Pengenumroh PDF');

        $t_cpdf2->SetMargins(15, 40, 15); // Margin kiri, atas (untuk header), kanan
        $t_cpdf2->SetAutoPageBreak(true, 15); // Penanganan otomatis margin bawah

        // Add a new page
        $t_cpdf2->AddPage();

        $t_cpdf2->SetY($t_cpdf2->getMargins()['top']);

        // Pilih font untuk isi
        $t_cpdf2->SetFont('Poppins-Bold', '', 15);
        $t_cpdf2->Cell(100, 10, 'BUKTI TERIMA PEMBAYARAN', 0, 1, 'L');

        $t_cpdf2->SetFont('Poppins-Regular', '', 11);
        // $t_cpdf2->SetX(114);
        $t_cpdf2->Cell(45, 9, 'No. Invoice', 0, 0, 'L');
        $t_cpdf2->Cell(20, 9, ':', 0, 0);
        $t_cpdf2->Cell(19, 9, $invoice->kode_invoice, 0, 0, 'L');

        $t_cpdf2->SetY($t_cpdf2->GetY());
        // $t_cpdf2->SetX(114);
        $t_cpdf2->SetFont('Poppins-Regular', '', 10);
        $t_cpdf2->Cell(45, 20, 'Tanggal Invoice', 0, 0, 'L');
        $t_cpdf2->Cell(20, 20, ':', 0, 0);
        $t_cpdf2->Cell(19, 20, $this->tgl_indo(date('Y-m-d', strtotime($invoice->tgl_invoice))), 0, 0, 'L');
        $t_cpdf2->SetY($t_cpdf2->GetY() + 13);
        // $t_cpdf2->SetX(114);
        $t_cpdf2->SetFont('Poppins-Bold', '', 14);
        $t_cpdf2->SetFont('Poppins-Regular', '', 10);
        $t_cpdf2->Cell(45, 5, 'Tanggal Jatuh Tempo', 0, 0, 'L');
        $t_cpdf2->Cell(20, 5, ':', 0, 0);
        $t_cpdf2->Cell(19, 5, $this->tgl_indo(date('Y-m-d', strtotime($invoice->tgl_tempo))), 0, 1, 'L');

        $t_cpdf2->SetY($t_cpdf2->GetY() + 2);
        // HEADER DATA PEMESAN
        $t_cpdf2->SetFont('Poppins-Regular', '', 11);
        $t_cpdf2->SetFillColor(252, 118, 19);
        $t_cpdf2->SetTextColor(255, 255, 255);
        $t_cpdf2->SetX(15);
        $t_cpdf2->Cell(139, 10, 'Data Pemesan', 0, 0, 'L', true);
        $t_cpdf2->Cell(43, 10, 'Status Pembayaran', 0, 1, 'L', true);
        $t_cpdf2->SetTextColor(0, 0, 0);

        // CONTENT DDATA PEMSAN
        $t_cpdf2->SetFont('Poppins-Regular', '', 10);
        $t_cpdf2->SetY($t_cpdf2->GetY() + 2);
        $t_cpdf2->SetX(15);
        $t_cpdf2->Cell(0, 0, 'Kpd Yth.', 0, 1);

        $t_cpdf2->SetFont('Poppins-Regular', 'B', 15);
        $t_cpdf2->SetX(15);
        $t_cpdf2->Cell(0, 0, $invoice->ctc_nama, 0, 0);
        $t_cpdf2->Cell(0, 0, $kwitansi->status_pembayaran, 0, 1, 'R');

        $t_cpdf2->SetY($t_cpdf2->GetY() + 2);
        $t_cpdf2->SetY($t_cpdf2->GetY() + 2);
        // HEADER DETAIL PEMESANAN
        $t_cpdf2->SetFont('Poppins-Regular', '', 11);
        $t_cpdf2->SetFillColor(252, 118, 19);
        $t_cpdf2->SetTextColor(255, 255, 255);
        $t_cpdf2->SetX(15);
        $t_cpdf2->Cell(182, 10, 'Detail Pemesanan', 0, 1, 'L', true);
        $t_cpdf2->SetTextColor(0, 0, 0);

        $tbl = <<<EOD
        <table border="1" cellpadding="3">
            <thead>
                <tr>
                    <th align="center" width="30%"><b>DESKRIPSI</b></th>
                    <th align="center" width="20%"><b>JUMLAH</b></th>
                    <th align="center" width="25%"><b>HARGA</b></th>
                    <th align="center" width="25%"><b>TOTAL</b></th>
                </tr>
            </thead>
            <tbody>
    EOD;
        foreach ($invoice_details as $detail) {
            $tbl .= '<tr>';
            $tbl .= '<td width="30%">' . $detail['deskripsi'] . '</td>';
            $tbl .= '<td width="20%" style="text-align: center">' . $detail['jumlah'] . '</td>';
            $tbl .= '<td width="25%" style="text-align: center">' . 'Rp. ' . number_format($detail['harga'], 0, ',', '.') . '</td>';
            $tbl .= '<td width="25%" style="text-align: center">' . 'Rp. ' . number_format($detail['total'], 0, ',', '.') . '</td>';
            $tbl .= '</tr>';
        }
        $tbl .= <<<EOD
    </tbody>
</table>
EOD;

        $t_cpdf2->writeHTMLCell(184, 0, 14, $t_cpdf2->GetY() + 4, $tbl, 0, 1, false, true, 'L', true);

        $table2 = <<<EOD
            <table>
            <tbody>
            EOD;

        $total = 0;
        $diskon = $invoice->diskon;
        foreach ($invoice_details as $detail) {
            if ($diskon != 0) {
                $total += $detail['total'] - ($detail['total'] * $diskon / 100);
            } else {
                $total += $detail['total'];
            }
        }

        $table2 .= '<tr style="border: none;">';
        $table2 .= '<td colspan="3" style="border: 1px solid black; text-align: center"><b>Sub Total</b></td>';
        $table2 .= '<td width="25%" style="border: 1px solid black; text-align: center"> ' . 'Rp. ' . number_format($total, 0, ',', '.') . '</td>';
        $table2 .= '</tr>';
        foreach ($nominal_dibayar_rows as $key) {
            $table2 .= '<tr style="border: none;">';
            $table2 .= '<td colspan="2" style="border: 1px solid black; text-align: center">Detail Pembayaran</td>';
            $table2 .= '<td width="25%" style="border: 1px solid black; text-align: center">' . $this->tgl_indo(date('Y-m-d', strtotime($key->tanggal_pembayaran))) . '</td>';
            $table2 .= '<td width="25%" style="border: 1px solid black; text-align: center"> ' . '- Rp. ' . number_format($key->nominal_dibayar, 0, ',', '.') . '</td>';
            $table2 .= '</tr>';
        }
        $table2 .= '<tr style="border: none;">';
        $table2 .= '<td colspan="3" style="border: 1px solid black; text-align: center"><b>Total bayar</b></td>';
        $table2 .= '<td width="25%" style="border: 1px solid black; text-align: center"> ' . 'Rp. ' . number_format($total_dibayar, 0, ',', '.') . '</td>';
        $table2 .= '</tr>';
        $table2 .= '<tr style="border: none;">';
        $table2 .= '<td colspan="2" style="border: none;"></td>';
        $table2 .= '<td width="25%" style="border: 1px solid black; text-align: center"><b>Sisa Tagihan</b></td>';
        $table2 .= '<td width="25%" style="border: 1px solid black; text-align: center"> ' . 'Rp. ' . number_format($total - $total_dibayar, 0, ',', '.') . '</td>';
        $table2 .= '</tr>';
        $table2 .=  <<<EOD
            <tbody>
            </table>
        EOD;

        $t_cpdf2->writeHTMLCell(184, 0, 14, $t_cpdf2->GetY(), $table2, 0, 1, false, true, 'L', true);

        $t_cpdf2->setY($t_cpdf2->GetY() + 5);
        $t_cpdf2->SetX(13);

        $t_cpdf2->SetFont('Poppins-Regular', '', 10);
        $t_cpdf2->Cell(19, 9, 'Catatan :', 0, 1);
        if ($invoice->keterangan != '') {
            $catatan = $invoice->keterangan;
        } else {
            $catatan = '';
        }

        $t_cpdf2->writeHTMLCell(0, 0, 13, $t_cpdf2->GetY(), $catatan, 0, 1, false, true, 'L', true);

        // Output PDF (tampilkan di browser)
        $t_cpdf2->Output('Invoice Pengenumroh.pdf', 'I'); // 'I' untuk menampilkan di browser
    }
}
