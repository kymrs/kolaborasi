<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mac_mutasi_stok extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_mac_mutasi_stok');
        $this->M_login->getsecurity();
        date_default_timezone_set('Asia/Jakarta');
    }

    // ================================================================
    // INDEX
    // ================================================================

    public function index()
    {
        $data['title']       = 'backend/mac_mutasi_stok/mac_mutasi_stok';
        $data['titleview']   = 'Mutasi Stok';
        $data['is_nasional'] = $this->session->userdata('is_nasional') ? true : false;
        $data['cabang_id']   = intval($this->session->userdata('cabang_id'));

        if ($data['is_nasional']) {
            $data['list_cabang'] = $this->db
                ->where('status', 'aktif')
                ->order_by('nama_cabang', 'ASC')
                ->get('mac_cabang')->result();
        } else {
            $data['list_cabang'] = [];
        }

        $this->load->view('backend/home', $data);
    }

    // ================================================================
    // GET DATA KARTU STOK (AJAX)
    // ================================================================

    public function get_mutasi_stok()
    {
        $inventory_id  = intval($this->input->post('inventory_id'));
        $tgl_dari      = $this->input->post('tgl_dari');
        $tgl_sampai    = $this->input->post('tgl_sampai');
        $is_nasional   = $this->session->userdata('is_nasional') ? true : false;
        $session_cabang = intval($this->session->userdata('cabang_id'));
        $filter_cabang  = intval($this->input->post('filter_cabang'));

        // Tentukan cabang_id untuk filter transaksi
        if ($is_nasional) {
            $use_cabang_id = $filter_cabang > 0 ? $filter_cabang : null; // null = semua cabang
        } else {
            $use_cabang_id = $session_cabang; // paksa cabang sendiri
        }

        if (!$inventory_id) {
            echo json_encode(['status' => FALSE, 'error' => 'Barang belum dipilih']);
            return;
        }

        $tgl_dari_db   = !empty($tgl_dari)
            ? implode('-', array_reverse(explode('-', $tgl_dari))) : null;
        $tgl_sampai_db = !empty($tgl_sampai)
            ? implode('-', array_reverse(explode('-', $tgl_sampai))) : null;

        $barang = $this->M_mac_mutasi_stok->get_barang($inventory_id, $use_cabang_id);
        if (!$barang) {
            echo json_encode(['status' => FALSE, 'error' => 'Barang tidak ditemukan']);
            return;
        }

        $stok_awal_periode = $this->M_mac_mutasi_stok->get_stok_awal($inventory_id, $tgl_dari_db, $use_cabang_id);
        $transaksi         = $this->M_mac_mutasi_stok->get_transaksi($inventory_id, $tgl_dari_db, $tgl_sampai_db, $use_cabang_id);

        $stok = $stok_awal_periode;
        foreach ($transaksi as &$row) {
            $tipe  = strtolower($row['tipe']);
            $stok += in_array($tipe, ['masuk', 'penyesuaian_masuk', 'stok_awal'])
                ? floatval($row['jumlah'])
                : -floatval($row['jumlah']);
            $row['stok'] = $stok;
        }

        $total_masuk  = array_sum(array_column(
            array_filter($transaksi, fn($r) => in_array(strtolower($r['tipe']), ['masuk','penyesuaian_masuk','stok_awal'])),
            'jumlah'));
        $total_keluar = array_sum(array_column(
            array_filter($transaksi, fn($r) => !in_array(strtolower($r['tipe']), ['masuk','penyesuaian_masuk','stok_awal'])),
            'jumlah'));

        echo json_encode([
            'status'        => TRUE,
            'barang'        => $barang,
            'stok_awal'     => $stok_awal_periode,
            'transaksi'     => $transaksi,
            'total_masuk'   => $total_masuk,
            'total_keluar'  => $total_keluar,
            'stok_akhir'    => $stok,
            'is_nasional'   => $is_nasional,
            'use_cabang_id' => $use_cabang_id, // null = semua cabang
        ]);
    }

    public function get_rentang_tanggal()
    {
        $inventory_id = intval($this->input->post('inventory_id'));

        if (!$inventory_id) {
            echo json_encode(['status' => FALSE]); return;
        }

        // Ambil tanggal transaksi paling awal untuk barang ini
        $pertama = $this->db->select_min('transaksi_date')
            ->where('inventory_id', $inventory_id)
            ->get('mac_transaksi')->row();

        // Jika belum ada transaksi, fallback ke created_at barang
        if (!$pertama || empty($pertama->transaksi_date)) {
            $barang = $this->db->select('created_at')
                ->where('id', $inventory_id)
                ->get('mac_inventory')->row();
            $tgl_awal = $barang ? date('d-m-Y', strtotime($barang->created_at)) : date('d-m-Y');
        } else {
            $tgl_awal = date('d-m-Y', strtotime($pertama->transaksi_date));
        }

        echo json_encode([
            'status'   => TRUE,
            'tgl_awal' => $tgl_awal,          // format DD-MM-YYYY untuk datepicker
            'tgl_hari_ini' => date('d-m-Y'),  // batas atas
        ]);
    }

    // ================================================================
    // GET INVENTORY LIST UNTUK SELECT2
    // ================================================================

    public function get_inventory()
    {
        $search         = $this->input->post('search');
        $is_nasional    = $this->session->userdata('is_nasional') ? true : false;
        $session_cabang = intval($this->session->userdata('cabang_id'));
        $filter_cabang  = intval($this->input->post('filter_cabang'));

        // Tentukan cabang untuk filter stok
        if ($is_nasional) {
            $use_cabang_id = $filter_cabang > 0 ? $filter_cabang : null;
        } else {
            $use_cabang_id = $session_cabang;
        }

        if (is_null($use_cabang_id)) {
            // Nasional semua cabang → SUM stok, GROUP BY untuk hindari duplikat
            $this->db->select('
                i.id, i.kode_produk, i.nama_produk, i.satuan, i.kategori,
                COALESCE(SUM(s.stok_saat_ini), 0) as stok_saat_ini
            ', FALSE)
            ->from('mac_inventory i')
            ->join('mac_inventory_stok s', 's.inventory_id = i.id', 'left')
            ->where('i.is_active', 1)
            ->group_by('i.id');
        } else {
            // Cabang spesifik → filter cabang_id di JOIN
            $this->db->select('
                i.id, i.kode_produk, i.nama_produk, i.satuan, i.kategori,
                COALESCE(s.stok_saat_ini, 0) as stok_saat_ini
            ', FALSE)
            ->from('mac_inventory i')
            ->join('mac_inventory_stok s',
                's.inventory_id = i.id AND s.cabang_id = ' . $use_cabang_id,
                'left')
            ->where('i.is_active', 1);
        }

        if (!empty($search)) {
            $this->db->group_start()
                ->like('i.nama_produk', $search)
                ->or_like('i.kode_produk', $search)
                ->group_end();
        }

        $this->db->order_by('i.nama_produk', 'ASC');
        echo json_encode($this->db->get()->result());
    }

    // ================================================================
    // EXPORT EXCEL
    // ================================================================
    public function export_excel()
    {
        $inventory_id  = intval($this->input->get('inventory_id'));
        $tgl_dari      = $this->input->get('tgl_dari');
        $tgl_sampai    = $this->input->get('tgl_sampai');

        $tgl_dari_db   = !empty($tgl_dari)
            ? implode('-', array_reverse(explode('-', $tgl_dari))) : null;
        $tgl_sampai_db = !empty($tgl_sampai)
            ? implode('-', array_reverse(explode('-', $tgl_sampai))) : null;

        $barang            = $this->M_mac_mutasi_stok->get_barang($inventory_id);
        $stok_awal_periode = $this->M_mac_mutasi_stok->get_stok_awal($inventory_id, $tgl_dari_db);
        $transaksi         = $this->M_mac_mutasi_stok->get_transaksi($inventory_id, $tgl_dari_db, $tgl_sampai_db);

        // Hitung stok berjalan
        $stok = $stok_awal_periode;
        foreach ($transaksi as &$row) {
            $tipe  = strtolower($row['tipe']);
            $stok += in_array($tipe, ['masuk', 'penyesuaian_masuk', 'stok_awal'])
                ? floatval($row['jumlah'])
                : -floatval($row['jumlah']);
            $row['stok'] = $stok;
        }

        // Hitung total
        $total_masuk = $total_keluar = 0;
        foreach ($transaksi as $r) {
            $tipe = strtolower($r['tipe']);
            if (in_array($tipe, ['masuk', 'penyesuaian_masuk', 'stok_awal'])) {
                $total_masuk += $r['jumlah'];
            } else {
                $total_keluar += $r['jumlah'];
            }
        }

        // ===== BUILD EXCEL =====
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Mutasi Stok');

        // --- JUDUL ---
        $sheet->mergeCells('A1:J1');
        $sheet->setCellValue('A1', 'MUTASI STOK - ' . strtoupper($barang->nama_produk));
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 14],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ]);

        // --- INFO BARANG ---
        $sheet->setCellValue('A3', 'Kode Produk');
        $sheet->setCellValue('B3', ': ' . $barang->kode_produk);
        $sheet->setCellValue('A4', 'Nama Produk');
        $sheet->setCellValue('B4', ': ' . $barang->nama_produk);
        $sheet->setCellValue('A5', 'Kategori');
        $sheet->setCellValue('B5', ': ' . $barang->kategori);
        $sheet->setCellValue('D3', 'Satuan');
        $sheet->setCellValue('E3', ': ' . $barang->satuan);
        $sheet->setCellValue('D4', 'Stok Minimal');
        $sheet->setCellValue('E4', ': ' . $barang->stok_minimal);
        $sheet->setCellValue('D5', 'Stok Saat Ini');
        $sheet->setCellValue('E5', ': ' . $barang->stok_saat_ini . ' ' . $barang->satuan);
        $sheet->setCellValue('G3', 'Periode');
        $sheet->setCellValue('H3', ': ' . ($tgl_dari ?: '—') . ' s/d ' . ($tgl_sampai ?: '—'));
        $sheet->setCellValue('G4', 'Dicetak');
        $sheet->setCellValue('H4', ': ' . date('d-m-Y H:i:s'));

        // Bold label info
        foreach (['A3','A4','A5','D3','D4','D5','G3','G4'] as $cell) {
            $sheet->getStyle($cell)->getFont()->setBold(true);
        }

        // --- SUMMARY ---
        $sheet->setCellValue('A7', 'Stok Awal');
        $sheet->setCellValue('B7', $stok_awal_periode);
        $sheet->setCellValue('C7', 'Total Masuk');
        $sheet->setCellValue('D7', $total_masuk);
        $sheet->setCellValue('E7', 'Total Keluar');
        $sheet->setCellValue('F7', $total_keluar);
        $sheet->setCellValue('G7', 'Stok Akhir');
        $sheet->setCellValue('H7', $stok_awal_periode + $total_masuk - $total_keluar);

        $summaryStyle = [
            'font'    => ['bold' => true],
            'fill'    => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E2E8F0']],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
        ];
        $sheet->getStyle('A7:H7')->applyFromArray($summaryStyle);

        // --- HEADER TABEL ---
        $headers = ['No', 'Tanggal', 'Tipe', 'Keterangan', 'Dokumen', 'Masuk', 'Keluar', 'Stok', 'Harga Beli', 'Nilai'];
        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . '9', $h);
            $col++;
        }
        $headerStyle = [
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['rgb' => '242D4A']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
        ];
        $sheet->getStyle('A9:J9')->applyFromArray($headerStyle);

        // --- BARIS STOK AWAL PERIODE ---
        $currentRow = 10;
        if (!empty($tgl_dari)) {
            $sheet->setCellValue('A' . $currentRow, '');
            $sheet->setCellValue('B' . $currentRow, '');
            $sheet->setCellValue('C' . $currentRow, '');
            $sheet->setCellValue('D' . $currentRow, 'Stok awal per ' . $tgl_dari);
            $sheet->setCellValue('E' . $currentRow, '');
            $sheet->setCellValue('F' . $currentRow, '');
            $sheet->setCellValue('G' . $currentRow, '');
            $sheet->setCellValue('H' . $currentRow, $stok_awal_periode);
            $sheet->setCellValue('I' . $currentRow, '');
            $sheet->setCellValue('J' . $currentRow, '');
            $sheet->getStyle('A' . $currentRow . ':J' . $currentRow)->applyFromArray([
                'font' => ['italic' => true, 'color' => ['rgb' => '666666']],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F0F0F0']],
            ]);
            $currentRow++;
        }

        // --- DATA TRANSAKSI ---
        $no = 1;
        foreach ($transaksi as $row) {
            $tipe    = strtolower($row['tipe']);
            $isMasuk = in_array($tipe, ['masuk', 'penyesuaian_masuk', 'stok_awal']);
            $harga   = floatval($row['harga_beli_saat_transaksi']);
            $nilai   = $harga * floatval($row['jumlah']);

            // Dokumen sumber
            $dok = '';
            if (!empty($row['kode_reimbust']))  $dok = $row['kode_reimbust'];
            if (!empty($row['invoice_number'])) $dok = $row['invoice_number'];
            if (!empty($row['referensi']) && empty($dok)) $dok = $row['referensi'];

            // Keterangan
            $ket = $row['keterangan'] ?? '';
            if (!empty($row['nama_pelapor']))  $ket .= ' | Pelapor: ' . $row['nama_pelapor'];
            if (!empty($row['customer_name'])) $ket .= ' | Customer: ' . $row['customer_name'];
            if (!empty($row['kode_batch']))    $ket .= ' | Batch: ' . $row['kode_batch'];

            $sheet->setCellValue('A' . $currentRow, $no++);
            $sheet->setCellValue('B' . $currentRow, date('d-m-Y H:i', strtotime($row['transaksi_date'])));
            $sheet->setCellValue('C' . $currentRow, ucfirst($row['tipe']));
            $sheet->setCellValue('D' . $currentRow, $ket);
            $sheet->setCellValue('E' . $currentRow, $dok);
            $sheet->setCellValue('F' . $currentRow, $isMasuk  ? floatval($row['jumlah']) : '');
            $sheet->setCellValue('G' . $currentRow, !$isMasuk ? floatval($row['jumlah']) : '');
            $sheet->setCellValue('H' . $currentRow, $row['stok']);
            $sheet->setCellValue('I' . $currentRow, $harga ?: '');
            $sheet->setCellValue('J' . $currentRow, $nilai  ?: '');

            // Warna baris masuk/keluar
            if ($isMasuk) {
                $sheet->getStyle('F' . $currentRow)->getFont()->getColor()->setRGB('1a7a4a');
                $sheet->getStyle('F' . $currentRow)->getFont()->setBold(true);
            } else {
                $sheet->getStyle('G' . $currentRow)->getFont()->getColor()->setRGB('c0392b');
                $sheet->getStyle('G' . $currentRow)->getFont()->setBold(true);
            }

            // Format angka
            $sheet->getStyle('I' . $currentRow)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('J' . $currentRow)->getNumberFormat()->setFormatCode('#,##0');

            // Border baris
            $sheet->getStyle('A' . $currentRow . ':J' . $currentRow)->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                            'color'       => ['rgb' => 'DEE2E6']]],
            ]);

            // Zebra stripe
            if ($no % 2 === 0) {
                $sheet->getStyle('A' . $currentRow . ':J' . $currentRow)->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('F8F9FC');
            }

            $currentRow++;
        }

        // --- BARIS TOTAL ---
        $totalNilai = 0;
        foreach ($transaksi as $r) {
            $tipe = strtolower($r['tipe']);
            if (in_array($tipe, ['masuk', 'penyesuaian_masuk', 'stok_awal'])) {
                $totalNilai += floatval($r['harga_beli_saat_transaksi']) * floatval($r['jumlah']);
            }
        }

        $sheet->setCellValue('A' . $currentRow, '');
        $sheet->mergeCells('A' . $currentRow . ':E' . $currentRow);
        $sheet->setCellValue('A' . $currentRow, 'TOTAL');
        $sheet->setCellValue('F' . $currentRow, $total_masuk);
        $sheet->setCellValue('G' . $currentRow, $total_keluar);
        $sheet->setCellValue('H' . $currentRow, $stok_awal_periode + $total_masuk - $total_keluar);
        $sheet->setCellValue('I' . $currentRow, '');
        $sheet->setCellValue('J' . $currentRow, $totalNilai);
        $sheet->getStyle('A' . $currentRow . ':J' . $currentRow)->applyFromArray([
            'font'    => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'    => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '242D4A']],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
        ]);
        $sheet->getStyle('J' . $currentRow)->getNumberFormat()->setFormatCode('#,##0');

        // --- FORMAT KOLOM ---
        $sheet->getStyle('A9:A' . $currentRow)->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C9:C' . $currentRow)->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('F9:H' . $currentRow)->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Auto width kolom
        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        // Paksa kolom keterangan tidak terlalu lebar
        $sheet->getColumnDimension('D')->setWidth(40);
        $sheet->getStyle('D10:D' . $currentRow)->getAlignment()->setWrapText(true);

        // --- OUTPUT ---
        $filename = 'Mutasi_Stok_' . $barang->kode_produk
            . (!empty($tgl_dari) ? '_' . str_replace('-', '', $tgl_dari) : '')
            . (!empty($tgl_sampai) ? '_sd_' . str_replace('-', '', $tgl_sampai) : '')
            . '.xlsx';

        if (ob_get_length()) ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        (new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet))->save('php://output');
        exit;
    }
}
