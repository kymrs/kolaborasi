<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_customer_pu extends CI_Model
{
    // INISIASI VARIABLE
    var $id = 'id';
    var $table = 'tbl_customer_pu';
    var $column_order = array(null, null, 'group_id', 'customer_id', 'nama', 'no_hp', 'tgl_berangkat', 'travel');
    var $column_search = array('group_id', 'customer_id', 'nama', 'no_hp', 'tgl_berangkat', 'travel'); //field yang diizin untuk pencarian
    var $order = array('id' => 'desc');

    // UNTUK QUERY DATA TABLE
    function _get_datatables_query()
    {
        $this->db->from($this->table);
        $this->db->order_by('group_id', 'DESC');

        $i = 0;

        foreach ($this->column_search as $item) // looping awal
        {
            if ($_POST['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
            {

                if ($i === 0) // looping awal
                {
                    $this->db->group_start();
                    $this->db->like('tbl_customer_pu.' . $item, $_POST['search']['value']);
                } else {
                    $this->db->or_like('tbl_customer_pu.' . $item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) {
                    $this->db->group_end();
                }
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

    public function get_by_id($id)
    {
        $this->db->where($this->id, $id);
        return $this->db->get($this->table)->row();
    }

    public function max_kode()
    {
        // Ambil customer_id terbesar dari tabel
        $this->db->select('customer_id');
        $this->db->order_by('customer_id', 'DESC'); // Urutkan dari terbesar
        $this->db->limit(1); // Ambil 1 baris hasil teratas
        $query = $this->db->get('tbl_customer_pu');

        if ($query->num_rows() > 0) {
            $row = $query->row();
            // Ambil nomor urut dari customer_id (misal: C003 -> 003)
            $last_kode = substr($row->customer_id, 1);
            // Tambahkan 1 ke nomor urut terakhir
            $new_kode = sprintf("%03d", $last_kode + 1);
        } else {
            // Jika belum ada customer_id, mulai dari C001
            $new_kode = '001';
        }

        // Gabungkan 'C' dengan nomor urut
        return 'C' . $new_kode;
    }

    // Fungsi untuk mengambil group_id terakhir dari database
    public function get_last_group_id()
    {
        $this->db->select('group_id');
        $this->db->order_by('group_id', 'DESC'); // Urutkan dari yang terakhir
        $this->db->limit(1); // Ambil satu saja
        $query = $this->db->get('tbl_customer_pu');

        if ($query->num_rows() > 0) {
            return $query->row()->group_id; // Kembalikan group_id terakhir
        }

        return null; // Jika tidak ada, kembalikan null
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

    public function get_data_customer()
    {
        $this->db->select('group_id, customer_id, nama, no_hp, tgl_berangkat, travel');
        $this->db->from('tbl_customer_pu');
        return $this->db->get()->result();
    }
}
