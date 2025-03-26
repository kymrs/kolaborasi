<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Qbg_transaksi extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_qbg_transaksi');
        $this->M_login->getsecurity();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $data['title'] = "backend/qbg_transaksi/qbg_transaksi_list";
        $data['titleview'] = "Data Transaksi";
        $data['produk'] = $this->db->select('kode_produk, nama_produk, berat, satuan')->get('qbg_produk')->result_array();
        $this->load->view('backend/home', $data);
    }

    function get_list()
    {
        $kode_produk = $this->input->post('kode_produk');
        $jenis_transaksi = $this->input->post('jenis_transaksi');
        $keterangan = $this->input->post('keterangan');
        $tgl_awal = $this->input->post('tgl_awal') ? date('Y-m-d', strtotime($this->input->post('tgl_awal'))) : null;
        $tgl_akhir = $this->input->post('tgl_akhir') ? date('Y-m-d', strtotime($this->input->post('tgl_akhir'))) : null;

        $list = $this->M_qbg_transaksi->get_datatables($kode_produk, $jenis_transaksi, $keterangan, $tgl_awal, $tgl_akhir);
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $field) {

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->kode_produk;
            $row[] = $field->nama_produk;
            $row[] = $field->berat;
            $row[] = $field->jenis_transaksi;
            $row[] = $field->jumlah;
            $row[] = $field->keterangan;
            $row[] = date("d-m-Y | H:i:s", strtotime($field->created_at));
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_qbg_transaksi->count_all(),
            "recordsFiltered" => $this->M_qbg_transaksi->count_filtered($kode_produk, $jenis_transaksi, $keterangan, $tgl_awal, $tgl_akhir),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }
}
