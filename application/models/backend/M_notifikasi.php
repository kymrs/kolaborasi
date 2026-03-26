<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_notifikasi extends CI_Model
{
    // untuk sidebar
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


                if (in_array('app4_name', $fields) && in_array('app4_status', $fields)) {
                    // app4 hanya jika statusnya waiting dan app4_name tidak null
                    $where[] = "(LOWER(app4_name) = " . $this->db->escape(strtolower($name)) . " AND app4_status = 'waiting' AND app4_name IS NOT NULL)";
                }

                // PU Notifikasi / HC Approval
                if (in_array('app_hc_name', $fields) && in_array('app_hc_status', $fields)) {
                    $where[] = "(" .
                        "LOWER(app_hc_name) = " . $this->db->escape(strtolower($name)) . " AND " .
                        "app_hc_status = 'waiting'" .
                        ")";
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
                    } elseif (in_array('app_hc_status', $fields)) {
                        // app2 hanya jika HC sudah approved
                        $where[] = "(LOWER(app2_name) = " . $this->db->escape(strtolower($name)) . " AND app2_status = 'waiting' AND app_hc_status = 'approved')";
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

    // untuk modal detail pending
    function get_pending_details($search = '', $jenis = '', $status = '', $menu_id = '')
    {
        $query = $this->db
            ->select('tbl_submenu.id_submenu, tbl_submenu.id_menu, tbl_submenu.link, tbl_submenu.nama_tbl, tbl_menu.nama_menu')
            ->from('tbl_submenu')
            ->join('tbl_menu', 'tbl_menu.id_menu = tbl_submenu.id_menu', 'left')
            ->where('tbl_submenu.nama_tbl IS NOT NULL', null, false)
            ->where('tbl_submenu.nama_tbl !=', '');

        if (!empty($menu_id)) {
            $query = $query->where('tbl_submenu.id_menu', $menu_id);
        }

        $tbl_notifikasi = $query->get()->result();

        $id = $this->session->userdata('id_user');
        $name = $this->db->select('fullname')
            ->from('tbl_user')
            ->where('id_user', $id)
            ->get()
            ->row('fullname');

        $details = [];

        foreach ($tbl_notifikasi as $table) {
            $tabel_nama = $table->nama_tbl;
            $sub_menu = $table->link;
            $nama_menu = $table->nama_menu;

            if ($tabel_nama != '' && $tabel_nama != null) {
                if (!empty($jenis) && stripos($tabel_nama, '_' . $jenis) === false) {
                    continue;
                }

                $fields = $this->db->list_fields($tabel_nama);

                $where = [];

                if (in_array('app4_name', $fields) && in_array('app4_status', $fields)) {
                    $where[] = "(LOWER(app4_name) = " . $this->db->escape(strtolower($name)) . " AND app4_status = 'waiting' AND app4_name IS NOT NULL)";
                }

                if (in_array('app_hc_name', $fields) && in_array('app_hc_status', $fields)) {
                    $where[] = "(" .
                        "LOWER(app_hc_name) = " . $this->db->escape(strtolower($name)) . " AND " .
                        "app_hc_status = 'waiting'" .
                        ")";
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
                        $where[] = "(LOWER(app2_name) = " . $this->db->escape(strtolower($name)) . " AND app2_status = 'waiting' AND app_status = 'approved')";
                    } elseif (in_array('app_hc_status', $fields)) {
                        $where[] = "(LOWER(app2_name) = " . $this->db->escape(strtolower($name)) . " AND app2_status = 'waiting' AND app_hc_status = 'approved')";
                    } else {
                        $where[] = "(LOWER(app2_name) = " . $this->db->escape(strtolower($name)) . " AND app2_status = 'waiting')";
                    }
                }

                if (!empty($where)) {
                    // Select fields: id, kode if exists, else id as kode
                    $select_fields = 'id';
                    if (in_array('kode', $fields)) {
                        $select_fields .= ', kode';
                    } elseif (in_array('kode_reimbust', $fields)) {
                        $select_fields .= ', kode_reimbust AS kode';
                    } elseif (in_array('kode_notifikasi', $fields)) {
                        $select_fields .= ', kode_notifikasi AS kode';
                    } elseif (in_array('kode_prepayment', $fields)) {
                        $select_fields .= ', kode_prepayment AS kode';
                    } elseif (in_array('kode_deklarasi', $fields)) {
                        $select_fields .= ', kode_deklarasi AS kode';
                    } else {
                        $select_fields .= ', id AS kode';
                    }

                    // Nama pengaju dari join tbl_data_user
                    $join_field = null;
                    if (in_array('id_pengaju', $fields)) {
                        $join_field = 'id_pengaju';
                    } elseif (in_array('id_user', $fields)) {
                        $join_field = 'id_user';
                    }

                    $tanggal_field = null;
                    foreach (['created_at', 'tanggal_pengajuan', 'tanggal', 'created_date', 'date_created', 'tgl_pengajuan', 'input_date', 'app_hc_date', 'app2_date'] as $candidate) {
                        if (in_array($candidate, $fields)) {
                            $tanggal_field = $candidate;
                            break;
                        }
                    }
                    if ($tanggal_field === null) {
                        $tanggal_field = 'id';
                    }

                    $select_fields .= ', tbl_data_user.name AS nama_pengaju, ' . $tabel_nama . '.' . $tanggal_field . ' AS tanggal_pengajuan';

                    $this->db->select($select_fields)
                        ->from($tabel_nama)
                        ->join('tbl_data_user', $join_field ? ($tabel_nama . '.' . $join_field . ' = tbl_data_user.id_user') : '1=0', 'left')
                        ->where(implode(' OR ', $where), null, false);

                    // Apply search filters
                    // No filters needed

                    $query = $this->db->get();
                    $results = $query->result_array();

                    foreach ($results as $row) {
                        $details[] = [
                            'nama_pengaju' => $row['nama_pengaju'] ?? 'Unknown',
                            'tanggal_pengajuan' => $row['tanggal_pengajuan'] ?? '',
                            'form' => $tabel_nama,
                            'kode' => $row['kode'] ?? $row['id'],
                            'link' => $sub_menu . '/read_form/' . $row['id'] // asumsi link edit
                        ];
                    }
                }
            }
        }

        return $details;
    }
}
