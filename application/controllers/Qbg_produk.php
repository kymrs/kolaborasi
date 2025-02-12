<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Qbg_produk extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_qbg_produk');
        $this->M_login->getsecurity();
        $this->load->helper('date_helper');
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $data['title'] = "backend/qbg_produk/qbg_produk_list";
        $data['titleview'] = "Data Produk";
        $this->load->view('backend/home', $data);
    }

    function get_list()
    {
        $list = $this->M_qbg_produk->get_datatables();
        $data = array();
        $no = $_POST['start'];

        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        $edit = $akses->edit_level;
        $delete = $akses->delete_level;

        foreach ($list as $field) {

            // MENENTUKAN ACTION APA YANG AKAN DITAMPILKAN DI LIST DATA TABLES
            $action_stok = '<a class="btn btn-primary btn-circle btn-sm" title="Stok" onclick="edit_stok(' . "'" . $field->kode_produk . "'" . ')"><i class="fa fa-cart-plus"></i></a>&nbsp;';
            $action_edit = ($edit == 'Y') ? '<a class="btn btn-warning btn-circle btn-sm" title="Edit" onclick="edit_data(' . "'" . $field->id . "'" . ')"><i class="fa fa-edit"></i></a>&nbsp;' : '';
            $action_delete = ($delete == 'Y') ? '<a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;' : '';

            $action = $action_stok . $action_edit . $action_delete;

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $action;
            $row[] = $field->kode_produk;
            $row[] = $field->nama_produk;
            $row[] = $field->berat;
            $row[] = ucwords($field->satuan);
            $query = $this->db->select('stok_akhir')->where('kode_produk', $field->kode_produk)->get('qbg_stok')->row();
            $stok_akhir = $query->stok_akhir;
            $row[] = $stok_akhir;
            $row[] = 'Rp. ' . number_format($field->harga_qubagift, 0, ',', '.');;
            $row[] = 'Rp. ' . number_format($field->harga_reseller, 0, ',', '.');;
            $row[] = 'Rp. ' . number_format($field->harga_distributor, 0, ',', '.');;
            $row[] = date("d-m-Y | H:i:s", strtotime($field->created_at));
            $row[] = $field->updated_at ? date("d-m-Y | H:i:s", strtotime($field->updated_at)) : '-';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_qbg_produk->count_all(),
            "recordsFiltered" => $this->M_qbg_produk->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    // function read_form($id)
    // {
    //     $data['master'] = $this->M_qbg_produk->get_by_id($id);
    //     $data['title_view'] = "Data Layanan";
    //     $data['title'] = 'backend/qbg_produk/layanan_read_pu';
    //     $this->load->view('backend/home', $data);
    // }

    // function add_form()
    // {
    //     $data['id'] = 0;
    //     $data['title_view'] = "Layanan Form";
    //     $data['title'] = 'backend/qbg_produk/layanan_form_pu';
    //     $this->load->view('backend/home', $data);
    // }

    // function edit_form($id)
    // {
    //     $data['master'] = $this->M_qbg_produk->get_by_id($id);
    //     $data['title_view'] = "Edit Data Layanan";
    //     $data['title'] = 'backend/qbg_produk/layanan_form_pu';
    //     $this->load->view('backend/home', $data);
    // }

    function get_id($id)
    {
        $data = $this->M_qbg_produk->get_by_id($id);
        echo json_encode($data);
    }

    function get_stok_kode($kode)
    {
        $stok = $this->db->get_where('qbg_stok', ['kode_produk' => $kode])->row();
        $produk = $this->db->get_where('qbg_produk', ['kode_produk' => $kode])->row();

        $data = [
            'stok' => $stok,
            'produk' => $produk
        ];

        echo json_encode($data);
    }

    public function add()
    {
        // Mendapatkan kode produk terakhir
        $kode_produk = $this->M_qbg_produk->max_kode();

        if ($kode_produk == null) {
            // Jika tidak ada kode produk, berarti ini adalah data pertama
            $kode_produk = 'Q001';
        } else {
            // Ambil angka dari kode produk terakhir, misalnya Q001 -> 1
            $last_number = (int) substr($kode_produk, 1);
            $next_number = $last_number + 1;
            $kode_produk = 'Q' . str_pad($next_number, 3, '0', STR_PAD_LEFT);
        }

        $data = array(
            'kode_produk' => $kode_produk,
            'nama_produk' => $this->input->post('nama_produk'),
            'berat' => $this->input->post('berat'),
            'satuan' => $this->input->post('satuan'),
            'harga_qubagift' => $this->input->post('harga_qubagift'),
            'harga_reseller' => $this->input->post('harga_reseller'),
            'harga_distributor' => $this->input->post('harga_distributor')
        );

        $this->M_qbg_produk->save($data);

        $data2 = [
            'kode_produk' => $kode_produk,
            'stok_awal' => $this->input->post('stok'),
            'stok_akhir' => $this->input->post('stok')
        ];

        $this->db->insert('qbg_stok', $data2);

        echo json_encode(array("status" => TRUE));
    }

    public function update()
    {
        $data = array(
            'nama_produk' => $this->input->post('nama_produk'),
            'berat' => $this->input->post('berat'),
            'satuan' => $this->input->post('satuan'),
            'harga_qubagift' => $this->input->post('harga_qubagift'),
            'harga_reseller' => $this->input->post('harga_reseller'),
            'harga_distributor' => $this->input->post('harga_distributor'),
            'updated_at' => date('Y-m-d H:i:s')
        );

        $this->db->where('id', $this->input->post('id'));
        $this->db->update('qbg_produk', $data);

        if ($this->input->post('tambah_stok')) {
            $kode_produk = $this->input->post('kode_produk');
            $stok_baru = $this->input->post('tambah_stok');

            $query = $this->db->select('stok_akhir')
                ->where('kode_produk', $kode_produk) // Perbaikan where
                ->get('qbg_stok')
                ->row();

            if ($query) {
                $stok_akhir = $query->stok_akhir; // Akses sebagai objek
                $total = $stok_akhir + $stok_baru;

                // Update stok
                $this->db->update('qbg_stok', ['stok_akhir' => $total], ['kode_produk' => $kode_produk]);
            }
        } else if ($this->input->post('kurangi_stok')) {
            var_dump('kurang');
        }

        echo json_encode(array("status" => TRUE));
    }

    function delete($id)
    {
        $this->M_qbg_produk->delete($id);
        echo json_encode(array("status" => TRUE));
    }
}
