<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Prepayment extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_prepayment');
        $this->M_login->getsecurity();
    }

    public function index()
    {
        $data['title'] = "backend/prepayment/prepayment_list";
        $data['titleview'] = "Data Prepayment";
        $this->load->view('backend/home', $data);
    }

    function get_list()
    {
        // INISIAI VARIABLE YANG DIBUTUHKAN
        $name = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $this->session->userdata('id_user'))
            ->get()
            ->row('name');
        $status = $this->input->post('status'); // Ambil status dari permintaan POST
        $list = $this->M_prepayment->get_datatables($status);
        $data = array();
        $no = $_POST['start'];

        //LOOPING DATATABLES
        foreach ($list as $field) {

            // MENENTUKAN ACTION APA YANG AKAN DITAMPILKAN DI LIST DATA TABLES
            if ($field->app_name == $name) {
                $action = '<a href="prepayment/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                                <a class="btn btn-success btn-circle btn-sm" href="prepayment/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } elseif ($field->app2_name == $name) {
                $action = '<a href="prepayment/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>     
                                <a class="btn btn-success btn-circle btn-sm" href="prepayment/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } elseif ($field->status == 'rejected') {
                $action = '<a href="prepayment/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                <a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>
                <a class="btn btn-success btn-circle btn-sm" href="prepayment/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } else {
                if ($field->app_status == 'approved') {
                    $action = '<a href="prepayment/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
			            <a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>
                        <a class="btn btn-success btn-circle btn-sm" href="prepayment/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
                } else {
                    $action = '<a href="prepayment/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                        <a href="prepayment/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
			            <a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>
                        <a class="btn btn-success btn-circle btn-sm" href="prepayment/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
                }
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
            $row[] = $field->tgl_prepayment;
            $row[] = $field->prepayment;
            $row[] = $formatted_nominal;
            // $row[] = $field->tujuan;
            $row[] = $field->status;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_prepayment->count_all(),
            "recordsFiltered" => $this->M_prepayment->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    // UNTUK MENAMPILKAN FORM READ
    public function read_form($id)
    {
        $data['id'] = $id;
        $data['user'] = $this->M_prepayment->get_by_id($id);
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
        $data['title'] = 'backend/prepayment/prepayment_app';
        $data['title_view'] = 'Prepayment Approval';
        $this->load->view('backend/prepayment/prepayment_read', $data);
    }

    // UNTUK MENAMPILKAN FORM ADD
    public function add_form()
    {
        $data['id'] = 0;
        $data['title'] = 'backend/prepayment/prepayment_form';
        $data['title_view'] = 'Prepayment Form';
        $this->load->view('backend/home', $data);
    }

    // MEREGENERATE KODE PREPAYMENT
    public function generate_kode()
    {
        $date = $this->input->post('date');
        $kode = $this->M_prepayment->max_kode($date)->row();
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
        $data['title'] = 'backend/prepayment/prepayment_form';
        $this->load->view('backend/home', $data);
    }

    function edit_data($id)
    {
        $data['master'] = $this->M_prepayment->get_by_id($id);
        $data['transaksi'] = $this->M_prepayment->get_by_id_detail($id);
        $data['nama'] = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $data['master']->id_user)
            ->get()->row('name');
        echo json_encode($data);
    }

    function read_detail($id)
    {
        $data = $this->M_prepayment->get_by_id_detail($id);
        echo json_encode($data);
    }

    // MENAMBAHKAN DATA
    public function add()
    {
        // INSERT KODE PREPAYMENT SAAT SUBMIT
        $date = $this->input->post('tgl_prepayment');
        $kode = $this->M_prepayment->max_kode($date)->row();
        if (empty($kode->kode_prepayment)) {
            $no_urut = 1;
        } else {
            $bln = substr($kode->kode_prepayment, 3, 2);
            $no_urut = substr($kode->kode_prepayment, 5) + 1;
        }
        $urutan = str_pad($no_urut, 4, "0", STR_PAD_LEFT);
        $month = substr($date, 3, 2);
        $year = substr($date, 8, 2);
        $kode_prepayment = 'p' . $year . $month . $urutan;

        // MENCARI SIAPA YANG AKAN MELAKUKAN APPROVAL PERMINTAAN
        $approval = $this->M_prepayment->approval($this->session->userdata('id_user'));
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
        $inserted = $this->M_prepayment->save($data);

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
            $this->M_prepayment->save_detail($data2);
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
        $this->M_prepayment->delete($id);
        $this->M_prepayment->delete_detail($id);
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

        if ($this->input->post('app_status') === 'revised') {
            $data['status'] = 'revised';
        }

        //UPDATE APPROVAL PERTAMA
        $this->db->where('id', $this->input->post('hidden_id'));
        $this->db->update('tbl_prepayment', $data);

        // UPDATE STATUS PREPAYMENT
        if ($this->input->post('app_status') == 'rejected') {
            $this->db->where('id', $this->input->post('hidden_id'));
            $this->db->update('tbl_prepayment', ['status' => 'rejected']);
        } elseif ($this->input->post('app_status') == 'revised') {
            $this->db->where('id', $this->input->post('hidden_id'));
            $this->db->update('tbl_prepayment', ['status' => 'revised']);
        }

        echo json_encode(array("status" => TRUE));
    }

    function approve2()
    {
        $data = array(
            'app2_keterangan' => $this->input->post('app2_keterangan'),
            'app2_status' => $this->input->post('app2_status'),
            'app2_date' => date('Y-m-d H:i:s'),
        );

        if ($this->input->post('app2_status') === 'revised') {
            $data['status'] = 'revised';
        }

        // UPDATE APPROVAL 2
        $this->db->where('id', $this->input->post('hidden_id'));
        $this->db->update('tbl_prepayment', $data);

        // UPDATE STATUS PREPAYMENT
        if ($this->input->post('app2_status') == 'rejected') {
            $this->db->where('id', $this->input->post('hidden_id'));
            $this->db->update('tbl_prepayment', ['status' => 'rejected']);
        } elseif ($this->input->post('app2_status') == 'rejected') {
            $this->db->where('id', $this->input->post('revised'));
            $this->db->update('tbl_prepayment', ['status' => 'revised']);
        } elseif ($this->input->post('app2_status') == 'approved') {
            $this->db->where('id', $this->input->post('hidden_id'));
            $this->db->update('tbl_prepayment', ['status' => 'approved']);
        }
        echo json_encode(array("status" => TRUE));
    }

    function formatIndonesianDate($date)
    {
        $bulan = [
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
        ];

        $date = new DateTime($date);
        $day = $date->format('d');
        $month = $bulan[(int)$date->format('m')];
        $year = $date->format('Y');

        return "$day $month $year";
    }

    // GENERATE PREPAYMENT MENJADI PDF MENGGUNAKAN FPDF
    public function generate_pdf($id)
    {
        // Load FPDF library
        $this->load->library('fpdf');

        // Load data from database based on $id
        $data['master'] = $this->M_prepayment->get_by_id($id);
        $data['transaksi'] = $this->M_prepayment->get_by_id_detail($id);
        $data['user'] = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $data['master']->id_user)
            ->get()
            ->row('name');
        $data['app_status'] = strtoupper($data['master']->app_status);
        $data['app2_status'] = strtoupper($data['master']->app2_status);

        // Format tgl_prepayment to Indonesian date
        $formattedDate = $this->formatIndonesianDate($data['master']->tgl_prepayment);

        // Start FPDF
        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->SetTitle('Form Pengajuan Prepayment');
        $pdf->AddPage('P', 'Letter');

        // Set font for title
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'PT. MANDIRI CIPTA SEJAHTERA', 0, 1, 'L');

        // Set smaller font and position for header info
        $pdf->SetFont('Arial', '', 12);
        // $pdf->Cell(60, 10, '', 0, 1); // Adding space
        $pdf->Cell(60, 10, 'Divisi: ' . $data['master']->divisi, 0, 2, 'L');
        $pdf->Cell(60, 10, 'Prepayment: ' . $data['master']->prepayment, 0, 1, 'L');

        // Title of the form
        $pdf->Ln(8);
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'FORM PENGAJUAN PREPAYMENT', 0, 1, 'C');
        $pdf->Ln(5);

        // Set font for form data
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(40, 10, 'Tanggal:', 0, 0);
        $pdf->Cell(60, 10, $formattedDate, 0, 1);
        $pdf->Cell(40, 10, 'Nama:', 0, 0);
        $pdf->Cell(60, 10, $data['user'], 0, 1);
        $pdf->Cell(40, 10, 'Jabatan:', 0, 0);
        $pdf->Cell(60, 10, $data['master']->jabatan, 0, 1);
        $pdf->Cell(40, 10, 'Tujuan:', 0, 0);
        $pdf->Cell(60, 10, $data['master']->tujuan, 0, 1);

        // Add Rincian Table
        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetFillColor(248, 249, 250); // Background color
        $pdf->Cell(60, 10, 'Rincian', 1, 0, 'L', true);
        $pdf->Cell(60, 10, 'Nominal', 1, 0, 'L', true);
        $pdf->Cell(60, 10, 'Keterangan', 1, 1, 'L', true);

        // Add table data
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetFillColor(255, 255, 255); // Row color
        foreach ($data['transaksi'] as $row) {
            $pdf->Cell(60, 10, $row['rincian'], 1, 0, 'L', true);
            $pdf->Cell(60, 10, number_format($row['nominal'], 0, ',', '.'), 1, 0, 'L', true);
            $pdf->Cell(60, 10, $row['keterangan'], 1, 1, 'L', true);
        }
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetFillColor(248, 249, 250); // Background color
        $pdf->Cell(60, 10, '', 0, 0, false);
        $pdf->Cell(60, 10, 'Total', 1, 0, 'R', true);
        $pdf->Cell(60, 10, number_format($data['master']->total_nominal, 0, ',', '.'), 1, 2, 'C', true);

        // Add Signature Section
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetFillColor(0, 123, 255); // Background color for headers
        $pdf->SetTextColor(255, 255, 255); // White text color
        $pdf->Cell(60, 10, 'Yang melakukan', 1, 0, 'C', true);
        $pdf->Cell(60, 10, 'Mengetahui', 1, 0, 'C', true);
        $pdf->Cell(60, 10, 'Menyetujui', 1, 1, 'C', true);

        // Empty cells for signatures
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetTextColor(0, 0, 0); // Reset text color
        $pdf->Cell(60, 20, '', 1, 0, 'C');
        $pdf->Cell(60, 20, $data['app_status'], 1, 0, 'C');
        $pdf->Cell(60, 20, $data['app2_status'], 1, 1, 'C');

        // Empty cells for signatures
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetTextColor(0, 0, 0); // Reset text color
        $pdf->Cell(60, 8, $data['user'], 1, 0, 'C');
        $pdf->Cell(60, 8, $data['master']->app_name, 1, 0, 'C');
        $pdf->Cell(60, 8, $data['master']->app2_name, 1, 1, 'C');

        // Add keterangan
        $pdf->Ln(5);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(40, 10, 'Keterangan:', 0, 0);
        $pdf->Ln(8);
        if ($data['master']->app_keterangan != null) {
            $pdf->Cell(60, 10, '*' . $data['master']->app_keterangan, 0, 1);
        } elseif ($data['master']->app2_keterangan != null) {
            $pdf->Cell(60, 10, '*' . $data['master']->app2_keterangan, 0, 1);
        }

        // Output the PDF
        $pdf->Output('I', 'Prepayment.pdf');
    }

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
