<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_penawaran_pu extends CI_Model
{
    // INISIASI VARIABLE
    var $id = 'id';
    var $table = 'tbl_penawaran';
    var $column_order = array(null, null, 'no_pelayanan', 'no_arsip', 'pelanggan', 'tgl_keberangkatan', 'durasi', 'created_at', 'tgl_berlaku');
    var $column_search = array('no_pelayanan', 'no_arsip', 'pelanggan', 'tgl_keberangkatan', 'durasi', 'created_at', 'tgl_berlaku'); //field yang diizin untuk pencarian
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
                    $this->db->like('tbl_penawaran.' . $item, $_POST['search']['value']);
                } else {
                    $this->db->or_like('tbl_penawaran.' . $item, $_POST['search']['value']);
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

    public function getPenawaranById($id)
    {
        $data = $this->db->from('tbl_penawaran')->where('id', $id)->get()->row();
        return $data;
    }

    public function getRundown($no_pelayanan)
    {
        $data = $this->db->from('tbl_rundown')->where('no_pelayanan', $no_pelayanan)->get()->result_array();
        return $data;
    }

    public function getPenawaran($kode)
    {
        $this->db->select('no_pelayanan, a.no_arsip, tgl_berlaku, produk, pelanggan, deskripsi, tgl_keberangkatan, durasi, keberangkatan, kepulangan, berangkat_dari, pkt_quad, pkt_triple, pkt_double, created_at');
        $this->db->from('tbl_penawaran as a');
        $this->db->where('a.no_arsip', $kode);
        $this->db->join('tbl_arsip_pu as b', 'a.no_pelayanan = b.no_dokumen', 'left');
        // $this->db->where('id', $id);
        $data = $this->db->get()->row_array();
        return $data;
    }

    public function max_kode($date)
    {
        $formatted_date = date('Y', strtotime($date));
        $this->db->select('no_pelayanan');
        $where = 'id=(SELECT max(id) FROM tbl_penawaran where SUBSTRING(no_pelayanan, 15, 4) = ' . $formatted_date . ')';
        $this->db->where($where);
        $query = $this->db->from('tbl_penawaran')->get();
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
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function insert_penawaran_detail($data)
    {
        // Cek apakah array $data tidak kosong
        if (!empty($data)) {
            // Menggunakan insert_batch untuk memasukkan banyak baris data sekaligus
            $this->db->insert_batch('tbl_penawaran_detail_lyn', $data);

            // Cek apakah ada kesalahan pada saat insert
            if ($this->db->affected_rows() > 0) {
                return TRUE;
            } else {
                log_message('error', 'Insert to tbl_penawaran_detail_lyn failed: ' . $this->db->last_query());
                return FALSE;
            }
        } else {
            log_message('error', 'Empty data array in insert_penawaran_detail');
            return FALSE;
        }
    }

    public function insert_penawaran_detail2($data)
    {
        // Cek apakah array $data tidak kosong
        if (!empty($data)) {
            // Menggunakan insert_batch untuk memasukkan banyak baris data sekaligus
            $this->db->insert_batch('tbl_penawaran_detail_htl', $data);

            // Cek apakah ada kesalahan pada saat insert
            if ($this->db->affected_rows() > 0) {
                return TRUE;
            } else {
                log_message('error', 'Insert to tbl_penawaran_detail_htl failed: ' . $this->db->last_query());
                return FALSE;
            }
        } else {
            log_message('error', 'Empty data array in insert_penawaran_detail');
            return FALSE;
        }
    }

    public function getLayananTermasuk($kode)
    {
        $this->db->select('a.nama_layanan, b.id_penawaran, b.id_layanan, b.is_active');
        $this->db->from('tbl_layanan as a');
        $this->db->join('tbl_penawaran_detail_lyn as b', 'a.id = b.id_layanan', 'left');
        $this->db->join('tbl_penawaran as c', 'b.id_penawaran = c.id', 'left');
        $this->db->where('b.is_active', 'Y');
        $this->db->where('c.no_arsip', $kode);
        // $this->db->join('tbl_produk as c', 'a.id_produk = c.id', 'left');
        $data = $this->db->get()->result_array();
        return $data;
    }

    public function getLayananTidakTermasuk($kode)
    {
        $this->db->select('a.nama_layanan, b.id_penawaran, b.id_layanan, b.is_active');
        $this->db->from('tbl_layanan as a');
        $this->db->join('tbl_penawaran_detail_lyn as b', 'a.id = b.id_layanan', 'left');
        $this->db->join('tbl_penawaran as c', 'b.id_penawaran = c.id', 'left');
        $this->db->where("(b.is_active = 'N' OR (b.is_active LIKE 'N%' AND LENGTH(b.is_active) > 1))");
        $this->db->where('c.no_arsip', $kode);
        $data = $this->db->get()->result_array();
        return $data;
    }

    public function getHotel($kode)
    {
        $this->db->select('a.nama_hotel, a.rating, a.kota, a.negara');
        $this->db->from('tbl_hotel_pu as a');
        $this->db->join('tbl_penawaran_detail_htl as b', 'a.id = b.id_hotel', 'left');
        $this->db->join('tbl_penawaran as c', 'c.id = b.id_penawaran', 'left');
        $this->db->where('c.no_arsip', $kode);
        $data = $this->db->get()->result_array();
        return $data;
    }

    // Fungsi untuk mengambil detail penawaran berdasarkan ID penawaran
    public function get_penawaran_detail($id_penawaran)
    {
        $this->db->select('id_penawaran, id_layanan, is_active');
        $this->db->from('tbl_penawaran_detail_lyn');
        $this->db->where('id_penawaran', $id_penawaran);
        $query = $this->db->get();

        // Mengembalikan hasil dalam bentuk array objek
        return $query->result_array();
    }

    public function get_penawaran_detail2($id_penawaran)
    {
        $this->db->select('id_penawaran, id_hotel, is_active');
        $this->db->from('tbl_penawaran_detail_htl');
        $this->db->where('id_penawaran', $id_penawaran);
        $query = $this->db->get();

        // Mengembalikan hasil dalam bentuk array objek
        return $query->result_array();
    }

    public function save_rundown($data)
    {
        $this->db->insert_batch('tbl_rundown', $data);
        return $this->db->insert_id();
    }

    public function get_hotels($id_penawaran)
    {
        $this->db->select('b.id, b.nama_hotel, b.rating, b.kota, b.negara');
        $this->db->from('tbl_land_arrangement_htl AS a');
        $this->db->join('tbl_hotel_pu AS b', 'a.id_hotel = b.id'); // Kondisi ON untuk join
        $this->db->where('a.id_penawaran', $id_penawaran); // Pastikan $id_penawaran terdefinisi
        $query = $this->db->get();

        // Mengembalikan hasil dalam bentuk array objek
        return $query->result();
    }
}
