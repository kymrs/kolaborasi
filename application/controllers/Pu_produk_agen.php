<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pu_produk_agen extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_pu_produk_agen');
        date_default_timezone_set('Asia/Jakarta');

        $allowed_origin = 'https://agen.pengenumroh.com';
        $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';

        if ($origin === $allowed_origin) {
            header("Access-Control-Allow-Origin: $origin");
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
            header("Access-Control-Allow-Headers: Content-Type, Authorization");
        }

        // Tangani preflight OPTIONS
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            header("HTTP/1.1 200 OK");
            exit;
        }
    }

    public function index()
    {
        $this->M_login->getsecurity();
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
        $read = $akses->view_level;
        $edit = $akses->edit_level;
        $delete = $akses->delete_level;

        foreach ($list as $field) {

            // MENENTUKAN ACTION APA YANG AKAN DITAMPILKAN DI LIST DATA TABLES
            $action_read = ($read == 'Y') ? '<a href="pu_produk_agen/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>&nbsp;' : '';
            $action_edit = ($edit == 'Y') ? '<a href="' . base_url('pu_produk_agen/edit_form/' . $field->id) . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;' : '';
            $action_delete = ($delete == 'Y') ? '<a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;' : '';

            $action = $action_read . $action_edit . $action_delete;

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $action;
            $row[] = '<button type="button" class="btn btn-sm status-produk-modal-trigger ' .
                ($field->is_active == 1 ? 'btn-outline-success' : 'btn-outline-danger') . '" 
                data-id="' . $field->id . '" 
                data-status="' . $field->is_active . '">'
                . ($field->is_active == 1 ? '<i class="fa fa-check-circle"></i> Aktif' : '<i class="fa fa-times-circle"></i> Tidak Aktif')
                . '</button>';
            $row[] = $field->nama_produk;
            $row[] = $field->travel;
            $row[] = 'Rp. ' . number_format($field->harga_paket, 0, ',', '.');
            $row[] = 'Rp. ' . number_format($field->fee_agen, 0, ',', '.');
            $row[] = date('d-m-Y', strtotime($field->tanggal_keberangkatan));
            $row[] = $field->sisa_seat;
            $row[] = '<a href="#" class="lihat-produk" data-img="' . base_url('assets/backend/document/pu_produk_agen/' . $field->image) . '">Lihat Foto</a>';
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
        $this->M_login->getsecurity();
        $data['id'] = 0;
        $data['travels'] = $this->db->get('pu_travel')->result();
        $data['title_view'] = "Produk Agen Form";
        $data['title'] = 'backend/pu_produk_agen/pu_produk_agen_form';
        $this->load->view('backend/home', $data);
    }

    function edit_form($id)
    {
        $data['id'] = $id;
        $data['aksi'] = 'update';
        $data['title_view'] = "Edit Data Produk Agen";
        $data['travels'] = $this->db->get('pu_travel')->result();
        $data['title'] = 'backend/pu_produk_agen/pu_produk_agen_form';
        $this->load->view('backend/home', $data);
    }

    function edit_data($id)
    {
        $data['master'] = $this->M_pu_produk_agen->get_by_id($id);
        echo json_encode($data);
    }

    function read_form($id)
    {
        $data['aksi'] = 'read';
        $data['id'] = $id;
        $data['title_view'] = "Data Produk Agen";
        $data['travels'] = $this->db->get('pu_travel')->result();
        $data['title'] = 'backend/pu_produk_agen/pu_produk_agen_form';
        $this->load->view('backend/home', $data);
    }

    public function add()
    {
        // Handle upload gambar produk
        $image_name = null;
        if (!empty($_FILES['image']['name'])) {
            $config['upload_path']   = './assets/backend/document/pu_produk_agen/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size']      = 4096; // 4MB
            $config['encrypt_name']  = TRUE;

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('image')) {
                $upload_data = $this->upload->data();
                $image_name = $upload_data['file_name'];

                // Kompres gambar
                $config['image_library'] = 'gd2';
                $config['source_image'] = $upload_data['full_path'];
                $config['create_thumb'] = FALSE;
                $config['maintain_ratio'] = TRUE;
                $config['quality'] = '80';
                $config['width'] = 1400;
                $config['height'] = 1400;

                $this->load->library('image_lib', $config);
                $this->image_lib->resize();
                $this->image_lib->clear();
            } else {
                echo json_encode([
                    "status" => FALSE,
                    "error" => $this->upload->display_errors()
                ]);
                return;
            }
        }

        $data = array(
            'nama_produk' => $this->input->post('nama_produk'),
            'travel' => $this->input->post('travel'),
            'tanggal_keberangkatan' => date('Y-m-d', strtotime($this->input->post('tanggal_keberangkatan'))),
            'harga_paket' => preg_replace('/\D/', '', $this->input->post('harga_paket')),
            'fee_agen' => preg_replace('/\D/', '', $this->input->post('fee_agen')),
            'sisa_seat' => $this->input->post('sisa_seat'),
            'image' => $image_name, // simpan nama file gambar
            'is_active' => 1,
            'created_at' =>  date('Y-m-d H:i:s')
        );

        $this->M_pu_produk_agen->save($data);
        echo json_encode(array("status" => TRUE));
    }

    public function update()
    {
        // Ambil data lama untuk cek gambar sebelumnya
        $id = $this->input->post('id');
        $produk = $this->M_pu_produk_agen->get_by_id($id);
        $image_name = $produk->image;

        // Jika ada file gambar baru diupload
        if (!empty($_FILES['image']['name'])) {
            $config['upload_path']   = './assets/backend/document/pu_produk_agen/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size']      = 4096; // 4MB
            $config['encrypt_name']  = TRUE;

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('image')) {
                // Hapus gambar lama jika ada
                if ($image_name && file_exists($config['upload_path'] . $image_name)) {
                    @unlink($config['upload_path'] . $image_name);
                }
                $upload_data = $this->upload->data();
                $image_name = $upload_data['file_name'];

                // Kompres gambar
                $config['image_library'] = 'gd2';
                $config['source_image'] = $upload_data['full_path'];
                $config['create_thumb'] = FALSE;
                $config['maintain_ratio'] = TRUE;
                $config['quality'] = '80';
                $config['width'] = 1400;
                $config['height'] = 1400;

                $this->load->library('image_lib', $config);
                $this->image_lib->resize();
                $this->image_lib->clear();
            } else {
                echo json_encode([
                    "status" => FALSE,
                    "error" => $this->upload->display_errors()
                ]);
                return;
            }
        }

        $data = array(
            'nama_produk' => $this->input->post('nama_produk'),
            'travel' => $this->input->post('travel'),
            'tanggal_keberangkatan' => date('Y-m-d', strtotime($this->input->post('tanggal_keberangkatan'))),
            'harga_paket' => preg_replace('/\D/', '', $this->input->post('harga_paket')),
            'fee_agen' => preg_replace('/\D/', '', $this->input->post('fee_agen')),
            'harga_paket' => preg_replace('/\D/', '', $this->input->post('harga_paket')),
            'fee_agen' => preg_replace('/\D/', '', $this->input->post('fee_agen')),
            'sisa_seat' => $this->input->post('sisa_seat'),
            'image' => $image_name // update nama file gambar
        );
        $this->db->where('id', $id);
        $this->db->update('pu_produk_agen', $data);
        echo json_encode(array("status" => TRUE));
    }

    function delete($id)
    {
        // Ambil data produk untuk mendapatkan nama file gambar
        $produk = $this->M_pu_produk_agen->get_by_id($id);
        if ($produk && $produk->image) {
            $file_path = './assets/backend/document/pu_produk_agen/' . $produk->image;
            if (file_exists($file_path)) {
                @unlink($file_path);
            }
        }

        // Hapus data dari database
        $this->M_pu_produk_agen->delete($id);
        echo json_encode(array("status" => TRUE));
    }

    public function update_status()
    {
        $id = $this->input->post('id');
        $is_active = $this->input->post('is_active');
        $this->db->where('id', $id);
        $this->db->update('pu_produk_agen', ['is_active' => $is_active]);
        echo json_encode(['status' => true, 'message' => 'Status produk berhasil diupdate']);
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
