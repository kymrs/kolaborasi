<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_penawaran_la_pu extends CI_Model
{
    // INISIASI VARIABLE
    var $id = 'id';
    var $table = 'tbl_land_arrangement';
    var $column_order = array(null, null, 'no_pelayanan', 'no_arsip', 'produk', 'tgl_berlaku', 'keberangkatan', 'durasi', 'tempat', 'biaya', 'pelanggan', 'created_at');
    var $column_search = array('no_pelayanan', 'no_arsip', 'produk', 'tgl_berlaku', 'keberangkatan', 'durasi', 'tempat', 'biaya', 'pelanggan', 'created_at'); //field yang diizin untuk pencarian
    var $order = array('id' => 'desc');

    // UNTUK QUERY DATA TABLE
    function _get_datatables_query()
    {
        $this->db->from($this->table);

        $i = 0;

        foreach ($this->column_search as $item) // looping awal
        {
            if ($_POST['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
            {

                if ($i === 0) // looping awal
                {
                    $this->db->group_start();
                    $this->db->like('tbl_land_arrangement.' . $item, $_POST['search']['value']);
                } else {
                    $this->db->or_like('tbl_land_arrangement.' . $item, $_POST['search']['value']);
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

    // Fungsi untuk format tanggal dalam bahasa Indonesia
    private function format_tanggal_indo($tanggal)
    {
        $bulan = array(
            1 => 'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );

        // Pisahkan tanggal menjadi array
        $tanggal_pisah = explode('-', $tanggal);
        $tahun = $tanggal_pisah[0];
        $bulan_indonesia = $bulan[(int)$tanggal_pisah[1]];
        $hari = $tanggal_pisah[2];

        // Return format "tanggal bulan tahun"
        return $hari . ' ' . $bulan_indonesia . ' ' . $tahun;
    }

    // Fungsi yang memanggil format_tanggal_indo berkali-kali
    public function getTanggal($tanggalValue)
    {
        // Hilangkan waktu jika ada, dengan memotong hanya bagian tanggal
        $tanggal_only = explode(' ', $tanggalValue)[0]; // Pisahkan tanggal dari waktu

        // Konversi ke format "tanggal bulan tahun" bahasa Indonesia
        $formatted_tgl = $this->format_tanggal_indo($tanggal_only);

        return $formatted_tgl;
    }

    // Fungsi lain yang juga bisa memanggil format_tanggal_indo
    public function anotherMethod($tanggalValue)
    {
        $formatted_tgl = $this->format_tanggal_indo($tanggalValue);
        return $formatted_tgl;
    }


    public function getPenawaran($id)
    {
        $data = $this->db->from('tbl_land_arrangement')->where('id', $id)->get()->row();
        return $data;
    }

    public function max_kode($date)
    {
        $formatted_date = date('Y', strtotime($date));
        $this->db->select('no_pelayanan');
        $where = 'id=(SELECT max(id) FROM tbl_land_arrangement where SUBSTRING(no_pelayanan, 17, 4) = ' . $formatted_date . ')';
        $this->db->where($where);
        $query = $this->db->from('tbl_land_arrangement')->get();
        return $query;
    }

    public function max_kode_arsip($date)
    {
        $formatted_date = date('y', strtotime($date));
        $this->db->select('no_arsip');
        $where = 'id=(SELECT max(id) FROM tbl_arsip_pu where SUBSTRING(no_arsip, 3, 2) = ' . $formatted_date . ')';
        $this->db->where($where);
        $query = $this->db->from('tbl_arsip_pu')->get();
        return $query;
    }

    public function save($data)
    {
        $this->db->insert('tbl_land_arrangement', $data);
        return $this->db->insert_id();
    }

    public function save_arsip($data)
    {
        $this->db->insert('tbl_arsip_pu', $data);
        return $this->db->insert_id();
    }
}
