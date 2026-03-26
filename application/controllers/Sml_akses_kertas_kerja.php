<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sml_akses_kertas_kerja extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_sml_akses_kertas_kerja');
        $this->M_login->getsecurity();
    }

    public function index()
    {
        $this->akses();
    }

    public function akses()
    {
        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        ($akses && $akses->view_level == 'N' ? redirect('auth') : '');

        // Jika hak akses submenu belum diset, tangani error
        $data['add'] = ($akses && isset($akses->add_level)) ? $akses->add_level : 'Y';
        $data['edit'] = ($akses && isset($akses->edit_level)) ? $akses->edit_level : 'Y';
        $data['delete'] = ($akses && isset($akses->delete_level)) ? $akses->delete_level : 'Y';

        $data['title'] = 'backend/sml_kertas_kerja/sml_akses_kertas_kerja';
        $data['titleview'] = 'Akses Kertas Kerja';

        // Ambil daftar username untuk dropdown
        $data['usernames'] = $this->db->select('username')
            ->from('tbl_user')
            ->order_by('username', 'asc')
            ->get()
            ->result();

        $this->load->view('backend/home', $data);
    }

    public function akses_get_list()
    {
        $list = $this->M_sml_akses_kertas_kerja->get_datatables();
        $data = array();
        $no = $_POST['start'];

        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        $can_edit = (!$akses || $akses->edit_level == 'Y');
        $can_delete = (!$akses || $akses->delete_level == 'Y');

        foreach ((array) $list as $field) {
            $no++;
            $row = array();
            $row[] = $no;

            $action = '';
            if ($can_edit) {
                $action .= '<a class="btn btn-warning btn-circle btn-sm" title="Edit" onclick="edit_data(' . "'" . $field->id . "'" . ')"><i class="fa fa-edit"></i></a>&nbsp;';
            }
            if ($can_delete) {
                $action .= '<a class="btn btn-danger btn-circle btn-sm" title="Delete" onclick="delete_data(' . "'" . $field->id . "'" . ')"><i class="fa fa-trash"></i></a>';
            }
            $row[] = $action;

            $row[] = isset($field->username) ? $field->username : '';
            $row[] = isset($field->role) ? $field->role : '';
            $row[] = (isset($field->is_active) && $field->is_active === 'Y') ? 'Yes' : 'No';
            $row[] = isset($field->updated_at) ? $field->updated_at : '';

            $data[] = $row;
        }

        $output = array(
            'draw' => $_POST['draw'],
            'recordsTotal' => $this->M_sml_akses_kertas_kerja->count_all(),
            'recordsFiltered' => $this->M_sml_akses_kertas_kerja->count_filtered(),
            'data' => $data,
        );
        echo json_encode($output);
    }

    public function akses_get_id($id)
    {
        $data = $this->M_sml_akses_kertas_kerja->get_by_id($id);
        echo json_encode($data);
    }

    public function akses_add()
    {
        // if (!$this->M_sml_akses_kertas_kerja->table_exists()) {
        //     $dbName = isset($this->db->database) ? $this->db->database : '';
        //     $suffix = $dbName ? (' (DB: ' . $dbName . ')') : '';
        //     echo json_encode(array('status' => FALSE, 'message' => 'Tabel sml_akses_kertas_kerja belum ada' . $suffix . '. Jalankan SQL migrasi pada database yang dipakai aplikasi.'));
        //     return;
        // }

        $username = strtolower(trim((string) $this->input->post('username', true)));
        $role = strtolower(trim((string) $this->input->post('role', true)));
        $is_active = strtoupper(trim((string) $this->input->post('is_active', true)));

        $valid_roles = array('marketing', 'plotting', 'monitoring', 'finance');
        if ($username === '' || $role === '' || !in_array($role, $valid_roles, true)) {
            echo json_encode(array('status' => FALSE, 'message' => 'Username dan Role wajib diisi (role: marketing/plotting/monitoring/finance).'));
            return;
        }
        if ($is_active !== 'Y' && $is_active !== 'N') {
            $is_active = 'Y';
        }

        // Cegah duplikat user+role
        $exists = $this->db
            ->from('sml_akses_kertas_kerja')
            ->where('username', $username)
            ->where('role', $role)
            ->get()->row();
        if ($exists) {
            echo json_encode(array('status' => FALSE, 'message' => 'Mapping untuk username+role ini sudah ada.'));
            return;
        }

        $this->M_sml_akses_kertas_kerja->save(array(
            'username' => $username,
            'role' => $role,
            'is_active' => $is_active,
        ));
        echo json_encode(array('status' => TRUE));
    }

    public function akses_update()
    {
        // if (!$this->M_sml_akses_kertas_kerja->table_exists()) {
        //     $dbName = isset($this->db->database) ? $this->db->database : '';
        //     $suffix = $dbName ? (' (DB: ' . $dbName . ')') : '';
        //     echo json_encode(array('status' => FALSE, 'message' => 'Tabel sml_akses_kertas_kerja belum ada' . $suffix . '. Jalankan SQL migrasi pada database yang dipakai aplikasi.'));
        //     return;
        // }

        $id = (int) $this->input->post('id', true);
        $username = strtolower(trim((string) $this->input->post('username', true)));
        $role = strtolower(trim((string) $this->input->post('role', true)));
        $is_active = strtoupper(trim((string) $this->input->post('is_active', true)));

        $valid_roles = array('marketing', 'plotting', 'monitoring', 'finance');
        if ($id <= 0 || $username === '' || $role === '' || !in_array($role, $valid_roles, true)) {
            echo json_encode(array('status' => FALSE, 'message' => 'Data tidak valid.'));
            return;
        }
        if ($is_active !== 'Y' && $is_active !== 'N') {
            $is_active = 'Y';
        }

        // Cegah duplikat user+role untuk record lain
        $exists = $this->db
            ->from('sml_akses_kertas_kerja')
            ->where('username', $username)
            ->where('role', $role)
            ->where('id !=', $id)
            ->get()->row();
        if ($exists) {
            echo json_encode(array('status' => FALSE, 'message' => 'Mapping username+role ini sudah dipakai record lain.'));
            return;
        }

        $this->M_sml_akses_kertas_kerja->update(array('id' => $id), array(
            'username' => $username,
            'role' => $role,
            'is_active' => $is_active,
        ));

        echo json_encode(array('status' => TRUE));
    }

    public function akses_delete($id)
    {
        // if (!$this->M_sml_akses_kertas_kerja->table_exists()) {
        //     $dbName = isset($this->db->database) ? $this->db->database : '';
        //     $suffix = $dbName ? (' (DB: ' . $dbName . ')') : '';
        //     echo json_encode(array('status' => FALSE, 'message' => 'Tabel sml_akses_kertas_kerja belum ada' . $suffix . '.'));
        //     return;
        // }

        $this->M_sml_akses_kertas_kerja->delete_by_id($id);
        echo json_encode(array('status' => TRUE));
    }
}
