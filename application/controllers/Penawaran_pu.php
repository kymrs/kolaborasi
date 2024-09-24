<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penawaran_pu extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_penawaran_pu');
        $this->M_login->getsecurity();
        $this->load->library('ciqrcode');
    }

    public function index()
    {
        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        ($akses->view_level == 'N' ? redirect('auth') : '');
        $data['add'] = $akses->add_level;


        $data['title'] = "backend/penawaran_pu/penawaran_list_pu";
        $data['titleview'] = "Data Penawaran";
        $name = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $this->session->userdata('id_user'))
            ->get()
            ->row('name');
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
        $list = $this->M_penawaran_pu->get_datatables();
        $data = array();
        $no = $_POST['start'];

        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        $read = $akses->view_level;
        $edit = $akses->edit_level;
        $delete = $akses->delete_level;
        $print = $akses->print_level;

        //LOOPING DATATABLES
        foreach ($list as $field) {

            $action_read = ($read == 'Y') ? '<a href="penawaran_pu/read_form/' . $field->no_arsip . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>&nbsp;' : '';
            $action_edit = ($edit == 'Y') ? '<a href="penawaran_pu/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;' : '';
            $action_delete = ($delete == 'Y') ? '<a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;' : '';
            $action_print = ($print == 'Y') ? '<a class="btn btn-success btn-circle btn-sm" target="_blank" href="penawaran_pu/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>' : '';

            // MENENTUKAN ACTION APA YANG AKAN DITAMPILKAN DI LIST DATA TABLES
            $action = $action_read . $action_edit . $action_delete . $action_print;

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $action;
            $row[] = strtoupper($field->no_pelayanan);
            $row[] = $field->pelanggan;
            $row[] = strtoupper($field->id_produk);
            $row[] = date("d M Y", strtotime($field->created_at));
            $row[] = date("d M Y", strtotime($field->tgl_berlaku));

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_penawaran_pu->count_all(),
            "recordsFiltered" => $this->M_penawaran_pu->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    public function read_form()
    {
        $kode = $this->uri->segment(3);
        // var_dump($kode);
        $data['penawaran'] = $this->M_penawaran_pu->getPenawaran($kode);

        if ($data['penawaran'] == null) {
            $this->load->view('backend/penawaran_pu/404');
        } else {
            $no_arsip = $data['penawaran']['no_arsip'];

            $params['data'] = 'https://arsip.pengenumroh.com/' . $no_arsip;
            $params['level'] = 'H';
            $params['size'] = 10;
            $params['savename'] = 'assets/backend/document/qrcode/qr-' . $no_arsip . '.png';
            $this->ciqrcode->generate($params);

            $data['title'] = 'backend/penawaran_pu/penawaran_read_pu';
            $data['title_view'] = 'Prepayment';
            $this->load->view('backend/home', $data);
        }
    }

    public function add_form()
    {
        $data['id'] = 0;
        $data['title'] = 'backend/penawaran_pu/penawaran_form_pu';
        $data['products'] = $this->db->select('id, nama')->from('tbl_produk')->get()->result_object();
        $data['title_view'] = 'Penawaran Form';
        $this->load->view('backend/home', $data);
    }

    public function generate_kode()
    {
        $date = date('Y-m-d h:i:sa');
        $kode = $this->M_penawaran_pu->max_kode($date)->row();
        if (empty($kode->no_pelayanan)) {
            $no_urut = 1;
        } else {
            $no_urut = substr($kode->no_pelayanan, 9, 3);
        }
        $urutan = str_pad(number_format($no_urut + 1), 3, "0", STR_PAD_LEFT);
        $year = substr($date, 0, 4);
        $data = 'UMROH/LA/' . $urutan . '/' . 'IX' . '/' . $year;
        echo json_encode($data);
    }

    public function generate_layanan()
    {
        $layanan = $this->db->from('tbl_produk')
            ->where('id', $this->input->post('id'))
            ->get()
            ->row();
        echo json_encode($layanan);
    }

    public function add()
    {
        //GENERATE NOMOR PELAYANAN
        $date = date('Y-m-d h:i:sa');
        $kode = $this->M_penawaran_pu->max_kode($date)->row();
        if (empty($kode->no_pelayanan)) {
            $no_urut = 1;
        } else {
            $no_urut = substr($kode->no_pelayanan, 9, 3);
            $no_urut2 = substr($kode->no_arsip, 6) + 1;
        }
        $urutan = str_pad(number_format($no_urut + 1), 3, "0", STR_PAD_LEFT);
        $year = substr($date, 0, 4);
        $no_pelayanan = 'UMROH/LA/' . $urutan . '/' . 'IX' . '/' . $year;

        //GENERATE NOMOR ARSIP
        $urutan2 = str_pad($no_urut2, 2, "0", STR_PAD_LEFT);
        $no_arsip = 'PU' . $year . '09' . $urutan2;


        $data = array(
            'no_pelayanan' => $no_pelayanan,
            'no_arsip' => $no_arsip,
            'tgl_berlaku' => $date,
            'id_produk' => 1,
            'pelanggan' => $this->input->post('pelanggan'),
            'catatan' => $this->input->post('editor_content')
        );

        $this->M_penawaran_pu->save($data);
        echo json_encode(array("status" => TRUE));
    }
}
