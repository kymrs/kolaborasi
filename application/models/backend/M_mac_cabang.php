<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_mac_cabang extends CI_Model
{
    var $table         = 'mac_cabang';
    var $column_order  = [null, null, 'kode', 'nama_cabang', 'no_telp', 'alamat', 'status', 'created_at'];
    var $column_search = ['kode', 'nama_cabang', 'alamat', 'no_telp'];
    var $order         = ['id' => 'desc'];

    private function _get_datatables_query()
    {
        $this->db->from($this->table);

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
        } else {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        return $this->db->get()->result();
    }

    public function count_filtered()
    {
        $this->_get_datatables_query();
        return $this->db->get()->num_rows();
    }

    public function count_all()
    {
        return $this->db->count_all($this->table);
    }

    public function get_by_id($id)
    {
        return $this->db->where('id', $id)->get($this->table)->row();
    }

    public function get_all_aktif()
    {
        return $this->db->where('status', 'aktif')
            ->order_by('nama_cabang', 'ASC')
            ->get($this->table)->result();
    }

    public function save($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update_cabang($id, $data)
    {
        $this->db->where('id', $id)->update($this->table, $data);
    }

    public function delete_cabang($id)
    {
        // Cek apakah cabang dipakai di stok/transaksi
        $used = $this->db->where('cabang_id', $id)->count_all_results('mac_inventory_stok');
        if ($used > 0) return FALSE;
        $this->db->where('id', $id)->delete($this->table);
        return TRUE;
    }

    public function cek_kode($kode, $exclude_id = null)
    {
        $this->db->where('kode', $kode);
        if ($exclude_id) $this->db->where('id !=', $exclude_id);
        return $this->db->get($this->table)->num_rows() > 0;
    }
}