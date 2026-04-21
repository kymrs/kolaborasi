<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Absensi extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('backend/M_absensi');
        // Asumsi id_user diambil dari session login
        // Jika belum ada session, kita hardcode dulu untuk demo
        if (!$this->session->userdata('id_user')) {
            $this->session->set_userdata('id_user', 1);
        }
    }

    public function index() {
        $id_user = $this->session->userdata('id_user');
        $data['absensi'] = $this->M_absensi->get_absensi_hari_ini($id_user);
        $this->load->view('backend/absensi', $data);
    }

    public function proses() {
        $type = $this->input->post('type');
        $lat = $this->input->post('latitude');
        $long = $this->input->post('longitude');
        $id_user = $this->session->userdata('id_user');
        $today = date('Y-m-d');
        $now = date('H:i:s');

        $existing = $this->M_absensi->get_absensi_hari_ini($id_user);

        if ($type == 'in') {
            if ($existing) {
                echo json_encode(['status' => 'error', 'msg' => 'Anda sudah check-in hari ini!']);
                return;
            }
            $data = [
                'id_user' => $id_user,
                'tanggal' => $today,
                'jam_masuk' => $now,
                'latitude_masuk' => $lat,
                'longitude_masuk' => $long
            ];
            $this->M_absensi->insert_check_in($data);
            echo json_encode(['status' => 'success', 'msg' => 'Check-in berhasil!']);
        } else {
            if (!$existing) {
                echo json_encode(['status' => 'error', 'msg' => 'Silahkan check-in terlebih dahulu!']);
                return;
            }
            if ($existing['jam_pulang'] != null) {
                echo json_encode(['status' => 'error', 'msg' => 'Anda sudah check-out hari ini!']);
                return;
            }
            $data = [
                'jam_pulang' => $now,
                'latitude_pulang' => $lat,
                'longitude_pulang' => $long
            ];
            $this->M_absensi->update_check_out($id_user, $data);
            echo json_encode(['status' => 'success', 'msg' => 'Check-out berhasil!']);
        }
    }
}