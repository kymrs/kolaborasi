<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pu_travel extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_pu_travel');
        $this->M_login->getsecurity();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $data['title'] = "backend/pu_travel/pu_travel_list";
        $data['titleview'] = "Data Travel";
        $this->load->view('backend/home', $data);
    }

    function get_list()
    {
        $list = $this->M_pu_travel->get_datatables();
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
            $row[] = $field->travel;
            $row[] = $field->created_at;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_pu_travel->count_all(),
            "recordsFiltered" => $this->M_pu_travel->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    function get_id($id)
    {
        $data = $this->M_pu_travel->get_by_id($id);
        echo json_encode($data);
    }

    public function add()
    {
        $data = array(
            'travel' => $this->input->post('travel'),
            'nama_bank' => $this->input->post('nama_bank'),
            'no_rek' => $this->input->post('no_rek'),
        );

        $this->M_pu_travel->save($data);
        echo json_encode(array("status" => TRUE));
    }

    public function update()
    {
        $data = array(
            'travel' => $this->input->post('travel'),
            'nama_bank' => $this->input->post('nama_bank'),
            'no_rek' => $this->input->post('no_rek'),
        );
        $this->db->where('id', $this->input->post('id'));
        $this->db->update('pu_travel', $data);
        echo json_encode(array("status" => TRUE));
    }

    function delete($id)
    {
        $this->M_pu_travel->delete($id);
        echo json_encode(array("status" => TRUE));
    }
}
