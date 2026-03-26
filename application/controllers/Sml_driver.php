<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sml_driver extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_sml_driver');
        $this->M_login->getsecurity();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $data['title'] = "backend/sml_driver/sml_driver_list";
        $data['titleview'] = "Data Driver";
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
        $list = $this->M_sml_driver->get_datatables($status);
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
            $row[] = $field->nama_driver;
            $row[] = $field->no_hp;
            $row[] = $field->nama_driver2;
            $row[] = $field->no_hp2;
            $row[] = $field->nopol;
            $row[] = $field->tipe_unit;
            $row[] = (!empty($field->tgl_stnk) && $field->tgl_stnk !== '0000-00-00' && $field->tgl_stnk !== '0000-00-00 00:00:00')
                ? date('d-m-Y', strtotime($field->tgl_stnk))
                : '-';
            $row[] = (!empty($field->tgl_keur) && $field->tgl_keur !== '0000-00-00' && $field->tgl_keur !== '0000-00-00 00:00:00')
                ? date('d-m-Y', strtotime($field->tgl_keur))
                : '-';
            $row[] = date('d-m-Y H:i:s', strtotime($field->created_at));
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_sml_driver->count_all(),
            "recordsFiltered" => $this->M_sml_driver->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    // function read_form($id)
    // {
    //     $data['master'] = $this->M_sml_driver->get_by_id($id);
    //     $data['title_view'] = "Data Hotel";
    //     $data['title'] = 'backend/sml_driver/Hotel_read_pu';
    //     $this->load->view('backend/home', $data);
    // }

    // function add_form()
    // {
    //     $data['id'] = 0;
    //     $data['title_view'] = "Hotel Form";
    //     $data['title'] = 'backend/sml_driver/hotel_form_pu';
    //     $this->load->view('backend/home', $data);
    // }

    // function edit_form($id)
    // {
    //     $data['master'] = $this->M_sml_driver->get_by_id($id);
    //     $data['title_view'] = "Edit Data Hotel";
    //     $data['title'] = 'backend/sml_driver/hotel_form_pu';
    //     $this->load->view('backend/home', $data);
    // }

    function get_id($id)
    {
        $data = $this->M_sml_driver->get_by_id($id);
        echo json_encode($data);
    }

    public function add()
    {
        $data = array(
            'nama_driver' => $this->input->post('nama_driver'),
            'no_hp' => $this->input->post('no_hp'),
            'nama_driver2' => $this->input->post('nama_driver2'),
            'no_hp2' => $this->input->post('no_hp2'),
            'nopol' => $this->input->post('nopol'),
            'tipe_unit' => $this->input->post('tipe_unit'),
            'tgl_stnk' => $this->input->post('tgl_stnk'),
            'tgl_keur' => $this->input->post('tgl_keur'),
            'created_at' => date('Y-m-d H:i:s')
        );

        $this->M_sml_driver->save($data);
        echo json_encode(array("status" => TRUE));
    }

    public function update()
    {
        $data = array(
            'nama_driver' => $this->input->post('nama_driver'),
            'no_hp' => $this->input->post('no_hp'),
            'nama_driver2' => $this->input->post('nama_driver2'),
            'no_hp2' => $this->input->post('no_hp2'),
            'nopol' => $this->input->post('nopol'),
            'tipe_unit' => $this->input->post('tipe_unit'),
            'tgl_stnk' => $this->input->post('tgl_stnk'),
            'tgl_keur' => $this->input->post('tgl_keur')
        );
        $this->db->where('id', $this->input->post('id'));
        $this->db->update('sml_driver', $data);
        echo json_encode(array("status" => TRUE));
    }

    function delete($id)
    {
        $this->M_sml_driver->delete($id);
        echo json_encode(array("status" => TRUE));
    }
}
