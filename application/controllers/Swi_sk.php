<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Swi_sk extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_swi_sk');
        $this->M_login->getsecurity();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $data['title'] = "backend/swi_sk/swi_sk_list";
        $data['titleview'] = "Surat Keputusan Direksi";
        $this->load->view('backend/home', $data);
    }

    function get_list()
    {
        // INISIAI VARIABLE YANG DIBUTUHKAN
        $fullname = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $this->session->userdata('id_user'))
            ->get()
            ->row('name');
        $status = $this->input->post('status'); // Ambil status dari permintaan POST
        $list = $this->M_swi_sk->get_datatables($status);
        $data = array();
        $no = $_POST['start'];

        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        $read = $akses->view_level;
        $edit = $akses->edit_level;
        $delete = $akses->delete_level;
        $print = $akses->print_level;

        foreach ($list as $field) {

            // MENENTUKAN ACTION APA YANG AKAN DITAMPILKAN DI LIST DATA TABLES
            $action_edit = ($edit == 'Y') ? '<a class="btn btn-warning btn-circle btn-sm" title="Edit" onclick="edit_data(' . "'" . $field->id . "'" . ')"><i class="fa fa-edit"></i></a>&nbsp;' : '';
            $action_delete = ($delete == 'Y') ? '<a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;' : '';

            $action = $action_edit . $action_delete;

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $action;
            $row[] = $field->nama_file;
            $row[] = '<a href="' . base_url('assets/backend/document/sk/swi_sk/' . $field->file) . '" target="_blank">' . 'Lihat File' . '</a>';
            $row[] = $field->created_at;
            $row[] = $field->updated_at;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_swi_sk->count_all(),
            "recordsFiltered" => $this->M_swi_sk->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    function get_id($id)
    {
        $data = $this->M_swi_sk->get_by_id($id);
        echo json_encode($data);
    }

    public function add()
    {
        // Validasi file diupload
        if (empty($_FILES['file']['name'])) {
            echo json_encode(array("status" => FALSE, "message" => "File wajib diupload."));
            return;
        }

        $upload_path = FCPATH . 'assets/backend/document/sk/swi_sk/';

        // Buat folder jika belum ada
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, TRUE);
        }

        $config = array(
            'upload_path'   => $upload_path,
            'allowed_types' => 'pdf|jpg|jpeg|png',
            'max_size'      => 10240, // 10MB dalam KB
            'file_name'     => 'swi_sk_' . time(),
            'overwrite'     => FALSE,
        );

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('file')) {
            echo json_encode(array("status" => FALSE, "message" => $this->upload->display_errors('', '')));
            return;
        }

        $upload_data = $this->upload->data();
        $file_name   = $upload_data['file_name'];

        $data = array(
            'nama_file'  => $this->input->post('nama_file'),
            'file'       => $file_name,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        );

        $this->M_swi_sk->save($data);
        echo json_encode(array("status" => TRUE));
    }

    public function update()
    {
        $id = $this->input->post('id');

        // Ambil data lama untuk keperluan replace file
        $old_data  = $this->db->get_where('swi_sk', array('id' => $id))->row();
        $upload_path = FCPATH . 'assets/backend/document/sk/swi_sk/';

        // Buat folder jika belum ada
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, TRUE);
        }

        $data = array(
            'nama_file'  => $this->input->post('nama_file'),
            'updated_at' => date('Y-m-d H:i:s'),
        );

        // Jika ada file baru diupload
        if (!empty($_FILES['file']['name'])) {
            $config = array(
                'upload_path'   => $upload_path,
                'allowed_types' => 'pdf|jpg|jpeg|png',
                'max_size'      => 10240, // 10MB dalam KB
                'file_name'     => 'swi_sk_' . time(),
                'overwrite'     => FALSE,
            );

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('file')) {
                echo json_encode(array("status" => FALSE, "message" => $this->upload->display_errors('', '')));
                return;
            }

            $upload_data = $this->upload->data();
            $new_file    = $upload_data['file_name'];

            // Hapus file lama jika ada
            if (!empty($old_data->file)) {
                $old_file_path = $upload_path . $old_data->file;
                if (file_exists($old_file_path)) {
                    unlink($old_file_path);
                }
            }

            $data['file'] = $new_file;
        }

        $this->db->where('id', $id);
        $this->db->update('swi_sk', $data);
        echo json_encode(array("status" => TRUE));
    }

    public function delete($id)
    {
        $upload_path = FCPATH . 'assets/backend/document/sk/swi_sk/';

        // Ambil data sebelum dihapus
        $row = $this->db->get_where('swi_sk', array('id' => $id))->row();

        // Hapus file fisik jika ada
        if (!empty($row->file)) {
            $file_path = $upload_path . $row->file;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }

        $this->M_swi_sk->delete($id);
        echo json_encode(array("status" => TRUE));
    }
}
