<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Datadeklarasi extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_datadeklarasi');
        $this->M_login->getsecurity();
    }

    public function index()
    {
        $data['title'] = "backend/datadeklarasi/deklarasi_list";
        $data['titleview'] = "Data Deklarasi";
        $this->load->view('backend/home', $data);
    }

    function get_list()
    {
        $id_level = $this->session->userdata('id_level');
        $fullname = $this->session->userdata('fullname');
        $list = $this->M_datadeklarasi->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {

            $action = '<a href="datadeklarasi/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                                <a href="datadeklarasi/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
                                <a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>
                                <a href="datadeklarasi/app_form/' . $field->id . '" class="btn btn-success btn-circle btn-sm" title="Approval"><i class="fa fa-check" aria-hidden="true"></i></a>';

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $action;
            $row[] = strtoupper($field->kode_deklarasi);
            $row[] = date("d M Y", strtotime($field->tgl_deklarasi));
            $row[] = $field->name;
            $row[] = $field->jabatan;
            $row[] = $field->nama_dibayar;
            $row[] = $field->tujuan;
            $row[] = $field->sebesar;
            $row[] = $field->status;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_datadeklarasi->count_all(),
            "recordsFiltered" => $this->M_datadeklarasi->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    function read_form($id)
    {
        $data['mengetahui'] = $this->M_datadeklarasi->mengetahui();
        $data['menyetujui'] = $this->M_datadeklarasi->menyetujui();
        $data['aksi'] = 'read';
        $data['id'] = $id;
        $data['title_view'] = "Data deklarasi";
        $data['title'] = 'backend/datadeklarasi/deklarasi_form';
        $this->load->view('backend/home', $data);
    }

    function add_form()
    {
        $data['id'] = 0;
        $data['title_view'] = "Data deklarasi Form";
        $data['title'] = 'backend/datadeklarasi/deklarasi_form';
        $this->load->view('backend/home', $data);
    }

    function edit_form($id)
    {
        $data['mengetahui'] = $this->M_datadeklarasi->mengetahui();
        $data['menyetujui'] = $this->M_datadeklarasi->menyetujui();
        $data['id'] = $id;
        $data['title_view'] = "Edit Data Deklarasi";
        $data['title'] = 'backend/datadeklarasi/deklarasi_form';
        $this->load->view('backend/home', $data);
    }

    function edit_data($id)
    {
        $data = $this->M_datadeklarasi->get_by_id($id);
        echo json_encode($data);
    }

    // UNTUK MENAMPILKAN FORM APPROVAL
    public function app_form($id)
    {
        $data['id'] = $id;
        $data['title'] = 'backend/datadeklarasi/deklarasi_app';
        $data['title_view'] = 'Deklarasi Approval';
        $this->load->view('backend/home', $data);
    }

    // MEREGENERATE KODE DEKLARASI
    public function generate_kode()
    {
        $date = $this->input->post('date');
        $kode = $this->M_datadeklarasi->max_kode($date)->row();
        if (empty($kode->kode_deklarasi)) {
            $no_urut = 1;
        } else {
            $bln = substr($kode->kode_deklarasi, 3, 2);
            $no_urut = substr($kode->kode_deklarasi, 5) + 1;
        }
        $urutan = str_pad($no_urut, 3, "0", STR_PAD_LEFT);
        $month = substr($date, 3, 2);
        $year = substr($date, 8, 2);
        $data = 'b' . $year . $month . $urutan;
        echo json_encode($data);
    }

    public function add()
    {
        // INSERT KODE DEKLARASI
        $date = $this->input->post('tgl_deklarasi');
        $kode = $this->M_datadeklarasi->max_kode($date)->row();
        if (empty($kode->kode_deklarasi)) {
            $no_urut = 1;
        } else {
            $bln = substr($kode->kode_deklarasi, 3, 2);
            $no_urut = substr($kode->kode_deklarasi, 5) + 1;
        }
        $urutan = str_pad($no_urut, 3, "0", STR_PAD_LEFT);
        $month = substr($date, 3, 2);
        $year = substr($date, 8, 2);
        $kode_deklarasi = 'b' . $year . $month . $urutan;

        // MENCARI SIAPA YANG AKAN MELAKUKAN APPROVAL PERMINTAAN
        $approval = $this->M_datadeklarasi->approval($this->session->userdata('id_user'));
        $id = $this->session->userdata('id_user');

        $data = array(
            'kode_deklarasi' => $kode_deklarasi,
            'tgl_deklarasi' => date('Y-m-d', strtotime($this->input->post('tgl_deklarasi'))),
            'id_pengaju' => $id,
            'jabatan' => $this->db->select('jabatan')
                ->from('tbl_data_user')
                ->where('id_user', $id)
                ->get()
                ->row('jabatan'),
            'nama_dibayar' => $this->input->post('nama_dibayar'),
            'tujuan' => $this->input->post('tujuan'),
            'sebesar' => $this->input->post('hidden_sebesar'),
            'app_name' => $this->db->select('name')
                ->from('tbl_data_user')
                ->where('id_user', $approval->app_id)
                ->get()
                ->row('name'),
            'app2_name' => $this->db->select('name')
                ->from('tbl_data_user')
                ->where('id_user', $approval->app2_id)
                ->get()
                ->row('name')
        );
        $this->M_datadeklarasi->save($data);
        echo json_encode(array("status" => TRUE));
    }

    public function update()
    {
        $data = array(
            'tgl_deklarasi' => date('Y-m-d', strtotime($this->input->post('tgl_deklarasi'))),
            'nama_dibayar' => $this->input->post('nama_dibayar'),
            'tujuan' => $this->input->post('tujuan'),
            'sebesar' => $this->input->post('hidden_sebesar'),
            'status' => $this->input->post('status'),
        );
        $this->db->where('id', $this->input->post('id'));
        $this->db->update('tbl_deklarasi', $data);
        echo json_encode(array("status" => TRUE));
    }

    function delete($id)
    {
        $this->M_datadeklarasi->delete($id);
        echo json_encode(array("status" => TRUE));
    }

    //APPROVE DATA
    public function approve()
    {
        $data = array(
            'app_name' => $this->input->post('app_name'),
            'app_keterangan' => $this->input->post('app_keterangan'),
            'app_status' => $this->input->post('app_status'),
            'app_date' => date('Y-m-d H:i:s'),
        );
        //UPDATE APPROVAL PERTAMA
        $this->db->where('id', $this->input->post('hidden_id'));
        $this->db->update('tbl_deklarasi', $data);

        // UPDATE STATUS PREPAYMENT
        if ($this->input->post('app_status') == 'rejected') {
            $this->db->where('id', $this->input->post('hidden_id'));
            $this->db->update('tbl_deklarasi', ['status' => 'rejected']);
        } elseif ($this->input->post('app_status') == 'revised') {
            $this->db->where('id', $this->input->post('hidden_id'));
            $this->db->update('tbl_deklarasi', ['status' => 'revised']);
        }

        echo json_encode(array("status" => TRUE));
    }

    function approve2()
    {
        $data = array(
            'app2_name' => $this->input->post('app2_name'),
            'app2_keterangan' => $this->input->post('app2_keterangan'),
            'app2_status' => $this->input->post('app2_status'),
            'app2_date' => date('Y-m-d H:i:s'),
        );
        // UPDATE APPROVAL 2
        $this->db->where('id', $this->input->post('hidden_id'));
        $this->db->update('tbl_deklarasi', $data);

        // UPDATE STATUS PREPAYMENT
        if ($this->input->post('app2_status') == 'rejected') {
            $this->db->where('id', $this->input->post('hidden_id'));
            $this->db->update('tbl_deklarasi', ['status' => 'rejected']);
        } elseif ($this->input->post('app2_status') == 'revised') {
            $this->db->where('id', $this->input->post('hidden_id'));
            $this->db->update('tbl_deklarasi', ['status' => 'revised']);
        } elseif ($this->input->post('app2_status') == 'approved') {
            $this->db->where('id', $this->input->post('hidden_id'));
            $this->db->update('tbl_deklarasi', ['status' => 'approved']);
        }
        echo json_encode(array("status" => TRUE));
    }
}
