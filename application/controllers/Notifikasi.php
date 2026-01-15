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

    // Untuk sidebar
    public function get_pending_notifications()
    {
        // $this->load->model('M_notifikasi');
        $notifications = $this->M_notifikasi->pending_notification();
        echo json_encode($notifications);
    }

    // Untuk dashboard
    public function get_data_pending_notifications()
    {
        $jenis = $this->input->get('jenis');
        $menu_id = $this->input->get('menu_id');
        $data = $this->M_notifikasi->get_pending_details('', $jenis, '', $menu_id);
        echo json_encode($data);
    }
}
