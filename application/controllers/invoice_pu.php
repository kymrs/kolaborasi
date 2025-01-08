<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Memuat file TCPDF dari folder third_party
require_once(APPPATH . 'third_party/TCPDF-main/tcpdf.php');

class T_cpdf2 extends TCPDf
{
    // Page header
    function Header()
    {
        // Logo
        $this->SetFont('helvetica', 'B', 12);
        $this->Image('assets/backend/img/pengenumroh.png', 5, 3, 37, 20);
        $this->SetX(102);
        $this->SetFont('Poppins-Regular', '', 9);
        $this->Cell(40, 16, 'pengenumroh@gmail.com', 0, 0);
        $this->SetX(121);
        $this->Cell(40, 26, '089602984422', 0, 0);
        $style = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));

        $this->Line(4, 27, 145, 27, $style);
        $this->Ln(5);
    }
}

class Invoice_pu extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_invoice_pu');
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

        $data['notif'] = $this->M_notifikasi->pending_notification();

        $data['title'] = "backend/pu_invoice/pu_invoice_list";
        $data['titleview'] = "Data Invoice";
        // $name = $this->db->select('name')
        //     ->from('tbl_data_user')
        //     ->where('id_user', $this->session->userdata('id_user'))
        //     ->get()
        //     ->row('name');
        // $data['approval'] = $this->db->select('COUNT(*) as total_approval')
        //     ->from('tbl_prepayment_pu')
        //     ->where('app_name', $name)
        //     ->or_where('app2_name', $name)
        //     ->get()
        //     ->row('total_approval');
        $this->load->view('backend/home', $data);
    }

    public function get_pdf()
    {
        $this->load->view('backend/prepayment_pu/prepayment_pdf');
    }

    function get_list()
    {
        // INISIAI VARIABLE YANG DIBUTUHKAN
        $fullname = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $this->session->userdata('id_user'))
            ->get()
            ->row('name');
        $list = $this->M_invoice_pu->get_datatables();
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
            $action_read = ($read == 'Y') ? '<a href="invoice_pu/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>&nbsp;' : '';
            $action_edit = ($edit == 'Y') ? '<a href="invoice_pu/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;' : '';
            $action_delete = ($delete == 'Y') ? '<a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;' : '';
            $action_print = ($print == 'Y') ? '<a class="btn btn-success btn-circle btn-sm" target="_blank" href="invoice_pu/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>' : '';

            // MENENTUKAN ACTION APA YANG AKAN DITAMPILKAN DI LIST DATA TABLES
            // if ($field->app_name == $fullname && $field->id_user != $this->session->userdata('id_user')) {
            //     $action = $action_read . $action_print;
            // } elseif ($field->id_user != $this->session->userdata('id_user') && $field->app2_name == $fullname) {
            //     $action = $action_read . $action_print;
            // } elseif (in_array($field->status, ['rejected', 'approved'])) {
            //     $action = $action_read . $action_print;
            // } elseif ($field->app_status == 'revised' || $field->app2_status == 'revised') {
            //     $action = $action_read . $action_edit . $action_print;
            // } elseif ($field->app_status == 'approved') {
            //     $action = $action_read . $action_print;
            // } else {
            //     $action = $action_read . $action_edit . $action_delete . $action_print;
            // }

            // //MENENSTUKAN SATTSU PROGRESS PENGAJUAN PERMINTAAN
            // if ($field->app_status == 'approved' && $field->app2_status == 'waiting' && $field->status == 'on-process') {
            //     $status = $field->status . ' (' . $field->app2_name . ')';
            // } elseif ($field->app_status == 'waiting' && $field->app2_status == 'waiting' && $field->status == 'on-process') {
            //     $status = $field->status . ' (' . $field->app_name . ')';
            // } else {
            //     $status = $field->status;
            // }

            $action = $action_read . $action_edit . $action_delete . $action_print;



            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $action;

            $row[] = $this->tgl_indo(date("Y-m-j", strtotime($field->tgl_invoice)));
            $kode_invoice = substr($field->kode_invoice, 0, 5) . substr($field->kode_invoice, 7, 6);
            $row[] = strtoupper($kode_invoice);
            $row[] = $field->ctc_nama2;
            $row[] = $field->ctc_alamat;
            $row[] = $this->tgl_indo(date("Y-m-j", strtotime($field->tgl_tempo)));

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_invoice_pu->count_all(),
            "recordsFiltered" => $this->M_invoice_pu->count_filtered(),
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
        $data['invoice'] = $this->M_invoice_pu->getInvoiceData($id);
        $data['rekening'] = $this->db->get_where('pu_rek_invoice', ['invoice_id' => $id])->result_array();
        $data['detail'] = $this->db->get_where('pu_detail_invoice', ['invoice_id' => $id])->result_array();
        $data['title'] = 'backend/pu_invoice/pu_invoice_read';
        $data['title_view'] = 'Prepayment';
        $this->load->view('backend/home', $data);
    }

    // UNTUK MENAMPILKAN FORM ADD
    public function add_form()
    {
        $data['id'] = 0;
        $data['title'] = 'backend/pu_invoice/pu_invoice_form';
        $data['title_view'] = 'Invoice Form';
        // $data['rek_options'] = $this->M_invoice_pu->options()->result_array();
        $data['notif'] = $this->M_notifikasi->pending_notification();
        $this->load->view('backend/home', $data);
    }

    // MEREGENERATE KODE INVOICE
    public function generate_kode()
    {
        $date = $this->input->post('date');

        $kode = $this->M_invoice_pu->max_kode($date)->row();

        if (empty($kode->kode_invoice)) {
            $no_urut = 1;
        } else {
            $bln = substr($kode->kode_invoice, 5, 2);
            $no_urut = substr($kode->kode_invoice, 10) + 1;
        }
        $urutan = str_pad($no_urut, 4, "0", STR_PAD_LEFT);
        $year = substr($date, 8, 2);
        $month = substr($date, 3, 2);
        $data = 'INVPU' . $year . $month . $urutan;
        echo json_encode($data);
    }

    // UNTUK MENAMPILKAN FORM EDIT
    function edit_form($id)
    {
        $data['notif'] = $this->M_notifikasi->pending_notification();
        $data['id'] = $id;
        $data['aksi'] = 'update';
        $data['title_view'] = "Edit Data Invoice";
        $data['title'] = 'backend/pu_invoice/pu_invoice_form';
        $this->load->view('backend/home', $data);
    }

    function edit_data($id)
    {
        $data['master'] = $this->M_invoice_pu->get_by_id($id);
        $data['rek_invoice'] = $this->db->get_where('pu_rek_invoice', ['invoice_id' => $id])->result_array();
        $data['detail_invoice'] = $this->db->get_where('pu_detail_invoice', ['invoice_id' => $id])->result_array();
        // $data['nama'] = $this->db->select('name')
        //     ->from('tbl_data_user')
        //     ->where('id_user', $data['master']->id_user)
        //     ->get()->row('name');
        echo json_encode($data);
    }

    function read_detail($id)
    {
        $data = $this->M_invoice_pu->get_by_id_detail($id);
        echo json_encode($data);
    }

    // MENAMBAHKAN DATA
    public function add()
    {
        // INSERT KODE PREPAYMENT SAAT SUBMIT
        $date = $this->input->post('tgl_invoice');

        $kode = $this->M_invoice_pu->max_kode($date)->row();

        if (empty($kode->kode_invoice)) {
            $no_urut = 1;
        } else {
            $bln = substr($kode->kode_invoice, 5, 2);
            $no_urut = substr($kode->kode_invoice, 10) + 1;
        }
        $urutan = str_pad($no_urut, 4, "0", STR_PAD_LEFT);
        $year = substr($date, 8, 2);
        $month = substr($date, 3, 2);

        $kode_invoice = 'INVPU' . $year . $month . $urutan;

        // MENCARI SIAPA YANG AKAN MELAKUKAN APPROVAL PERMINTAAN
        // $approval = $this->M_invoice_pu->approval($this->session->userdata('id_user'));
        // $id = $this->session->userdata('id_user');

        // CHECK APAKAH MENGINPUT YANG SUDAH ADA ATAU YANG BARU (REKENING)
        // if (!empty($_POST['nama_rek'])) {
        //     $no_rek = $this->input->post('nama_rek') . "-" . $this->input->post('nama_bank') . "-" . $this->input->post('nomor_rekening');
        // } else {
        //     $no_rek = $this->input->post('rekening');
        // }

        $data = array(
            'tgl_invoice' => date('Y-m-d', strtotime($this->input->post('tgl_invoice'))),
            'kode_invoice' => $kode_invoice,
            'tgl_tempo' => date('Y-m-d', strtotime($this->input->post('tgl_tempo'))),
            'ctc_nama1' => $this->input->post('ctc_nama1'),
            'ctc_nomor1' => $this->input->post('ctc_nomor1'),
            'ctc_nama2' => $this->input->post('ctc_nama2'),
            'ctc_nomor2' => $this->input->post('ctc_nomor2'),
            'ctc_alamat' => $this->input->post('ctc_alamat'),
            'created_at' => date('Y-m-d H:i:s')
        );

        $inserted = $this->M_invoice_pu->save($data);

        if ($inserted) {
            // INISIASI VARIABEL INPUT DETAIL PREPAYMENT
            $nama_bank = $this->input->post('nama_bank[]');
            $no_rek = $this->input->post('no_rek[]');
            //PERULANGAN UNTUK INSER QUERY DETAIL PREPAYMENT
            for ($i = 0; $i < count($nama_bank); $i++) {
                $data2[] = array(
                    'invoice_id' => $inserted,
                    'nama_bank' => $nama_bank[$i],
                    'no_rek' => $no_rek[$i]
                );
            }
            $this->M_invoice_pu->save_detail($data2);
        }

        if ($inserted) {
            // INISIASI VARIABEL INPUT DETAIL PREPAYMENT
            $deskripsi = $this->input->post('deskripsi[]');
            $jumlah = preg_replace('/\D/', '', $this->input->post('jumlah[]'));
            $harga = preg_replace('/\D/', '', $this->input->post('harga[]'));
            $satuan = $this->input->post('satuan[]');
            $total = preg_replace('/\D/', '', $this->input->post('total[]'));
            //PERULANGAN UNTUK INSER QUERY DETAIL PREPAYMENT
            if (!empty($deskripsi) && !empty($jumlah) && !empty($satuan) && !empty($harga) && !empty($total)) {
                for ($i = 1; $i <= count($deskripsi); $i++) {
                    $data3[] = array(
                        'invoice_id' => $inserted,
                        'deskripsi' => $deskripsi[$i],
                        'jumlah' => $jumlah[$i],
                        'satuan' => $satuan[$i],
                        'harga' => $harga[$i],
                        'total' => $total[$i]
                    );
                }
                $this->M_invoice_pu->save_detail2($data3);
            }
        }

        echo json_encode(array("status" => TRUE));
    }

    // UPDATE DATA
    public function update()
    {
        // CHECK APAKAH MENGINPUT YANG SUDAH ADA ATAU YANG BARU (REKENING)
        if (!empty($_POST['nama_rek'])) {
            $no_rek = $this->input->post('nama_rek') . "-" . $this->input->post('nama_bank') . "-" . $this->input->post('nomor_rekening');
        } else {
            $no_rek = $this->input->post('rekening');
        }

        $data = array(
            'kode_prepayment' => $this->input->post('kode_prepayment'),
            'prepayment' => $this->input->post('prepayment'),
            'tujuan' => $this->input->post('tujuan'),
            'tgl_prepayment' => date('Y-m-d', strtotime($this->input->post('tgl_prepayment'))),
            'total_nominal' => $this->input->post('total_nominal'),
            'no_rek' => $no_rek,
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
        if ($this->db->update('tbl_prepayment_pu', $data)) {
            // UNTUK MENGHAPUS ROW YANG TELAH DIDELETE
            $deletedRows = json_decode($this->input->post('deleted_rows'), true);
            if (!empty($deletedRows)) {
                foreach ($deletedRows as $id2) {
                    // Hapus row dari database berdasarkan ID
                    $this->db->where('id', $id2);
                    $this->db->delete('tbl_prepayment_detail_pu');
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
                $this->db->replace('tbl_prepayment_detail_pu', $data2[$i - 1]);
            }
        }
        echo json_encode(array("status" => TRUE));
    }

    // MENGHAPUS DATA
    function delete($id)
    {
        $this->M_invoice_pu->delete($id);
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
        $this->db->update('tbl_prepayment_pu', $data);

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
        $this->db->update('tbl_prepayment_pu', $data);

        echo json_encode(array("status" => TRUE));
    }

    // PRINTOUT TCPDF
    public function generate_pdf2()
    {

        // INISIAI VARIABLE
        // $penawaran = $this->M_penawaran_la_pu->getPenawaran($id);
        // $rundowns = $this->M_penawaran_la_pu->get_rundown($penawaran->no_pelayanan);
        // $hotels = $this->M_penawaran_la_pu->get_hotels($id);

        // Initialize the TCPDF object
        $t_cpdf2 = new t_cpdf2('P', 'mm', 'A5', true, 'UTF-8', false);

        // Set document properties
        $t_cpdf2->SetCreator(PDF_CREATOR);
        $t_cpdf2->SetAuthor('Author Name');
        $t_cpdf2->SetTitle('Penawaran PDF');

        // $t_cpdf2->SetY(50); // Margin kiri, atas (untuk header), kanan
        // $t_cpdf2->SetHeaderMargin(40);    // Jarak antara header dan konten
        // $t_cpdf2->SetAutoPageBreak(true, 40); // Penanganan otomatis margin bawah

        // Add a new page
        $t_cpdf2->AddPage();

        $t_cpdf2->SetY(30);

        // Pilih font untuk isi
        $t_cpdf2->SetFont('Poppins-Bold', '', 22);

        $t_cpdf2->SetX(57);
        $t_cpdf2->Cell(100, 10, 'INVOICE', 0, 1, 'L');

        $t_cpdf2->SetFont('Poppins-Regular', '', 11);
        $t_cpdf2->SetX(56);
        $t_cpdf2->Cell(19, 4, 'No. Invoice : 01234', 0, 0, 'L');

        $t_cpdf2->SetY($t_cpdf2->GetY());
        $t_cpdf2->SetX(4);
        $t_cpdf2->SetFont('Poppins-Regular', '', 10);
        $t_cpdf2->Cell(75, 20, 'Kepada Yth.', 0, 0);
        $t_cpdf2->Cell(45, 20, 'Tanggal Invoice', 0, 0);
        $t_cpdf2->Cell(19, 20, ':' . '02/01/2025', 0, 0);
        $t_cpdf2->SetY($t_cpdf2->GetY() + 13);
        $t_cpdf2->SetX(4);
        $t_cpdf2->SetFont('Poppins-Regular', '', 14);
        $t_cpdf2->Cell(75, 5, 'Aldo Kurniawan', 0, 0);
        $t_cpdf2->SetFont('Poppins-Regular', '', 10);
        $t_cpdf2->Cell(45, 5, 'Tanggal Jatuh Tempo', 0, 0);
        $t_cpdf2->Cell(19, 5, ':' . '02/01/2025', 0, 0);

        $t_cpdf2->SetFont('Poppins-Regular', '', 10);
        $t_cpdf2->SetY($t_cpdf2->GetY() + 7);
        $t_cpdf2->SetX(4);
        $t_cpdf2->MultiCell(55, 4, 'Alamat :' . 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsam, quasi?', 0, 'L');

        $t_cpdf2->SetY($t_cpdf2->GetY() + 2);
        // HEADER DETAIL PEMESANAN
        $t_cpdf2->SetFont('Poppins-Regular', '', 11);
        $t_cpdf2->SetFillColor(252, 118, 19);
        $t_cpdf2->SetTextColor(255, 255, 255);
        $t_cpdf2->SetX(5);
        $t_cpdf2->Cell(140, 10, 'Detail Pemesanan', 0, 1, 'L', true);
        $t_cpdf2->SetTextColor(0, 0, 0);

        $tbl = <<<EOD
<table border="1" cellpadding="3">
    <thead>
        <tr>
            <th align="center" width="30%"><b>DESKRIPSI</b></th>
            <th align="center" width="20%"><b>JUMLAH</b></th>
            <th align="center" width="25%"><b>HARGA</b></th>
            <th align="center" width="25%"><b>TOTAL</b></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td width="30%">Cokelat Krikil</td>
            <td width="20%">1 Kg</td>
            <td width="25%">100.000</td>
            <td width="25%">100.000</td>
        </tr>
        <tr>
            <td width="30%">Air Zam-zam</td>
            <td width="20%">1 L</td>
            <td width="25%">150.000</td>
            <td width="25%">150.000</td>
        </tr>
        <tr>
            <td width="30%">Kacang Almond</td>
            <td width="20%">2 Kg</td>
            <td width="25%">200.000</td>
            <td width="25%">200.000</td>
        </tr>
        <tr>
            <td width="30%">Kacang kacangan</td>
            <td width="20%">5 Kg</td>
            <td width="25%">400.000</td>
            <td width="25%">400.000</td>
        </tr>
    </tbody>
</table>
EOD;

        $t_cpdf2->writeHTMLCell(142, 0, 4, $t_cpdf2->GetY() + 4, $tbl, 0, 1, false, true, 'L', true);

        $table2 = <<<EOD
            <table>
            <tbody>
            <tr style="border: none;">
                <td colspan="2"></td>
                <td width="25%" style="border: 1px solid black;"> Diskon</td>
                <td width="25%" style="border: 1px solid black;"> 0</td>
            </tr>
            <tr style="border: none;">
                <td colspan="2" style="border: none;"></td>
                <td width="25%" style="border: 1px solid black;"> <b>Total</b></td>
                <td width="25%" style="border: 1px solid black;"> 850.000</td>
            </tr>
            <tbody>
            </table>
        EOD;

        $t_cpdf2->writeHTMLCell(142, 0, 4, $t_cpdf2->GetY(), $table2, 0, 1, false, true, 'L', true);

        $t_cpdf2->SetY($t_cpdf2->GetY() + 1);
        $t_cpdf2->SetX(4);
        $t_cpdf2->SetFont('Poppins-Bold', '', 11);
        $t_cpdf2->Cell(14, 9, 'Metode Pembayaran', 0, 1);
        $list = <<<EOD
                <ol>
                    <li>Bank : Bank Syariah Indonesia<br>No. Rekening : 7215671498</li>
                    <li>Bank : Bank Central Asia<br>No. Rekening : 7131720452</li>
                </ol>
        EOD;
        $t_cpdf2->SetFont('Poppins-Regular', '', 10);
        $y = $t_cpdf2->GetY();
        $x = -4;
        $t_cpdf2->writeHTMLCell(0, 0, $x, $y, $list, 0, 1, false, true, 'L', true);

        $t_cpdf2->SetY($t_cpdf2->GetY() - 1);
        $t_cpdf2->SetX(4);

        $t_cpdf2->SetFont('Poppins-Bold', '', 11);
        $t_cpdf2->Cell(19, 9, 'Atas Nama : PT. Kolaborasi Para Sahabat', 0, 1);

        // Output PDF (tampilkan di browser)
        $t_cpdf2->Output('example.pdf', 'I'); // 'I' untuk menampilkan di browser
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
        $this->db->update('tbl_prepayment_pu', ['payment_status' => $this->input->post('payment_status')]);

        echo json_encode(array("status" => TRUE));
    }
}
