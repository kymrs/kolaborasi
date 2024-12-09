<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Prepayment_sw extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_prepayment_sw');
        $this->load->model('backend/M_notifikasi');
        $this->M_login->getsecurity();
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

        $data['notif'] = $this->M_notifikasi->pending_notification();

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
            ->or_where('app4_name', $name)
            ->get()
            ->row('total_approval');
        $this->load->view('backend/home', $data);
    }

    public function get_list_event()
    {
        $queries = $this->M_prepayment_sw->get_datatables_event();
        $no = $_POST['start'];  // Tambahkan pengecekan start

        $data = array();  // Inisialisasi variabel data

        foreach ($queries as $query) {

            $is_active = $query->is_active == 1 ? 'Active' : 'Not Active';
            $updated_at = isset($query->updated_at) ? $query->updated_at : '-';

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $query->id;
            $row[] = '<a onclick="delete_data(' . "'" . $query->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;';
            $row[] = $query->event_name;
            $row[] = $is_active;
            $row[] = $query->created_at;
            $row[] = $updated_at;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],  // Tambahkan pengecekan draw
            "recordsTotal" => $this->M_prepayment_sw->count_all_event(),
            "recordsFiltered" => $this->M_prepayment_sw->count_filtered_event(),
            "data" => $data,
        );
        // Output dalam format JSON
        echo json_encode($output);
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

        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        $read = $akses->view_level;
        $edit = $akses->edit_level;
        $delete = $akses->delete_level;
        $print = $akses->print_level;

        //LOOPING DATATABLES
        foreach ($list as $field) {

            $action_read = ($read == 'Y') ? '<a href="prepayment_sw/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>&nbsp;' : '';
            $action_edit = ($edit == 'Y') ? '<a href="prepayment_sw/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;' : '';
            $action_delete = ($delete == 'Y') ? '<a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;' : '';
            $action_print = ($print == 'Y') ? '<a class="btn btn-success btn-circle btn-sm" target="_blank" href="prepayment_sw/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>' : '';

            // MENENTUKAN ACTION APA YANG AKAN DITAMPILKAN DI LIST DATA TABLES
            if ($field->app_name == $fullname && $field->id_user != $this->session->userdata('id_user')) {
                $action = $action_read . $action_print;
            } elseif ($field->id_user != $this->session->userdata('id_user') && $field->app2_name == $fullname) {
                $action = $action_read . $action_print;
            } elseif ($field->id_user != $this->session->userdata('id_user') && $field->app4_name == $fullname) {
                $action = $action_read . $action_print;
            } elseif (in_array($field->status, ['rejected', 'approved'])) {
                $action = $action_read . $action_print;
            } elseif ($field->app_status == 'revised' || $field->app2_status == 'revised' || $field->app4_status == 'revised') {
                $action = $action_read . $action_edit . $action_print;
            } elseif ($field->app4_status == 'approved') {
                $action = $action_read . $action_print;
            } else {
                $action = $action_read . $action_edit . $action_delete . $action_print;
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


            $formatted_nominal = number_format($field->total_nominal, 0, ',', '.');
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $action;
            if ($field->payment_status == 'paid') {
                $row[] = '<div class="text-center"><i class="fas fa-check" style="color: green;"></i></div>'; // Ikon checklist hijau di tengah
            } else if ($field->payment_status == 'unpaid') {
                $row[] = '<div class="text-center"><i class="fas fa-times" style="color: red;"></i></div>'; // Ikon unchecklist merah di tengah
            }
            $row[] = strtoupper($field->kode_prepayment);
            $row[] = $field->event_name;
            $row[] = $field->name;
            $row[] = strtoupper($field->divisi);
            $row[] = strtoupper($field->jabatan);
            $row[] = $this->tgl_indo(date("Y-m-j", strtotime($field->tgl_prepayment)));
            $row[] = $formatted_nominal;
            // $row[] = $field->tujuan;
            $row[] = $status;

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
        $data['notif'] = $this->M_notifikasi->pending_notification();
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
        $data['notif'] = $this->M_notifikasi->pending_notification();
        $data['events'] = $this->M_prepayment_sw->get_events();
        $data['hak_akses'] = $this->session->userdata('id_level');
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
        $data['notif'] = $this->M_notifikasi->pending_notification();
        $data['id'] = $id;
        $data['hak_akses'] = $this->session->userdata('id_level');
        $data['selected'] = $this->M_prepayment_sw->get_selected_event($id);
        $data['aksi'] = 'update';
        $data['events'] = $this->M_prepayment_sw->get_events();
        $data['title_view'] = "Edit Data Prepayment";
        $data['title'] = 'backend/prepayment_sw/prepayment_form_sw';
        $this->load->view('backend/home', $data);
    }

    function edit_data($id)
    {
        $event_id = $this->db->select('event')->from('tbl_prepayment')->where('id', $id)->get()->row('event');
        $data['master'] = $this->M_prepayment_sw->get_by_id($id);
        $data['event'] = $this->db->select('event_name')->from('tbl_event_sw')->where('id', $event_id)->get()->row('event_name');
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
            'event' => $this->input->post('event'),
            'tujuan' => $this->input->post('tujuan'),
            'tgl_prepayment' => date('Y-m-d', strtotime($this->input->post('tgl_prepayment'))),
            'total_nominal' => $this->input->post('total_nominal'),
            'no_rek' => $this->input->post('no_rek'),
            'jenis_rek' =>  $this->input->post('jenis_rek'),
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
                ->row('name'),
            'app4_name' => $this->db->select('name')
                ->from('tbl_data_user')
                ->where('id_user', $approval->app4_id)
                ->get()
                ->row('name'),
            'created_at' => date('Y-m-d H:i:s')
        );

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
            'event' => $this->input->post('event'),
            'tujuan' => $this->input->post('tujuan'),
            'tgl_prepayment' => date('Y-m-d', strtotime($this->input->post('tgl_prepayment'))),
            'total_nominal' => $this->input->post('total_nominal'),
            'no_rek' => $this->input->post('no_rek'),
            'jenis_rek' =>  $this->input->post('jenis_rek'),
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

    public function add_event()
    {
        $data = array( // Ubah $data[] menjadi $data
            'id' => $this->input->post('event_id'),
            'event_name' => $this->input->post('event_name'),
            'is_active' => $this->input->post('is_active')
        );

        if ($this->input->post('event_id') != null) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }

        // Menggunakan db->replace untuk memasukkan atau menggantikan data
        $this->db->replace('tbl_event_sw', $data);

        echo json_encode(array("status" => TRUE));
    }

    // MENGHAPUS DATA
    function delete($id)
    {
        $this->M_prepayment_sw->delete($id);
        $this->M_prepayment_sw->delete_detail($id);
        echo json_encode(array("status" => TRUE));
    }

    // MENGHAPUS DATA
    function delete_event($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('tbl_event_sw');
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
        $this->db->update('tbl_prepayment', $data);

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
        $this->load->library('Fpdf_generate');

        // Load data from database based on $id
        $data['master'] = $this->M_prepayment_sw->get_by_id($id);
        $data['transaksi'] = $this->M_prepayment_sw->get_by_id_detail($id);
        $data['user'] = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $data['master']->id_user)
            ->get()
            ->row('name');
        $data['event_name'] = $this->db->select('event_name')->from('tbl_event_sw')->where('id', $data['master']->event)->get()->row('event_name');
        $data['app_status'] = strtoupper($data['master']->app_status);
        $data['app2_status'] = strtoupper($data['master']->app2_status);

        // Format tgl_prepayment to Indonesian date
        $tanggal = $data['master']->tgl_prepayment;
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

        $pdf->AddFont('Poppins-Regular', '', 'Poppins-Regular.php');
        $pdf->AddFont('Poppins-Bold', '', 'Poppins-Bold.php');

        // Pindahkan posisi sedikit ke bawah dan tetap sejajar
        $pdf->Ln(27);
        $pdf->SetFont('Poppins-Regular', '', 12);
        $pdf->Cell(30, 10, 'Divisi', 0, 0);
        $pdf->Cell(5, 10, ':', 0, 0);
        $pdf->Cell(50, 10, $data['master']->divisi, 0, 1);

        $pdf->Cell(30, 10, 'Event', 0, 0);
        $pdf->Cell(5, 10, ':', 0, 0);
        $pdf->Cell(50, 10, $data['event_name'], 0, 1);
        $pdf->Ln(8);

        // Form Title
        $pdf->SetFont('Poppins-Bold', '', 14);
        $pdf->Cell(0, 10, 'FORM PENGAJUAN PREPAYMENT', 0, 1, 'C');
        $pdf->Ln(5);

        // Form Fields
        $pdf->SetFont('Poppins-Regular', '', 12);
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
        $pdf->SetFont('Poppins-Bold', '', 12);
        $pdf->Cell(55, 10, 'Rincian', 1, 0, 'C');
        $pdf->Cell(55, 10, 'Nominal', 1, 0, 'C');
        $pdf->Cell(79, 10, 'Keterangan', 1, 1, 'C');

        // Table Content
        $pdf->SetFont('Poppins-Regular', '', 12);
        $pdf->SetFillColor(255, 255, 255); // Row color

        foreach ($data['transaksi'] as $row) {
            // Hitung tinggi maksimum dari baris ini berdasarkan jumlah baris dari masing-masing kolom
            $rincianLines = $pdf->GetStringWidth($row['rincian']) / 55; // 55 adalah lebar kolom rincian
            $rincianHeight = ceil($rincianLines) * 7; // Estimasi tinggi kolom rincian (ubah dari 10 ke 7)

            $keteranganLines = $pdf->GetStringWidth($row['keterangan']) / 79; // 79 adalah lebar kolom keterangan
            $keteranganHeight = ceil($keteranganLines) * 7; // Estimasi tinggi kolom keterangan

            // Ambil tinggi maksimum dari kedua kolom
            $height = max($rincianHeight, $keteranganHeight, 7);
            $height2 = max($rincianHeight, $keteranganHeight, 7);
            if ($rincianHeight > 7) {
                $height = 7;
            }
            if ($keteranganHeight > 7) {
                $height2 = 7;
            }
            $maxHeight = max($rincianHeight, $keteranganHeight, 7); // Pastikan tinggi minimum 7

            // Kolom rincian (gunakan MultiCell untuk wrapping)
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(55, $height, $row['rincian'], 1, 'C', true); // Tinggi kolom rincian 7
            $pdf->SetXY($x + 55, $y); // Pindahkan ke posisi kolom selanjutnya

            // Kolom nominal (gunakan MultiCell untuk penyesuaian tinggi)
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(55, $maxHeight, number_format($row['nominal'], 0, ',', '.'), 1, 'C', true); // Tinggi sesuai maxHeight
            $pdf->SetXY($x + 55, $y); // Pindahkan ke posisi kolom selanjutnya

            // Kolom keterangan (gunakan MultiCell untuk wrapping)
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(79, $height2, $row['keterangan'], 1, 'C', true); // Tinggi sesuai maxHeight
            $pdf->SetXY($x + 79, $y); // Pindahkan ke posisi kolom selanjutnya

            // Pindahkan ke baris baru
            $pdf->Ln($maxHeight);
        }

        // Table Footer
        $pdf->SetFont('Poppins-Bold', '', 12);
        $pdf->Cell(55, 10, 'Total :', 1, 0, 'C');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(134, 10, number_format($data['master']->total_nominal, 0, ',', '.'), 1, 1, 'C');
        $pdf->Ln(10);

        //APPROVAL
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
        $pdf->SetFont('Poppins-Regular', '', 12);
        if (($data['master']->app_keterangan != null && $data['master']->app_keterangan != '') || ($data['master']->app2_keterangan != null && $data['master']->app2_keterangan != '')) {
            $pdf->Cell(40, 10, 'Keterangan:', 0, 0);
        }
        $pdf->Ln(8);
        if ($data['master']->app_keterangan != null && $data['master']->app_keterangan != '') {
            $pdf->Cell(60, 10, '*' . '(' . $data['master']->app_name . ') ' . $data['master']->app_keterangan, 0, 1);
        }
        if ($data['master']->app2_keterangan != null && $data['master']->app2_keterangan != '') {
            $pdf->Cell(60, 10, '*' . '(' . $data['master']->app2_name . ')' . $data['master']->app2_keterangan, 0, 1);
        }

        // Output the PDF
        $pdf->Output('I', 'Prepayment_SW.pdf');
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
    }

    function payment()
    {
        $this->db->where('id', $this->input->post('id'));
        $this->db->update('tbl_prepayment', ['payment_status' => $this->input->post('payment_status')]);

        echo json_encode(array("status" => TRUE));
    }
}
