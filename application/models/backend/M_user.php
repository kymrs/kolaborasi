<?php

class M_user extends CI_Model
{
    var $table = 'tbl_user'; //nama tabel dari database
    var $column_order = array(null, 'username', 'fullname', 'image', 'id_level', 'is_active', null); //field yang ada di table user
    var $column_search = array('username', 'fullname', 'id_level', 'is_active'); //field yang diizin untuk pencarian 
    var $order = array('id_user' => 'asc'); // default order 

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
        $this->db->trans_start();

        $this->db->where('id_user', $id);
        $this->db->delete($this->table);

        $this->db->where('id_user', $id);
        $this->db->delete('tbl_data_user');

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            // Jika ada kesalahan, rollback
            $this->db->trans_rollback();
            return false;
        } else {
            // Jika berhasil, commit
            return true;
        }
    }

    public function get_by_id($id)
    {
        $this->db->from($this->table);
        $this->db->where('id_user', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function save($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function save2($data2)
    {
        $this->db->insert('tbl_data_user', $data2);
        return $this->db->insert_id();
    }
}
