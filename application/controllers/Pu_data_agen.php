<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pu_data_agen extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_pu_data_agen');
        $this->M_login->getsecurity();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $data['title'] = "backend/pu_data_agen/pu_data_agen_list";
        $data['titleview'] = "Data Agen";
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
        $status = $this->input->post('status'); // Ambil status dari permintaan POST
        $list = $this->M_pu_data_agen->get_datatables($status);
        $data = array();
        $no = $_POST['start'];

        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        $read = $akses->view_level;
        $edit = $akses->edit_level;
        $delete = $akses->delete_level;
        $print = $akses->print_level;

        foreach ($list as $field) {

            // MENENTUKAN ACTION APA YANG AKAN DITAMPILKAN DI LIST DATA TABLES
            $action_edit = ($edit == 'Y') ? '<a class="btn btn-warning btn-circle btn-sm" title="Edit" onclick="edit_data(' . "'" . $field->id . "'" . ')"><i class="fa fa-edit"></i></a>&nbsp;' : '';
            $action_delete = ($delete == 'Y') ? '<a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;' : '';

            $action = $action_edit . $action_delete;

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $action;
            $row[] = $field->nama;
            $row[] = $field->no_telp;
            $row[] = $field->alamat;
            $row[] = '<a href="#" class="lihat-ktp" data-img="' . base_url('assets/backend/document/ktp_agen_pu/' . $field->ktp) . '">Lihat KTP</a>';
            $row[] = $field->created_at;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_pu_data_agen->count_all(),
            "recordsFiltered" => $this->M_pu_data_agen->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    function add_form()
    {
        $data['id'] = 0;
        $data['title_view'] = "Hotel Form";
        $data['title'] = 'backend/pu_data_agen/hotel_form_pu';
        $this->load->view('backend/home', $data);
    }

    // function edit_form($id)
    // {
    //     $data['master'] = $this->M_pu_data_agen->get_by_id($id);
    //     $data['title_view'] = "Edit Data Hotel";
    //     $data['title'] = 'backend/pu_data_agen/hotel_form_pu';
    //     $this->load->view('backend/home', $data);
    // }

    function get_id($id)
    {
        $data = $this->M_pu_data_agen->get_by_id($id);
        echo json_encode($data);
    }

    public function add()
    {
        $data = array(
            'nama_hotel' => $this->input->post('nama_hotel'),
            'kota' => $this->input->post('kota'),
            'negara' => $this->input->post('negara'),
            'rating' => $this->input->post('rating')
        );

        $this->M_pu_data_agen->save($data);
        echo json_encode(array("status" => TRUE));
    }

    public function update()
    {
        $data = array(
            'nama_hotel' => $this->input->post('nama_hotel'),
            'kota' => $this->input->post('kota'),
            'negara' => $this->input->post('negara'),
            'rating' => $this->input->post('rating')
        );
        $this->db->where('id', $this->input->post('id'));
        $this->db->update('pu_hotel', $data);
        echo json_encode(array("status" => TRUE));
    }

    function delete($id)
    {
        $this->M_pu_data_agen->delete($id);
        echo json_encode(array("status" => TRUE));
    }
}
