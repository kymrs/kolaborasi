<?php
defined('BASEPATH') or exit('No direct script access allowed');


use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class Pu_land_arrangement extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_pu_land_arrangement');
        $this->load->model('backend/M_notifikasi');
        $this->load->helper('date');
        $this->M_login->getsecurity();
        // $this->load->library('ciqrcode');
    }

    public function index()
    {
        // $data['notif'] = $this->M_notifikasi->pending_notification();
        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        ($akses->view_level == 'N' ? redirect('auth') : '');
        $data['add'] = $akses->add_level;

        $data['title'] = "backend/pu_penawaran/pu_land_arrangement_list";
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
        $list = $this->M_pu_land_arrangement->get_datatables();
        $data = array();
        $no = $_POST['start'];

        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        $read = $akses->view_level;
        $edit = $akses->edit_level;
        $delete = $akses->delete_level;
        $print = $akses->print_level;

        //LOOPING DATATABLES
        foreach ($list as $field) {

            $action_read = ($read == 'Y') ? '<a href="pu_land_arrangement/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>&nbsp;' : '';
            $action_edit = ($edit == 'Y') ? '<a href="pu_land_arrangement/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;' : '';
            $action_delete = ($delete == 'Y') ? '<a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;' : '';
            $action_print = ($print == 'Y') ? '<a class="btn btn-success btn-circle btn-sm" target="_blank" href="pu_land_arrangement/generate_pdf2/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>' : '';

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
            "recordsTotal" => $this->M_pu_land_arrangement->count_all(),
            "recordsFiltered" => $this->M_pu_land_arrangement->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    public function read_form($id)
    {
        $data['penawaran'] = $this->M_pu_land_arrangement->getPenawaran($id);

        $data['id'] = $id;
        if ($data['penawaran'] == null) {
            $this->load->view('backend/pu_penawaran/404');
        } else {
            $no_arsip = $data['penawaran']->no_arsip;

            // Create a QR code instance with the data you want to encode
            $qrCode = new QrCode('https://arsip.pengenumroh.com/' . $no_arsip);

            // Create the writer for PNG format
            $writer = new PngWriter();

            // Generate the QR code and get it as a Data URI (base64 encoded)
            $result = $writer->write($qrCode)->getDataUri();

            // Pass the base64 QR code data to the view
            $data['qr_code'] = $result;

            $data['title'] = 'backend/pu_penawaran/pu_land_arrangement_read';
            $data['title_view'] = 'Land Arrangement';
            $data['rundowns'] = $this->M_pu_land_arrangement->get_rundown($data['penawaran']->no_pelayanan);
            $data['hotel'] = $this->M_pu_land_arrangement->getHotel($id);
            $data['tgl_keberangkatan'] = $this->M_pu_land_arrangement->anotherMethod($data['penawaran']->tgl_keberangkatan);
            $this->load->view('backend/home', $data);
        }
    }

    public function add_form()
    {
        // $this->load->model('backend/M_notifikasi');
        // $data['notif'] = $this->M_notifikasi->pending_notification();
        $data['id'] = 0;
        $data['title'] = 'backend/pu_penawaran/pu_land_arrangement_form';
        // $data['products'] = $this->db->select('id, nama')->from('tbl_produk')->get()->result_object();
        $data['hotel'] = $this->db->get('pu_hotel')->result_array();
        $data['title_view'] = 'Land Arrangement Form';
        $this->load->view('backend/home', $data);
    }

    function edit_form($id)
    {
        $this->load->model('backend/M_notifikasi');

        $data['id'] = $id;
        $data['aksi'] = 'update';
        $data['title'] = 'backend/pu_penawaran/pu_land_arrangement_form';
        // $data['products'] = $this->db->select('id, nama')->from('tbl_produk')->get()->result_object();
        $data['hotel'] = $this->db->get('pu_hotel')->result_array();
        $data['title_view'] = 'Edit Land Arrangement Form';
        $this->load->view('backend/home', $data);
    }

    function edit_data($id)
    {
        $data['master'] = $this->db->get_where('pu_land_arrangement', ['id' => $id])->row();
        $data['transaksi'] = $this->db->get_where('pu_rundown', ['no_pelayanan' => $data['master']->no_pelayanan])->result_array();
        $data['hotel'] = $this->M_pu_land_arrangement->get_la_hotel($id); // Ambil detail hotel
        echo json_encode($data);
    }

    public function generate_kode()
    {
        $date = date('Y-m-d h:i:sa');
        $kode = $this->M_pu_land_arrangement->max_kode($date)->row();
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
        $kode = $this->M_pu_land_arrangement->max_kode($date)->row();
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

        $arsip = $this->M_pu_land_arrangement->max_kode_arsip($date)->row();
        if (empty($arsip->no_arsip)) {
            $no_urut2 = 1;
        } else {
            $no_urut2 = substr($arsip->no_arsip, 6) + 1;
        }

        //GENERATE NOMOR ARSIP
        $urutan2 = str_pad($no_urut2, 2, "0", STR_PAD_LEFT);
        $no_arsip = 'PU' . $year2 . $bulan . $urutan2;

        //CONVERT TIME
        // Ambil nilai input datetime dari form
        $input_datetime = $this->input->post('tgl_berlaku');
        $input2_datetime = $this->input->post('tgl_keberangkatan');

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
            'catatan' => $this->input->post('catatan_content'),
            'keberangkatan' => $this->input->post('keberangkatan'),
            'kepulangan' => $this->input->post('kepulangan')
        );

        if ($id_la = $this->M_pu_land_arrangement->save($data)) {

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
                            'id_la' => $id_la, // ID penawaran yang baru disimpan
                            'id_hotel' => $id,
                            'is_active' => $status2[$index], // Nilai status2
                        ];
                    }
                }

                // Hanya insert jika ada data yang valid
                if (!empty($hotel_data)) {
                    $this->M_pu_land_arrangement->save_hotel($hotel_data);
                } else {
                    // PESAN ERROR TABLE HOTEL
                    echo json_encode(array("status" => FALSE, "error" => "hotel"));
                }
            }

            // INISIASI INPUT KE RUNDOWN
            $hari = $this->input->post('hari[]');
            $tanggal = $this->input->post('tanggal[]');
            $kegiatan = $this->input->post('hidden_kegiatan_[]');
            // PERULANGAN INPUT RUNDOWN
            for ($i = 1; $i <= count($_POST['hari']); $i++) {
                $data2[] = array(
                    'no_pelayanan' => $no_pelayanan,
                    'hari' => $hari[$i],
                    'tanggal' => $tanggal[$i],
                    'kegiatan' => $kegiatan[$i]
                );
            }

            if ($this->M_pu_land_arrangement->save_rundown($data2)) {
                //ARSIP
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
                if ($this->M_pu_land_arrangement->save_arsip($data3)) {
                    // PESAN PENGINPUTAN BERHASIL
                    echo json_encode(array("status" => TRUE));
                } else {
                    // PESAN ERROR TABLE RUNDOWN
                    echo json_encode(array("status" => FALSE, "error" => "arsip"));
                }
            } else {
                // PESAN ERROR TABLE RUNDOWN
                echo json_encode(array("status" => FALSE, "error" => "rundown"));
            }
        } else {
            // PESAN ERROR TABLE MASTER
            echo json_encode(array("status" => FALSE, "error" => "master"));
        }
    }

    public function update($id)
    {
        //CONVERT TIME
        // Ambil nilai input datetime dari form
        $input_datetime = $this->input->post('tgl_berlaku');
        $input2_datetime = $this->input->post('tgl_keberangkatan');

        // Ubah format dari 'Y-m-dTH:i' ke 'Y-m-d H:i:s' agar sesuai dengan format MySQL
        $formatted_datetime = date('Y-m-d', strtotime($input_datetime));
        $formatted2_datetime = date('Y-m-d', strtotime($input2_datetime));

        $data = array(
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
            'catatan' => $this->input->post('catatan_content'),
            'keberangkatan' => $this->input->post('keberangkatan'),
            'kepulangan' => $this->input->post('kepulangan')
        );
        $this->db->update('pu_land_arrangement', $data, ['id' => $id]);

        // UPDATE TRANSAKSI PENAWARAN LAND_ARRANGEMENT
        $la_id = $this->input->post('hidden_id[]');
        $hari = $this->input->post('hari[]');
        $tanggal = $this->input->post('tanggal[]');
        $kegiatan = $this->input->post('hidden_kegiatan_[]');
        // UNTUK MENGHAPUS ROW YANG TELAH DIDELETE
        $deletedRows = json_decode($this->input->post('deleted_rows'), true);
        if (!empty($deletedRows)) {
            foreach ($deletedRows as $id2) {
                // Hapus row dari database berdasarkan ID
                $this->db->where('id', $id2);
                $this->db->delete('pu_rundown');
            }
        }

        //MELAKUKAN REPLACE DATA LAMA DENGAN YANG BARU
        for ($i = 1; $i <= count($_POST['hari']); $i++) {
            // Set id menjadi NULL jika id_detail tidak ada atau kosong
            $id2 = !empty($la_id[$i]) ? $la_id[$i] : NULL;
            if (!empty($kegiatan[$i])) {
                $data2[$i] = array(
                    'id' => $id2,
                    'no_pelayanan' => $this->input->post('no_pelayanan'),
                    'hari' => $hari[$i],
                    'tanggal' => $tanggal[$i],
                    'kegiatan' => $kegiatan[$i]
                );
                // // Menggunakan db->replace untuk memasukkan atau menggantikan data
                $this->db->replace('pu_rundown', $data2[$i]);
            } else {
                $data2[$i] = array(
                    'id' => $id2,
                    'no_pelayanan' => $this->input->post('no_pelayanan'),
                    'hari' => $hari[$i],
                    'tanggal' => $tanggal[$i],
                );
                // // Menggunakan db->replace untuk memasukkan atau menggantikan data
                $this->db->where('id', $data2[$i]['id']); // Tambahkan kondisi WHERE
                $this->db->update('pu_rundown', $data2[$i]); // Lakukan update
            }
        }

        // Data Hotel
        $hotel_ids = $this->input->post('id_hotel');
        $statuses2 = $this->input->post('status2');

        // Proses Update Data Hotel

        // // Pastikan semua input adalah array
        if (!is_array($hotel_ids) || !is_array($statuses2)) {
            echo json_encode(array("status" => FALSE, "message" => "Data tidak valid."));
            return;
        }

        foreach ($hotel_ids as $index => $hotel_id) {
            $status2 = isset($statuses2[$index]) ? $statuses2[$index] : null;

            $existing_hotel = $this->db->get_where('pu_land_arrangement_htl', [
                'id_hotel' => $hotel_id,
                'id_la' => $id
            ])->row();

            // Jika status kosong, hapus dari database
            if (empty($status2)) {
                if ($existing_hotel) {
                    $this->db->delete('pu_land_arrangement_htl', ['id' => $existing_hotel->id]);
                }
                continue; // Lewati iterasi ini dan lanjutkan ke hotel berikutnya
            }

            // Jika hotel sudah ada, lakukan update
            if ($existing_hotel) {
                $this->db->update('pu_land_arrangement_htl', ['is_active' => $status2], ['id' => $existing_hotel->id]);
            } else {
                // Jika hotel tidak ada, lakukan insert
                $data_hotel_insert = array(
                    'id_la' => $id,
                    'id_hotel' => $hotel_id,
                    'is_active' => $status2 // Gabungkan status dengan nominal
                );

                // Debugging: Tampilkan data yang akan diinsert
                $this->db->insert('pu_land_arrangement_htl', $data_hotel_insert);
            }
        }

        // var_dump($data2);
        echo json_encode(array("status" => TRUE));
    }

    function delete($id)
    {
        // Ambil no_pelayanan berdasarkan id
        $no_pelayanan = $this->db->select('no_pelayanan, no_arsip')
            ->from('pu_land_arrangement')
            ->where('id', $id)
            ->get()
            ->row();

        // Periksa apakah data ditemukan
        if ($no_pelayanan) {
            // Hapus dari tabel pu_land_arrangement
            $this->db->delete('pu_land_arrangement', ['id' => $id]);

            // Hapus dari tabel pu_rundown berdasarkan no_pelayanan
            $this->db->delete('pu_rundown', ['no_pelayanan' => $no_pelayanan->no_pelayanan]);

            // Hapus dari tabel pu_land_arrangement_htl berdasarkan id
            $this->db->delete('pu_land_arrangement_htl', ['id_la' => $id]);

            // Hapus dari tabel tbL-arsip berdasarkan nomor pelayanan
            $this->db->delete('pu_arsip', ['no_arsip' => $no_pelayanan->no_arsip]);

            // Kirim respons sukses
            echo json_encode(array("status" => TRUE, "message" => "Data berhasil dihapus"));
        } else {
            // Jika no_pelayanan tidak ditemukan, kirim respons error
            echo json_encode(array("status" => FALSE, "message" => "Data tidak ditemukan atau sudah dihapus"));
        }
    }

    // PRINTOUT TCPDF
    public function generate_pdf2($id)
    {
        // Load
        $this->load->library('t_cpdf');

        // INISIAI VARIABLE
        $penawaran = $this->M_pu_land_arrangement->getPenawaran($id);
        $rundowns = $this->M_pu_land_arrangement->get_rundown($penawaran->no_pelayanan);
        $hotels = $this->M_pu_land_arrangement->get_hotels($id);

        // Initialize the TCPDF object
        $t_cpdf = new t_cpdf('P', 'mm', 'A4', true, 'UTF-8', false);

        // Set document properties
        $t_cpdf->SetCreator(PDF_CREATOR);
        $t_cpdf->SetAuthor('Author Name');
        $t_cpdf->SetTitle('Penawaran PDF');

        $t_cpdf->SetMargins(15, 38, 15); // Margin kiri, atas (untuk header), kanan
        // $t_cpdf->SetHeaderMargin(40);    // Jarak antara header dan konten
        $t_cpdf->SetAutoPageBreak(true, 40); // Penanganan otomatis margin bawah

        $t_cpdf->AddFont('poppins-bold', '', base_url('\application\third_party\TCPDF-main\fonts\Poppins-Bold.php'));
        $t_cpdf->AddFont('Poppins-Regular', '', base_url('\application\third_party\TCPDF-main\fonts\Poppins-Regular.php'));

        // Add a new page
        $t_cpdf->AddPage();

        // Pilih font untuk isi
        $t_cpdf->SetFont('poppins-bold', '', 24);

        // Margin setup
        $left_margin = 10;
        $t_cpdf->SetLeftMargin($left_margin);  // Mengatur margin kiri

        // Bagian TO
        $t_cpdf->SetXY($left_margin, $t_cpdf->GetY());
        $t_cpdf->Cell(0, 10, 'PENAWARAN', 0, 1, 'L');

        // Name and title (Creative Director)
        $t_cpdf->SetFont('poppins-regular', '', 9);
        $t_cpdf->Cell(38, 6, 'No', 0, 0,);
        $t_cpdf->cell(5, 5, ':', 0, 0);
        $t_cpdf->Cell(50, 5, $penawaran->no_pelayanan, 0, 1);
        $tanggalDokumen = DateTime::createFromFormat('Y-m-d H:i:s', $penawaran->created_at);
        $t_cpdf->Cell(38, 5, 'Tanggal Dokumen', 0, 0);
        $t_cpdf->Cell(5, 5, ':', 0, 0);
        $t_cpdf->Cell(50, 5, $tanggalDokumen ? $tanggalDokumen->format('Y/m/d') : '-', 0, 1);
        $tanggalBerlaku = DateTime::createFromFormat('Y-m-d', $penawaran->tgl_berlaku);
        $t_cpdf->Cell(38, 5, 'Berlaku s.d.', 0, 0);
        $t_cpdf->Cell(5, 5, ':', 0, 0);
        $t_cpdf->Cell(50, 5, $tanggalBerlaku ? $tanggalBerlaku->format('Y/m/d') : '-', 0, 1);

        $t_cpdf->Cell(38, 5, 'Produk', 0, 0);
        $t_cpdf->Cell(5, 5, ':', 0, 0);
        $t_cpdf->Cell(50, 5, $penawaran->produk, 0, 1);

        $t_cpdf->Cell(38, 5, 'Kepada', 0, 0);
        $t_cpdf->Cell(5, 5, ':', 0, 0);
        $t_cpdf->Cell(50, 5, $penawaran->pelanggan, 0, 1);

        // QRCODE

        // Set QR Code data
        if ($penawaran->no_arsip == null) {
            $no_arsip = 'backend/pu_penawaran/404';
            $data = $no_arsip;
        } else {
            $no_arsip = $penawaran->no_arsip;
            $data = 'https://arsip.pengenumroh.com/' . $no_arsip;
        }

        // Create a QR code instance with the data you want to encode
        $qrCode = new QrCode($data);

        // Create the writer for PNG format
        $writer = new PngWriter();

        // Generate the QR code as a binary string (raw PNG data)
        $result = $writer->write($qrCode)->getString();

        // Add QR Code to PDF using TCPDF
        // Use TCPDF Image() method with '@"' to directly use the binary string
        $t_cpdf->Image('@' . $result, 140, 42, 32, 32, 'PNG');

        // Add favicon with white background
        $t_cpdf->SetFillColor(255, 255, 255); // RGB for white
        $t_cpdf->Rect(153.5, 56, 5, 6, 'F');   // X, Y, Width, Height, 'F' for filled rectangle
        $t_cpdf->Image('assets/backend/img/favicon-pu.png', 153.5, 56, 5, 6);


        $t_cpdf->Ln(5); // SPASI

        // HEADER LAYANAN
        $t_cpdf->SetFont('poppins-regular', '', 11);
        $t_cpdf->SetFillColor(252, 118, 19);
        $t_cpdf->SetTextColor(255, 255, 255);
        $t_cpdf->Cell(0, 10, 'LAYANAN', 0, 1, 'L', true);
        $t_cpdf->SetTextColor(0, 0, 0);


        // Spasi antara bagian atas dan konten
        $t_cpdf->Ln(2);

        // Konten text (justify)
        $t_cpdf->SetFont('poppins-regular', '', 9);

        // HEADER DESKRIPSI
        $t_cpdf->Cell(100, 5, 'Deskripsi :', 0, 0);
        $right_column_x = 120;

        // Keberangkatan
        $t_cpdf->SetX($right_column_x); // Pindahkan posisi ke kolom kanan
        $t_cpdf->Cell(26, 5, 'Keberangkatan', 0, 0);
        $t_cpdf->cell(2, 5, ':', 0, 0);
        $t_cpdf->Cell(50, 5, $this->M_pu_land_arrangement->getTanggal($penawaran->tgl_keberangkatan), 0, 1);
        // Durasi
        $t_cpdf->SetX($right_column_x); // Pindahkan posisi ke kolom kanan
        $t_cpdf->Cell(26, 5, 'Durasi', 0, 0);
        $t_cpdf->cell(2, 5, ':', 0, 0);
        $t_cpdf->Cell(50, 5, $penawaran->durasi, 0, 1);
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

        // HEADER LAYANAN TERMASUK
        $t_cpdf->SetFont('poppins-regular', '', 9);
        $t_cpdf->Cell(100, 5, 'Layanan Termasuk :', 0, 0);

        $trmskY = $t_cpdf->GetY();

        // HEADER LAYANAN TIDAK TERMASUK
        $t_cpdf->SetX($right_column_x); // Pindahkan posisi ke kolom kanan
        $t_cpdf->Cell(80, 5, 'Layanan Tidak Termasuk :', 0, 1);

        // KONTEN LAYANAN TIDAK TERMASUK
        $t_cpdf->SetX($right_column_x);
        $body_text3 = $penawaran->layanan_tdk_trmsk;

        if (strpos($body_text3, '<ol>') !== false) {
            $x3 = 111;
        } else if (strpos($body_text3, '<li>') !== false) {
            $x3 = 111;
        } else {
            $x3 = 120;
        }

        $t_cpdf->writeHTMLCell(
            80,                    // Lebar sel
            0,                     // Tinggi sel (0 berarti tinggi dinamis)
            $x3,       // Posisi X
            $t_cpdf->GetY(),       // Posisi Y saat ini
            $body_text3,           // Konten HTML
            0,                     // Border (0 = tidak ada border)
            1,                     // Line break (1 = pindah ke baris baru setelah cell)
            false,                 // Fill (false = tidak ada latar belakang)
            true,                  // Auto padding
            'L',                   // Align (L = kiri)
            true                   // Konversi tag HTML
        );

        $t_cpdf->Ln(10); // Spasi antara paragraf

        // KONTEN HOTEL DAN PENERBANGAN
        foreach ($hotels as $hotel) {
            $t_cpdf->SetFont('poppins-regular', '', 9);
            $t_cpdf->SetX(100); // Pindahkan posisi ke kolom kanan
            $t_cpdf->Cell(25, 5, 'Hotel ' . $hotel->kota, 0, 0,);
            $t_cpdf->SetFont('ZapfDingbats');
            $stars = '';
            for ($i = 0; $i < 5; $i++) {
                if ($i < $hotel->rating) {
                    $stars .= chr(72);
                } else {
                    $stars .= chr(73);
                }
            }
            $t_cpdf->cell(15, 5, $stars, 0, 0);
            $t_cpdf->SetFont('poppins-regular', '', 9);
            $t_cpdf->cell(3, 5, ':', 0, 0);
            $t_cpdf->Cell(40, 5, $hotel->nama_hotel, 0, 1);
        }

        // PENERBANGAN
        $t_cpdf->SetX(100); // Pindahkan posisi ke kolom kanan
        $t_cpdf->Cell(40, 5, 'Keberangkatan', 0, 0);
        $t_cpdf->Cell(3, 5, ':', 0, 0);
        $t_cpdf->Cell(40, 5, $penawaran->keberangkatan, 0, 1);

        $t_cpdf->SetX(100); // Pindahkan posisi ke kolom kanan
        $t_cpdf->Cell(40, 5, 'Kepulangan', 0, 0);
        $t_cpdf->Cell(3, 5, ':', 0, 0);
        $t_cpdf->Cell(40, 5, $penawaran->kepulangan, 0, 1);

        // Dapatkan posisi Y setelah konten terakhir
        $tidak_termasukY = $t_cpdf->GetY();

        // KONTEN LAYANAN TERMASUK
        $t_cpdf->Sety($trmskY + 5);
        $body_text2 = $penawaran->layanan_trmsk;

        if (strpos($body_text2, '<ol>') !== false) {
            $x2 = 1;
        } else if (strpos($body_text2, '<li>') !== false) {
            $x2 = 1;
        } else {
            $x2 = 10;
        }


        $t_cpdf->writeHTMLCell(
            80,                    // Lebar sel
            0,                     // Tinggi sel (0 berarti tinggi dinamis)
            $x2,       // Posisi X
            $t_cpdf->GetY(),       // Posisi Y saat ini
            $body_text2,           // Konten HTML
            0,                     // Border (0 = tidak ada border)
            1,                     // Line break (1 = pindah ke baris baru setelah cell)
            false,                 // Fill (false = tidak ada latar belakang)
            true,                  // Auto padding
            'L',                   // Align (L = kiri)
            true                   // Konversi tag HTML
        );

        // Dapatkan posisi Y setelah konten terakhir
        $termasukY = $t_cpdf->GetY();

        // Kondisi penggunaan setY yang sesuai
        if ($tidak_termasukY > $termasukY) {
            $useY2 = $tidak_termasukY;
        } else {
            $useY2 = $termasukY;
        }

        // Set posisi header "HARGA PAKET"
        $t_cpdf->SetY($useY2 + 5);

        // HEADER HARGA PAKET
        $t_cpdf->SetFont('poppins-regular', '', 11);
        $t_cpdf->SetFillColor(252, 118, 19);
        $t_cpdf->SetTextColor(255, 255, 255);
        $t_cpdf->Cell(0, 10, 'HARGA PAKET', 0, 1, 'L', true);
        $t_cpdf->SetTextColor(0, 0, 0);

        // Spasi antara konten dan signature
        $t_cpdf->Ln(2);

        // BIAYA
        $t_cpdf->SetFont('poppins-bold', '', 15);
        $t_cpdf->Cell(20, 5, 'BIAYA', 0, 0,);
        $t_cpdf->cell(5, 5, ':', 0, 0);
        $t_cpdf->Cell(50, 5, 'Rp. ' . number_format($penawaran->biaya, 0, ',', '.'), 0, 1);

        // Spasi antara konten dan signature
        $t_cpdf->Ln(2);

        // HEADER LAYANAN PASTI
        $t_cpdf->SetFont('poppins-regular', '', 11);
        $t_cpdf->SetFillColor(252, 118, 19);
        $t_cpdf->SetTextColor(255, 255, 255);
        $t_cpdf->Cell(0, 10, 'LAYANAN PASTI', 0, 1, 'L', true);
        $t_cpdf->SetTextColor(0, 0, 0);

        // Spasi antara konten dan signature
        $t_cpdf->Ln(1);

        // Konten text (justify)
        $t_cpdf->SetFont('poppins-regular', '', 11);

        // LAYANAN PASTI
        $t_cpdf->Cell(100, 5, '1. Konsultasi Gratis', 0, 0);
        $t_cpdf->Cell(100, 5, '5. Gratis Handling Keberangkatan', 0, 1);
        $t_cpdf->Cell(100, 5, '2. Gratis Bantuan Pembuatan Paspor', 0, 0);
        $t_cpdf->Cell(100, 5, '6. Gratis Handling Kepulangan', 0, 1);
        $t_cpdf->Cell(100, 5, '3. Gratis Antar Dokumen & Perlengkapan', 0, 0);
        $t_cpdf->Cell(100, 5, '7. Jaminan Pasti Berangkat', 0, 1);
        $t_cpdf->Cell(100, 5, '4. Gratis Pendampingan Manasik', 0, 0);
        $t_cpdf->SetXY(110, $t_cpdf->GetY() + 0); // Pindah ke kolom kanan
        $t_cpdf->MultiCell(100, 4, '8. Garansi 100% Uang Kembali Apabila Travel Gagal Memberangkatkan', 0, 'L', false);

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
            $tbl .= '<td width="100" align="center">' . $rundown->hari . '</td>';
            $tbl .= '<td width="140" align="center">' . $rundown->tanggal . '</td>';
            $tbl .= '<td width="300">' . $rundown->kegiatan . '</td>';
            $tbl .= '</tr>';
        }

        // Akhir dari tabel
        $tbl .= <<<EOD
</tbody>
</table>
EOD;
        $t_cpdf->Sety(38);
        $t_cpdf->writeHTML($tbl, true, false, false, false, '');

        // Output PDF (tampilkan di browser)
        $t_cpdf->Output('Land_Arrangement', 'I'); // 'I' untuk menampilkan di browser
    }
}
