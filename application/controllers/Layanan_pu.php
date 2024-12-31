<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Layanan_pu extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_layanan_pu');
        $this->M_login->getsecurity();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $data['title'] = "backend/layanan_pu/layanan_list_pu";
        $data['titleview'] = "Layanan";
        $this->load->view('backend/home', $data);
    }

    function get_list()
    {
        $list = $this->M_layanan_pu->get_datatables();
        $data = array();
        $no = $_POST['start'];

        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        $edit = $akses->edit_level;
        $delete = $akses->delete_level;

        foreach ($list as $field) {

            // MENENTUKAN ACTION APA YANG AKAN DITAMPILKAN DI LIST DATA TABLES
            $action_edit = ($edit == 'Y') ? '<a class="btn btn-warning btn-circle btn-sm" title="Edit" onclick="edit_data(' . "'" . $field->id . "'" . ')"><i class="fa fa-edit"></i></a>&nbsp;' : '';
            $action_delete = ($delete == 'Y') ? '<a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;' : '';

            $action = $action_edit . $action_delete;

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $action;
            $row[] = $field->nama_layanan;
            $row[] = $field->created_at;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_layanan_pu->count_all(),
            "recordsFiltered" => $this->M_layanan_pu->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    function read_form($id)
    {
        $data['master'] = $this->M_layanan_pu->get_by_id($id);
        $data['title_view'] = "Data Layanan";
        $data['title'] = 'backend/layanan_pu/layanan_read_pu';
        $this->load->view('backend/home', $data);
    }

    function add_form()
    {
        $data['id'] = 0;
        $data['title_view'] = "Layanan Form";
        $data['title'] = 'backend/layanan_pu/layanan_form_pu';
        $this->load->view('backend/home', $data);
    }

    function edit_form($id)
    {
        $data['master'] = $this->M_layanan_pu->get_by_id($id);
        $data['title_view'] = "Edit Data Layanan";
        $data['title'] = 'backend/layanan_pu/layanan_form_pu';
        $this->load->view('backend/home', $data);
    }

    function get_id($id)
    {
        $data = $this->M_layanan_pu->get_by_id($id);
        echo json_encode($data);
    }

    public function add()
    {
        $data = array(
            'nama_layanan' => $this->input->post('nama_layanan')
        );

        $this->M_layanan_pu->save($data);
        echo json_encode(array("status" => TRUE));
    }

    public function update()
    {
        $data = array(
            'nama_layanan' => $this->input->post('nama_layanan')
        );
        $this->db->where('id', $this->input->post('id'));
        $this->db->update('tbl_layanan_pu', $data);
        echo json_encode(array("status" => TRUE));
    }

    function delete($id)
    {
        $this->M_layanan_pu->delete($id);
        echo json_encode(array("status" => TRUE));
    }
}
