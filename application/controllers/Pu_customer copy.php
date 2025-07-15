<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class pu_customer extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_pu_customer');
        $this->load->model('backend/M_notifikasi');
        $this->load->library('upload');
        $this->M_login->getsecurity();
        date_default_timezone_set('Asia/Jakarta');
    }

    function tgl_indo($tanggal)
    {
        $bulan = array(
            1 =>   'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );
        $pecahkan = explode('-', $tanggal);

        // variabel pecahkan 0 = tanggal
        // variabel pecahkan 1 = bulan
        // variabel pecahkan 2 = tahun

        return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
    }

    public function index()
    {
        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        ($akses->view_level == 'N' ? redirect('auth') : '');
        $data['add'] = $akses->add_level;


        $data['title'] = "backend/pu_customer/pu_tr_customer_list";
        $data['titleview'] = "Data Transaksi Customer";
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
        $list = $this->M_pu_customer->get_datatables();
        $data = array();
        $no = $_POST['start'];

        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        $read = $akses->view_level;
        $edit = $akses->edit_level;
        $delete = $akses->delete_level;
        // $print = $akses->print_level;

        //LOOPING DATATABLES
        foreach ($list as $field) {

            // MENENTUKAN ACTION APA YANG AKAN DITAMPILKAN DI LIST DATA TABLES
            $action_read = ($read == 'Y') ? '<a href="read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>&nbsp;' : '';
            $action_edit = ($edit == 'Y') ? '<a href="edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;' : '';
            $action_delete = ($delete == 'Y') ? '<a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;' : '';

            $action = $action_read . $action_edit . $action_delete;

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $action;
            $row[] = strtoupper($field->customer_id);
            $row[] = strtoupper($field->group_id);
            $row[] = $field->title . '. ' . $field->nama;
            $row[] = $field->jenis_kelamin;
            $row[] = $field->no_hp;
            $row[] = $field->asal;
            $row[] = date("d-m-Y | H:i:s", strtotime($field->created_at));

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_pu_customer->count_all(),
            "recordsFiltered" => $this->M_pu_customer->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    function get_list2()
    {
        // INISIAI VARIABLE YANG DIBUTUHKAN
        $fullname = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $this->session->userdata('id_user'))
            ->get()
            ->row('name');
        $list = $this->M_pu_customer->get_datatables2();
        $data = array();
        $no = $_POST['start'];

        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        $read = $akses->view_level;
        $edit = $akses->edit_level;
        $delete = $akses->delete_level;
        // $print = $akses->print_level;

        //LOOPING DATATABLES
        foreach ($list as $field) {

            // MENENTUKAN ACTION APA YANG AKAN DITAMPILKAN DI LIST DATA TABLES
            $action_read = ($read == 'Y') ? '<a href="pu_customer/read_form_transaksi/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>&nbsp;' : '';
            $action_edit = ($edit == 'Y') ? '<a href="pu_customer/edit_form_transaksi/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;' : '';

            $action = $action_read . $action_edit;

            if (time() < strtotime($field->tgl_berangkat)) {
                $action_delete = ($delete == 'Y') ? '<a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;' : '';
                $action .= $action_delete;
            }

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $action;
            $row[] = strtoupper($field->customer_id);
            $row[] = strtoupper($field->group_id);
            $row[] = $field->title . '. ' . $field->nama;
            $row[] = $field->jenis_kelamin;
            $row[] = $field->no_hp;
            $row[] = $this->tgl_indo($field->tgl_berangkat);
            $row[] = $field->produk;
            $row[] = $field->travel;
            $row[] = date("d-m-Y | H:i:s", strtotime($field->created_at));

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_pu_customer->count_all2(),
            "recordsFiltered" => $this->M_pu_customer->count_filtered2(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    public function customer()
    {
        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        ($akses->view_level == 'N' ? redirect('auth') : '');
        $data['add'] = $akses->add_level;

        $data['title'] = "backend/pu_customer/pu_customer_list";
        $data['titleview'] = "Data Customer";
        $this->load->view('backend/home', $data);
    }

    function read_form($id)
    {
        $data['id'] = $id;
        $data['user'] = $this->M_pu_customer->get_by_id($id);
        $data['aksi'] = 'read';

        $data['title_view'] = "Data Customer";
        $data['title'] = 'backend/pu_customer/pu_customer_form';
        $data['travel'] = $this->db->get('pu_travel')->result_array();
        $query = "SELECT DISTINCT group_id FROM pu_customer ORDER BY group_id DESC";
        $data['group_id'] = $this->db->query($query)->result_array();
        $this->load->view('backend/home', $data);
    }

    function read_form_transaksi($id)
    {
        $data['id'] = $id;
        $data['user'] = $this->M_pu_customer->get_by_id($id);
        $data['aksi'] = 'read';

        $data['title_view'] = "Data Transaksi Customer";
        $data['title'] = 'backend/pu_customer/pu_tr_customer_form';
        $query = "SELECT DISTINCT group_id FROM pu_customer ORDER BY group_id DESC";
        $data['customer'] = $this->db->select('customer_id, group_id, title, nama')->get('pu_customer')->result_array();
        $data['group_id'] = $this->db->query($query)->result_array();
        $data['travel'] = $this->db->get('pu_travel')->result_array();
        $this->load->view('backend/home', $data);
    }

    function add_form()
    {
        $data['id'] = 0;
        $data['title_view'] = "Customer Form";

        $data['aksi'] = 'update';
        $data['title'] = 'backend/pu_customer/pu_customer_form';
        $data['travel'] = $this->db->get('pu_travel')->result_array();
        $query = "SELECT DISTINCT group_id FROM pu_customer WHERE group_id <> '-' ORDER BY group_id DESC";
        $data['group_id'] = $this->db->query($query)->result_array();
        $this->load->view('backend/home', $data);
    }

    function add_form_transaksi()
    {
        $data['id'] = 0;
        $data['title_view'] = "Transaksi Customer Form";

        $data['aksi'] = 'update';
        $data['title'] = 'backend/pu_customer/pu_tr_customer_form';
        $data['travel'] = $this->db->get('pu_travel')->result_array();
        $data['customer'] = $this->db->select('customer_id, group_id, title, nama')->get('pu_customer')->result_array();
        $this->load->view('backend/home', $data);
    }

    function edit_form($id)
    {
        $data['id'] = $id;
        $data['title_view'] = "Edit Data Customer";

        $data['title'] = 'backend/pu_customer/pu_customer_form';
        $data['travel'] = $this->db->get('pu_travel')->result_array();
        $query = "SELECT DISTINCT group_id FROM pu_customer WHERE group_id <> '-' ORDER BY group_id DESC";
        $data['customer'] = $this->db->select('customer_id, group_id, title, nama')->get('pu_customer')->result_array();
        $data['group_id'] = $this->db->query($query)->result_array();
        $this->load->view('backend/home', $data);
    }

    function edit_form_transaksi($id)
    {
        $data['id'] = $id;
        $data['title_view'] = "Edit Data Transaksi Customer";

        $data['title'] = 'backend/pu_customer/pu_tr_customer_form';
        $data['travel'] = $this->db->get('pu_travel')->result_array();
        $query = "SELECT DISTINCT group_id FROM pu_customer ORDER BY group_id DESC";
        $data['group_id'] = $this->db->query($query)->result_array();
        $data['customer'] = $this->db->select('customer_id, group_id, title, nama')->get('pu_customer')->result_array();
        $this->load->view('backend/home', $data);
    }

    function edit_data($id)
    {
        $data['master'] = $this->M_pu_customer->get_by_id($id);
        echo json_encode($data);
    }

    function edit_data_transaksi($id)
    {
        $data['master'] = $this->M_pu_customer->get_by_id2($id);
        echo json_encode($data);
    }

    // MEREGENERATE ID Customer
    public function generate_customer_id()
    {
        // Panggil fungsi max_kode untuk mendapatkan customer_id terbaru
        $customer_id = $this->M_pu_customer->max_kode();

        // Kembalikan dalam format JSON
        echo json_encode(array('customer_id' => $customer_id));
    }

    public function add()
    {
        // Panggil fungsi max_kode untuk mendapatkan customer_id terbaru
        $customer_id = $this->M_pu_customer->max_kode();
        $group_id = $this->M_pu_customer->max_kode_group();

        // Ambil data group_id dari input
        $group_id_input = $this->input->post('group_id');

        // Jika group_id kosong, ambil group_id terakhir dari database
        if ($group_id_input == '-') {
            $new_group_id = '-'; // Increment group_id
        } else if ($group_id_input == 'group') {
            $new_group_id = $group_id;
        } else {
            $new_group_id = $group_id_input; // Gunakan input dari user
        }

        // Inisialisasi array untuk menyimpan nama file
        $uploaded_files = [];

        // Upload untuk masing-masing file
        $fields = ['pas_foto', 'paspor', 'kartu_kuning', 'ktp', 'kk', 'buku_nikah', 'akta_lahir'];
        foreach ($fields as $field) {
            if (!empty($_FILES[$field]['name'])) {
                // Siapkan file untuk diupload
                $_FILES['file']['name'] = $_FILES[$field]['name'];
                $_FILES['file']['type'] = $_FILES[$field]['type'];
                $_FILES['file']['tmp_name'] = $_FILES[$field]['tmp_name'];
                $_FILES['file']['error'] = $_FILES[$field]['error'];
                $_FILES['file']['size'] = $_FILES[$field]['size'];

                // Tentukan folder upload sesuai dengan field
                $upload_path = './assets/backend/document/pu_customer/' . $field;
                $config['upload_path'] = $upload_path;
                $config['allowed_types'] = 'jpeg|jpg|png'; // Tipe file yang diizinkan
                $config['max_size'] = 3000; // Maksimal ukuran file 3MB 3000(KB)
                $config['encrypt_name'] = TRUE; // Enkripsi nama file untuk menghindari konflik

                $this->upload->initialize($config);

                // Proses upload
                if ($this->upload->do_upload('file')) {
                    $uploaded_files[$field] = $this->upload->data('file_name'); // Simpan nama file yang diupload
                } else {
                    echo json_encode(array("status" => FALSE, "error" => $this->upload->display_errors()));
                    return;
                }
            } else {
                $uploaded_files[$field] = ''; // Jika tidak ada file, set ke string kosong
            }
        }

        // Data yang akan disimpan ke dalam database
        $data = array(
            'customer_id' => $customer_id,
            'group_id' => $new_group_id,
            'title' => $this->input->post('title'),
            'nama' => $this->input->post('nama'),
            'jenis_kelamin' => $this->input->post('jenis_kelamin'),
            'no_hp' => $this->input->post('no_hp'),
            'asal' => $this->input->post('asal'),
            'akun' => $this->input->post('akun'),
            'paspor' => $uploaded_files['paspor'],
            'kartu_kuning' => $uploaded_files['kartu_kuning'],
            'ktp' => $uploaded_files['ktp'],
            'kk' => $uploaded_files['kk'],
            'buku_nikah' => $uploaded_files['buku_nikah'],
            'akta_lahir' => $uploaded_files['akta_lahir'],
            'pas_foto' => $uploaded_files['pas_foto'],
            'created_at' => date('Y-m-d H:i:s')
        );

        // Simpan data ke database
        $this->M_pu_customer->save($data);

        // Kembalikan status JSON sebagai respons
        echo json_encode(array("status" => TRUE));
    }

    public function add_transaksi()
    {

        // Data yang akan disimpan ke dalam database
        $data = array(
            'customer_id' => $this->input->post('customer_id'),
            'tgl_berangkat' => date('Y-m-d', strtotime($this->input->post('tgl_berangkat'))),
            'travel' => $this->input->post('travel'),
            'produk' => $this->input->post('produk'),
            'tipe_kamar' => $this->input->post('tipe_kamar'),
            'pakaian' => $this->input->post('pakaian'),
            'ukuran' => $this->input->post('ukuran'),
            'kirim_perlengkapan' => $this->input->post('kirim_perlengkapan'),
            'status' => $this->input->post('status'),
            'harga' => preg_replace('/\D/', '', $this->input->post('harga')),
            'harga_promo' => preg_replace('/\D/', '', $this->input->post('harga_promo')),
            'promo_diskon' => $this->input->post('promo_diskon'),
            'dp' => preg_replace('/\D/', '', $this->input->post('dp')),
            'pembayaran_1' => preg_replace('/\D/', '', $this->input->post('pembayaran_1')),
            'pembayaran_2' => preg_replace('/\D/', '', $this->input->post('pembayaran_2')),
            'pelunasan' => preg_replace('/\D/', '', $this->input->post('pelunasan')),
            'cashback' => preg_replace('/\D/', '', $this->input->post('cashback')),
            'created_at' => date('Y-m-d H:i:s')
        );

        // Simpan data ke database
        $this->M_pu_customer->save2($data);

        // Kembalikan status JSON sebagai respons
        echo json_encode(array("status" => TRUE));
    }


    // Fungsi untuk increment group_id
    public function increment_group_id($last_group_id)
    {
        if ($last_group_id) {
            // Ambil nomor urut dari group_id (misal: G002 -> 002)
            $last_number = (int)substr($last_group_id, 1); // Ambil bagian angka
            $new_number = $last_number + 1; // Tambahkan 1 ke angka terakhir
            return 'G' . sprintf("%03d", $new_number); // Kembalikan dalam format GXXX
        }

        // Jika tidak ada group_id sebelumnya, mulai dari G001
        return 'G001';
    }

    public function update()
    {
        // Ambil data customer berdasarkan ID
        $id = $this->input->post('id');
        $customer = $this->M_pu_customer->get_by_id($id);

        // Inisialisasi array untuk menyimpan nama file yang diupload
        $uploaded_files = [];

        // Daftar field yang berhubungan dengan file
        $fields = ['pas_foto', 'paspor', 'kartu_kuning', 'ktp', 'kk', 'buku_nikah', 'akta_lahir'];

        // Proses upload file
        foreach ($fields as $field) {
            if (!empty($_FILES[$field]['name'])) {
                // Siapkan konfigurasi upload
                $config['upload_path'] = './assets/backend/document/pu_customer/' . $field . '/';
                $config['allowed_types'] = 'jpeg|jpg|png'; // Jenis file yang diizinkan
                $config['max_size'] = 3000; // Maksimal 3MB
                $config['encrypt_name'] = TRUE; // Enkripsi nama file

                $this->upload->initialize($config);

                // Proses upload file
                if ($this->upload->do_upload($field)) {
                    // Hapus file lama jika ada
                    if (!empty($customer->$field)) {
                        $old_file_path = './assets/backend/document/pu_customer/' . $field . '/' . $customer->$field;
                        if (file_exists($old_file_path)) {
                            unlink($old_file_path); // Hapus file lama
                        }
                    }
                    // Simpan nama file baru
                    $uploaded_files[$field] = $this->upload->data('file_name');
                } else {
                    echo json_encode(array("status" => FALSE, "error" => $this->upload->display_errors()));
                    return;
                }
            } else {
                // Jika tidak ada file yang diupload, gunakan file lama
                $uploaded_files[$field] = $customer->$field;
            }
        }

        // Data yang akan disimpan ke dalam database
        $data = array(
            'group_id' => $this->input->post('group_id', TRUE) ?: '-',
            'title' => $this->input->post('title'),
            'nama' => $this->input->post('nama'),
            'jenis_kelamin' => $this->input->post('jenis_kelamin'),
            'no_hp' => $this->input->post('no_hp'),
            'asal' => $this->input->post('asal'),
            'akun' => $this->input->post('akun'),
            'paspor' => $uploaded_files['paspor'],
            'kartu_kuning' => $uploaded_files['kartu_kuning'],
            'ktp' => $uploaded_files['ktp'],
            'kk' => $uploaded_files['kk'],
            'buku_nikah' => $uploaded_files['buku_nikah'],
            'akta_lahir' => $uploaded_files['akta_lahir'],
            'pas_foto' => $uploaded_files['pas_foto'],
        );

        // Update data ke database
        $this->db->where('id', $id);
        $this->db->update('pu_customer', $data);

        echo json_encode(array("status" => TRUE));
    }

    public function update_transaksi()
    {
        // Ambil data customer berdasarkan ID
        $id = $this->input->post('id');
        $customer = $this->M_pu_customer->get_by_id($id);

        // Data yang akan disimpan ke dalam database
        $data = array(
            'customer_id' => $this->input->post('customer_id'),
            'tgl_berangkat' => date('Y-m-d', strtotime($this->input->post('tgl_berangkat'))),
            'travel' => $this->input->post('travel'),
            'produk' => $this->input->post('produk'),
            'tipe_kamar' => $this->input->post('tipe_kamar'),
            'pakaian' => $this->input->post('pakaian'),
            'ukuran' => $this->input->post('ukuran'),
            'kirim_perlengkapan' => $this->input->post('kirim_perlengkapan'),
            'status' => $this->input->post('status'),
            'harga' => preg_replace('/\D/', '', $this->input->post('harga')),
            'harga_promo' => preg_replace('/\D/', '', $this->input->post('harga_promo')),
            'promo_diskon' => $this->input->post('promo_diskon'),
            'dp' => preg_replace('/\D/', '', $this->input->post('dp')),
            'pembayaran_1' => preg_replace('/\D/', '', $this->input->post('pembayaran_1')),
            'pembayaran_2' => preg_replace('/\D/', '', $this->input->post('pembayaran_2')),
            'pelunasan' => preg_replace('/\D/', '', $this->input->post('pelunasan')),
            'cashback' => preg_replace('/\D/', '', $this->input->post('cashback'))
        );

        // Update data ke database
        $this->db->where('id', $id);
        $this->db->update('pu_tr_customer', $data);

        echo json_encode(array("status" => TRUE));
    }

    public function delete($id)
    {
        // Dapatkan data customer berdasarkan ID
        $customer = $this->M_pu_customer->get_by_id($id); // Pastikan Anda memiliki fungsi ini untuk mendapatkan data customer berdasarkan ID

        // Daftar field yang berisi nama file gambar
        $fields = ['pas_foto', 'kk', 'ktp', 'buku_nikah', 'paspor', 'akta_lahir', 'kartu_kuning'];

        // Hapus setiap file gambar jika ada
        foreach ($fields as $field) {
            if (!empty($customer->$field)) {
                $file_path = './assets/backend/document/pu_customer/' . $field . '/' . $customer->$field;
                if (file_exists($file_path)) {
                    unlink($file_path); // Hapus file dari direktori
                }
            }
        }

        // Hapus data dari database
        $this->M_pu_customer->delete($id); // Fungsi untuk menghapus data di database

        // Kirim respons sukses
        echo json_encode(array("status" => TRUE));
    }

    public function delete2($id)
    {
        // Hapus data dari database
        $this->M_pu_customer->delete2($id); // Fungsi untuk menghapus data di database

        // Kirim respons sukses
        echo json_encode(array("status" => TRUE));
    }


    public function delete_file()
    {
        // Dapatkan ID customer dan nama field dari request
        $id = $this->input->post('id');
        $field = $this->input->post('field');

        // Validasi field yang diperbolehkan
        $allowed_fields = ['pas_foto', 'ktp', 'kk', 'buku_nikah', 'paspor', 'akta_lahir', 'kartu_kuning'];
        if (!in_array($field, $allowed_fields)) {
            echo json_encode(['status' => FALSE, 'error' => 'Invalid field']);
            return;
        }

        // Ambil data customer berdasarkan ID
        $customer = $this->M_pu_customer->get_by_id($id);

        // Path file yang akan dihapus
        $file_path = './assets/backend/document/pu_customer/' . $field . '/' . $customer->$field;

        // Hapus file jika ada
        if ($customer->$field && file_exists($file_path)) {
            unlink($file_path);
        }

        // Update database, set field menjadi string kosong
        $this->db->where('id', $id);
        $this->db->update('pu_customer', [$field => '']);

        echo json_encode(['status' => TRUE]);
    }

    public function export_excel()
    {
        // Ambil data dari model
        $customerDataTransaksi = $this->M_pu_customer->get_data_transaksi();

        // Inisialisasi Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set judul kolom
        $sheet->setCellValue('A1', 'Customer ID');
        $sheet->setCellValue('B1', 'Group ID');
        $sheet->setCellValue('C1', 'Tanggal Berangkat');
        $sheet->setCellValue('D1', 'Travel');
        $sheet->setCellValue('E1', 'Produk');
        $sheet->setCellValue('F1', 'Tipe Kamar');
        $sheet->setCellValue('G1', 'Pakaian');
        $sheet->setCellValue('H1', 'Ukuran');
        $sheet->setCellValue('I1', 'Kirim Perlengkapan');
        $sheet->setCellValue('J1', 'Status');
        $sheet->setCellValue('K1', 'Harga');
        $sheet->setCellValue('L1', 'Harga Promo');
        $sheet->setCellValue('M1', 'DP');
        $sheet->setCellValue('N1', 'Pembayaran 1');
        $sheet->setCellValue('O1', 'Pembayaran 2');
        $sheet->setCellValue('P1', 'Pelunasan');
        $sheet->setCellValue('Q1', 'Cashback');


        // Isi data dari database mulai dari baris ke-2
        $row = 2;
        foreach ($customerDataTransaksi as $data) {
            $sheet->setCellValue('A' . $row, $data->customer_id);
            $sheet->setCellValue('B' . $row, $data->group_id);
            $sheet->setCellValue('C' . $row, date('d/m/Y', strtotime($data->tgl_berangkat)));
            $sheet->setCellValue('D' . $row, $data->travel);
            $sheet->setCellValue('E' . $row, $data->produk);
            $sheet->setCellValue('F' . $row, $data->tipe_kamar);
            $sheet->setCellValue('G' . $row, $data->pakaian);
            $sheet->setCellValue('H' . $row, $data->ukuran);
            $sheet->setCellValue('I' . $row, $data->kirim_perlengkapan);
            $sheet->setCellValue('J' . $row, $data->status);
            // Kolom yang memerlukan format Rupiah
            $sheet->setCellValue('K' . $row, $data->harga);
            $sheet->setCellValue('L' . $row, $data->harga_promo);
            $sheet->setCellValue('M' . $row, $data->dp);
            $sheet->setCellValue('N' . $row, $data->pembayaran_1);
            $sheet->setCellValue('O' . $row, $data->pembayaran_2);
            $sheet->setCellValue('P' . $row, $data->pelunasan);
            $sheet->setCellValue('Q' . $row, $data->cashback);

            // Tetapkan format Rupiah pada kolom yang memerlukan
            $sheet->getStyle('K' . $row)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('L' . $row)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('M' . $row)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('N' . $row)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('O' . $row)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('P' . $row)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('Q' . $row)->getNumberFormat()->setFormatCode('#,##0');
            $row++;
        }

        // Gunakan metode calculateColumnWidths untuk otomatis mengatur lebar kolom
        foreach ($sheet->getColumnIterator() as $column) {
            $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
        }

        // Buat writer untuk export ke Excel
        $writer = new Xlsx($spreadsheet);

        // Set header untuk download file Excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Data Transaksi Customer.xlsx"');
        header('Cache-Control: max-age=0');

        // Simpan file ke output
        $writer->save('php://output');
        exit;
    }

    public function export_excel_customer()
    {
        // Ambil data dari model
        $customerData = $this->M_pu_customer->get_data_customer();

        // Inisialisasi Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set judul kolom
        $sheet->setCellValue('A1', 'Customer ID');
        $sheet->setCellValue('B1', 'Group ID');
        $sheet->setCellValue('C1', 'Title');
        $sheet->setCellValue('D1', 'Nama');
        $sheet->setCellValue('E1', 'Jenis Kelamin');
        $sheet->setCellValue('F1', 'No Telp');
        $sheet->setCellValue('G1', 'Asal');
        $sheet->setCellValue('H1', 'Akun');
        $sheet->setCellValue('I1', 'Paspor');
        $sheet->setCellValue('J1', 'Kartu Kuning');
        $sheet->setCellValue('K1', 'KTP');
        $sheet->setCellValue('L1', 'KK');
        $sheet->setCellValue('M1', 'Buku Nikah');
        $sheet->setCellValue('N1', 'Akta Lahir');
        $sheet->setCellValue('O1', 'Pas Foto');

        // Isi data dari database mulai dari baris ke-2
        $row = 2;
        foreach ($customerData as $data) {
            $sheet->setCellValue('A' . $row, $data->customer_id);
            $sheet->setCellValue('B' . $row, $data->group_id);
            $sheet->setCellValue('C' . $row, $data->title);
            $sheet->setCellValue('D' . $row, $data->nama);
            $sheet->setCellValue('E' . $row, $data->jenis_kelamin);
            $sheet->setCellValue('F' . $row, $data->no_hp);
            $sheet->setCellValue('G' . $row, $data->asal);
            $sheet->setCellValue('H' . $row, $data->akun);

            $sheet->setCellValue('I' . $row, $data->paspor ? 'Ada' : 'Tidak ada');
            $sheet->setCellValue('J' . $row, $data->kartu_kuning ? 'Ada' : 'Tidak ada');
            $sheet->setCellValue('K' . $row, $data->ktp ? 'Ada' : 'Tidak ada');
            $sheet->setCellValue('L' . $row, $data->kk ? 'Ada' : 'Tidak ada');
            $sheet->setCellValue('M' . $row, $data->buku_nikah ? 'Ada' : 'Tidak ada');
            $sheet->setCellValue('N' . $row, $data->akta_lahir ? 'Ada' : 'Tidak ada');
            $sheet->setCellValue('O' . $row, $data->pas_foto ? 'Ada' : 'Tidak ada');


            $row++;
        }

        // Gunakan metode calculateColumnWidths untuk otomatis mengatur lebar kolom
        foreach ($sheet->getColumnIterator() as $column) {
            $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
        }

        // Buat writer untuk export ke Excel
        $writer = new Xlsx($spreadsheet);

        // Set header untuk download file Excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Data Customer.xlsx"');
        header('Cache-Control: max-age=0');

        // Simpan file ke output
        $writer->save('php://output');
        exit;
    }
}
