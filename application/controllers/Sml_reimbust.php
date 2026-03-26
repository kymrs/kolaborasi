<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sml_reimbust extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_sml_reimbust');
        $this->load->model('backend/M_notifikasi');
        $this->M_login->getsecurity();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {

        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        ($akses->view_level == 'N' ? redirect('auth') : '');
        $data['add'] = $akses->add_level;
        $data['alias'] = $this->session->userdata('username');

        $data['title'] = "backend/sml_reimbust/sml_reimbust_list";
        $data['titleview'] = "Data Reimbust";
        $name = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $this->session->userdata('id_user'))
            ->get()
            ->row('name');
        $data['approval'] = $this->db->select('COUNT(*) as total_approval')
            ->from('sml_reimbust')
            ->where('app_name', $name)
            ->or_where('app2_name', $name)
            ->or_where('app4_name', $name)
            ->get()
            ->row('total_approval');
        $this->load->view('backend/home', $data);
    }

    // get list reimbust
    function get_list()
    {
        // INISIAI VARIABLE YANG DIBUTUHKAN
        $fullname = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $this->session->userdata('id_user'))
            ->get()
            ->row('name');
        $list = $this->M_sml_reimbust->get_datatables();
        $data = array();
        $no = $_POST['start'];

        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        $read = $akses->view_level;
        $edit = $akses->edit_level;
        $delete = $akses->delete_level;
        $print = $akses->print_level;

        foreach ($list as $field) {

            // MENENTUKAN ACTION APA YANG AKAN DITAMPILKAN DI LIST DATA TABLES
            $action_read = ($read == 'Y') ? '<a href="sml_reimbust/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>&nbsp;' : '';
            $action_edit = ($edit == 'Y') ? '<a href="sml_reimbust/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;' : '';
            $action_delete = ($delete == 'Y') ? '<a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;' : '';
            $action_print = ($print == 'Y') ? '<a class="btn btn-success btn-circle btn-sm" target="_blank" href="sml_reimbust/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>' : '';

            // MENENTUKAN ACTION APA YANG AKAN DITAMPILKAN DI LIST DATA TABLES
            if ($this->session->userdata('username') == 'eko') {
                $action = $action_read . $action_edit . $action_delete . $action_print;
            } elseif ($field->id_user == $this->session->userdata('id_user') && !in_array($field->status, ['rejected', 'approved', 'revised']) && $field->app_status == "waiting") {
                $action = $action_read . $action_edit . $action_delete . $action_print;
            } elseif (($field->id_user == $this->session->userdata('id_user') || $this->session->userdata('username') == 'eko') && $field->status == 'revised') {
                $action = $action_read . $action_edit . $action_print;
            } else {
                $action = $action_read . $action_print;
            }

            //MENENSTUKAN SATTSU PROGRESS PENGAJUAN PERMINTAAN
            if ($field->app_status == 'approved' && $field->app2_status == 'waiting' && $field->status == 'on-process') {
                $status = $field->status . ' (' . $field->app2_name . ')';
            } elseif ($field->app_status == 'waiting' && $field->app2_status == 'waiting' && $field->status == 'on-process') {
                $status = $field->status . ' (' . $field->app_name . ')';
            } else {
                $status = $field->status;
            }

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $action;
            if ($field->payment_status == 'paid') {
                $row[] = '<div class="text-center"><i class="fas fa-check" style="color: green;"></i></div>'; // Ikon checklist hijau di tengah
            } else if ($field->payment_status == 'unpaid') {
                $row[] = '<div class="text-center"><i class="fas fa-times" style="color: red;"></i></div>'; // Ikon unchecklist merah di tengah
            }
            $row[] = strtoupper($field->kode_reimbust);
            $row[] = $field->name;
            // $row[] = $field->jabatan;
            // $row[] = $field->departemen;
            $row[] = $field->sifat_pelaporan;
            // Array bulan bahasa Indonesia
            $bulanIndo = array(
                1 => 'Januari',
                2 => 'Februari',
                3 => 'Maret',
                4 => 'April',
                5 => 'Mei',
                6 => 'Juni',
                7 => 'Juli',
                8 => 'Agustus',
                9 => 'September',
                10 => 'Oktober',
                11 => 'November',
                12 => 'Desember'
            );
            $row[] = date("d", strtotime($field->tgl_pengajuan)) . " " . $bulanIndo[date("n", strtotime($field->tgl_pengajuan))] . " " . date("Y", strtotime($field->tgl_pengajuan));
            $row[] = $field->tujuan;
            $row[] = 'Rp. ' . number_format($field->jumlah_prepayment, 0, ',', '.');
            $row[] = ucwords($status);

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_sml_reimbust->count_all(),
            "recordsFiltered" => $this->M_sml_reimbust->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    // get list deklarasi
    function get_list2()
    {
        // INISIAI VARIABLE YANG DIBUTUHKAN
        $fullname = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $this->session->userdata('id_user'))
            ->get()
            ->row('name');
        $list = $this->M_sml_reimbust->get_datatables2();
        $data = array();
        $no = $_POST['start'];

        //LOOPING DATATABLES
        foreach ($list as $field) {

            // MENENTUKAN ACTION APA YANG AKAN DITAMPILKAN DI LIST DATA TABLES
            if ($field->app_name == $fullname) {
                $action = '<a href="sml_datadeklarasi/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                                <a class="btn btn-success btn-circle btn-sm" href="sml_datadeklarasi/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } elseif ($field->app2_name == $fullname) {
                $action = '<a href="sml_datadeklarasi/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>     
                                <a class="btn btn-success btn-circle btn-sm" href="sml_datadeklarasi/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } elseif (in_array($field->status, ['rejected', 'approved'])) {
                $action = '<a href="sml_datadeklarasi/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                <a class="btn btn-success btn-circle btn-sm" href="sml_datadeklarasi/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } elseif ($field->app_status == 'revised' || $field->app2_status == 'revised') {
                $action = '<a href="sml_datadeklarasi/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                    <a href="sml_datadeklarasi/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
                    <a class="btn btn-success btn-circle btn-sm" href="sml_datadeklarasi/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } elseif ($field->app_status == 'approved') {
                $action = '<a href="sml_datadeklarasi/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                            <a class="btn btn-success btn-circle btn-sm" href="sml_datadeklarasi/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } else {
                $action = '<a href="sml_datadeklarasi/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                        <a href="sml_datadeklarasi/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
			            <a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>
                        <a class="btn btn-success btn-circle btn-sm" href="sml_datadeklarasi/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            }

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $action;
            $row[] = strtoupper($field->kode_deklarasi);
            $row[] = date("d M Y", strtotime($field->tgl_deklarasi));
            $row[] = $field->name;
            $row[] = $field->jabatan;
            $row[] = $field->nama_dibayar;
            $row[] = $field->tujuan;
            $row[] = 'Rp. ' . number_format($field->sebesar, 0, ',', '.');;
            // $row[] = $field->sebesar;
            $row[] = $field->status;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_sml_reimbust->count_all2(),
            "recordsFiltered" => $this->M_sml_reimbust->count_filtered2(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    // get list prepayment
    function get_list3()
    {
        // INISIAI VARIABLE YANG DIBUTUHKAN
        $fullname = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $this->session->userdata('id_user'))
            ->get()
            ->row('name');
        $list = $this->M_sml_reimbust->get_datatables3();
        $data = array();
        $no = $_POST['start'];

        //LOOPING DATATABLES
        foreach ($list as $field) {

            // MENENTUKAN ACTION APA YANG AKAN DITAMPILKAN DI LIST DATA TABLES
            if ($field->app_name == $fullname) {
                $action = '<a href="prepayment/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                                <a class="btn btn-success btn-circle btn-sm" href="prepayment/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } elseif ($field->app2_name == $fullname) {
                $action = '<a href="prepayment/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>     
                                <a class="btn btn-success btn-circle btn-sm" href="prepayment/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } elseif (in_array($field->status, ['rejected', 'approved'])) {
                $action = '<a href="prepayment/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                <a class="btn btn-success btn-circle btn-sm" href="prepayment/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } elseif ($field->app_status == 'revised' || $field->app2_status == 'revised') {
                $action = '<a href="prepayment/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                    <a href="prepayment/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
                    <a class="btn btn-success btn-circle btn-sm" href="prepayment/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } elseif ($field->app_status == 'approved') {
                $action = '<a href="prepayment/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                            <a class="btn btn-success btn-circle btn-sm" href="prepayment/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } else {
                $action = '<a href="prepayment/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                        <a href="prepayment/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
                        <a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>
                        <a class="btn btn-success btn-circle btn-sm" href="prepayment/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            }


            $formatted_nominal = number_format($field->total_nominal, 0, ',', '.');
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $action;
            $row[] = strtoupper($field->kode_prepayment);
            $row[] = $field->name;
            $row[] = strtoupper($field->divisi);
            $row[] = strtoupper($field->jabatan);
            $row[] = date("d M Y", strtotime($field->tgl_prepayment));
            $row[] = $field->tujuan;
            $row[] = $formatted_nominal;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_sml_reimbust->count_all3(),
            "recordsFiltered" => $this->M_sml_reimbust->count_filtered3(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    function read_form($id)
    {

        $data['aksi'] = 'read';
        $data['user'] = $this->M_sml_reimbust->get_by_id($id);
        $data['app_name'] = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $this->session->userdata('id_user'))
            ->get()
            ->row('name');
        $data['app2_name'] = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $this->session->userdata('id_user'))
            ->get()
            ->row('name');
        $data['id'] = $id;
        $data['title_view'] = "Data Reimbust";
        $data['title'] = 'backend/sml_reimbust/sml_reimbust_read';
        $this->db->select('kwitansi');
        $this->db->where('reimbust_id', $id);
        $data['kwitansi'] = $this->db->get('sml_reimbust_detail')->result_array();
        $this->load->view('backend/home', $data);
    }

    public function add_form()
    {
        // INISIASI
        $id = $this->session->userdata('id_user');
        $data['id_user'] = $id;
        $data['id_pembuat'] = 0;

        $data['id'] = 0;
        $data['aksi'] = 'add';
        $data['rek_options'] = $this->M_sml_reimbust->options($id)->result_array();
        $data['title_view'] = "Reimbust Form";
        $data['title'] = 'backend/sml_reimbust/sml_reimbust_form';
        $this->load->view('backend/home', $data);
    }

    public function generate_pdf($id)
    {
        // Load FPDF library
        $this->load->library('Fpdf_generate');

        // Load data from database based on $id
        $data['master'] = $this->M_sml_reimbust->get_by_id($id);
        $data['transaksi'] = $this->M_sml_reimbust->get_by_id_detail($id);
        $data['user'] = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $data['master']->id_user)
            ->get()
            ->row('name');
        $data['app_status'] = strtoupper($data['master']->app_status);
        $data['app2_status'] = strtoupper($data['master']->app2_status);

        // Start FPDF
        $pdf = new FPDF('L', 'mm', 'Letter');
        $pdf->SetTitle('Form Pengajuan Reimbust');
        $pdf->AddPage();

        // Mengatur margin kiri, atas, dan kanan
        $pdf->SetMargins(10, 10, 0); // Margin kiri 20mm, atas 10mm, kanan 20mm

        // Mengatur margin bawah (dan aktifkan auto page break)
        $pdf->SetAutoPageBreak(true, 5); // Margin bawah 15mm

        // Logo
        $pdf->Image(base_url('') . '/assets/backend/img/sml.png', 10, 12, 49, 16);

        // Set font
        $pdf->AddFont('Poppins-Regular', '', 'Poppins-Regular.php');
        $pdf->AddFont('Poppins-Bold', '', 'Poppins-Bold.php');

        $pdf->SetFont('Poppins-Bold', '', 16);

        // Teks yang ingin ditampilkan
        $text1 = 'FORM PELAPORAN / REIMBUST';
        $text2 = 'SAHABAT MULTI LOGISTIK';

        // Menghitung lebar teks
        $textWidth1 = $pdf->GetStringWidth($text1);
        $textWidth2 = $pdf->GetStringWidth($text2);

        // Menghitung posisi X agar teks berada di tengah halaman
        $pageWidth = $pdf->GetPageWidth();
        $x1 = ($pageWidth - $textWidth1) / 2;
        $x2 = ($pageWidth - $textWidth2) / 2;

        // Menempatkan teks di tengah halaman secara horizontal
        $pdf->SetXY($x1, 9); // Y posisi diatur dengan nilai tetap
        $pdf->Cell($textWidth1, 10, $text1, 0, 1, 'C');

        $pdf->SetXY($x2, 18); // Y posisi diatur dengan nilai tetap
        $pdf->Cell($textWidth2, 10, $text2, 0, 1, 'C');

        // Enter
        $pdf->SetY(35); // Posisi Y (vertikal) dari garis
        $pdf->SetX(10); // Posisi X (horizontal) dari garis

        // Field Master

        $pdf->SetFont('Poppins-Regular', '', 10);

        function Cell($pdf, $width, $height, $text, $align = 'L', $fill = false)
        {
            $pdf->Cell($width, $height, $text, 0, 0, $align, $fill);
        }

        // Function to create a row in the table
        function Row($pdf, $height, $data, $widths, $fill = false)
        {
            $pdf->SetX(10); // Start at X position

            for ($i = 0; $i < count($data); $i++) {
                // Adjust the width of columns as needed to reduce space
                $pdf->Cell($widths[$i], $height, $data[$i], 0, 0, 'L', $fill);
            }

            $pdf->Ln(); // Move to the next line
        }

        // Set column widths (adjust widths as necessary to reduce spacing)
        $widths = array(43, 3.5, 60); // Adjusted width for the columns

        $tanggal = $data['master']->tgl_pengajuan;
        $formatted_date = date('d F Y', strtotime($tanggal));

        // Array untuk mengubah nama bulan
        $months = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember'
        ];

        // Mengganti nama bulan
        $month = date('F', strtotime($tanggal));
        $translated_month = $months[$month];
        $formatted_date = str_replace($month, $translated_month, $formatted_date);

        // Add some data with adjusted column widths
        Row($pdf, 10, array('NAMA', ':', $data['user']), $widths, false);
        $pdf->Ln(-3);
        // Row($pdf, 10, array('JABATAN', ':', $data['master']->jabatan), $widths, false);
        // $pdf->Ln(-3);
        // Row($pdf, 10, array('DEPARTEMEN', ':', $data['master']->departemen), $widths, false);
        // $pdf->Ln(-3);
        Row($pdf, 10, array('SIFAT PELAPORAN', ':', $data['master']->sifat_pelaporan), $widths, false);
        $pdf->Ln(-3);
        Row($pdf, 10, array('TANGGAL', ':', $formatted_date), $widths, false);
        $pdf->Ln(-3);
        Row($pdf, 10, array('TUJUAN', ':',  $data['master']->tujuan), $widths, false);

        // Set font for the table header
        $pdf->SetY($pdf->GetY() + -1); // Move down from previous cell

        $jumlah_prepayment = number_format($data['master']->jumlah_prepayment, 0, ',', '.');

        // Add table headers
        // Tambahkan "JUMLAH PREPAYMENT" dalam satu Cell
        $pdf->SetFont('Poppins-Regular', '', 9);
        $pdf->Cell(193, 7, 'No. Prepayment : ' . (!empty($data['master']->kode_prepayment) ? $data['master']->kode_prepayment : '-'), 0, 1, 'R');

        $pdf->SetFont('Poppins-Bold', '', 10);

        $pdf->Cell(193, 8.5, 'JUMLAH PREPAYMENT', 1, 0, 'L');
        $pdf->Cell(66, 8.5, 'BUKTI PENGELUARAN', 1, 0, 'C');

        // Tambahkan "Rp. 500.000" dalam Cell terpisah, dengan posisi terpisah dari teks
        $pdf->Cell(-67, 8.5, 'Rp. ' . $jumlah_prepayment, 0, 1, 'R');
        $pdf->Cell(120, 8.5, 'PEMAKAIAN', 1);
        $pdf->Cell(40, 8.5, 'TGL NOTA', 1, 0, 'C');
        $pdf->Cell(33, 8.5, 'JUMLAH', 1, 0, 'C');
        $pdf->Cell(33, 8.5, 'KWITANSI', 1, 0, 'C');
        $pdf->Cell(33, 8.5, 'DEKLARASI', 1, 1, 'C');

        // Set font for table body
        $pdf->SetFont('Poppins-Regular', '', 10);

        // Add table data
        $no = 1;
        $totalJumlah = 0;
        $jumlahPengurangan = $data['master']->jumlah_prepayment;
        foreach ($data['transaksi'] as $row) {
            $tanggal = $row['tgl_nota'];
            $formatted_date = date('d F Y', strtotime($tanggal));

            // Array untuk mengubah nama bulan
            $months = [
                'January' => 'Januari',
                'February' => 'Februari',
                'March' => 'Maret',
                'April' => 'April',
                'May' => 'Mei',
                'June' => 'Juni',
                'July' => 'Juli',
                'August' => 'Agustus',
                'September' => 'September',
                'October' => 'Oktober',
                'November' => 'November',
                'December' => 'Desember'
            ];

            // Mengganti nama bulan
            $month = date('F', strtotime($tanggal));
            $translated_month = $months[$month];
            $formatted_date = str_replace($month, $translated_month, $formatted_date);

            $pdf->Cell(120, 8.5, $no++ . '. ' . $row['pemakaian'], 1);
            $pdf->Cell(40, 8.5, $formatted_date, 1, 0, 'C');
            $jumlah = $row['jumlah'];

            $totalJumlah += $jumlah;
            $sisaPrepayment = $jumlahPengurangan - $totalJumlah;

            $pdf->Cell(33, 8.5, number_format($jumlah, 0, ',', '.'), 1, 0, 'C');
            $pdf->Cell(33, 8.5, $row['kwitansi'] ? 'Ada' : '-', 1, 0, 'C');
            $pdf->Cell(33, 8.5, $row['deklarasi'] ? 'Ada' : '-', 1, 1, 'C');
        }

        // Add total and remaining prepayment
        $pdf->SetFont('Poppins-Bold', '', 10);
        $pdf->Cell(259, 8.5, 'TOTAL PEMAKAIAN', 1, 0, 'L');
        $pdf->Cell(-1, 8.5, 'Rp. ' . number_format($totalJumlah, 0, ',', '.'), 0, 1, 'R');
        $pdf->Cell(259, 8.5, 'SISA PREPAYMENT', 1, 0, 'L');
        $pdf->Cell(-1, 8.5, 'Rp. ' . number_format($sisaPrepayment, 0, ',', '.'), 0, 1, 'R');

        $pdf->Ln(10);

        $pdf->SetFont('Poppins-Regular', '', 10);
        // Membuat header tabel
        $pdf->Cell(47.3, 8.5, 'Yang Melakukan', 1, 0, 'C');
        $pdf->Cell(47.3, 8.5, 'Memeriksa', 1, 0, 'C');
        $pdf->Cell(47.3, 8.5, 'Mengetahui', 1, 0, 'C');
        $pdf->Cell(47.3, 8.5, 'Menyetujui', 1, 1, 'C');

        // Set font normal untuk konten tabel
        $pdf->SetFont('Poppins-Regular', '', 10);

        // Baris pemisah
        $pdf->Cell(47.3, 5, '', 'LR', 0, 'C');
        $pdf->Cell(47.3, 5, '', 0, 0, 'C');
        $pdf->Cell(47.3, 5, '', 'L', 0, 'C');
        $pdf->Cell(47.3, 5, '', 'LR', 1, 'C');

        // Baris pertama (Status)
        $pdf->Cell(47.3, 5, 'CREATED', 'LR', 0, 'C');
        $pdf->Cell(47.3, 5, strtoupper($data['master']->app4_status), 'R', 0, 'C');
        $pdf->Cell(47.3, 5, strtoupper($data['master']->app_status), 0, 0, 'C');
        $pdf->Cell(47.3, 5, strtoupper($data['master']->app2_status), 'LR', 1, 'C');

        // Baris kedua (Tanggal)
        $pdf->Cell(47.3, 5, $data['master']->created_at, 'LR', 0, 'C');
        $pdf->Cell(47.3, 5, $data['master']->app4_date, 'R', 0, 'C');
        $pdf->Cell(47.3, 5, $data['master']->app_date, 0, 0, 'C');
        $pdf->Cell(47.3, 5, $data['master']->app2_date, 'LR', 1, 'C');

        // Baris pemisah
        $pdf->Cell(47.3, 5, '', 'LR', 0, 'C');
        $pdf->Cell(47.3, 5, '', 0, 0, 'C');
        $pdf->Cell(47.3, 5, '', 'L', 0, 'C');
        $pdf->Cell(47.3, 5, '', 'LR', 1, 'C');

        // Jarak kosong untuk pemisah
        $pdf->Ln(0);

        // Baris ketiga (Nama pengguna)
        $pdf->Cell(47.3, 8.5, $data['user'], 1, 0, 'C');
        $pdf->Cell(47.3, 8.5, $data['master']->app4_name, 1, 0, 'C');
        $pdf->Cell(47.3, 8.5, $data['master']->app_name, 1, 0, 'C');
        $pdf->Cell(47.3, 8.5, $data['master']->app2_name, 1, 1, 'C');


        // Output the PDF
        $pdf->Output('I', 'Reimbust_SW - ' . $data['master']->kode_reimbust . '.pdf');
    }

    // MEREGENERATE KODE REIMBUST
    public function generate_kode()
    {
        $date = $this->input->post('date');
        $kode = $this->M_sml_reimbust->max_kode($date)->row();
        if (empty($kode->kode_reimbust)) {
            $no_urut = 1;
        } else {
            $bln = substr($kode->kode_reimbust, 3, 2);
            $no_urut = substr($kode->kode_reimbust, 5) + 1;
        }
        $urutan = str_pad($no_urut, 4, "0", STR_PAD_LEFT);
        $month = substr($date, 3, 2);
        $year = substr($date, 8, 2);
        $data = 'r' . $year . $month . $urutan;
        echo json_encode($data);
    }

    function edit_form($id)
    {
        // INISIASI
        $data['id_user'] = $this->session->userdata('id_user');
        $data['id_pembuat'] = $this->M_sml_reimbust->get_by_id($id)->id_user;


        $data['id'] = $id;
        $data['aksi'] = 'update';
        $data['title_view'] = "Edit Reimbust";
        $data['rek_options'] = $this->M_sml_reimbust->options($data['id_user'])->result_array();
        $data['title'] = 'backend/sml_reimbust/sml_reimbust_form';
        $this->load->view('backend/home', $data);
    }

    function edit_data($id)
    {
        $data['master'] = $this->M_sml_reimbust->get_by_id($id);
        $data['transaksi'] = $this->M_sml_reimbust->get_by_id_detail($id);
        $data['nama'] = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $data['master']->id_user)
            ->get()->row('name');
        echo json_encode($data);
    }

    function read_detail($id)
    {
        $data = $this->M_sml_reimbust->get_by_id_detail($id);
        echo json_encode($data);
    }

    public function detail_deklarasi()
    {
        if ($this->input->is_ajax_request()) {
            $deklarasi = $this->input->post('deklarasi');

            // Mengambil data deklarasi dari database
            $deklarasiRecord = $this->db->get_where('sml_deklarasi', ['kode_deklarasi' => $deklarasi])->row_array();

            // Debug log
            log_message('debug', 'Deklarasi: ' . print_r($deklarasi, true));
            log_message('debug', 'Deklarasi Record: ' . print_r($deklarasiRecord, true));

            if ($deklarasiRecord) {
                // Mengambil ID dari record yang ditemukan
                $deklarasiId = $deklarasiRecord['id']; // Pastikan 'id' adalah nama kolom yang sesuai
                $redirect_url = site_url('sml_datadeklarasi/read_form/' . $deklarasiId);

                $response = array(
                    'status' => 'success',
                    'message' => 'Data berhasil diproses',
                    'redirect_url' => $redirect_url
                );
            } else {
                $response = array(
                    'status' => 'error',
                    'message' => 'Data deklarasi tidak ditemukan'
                );
            }

            // Mengirimkan response JSON
            echo json_encode($response);
        } else {
            show_error('No direct access allowed', 403);
        }
    }

    public function add()
    {
        // INSERT KODE REIMBUST SAAT SUBMIT
        $date = $this->input->post('tgl_pengajuan');
        $kode = $this->M_sml_reimbust->max_kode($date)->row();
        if (empty($kode->kode_reimbust)) {
            $no_urut = 1;
        } else {
            $bln = substr($kode->kode_reimbust, 3, 2);
            $no_urut = substr($kode->kode_reimbust, 5) + 1;
        }
        $urutan = str_pad($no_urut, 4, "0", STR_PAD_LEFT);
        $month = substr($date, 3, 2);
        $year = substr($date, 8, 2);
        $kode_reimbust = 'r' . $year . $month . $urutan;

        // Mulai transaksi agar operasi insert/update atomic
        $this->db->trans_begin();

        // Load library upload
        $this->load->library('upload');

        // CHECK APAKAH MENGINPUT YANG SUDAH ADA ATAU YANG BARU (REKENING)
        if (!empty($_POST['nama_rek'])) {
            $no_rek = $this->input->post('nama_rek') . "-" . $this->input->post('nama_bank') . "-" . $this->input->post('nomor_rekening');
        } else {
            $no_rek = $this->input->post('rekening');
        }

        // INISIASI VARIABEL INPUT DETAIL REIMBUST
        $pemakaian = $this->input->post('pemakaian');
        $tgl_nota = $this->input->post('tgl_nota');
        $jumlah = $this->input->post('jumlah');

        // Bersihkan input untuk hanya mengambil angka
        $jumlahClean = preg_replace('/\D/', '', $jumlah);
        $deklarasi = $this->input->post('deklarasi');
        // jika ada deklarasi baru dari modal, buatkan entry sebelum detail loop
        $deklarasi_map = [];
        $used_deklarasi_codes = [];

        $deklarasiDataJson = $this->input->post('deklarasi_data');
        if ($deklarasiDataJson) {
            $deklarasiData = json_decode($deklarasiDataJson, true);
            if (is_array($deklarasiData)) {
                foreach ($deklarasiData as $d) {
                    if (empty($d['kode'])) {
                        continue;
                    }

                    $requestedCode = strtoupper(trim($d['kode']));

                    // Cek apakah kode sudah ada di database atau sudah diambil pada batch ini
                    $existingDeklarasi = $this->db->get_where('sml_deklarasi', ['kode_deklarasi' => $requestedCode])->row();
                    $alreadyUsedInBatch = in_array($requestedCode, $used_deklarasi_codes);

                    if ($existingDeklarasi || $alreadyUsedInBatch) {
                        // Bila ada konflik, generate kode berikutnya yang valid dan berurutan
                        $finalCode = $this->generate_new_sml_deklarasi_code($d['tgl'], $used_deklarasi_codes);
                        $deklarasi_map[$requestedCode] = $finalCode;
                    } else {
                        $finalCode = $requestedCode;
                        $used_deklarasi_codes[] = $finalCode;
                    }

                    // Data approval sebagaimana pada deklarasi controller
                    $id_menu2 = $this->db->select('id_menu')
                        ->where('link', 'sml_datadeklarasi')
                        ->get('tbl_submenu')
                        ->row();
                    $confirm2 = $this->db->select('app_id, app2_id, app4_id')
                        ->from('tbl_approval')
                        ->where('id_menu', $id_menu2->id_menu)
                        ->get()->row();
                    $appName2 = '';
                    $app2Name2 = '';
                    $app4Name2 = '';
                    if (!empty($confirm2)) {
                        $appName2 = $this->db->select('name')->from('tbl_data_user')->where('id_user', $confirm2->app_id)->get()->row('name');
                        $app2Name2 = $this->db->select('name')->from('tbl_data_user')->where('id_user', $confirm2->app2_id)->get()->row('name');
                        $app4Name2 = $this->db->select('name')->from('tbl_data_user')->where('id_user', $confirm2->app4_id)->get()->row('name');
                    }
                    $userId2 = $this->session->userdata('id_user');
                    $jabatan2 = $this->db->select('jabatan')->from('tbl_data_user')->where('id_user', $userId2)->get()->row('jabatan');

                    // Jika code sudah ada di DB, kita tidak menimpanya untuk menjamin data pertama tidak hilang.
                    if ($existingDeklarasi && $finalCode === $requestedCode) {
                        // tetap update jika kode persis ada dan tidak konflik (untuk memastikan detail terkini)
                        $this->db->update('sml_deklarasi', [
                            'tgl_deklarasi' => date('Y-m-d', strtotime($d['tgl'])),
                            'nama_dibayar' => $d['nama'],
                            'tujuan' => $d['tujuan'],
                            'sebesar' => preg_replace('/\D/', '', $d['sebesar'])
                        ], ['kode_deklarasi' => $requestedCode]);
                    } else {
                        // entri baru
                        $this->db->insert('sml_deklarasi', [
                            'kode_deklarasi' => $finalCode,
                            'tgl_deklarasi' => date('Y-m-d', strtotime($d['tgl'])),
                            'id_pengaju' => $userId2,
                            'jabatan' => $jabatan2,
                            'nama_dibayar' => $d['nama'],
                            'tujuan' => $d['tujuan'],
                            'sebesar' => preg_replace('/\D/', '', $d['sebesar']),
                            'app_name' => $appName2,
                            'app2_name' => $app2Name2,
                            'app4_name' => $app4Name2,
                            'is_active' => 1,
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                    }

                    // Simpan mapping untuk digunakan ketika menginsert detail
                    if ($finalCode !== $requestedCode) {
                        $deklarasi_map[$requestedCode] = $finalCode;
                    }
                }
            }
        }
        $id_user = $this->session->userdata('id_user');

        $data_user = $this->db->get_where('tbl_data_user', ['id_user' => $id_user])->row_array();

        $departemen = $data_user['divisi'];
        $jabatan = $data_user['jabatan'];

        // Flag untuk menandai apakah ada file yang lebih dari 3 MB
        $valid = true;
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png']; // Tipe file yang diizinkan

        // PERULANGAN UNTUK CEK UKURAN FILE
        for ($i = 1; $i <= count($pemakaian); $i++) {
            if (!empty($_FILES['kwitansi']['name'][$i])) {
                // Cek tipe file
                if (!in_array($_FILES['kwitansi']['type'][$i], $allowed_types)) {
                    $this->db->trans_rollback();
                    echo json_encode(array("status" => FALSE, "error" => "Tipe file tidak diizinkan untuk file ke-$i. Hanya file JPG dan PNG yang diperbolehkan."));
                    exit();
                }

                // Cek ukuran file
                if ($_FILES['kwitansi']['size'][$i] > 3072 * 1024) { // 3 MB in KB
                    $this->db->trans_rollback();
                    echo json_encode(array("status" => FALSE, "error" => "Ukuran file tidak boleh melebihi dari 3 MB untuk file ke-$i."));
                    exit();
                }
            }
        }

        // MENCARI SIAPA YANG AKAN MELAKUKAN APPROVAL PERMINTAAN
        $id_menu = $this->db->select('id_menu')
            ->where('link', $this->router->fetch_class())
            ->get('tbl_submenu')
            ->row();

        $confirm = $this->db->select('app_id, app2_id, app4_id')->from('tbl_approval')->where('id_menu', $id_menu->id_menu)->get()->row();
        if (!empty($confirm) && isset($confirm->app_id, $confirm->app2_id)) {
            $app = $confirm;
        } else {
            $this->db->trans_rollback();
            echo json_encode(array("status" => FALSE, "error" => "Approval Belum Ditentukan, Mohon untuk menghubungi admin."));
            exit();
        }

        // Inisialisasi data untuk tabel reimbust
        $data1 = array(
            'kode_reimbust' => $kode_reimbust,
            'kode_prepayment' => $this->input->post('kode_prepayment'),
            'id_user' => $id_user,
            'jabatan' => $jabatan,
            'departemen' => $departemen,
            'sifat_pelaporan' => $this->input->post('sifat_pelaporan'),
            'tgl_pengajuan' => date('Y-m-d', strtotime($this->input->post('tgl_pengajuan'))),
            'tujuan' => $this->input->post('tujuan'),
            'jumlah_prepayment' => $this->input->post('jumlah_prepayment'),
            'no_rek' => $no_rek,
            'app_name' => $this->db->select('name')
                ->from('tbl_data_user')
                ->where('id_user', $app->app_id)
                ->get()
                ->row('name'),
            'app2_name' => $this->db->select('name')
                ->from('tbl_data_user')
                ->where('id_user', $app->app2_id)
                ->get()
                ->row('name'),
            'app4_name' => $this->db->select('name')
                ->from('tbl_data_user')
                ->where('id_user', $app->app4_id)
                ->get()
                ->row('name'),
            'created_at' =>  date('Y-m-d H:i:s')
        );
        // Hanya simpan ke database jika tidak ada file yang melebihi 3 MB
        if ($valid) {
            $reimbust_id = $this->M_sml_reimbust->save($data1);
        }

        $data2 = [];
        for ($i = 1; $i <= count($pemakaian); $i++) {
            $kwitansi = null;

            // Handle upload file untuk 'kwitansi'
            if (!empty($_FILES['kwitansi']['name'][$i])) {
                $_FILES['file']['name'] = $_FILES['kwitansi']['name'][$i];
                $_FILES['file']['type'] = $_FILES['kwitansi']['type'][$i];
                $_FILES['file']['tmp_name'] = $_FILES['kwitansi']['tmp_name'][$i];
                $_FILES['file']['error'] = $_FILES['kwitansi']['error'][$i];
                $_FILES['file']['size'] = $_FILES['kwitansi']['size'][$i];

                $config['upload_path'] = './assets/backend/document/reimbust/kwitansi_sml';
                $config['allowed_types'] = 'jpeg|jpg|png';
                $config['max_size'] = 3072; // Batasan ukuran file dalam kilobytes (3 MB)
                $config['encrypt_name'] = TRUE;

                $this->upload->initialize($config);

                if ($this->upload->do_upload('file')) {
                    $kwitansi = $this->upload->data('file_name');
                } else {
                    $this->db->trans_rollback();
                    echo json_encode(array("status" => FALSE, "error" => $this->upload->display_errors()));
                    return;
                }
            }

            $kodeDeklarasiDipakai = strtoupper(trim($deklarasi[$i] ?? ''));
            if (isset($deklarasi_map[$kodeDeklarasiDipakai])) {
                $kodeDeklarasiDipakai = $deklarasi_map[$kodeDeklarasiDipakai];
            }

            $data2[] = [
                'reimbust_id' => $reimbust_id,
                'pemakaian' => $pemakaian[$i],
                'tgl_nota' => !empty($tgl_nota[$i]) ? date('Y-m-d', strtotime($tgl_nota[$i])) : date('Y-m-d'),
                'jumlah' => $jumlahClean[$i],
                'kwitansi' => $kwitansi,
                'deklarasi' => $kodeDeklarasiDipakai
            ];

            // Update data deklarasi yang di tampilkan di modal, jika gambar di submit maka is active menjadi 0
            if ($kodeDeklarasiDipakai) {
                $this->db->set('is_active', 0);
                $this->db->where('kode_deklarasi', $kodeDeklarasiDipakai);
                $this->db->update('sml_deklarasi');
            }
        }
        $this->db->set('is_active', 0);
        $this->db->where('kode_prepayment', $this->input->post('kode_prepayment'));
        $this->db->update('sml_prepayment');

        $this->M_sml_reimbust->save_detail($data2);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode(array("status" => FALSE, "error" => "Gagal menyimpan data. Silakan coba lagi."));
        } else {
            $this->db->trans_commit();
            echo json_encode(array("status" => TRUE));
        }
    }

    /**
     * Generate kode deklarasi baru untuk workflow SML dengan penanganan konflik.
     */
    private function generate_new_sml_deklarasi_code($date, &$used_codes = [])
    {
        $tanggal = date('Y-m-d', strtotime($date));
        if (!$tanggal) {
            $tanggal = date('Y-m-d');
        }

        $year = date('y', strtotime($tanggal));
        $month = date('m', strtotime($tanggal));
        $prefix = 'D' . $year . $month;

        // Dapatkan kode terakhir di DB untuk periode tersebut
        $maxKodeRow = $this->db->select('MAX(kode_deklarasi) AS maxKode')
            ->from('sml_deklarasi')
            ->like('kode_deklarasi', $prefix, 'after')
            ->get()
            ->row();

        $maxKode = $maxKodeRow->maxKode ?? null;
        $lastOrdinal = 0;
        if ($maxKode && strlen($maxKode) >= 8) {
            $lastOrdinal = (int) substr($maxKode, 5);
        }

        $nextOrdinal = $lastOrdinal + 1;

        while (true) {
            $candidate = $prefix . str_pad($nextOrdinal, 3, '0', STR_PAD_LEFT);

            if (in_array($candidate, $used_codes)) {
                $nextOrdinal++;
                continue;
            }

            $exists = $this->db->get_where('sml_deklarasi', ['kode_deklarasi' => $candidate])->row();
            if ($exists) {
                $nextOrdinal++;
                continue;
            }

            $used_codes[] = $candidate;
            return $candidate;
        }
    }


    public function update()
    {
        $this->load->library('upload');

        // CHECK APAKAH MENGINPUT YANG SUDAH ADA ATAU YANG BARU (REKENING)
        if (!empty($_POST['nama_rek'])) {
            $no_rek = $this->input->post('nama_rek') . "-" . $this->input->post('nama_bank') . "-" . $this->input->post('nomor_rekening');
        } else {
            $no_rek = $this->input->post('rekening');
        }

        $data = array(
            'sifat_pelaporan' => $this->input->post('sifat_pelaporan'),
            'tgl_pengajuan' => date('Y-m-d', strtotime($this->input->post('tgl_pengajuan'))),
            'kode_reimbust' => $this->input->post('kode_reimbust'),
            'tujuan' => $this->input->post('tujuan'),
            'jumlah_prepayment' => $this->input->post('jumlah_prepayment'),
            'no_rek' => $no_rek,
            'kode_prepayment' => $this->input->post('kode_prepayment'),
            'app_status' => 'waiting',
            'app_date' => null,
            'app_keterangan' => null,
            'app2_status' => 'waiting',
            'app2_date' => null,
            'app2_keterangan' => null,
            'app4_status' => 'waiting',
            'app4_date' => null,
            'app4_keterangan' => null,
            'status' => 'on-process'
        );

        $this->db->where('id', $this->input->post('id'));

        $detail_id = $this->input->post('detail_id');
        $reimbust_id = $this->input->post('id');
        $pemakaian = $this->input->post('pemakaian');
        $jumlah = $this->input->post('jumlah');

        // Bersihkan input untuk hanya mengambil angka
        $jumlahClean = preg_replace('/\D/', '', $jumlah);
        $tgl_nota = $this->input->post('tgl_nota');
        $kwitansi_image = $this->input->post('kwitansi_image');
        $deklarasi = $this->input->post('deklarasi');
        $deklarasi_old = $this->input->post('deklarasi_old');
        // in case update also received deklarasi_data (new declarations)
        $deklarasiDataJson = $this->input->post('deklarasi_data');
        if ($deklarasiDataJson) {
            $deklarasiData = json_decode($deklarasiDataJson, true);
            if (is_array($deklarasiData)) {
                foreach ($deklarasiData as $d) {
                    if (!empty($d['kode'])) {
                        $exists = $this->db->get_where('sml_deklarasi', ['kode_deklarasi' => $d['kode']])->row();
                        if (!$exists) {
                            // gather approval names same as before
                            $id_menu2 = $this->db->select('id_menu')
                                ->where('link', 'sml_datadeklarasi')
                                ->get('tbl_submenu')
                                ->row();
                            $confirm2 = $this->db->select('app_id, app2_id, app4_id')
                                ->from('tbl_approval')
                                ->where('id_menu', $id_menu2->id_menu)
                                ->get()->row();
                            $appName2 = '';
                            $app2Name2 = '';
                            $app4Name2 = '';
                            if (!empty($confirm2)) {
                                $appName2 = $this->db->select('name')->from('tbl_data_user')->where('id_user', $confirm2->app_id)->get()->row('name');
                                $app2Name2 = $this->db->select('name')->from('tbl_data_user')->where('id_user', $confirm2->app2_id)->get()->row('name');
                                $app4Name2 = $this->db->select('name')->from('tbl_data_user')->where('id_user', $confirm2->app4_id)->get()->row('name');
                            }
                            $userId2 = $this->session->userdata('id_user');
                            $jabatan2 = $this->db->select('jabatan')->from('tbl_data_user')->where('id_user', $userId2)->get()->row('jabatan');

                            $this->db->insert('sml_deklarasi', [
                                'kode_deklarasi' => $d['kode'],
                                'tgl_deklarasi' => date('Y-m-d', strtotime($d['tgl'])),
                                'id_pengaju' => $userId2,
                                'jabatan' => $jabatan2,
                                'nama_dibayar' => $d['nama'],
                                'tujuan' => $d['tujuan'],
                                'sebesar' => preg_replace('/\D/', '', $d['sebesar']),
                                'app_name' => $appName2,
                                'app2_name' => $app2Name2,
                                'app4_name' => $app4Name2,
                                'is_active' => 1,
                                'created_at' => date('Y-m-d H:i:s')
                            ]);
                        }
                    }
                }
            }
        }

        if ($this->db->update('sml_reimbust', $data)) {
            // 1. Hapus Baris yang Telah Dihapus
            $deletedRows = json_decode($this->input->post('deleted_rows'), true);
            if (!empty($deletedRows)) {
                foreach ($deletedRows as $id2) {
                    $reimbust_detail = $this->db->get_where('sml_reimbust_detail', ['id' => $id2])->row_array();

                    if ($reimbust_detail) {
                        $old_image = $reimbust_detail['kwitansi'];
                        if ($old_image != 'default.jpg') {
                            @unlink(FCPATH . './assets/backend/document/reimbust/kwitansi_sml/' . $old_image);
                        }

                        $this->db->where('id', $id2);
                        $this->db->delete('sml_reimbust_detail');

                        $kode_deklarasi = $reimbust_detail['deklarasi'];
                        $this->db->update('sml_deklarasi', ['is_active' => 1], ['kode_deklarasi' => $kode_deklarasi]);
                    }
                }
            }

            // 2. Replace Data Lama dengan yang Baru
            foreach ($pemakaian as $i => $p) {
                $kwitansi = ''; // Inisialisasi variabel kwitansi

                // Cek apakah file kwitansi untuk indeks $i ada
                if (isset($_FILES['kwitansi']['name'][$i]) && !empty($_FILES['kwitansi']['name'][$i])) {
                    $_FILES['file']['name'] = $_FILES['kwitansi']['name'][$i];
                    $_FILES['file']['type'] = $_FILES['kwitansi']['type'][$i];
                    $_FILES['file']['tmp_name'] = $_FILES['kwitansi']['tmp_name'][$i];
                    $_FILES['file']['error'] = $_FILES['kwitansi']['error'][$i];
                    $_FILES['file']['size'] = $_FILES['kwitansi']['size'][$i];

                    if ($_FILES['file']['error'] == UPLOAD_ERR_INI_SIZE || $_FILES['file']['size'] > 3072 * 1024) {
                        echo json_encode(array("status" => FALSE, "error" => "Ukuran file tidak boleh melebihi dari 3 MB."));
                        return;
                    }

                    $config['upload_path'] = './assets/backend/document/reimbust/kwitansi_sml/';
                    $config['allowed_types'] = 'jpeg|jpg|png';
                    $config['max_size'] = 3072;
                    $config['encrypt_name'] = TRUE;

                    $this->upload->initialize($config);

                    if ($this->upload->do_upload('file')) {
                        $id = !empty($detail_id[$i]) ? $detail_id[$i] : NULL;

                        $reimbust_detail = $this->db->get_where('sml_reimbust_detail', ['id' => $id])->row_array();

                        if ($reimbust_detail) {
                            $old_image = $reimbust_detail['kwitansi'];

                            if ($old_image && $old_image != 'default.jpg') {
                                @unlink(FCPATH . './assets/backend/document/reimbust/kwitansi_sml/' . $old_image);
                            }
                        }
                        $kwitansi = $this->upload->data('file_name');
                    } else {
                        echo json_encode(array("status" => FALSE, "error" => $this->upload->display_errors()));
                        return;
                    }
                }

                $id = !empty($detail_id[$i]) ? $detail_id[$i] : NULL;

                $newDeklarasi = '';
                if (is_array($deklarasi) && array_key_exists($i, $deklarasi)) {
                    $newDeklarasi = trim((string)$deklarasi[$i]);
                }

                $oldDeklarasi = '';
                if (is_array($deklarasi_old) && array_key_exists($i, $deklarasi_old)) {
                    $oldDeklarasi = trim((string)$deklarasi_old[$i]);
                }

                // Pastikan kode deklarasi tidak berubah/hilang saat edit.
                // Jika user tidak memilih ulang deklarasi, gunakan nilai lama (atau yang tersimpan di DB).
                if ($newDeklarasi === '' || $newDeklarasi === '-' || strtolower($newDeklarasi) === 'null') {
                    $newDeklarasi = $oldDeklarasi;
                }
                if (($newDeklarasi === '' || $newDeklarasi === '-' || strtolower($newDeklarasi) === 'null') && !empty($id)) {
                    $newDeklarasi = (string)$this->db->select('deklarasi')
                        ->from('sml_reimbust_detail')
                        ->where('id', $id)
                        ->get()
                        ->row('deklarasi');
                    $newDeklarasi = trim((string)$newDeklarasi);
                }

                $data2 = array(
                    'id' => $id,
                    'reimbust_id' => $reimbust_id,
                    'tgl_nota' => !empty($tgl_nota[$i]) ? date('Y-m-d', strtotime($tgl_nota[$i])) : date('Y-m-d'),
                    'pemakaian' => $pemakaian[$i],
                    'jumlah' => $jumlahClean[$i],
                    'kwitansi' => !empty($kwitansi) ? $kwitansi : (isset($kwitansi_image[$i]) ? $kwitansi_image[$i] : ''),
                );

                if ($newDeklarasi !== '' && $newDeklarasi !== '-' && strtolower($newDeklarasi) !== 'null') {
                    $data2['deklarasi'] = $newDeklarasi;
                }

                // Mengubah data prepayment is_active menjadi 0 pada data prepayment terbaru, jika kode_prepayment ada
                $kode_prepayment = $this->input->post('kode_prepayment');
                if (!empty($kode_prepayment)) {
                    $this->db->update('sml_prepayment', ['is_active' => 0], ['kode_prepayment' => $kode_prepayment]);
                }

                // Mengubah data prepayment is_active menjadi 1 pada data prepayment terlama, jika kode_prepayment_old ada
                $kode_prepayment_old = $this->input->post('kode_prepayment_old');
                if ($kode_prepayment != $kode_prepayment_old && !empty($kode_prepayment_old)) {
                    $this->db->update('sml_prepayment', ['is_active' => 1], ['kode_prepayment' => $kode_prepayment_old]);
                }

                // Replace data di sml_reimbust_detail
                $this->db->replace('sml_reimbust_detail', $data2);

                // mengubah is_active deklarasi awal menjadi 1, dan deklarasi baru menjadi 0 (hanya jika berubah)
                if ($oldDeklarasi !== '' && $newDeklarasi !== '' && $oldDeklarasi !== $newDeklarasi) {
                    $this->db->update('sml_deklarasi', ['is_active' => 1], ['kode_deklarasi' => $oldDeklarasi]);
                    $this->db->update('sml_deklarasi', ['is_active' => 0], ['kode_deklarasi' => $newDeklarasi]);
                } elseif ($oldDeklarasi === '' && $newDeklarasi !== '') {
                    $this->db->update('sml_deklarasi', ['is_active' => 0], ['kode_deklarasi' => $newDeklarasi]);
                }
            }
        }
        echo json_encode(array("status" => TRUE));
    }

    function delete($id)
    {
        $this->M_sml_reimbust->delete($id);
        echo json_encode(array("status" => TRUE));
    }

    private function sync_deklarasi_approval_by_reimbust($reimbustId, array $deklarasiUpdate)
    {
        $rows = $this->db->select('deklarasi')
            ->from('sml_reimbust_detail')
            ->where('reimbust_id', $reimbustId)
            ->get()
            ->result_array();

        if (empty($rows)) {
            return;
        }

        $kodeDeklarasi = array();
        foreach ($rows as $row) {
            $kode = isset($row['deklarasi']) ? trim((string)$row['deklarasi']) : '';
            if ($kode === '' || $kode === '-' || strtolower($kode) === 'null') {
                continue;
            }
            $kodeDeklarasi[] = $kode;
        }

        $kodeDeklarasi = array_values(array_unique($kodeDeklarasi));
        if (empty($kodeDeklarasi)) {
            return;
        }

        $this->db->where_in('kode_deklarasi', $kodeDeklarasi);
        $this->db->update('sml_deklarasi', $deklarasiUpdate);
    }

    //APPROVE DATA
    public function approve3()
    {
        $app4Status = $this->input->post('app4_status');
        $data = array(
            'app4_keterangan' => $this->input->post('app4_keterangan'),
            'app4_status' => $app4Status,
            'app4_date' => date('Y-m-d H:i:s'),
        );

        // UPDATE STATUS DEKLARASI
        if ($app4Status === 'revised') {
            $data['status'] = 'revised';
        } elseif ($app4Status === 'approved') {
            $data['status'] = 'on-process';
        } elseif ($app4Status === 'rejected') {
            $data['status'] = 'rejected';
        }

        //UPDATE APPROVAL PERTAMA
        $this->db->where('id', $this->input->post('hidden_id'));
        $this->db->update('sml_reimbust', $data);

        // Sinkronisasi approval deklarasi yang dipilih pada detail reimbust
        $deklarasiUpdate = array(
            'app4_keterangan' => $this->input->post('app4_keterangan'),
            'app4_status' => $app4Status,
            'app4_date' => date('Y-m-d H:i:s'),
        );
        if ($app4Status === 'revised') {
            $deklarasiUpdate['status'] = 'revised';
        } elseif ($app4Status === 'approved') {
            $deklarasiUpdate['status'] = 'on-process';
        } elseif ($app4Status === 'rejected') {
            $deklarasiUpdate['status'] = 'rejected';
        }
        $this->sync_deklarasi_approval_by_reimbust($this->input->post('hidden_id'), $deklarasiUpdate);

        echo json_encode(array("status" => TRUE));
    }

    public function approve()
    {
        $appStatus = $this->input->post('app_status');
        $data = array(
            'app_keterangan' => $this->input->post('app_keterangan'),
            'app_status' => $appStatus,
            'app_date' => date('Y-m-d H:i:s'),
        );

        // UPDATE STATUS DEKLARASI
        if ($appStatus === 'revised') {
            $data['status'] = 'revised';
        } elseif ($appStatus === 'approved') {
            $data['status'] = 'on-process';
        } elseif ($appStatus === 'rejected') {
            $data['status'] = 'rejected';
        }

        //UPDATE APPROVAL PERTAMA
        $this->db->where('id', $this->input->post('hidden_id'));
        $this->db->update('sml_reimbust', $data);

        // Sinkronisasi approval deklarasi yang dipilih pada detail reimbust
        $deklarasiUpdate = array(
            'app_keterangan' => $this->input->post('app_keterangan'),
            'app_status' => $appStatus,
            'app_date' => date('Y-m-d H:i:s'),
        );
        if ($appStatus === 'revised') {
            $deklarasiUpdate['status'] = 'revised';
        } elseif ($appStatus === 'approved') {
            $deklarasiUpdate['status'] = 'on-process';
        } elseif ($appStatus === 'rejected') {
            $deklarasiUpdate['status'] = 'rejected';
        }
        $this->sync_deklarasi_approval_by_reimbust($this->input->post('hidden_id'), $deklarasiUpdate);

        echo json_encode(array("status" => TRUE));
    }

    function approve2()
    {
        $app2Status = $this->input->post('app2_status');
        $data = array(
            'app2_keterangan' => $this->input->post('app2_keterangan'), 
            'app2_status' => $app2Status,
            'app2_date' => date('Y-m-d H:i:s'),
        );

        // UPDATE STATUS DEKLARASI
        if ($app2Status === 'revised') {
            $data['status'] = 'revised';
        } elseif ($app2Status === 'approved') {
            $data['status'] = 'approved';
        } elseif ($app2Status === 'rejected') {
            $data['status'] = 'rejected';
        }

        // UPDATE APPROVAL 2
        $this->db->where('id', $this->input->post('hidden_id'));
        $this->db->update('sml_reimbust', $data);

        // Sinkronisasi approval deklarasi yang dipilih pada detail reimbust
        $deklarasiUpdate = array(
            'app2_keterangan' => $this->input->post('app2_keterangan'),
            'app2_status' => $app2Status,
            'app2_date' => date('Y-m-d H:i:s'),
        );
        if ($app2Status === 'revised') {
            $deklarasiUpdate['status'] = 'revised';
        } elseif ($app2Status === 'approved') {
            $deklarasiUpdate['status'] = 'approved';
        } elseif ($app2Status === 'rejected') {
            $deklarasiUpdate['status'] = 'rejected';
        }
        $this->sync_deklarasi_approval_by_reimbust($this->input->post('hidden_id'), $deklarasiUpdate);

        echo json_encode(array("status" => TRUE));
    }

    function payment()
    {
        // UPDATE APPROVAL 2
        $this->db->where('id', $this->input->post('id'));
        $this->db->update('sml_reimbust', ['payment_status' => $this->input->post('payment_status')]);

        echo json_encode(array("status" => TRUE));
    }
}
