<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		// $this->load->model('M_login');
	}

	public function index()
	{
		$cek = $this->session->userdata('log');
		if (empty($cek)) {
			$this->load->view('backend/login');
		} else {
			redirect('dashboard');
		}
	}

	public function login()
	{
		$user = $this->input->post('username');
		$pass = md5($this->input->post('password'));
		$auth = $this->M_login->cek_login($user, $pass)->row();
		if ($pass == $auth->password) {
			$userdata = array(
				'log'			=> 'Masuk',
				'id_user'		=> $auth->id_user,
				'username'		=> $auth->username,
				'fullname'		=> $auth->fullname,
				'id_level'		=> $auth->id_level,
				'core'			=> $auth->core,
				'no_rek'		=> $auth->no_rek,
				'image'			=> $auth->image,
				'active'		=> $auth->is_active
			);
			$this->session->set_userdata($userdata);
			redirect('dashboard');
		} else {
			$this->session->set_flashdata('message', ' <p style="color :red; font-weight: 500"> Username or password incorrect !!!</p> ');
			redirect('auth');
		}
	}

	public function changepassword()
	{
		$this->M_login->getsecurity();
		$data['title'] = "changepassword";
		$data['titleview'] = "Change Password";
		$this->load->view('home', $data);
	}

	public function change_pass()
	{
		$this->M_login->getsecurity();
		$new_pass = md5($this->input->post('new_password1'));
		$user = $this->session->userdata('username');
		$query = $this->db->update('tbl_user', array('password' => $new_pass), array('username' => $user));
		echo json_encode(array("status" => TRUE));
	}

	public function cek_pass()
	{
		$this->M_login->getsecurity();
		$user = $this->session->userdata('username');
		$pass = md5($this->input->post('current_password'));
		$query = $this->M_login->cek_pass($user);
		if ($query->password == $pass) {
			echo json_encode(true);
		} else {
			echo json_encode(false);
		}
	}

	public function change_password()
	{
		header('Content-Type: application/json', true);

		try {
			// Check if user is logged in
			if (!$this->session->userdata('log')) {
				http_response_code(401);
				echo json_encode(array('success' => false, 'message' => 'User tidak ter-autentikasi'));
				return;
			}

			// Get input from POST
			$current_password = $this->input->post('current_password', TRUE);
			$new_password = $this->input->post('new_password', TRUE);
			$confirm_password = $this->input->post('confirm_password', TRUE);
			$username = $this->session->userdata('username');

			// Validasi input tidak kosong
			if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
				echo json_encode(array('success' => false, 'message' => 'Semua field harus diisi!'));
				return;
			}

			// Validasi password minimal 6 karakter
			if (strlen($new_password) < 3) {
				echo json_encode(array('success' => false, 'message' => 'Password baru minimal 3 karakter!'));
				return;
			}

			// Validasi password cocok
			if ($new_password !== $confirm_password) {
				echo json_encode(array('success' => false, 'message' => 'Password baru dan konfirmasi tidak cocok!'));
				return;
			}

			// Get user dari database
			$user = $this->db->select('password')
				->from('tbl_user')
				->where('username', $username)
				->get()
				->row();

			if (!$user) {
				echo json_encode(array('success' => false, 'message' => 'User tidak ditemukan!'));
				return;
			}

			// Verify current password
			$current_password_hash = md5($current_password);
			if ($user->password !== $current_password_hash) {
				echo json_encode(array('success' => false, 'message' => 'Password saat ini salah!'));
				return;
			}

			// Update password baru
			$new_password_hash = md5($new_password);
			$update = $this->db->update('tbl_user', 
				array('password' => $new_password_hash), 
				array('username' => $username)
			);

			if ($update) {
				echo json_encode(array('success' => true, 'message' => 'Password berhasil diubah!'));
			} else {
				echo json_encode(array('success' => false, 'message' => 'Gagal mengubah password!'));
			}

		} catch (Exception $e) {
			http_response_code(500);
			echo json_encode(array('success' => false, 'message' => 'Error: ' . $e->getMessage()));
		}
	}

	function logout()
	{
		// $this->session->unset_userdata('username');
		$this->session->sess_destroy();
		redirect('auth');
	}
}
