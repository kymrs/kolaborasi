<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * M_mac_inventory_stok
 * Satu-satunya pintu masuk untuk mengubah stok.
 * Prinsip: stok = hasil histori mac_transaksi, bukan angka yang diubah manual.
 */
class M_mac_inventory_stok extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // ---------------------------------------------------------------
    // PRIVATE HELPERS
    // ---------------------------------------------------------------

    private function generate_kode_batch()
    {
        $prefix = 'BTH' . date('ym');
        $last   = $this->db->select('kode_batch')
            ->from('mac_inventory_batch')
            ->like('kode_batch', $prefix, 'after')
            ->order_by('id', 'DESC')->limit(1)->get()->row();
        $urutan = empty($last) ? 1 : ((int) substr($last->kode_batch, -4) + 1);
        return $prefix . str_pad($urutan, 4, '0', STR_PAD_LEFT);
    }

    private function get_or_create_stok_cache($inventory_id, $cabang_id = 1)
    {
        $cabang_id = intval($cabang_id) ?: 1;

        $row = $this->db
            ->where('inventory_id', (int) $inventory_id)
            ->where('cabang_id',    $cabang_id)
            ->get('mac_inventory_stok')->row();

        if (!$row) {
            $this->db->insert('mac_inventory_stok', [
                'inventory_id'  => (int) $inventory_id,
                'cabang_id'     => $cabang_id,
                'stok_saat_ini' => 0,
                'updated_at'    => date('Y-m-d H:i:s'),
            ]);
            return (object)[
                'inventory_id'  => $inventory_id,
                'cabang_id'     => $cabang_id,
                'stok_saat_ini' => 0,
            ];
        }

        return $row;
    }

    // ---------------------------------------------------------------
    // IDEMPOTENCY CHECK
    // ---------------------------------------------------------------

    public function sudah_diproses($referensi_tipe, $referensi_id, $cabang_id, $tipe = 'Masuk')
    {
        return $this->db
            ->where('referensi_tipe', $referensi_tipe)
            ->where('referensi_id',   (int) $referensi_id)
            ->where('cabang_id',      (int) $cabang_id)
            ->where('tipe',           $tipe)
            ->count_all_results('mac_transaksi') > 0;
    }

    // ---------------------------------------------------------------
    // BARANG MASUK — buat batch + ledger + update cache
    // ---------------------------------------------------------------

    public function tambah_stok_masuk(
        $inventory_id,
        $cabang_id,
        $qty,
        $harga_beli,
        $tanggal,
        $referensi_tipe,
        $referensi_id,
        $created_by
    ) {
        if (empty($inventory_id) || floatval($qty) <= 0) return false;
 
        $cabang_id = intval($cabang_id) ?: 1;
 
        $this->db->trans_start();
 
        // 1. Buat batch baru
        $kode_batch = $this->generate_kode_batch();
        $this->db->insert('mac_inventory_batch', [
            'kode_batch'         => $kode_batch,
            'inventory_id'       => (int) $inventory_id,
            'cabang_id'          => $cabang_id,
            'reimbust_detail_id' => ($referensi_tipe === 'reimbust_detail') ? (int) $referensi_id : null,
            'tanggal_masuk'      => $tanggal,
            'qty_masuk'          => floatval($qty),
            'qty_sisa'           => floatval($qty),
            'harga_beli'         => floatval($harga_beli),
            'status'             => 'aktif',
            'created_at'         => date('Y-m-d H:i:s'),
        ]);
        $batch_id = $this->db->insert_id();
 
        // 2. Baca stok sebelumnya
        $cache        = $this->get_or_create_stok_cache($inventory_id, $cabang_id);
        $stok_sebelum = floatval($cache->stok_saat_ini);
        $stok_sesudah = $stok_sebelum + floatval($qty);
 
        // 3. Update cache
        $this->db
            ->where('inventory_id', (int) $inventory_id)
            ->where('cabang_id',    $cabang_id)
            ->update('mac_inventory_stok', [
                'stok_saat_ini' => $stok_sesudah,
                'updated_at'    => date('Y-m-d H:i:s'),
            ]);
 
        // 4. Catat ledger
        $this->db->insert('mac_transaksi', [
            'inventory_id'              => (int) $inventory_id,
            'cabang_id'                 => $cabang_id,
            'tipe'                      => 'Masuk',
            'jumlah'                    => floatval($qty),
            'stok_sebelum'              => $stok_sebelum,
            'stok_sesudah'              => $stok_sesudah,
            'batch_id'                  => $batch_id,
            'harga_beli_saat_transaksi' => floatval($harga_beli),
            'referensi_tipe'            => $referensi_tipe,
            'referensi_id'              => (int) $referensi_id,
            'keterangan'                => 'Barang masuk dari ' . $referensi_tipe . ' #' . $referensi_id,
            'transaksi_date'            => date('Y-m-d H:i:s'),
            'created_at'                => date('Y-m-d H:i:s'),
            'created_by'                => (int) $created_by,
        ]);
 
        $this->db->trans_complete();
 
        if (!$this->db->trans_status()) {
            log_message('error', "tambah_stok_masuk FAILED — inv={$inventory_id}, cabang={$cabang_id}");
            return false;
        }
 
        return $batch_id;
    }

    // ---------------------------------------------------------------
    // BARANG KELUAR (FIFO) — konsumsi batch dari yang paling lama
    //
    // Algoritma:
    // 1. Ambil batch aktif FIFO (ORDER BY tanggal_masuk ASC, id ASC)
    //    dengan FOR UPDATE untuk hindari race condition
    // 2. Kurangi qty_sisa dari batch satu per satu
    // 3. Tiap pengambilan dari satu batch = satu baris mac_transaksi
    // 4. Jika total qty_sisa semua batch tidak cukup → ROLLBACK + return false
    //
    // @return array|false  array batch yang terpakai jika sukses, false jika gagal
    // ---------------------------------------------------------------

   public function tambah_stok_keluar(
        $inventory_id,
        $cabang_id,
        $qty_keluar,
        $harga_jual,
        $referensi_tipe,
        $referensi_id,
        $created_by
    ) {
        $cabang_id  = intval($cabang_id) ?: 1;
        $qty_keluar = floatval($qty_keluar);
 
        if ($qty_keluar <= 0) return 'Qty keluar harus lebih dari 0';
 
        // Ambil batch FIFO aktif untuk cabang ini
        $batches = $this->get_batch_fifo($inventory_id, $cabang_id);
 
        $total_tersedia = array_sum(array_column($batches, 'qty_sisa'));
        if ($total_tersedia < $qty_keluar) {
            return 'Stok tidak mencukupi (tersedia: ' . $total_tersedia . ')';
        }
 
        $this->db->trans_start();
 
        $cache        = $this->get_or_create_stok_cache($inventory_id, $cabang_id);
        $stok_sebelum = floatval($cache->stok_saat_ini);
        $sisa_keluar  = $qty_keluar;
 
        foreach ($batches as $batch) {
            if ($sisa_keluar <= 0) break;
 
            $ambil = min(floatval($batch['qty_sisa']), $sisa_keluar);
 
            // Update qty_sisa batch
            $qty_sisa_baru = floatval($batch['qty_sisa']) - $ambil;
            $this->db->where('id', $batch['id'])->update('mac_inventory_batch', [
                'qty_sisa' => $qty_sisa_baru,
                'status'   => $qty_sisa_baru <= 0 ? 'habis' : 'aktif',
            ]);
 
            // Catat ledger per batch yang dikonsumsi
            $stok_sesudah = $stok_sebelum - $ambil;
            $this->db->insert('mac_transaksi', [
                'inventory_id'              => (int) $inventory_id,
                'cabang_id'                 => $cabang_id,
                'tipe'                      => 'Keluar',
                'jumlah'                    => $ambil,
                'stok_sebelum'              => $stok_sebelum,
                'stok_sesudah'              => $stok_sesudah,
                'batch_id'                  => $batch['id'],
                'harga_beli_saat_transaksi' => floatval($batch['harga_beli']),
                'harga_jual_saat_transaksi' => $harga_jual,
                'referensi_tipe'            => $referensi_tipe,
                'referensi_id'              => (int) $referensi_id,
                'keterangan'                => 'Barang keluar ke ' . $referensi_tipe . ' #' . $referensi_id,
                'transaksi_date'            => date('Y-m-d H:i:s'),
                'created_at'                => date('Y-m-d H:i:s'),
                'created_by'                => (int) $created_by,
            ]);
 
            $stok_sebelum = $stok_sesudah;
            $sisa_keluar -= $ambil;
        }
 
        // Update cache stok
        $this->db
            ->where('inventory_id', (int) $inventory_id)
            ->where('cabang_id',    $cabang_id)
            ->update('mac_inventory_stok', [
                'stok_saat_ini' => $stok_sebelum,
                'updated_at'    => date('Y-m-d H:i:s'),
            ]);
 
        $this->db->trans_complete();
 
        return $this->db->trans_status() ? true : 'Database error saat keluar stok';
    }

    // -----------------------------------------------------------------
    // AMBIL STOK — per cabang
    // -----------------------------------------------------------------
    public function get_stok($inventory_id, $is_nasional = false, $cabang_id = null)
    {
        $this->db->reset_query();

        $this->db->from('mac_inventory_stok');
        $this->db->where('inventory_id', (int)$inventory_id);

        if ($is_nasional) {
            $this->db->select('COALESCE(SUM(stok_saat_ini), 0) AS stok', false);
        } else {
            $this->db->select('COALESCE(stok_saat_ini, 0) AS stok', false);
            $this->db->where('cabang_id', (int)$cabang_id);
        }

        $row = $this->db->get()->row();

        return $row ? (float)$row->stok : 0;
    }
    
    // ================================================================
    // STOK EFEKTIF = STOK FISIK - BARANG YANG SEDANG DIPINJAM
    // ================================================================
    public function get_stok_efektif($inventory_id, $is_nasional = false, $cabang_id = null)
    {
        // Stok fisik — sudah aware nasional/cabang
        $stok_fisik = $this->get_stok($inventory_id, $is_nasional, $cabang_id);

        // Hitung total yang sedang dipinjam
        $this->db->select('COALESCE(SUM(d.qty_pinjam - d.qty_kembali), 0) AS total', false);
        $this->db->from('mac_peminjaman_detail d');
        $this->db->join('mac_peminjaman p', 'p.id = d.peminjaman_id');
        $this->db->where('d.inventory_id', $inventory_id);
        $this->db->where('p.status', 'aktif');
        $this->db->where('p.app_status', 'approved');

        // SUM semua cabang jika nasional tanpa filter, filter cabang jika ada
        if (!$is_nasional && $cabang_id) {
            $this->db->where('p.cabang_id', $cabang_id);
        } elseif ($is_nasional && $cabang_id) {
            $this->db->where('p.cabang_id', $cabang_id);
        }
        // is_nasional && !cabang_id → tidak filter → SUM semua cabang

        $sedang_dipinjam = (float) $this->db->get()->row()->total;

        return max(0, $stok_fisik - $sedang_dipinjam);
    }
    
    // -----------------------------------------------------------------
    // BATCH FIFO — per cabang
    // -----------------------------------------------------------------
    public function get_batch_fifo($inventory_id, $cabang_id = 1)
    {
        return $this->db->select('*')
            ->from('mac_inventory_batch')
            ->where('inventory_id', (int) $inventory_id)
            ->where('cabang_id',    (int) $cabang_id)
            ->where('qty_sisa >',   0)
            ->where('status',       'aktif')
            ->order_by('tanggal_masuk', 'ASC')
            ->order_by('id', 'ASC')
            ->get()->result_array();
    }
 
    // -----------------------------------------------------------------
    // REKONSILIASI CACHE — hitung ulang dari batch (per cabang)
    // -----------------------------------------------------------------
    public function rekonsiliasi_cache($inventory_id = null, $cabang_id = null)
    {
        $q = $this->db->select('inventory_id, cabang_id, SUM(qty_sisa) as total_sisa', FALSE)
            ->from('mac_inventory_batch')
            ->where('status', 'aktif')
            ->group_by('inventory_id, cabang_id');
 
        if ($inventory_id) $q->where('inventory_id', (int) $inventory_id);
        if ($cabang_id)    $q->where('cabang_id',    (int) $cabang_id);
 
        $rows = $q->get()->result();
 
        foreach ($rows as $r) {
            $this->db
                ->where('inventory_id', $r->inventory_id)
                ->where('cabang_id',    $r->cabang_id)
                ->update('mac_inventory_stok', [
                    'stok_saat_ini' => floatval($r->total_sisa),
                    'updated_at'    => date('Y-m-d H:i:s'),
                ]);
        }
 
        return count($rows);
    }
}