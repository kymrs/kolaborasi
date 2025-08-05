<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_kps_karyawan extends CI_Model
{
    var $id = 'id';
    var $table = 'kps_karyawan'; //nama tabel dari database
    var $column_order = array(null, null, 'npk', 'nama_lengkap', 'jenis_kelamin', 'tempat_lahir', 'tgl_lahir', 'umur', 'created_at');
    var $column_search = array('npk', 'nama_lengkap', 'jenis_kelamin', 'tempat_lahir', 'tgl_lahir', 'umur', 'created_at'); //field yang diizin untuk pencarian
    var $order = array('id' => 'desc'); // default order

    var $table2 = 'kps_kontrak_pkwt';
    var $column_order2 = array(null, null, 'a.npk', 'b.nama_lengkap', 'b.jenis_kelamin', 'b.tgl_lahir', 'a.jk_awal', 'a.jk_akhir', 'a.created_at');
    var $column_search2 = array('a.npk', 'b.nama_lengkap', 'b.jenis_kelamin', 'b.tgl_lahir', 'a.jk_awal', 'a.jk_akhir', 'a.created_at'); //field yang diizin untuk pencarian

    public function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query()
    {

        $this->db->from($this->table);

        $i = 0;

        foreach ($this->column_search as $item) // looping awal
        {
            if ($_POST['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
            {

                foreach ($this->column_search as $item) // looping awal
                {
                    if ($_POST['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
                    {

                        if ($i === 0) // looping awal
                        {
                            $this->db->group_start();
                            $this->db->like($item, $_POST['search']['value']);
                        } else {
                            $this->db->or_like($item, $_POST['search']['value']);
                        }

                        if (count($this->column_search) - 1 == $i)
                            $this->db->group_end();
                    }
                    $i++;
                }

                if (count($this->column_search) - 1 == $i)
                    $this->db->group_end();
            }
            $i++;
        }

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

        $i = 0;

        foreach ($this->column_search2 as $item) // looping awal
        {
            if ($_POST['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
            {

                foreach ($this->column_search2 as $item) // looping awal
                {
                    if ($_POST['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
                    {

                        if ($i === 0) // looping awal
                        {
                            $this->db->group_start();
                            $this->db->like($item, $_POST['search']['value']);
                        } else {
                            $this->db->or_like($item, $_POST['search']['value']);
                        }

                        if (count($this->column_search2) - 1 == $i)
                            $this->db->group_end();
                    }
                    $i++;
                }

                if (count($this->column_search2) - 1 == $i)
                    $this->db->group_end();
            }
            $i++;
        }

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
        $this->db->from($this->table2);
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
        return $this->db->delete('kps_kontrak_pkwt');
    }

    public function delete_keluarga_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('kps_keluarga_karyawan');
    }
}
