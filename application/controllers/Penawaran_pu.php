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
            $action_print = ($print == 'Y') ? '<a class="btn btn-success btn-circle btn-sm" target="_blank" href="penawaran_pu/generate_pdf2/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>' : '';

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

        $data['layanan_termasuk'] = $this->M_penawaran_pu->getLayananTermasuk($kode);
        $data['layanan_tidak_termasuk'] = $this->M_penawaran_pu->getLayananTidakTermasuk($kode);

        $getPenawaran = $this->db->get_where('tbl_penawaran', ['no_arsip' => $kode])->row_array();
        $no_pelayanan = $getPenawaran['no_pelayanan'];
        $data['rundown'] = $this->M_penawaran_pu->getRundown($no_pelayanan);

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

        // INISIASI INPUT KE RUNDOWN
        $hari = $this->input->post('hari[]');
        $tanggal = $this->input->post('tanggal[]');
        $kegiatan = $this->input->post('hidden_kegiatan_[]');
        // PERULANGAN INPUT RUNDOWN
        for ($i = 1; $i <= count($_POST['hari']); $i++) {
            $data3[] = array(
                'no_pelayanan' => $no_pelayanan,
                'hari' => $hari[$i],
                'tanggal' => $tanggal[$i],
                'kegiatan' => $kegiatan[$i]
            );
        }
        $this->M_penawaran_pu->save_rundown($data3);

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
        // Hapus data detail layanan
        $this->db->delete('tbl_penawaran_detail_lyn', ['id_penawaran' => $id]);

        // Hapus data detail hotel
        $this->db->delete('tbl_penawaran_detail_htl', ['id_penawaran' => $id]);

        // ambil semua 1 row data pada tabel master
        $get_penawaran = $this->db->get('tbl_penawaran', ['id' => $id])->row_array();

        // ambil data no arsip pada penawaran
        $no_arsip = $get_penawaran['no_arsip'];

        // Hapus data arsip berdasarkan no_arsip
        $this->db->delete('tbl_arsip_pu', ['no_arsip' => $no_arsip]);

        // Ambil data no pelayanan dari tabel master
        $no_pelayanan = $get_penawaran['no_pelayanan'];

        // Hapus data arsip berdasarkan no_pelayanan
        $this->db->delete('tbl_rundown', ['no_pelayanan' => $no_pelayanan]);

        // Hapus data master
        $this->db->delete('tbl_penawaran', ['id' => $id]);

        echo json_encode(array("status" => TRUE));
    }

    // PRINTOUT TCPDF
    public function generate_pdf2($id)
    {
        // Load
        $this->load->library('t_cpdf');

        // INISIAI VARIABLE
        $penawaran = $this->M_penawaran_pu->getPenawaranById($id);
        $rundowns = $this->M_penawaran_pu->getRundown($penawaran->no_pelayanan);
        $hotels = $this->M_penawaran_pu->get_hotels($id);

        // Initialize the TCPDF object
        $t_cpdf = new t_cpdf('P', 'mm', 'A4', true, 'UTF-8', false);

        // Set document properties
        $t_cpdf->SetCreator(PDF_CREATOR);
        $t_cpdf->SetAuthor('Author Name');
        $t_cpdf->SetTitle('Penawaran PDF');

        $t_cpdf->SetMargins(15, 38, 15); // Margin kiri, atas (untuk header), kanan
        // $t_cpdf->SetHeaderMargin(40);    // Jarak antara header dan konten
        $t_cpdf->SetAutoPageBreak(true, 40); // Penanganan otomatis margin bawah

        // Add a new page
        $t_cpdf->AddPage();

        // Pilih font untuk isi
        $t_cpdf->SetFont('Poppins-Bold', '', 24);

        // Margin setup
        $left_margin = 10;
        $t_cpdf->SetLeftMargin($left_margin);  // Mengatur margin kiri

        // Bagian TO
        $t_cpdf->SetXY($left_margin, $t_cpdf->GetY());
        $t_cpdf->Cell(0, 10, 'PENAWARAN', 0, 1, 'L');

        // Name and title (Creative Director)
        $t_cpdf->SetFont('Poppins-Regular', '', 9);
        $t_cpdf->Cell(38, 5, 'No', 0, 0,);
        $t_cpdf->cell(5, 5, ':', 0, 0);
        $t_cpdf->Cell(50, 5, $penawaran->no_pelayanan, 0, 1);

        $t_cpdf->Cell(38, 5, 'Tanggal Dokumen', 0, 0);
        $t_cpdf->Cell(5, 5, ':', 0, 0);
        $t_cpdf->Cell(50, 5, date('d/m/y', strtotime($penawaran->created_at)), 0, 1);

        $t_cpdf->Cell(38, 5, 'Berlaku s.d.', 0, 0);
        $t_cpdf->Cell(5, 5, ':', 0, 0);
        $t_cpdf->Cell(50, 5, date('d/m/y', strtotime($penawaran->tgl_berlaku)), 0, 1);

        $t_cpdf->Cell(38, 5, 'Produk', 0, 0);
        $t_cpdf->Cell(5, 5, ':', 0, 0);
        $t_cpdf->Cell(50, 5, $penawaran->produk, 0, 1);

        $t_cpdf->Cell(38, 5, 'Kepada', 0, 0);
        $t_cpdf->Cell(5, 5, ':', 0, 0);
        $t_cpdf->Cell(50, 5, $penawaran->pelanggan, 0, 1);

        // QRCODE
        // QR Code configuration
        $config = [
            'cacheable'    => false, // No need to cache the QR code
            'imagedir'     => '',    // Do not save the QR code to a directory
            'quality'      => true,
            'size'         => 1024,
            'black'        => [0, 0, 0],       // Black QR code
            'white'        => [255, 255, 255], // White background
        ];
        $this->ciqrcode->initialize($config);

        // QR Code parameters
        $params = [
            'data'     => 'https://example.com', // The content of the QR code
            'level'    => 'H',                  // Error correction level (L, M, Q, H)
            'size'     => 10,                   // Size of the QR code
            'savename' => null,                 // Do not save the QR code
        ];

        // Generate QR Code directly into a variable
        ob_start();
        $this->ciqrcode->generate($params);
        $qrCodeImage = ob_get_clean();

        // Add QR Code image to PDF
        $t_cpdf->Image('@' . $qrCodeImage, 140, 42, 32, 32); // Directly add the image from memory

        // Add favicon with white background
        $t_cpdf->SetFillColor(255, 255, 255); // RGB for white
        $t_cpdf->Rect(152.5, 54, 7, 8, 'F');   // X, Y, Width, Height, 'F' for filled rectangle
        $t_cpdf->Image('assets/backend/img/favicon-pu.png', 152.5, 54, 7, 8);

        $t_cpdf->Ln(5); // SPASI

        // HEADER LAYANAN
        $t_cpdf->SetFont('Poppins-Regular', '', 11);
        $t_cpdf->SetFillColor(252, 118, 19);
        $t_cpdf->SetTextColor(255, 255, 255);
        $t_cpdf->Cell(0, 10, 'LAYANAN', 0, 1, 'L', true);
        $t_cpdf->SetTextColor(0, 0, 0);


        // Spasi antara bagian atas dan konten
        $t_cpdf->Ln(2);

        // Konten text (justify)
        $t_cpdf->SetFont('Poppins-Regular', '', 9);

        // HEADER DESKRIPSI
        $t_cpdf->Cell(100, 5, 'Deskripsi :', 0, 0);
        $right_column_x = 120;

        // Keberangkatan
        $t_cpdf->SetX($right_column_x); // Pindahkan posisi ke kolom kanan
        $t_cpdf->Cell(26, 5, 'Keberangkatan', 0, 0);
        $t_cpdf->cell(2, 5, ':', 0, 0);
        $tgl_keberangkatan = substr(date('d m Y', strtotime($penawaran->tgl_keberangkatan)), 3, 2);
        $tanggal = date('d ', strtotime($penawaran->tgl_keberangkatan));
        $bulan_formatted = '';
        $tahun = date(' Y', strtotime($penawaran->tgl_keberangkatan));

        if ($tgl_keberangkatan == 1) {
            $bulan_formatted = $tanggal . 'Januari' . $tahun;
        } else if ($tgl_keberangkatan == 2) {
            $bulan_formatted = $tanggal . 'Februari' . $tahun;
        } else if ($tgl_keberangkatan == 3) {
            $bulan_formatted = $tanggal . 'Maret' . $tahun;
        } else if ($tgl_keberangkatan == 4) {
            $bulan_formatted = $tanggal . 'April' . $tahun;
        } else if ($tgl_keberangkatan == 5) {
            $bulan_formatted = $tanggal . 'Mei' . $tahun;
        } else if ($tgl_keberangkatan == 6) {
            $bulan_formatted = $tanggal . 'Juni' . $tahun;
        } else if ($tgl_keberangkatan == 7) {
            $bulan_formatted = $tanggal . 'Juli' . $tahun;
        } else if ($tgl_keberangkatan == 8) {
            $bulan_formatted = $tanggal . 'Agustus' . $tahun;
        } else if ($tgl_keberangkatan == 9) {
            $bulan_formatted = $tanggal . 'September' . $tahun;
        } else if ($tgl_keberangkatan == 10) {
            $bulan_formatted = $tanggal . 'Oktober' . $tahun;
        } else if ($tgl_keberangkatan == 11) {
            $bulan_formatted = $tanggal . 'November' . $tahun;
        } else if ($tgl_keberangkatan == 12) {
            $bulan_formatted = $tanggal . 'Desember' . $tahun;
        }

        $t_cpdf->Cell(50, 5, $bulan_formatted, 0, 1);
        // Durasi
        $t_cpdf->SetX($right_column_x); // Pindahkan posisi ke kolom kanan
        $t_cpdf->Cell(26, 5, 'Durasi', 0, 0);
        $t_cpdf->cell(2, 5, ':', 0, 0);
        $t_cpdf->Cell(50, 5, $penawaran->durasi . ' Hari', 0, 1);
        // Berangkat dari
        $t_cpdf->SetX($right_column_x); // Pindahkan posisi ke kolom kanan
        $t_cpdf->Cell(26, 5, 'Berangkat Dari', 0, 0);
        $t_cpdf->cell(2, 5, ':', 0, 0);
        $t_cpdf->Cell(50, 5, $penawaran->berangkat_dari, 0, 1);

        $keberangkatanY = $t_cpdf->GetY();

        // Mengatur lebar untuk konten agar justify bisa bekerja
        $content_width = 100;  // Misal, lebar halaman adalah 210, jadi margin kiri 10 dan margin kanan 10

        // KONTEN DESKRIPSI
        $body_text = $penawaran->deskripsi;
        $t_cpdf->Sety(91 + 4);
        $t_cpdf->MultiCell($content_width, 4, $body_text, 0, 'J');  // 'J' digunakan untuk rata kiri dan kanan (justify)

        $deskripsiY = $t_cpdf->GetY();

        // Kondisi penggunaan Y
        if ($deskripsiY > $keberangkatanY) {
            $useY = $deskripsiY;
        } else {
            $useY = $keberangkatanY;
        }

        $t_cpdf->Sety($useY + 5);

        // HEADER LAYANAN TERMASUK DAN TIDAK TERMASUK
        $t_cpdf->SetFont('Poppins-Regular', '', 9);
        $t_cpdf->Cell(100, 5, 'Layanan Termasuk:', 0, 0, 'L'); // Header kiri
        $trmskY = $t_cpdf->GetY();

        $t_cpdf->SetX($right_column_x); // Pindahkan posisi ke kolom kanan
        $t_cpdf->Cell(90, 5, 'Layanan Tidak Termasuk:', 0, 1, 'L'); // Header kanan

        // Ambil data layanan
        $kode = $penawaran->no_arsip; // Kode arsip dari penawaran
        $data_layanan_termasuk = $this->M_penawaran_pu->getLayananTermasuk($kode); // Data layanan termasuk
        $data_layanan_tidak_termasuk = $this->M_penawaran_pu->getLayananTidakTermasuk($kode); // Data layanan tidak termasuk

        // Menentukan jumlah baris terbesar antara dua data
        $maxRows = max(count($data_layanan_termasuk), count($data_layanan_tidak_termasuk));

        // Menampilkan data layanan secara sejajar
        $t_cpdf->SetFont('Poppins-Regular', '', 9); // Atur font
        for ($i = 0; $i < $maxRows; $i++) {
            // Kolom Layanan Termasuk
            if (isset($data_layanan_termasuk[$i])) {
                $nomorTermasuk = $i + 1;
                $t_cpdf->Cell(4, 5, $nomorTermasuk . '.', 0, 0, 'L'); // Nomor
                $t_cpdf->Cell(106, 5, $data_layanan_termasuk[$i]['nama_layanan'], 0, 0, 'L'); // Nama layanan
            } else {
                $t_cpdf->Cell(90, 5, '', 0, 0); // Kosongkan cell jika data habis
            }

            // Kolom Layanan Tidak Termasuk
            if (isset($data_layanan_tidak_termasuk[$i])) {
                $nomorTidakTermasuk = $i + 1;
                $t_cpdf->Cell(4, 5, $nomorTidakTermasuk . '.', 0, 0, 'L'); // Nomor
                $t_cpdf->Cell(100, 5, $data_layanan_tidak_termasuk[$i]['nama_layanan'], 0, 1, 'L'); // Nama layanan
            } else {
                $t_cpdf->Cell(90, 5, '', 0, 1); // Kosongkan cell jika data habis
            }
        }

        // Jika tidak ada layanan
        if (empty($data_layanan_termasuk) && empty($data_layanan_tidak_termasuk)) {
            $t_cpdf->SetFont('Poppins-Regular', 'I', 9); // Font miring untuk pesan kosong
            $t_cpdf->Cell(0, 5, 'Tidak ada layanan tersedia.', 0, 1, 'C');
        }

        $trmskY = $t_cpdf->GetY();

        $t_cpdf->Ln(10); // Spasi antara paragraf

        // KONTEN HOTEL DAN PENERBANGAN
        $t_cpdf->SetFont('Poppins-Regular', '', 9);
        $t_cpdf->SetX(100); // Pindahkan posisi ke kolom kanan
        $t_cpdf->Cell(25, 5, 'Hotel Makkah', 0, 0,);
        $t_cpdf->SetFont('ZapfDingbats');
        $stars = '';
        for ($i = 0; $i < 5; $i++) {
            $stars .= chr(73);
        }
        $t_cpdf->cell(15, 5, $stars, 0, 0);
        $t_cpdf->SetFont('Poppins-Regular', '', 9);
        $t_cpdf->cell(3, 5, ':', 0, 0);
        $t_cpdf->Cell(40, 5, 'Sofwah Orchid', 0, 1);

        $t_cpdf->SetX(100); // Pindahkan posisi ke kolom kanan
        $t_cpdf->Cell(25, 5, 'Hotel Madinah', 0, 0);
        $t_cpdf->SetFont('ZapfDingbats');
        $stars2 = '';
        for ($i = 0; $i < 5; $i++) {
            $stars2 .= chr(73);
        }
        $t_cpdf->cell(15, 5, $stars2, 0, 0);
        $t_cpdf->SetFont('Poppins-Regular', '', 9);
        $t_cpdf->Cell(3, 5, ':', 0, 0);
        $t_cpdf->Cell(40, 5, 'Taiba Front', 0, 1);

        $t_cpdf->SetX(100); // Pindahkan posisi ke kolom kanan
        $t_cpdf->Cell(40, 5, 'Keberangkatan', 0, 0);
        $t_cpdf->Cell(3, 5, ':', 0, 0);
        $t_cpdf->Cell(40, 5, 'Direct Saudia Airlines SV817', 0, 1);

        $t_cpdf->SetX(100); // Pindahkan posisi ke kolom kanan
        $t_cpdf->Cell(40, 5, 'Kepulangan', 0, 0);
        $t_cpdf->Cell(3, 5, ':', 0, 0);
        $t_cpdf->Cell(40, 5, 'Direct Saudia Airlines SV826', 0, 1);

        // HEADER LAYANAN PASTI
        $t_cpdf->SetFont('Poppins-Regular', '', 11);
        $t_cpdf->SetFillColor(252, 118, 19);
        $t_cpdf->SetTextColor(255, 255, 255);
        $t_cpdf->Cell(0, 10, 'LAYANAN PASTI', 0, 1, 'L', true);
        $t_cpdf->SetTextColor(0, 0, 0);

        // Spasi antara konten dan signature
        $t_cpdf->Ln(1);

        $layananPastiY = $t_cpdf->GetY();

        $body_text4 = '<ol>
            <li>Konsultasi Gratis</li>
            <li>Gratis Bantuan Pembuatan Paspor</li>
            <li>Gratis Antar Dokumen & Perlengkapan</li>
            <li>Gratis Pendampingan Manasik</li>
        </ol>';
        $t_cpdf->writeHTMLCell(
            900,                    // Lebar sel
            0,                     // Tinggi sel (0 berarti tinggi dinamis)
            -1,       // Posisi X
            $t_cpdf->GetY(),       // Posisi Y saat ini
            $body_text4,           // Konten HTML
            0,                     // Border (0 = tidak ada border)
            1,                     // Line break (1 = pindah ke baris baru setelah cell)
            false,                 // Fill (false = tidak ada latar belakang)
            true,                  // Auto padding
            'J',                   // Align (L = kiri)
            true                   // Konversi tag HTML
        );

        $t_cpdf->Sety($layananPastiY);
        $body_text5 = '<ol>
            <li>Gratis Handling Keberangkatan</li>
            <li>Gratis Handling Kepulangan</li>
            <li>Jaminan Pasti Berangkat</li>
            <li>Garansi 100% Uang Kembali Apabila Travel Gagal Memberangkatkan</li>
        </ol>';
        $t_cpdf->writeHTMLCell(
            90,                    // Lebar sel
            0,                     // Tinggi sel (0 berarti tinggi dinamis)
            98,       // Posisi X
            $t_cpdf->GetY(),       // Posisi Y saat ini
            $body_text5,           // Konten HTML
            0,                     // Border (0 = tidak ada border)
            1,                     // Line break (1 = pindah ke baris baru setelah cell)
            false,                 // Fill (false = tidak ada latar belakang)
            true,                  // Auto padding
            'J',                   // Align (L = kiri)
            true                   // Konversi tag HTML
        );

        // Add a new page
        $t_cpdf->AddPage();

        // Awal dari tabel
        $tbl = <<<EOD
<table border="1" cellpadding="4">
<thead>
 <tr>
  <th width="100" align="center">Hari</th>
  <th width="140" align="center">Tanggal</th>
  <th width="300" align="center">Kegiatan</th>
 </tr>
</thead>
<tbody>
EOD;

        // Looping melalui rundown untuk menambahkan baris dinamis
        foreach ($rundowns as $rundown) {
            $tbl .= '<tr>';
            $tbl .= '<td width="100" align="center">' . $rundown['hari'] . '</td>';
            $tbl .= '<td width="140" align="center">' . $rundown['tanggal'] . '</td>';
            $tbl .= '<td width="300">' . $rundown['kegiatan'] . '</td>';
            $tbl .= '</tr>';
        }

        // Akhir dari tabel
        $tbl .= <<<EOD
</tbody>
</table>
EOD;
        $t_cpdf->Sety(38);
        $t_cpdf->writeHTML($tbl, true, false, false, false, '');

        // // Table Header
        // $t_cpdf->Sety(38);
        // $t_cpdf->SetFont('Poppins-Bold', '', 10);
        // $t_cpdf->Cell(32, 5, 'Hari', 1, 0, 'C');
        // $t_cpdf->Cell(32, 5, 'Tanggal', 1, 0, 'C');
        // $t_cpdf->Cell(127, 5, 'Kegiatan', 1, 1, 'C');

        // $t_cpdf->SetFont('Poppins-Regular', '', 10);

        // // Looping melalui rundown
        // foreach ($rundowns as $rundown) {
        //     $content_text = $rundown->kegiatan;

        //     // Hitung jumlah baris teks untuk kolom 3
        //     $lines = $t_cpdf->getNumLines($content_text, 127);

        //     // Hitung tinggi total untuk setiap sel, sesuaikan dengan tinggi baris kolom 3
        //     $height3 = round($lines * 5); // 5 adalah tinggi baris (disesuaikan sesuai kebutuhan)

        //     // Menulis data ke PDF dengan menyesuaikan tinggi kolom 1 dan 2
        //     $t_cpdf->Cell(32, $height3, $rundown->hari, 1, 0, 'L'); // Kolom 1
        //     $t_cpdf->Cell(32, $height3, $rundown->tanggal, 1, 0, 'C'); // Kolom 2

        //     // Menulis kolom 3 dengan MultiCell yang menyesuaikan tinggi
        //     $t_cpdf->MultiCell(127, 5, $content_text, 1, 'L'); // Kolom 3

        //     // Menyesuaikan posisi Y setelah menulis kolom 3, agar kolom 1 dan 2 tetap sejajar
        //     $t_cpdf->SetY($t_cpdf->GetY() + $height3); // Sesuaikan posisi Y berdasarkan tinggi kolom 3
        // }


        // Output PDF (tampilkan di browser)
        $t_cpdf->Output('example.t_cpdf', 'I'); // 'I' untuk menampilkan di browser
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
