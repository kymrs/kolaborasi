<?php

use Mpdf\Tag\Center;

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
        $this->Image(base_url('assets/backend/img/header.png'), 49, 5, 160, 30);
        $this->Ln(5);
    }

    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        // $this->SetY(-15);
        // helvetica italic 8
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        // $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
        $this->Image(base_url('assets/backend/img/footer.png'), 0, 280, 210, 5);
    }
}

class Pu_invoice extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_pu_invoice');
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
        $data['edit'] = $akses->edit_level;
        $data['delete'] = $akses->delete_level;
        $data['read'] = $akses->view_level;

        // $data['notif'] = $this->M_notifikasi->pending_notification();

        $data['title'] = "backend/pu_invoice/pu_invoice_list";
        $data['is_active'] = $this->db->select('is_active')
            ->from('pu_invoice')
            ->where('is_active', 0)
            ->get()
            ->row('is_active');
        $data['titleview'] = "Data Invoice";
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
        $list = $this->M_pu_invoice->get_datatables();
        $data = array();
        $no = $_POST['start'];

        //LOOPING DATATABLES
        foreach ($list as $field) {

            $jumlah_terbayar = $this->db->select_sum('total_tagihan')
                ->from('pu_invoice')
                ->where('order_id', $field->pu_order_id)
                ->where('status', 0)
                ->get()
                ->row('total_tagihan') ?? 0;


            if ($field->status == 0) {
                $status = 'Lunas';
            } else {
                $status = 'Belum Lunas';
            }

            $no++;
            $row = array();
            $row[] = $field->pu_order_id;
            $row[] = $no;
            $row[] = '';
            // $row[] = $action;
            $row[] = $field->ctc_nama;
            $row[] = strtoupper($field->kode_order);
            $row[] = $this->tgl_indo(date("Y-m-j", strtotime($field->pu_order_createdAt)));
            $row[] = 'Rp ' . number_format($field->total_order, 0, ',', '.');
            $row[] = 'Rp ' . number_format($jumlah_terbayar, 0, ',', '.');
            $row[] = $status;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_pu_invoice->count_all(),
            "recordsFiltered" => $this->M_pu_invoice->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    // UNTUK MENAMPILKAN FORM INVOICE
    public function read_invoice($id)
    {
        $data['id'] = $id;
        $data['invoice'] = $this->M_pu_invoice->getInvoiceData($id);
        $data['tgl_invoice'] = $this->tgl_indo(date("Y-m-j", strtotime($data['invoice']['tgl_invoice'])));
        $data['tgl_tempo'] = $this->tgl_indo(date("Y-m-j", strtotime($data['invoice']['tgl_tempo'])));
        $data['details'] = $this->db->get_where('pu_detail_invoice', ['invoice_id' => $id])->result_array();
        $data['total_tagihan'] = $this->db->select_sum('total')->get_where('pu_detail_invoice', ['invoice_id' => $id])->row()->total;
        $data['rekening'] = $this->db->get_where('pu_travel', ['id' => $data['invoice']['travel_id']])->row();
        $data['kwitansi'] = $this->db->get_where('pu_kwitansi', ['id_invoice' => $id])->result_array();

        // Mendapatkan total nominal_dibayar
        $this->db->select_sum('nominal_dibayar');
        $this->db->where('id_invoice', $id);
        $data['total_nominal_dibayar'] = $this->db->get('pu_kwitansi')->row()->nominal_dibayar;

        $data['title'] = 'backend/pu_invoice/pu_invoice_read';
        $data['title_view'] = 'Prepayment';
        $this->load->view('backend/home', $data);
    }

    // UNTUK MENAMPILKAN FORM KWITANSI
    public function read_kwitansi($id)
    {
        // $data['notif'] = $this->M_notifikasi->pending_notification();
        $data['id'] = $id;
        $data['invoice'] = $this->M_pu_invoice->getInvoiceData($id);
        $data['kwitansi'] = $this->db->get_where('pu_kwitansi', ['id_invoice' => $id])->result_array();
        // $data['detail'] = $this->db->get_where('pu_detail_invoice', ['invoice_id' => $id])->result_array();
        // $data['total_tagihan'] = $this->db->select_sum('total')->get_where('pu_detail_invoice', ['invoice_id' => $id])->row()->total;
        $data['tgl_invoice'] = $this->tgl_indo(date("Y-m-j", strtotime($data['invoice']['tgl_invoice'])));
        $data['title'] = 'backend/pu_invoice/pu_kwitansi_read';
        $data['title_view'] = 'Prepayment';
        $this->load->view('backend/home', $data);
    }

    public function get_invoice_by_order($order_id)
    {
        $this->db->select('pu_invoice.*, pu_kwitansi.id as kwitansi_id, SUM(pu_kwitansi.nominal_dibayar) as total_dibayar');
        $this->db->from('pu_invoice');
        $this->db->join('pu_kwitansi', 'pu_kwitansi.id_invoice = pu_invoice.id', 'left');
        $this->db->where('pu_invoice.order_id', $order_id);
        $this->db->group_by('pu_invoice.id'); // Penting agar pu_invoice.* tidak error saat SUM
        $result = $this->db->get()->result();

        echo json_encode($result);
    }

    // UNTUK MENAMPILKAN DETAIL KWITANSI
    public function get_kwitansi($data = null)
    {
        if (!$data) {
            // untuk request via AJAX
            $id = $this->input->post('id');
            $id_invoice = $this->input->post('id_invoice');
        } else {
            // untuk pemanggilan internal dari PHP
            $id = $data['id'];
            $id_invoice = $data['id_invoice'];
        }

        $result['kwitansi'] = $this->db->get_where('pu_kwitansi', ['id' => $id])->row();

        $this->db->where('id_invoice', $id_invoice);
        $this->db->where('id <=', $id);
        $result['detail'] = $this->db->get('pu_kwitansi')->result_array();

        $this->db->select_sum('nominal_dibayar');
        $this->db->where('id_invoice', $id_invoice);
        $this->db->where('id <=', $id);
        $result['total_nominal_dibayar'] = $this->db->get('pu_kwitansi')->row()->nominal_dibayar;

        // Bedakan output jika dipanggil via AJAX vs dipanggil secara internal
        if (!$data) {
            echo json_encode($result); // AJAX
        } else {
            return json_encode($result); // internal use
        }
    }


    public function edit_data_kwitansi($id)
    {
        $data = $this->db->get_where('pu_kwitansi', ['id' => $id])->row();
        echo json_encode($data);
    }

    // UNTUK MENAMPILKAN FORM ADD
    public function add_form()
    {
        $data['id'] = 0;
        $data['title'] = 'backend/pu_invoice/pu_invoice_form';
        $data['orders'] = [];
        $data['title_view'] = 'Invoice Form';
        $data['rek_options'] = $this->M_pu_invoice->options()->result();
        $this->load->view('backend/home', $data);
    }

    public function get_kwitansi_dates($id_invoice)
    {
        $this->db->select('id, tanggal_pembayaran');
        $this->db->from('pu_kwitansi');
        $this->db->where('id_invoice', $id_invoice);
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            echo json_encode($query->result_array());
        } else {
            echo json_encode(array(false, 'message' => 'No kwitansi found for this invoice.'));
        }
    }

    // MEREGENERATE KODE INVOICE
    public function generate_kode()
    {
        $date = $this->input->post('date');

        $kode = $this->M_pu_invoice->max_kode($date)->row();

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
        $order_id = $this->db->select('order_id')->from('pu_invoice')->where('id', $id)->get()->row('order_id');
        $data['id'] = $id;
        $data['aksi'] = 'update';
        $data['orders'] = $this->db->select('id')->from('pu_invoice')->where('order_id', $order_id)->get()->result_array();
        $data['title_view'] = "Edit Data Invoice";
        $data['title'] = 'backend/pu_invoice/pu_invoice_form';
        $data['rek_options'] = $this->M_pu_invoice->options()->result();
        $this->load->view('backend/home', $data);
    }

    //UNTUK MENAMPILKAN INVOICE BARU
    function new_form($id)
    {
        $order_id = $this->db->select('order_id')->from('pu_invoice')->where('id', $id)->get()->row('order_id');
        $data['id'] = $id;
        $data['aksi'] = 'new';
        $data['orders'] = $this->db->select('id')->from('pu_invoice')->where('order_id', $order_id)->get()->result_array();
        $data['title_view'] = "Tambah Data Invoice";
        $data['title'] = 'backend/pu_invoice/pu_invoice_form';
        $data['rek_options'] = $this->M_pu_invoice->options()->result();
        $this->load->view('backend/home', $data);
    }

    function edit_data($id)
    {
        $data['master'] = $this->M_pu_invoice->get_by_id($id);
        $data['detail_invoice'] = $this->db->get_where('pu_detail_invoice', ['invoice_id' => $id])->result_array();
        echo json_encode($data);
    }

    function read_detail($id)
    {
        $data = $this->M_pu_invoice->get_by_id_detail($id);
        echo json_encode($data);
    }

    // MENAMBAHKAN DATA
    public function add()
    {
        // INISIASI JAMAAH DAN PESANAN
        $raw_jamaah = $this->input->post('jamaah'); // hasil: "lalaland, testing"
        $raw_pesanan = $this->input->post('pesanan');

        // Pecah berdasarkan koma, lalu bersihkan spasi
        $items = array_filter(array_map('trim', explode(',', $raw_jamaah)));
        $items2 = array_filter(array_map('trim', explode(',', $raw_pesanan)));

        // Bangun HTML list Jamaah
        $html_jamaah = "<ol>";
        foreach ($items as $item) {
            $safe_item = htmlspecialchars($item, ENT_QUOTES, 'UTF-8'); // amankan karakter spesial
            $html_jamaah .= '<li data-list="ordered"><span class="ql-ui" contenteditable="false"></span>' . $safe_item . '</li>';
        }
        $html_jamaah .= "</ol>";

        // Bangun HTML list Pesanan
        $html_pesanan = "<ol>";
        foreach ($items2 as $item2) {
            $safe_item2 = htmlspecialchars($item2, ENT_QUOTES, 'UTF-8'); // amankan karakter spesial
            $html_pesanan .= '<li data-list="ordered"><span class="ql-ui" contenteditable="false"></span>' . $safe_item2 . '</li>';
        }
        $html_pesanan .= "</ol>";


        // INSERT KODE PREPAYMENT SAAT SUBMIT
        $date = $this->input->post('tgl_invoice');

        $kode = $this->M_pu_invoice->max_kode($date)->row();
        $kode_order = $this->M_pu_invoice->max_kode_order($date)->row();

        if (empty($kode->kode_invoice)) {
            $no_urut = 1;
        } else {
            $bln = substr($kode->kode_invoice, 5, 2);
            $no_urut = substr($kode->kode_invoice, 10) + 1;
        }

        if (empty($kode_order->kode_order)) {
            $no_urut_order = 1;
        } else {
            $no_urut_order = substr($kode_order->kode_order, 6) + 1;
        }

        $urutan = str_pad($no_urut, 4, "0", STR_PAD_LEFT);
        $urutan_order = str_pad($no_urut_order, 4, "0", STR_PAD_LEFT);
        $year = substr($date, 8, 2);
        $month = substr($date, 3, 2);

        $kode_order = 'ORD' . $year . $urutan_order;
        $kode_invoice = 'INVPU' . $year . $month . $urutan;

        // Ambil diskon dari input (pastikan sudah diambil dan di-clean)
        $diskon = preg_replace('/\D/', '', $this->input->post('diskon'));
        $total_order_input = preg_replace('/\D/', '', $this->input->post('total_order'));

        // Hitung total order setelah diskon
        if (!empty($diskon)) {
            $total_order = $total_order_input - $diskon;
        } else {
            $total_order = $total_order_input;
        }

        $data = array(
            'tgl_invoice' => date('Y-m-d', strtotime($this->input->post('tgl_invoice'))),
            'kode_invoice' => $kode_invoice,
            'total_order' => $total_order,
            'diskon' => $diskon,
            'tgl_tempo' => date('Y-m-d', strtotime($this->input->post('tgl_tempo'))),
            'ctc_nama' => $this->input->post('ctc_nama'),
            'ctc_email' => $this->input->post('ctc_email'),
            'ctc_alamat' => $this->input->post('ctc_alamat'),
            'travel_id' => $this->input->post('rekening'),
            'created_at' => date('Y-m-d H:i:s')
        );

        if (!empty($this->input->post('jamaah'))) {
            $data['jamaah'] = $html_jamaah;
        } else {
            echo json_encode(array("status" => FALSE, "message" => "Jamaah tidak boleh kosong"));
            exit;
        }

        if (!empty($this->input->post('pesanan'))) {
            $data['detail_pesanan'] = $html_pesanan;
        } else {
            echo json_encode(array("status" => FALSE, "message" => "Pesanan tidak boleh kosong"));
            exit;
        }

        if (!empty($_POST['catatan_item'])) {
            $data['keterangan'] = $this->input->post('catatan_item');
        }
        // if (!empty($_POST['diskon'])) {
        //     $data['diskon'] = $this->input->post('diskon');
        // }

        // INISIASI VARIABEL UNTUK MENGHITUNG TOTAL TAGIHAN
        $grand_total = 0;
        $deskripsi = $this->input->post('deskripsi[]');
        $jumlah = preg_replace('/\D/', '', $this->input->post('jumlah[]'));
        $harga = preg_replace('/\D/', '', $this->input->post('harga[]'));
        $total = preg_replace('/\D/', '', $this->input->post('total[]'));

        if (!empty($deskripsi) && !empty($jumlah) && !empty($harga) && !empty($total)) {
            for ($i = 1; $i <= count($deskripsi); $i++) {
                $grand_total += (int)$total[$i]; // Menjumlahkan total
                $jml = (int)preg_replace('/\D/', '', $jumlah[$i]);
                $hrg = (int)preg_replace('/\D/', '', $harga[$i]);
                $ttl = (int)preg_replace('/\D/', '', $total[$i]);

                if ($jml * $hrg != $ttl) {
                    echo json_encode(array("status" => FALSE, "message" => "Harga dan total tidak sesuai"));
                    exit();
                }
            }
            if ($grand_total > $total_order) {
                echo json_encode(array("status" => FALSE, "message" => "Total tagihan tidak boleh lebih besar dari total order"));
                exit();
            }
            $data['total_tagihan'] = $grand_total;
        } else {
            echo json_encode(array("status" => FALSE, "message" => "Data detail invoice tidak boleh kosong"));
            exit();
        }

        if (empty($this->input->post('id'))) {
            $order = array(
                'kode_order' => $kode_order,
                'total_order' => $total_order,
                'created_at' => date('Y-m-d', strtotime($this->input->post('tgl_invoice')))
            );
            $this->db->insert('pu_order', $order);
            $order_insert = $this->db->insert_id();
        } else {
            $order_insert = $this->input->post('order_insert');
        }

        if (!empty($this->input->post('id'))) {
            $this->db->update('pu_invoice', ['is_active' => 2], ['id' => $this->input->post('id')]);
        }

        $data['order_id'] = $order_insert;

        $inserted = $this->M_pu_invoice->save($data);

        if ($inserted) {
            //PERULANGAN UNTUK INSER QUERY DETAIL PREPAYMENT
            if (!empty($deskripsi) && !empty($jumlah) && !empty($harga) && !empty($total)) {
                for ($i = 1; $i <= count($deskripsi); $i++) {
                    $data3[] = array(
                        'invoice_id' => $inserted,
                        'deskripsi' => $deskripsi[$i],
                        'jumlah' => $jumlah[$i],
                        'harga' => $harga[$i],
                        'total' => $total[$i]
                    );
                }
                $this->M_pu_invoice->save_detail2($data3);
            }
        }

        echo json_encode(array("status" => TRUE));
    }

    // ADD KWITANSI
    public function add_kwitansi()
    {
        $total = $this->db->select('order_id, total_order, total_tagihan')
            ->from('pu_invoice')
            ->where('id', $this->input->post('invoice_id'))
            ->get()
            ->row();

        $sisa_tagihan = ($row = $this->db->select('sisa_tagihan')->from('pu_kwitansi')->where('id_invoice', $this->input->post('invoice_id'))->order_by('id', 'DESC')->limit(1)->get()->row()) ? $row->sisa_tagihan : 0;

        $nominal_dibayar = preg_replace('/\D/', '', $this->input->post('nominal_dibayar'));

        // ERROR HANDLING UNTUK NOMINAL DIBAYAR
        if ($sisa_tagihan > 0 && $nominal_dibayar > $sisa_tagihan) {
            echo json_encode(array("status" => FALSE, "message" => "Nominal yang dibayarkan tidak sesuai dengan Invoice"));
            return;
        } else if ($nominal_dibayar > $total->total_tagihan) {
            echo json_encode(array("status" => FALSE, "message" => "Nominal yang dibayarkan tidak sesuai dengan Invoice"));
            return;
        }

        // ERROR HANDLING UNTUK TANGGAL KOSONG
        if (empty($this->input->post('tanggal_pembayaran'))) {
            echo json_encode(array("status" => FALSE, "message" => "Mohon Input Tanggal Pembayaran"));
            return;
        }

        if (empty($this->input->post('nominal_dibayar'))) {
            echo json_encode(array("status" => FALSE, "message" => "Nominal Bayar Harus Diisi"));
            return;
        }

        if (empty($this->input->post('keterangan'))) {
            echo json_encode(array("status" => FALSE, "message" => "Keterangan Harus Diisi"));
            return;
        }

        if (empty($this->input->post('status_pembayaran'))) {
            echo json_encode(array("status" => FALSE, "message" => "Status Pembayaran Harus Diisi"));
            return;
        }

        // Handle upload bukti_pembayaran
        if (empty($_FILES['bukti_pembayaran']['name'])) {
            echo json_encode(array("status" => FALSE, "message" => "Bukti pembayaran harus diupload"));
            return;
        }

        $config['upload_path'] = FCPATH . 'assets/backend/uploads/bukti_pembayaran_pu/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size'] = 8072; // 3MB dalam KB
        $config['encrypt_name'] = TRUE;

        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, true);
        }

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('bukti_pembayaran')) {
            $upload_data = $this->upload->data();
            $bukti_pembayaran = $upload_data['file_name'];
            // Kompres gambar
            $config['image_library'] = 'gd2';
            $config['source_image'] = $upload_data['full_path'];
            $config['create_thumb'] = FALSE;
            $config['maintain_ratio'] = TRUE;
            $config['quality'] = '80';
            $config['width'] = 1400;
            $config['height'] = 1400;

            $this->load->library('image_lib', $config);
            $this->image_lib->resize();
            $this->image_lib->clear();
        } else {
            echo json_encode(array("status" => FALSE, "message" => strip_tags($this->upload->display_errors())));
            return;
        }

        if ($sisa_tagihan > 0) {
            $new_total_tagihan = $sisa_tagihan - $nominal_dibayar;
        } else {
            $new_total_tagihan = $total->total_tagihan - $nominal_dibayar;
        }



        $data = array(
            'id_invoice' => $this->input->post('invoice_id'),
            'tanggal_pembayaran' => date('Y-m-d', strtotime($this->input->post('tanggal_pembayaran'))),
            'status_pembayaran' => $this->input->post('status_pembayaran'),
            'nominal_dibayar' => $nominal_dibayar,
            'sisa_tagihan' => $new_total_tagihan,
            'ctc_email' => $this->input->post('email'),
            'keterangan' => $this->input->post('keterangan'),
            'created_at' => date('Y-m-d H:i:s'),
            'bukti_pembayaran' => $bukti_pembayaran
        );


        if ($this->M_pu_invoice->save_kwitansi($data)) {
            // Update invoice setelah pembayaran
            $invoice_update = array(
                'is_active' => 0,
                'status' => ($new_total_tagihan == 0) ? 0 : 1,
            );

            $this->db->where('id', $this->input->post('invoice_id'));
            $this->db->update('pu_invoice', $invoice_update);

            if ($total->total_order == $total->total_tagihan && $new_total_tagihan == 0) {
                $this->db->where('id', $total->order_id)->update('pu_order', ['status' => 0]);
            }

            echo json_encode(array("status" => TRUE, "message" => "Kwitansi berhasil disimpan"));
        } else {
            echo json_encode(array("status" => FALSE, "message" => "Data kwitansi tidak boleh kosong"));
        }
    }

    // UPDATE KWITANSI
    public function update_kwitansi()
    {
        $kwitansi = $this->db->select('nominal_dibayar, sisa_tagihan')
            ->from('pu_kwitansi')
            ->where('id', $this->input->post('tgl_update_pembayaran'))
            ->get()
            ->row();

        $nominal_dibayar = preg_replace('/\D/', '', $this->input->post('nominal_dibayar'));
        if (empty($nominal_dibayar) || $nominal_dibayar == 0) {
            echo json_encode(array("status" => FALSE, "message" => "Nominal dibayar tidak boleh kosong atau 0"));
            return;
        }

        // ERROR HANDLING UNTUK TANGGAL KOSONG
        if (empty($this->input->post('tanggal_pembayaran'))) {
            echo json_encode(array("status" => FALSE, "message" => "Mohon Input Tanggal Pembayaran"));
            return;
        }

        if (empty($this->input->post('nominal_dibayar'))) {
            echo json_encode(array("status" => FALSE, "message" => "Nominal Bayar Harus Diisi"));
            return;
        }

        if (empty($this->input->post('keterangan'))) {
            echo json_encode(array("status" => FALSE, "message" => "Keterangan Harus Diisi"));
            return;
        }

        if (empty($this->input->post('status_pembayaran'))) {
            echo json_encode(array("status" => FALSE, "message" => "Status Pembayaran Harus Diisi"));
            return;
        }


        $data = array(
            'id_invoice' => $this->input->post('invoice_id'),
            'tanggal_pembayaran' => date('Y-m-d', strtotime($this->input->post('tanggal_pembayaran'))),
            'status_pembayaran' => $this->input->post('status_pembayaran'),
            'nominal_dibayar' => $nominal_dibayar,
            'keterangan' => $this->input->post('keterangan'),
        );

        if ($nominal_dibayar > ((int)$kwitansi->sisa_tagihan + (int)$kwitansi->nominal_dibayar)) {
            echo json_encode(array("status" => FALSE, "message" => "Total tagihan tidak boleh negatif"));
            exit;
        } else {
            $data['nominal_dibayar'] = $nominal_dibayar;
            $data['sisa_tagihan'] = ((int)$kwitansi->sisa_tagihan + (int)$kwitansi->nominal_dibayar) - $nominal_dibayar;
        }

        // Handle upload bukti_pembayaran
        if (!empty($_FILES['bukti_pembayaran']['name'])) {
            // Menghapus bukti pembayaran sebelumnya jika ada
            $bukti_sebelumnya = $this->db->get_where('pu_kwitansi', ['id' => $this->input->post('tgl_update_pembayaran')])->row('bukti_pembayaran');
            if ($bukti_sebelumnya) {
                $file_path = FCPATH . 'assets/backend/uploads/bukti_pembayaran_pu/' . $bukti_sebelumnya;
                if (file_exists($file_path)) {
                    @unlink($file_path);
                }
            }

            $config['upload_path'] = FCPATH . 'assets/backend/uploads/bukti_pembayaran_pu/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size'] = 3072; // 3MB dalam KB
            $config['encrypt_name'] = TRUE;

            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, true);
            }

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('bukti_pembayaran')) {
                $upload_data = $this->upload->data();
                $bukti_pembayaran = $upload_data['file_name'];
                // Kompres gambar
                $config['image_library'] = 'gd2';
                $config['source_image'] = $upload_data['full_path'];
                $config['create_thumb'] = FALSE;
                $config['maintain_ratio'] = TRUE;
                $config['quality'] = '80';
                $config['width'] = 1400;
                $config['height'] = 1400;

                $this->load->library('image_lib', $config);
                $this->image_lib->resize();
                $this->image_lib->clear();

                $data['bukti_pembayaran'] = $bukti_pembayaran;
            } else {
                echo json_encode(array("status" => FALSE, "message" => strip_tags($this->upload->display_errors())));
                return;
            }
        }

        $kwitansi_id = $this->input->post('tgl_update_pembayaran'); // diasumsikan sebagai ID kwitansi

        if (empty($kwitansi_id)) {
            echo json_encode(array("status" => FALSE, "message" => "ID kwitansi untuk update tidak ditemukan"));
            return;
        }

        $this->db->where('id', $kwitansi_id);
        $updated = $this->db->update('pu_kwitansi', $data);

        // var_dump();

        if ($updated) {
            // INISIASI SETELAH UPDATE
            $kwitansi2 = $this->db->select('nominal_dibayar, sisa_tagihan')
                ->from('pu_kwitansi')
                ->where('id', $this->input->post('tgl_update_pembayaran'))
                ->get()
                ->row();

            $invoice2 = $this->db
                ->select('total_tagihan, total_order, order_id')
                ->from('pu_invoice')
                ->where('id', $this->input->post('invoice_id'))
                ->get()
                ->row();

            if ($kwitansi2->sisa_tagihan > 0) {
                $new_total_tagihan = $kwitansi2->sisa_tagihan - $nominal_dibayar;
            } else {
                $new_total_tagihan = $invoice2->total_tagihan - $nominal_dibayar;
            }

            $data2 = [];

            // var_dump((int)$invoice->total_tagihan);

            if ($invoice2->total_order == $invoice2->total_tagihan && $new_total_tagihan == 0) {
                $data2['is_active'] = 2;
                $data2['status'] = 0;
                $this->db->where('id', $this->input->post('invoice_id'))->update('pu_invoice', $data2);
                $this->db->where('id', $invoice2->order_id)->update('pu_order', ['status' => 0]);
            } elseif ((int)$invoice2->total_tagihan == (((int)$kwitansi2->sisa_tagihan + (int)$kwitansi2->nominal_dibayar) - (int)$nominal_dibayar)) {
                $data2['status'] = 0;
                $data2['is_active'] = 2;
                $this->db->where('id', $this->input->post('invoice_id'))->update('pu_invoice', $data2);
            } elseif ($kwitansi2->sisa_tagihan == 0) {
                $data2['status'] = 0;
                $data2['is_active'] = 0;
                $this->db->where('id', $this->input->post('invoice_id'))->update('pu_invoice', $data2);
            }

            echo json_encode(array("status" => TRUE, "message" => "Kwitansi berhasil diperbarui"));
        } else {
            echo json_encode(array("status" => FALSE, "message" => "Gagal memperbarui kwitansi"));
        }
    }


    // UPDATE DATA
    public function update()
    {
        // INISIASI JAMAAH DAN PESANAN
        $raw_jamaah = $this->input->post('jamaah'); // hasil: "lalaland, testing"
        $raw_pesanan = $this->input->post('pesanan');

        // Pecah berdasarkan koma, lalu bersihkan spasi
        $items = array_filter(array_map('trim', explode(',', $raw_jamaah)));
        $items2 = array_filter(array_map('trim', explode(',', $raw_pesanan)));

        // Bangun HTML list Jamaah
        $html_jamaah = "<ol>";
        foreach ($items as $item) {
            $safe_item = htmlspecialchars($item, ENT_QUOTES, 'UTF-8'); // amankan karakter spesial
            $html_jamaah .= '<li data-list="ordered"><span class="ql-ui" contenteditable="false"></span>' . $safe_item . '</li>';
        }
        $html_jamaah .= "</ol>";

        // Bangun HTML list Pesanan
        $html_pesanan = "<ol>";
        foreach ($items2 as $item2) {
            $safe_item2 = htmlspecialchars($item2, ENT_QUOTES, 'UTF-8'); // amankan karakter spesial
            $html_pesanan .= '<li data-list="ordered"><span class="ql-ui" contenteditable="false"></span>' . $safe_item2 . '</li>';
        }
        $html_pesanan .= "</ol>";

        $diskon = preg_replace('/\D/', '', $this->input->post('diskon'));
        $total_order_input = preg_replace('/\D/', '', $this->input->post('total_order'));

        if (!empty($diskon) && $diskon > 0) {
            $total_order = $total_order_input - $diskon;
        } else {
            $total_order = $total_order_input;
        }

        $data = array(
            'tgl_invoice' => date('Y-m-d', strtotime($this->input->post('tgl_invoice'))),
            'total_order' => $total_order,
            'diskon' => $diskon,
            'tgl_tempo' => date('Y-m-d', strtotime($this->input->post('tgl_tempo'))),
            'ctc_nama' => $this->input->post('ctc_nama'),
            'ctc_email' => $this->input->post('ctc_email'),
            'ctc_alamat' => $this->input->post('ctc_alamat'),
            'detail_pesanan' => $this->input->post('pesanan_item'),
            'travel_id' => $this->input->post('rekening'),
            'created_at' => date('Y-m-d H:i:s')
        );

        if (!empty($this->input->post('jamaah'))) {
            $data['jamaah'] = $html_jamaah;
        } else {
            echo json_encode(array("status" => FALSE, "message" => "Jamaah tidak boleh kosong"));
        }

        if (!empty($this->input->post('pesanan'))) {
            $data['detail_pesanan'] = $html_pesanan;
        } else {
            echo json_encode(array("status" => FALSE, "message" => "Pesanan tidak boleh kosong"));
        }

        if (!empty($_POST['catatan_item'])) {
            $data['keterangan'] = $this->input->post('catatan_item');
        }

        // INISIASI VARIABEL UNTUK MENGHITUNG TOTAL TAGIHAN
        $grand_total = 0;
        $deskripsi = $this->input->post('deskripsi[]');
        $jumlah = preg_replace('/\D/', '', $this->input->post('jumlah[]'));
        $harga = preg_replace('/\D/', '', $this->input->post('harga[]'));
        $total = preg_replace('/\D/', '', $this->input->post('total[]'));

        if (!empty($deskripsi) && !empty($jumlah) && !empty($harga) && !empty($total)) {
            for ($i = 1; $i <= count($deskripsi); $i++) {
                $jml = (int)preg_replace('/\D/', '', $jumlah[$i]);
                $hrg = (int)preg_replace('/\D/', '', $harga[$i]);
                $ttl = (int)preg_replace('/\D/', '', $total[$i]);
                $grand_total += (int)$total[$i]; // Menjumlahkan total

                if ($jml * $hrg != $ttl) {
                    echo json_encode(array("status" => FALSE, "message" => "Harga dan total tidak sesuai"));
                    exit();
                }
            }
            if ($grand_total > $total_order) {
                echo json_encode(array("status" => FALSE, "message" => "Total tagihan tidak boleh lebih besar dari total order"));
                exit();
            }
            $data['total_tagihan'] = $grand_total;
        } else {
            echo json_encode(array("status" => FALSE, "message" => "Data detail invoice tidak boleh kosong"));
            exit();
        }

        if (!empty($this->input->post('id'))) {
            $order_id = $this->db->from('pu_invoice')
                ->where('id', $this->input->post('id'))
                ->get()
                ->row('order_id');

            $order = array(
                'total_order' => $total_order,
            );

            $this->db->where('id', $order_id);
            $this->db->update('pu_order', $order);
        }


        //UPDATE DETAIL PREPAYMENT
        $id_detail = $this->input->post('hidden_id[]');
        // $invoice_id = $this->input->post('hidden_invoiceId[]');
        if ($this->db->update('pu_invoice', $data, ['id' => $this->input->post('id')])) {
            // UNTUK MENGHAPUS ROW YANG TELAH DIDELETE
            $deletedRows = json_decode($this->input->post('deleted_rows'), true);
            if (!empty($deletedRows)) {
                foreach ($deletedRows as $delRows) {
                    // Hapus row dari database berdasarkan ID
                    $this->db->where('id', $delRows);
                    $this->db->delete('pu_detail_invoice');
                }
            }

            //MELAKUKAN REPLACE DATA LAMA DENGAN YANG BARU
            for ($i = 1; $i <= count($_POST['deskripsi']); $i++) {
                // Set id menjadi NULL jika id_detail tidak ada atau kosong
                $id_invoice = !empty($id_detail[$i]) ? $id_detail[$i] : NULL;
                $data2[] = array(
                    'id' => $id_invoice,
                    'invoice_id' => $this->input->post('id'),
                    'deskripsi' => $deskripsi[$i],
                    'jumlah' => $jumlah[$i],
                    'harga' => $harga[$i],
                    'total' => $total[$i]
                );
                // Menggunakan db->replace untuk memasukkan atau menggantikan data
                $this->db->replace('pu_detail_invoice', $data2[$i - 1]);
            }
        }
        echo json_encode(array("status" => TRUE));
    }

    // MENGHAPUS DATA
    function delete($id)
    {
        // MENGHITUNG JUMLAH DATA YANG MEMILIKI NO ORDER YANG SAMA
        $no_order = $this->db->select('order_id')->from('pu_invoice')->where('id', $id)->get()->row('order_id');

        $jumlah_order = $this->db->from('pu_invoice')->where('order_id', $no_order)->get()->num_rows();

        if (!($jumlah_order > 1)) {
            $this->M_pu_invoice->delete_order($no_order);
        }

        $delete = $this->M_pu_invoice->delete($id);
        if ($delete) {
            $pu_id = $this->db->from('pu_invoice')->where('order_id', $no_order)->order_by('id', 'DESC')->limit(1)->get()->row('id');
            $this->db->where('id', $pu_id)->update('pu_invoice', ['is_active' => 0]);
            echo json_encode(array("status" => TRUE));
        } else {
            echo json_encode(array("status" => FALSE));
        }

        // $bukti_pembayaran = $this->db->get_where('pu_kwitansi', ['id' => $id])->row('bukti_pembayaran');
        // if ($bukti_pembayaran) {
        //     $file_path = FCPATH . 'assets/backend/uploads/bukti_pembayaran_pu/' . $bukti_pembayaran;
        //     if (file_exists($file_path)) {
        //         @unlink($file_path);
        //     }
        // }
    }

    public function prepare_print_invoice()
    {
        $id_invoice = $this->input->post('id_invoice');
        $id = $this->input->post('id');
        $data = [
            'id_invoice' => $id_invoice
        ];
        if (!empty($id)) {
            $data['id'] = $id;
        }
        return $this->generate_pdf_invoice($data);
    }

    public function prepare_print_kwitansi()
    {
        $id = $this->input->post('id');
        $data = [
            'id' => $id,
        ];
        return $this->generate_pdf_kwitansi($data);
    }

    //SEND EMAIL
    public function send_email_invoice()
    {
        $email = $this->input->post('email');
        $this->load->library('tcpdf'); // Pastikan library TCPDF sudah di-autoload atau dimuat manual
        $this->load->library('tcpdf_invoice'); // Library untuk generate invoice PDF
        $id = $this->input->post('id');
        $id_invoice = $this->input->post('id_invoice');

        $config = [
            'protocol'    => 'smtp',
            'smtp_host'   => 'ssl://smtp.hostinger.com',
            'smtp_user'   => 'cs@pengenumroh.com',
            'smtp_pass'   => '@Adminkps123',
            'smtp_port'   => 465,
            'mailtype'    => 'html',
            'charset'     => 'utf-8',
            'newline'     => "\r\n",
            'crlf'        => "\r\n",
            'smtp_timeout' => 30,
            'wordwrap'    => TRUE
        ];

        // $config = [
        //     'protocol'    => 'smtp',
        //     'smtp_host'   => 'ssl://smtp.gmail.com',
        //     'smtp_user'   => 'audricafabiano@gmail.com',
        //     'smtp_pass'   => 'qwqy rojd ugkr mrpz',
        //     'smtp_port'   => 465,
        //     'mailtype'    => 'html',
        //     'charset'     => 'utf-8',
        //     'newline'     => "\r\n",
        //     'crlf'        => "\r\n",
        //     'smtp_timeout' => 30,
        //     'wordwrap'    => TRUE
        // ];

        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");

        $data = [
            'id_invoice'      => $id_invoice,
            'output'  => 'send',
        ];

        if (!empty($id)) {
            $data['id'] = $id;
        };

        // Generate PDF content dari TCPDF
        $pdf_content = $this->generate_pdf_invoice($data);

        // Simpan sementara file PDF di server
        $pdf_path = FCPATH . 'assets/backend/uploads/invoicePU/Invoice_PengenUmroh.pdf';
        file_put_contents($pdf_path, $pdf_content);

        // Email body (HTML)
        $message_body = '
        <p>Dear Customer,</p>

        <p>Melalui email ini, kami ingin menginformasikan bahwa pelunasan atas invoice yang telah diterbitkan telah kami selesaikan. Mohon dapat dicek kembali dan dikonfirmasi apabila telah diterima dengan baik.</p>

        <p>Apabila terdapat pertanyaan atau hal lain yang perlu dikomunikasikan lebih lanjut, silakan menghubungi kami melalui email atau nomor telepon di bawah ini.</p>

        <p>Terima kasih atas kerja samanya.</p>

        <br>

        <p>Best regards,</p>
        <p><strong>Tim Pengenumroh</strong><br>
        Email: <a href="mailto:cs@pengenumroh.com">cs@pengenumroh.com</a><br>
        WhatsApp: </p>
        ';

        $this->email->from('cs@pengenumroh.com', 'Pengenumroh');
        $this->email->to($email);
        $this->email->subject('Kwitansi Invoice');
        $this->email->message($message_body);

        // Attach PDF
        $this->email->attach($pdf_path);

        if ($this->email->send()) {
            echo json_encode(["status" => true, "message" => "Berhasil dikirim dengan PDF"]);
            // (Opsional) hapus file PDF setelah dikirim
            unlink($pdf_path);
        } else {
            echo json_encode(["status" => false, "message" => $this->email->print_debugger()]);
        }
    }

    //SEND EMAIL
    public function send_email_kwitansi()
    {
        $email = $this->input->post('email');
        $this->load->library('tcpdf'); // Pastikan library TCPDF sudah di-autoload atau dimuat manual
        $this->load->library('tcpdf_invoice'); // Library untuk generate invoice PDF
        $id = $this->input->post('id');

        $config = [
            'protocol'    => 'smtp',
            'smtp_host'   => 'ssl://smtp.hostinger.com',
            'smtp_user'   => 'cs@pengenumroh.com',
            'smtp_pass'   => '@Adminkps123',
            'smtp_port'   => 465,
            'mailtype'    => 'html',
            'charset'     => 'utf-8',
            'newline'     => "\r\n",
            'crlf'        => "\r\n",
            'smtp_timeout' => 30,
            'wordwrap'    => TRUE
        ];

        // $config = [
        //     'protocol'    => 'smtp',
        //     'smtp_host'   => 'ssl://smtp.gmail.com',
        //     'smtp_user'   => 'audricafabiano@gmail.com',
        //     'smtp_pass'   => 'qwqy rojd ugkr mrpz',
        //     'smtp_port'   => 465,
        //     'mailtype'    => 'html',
        //     'charset'     => 'utf-8',
        //     'newline'     => "\r\n",
        //     'crlf'        => "\r\n",
        //     'smtp_timeout' => 30,
        //     'wordwrap'    => TRUE
        // ];

        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");

        $data = [
            'id'      => $id,
            'output'  => 'send',
        ];

        // Generate PDF content dari TCPDF
        $pdf_content = $this->generate_pdf_kwitansi($data);

        // Simpan sementara file PDF di server
        $pdf_path = FCPATH . 'assets/backend/uploads/kwitansiPU/Kwitansi_PengenUmroh.pdf';
        file_put_contents($pdf_path, $pdf_content);

        // Email body (HTML)
        $message_body = '
            <p>Dear Customer,</p>

            <p>Melalui email ini, kami ingin menginformasikan bahwa kwitansi atas pembayaran yang telah Anda lakukan telah kami terbitkan dan terlampir dalam email ini. Mohon untuk memeriksa dokumen tersebut dan mengonfirmasi apabila telah diterima dengan baik.</p>

            <p>Apabila terdapat pertanyaan lebih lanjut atau hal lain yang perlu didiskusikan, silakan hubungi kami melalui email atau nomor WhatsApp di bawah ini.</p>

            <p>Terima kasih atas kepercayaan dan kerja samanya.</p>

            <br>

            <p>Best regards,</p>
            <p><strong>Tim Pengenumroh</strong><br>
            Email: <a href="mailto:cs@pengenumroh.com">cs@pengenumroh.com</a><br>
            WhatsApp: 0812-xxxx-xxxx</p>
        ';


        $this->email->from('cs@pengenumroh.com', 'Pengenumroh');
        $this->email->to($email);
        $this->email->subject('Pengenumroh Kwitansi');
        $this->email->message($message_body);

        // Attach PDF
        $this->email->attach($pdf_path);

        if ($this->email->send()) {
            echo json_encode(["status" => true, "message" => "Berhasil dikirim dengan PDF"]);
            // (Opsional) hapus file PDF setelah dikirim
            unlink($pdf_path);
        } else {
            echo json_encode(["status" => false, "message" => $this->email->print_debugger()]);
        }
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

    // PRINTOUT TCPDF INVOICE
    public function generate_pdf_invoice($data)
    {
        // INISIAI VARIABLE
        $invoice = $this->M_pu_invoice->get_by_id($data['id_invoice']);
        $invoice_details = $this->M_pu_invoice->get_detail($data['id_invoice']);
        $invoice_rek = $this->db->where('id', $invoice->travel_id)->get('pu_travel')->row();
        $this->db->select_sum('nominal_dibayar');
        $this->db->where('id_invoice', $data['id_invoice']);
        $total_nominal_dibayar = $this->db->get('pu_kwitansi')->row()->nominal_dibayar;
        if (!empty($data['id'])) {
            $kwitansi_json = $this->get_kwitansi($data);
            $kwitansi = json_decode($kwitansi_json);
        }

        // Initialize the TCPDF object
        $t_cpdf2 = new t_cpdf2('P', 'mm', 'A4', true, 'UTF-8', false);

        // Set document properties
        $t_cpdf2->SetCreator(PDF_CREATOR);
        $t_cpdf2->SetAuthor('Author Name');
        $t_cpdf2->SetTitle('Invoice Pengenumroh PDF');

        $t_cpdf2->SetMargins(15, 40, 15); // Margin kiri, atas (untuk header), kanan
        $t_cpdf2->SetAutoPageBreak(true, 15); // Penanganan otomatis margin bawah

        // Add a new page
        $t_cpdf2->AddPage();

        $t_cpdf2->SetY($t_cpdf2->getMargins()['top']);

        // Pilih font untuk isi
        $t_cpdf2->SetFont('Poppins-Bold', '', 15);
        $t_cpdf2->Cell(100, 10, 'PT. KOLABORASI PARA SAHABAT', 0, 0, 'L');

        $t_cpdf2->SetFont('Poppins-Bold', '', 22);
        $t_cpdf2->SetX(164);
        $t_cpdf2->Cell(100, 10, 'INVOICE', 0, 1, 'L');

        $t_cpdf2->SetFont('Poppins-Regular', '', 11);
        $t_cpdf2->SetX(114);
        $t_cpdf2->Cell(45, 10, 'No. Invoice', 0, 0, 'R');
        $t_cpdf2->Cell(20, 10, ':', 0, 0);
        $t_cpdf2->Cell(19, 10, $invoice->kode_invoice, 0, 0, 'R');

        $t_cpdf2->SetY($t_cpdf2->GetY());
        $t_cpdf2->SetX(114);
        $t_cpdf2->SetFont('Poppins-Regular', '', 10);
        $t_cpdf2->Cell(45, 23, 'Tanggal Invoice', 0, 0, 'R');
        $t_cpdf2->Cell(20, 23, ':', 0, 0);
        $t_cpdf2->Cell(19, 23, $this->tgl_indo(date('Y-m-j', strtotime($invoice->tgl_invoice))), 0, 0, 'R');
        $t_cpdf2->SetY($t_cpdf2->GetY() + 13);
        $t_cpdf2->SetX(114);
        $t_cpdf2->SetFont('Poppins-Bold', '', 14);
        $t_cpdf2->SetFont('Poppins-Regular', '', 10);
        $t_cpdf2->Cell(45, 10, 'Tanggal Jatuh Tempo', 0, 0, 'R');
        $t_cpdf2->Cell(20, 10, ':', 0, 0);
        $t_cpdf2->Cell(19, 10, $this->tgl_indo(date('Y-m-j', strtotime($invoice->tgl_tempo))), 0, 1, 'R');

        $total = 0;
        $diskon = $invoice->diskon;
        foreach ($invoice_details as $detail) {
            if ($diskon != 0) {
                $total += $detail->total - ($detail->total * $diskon / 100);
            } else {
                $total += $detail->total;
            }
        }

        $t_cpdf2->SetY($t_cpdf2->GetY() + 2);
        // HEADER DATA PEMESAN
        $t_cpdf2->SetFont('Poppins-Regular', '', 11);
        $t_cpdf2->SetFillColor(252, 118, 19);
        $t_cpdf2->SetTextColor(255, 255, 255);
        $t_cpdf2->SetX(15);
        $t_cpdf2->Cell(182, 10, 'Data Pemesan', 0, 0, 'L', true);
        $t_cpdf2->Cell(0, 10, 'Jumlah Yang Harus Dibayar', 0, 1, 'R', true);
        $t_cpdf2->SetTextColor(0, 0, 0);

        // CONTENT DDATA PEMESAN
        $t_cpdf2->SetFont('Poppins-Regular', '', 10);
        $t_cpdf2->SetY($t_cpdf2->GetY() + 3);
        $t_cpdf2->SetX(15);
        $t_cpdf2->Cell(0, 9, 'Kpd Yth.', 0, 1);

        $t_cpdf2->SetFont('Poppins-Regular', 'B', 16);
        $t_cpdf2->SetX(15);
        $t_cpdf2->Cell(0, 0, $invoice->ctc_nama, 0, 0);
        $t_cpdf2->Cell(0, 0, "Rp. " . number_format($total ?? 0, 0, ',', '.'), 0, 1, 'R');

        $t_cpdf2->SetFont('Poppins-Regular', '', 10);
        $t_cpdf2->SetY($t_cpdf2->GetY() + 1);
        $t_cpdf2->SetX(15);
        $t_cpdf2->Cell(0, 8, 'Alamat', 0, 0);
        $t_cpdf2->SetY($t_cpdf2->GetY());
        $t_cpdf2->SetX(29);
        $t_cpdf2->Cell(0, 8, ':', 0, 0);
        $t_cpdf2->SetY($t_cpdf2->GetY() + 2);
        $t_cpdf2->SetX(31);
        $t_cpdf2->MultiCell(58, 10, $invoice->ctc_alamat, 0, 'L');

        $t_cpdf2->SetY($t_cpdf2->GetY() + 2);
        // HEADER DETAIL JAMAAH
        $t_cpdf2->SetFont('Poppins-Regular', '', 11);
        $t_cpdf2->SetFillColor(252, 118, 19);
        $t_cpdf2->SetTextColor(255, 255, 255);
        $t_cpdf2->SetX(15);
        $t_cpdf2->Cell(182, 10, 'Data Jamaah', 0, 0, 'L', true);
        $t_cpdf2->Cell(0, 10, 'Detail Pesanan', 0, 1, 'R', true);
        $t_cpdf2->SetTextColor(0, 0, 0);

        // CONTENT DATA JAMAAH
        // ========== KOLOM KIRI =============
        $t_cpdf2->SetFont('Poppins-Regular', '', 10);
        $t_cpdf2->SetY($t_cpdf2->GetY() + 8); // Tambah jarak aman dari header
        $t_cpdf2->writeHTMLCell(
            80,
            12,
            4,
            $t_cpdf2->GetY() - 3,
            $invoice->jamaah,
            0,
            1,
            false,
            true,
            'L',
            true // Perhatikan: 1 untuk line break!
        );
        $y_kiri = $t_cpdf2->GetY(); // Ambil Y setelah tulis kolom kiri

        // ========== KOLOM KANAN =============
        $t_cpdf2->SetFont('Poppins-Regular', '', 10);
        $t_cpdf2->SetY($t_cpdf2->GetY() - ($t_cpdf2->getLastH() ?? 0)); // Kembali ke posisi Y semula
        $t_cpdf2->writeHTMLCell(
            65,
            0,
            130,
            $t_cpdf2->GetY(),
            $invoice->detail_pesanan,
            0,
            1,
            false,
            true,
            'R',
            true
        );
        $y_kanan = $t_cpdf2->GetY(); // Ambil Y setelah tulis kolom kanan

        // ========== ATUR Y UNTUK KONTEN BERIKUTNYA ===========
        $t_cpdf2->SetY(max($y_kiri, $y_kanan) + 2); // Gunakan yang paling bawah + jarak


        // HEADER DETAIL PEMESANAN
        $t_cpdf2->SetFont('Poppins-Regular', '', 11);
        $t_cpdf2->SetFillColor(252, 118, 19);
        $t_cpdf2->SetTextColor(255, 255, 255);
        $t_cpdf2->SetX(15);
        $t_cpdf2->Cell(182, 10, 'Detail Pemesanan', 0, 1, 'L', true);
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
    EOD;
        foreach ($invoice_details as $detail) {
            $tbl .= '<tr>';
            $tbl .= '<td width="30%">' . $detail->deskripsi . '</td>';
            $tbl .= '<td width="20%" style="text-align: center">' . $detail->jumlah . '</td>';
            $tbl .= '<td width="25%" style="text-align: center">' . 'Rp. ' . number_format($detail->harga ?? 0, 0, ',', '.') . '</td>';
            $tbl .= '<td width="25%" style="text-align: center">' . 'Rp. ' . number_format($detail->total ?? 0, 0, ',', '.') . '</td>';
            $tbl .= '</tr>';
        }
        $tbl .= <<<EOD
    </tbody>
</table>
EOD;

        $t_cpdf2->writeHTMLCell(184, 0, 14, $t_cpdf2->GetY() + 6, $tbl, 0, 1, false, true, 'L', true);

        $table2 = <<<EOD
            <table>
            <tbody>
            EOD;

        // $table2 .= '<tr style="border: none;">';
        // $table2 .= '<td colspan="2"></td>';
        // $table2 .= '<td width="25%" style="border: 1px solid black; text-align: center"> <b>Diskon</b></td>';
        // $table2 .= '<td width="25%" style="border: 1px solid black; text-align: center"> ' . $invoice->diskon .  '%</td>';
        // $table2 .= '</tr>';
        $table2 .= '<tr style="border: none;">';
        $table2 .= '<td colspan="2" style="border: none;"></td>';
        $table2 .= '<td width="25%" style="border: 1px solid black; text-align: center"><b>Total</b></td>';
        $table2 .= '<td width="25%" style="border: 1px solid black; text-align: center"> ' . 'Rp. ' . number_format($total ?? 0, 0, ',', '.') . '</td>';
        $table2 .= '</tr>';
        if (!empty($data['id']) && $data['id'] != 'default') {
            $table2 .= '<tr style="border: none;">';
            $table2 .= '<td colspan="2" style="border: none;"></td>';
            $table2 .= '<td width="25%" style="border: 1px solid black; text-align: center"><b>Telah terbayar</b></td>';
            $table2 .= '<td width="25%" style="border: 1px solid black; text-align: center">' . 'Rp. ' . number_format($kwitansi->total_nominal_dibayar ?? 0, 0, ',', '.') . '</td>';
            $table2 .= '</tr>';
            $table2 .= '<tr style="border: none;">';
            $table2 .= '<td colspan="2" style="border: none;"></td>';
            $table2 .= '<td width="25%" style="border: 1px solid black; text-align: center"><b>Sisa Tagihan</b></td>';
            $table2 .= '<td width="25%" style="border: 1px solid black; text-align: center">' . 'Rp. ' . number_format($kwitansi->kwitansi->sisa_tagihan ?? 0, 0, ',', '.') . '</td>';
            $table2 .= '</tr>';
        }
        $table2 .=  <<<EOD
            <tbody>
            </table>
        EOD;

        $t_cpdf2->writeHTMLCell(184, 0, 14, $t_cpdf2->GetY(), $table2, 0, 1, false, true, 'L', true);

        $t_cpdf2->SetY($t_cpdf2->GetY() + 3);
        $t_cpdf2->SetX(13);
        $t_cpdf2->SetFont('Poppins-Bold', '', 11);
        $t_cpdf2->Cell(0, 8, 'Metode Pembayaran', 0, 1);
        $t_cpdf2->SetX(13);
        $t_cpdf2->SetFont('Poppins-Bold', '', 10);
        $t_cpdf2->Cell(0, 5, 'Bank : ' . $invoice_rek->nama_bank, 0, 1);
        $t_cpdf2->SetX(13);
        $t_cpdf2->Cell(0, 8, 'No. Rekening : ' . $invoice_rek->no_rek, 0, 1);

        $t_cpdf2->SetY($t_cpdf2->GetY() - 1);
        $t_cpdf2->SetX(13);

        $t_cpdf2->SetFont('Poppins-Bold', '', 11);
        $t_cpdf2->Cell(0, 5, 'a/n : ' . $invoice_rek->perusahaan, 0, 1);

        $t_cpdf2->setY($t_cpdf2->GetY());
        $t_cpdf2->SetX(13);

        $t_cpdf2->SetFont('Poppins-Regular', '', 10);
        $t_cpdf2->Cell(19, 9, 'Catatan :', 0, 1);
        if ($invoice->keterangan != '') {
            $catatan = $invoice->keterangan;
        } else {
            $catatan = '';
        }

        $t_cpdf2->writeHTMLCell(0, 0, 13, $t_cpdf2->GetY(), $catatan, 0, 1, false, true, 'L', true);

        // Output PDF (tampilkan di browser)
        if (isset($data['output']) && $data['output'] === 'send') {
            // Simpan PDF ke file dan kembalikan kontennya
            $pdf_path = FCPATH . 'assets/backend/uploads/invoicePU/Invoice_PengenUmroh.pdf';
            $t_cpdf2->Output($pdf_path, 'F');
            return file_get_contents($pdf_path);
        } else {
            $t_cpdf2->Output('Invoice Pengenumroh ' . $invoice->kode_invoice . '.pdf', 'I');
        }
    }

    // TCPDF KWITANSI
    public function generate_pdf_kwitansi($data)
    {
        // INISIAI VARIABLE
        $kwitansi = $this->M_pu_invoice->get_kwitansi($data['id']);
        $invoice = $this->M_pu_invoice->get_by_id($kwitansi->id_invoice);
        $invoice_details = $this->db->where('invoice_id', $kwitansi->id_invoice)->get('pu_detail_invoice')->result_array();
        $nominal_dibayar_rows = $this->db->select('tanggal_pembayaran, nominal_dibayar')
            ->from('pu_kwitansi')
            ->where('id', $data['id'])
            ->get()
            ->row();

        // Initialize the TCPDF object
        $t_cpdf2 = new t_cpdf2('P', 'mm', 'A4', true, 'UTF-8', false);

        // Set document properties
        $t_cpdf2->SetCreator(PDF_CREATOR);
        $t_cpdf2->SetAuthor('Author Name');
        $t_cpdf2->SetTitle('Kwitansi Pengenumroh PDF');

        $t_cpdf2->SetMargins(15, 40, 15); // Margin kiri, atas (untuk header), kanan
        $t_cpdf2->SetAutoPageBreak(true, 15); // Penanganan otomatis margin bawah

        // Add a new page
        $t_cpdf2->AddPage();

        $t_cpdf2->SetY($t_cpdf2->getMargins()['top']);

        // Pilih font untuk isi
        $t_cpdf2->SetFont('Poppins-Bold', '', 15);
        $t_cpdf2->Cell(100, 10, 'BUKTI TERIMA PEMBAYARAN', 0, 1, 'L');

        $t_cpdf2->SetFont('Poppins-Regular', '', 11);
        // $t_cpdf2->SetX(114);
        $t_cpdf2->Cell(45, 9, 'No. Invoice', 0, 0, 'L');
        $t_cpdf2->Cell(3, 9, ':', 0, 0);
        $t_cpdf2->Cell(19, 9, $invoice->kode_invoice, 0, 0, 'L');

        $t_cpdf2->SetY($t_cpdf2->GetY());
        // $t_cpdf2->SetX(114);
        $t_cpdf2->SetFont('Poppins-Regular', '', 10);
        $t_cpdf2->Cell(45, 20, 'Tanggal Invoice', 0, 0, 'L');
        $t_cpdf2->Cell(3, 20, ':', 0, 0);
        $t_cpdf2->Cell(19, 20, $this->tgl_indo(date('Y-m-d', strtotime($invoice->tgl_invoice))), 0, 0, 'L');
        $t_cpdf2->SetY($t_cpdf2->GetY() + 13);
        // $t_cpdf2->SetX(114);
        $t_cpdf2->SetFont('Poppins-Bold', '', 14);
        $t_cpdf2->SetFont('Poppins-Regular', '', 10);
        $t_cpdf2->Cell(45, 5, 'Tanggal Jatuh Tempo', 0, 0, 'L');
        $t_cpdf2->Cell(3, 5, ':', 0, 0);
        $t_cpdf2->Cell(19, 5, $this->tgl_indo(date('Y-m-d', strtotime($invoice->tgl_tempo))), 0, 1, 'L');

        $t_cpdf2->SetY($t_cpdf2->GetY() + 2);
        // HEADER DATA PEMESAN
        $t_cpdf2->SetFont('Poppins-Regular', '', 11);
        $t_cpdf2->SetFillColor(252, 118, 19);
        $t_cpdf2->SetTextColor(255, 255, 255);
        $t_cpdf2->SetX(15);
        $t_cpdf2->Cell(139, 10, 'Data Pemesan', 0, 0, 'L', true);
        $t_cpdf2->Cell(43, 10, 'Status Pembayaran', 0, 1, 'L', true);
        $t_cpdf2->SetTextColor(0, 0, 0);

        // CONTENT DDATA PEMSAN
        $t_cpdf2->SetFont('Poppins-Regular', '', 10);
        $t_cpdf2->SetY($t_cpdf2->GetY() + 2);
        $t_cpdf2->SetX(15);
        $t_cpdf2->Cell(0, 0, 'Kpd Yth.', 0, 1);

        $t_cpdf2->SetFont('Poppins-Regular', 'B', 15);
        $t_cpdf2->SetX(15);
        $t_cpdf2->Cell(0, 0, $invoice->ctc_nama, 0, 0);
        $t_cpdf2->Cell(0, 0, strtoupper($kwitansi->status_pembayaran), 0, 1, 'R');

        $t_cpdf2->SetY($t_cpdf2->GetY() + 2);
        $t_cpdf2->SetY($t_cpdf2->GetY() + 2);
        // HEADER DETAIL PEMESANAN
        $t_cpdf2->SetFont('Poppins-Regular', '', 11);
        $t_cpdf2->SetFillColor(252, 118, 19);
        $t_cpdf2->SetTextColor(255, 255, 255);
        $t_cpdf2->SetX(15);
        $t_cpdf2->Cell(182, 10, 'Detail Pemesanan', 0, 1, 'L', true);
        $t_cpdf2->SetTextColor(0, 0, 0);

        $table2 = <<<EOD
            <table>
            <tbody>
            EOD;

        $total = 0;
        $diskon = $invoice->diskon;
        foreach ($invoice_details as $detail) {
            if ($diskon != 0) {
                $total += $detail['total'] - ($detail['total'] * $diskon / 100);
            } else {
                $total += $detail['total'];
            }
        }

        $table2 .= '<tr style="border: none;">';
        $table2 .= '<td width="15%" colspan="2" style="border: none; text-align: left">Banyak Uang</td>';
        $table2 .= '<td width="10px" style="border: none; text-align: center">:</td>';
        // foreach ($nominal_dibayar_rows as $key) {
        //     $table2 .= '<td width="83%" style="border: none; text-align: left"> ' . 'Rp. ' . number_format($key->nominal_dibayar ?? 0, 0, ',', '.') . '</td>';
        // }
        $table2 .= '<td width="83%" style="border: none; text-align: left"> ' . 'Rp. ' . number_format($nominal_dibayar_rows->nominal_dibayar ?? 0, 0, ',', '.') . '</td>';
        $table2 .= '</tr>';
        $table2 .= '<tr style="border: none;">';
        $table2 .= '<td width="15%" colspan="2" style="border: none; text-align: left">Keterangan</td>';
        $table2 .= '<td width="10px" style="border: none; text-align: center">:</td>';
        $table2 .= '<td width="83%" style="border: none; text-align: left"> ' . $kwitansi->keterangan . '</td>';
        $table2 .= '</tr>';
        $table2 .=  <<<EOD
            <tbody>
            </table>
        EOD;

        $t_cpdf2->writeHTMLCell(184, 0, 14, $t_cpdf2->GetY() + 4, $table2, 0, 1, false, true, 'L', true);

        // Tampilkan gambar bukti pembayaran jika ada
        if (!empty($kwitansi->bukti_pembayaran)) {
            $bukti_path = FCPATH . 'assets/backend/uploads/bukti_pembayaran_pu/' . $kwitansi->bukti_pembayaran;
            if (file_exists($bukti_path)) {
                $t_cpdf2->SetY($t_cpdf2->GetY() + 10);
                // Maksimal panjang gambar (width) 100mm, tinggi otomatis proporsional
                $t_cpdf2->Image(
                    base_url('assets/backend/uploads/bukti_pembayaran_pu/' . $kwitansi->bukti_pembayaran),
                    80, // X
                    $t_cpdf2->GetY(), // Y
                    55, // width max 100mm
                    0,   // height auto
                    '',  // type auto
                    '',  // link
                    '',  // align
                    false, // resize
                    300,   // dpi
                    '',    // palign
                    false, // ismask
                    false, // imgmask
                    0,     // border
                    false, // fitbox
                    false, // hidden
                    false  // fitonpage
                );
                $t_cpdf2->SetY($t_cpdf2->GetY() + 50); // Tambah jarak setelah gambar (opsional)
            }
        } else {
            $t_cpdf2->SetY($t_cpdf2->GetY() + 2);
        }

        // Output PDF (tampilkan di browser)
        if (isset($data['output']) && $data['output'] === 'send') {
            // Simpan PDF ke file dan kembalikan kontennya
            $pdf_path = FCPATH . 'assets/backend/uploads/kwitansiPU/Kwitansi_PengenUmroh.pdf';
            $t_cpdf2->Output($pdf_path, 'F');
            return file_get_contents($pdf_path);
        } else {
            $t_cpdf2->Output('Kwitansi Pengenumroh ' . $invoice->kode_invoice . '.pdf', 'I'); // 'I' untuk menampilkan di browser
        }
    }
}
