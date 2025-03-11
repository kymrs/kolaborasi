<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_qbg_transaksi extends CI_Model
{
    var $id = 'id';
    var $table = 'qbg_transaksi'; //nama tabel dari database
    var $column_order = array(null, null, 'kode_produk', 'berat', 'jenis_transaksi', 'jumlah', 'keterangan', 'created_at');
    var $column_search = array('kode_produk', 'berat', 'jenis_transaksi', 'jumlah', 'keterangan', 'created_at'); //field yang diizin untuk pencarian 
    // var $order = array('id' => 'asc'); // default order 

    public function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query($kode_produk = 'all', $jenis_transaksi = 'all', $keterangan = 'all', $tgl_awal = 0, $tgl_akhir = 0)
    {

        $this->db->select('a.id, a.kode_produk, b.nama_produk, b.berat, a.jenis_transaksi, a.jumlah, a.keterangan, a.created_at');

        // filter berdasarkan kode produk
        if ($kode_produk != 'all') {
            $this->db->where('a.kode_produk', $kode_produk);
        }

        // filter berdasarkan jenis transaksi
        if ($jenis_transaksi != 'all') {
            $this->db->where('a.jenis_transaksi', $jenis_transaksi);
        }

        if ($keterangan != 'all') {
            $this->db->like('a.keterangan', $keterangan, 'both');
        }

        if ($tgl_awal != 0) {
            $this->db->where("DATE(a.created_at) >=", $tgl_awal);
        }

        if ($tgl_akhir != 0) {
            $this->db->where("DATE(a.created_at) <=", $tgl_akhir);
        }


        $this->db->from('qbg_transaksi a');
        $this->db->join('qbg_produk b', 'a.kode_produk = b.kode_produk');
        $this->db->order_by('id', 'DESC');

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

    function get_datatables($kode_produk, $jenis_transaksi, $keterangan, $tgl_awal, $tgl_akhir)
    {
        $this->_get_datatables_query($kode_produk, $jenis_transaksi, $keterangan, $tgl_awal, $tgl_akhir);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered($kode_produk, $jenis_transaksi, $keterangan, $tgl_awal, $tgl_akhir)
    {
        $this->_get_datatables_query($kode_produk, $jenis_transaksi, $keterangan, $tgl_awal, $tgl_akhir);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // public function get_by_id($id)
    // {
    //     $this->db->where($this->id, $id);
    //     return $this->db->get($this->table)->row();
    // }

    // public function save($data)
    // {
    //     $this->db->insert($this->table, $data);
    //     return $this->db->insert_id();
    // }

    // public function delete($id)
    // {
    //     $this->db->where($this->id, $id);
    //     $this->db->delete($this->table);
    // }
}
