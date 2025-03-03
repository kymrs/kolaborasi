<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('backend/M_user');
		$this->load->model('backend/M_approval');
		$this->load->model('backend/M_level');
		// $this->load->model('backend/M_notifikasi');
		$this->M_login->getsecurity();
	}

	function index()
	{
		// $data['notif'] = $this->M_notifikasi->pending_notification();
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
			$row[] = ($akses->edit_level == 'Y' ? '<a href="user/edit_form/' . $field->id_user . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;' : '') .
				($akses->delete_level == 'Y' ? '<a class="btn btn-danger btn-circle btn-sm" title="Delete" onclick="delete_user(' . "'" . $field->id_user . "'" . ')"><i class="fa fa-trash"></i></a>' : '');
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
		$data['users'] = $this->db->select('id_user, fullname')->from('tbl_user')->get()->result_object();
		$data['userlevels'] = $this->db->select('id_level, nama_level')->from('tbl_userlevel')->get()->result_object();
		$this->load->view('backend/home', $data);
	}

	function edit_form($id)
	{
		$data['id'] = $id;
		$data['title_view'] = "Edit User Form";
		$data['aksi'] = 'update';
		$data['users'] = $this->db->select('id_user, fullname')->from('tbl_user')->get()->result_object();
		$data['userlevels'] = $this->db->select('id_level, nama_level')->from('tbl_userlevel')->get()->result_object();
		$data['title'] = 'backend/user/user_form';
		$this->load->view('backend/home', $data);
	}

	function edit_data($id)
	{
		$data['user'] = $this->db->get_where('tbl_user', ['id_user' => $id])->row_array();
		$data['detail'] = $this->db->get_where('tbl_data_user', ['id_user' => $id])->row_array();
		echo json_encode($data);
	}

	function delete($id)
	{
		$data = $this->M_user->get_by_id($id);
		$img = $data->image;
		$path = './assets/backend/img/user/' . $img;
		if (is_file($path)) {
			unlink($path);
		}

		if ($this->M_user->delete_id($id)) {
			echo json_encode(array("status" => TRUE));
		} else {
			# GAGAL MENHAPUS DATA USER DAN APPROVAL
			echo json_encode(array("status" => FALSE, "error" => "user"));
		}
	}

	function add()
	{
		// ADD USER

		// PENGECEKAN FILE UPLOAD
		$max_size = 3072 * 1024; // Maksimum ukuran file: 3MB
		$allowed_types = array('image/jpg', 'image/jpeg', 'image/png');

		if (!empty($_FILES['image']['name'])) {

			if ($_FILES['image']['size'] > $max_size) {
				echo json_encode(array("status" => FALSE, "error" => "size"));
				return;
			} elseif (!in_array($_FILES['image']['type'], $allowed_types)) {
				echo json_encode(array("status" => FALSE, "error" => "type"));
				return;
			}

			$config['upload_path'] = "./assets/backend/img/user/";
			$config['allowed_types'] = 'jpg|png|jpeg';
			$config['max_size'] = 3000;
			$config['encrypt_name'] = true;
			$this->load->library('upload', $config); //call library upload 
			if ($this->upload->do_upload("image")) { //upload file
				$data = array('upload_data' => $this->upload->data()); //ambil file name yang diupload
				$img = $data['upload_data']['file_name']; //set file name ke variable image
			} else {
				$img = '';
			}
		} else {
			$img = '';
		}

		$core = implode(',', $this->input->post('core'));

		$data = array(
			'username' => $this->input->post('username'),
			'fullname' => $this->input->post('fullname'),
			'password' => md5($this->input->post('password')),
			'image' => $img,
			'core' => $core,
			'id_level' => $this->input->post('level'),
			'is_active' => $this->input->post('aktif'),
			'app' => $this->input->post('app')
		);

		// Mulai transaksi
		// $this->db->trans_start();

		$insert = $this->M_user->save($data);

		if ($insert) {
			$data2 = array(
				'id_user' => $insert,
				'name' => $this->input->post('fullname'),
				'divisi' => $this->input->post('divisi'),
				'jabatan' => $this->input->post('jabatan'),
			);

			if ($this->M_user->save2($data2)) {
				// Jika semua query berhasil, commit transaksi
				// $this->db->trans_complete();
				echo json_encode(array("status" => TRUE));
			} else {
				// Jika save2 gagal, rollback transaksi
				// $this->db->trans_rollback();
				echo json_encode(array("status" => FALSE, "error" => "data_user"));
			}
		} else {
			// Jika save gagal, rollback transaksi
			// $this->db->trans_rollback();
			echo json_encode(array("status" => FALSE, "error" => "user"));
		}

		// Pastikan transaksi selesai
		// $this->db->trans_complete();
	}

	function update()
	{
		// UPDATE USER
		$data2 = array(
			'username' => $this->input->post('username'),
			'fullname' => $this->input->post('fullname'),
			'core' => implode(',', $this->input->post('core')),
			'id_level' => $this->input->post('level'),
			'is_active' => $this->input->post('aktif'),
			'app' => $this->input->post('app')
		);

		if (!empty($_POST['new_password'])) {
			$data2['password'] = md5($this->input->post('new_password'));
		}

		// PENGECEKAN FILE UPLOAD
		$max_size = 3072 * 1024; // Maksimum ukuran file: 3MB
		$allowed_types = array('image/jpg', 'image/jpeg', 'image/png');

		if (!empty($_FILES['image']['name'])) {
			if ($_FILES['image']['size'] > $max_size) {
				echo json_encode(array("status" => FALSE, "error" => "size"));
				return;
			} elseif (!in_array($_FILES['image']['type'], $allowed_types)) {
				echo json_encode(array("status" => FALSE, "error" => "type"));
				return;
			}

			$config['upload_path'] = "./assets/backend/img/user/";
			$config['allowed_types'] = 'jpg|png|jpeg';
			$config['max_size'] = 3000;
			$config['encrypt_name'] = true;
			$this->load->library('upload', $config); //call library upload 
			if ($this->upload->do_upload("image")) { //upload file
				$data = array('upload_data' => $this->upload->data()); //ambil file name yang diupload
				$img = $data['upload_data']['file_name']; //set file name ke variable image
			}
			$data = array_merge($data2, array('image' => $img));
			$pict = $this->db->get_where('tbl_user', ['id_user' => $this->input->post('hidden_id')])->row();
			$path = './assets/backend/img/user/' . $pict->image;
			if (is_file($path)) {
				unlink($path);
			}
		} else {
			$data = $data2;
		}

		$data3 = array(
			'divisi' => $this->input->post('divisi'),
			'jabatan' => $this->input->post('jabatan'),
		);

		$this->db->trans_start();

		$this->db->where('id_user', $this->input->post('hidden_id'));

		// MENGEMBALIKAN NILAI
		if ($this->db->update('tbl_user', $data)) {
			if ($this->db->where('id_user', $this->input->post('hidden_id'))->update('tbl_data_user', $data3)) {
				$this->db->trans_complete();
				echo json_encode(array("status" => TRUE));
			} else {
				$this->db->trans_rollback();
				echo json_encode(array("status" => False, "error" => "data_user"));
			}
		} else {
			// GAGAL UPDATE USER
			$this->db->trans_rollback();
			echo json_encode(array("status" => FALSE, "error" => "user"));
		}

		$this->db->trans_complete();
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
