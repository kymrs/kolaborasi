<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mac_insentif extends CI_Controller
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
            echo "<script>
                alert('Cabang belum diatur. Silakan hubungi Admin untuk melanjutkan.');
                window.location.href = '" . site_url('dashboard') . "';
            </script>";
            exit;
        }

        $is_nasional    = $this->session->userdata('is_nasional') ? true : false;
        $session_cabang = (int) $this->session->userdata('cabang_id');

        // List mekanik — filter per cabang jika bukan nasional
        $this->db->select('m.id, m.nama, m.npk, m.cabang_id, c.nama_cabang')
            ->from('mac_mekanik m')
            ->join('mac_cabang c', 'c.id = m.cabang_id', 'left');

        if (!$is_nasional) {
            $this->db->where('m.cabang_id', $session_cabang);
        }

        $this->db->order_by('m.nama', 'ASC');
        $data['list_mekanik'] = $this->db->get()->result();

        // TAMBAHKAN: list_cabang untuk dropdown filter Nasional
        if ($is_nasional) {
            $data['list_cabang'] = $this->db
                ->where('status', 'aktif')
                ->where('id !=', 1)
                ->order_by('nama_cabang', 'ASC')
                ->get('mac_cabang')->result();
        } else {
            $data['list_cabang'] = [];
        }

        $data['is_nasional'] = $is_nasional;
        $data['title']       = 'backend/mac_insentif/mac_insentif_list';
        $data['titleview']   = 'Insentif Mekanik';

        $this->load->view('backend/home', $data);
    }

    // GET semua config (untuk ditampilkan di UI)
    public function get_all()
    {
        $data = $this->db->order_by('kategori', 'ASC')
            ->order_by('level', 'ASC')
            ->get('mac_insentif')->result_array();
        echo json_encode($data);
    }

    // GET nominal berdasarkan kategori + level (untuk kalkulasi insentif)
    public function get_nominal()
    {
        $kategori = $this->input->post('kategori');
        $level    = intval($this->input->post('level'));

        $row = $this->db->where('kategori', $kategori)
            ->where('level', $level)
            ->where('is_active', 1)
            ->get('mac_insentif')->row();

        echo json_encode([
            'status'  => $row ? TRUE : FALSE,
            'nominal' => $row ? floatval($row->nominal) : 0,
        ]);
    }

    // UPDATE nominal (bulk update semua sekaligus)
    public function update()
    {
        $ids     = $this->input->post('id')      ?: [];
        $nominals = $this->input->post('nominal') ?: [];

        foreach ($ids as $i => $id) {
            $nominal = intval(str_replace('.', '', $nominals[$i] ?? 0));
            if ($nominal < 0) continue;
            $this->db->where('id', intval($id))->update('mac_insentif', [
                'nominal'    => $nominal,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        echo json_encode(['status' => TRUE, 'message' => 'Konfigurasi berhasil disimpan']);
    }

    public function rekap_insentif()
    {
        $data['title']       = 'backend/mac_insentif/mac_insentif_list';
        $data['titleview']   = 'Konfigurasi Insentif Mekanik';
        $data['is_nasional'] = $this->session->userdata('is_nasional') ? true : false;
        $data['cabang_id']   = intval($this->session->userdata('cabang_id'));

        if ($data['is_nasional']) {
            $data['list_cabang'] = $this->db
                ->where('status', 'aktif')
                ->where('id !=', 1)
                ->order_by('nama_cabang', 'ASC')
                ->get('mac_cabang')->result();
        } else {
            $data['list_cabang'] = [];
        }

        // Dropdown mekanik — filter per cabang
        $is_nasional    = $data['is_nasional'];
        $session_cabang = $data['cabang_id'];

        $q = $this->db->select('m.id, m.nama, m.npk, c.nama_cabang')
            ->from('mac_mekanik m')
            ->join('mac_cabang c', 'c.id = m.cabang_id', 'left')
            ->order_by('m.nama', 'ASC');

        if (!$is_nasional) {
            $q->where('m.cabang_id', $session_cabang);
        }

        $data['list_mekanik'] = $q->get()->result();

        $this->load->view('backend/home', $data);
    }

    public function get_rekap_insentif()
    {
        $is_nasional    = $this->session->userdata('is_nasional') ? true : false;
        $session_cabang = intval($this->session->userdata('cabang_id'));
        $bulan          = $this->input->post('bulan');        // format YYYY-MM
        $mekanik_id     = intval($this->input->post('mekanik_id'));
        $filter_cabang  = intval($this->input->post('filter_cabang'));

        // Tentukan cabang untuk filter
        $use_cabang_id = $is_nasional
            ? ($filter_cabang > 0 ? $filter_cabang : null)
            : $session_cabang;

        $q = $this->db->select('
                mk.id as mekanik_id,
                mk.nama,
                mk.npk,
                c.nama_cabang,
                im.level,
                im.kategori,
                im.nominal_per_mekanik,
                i.invoice_number,
                i.invoice_date,
                i.awal_service,
                i.nopol,
                cust.customer_name
            ', FALSE)
            ->from('mac_invoice_mekanik im')
            ->join('mac_mekanik mk',   'mk.id = im.mekanik_id',    'left')
            ->join('mac_cabang c',     'c.id = mk.cabang_id',      'left')
            ->join('mac_invoice i',    'i.id = im.invoice_id',     'left')
            ->join('mac_customer cust','cust.id = i.customer_id',  'left')
            ->where('i.app_status', 'approved')
            ->where('i.is_active', 1);

        // Filter cabang mekanik
        if (!is_null($use_cabang_id)) {
            $q->where('mk.cabang_id', $use_cabang_id);
        }

        // Filter bulan
        if (!empty($bulan)) {
            $q->where('DATE_FORMAT(i.invoice_date, \'%Y-%m\') = ', $bulan, FALSE);
        }

        // Filter mekanik spesifik
        if ($mekanik_id > 0) {
            $q->where('im.mekanik_id', $mekanik_id);
        }

        $q->order_by('mk.nama', 'ASC')
        ->order_by('i.invoice_date', 'ASC');

        $detail = $q->get()->result_array();

        // Group by mekanik untuk summary
        $summary = [];
        foreach ($detail as $row) {
            $mid = $row['mekanik_id'];
            if (!isset($summary[$mid])) {
                $summary[$mid] = [
                    'mekanik_id'     => $mid,
                    'nama'           => $row['nama'],
                    'npk'            => $row['npk'],
                    'nama_cabang'    => $row['nama_cabang'],
                    'total_insentif' => 0,
                    'jumlah_invoice' => 0,
                ];
            }
            $summary[$mid]['total_insentif'] += floatval($row['nominal_per_mekanik']);
            $summary[$mid]['jumlah_invoice'] += 1;
        }

        echo json_encode([
            'status'  => TRUE,
            'detail'  => $detail,
            'summary' => array_values($summary),
        ]);
    }

       
    public function export_rekap_insentif_raw()
    {
        require APPPATH . '../vendor/autoload.php';

        $is_nasional    = $this->session->userdata('is_nasional') ? true : false;
        $session_cabang = intval($this->session->userdata('cabang_id'));
        $bulan          = $this->input->get('bulan');
        $mekanik_id     = intval($this->input->get('mekanik_id'));
        $filter_cabang  = intval($this->input->get('filter_cabang'));

        $use_cabang_id = $is_nasional
            ? ($filter_cabang > 0 ? $filter_cabang : null)
            : $session_cabang;

        $q = $this->db->select('
                mk.id as mekanik_id,
                mk.nama, mk.npk,
                c.nama_cabang,
                im.level, im.kategori,
                im.nominal_total,
                im.nominal_per_mekanik,
                i.invoice_number,
                i.invoice_date,
                i.nopol,
                cust.customer_name
            ', FALSE)
            ->from('mac_invoice_mekanik im')
            ->join('mac_mekanik mk',    'mk.id = im.mekanik_id',   'left')
            ->join('mac_cabang c',      'c.id = mk.cabang_id',     'left')
            ->join('mac_invoice i',     'i.id = im.invoice_id',    'left')
            ->join('mac_customer cust', 'cust.id = i.customer_id', 'left')
            ->where('i.app_status', 'approved')
            ->where('i.is_active', 1);

        if (!is_null($use_cabang_id)) $q->where('mk.cabang_id', $use_cabang_id);
        if (!empty($bulan))           $q->where('DATE_FORMAT(i.invoice_date, \'%Y-%m\') = ', $bulan, FALSE);
        if ($mekanik_id > 0)          $q->where('im.mekanik_id', $mekanik_id);

        $rows = $q->order_by('mk.nama', 'ASC')
                ->order_by('i.invoice_date', 'ASC')
                ->get()->result_array();

        // ── BUILD EXCEL ────────────────────────────────────────────────
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Raw Insentif');
        $fmt = '#,##0';

        // Header — 1 baris, langsung data
        $headers = [
            'No',
            'Nama Mekanik',
            'NPK',
            'Cabang',
            'No Invoice',
            'Tgl Service',
            'Nopol',
            'Customer',
            'Kategori',
            'Level',
            'Nominal Max Level',
            'Insentif Diterima',
        ];

        foreach ($headers as $ci => $h) {
            $sheet->setCellValueByColumnAndRow($ci + 1, 1, $h);
        }

        $lastCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($headers));

        $sheet->getStyle('A1:' . $lastCol . '1')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['rgb' => '242D4A']],
            'borders'   => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
        ]);

        // Freeze + autofilter
        $sheet->freezePane('A2');
        $sheet->setAutoFilter('A1:' . $lastCol . '1');

        // Data rows
        $r = 2;
        foreach ($rows as $d) {
            $sheet->setCellValue('A' . $r, $r - 1);
            $sheet->setCellValue('B' . $r, $d['nama']);
            $sheet->setCellValue('C' . $r, $d['npk'] ?: '');
            $sheet->setCellValue('D' . $r, $d['nama_cabang']);
            $sheet->setCellValue('E' . $r, $d['invoice_number']);

            // Tanggal sebagai Date native Excel
            if (!empty($d['invoice_date'])) {
                $sheet->setCellValue('F' . $r,
                    \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel(
                        strtotime($d['invoice_date'])
                    )
                );
                $sheet->getStyle('F' . $r)->getNumberFormat()
                    ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_DDMMYYYY);
            } else {
                $sheet->setCellValue('F' . $r, '');
            }

            $sheet->setCellValue('G' . $r, $d['nopol']);
            $sheet->setCellValue('H' . $r, $d['customer_name']);
            $sheet->setCellValue('I' . $r, $d['kategori']);
            $sheet->setCellValue('J' . $r, intval($d['level']));
            $sheet->setCellValue('K' . $r, floatval($d['nominal_total']));
            $sheet->setCellValue('L' . $r, floatval($d['nominal_per_mekanik']));

            // Format angka
            foreach (['K', 'L'] as $col) {
                $sheet->getStyle($col . $r)->getNumberFormat()->setFormatCode($fmt);
            }

            // Zebra stripe
            if ($r % 2 === 0) {
                $sheet->getStyle('A' . $r . ':L' . $r)->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('F8F9FC');
            }

            $r++;
        }

        // Border semua data
        if ($r > 2) {
            $sheet->getStyle('A2:L' . ($r - 1))->applyFromArray([
                'borders' => ['allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color'       => ['rgb' => 'DEE2E6'],
                ]],
            ]);
        }

        // Auto width
        foreach (range('A', 'L') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'Raw_Insentif' . ($bulan ? '_' . $bulan : '') . '_' . date('Ymd_His') . '.xlsx';
        if (ob_get_length()) ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        (new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet))->save('php://output');
        exit;
    }

    public function export_rekap_insentif()
    {
        require APPPATH . '../vendor/autoload.php';

        $is_nasional    = $this->session->userdata('is_nasional') ? true : false;
        $session_cabang = intval($this->session->userdata('cabang_id'));
        $bulan          = $this->input->get('bulan');
        $mekanik_id     = intval($this->input->get('mekanik_id'));
        $filter_cabang  = intval($this->input->get('filter_cabang'));

        $use_cabang_id = $is_nasional
            ? ($filter_cabang > 0 ? $filter_cabang : null)
            : $session_cabang;

        // ── AMBIL SEMUA DATA INSENTIF ──────────────────────────────────
        $q = $this->db->select('
                mk.id       as mekanik_id,
                mk.nama,
                mk.npk,
                c.nama_cabang,
                im.level,
                im.kategori,
                im.nominal_per_mekanik,
                i.invoice_date
            ', FALSE)
            ->from('mac_invoice_mekanik im')
            ->join('mac_mekanik mk',    'mk.id = im.mekanik_id',   'left')
            ->join('mac_cabang c',      'c.id = mk.cabang_id',     'left')
            ->join('mac_invoice i',     'i.id = im.invoice_id',    'left')
            ->where('i.app_status', 'approved')
            ->where('i.is_active', 1);

        if (!is_null($use_cabang_id)) $q->where('mk.cabang_id', $use_cabang_id);
        if (!empty($bulan))           $q->where('DATE_FORMAT(i.invoice_date, \'%Y-%m\') = ', $bulan, FALSE);
        if ($mekanik_id > 0)          $q->where('im.mekanik_id', $mekanik_id);

        $rows = $q->order_by('mk.nama', 'ASC')->get()->result_array();

        // ── PIVOT: group by mekanik, sum per level per kategori ────────
        // Level 1-6, kategori Mobil & Motor
        // Level 7 = "Khusus" (custom)
        $level_labels = [
            1 => 'Level 1',
            2 => 'Level 2',
            3 => 'Level 3',
            4 => 'Level 4',
            5 => 'Level 5',
            6 => 'Level 6',
            7 => 'Khusus'
        ];

        $levels = [1, 2, 3, 4, 5, 6, 7];
        $kategories = ['Mobil', 'Motor'];

        $pivot = []; // pivot[$mekanik_id]['Mobil'][1] = nominal

        foreach ($rows as $row) {
            $mid  = $row['mekanik_id'];
            $kat  = $row['kategori'];
            $lvl  = intval($row['level']);

            if (!isset($pivot[$mid])) {
                $pivot[$mid] = [
                    'nama'        => $row['nama'],
                    'npk'         => $row['npk'] ?: '-',
                    'nama_cabang' => $row['nama_cabang'],
                    'Mobil'       => array_fill_keys($levels, 0),
                    'Motor'       => array_fill_keys($levels, 0),
                ];
            }

            $pivot[$mid][$kat][$lvl] += floatval($row['nominal_per_mekanik']);
        }

        // Hitung total per mekanik
        foreach ($pivot as $mid => &$p) {
            $total = 0;
            foreach ($kategories as $kat) {
                foreach ($levels as $lvl) {
                    $total += $p[$kat][$lvl];
                }
            }
            $p['total'] = $total;
        }
        unset($p);

        // ── BUILD EXCEL ────────────────────────────────────────────────
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Rekap Insentif');

        $fmt = '#,##0';

        $COLOR_HEADER     = '242D4A';
        $COLOR_MOBIL      = '2B6CB0'; // biru tua
        $COLOR_MOTOR      = '276749'; // hijau tua
        $COLOR_TOTAL      = '744210'; // coklat
        $COLOR_SUB_MOBIL  = 'BEE3F8'; // biru muda
        $COLOR_SUB_MOTOR  = 'C6F6D5'; // hijau muda
        $COLOR_ROW_ODD    = 'FFFFFF';
        $COLOR_ROW_EVEN   = 'F7FAFC';

        // ── BARIS 1: Judul + Merge Mobil/Motor ─────────────────────────
        // Struktur kolom:
        // A=No, B=NPK, C=Nama, D=Cabang
        // E-K  = Mobil Level 1-6 + Khusus (7 kolom)
        // L-R  = Motor Level 1-6 + Khusus (7 kolom)
        // S    = Total
        // Note: Level 0-6 = 7 level + Level 7 (Khusus) = 8 kolom per kategori

        $col_start_mobil = 5;  // E
        $col_end_mobil   = 11; // K

        $col_start_motor = 12; // L
        $col_end_motor   = 18; // R

        $col_total       = 19; // S

        $col_mobil_start_letter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col_start_mobil);
        $col_mobil_end_letter   = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col_end_mobil);
        $col_motor_start_letter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col_start_motor);
        $col_motor_end_letter   = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col_end_motor);
        $col_total_letter       = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col_total);

        // Baris 1 — judul periode
        $sheet->mergeCells('A1:' . $col_total_letter . '1');
        $sheet->setCellValue('A1', 'REKAP INSENTIF MEKANIK' . ($bulan ? ' — ' . $bulan : ''));
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 13, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['rgb' => $COLOR_HEADER]],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ]);

        // Baris 2 — header grup Mobil / Motor / Total
        // A-D kosong, merge ke bawah nanti
        $sheet->setCellValue($col_mobil_start_letter . '2', 'Mobil');
        $sheet->mergeCells($col_mobil_start_letter . '2:' . $col_mobil_end_letter . '2');
        $sheet->getStyle($col_mobil_start_letter . '2:' . $col_mobil_end_letter . '2')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['rgb' => $COLOR_MOBIL]],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
        ]);

        $sheet->setCellValue($col_motor_start_letter . '2', 'Motor');
        $sheet->mergeCells($col_motor_start_letter . '2:' . $col_motor_end_letter . '2');
        $sheet->getStyle($col_motor_start_letter . '2:' . $col_motor_end_letter . '2')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['rgb' => $COLOR_MOTOR]],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
        ]);

        // Total header — merge baris 2-3
        $sheet->setCellValue($col_total_letter . '2', 'Total');
        $sheet->mergeCells($col_total_letter . '2:' . $col_total_letter . '3');
        $sheet->getStyle($col_total_letter . '2:' . $col_total_letter . '3')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['rgb' => $COLOR_TOTAL]],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
        ]);

        // No, NPK, Nama, Cabang — merge baris 2-3
        $fixed_headers = ['A' => 'No', 'B' => 'NPK', 'C' => 'Nama', 'D' => 'Cabang'];
        foreach ($fixed_headers as $col => $label) {
            $sheet->setCellValue($col . '2', $label);
            $sheet->mergeCells($col . '2:' . $col . '3');
            $sheet->getStyle($col . '2:' . $col . '3')->applyFromArray([
                'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'startColor' => ['rgb' => $COLOR_HEADER]],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
            ]);
        }

        // ── BARIS 3: sub header level per kategori ─────────────────────
        $level_labels_short = [
            1 => 'Level 1',
            2 => 'Level 2',
            3 => 'Level 3',
            4 => 'Level 4',
            5 => 'Level 5',
            6 => 'Level 6',
            7 => 'Khusus'
        ];

        foreach (['Mobil' => $col_start_mobil, 'Motor' => $col_start_motor] as $kat => $col_start) {

            $bg = $kat === 'Mobil' ? $COLOR_SUB_MOBIL : $COLOR_SUB_MOTOR;
            $fg = $kat === 'Mobil' ? $COLOR_MOBIL : $COLOR_MOTOR;

            foreach ($levels as $i => $lvl) {

                $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
                    $col_start + $i
                );

                $sheet->setCellValue(
                    $colLetter . '3',
                    $level_labels_short[$lvl]
                );

                $sheet->getStyle($colLetter . '3')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => $fg]
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => $bg]
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                        ]
                    ],
                ]);
            }
        }

        // ── DATA ROWS ──────────────────────────────────────────────────
        $r  = 4;
        $no = 1;

        foreach ($pivot as $mid => $p) {
            $bg = $no % 2 === 0 ? $COLOR_ROW_EVEN : $COLOR_ROW_ODD;

            $sheet->setCellValue('A' . $r, $no++);
            $sheet->setCellValue('B' . $r, $p['npk']);
            $sheet->setCellValue('C' . $r, $p['nama']);
            $sheet->setCellValue('D' . $r, $p['nama_cabang']);

            // Mobil
            foreach ($levels as $i => $lvl) {
                $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col_start_mobil + $i);
                $val = $p['Mobil'][$lvl];
                $sheet->setCellValue($colLetter . $r, $val > 0 ? $val : '-');
                if ($val > 0) {
                    $sheet->getStyle($colLetter . $r)->getNumberFormat()->setFormatCode($fmt);
                }
            }

            // Motor
            foreach ($levels as $i => $lvl) {
                $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col_start_motor + $i);
                $val = $p['Motor'][$lvl];
                $sheet->setCellValue($colLetter . $r, $val > 0 ? $val : '-');
                if ($val > 0) {
                    $sheet->getStyle($colLetter . $r)->getNumberFormat()->setFormatCode($fmt);
                }
            }

            // Total
            $sheet->setCellValue($col_total_letter . $r, $p['total']);
            $sheet->getStyle($col_total_letter . $r)->getNumberFormat()->setFormatCode($fmt);
            $sheet->getStyle($col_total_letter . $r)->getFont()->setBold(true);

            // Warna baris
            $sheet->getStyle('A' . $r . ':' . $col_total_letter . $r)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB($bg);

            // Border
            $sheet->getStyle('A' . $r . ':' . $col_total_letter . $r)->applyFromArray([
                'borders' => ['allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color'       => ['rgb' => 'DEE2E6'],
                ]],
            ]);

            $r++;
        }

        // ── BARIS TOTAL ────────────────────────────────────────────────
        $sheet->mergeCells('A' . $r . ':D' . $r);
        $sheet->setCellValue('A' . $r, 'TOTAL');

        $grand_total = 0;
        foreach ($levels as $i => $lvl) {
            // Mobil total
            $col_m = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col_start_mobil + $i);
            $sum_m = 0;
            foreach ($pivot as $p) { $sum_m += $p['Mobil'][$lvl]; }
            $sheet->setCellValue($col_m . $r, $sum_m > 0 ? $sum_m : '-');
            if ($sum_m > 0) $sheet->getStyle($col_m . $r)->getNumberFormat()->setFormatCode($fmt);

            // Motor total
            $col_t = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col_start_motor + $i);
            $sum_t = 0;
            foreach ($pivot as $p) { $sum_t += $p['Motor'][$lvl]; }
            $sheet->setCellValue($col_t . $r, $sum_t > 0 ? $sum_t : '-');
            if ($sum_t > 0) $sheet->getStyle($col_t . $r)->getNumberFormat()->setFormatCode($fmt);

            $grand_total += $sum_m + $sum_t;
        }

        $sheet->setCellValue($col_total_letter . $r, $grand_total);
        $sheet->getStyle($col_total_letter . $r)->getNumberFormat()->setFormatCode($fmt);
        $sheet->getStyle('A' . $r . ':' . $col_total_letter . $r)->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $COLOR_HEADER]],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
        ]);

        // ── AUTO WIDTH ─────────────────────────────────────────────────
        foreach (range('A', 'S') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(18);

        $filename = 'Rekap_Insentif' . ($bulan ? '_' . $bulan : '') . '_' . date('Ymd_His') . '.xlsx';
        if (ob_get_length()) ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        (new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet))->save('php://output');
        exit;
    }

    public function get_rekap_produktivitas()
    {
        $is_nasional    = $this->session->userdata('is_nasional') ? true : false;
        $session_cabang = intval($this->session->userdata('cabang_id'));
        $tgl_dari       = $this->input->post('tgl_dari');
        $tgl_sampai     = $this->input->post('tgl_sampai');
        $filter_cabang  = intval($this->input->post('filter_cabang'));
        $mekanik_id     = intval($this->input->post('mekanik_id'));

        $tgl_dari_db   = !empty($tgl_dari)
            ? implode('-', array_reverse(explode('-', $tgl_dari))) : null;
        $tgl_sampai_db = !empty($tgl_sampai)
            ? implode('-', array_reverse(explode('-', $tgl_sampai))) : null;

        $use_cabang_id = $is_nasional
            ? ($filter_cabang > 0 ? $filter_cabang : null)
            : $session_cabang;

        $q = $this->db->select('
                mk.id as mekanik_id,
                mk.nama,
                mk.npk,
                c.nama_cabang,
                SUM(CASE WHEN im.kategori = \'Mobil\' THEN 1 ELSE 0 END) as jml_mobil,
                SUM(CASE WHEN im.kategori = \'Motor\' THEN 1 ELSE 0 END) as jml_motor,
                COUNT(im.id)                                               as total_service,
                SUM(im.nominal_per_mekanik)                               as total_insentif
            ', FALSE)
            ->from('mac_invoice_mekanik im')
            ->join('mac_mekanik mk',  'mk.id = im.mekanik_id',  'left')
            ->join('mac_cabang c',    'c.id = mk.cabang_id',    'left')
            ->join('mac_invoice i',   'i.id = im.invoice_id',   'left')
            ->where('i.app_status', 'approved')
            ->where('i.is_active', 1);

        if (!is_null($use_cabang_id)) $q->where('mk.cabang_id', $use_cabang_id);
        if ($mekanik_id > 0)          $q->where('im.mekanik_id', $mekanik_id);
        if (!empty($tgl_dari_db))     $q->where('DATE(i.invoice_date) >=', $tgl_dari_db);
        if (!empty($tgl_sampai_db))   $q->where('DATE(i.invoice_date) <=', $tgl_sampai_db);

        $rows = $q->group_by('im.mekanik_id')
                ->order_by('total_service', 'DESC')
                ->get()->result_array();

        // Hitung summary
        $total_mobil    = array_sum(array_column($rows, 'jml_mobil'));
        $total_motor    = array_sum(array_column($rows, 'jml_motor'));
        $total_service  = array_sum(array_column($rows, 'total_service'));
        $total_insentif = array_sum(array_column($rows, 'total_insentif'));

        echo json_encode([
            'status'         => TRUE,
            'rows'           => $rows,
            'total_mobil'    => $total_mobil,
            'total_motor'    => $total_motor,
            'total_service'  => $total_service,
            'total_insentif' => $total_insentif,
        ]);
    }

    public function export_produktivitas_raw()
    {
        require APPPATH . '../vendor/autoload.php';

        $is_nasional    = $this->session->userdata('is_nasional') ? true : false;
        $session_cabang = intval($this->session->userdata('cabang_id'));
        $tgl_dari       = $this->input->get('tgl_dari');
        $tgl_sampai     = $this->input->get('tgl_sampai');
        $filter_cabang  = intval($this->input->get('filter_cabang'));
        $mekanik_id     = intval($this->input->get('mekanik_id'));

        $tgl_dari_db   = !empty($tgl_dari)
            ? implode('-', array_reverse(explode('-', $tgl_dari))) : null;
        $tgl_sampai_db = !empty($tgl_sampai)
            ? implode('-', array_reverse(explode('-', $tgl_sampai))) : null;

        $use_cabang_id = $is_nasional
            ? ($filter_cabang > 0 ? $filter_cabang : null)
            : $session_cabang;

        // Ambil periode label untuk judul
        $periode_label = '';
        if (!empty($tgl_dari_db) && !empty($tgl_sampai_db)) {
            $periode_label = date('Ym', strtotime($tgl_dari_db));
        } elseif (!empty($tgl_dari_db)) {
            $periode_label = date('Ym', strtotime($tgl_dari_db));
        }

        $q = $this->db->select('
                mk.id as mekanik_id,
                mk.nama,
                mk.npk,
                c.nama_cabang,
                SUM(CASE WHEN im.kategori = \'Mobil\' THEN 1 ELSE 0 END) as jml_mobil,
                SUM(CASE WHEN im.kategori = \'Motor\' THEN 1 ELSE 0 END) as jml_motor,
                COUNT(im.id) as total_service
            ', FALSE)
            ->from('mac_invoice_mekanik im')
            ->join('mac_mekanik mk',  'mk.id = im.mekanik_id',  'left')
            ->join('mac_cabang c',    'c.id = mk.cabang_id',    'left')
            ->join('mac_invoice i',   'i.id = im.invoice_id',   'left')
            ->where('i.app_status', 'approved')
            ->where('i.is_active', 1);

        if (!is_null($use_cabang_id)) $q->where('mk.cabang_id', $use_cabang_id);
        if ($mekanik_id > 0)          $q->where('im.mekanik_id', $mekanik_id);
        if (!empty($tgl_dari_db))     $q->where('DATE(i.service_date) >=', $tgl_dari_db);
        if (!empty($tgl_sampai_db))   $q->where('DATE(i.service_date) <=', $tgl_sampai_db);

        $rows = $q->group_by('im.mekanik_id')
                ->order_by('c.nama_cabang', 'ASC')
                ->order_by('mk.nama', 'ASC')
                ->get()->result_array();

        // ── BUILD EXCEL ────────────────────────────────────────────────
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Produktivitas');

        $COLOR_TITLE   = 'FFFFFF'; // kuning
        $COLOR_HEADER  = '242D4A'; // kuning
        $COLOR_SERVICE = '00B0F0'; // biru muda — header "Service Maintenance"
        $COLOR_MOBIL   = '00B0F0';
        $COLOR_MOTOR   = '00B0F0';
        $COLOR_INTERN  = 'BDD7EE'; // biru muda untuk baris internship
        $COLOR_ODD     = 'FFFFFF';
        $COLOR_EVEN    = 'F2F2F2';

        $borderThin = [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color'       => ['rgb' => '000000'],
        ];
        $borderAll = ['allBorders' => $borderThin];

        // ── BARIS 1: Judul ─────────────────────────────────────────────
        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue('A1',
            'Produktivitas Mekanik' . ($periode_label ? ' | Periode ' . $periode_label : ''));
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 13],
            'fill'      => ['fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['rgb' => $COLOR_TITLE]],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
            'borders'   => $borderAll,
        ]);
        $sheet->getRowDimension(1)->setRowHeight(20);

        // ── BARIS 2: Header grup "Service Maintenance" ─────────────────
        // A-D kosong merge ke bawah (baris 2-3)
        // ── BARIS 2: Header grup ────────────────────────────────────────

        // A-D dan G merge ke bawah (baris 2-3)
        $mainHeaders = [
            'A' => 'No.',
            'B' => 'NPK',
            'C' => 'Nama',
            'D' => 'Cabang',
            'G' => 'Total Produktivitas',
        ];

        foreach ($mainHeaders as $col => $label) {
            $sheet->mergeCells($col . '2:' . $col . '3');
            $sheet->setCellValue($col . '2', $label);

            $sheet->getStyle($col . '2:' . $col . '3')->applyFromArray([
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $COLOR_HEADER],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'borders' => $borderAll,
            ]);
        }

        // Service Maintenance merge E2:F2
        $sheet->mergeCells('E2:F2');
        $sheet->setCellValue('E2', 'Service Maintenance');

        $sheet->getStyle('E2:F2')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '000000']
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => $COLOR_SERVICE],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => $borderAll,
        ]);

        // ── BARIS 3: Sub-header Service Maintenance ─────────────────────
        $subHeaders = [
            'E' => 'Mobil',
            'F' => 'Motor',
        ];

        foreach ($subHeaders as $col => $label) {
            $sheet->setCellValue($col . '3', $label);

            $sheet->getStyle($col . '3')->applyFromArray([
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $COLOR_HEADER],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'borders' => $borderAll,
            ]);
        }

        // Apply border ke header grup (A2:D3 dan G2:G3)
        $sheet->getStyle('A2:G3')->applyFromArray(['borders' => $borderAll]);
        $sheet->getRowDimension(2)->setRowHeight(18);
        $sheet->getRowDimension(3)->setRowHeight(18);

        // ── DATA ROWS ──────────────────────────────────────────────────
        $r  = 4;
        $no = 1;

        foreach ($rows as $d) {
            $jml_mobil  = intval($d['jml_mobil']);
            $jml_motor  = intval($d['jml_motor']);
            $total      = $jml_mobil + $jml_motor;
            $npk        = $d['npk'] ?: 'Internship';
            $is_intern  = empty($d['npk']);

            // Warna baris
            $bg = $is_intern ? $COLOR_INTERN : ($no % 2 === 0 ? $COLOR_EVEN : $COLOR_ODD);

            $cabang = $d['nama_cabang']
                ? strtoupper(trim($d['nama_cabang']))
                : '—';

            $sheet->setCellValue('A' . $r, $no++);
            $sheet->setCellValue('B' . $r, $npk);
            $sheet->setCellValue('C' . $r, strtoupper($d['nama']));
            $sheet->setCellValue('D' . $r, $cabang);
            $sheet->setCellValue('E' . $r, $jml_mobil > 0 ? $jml_mobil . ' unit' : '-');
            $sheet->setCellValue('F' . $r, $jml_motor > 0 ? $jml_motor . ' unit' : '-');
            $sheet->setCellValue('G' . $r, $total > 0 ? $total : '-');

            // Warna + border + alignment
            $sheet->getStyle('A' . $r . ':G' . $r)->applyFromArray([
                'fill'      => ['fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'startColor' => ['rgb' => $bg]],
                'borders'   => $borderAll,
                'alignment' => ['vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
            ]);

            // Alignment per kolom
            $sheet->getStyle('A' . $r)->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('E' . $r)->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('F' . $r)->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('G' . $r)->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            // Warna teks NPK internship — biru
            if ($is_intern) {
                $sheet->getStyle('B' . $r)->getFont()
                    ->getColor()->setRGB('0070C0');
            }

            $r++;
        }

        // ── BARIS TOTAL ────────────────────────────────────────────────
        $grand_mobil  = array_sum(array_column($rows, 'jml_mobil'));
        $grand_motor  = array_sum(array_column($rows, 'jml_motor'));
        $grand_total  = $grand_mobil + $grand_motor;

        $sheet->mergeCells('A' . $r . ':D' . $r);
        $sheet->setCellValue('A' . $r, 'TOTAL');
        $sheet->setCellValue('E' . $r, $grand_mobil . ' unit');
        $sheet->setCellValue('F' . $r, $grand_motor . ' unit');
        $sheet->setCellValue('G' . $r, $grand_total);
        $sheet->getStyle('A' . $r . ':G' . $r)->applyFromArray([
            'font'    => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'    => ['fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => $COLOR_HEADER]],
            'borders' => $borderAll,
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        // ── AUTO WIDTH ─────────────────────────────────────────────────
        $sheet->getColumnDimension('A')->setWidth(6);
        $sheet->getColumnDimension('B')->setWidth(14);
        $sheet->getColumnDimension('C')->setWidth(35);
        $sheet->getColumnDimension('D')->setWidth(18);
        $sheet->getColumnDimension('E')->setWidth(12);
        $sheet->getColumnDimension('F')->setWidth(12);
        $sheet->getColumnDimension('G')->setWidth(18);

        $filename = 'Produktivitas_Mekanik' .
            ($periode_label ? '_' . $periode_label : '') .
            '_' . date('Ymd_His') . '.xlsx';

        if (ob_get_length()) ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        (new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet))->save('php://output');
        exit;
    }
}