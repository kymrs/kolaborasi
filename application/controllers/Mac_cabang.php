<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mac_cabang extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_mac_cabang');
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

        $data['title']     = 'backend/mac_cabang/mac_cabang_list';
        $data['titleview'] = 'Master Cabang';
        $this->load->view('backend/home', $data);
    }

    public function get_list()
    {
        $list = $this->M_mac_cabang->get_datatables();
        $data = [];
        $no   = $_POST['start'];

        $akses  = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        $edit   = $akses->edit_level;
        $delete = $akses->delete_level;

        foreach ($list as $field) {
            $action_edit   = ($edit   == 'Y')
                ? '<a onclick="open_modal(' . $field->id . ')" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;'
                : '';
            $action_delete = ($delete == 'Y')
                ? '<a onclick="delete_data(\'' . $field->id . '\')" class="btn btn-danger btn-circle btn-sm" title="Hapus"><i class="fa fa-trash"></i></a>&nbsp;'
                : '';

            $badge = $field->status === 'aktif'
                ? '<span class="badge badge-success">Aktif</span>'
                : '<span class="badge badge-secondary">Nonaktif</span>';

            $no++;
            $row   = [];
            $row[] = $no;
            $row[] = $action_edit . $action_delete;
            $row[] = strtoupper($field->kode);
            $row[] = $field->nama_cabang;
            $row[] = $field->no_telp  ?: '-';
            $row[] = $field->alamat  ?: '-';
            $row[] = $badge;
            $row[] = date('d-m-Y', strtotime($field->created_at));
            $data[] = $row;
        }

        echo json_encode([
            "draw"            => $_POST['draw'],
            "recordsTotal"    => $this->M_mac_cabang->count_all(),
            "recordsFiltered" => $this->M_mac_cabang->count_filtered(),
            "data"            => $data,
        ]);
    }

    public function get_data($id)
    {
        echo json_encode($this->M_mac_cabang->get_by_id($id) ?: []);
    }

    // Untuk select2 / dropdown di form lain
    public function get_options()
    {
        $data = $this->M_mac_cabang->get_all_aktif();
        echo json_encode($data);
    }

    public function add()
    {
        $kode        = strtoupper(trim($this->input->post('kode')));
        $nama_cabang = trim($this->input->post('nama_cabang'));

        if (empty($kode)) {
            echo json_encode(['status' => FALSE, 'error' => 'Kode cabang wajib diisi.']); return;
        }
        if (empty($nama_cabang)) {
            echo json_encode(['status' => FALSE, 'error' => 'Nama cabang wajib diisi.']); return;
        }
        if ($this->M_mac_cabang->cek_kode($kode)) {
            echo json_encode(['status' => FALSE, 'error' => 'Kode ' . $kode . ' sudah digunakan.']); return;
        }

        $id = $this->M_mac_cabang->save([
            'kode'        => $kode,
            'nama_cabang' => $nama_cabang,
            'alamat'      => $this->input->post('alamat')  ?: null,
            'no_telp'     => $this->input->post('no_telp') ?: null,
            'status'      => $this->input->post('status')  ?: 'aktif',
            'created_at'  => date('Y-m-d H:i:s'),
        ]);

        echo $id
            ? json_encode(['status' => TRUE,  'message' => 'Cabang berhasil disimpan'])
            : json_encode(['status' => FALSE, 'error'   => 'Gagal menyimpan data']);
    }

    public function update()
    {
        $id          = intval($this->input->post('id'));
        $kode        = strtoupper(trim($this->input->post('kode')));
        $nama_cabang = trim($this->input->post('nama_cabang'));

        if (empty($kode)) {
            echo json_encode(['status' => FALSE, 'error' => 'Kode cabang wajib diisi.']); return;
        }
        if (empty($nama_cabang)) {
            echo json_encode(['status' => FALSE, 'error' => 'Nama cabang wajib diisi.']); return;
        }
        if ($this->M_mac_cabang->cek_kode($kode, $id)) {
            echo json_encode(['status' => FALSE, 'error' => 'Kode ' . $kode . ' sudah digunakan.']); return;
        }

        $this->M_mac_cabang->update_cabang($id, [
            'kode'        => $kode,
            'nama_cabang' => $nama_cabang,
            'alamat'      => $this->input->post('alamat')  ?: null,
            'no_telp'     => $this->input->post('no_telp') ?: null,
            'status'      => $this->input->post('status')  ?: 'aktif',
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);

        echo json_encode(['status' => TRUE, 'message' => 'Cabang berhasil diupdate']);
    }

    public function delete($id)
    {
        $result = $this->M_mac_cabang->delete_cabang($id);
        if (!$result) {
            echo json_encode(['status' => FALSE, 'error' => 'Cabang tidak dapat dihapus karena sudah digunakan di data stok.']);
            return;
        }
        echo json_encode(['status' => TRUE]);
    }
}