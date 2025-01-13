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
        $prepayment_sw = $this->db->table_exists('tbl_prepayment') ? $this->db->select('id')->from('tbl_prepayment')->where('app4_name', $app)->where('app4_status', 'waiting')->or_where('app_name', $app)->where('app4_status', 'approved')->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $pu_prepayment = $this->db->table_exists('pu_prepayment') ? $this->db->select('id')->from('pu_prepayment')->where('app_name', $app)->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $swi_prepayment = $this->db->table_exists('swi_prepayment') ? $this->db->select('id')->from('swi_prepayment')->where('app_name', $app)->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $prepayment_kps = $this->db->table_exists('kps_prepayment') ? $this->db->select('id')->from('kps_prepayment')->where('app_name', $app)->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $prepayment_qbg = $this->db->table_exists('qbg_prepayment') ? $this->db->select('id')->from('qbg_prepayment')->where('app_name', $app)->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $prepayment_bmn = $this->db->table_exists('bmn_prepayment') ? $this->db->select('id')->from('bmn_prepayment')->where('app_name', $app)->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $prepayment_mac = $this->db->table_exists('mac_prepayment') ? $this->db->select('id')->from('mac_prepayment')->where('app_name', $app)->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;

        // REIMBUST
        $reimbust_sw = $this->db->table_exists('tbl_reimbust') ? $this->db->select('id')->from('tbl_reimbust')->where('app4_name', $app)->where('app4_status', 'waiting')->or_where('app_name', $app)->where('app4_status', 'approved')->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $pu_reimbust = $this->db->table_exists('pu_reimbust') ? $this->db->select('id')->from('pu_reimbust')->where('app_name', $app)->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $swi_reimbust = $this->db->table_exists('swi_reimbust') ? $this->db->select('id')->from('swi_reimbust')->where('app_name', $app)->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $reimbust_kps = $this->db->table_exists('kps_reimbust') ? $this->db->select('id')->from('kps_reimbust')->where('app_name', $app)->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $reimbust_bmn = $this->db->table_exists('bmn_reimbust') ? $this->db->select('id')->from('bmn_reimbust')->where('app_name', $app)->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $reimbust_qbg = $this->db->table_exists('qbg_reimbust') ? $this->db->select('id')->from('qbg_reimbust')->where('app_name', $app)->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $reimbust_mac = $this->db->table_exists('mac_reimbust') ? $this->db->select('id')->from('mac_reimbust')->where('app_name', $app)->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;

        // DEKLARASI
        $deklarasi_sw = $this->db->table_exists('tbl_datadeklarasi') ? $this->db->select('id')->from('tbl_deklarasi')->where('app4_name', $app)->where('app4_status', 'waiting')->or_where('app_name', $app)->where('app4_status', 'approved')->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $pu_deklarasi = $this->db->table_exists('pu_deklarasi') ? $this->db->select('id')->from('pu_deklarasi')->where('app_name', $app)->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $swi_datadeklarasi = $this->db->table_exists('swi_deklarasi') ? $this->db->select('id')->from('swi_deklarasi')->where('app_name', $app)->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $deklarasi_kps = $this->db->table_exists('kps_deklarasi') ? $this->db->select('id')->from('kps_deklarasi')->where('app_name', $app)->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $deklarasi_bmn = $this->db->table_exists('bmn_deklarasi') ? $this->db->select('id')->from('bmn_deklarasi')->where('app_name', $app)->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $deklarasi_qbg = $this->db->table_exists('tbl_datadeklarasi_qbg') ? $this->db->select('id')->from('tbl_deklarasi_qbg')->where('app_name', $app)->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $deklarasi_mac = $this->db->table_exists('mac_deklarasi') ? $this->db->select('id')->from('mac_deklarasi')->where('app_name', $app)->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;

        // NOTIFIKASI
        $notifikasi_sw = $this->db->table_exists('tbl_datanotifikasi') ? $this->db->select('id')->from('tbl_notifikasi')->where('app_hc_name', $app)->where('app_hc_status', 'waiting')->or_where('app2_name', $app)->where('app_hc_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $notifikasi_pu = $this->db->table_exists('tbl_datanotifikasi_pu') ? $this->db->select('id')->from('tbl_notifikasi_pu')->where('app_hc_name', $app)->where('app_hc_status', 'waiting')->or_where('app2_name', $app)->where('app_hc_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $notifikasi_pw = $this->db->table_exists('tbl_datanotifikasi_pw') ? $this->db->select('id')->from('tbl_notifikasi_pw')->where('app_hc_name', $app)->where('app_hc_status', 'waiting')->or_where('app2_name', $app)->where('app_hc_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $notifikasi_kps = $this->db->table_exists('tbl_datanotifikasi_kps') ? $this->db->select('id')->from('tbl_notifikasi_kps')->where('app_hc_name', $app)->where('app_hc_status', 'waiting')->or_where('app2_name', $app)->where('app_hc_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $notifikasi_bmn = $this->db->table_exists('tbl_datanotifikasi_bmn') ? $this->db->select('id')->from('tbl_notifikasi_bmn')->where('app_hc_name', $app)->where('app_hc_status', 'waiting')->or_where('app2_name', $app)->where('app_hc_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $notifikasi_qbg = $this->db->table_exists('qbg_notifikasi') ? $this->db->select('id')->from('qbg_notifikasi')->where('app_hc_name', $app)->where('app_hc_status', 'waiting')->or_where('app2_name', $app)->where('app_hc_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;

        $data['notif_pending'] = [
            'prepayment_sw' => $prepayment_sw,
            'pu_prepayment' => $pu_prepayment,
            'swi_prepayment' => $swi_prepayment,
            'prepayment_kps' => $prepayment_kps,
            'prepayment_bmn' => $prepayment_bmn,
            'prepayment_qbg' => $prepayment_qbg,
            'prepayment_mac' => $prepayment_mac,
            'reimbust_sw' => $reimbust_sw,
            'pu_reimbust' => $pu_reimbust,
            'swi_reimbust' => $swi_reimbust,
            'reimbust_kps' => $reimbust_kps,
            'reimbust_bmn' => $reimbust_bmn,
            'reimbust_qbg' => $reimbust_qbg,
            'reimbust_mac' => $reimbust_mac,
            'datadeklarasi_sw' => $deklarasi_sw,
            'datadeklarasi_pu' => $pu_deklarasi,
            'swi_datadeklarasi' => $swi_datadeklarasi,
            'datadeklarasi_kps' => $deklarasi_kps,
            'datadeklarasi_bmn' => $deklarasi_bmn,
            'datadeklarasi_qbg' => $deklarasi_qbg,
            'datadeklarasi_mac' => $deklarasi_mac,
            'datanotifikasi_sw' => $notifikasi_sw,
            'datanotifikasi_pu' => $notifikasi_pu,
            'datanotifikasi_pw' => $notifikasi_pw,
            'datanotifikasi_bmn' => $notifikasi_bmn,
            'datanotifikasi_kps' => $notifikasi_kps,
            'datanotifikasi_qbg' => $notifikasi_qbg,
        ];
        return $data;
    }
}
