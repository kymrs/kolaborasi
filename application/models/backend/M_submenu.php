<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_submenu extends CI_Model
{

    var $table = 'tbl_submenu'; //nama tabel dari database
    // Column order/search must match the DataTables columns in view (including aliases)
    var $column_order = array(null, null, 'a.nama_submenu', 'a.link', 'a.icon', 'b.nama_menu', 'a.is_active', 'a.urutan');
    var $column_search = array('a.nama_submenu', 'a.link', 'a.icon', 'b.nama_menu', 'a.is_active', 'a.urutan'); //field yang diizin untuk pencarian 
    var $order = array('a.id_submenu' => 'asc'); // default order 

    public function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query()
    {
        $this->db->select('a.*, b.nama_menu');
        $this->db->from($this->table . ' a');
        $this->db->join('tbl_menu b', 'a.id_menu = b.id_menu', 'left');

        // Optional filter from UI buttons (filter by menu id)
        if (isset($_POST['filter_menu_id']) && $_POST['filter_menu_id'] !== '') {
            $this->db->where('a.id_menu', $_POST['filter_menu_id']);
        }

        $i = 0;

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

        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
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

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function delete_id($id)
    {
        $this->db->where('id_submenu', $id);
        $this->db->delete($this->table);
    }

    public function get_by_id($id)
    {
        $this->db->from($this->table);
        $this->db->where('id_submenu', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function save($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function get_max()
    {
        $this->db->select('urutan');
        $where = 'urutan=(SELECT max(urutan) FROM tbl_submenu)';
        $this->db->where($where);
        $query = $this->db->get($this->table);
        return $query->row();
    }
}
