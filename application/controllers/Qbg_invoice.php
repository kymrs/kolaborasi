<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Memuat file TCPDF dari folder third_party
require_once(APPPATH . 'third_party/TCPDF-main/tcpdf.php');

class T_cpdf2 extends TCPDf
{
    // Page header
    function Header()
    {
        // Warna latar belakang header (RGB)
        // $this->SetFillColor(176, 210, 219); // Warna abu-abu terang
        // $this->Rect(3.5, 0, 142, 4.5, 'F'); // Mengisi kotak di area header
        // $this->SetFillColor(69, 87, 123); // Warna abu-abu terang
        // $this->Rect(3.5, 4.5, 142, 18.5, 'F'); // Mengisi kotak di area header
        // $this->SetFillColor(176, 210, 219); // Warna abu-abu terang
        // $this->Rect(3.5, 23, 142, 4.5, 'F'); // Mengisi kotak di area header

        // Logo
        $this->SetFont('Helvetica', 'B', 12);
        $this->Image('assets/backend/img/qubagift.png', 2, 2, 37, 22);
        $this->SetX(113);
        $this->SetFont('Helvetica', '', 9);
        $this->Cell(40, 22, 'qubagift@gmail.com', 0, 0);
        $this->SetX(121);
        $this->Cell(40, 31, '081290399933', 0, 1);
        $style = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));

        // $this->SetY(18);
        // $this->SetX(34);
        // $this->SetFont('Helvetica', '', 8);
        // $this->Cell(40, 16, 'Jl. Mahoni Raya No 13, Sukmajaya, Depok, Jawa Barat', 0, 0);

        $this->Line(4, 26, 145, 26, $style);
        $this->Ln(5);
    }
}

