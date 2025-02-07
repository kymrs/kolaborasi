<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_mac_mom extends CI_Model
{
    var $id = 'id';
    var $table = 'mac_mom'; //nama tabel dari database
    var $column_order = array(null, null, 'no_dok', 'agenda', 'date', 'start_time', 'end_time', 'lokasi', 'peserta');
    var $column_search = array('no_dok', 'agenda', 'date', 'start_time', 'end_time', 'lokasi', 'peserta'); //field yang diizin untuk pencarian 
    var $order = array('id' => 'desc'); // default order 

    public function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query()
    {

        $this->db->from($this->table);
        if ($this->session->userdata('core') != "all") {
            $this->db->where('user', $this->session->userdata('fullname'));
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
        if ($this->session->userdata('core') != "all") {
            $this->db->where('user', $this->session->userdata('fullname'));
        }
        return $this->db->count_all_results();
    }

    public function get_by_id($id)
    {
        $this->db->where($this->id, $id);
        return $this->db->get($this->table)->row();
    }

    public function max_kode()
    {
        $this->db->select('no_dok');
        $where = 'id=(SELECT max(id) FROM mac_mom where SUBSTRING(no_dok, 4, 4) = ' . date('ym') . ')';
        $this->db->where($where);
        $query = $this->db->get('mac_mom');
        return $query;
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
}
