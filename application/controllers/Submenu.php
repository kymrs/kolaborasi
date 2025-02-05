<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Submenu extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('backend/M_submenu');
		// $this->load->model('backend/M_notifikasi');
		$this->load->model('backend/M_menu');
		$this->M_login->getsecurity();
	}

	function index()
	{
<<<<<<< HEAD
		// $data['notif'] = $this->M_notifikasi->pending_notification();
=======

>>>>>>> 0cd3da1964fe43a389c23c2bd525e0431a8b9da7
		$akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
		($akses->view_level == 'N' ? redirect('auth') : '');
		$data['add'] = $akses->add_level;
		$data['title'] = "backend/submenu";
		$data['titleview'] = "Data Sub Menu";
		$this->load->view('backend/home', $data);
	}

	function get_list()
	{
		$list = $this->M_submenu->get_datatables();
		$data = array();
		$no = $_POST['start'];
		$akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
		foreach ($list as $field) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = ($akses->edit_level == 'Y' ? '<a class="btn btn-warning btn-circle btn-sm" title="Edit" onclick="edit_data(' . "'" . $field->id_submenu . "'" . ')"><i class="fa fa-edit"></i></a>&nbsp;' : '') .
				($akses->delete_level == 'Y' ? '<a class="btn btn-danger btn-circle btn-sm" title="Delete" onclick="delete_data(' . "'" . $field->id_submenu . "'" . ')"><i class="fa fa-trash"></i></a>' : '');
			$row[] = $field->nama_submenu;
			$row[] = $field->link;
			$row[] = $field->icon;
			$mn = $this->M_menu->get_by_id($field->id_menu);
			$row[] = $mn->nama_menu;
			$row[] = ($field->is_active == 'Y' ? 'Yes' : 'No');
			$row[] = $field->urutan;

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->M_submenu->count_all(),
			"recordsFiltered" => $this->M_submenu->count_filtered(),
			"data" => $data,
		);
		//output dalam format JSON
		echo json_encode($output);
	}

	function delete($id)
	{
		$this->M_submenu->delete_id($id);
		echo json_encode(array("status" => TRUE));
	}

	function add()
	{
		$data = array(
			'nama_submenu' => $this->input->post('submenu'),
			'id_menu' => $this->input->post('menu'),
			'link' => $this->input->post('link'),
			'nama_tbl' => $this->input->post('nama_tbl'),
			'icon' => $this->input->post('icon'),
			'urutan' => $this->input->post('urutan'),
			'is_active' => $this->input->post('aktif'),
		);
		$this->M_submenu->save($data);
		echo json_encode(array("status" => TRUE));
	}

	function update()
	{
		$data = array(
			'nama_submenu' => $this->input->post('submenu'),
			'id_menu' => $this->input->post('menu'),
			'link' => $this->input->post('link'),
			'nama_tbl' => $this->input->post('nama_tbl'),
			'icon' => $this->input->post('icon'),
			'urutan' => $this->input->post('urutan'),
			'is_active' => $this->input->post('aktif'),
		);
		$this->db->where('id_submenu', $this->input->post('id'));
		$this->db->update('tbl_submenu', $data);
		echo json_encode(array("status" => TRUE));
	}

	function get_id($id)
	{
		$data = $this->M_submenu->get_by_id($id);
		echo json_encode($data);
	}

	function get_menu()
	{
		$data = $this->db->get('tbl_menu')->result();
		echo json_encode($data);
	}

	function get_max()
	{
		$max = $this->M_submenu->get_max();
		$data = $max->urutan + 1;
		echo json_encode($data);
	}
}
