<?php
class M_absensi extends CI_Model {

    public function get_absensi_hari_ini($id_user) {
        $today = date('Y-m-d');
        return $this->db->get_where('tbl_absensi', [
            'id_user' => $id_user,
            'tanggal' => $today
        ])->row_array();
    }

    public function insert_check_in($data) {
        return $this->db->insert('tbl_absensi', $data);
    }

    public function update_check_out($id_user, $data) {
        $today = date('Y-m-d');
        $this->db->where('id_user', $id_user);
        $this->db->where('tanggal', $today);
        return $this->db->update('tbl_absensi', $data);
    }
}