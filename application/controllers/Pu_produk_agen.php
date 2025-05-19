<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pu_produk_agen extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_pu_produk_agen');
        $this->M_login->getsecurity();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $data['title'] = "backend/pu_produk_agen/pu_produk_agen_list";
        $data['titleview'] = "Data Produk Agen";
        $this->load->view('backend/home', $data);
    }

    function get_list()
    {
        $status = $this->input->post('statuzs'); // Ambil status dari permintaan POST
        $list = $this->M_pu_produk_agen->get_datatables($status);
        $data = array();
        $no = $_POST['start'];

        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        $edit = $akses->edit_level;
        $delete = $akses->delete_level;

        foreach ($list as $field) {

            // MENENTUKAN ACTION APA YANG AKAN DITAMPILKAN DI LIST DATA TABLES
            $action_edit = ($edit == 'Y') ? '<a href="' . base_url('pu_produk_agen/edit_form/' . $field->id) . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;' : '';
            $action_delete = ($delete == 'Y') ? '<a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;' : '';

            $action = $action_edit . $action_delete;

            $kategori = $this->db->select('kategori')
                ->from('pu_ktgri_produk_agen')
                ->where('id', $field->kategori_id)
                ->get();

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $action;
            $row[] = $kategori->row('kategori');
            $row[] = $field->nama_produk;
            $row[] = 'Rp. ' . number_format($field->harga_paket, 0, ',', '.');
            $row[] = 'Rp. ' . number_format($field->fee_agen, 0, ',', '.');
            $row[] = '<a href="#" class="lihat-produk" data-img="' . base_url('assets/backend/document/pu_produk_agen/' . $field->image) . '">Lihat Produk</a>';
            $row[] = $field->hotel;
            $row[] = $field->rating;
            $row[] = $field->maskapai;
            $row[] = $field->layanan_unggul;
            $row[] = $field->created_at;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_pu_produk_agen->count_all(),
            "recordsFiltered" => $this->M_pu_produk_agen->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    function add_form()
    {
        $data['id'] = 0;
        $data['kategori'] = $this->db->get('pu_ktgri_produk_agen')->result();
        $data['title_view'] = "Produk Agen Form";
        $data['title'] = 'backend/pu_produk_agen/pu_produk_agen_form';
        $this->load->view('backend/home', $data);
    }

    function edit_form($id)
    {
        $data['master'] = $this->M_pu_produk_agen->get_by_id($id);
        $data['title_view'] = "Edit Data Produk Agen";
        $data['title'] = 'backend/pu_produk_agen/pu_produk_agen_form';
        $this->load->view('backend/home', $data);
    }

    function get_id($id)
    {
        $data = $this->M_pu_produk_agen->get_by_id($id);
        echo json_encode($data);
    }

    public function add()
    {
        $data = array(
            'kategori_id' => $this->input->post('kategori_id'),
            'nama_produk' => $this->input->post('nama_produk'),
            'hotel' => $this->input->post('hotel'),
            'rating' => $this->input->post('rating'),
            'maskapai' => $this->input->post('maskapai'),
            'layanan_unggul' => $this->input->post('layanan_unggul'),
            'harga_paket' => preg_replace('/\D/', '', $this->input->post('harga_paket')),
            'fee_agen' => preg_replace('/\D/', '', $this->input->post('fee_agen'))
        );

        $this->M_pu_produk_agen->save($data);
        echo json_encode(array("status" => TRUE));
    }

    public function update()
    {
        $data = array(
            'kategori_id' => $this->input->post('kategori_id'),
            'nama_produk' => $this->input->post('nama_produk'),
            'hotel' => $this->input->post('hotel'),
            'rating' => $this->input->post('rating'),
            'maskapai' => $this->input->post('maskapai'),
            'layanan_unggul' => $this->input->post('layanan_unggul'),
            'harga_paket' => preg_replace('/\D/', '', $this->input->post('harga_paket')),
            'fee_agen' => preg_replace('/\D/', '', $this->input->post('fee_agen'))
        );
        $this->db->where('id', $this->input->post('id'));
        $this->db->update('pu_hotel', $data);
        echo json_encode(array("status" => TRUE));
    }

    function delete($id)
    {
        $this->M_pu_produk_agen->delete($id);
        echo json_encode(array("status" => TRUE));
    }

    public function api_produk_agen()
    {
        // CORS
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        header("Content-Type: application/json; charset=UTF-8");

        // Handle preflight
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            http_response_code(200);
            exit();
        }

        // Ambil data dari tabel pu_produk_agen
        $produk = $this->db->get('pu_produk_agen')->result();

        // Kirim data dalam format JSON
        echo json_encode([
            "status" => true,
            "data" => $produk
        ]);
    }
}
