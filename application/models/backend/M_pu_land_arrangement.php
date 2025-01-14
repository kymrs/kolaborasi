<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_pu_land_arrangement extends CI_Model
{
    // INISIASI VARIABLE
    var $id = 'id';
    var $table = 'pu_land_arrangement';
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
                    $this->db->like('pu_land_arrangement.' . $item, $_POST['search']['value']);
                } else {
                    $this->db->or_like('pu_land_arrangement.' . $item, $_POST['search']['value']);
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
        $data = $this->db->from('pu_land_arrangement')->where('id', $id)->get()->row();
        return $data;
    }

    public function get_rundown($no_pelayanan)
    {
        $data = $this->db->from('pu_rundown')->where('no_pelayanan', $no_pelayanan)->get()->result();
        return $data;
    }

    public function getHotel($id)
    {
        $this->db->select('a.nama_hotel, a.rating, a.kota, a.negara');
        $this->db->from('pu_hotel as a');
        $this->db->join('pu_land_arrangement_htl as b', 'a.id = b.id_hotel', 'left');
        $this->db->where('b.id_la', $id);
        $data = $this->db->get()->result_array();
        return $data;
    }

    public function max_kode($date)
    {
        $formatted_date = date('Y', strtotime($date));
        $this->db->select('no_pelayanan');
        $where = 'id=(SELECT max(id) FROM pu_land_arrangement where SUBSTRING(no_pelayanan, -4, 4) = ' . $formatted_date . ')';
        $this->db->where($where);
        $query = $this->db->from('pu_land_arrangement')->get();
        return $query;
    }

    public function max_kode_arsip($date)
    {
        $formatted_date = date('y', strtotime($date));
        $this->db->select('no_arsip');
        $where = 'id=(SELECT max(id) FROM pu_arsip where SUBSTRING(no_arsip, 3, 2) = ' . $formatted_date . ')';
        $this->db->where($where);
        $query = $this->db->from('pu_arsip')->get();
        return $query;
    }

    public function save($data)
    {
        $this->db->insert('pu_land_arrangement', $data);
        return $this->db->insert_id();
    }

    public function save_rundown($data)
    {
        $this->db->insert_batch('pu_rundown', $data);
        return $this->db->insert_id();
    }

    public function save_arsip($data)
    {
        $this->db->insert('pu_arsip', $data);
        return $this->db->insert_id();
    }

    public function save_hotel($data)
    {
        // Cek apakah array $data tidak kosong
        if (!empty($data)) {
            // Menggunakan insert_batch untuk memasukkan banyak baris data sekaligus
            $this->db->insert_batch('pu_land_arrangement_htl', $data);

            // Cek apakah ada kesalahan pada saat insert
            if ($this->db->affected_rows() > 0) {
                return TRUE;
            } else {
                log_message('error', 'Insert to pu_land_arrangement_htl failed: ' . $this->db->last_query());
                return FALSE;
            }
        } else {
            log_message('error', 'Empty data array in insert_penawaran_detail');
            return FALSE;
        }
    }

    public function get_hotels($id_la)
    {
        $this->db->select('b.id, b.nama_hotel, b.rating, b.kota, b.negara');
        $this->db->from('pu_land_arrangement_htl AS a');
        $this->db->join('pu_hotel AS b', 'a.id_hotel = b.id'); // Kondisi ON untuk join
        $this->db->where('a.id_la', $id_la); // Pastikan $id_la terdefinisi
        $query = $this->db->get();

        // Mengembalikan hasil dalam bentuk array objek
        return $query->result();
    }

    public function get_la_hotel($id_la)
    {
        $this->db->select('id_la, id_hotel, is_active');
        $this->db->from('pu_land_arrangement_htl');
        $this->db->where('id_la', $id_la);
        $query = $this->db->get();

        // Mengembalikan hasil dalam bentuk array objek
        return $query->result_array();
    }
}
