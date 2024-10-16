<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->M_login->getsecurity();
		$this->load->model('backend/M_notifikasi');
	}

	public function index()
	{
		$data['title'] = "backend/dashboard";
		$data['titleview'] = 'Dashboard';
		$data['notif'] = $this->M_notifikasi->pending_notification();
		$this->load->view('backend/home', $data);
	}
}
