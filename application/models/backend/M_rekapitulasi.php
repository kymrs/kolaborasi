<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_rekapitulasi extends CI_Model
{
    var $id = 'id';

    function _get_datatables_query()
    {
        // Define column ordering for different tabs
        if (!empty($_POST['tab'])) {
            if ($_POST['tab'] == 'pelaporan') {
                // Column order for "pelaporan" tab
                $this->column_order = array(null, 'kode_prepayment', 'kode_reimbust', 'user_name', 'tujuan', 'tgl_pengajuan', 'total_nominal', 'total_jumlah_detail', 'source_table');
                $this->column_search = array('kode_prepayment', 'kode_reimbust', 'user_name', 'tujuan', 'tgl_pengajuan', 'total_nominal', 'total_jumlah_detail', 'source_table');

                // Query for "pelaporan" tab
                $this->db->select('*');  // Specify '*' to select all columns
                $this->db->from('view_pelaporan');

                // Filter by date range
                if (!empty($_POST['awal']) && !empty($_POST['akhir'])) {
                    $tgl_awal = date('Y-m-d', strtotime($_POST['awal']));
                    $tgl_akhir = date('Y-m-d', strtotime($_POST['akhir']));

                    $this->db->where('tgl_pengajuan >=', $tgl_awal);
                    $this->db->where('tgl_pengajuan <=', $tgl_akhir);
                    $this->db->where('kode_prepayment IS NOT NULL');
                }
            } elseif ($_POST['tab'] == 'reimbust') {
                // Column order for "reimbust" tab
                $this->column_order = array(null, 'kode_prepayment', 'kode_reimbust', 'user_name', 'tujuan', 'tgl_pengajuan', 'total_jumlah_detail', 'source_table');
                $this->column_search = array('kode_prepayment', 'kode_reimbust', 'user_name', 'tujuan', 'tgl_pengajuan', 'total_jumlah_detail', 'source_table');

                // Query for "reimbust" tab
                $this->db->select('*');  // Specify '*' to select all columns
                $this->db->from('view_reimbust');

                // Filter by date range
                if (!empty($_POST['awal']) && !empty($_POST['akhir'])) {
                    $tgl_awal = date('Y-m-d', strtotime($_POST['awal']));
                    $tgl_akhir = date('Y-m-d', strtotime($_POST['akhir']));

                    $this->db->where('tgl_pengajuan >=', $tgl_awal);
                    $this->db->where('tgl_pengajuan <=', $tgl_akhir);
                }
            }
        }

        // Search functionality
        if (!empty($_POST['search']['value'])) {
            $this->db->group_start();
            foreach ($this->column_search as $index => $item) {
                if ($index === 0) {
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
            }
            $this->db->group_end();
        }

        // Order functionality
        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        // Define column ordering for different tabs
        if (!empty($_POST['tab'])) {
            if ($_POST['tab'] == 'pelaporan') {
                // Column order for "pelaporan" tab
                $this->column_order = array(null, 'kode_prepayment', 'kode_reimbust', 'user_name', 'tujuan', 'tgl_pengajuan', 'total_nominal', 'total_jumlah_detail', 'source_table');
                $this->column_search = array('kode_prepayment', 'kode_reimbust', 'user_name', 'tujuan', 'tgl_pengajuan', 'total_nominal', 'total_jumlah_detail', 'source_table');

                // Query for "pelaporan" tab
                $this->db->select('*');  // Specify '*' to select all columns
                $this->db->from('view_pelaporan');

                // Filter by date range
                if (!empty($_POST['awal']) && !empty($_POST['akhir'])) {
                    $tgl_awal = date('Y-m-d', strtotime($_POST['awal']));
                    $tgl_akhir = date('Y-m-d', strtotime($_POST['akhir']));

                    $this->db->where('tgl_pengajuan >=', $tgl_awal);
                    $this->db->where('tgl_pengajuan <=', $tgl_akhir);
                    $this->db->where('kode_prepayment IS NOT NULL');
                }
            } elseif ($_POST['tab'] == 'reimbust') {
                // Column order for "reimbust" tab
                $this->column_order = array(null, 'id', 'kode_prepayment', 'kode_reimbust', 'user_name', 'tujuan', 'tgl_pengajuan', 'total_jumlah_detail', 'source_table');
                $this->column_search = array('id', 'kode_prepayment', 'kode_reimbust', 'user_name', 'tujuan', 'tgl_pengajuan', 'total_jumlah_detail', 'source_table');

                // Query for "reimbust" tab
                $this->db->select('*');  // Specify '*' to select all columns
                $this->db->from('view_reimbust');

                // Filter by date range
                if (!empty($_POST['awal']) && !empty($_POST['akhir'])) {
                    $tgl_awal = date('Y-m-d', strtotime($_POST['awal']));
                    $tgl_akhir = date('Y-m-d', strtotime($_POST['akhir']));

                    $this->db->where('tgl_pengajuan >=', $tgl_awal);
                    $this->db->where('tgl_pengajuan <=', $tgl_akhir);
                }
            }
        }

        // Search functionality
        if (!empty($_POST['search']['value'])) {
            $this->db->group_start();
            foreach ($this->column_search as $index => $item) {
                if ($index === 0) {
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
            }
            $this->db->group_end();
        }

        // Order functionality
        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
        return $this->db->count_all_results();
    }

    function get_total_pengeluaran()
    {
        // Total untuk pelaporan
        $this->db->select('SUM(total_jumlah_detail) AS jumlah_detail');
        $this->db->from('view_pelaporan');
        $this->db->where('kode_reimbust !=', '');

        // // Filter by date range if needed
        if (!empty($_POST['awal']) && !empty($_POST['akhir'])) {
            $tgl_awal = date('Y-m-d', strtotime($_POST['awal']));
            $tgl_akhir = date('Y-m-d', strtotime($_POST['akhir']));

            $this->db->group_start();

            if ($tgl_awal == $tgl_akhir) {
                $this->db->where('tgl_pengajuan =', $tgl_awal);
            } else {
                $this->db->where('tgl_pengajuan >=', $tgl_awal);
                $this->db->where('tgl_pengajuan <=', $tgl_akhir);
            }

            $this->db->group_end();
        }

        $query_pelaporan = $this->db->get()->row()->jumlah_detail;
        $total_pelaporan = $query_pelaporan != NULL ? $query_pelaporan : 0;

        // Total untuk prepayment
        $this->db->select('SUM(total_nominal) AS total_nominal');
        $this->db->from('view_pelaporan');
        $this->db->where('kode_reimbust IS NULL');

        // Filter by date range if needed
        if (!empty($_POST['awal']) && !empty($_POST['akhir'])) {
            $tgl_awal = date('Y-m-d', strtotime($_POST['awal']));
            $tgl_akhir = date('Y-m-d', strtotime($_POST['akhir']));

            $this->db->group_start();

            if ($tgl_awal == $tgl_akhir) {
                $this->db->where('tgl_pengajuan =', $tgl_awal);
            } else {
                $this->db->where('tgl_pengajuan >=', $tgl_awal);
                $this->db->where('tgl_pengajuan <=', $tgl_akhir);
            }

            $this->db->group_end();
        }

        $query_prepayment = $this->db->get()->row()->total_nominal;
        $total_prepayment = $query_prepayment != NULL ? $query_prepayment : 0;

        // Total untuk reimbust
        $this->db->select('SUM(total_jumlah_detail) AS jumlah_detail');
        $this->db->from('view_reimbust');

        // // Filter by date range if needed
        if (!empty($_POST['awal']) && !empty($_POST['akhir'])) {
            $tgl_awal = date('Y-m-d', strtotime($_POST['awal']));
            $tgl_akhir = date('Y-m-d', strtotime($_POST['akhir']));

            $this->db->group_start();

            if ($tgl_awal == $tgl_akhir) {
                $this->db->where('tgl_pengajuan =', $tgl_awal);
            } else {
                $this->db->where('tgl_pengajuan >=', $tgl_awal);
                $this->db->where('tgl_pengajuan <=', $tgl_akhir);
            }

            $this->db->group_end();
        }

        $query_reimbust = $this->db->get()->row()->jumlah_detail;
        $total_reimbust = $query_reimbust != NULL ? $query_reimbust : 0;

        // Total keseluruhan dari pelaporan dan reimbust
        $total_keseluruhan = $total_prepayment + $total_pelaporan + $total_reimbust;

        $data = array(
            'total_prepayment' => $total_prepayment,
            'total_pelaporan' => $total_pelaporan,
            'total_reimbust' => $total_reimbust,
            'total_pengeluaran' => $total_keseluruhan
        );

        return $data;
    }

    function get_data_prepayment($tgl_awal, $tgl_akhir)
    {
        $this->db->select('kode_prepayment, tgl_pengajuan, tujuan, total_nominal, source_table');
        $this->db->from('view_pelaporan');
        $this->db->where('kode_reimbust IS NULL');

        // Filter by date range if needed
        if (!empty($tgl_awal) && !empty($tgl_akhir)) {
            $awal = date('Y-m-d', strtotime($tgl_awal));
            $akhir = date('Y-m-d', strtotime($tgl_akhir));

            $this->db->group_start();

            if ($tgl_awal == $tgl_akhir) {
                $this->db->where('tgl_pengajuan =', $awal);
            } else {
                $this->db->where('tgl_pengajuan >=', $awal);
                $this->db->where('tgl_pengajuan <=', $akhir);
            }

            $this->db->group_end();
        }

        $query = $this->db->get(); // Simpan hasil query
        return $query->result(); // Kembalikan hasil dalam bentuk object
    }

    function get_data_pelaporan($tgl_awal, $tgl_akhir)
    {
        $this->db->select('kode_reimbust, tgl_pengajuan, tujuan, total_jumlah_detail, source_table');
        $this->db->from('view_pelaporan');
        $this->db->where('kode_reimbust IS NOT NULL');

        // Filter by date range if needed
        if (!empty($tgl_awal) && !empty($tgl_akhir)) {
            $awal = date('Y-m-d', strtotime($tgl_awal));
            $akhir = date('Y-m-d', strtotime($tgl_akhir));

            $this->db->group_start();

            if ($tgl_awal == $tgl_akhir) {
                $this->db->where('tgl_pengajuan =', $awal);
            } else {
                $this->db->where('tgl_pengajuan >=', $awal);
                $this->db->where('tgl_pengajuan <=', $akhir);
            }

            $this->db->group_end();
        }

        $query = $this->db->get(); // Simpan hasil query
        return $query->result(); // Kembalikan hasil dalam bentuk object
    }

    function get_data_reimbust($tgl_awal, $tgl_akhir)
    {
        $this->db->select('id, kode_reimbust, tgl_pengajuan, tujuan, total_jumlah_detail, source_table');
        $this->db->from('view_reimbust');

        // Filter by date range if needed
        if (!empty($tgl_awal) && !empty($tgl_akhir)) {
            $awal = date('Y-m-d', strtotime($tgl_awal));
            $akhir = date('Y-m-d', strtotime($tgl_akhir));

            $this->db->group_start();

            if ($tgl_awal == $tgl_akhir) {
                $this->db->where('tgl_pengajuan =', $awal);
            } else {
                $this->db->where('tgl_pengajuan >=', $awal);
                $this->db->where('tgl_pengajuan <=', $akhir);
            }

            $this->db->group_end();
        }

        $query = $this->db->get();
        return $query->result();
    }
}
