<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Databooking extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_databooking');
        $this->M_login->getsecurity();
    }

    public function index()
    {
        $data['title'] = "backend/databooking/booking_list";
        $data['titleview'] = "Data Booking";
        $this->load->view('backend/home', $data);
    }

    function get_list()
    {
        $list = $this->M_databooking->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<a href="databooking/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
            <a href="databooking/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
			<a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>';
            $row[] = $field->kode_booking;
            $row[] = $field->nama;
            $row[] = $field->no_hp;
            $row[] = $field->email;
            $row[] = $field->tgl_berangkat;
            $row[] = $field->tgl_pulang;
            $row[] = $field->jam_jemput;
            $row[] = $field->titik_jemput;
            $row[] = $field->type_kendaraan;
            $row[] = $field->jumlah;
            $row[] = $field->status;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_databooking->count_all(),
            "recordsFiltered" => $this->M_databooking->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    function read_form($id)
    {
        $data['aksi'] = 'read';
        $data['id'] = $id;
        $data['title_view'] = "Data Booking";
        $data['title'] = 'backend/databooking/booking_form';
        $this->load->view('backend/home', $data);
    }

    function add_form()
    {
        $kode = $this->M_databooking->max_kode()->row();
        if (empty($kode->kode_booking)) {
            $no_urut = 1;
        } else {
            $bln = substr($kode->kode_booking, 3, 2);
            if ($bln != date('m')) {
                $no_urut = 1;
            } else {
                $no_urut = substr($kode->kode_booking, 5) + 1;
            }
        }
        $urutan = str_pad($no_urut, 3, "0", STR_PAD_LEFT);
        $data['kode'] = 'B' . date('ym') . $urutan;
        $data['id'] = 0;
        $data['title_view'] = "Data Booking Form";
        $data['title'] = 'backend/databooking/booking_form';
        $this->load->view('backend/home', $data);
    }

    function edit_form($id)
    {
        $data['id'] = $id;
        $data['title_view'] = "Edit Data Booking";
        $data['title'] = 'backend/databooking/booking_form';
        $this->load->view('backend/home', $data);
    }

    function edit_data($id)
    {
        $data = $this->M_databooking->get_by_id($id);
        echo json_encode($data);
    }

    public function add()
    {
        $kode = $this->M_databooking->max_kode()->row();
        if (empty($kode->kode_booking)) {
            $no_urut = 1;
        } else {
            $bln = substr($kode->kode_booking, 3, 2);
            if ($bln != date('m')) {
                $no_urut = 1;
            } else {
                $no_urut = substr($kode->kode_booking, 5) + 1;
            }
        }
        $urutan = str_pad($no_urut, 3, "0", STR_PAD_LEFT);
        $kode_booking = 'B' . date('ym') . $urutan;
        $data = array(
            'kode_booking' => $kode_booking,
            'nama' => $this->input->post('nama'),
            'no_hp' => $this->input->post('no_hp'),
            'email' => $this->input->post('email'),
            'tgl_berangkat' => date('Y-m-d', strtotime($this->input->post('tgl_berangkat'))),
            'tgl_pulang' => date('Y-m-d', strtotime($this->input->post('tgl_pulang'))),
            'jam_jemput' => $this->input->post('jam_jemput'),
            'titik_jemput' => $this->input->post('titik_jemput'),
            'type_kendaraan' => $this->input->post('type_kendaraan'),
            'jumlah' => $this->input->post('jumlah'),
            'status' => $this->input->post('status'),
        );
        $this->M_databooking->save($data);
        echo json_encode(array("status" => TRUE));
    }

    public function update()
    {
        $data = array(
            'nama' => $this->input->post('nama'),
            'no_hp' => $this->input->post('no_hp'),
            'email' => $this->input->post('email'),
            'tgl_berangkat' => date('Y-m-d', strtotime($this->input->post('tgl_berangkat'))),
            'tgl_pulang' => date('Y-m-d', strtotime($this->input->post('tgl_pulang'))),
            'jam_jemput' => $this->input->post('jam_jemput'),
            'titik_jemput' => $this->input->post('titik_jemput'),
            'type_kendaraan' => $this->input->post('type_kendaraan'),
            'jumlah' => $this->input->post('jumlah'),
            'status' => $this->input->post('status'),
        );
        $this->db->where('id', $this->input->post('id'));
        $this->db->update('tbl_booking', $data);
        echo json_encode(array("status" => TRUE));
    }

    function delete($id)
    {
        $this->M_databooking->delete($id);
        echo json_encode(array("status" => TRUE));
    }
}
