<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Customer_pu extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_customer_pu');
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

        $data['notif'] = $this->M_notifikasi->pending_notification();
        $data['title'] = "backend/customer_pu/customer_list_pu";
        $data['titleview'] = "Data Customer";
        // $name = $this->db->select('name')
        //     ->from('tbl_data_user')
        //     ->where('id_user', $this->session->userdata('id_user'))
        //     ->get()
        //     ->row('name');
        // $data['approval'] = $this->db->select('COUNT(*) as total_approval')
        //     ->from('tbl_customer')
        //     ->where('app_name', $name)
        //     ->or_where('app2_name', $name)
        //     ->get()
        //     ->row('total_approval');
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
        $list = $this->M_customer_pu->get_datatables();
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
            $action_read = ($read == 'Y') ? '<a href="customer_pu/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>&nbsp;' : '';
            $action_edit = ($edit == 'Y') ? '<a href="customer_pu/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;' : '';
            $action_delete = ($delete == 'Y') ? '<a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;' : '';
            // $action_print = ($print == 'Y') ? '<a class="btn btn-success btn-circle btn-sm" target="_blank" href="customer_pu/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>' : '';

            $action = $action_read . $action_edit . $action_delete;

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $action;
            $row[] = strtoupper($field->group_id);
            // $row[] = strtoupper($field->customer_id);
            // $row[] = $field->title;
            $row[] = $field->nama;
            $row[] = $field->jenis_kelamin;
            $row[] = $field->no_hp;
            // $row[] = $field->status;
            $row[] = $field->asal;
            $row[] = $field->produk;
            // $row[] = 'Rp. ' . number_format($field->harga, 0, ',', '.');
            // $row[] = 'Rp. ' . number_format($field->harga_promo, 0, ',', '.');
            // $row[] = $field->tipe_kamar;
            // $row[] = 'Rp. ' . number_format($field->promo_diskon, 0, ',', '.');
            // $row[] = $field->paspor;
            // $row[] = $field->kartu_kuning;
            // $row[] = $field->ktp;
            // $row[] = $field->kk;
            // $row[] = $field->buku_nikah;
            // $row[] = $field->akta_lahir;
            // $row[] = ($field->pas_foto != '' ? '<img src="' . site_url("assets/backend/document/customer/") . $field->pas_foto  . '" height="40px" width="40px">' : '');
            // $row[] = 'Rp. ' . number_format($field->dp, 0, ',', '.');
            // $row[] = 'Rp. ' . number_format($field->pembayaran_1, 0, ',', '.');
            // $row[] = 'Rp. ' . number_format($field->pembayaran_2, 0, ',', '.');
            // $row[] = 'Rp. ' . number_format($field->pelunasan, 0, ',', '.');
            // $row[] = 'Rp. ' . number_format($field->cashback, 0, ',', '.');
            // $row[] = $field->akun;
            // $row[] = $field->pakaian;
            // $row[] = $field->ukuran;
            // $row[] = $field->kirim_perlengkapan;
            $row[] = $this->tgl_indo(date("Y-m-j", strtotime($field->tgl_berangkat)));
            $row[] = $field->travel;
            // $row[] = $field->tujuan;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_customer_pu->count_all(),
            "recordsFiltered" => $this->M_customer_pu->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    function read_form($id)
    {
        $data['id'] = $id;
        $data['user'] = $this->M_customer_pu->get_by_id($id);
        $data['aksi'] = 'read';
        $data['notif'] = $this->M_notifikasi->pending_notification();
        // $data['app_name'] = $this->db->select('name')
        //     ->from('tbl_data_user')
        //     ->where('id_user', $this->session->userdata('id_user'))
        //     ->get()
        //     ->row('name');
        // $data['app2_name'] = $this->db->select('name')
        //     ->from('tbl_data_user')
        //     ->where('id_user', $this->session->userdata('id_user'))
        //     ->get()  
        //     ->row('name');
        $data['title_view'] = "Data Customer";
        $data['title'] = 'backend/customer_pu/customer_form_pu';
        $data['travel'] = $this->db->get('tbl_travel_pu')->result_array();
        $query = "SELECT DISTINCT group_id FROM tbl_customer_pu ORDER BY group_id DESC";
        $data['group_id'] = $this->db->query($query)->result_array();
        $this->load->view('backend/home', $data);
    }

    function add_form()
    {
        $data['id'] = 0;
        $data['title_view'] = "Customer Form";
        $data['notif'] = $this->M_notifikasi->pending_notification();
        $data['aksi'] = 'update';
        $data['title'] = 'backend/customer_pu/customer_form_pu';
        $data['travel'] = $this->db->get('tbl_travel_pu')->result_array();
        $query = "SELECT DISTINCT group_id FROM tbl_customer_pu ORDER BY group_id DESC";
        $data['group_id'] = $this->db->query($query)->result_array();
        $this->load->view('backend/home', $data);
    }

    function edit_form($id)
    {
        $data['id'] = $id;
        $data['title_view'] = "Edit Data Customer";
        $data['notif'] = $this->M_notifikasi->pending_notification();
        $data['title'] = 'backend/customer_pu/customer_form_pu';
        $data['travel'] = $this->db->get('tbl_travel_pu')->result_array();
        $query = "SELECT DISTINCT group_id FROM tbl_customer_pu ORDER BY group_id DESC";
        $data['group_id'] = $this->db->query($query)->result_array();
        $this->load->view('backend/home', $data);
    }

    function edit_data($id)
    {
        $data['master'] = $this->M_customer_pu->get_by_id($id);
        echo json_encode($data);
    }

    // MEREGENERATE ID Customer
    public function generate_customer_id()
    {
        // Panggil fungsi max_kode untuk mendapatkan customer_id terbaru
        $customer_id = $this->M_customer_pu->max_kode();

        // Kembalikan dalam format JSON
        echo json_encode(array('customer_id' => $customer_id));
    }

    public function add()
    {
        // Panggil fungsi max_kode untuk mendapatkan customer_id terbaru
        $customer_id = $this->M_customer_pu->max_kode();

        // Ambil data group_id dari input
        $group_id_input = $this->input->post('group_id');

        // Jika group_id kosong, ambil group_id terakhir dari database
        if (empty($group_id_input)) {
            $last_group_id = $this->M_customer_pu->get_last_group_id(); // Fungsi untuk mendapatkan group_id terakhir
            $new_group_id = $this->increment_group_id($last_group_id); // Increment group_id
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
                $upload_path = './assets/backend/document/customer/' . $field . '/';
                $config['upload_path'] = $upload_path;
                $config['allowed_types'] = 'jpeg|jpg|png'; // Tipe file yang diizinkan
                $config['max_size'] = 4000; // Maksimal ukuran file 4MB 4000(KB)
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
            'tgl_berangkat' => date('Y-m-d', strtotime($this->input->post('tgl_berangkat'))),
            'title' => $this->input->post('title'),
            'nama' => $this->input->post('nama'),
            'jenis_kelamin' => $this->input->post('jenis_kelamin'),
            'no_hp' => $this->input->post('no_hp'),
            'asal' => $this->input->post('asal'),
            'produk' => $this->input->post('produk'),
            'harga' => preg_replace('/\D/', '', $this->input->post('harga')),
            'harga_promo' => preg_replace('/\D/', '', $this->input->post('harga_promo')),
            'tipe_kamar' => $this->input->post('tipe_kamar'),
            'promo_diskon' => $this->input->post('promo_diskon'),
            'paspor' => $uploaded_files['paspor'],
            'kartu_kuning' => $uploaded_files['kartu_kuning'],
            'ktp' => $uploaded_files['ktp'],
            'kk' => $uploaded_files['kk'],
            'buku_nikah' => $uploaded_files['buku_nikah'],
            'akta_lahir' => $uploaded_files['akta_lahir'],
            'dp' => preg_replace('/\D/', '', $this->input->post('dp')),
            'pembayaran_1' => preg_replace('/\D/', '', $this->input->post('pembayaran_1')),
            'pembayaran_2' => preg_replace('/\D/', '', $this->input->post('pembayaran_2')),
            'pelunasan' => preg_replace('/\D/', '', $this->input->post('pelunasan')),
            'cashback' => preg_replace('/\D/', '', $this->input->post('cashback')),
            'akun' => $this->input->post('akun'),
            'pakaian' => $this->input->post('pakaian'),
            'ukuran' => $this->input->post('ukuran'),
            'kirim_perlengkapan' => $this->input->post('kirim_perlengkapan'),
            'travel' => $this->input->post('travel'),
            'status' => $this->input->post('status'),
            'pas_foto' => $uploaded_files['pas_foto']
        );

        // Simpan data ke database
        $this->M_customer_pu->save($data);

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
        $customer = $this->M_customer_pu->get_by_id($id);

        // Inisialisasi array untuk menyimpan nama file yang diupload
        $uploaded_files = [];

        // Daftar field yang berhubungan dengan file
        $fields = ['pas_foto', 'paspor', 'kartu_kuning', 'ktp', 'kk', 'buku_nikah', 'akta_lahir'];

        // Proses upload file
        foreach ($fields as $field) {
            if (!empty($_FILES[$field]['name'])) {
                // Siapkan konfigurasi upload
                $config['upload_path'] = './assets/backend/document/customer/' . $field . '/';
                $config['allowed_types'] = 'jpeg|jpg|png'; // Jenis file yang diizinkan
                $config['max_size'] = 4000; // Maksimal 4MB
                $config['encrypt_name'] = TRUE; // Enkripsi nama file

                $this->upload->initialize($config);

                // Proses upload file
                if ($this->upload->do_upload($field)) {
                    // Hapus file lama jika ada
                    if (!empty($customer->$field)) {
                        $old_file_path = './assets/backend/document/customer/' . $field . '/' . $customer->$field;
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
            'group_id' => $this->input->post('group_id'),
            'tgl_berangkat' => date('Y-m-d', strtotime($this->input->post('tgl_berangkat'))),
            'title' => $this->input->post('title'),
            'nama' => $this->input->post('nama'),
            'jenis_kelamin' => $this->input->post('jenis_kelamin'),
            'no_hp' => $this->input->post('no_hp'),
            'asal' => $this->input->post('asal'),
            'produk' => $this->input->post('produk'),
            'harga' => preg_replace('/\D/', '', $this->input->post('harga')),
            'harga_promo' => preg_replace('/\D/', '', $this->input->post('harga_promo')),
            'tipe_kamar' => $this->input->post('tipe_kamar'),
            'promo_diskon' => $this->input->post('promo_diskon'),
            'paspor' => $uploaded_files['paspor'],
            'kartu_kuning' => $uploaded_files['kartu_kuning'],
            'ktp' => $uploaded_files['ktp'],
            'kk' => $uploaded_files['kk'],
            'buku_nikah' => $uploaded_files['buku_nikah'],
            'akta_lahir' => $uploaded_files['akta_lahir'],
            'dp' => preg_replace('/\D/', '', $this->input->post('dp')),
            'pembayaran_1' => preg_replace('/\D/', '', $this->input->post('pembayaran_1')),
            'pembayaran_2' => preg_replace('/\D/', '', $this->input->post('pembayaran_2')),
            'pelunasan' => preg_replace('/\D/', '', $this->input->post('pelunasan')),
            'cashback' => preg_replace('/\D/', '', $this->input->post('cashback')),
            'akun' => $this->input->post('akun'),
            'pakaian' => $this->input->post('pakaian'),
            'ukuran' => $this->input->post('ukuran'),
            'kirim_perlengkapan' => $this->input->post('kirim_perlengkapan'),
            'travel' => $this->input->post('travel'),
            'status' => $this->input->post('status'),
            'pas_foto' => $uploaded_files['pas_foto'] // Pas_foto diambil dari array file yang diupload
        );

        // Update data ke database
        $this->db->where('id', $id);
        $this->db->update('tbl_customer_pu', $data);

        echo json_encode(array("status" => TRUE));
    }


    public function delete($id)
    {
        // Dapatkan data customer berdasarkan ID
        $customer = $this->M_customer_pu->get_by_id($id); // Pastikan Anda memiliki fungsi ini untuk mendapatkan data customer berdasarkan ID

        // Daftar field yang berisi nama file gambar
        $fields = ['pas_foto', 'kk', 'ktp', 'buku_nikah', 'paspor', 'akta_lahir', 'kartu_kuning'];

        // Hapus setiap file gambar jika ada
        foreach ($fields as $field) {
            if (!empty($customer->$field)) {
                $file_path = './assets/backend/document/customer/' . $field . '/' . $customer->$field;
                if (file_exists($file_path)) {
                    unlink($file_path); // Hapus file dari direktori
                }
            }
        }

        // Hapus data dari database
        $this->M_customer_pu->delete($id); // Fungsi untuk menghapus data di database

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
        $customer = $this->M_customer_pu->get_by_id($id);

        // Path file yang akan dihapus
        $file_path = './assets/backend/document/customer/' . $field . '/' . $customer->$field;

        // Hapus file jika ada
        if ($customer->$field && file_exists($file_path)) {
            unlink($file_path);
        }

        // Update database, set field menjadi string kosong
        $this->db->where('id', $id);
        $this->db->update('tbl_customer_pu', [$field => '']);

        echo json_encode(['status' => TRUE]);
    }

    public function export_excel()
    {
        // Ambil data dari model
        $customerData = $this->M_customer_pu->get_data_customer();

        // Inisialisasi Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set judul kolom
        $sheet->setCellValue('A1', 'Group ID');
        $sheet->setCellValue('B1', 'Customer ID');
        $sheet->setCellValue('C1', 'Title');
        $sheet->setCellValue('D1', 'Nama');
        $sheet->setCellValue('E1', 'Jenis Kelamin');
        $sheet->setCellValue('F1', 'No Telp');
        $sheet->setCellValue('G1', 'Status');
        $sheet->setCellValue('H1', 'Asal');
        $sheet->setCellValue('I1', 'Produk');
        $sheet->setCellValue('J1', 'Harga');
        $sheet->setCellValue('K1', 'Harga Promo');
        $sheet->setCellValue('L1', 'Tipe Kamar');
        $sheet->setCellValue('M1', 'Promo Diskon');
        $sheet->setCellValue('N1', 'Paspor');
        $sheet->setCellValue('O1', 'Kartu Kuning');
        $sheet->setCellValue('P1', 'KTP');
        $sheet->setCellValue('Q1', 'KK');
        $sheet->setCellValue('R1', 'Buku Nikah');
        $sheet->setCellValue('S1', 'Akta Lahir');
        $sheet->setCellValue('T1', 'Pas Foto');
        $sheet->setCellValue('U1', 'DP');
        $sheet->setCellValue('V1', 'Pembayaran 1');
        $sheet->setCellValue('W1', 'Pembayaran 2');
        $sheet->setCellValue('X1', 'Pelunasan');
        $sheet->setCellValue('Y1', 'Cashback');
        $sheet->setCellValue('Z1', 'Akun');
        $sheet->setCellValue('AA1', 'Pakaian');
        $sheet->setCellValue('AB1', 'Ukuran');
        $sheet->setCellValue('AC1', 'Kirim Perlengkapan');
        $sheet->setCellValue('AD1', 'Tanggal Berangkat');
        $sheet->setCellValue('AE1', 'Travel');

        // Isi data dari database mulai dari baris ke-2
        $row = 2;
        foreach ($customerData as $data) {
            $sheet->setCellValue('A' . $row, $data->group_id);
            $sheet->setCellValue('B' . $row, $data->customer_id);
            $sheet->setCellValue('C' . $row, $data->title);
            $sheet->setCellValue('D' . $row, $data->nama);
            $sheet->setCellValue('E' . $row, $data->jenis_kelamin);
            $sheet->setCellValue('F' . $row, $data->no_hp);
            $sheet->setCellValue('G' . $row, $data->status);
            $sheet->setCellValue('H' . $row, $data->asal);
            $sheet->setCellValue('I' . $row, $data->produk);

            // Kolom yang memerlukan format Rupiah
            $sheet->setCellValue('J' . $row, $data->harga);
            $sheet->setCellValue('K' . $row, $data->harga_promo);
            $sheet->setCellValue('M' . $row, $data->promo_diskon);
            $sheet->setCellValue('U' . $row, $data->dp);
            $sheet->setCellValue('V' . $row, $data->pembayaran_1);
            $sheet->setCellValue('W' . $row, $data->pembayaran_2);
            $sheet->setCellValue('X' . $row, $data->pelunasan);
            $sheet->setCellValue('Y' . $row, $data->cashback);

            // Tetapkan format Rupiah pada kolom yang memerlukan
            $sheet->getStyle('J' . $row)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('K' . $row)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('M' . $row)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('U' . $row)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('V' . $row)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('W' . $row)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('X' . $row)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('Y' . $row)->getNumberFormat()->setFormatCode('#,##0');

            // Kolom lainnya
            $sheet->setCellValue('L' . $row, $data->tipe_kamar);
            $sheet->setCellValue('N' . $row, $data->paspor);
            $sheet->setCellValue('O' . $row, $data->kartu_kuning);
            $sheet->setCellValue('P' . $row, $data->ktp);
            $sheet->setCellValue('Q' . $row, $data->kk);
            $sheet->setCellValue('R' . $row, $data->buku_nikah);
            $sheet->setCellValue('S' . $row, $data->akta_lahir);
            $sheet->setCellValue('T' . $row, $data->pas_foto);
            $sheet->setCellValue('Z' . $row, $data->akun);
            $sheet->setCellValue('AA' . $row, $data->pakaian);
            $sheet->setCellValue('AB' . $row, $data->ukuran);
            $sheet->setCellValue('AC' . $row, $data->kirim_perlengkapan);
            $sheet->setCellValue('AD' . $row, $data->tgl_berangkat);
            $sheet->setCellValue('AE' . $row, $data->travel);
            $row++;
        }

        // Autosize untuk setiap kolom dari A hingga AE setelah data diisi
        foreach (range('A', 'AE') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
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
