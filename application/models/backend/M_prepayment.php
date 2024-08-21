<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_prepayment extends CI_Model
{
    // INISIASI VARIABLE
    var $id = 'id';
    var $table = 'tbl_prepayment';
    var $table2 = 'tbl_prepayment_detail';
    var $column_order = array(null, null, 'kode_prepayment', 'nama', 'jabatan', 'tgl_prepayment', 'prepayment', 'tujuan', 'status');
    var $column_search = array('kode_prepayment', 'nama', 'jabatan', 'tgl_prepayment', 'prepayment', 'tujuan', 'status'); //field yang diizin untuk pencarian
    var $order = array('id' => 'desc');

    // UNTUK QUERY DATA TABLE
    private function _get_datatables_query()
    {

        $this->db->from($this->table);

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

    // UNTUK MENAMPILKAN HASIL QUERY KE DATA TABLES
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

    // GET BY ID TABLE PREPAYMENT MASTER
    public function get_by_id($id)
    {
        $this->db->where($this->id, $id);
        return $this->db->get($this->table)->row();
    }

    // GET BY ID TABLE DETAIL PREPAYMENT TRANSAKSI
    public function get_by_id_detail($id)
    {
        $this->db->where('prepayment_id', $id);
        return $this->db->get($this->table2)->result_array();
    }

    // UNTUK QUERY MENGAMBIL KODE UNTUK DIGENERATE DI CONTROLLER
    public function max_kode($date)
    {
        $formatted_date = date('ym', strtotime($date));
        $this->db->select('kode_prepayment');
        $where = 'id=(SELECT max(id) FROM tbl_prepayment where SUBSTRING(kode_prepayment, 2, 4) = ' . $formatted_date . ')';
        $this->db->where($where);
        $query = $this->db->get('tbl_prepayment');
        return $query;
    }

    // UNTUK QUERY INSERT DATA PREPAYMENT
    public function save($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    // UNTUK QUERY INSERT DATA PREPAYMENT_DETAIL
    public function save_detail($data)
    {
        $this->db->insert_batch($this->table2, $data);
        return $this->db->insert_id();
    }

    // UNTUK QUERY DELETE DATA PREPAYMENT
    public function delete($id)
    {
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
    }

    // UNTUK QUERY DELETE DATA PREPAYMENT DETAIL BERBARENGAN DENGAN PREPAYMENT MASTER
    public function delete_detail($id)
    {
        $this->db->where('prepayment_id', $id);
        $this->db->delete($this->table2);
    }

    // UNTUK QUERY APPROVE DATA PREPAYMENT

    public function approve2($data)
    {
        // var_dump($data);
        $app = array(
            'app2_name' => $data['app2_name'],
            'app2_status' => $data['app2_status'],
            'app2_keterangan' => $data['app2_keterangan'],
            'app2_date' => date('Y-m-d H:i:s')
        );

        $this->db->where('id', $data['hidden_id']);
        $this->db->update($this->table, $app);
    }
}
