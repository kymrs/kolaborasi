<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_sml_kertas_kerja extends CI_Model
{
    var $id = 'id';
    var $table = 'sml_kertas_Kerja'; //nama tabel dari database
    var $column_order = array(null, null, 'no_dok', 'agenda', 'date', 'start_time', 'end_time', 'lokasi', 'peserta');
    var $column_search = array('no_dok', 'agenda', 'date', 'start_time', 'end_time', 'lokasi', 'peserta'); //field yang diizin untuk pencarian 
    var $order = array('id' => 'desc'); // default order 

    private $role_filters = array();
    private $cached_fields = null;

    private function first_existing_field($candidates)
    {
        foreach ((array) $candidates as $c) {
            $c = strtolower(trim((string) $c));
            if ($c !== '' && $this->has_field($c)) {
                return $c;
            }
        }
        return '';
    }

    public function __construct()
    {
        parent::__construct();
    }

    public function set_role_filters($roles)
    {
        $roles = is_array($roles) ? $roles : array($roles);
        $clean = array();
        foreach ($roles as $r) {
            $r = strtolower(trim((string) $r));
            if ($r !== '') {
                $clean[] = $r;
            }
        }
        $this->role_filters = array_values(array_unique($clean));
    }

    private function table_fields()
    {
        if ($this->cached_fields === null) {
            $this->cached_fields = (array) $this->db->list_fields($this->table);
        }
        return $this->cached_fields;
    }

    private function has_field($field)
    {
        return in_array($field, $this->table_fields(), true);
    }

    private function not_empty_expr($field)
    {
        // Samakan logika dengan controller: nilai kosong/0/tanggal nol dianggap belum terisi.
        return '(' . $field . ' IS NOT NULL'
            . ' AND TRIM(' . $field . ") <> ''"
            . ' AND TRIM(' . $field . ") <> '0'"
            . ' AND ' . $field . " <> '0000-00-00'"
            . ' AND ' . $field . " <> '0000-00-00 00:00:00'"
            . ')';
    }

    private function status_filter_value()
    {
        $v = '';
        if (isset($_POST['status_filter'])) {
            $v = (string) $_POST['status_filter'];
        } elseif (isset($_GET['status_filter'])) {
            $v = (string) $_GET['status_filter'];
        }

        $v = strtolower(trim($v));
        if ($v === 'selesai' || $v === 'done' || $v === 'finish' || $v === 'finished') {
            return 'selesai';
        }
        if ($v === 'proses' || $v === 'process' || $v === 'ongoing' || $v === 'on-going') {
            return 'proses';
        }
        return 'all';
    }

    private function stage_done_exprs()
    {
        // Expression "done" per tahap (defensif terhadap kolom yang belum ada).
        $marketing_done_parts = array();
        if ($this->has_field('periode')) {
            $marketing_done_parts[] = $this->not_empty_expr('periode');
        }
        // Minimal marketing: tanggal + (konsumen/customer)
        if ($this->has_field('tanggal')) {
            $konsumen_part = '';
            if ($this->has_field('konsumen')) {
                $konsumen_part = $this->not_empty_expr('konsumen');
            } elseif ($this->has_field('customer')) {
                $konsumen_part = $this->not_empty_expr('customer');
            }
            if ($konsumen_part !== '') {
                $marketing_done_parts[] = '(' . $this->not_empty_expr('tanggal') . ' AND ' . $konsumen_part . ')';
            }
        }
        $marketing_done = !empty($marketing_done_parts) ? '(' . implode(' OR ', $marketing_done_parts) . ')' : '(1=1)';

        $plotting_done_parts = array();
        if ($this->has_field('nopol')) {
            $plotting_done_parts[] = $this->not_empty_expr('nopol');
        }
        if ($this->has_field('id_asset')) {
            $plotting_done_parts[] = $this->not_empty_expr('id_asset');
        }
        $plotting_done = !empty($plotting_done_parts) ? '(' . implode(' OR ', $plotting_done_parts) . ')' : '(1=1)';

        $monitoring_done_parts = array();
        if ($this->has_field('uang_jalan')) {
            $monitoring_done_parts[] = $this->not_empty_expr('uang_jalan');
        }
        if ($this->has_field('tgl_muat_monitoring')) {
            $monitoring_done_parts[] = $this->not_empty_expr('tgl_muat_monitoring');
        }
        if ($this->has_field('tgl_bongkar')) {
            $monitoring_done_parts[] = $this->not_empty_expr('tgl_bongkar');
        }
        $monitoring_done = !empty($monitoring_done_parts) ? '(' . implode(' OR ', $monitoring_done_parts) . ')' : '(1=1)';

        $finance_done_parts = array();
        if ($this->has_field('no_invoice')) {
            $finance_done_parts[] = $this->not_empty_expr('no_invoice');
        }
        if ($this->has_field('status_pembayaran')) {
            $finance_done_parts[] = $this->not_empty_expr('status_pembayaran');
        }
        if ($this->has_field('tgl_invoice')) {
            $finance_done_parts[] = $this->not_empty_expr('tgl_invoice');
        }
        $finance_done = !empty($finance_done_parts) ? '(' . implode(' OR ', $finance_done_parts) . ')' : '(1=1)';

        return array(
            'marketing_done' => $marketing_done,
            'plotting_done' => $plotting_done,
            'monitoring_done' => $monitoring_done,
            'finance_done' => $finance_done,
        );
    }

    private function last_update_order_expr()
    {
        // Ikuti prioritas timestamp yang dipakai di controller untuk "Last Update".
        $candidates = array(
            'updated_at_finance', 'updated_at_monitoring', 'updated_at_plotting', 'updated_at_marketing',
            'created_at_finance', 'created_at_monitoring', 'created_at_plotting', 'created_at_marketing',
            'edit_at', 'updated_at', 'update_at', 'modified_at', 'modified', 'tgl_input', 'created_at', 'created_date', 'create_date',
        );

        $cols = array();
        foreach ($candidates as $c) {
            if ($this->has_field($c)) {
                $cols[] = $c;
            }
        }

        if (empty($cols)) {
            return '';
        }

        // Treat empty/0/zero-date as minimal value.
        $norm = array();
        foreach ($cols as $c) {
            $norm[] = "COALESCE(NULLIF(NULLIF(NULLIF(NULLIF(TRIM($c),''),'0'),'0000-00-00'),'0000-00-00 00:00:00'),'0000-00-00 00:00:00')";
        }

        // MySQL/MariaDB: GREATEST() works for DATETIME/DATE or comparable strings (YYYY-MM-DD ..)
        return 'GREATEST(' . implode(',', $norm) . ')';
    }

    private function apply_completion_status_filter()
    {
        $filter = $this->status_filter_value();
        if ($filter === 'all') {
            return;
        }

        $exprs = $this->stage_done_exprs();
        $complete_expr = '(' . $exprs['marketing_done']
            . ' AND ' . $exprs['plotting_done']
            . ' AND ' . $exprs['monitoring_done']
            . ' AND ' . $exprs['finance_done']
            . ')';

        if ($filter === 'selesai') {
            $this->db->where($complete_expr, null, false);
            return;
        }

        // proses: selain yang complete
        if ($filter === 'proses') {
            $this->db->where('NOT ' . $complete_expr, null, false);
            return;
        }
    }

    private function apply_role_visibility_filter()
    {
        // Jika tidak ada role filter, jangan batasi (fallback).
        if (empty($this->role_filters)) {
            return;
        }

        // Expression "done" per tahap (dibuat defensif terhadap kolom yang belum ada).
        $marketing_done_parts = array();
        if ($this->has_field('periode')) {
            $marketing_done_parts[] = $this->not_empty_expr('periode');
        }
        // Minimal marketing: tanggal + (konsumen/customer)
        if ($this->has_field('tanggal')) {
            $konsumen_part = '';
            if ($this->has_field('konsumen')) {
                $konsumen_part = $this->not_empty_expr('konsumen');
            } elseif ($this->has_field('customer')) {
                $konsumen_part = $this->not_empty_expr('customer');
            }
            if ($konsumen_part !== '') {
                $marketing_done_parts[] = '(' . $this->not_empty_expr('tanggal') . ' AND ' . $konsumen_part . ')';
            }
        }
        $marketing_done = !empty($marketing_done_parts) ? '(' . implode(' OR ', $marketing_done_parts) . ')' : '(1=1)';

        $plotting_done_parts = array();
        if ($this->has_field('nopol')) {
            $plotting_done_parts[] = $this->not_empty_expr('nopol');
        }
        if ($this->has_field('id_asset')) {
            $plotting_done_parts[] = $this->not_empty_expr('id_asset');
        }
        $plotting_done = !empty($plotting_done_parts) ? '(' . implode(' OR ', $plotting_done_parts) . ')' : '(1=1)';

        $monitoring_done_parts = array();
        if ($this->has_field('uang_jalan')) {
            $monitoring_done_parts[] = $this->not_empty_expr('uang_jalan');
        }
        if ($this->has_field('tgl_muat_monitoring')) {
            $monitoring_done_parts[] = $this->not_empty_expr('tgl_muat_monitoring');
        }
        if ($this->has_field('tgl_bongkar')) {
            $monitoring_done_parts[] = $this->not_empty_expr('tgl_bongkar');
        }
        $monitoring_done = !empty($monitoring_done_parts) ? '(' . implode(' OR ', $monitoring_done_parts) . ')' : '(1=1)';

        $finance_done_parts = array();
        if ($this->has_field('no_invoice')) {
            $finance_done_parts[] = $this->not_empty_expr('no_invoice');
        }
        if ($this->has_field('status_pembayaran')) {
            $finance_done_parts[] = $this->not_empty_expr('status_pembayaran');
        }
        if ($this->has_field('tgl_invoice')) {
            $finance_done_parts[] = $this->not_empty_expr('tgl_invoice');
        }
        $finance_done = !empty($finance_done_parts) ? '(' . implode(' OR ', $finance_done_parts) . ')' : '(1=1)';

        // Stage conditions (berjenjang) untuk list per role.
        // Khusus Marketing: tetap boleh melihat semua record (supaya data yang baru diinput tetap muncul di list marketing
        // meskipun sudah masuk tahap plotting). Hak edit tetap dikunci di controller/list action.
        $cond_marketing = '(1=1)';
        // plotting: marketing_done dan belum plotting_done
        $cond_plotting = '(' . $marketing_done . ' AND NOT ' . $plotting_done . ')';
        // monitoring: plotting_done dan belum monitoring_done
        $cond_monitoring = '(' . $plotting_done . ' AND NOT ' . $monitoring_done . ')';
        // finance: monitoring_done dan belum finance_done, atau sudah selesai (finance_done)
        $cond_finance = '(' . $monitoring_done . ' AND NOT ' . $finance_done . ') OR (' . $finance_done . ')';

        $this->db->group_start();
        $first = true;
        foreach ($this->role_filters as $role) {
            $expr = '';
            if ($role === 'marketing') {
                $expr = $cond_marketing;
            } elseif ($role === 'plotting') {
                $expr = $cond_plotting;
            } elseif ($role === 'monitoring') {
                $expr = $cond_monitoring;
            } elseif ($role === 'finance') {
                $expr = '(' . $cond_finance . ')';
            }

            if ($expr !== '') {
                if ($first) {
                    $this->db->where($expr, null, false);
                    $first = false;
                } else {
                    $this->db->or_where($expr, null, false);
                }
            }
        }
        $this->db->group_end();
    }

    private function _get_datatables_query()
    {

        $this->db->where('user', $this->session->userdata('fullname'));
        $this->db->from($this->table);

        // Filter status (Selesai/Proses/All)
        $this->apply_completion_status_filter();

        // List menampilkan semua record; visibilitas kolom ditangani di controller/view.

        // Server-side global search
        $search_value = isset($_POST['search']['value']) ? trim((string) $_POST['search']['value']) : '';
        if ($search_value !== '') {
            $tanggal_col = $this->first_existing_field(array('tanggal', 'date'));
            $konsumen_col = $this->first_existing_field(array('konsumen', 'customer'));

            $search_columns = array();
            foreach (array('no_dok', 'periode', 'project', 'origin', 'destinasi', 'nopol', 'driver', 'lokasi_bongkar', 'no_invoice', 'status_pembayaran', 'status') as $c) {
                if ($this->has_field($c)) {
                    $search_columns[] = $c;
                }
            }
            if ($tanggal_col !== '') {
                $search_columns[] = $tanggal_col;
            }
            if ($konsumen_col !== '') {
                $search_columns[] = $konsumen_col;
            }

            // Pastikan tidak duplikat
            $search_columns = array_values(array_unique($search_columns));

            if (!empty($search_columns)) {
                $this->db->group_start();
                $first = true;
                foreach ($search_columns as $col) {
                    if ($first) {
                        $this->db->like($col, $search_value);
                        $first = false;
                    } else {
                        $this->db->or_like($col, $search_value);
                    }
                }
                $this->db->group_end();
            }
        }

        // Server-side ordering
        $order_applied = false;
        if (isset($_POST['order'][0]['column'])) {
            $col_idx = (int) $_POST['order'][0]['column'];
            $dir = isset($_POST['order'][0]['dir']) ? strtolower((string) $_POST['order'][0]['dir']) : 'asc';
            $dir = ($dir === 'desc') ? 'DESC' : 'ASC';

            $tanggal_col = $this->first_existing_field(array('tanggal', 'date'));
            $konsumen_col = $this->first_existing_field(array('konsumen', 'customer'));

            // Mapping index kolom DataTables (view) -> kolom DB / SQL expr
            // 0 No, 1 Action, 2 Periode, 3 Tanggal, 4 Konsumen, 5 Project, 6 Route,
            // 7 Nopol, 8 Driver, 9 Est Tanggal Tujuan, 10 Tanggal Muat, 11 Lokasi Bongkar,
            // 12 Uang Jalan, 13 No Invoice, 14 Status Bayar, 15 Progress, 16 Status, 17 Last Update
            $order_map = array();

            // No: gunakan id supaya tetap bisa disortir
            if ($this->has_field($this->id)) {
                $order_map[0] = array('type' => 'field', 'value' => $this->id);
            }
            if ($this->has_field('periode')) {
                $order_map[2] = array('type' => 'field', 'value' => 'periode');
            }
            if ($tanggal_col !== '') {
                $order_map[3] = array('type' => 'field', 'value' => $tanggal_col);
            }
            if ($konsumen_col !== '') {
                $order_map[4] = array('type' => 'field', 'value' => $konsumen_col);
            }
            if ($this->has_field('project')) {
                $order_map[5] = array('type' => 'field', 'value' => 'project');
            }

            // Route: origin + destinasi (kalau ada)
            $has_origin = $this->has_field('origin');
            $has_dest = $this->has_field('destinasi');
            if ($has_origin || $has_dest) {
                if ($has_origin && $has_dest) {
                    $route_expr = "TRIM(CONCAT(COALESCE(origin,''), CASE WHEN TRIM(COALESCE(origin,''))<>'' AND TRIM(COALESCE(destinasi,''))<>'' THEN ' - ' ELSE '' END, COALESCE(destinasi,'')))";
                } elseif ($has_origin) {
                    $route_expr = "TRIM(COALESCE(origin,''))";
                } else {
                    $route_expr = "TRIM(COALESCE(destinasi,''))";
                }
                $order_map[6] = array('type' => 'expr', 'value' => $route_expr);
            }
            if ($this->has_field('nopol')) {
                $order_map[7] = array('type' => 'field', 'value' => 'nopol');
            }
            if ($this->has_field('driver')) {
                $order_map[8] = array('type' => 'field', 'value' => 'driver');
            }
            if ($this->has_field('est_tgl_tujuan')) {
                $order_map[9] = array('type' => 'field', 'value' => 'est_tgl_tujuan');
            }
            if ($this->has_field('tgl_muat_monitoring')) {
                $order_map[10] = array('type' => 'field', 'value' => 'tgl_muat_monitoring');
            }
            if ($this->has_field('lokasi_bongkar')) {
                $order_map[11] = array('type' => 'field', 'value' => 'lokasi_bongkar');
            }
            if ($this->has_field('uang_jalan')) {
                $order_map[12] = array('type' => 'field', 'value' => 'uang_jalan');
            }
            if ($this->has_field('no_invoice')) {
                $order_map[13] = array('type' => 'field', 'value' => 'no_invoice');
            }
            if ($this->has_field('status_pembayaran')) {
                $order_map[14] = array('type' => 'field', 'value' => 'status_pembayaran');
            }

            // Progress / Status: berdasarkan stage completion
            $exprs = $this->stage_done_exprs();
            $marketing_done = $exprs['marketing_done'];
            $plotting_done = $exprs['plotting_done'];
            $monitoring_done = $exprs['monitoring_done'];
            $finance_done = $exprs['finance_done'];
            $progress_expr = '(CASE WHEN ' . $marketing_done . ' THEN 1 ELSE 0 END'
                . ' + CASE WHEN ' . $plotting_done . ' THEN 1 ELSE 0 END'
                . ' + CASE WHEN ' . $monitoring_done . ' THEN 1 ELSE 0 END'
                . ' + CASE WHEN ' . $finance_done . ' THEN 1 ELSE 0 END)';
            $complete_expr = '(' . $marketing_done . ' AND ' . $plotting_done . ' AND ' . $monitoring_done . ' AND ' . $finance_done . ')';
            $status_expr = '(CASE WHEN ' . $complete_expr . ' THEN 1 ELSE 0 END)';

            $order_map[15] = array('type' => 'expr', 'value' => $progress_expr);
            $order_map[16] = array('type' => 'expr', 'value' => $status_expr);

            // Last Update: greatest timestamp yang tersedia
            $last_update_expr = $this->last_update_order_expr();
            if ($last_update_expr !== '') {
                $order_map[17] = array('type' => 'expr', 'value' => $last_update_expr);
            }

            if (isset($order_map[$col_idx])) {
                $entry = $order_map[$col_idx];
                if (is_array($entry) && isset($entry['type'], $entry['value'])) {
                    if ($entry['type'] === 'field') {
                        $this->db->order_by($entry['value'], $dir);
                        $order_applied = true;
                    } elseif ($entry['type'] === 'expr') {
                        $this->db->order_by($entry['value'], $dir, false);
                        $order_applied = true;
                    }
                }
            }
        }

        if (!$order_applied) {
            // Default: tampilkan yang terbaru di atas.
            // Prioritas: Last Update (GREATEST timestamp) jika tersedia, fallback ke default order (biasanya id desc).
            $last_update_expr = $this->last_update_order_expr();
            if ($last_update_expr !== '') {
                $this->db->order_by($last_update_expr, 'DESC', false);
            } elseif (isset($this->order)) {
                $order = $this->order;
                $this->db->order_by(key($order), $order[key($order)]);
            }
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

    /**
     * Return all rows matching current filters (search, status) without paging.
     * Used for export to Excel.
     */
    public function get_all_for_export()
    {
        $this->_get_datatables_query();
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
        if ($this->session->userdata('core') != "all") {
            $this->db->where('user', $this->session->userdata('fullname'));
        }
        return $this->db->count_all_results();
    }

    public function get_by_id($id)
    {
        $this->db->where($this->id, $id);
        return $this->db->get($this->table)->row();
    }

    public function max_kode()
    {
        $this->db->select('no_dok');
        $where = 'id=(SELECT max(id) FROM sml_kertas_kerja where SUBSTRING(no_dok, 4, 4) = ' . date('ym') . ')';
        $this->db->where($where);
        $query = $this->db->get('sml_kertas_kerja');
        return $query;
    }

    public function save($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function delete($id)
    {
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
    }
}
