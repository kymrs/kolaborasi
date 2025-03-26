<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Memuat file TCPDF dari folder third_party
require_once(APPPATH . 'third_party/TCPDF-main/tcpdf.php');

class T_cpdf2 extends TCPDf
{
    // Page header
    function Header()
    {
        // Ambil ukuran halaman
        $pageWidth = $this->getPageWidth();
        $pageHeight = $this->getPageHeight();

        // Set background image (ganti dengan path gambar kamu)
        $this->Image('assets/backend/img/background-invoice-swi.png', 0, 0, $pageWidth, $pageHeight, '', '', '', false, 300, '', false, false, 0);
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

    // PRINTOUT FPDF
    public function generate_pdf($id)
    {
        // INISIAI VARIABLE
        // $penawaran = $this->M_swi_invoice->get_by_id($id);
        // $penawaran_details = $this->M_swi_invoice->get_detail($id);

        // Initialize the TCPDF object
        $t_cpdf2 = new t_cpdf2('P', 'mm', 'A4', true, 'UTF-8', false);

        // Set document properties
        $t_cpdf2->SetCreator(PDF_CREATOR);
        $t_cpdf2->SetAuthor('Author Name');
        $t_cpdf2->SetTitle('Invoice - ' . 'invoice');

        // Set margin agar gambar memenuhi halaman
        $t_cpdf2->SetMargins(0, 0, 0); // Margin ke 0
        $t_cpdf2->SetAutoPageBreak(true, 0);

        // Tambahkan halaman baru
        $t_cpdf2->AddPage();

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
        $t_cpdf2->Cell(40, 5, 'kode invoice', 0, 1, 'L');

        // Kolom kedua (Tgl)
        $t_cpdf2->SetX(160);
        $t_cpdf2->Cell(9, 5, 'Tgl :', 0, 0, 'L');
        $t_cpdf2->Cell(40, 5, 'tgl_invoice', 0, 1, 'L');

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
        $t_cpdf2->Cell(0, 7, 'invoice_to', 0, 1, 'L');

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
        $t_cpdf2->MultiCell(65, 7, 'alamat_invoice', 0, 'L');

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
        // foreach ($invoice_details as $data) {
        //     $html .= '<tr style="text-align: center">
        //         <td style="width: 35%">' . $data->item . '</td>
        //         <td style="width: 8%">' . $data->qty . '</td>
        //         <td style="width: 20.5%">' . $data->day . '</td>
        //         <td style="width: 18%">Rp. ' . number_format($data->price, 0, ',', '.') . '</td>
        //         <td>Rp. ' . number_format($data->total, 0, ',', '.') . '</td>
        //       </tr>';
        // }

        // $html .= '</tbody></table>';

        // Tampilkan di PDF
        // $t_cpdf2->writeHTML($html, true, false, true, false, '');

        $t_cpdf2->SetY(180);
        $t_cpdf2->SetX(130);
        $t_cpdf2->SetFont('poppins-regular', 'B', 10);
        $t_cpdf2->Cell(0, 7, 'TOTAL', 0, 1, 'L');
        $t_cpdf2->SetFont('poppins-regular', '', 10);
        $t_cpdf2->SetY(180);
        $t_cpdf2->SetX(164);
        $t_cpdf2->Cell(0, 7, 'Rp. ' . 'total', 0, 1, 'L');

        $t_cpdf2->SetY(189);
        $t_cpdf2->SetX(130);
        $t_cpdf2->SetFont('poppins-regular', 'B', 10);
        $t_cpdf2->Cell(0, 7, 'TAX', 0, 1, 'L');
        $t_cpdf2->SetFont('poppins-regular', '', 10);
        $t_cpdf2->SetY(189);
        $t_cpdf2->SetX(164);
        $t_cpdf2->Cell(0, 7, 'tax', 0, 1, 'L');

        $grand_total = 'total';

        // $t_cpdf2->SetY(198);
        // $t_cpdf2->SetX(130);
        // $t_cpdf2->SetFont('poppins-regular', 'B', 10);
        // $t_cpdf2->Cell(0, 7, 'GRAND TOTAL', 0, 1, 'L');
        // $t_cpdf2->SetFont('poppins-regular', '', 10);
        // $t_cpdf2->SetY(198);
        // $t_cpdf2->SetX(164);
        // $t_cpdf2->Cell(0, 7, 'Rp. ' . number_format($grand_total, 0, ',', '.'), 0, 1, 'L');

        // $t_cpdf2->SetY(238);
        // $t_cpdf2->SetX(143);
        // $t_cpdf2->SetFont('poppins-regular', 'B', 10);
        // $t_cpdf2->Cell(0, 7, 'Rahmat Kurniawan', 0, 1, 'L');
        // $t_cpdf2->SetY(245);
        // $t_cpdf2->SetX(151);
        // $t_cpdf2->Cell(0, 7, 'Head Unit', 0, 1, 'L');

        $t_cpdf2->SetY(180);
        $t_cpdf2->SetX(20);
        $t_cpdf2->SetFont('poppins-regular', 'B', 10);
        $t_cpdf2->Cell(0, 7, 'BANK DETAILS', 0, 1, 'L');

        $t_cpdf2->SetY(188);
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
        // foreach ($invoice_rek as $data) {
        //     $html .= '<tr>
        //                 <td style="width: 35%">' . $data->nama_bank . '</td>
        //             </tr>';
        //     $html .= '<tr>
        //                 <td style="width: 35%">' . $data->no_rek . '</td>
        //             </tr>';
        //     $html .= '<tr>
        //                 <td style="width: 35%">' . $data->nama_rek . '</td>
        //             </tr>';
        // }

        $html .= '</tbody></table>';

        // Tampilkan di PDF
        $t_cpdf2->writeHTML($html, true, false, true, false, '');

        // Output file
        $t_cpdf2->Output('Penawaran sobatwisata-' . 'kode' . '.pdf', 'I');
    }
}
