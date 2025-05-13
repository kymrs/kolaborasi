<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Memuat file TCPDF dari folder third_party
require_once(APPPATH . 'third_party/TCPDF-main/tcpdf.php');

class T_cpdf2 extends TCPDF
{
    public function __construct($orientation = 'P', $unit = 'mm', $format = 'A4', $unicode = true, $encoding = 'UTF-8', $diskcache = false)
    {
        parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache);
    }

    // Page header
    function Header()
    {
        // Logo
        $this->Image('assets/backend/img/sml.png', 15, 8, 60, 20); // Menyesuaikan posisi logo
        $this->SetY(10); // Menyesuaikan posisi Y setelah logo

        // Font Header
        $this->SetFont('helvetica', 'B', 12);

        // Geser ke kanan agar lebih rapi
        $this->SetX(173);
        $this->Cell(0, 16, 'INVOICE', 0, 0, 'L');

        // Garis bawah
        $this->SetLineWidth(0.5);
        $this->Line(10, 30, 200, 30); // Diperpanjang agar sejajar dengan margin halaman A4
        $this->Ln(8); // Beri jarak ke konten utama
    }
}


class Sml_invoice extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_sml_invoice');
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

        return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
    }

    public function index()
    {
        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        ($akses->view_level == 'N' ? redirect('auth') : '');
        $data['add'] = $akses->add_level;

        $data['title'] = "backend/sml_invoice/sml_invoice_list";
        $data['titleview'] = "Data Invoice";
        $this->load->view('backend/home', $data);
    }

    public function get_pdf()
    {
        $this->load->view('backend/prepayment_pu/prepayment_pdf');
    }

    function get_list()
    {
        $id_user = $this->session->userdata('id_user');

        // INISIAI VARIABLE YANG DIBUTUHKAN
        $name = $this->db->select('username')
            ->from('tbl_user')
            ->where('id_user', $id_user)
            ->get()
            ->row('username');
        $list = $this->M_sml_invoice->get_datatables();
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
            $action_read = ($read == 'Y') ? '<a href="sml_invoice/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>&nbsp;' : '';
            $action_edit = ($edit == 'Y') ? '<a href="sml_invoice/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;' : '';
            $action_delete = ($delete == 'Y') ? '<a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;' : '';
            $action_print = ($print == 'Y') ? '<a class="btn btn-success btn-circle btn-sm" target="_blank" href="sml_invoice/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>' : '';

            if ($name == 'eko') {
                $action = $action_read . $action_edit . $action_delete . $action_print;
            } elseif ($field->id_user == $id_user) {
                $action = $action_read . $action_edit . $action_delete . $action_print;
            } else {
                $action = $action_read . $action_print;
            }

            $status = $field->payment_status == 1 ? 'Lunas' : 'Belum Lunas';

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $action;

            $row[] = $this->tgl_indo(date("Y-m-j", strtotime($field->tgl_invoice)));
            $kode_invoice = substr($field->kode_invoice, 0, 5) . substr($field->kode_invoice, 7, 6);
            $row[] = strtoupper($kode_invoice);
            $row[] = $field->ctc_to;
            $row[] = $field->ctc_address;
            $row[] = $status;
            $row[] = $this->tgl_indo(date("Y-m-j", strtotime($field->tgl_tempo)));

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_sml_invoice->count_all(),
            "recordsFiltered" => $this->M_sml_invoice->count_filtered(),
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
        $data['id_user'] = $this->session->userdata('id_user');
        $data['name'] = $this->db->select('username')
            ->from('tbl_user')
            ->where('id_user', $data['id_user'])
            ->get()
            ->row('username');
        $data['invoice'] = $this->M_sml_invoice->getInvoiceData($id);
        $data['details'] = $this->M_sml_invoice->get_detail($id);
        $data['status'] = $this->M_sml_invoice->get_by_id($id)->payment_status;
        $data['rekening'] = $this->db->get_where('sml_rek_invoice', ['invoice_id' => $id])->result_array();
        $data['detail'] = $this->db->get_where('sml_detail_invoice', ['invoice_id' => $id])->result_array();
        $data['title'] = 'backend/sml_invoice/sml_invoice_read';
        $data['title_view'] = 'Prepayment';
        $this->load->view('backend/home', $data);
    }

    // UNTUK MENAMPILKAN FORM ADD
    public function add_form()
    {
        $data['id'] = 0;
        $data['title'] = 'backend/sml_invoice/sml_invoice_form';
        $data['title_view'] = 'Invoice Form';
        $data['rek_options'] = $this->M_sml_invoice->options()->result();
        // $data['notif'] = $this->M_notifikasi->pending_notification();
        $this->load->view('backend/home', $data);
    }

    // MEREGENERATE KODE INVOICE
    public function generate_kode()
    {
        $date = $this->input->post('date');

        $kode = $this->M_sml_invoice->max_kode($date)->row();

        if (empty($kode->kode_invoice)) {
            $no_urut = 1;
        } else {
            $bln = substr($kode->kode_invoice, 5, 2);
            $no_urut = substr($kode->kode_invoice, 10) + 1;
        }
        $urutan = str_pad($no_urut, 4, "0", STR_PAD_LEFT);
        $year = substr($date, 8, 2);
        $month = substr($date, 3, 2);
        $data = 'INVBM' . $year . $month . $urutan;
        echo json_encode($data);
    }

    // UNTUK MENAMPILKAN FORM EDIT
    function edit_form($id)
    {
        $data['id'] = $id;
        $data['aksi'] = 'update';
        $data['title_view'] = "Edit Data Invoice";
        $data['title'] = 'backend/sml_invoice/sml_invoice_form';
        $data['rek_options'] = $this->M_sml_invoice->options()->result();
        $this->load->view('backend/home', $data);
    }

    function edit_data($id)
    {
        $data['master'] = $this->M_sml_invoice->get_by_id($id);
        $data['rek_invoice'] = $this->db->get_where('sml_rek_invoice', ['invoice_id' => $id])->result_array();
        $data['detail_invoice'] = $this->db->get_where('sml_detail_invoice', ['invoice_id' => $id])->result_array();
        echo json_encode($data);
    }

    function read_detail($id)
    {
        $data = $this->M_sml_invoice->get_by_id_detail($id);
        echo json_encode($data);
    }

    // MENAMBAHKAN DATA
    public function add()
    {
        $id_user = $this->session->userdata('id_user');
        // INSERT KODE PREPAYMENT SAAT SUBMIT
        $date = $this->input->post('tgl_invoice');

        $kode = $this->M_sml_invoice->max_kode($date)->row();

        if (empty($kode->kode_invoice)) {
            $no_urut = 1;
        } else {
            $bln = substr($kode->kode_invoice, 3, 2);
            $no_urut = substr($kode->kode_invoice, 7) + 1;
        }
        $urutan = str_pad($no_urut, 3, "0", STR_PAD_LEFT);
        $year = substr($date, 8, 2);
        $month = substr($date, 3, 2);

        $kode_invoice = 'S' . $year . $month . $urutan;

        $data = array(
            'kode_invoice' => $kode_invoice,
            'id_user' => $id_user,
            'tgl_invoice' => date('Y-m-d', strtotime($this->input->post('tgl_invoice'))),
            'tgl_tempo' => date('Y-m-d', strtotime($this->input->post('tgl_tempo'))),
            'ctc_to' => $this->input->post('ctc_to'),
            'ctc_address' => $this->input->post('ctc_address'),
            'metode' => $this->input->post('metode'),
            'total' => preg_replace('/\D/', '', $this->input->post('total_nominal')),
            'created_at' => date('Y-m-d H:i:s')
        );

        if (!empty($this->input->post('tax'))) {
            $data['tax'] = preg_replace('/\D/', '', $this->input->post('tax'));
        }

        if (!empty($this->input->post('diskon'))) {
            $data['diskon'] = preg_replace('/\D/', '', $this->input->post('diskon'));
        }

        $inserted = $this->M_sml_invoice->save($data);

        if ($inserted) {
            // INISIASI VARIABEL INPUT DETAIL PREPAYMENT
            $nama_rek = $this->input->post('nama_rek[]');
            $nama_bank = $this->input->post('nama_bank[]');
            $no_rek = $this->input->post('no_rek[]');
            if (!empty($no_rek)) {
                //PERULANGAN UNTUK INSER QUERY DETAIL PREPAYMENT
                for ($i = 1; $i <= count($nama_bank); $i++) {
                    $data2[] = array(
                        'invoice_id' => $inserted,
                        'nama' => $nama_rek[$i],
                        'nama_bank' => $nama_bank[$i],
                        'no_rek' => $no_rek[$i]
                    );
                }
                $this->M_sml_invoice->save_detail($data2);
            }
        }

        if ($inserted) {
            // INISIASI VARIABEL INPUT DETAIL PREPAYMENT
            $item = $this->input->post('item[]');
            $nopol = $this->input->post('nopol[]');
            $tipe = $this->input->post('tipe[]');
            $total = preg_replace('/\D/', '', $this->input->post('total[]'));
            //PERULANGAN UNTUK INSER QUERY DETAIL PREPAYMENT
            if (!empty($item)) {
                for ($i = 1; $i <= count($item); $i++) {
                    $data3[] = array(
                        'invoice_id' => $inserted,
                        'deskripsi' => $item[$i],
                        'nopol' => $nopol[$i],
                        'tipe' => $tipe[$i],
                        'total' => $total[$i]
                    );
                }
                $this->M_sml_invoice->save_detail2($data3);
            }
        }

        echo json_encode(array("status" => TRUE));
    }

    // UPDATE DATA
    public function update()
    {
        $data = array(
            'tgl_invoice' => date('Y-m-d', strtotime($this->input->post('tgl_invoice'))),
            'tgl_tempo' => date('Y-m-d', strtotime($this->input->post('tgl_tempo'))),
            'ctc_to' => $this->input->post('ctc_to'),
            'ctc_address' => $this->input->post('ctc_address'),
            'metode' => $this->input->post('metode'),
            'tax' => preg_replace('/\D/', '', $this->input->post('tax')),
            'total' => preg_replace('/\D/', '', $this->input->post('total_nominal')),
            'created_at' => date('Y-m-d H:i:s')
        );

        if (!empty($this->input->post('tax'))) {
            $data['tax'] = preg_replace('/\D/', '', $this->input->post('tax'));
        } else {
            $data['tax'] = 0;
        }

        if (!empty($this->input->post('diskon'))) {
            $data['diskon'] = preg_replace('/\D/', '', $this->input->post('diskon'));
        } else {
            $data['diskon'] = 0;
        }

        //UPDATE DETAIL PREPAYMENT
        $id_detail = $this->input->post('hidden_id[]');
        // $invoice_id = $this->input->post('hidden_invoiceId[]');
        $item = $this->input->post('item[]');
        $nopol = $this->input->post('nopol[]');
        $tipe = $this->input->post('tipe[]');
        $total = preg_replace('/\D/', '', $this->input->post('total[]'));
        if ($this->db->update('sml_invoice', $data, ['id' => $this->input->post('id')])) {
            // UNTUK MENGHAPUS ROW YANG TELAH DIDELETE
            $deletedRows = json_decode($this->input->post('deleted_rows'), true);
            if (!empty($deletedRows)) {
                foreach ($deletedRows as $delRows) {
                    // Hapus row dari database berdasarkan ID
                    $this->db->where('id', $delRows);
                    $this->db->delete('sml_detail_invoice');
                }
            }
            //MELAKUKAN REPLACE DATA LAMA DENGAN YANG BARU
            for ($i = 1; $i <= count($_POST['item']); $i++) {
                // Set id menjadi NULL jika id_detail tidak ada atau kosong
                $id_invoice = !empty($id_detail[$i]) ? $id_detail[$i] : NULL;
                $data2[] = array(
                    'id' => $id_invoice,
                    'invoice_id' => $this->input->post('id'),
                    'deskripsi' => $item[$i],
                    'nopol' => $nopol[$i],
                    'tipe' => $tipe[$i],
                    'total' => $total[$i]
                );
                // Menggunakan db->replace untuk memasukkan atau menggantikan data
                $this->db->replace('sml_detail_invoice', $data2[$i - 1]);
            }

            // UNTUK MENGHAPUS REKENING
            $deletedRekRows = json_decode($this->input->post('deletedRekRows'), true);
            if (!empty($deletedRekRows)) {
                foreach ($deletedRekRows as $delRekRow) {
                    // Hapus row dari database berdasarkan ID
                    $this->db->where('id', $delRekRow);
                    $this->db->delete('sml_rek_invoice');
                }
            }

            // MELAKUKAN REPLACE DATA REKENING
            $id_rek = $this->input->post('hidden_rekId[]');
            $nama_rek = $this->input->post('nama_rek[]');
            $nama_bank = $this->input->post('nama_bank[]');
            $no_rek = $this->input->post('no_rek[]');
            if (!empty($nama_bank)) {
                for ($i = 1; $i <= count($_POST['nama_bank']); $i++) {
                    // Set id menjadi NULL jika id_rek tidak ada atau kosong
                    $id_rekening = !empty($id_rek[$i]) ? $id_rek[$i] : NULL;
                    $data3[] = array(
                        'id' => $id_rekening,
                        'invoice_id' => $this->input->post('id'),
                        'nama' => $nama_rek[$i],
                        'nama_bank' => $nama_bank[$i],
                        'no_rek' => $no_rek[$i]
                    );
                    // Menggunakan db->replace untuk memasukkan atau menggantikan data
                    $this->db->replace('sml_rek_invoice', $data3[$i - 1]);
                }
            }
        }
        echo json_encode(array("status" => TRUE));
    }

    // MENGHAPUS DATA
    function delete($id)
    {
        $this->M_sml_invoice->delete($id);
        echo json_encode(array("status" => TRUE));
    }

    public function send_email()
    {
        $this->load->library('email');
        $this->load->library('tcpdf'); // Pastikan TCPDF sudah di-load
        $email = $this->input->post('email'); // Ambil email tujuan
        $id = $this->input->post('id');

        if ($email) {
            // Konfigurasi email
            $config = [
                'protocol'  => 'smtp',
                'smtp_host' => 'ssl://smtp.googlemail.com',
                'smtp_user' => 'audricafabiano@gmail.com',
                'smtp_pass' => 'rxhr ylvy dgwg lwgl',
                'smtp_port' => 465,
                'mailtype'  => 'html',
                'charset'   => 'utf-8',
                'newline'   => "\r\n"
            ];

            $this->email->initialize($config);

            $data['id'] = $id;
            $data['sub'] = 'bmn';
            $data['output'] = 'save';
            $this->load->library('tcpdf_invoice'); // Sesuaikan dengan nama file library
            $pdf_content = $this->tcpdf_invoice->generateInvoice($data); // Gunakan nama yang benar

            // Simpan PDF sementara
            $pdf_path = FCPATH . 'assets/backend/uploads/Invoice ByMoment.pdf'; // Simpan di folder uploads

            // **2. Kirim email dengan lampiran PDF**
            $this->email->from('audricafabiano@gmail.com', 'Audrica Ewaldo');
            $this->email->to($email);
            $this->email->subject('Invoice by.moment');
            $this->email->message('Terlampir invoice Anda dalam format PDF.');

            // **3. Attach PDF**
            $this->email->attach($pdf_path);

            if ($this->email->send()) {
                echo json_encode(array("status" => TRUE, "message" => "Email berhasil dikirim dengan PDF."));
            } else {
                echo json_encode(array("status" => FALSE, "message" => $this->email->print_debugger()));
            }

            // **4. Hapus file setelah dikirim agar tidak menumpuk**
            unlink($pdf_path);
        } else {
            echo json_encode(array("status" => FALSE, "message" => "Email tidak ditemukan."));
        }
    }


    // PRINTOUT TCPDF
    public function generate_pdf($id)
    {
        $sub = 'sml';
        $output = 'view';
        $invoice = $this->M_sml_invoice->get_by_id($id);
        $invoice_details = $this->M_sml_invoice->get_detail($id);
        $invoice_rek = $this->M_sml_invoice->get_rek($id);
        $status = $this->M_sml_invoice->get_by_id($id)->payment_status;

        // Initialize the TCPDF object
        $t_cpdf2 = new t_cpdf2('P', 'mm', 'A4', true, 'UTF-8', false);

        // Set document properties
        $t_cpdf2->SetCreator(PDF_CREATOR);
        $t_cpdf2->SetAuthor('Author Name');
        $t_cpdf2->SetTitle('Invoice Pengenumroh PDF');

        $t_cpdf2->SetMargins(15, 40, 15); // Margin kiri, atas (untuk header), kanan
        // $t_cpdf2->SetHeaderMargin(30);    // Jarak antara header dan konten
        $t_cpdf2->SetAutoPageBreak(true, 15); // Penanganan otomatis margin bawah

        $t_cpdf2->AddPage();
        $t_cpdf2->SetFont('helvetica', '', 10);

        // Informasi alamat
        $t_cpdf2->SetFont('helvetica', 'B');
        $t_cpdf2->Cell(40, 6, 'Jl. Kp. Tunggilis RT 001 RW 007', 0, 1);
        $t_cpdf2->SetFont('helvetica', 'B', 10);
        $t_cpdf2->Cell(0, 6, 'Situsari Kec. Cileungsi Kab. Bogor', 0, 0);

        // Informasi Invoice
        $t_cpdf2->SetY($t_cpdf2->getY() - 6.5);
        $t_cpdf2->SetX(129);
        $t_cpdf2->Cell(30, 6, 'No. Invoice', 0, 0);
        $t_cpdf2->Cell(50, 6, ': ' . $invoice->kode_invoice, 0, 1);
        $t_cpdf2->SetX(129);
        $t_cpdf2->Cell(30, 6, 'Tanggal', 0, 0);
        $t_cpdf2->Cell(50, 6, ': ' . date('d-m-Y', strtotime($invoice->tgl_invoice)), 0, 1);
        $t_cpdf2->SetX(129);
        $t_cpdf2->Cell(30, 6, 'Jatuh Tempo', 0, 0);
        $t_cpdf2->Cell(50, 6, ': ' . date('d-m-Y', strtotime($invoice->tgl_tempo)), 0, 1);
        $t_cpdf2->SetX(129);
        $t_cpdf2->Cell(30, 6, 'Metode', 0, 0);
        $t_cpdf2->Cell(50, 6, ': ' . $invoice->metode, 0, 1);
        // $t_cpdf2->Ln(5);

        // Informasi Penerima
        $t_cpdf2->SetFont('helvetica', 'B');
        $t_cpdf2->SetY($t_cpdf2->getY() - 6.5);
        $t_cpdf2->Cell(40, 6, 'Ditujukan Kepada:', 0, 1);
        $t_cpdf2->SetFont('helvetica', 'B', 10);
        $t_cpdf2->Cell(0, 6, 'PT. MANDIRI CIPTA SEJAHTERA', 0, 1);
        $t_cpdf2->SetFont('helvetica', '', 10);
        $t_cpdf2->MultiCell(0, 6, 'Ruko Niaga Citra Grand Blok R9 3-6', 0, 'L');
        $t_cpdf2->Ln(5);

        // Tabel Detail Barang
        $t_cpdf2->SetFont('helvetica', 'B', 10);
        $t_cpdf2->Cell(10, 6, 'No.', 1, 0, 'C');
        $t_cpdf2->Cell(65, 6, 'Deskripsi', 1, 0, 'C');
        $t_cpdf2->Cell(25, 6, 'Nopol', 1, 0, 'C');
        $t_cpdf2->Cell(30, 6, 'Tipe', 1, 0, 'C');
        $t_cpdf2->Cell(44, 6, 'Total', 1, 1, 'C');

        $t_cpdf2->SetFont('helvetica', '', 10);
        $no = 1;
        $grand_total = 0;
        foreach ($invoice_details as $item) {
            $t_cpdf2->Cell(10, 6, $no++, 1, 0, 'C');
            $t_cpdf2->Cell(65, 6, $item->deskripsi, 1, 0, 'L');
            $t_cpdf2->Cell(25, 6, $item->nopol, 1, 0, 'C');
            $t_cpdf2->Cell(30, 6, $item->tipe, 1, 0, 'C');
            $t_cpdf2->Cell(44, 6, number_format($item->total, 0, ',', '.'), 1, 1, 'R');
            $grand_total += $item->total;
        }

        // Total
        $t_cpdf2->SetFont('helvetica', 'B', 10);
        if ($invoice->tax > 0) {
            $t_cpdf2->Cell(130, 6, 'PPN', 1, 0, 'R');
            $t_cpdf2->Cell(44, 6, number_format($invoice->tax, 0, ',', '.'), 1, 1, 'R');
        }
        if ($invoice->diskon > 0) {
            $t_cpdf2->Cell(130, 6, 'Diskon', 1, 0, 'R');
            $t_cpdf2->Cell(44, 6, number_format($invoice->diskon, 0, ',', '.'), 1, 1, 'R');
        }
        $t_cpdf2->Cell(130, 6, 'Total', 1, 0, 'R');
        $t_cpdf2->Cell(44, 6, number_format($grand_total + $invoice->tax - $invoice->diskon, 0, ',', '.'), 1, 1, 'R');

        // Informasi Transfer
        $t_cpdf2->Ln(10);
        $t_cpdf2->SetFont('helvetica', 'B', 10);
        $t_cpdf2->Cell(0, 6, 'Pembayaran Transfer Melalui:', 0, 1);
        $t_cpdf2->Cell(0, 6, 'BCA Cab. Cibodas', 0, 1);
        $t_cpdf2->Cell(0, 6, 'No. Rekening : 7131720380', 0, 1);
        $t_cpdf2->SetFont('helvetica', '', 10);
        if (isset($invoice_rek)) {
            $list = <<<EOD
        <ol>
        EOD;

            foreach ($invoice_rek as $rek) {
                $list .= '<li>Nama : ' . $rek->nama . '<br>Bank : ' . $rek->nama_bank . '<br>No. Rekening : ' . $rek->no_rek . '</li>';
            }
            $list .= <<<EOD
        </ol>
        EOD;
            $t_cpdf2->SetFont('helvetica', '', 10);
            $y = $t_cpdf2->GetY();
            $x = 8;
            $t_cpdf2->writeHTMLCell(0, 0, $x, $y, $list, 0, 1, false, true, 'L', true);
        }
        $t_cpdf2->SetFont('helvetica', 'B', 10);
        $t_cpdf2->Cell(0, 6, 'a/n PT. Sahabat Multi Logistik', 0, 1);

        // Footer
        $t_cpdf2->Ln(10);
        $t_cpdf2->SetX(136);
        $t_cpdf2->Cell(0, 6, 'PT. SAHABAT MULTI LOGISTIK', 0, 1, 'L');
        $t_cpdf2->Image('assets/backend/img/cap.jpg', $t_cpdf2->getX() + 125, $t_cpdf2->getY() + 5, 18, 16);
        $t_cpdf2->Image('assets/backend/img/ttdsml.png', $t_cpdf2->getX() + 135, $t_cpdf2->getY() + 5, 30, 20);
        $t_cpdf2->Ln(26);
        $t_cpdf2->SetX(148);
        $t_cpdf2->Cell(0, 6, 'M. Charles Manalu', 0, 1, 'L');

        // Output PDF
        if ($output == 'view') {
            $t_cpdf2->Output('Invoice.pdf', 'I');
        } elseif ($output == 'save') {
            $t_cpdf2->Output(FCPATH . 'assets/invoices/Invoice.pdf', 'F');
        }
    }

    function payment()
    {
        $this->db->where('id', $this->input->post('id'));
        $this->db->update('sml_invoice', ['payment_status' => $this->input->post('payment_status')]);

        echo json_encode(array("status" => TRUE));
    }
}