class Qbg_invoice extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_qbg_invoice');
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

        $data['title'] = "backend/qbg_invoice/qbg_invoice_list";
        $data['titleview'] = "Data Invoice";
        $this->load->view('backend/home', $data);
    }

    public function get_pdf()
    {
        $this->load->view('backend/prepayment_pu/prepayment_pdf');
    }

    function get_list()
    {
        // INISIAI VARIABLE YANG DIBUTUHKAN
        $fullname = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $this->session->userdata('id_user'))
            ->get()
            ->row('name');
        $list = $this->M_qbg_invoice->get_datatables();
        $data = array();
        $no = $_POST['start'];

        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        $read = $akses->view_level;
        $edit = $akses->edit_level;
        $delete = $akses->delete_level;
        $print = $akses->print_level;

        //LOOPING DATATABLES
        foreach ($list as $field) {

            // MENENTUKAN ACTION APA YANG AKAN DITAMPILKAN DI LIST DATA TABLES
            $action_read = ($read == 'Y') ? '<a href="qbg_invoice/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>&nbsp;' : '';
            $action_edit = ($edit == 'Y') ? '<a href="qbg_invoice/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;' : '';
            $action_delete = ($delete == 'Y') ? '<a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;' : '';
            $action_print = ($print == 'Y') ? '<a class="btn btn-success btn-circle btn-sm" target="_blank" href="qbg_invoice/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>' : '';

            $action = $action_read . $action_edit . $action_delete . $action_print;

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $action;

            $row[] = $this->tgl_indo(date("Y-m-j", strtotime($field->tgl_invoice)));
            $kode_invoice = $field->kode_invoice;
            $row[] = strtoupper($kode_invoice);
            $row[] = $field->nama_customer;
            $row[] = $field->alamat_customer;
            $row[] = $this->tgl_indo(date("Y-m-j", strtotime($field->tgl_tempo)));
            $row[] = date("d-m-Y | H:i:s", strtotime($field->created_at));

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_qbg_invoice->count_all(),
            "recordsFiltered" => $this->M_qbg_invoice->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    // get list deklarasi
    function get_list2()
    {
        $list = $this->M_qbg_invoice->get_datatables2();
        $data = array();
        $no = $_POST['start'];

        //LOOPING DATATABLES
        foreach ($list as $field) {

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->kode_produk;
            $row[] = $field->nama_produk . ' ' . $field->berat . ' ' . $field->satuan;
            $row[] = $field->stok_akhir;
            $row[] = 'Rp. ' . number_format($field->harga_qubagift, 0, ',', '.');
            $row[] = 'Rp. ' . number_format($field->harga_reseller, 0, ',', '.');
            $row[] = 'Rp. ' . number_format($field->harga_distributor, 0, ',', '.');
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_qbg_invoice->count_all2(),
            "recordsFiltered" => $this->M_qbg_invoice->count_filtered2(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    // UNTUK MENAMPILKAN FORM READ
    public function read_form($id)
    {
        // $data['notif'] = $this->M_notifikasi->pending_notification();
        $data['id'] = $id;
        $data['invoice'] = $this->M_qbg_invoice->getInvoiceData($id);
        $data['rekening'] = $this->db->get_where('qbg_rek_invoice', ['invoice_id' => $id])->result_array();
        $data['detail'] = $this->db->get_where('qbg_detail_invoice', ['invoice_id' => $id])->result_array();
        $data['title'] = 'backend/qbg_invoice/qbg_invoice_read';
        $data['title_view'] = 'Prepayment';
        $this->load->view('backend/home', $data);
    }

    // UNTUK MENAMPILKAN FORM ADD
    public function add_form()
    {
        $data['id'] = 0;
        $data['title'] = 'backend/qbg_invoice/qbg_invoice_form';
        $data['title_view'] = 'Invoice Form';
        $data['rek_options'] = $this->M_qbg_invoice->options()->result();
        // $data['notif'] = $this->M_notifikasi->pending_notification();
        $this->load->view('backend/home', $data);
    }

    // MEREGENERATE KODE INVOICE
    public function generate_kode()
    {
        $date = $this->input->post('date');

        $kode = $this->M_qbg_invoice->max_kode($date)->row();

        if (empty($kode->kode_invoice)) {
            $no_urut = 1;
        } else {
            $bln = substr($kode->kode_invoice, 3, 2);
            $no_urut = substr($kode->kode_invoice, 7) + 1;
        }
        $urutan = str_pad($no_urut, 3, "0", STR_PAD_LEFT);
        $year = substr($date, 8, 2);
        $month = substr($date, 3, 2);
        $data = 'Q' . $year . $month . $urutan;
        echo json_encode($data);
    }

    // public function getAllProducts()
    // {
    //     $this->load->database();
    //     $query = $this->db->get('qbg_produk');  // Ganti 'produk' dengan nama tabel lo
    //     $result = $query->result_array();
    //     echo json_encode($result);
    // }


    // UNTUK MENAMPILKAN FORM EDIT
    function edit_form($id)
    {
        $data['id'] = $id;
        $data['aksi'] = 'update';
        $data['title_view'] = "Edit Data Invoice";
        $data['title'] = 'backend/qbg_invoice/qbg_invoice_form';
        $data['rek_options'] = $this->M_qbg_invoice->options()->result();
        $this->load->view('backend/home', $data);
    }

    function edit_data($id)
    {
        $data['master'] = $this->M_qbg_invoice->get_by_id($id);
        $data['rek_invoice'] = $this->db->get_where('qbg_rek_invoice', ['invoice_id' => $id])->result_array();

        // Join detail invoice dengan qbg_produk
        $this->db->select('d.*, p.nama_produk, p.berat, p.satuan, p.stok_akhir');
        $this->db->from('qbg_detail_invoice d');
        $this->db->join('qbg_produk p', 'd.kode_produk = p.kode_produk', 'left');
        $this->db->where('d.invoice_id', $id);
        $data['detail_invoice'] = $this->db->get()->result_array();

        echo json_encode($data);
    }


    function read_detail($id)
    {
        $data = $this->M_qbg_invoice->get_by_id_detail($id);
        echo json_encode($data);
    }

    // MENAMBAHKAN DATA
    public function add()
    {
        // INSERT KODE PREPAYMENT SAAT SUBMIT
        $date = $this->input->post('tgl_invoice');

        $kode = $this->M_qbg_invoice->max_kode($date)->row();

        if (empty($kode->kode_invoice)) {
            $no_urut = 1;
        } else {
            $bln = substr($kode->kode_invoice, 3, 2);
            $no_urut = substr($kode->kode_invoice, 7) + 1;
        }
        $urutan = str_pad($no_urut, 3, "0", STR_PAD_LEFT);
        $year = substr($date, 8, 2);
        $month = substr($date, 3, 2);

        $kode_invoice = 'Q' . $year . $month . $urutan;

        $total_akhir = $this->input->post('total_akhir');

        $data = array(
            'tgl_invoice' => date('Y-m-d', strtotime($this->input->post('tgl_invoice'))),
            'kode_invoice' => $kode_invoice,
            'tgl_tempo' => date('Y-m-d', strtotime($this->input->post('tgl_tempo'))),
            'nama_customer' => $this->input->post('nama_customer'),
            'nomor_customer' => $this->input->post('nomor_customer'),
            'email_customer' => $this->input->post('email_customer'),
            'alamat_customer' => $this->input->post('alamat_customer'),
            'jenis_harga' => $this->input->post('jenis_harga'),
            'total' => $total_akhir,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('id_level')
        );

        $ongkir = preg_replace('/\D/', '', $this->input->post('ongkir'));
        if (!empty($ongkir || $ongkir == 0)) {

            $total_akhir_ongkir = $total_akhir +  $ongkir;
            $data['ongkir'] = $ongkir;
        }

        $potongan_harga = preg_replace('/\D/', '', $this->input->post('potongan_harga'));
        if (!empty($potongan_harga || $potongan_harga == 0)) {
            $data['potongan_harga'] = $potongan_harga;

            $data['grand_total'] = $total_akhir_ongkir - $potongan_harga;
        } else {
            $data['grand_total'] = $total_akhir_ongkir;
        }

        if (!empty($_POST['catatan_item'])) {
            $data['keterangan'] = $this->input->post('catatan_item');
        }

        $inserted = $this->M_qbg_invoice->save($data);

        if ($inserted) {
            // save data rekening
            $nama_bank = $this->input->post('nama_bank[]');
            $no_rek = $this->input->post('no_rek[]');
            //PERULANGAN UNTUK INSER QUERY DETAIL PREPAYMENT
            for ($i = 1; $i <= count($nama_bank); $i++) {
                $data2[] = array(
                    'invoice_id' => $inserted,
                    'nama_bank' => $nama_bank[$i],
                    'no_rek' => $no_rek[$i]
                );
            }
            $this->M_qbg_invoice->save_detail($data2);
        }

        if ($inserted) {
            // Ambil data produk dari form
            $kode_produk = $this->input->post('kode_produk[]');
            $jumlah = $this->input->post('jumlah[]');
            $total = preg_replace('/\D/', '', $this->input->post('total[]'));
            $harga = preg_replace('/\D/', '', $this->input->post('harga[]'));

            // Perulangan untuk insert ke tabel stok dan detail prepayment
            if (!empty($kode_produk) && !empty($jumlah) && !empty($total) && !empty($harga)) {
                $data3 = [];

                for ($i = 1; $i <= count($kode_produk); $i++) { // Pakai $i = 0 karena array index mulai dari 0
                    // Simpan detail invoice dulu
                    $data3[] = [
                        'invoice_id'  => $inserted,
                        'kode_produk' => $kode_produk[$i],
                        'jumlah'      => $jumlah[$i],
                        'harga'       => $harga[$i],
                        'total'       => $total[$i]
                    ];
                }

                // Insert detail invoice ke database (batch insert supaya lebih efisien)
                $this->M_qbg_invoice->save_detail2($data3);

                foreach ($kode_produk as $i => $kode) {
                    // Ambil stok terakhir
                    $stok_sebelumnya = $this->db->select('stok_akhir')
                        ->where('kode_produk', $kode)
                        ->order_by('id', 'DESC')
                        ->limit(1)
                        ->get('qbg_produk')
                        ->row();

                    $stok_awal = $stok_sebelumnya ? $stok_sebelumnya->stok_akhir : 0;
                    $stok_akhir = $stok_awal - $jumlah[$i];

                    // Insert transaksi stok baru
                    $this->db->insert('qbg_transaksi', [
                        'kode_produk'       => $kode,
                        'jenis_transaksi'   => 'keluar',
                        'jumlah'            => $jumlah[$i],
                        'keterangan'        => 'penjualan (' . $kode_invoice . ')',
                        'created_by'        => $this->session->userdata('id_user')
                    ]);

                    // Update stok akhir di table produk
                    $this->db->update('qbg_produk', ['stok_akhir' => $stok_akhir], ['kode_produk' => $kode]);
                }
            }
        }
        echo json_encode(array("status" => TRUE));
    }

    // UPDATE DATA
    public function update()
    {
        $total_akhir = $this->input->post('total_akhir');

        $data = array(
            'tgl_invoice' => date('Y-m-d', strtotime($this->input->post('tgl_invoice'))),
            'tgl_tempo' => date('Y-m-d', strtotime($this->input->post('tgl_tempo'))),
            'nama_customer' => $this->input->post('nama_customer'),
            'nomor_customer' => $this->input->post('nomor_customer'),
            'email_customer' => $this->input->post('email_customer'),
            'alamat_customer' => $this->input->post('alamat_customer'),
            'jenis_harga' => $this->input->post('jenis_harga'),
            'total' => $total_akhir,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $this->session->userdata('id_level')
        );

        $ongkir = preg_replace('/\D/', '', $this->input->post('ongkir'));
        if (!empty($ongkir || $ongkir == 0)) {

            $total_akhir_ongkir = $total_akhir +  $ongkir;
            $data['ongkir'] = $ongkir;
        }

        $potongan_harga = preg_replace('/\D/', '', $this->input->post('potongan_harga'));
        if (!empty($potongan_harga || $potongan_harga == 0)) {
            $data['potongan_harga'] = $potongan_harga;

            $data['grand_total'] = $total_akhir_ongkir - $potongan_harga;
        } else {
            $data['grand_total'] = $total_akhir_ongkir;
        }

        if (!empty($_POST['catatan_item'])) {
            $data['keterangan'] = $this->input->post('catatan_item');
        }

        // UPDATE DETAIL PRODUK
        // $invoice_id = $this->input->post('hidden_invoiceId[]');
        $id_detail = $this->input->post('hidden_id[]');
        $kode_produk = $this->input->post('kode_produk[]');
        $jumlah = $this->input->post('jumlah[]');
        $harga = preg_replace('/\D/', '', $this->input->post('harga[]'));
        $total = preg_replace('/\D/', '', $this->input->post('total[]'));

        if ($this->db->update('qbg_invoice', $data, ['id' => $this->input->post('id')])) {

            // UNTUK MENGHAPUS ROW YANG TELAH DIDELETE
            $deletedRows = json_decode($this->input->post('deleted_rows'), true);
            if (!empty($deletedRows)) {
                foreach ($deletedRows as $delRows) {
                    // Ambil kode_produk berdasarkan ID yang dihapus
                    $query = $this->db->select('kode_produk')->where('id', $delRows)->get('qbg_detail_invoice')->row_array();


                    if ($query) { // Cek apakah data ditemukan
                        $kode_produk = $query['kode_produk'];

                        // Hapus data pada detail invoice
                        $this->db->where('id', $delRows);
                        $this->db->delete('qbg_detail_invoice');

                        // // Cari ID terbesar (data terakhir) yang memiliki kode_produk ini
                        // $lastStok = $this->db->select('id, stok_akhir')
                        //     ->where('kode_produk', $kode_produk)
                        //     ->get('qbg_produk')
                        //     ->row_array();

                        // $this->db->where('kode_produk', $kode_produk);
                        // $this->db->set('stok_akhir', $lastStok['stok_akhir']);
                        // $this->db->update('qbg_produk');

                        // if ($lastStok) {
                        //     // Hapus hanya stok dengan ID terbesar
                        //     $this->db->where('id', $lastStok['id']);
                        //     $this->db->delete('qbg_transaksi');
                        // }
                    }
                }
            }

            if (!isset($_POST['kode_produk']) || !is_array($_POST['kode_produk'])) {
                die("Error: Data kode_produk tidak ditemukan atau bukan array.");
            }
            $kode_produk = $_POST['kode_produk']; // Pastikan ini array


            // MELAKUKAN REPLACE DATA LAMA DENGAN YANG BARU
            for ($i = 1; $i <= count($_POST['kode_produk']); $i++) {
                $id_invoice = !empty($id_detail[$i]) ? $id_detail[$i] : NULL;
                $data2[] = array(
                    'id' => $id_invoice,
                    'invoice_id' => $this->input->post('id'),
                    'kode_produk' => $kode_produk[$i] ?? '',
                    'jumlah' => $jumlah[$i] ?? 0,
                    'harga' => $harga[$i] ?? 0,
                    'total' => $total[$i] ?? 0
                );
                $this->db->replace('qbg_detail_invoice', $data2[$i - 1]);
            }

            // UNTUK MENGHAPUS REKENING
            // $deletedRekRows = json_decode($this->input->post('deletedRekRows'), true);
            // if (!empty($deletedRekRows)) {
            //     foreach ($deletedRekRows as $delRekRow) {
            //         // Hapus row dari database berdasarkan ID
            //         $this->db->where('id', $delRekRow);
            //         $this->db->delete('qbg_rek_invoice');
            //     }
            // }

            // // MELAKUKAN REPLACE DATA REKENING
            // $id_rek = $this->input->post('hidden_rekId[]');
            // $nama_bank = $this->input->post('nama_bank[]');
            // $no_rek = $this->input->post('no_rek[]');
            // for ($i = 1; $i <= count($_POST['nama_bank']); $i++) {
            //     // Set id menjadi NULL jika id_rek tidak ada atau kosong
            //     $id_rekening = !empty($id_rek[$i]) ? $id_rek[$i] : NULL;
            //     $data3[] = array(
            //         'id' => $id_rekening,
            //         'invoice_id' => $this->input->post('id'),
            //         'nama_bank' => $nama_bank[$i],
            //         'no_rek' => $no_rek[$i]
            //     );
            //     // Menggunakan db->replace untuk memasukkan atau menggantikan data
            //     $this->db->replace('qbg_rek_invoice', $data3[$i - 1]);
            // }
        }

        echo json_encode(array("status" => TRUE));
    }

    // MENGHAPUS DATA
    function delete($id)
    {
        $this->M_qbg_invoice->delete($id);
        echo json_encode(array("status" => TRUE));
    }

    //APPROVE DATA
    public function approve()
    {
        $data = array(
            'app_keterangan' => $this->input->post('app_keterangan'),
            'app_status' => $this->input->post('app_status'),
            'app_date' => date('Y-m-d H:i:s'),
        );

        // UPDATE STATUS DEKLARASI
        if ($this->input->post('app_status') === 'revised') {
            $data['status'] = 'revised';
        } elseif ($this->input->post('app_status') === 'approved') {
            $data['status'] = 'on-process';
        } elseif ($this->input->post('app_status') === 'rejected') {
            $data['status'] = 'rejected';
        }

        //UPDATE APPROVAL PERTAMA
        $this->db->where('id', $this->input->post('hidden_id'));
        $this->db->update('tbl_prepayment_pu', $data);

        echo json_encode(array("status" => TRUE));
    }

    function approve2()
    {
        $data = array(
            'app2_keterangan' => $this->input->post('app2_keterangan'),
            'app2_status' => $this->input->post('app2_status'),
            'app2_date' => date('Y-m-d H:i:s'),
        );

        // UPDATE STATUS DEKLARASI
        if ($this->input->post('app2_status') === 'revised') {
            $data['status'] = 'revised';
        } elseif ($this->input->post('app2_status') === 'approved') {
            $data['status'] = 'approved';
        } elseif ($this->input->post('app2_status') === 'rejected') {
            $data['status'] = 'rejected';
        }

        // UPDATE APPROVAL 2
        $this->db->where('id', $this->input->post('hidden_id'));
        $this->db->update('tbl_prepayment_pu', $data);

        echo json_encode(array("status" => TRUE));
    }

    // PRINTOUT TCPDF
    public function generate_pdf($id)
    {
        // INISIAI VARIABLE
        $invoice = $this->M_qbg_invoice->get_by_id($id);
        $invoice_details = $this->M_qbg_invoice->get_detail($id);
        $invoice_rek = $this->M_qbg_invoice->get_rek($id);

        // Initialize the TCPDF object
        $t_cpdf2 = new t_cpdf2('P', 'mm', 'A5', true, 'UTF-8', false);

        // Set document properties
        $t_cpdf2->SetCreator(PDF_CREATOR);
        $t_cpdf2->SetAuthor('Author Name');
        $t_cpdf2->SetTitle('Invoice Qubagift PDF');

        $t_cpdf2->SetMargins(15, 28, 15); // Margin kiri, atas (untuk header), kanan
        // $t_cpdf2->SetHeaderMargin(30);    // Jarak antara header dan konten
        $t_cpdf2->SetAutoPageBreak(true, 20); // Penanganan otomatis margin bawah

        // Add a new page
        $t_cpdf2->AddPage();

        $t_cpdf2->SetY(30);

        // Pilih font untuk isi
        $t_cpdf2->SetFont('Helvetica', 'B', 22);

        $t_cpdf2->SetX(58);
        $t_cpdf2->Cell(100, 10, 'INVOICE', 0, 1, 'L');

        $t_cpdf2->SetFont('Helvetica', '', 11);
        $t_cpdf2->SetX(53.5);
        $t_cpdf2->Cell(19, 8, 'No. Invoice : ' . $invoice->kode_invoice, 0, 0, 'L');

        $t_cpdf2->SetY($t_cpdf2->GetY() + 5);
        $t_cpdf2->SetX(4);
        $t_cpdf2->SetFont('Helvetica', '', 10);
        $t_cpdf2->Cell(82, 20, 'Kepada Yth.', 0, 0);
        $t_cpdf2->Cell(36, 20, 'Tanggal Invoice', 0, 0);
        $t_cpdf2->Cell(19, 20, ': ' . date('d/m/Y', strtotime($invoice->tgl_invoice)), 0, 0);
        $t_cpdf2->SetY($t_cpdf2->GetY() + 14);
        $t_cpdf2->SetX(4);
        $t_cpdf2->SetFont('Helvetica', '', 14);
        $t_cpdf2->Cell(82, 7, $invoice->nama_customer, 0, 0);
        $t_cpdf2->SetFont('Helvetica', '', 10);
        $t_cpdf2->Cell(36, 5, 'Tanggal Jatuh Tempo', 0, 0);
        $t_cpdf2->Cell(19, 5, ': ' . date('d/m/Y', strtotime($invoice->tgl_tempo)), 0, 0);

        $t_cpdf2->SetFont('Helvetica', '', 10);
        $t_cpdf2->SetY($t_cpdf2->GetY() + 9);
        $t_cpdf2->SetX(4);
        $t_cpdf2->Cell(0, 0, 'Email', 0, 0);
        $t_cpdf2->SetX(23);
        $t_cpdf2->Cell(0, 0, ': ' . ($invoice->email_customer ?: '-'), 0, 0);

        $t_cpdf2->SetFont('Helvetica', '', 10);
        $t_cpdf2->SetY($t_cpdf2->GetY() + 6);
        $t_cpdf2->SetX(4);
        $t_cpdf2->Cell(75, 0, 'Telepon', 0, 0);
        $t_cpdf2->SetX(23);
        $t_cpdf2->Cell(0, 0, ': ' . $invoice->nomor_customer, 0, 0);

        $t_cpdf2->SetFont('Helvetica', '', 10);
        $t_cpdf2->SetY($t_cpdf2->GetY() + 6);
        $t_cpdf2->SetX(4);
        $t_cpdf2->Cell(0, 0, 'Alamat', 0, 0);
        $t_cpdf2->SetY($t_cpdf2->GetY());
        $t_cpdf2->SetX(23);
        $t_cpdf2->Cell(0, 0, ':', 0, 0);
        $t_cpdf2->SetY($t_cpdf2->GetY());
        $t_cpdf2->SetX(24.5);
        $t_cpdf2->MultiCell(58, 0, $invoice->alamat_customer, 0, 'L');

        $t_cpdf2->SetY($t_cpdf2->GetY() + 4);
        // HEADER DETAIL PEMESANAN
        $t_cpdf2->SetFont('Helvetica', '', 10);
        $t_cpdf2->SetFillColor(0, 190, 99);
        $t_cpdf2->SetTextColor(255, 255, 255);
        $t_cpdf2->SetX(5);
        $t_cpdf2->Cell(140, 10, 'Detail Pemesanan', 0, 1, 'L', true);
        $t_cpdf2->SetTextColor(0, 0, 0);

        $tbl = <<<EOD
        <table border="1" cellpadding="3">
            <thead>
                <tr>
                    <th align="center" width="35%"><b>PRODUK</b></th>
                    <th align="center" width="15%"><b>JUMLAH</b></th>
                    <th align="center" width="25%"><b>HARGA</b></th>
                    <th align="center" width="25%"><b>TOTAL</b></th>
                </tr>
            </thead>
            <tbody>
    EOD;
        foreach ($invoice_details as $detail) {
            $produk = $this->db->select('nama_produk, berat, satuan')->where('kode_produk', $detail->kode_produk)->get('qbg_produk')->row_array();

            $tbl .= '<tr>';
            $tbl .= '<td width="35%">' . $produk['nama_produk'] . ' ' . $produk['berat'] . ' ' . $produk['satuan'] . '</td>';
            $tbl .= '<td width="15%" style="text-align: center">' . $detail->jumlah . '</td>';
            $tbl .= '<td width="25%" style="text-align: center">' . 'Rp. ' . number_format($detail->harga, 0, ',', '.') . '</td>';
            $tbl .= '<td width="25%" style="text-align: center">' . 'Rp. ' . number_format($detail->total, 0, ',', '.') . '</td>';
            $tbl .= '</tr>';
        }
        $tbl .= <<<EOD
    </tbody>
</table>
EOD;

        $t_cpdf2->writeHTMLCell(142, 0, 4, $t_cpdf2->GetY() + 4, $tbl, 0, 1, false, true, 'L', true);

        $table2 = <<<EOD
            <table cellpadding="3">
            <tbody>
            EOD;

        $table2 .= '<tr style="border: none;">';
        $table2 .= '<td colspan="2"></td>';
        $table2 .= '<td width="25%" style="border: 1px solid black; text-align: center">Biaya Pengiriman</td>';
        $table2 .= '<td width="25%" style="border: 1px solid black; text-align: center;"> ' . 'Rp. ' . number_format($invoice->ongkir, 0, ',', '.') . '</td>';
        $table2 .= '</tr>';
        $table2 .= '<tr style="border: none;">';
        $table2 .= '<td colspan="2"></td>';
        $table2 .= '<td width="25%" style="border: 1px solid black; text-align: center">Potongan Harga</td>';
        $table2 .= '<td width="25%" style="border: 1px solid black; text-align: center;"> ' . 'Rp. -' . number_format($invoice->potongan_harga, 0, ',', '.') . '</td>';
        $table2 .= '</tr>';
        $table2 .= '<tr style="border: none;">';
        $table2 .= '<td colspan="2" style="border: none;"></td>';
        $table2 .= '<td width="25%" style="border: 1px solid black; text-align: center;"> <b>Total</b></td>';
        $table2 .= '<td width="25%" style="border: 1px solid black; text-align: center;"> ' . 'Rp. ' . number_format($invoice->total, 0, ',', '.') . '</td>';
        $table2 .= '</tr>';
        $table2 .=  <<<EOD
            <tbody>
            </table>
        EOD;

        $t_cpdf2->writeHTMLCell(142, 0, 4, $t_cpdf2->GetY(), $table2, 0, 1, false, true, 'L', true);

        $t_cpdf2->SetY($t_cpdf2->GetY() + 1);
        $t_cpdf2->SetX(4);
        $t_cpdf2->SetFont('Helvetica', '', 11);
        $t_cpdf2->Cell(14, 9, 'Metode Pembayaran', 0, 1);
        $list = <<<EOD
        <ol>
        EOD;

        foreach ($invoice_rek as $rek) {
            $list .= '<li>Bank : ' . $rek->nama_bank . '<br>No. Rekening : ' . $rek->no_rek . '</li>';
        }
        $list .= <<<EOD
        </ol>
        EOD;
        $t_cpdf2->SetFont('Helvetica', '', 10);
        $y = $t_cpdf2->GetY();
        $x = -4;
        $t_cpdf2->writeHTMLCell(0, 0, $x, $y, $list, 0, 1, false, true, 'L', true);

        $t_cpdf2->SetY($t_cpdf2->GetY() - 1);
        $t_cpdf2->SetX(4);

        $t_cpdf2->SetFont('Helvetica', '', 10);
        $t_cpdf2->Cell(19, 9, 'Atas Nama : PT. Kolaborasi Para Sahabat', 0, 1);

        $t_cpdf2->setY($t_cpdf2->GetY());
        $t_cpdf2->SetX(4);

        $t_cpdf2->SetFont('Helvetica', '', 10);
        $t_cpdf2->Cell(19, 9, 'Catatan :', 0, 1);
        if ($invoice->keterangan != '') {
            $catatan = $invoice->keterangan;
        } else {
            $catatan = '-';
        }

        $t_cpdf2->writeHTMLCell(0, 0, 4, $t_cpdf2->GetY(), $catatan, 0, 1, false, true, 'L', true);

        $t_cpdf2->setY($t_cpdf2->GetY() + 3);
        $t_cpdf2->SetX(106);
        $t_cpdf2->SetFont('Helvetica', '', 10);
        $t_cpdf2->Cell(22.5, 10, 'Terima Kasih, ', 0, 0);
        $t_cpdf2->SetFont('Helvetica', '', 10);
        $t_cpdf2->Cell(19, 10, 'Qubagift', 0, 1);

        // Output PDF (tampilkan di browser)
        $t_cpdf2->Output('Invoice Qubagift - ' . $invoice->kode_invoice . '.pdf', 'I'); // 'I' untuk menampilkan di browser
    }

    function payment()
    {
        $this->db->where('id', $this->input->post('id'));
        $this->db->update('tbl_prepayment_pu', ['payment_status' => $this->input->post('payment_status')]);

        echo json_encode(array("status" => TRUE));
    }
}
