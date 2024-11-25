<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_notifikasi extends CI_Model
{
    // function pending_notification()
    // {
    //     $id = $this->session->userdata('id_user');
    //     $app = $this->db->select('name')->from('tbl_data_user')->where('id_user', $id)->get()->row('name');

    //     // Function to count rows with dynamic conditions
    //     function get_pending_count($table, $app, $conditions)
    //     {
    //         $ci = &get_instance();
    //         if ($ci->db->table_exists($table)) {
    //             $query = $ci->db->select('id')->from($table);

    //             $query->where_not_in('status', ['approved', 'rejected']);

    //             $query->group_start();
    //             foreach ($conditions as $condition) {
    //                 $query->or_where($condition);
    //             }
    //             $query->group_end();
    //             return $query->get()->num_rows();
    //         }
    //         return null;
    //     }

    //     // Define all the tables and their respective conditions
    //     $tables = [
    //         'prepayment' => [
    //             'tbl_prepayment' => [
    //                 ['app4_name' => $app, 'app4_status' => 'waiting'],
    //                 ['app_name' => $app, 'app4_status' => 'approved', 'app_status' => 'waiting'],
    //                 ['app2_name' => $app, 'app_status' => 'approved', 'app2_status' => 'waiting']
    //             ],
    //             'tbl_prepayment_pu' => [
    //                 ['app_name' => $app, 'app_status' => 'waiting'],
    //                 ['app2_name' => $app, 'app_status' => 'approved', 'app2_status' => 'waiting']
    //             ],
    //             'tbl_prepayment_pw' => [
    //                 ['app_name' => $app, 'app_status' => 'waiting'],
    //                 ['app2_name' => $app, 'app_status' => 'approved', 'app2_status' => 'waiting']
    //             ],
    //             'tbl_prepayment_kps' => [
    //                 ['app_name' => $app, 'app_status' => 'waiting'],
    //                 ['app2_name' => $app, 'app_status' => 'approved', 'app2_status' => 'waiting']
    //             ],
    //             'tbl_prepayment_bmn' => [
    //                 ['app_name' => $app, 'app_status' => 'waiting'],
    //                 ['app2_name' => $app, 'app_status' => 'approved', 'app2_status' => 'waiting']
    //             ],
    //             'qbg_prepayment' => [
    //                 ['app_name' => $app, 'app_status' => 'waiting'],
    //                 ['app2_name' => $app, 'app_status' => 'approved', 'app2_status' => 'waiting']
    //             ],
    //         ],
    //         'reimbust' => [
    //             'tbl_reimbust' => [
    //                 ['app4_name' => $app, 'app4_status' => 'waiting'],
    //                 ['app_name' => $app, 'app4_status' => 'approved', 'app_status' => 'waiting'],
    //                 ['app2_name' => $app, 'app_status' => 'approved', 'app2_status' => 'waiting']
    //             ],
    //             'tbl_reimbust_pu' => [
    //                 ['app_name' => $app, 'app_status' => 'waiting'],
    //                 ['app2_name' => $app, 'app_status' => 'approved', 'app2_status' => 'waiting']
    //             ],
    //             'tbl_reimbust_pw' => [
    //                 ['app_name' => $app, 'app_status' => 'waiting'],
    //                 ['app2_name' => $app, 'app_status' => 'approved', 'app2_status' => 'waiting']
    //             ],
    //             'tbl_reimbust_kps' => [
    //                 ['app_name' => $app, 'app_status' => 'waiting'],
    //                 ['app2_name' => $app, 'app_status' => 'approved', 'app2_status' => 'waiting']
    //             ],
    //             'tbl_reimbust_bmn' => [
    //                 ['app_name' => $app, 'app_status' => 'waiting'],
    //                 ['app2_name' => $app, 'app_status' => 'approved', 'app2_status' => 'waiting']
    //             ],
    //             'qbg_reimbust' => [
    //                 ['app_name' => $app, 'app_status' => 'waiting'],
    //                 ['app2_name' => $app, 'app_status' => 'approved', 'app2_status' => 'waiting']
    //             ],
    //         ],
    //         'deklarasi' => [
    //             'tbl_datadeklarasi' => [
    //                 ['app4_name' => $app, 'app4_status' => 'waiting'],
    //                 ['app_name' => $app, 'app4_status' => 'approved', 'app_status' => 'waiting'],
    //                 ['app2_name' => $app, 'app_status' => 'approved', 'app2_status' => 'waiting']
    //             ],
    //             'tbl_datadeklarasi_pu' => [
    //                 ['app_name' => $app, 'app_status' => 'waiting'],
    //                 ['app2_name' => $app, 'app_status' => 'approved', 'app2_status' => 'waiting']
    //             ],
    //             'tbl_datadeklarasi_pw' => [
    //                 ['app_name' => $app, 'app_status' => 'waiting'],
    //                 ['app2_name' => $app, 'app_status' => 'approved', 'app2_status' => 'waiting']
    //             ],
    //             'tbl_datadeklarasi_kps' => [
    //                 ['app_name' => $app, 'app_status' => 'waiting'],
    //                 ['app2_name' => $app, 'app_status' => 'approved', 'app2_status' => 'waiting']
    //             ],
    //             'tbl_datadeklarasi_bmn' => [
    //                 ['app_name' => $app, 'app_status' => 'waiting'],
    //                 ['app2_name' => $app, 'app_status' => 'approved', 'app2_status' => 'waiting']
    //             ],
    //             'qbg_datadeklarasi' => [
    //                 ['app_name' => $app, 'app_status' => 'waiting'],
    //                 ['app2_name' => $app, 'app_status' => 'approved', 'app2_status' => 'waiting']
    //             ],
    //         ],
    //         'notifikasi' => [
    //             'tbl_datanotifikasi' => [
    //                 ['app_hc_name' => $app, 'app_hc_status' => 'waiting'],
    //                 ['app2_name' => $app, 'app_hc_status' => 'approved', 'app2_status' => 'waiting']
    //             ],
    //             'tbl_datanotifikasi_pu' => [
    //                 ['app_name' => $app, 'app_status' => 'waiting'],
    //                 ['app2_name' => $app, 'app_status' => 'approved', 'app2_status' => 'waiting']
    //             ],
    //             'tbl_datanotifikasi_pw' => [
    //                 ['app_name' => $app, 'app_status' => 'waiting'],
    //                 ['app2_name' => $app, 'app_status' => 'approved', 'app2_status' => 'waiting']
    //             ],
    //             'tbl_datanotifikasi_kps' => [
    //                 ['app_name' => $app, 'app_status' => 'waiting'],
    //                 ['app2_name' => $app, 'app_status' => 'approved', 'app2_status' => 'waiting']
    //             ],
    //             'tbl_datanotifikasi_bmn' => [
    //                 ['app_name' => $app, 'app_status' => 'waiting'],
    //                 ['app2_name' => $app, 'app_status' => 'approved', 'app2_status' => 'waiting']
    //             ],
    //             'qbg_datanotifikasi' => [
    //                 ['app_name' => $app, 'app_status' => 'waiting'],
    //                 ['app2_name' => $app, 'app_status' => 'approved', 'app2_status' => 'waiting']
    //             ],
    //         ],
    //     ];

    //     // Process all the tables dynamically
    //     $notif_pending = [];
    //     foreach ($tables as $type => $tableGroup) {
    //         foreach ($tableGroup as $table => $conditions) {
    //             $key = "{$type}_" . substr($table, strrpos($table, '_') + 1);
    //             $notif_pending[$key] = get_pending_count($table, $app, $conditions);
    //         }
    //     }

    //     return ['notif_pending' => $notif_pending];
    // }


    function pending_notification()
    {
        $id = $this->session->userdata('id_user');
        $app = $this->db->select('name')->from('tbl_data_user')->where('id_user', $id)->get()->row('name');
        // PREPAYMENT
        $prepayment_sw = $this->db->table_exists('tbl_prepayment') ? $this->db->select('id')->from('tbl_prepayment')->where('app4_name', $app)->where('app4_status', 'waiting')->or_where('app_name', $app)->where('app4_status', 'approved')->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $prepayment_pu = $this->db->table_exists('tbl_prepayment_pu') ? $this->db->select('id')->from('tbl_prepayment_pu')->where('app_name', $app)->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $prepayment_pw = $this->db->table_exists('tbl_prepayment_pw') ? $this->db->select('id')->from('tbl_prepayment_pw')->where('app_name', $app)->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $prepayment_kps = $this->db->table_exists('tbl_prepayment_kps') ? $this->db->select('id')->from('tbl_prepayment_kps')->where('app_name', $app)->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $prepayment_qbg = $this->db->table_exists('qbg_prepayment') ? $this->db->select('id')->from('qbg_prepayment')->where('app_name', $app)->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $prepayment_bmn = $this->db->table_exists('tbl_prepayment_bmn') ? $this->db->select('id')->from('tbl_prepayment_bmn')->where('app_name', $app)->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;

        // REIMBUST
        $reimbust_sw = $this->db->table_exists('tbl_reimbust') ? $this->db->select('id')->from('tbl_reimbust')->where('app4_name', $app)->where('app4_status', 'waiting')->or_where('app_name', $app)->where('app4_status', 'approved')->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $reimbust_pu = $this->db->table_exists('tbl_reimbust_pu') ? $this->db->select('id')->from('tbl_reimbust_pu')->where('app_name', $app)->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $reimbust_pw = $this->db->table_exists('tbl_reimbust_pw') ? $this->db->select('id')->from('tbl_reimbust_pw')->where('app_name', $app)->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $reimbust_kps = $this->db->table_exists('tbl_reimbust_kps') ? $this->db->select('id')->from('tbl_reimbust_kps')->where('app_name', $app)->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $reimbust_bmn = $this->db->table_exists('tbl_reimbust_bmn') ? $this->db->select('id')->from('tbl_reimbust_bmn')->where('app_name', $app)->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $reimbust_qbg = $this->db->table_exists('qbg_reimbust') ? $this->db->select('id')->from('qbg_reimbust')->where('app_name', $app)->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;

        // DEKLARASI
        $deklarasi_sw = $this->db->table_exists('tbl_datadeklarasi') ? $this->db->select('id')->from('tbl_deklarasi')->where('app4_name', $app)->where('app4_status', 'waiting')->or_where('app_name', $app)->where('app4_status', 'approved')->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $deklarasi_pu = $this->db->table_exists('tbl_datadeklarasi_pu') ? $this->db->select('id')->from('tbl_deklarasi_pu')->where('app_name', $app)->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $deklarasi_pw = $this->db->table_exists('tbl_datadeklarasi_pw') ? $this->db->select('id')->from('tbl_deklarasi_pw')->where('app_name', $app)->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $deklarasi_kps = $this->db->table_exists('tbl_datadeklarasi_kps') ? $this->db->select('id')->from('tbl_deklarasi_kps')->where('app_name', $app)->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $deklarasi_bmn = $this->db->table_exists('tbl_datadeklarasi_bmn') ? $this->db->select('id')->from('tbl_deklarasi_bmn')->where('app_name', $app)->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $deklarasi_qbg = $this->db->table_exists('tbl_datadeklarasi_qbg') ? $this->db->select('id')->from('tbl_deklarasi_qbg')->where('app_name', $app)->where('app_status', 'waiting')->or_where('app2_name', $app)->where('app_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;

        // NOTIFIKASI
        $notifikasi_sw = $this->db->table_exists('tbl_datanotifikasi') ? $this->db->select('id')->from('tbl_notifikasi')->where('app_hc_name', $app)->where('app_hc_status', 'waiting')->or_where('app2_name', $app)->where('app_hc_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $notifikasi_pu = $this->db->table_exists('tbl_datanotifikasi_pu') ? $this->db->select('id')->from('tbl_notifikasi_pu')->where('app_hc_name', $app)->where('app_hc_status', 'waiting')->or_where('app2_name', $app)->where('app_hc_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $notifikasi_pw = $this->db->table_exists('tbl_datanotifikasi_pw') ? $this->db->select('id')->from('tbl_notifikasi_pw')->where('app_hc_name', $app)->where('app_hc_status', 'waiting')->or_where('app2_name', $app)->where('app_hc_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $notifikasi_kps = $this->db->table_exists('tbl_datanotifikasi_kps') ? $this->db->select('id')->from('tbl_notifikasi_kps')->where('app_hc_name', $app)->where('app_hc_status', 'waiting')->or_where('app2_name', $app)->where('app_hc_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $notifikasi_bmn = $this->db->table_exists('tbl_datanotifikasi_bmn') ? $this->db->select('id')->from('tbl_notifikasi_bmn')->where('app_hc_name', $app)->where('app_hc_status', 'waiting')->or_where('app2_name', $app)->where('app_hc_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;
        $notifikasi_qbg = $this->db->table_exists('qbg_notifikasi') ? $this->db->select('id')->from('qbg_notifikasi')->where('app_hc_name', $app)->where('app_hc_status', 'waiting')->or_where('app2_name', $app)->where('app_hc_status', 'approved')->where('app2_status', 'waiting')->get()->num_rows() : null;

        $data['notif_pending'] = [
            'prepayment_sw' => $prepayment_sw,
            'prepayment_pu' => $prepayment_pu,
            'prepayment_pw' => $prepayment_pw,
            'prepayment_kps' => $prepayment_kps,
            'prepayment_bmn' => $prepayment_bmn,
            'prepayment_qbg' => $prepayment_qbg,
            'reimbust_sw' => $reimbust_sw,
            'reimbust_pu' => $reimbust_pu,
            'reimbust_pw' => $reimbust_pw,
            'reimbust_kps' => $reimbust_kps,
            'reimbust_bmn' => $reimbust_bmn,
            'reimbust_qbg' => $reimbust_qbg,
            'datadeklarasi_sw' => $deklarasi_sw,
            'datadeklarasi_pu' => $deklarasi_pu,
            'datadeklarasi_pw' => $deklarasi_pw,
            'datadeklarasi_kps' => $deklarasi_kps,
            'datadeklarasi_bmn' => $deklarasi_bmn,
            'datadeklarasi_qbg' => $deklarasi_qbg,
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
