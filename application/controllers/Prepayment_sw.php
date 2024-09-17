<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Prepayment_sw extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_prepayment_sw');
        $this->load->model('backend/M_app');
        $this->M_login->getsecurity();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        // $akses = $this->M_app->hak_akses($this->session->userdata('level'), $this->router->fetch_class());
        // ($akses->view_level == 'N' ? redirect('auth') : '');
        // $data['add'] = $akses->add_level;


        $data['title'] = "backend/prepayment_sw/prepayment_list_sw";
        $data['titleview'] = "Data Prepayment";
        $name = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $this->session->userdata('id_user'))
            ->get()
            ->row('name');
        $data['approval'] = $this->db->select('COUNT(*) as total_approval')
            ->from('tbl_prepayment')
            ->where('app_name', $name)
            ->or_where('app2_name', $name)
            ->get()
            ->row('total_approval');
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
        $list = $this->M_prepayment_sw->get_datatables();
        $data = array();
        $no = $_POST['start'];

        // $akses = $this->M_app->hak_akses($this->session->userdata('level'), $this->router->fetch_class());
        // ($akses->view_level == 'N' ? redirect('auth') : '');
        // $data['add'] = $akses->add_level;

        //LOOPING DATATABLES
        foreach ($list as $field) {

            // MENENTUKAN ACTION APA YANG AKAN DITAMPILKAN DI LIST DATA TABLES
            if ($field->app_name == $fullname) {
                $action = '<a href="prepayment_sw/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                                <a class="btn btn-success btn-circle btn-sm" href="prepayment_sw/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } elseif ($field->app2_name == $fullname) {
                $action = '<a href="prepayment_sw/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>     
                                <a class="btn btn-success btn-circle btn-sm" href="prepayment_sw/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } elseif (in_array($field->status, ['rejected', 'approved'])) {
                $action = '<a href="prepayment_sw/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                <a class="btn btn-success btn-circle btn-sm" href="prepayment_sw/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } elseif ($field->app_status == 'revised' || $field->app2_status == 'revised') {
                $action = '<a href="prepayment_sw/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                    <a href="prepayment_sw/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
                    <a class="btn btn-success btn-circle btn-sm" href="prepayment_sw/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } elseif ($field->app_status == 'approved') {
                $action = '<a href="prepayment_sw/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                            <a class="btn btn-success btn-circle btn-sm" href="prepayment_sw/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } else {
                $action = '<a href="prepayment_sw/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                        <a href="prepayment_sw/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
			            <a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>
                        <a class="btn btn-success btn-circle btn-sm" href="prepayment_sw/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
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
            $row[] = $field->prepayment;
            $row[] = $formatted_nominal;
            // $row[] = $field->tujuan;
            $row[] = $field->status;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_prepayment_sw->count_all(),
            "recordsFiltered" => $this->M_prepayment_sw->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    // UNTUK MENAMPILKAN FORM READ
    public function read_form($id)
    {
        $data['id'] = $id;
        $data['user'] = $this->M_prepayment_sw->get_by_id($id);
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
        $data['title'] = 'backend/prepayment_sw/prepayment_read_sw';
        $data['title_view'] = 'Prepayment';
        $this->load->view('backend/home', $data);
    }

    // UNTUK MENAMPILKAN FORM ADD
    public function add_form()
    {
        $data['id'] = 0;
        $data['title'] = 'backend/prepayment_sw/prepayment_form_sw';
        $data['title_view'] = 'Prepayment Form';
        $this->load->view('backend/home', $data);
    }

    // MEREGENERATE KODE PREPAYMENT
    public function generate_kode()
    {
        $date = $this->input->post('date');
        $kode = $this->M_prepayment_sw->max_kode($date)->row();
        if (empty($kode->kode_prepayment)) {
            $no_urut = 1;
        } else {
            $bln = substr($kode->kode_prepayment, 3, 2);
            $no_urut = substr($kode->kode_prepayment, 5) + 1;
        }
        $urutan = str_pad($no_urut, 4, "0", STR_PAD_LEFT);
        $month = substr($date, 3, 2);
        $year = substr($date, 8, 2);
        $data = 'p' . $year . $month . $urutan;
        echo json_encode($data);
    }

    // UNTUK MENAMPILKAN FORM EDIT
    function edit_form($id)
    {
        $data['id'] = $id;
        $data['aksi'] = 'update';
        $data['title_view'] = "Edit Data Prepayment";
        $data['title'] = 'backend/prepayment_sw/prepayment_form_sw';
        $this->load->view('backend/home', $data);
    }

    function edit_data($id)
    {
        $data['master'] = $this->M_prepayment_sw->get_by_id($id);
        $data['transaksi'] = $this->M_prepayment_sw->get_by_id_detail($id);
        $data['nama'] = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $data['master']->id_user)
            ->get()->row('name');
        echo json_encode($data);
    }

    function read_detail($id)
    {
        $data = $this->M_prepayment_sw->get_by_id_detail($id);
        echo json_encode($data);
    }

    // MENAMBAHKAN DATA
    public function add()
    {
        // INSERT KODE PREPAYMENT SAAT SUBMIT
        $date = $this->input->post('tgl_prepayment');
        $kode = $this->M_prepayment_sw->max_kode($date)->row();
        if (empty($kode->kode_prepayment)) {
            $no_urut = 1;
        } else {
            $bln = substr($kode->kode_prepayment, 3, 2);
            $no_urut = substr($kode->kode_prepayment, 5) + 1;
        }
        $urutan = str_pad($no_urut, 4, "0", STR_PAD_LEFT);
        $month = substr($date, 3, 2);
        $year = substr($date, 8, 2);
        $kode_prepayment = 'P' . $year . $month . $urutan;

        // MENCARI SIAPA YANG AKAN MELAKUKAN APPROVAL PERMINTAAN
        $approval = $this->M_prepayment_sw->approval($this->session->userdata('id_user'));
        $id = $this->session->userdata('id_user');

        $data = array(
            'kode_prepayment' => $kode_prepayment,
            'id_user' => $id,
            'prepayment' => $this->input->post('prepayment'),
            'tujuan' => $this->input->post('tujuan'),
            'tgl_prepayment' => date('Y-m-d', strtotime($this->input->post('tgl_prepayment'))),
            'total_nominal' => $this->input->post('total_nominal'),
            'divisi' => $this->db->select('divisi')
                ->from('tbl_data_user')
                ->where('id_user', $id)
                ->get()
                ->row('divisi'),
            'jabatan' => $this->db->select('jabatan')
                ->from('tbl_data_user')
                ->where('id_user', $id)
                ->get()
                ->row('jabatan'),
            'app_name' => $this->db->select('name')
                ->from('tbl_data_user')
                ->where('id_user', $approval->app_id)
                ->get()
                ->row('name'),
            'app2_name' => $this->db->select('name')
                ->from('tbl_data_user')
                ->where('id_user', $approval->app2_id)
                ->get()
                ->row('name')
        );

        // BILA YANG MEMBUAT PREPAYMENT DAPAT MENGAPPROVE SENDIRI
        if ($approval->app_id == $this->session->userdata('id_user')) {
            $data['app_status'] = 'approved';
            $data['app_date'] = date('Y-m-d H:i:s');
        }

        $inserted = $this->M_prepayment_sw->save($data);

        if ($inserted) {
            // INISIASI VARIABEL INPUT DETAIL PREPAYMENT
            $rincian = $this->input->post('rincian[]');
            $nominal = $this->input->post('hidden_nominal[]');
            $keterangan = $this->input->post('keterangan[]');
            //PERULANGAN UNTUK INSER QUERY DETAIL PREPAYMENT
            for ($i = 1; $i <= count($_POST['rincian']); $i++) {
                $data2[] = array(
                    'prepayment_id' => $inserted,
                    'rincian' => $rincian[$i],
                    'nominal' => $nominal[$i],
                    'keterangan' => $keterangan[$i]
                );
            }
            $this->M_prepayment_sw->save_detail($data2);
        }
        echo json_encode(array("status" => TRUE));
    }

    // UPDATE DATA
    public function update()
    {
        $data = array(
            'kode_prepayment' => $this->input->post('kode_prepayment'),
            'prepayment' => $this->input->post('prepayment'),
            'tujuan' => $this->input->post('tujuan'),
            'tgl_prepayment' => date('Y-m-d', strtotime($this->input->post('tgl_prepayment'))),
            'total_nominal' => $this->input->post('total_nominal'),
            'app_status' => 'waiting',
            'app_date' => null,
            'app_keterangan' => null,
            'app2_status' => 'waiting',
            'app2_date' => null,
            'app2_keterangan' => null,
            'status' => 'on-process'
        );
        $this->db->where('id', $this->input->post('id'));

        //UPDATE DETAIL PREPAYMENT
        $prepayment_id = $this->input->post('id');
        $id_detail = $this->input->post('hidden_id_detail[]');
        $rincian = $this->input->post('rincian[]');
        $nominal = $this->input->post('hidden_nominal[]');
        $keterangan = $this->input->post('keterangan[]');
        if ($this->db->update('tbl_prepayment', $data)) {
            // UNTUK MENGHAPUS ROW YANG TELAH DIDELETE
            $deletedRows = json_decode($this->input->post('deleted_rows'), true);
            if (!empty($deletedRows)) {
                foreach ($deletedRows as $id2) {
                    // Hapus row dari database berdasarkan ID
                    $this->db->where('id', $id2);
                    $this->db->delete('tbl_prepayment_detail');
                }
            }

            //MELAKUKAN REPLACE DATA LAMA DENGAN YANG BARU
            for ($i = 1; $i <= count($_POST['rincian']); $i++) {
                // Set id menjadi NULL jika id_detail tidak ada atau kosong
                $id = !empty($id_detail[$i]) ? $id_detail[$i] : NULL;
                $data2[] = array(
                    'id' => $id,
                    'prepayment_id' => $prepayment_id,
                    'rincian' => $rincian[$i],
                    'nominal' => $nominal[$i],
                    'keterangan' => $keterangan[$i]
                );
                // Menggunakan db->replace untuk memasukkan atau menggantikan data
                $this->db->replace('tbl_prepayment_detail', $data2[$i - 1]);
            }
        }
        echo json_encode(array("status" => TRUE));
    }

    // MENGHAPUS DATA
    function delete($id)
    {
        $this->M_prepayment_sw->delete($id);
        $this->M_prepayment_sw->delete_detail($id);
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
        $this->db->update('tbl_prepayment', $data);

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
        $this->db->update('tbl_prepayment', $data);

        echo json_encode(array("status" => TRUE));
    }

    public function generate_pdf($id)
    {
        // Load FPDF library
        $this->load->library('Fpdf');

        // Load data from database based on $id
        $data['master'] = $this->M_prepayment_sw->get_by_id($id);
        $data['transaksi'] = $this->M_prepayment_sw->get_by_id_detail($id);
        $data['user'] = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $data['master']->id_user)
            ->get()
            ->row('name');
        $data['app_status'] = strtoupper($data['master']->app_status);
        $data['app2_status'] = strtoupper($data['master']->app2_status);

        // Format tgl_prepayment to Indonesian date
        $tanggal = $data['master']->prepayment;
        $formatted_date = date('d F Y', strtotime($tanggal));
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
        $month = date('F', strtotime($tanggal));
        $translated_month = $months[$month];
        $formatted_date = str_replace($month, $translated_month, $formatted_date);

        // Start FPDF
        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->SetTitle('Form Pengajuan Prepayment');
        $pdf->AddPage('P', 'Letter');

        // Logo
        $pdf->Image(base_url('') . '/assets/backend/img/sebelaswarna.png', 8, 10, 40, 30);

        // Pindahkan posisi sedikit ke bawah dan tetap sejajar
        // $pdf->SetX(46); // Tetap di posisi yang sama untuk elemen lain
        $pdf->Ln(27);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(30, 10, 'Divisi', 0, 0);
        $pdf->Cell(5, 10, ':', 0, 0);
        $pdf->Cell(50, 10, $data['master']->divisi, 0, 1);

        // $pdf->SetX(46); // Tetap di posisi yang sama untuk elemen lainnya
        $pdf->Cell(30, 10, 'Prepayment', 0, 0);
        $pdf->Cell(5, 10, ':', 0, 0);
        $pdf->Cell(50, 10, $data['master']->prepayment, 0, 1);
        $pdf->Ln(8);

        // Form Title
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'FORM PENGAJUAN PREPAYMENT', 0, 1, 'C');
        $pdf->Ln(5);

        // Form Fields
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(30, 10, 'Tanggal', 0, 0);
        $pdf->Cell(5, 10, ':', 0, 0);
        $pdf->Cell(50, 10, $formatted_date, 0, 1);

        $pdf->Cell(30, 10, 'Nama', 0, 0);
        $pdf->Cell(5, 10, ':', 0, 0);
        $pdf->Cell(50, 10, $data['user'], 0, 1);

        $pdf->Cell(30, 10, 'Jabatan', 0, 0);
        $pdf->Cell(5, 10, ':', 0, 0);
        $pdf->Cell(50, 10, $data['master']->jabatan, 0, 1);

        $pdf->Cell(60, 10, 'Dengan ini bermaksud mengajukan prepayment untuk :', 0, 1);

        $pdf->Cell(30, 10, 'Tujuan', 0, 0);
        $pdf->Cell(5, 10, ':', 0, 0);
        $pdf->Cell(50, 10, $data['master']->tujuan, 0, 1);
        $pdf->Ln(5);

        // Table Header
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(55, 10, 'Rincian', 1, 0, 'C');
        $pdf->Cell(55, 10, 'Nominal', 1, 0, 'C');
        $pdf->Cell(79, 10, 'Keterangan', 1, 1, 'C');

        // Table Content
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetFillColor(255, 255, 255); // Row color
        foreach ($data['transaksi'] as $row) {
            $pdf->Cell(55, 10, $row['rincian'], 1, 0, 'C', true);
            $pdf->Cell(55, 10, number_format($row['nominal'], 0, ',', '.'), 1, 0, 'C', true);
            $pdf->Cell(79, 10, $row['keterangan'], 1, 1, 'C', true);
        }

        // Table Footer
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(55, 10, 'Total :', 1, 0, 'C');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(134, 10, number_format($data['master']->total_nominal, 0, ',', '.'), 1, 1, 'C');
        $pdf->Ln(10);

        //APPROVAL
        $pdf->SetFont('Arial', 'B', 12);

        // Membuat header tabel
        $pdf->Cell(63, 8.5, 'Yang Melakukan', 1, 0, 'C');
        $pdf->Cell(63, 8.5, 'Mengetahui', 1, 0, 'C');
        $pdf->Cell(63, 8.5, 'Menyetujui', 1, 1, 'C');

        // Set font normal untuk konten tabel
        $pdf->SetFont('Arial', '', 10);

        // Baris pemisah
        $pdf->Cell(63, 5, '', 'LR', 0, 'C');
        $pdf->Cell(63, 5, '', 0, 0, 'C');
        $pdf->Cell(63, 5, '', 'LR', 1, 'C');

        // Baris pertama (Status)
        $pdf->Cell(63, 5, 'CREATED', 'LR', 0, 'C');
        $pdf->Cell(63, 5, strtoupper($data['master']->app_status), 0, 0, 'C');
        $pdf->Cell(63, 5, strtoupper($data['master']->app2_status), 'LR', 1, 'C');

        // Baris kedua (Tanggal)
        $pdf->Cell(63, 5, $data['master']->created_at, 'LR', 0, 'C');
        $pdf->Cell(63, 5, $data['master']->app_date, 0, 0, 'C');
        $pdf->Cell(63, 5, $data['master']->app2_date, 'LR', 1, 'C');

        // Baris pemisah
        $pdf->Cell(63, 5, '', 'LR', 0, 'C');
        $pdf->Cell(63, 5, '', 0, 0, 'C');
        $pdf->Cell(63, 5, '', 'LR', 1, 'C');

        // Jarak kosong untuk pemisah
        $pdf->Ln(0);

        // Baris ketiga (Nama pengguna)
        $pdf->Cell(63, 8.5, $data['user'], 1, 0, 'C');
        $pdf->Cell(63, 8.5, $data['master']->app_name, 1, 0, 'C');
        $pdf->Cell(63, 8.5, $data['master']->app2_name, 1, 1, 'C');


        // Add keterangan
        $pdf->Ln(5);
        $pdf->SetFont('Arial', '', 12);
        if (($data['master']->app_keterangan != null && $data['master']->app_keterangan != '') || ($data['master']->app2_keterangan != null && $data['master']->app2_keterangan != '')) {
            $pdf->Cell(40, 10, 'Keterangan:', 0, 0);
        }
        $pdf->Ln(8);
        if ($data['master']->app_keterangan != null && $data['master']->app_keterangan != '') {
            $pdf->Cell(60, 10, '*' . $data['master']->app_keterangan . '(' . $data['master']->app_name . ')', 0, 1);
        }
        if ($data['master']->app2_keterangan != null && $data['master']->app2_keterangan != '') {
            $pdf->Cell(60, 10, '*' . $data['master']->app2_keterangan . '(' . $data['master']->app2_name . ')', 0, 1);
        }

        // Output the PDF
        $pdf->Output('I', 'Prepayment.pdf');
    }

    // GENERATE PREPAYMENT MENJADI PDF MENGGUNAKAN FPDF
    // public function generate_pdf($id)
    // {
    //     // Load FPDF library
    //     $this->load->library('fpdf');

    //     // Load data from database based on $id
    //     $data['master'] = $this->M_prepayment_sw->get_by_id($id);
    //     $data['transaksi'] = $this->M_prepayment_sw->get_by_id_detail($id);
    //     $data['user'] = $this->db->select('name')
    //         ->from('tbl_data_user')
    //         ->where('id_user', $data['master']->id_user)
    //         ->get()
    //         ->row('name');
    //     $data['app_status'] = strtoupper($data['master']->app_status);
    //     $data['app2_status'] = strtoupper($data['master']->app2_status);

    //     // Format tgl_prepayment to Indonesian date
    //     $formattedDate = $this->formatIndonesianDate($data['master']->tgl_prepayment);

    //     // Start FPDF
    //     $pdf = new FPDF('P', 'mm', 'A4');
    //     $pdf->SetTitle('Form Pengajuan Prepayment');
    //     $pdf->AddPage('P', 'Letter');

    //     // Set font for title
    //     $pdf->SetFont('Arial', 'B', 14);
    //     $pdf->Cell(0, 10, 'PT. MANDIRI CIPTA SEJAHTERA', 0, 1, 'L');

    //     // Set smaller font and position for header info
    //     $pdf->SetFont('Arial', '', 12);
    //     // $pdf->Cell(60, 10, '', 0, 1); // Adding space
    //     $pdf->Cell(60, 10, 'Divisi: ' . $data['master']->divisi, 0, 2, 'L');
    //     $pdf->Cell(60, 10, 'Prepayment: ' . $data['master']->prepayment, 0, 1, 'L');

    //     // Title of the form
    //     $pdf->Ln(8);
    //     $pdf->SetFont('Arial', 'B', 14);
    //     $pdf->Cell(0, 10, 'FORM PENGAJUAN PREPAYMENT', 0, 1, 'C');
    //     $pdf->Ln(5);

    //     // Set font for form data
    //     $pdf->SetFont('Arial', '', 12);
    //     $pdf->Cell(40, 10, 'Tanggal:', 0, 0);
    //     $pdf->Cell(60, 10, $formattedDate, 0, 1);
    //     $pdf->Cell(40, 10, 'Nama:', 0, 0);
    //     $pdf->Cell(60, 10, $data['user'], 0, 1);
    //     $pdf->Cell(40, 10, 'Jabatan:', 0, 0);
    //     $pdf->Cell(60, 10, $data['master']->jabatan, 0, 1);
    //     $pdf->Cell(40, 10, 'Tujuan:', 0, 0);
    //     $pdf->Cell(60, 10, $data['master']->tujuan, 0, 1);

    //     // Add Rincian Table
    //     $pdf->Ln(5);
    //     $pdf->SetFont('Arial', 'B', 12);
    //     $pdf->SetFillColor(248, 249, 250); // Background color
    //     $pdf->Cell(60, 10, 'Rincian', 1, 0, 'L', true);
    //     $pdf->Cell(60, 10, 'Nominal', 1, 0, 'L', true);
    //     $pdf->Cell(60, 10, 'Keterangan', 1, 1, 'L', true);

    //     // Add table data
    //     $pdf->SetFont('Arial', '', 12);
    //     $pdf->SetFillColor(255, 255, 255); // Row color
    //     foreach ($data['transaksi'] as $row) {
    //         $pdf->Cell(60, 10, $row['rincian'], 1, 0, 'L', true);
    //         $pdf->Cell(60, 10, number_format($row['nominal'], 0, ',', '.'), 1, 0, 'L', true);
    //         $pdf->Cell(60, 10, $row['keterangan'], 1, 1, 'L', true);
    //     }
    //     $pdf->SetFont('Arial', 'B', 12);
    //     $pdf->SetFillColor(248, 249, 250); // Background color
    //     $pdf->Cell(60, 10, '', 0, 0, false);
    //     $pdf->Cell(60, 10, 'Total', 1, 0, 'R', true);
    //     $pdf->Cell(60, 10, number_format($data['master']->total_nominal, 0, ',', '.'), 1, 2, 'C', true);

    //     // Add Signature Section
    //     $pdf->Ln(10);
    //     $pdf->SetFont('Arial', 'B', 12);
    //     $pdf->SetFillColor(0, 123, 255); // Background color for headers
    //     $pdf->SetTextColor(255, 255, 255); // White text color
    //     $pdf->Cell(60, 10, 'Yang melakukan', 1, 0, 'C', true);
    //     $pdf->Cell(60, 10, 'Mengetahui', 1, 0, 'C', true);
    //     $pdf->Cell(60, 10, 'Menyetujui', 1, 1, 'C', true);

    //     // Empty cells for signatures
    //     $pdf->SetFont('Arial', 'B', 12);
    //     $pdf->SetTextColor(0, 0, 0); // Reset text color
    //     $pdf->Cell(60, 20, '', 1, 0, 'C');
    //     $pdf->Cell(60, 20, $data['app_status'], 1, 0, 'C');
    //     $pdf->Cell(60, 20, $data['app2_status'], 1, 1, 'C');

    //     // Empty cells for signatures
    //     $pdf->SetFont('Arial', '', 12);
    //     $pdf->SetTextColor(0, 0, 0); // Reset text color
    //     $pdf->Cell(60, 8, $data['user'], 1, 0, 'C');
    //     $pdf->Cell(60, 8, $data['master']->app_name, 1, 0, 'C');
    //     $pdf->Cell(60, 8, $data['master']->app2_name, 1, 1, 'C');

    //     // Add keterangan
    //     $pdf->Ln(5);
    //     $pdf->SetFont('Arial', '', 12);
    //     $pdf->Cell(40, 10, 'Keterangan:', 0, 0);
    //     $pdf->Ln(8);
    //     if ($data['master']->app_keterangan != null) {
    //         $pdf->Cell(60, 10, '*' . $data['master']->app_keterangan, 0, 1);
    //     } elseif ($data['master']->app2_keterangan != null) {
    //         $pdf->Cell(60, 10, '*' . $data['master']->app2_keterangan, 0, 1);
    //     }

    //     // Output the PDF
    //     $pdf->Output('I', 'Prepayment.pdf');
    // }

    // QUERY UNTUK INPUT TANDA TANGAN
    function signature()
    {
        // Ambil data dari request
        $img = $this->input->post('imgBase64');

        // Decode base64
        $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);

        // Tentukan lokasi dan nama file
        $fileName = uniqid() . '.png';
        $filePath = './assets/backend/img/signatures/' . $fileName;

        // Simpan file ke server
        if (file_put_contents($filePath, $data)) {
            echo json_encode(['status' => 'success', 'fileName' => $fileName]);
        } else {
            echo json_encode(['status' => 'error']);
        }
        //Pastikan folder ./assets/backend/img/signatures/ dapat ditulisi oleh server.
        // mkdir -p ./assets/backend/img/signatures/
        // chmod 755 ./assets/backend/img/signatures/
    }
}
