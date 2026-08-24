<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mac_laporan_hpp extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->M_login->getsecurity();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        if (empty($this->session->userdata('cabang_id'))) {
            echo "
            <script>
                alert('Cabang belum diatur. Silakan hubungi Admin untuk melanjutkan.');
                window.location.href = '" . site_url('dashboard') . "';
            </script>";
            exit;
        }

        $data['title']      = 'backend/mac_laporan_hpp/mac_laporan_hpp';
        $data['titleview']  = 'Report HPP & Profit';
        $data['is_nasional'] = $this->session->userdata('is_nasional') ? true : false;
        $data['cabang_id'] = intval($this->session->userdata('cabang_id'));

        $data['list_cabang'] = $this->db
            ->where('status', 'aktif')
            ->where('id !=', 1)
            ->order_by('nama_cabang', 'ASC')
            ->get('mac_cabang')->result();

        $this->load->view('backend/home', $data);
    }

    public function get_data()
    {
        $tgl_dari       = $this->input->post('tgl_dari');
        $tgl_sampai     = $this->input->post('tgl_sampai');
        $filter_cabang  = intval($this->input->post('filter_cabang'));
        $is_nasional    = $this->session->userdata('is_nasional') ? true : false;
        $session_cabang = intval($this->session->userdata('cabang_id'));

        $tgl_dari_db   = !empty($tgl_dari)
            ? implode('-', array_reverse(explode('-', $tgl_dari))) : null;
        $tgl_sampai_db = !empty($tgl_sampai)
            ? implode('-', array_reverse(explode('-', $tgl_sampai))) : null;

        // Tentukan cabang filter
        // Nasional: pakai filter_cabang jika dipilih, null = semua
        // Cabang: paksa pakai cabang sendiri
        if ($is_nasional) {
            $use_cabang_id = $filter_cabang > 0 ? $filter_cabang : null;
        } else {
            $use_cabang_id = $session_cabang;
        }

        $q = $this->db->select('
                i.id,
                i.invoice_number,
                i.awal_service,
                i.nopol,
                i.km,
                c.customer_name,
                i.total_penjualan,
                i.total_hpp,
                i.profit,
                i.margin_persen,
                cab.nama_cabang,
                i.cabang_id
            ', FALSE)
            ->from('mac_invoice i')
            ->join('mac_customer c',  'c.id = i.customer_id',  'left')
            ->join('mac_cabang cab',  'cab.id = i.cabang_id',  'left')
            ->where('i.app_status', 'approved')
            ->where('i.is_active', 1);

        if (!empty($tgl_dari_db))   $q->where('DATE(i.awal_service) >=', $tgl_dari_db);
        if (!empty($tgl_sampai_db)) $q->where('DATE(i.awal_service) <=', $tgl_sampai_db);

        // Filter cabang
        if (!is_null($use_cabang_id)) {
            $q->where('i.cabang_id', $use_cabang_id);
        }

        $rows = $q->order_by('i.awal_service', 'ASC')
                ->order_by('i.id', 'ASC')
                ->get()->result_array();

        // Hitung summary
        $total_penjualan = array_sum(array_column($rows, 'total_penjualan'));
        $total_hpp       = array_sum(array_column($rows, 'total_hpp'));
        $total_profit    = array_sum(array_column($rows, 'profit'));

        // MARGIN OVERALL = Total Profit / Total Penjualan × 100
        // Bukan rata-rata per invoice - lebih akurat untuk ukur performa bisnis
        $overall_margin = $total_penjualan > 0
            ? round(($total_profit / $total_penjualan) * 100, 2)
            : 0;

        // MARKUP OVERALL = Total Profit / Total HPP × 100
        $overall_markup = $total_hpp > 0
            ? round(($total_profit / $total_hpp) * 100, 2)
            : null;

        echo json_encode([
            'status'          => TRUE,
            'rows'            => $rows,
            'total_penjualan' => $total_penjualan,
            'total_hpp'       => $total_hpp,
            'total_profit'    => $total_profit,
            'overall_margin'  => $overall_margin,  // ← ganti avg_margin
            'overall_markup'  => $overall_markup,
            'jumlah_invoice'  => count($rows),
        ]);
    }

    public function get_detail_hpp()
    {
        $invoice_id = intval($this->input->post('invoice_id'));
        if (!$invoice_id) {
            echo json_encode(['status' => FALSE, 'error' => 'ID tidak valid']);
            return;
        }

        // Info header invoice
        $invoice = $this->db->select('
                i.invoice_number, i.awal_service, i.nopol,
                c.customer_name,
                i.total_penjualan, i.total_hpp, i.profit, i.margin_persen
            ', FALSE)
            ->from('mac_invoice i')
            ->join('mac_customer c', 'c.id = i.customer_id', 'left')
            ->where('i.id', $invoice_id)
            ->get()->row_array();

        if (!$invoice) {
            echo json_encode(['status' => FALSE, 'error' => 'Invoice tidak ditemukan']);
            return;
        }

        // Detail per item
        $details = $this->db->select('
                d.id,
                d.tipe_item,
                d.item,
                d.qty,
                d.biaya as harga_jual_satuan,
                d.total as total_jual,
                d.harga_beli_jasa
            ', FALSE)
            ->from('mac_invoice_detail d')
            ->where('d.invoice_id', $invoice_id)
            ->where('d.is_active', 1)
            ->get()->result_array();

        $stokable = ['Sparepart', 'Bahan', 'Pelumas'];
        $rows     = [];

        foreach ($details as $d) {
            $total_jual = floatval($d['total_jual']);
            $hpp        = 0;

            if (in_array($d['tipe_item'], $stokable)) {
                // HPP barang - dari mac_transaksi FIFO
                $hpp_row = $this->db->select(
                        'COALESCE(SUM(t.harga_beli_saat_transaksi * t.jumlah), 0) as total_hpp', FALSE)
                    ->from('mac_transaksi t')
                    ->where('t.referensi_tipe', 'Invoice')
                    ->where('t.referensi_id', $d['id'])
                    ->where('t.tipe', 'Keluar')
                    ->get()->row();
                $hpp = $hpp_row ? floatval($hpp_row->total_hpp) : 0;

            } elseif ($d['tipe_item'] === 'Jasa') {
                // HPP jasa external dari harga_beli_jasa
                // Internal = 0 (tidak ada biaya keluar)
                $hpp = floatval($d['harga_beli_jasa'] ?? 0) * floatval($d['qty']);
            }

            $profit = $total_jual - $hpp;

            // Margin = Profit / Harga Jual × 100
            $margin = $total_jual > 0
                ? round(($profit / $total_jual) * 100, 2)
                : 0;

            // Markup = Profit / HPP × 100
            $markup = $hpp > 0
                ? round(($profit / $hpp) * 100, 2)
                : null; // null jika HPP = 0 (tidak bisa hitung markup)

            $rows[] = [
                'tipe_item'        => $d['tipe_item'],
                'item'             => $d['item'],
                'qty'              => $d['qty'],
                'harga_jual_satuan'=> floatval($d['harga_jual_satuan']),
                'total_jual'       => $total_jual,
                'hpp'              => $hpp,
                'profit'           => $profit,
                'margin'           => $margin,
                'markup'           => $markup,
            ];
        }

        // Hitung total
        $grand_jual   = array_sum(array_column($rows, 'total_jual'));
        $grand_hpp    = array_sum(array_column($rows, 'hpp'));
        $grand_profit = $grand_jual - $grand_hpp;
        $grand_margin = $grand_jual  > 0 ? round(($grand_profit / $grand_jual)  * 100, 2) : 0;
        $grand_markup = $grand_hpp   > 0 ? round(($grand_profit / $grand_hpp)   * 100, 2) : null;

        echo json_encode([
            'status'       => TRUE,
            'invoice'      => $invoice,
            'rows'         => $rows,
            'grand_jual'   => $grand_jual,
            'grand_hpp'    => $grand_hpp,
            'grand_profit' => $grand_profit,
            'grand_margin' => $grand_margin,
            'grand_markup' => $grand_markup,
        ]);
    }

    public function export_excel()
    {
        require APPPATH . '../vendor/autoload.php';

        $tgl_dari       = $this->input->get('tgl_dari');
        $tgl_sampai     = $this->input->get('tgl_sampai');
        $filter_cabang  = intval($this->input->get('filter_cabang'));
        $is_nasional    = $this->session->userdata('is_nasional') ? true : false;
        $session_cabang = intval($this->session->userdata('cabang_id'));

        $tgl_dari_db   = !empty($tgl_dari)
            ? implode('-', array_reverse(explode('-', $tgl_dari))) : null;
        $tgl_sampai_db = !empty($tgl_sampai)
            ? implode('-', array_reverse(explode('-', $tgl_sampai))) : null;

        $use_cabang_id = $is_nasional
            ? ($filter_cabang > 0 ? $filter_cabang : null)
            : $session_cabang;

        // ── AMBIL INVOICE ──────────────────────────────────────────────
        $q = $this->db->select('
                i.id as invoice_id, i.invoice_number, i.awal_service,
                i.nopol, i.km, c.customer_name,
                i.total_penjualan, i.total_hpp, i.profit, i.margin_persen,
                cab.nama_cabang
            ', FALSE)
            ->from('mac_invoice i')
            ->join('mac_customer c',  'c.id = i.customer_id',  'left')
            ->join('mac_cabang cab',  'cab.id = i.cabang_id',  'left')
            ->where('i.app_status', 'approved')
            ->where('i.is_active', 1);

        if (!empty($tgl_dari_db))   $q->where('DATE(i.awal_service) >=', $tgl_dari_db);
        if (!empty($tgl_sampai_db)) $q->where('DATE(i.awal_service) <=', $tgl_sampai_db);
        if (!is_null($use_cabang_id)) $q->where('i.cabang_id', $use_cabang_id);

        $invoices = $q->order_by('i.awal_service', 'ASC')
                    ->order_by('i.id', 'ASC')
                    ->get()->result_array();

        // ── AMBIL DETAIL + HITUNG HPP PER ITEM ────────────────────────
        $stokable = ['Sparepart', 'Bahan', 'Pelumas'];
        foreach ($invoices as &$inv) {
            $details = $this->db->select('
                    d.id, d.tipe_item, d.item, d.qty,
                    d.biaya as harga_jual_satuan,
                    d.total as total_jual,
                    d.harga_beli_jasa
                ', FALSE)
                ->from('mac_invoice_detail d')
                ->where('d.invoice_id', $inv['invoice_id'])
                ->where('d.is_active', 1)
                ->get()->result_array();

            foreach ($details as &$d) {
                $hpp = 0;
                if (in_array($d['tipe_item'], $stokable)) {
                    $hpp_row = $this->db->select(
                            'COALESCE(SUM(t.harga_beli_saat_transaksi * t.jumlah), 0) as total_hpp', FALSE)
                        ->from('mac_transaksi t')
                        ->where('t.referensi_tipe', 'Invoice')
                        ->where('t.referensi_id', $d['id'])
                        ->where('t.tipe', 'Keluar')
                        ->get()->row();
                    $hpp = $hpp_row ? floatval($hpp_row->total_hpp) : 0;
                } elseif ($d['tipe_item'] === 'Jasa') {
                    $hpp = floatval($d['harga_beli_jasa'] ?? 0) * floatval($d['qty']);
                }
                $total_jual   = floatval($d['total_jual']);
                $profit       = $total_jual - $hpp;
                $d['hpp']     = $hpp;
                $d['profit']  = $profit;
                $d['margin']  = $total_jual > 0 ? round(($profit / $total_jual) * 100, 2) : 0;
                $d['markup']  = $hpp > 0        ? round(($profit / $hpp)        * 100, 2) : null;
            }
            unset($d);
            $inv['details'] = $details;
        }
        unset($inv);

        // ── SUMMARY OVERALL ────────────────────────────────────────────
        $total_penjualan  = array_sum(array_column($invoices, 'total_penjualan'));
        $total_hpp        = array_sum(array_column($invoices, 'total_hpp'));
        $total_profit     = array_sum(array_column($invoices, 'profit'));
        $overall_margin   = $total_penjualan > 0
            ? round(($total_profit / $total_penjualan) * 100, 2) : 0;
        $overall_markup   = $total_hpp > 0
            ? round(($total_profit / $total_hpp) * 100, 2) : null;

        // ── BUILD EXCEL ────────────────────────────────────────────────
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Report HPP');
        $fmt     = '#,##0';
        $fmt_pct = '0.00"%"';

        $headerStyle = [
            'font'    => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'    => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '242D4A']],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ];
        $subHeaderStyle = [
            'font'    => ['bold' => true, 'size' => 9, 'color' => ['rgb' => 'FFFFFF']],
            'fill'    => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4A5568']],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
        ];
        $totalStyle = [
            'font'    => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'    => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '242D4A']],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
        ];

        // Judul
        $sheet->mergeCells('A1:K1');
        $sheet->setCellValue('A1', 'REPORT HPP & PROFIT');
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 13],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->mergeCells('A2:K2');
        $sheet->setCellValue('A2', 'Periode: ' . ($tgl_dari ?: '-') . ' s/d ' . ($tgl_sampai ?: '-'));
        $sheet->getStyle('A2')->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Summary overall
        $sheet->setCellValue('B4', 'Total Penjualan');  $sheet->setCellValue('C4', $total_penjualan);
        $sheet->setCellValue('D4', 'Total HPP');         $sheet->setCellValue('E4', $total_hpp);
        $sheet->setCellValue('F4', 'Gross Profit');       $sheet->setCellValue('G4', $total_profit);
        $sheet->setCellValue('H4', 'Overall Margin');     $sheet->setCellValue('I4', $overall_margin);
        $sheet->setCellValue('J4', 'Overall Markup');
        $sheet->setCellValue('K4', $overall_markup !== null ? $overall_markup : '-');
        $sheet->getStyle('B4:K4')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'EBF8FF']],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
        ]);
        foreach (['C4', 'E4', 'G4'] as $cell) {
            $sheet->getStyle($cell)->getNumberFormat()->setFormatCode($fmt);
        }
        foreach (['I4', 'K4'] as $cell) {
            if (is_numeric($sheet->getCell($cell)->getValue())) {
                $sheet->getStyle($cell)->getNumberFormat()->setFormatCode($fmt_pct);
            }
        }

        // Header tabel invoice
        $r = 6;
        $inv_headers = ['No', 'Invoice', 'Tgl Service', 'Nopol & Km', 'Customer',
                        'Cabang', 'Total Penjualan', 'HPP', 'Profit', 'Margin', 'Markup'];
        foreach ($inv_headers as $ci => $h) {
            $sheet->setCellValueByColumnAndRow($ci + 1, $r, $h);
        }
        $sheet->getStyle('A' . $r . ':K' . $r)->applyFromArray($headerStyle);
        $r++;

        // Data invoice + detail
        $no_inv = 1;
        foreach ($invoices as $inv) {
            $inv_margin = floatval($inv['margin_persen']);
            $inv_markup = floatval($inv['total_hpp']) > 0
                ? round((floatval($inv['profit']) / floatval($inv['total_hpp'])) * 100,     2)
                : null;

            // Warna baris invoice berdasarkan margin
            $inv_bg = $inv_margin >= 30 ? 'C6EFCE' : ($inv_margin >= 10 ? 'FFEB9C' : 'FFC7CE');

            $sheet->setCellValue('A' . $r, $no_inv++);
            $sheet->setCellValue('B' . $r, $inv['invoice_number']);
            $sheet->setCellValue('C' . $r, $inv['awal_service']
                ? date('d-m-Y', strtotime($inv['awal_service'])) : '-');
            $sheet->setCellValue('D' . $r, $inv['nopol'] . ' (' . number_format($inv['km'], 0, ',', '.') . ' Km)');
            $sheet->setCellValue('E' . $r, $inv['customer_name']);
            $sheet->setCellValue('F' . $r, $inv['nama_cabang']);
            $sheet->setCellValue('G' . $r, floatval($inv['total_penjualan']));
            $sheet->setCellValue('H' . $r, floatval($inv['total_hpp']));
            $sheet->setCellValue('I' . $r, floatval($inv['profit']));
            $sheet->setCellValue('J' . $r, $inv_margin);
            $sheet->setCellValue('K' . $r, $inv_markup !== null ? $inv_markup : '-');

            $sheet->getStyle('A' . $r . ':K' . $r)->applyFromArray([
                'font'    => ['bold' => true],
                'fill'    => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['rgb' => $inv_bg]],
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
            ]);
            foreach (['G', 'H', 'I'] as $col) {
                $sheet->getStyle($col . $r)->getNumberFormat()->setFormatCode($fmt);
            }
            foreach (['J', 'K'] as $col) {
                if (is_numeric($sheet->getCell($col . $r)->getValue())) {
                    $sheet->getStyle($col . $r)->getNumberFormat()->setFormatCode($fmt_pct);
                }
            }
            $r++;

            // Sub header detail item
            $detail_headers = ['', 'No', 'Item', 'Tipe', 'Qty',
                            'Harga/Satuan', 'Total Jual', 'HPP', 'Profit', 'Margin', 'Markup'];
            foreach ($detail_headers as $ci => $h) {
                $sheet->setCellValueByColumnAndRow($ci + 1, $r, $h);
            }
            $sheet->getStyle('A' . $r . ':K' . $r)->applyFromArray($subHeaderStyle);
            $r++;

            // Detail item
            $no_item = 1;
            foreach ($inv['details'] as $d) {
                $is_ext     = $d['tipe_item'] === 'Jasa' && $d['hpp'] > 0;
                $tipe_label = $d['tipe_item'] === 'Jasa'
                    ? ($is_ext ? 'Jasa Ext' : 'Jasa Int')
                    : $d['tipe_item'];

                $sheet->setCellValue('A' . $r, '');
                $sheet->setCellValue('B' . $r, $no_item++);
                $sheet->setCellValue('C' . $r, $d['item']);
                $sheet->setCellValue('D' . $r, $tipe_label);
                $sheet->setCellValue('E' . $r, $d['qty']);
                $sheet->setCellValue('F' . $r, floatval($d['harga_jual_satuan']));
                $sheet->setCellValue('G' . $r, floatval($d['total_jual']));
                $sheet->setCellValue('H' . $r, $d['hpp'] > 0 ? $d['hpp'] : '-');
                $sheet->setCellValue('I' . $r, floatval($d['profit']));
                $sheet->setCellValue('J' . $r, $d['margin']);
                $sheet->setCellValue('K' . $r, $d['markup'] !== null ? $d['markup'] : '-');

                $bg = $no_item % 2 === 0 ? 'F7FAFC' : 'FFFFFF';
                $sheet->getStyle('A' . $r . ':K' . $r)->applyFromArray([
                    'font'    => ['size' => 9],
                    'fill'    => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'startColor' => ['rgb' => $bg]],
                    'borders' => ['allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color'       => ['rgb' => 'CBD5E0'],
                    ]],
                ]);
                foreach (['F', 'G', 'I'] as $col) {
                    $sheet->getStyle($col . $r)->getNumberFormat()->setFormatCode($fmt);
                }
                if (is_numeric($sheet->getCell('H' . $r)->getValue())) {
                    $sheet->getStyle('H' . $r)->getNumberFormat()->setFormatCode($fmt);
                }
                foreach (['J', 'K'] as $col) {
                    if (is_numeric($sheet->getCell($col . $r)->getValue())) {
                        $sheet->getStyle($col . $r)->getNumberFormat()->setFormatCode($fmt_pct);
                    }
                }
                $r++;
            }
            $r++; // baris kosong pemisah
        }

        // Grand total
        $sheet->mergeCells('A' . $r . ':F' . $r);
        $sheet->setCellValue('A' . $r, 'GRAND TOTAL');
        $sheet->setCellValue('G' . $r, $total_penjualan);
        $sheet->setCellValue('H' . $r, $total_hpp);
        $sheet->setCellValue('I' . $r, $total_profit);
        $sheet->setCellValue('J' . $r, $overall_margin);
        $sheet->setCellValue('K' . $r, $overall_markup !== null ? $overall_markup : '-');
        $sheet->getStyle('A' . $r . ':K' . $r)->applyFromArray($totalStyle);
        foreach (['G', 'H', 'I'] as $col) {
            $sheet->getStyle($col . $r)->getNumberFormat()->setFormatCode($fmt);
        }
        foreach (['J', 'K'] as $col) {
            if (is_numeric($sheet->getCell($col . $r)->getValue())) {
                $sheet->getStyle($col . $r)->getNumberFormat()->setFormatCode($fmt_pct);
            }
        }

        // Auto width
        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        $sheet->getColumnDimension('C')->setWidth(35);
        $sheet->getColumnDimension('E')->setWidth(22);

        $filename = 'Report_HPP_PROFIT_MAC_' . date('Ymd_His') . '.xlsx';
        if (ob_get_length()) ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        (new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet))->save('php://output');
        exit;
    }

    public function export_excel_raw()
    {
        require APPPATH . '../vendor/autoload.php';

        $tgl_dari       = $this->input->get('tgl_dari');
        $tgl_sampai     = $this->input->get('tgl_sampai');
        $filter_cabang  = intval($this->input->get('filter_cabang'));
        $is_nasional    = $this->session->userdata('is_nasional') ? true : false;
        $session_cabang = intval($this->session->userdata('cabang_id'));

        $tgl_dari_db   = !empty($tgl_dari)
            ? implode('-', array_reverse(explode('-', $tgl_dari))) : null;
        $tgl_sampai_db = !empty($tgl_sampai)
            ? implode('-', array_reverse(explode('-', $tgl_sampai))) : null;

        $use_cabang_id = $is_nasional
            ? ($filter_cabang > 0 ? $filter_cabang : null)
            : $session_cabang;

        $stokable = ['Sparepart', 'Bahan', 'Pelumas'];

        // ── AMBIL DATA INVOICE + DETAIL ────────────────────────────────
        $q = $this->db->select('
                i.id                as invoice_id,
                i.invoice_number,
                i.awal_service,
                i.akhir_service,
                i.due_date,
                i.nopol,
                i.km,
                i.jenis_kendaraan,
                i.tipe,
                i.payment_status,
                i.uraian            as kategori_pekerjaan,
                c.customer_name,
                c.type_customer,
                cab.nama_cabang,
                i.total_penjualan   as invoice_total_penjualan,
                i.total_hpp         as invoice_total_hpp,
                i.profit            as invoice_profit,
                i.margin_persen     as invoice_margin,
                d.id                as detail_id,
                d.tipe_item,
                d.item              as nama_item,
                d.qty,
                d.biaya             as harga_jual_satuan,
                d.diskon,
                d.total             as total_jual,
                d.harga_beli_jasa
            ', FALSE)
            ->from('mac_invoice i')
            ->join('mac_customer c',       'c.id = i.customer_id',                    'left')
            ->join('mac_cabang cab',       'cab.id = i.cabang_id',                    'left')
            ->join('mac_invoice_detail d', 'd.invoice_id = i.id AND d.is_active = 1', 'left')
            ->where('i.app_status', 'approved')
            ->where('i.is_active', 1);

        if (!empty($tgl_dari_db))     $q->where('DATE(i.awal_service) >=', $tgl_dari_db);
        if (!empty($tgl_sampai_db))   $q->where('DATE(i.awal_service) <=', $tgl_sampai_db);
        if (!is_null($use_cabang_id)) $q->where('i.cabang_id', $use_cabang_id);

        $rows = $q->order_by('i.awal_service', 'ASC')
                ->order_by('i.id', 'ASC')
                ->order_by('d.id', 'ASC')
                ->get()->result_array();

        // ── AMBIL PAYMENT PER INVOICE ──────────────────────────────────
        $invoice_ids = array_unique(array_column($rows, 'invoice_id'));
        $payment_map = [];

        if (!empty($invoice_ids)) {
            $payments = $this->db->select('invoice_id, SUM(nominal) as total_bayar', FALSE)
                ->from('mac_invoice_payment')
                ->where_in('invoice_id', $invoice_ids)
                ->group_by('invoice_id')
                ->get()->result_array();

            foreach ($payments as $p) {
                $payment_map[$p['invoice_id']]['total_bayar'] = floatval($p['total_bayar']);
            }

            $pelunasan = $this->db->select('invoice_id, MAX(tgl_bayar) as tgl_lunas')
                ->from('mac_invoice_payment')
                ->where_in('invoice_id', $invoice_ids)
                ->group_by('invoice_id')
                ->get()->result_array();

            foreach ($pelunasan as $p) {
                $payment_map[$p['invoice_id']]['tgl_lunas'] = $p['tgl_lunas'];
            }
        }

        // ── PROSES & GROUP PER INVOICE ─────────────────────────────────
        $invoices_grouped = [];

        foreach ($rows as &$row) {
            $inv_id = $row['invoice_id'];

            // HPP per item
            $hpp = 0;
            if (in_array($row['tipe_item'], $stokable)) {
                $hpp_row = $this->db->select(
                        'COALESCE(SUM(t.harga_beli_saat_transaksi * t.jumlah), 0) as total_hpp', FALSE)
                    ->from('mac_transaksi t')
                    ->where('t.referensi_tipe', 'Invoice')
                    ->where('t.referensi_id',   $row['detail_id'])
                    ->where('t.tipe', 'Keluar')
                    ->get()->row();
                $hpp = $hpp_row ? floatval($hpp_row->total_hpp) : 0;
            } elseif ($row['tipe_item'] === 'Jasa') {
                $hpp = floatval($row['harga_beli_jasa'] ?? 0) * floatval($row['qty']);
            }

            $total_jual         = floatval($row['total_jual']);
            $profit             = $total_jual - $hpp;
            $row['hpp_item']    = $hpp;
            $row['profit_item'] = $profit;
            $row['margin_item'] = $total_jual > 0 ? ($profit / $total_jual) : 0;
            $row['markup_item'] = $hpp > 0        ? ($profit / $hpp)        : null;
            $row['tipe_label']  = $row['tipe_item'] === 'Jasa'
                ? 'Jasa ' . (floatval($row['harga_beli_jasa']) > 0 ? 'External' : 'Internal')
                : $row['tipe_item'];

            // Lama pengerjaan (hari)
            $tgl_awal   = !empty($row['awal_service'])     ? strtotime($row['awal_service'])     : null;
            $tgl_akhir  = !empty($row['akhir_service']) ? strtotime($row['akhir_service']) : null;
            $row['lama_pengerjaan'] = ($tgl_awal && $tgl_akhir)
                ? (int) (floor(($tgl_akhir - $tgl_awal) / 86400) + 1)
                : '';

            // Payment
            $pay                   = $payment_map[$inv_id] ?? null;
            $total_bayar           = $pay['total_bayar'] ?? 0;
            $status_paid           = strtolower($row['payment_status'] ?? '') === 'paid';
            $row['total_bayar']    = $total_bayar;
            $row['kekurangan']     = max(0, floatval($row['invoice_total_penjualan']) - $total_bayar);
            $row['tgl_lunas']      = $status_paid ? ($pay['tgl_lunas'] ?? null) : null;
            $row['status_display'] = $status_paid ? 'LUNAS' : 'BELUM LUNAS';

            // Invoice margin/markup desimal
            $inv_penj              = floatval($row['invoice_total_penjualan']);
            $inv_hpp               = floatval($row['invoice_total_hpp']);
            $inv_profit            = floatval($row['invoice_profit']);
            $row['inv_margin_dec'] = $inv_penj > 0 ? ($inv_profit / $inv_penj) : 0;
            $row['inv_markup_dec'] = $inv_hpp  > 0 ? ($inv_profit / $inv_hpp)  : null;

            // Periode YYYYMM
            $row['periode'] = !empty($row['invoice_number'])
                ? '20' . substr($row['invoice_number'], 6, 4)
                : '';

            $invoices_grouped[$inv_id][] = $row;
        }
        unset($row);

        // ── BUILD EXCEL ────────────────────────────────────────────────
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Raw HPP');

        $fmt     = '#,##0';
        $fmt_pct = '0.00%';
        $fmt_dt  = \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_DDMMYYYY;

        // Warna
        $COLOR_HEADER   = '242D4A';
        $INV_COLORS     = ['EBF8FF', 'FFF9F0']; // bergantian per invoice
        $ITEM_ODD       = 'FFFFFF';
        $ITEM_EVEN      = 'F0F4F8';
        $MERGE_COLS_BG  = ['EBF8FF', 'FFF9F0']; // kolom merge ikut warna invoice

        // ── HEADER ─────────────────────────────────────────────────────
        $headers = [
            'No Invoice',            // A
            'Kategori Pekerjaan',    // B
            'Periode',               // C
            'Cabang',                // D
            'Tipe Customer',         // E
            'Nama Customer',         // F
            'Jenis Kendaraan',       // G
            'Merk Kendaraan',        // H
            'Nopol',                 // I
            'Kilometer',             // J
            'Awal Service',          // K
            'Akhir Service',         // L
            'Lama Pengerjaan',       // M
            'Tgl Jatuh Tempo',       // N
            'Tipe Item',             // O
            'Nama Item',             // P  ─── per item ───
            'Harga/Satuan',          // Q
            'Qty',                   // R
            'Diskon',                // S
            'Total Jual Item',       // T
            'HPP Item',              // U
            'Profit Item',           // V
            'Margin Item',           // W
            'Markup Item',           // X  ─── per invoice (merge) ───
            'Total Penjualan',       // Y
            'Total HPP',             // Z
            'Total Profit',          // AA
            'Total Margin',          // AB
            'Total Markup',          // AC
            'Terbayar',              // AD
            'Kekurangan',            // AE
            'Tgl Bayar Lunas',       // AF
            'Status Pembayaran',     // AG
        ];

        foreach ($headers as $ci => $h) {
            $sheet->setCellValueByColumnAndRow($ci + 1, 1, $h);
        }

        $lastCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($headers));
        $sheet->getStyle('A1:' . $lastCol . '1')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
            'fill'      => ['fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['rgb' => $COLOR_HEADER]],
            'borders'   => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                            'color'       => ['rgb' => '4A5568']]],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                            'wrapText'   => true],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(40);
        $sheet->freezePane('A2');
        $sheet->setAutoFilter('A1:' . $lastCol . '1');

        // Helper: set date cell
        $setDate = function($col, $row, $val) use ($sheet, $fmt_dt) {
            if (!empty($val)) {
                $sheet->setCellValue($col . $row,
                    \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel(strtotime($val))
                );
                $sheet->getStyle($col . $row)->getNumberFormat()->setFormatCode($fmt_dt);
            } else {
                $sheet->setCellValue($col . $row, '');
            }
        };

        // Kolom yang di-merge per invoice
        $merge_cols = ['Y','Z','AA','AB','AC','AD','AE','AF','AG'];

        // ── DATA ROWS ──────────────────────────────────────────────────
        $r      = 2;
        $inv_no = 0;

        foreach ($invoices_grouped as $inv_id => $items) {
            $inv_no++;
            $row_start  = $r;
            $row_end    = $r + count($items) - 1;
            $item_count = count($items);
            $inv_color  = $INV_COLORS[$inv_no % 2];

            foreach ($items as $idx => $item) {
                $item_bg = $idx % 2 === 0 ? $ITEM_ODD : $ITEM_EVEN;

                // ── Kolom A–O: info invoice (diisi tiap baris agar VLOOKUP tetap bisa) ──
                $sheet->setCellValue('A' . $r, $item['invoice_number']);
                $sheet->setCellValue('B' . $r, $item['kategori_pekerjaan'] ?? '');
                $sheet->setCellValue('C' . $r, intval($item['periode']));
                $sheet->setCellValue('D' . $r, $item['nama_cabang']);
                $sheet->setCellValue('E' . $r, $item['type_customer'] ?? '');
                $sheet->setCellValue('F' . $r, $item['customer_name']);
                $sheet->setCellValue('G' . $r, $item['jenis_kendaraan'] ?? '');
                $sheet->setCellValue('H' . $r, $item['tipe']  ?? '');
                $sheet->setCellValue('I' . $r, $item['nopol']);
                $sheet->setCellValue('J' . $r, is_numeric($item['km']) ? floatval($item['km']) : '');
                $setDate('K', $r, $item['awal_service']);
                $setDate('L', $r, $item['akhir_service']);
                $sheet->setCellValue('M' . $r, $item['lama_pengerjaan'] . ' Hari');
                $setDate('N', $r, $item['due_date']);

                // ── Kolom P–Y: per item ──
                $sheet->setCellValue('O' . $r, $item['tipe_label']);
                $sheet->setCellValue('P' . $r, $item['nama_item']);
                $sheet->setCellValue('Q' . $r, floatval($item['harga_jual_satuan']));
                $sheet->setCellValue('R' . $r, floatval($item['qty']));
                $sheet->setCellValue('S' . $r, floatval($item['diskon']));
                $sheet->setCellValue('T' . $r, floatval($item['total_jual']));
                $sheet->setCellValue('U' . $r, $item['hpp_item']);
                $sheet->setCellValue('V' . $r, $item['profit_item']);
                $sheet->setCellValue('W' . $r, $item['margin_item']);
                $sheet->setCellValue('X' . $r, $item['markup_item'] !== null ? $item['markup_item'] : '');

                // ── Kolom Y–AG: per invoice (isi hanya di baris pertama, sisanya merge) ──
                if ($idx === 0) {
                    $sheet->setCellValue('Y'  . $r, floatval($item['invoice_total_penjualan']));
                    $sheet->setCellValue('Z' . $r, floatval($item['invoice_total_hpp']));
                    $sheet->setCellValue('AA' . $r, floatval($item['invoice_profit']));
                    $sheet->setCellValue('AB' . $r, $item['inv_margin_dec']);
                    $sheet->setCellValue('AC' . $r, $item['inv_markup_dec'] !== null ? $item['inv_markup_dec'] : '');
                    $sheet->setCellValue('AD' . $r, $item['total_bayar']);
                    $sheet->setCellValue('AE' . $r, $item['kekurangan']);
                    $setDate('AF', $r, $item['tgl_lunas']);
                    $sheet->setCellValue('AG' . $r, $item['status_display']);
                }

                // Format NUMBER per item
                foreach (['J', 'Q', 'S', 'T', 'U', 'V'] as $col) {
                    $sheet->getStyle($col . $r)->getNumberFormat()->setFormatCode($fmt);
                }
                // Format PERCENT per item
                foreach (['W', 'X'] as $col) {
                    if (is_numeric($sheet->getCell($col . $r)->getValue())) {
                        $sheet->getStyle($col . $r)->getNumberFormat()->setFormatCode($fmt_pct);
                    }
                }

                // Warna A–Y (kolom per item)
                $sheet->getStyle('A' . $r . ':X' . $r)->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB($item_bg);

                // Warna Z–AH (kolom per invoice — warna bergantian tiap invoice)
                $sheet->getStyle('Y' . $r . ':AG' . $r)->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB($inv_color);

                // Border per baris
                $sheet->getStyle('A' . $r . ':AG' . $r)->applyFromArray([
                    'borders' => ['allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color'       => ['rgb' => 'DEE2E6'],
                    ]],
                ]);

                $r++;
            }

            // ── MERGE kolom invoice jika item > 1 ──────────────────────
            if ($item_count > 1) {
                foreach ($merge_cols as $col) {
                    $sheet->mergeCells($col . $row_start . ':' . $col . $row_end);
                }
            }

            // Apply alignment ke semua kolom merge — selalu, tidak peduli jumlah item
            foreach ($merge_cols as $col) {
                $sheet->getStyle($col . $row_start)->getAlignment()
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                    ->setWrapText(false);
            }

            // Format NUMBER kolom invoice (apply ke row_start karena merge)
            foreach (['Y', 'Z', 'AA', 'AB', 'AD', 'AE'] as $col) {
                $sheet->getStyle($col . $row_start)->getNumberFormat()->setFormatCode($fmt);
            }
            foreach (['AB', 'AC'] as $col) {
                if (is_numeric($sheet->getCell($col . $row_start)->getValue())) {
                    $sheet->getStyle($col . $row_start)->getNumberFormat()->setFormatCode($fmt_pct);
                }
            }

            // Border tebal outline per invoice
            $sheet->getStyle('A' . $row_start . ':AG' . $row_end)->applyFromArray([
                'borders' => ['outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                    'color'       => ['rgb' => '718096'],
                ]],
            ]);
        }

        // ── AUTO WIDTH ─────────────────────────────────────────────────
        foreach (range('A', 'Z') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        foreach (['AA', 'AB','AC','AD','AE','AF','AG'] as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        // Fixed width untuk kolom teks panjang
        // $sheet->getColumnDimension('F')->setWidth(28);
        // $sheet->getColumnDimension('P')->setWidth(30);

        // ── OUTPUT ─────────────────────────────────────────────────────
        $filename = 'Raw_HPP_PROFIT_MAC_' . date('Ymd_His') . '.xlsx';
        if (ob_get_length()) ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        (new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet))->save('php://output');
        exit;
    }
}