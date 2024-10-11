<?php
defined('BASEPATH') or exit('No direct script access allowed');
setlocale(LC_ALL, 'id_ID');

class Rekapitulasi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_rekapitulasi');
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

        $data['title'] = "backend/rekapitulasi";
        $data['titleview'] = "Data Rekapitulasi";
        $this->load->view('backend/home', $data);
    }

    function get_list()
    {
        // INISIAI VARIABLE YANG DIBUTUHKAN
        $list = $this->M_rekapitulasi->get_datatables();
        $data = array();
        $no = $_POST['start'];

        //LOOPING DATATABLES
        foreach ($list as $field) {
            // Cek apakah kode tersedia, jika tidak berikan tanda "-"
            $kode_prepayment = !empty($field->kode_prepayment) ? $field->kode_prepayment : '-';
            $kode_reimbust = !empty($field->kode_reimbust) ? strtoupper($field->kode_reimbust) : '-';
            $tanggal = !empty($field->tgl_pengajuan) ? $this->tgl_indo(date('Y-m-j', strtotime($field->tgl_pengajuan))) : $this->tgl_indo(date('Y-m-j', strtotime($field->tgl_prepayment)));
            $pengeluaran = !empty($field->total_jumlah_detail) ? number_format($field->total_jumlah_detail, 0, ',', '.') : number_format($field->total_nominal, 0, ',', '.');

            // Inkrement nomor urut
            $no++;
            $row = array();
            $row[] = $no; // Nomor urut
            $row[] = $tanggal; // Format tanggal
            $row[] = $field->name; // Nama pengguna
            $row[] = $field->tujuan; // Tujuan dari pengajuan
            $row[] = $kode_prepayment; // Kode prepayment, atau tanda "-"
            $row[] = $kode_reimbust; // Kode reimburse
            $row[] = 'Rp. ' . $pengeluaran; // Format nominal

            // Tambahkan row ke array data
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_rekapitulasi->count_all(),
            "recordsFiltered" => $this->M_rekapitulasi->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }
}
