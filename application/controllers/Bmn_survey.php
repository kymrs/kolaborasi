<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bmn_survey extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_bmn_survey');
        // $this->M_login->getsecurity();
        date_default_timezone_set('Asia/Jakarta');

        // Header untuk CORS
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");

        // Tangani preflight request OPTIONS
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            header("HTTP/1.1 200 OK");
            exit;
        }
    }

    public function index()
    {
        $this->M_login->getsecurity();
        $data['title'] = "backend/bmn_survey/survey_list_bmn";
        $data['titleview'] = "Data Survey Customer";
        $this->load->view('backend/home', $data);
    }

    function get_list()
    {
        $list = $this->M_bmn_survey->get_datatables();
        $data = array();
        $no = $_POST['start'];

        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        $read = $akses->view_level;

        function bulanIndonesia($tanggal)
        {
            $bulan = array(
                '01' => 'Januari',
                '02' => 'Februari',
                '03' => 'Maret',
                '04' => 'April',
                '05' => 'Mei',
                '06' => 'Juni',
                '07' => 'Juli',
                '08' => 'Agustus',
                '09' => 'September',
                '10' => 'Oktober',
                '11' => 'November',
                '12' => 'Desember'
            );

            return $bulan[date('m', strtotime($tanggal))];
        }


        foreach ($list as $field) {

            // MENENTUKAN ACTION APA YANG AKAN DITAMPILKAN DI LIST DATA TABLES

            $action_read = ($read == 'Y') ? '<a href="bmn_survey/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>' : '';

            $action = $action_read;

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<div style="text-align: center;">' . $action . '</div>';
            $row[] = $field->nama;
            $row[] = date('d ', strtotime($field->tgl_pernikahan)) . bulanIndonesia($field->tgl_pernikahan) . date(' Y', strtotime($field->tgl_pernikahan));
            $row[] = $field->no_hp;
            $row[] = date('d ', strtotime($field->created_at)) . bulanIndonesia($field->created_at) . date(' Y', strtotime($field->created_at));
            $row[] = $field->created_at;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_bmn_survey->count_all(),
            "recordsFiltered" => $this->M_bmn_survey->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    function read_form($id)
    {
        $this->M_login->getsecurity();
        $data['survey'] = $this->M_bmn_survey->get_by_id($id);
        $data['title_view'] = "Data Survey Customer";
        $data['title'] = 'backend/bmn_survey/survey_read_bmn';
        $this->load->view('backend/home', $data);
    }

    function get_id($id)
    {
        $data = $this->M_bmn_survey->get_by_id($id);
        echo json_encode($data);
    }

    public function add()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");

        $data = array(
            'nama' => ucwords(strtolower($this->input->post('nama'))),
            'tgl_pernikahan' => date('Y-m-d', strtotime($this->input->post('tgl_pernikahan'))),
            'no_hp' => $this->input->post('no_hp'),
            'q1' => $this->input->post('q1'),
            'q2' => $this->input->post('q2'),
            'q3' => $this->input->post('q3'),
            'q4' => $this->input->post('q4'),
            'q5' => $this->input->post('q5'),
            'q6' => $this->input->post('q6'),
            'q7' => $this->input->post('q7'),
            'q8' => $this->input->post('q8'),
            'q9' => $this->input->post('q9'),
            'q10' => $this->input->post('q10'),
            'kritik_saran' => $this->input->post('kritik_saran'),
            'created_at' => date('Y-m-d H:i:s')
        );

        $this->db->insert('bmn_survey', $data);
        // $this->M_bmn_survey->save($data);
        echo json_encode(array("status" => TRUE));
    }

    function delete($id)
    {
        $this->M_bmn_survey->delete($id);
        echo json_encode(array("status" => TRUE));
    }
}
