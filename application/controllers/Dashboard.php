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

	public function get_pending_notifications()
	{
		// $this->load->model('M_notifikasi');
		$notifications = $this->M_notifikasi->pending_notification();
		echo json_encode($notifications);
	}

	public function index()
	{
		$data['title'] = "backend/dashboard";
		$data['titleview'] = 'Dashboard';
		// $data['notif'] = $this->M_notifikasi->pending_notification();
		$this->load->view('backend/home', $data);
	}
}
