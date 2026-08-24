<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mac_peminjaman extends CI_Controller
{
    private $approvers = ['dwi', 'bhakti', 'sopandi'];

    function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_mac_peminjaman');
        $this->load->model('backend/M_mac_inventory_stok');
        $this->M_login->getsecurity();
        date_default_timezone_set('Asia/Jakarta');
    }

    function tanggal_indo($tanggal)
    {
        $bulan = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        // Cek tanggal tidak kosong/null sebelum proses
        if (empty($tanggal)) {
            return '-';
        }
        $pecah = explode('-', date('Y-m-d', strtotime($tanggal)));
        $tahun = $pecah[0];
        $bulan_angka = (int)$pecah[1];
        $hari = $pecah[2];
        return $hari . ' ' . $bulan[$bulan_angka] . ' ' . $tahun;
    }

    // ================================================================
    // INDEX
    // ================================================================

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

        $data['title']     = 'backend/mac_peminjaman/mac_peminjaman_list';
        $data['titleview'] = 'Peminjaman Barang';
        $this->load->view('backend/home', $data);
    }

    // ================================================================
    // DATATABLES
    // ================================================================

    public function get_list()
    {
        $list = $this->M_mac_peminjaman->get_datatables();
        $data = [];
        $no   = $_POST['start'];

        $akses  = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        $edit   = $akses->edit_level;
        $delete = $akses->delete_level;

        foreach ($list as $field) {
            $action_read   = '<a href="mac_peminjaman/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Detail"><i class="fa fa-eye"></i></a>&nbsp;';
            $action_edit   = '';
            $action_delete = '';
            $action_return = '';

            // Edit hanya jika status masih aktif
            if ($edit == 'Y' && $field->status === 'aktif' && $field->app_status !== 'approved') {
                $action_edit = '<a href="mac_peminjaman/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;';
            }
            if ($delete == 'Y' && $field->status === 'aktif' && $field->app_status !== 'approved') {
                $action_delete = '<a onclick="delete_data(\'' . $field->id . '\')" class="btn btn-danger btn-circle btn-sm" title="Batal"><i class="fa fa-trash"></i></a>&nbsp;';
            }

            // Tombol pengembalian hanya muncul jika status aktif DAN sudah approved
            if ($field->status === 'aktif' && $field->app_status === 'approved') {
                $action_return = '<a onclick="open_return(\'' . $field->id . '\')" class="btn btn-success btn-circle btn-sm" title="Catat Pengembalian"><i class="fa fa-undo"></i></a>&nbsp;';
            } else {
                $action_return = '';
            }

            // Badge status
            if ($field->status === 'aktif' && $field->app_status === 'waiting') {
                $badge = '<span class="badge badge-secondary">Menunggu Persetujuan</span>';
            } elseif ($field->status === 'aktif' && $field->app_status === 'approved') {
                $badge = '<span class="badge badge-warning">Aktif</span>';
            } elseif ($field->status === 'kembali') {
                $badge = '<span class="badge badge-success">Kembali</span>';
            } elseif ($field->status === 'batal') {
                $badge = '<span class="badge badge-danger">Batal</span>';
            } else {
                $badge = '<span class="badge badge-light">' . $field->status . '</span>';
            }

            // Tanda lewat jatuh tempo
            $today       = date('Y-m-d');
            $tgl_kembali = $field->tgl_kembali;
            $terlambat   = ($field->status === 'aktif' && $tgl_kembali < $today)
                ? ' <span class="badge badge-danger">Terlambat</span>' : '';

            $no++;
            $row   = [];
            $row[] = $no;
            $row[] = $action_read . $action_edit . $action_return . $action_delete;
            $row[] = strtoupper($field->kode_pinjam);
            $row[] = ucwords($field->peminjam);
            $row[] = $this->tanggal_indo($field->tgl_pinjam);
            $row[] = $this->tanggal_indo($field->tgl_kembali) . $terlambat;
            $row[] = $badge;
            $data[] = $row;
        }

        echo json_encode([
            "draw"            => $_POST['draw'],
            "recordsTotal"    => $this->M_mac_peminjaman->count_all(),
            "recordsFiltered" => $this->M_mac_peminjaman->count_filtered(),
            "data"            => $data,
        ]);
    }

    // ================================================================
    // FORMS
    // ================================================================

    public function add_form()
    {
        $data['id']         = 0;
        $data['title_view'] = 'Form Peminjaman Barang';
        $data['title']      = 'backend/mac_peminjaman/mac_peminjaman_form';
        $this->load->view('backend/home', $data);
    }

    public function edit_form($id)
    {
        $master = $this->M_mac_peminjaman->get_by_id($id);
        if (!$master || $master->status !== 'aktif') {
            redirect('mac_peminjaman');
        }
   
        if ($master->app_status === 'approved') {
            redirect('mac_peminjaman');
        }
        $data['id']         = $id;
        $data['title_view'] = 'Edit Peminjaman Barang';
        $data['title']      = 'backend/mac_peminjaman/mac_peminjaman_form';
        $this->load->view('backend/home', $data);
    }

    public function read_form($id)
    {
        $data['id']         = $id;
        $data['title_view'] = 'Detail Peminjaman';
        $data['title']      = 'backend/mac_peminjaman/mac_peminjaman_read';
        $this->load->view('backend/home', $data);
    }

    // ================================================================
    // GET DATA (AJAX)
    // ================================================================

    public function get_data($id)
    {
        $master = $this->M_mac_peminjaman->get_by_id($id);
        if (!$master) { echo json_encode([]); return; }

        $user = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $master->id_user)
            ->get()->row('name');

        echo json_encode([
            'master'  => $master,
            'detail'  => $this->M_mac_peminjaman->get_detail($id),
            'log'     => $this->M_mac_peminjaman->get_log($id),
            'nama'    => $user,
        ]);
    }

    // ================================================================
    // GET STOK EFEKTIF (AJAX) — stok fisik dikurangi sedang dipinjam
    // ================================================================

    public function get_stok_efektif()
    {
        $inventory_id = intval($this->input->post('inventory_id'));
        if (!$inventory_id) {
            echo json_encode(['stok' => 0, 'satuan' => '']); return;
        }

        $stok   = $this->M_mac_inventory_stok->get_stok_efektif($inventory_id);
        $inv    = $this->db->select('satuan, nama_produk')
            ->where('id', $inventory_id)->get('mac_inventory')->row();

        echo json_encode([
            'stok'        => $stok,
            'satuan'      => $inv ? $inv->satuan : '',
            'nama_produk' => $inv ? $inv->nama_produk : '',
        ]);
    }

    // ================================================================
    // GET INVENTORY (select2 di form)
    // ================================================================

    public function get_inventory()
    {
        $search         = $this->input->post('search');
        $session_cabang = (int)$this->session->userdata('cabang_id');

        $this->db->select("
            i.id,
            i.kode_produk,
            i.nama_produk,
            i.satuan,
            COALESCE(s.stok_saat_ini, 0) as stok_fisik
        ", FALSE);

        $this->db->from('mac_inventory i');

        $this->db->join(
            'mac_inventory_stok s',
            's.inventory_id = i.id AND s.cabang_id = '.$session_cabang,
            'left'
        );

        $this->db->where('i.is_active', 1);
        $this->db->where('i.kategori !=', 'Jasa');

        if (!empty($search)) {
            $this->db->group_start()
                ->like('i.nama_produk', $search)
                ->or_like('i.kode_produk', $search)
                ->group_end();
        }

        $this->db->order_by('i.nama_produk', 'ASC');

        $items = $this->db->get()->result();

        // Hitung stok efektif sesuai cabang
        foreach ($items as &$item) {
            $item->stok_efektif = $this->M_mac_inventory_stok->get_stok_efektif(
                $item->id,
                false,
                $session_cabang
            );
        }

        echo json_encode($items);
    }

    // ================================================================
    // ADD
    // ================================================================

    public function add()
    {
        $id_user        = (int) $this->session->userdata('id_user');
        $cabang_id      = (int) $this->session->userdata('cabang_id');
        $data_user      = $this->db
                                ->get_where('tbl_data_user', ['id_user' => $id_user])
                                ->row_array();

        $inventory_ids  = $this->input->post('inventory_id') ?: [];
        $qty_list       = $this->input->post('qty_pinjam') ?: [];
        $ket_list       = $this->input->post('keterangan_detail') ?: [];

        if (empty($inventory_ids)) {
            echo json_encode([
                'status' => FALSE,
                'error'  => 'Minimal satu barang harus dipilih.'
            ]);
            return;
        }

        // ==========================
        // VALIDASI STOK
        // ==========================
        foreach ($inventory_ids as $i => $inv_id) {

            if (empty($inv_id)) {
                continue;
            }

            $qty = (float)($qty_list[$i] ?? 0);

            if ($qty <= 0) {
                echo json_encode([
                    'status' => FALSE,
                    'error'  => 'Qty tidak valid.'
                ]);
                return;
            }

            $stok = $this->M_mac_inventory_stok->get_stok_efektif(
                $inv_id,
                false,
                $cabang_id
            );

            $barang = $this->db
                ->select('nama_produk')
                ->where('id', $inv_id)
                ->get('mac_inventory')
                ->row();

            if ($qty > $stok) {

                echo json_encode([
                    'status' => FALSE,
                    'error'  => 'Stok efektif ' .
                        ($barang->nama_produk ?? '-') .
                        ' tidak mencukupi. Stok tersedia : ' .
                        $stok
                ]);
                return;
            }
        }

        // ==========================
        // HEADER
        // ==========================
        $kode = $this->M_mac_peminjaman->generate_kode();

        $peminjaman_id = $this->M_mac_peminjaman->save([

            'cabang_id'   => $cabang_id,
            'kode_pinjam' => $kode,
            'id_user'     => $id_user,
            'peminjam'    => $data_user['name'] ?? '',
            'tgl_pinjam'  => date(
                                'Y-m-d',
                                strtotime($this->input->post('tgl_pinjam'))
                            ),
            'tgl_kembali' => date(
                                'Y-m-d',
                                strtotime($this->input->post('tgl_kembali'))
                            ),

            'status'      => 'aktif',
            'app_status'  => 'waiting',
            'keterangan'  => $this->input->post('keterangan'),

            'created_by'  => $id_user,
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);

        // ==========================
        // DETAIL
        // ==========================
        $detail_batch = [];

        foreach ($inventory_ids as $i => $inv_id) {

            if (empty($inv_id)) {
                continue;
            }

            $qty = (float)($qty_list[$i] ?? 0);

            $detail_batch[] = [

                'peminjaman_id' => $peminjaman_id,
                'inventory_id'  => (int)$inv_id,
                'qty_pinjam'    => $qty,
                'qty_kembali'   => 0,
                'keterangan'    => $ket_list[$i] ?? ''

            ];

            $this->M_mac_peminjaman->save_log([

                'peminjaman_id' => $peminjaman_id,
                'inventory_id'  => (int)$inv_id,
                'aksi'          => 'pinjam',
                'qty'           => $qty,
                'keterangan'    => 'Pengajuan peminjaman',
                'created_by'    => $id_user,
                'created_at'    => date('Y-m-d H:i:s')

            ]);
        }

        $this->M_mac_peminjaman->save_detail($detail_batch);

        echo json_encode([
            'status'  => TRUE,
            'message' => 'Pengajuan peminjaman berhasil dibuat dan menunggu approval.',
            'kode'    => $kode
        ]);
    }

    // ================================================================
    // UPDATE
    // ================================================================

    public function update()
    {
        $id = (int) $this->input->post('id');

        $master = $this->M_mac_peminjaman->get_by_id($id);

        if (!$master) {
            echo json_encode([
                'status' => FALSE,
                'error'  => 'Data tidak ditemukan.'
            ]);
            return;
        }

        // Tidak boleh edit jika sudah selesai atau batal
        if (in_array($master->status, ['kembali', 'batal'])) {
            echo json_encode([
                'status' => FALSE,
                'error'  => 'Data tidak dapat diubah.'
            ]);
            return;
        }

        // Tidak boleh edit jika approval sudah diproses
        if ($master->app_status != 'waiting') {
            echo json_encode([
                'status' => FALSE,
                'error'  => 'Data sudah diproses approval dan tidak dapat diubah.'
            ]);
            return;
        }

        $this->M_mac_peminjaman->update($id, [

            'tgl_kembali' => date(
                'Y-m-d',
                strtotime($this->input->post('tgl_kembali'))
            ),

            'keterangan' => $this->input->post('keterangan'),

            'updated_at' => date('Y-m-d H:i:s')

        ]);

        echo json_encode([
            'status'  => TRUE,
            'message' => 'Data berhasil diupdate.'
        ]);
    }

    // ================================================================
    // PENGEMBALIAN — catat qty yang dikembalikan per item
    // ================================================================

    public function catat_kembali()
    {
        $peminjaman_id  = intval($this->input->post('peminjaman_id'));
        $detail_ids     = $this->input->post('detail_id')     ?: [];
        $keterangan_kembali = $this->input->post('keterangan_kembali') ?: [];
        $qty_kembali    = $this->input->post('qty_kembali')   ?: [];
        $id_user        = $this->session->userdata('id_user');

        $master = $this->M_mac_peminjaman->get_by_id($peminjaman_id);
        if (!$master || $master->status !== 'aktif') {
            echo json_encode(['status' => FALSE, 'error' => 'Peminjaman tidak ditemukan atau sudah selesai.']);
            return;
        }

        $semua_kembali = true;

        foreach ($detail_ids as $i => $detail_id) {
            $detail = $this->db->where('id', $detail_id)->get('mac_peminjaman_detail')->row_array();
            if (!$detail) continue;

            $qty_baru_kembali  = floatval($qty_kembali[$i] ?? 0);
            $keterangan       = $keterangan_kembali[$i] ?? '';
            $total_kembali     = floatval($detail['qty_kembali']) + $qty_baru_kembali;
            $sisa_pinjam       = floatval($detail['qty_pinjam']) - floatval($detail['qty_kembali']);

            // Validasi tidak melebihi yang dipinjam
            if ($qty_baru_kembali > $sisa_pinjam) {
                $inv  = $this->db->select('nama_produk')->where('id', $detail['inventory_id'])->get('mac_inventory')->row();
                $nama = $inv ? $inv->nama_produk : '#' . $detail['inventory_id'];
                echo json_encode(['status' => FALSE, 'error' => 'Qty kembali ' . $nama . ' melebihi sisa pinjam (' . $sisa_pinjam . ')']);
                return;
            }

            if ($qty_baru_kembali <= 0) {
                continue;
            }

            // if (!in_array($keterangan, ['kembali_gudang', 'terjual_invoice'])) {
            //     echo json_encode([
            //         'status' => FALSE,
            //         'error'  => 'Silakan pilih status pengembalian untuk barang tersebut.'
            //     ]);
            //     return;
            // }

            if ($qty_baru_kembali <= 0) continue;

            // Update qty_kembali di detail
            $this->M_mac_peminjaman->update_detail($detail_id, [
                'qty_kembali' => $total_kembali,
                'keterangan'  => $keterangan
            ]);

            // Tentukan aksi log
            $aksi = ($total_kembali >= floatval($detail['qty_pinjam'])) ? 'kembali_semua' : 'kembali_sebagian';

            // Catat log
            $this->M_mac_peminjaman->save_log([
                'peminjaman_id' => $peminjaman_id,
                'detail_id'     => $detail_id,
                'aksi'          => $aksi,
                'inventory_id'  => $detail['inventory_id'],
                'qty'           => $qty_baru_kembali,
                'keterangan' => $keterangan === 'kembali_gudang'
                                ? 'Kembali ke Gudang'
                                : 'Berhasil Terjual / Keluar Invoice',
                'created_by'    => $id_user,
                'created_at'    => date('Y-m-d H:i:s'),
            ]);

            // Cek apakah item ini belum kembali semua
            if ($total_kembali < floatval($detail['qty_pinjam'])) {
                $semua_kembali = false;
            }
        }

        // Jika semua item sudah kembali semua, ubah status header
        $details_terbaru = $this->M_mac_peminjaman->get_detail($peminjaman_id);
        $semua_lunas     = true;
        foreach ($details_terbaru as $d) {
            if (floatval($d['qty_kembali']) < floatval($d['qty_pinjam'])) {
                $semua_lunas = false;
                break;
            }
        }

        if ($semua_lunas) {
            $this->M_mac_peminjaman->update($peminjaman_id, [
                'status'               => 'kembali',
                'tgl_kembali_aktual'   => date('Y-m-d'),
                'updated_at'           => date('Y-m-d H:i:s'),
            ]);
        }

        echo json_encode(['status' => TRUE, 'message' => 'Pengembalian berhasil dicatat', 'semua_kembali' => $semua_lunas]);
    }

    public function approve()
    {
        $id         = intval($this->input->post('id'));
        $app_status = $this->input->post('app_status');
        $keterangan = $this->input->post('app_keterangan');
        $username   = $this->session->userdata('username');
        $id_user    = $this->session->userdata('id_user');

        // Hanya approver
        if (!in_array($username, $this->approvers)) {
            echo json_encode([
                'status' => FALSE,
                'error'  => 'Akses ditolak.'
            ]);
            return;
        }

        if (!in_array($app_status, ['approved', 'rejected'])) {
            echo json_encode([
                'status' => FALSE,
                'error'  => 'Status tidak valid.'
            ]);
            return;
        }

        $master = $this->M_mac_peminjaman->get_by_id($id);

        if (!$master) {
            echo json_encode([
                'status' => FALSE,
                'error'  => 'Data tidak ditemukan.'
            ]);
            return;
        }

        if ($master->app_status != 'waiting') {
            echo json_encode([
                'status' => FALSE,
                'error'  => 'Peminjaman sudah diproses sebelumnya.'
            ]);
            return;
        }

        /**
         * ==========================================================
         * VALIDASI STOK SAAT APPROVAL
         * ==========================================================
         */
        if ($app_status == 'approved') {

            $details = $this->M_mac_peminjaman->get_detail($id);

            foreach ($details as $d) {

                $stok = $this->M_mac_inventory_stok->get_stok_efektif(
                    $d['inventory_id'],
                    false,
                    $master->cabang_id
                );

                if ($d['qty_pinjam'] > $stok) {

                    $barang = $this->db
                        ->select('nama_produk')
                        ->where('id', $d['inventory_id'])
                        ->get('mac_inventory')
                        ->row();

                    echo json_encode([
                        'status' => FALSE,
                        'error'  => 'Stok efektif "' .
                            ($barang ? $barang->nama_produk : '-') .
                            '" tidak mencukupi. Tersedia : ' . $stok
                    ]);
                    return;
                }
            }
        }

        /**
         * ==========================================================
         * UPDATE HEADER
         * ==========================================================
         */

        $update = [
            'app_status'     => $app_status,
            'app_by'         => $username,
            'app_date'       => date('Y-m-d H:i:s'),
            'app_keterangan' => $keterangan,
            'updated_at'     => date('Y-m-d H:i:s'),
        ];

        if ($app_status == 'rejected') {
            $update['status'] = 'batal';
        }

        $this->M_mac_peminjaman->update($id, $update);

        /**
         * ==========================================================
         * LOG
         * ==========================================================
         */

        $this->M_mac_peminjaman->save_log([
            'peminjaman_id' => $id,
            'aksi'          => ($app_status == 'approved') ? 'approved' : 'rejected',
            'keterangan'    => 'Approval : ' . strtoupper($app_status)
                                . ($keterangan ? ' - ' . $keterangan : ''),
            'created_by'    => $id_user,
            'created_at'    => date('Y-m-d H:i:s'),
        ]);

        echo json_encode([
            'status'  => TRUE,
            'message' => 'Approval berhasil disimpan.'
        ]);
    }

    // ================================================================
    // BATAL / DELETE
    // ================================================================

    public function delete($id)
    {
        $master = $this->M_mac_peminjaman->get_by_id($id);
        if (!$master || $master->status !== 'aktif' || $master->app_status === 'approved') {
            echo json_encode(['status' => FALSE, 'error' => 'Tidak dapat membatalkan peminjaman yang sudah diapprove.']);
            return;
        }

        $id_user = $this->session->userdata('id_user');

        // Catat log batal per detail
        $details = $this->M_mac_peminjaman->get_detail($id);
        foreach ($details as $d) {
            $sisa = floatval($d['qty_pinjam']) - floatval($d['qty_kembali']);
            if ($sisa > 0) {
                $this->M_mac_peminjaman->save_log([
                    'peminjaman_id' => $id,
                    'aksi'          => 'batal',
                    'inventory_id'  => $d['inventory_id'],
                    'qty'           => $sisa,
                    'keterangan'    => 'Peminjaman dibatalkan',
                    'created_by'    => $id_user,
                    'created_at'    => date('Y-m-d H:i:s'),
                ]);
            }
        }

        // Update status ke batal (soft delete)
        $this->M_mac_peminjaman->update($id, [
            'status'     => 'batal',
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        echo json_encode(['status' => TRUE]);
    }
}
