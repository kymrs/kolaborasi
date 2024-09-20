<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Datanotifikasi_sw extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_datanotifikasi_sw');
        $this->M_login->getsecurity();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        ($akses->view_level == 'N' ? redirect('auth') : '');
        $data['add'] = $akses->add_level;

        $data['title'] = "backend/datanotifikasi_sw/notifikasi_list_sw";
        $data['titleview'] = "Notifikasi";
        $name = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $this->session->userdata('id_user'))
            ->get()
            ->row('name');
        $data['approval'] = $this->db->select('COUNT(*) as total_approval')
            ->from('tbl_notifikasi')
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
        $status = $this->input->post('status'); // Ambil status dari permintaan POST
        $list = $this->M_datanotifikasi_sw->get_datatables($status);
        $data = array();
        $no = $_POST['start'];

        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        $read = $akses->view_level;
        $edit = $akses->edit_level;
        $delete = $akses->delete_level;
        $print = $akses->print_level;

        foreach ($list as $field) {

            // MENENTUKAN ACTION APA YANG AKAN DITAMPILKAN DI LIST DATA TABLES
            $action_read = ($read == 'Y') ? '<a href="datanotifikasi_sw/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>&nbsp;' : '';
            $action_edit = ($edit == 'Y') ? '<a href="datanotifikasi_sw/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;' : '';
            $action_delete = ($delete == 'Y') ? '<a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;' : '';
            $action_print = ($print == 'Y') ? '<a class="btn btn-success btn-circle btn-sm" target="_blank" href="datanotifikasi_sw/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>' : '';

            // MENENTUKAN ACTION APA YANG AKAN DITAMPILKAN DI LIST DATA TABLES
            if ($field->app_name == $fullname) {
                $action = $action_read . $action_print;
            } elseif ($field->app2_name == $fullname) {
                $action = $action_read . $action_print;
            } elseif (in_array($field->status, ['rejected', 'approved'])) {
                $action = $action_read . $action_print;
            } elseif ($field->app_status == 'revised' || $field->app2_status == 'revised') {
                $action = $action_read . $action_edit . $action_print;
            } elseif ($field->app_status == 'approved') {
                $action = $action_read . $action_print;
            } else {
                $action = $action_read . $action_edit . $action_delete . $action_print;
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
            "recordsTotal" => $this->M_datanotifikasi_sw->count_all(),
            "recordsFiltered" => $this->M_datanotifikasi_sw->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    function read_form($id)
    {
        $data['id'] = $id;
        $data['user'] = $this->M_datanotifikasi_sw->get_by_id($id);
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
        $sql = '
            WITH RankedNotifikasi AS (
                SELECT *,
                       ROW_NUMBER() OVER (ORDER BY created_at ASC) AS row_num
                FROM tbl_notifikasi
                WHERE id_user = ' . $data['user']->id_user . '
                AND YEAR(created_at) = ' . date('Y', strtotime($data['user']->created_at)) . '
            )
            SELECT row_num
            FROM RankedNotifikasi
            WHERE id = ' . $id . ';
        ';
        $query = $this->db->query($sql);
        $data['ke'] = $query->row()->row_num;
        $data['title_view'] = "Data Notifikasi";
        $data['title'] = 'backend/datanotifikasi_sw/notifikasi_read_sw';
        $this->load->view('backend/home', $data);
    }

    // MEREGENERATE KODE PREPAYMENT
    public function generate_kode()
    {
        $date = $this->input->post('date');
        $kode = $this->M_datanotifikasi_sw->max_kode($date)->row();
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
        $data['title_view'] = "Notifikasi Form";
        $data['title'] = 'backend/datanotifikasi_sw/notifikasi_form_sw';
        $this->load->view('backend/home', $data);
    }

    function edit_form($id)
    {
        $data['id'] = $id;
        $data['title_view'] = "Edit Data Notifikasi";
        $data['title'] = 'backend/datanotifikasi_sw/notifikasi_form_sw';
        $this->load->view('backend/home', $data);
    }

    function edit_data($id)
    {
        $data['master'] = $this->M_datanotifikasi_sw->get_by_id($id);
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
        $kode = $this->M_datanotifikasi_sw->max_kode($date)->row();
        if (empty($kode->kode_notifikasi)) {
            $no_urut = 1;
        } else {
            $bln = substr($kode->kode_notifikasi, 3, 2);
            $no_urut = substr($kode->kode_notifikasi, 5) + 1;
        }
        $urutan = str_pad($no_urut, 3, "0", STR_PAD_LEFT);
        $month = substr($date, 3, 2);
        $year = substr($date, 8, 2);
        $kode_notifikasi = 'N' . $year . $month . $urutan;

        // MENCARI SIAPA YANG AKAN MELAKUKAN APPROVAL PERMINTAAN
        $approval = $this->M_datanotifikasi_sw->approval($this->session->userdata('id_user'));
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

        // BILA YANG MEMBUAT PREPAYMENT DAPAT MENGAPPROVE SENDIRI
        if ($approval->app_id == $this->session->userdata('id_user')) {
            $data['app_status'] = 'approved';
            $data['app_date'] = date('Y-m-d H:i:s');
        }

        $this->M_datanotifikasi_sw->save($data);
        echo json_encode(array("status" => TRUE));
    }

    public function update()
    {
        $data = array(
            'pengajuan' => $this->input->post('pengajuan'),
            'waktu' => $this->input->post('waktu'),
            'alasan' => $this->input->post('alasan'),
            'app_status' => 'waiting',
            'app_date' => null,
            'app_keterangan' => null,
            'catatan' => null,
            'app2_status' => 'waiting',
            'app2_date' => null,
            'app2_keterangan' => null,
            'status' => 'on-process'
        );
        $this->db->where('id', $this->input->post('id'));
        $this->db->update('tbl_notifikasi', $data);
        echo json_encode(array("status" => TRUE));
    }

    function delete($id)
    {
        $this->M_datanotifikasi_sw->delete($id);
        echo json_encode(array("status" => TRUE));
    }

    //APPROVE DATA
    public function approve()
    {
        $data = array(
            'app_keterangan' => $this->input->post('app_keterangan'),
            'app_status' => $this->input->post('app_status'),
            'app_date' => date('Y-m-d H:i:s'),
            'catatan' => $this->input->post('app_catatan')
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
        $this->db->update('tbl_notifikasi', $data);

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
        $this->db->update('tbl_notifikasi', $data);

        echo json_encode(array("status" => TRUE));
    }

    // PRINTOUT FPDF
    public function generate_pdf($id)
    {
        // Load FPDF library
        $this->load->library('fpdf');

        // Load data from database based on $id
        $data['master'] = $this->M_datanotifikasi_sw->get_by_id($id);
        $data['user'] = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $data['master']->id_user)
            ->get()
            ->row('name');
        $data['app_status'] = strtoupper($data['master']->app_status);
        $data['app2_status'] = strtoupper($data['master']->app2_status);
        $sql = '
            WITH RankedNotifikasi AS (
                SELECT *,
                       ROW_NUMBER() OVER (ORDER BY created_at ASC) AS row_num
                FROM tbl_notifikasi
                WHERE id_user = ' . $data['master']->id_user . '
                AND YEAR(created_at) = ' . date('Y', strtotime($data['master']->created_at)) . '
            )
            SELECT row_num
            FROM RankedNotifikasi
            WHERE id = ' . $id . ';
        ';
        $query = $this->db->query($sql);
        $data['ke'] = $query->row()->row_num;

        $tanggal = $data['master']->tgl_notifikasi;
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
        $pdf->SetTitle('Form Notifikasi');
        $pdf->AddPage('P', 'Letter');

        // Logo
        $pdf->Image(base_url('') . '/assets/backend/img/sebelaswarna.png', 14, 10, 40, 30);

        // Set font for title
        // $pdf->SetFont('Arial', 'B', 14);
        // $pdf->Cell(0, 28, 'PT. MANDIRI CIPTA SEJAHTERA', 0, 1, 'C');

        // Title of the form
        $pdf->Ln(27);
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'FORM NOTIFIKASI', 0, 1, 'C');
        $pdf->Ln(3);

        $pdf->Ln(1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(60, 10, 'Saya yang bertanda tangan dibawah ini:', 0, 1);

        // Set font for form data
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(40, 10, 'Nama', 0, 0);
        $pdf->Cell(60, 10, ': ' . $data['user'], 0, 1);
        $pdf->Cell(40, 10, 'Jabatan', 0, 0);
        $pdf->Cell(60, 10, ': ' . $data['master']->jabatan, 0, 1);
        $pdf->Cell(40, 10, 'Mengajukan izin', 0, 0);
        $pdf->Cell(60, 10, ': ' . $data['master']->pengajuan, 0, 1);

        // Set font for form data

        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(40, 10, 'Tanggal', 0, 0);
        $pdf->Cell(60, 10, ': ' . $formatted_date, 0, 1);
        $pdf->Cell(40, 10, 'Waktu', 0, 0);
        $pdf->Cell(60, 10, ': ' . $data['master']->waktu, 0, 1);
        $pdf->Cell(40, 10, 'Alasan', 0, 0);
        $pdf->Cell(60, 10, ': ' . $data['master']->alasan, 0, 1);

        $pdf->Ln(3);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(60, 10, 'DIISI OLEH ATASAN KARYAWAN BERSANGKUTAN:', 0, 1);

        if ($data['master']->app_status == 'approved') {
            $status = 'Diizinkan';
        } elseif ($data['master']->app_status == 'rejected') {
            $status = 'Tidak Disetujui';
        } else {
            $status = '';
        }

        $pdf->Ln(3);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(40, 10, 'Notifikasi ini', 0, 0);
        $pdf->Cell(60, 10, ': ' . $status, 0, 1);
        $pdf->Cell(40, 10, 'Dengan alasan', 0, 0);
        $pdf->Cell(60, 10, ': ' . $data['master']->catatan, 0, 1);

        $pdf->Ln(3);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(60, 10, 'CATATAN HUMAN CAPITAL DEPARTEMENT', 0, 1);

        $pdf->Ln(3);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(40, 10, 'Notifikasi ke', 0, 0);
        $pdf->Cell(60, 10, ': ' . $data['ke'] . ' (' . date('Y', strtotime($data['master']->created_at)) . ')', 0, 1);
        $pdf->Ln(3);

        //APPROVAL
        $pdf->SetFont('Arial', 'B', 12);

        // Membuat header tabel
        $pdf->Cell(63, 8.5, 'KARYAWAN', 1, 0, 'C');
        $pdf->Cell(63, 8.5, 'HC DEPARTEMENT', 1, 0, 'C');
        $pdf->Cell(63, 8.5, 'DEPT. HEAD', 1, 1, 'C');

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
        // $pdf->Ln(5);
        // $pdf->SetFont('Arial', '', 12);
        // $pdf->Cell(40, 10, 'Keterangan:', 0, 0);
        // $pdf->Ln(8);
        // if ($data['master']->app_keterangan != null && $data['master']->app_keterangan != '') {
        //     $pdf->Cell(60, 10, '*' . $data['master']->app_keterangan, 0, 1);
        // }
        // if ($data['master']->app2_keterangan != null && $data['master']->app2_keterangan != '') {
        //     $pdf->Cell(60, 10, '*' . $data['master']->app2_keterangan, 0, 1);
        // }

        // Output the PDF
        $pdf->Output('I', 'Deklarasi.pdf');
    }
}
