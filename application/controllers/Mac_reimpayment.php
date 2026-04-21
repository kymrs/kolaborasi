<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mac_reimpayment extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_mac_reimpayment');
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
        $data['title'] = "backend/mac_reimpayment/mac_reimpayment_list";
        $data['titleview'] = "Data Reimbust & Prepayment";
        $name = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $this->session->userdata('id_user'))
            ->get()
            ->row('name');
        $data['approval'] = $this->db->select('COUNT(*) as total_approval')
            ->from('mac_reimbust')
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
        $list = $this->M_mac_reimpayment->get_datatables();
        $data = array();
        $no = $_POST['start'];

        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        $read = $akses->view_level;
        $edit = $akses->edit_level;
        $delete = $akses->delete_level;
        $print = $akses->print_level;

        foreach ($list as $field) {

            // MENENTUKAN ACTION APA YANG AKAN DITAMPILKAN DI LIST DATA TABLES
            $action_read = ($read == 'Y') ? '<a href="mac_reimpayment/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>&nbsp;' : '';
            $action_edit = ($edit == 'Y') ? '<a href="mac_reimpayment/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;' : '';
            $action_delete = ($delete == 'Y') ? '<a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;' : '';
            $action_print = ($print == 'Y') ? '<a class="btn btn-success btn-circle btn-sm" target="_blank" href="mac_reimpayment/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>' : '';

            // MENENTUKAN ACTION APA YANG AKAN DITAMPILKAN DI LIST DATA TABLES
            if ($this->session->userdata('username') == 'eko') {
                $action = $action_read .  $action_edit . $action_delete . $action_print;
            } elseif ($field->id_user == $this->session->userdata('id_user') && !in_array($field->status, ['rejected', 'approved', 'revised']) && $field->app_status == "waiting") {
                $action = $action_read . $action_edit . $action_delete . $action_print;
            } elseif (($field->id_user == $this->session->userdata('id_user') || $this->session->userdata('username') == 'eko') && $field->status == 'revised') {
                $action = $action_read . $action_edit . $action_print;
            } else {
                $action = $action_read . $action_edit . $action_print;
            }

            //MENENSTUKAN SATTSU PROGRESS PENGAJUAN PERMINTAAN
            if ($field->app_status == 'approved' && $field->app2_status == 'waiting' && $field->status == 'on-process') {
                $status = $field->status . ' (' . $field->app2_name . ')';
            } elseif ($field->app_status == 'waiting' && $field->app2_status == 'waiting' && $field->status == 'on-process') {
                $status = $field->status . ' (' . $field->app_name . ')';
            } elseif ($field->app4_status == 'waiting' && $field->app2_status == 'waiting' && $field->status == 'on-process') {
                $status = $field->status . ' (' . $field->app4_name . ')';
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
            $name = $this->db->select('name')
                ->from('tbl_data_user')
                ->where('id_user', $field->id_user)
                ->get()
                ->row('name');
            $row[] = $name;
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
            $row[] = 'Rp. ' . number_format($field->jumlah_prepayment, 0, ',', '.');;
            $row[] = ucwords($status);

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_mac_reimpayment->count_all(),
            "recordsFiltered" => $this->M_mac_reimpayment->count_filtered(),
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
        $list = $this->M_mac_reimpayment->get_datatables2();
        $data = array();
        $no = $_POST['start'];

        //LOOPING DATATABLES
        foreach ($list as $field) {

            // MENENTUKAN ACTION APA YANG AKAN DITAMPILKAN DI LIST DATA TABLES
            if ($field->app_name == $fullname) {
                $action = '<a href="mac_datadeklarasi/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                                <a class="btn btn-success btn-circle btn-sm" href="mac_datadeklarasi/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } elseif ($field->app2_name == $fullname) {
                $action = '<a href="mac_datadeklarasi/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>     
                                <a class="btn btn-success btn-circle btn-sm" href="mac_datadeklarasi/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } elseif (in_array($field->status, ['rejected', 'approved'])) {@
                $action = '<a href="mac_datadeklarasi/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                <a class="btn btn-success btn-circle btn-sm" href="mac_datadeklarasi/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } elseif ($field->app_status == 'revised' || $field->app2_status == 'revised') {
                $action = '<a href="mac_datadeklarasi/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                    <a href="mac_datadeklarasi/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
                    <a class="btn btn-success btn-circle btn-sm" href="mac_datadeklarasi/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } elseif ($field->app_status == 'approved') {
                $action = '<a href="mac_datadeklarasi/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                            <a class="btn btn-success btn-circle btn-sm" href="mac_datadeklarasi/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } else {
                $action = '<a href="mac_datadeklarasi/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                        <a href="mac_datadeklarasi/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
			            <a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>
                        <a class="btn btn-success btn-circle btn-sm" href="mac_datadeklarasi/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
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
            "recordsTotal" => $this->M_mac_reimpayment->count_all2(),
            "recordsFiltered" => $this->M_mac_reimpayment->count_filtered2(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    // get list mac_prepayment
    function get_list3()
    {
        // INISIAI VARIABLE YANG DIBUTUHKAN
        $fullname = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $this->session->userdata('id_user'))
            ->get()
            ->row('name');
        $list = $this->M_mac_reimpayment->get_datatables3();
        $data = array();
        $no = $_POST['start'];

        //LOOPING DATATABLES
        foreach ($list as $field) {

            // MENENTUKAN ACTION APA YANG AKAN DITAMPILKAN DI LIST DATA TABLES
            if ($field->app_name == $fullname) {
                $action = '<a href="mac_prepayment/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                                <a class="btn btn-success btn-circle btn-sm" href="mac_prepayment/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } elseif ($field->app2_name == $fullname) {
                $action = '<a href="mac_prepayment/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>     
                                <a class="btn btn-success btn-circle btn-sm" href="mac_prepayment/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } elseif (in_array($field->status, ['rejected', 'approved'])) {
                $action = '<a href="mac_prepayment/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                <a class="btn btn-success btn-circle btn-sm" href="mac_prepayment/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } elseif ($field->app_status == 'revised' || $field->app2_status == 'revised') {
                $action = '<a href="mac_prepayment/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                    <a href="mac_prepayment/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
                    <a class="btn btn-success btn-circle btn-sm" href="mac_prepayment/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } elseif ($field->app_status == 'approved') {
                $action = '<a href="mac_prepayment/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                            <a class="btn btn-success btn-circle btn-sm" href="mac_prepayment/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } else {
                $action = '<a href="mac_prepayment/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                        <a href="mac_prepayment/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
                        <a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>
                        <a class="btn btn-success btn-circle btn-sm" href="mac_prepayment/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            }


            $formatted_nominal = number_format($field->total_nominal, 0, ',', '.');
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $action;
            $row[] = strtoupper($field->kode_prepayment);
            $name = $this->db->select('name')
                ->from('tbl_data_user')
                ->where('id_user', $field->id_user)
                ->get()
                ->row('name');
            $row[] = $name;
            $row[] = strtoupper($field->divisi);
            $row[] = strtoupper($field->jabatan);
            $row[] = date("d M Y", strtotime($field->tgl_prepayment));
            $row[] = $field->prepayment;
            $row[] = $formatted_nominal;
            // $row[] = $field->tujuan;
            $row[] = $field->status;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_mac_reimpayment->count_all3(),
            "recordsFiltered" => $this->M_mac_reimpayment->count_filtered3(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    function read_form($id)
    {
        $data['aksi'] = 'read';
        $data['user'] = $this->M_mac_reimpayment->get_by_id($id);
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
        $data['title'] = 'backend/mac_reimpayment/mac_reimpayment_read';
        $this->db->select('kwitansi');
        $this->db->where('reimbust_id', $id);
        $data['kwitansi'] = $this->db->get('mac_reimbust_detail')->result_array();
        $this->load->view('backend/home', $data);
    }

    public function generate_pdf($id)
    {
        // Load FPDF library
        $this->load->library('Fpdf_generate');

        // Load data from database based on $id
        $data['master'] = $this->M_mac_reimpayment->get_by_id($id);
        $data['transaksi'] = $this->M_mac_reimpayment->get_by_id_detail($id);
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
        $pdf->Image(base_url('') . '/assets/backend/img/mobileautocare.png', 11, 5, 45, 25);

        // Set font
        $pdf->AddFont('Poppins-Regular', '', 'Poppins-Regular.php');
        $pdf->AddFont('Poppins-Bold', '', 'Poppins-Bold.php');

        $pdf->SetFont('Poppins-Bold', '', 16);

        // Teks yang ingin ditampilkan
        $text1 = 'FORM PELAPORAN / REIMBUST';
        $text2 = 'MAC';

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

        // Add total and remaining mac_prepayment
        $pdf->SetFont('Poppins-Bold', '', 10);
        $pdf->Cell(259, 8.5, 'TOTAL PEMAKAIAN', 1, 0, 'L');
        $pdf->Cell(-1, 8.5, 'Rp. ' . number_format($totalJumlah, 0, ',', '.'), 0, 1, 'R');
        $pdf->Cell(259, 8.5, 'SISA PREPAYMENT', 1, 0, 'L');
        $pdf->Cell(-1, 8.5, 'Rp. ' . number_format($sisaPrepayment, 0, ',', '.'), 0, 1, 'R');

        $pdf->Ln(10);

        $pdf->SetFont('Poppins-Regular', '', 10);
        $pdf->Cell(50, 8.5, 'YANG MELAKUKAN', 1, 0, 'C');
        $pdf->Cell(50, 8.5, 'MENGETAHUI', 1, 0, 'C');
        $pdf->Cell(50, 8.5, 'MENYETUJUI', 1, 1, 'C');

        $pdf->Cell(50, 13.5, 'CREATED', 0, 0, 'C');

        $x = $pdf->GetX();
        $y = $pdf->GetY();

        $pdf->SetXY($x + -50, $y + 0); // Menambahkan margin horizontal dan vertikal
        $pdf->Cell(50, 18, '', 1, 0, 'C');

        // Kembali ke posisi sebelumnya untuk elemen berikutnya
        $pdf->SetXY($x + 0, $y); // Mengatur posisi untuk elemen berikutnya jika diperlukan

        // Menyimpan posisi saat ini
        $x = $pdf->GetX();
        $y = $pdf->GetY();

        // Mengatur posisi X dan Y dengan margin tambahan untuk teks tanggal
        $pdf->SetXY($x + -50, $y + 4.5); // Menambahkan margin horizontal dan vertikal

        // Menggunakan Cell() untuk mencetak teks tanggal dengan margin
        $pdf->Cell(50, 15, date('d-m-Y H:i:s', strtotime($data['master']->created_at)), 0, 0, 'C');

        // Kembali ke posisi sebelumnya untuk elemen berikutnya
        $pdf->SetXY($x + 0, $y); // Mengatur posisi untuk elemen berikutnya jika diperlukan

        // Approval 1
        $pdf->Cell(50, 13.5, strtoupper($data['master']->app_status), 0, 0, 'C');

        $x = $pdf->GetX();
        $y = $pdf->GetY();

        $pdf->SetXY($x + -50, $y + 0); // Menambahkan margin horizontal dan vertikal
        $pdf->Cell(50, 18, '', 1, 0, 'C');

        // Kembali ke posisi sebelumnya untuk elemen berikutnya
        $pdf->SetXY($x + 0, $y); // Mengatur posisi untuk elemen berikutnya jika diperlukan

        // Menyimpan posisi saat ini
        $x = $pdf->GetX();
        $y = $pdf->GetY();

        // Mengatur posisi X dan Y dengan margin tambahan untuk teks tanggal
        $pdf->SetXY($x + -50, $y + 4.5); // Menambahkan margin horizontal dan vertikal

        if ($data['master']->app_date == null) {
            $date = '';
        }
        if ($data['master']->app_date != null) {
            $date = date('d-m-Y H:i:s', strtotime($data['master']->app_date));
        }

        // Menggunakan Cell() untuk mencetak teks tanggal dengan margin
        $pdf->Cell(50, 15, $date, 0, 0, 'C');

        // Kembali ke posisi sebelumnya untuk elemen berikutnya
        $pdf->SetXY($x + 0, $y); // Mengatur posisi untuk elemen berikutnya jika diperlukan

        // Approval 2
        $pdf->Cell(50, 13.5, strtoupper($data['master']->app2_status), 0, 0, 'C');

        $x = $pdf->GetX();
        $y = $pdf->GetY();

        $pdf->SetXY($x + -50, $y + 0); // Menambahkan margin horizontal dan vertikal
        $pdf->Cell(50, 18, '', 1, 0, 'C');

        // Kembali ke posisi sebelumnya untuk elemen berikutnya
        $pdf->SetXY($x + 0, $y); // Mengatur posisi untuk elemen berikutnya jika diperlukan

        // Menyimpan posisi saat ini
        $x = $pdf->GetX();
        $y = $pdf->GetY();

        // Mengatur posisi X dan Y dengan margin tambahan untuk teks tanggal
        $pdf->SetXY($x + -50, $y + 4.5); // Menambahkan margin horizontal dan vertikal

        if ($data['master']->app2_date == null) {
            $date2 = '';
        }
        if ($data['master']->app2_date != null) {
            $date2 = date('d-m-Y H:i:s', strtotime($data['master']->app2_date));
        }

        // Menggunakan Cell() untuk mencetak teks tanggal dengan margin
        $pdf->Cell(50, 15, $date2, 0, 0, 'C');

        // Kembali ke posisi sebelumnya untuk elemen berikutnya 
        $pdf->SetXY($x + -150, $y + 18); // Mengatur posisi untuk elemen berikutnya jika diperlukan

        // Menulis elemen selanjutnya dengan ukuran baris yang lebih kecil
        $pdf->Cell(50, 8.5, $data['user'], 1, 0, 'C');
        $pdf->Cell(50, 8.5, $data['master']->app4_name, 1, 1, 'C');
        $pdf->Cell(50, 8.5, $data['master']->app_name, 1, 0, 'C');
        $pdf->Cell(50, 8.5, $data['master']->app2_name, 1, 1, 'C');


        // Output the PDF
        $pdf->Output('I', 'mac_reimbust - ' . $data['master']->kode_reimbust . '.pdf');
    }

    function read_detail($id)
    {
        $data = $this->M_mac_reimpayment->get_by_id_detail($id);
        echo json_encode($data);
    }

    public function detail_deklarasi()
    {
        if ($this->input->is_ajax_request()) {
            $deklarasi = $this->input->post('deklarasi');

            // Mengambil data deklarasi dari database
            $deklarasiRecord = $this->db->get_where('mac_deklarasi', ['kode_deklarasi' => $deklarasi])->row_array();

            // Debug log
            log_message('debug', 'Deklarasi: ' . print_r($deklarasi, true));
            log_message('debug', 'Deklarasi Record: ' . print_r($deklarasiRecord, true));

            if ($deklarasiRecord) {
                // Mengambil ID dari record yang ditemukan
                $deklarasiId = $deklarasiRecord['id']; // Pastikan 'id' adalah nama kolom yang sesuai
                $redirect_url = site_url('mac_datadeklarasi/read_form/' . $deklarasiId);

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
}
