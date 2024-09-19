<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Approval_sw extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_approval_sw');
        $this->M_login->getsecurity();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
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

        //LOOPING DATATABLES
        foreach ($list as $field) {

            // MENENTUKAN ACTION APA YANG AKAN DITAMPILKAN DI LIST DATA TABLES
            $action = '<a href="approval_sw/read_form/' . $field->id_user . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                        <a href="approval_sw/edit_form/' . $field->id_user . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
                        <a onclick="delete_data(' . "'" . $field->id_user . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>';

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
        $data['id'] = 0;
        $data['title_view'] = "Approval Form";
        $data['aksi'] = 'update';
        $data['approvals'] = $this->db->select('id_user, name')->from('tbl_data_user')->get()->result_object();
        $data['title'] = 'backend/approval_sw/approval_form_sw';
        $this->load->view('backend/home', $data);
    }

    public function add()
    {
        $data = array(
            ''
        );
    }
}
