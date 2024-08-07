<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->M_login->getsecurity();
	}

	public function index()
	{
		$data['title'] = "backend/dashboard";
		$data['titleview'] = 'Dashboard';
		$this->load->view('backend/home',$data);
	}
}
