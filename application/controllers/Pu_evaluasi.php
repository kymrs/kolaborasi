<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pu_evaluasi extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_pu_evaluasi');
        $this->M_login->getsecurity();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $data['title'] = "backend/pu_evaluasi/pu_evaluasi_list";
        $data['titleview'] = "Data Evaluasi Vendor";
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
        $list = $this->M_pu_evaluasi->get_datatables($status);
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
            $row[] = $field->nama_hotel;
            $rating_star = '';
            if ($field->rating == 1) {
                $rating_star .= '<i class="fas fa-star"></i>';
            } else if ($field->rating == 2) {
                $rating_star .= '<i class="fas fa-star"></i><i class="fas fa-star"></i>';
            } else if ($field->rating == 3) {
                $rating_star .= '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>';
            } else if ($field->rating == 4) {
                $rating_star .= '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>';
            } else if ($field->rating == 5) {
                $rating_star .= '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>';
            }
            $row[] = $rating_star;
            $row[] = $field->kota;
            $row[] = $field->negara;
            $row[] = $field->created_at;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_pu_evaluasi->count_all(),
            "recordsFiltered" => $this->M_pu_evaluasi->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    function read_form($id)
    {
        $data['master'] = $this->M_pu_evaluasi->get_by_id($id);
        $data['title_view'] = "Data Evaluasi Vendor";
        $data['title'] = 'backend/pu_evaluasi/pu_evaluasi_read';
        $this->load->view('backend/home', $data);
    }

    function add_form()
    {
        $data['id'] = 0;
        $data['title_view'] = "Tambah Data Evaluasi Vendor";
        $data['title'] = 'backend/pu_evaluasi/pu_evaluasi_form';
        $this->load->view('backend/home', $data);
    }

    function edit_form($id)
    {
        $data['master'] = $this->M_pu_evaluasi->get_by_id($id);
        $data['title_view'] = "Edit Data Evaluasi Vendor";
        $data['title'] = 'backend/pu_evaluasi/pu_evaluasi_form';
        $this->load->view('backend/home', $data);
    }

    function get_id($id)
    {
        $data = $this->M_pu_evaluasi->get_by_id($id);
        echo json_encode($data);
    }

    public function add()
    {
        $data = array(
            'nama_program' => $this->input->post('nama_program'),
            'tgl_keberangkatan' => $this->input->post('tgl_keberangkatan'),
            'tgl_kepulangan' => $this->input->post('tgl_kepulangan'),
            'tour_leader' => $this->input->post('tour_leader'),
            'nama_hotel' => $this->input->post('nama_hotel')
        );

        $this->M_pu_evaluasi->save($data);
        echo json_encode(array("status" => TRUE));
    }

    public function update()
    {
        $data = array(
            'nama_program' => $this->input->post('nama_program'),
            'tgl_keberangkatan' => $this->input->post('tgl_keberangkatan'),
            'tgl_kepulangan' => $this->input->post('tgl_kepulangan'),
            'tour_leader' => $this->input->post('tour_leader'),
            'nama_hotel' => $this->input->post('nama_hotel')
        );
        $this->db->where('id', $this->input->post('id'));
        $this->db->update('pu_evaluasi', $data);
        echo json_encode(array("status" => TRUE));
    }

    function delete($id)
    {
        $this->M_pu_evaluasi->delete($id);
        echo json_encode(array("status" => TRUE));
    }
}
