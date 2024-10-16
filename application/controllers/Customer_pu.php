<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Customer_pu extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_customer_pu');
        $this->load->model('backend/M_notifikasi');
        $this->M_login->getsecurity();
        date_default_timezone_set('Asia/Jakarta');
    }

    function tgl_indo($tanggal)
    {
        $bulan = array(
            1 =>   'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );
        $pecahkan = explode('-', $tanggal);

        // variabel pecahkan 0 = tanggal
        // variabel pecahkan 1 = bulan
        // variabel pecahkan 2 = tahun

        return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
    }

    public function index()
    {
        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        ($akses->view_level == 'N' ? redirect('auth') : '');
        $data['add'] = $akses->add_level;

        $data['notif'] = $this->M_notifikasi->pending_notification();
        $data['title'] = "backend/customer_pu/customer_list_pu";
        $data['titleview'] = "Data Customer";
        // $name = $this->db->select('name')
        //     ->from('tbl_data_user')
        //     ->where('id_user', $this->session->userdata('id_user'))
        //     ->get()
        //     ->row('name');
        // $data['approval'] = $this->db->select('COUNT(*) as total_approval')
        //     ->from('tbl_customer')
        //     ->where('app_name', $name)
        //     ->or_where('app2_name', $name)
        //     ->get()
        //     ->row('total_approval');
        $this->load->view('backend/home', $data);
    }

    function get_list()
    {
        // INISIAI VARIABLE YANG DIBUTUHKAN
        $fullname = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $this->session->userdata('id_user'))
            ->get()
            ->row('name');
        $list = $this->M_customer_pu->get_datatables();
        $data = array();
        $no = $_POST['start'];

        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        $read = $akses->view_level;
        $edit = $akses->edit_level;
        $delete = $akses->delete_level;
        $print = $akses->print_level;

        //LOOPING DATATABLES
        foreach ($list as $field) {

            // MENENTUKAN ACTION APA YANG AKAN DITAMPILKAN DI LIST DATA TABLES
            $action_read = ($read == 'Y') ? '<a href="customer_pu/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>&nbsp;' : '';
            $action_edit = ($edit == 'Y') ? '<a href="customer_pu/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;' : '';
            $action_delete = ($delete == 'Y') ? '<a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;' : '';
            $action_print = ($print == 'Y') ? '<a class="btn btn-success btn-circle btn-sm" target="_blank" href="customer_pu/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>' : '';

            $action = $action_read . $action_edit . $action_delete .  $action_print;

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $action;
            $row[] = strtoupper($field->group_id);
            $row[] = strtoupper($field->customer_id);
            $row[] = $field->nama;
            $row[] = $field->no_hp;
            $row[] = $this->tgl_indo(date("Y-m-j", strtotime($field->tgl_berangkat)));
            $travel = $this->M_customer_pu->getTravel($field->travel_id);
            $row[] = $travel['travel'];
            // $row[] = $field->tujuan;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_customer_pu->count_all(),
            "recordsFiltered" => $this->M_customer_pu->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    function read_form($id)
    {
        $data['id'] = $id;
        $data['user'] = $this->M_customer_pu->get_by_id($id);
        $data['notif'] = $this->M_notifikasi->pending_notification();
        $data['aksi'] = 'read';
        // $data['app_name'] = $this->db->select('name')
        //     ->from('tbl_data_user')
        //     ->where('id_user', $this->session->userdata('id_user'))
        //     ->get()
        //     ->row('name');
        // $data['app2_name'] = $this->db->select('name')
        //     ->from('tbl_data_user')
        //     ->where('id_user', $this->session->userdata('id_user'))
        //     ->get()  
        //     ->row('name');
        $data['title_view'] = "Data Customer";
        $data['title'] = 'backend/customer_pu/customer_form_pu';
        $data['travel'] = $this->db->get('tbl_travel_pu')->result_array();
        $query = "SELECT DISTINCT group_id FROM tbl_customer_pu ORDER BY group_id DESC";
        $data['group_id'] = $this->db->query($query)->result_array();
        $this->load->view('backend/home', $data);
    }

    function add_form()
    {
        $data['id'] = 0;
        $data['title_view'] = "Customer Form";
        $data['aksi'] = 'update';
        $data['notif'] = $this->M_notifikasi->pending_notification();
        $data['title'] = 'backend/customer_pu/customer_form_pu';
        $data['travel'] = $this->db->get('tbl_travel_pu')->result_array();
        $query = "SELECT DISTINCT group_id FROM tbl_customer_pu ORDER BY group_id DESC";
        $data['group_id'] = $this->db->query($query)->result_array();
        $this->load->view('backend/home', $data);
    }

    function edit_form($id)
    {
        $data['id'] = $id;
        $data['title_view'] = "Edit Data Customer";
        $data['notif'] = $this->M_notifikasi->pending_notification();
        $data['title'] = 'backend/customer_pu/customer_form_pu';
        $data['travel'] = $this->db->get('tbl_travel_pu')->result_array();
        $query = "SELECT DISTINCT group_id FROM tbl_customer_pu ORDER BY group_id DESC";
        $data['group_id'] = $this->db->query($query)->result_array();
        $this->load->view('backend/home', $data);
    }

    function edit_data($id)
    {
        $data['master'] = $this->M_customer_pu->get_by_id($id);
        echo json_encode($data);
    }

    // MEREGENERATE ID Customer
    public function generate_customer_id()
    {
        // Panggil fungsi max_kode untuk mendapatkan customer_id terbaru
        $customer_id = $this->M_customer_pu->max_kode();

        // Kembalikan dalam format JSON
        echo json_encode(array('customer_id' => $customer_id));
    }

    public function add()
    {
        // Panggil fungsi max_kode untuk mendapatkan customer_id terbaru
        $customer_id = $this->M_customer_pu->max_kode();

        // Ambil data group_id dari input
        $group_id_input = $this->input->post('group_id');

        // Jika group_id kosong, ambil group_id terakhir dari database
        if (empty($group_id_input)) {
            $last_group_id = $this->M_customer_pu->get_last_group_id(); // Fungsi untuk mendapatkan group_id terakhir
            $new_group_id = $this->increment_group_id($last_group_id); // Increment group_id
        } else {
            $new_group_id = $group_id_input; // Gunakan input dari user
        }

        // Data yang akan disimpan ke dalam database
        $data = array(
            'customer_id' => $customer_id, // Gunakan kode yang baru di-generate dari max_kode()
            'group_id' => $new_group_id,
            'tgl_berangkat' => date('Y-m-d', strtotime($this->input->post('tgl_berangkat'))),
            'nama' => $this->input->post('nama'),
            'no_hp' => $this->input->post('no_hp'),
            'travel_id' => $this->input->post('travel_id')
        );

        // Simpan data ke database
        $this->M_customer_pu->save($data);

        // Kembalikan status JSON sebagai respons
        echo json_encode(array("status" => TRUE));
    }

    // Fungsi untuk increment group_id
    public function increment_group_id($last_group_id)
    {
        if ($last_group_id) {
            // Ambil nomor urut dari group_id (misal: G002 -> 002)
            $last_number = (int)substr($last_group_id, 1); // Ambil bagian angka
            $new_number = $last_number + 1; // Tambahkan 1 ke angka terakhir
            return 'G' . sprintf("%03d", $new_number); // Kembalikan dalam format GXXX
        }

        // Jika tidak ada group_id sebelumnya, mulai dari G001
        return 'G001';
    }


    public function update()
    {
        $data = array(
            'tgl_berangkat' => date('Y-m-d', strtotime($this->input->post('tgl_berangkat'))),
            'nama' => $this->input->post('nama'),
            'no_hp' => $this->input->post('no_hp'),
            'travel_id' => $this->input->post('travel_id')
        );
        $this->db->where('id', $this->input->post('id'));
        $this->db->update('tbl_customer_pu', $data);
        echo json_encode(array("status" => TRUE));
    }

    function delete($id)
    {
        $this->M_customer_pu->delete($id);
        echo json_encode(array("status" => TRUE));
    }
}
