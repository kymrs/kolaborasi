<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Prepayment extends CI_Controller
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('backend/M_prepayment');
        $this->M_login->getsecurity();
    }

    public function index()
    {
        $data['title'] = "backend/prepayment/prepayment_list"
        $data['titleview'] = "Data Prepayment";
        $this->load->view('backend/home', $data);
    }

    function get_list()
    {
        $list = $this->M_databooking->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<a href="databooking/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
            <a href="databooking/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
			<a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>';
            $row[] = $field->kode_booking;
            $row[] = $field->nama;
            $row[] = $field->no_hp;
            $row[] = $field->email;
            $row[] = $field->tgl_berangkat;
            $row[] = $field->tgl_pulang;
            $row[] = $field->jam_jemput;
            $row[] = $field->titik_jemput;
            $row[] = $field->type_kendaraan;
            $row[] = $field->jumlah;
            $row[] = $field->status;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_databooking->count_all(),
            "recordsFiltered" => $this->M_databooking->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    public function add_form()
    {
        $data['title'] = 'backend/prepayment/prepayment_form';
        $data['title_view'] = 'Prepayment Form';
        $this->load->view('backend/home', $data);
    }

    public function add() {
        $data = array(
            'kode_prepayment' => $this->input->post('kode_prepayment'),
            'nama' => $this->input->post('nama'),
            'jabatan' => $this->input->post('jabatan'),
            'prepayment' => $this->input->post('prepayment'),
            'tujuan' => $this->input->post('tujuan'),
            'tgl_prepayment' => date('Y-m-d', strtotime($this->input->post('tgl_prepayment')))
        );
        $this->M_prepayment->save($data);
        echo json_encode(array("status" => TRUE));
    }
}