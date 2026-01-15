<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sml_kertas_kerja extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_sml_kertas_kerja');
        $this->M_login->getsecurity();
    }

    public function index()
    {
        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        ($akses->view_level == 'N' ? redirect('auth') : '');
        $data['add'] = $akses->add_level;
        $data['title'] = "backend/sml_kertas_kerja/sml_kertas_kerja_list";
        $data['titleview'] = "Kertas Kerja";
        $this->load->view('backend/home', $data);
    }

    function get_list()
    {
        $list = $this->M_sml_kertas_kerja->get_datatables();
        $data = array();
        $no = $_POST['start'];
        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());

        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = ($akses->view_level == 'Y' ? '<a href="sml_kertas_kerja/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>&nbsp;' : '') .
                ($akses->edit_level == 'Y' ? '<a href="sml_kertas_kerja/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;' : '') .
                ($akses->delete_level == 'Y' ? '<a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;' : '') .
                ($akses->print_level == 'Y' ? '<a href="sml_kertas_kerja/pdf/' . $field->id . '" target="_blank" class="btn btn-success btn-circle btn-sm" title="Read"><i class="far fa-file-pdf"></i></a>' : '');
            $row[] = $field->no_dok;
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
            "recordsTotal" => $this->M_sml_kertas_kerja->count_all(),
            "recordsFiltered" => $this->M_sml_kertas_kerja->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    function read_form($id)
    {
        $data['data'] = $this->M_sml_kertas_kerja->get_by_id($id);
        $data['aksi'] = 'read';
        $data['id'] = $id;
        $data['title_view'] = "Data Minutes Of Meeting";
        $data['title'] = 'backend/sml_kertas_kerja/sml_kertas_kerja_form';
        $this->load->view('backend/home', $data);
    }

    function add_form()
    {
        $data['id'] = 0;
        $data['title_view'] = "Kertas Kerja Form";
        $data['title'] = 'backend/sml_kertas_kerja/sml_kertas_kerja_form';
        $this->load->view('backend/home', $data);
    }

    function edit_form($id)
    {
        $data['data'] = $this->M_sml_kertas_kerja->get_by_id($id);
        $data['id'] = $id;
        $data['title_view'] = "Edit Data Minutes Of Meeting";
        $data['title'] = 'backend/sml_kertas_kerja/sml_kertas_kerja_form';
        $this->load->view('backend/home', $data);
    }

    function edit_data($id)
    {
        $data = $this->M_sml_kertas_kerja->get_by_id($id);
        echo json_encode($data);
    }

    public function add()
    {
        $data = array(
            'agenda' => $this->input->post('agenda'),
            'date' => date('Y-m-d', strtotime($this->input->post('date'))),
            'start_time' => $this->input->post('start_time'),
            'end_time' => $this->input->post('end_time'),
            'lokasi' => $this->input->post('lokasi'),
            'peserta' => $this->input->post('peserta'),
            'konten' => $this->input->post('konten'),
            'user' => $this->session->userdata('fullname'),
            'created_at' => date('Y-m-d H:i:s'),
        );
        $this->M_sml_kertas_kerja->save($data);
        echo json_encode(array("status" => TRUE));
    }

    public function update()
    {
        $data = array(
            'agenda' => $this->input->post('agenda'),
            'date' => date('Y-m-d', strtotime($this->input->post('date'))),
            'start_time' => $this->input->post('start_time'),
            'end_time' => $this->input->post('end_time'),
            'lokasi' => $this->input->post('lokasi'),
            'peserta' => $this->input->post('peserta'),
            'konten' => $this->input->post('konten'),
            'user' => $this->session->userdata('fullname'),
            'edit_at' =>  date('Y-m-d H:i:s'),
        );
        $this->db->where('id', $this->input->post('id'));
        $this->db->update('sml_kertas_kerja', $data);
        echo json_encode(array("status" => TRUE));
    }

    function delete($id)
    {
        $this->M_sml_kertas_kerja->delete($id);
        echo json_encode(array("status" => TRUE));
    }

}
