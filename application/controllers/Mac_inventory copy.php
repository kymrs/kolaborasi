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
        $data['title']     = 'backend/mac_inventory/mac_inventory_list';
        $data['titleview'] = 'Data Inventory';
        $this->load->view('backend/home', $data);
    }

    function get_list()
    {
        $list = $this->M_mac_inventory->get_datatables();
        $data = array();
        $no   = $_POST['start'];

        $cabang_id = $this->session->userdata('cabang_id');

        $akses  = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        $edit   = $akses->edit_level;
        $delete = $akses->delete_level;

        foreach ($list as $field) {
            $action_edit   = ($edit   == 'Y') ? '<a onclick="open_modal(' . $field->id . ')" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;' : '';
            $action_delete = ($delete == 'Y') ? '<a onclick="delete_data(\'' . $field->id . '\')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;' : '';

            $no++;
            $row   = array();
            $row[] = $no;
            $row[] = $action_edit . $action_delete;
            $row[] = $field->kode_produk;
            $row[] = $field->nama_produk;
            $row[] = $field->kategori;
            $row[] = $field->satuan;
            $row[] = 'Rp ' . number_format($field->harga_beli, 0, ',', '.');
            $row[] = 'Rp ' . number_format($field->harga_jual, 0, ',', '.');
            // $row[] = $field->stok_awal;

            // Tampilkan stok dari cache mac_inventory_stok, bukan stok_awal
            $stok_fisik   = $this->M_mac_inventory_stok->get_stok($field->id);
            $stok_efektif = $this->M_mac_inventory_stok->get_stok_efektif($field->id, $cabang_id);

            $badge2 = $stok_fisik <= 0
                ? '<span class="badge badge-danger">'  . (int)$stok_fisik . '</span>'
                : ($stok_fisik < $field->stok_minimal
                    ? '<span class="badge badge-warning">' . (int)$stok_fisik . '</span>'
                    : '<span class="badge badge-success">' . (int)$stok_fisik . '</span>');

            $row[] = $badge2;

            $badge2 = $stok_efektif <= 0
                ? '<span class="badge badge-danger">'  . (int)$stok_efektif . '</span>'
                : ($stok_efektif < $stok_fisik
                    ? '<span class="badge badge-warning">' . (int)$stok_efektif . '</span>'
                    : '<span class="badge badge-success">' . (int)$stok_efektif . '</span>');
            $row[] = $badge2;

            $row[] = date('d-m-Y H:i:s', strtotime($field->created_at));
            $data[] = $row;
        }

        echo json_encode(array(
            "draw"            => $_POST['draw'],
            "recordsTotal"    => $this->M_mac_inventory->count_all(),
            "recordsFiltered" => $this->M_mac_inventory->count_filtered(),
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
        // Ambil stok dari cache, bukan kalkulasi manual
        $master->stok_aktual = $this->M_mac_inventory_stok->get_stok($id);
        echo json_encode($master);
    }

    // ========== SAVE BARANG ==========
    public function add()
    {
        $harga_beli = str_replace('.', '', $this->input->post('harga_beli'));
        $stok_awal  = (int)$this->input->post('stok_awal');

        $data = array(
            'kode_produk'  => $this->input->post('kode_produk'),
            'nama_produk'  => $this->input->post('nama_produk'),
            'kategori'     => $this->input->post('kategori'),
            'satuan'       => $this->input->post('satuan'),
            'harga_beli'   => $harga_beli,
            'harga_jual'   => str_replace('.', '', $this->input->post('harga_jual')),
            'stok_awal'    => $stok_awal,
            'stok_minimal' => (int)$this->input->post('stok_minimal'),
            'is_active'    => $this->input->post('is_active'),
            'created_at'   => date('Y-m-d H:i:s'),
            'updated_at'   => date('Y-m-d H:i:s'),
        );

        $id = $this->M_mac_inventory->save($data);

        if (!$id) {
            echo json_encode(['status' => FALSE, 'error' => 'Gagal menyimpan data']);
            return;
        }

        // ----------------------------------------------------------------
        // BARU: Jika stok_awal > 0, catat ke batch + ledger + cache stok
        // lewat M_mac_inventory_stok (bukan add_mutasi langsung).
        // Ini memastikan stok awal punya jejak batch FIFO yang lengkap.
        // ----------------------------------------------------------------
        $cabang_id = $this->session->userdata('cabang_id') ?: 1;
        if ($stok_awal > 0) {
            $this->M_mac_inventory_stok->tambah_stok_masuk(
                $id,                                    // inventory_id
                'Stok Awal',                            // kode referensi
                $stok_awal,                             // qty
                floatval($harga_beli),                  // harga_beli
                floatval(str_replace('.', '', $this->input->post('harga_jual'))),                  // harga_jual
                date('Y-m-d'),                          // tanggal masuk
                'Master Inventory',                     // referensi_tipe
                $id,                                    // referensi_id = id barang
                $this->session->userdata('username'),   // created_by
                $cabang_id                              // cabang id
            );
        }
        // ----------------------------------------------------------------

        echo json_encode(['status' => TRUE, 'message' => 'Data berhasil disimpan']);
    }

    // ========== UPDATE BARANG ==========
    public function update()
    {
        $id = $this->input->post('id');
        $data = array(
            'kode_produk'  => $this->input->post('kode_produk'),
            'nama_produk'  => $this->input->post('nama_produk'),
            'kategori'     => $this->input->post('kategori'),
            'satuan'       => $this->input->post('satuan'),
            'harga_beli'   => str_replace('.', '', $this->input->post('harga_beli')),
            'harga_jual'   => str_replace('.', '', $this->input->post('harga_jual')),
            'stok_minimal' => (int)$this->input->post('stok_minimal'),
            'is_active'    => $this->input->post('is_active'),
            'updated_at'   => date('Y-m-d H:i:s'),
        );

        // stok_awal tidak diubah via edit — perubahan stok hanya lewat
        // Pelaporan (barang masuk) atau Invoice (barang keluar)
        $this->M_mac_inventory->update_barang($id, $data);
        echo json_encode(['status' => TRUE, 'message' => 'Data berhasil diupdate']);
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

    public function get_stok_menipis()
    {
        $data = $this->db->select('
                i.id, i.kode_produk, i.nama_produk, i.kategori,
                i.satuan, i.stok_minimal,
                COALESCE(s.stok_saat_ini, 0) as stok_saat_ini
            ', FALSE)
            ->from('mac_inventory i')
            ->join('mac_inventory_stok s', 's.inventory_id = i.id', 'left')
            ->where('i.is_active', 1)
            ->where('COALESCE(s.stok_saat_ini, 0) <=', 'i.stok_minimal', FALSE)
            ->order_by('s.stok_saat_ini', 'ASC')
            ->get()->result();

        echo json_encode($data);
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
}