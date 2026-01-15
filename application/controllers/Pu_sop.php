<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pu_sop extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_pu_sop');
        $this->M_login->getsecurity();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $data['title'] = "backend/pu_sop/pu_sop_list";
        $data['titleview'] = "SOP";
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
        
        // Get parameters dari DataTables
        $start = isset($_POST['start']) ? $_POST['start'] : 0;
        $length = isset($_POST['length']) ? $_POST['length'] : 10;
        $draw = isset($_POST['draw']) ? $_POST['draw'] : 0;
        $search = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';
        $filter_jenis = isset($_POST['filter_jenis']) ? $_POST['filter_jenis'] : '';
        
        // Get order parameter dari DataTables
        $order_column = isset($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 2; // Default column 2 (no)
        $order_dir = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 'asc'; // Default asc
        
        // Mapping column index ke field name
        $columns = array(0 => 'id', 1 => 'id', 2 => 'no', 3 => 'jenis', 4 => 'kode', 5 => 'nama', 6 => 'file', 7 => 'created_at');
        $order_field = isset($columns[$order_column]) ? $columns[$order_column] : 'no';
        
        // Get semua data dengan hierarchy yang benar
        $list = $this->M_pu_sop->get_all_with_hierarchy();
        
        // Hitung total records sebelum filter
        $total_records = count($list);
        
        // Apply jenis filter jika ada
        if (!empty($filter_jenis)) {
            $list = array_filter($list, function($item) use ($filter_jenis) {
                return $item->jenis === $filter_jenis;
            });
            // Re-index array after filter
            $list = array_values($list);
        }
        
        // Apply search filter jika ada
        if (!empty($search)) {
            $list = array_filter($list, function($item) use ($search) {
                $search_lower = strtolower($search);
                return (
                    stripos($item->no, $search) !== false ||
                    stripos($item->nama, $search) !== false ||
                    stripos($item->kode, $search) !== false ||
                    stripos($item->jenis, $search) !== false
                );
            });
            // Re-index array after filter
            $list = array_values($list);
        }
        
        // Hitung total records setelah filter
        $records_filtered = count($list);
        
        // Apply sorting sebelum pagination
        usort($list, function($a, $b) use ($order_field, $order_dir) {
            $val_a = $a->$order_field;
            $val_b = $b->$order_field;
            
            // Handle numeric comparison untuk field 'no' yang mengandung angka dan dash
            if ($order_field === 'no') {
                // Convert 'no' to array of integers untuk numeric comparison
                $parts_a = explode('-', $val_a);
                $parts_b = explode('-', $val_b);
                
                // Convert ke integer untuk comparison
                $parts_a = array_map('intval', $parts_a);
                $parts_b = array_map('intval', $parts_b);
                
                // Compare arrays numerically
                for ($i = 0; $i < max(count($parts_a), count($parts_b)); $i++) {
                    $a_val = isset($parts_a[$i]) ? $parts_a[$i] : 0;
                    $b_val = isset($parts_b[$i]) ? $parts_b[$i] : 0;
                    
                    if ($a_val != $b_val) {
                        $cmp = $a_val - $b_val;
                        break;
                    }
                }
                
                if (!isset($cmp) || $cmp == 0) {
                    $cmp = 0;
                }
            } else {
                // String comparison untuk field lainnya
                $cmp = strcasecmp($val_a, $val_b);
            }
            
            return ($order_dir === 'desc') ? -$cmp : $cmp;
        });
        
        // Apply LIMIT untuk pagination
        $list_paginated = array_slice($list, $start, $length);
        
        $data = array();
        $no = $start + 1;

        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        $read = $akses->view_level;
        $edit = $akses->edit_level;
        $delete = $akses->delete_level;
        $print = $akses->print_level;

        foreach ($list_paginated as $field) {

            // MENENTUKAN ACTION APA YANG AKAN DITAMPILKAN DI LIST DATA TABLES
            $action_edit = ($edit == 'Y') ? '<a class="btn btn-warning btn-circle btn-sm" title="Edit" onclick="edit_data(' . "'" . $field->id . "'" . ')"><i class="fa fa-edit"></i></a>&nbsp;' : '';
            $action_delete = ($delete == 'Y') ? '<a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>' : '';

            $action = $action_edit . $action_delete;

            // Format tampilan jenis dengan indentasi berdasarkan level hierarki
            $indent = '';
            if ($field->parent_id != null) {
                $level = substr_count($field->no, '-') + 1;
                $indent = str_repeat('&nbsp;&nbsp;', $level);
            }
            
            // Tambahkan prefix jenis untuk clarity
            $jenis_display = $field->jenis;
            if ($field->parent_id != null) {
                $jenis_display = '└─ ' . $field->jenis;
            }

            $row = array();
            $row[] = $no;
            $row[] = $action;
            $row[] = $field->no;
            $row[] = $indent . $jenis_display;
            $row[] = $field->kode;
            $row[] = $field->nama;
            $row[] = $field->file;
            $row[] = $this->format_date_indo($field->created_at);
            $data[] = $row;
            
            $no++;
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $total_records,
            "recordsFiltered" => $records_filtered,
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    function read_form($id)
    {
        $data['master'] = $this->M_pu_sop->get_by_id($id);
        $data['title_view'] = "Data SOP";
        $data['title'] = 'backend/pu_sop/sop_read_pu';
        $this->load->view('backend/home', $data);
    }

    function add_form()
    {
        $data['id'] = 0;
        $data['parent_options'] = array(); // Default kosong, akan diisi via AJAX
        $data['title_view'] = "SOP Form";
        $data['title'] = 'backend/pu_sop/sop_form_pu';
        $this->load->view('backend/home', $data);
    }

    function edit_form($id)
    {
        $data['master'] = $this->M_pu_sop->get_by_id($id);
        $data['title_view'] = "Edit Data SOP";
        $data['title'] = 'backend/pu_sop/sop_form_pu';
        $this->load->view('backend/home', $data);
    }

    function get_id($id)
    {
        $data = $this->M_pu_sop->get_by_id($id);
        echo json_encode($data);
    }

    public function add()
    {
        // Load upload library
        $this->load->library('upload');

        // Config upload
        $config['upload_path'] = './assets/backend/document/pu_sop';
        $config['allowed_types'] = 'jpg|jpeg|png|pdf';
        $config['max_size'] = 3072; // 3MB in KB
        $config['encrypt_name'] = TRUE; // To avoid file name conflicts

        $this->upload->initialize($config);

        $file_name = '';
        if (!empty($_FILES['file']['name'])) {
            if ($this->upload->do_upload('file')) {
                $upload_data = $this->upload->data();
                $file_name = $upload_data['file_name'];
            } else {
                // Upload failed
                $error = $this->upload->display_errors();
                echo json_encode(array("status" => FALSE, "message" => $error));
                return;
            }
        }

        // Get jenis from POST
        $jenis = $this->input->post('jenis');
        $parent_no = $this->input->post('parent_no'); // Ambil parent_no (nomor hierarki)

        // Convert parent_no ke parent_id
        $parent_id = null;
        if ($parent_no && $parent_no != '') {
            $parent_data = $this->M_pu_sop->get_by_no($parent_no);
            if ($parent_data) {
                $parent_id = $parent_data->id;
            }
        }

        // Get kode from manual input
        $kode = $this->input->post('kode');

        // Generate nomor hierarki
        $no = $this->M_pu_sop->generate_no_hierarki($jenis, $parent_id);

        if ($no === null && $jenis != 'SOP') {
            // Validasi parent untuk Juklak dan Juknis
            echo json_encode(array("status" => FALSE, "message" => "Parent tidak valid untuk jenis " . $jenis));
            return;
        }

        $data = array(
            'jenis' => $jenis,
            'kode' => $kode,
            'no' => $no,
            'parent_id' => ($parent_id && $parent_id != '') ? $parent_id : NULL,
            'nama' => $this->input->post('nama'),
            'file' => $file_name,
            'created_at' => date('Y-m-d H:i:s')
        );

        $this->M_pu_sop->save($data);
        echo json_encode(array("status" => TRUE));
    }

    public function update()
    {
        $id = $this->input->post('id');

        // Get old data to check old file
        $old_data = $this->M_pu_sop->get_by_id($id);
        $old_file = $old_data->file;

        // Load upload library
        $this->load->library('upload');

        // Config upload
        $config['upload_path'] = './assets/backend/document/pu_sop';
        $config['allowed_types'] = 'jpg|jpeg|png|pdf';
        $config['max_size'] = 3072; // 3MB in KB
        $config['encrypt_name'] = TRUE; // To avoid file name conflicts

        $this->upload->initialize($config);

        $file_name = $old_file; // Default to old file
        if (!empty($_FILES['file']['name'])) {
            if ($this->upload->do_upload('file')) {
                $upload_data = $this->upload->data();
                $file_name = $upload_data['file_name'];

                // Delete old file if exists
                if (!empty($old_file) && file_exists('assets/backend/document/pu_sop/' . $old_file)) {
                    unlink('assets/backend/document/pu_sop/' . $old_file);
                }
            } else {
                // Upload failed
                $error = $this->upload->display_errors();
                echo json_encode(array("status" => FALSE, "message" => $error));
                return;
            }
        }

        $jenis = $this->input->post('jenis');
        $parent_no = $this->input->post('parent_no');
        $parent_id = null;
        $no = $old_data->no; // Default ke nomor lama

        // Jika ada parent_no, cari parent dan generate nomor baru
        if (!empty($parent_no)) {
            $parent_data = $this->M_pu_sop->get_by_no($parent_no);
            if ($parent_data) {
                $parent_id = $parent_data->id;
                // Generate nomor hierarki baru berdasarkan parent baru
                $no = $this->M_pu_sop->generate_no_hierarki($jenis, $parent_id);
            }
        }

        $data = array(
            'jenis' => $jenis,
            'nama' => $this->input->post('nama'),
            'no' => $no,
            'parent_id' => ($parent_id && $parent_id != '') ? $parent_id : NULL,
            'file' => $file_name
        );

        $this->M_pu_sop->update($id, $data);
        echo json_encode(array("status" => TRUE));
    }

    /**
     * Generate nomor hierarki berdasarkan jenis dan parent
     * Digunakan saat user mengubah parent di form edit
     */
    public function generate_no_from_parent()
    {
        $jenis = $this->input->post('jenis');
        $parent_no = $this->input->post('parent_no');
        
        if (!$jenis || !$parent_no) {
            echo json_encode(array("status" => FALSE, "message" => "Jenis dan parent_no harus ada"));
            return;
        }
        
        // Get parent ID dari parent_no
        $parent_data = $this->M_pu_sop->get_by_no($parent_no);
        
        if (!$parent_data) {
            echo json_encode(array("status" => FALSE, "message" => "Parent tidak ditemukan"));
            return;
        }
        
        $parent_id = $parent_data->id;
        
        // Generate nomor hierarki
        $no = $this->M_pu_sop->generate_no_hierarki($jenis, $parent_id);
        
        if ($no === null) {
            echo json_encode(array("status" => FALSE, "message" => "Gagal generate nomor"));
            return;
        }
        
        echo json_encode(array("status" => TRUE, "no" => $no));
    }

    /**
     * Get parent options berdasarkan jenis yang dipilih
     * Digunakan untuk AJAX call saat user memilih jenis di form
     */
    public function get_parent_options()
    {
        $jenis = $this->input->post('jenis');
        
        if (!$jenis) {
            echo json_encode(array("status" => FALSE, "message" => "Jenis tidak ditemukan"));
            return;
        }

        $parent_options = $this->M_pu_sop->get_parent_options($jenis);
        
        echo json_encode(array(
            "status" => TRUE,
            "data" => $parent_options
        ));
    }


    private function format_date_indo($date_str) {
        $bulan = array(
            1 => 'Januari',
            2 => 'Februari', 
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        );
        
        $date = new DateTime($date_str);
        $hari = $date->format('d');
        $bulan_num = (int)$date->format('m');
        $tahun = $date->format('Y');
        $jam = $date->format('H:i:s');
        
        return $hari . ' ' . $bulan[$bulan_num] . ' ' . $tahun . ' ' . $jam;
    }
}