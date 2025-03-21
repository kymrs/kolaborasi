<?php

class WeekCalculator extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_swi_prepayment');
    }

    public function index()
    {
        $data['title'] = "week";
        $this->load->view('backend/home', $data);
    }

    public function getWeek($year = null, $month = null, $day = null)
    {
        // Set default bulan, tahun, dan hari jika tidak diberikan
        $year = $year ?? date('Y');
        $month = $month ?? date('m');
        $day = $day ?? date('d');

        // Hitung jumlah hari dalam bulan tersebut
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        // Validasi tanggal yang dimasukkan
        if ($day < 1 || $day > $daysInMonth) {
            echo json_encode(["error" => "Tanggal tidak valid untuk bulan ini."]);
            return;
        }

        // Array untuk menyimpan hasil minggu
        $weeks = [];

        $weekNumber = 0;

        // Looping untuk setiap hari dalam bulan tersebut
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $currentDate = sprintf('%04d-%02d-%02d', $year, $month, $i);
            $dayOfWeek = date('w', strtotime($currentDate)); // 0 = Minggu, 1 = Senin, ..., 6 = Sabtu

            // Tentukan minggu
            if ($i == 1) {
                $weekNumber = 1; // Tanggal 1 selalu minggu pertama
            } elseif ($dayOfWeek == 0) {
                $weekNumber++; // Hari Minggu menandai awal minggu baru
            } elseif (!isset($weekNumber)) {
                $weekNumber = 2; // Hari setelah 1 sebelum Minggu masuk ke minggu kedua
            }

            // Simpan hasil
            $weeks[$i] = [
                'date' => $currentDate,
                'week' => $weekNumber
            ];
        }

        // Output hanya untuk tanggal yang dimasukkan
        header('Content-Type: application/json');
        echo json_encode($weeks[$day], JSON_PRETTY_PRINT);
    }
}
