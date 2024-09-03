<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_datanotifikasi extends CI_Model
{
    var $id = 'id';
    var $table = 'tbl_notifikasi'; //nama tabel dari database
    var $column_order = array(null, null, 'kode_notifikasi', 'name', 'jabatan', 'departemen', 'pengajuan', 'tgl_notifikasi', 'waktu', 'alasan', 'status', 'catatan');
    var $column_search = array('kode_notifikasi', 'name', 'jabatan', 'departemen', 'pengajuan', 'tgl_notifikasi', 'waktu', 'alasan', 'status', 'catatan'); //field yang diizin untuk pencarian 
    var $order = array('id' => 'desc'); // default order 

    public function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query()
    {

        // $this->db->from($this->table);
        $this->db->select('tbl_notifikasi.*, tbl_data_user.name');
        $this->db->from('tbl_notifikasi');
        $this->db->join('tbl_data_user', 'tbl_data_user.id_user = tbl_notifikasi.id_user');

        $i = 0;

        foreach ($this->column_search as $item) // looping awal
        {
            if ($_POST['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
            {

                if ($i === 0) // looping awal
                {
                    $this->db->group_start();
                    if ($item == 'name') {
                        $this->db->like('tbl_data_user.' . $item, $_POST['search']['value']);
                    } else {
                        $this->db->like('tbl_notifikasi.' . $item, $_POST['search']['value']);
                    }
                } else {
                    if ($item == 'name') {
                        $this->db->or_like('tbl_data_user.' . $item, $_POST['search']['value']);
                    } else {
                        $this->db->or_like('tbl_notifikasi.' . $item, $_POST['search']['value']);
                    }
                }

                if (count($this->column_search) - 1 == $i)
                    $this->db->group_end();
            }
            $i++;
        }

        // Tambahkan pemfilteran berdasarkan status
        if (!empty($_POST['status'])) {
            $this->db->where('status', $_POST['status']);
        }

        // Tambahkan kondisi WHERE untuk user ID atau nama approval
        if ($this->session->userdata('fullname') != 'super admin') {
            $this->db->group_start()
                ->where('tbl_notifikasi.id_user', $this->session->userdata('id_user'))
                ->or_where('tbl_notifikasi.app_name =', "(SELECT name FROM tbl_data_user WHERE id_user = " . $this->session->userdata('id_user') . ") AND tbl_notifikasi.app_status NOT IN ('rejected', 'approved') AND tbl_notifikasi.status != 'revised'", FALSE)
                ->or_where('tbl_notifikasi.app2_name =', "(SELECT name FROM tbl_data_user WHERE id_user = " . $this->session->userdata('id_user') . ") AND tbl_notifikasi.app_status NOT IN ('rejected', 'waiting', 'revised') AND tbl_notifikasi.app2_status NOT IN ('rejected', 'approved') AND tbl_notifikasi.status != 'revised'", FALSE)
                ->group_end();
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

    public function get_by_id($id)
    {
        $this->db->where($this->id, $id);
        return $this->db->get($this->table)->row();
    }

    public function max_kode($date)
    {
        $formatted_date = date('ym', strtotime($date));
        $this->db->select('kode_notifikasi');
        $where = 'id=(SELECT max(id) FROM tbl_notifikasi where SUBSTRING(kode_notifikasi, 2, 4) = ' . $formatted_date . ')';
        $this->db->where($where);
        $query = $this->db->get('tbl_notifikasi');
        return $query;
    }

    // UNTUK QUERY MENENTUKAN SIAPA YANG MELAKUKAN APPROVAL
    public function approval($id)
    {
        $this->db->select('app_id, app2_id');
        $this->db->from('tbl_data_user');
        $this->db->where('id_user', $id);
        $query = $this->db->get();
        return $query->row();
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
