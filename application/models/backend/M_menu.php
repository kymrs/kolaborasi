<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_menu extends CI_Model
{

    var $table = 'tbl_menu'; //nama tabel dari database
    var $column_order = array(null, null, 'nama_menu', 'link', 'icon', 'urutan', 'is_active');
    var $column_search = array('nama_menu', 'link', 'icon', 'urutan', 'is_active'); //field yang diizin untuk pencarian 
    var $order = array('id_menu' => 'asc'); // default order 

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
        // Pastikan ID tidak kosong atau null
        if (empty($id)) {
            echo json_encode(array("status" => FALSE, "message" => "ID tidak valid"));
            return;
        }

        // Gunakan transaksi database untuk memastikan integritas data
        $this->db->trans_start();

        // Hapus dari tabel utama
        $this->db->where('id_menu', $id);
        $this->db->delete($this->table);

        // Hapus dari tabel approval terkait
        $this->db->where('id_menu', $id);
        $this->db->delete('tbl_approval');

        // Selesaikan transaksi
        $this->db->trans_complete();

        // Cek apakah transaksi berhasil
        if ($this->db->trans_status() === FALSE) {
            return json_encode(array("status" => FALSE, "message" => "Gagal menghapus data"));
        } else {
            return json_encode(array("status" => TRUE, "message" => "Data berhasil dihapus"));
        }
    }


    public function get_by_id($id)
    {
        $data['menu'] = $this->db->from($this->table)->where('id_menu', $id)->get()->row();
        $data['approval'] = $this->db->from('tbl_approval')->where('id_menu', $id)->get()->row();
        return $data;
    }

    public function get_max()
    {
        $this->db->select('urutan');
        $where = 'urutan=(SELECT max(urutan) FROM tbl_menu)';
        $this->db->where($where);
        $query = $this->db->get($this->table);
        return $query->row();
    }

    public function save($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function save2($data2)
    {
        $this->db->replace('tbl_approval', $data2);
        // return $this->db->insert_id();
    }
}
