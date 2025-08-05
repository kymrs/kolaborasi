<?php
defined('BASEPATH') or exit('No direct script access allowed');
setlocale(LC_ALL, 'id_ID');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Swi_rekapitulasi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_swi_rekapitulasi');
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
        $data['notif'] = $this->M_notifikasi->pending_notification();
        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        ($akses->view_level == 'N' ? redirect('auth') : '');
        $data['add'] = $akses->add_level;
        $data['total'] = $this->M_swi_rekapitulasi->get_total_pengeluaran();

        $data['title'] = "backend/swi_rekapitulasi";
        $data['titleview'] = "Data Rekapitulasi Sobat Wisata";
        $this->load->view('backend/home', $data);
    }

    public function get_list_pelaporan()
    {
        $list = $this->M_swi_rekapitulasi->get_datatables_pelaporan();
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->kode_prepayment ? $field->kode_prepayment : '-';
            $row[] = !empty($field->kode_reimbust) ? ucfirst($field->kode_reimbust) : '-';
            $row[] = $this->tgl_indo($field->tgl_pengajuan);
            $row[] = $field->name;
            $row[] = $field->tujuan;
            $row[] = 'Rp. ' . number_format($field->total_nominal, 0, ',', '.');
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_swi_rekapitulasi->count_all_pelaporan(),
            "recordsFiltered" => $this->M_swi_rekapitulasi->count_filtered_pelaporan(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function get_list_reimbust()
    {
        $list = $this->M_swi_rekapitulasi->get_datatables_reimbust();
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '-';
            $row[] = !empty($field->kode_reimbust) ? strtoupper($field->kode_reimbust) : '-';
            $row[] = $this->tgl_indo($field->tgl_pengajuan);
            $row[] = $field->name;
            $row[] = $field->tujuan;
            $row[] = 'Rp. ' . number_format($field->total_jumlah_detail, 0, ',', '.');
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_swi_rekapitulasi->count_all_reimbust(),
            "recordsFiltered" => $this->M_swi_rekapitulasi->count_filtered_reimbust(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function get_list_invoice()
    {
        $list = $this->M_swi_rekapitulasi->get_datatables_invoice();
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = !empty($field->kode_invoice) ? $field->kode_invoice : '-';
            $row[] = $this->tgl_indo($field->tgl_invoice);
            $row[] = $field->ctc_to;
            $row[] = 'Rp. ' . number_format($field->total, 0, ',', '.');
            $row[] = $field->payment_status == 1 ? 'Lunas' : 'Belum Lunas';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_swi_rekapitulasi->count_all_invoice(),
            "recordsFiltered" => $this->M_swi_rekapitulasi->count_filtered_invoice(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    function get_total()
    {
        $output = array(
            'pengeluaran' => $this->M_swi_rekapitulasi->get_total_pengeluaran(),
            'pemasukan' => $this->M_swi_rekapitulasi->get_total_pemasukan()
        );

        //output dalam format JSON
        echo json_encode($output);
    }

    public function export_excel()
    {
        // Ambil parameter tanggal dari URL
        $tgl_awal = $this->input->get('tgl_awal');
        $tgl_akhir = $this->input->get('tgl_akhir');

        // Ambil data dari model
        $prepayment = $this->M_swi_rekapitulasi->get_data_prepayment($tgl_awal, $tgl_akhir);
        $reimbust = $this->M_swi_rekapitulasi->get_data_reimbust($tgl_awal, $tgl_akhir);
        $invoice = $this->M_swi_rekapitulasi->get_data_invoice($tgl_awal, $tgl_akhir);


        // Inisialisasi Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set judul kolom
        $sheet->setCellValue('A1', 'Kode Transaksi');
        $sheet->setCellValue('B1', 'Jenis Transaksi');
        $sheet->setCellValue('C1', 'Tanggal Transaksi');
        $sheet->setCellValue('D1', 'Nominal');

        // Atur Auto Size untuk setiap kolom
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);

        // Tambahkan filter ke header (baris pertama)
        $sheet->setAutoFilter('A1:D1');

        // Isi data dari database mulai dari baris ke-2
        $row = 2;
        foreach ($prepayment as $data) {
            $sheet->setCellValue('A' . $row, $data->kode_prepayment);
            $sheet->setCellValue('B' . $row, 'Prepayment');
            $sheet->setCellValue('C' . $row, $this->tgl_indo(date("Y-m-j", strtotime($data->tgl_prepayment))));
            $sheet->setCellValue('D' . $row, $data->total_nominal);
            $row++;
        }

        foreach ($reimbust as $data) {
            $sheet->setCellValue('A' . $row, strtoupper($data->kode_reimbust));
            $sheet->setCellValue('B' . $row, $data->sifat_pelaporan);
            $sheet->setCellValue('C' . $row, $this->tgl_indo(date("Y-m-j", strtotime($data->tgl_pengajuan))));
            $sheet->setCellValue('D' . $row, $data->total_nominal);
            $row++;
        }

        foreach ($invoice as $data) {
            $sheet->setCellValue('A' . $row, strtoupper($data->kode_invoice));
            $sheet->setCellValue('B' . $row, 'Invoice');
            $sheet->setCellValue('C' . $row, $this->tgl_indo(date("Y-m-j", strtotime($data->tgl_invoice))));
            $sheet->setCellValue('D' . $row, $data->total);
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
        header('Content-Disposition: attachment;filename="Data Rekapitulasi Sobat Wisata.xlsx"');
        header('Cache-Control: max-age=0');

        // Simpan file ke output
        $writer->save('php://output');;
        exit;
    }
}
