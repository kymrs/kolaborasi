<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Notifikasi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_notifikasi');
        $this->M_login->getsecurity();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function get_pending_notifications()
    {
        $list_table = $this->db->select('nama_tbl, id_menu')->from('tbl_submenu')->get()->result();

        // Inisialisasi array kosong untuk menampung hasil
        $tables = [
            'tbl' => [],
        ];

        // Proses setiap elemen dari hasil query
        foreach ($list_table as $row) {
            // Tambahkan setiap nama tabel ke dalam array
            $tables['tbl'][] = $row->nama_tbl;
            // $tables['tbl']['id_menu'] = $row->id_menu;
        }

        // Hasil akhir sebagai associative array
        $finalResult = [];

        // Iterasi setiap tabel dalam $tables
        foreach ($tables as $category => $tableList) {
            foreach ($tableList as $selectedTable) {
                if ($selectedTable != null) {
                    // Ambil daftar kolom dari tabel yang dipilih
                    $query = $this->db->query("SHOW COLUMNS FROM $selectedTable");

                    if ($query->num_rows() > 0) {
                        $columns = array_column($query->result_array(), 'Field'); // Daftar kolom dari tabel
                    } else {
                        $columns = []; // Jika tabel tidak memiliki kolom
                    }

                    // Filter $statuses hanya dengan kolom yang ada
                    $statuses = [
                        ['field' => 'app_name', 'status' => 'app_status'],
                        ['field' => 'app2_name', 'status' => 'app2_status', 'next_status' => 'waiting'],
                        ['field' => 'app4_name', 'status' => 'app4_status', 'next_status' => 'waiting'],
                    ];

                    $filteredStatuses = array_filter($statuses, function ($status) use ($columns) {
                        return in_array($status['field'], $columns); // Hanya gunakan field yang ada di tabel
                    });

                    // Tambahkan hasil ke $finalResult berdasarkan nama tabel
                    foreach ($filteredStatuses as $status) {
                        unset($status['table']); // Pastikan key 'table' tidak ditambahkan di dalam hasil akhir
                        $finalResult[$selectedTable][] = $status; // Kelompokkan berdasarkan nama tabel
                    }
                }
            }
        }

        $notifications = $this->M_notifikasi->pending_notification($finalResult);
        echo json_encode($notifications);
    }
}
