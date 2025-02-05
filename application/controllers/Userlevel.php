<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Userlevel extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_level');
        // $this->load->model('backend/M_notifikasi');
        $this->M_login->getsecurity();
    }

    function index()
    {
        // $data['notif'] = $this->M_notifikasi->pending_notification();
        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        ($akses->view_level == 'N' ? redirect('auth') : '');
        $data['add'] = $akses->add_level;
        $data['title'] = "backend/userlevel";
        $data['titleview'] = "Data User Level";
        $this->load->view('backend/home', $data);
    }

    function get_list()
    {
        $list = $this->M_level->get_datatables();
        $data = array();
        $no = $_POST['start'];
        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = ($akses->edit_level == 'Y' ? '<a class="btn btn-warning btn-circle btn-sm" title="Edit" onclick="edit_data(' . "'" . $field->id_level . "'" . ')"><i class="fa fa-edit"></i></a>&nbsp;' : '') .
                ($akses->delete_level == 'Y' ? '<a class="btn btn-danger btn-circle btn-sm" title="Delete" onclick="delete_data(' . "'" . $field->id_level . "'" . ')"><i class="fa fa-trash"></i></a>' : '');
            $row[] = $field->nama_level;
            $row[] = '<a class="btn btn-success btn-sm" title="Hak Akses" onclick="aksesmenu(' . "'" . $field->id_level . "'" . ')">&nbsp;Hak Akses</a>';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_level->count_all(),
            "recordsFiltered" => $this->M_level->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    function delete($id)
    {
        $this->M_level->delete_id($id);
        echo json_encode(array("status" => TRUE));
    }

    function add()
    {
        $data = array(
            'nama_level' => $this->input->post('level'),
        );
        $this->M_level->save($data);
        echo json_encode(array("status" => TRUE));
    }

    function update()
    {
        $data = array(
            'nama_level' => $this->input->post('level'),
        );
        $this->db->where('id_level', $this->input->post('id'));
        $this->db->update('tbl_userlevel', $data);
        echo json_encode(array("status" => TRUE));
    }

    function get_id($id)
    {
        $data = $this->M_level->get_by_id($id);
        echo json_encode($data);
    }

    //tambahan hak akses
    public function view_akses_menu()
    {
        $id = $this->input->post('id');
        $data['data_menu'] = $this->M_level->view_akses_menu($id)->result();
        $data['data_submenu'] = $this->M_level->akses_submenu($id)->result();
        $this->load->view('backend/view_akses_menu', $data);
    }

    public function update_akses()
    {
        $chek = $this->input->post('chek');
        $id = $this->input->post('id');
        if ($chek == 'checked') {
            $up = array(
                'view_level' => 'N'
            );
        } else {
            $up = array(
                'view_level' => 'Y'
            );
        }
        $this->M_level->update_aksesmenu($id, $up);
        echo json_encode(array("status" => TRUE));
    }

    public function view_akses()
    {
        $chek = $this->input->post('chek');
        $id = $this->input->post('id');
        if ($chek == 'checked') {
            $data = array(
                'view_level' => 'N'
            );
        } else {
            $data = array(
                'view_level' => 'Y'
            );
        }
        $this->M_level->update_akses_submenu($id, $data);
        echo json_encode(array("status" => TRUE));
    }

    public function add_akses()
    {
        $chek = $this->input->post('chek');
        $id = $this->input->post('id');
        var_dump($id);
        if ($chek == 'checked') {
            $data = array(
                'add_level' => 'N'
            );
        } else {
            $data = array(
                'add_level' => 'Y'
            );
        }
        $this->M_level->update_akses_submenu($id, $data);
        echo json_encode(array("status" => TRUE));
    }

    public function edit_akses()
    {
        $chek = $this->input->post('chek');
        $id = $this->input->post('id');
        var_dump($id);
        if ($chek == 'checked') {
            $data = array(
                'edit_level' => 'N'
            );
        } else {
            $data = array(
                'edit_level' => 'Y'
            );
        }
        $this->M_level->update_akses_submenu($id, $data);
        echo json_encode(array("status" => TRUE));
    }

    public function delete_akses()
    {
        $chek = $this->input->post('chek');
        $id = $this->input->post('id');
        var_dump($id);
        if ($chek == 'checked') {
            $data = array(
                'delete_level' => 'N'
            );
        } else {
            $data = array(
                'delete_level' => 'Y'
            );
        }
        $this->M_level->update_akses_submenu($id, $data);
        echo json_encode(array("status" => TRUE));
    }

    public function print_akses()
    {
        $chek = $this->input->post('chek');
        $id = $this->input->post('id');
        var_dump($id);
        if ($chek == 'checked') {
            $data = array(
                'print_level' => 'N'
            );
        } else {
            $data = array(
                'print_level' => 'Y'
            );
        }
        $this->M_level->update_akses_submenu($id, $data);
        echo json_encode(array("status" => TRUE));
    }

    public function upload_akses()
    {
        $chek = $this->input->post('chek');
        $id = $this->input->post('id');
        var_dump($id);
        if ($chek == 'checked') {
            $data = array(
                'upload_level' => 'N'
            );
        } else {
            $data = array(
                'upload_level' => 'Y'
            );
        }
        $this->M_level->update_akses_submenu($id, $data);
        echo json_encode(array("status" => TRUE));
    }

    function update_tbl_akses_menu()
    {
        $id = $this->input->post('id');
        //hapus id_menu di akses menu yang tidak ada di menu
        $get_old_menu = $this->M_level->get_old_akses_menu();

        if ($get_old_menu->num_rows() > 0) {
            foreach ($get_old_menu->result() as $row) {
                $this->M_level->del_old_akses_menu($row->id_menu);
            }
        }

        //hapus id_submenu di akses submenu yang tidak ada di submenu
        $get_old_submenu = $this->M_level->get_old_akses_submenu();
        if ($get_old_submenu->num_rows() > 0) {
            foreach ($get_old_submenu->result() as $row) {
                $this->M_level->del_old_akses_submenu($row->id_submenu);
            }
        }

        $old_menu = $this->M_level->get_akses_menu($id);
        $new_menu = $this->M_level->get_new_menu($id)->result();
        $menus = $this->M_level->getMenu();

        // jika id level sudah ada get menu
        if ($old_menu->num_rows() > 0) {
            foreach ($new_menu as $new) {

                $idmenu = intval($new->id_menu);
                $datamenu = array(
                    'id_level' => $id,
                    'id_menu' => $idmenu,
                    'view_level' => 'N',
                );
                $this->M_level->insert_akses_menu('tbl_akses_menu', $datamenu);
            }
        } else {
            // insert all menu;
            foreach ($menus->result() as $new) {
                $idmenu = intval($new->id_menu);
                $datamenu = array(
                    'id_level' => $id,
                    'id_menu' => $idmenu,
                    'view_level' => 'N',
                );
                $this->M_level->insert_akses_menu('tbl_akses_menu', $datamenu);
            }
        }

        $old_submenu = $this->M_level->get_akses_submenu($id)->result();
        //ambil data bila di tbl akses sub menu tidak ada ada di tbl sub menu
        $new_submenu = $this->M_level->get_new_submenu($id)->result();
        //cari id sub menu
        $sub_menus = $this->M_level->getsubMenu()->result();

        if ($old_submenu) {
            foreach ($new_submenu as $new) {
                $datasubmenu = array(
                    'id_level'  => $id,
                    'id_submenu'   => $new->id_submenu,
                    'view_level' => 'N',
                    'add_level'  => 'N',
                    'edit_level' => 'N',
                    'delete_level' => 'N',
                    'print_level'  => 'N',
                    'upload_level' => 'N'
                );
                $this->M_level->insert_akses_submenu('tbl_akses_submenu', $datasubmenu);
            }
        } else {
            foreach ($sub_menus as $submenu) {
                $datasubmenu = array(
                    'id'    => '',
                    'id_level'  => $id,
                    'id_submenu'   => $submenu->id_submenu,
                    'view_level' => 'N',
                    'add_level'  => 'N',
                    'edit_level' => 'N',
                    'delete_level' => 'N',
                    'print_level'  => 'N',
                    'upload_level' => 'N'
                );
                $this->M_level->insert_akses_submenu('tbl_akses_submenu', $datasubmenu);
            }
        }
    }
}
