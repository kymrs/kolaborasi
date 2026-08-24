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

    private function get_or_create_stok_cache($inventory_id)
    {
        $row = $this->db->where('inventory_id', $inventory_id)
            ->get('mac_inventory_stok')->row();
        if (!$row) {
            $this->db->insert('mac_inventory_stok', [
                'inventory_id'  => $inventory_id,
                'stok_saat_ini' => 0,
                'updated_at'    => date('Y-m-d H:i:s'),
            ]);
            return (object)['inventory_id' => $inventory_id, 'stok_saat_ini' => 0];
        }
        return $row;
    }

    // ---------------------------------------------------------------
    // IDEMPOTENCY CHECK
    // ---------------------------------------------------------------

    public function sudah_diproses($referensi_tipe, $referensi_id, $tipe = 'MASUK')
    {
        return $this->db
            ->where('referensi_tipe', $referensi_tipe)
            ->where('referensi_id',   (int) $referensi_id)
            ->where('tipe',           $tipe)
            ->count_all_results('mac_transaksi') > 0;
    }

    // ---------------------------------------------------------------
    // BARANG MASUK — buat batch + ledger + update cache
    // ---------------------------------------------------------------

    public function tambah_stok_masuk(
        $inventory_id, $cabang_id, $kode_referensi, $qty, $harga_beli, $tanggal,
        $referensi_tipe, $referensi_id, $created_by
    ) {
        if (empty($inventory_id) || floatval($qty) <= 0) return false;

        $this->db->trans_start();

        $kode_batch = $this->generate_kode_batch();
        $this->db->insert('mac_inventory_batch', [
            'kode_batch'         => $kode_batch,
            'inventory_id'       => (int) $inventory_id,
            'cabang_id'          => $cabang_id,
            'reimbust_detail_id' => ($referensi_tipe === 'Pelaporan') ? (int) $referensi_id : null,
            'tanggal_masuk'      => $tanggal,
            'qty_masuk'          => floatval($qty),
            'qty_sisa'           => floatval($qty),
            'harga_beli'         => floatval($harga_beli),
            'status'             => 'aktif',
            'created_at'         => date('Y-m-d H:i:s'),
        ]);
        $batch_id = $this->db->insert_id();

        $cache        = $this->get_or_create_stok_cache($inventory_id);
        $stok_sebelum = floatval($cache->stok_saat_ini);
        $stok_sesudah = $stok_sebelum + floatval($qty);

        $this->db->where('inventory_id', (int) $inventory_id)
                ->where('cabang_id', (int) $cabang_id)
                ->update('mac_inventory_stok', [
                    'stok_saat_ini' => $stok_sesudah,
                    'updated_at'    => date('Y-m-d H:i:s'),
                ]);

        $this->db->insert('mac_transaksi', [
            'inventory_id'              => (int) $inventory_id,
            'cabang_id'                 => $cabang_id,
            'tipe'                      => 'Masuk',
            'jumlah'                    => floatval($qty),
            'stok_sebelum'              => $stok_sebelum,
            'stok_sesudah'              => $stok_sesudah,
            'batch_id'                  => $batch_id,
            'harga_beli_saat_transaksi' => floatval($harga_beli),
            'referensi'                 => $kode_referensi,
            'referensi_tipe'            => $referensi_tipe,
            'referensi_id'              => (int) $referensi_id,
            'keterangan'                => 'Barang masuk dari ' . $referensi_tipe,
            'transaksi_date'            => date('Y-m-d H:i:s'),
            'created_at'                => date('Y-m-d H:i:s'),
            'created_by'                => $created_by,
        ]);

        $this->db->trans_complete();
        if (!$this->db->trans_status()) {
            log_message('error', "tambah_stok_masuk FAILED — inventory_id={$inventory_id}");
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
        $inventory_id, $kode_invoice, $qty_dibutuhkan, $tanggal,
        $referensi_tipe, $referensi_id, $created_by
    ) {
        if (empty($inventory_id) || floatval($qty_dibutuhkan) <= 0) return false;

        // Validasi stok sebelum mulai transaksi
        $stok_tersedia = $this->get_stok($inventory_id);
        if ($stok_tersedia < floatval($qty_dibutuhkan)) {
            log_message('error', "tambah_stok_keluar: stok tidak cukup — inventory_id={$inventory_id}, dibutuhkan={$qty_dibutuhkan}, tersedia={$stok_tersedia}");
            return false;
        }

        $this->db->trans_start();

        // Lock baris batch yang akan dikonsumsi (FOR UPDATE)
        // CI3 tidak punya method FOR UPDATE di query builder,
        // jadi pakai query manual
        $batch_list = $this->db->query(
            "SELECT * FROM mac_inventory_batch
             WHERE inventory_id = ? AND qty_sisa > 0 AND status = 'aktif'
             ORDER BY tanggal_masuk ASC, id ASC
             FOR UPDATE",
            [(int) $inventory_id]
        )->result_array();

        $sisa_kebutuhan = floatval($qty_dibutuhkan);
        $batch_terpakai = [];

        foreach ($batch_list as $batch) {
            if ($sisa_kebutuhan <= 0) break;

            $ambil       = min(floatval($batch['qty_sisa']), $sisa_kebutuhan);
            $qty_sisa_baru = floatval($batch['qty_sisa']) - $ambil;

            // Update qty_sisa batch
            $this->db->where('id', $batch['id'])->update('mac_inventory_batch', [
                'qty_sisa' => $qty_sisa_baru,
                'status'   => $qty_sisa_baru <= 0 ? 'habis' : 'aktif',
            ]);

            // Snapshot stok untuk baris ledger ini
            $cache        = $this->get_or_create_stok_cache($inventory_id);
            $stok_sebelum = floatval($cache->stok_saat_ini);
            $stok_sesudah = $stok_sebelum - $ambil;

            // Update cache stok
            $this->db->where('inventory_id', (int) $inventory_id)
                ->update('mac_inventory_stok', [
                    'stok_saat_ini' => $stok_sesudah,
                    'updated_at'    => date('Y-m-d H:i:s'),
                ]);

            // Catat satu baris ledger per batch yang dikonsumsi
            $this->db->insert('mac_transaksi', [
                'inventory_id'              => (int) $inventory_id,
                'tipe'                      => 'Keluar',
                'jumlah'                    => $ambil,
                'stok_sebelum'              => $stok_sebelum,
                'stok_sesudah'              => $stok_sesudah,
                'batch_id'                  => $batch['id'],
                'harga_beli_saat_transaksi' => floatval($batch['harga_beli']),
                'referensi'                 => $kode_invoice,
                'referensi_tipe'            => $referensi_tipe,
                'referensi_id'              => (int) $referensi_id,
                'keterangan'                => 'Barang keluar dari ' . $referensi_tipe,
                'transaksi_date'            => date('Y-m-d H:i:s'),
                'created_at'                => date('Y-m-d H:i:s'),
                'created_by'                => $created_by,
            ]);

            $batch_terpakai[] = [
                'batch_id'    => $batch['id'],
                'kode_batch'  => $batch['kode_batch'],
                'harga_beli'  => $batch['harga_beli'],
                'qty_diambil' => $ambil,
            ];

            $sisa_kebutuhan -= $ambil;
        }

        // Jika masih ada sisa kebutuhan yang belum terpenuhi → rollback
        if ($sisa_kebutuhan > 0) {
            $this->db->trans_rollback();
            log_message('error', "tambah_stok_keluar: stok habis di tengah proses — inventory_id={$inventory_id}");
            return false;
        }

        $this->db->trans_complete();

        if (!$this->db->trans_status()) {
            log_message('error', "tambah_stok_keluar FAILED — inventory_id={$inventory_id}");
            return false;
        }

        return $batch_terpakai;
    }

    // ---------------------------------------------------------------
    // REKONSILIASI CACHE (untuk admin/audit)
    // ---------------------------------------------------------------

    public function rekonsiliasi_cache($inventory_id = null)
    {
        $q = $this->db->select('inventory_id, SUM(qty_sisa) as total_sisa')
            ->from('mac_inventory_batch')->where('status', 'aktif')
            ->group_by('inventory_id');
        if ($inventory_id) $q->where('inventory_id', (int) $inventory_id);
        $rows = $q->get()->result();
        foreach ($rows as $r) {
            $this->db->where('inventory_id', $r->inventory_id)
                ->update('mac_inventory_stok', [
                    'stok_saat_ini' => floatval($r->total_sisa),
                    'updated_at'    => date('Y-m-d H:i:s'),
                ]);
        }
        return count($rows);
    }

    // ---------------------------------------------------------------
    // GETTER PUBLIK
    // ---------------------------------------------------------------

    public function get_stok($inventory_id)
    {
        $row = $this->db->where('inventory_id', (int) $inventory_id)
            ->get('mac_inventory_stok')->row();
        return $row ? floatval($row->stok_saat_ini) : 0;
    }

    public function get_batch_fifo($inventory_id)
    {
        return $this->db->select('*')
            ->from('mac_inventory_batch')
            ->where('inventory_id', (int) $inventory_id)
            ->where('qty_sisa >', 0)
            ->where('status', 'aktif')
            ->order_by('tanggal_masuk', 'ASC')
            ->order_by('id', 'ASC')
            ->get()->result_array();
    }
}