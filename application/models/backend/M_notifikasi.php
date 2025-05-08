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
        $prepayment_sw = $this->db->table_exists('tbl_prepayment') ? $this->db->select('id')->from('tbl_prepayment')->where('app4_name', $app)->where('app4_status', 'waiting')->where('app_status', 'waiting')->where('app2_status', 'waiting')->or_where('app_name', $app)->where('app4_status', 'approved')->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app4_status', 'approved')->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $sml_prepayment = $this->db->table_exists('sml_prepayment') ? $this->db->select('id')->from('sml_prepayment')->where('app4_name', $app)->where('app4_status', 'waiting')->where('app_status', 'waiting')->where('app2_status', 'waiting')->or_where('app_name', $app)->where('app4_status', 'approved')->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app4_status', 'approved')->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $pu_prepayment = $this->db->table_exists('tbl_prepayment_pu') ? $this->db->select('id')->from('tbl_prepayment_pu')->where('app_name', $app)->where('app_status', 'waiting')->where('app2_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $swi_prepayment = $this->db->table_exists('swi_prepayment') ? $this->db->select('id')->from('swi_prepayment')->where('app_name', $app)->where('app_status', 'waiting')->where('app2_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $kps_prepayment = $this->db->table_exists('kps_prepayment') ? $this->db->select('id')->from('kps_prepayment')->where('app_name', $app)->where('app_status', 'waiting')->where('app2_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $qbg_prepayment = $this->db->table_exists('qbg_prepayment') ? $this->db->select('id')->from('qbg_prepayment')->where('app_name', $app)->where('app_status', 'waiting')->where('app2_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $bmn_prepayment = $this->db->table_exists('bmn_prepayment') ? $this->db->select('id')->from('bmn_prepayment')->where('app_name', $app)->where('app_status', 'waiting')->where('app2_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $prepayment_mac = $this->db->table_exists('mac_prepayment') ? $this->db->select('id')->from('mac_prepayment')->where('app_name', $app)->where('app_status', 'waiting')->where('app2_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $prepayment_ctz = $this->db->table_exists('ctz_prepayment') ? $this->db->select('id')->from('ctz_prepayment')->where('app_name', $app)->where('app_status', 'waiting')->where('app2_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;

        // REIMBUST
        $reimbust_sw = $this->db->table_exists('tbl_reimbust') ? $this->db->select('id')->from('tbl_reimbust')->where('app4_name', $app)->where('app4_status', 'waiting')->where('app_status', 'waiting')->where('app2_status', 'waiting')->or_where('app_name', $app)->where('app4_status', 'approved')->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app4_status', 'approved')->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $sml_reimbust = $this->db->table_exists('sml_reimbust') ? $this->db->select('id')->from('sml_reimbust')->where('app4_name', $app)->where('app4_status', 'waiting')->where('app_status', 'waiting')->where('app2_status', 'waiting')->or_where('app_name', $app)->where('app4_status', 'approved')->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app4_status', 'approved')->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $pu_reimbust = $this->db->table_exists('tbl_reimbust_pu') ? $this->db->select('id')->from('tbl_reimbust_pu')->where('app_name', $app)->where('app_status', 'waiting')->where('app2_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $swi_reimbust = $this->db->table_exists('swi_reimbust') ? $this->db->select('id')->from('swi_reimbust')->where('app_name', $app)->where('app_status', 'waiting')->where('app2_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $kps_reimbust = $this->db->table_exists('kps_reimbust') ? $this->db->select('id')->from('kps_reimbust')->where('app_name', $app)->where('app_status', 'waiting')->where('app2_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $bmn_reimbust = $this->db->table_exists('bmn_reimbust') ? $this->db->select('id')->from('bmn_reimbust')->where('app_name', $app)->where('app_status', 'waiting')->where('app2_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $qbg_reimbust = $this->db->table_exists('qbg_reimbust') ? $this->db->select('id')->from('qbg_reimbust')->where('app_name', $app)->where('app_status', 'waiting')->where('app2_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $reimbust_mac = $this->db->table_exists('mac_reimbust') ? $this->db->select('id')->from('mac_reimbust')->where('app_name', $app)->where('app_status', 'waiting')->where('app2_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $reimbust_ctz = $this->db->table_exists('ctz_reimbust') ? $this->db->select('id')->from('ctz_reimbust')->where('app_name', $app)->where('app_status', 'waiting')->where('app2_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;

        // DEKLARASI
        $datadeklarasi_sw = $this->db->table_exists('tbl_deklarasi') ? $this->db->select('id')->from('tbl_deklarasi')->where('app4_name', $app)->where('app4_status', 'waiting')->where('app_status', 'waiting')->where('app2_status', 'waiting')->or_where('app_name', $app)->where('app4_status', 'approved')->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app4_status', 'approved')->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $sml_datadeklarasi = $this->db->table_exists('sml_deklarasi') ? $this->db->select('id')->from('sml_deklarasi')->where('app4_name', $app)->where('app4_status', 'waiting')->where('app_status', 'waiting')->where('app2_status', 'waiting')->or_where('app_name', $app)->where('app4_status', 'approved')->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app4_status', 'approved')->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $pu_datadeklarasi = $this->db->table_exists('tbl_deklarasi_pu') ? $this->db->select('id')->from('tbl_deklarasi_pu')->where('app_name', $app)->where('app_status', 'waiting')->where('app2_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $swi_datadeklarasi = $this->db->table_exists('swi_deklarasi') ? $this->db->select('id')->from('swi_deklarasi')->where('app_name', $app)->where('app_status', 'waiting')->where('app2_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $kps_datadeklarasi = $this->db->table_exists('kps_deklarasi') ? $this->db->select('id')->from('kps_deklarasi')->where('app_name', $app)->where('app_status', 'waiting')->where('app2_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $bmn_datadeklarasi = $this->db->table_exists('bmn_deklarasi') ? $this->db->select('id')->from('bmn_deklarasi')->where('app_name', $app)->where('app_status', 'waiting')->where('app2_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $qbg_datadeklarasi = $this->db->table_exists('qbg_deklarasi') ? $this->db->select('id')->from('qbg_deklarasi')->where('app_name', $app)->where('app_status', 'waiting')->where('app2_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $deklarasi_mac = $this->db->table_exists('mac_deklarasi') ? $this->db->select('id')->from('mac_deklarasi')->where('app_name', $app)->where('app_status', 'waiting')->where('app2_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $deklarasi_ctz = $this->db->table_exists('ctz_deklarasi') ? $this->db->select('id')->from('ctz_deklarasi')->where('app_name', $app)->where('app_status', 'waiting')->where('app2_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;

        // NOTIFIKASI
        $notifikasi_sw = $this->db->table_exists('tbl_datanotifikasi') ? $this->db->select('id')->from('tbl_notifikasi')->where('app_hc_name', $app)->where('app_hc_status', 'waiting')->where('app2_status', 'waiting')->or_where('app2_name', $app)->where('app_hc_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $notifikasi_pu = $this->db->table_exists('tbl_datanotifikasi_pu') ? $this->db->select('id')->from('tbl_notifikasi_pu')->where('app_hc_name', $app)->where('app_hc_status', 'waiting')->where('app2_status', 'waiting')->or_where('app2_name', $app)->where('app_hc_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $notifikasi_pw = $this->db->table_exists('tbl_datanotifikasi_pw') ? $this->db->select('id')->from('tbl_notifikasi_pw')->where('app_hc_name', $app)->where('app_hc_status', 'waiting')->where('app2_status', 'waiting')->or_where('app2_name', $app)->where('app_hc_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $notifikasi_kps = $this->db->table_exists('tbl_datanotifikasi_kps') ? $this->db->select('id')->from('tbl_notifikasi_kps')->where('app_hc_name', $app)->where('app_hc_status', 'waiting')->where('app2_status', 'waiting')->or_where('app2_name', $app)->where('app_hc_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $notifikasi_bmn = $this->db->table_exists('tbl_datanotifikasi_bmn') ? $this->db->select('id')->from('tbl_notifikasi_bmn')->where('app_hc_name', $app)->where('app_hc_status', 'waiting')->where('app2_status', 'waiting')->or_where('app2_name', $app)->where('app_hc_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $notifikasi_qbg = $this->db->table_exists('qbg_notifikasi') ? $this->db->select('id')->from('qbg_notifikasi')->where('app_hc_name', $app)->where('app_hc_status', 'waiting')->where('app2_status', 'waiting')->or_where('app2_name', $app)->where('app_hc_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;

        $data['notif_pending'] = [
            'sw_prepayment' => $prepayment_sw,
            'sml_prepayment' => $sml_prepayment,
            'pu_prepayment' => $pu_prepayment,
            'swi_prepayment' => $swi_prepayment,
            'kps_prepayment' => $kps_prepayment,
            'bmn_prepayment' => $bmn_prepayment,
            'qbg_prepayment' => $qbg_prepayment,
            'mac_prepayment' => $prepayment_mac,
            'ctz_prepayment' => $prepayment_ctz,
            'sw_reimbust' => $reimbust_sw,
            'sml_reimbust' => $sml_reimbust,
            'pu_reimbust' => $pu_reimbust,
            'swi_reimbust' => $swi_reimbust,
            'kps_reimbust' => $kps_reimbust,
            'bmn_reimbust' => $bmn_reimbust,
            'qbg_reimbust' => $qbg_reimbust,
            'mac_reimbust' => $reimbust_mac,
            'ctz_reimbust' => $reimbust_ctz,
            'sw_datadeklarasi' => $datadeklarasi_sw,
            'sml_datadeklarasi' => $sml_datadeklarasi,
            'pu_datadeklarasi' => $pu_datadeklarasi,
            'swi_datadeklarasi' => $swi_datadeklarasi,
            'kps_datadeklarasi' => $kps_datadeklarasi,
            'bmn_datadeklarasi' => $bmn_datadeklarasi,
            'qbg_datadeklarasi' => $qbg_datadeklarasi,
            'mac_datadeklarasi' => $deklarasi_mac,
            'ctz_datadeklarasi' => $deklarasi_ctz,
            'datanotifikasi_pu' => $notifikasi_pu,
        ];

        $data['notif_menu'] = [
            'pengenumroh' => $pu_prepayment + $pu_reimbust + $pu_datadeklarasi,
            'sebelaswarna' => $prepayment_sw + $reimbust_sw + $datadeklarasi_sw,
            'KPS' => $kps_prepayment + $kps_reimbust + $kps_datadeklarasi,
            'sobatwisata' => $swi_prepayment + $swi_reimbust + $swi_datadeklarasi,
            'bymoment' => $bmn_prepayment + $bmn_reimbust + $bmn_datadeklarasi,
            'qubagift' => $qbg_prepayment + $qbg_reimbust + $qbg_datadeklarasi,
            'mobileautocare' => $prepayment_mac + $reimbust_mac + $deklarasi_mac,
            'carstensz' => $prepayment_ctz + $reimbust_ctz + $deklarasi_ctz,
            'samlog' => $sml_prepayment + $sml_reimbust + $sml_datadeklarasi
        ];

        return $data;
    }
}
