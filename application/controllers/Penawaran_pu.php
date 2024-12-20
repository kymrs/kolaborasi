<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penawaran_pu extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_penawaran_pu');
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
            $row[] = $field->pelanggan;
            $row[] = $field->tgl_keberangkatan;
            $row[] = $field->durasi . ' Hari';
            $row[] = $this->M_penawaran_pu->getTanggal($field->created_at);
            $row[] = $this->M_penawaran_pu->getTanggal($field->tgl_berlaku);
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
        $data['notif'] = $this->M_notifikasi->pending_notification();
        $kode = $this->uri->segment(3);
        // var_dump($kode);
        $data['penawaran'] = $this->M_penawaran_pu->getPenawaran($kode);

        $no_arsip = $data['penawaran']['no_arsip'];
        $data['layanan_termasuk'] = $this->M_penawaran_pu->getLayananTermasuk($kode);
        $data['layanan_tidak_termasuk'] = $this->M_penawaran_pu->getLayananTidakTermasuk($kode);
        $data['hotel'] = $this->M_penawaran_pu->getHotel($kode);

        $data['title'] = 'backend/penawaran_pu/penawaran_read_pu';
        $data['title_view'] = 'Prepayment';
        $this->load->view('backend/home', $data);
    }

    public function add_form()
    {
        $data['notif'] = $this->M_notifikasi->pending_notification();
        $data['id'] = 0;
        $data['title'] = 'backend/penawaran_pu/penawaran_form_pu';
        $this->db->order_by('nama_layanan', 'ASC');
        $data['layanan'] = $this->db->get('tbl_layanan')->result_array();
        $data['hotel'] = $this->db->get('tbl_hotel_pu')->result_array();
        $data['title_view'] = 'Penawaran Form';
        $this->load->view('backend/home', $data);
    }

    function edit_form($id)
    {
        $data['notif'] = $this->M_notifikasi->pending_notification();
        $data['id'] = $id;
        $data['aksi'] = 'update';
        $data['title_view'] = "Edit Data Prepayment";
        $data['title'] = 'backend/penawaran_pu/penawaran_form_pu';
        $data['hotel'] = $this->db->get('tbl_hotel_pu')->result_array();
        $this->db->order_by('nama_layanan', 'ASC');
        $data['layanan'] = $this->db->get('tbl_layanan')->result_array();
        $this->load->view('backend/home', $data);
    }

    function edit_data($id)
    {
        $data['master'] = $this->db->get_where('tbl_penawaran', ['id' => $id])->row_array();
        $data['layanan'] = $this->M_penawaran_pu->get_penawaran_detail($id); // Ambil detail layanan (status, nominal)
        $data['hotel'] = $this->M_penawaran_pu->get_penawaran_detail2($id); // Ambil detail hotel
        echo json_encode($data);
    }

    public function generate_kode()
    {
        $date = date('Y-m-d');
        $kode = $this->M_penawaran_pu->max_kode($date)->row();
        if (empty($kode->no_pelayanan)) {
            $no_urut = 0;
        } else {
            $no_urut = substr($kode->no_pelayanan, 6, 3);
        }
        $urutan = str_pad(number_format($no_urut + 1), 3, "0", STR_PAD_LEFT);
        $year = substr($date, 0, 4);
        $bulan = substr($date, 5, 2);
        $bulan_romawi = bulan_angka_ke_romawi((int)$bulan);
        $data = 'UMROH/' . $urutan . '/' . $bulan_romawi . '/' . $year;
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
        $date = date('Y-m-d');
        // GENERATE NO PELAYANAN
        $kode = $this->M_penawaran_pu->max_kode($date)->row();
        if (empty($kode->no_pelayanan)) {
            $no_urut = 0;
        } else {
            $no_urut = substr($kode->no_pelayanan, 6, 3);
        }
        $urutan = str_pad(number_format($no_urut + 1), 3, "0", STR_PAD_LEFT);
        $year = substr($date, 0, 4);
        $bulan = substr($date, 5, 2);
        $bulan_romawi = bulan_angka_ke_romawi((int)$bulan);
        $no_pelayanan = 'UMROH/' . $urutan . '/' . $bulan_romawi . '/' . $year;

        // GENERATE NOMOR ARSIP
        $date2 = date('y-m-d');
        $kode2 = $this->M_penawaran_pu->max_kode_arsip($date2)->row();
        if (empty($kode2->no_arsip)) {
            $no_urut2 = 0;
        } else {
            $no_urut2 = substr($kode2->no_arsip, 6, 2);
        }

        $urutan2 = str_pad(number_format($no_urut2 + 1), 2, "0", STR_PAD_LEFT);
        $year2 = substr($date2, 0, 2);
        $bulan2 = substr($date2, 3, 2);
        $no_arsip = 'PU' . $year2 . $bulan2 . $urutan2;

        $params['data'] = 'https://arsip.pengenumroh.com/' . $no_arsip;
        $params['level'] = 'H';
        $params['size'] = 10;
        $params['savename'] = 'assets/backend/document/qrcode/qr-' . $no_arsip . '.png';
        $this->ciqrcode->generate($params);

        //CONVERT TIME
        // Ambil nilai input datetime dari form
        $input_datetime = $this->input->post('tgl_berlaku');
        $input2_datetime = $this->input->post('tgl_keberangkatan');

        // Ubah format dari 'Y-m-dTH:i' ke 'Y-m-d' agar sesuai dengan format MySQL
        $formatted_datetime = date('Y-m-d', strtotime($input_datetime));
        $formatted2_datetime = date('Y-m-d', strtotime($input2_datetime));

        // Data untuk tabel penawaran
        $data = array(
            'no_pelayanan' => $no_pelayanan,
            'no_arsip' => $no_arsip,
            'pelanggan' => $this->input->post('title') . '. ' . $this->input->post('pelanggan'),
            'produk' => $this->input->post('produk'),
            'deskripsi' => $this->input->post('deskripsi'),
            'tgl_berlaku' => $formatted_datetime,
            'tgl_keberangkatan' => $formatted2_datetime,
            'durasi' => $this->input->post('durasi'),
            'berangkat_dari' => $this->input->post('berangkat_dari'),
            'pkt_quad' => preg_replace('/\D/', '', $this->input->post('pkt_quad')),
            'pkt_triple' => preg_replace('/\D/', '', $this->input->post('pkt_triple')),
            'pkt_double' => preg_replace('/\D/', '', $this->input->post('pkt_double')),
            'keberangkatan' => $this->input->post('keberangkatan'),
            'kepulangan' => $this->input->post('kepulangan')
        );

        // Simpan data ke tabel penawaran dan ambil ID penawaran yang baru disimpan
        $id_penawaran = $this->M_penawaran_pu->save($data);

        // Simpan data ke Arsip
        $getUser = $this->db->get_where('tbl_data_user', ['id_user' => $this->session->userdata('id_user')])->row_array();
        $divisi = $getUser['divisi'];

        $data2 = [
            'id_user' => $this->session->userdata('id_user'),
            'no_arsip' => $no_arsip,
            'nama_dokumen' => $this->input->post('produk'),
            'penerbit' => $divisi,
            'no_dokumen' => $no_pelayanan
        ];

        $this->db->insert('tbl_arsip_pu', $data2);

        // // Ambil data layanan dari input (id layanan, status, dan nominal)
        $ids = $this->input->post('id_layanan'); // ID layanan
        $statuses = $this->input->post('status'); // Status layanan (Y/N)
        $nominals = preg_replace('/\D/', '', $this->input->post('nominal')); // Nominal biaya layanan

        // Siapkan array untuk insert ke tabel tbl_penawaran_detail_lyn
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

            // Simpan detail layanan ke tabel tbl_penawaran_detail_lyn
            if (!empty($detail_data)) {
                $this->M_penawaran_pu->insert_penawaran_detail($detail_data);
            }

            // Proses save data hotel

            // Ambil data yang dikirimkan
            $id_hotel = $this->input->post('id_hotel'); // Array ID hotel
            $status2 = $this->input->post('status2');   // Array status hotel

            // Validasi sederhana untuk memastikan data ada
            if (!empty($id_hotel) && is_array($id_hotel) && !empty($status2) && is_array($status2)) {
                // Gabungkan ID hotel dan status2 menjadi satu array
                $hotel_data = [];
                foreach ($id_hotel as $index => $id) {
                    // Pastikan status2 memiliki nilai sebelum menambahkannya ke array
                    if (!empty($status2[$index])) {
                        $hotel_data[] = [
                            'id_penawaran' => $id_penawaran, // ID penawaran yang baru disimpan
                            'id_hotel' => $id,
                            'is_active' => $status2[$index], // Nilai status2
                        ];
                    }
                }

                // Hanya insert jika ada data yang valid
                if (!empty($hotel_data)) {
                    $result = $this->M_penawaran_pu->insert_penawaran_detail2($hotel_data);

                    // Berikan respons berdasarkan hasil simpan
                    if ($result) {
                        $this->session->set_flashdata('success', 'Data hotel berhasil disimpan.');
                    } else {
                        $this->session->set_flashdata('error', 'Gagal menyimpan data hotel.');
                    }
                } else {
                    $this->session->set_flashdata('error', 'Tidak ada data hotel yang valid untuk disimpan.');
                }
            } else {
                $this->session->set_flashdata('error', 'Data hotel tidak valid.');
            }
        }
        // Kirim response
        echo json_encode(array("status" => TRUE));
    }

    public function update($id)
    {
        // Ambil data dari form layanan
        $no_pelayanan = $this->input->post('no_pelayanan');
        $layanan_ids = $this->input->post('id_layanan'); // Array of layanan IDs
        $statuses = $this->input->post('status'); // Array of layanan statuses
        $extra_inputs = $this->input->post('nominal'); // Ambil nominal dari input

        // Data Hotel
        $hotel_ids = $this->input->post('id_hotel');
        $statuses2 = $this->input->post('status2');

        // Jika nominal berformat rupiah, bersihkan dari karakter non-digit
        $extra_inputs = array_map(function ($input) {
            return preg_replace('/\D/', '', $input); // Hanya ambil karakter digit
        }, $extra_inputs);

        // CONVERT TIME
        // Ambil nilai input datetime dari form
        $input_datetime = $this->input->post('tgl_berlaku');
        $input2_datetime = $this->input->post('tgl_keberangkatan');

        // Ubah format dari 'Y-m-dTH:i' ke 'Y-m-d' agar sesuai dengan format MySQL
        $formatted_datetime = date('Y-m-d', strtotime($input_datetime));
        $formatted2_datetime = date('Y-m-d', strtotime($input2_datetime));

        // Data untuk tabel penawaran
        $data = array(
            'no_pelayanan' => $no_pelayanan,
            'pelanggan' => $this->input->post('title') . '. ' . $this->input->post('pelanggan'),
            'produk' => $this->input->post('produk'),
            'deskripsi' => $this->input->post('deskripsi'),
            'tgl_berlaku' => $formatted_datetime,
            'tgl_keberangkatan' => $formatted2_datetime,
            'durasi' => $this->input->post('durasi'),
            'berangkat_dari' => $this->input->post('berangkat_dari'),
            'pkt_quad' => preg_replace('/\D/', '', $this->input->post('pkt_quad')),
            'pkt_triple' => preg_replace('/\D/', '', $this->input->post('pkt_triple')),
            'pkt_double' => preg_replace('/\D/', '', $this->input->post('pkt_double')),
            'keberangkatan' => $this->input->post('keberangkatan'),
            'kepulangan' => $this->input->post('kepulangan')
        );

        // Update data penawaran
        $this->db->update('tbl_penawaran', $data, ['id' => $id]);

        // // Pastikan semua input adalah array
        if (!is_array($layanan_ids) || !is_array($statuses) || !is_array($extra_inputs)) {
            echo json_encode(array("status" => FALSE, "message" => "Data tidak valid."));
            return;
        }

        // Loop over each layanan and check whether to update, insert, or delete
        foreach ($layanan_ids as $index => $layanan_id) {
            $status = isset($statuses[$index]) ? $statuses[$index] : null; // Ambil status
            $extra_input = isset($extra_inputs[$index]) ? $extra_inputs[$index] : null; // Ambil nominal

            // Cek apakah layanan sudah ada di database
            $existing_layanan = $this->db->get_where('tbl_penawaran_detail_lyn', ['id_layanan' => $layanan_id, 'id_penawaran' => $id])->row();

            // Jika status kosong, hapus dari database
            if (empty($status)) {
                if ($existing_layanan) {
                    $this->db->delete('tbl_penawaran_detail_lyn', ['id' => $existing_layanan->id]);
                }
                continue; // Lewati iterasi ini dan lanjutkan ke layanan berikutnya
            }

            // Jika layanan sudah ada, lakukan update
            if ($existing_layanan) {
                if ($layanan_id == 9 && $status === 'Y') {
                    // Jika id_layanan 9 dan statusnya N, hapus nilai nominal
                    $data_layanan_update = array(
                        'is_active' => 'Y' // Set is_active hanya 'N'
                    );
                } else {
                    // Gabungkan nominal ke dalam is_active
                    $data_layanan_update = array(
                        'is_active' => $status . ' ' . $extra_input // Gabungkan status dengan nominal
                    );
                }
                $this->db->update('tbl_penawaran_detail_lyn', $data_layanan_update, ['id' => $existing_layanan->id]);
            } else {
                // Jika layanan tidak ada, lakukan insert
                $data_layanan_insert = array(
                    'id_penawaran' => $id,
                    'id_layanan' => $layanan_id,
                    'is_active' => $status . ' ' . $extra_input // Gabungkan status dengan nominal
                );

                // Debugging: Tampilkan data yang akan diinsert
                // var_dump($data_layanan_insert);
                $this->db->insert('tbl_penawaran_detail_lyn', $data_layanan_insert);
            }
        }

        // Proses Update Data Hotel

        // // Pastikan semua input adalah array
        if (!is_array($hotel_ids) || !is_array($statuses2)) {
            echo json_encode(array("status" => FALSE, "message" => "Data tidak valid."));
            return;
        }

        foreach ($hotel_ids as $index => $hotel_id) {
            $status2 = isset($statuses2[$index]) ? $statuses2[$index] : null;

            $existing_hotel = $this->db->get_where('tbl_penawaran_detail_htl', [
                'id_hotel' => $hotel_id,
                'id_penawaran' => $id
            ])->row();

            // Jika status kosong, hapus dari database
            if (empty($status2)) {
                if ($existing_hotel) {
                    $this->db->delete('tbl_penawaran_detail_htl', ['id' => $existing_hotel->id]);
                }
                continue; // Lewati iterasi ini dan lanjutkan ke hotel berikutnya
            }

            // Jika hotel sudah ada, lakukan update
            if ($existing_hotel) {
                $this->db->update('tbl_penawaran_detail_htl', ['is_active' => $status2], ['id' => $existing_hotel->id]);
            } else {
                // Jika hotel tidak ada, lakukan insert
                $data_hotel_insert = array(
                    'id_penawaran' => $id,
                    'id_hotel' => $hotel_id,
                    'is_active' => $status2 // Gabungkan status dengan nominal
                );

                // Debugging: Tampilkan data yang akan diinsert
                $this->db->insert('tbl_penawaran_detail_htl', $data_hotel_insert);
            }
        }

        // Mengembalikan status berhasil
        echo json_encode(array("status" => TRUE));
    }


    function delete($id)
    {
        // Hapus data master
        $this->db->delete('tbl_penawaran', ['id' => $id]);

        // Hapus data detail layanan
        $this->db->delete('tbl_penawaran_detail_lyn', ['id_penawaran' => $id]);

        // Hapus data detail hotel
        $this->db->delete('tbl_penawaran_detail_htl', ['id_penawaran' => $id]);

        // ambil semua 1 row data pada tabel penawaran
        $get_penawaran = $this->db->get('tbl_penawaran', ['id' => $id])->row_array();

        // ambil data no arsip pada penawaran
        $no_arsip = $get_penawaran['no_arsip'];

        // Hapus data arsip berdasarkan no_arsip
        $this->db->delete('tbl_arsip_pu', ['no_arsip' => $no_arsip]);

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
