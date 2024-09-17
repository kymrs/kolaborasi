<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_level extends CI_Model
{

    var $table = 'tbl_userlevel'; //nama tabel dari database
    var $column_order = array(null, 'nama_level', null, null);
    var $column_search = array('id_level', 'nama_level'); //field yang diizin untuk pencarian 
    var $order = array('id_level' => 'asc'); // default order 

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
        $this->db->where('id_level', $id);
        $this->db->delete($this->table);
        //hapus juga aksesnya
        $this->db->where('id_level', $id);
        $this->db->delete('tbl_akses_menu');

        $this->db->where('id_level', $id);
        $this->db->delete('tbl_akses_submenu');
    }

    public function get_by_id($id)
    {
        $this->db->from($this->table);
        $this->db->where('id_level', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function save($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    // tambahan hak akses
    function view_akses_menu($id)
    {
        $this->db->join('tbl_menu b', 'a.id_menu=b.id_menu');
        $this->db->where('a.id_level', $id);
        $this->db->where('b.is_active', 'Y');
        $this->db->order_by('b.urutan', 'ASC');
        return $this->db->get('tbl_akses_menu a');
    }

    function akses_submenu($id)
    {
        return $this->db->query("SELECT g.*, f.id_menu as id_menus FROM tbl_submenu f JOIN (SELECT d.id_menu,c.* FROM (SELECT b.nama_submenu,a.* FROM `tbl_akses_submenu` a JOIN tbl_submenu b ON a.id_submenu=b.id_submenu WHERE b.is_active ='Y' AND a.id_level =$id)c LEFT OUTER JOIN tbl_submenu d ON c.id_submenu = d.id_submenu GROUP BY c.id_submenu)g ON f.id_submenu = g.id_submenu");
    }

    function update_aksesmenu($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('tbl_akses_menu', $data);
    }

    function update_akses_submenu($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('tbl_akses_submenu', $data);
    }

    function getMenu()
    {
        $this->db->select('id_menu');
        return $this->db->get('tbl_menu');
    }

    function get_akses_menu($id)
    {
        $this->db->select('id_menu');
        $this->db->where('id_level', $id);
        return $this->db->get('tbl_akses_menu');
    }

    function get_new_menu($id)
    {
        return $this->db->query("SELECT id_menu FROM `tbl_menu` WHERE is_active='Y' AND id_menu NOT IN (SELECT id_menu FROM `tbl_akses_menu` WHERE id_level =$id)");
    }

    function getsubMenu()
    {
        $this->db->select('id_submenu');
        return $this->db->get('tbl_submenu');
    }

    function get_akses_submenu($id)
    {
        $this->db->select('*');
        $this->db->where('id_level', $id);
        return $this->db->get('tbl_akses_submenu');
    }

    function get_new_submenu($id)
    {
        return $this->db->query("SELECT id_submenu FROM `tbl_submenu` WHERE id_submenu NOT IN (SELECT id_submenu FROM `tbl_akses_submenu` WHERE id_level =$id)");
    }

    function insert_akses_menu($tbl_akses_menu, $data)
    {
        $insert = $this->db->insert($tbl_akses_menu, $data);
        return $insert;
    }

    function insert_akses_submenu($tbl_akses_submenu, $data)
    {
        $insert = $this->db->insert($tbl_akses_submenu, $data);
        return $insert;
    }

    function get_old_akses_menu()
    {
        return $this->db->query("SELECT DISTINCT(id_menu) FROM `tbl_akses_menu` WHERE id_menu NOT IN (SELECT id_menu FROM `tbl_menu` WHERE `is_active` ='Y')");
    }

    function del_old_akses_menu($id)
    {
        //hapus submenu yang ada id akses menu
        $this->db->where('id_menu', $id);
        $this->db->delete('tbl_akses_menu');
    }

    function get_old_akses_submenu()
    {
        return $this->db->query("SELECT DISTINCT(id_submenu) FROM `tbl_akses_submenu` WHERE id_submenu NOT IN (SELECT id_submenu FROM `tbl_submenu` WHERE `is_active` ='Y')");
    }

    function del_old_akses_submenu($id)
    {
        $this->db->where('id_submenu', $id);
        $this->db->delete('tbl_akses_submenu');
    }
}
