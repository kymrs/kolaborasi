<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Approval extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_approval');
        $this->load->model('backend/M_notifikasi');
        $this->M_login->getsecurity();
        $this->load->library('session');
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        ($akses->view_level == 'N' ? redirect('auth') : '');
        $data['add'] = $akses->add_level;

        $data['title'] = "backend/approval/approval_list";
        $data['titleview'] = "Approval";
        $this->load->view('backend/home', $data);
    }

    function get_list()
    {
        // INISIAI VARIABLE YANG DIBUTUHKAN
        $fullname = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $this->session->userdata('id_user'))
            ->get()
            ->row('name');
        $list = $this->M_approval->get_datatables();
        $data = array();
        $no = $_POST['start'];

        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        $edit = $akses->edit_level;
        $delete = $akses->delete_level;

        //LOOPING DATATABLES
        foreach ($list as $field) {

            // MENENTUKAN ACTION APA YANG AKAN DITAMPILKAN DI LIST DATA TABLES
            $action_edit = ($edit == 'Y') ? '<a href="approval/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;' : '';
            $action_delete = ($delete == 'Y') ? '<a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;' : '';

            // MENENTUKAN ACTION APA YANG AKAN DITAMPILKAN DI LIST DATA TABLES
            $action = $action_edit . $action_delete;

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $action;
            $row[] = $field->sub_name;
            $row[] = $field->id_menu;
            $row[] = $field->app_id;
            $row[] = $field->app2_id;
            $row[] = $field->app3_id;
            $row[] = $field->app4_id;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_approval->count_all(),
            "recordsFiltered" => $this->M_approval->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    public function add_form()
    {
        // // Check if access is granted
        // $this->check_access();
        $this->load->model('backend/M_notifikasi');
        $data['id'] = 0;
        $data['title_view'] = "Approval Form";
        $data['aksi'] = 'save';
        $data['menus'] = $this->db->select('id_menu, nama_menu')->from('tbl_menu')->get()->result();
        $data['approvals'] = $this->db->select('id_user, fullname')->from('tbl_user')->where('app', 'Y')->get()->result_object();
        $data['title'] = 'backend/approval/approval_form';
        $this->load->view('backend/home', $data);

        // Clear access after checking
        $this->session->unset_userdata('access_granted');
    }

    public function add()
    {
        $data = array(
            'id_user' => $this->input->post('name'),
            'name' => $this->input->post('selectedText'),
            'divisi' => $this->input->post('divisi'),
            'jabatan' => $this->input->post('jabatan'),
            'app_id' => $this->input->post('app_id'),
            'app2_id' => $this->input->post('app2_id'),
            'app3_id' => $this->input->post('app3_id'),
        );

        if (!empty($this->input->post('app4_id'))) {
            $data['app4_id'] = $this->input->post('app4_id');
        }

        $inserted = $this->M_approval->save($data);

        echo json_encode(array("status" => TRUE));
    }

    public function update()
    {
        $data = array(
            'name' => $this->input->post('selectedText'),
            'divisi' => $this->input->post('divisi'),
            'jabatan' => $this->input->post('jabatan'),
            'app_id' => $this->input->post('app_id'),
            'app2_id' => $this->input->post('app2_id'),
            'app3_id' => $this->input->post('app3_id'),
            'updated_at' => date('Y-m-d H:i:s')
        );

        if (!empty($this->input->post('app4_id'))) {
            $data['app4_id'] = $this->input->post('app4_id');
        }

        $this->db->update('tbl_data_user', $data, ['id_user' => $this->input->post('id')]);

        echo json_encode(array("status" => TRUE));
    }

    public function edit_form($id)
    {
        $data['id'] = $id;
        $this->load->model('backend/M_notifikasi');
        $data['title_view'] = "Edit Approval Form";
        $data['aksi'] = 'update';
        $data['menus'] = $this->db->select('id_menu, nama_menu')->from('tbl_menu')->get()->result();
        $data['approvals'] = $this->db->select('id_user, fullname')->from('tbl_user')->where('app', 'Y')->get()->result_object();
        $data['title'] = 'backend/approval/approval_form';
        $this->load->view('backend/home', $data);
    }

    function edit_data($id)
    {
        // $data['master'] = $this->db->get_where('tbl_data_user', ['id_user' => $id])->row_array();
        $data = $this->db->get_where('tbl_approval', ['id' => $id])->row_array();
        echo json_encode($data);
    }

    function delete($id)
    {
        $this->db->delete('tbl_data_user', ['id_user' => $id]);
        echo json_encode(array("status" => TRUE));
    }
}
