<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Datanotifikasi extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_datanotifikasi');
        $this->M_login->getsecurity();
    }

    public function index()
    {
        $data['title'] = "backend/datanotifikasi/notifikasi_list";
        $data['titleview'] = "Data Notifikasi";
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
        $status = $this->input->post('status'); // Ambil status dari permintaan POST
        $list = $this->M_datanotifikasi->get_datatables($status);
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {

            // MENENTUKAN ACTION APA YANG AKAN DITAMPILKAN DI LIST DATA TABLES
            if ($field->app_name == $fullname) {
                $action = '<a href="datanotifikasi/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                                <a class="btn btn-success btn-circle btn-sm" href="datanotifikasi/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } elseif ($field->app2_name == $fullname) {
                $action = '<a href="datanotifikasi/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>     
                                <a class="btn btn-success btn-circle btn-sm" href="datanotifikasi/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } elseif ($field->status == 'rejected') {
                $action = '<a href="datanotifikasi/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                <a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>
                <a class="btn btn-success btn-circle btn-sm" href="datanotifikasi/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } else {
                if ($field->app_status == 'approved') {
                    $action = '<a href="datanotifikasi/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
			            <a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>
                        <a class="btn btn-success btn-circle btn-sm" href="datanotifikasi/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
                } else {
                    $action = '<a href="datanotifikasi/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                        <a href="datanotifikasi/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
			            <a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>
                        <a class="btn btn-success btn-circle btn-sm" href="datanotifikasi/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
                }
            }


            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $action;
            $row[] = strtoupper($field->kode_notifikasi);
            $row[] = $field->name;
            $row[] = $field->jabatan;
            $row[] = $field->departemen;
            $row[] = $field->pengajuan;
            $row[] = date("d M Y", strtotime($field->tgl_notifikasi));
            $row[] = $field->alasan;
            $row[] = $field->status;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_datanotifikasi->count_all(),
            "recordsFiltered" => $this->M_datanotifikasi->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    function read_form($id)
    {
        $data['id'] = $id;
        $data['user'] = $this->M_datanotifikasi->get_by_id($id);
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
        $data['title_view'] = "Data Notifikasi";
        $this->load->view('backend/datanotifikasi/notifikasi_read2', $data);
    }

    // MEREGENERATE KODE PREPAYMENT
    public function generate_kode()
    {
        $date = $this->input->post('date');
        $kode = $this->M_datanotifikasi->max_kode($date)->row();
        if (empty($kode->kode_notifikasi)) {
            $no_urut = 1;
        } else {
            $bln = substr($kode->kode_notifikasi, 3, 2);
            $no_urut = substr($kode->kode_notifikasi, 5) + 1;
        }
        $urutan = str_pad($no_urut, 3, "0", STR_PAD_LEFT);
        $month = substr($date, 3, 2);
        $year = substr($date, 8, 2);
        $data = 'n' . $year . $month . $urutan;
        echo json_encode($data);
    }

    function add_form()
    {
        $data['id'] = 0;
        $data['title_view'] = "Data Notifikasi Form";
        $data['title'] = 'backend/datanotifikasi/notifikasi_form';
        $this->load->view('backend/home', $data);
    }

    function edit_form($id)
    {
        $data['id'] = $id;
        $data['title_view'] = "Edit Data Notifikasi";
        $data['title'] = 'backend/datanotifikasi/notifikasi_form';
        $this->load->view('backend/home', $data);
    }

    function edit_data($id)
    {
        $data['master'] = $this->M_datanotifikasi->get_by_id($id);
        $data['nama'] = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $data['master']->id_user)
            ->get()->row('name');
        echo json_encode($data);
    }

    public function add()
    {
        // INSERT KODE DEKLARASI
        $date = $this->input->post('tgl_notifikasi');
        $kode = $this->M_datanotifikasi->max_kode($date)->row();
        if (empty($kode->kode_notifikasi)) {
            $no_urut = 1;
        } else {
            $bln = substr($kode->kode_notifikasi, 3, 2);
            $no_urut = substr($kode->kode_notifikasi, 5) + 1;
        }
        $urutan = str_pad($no_urut, 3, "0", STR_PAD_LEFT);
        $month = substr($date, 3, 2);
        $year = substr($date, 8, 2);
        $kode_notifikasi = 'n' . $year . $month . $urutan;

        // MENCARI SIAPA YANG AKAN MELAKUKAN APPROVAL PERMINTAAN
        $approval = $this->M_datanotifikasi->approval($this->session->userdata('id_user'));
        $id = $this->session->userdata('id_user');

        $data = array(
            'kode_notifikasi' => $kode_notifikasi,
            'id_user' => $id,
            'jabatan' => $this->db->select('jabatan')
                ->from('tbl_data_user')
                ->where('id_user', $id)
                ->get()
                ->row('jabatan'),
            'departemen' => $this->db->select('divisi')
                ->from('tbl_data_user')
                ->where('id_user', $id)
                ->get()
                ->row('divisi'),
            'pengajuan' => $this->input->post('pengajuan'),
            'tgl_notifikasi' => date('Y-m-d', strtotime($this->input->post('tgl_notifikasi'))),
            'waktu' => $this->input->post('waktu'),
            'alasan' => $this->input->post('alasan'),
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
        $this->M_datanotifikasi->save($data);
        echo json_encode(array("status" => TRUE));
    }

    public function update()
    {
        $data = array(
            'pengajuan' => $this->input->post('pengajuan'),
            'waktu' => $this->input->post('waktu'),
            'alasan' => $this->input->post('alasan'),
            'status' => 'on-process'
        );
        $this->db->where('id', $this->input->post('id'));
        $this->db->update('tbl_notifikasi', $data);
        echo json_encode(array("status" => TRUE));
    }

    function delete($id)
    {
        $this->M_datanotifikasi->delete($id);
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
        //UPDATE APPROVAL PERTAMA
        $this->db->where('id', $this->input->post('hidden_id'));
        $this->db->update('tbl_notifikasi', $data);

        // UPDATE STATUS PREPAYMENT
        if ($this->input->post('app_status') == 'rejected') {
            $this->db->where('id', $this->input->post('hidden_id'));
            $this->db->update('tbl_notifikasi', ['status' => 'rejected']);
        } elseif ($this->input->post('app_status') == 'revised') {
            $this->db->where('id', $this->input->post('hidden_id'));
            $this->db->update('tbl_notifikasi', ['status' => 'revised']);
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
        // UPDATE APPROVAL 2
        $this->db->where('id', $this->input->post('hidden_id'));
        $this->db->update('tbl_notifikasi', $data);

        // UPDATE STATUS PREPAYMENT
        if ($this->input->post('app2_status') == 'rejected') {
            $this->db->where('id', $this->input->post('hidden_id'));
            $this->db->update('tbl_notifikasi', ['status' => 'rejected']);
        } elseif ($this->input->post('app2_status') == 'revised') {
            $this->db->where('id', $this->input->post('hidden_id'));
            $this->db->update('tbl_notifikasi', ['status' => 'revised']);
        } elseif ($this->input->post('app2_status') == 'approved') {
            $this->db->where('id', $this->input->post('hidden_id'));
            $this->db->update('tbl_notifikasi', ['status' => 'approved']);
        }
        echo json_encode(array("status" => TRUE));
    }

    // PRINTOUT FPDF
    public function generate_pdf($id)
    {
        // Load FPDF library
        $this->load->library('fpdf');

        // Load data from database based on $id
        $data['master'] = $this->M_datanotifikasi->get_by_id($id);
        $data['user'] = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $data['master']->id_user)
            ->get()
            ->row('name');
        $data['app_status'] = strtoupper($data['master']->app_status);
        $data['app2_status'] = strtoupper($data['master']->app2_status);

        // Start FPDF
        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->SetTitle('Form Notifikasi');
        $pdf->AddPage('P', 'Letter');

        // Set font for title
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'PT. MANDIRI CIPTA SEJAHTERA', 0, 1, 'C');

        // Title of the form
        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'FORM NOTIFIKASI', 0, 1, 'C');
        $pdf->Ln(5);

        $pdf->Ln(5);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(60, 10, 'Saya yang bertanda tangan dibawah ini:', 0, 1);

        // Set font for form data
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(40, 10, 'Nama:', 0, 0);
        $pdf->Cell(60, 10, $data['user'], 0, 1);
        $pdf->Cell(40, 10, 'Jabatan:', 0, 0);
        $pdf->Cell(60, 10, $data['master']->jabatan, 0, 1);

        $pdf->Ln(5);
        $pdf->Cell(40, 10, 'Mengajukan izin:', 0, 0);
        $pdf->Cell(60, 10, $data['master']->pengajuan, 0, 1);

        // Set font for form data
        $pdf->Ln(5);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(40, 10, 'Tanggal:', 0, 0);
        $pdf->Cell(60, 10, $data['master']->tgl_notifikasi, 0, 1);
        $pdf->Cell(40, 10, 'Waktu:', 0, 0);
        $pdf->Cell(60, 10, $data['master']->waktu, 0, 1);
        $pdf->Cell(40, 10, 'Alasan:', 0, 0);
        $pdf->Cell(60, 10, $data['master']->alasan, 0, 1);

        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(60, 10, 'DIISI OLEH ATASAN KARYAWAN BERSANGKUTAN:', 0, 1);

        $pdf->Ln(5);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(40, 10, 'Notifikasi ini:', 0, 0);
        $pdf->Cell(60, 10, $data['master']->status, 0, 1);
        $pdf->Cell(40, 10, 'Dengan alasan:', 0, 0);
        $pdf->Cell(60, 10, $data['master']->catatan, 0, 1);

        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(60, 10, 'CATATAN HUMAN CAPITAL DEPARTEMENT', 0, 1);

        $pdf->Ln(5);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(40, 10, 'Notifikasi ke:', 0, 0);
        $pdf->Cell(60, 10, strtoupper($data['master']->kode_notifikasi), 0, 1);

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
        $pdf->Output('I', 'Deklarasi.pdf');
    }
}
