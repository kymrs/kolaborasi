<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kps_karyawan extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_kps_karyawan');
        $this->M_login->getsecurity();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        ($akses->view_level == 'N' ? redirect('auth') : '');
        $data['add'] = $akses->add_level;
        $data['karyawan'] = $this->db->select('npk, nama_lengkap')->get('kps_karyawan')->result_array();
        $data['title'] = "backend/kps_karyawan/kps_karyawan_list";
        $data['titleview'] = "Data Karyawan";
        $this->load->view('backend/home', $data);
    }

    function get_list()
    {
        $status_kerja = $this->input->post('status_kerja'); // Ambil status dari permintaan POST
        if ($status_kerja !== '') {
            $this->db->where('status_kerja', $status_kerja);
        }
        $list = $this->M_kps_karyawan->get_datatables($status_kerja);
        $data = array();
        $no = $_POST['start'];

        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        $read = $akses->view_level;
        $edit = $akses->edit_level;
        $delete = $akses->delete_level;
        $print = $akses->print_level;

        foreach ($list as $field) {

            // MENENTUKAN ACTION APA YANG AKAN DITAMPILKAN DI LIST DATA TABLES
            $action_read = ($read == 'Y') ? '<a href="kps_karyawan/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>&nbsp;' : '';
            $action_edit = ($edit == 'Y') ? '<a href="kps_karyawan/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;' : '';
            $action_delete = ($delete == 'Y') ? '<a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;' : '';

            $action = $action_read . $action_edit . $action_delete;

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $action;
            $row[] = $field->npk;
            $row[] = $field->nama_lengkap;
            $row[] = $field->jenis_kelamin;
            $row[] = $field->tempat_lahir;
            $row[] = date('d-m-Y', strtotime($field->tgl_lahir));
            $row[] = $field->umur;
            $row[] = date('d-m-Y', strtotime($field->created_at));;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_kps_karyawan->count_all(),
            "recordsFiltered" => $this->M_kps_karyawan->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    function read_form($id)
    {
        $data['id_master'] = $id;
        $data['aksi'] = 'read';
        $data['master'] = $this->M_kps_karyawan->get_by_id($id);
        $data['keluarga'] = $this->db->get_where('kps_keluarga_karyawan', array('npk' => $data['master']->npk))->result();
        $data['kontrak'] = $this->db->get_where('kps_kontrak_pkwt', array('npk' => $data['master']->npk))->result();
        $data['title_view'] = "Data Karyawan";
        $data['title'] = 'backend/kps_karyawan/kps_karyawan_read_update';
        $this->load->view('backend/home', $data);
    }

    function add_form()
    {
        $data['id'] = 0;
        $data['title_view'] = "Karyawan Form";
        $data['title'] = 'backend/kps_karyawan/kps_karyawan_form';
        $this->load->view('backend/home', $data);
    }

    function add_form_keluarga()
    {
        $data['id'] = 0;
        $data['karyawan'] = $this->db->select('npk, nama_lengkap')->get('kps_karyawan')->result_array();
        $data['title_view'] = "Keluarga Karyawan Form";
        $data['title'] = 'backend/kps_karyawan/kps_keluarga_form';
        $this->load->view('backend/home', $data);
    }

    function edit_form($id)
    {
        $data['aksi'] = 'edit';
        $data['id_master'] = $id;
        $data['master'] = $this->M_kps_karyawan->get_by_id($id);
        $data['keluarga'] = $this->db->get_where('kps_keluarga_karyawan', array('npk' => $data['master']->npk))->result();
        $data['kontrak'] = $this->db->get_where('kps_kontrak_pkwt', array('npk' => $data['master']->npk))->result();
        $data['title_view'] = "Edit Data Karyawan";
        $data['title'] = 'backend/kps_karyawan/kps_karyawan_read_update';
        $this->load->view('backend/home', $data);
    }

    function edit_data($id)
    {
        $data['master'] = $this->M_kps_karyawan->get_by_id($id);
        echo json_encode($data);
    }

    function get_id($id)
    {
        $data = $this->M_kps_karyawan->get_by_id($id);
        echo json_encode($data);
    }

    public function add_data_karyawan()
    {
        $config['upload_path']   = './assets/backend/document/data_karyawan';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size']      = 3048;
        $config['encrypt_name']  = TRUE;

        $this->load->library('upload', $config);

        // Pastikan folder tujuan ada
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, TRUE);
        }

        $foto_name = null;

        if (!empty($_FILES['foto']['name'])) {
            if ($this->upload->do_upload('foto')) {
                $upload_data = $this->upload->data();
                $foto_name = $upload_data['file_name'];
            } else {
                echo json_encode([
                    "status" => FALSE,
                    "error" => $this->upload->display_errors()
                ]);
                return;
            }
        }

        $data = array(
            'status_kerja' => $this->input->post('status_kerja'),
            'npk' => $this->input->post('npk'),
            'foto' => $foto_name,
            'nama_lengkap' => $this->input->post('nama_lengkap'),
            'jenis_kelamin' => $this->input->post('jenis_kelamin'),
            'tempat_lahir' => $this->input->post('tempat_lahir'),
            'tgl_lahir' => date('Y-m-d', strtotime($this->input->post('tgl_lahir'))),
            'umur' => $this->input->post('umur'),
            'pendidikan' => $this->input->post('pendidikan'),
            'no_ktp' => $this->input->post('no_ktp'),
            'status_pernikahan' => $this->input->post('status_pernikahan'),
            'ktk' => $this->input->post('ktk'),
            'alamat_ktp' => $this->input->post('alamat_ktp'),
            'domisili' => $this->input->post('domisili'),
            'telp_klrga_serumah' => $this->input->post('telp_klrga_serumah'),
            'telp_klrga_tdk_serumah' => $this->input->post('telp_klrga_tdk_serumah'),
            'gol_darah' => $this->input->post('gol_darah'),
            'no_hp' => $this->input->post('no_hp'),
            'lokasi_kerja' => $this->input->post('lokasi_kerja'),
            'wilayah_kerja' => $this->input->post('wilayah_kerja'),
            'posisi' => $this->input->post('posisi'),
            'jabatan' => $this->input->post('jabatan'),
            'department' => $this->input->post('department'),
            'grade' => $this->input->post('grade'),
            'status_karyawan' => $this->input->post('status_karyawan'),
            'tgl_masuk' => date('Y-m-d', strtotime($this->input->post('tgl_masuk'))),
            'tgl_rekrut' => date('Y-m-d', strtotime($this->input->post('tgl_rekrut'))),
            'tgl_permanen' => date('Y-m-d', strtotime($this->input->post('tgl_permanen'))),
            'tgl_akhir_kontrak' => date('Y-m-d', strtotime($this->input->post('tgl_akhir_kontrak'))),
            'tgl_phk' => date('Y-m-d', strtotime($this->input->post('tgl_phk'))),
            'masa_kerja' => $this->input->post('masa_kerja'),
            'total_bulan' => $this->input->post('total_bulan'),
            'no_rek' => $this->input->post('no_rek'),
            'nama_pemilik_rek' => $this->input->post('nama_pemilik_rek'),
            'nama_bank' => $this->input->post('nama_bank'),
            'asal_karyawan' => $this->input->post('asal_karyawan'),
            'keahlian' => $this->input->post('keahlian'),
            'pelatihan_internal' => $this->input->post('pelatihan_internal'),
            'pelatihan_eksternal' => $this->input->post('pelatihan_eksternal'),
            'created_at'     => date('Y-m-d H:i:s')
        );

        $this->M_kps_karyawan->save($data);
        echo json_encode(array("status" => TRUE));
    }

    public function add_data_keluarga()
    {
        $data = array(
            'npk' => $this->input->post('npk'),
            'status_wp' => $this->input->post('status_wp'),
            'nama_anggota' => $this->input->post('nama_anggota'),
            'jenis_kelamin' => $this->input->post('jenis_kelamin'),
            'tgl_lahir' => date('Y-m-d', strtotime($this->input->post('tgl_lahir'))),
            'keanggotaan' => $this->input->post('keanggotaan'),
            'lokasi_kerja' => $this->input->post('lokasi_kerja'),
            'wilayah_kerja' => $this->input->post('wilayah_kerja'),
            'umur' => $this->input->post('umur'),
            'pendidikan' => $this->input->post('pendidikan'),
            'created_at' => date('Y-m-d H:i:s')
        );

        $this->db->insert('kps_keluarga_karyawan', $data);
        echo json_encode(array("status" => TRUE));
    }

    public function add_kontrak_karyawan()
    {
        $data = [
            'npk' => $this->input->post('npk'),
            'start' => date('Y-m-d', strtotime($this->input->post('start'))),
            'end' => date('Y-m-d', strtotime($this->input->post('end'))),
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->db->insert('kps_kontrak_pkwt', $data);
        echo json_encode(array("status" => TRUE));
    }

    public function update()
    {
        $npk = $this->input->post('npk');
        $id_master = $this->input->post('id_master');

        // Konfigurasi upload
        $config['upload_path']   = './assets/backend/document/data_karyawan';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size']      = 3048;
        $config['encrypt_name']  = TRUE;

        $this->load->library('upload', $config);

        // Pastikan folder tujuan ada
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, TRUE);
        }

        // Ambil data karyawan lama
        $karyawan_lama = $this->db->select('foto')->where('id', $id_master)->get('kps_karyawan')->row_array();
        $foto_name = $karyawan_lama['foto'];

        // Proses upload foto baru jika ada
        if (!empty($_FILES['foto']['name'])) {
            if ($this->upload->do_upload('foto')) {
                $upload_data = $this->upload->data();
                $foto_name = $upload_data['file_name'];

                // Hapus foto lama jika ada
                if (!empty($karyawan_lama['foto'])) {
                    $file_path = FCPATH . 'assets/backend/document/data_karyawan/' . $karyawan_lama['foto'];
                    if (file_exists($file_path)) {
                        unlink($file_path);
                    }
                }
            } else {
                echo json_encode([
                    "status" => FALSE,
                    "error" => $this->upload->display_errors()
                ]);
                return;
            }
        }

        $data = array(
            'status_kerja' => $this->input->post('status_kerja'),
            'npk' => $npk,
            'nama_lengkap' => $this->input->post('nama_lengkap'),
            'jenis_kelamin' => $this->input->post('jenis_kelamin'),
            'tempat_lahir' => $this->input->post('tempat_lahir'),
            'tgl_lahir' => date('Y-m-d', strtotime($this->input->post('tgl_lahir'))),
            'umur' => $this->input->post('umur'),
            'pendidikan' => $this->input->post('pendidikan'),
            'no_ktp' => $this->input->post('no_ktp'),
            'status_pernikahan' => $this->input->post('status_pernikahan'),
            'ktk' => $this->input->post('ktk'),
            'alamat_ktp' => $this->input->post('alamat_ktp'),
            'domisili' => $this->input->post('domisili'),
            'telp_klrga_serumah' => $this->input->post('telp_klrga_serumah'),
            'telp_klrga_tdk_serumah' => $this->input->post('telp_klrga_tdk_serumah'),
            'gol_darah' => $this->input->post('gol_darah'),
            'no_hp' => $this->input->post('no_hp'),
            'lokasi_kerja' => $this->input->post('lokasi_kerja'),
            'wilayah_kerja' => $this->input->post('wilayah_kerja'),
            'posisi' => $this->input->post('posisi'),
            'jabatan' => $this->input->post('jabatan'),
            'department' => $this->input->post('department'),
            'grade' => $this->input->post('grade'),
            'status_karyawan' => $this->input->post('status_karyawan'),
            'tgl_masuk' => date('Y-m-d', strtotime($this->input->post('tgl_masuk'))),
            'tgl_rekrut' => date('Y-m-d', strtotime($this->input->post('tgl_rekrut'))),
            'tgl_permanen' => date('Y-m-d', strtotime($this->input->post('tgl_permanen'))),
            'tgl_akhir_kontrak' => date('Y-m-d', strtotime($this->input->post('tgl_akhir_kontrak'))),
            'tgl_phk' => date('Y-m-d', strtotime($this->input->post('tgl_phk'))),
            'masa_kerja' => $this->input->post('masa_kerja'),
            'total_bulan' => $this->input->post('total_bulan'),
            'no_rek' => $this->input->post('no_rek'),
            'nama_pemilik_rek' => $this->input->post('nama_pemilik_rek'),
            'nama_bank' => $this->input->post('nama_bank'),
            'asal_karyawan' => $this->input->post('asal_karyawan'),
            'keahlian' => $this->input->post('keahlian'),
            'pelatihan_internal' => $this->input->post('pelatihan_internal'),
            'pelatihan_eksternal' => $this->input->post('pelatihan_eksternal'),
            'foto' => $foto_name,
            'updated_at' => date('Y-m-d H:i:s')
        );
        $this->db->where('id', $id_master);
        $this->db->update('kps_karyawan', $data);

        $id_kontrak = $this->input->post('id_kontrak');
        $start = $this->input->post('start');
        $end = $this->input->post('end');

        if (is_array($id_kontrak) && count($id_kontrak) > 0) {
            $data1 = [];
            foreach ($id_kontrak as $i => $val) {
                $data1[] = [
                    'id' => $id_kontrak[$i],
                    'npk' => $npk,
                    'start' => $start[$i],
                    'end' => $end[$i],
                    'updated_at' => date('Y-m-d H:i:s')
                ];
            }
            $this->db->update_batch('kps_kontrak_pkwt', $data1, 'id');
        }

        $id_keluarga = $this->input->post('id_keluarga');
        $nama_anggota = $this->input->post('nama_anggota');
        $status_wp = $this->input->post('status_wp');
        $jenis_kelamin_kel = $this->input->post('jenis_kelamin_kel');
        $tgl_lahir_kel = $this->input->post('tgl_lahir_kel');
        $umur_kel = $this->input->post('umur_kel');
        $pendidikan_kel = $this->input->post('pendidikan_kel');
        $keanggotaan = $this->input->post('keanggotaan');
        $lokasi_kerja_kel = $this->input->post('lokasi_kerja_kel');
        $wilayah_kerja_kel = $this->input->post('wilayah_kerja_kel');

        if (is_array($id_keluarga) && count($id_keluarga) > 0) {
            $data2 = [];
            foreach ($id_keluarga as $i => $val) {
                $data2[] = [
                    'id' => $id_keluarga[$i],
                    'npk' => $npk,
                    'status_wp' => $status_wp[$i],
                    'nama_anggota' => $nama_anggota[$i],
                    'jenis_kelamin' => $jenis_kelamin_kel[$i],
                    'tgl_lahir' => $tgl_lahir_kel[$i],
                    'umur' => $umur_kel[$i],
                    'pendidikan' => $pendidikan_kel[$i],
                    'keanggotaan' => $keanggotaan[$i],
                    'lokasi_kerja' => $lokasi_kerja_kel[$i],
                    'wilayah_kerja' => $wilayah_kerja_kel[$i],
                    'updated_at' => date('Y-m-d H:i:s')
                ];
            }
            $this->db->update_batch('kps_keluarga_karyawan', $data2, 'id');
        }

        echo json_encode(array("status" => TRUE));
    }

    function delete_data_karyawan($id)
    {
        // Ambil npk dan nama file foto
        $karyawan = $this->db->select('npk, foto')->where('id', $id)->get('kps_karyawan')->row_array();

        // Hapus file foto jika ada
        if (!empty($karyawan['foto'])) {
            $file_path = FCPATH . 'assets/backend/document/data_karyawan/' . $karyawan['foto'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }

        // Hapus data keluarga dan kontrak
        $this->db->delete('kps_keluarga_karyawan', ['npk' => $karyawan['npk']]);
        $this->db->delete('kps_kontrak_pkwt', ['npk' => $karyawan['npk']]);

        // Hapus data karyawan
        $this->M_kps_karyawan->delete($id);
        echo json_encode(array("status" => TRUE));
    }

    public function delete_kontrak()
    {
        $id = $this->input->post('id');
        if (!$id) {
            echo json_encode(['status' => false, 'message' => 'ID tidak ditemukan']);
            return;
        }

        $deleted = $this->M_kps_karyawan->delete_kontrak_by_id($id);

        if ($deleted) {
            echo json_encode(['status' => true]);
        } else {
            echo json_encode(['status' => false, 'message' => 'Gagal menghapus data']);
        }
    }

    public function delete_keluarga()
    {
        $id = $this->input->post('id');
        if (!$id) {
            echo json_encode(['status' => false, 'message' => 'ID tidak ditemukan']);
            return;
        }

        $deleted = $this->M_kps_karyawan->delete_keluarga_by_id($id);

        if ($deleted) {
            echo json_encode(['status' => true]);
        } else {
            echo json_encode(['status' => false, 'message' => 'Gagal menghapus data']);
        }
    }
}
