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
        $data['title'] = "backend/prepayment/prepayment_list";
        $data['titleview'] = "Data Prepayment";
        $this->load->view('backend/home', $data);
    }

    function get_list()
    {
        $list = $this->M_prepayment->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->kode_prepayment;
            $row[] = $field->nama;
            $row[] = $field->jabatan;
            $row[] = $field->prepayment;
            $row[] = $field->tgl_prepayment;
            $row[] = $field->status;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_prepayment->count_all(),
            "recordsFiltered" => $this->M_prepayment->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    function read_form($id)
    {
        $data['aksi'] = 'read';
        $data['id'] = $id;
        $data['title_view'] = "Data Prepayment";
        $data['title'] = 'backend/prepayment/prepayment_form';
        $this->load->view('backend/home', $data);
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