<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Approval_sw extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_approval_sw');
        $this->M_login->getsecurity();
        $this->load->library('session');
        date_default_timezone_set('Asia/Jakarta');
    }

    // Reusable method to check if access is granted
    private function check_access()
    {
        if (!$this->session->userdata('access_granted')) {
            // If not, show error or redirect
            show_error('Unauthorized access!', 403);

            // Clear access after checking
            $this->session->unset_userdata('access_granted');
        }
    }

    // Call this when the button is clicked to set access
    public function set_access()
    {
        // Set session variable when button is clicked
        $this->session->set_userdata('access_granted', TRUE);

        $link = $this->input->get('link');

        // Redirect to any protected action
        redirect('approval_sw/' . $link); // Example redirect to the first action
    }

    public function index()
    {
        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        ($akses->view_level == 'N' ? redirect('auth') : '');
        $data['add'] = $akses->add_level;

        $data['title'] = "backend/approval_sw/approval_list_sw";
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
        $list = $this->M_approval_sw->get_datatables();
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
            $action_read = ($read == 'Y') ? '<a href="approval_sw/read_form/' . $field->id_user . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>&nbsp;' : '';
            $action_edit = ($edit == 'Y') ? '<a href="approval_sw/edit_form/' . $field->id_user . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;' : '';
            $action_delete = ($delete == 'Y') ? '<a onclick="delete_data(' . "'" . $field->id_user . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;' : '';
            $action_print = ($print == 'Y') ? '<a class="btn btn-success btn-circle btn-sm" target="_blank" href="approval_sw/generate_pdf/' . $field->id_user . '"><i class="fas fa-file-pdf"></i></a>' : '';

            // MENENTUKAN ACTION APA YANG AKAN DITAMPILKAN DI LIST DATA TABLES
            $action = $action_read . $action_edit . $action_delete . $action_print;

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $action;
            $row[] = $field->id_user;
            $row[] = $field->name;
            $row[] = $field->divisi;
            $row[] = $field->jabatan;
            $row[] = date('d F Y', strtotime($field->created_at));

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_approval_sw->count_all(),
            "recordsFiltered" => $this->M_approval_sw->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    public function add_form()
    {
        // Check if access is granted
        $this->check_access();

        $data['id'] = 0;
        $data['title_view'] = "Approval Form";
        $data['aksi'] = 'save';
        $data['approvals'] = $this->db->select('id_user, fullname')->from('tbl_user')->get()->result_object();
        $data['title'] = 'backend/approval_sw/approval_form_sw';
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
            'app2_id' => $this->input->post('app2_id')
        );

        $inserted = $this->M_approval_sw->save($data);

        echo json_encode(array("status" => TRUE));
    }
}
