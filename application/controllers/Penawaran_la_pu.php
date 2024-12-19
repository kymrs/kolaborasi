<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penawaran_la_pu extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_penawaran_la_pu');
        $this->load->model('backend/M_notifikasi');
        $this->load->helper('date');
        $this->M_login->getsecurity();
        $this->load->library('ciqrcode');
    }

    public function index()
    {
        $data['notif'] = $this->M_notifikasi->pending_notification();
        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        ($akses->view_level == 'N' ? redirect('auth') : '');
        $data['add'] = $akses->add_level;

        $this->load->model('backend/M_notifikasi');

        $data['title'] = "backend/penawaran_pu/penawaran_list_la_pu";
        $data['titleview'] = "Data Penawaran";
        $name = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $this->session->userdata('id_user'))
            ->get()
            ->row('name');
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
        $list = $this->M_penawaran_la_pu->get_datatables();
        $data = array();
        $no = $_POST['start'];

        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        $read = $akses->view_level;
        $edit = $akses->edit_level;
        $delete = $akses->delete_level;
        $print = $akses->print_level;

        //LOOPING DATATABLES
        foreach ($list as $field) {

            $action_read = ($read == 'Y') ? '<a href="penawaran_la_pu/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>&nbsp;' : '';
            $action_edit = ($edit == 'Y') ? '<a href="penawaran_la_pu/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;' : '';
            $action_delete = ($delete == 'Y') ? '<a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;' : '';
            $action_print = ($print == 'Y') ? '<a class="btn btn-success btn-circle btn-sm" target="_blank" href="penawaran_la_pu/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>' : '';

            // MENENTUKAN ACTION APA YANG AKAN DITAMPILKAN DI LIST DATA TABLES
            $action = $action_read . $action_edit . $action_delete . $action_print;

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $action;
            $row[] = strtoupper($field->no_pelayanan);
            $row[] = strtoupper($field->no_arsip);
            $row[] = $field->produk;
            $row[] = date("d M Y", strtotime($field->tgl_berlaku));
            $row[] = $field->tgl_keberangkatan;
            $row[] = $field->durasi;
            $row[] = $field->berangkat_dari;
            $row[] = $field->biaya;
            $row[] = $field->pelanggan;
            $row[] = date("d M Y", strtotime($field->created_at));

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_penawaran_la_pu->count_all(),
            "recordsFiltered" => $this->M_penawaran_la_pu->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    public function read_form($id)
    {
        $data['penawaran'] = $this->M_penawaran_la_pu->getPenawaran($id);

        $data['id'] = $id;
        if ($data['penawaran'] == null) {
            $this->load->view('backend/penawaran_pu/404');
        } else {
            $no_arsip = $data['penawaran']->no_arsip;

            $params['data'] = 'https://arsip.pengenumroh.com/' . $no_arsip;
            $params['level'] = 'H';
            $params['size'] = 10;
            $params['savename'] = 'assets/backend/document/qrcode/qr-' . $no_arsip . '.png';
            $this->ciqrcode->generate($params);

            $data['title'] = 'backend/penawaran_pu/penawaran_read_la_pu_2';
            $data['title_view'] = 'Land Arrangement';
            $data['rundowns'] = $this->M_penawaran_la_pu->get_rundown($data['penawaran']->no_pelayanan);
            $this->load->view('backend/home', $data);
        }
    }

    public function add_form()
    {
        $this->load->model('backend/M_notifikasi');
        $data['notif'] = $this->M_notifikasi->pending_notification();
        $data['id'] = 0;
        $data['title'] = 'backend/penawaran_pu/penawaran_form_la_pu_2';
        // $data['products'] = $this->db->select('id, nama')->from('tbl_produk')->get()->result_object();
        $data['title_view'] = 'Land Arrangement Form';
        $this->load->view('backend/home', $data);
    }

    function edit_form($id)
    {
        $this->load->model('backend/M_notifikasi');
        $data['notif'] = $this->M_notifikasi->pending_notification();
        $data['id'] = $id;
        $data['aksi'] = 'update';
        $data['title'] = 'backend/penawaran_pu/penawaran_form_la_pu_2';
        // $data['products'] = $this->db->select('id, nama')->from('tbl_produk')->get()->result_object();
        $data['title_view'] = 'Edit Land Arrangement Form';
        $this->load->view('backend/home', $data);
    }

    function edit_data($id)
    {
        $data['master'] = $this->db->get_where('tbl_land_arrangement', ['id' => $id])->row_array();
        echo json_encode($data);
    }

    public function generate_kode()
    {
        $date = date('Y-m-d h:i:sa');
        $kode = $this->M_penawaran_la_pu->max_kode($date)->row();
        if (empty($kode->no_pelayanan)) {
            $no_urut = 0;
        } else {
            $no_urut = substr($kode->no_pelayanan, 9, 3);
        }
        $urutan = str_pad(number_format($no_urut + 1), 3, "0", STR_PAD_LEFT);
        $year = substr($date, 0, 4);
        $bulan = substr($date, 5, 2);
        $bulan_romawi = bulan_angka_ke_romawi((int)$bulan);
        $data = 'UMROH/LA/' . $urutan . '/' . $bulan_romawi . '/' . $year;
        echo json_encode($data);
    }

    public function generate_layanan()
    {
        $layanan = $this->db->from('tbl_produk')
            ->where('id', $this->input->post('id'))
            ->get()
            ->row();
        echo json_encode($layanan);
    }

    public function add()
    {
        //GENERATE NOMOR PELAYANAN
        $date = date('Y-m-d h:i:sa');
        $kode = $this->M_penawaran_la_pu->max_kode($date)->row();
        if (empty($kode->no_pelayanan)) {
            $no_urut = 0;
        } else {
            $no_urut = substr($kode->no_pelayanan, 9, 3);
        }
        $urutan = str_pad(number_format($no_urut + 1), 3, "0", STR_PAD_LEFT);
        $year = substr($date, 0, 4);
        $year2 = substr($date, 2, 2);
        $bulan = substr($date, 5, 2);
        $bulan_romawi = bulan_angka_ke_romawi((int)$bulan);
        $no_pelayanan = 'UMROH/LA/' . $urutan . '/' . $bulan_romawi . '/' . $year;

        $arsip = $this->M_penawaran_la_pu->max_kode_arsip($date)->row();
        if (empty($arsip->no_arsip)) {
            $no_urut2 = 1;
        } else {
            $no_urut2 = substr($arsip->no_arsip, 6) + 1;
        }

        //GENERATE NOMOR ARSIP
        $urutan2 = str_pad($no_urut2, 2, "0", STR_PAD_LEFT);
        $no_arsip = 'PU' . $year2 . '09' . $urutan2;

        //CONVERT TIME
        // Ambil nilai input datetime dari form
        $input_datetime = $this->input->post('tgl_berlaku');
        $input2_datetime = $this->input->post('keberangkatan');

        // Ubah format dari 'Y-m-dTH:i' ke 'Y-m-d H:i:s' agar sesuai dengan format MySQL
        $formatted_datetime = date('Y-m-d', strtotime($input_datetime));
        $formatted2_datetime = date('Y-m-d', strtotime($input2_datetime));

        $data = array(
            'no_pelayanan' => $no_pelayanan,
            'no_arsip' => $no_arsip,
            'pelanggan' => $this->input->post('pelanggan'),
            'produk' => $this->input->post('produk'),
            'deskripsi' => $this->input->post('deskripsi'),
            'tgl_berlaku' => $formatted_datetime,
            'tgl_keberangkatan' => $formatted2_datetime,
            'durasi' => $this->input->post('durasi'),
            'berangkat_dari' => $this->input->post('berangkat_dari'),
            'biaya' => $this->input->post('biaya_integer'),
            'layanan_trmsk' => $this->input->post('layanan_content'),
            'layanan_tdk_trmsk' => $this->input->post('layanan_content2'),
            'catatan' => $this->input->post('catatan_content')
        );

        $this->M_penawaran_la_pu->save($data);

        // INISIASI INPUT KE RUNDOWN
        $hari = $this->input->post('hari[]');
        $tanggal = $this->input->post('tanggal[]');
        $kegiatan = $this->input->post('kegiatan[]');
        // PERULANGAN INPUT RUNDOWN
        for ($i = 1; $i <= count($_POST['hari']); $i++) {
            $data2[] = array(
                'no_pelayanan' => $no_pelayanan,
                'hari' => $hari[$i],
                'tanggal' => $tanggal[$i],
                'kegiatan' => $kegiatan[$i]
            );
        }
        $this->M_penawaran_la_pu->save_rundown($data2);

        //DIVISI
        $id_user = $this->session->userdata('id_user');
        $divisi = $this->db->select('divisi')->from('tbl_data_user')->where('id_user', $id_user)->get()->row('divisi');

        $data3 = array(
            'id_user' => $id_user,
            'nama_dokumen' => $this->input->post('produk'),
            'penerbit' => $divisi,
            'no_dokumen' => $no_pelayanan,
            'tgl_dokumen' => date('Y-m-d H:i:s'),
            'no_arsip' => $no_arsip
        );

        $this->M_penawaran_la_pu->save_arsip($data3);
        // echo json_encode($data2);
        echo json_encode(array("status" => TRUE));
    }

    public function update($id)
    {
        //CONVERT TIME
        // Ambil nilai input datetime dari form
        $input_datetime = $this->input->post('tgl_berlaku');
        $input2_datetime = $this->input->post('keberangkatan');

        // Ubah format dari 'Y-m-dTH:i' ke 'Y-m-d H:i:s' agar sesuai dengan format MySQL
        $formatted_datetime = date('Y-m-d H:i:s', strtotime($input_datetime));
        $formatted2_datetime = date('Y-m-d H:i:s', strtotime($input2_datetime));

        $data = array(
            'pelanggan' => $this->input->post('pelanggan'),
            'alamat' => $this->input->post('alamat'),
            'produk' => $this->input->post('produk'),
            'deskripsi' => $this->input->post('deskripsi'),
            'tgl_berlaku' => $formatted_datetime,
            'keberangkatan' => $formatted2_datetime,
            'durasi' => $this->input->post('durasi'),
            'tempat' => $this->input->post('tempat'),
            'biaya' => $this->input->post('biaya_integer'),
            'layanan_la' => $this->input->post('layanan_content'),
            'pelanggan' => $this->input->post('pelanggan'),
            'catatan' => $this->input->post('catatan_content'),
            'updated_at' => date('Y-m-d H:i:s')
        );
        $this->db->update('tbl_land_arrangement', $data, ['id' => $id]);
        echo json_encode(array("status" => TRUE));
    }

    function delete($id)
    {
        $this->db->delete('tbl_land_arrangement', ['id' => $id]);
        echo json_encode(array("status" => TRUE));
    }

    // PRINTOUT FPDF
    public function generate_pdf($id)
    {
        // $this->load->library('fpdf_html');
        $this->load->library('pdf');

        // INISIAI VARIABLE
        $penawaran = $this->M_penawaran_la_pu->getPenawaran($id);
        $rundowns = $this->M_penawaran_la_pu->get_rundown($penawaran->no_pelayanan);

        // Start FPDF
        // $pdf = new Fpdf_html();
        $pdf = new pdf();
        $pdf->AddPage();

        $pdf->AddFont('Poppins-Regular', '', 'Poppins-Regular.php');
        $pdf->AddFont('Poppins-Bold', '', 'Poppins-Bold.php');

        // Mengatur posisi Y untuk menggeser seluruh konten ke bawah
        $pdf->SetY(35); // Ganti 50 dengan jumlah yang Anda inginkan

        // Pilih font untuk isi
        $pdf->SetFont('Poppins-Bold', '', 24);

        // Margin setup
        $left_margin = 10;
        $pdf->SetLeftMargin($left_margin);  // Mengatur margin kiri

        // Bagian TO
        $pdf->SetXY($left_margin, $pdf->GetY());
        $pdf->Cell(0, 10, 'PENAWARAN', 0, 1, 'L');

        // Name and title (Creative Director)
        $pdf->SetFont('Poppins-Regular', '', 9);
        $pdf->Cell(38, 5, 'No', 0, 0,);
        $pdf->cell(5, 5, ':', 0, 0);
        $pdf->Cell(50, 5, $penawaran->no_pelayanan, 0, 1);

        $pdf->Cell(38, 5, 'Tanggal Dokumen', 0, 0);
        $pdf->Cell(5, 5, ':', 0, 0);
        $pdf->Cell(50, 5, $penawaran->created_at, 0, 1);

        $pdf->Cell(38, 5, 'Berlaku s.d.', 0, 0);
        $pdf->Cell(5, 5, ':', 0, 0);
        $pdf->Cell(50, 5, $penawaran->tgl_berlaku, 0, 1);

        $pdf->Cell(38, 5, 'Produk', 0, 0);
        $pdf->Cell(5, 5, ':', 0, 0);
        $pdf->Cell(50, 5, $penawaran->produk, 0, 1);

        $pdf->Cell(38, 5, 'Kepada', 0, 0);
        $pdf->Cell(5, 5, ':', 0, 0);
        $pdf->Cell(50, 5, $penawaran->pelanggan, 0, 1);

        // QRCODE
        // Define QR Code parameters
        $params['data'] = 'https://example.com'; // Data to encode in QR
        $params['level'] = 'H'; // QR Code error correction level
        $params['size'] = 5; // Size of QR Code
        $qr_image_path = 'assets/qrcode.png'; // Path to save QR Code image
        $params['savename'] = FCPATH . $qr_image_path;

        // Generate QR Code
        $this->ciqrcode->generate($params);

        // Add QR Code image
        if (file_exists($qr_image_path)) { // Check if the file exists
            $pdf->Image($qr_image_path, 140, 37, 32, 32); // Position (X,Y), Width, Height

            // Delete the QR Code file after using it
            unlink($qr_image_path);
        } else {
            $pdf->Text(10, 30, 'QR Code image not found.');
        }

        $pdf->Ln(5); // SPASI

        // HEADER LAYANAN
        $pdf->SetFont('Poppins-Regular', '', 11);
        $pdf->SetFillColor(252, 118, 19);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(0, 10, 'LAYANAN', 0, 1, 'L', true);
        $pdf->SetTextColor(0, 0, 0);


        // Spasi antara bagian atas dan konten
        $pdf->Ln(2);

        // Konten text (justify)
        $pdf->SetFont('Poppins-Regular', '', 9);

        // HEADER DESKRIPSI
        $pdf->Cell(100, 5, 'Deskripsi :', 0, 0);
        $right_column_x = 120;

        // Keberangkatan
        $pdf->SetX($right_column_x); // Pindahkan posisi ke kolom kanan
        $pdf->Cell(26, 5, 'Keberangkatan', 0, 0);
        $pdf->cell(2, 5, ':', 0, 0);
        $pdf->Cell(50, 5, $penawaran->tgl_keberangkatan, 0, 1);
        // Durasi
        $pdf->SetX($right_column_x); // Pindahkan posisi ke kolom kanan
        $pdf->Cell(26, 5, 'Durasi', 0, 0);
        $pdf->cell(2, 5, ':', 0, 0);
        $pdf->Cell(50, 5, $penawaran->durasi, 0, 1);
        // Berangkat dari
        $pdf->SetX($right_column_x); // Pindahkan posisi ke kolom kanan
        $pdf->Cell(26, 5, 'Berangkat Dari', 0, 0);
        $pdf->cell(2, 5, ':', 0, 0);
        $pdf->Cell(50, 5, $penawaran->berangkat_dari, 0, 1);

        $keberangkatanY = $pdf->GetY();

        // Mengatur lebar untuk konten agar justify bisa bekerja
        $content_width = 100;  // Misal, lebar halaman adalah 210, jadi margin kiri 10 dan margin kanan 10

        // KONTEN DESKRIPSI
        $body_text = $penawaran->deskripsi;
        $pdf->Sety(91);
        $pdf->MultiCell($content_width, 4, $body_text, 0, 'J');  // 'J' digunakan untuk rata kiri dan kanan (justify)

        $deskripsiY = $pdf->GetY();

        // Kondisi penggunaan Y
        if ($deskripsiY > $keberangkatanY) {
            $useY = $deskripsiY;
        } else {
            $useY = $keberangkatanY;
        }


        $pdf->Sety($useY + 5);

        // HEADER LAYANAN TERMASUK
        $pdf->SetFont('Poppins-Regular', '', 9);
        $pdf->Cell(100, 5, 'Layanan Termasuk :', 0, 0);

        $trmskY = $pdf->GetY();

        // HEADER LAYANAN TIDAK TERMASUK
        $pdf->SetX($right_column_x); // Pindahkan posisi ke kolom kanan
        $pdf->Cell(80, 5, 'Layanan Tidak Termasuk :', 0, 1);

        // KONTEN LAYANAN TIDAK TERMASUK
        $pdf->SetX($right_column_x);
        $body_text3 = $penawaran->layanan_tdk_trmsk;
        // $body_text3 = html_entity_decode($body_text3);
        // $pdf->WriteHTML($body_text3);
        $pdf->MultiCell(80, 4, $body_text3, 0, 'J');

        $pdf->Ln(10); // Spasi antara paragraf

        // KONTEN HOTEL DAN PENERBANGAN
        $pdf->SetFont('Poppins-Regular', '', 9);
        $pdf->SetX(100); // Pindahkan posisi ke kolom kanan
        $pdf->Cell(25, 5, 'Hotel Makkah', 0, 0,);
        $pdf->SetFont('ZapfDingbats');
        $stars = '';
        for ($i = 0; $i < 5; $i++) {
            $stars .= chr(73);
        }
        $pdf->cell(15, 5, $stars, 0, 0);
        $pdf->SetFont('Poppins-Regular', '', 9);
        $pdf->cell(3, 5, ':', 0, 0);
        $pdf->Cell(40, 5, 'Sofwah Orchid', 0, 1);

        $pdf->SetX(100); // Pindahkan posisi ke kolom kanan
        $pdf->Cell(25, 5, 'Hotel Madinah', 0, 0);
        $pdf->SetFont('ZapfDingbats');
        $stars2 = '';
        for ($i = 0; $i < 5; $i++) {
            $stars2 .= chr(73);
        }
        $pdf->cell(15, 5, $stars2, 0, 0);
        $pdf->SetFont('Poppins-Regular', '', 9);
        $pdf->Cell(3, 5, ':', 0, 0);
        $pdf->Cell(40, 5, 'Taiba Front', 0, 1);

        $pdf->SetX(100); // Pindahkan posisi ke kolom kanan
        $pdf->Cell(40, 5, 'Keberangkatan', 0, 0);
        $pdf->Cell(3, 5, ':', 0, 0);
        $pdf->Cell(40, 5, 'Direct Saudia Airlines SV817', 0, 1);

        $pdf->SetX(100); // Pindahkan posisi ke kolom kanan
        $pdf->Cell(40, 5, 'Kepulangan', 0, 0);
        $pdf->Cell(3, 5, ':', 0, 0);
        $pdf->Cell(40, 5, 'Direct Saudia Airlines SV826', 0, 1);

        // Dapatkan posisi Y setelah konten terakhir
        $tidak_termasukY = $pdf->GetY();

        // KONTEN LAYANAN TERMASUK
        $pdf->Sety($trmskY + 5);
        $body_text2 = $penawaran->layanan_trmsk;
        $pdf->MultiCell(86, 4, $body_text2, 0, 'J');

        // Dapatkan posisi Y setelah konten terakhir
        $termasukY = $pdf->GetY();

        // Kondisi penggunaan setY yang sesuai
        if ($tidak_termasukY > $termasukY) {
            $useY2 = $tidak_termasukY;
        } else {
            $useY2 = $termasukY;
        }

        // Set posisi header "HARGA PAKET"
        $pdf->SetY($useY2 + 5);

        // HEADER HARGA PAKET
        $pdf->SetFont('Poppins-Regular', '', 11);
        $pdf->SetFillColor(252, 118, 19);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(0, 10, 'HARGA PAKET', 0, 1, 'L', true);
        $pdf->SetTextColor(0, 0, 0);

        // Spasi antara konten dan signature
        $pdf->Ln(2);

        // BIAYA
        $pdf->SetFont('Poppins-Bold', '', 15);
        $pdf->Cell(20, 5, 'BIAYA', 0, 0,);
        $pdf->cell(5, 5, ':', 0, 0);
        $pdf->Cell(50, 5, 'Rp. ' . number_format($penawaran->biaya, 0, ',', '.'), 0, 1);

        // // Spasi antara konten dan signature
        // $pdf->Ln(1);

        // // TRIPLE
        // $pdf->Cell(20, 5, 'TRIPLE', 0, 0);
        // $pdf->Cell(5, 5, ':', 0, 0);
        // $pdf->Cell(50, 5, 'Rp. 40.900.000', 0, 1);

        // // Spasi antara konten dan signature
        // $pdf->Ln(1);

        // // DOUBLE
        // $pdf->Cell(20, 5, 'DOUBLE', 0, 0);
        // $pdf->Cell(5, 5, ':', 0, 0);
        // $pdf->Cell(50, 5, 'Rp. 42.900.000', 0, 1);

        // Spasi antara konten dan signature
        $pdf->Ln(2);

        // HEADER LAYANAN PASTI
        $pdf->SetFont('Poppins-Regular', '', 11);
        $pdf->SetFillColor(252, 118, 19);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(0, 10, 'LAYANAN PASTI', 0, 1, 'L', true);
        $pdf->SetTextColor(0, 0, 0);

        // Spasi antara konten dan signature
        $pdf->Ln(1);

        $layananPastiY = $pdf->GetY();

        $body_text4 = "1. Konsultasi Gratis
2. Gratis Bantuan Pembuatan Paspor
3. Gratis Antar Dokumen & Perlengkapan
4. Gratis Pendampingan Manasik";
        $pdf->MultiCell(84, 4, $body_text4, 0, 'J');

        $pdf->Sety($layananPastiY);
        $pdf->SetX(96); // Pindahkan posisi ke kolom kanan
        $body_text5 = "5. Gratis Handling Keberangkatan
6. Gratis Handling Kepulangan
7. Jaminan Pasti Berangkat
8. Garansi 100% Uang Kembali Apabila Travel Gagal
Memberangkatkan";
        $pdf->MultiCell(84, 4, $body_text5, 0, 'J');

        // PAGE RUNDOWN
        $pdf->AddPage();

        // Table Header
        $pdf->Sety(38);
        $pdf->SetFont('Poppins-Bold', '', 10);
        $pdf->Cell(32, 5, 'Hari', 1, 0, 'C');
        $pdf->Cell(32, 5, 'Tanggal', 1, 0, 'C');
        $pdf->Cell(127, 5, 'Kegiatan', 1, 1, 'C');

        // TABLE CONTENT
        $pdf->SetFont('Poppins-Regular', '', 10);

        foreach ($rundowns as $rundown) {
            $content_text = $rundown->kegiatan;

            // Hitung jumlah baris teks
            $lines = $pdf->NbLines(127, $content_text);

            // Hitung tinggi total
            $height2 = $lines * 5;

            $pdf->Cell(32, $height2, $rundown->hari, 1, 0, 'L');
            $pdf->Cell(32, $height2, $rundown->tanggal, 1, 0, 'C');

            $pdf->MultiCell(127, 5, $content_text, 1, 'L');
        }

        // Output the PDF
        $pdf->Output('I', 'Deklarasi.pdf');
    }
}
