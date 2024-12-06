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

	public function logout()
	{
		// $this->session->unset_userdata('username');
		$this->session->sess_destroy();
		redirect('auth');
	}
}
