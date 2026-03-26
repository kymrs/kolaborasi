<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_sml_akses_kertas_kerja extends CI_Model
{
    var $table = 'sml_akses_kertas_kerja';
    var $id = 'id';

    var $column_order = array(null, null, 'username', 'role', 'is_active', 'updated_at');
    var $column_search = array('username', 'role', 'is_active');
    var $order = array('updated_at' => 'desc');

    public function __construct()
    {
        parent::__construct();
    }

    public function table_exists()
    {
        if (!isset($this->db)) {
            return false;
        }

        $table = trim((string) $this->table);
        if ($table === '') {
            return false;
        }

        // Cek tanpa/ dengan prefix (kalau suatu saat dbprefix dipakai).
        $candidates = array($table);
        if (method_exists($this->db, 'dbprefix')) {
            $prefixed = (string) $this->db->dbprefix($table);
            if ($prefixed !== '' && $prefixed !== $table) {
                $candidates[] = $prefixed;
            }
        }
        $candidates = array_values(array_unique($candidates));

        // Gunakan information_schema agar tidak tergantung cache list_tables().
        $dbName = isset($this->db->database) ? (string) $this->db->database : '';
        foreach ($candidates as $t) {
            if ($t === '') {
                continue;
            }

            // Fast path jika available
            if (method_exists($this->db, 'table_exists')) {
                // table_exists() di CI memakai list_tables() yang dicache; tetap kita coba,
                // tapi jangan jadikan satu-satunya sumber kebenaran.
                if ((bool) $this->db->table_exists($t)) {
                    return true;
                }
            }

            if ($dbName !== '') {
                $q = $this->db->query(
                    'SELECT 1 FROM information_schema.tables WHERE table_schema = ? AND table_name = ? LIMIT 1',
                    array($dbName, $t)
                );
                if ($q && $q->num_rows() > 0) {
                    return true;
                }
            }

            // Fallback paling kompatibel
            $q2 = $this->db->query("SHOW TABLES LIKE '" . $this->db->escape_like_str($t) . "'");
            if ($q2 && $q2->num_rows() > 0) {
                return true;
            }
        }

        return false;
    }

    public function roles_for_username($username)
    {
        $username = strtolower(trim((string) $username));
        if ($username === '') {
            return array();
        }

        $rows = $this->db
            ->select('role')
            ->from($this->table)
            ->where('username', $username)
            ->where('is_active', 'Y')
            ->get()
            ->result();

        $roles = array();
        foreach ((array) $rows as $r) {
            if (is_object($r) && isset($r->role)) {
                $role = strtolower(trim((string) $r->role));
                if ($role !== '') {
                    $roles[] = $role;
                }
            }
        }

        $roles = array_values(array_unique($roles));
        return $roles;
    }

    private function _get_datatables_query()
    {
        $this->db->from($this->table);

        $i = 0;
        foreach ($this->column_search as $item) {
            if (isset($_POST['search']['value']) && $_POST['search']['value']) {
                if ($i === 0) {
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
        if (isset($_POST['length']) && $_POST['length'] != -1)
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
        return $this->db->from($this->table)->where($this->id, $id)->get()->row();
    }

    public function save($data)
    {
        $now = date('Y-m-d H:i:s');
        if (!isset($data['created_at'])) $data['created_at'] = $now;
        if (!isset($data['updated_at'])) $data['updated_at'] = $now;
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($where, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->update($this->table, $data, $where);
        return $this->db->affected_rows();
    }

    public function delete_by_id($id)
    {
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
    }
}
