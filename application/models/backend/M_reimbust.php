<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_reimbust extends CI_Model
{
    var $id = 'id';
    var $table = 'tbl_reimbust'; //nama tabel dari database
    var $table2 = 'tbl_reimbust_detail';
    var $column_order = array(null, null, 'kode_reimbust', 'name', 'jabatan', 'departemen', 'sifat_pelaporan', 'tgl_pengajuan', 'tujuan', 'jumlah_prepayment', 'status');
    var $column_search = array('kode_reimbust', 'name', 'jabatan', 'departemen', 'sifat_pelaporan', 'tgl_pengajuan', 'tujuan', 'jumlah_prepayment', 'status'); //field yang diizin untuk pencarian 
    var $order = array('id' => 'desc'); // default order 

    public function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query()
    {
        $this->db->select('tbl_reimbust.*, tbl_data_user.name'); // Memilih kolom dari kedua tabel
        $this->db->from($this->table);
        $this->db->join('tbl_data_user', 'tbl_data_user.id_user = tbl_reimbust.id_user', 'left'); // JOIN dengan tabel tbl_user

        $i = 0;

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
                        $this->db->like('tbl_reimbust.' . $item, $_POST['search']['value']);
                    }
                } else {
                    if ($item == 'name') {
                        $this->db->or_like('tbl_data_user.' . $item, $_POST['search']['value']);
                    } else {
                        $this->db->or_like('tbl_reimbust.' . $item, $_POST['search']['value']);
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
                $this->db->where('app_status', 'waiting')
                    ->where('app2_status', 'waiting')
                    ->or_where('app2_name = (SELECT name FROM tbl_data_user WHERE id_user = ' . $id_user_logged_in . ' AND app_status = "approved" AND app2_status = "waiting" AND status != "rejected" AND status != "revised")', NULL, FALSE);
            } elseif ($_POST['status'] == 'approved') {
                // Conditions for 'approved' status
                $this->db->where('app_status', $_POST['status'])
                    ->where('app2_status', 'approved')
                    ->or_where('app_status', $_POST['status'])
                    ->where('app2_status', 'approved')
                    ->or_where('app_name = (SELECT name FROM tbl_data_user WHERE id_user = ' . $id_user_logged_in . ' AND app_status = "approved" AND app2_status != "approved" AND status = "on-process")', NULL, FALSE);
            } elseif ($_POST['status'] == 'revised') {
                $this->db->where('status', $_POST['status']);
            } elseif ($_POST['status'] == 'rejected') {
                $this->db->where('status', $_POST['status']);
            }

            $this->db->group_end(); // End grouping conditions
        }

        // Tambahkan kondisi berdasarkan tab yang dipilih
        if (!empty($_POST['tab'])) {
            if ($_POST['tab'] == 'personal') {
                $this->db->where('tbl_reimbust.id_user', $this->session->userdata('id_user'));
            } elseif ($_POST['tab'] == 'employee') {
                $this->db->group_start()
                    ->where('tbl_reimbust.app_name =', "(SELECT name FROM tbl_data_user WHERE id_user = " . $this->session->userdata('id_user') . ")", FALSE)
                    ->where('tbl_reimbust.id_user !=', $this->session->userdata('id_user'))
                    ->or_where('tbl_reimbust.app2_name =', "(SELECT name FROM tbl_data_user WHERE id_user = " . $this->session->userdata('id_user') . ") && tbl_reimbust.app_status = 'approved'", FALSE)
                    ->where('tbl_reimbust.id_user !=', $this->session->userdata('id_user'))
                    ->group_end();
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
        $this->db->select('tbl_reimbust.*, tbl_data_user.name'); // Memilih kolom dari kedua tabel
        $this->db->from($this->table);
        $this->db->join('tbl_data_user', 'tbl_data_user.id_user = tbl_reimbust.id_user', 'left'); // JOIN dengan tabel tbl_user
        // Tambahkan pemfilteran berdasarkan status
        if (!empty($_POST['status'])) {
            $this->db->where('status', $_POST['status']);
        }

        // Tambahkan kondisi be'rdasarkan tab yang dipilih
        if (!empty($_POST['tab'])) {
            if ($_POST['tab'] == 'personal') {
                $this->db->where('tbl_reimbust.id_user', $this->session->userdata('id_user'));
            } elseif ($_POST['tab'] == 'employee') {
                $this->db->group_start()
                    ->where('tbl_reimbust.app_name =', "(SELECT name FROM tbl_data_user WHERE id_user = " . $this->session->userdata('id_user') . ")", FALSE)
                    ->or_where('tbl_reimbust.app2_name =', "(SELECT name FROM tbl_data_user WHERE id_user = " . $this->session->userdata('id_user') . ")", FALSE)
                    ->group_end();
            }
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
        $where = 'id=(SELECT max(id) FROM tbl_reimbust where SUBSTRING(kode_reimbust, 2, 4) = ' . $formatted_date . ')';
        $this->db->where($where);
        $query = $this->db->get('tbl_reimbust');
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
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);

        // Ambil data tbl_reimbust_detail berdasarkan reimbust_id
        $reimbust_detail = $this->db->get_where('tbl_reimbust_detail', ['reimbust_id' => $id])->result_array();

        if ($reimbust_detail) {
            foreach ($reimbust_detail as $rd) {
                $old_image = $rd['kwitansi'];
                $file_path = FCPATH . './assets/backend/img/reimbust/kwitansi/' . $old_image;

                if (file_exists($file_path)) {
                    unlink($file_path);
                } else {
                    echo json_encode(array("status" => FALSE, "error" => "File '$old_image' tidak ditemukan di direktori."));
                    return;
                }
            }
        }

        $this->db->where('reimbust_id', $id);
        $this->db->delete('tbl_reimbust_detail');

        echo json_encode(array("status" => TRUE));
    }
}
