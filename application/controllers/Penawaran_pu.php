<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penawaran_pu extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_penawaran_pu');
        $this->M_login->getsecurity();
        $this->load->library('ciqrcode');
    }

    public function index()
    {
        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        ($akses->view_level == 'N' ? redirect('auth') : '');
        $data['add'] = $akses->add_level;


        $data['title'] = "backend/penawaran_pu/penawaran_list_pu";
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
        $list = $this->M_penawaran_pu->get_datatables();
        $data = array();
        $no = $_POST['start'];

        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        $read = $akses->view_level;
        $edit = $akses->edit_level;
        $delete = $akses->delete_level;
        $print = $akses->print_level;

        //LOOPING DATATABLES
        foreach ($list as $field) {

            $action_read = ($read == 'Y') ? '<a href="penawaran_pu/read_form/' . $field->no_arsip . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>&nbsp;' : '';
            $action_edit = ($edit == 'Y') ? '<a href="penawaran_pu/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;' : '';
            $action_delete = ($delete == 'Y') ? '<a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;' : '';
            $action_print = ($print == 'Y') ? '<a class="btn btn-success btn-circle btn-sm" target="_blank" href="penawaran_pu/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>' : '';

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
            $row[] = $field->keberangkatan;
            $row[] = $field->durasi . ' Hari';
            $row[] = $field->tempat;
            $row[] = 'Rp. ' . number_format($field->biaya, 0, ',', '.');;
            $row[] = $field->pelanggan;
            $row[] = date("d M Y", strtotime($field->created_at));

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_penawaran_pu->count_all(),
            "recordsFiltered" => $this->M_penawaran_pu->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    public function read_form()
    {
        $kode = $this->uri->segment(3);
        // var_dump($kode);
        $data['penawaran'] = $this->M_penawaran_pu->getPenawaran($kode);

        if ($data['penawaran'] == null) {
            $this->load->view('backend/penawaran_pu/404');
        } else {
            $no_arsip = $data['penawaran']['no_arsip'];
            $data['layanan_termasuk'] = $this->M_penawaran_pu->getLayananTermasuk($kode);
            $data['layanan_tidak_termasuk'] = $this->M_penawaran_pu->getLayananTidakTermasuk($kode);

            $params['data'] = 'https://arsip.pengenumroh.com/' . $no_arsip;
            $params['level'] = 'H';
            $params['size'] = 10;
            $params['savename'] = 'assets/backend/document/qrcode/qr-' . $no_arsip . '.png';
            $this->ciqrcode->generate($params);

            $data['title'] = 'backend/penawaran_pu/penawaran_read_pu';
            $data['title_view'] = 'Prepayment';
            $this->load->view('backend/home', $data);
        }
    }

    public function add_form()
    {
        $data['id'] = 0;
        $data['title'] = 'backend/penawaran_pu/penawaran_form_pu';
        $data['layanan'] = $this->db->get('tbl_layanan')->result_array();
        $data['title_view'] = 'Penawaran Form';
        $this->load->view('backend/home', $data);
    }

    function edit_form($id)
    {
        $data['id'] = $id;
        $data['aksi'] = 'update';
        $data['title_view'] = "Edit Data Prepayment";
        $data['title'] = 'backend/penawaran_pu/penawaran_form_pu';
        $data['layanan'] = $this->db->get('tbl_layanan')->result_array();
        $this->load->view('backend/home', $data);
    }

    function edit_data($id)
    {
        $data['master'] = $this->db->get_where('tbl_penawaran', ['id' => $id])->row_array();
        $data['layanan'] = $this->M_penawaran_pu->get_penawaran_detail($id); // Ambil detail layanan (status, nominal)
        echo json_encode($data);
    }

    public function generate_kode()
    {
        $date = date('Y-m-d h:i:sa');
        $kode = $this->M_penawaran_pu->max_kode($date)->row();
        if (empty($kode->no_pelayanan)) {
            $no_urut = 1;
        } else {
            $no_urut = substr($kode->no_pelayanan, 9, 3);
        }
        $urutan = str_pad(number_format($no_urut + 1), 3, "0", STR_PAD_LEFT);
        $year = substr($date, 0, 4);
        $data = 'UMROH/LA/' . $urutan . '/' . 'IX' . '/' . $year;
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
        // GENERATE NOMOR PELAYANAN
        $date = date('Y-m-d h:i:sa');
        $kode = $this->M_penawaran_pu->max_kode($date)->row();
        if (empty($kode->no_pelayanan)) {
            $no_urut = 1;
            $no_urut2 = 1; // Inisialisasi untuk arsip jika baru
        } else {
            $no_urut = substr($kode->no_pelayanan, 9, 3);
            $no_urut2 = substr($kode->no_arsip, 6) + 1;
        }
        $urutan = str_pad(number_format($no_urut + 1), 3, "0", STR_PAD_LEFT);
        $year = substr($date, 0, 4);
        $year2 = substr($date, 2, 2);
        $no_pelayanan = 'UMROH/LA/' . $urutan . '/' . 'IX' . '/' . $year;

        // GENERATE NOMOR ARSIP
        $urutan2 = str_pad($no_urut2, 2, "0", STR_PAD_LEFT);
        $no_arsip = 'PU' . $year2 . '09' . $urutan2;

        //CONVERT TIME
        // Ambil nilai input datetime dari form
        $input_datetime = $this->input->post('tgl_berlaku');
        $input2_datetime = $this->input->post('keberangkatan');

        // Ubah format dari 'Y-m-dTH:i' ke 'Y-m-d H:i:s' agar sesuai dengan format MySQL
        $formatted_datetime = date('Y-m-d H:i:s', strtotime($input_datetime));
        $formatted2_datetime = date('Y-m-d H:i:s', strtotime($input2_datetime));

        // Data untuk tabel penawaran
        $data = array(
            'no_pelayanan' => $no_pelayanan,
            'no_arsip' => $no_arsip,
            'pelanggan' => $this->input->post('pelanggan'),
            'alamat' => $this->input->post('alamat'),
            'produk' => $this->input->post('produk'),
            'deskripsi' => $this->input->post('deskripsi'),
            'tgl_berlaku' => $formatted_datetime,
            'keberangkatan' => $formatted2_datetime,
            'durasi' => $this->input->post('durasi'),
            'tempat' => $this->input->post('tempat'),
            'biaya' => preg_replace('/\D/', '', $this->input->post('biaya')),
            'pelanggan' => $this->input->post('pelanggan'),
            'catatan' => $this->input->post('catatan_content')
        );

        // Simpan data ke tabel penawaran dan ambil ID penawaran yang baru disimpan
        $id_penawaran = $this->M_penawaran_pu->save($data);

        // Ambil data layanan dari input (id layanan, status, dan nominal)
        $ids = $this->input->post('id_layanan'); // ID layanan
        $statuses = $this->input->post('status'); // Status layanan (Y/N)
        $nominals = preg_replace('/\D/', '', $this->input->post('nominal')); // Nominal biaya layanan

        // Siapkan array untuk insert ke tabel tbl_penawaran_detail
        $detail_data = [];
        if (is_array($ids) && is_array($statuses) && is_array($nominals)) {
            foreach ($ids as $key => $id_layanan) {
                // Isi "-" jika status kosong, tetapi lewati data dengan is_active "-"
                $is_active = isset($statuses[$key]) && !empty($statuses[$key]) ? $statuses[$key] : '-';
                $nominal = isset($nominals[$key]) && !empty($nominals[$key]) ? $nominals[$key] : null;

                // Hanya insert data yang statusnya bukan "-"
                if ($is_active !== '-') {
                    // Jika id_layanan adalah 9, tambahkan nominal ke dalam is_active
                    if ($id_layanan == 9 && $nominal !== null) {
                        $is_active .= ' ' . $nominal; // Gabungkan is_active dengan nominal
                    }

                    $detail_data[] = [
                        'id_penawaran' => $id_penawaran, // ID penawaran yang baru disimpan
                        'id_layanan' => $id_layanan, // ID layanan
                        'is_active' => $is_active, // Status layanan (is_active + nominal jika id_layanan = 9)
                    ];
                }
            }

            // Log atau print untuk memeriksa data yang akan diinsert
            log_message('info', 'Detail Data to insert: ' . print_r($detail_data, TRUE));

            // Simpan detail layanan ke tabel tbl_penawaran_detail
            if (!empty($detail_data)) {
                $this->M_penawaran_pu->insert_penawaran_detail($detail_data);
            }
        }
        // Kirim response
        echo json_encode(array("status" => TRUE));
    }

    public function update($id)
    {
        // Ambil data dari form
        $no_pelayanan = $this->input->post('no_pelayanan');
        $pelanggan = $this->input->post('pelanggan');
        $catatan = $this->input->post('editor_content');
        $layanan_ids = $this->input->post('id_layanan'); // Array of layanan IDs
        $statuses = $this->input->post('status'); // Array of layanan statuses
        $extra_inputs = $this->input->post('nominal'); // Ambil nominal dari input

        // Jika nominal berformat rupiah, bersihkan dari karakter non-digit
        $extra_inputs = array_map(function ($input) {
            return preg_replace('/\D/', '', $input); // Hanya ambil karakter digit
        }, $extra_inputs);

        // Debugging: Tampilkan nilai nominal
        // var_dump($extra_inputs);

        // CONVERT TIME
        // Ambil nilai input datetime dari form
        $input_datetime = $this->input->post('tgl_berlaku');
        $input2_datetime = $this->input->post('keberangkatan');

        // Ubah format dari 'Y-m-dTH:i' ke 'Y-m-d H:i:s' agar sesuai dengan format MySQL
        $formatted_datetime = date('Y-m-d H:i:s', strtotime($input_datetime));
        $formatted2_datetime = date('Y-m-d H:i:s', strtotime($input2_datetime));

        // Data untuk tabel penawaran
        $data = array(
            'no_pelayanan' => $no_pelayanan,
            'pelanggan' => $this->input->post('pelanggan'),
            'alamat' => $this->input->post('alamat'),
            'produk' => $this->input->post('produk'),
            'deskripsi' => $this->input->post('deskripsi'),
            'tgl_berlaku' => $formatted_datetime,
            'keberangkatan' => $formatted2_datetime,
            'durasi' => $this->input->post('durasi'),
            'tempat' => $this->input->post('tempat'),
            'biaya' => preg_replace('/\D/', '', $this->input->post('biaya')),
            'catatan' => $this->input->post('catatan_content')
        );

        // Update data penawaran
        $this->db->update('tbl_penawaran', $data, ['id' => $id]);

        // Pastikan semua input adalah array
        if (!is_array($layanan_ids) || !is_array($statuses) || !is_array($extra_inputs)) {
            echo json_encode(array("status" => FALSE, "message" => "Data tidak valid."));
            return;
        }

        // Loop over each layanan and check whether to update, insert, or delete
        foreach ($layanan_ids as $index => $layanan_id) {
            $status = isset($statuses[$index]) ? $statuses[$index] : null; // Ambil status
            $extra_input = isset($extra_inputs[$index]) ? $extra_inputs[$index] : null; // Ambil nominal

            // Cek apakah layanan sudah ada di database
            $existing_layanan = $this->db->get_where('tbl_penawaran_detail', ['id_layanan' => $layanan_id, 'id_penawaran' => $id])->row();

            // Jika status kosong, hapus dari database
            if (empty($status)) {
                if ($existing_layanan) {
                    $this->db->delete('tbl_penawaran_detail', ['id' => $existing_layanan->id]);
                }
                continue; // Lewati iterasi ini dan lanjutkan ke layanan berikutnya
            }

            // Jika layanan sudah ada, lakukan update
            if ($existing_layanan) {
                if ($layanan_id == 9 && $status === 'N') {
                    // Jika id_layanan 9 dan statusnya N, hapus nilai nominal
                    $data_layanan_update = array(
                        'is_active' => 'N' // Set is_active hanya 'N'
                    );
                } else {
                    // Gabungkan nominal ke dalam is_active
                    $data_layanan_update = array(
                        'is_active' => $status . ' ' . $extra_input // Gabungkan status dengan nominal
                    );
                }
                $this->db->update('tbl_penawaran_detail', $data_layanan_update, ['id' => $existing_layanan->id]);
            } else {
                // Jika layanan tidak ada, lakukan insert
                $data_layanan_insert = array(
                    'id_penawaran' => $id,
                    'id_layanan' => $layanan_id,
                    'is_active' => $status . ' ' . $extra_input // Gabungkan status dengan nominal
                );

                // Debugging: Tampilkan data yang akan diinsert
                // var_dump($data_layanan_insert);
                $this->db->insert('tbl_penawaran_detail', $data_layanan_insert);
            }
        }
        // Mengembalikan status berhasil
        echo json_encode(array("status" => TRUE));
    }


    function delete($id)
    {
        $this->db->delete('tbl_penawaran', ['id' => $id]);
        $this->db->delete('tbl_penawaran_detail', ['id_penawaran' => $id]);
        echo json_encode(array("status" => TRUE));
    }

    // PRINTOUT FPDF
    public function generate_pdf()
    {
        $this->load->library('Fpdf_generate');

        // Start FPDF
        $pdf = new Pdf('P', 'mm', 'A4');
        $pdf->SetTitle('Form Deklarasi');
        $pdf->AddPage('P', 'Letter');

        // Start FPDF
        $pdf = new Pdf;
        $pdf->AddPage();

        // Mengatur posisi Y untuk menggeser seluruh konten ke bawah
        $pdf->SetY(50); // Ganti 50 dengan jumlah yang Anda inginkan

        // Pilih font untuk isi
        $pdf->SetFont('Poppins-Regular', 'B', 12);

        // Margin setup
        $left_margin = 10;
        $pdf->SetLeftMargin($left_margin);  // Mengatur margin kiri

        // Bagian TO
        $pdf->SetXY($left_margin, $pdf->GetY());
        $pdf->Cell(0, 10, 'TO:', 0, 1, 'L');

        // Name and title (Creative Director)
        $pdf->SetFont('Poppins-Regular', 'B', 12);
        $pdf->Cell(0, 10, 'NAME SURNAME', 0, 1, 'L');
        $pdf->SetFont('Poppins-Regular', '', 10);
        $pdf->Cell(0, 10, 'Creative Director', 0, 1, 'L');

        // Spasi antara bagian atas dan konten
        $pdf->Ln(5);

        // Konten text (justify)
        $pdf->SetFont('Poppins-Regular', '', 10);

        // Mengatur lebar untuk konten agar justify bisa bekerja
        $content_width = 190;  // Misal, lebar halaman adalah 210, jadi margin kiri 10 dan margin kanan 10

        // Paragraf 1
        $body_text = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam sit amet nisi sit amet nibh dignis sim elementum id suscipit leo. Sed ut condimentum diam. Sed ac nulla libero. Morbi ante ante inte rrdum luctus dictum ut, sollicitudin in mi. Donec aliquet lectus quis enim tempor ullamcorper pelle ntesque et neque posuere, gravida lacus molestie, pretium ex. Vivamus in justo ac ante lacinia pharetra.";
        $pdf->MultiCell($content_width, 7, $body_text, 0, 'J');  // 'J' digunakan untuk rata kiri dan kanan (justify)

        $pdf->Ln(5); // Spasi antara paragraf

        // Paragraf 2
        $body_text2 = "Donec ultrices lacinia arcu, eget faucibus quam rhoncus id. Sed convallis eros neque, quis effici tur erat euismod vel. Mauris consequat nunc quis tortor efficitur euismod. Curabitur posuere hendrerit semper nam dignissim sed tellus id fermentum.";
        $pdf->MultiCell($content_width, 7, $body_text2, 0, 'J');

        $pdf->Ln(5); // Spasi antara paragraf

        // Paragraf 3
        $body_text3 = "Phasellus id dui arcu nullam finibus nisl quis quam egestas blandit. Praesent eu leo justo nullam porta nisi non tempus lacinia. Quisque molestie nulla id volutpat congue.";
        $pdf->MultiCell($content_width, 7, $body_text3, 0, 'J');

        // Spasi antara konten dan signature
        $pdf->Ln(20);

        // Bagian Nama kedua dan jabatan (Account Manager)
        $pdf->SetFont('Poppins-Regular', 'B', 12);
        $pdf->Cell(0, 10, 'NAME SURNAME', 0, 1, 'L');
        $pdf->SetFont('Poppins-Regular', '', 10);
        $pdf->Cell(0, 10, 'Account Manager', 0, 1, 'L');

        $pdf->AddPage();

        // Output the PDF
        $pdf->Output('I', 'Deklarasi.pdf');
    }
}
