<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sw_datadeklarasi extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_sw_datadeklarasi');
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
        $data['alias'] = $this->session->userdata('username');
        $data['title'] = "backend/sw_datadeklarasi/sw_deklarasi_list";
        $data['titleview'] = "Deklarasi";
        $name = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $this->session->userdata('id_user'))
            ->get()
            ->row('name');
        $data['approval'] = $this->db->select('COUNT(*) as total_approval')
            ->from('tbl_deklarasi')
            ->where('app_name', $name)
            ->or_where('app2_name', $name)
            ->or_where('app4_name', $name)
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
        $list = $this->M_sw_datadeklarasi->get_datatables();
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
            $action_read = ($read == 'Y') ? '<a href="sw_datadeklarasi/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>&nbsp;' : '';
            $action_edit = ($edit == 'Y') ? '<a href="sw_datadeklarasi/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;' : '';
            $action_delete = ($delete == 'Y') ? '<a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;' : '';
            $action_print = ($print == 'Y') ? '<a class="btn btn-success btn-circle btn-sm" target="_blank" href="sw_datadeklarasi/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>' : '';

            // MENENTUKAN ACTION APA YANG AKAN DITAMPILKAN DI LIST DATA TABLES
            if ($this->session->userdata('username') == 'eko') {
                $action = $action_read . $action_edit . $action_delete . $action_print;
            } elseif ($field->id_pengaju == $this->session->userdata('id_user') && !in_array($field->status, ['rejected', 'approved', 'revised']) && $field->app_status == "waiting") {
                $action = $action_read . $action_edit . $action_delete . $action_print;
            } elseif (($field->id_pengaju == $this->session->userdata('id_user') || $this->session->userdata('username') == 'eko') && $field->status == 'revised') {
                $action = $action_read . $action_edit . $action_print;
            } else {
                $action = $action_read . $action_print;
            }

            //MENENSTUKAN SATTSU PROGRESS PENGAJUAN PERMINTAAN
            if ($field->app_status == 'approved' && $field->app2_status == 'waiting' && $field->status == 'on-process') {
                $status = $field->status . ' (' . $field->app2_name . ')';
            } elseif ($field->app4_status == 'approved' && $field->app2_status == 'waiting' && $field->status == 'on-process') {
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
            $row[] = strtoupper($field->kode_deklarasi);
            $row[] = $this->tgl_indo(date("Y-m-j", strtotime($field->tgl_deklarasi)));
            $row[] = $field->name;
            // $row[] = $field->jabatan;
            $row[] = $field->nama_dibayar;
            $row[] = $field->tujuan;
            $row[] = 'Rp. ' . number_format($field->sebesar, 0, ',', '.');;
            // $row[] = $field->sebesar;
            $row[] = $status;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_sw_datadeklarasi->count_all(),
            "recordsFiltered" => $this->M_sw_datadeklarasi->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    function read_form($id)
    {
        $data['id'] = $id;
        $data['user'] = $this->M_sw_datadeklarasi->get_by_id($id);
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
        $data['app4_name'] = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $this->session->userdata('id_user'))
            ->get()
            ->row('name');
        $data['title_view'] = "Data Deklarasi";
        $data['title'] = 'backend/sw_datadeklarasi/sw_deklarasi_read';
        $this->load->view('backend/home', $data);
    }

    function add_form()
    {
        $data['id'] = 0;
        $data['id_user'] = $data['id'];
        $data['id_pembuat'] = 0;
        $data['title_view'] = "Deklarasi Form";
        $data['aksi'] = 'update';
        $data['title'] = 'backend/sw_datadeklarasi/sw_deklarasi_form';
        $this->load->view('backend/home', $data);
    }

    function edit_form($id)
    {
        $data['id'] = $id;
        $data['id_user'] = $this->session->userdata('id_user');
        $data['id_pembuat'] = $this->M_sw_datadeklarasi->get_by_id($id)->id_pengaju;
        $data['title_view'] = "Edit Data Deklarasi";
        $data['title'] = 'backend/sw_datadeklarasi/sw_deklarasi_form';
        $this->load->view('backend/home', $data);
    }

    function edit_data($id)
    {
        $data['master'] = $this->M_sw_datadeklarasi->get_by_id($id);
        $data['nama'] = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $data['master']->id_pengaju)
            ->get()->row('name');
        echo json_encode($data);
    }

    // MEREGENERATE KODE DEKLARASI
    public function generate_kode()
    {
        $date = $this->input->post('date');
        $kode = $this->M_sw_datadeklarasi->max_kode($date)->row();
        if (empty($kode->kode_deklarasi)) {
            $no_urut = 1;
        } else {
            $bln = substr($kode->kode_deklarasi, 3, 2);
            $no_urut = substr($kode->kode_deklarasi, 5) + 1;
        }
        $urutan = str_pad($no_urut, 3, "0", STR_PAD_LEFT);
        $month = substr($date, 3, 2);
        $year = substr($date, 8, 2);
        $data = 'd' . $year . $month . $urutan;
        echo json_encode($data);
    }

    public function add()
    {
        // INSERT KODE DEKLARASI
        $date = $this->input->post('tgl_deklarasi');
        $kode = $this->M_sw_datadeklarasi->max_kode($date)->row();
        if (empty($kode->kode_deklarasi)) {
            $no_urut = 1;
        } else {
            $bln = substr($kode->kode_deklarasi, 3, 2);
            $no_urut = substr($kode->kode_deklarasi, 5) + 1;
        }
        $urutan = str_pad($no_urut, 3, "0", STR_PAD_LEFT);
        $month = substr($date, 3, 2);
        $year = substr($date, 8, 2);
        $kode_deklarasi = 'D' . $year . $month . $urutan;

        // MENCARI SIAPA YANG AKAN MELAKUKAN APPROVAL PERMINTAAN
        $id_menu = $this->db->select('id_menu')
            ->where('link', $this->router->fetch_class())
            ->get('tbl_submenu')
            ->row();

        $valid = true;
        $confirm = $this->db->select('app_id, app2_id, app4_id')->from('tbl_approval')->where('id_menu', $id_menu->id_menu)->get()->row();
        if (!empty($confirm) && isset($confirm->app_id, $confirm->app2_id)) {
            $app = $confirm;
        } else {
            echo json_encode(array("status" => FALSE, "error" => "Approval Belum Ditentukan, Mohon untuk menghubungi admin."));
            exit();
            $valid = false;
        }
        $id = $this->session->userdata('id_user');

        $data = array(
            'kode_deklarasi' => $kode_deklarasi,
            'tgl_deklarasi' => date('Y-m-d', strtotime($this->input->post('tgl_deklarasi'))),
            'id_pengaju' => $id,
            'jabatan' => $this->db->select('jabatan')
                ->from('tbl_data_user')
                ->where('id_user', $id)
                ->get()
                ->row('jabatan'),
            'nama_dibayar' => $this->input->post('nama_dibayar'),
            'tujuan' => $this->input->post('tujuan'),
            'sebesar' => $this->input->post('hidden_sebesar'),
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
            'created_at' => date('Y-m-d H:i:s')
        );

        if ($app->app4_id != null) {
            $data['app4_name'] = $this->db->select('name')
                ->from('tbl_data_user')
                ->where('id_user', $app->app4_id)
                ->get()
                ->row('name');
        }

        if ($valid) {
            $this->M_sw_datadeklarasi->save($data);
            echo json_encode(array("status" => TRUE));
        } else {
            echo json_encode(array("status" => FALSE));
        }
    }

    public function update()
    {
        $data = array(
            'tgl_deklarasi' => date('Y-m-d', strtotime($this->input->post('tgl_deklarasi'))),
            'nama_dibayar' => $this->input->post('nama_dibayar'),
            'tujuan' => $this->input->post('tujuan'),
            'sebesar' => $this->input->post('hidden_sebesar'),
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
        $this->db->update('tbl_deklarasi', $data);
        echo json_encode(array("status" => TRUE));
    }

    function delete($id)
    {
        $this->M_sw_datadeklarasi->delete($id);
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
        $this->db->update('tbl_deklarasi', $data);

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
        $this->db->update('tbl_deklarasi', $data);

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
        $this->db->update('tbl_deklarasi', $data);

        echo json_encode(array("status" => TRUE));
    }

    // PRINTOUT FPDF
    public function generate_pdf($id)
    {
        // Load FPDF library
        $this->load->library('Fpdf_generate');

        // Load data from database based on $id
        $data['master'] = $this->M_sw_datadeklarasi->get_by_id($id);
        $data['user'] = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $data['master']->id_pengaju)
            ->get()
            ->row('name');
        $data['app_status'] = strtoupper($data['master']->app_status);
        $data['app2_status'] = strtoupper($data['master']->app2_status);

        // Format tgl_prepayment to Indonesian date
        $tanggal = $data['master']->tgl_deklarasi;
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
        $pdf->SetTitle('Form Deklarasi');
        $pdf->AddPage('P', 'Letter');

        // Logo
        $pdf->Image(base_url('') . '/assets/backend/img/sebelaswarna.png', 14, 5, 40, 30);

        // Title of the form
        $pdf->Ln(25);

        // Set font
        $pdf->AddFont('Poppins-Regular', '', 'Poppins-Regular.php');
        $pdf->AddFont('Poppins-Bold', '', 'Poppins-Bold.php');

        $pdf->SetFont('Poppins-Bold', '', 14);

        $pdf->Cell(0, 10, 'FORM DEKLARASI', 0, 1, 'C');
        $pdf->Ln(5);

        // Set font for form data
        $pdf->SetFont('Poppins-Regular', '', 12);
        $pdf->Cell(40, 10, 'Tanggal', 0, 0);
        $pdf->Cell(60, 10, ': ' . $formatted_date, 0, 1);
        $pdf->Cell(40, 10, 'Nama', 0, 0);
        $pdf->Cell(60, 10, ': ' . $data['user'], 0, 1);
        $pdf->Cell(40, 10, 'Jabatan', 0, 0);
        $pdf->Cell(60, 10, ': ' . $data['master']->jabatan, 0, 1);

        $pdf->Ln(1);
        $pdf->SetFont('Poppins-Regular', '', 12);
        $pdf->Cell(60, 10, 'Telah/akan melakukan pembayaran kepada:', 0, 1);

        // Set font for form data
        $pdf->SetFont('Poppins-Regular', '', 12);
        $pdf->Cell(40, 10, 'Nama', 0, 0);
        $pdf->Cell(60, 10, ': ' . $data['master']->nama_dibayar, 0, 1);
        $pdf->Cell(40, 10, 'Tujuan', 0, 0);
        $pdf->Cell(60, 10, ': ' . $data['master']->tujuan, 0, 1);
        $pdf->Cell(40, 10, 'Sebesar', 0, 0);
        $pdf->Cell(60, 10, ': ' . number_format($data['master']->sebesar, 0, ',', '.'), 0, 1);

        // Jarak kosong untuk pemisah
        $pdf->Ln(3);

        // Set font untuk header
        $pdf->SetFont('Poppins-Bold', '', 12);

        // Membuat header tabel
        $pdf->Cell(47.3, 8.5, 'Yang Melakukan', 1, 0, 'C');
        $pdf->Cell(47.3, 8.5, 'Captain', 1, 0, 'C');
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


        // Add keterangan
        $pdf->Ln(5);
        $pdf->SetFont('Arial', '', 12);
        if (($data['master']->app_keterangan != null && $data['master']->app_keterangan != '') || ($data['master']->app2_keterangan != null && $data['master']->app2_keterangan != '')) {
            $pdf->Cell(40, 10, 'Keterangan:', 0, 0);
        }
        $pdf->Ln(8);
        if ($data['master']->app_keterangan != null && $data['master']->app_keterangan != '') {
            $pdf->Cell(60, 10, '*' . '(' . $data['master']->app_name . ')' . $data['master']->app_keterangan, 0, 1);
        }
        if ($data['master']->app2_keterangan != null && $data['master']->app2_keterangan != '') {
            $pdf->Cell(60, 10, '*' . '(' . $data['master']->app2_name . ')' . $data['master']->app2_keterangan, 0, 1);
        }

        // Output the PDF
        $pdf->Output('I', 'Deklarasi_SW.pdf');
    }
}
