<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Notifikasi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_notifikasi');
        $this->M_login->getsecurity();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function get_pending_notifications()
    {
        // $this->load->model('M_notifikasi');
        $notifications = $this->M_notifikasi->pending_notification();
        echo json_encode($notifications);
    }
}
