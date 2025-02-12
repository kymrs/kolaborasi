<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Memuat file TCPDF dari folder third_party
require_once(APPPATH . 'third_party/TCPDF-main/tcpdf.php');

class T_cpdf2 extends TCPDf
{
    // Page header
    function Header()
    {
    }
}

class Swi_invoice extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_swi_invoice');
        // $this->load->model('backend/M_notifikasi');
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

        // $data['notif'] = $this->M_notifikasi->pending_notification();

        $data['title'] = "backend/swi_invoice/swi_invoice_list";
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
        $list = $this->M_swi_invoice->get_datatables();
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
            $action_read = ($read == 'Y') ? '<a href="swi_invoice/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>&nbsp;' : '';
            $action_edit = ($edit == 'Y') ? '<a href="swi_invoice/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;' : '';
            $action_delete = ($delete == 'Y') ? '<a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;' : '';
            $action_print = ($print == 'Y') ? '<a class="btn btn-success btn-circle btn-sm" target="_blank" href="swi_invoice/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>' : '';

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
            $row[] = $field->kode_invoice;
            $row[] = $field->ctc_to;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_swi_invoice->count_all(),
            "recordsFiltered" => $this->M_swi_invoice->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    // UNTUK MENAMPILKAN FORM READ
    public function read_form($id)
    {
        // $data['notif'] = $this->M_notifikasi->pending_notification();
        $data['id'] = $id;
        $data['invoice'] = $this->M_swi_invoice->getInvoiceData($id);
        $data['rekening'] = $this->db->get_where('swi_rek_invoice', ['invoice_id' => $id])->result_array();
        $data['detail'] = $this->db->get_where('swi_detail_invoice', ['invoice_id' => $id])->result_array();
        $data['title'] = 'backend/swi_invoice/swi_invoice_read';
        $data['title_view'] = 'Prepayment';
        $this->load->view('backend/home', $data);
    }

    // UNTUK MENAMPILKAN FORM ADD
    public function add_form()
    {
        $data['id'] = 0;
        $data['title'] = 'backend/swi_invoice/swi_invoice_form';
        $data['title_view'] = 'Invoice Form';
        $data['rek_options'] = $this->M_swi_invoice->options()->result();
        // $data['notif'] = $this->M_notifikasi->pending_notification();
        $this->load->view('backend/home', $data);
    }

    // MEREGENERATE KODE INVOICE
    public function generate_kode()
    {
        $date = $this->input->post('date');

        $kode = $this->M_swi_invoice->max_kode($date)->row();

        if (empty($kode->kode_invoice)) {
            $no_urut = 1;
        } else {
            $bln = substr($kode->kode_invoice, 3, 2);
            $no_urut = substr($kode->kode_invoice, 7) + 1;
        }
        $urutan = str_pad($no_urut, 3, "0", STR_PAD_LEFT);
        $year = substr($date, 8, 2);
        $month = substr($date, 3, 2);
        $data = 'S' . $year . $month . $urutan;
        echo json_encode($data);
    }

    // UNTUK MENAMPILKAN FORM EDIT
    function edit_form($id)
    {
        // $data['notif'] = $this->M_notifikasi->pending_notification();
        $data['id'] = $id;
        $data['aksi'] = 'update';
        $data['title_view'] = "Edit Data Invoice";
        $data['title'] = 'backend/swi_invoice/swi_invoice_form';
        $data['rek_options'] = $this->M_swi_invoice->options()->result();
        $this->load->view('backend/home', $data);
    }

    function edit_data($id)
    {
        $data['master'] = $this->M_swi_invoice->get_by_id($id);
        $data['rek_invoice'] = $this->db->get_where('swi_rek_invoice', ['invoice_id' => $id])->result_array();
        $data['detail_invoice'] = $this->db->get_where('swi_detail_invoice', ['invoice_id' => $id])->result_array();
        echo json_encode($data);
    }

    function read_detail($id)
    {
        $data = $this->M_swi_invoice->get_by_id_detail($id);
        echo json_encode($data);
    }

    // MENAMBAHKAN DATA
    public function add()
    {
        // INSERT KODE PREPAYMENT SAAT SUBMIT
        $date = $this->input->post('tgl_invoice');

        $kode = $this->M_swi_invoice->max_kode($date)->row();

        if (empty($kode->kode_invoice)) {
            $no_urut = 1;
        } else {
            $bln = substr($kode->kode_invoice, 3, 2);
            $no_urut = substr($kode->kode_invoice, 7) + 1;
        }
        $urutan = str_pad($no_urut, 3, "0", STR_PAD_LEFT);
        $year = substr($date, 8, 2);
        $month = substr($date, 3, 2);

        $kode_invoice = 'S' . $year . $month . $urutan;

        $data = array(
            'kode_invoice' => $kode_invoice,
            'tgl_invoice' => date('Y-m-d', strtotime($this->input->post('tgl_invoice'))),
            'ctc_to' => $this->input->post('ctc_to'),
            'ctc_address' => $this->input->post('ctc_address'),
            'tax' => preg_replace('/\D/', '', $this->input->post('tax')),
            'total' => preg_replace('/\D/', '', $this->input->post('total_akhir')),
            'created_at' => date('Y-m-d H:i:s')
        );

        $inserted = $this->M_swi_invoice->save($data);

        if ($inserted) {
            // INISIASI VARIABEL INPUT DETAIL PREPAYMENT
            $nama_rek = $this->input->post('nama_rek[]');
            $nama_bank = $this->input->post('nama_bank[]');
            $no_rek = $this->input->post('no_rek[]');
            //PERULANGAN UNTUK INSER QUERY DETAIL PREPAYMENT
            for ($i = 1; $i <= count($nama_rek); $i++) {
                $data2[] = array(
                    'invoice_id' => $inserted,
                    'nama_rek' => $nama_rek[$i],
                    'nama_bank' => $nama_bank[$i],
                    'no_rek' => $no_rek[$i]
                );
            }
            $this->M_swi_invoice->save_detail($data2);
        }

        if ($inserted) {
            // INISIASI VARIABEL INPUT DETAIL PREPAYMENT
            $item = $this->input->post('item[]');
            $qty = $this->input->post('qty[]');
            $day = $this->input->post('day[]');
            $price = preg_replace('/\D/', '', $this->input->post('price[]'));
            $total = preg_replace('/\D/', '', $this->input->post('total[]'));
            //PERULANGAN UNTUK INSER QUERY DETAIL PREPAYMENT
            if (!empty($item) && !empty($qty) && !empty($price) && !empty($day) && !empty($total)) {
                for ($i = 1; $i <= count($item); $i++) {
                    $data3[] = array(
                        'invoice_id' => $inserted,
                        'item' => $item[$i],
                        'qty' => $qty[$i],
                        'day' => $day[$i],
                        'price' => $price[$i],
                        'total' => $total[$i]
                    );
                }
                $this->M_swi_invoice->save_detail2($data3);
            }
        }

        echo json_encode(array("status" => TRUE));
    }

    // UPDATE DATA
    public function update()
    {
        $data = array(
            'tgl_invoice' => date('Y-m-d', strtotime($this->input->post('tgl_invoice'))),
            'ctc_to' => $this->input->post('ctc_to'),
            'ctc_address' => $this->input->post('ctc_address'),
            'tax' => preg_replace('/\D/', '', $this->input->post('tax')),
            'total' => preg_replace('/\D/', '', $this->input->post('total_akhir')),
            'created_at' => date('Y-m-d H:i:s')
        );

        //UPDATE DETAIL PREPAYMENT
        $id_detail = $this->input->post('hidden_id[]');
        // $invoice_id = $this->input->post('hidden_invoiceId[]');
        $item = $this->input->post('item[]');
        $qty = preg_replace('/\D/', '', $this->input->post('qty[]'));
        $day = $this->input->post('day[]');
        $price = preg_replace('/\D/', '', $this->input->post('price[]'));
        $total = preg_replace('/\D/', '', $this->input->post('total[]'));
        if ($this->db->update('swi_invoice', $data, ['id' => $this->input->post('id')])) {
            // UNTUK MENGHAPUS ROW YANG TELAH DIDELETE
            $deletedRows = json_decode($this->input->post('deleted_rows'), true);
            if (!empty($deletedRows)) {
                foreach ($deletedRows as $delRows) {
                    // Hapus row dari database berdasarkan ID
                    $this->db->where('id', $delRows);
                    $this->db->delete('swi_detail_invoice');
                }
            }
            //MELAKUKAN REPLACE DATA LAMA DENGAN YANG BARU
            for ($i = 1; $i <= count($_POST['item']); $i++) {
                // Set id menjadi NULL jika id_detail tidak ada atau kosong
                $id_invoice = !empty($id_detail[$i]) ? $id_detail[$i] : NULL;
                $data2[] = array(
                    'id' => $id_invoice,
                    'invoice_id' => $this->input->post('id'),
                    'item' => $item[$i],
                    'qty' => $qty[$i],
                    'day' => $day[$i],
                    'price' => $price[$i],
                    'total' => $total[$i]
                );
                // Menggunakan db->replace untuk memasukkan atau menggantikan data
                $this->db->replace('swi_detail_invoice', $data2[$i - 1]);
            }

            // UNTUK MENGHAPUS REKENING
            $deletedRekRows = json_decode($this->input->post('deletedRekRows'), true);
            if (!empty($deletedRekRows)) {
                foreach ($deletedRekRows as $delRekRow) {
                    // Hapus row dari database berdasarkan ID
                    $this->db->where('id', $delRekRow);
                    $this->db->delete('swi_rek_invoice');
                }
            }

            // MELAKUKAN REPLACE DATA REKENING
            $id_rek = $this->input->post('hidden_rekId[]');
            $nama_rek = $this->input->post('nama_rek[]');
            $nama_bank = $this->input->post('nama_bank[]');
            $no_rek = $this->input->post('no_rek[]');
            for ($i = 1; $i <= count($_POST['nama_bank']); $i++) {
                // Set id menjadi NULL jika id_rek tidak ada atau kosong
                $id_rekening = !empty($id_rek[$i]) ? $id_rek[$i] : NULL;
                $data3[] = array(
                    'id' => $id_rekening,
                    'invoice_id' => $this->input->post('id'),
                    'nama_rek' => $nama_rek[$i],
                    'nama_bank' => $nama_bank[$i],
                    'no_rek' => $no_rek[$i]
                );
                // Menggunakan db->replace untuk memasukkan atau menggantikan data
                $this->db->replace('swi_rek_invoice', $data3[$i - 1]);
            }
        }
        echo json_encode(array("status" => TRUE));
    }

    // MENGHAPUS DATA
    function delete($id)
    {
        $this->M_swi_invoice->delete($id);
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
    public function generate_pdf($id)
    {
        // INISIAI VARIABLE
        $invoice = $this->M_swi_invoice->get_by_id($id);
        $invoice_details = $this->M_swi_invoice->get_detail($id);
        $invoice_rek = $this->M_swi_invoice->get_rek($id);

        // Initialize the TCPDF object
        $t_cpdf2 = new t_cpdf2('P', 'mm', 'A4', true, 'UTF-8', false);

        // Set document properties
        $t_cpdf2->SetCreator(PDF_CREATOR);
        $t_cpdf2->SetAuthor('Author Name');
        $t_cpdf2->SetTitle('Invoice - ' . $invoice->kode_invoice);

        // Set margin agar gambar memenuhi halaman
        $t_cpdf2->SetMargins(0, 0, 0); // Margin ke 0
        $t_cpdf2->SetAutoPageBreak(true, 0);

        // Tambahkan halaman baru
        $t_cpdf2->AddPage();

        // Tambahkan gambar background untuk memenuhi halaman
        $t_cpdf2->Image('assets/backend/img/background-invoice-swi.png', 0, 0, 210, 297, '', '', '', false);

        // Set posisi untuk teks
        $t_cpdf2->SetY(3);
        $t_cpdf2->SetFont('poppins-bold', '', 24);
        $t_cpdf2->SetX($t_cpdf2->GetPageWidth() - 50); // Posisikan teks di kanan atas
        $t_cpdf2->Cell(35, 10, 'INVOICE', 0, 1, 'R');

        // Set font untuk detail
        $t_cpdf2->SetFont('poppins-regular', '', 11);

        // Set posisi awal
        $t_cpdf2->SetY(15);
        $t_cpdf2->SetX(160);

        // Kolom pertama (No)
        $t_cpdf2->Cell(9, 5, 'No :', 0, 0, 'L');
        $t_cpdf2->Cell(40, 5, $invoice->kode_invoice, 0, 1, 'L');

        // Kolom kedua (Tgl)
        $t_cpdf2->SetX(160);
        $t_cpdf2->Cell(9, 5, 'Tgl :', 0, 0, 'L');
        $t_cpdf2->Cell(40, 5, date('d M Y', strtotime($invoice->tgl_invoice)), 0, 1, 'L');

        $t_cpdf2->SetY(45);
        $t_cpdf2->SetX(7);
        $t_cpdf2->SetFont('poppins-regular', 'B', 10);
        $t_cpdf2->Cell(0, 7, 'FROM', 0, 1, 'L');
        $t_cpdf2->SetFont('poppins-regular', '', 10);
        $t_cpdf2->SetX(7);
        $t_cpdf2->Cell(0, 7, 'Sobat Wisata', 0, 1, 'L');

        $t_cpdf2->SetY(45);
        $t_cpdf2->SetX(80);
        $t_cpdf2->SetFont('poppins-regular', 'B', 10);
        $t_cpdf2->Cell(0, 7, 'TO', 0, 1, 'L');
        $t_cpdf2->SetFont('poppins-regular', '', 10);
        $t_cpdf2->SetX(80);
        $t_cpdf2->Cell(0, 7, $invoice->ctc_to, 0, 1, 'L');

        $t_cpdf2->SetY(63);
        $t_cpdf2->SetX(7);
        $t_cpdf2->SetFont('poppins-regular', 'B', 10);
        $t_cpdf2->Cell(0, 7, 'Address', 0, 1, 'L');
        $t_cpdf2->SetFont('poppins-regular', '', 10);
        $t_cpdf2->SetX(7);
        // MultiCell untuk teks panjang
        $t_cpdf2->MultiCell(65, 7, 'Kp. Tunggilis RT 001 RW 007, Desa/Kelurahan Situsari, Kec. Cileungsi, Kab. Bogor, Provinsi Jawa Barat, Kode Pos: 16820', 0, 'L');

        $t_cpdf2->SetY(63);
        $t_cpdf2->SetX(80);
        $t_cpdf2->SetFont('poppins-regular', 'B', 10);
        $t_cpdf2->Cell(0, 7, 'Address', 0, 1, 'L');
        $t_cpdf2->SetFont('poppins-regular', '', 10);
        $t_cpdf2->SetX(80);
        // MultiCell untuk teks panjang
        $t_cpdf2->MultiCell(65, 7, $invoice->ctc_address, 0, 'L');

        // Table
        // Geser posisi vertikal ke tengah halaman (atur sesuai kebutuhan)
        $t_cpdf2->SetY(117);  // Ini buat vertikal, ubah 100 biar lebih pas
        $t_cpdf2->SetX(18);  // Ini buat vertikal, ubah 100 biar lebih pas

        // Geser posisi horizontal (atur tengah secara manual)
        $html = '<table border="1" cellpadding="5" cellspacing="0" style="margin-left:auto; margin-right:auto; width: 90%;">  
            <thead>
                <tr style="background-color:#f2f2f2; display: none">
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Day</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>';

        // Looping data dari database
        foreach ($invoice_details as $data) {
            $html .= '<tr style="text-align: center">
                <td style="width: 35%">' . $data->item . '</td>
                <td style="width: 8%">' . $data->qty . '</td>
                <td style="width: 20.5%">' . $data->day . '</td>
                <td style="width: 18%">Rp. ' . number_format($data->price, 0, ',', '.') . '</td>
                <td>Rp. ' . number_format($data->total, 0, ',', '.') . '</td>
              </tr>';
        }

        $html .= '</tbody></table>';

        // Tampilkan di PDF
        $t_cpdf2->writeHTML($html, true, false, true, false, '');

        $t_cpdf2->SetY(190);
        $t_cpdf2->SetX(130);
        $t_cpdf2->SetFont('poppins-regular', 'B', 10);
        $t_cpdf2->Cell(0, 7, 'TOTAL', 0, 1, 'L');
        $t_cpdf2->SetFont('poppins-regular', '', 10);
        $t_cpdf2->SetY(190);
        $t_cpdf2->SetX(164);
        $t_cpdf2->Cell(0, 7, 'Rp. ' . number_format($invoice->total, 0, ',', '.'), 0, 1, 'L');

        $t_cpdf2->SetY(198);
        $t_cpdf2->SetX(130);
        $t_cpdf2->SetFont('poppins-regular', 'B', 10);
        $t_cpdf2->Cell(0, 7, 'TAX', 0, 1, 'L');
        $t_cpdf2->SetFont('poppins-regular', '', 10);
        $t_cpdf2->SetY(198);
        $t_cpdf2->SetX(164);
        $t_cpdf2->Cell(0, 7, $invoice->tax ? 'Rp. ' . number_format($invoice->tax, 0, ',', '.') : 'Rp. -', 0, 1, 'L');

        $grand_total = $invoice->total + $invoice->tax;

        $t_cpdf2->SetY(206);
        $t_cpdf2->SetX(130);
        $t_cpdf2->SetFont('poppins-regular', 'B', 10);
        $t_cpdf2->Cell(0, 7, 'GRAND TOTAL', 0, 1, 'L');
        $t_cpdf2->SetFont('poppins-regular', '', 10);
        $t_cpdf2->SetY(206);
        $t_cpdf2->SetX(164);
        $t_cpdf2->Cell(0, 7, 'Rp. ' . number_format($grand_total, 0, ',', '.'), 0, 1, 'L');

        $t_cpdf2->SetY(230);
        $t_cpdf2->SetX(143);
        $t_cpdf2->SetFont('poppins-regular', 'B', 10);
        $t_cpdf2->Cell(0, 7, 'Rahmat Kurniawan', 0, 1, 'L');
        $t_cpdf2->SetY(237);
        $t_cpdf2->SetX(151);
        $t_cpdf2->Cell(0, 7, 'Head Unit', 0, 1, 'L');

        $t_cpdf2->SetY(191);
        $t_cpdf2->SetX(20);
        $t_cpdf2->SetFont('poppins-regular', 'B', 10);
        $t_cpdf2->Cell(0, 7, 'BANK DETAILS', 0, 1, 'L');

        $t_cpdf2->SetY(198);
        $t_cpdf2->SetX(20);

        $t_cpdf2->SetFont('poppins-regular', '', 10);
        // Geser posisi horizontal (atur tengah secara manual)
        $html = '<table border="1" cellpadding="3" cellspacing="0" style="margin-left:auto; margin-right:auto; width: 90%;">  
    <thead>
        <tr style="background-color:#f2f2f2; display: none">
            <th></th>
        </tr>
    </thead>
    <tbody>';

        // Looping data dari database
        foreach ($invoice_rek as $data) {
            $html .= '<tr>
                        <td style="width: 35%">' . $data->nama_bank . '</td>
                    </tr>';
            $html .= '<tr>
                        <td style="width: 35%">' . $data->no_rek . '</td>
                    </tr>';
            $html .= '<tr>
                        <td style="width: 35%">' . $data->nama_rek . '</td>
                    </tr>';
        }

        $html .= '</tbody></table>';

        // Tampilkan di PDF
        $t_cpdf2->writeHTML($html, true, false, true, false, '');

        // $t_cpdf2->SetY(230);
        // $t_cpdf2->SetX(19);
        // $t_cpdf2->SetFont('poppins-regular', '', 10);
        // $t_cpdf2->Cell(0, 7, ' PT. Quick Project Indonesia', 0, 1, 'L');

        // Output file
        $t_cpdf2->Output('Invoice sobatwisata-' . $invoice->kode_invoice . '.pdf', 'I');
    }

    // QUERY UNTUK INPUT TANDA TANGAN
    // function signature()
    // {
    //     // Ambil data dari request
    //     $img = $this->input->post('imgBase64');

    //     // Decode base64
    //     $img = str_replace('data:image/png;base64,', '', $img)
    //     $img = str_replace(' ', '+', $img);
    //     $data = base64_decode($img);

    //     // Tentukan lokasi dan nama file
    //     $fileName = uniqid() . '.png';
    //     $filePath = './assets/backend/img/signatures/' . $fileName;

    //     // Simpan file ke server
    //     if (file_put_contents($filePath, $data)) {
    //         echo json_encode(['status' => 'success', 'fileName' => $fileName]);
    //     } else {
    //         echo json_encode(['status' => 'error']);
    //     }
    // }

    function payment()
    {
        $this->db->where('id', $this->input->post('id'));
        $this->db->update('tbl_prepayment_pu', ['payment_status' => $this->input->post('payment_status')]);

        echo json_encode(array("status" => TRUE));
    }
}
