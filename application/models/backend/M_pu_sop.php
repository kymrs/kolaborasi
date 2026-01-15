<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_pu_sop extends CI_Model
{
    var $id = 'id';
    var $table = 'pu_sop'; //nama tabel dari database
    var $column_order = array(null, null, 'no', 'jenis', 'kode', 'nama', 'file', 'created_at');
    var $column_search = array('jenis', 'kode', 'nama', 'file', 'created_at'); //field yang diizin untuk pencarian 
    var $order = array('id' => 'desc'); // default order 

    public function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query()
    {

        $this->db->from($this->table);

        $i = 0;

        foreach ($this->column_search as $item) // looping awal
        {
            if ($_POST['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
            {

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
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function get_by_id($id)
    {
        $this->db->where($this->id, $id);
        $result = $this->db->get($this->table)->row();
        
        // Jika ada parent_id, ambil parent_no untuk ditampilkan di form
        if ($result && $result->parent_id) {
            $parent = $this->get_by_id($result->parent_id);
            if ($parent) {
                $result->parent_no = $parent->no;
            }
        }
        
        return $result;
    }

    /**
     * Ambil data berdasarkan nomor hierarki (kolom no)
     */
    public function get_by_no($no)
    {
        $this->db->where('no', $no);
        return $this->db->get($this->table)->row();
    }

    public function save($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $this->db->where($this->id, $id);
        $this->db->update($this->table, $data);
    }

    public function delete($id)
    {
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
    }

    /**
     * Generate kode otomatis berdasarkan jenis (SOP, Juklak, Juknis)
     * SOP -> SOP0001, SOP0002, dst
     * Juklak -> JKL0001, JKL0002, dst
     * Juknis -> JKN0001, JKN0002, dst
     */
    public function generate_kode($jenis)
    {
        // Map jenis ke prefix kode
        $prefix_map = array(
            'SOP' => 'SOP',
            'Juklak' => 'JKL',
            'Juknis' => 'JKN'
        );

        $prefix = isset($prefix_map[$jenis]) ? $prefix_map[$jenis] : 'SOP';

        // Get the last kode dengan prefix yang sama
        $this->db->select('kode')
            ->from($this->table)
            ->where('jenis', $jenis)
            ->order_by('id', 'DESC')
            ->limit(1);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            // Ada data sebelumnya, increment nomor
            $last_kode = $query->row()->kode;
            // Extract nomor dari kode (contoh: "SOP0001" -> ambil "0001")
            $last_number = intval(substr($last_kode, -4));
            $new_number = $last_number + 1;
        } else {
            // Belum ada data, mulai dari 1
            $new_number = 1;
        }

        // Format kode dengan leading zeros (4 digit)
        $kode = $prefix . str_pad($new_number, 4, '0', STR_PAD_LEFT);

        return $kode;
    }

    /**
     * Generate nomor hierarki berdasarkan jenis dan parent
     * SOP: "1", "2", "3", dst
     * Juklak (child of SOP 1): "1-1", "1-2", dst
     * Juknis (child of Juklak 1-1): "1-1-1", "1-1-2", dst
     */
    public function generate_no_hierarki($jenis, $parent_id = null)
    {
        if ($jenis == 'SOP') {
            // SOP tidak memiliki parent, hitung SOP terbesar
            $this->db->select('no')
                ->from($this->table)
                ->where('jenis', 'SOP')
                ->where('parent_id', NULL)
                ->order_by('id', 'DESC')
                ->limit(1);

            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                $last_no = $query->row()->no;
                $next_number = intval($last_no) + 1;
            } else {
                $next_number = 1;
            }

            return (string)$next_number;
        } else {
            // Juklak atau Juknis, harus ada parent_id
            if (!$parent_id) {
                return null;
            }

            // Get parent info
            $parent = $this->get_by_id($parent_id);
            if (!$parent) {
                return null;
            }

            $parent_no = $parent->no;

            if ($jenis == 'Juklak') {
                // Parent harus SOP
                if ($parent->jenis != 'SOP') {
                    return null;
                }

                // Cari Juklak terbanyak di bawah SOP ini
                $this->db->select('no')
                    ->from($this->table)
                    ->where('jenis', 'Juklak')
                    ->where('parent_id', $parent_id)
                    ->order_by('id', 'DESC')
                    ->limit(1);

                $query = $this->db->get();

                if ($query->num_rows() > 0) {
                    $last_no = $query->row()->no;
                    // Extract last number (contoh: "1-1" -> ambil "1")
                    $parts = explode('-', $last_no);
                    $last_child_number = intval(end($parts)) + 1;
                } else {
                    $last_child_number = 1;
                }

                return $parent_no . '-' . $last_child_number;
            } else if ($jenis == 'Juknis') {
                // Parent harus Juklak
                if ($parent->jenis != 'Juklak') {
                    return null;
                }

                // Cari Juknis terbanyak di bawah Juklak ini
                $this->db->select('no')
                    ->from($this->table)
                    ->where('jenis', 'Juknis')
                    ->where('parent_id', $parent_id)
                    ->order_by('id', 'DESC')
                    ->limit(1);

                $query = $this->db->get();

                if ($query->num_rows() > 0) {
                    $last_no = $query->row()->no;
                    // Extract last number (contoh: "1-1-1" -> ambil "1")
                    $parts = explode('-', $last_no);
                    $last_child_number = intval(end($parts)) + 1;
                } else {
                    $last_child_number = 1;
                }

                return $parent_no . '-' . $last_child_number;
            }
        }

        return null;
    }

    /**
     * Get opsi parent berdasarkan jenis yang akan dibuat
     */
    public function get_parent_options($jenis)
    {
        $result = array();

        if ($jenis == 'Juklak') {
            // Parent harus SOP
            $this->db->select('id, no, kode, nama')
                ->from($this->table)
                ->where('jenis', 'SOP')
                ->where('parent_id', NULL)
                ->order_by('no', 'ASC');

            $query = $this->db->get();
            $result = $query->result();
        } else if ($jenis == 'Juknis') {
            // Parent harus Juklak
            $this->db->select('id, no, kode, nama')
                ->from($this->table)
                ->where('jenis', 'Juklak')
                ->order_by('no', 'ASC');

            $query = $this->db->get();
            $result = $query->result();
        }

        // Sort berdasarkan hierarki yang benar
        return $this->sort_by_hierarchy($result);
    }

    /**
     * Get semua data dengan struktur hirarki (untuk tampilan)
     */
    public function get_all_with_hierarchy()
    {
        $this->db->select('*')
            ->from($this->table)
            ->order_by('parent_id', 'ASC')
            ->order_by('no', 'ASC');

        $query = $this->db->get();
        $result = $query->result();
        
        // Sort berdasarkan hierarki yang benar
        return $this->sort_by_hierarchy($result);
    }

    /**
     * Sort data berdasarkan hierarki yang benar
     * Contoh output: 1, 1-1, 1-1-1, 1-2, 2, 2-1, dst
     */
    private function sort_by_hierarchy($data)
    {
        $sorted = array();
        $sorted_ids = array();
        
        // Helper function untuk mengekstrak parts dari nomor hierarki
        $get_hierarchy_parts = function($no) {
            $parts = explode('-', $no);
            return array_map('intval', $parts);
        };
        
        // Urutkan dengan custom comparator
        usort($data, function($a, $b) use ($get_hierarchy_parts) {
            $parts_a = $get_hierarchy_parts($a->no);
            $parts_b = $get_hierarchy_parts($b->no);
            
            // Bandingkan setiap level hierarki
            $min_count = min(count($parts_a), count($parts_b));
            
            for ($i = 0; $i < $min_count; $i++) {
                if ($parts_a[$i] != $parts_b[$i]) {
                    return $parts_a[$i] - $parts_b[$i];
                }
            }
            
            // Jika semua level sama, item yang lebih pendek datang lebih dulu
            return count($parts_a) - count($parts_b);
        });
        
        return $data;
    }
}