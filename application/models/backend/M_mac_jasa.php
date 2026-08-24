<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_mac_jasa extends CI_Model
{
    var $id            = 'id';
    var $table         = 'mac_jasa';
    var $column_order  = [null, null, 'nama', 'qty', 'satuan', 'paket', 'harga', 'created_at'];
    var $column_search = ['nama', 'satuan', 'paket'];
    var $order         = ['id' => 'desc'];

    public function __construct()
    {
        parent::__construct();
    }

    // ================================================================
    // DATATABLES
    // ================================================================

    private function _get_datatables_query($cabang_id = null, $jenis = null)
    {
        $this->db->from($this->table);
        $this->db->where('is_active', 1);

        // Filter cabang
        if (!is_null($cabang_id)) {
            $this->db->where('cabang_id', $cabang_id);
        }

        // Filter jenis jasa
        if (!empty($jenis)) {
            $this->db->where('jenis', $jenis);
        }

        $i = 0;
        foreach ($this->column_search as $item) {
            if (!empty($_POST['search']['value'])) {
                if ($i === 0) {
                    $this->db->group_start()->like($item, $_POST['search']['value']);
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
            $this->db->order_by(
                $this->column_order[$_POST['order']['0']['column']],
                $_POST['order']['0']['dir']
            );
        } elseif (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function get_datatables($cabang_id = null, $jenis = null)
    {
        $this->_get_datatables_query($cabang_id, $jenis);

        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }

        return $this->db->get()->result();
    }

    public function count_filtered($cabang_id = null, $jenis = null)
    {
        $this->_get_datatables_query($cabang_id, $jenis);
        return $this->db->get()->num_rows();
    }

    public function count_all($cabang_id = null, $jenis = null)
    {
        $this->db->from($this->table);
        $this->db->where('is_active', 1);

        if (!is_null($cabang_id)) {
            $this->db->where('cabang_id', $cabang_id);
        }

        if (!empty($jenis)) {
            $this->db->where('jenis', $jenis);
        }

        return $this->db->count_all_results();
    }

    // ================================================================
    // CRUD
    // ================================================================

    public function get_by_id($id)
    {
        return $this->db->where($this->id, $id)->get($this->table)->row();
    }

    public function save($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update_jasa($id, $data)
    {
        $this->db->where($this->id, $id)->update($this->table, $data);
    }

    public function delete_jasa($id)
    {
        // Soft delete agar histori invoice yang sudah memakai jasa ini tetap valid
        $this->db->where($this->id, $id)->update($this->table, ['is_active' => 0]);
    }

    // ================================================================
    // CEK DUPLIKASI NAMA + PAKET
    // Kombinasi nama+paket harus unik (mis. "Cuci Mobil" - "Basic" tidak boleh dobel)
    // ================================================================

    public function cek_duplikat($nama, $paket, $exclude_id = null)
    {
        $this->db->where('nama', $nama)
            ->where('paket', $paket)
            ->where('is_active', 1);

        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }

        return $this->db->get($this->table)->num_rows() > 0;
    }
}
