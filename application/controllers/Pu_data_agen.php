<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pu_data_agen extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_pu_data_agen');
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $this->M_login->getsecurity();
        $data['title'] = "backend/pu_data_agen/pu_data_agen_list";
        $data['titleview'] = "Data Agen";
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
        $list = $this->M_pu_data_agen->get_datatables($status);
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
            $row[] = $field->nama;
            $row[] = $field->no_telp;
            $row[] = $field->alamat;
            $row[] = '<a href="#" class="lihat-ktp" data-img="' . base_url('assets/backend/document/pu_data_agen/' . $field->ktp) . '">Lihat KTP</a>';
            $row[] = $field->created_at;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_pu_data_agen->count_all(),
            "recordsFiltered" => $this->M_pu_data_agen->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    function add_form()
    {
        $this->M_login->getsecurity();
        $data['id'] = 0;
        $data['title_view'] = "Hotel Form";
        $data['title'] = 'backend/pu_data_agen/hotel_form_pu';
        $this->load->view('backend/home', $data);
    }

    // function edit_form($id)
    // {
    //     $data['master'] = $this->M_pu_data_agen->get_by_id($id);
    //     $data['title_view'] = "Edit Data Hotel";
    //     $data['title'] = 'backend/pu_data_agen/hotel_form_pu';
    //     $this->load->view('backend/home', $data);
    // }

    function get_id($id)
    {
        $data = $this->M_pu_data_agen->get_by_id($id);
        echo json_encode($data);
    }

    public function add()
    {
        // Konfigurasi upload
        $config['upload_path']   = './assets/backend/document/pu_data_agen/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size']      = 2048; // 2MB
        $config['encrypt_name']  = TRUE;

        $this->load->library('upload', $config);

        $ktp_file = null;
        if (!empty($_FILES['ktp']['name'])) {
            if (!$this->upload->do_upload('ktp')) {
                echo json_encode([
                    "status" => FALSE,
                    "error" => $this->upload->display_errors('', '')
                ]);
                return;
            }
            $upload_data = $this->upload->data();
            $ktp_file = $upload_data['file_name'];
        }

        $data = array(
            'nama'      => $this->input->post('nama_agen'),
            'no_telp'   => $this->input->post('no_telp'),
            'alamat'    => $this->input->post('alamat'),
            'ktp'       => $ktp_file,
            'password'    => $this->input->post('password'),
            'created_at' => date('Y-m-d H:i:s')
        );

        $this->M_pu_data_agen->save($data);
        echo json_encode(array("status" => TRUE));
    }

    public function update()
    {
        $id = $this->input->post('id');
        $agen = $this->M_pu_data_agen->get_by_id($id);
        $ktp_file = $agen->ktp;

        // Cek jika ada file KTP baru diupload
        if (!empty($_FILES['ktp']['name'])) {
            $config['upload_path']   = './assets/backend/document/pu_data_agen/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size']      = 2048; // 2MB
            $config['encrypt_name']  = TRUE;

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('ktp')) {
                // Hapus file lama jika ada
                if ($ktp_file && file_exists($config['upload_path'] . $ktp_file)) {
                    @unlink($config['upload_path'] . $ktp_file);
                }
                $upload_data = $this->upload->data();
                $ktp_file = $upload_data['file_name'];
            } else {
                echo json_encode([
                    "status" => FALSE,
                    "error" => $this->upload->display_errors('', '')
                ]);
                return;
            }
        }

        $data = array(
            'nama'      => $this->input->post('nama_agen'),
            'no_telp'   => $this->input->post('no_telp'),
            'alamat'    => $this->input->post('alamat'),
            'ktp'       => $ktp_file,
            'password'  => $this->input->post('password'),
            'created_at' => $agen->created_at // atau update field lain sesuai kebutuhan
        );

        $this->db->where('id', $id);
        $this->db->update('pu_data_agen', $data);
        echo json_encode(array("status" => TRUE));
    }

    function delete($id)
    {
        // Ambil data agen untuk mendapatkan nama file KTP
        $agen = $this->M_pu_data_agen->get_by_id($id);
        if ($agen && $agen->ktp) {
            $file_path = './assets/backend/document/pu_data_agen/' . $agen->ktp;
            if (file_exists($file_path)) {
                @unlink($file_path);
            }
        }

        // Hapus data dari database
        $this->M_pu_data_agen->delete($id);
        echo json_encode(array("status" => TRUE));
    }

    public function api_data_agen()
    {
        // Izinkan akses dari semua origin (CORS)
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        header("Content-Type: application/json; charset=UTF-8");

        // Handle preflight request
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            http_response_code(200);
            exit();
        }

        // Ambil data dari tabel pu_data_agen
        $data = $this->db->get('pu_data_agen')->result();

        // Kirim data dalam format JSON
        echo json_encode([
            "status" => true,
            "data" => $data
        ]);
    }

    public function api_tambah_agen()
    {
        // Izinkan akses dari semua origin (CORS)
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        header("Content-Type: application/json; charset=UTF-8");

        // Validasi method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['status' => false, 'message' => 'Invalid request method']);
            return;
        }

        // Ambil data POST
        $nama     = $this->input->post('nama', TRUE);
        $no_telp  = $this->input->post('no_telp', TRUE);
        $alamat   = $this->input->post('alamat', TRUE);
        $password = $this->input->post('password', TRUE);

        // Validasi data
        if (!$nama || !$no_telp || !$alamat || !$password || !isset($_FILES['foto_ktp'])) {
            echo json_encode(['status' => false, 'message' => 'Data tidak lengkap']);
            return;
        }

        // Upload foto KTP
        $config['upload_path']   = './assets/backend/document/pu_data_agen/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size']      = 2048; // 2MB
        $config['encrypt_name']  = TRUE;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('foto_ktp')) {
            echo json_encode(['status' => false, 'message' => $this->upload->display_errors('', '')]);
            return;
        }

        $upload_data = $this->upload->data();
        $ktp_path = $upload_data['file_name'];

        // Simpan ke database (ganti 'agen' sesuai nama tabel kamu)
        $data = [
            'nama'     => $nama,
            'no_telp'  => $no_telp,
            'alamat'   => $alamat,
            'password' => $password, // Untuk produksi, sebaiknya hash password!
            'ktp'      => $ktp_path,
            'created_at' =>  date('Y-m-d H:i:s')
        ];

        $insert = $this->db->insert('pu_data_agen', $data);

        if ($insert) {
            echo json_encode(['status' => true, 'message' => 'Agen berhasil didaftarkan']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Gagal menyimpan data agen']);
        }
    }
}
