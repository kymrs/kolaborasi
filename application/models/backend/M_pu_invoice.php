<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_pu_invoice extends CI_Model
{
    // INISIASI VARIABLE
    var $id = 'id';
    var $table = 'pu_invoice';
    var $table2 = 'pu_rek_invoice';
    var $column_order = array(null, null, 'tgl_invoice', 'kode_invoice', 'tgl_tempo', 'ctc_nama2', 'ctc_alamat');
    var $column_search = array('tgl_invoice', 'kode_invoice', 'tgl_tempo', 'ctc_nama2', 'ctc_alamat'); //field yang diizin untuk pencarian
    var $order = array('id' => 'desc');

    // UNTUK QUERY DATA TABLE
    function _get_datatables_query()
    {
        $this->db->select('*'); // Memilih kolom dari kedua tabel
        $this->db->from($this->table);
        // $this->db->join('tbl_data_user', 'tbl_data_user.id_user = pu_invoice.id_user', 'left'); // JOIN dengan tabel tbl_user

        $i = 0;

        foreach ($this->column_search as $item) // looping awal
        {
            if ($_POST['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
            {

                if ($i === 0) // looping awal
                {
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

    // UNTUK MENAMPILKAN HASIL QUERY KE DATA TABLES
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
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // GET BY ID TABLE INVOICE MASTER
    public function get_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('pu_invoice')->row();
    }

    // GET BY ID TABLE DETAIL INVOICE TRANSAKSI
    public function get_detail($id)
    {
        $this->db->where('invoice_id', $id);
        return $this->db->get('pu_detail_invoice')->result();
    }

    // GET BY ID TABLE DETAIL NOMOR REKENING
    public function get_rek($id)
    {
        $this->db->where('invoice_id', $id);
        return $this->db->get('pu_rek_invoice')->result();
    }

    public function getInvoiceData($id)
    {
        $this->db->select('tgl_invoice, kode_invoice, diskon, tgl_tempo, ctc_nama1, ctc_nomor1, ctc_nama2, ctc_nomor2, ctc_alamat, keterangan');
        $this->db->from('pu_invoice');
        $this->db->where('id', $id);
        $data = $this->db->get()->row_array();
        return $data;
    }

    // UNTUK QUERY MENGAMBIL KODE UNTUK DIGENERATE DI CONTROLLER
    public function max_kode($date)
    {
        $formatted_date = date('ym', strtotime($date));
        $this->db->select('kode_invoice');
        $where = 'id=(SELECT max(id) FROM pu_invoice where SUBSTRING(kode_invoice, 6, 4) = ' . $formatted_date . ')';
        $this->db->where($where);
        $this->db->from('pu_invoice');
        $query = $this->db->get();
        return $query;
    }

    // UNTUK QUERY MENENTUKAN SIAPA YANG MELAKUKAN APPROVAL
    public function approval($id)
    {
        $this->db->select('app_id, app2_id');
        $this->db->from('tbl_data_user');
        $this->db->where('id_user', $id);
        $query = $this->db->get();
        return $query->row();
    }

    // UNTUK QUERY INSERT DATA PREPAYMENT
    public function save($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    // UNTUK QUERY INSERT DATA KE PU_REK_INVOICE
    public function save_detail($data)
    {
        $this->db->insert_batch($this->table2, $data);
        return $this->db->insert_id();
    }

    // UNTUK QUERY INSERT DATA KE PU_DETAIL_INVOICE
    public function save_detail2($data)
    {
        $this->db->insert_batch('pu_detail_invoice', $data);
        return $this->db->insert_id();
    }

    // UNTUK QUERY DELETE DATA PREPAYMENT
    public function delete($id)
    {
        // Hapus data master
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);

        // Hapus data rekening
        $this->db->where('invoice_id', $id);
        $this->db->delete('pu_rek_invoice');

        // Hapus data detail
        $this->db->where('invoice_id', $id);
        $this->db->delete('pu_detail_invoice');
    }

    // OPSI REKENING
    public function options()
    {
        return $this->db->distinct()->select('nama_bank, no_rek')->from('pu_rek_invoice')->get();
    }
}