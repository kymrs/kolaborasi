<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Swi_penawaran extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_swi_penawaran');
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
        $data['title'] = "backend/swi_penawaran/swi_penawaran_list";
        $data['titleview'] = "Penawaran";
        $name = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $this->session->userdata('id_user'))
            ->get()
            ->row('name');
        $data['approval'] = $this->db->select('COUNT(*) as total_approval')
            ->from('swi_deklarasi')
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
        $list = $this->M_swi_penawaran->get_datatables();
        $data = array();
        $no = $_POST['start'];

        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        $read = $akses->view_level;
        $edit = $akses->edit_level;
        $delete = $akses->delete_level;
        $print = $akses->print_level;

        //LOOPING DATATABLES
        foreach ($list as $field) {
            $action_read = ($read == 'Y') ? '<a href="swi_penawaran/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>&nbsp;' : '';
            $action_edit = ($edit == 'Y') ? '<a href="swi_penawaran/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;' : '';
            $action_delete = ($delete == 'Y') ? '<a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;' : '';
            $action_print = ($print == 'Y') ? '<a class="btn btn-success btn-circle btn-sm" target="_blank" href="swi_penawaran/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>' : '';

            // MENENTUKAN ACTION APA YANG AKAN DITAMPILKAN DI LIST DATA TABLES
            $action = $action_read . $action_edit . $action_delete . $action_print;

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $action;
            $row[] = strtoupper($field->kode);
            $row[] = $field->name;
            $row[] = $field->asal;
            $row[] = $field->tujuan;
            $row[] = $field->kendaraan;
            $row[] = date("d M Y", strtotime($field->created_at));
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_swi_penawaran->count_all(),
            "recordsFiltered" => $this->M_swi_penawaran->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    function read_form($id)
    {
        $data['id'] = $id;
        $data['user'] = $this->M_swi_penawaran->get_by_id($id);
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
        $data['title_view'] = "Data Penawaran";
        $data['title'] = 'backend/swi_penawaran/swi_penawaran_read';
        $this->load->view('backend/home', $data);
    }

    function add_form()
    {
        $data['id'] = 0;
        $data['id_user'] = $data['id'];
        $data['id_pembuat'] = 0;
        $data['title_view'] = "Penawaran Form";
        $data['title'] = 'backend/swi_penawaran/swi_penawaran_form';
        $this->load->view('backend/home', $data);
    }

    function edit_form($id)
    {
        $data['id'] = $id;
        $data['id_user'] = $this->session->userdata('id_user');
        $data['title_view'] = "Edit Data Penawaran";
        $data['aksi'] = 'update';
        $data['title'] = 'backend/swi_penawaran/swi_penawaran_form';
        $this->load->view('backend/home', $data);
    }

    function edit_data($id)
    {
        $data['master'] = $this->M_swi_penawaran->get_by_id($id);
        $data['transaksi'] = $this->M_swi_penawaran->get_by_id_detail($id);
        echo json_encode($data);
    }

    // MEREGENERATE KODE DEKLARASI
    public function generate_kode()
    {
        $date = $this->input->post('date');
        $kode = $this->M_swi_penawaran->max_kode($date)->row();
        if (empty($kode->kode)) {
            $no_urut = 1;
        } else {
            $no_urut = substr($kode->kode, 7, 3) + 1;
        }
        $urutan = str_pad($no_urut, 3, "0", STR_PAD_LEFT);
        $month = substr($date, 3, 2);
        $year = substr($date, 6, 4);
        $data = 'S' . $year . $month . $urutan . 'P';
        echo json_encode($data);
    }

    public function add()
    {
        // INSERT KODE DEKLARASI
        $date = $this->input->post('tgl_dokumen');
        $kode = $this->M_swi_penawaran->max_kode($date)->row();
        if (empty($kode->kode)) {
            $no_urut = 1;
        } else {
            $no_urut = substr($kode->kode, 7, 3) + 1;
        }
        $urutan = str_pad($no_urut, 3, "0", STR_PAD_LEFT);
        $month = substr($date, 3, 2);
        $year = substr($date, 6, 4);
        $kode_penawaran = 'S' . $year . $month . $urutan . 'P';

        $data = array(
            'kode' => $kode_penawaran,
            'created_at' => date('Y-m-d', strtotime($this->input->post('tgl_dokumen'))),
            'name' => $this->input->post('name'),
            'asal' => $this->input->post('asal'),
            'tujuan' => $this->input->post('tujuan'),
            'kendaraan' => $this->input->post('kendaraan'),
        );

        // INISIASI VARIABLE DETAIL
        $tgl_keberangkatan = $this->input->post('tgl_keberangkatan[]');
        $tgl_kepulangan = $this->input->post('tgl_kepulangan[]');
        $jenis = $this->input->post('jenis[]');
        $jumlah = $this->input->post('jumlah[]');
        $nominal = $this->input->post('hidden_nominal[]');
        $keterangan = $this->input->post('keterangan[]');

        if ($id_penawaran = $this->M_swi_penawaran->save($data)) {
            for ($i = 1; $i <= count($tgl_keberangkatan); $i++) {
                $data2[] = array(
                    'id_penawaran' => $id_penawaran,
                    'tgl_keberangkatan' => date('Y-m-d', strtotime($tgl_keberangkatan[$i])),
                    'tgl_kepulangan' => date('Y-m-d', strtotime($tgl_kepulangan[$i])),
                    'jenis' => $jenis[$i],
                    'jumlah' => $jumlah[$i],
                    'harga' => $nominal[$i],
                    'keterangan' => isset($keterangan[$i]) ? $keterangan[$i] : ''
                );
            }
            $this->M_swi_penawaran->save_detail($data2);
            echo json_encode(array("status" => TRUE));
        } else {
            echo json_encode(array("status" => FALSE));
        }
    }

    public function update()
    {
        $data = array(
            'created_at' => date('Y-m-d', strtotime($this->input->post('tgl_dokumen'))),
            'kode' => $this->input->post('kode_penawaran'),
            'name' => $this->input->post('name'),
            'asal' => $this->input->post('asal'),
            'tujuan' => $this->input->post('tujuan'),
            'kendaraan' => $this->input->post('kendaraan'),
        );
        // $this->db->where('id', $this->input->post('id'));
        // $this->db->update('swi_penawaran', $data);
        if ($this->db->update('swi_penawaran', $data, ['id' => $this->input->post('id')])) {
            // INISIASI VARIABLE DETAIL
            $tgl_keberangkatan = $this->input->post('tgl_keberangkatan[]');
            $tgl_kepulangan = $this->input->post('tgl_kepulangan[]');
            $id_penawaran2 = $this->input->post('id');
            $id_detail = $this->input->post('hidden_id_detail[]');
            $jenis = $this->input->post('jenis[]');
            $jumlah = $this->input->post('jumlah[]');
            $nominal = $this->input->post('hidden_nominal[]');
            $keterangan = $this->input->post('keterangan[]');


            // UNTUK MENGHAPUS ROW YANG TELAH DIDELETE
            $deletedRows = json_decode($this->input->post('deleted_rows'), true);
            if (!empty($deletedRows)) {
                foreach ($deletedRows as $id2) {
                    // Hapus row dari database berdasarkan ID
                    $this->db->where('id', $id2);
                    $this->db->delete('swi_penawaran_detail');
                }
            }

            for ($i = 1; $i <= count($tgl_keberangkatan); $i++) {
                $id = !empty($id_detail[$i]) ? $id_detail[$i] : NULL;
                $data2 = array(
                    'id' => $id,
                    'id_penawaran' => $id_penawaran2,
                    'tgl_keberangkatan' => date('Y-m-d', strtotime($tgl_keberangkatan[$i])),
                    'tgl_kepulangan' => date('Y-m-d', strtotime($tgl_kepulangan[$i])),
                    'jenis' => $jenis[$i],
                    'jumlah' => $jumlah[$i],
                    'harga' => $nominal[$i],
                    'keterangan' => isset($keterangan[$i]) ? $keterangan[$i] : ''
                );
                $this->db->replace('swi_penawaran_detail', $data2);
            }
            echo json_encode(array("status" => TRUE));
        } else {
            echo json_encode(array("status" => FALSE));
        }
    }

    function delete($id)
    {
        $this->M_swi_penawaran->delete($id);
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
        $this->db->update('swi_deklarasi', $data);

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
        $this->db->update('swi_deklarasi', $data);

        echo json_encode(array("status" => TRUE));
    }

    // PRINTOUT FPDF
    public function generate_pdf($id)
    {
        // Load FPDF library
        $this->load->library('Fpdf_generate');

        // Load data from database based on $id
        $data['master'] = $this->M_swi_penawaran->get_by_id($id);
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
        $pdf->Image(base_url('') . '/assets/backend/img/sobatwisata.png', 12, 8, 34, 26);

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
        // $pdf->Cell(40, 10, 'Jabatan', 0, 0);
        // $pdf->Cell(60, 10, ': ' . $data['master']->jabatan, 0, 1);

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
        $pdf->Cell(63, 8.5, 'Yang Melakukan', 1, 0, 'C');
        $pdf->Cell(63, 8.5, 'Mengetahui', 1, 0, 'C');
        $pdf->Cell(63, 8.5, 'Menyetujui', 1, 1, 'C');

        // Set font normal untuk konten tabel
        $pdf->SetFont('Poppins-Regular', '', 10);

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
        $pdf->SetFont('Poppins-Regular', '', 12);
        if (($data['master']->app_keterangan != null && $data['master']->app_keterangan != '') || ($data['master']->app2_keterangan != null && $data['master']->app2_keterangan != '')) {
            $pdf->Cell(40, 10, 'Keterangan:', 0, 0);
        }
        $pdf->Ln(8);
        if ($data['master']->app_keterangan != null && $data['master']->app_keterangan != '') {
            $pdf->Cell(60, 10, '*' . '(' . $data['master']->app_name . ') ' . $data['master']->app_keterangan, 0, 1);
        }
        if ($data['master']->app2_keterangan != null && $data['master']->app2_keterangan != '') {
            $pdf->Cell(60, 10, '*' . '(' . $data['master']->app2_name . ') ' . $data['master']->app2_keterangan, 0, 1);
        }

        // Output the PDF
        $pdf->Output('I', 'Deklarasi.pdf');
    }
}
