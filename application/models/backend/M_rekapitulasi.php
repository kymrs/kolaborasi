<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_rekapitulasi extends CI_Model
{
    var $id = 'id';
    var $table = 'tbl_reimbust_pu';
    var $table2 = 'tbl_reimbust_detail_pu';
    var $table3 = 'tbl_prepayment_pu';
    // Kolom yang bisa diurutkan (ORDER BY) berdasarkan query UNION
    var $column_order = array(null, 'tgl_pengajuan', 'name', 'tujuan', 'kode_reimbust', 'kode_prepayment', 'total_nominal');

    // Kolom yang bisa dicari (SEARCH)
    var $column_search = array('tgl_pengajuan', 'name', 'tujuan', 'kode_reimbust', 'kode_prepayment');
    // var $order = array('tbl_reimbust_pu.tgl_pengajuan' => 'ASC');

    function _get_datatables_query()
    {
        // Tambahkan kondisi berdasarkan tab yang dipilih
        if (!empty($_POST['tab'])) {
            if ($_POST['tab'] == 'pelaporan') {
                //PELAPORAN TABS
                $this->db->select('tbl_reimbust_pu.id, 
                   tbl_prepayment_pu.id as prepayment_id, 
                   tbl_reimbust_pu.kode_reimbust, 
                   tbl_data_user.name, 
                   tbl_prepayment_pu.tujuan, 
                   tbl_reimbust_pu.tgl_pengajuan, 
                   tbl_prepayment_pu.tgl_prepayment, 
                   tbl_prepayment_pu.kode_prepayment, 
                   tbl_prepayment_pu.total_nominal, 
                   SUM(tbl_reimbust_detail_pu.jumlah) AS total_jumlah_detail');
                $this->db->from('tbl_prepayment_pu');
                $this->db->join('tbl_reimbust_pu', 'tbl_reimbust_pu.kode_prepayment = tbl_prepayment_pu.kode_prepayment', 'left');
                $this->db->join('tbl_reimbust_detail_pu', 'tbl_reimbust_pu.id = tbl_reimbust_detail_pu.reimbust_id', 'left');
                $this->db->join('tbl_data_user', 'tbl_prepayment_pu.id_user = tbl_data_user.id_user', 'left');

                // Filter berdasarkan range tanggal
                if (!empty($_POST['awal']) && !empty($_POST['akhir'])) {
                    $tgl_awal = date('Y-m-d', strtotime($_POST['awal']));
                    $tgl_akhir = date('Y-m-d', strtotime($_POST['akhir']));

                    // Filter berdasarkan range tanggal
                    $this->db->group_start(); // Memulai grup kondisi
                    $this->db->where('tbl_reimbust_pu.tgl_pengajuan >=', $tgl_awal);
                    $this->db->or_where('tbl_prepayment_pu.tgl_prepayment >=', $tgl_awal);
                    $this->db->group_end(); // Menutup grup kondisi

                    $this->db->group_start(); // Memulai grup kondisi
                    $this->db->where('tbl_reimbust_pu.tgl_pengajuan <=', $tgl_akhir);
                    $this->db->or_where('tbl_prepayment_pu.tgl_prepayment <=', $tgl_akhir);
                    $this->db->group_end(); // Menutup grup kondisi
                }

                $this->db->group_by(array('tbl_prepayment_pu.id', 'tbl_prepayment_pu.kode_prepayment', 'tbl_prepayment_pu.tgl_prepayment'));
            } elseif ($_POST['tab'] == 'reimbust') {
                //TABS REINBUST
                $this->db->select('tbl_reimbust_pu.id, tbl_reimbust_pu.tgl_pengajuan, tbl_data_user.name, tbl_reimbust_pu.tujuan, tbl_reimbust_pu.kode_reimbust, tbl_reimbust_pu.kode_prepayment, SUM(tbl_reimbust_detail_pu.jumlah) AS total_jumlah_detail');
                $this->db->from('tbl_reimbust_pu');
                $this->db->join('tbl_reimbust_detail_pu', 'tbl_reimbust_pu.id = tbl_reimbust_detail_pu.reimbust_id');
                $this->db->join('tbl_data_user', 'tbl_reimbust_pu.id_user = tbl_data_user.id_user');
                $this->db->where('tbl_reimbust_pu.kode_prepayment', '');
                // Filter berdasarkan range tanggal
                if (!empty($_POST['awal']) && !empty($_POST['akhir'])) {
                    $tgl_awal = date('Y-m-d', strtotime($_POST['awal']));
                    $tgl_akhir = date('Y-m-d', strtotime($_POST['akhir']));

                    // Filter berdasarkan range tanggal
                    $this->db->where('tbl_reimbust_pu.tgl_pengajuan >=', $tgl_awal);
                    $this->db->where('tbl_reimbust_pu.tgl_pengajuan <=', $tgl_akhir);
                }
                $this->db->group_by('tbl_reimbust_pu.id, tbl_reimbust_pu.kode_reimbust, tbl_reimbust_pu.tgl_pengajuan');
            }
        }

        // Query pertama (LEFT JOIN dengan tbl_prepayment_pu dan tbl_reimbust_pu, dengan kondisi kode_reimbust IS NULL)
        // $this->db->select('tbl_prepayment_pu.id, tbl_prepayment_pu.kode_prepayment, tbl_prepayment_pu.tgl_prepayment, tbl_prepayment_pu.total_nominal');
        // $this->db->from('tbl_prepayment_pu');
        // $this->db->join('tbl_reimbust_pu', 'tbl_prepayment_pu.kode_prepayment = tbl_reimbust_pu.kode_prepayment', 'left');
        // $this->db->where('tbl_reimbust_pu.kode_reimbust IS NULL', null, false);
        // $query1 = $this->db->get_compiled_select(); // Compile query pertama

        // // // Query kedua (JOIN tbl_reimbust_pu dan tbl_reimbust_detail_pu)
        // $this->db->select('tbl_reimbust_pu.id, tbl_reimbust_pu.kode_reimbust, tbl_reimbust_pu.tgl_pengajuan, SUM(tbl_reimbust_detail_pu.jumlah) AS total_jumlah_detail');
        // $this->db->from('tbl_reimbust_pu');
        // $this->db->join('tbl_reimbust_detail_pu', 'tbl_reimbust_pu.id = tbl_reimbust_detail_pu.reimbust_id');
        // $this->db->group_by('tbl_reimbust_pu.id');
        // $query2 = $this->db->get_compiled_select(); // Compile query kedua

        // Menggabungkan kedua query dengan UNION
        // $union_query = "($query1 UNION $query2) as unionTable";

        // Menggunakan query gabungan untuk SELECT di DataTables
        // $this->db->query("($query1 UNION $query2) as unionTable");

        $i = 0;
        foreach ($this->column_search as $item) {
            if ($_POST['search']['value']) {
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if (count($this->column_search) - 1 == $i)
                    $this->db->group_end();
            }
            $i++;
        }

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
        if (!empty($_POST['tab'])) {
            if ($_POST['tab'] == 'pelaporan') {
                //PELAPORAN TABS
                $this->db->select('tbl_reimbust_pu.id, 
                                    tbl_reimbust_pu.kode_reimbust, 
                                    tbl_data_user.name, 
                                    tbl_reimbust_pu.tujuan, 
                                    tbl_reimbust_pu.tgl_pengajuan, 
                                    tbl_prepayment_pu.tgl_prepayment, 
                                    tbl_reimbust_pu.kode_prepayment, 
                                    tbl_reimbust_pu.jumlah_prepayment, 
                                    SUM(tbl_reimbust_detail_pu.jumlah) AS total_jumlah_detail');
                $this->db->from('tbl_reimbust_pu');
                $this->db->join('tbl_reimbust_detail_pu', 'tbl_reimbust_pu.id = tbl_reimbust_detail_pu.reimbust_id');
                $this->db->join('tbl_data_user', 'tbl_reimbust_pu.id_user = tbl_data_user.id_user');
                $this->db->join('tbl_prepayment_pu', 'tbl_reimbust_pu.kode_prepayment = tbl_prepayment_pu.kode_prepayment', 'left');

                // Filter berdasarkan range tanggal
                if (!empty($_POST['awal']) && !empty($_POST['akhir'])) {
                    $tgl_awal = date('Y-m-d', strtotime($_POST['awal']));
                    $tgl_akhir = date('Y-m-d', strtotime($_POST['akhir']));

                    // Filter berdasarkan range tanggal
                    $this->db->where('tbl_reimbust_pu.tgl_pengajuan >=', $tgl_awal);
                    $this->db->where('tbl_reimbust_pu.tgl_pengajuan <=', $tgl_akhir);
                }

                $this->db->group_by(array('tbl_reimbust_pu.id', 'tbl_reimbust_pu.kode_prepayment', 'tbl_reimbust_pu.jumlah_prepayment'));
            } elseif ($_POST['tab'] == 'reimbust') {
                //TABS REINBUST
                $this->db->select('tbl_reimbust_pu.id', 'tbl_reimbust_pu.tgl_pengajuan', 'tbl_reimbust_pu.tujuan', 'tbl_reimbust_pu.kode_reimbust', 'tbl_reimbust_pu.kode_prepayment', 'SUM(tbl_reimbust_detail_pu.jumlah) AS total_jumlah_detail');
                $this->db->from('tbl_reimbust_pu');
                $this->db->join('tbl_reimbust_detail_pu', 'tbl_reimbust_pu.id = tbl_reimbust_detail_pu.reimbust_id');
                $this->db->where('tbl_reimbust_pu.kode_prepayment', '');
                // Filter berdasarkan range tanggal
                if (!empty($_POST['awal']) && !empty($_POST['akhir'])) {
                    $tgl_awal = date('Y-m-d', strtotime($_POST['awal']));
                    $tgl_akhir = date('Y-m-d', strtotime($_POST['akhir']));

                    // Filter berdasarkan range tanggal
                    $this->db->where('tbl_reimbust_pu.tgl_pengajuan >=', $tgl_awal);
                    $this->db->where('tbl_reimbust_pu.tgl_pengajuan <=', $tgl_akhir);
                }
                $this->db->group_by(array('tbl_reimbust_pu.id', 'tbl_reimbust_pu.kode_reimbust', 'tbl_reimbust_pu.tgl_pengajuan'));
            }
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
}
