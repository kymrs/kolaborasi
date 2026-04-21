<?php
defined('BASEPATH') or exit('No direct script access allowed');

// for Excel export
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class Sml_kertas_kerja extends CI_Controller
{

    private function first_existing_field_from_list($fields, $candidates)
    {
        $fields = array_map('strtolower', (array) $fields);
        foreach ((array) $candidates as $c) {
            $c = strtolower(trim((string) $c));
            if ($c !== '' && in_array($c, $fields, true)) {
                return $c;
            }
        }
        return '';
    }

    private function json_output($payload)
    {
        $this->output
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($payload));
    }

    private function now_datetime()
    {
        return date('Y-m-d H:i:s');
    }

    private function is_filled_datetime($v)
    {
        if ($v === null) {
            return false;
        }
        $s = trim((string) $v);
        if ($s === '' || $s === '0') {
            return false;
        }
        if ($s === '0000-00-00' || $s === '0000-00-00 00:00:00') {
            return false;
        }
        return true;
    }

    private function pick_highest_role($roles_for_user)
    {
        $roles_for_user = is_array($roles_for_user) ? $roles_for_user : array();
        $roles_lower = array();
        foreach ($roles_for_user as $r) {
            $r = strtolower(trim((string) $r));
            if ($r !== '') {
                $roles_lower[] = $r;
            }
        }

        $order = array('marketing', 'plotting', 'monitoring', 'finance');
        $best = '';
        $bestRank = -1;
        foreach ($roles_lower as $r) {
            $rank = array_search($r, $order, true);
            if ($rank !== false && $rank > $bestRank) {
                $bestRank = $rank;
                $best = $r;
            }
        }
        return $best;
    }

    /**
     * Scan the read_form view to collect all input field names used.
     * This ensures exports only include columns that the user can see in the form.
     */
    private function form_field_names()
    {
        $names = array();
        $path = APPPATH . 'views/backend/sml_kertas_kerja/sml_kertas_kerja_form.php';
        if (is_file($path)) {
            $content = file_get_contents($path);
            if ($content !== false) {
                if (preg_match_all("/'name'\s*=>\s*'([^']*)'/", $content, $m)) {
                    foreach ($m[1] as $n) {
                        $n = trim((string) $n);
                        if ($n !== '') {
                            $names[] = $n;
                        }
                    }
                }
            }
        }
        return array_values(array_unique($names));
    }

    private function konsumen_master_options()
    {
        $options = array();

        // Safeguard jika database belum ready
        if (!isset($this->db) || !is_object($this->db)) {
            return $options;
        }

        // Hindari error jika tabel belum ada
        if (method_exists($this->db, 'table_exists') && !$this->db->table_exists('sml_konsumen')) {
            return $options;
        }

        $this->db->distinct();
        $this->db->select('nama');
        $this->db->from('sml_konsumen');
        $this->db->where('nama IS NOT NULL', null, false);
        $this->db->where("TRIM(nama) <> ''", null, false);
        $this->db->order_by('nama', 'ASC');
        $rows = $this->db->get()->result();

        foreach ((array) $rows as $r) {
            if (is_object($r) && isset($r->nama)) {
                $options[] = (string) $r->nama;
            }
        }

        return $options;
    }

    private function destinasi_master_options()
    {
        $options = array();

        if (!isset($this->db) || !is_object($this->db)) {
            return $options;
        }

        if (method_exists($this->db, 'table_exists') && !$this->db->table_exists('sml_destinasi')) {
            return $options;
        }

        $fields = (array) $this->db->list_fields('sml_destinasi');
        $destCol = $this->first_existing_field_from_list($fields, array('destinasi', 'destination', 'nama_destinasi', 'tujuan'));
        if ($destCol === '') {
            return $options;
        }

        $this->db->distinct();
        $this->db->select($destCol . ' AS destinasi', false);
        $this->db->from('sml_destinasi');
        $this->db->where($destCol . ' IS NOT NULL', null, false);
        $this->db->where("TRIM(" . $destCol . ") <> ''", null, false);
        $this->db->order_by($destCol, 'ASC');
        $rows = $this->db->get()->result_array();

        foreach ((array) $rows as $r) {
            $d = isset($r['destinasi']) ? trim((string) $r['destinasi']) : '';
            if ($d !== '') {
                $options[] = $d;
            }
        }

        return $options;
    }

    private function coerce_numeric_fields(&$data, $table_fields_map, $field_names)
    {
        if (!is_array($data) || empty($data) || empty($field_names)) {
            return;
        }

        foreach ((array) $field_names as $field) {
            if (!isset($table_fields_map[$field])) {
                continue;
            }
            if (!array_key_exists($field, $data)) {
                continue;
            }
            $data[$field] = $this->parse_number($data[$field]);
        }
    }

    private function parse_number($v)
    {
        if ($v === null) {
            return 0.0;
        }
        if (is_int($v) || is_float($v)) {
            return (float) $v;
        }

        $s = trim((string) $v);
        if ($s === '') {
            return 0.0;
        }

        // Umum: input rupiah diformat 1.000.000 (titik ribuan)
        // Normalisasi: buang titik ribuan, ubah koma desimal jadi titik.
        $s = str_replace('.', '', $s);
        $s = str_replace(',', '.', $s);
        $s = preg_replace('/[^0-9\.-]/', '', $s);

        return is_numeric($s) ? (float) $s : 0.0;
    }

    private function parse_time_to_minutes($v)
    {
        if ($v === null) {
            return null;
        }
        $s = trim((string) $v);
        if ($s === '') {
            return null;
        }

        // Accept HH:MM or HH:MM:SS
        if (!preg_match('/^([01]?\d|2[0-3]):([0-5]\d)(?::([0-5]\d))?$/', $s, $m)) {
            return null;
        }

        $h = (int) $m[1];
        $mi = (int) $m[2];
        return ($h * 60) + $mi;
    }

    private function format_minutes_to_hhmm($total_minutes)
    {
        if ($total_minutes === null) {
            return '';
        }
        $mins = (int) $total_minutes;
        if ($mins < 0) {
            $mins = 0;
        }
        $h = (int) floor($mins / 60);
        $m = (int) ($mins % 60);
        return str_pad((string) $h, 2, '0', STR_PAD_LEFT) . ':' . str_pad((string) $m, 2, '0', STR_PAD_LEFT);
    }

    private function apply_marketing_calculations(&$data, $post, $table_fields_map)
    {
        // Logic 1: multidrop_selling = multidrop_s * qty_selling
        // Logic 2: selling_2 = selling + tkbm_selling + multidrop_selling + inap_selling + additional_cost
        // Logic 3: multidrop_buying = multidrop_b * qty_buying
        // Logic 4: buying = buying_uj + multidrop_buying + tkbm_buying + inap_buying + additional_cost2
        // Logic 5: estimasi_margin = selling_2 - buying
        // Catatan: hanya diset jika kolom ada di tabel.

        $multidrop_s = $this->parse_number(isset($post['multidrop_s']) ? $post['multidrop_s'] : null);
        $qty_selling = $this->parse_number(isset($post['qty_selling']) ? $post['qty_selling'] : null);
        $selling_1 = $this->parse_number(isset($post['selling']) ? $post['selling'] : null);
        $tkbm_selling = $this->parse_number(isset($post['tkbm_selling']) ? $post['tkbm_selling'] : null);
        $inap_selling = $this->parse_number(isset($post['inap_selling']) ? $post['inap_selling'] : null);
        $additional_cost = $this->parse_number(isset($post['additional_cost']) ? $post['additional_cost'] : null);

        $multidrop_b = $this->parse_number(isset($post['multidrop_b']) ? $post['multidrop_b'] : null);
        $qty_buying = $this->parse_number(isset($post['qty_buying']) ? $post['qty_buying'] : null);
        $buying_uj = $this->parse_number(isset($post['buying_uj']) ? $post['buying_uj'] : null);
        $tkbm_buying = $this->parse_number(isset($post['tkbm_buying']) ? $post['tkbm_buying'] : null);
        $inap_buying = $this->parse_number(isset($post['inap_buying']) ? $post['inap_buying'] : null);
        $additional_cost2 = $this->parse_number(isset($post['additional_cost2']) ? $post['additional_cost2'] : null);

        $multidrop_selling = $multidrop_s * $qty_selling;
        $selling_2 = $selling_1 + $multidrop_selling + $tkbm_selling + $inap_selling + $additional_cost;

        $multidrop_buying = $multidrop_b * $qty_buying;
        $buying = $buying_uj + $multidrop_buying + $tkbm_buying + $inap_buying + $additional_cost2;
        $estimasi_margin = $selling_2 - $buying;

        if (isset($table_fields_map['multidrop_selling'])) {
            $data['multidrop_selling'] = $multidrop_selling;
        }
        if (isset($table_fields_map['selling_2'])) {
            $data['selling_2'] = $selling_2;
        }

        if (isset($table_fields_map['multidrop_buying'])) {
            $data['multidrop_buying'] = $multidrop_buying;
        }
        if (isset($table_fields_map['buying'])) {
            $data['buying'] = $buying;
        }
        if (isset($table_fields_map['estimasi_margin'])) {
            $data['estimasi_margin'] = $estimasi_margin;
        }
    }

    private function role_usernames()
    {
        // Deprecated: mapping hardcode sudah diganti DB (sml_akses_kertas_kerja).
        // Dipertahankan hanya sebagai fallback jika tabel belum dibuat.
        return array(
            // 'marketing' => array('admin', 'tyas'),
            // 'plotting' => array('rahmat'),
            // 'monitoring' => array('arya'),
            // 'finance' => array('marimar'),
        );
    }

    private function roles_for_user()
    {
        $username = strtolower(trim((string) $this->session->userdata('username')));

        if ($username === '') {
            $username = strtolower(trim((string) $this->session->userdata('user')));
        }

        if ($username === '') {
            $username = strtolower(trim((string) $this->session->userdata('uname')));
        }

        // Jika tabel mapping sudah ada, pakai DB sebagai sumber kebenaran.
        if (isset($this->M_sml_akses_kertas_kerja)) {
            return $this->M_sml_akses_kertas_kerja->roles_for_username($username);
        }

        // Fallback: hardcode (untuk masa transisi sebelum tabel dibuat)
        $roles = array();
        foreach ($this->role_usernames() as $role => $users) {
            $users_lower = array_map('strtolower', (array) $users);
            if (in_array($username, $users_lower, true)) {
                $roles[] = $role;
            }
        }
        return $roles;
    }

    private function build_role_context($id)
    {
        $roles_for_user = $this->roles_for_user();

        $mode = strtolower(trim((string) $this->input->get('mode', true)));
        $all_modes = array('marketing', 'plotting', 'monitoring', 'finance');
        if (!in_array($mode, $all_modes, true)) {
            $mode = '';
        }

        $active_role = !empty($roles_for_user) ? $roles_for_user[0] : '';
        if (!empty($mode) && in_array($mode, $roles_for_user, true)) {
            $active_role = $mode;
        }

        $is_authorized = !empty($roles_for_user);
        $is_create = ((int) $id === 0);
        $is_marketing = ($active_role === 'marketing');
        $can_create = ($is_marketing && $is_authorized);
        $can_update = ($is_authorized && !$is_create);

        return array(
            'roles_for_user' => $roles_for_user,
            'active_role' => $active_role,
            'is_authorized' => $is_authorized,
            'is_create' => $is_create,
            'is_marketing' => $is_marketing,
            'can_create' => $can_create,
            'can_update' => $can_update,
        );
    }

    private function has_role($role)
    {
        return in_array($role, $this->roles_for_user(), true);
    }

    private function get_stage_for_row($row)
    {
        if (!$row) {
            return '';
        }

        $getVal = function ($obj, $key, $default = '') {
            return (is_object($obj) && isset($obj->{$key}) && $obj->{$key} !== null) ? $obj->{$key} : $default;
        };

        $isFilled = function ($v) {
            if ($v === null) {
                return false;
            }
            $s = trim((string) $v);
            if ($s === '' || $s === '0') {
                return false;
            }
            // MySQL zero-date / zero-datetime dianggap belum diisi
            if ($s === '0000-00-00' || $s === '0000-00-00 00:00:00') {
                return false;
            }
            return true;
        };

        $tanggal = $getVal($row, 'tanggal', $getVal($row, 'date'));
        $konsumen = $getVal($row, 'konsumen', $getVal($row, 'customer'));
        $nopol = $getVal($row, 'nopol');
        $uang_jalan = $getVal($row, 'uang_jalan');
        $no_invoice = $getVal($row, 'no_invoice');
        $status_pembayaran = $getVal($row, 'status_pembayaran');

        $marketing_done = $isFilled($getVal($row, 'periode')) || ($isFilled($tanggal) && $isFilled($konsumen));
        // Plotting dianggap selesai jika nopol sudah terisi.
        // (id_asset sekarang dipakai sebagai pilihan Asset/Vendor sehingga selalu terisi dan tidak cocok untuk penanda progress.)
        $plotting_done = $isFilled($nopol);
        $monitoring_done = $isFilled($uang_jalan) || $isFilled($getVal($row, 'tgl_muat_monitoring')) || $isFilled($getVal($row, 'tgl_bongkar'));
        $finance_done = $isFilled($no_invoice) || $isFilled($status_pembayaran) || $isFilled($getVal($row, 'tgl_invoice'));

        if (!$marketing_done) {
            return 'marketing';
        }
        if (!$plotting_done) {
            return 'plotting';
        }
        if (!$monitoring_done) {
            return 'monitoring';
        }
        if (!$finance_done) {
            return 'finance';
        }
        // selesai (tetap finance)
        return 'finance';
    }

    private function enforce_any_role()
    {
        // Minimal proteksi: hanya user yang punya mapping role boleh akses data kertas kerja.
        if (empty($this->roles_for_user())) {
            redirect('sml_kertas_kerja');
            return;
        }
    }

    private function allowed_view_fields_for_role($role)
    {
        // Visibilitas kumulatif:
        // marketing: marketing
        // plotting: marketing + plotting
        // monitoring: marketing + plotting + monitoring
        // finance: semuanya
        $role = strtolower(trim((string) $role));
        $order = array('marketing', 'plotting', 'monitoring', 'finance');

        $allowed = array('id');
        foreach ($order as $r) {
            $allowed = array_merge($allowed, $this->allowed_fields_for_role($r));
            if ($r === $role) {
                break;
            }
        }

        // Timestamp per section (kumulatif sesuai role)
        foreach ($order as $r) {
            $allowed = array_merge($allowed, array('created_at_' . $r, 'updated_at_' . $r));
            if ($r === $role) {
                break;
            }
        }

        // Tambahan field umum jika ada
        $allowed = array_merge($allowed, array('input_role', 'user', 'created_at', 'updated_at', 'edit_at'));
        return array_values(array_unique($allowed));
    }

    private function allowed_fields_for_role($role)
    {
        // Field yang boleh diupdate per role. Ini juga jadi proteksi dari manipulasi POST.
        $map = array(
            'marketing' => array(
                'no_dok',
                'periode', 'tanggal', 'konsumen', 'project', 'origin', 'destinasi', 'wilayah', 'tipe_trip',
                'jenis_unit', 'service', 'tipe_kiriman',
                'status',
                'selling', 'multidrop_s', 'qty_selling', 'multidrop_selling', 'tkbm_selling', 'inap_selling', 'additional_cost', 'keterangan_addcost', 'selling_2',
                'pelaksana', 'buying_uj', 'multidrop_b', 'qty_buying', 'multidrop_buying', 'tkbm_buying', 'inap_buying', 'additional_cost2', 'buying', 'estimasi_margin',
            ),
            'plotting' => array(
                'id_asset', 'vendor', 'nopol', 'driver', 'no_hp', 'driver2', 'no_hp2', 'tipe_unit',
                'tgl_stnk', 'tgl_keur', 'tgl_berangkat', 'sla', 'est_tgl_tujuan', 'km_berangkat', 'buying_actual',
            ),
            'monitoring' => array(
                'no_do', 'tgl_muat_monitoring', 'waktu_muat', 'lokasi_bongkar', 'tgl_bongkar', 'waktu_bongkar',
                'aktual_jam', 'tgl_kirim_sj', 'no_resi_kirim', 'tgl_masuk_pool', 'uang_balikan', 'tgl_transfer',
            ),
            'finance' => array(
                'no_invoice', 'tgl_invoice', 'tgl_kirim_inv', 'customer', 'project_finance', 'ppn%', 'nominal', 'pph2', 'ppn',
                'total_tagihan', 'jatuh_tempo', 'tgl_bayar', 'nominal_pembayaran', 'status_pembayaran', 'selisih_pembayaran',
                'actual_margin', 'actual_margin_persen', 'roi',
            ),
        );

        $role = strtolower(trim((string) $role));
        return isset($map[$role]) ? $map[$role] : array();
    }

    function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_sml_kertas_kerja');
        $this->load->model('backend/M_sml_kertas_kerja_transaksi');
        $this->load->model('backend/M_sml_driver');
        $this->load->model('backend/M_sml_akses_kertas_kerja');
        $this->M_login->getsecurity();
    }

    public function driver_lookup()
    {
        // Endpoint untuk Select2: ambil data driver dari tabel sml_driver.
        // Dipakai saat vendor belum dipilih (driver dipilih dari master).
        $this->enforce_any_role();

        $q = trim((string) $this->input->get('q', true));
        $rows = array();

        if (isset($this->M_sml_driver)) {
            $rows = $this->M_sml_driver->search($q, 20);
        }

        $results = array();
        foreach ((array) $rows as $r) {
            $results[] = array(
                'id' => isset($r['id']) ? $r['id'] : (isset($r['driver']) ? $r['driver'] : ''),
                'text' => isset($r['driver']) ? $r['driver'] : '',
                'nopol' => isset($r['nopol']) ? $r['nopol'] : '',
                'no_hp' => isset($r['no_hp']) ? $r['no_hp'] : '',
            );
        }

        echo json_encode(array('results' => $results));
    }

    public function driver_nopol_list()
    {
        // Endpoint untuk Select2: list nopol dari tabel sml_driver.
        $this->enforce_any_role();

        if (!isset($this->db) || !is_object($this->db) || (method_exists($this->db, 'table_exists') && !$this->db->table_exists('sml_driver'))) {
            $this->json_output(array('results' => array()));
            return;
        }

        $fields = (array) $this->db->list_fields('sml_driver');
        $nopolCol = $this->first_existing_field_from_list($fields, array('nopol', 'no_pol', 'no_polisi', 'plat', 'plat_nomor'));
        if ($nopolCol === '') {
            $this->json_output(array('results' => array()));
            return;
        }

        $this->db->distinct();
        $this->db->select($nopolCol . ' AS nopol', false);
        $this->db->from('sml_driver');
        $this->db->where($nopolCol . ' IS NOT NULL', null, false);
        $this->db->where("TRIM(" . $nopolCol . ") <> ''", null, false);
        $this->db->order_by($nopolCol, 'ASC');
        $rows = $this->db->get()->result_array();

        $results = array();
        foreach ((array) $rows as $r) {
            $np = isset($r['nopol']) ? trim((string) $r['nopol']) : '';
            if ($np === '') {
                continue;
            }
            $results[] = array('id' => $np, 'text' => $np);
        }

        $this->json_output(array('results' => $results));
    }

    public function driver_detail_by_nopol()
    {
        // Detail driver berdasarkan nopol.
        // Output disesuaikan dengan kolom: (id, nopol, nama_driver, no_hp, driver2, no_hp2, tipe_unit, tgl_stnk, tgl_keur)
        $this->enforce_any_role();

        $nopol = trim((string) $this->input->get('nopol', true));
        if ($nopol === '') {
            $this->json_output(array());
            return;
        }

        if (!isset($this->db) || !is_object($this->db) || (method_exists($this->db, 'table_exists') && !$this->db->table_exists('sml_driver'))) {
            $this->json_output(array());
            return;
        }

        $fields = (array) $this->db->list_fields('sml_driver');

        $idCol = $this->first_existing_field_from_list($fields, array('id', 'driver_id'));
        $nopolCol = $this->first_existing_field_from_list($fields, array('nopol', 'no_pol', 'no_polisi', 'plat', 'plat_nomor'));
        $nameCol = $this->first_existing_field_from_list($fields, array('nama_driver', 'driver', 'nama', 'name'));
        $hpCol = $this->first_existing_field_from_list($fields, array('no_hp', 'hp', 'telp', 'telepon', 'phone', 'no_telp'));
        $name2Col = $this->first_existing_field_from_list($fields, array('nama_driver2', 'driver2', 'nama_driver_2', 'nama2', 'driver_2'));
        $hp2Col = $this->first_existing_field_from_list($fields, array('no_hp2', 'hp2', 'telp2', 'telepon2', 'phone2', 'no_telp2'));
        $tipeCol = $this->first_existing_field_from_list($fields, array('tipe_unit', 'type_unit', 'unit_type', 'tipe', 'type'));
        $stnkCol = $this->first_existing_field_from_list($fields, array('tgl_stnk', 'tanggal_stnk', 'stnk_date'));
        $keurCol = $this->first_existing_field_from_list($fields, array('tgl_keur', 'tanggal_keur', 'keur_date'));

        if ($nopolCol === '') {
            $this->json_output(array());
            return;
        }

        $this->db->from('sml_driver');
        if ($idCol !== '') {
            $this->db->select($idCol . ' AS id', false);
        } else {
            $this->db->select("'' AS id", false);
        }
        $this->db->select($nopolCol . ' AS nopol', false);
        $this->db->select(($nameCol !== '' ? $nameCol : "''") . ' AS nama_driver', false);
        $this->db->select(($hpCol !== '' ? $hpCol : "''") . ' AS no_hp', false);
        $this->db->select(($name2Col !== '' ? $name2Col : "''") . ' AS driver2', false);
        $this->db->select(($hp2Col !== '' ? $hp2Col : "''") . ' AS no_hp2', false);
        $this->db->select(($tipeCol !== '' ? $tipeCol : "''") . ' AS tipe_unit', false);
        $this->db->select(($stnkCol !== '' ? $stnkCol : "''") . ' AS tgl_stnk', false);
        $this->db->select(($keurCol !== '' ? $keurCol : "''") . ' AS tgl_keur', false);
        $this->db->where($nopolCol, $nopol);
        $row = $this->db->get()->row_array();

        $this->json_output($row ? $row : array());
    }

    public function destinasi_harga_by_destinasi()
    {
        // Ambil harga dari tabel sml_destinasi berdasarkan destinasi.
        $this->enforce_any_role();

        if (!isset($this->db) || !is_object($this->db) || (method_exists($this->db, 'table_exists') && !$this->db->table_exists('sml_destinasi'))) {
            $this->json_output(array('harga' => 0));
            return;
        }

        $dest = trim((string) $this->input->get('destinasi', true));
        if ($dest === '') {
            $this->json_output(array('harga' => 0));
            return;
        }

        $fields = (array) $this->db->list_fields('sml_destinasi');
        $destCol = $this->first_existing_field_from_list($fields, array('destinasi', 'destination', 'nama_destinasi', 'tujuan'));
        $hargaCol = $this->first_existing_field_from_list($fields, array('harga', 'price', 'tarif', 'biaya'));
        if ($destCol === '' || $hargaCol === '') {
            $this->json_output(array('harga' => 0));
            return;
        }

        $this->db->select($hargaCol . ' AS harga', false);
        $this->db->from('sml_destinasi');
        $this->db->where($destCol, $dest);
        $this->db->limit(1);
        $row = $this->db->get()->row_array();

        $harga = 0;
        if (is_array($row) && array_key_exists('harga', $row)) {
            $harga = $this->parse_number($row['harga']);
        }

        $this->json_output(array('harga' => $harga));
    }

    public function index()
    {
        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        ($akses->view_level == 'N' ? redirect('auth') : '');

        // Role context untuk menentukan tombol/kolom di list.
        $roles_for_user = $this->roles_for_user();
        $data['roles_for_user'] = $roles_for_user;
        $data['viewer_role'] = !empty($roles_for_user) ? $roles_for_user[0] : '';
        $data['is_authorized'] = !empty($roles_for_user);

        $data['add'] = $akses->add_level;
        $data['title'] = "backend/sml_kertas_kerja/sml_kertas_kerja_list";
        $data['titleview'] = "Kertas Kerja";
        $this->load->view('backend/home', $data);
    }

    function get_list()
    {
        $roles_for_user = $this->roles_for_user();

        // Jika user tidak punya akses mapping, jangan tampilkan data apapun.
        if (empty($roles_for_user)) {
            $output = array(
                'draw' => isset($_POST['draw']) ? $_POST['draw'] : 0,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => array(),
            );
            echo json_encode($output);
            return;
        }

        // List tampilkan semua record; kolom yang terlihat disesuaikan role yang login.
        $list = $this->M_sml_kertas_kerja->get_datatables();
        $data = array();
        $no = $_POST['start'];
        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());

        $getVal = function ($obj, $key, $default = '') {
            return (is_object($obj) && isset($obj->{$key}) && $obj->{$key} !== null) ? $obj->{$key} : $default;
        };

        $isFilled = function ($v) {
            if ($v === null) {
                return false;
            }
            $s = trim((string) $v);
            if ($s === '' || $s === '0') {
                return false;   
            }
            if ($s === '0000-00-00' || $s === '0000-00-00 00:00:00') {
                return false;
            }
            return true;
        };

        $badge = function ($label, $ok) {
            $cls = $ok ? 'badge-success' : 'badge-secondary';
            return '<span class="badge ' . $cls . '" style="margin-right:4px;">' . $label . '</span>';
        };

        $formatIdDatetime = function ($v) use ($isFilled) {
            if (!$isFilled($v) || $v === '-') {
                return '-';
            }

            try {
                $dt = new DateTime((string) $v);
            } catch (Exception $e) {
                return (string) $v;
            }

            $months = array(
                1 => 'Januari',
                2 => 'Februari',
                3 => 'Maret',
                4 => 'April',
                5 => 'Mei',
                6 => 'Juni',
                7 => 'Juli',
                8 => 'Agustus',
                9 => 'September',
                10 => 'Oktober',
                11 => 'November',
                12 => 'Desember',
            );

            $day = (int) $dt->format('d');
            $monthNum = (int) $dt->format('n');
            $year = $dt->format('Y');
            $time = $dt->format('H:i:s');
            $monthName = isset($months[$monthNum]) ? $months[$monthNum] : $dt->format('m');

            return $day . ' ' . $monthName . ' ' . $year . ' ' . $time;
        };

        $formatIdDate = function ($v) use ($isFilled) {
            if (!$isFilled($v) || $v === '-') {
                return '-';
            }

            try {
                $dt = new DateTime((string) $v);
            } catch (Exception $e) {
                return (string) $v;
            }

            $months = array(
                1 => 'Januari',
                2 => 'Februari',
                3 => 'Maret',
                4 => 'April',
                5 => 'Mei',
                6 => 'Juni',
                7 => 'Juli',
                8 => 'Agustus',
                9 => 'September',
                10 => 'Oktober',
                11 => 'November',
                12 => 'Desember',
            );

            $day = (int) $dt->format('d');
            $monthNum = (int) $dt->format('n');
            $year = $dt->format('Y');
            $monthName = isset($months[$monthNum]) ? $months[$monthNum] : $dt->format('m');

            return $day . ' ' . $monthName . ' ' . $year;
        };

        // Role utama viewer (dipakai untuk menentukan kolom list yang tampil)
        $viewer_role = !empty($roles_for_user) ? $roles_for_user[0] : '';

        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $no;
            $action = '';

            if ($akses->view_level == 'Y') {
                $action .= '<a href="sml_kertas_kerja/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>&nbsp;';
            }

            // Tombol transaksi/timestamp tidak ditampilkan di list kertas kerja (akses via menu/halaman transaksi).

            $periode_raw = $getVal($field, 'periode');
            if (!$isFilled($periode_raw)) {
                // fallback: ambil dari tanggal jika periode belum tersimpan
                $tanggal_tmp = $getVal($field, 'tanggal', $getVal($field, 'date'));
                if ($isFilled($tanggal_tmp) && preg_match('/^(\d{4})-(\d{2})/', (string) $tanggal_tmp, $m)) {
                    $periode_raw = $m[1] . $m[2];
                }
            }

            // Normalisasi format periode jadi YYYYMM
            $periode_display = '';
            if ($isFilled($periode_raw)) {
                $p = trim((string) $periode_raw);
                if (preg_match('/^(\d{4})-(\d{2})/', $p, $m)) {
                    $periode_display = $m[1] . $m[2];
                } elseif (preg_match('/^(\d{6})$/', $p, $m)) {
                    $periode_display = $m[1];
                } else {
                    $periode_display = $p;
                }
            } else {
                $periode_display = '-';
            }

            // Marketing summary
            $tanggal_raw = $getVal($field, 'tanggal', $getVal($field, 'date'));
            $konsumen_raw = $getVal($field, 'konsumen', $getVal($field, 'customer'));
            $project_raw = $getVal($field, 'project');
            $origin = $getVal($field, 'origin');
            $destinasi = $getVal($field, 'destinasi');
            $route = trim($origin . (empty($origin) || empty($destinasi) ? '' : ' - ') . $destinasi);

            $status_raw = $getVal($field, 'status');
            $status_done = (strtolower(trim((string) $status_raw)) === 'done');

            // Plotting summary
            $nopol_raw = $getVal($field, 'nopol');
            $driver_raw = $getVal($field, 'driver');
            $est_tgl_tujuan_raw = $getVal($field, 'est_tgl_tujuan');

            // Monitoring summary
            $uang_jalan_raw = $getVal($field, 'uang_jaland');
            $tgl_muat_monitoring_raw = $getVal($field, 'tgl_muat_monitoring');
            $lokasi_bongkar_raw = $getVal($field, 'lokasi_bongkar');

            // Finance summary
            $no_invoice_raw = $getVal($field, 'no_invoice');
            $status_pembayaran_raw = $getVal($field, 'status_pembayaran');

            // Progress rules dihitung dari data asli (bukan yang dimasking untuk tampilan)
            $marketing_done = $isFilled($getVal($field, 'periode')) || ($isFilled($tanggal_raw) && $isFilled($konsumen_raw));
            // Plotting dianggap selesai jika nopol sudah terisi.
            $plotting_done = $isFilled($nopol_raw);
            $monitoring_done = $isFilled($uang_jalan_raw) || $isFilled($getVal($field, 'tgl_muat_monitoring')) || $isFilled($getVal($field, 'tgl_bongkar'));
            $finance_done = $isFilled($no_invoice_raw) || $isFilled($status_pembayaran_raw) || $isFilled($getVal($field, 'tgl_invoice'));

            // Nilai yang akan ditampilkan (bisa dimasking sesuai role login)
            $tanggal = $isFilled($tanggal_raw) ? $formatIdDate($tanggal_raw) : '-';
            $konsumen = $konsumen_raw;
            $project = $project_raw;
            $nopol = $isFilled($nopol_raw) ? $nopol_raw : '-';
            $driver = $isFilled($driver_raw) ? $driver_raw : '-';
            $est_tgl_tujuan = $isFilled($est_tgl_tujuan_raw) ? $formatIdDate($est_tgl_tujuan_raw) : '-';
            $tgl_muat_monitoring = $isFilled($tgl_muat_monitoring_raw) ? $formatIdDate($tgl_muat_monitoring_raw) : '-';
            $lokasi_bongkar = $lokasi_bongkar_raw;

            // Format Uang Jalan sebagai Rupiah (tetap tampil Rp 0 jika nilainya 0)
            $uang_jalan = '';
            if ($uang_jalan_raw !== null && trim((string) $uang_jalan_raw) !== '') {
                $uang_jalan = 'Rp ' . number_format($this->parse_number($uang_jalan_raw), 0, ',', '.');
            }
            $no_invoice = $no_invoice_raw;
            $status_pembayaran = $status_pembayaran_raw;

            // Masking kolom list sesuai role yang login (hanya tampilkan data role tersebut)
            // Identitas dasar yang tetap ditampilkan: no_dok + route
            if ($viewer_role === 'marketing') {
                // marketing hanya lihat marketing (kolom lain dikosongkan)
                $nopol = '';
                $driver = '';
                $est_tgl_tujuan = '';
                $tgl_muat_monitoring = '';
                $lokasi_bongkar = '';
                $uang_jalan = '';
                $no_invoice = '';
                $status_pembayaran = '';
            } elseif ($viewer_role === 'plotting') {
                // plotting hanya lihat plotting (marketing field selain route dikosongkan)
                $tanggal = '';
                $konsumen = '';
                $project = '';
                $tgl_muat_monitoring = '';
                $lokasi_bongkar = '';
                $uang_jalan = '';
                $no_invoice = '';
                $status_pembayaran = '';
            } elseif ($viewer_role === 'monitoring') {
                // monitoring hanya lihat monitoring
                $tanggal = '';
                $konsumen = '';
                $project = '';
                $nopol = '';
                $driver = '';
                $est_tgl_tujuan = '';
                $no_invoice = '';
                $status_pembayaran = '';
            } elseif ($viewer_role === 'finance') {
                // finance hanya lihat finance
                $tanggal = '';
                $konsumen = '';
                $project = '';
                $nopol = '';
                $driver = '';
                $est_tgl_tujuan = '';
                $tgl_muat_monitoring = '';
                $lokasi_bongkar = '';
                $uang_jalan = '';
            }

            // Tombol update per role: create dilakukan oleh marketing (add), role lain update record existing.
            if ($akses->edit_level == 'Y') {
                // Marketing edit hanya saat stage marketing (belum marketing_done)
                if (in_array('marketing', $roles_for_user, true) && !$marketing_done) {
                    $action .= '<a href="sml_kertas_kerja/edit_form/' . $field->id . '?mode=marketing" class="btn btn-primary btn-circle btn-sm" title="Marketing"><i class="fa fa-pen"></i></a>&nbsp;';
                }
                if (in_array('plotting', $roles_for_user, true) && $marketing_done && !$plotting_done) {
                    $action .= '<a href="sml_kertas_kerja/edit_form/' . $field->id . '?mode=plotting" class="btn btn-warning btn-circle btn-sm" title="Isi Plotting"><i class="fa fa-truck"></i></a>&nbsp;';
                }
                if (in_array('monitoring', $roles_for_user, true) && $plotting_done && !$monitoring_done) {
                    $action .= '<a href="sml_kertas_kerja/edit_form/' . $field->id . '?mode=monitoring" class="btn btn-info btn-circle btn-sm" title="Isi Monitoring"><i class="fa fa-route"></i></a>&nbsp;';
                }
                if (in_array('finance', $roles_for_user, true) && $monitoring_done && !$finance_done) {
                    if ($status_done) {
                        $action .= '<a href="sml_kertas_kerja/edit_form/' . $field->id . '?mode=finance" class="btn btn-success btn-circle btn-sm" title="Isi Finance"><i class="fa fa-file-invoice-dollar"></i></a>&nbsp;';
                    } else {
                        $onclick = "if (typeof Swal !== 'undefined') { Swal.fire({icon:'warning', title:'Belum bisa isi Finance', text:'Status Marketing masih On Going.'}); } else { alert('Belum bisa isi Finance: Status Marketing masih On Going.'); } return false;";
                        $action .= '<a href="javascript:void(0)" onclick="' . $onclick . '" class="btn btn-success btn-circle btn-sm" title="Isi Finance"><i class="fa fa-file-invoice-dollar"></i></a>&nbsp;';
                    }
                }
            }

            if ($akses->delete_level == 'Y') {
                $action .= '<a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;';
            }

            // Tombol PDF di list dihapus sesuai kebutuhan.

            $row[] = $action;

            $progress_count = 0;
            foreach (array($marketing_done, $plotting_done, $monitoring_done, $finance_done) as $flag) {
                if ($flag) {
                    $progress_count++;
                }
            }

            $progress_html =
                $badge('M', $marketing_done) .
                $badge('P', $plotting_done) .
                $badge('Mo', $monitoring_done) .
                $badge('F', $finance_done) .
                '<span class="text-muted" style="margin-left:6px;">' . $progress_count . '/4</span>';

            $is_complete = ($progress_count === 4);
            $status_html = $is_complete
                ? '<span class="badge badge-success">SELESAI</span>'
                : '<span class="badge badge-warning">PROSES</span>';

            // Last update: ambil timestamp terbaru dari kolom yang tersedia.
            $timestamp_keys = array(
                // Section timestamps (konsep baru)
                'updated_at_finance', 'updated_at_monitoring', 'updated_at_plotting', 'updated_at_marketing',
                'created_at_finance', 'created_at_monitoring', 'created_at_plotting', 'created_at_marketing',
                // Global / legacy
                'edit_at', 'updated_at', 'update_at', 'modified_at', 'modified', 'tgl_input', 'created_at', 'created_date', 'create_date',
            );

            $best_ts = null;
            $best_raw = '';
            foreach ($timestamp_keys as $k) {
                $v = $getVal($field, $k, '');
                if (!$isFilled($v)) {
                    continue;
                }

                try {
                    $dt = new DateTime((string) $v);
                    $ts = (int) $dt->format('U');
                } catch (Exception $e) {
                    continue;
                }

                if ($best_ts === null || $ts > $best_ts) {
                    $best_ts = $ts;
                    $best_raw = (string) $v;
                }
            }

            $last_update = ($best_ts === null) ? '-' : $formatIdDatetime($best_raw);

            $row[] = $periode_display;
            $row[] = $tanggal;
            $row[] = $konsumen;
            $row[] = $project;
            $row[] = $route;
            $row[] = $nopol;
            $row[] = $driver;
            $row[] = $est_tgl_tujuan;
            $row[] = $tgl_muat_monitoring;
            $row[] = $lokasi_bongkar;
            $row[] = $uang_jalan;
            $row[] = $no_invoice;
            $row[] = $status_pembayaran;
            $row[] = $progress_html;
            $row[] = $status_html;
            $row[] = $last_update;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_sml_kertas_kerja->count_all(),
            "recordsFiltered" => $this->M_sml_kertas_kerja->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    public function transaksi_list()
    {
        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        ($akses->view_level == 'N' ? redirect('auth') : '');

        $this->enforce_any_role();

        $roles_for_user = $this->roles_for_user();
        $data['roles_for_user'] = $roles_for_user;
        $data['viewer_role'] = !empty($roles_for_user) ? $roles_for_user[0] : '';
        $data['is_authorized'] = !empty($roles_for_user);

        $data['add'] = $akses->add_level;
        $data['title'] = 'backend/sml_kertas_kerja/sml_kertas_kerja_transaksi_list';
        $data['titleview'] = 'Transaksi Kertas Kerja';
        $this->load->view('backend/home', $data);
    }

    public function transaksi_get_list()
    {
        $this->enforce_any_role();
        $roles_for_user = $this->roles_for_user();
        if (empty($roles_for_user)) {
            $output = array(
                'draw' => isset($_POST['draw']) ? $_POST['draw'] : 0,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => array(),
            );
            echo json_encode($output);
            return;
        }

        $list = $this->M_sml_kertas_kerja_transaksi->get_datatables();
        $data = array();
        $no = isset($_POST['start']) ? $_POST['start'] : 0;

        $getVal = function ($obj, $key, $default = '') {
            return (is_object($obj) && isset($obj->{$key}) && $obj->{$key} !== null) ? $obj->{$key} : $default;
        };

        $isFilled = function ($v) {
            if ($v === null) {
                return false;
            }
            $s = trim((string) $v);
            if ($s === '' || $s === '0') {
                return false;
            }
            if ($s === '0000-00-00' || $s === '0000-00-00 00:00:00') {
                return false;
            }
            return true;
        };

        $formatIdDatetime = function ($v) use ($isFilled) {
            if (!$isFilled($v) || $v === '-') {
                return '-';
            }

            try {
                $dt = new DateTime((string) $v);
            } catch (Exception $e) {
                return (string) $v;
            }

            $months = array(
                1 => 'Januari',
                2 => 'Februari',
                3 => 'Maret',
                4 => 'April',
                5 => 'Mei',
                6 => 'Juni',
                7 => 'Juli',
                8 => 'Agustus',
                9 => 'September',
                10 => 'Oktober',
                11 => 'November',
                12 => 'Desember',
            );

            $day = (int) $dt->format('d');
            $monthNum = (int) $dt->format('n');
            $year = $dt->format('Y');
            $time = $dt->format('H:i:s');
            $monthName = isset($months[$monthNum]) ? $months[$monthNum] : $dt->format('m');
            return $day . ' ' . $monthName . ' ' . $year . ' ' . $time;
        };

        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $no;

            $action = '<a href="' . base_url('sml_kertas_kerja/transaksi_form/' . $field->id) . '" class="btn btn-info btn-circle btn-sm" title="Detail Transaksi"><i class="fa fa-eye"></i></a>';
            $row[] = $action;

            $periode = $getVal($field, 'periode', '-');
            $origin = $getVal($field, 'origin', '');
            $destinasi = $getVal($field, 'destinasi', '');
            $route = trim($origin . (empty($origin) || empty($destinasi) ? '' : ' - ') . $destinasi);
            if ($route === '') {
                $route = '-';
            }

            $m_upd = $formatIdDatetime($getVal($field, 'updated_at_marketing', '-'));
            $p_upd = $formatIdDatetime($getVal($field, 'updated_at_plotting', '-'));
            $mo_upd = $formatIdDatetime($getVal($field, 'updated_at_monitoring', '-'));
            $f_upd = $formatIdDatetime($getVal($field, 'updated_at_finance', '-'));

            // last_update (ambil terbaru)
            $timestamp_keys = array(
                'updated_at_finance', 'updated_at_monitoring', 'updated_at_plotting', 'updated_at_marketing',
                'created_at_finance', 'created_at_monitoring', 'created_at_plotting', 'created_at_marketing',
                'edit_at', 'updated_at', 'update_at', 'modified_at', 'modified', 'tgl_input', 'created_at', 'created_date', 'create_date',
            );
            $best_ts = null;
            $best_raw = '';
            foreach ($timestamp_keys as $k) {
                $v = $getVal($field, $k, '');
                if (!$isFilled($v)) {
                    continue;
                }
                try {
                    $dt = new DateTime((string) $v);
                    $ts = (int) $dt->format('U');
                } catch (Exception $e) {
                    continue;
                }
                if ($best_ts === null || $ts > $best_ts) {
                    $best_ts = $ts;
                    $best_raw = (string) $v;
                }
            }
            $last_update = ($best_ts === null) ? '-' : $formatIdDatetime($best_raw);

            $row[] = $periode;
            $row[] = $route;
            $row[] = $m_upd;
            $row[] = $p_upd;
            $row[] = $mo_upd;
            $row[] = $f_upd;
            $row[] = $last_update;

            $data[] = $row;
        }

        $output = array(
            'draw' => isset($_POST['draw']) ? $_POST['draw'] : 0,
            'recordsTotal' => $this->M_sml_kertas_kerja_transaksi->count_all(),
            'recordsFiltered' => $this->M_sml_kertas_kerja_transaksi->count_filtered(),
            'data' => $data,
        );
        echo json_encode($output);
    }

    public function transaksi_form($id)
    {
        $this->enforce_any_role();
        $row = $this->M_sml_kertas_kerja->get_by_id($id);
        if (!$row) {
            redirect('sml_kertas_kerja');
            return;
        }

        $data['row'] = $row;
        $data['id'] = $id;
        $data['title'] = 'backend/sml_kertas_kerja/sml_kertas_kerja_transaksi_form';
        $data['titleview'] = 'Detail Transaksi Kertas Kerja';
        $this->load->view('backend/home', $data);
    }

    function read_form($id)
    {
        $row = $this->M_sml_kertas_kerja->get_by_id($id);
        $this->enforce_any_role();

        $data['data'] = $row;
        $data['aksi'] = 'read';
        $data['id'] = $id;
        $data['konsumen_options'] = $this->konsumen_master_options();
        $data['destinasi_options'] = $this->destinasi_master_options();
        $data = array_merge($data, $this->build_role_context($id));

        // READ MODE: jika user punya banyak role, tampilkan data secara kumulatif sesuai role tertinggi.
        // Contoh: user punya marketing+plotting+monitoring+finance => default finance (lihat semua).
        // Tetap izinkan override lewat query ?mode= jika valid.
        $mode = strtolower(trim((string) $this->input->get('mode', true)));
        $roles_for_user = isset($data['roles_for_user']) ? (array) $data['roles_for_user'] : array();
        if ($mode !== '' && in_array($mode, $roles_for_user, true)) {
            $data['active_role'] = $mode;
        } else {
            $best = $this->pick_highest_role($roles_for_user);
            if ($best !== '') {
                $data['active_role'] = $best;
            }
        }
        $data['is_marketing'] = (isset($data['active_role']) && $data['active_role'] === 'marketing');
        $data['title_view'] = "Kertas Kerja";
        $data['title'] = 'backend/sml_kertas_kerja/sml_kertas_kerja_form';
        $this->load->view('backend/home', $data);
    }

    function add_form()
    {
        // Skema: hanya Marketing yang boleh create (add_form).
        if (!$this->has_role('marketing')) {
            redirect('sml_kertas_kerja');
        }
        $data['id'] = 0;
        $data['konsumen_options'] = $this->konsumen_master_options();
        $data['destinasi_options'] = $this->destinasi_master_options();
        $data = array_merge($data, $this->build_role_context(0));
        $data['title_view'] = "Kertas Kerja Form";
        $data['title'] = 'backend/sml_kertas_kerja/sml_kertas_kerja_form';
        $this->load->view('backend/home', $data);
    }

    function edit_form($id)
    {
        $row = $this->M_sml_kertas_kerja->get_by_id($id);
        $this->enforce_any_role();

        $data['data'] = $row;
        $data['id'] = $id;
        $data['konsumen_options'] = $this->konsumen_master_options();
        $data['destinasi_options'] = $this->destinasi_master_options();
        $data = array_merge($data, $this->build_role_context($id));
        $data['title_view'] = "Edit Kertas Kerja";
        $data['title'] = 'backend/sml_kertas_kerja/sml_kertas_kerja_form';  
        $this->load->view('backend/home', $data);
    }

    function edit_data($id)
    {
        $row = $this->M_sml_kertas_kerja->get_by_id($id);
        $this->enforce_any_role();

        $roles_for_user = $this->roles_for_user();
        $mode = strtolower(trim((string) $this->input->get('mode', true)));
        if ($mode === '' || !in_array($mode, $roles_for_user, true)) {
            $mode = !empty($roles_for_user) ? $roles_for_user[0] : '';
        }

        // Redaksi data: hanya kirim field sesuai role (kumulatif)
        $allowed = $this->allowed_view_fields_for_role($mode);
        $data = array();
        if (is_object($row)) {
            $row_arr = (array) $row;
            $data = array_intersect_key($row_arr, array_flip($allowed));
        }

        // Pastikan nominal numerik dengan nilai 0 tidak berubah menjadi string kosong saat dipakai isi form.
        // (Catatan: di PHP, empty('0') = true, jadi jangan pakai empty() untuk field numeric.)
        $numeric_fields = array(
            // Marketing
            'selling', 'multidrop_s', 'multidrop_selling', 'tkbm_selling', 'inap_selling', 'additional_cost', 'selling_2',
            'buying_uj', 'multidrop_b', 'multidrop_buying', 'tkbm_buying', 'inap_buying', 'additional_cost2', 'buying', 'estimasi_margin',
            // Plotting
            'km_berangkat', 'buying_actual',
            // Monitoring
            'uang_jalan', 'uang_balikan',
            // Finance
            'nominal', 'pph2', 'ppn', 'total_tagihan', 'nominal_pembayaran', 'selisih_pembayaran',
            'actual_margin', 'actual_margin_persen', 'roi',
            // Qty (ikut dinormalisasi agar konsisten)
            'qty_selling', 'qty_buying',
        );
        foreach ($numeric_fields as $f) {
            if (array_key_exists($f, $data) && ($data[$f] === null || $data[$f] === '')) {
                $data[$f] = 0;
            }
        }

        // Map kolom DB yang namanya mengandung '%' agar mudah dipakai di form.
        // DB: `ppn%` -> Form input: `ppn_persen`
        if (array_key_exists('ppn%', $data) && !array_key_exists('ppn_persen', $data)) {
            $data['ppn_persen'] = $data['ppn%'];
        }

        echo json_encode($data);
    }

    public function add()
    {
        if (!$this->has_role('marketing')) {
            echo json_encode(array("status" => FALSE, "message" => "Akses ditolak: hanya Marketing yang boleh membuat data baru."));
            return;
        }

        $post = (array) $this->input->post(NULL, TRUE);
        unset($post['id'], $post['aksi']);

        $fields = (array) $this->db->list_fields('sml_kertas_kerja');
        $allowed = array_flip($fields);
        $data = array_intersect_key($post, $allowed);

        // Marketing create: hanya simpan field marketing
        $marketing_allowed = array_flip($this->allowed_fields_for_role('marketing'));
        $data = array_intersect_key($data, $marketing_allowed);

        // Derived fields (server-side)
        $this->apply_marketing_calculations($data, $post, $allowed);

        // Validasi pelaksana
        if (isset($allowed['pelaksana']) && isset($marketing_allowed['pelaksana'])) {
            $pelaksana = isset($post['pelaksana']) ? trim((string) $post['pelaksana']) : '';
            $valid_pelaksana = array('Vendor', 'Asset');
            if ($pelaksana !== '' && !in_array($pelaksana, $valid_pelaksana, true)) {
                echo json_encode(array("status" => FALSE, "message" => "Nilai Pelaksana tidak valid."));
                return;
            }
            $data['pelaksana'] = ($pelaksana === '') ? null : $pelaksana;
        }

        // Validasi & default status (Marketing)
        if (isset($allowed['status']) && isset($marketing_allowed['status'])) {
            $status = isset($post['status']) ? trim((string) $post['status']) : '';
            $valid_status = array('On Going', 'Done');
            if ($status === '') {
                $status = 'On Going';
            }
            if (!in_array($status, $valid_status, true)) {
                echo json_encode(array("status" => FALSE, "message" => "Nilai Status tidak valid."));
                return;
            }
            $data['status'] = $status;
        }

        // Normalisasi field numeric: kosong/null dianggap 0.
        $numeric_fields = array(
            'selling', 'multidrop_s', 'multidrop_selling', 'tkbm_selling', 'inap_selling', 'additional_cost', 'selling_2',
            'buying_uj', 'multidrop_b', 'multidrop_buying', 'tkbm_buying', 'inap_buying', 'additional_cost2', 'buying', 'estimasi_margin',
            'km_berangkat', 'buying_actual',
            'uang_jalan', 'uang_balikan',
            'nominal', 'pph2', 'ppn', 'total_tagihan', 'nominal_pembayaran', 'selisih_pembayaran',
            'qty_selling', 'qty_buying',
        );
        $this->coerce_numeric_fields($data, $allowed, $numeric_fields);

        // Periode otomatis: YYYYMM (contoh 202601). Ambil dari tanggal jika ada, kalau tidak pakai bulan berjalan.
        if (isset($allowed['periode'])) {
            $periode = '';
            $tanggal_post = isset($post['tanggal']) ? trim((string) $post['tanggal']) : '';
            if ($tanggal_post !== '' && preg_match('/^(\d{4})-(\d{2})/', $tanggal_post, $m)) {
                $periode = $m[1] . $m[2];
            } elseif (isset($post['periode'])) {
                $p = trim((string) $post['periode']);
                if (preg_match('/^(\d{4})-(\d{2})/', $p, $m)) {
                    $periode = $m[1] . $m[2];
                } elseif (preg_match('/^(\d{6})$/', $p, $m)) {
                    $periode = $m[1];
                }
            }
            if ($periode === '') {
                $periode = date('Ym');
            }
            $data['periode'] = $periode;
        }

        if (isset($allowed['input_role'])) {
            $data['input_role'] = 'marketing';
        }

        if (isset($allowed['user'])) {
            $data['user'] = $this->session->userdata('fullname');
        }
        $now = $this->now_datetime();

        // Timestamp global (tetap dipertahankan untuk kompatibilitas)
        if (isset($allowed['created_at'])) {
            $data['created_at'] = $now;
        }
        if (isset($allowed['updated_at'])) {
            $data['updated_at'] = $now;
        }

        // Timestamp per section (marketing create)
        if (isset($allowed['created_at_marketing'])) {
            $data['created_at_marketing'] = $now;
        }
        if (isset($allowed['updated_at_marketing'])) {
            $data['updated_at_marketing'] = $now;
        }

        $new_id = $this->M_sml_kertas_kerja->save($data);
        echo json_encode(array("status" => TRUE, "id" => $new_id));
    }

    public function update()
    {
        $id = $this->input->post('id');

        // Update berdasarkan role aktif di form (input_role / mode), bukan stage record.
        // Tujuan: user Monitoring bisa mengisi Monitoring meskipun tahap sebelumnya belum lengkap.
        $row = $this->M_sml_kertas_kerja->get_by_id($id);
        if (!$row) {
            echo json_encode(array("status" => FALSE, "message" => "Data tidak ditemukan."));
            return;
        }

        $roles_for_user = $this->roles_for_user();
        $requested_role = strtolower(trim((string) $this->input->post('input_role', TRUE)));
        if ($requested_role === '' || !in_array($requested_role, $roles_for_user, true)) {
            // fallback: pakai mode query jika ada (kalau submit membawa mode), atau role pertama user.
            $mode = strtolower(trim((string) $this->input->get('mode', true)));
            if ($mode !== '' && in_array($mode, $roles_for_user, true)) {
                $requested_role = $mode;
            } else {
                $requested_role = !empty($roles_for_user) ? $roles_for_user[0] : '';
            }
        }

        if ($requested_role === '' || !$this->has_role($requested_role)) {
            echo json_encode(array("status" => FALSE, "message" => "Akses ditolak: role Anda tidak sesuai."));
            return;
        }

        $post = (array) $this->input->post(NULL, TRUE);
        unset($post['id'], $post['aksi']);

        $fields = (array) $this->db->list_fields('sml_kertas_kerja');
        $allowed = array_flip($fields);
        $data = array_intersect_key($post, $allowed);

        // Finance hanya boleh input jika status Marketing = Done
        if ($requested_role === 'finance' && isset($allowed['status'])) {
            $current_status = (is_object($row) && isset($row->status)) ? strtolower(trim((string) $row->status)) : '';
            if ($current_status !== 'done') {
                echo json_encode(array("status" => FALSE, "message" => "Finance belum bisa menginput: Status Marketing masih On Going."));
                return;
            }
        }

        // Field-level permission: hanya boleh update kolom milik role yang sedang aktif
        $role_allowed = array_flip($this->allowed_fields_for_role($requested_role));
        $data = array_intersect_key($data, $role_allowed);

        // Derived fields khusus marketing (server-side)
        if ($requested_role === 'marketing') {
            $this->apply_marketing_calculations($data, $post, $allowed);

            // Validasi & normalisasi pelaksana
            if (isset($allowed['pelaksana']) && isset($role_allowed['pelaksana'])) {
                $pelaksana = isset($post['pelaksana']) ? trim((string) $post['pelaksana']) : '';
                $valid_pelaksana = array('Vendor', 'Asset');
                if ($pelaksana !== '' && !in_array($pelaksana, $valid_pelaksana, true)) {
                    echo json_encode(array("status" => FALSE, "message" => "Nilai Pelaksana tidak valid."));
                    return;
                }
                $data['pelaksana'] = ($pelaksana === '') ? null : $pelaksana;
            }

            // Validasi & normalisasi status
            if (isset($allowed['status']) && isset($role_allowed['status'])) {
                $status = isset($post['status']) ? trim((string) $post['status']) : '';
                $valid_status = array('On Going', 'Done');
                if ($status === '') {
                    $status = 'On Going';
                }
                if (!in_array($status, $valid_status, true)) {
                    echo json_encode(array("status" => FALSE, "message" => "Nilai Status tidak valid."));
                    return;
                }
                $data['status'] = $status;
            }
        }

        // Derived fields khusus finance (server-side)
        if ($requested_role === 'finance') {
            // sumber: data marketing & plotting yang sudah tersimpan pada row
            $konsumen = (is_object($row) && isset($row->konsumen)) ? (string) $row->konsumen : '';
            $project = (is_object($row) && isset($row->project)) ? (string) $row->project : '';
            $selling2 = (is_object($row) && isset($row->selling_2)) ? $this->parse_number($row->selling_2) : 0.0;

            $multidrop_b = (is_object($row) && isset($row->multidrop_b)) ? $this->parse_number($row->multidrop_b) : 0.0;
            $qty_buying = (is_object($row) && isset($row->qty_buying)) ? $this->parse_number($row->qty_buying) : 0.0;
            $multidrop_buying = (is_object($row) && isset($row->multidrop_buying)) ? $this->parse_number($row->multidrop_buying) : ($multidrop_b * $qty_buying);
            $tkbm_buying = (is_object($row) && isset($row->tkbm_buying)) ? $this->parse_number($row->tkbm_buying) : 0.0;
            $inap_buying = (is_object($row) && isset($row->inap_buying)) ? $this->parse_number($row->inap_buying) : 0.0;
            $additional_cost2 = (is_object($row) && isset($row->additional_cost2)) ? $this->parse_number($row->additional_cost2) : 0.0;
            $buying_actual = (is_object($row) && isset($row->buying_actual)) ? $this->parse_number($row->buying_actual) : 0.0;

            // nominal mengikuti selling_2; PPH mengikuti nominal
            $nominal = $selling2;

            if (isset($allowed['customer']) && isset($role_allowed['customer'])) {
                $data['customer'] = $konsumen;
            }
            if (isset($allowed['project_finance']) && isset($role_allowed['project_finance'])) {
                $data['project_finance'] = $project;
            }
            if (isset($allowed['nominal']) && isset($role_allowed['nominal'])) {
                $data['nominal'] = $nominal;
            }
            if (isset($allowed['pph2']) && isset($role_allowed['pph2'])) {
                $data['pph2'] = $nominal * 0.02;
            }
            // Simpan persen yang dipakai ke kolom DB `ppn%`
            $ppn_persen = isset($post['ppn_persen']) ? trim((string) $post['ppn_persen']) : '';
            if ($ppn_persen === '') {
                $ppn_persen = '11%';
            }
            if (isset($allowed['ppn%']) && isset($role_allowed['ppn%'])) {
                $data['ppn%'] = $ppn_persen;
            }

            // Hitung nominal PPN berdasarkan persen
            if (isset($allowed['ppn']) && isset($role_allowed['ppn'])) {
                $ppn_rate = ($ppn_persen === '1,1%') ? 0.011 : 0.11;
                $data['ppn'] = $nominal * $ppn_rate;
            }

            if (isset($allowed['total_tagihan']) && isset($role_allowed['total_tagihan'])) {
                $pph2 = $nominal * 0.02;
                $ppn = isset($data['ppn']) ? $this->parse_number($data['ppn']) : ($nominal * 0.11);
                $data['total_tagihan'] = $nominal + $pph2 + $ppn;
            }

            // Actual margin / % / ROI (server-side)
            $actual_margin = $selling2 - $multidrop_buying - $tkbm_buying - $inap_buying - $additional_cost2 - $buying_actual;
            $actual_margin_persen = ($selling2 != 0.0) ? ($actual_margin / $selling2) : 0.0;
            $denom_roi = ($multidrop_buying + $tkbm_buying + $inap_buying + $additional_cost2 + $buying_actual);
            $roi = ($denom_roi != 0.0) ? ($actual_margin / $denom_roi) : 0.0;

            if (isset($allowed['actual_margin']) && isset($role_allowed['actual_margin'])) {
                $data['actual_margin'] = $actual_margin;
            }
            if (isset($allowed['actual_margin_persen']) && isset($role_allowed['actual_margin_persen'])) {
                $data['actual_margin_persen'] = $actual_margin_persen;
            }
            if (isset($allowed['roi']) && isset($role_allowed['roi'])) {
                $data['roi'] = $roi;
            }
        }

        // Normalisasi field numeric: kosong/null dianggap 0.
        $numeric_fields = array(
            'selling', 'multidrop_s', 'multidrop_selling', 'tkbm_selling', 'inap_selling', 'additional_cost', 'selling_2',
            'buying_uj', 'multidrop_b', 'multidrop_buying', 'tkbm_buying', 'inap_buying', 'additional_cost2', 'buying', 'estimasi_margin',
            'km_berangkat', 'buying_actual',
            'uang_jalan', 'uang_balikan',
            'nominal', 'pph2', 'ppn', 'total_tagihan', 'nominal_pembayaran', 'selisih_pembayaran',
            'actual_margin', 'actual_margin_persen', 'roi',
            'qty_selling', 'qty_buying',
        );
        $this->coerce_numeric_fields($data, $allowed, $numeric_fields);

        // Monitoring: lokasi_bongkar mengikuti destinasi jika user tidak mengisi.
        if ($requested_role === 'monitoring' && isset($allowed['lokasi_bongkar']) && isset($role_allowed['lokasi_bongkar'])) {
            $lokasi = array_key_exists('lokasi_bongkar', $data) ? trim((string) $data['lokasi_bongkar']) : '';
            if ($lokasi === '') {
                $dest = (is_object($row) && isset($row->destinasi)) ? trim((string) $row->destinasi) : '';
                if ($dest === '' && is_object($row) && isset($row->destination)) {
                    $dest = trim((string) $row->destination);
                }
                if ($dest !== '') {
                    $data['lokasi_bongkar'] = $dest;
                }
            }
        }

        // Monitoring: aktual_jam = selisih (tgl_muat+waktu_muat) sampai (tgl_bongkar+waktu_bongkar).
        // 1 hari = 24:00, 2 hari = 48:00. Hitung server-side agar konsisten.
        if ($requested_role === 'monitoring' && isset($allowed['waktu_muat']) && isset($allowed['waktu_bongkar']) && isset($allowed['aktual_jam'])) {
            $tgl_muat = trim((string) (isset($post['tgl_muat_monitoring']) ? $post['tgl_muat_monitoring'] : (isset($post['tgl_muat']) ? $post['tgl_muat'] : '')));
            $tgl_bongkar = trim((string) (isset($post['tgl_bongkar']) ? $post['tgl_bongkar'] : ''));

            $start_min = $this->parse_time_to_minutes(isset($post['waktu_muat']) ? $post['waktu_muat'] : null);
            $end_min = $this->parse_time_to_minutes(isset($post['waktu_bongkar']) ? $post['waktu_bongkar'] : null);

            $can_calc = ($tgl_muat !== '' && $tgl_bongkar !== '' && $start_min !== null && $end_min !== null);
            if ($can_calc) {
                try {
                    $start_dt = new DateTime($tgl_muat . ' 00:00:00');
                    $end_dt = new DateTime($tgl_bongkar . ' 00:00:00');
                } catch (Exception $e) {
                    $can_calc = false;
                }
            }

            if ($can_calc) {
                // Selisih hari dalam menit + selisih menit dalam hari
                $diff_days = (int) $start_dt->diff($end_dt)->format('%r%a');

                $diff = ($diff_days * 24 * 60) + ($end_min - $start_min);

                // Jika tanggal sama tapi waktu bongkar < waktu muat, anggap lewat tengah malam (+1 hari)
                if ($diff < 0 && $diff_days === 0) {
                    $diff += (24 * 60);
                }

                if ($diff >= 0) {
                    $data['aktual_jam'] = $this->format_minutes_to_hhmm($diff);
                } else {
                    // invalid (tgl_bongkar sebelum tgl_muat), jangan overwrite.
                    if (array_key_exists('aktual_jam', $data)) {
                        unset($data['aktual_jam']);
                    }
                }
            } else {
                // Jika tidak bisa dihitung, jangan overwrite aktual_jam lama jadi kosong.
                if (array_key_exists('aktual_jam', $data)) {
                    unset($data['aktual_jam']);
                }
            }
        }

        // Normalisasi periode untuk marketing (format YYYYMM)
        if ($requested_role === 'marketing' && isset($allowed['periode']) && isset($data['periode'])) {
            $p = trim((string) $data['periode']);
            if (preg_match('/^(\d{4})-(\d{2})/', $p, $m)) {
                $data['periode'] = $m[1] . $m[2];
            } elseif (preg_match('/^(\d{6})$/', $p, $m)) {
                $data['periode'] = $m[1];
            } else {
                // fallback: jika tanggal ada
                $tanggal_post = isset($post['tanggal']) ? trim((string) $post['tanggal']) : '';
                if ($tanggal_post !== '' && preg_match('/^(\d{4})-(\d{2})/', $tanggal_post, $m)) {
                    $data['periode'] = $m[1] . $m[2];
                }
            }
        }

        if (isset($allowed['input_role'])) {
            $data['input_role'] = $requested_role;
        }

        $now = $this->now_datetime();

        if (isset($allowed['user'])) {
            $data['user'] = $this->session->userdata('fullname');
        }

        // Timestamp global
        if (isset($allowed['edit_at'])) {
            $data['edit_at'] = $now;
        }
        if (isset($allowed['updated_at'])) {
            $data['updated_at'] = $now;
        }

        // Timestamp per section: set created_at_{role} hanya saat pertama kali, updated_at_{role} setiap update.
        $created_field = 'created_at_' . $requested_role;
        $updated_field = 'updated_at_' . $requested_role;

        if (isset($allowed[$updated_field])) {
            $data[$updated_field] = $now;
        }

        if (isset($allowed[$created_field])) {
            $existing_created = (is_object($row) && isset($row->{$created_field})) ? $row->{$created_field} : null;
            if (!$this->is_filled_datetime($existing_created)) {
                $data[$created_field] = $now;
            }
        }

        $this->db->where('id', $id);
        $this->db->update('sml_kertas_kerja', $data);
        echo json_encode(array("status" => TRUE));
    }

    function delete($id)
    {
        $this->M_sml_kertas_kerja->delete($id);
        echo json_encode(array("status" => TRUE));
    }

    public function export_excel()
    {
        // only users with any role are allowed to export
        $roles_for_user = $this->roles_for_user();
        if (empty($roles_for_user)) {
            // return 403 forbidden
            show_error('Access denied', 403);
            return;
        }

        // ---------- dynamic full-field export (new requirement) ----------
        // gather rows using existing filter logic in model
        $status_filter = $this->input->get('status_filter'); // preserved for model use
        $list = $this->M_sml_kertas_kerja->get_all_for_export();

        // build header list from table fields but only those used in the read form
        $fields = $this->db->list_fields('sml_kertas_kerja');
        $formNames = $this->form_field_names();
        $exclude = array('created_at', 'updated_at', 'ppn');
        $headers = array();
        foreach ($fields as $f) {
            if (in_array($f, $exclude, true)) {
                continue;
            }
            if (strpos($f, 'created_at_') === 0 || strpos($f, 'updated_at_') === 0) {
                continue;
            }
            if (!in_array($f, $formNames, true)) {
                continue; // skip fields that are not present in read_form view
            }
            $headers[] = $f;
        }
        // insert pph2% and pph11% right after nominal column
        $nominalPos = array_search('nominal', $headers, true);
        if ($nominalPos !== false) {
            // insert right after nominal (at position nominal+1)
            array_splice($headers, $nominalPos + 1, 0, array('pph2%', 'pph11%'));
        } else {
            // if nominal not found, just append at the end
            $headers[] = 'pph2%';
            $headers[] = 'pph11%';
        }

        // ensure calculated finance fields show at the very end of export
        // (they may exist earlier in DB order).  Append them if present and also
        // add them if they weren't included at all yet.
        $financeExtras = array('actual_margin', 'actual_margin_persen', 'roi');
        foreach ($financeExtras as $extraField) {
            $pos = array_search($extraField, $headers, true);
            if ($pos !== false) {
                unset($headers[$pos]);
                $headers[] = $extraField;
            } else {
                // not found in headers (maybe not a DB column or not in form),
                // but user requested it so we still include an empty column.
                $headers[] = $extraField;
            }
        }
        // reindex headers after possible removals/appends
        $headers = array_values($headers);

        // create spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // header row
        foreach ($headers as $i => $h) {
            $cell = Coordinate::stringFromColumnIndex($i + 1) . '1';
            $sheet->setCellValue($cell, $h);
            $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($i + 1))->setAutoSize(true);
        }
        $sheet->setAutoFilter('A1:' . Coordinate::stringFromColumnIndex(count($headers)) . '1');

        // data rows
        $rowNum = 2;
        foreach ($list as $row) {
            foreach ($headers as $i => $h) {
                $col = Coordinate::stringFromColumnIndex($i + 1);
                $value = '';
                
                if ($h === 'pph2%') {
                    // Calculate PPH 2% from nominal
                    $nominal = isset($row->nominal) ? $this->parse_number($row->nominal) : 0;
                    $value = $nominal * 0.02;
                } elseif ($h === 'pph11%') {
                    // Calculate PPN 11% from nominal
                    $nominal = isset($row->nominal) ? $this->parse_number($row->nominal) : 0;
                    $value = $nominal * 0.11;
                } else {
                    $value = isset($row->{$h}) ? $row->{$h} : '';
                }
                
                $sheet->setCellValue($col . $rowNum, $value);
            }
            $rowNum++;
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Kertas Kerja.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;   


        // status filter may be provided via query string or POST; the model's helper
        // already checks both $_POST and $_GET so we don't have to do anything else.
        // we simply make sure the value is available in $_GET for GET-based export
        $status_filter = $this->input->get('status_filter');
        if ($status_filter !== null) {
            // leave in GET, model will read it
        }

        // fetch all matching rows (without paging)
        $list = $this->M_sml_kertas_kerja->get_all_for_export();

        // helpers reused from get_list
        $getVal = function ($obj, $key, $default = '') {
            return (is_object($obj) && isset($obj->{$key}) && $obj->{$key} !== null) ? $obj->{$key} : $default;
        };
        $isFilled = function ($v) {
            if ($v === null) {
                return false;
            }
            $s = trim((string) $v);
            if ($s === '' || $s === '0') {
                return false;
            }
            if ($s === '0000-00-00' || $s === '0000-00-00 00:00:00') {
                return false;
            }
            return true;
        };
        $formatIdDate = function ($v) use ($isFilled) {
            if (!$isFilled($v) || $v === '-') {
                return '-';
            }
            try {
                $dt = new DateTime((string) $v);
            } catch (Exception $e) {
                return (string) $v;
            }
            $months = array(
                1 => 'Januari',
                2 => 'Februari',
                3 => 'Maret',
                4 => 'April',
                5 => 'Mei',
                6 => 'Juni',
                7 => 'Juli',
                8 => 'Agustus',
                9 => 'September',
                10 => 'Oktober',
                11 => 'November',
                12 => 'Desember',
            );
            $day = (int) $dt->format('d');
            $monthNum = (int) $dt->format('n');
            $year = $dt->format('Y');
            $monthName = isset($months[$monthNum]) ? $months[$monthNum] : $dt->format('m');
            return $day . ' ' . $monthName . ' ' . $year;
        };
        $formatIdDatetime = function ($v) use ($isFilled) {
            if (!$isFilled($v) || $v === '-') {
                return '-';
            }
            try {
                $dt = new DateTime((string) $v);
            } catch (Exception $e) {
                return (string) $v;
            }
            $months = array(
                1 => 'Januari',
                2 => 'Februari',
                3 => 'Maret',
                4 => 'April',
                5 => 'Mei',
                6 => 'Juni',
                7 => 'Juli',
                8 => 'Agustus',
                9 => 'September',
                10 => 'Oktober',
                11 => 'November',
                12 => 'Desember',
            );
            $day = (int) $dt->format('d');
            $monthNum = (int) $dt->format('n');
            $year = $dt->format('Y');
            $time = $dt->format('H:i:s');
            $monthName = isset($months[$monthNum]) ? $months[$monthNum] : $dt->format('m');
            return $day . ' ' . $monthName . ' ' . $year . ' ' . $time;
        };

        // create spreadsheet and column headers
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = array(
            'No',
            'Periode',
            'Tanggal',
            'Konsumen/Customer',
            'Project',
            'Route',
            'Nopol',
            'Driver',
            'Est Tanggal Tujuan',
            'Tanggal Muat',
            'Tgl Bongkar',
            'Lokasi Bongkar',
            'Uang Jalan',
            'No Invoice',
            'Tgl Invoice',
            'Status Bayar',
            'Progress',
            'Status',
            'Last Update',
        );
        foreach ($headers as $i => $h) {
            $cell = chr(65 + $i) . '1';
            $sheet->setCellValue($cell, $h);
            $sheet->getColumnDimension(chr(65 + $i))->setAutoSize(true);
        }
        $sheet->setAutoFilter('A1:' . chr(65 + count($headers) - 1) . '1');

        $rowNum = 2;
        foreach ($list as $field) {
            // marketing fields
            $periode_raw = $getVal($field, 'periode');
            if (!$isFilled($periode_raw)) {
                $tanggal_tmp = $getVal($field, 'tanggal', $getVal($field, 'date'));
                if ($isFilled($tanggal_tmp) && preg_match('/^(\d{4})-(\d{2})/', (string) $tanggal_tmp, $m)) {
                    $periode_raw = $m[1] . $m[2];
                }
            }
            $periode_display = '';
            if ($isFilled($periode_raw)) {
                $p = trim((string) $periode_raw);
                if (preg_match('/^(\d{4})-(\d{2})/', $p, $m)) {
                    $periode_display = $m[1] . $m[2];
                } elseif (preg_match('/^(\d{6})$/', $p, $m)) {
                    $periode_display = $m[1];
                } else {
                    $periode_display = $p;
                }
            } else {
                $periode_display = '-';
            }

            $tanggal_raw = $getVal($field, 'tanggal', $getVal($field, 'date'));
            $tanggal = $isFilled($tanggal_raw) ? $formatIdDate($tanggal_raw) : '-';

            $konsumen_raw = $getVal($field, 'konsumen', $getVal($field, 'customer'));
            $project_raw = $getVal($field, 'project');
            $origin = $getVal($field, 'origin');
            $destinasi = $getVal($field, 'destinasi');
            $route = trim($origin . (empty($origin) || empty($destinasi) ? '' : ' - ') . $destinasi);
            $nopol_raw = $getVal($field, 'nopol');
            $driver_raw = $getVal($field, 'driver');
            $est_tgl_tujuan_raw = $getVal($field, 'est_tgl_tujuan');
            $tgl_muat_monitoring_raw = $getVal($field, 'tgl_muat_monitoring');
            $tgl_bongkar_raw = $getVal($field, 'tgl_bongkar');
            $lokasi_bongkar_raw = $getVal($field, 'lokasi_bongkar');
            $uang_jalan_raw = $getVal($field, 'uang_jalan');
            $no_invoice_raw = $getVal($field, 'no_invoice');
            $tgl_invoice_raw = $getVal($field, 'tgl_invoice');
            $status_pembayaran_raw = $getVal($field, 'status_pembayaran');

            $marketing_done = $isFilled($getVal($field, 'periode')) || ($isFilled($tanggal_raw) && $isFilled($konsumen_raw));
            $plotting_done = $isFilled($nopol_raw);
            $monitoring_done = $isFilled($uang_jalan_raw) || $isFilled($tgl_muat_monitoring_raw) || $isFilled($tgl_bongkar_raw);
            $finance_done = $isFilled($no_invoice_raw) || $isFilled($status_pembayaran_raw) || $isFilled($getVal($field, 'tgl_invoice'));

            $progress_count = 0;
            foreach (array($marketing_done, $plotting_done, $monitoring_done, $finance_done) as $flag) {
                if ($flag) {
                    $progress_count++;
                }
            }
            $progress_text = '';
            $progress_text .= $marketing_done ? 'M ' : '';
            $progress_text .= $plotting_done ? 'P ' : '';
            $progress_text .= $monitoring_done ? 'Mo ' : '';
            $progress_text .= $finance_done ? 'F ' : '';
            $progress_text = trim($progress_text) . ' ' . $progress_count . '/4';

            $is_complete = ($progress_count === 4);
            $status_text = $is_complete ? 'SELESAI' : 'PROSES';

            // compute last update
            $timestamp_keys = array(
                'updated_at_finance', 'updated_at_monitoring', 'updated_at_plotting', 'updated_at_marketing',
                'created_at_finance', 'created_at_monitoring', 'created_at_plotting', 'created_at_marketing',
                'edit_at', 'updated_at', 'update_at', 'modified_at', 'modified', 'tgl_input', 'created_at', 'created_date', 'create_date',
            );
            $best_ts = null;
            $best_raw = '';
            foreach ($timestamp_keys as $k) {
                $v = $getVal($field, $k, '');
                if (!$isFilled($v)) {
                    continue;
                }
                try {
                    $dt = new DateTime((string) $v);
                    $ts = (int) $dt->format('U');
                } catch (Exception $e) {
                    continue;
                }
                if ($best_ts === null || $ts > $best_ts) {
                    $best_ts = $ts;
                    $best_raw = (string) $v;
                }
            }
            $last_update = ($best_ts === null) ? '-' : $formatIdDatetime($best_raw);

            $sheet->setCellValue('A' . $rowNum, $rowNum - 1);
            $sheet->setCellValue('B' . $rowNum, $periode_display);
            $sheet->setCellValue('C' . $rowNum, $tanggal);
            $sheet->setCellValue('D' . $rowNum, $konsumen_raw);
            $sheet->setCellValue('E' . $rowNum, $project_raw);
            $sheet->setCellValue('F' . $rowNum, $route);
            $sheet->setCellValue('G' . $rowNum, $nopol_raw);
            $sheet->setCellValue('H' . $rowNum, $driver_raw);
            $sheet->setCellValue('I' . $rowNum, $isFilled($est_tgl_tujuan_raw) ? $formatIdDate($est_tgl_tujuan_raw) : '-');
            $sheet->setCellValue('J' . $rowNum, $isFilled($tgl_muat_monitoring_raw) ?  $formatIdDate($tgl_muat_monitoring_raw) : '-');
            $sheet->setCellValue('K' . $rowNum, $isFilled($tgl_bongkar_raw) ? $formatIdDate($tgl_bongkar_raw) : '-');
            $sheet->setCellValue('L' . $rowNum, $lokasi_bongkar_raw);
            $uang_jalan_num = $this->parse_number($uang_jalan_raw);
            $sheet->setCellValue('M' . $rowNum, $uang_jalan_num);
            $sheet->setCellValue('N' . $rowNum, $no_invoice_raw);
            $sheet->setCellValue('O' . $rowNum, $isFilled($tgl_invoice_raw) ? $formatIdDate($tgl_invoice_raw) : '-');
            $sheet->setCellValue('P' . $rowNum, $status_pembayaran_raw);
            $sheet->setCellValue('Q' . $rowNum, $progress_text);
            $sheet->setCellValue('R' . $rowNum, $status_text);
            $sheet->setCellValue('S' . $rowNum, $last_update);

            $rowNum++;
        }

        // format uang jalan column (M) after adding new columns
        $sheet->getStyle('M2:M' . ($rowNum - 1))
            ->getNumberFormat()
            ->setFormatCode('#,##0');

        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Kertas Kerja.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
