<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mac_mom_admin extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_mac_mom_admin');
        $this->M_login->getsecurity();
    }

    public function index()
    {
        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        ($akses->view_level == 'N' ? redirect('auth') : '');
        $data['add'] = $akses->add_level;
        $data['title'] = "backend/mac_mom_admin/mom_list_mac";
        $data['titleview'] = "Data Minutes Of Meeting";
        $this->load->view('backend/home', $data);
    }

    function get_list()
    {
        $list = $this->M_mac_mom_admin->get_datatables();
        $data = array();
        $no = $_POST['start'];
        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = ($akses->view_level == 'Y' ? '<a href="mac_mom_admin/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>&nbsp;' : '') .
                ($akses->print_level == 'Y' ? '<a href="mac_mom_admin/pdf/' . $field->id . '" target="_blank" class="btn btn-success btn-circle btn-sm" title="Read"><i class="far fa-file-pdf"></i></a>' : '');
            $row[] = $field->no_dok;
            $row[] = $field->user;
            $row[] = $field->agenda;
            $row[] = $field->date;
            $row[] = $field->start_time;
            $row[] = $field->end_time;
            $row[] = $field->lokasi;
            $row[] = $field->peserta;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_mac_mom_admin->count_all(),
            "recordsFiltered" => $this->M_mac_mom_admin->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    function read_form($id)
    {
        $data['data'] = $this->M_mac_mom_admin->get_by_id($id);
        $data['aksi'] = 'read';
        $data['id'] = $id;
        $data['title_view'] = "Data Minutes Of Meeting";
        $data['title'] = 'backend/mac_mom_admin/mom_form_mac';
        $this->load->view('backend/home', $data);
    }

    function edit_data($id)
    {
        $data = $this->M_mac_mom_admin->get_by_id($id);
        echo json_encode($data);
    }

    function pdf($id)
    {
        $mpdf = new \Mpdf\Mpdf(['format' => 'A4']);
        $data = $this->M_mac_mom_admin->get_by_id($id);
        $data2 = $this->load->view('backend/mac_mom_admin/mom_pdf_mac', $data, TRUE);
        $mpdf->WriteHTML($data2);
        $mpdf->Output();
    }
}
