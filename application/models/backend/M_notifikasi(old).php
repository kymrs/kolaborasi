<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_notifikasi extends CI_Model
{
    function pending_notification()
    {
        $id = $this->session->userdata('id_user');
        $app = $this->db->select('name')->from('tbl_data_user')->where('id_user', $id)->get()->row('name');
        // PREPAYMENT
        $tbl_prepayment = $this->db->table_exists('tbl_prepayment') ? $this->db->select('id')->from('tbl_prepayment')->group_start()->where('app_name', $app)->where('app_status', 'waiting')->group_end()->or_group_start()->where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->group_end()->get()->num_rows() : null;

        // REIMBUST
        $tbl_reimbust = $this->db->table_exists('tbl_reimbust') ? $this->db->select('id')->from('tbl_reimbust')->group_start()->where('app_name', $app)->where('app_status', 'waiting')->or_group_start()->where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->group_end()->group_end()->get()->num_rows() : null;

        // DEKLARASI
        $tbl_deklarasi = $this->db->table_exists('tbl_deklarasi') ? $this->db->select('id')->from('tbl_deklarasi')->group_start()->where('app_name', $app)->where('app_status', 'waiting')->or_group_start()->where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->group_end()->group_end()->get()->num_rows() : null;

        // NOTIFIKASI
        $notifikasi_pu = $this->db->table_exists('tbl_datanotifikasi_pu') ? $this->db->select('id')->from('tbl_notifikasi_pu')->where('app_hc_name', $app)->where('app_hc_status', 'waiting')->or_where('app2_name', $app)->where('app_hc_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;

        $data['notif_pending'] = [
            'link_prepayment' => $tbl_prepayment,
            'link_reimbust' => $tbl_reimbust,
            'link_deklarasi' => $tbl_deklarasi,
            'link_notifikasi' => $notifikasi_pu,
        ];

        $data['username'] = $app;

        $data['notif_menu'] = [
            'nama_menu' => $tbl_prepayment + $tbl_reimbust + $tbl_deklarasi,
        ];

        return $data;
    }
}
