<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Notifikasiadmin extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_notifikasiAdmin');
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
        $data['alias'] = $this->session->userdata('username');
        $data['title'] = "backend/notifikasiadmin";
        $data['titleview'] = "Notifikasi";
        $this->load->view('backend/home', $data);
    }

    public function get_list()
    {
        $list = $this->M_notifikasiAdmin->get_datatables();
        $data = [];
        $no = $_POST['start'];

        foreach ($list as $item) {
            $no++;
            $row = [];
            $row['no'] = $no;
            $row['menu'] = $item['menu'];
            $row['submenu'] = $item['submenu'];
            $row['jumlah'] = $item['jumlah'];
            $row['detail'] = '<button type="button" class="btn btn-sm btn-primary detail-btn"
                                data-nama_tbl="' . $item['nama_tbl'] . '"
                                data-toggle="modal"
                                data-target="#detailModal">Detail</button>';
            $data[] = $row;
        }

        $output = [
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_notifikasiAdmin->count_all(),
            "recordsFiltered" => $this->M_notifikasiAdmin->count_filtered(),
            "data" => $data,
        ];

        echo json_encode($output);
    }

    public function get_link($link)
    {
        // 1. Pastikan tabel benar-benar ada (opsional)
        if (!$this->db->table_exists($link)) {
            show_404();
            return;
        }

        // 2. Cek kolom-kolom yang tersedia di tabel
        $availableFields = $this->db->list_fields($link);

        // 3. Daftar kolom yang diinginkan
        $possibleFields = ['kode_deklarasi', 'kode_prepayment', 'kode_reimbust', 'app_name', 'app_status', 'app2_name', 'app2_status', 'app4_name', 'app4_status'];

        // 4. Ambil kolom yang tersedia di tabel
        $selectedFields = array_intersect($possibleFields, $availableFields);

        if (empty($selectedFields)) {
            echo json_encode(['data' => []]);
            return;
        }

        // 5. Ambil data dari tabel hanya dengan kolom yang tersedia
        $this->db->select(implode(',', $selectedFields));
        $this->db->from($link);
        $this->db->where('status', 'on-process');
        $result = $this->db->get()->result();

        // 6. Kembalikan hasil dalam format JSON
        echo json_encode(['data' => $result]);
    }
}
