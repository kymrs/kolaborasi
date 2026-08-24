<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mac_mekanik extends CI_Controller
{
    // Daftar paket yang valid — dipakai untuk validasi server-side
    private $paket_valid = ['Basic', 'Medium', 'Luxury'];

    function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_mac_mekanik');
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

        $data['title']     = 'backend/mac_mekanik/mac_mekanik_list';
        $data['titleview'] = 'Master Mekanik';
        $this->load->view('backend/home', $data);
    }

    // ================================================================
    // DATATABLES LIST
    // ================================================================

    public function get_list()
    {
        $list = $this->M_mac_mekanik->get_datatables();
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

            $no++;
            $row   = [];
            $row[] = $no;
            $row[] = $action_edit . $action_delete;
            $row[] = $field->nama;
            $row[] = $field->npk;
            $row[] = $field->cabang_id;
            $row[] = $field->no_telp;
            $row[] = $field->alamat;
            $row[] = date('d-m-Y H:i:s', strtotime($field->created_at));
            $data[] = $row;
        }

        echo json_encode([
            "draw"            => $_POST['draw'],
            "recordsTotal"    => $this->M_mac_mekanik->count_all(),
            "recordsFiltered" => $this->M_mac_mekanik->count_filtered(),
            "data"            => $data,
        ]);
    }

    // ================================================================
    // GET DATA (AJAX — untuk modal edit)
    // ================================================================

    public function get_data($id)
    {
        $row = $this->M_mac_mekanik->get_by_id($id);
        echo json_encode($row ?: []);
    }

    // ================================================================
    // SAVE (ADD)
    // ================================================================

    public function add()
    {
        $nama  = trim($this->input->post('nama'));
        $npk = $this->input->post('npk');
        $cabang_id = $this->input->post('cabang_id');
        $no_telp = $this->input->post('no_telp');
        $alamat = $this->input->post('alamat');

        if (empty($nama)) {
            echo json_encode(['status' => FALSE, 'error' => 'Nama mekanik wajib diisi.']);
            return;
        }

        $data = [
            'nama'       => $nama,
            'npk'     => $npk,
            'cabang_id'     => $cabang_id,
            'no_telp'    => $no_telp,
            'alamat'     => $alamat,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $id = $this->M_mac_mekanik->save($data);

        echo $id
            ? json_encode(['status' => TRUE, 'message' => 'Data mekanik berhasil disimpan'])
            : json_encode(['status' => FALSE, 'error' => 'Gagal menyimpan data']);
    }

    // ================================================================
    // UPDATE
    // ================================================================

    public function update()
    {
        $id  = trim($this->input->post('id'));
        $nama  = trim($this->input->post('nama'));
        $npk = $this->input->post('npk');
        $cabang_id = $this->input->post('cabang_id');
        $no_telp = $this->input->post('no_telp');
        $alamat = $this->input->post('alamat');

        if (empty($nama)) {
            echo json_encode(['status' => FALSE, 'error' => 'Nama mekanik wajib diisi.']);
            return;
        }

        $data = [
            'nama'   => $nama,
            'npk'    => $npk,
            'cabang_id' => $cabang_id,
            'no_telp'    => $no_telp,
            'alamat'    => $alamat
        ];

        $this->M_mac_mekanik->update_mekanik($id, $data);
        echo json_encode(['status' => TRUE, 'message' => 'Data mekanik berhasil diupdate']);
    }

    // ================================================================
    // DELETE (soft delete)
    // ================================================================

    public function delete($id)
    {
        $this->M_mac_mekanik->delete_mekanik($id);
        echo json_encode(['status' => TRUE]);
    }
}
