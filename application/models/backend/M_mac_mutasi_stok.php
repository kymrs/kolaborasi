<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_mac_mutasi_stok extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // ================================================================
    // GET INFO BARANG
    // ================================================================

    public function get_barang($inventory_id, $cabang_id = null)
    {
        // stok_saat_ini sesuai cabang
        if (!is_null($cabang_id)) {
            return $this->db->select('i.*, COALESCE(s.stok_saat_ini, 0) as stok_saat_ini')
                ->from('mac_inventory i')
                ->join('mac_inventory_stok s',
                    's.inventory_id = i.id AND s.cabang_id = ' . intval($cabang_id), 'left')
                ->where('i.id', $inventory_id)
                ->get()->row();
        } else {
            // Semua cabang → SUM
            return $this->db->select('i.*, COALESCE(SUM(s.stok_saat_ini), 0) as stok_saat_ini', FALSE)
                ->from('mac_inventory i')
                ->join('mac_inventory_stok s', 's.inventory_id = i.id', 'left')
                ->where('i.id', $inventory_id)
                ->group_by('i.id')
                ->get()->row();
        }
    }

    // ================================================================
    // GET SALDO AWAL SEBELUM PERIODE
    // Hitung stok sebelum tanggal filter dimulai
    // Jika tidak ada filter tanggal, saldo awal = 0 (ambil dari awal)
    // ================================================================

    public function get_stok_awal($inventory_id, $tgl_dari = null, $cabang_id = null)
    {
        if (empty($tgl_dari)) return 0;

        $q_masuk = $this->db->select_sum('jumlah')
            ->from('mac_transaksi')
            ->where('inventory_id', $inventory_id)
            ->where('tipe', 'Masuk')
            ->where('DATE(transaksi_date) <', $tgl_dari);
        if (!is_null($cabang_id)) $q_masuk->where('cabang_id', $cabang_id);
        $masuk = $q_masuk->get()->row('jumlah');

        $q_keluar = $this->db->select_sum('jumlah')
            ->from('mac_transaksi')
            ->where('inventory_id', $inventory_id)
            ->where('tipe !=', 'Masuk')
            ->where('DATE(transaksi_date) <', $tgl_dari);
        if (!is_null($cabang_id)) $q_keluar->where('cabang_id', $cabang_id);
        $keluar = $q_keluar->get()->row('jumlah');

        return floatval($masuk) - floatval($keluar);
    }

    // ================================================================
    // GET HISTORI TRANSAKSI DALAM PERIODE
    // Join ke dokumen sumber (reimburse / invoice) untuk keterangan lengkap
    // ================================================================

    public function get_transaksi($inventory_id, $tgl_dari = null, $tgl_sampai = null, $cabang_id = null)
    {
        $this->db->select("
            t.id, t.tipe, t.jumlah, t.stok_sebelum, t.stok_sesudah,
            t.referensi, t.referensi_tipe, t.referensi_id,
            t.keterangan, t.harga_beli_saat_transaksi, t.transaksi_date,
            t.created_by,
            u.name as nama_user,
            b.kode_batch,
            b.harga_beli as harga_batch,
            r.kode_reimbust,
            r.id as reimbust_id,
            r.tgl_pengajuan as tgl_reimbust,
            r_user.name as nama_pelapor,
            inv.id as invoice_id,
            inv.invoice_number,
            inv.nopol,
            c.customer_name,
            cab.nama_cabang
        ", FALSE)
        ->from('mac_transaksi t')
        ->join('tbl_data_user u',       'u.id_user = t.created_by',           'left')
        ->join('mac_inventory_batch b',  'b.id = t.batch_id',                 'left')
        ->join('mac_reimbust_detail rd', "rd.id = t.referensi_id AND t.referensi_tipe IN ('Pelaporan','Reimbust')", 'left')
        ->join('mac_reimbust r',         'r.id = rd.reimbust_id',             'left')
        ->join('tbl_data_user r_user',   'r_user.id_user = r.id_user',        'left')
        ->join('mac_invoice_detail id_tbl', "id_tbl.id = t.referensi_id AND t.referensi_tipe = 'Invoice'", 'left')
        ->join('mac_invoice inv',        'inv.id = id_tbl.invoice_id',        'left')
        ->join('mac_customer c',         'c.id = inv.customer_id',            'left')
        ->join('mac_cabang cab',         'cab.id = t.cabang_id',              'left') // ← tambahan
        ->where('t.inventory_id', $inventory_id);

        // Filter cabang
        if (!is_null($cabang_id)) {
            $this->db->where('t.cabang_id', $cabang_id);
        }

        if (!empty($tgl_dari)) {
            $this->db->where('DATE(t.transaksi_date) >=', $tgl_dari);
        }
        if (!empty($tgl_sampai)) {
            $this->db->where('DATE(t.transaksi_date) <=', $tgl_sampai);
        }

        $this->db->order_by('t.transaksi_date', 'ASC')
                ->order_by('t.id', 'ASC');

        return $this->db->get()->result_array();
    }
}
