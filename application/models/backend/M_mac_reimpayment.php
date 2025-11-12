<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_mac_reimpayment extends CI_Model
{
    // Reimbust
    var $id = 'id';
    var $table = 'mac_reimbust'; //nama tabel dari database
    var $table2 = 'mac_reimbust_detail';
    var $column_order = array(null, null, 'payment_status', 'kode_reimbust', 'name', 'jabatan', 'departemen', 'sifat_pelaporan', 'tgl_pengajuan', 'tujuan', 'jumlah_prepayment', 'status');
    var $column_search = array('payment_status', 'kode_reimbust', 'name', 'jabatan', 'departemen', 'sifat_pelaporan', 'tgl_pengajuan', 'tujuan', 'jumlah_prepayment', 'status'); //field yang diizin untuk pencarian 
    var $order = array('id' => 'desc'); // default order 

    // Deklarasi
    var $table3 = 'mac_deklarasi'; //nama tabel dari database
    var $column_order2 = array(null, null, 'kode_deklarasi', 'tgl_deklarasi', 'name', 'jabatan', 'nama_dibayar', 'tujuan', 'sebesar', 'status');
    var $column_search2 = array('kode_deklarasi', 'tgl_deklarasi', 'name', 'jabatan', 'nama_dibayar', 'tujuan', 'sebesar', 'status'); //field yang diizin untuk pencarian 
    var $order2 = array('id' => 'desc'); // default order 

    // Prepayment
    var $table4 = 'mac_prepayment';
    var $column_order3 = array(null, null, 'kode_prepayment', 'name', 'divisi', 'jabatan', 'tgl_prepayment', 'prepayment', 'total_nominal', 'status');
    var $column_search3 = array('kode_prepayment', 'name', 'divisi', 'jabatan', 'tgl_prepayment', 'prepayment', 'total_nominal', 'status'); //field yang diizin untuk pencarian
    var $order3 = array('id' => 'desc');

    public function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query()
    {
        $this->db->from($this->table);

        $i = 0;

        foreach ($this->column_search as $item) // looping awal
        {
            if ($_POST['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
            {

                foreach ($this->column_search as $item) // looping awal
                {
                    if ($_POST['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
                    {

                        if ($i === 0) // looping awal
                        {
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

                if (count($this->column_search) - 1 == $i)
                    $this->db->group_end();
            }
            $i++;
        }

        if (!empty($_POST['status'])) {
        $this->db->group_start(); // Start grouping conditions
            if ($_POST['status'] == 'on-process') {
                    $this->db->where('status', 'on-process');
            } elseif ($_POST['status'] == 'approved') {
                    $this->db->where('status', 'approved');
            } elseif ($_POST['status'] == 'revised') {
                    $this->db->where('status', 'revised');
            } elseif ($_POST['status'] == 'rejected') {
                $this->db->where('status', $_POST['status']);
            }
            $this->db->group_end(); // End grouping
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
        $this->db->from($this->table);

        if (!empty($_POST['status'])) {
            $this->db->group_start(); // Start grouping conditions
            if ($_POST['status'] == 'on-process') {
                    $this->db->where('status = "on-process"');
            } elseif ($_POST['status'] == 'approved') {
                // Conditions for 'approved' status
                    $this->db->where('status = "approved"');
            } elseif ($_POST['status'] == 'revised') {
                    $this->db->where('status = "revised"');
            } elseif ($_POST['status'] == 'rejected') {
                $this->db->where('status', $_POST['status']);
            }
            $this->db->group_end(); // End grouping
        }

        return $this->db->count_all_results();
    }

    // Data Deklarasi
    private function _get_datatables_query2()
    {

        // $this->db->from($this->table);
        $this->db->where('is_active', 1);
        $this->db->select('mac_deklarasi.*, tbl_data_user.name');
        $this->db->from($this->table3);
        $this->db->join('tbl_data_user', 'tbl_data_user.id_user = mac_deklarasi.id_pengaju', 'left');
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
                        $this->db->like('mac_deklarasi.' . $item, $_POST['search']['value']);
                    }
                } else {
                    if ($item == 'name') {
                        $this->db->or_like('tbl_data_user.' . $item, $_POST['search']['value']);
                    } else {
                        $this->db->or_like('mac_deklarasi.' . $item, $_POST['search']['value']);
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
        $this->db->select('mac_deklarasi.*, tbl_data_user.name');
        $this->db->from($this->table3);
        $this->db->join('tbl_data_user', 'tbl_data_user.id_user = mac_deklarasi.id_pengaju', 'left');
        $id_user_logged_in = $this->session->userdata('id_user'); // Mengambil id_user dari sesi pengguna yang login
        $this->db->where('id_pengaju', $id_user_logged_in);

        return $this->db->count_all_results();
    }

    // Prepayment
    function _get_datatables_query3()
    {
        $this->db->select('*'); // Memilih kolom dari kedua tabel
        $this->db->from($this->table4);

        $i = 0;

        foreach ($this->column_search as $item) // looping awal
        {
            if ($_POST['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
            {

                foreach ($this->column_search as $item) // looping awal
                {
                    if ($_POST['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
                    {

                        if ($i === 0) // looping awal
                        {
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

                if (count($this->column_search) - 1 == $i)
                    $this->db->group_end();
            }
            $i++;
        }

        if (!empty($_POST['status'])) {
            $this->db->group_start();
            if ($_POST['status'] == 'on-process') {
                    $this->db->where('status', 'on-process');
            } elseif ($_POST['status'] == 'approved') {
                    $this->db->where('status', 'approved');
            } elseif ($_POST['status'] == 'revised') {
                    $this->db->where('status', 'revised');
            } elseif ($_POST['status'] == 'rejected') {
                $this->db->where('status', $_POST['status']);
            }
            $this->db->group_end(); // End grouping
        }

        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
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
        $this->db->from($this->table4);

        if (!empty($_POST['status'])) {
            $this->db->group_start();
            if ($_POST['status'] == 'on-process') {
                    $this->db->where('status', 'on-process');
            } elseif ($_POST['status'] == 'approved') {
                    $this->db->where('status', 'approved');
            } elseif ($_POST['status'] == 'revised') {
                    $this->db->where('status', 'revised');
            } elseif ($_POST['status'] == 'rejected') {
                $this->db->where('status', $_POST['status']);
            }
            $this->db->group_end(); // End grouping
        }


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
        $where = 'id=(SELECT max(id) FROM mac_reimbust where SUBSTRING(kode_reimbust, 2, 4) = ' . $formatted_date . ')';
        $this->db->where($where);
        $query = $this->db->get('mac_reimbust');
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
        // Ambil data mac_reimbust_detail berdasarkan reimbust_id
        $detail = $this->db->get_where('mac_reimbust_detail', ['reimbust_id' => $id])->result_array();

        // untuk menghapus file gambar
        if ($detail) {
            foreach ($detail as $rd) {
                $old_image = $rd['kwitansi'];
                $file_path = FCPATH . './assets/backend/document/reimbust/kwitansi_mac/' . $old_image;

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

                $this->db->update('mac_deklarasi', ['is_active' => 1], ['kode_deklarasi' => $deklarasi]);
            }
        }

        // Ambil data mac_reimbust berdasarkan reimbust_id
        $reimbust = $this->db->get_where('mac_reimbust', ['id' => $id])->row_array();

        // ambil data prepayment pada table reimbust
        $prepayment = $reimbust['kode_prepayment'];

        // update data pada kolom is active menjadi 1 di table prepayment
        $this->db->update('mac_prepayment', ['is_active' => 1], ['kode_prepayment' => $prepayment]);

        // delete data master reimbust
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);

        // delete data detail teimbust
        $this->db->where('reimbust_id', $id);
        $this->db->delete('mac_reimbust_detail');

        echo json_encode(array("status" => TRUE));
    }

    // OPSI REKENING
    public function options($id)
    {
        return $this->db->distinct()->select('no_rek')->where('id_user', $id)->from('mac_reimbust')->get();
    }
}
