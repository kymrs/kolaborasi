<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Contract_model extends CI_Model {

    /**
     * Get next counter for given sub-bisnis code and year in a transaction-safe way.
     * Returns new counter int on success, or false on failure.
     */
    public function get_next_counter($kode_sub_bisnis, $year)
    {
        if (empty($kode_sub_bisnis) || empty($year)) {
            return false;
        }

        $kode = strtoupper(trim($kode_sub_bisnis));
        $year = (int) $year;

        $this->db->trans_begin();

        // Lock row supaya aman kalau ada 2 user generate bersamaan
        $sql = "
            SELECT id, counter
            FROM holding_contract_counters
            WHERE kode_sub_bisnis = ?
              AND year = ?
            FOR UPDATE
        ";

        $query = $this->db->query($sql, [$kode, $year]);
        $row = $query->row();

        if (!$row) {

            // Buat counter baru
            $this->db->insert('holding_contract_counters', [
                'kode_sub_bisnis' => $kode,
                'year'            => $year,
                'counter'         => 0
            ]);

            if ($this->db->affected_rows() <= 0) {
                $this->db->trans_rollback();
                return false;
            }

            // Ambil lagi row yang baru dibuat
            $query = $this->db->query($sql, [$kode, $year]);
            $row = $query->row();

            if (!$row) {
                $this->db->trans_rollback();
                return false;
            }
        }

        // Naikkan counter
        $new_counter = ((int) $row->counter) + 1;

        $this->db
            ->where('id', $row->id)
            ->update('holding_contract_counters', [
                'counter' => $new_counter
            ]);

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();

        return $new_counter;
    }

    /**
     * Peek next counter without incrementing the stored counter.
     * This is useful for previewing nomor perjanjian based on existing documents.
     */
    public function peek_next_counter($kode_sub_bisnis, $year)
    {
        if (empty($kode_sub_bisnis) || empty($year)) return false;

        $kode = strtoupper(trim($kode_sub_bisnis));
        $year = (int) $year;

        $sql = "SELECT counter FROM holding_contract_counters WHERE kode_sub_bisnis = ? AND year = ?";
        $q = $this->db->query($sql, [$kode, $year]);
        $row = $q->row();
        $counter = $row ? (int)$row->counter : 0;

        $this->db->select('MAX(CAST(SUBSTRING_INDEX(no_perjanjian, "/", 1) AS UNSIGNED)) as max_urut', false);
        $this->db->like('no_perjanjian', "/PKWT-{$kode}/", 'both');
        $this->db->like('no_perjanjian', "/{$year}", 'before');
        $row2 = $this->db->get('holding_kontrak_pkwt')->row();
        $max_existing = isset($row2->max_urut) ? (int)$row2->max_urut : 0;

        return max($counter, $max_existing) + 1;
    }
}
