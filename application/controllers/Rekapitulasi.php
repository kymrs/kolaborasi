<?php
defined('BASEPATH') or exit('No direct script access allowed');
setlocale(LC_ALL, 'id_ID');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Rekapitulasi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_rekapitulasi');
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
        $data['total'] = $this->M_rekapitulasi->get_total_pengeluaran();

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
            // $tanggal = !empty($field->tgl_pengajuan) ? $field->tgl_pengajuan : $field->tgl_prepayment;
            $pengeluaran = !empty($field->total_jumlah_detail) ? number_format($field->total_jumlah_detail, 0, ',', '.') : number_format($field->total_nominal, 0, ',', '.');

            // Inkrement nomor urut
            $no++;
            $row = array();
            $row[] = $no; // Nomor urut
            $row[] = $field->source_table;
            $row[] = $kode_prepayment; // Kode prepayment, atau tanda "-"
            $row[] = $kode_reimbust; // Kode reimburse
            $row[] = $field->user_name; // Nama pengguna
            $row[] = $field->tujuan; // Tujuan dari pengajuan
            $row[] = $field->tgl_pengajuan; // Format tanggal
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

    function get_total()
    {
        $output = $this->M_rekapitulasi->get_total_pengeluaran();

        //output dalam format JSON
        echo json_encode($output);
    }

    public function export_excel()
    {
        // Ambil parameter tanggal dari URL
        $tgl_awal = $this->input->get('tgl_awal');
        $tgl_akhir = $this->input->get('tgl_akhir');

        // Ambil data dari model
        $prepayment = $this->M_rekapitulasi->get_data_prepayment($tgl_awal, $tgl_akhir);
        $pelaporan = $this->M_rekapitulasi->get_data_pelaporan($tgl_awal, $tgl_akhir);
        $reimbust = $this->M_rekapitulasi->get_data_reimbust($tgl_awal, $tgl_akhir);

        // Inisialisasi Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set judul kolom
        $sheet->setCellValue('A1', 'Core');
        $sheet->setCellValue('B1', 'Kode Transaksi');
        $sheet->setCellValue('C1', 'Jenis Transaksi');
        $sheet->setCellValue('D1', 'Tanggal Transaksi');
        $sheet->setCellValue('E1', 'Nominal');

        // Atur Auto Size untuk setiap kolom
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);

        // Tambahkan filter ke header (baris pertama)
        $sheet->setAutoFilter('A1:E1');

        // Isi data dari database mulai dari baris ke-2
        $row = 2;
        foreach ($prepayment as $data) {
            $sheet->setCellValue('A' . $row, $data->source_table);
            $sheet->setCellValue('B' . $row, $data->kode_prepayment);
            $sheet->setCellValue('C' . $row, 'Prepayment');
            $sheet->setCellValue('D' . $row, $this->tgl_indo(date("Y-m-j", strtotime($data->tgl_pengajuan))));
            $sheet->setCellValue('E' . $row, $data->total_nominal);
            $row++;
        }

        foreach ($pelaporan as $data) {
            $sheet->setCellValue('A' . $row, $data->source_table);
            $sheet->setCellValue('B' . $row, strtoupper($data->kode_reimbust));
            $sheet->setCellValue('C' . $row, 'Pelaporan');
            $sheet->setCellValue('D' . $row, $this->tgl_indo(date("Y-m-j", strtotime($data->tgl_pengajuan))));
            $sheet->setCellValue('E' . $row, $data->total_jumlah_detail);
            $row++;
        }

        foreach ($reimbust as $data) {
            $sheet->setCellValue('A' . $row, $data->source_table);
            $sheet->setCellValue('B' . $row, strtoupper($data->kode_reimbust));
            $sheet->setCellValue('C' . $row, 'Reimbust');
            $sheet->setCellValue('D' . $row, $this->tgl_indo(date("Y-m-j", strtotime($data->tgl_pengajuan))));
            $sheet->setCellValue('E' . $row, $data->total_jumlah_detail);
            $row++;
        }

        // Terapkan format angka untuk kolom Nominal
        $sheet->getStyle('D2:D' . ($row - 1))
            ->getNumberFormat()
            ->setFormatCode('#,##0');

        // Buat writer untuk export ke Excel
        $writer = new Xlsx($spreadsheet);

        // Set header untuk download file Excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Data Rekapitulasi All .xlsx"');
        header('Cache-Control: max-age=0');

        // Simpan file ke output
        $writer->save('php://output');;
        exit;
    }
}
