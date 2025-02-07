<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_pu_data_link extends CI_Model
{

    // Nama file JSON yang digunakan
    private $file_path_crew = FCPATH . '../linkgroup/link.pengenumroh/data-crew.json';
    private $file_path_member = FCPATH . '../linkgroup/link.pengenumroh/data-member.json';

    public function __construct()
    {
        parent::__construct();
    }

    // Ambil data dari JSON
    private function get_data_from_json()
    {
        if (file_exists($this->file_path_crew)) {
            $json_data = file_get_contents($this->file_path_crew);
            return json_decode($json_data, true) ?: [];  // Pastikan tidak return null
        } else {
            return [];
        }
    }

    // Mendapatkan data dengan paging
    public function get_datatables()
    {
        $data = $this->get_data_from_json();

        // Ambil parameter paging dari DataTables
        $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
        $length = isset($_POST['length']) ? intval($_POST['length']) : 10;

        // Potong data sesuai pagination
        return array_slice($data, $start, $length);
    }

    // Total semua data (sebelum filtering)
    public function count_all()
    {
        return count($this->get_data_from_json());
    }

    // Total data setelah filtering (sementara ini dianggap sama dengan total)
    public function count_filtered()
    {
        return count($this->get_data_from_json()); // Perlu ditambah filter jika ada pencarian
    }

    // Ambil data dari JSON
    private function get_data_from_json2()
    {
        if (file_exists($this->file_path_member)) {
            $json_data = file_get_contents($this->file_path_member);
            return json_decode($json_data, true) ?: [];  // Pastikan tidak return null
        } else {
            return [];
        }
    }

    // Mendapatkan data dengan paging
    public function get_datatables2()
    {
        $data = $this->get_data_from_json2();

        // Ambil parameter paging dari DataTables
        $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
        $length = isset($_POST['length']) ? intval($_POST['length']) : 10;

        // Potong data sesuai pagination
        return array_slice($data, $start, $length);
    }

    // Total semua data (sebelum filtering)
    public function count_all2()
    {
        return count($this->get_data_from_json2());
    }

    // Total data setelah filtering (sementara ini dianggap sama dengan total)
    public function count_filtered2()
    {
        return count($this->get_data_from_json2()); // Perlu ditambah filter jika ada pencarian
    }
}
