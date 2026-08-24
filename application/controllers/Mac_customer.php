<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mac_customer extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_mac_customer');
        $this->M_login->getsecurity();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        if (empty($this->session->userdata('cabang_id'))) {
            echo "
            <script>
                alert('Cabang belum diatur. Silakan hubungi Admin untuk melanjutkan.');
                window.location.href = '" . site_url('dashboard') . "';
            </script>";
            exit;
        }

        $data['title']     = "backend/mac_customer/mac_customer_list";
        $data['titleview'] = "Data Customer";
        $this->load->view('backend/home', $data);
    }

    // ========== DATATABLES LIST ==========
    function get_list()
    {
        $list = $this->M_mac_customer->get_datatables();
        $data = array();
        $no   = $_POST['start'];

        $akses  = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        $edit   = $akses->edit_level;
        $delete = $akses->delete_level;

        foreach ($list as $field) {
            $action_edit   = ($edit == 'Y')   ? '<a class="btn btn-warning btn-circle btn-sm" title="Edit" onclick="edit_data(' . "'" . $field->id . "'" . ')"><i class="fa fa-edit"></i></a>&nbsp;' : '';
            $action_delete = ($delete == 'Y') ? '<a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;' : '';

            $action = $action_edit . $action_delete;

            $no++;
            $row   = array();
            $row[] = $no;
            $row[] = $action;
            $row[] = $field->customer_name;
            $row[] = $field->type_customer;
            $row[] = $field->address;
            $row[] = date('d-m-Y H:i:s', strtotime($field->created_at));
            $data[] = $row;
        }

        $output = array(
            "draw"            => $_POST['draw'],
            "recordsTotal"    => $this->M_mac_customer->count_all(),
            "recordsFiltered" => $this->M_mac_customer->count_filtered(),
            "data"            => $data,
        );
        echo json_encode($output);
    }

    // ========== GET BY ID ==========
    function get_id($id)
    {
        $data = $this->M_mac_customer->get_by_id($id);
        echo json_encode($data);
    }

    // ========== ADD ==========
    public function add()
    {
        $title         = $this->input->post('title');
        $customer_name = $this->input->post('customer_name');

        if ($title === 'Ibu' || $title === 'Bapak') {
            $full_name = $title . ' ' . $customer_name;
        } else {
            $full_name =  $title . '. ' . $customer_name;
        }

        $data = array(
            'type_customer' => $this->input->post('type_customer'),
            'customer_name' => $full_name,
            'address' => ucwords(strtolower($this->input->post('address'))),
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        );

        $this->M_mac_customer->save($data);
        echo json_encode(array("status" => TRUE, "message" => "Data saved successfully"));
    }

    // ========== UPDATE ==========
    public function update()
    {
        $title         = $this->input->post('title');
        $customer_name = $this->input->post('customer_name');

        $full_name = ($title && $title !== '-')
            ? $title . '. ' . $customer_name
            : $customer_name;

        $data = array(
            'type_customer' => $this->input->post('type_customer'),
            'customer_name' => $full_name,
            'address' => ucwords(strtolower($this->input->post('address'))),
            'updated_at'    => date('Y-m-d H:i:s'),
        );

        $this->db->where('id', $this->input->post('id'));
        $this->db->update('mac_customer', $data);
        echo json_encode(array("status" => TRUE, "message" => "Data updated successfully"));
    }

    // ========== DELETE ==========
    function delete($id)
    {
        $this->M_mac_customer->delete($id);
        echo json_encode(array("status" => TRUE));
    }
}