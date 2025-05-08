<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_pu_data_link extends CI_Model
{
    // URL file JSON yang digunakan
    private $file_path_crew = 'https://link.pengenumroh.com/data-crew.json';
    private $file_path_member = 'https://link.pengenumroh.com/data-member.json';

    public function __construct()
    {
        parent::__construct();
    }

    // Ambil data dari JSON dengan cURL (lebih aman)
    private function get_data_from_json($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Timeout 10 detik
        $json_data = curl_exec($ch);
        curl_close($ch);

        return $json_data ? json_decode($json_data, true) : [];
    }

    // Mendapatkan data crew dengan paging
    public function get_datatables()
    {
        $data = $this->get_data_from_json($this->file_path_crew);
        $search = $_POST['search']['value'] ?? ''; // Ambil keyword dari DataTables

        if (!empty($search)) {
            // Filter data berdasarkan pencarian
            $filtered = array_filter($data, function ($item) use ($search) {
                // Contoh filter berdasarkan nama atau noHP
                return stripos($item['nama'], $search) !== false ||
                    stripos($item['noHP'], $search) !== false;
            });
        } else {
            $filtered = $data;
        }

        // Paging
        $start = intval($_POST['start'] ?? 0);
        $length = intval($_POST['length'] ?? 10);

        return array_slice($filtered, $start, $length);
    }


    // Total semua data crew
    public function count_all()
    {
        return count($this->get_data_from_json($this->file_path_crew));
    }

    public function count_filtered()
    {
        $data = $this->get_data_from_json($this->file_path_crew);
        $search = $_POST['search']['value'] ?? '';

        if (!empty($search)) {
            $filtered = array_filter($data, function ($item) use ($search) {
                return stripos($item['nama'], $search) !== false ||
                    stripos($item['noHP'], $search) !== false;
            });
            return count($filtered);
        }

        return count($data);
    }

    public function get_datatables2()
    {
        $data = $this->get_data_from_json($this->file_path_member);
        $search = $_POST['search']['value'] ?? '';

        if (!empty($search)) {
            $filtered = array_filter($data, function ($item) use ($search) {
                return stripos($item['namaMember'], $search) !== false;
            });
        } else {
            $filtered = $data;
        }

        $start = intval($_POST['start'] ?? 0);
        $length = intval($_POST['length'] ?? 10);

        return array_slice($filtered, $start, $length);
    }

    // Total semua data member
    public function count_all2()
    {
        return count($this->get_data_from_json($this->file_path_member));
    }

    // Total data member setelah filtering
    public function count_filtered2()
    {
        $data = $this->get_data_from_json($this->file_path_member);
        $search = $_POST['search']['value'] ?? '';

        if (!empty($search)) {
            $filtered = array_filter($data, function ($item) use ($search) {
                return stripos($item['namaMember'], $search) !== false;
            });
            return count($filtered);
        }

        return count($data);
    }
}
