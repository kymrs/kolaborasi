<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Memuat file TCPDF dari folder third_party
require_once(APPPATH . 'third_party/TCPDF-main/tcpdf.php');

class T_cpdf2 extends TCPDf
{
    // Page header
    function Header()
    {
        $this->Image('assets/backend/img/sobatwisata.png', 170, 5, 37, 20);
        $this->Image('assets/backend/img/ellipse-invoice-swi.png', 146, 0, 85, 60);

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
        $this->Image(base_url('assets/backend/img/footer-invoice-swi.png'), 0, 260, 210, 40);
    }
}

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

    function tgl_indo($tanggal)
    {
        $bulan = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        // Pecah berdasarkan tanda "-"
        $pecahkan = explode('-', $tanggal);
        $jumlahPecahan = count($pecahkan);

        // Jika format YYYY-MM-DD (3 bagian)
        if ($jumlahPecahan === 3) {
            return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
        }
        // Jika format MM-DD (2 bagian)
        elseif ($jumlahPecahan === 2) {
            return $pecahkan[1] . ' ' . $bulan[(int)$pecahkan[0]];
        }
        // Jika hanya MM (1 bagian)
        elseif ($jumlahPecahan === 1) {
            return $bulan[(int)$pecahkan[0]];
        }

        return "Format tidak valid";
    }


    // PRINTOUT FPDF
    public function generate_pdf($id)
    {
        // INISIAI VARIABLE
        $penawaran = $this->M_swi_penawaran->get_by_id($id);
        $penawaran_details = $this->M_swi_penawaran->get_by_id_detail($id);

        // Initialize the TCPDF object
        $t_cpdf2 = new t_cpdf2('P', 'mm', 'A4', true, 'UTF-8', false);

        // Set document properties
        $t_cpdf2->SetCreator(PDF_CREATOR);
        $t_cpdf2->SetAuthor('Author Name');
        $t_cpdf2->SetTitle('Invoice - ' . 'invoice');

        $t_cpdf2->SetMargins(15, 50, 15); // Margin kiri, atas (untuk header), kanan
        // $t_cpdf2->SetHeaderMargin(30);    // Jarak antara header dan konten
        $t_cpdf2->SetAutoPageBreak(true, 15); // Penanganan otomatis margin bawah
        $t_cpdf2->setPrintHeader(true);

        // Tambahkan halaman baru
        $t_cpdf2->AddPage();

        $t_cpdf2->SetFont('Poppins-Regular', '');

        // No & Tanggal
        $t_cpdf2->SetXY(15, 10);
        $t_cpdf2->Cell(20, 5, 'No', 0, 0, 'L');
        $t_cpdf2->Cell(2, 5, ':', 0, 0, 'L');
        $t_cpdf2->Cell(0, 5, $penawaran->kode, 0, 1, 'L');
        $t_cpdf2->SetXY(15, 15);
        $t_cpdf2->Cell(20, 5, 'Tgl', 0, 0, 'L');
        $t_cpdf2->Cell(2, 5, ':', 0, 0, 'L');
        $t_cpdf2->Cell(0, 5, $this->tgl_indo(date("Y-m-j", strtotime($penawaran->created_at))), 0, 1, 'L');
        $t_cpdf2->SetXY(15, 20);
        $t_cpdf2->Cell(20, 5, 'Perihal', 0, 0, 'L');
        $t_cpdf2->Cell(2, 5, ':', 0, 0, 'L');
        $t_cpdf2->Cell(0, 5, 'Penawaran Harga Sewa Armada', 0, 1, 'L');

        // Kepada Yth
        $t_cpdf2->SetXY(15, 35);
        $t_cpdf2->Cell(0, 5, 'Kepada Yth,', 0, 1, 'L');
        $t_cpdf2->SetXY(15, 45);
        $t_cpdf2->Cell(0, 5, 'Sdr. ' . ucfirst($penawaran->name), 0, 1, 'L');
        $t_cpdf2->SetXY(15, 50);
        $t_cpdf2->Cell(0, 5, 'Di Tempat', 0, 1, 'L');

        // Isi Surat
        $t_cpdf2->SetXY(15, 63);
        $t_cpdf2->MultiCell(0, 5, "Dengan hormat,\n\nBersama surat ini kami sampaikan penawaran penyewaan Armada " . $penawaran->kendaraan . " untuk kebutuhan perjalanan dari " . $penawaran->asal . " ke " . $penawaran->tujuan . " dengan rincian sebagai berikut:", 0, 'L');

        // Tabel

        $t_cpdf2->Image('assets/backend/img/tableheader-penawaran-swi.png', 5.5, 85, 200, 12);

        $tbl = <<<EOD
        <table border="0" cellpadding="3">
            <thead>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
    EOD;
        foreach ($penawaran_details as $detail) {
            $bulan_awal = date("m", strtotime($detail->tgl_keberangkatan));
            $bulan_kedua = date("m", strtotime($detail->tgl_kepulangan));
            if ($bulan_awal == $bulan_kedua) {
                $date_use = date("d", strtotime($detail->tgl_keberangkatan)) . " - " . $this->tgl_indo(date("Y-m-j", strtotime($detail->tgl_kepulangan)));
            } else {
                $date_use = $this->tgl_indo(date("m-j", strtotime($detail->tgl_keberangkatan))) . " - " . $this->tgl_indo(date("Y-m-j", strtotime($detail->tgl_kepulangan)));
            }
            $tbl .= '<tr>';
            $tbl .= '<td width="20%">' . $date_use . '</td>';
            $tbl .= '<td width="20%" style="text-align: center">' . $detail->jenis . '</td>';
            $tbl .= '<td width="15%" style="text-align: center">' . $detail->jumlah . '</td>';
            $tbl .= '<td width="25%" style="text-align: center">' . 'Rp. ' . number_format($detail->harga, 0, ',', '.') . '</td>';
            $tbl .= '<td width="20%" style="text-align: center">' . $detail->keterangan . '</td>';
            $tbl .= '</tr>';
        }
        $tbl .= <<<EOD
    </tbody>
</table>
EOD;
        $t_cpdf2->SetXY(15, 90);
        $t_cpdf2->writeHTML($tbl, true, false, false, false, '');

        // Notes
        $t_cpdf2->SetXY(15, 115);
        $note = "Note:\n" .
            "- DP Minimal 30%\n" .
            str_repeat(" ", 0) . "- Pelunasan H-5 sebelum hari keberangkatan\n" .
            str_repeat(" ", 0) . "- Pembayaran melalui transfer ke BCA PT Quick Project Indonesia\n" .
            str_repeat(" ", 3) . "dengan nomor rekening 713 172 8003\n" .
            str_repeat(" ", 0) . "- Harga dan ketersediaan unit tidak mengikat jika tidak\n" .
            str_repeat(" ", 3) . "melakukan pembayaran DP\n\n" .
            "Demikian Surat penawaran harga ini kami buat, kami tunggu kabar baik dari Bapak/Ibu, atas perhatiannya kami ucapkan terima kasih.";

        $t_cpdf2->MultiCell(0, 5, $note, 0, 'L');

        $t_cpdf2->Cell(0, 15, 'Hormat kami,', 0, 1, 'R');
        $t_cpdf2->Cell(0, 40, 'Sobat Wisata', 0, 1, 'R');

        //PAGE SELANJUTNYA
        $t_cpdf2->setPrintHeader(false);
        $t_cpdf2->AddPage();
        $t_cpdf2->setPrintFooter(false);

        $pageWidth = $t_cpdf2->getPageWidth();
        $pageHeight = $t_cpdf2->getPageHeight();

        // Set background image (ganti dengan path gambar kamu)
        $t_cpdf2->SetAutoPageBreak(false, 0);
        $t_cpdf2->Image('assets/backend/img/ketentuan-sewa-swi.png', 0, 0, $pageWidth + 1, $pageHeight, '', '', '', false, 300, '', false, false, 1);


        // Output file
        $t_cpdf2->Output('Penawaran sobatwisata-' . 'kode' . '.pdf', 'I');
    }
}
