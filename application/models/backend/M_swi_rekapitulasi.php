<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_swi_rekapitulasi extends CI_Model
{
    var $id = 'id';
    var $table = 'swi_reimbust';
    var $table2 = 'swi_reimbust_detail';
    var $table3 = 'swi_prepayment';


    public function __get_query_pelaporan()
    {
        // Column order for "pelaporan" tab
        $this->column_order = array(null, 'swi_reimbust.tgl_pengajuan', 'tbl_data_user.name', 'swi_prepayment.tujuan', 'swi_reimbust.kode_reimbust', 'swi_prepayment.kode_prepayment', 'swi_prepayment.total_nominal');
        $this->column_search = array('swi_reimbust.tgl_pengajuan', 'tbl_data_user.name', 'swi_prepayment.tujuan', 'swi_reimbust.kode_reimbust', 'swi_prepayment.kode_prepayment', 'swi_prepayment.total_nominal');

        // Query for "pelaporan" tab
        $this->db->select('swi_reimbust.id, 
                               swi_prepayment.id as prepayment_id, 
                               swi_reimbust.kode_reimbust, 
                               tbl_data_user.name, 
                               swi_prepayment.tujuan, 
                               IF(swi_reimbust.kode_prepayment IS NOT NULL, swi_reimbust.tgl_pengajuan, swi_prepayment.tgl_prepayment) AS tgl_pengajuan,  
                               swi_prepayment.kode_prepayment, 
                               swi_prepayment.total_nominal, 
                               SUM(swi_reimbust_detail.jumlah) AS total_jumlah_detail');
        $this->db->from('swi_prepayment');
        $this->db->join('swi_reimbust', 'swi_reimbust.kode_prepayment = swi_prepayment.kode_prepayment', 'left');
        $this->db->join('swi_reimbust_detail', 'swi_reimbust.id = swi_reimbust_detail.reimbust_id', 'left');
        $this->db->join('tbl_data_user', 'swi_prepayment.id_user = tbl_data_user.id_user', 'left');

        $this->db->group_start();
        $this->db->group_start();
        $this->db->where('swi_prepayment.payment_status', 'paid');
        $this->db->where('swi_reimbust.kode_prepayment IS NULL');
        $this->db->group_end();
        $this->db->or_group_start();
        $this->db->where('swi_prepayment.payment_status', 'paid');
        $this->db->where('swi_reimbust.kode_prepayment IS NOT NULL');
        $this->db->group_end();
        $this->db->or_group_start();
        $this->db->where('swi_reimbust.payment_status', 'paid');
        $this->db->where('swi_reimbust.kode_prepayment IS NOT NULL');
        $this->db->group_end();
        $this->db->group_end();


        // Filter by date range
        if (!empty($_POST['awal']) && !empty($_POST['akhir'])) {
            $tgl_awal = date('Y-m-d', strtotime($_POST['awal']));
            $tgl_akhir = date('Y-m-d', strtotime($_POST['akhir']));

            $this->db->group_start();
            $this->db->where('swi_reimbust.tgl_pengajuan >=', $tgl_awal);
            $this->db->where('swi_reimbust.kode_prepayment IS NOT NULL');
            $this->db->or_where('swi_prepayment.tgl_prepayment >=', $tgl_awal);
            $this->db->where('swi_reimbust.kode_prepayment IS NULL');
            $this->db->group_end();

            $this->db->group_start();
            $this->db->where('swi_reimbust.tgl_pengajuan <=', $tgl_akhir);
            $this->db->where('swi_reimbust.kode_prepayment IS NOT NULL');
            $this->db->or_where('swi_prepayment.tgl_prepayment <=', $tgl_akhir);
            $this->db->where('swi_reimbust.kode_prepayment IS NULL');
            $this->db->group_end();
        }



        $this->db->group_by(array('swi_prepayment.id', 'swi_prepayment.kode_prepayment'));

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
        } else {
            $this->db->order_by('swi_reimbust.tgl_pengajuan', 'DESC');
        }
    }

    function get_datatables_pelaporan()
    {
        $this->__get_query_pelaporan();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    public function count_all_pelaporan()
    {
        // Column order for "pelaporan" tab
        $this->column_order = array(null, 'tgl_pengajuan', 'name', 'tujuan', 'kode_reimbust', 'kode_prepayment', 'total_nominal', 'total_jumlah_detail');
        $this->column_search = array('swi_reimbust.tgl_pengajuan', 'tbl_data_user.name', 'swi_prepayment.tujuan', 'swi_reimbust.kode_reimbust', 'swi_prepayment.kode_prepayment', 'swi_prepayment.total_nominal');

        // Query for "pelaporan" tab
        $this->db->select('swi_reimbust.id, 
                               swi_prepayment.id as prepayment_id, 
                               swi_reimbust.kode_reimbust, 
                               tbl_data_user.name, 
                               swi_prepayment.tujuan, 
                               IF(swi_reimbust.kode_prepayment IS NOT NULL, swi_reimbust.tgl_pengajuan, swi_prepayment.tgl_prepayment) AS tgl_pengajuan,  
                               swi_prepayment.kode_prepayment, 
                               swi_prepayment.total_nominal, 
                               SUM(swi_reimbust_detail.jumlah) AS total_jumlah_detail');
        $this->db->from('swi_prepayment');
        $this->db->join('swi_reimbust', 'swi_reimbust.kode_prepayment = swi_prepayment.kode_prepayment', 'left');
        $this->db->join('swi_reimbust_detail', 'swi_reimbust.id = swi_reimbust_detail.reimbust_id', 'left');
        $this->db->join('tbl_data_user', 'swi_prepayment.id_user = tbl_data_user.id_user', 'left');

        $this->db->group_start();
        $this->db->group_start();
        $this->db->where('swi_prepayment.payment_status', 'paid');
        $this->db->where('swi_reimbust.kode_prepayment IS NULL');
        $this->db->group_end();
        $this->db->or_group_start();
        $this->db->where('swi_prepayment.payment_status', 'paid');
        $this->db->where('swi_reimbust.kode_prepayment IS NOT NULL');
        $this->db->group_end();
        $this->db->or_group_start();
        $this->db->where('swi_reimbust.payment_status', 'paid');
        $this->db->where('swi_reimbust.kode_prepayment IS NOT NULL');
        $this->db->group_end();
        $this->db->group_end();


        // Filter by date range
        if (!empty($_POST['awal']) && !empty($_POST['akhir'])) {
            $tgl_awal = date('Y-m-d', strtotime($_POST['awal']));
            $tgl_akhir = date('Y-m-d', strtotime($_POST['akhir']));

            $this->db->group_start();
            $this->db->where('swi_reimbust.tgl_pengajuan >=', $tgl_awal);
            $this->db->where('swi_reimbust.kode_prepayment IS NOT NULL');
            $this->db->or_where('swi_prepayment.tgl_prepayment >=', $tgl_awal);
            $this->db->where('swi_reimbust.kode_prepayment IS NULL');
            $this->db->group_end();

            $this->db->group_start();
            $this->db->where('swi_reimbust.tgl_pengajuan <=', $tgl_akhir);
            $this->db->where('swi_reimbust.kode_prepayment IS NOT NULL');
            $this->db->or_where('swi_prepayment.tgl_prepayment <=', $tgl_akhir);
            $this->db->where('swi_reimbust.kode_prepayment IS NULL');
            $this->db->group_end();
        }



        $this->db->group_by(array('swi_prepayment.id', 'swi_prepayment.kode_prepayment'));

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
        } else {
            // Default sorting jika tidak ada `tab`
            $this->db->order_by('swi_reimbust.tgl_pengajuan', 'DESC');
        }
        return $this->db->count_all_results();
    }

    public function count_filtered_pelaporan()
    {
        // Tambahkan filter sesuai datatables
        $this->__get_query_pelaporan();
        $query = $this->db->get();
        return $query->num_rows();

        return $this->db->count_all_results();
    }

    public function __get_query_reimbust()
    {

        // Column order for "reimbust" tab
        $this->column_order = array(null, 'kode_prepayment', 'swi_reimbust.kode_reimbust', 'name', 'tujuan', 'swi_reimbust.tgl_pengajuan', 'total_jumlah_detail');
        $this->column_search = array('swi_reimbust.tgl_pengajuan', 'tbl_data_user.name', 'swi_reimbust.tujuan', 'swi_reimbust.kode_reimbust', 'swi_reimbust.kode_prepayment');

        // Query for "reimbust" tab
        $this->db->select('swi_reimbust.id, swi_reimbust.tgl_pengajuan, tbl_data_user.name, swi_reimbust.tujuan, swi_reimbust.kode_reimbust, swi_reimbust.kode_prepayment, SUM(swi_reimbust_detail.jumlah) AS total_jumlah_detail');
        $this->db->from('swi_reimbust');
        $this->db->join('swi_reimbust_detail', 'swi_reimbust.id = swi_reimbust_detail.reimbust_id', 'left');
        $this->db->join('tbl_data_user', 'swi_reimbust.id_user = tbl_data_user.id_user', 'left');
        $this->db->where('swi_reimbust.payment_status', 'paid');
        $this->db->where('swi_reimbust.kode_prepayment', '');

        // Filter by date range
        if (!empty($_POST['awal']) && !empty($_POST['akhir'])) {
            $tgl_awal = date('Y-m-d', strtotime($_POST['awal']));
            $tgl_akhir = date('Y-m-d', strtotime($_POST['akhir']));

            $this->db->where('swi_reimbust.tgl_pengajuan >=', $tgl_awal);
            $this->db->where('swi_reimbust.tgl_pengajuan <=', $tgl_akhir);
        }

        $this->db->group_by('swi_reimbust.id, swi_reimbust.kode_reimbust, swi_reimbust.tgl_pengajuan');

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
        } else {
            $this->db->order_by('swi_reimbust.tgl_pengajuan', 'DESC');
        }
    }

    public function get_datatables_reimbust()
    {
        $this->__get_query_reimbust();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    public function count_all_reimbust()
    {
        // Column order for "reimbust" tab
        $this->column_order = array(null, 'kode_prepayment', 'swi_reimbust.kode_reimbust', 'name', 'tujuan', 'swi_reimbust.tgl_pengajuan', 'total_jumlah_detail');
        $this->column_search = array('swi_reimbust.tgl_pengajuan', 'tbl_data_user.name', 'swi_reimbust.tujuan', 'swi_reimbust.kode_reimbust', 'swi_reimbust.kode_prepayment');

        // Query for "reimbust" tab
        $this->db->select('swi_reimbust.id, swi_reimbust.tgl_pengajuan, tbl_data_user.name, swi_reimbust.tujuan, swi_reimbust.kode_reimbust, swi_reimbust.kode_prepayment, SUM(swi_reimbust_detail.jumlah) AS total_jumlah_detail');
        $this->db->from('swi_reimbust');
        $this->db->join('swi_reimbust_detail', 'swi_reimbust.id = swi_reimbust_detail.reimbust_id', 'left');
        $this->db->join('tbl_data_user', 'swi_reimbust.id_user = tbl_data_user.id_user', 'left');
        $this->db->where('swi_reimbust.payment_status', 'paid');
        $this->db->where('swi_reimbust.kode_prepayment', '');

        // Filter by date range
        if (!empty($_POST['awal']) && !empty($_POST['akhir'])) {
            $tgl_awal = date('Y-m-d', strtotime($_POST['awal']));
            $tgl_akhir = date('Y-m-d', strtotime($_POST['akhir']));

            $this->db->where('swi_reimbust.tgl_pengajuan >=', $tgl_awal);
            $this->db->where('swi_reimbust.tgl_pengajuan <=', $tgl_akhir);
        }

        $this->db->group_by('swi_reimbust.id, swi_reimbust.kode_reimbust, swi_reimbust.tgl_pengajuan');

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
        } else {
            $this->db->order_by('swi_reimbust.tgl_pengajuan', 'DESC');
        }
        return $this->db->count_all_results();
    }

    public function count_filtered_reimbust()
    {
        $this->__get_query_reimbust();
        $query = $this->db->get();
        return $query->num_rows();

        return $this->db->count_all_results();
    }

    public function __get_query_invoice()
    {
        // Column order for "invoice" tab
        $this->column_order = array(null, 'kode_invoice', 'tgl_invoice', 'ctc_to', 'total', 'tax', 'payment_status');
        $this->column_search = array('kode_invoice', 'tgl_invoice', 'ctc_to', 'total', 'tax', 'payment_status');

        // Query for "invoice" tab
        $this->db->select('kode_invoice, tgl_invoice, ctc_to, total, tax, payment_status');
        $this->db->from('swi_invoice');

        // Filter by date range
        if (!empty($_POST['awal']) && !empty($_POST['akhir'])) {
            $tgl_awal = date('Y-m-d', strtotime($_POST['awal']));
            $tgl_akhir = date('Y-m-d', strtotime($_POST['akhir']));

            $this->db->where('tgl_invoice >=', $tgl_awal);
            $this->db->where('tgl_invoice <=', $tgl_akhir);
        }

        $this->db->group_by('id, kode_invoice, tgl_invoice');

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
        } else {
            // Default sorting jika tidak ada `tab`
            $this->db->order_by('tgl_invoice', 'DESC');
        }
    }

    public function get_datatables_invoice()
    {
        $this->__get_query_invoice();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    public function count_all_invoice()
    {
        // Column order for "invoice" tab
        $this->column_order = array(null, 'kode_invoice', 'tgl_invoice', 'ctc_to', 'ctc_address', 'total', 'tax', 'payment_status');
        $this->column_search = array('kode_invoice', 'tgl_invoice', 'ctc_to', 'ctc_address', 'total', 'tax', 'payment_status');

        // Query for "invoice" tab
        $this->db->select('kode_invoice, tgl_invoice, ctc_to, ctc_address, total, tax, payment_status');
        $this->db->from('swi_invoice');

        // Filter by date range
        if (!empty($_POST['awal']) && !empty($_POST['akhir'])) {
            $tgl_awal = date('Y-m-d', strtotime($_POST['awal']));
            $tgl_akhir = date('Y-m-d', strtotime($_POST['akhir']));

            $this->db->where('tgl_invoice >=', $tgl_awal);
            $this->db->where('tgl_invoice <=', $tgl_akhir);
        }

        $this->db->group_by('id, kode_invoice, tgl_invoice');

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
        } else {
            // Default sorting jika tidak ada `tab`
            $this->db->order_by('tgl_invoice', 'DESC');
        }
        return $this->db->count_all_results();
    }

    public function count_filtered_invoice()
    {
        $this->__get_query_invoice();
        $query = $this->db->get();
        return $query->num_rows();

        return $this->db->count_all_results();
    }

    function _get_datatables_query()
    {
        // Define column ordering for different tabs
        if (!empty($_POST['tab'])) {
            if ($_POST['tab'] == 'pelaporan') {
                // Column order for "pelaporan" tab
                $this->column_order = array(null, 'tgl_pengajuan', 'name', 'tujuan', 'kode_reimbust', 'kode_prepayment', 'total_nominal', 'total_jumlah_detail');
                $this->column_search = array('swi_reimbust.tgl_pengajuan', 'tbl_data_user.name', 'swi_prepayment.tujuan', 'swi_reimbust.kode_reimbust', 'swi_prepayment.kode_prepayment', 'swi_prepayment.total_nominal');

                // Query for "pelaporan" tab
                $this->db->select('swi_reimbust.id, 
                               swi_prepayment.id as prepayment_id, 
                               swi_reimbust.kode_reimbust, 
                               tbl_data_user.name, 
                               swi_prepayment.tujuan, 
                               IF(swi_reimbust.kode_prepayment IS NOT NULL, swi_reimbust.tgl_pengajuan, swi_prepayment.tgl_prepayment) AS tgl_pengajuan,  
                               swi_prepayment.kode_prepayment, 
                               swi_prepayment.total_nominal, 
                               SUM(swi_reimbust_detail.jumlah) AS total_jumlah_detail');
                $this->db->from('swi_prepayment');
                $this->db->join('swi_reimbust', 'swi_reimbust.kode_prepayment = swi_prepayment.kode_prepayment', 'left');
                $this->db->join('swi_reimbust_detail', 'swi_reimbust.id = swi_reimbust_detail.reimbust_id', 'left');
                $this->db->join('tbl_data_user', 'swi_prepayment.id_user = tbl_data_user.id_user', 'left');

                $this->db->group_start();
                $this->db->group_start();
                $this->db->where('swi_prepayment.payment_status', 'paid');
                $this->db->where('swi_reimbust.kode_prepayment IS NULL');
                $this->db->group_end();
                $this->db->or_group_start();
                $this->db->where('swi_prepayment.payment_status', 'paid');
                $this->db->where('swi_reimbust.kode_prepayment IS NOT NULL');
                $this->db->group_end();
                $this->db->or_group_start();
                $this->db->where('swi_reimbust.payment_status', 'paid');
                $this->db->where('swi_reimbust.kode_prepayment IS NOT NULL');
                $this->db->group_end();
                $this->db->group_end();


                // Filter by date range
                if (!empty($_POST['awal']) && !empty($_POST['akhir'])) {
                    $tgl_awal = date('Y-m-d', strtotime($_POST['awal']));
                    $tgl_akhir = date('Y-m-d', strtotime($_POST['akhir']));

                    $this->db->group_start();
                    $this->db->where('swi_reimbust.tgl_pengajuan >=', $tgl_awal);
                    $this->db->where('swi_reimbust.kode_prepayment IS NOT NULL');
                    $this->db->or_where('swi_prepayment.tgl_prepayment >=', $tgl_awal);
                    $this->db->where('swi_reimbust.kode_prepayment IS NULL');
                    $this->db->group_end();

                    $this->db->group_start();
                    $this->db->where('swi_reimbust.tgl_pengajuan <=', $tgl_akhir);
                    $this->db->where('swi_reimbust.kode_prepayment IS NOT NULL');
                    $this->db->or_where('swi_prepayment.tgl_prepayment <=', $tgl_akhir);
                    $this->db->where('swi_reimbust.kode_prepayment IS NULL');
                    $this->db->group_end();
                }



                $this->db->group_by(array('swi_prepayment.id', 'swi_prepayment.kode_prepayment'));
            } elseif ($_POST['tab'] == 'reimbust') {
                // Column order for "reimbust" tab
                $this->column_order = array(null, 'kode_prepayment', 'swi_reimbust.kode_reimbust', 'name', 'tujuan', 'swi_reimbust.tgl_pengajuan', 'total_jumlah_detail');
                $this->column_search = array('swi_reimbust.tgl_pengajuan', 'tbl_data_user.name', 'swi_reimbust.tujuan', 'swi_reimbust.kode_reimbust', 'swi_reimbust.kode_prepayment');

                // Query for "reimbust" tab
                $this->db->select('swi_reimbust.id, swi_reimbust.tgl_pengajuan, tbl_data_user.name, swi_reimbust.tujuan, swi_reimbust.kode_reimbust, swi_reimbust.kode_prepayment, SUM(swi_reimbust_detail.jumlah) AS total_jumlah_detail');
                $this->db->from('swi_reimbust');
                $this->db->join('swi_reimbust_detail', 'swi_reimbust.id = swi_reimbust_detail.reimbust_id', 'left');
                $this->db->join('tbl_data_user', 'swi_reimbust.id_user = tbl_data_user.id_user', 'left');
                $this->db->where('swi_reimbust.payment_status', 'paid');
                $this->db->where('swi_reimbust.kode_prepayment', '');

                // Filter by date range
                if (!empty($_POST['awal']) && !empty($_POST['akhir'])) {
                    $tgl_awal = date('Y-m-d', strtotime($_POST['awal']));
                    $tgl_akhir = date('Y-m-d', strtotime($_POST['akhir']));

                    $this->db->where('swi_reimbust.tgl_pengajuan >=', $tgl_awal);
                    $this->db->where('swi_reimbust.tgl_pengajuan <=', $tgl_akhir);
                }

                $this->db->group_by('swi_reimbust.id, swi_reimbust.kode_reimbust, swi_reimbust.tgl_pengajuan');
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
        } else {
            if (isset($_POST['tab'])) {
                if ($_POST['tab'] == 'pelaporan') {
                    $this->db->order_by('tgl_pengajuan', 'DESC');
                } elseif ($_POST['tab'] == 'reimbust') {
                    $this->db->order_by('swi_reimbust.tgl_pengajuan', 'DESC');
                }
            } else {
                // Default sorting jika tidak ada `tab`
                $this->db->order_by('tgl_pengajuan', 'DESC');
            }
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
                $this->column_search = array('swi_reimbust.tgl_pengajuan', 'name', 'swi_prepayment.tujuan', 'kode_reimbust', 'swi_prepayment.kode_prepayment', 'swi_prepayment.total_nominal');

                // Query for "pelaporan" tab
                $this->db->select('swi_reimbust.id, 
                               swi_prepayment.id as prepayment_id, 
                               swi_reimbust.kode_reimbust, 
                               tbl_data_user.name, 
                               swi_prepayment.tujuan, 
                               IF(swi_reimbust.kode_prepayment IS NOT NULL, swi_reimbust.tgl_pengajuan, swi_prepayment.tgl_prepayment) AS tgl_pengajuan,  
                               swi_prepayment.kode_prepayment, 
                               swi_prepayment.total_nominal, 
                               SUM(swi_reimbust_detail.jumlah) AS total_jumlah_detail');
                $this->db->from('swi_prepayment');
                $this->db->join('swi_reimbust', 'swi_reimbust.kode_prepayment = swi_prepayment.kode_prepayment', 'left');
                $this->db->join('swi_reimbust_detail', 'swi_reimbust.id = swi_reimbust_detail.reimbust_id', 'left');
                $this->db->join('tbl_data_user', 'swi_prepayment.id_user = tbl_data_user.id_user', 'left');

                $this->db->group_start();
                $this->db->group_start();
                $this->db->where('swi_prepayment.payment_status', 'paid');
                $this->db->where('swi_reimbust.kode_prepayment IS NULL');
                $this->db->group_end();
                $this->db->or_group_start();
                $this->db->where('swi_prepayment.payment_status', 'paid');
                $this->db->where('swi_reimbust.kode_prepayment IS NOT NULL');
                $this->db->group_end();
                $this->db->or_group_start();
                $this->db->where('swi_reimbust.payment_status', 'paid');
                $this->db->where('swi_reimbust.kode_prepayment IS NOT NULL');
                $this->db->group_end();
                $this->db->group_end();

                // Filter by date range
                if (!empty($_POST['awal']) && !empty($_POST['akhir'])) {
                    $tgl_awal = date('Y-m-d', strtotime($_POST['awal']));
                    $tgl_akhir = date('Y-m-d', strtotime($_POST['akhir']));

                    $this->db->group_start();
                    $this->db->where('swi_reimbust.tgl_pengajuan >=', $tgl_awal);
                    $this->db->where('swi_reimbust.kode_prepayment IS NOT NULL');
                    $this->db->or_where('swi_prepayment.tgl_prepayment >=', $tgl_awal);
                    $this->db->where('swi_reimbust.kode_prepayment IS NULL');
                    $this->db->group_end();

                    $this->db->group_start();
                    $this->db->where('swi_reimbust.tgl_pengajuan <=', $tgl_akhir);
                    $this->db->where('swi_reimbust.kode_prepayment IS NOT NULL');
                    $this->db->or_where('swi_prepayment.tgl_prepayment <=', $tgl_akhir);
                    $this->db->where('swi_reimbust.kode_prepayment IS NULL');
                    $this->db->group_end();
                }

                $this->db->group_by(array('swi_prepayment.id', 'swi_prepayment.kode_prepayment'));
            } elseif ($_POST['tab'] == 'reimbust') {
                // Column order for "reimbust" tab
                $this->column_order = array(null, 'swi_reimbust.tgl_pengajuan', 'name', 'tujuan', 'swi_reimbust.kode_reimbust', 'kode_prepayment', 'total_jumlah_detail');
                $this->column_search = array('swi_reimbust.tgl_pengajuan', 'name', 'tujuan', 'swi_reimbust.kode_reimbust', 'kode_prepayment');

                // Query for "reimbust" tab
                $this->db->select('swi_reimbust.id, swi_reimbust.tgl_pengajuan, tbl_data_user.name, swi_reimbust.tujuan, swi_reimbust.kode_reimbust, swi_reimbust.kode_prepayment, SUM(swi_reimbust_detail.jumlah) AS total_jumlah_detail');
                $this->db->from('swi_reimbust');
                $this->db->join('swi_reimbust_detail', 'swi_reimbust.id = swi_reimbust_detail.reimbust_id');
                $this->db->join('tbl_data_user', 'swi_reimbust.id_user = tbl_data_user.id_user');
                $this->db->where('swi_reimbust.payment_status', 'paid');
                $this->db->where('swi_reimbust.kode_prepayment', '');

                // Filter by date range
                if (!empty($_POST['awal']) && !empty($_POST['akhir'])) {
                    $tgl_awal = date('Y-m-d', strtotime($_POST['awal']));
                    $tgl_akhir = date('Y-m-d', strtotime($_POST['akhir']));

                    $this->db->where('swi_reimbust.tgl_pengajuan >=', $tgl_awal);
                    $this->db->where('swi_reimbust.tgl_pengajuan <=', $tgl_akhir);
                }

                $this->db->group_by('swi_reimbust.id, swi_reimbust.kode_reimbust, swi_reimbust.tgl_pengajuan');
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
        $this->db->from('swi_prepayment AS a');
        $this->db->join('swi_reimbust AS b', 'a.kode_prepayment = b.kode_prepayment', 'left');
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
        $this->db->from('swi_reimbust AS a');
        $this->db->join('swi_reimbust_detail AS b', 'a.id = b.reimbust_id', 'left');
        $this->db->join('swi_prepayment AS c', 'c.kode_prepayment = a.kode_prepayment', 'right');
        $this->db->group_start();
        $this->db->where('a.payment_status', 'paid');
        $this->db->or_where('c.payment_status', 'paid');
        $this->db->where('a.kode_prepayment !=', '');
        $this->db->group_end();

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
        $this->db->from('swi_reimbust AS a');
        $this->db->join('swi_reimbust_detail AS b', 'a.id = b.reimbust_id', 'left');
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

    function get_total_pemasukan()
    {
        $this->db->select('SUM(total) AS total_pemasukan');
        $this->db->from('swi_invoice');
        $this->db->where('payment_status', 1);

        // // Filter by date range if needed
        if (!empty($_POST['awal']) && !empty($_POST['akhir'])) {
            $tgl_awal = date('Y-m-d', strtotime($_POST['awal']));
            $tgl_akhir = date('Y-m-d', strtotime($_POST['akhir']));

            $this->db->group_start();

            if ($tgl_awal == $tgl_akhir) {
                $this->db->where('tgl_invoice =', $tgl_awal);
            } else {
                $this->db->where('tgl_invoice >=', $tgl_awal);
                $this->db->where('tgl_invoice <=', $tgl_akhir);
            }

            $this->db->group_end();
        }

        $lunas = $this->db->get()->row()->total_pemasukan;

        $this->db->select('SUM(total) AS total_pemasukan');
        $this->db->from('swi_invoice');
        $this->db->where('payment_status', 0);

        // // Filter by date range if needed
        if (!empty($_POST['awal']) && !empty($_POST['akhir'])) {
            $tgl_awal = date('Y-m-d', strtotime($_POST['awal']));
            $tgl_akhir = date('Y-m-d', strtotime($_POST['akhir']));

            $this->db->group_start();

            if ($tgl_awal == $tgl_akhir) {
                $this->db->where('tgl_invoice =', $tgl_awal);
            } else {
                $this->db->where('tgl_invoice >=', $tgl_awal);
                $this->db->where('tgl_invoice <=', $tgl_akhir);
            }

            $this->db->group_end();
        }

        $tidak_lunas = $this->db->get()->row()->total_pemasukan;

        return [
            'lunas' => $lunas,
            'tidak_lunas' => $tidak_lunas
        ];
    }

    function get_data_prepayment($tgl_awal, $tgl_akhir)
    {
        $this->db->select('a.id, a.kode_prepayment, a.tgl_prepayment, a.prepayment, a.total_nominal');
        $this->db->from('swi_prepayment AS a');
        $this->db->join('swi_reimbust AS b', 'a.kode_prepayment = b.kode_prepayment', 'left');
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
        $this->db->from('swi_reimbust AS a');
        $this->db->join('swi_reimbust_detail AS b', 'a.id = b.reimbust_id', 'inner');
        $this->db->join('swi_prepayment AS c', 'a.kode_prepayment = c.kode_prepayment', 'left');

        // Group kondisi paid
        $this->db->group_start();
        $this->db->where('a.payment_status', 'paid');
        $this->db->or_where('c.payment_status', 'paid');
        $this->db->group_end();

        // Filter by date range
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

        $this->db->group_by('a.id');
        $query = $this->db->get();
        return $query->result();
    }


    function get_data_invoice($tgl_awal, $tgl_akhir)
    {
        $this->db->select('kode_invoice, tgl_invoice, ctc_to, ctc_address, total, tax, payment_status');
        $this->db->from('swi_invoice');
        $this->db->group_by('id');

        // Filter by date range if needed
        if (!empty($tgl_awal) && !empty($tgl_akhir)) {
            $awal = date('Y-m-d', strtotime($tgl_awal));
            $akhir = date('Y-m-d', strtotime($tgl_akhir));

            $this->db->group_start();

            if ($tgl_awal == $tgl_akhir) {
                $this->db->where('tgl_invoice =', $awal);
            } else {
                $this->db->where('tgl_invoice >=', $awal);
                $this->db->where('tgl_invoice <=', $akhir);
            }

            $this->db->group_end();
        }

        $query = $this->db->get();
        return $query->result();
    }
}
