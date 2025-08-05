<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_notifikasiAdmin extends CI_Model
{
    private $column_search = ['tbl_menu.nama_menu', 'tbl_submenu.link']; // Kolom untuk pencarian
    private $order_column = ['tbl_menu.nama_menu', 'tbl_submenu.link', null]; // Kolom untuk ordering

    private function _get_base_data()
    {
        // Ambil tabel-tabel yang terkait notifikasi
        return $this->db
            ->select('tbl_submenu.link, tbl_submenu.nama_submenu, tbl_submenu.nama_tbl, tbl_menu.nama_menu')
            ->from('tbl_submenu')
            ->join('tbl_menu', 'tbl_menu.id_menu = tbl_submenu.id_menu', 'left')
            ->where('tbl_submenu.nama_tbl IS NOT NULL', null, false)
            ->get()
            ->result();
    }

    private function _get_query()
    {
        $result = [];
        $tbl_notifikasi = $this->_get_base_data();

        foreach ($tbl_notifikasi as $row) {
            $tabel_nama = $row->nama_tbl;
            $link = $row->link;
            $nama_submenu = $row->nama_submenu;
            $nama_menu = $row->nama_menu;

            if ($tabel_nama != '') {
                $fields = $this->db->list_fields($tabel_nama);
                $where = [];

                if (in_array('status', $fields)) {
                    $where[] = "status = 'on-process'";
                }

                if (!empty($where)) {
                    $count = $this->db
                        ->select('COUNT(*) AS total')
                        ->from($tabel_nama)
                        ->where(implode(' OR ', $where), null, false)
                        ->get()
                        ->row()
                        ->total;

                    $result[] = [
                        'menu' => $nama_menu,
                        'link' => $link,
                        'submenu' => $nama_submenu,
                        'nama_tbl' => $tabel_nama,
                        'jumlah' => $count
                    ];
                }
            }
        }

        return $result;
    }

    public function get_datatables()
    {
        $username = $this->session->userdata('username');
        if ($username !== 'eko') {
            return [];
        }

        $data = $this->_get_query();

        // Search
        if ($_POST['search']['value']) {
            $search = strtolower($_POST['search']['value']);
            $data = array_filter($data, function ($row) use ($search) {
                return (strpos(strtolower($row['menu']), $search) !== false) ||
                    (strpos(strtolower($row['submenu']), $search) !== false);
            });
        }

        // Order
        if (isset($_POST['order'])) {
            $order_col = $_POST['order'][0]['column'];
            $order_dir = $_POST['order'][0]['dir'];
            $order_key = ['menu', 'submenu', 'jumlah'][$order_col];

            usort($data, function ($a, $b) use ($order_key, $order_dir) {
                if ($order_dir === 'asc') {
                    return strcmp($a[$order_key], $b[$order_key]);
                } else {
                    return strcmp($b[$order_key], $a[$order_key]);
                }
            });
        }

        // Paging
        $start = $_POST['start'];
        $length = $_POST['length'];
        return array_slice($data, $start, $length);
    }

    public function count_filtered()
    {
        $username = $this->session->userdata('username');
        if ($username !== 'eko') {
            return 0;
        }

        $data = $this->_get_query();

        // Filter by search
        if ($_POST['search']['value']) {
            $search = strtolower($_POST['search']['value']);
            $data = array_filter($data, function ($row) use ($search) {
                return (strpos(strtolower($row['menu']), $search) !== false) ||
                    (strpos(strtolower($row['submenu']), $search) !== false);
            });
        }

        return count($data);
    }

    public function count_all()
    {
        $username = $this->session->userdata('username');
        if ($username !== 'eko') {
            return 0;
        }

        return count($this->_get_query());
    }
}
