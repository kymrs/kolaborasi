<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_sam_rekapitulasi extends CI_Model
{
    var $id = 'id';
    var $table = 'sam_reimbust';
    var $table2 = 'sam_reimbust_detail';
    var $table3 = 'sam_prepayment';

    private function _get_datatables_query_pelaporan()
    {
        // Column order must match the controller output columns
        // get_list_pelaporan(): No, kode_prepayment, kode_reimbust, tgl_pengajuan, tujuan, nominal
        $this->column_order = array(null, 'sam_prepayment.kode_prepayment', 'sam_reimbust.kode_reimbust', 'tgl_pengajuan', 'sam_prepayment.tujuan', 'pengeluaran');
        $this->column_search = array('sam_prepayment.kode_prepayment', 'sam_reimbust.kode_reimbust', 'tgl_pengajuan', 'sam_prepayment.tujuan');

        // Query for "pelaporan" tab
        $this->db->select('sam_reimbust.id, 
                       sam_prepayment.id as prepayment_id, 
                       sam_reimbust.kode_reimbust,  
                       sam_prepayment.tujuan, 
                       IF(sam_reimbust.kode_prepayment IS NOT NULL, sam_reimbust.tgl_pengajuan, sam_prepayment.tgl_prepayment) AS tgl_pengajuan,  
                       sam_prepayment.kode_prepayment, 
                       sam_prepayment.total_nominal, 
                       SUM(sam_reimbust_detail.jumlah) AS total_jumlah_detail,
                       COALESCE(SUM(sam_reimbust_detail.jumlah), sam_prepayment.total_nominal) AS pengeluaran');
        $this->db->from('sam_prepayment');
        $this->db->join('sam_reimbust', 'sam_reimbust.kode_prepayment = sam_prepayment.kode_prepayment', 'left');
        $this->db->join('sam_reimbust_detail', 'sam_reimbust.id = sam_reimbust_detail.reimbust_id', 'left');

        // Only include paid+approved data
        $this->db->group_start();
        // Standalone prepayment (no reimbust)
        $this->db->group_start();
        $this->db->where('sam_prepayment.payment_status', 'paid');
        $this->db->where('sam_prepayment.status', 'approved');
        $this->db->where('sam_reimbust.kode_prepayment IS NULL');
        $this->db->group_end();
        // Linked reimbust + prepayment must be paid+approved on both sides
        $this->db->or_group_start();
        $this->db->where('sam_prepayment.payment_status', 'paid');
        $this->db->where('sam_prepayment.status', 'approved');
        $this->db->where('sam_reimbust.kode_prepayment IS NOT NULL');
        $this->db->where('sam_reimbust.payment_status', 'paid');
        $this->db->where('sam_reimbust.status', 'approved');
        $this->db->group_end();
        $this->db->group_end();


        // Filter by date range
        if (!empty($_POST['awal']) && !empty($_POST['akhir'])) {
            $tgl_awal = date('Y-m-d', strtotime($_POST['awal']));
            $tgl_akhir = date('Y-m-d', strtotime($_POST['akhir']));

            $this->db->group_start();
            $this->db->group_start();
            $this->db->where('sam_reimbust.kode_prepayment IS NOT NULL');
            $this->db->where('sam_reimbust.tgl_pengajuan >=', $tgl_awal);
            $this->db->group_end();
            $this->db->or_group_start();
            $this->db->where('sam_reimbust.kode_prepayment IS NULL');   
            $this->db->where('sam_prepayment.tgl_prepayment >=', $tgl_awal);
            $this->db->group_end();
            $this->db->group_end();

            $this->db->group_start();
            $this->db->group_start();
            $this->db->where('sam_reimbust.kode_prepayment IS NOT NULL');
            $this->db->where('sam_reimbust.tgl_pengajuan <=', $tgl_akhir);
            $this->db->group_end();
            $this->db->or_group_start();
            $this->db->where('sam_reimbust.kode_prepayment IS NULL');
            $this->db->where('sam_prepayment.tgl_prepayment <=', $tgl_akhir);
            $this->db->group_end();
            $this->db->group_end();
        }

        $this->db->group_by(array('sam_prepayment.id', 'sam_prepayment.kode_prepayment'));

        // Search
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

        // Order
        if (isset($_POST['order'])) {
            $columnIndex = (int)$_POST['order']['0']['column'];
            $columnName = $this->column_order[$columnIndex] ?? null;
            if (!empty($columnName)) {
                $this->db->order_by($columnName, $_POST['order']['0']['dir']);
            }
        } else {
            $this->db->order_by('tgl_pengajuan', 'DESC');
        }
    }

    public function get_datatables_pelaporan()
    {
        $this->_get_datatables_query_pelaporan();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    public function count_filtered_pelaporan()
    {
        $this->_get_datatables_query_pelaporan();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all_pelaporan()
    {
        $this->_get_datatables_query_pelaporan();
        return $this->db->count_all_results();
    }

    private function _get_datatables_query_reimbust()
    {
        // get_list_reimbust(): No, '-', kode_reimbust, tgl_pengajuan, tujuan, nominal
        $this->column_order = array(null, null, 'sam_reimbust.kode_reimbust', 'sam_reimbust.tgl_pengajuan', 'sam_reimbust.tujuan', 'total_jumlah_detail');
        $this->column_search = array('sam_reimbust.kode_reimbust', 'sam_reimbust.tgl_pengajuan', 'sam_reimbust.tujuan');

        $this->db->select('sam_reimbust.id, sam_reimbust.tgl_pengajuan, sam_reimbust.tujuan, sam_reimbust.kode_reimbust, sam_reimbust.kode_prepayment, SUM(sam_reimbust_detail.jumlah) AS total_jumlah_detail');
        $this->db->from('sam_reimbust');
        $this->db->join('sam_reimbust_detail', 'sam_reimbust.id = sam_reimbust_detail.reimbust_id', 'left');
        $this->db->where('sam_reimbust.payment_status', 'paid');
        $this->db->where('sam_reimbust.status', 'approved');
        $this->db->where('sam_reimbust.kode_prepayment', '');

        // Filter by date range
        if (!empty($_POST['awal']) && !empty($_POST['akhir'])) {
            $tgl_awal = date('Y-m-d', strtotime($_POST['awal']));
            $tgl_akhir = date('Y-m-d', strtotime($_POST['akhir']));
            $this->db->where('sam_reimbust.tgl_pengajuan >=', $tgl_awal);
            $this->db->where('sam_reimbust.tgl_pengajuan <=', $tgl_akhir);
        }

        $this->db->group_by('sam_reimbust.id, sam_reimbust.kode_reimbust, sam_reimbust.tgl_pengajuan');

        // Search
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

        // Order
        if (isset($_POST['order'])) {
            $columnIndex = (int)$_POST['order']['0']['column'];
            $columnName = $this->column_order[$columnIndex] ?? null;
            if (!empty($columnName)) {
                $this->db->order_by($columnName, $_POST['order']['0']['dir']);
            }
        } else {
            $this->db->order_by('sam_reimbust.tgl_pengajuan', 'DESC');
        }
    }

    public function get_datatables_reimbust()
    {
        $this->_get_datatables_query_reimbust();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    public function count_filtered_reimbust()
    {
        $this->_get_datatables_query_reimbust();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all_reimbust()
    {
        $this->_get_datatables_query_reimbust();
        return $this->db->count_all_results();
    }

    private function _get_datatables_query_invoice()
    {
        $this->column_order = array(null, 'sam_invoice.id', 'sam_invoice.tgl_invoice', 'sam_invoice.ctc_to', 'sam_invoice.kode_invoice', 'sam_invoice.payment_status');
        $this->column_search = array('sam_invoice.id', 'sam_invoice.tgl_invoice', 'sam_invoice.ctc_to', 'sam_invoice.kode_invoice', 'sam_invoice.payment_status');

        $this->db->select('sam_invoice.id, sam_invoice.tgl_invoice, sam_invoice.ctc_to, sam_invoice.kode_invoice, sam_invoice.payment_status, SUM(sam_detail_invoice.total) AS total');
        $this->db->from('sam_invoice');
        $this->db->join('sam_detail_invoice', 'sam_invoice.id = sam_detail_invoice.invoice_id', 'left');
        $this->db->where('sam_invoice.payment_status', 1);

        // Filter by date range
        if (!empty($_POST['awal']) && !empty($_POST['akhir'])) {
            $tgl_awal = date('Y-m-d', strtotime($_POST['awal']));
            $tgl_akhir = date('Y-m-d', strtotime($_POST['akhir']));
            $this->db->where('sam_invoice.tgl_invoice >=', $tgl_awal);
            $this->db->where('sam_invoice.tgl_invoice <=', $tgl_akhir);
        }

        $this->db->group_by('sam_invoice.id, sam_invoice.kode_invoice, sam_invoice.tgl_invoice');

        // Search
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

        // Order
        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('sam_invoice.tgl_invoice', 'DESC');
        }
    }

    public function get_datatables_invoice()
    {
        $this->_get_datatables_query_invoice();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    public function count_filtered_invoice()
    {
        $this->_get_datatables_query_invoice();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all_invoice()
    {
        $this->_get_datatables_query_invoice();
        return $this->db->count_all_results();
    }

    function _get_datatables_query()
    {
        // Define column ordering for different tabs
        if (!empty($_POST['tab'])) {
            if ($_POST['tab'] == 'pelaporan') {
                // Controller get_list() outputs: No, kode_prepayment, kode_reimbust, name, tujuan, tgl_pengajuan, nominal
                $this->column_order = array(null, 'sam_prepayment.kode_prepayment', 'sam_reimbust.kode_reimbust', 'tbl_data_user.name', 'sam_prepayment.tujuan', 'tgl_pengajuan', 'pengeluaran');
                $this->column_search = array('sam_prepayment.kode_prepayment', 'sam_reimbust.kode_reimbust', 'tbl_data_user.name', 'sam_prepayment.tujuan', 'tgl_pengajuan');

                // Query for "pelaporan" tab
                $this->db->select('sam_reimbust.id, 
                               sam_prepayment.id as prepayment_id, 
                               sam_reimbust.kode_reimbust, 
                               tbl_data_user.name, 
                               sam_prepayment.tujuan, 
                               IF(sam_reimbust.kode_prepayment IS NOT NULL, sam_reimbust.tgl_pengajuan, sam_prepayment.tgl_prepayment) AS tgl_pengajuan,  
                               sam_prepayment.kode_prepayment, 
                               sam_prepayment.total_nominal, 
                               SUM(sam_reimbust_detail.jumlah) AS total_jumlah_detail,
                               COALESCE(SUM(sam_reimbust_detail.jumlah), sam_prepayment.total_nominal) AS pengeluaran');
                $this->db->from('sam_prepayment');
                $this->db->join('sam_reimbust', 'sam_reimbust.kode_prepayment = sam_prepayment.kode_prepayment', 'left');
                $this->db->join('sam_reimbust_detail', 'sam_reimbust.id = sam_reimbust_detail.reimbust_id', 'left');
                $this->db->join('tbl_data_user', 'sam_prepayment.id_user = tbl_data_user.id_user', 'left');

                // Only include paid+approved data
                $this->db->group_start();
                // Standalone prepayment (no reimbust)
                $this->db->group_start();
                $this->db->where('sam_prepayment.payment_status', 'paid');
                $this->db->where('sam_prepayment.status', 'approved');
                $this->db->where('sam_reimbust.kode_prepayment IS NULL');
                $this->db->group_end();
                // Linked reimbust + prepayment must be paid+approved on both sides
                $this->db->or_group_start();
                $this->db->where('sam_prepayment.payment_status', 'paid');
                $this->db->where('sam_prepayment.status', 'approved');
                $this->db->where('sam_reimbust.kode_prepayment IS NOT NULL');
                $this->db->where('sam_reimbust.payment_status', 'paid');
                $this->db->where('sam_reimbust.status', 'approved');
                $this->db->group_end();
                $this->db->group_end();

                // Filter by date range
                if (!empty($_POST['awal']) && !empty($_POST['akhir'])) {
                    $tgl_awal = date('Y-m-d', strtotime($_POST['awal']));
                    $tgl_akhir = date('Y-m-d', strtotime($_POST['akhir']));

                    $this->db->group_start();
                    $this->db->group_start();
                    $this->db->where('sam_reimbust.kode_prepayment IS NOT NULL');
                    $this->db->where('sam_reimbust.tgl_pengajuan >=', $tgl_awal);
                    $this->db->group_end();
                    $this->db->or_group_start();
                    $this->db->where('sam_reimbust.kode_prepayment IS NULL');
                    $this->db->where('sam_prepayment.tgl_prepayment >=', $tgl_awal);
                    $this->db->group_end();
                    $this->db->group_end();

                    $this->db->group_start();
                    $this->db->group_start();
                    $this->db->where('sam_reimbust.kode_prepayment IS NOT NULL');
                    $this->db->where('sam_reimbust.tgl_pengajuan <=', $tgl_akhir);
                    $this->db->group_end();
                    $this->db->or_group_start();
                    $this->db->where('sam_reimbust.kode_prepayment IS NULL');
                    $this->db->where('sam_prepayment.tgl_prepayment <=', $tgl_akhir);
                    $this->db->group_end();
                    $this->db->group_end();
                }

                $this->db->group_by(array('sam_prepayment.id', 'sam_prepayment.kode_prepayment'));
            } elseif ($_POST['tab'] == 'reimbust') {
                // Controller get_list() outputs: No, kode_prepayment, kode_reimbust, name, tujuan, tgl_pengajuan, nominal
                $this->column_order = array(null, 'sam_reimbust.kode_prepayment', 'sam_reimbust.kode_reimbust', 'tbl_data_user.name', 'sam_reimbust.tujuan', 'sam_reimbust.tgl_pengajuan', 'total_jumlah_detail');
                $this->column_search = array('sam_reimbust.kode_prepayment', 'sam_reimbust.kode_reimbust', 'tbl_data_user.name', 'sam_reimbust.tujuan', 'sam_reimbust.tgl_pengajuan');

                // Query for "reimbust" tab
                $this->db->select('sam_reimbust.id, sam_reimbust.tgl_pengajuan, tbl_data_user.name, sam_reimbust.tujuan, sam_reimbust.kode_reimbust, sam_reimbust.kode_prepayment, SUM(sam_reimbust_detail.jumlah) AS total_jumlah_detail');
                $this->db->from('sam_reimbust');
                $this->db->join('sam_reimbust_detail', 'sam_reimbust.id = sam_reimbust_detail.reimbust_id', 'left');
                $this->db->join('tbl_data_user', 'sam_reimbust.id_user = tbl_data_user.id_user', 'left');
                $this->db->where('sam_reimbust.payment_status', 'paid');
                $this->db->where('sam_reimbust.status', 'approved');
                $this->db->where('sam_reimbust.kode_prepayment', '');

                // Filter by date range
                if (!empty($_POST['awal']) && !empty($_POST['akhir'])) {
                    $tgl_awal = date('Y-m-d', strtotime($_POST['awal']));
                    $tgl_akhir = date('Y-m-d', strtotime($_POST['akhir']));

                    $this->db->where('sam_reimbust.tgl_pengajuan >=', $tgl_awal);
                    $this->db->where('sam_reimbust.tgl_pengajuan <=', $tgl_akhir);
                }

                $this->db->group_by('sam_reimbust.id, sam_reimbust.kode_reimbust, sam_reimbust.tgl_pengajuan');
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
            $columnIndex = (int)$_POST['order']['0']['column'];
            $columnName = $this->column_order[$columnIndex] ?? null;
            if (!empty($columnName)) {
                $this->db->order_by($columnName, $_POST['order']['0']['dir']);
            }
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
                $this->column_order = array(null, 'sam_prepayment.kode_prepayment', 'sam_reimbust.kode_reimbust', 'tbl_data_user.name', 'sam_prepayment.tujuan', 'tgl_pengajuan', 'pengeluaran');
                $this->column_search = array('sam_prepayment.kode_prepayment', 'sam_reimbust.kode_reimbust', 'tbl_data_user.name', 'sam_prepayment.tujuan', 'tgl_pengajuan');

                // Query for "pelaporan" tab
                $this->db->select('sam_reimbust.id, 
                               sam_prepayment.id as prepayment_id, 
                               sam_reimbust.kode_reimbust, 
                               tbl_data_user.name, 
                               sam_prepayment.tujuan, 
                               IF(sam_reimbust.kode_prepayment IS NOT NULL, sam_reimbust.tgl_pengajuan, sam_prepayment.tgl_prepayment) AS tgl_pengajuan,  
                               sam_prepayment.kode_prepayment, 
                               sam_prepayment.total_nominal, 
                               SUM(sam_reimbust_detail.jumlah) AS total_jumlah_detail,
                               COALESCE(SUM(sam_reimbust_detail.jumlah), sam_prepayment.total_nominal) AS pengeluaran');
                $this->db->from('sam_prepayment');
                $this->db->join('sam_reimbust', 'sam_reimbust.kode_prepayment = sam_prepayment.kode_prepayment', 'left');
                $this->db->join('sam_reimbust_detail', 'sam_reimbust.id = sam_reimbust_detail.reimbust_id', 'left');
                $this->db->join('tbl_data_user', 'sam_prepayment.id_user = tbl_data_user.id_user', 'left');

                // Only include paid+approved data
                $this->db->group_start();
                // Standalone prepayment (no reimbust)
                $this->db->group_start();
                $this->db->where('sam_prepayment.payment_status', 'paid');
                $this->db->where('sam_prepayment.status', 'approved');
                $this->db->where('sam_reimbust.kode_prepayment IS NULL');
                $this->db->group_end();
                // Linked reimbust + prepayment must be paid+approved on both sides
                $this->db->or_group_start();
                $this->db->where('sam_prepayment.payment_status', 'paid');
                $this->db->where('sam_prepayment.status', 'approved');
                $this->db->where('sam_reimbust.kode_prepayment IS NOT NULL');
                $this->db->where('sam_reimbust.payment_status', 'paid');
                $this->db->where('sam_reimbust.status', 'approved');
                $this->db->group_end();
                $this->db->group_end();

                // Filter by date range
                if (!empty($_POST['awal']) && !empty($_POST['akhir'])) {
                    $tgl_awal = date('Y-m-d', strtotime($_POST['awal']));
                    $tgl_akhir = date('Y-m-d', strtotime($_POST['akhir']));

                    $this->db->group_start();
                    $this->db->group_start();
                    $this->db->where('sam_reimbust.kode_prepayment IS NOT NULL');
                    $this->db->where('sam_reimbust.tgl_pengajuan >=', $tgl_awal);
                    $this->db->group_end();
                    $this->db->or_group_start();
                    $this->db->where('sam_reimbust.kode_prepayment IS NULL');
                    $this->db->where('sam_prepayment.tgl_prepayment >=', $tgl_awal);
                    $this->db->group_end();
                    $this->db->group_end();

                    $this->db->group_start();
                    $this->db->group_start();
                    $this->db->where('sam_reimbust.kode_prepayment IS NOT NULL');
                    $this->db->where('sam_reimbust.tgl_pengajuan <=', $tgl_akhir);
                    $this->db->group_end();
                    $this->db->or_group_start();
                    $this->db->where('sam_reimbust.kode_prepayment IS NULL');
                    $this->db->where('sam_prepayment.tgl_prepayment <=', $tgl_akhir);
                    $this->db->group_end();
                    $this->db->group_end();
                }

                $this->db->group_by(array('sam_prepayment.id', 'sam_prepayment.kode_prepayment'));
            } elseif ($_POST['tab'] == 'reimbust') {
                $this->column_order = array(null, 'sam_reimbust.kode_prepayment', 'sam_reimbust.kode_reimbust', 'tbl_data_user.name', 'sam_reimbust.tujuan', 'sam_reimbust.tgl_pengajuan', 'total_jumlah_detail');
                $this->column_search = array('sam_reimbust.kode_prepayment', 'sam_reimbust.kode_reimbust', 'tbl_data_user.name', 'sam_reimbust.tujuan', 'sam_reimbust.tgl_pengajuan');

                // Query for "reimbust" tab
                $this->db->select('sam_reimbust.id, sam_reimbust.tgl_pengajuan, tbl_data_user.name, sam_reimbust.tujuan, sam_reimbust.kode_reimbust, sam_reimbust.kode_prepayment, SUM(sam_reimbust_detail.jumlah) AS total_jumlah_detail');
                $this->db->from('sam_reimbust');
                $this->db->join('sam_reimbust_detail', 'sam_reimbust.id = sam_reimbust_detail.reimbust_id');
                $this->db->join('tbl_data_user', 'sam_reimbust.id_user = tbl_data_user.id_user');
                $this->db->where('sam_reimbust.payment_status', 'paid');
                $this->db->where('sam_reimbust.status', 'approved');
                $this->db->where('sam_reimbust.kode_prepayment', '');

                // Filter by date range
                if (!empty($_POST['awal']) && !empty($_POST['akhir'])) {
                    $tgl_awal = date('Y-m-d', strtotime($_POST['awal']));
                    $tgl_akhir = date('Y-m-d', strtotime($_POST['akhir']));

                    $this->db->where('sam_reimbust.tgl_pengajuan >=', $tgl_awal);
                    $this->db->where('sam_reimbust.tgl_pengajuan <=', $tgl_akhir);
                }

                $this->db->group_by('sam_reimbust.id, sam_reimbust.kode_reimbust, sam_reimbust.tgl_pengajuan');
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
            $columnIndex = (int)$_POST['order']['0']['column'];
            $columnName = $this->column_order[$columnIndex] ?? null;
            if (!empty($columnName)) {
                $this->db->order_by($columnName, $_POST['order']['0']['dir']);
            }
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
        $this->db->from('sam_prepayment AS a');
        $this->db->join('sam_reimbust AS b', 'a.kode_prepayment = b.kode_prepayment', 'left');
        $this->db->where('a.payment_status', 'paid');
        $this->db->where('a.status', 'approved');
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
        $this->db->from('sam_reimbust AS a');
        $this->db->join('sam_reimbust_detail AS b', 'a.id = b.reimbust_id', 'left');
        $this->db->join('sam_prepayment AS c', 'c.kode_prepayment = a.kode_prepayment', 'right');
        $this->db->group_start();
        $this->db->where('a.kode_prepayment !=', '');
        $this->db->where('a.payment_status', 'paid');
        $this->db->where('a.status', 'approved');
        $this->db->where('c.payment_status', 'paid');
        $this->db->where('c.status', 'approved');
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
        $this->db->from('sam_reimbust AS a');
        $this->db->join('sam_reimbust_detail AS b', 'a.id = b.reimbust_id', 'left');
        $this->db->where('a.payment_status', 'paid');
        $this->db->where('a.status', 'approved');
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
        $this->db->from('sam_invoice');
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
        $this->db->from('sam_invoice');
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
        $this->db->select('a.id, a.kode_prepayment, a.tgl_prepayment, a.prepayment, a.total_nominal, a.tujuan');
        $this->db->from('sam_prepayment AS a');
        $this->db->join('sam_reimbust AS b', 'a.kode_prepayment = b.kode_prepayment', 'left');
        $this->db->where('a.payment_status', 'paid');
        $this->db->where('a.status', 'approved');
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
        $this->db->select('a.id, a.kode_reimbust, a.tgl_pengajuan, a.sifat_pelaporan, SUM(b.jumlah) AS total_nominal, a.tujuan');
        $this->db->from('sam_reimbust AS a');
        $this->db->join('sam_reimbust_detail AS b', 'a.id = b.reimbust_id', 'inner');
        $this->db->join('sam_prepayment AS c', 'a.kode_prepayment = c.kode_prepayment', 'left');
        $this->db->group_start();
        // Standalone reimbust
        $this->db->group_start();
        $this->db->where('a.kode_prepayment', '');
        $this->db->where('a.payment_status', 'paid');
        $this->db->where('a.status', 'approved');
        $this->db->group_end();
        // Linked reimbust must have paid+approved prepayment
        $this->db->or_group_start();
        $this->db->where('a.kode_prepayment !=', '');
        $this->db->where('a.payment_status', 'paid');
        $this->db->where('a.status', 'approved');
        $this->db->where('c.payment_status', 'paid');
        $this->db->where('c.status', 'approved');
        $this->db->group_end();
        $this->db->group_end();
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

    function get_data_invoice($tgl_awal, $tgl_akhir)
    {
        $this->db->select('sam_invoice.id, sam_invoice.kode_invoice, sam_invoice.tgl_invoice, sam_invoice.tgl_tempo, sam_invoice.payment_status, sam_invoice.ctc_to, sam_invoice.total');
        $this->db->from('sam_invoice');
        $this->db->join('bmn_detail_invoice', 'sam_invoice.id = bmn_detail_invoice.invoice_id', 'left');
        $this->db->where('sam_invoice.payment_status', 1);

        if (!empty($tgl_awal) && !empty($tgl_akhir)) {
            $awal = date('Y-m-d', strtotime($tgl_awal));
            $akhir = date('Y-m-d', strtotime($tgl_akhir));

            $this->db->group_start();

            if ($tgl_awal == $tgl_akhir) {
                $this->db->where('sam_invoice.tgl_invoice =', $awal);
            } else {
                $this->db->where('sam_invoice.tgl_invoice >=', $awal);
                $this->db->where('sam_invoice.tgl_invoice <=', $akhir);
            }

            $this->db->group_end();
        }

        $this->db->group_by('sam_invoice.id');

        $query = $this->db->get();
        return $query->result();
    }
}
