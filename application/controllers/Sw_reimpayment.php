<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sw_reimpayment extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_notifikasi');
        $this->M_login->getsecurity();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        ($akses->view_level == 'N' ? redirect('auth') : '');
        $data['add'] = $akses->add_level;
        $data['alias'] = $this->session->userdata('username');
        $data['title'] = "backend/sw_reimpayment/sw_reimpayment_list";
        $data['titleview'] = "Data Reimbust & Prepayment";
        $name = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $this->session->userdata('id_user'))
            ->get()
            ->row('name');
        $data['approval'] = $this->db->select('COUNT(*) as total_approval')
            ->from('sw_reimbust')
            ->where('app_name', $name)
            ->or_where('app2_name', $name)
            // ->or_where('app4_name', $name)
            ->get()
            ->row('total_approval');
        $this->load->view('backend/home', $data);
    }
}
