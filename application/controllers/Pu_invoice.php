<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Memuat file TCPDF dari folder third_party
require_once(APPPATH . 'third_party/TCPDF-main/tcpdf.php');

class T_cpdf2 extends TCPDf
{
    // Page header
    function Header()
    {
        // Logo
        $this->SetFont('helvetica', 'B', 12);
        $this->Image(base_url('assets/backend/img/Header.png'), 49, 5, 160, 30);
        $this->Ln(5);
    }

    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        // $this->SetY(-15);
        // helvetica italic 8
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        // $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
        $this->Image(base_url('assets/backend/img/Footer.png'), 0, 280, 210, 5);
    }
}

class Pu_invoice extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_pu_invoice');
        // $this->load->model('backend/M_notifikasi');
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

        // $data['notif'] = $this->M_notifikasi->pending_notification();

        $data['title'] = "backend/pu_invoice/pu_invoice_list";
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
        $list = $this->M_pu_invoice->get_datatables();
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
            $action_read = ($read == 'Y') ? '<a href="pu_invoice/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>&nbsp;' : '';
            $action_edit = ($edit == 'Y') ? '<a href="pu_invoice/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;' : '';
            $action_delete = ($delete == 'Y') ? '<a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;' : '';
            $action_print = ($print == 'Y') ? '<a class="btn btn-success btn-circle btn-sm" target="_blank" href="pu_invoice/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>&nbsp;' : '';
            $action_payment = '<button type="button" class="btn btn-primary btn-circle btn-sm" onclick="showPaymentModal(' . $field->id . ')"><i class="fas fa-money-bill-wave"></i></button>';

            if ($field->is_active == 0) {
                $action = $action_read . $action_print . $action_payment;
            } else {
                $action = $action_read . $action_edit . $action_delete . $action_print . $action_payment;
            }

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $action;

            $kode_invoice = substr($field->kode_invoice, 0, 5) . substr($field->kode_invoice, 7, 6);
            $row[] = strtoupper($kode_invoice);
            $row[] = $field->ctc_nama;
            $row[] = $field->status_pembayaran;
            $row[] = $this->tgl_indo(date("Y-m-j", strtotime($field->tgl_invoice)));
            $row[] = $this->tgl_indo(date("Y-m-j", strtotime($field->tgl_tempo)));

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_pu_invoice->count_all(),
            "recordsFiltered" => $this->M_pu_invoice->count_filtered(),
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
        $data['invoice'] = $this->M_pu_invoice->getInvoiceData($id);
        $data['rekening'] = $this->db->get_where('pu_rek_invoice', ['invoice_id' => $id])->result_array();
        $data['detail'] = $this->db->get_where('pu_detail_invoice', ['invoice_id' => $id])->result_array();
        $data['title'] = 'backend/pu_invoice/pu_invoice_read';
        $data['title_view'] = 'Prepayment';
        $this->load->view('backend/home', $data);
    }

    // UNTUK MENAMPILKAN FORM ADD
    public function add_form()
    {
        $data['id'] = 0;
        $data['title'] = 'backend/pu_invoice/pu_invoice_form';
        $data['title_view'] = 'Invoice Form';
        $data['rek_options'] = $this->M_pu_invoice->options()->result();
        // $data['notif'] = $this->M_notifikasi->pending_notification();
        $this->load->view('backend/home', $data);
    }

    // MEREGENERATE KODE INVOICE
    public function generate_kode()
    {
        $date = $this->input->post('date');

        $kode = $this->M_pu_invoice->max_kode($date)->row();

        if (empty($kode->kode_invoice)) {
            $no_urut = 1;
        } else {
            $bln = substr($kode->kode_invoice, 5, 2);
            $no_urut = substr($kode->kode_invoice, 10) + 1;
        }
        $urutan = str_pad($no_urut, 4, "0", STR_PAD_LEFT);
        $year = substr($date, 8, 2);
        $month = substr($date, 3, 2);
        $data = 'INVPU' . $year . $month . $urutan;
        echo json_encode($data);
    }

    // UNTUK MENAMPILKAN FORM EDIT
    function edit_form($id)
    {
        // $data['notif'] = $this->M_notifikasi->pending_notification();
        $data['id'] = $id;
        $data['aksi'] = 'update';
        $data['title_view'] = "Edit Data Invoice";
        $data['title'] = 'backend/pu_invoice/pu_invoice_form';
        $data['rek_options'] = $this->M_pu_invoice->options()->result();
        $this->load->view('backend/home', $data);
    }

    function edit_data($id)
    {
        $data['master'] = $this->M_pu_invoice->get_by_id($id);
        $data['rek_invoice'] = $this->db->get_where('pu_rek_invoice', ['invoice_id' => $id])->result_array();
        $data['detail_invoice'] = $this->db->get_where('pu_detail_invoice', ['invoice_id' => $id])->result_array();
        echo json_encode($data);
    }

    function read_detail($id)
    {
        $data = $this->M_pu_invoice->get_by_id_detail($id);
        echo json_encode($data);
    }

    // MENAMBAHKAN DATA
    public function add()
    {
        // INSERT KODE PREPAYMENT SAAT SUBMIT
        $date = $this->input->post('tgl_invoice');

        $kode = $this->M_pu_invoice->max_kode($date)->row();

        if (empty($kode->kode_invoice)) {
            $no_urut = 1;
        } else {
            $bln = substr($kode->kode_invoice, 5, 2);
            $no_urut = substr($kode->kode_invoice, 10) + 1;
        }
        $urutan = str_pad($no_urut, 4, "0", STR_PAD_LEFT);
        $year = substr($date, 8, 2);
        $month = substr($date, 3, 2);

        $kode_invoice = 'INVPU' . $year . $month . $urutan;

        $data = array(
            'tgl_invoice' => date('Y-m-d', strtotime($this->input->post('tgl_invoice'))),
            'kode_invoice' => $kode_invoice,
            'tgl_tempo' => date('Y-m-d', strtotime($this->input->post('tgl_tempo'))),
            'ctc_nama' => $this->input->post('ctc_nama'),
            'ctc_alamat' => $this->input->post('ctc_alamat'),
            'detail_pesanan' => $this->input->post('detail_pesanan'),
            'created_at' => date('Y-m-d H:i:s')
        );

        // INISIAI VARIABEL UNTUK PAYMENT
        $tanggal_pembayaran = $this->input->post('tanggal_pembayaran');
        $nominal_dibayar = $this->input->post('nominal_dibayar');
        $status_pembayaran = $this->input->post('status_pembayaran');

        if ($tanggal_pembayaran && $nominal_dibayar && $status_pembayaran) {
            $data['tanggal_pembayaran'] = $tanggal_pembayaran;
            $data['nominal_dibayar'] = $nominal_dibayar;
            $data['status_pembayaran'] =  $status_pembayaran;
        }

        if (!empty($this->input->post('jamaah_item'))) {
            $data['jamaah'] = $this->input->post('jamaah_item');
        } else {
            echo json_encode(array("status" => FALSE, "message" => "Jamaah tidak boleh kosong"));
        }

        if (!empty($_POST['catatan_item'])) {
            $data['keterangan'] = $this->input->post('catatan_item');
        }
        if (!empty($_POST['diskon'])) {
            $data['diskon'] = $this->input->post('diskon');
        }

        // INISIASI VARIABEL UNTUK MENGHITUNG TOTAL TAGIHAN
        $grand_total = 0;
        $deskripsi = $this->input->post('deskripsi[]');
        $jumlah = preg_replace('/\D/', '', $this->input->post('jumlah[]'));
        $harga = preg_replace('/\D/', '', $this->input->post('harga[]'));
        $satuan = $this->input->post('satuan[]');
        $total = preg_replace('/\D/', '', $this->input->post('total[]'));

        if (!empty($deskripsi) && !empty($jumlah) && !empty($satuan) && !empty($harga) && !empty($total)) {
            for ($i = 1; $i <= count($deskripsi); $i++) {
                $grand_total += (int)$total[$i]; // Menjumlahkan total
            }
            $data['total_tagihan'] = $grand_total;
        } else {
            echo json_encode(array("status" => FALSE, "message" => "Data detail invoice tidak boleh kosong"));
            exit();
        }

        $inserted = $this->M_pu_invoice->save($data);

        if ($inserted) {
            // INISIASI VARIABEL INPUT DETAIL PREPAYMENT
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
            $this->M_pu_invoice->save_detail($data2);
        }

        if ($inserted) {
            //PERULANGAN UNTUK INSER QUERY DETAIL PREPAYMENT
            if (!empty($deskripsi) && !empty($jumlah) && !empty($satuan) && !empty($harga) && !empty($total)) {
                for ($i = 1; $i <= count($deskripsi); $i++) {
                    $data3[] = array(
                        'invoice_id' => $inserted,
                        'deskripsi' => $deskripsi[$i],
                        'jumlah' => $jumlah[$i],
                        'satuan' => $satuan[$i],
                        'harga' => $harga[$i],
                        'total' => $total[$i]
                    );
                }
                $this->M_pu_invoice->save_detail2($data3);
            }
        }

        echo json_encode(array("status" => TRUE));
    }

    // UPDATE DATA
    public function update()
    {
        // INSERT KODE PREPAYMENT SAAT SUBMIT
        $date = $this->input->post('tgl_invoice');

        $kode = $this->M_pu_invoice->max_kode($date)->row();

        if (empty($kode->kode_invoice)) {
            $no_urut = 1;
        } else {
            $bln = substr($kode->kode_invoice, 5, 2);
            $no_urut = substr($kode->kode_invoice, 10) + 1;
        }
        $urutan = str_pad($no_urut, 4, "0", STR_PAD_LEFT);
        $year = substr($date, 8, 2);
        $month = substr($date, 3, 2);

        $kode_invoice = 'INVPU' . $year . $month . $urutan;

        $data = array(
            'tgl_invoice' => date('Y-m-d', strtotime($this->input->post('tgl_invoice'))),
            'kode_invoice' => $kode_invoice,
            'tgl_tempo' => date('Y-m-d', strtotime($this->input->post('tgl_tempo'))),
            'ctc_nama' => $this->input->post('ctc_nama'),
            'ctc_alamat' => $this->input->post('ctc_alamat'),
            'created_at' => date('Y-m-d H:i:s')
        );

        // INISIAI VARIABEL UNTUK PAYMENT
        $tanggal_pembayaran = $this->input->post('tanggal_pembayaran');
        $nominal_dibayar = $this->input->post('nominal_dibayar');
        $status_pembayaran = $this->input->post('status_pembayaran');

        if ($tanggal_pembayaran && $nominal_dibayar && $status_pembayaran) {
            $data['tanggal_pembayaran'] = $tanggal_pembayaran;
            $data['nominal_dibayar'] = $nominal_dibayar;
            $data['status_pembayaran'] =  $status_pembayaran;
        }

        if (!empty($this->input->post('jamaah_item'))) {
            $data['jamaah'] = $this->input->post('jamaah_item');
        } else {
            echo json_encode(array("status" => FALSE, "message" => "Jamaah tidak boleh kosong"));
        }

        if (!empty($_POST['catatan_item'])) {
            $data['keterangan'] = $this->input->post('catatan_item');
        }

        if (!empty($_POST['diskon'])) {
            $data['diskon'] = $this->input->post('diskon');
        }

        // INISIASI VARIABEL UNTUK MENGHITUNG TOTAL TAGIHAN
        $grand_total = 0;
        $deskripsi = $this->input->post('deskripsi[]');
        $jumlah = preg_replace('/\D/', '', $this->input->post('jumlah[]'));
        $harga = preg_replace('/\D/', '', $this->input->post('harga[]'));
        $satuan = $this->input->post('satuan[]');
        $total = preg_replace('/\D/', '', $this->input->post('total[]'));

        if (!empty($deskripsi) && !empty($jumlah) && !empty($satuan) && !empty($harga) && !empty($total)) {
            for ($i = 1; $i <= count($deskripsi); $i++) {
                $grand_total += (int)$total[$i]; // Menjumlahkan total
            }
            $data['total_tagihan'] = $grand_total;
        } else {
            echo json_encode(array("status" => FALSE, "message" => "Data detail invoice tidak boleh kosong"));
            exit();
        }

        //UPDATE DETAIL PREPAYMENT
        $id_detail = $this->input->post('hidden_id[]');
        // $invoice_id = $this->input->post('hidden_invoiceId[]');
        if ($this->db->update('pu_invoice', $data, ['id' => $this->input->post('id')])) {
            // UNTUK MENGHAPUS ROW YANG TELAH DIDELETE
            $deletedRows = json_decode($this->input->post('deleted_rows'), true);
            if (!empty($deletedRows)) {
                foreach ($deletedRows as $delRows) {
                    // Hapus row dari database berdasarkan ID
                    $this->db->where('id', $delRows);
                    $this->db->delete('pu_detail_invoice');
                }
            }

            //MELAKUKAN REPLACE DATA LAMA DENGAN YANG BARU
            for ($i = 1; $i <= count($_POST['deskripsi']); $i++) {
                // Set id menjadi NULL jika id_detail tidak ada atau kosong
                $id_invoice = !empty($id_detail[$i]) ? $id_detail[$i] : NULL;
                $data2[] = array(
                    'id' => $id_invoice,
                    'invoice_id' => $this->input->post('id'),
                    'deskripsi' => $deskripsi[$i],
                    'jumlah' => $jumlah[$i],
                    'satuan' => $satuan[$i],
                    'harga' => $harga[$i],
                    'total' => $total[$i]
                );
                // Menggunakan db->replace untuk memasukkan atau menggantikan data
                $this->db->replace('pu_detail_invoice', $data2[$i - 1]);
            }

            // UNTUK MENGHAPUS REKENING
            $deletedRekRows = json_decode($this->input->post('deletedRekRows'), true);
            if (!empty($deletedRekRows)) {
                foreach ($deletedRekRows as $delRekRow) {
                    // Hapus row dari database berdasarkan ID
                    $this->db->where('id', $delRekRow);
                    $this->db->delete('pu_rek_invoice');
                }
            }

            // MELAKUKAN REPLACE DATA REKENING
            $id_rek = $this->input->post('hidden_rekId[]');
            $nama_bank = $this->input->post('nama_bank[]');
            $no_rek = $this->input->post('no_rek[]');
            for ($i = 1; $i <= count($_POST['nama_bank']); $i++) {
                // Set id menjadi NULL jika id_rek tidak ada atau kosong
                $id_rekening = !empty($id_rek[$i]) ? $id_rek[$i] : NULL;
                $data3[] = array(
                    'id' => $id_rekening,
                    'invoice_id' => $this->input->post('id'),
                    'nama_bank' => $nama_bank[$i],
                    'no_rek' => $no_rek[$i]
                );
                // Menggunakan db->replace untuk memasukkan atau menggantikan data
                $this->db->replace('pu_rek_invoice', $data3[$i - 1]);
            }
        }
        echo json_encode(array("status" => TRUE));
    }

    // MENGHAPUS DATA
    function delete($id)
    {
        $this->M_pu_invoice->delete($id);
        echo json_encode(array("status" => TRUE));
    }

    // PAYMENT
    function payment($id)
    {
        $this->M_pu_invoice->payment($id);
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
        $invoice = $this->M_pu_invoice->get_by_id($id);
        $invoice_details = $this->M_pu_invoice->get_detail($id);
        $invoice_rek = $this->M_pu_invoice->get_rek($id);

        // Initialize the TCPDF object
        $t_cpdf2 = new t_cpdf2('P', 'mm', 'A4', true, 'UTF-8', false);

        // Set document properties
        $t_cpdf2->SetCreator(PDF_CREATOR);
        $t_cpdf2->SetAuthor('Author Name');
        $t_cpdf2->SetTitle('Invoice Pengenumroh PDF');

        $t_cpdf2->SetMargins(15, 40, 15); // Margin kiri, atas (untuk header), kanan
        $t_cpdf2->SetAutoPageBreak(true, 15); // Penanganan otomatis margin bawah

        // Add a new page
        $t_cpdf2->AddPage();

        $t_cpdf2->SetY($t_cpdf2->getMargins()['top']);

        // Pilih font untuk isi
        $t_cpdf2->SetFont('Poppins-Bold', '', 15);
        $t_cpdf2->Cell(100, 10, 'PT. KOLABORASI PARA SAHABAT', 0, 0, 'L');

        $t_cpdf2->SetFont('Poppins-Bold', '', 22);
        $t_cpdf2->SetX(164);
        $t_cpdf2->Cell(100, 10, 'INVOICE', 0, 1, 'L');

        $t_cpdf2->SetFont('Poppins-Regular', '', 11);
        $t_cpdf2->SetX(114);
        $t_cpdf2->Cell(45, 9, 'No. Invoice', 0, 0, 'R');
        $t_cpdf2->Cell(20, 9, ':', 0, 0);
        $t_cpdf2->Cell(19, 9, $invoice->kode_invoice, 0, 0, 'R');

        $t_cpdf2->SetY($t_cpdf2->GetY());
        $t_cpdf2->SetX(114);
        $t_cpdf2->SetFont('Poppins-Regular', '', 10);
        $t_cpdf2->Cell(45, 20, 'Tanggal Invoice', 0, 0, 'R');
        $t_cpdf2->Cell(20, 20, ':', 0, 0);
        $t_cpdf2->Cell(19, 20, date('d/m/Y', strtotime($invoice->tgl_invoice)), 0, 0, 'R');
        $t_cpdf2->SetY($t_cpdf2->GetY() + 13);
        $t_cpdf2->SetX(114);
        $t_cpdf2->SetFont('Poppins-Bold', '', 14);
        $t_cpdf2->SetFont('Poppins-Regular', '', 10);
        $t_cpdf2->Cell(45, 5, 'Tanggal Jatuh Tempo', 0, 0, 'R');
        $t_cpdf2->Cell(20, 5, ':', 0, 0);
        $t_cpdf2->Cell(19, 5, date('d/m/Y', strtotime($invoice->tgl_tempo)), 0, 1, 'R');

        $t_cpdf2->SetY($t_cpdf2->GetY() + 2);
        // HEADER DATA PEMESAN
        $t_cpdf2->SetFont('Poppins-Regular', '', 11);
        $t_cpdf2->SetFillColor(252, 118, 19);
        $t_cpdf2->SetTextColor(255, 255, 255);
        $t_cpdf2->SetX(15);
        $t_cpdf2->Cell(182, 10, 'Data Pemesan', 0, 0, 'L', true);
        $t_cpdf2->Cell(0, 10, 'Jumlah Yang Harus Dibayar', 0, 1, 'R', true);
        $t_cpdf2->SetTextColor(0, 0, 0);

        // CONTENT DDATA PEMSAN
        $t_cpdf2->SetFont('Poppins-Regular', '', 10);
        $t_cpdf2->SetY($t_cpdf2->GetY() + 2);
        $t_cpdf2->SetX(15);
        $t_cpdf2->Cell(0, 0, 'Kpd Yth.', 0, 1);

        $t_cpdf2->SetFont('Poppins-Regular', 'B', 15);
        $t_cpdf2->SetX(15);
        $t_cpdf2->Cell(0, 0, $invoice->ctc_nama, 0, 0);
        $t_cpdf2->Cell(0, 0, '30.000.000', 0, 1, 'R');

        $t_cpdf2->SetFont('Poppins-Regular', '', 10);
        $t_cpdf2->SetY($t_cpdf2->GetY() + 1);
        $t_cpdf2->SetX(15);
        $t_cpdf2->Cell(0, 0, 'Alamat', 0, 0);
        $t_cpdf2->SetY($t_cpdf2->GetY());
        $t_cpdf2->SetX(29);
        $t_cpdf2->Cell(0, 0, ':', 0, 0);
        $t_cpdf2->SetY($t_cpdf2->GetY());
        $t_cpdf2->SetX(31);
        $t_cpdf2->MultiCell(58, 0, $invoice->ctc_alamat, 0, 'L');

        $t_cpdf2->SetY($t_cpdf2->GetY() + 2);
        // HEADER DETAIL JAMAAH
        $t_cpdf2->SetFont('Poppins-Regular', '', 11);
        $t_cpdf2->SetFillColor(252, 118, 19);
        $t_cpdf2->SetTextColor(255, 255, 255);
        $t_cpdf2->SetX(15);
        $t_cpdf2->Cell(182, 10, 'Data Jamaah', 0, 0, 'L', true);
        $t_cpdf2->Cell(0, 10, 'Detail Pesanan', 0, 1, 'R', true);
        $t_cpdf2->SetTextColor(0, 0, 0);

        // CONTENT DATA JAMAAH
        $t_cpdf2->SetFont('Poppins-Regular', '', 10);
        $t_cpdf2->SetY($t_cpdf2->GetY() + 2);
        $t_cpdf2->SetX(15);
        $t_cpdf2->Cell(0, 0, '1. Bapak Nana Suryana', 0, 1);
        $t_cpdf2->Cell(0, 0, '2. Bapak Kurniawan Rubarsah', 0, 1);
        $t_cpdf2->Cell(0, 0, '3. Bapak Agus Taryana', 0, 0);

        $t_cpdf2->SetFont('Poppins-Regular', '', 10);
        $t_cpdf2->SetY($t_cpdf2->GetY() - 9);
        $t_cpdf2->SetX(100);
        $t_cpdf2->Cell(0, 0, 'Umroh 4 September 2025', 0, 1, 'R');
        $t_cpdf2->SetX(100);
        $t_cpdf2->Cell(0, 0, 'Plus City Tour Thaif - 9 Hari', 0, 1, 'R');
        $t_cpdf2->SetX(100);
        $t_cpdf2->Cell(0, 0, 'Kamar : Triple', 0, 1, 'R');

        $t_cpdf2->SetY($t_cpdf2->GetY() + 2);
        // HEADER DETAIL PEMESANAN
        $t_cpdf2->SetFont('Poppins-Regular', '', 11);
        $t_cpdf2->SetFillColor(252, 118, 19);
        $t_cpdf2->SetTextColor(255, 255, 255);
        $t_cpdf2->SetX(15);
        $t_cpdf2->Cell(182, 10, 'Detail Pemesanan', 0, 1, 'L', true);
        $t_cpdf2->SetTextColor(0, 0, 0);

        $tbl = <<<EOD
        <table border="1" cellpadding="3">
            <thead>
                <tr>
                    <th align="center" width="30%"><b>DESKRIPSI</b></th>
                    <th align="center" width="20%"><b>JUMLAH</b></th>
                    <th align="center" width="25%"><b>HARGA</b></th>
                    <th align="center" width="25%"><b>TOTAL</b></th>
                </tr>
            </thead>
            <tbody>
    EOD;
        foreach ($invoice_details as $detail) {
            $tbl .= '<tr>';
            $tbl .= '<td width="30%">' . $detail->deskripsi . '</td>';
            $tbl .= '<td width="20%" style="text-align: center">' . $detail->jumlah . '</td>';
            $tbl .= '<td width="25%" style="text-align: center">' . 'Rp. ' . number_format($detail->harga, 0, ',', '.') . '</td>';
            $tbl .= '<td width="25%" style="text-align: center">' . 'Rp. ' . number_format($detail->total, 0, ',', '.') . '</td>';
            $tbl .= '</tr>';
        }
        $tbl .= <<<EOD
    </tbody>
</table>
EOD;

        $t_cpdf2->writeHTMLCell(184, 0, 14, $t_cpdf2->GetY() + 4, $tbl, 0, 1, false, true, 'L', true);

        $table2 = <<<EOD
            <table>
            <tbody>
            EOD;

        $total = 0;
        $diskon = $invoice->diskon;
        foreach ($invoice_details as $detail) {
            if ($diskon != 0) {
                $total += $detail->total - ($detail->total * $diskon / 100);
            } else {
                $total += $detail->total;
            }
        }

        $table2 .= '<tr style="border: none;">';
        $table2 .= '<td colspan="2"></td>';
        $table2 .= '<td width="25%" style="border: 1px solid black; text-align: center"> <b>Diskon</b></td>';
        $table2 .= '<td width="25%" style="border: 1px solid black; text-align: center"> ' . $invoice->diskon .  '%</td>';
        $table2 .= '</tr>';
        $table2 .= '<tr style="border: none;">';
        $table2 .= '<td colspan="2" style="border: none;"></td>';
        $table2 .= '<td width="25%" style="border: 1px solid black; text-align: center"> <b>Total</b></td>';
        $table2 .= '<td width="25%" style="border: 1px solid black; text-align: center"> ' . 'Rp. ' . number_format($total, 0, ',', '.') . '</td>';
        $table2 .= '</tr>';
        $table2 .=  <<<EOD
            <tbody>
            </table>
        EOD;

        $t_cpdf2->writeHTMLCell(184, 0, 14, $t_cpdf2->GetY(), $table2, 0, 1, false, true, 'L', true);

        $t_cpdf2->SetY($t_cpdf2->GetY() + 1);
        $t_cpdf2->SetX(13);
        $t_cpdf2->SetFont('Poppins-Bold', '', 11);
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
        $t_cpdf2->SetFont('Poppins-Regular', '', 10);
        $y = $t_cpdf2->GetY();
        $x = 3;
        $t_cpdf2->writeHTMLCell(0, 0, $x, $y, $list, 0, 1, false, true, 'L', true);

        $t_cpdf2->SetY($t_cpdf2->GetY() - 1);
        $t_cpdf2->SetX(13);

        $t_cpdf2->SetFont('Poppins-Bold', '', 11);
        $t_cpdf2->Cell(19, 9, 'a/n : PT. Kolaborasi Para Sahabat', 0, 1);

        $t_cpdf2->setY($t_cpdf2->GetY());
        $t_cpdf2->SetX(13);

        $t_cpdf2->SetFont('Poppins-Regular', '', 10);
        $t_cpdf2->Cell(19, 9, 'Catatan :', 0, 1);
        if ($invoice->keterangan != '') {
            $catatan = $invoice->keterangan;
        } else {
            $catatan = '';
        }

        $t_cpdf2->writeHTMLCell(0, 0, 13, $t_cpdf2->GetY(), $catatan, 0, 1, false, true, 'L', true);

        // Output PDF (tampilkan di browser)
        $t_cpdf2->Output('Invoice Pengenumroh.pdf', 'I'); // 'I' untuk menampilkan di browser
    }
}
