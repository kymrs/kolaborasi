<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_qbg_invoice extends CI_Model
{
    // INISIASI VARIABLE
    var $id = 'id';
    var $table = 'qbg_invoice';
    var $table2 = 'qbg_rek_invoice';
    var $column_order = array(null, null, 'kode_invoice', 'nama_customer', 'nomor_customer', 'email');
    var $column_search = array('kode_invoice', 'nama_customer', 'nomor_customer', 'email'); //field yang diizin untuk pencarian
    var $order = array('id' => 'desc');

    // Deklarasi
    var $table3 = 'qbg_produk'; //nama tabel dari database
    var $column_order2 = array(null, null, 'kode_produk', 'nama_produk', 'berat', 'satuan', 'stok_akhir');
    var $column_search2 = array('kode_produk', 'nama_produk', 'berat', 'satuan', 'stok_akhir'); //field yang diizin untuk pencarian 
    var $order2 = array('id' => 'desc'); // default order 

    // UNTUK QUERY DATA TABLE
    private function _get_datatables_query($status = 'unpaid')
    {
        $this->db->where('payment_status', $status); // Pakai status dari parameter
        $this->db->select();
        $this->db->from($this->table);

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

        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    // UNTUK MENAMPILKAN HASIL QUERY KE DATA TABLES
    function get_datatables($status)
    {
        $this->_get_datatables_query($status);
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

    // Data Deklarasi
    private function _get_datatables_query2()
    {

        $this->db->select();
        $this->db->from($this->table3);

        $i = 0;

        foreach ($this->column_search2 as $item) // looping awal
        {
            if ($_POST['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
            {

                foreach ($this->column_search2 as $item) // looping awal
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

                        if (count($this->column_search2) - 1 == $i)
                            $this->db->group_end();
                    }
                    $i++;
                }

                if (count($this->column_search2) - 1 == $i)
                    $this->db->group_end();
            }
            $i++;
        }

        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order2[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order2)) {
            $order = $this->order2;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables2()
    {
        $this->_get_datatables_query2();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered2()
    {
        $this->_get_datatables_query2();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all2()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // GET BY ID TABLE INVOICE MASTER
    public function get_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('qbg_invoice')->row();
    }

    // GET BY ID TABLE DETAIL INVOICE TRANSAKSI
    public function get_detail($id)
    {
        $this->db->where('invoice_id', $id);
        return $this->db->get('qbg_detail_invoice')->result();
    }

    // GET BY ID TABLE DETAIL NOMOR REKENING
    public function get_rek($id)
    {
        $this->db->where('invoice_id', $id);
        return $this->db->get('qbg_rek_invoice')->result();
    }

    public function getInvoiceData($id)
    {
        $this->db->select('tgl_invoice, kode_invoice, tgl_tempo, nama_customer, nomor_customer, email_customer, alamat_customer, jenis_harga, potongan_harga, potongan_harga, ongkir, total, grand_total, keterangan');
        $this->db->from('qbg_invoice');
        $this->db->where('id', $id);
        $data = $this->db->get()->row_array();
        return $data;
    }

    // UNTUK QUERY MENGAMBIL KODE UNTUK DIGENERATE DI CONTROLLER
    public function max_kode($date)
    {
        $formatted_date = date('ym', strtotime($date));
        $this->db->select('kode_invoice');
        $where = 'id=(SELECT max(id) FROM qbg_invoice where SUBSTRING(kode_invoice, 2, 4) = ' . $formatted_date . ')';
        $this->db->where($where);
        $this->db->from('qbg_invoice');
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

    // UNTUK QUERY INSERT DATA KE qbg_rek_invoice
    public function save_detail($data)
    {
        $this->db->insert_batch($this->table2, $data);
        return $this->db->insert_id();
    }

    // UNTUK QUERY INSERT DATA KE qbg_detail_invoice
    public function save_detail2($data)
    {
        $this->db->insert_batch('qbg_detail_invoice', $data);
        return $this->db->insert_id();
    }

    // UNTUK QUERY DELETE DATA PREPAYMENT
    public function delete($id)
    {
        // Hapus data rekening
        $this->db->where('invoice_id', $id);
        $this->db->delete('qbg_rek_invoice');

        // Ambil data kode produk dan jumlah berdasarkan id pada tabel detail invoice
        $query = $this->db->select('kode_produk, jumlah')
            ->where('invoice_id', $id) // Perbaikan: id_invoice, bukan id
            ->get('qbg_detail_invoice')
            ->result_array();

        foreach ($query as $row) {
            $kode_produk = $row['kode_produk'];
            $jumlah = $row['jumlah'];

            // Ambil stok terakhir dari tabel produk
            $query2 = $this->db->select('stok_akhir')
                ->where('kode_produk', $kode_produk)
                ->get('qbg_produk')
                ->row_array();

            if ($query2) { // Pastikan data produk ada
                $stok_akhir = $query2['stok_akhir'];
                $total = $stok_akhir + $jumlah;

                // Update stok di tabel produk
                $this->db->where('kode_produk', $kode_produk);
                $this->db->update('qbg_produk', ['stok_akhir' => $total]); // Perbaikan di kolom yang diupdate
            }
        }

        // Hapus data detail invoice
        $this->db->where('invoice_id', $id);
        $this->db->delete('qbg_detail_invoice');

        // Ambil data kode invoice pada tabel invoice
        $query3 = $this->db->select('kode_invoice')->where('id', $id)->row_array();
        $kode_invoice = $query3['kode_invoice'];

        $this->db->where('keterangan', $kode_invoice);
        $this->db->delete('qbg_transaksi');

        // Hapus data master invoice
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
    }

    // OPSI REKENING
    public function options()
    {
        return $this->db->distinct()->select('nama_bank, no_rek')->from('qbg_rek_invoice')->get();
    }
}
