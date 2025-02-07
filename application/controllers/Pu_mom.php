<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pu_mom extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_pu_mom');
        $this->M_login->getsecurity();
    }

    public function index()
    {
        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        ($akses->view_level == 'N' ? redirect('auth') : '');
        $data['add'] = $akses->add_level;
        $data['title'] = "backend/pu_mom/mom_list_pu";
        $data['titleview'] = "Data Minutes Of Meeting";
        $this->load->view('backend/home', $data);
    }

    function get_list()
    {
        $list = $this->M_pu_mom->get_datatables();
        $data = array();
        $no = $_POST['start'];
        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = ($akses->view_level == 'Y' ? '<a href="pu_mom/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>&nbsp;' : '') .
                ($akses->edit_level == 'Y' ? '<a href="pu_mom/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;' : '') .
                ($akses->delete_level == 'Y' ? '<a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;' : '') .
                ($akses->print_level == 'Y' ? '<a href="pu_mom/pdf/' . $field->id . '" target="_blank" class="btn btn-success btn-circle btn-sm" title="Read"><i class="far fa-file-pdf"></i></a>' : '');
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
            "recordsTotal" => $this->M_pu_mom->count_all(),
            "recordsFiltered" => $this->M_pu_mom->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    function read_form($id)
    {
        $data['data'] = $this->M_pu_mom->get_by_id($id);
        $data['aksi'] = 'read';
        $data['id'] = $id;
        $data['title_view'] = "Data Minutes Of Meeting";
        $data['title'] = 'backend/pu_mom/mom_form_pu';
        $this->load->view('backend/home', $data);
    }

    function add_form()
    {
        $kode = $this->M_pu_mom->max_kode()->row();
        if (empty($kode->no_dok)) {
            $no_urut = 1;
        } else {
            $bln = substr($kode->no_dok, 5, 2);
            if ($bln != date('m')) {
                $no_urut = 1;
            } else {
                $no_urut = substr($kode->no_dok, 7) + 1;
            }
        }
        $urutan = str_pad($no_urut, 3, "0", STR_PAD_LEFT);
        $data['kode'] = 'MOM' . date('ym') . $urutan;
        $data['id'] = 0;
        $data['title_view'] = "Data Minutes Of Meeting";
        $data['title'] = 'backend/pu_mom/mom_form_pu';
        $this->load->view('backend/home', $data);
    }

    function edit_form($id)
    {
        $data['data'] = $this->M_pu_mom->get_by_id($id);
        $data['id'] = $id;
        $data['title_view'] = "Edit Data Minutes Of Meeting";
        $data['title'] = 'backend/pu_mom/mom_form_pu';
        $this->load->view('backend/home', $data);
    }

    function edit_data($id)
    {
        $data = $this->M_pu_mom->get_by_id($id);
        echo json_encode($data);
    }

    public function add()
    {
        $kode = $this->M_pu_mom->max_kode()->row();
        if (empty($kode->no_dok)) {
            $no_urut = 1;
        } else {
            $bln = substr($kode->no_dok, 5, 2);
            if ($bln != date('m')) {
                $no_urut = 1;
            } else {
                $no_urut = substr($kode->no_dok, 7) + 1;
            }
        }
        $urutan = str_pad($no_urut, 3, "0", STR_PAD_LEFT);
        $no_dok = 'MOM' . date('ym') . $urutan;

        $temp = explode(".", $_FILES["foto"]["name"]);
        $extension = end($temp);
        $new_name = time() . "_foto." . $extension;
        $config['upload_path']   = './assets/backend/document/mom/foto_mom_pu';
        $config['allowed_types'] = 'jpg|jpeg|png|pdf';
        $config['file_name'] = $new_name;
        $this->load->library('upload', $config);
        if ($this->upload->do_upload("foto")) {
            $data = array('upload_data' => $this->upload->data());
            $foto = $data['upload_data']['file_name'];
        } else {
            $foto = "";
        }

        $data = array(
            'no_dok' => $no_dok,
            'agenda' => $this->input->post('agenda'),
            'date' => date('Y-m-d', strtotime($this->input->post('date'))),
            'start_time' => $this->input->post('start_time'),
            'end_time' => $this->input->post('end_time'),
            'lokasi' => $this->input->post('lokasi'),
            'peserta' => $this->input->post('peserta'),
            'konten' => $this->input->post('konten'),
            'foto' => $foto,
            'user' => $this->session->userdata('fullname'),
            'created_at' => date('Y-m-d H:i:s'),
        );
        $this->M_pu_mom->save($data);
        echo json_encode(array("status" => TRUE));
    }

    public function update()
    {
        $upload = $this->M_pu_mom->get_by_id($this->input->post('id'));
        $temp = explode(".", $_FILES["foto"]["name"]);
        $extension = end($temp);
        $new_name = time() . "_foto." . $extension;
        $config['upload_path']   = './assets/backend/document/mom/foto_mom_pu';
        $config['allowed_types'] = 'jpg|jpeg|png|pdf';
        $config['file_name'] = $new_name;
        $this->load->library('upload', $config);

        if (!empty($_FILES['foto']['name'])) {
            if ($this->upload->do_upload("foto")) {
                $data = array('upload_data' => $this->upload->data());
                $foto = $data['upload_data']['file_name'];
            }
            $path = './assets/backend/document/mom/foto_mom_pu/' . $upload->foto;
            if (is_file($path)) {
                unlink($path);
            }
        } else {
            $foto =  $upload->foto;
        }

        $data = array(
            'agenda' => $this->input->post('agenda'),
            'date' => date('Y-m-d', strtotime($this->input->post('date'))),
            'start_time' => $this->input->post('start_time'),
            'end_time' => $this->input->post('end_time'),
            'lokasi' => $this->input->post('lokasi'),
            'peserta' => $this->input->post('peserta'),
            'konten' => $this->input->post('konten'),
            'foto' => $foto,
            'user' => $this->session->userdata('fullname'),
            'edit_at' =>  date('Y-m-d H:i:s'),
        );
        $this->db->where('id', $this->input->post('id'));
        $this->db->update('pu_mom', $data);
        echo json_encode(array("status" => TRUE));
    }

    function delete($id)
    {
        $data = $this->M_pu_mom->get_by_id($id);
        $path = './assets/backend/document/mom/foto_mom_pu/' . $data->foto;
        if (is_file($path)) {
            unlink($path);
        }
        $this->M_pu_mom->delete($id);
        echo json_encode(array("status" => TRUE));
    }

    function pdf($id)
    {
        $mpdf = new \Mpdf\Mpdf(['format' => 'A4']);
        $data = $this->M_pu_mom->get_by_id($id);
        $data2 = $this->load->view('backend/pu_mom/mom_pdf_pu', $data, TRUE);
        $mpdf->WriteHTML($data2);
        $mpdf->Output();
    }
}
