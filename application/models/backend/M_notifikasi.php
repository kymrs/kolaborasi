<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_notifikasi extends CI_Model
{
    function pending_notification()
    {
        $id = $this->session->userdata('id_user');
        $app = $this->db->select('name')->from('tbl_data_user')->where('id_user', $id)->get()->row('name');
        $data['notif_pending'] = [
            'prepayment_sw' => $this->db->select('id')->from('tbl_prepayment')->where('app_name', $app)->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows(),
            'prepayment_pu' => $this->db->select('id')->from('tbl_prepayment_pu')->where('app_name', $app)->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows(),
            'prepayment_pw' => $this->db->select('id')->from('tbl_prepayment_pw')->where('app_name', $app)->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows(),
            'reimbust_sw' => $this->db->select('id')->from('tbl_reimbust')->where('app_name', $app)->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows(),
            'reimbust_pu' => $this->db->select('id')->from('tbl_reimbust_pu')->where('app_name', $app)->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows(),
            'reimbust_pw' => $this->db->select('id')->from('tbl_reimbust_pw')->where('app_name', $app)->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows(),
            'datadeklarasi_sw' => $this->db->select('id')->from('tbl_deklarasi')->where('app_name', $app)->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows(),
            'datadeklarasi_pu' => $this->db->select('id')->from('tbl_deklarasi_pu')->where('app_name', $app)->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows(),
            'datadeklarasi_pw' => $this->db->select('id')->from('tbl_deklarasi_pw')->where('app_name', $app)->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows(),
            'datanotifikasi_sw' => $this->db->select('id')->from('tbl_notifikasi')->where('app_hc_name', $app)->where('app_hc_status', 'waiting')->or_where('app2_name', $app)->where('app_hc_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows(),
            'datanotifikasi_pu' => $this->db->select('id')->from('tbl_notifikasi_pu')->where('app_hc_name', $app)->where('app_hc_status', 'waiting')->or_where('app2_name', $app)->where('app_hc_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows(),
            'datanotifikasi_pw' => $this->db->select('id')->from('tbl_notifikasi_pw')->where('app_hc_name', $app)->where('app_hc_status', 'waiting')->or_where('app2_name', $app)->where('app_hc_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows(),
            'approval_sw' => '',
            'approval' => '',
            'penawaran_la_pu' => '',
            'penawaran_pu' => '',
            'rekapitulasi_pu' => '',
            'rekapitulasi_sw' => '',
            'tanda_terima' => '',
            'customer_pu' => '',
            'menu' => '',
            'submenu' => '',
            'user' => '',
            'userlevel' => ''
        ];
        return $data;
    }
}
