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
            $row[] = $field->kode_notifikasi;
            $row[] = $field->nama;
            $row[] = $field->jabatan;
            $row[] = $field->departemen;
            $row[] = $field->pengajuan;
            $row[] = date("d M Y", strtotime($field->tanggal));
            $row[] = $field->waktu;
            $row[] = $field->alasan;
            $row[] = $field->status;
            $row[] = $field->catatan;
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
        $data['aksi'] = 'read';
        $data['id'] = $id;
        $data['title_view'] = "Data Notifikasi";
        $data['title'] = 'backend/datanotifikasi/notifikasi_form';
        $this->load->view('backend/home', $data);
    }

    function add_form()
    {
        $kode = $this->M_datanotifikasi->max_kode()->row();
        if (empty($kode->kode_notifikasi)) {
            $no_urut = 1;
        } else {
            $bln = substr($kode->kode_notifikasi, 3, 2);
            if ($bln != date('m')) {
                $no_urut = 1;
            } else {
                $no_urut = substr($kode->kode_notifikasi, 5) + 1;
            }
        }
        $urutan = str_pad($no_urut, 3, "0", STR_PAD_LEFT);
        $data['kode'] = 'B' . date('ym') . $urutan;
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
        $data = $this->M_datanotifikasi->get_by_id($id);
        echo json_encode($data);
    }

    public function add()
    {
        $kode = $this->M_datanotifikasi->max_kode()->row();
        if (empty($kode->kode_notifikasi)) {
            $no_urut = 1;
        } else {
            $bln = substr($kode->kode_notifikasi, 3, 2);
            if ($bln != date('m')) {
                $no_urut = 1;
            } else {
                $no_urut = substr($kode->kode_notifikasi, 5) + 1;
            }
        }
        $urutan = str_pad($no_urut, 3, "0", STR_PAD_LEFT);
        $kode_notifikasi = 'B' . date('ym') . $urutan;
        $data = array(
            'kode_notifikasi' => $kode_notifikasi,
            'nama' => $this->input->post('nama'),
            'jabatan' => $this->input->post('jabatan'),
            'departemen' => $this->input->post('departemen'),
            'pengajuan' => $this->input->post('pengajuan'),
            'tanggal' => date('Y-m-d', strtotime($this->input->post('tanggal'))),
            'waktu' => $this->input->post('waktu'),
            'alasan' => $this->input->post('alasan'),
            'status' => $this->input->post('status'),
            'catatan' => $this->input->post('catatan'),
        );
        $this->M_datanotifikasi->save($data);
        echo json_encode(array("status" => TRUE));
    }

    public function update()
    {
        $data = array(
            'nama' => $this->input->post('nama'),
            'jabatan' => $this->input->post('jabatan'),
            'departemen' => $this->input->post('departemen'),
            'pengajuan' => $this->input->post('pengajuan'),
            'tanggal' => date('Y-m-d', strtotime($this->input->post('tanggal'))),
            'waktu' => $this->input->post('waktu'),
            'alasan' => $this->input->post('alasan'),
            'status' => $this->input->post('status'),
            'catatan' => $this->input->post('catatan'),
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
    
}
