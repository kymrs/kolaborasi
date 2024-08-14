<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Prepayment extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_prepayment');
        $this->M_login->getsecurity();
    }

    public function index()
    {
        $data['title'] = "backend/prepayment/prepayment_list";
        $data['titleview'] = "Data Prepayment";
        $this->load->view('backend/home', $data);
    }

    function get_list()
    {
        $list = $this->M_prepayment->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<a href="prepayment/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
            <a href="prepayment/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
            <a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>';
            $row[] = strtoupper($field->kode_prepayment);
            $row[] = $field->nama;
            $row[] = strtoupper($field->jabatan);
            $row[] = $field->tgl_prepayment;
            $row[] = $field->prepayment;
            $row[] = $field->tujuan;
            $row[] = $field->status;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_prepayment->count_all(),
            "recordsFiltered" => $this->M_prepayment->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    function read_form($id)
    {
        $data['aksi'] = 'read';
        $data['id'] = $id;
        $data['title_view'] = "Data Prepayment";
        $data['title'] = 'backend/prepayment/prepayment_form';
        $this->load->view('backend/home', $data);
    }

    // MENGENERATE DAN MERESET NO URUT KODE PREPAYMENT SETIAP BULAN
    public function add_form()
    {
        // $kode = $this->M_prepayment->max_kode()->row();
        // if (empty($kode->kode_prepayment)) {
        //     $no_urut = 1;
        // } else {
        //     $bln = substr($kode->kode_prepayment, 3, 2);
        //     if ($bln != date('m')) {
        //         $no_urut = 1;
        //     } else {
        //         $no_urut = substr($kode->kode_prepayment, 5) + 1;
        //     }
        // }
        // $urutan = str_pad($no_urut, 4, "0", STR_PAD_LEFT);
        // $data['kode'] = 'p' . date('ym') . $urutan;
        $data['id'] = 0;
        $data['title'] = 'backend/prepayment/prepayment_form';
        $data['title_view'] = 'Prepayment Form';
        $this->load->view('backend/home', $data);
    }

    public function generate_kode()
    {
        $date = $this->input->post('date');
        $kode = $this->M_prepayment->max_kode()->row();
        if (empty($kode->kode_prepayment)) {
            $no_urut = 1;
        } else {
            $bln = substr($kode->kode_prepayment, 3, 2);
            if ($bln != date('m')) {
                $no_urut = 1;
            } else {
                $no_urut = substr($kode->kode_prepayment, 5) + 1;
            }
        }
        $urutan = str_pad($no_urut, 4, "0", STR_PAD_LEFT);
        $month = substr($date, 3, 2);
        $year = substr($date, 8, 2);
        $data = 'p' . $year . $month . $urutan;
        echo json_encode($data);
    }

    function edit_form($id)
    {
        $data['id'] = $id;
        $data['title_view'] = "Edit Data Prepayment";
        $data['title'] = 'backend/prepayment/prepayment_form';
        $this->load->view('backend/home', $data);
    }

    function edit_data($id)
    {
        $data = $this->M_prepayment->get_by_id($id);
        echo json_encode($data);
    }

    // MENAMBAHKAN DATA
    public function add()
    {
        $data = array(
            'kode_prepayment' => $this->input->post('kode_prepayment'),
            'nama' => $this->input->post('nama'),
            'jabatan' => $this->input->post('jabatan'),
            'prepayment' => $this->input->post('prepayment'),
            'tujuan' => $this->input->post('tujuan'),
            'tgl_prepayment' => date('Y-m-d', strtotime($this->input->post('tgl_prepayment')))
        );
        $inserted = $this->M_prepayment->save($data);
        if ($inserted) {
            // INISIASI VARIABEL INPUT DETAIL PREPAYMENT
            $rincian = $this->input->post('rincian[]');
            $nominal = $this->input->post('nominal[]');
            $keterangan = $this->input->post('keterangan[]');
            //PERULANGAN UNTUK INSER QUERY DETAIL PREPAYMENT
            for ($i = 1; $i <= count($_POST['rincian']); $i++) {
                $data2[] = array(
                    'prepayment_id' => $inserted,
                    'rincian' => $rincian[$i],
                    'nominal' => $nominal[$i],
                    'keterangan' => $keterangan[$i]
                );
            }
            $this->M_prepayment->save_detail($data2);
        }
        echo json_encode(array("status" => TRUE));
    }

    // UPDATE DATA
    public function update()
    {
        $data = array(
            'kode_prepayment' => $this->input->post('kode_prepayment'),
            'nama' => $this->input->post('nama'),
            'jabatan' => $this->input->post('jabatan'),
            'prepayment' => $this->input->post('prepayment'),
            'tujuan' => $this->input->post('tujuan'),
            'tgl_prepayment' => date('Y-m-d', strtotime($this->input->post('tgl_prepayment')))
        );
        $this->db->where('id', $this->input->post('id'));
        $this->db->update('tbl_prepayment', $data);
        echo json_encode(array("status" => TRUE));
    }

    // MENGHAPUS DATA
    function delete($id)
    {
        $this->M_prepayment->delete($id);
        echo json_encode(array("status" => TRUE));
    }
}
