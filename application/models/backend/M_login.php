<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_Login extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function cek_login($username, $password)
    {
        //menggunakan active record . untuk menghindari sql injection
        $this->db->where("username", $username);
        $this->db->where("password", $password);
        return $this->db->get("tbl_user");
    }

    function cek_pass($username)
    {
        return $this->db->get_where('tbl_user', array('username' => $username))->row();
    }

    function cek_username($username)
    {
        return $this->db->get_where('tbl_user', array('username' => $username))->row();
    }

    public function save($data)
    {
        $this->db->insert('tbl_user', $data);
        return $this->db->insert_id();
    }

    function getsecurity()
    {
        if (!$this->session->userdata('log') == 'Masuk') {
            redirect(base_url('auth'));
        }
    }
}
