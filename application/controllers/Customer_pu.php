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
            $row[] = strtoupper($field->customer_id);
            $row[] = $field->title;
            $row[] = $field->nama;
            $row[] = $field->jenis_kelamin;
            $row[] = $field->no_hp;
            $row[] = $field->status;
            $row[] = $field->asal;
            $row[] = $field->produk;
            $row[] = 'Rp. ' . number_format($field->harga, 0, ',', '.');
            $row[] = 'Rp. ' . number_format($field->harga_promo, 0, ',', '.');
            $row[] = $field->tipe_kamar;
            $row[] = 'Rp. ' . number_format($field->promo_diskon, 0, ',', '.');
            $row[] = $field->paspor;
            $row[] = $field->kartu_kuning;
            $row[] = $field->ktp;
            $row[] = $field->kk;
            $row[] = $field->buku_nikah;
            $row[] = $field->akta_lahir;
            $row[] = ($field->pas_foto != '' ? '<img src="' . site_url("assets/backend/document/customer/") . $field->pas_foto  . '" height="40px" width="40px">' : '');
            $row[] = 'Rp. ' . number_format($field->dp, 0, ',', '.');
            $row[] = 'Rp. ' . number_format($field->pembayaran_1, 0, ',', '.');
            $row[] = 'Rp. ' . number_format($field->pembayaran_2, 0, ',', '.');
            $row[] = 'Rp. ' . number_format($field->pelunasan, 0, ',', '.');
            $row[] = 'Rp. ' . number_format($field->cashback, 0, ',', '.');
            $row[] = $field->akun;
            $row[] = $field->pakaian;
            $row[] = $field->ukuran;
            $row[] = $field->kirim;
            $row[] = $field->perlengkapan;
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

        // Fungsi untuk upload pas_foto
        $upload = $this->do_upload('pas_foto');

        // Jika gagal upload, kembalikan error
        if (!$upload['status']) {
            echo json_encode(array("status" => FALSE, "error" => $upload['error']));
            return;
        }

        // Data yang akan disimpan ke dalam database
        $data = array(
            'customer_id' => $customer_id, // Gunakan kode yang baru di-generate dari max_kode()
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
            'promo_diskon' => preg_replace('/\D/', '', $this->input->post('promo_diskon')),
            'paspor' => $this->input->post('paspor'),
            'kartu_kuning' => $this->input->post('kartu_kuning'),
            'ktp' => $this->input->post('ktp'),
            'kk' => $this->input->post('kk'),
            'buku_nikah' => $this->input->post('buku_nikah'),
            'akta_lahir' => $this->input->post('akta_lahir'),
            'dp' => preg_replace('/\D/', '', $this->input->post('dp')),
            'pembayaran_1' => preg_replace('/\D/', '', $this->input->post('pembayaran_1')),
            'pembayaran_2' => preg_replace('/\D/', '', $this->input->post('pembayaran_2')),
            'pelunasan' => preg_replace('/\D/', '', $this->input->post('pelunasan')),
            'cashback' => preg_replace('/\D/', '', $this->input->post('cashback')),
            'akun' => $this->input->post('akun'),
            'pakaian' => $this->input->post('pakaian'),
            'ukuran' => $this->input->post('ukuran'),
            'kirim' => $this->input->post('kirim'),
            'perlengkapan' => $this->input->post('perlengkapan'),
            'travel' => $this->input->post('travel'),
            'status' => $this->input->post('status'),
            'pas_foto' => $upload['file_name'] // Simpan nama file yang diupload
        );

        // Simpan data ke database
        $this->M_customer_pu->save($data);

        // Kembalikan status JSON sebagai respons
        echo json_encode(array("status" => TRUE));
    }

    // Fungsi untuk upload foto
    private function do_upload($field_name)
    {
        // Konfigurasi upload
        $config['upload_path'] = './assets/backend/document/customer'; // Tentukan folder penyimpanan
        $config['allowed_types'] = 'jpg|jpeg|png'; // Tipe file yang diizinkan
        $config['max_size'] = 3072; // Maksimal ukuran file 3MB (3072KB)
        $config['file_name'] = uniqid(); // Beri nama unik pada file yang diupload

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload($field_name)) {
            // Jika gagal upload
            return array('status' => FALSE, 'error' => $this->upload->display_errors());
        } else {
            // Jika sukses upload
            return array('status' => TRUE, 'file_name' => $this->upload->data('file_name'));
        }
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
        $customer = $this->M_customer_pu->get_by_id($id); // Pastikan Anda sudah punya fungsi ini untuk mendapatkan data customer

        // Fungsi untuk upload pas_foto
        $upload = $this->do_upload('pas_foto');

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
            'promo_diskon' => preg_replace('/\D/', '', $this->input->post('promo_diskon')),
            'paspor' => $this->input->post('paspor'),
            'kartu_kuning' => $this->input->post('kartu_kuning'),
            'ktp' => $this->input->post('ktp'),
            'kk' => $this->input->post('kk'),
            'buku_nikah' => $this->input->post('buku_nikah'),
            'akta_lahir' => $this->input->post('akta_lahir'),
            'dp' => preg_replace('/\D/', '', $this->input->post('dp')),
            'pembayaran_1' => preg_replace('/\D/', '', $this->input->post('pembayaran_1')),
            'pembayaran_2' => preg_replace('/\D/', '', $this->input->post('pembayaran_2')),
            'pelunasan' => preg_replace('/\D/', '', $this->input->post('pelunasan')),
            'cashback' => preg_replace('/\D/', '', $this->input->post('cashback')),
            'akun' => $this->input->post('akun'),
            'pakaian' => $this->input->post('pakaian'),
            'ukuran' => $this->input->post('ukuran'),
            'kirim' => $this->input->post('kirim'),
            'perlengkapan' => $this->input->post('perlengkapan'),
            'travel' => $this->input->post('travel'),
            'status' => $this->input->post('status')
        );

        // Jika file baru diupload
        if ($upload['status']) {
            // Hapus file gambar lama jika ada
            if ($customer->pas_foto) {
                $old_file_path = './assets/backend/document/customer/' . $customer->pas_foto;
                if (file_exists($old_file_path)) {
                    unlink($old_file_path); // Menghapus file lama
                }
            }
            // Simpan nama file yang baru diupload
            $data['pas_foto'] = $upload['file_name'];
        }

        // Update data ke database
        $this->db->where('id', $id);
        $this->db->update('tbl_customer_pu', $data);

        echo json_encode(array("status" => TRUE));
    }

    function delete($id)
    {
        $this->M_customer_pu->delete($id);

        echo json_encode(array("status" => TRUE));
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
        $sheet->setCellValue('AC1', 'Kirim');
        $sheet->setCellValue('AD1', 'Perlengkapan');
        $sheet->setCellValue('AF1', 'Tanggal Berangkat');
        $sheet->setCellValue('AG1', 'Travel');

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
            $sheet->setCellValue('J' . $row, $data->harga);
            $sheet->setCellValue('K' . $row, $data->harga_promo);
            $sheet->setCellValue('L' . $row, $data->tipe_kamar);
            $sheet->setCellValue('M' . $row, $data->promo_diskon);
            $sheet->setCellValue('N' . $row, $data->paspor);
            $sheet->setCellValue('O' . $row, $data->kartu_kuning);
            $sheet->setCellValue('P' . $row, $data->ktp);
            $sheet->setCellValue('Q' . $row, $data->kk);
            $sheet->setCellValue('R' . $row, $data->buku_nikah);
            $sheet->setCellValue('S' . $row, $data->akta_lahir);
            $sheet->setCellValue('T' . $row, $data->pas_foto);
            $sheet->setCellValue('U' . $row, $data->dp);
            $sheet->setCellValue('V' . $row, $data->pembayaran_1);
            $sheet->setCellValue('W' . $row, $data->pembayaran_2);
            $sheet->setCellValue('X' . $row, $data->pelunasan);
            $sheet->setCellValue('Y' . $row, $data->cashback);
            $sheet->setCellValue('Z' . $row, $data->akun);
            $sheet->setCellValue('AA' . $row, $data->pakaian);
            $sheet->setCellValue('AB' . $row, $data->ukuran);
            $sheet->setCellValue('AC' . $row, $data->kirim);
            $sheet->setCellValue('AD' . $row, $data->perlengkapan);
            $sheet->setCellValue('AE' . $row, $data->tgl_berangkat);
            $sheet->setCellValue('AF' . $row, date('Y-m-d', strtotime($data->tgl_berangkat)));
            $sheet->setCellValue('AG' . $row, $data->travel);
            $row++;
        }

        // Buat writer untuk export ke Excel
        $writer = new Xlsx($spreadsheet);

        // Set header untuk download file Excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Data Customer.xlsx"');
        header('Cache-Control: max-age=0');

        // Simpan file ke output
        $writer->save('php://output');;
        exit;
    }
}
