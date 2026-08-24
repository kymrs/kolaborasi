<?php
defined('BASEPATH') or exit('No direct script access allowed');
setlocale(LC_ALL, 'id_ID');

class Mac_inventory extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_mac_inventory');
        $this->load->model('backend/M_mac_inventory_stok');
        $this->load->model('backend/M_mac_peminjaman');
        $this->M_login->getsecurity();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        ($akses->view_level == 'N' ? redirect('auth') : '');

        if (empty($this->session->userdata('cabang_id'))) {
            echo "
            <script>
                alert('Cabang belum diatur. Silakan hubungi Admin untuk melanjutkan.');
                window.location.href = '" . site_url('dashboard') . "';
            </script>";
            exit;
        }
                    
        $nama_cabang = $this->db->select('nama_cabang')
            ->from('mac_cabang')
            ->where('id', $this->session->userdata('cabang_id'))
            ->get()
            ->row()
            ->nama_cabang;

        // Hilangkan prefix "MAC " dan ubah menjadi Title Case
        $nama_cabang = ucwords(strtolower(str_replace('MAC ', '', $nama_cabang)));

        $data['add']         = $akses->add_level;
        $data['title']       = 'backend/mac_inventory/mac_inventory_list';
        $data['is_nasional'] = $this->session->userdata('is_nasional') ? true : false;
        $data['cabang_id']   = $this->session->userdata('cabang_id');
    
        // Daftar cabang untuk dropdown filter (hanya dipakai Nasional)
        if ($data['is_nasional']) {
            $data['list_cabang'] = $this->db
                ->where('status', 'aktif')
                ->order_by('nama_cabang', 'ASC')
                ->get('mac_cabang')->result();
        } else {
            $data['list_cabang'] = [];
        }
    
        $this->load->view('backend/home', $data);
    }

    function get_list()
    {
        $is_nasional    = $this->session->userdata('is_nasional') ? true : false;
        $session_cabang = intval($this->session->userdata('cabang_id'));
        $filter_cabang  = intval($this->input->post('filter_cabang'));

        if ($is_nasional) {
            $use_cabang_id = ($filter_cabang > 0) ? $filter_cabang : null;
        } else {
            $use_cabang_id = $session_cabang;
        }

        $list = $this->M_mac_inventory->get_datatables($is_nasional, $use_cabang_id);
        $data = array();
        $no   = $_POST['start'];

        $akses  = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        $edit   = $akses->edit_level;
        $set_stok = $akses->upload_level;
        $delete = $akses->delete_level;

        foreach ($list as $field) {
            $action_edit   = ($edit   == 'Y') ? '<a onclick="open_modal(' . $field->id . ', ' . ($use_cabang_id ?: 0) . ')" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;' : '';
            $action_delete = ($delete == 'Y') ? '<a onclick="delete_data(\'' . $field->id . '\')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>' : '';
            $action_detail_cabang = $is_nasional ? '<a onclick="detail_stok_cabang(' . $field->id . ', \'' . htmlspecialchars($field->nama_produk) . '\')" class="btn btn-info btn-circle btn-sm" title="Stok per Cabang"><i class="fa fa-sitemap"></i></a>&nbsp;' : '';
            $action_set_stok = ($set_stok == 'Y') ? '<a onclick="set_stok_awal(' . $field->id . ', \'' . htmlspecialchars($field->nama_produk, ENT_QUOTES) . '\')" class="btn btn-success btn-circle btn-sm" title="Set Stok Awal">' . '<i class="fa fa-database"></i></a>&nbsp;' : '';

            $no++;
            $row   = array();
            $row[] = $no;
            if ($filter_cabang == 0) {
                $row[] = $action_edit . $action_detail_cabang . $action_set_stok . $action_delete;
            } else {
                $row[] = $action_edit . $action_set_stok . $action_delete;
            }
            $row[] = $field->kode_produk;
            $row[] = $field->nama_produk;
            $row[] = $field->kategori;
            $row[] = $field->satuan;

            if ($is_nasional && is_null($use_cabang_id)) {
                // Nasional semua cabang — strip harga
                $row[] = '<div class="text-center text-muted">-</div>';
                $row[] = '<div class="text-center text-muted">-</div>';
            } else {
                // Harga beli — dari batch FIFO terbaru cabang ini
                $row[] = !empty($field->harga_beli_cabang)
                    ? 'Rp ' . number_format($field->harga_beli_cabang, 0, ',', '.')
                    : '<span class="text-muted">-</span>';

                // Harga jual — override cabang atau fallback default Nasional
                $row[] = !empty($field->harga_jual_cabang)
                    ? 'Rp ' . number_format($field->harga_jual_cabang, 0, ',', '.')
                    : '<span class="text-muted">-</span>';
            }

            // Tampilkan stok dari cache mac_inventory_stok, bukan stok_awal
            // $stok_fisik = $this->M_mac_inventory_stok->get_stok($field->id, $is_nasional, $use_cabang_id);
            // $stok_efektif = $this->M_mac_inventory_stok->get_stok_efektif($field->id, $is_nasional, $use_cabang_id);

            $pinjam = $this->db->select('COALESCE(SUM(d.qty_pinjam - d.qty_kembali), 0) as total', FALSE)
                ->from('mac_peminjaman_detail d')
                ->join('mac_peminjaman p', 'p.id = d.peminjaman_id')
                ->where('d.inventory_id', $field->id)
                ->where('p.status', 'aktif')
                ->where('p.app_status', 'approved');

            if (!is_null($use_cabang_id)) {
                $pinjam->where('p.cabang_id', $use_cabang_id);
            }

            $pinjam       = floatval($pinjam->get()->row()->total);
            $stok_fisik   = floatval($field->stok_saat_ini);
            $stok_efektif = max(0, $stok_fisik - $pinjam);

            $badge2 = $stok_fisik <= 0
                ? '<span class="badge badge-danger" data-toggle="tooltip" title="Stok habis">'  . (int)$stok_fisik . '</span>'
                : ($stok_fisik < $field->stok_minimal_cabang
                    ? '<span class="badge badge-warning" data-toggle="tooltip" title="Stok menipis">' . (int)$stok_fisik . '</span>'
                    : '<span class="badge badge-success" data-toggle="tooltip" title="Stok aman">' . (int)$stok_fisik . '</span>');

            // $badge2 = (int)$field->stok_saat_ini <= 0
            //     ? '<span class="badge badge-danger">'  . (int)$field->stok_saat_ini . '</span>'
            //     : ($field->stok_saat_ini < $field->stok_minimal_cabang
            //         ? '<span class="badge badge-warning">' . (int)$field->stok_saat_ini . '</span>'
            //         : '<span class="badge badge-success">' . (int)$field->stok_saat_ini . '</span>');

            $row[] = $badge2;

            $badge2 = $stok_efektif <= 0
                ? '<span class="badge badge-danger" data-toggle="tooltip" title="Stok habis">'  . (int)$stok_efektif . '</span>'
                : ($stok_efektif < $stok_fisik
                    ? '<span class="badge badge-warning" data-toggle="tooltip" title="Sebagian stok sedang dipinjam">' . (int)$stok_efektif . '</span>'
                    : '<span class="badge badge-success" data-toggle="tooltip" title="Stok aman">' . (int)$stok_efektif . '</span>');
            $row[] = $badge2;

            $row[] = date('d-m-Y H:i:s', strtotime($field->created_at)) . ' | ' . ucwords($field->created_by);
            $data[] = $row;
        }

        echo json_encode(array(
            "draw"            => $_POST['draw'],
            "recordsTotal"    => $this->M_mac_inventory->count_all(),
            "recordsFiltered" => $this->M_mac_inventory->count_filtered($is_nasional, $use_cabang_id),
            "data"            => $data,
        ));
    }

    function add_form()
    {
        $data['id']         = 0;
        $data['title_view'] = 'Tambah Barang';
        $data['title']      = 'backend/mac_inventory/mac_inventory_form';
        $this->load->view('backend/home', $data);
    }

    function edit_form($id)
    {
        $data['id']         = $id;
        $data['title_view'] = 'Edit Barang';
        $data['title']      = 'backend/mac_inventory/mac_inventory_form';
        $this->load->view('backend/home', $data);
    }

    function get_data($id)
    {
        $master = $this->M_mac_inventory->get_by_id($id);
        if (!$master) { echo json_encode([]); return; }

        $master->stok_aktual = $this->M_mac_inventory_stok->get_stok($id);

        $is_nasional    = $this->session->userdata('is_nasional') ? true : false;
        $session_cabang = intval($this->session->userdata('cabang_id'));
        $filter_cabang  = intval($this->input->get('filter_cabang'));

        // Jika nasional dan ada filter cabang aktif → pakai filter
        // Jika nasional tanpa filter → pakai cabang_id=1 (Nasional)
        // Jika cabang biasa → pakai session cabang sendiri
        if ($is_nasional && $filter_cabang > 0) {
            $cabang_id = $filter_cabang;
        } else {
            $cabang_id = $session_cabang;
        }

        $this->db->reset_query();

        $override = $this->db
            ->select('harga_jual, stok_minimal')
            ->from('mac_inventory_cabang')
            ->where('inventory_id', (int) $id)
            ->where('cabang_id', $cabang_id)
            ->get()->row();

        $master->harga_jual_tampil   = ($override && !is_null($override->harga_jual))
            ? floatval($override->harga_jual)
            : null;

        $master->stok_minimal_tampil = ($override && !is_null($override->stok_minimal))
            ? intval($override->stok_minimal)
            : null;

        echo json_encode($master);
    }

    // ========== SAVE BARANG ==========
    public function add()
    {
        $data = array(
            'kode_produk'  => $this->input->post('kode_produk'),
            'nama_produk'  => ucwords(strtolower($this->input->post('nama_produk'))),
            'kategori'     => $this->input->post('kategori'),
            'satuan'       => $this->input->post('satuan'),
            'is_active'    => $this->input->post('is_active'),
            'created_at'   => date('Y-m-d H:i:s'),
            'created_by'   => $this->session->userdata('username'),
            'updated_at'   => date('Y-m-d H:i:s'),
        );

        $id = $this->M_mac_inventory->save($data);

        if (!$id) {
            echo json_encode(['status' => FALSE, 'error' => 'Gagal menyimpan data']);
            return;
        }

        echo json_encode(['status' => TRUE, 'message' => 'Data berhasil disimpan']);
    }

    // ========== UPDATE BARANG ==========
    public function update()
    {
        $id          = $this->input->post('id');
        $is_nasional = $this->session->userdata('is_nasional') ? true : false;
        $session_cabang = intval($this->session->userdata('cabang_id'));
        $filter_cabang  = intval($this->input->post('filter_cabang'));

        // Tentukan cabang_id yang dipakai untuk update mac_inventory_cabang
        // Jika nasional + ada filter cabang aktif → update cabang yang dipilih
        // Jika nasional tanpa filter → update cabang Nasional (id=1)
        // Jika cabang biasa → update cabang sendiri
        if ($is_nasional) {
            $cabang_id_update = $filter_cabang > 0 ? $filter_cabang : $session_cabang;
        } else {
            $cabang_id_update = $session_cabang;
        }

        if ($is_nasional) {
            // Nasional — update field master di mac_inventory (tanpa harga_jual & stok_minimal)
            $data = array(
                'kode_produk' => $this->input->post('kode_produk'),
                'nama_produk' => $this->input->post('nama_produk'),
                'kategori'    => $this->input->post('kategori'),
                'satuan'      => $this->input->post('satuan'),
                'is_active'   => $this->input->post('is_active'),
                'updated_at'  => date('Y-m-d H:i:s'),
            );
            $this->M_mac_inventory->update_barang($id, $data);
        }

        // Nasional dan Cabang — sama-sama update harga_jual & stok_minimal
        // ke mac_inventory_cabang sesuai cabang yang aktif
        $harga_jual   = intval(str_replace('.', '', $this->input->post('harga_jual')));
        $stok_minimal = (int)$this->input->post('stok_minimal');

        $existing = $this->db
            ->where('inventory_id', $id)
            ->where('cabang_id', $cabang_id_update)
            ->get('mac_inventory_cabang')->row();

        if ($existing) {
            $this->db->where('inventory_id', $id)
                ->where('cabang_id', $cabang_id_update)
                ->update('mac_inventory_cabang', [
                    'harga_jual'   => $harga_jual > 0 ? $harga_jual : null,
                    'stok_minimal' => $stok_minimal > 0 ? $stok_minimal : null,
                    'updated_by'   => $this->session->userdata('id_user'),
                    'updated_at'   => date('Y-m-d H:i:s'),
                ]);
        } else {
            $this->db->insert('mac_inventory_cabang', [
                'inventory_id' => $id,
                'cabang_id'    => $cabang_id_update,
                'harga_jual'   => $harga_jual > 0 ? $harga_jual : null,
                'stok_minimal' => $stok_minimal > 0 ? $stok_minimal : null,
                'updated_by'   => $this->session->userdata('id_user'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ]);
        }

        echo json_encode(['status' => TRUE, 'message' => 'Data berhasil diupdate']);
    }

    // ----------------------------------------------------------------
    // GET STOK AWAL CABANG — load data ke modal
    // ----------------------------------------------------------------
    public function get_stok_awal_cabang()
    {
        $inventory_id   = intval($this->input->post('inventory_id'));
        $is_nasional    = $this->session->userdata('is_nasional') ? true : false;
        $session_cabang = intval($this->session->userdata('cabang_id'));

        $q = $this->db->select('
                c.id as cabang_id,
                c.nama_cabang,
                c.kode,
                COALESCE(s.stok_saat_ini, 0) as stok_saat_ini,
                COALESCE(ic.harga_jual, 0) as harga_jual,
                COALESCE(ic.stok_minimal, 0) as stok_minimal,
                (
                    SELECT ib.harga_beli
                    FROM mac_inventory_batch ib
                    WHERE ib.inventory_id = '.$inventory_id.'
                    AND ib.cabang_id = c.id
                    ORDER BY ib.tanggal_masuk DESC, ib.id DESC
                    LIMIT 1
                ) AS harga_beli
            ', FALSE)
            ->from('mac_cabang c')
            ->join(
                'mac_inventory_stok s',
                's.cabang_id = c.id AND s.inventory_id = '.$inventory_id,
                'left'
            )
            ->join(
                'mac_inventory_cabang ic',
                'ic.cabang_id = c.id AND ic.inventory_id = '.$inventory_id,
                'left'
            )
            ->where('c.status', 'aktif')
            ->where('c.id !=', 1);

        if (!$is_nasional) {
            $q->where('c.id', $session_cabang);
        }

        $rows = $q->order_by('c.nama_cabang', 'ASC')
                ->get()
                ->result_array();

        echo json_encode([
            'status' => TRUE,
            'rows'   => $rows
        ]);
    }

    // ----------------------------------------------------------------
    // SAVE STOK AWAL — simpan per cabang via batch FIFO
    // ----------------------------------------------------------------
    public function save_stok_awal()
    {
        $inventory_id   = intval($this->input->post('inventory_id'));
        $is_nasional    = $this->session->userdata('is_nasional') ? true : false;
        $session_cabang = intval($this->session->userdata('cabang_id'));

        $cabang_ids    = $this->input->post('cabang_id')    ?: [];
        $stok_awals    = $this->input->post('stok_awal')    ?: [];
        $harga_belis   = $this->input->post('harga_beli')   ?: [];
        $harga_juals   = $this->input->post('harga_jual')   ?: [];
        $stok_minimals = $this->input->post('stok_minimal') ?: [];

        $errors  = [];
        $success = 0;

        foreach ($cabang_ids as $i => $cabang_id) {
            $cabang_id    = intval($cabang_id);
            $stok_awal    = floatval($stok_awals[$i]  ?? 0);
            $harga_beli   = floatval(str_replace('.', '', $harga_belis[$i]   ?? 0));
            $harga_jual   = intval(str_replace('.', '',  $harga_juals[$i]    ?? 0));
            $stok_minimal = intval($stok_minimals[$i] ?? 0);

            if ($cabang_id <= 0) continue;

            // Guard: cabang biasa hanya bisa set milik sendiri
            if (!$is_nasional && $cabang_id !== $session_cabang) continue;

            // Skip baris stok awal tidak diisi
            if ($stok_awal <= 0) continue;

            // Guard: tolak jika stok cabang ini sudah ada
            $existing_stok = $this->db
                ->where('inventory_id', $inventory_id)
                ->where('cabang_id', $cabang_id)
                ->get('mac_inventory_stok')->row();

            if ($existing_stok && floatval($existing_stok->stok_saat_ini) > 0) {
                $nm = $this->db->select('nama_cabang')
                    ->where('id', $cabang_id)
                    ->get('mac_cabang')->row();
                $errors[] = ($nm ? $nm->nama_cabang : 'Cabang ' . $cabang_id)
                    . ' sudah punya stok, gunakan Pelaporan untuk menambah stok.';
                continue;
            }

            // Simpan stok via batch FIFO
            $result = $this->M_mac_inventory_stok->tambah_stok_masuk(
                $inventory_id,
                $cabang_id,
                $stok_awal,
                $harga_beli,
                date('Y-m-d'),
                'Stok Awal',
                $inventory_id,
                $this->session->userdata('id_user')
            );

            if (!$result) {
                $errors[] = 'Gagal simpan stok untuk cabang ID ' . $cabang_id;
                continue;
            }

            // Simpan harga_jual dan stok_minimal ke mac_inventory_cabang
            // Hanya jika salah satu diisi
            if ($harga_jual > 0 || $stok_minimal > 0) {
                $existing_cabang = $this->db
                    ->where('inventory_id', $inventory_id)
                    ->where('cabang_id', $cabang_id)
                    ->get('mac_inventory_cabang')->row();

                $data_cabang = [
                    'harga_jual'   => $harga_jual   > 0 ? $harga_jual   : null,
                    'stok_minimal' => $stok_minimal > 0 ? $stok_minimal : null,
                    'updated_by'   => $this->session->userdata('id_user'),
                    'updated_at'   => date('Y-m-d H:i:s'),
                ];

                if ($existing_cabang) {
                    $this->db->where('inventory_id', $inventory_id)
                        ->where('cabang_id', $cabang_id)
                        ->update('mac_inventory_cabang', $data_cabang);
                } else {
                    $data_cabang['inventory_id'] = $inventory_id;
                    $data_cabang['cabang_id']    = $cabang_id;
                    $this->db->insert('mac_inventory_cabang', $data_cabang);
                }
            }

            $success++;
        }

        echo json_encode([
            'status'  => $success > 0,
            'message' => $success > 0 ? $success . ' cabang berhasil di-set.' : '',
            'warning' => !empty($errors) ? implode('<br>', $errors) : '',
        ]);
    }

    function delete($id)
    {
        $this->M_mac_inventory->delete_barang($id);
        echo json_encode(array("status" => TRUE));
    }

    public function generate_kode()
    {
        $kategori = $this->input->post('kategori');
        if (empty($kategori)) {
            echo json_encode(['status' => FALSE, 'message' => 'Kategori harus dipilih']);
            return;
        }
        $kode = $this->M_mac_inventory->generate_kode_produk($kategori);
        echo $kode
            ? json_encode(['status' => TRUE, 'kode' => $kode])
            : json_encode(['status' => FALSE, 'message' => 'Kategori tidak valid']);
    }

    function mutasi_form($id)
    {
        $data['id']         = $id;
        $data['title_view'] = 'Mutasi Stok';
        $data['title']      = 'backend/mac_inventory/mac_transaksi';
        $this->load->view('backend/home', $data);
    }

    public function mutasi_save()
    {
        $id         = $this->input->post('inventory_id');
        $tipe       = $this->input->post('tipe');
        $jumlah     = (int)$this->input->post('jumlah');
        $referensi  = $this->input->post('referensi');
        $keterangan = $this->input->post('keterangan');

        if ($jumlah <= 0) {
            echo json_encode(['status' => FALSE, 'error' => 'Jumlah harus lebih dari 0']);
            return;
        }

        // Ambil stok dari cache
        $stok_sekarang = $this->M_mac_inventory_stok->get_stok($id);

        if ($tipe == 'Keluar' && $jumlah > $stok_sekarang) {
            echo json_encode(['status' => FALSE, 'error' => 'Stok tidak mencukupi. Stok saat ini: ' . $stok_sekarang]);
            return;
        }

        $stok_sesudah = ($tipe == 'Masuk')
            ? $stok_sekarang + $jumlah
            : $stok_sekarang - $jumlah;

        $this->M_mac_inventory->add_mutasi([
            'inventory_id' => $id,
            'tipe'         => $tipe,
            'jumlah'       => $jumlah,
            'stok_sebelum' => $stok_sekarang,
            'stok_sesudah' => $stok_sesudah,
            'referensi'    => $referensi,
            'keterangan'   => $keterangan,
            'transaksi_date' => date('Y-m-d H:i:s'),
            'created_at'   => date('Y-m-d H:i:s'),
            'created_by'   => $this->session->userdata('id_user'),
        ]);

        // Update cache stok setelah mutasi manual
        $this->db->where('inventory_id', $id)
            ->update('mac_inventory_stok', [
                'stok_saat_ini' => $stok_sesudah,
                'updated_at'    => date('Y-m-d H:i:s'),
            ]);

        $this->M_mac_inventory->update_barang($id, ['updated_at' => date('Y-m-d H:i:s')]);

        echo json_encode([
            'status'       => TRUE,
            'message'      => 'Mutasi stok berhasil dicatat',
            'stok_sesudah' => $stok_sesudah,
        ]);
    }

    // ----------------------------------------------------------------
    // get_stok_per_cabang() — untuk modal detail Nasional
    // ----------------------------------------------------------------
    public function get_stok_per_cabang()
    {
        $inventory_id = intval($this->input->post('inventory_id'));

        if (!$inventory_id) {
            echo json_encode([
                'status' => FALSE
            ]);
            return;
        }

        $rows = $this->db->select('
                c.id as cabang_id,
                c.kode,
                c.nama_cabang,
                COALESCE(s.stok_saat_ini, 0) as stok_saat_ini,
                COALESCE(ic.stok_minimal, 0) as stok_minimal,
                i.satuan
            ', FALSE)
            ->from('mac_cabang c')
            ->join(
                'mac_inventory_stok s',
                's.cabang_id = c.id AND s.inventory_id = ' . (int)$inventory_id,
                'left'
            )
            ->join(
                'mac_inventory_cabang ic',
                'ic.cabang_id = c.id AND ic.inventory_id = ' . (int)$inventory_id,
                'left'
            )
            ->join(
                'mac_inventory i',
                'i.id = ' . (int)$inventory_id,
                'left'
            )
            ->where('c.status', 'aktif')
            ->where('c.id !=', 1)
            ->order_by('c.nama_cabang', 'ASC')
            ->get()
            ->result_array();

        $total_stok = array_sum(array_column($rows, 'stok_saat_ini'));

        echo json_encode([
            'status'     => TRUE,
            'rows'       => $rows,
            'total_stok' => $total_stok,
        ]);
    }

    public function get_stok_menipis()
    {
        $is_nasional    = $this->session->userdata('is_nasional') ? true : false;
        $session_cabang = intval($this->session->userdata('cabang_id'));

        if ($is_nasional) {
            // Nasional: tampil per cabang, bukan SUM
            // Agar bisa lihat cabang mana yang stoknya menipis
            $data = $this->db->select('
                    i.id, i.kode_produk, i.nama_produk, i.kategori, i.satuan,
                    c.nama_cabang,
                    COALESCE(ic.stok_minimal, 0) as stok_minimal,
                    COALESCE(s.stok_saat_ini, 0) as stok_saat_ini
                ', FALSE)
                ->from('mac_inventory i')
                ->join('mac_inventory_stok s', 's.inventory_id = i.id', 'left')
                ->join('mac_cabang c', 'c.id = s.cabang_id', 'left')
                ->join('mac_inventory_cabang ic',
                    'ic.inventory_id = i.id AND ic.cabang_id = s.cabang_id',
                    'left')
                ->where('i.is_active', 1)
                ->where('c.id !=', 1) // exclude Nasional
                ->where('COALESCE(s.stok_saat_ini, 0) <= COALESCE(ic.stok_minimal, 0)', NULL, FALSE)
                ->order_by('c.nama_cabang', 'ASC')
                ->order_by('stok_saat_ini', 'ASC')
                ->get()->result();

        } else {
            // Cabang: hanya stok cabang sendiri
            $data = $this->db->select('
                    i.id, i.kode_produk, i.nama_produk, i.kategori, i.satuan,
                    NULL as nama_cabang,
                    COALESCE(ic.stok_minimal, 0) as stok_minimal,
                    COALESCE(s.stok_saat_ini, 0) as stok_saat_ini
                ', FALSE)
                ->from('mac_inventory i')
                ->join('mac_inventory_stok s',
                    's.inventory_id = i.id AND s.cabang_id = ' . $session_cabang,
                    'left')
                ->join('mac_inventory_cabang ic',
                    'ic.inventory_id = i.id AND ic.cabang_id = ' . $session_cabang,
                    'left')
                ->where('i.is_active', 1)
                ->where('COALESCE(s.stok_saat_ini, 0) <= COALESCE(ic.stok_minimal, 0)', NULL, FALSE)
                ->order_by('stok_saat_ini', 'ASC')
                ->get()->result();
        }

        echo json_encode(['data' => $data, 'is_nasional' => $is_nasional]);
    }

    function log($id)
    {
        $data['id']         = $id;
        $data['title_view'] = 'Riwayat Mutasi Stok';
        $data['title']      = 'backend/mac_inventory/mac_inventory_log';
        $this->load->view('backend/home', $data);
    }

    function get_log($id)
    {
        echo json_encode([
            'barang'      => $this->M_mac_inventory->get_by_id($id),
            'stok_aktual' => $this->M_mac_inventory_stok->get_stok($id),
            'mutasi'      => $this->M_mac_inventory->get_mutasi($id),
        ]);
    }

    public function export_stok_per_cabang($inventory_id)
    {
        // Load PhpSpreadsheet
        require APPPATH . '../vendor/autoload.php';

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Ambil nama barang
        $barang = $this->db
            ->select('nama_produk')
            ->where('id', $inventory_id)
            ->get('mac_inventory')
            ->row();

        $nama_barang = $barang ? $barang->nama_produk : '-';

        // Ambil data stok
        $rows = $this->db
            ->select('
                c.nama_cabang,
                c.kode,
                s.stok_saat_ini,
                i.stok_minimal,
                i.satuan
            ')
            ->from('mac_inventory_stok s')
            ->join('mac_cabang c', 'c.id = s.cabang_id')
            ->join('mac_inventory i', 'i.id = s.inventory_id')
            ->where('s.inventory_id', $inventory_id)
            ->order_by('c.nama_cabang', 'ASC')
            ->get()
            ->result();

        // ===========================
        // Judul
        // ===========================

        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue('A1', 'LAPORAN STOK PER CABANG');

        $sheet->mergeCells('A2:F2');
        $sheet->setCellValue('A2', $nama_barang);

        // Header
        $sheet->setCellValue('A4', 'No');
        $sheet->setCellValue('B4', 'Cabang');
        $sheet->setCellValue('C4', 'Kode');
        $sheet->setCellValue('D4', 'Stok');
        $sheet->setCellValue('E4', 'Stok Minimal');
        $sheet->setCellValue('F4', 'Status');

        // Style Header
        $sheet->getStyle('A4:F4')->getFont()->setBold(true);

        $baris = 5;
        $no = 1;
        $total = 0;

        foreach ($rows as $r) {

            $stok = (float)$r->stok_saat_ini;
            $minimal = (float)$r->stok_minimal;

            if ($stok <= 0) {
                $status = 'Habis';
            } elseif ($stok <= $minimal) {
                $status = 'Menipis';
            } else {
                $status = 'Aman';
            }

            $sheet->setCellValue('A'.$baris, $no++);
            $sheet->setCellValue('B'.$baris, $r->nama_cabang);
            $sheet->setCellValue('C'.$baris, $r->kode);
            $sheet->setCellValue('D'.$baris, $stok.' '.$r->satuan);
            $sheet->setCellValue('E'.$baris, $minimal.' '.$r->satuan);
            $sheet->setCellValue('F'.$baris, $status);

            $total += $stok;
            $baris++;
        }

        // Total
        $sheet->mergeCells('A'.$baris.':C'.$baris);
        $sheet->setCellValue('A'.$baris, 'TOTAL STOK NASIONAL');
        $sheet->setCellValue('D'.$baris, $total.' '.($rows ? $rows[0]->satuan : ''));

        // Border
        $sheet->getStyle('A4:F'.$baris)
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(
                \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
            );

        // Auto Width
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Download
        $filename = 'Stok_Per_Cabang_'.$nama_barang.'_'.date('Ymd_His').'.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}