<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_notifikasi extends CI_Model
{
    private function get_pending_count($table, $conditions)
    {
        if (!$this->db->table_exists($table)) return null;

        $this->db->select('id')->from($table);
        $this->db->group_start();
        foreach ($conditions as $group) {
            $this->db->or_group_start();
            foreach ($group as $field => $value) {
                $this->db->where($field, $value);
            }
            $this->db->group_end();
        }
        $this->db->group_end();

        return $this->db->get()->num_rows();
    }


    function pending_notification()
    {
        $id = $this->session->userdata('id_user');
        $app = $this->db->select('name')->from('tbl_data_user')->where('id_user', $id)->get()->row('name');

        $prepayment_sw = $this->get_pending_count('tbl_prepayment', [
            ['app4_name' => $app, 'app4_status' => 'waiting', 'app_status' => 'waiting', 'app2_status' => 'waiting'],
            ['app_name' => $app, 'app4_status' => 'approved', 'app_status' => 'waiting'],
            ['app2_name' => $app, 'app4_status' => 'approved', 'app_status' => 'approved', 'app2_status' => 'waiting'],
        ]);

        $sml_prepayment = $this->get_pending_count('sml_prepayment', [
            ['app4_name' => $app, 'app4_status' => 'waiting', 'app_status' => 'waiting', 'app2_status' => 'waiting'],
            ['app_name' => $app, 'app4_status' => 'approved', 'app_status' => 'waiting'],
            ['app2_name' => $app, 'app4_status' => 'approved', 'app_status' => 'approved', 'app2_status' => 'waiting'],
        ]);

        $pu_prepayment = $this->get_pending_count('pu_prepayment', [
            ['app_name' => $app, 'app_status' => 'waiting'],
            ['app2_name' => $app, 'app_status' => 'approved', 'app2_status' => 'waiting'],
        ]);

        $swi_prepayment = $this->get_pending_count('swi_prepayment', [
            ['app_name' => $app, 'app_status' => 'waiting'],
            ['app2_name' => $app, 'app_status' => 'approved', 'app2_status' => 'waiting'],
        ]);

        $kps_prepayment = $this->get_pending_count('kps_prepayment', [
            ['app_name' => $app, 'app_status' => 'waiting'],
            ['app2_name' => $app, 'app_status' => 'approved', 'app2_status' => 'waiting'],
        ]);

        $bmn_prepayment = $this->get_pending_count('bmn_prepayment', [
            ['app_name' => $app, 'app_status' => 'waiting'],
            ['app2_name' => $app, 'app_status' => 'approved', 'app2_status' => 'waiting'],
        ]);

        $qbg_prepayment = $this->get_pending_count('pu_prepayment', [
            ['app_name' => $app, 'app_status' => 'waiting'],
            ['app2_name' => $app, 'app_status' => 'approved', 'app2_status' => 'waiting'],
        ]);

        $prepayment_mac = $this->get_pending_count('prepayment_mac', [
            ['app_name' => $app, 'app_status' => 'waiting'],
            ['app2_name' => $app, 'app_status' => 'approved', 'app2_status' => 'waiting'],
        ]);

        $prepayment_ctz = $this->get_pending_count('prepayment_ctz', [
            ['app_name' => $app, 'app_status' => 'waiting'],
            ['app2_name' => $app, 'app_status' => 'approved', 'app2_status' => 'waiting'],
        ]);

        $reimbust_sw = $this->get_pending_count('tbl_reimbust', [
            ['app4_name' => $app, 'app4_status' => 'waiting', 'app_status' => 'waiting', 'app2_status' => 'waiting'],
            ['app_name' => $app, 'app4_status' => 'approved', 'app_status' => 'waiting'],
            ['app2_name' => $app, 'app4_status' => 'approved', 'app_status' => 'approved', 'app2_status' => 'waiting'],
        ]);

        $sml_reimbust = $this->get_pending_count('sml_reimbust', [
            ['app4_name' => $app, 'app4_status' => 'waiting', 'app_status' => 'waiting', 'app2_status' => 'waiting'],
            ['app_name' => $app, 'app4_status' => 'approved', 'app_status' => 'waiting'],
            ['app2_name' => $app, 'app4_status' => 'approved', 'app_status' => 'approved', 'app2_status' => 'waiting'],
        ]);

        $pu_reimbust = $this->get_pending_count('pu_reimbust', [
            ['app_name' => $app, 'app_status' => 'waiting'],
            ['app2_name' => $app, 'app_status' => 'approved', 'app2_status' => 'waiting'],
        ]);

        $swi_reimbust = $this->get_pending_count('swi_reimbust', [
            ['app_name' => $app, 'app_status' => 'waiting'],
            ['app2_name' => $app, 'app_status' => 'approved', 'app2_status' => 'waiting'],
        ]);

        $kps_reimbust = $this->get_pending_count('kps_reimbust', [
            ['app_name' => $app, 'app_status' => 'waiting'],
            ['app2_name' => $app, 'app_status' => 'approved', 'app2_status' => 'waiting'],
        ]);

        $bmn_reimbust = $this->get_pending_count('bmn_reimbust', [
            ['app_name' => $app, 'app_status' => 'waiting'],
            ['app2_name' => $app, 'app_status' => 'approved', 'app2_status' => 'waiting'],
        ]);

        $qbg_reimbust = $this->get_pending_count('pu_reimbust', [
            ['app_name' => $app, 'app_status' => 'waiting'],
            ['app2_name' => $app, 'app_status' => 'approved', 'app2_status' => 'waiting'],
        ]);

        $reimbust_mac = $this->get_pending_count('reimbust_mac', [
            ['app_name' => $app, 'app_status' => 'waiting'],
            ['app2_name' => $app, 'app_status' => 'approved', 'app2_status' => 'waiting'],
        ]);

        $reimbust_ctz = $this->get_pending_count('reimbust_ctz', [
            ['app_name' => $app, 'app_status' => 'waiting'],
            ['app2_name' => $app, 'app_status' => 'approved', 'app2_status' => 'waiting'],
        ]);

        $datadeklarasi_sw = $this->get_pending_count('tbl_deklarasi', [
            ['app4_name' => $app, 'app4_status' => 'waiting', 'app_status' => 'waiting', 'app2_status' => 'waiting'],
            ['app_name' => $app, 'app4_status' => 'approved', 'app_status' => 'waiting'],
            ['app2_name' => $app, 'app4_status' => 'approved', 'app_status' => 'approved', 'app2_status' => 'waiting'],
        ]);

        $sml_datadeklarasi = $this->get_pending_count('sml_deklarasi', [
            ['app4_name' => $app, 'app4_status' => 'waiting', 'app_status' => 'waiting', 'app2_status' => 'waiting'],
            ['app_name' => $app, 'app4_status' => 'approved', 'app_status' => 'waiting'],
            ['app2_name' => $app, 'app4_status' => 'approved', 'app_status' => 'approved', 'app2_status' => 'waiting'],
        ]);

        $pu_datadeklarasi = $this->get_pending_count('pu_deklarasi', [
            ['app_name' => $app, 'app_status' => 'waiting'],
            ['app2_name' => $app, 'app_status' => 'approved', 'app2_status' => 'waiting'],
        ]);

        $swi_datadeklarasi = $this->get_pending_count('swi_deklarasi', [
            ['app_name' => $app, 'app_status' => 'waiting'],
            ['app2_name' => $app, 'app_status' => 'approved', 'app2_status' => 'waiting'],
        ]);

        $kps_datadeklarasi = $this->get_pending_count('kps_deklarasi', [
            ['app_name' => $app, 'app_status' => 'waiting'],
            ['app2_name' => $app, 'app_status' => 'approved', 'app2_status' => 'waiting'],
        ]);

        $bmn_datadeklarasi = $this->get_pending_count('bmn_deklarasi', [
            ['app_name' => $app, 'app_status' => 'waiting'],
            ['app2_name' => $app, 'app_status' => 'approved', 'app2_status' => 'waiting'],
        ]);

        $qbg_datadeklarasi = $this->get_pending_count('pu_deklarasi', [
            ['app_name' => $app, 'app_status' => 'waiting'],
            ['app2_name' => $app, 'app_status' => 'approved', 'app2_status' => 'waiting'],
        ]);

        $deklarasi_mac = $this->get_pending_count('deklarasi_mac', [
            ['app_name' => $app, 'app_status' => 'waiting'],
            ['app2_name' => $app, 'app_status' => 'approved', 'app2_status' => 'waiting'],
        ]);

        $deklarasi_ctz = $this->get_pending_count('deklarasi_ctz', [
            ['app_name' => $app, 'app_status' => 'waiting'],
            ['app2_name' => $app, 'app_status' => 'approved', 'app2_status' => 'waiting'],
        ]);

        $notifikasi_pu = $this->get_pending_count('tbl_datanotifikasi_pu', [
            ['app_hc_name' => $app, 'app_hc_status' => 'waiting'],
            ['app2_name' => $app, 'app_hc_status' => 'approved', 'app2_status' => 'waiting'],
        ]);

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
