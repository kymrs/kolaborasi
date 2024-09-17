<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_App extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function hak_akses($level, $menu)
    {
        $this->db->select('id_level,nama_submenu,view_level,add_level,edit_level,delete_level,print_level,upload_level,substring_index(link,"/",-1) as cek');
        $this->db->from('tbl_akses_submenu a');
        $this->db->join('tbl_submenu b', 'a.id_submenu=b.id_submenu', 'left');
        $this->db->where("id_level", $level);
        $this->db->where("substring_index(link,'/',-1)", $menu);
        return $this->db->get()->row();
    }
}
