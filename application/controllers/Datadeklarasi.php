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
        $list = $this->M_datadeklarasi->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<a href="datadeklarasi/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
            <a href="datadeklarasi/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
			<a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>';
            $row[] = $field->kode_deklarasi;
            $row[] = date("d M Y", strtotime($field->tanggal));
            $row[] = $field->nama_pengajuan;
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
        $data['aksi'] = 'read';
        $data['id'] = $id;
        $data['title_view'] = "Data deklarasi";
        $data['title'] = 'backend/datadeklarasi/deklarasi_form';
        $this->load->view('backend/home', $data);
    }

    function add_form()
    {
        $kode = $this->M_datadeklarasi->max_kode()->row();
        if (empty($kode->kode_deklarasi)) {
            $no_urut = 1;
        } else {
            $bln = substr($kode->kode_deklarasi, 3, 2);
            if ($bln != date('m')) {
                $no_urut = 1;
            } else {
                $no_urut = substr($kode->kode_deklarasi, 5) + 1;
            }
        }
        $urutan = str_pad($no_urut, 3, "0", STR_PAD_LEFT);
        $data['kode'] = 'B' . date('ym') . $urutan;
        $data['id'] = 0;
        $data['title_view'] = "Data deklarasi Form";
        $data['title'] = 'backend/datadeklarasi/deklarasi_form';
        $this->load->view('backend/home', $data);
    }

     function edit_form($id)
    {
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

    public function add()
    {
        $kode = $this->M_datadeklarasi->max_kode()->row();
        if (empty($kode->kode_deklarasi)) {
            $no_urut = 1;
        } else {
            $bln = substr($kode->kode_deklarasi, 3, 2);
            if ($bln != date('m')) {
                $no_urut = 1;
            } else {
                $no_urut = substr($kode->kode_deklarasi, 5) + 1;
            }
        }
        $urutan = str_pad($no_urut, 3, "0", STR_PAD_LEFT);
        $kode_deklarasi = 'B' . date('ym') . $urutan;
        $data = array(
            'kode_deklarasi' => $kode_deklarasi,
            'tanggal' => date('Y-m-d', strtotime($this->input->post('tanggal'))),
            'nama_pengajuan' => $this->input->post('nama_pengajuan'),
            'jabatan' => $this->input->post('jabatan'),
            'nama_dibayar' => $this->input->post('nama_dibayar'),
            'tujuan' => $this->input->post('tujuan'),
            'sebesar' => $this->input->post('sebesar'),
            'status' => $this->input->post('status'),
        );
        $this->M_datadeklarasi->save($data);
        echo json_encode(array("status" => TRUE));
    }

    public function update()
    {
        $data = array(
            'tanggal' => date('Y-m-d', strtotime($this->input->post('tanggal'))),
            'nama_pengajuan' => $this->input->post('nama_pengajuan'),
            'jabatan' => $this->input->post('jabatan'),
            'nama_dibayar' => $this->input->post('nama_dibayar'),
            'tujuan' => $this->input->post('tujuan'),
            'sebesar' => $this->input->post('sebesar'),
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
    
}
