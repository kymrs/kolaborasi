<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_notifikasi extends CI_Model
{
    function pending_notification()
    {
        $tbl_notifikasi = $this->db
            ->select('tbl_submenu.id_submenu, tbl_submenu.id_menu, tbl_submenu.link, tbl_submenu.nama_tbl, tbl_menu.nama_menu')
            ->from('tbl_submenu')
            ->join('tbl_menu', 'tbl_menu.id_menu = tbl_submenu.id_menu', 'left') // atau 'inner' sesuai kebutuhan
            ->where('tbl_submenu.nama_tbl IS NOT NULL', null, false)
            ->get()
            ->result();


        $id = $this->session->userdata('id_user');
        $name = $this->db->select('fullname')
            ->from('tbl_user')
            ->where('id_user', $id)
            ->get()
            ->row('fullname');

        $tbl = [];

        foreach ($tbl_notifikasi as $table) {
            $tabel_nama = $table->nama_tbl;
            $sub_menu = $table->link;

            if ($tabel_nama != '' || $tabel_nama != null) {
                // Cek kolom yang tersedia di tabel tersebut
                $fields = $this->db->list_fields($tabel_nama);

                // Tentukan nama kolom yang dipakai untuk pencarian nama
                $kolom_nama = null;

                $where = [];
                $where = [];


                if (in_array('app4_name', $fields) && in_array('app4_status', $fields)) {
                    // app4 hanya jika statusnya waiting dan app4_name tidak null
                    $where[] = "(LOWER(app4_name) = " . $this->db->escape(strtolower($name)) . " AND app4_status = 'waiting' AND app4_name IS NOT NULL)";
                }

                if (in_array('app_name', $fields) && in_array('app_status', $fields)) {
                    if (in_array('app4_status', $fields) && in_array('app4_name', $fields)) {
                        $where[] = "(" .
                            "LOWER(app_name) = " . $this->db->escape(strtolower($name)) . " AND " .
                            "app_status = 'waiting' AND " .
                            "(app4_name IS NULL OR app4_name = '' OR app4_status = 'approved')" .
                            ")";
                    } else {
                        $where[] = "(" .
                            "LOWER(app_name) = " . $this->db->escape(strtolower($name)) . " AND " .
                            "app_status = 'waiting'" .
                            ")";
                    }
                }


                if (in_array('app2_name', $fields) && in_array('app2_status', $fields)) {
                    if (in_array('app_status', $fields)) {
                        // app2 hanya jika app sudah approved
                        $where[] = "(LOWER(app2_name) = " . $this->db->escape(strtolower($name)) . " AND app2_status = 'waiting' AND app_status = 'approved')";
                    } else {
                        $where[] = "(LOWER(app2_name) = " . $this->db->escape(strtolower($name)) . " AND app2_status = 'waiting')";
                    }
                }


                if (!empty($where)) {
                    $this->db->select('COUNT(*) AS total')
                        ->from($tabel_nama)
                        ->where(implode(' OR ', $where), null, false);

                    $total = $this->db->get()->row()->total;
                    $tbl[$sub_menu] = $total;
                } else {
                    $tbl[$sub_menu] = 0;
                }
            }
        }

        $data['notif_pending'] = $tbl;

        $notif_menu = [];

        foreach ($tbl as $link => $jumlah) {
            $nama_menu = null;

            foreach ($tbl_notifikasi as $row) {
                if ($row->link == $link) {
                    $nama_menu = $row->nama_menu;
                    break;
                }
            }

            if ($nama_menu !== null) {
                if (!isset($notif_menu[$nama_menu])) {
                    $notif_menu[$nama_menu] = 0;
                }

                $notif_menu[$nama_menu] += (int)$jumlah;
            }
        }

        $data['notif_pending'] = $tbl;
        $data['notif_menu'] = $notif_menu;

        return $data;
    }
}
