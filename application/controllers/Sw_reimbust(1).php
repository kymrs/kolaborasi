<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sw_reimbust extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_sw_reimbust');
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
        $data['title'] = "backend/sw_reimbust/sw_reimbust_list";
        $data['titleview'] = "Data Reimbust";
        $name = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $this->session->userdata('id_user'))
            ->get()
            ->row('name');
        $data['approval'] = $this->db->select('COUNT(*) as total_approval')
            ->from('tbl_reimbust')
            ->where('app_name', $name)
            ->or_where('app2_name', $name)
            // ->or_where('app4_name', $name)
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
        $list = $this->M_sw_reimbust->get_datatables();
        $data = array();
        $no = $_POST['start'];

        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        $read = $akses->view_level;
        $edit = $akses->edit_level;
        $delete = $akses->delete_level;
        $print = $akses->print_level;

        foreach ($list as $field) {

            // MENENTUKAN ACTION APA YANG AKAN DITAMPILKAN DI LIST DATA TABLES
            $action_read = ($read == 'Y') ? '<a href="sw_reimbust/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>&nbsp;' : '';
            $action_edit = ($edit == 'Y') ? '<a href="sw_reimbust/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;' : '';
            $action_delete = ($delete == 'Y') ? '<a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;' : '';
            $action_print = ($print == 'Y') ? '<a class="btn btn-success btn-circle btn-sm" target="_blank" href="sw_reimbust/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>' : '';

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
            } elseif ($field->app2_status == 'waiting' && $field->status == 'on-process') {
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
            "recordsTotal" => $this->M_sw_reimbust->count_all(),
            "recordsFiltered" => $this->M_sw_reimbust->count_filtered(),
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
        $list = $this->M_sw_reimbust->get_datatables2();
        $data = array();
        $no = $_POST['start'];

        //LOOPING DATATABLES
        foreach ($list as $field) {

            // MENENTUKAN ACTION APA YANG AKAN DITAMPILKAN DI LIST DATA TABLES
            if ($field->app_name == $fullname) {
                $action = '<a href="sw_datadeklarasi/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                                <a class="btn btn-success btn-circle btn-sm" href="sw_datadeklarasi/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } elseif ($field->app2_name == $fullname) {
                $action = '<a href="sw_datadeklarasi/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>     
                                <a class="btn btn-success btn-circle btn-sm" href="sw_datadeklarasi/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } elseif (in_array($field->status, ['rejected', 'approved'])) {
                $action = '<a href="sw_datadeklarasi/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                <a class="btn btn-success btn-circle btn-sm" href="sw_datadeklarasi/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } elseif ($field->app_status == 'revised' || $field->app2_status == 'revised') {
                $action = '<a href="sw_datadeklarasi/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                    <a href="sw_datadeklarasi/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
                    <a class="btn btn-success btn-circle btn-sm" href="sw_datadeklarasi/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } elseif ($field->app_status == 'approved') {
                $action = '<a href="sw_datadeklarasi/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                            <a class="btn btn-success btn-circle btn-sm" href="sw_datadeklarasi/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } else {
                $action = '<a href="sw_datadeklarasi/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                        <a href="sw_datadeklarasi/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
			            <a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>
                        <a class="btn btn-success btn-circle btn-sm" href="sw_datadeklarasi/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
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
            "recordsTotal" => $this->M_sw_reimbust->count_all2(),
            "recordsFiltered" => $this->M_sw_reimbust->count_filtered2(),
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
        $list = $this->M_sw_reimbust->get_datatables3();
        $data = array();
        $no = $_POST['start'];

        //LOOPING DATATABLES
        foreach ($list as $field) {

            // MENENTUKAN ACTION APA YANG AKAN DITAMPILKAN DI LIST DATA TABLES
            if ($field->app_name == $fullname) {
                $action = '<a href="sw_prepayment/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                                <a class="btn btn-success btn-circle btn-sm" href="sw_prepayment/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } elseif ($field->app2_name == $fullname) {
                $action = '<a href="sw_prepayment/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>     
                                <a class="btn btn-success btn-circle btn-sm" href="sw_prepayment/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } elseif (in_array($field->status, ['rejected', 'approved'])) {
                $action = '<a href="sw_prepayment/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                <a class="btn btn-success btn-circle btn-sm" href="sw_prepayment/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } elseif ($field->app_status == 'revised' || $field->app2_status == 'revised') {
                $action = '<a href="sw_prepayment/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                    <a href="sw_prepayment/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
                    <a class="btn btn-success btn-circle btn-sm" href="sw_prepayment/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } elseif ($field->app_status == 'approved') {
                $action = '<a href="sw_prepayment/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                            <a class="btn btn-success btn-circle btn-sm" href="sw_prepayment/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } else {
                $action = '<a href="sw_prepayment/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                        <a href="sw_prepayment/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
                        <a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>
                        <a class="btn btn-success btn-circle btn-sm" href="sw_prepayment/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
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
            "recordsTotal" => $this->M_sw_reimbust->count_all3(),
            "recordsFiltered" => $this->M_sw_reimbust->count_filtered3(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    function read_form($id)
    {
        $data['aksi'] = 'read';
        $data['user'] = $this->M_sw_reimbust->get_by_id($id);
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
        $data['title'] = 'backend/sw_reimbust/sw_reimbust_read';
        $this->db->select('kwitansi');
        $this->db->where('reimbust_id', $id);
        $data['kwitansi'] = $this->db->get('tbl_reimbust_detail')->result_array();
        $this->load->view('backend/home', $data);
    }

    public function add_form()
    {
        // INISIASI
        $id_user = $this->session->userdata('id_user');
        $data['id_user'] = $id_user;
        $data['id_pembuat'] = 0;

        $data['id'] = 0;
        $data['aksi'] = 'add';
        $data['rek_options'] = $this->M_sw_reimbust->options($id_user)->result_array();
        $data['title_view'] = "Reimbust Form";
        $data['title'] = 'backend/sw_reimbust/sw_reimbust_form';
        $this->load->view('backend/home', $data);
    }

    public function generate_pdf($id)
    {
        // Load FPDF library
        $this->load->library('Fpdf_generate');

        // Load data from database based on $id
        $data['master'] = $this->M_sw_reimbust->get_by_id($id);
        $data['transaksi'] = $this->M_sw_reimbust->get_by_id_detail($id);
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
        $pdf->Image(base_url('') . '/assets/backend/img/sebelaswarna.png', 10, 5, 45, 30);

        // Set font
        $pdf->AddFont('Poppins-Regular', '', 'Poppins-Regular.php');
        $pdf->AddFont('Poppins-Bold', '', 'Poppins-Bold.php');

        $pdf->SetFont('Poppins-Bold', '', 16);

        // Teks yang ingin ditampilkan
        $text1 = 'FORM PELAPORAN / REIMBUST';
        $text2 = 'SEBELASWARNA';

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
        Row($pdf, 10, array('JABATAN', ':', $data['master']->jabatan), $widths, false);
        $pdf->Ln(-3);
        Row($pdf, 10, array('DEPARTEMEN', ':', $data['master']->departemen), $widths, false);
        $pdf->Ln(-3);
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
        // $pdf->Cell(47.3, 8.5, 'Captain', 1, 0, 'C');
        $pdf->Cell(47.3, 8.5, 'Mengetahui', 1, 0, 'C');
        $pdf->Cell(47.3, 8.5, 'Menyetujui', 1, 1, 'C');

        // Set font normal untuk konten tabel
        $pdf->SetFont('Poppins-Regular', '', 10);

        // Baris pemisah
        $pdf->Cell(47.3, 5, '', 'LR', 0, 'C');
        // $pdf->Cell(47.3, 5, '', 0, 0, 'C');
        $pdf->Cell(47.3, 5, '', 'L', 0, 'C');
        $pdf->Cell(47.3, 5, '', 'LR', 1, 'C');

        // Baris pertama (Status)
        $pdf->Cell(47.3, 5, 'CREATED', 'LR', 0, 'C');
        // $pdf->Cell(47.3, 5, strtoupper($data['master']->app4_status), 'R', 0, 'C');
        $pdf->Cell(47.3, 5, strtoupper($data['master']->app_status), 0, 0, 'C');
        $pdf->Cell(47.3, 5, strtoupper($data['master']->app2_status), 'LR', 1, 'C');

        // Baris kedua (Tanggal)
        $pdf->Cell(47.3, 5, $data['master']->created_at, 'LR', 0, 'C');
        // $pdf->Cell(47.3, 5, $data['master']->app4_date, 'R', 0, 'C');
        $pdf->Cell(47.3, 5, $data['master']->app_date, 0, 0, 'C');
        $pdf->Cell(47.3, 5, $data['master']->app2_date, 'LR', 1, 'C');

        // Baris pemisah
        $pdf->Cell(47.3, 5, '', 'LR', 0, 'C');
        // $pdf->Cell(47.3, 5, '', 0, 0, 'C');
        $pdf->Cell(47.3, 5, '', 'L', 0, 'C');
        $pdf->Cell(47.3, 5, '', 'LR', 1, 'C');

        // Jarak kosong untuk pemisah
        $pdf->Ln(0);

        // Baris ketiga (Nama pengguna)
        $pdf->Cell(47.3, 8.5, $data['user'], 1, 0, 'C');
        // $pdf->Cell(47.3, 8.5, $data['master']->app4_name, 1, 0, 'C');
        $pdf->Cell(47.3, 8.5, $data['master']->app_name, 1, 0, 'C');
        $pdf->Cell(47.3, 8.5, $data['master']->app2_name, 1, 1, 'C');


        // Output the PDF
        $pdf->Output('I', 'sw_reimbust - ' . $data['master']->kode_reimbust . '.pdf');
    }

    // MEREGENERATE KODE REIMBUST
    public function generate_kode()
    {
        $date = $this->input->post('date');
        $kode = $this->M_sw_reimbust->max_kode($date)->row();
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
        $data['id_pembuat'] = $this->M_sw_reimbust->get_by_id($id)->id_user;

        $data['id'] = $id;
        $data['aksi'] = 'update';
        $data['title_view'] = "Edit Reimbust";
        $data['rek_options'] = $this->M_sw_reimbust->options($data['id_user'])->result_array();
        $data['title'] = 'backend/sw_reimbust/sw_reimbust_form';
        $this->load->view('backend/home', $data);
    }

    function edit_data($id)
    {
        $data['master'] = $this->M_sw_reimbust->get_by_id($id);
        $data['transaksi'] = $this->M_sw_reimbust->get_by_id_detail($id);
        $data['nama'] = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $data['master']->id_user)
            ->get()->row('name');
        echo json_encode($data);
    }

    function read_detail($id)
    {
        $data = $this->M_sw_reimbust->get_by_id_detail($id);
        echo json_encode($data);
    }

    public function detail_deklarasi()
    {
        if ($this->input->is_ajax_request()) {
            $deklarasi = $this->input->post('deklarasi');

            // Mengambil data deklarasi dari database
            $deklarasiRecord = $this->db->get_where('tbl_deklarasi', ['kode_deklarasi' => $deklarasi])->row_array();

            // Debug log
            log_message('debug', 'Deklarasi: ' . print_r($deklarasi, true));
            log_message('debug', 'Deklarasi Record: ' . print_r($deklarasiRecord, true));

            if ($deklarasiRecord) {
                // Mengambil ID dari record yang ditemukan
                $deklarasiId = $deklarasiRecord['id']; // Pastikan 'id' adalah nama kolom yang sesuai
                $redirect_url = site_url('sw_datadeklarasi/read_form/' . $deklarasiId);

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
        $kode = $this->M_sw_reimbust->max_kode($date)->row();
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
                    echo json_encode(array("status" => FALSE, "error" => "Tipe file tidak diizinkan untuk file ke-$i. Hanya file JPG dan PNG yang diperbolehkan."));
                    exit();
                    $valid = false;  // Tandai bahwa ada file yang tidak valid
                    break; // Keluar dari perulangan jika ada file yang tidak valid
                }

                // Cek ukuran file
                if ($_FILES['kwitansi']['size'][$i] > 3072 * 1024) { // 3 MB in KB
                    echo json_encode(array("status" => FALSE, "error" => "Ukuran file tidak boleh melebihi dari 3 MB untuk file ke-$i."));
                    exit();
                    $valid = false;  // Tandai bahwa ada file yang tidak valid
                    break; // Keluar dari perulangan jika ada file yang terlalu besar
                }
            }
        }

        // MENCARI SIAPA YANG AKAN MELAKUKAN APPROVAL PERMINTAAN
        $id_menu = $this->db->select('id_menu')
            ->where('link', $this->router->fetch_class())
            ->get('tbl_submenu')
            ->row();

        $confirm = $this->db->select('app_id, app2_id')->from('tbl_approval')->where('id_menu', $id_menu->id_menu)->get()->row();
        if (!empty($confirm) && isset($confirm->app_id, $confirm->app2_id)) {
            $app = $confirm;
        } else {
            echo json_encode(array("status" => FALSE, "error" => "Approval Belum Ditentukan, Mohon untuk menghubungi admin."));
            exit();
            $valid = false;
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
            // 'app4_name' => $this->db->select('name')
            //     ->from('tbl_data_user')
            //     ->where('id_user', $app->app4_id)
            //     ->get()
            //     ->row('name'),
            'created_at' =>  date('Y-m-d H:i:s')
        );
        // Hanya simpan ke database jika tidak ada file yang melebihi 3 MB
        if ($valid) {
            $reimbust_id = $this->M_sw_reimbust->save($data1);
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

                $config['upload_path'] = './assets/backend/document/reimbust/kwitansi_sw/';
                $config['allowed_types'] = 'jpeg|jpg|png';
                $config['max_size'] = 3072; // Batasan ukuran file dalam kilobytes (3 MB)
                $config['encrypt_name'] = TRUE;

                $this->upload->initialize($config);

                if ($this->upload->do_upload('file')) {
                    $kwitansi = $this->upload->data('file_name');
                } else {
                    echo json_encode(array("status" => FALSE, "error" => $this->upload->display_errors()));
                    return;
                }
            }

            $data2[] = [
                'reimbust_id' => $reimbust_id,
                'pemakaian' => $pemakaian[$i],
                'tgl_nota' => !empty($tgl_nota[$i]) ? date('Y-m-d', strtotime($tgl_nota[$i])) : date('Y-m-d'),
                'jumlah' => $jumlahClean[$i],
                'kwitansi' => $kwitansi,
                'deklarasi' => $deklarasi[$i]
            ];

            // Update data deklarasi yang di tampilkan di modal, jika gambar di submit maka is active akan menjadi 0
            $this->db->set('is_active', 0);
            $this->db->where('kode_deklarasi', $deklarasi[$i]);
            $this->db->update('tbl_deklarasi');
        }
        $this->db->set('is_active', 0);
        $this->db->where('kode_prepayment', $this->input->post('kode_prepayment'));
        $this->db->update('tbl_prepayment');

        $this->M_sw_reimbust->save_detail($data2);


        echo json_encode(array("status" => TRUE));
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
            // 'app4_status' => 'waiting',
            // 'app4_date' => null,
            // 'app4_keterangan' => null,
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

        if ($this->db->update('tbl_reimbust', $data)) {
            // 1. Hapus Baris yang Telah Dihapus
            $deletedRows = json_decode($this->input->post('deleted_rows'), true);
            if (!empty($deletedRows)) {
                foreach ($deletedRows as $id2) {
                    $reimbust_detail = $this->db->get_where('tbl_reimbust_detail', ['id' => $id2])->row_array();

                    if ($reimbust_detail) {
                        $old_image = $reimbust_detail['kwitansi'];
                        if ($old_image != 'default.jpg') {
                            @unlink(FCPATH . './assets/backend/document/reimbust/kwitansi_sw/' . $old_image);
                        }

                        $this->db->where('id', $id2);
                        $this->db->delete('tbl_reimbust_detail');

                        $kode_deklarasi = $reimbust_detail['deklarasi'];
                        $this->db->update('tbl_deklarasi', ['is_active' => 1], ['kode_deklarasi' => $kode_deklarasi]);
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

                    $config['upload_path'] = './assets/backend/document/reimbust/kwitansi_sw/';
                    $config['allowed_types'] = 'jpeg|jpg|png';
                    $config['max_size'] = 3072;
                    $config['encrypt_name'] = TRUE;

                    $this->upload->initialize($config);

                    if ($this->upload->do_upload('file')) {
                        $id = !empty($detail_id[$i]) ? $detail_id[$i] : NULL;

                        $reimbust_detail = $this->db->get_where('tbl_reimbust_detail', ['id' => $id])->row_array();

                        if ($reimbust_detail) {
                            $old_image = $reimbust_detail['kwitansi'];

                            if ($old_image && $old_image != 'default.jpg') {
                                @unlink(FCPATH . './assets/backend/document/reimbust/kwitansi_sw/' . $old_image);
                            }
                        }
                        $kwitansi = $this->upload->data('file_name');
                    } else {
                        echo json_encode(array("status" => FALSE, "error" => $this->upload->display_errors()));
                        return;
                    }
                }

                $id = !empty($detail_id[$i]) ? $detail_id[$i] : NULL;

                $data2 = array(
                    'id' => $id,
                    'reimbust_id' => $reimbust_id,
                    'tgl_nota' => !empty($tgl_nota[$i]) ? date('Y-m-d', strtotime($tgl_nota[$i])) : date('Y-m-d'),
                    'pemakaian' => $pemakaian[$i],
                    'jumlah' => $jumlahClean[$i],
                    'kwitansi' => !empty($kwitansi) ? $kwitansi : (isset($kwitansi_image[$i]) ? $kwitansi_image[$i] : ''),
                    // 'deklarasi' => $deklarasi[$i]
                );

                if (isset($deklarasi[$i])) {
                    $data2['deklarasi'] = $deklarasi[$i];
                }

                // Mengubah data prepayment is_active menjadi 0 pada data prepayment terbaru, jika kode_prepayment ada
                $kode_prepayment = $this->input->post('kode_prepayment');
                if (!empty($kode_prepayment)) {
                    $this->db->update('tbl_prepayment', ['is_active' => 0], ['kode_prepayment' => $kode_prepayment]);
                }

                // Mengubah data prepayment is_active menjadi 1 pada data prepayment terlama, jika kode_prepayment_old ada
                $kode_prepayment_old = $this->input->post('kode_prepayment_old');
                if ($kode_prepayment != $kode_prepayment_old && !empty($kode_prepayment_old)) {
                    $this->db->update('tbl_prepayment', ['is_active' => 1], ['kode_prepayment' => $kode_prepayment_old]);
                }

                // Replace data di tbl_reimbust_detail
                $this->db->replace('tbl_reimbust_detail', $data2);

                // mengubah is_active deklarasi awal menjadi 1, dan deklarasi baru menjadi 0
                if (isset($deklarasi_old[$i])) {
                    if ($deklarasi_old[$i]) {
                        $this->db->update('tbl_deklarasi', ['is_active' => 1], ['kode_deklarasi' => $deklarasi_old[$i]]);
                        $this->db->update('tbl_deklarasi', ['is_active' => 0], ['kode_deklarasi' => $deklarasi[$i]]);
                    } else {
                        $this->db->update('tbl_deklarasi', ['is_active' => 0], ['kode_deklarasi' => $deklarasi[$i]]);
                    }
                }
            }
        }
        echo json_encode(array("status" => TRUE));
    }

    function delete($id)
    {
        $this->M_sw_reimbust->delete($id);
        echo json_encode(array("status" => TRUE));
    }

    //APPROVE DATA
    public function approve3()
    {
        $data = array(
            'app4_keterangan' => $this->input->post('app4_keterangan'),
            'app4_status' => $this->input->post('app4_status'),
            'app4_date' => date('Y-m-d H:i:s'),
        );

        // UPDATE STATUS DEKLARASI
        if ($this->input->post('app4_status') === 'revised') {
            $data['status'] = 'revised';
        } elseif ($this->input->post('app4_status') === 'approved') {
            $data['status'] = 'on-process';
        } elseif ($this->input->post('app4_status') === 'rejected') {
            $data['status'] = 'rejected';
        }

        //UPDATE APPROVAL PERTAMA
        $this->db->where('id', $this->input->post('hidden_id'));
        $this->db->update('tbl_reimbust', $data);

        echo json_encode(array("status" => TRUE));
    }

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
        $this->db->update('tbl_reimbust', $data);

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
        $this->db->update('tbl_reimbust', $data);

        echo json_encode(array("status" => TRUE));
    }

    function payment()
    {
        // UPDATE APPROVAL 2
        $this->db->where('id', $this->input->post('id'));
        $this->db->update('tbl_reimbust', ['payment_status' => $this->input->post('payment_status')]);

        echo json_encode(array("status" => TRUE));
    }
}
