<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Datanotifikasi extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_datanotifikasi');
        $this->M_login->getsecurity();
    }

    public function index()
    {
        $data['title'] = "backend/datanotifikasi/notifikasi_list";
        $data['titleview'] = "Data Notifikasi";
        $this->load->view('backend/home', $data);
    }

    function get_list()
    {
        $list = $this->M_datanotifikasi->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<a href="datanotifikasi/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
            <a href="datanotifikasi/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
			<a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>';
            $row[] = strtoupper($field->kode_notifikasi);
            $row[] = $field->name;
            $row[] = $field->jabatan;
            $row[] = $field->departemen;
            $row[] = $field->pengajuan;
            $row[] = date("d M Y", strtotime($field->tgl_notifikasi));
            $row[] = $field->alasan;
            $row[] = $field->status;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_datanotifikasi->count_all(),
            "recordsFiltered" => $this->M_datanotifikasi->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    function read_form($id)
    {
        $data['id'] = $id;
        $data['user'] = $this->M_datanotifikasi->get_by_id($id);
        $data['app_name'] = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $this->session->userdata('id_user'))
            ->get()
            ->row('name');
        $data['app2_name'] = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $this->session->userdata('id_user'))
            ->get()
            ->row('name');
        $data['title_view'] = "Data Notifikasi";
        $this->load->view('backend/datanotifikasi/notifikasi_read', $data);
    }

    // MEREGENERATE KODE PREPAYMENT
    public function generate_kode()
    {
        $date = $this->input->post('date');
        $kode = $this->M_datanotifikasi->max_kode($date)->row();
        if (empty($kode->kode_notifikasi)) {
            $no_urut = 1;
        } else {
            $bln = substr($kode->kode_notifikasi, 3, 2);
            $no_urut = substr($kode->kode_notifikasi, 5) + 1;
        }
        $urutan = str_pad($no_urut, 3, "0", STR_PAD_LEFT);
        $month = substr($date, 3, 2);
        $year = substr($date, 8, 2);
        $data = 'n' . $year . $month . $urutan;
        echo json_encode($data);
    }

    function add_form()
    {
        $data['id'] = 0;
        $data['title_view'] = "Data Notifikasi Form";
        $data['title'] = 'backend/datanotifikasi/notifikasi_form';
        $this->load->view('backend/home', $data);
    }

    function edit_form($id)
    {
        $data['id'] = $id;
        $data['title_view'] = "Edit Data Notifikasi";
        $data['title'] = 'backend/datanotifikasi/notifikasi_form';
        $this->load->view('backend/home', $data);
    }

    function edit_data($id)
    {
        $data['master'] = $this->M_datanotifikasi->get_by_id($id);
        $data['nama'] = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $data['master']->id_user)
            ->get()->row('name');
        echo json_encode($data);
    }

    public function add()
    {
        // INSERT KODE DEKLARASI
        $date = $this->input->post('tgl_notifikasi');
        $kode = $this->M_datanotifikasi->max_kode($date)->row();
        if (empty($kode->kode_notifikasi)) {
            $no_urut = 1;
        } else {
            $bln = substr($kode->kode_notifikasi, 3, 2);
            $no_urut = substr($kode->kode_notifikasi, 5) + 1;
        }
        $urutan = str_pad($no_urut, 3, "0", STR_PAD_LEFT);
        $month = substr($date, 3, 2);
        $year = substr($date, 8, 2);
        $kode_notifikasi = 'n' . $year . $month . $urutan;

        // MENCARI SIAPA YANG AKAN MELAKUKAN APPROVAL PERMINTAAN
        $approval = $this->M_datanotifikasi->approval($this->session->userdata('id_user'));
        $id = $this->session->userdata('id_user');

        $data = array(
            'kode_notifikasi' => $kode_notifikasi,
            'id_user' => $id,
            'jabatan' => $this->db->select('jabatan')
                ->from('tbl_data_user')
                ->where('id_user', $id)
                ->get()
                ->row('jabatan'),
            'departemen' => $this->db->select('divisi')
                ->from('tbl_data_user')
                ->where('id_user', $id)
                ->get()
                ->row('divisi'),
            'pengajuan' => $this->input->post('pengajuan'),
            'tgl_notifikasi' => date('Y-m-d', strtotime($this->input->post('tgl_notifikasi'))),
            'waktu' => $this->input->post('waktu'),
            'alasan' => $this->input->post('alasan'),
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
        $this->M_datanotifikasi->save($data);
        echo json_encode(array("status" => TRUE));
    }

    public function update()
    {
        $data = array(
            'pengajuan' => $this->input->post('pengajuan'),
            'waktu' => $this->input->post('waktu'),
            'alasan' => $this->input->post('alasan'),
        );
        $this->db->where('id', $this->input->post('id'));
        $this->db->update('tbl_notifikasi', $data);
        echo json_encode(array("status" => TRUE));
    }

    function delete($id)
    {
        $this->M_datanotifikasi->delete($id);
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
        $this->db->update('tbl_notifikasi', $data);

        // UPDATE STATUS PREPAYMENT
        if ($this->input->post('app_status') == 'rejected') {
            $this->db->where('id', $this->input->post('hidden_id'));
            $this->db->update('tbl_notifikasi', ['status' => 'rejected']);
        } elseif ($this->input->post('app_status') == 'revised') {
            $this->db->where('id', $this->input->post('hidden_id'));
            $this->db->update('tbl_notifikasi', ['status' => 'revised']);
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
        $this->db->update('tbl_notifikasi', $data);

        // UPDATE STATUS PREPAYMENT
        if ($this->input->post('app2_status') == 'rejected') {
            $this->db->where('id', $this->input->post('hidden_id'));
            $this->db->update('tbl_notifikasi', ['status' => 'rejected']);
        } elseif ($this->input->post('app2_status') == 'revised') {
            $this->db->where('id', $this->input->post('hidden_id'));
            $this->db->update('tbl_notifikasi', ['status' => 'revised']);
        } elseif ($this->input->post('app2_status') == 'approved') {
            $this->db->where('id', $this->input->post('hidden_id'));
            $this->db->update('tbl_notifikasi', ['status' => 'approved']);
        }
        echo json_encode(array("status" => TRUE));
    }
}
