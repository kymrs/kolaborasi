<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_rekapitulasi_bmn extends CI_Model
{
    var $id = 'id';
    var $table = 'tbl_reimbust_bmn';
    var $table2 = 'tbl_reimbust_detail_bmn';
    var $table3 = 'tbl_prepayment_bmn';

    function _get_datatables_query()
    {
        // Define column ordering for different tabs
        if (!empty($_POST['tab'])) {
            if ($_POST['tab'] == 'pelaporan') {
                // Column order for "pelaporan" tab
                $this->column_order = array(null, 'tgl_pengajuan', 'name', 'tujuan', 'kode_reimbust', 'kode_prepayment', 'total_nominal', 'total_jumlah_detail');
                $this->column_search = array('tbl_reimbust_bmn.tgl_pengajuan', 'tbl_data_user.name', 'tbl_prepayment_bmn.tujuan', 'tbl_reimbust_bmn.kode_reimbust', 'tbl_prepayment_bmn.kode_prepayment', 'tbl_prepayment_bmn.total_nominal');

                // Query for "pelaporan" tab
                $this->db->select('tbl_reimbust_bmn.id, 
                               tbl_prepayment_bmn.id as prepayment_id, 
                               tbl_reimbust_bmn.kode_reimbust, 
                               tbl_data_user.name, 
                               tbl_prepayment_bmn.tujuan, 
                               IF(tbl_reimbust_bmn.kode_prepayment IS NOT NULL, tbl_reimbust_bmn.tgl_pengajuan, tbl_prepayment_bmn.tgl_prepayment) AS tgl_pengajuan,  
                               tbl_prepayment_bmn.kode_prepayment, 
                               tbl_prepayment_bmn.total_nominal, 
                               SUM(tbl_reimbust_detail_bmn.jumlah) AS total_jumlah_detail');
                $this->db->from('tbl_prepayment_bmn');
                $this->db->join('tbl_reimbust_bmn', 'tbl_reimbust_bmn.kode_prepayment = tbl_prepayment_bmn.kode_prepayment', 'left');
                $this->db->join('tbl_reimbust_detail_bmn', 'tbl_reimbust_bmn.id = tbl_reimbust_detail_bmn.reimbust_id', 'left');
                $this->db->join('tbl_data_user', 'tbl_prepayment_bmn.id_user = tbl_data_user.id_user', 'left');

                $this->db->group_start();
                $this->db->where('tbl_prepayment_bmn.payment_status', 'paid');
                $this->db->where('tbl_reimbust_bmn.kode_prepayment IS NULL');
                $this->db->or_where('tbl_reimbust_bmn.payment_status', 'paid');
                $this->db->group_end();

                // Filter by date range
                if (!empty($_POST['awal']) && !empty($_POST['akhir'])) {
                    $tgl_awal = date('Y-m-d', strtotime($_POST['awal']));
                    $tgl_akhir = date('Y-m-d', strtotime($_POST['akhir']));

                    $this->db->group_start();
                    $this->db->where('tbl_reimbust_bmn.tgl_pengajuan >=', $tgl_awal);
                    $this->db->where('tbl_reimbust_bmn.kode_prepayment IS NOT NULL');
                    $this->db->or_where('tbl_prepayment_bmn.tgl_prepayment >=', $tgl_awal);
                    $this->db->where('tbl_reimbust_bmn.kode_prepayment IS NULL');
                    $this->db->group_end();

                    $this->db->group_start();
                    $this->db->where('tbl_reimbust_bmn.tgl_pengajuan <=', $tgl_akhir);
                    $this->db->where('tbl_reimbust_bmn.kode_prepayment IS NOT NULL');
                    $this->db->or_where('tbl_prepayment_bmn.tgl_prepayment <=', $tgl_akhir);
                    $this->db->where('tbl_reimbust_bmn.kode_prepayment IS NULL');
                    $this->db->group_end();
                }



                $this->db->group_by(array('tbl_prepayment_bmn.id', 'tbl_prepayment_bmn.kode_prepayment'));
            } elseif ($_POST['tab'] == 'reimbust') {
                // Column order for "reimbust" tab
                $this->column_order = array(null, 'kode_prepayment', 'tbl_reimbust_bmn.kode_reimbust', 'name', 'tujuan', 'tbl_reimbust_bmn.tgl_pengajuan', 'total_jumlah_detail');
                $this->column_search = array('tbl_reimbust_bmn.tgl_pengajuan', 'tbl_data_user.name', 'tbl_reimbust_bmn.tujuan', 'tbl_reimbust_bmn.kode_reimbust', 'tbl_reimbust_bmn.kode_prepayment');

                // Query for "reimbust" tab
                $this->db->select('tbl_reimbust_bmn.id, tbl_reimbust_bmn.tgl_pengajuan, tbl_data_user.name, tbl_reimbust_bmn.tujuan, tbl_reimbust_bmn.kode_reimbust, tbl_reimbust_bmn.kode_prepayment, SUM(tbl_reimbust_detail_bmn.jumlah) AS total_jumlah_detail');
                $this->db->from('tbl_reimbust_bmn');
                $this->db->join('tbl_reimbust_detail_bmn', 'tbl_reimbust_bmn.id = tbl_reimbust_detail_bmn.reimbust_id', 'left');
                $this->db->join('tbl_data_user', 'tbl_reimbust_bmn.id_user = tbl_data_user.id_user', 'left');
                $this->db->where('tbl_reimbust_bmn.payment_status', 'paid');
                $this->db->where('tbl_reimbust_bmn.kode_prepayment', '');

                // Filter by date range
                if (!empty($_POST['awal']) && !empty($_POST['akhir'])) {
                    $tgl_awal = date('Y-m-d', strtotime($_POST['awal']));
                    $tgl_akhir = date('Y-m-d', strtotime($_POST['akhir']));

                    $this->db->where('tbl_reimbust_bmn.tgl_pengajuan >=', $tgl_awal);
                    $this->db->where('tbl_reimbust_bmn.tgl_pengajuan <=', $tgl_akhir);
                }

                $this->db->group_by('tbl_reimbust_bmn.id, tbl_reimbust_bmn.kode_reimbust, tbl_reimbust_bmn.tgl_pengajuan');
            }
        }

        // Search functionality
        $i = 0;
        foreach ($this->column_search as $item) {
            if ($_POST['search']['value']) {
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if (count($this->column_search) - 1 == $i) {
                    $this->db->group_end();
                }
            }
            $i++;
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
                $this->column_order = array(null, 'tgl_pengajuan', 'name', 'tujuan', 'kode_reimbust', 'kode_prepayment', 'total_nominal', 'total_jumlah_detail');
                $this->column_search = array('tbl_reimbust_bmn.tgl_pengajuan', 'name', 'tbl_prepayment_bmn.tujuan', 'kode_reimbust', 'tbl_prepayment_bmn.kode_prepayment', 'tbl_prepayment_bmn.total_nominal');

                // Query for "pelaporan" tab
                $this->db->select('tbl_reimbust_bmn.id, 
                               tbl_prepayment_bmn.id as prepayment_id, 
                               tbl_reimbust_bmn.kode_reimbust, 
                               tbl_data_user.name, 
                               tbl_prepayment_bmn.tujuan, 
                               IF(tbl_reimbust_bmn.kode_prepayment IS NOT NULL, tbl_reimbust_bmn.tgl_pengajuan, tbl_prepayment_bmn.tgl_prepayment) AS tgl_pengajuan,  
                               tbl_prepayment_bmn.kode_prepayment, 
                               tbl_prepayment_bmn.total_nominal, 
                               SUM(tbl_reimbust_detail_bmn.jumlah) AS total_jumlah_detail');
                $this->db->from('tbl_prepayment_bmn');
                $this->db->join('tbl_reimbust_bmn', 'tbl_reimbust_bmn.kode_prepayment = tbl_prepayment_bmn.kode_prepayment', 'left');
                $this->db->join('tbl_reimbust_detail_bmn', 'tbl_reimbust_bmn.id = tbl_reimbust_detail_bmn.reimbust_id', 'left');
                $this->db->join('tbl_data_user', 'tbl_prepayment_bmn.id_user = tbl_data_user.id_user', 'left');

                $this->db->group_start();
                $this->db->where('tbl_prepayment_bmn.payment_status', 'paid');
                $this->db->where('tbl_reimbust_bmn.kode_prepayment IS NULL');
                $this->db->or_where('tbl_reimbust_bmn.payment_status', 'paid');
                $this->db->group_end();

                // Filter by date range
                if (!empty($_POST['awal']) && !empty($_POST['akhir'])) {
                    $tgl_awal = date('Y-m-d', strtotime($_POST['awal']));
                    $tgl_akhir = date('Y-m-d', strtotime($_POST['akhir']));

                    $this->db->group_start();
                    $this->db->where('tbl_reimbust_bmn.tgl_pengajuan >=', $tgl_awal);
                    $this->db->where('tbl_reimbust_bmn.kode_prepayment IS NOT NULL');
                    $this->db->or_where('tbl_prepayment_bmn.tgl_prepayment >=', $tgl_awal);
                    $this->db->where('tbl_reimbust_bmn.kode_prepayment IS NULL');
                    $this->db->group_end();

                    $this->db->group_start();
                    $this->db->where('tbl_reimbust_bmn.tgl_pengajuan <=', $tgl_akhir);
                    $this->db->where('tbl_reimbust_bmn.kode_prepayment IS NOT NULL');
                    $this->db->or_where('tbl_prepayment_bmn.tgl_prepayment <=', $tgl_akhir);
                    $this->db->where('tbl_reimbust_bmn.kode_prepayment IS NULL');
                    $this->db->group_end();
                }

                $this->db->group_by(array('tbl_prepayment_bmn.id', 'tbl_prepayment_bmn.kode_prepayment'));
            } elseif ($_POST['tab'] == 'reimbust') {
                // Column order for "reimbust" tab
                $this->column_order = array(null, 'tbl_reimbust_bmn.tgl_pengajuan', 'name', 'tujuan', 'tbl_reimbust_bmn.kode_reimbust', 'kode_prepayment', 'total_jumlah_detail');
                $this->column_search = array('tbl_reimbust_bmn.tgl_pengajuan', 'name', 'tujuan', 'tbl_reimbust_bmn.kode_reimbust', 'kode_prepayment');

                // Query for "reimbust" tab
                $this->db->select('tbl_reimbust_bmn.id, tbl_reimbust_bmn.tgl_pengajuan, tbl_data_user.name, tbl_reimbust_bmn.tujuan, tbl_reimbust_bmn.kode_reimbust, tbl_reimbust_bmn.kode_prepayment, SUM(tbl_reimbust_detail_bmn.jumlah) AS total_jumlah_detail');
                $this->db->from('tbl_reimbust_bmn');
                $this->db->join('tbl_reimbust_detail_bmn', 'tbl_reimbust_bmn.id = tbl_reimbust_detail_bmn.reimbust_id');
                $this->db->join('tbl_data_user', 'tbl_reimbust_bmn.id_user = tbl_data_user.id_user');
                $this->db->where('tbl_reimbust_bmn.payment_status', 'paid');
                $this->db->where('tbl_reimbust_bmn.kode_prepayment', '');

                // Filter by date range
                if (!empty($_POST['awal']) && !empty($_POST['akhir'])) {
                    $tgl_awal = date('Y-m-d', strtotime($_POST['awal']));
                    $tgl_akhir = date('Y-m-d', strtotime($_POST['akhir']));

                    $this->db->where('tbl_reimbust_bmn.tgl_pengajuan >=', $tgl_awal);
                    $this->db->where('tbl_reimbust_bmn.tgl_pengajuan <=', $tgl_akhir);
                }

                $this->db->group_by('tbl_reimbust_bmn.id, tbl_reimbust_bmn.kode_reimbust, tbl_reimbust_bmn.tgl_pengajuan');
            }
        }

        // Search functionality
        $i = 0;
        foreach ($this->column_search as $item) {
            if ($_POST['search']['value']) {
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if (count($this->column_search) - 1 == $i) {
                    $this->db->group_end();
                }
            }
            $i++;
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

    public function get_by_id($id)
    {
        $this->db->where($this->id, $id);
        return $this->db->get($this->table)->row();
    }

    public function get_by_id_detail($id)
    {
        $this->db->where('reimbust_id', $id);
        return $this->db->get($this->table2)->result_array();
    }

    function get_total_pengeluaran()
    {
        // Total untuk prepayment
        $this->db->select('SUM(a.total_nominal) AS total_nominal');
        $this->db->from('tbl_prepayment_bmn AS a');
        $this->db->join('tbl_reimbust_bmn AS b', 'a.kode_prepayment = b.kode_prepayment', 'left');
        $this->db->where('a.payment_status', 'paid');
        $this->db->where('b.kode_prepayment IS NULL');

        // // Filter by date range if needed
        if (!empty($_POST['awal']) && !empty($_POST['akhir'])) {
            $tgl_awal = date('Y-m-d', strtotime($_POST['awal']));
            $tgl_akhir = date('Y-m-d', strtotime($_POST['akhir']));

            $this->db->group_start();

            if ($tgl_awal == $tgl_akhir) {
                $this->db->where('a.tgl_prepayment =', $tgl_awal);
            } else {
                $this->db->where('a.tgl_prepayment >=', $tgl_awal);
                $this->db->where('a.tgl_prepayment <=', $tgl_akhir);
            }

            $this->db->group_end();
        }

        $query_prepayment = $this->db->get()->row()->total_nominal;
        $total_prepayment = $query_prepayment != NULL ? $query_prepayment : 0;

        // Total untuk pelaporan
        $this->db->select('SUM(b.jumlah) AS total_nominal');
        $this->db->from('tbl_reimbust_bmn AS a');
        $this->db->join('tbl_reimbust_detail_bmn AS b', 'a.id = b.reimbust_id', 'left');
        $this->db->where('a.payment_status', 'paid');
        $this->db->where('a.kode_prepayment !=', '');

        // Filter by date range if needed
        if (!empty($_POST['awal']) && !empty($_POST['akhir'])) {
            $tgl_awal = date('Y-m-d', strtotime($_POST['awal']));
            $tgl_akhir = date('Y-m-d', strtotime($_POST['akhir']));

            $this->db->group_start();

            if ($tgl_awal == $tgl_akhir) {
                $this->db->where('a.tgl_pengajuan =', $tgl_awal);
            } else {
                $this->db->where('a.tgl_pengajuan >=', $tgl_awal);
                $this->db->where('a.tgl_pengajuan <=', $tgl_akhir);
            }

            $this->db->group_end();
        }

        $query_pelaporan = $this->db->get()->row()->total_nominal;
        $total_pelaporan = $query_pelaporan != NULL ? $query_pelaporan : 0;

        // Total untuk reimbust
        $this->db->select('SUM(b.jumlah) AS total_nominal');
        $this->db->from('tbl_reimbust_bmn AS a');
        $this->db->join('tbl_reimbust_detail_bmn AS b', 'a.id = b.reimbust_id', 'left');
        $this->db->where('a.payment_status', 'paid');
        $this->db->where('a.kode_prepayment', '');

        // // Filter by date range if needed
        if (!empty($_POST['awal']) && !empty($_POST['akhir'])) {
            $tgl_awal = date('Y-m-d', strtotime($_POST['awal']));
            $tgl_akhir = date('Y-m-d', strtotime($_POST['akhir']));

            $this->db->group_start();

            if ($tgl_awal == $tgl_akhir) {
                $this->db->where('a.tgl_pengajuan =', $tgl_awal);
            } else {
                $this->db->where('a.tgl_pengajuan >=', $tgl_awal);
                $this->db->where('a.tgl_pengajuan <=', $tgl_akhir);
            }

            $this->db->group_end();
        }

        $query_reimbust = $this->db->get()->row()->total_nominal;
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
        $this->db->select('a.id, a.kode_prepayment, a.tgl_prepayment, a.prepayment, a.total_nominal');
        $this->db->from('tbl_prepayment_bmn AS a');
        $this->db->join('tbl_reimbust_bmn AS b', 'a.kode_prepayment = b.kode_prepayment', 'left');
        $this->db->where('a.payment_status', 'paid');
        $this->db->where('b.kode_prepayment IS NULL');

        // Filter by date range if needed
        if (!empty($tgl_awal) && !empty($tgl_akhir)) {
            $awal = date('Y-m-d', strtotime($tgl_awal));
            $akhir = date('Y-m-d', strtotime($tgl_akhir));

            $this->db->group_start();

            if ($tgl_awal == $tgl_akhir) {
                $this->db->where('a.tgl_prepayment =', $awal);
            } else {
                $this->db->where('a.tgl_prepayment >=', $awal);
                $this->db->where('a.tgl_prepayment <=', $akhir);
            }

            $this->db->group_end();
        }

        $query = $this->db->get(); // Simpan hasil query
        return $query->result(); // Kembalikan hasil dalam bentuk object
    }

    function get_data_reimbust($tgl_awal, $tgl_akhir)
    {
        $this->db->select('a.id, a.kode_reimbust, a.tgl_pengajuan, a.sifat_pelaporan, SUM(b.jumlah) AS total_nominal');
        $this->db->from('tbl_reimbust_bmn AS a');
        $this->db->join('tbl_reimbust_detail_bmn AS b', 'a.id = b.reimbust_id', 'inner');
        $this->db->where('a.payment_status', 'paid');
        $this->db->group_by('a.id');

        // Filter by date range if needed
        if (!empty($tgl_awal) && !empty($tgl_akhir)) {
            $awal = date('Y-m-d', strtotime($tgl_awal));
            $akhir = date('Y-m-d', strtotime($tgl_akhir));

            $this->db->group_start();

            if ($tgl_awal == $tgl_akhir) {
                $this->db->where('a.tgl_pengajuan =', $awal);
            } else {
                $this->db->where('a.tgl_pengajuan >=', $awal);
                $this->db->where('a.tgl_pengajuan <=', $akhir);
            }

            $this->db->group_end();
        }

        $query = $this->db->get();
        return $query->result();
    }
}