<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_swi_penawaran extends CI_Model
{
    // INISIASI VARIABLE
    var $id = 'id';
    var $table = 'swi_penawaran';
    var $column_order = array(null, null, 'kode', 'name', 'asal', 'tujuan', 'kendaraan', 'created_at');
    var $column_search = array('kode', 'name', 'asal', 'tujuan', 'kendaraan', 'created_at'); //field yang diizin untuk pencarian
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
                    $this->db->like('swi_penawaran.' . $item, $_POST['search']['value']);
                } else {
                    $this->db->or_like('swi_penawaran.' . $item, $_POST['search']['value']);
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

    public function get_by_id($id)
    {
        $data = $this->db->from('swi_penawaran')->where('id', $id)->get()->row();
        return $data;
    }

    public function get_by_id_detail($id)
    {
        $data = $this->db->from('swi_penawaran_detail')->where('id_penawaran', $id)->get()->result();
        return $data;
    }

    public function max_kode($date)
    {
        // Pastikan format tanggal benar
        $formatted_date = date('Ym', strtotime($date));

        $this->db->select('kode');
        $this->db->from('swi_penawaran');
        $this->db->where("SUBSTRING(kode, 2, 6) =", $formatted_date);
        $this->db->order_by('id', 'DESC'); // Ambil id terbesar
        $this->db->limit(1);

        return $this->db->get();
    }

    public function save($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function save_detail($data)
    {
        $this->db->insert_batch('swi_penawaran_detail', $data);
        return true;
    }

    public function delete($id)
    {
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);

        $this->db->where('id_penawaran', $id);
        $this->db->delete('swi_penawaran_detail');
        return true;
    }
}
