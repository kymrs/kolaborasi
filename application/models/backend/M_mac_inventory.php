<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_mac_inventory extends CI_Model
{
    var $id            = 'id';
    var $table         = 'mac_inventory';
    var $column_order  = array(null, null, 'kode_produk', 'nama_produk', 'kategori', 'satuan', 'harga_beli_cabang', 'harga_jual_cabang', 'stok_saat_ini', null, 'created_at');
    var $column_search = array('kode_produk', 'nama_produk', 'kategori', 'satuan');
    var $order         = array('id' => 'desc');

    public function __construct()
    {
        parent::__construct();
    }

    // ========== HITUNG STOK AKTUAL ==========
    // public function get_stok_aktual($inventory_id)
    // {
    //     $row = $this->db->get_where($this->table, ['id' => $inventory_id])->row();
    //     if (!$row) return 0;

    //     $stok_awal = (int)$row->stok_awal;

    //     $masuk = $this->db->select_sum('jumlah')
    //         ->where('inventory_id', $inventory_id)
    //         ->where('tipe', 'masuk')
    //         ->get('mac_transaksi')->row();

    //     $keluar = $this->db->select_sum('jumlah')
    //         ->where('inventory_id', $inventory_id)
    //         ->where('tipe', 'keluar')
    //         ->get('mac_transaksi')->row();

    //     return $stok_awal + (int)$masuk->jumlah - (int)$keluar->jumlah;
    // }

    private function _get_datatables_query($is_nasional = false, $cabang_id = null)
    {
        if ($is_nasional && is_null($cabang_id)) {
            // Nasional tanpa filter → SUM semua cabang, harga strip
            $this->db->select("
                i.*,
                COALESCE(SUM(s.stok_saat_ini),0) as stok_saat_ini,
                (
                    SELECT MIN(stok_minimal)
                    FROM mac_inventory_cabang
                    WHERE inventory_id = i.id
                ) as stok_minimal_cabang,
                NULL as harga_beli_cabang,
                NULL as harga_jual_cabang
            ", FALSE)
            ->from('mac_inventory i')
            ->join(
                'mac_inventory_stok s',
                's.inventory_id = i.id',
                'left'
            )
            ->where('i.is_active',1)
            ->group_by('i.id');

        } elseif (!is_null($cabang_id)) {
            $cid = intval($cabang_id);
            $this->db->select("
                i.*,
                COALESCE(s.stok_saat_ini, 0) as stok_saat_ini,
                (
                    SELECT stok_minimal FROM mac_inventory_cabang
                    WHERE inventory_id = i.id AND cabang_id = {$cid}
                    LIMIT 1
                ) as stok_minimal_cabang,
                (
                    SELECT harga_beli FROM mac_inventory_batch
                    WHERE inventory_id = i.id
                    AND cabang_id = {$cid}
                    AND status = 'aktif'
                    ORDER BY tanggal_masuk DESC, id DESC
                    LIMIT 1
                ) as harga_beli_cabang,
                (
                    SELECT harga_jual FROM mac_inventory_cabang
                    WHERE inventory_id = i.id AND cabang_id = {$cid}
                    LIMIT 1
                ) as harga_jual_cabang
            ", FALSE)
            ->from('mac_inventory i')
            ->join('mac_inventory_stok s',
                's.inventory_id = i.id AND s.cabang_id = ' . $cid,
                'left')
            ->where('i.is_active', 1);

        } else {
            $this->db->select('
                i.*,
                0 as stok_saat_ini,
                NULL as stok_minimal_cabang,
                NULL as harga_beli_cabang,
                NULL as harga_jual_cabang
            ', FALSE)
            ->from('mac_inventory i')
            ->where('i.is_active', 1);
        }

        $i = 0;
        foreach ($this->column_search as $item) {
            if (!empty($_POST['search']['value'])) {
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like('i.' . $item, $_POST['search']['value']);
                } else {
                    $this->db->or_like('i.' . $item, $_POST['search']['value']);
                }
                if (count($this->column_search) - 1 == $i)
                    $this->db->group_end();
            }
            $i++;
        }

        if (isset($_POST['order'])) {
            $column = $this->column_order[$_POST['order']['0']['column']];
            $dir    = $_POST['order']['0']['dir'];

            if ($column == 'stok_saat_ini') {
                if ($is_nasional && is_null($cabang_id)) {
                    $this->db->order_by('SUM(s.stok_saat_ini)', $dir, false);
                } else {
                    $this->db->order_by('s.stok_saat_ini', $dir);
                }
            } else {
                $this->db->order_by($column, $dir);
            }
        } elseif (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    // ========== DATATABLES ==========
    // private function _get_datatables_query($is_nasional = false, $cabang_id = null)
    // {
    //     if ($is_nasional && is_null($cabang_id)) {
    //         // Nasional tanpa filter → SUM semua cabang
    //         $this->db->select('i.*, COALESCE(SUM(s.stok_saat_ini), 0) as stok_saat_ini', FALSE)
    //             ->from('mac_inventory i')
    //             ->join('mac_inventory_stok s', 's.inventory_id = i.id', 'left')
    //             ->where('i.is_active', 1)
    //             ->group_by('i.id');

    //     } elseif (!is_null($cabang_id)) {
    //         // Nasional pilih cabang spesifik ATAU login sebagai cabang biasa
    //         $this->db->select('i.*, COALESCE(s.stok_saat_ini, 0) as stok_saat_ini', FALSE)
    //             ->from('mac_inventory i')
    //             ->join('mac_inventory_stok s',
    //                 's.inventory_id = i.id AND s.cabang_id = ' . intval($cabang_id),
    //                 'left')
    //             ->where('i.is_active', 1);

    //     } else {
    //         // Fallback
    //         $this->db->select('i.*, 0 as stok_saat_ini', FALSE)
    //             ->from('mac_inventory i')
    //             ->where('i.is_active', 1);
    //     }
        
    //     $i = 0;
    //     foreach ($this->column_search as $item) {
    //         if (!empty($_POST['search']['value'])) {
    //             if ($i === 0) {
    //                 $this->db->group_start();
    //                 $this->db->like($item, $_POST['search']['value']);
    //             } else {
    //                 $this->db->or_like($item, $_POST['search']['value']);
    //             }
    //             if (count($this->column_search) - 1 == $i)
    //                 $this->db->group_end();
    //         }
    //         $i++;
    //     }

    //     if (isset($_POST['order'])) {
    //         $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    //     } else if (isset($this->order)) {
    //         $order = $this->order;
    //         $this->db->order_by(key($order), $order[key($order)]);
    //     }
    // }

    public function get_datatables($is_nasional = false, $cabang_id = null)
    {
        $this->_get_datatables_query($is_nasional, $cabang_id);
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        return $this->db->get()->result();
    }

    function count_filtered($is_nasional = false, $cabang_id = null)
    {
        $this->_get_datatables_query($is_nasional, $cabang_id);
        return $this->db->get()->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function get_by_id($id)
    {
        $this->db->where($this->id, $id);
        return $this->db->get($this->table)->row();
    }

    // ========== MUTASI ==========
    public function get_mutasi($inventory_id)
    {
        return $this->db
            ->where('inventory_id', $inventory_id)
            ->order_by('id', 'desc')
            ->get('mac_transaksi')
            ->result();
    }

    public function add_mutasi($data)
    {
        $this->db->insert('mac_transaksi', $data);
        return $this->db->insert_id();
    }

    // ========== GENERATE KODE PRODUK BERDASARKAN KATEGORI ==========
    public function generate_kode_produk($kategori)
    {
        // Mapping kategori ke prefix
        $kategori_map = array(
            'Sparepart'  => 'SPP',
            'Pelumas'    => 'PLS',
            'Bahan'      => 'BHN'
        );

        if (!isset($kategori_map[$kategori])) {
            return false;
        }

        $prefix = $kategori_map[$kategori];

        // Cari kode produk terakhir dengan prefix ini
        $this->db->where('kode_produk LIKE', $prefix . '%');
        $this->db->order_by('kode_produk', 'desc');
        $this->db->limit(1);
        $last = $this->db->get($this->table)->row();

        if ($last) {
            // Extract angka dari kode terakhir
            $last_code = $last->kode_produk;
            // Hapus prefix untuk mendapat angka
            $last_number = (int)substr($last_code, strlen($prefix));
            $next_number = $last_number + 1;
        } else {
            $next_number = 1;
        }

        // Format dengan leading zeros
        if ($prefix === 'J') {
            $kode = $prefix . str_pad($next_number, 4, '0', STR_PAD_LEFT);
        } else {
            $kode = $prefix . str_pad($next_number, 4, '0', STR_PAD_LEFT);
        }

        return $kode;
    }

    // ========== SAVE / UPDATE BARANG ==========
    public function save($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update_barang($id, $data)
    {
        $this->db->where($this->id, $id);
        $this->db->update($this->table, $data);
    }

    public function delete_barang($id)
    {
        $this->db->where($this->id, $id);
        return $this->db->update($this->table, [
            'is_active' => 0,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
}