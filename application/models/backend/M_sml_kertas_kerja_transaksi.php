<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_sml_kertas_kerja_transaksi extends CI_Model
{
    var $id = 'id';
    var $table = 'sml_kertas_kerja';

    // Datatables
    // Column order disederhanakan (UI transaksi tidak menampilkan No Dok)
    var $column_order = array(null, null, 'periode', 'origin', 'destinasi', null, null, null, null);
    var $column_search = array();
    var $order = array('id' => 'desc');

    private $cached_fields = null;

    public function __construct()
    {
        parent::__construct();
        $this->build_search_columns();
    }

    private function table_fields()
    {
        if ($this->cached_fields === null) {
            $this->cached_fields = (array) $this->db->list_fields($this->table);
        }
        return $this->cached_fields;
    }

    private function has_field($field)
    {
        return in_array($field, $this->table_fields(), true);
    }

    private function build_search_columns()
    {
        $candidates = array('no_dok', 'periode', 'tanggal', 'konsumen', 'customer', 'project', 'origin', 'destinasi', 'nopol', 'driver1', 'no_invoice');
        $cols = array();
        foreach ($candidates as $c) {
            if ($this->has_field($c)) {
                $cols[] = $c;
            }
        }
        $this->column_search = $cols;
    }

    private function _get_datatables_query()
    {
        $this->db->from($this->table);

        $i = 0;
        foreach ($this->column_search as $item) {
            if (isset($_POST['search']['value']) && $_POST['search']['value']) {
                if ($i === 0) {
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
            $colIdx = (int) $_POST['order']['0']['column'];
            $dir = $_POST['order']['0']['dir'];
            if (isset($this->column_order[$colIdx]) && $this->column_order[$colIdx] !== null) {
                $this->db->order_by($this->column_order[$colIdx], $dir);
            }
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function get_datatables()
    {
        $this->_get_datatables_query();
        if (isset($_POST['length']) && $_POST['length'] != -1)
            $this->db->limit($_POST['length'], isset($_POST['start']) ? $_POST['start'] : 0);
        $query = $this->db->get();
        return $query->result();
    }

    public function count_filtered()
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
}
