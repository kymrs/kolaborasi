<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_sw_prepayment extends CI_Model
{
    // INISIASI VARIABLE
    var $id = 'id';
    var $table = 'tbl_prepayment';
    var $table2 = 'tbl_prepayment_detail';
    var $column_order = array(null, null, 'payment_status', 'kode_prepayment', 'event_name', 'name', 'divisi', 'jabatan', 'tgl_prepayment', 'total_nominal', 'status');
    var $column_order2 = array(null, null, 'id', 'event_name', 'is_active', 'created_at', 'updated_at');
    var $column_search = array('payment_status', 'kode_prepayment', 'event_name', 'name', 'divisi', 'jabatan', 'tgl_prepayment', 'total_nominal', 'status'); //field yang diizin untuk pencarian
    var $column_search2 = array('id', 'event_name', 'is_active', 'created_at', 'updated_at');
    var $order = array('id' => 'desc');

    // UNTUK QUERY DATA TABLE
    function _get_datatables_query()
    {
        $this->db->select('tbl_prepayment.*, tbl_data_user.name, tbl_event_sw.event_name'); // Memilih kolom dari kedua tabel
        $this->db->from($this->table);
        $this->db->join('tbl_data_user', 'tbl_data_user.id_user = tbl_prepayment.id_user', 'left'); // JOIN dengan tabel tbl_user
        $this->db->join('tbl_event_sw', 'tbl_prepayment.event = tbl_event_sw.id', 'left');

        $i = 0;
        $alias = $this->session->userdata('username');

        foreach ($this->column_search as $item) // looping awal
        {
            if ($_POST['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
            {

                if ($i === 0) // looping awal
                {
                    $this->db->group_start();
                    if ($item == 'name') {
                        $this->db->like('tbl_data_user.' . $item, $_POST['search']['value']);
                    } else {
                        $this->db->like('tbl_prepayment.' . $item, $_POST['search']['value']);
                    }
                } else {
                    if ($item == 'name') {
                        $this->db->or_like('tbl_data_user.' . $item, $_POST['search']['value']);
                    } else {
                        $this->db->or_like('tbl_prepayment.' . $item, $_POST['search']['value']);
                    }
                }

                if (count($this->column_search) - 1 == $i) {
                    $this->db->group_end();
                }
            }
            $i++;
        }

        // Tambahkan pemfilteran berdasarkan status
        // Tambahkan kondisi jika id_user login sesuai dengan app2_name
        $id_user_logged_in = $this->session->userdata('id_user'); // Mengambil id_user dari sesi pengguna yang login

        if (!empty($_POST['status'])) {
            $this->db->group_start(); // Start grouping conditions

            if ($_POST['status'] == 'on-process') {
                // Conditions for 'on-process' status
                if ($alias != "eko") {
                    $this->db->where('tbl_prepayment.id_user =' . $id_user_logged_in . ' AND status = "on-process"')
                        // ->or_where('app4_name = (SELECT name FROM tbl_data_user WHERE id_user = ' . $id_user_logged_in . ' AND app4_status = "waiting" AND status != "rejected" AND status != "revised")', NULL, FALSE)
                        ->or_where('app_name = (SELECT name FROM tbl_data_user WHERE id_user = ' . $id_user_logged_in . ' AND app_status = "waiting" AND status != "rejected" AND status != "revised")', NULL, FALSE)
                        ->or_where('app2_name = (SELECT name FROM tbl_data_user WHERE id_user = ' . $id_user_logged_in . ' AND app_status = "approved" AND app2_status = "waiting" AND status != "rejected" AND status != "revised")', NULL, FALSE);
                } else {
                    $this->db->where('status = "on-process"');
                }
            } elseif ($_POST['status'] == 'approved') {
                // Conditions for 'approved' status
                if ($alias != "approved") {
                    $this->db->where('app2_status', 'approved')
                        // ->or_where('app4_name = (SELECT name FROM tbl_data_user WHERE id_user = ' . $id_user_logged_in . ' AND app4_status = "approved" AND app2_status != "rejected")', NULL, FALSE)
                        ->or_where('app_name = (SELECT name FROM tbl_data_user WHERE id_user = ' . $id_user_logged_in . ' AND app_status = "approved" AND app2_status != "rejected")', NULL, FALSE)
                        ->or_where('app2_name = (SELECT name FROM tbl_data_user WHERE id_user = ' . $id_user_logged_in . ' AND app2_status = "approved" AND app2_status != "rejected")', NULL, FALSE);
                } else {
                    $this->db->where('status = "approved"');
                }
            } elseif ($_POST['status'] == 'revised') {
                if ($alias != "eko") {
                    $this->db->where('app2_name = (SELECT name FROM tbl_data_user WHERE id_user = ' . $id_user_logged_in . ' AND app2_status = "revised")', NULL, FALSE)
                        ->or_where('app_name = (SELECT name FROM tbl_data_user WHERE id_user = ' . $id_user_logged_in . ' AND app_status = "revised")', NULL, FALSE)
                        // ->or_where('app4_name = (SELECT name FROM tbl_data_user WHERE id_user = ' . $id_user_logged_in . ' AND app4_status = "revised")', NULL, FALSE)
                        ->or_where('tbl_prepayment.id_user =' . $id_user_logged_in . ' AND (app_status = "revised" OR app2_status = "revised")');
                } else {
                    $this->db->where('status = "revised"');
                }
            } elseif ($_POST['status'] == 'rejected') {
                $this->db->where('status', $_POST['status']);
            }

            $this->db->group_end(); // End grouping conditions
        }

        // Tambahkan kondisi berdasarkan tab yang dipilih
        if (!empty($_POST['tab'])) {
            if ($_POST['tab'] == 'personal') {
                $this->db->where('tbl_prepayment.id_user', $this->session->userdata('id_user'));
            } elseif ($_POST['tab'] == 'employee') {
                if ($alias != "eko") {
                    $this->db->group_start()
                        ->where('tbl_prepayment.app_name =', "(SELECT name FROM tbl_data_user WHERE id_user = " . $this->session->userdata('id_user') . ")", FALSE)
                        ->where('tbl_prepayment.id_user !=', $this->session->userdata('id_user'))
                        ->or_where('tbl_prepayment.app2_name =', "(SELECT name FROM tbl_data_user WHERE id_user = " . $this->session->userdata('id_user') . ") && tbl_prepayment.app_status = 'approved'", FALSE)
                        ->where('tbl_prepayment.id_user !=', $this->session->userdata('id_user'))
                        // ->or_where('tbl_prepayment.app4_name =', "(SELECT name FROM tbl_data_user WHERE id_user = " . $this->session->userdata('id_user') . ")", FALSE)
                        // ->where('tbl_prepayment.id_user !=', $this->session->userdata('id_user'))
                        ->group_end();
                }
            }
        }

        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    // UNTUK MENAMPILKAN HASIL QUERY KE DATA TABLES
    function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
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
        $this->db->select('tbl_prepayment.*, tbl_data_user.name, tbl_event_sw.event_name'); // Memilih kolom dari kedua tabel
        $this->db->from($this->table);
        $this->db->join('tbl_data_user', 'tbl_data_user.id_user = tbl_prepayment.id_user', 'left'); // JOIN dengan tabel tbl_user
        $this->db->join('tbl_event_sw', 'tbl_prepayment.event = tbl_event_sw.id', 'left');

        // Tambahkan pemfilteran berdasarkan status
        // Tambahkan kondisi jika id_user login sesuai dengan app2_name
        $id_user_logged_in = $this->session->userdata('id_user'); // Mengambil id_user dari sesi pengguna yang login
        $alias = $this->session->userdata('username');

        if (!empty($_POST['status'])) {
            $this->db->group_start(); // Start grouping conditions

            if ($_POST['status'] == 'on-process') {
                // Conditions for 'on-process' status
                if ($alias != "eko") {
                    $this->db->where('tbl_prepayment.id_user =' . $id_user_logged_in . ' AND status = "on-process"')
                        // ->or_where('app4_name = (SELECT name FROM tbl_data_user WHERE id_user = ' . $id_user_logged_in . ' AND app4_status = "waiting" AND status != "rejected" AND status != "revised")', NULL, FALSE)
                        ->or_where('app_name = (SELECT name FROM tbl_data_user WHERE id_user = ' . $id_user_logged_in . ' AND app_status = "waiting" AND status != "rejected" AND status != "revised")', NULL, FALSE)
                        ->or_where('app2_name = (SELECT name FROM tbl_data_user WHERE id_user = ' . $id_user_logged_in . ' AND app_status = "approved" AND app2_status = "waiting" AND status != "rejected" AND status != "revised")', NULL, FALSE);
                } else {
                    $this->db->where('status = "on-process"');
                }
            } elseif ($_POST['status'] == 'approved') {
                // Conditions for 'approved' status
                if ($alias != "approved") {
                    $this->db->where('app2_status', 'approved')
                        ->or_where('app_name = (SELECT name FROM tbl_data_user WHERE id_user = ' . $id_user_logged_in . ' AND app_status = "approved" AND app2_status != "rejected")', NULL, FALSE)
                        ->or_where('app2_name = (SELECT name FROM tbl_data_user WHERE id_user = ' . $id_user_logged_in . ' AND app2_status = "approved" AND app2_status != "rejected")', NULL, FALSE);
                } else {
                    $this->db->where('status = "approved"');
                }
            } elseif ($_POST['status'] == 'revised') {
                if ($alias != "eko") {
                    $this->db->where('app2_name = (SELECT name FROM tbl_data_user WHERE id_user = ' . $id_user_logged_in . ' AND app2_status = "revised")', NULL, FALSE)
                        ->or_where('app_name = (SELECT name FROM tbl_data_user WHERE id_user = ' . $id_user_logged_in . ' AND app_status = "revised")', NULL, FALSE)
                        ->or_where('tbl_prepayment.id_user =' . $id_user_logged_in . ' AND (app_status = "revised" OR app2_status = "revised")');
                } else {
                    $this->db->where('status = "revised"');
                }
            } elseif ($_POST['status'] == 'rejected') {
                $this->db->where('status', $_POST['status']);
            }

            $this->db->group_end(); // End grouping conditions
        }

        // Tambahkan kondisi berdasarkan tab yang dipilih
        if (!empty($_POST['tab'])) {
            if ($_POST['tab'] == 'personal') {
                $this->db->where('tbl_prepayment.id_user', $this->session->userdata('id_user'));
            } elseif ($_POST['tab'] == 'employee') {
                $this->db->group_start()
                    ->where('tbl_prepayment.app_name =', "(SELECT name FROM tbl_data_user WHERE id_user = " . $this->session->userdata('id_user') . ")", FALSE)
                    ->where('tbl_prepayment.id_user !=', $this->session->userdata('id_user'))
                    ->or_where('tbl_prepayment.app2_name =', "(SELECT name FROM tbl_data_user WHERE id_user = " . $this->session->userdata('id_user') . ") && tbl_prepayment.app_status = 'approved'", FALSE)
                    ->where('tbl_prepayment.id_user !=', $this->session->userdata('id_user'))
                    ->where('tbl_prepayment.id_user !=', $this->session->userdata('id_user'))
                    ->group_end();
            }
        }
        return $this->db->count_all_results();
    }

    // GET BY ID TABLE PREPAYMENT MASTER
    public function get_by_id($id)
    {
        $this->db->where($this->id, $id);
        return $this->db->get($this->table)->row();
    }

    // GET BY ID TABLE DETAIL PREPAYMENT TRANSAKSI
    public function get_by_id_detail($id)
    {
        $this->db->where('prepayment_id', $id);
        return $this->db->get($this->table2)->result_array();
    }

    // UNTUK QUERY MENGAMBIL KODE UNTUK DIGENERATE DI CONTROLLER
    public function max_kode($date)
    {
        $formatted_date = date('ym', strtotime($date));
        $this->db->select('kode_prepayment');
        $where = 'id=(SELECT max(id) FROM tbl_prepayment where SUBSTRING(kode_prepayment, 2, 4) = ' . $formatted_date . ')';
        $this->db->where($where);
        $query = $this->db->get('tbl_prepayment');
        return $query;
    }

    // UNTUK QUERY MENENTUKAN SIAPA YANG MELAKUKAN APPROVAL
    public function approval($id)
    {
        $this->db->select('app_id, app2_id');
        $this->db->from('tbl_data_user');
        $this->db->where('id_user', $id);
        $query = $this->db->get();
        return $query->row();
    }

    // UNTUK QUERY INSERT DATA PREPAYMENT
    public function save($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    // UNTUK QUERY INSERT DATA PREPAYMENT_DETAIL
    public function save_detail($data)
    {
        $this->db->insert_batch($this->table2, $data);
        return $this->db->insert_id();
    }

    // UNTUK QUERY DELETE DATA PREPAYMENT
    public function delete($id)
    {
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
    }

    // UNTUK QUERY DELETE DATA PREPAYMENT DETAIL BERBARENGAN DENGAN PREPAYMENT MASTER
    public function delete_detail($id)
    {
        $this->db->where('prepayment_id', $id);
        $this->db->delete($this->table2);
    }

    public function _get_datatables_query_event()
    {
        $this->db->select('id, event_name, is_active, created_at, updated_at')->from('tbl_event_sw');

        $i = 0;

        foreach ($this->column_search2 as $item) { // Pastikan ini adalah column_search2
            if (!empty($_POST['search']['value'])) {
                if ($i === 0) {
                    $this->db->group_start(); // Mulai grup pencarian
                    $this->db->like('tbl_event_sw.' . $item, $_POST['search']['value']);
                } else {
                    $this->db->or_like('tbl_event_sw.' . $item, $_POST['search']['value']);
                }

                if (count($this->column_search2) - 1 == $i) { // Pastikan ini adalah column_search2
                    $this->db->group_end(); // Tutup grup pencarian setelah semua kolom
                }
            }
            $i++;
        }

        // Pastikan bagian order berada di luar grup pencarian
        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order2[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables_event()
    {
        $this->_get_datatables_query_event();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    public function count_all_event()
    {
        $this->db->select('id, event_name, is_active, created_at, updated_at')->from('tbl_event_sw');

        $i = 0;

        foreach ($this->column_search2 as $item) { // Pastikan ini adalah column_search2
            if (!empty($_POST['search']['value'])) {
                if ($i === 0) {
                    $this->db->group_start(); // Mulai grup pencarian
                    $this->db->like('tbl_event_sw.' . $item, $_POST['search']['value']);
                } else {
                    $this->db->or_like('tbl_event_sw.' . $item, $_POST['search']['value']);
                }

                if (count($this->column_search2) - 1 == $i) { // Pastikan ini adalah column_search2
                    $this->db->group_end(); // Tutup grup pencarian setelah semua kolom
                }
            }
            $i++;
        }

        // Pastikan bagian order berada di luar grup pencarian
        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order2[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
        return $this->db->count_all_results();  // Perbaikan nama fungsi
    }

    public function count_filtered_event()
    {
        $this->_get_datatables_query_event();  // Panggil fungsi yang benar
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function get_events()
    {
        $query = $this->db->select('id, event_name')->from('tbl_event_sw')->where('is_active', 1);
        return $query->get()->result();
    }

    public function get_selected_event($id)
    {
        $query = $this->db->select('event')->from('tbl_prepayment')->where('id', $id);
        return $query->get()->row('event');
    }

    // OPSI REKENING
    public function options($id)
    {
        return $this->db->distinct()->select('no_rek')->where('id_user', $id)->from('tbl_prepayment')->get();
    }
}
