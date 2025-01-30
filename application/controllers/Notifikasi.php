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
        $tables = [];

        // Proses setiap elemen dari hasil query
        foreach ($list_table as $row) {
            // Simpan dalam array dengan id_menu
            $tables[$row->nama_tbl] = $row->id_menu;
        }

        // Hasil akhir sebagai associative array
        $finalResult = [];

        // Iterasi setiap tabel dalam $tables
        foreach ($tables as $selectedTable => $id_menu) {
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

                // Tambahkan hasil ke $finalResult berdasarkan nama tabel, termasuk id_menu
                $finalResult[$selectedTable] = [
                    'id_menu' => $id_menu,  // Tambahkan id_menu
                    'statuses' => array_values($filteredStatuses) // Reset indeks array
                ];
            }
        }

        $notifications = $this->M_notifikasi->pending_notification($finalResult);
        echo json_encode($notifications);
    }
}
