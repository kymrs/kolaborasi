<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('backend/M_user');
		$this->load->model('backend/M_level');
		$this->load->model('backend/M_notifikasi');
		$this->M_login->getsecurity();
	}

	function index()
	{
		$data['notif'] = $this->M_notifikasi->pending_notification();
		$akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
		($akses->view_level == 'N' ? redirect('auth') : '');
		$data['add'] = $akses->add_level;
		$data['title'] = "backend/user/user";
		$data['titleview'] = "Data User";
		$this->load->view('backend/home', $data);
	}

	function get_list()
	{
		$list = $this->M_user->get_datatables();
		$data = array();
		$no = $_POST['start'];
		$akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
		foreach ($list as $field) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = ($akses->edit_level == 'Y' ? '<a class="btn btn-warning btn-circle btn-sm" title="Edit" onclick="edit_data(' . "'" . $field->id_user . "'" . ')"><i class="fa fa-edit"></i></a>&nbsp;' : '') .
				($akses->delete_level == 'Y' ? '<a class="btn btn-danger btn-circle btn-sm" title="Delete" onclick="delete_data(' . "'" . $field->id_user . "'" . ')"><i class="fa fa-trash"></i></a>' : '');
			$row[] = $field->username;
			$row[] = $field->fullname;
			$row[] = '<img src="' . site_url("assets/backend/img/user/") . ($field->image != '' ? $field->image : 'default.jpg') . '" height="40px" width="40px">';
			$lvl = $this->M_level->get_by_id($field->id_level);
			$row[] = (empty($lvl->nama_level) ? '' : $lvl->nama_level);
			$row[] = ($field->is_active == 'Y' ? 'Yes' : 'No');

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->M_user->count_all(),
			"recordsFiltered" => $this->M_user->count_filtered(),
			"data" => $data,
		);
		//output dalam format JSON
		echo json_encode($output);
	}

	function add_form()
	{
		$data['id'] = 0;
		$data['title_view'] = "User Form";
		$data['aksi'] = 'add';
		$data['title'] = 'backend/user/user_form';
		$this->load->view('backend/home', $data);
	}

	function delete($id)
	{
		$data = $this->M_user->get_by_id($id);
		$img = $data->image;
		$path = './assets/backend/img/user/' . $img;
		if (is_file($path)) {
			unlink($path);
		}
		$this->M_user->delete_id($id);
		echo json_encode(array("status" => TRUE));
	}

	function add()
	{
		$config['upload_path'] = "./assets/backend/img/user/";
		$config['allowed_types'] = 'gif|jpg|png';
		$this->load->library('upload', $config); //call library upload 
		if ($this->upload->do_upload("image")) { //upload file
			$data = array('upload_data' => $this->upload->data()); //ambil file name yang diupload
			$img = $data['upload_data']['file_name']; //set file name ke variable image
		} else {
			$img = '';
		}

		$data = array(
			'username' => $this->input->post('username'),
			'fullname' => $this->input->post('fullname'),
			'password' => md5($this->input->post('password')),
			'image' => $img,
			'id_level' => $this->input->post('level'),
			'is_active' => $this->input->post('aktif'),
		);
		$this->M_user->save($data);
		echo json_encode(array("status" => TRUE));
	}

	function update()
	{
		$data2 = array(
			'username' => $this->input->post('username'),
			'fullname' => $this->input->post('fullname'),
			'id_level' => $this->input->post('level'),
			'is_active' => $this->input->post('aktif'),
		);
		$data3 = array('password' => md5($this->input->post('password')));
		if ($this->input->post('password') == "") {
			$data1 = $data2;
		} else {
			$data1 = array_merge($data2, $data3);
		}

		if (!empty($_FILES['image']['name'])) {
			$config['upload_path'] = "./assets/backend/img/user/";
			$config['allowed_types'] = 'gif|jpg|png';
			$this->load->library('upload', $config); //call library upload 
			if ($this->upload->do_upload("image")) { //upload file
				$data = array('upload_data' => $this->upload->data()); //ambil file name yang diupload
				$img = $data['upload_data']['file_name']; //set file name ke variable image
			}
			$data = array_merge($data1, array('image' => $img));
			$pict = $this->M_user->get_by_id($this->input->post('id'));
			$path = './assets/backend/img/user//' . $pict->image;
			if (is_file($path)) {
				unlink($path);
			}
		} else {
			$data = $data1;
		}

		$this->db->where('id_user', $this->input->post('id'));
		$this->db->update('tbl_user', $data);
		echo json_encode(array("status" => TRUE));
	}

	function get_id($id)
	{
		$data = $this->M_user->get_by_id($id);
		echo json_encode($data);
	}

	function get_level()
	{
		$data = $this->db->get('tbl_userlevel')->result();
		echo json_encode($data);
	}
}
