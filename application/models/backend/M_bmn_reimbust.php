<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_bmn_reimbust extends CI_Model
{
    // Reimbust
    var $id = 'id';
    var $table = 'bmn_reimbust'; //nama tabel dari database
    var $table2 = 'bmn_reimbust_detail';
    var $column_order = array(null, null, 'payment_status', 'kode_reimbust', 'name', 'jabatan', 'departemen', 'sifat_pelaporan', 'tgl_pengajuan', 'tujuan', 'jumlah_prepayment', 'status');
    var $column_search = array('payment_status', 'kode_reimbust', 'name', 'jabatan', 'departemen', 'sifat_pelaporan', 'tgl_pengajuan', 'tujuan', 'jumlah_prepayment', 'status'); //field yang diizin untuk pencarian 
    var $order = array('id' => 'desc'); // default order 

    // Deklarasi
    var $table3 = 'bmn_deklarasi'; //nama tabel dari database
    var $column_order2 = array(null, null, 'kode_deklarasi', 'tgl_deklarasi', 'name', 'jabatan', 'nama_dibayar', 'tujuan', 'sebesar', 'status');
    var $column_search2 = array('kode_deklarasi', 'tgl_deklarasi', 'name', 'jabatan', 'nama_dibayar', 'tujuan', 'sebesar', 'status'); //field yang diizin untuk pencarian 
    var $order2 = array('id' => 'desc'); // default order 

    // Prepayment
    var $table4 = 'bmn_prepayment';
    var $column_order3 = array(null, null, 'kode_prepayment', 'name', 'divisi', 'jabatan', 'tgl_prepayment', 'prepayment', 'total_nominal', 'status');
    var $column_search3 = array('kode_prepayment', 'name', 'divisi', 'jabatan', 'tgl_prepayment', 'prepayment', 'total_nominal', 'status'); //field yang diizin untuk pencarian
    var $order3 = array('id' => 'desc');

    public function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query()
    {
        $this->db->select('bmn_reimbust.*, tbl_data_user.name'); // Memilih kolom dari kedua tabel
        $this->db->from($this->table);
        $this->db->join('tbl_data_user', 'tbl_data_user.id_user = bmn_reimbust.id_user', 'left'); // JOIN dengan tabel tbl_user

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
                        $this->db->like('bmn_reimbust.' . $item, $_POST['search']['value']);
                    }
                } else {
                    if ($item == 'name') {
                        $this->db->or_like('tbl_data_user.' . $item, $_POST['search']['value']);
                    } else {
                        $this->db->or_like('bmn_reimbust.' . $item, $_POST['search']['value']);
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
                    $this->db->where('app_status', 'waiting')
                        ->where('app2_status', 'waiting')
                        ->or_where('bmn_reimbust.id_user =' . $id_user_logged_in . ' AND app_status = "approved" AND app2_status = "waiting"')
                        ->or_where('app2_name = (SELECT name FROM tbl_data_user WHERE id_user = ' . $id_user_logged_in . ' AND app_status = "approved" AND app2_status = "waiting" AND status != "rejected" AND status != "revised")', NULL, FALSE);
                } else {
                    $this->db->where('status = "on-process"');
                }
            } elseif ($_POST['status'] == 'approved') {
                // Conditions for 'approved' status
                if ($alias != "eko") {
                    $this->db->where('app_status', $_POST['status'])
                        ->where('app2_status', 'approved')
                        ->or_where('app_name = (SELECT name FROM tbl_data_user WHERE id_user = ' . $id_user_logged_in . ' AND app_status = "approved" AND app2_status != "rejected")', NULL, FALSE);
                } else {
                    $this->db->where('status = "approved"');
                }
            } elseif ($_POST['status'] == 'revised') {
                if ($alias != "eko") {
                    $this->db->where('app2_name = (SELECT name FROM tbl_data_user WHERE id_user = ' . $id_user_logged_in . ' AND app2_status = "revised")', NULL, FALSE)
                        ->or_where('app_name = (SELECT name FROM tbl_data_user WHERE id_user = ' . $id_user_logged_in . ' AND app_status = "revised")', NULL, FALSE)
                        ->or_where('bmn_reimbust.id_user =' . $id_user_logged_in . ' AND (app_status = "revised" OR app2_status = "revised")');
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
                $this->db->where('bmn_reimbust.id_user', $this->session->userdata('id_user'));
            } elseif ($_POST['tab'] == 'employee') {
                if ($alias != "eko") {
                    $this->db->group_start()
                        ->where('bmn_reimbust.app_name =', "(SELECT name FROM tbl_data_user WHERE id_user = " . $this->session->userdata('id_user') . ")", FALSE)
                        ->where('bmn_reimbust.id_user !=', $this->session->userdata('id_user'))
                        ->or_where('bmn_reimbust.app2_name =', "(SELECT name FROM tbl_data_user WHERE id_user = " . $this->session->userdata('id_user') . ") && bmn_reimbust.app_status = 'approved'", FALSE)
                        ->where('bmn_reimbust.id_user !=', $this->session->userdata('id_user'))
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
        $this->db->select('bmn_reimbust.*, tbl_data_user.name'); // Memilih kolom dari kedua tabel
        $this->db->from($this->table);
        $this->db->join('tbl_data_user', 'tbl_data_user.id_user = bmn_reimbust.id_user', 'left'); // JOIN dengan tabel tbl_user
        // Tambahkan pemfilteran berdasarkan status
        $id_user_logged_in = $this->session->userdata('id_user'); // Mengambil id_user dari sesi pengguna yang login
        $alias = $this->session->userdata('username');

        if (!empty($_POST['status'])) {
            $this->db->group_start(); // Start grouping conditions

            if ($_POST['status'] == 'on-process') {
                // Conditions for 'on-process' status
                if ($alias != "eko") {
                    $this->db->where('app_status', 'waiting')
                        ->where('app2_status', 'waiting')
                        ->or_where('bmn_reimbust.id_user =' . $id_user_logged_in . ' AND app_status = "approved" AND app2_status = "waiting"')
                        ->or_where('app2_name = (SELECT name FROM tbl_data_user WHERE id_user = ' . $id_user_logged_in . ' AND app_status = "approved" AND app2_status = "waiting" AND status != "rejected" AND status != "revised")', NULL, FALSE);
                } else {
                    $this->db->where('status = "on-process"');
                }
            } elseif ($_POST['status'] == 'approved') {
                // Conditions for 'approved' status
                $this->db->where('app_status', $_POST['status'])
                    ->where('app2_status', 'approved')
                    ->or_where('app_name = (SELECT name FROM tbl_data_user WHERE id_user = ' . $id_user_logged_in . ' AND app_status = "approved" AND app2_status != "rejected")', NULL, FALSE);
            } elseif ($_POST['status'] == 'revised') {
                if ($alias != "eko") {
                    $this->db->where('app2_name = (SELECT name FROM tbl_data_user WHERE id_user = ' . $id_user_logged_in . ' AND app2_status = "revised")', NULL, FALSE)
                        ->or_where('app_name = (SELECT name FROM tbl_data_user WHERE id_user = ' . $id_user_logged_in . ' AND app_status = "revised")', NULL, FALSE)
                        ->or_where('bmn_reimbust.id_user =' . $id_user_logged_in . ' AND (app_status = "revised" OR app2_status = "revised")');
                } else {
                    $this->db->where('status = "revised"');
                }
            } elseif ($_POST['status'] == 'rejected') {
                $this->db->where('status', $_POST['status']);
            }

            $this->db->group_end(); // End grouping conditions
        }

        // Tambahkan kondisi be'rdasarkan tab yang dipilih
        if (!empty($_POST['tab'])) {
            if ($_POST['tab'] == 'personal') {
                $this->db->where('bmn_reimbust.id_user', $this->session->userdata('id_user'));
            } elseif ($_POST['tab'] == 'employee') {
                if ($alias != "eko") {
                    $this->db->group_start()
                        ->where('bmn_reimbust.app_name =', "(SELECT name FROM tbl_data_user WHERE id_user = " . $this->session->userdata('id_user') . ")", FALSE)
                        ->where('bmn_reimbust.id_user !=', $this->session->userdata('id_user'))
                        ->or_where('bmn_reimbust.app2_name =', "(SELECT name FROM tbl_data_user WHERE id_user = " . $this->session->userdata('id_user') . ") && bmn_reimbust.app_status = 'approved'", FALSE)
                        ->where('bmn_reimbust.id_user !=', $this->session->userdata('id_user'))
                        ->group_end();
                }
            }
        }
        return $this->db->count_all_results();
    }

    // Data Deklarasi
    private function _get_datatables_query2()
    {

        // $this->db->from($this->table);
        $this->db->where('is_active', 1);
        $this->db->select('bmn_deklarasi.*, tbl_data_user.name');
        $this->db->from($this->table3);
        $this->db->join('tbl_data_user', 'tbl_data_user.id_user = bmn_deklarasi.id_pengaju', 'left');
        $id_user_logged_in = $this->session->userdata('id_user'); // Mengambil id_user dari sesi pengguna yang login
        $this->db->where('id_pengaju', $id_user_logged_in);

        $i = 0;

        foreach ($this->column_search2 as $item) // looping awal
        {
            if ($_POST['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
            {

                if ($i === 0) // looping awal
                {
                    $this->db->group_start();
                    if ($item == 'name') {
                        $this->db->like('tbl_data_user.' . $item, $_POST['search']['value']);
                    } else {
                        $this->db->like('bmn_deklarasi.' . $item, $_POST['search']['value']);
                    }
                } else {
                    if ($item == 'name') {
                        $this->db->or_like('tbl_data_user.' . $item, $_POST['search']['value']);
                    } else {
                        $this->db->or_like('bmn_deklarasi.' . $item, $_POST['search']['value']);
                    }
                }

                if (count($this->column_search2) - 1 == $i)
                    $this->db->group_end();
            }
            $i++;
        }

        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order2[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order2)) {
            $order = $this->order2;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables2()
    {
        $this->_get_datatables_query2();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered2()
    {
        $this->_get_datatables_query2();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all2()
    {
        $this->db->select('bmn_deklarasi.*, tbl_data_user.name');
        $this->db->from($this->table3);
        $this->db->join('tbl_data_user', 'tbl_data_user.id_user = bmn_deklarasi.id_pengaju', 'left');
        $id_user_logged_in = $this->session->userdata('id_user'); // Mengambil id_user dari sesi pengguna yang login
        $this->db->where('id_pengaju', $id_user_logged_in);

        return $this->db->count_all_results();
    }

    // Prepayment
    function _get_datatables_query3()
    {
        $this->db->where('is_active', 1);
        $this->db->select('bmn_prepayment.*, tbl_data_user.name'); // Memilih kolom dari kedua tabel
        $this->db->from($this->table4);
        $this->db->join('tbl_data_user', 'tbl_data_user.id_user = bmn_prepayment.id_user', 'left'); // JOIN dengan tabel tbl_user
        $id_user_logged_in = $this->session->userdata('id_user'); // Mengambil id_user dari sesi pengguna yang login
        $this->db->where('bmn_prepayment.id_user', $id_user_logged_in);
        $this->db->where('bmn_prepayment.status', 'approved');

        $i = 0;

        foreach ($this->column_search3 as $item) // looping awal
        {
            if ($_POST['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
            {

                if ($i === 0) // looping awal
                {
                    $this->db->group_start();
                    if ($item == 'name') {
                        $this->db->like('tbl_data_user.' . $item, $_POST['search']['value']);
                    } else {
                        $this->db->like('bmn_prepayment.' . $item, $_POST['search']['value']);
                    }
                } else {
                    if ($item == 'name') {
                        $this->db->or_like('tbl_data_user.' . $item, $_POST['search']['value']);
                    } else {
                        $this->db->or_like('bmn_prepayment.' . $item, $_POST['search']['value']);
                    }
                }

                if (count($this->column_search3) - 1 == $i) {
                    $this->db->group_end();
                }
            }
            $i++;
        }

        // Tambahkan kondisi berdasarkan tab yang dipilih
        if (!empty($_POST['tab'])) {
            if ($_POST['tab'] == 'personal') {
                $this->db->where('bmn_prepayment.id_user', $this->session->userdata('id_user'));
            } elseif ($_POST['tab'] == 'employee') {
                $this->db->group_start()
                    ->where('bmn_prepayment.app_name =', "(SELECT name FROM tbl_data_user WHERE id_user = " . $this->session->userdata('id_user') . ")", FALSE)
                    ->where('bmn_prepayment.id_user !=', $this->session->userdata('id_user'))
                    ->or_where('bmn_prepayment.app2_name =', "(SELECT name FROM tbl_data_user WHERE id_user = " . $this->session->userdata('id_user') . ") && tbl_prepayment.app_status = 'approved'", FALSE)
                    ->where('bmn_prepayment.id_user !=', $this->session->userdata('id_user'))
                    ->group_end();
            }
        }

        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order3[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order3)) {
            $order = $this->order3;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    // UNTUK MENAMPILKAN HASIL QUERY KE DATA TABLES
    function get_datatables3()
    {
        $this->_get_datatables_query3();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered3()
    {
        $this->_get_datatables_query3();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all3()
    {
        $this->db->select('bmn_prepayment.*, tbl_data_user.name'); // Memilih kolom dari kedua tabel
        $this->db->from($this->table4);
        $this->db->join('tbl_data_user', 'tbl_data_user.id_user = bmn_prepayment.id_user', 'left'); // JOIN dengan tabel tbl_user
        $id_user_logged_in = $this->session->userdata('id_user'); // Mengambil id_user dari sesi pengguna yang login
        $this->db->where('bmn_prepayment.id_user', $id_user_logged_in);

        return $this->db->count_all_results();
    }

    // GET BY ID TABLE REIMBUST MASTER
    public function get_by_id($id)
    {
        $this->db->where($this->id, $id);
        return $this->db->get($this->table)->row();
    }

    // GET BY ID TABLE DETAIL REIMBUST TRANSAKSI
    public function get_by_id_detail($id)
    {
        $this->db->where('reimbust_id', $id);
        return $this->db->get($this->table2)->result_array();
    }

    // UNTUK QUERY MENGAMBIL KODE UNTUK DIGENERATE DI CONTROLLER
    public function max_kode($date)
    {
        $formatted_date = date('ym', strtotime($date));
        $this->db->select('kode_reimbust');
        $where = 'id=(SELECT max(id) FROM bmn_reimbust where SUBSTRING(kode_reimbust, 2, 4) = ' . $formatted_date . ')';
        $this->db->where($where);
        $query = $this->db->get('bmn_reimbust');
        return $query;
    }

    public function approval($id)
    {
        $this->db->select('app_id, app2_id');
        $this->db->from('tbl_data_user');
        $this->db->where('id_user', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function save($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function save_detail($data)
    {
        $this->db->insert_batch($this->table2, $data);
        return $this->db->insert_id();
    }

    public function delete($id)
    {
        // Ambil data tbl_reimbust_detail berdasarkan reimbust_id
        $detail = $this->db->get_where('bmn_reimbust_detail', ['reimbust_id' => $id])->result_array();

        // untuk menghapus file gambar
        if ($detail) {
            foreach ($detail as $rd) {
                $old_image = $rd['kwitansi'];
                $file_path = FCPATH . './assets/backend/document/reimbust/kwitansi_bmn/' . $old_image;

                if (file_exists($file_path)) {
                    unlink($file_path);
                } else {
                    echo json_encode(array("status" => FALSE, "error" => "File '$old_image' tidak ditemukan di direktori."));
                    return;
                }
            }
        }

        // untuk mengembalikan is_active pada deklarasi menjadi 1 / aktif
        if ($detail) {
            foreach ($detail as $deklarasi) {
                $deklarasi = $deklarasi['deklarasi'];

                $this->db->update('bmn_deklarasi', ['is_active' => 1], ['kode_deklarasi' => $deklarasi]);
            }
        }

        // Ambil data tbl_reimbust berdasarkan reimbust_id
        $reimbust = $this->db->get_where('bmn_reimbust', ['id' => $id])->row_array();

        // ambil data prepayment pada table reimbust
        $prepayment = $reimbust['kode_prepayment'];

        // update data pada kolom is active menjadi 1 di table prepayment
        $this->db->update('bmn_prepayment', ['is_active' => 1], ['kode_prepayment' => $prepayment]);

        // delete data master reimbust
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);

        // delete data detail teimbust
        $this->db->where('reimbust_id', $id);
        $this->db->delete('bmn_reimbust_detail');

        echo json_encode(array("status" => TRUE));
    }

    // OPSI REKENING
    public function options($id)
    {
        return $this->db->distinct()->select('no_rek')->where('id_user', $id)->from('bmn_reimbust')->get();
    }
}
