<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_sam_rekapitulasi extends CI_Model
{
    var $id = 'id';
    var $table = 'sam_reimbust';
    var $table2 = 'sam_reimbust_detail';
    var $table3 = 'sam_prepayment';

    private $column_order = array();
    private $column_search = array();

    private function _get_tab()
    {
        return isset($_POST['tab']) && $_POST['tab'] !== '' ? $_POST['tab'] : 'pelaporan';
    }

    private function _apply_date_filter_pelaporan()
    {
        if (empty($_POST['awal']) || empty($_POST['akhir'])) {
            return;
        }

        $tgl_awal = date('Y-m-d', strtotime($_POST['awal']));
        $tgl_akhir = date('Y-m-d', strtotime($_POST['akhir']));

        $this->db->group_start();
        $this->db->group_start();
        $this->db->where('sam_reimbust.kode_prepayment IS NOT NULL', null, false);
        $this->db->where('sam_reimbust.tgl_pengajuan >=', $tgl_awal);
        $this->db->where('sam_reimbust.tgl_pengajuan <=', $tgl_akhir);
        $this->db->group_end();

        $this->db->or_group_start();
        $this->db->where('sam_reimbust.kode_prepayment IS NULL', null, false);
        $this->db->where('sam_prepayment.tgl_prepayment >=', $tgl_awal);
        $this->db->where('sam_prepayment.tgl_prepayment <=', $tgl_akhir);
        $this->db->group_end();
        $this->db->group_end();
    }

    private function _apply_date_filter_reimbust()
    {
        if (empty($_POST['awal']) || empty($_POST['akhir'])) {
            return;
        }

        $tgl_awal = date('Y-m-d', strtotime($_POST['awal']));
        $tgl_akhir = date('Y-m-d', strtotime($_POST['akhir']));
        $this->db->where('sam_reimbust.tgl_pengajuan >=', $tgl_awal);
        $this->db->where('sam_reimbust.tgl_pengajuan <=', $tgl_akhir);
    }

    private function _apply_date_filter_transport()
    {
        if (empty($_POST['awal']) || empty($_POST['akhir'])) {
            return;
        }

        $tgl_awal = date('Y-m-d', strtotime($_POST['awal']));
        $tgl_akhir = date('Y-m-d', strtotime($_POST['akhir']));
        $this->db->where('sam_reimbust.tgl_pengajuan >=', $tgl_awal);
        $this->db->where('sam_reimbust.tgl_pengajuan <=', $tgl_akhir);
    }

    private function _build_base_query()
    {
        $tab = $this->_get_tab();

        if ($tab === 'pelaporan') {
            $this->column_order = array(
                null,
                'sam_prepayment.kode_prepayment',
                'sam_reimbust.kode_reimbust',
                'tbl_data_user.name',
                'sam_prepayment.tujuan',
                'tgl_pengajuan',
                'total_pengeluaran'
            );
            $this->column_search = array(
                'sam_prepayment.kode_prepayment',
                'sam_reimbust.kode_reimbust',
                'tbl_data_user.name',
                'sam_prepayment.tujuan'
            );

            $this->db->select(
                'sam_reimbust.id,
                 sam_prepayment.id as prepayment_id,
                 sam_reimbust.kode_reimbust,
                 tbl_data_user.name,
                 sam_prepayment.tujuan,
                 IF(sam_reimbust.kode_prepayment IS NOT NULL, sam_reimbust.tgl_pengajuan, sam_prepayment.tgl_prepayment) AS tgl_pengajuan,
                 sam_prepayment.kode_prepayment,
                 sam_prepayment.total_nominal,
                 SUM(sam_reimbust_detail.jumlah) AS total_jumlah_detail,
                 COALESCE(SUM(sam_reimbust_detail.jumlah), sam_prepayment.total_nominal) AS total_pengeluaran'
            );
            $this->db->from('sam_prepayment');
            $this->db->join('sam_reimbust', 'sam_reimbust.kode_prepayment = sam_prepayment.kode_prepayment', 'left');
            $this->db->join('sam_reimbust_detail', 'sam_reimbust.id = sam_reimbust_detail.reimbust_id', 'left');
            $this->db->join('tbl_data_user', 'sam_prepayment.id_user = tbl_data_user.id_user', 'left');

            $this->db->group_start();
            $this->db->group_start();
            $this->db->where('sam_prepayment.payment_status', 'paid');
            $this->db->where('sam_reimbust.kode_prepayment IS NULL', null, false);
            $this->db->group_end();
            $this->db->or_group_start();
            $this->db->where('sam_prepayment.payment_status', 'paid');
            $this->db->where('sam_reimbust.kode_prepayment IS NOT NULL', null, false);
            $this->db->group_end();
            $this->db->or_group_start();
            $this->db->where('sam_reimbust.payment_status', 'paid');
            $this->db->where('sam_reimbust.kode_prepayment IS NOT NULL', null, false);
            $this->db->group_end();
            $this->db->group_end();

            $this->_apply_date_filter_pelaporan();

            $this->db->group_by(array('sam_prepayment.id', 'sam_prepayment.kode_prepayment'));
            return;
        }

        if ($tab === 'transport') {
            $this->column_order = array(
                null,
                null,
                'sam_reimbust.kode_reimbust',
                'tbl_data_user.name',
                'sam_reimbust.tujuan',
                'sam_reimbust.tgl_pengajuan',
                'total_pengeluaran'
            );
            $this->column_search = array(
                'sam_reimbust.kode_reimbust',
                'tbl_data_user.name',
                'sam_reimbust.tujuan'
            );

            $this->db->select(
                'sam_reimbust.id,
                 sam_reimbust.tgl_pengajuan,
                 tbl_data_user.name,
                 sam_reimbust.tujuan,
                 sam_reimbust.kode_reimbust,
                 sam_reimbust.kode_prepayment,
                 SUM(sam_reimbust_detail.jumlah) AS total_jumlah_detail,
                 COALESCE(SUM(sam_reimbust_detail.jumlah), 0) AS total_pengeluaran'
            );
            $this->db->from('sam_reimbust');
            $this->db->join('sam_reimbust_detail', 'sam_reimbust.id = sam_reimbust_detail.reimbust_id', 'left');
            $this->db->join('tbl_data_user', 'sam_reimbust.id_user = tbl_data_user.id_user', 'left');
            $this->db->where('sam_reimbust.payment_status', 'paid');
            $this->db->where('sam_reimbust.sifat_pelaporan', 'Transport');

            $this->_apply_date_filter_transport();

            $this->db->group_by('sam_reimbust.id');
            return;
        }

        // reimbust tab
        $this->column_order = array(
            null,
            null,
            'sam_reimbust.kode_reimbust',
            'tbl_data_user.name',
            'sam_reimbust.tujuan',
            'sam_reimbust.tgl_pengajuan',
            'total_pengeluaran'
        );
        $this->column_search = array(
            'sam_reimbust.kode_reimbust',
            'tbl_data_user.name',
            'sam_reimbust.tujuan'
        );

        $this->db->select(
            'sam_reimbust.id,
             sam_reimbust.tgl_pengajuan,
             tbl_data_user.name,
             sam_reimbust.tujuan,
             sam_reimbust.kode_reimbust,
             sam_reimbust.kode_prepayment,
             SUM(sam_reimbust_detail.jumlah) AS total_jumlah_detail,
             COALESCE(SUM(sam_reimbust_detail.jumlah), 0) AS total_pengeluaran'
        );
        $this->db->from('sam_reimbust');
        $this->db->join('sam_reimbust_detail', 'sam_reimbust.id = sam_reimbust_detail.reimbust_id', 'left');
        $this->db->join('tbl_data_user', 'sam_reimbust.id_user = tbl_data_user.id_user', 'left');
        $this->db->where('sam_reimbust.payment_status', 'paid');
        $this->db->where('sam_reimbust.kode_prepayment', '');
        $this->db->where('sam_reimbust.sifat_pelaporan', 'Reimbust');

        $this->_apply_date_filter_reimbust();

        $this->db->group_by('sam_reimbust.id');
    }

    private function _apply_search()
    {
        if (empty($_POST['search']['value'])) {
            return;
        }

        $searchValue = $_POST['search']['value'];
        $this->db->group_start();
        foreach ($this->column_search as $i => $item) {
            if ($i === 0) {
                $this->db->like($item, $searchValue);
            } else {
                $this->db->or_like($item, $searchValue);
            }
        }
        $this->db->group_end();
    }

    private function _apply_order()
    {
        if (isset($_POST['order'])) {
            $columnIndex = (int)$_POST['order']['0']['column'];
            $dir = $_POST['order']['0']['dir'];
            if (isset($this->column_order[$columnIndex]) && $this->column_order[$columnIndex]) {
                $this->db->order_by($this->column_order[$columnIndex], $dir);
                return;
            }
        }

        // Default order
        $tab = $this->_get_tab();
        if ($tab === 'reimbust' || $tab === 'transport') {
            $this->db->order_by('sam_reimbust.tgl_pengajuan', 'DESC');
        } else {
            $this->db->order_by('tgl_pengajuan', 'DESC');
        }
    }

    function _get_datatables_query()
    {
        $this->_build_base_query();
        $this->_apply_search();
        $this->_apply_order();
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
        $this->_build_base_query();
        $this->_apply_search();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->_build_base_query();
        $query = $this->db->get();
        return $query->num_rows();
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
        $this->db->from('sam_reimbust AS a');
        $this->db->join('sam_reimbust_detail AS b', 'a.id = b.reimbust_id', 'left');
        $this->db->where('a.payment_status', 'paid');
        $this->db->where('a.kode_prepayment', '');
        $this->db->where('a.sifat_pelaporan', 'Reimbust');

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

        // Total untuk transport
        $this->db->select('SUM(b.jumlah) AS total_nominal');
        $this->db->from('sam_reimbust AS a');
        $this->db->join('sam_reimbust_detail AS b', 'a.id = b.reimbust_id', 'left');
        $this->db->where('a.payment_status', 'paid');
        $this->db->where('a.sifat_pelaporan', 'Transport');

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

        $query_transport = $this->db->get()->row()->total_nominal;
        $total_transport = $query_transport != NULL ? $query_transport : 0;

        // Total keseluruhan dari pelaporan, reimbust, dan transport
        $total_keseluruhan = $total_prepayment + $total_pelaporan + $total_reimbust + $total_transport;

        $data = array(
            'total_prepayment' => $total_prepayment,
            'total_pelaporan' => $total_pelaporan,
            'total_reimbust' => $total_reimbust,
            'total_transport' => $total_transport,
            'total_pengeluaran' => $total_keseluruhan
        );

        return $data;
    }

    function get_data_prepayment($tgl_awal, $tgl_akhir)
    {
        $this->db->select('a.id, a.kode_prepayment, a.tgl_prepayment, a.prepayment, a.total_nominal');
        $this->db->from('sam_prepayment AS a');
        $this->db->join('sam_reimbust AS b', 'a.kode_prepayment = b.kode_prepayment', 'left');
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
        $this->db->from('sam_reimbust AS a');
        $this->db->join('sam_reimbust_detail AS b', 'a.id = b.reimbust_id', 'inner');
        $this->db->join('sam_prepayment AS c', 'a.kode_prepayment = c.kode_prepayment', 'left');
        $this->db->where('a.payment_status', 'paid');
        $this->db->or_where('c.payment_status', 'paid');
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
