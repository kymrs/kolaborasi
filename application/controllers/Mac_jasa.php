<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mac_jasa extends CI_Controller
{
    // Daftar paket yang valid — dipakai untuk validasi server-side
    private $paket_valid = ['Basic', 'Medium', 'Luxury'];

    function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_mac_jasa');
        $this->M_login->getsecurity();
        date_default_timezone_set('Asia/Jakarta');
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

        $data['title']       = 'backend/mac_jasa/mac_jasa_list';
        $data['titleview']   = 'Master Jasa';
        $data['is_nasional'] = $this->session->userdata('is_nasional') ? true : false;
        $data['cabang_id']   = intval($this->session->userdata('cabang_id'));

        if ($data['is_nasional']) {

            $data['title_cabang'] = 'Nasional';

            $data['list_cabang'] = $this->db
                ->where('status', 'aktif')
                ->where('id !=', 1)
                ->order_by('nama_cabang', 'ASC')
                ->get('mac_cabang')
                ->result();

        } else {

            $cabang = $this->db
                ->select('nama_cabang')
                ->where('id', $data['cabang_id'])
                ->get('mac_cabang')
                ->row();

            $data['title_cabang'] = $cabang ? $cabang->nama_cabang : '-';
            $data['list_cabang']  = [];
        }

        $this->load->view('backend/home', $data);
    }

    // ================================================================
    // DATATABLES LIST
    // ================================================================
    public function get_list()
    {
        $is_nasional    = $this->session->userdata('is_nasional') ? true : false;
        $session_cabang = intval($this->session->userdata('cabang_id'));
        $filter_cabang  = intval($this->input->post('filter_cabang'));
        $filter_jenis   = $this->input->post('filter_jenis');

        $use_cabang_id = $is_nasional ? ($filter_cabang > 0 ? $filter_cabang : null) : $session_cabang;

        $list = $this->M_mac_jasa->get_datatables($use_cabang_id, $filter_jenis);
        $data = [];
        $no   = $_POST['start'];

        $akses  = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        $edit   = $akses->edit_level;
        $delete = $akses->delete_level;

        $badge_map = [
            'Basic'  => 'badge-secondary',
            'Medium' => 'badge-info',
            'Luxury' => 'badge-warning',
        ];

        foreach ($list as $field) {
            $action_edit   = ($edit == 'Y')
                ? '<a onclick="open_modal(' . $field->id . ')" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;'
                : '';
            $action_delete = ($delete == 'Y')
                ? '<a onclick="delete_data(\'' . $field->id . '\')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;'
                : '';

            $badge_class = $badge_map[$field->paket] ?? 'badge-light';

            // Badge jenis internal/external
            $jenis_badge = strtolower($field->jenis) === 'external'
                ? '<span class="badge badge-warning">External</span>'
                : '<span class="badge badge-secondary">Internal</span>';

            $no++;
            $row   = [];
            $row[] = $no;
            $row[] = $action_edit . $action_delete;
            $row[] = ucwords($field->nama);
            $row[] = $jenis_badge;
            $row[] = ucwords($field->satuan);
            $row[] = '<span class="badge ' . $badge_class . '">' . $field->paket . '</span>';
            $row[] = $field->harga_beli > 0
                ? 'Rp ' . number_format($field->harga_beli, 0, ',', '.')
                : '<span class="text-muted">-</span>';
            $row[] = 'Rp ' . number_format($field->harga_jual, 0, ',', '.');
            $row[] = date('d-m-Y H:i:s', strtotime($field->created_at));
            $data[] = $row;
        }

        echo json_encode([
            "draw"            => $_POST['draw'],
            "recordsTotal"    => $this->M_mac_jasa->count_all($use_cabang_id, $filter_jenis),
            "recordsFiltered" => $this->M_mac_jasa->count_filtered($use_cabang_id, $filter_jenis),
            "data"            => $data,
        ]);
    }

    // ================================================================
    // GET DATA (AJAX — untuk modal edit)
    // ================================================================

    public function get_data($id)
    {
        $row = $this->M_mac_jasa->get_by_id($id);
        echo json_encode($row ?: []);
    }

    // GET harga per cabang untuk modal Nasional
    public function get_jasa()
    {
        $search    = $this->input->post('search');
        $cabang_id = intval($this->session->userdata('cabang_id'));

        $this->db->select('id, nama, paket, satuan, harga_beli, harga_jual, jenis')
            ->from('mac_jasa')
            ->where('is_active', 1)
            ->where('cabang_id', $cabang_id); // hanya jasa cabang sendiri

        if (!empty($search)) {
            $this->db->group_start()
                ->like('nama', $search)
                ->or_like('paket', $search)
                ->group_end();
        }

        $this->db->order_by('nama', 'ASC');
        echo json_encode($this->db->get()->result());
    }

    // SAVE harga per cabang
    public function save_harga_jasa_cabang()
    {
        $jasa_id   = intval($this->input->post('jasa_id'));
        $cabang_ids = $this->input->post('cabang_id')  ?: [];
        $hargas     = $this->input->post('harga_jasa') ?: [];

        foreach ($cabang_ids as $i => $cabang_id) {
            $cabang_id = intval($cabang_id);
            $harga     = intval(str_replace('.', '', $hargas[$i] ?? 0));
            if ($cabang_id <= 0) continue;

            $existing = $this->db
                ->where('jasa_id', $jasa_id)
                ->where('cabang_id', $cabang_id)
                ->get('mac_jasa')->row();

            if ($existing) {
                $this->db->where('jasa_id', $jasa_id)
                    ->where('cabang_id', $cabang_id)
                    ->update('mac_jasa', [
                        'harga'      => $harga > 0 ? $harga : null,
                        'updated_by' => $this->session->userdata('id_user'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
            } else {
                $this->db->insert('mac_jasa', [
                    'jasa_id'    => $jasa_id,
                    'cabang_id'  => $cabang_id,
                    'harga'      => $harga > 0 ? $harga : null,
                    'updated_by' => $this->session->userdata('id_user'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }

        echo json_encode(['status' => TRUE, 'message' => 'Harga jasa berhasil disimpan']);
    }

    // ================================================================
    // SAVE (ADD)
    // ================================================================

    public function add()
    {
        $nama       = trim($this->input->post('nama'));
        $paket      = $this->input->post('paket');
        $jenis      = $this->input->post('jenis');
        $harga_jual = intval(str_replace('.', '', $this->input->post('harga_jual')));
        $harga_beli = intval(str_replace('.', '', $this->input->post('harga_beli')));

        $is_nasional    = $this->session->userdata('is_nasional') ? true : false;
        $session_cabang = intval($this->session->userdata('cabang_id'));

        // Nasional bisa pilih cabang, cabang biasa pakai cabang sendiri
        $cabang_id = $is_nasional
            ? intval($this->input->post('cabang_id'))
            : $session_cabang;

        if (empty($cabang_id)) {
            echo json_encode(['status' => FALSE, 'error' => 'Cabang wajib dipilih.']);
            return;
        }

        if (!in_array($paket, $this->paket_valid)) {
            echo json_encode(['status' => FALSE, 'error' => 'Paket tidak valid.']); return;
        }
        if (empty($nama)) {
            echo json_encode(['status' => FALSE, 'error' => 'Nama jasa wajib diisi.']); return;
        }
        if (!in_array(strtolower($jenis), ['internal', 'external'])) {
            echo json_encode(['status' => FALSE, 'error' => 'Jenis tidak valid.']); return;
        }

        // Jika internal, harga_beli harus 0
        if (strtolower($jenis) === 'internal') $harga_beli = 0;

        // Cek duplikat nama + paket + cabang
        $duplikat = $this->db
            ->where('nama', ucwords($nama))
            ->where('paket', $paket)
            ->where('cabang_id', $cabang_id)
            ->where('is_active', 1)
            ->count_all_results('mac_jasa');

        if ($duplikat > 0) {
            echo json_encode(['status' => FALSE, 'error' => 'Jasa "' . $nama . '" dengan paket "' . $paket . '" sudah terdaftar di cabang ini.']);
            return;
        }

        $id = $this->M_mac_jasa->save([
            'cabang_id'  => $cabang_id,
            'nama'       => ucwords($nama),
            'satuan'     => $this->input->post('satuan'),
            'jenis'      => ucwords($jenis),
            'paket'      => $paket,
            'harga_jual' => $harga_jual,
            'harga_beli' => $harga_beli,
            'is_active'  => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('id_user'),
        ]);

        echo $id
            ? json_encode(['status' => TRUE, 'message' => 'Data jasa berhasil disimpan'])
            : json_encode(['status' => FALSE, 'error' => 'Gagal menyimpan data']);
    }

    // ================================================================
    // UPDATE
    // ================================================================

    public function update()
    {
        $id         = $this->input->post('id');
        $nama       = trim($this->input->post('nama'));
        $paket      = $this->input->post('paket');
        $jenis      = $this->input->post('jenis');
        $harga_jual = intval(str_replace('.', '', $this->input->post('harga_jual')));
        $harga_beli = intval(str_replace('.', '', $this->input->post('harga_beli')));

        if (!in_array($paket, $this->paket_valid)) {
            echo json_encode(['status' => FALSE, 'error' => 'Paket tidak valid.']); return;
        }
        if (empty($nama)) {
            echo json_encode(['status' => FALSE, 'error' => 'Nama jasa wajib diisi.']); return;
        }
        if (!in_array(strtolower($jenis), ['internal', 'external'])) {
            echo json_encode(['status' => FALSE, 'error' => 'Jenis tidak valid.']); return;
        }

        // Jika internal, harga_beli harus 0
        if (strtolower($jenis) === 'internal') $harga_beli = 0;

        $this->M_mac_jasa->update_jasa($id, [
            'nama'       => ucwords($nama),
            'satuan'     => $this->input->post('satuan'),
            'jenis'      => ucwords($jenis),
            'paket'      => $paket,
            'harga_jual' => $harga_jual,
            'harga_beli' => $harga_beli,
        ]);

        echo json_encode(['status' => TRUE, 'message' => 'Data jasa berhasil diupdate']);
    }

    // ================================================================
    // DELETE (soft delete)
    // ================================================================

    public function delete($id)
    {
        $this->M_mac_jasa->delete_jasa($id);
        echo json_encode(['status' => TRUE]);
    }
}
