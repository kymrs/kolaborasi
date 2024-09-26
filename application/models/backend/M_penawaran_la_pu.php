<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_penawaran_la_pu extends CI_Model
{
    // INISIASI VARIABLE
    var $id = 'id';
    var $table = 'tbl_penawaran';
    var $table2 = 'tbl_produk';
    var $column_order = array(null, null, 'no_pelayanan', 'pelanggan', 'tgl_berlaku', 'nama', 'created_at', 'catatan');
    var $column_search = array('no_pelayanan', 'pelanggan', 'tgl_berlaku', 'nama', 'created_at', 'catatan'); //field yang diizin untuk pencarian
    var $order = array('id' => 'desc');

    // UNTUK QUERY DATA TABLE
    function _get_datatables_query()
    {
        $this->db->select('tbl_penawaran.*, tbl_produk.nama'); // Memilih kolom dari kedua tabel
        $this->db->from($this->table);
        $this->db->join('tbl_produk', 'tbl_produk.id = tbl_penawaran.id_produk', 'left'); // JOIN dengan tabel tbl_user

        $i = 0;

        foreach ($this->column_search as $item) // looping awal
        {
            if ($_POST['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
            {

                if ($i === 0) // looping awal
                {
                    $this->db->group_start();
                    if ($item == 'nama') {
                        $this->db->like('tbl_produk.' . $item, $_POST['search']['value']);
                    } else {
                        $this->db->like('tbl_penawaran.' . $item, $_POST['search']['value']);
                    }
                } else {
                    if ($item == 'nama') {
                        $this->db->or_like('tbl_produk.' . $item, $_POST['search']['value']);
                    } else {
                        $this->db->or_like('tbl_penawaran.' . $item, $_POST['search']['value']);
                    }
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
        $this->db->select('tbl_penawaran.*, tbl_produk.nama'); // Memilih kolom dari kedua tabel
        $this->db->from($this->table);
        $this->db->join('tbl_produk', 'tbl_produk.id = tbl_penawaran.id_produk', 'left'); // JOIN dengan tabel tbl_user

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


    public function getPenawaran($kode)
    {
        $this->db->select('a.no_pelayanan, a.no_arsip, a.tgl_berlaku, a.id_produk, a.pelanggan, a.catatan, a.created_at, b.nama_dokumen, b.penerbit, b.no_dokumen, b.tgl_dokumen, c.nama as nama_produk, c.layanan_termasuk, c.layanan_tdk_termasuk, c.keberangkatan, c.durasi, c.tempat_keberangkatan, c.biaya, c.created_at');
        $this->db->from('tbl_penawaran as a');
        $this->db->where('a.no_arsip', $kode);
        $this->db->join('tbl_arsip_pu as b', 'a.no_pelayanan = b.no_dokumen', 'left');
        $this->db->join('tbl_produk as c', 'a.id_produk = c.id', 'left');
        // $this->db->where('id', $id);
        $data = $this->db->get()->row_array();
        return $data;
    }
    public function max_kode($date)
    {
        $formatted_date = date('Y', strtotime($date));
        $this->db->select('no_pelayanan, no_arsip');
        $where = 'id=(SELECT max(id) FROM tbl_penawaran where SUBSTRING(no_pelayanan, 17, 4) = ' . $formatted_date . ')';
        $this->db->where($where);
        $query = $this->db->from('tbl_penawaran')->get();
        return $query;
    }

    public function save($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }
}