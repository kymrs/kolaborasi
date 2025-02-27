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
        $this->Image('assets/backend/img/bymoment.png', 5, 4, 37, 20);
        $this->SetX(117);
        $this->SetFont('Poppins-Regular', '', 9);
        $this->Cell(40, 16, 'cs@bymoment.id', 0, 0);
        $this->SetX(121);
        $this->Cell(40, 26, '0812-90700033', 0, 1);
        $style = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));

        $this->Line(4, 26, 145, 26, $style);
        $this->Ln(5);
    }
}

class Bmn_invoice extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_bmn_invoice');
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

        return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
    }

    public function index()
    {
        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        ($akses->view_level == 'N' ? redirect('auth') : '');
        $data['add'] = $akses->add_level;

        $data['title'] = "backend/bmn_invoice/bmn_invoice_list";
        $data['titleview'] = "Data Invoice";
        $this->load->view('backend/home', $data);
    }

    public function get_pdf()
    {
        $this->load->view('backend/prepayment_pu/prepayment_pdf');
    }

    function get_list()
    {
        $id_user = $this->session->userdata('id_user');

        // INISIAI VARIABLE YANG DIBUTUHKAN
        $name = $this->db->select('username')
            ->from('tbl_user')
            ->where('id_user', $id_user)
            ->get()
            ->row('username');
        $list = $this->M_bmn_invoice->get_datatables();
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
            $action_read = ($read == 'Y') ? '<a href="bmn_invoice/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>&nbsp;' : '';
            $action_edit = ($edit == 'Y') ? '<a href="bmn_invoice/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;' : '';
            $action_delete = ($delete == 'Y') ? '<a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;' : '';
            $action_print = ($print == 'Y') ? '<a class="btn btn-success btn-circle btn-sm" target="_blank" href="bmn_invoice/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>' : '';

            if ($name == 'eko') {
                $action = $action_read . $action_edit . $action_delete . $action_print;
            } elseif ($field->id_user == $id_user) {
                $action = $action_read . $action_edit . $action_delete . $action_print;
            } else {
                $action = $action_read . $action_print;
            }

            $status = $field->payment_status == 1 ? 'Lunas' : 'Belum Lunas';

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $action;

            $row[] = $this->tgl_indo(date("Y-m-j", strtotime($field->tgl_invoice)));
            $kode_invoice = substr($field->kode_invoice, 0, 5) . substr($field->kode_invoice, 7, 6);
            $row[] = strtoupper($kode_invoice);
            $row[] = $field->ctc2_nama;
            $row[] = $field->ctc2_alamat;
            $row[] = $status;
            $row[] = $this->tgl_indo(date("Y-m-j", strtotime($field->tgl_tempo)));

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_bmn_invoice->count_all(),
            "recordsFiltered" => $this->M_bmn_invoice->count_filtered(),
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
        $data['id_user'] = $this->session->userdata('id_user');
        $data['name'] = $this->db->select('username')
            ->from('tbl_user')
            ->where('id_user', $data['id_user'])
            ->get()
            ->row('username');
        $data['invoice'] = $this->M_bmn_invoice->getInvoiceData($id);
        $data['status'] = $this->M_bmn_invoice->get_by_id($id)->payment_status;
        $data['rekening'] = $this->db->get_where('bmn_rek_invoice', ['invoice_id' => $id])->result_array();
        $data['detail'] = $this->db->get_where('bmn_detail_invoice', ['invoice_id' => $id])->result_array();
        $data['title'] = 'backend/bmn_invoice/bmn_invoice_read';
        $data['title_view'] = 'Prepayment';
        $this->load->view('backend/home', $data);
    }

    // UNTUK MENAMPILKAN FORM ADD
    public function add_form()
    {
        $data['id'] = 0;
        $data['title'] = 'backend/bmn_invoice/bmn_invoice_form';
        $data['title_view'] = 'Invoice Form';
        $data['rek_options'] = $this->M_bmn_invoice->options()->result();
        // $data['notif'] = $this->M_notifikasi->pending_notification();
        $this->load->view('backend/home', $data);
    }

    // MEREGENERATE KODE INVOICE
    public function generate_kode()
    {
        $date = $this->input->post('date');

        $kode = $this->M_bmn_invoice->max_kode($date)->row();

        if (empty($kode->kode_invoice)) {
            $no_urut = 1;
        } else {
            $bln = substr($kode->kode_invoice, 5, 2);
            $no_urut = substr($kode->kode_invoice, 10) + 1;
        }
        $urutan = str_pad($no_urut, 4, "0", STR_PAD_LEFT);
        $year = substr($date, 8, 2);
        $month = substr($date, 3, 2);
        $data = 'INVBM' . $year . $month . $urutan;
        echo json_encode($data);
    }

    // UNTUK MENAMPILKAN FORM EDIT
    function edit_form($id)
    {
        $data['id'] = $id;
        $data['aksi'] = 'update';
        $data['title_view'] = "Edit Data Invoice";
        $data['title'] = 'backend/bmn_invoice/bmn_invoice_form';
        $data['rek_options'] = $this->M_bmn_invoice->options()->result();
        $this->load->view('backend/home', $data);
    }

    function edit_data($id)
    {
        $data['master'] = $this->M_bmn_invoice->get_by_id($id);
        $data['rek_invoice'] = $this->db->get_where('bmn_rek_invoice', ['invoice_id' => $id])->result_array();
        $data['detail_invoice'] = $this->db->get_where('bmn_detail_invoice', ['invoice_id' => $id])->result_array();
        echo json_encode($data);
    }

    function read_detail($id)
    {
        $data = $this->M_bmn_invoice->get_by_id_detail($id);
        echo json_encode($data);
    }

    // MENAMBAHKAN DATA
    public function add()
    {
        $id_user = $this->session->userdata('id_user');

        // INSERT KODE PREPAYMENT SAAT SUBMIT
        $date = $this->input->post('tgl_invoice');

        $kode = $this->M_bmn_invoice->max_kode($date)->row();

        if (empty($kode->kode_invoice)) {
            $no_urut = 1;
        } else {
            $bln = substr($kode->kode_invoice, 5, 2);
            $no_urut = substr($kode->kode_invoice, 10) + 1;
        }
        $urutan = str_pad($no_urut, 4, "0", STR_PAD_LEFT);
        $year = substr($date, 8, 2);
        $month = substr($date, 3, 2);

        $kode_invoice = 'INVBM' . $year . $month . $urutan;

        $data = array(
            'id_user' => $id_user,
            'tgl_invoice' => date('Y-m-d', strtotime($this->input->post('tgl_invoice'))),
            'kode_invoice' => $kode_invoice,
            'tgl_tempo' => date('Y-m-d', strtotime($this->input->post('tgl_tempo'))),
            'ctc_nama' => $this->input->post('ctc_nama'),
            'ctc_nomor' => $this->input->post('ctc_nomor'),
            'ctc2_nama' => $this->input->post('ctc2_nama'),
            'ctc2_nomor' => $this->input->post('ctc2_nomor'),
            'ctc2_alamat' => $this->input->post('ctc2_alamat'),
            'ctc2_email' => $this->input->post('ctc2_email'),
            'created_at' => date('Y-m-d H:i:s')
        );

        if (!empty($_POST['catatan_item'])) {
            $data['keterangan'] = $this->input->post('catatan_item');
        }
        if (!empty($_POST['diskon'])) {
            $data['diskon'] = preg_replace('/\D/', '', $this->input->post('diskon'));
        }

        $inserted = $this->M_bmn_invoice->save($data);

        // INISIASI VARIABEL INPUT Nomor Rekening Bank

        $nama_bank = $this->input->post('nama_bank[]');
        $no_rek = $this->input->post('no_rek[]');
        if (!empty($no_rek)) {
            //PERULANGAN UNTUK INSER QUERY Nomor Rekening Bank
            for ($i = 1; $i <= count($nama_bank); $i++) {
                $data2[] = array(
                    'invoice_id' => $inserted,
                    'nama_bank' => $nama_bank[$i],
                    'no_rek' => $no_rek[$i]
                );
            }
            $this->M_bmn_invoice->save_detail($data2);
        }

        if ($inserted) {
            // INISIASI VARIABEL INPUT DETAIL INVOICE
            $deskripsi = $this->input->post('deskripsi[]');
            $jumlah = preg_replace('/\D/', '', $this->input->post('jumlah[]'));
            $harga = preg_replace('/\D/', '', $this->input->post('harga[]'));
            $satuan = $this->input->post('satuan[]');
            $total = preg_replace('/\D/', '', $this->input->post('total[]'));
            //PERULANGAN UNTUK INSER QUERY DETAIL INVOICE
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
                $this->M_bmn_invoice->save_detail2($data3);
            }
        }

        echo json_encode(array("status" => TRUE));
    }

    // UPDATE DATA
    public function update()
    {
        // INSERT KODE PREPAYMENT SAAT SUBMIT
        $date = $this->input->post('tgl_invoice');

        $kode = $this->M_bmn_invoice->max_kode($date)->row();

        if (empty($kode->kode_invoice)) {
            $no_urut = 1;
        } else {
            $bln = substr($kode->kode_invoice, 5, 2);
            $no_urut = substr($kode->kode_invoice, 10) + 1;
        }
        $urutan = str_pad($no_urut, 4, "0", STR_PAD_LEFT);
        $year = substr($date, 8, 2);
        $month = substr($date, 3, 2);

        $kode_invoice = 'INVBM' . $year . $month . $urutan;

        $data = array(
            'tgl_invoice' => date('Y-m-d', strtotime($this->input->post('tgl_invoice'))),
            'kode_invoice' => $kode_invoice,
            'tgl_tempo' => date('Y-m-d', strtotime($this->input->post('tgl_tempo'))),
            'ctc_nama' => $this->input->post('ctc_nama'),
            'ctc_nomor' => $this->input->post('ctc_nomor'),
            'ctc2_nama' => $this->input->post('ctc2_nama'),
            'ctc2_nomor' => $this->input->post('ctc2_nomor'),
            'ctc2_alamat' => $this->input->post('ctc2_alamat'),
            'ctc2_email' => $this->input->post('ctc2_email'),
            'created_at' => date('Y-m-d H:i:s')
        );

        if (!empty($_POST['catatan_item'])) {
            $data['keterangan'] = $this->input->post('catatan_item');
        }

        if (!empty($_POST['diskon'])) {
            $data['diskon'] = preg_replace('/\D/', '', $this->input->post('diskon'));
        } else {
            $data['diskon'] = 0;
        }

        //UPDATE DETAIL PREPAYMENT
        $id_detail = $this->input->post('hidden_id[]');
        // $invoice_id = $this->input->post('hidden_invoiceId[]');
        $deskripsi = $this->input->post('deskripsi[]');
        $jumlah = preg_replace('/\D/', '', $this->input->post('jumlah[]'));
        $satuan = $this->input->post('satuan[]');
        $harga = preg_replace('/\D/', '', $this->input->post('harga[]'));
        $total = preg_replace('/\D/', '', $this->input->post('total[]'));
        if ($this->db->update('bmn_invoice', $data, ['id' => $this->input->post('id')])) {
            // UNTUK MENGHAPUS ROW YANG TELAH DIDELETE
            $deletedRows = json_decode($this->input->post('deleted_rows'), true);
            if (!empty($deletedRows)) {
                foreach ($deletedRows as $delRows) {
                    // Hapus row dari database berdasarkan ID
                    $this->db->where('id', $delRows);
                    $this->db->delete('bmn_detail_invoice');
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
                    'satuan' => $satuan[$i],
                    'harga' => $harga[$i],
                    'total' => $total[$i]
                );
                // Menggunakan db->replace untuk memasukkan atau menggantikan data
                $this->db->replace('bmn_detail_invoice', $data2[$i - 1]);
            }

            // UNTUK MENGHAPUS REKENING
            $deletedRekRows = json_decode($this->input->post('deletedRekRows'), true);
            if (!empty($deletedRekRows)) {
                foreach ($deletedRekRows as $delRekRow) {
                    // Hapus row dari database berdasarkan ID
                    $this->db->where('id', $delRekRow);
                    $this->db->delete('bmn_rek_invoice');
                }
            }

            // MELAKUKAN REPLACE DATA REKENING
            $id_rek = $this->input->post('hidden_rekId[]');
            $nama_bank = $this->input->post('nama_bank[]');
            $no_rek = $this->input->post('no_rek[]');
            if (!empty($no_rek)) {
                for ($i = 1; $i <= count($_POST['nama_bank']); $i++) {
                    // Set id menjadi NULL jika id_rek tidak ada atau kosong
                    $id_rekening = !empty($id_rek[$i]) ? $id_rek[$i] : NULL;
                    $data3[] = array(
                        'id' => $id_rekening,
                        'invoice_id' => $this->input->post('id'),
                        'nama_bank' => $nama_bank[$i],
                        'no_rek' => $no_rek[$i]
                    );
                    // Menggunakan db->replace untuk memasukkan atau menggantikan data
                    $this->db->replace('bmn_rek_invoice', $data3[$i - 1]);
                }
            }
        }
        echo json_encode(array("status" => TRUE));
    }

    // MENGHAPUS DATA
    function delete($id)
    {
        $this->M_bmn_invoice->delete($id);
        echo json_encode(array("status" => TRUE));
    }

    public function send_email()
    {
        $this->load->library('email');
        $this->load->library('tcpdf'); // Pastikan TCPDF sudah di-load
        $email = $this->input->post('email'); // Ambil email tujuan
        $id = $this->input->post('id');

        if ($email) {
            // Konfigurasi email
            $config = [
                'protocol'  => 'smtp',
                'smtp_host' => 'ssl://smtp.googlemail.com',
                'smtp_user' => 'audricafabiano@gmail.com',
                'smtp_pass' => 'rxhr ylvy dgwg lwgl',
                'smtp_port' => 465,
                'mailtype'  => 'html',
                'charset'   => 'utf-8',
                'newline'   => "\r\n"
            ];

            $this->email->initialize($config);

            $data['id'] = $id;
            $data['sub'] = 'bmn';
            $data['output'] = 'save';
            $this->load->library('tcpdf_invoice'); // Sesuaikan dengan nama file library
            $pdf_content = $this->tcpdf_invoice->generateInvoice($data); // Gunakan nama yang benar

            // Simpan PDF sementara
            $pdf_path = FCPATH . 'assets/backend/uploads/Invoice ByMoment.pdf'; // Simpan di folder uploads

            // **2. Kirim email dengan lampiran PDF**
            $this->email->from('audricafabiano@gmail.com', 'Audrica Ewaldo');
            $this->email->to($email);
            $this->email->subject('Invoice by.moment');
            $this->email->message('Terlampir invoice Anda dalam format PDF.');

            // **3. Attach PDF**
            $this->email->attach($pdf_path);

            if ($this->email->send()) {
                echo json_encode(array("status" => TRUE, "message" => "Email berhasil dikirim dengan PDF."));
            } else {
                echo json_encode(array("status" => FALSE, "message" => $this->email->print_debugger()));
            }

            // **4. Hapus file setelah dikirim agar tidak menumpuk**
            unlink($pdf_path);
        } else {
            echo json_encode(array("status" => FALSE, "message" => "Email tidak ditemukan."));
        }
    }


    // PRINTOUT TCPDF
    public function generate_pdf($id)
    {
        $data['id'] = $id;
        $data['sub'] = 'bmn';
        $data['output'] = 'view';
        $data['status'] = $this->M_bmn_invoice->get_by_id($id)->payment_status;
        $this->load->library('tcpdf_invoice'); // Sesuaikan dengan nama file library
        $this->tcpdf_invoice->generateInvoice($data); // Gunakan nama yang benar
    }

    function payment()
    {
        $this->db->where('id', $this->input->post('id'));
        $this->db->update('bmn_invoice', ['payment_status' => $this->input->post('payment_status')]);

        echo json_encode(array("status" => TRUE));
    }
}
