<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_holding_karyawan extends CI_Model
{
    var $id = 'id';
    var $table = 'holding_karyawan'; //nama tabel dari database
    var $column_order = array(null, null, 'npk', 'nama_lengkap', 'jenis_kelamin', 'tempat_lahir', 'tgl_lahir', 'umur', 'created_at');
    var $column_search = array('npk', 'nama_lengkap', 'jenis_kelamin', 'tempat_lahir', 'tgl_lahir', 'umur', 'created_at'); //field yang diizin untuk pencarian
    var $order = array('id' => 'desc'); // default order

    var $table2 = 'holding_kontrak_pkwt';
    var $column_order2 = array(null, null, 'a.npk', 'b.nama_lengkap', 'b.jenis_kelamin', 'b.tgl_lahir', 'a.jk_awal', 'a.jk_akhir', 'a.created_at');
    var $column_search2 = array('a.npk', 'b.nama_lengkap', 'b.jenis_kelamin', 'b.tgl_lahir', 'a.jk_awal', 'a.jk_akhir', 'a.created_at'); //field yang diizin untuk pencarian
    var $order2 = array('a.id' => 'desc');

    public function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query()
    {
        $this->db->select('a.*');
        $this->db->from($this->table . ' a');

        // Join ke tabel holding_kontrak_pkwt (table2) untuk mengambil nomor perjanjian
        $this->db->join($this->table2 . ' b', 'a.npk = b.npk', 'left');

        // Join ke holding_sub_bisnis berdasarkan kode yang di-parse dari nomor PKWT (b.no_perjanjian)
        $this->db->join(
            'holding_sub_bisnis c',
            "c.kode = SUBSTRING_INDEX(
                SUBSTRING_INDEX(
                    SUBSTRING_INDEX(b.no_perjanjian, '/', 2),
                    '-',
                    -1
                ),
                '/',
                1
            )",
            'left',
            FALSE
        );

        // Filter Hak Akses / User Level (Diperbarui agar mencakup user_id_ttd)
        $allowed_levels = [4, 1, 21];
        if (!in_array($this->session->userdata('id_level'), $allowed_levels)) {
            $this->db->group_start();
                $this->db->where('a.id_user', $this->session->userdata('id_user'));
                $this->db->or_where('c.user_id_ttd', $this->session->userdata('id_user'));
            $this->db->group_end();
        }

        // Filter Unit Bisnis
        if (!empty($_POST['filter_unit_bisnis'])) {
            $this->db->where('LOWER(a.nama_pt)', strtolower($_POST['filter_unit_bisnis']));
        }

        // Datatables Global Search (Disamakan strukturnya dengan query2)
        if (!empty($_POST['search']['value'])) {
            $i = 0;
            foreach ($this->column_search as $item) {
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) {
                    $this->db->group_end();
                }
                $i++;
            }
        }

        // Datatables Ordering
        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    private function _get_datatables_query2()
    {
        $this->db->select('a.*, b.*, a.id as id_pkwt, a.created_at as created_at_pkwt');
        $this->db->from($this->table2 . ' a');
        $this->db->join($this->table . ' b', 'a.npk = b.npk', 'left');

        // Join ke holding_sub_bisnis berdasarkan kode yang di-parse dari nomor PKWT (a.no_perjanjian)
        // Mengambil string antara '-' dan '/' secara dinamis
        // Sesuaikan 'a.no_perjanjian' dengan nama kolom nomor PKWT di tabel holding_kontrak_pkwt
        $this->db->join(
            'holding_sub_bisnis c',
            "c.kode = SUBSTRING_INDEX(
                SUBSTRING_INDEX(
                    SUBSTRING_INDEX(a.no_perjanjian, '/', 2),
                    '-',
                    -1
                ),
                '/',
                1
            )",
            'left',
            FALSE
        );

        // Filter Hak Akses / User Level
        $allowed_levels = [4, 1, 21];
        if (!in_array($this->session->userdata('id_level'), $allowed_levels)) {
            $current_user_id = $this->session->userdata('id_user');

            $this->db->group_start();
                // 1. Jika user adalah Atasan Penandatangan (user_id_ttd), tampilkan semua (termasuk status waiting)
                $this->db->where('c.user_id_ttd', $current_user_id);

                // 2. Jika user adalah Karyawan biasa (b.id_user), HANYA tampilkan jika app_status SUDAH approved
                $this->db->or_group_start();
                    $this->db->where('b.id_user', $current_user_id);
                    $this->db->where('a.app_status', 'approved');
                $this->db->group_end();
            $this->db->group_end();
        }

        // Filter Unit Bisnis
        if (!empty($_POST['filter_unit_bisnis'])) {
            $this->db->where('LOWER(b.nama_pt)', strtolower($_POST['filter_unit_bisnis']));
        }

        // Filter Status Approval
        $status_approve = isset($_POST['status_approve']) ? $_POST['status_approve'] : null;
        if ($status_approve !== null && $status_approve !== '') {
            if ($status_approve == 'on-process') {
                $this->db->where('a.app_status', 'waiting');
            } else {
                $this->db->where('a.app_status', $status_approve);
            }
        }

        // Datatables Global Search
        if (!empty($_POST['search']['value'])) {
            $i = 0;
            foreach ($this->column_search2 as $item) {
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search2) - 1 == $i) {
                    $this->db->group_end();
                }
                $i++;
            }
        }

        // Datatables Ordering
        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order2[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order2)) {
            $order = $this->order2;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function get_datatables2()
    {
        $this->_get_datatables_query2();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_filtered2()
    {
        $this->_get_datatables_query2();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function count_all2()
    {
        $this->db->from($this->table2 . ' a');
        $this->db->join($this->table . ' b', 'a.npk = b.npk', 'left');

        if ($this->session->userdata('id_level') != 4 && $this->session->userdata('id_level') != 1 && $this->session->userdata('id_level') != 21) {
            $this->db->where('b.id_user', $this->session->userdata('id_user'));
        }

        return $this->db->count_all_results();
    }

    public function get_by_id($id)
    {
        $this->db->where($this->id, $id);
        return $this->db->get($this->table)->row();
    }

    public function get_by_id2($id)
    {
        $this->db->where($this->id, $id);
        return $this->db->get($this->table2)->row();
    }

    public function save($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function delete($id)
    {
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
    }

    public function delete_kontrak_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('holding_kontrak_pkwt');
    }

    public function delete_keluarga_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('holding_keluarga_karyawan');
    }

    public function get_expiring($days = 30)
    {
        $today = date('Y-m-d');
        $limitDate = date('Y-m-d', strtotime("+{$days} days"));

        $this->db->select("a.id, a.npk, b.nama_lengkap, DATE(a.jk_akhir) AS tgl_akhir_kontrak", false);
        $this->db->from('holding_kontrak_pkwt a');
        $this->db->join('holding_karyawan b', 'a.npk = b.npk', 'left');

        $where = "DATE(a.jk_akhir) BETWEEN '{$today}' AND '{$limitDate}'";
        $this->db->where($where, null, false);

        $this->db->order_by('a.jk_akhir', 'ASC');
        $q = $this->db->get();
        return $q->result_array();
    }
}
