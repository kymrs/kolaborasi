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

    public function e_pkwt()
    {
        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        ($akses->view_level == 'N' ? redirect('auth') : '');
        $data['add'] = $akses->add_level;
        $data['title'] = "backend/kps_karyawan/kps_pkwt_list";
        $data['titleview'] = "Data Karyawan → Data E-PKWT";
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
            $row[] = ucfirst($field->jenis_kelamin);
            $row[] = $field->tempat_lahir;
            $row[] = date('d-m-Y', strtotime($field->tgl_lahir));
            $row[] = $field->umur;
            $row[] = date('H:i:s d-m-Y', strtotime($field->created_at));;

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

    function get_list2()
    {
        function tanggal_indo($tanggal)
        {
            $bulan = [
                1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];

            $pecah = explode('-', date('Y-m-d', strtotime($tanggal)));
            $tahun = $pecah[0];
            $bulan_angka = (int)$pecah[1];
            $hari = $pecah[2];

            return $hari . ' ' . $bulan[$bulan_angka] . ' ' . $tahun;
        }

        $status_approve = $this->input->post('status_approve');
        if ($status_approve !== null && $status_approve !== '') {
            if ($status_approve == 'on-process') {
                $this->db->where('app_status', 'waiting');
            } else {
                $this->db->where('app_status', $status_approve);
            }
        }

        $list = $this->M_kps_karyawan->get_datatables2();
        $data = array();
        $no = $_POST['start'];

        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        $read = $akses->view_level;
        $edit = $akses->edit_level;
        $delete = $akses->delete_level;
        $print = $akses->print_level;

        foreach ($list as $field) {

            // MENENTUKAN ACTION APA YANG AKAN DITAMPILKAN DI LIST DATA TABLES
            $action_read = ($read == 'Y') ? '<a href="read_form_pkwt/' . $field->id_pkwt . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>&nbsp;' : '';
            $action_edit = ($edit == 'Y') ? '<a href="edit_form_pkwt/' . $field->id_pkwt . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;' : '';
            $action_delete = ($delete == 'Y') ? '<a onclick="delete_data(' . "'" . $field->id_pkwt . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;' : '';
            $action_print = ($print == 'Y') ? '<a class="btn btn-success btn-circle btn-sm" target="_blank" href="e_pkwt_pdf/' . $field->id_pkwt . '"><i class="fas fa-file-pdf"></i></a>' : '';

            $action = $action_read . $action_edit . $action_delete . $action_print;

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $action;
            $row[] = $field->npk;
            $row[] = $field->nama_lengkap;
            $row[] = ucfirst($field->jenis_kelamin);
            $row[] = tanggal_indo($field->tgl_lahir);
            $row[] = tanggal_indo($field->jk_awal);
            $row[] = tanggal_indo($field->jk_akhir);
            $row[] = date('H:i:s d-m-Y', strtotime($field->created_at_pkwt));

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
        $data['keluarga'] = $this->db->get_where('kps_keluarga_karyawan', ['npk' => $data['master']->npk])->result();
        $data['kontrak'] = $this->db->get_where('kps_kontrak_pkwt', ['npk' => $data['master']->npk, 'app_status' => 'approved'])->result();
        $data['title_view'] = "Data Karyawan";
        $data['title'] = 'backend/kps_karyawan/kps_karyawan_read';
        $this->load->view('backend/home', $data);
    }

    function read_form_pkwt($id)
    {
        $data['id_master'] = $id;
        $data['aksi'] = 'read';
        $data['transaksi'] = $this->M_kps_karyawan->get_by_id2($id);
        $data['master'] = $this->db->get_where('kps_karyawan', array('npk' => $data['transaksi']->npk))->row();
        $data['title_view'] = "Data E-PKWT Karyawan";
        $data['title'] = 'backend/kps_karyawan/e_pkwt_read_form';
        $this->load->view('backend/home', $data);
    }

    function add_form()
    {
        $data['id'] = 0;
        $data['title_view'] = "Karyawan Form";
        $data['title'] = 'backend/kps_karyawan/kps_karyawan_form';
        $this->load->view('backend/home', $data);
    }

    function add_form_pkwt()
    {
        $data['id'] = 0;
        $data['aksi'] = 'add';
        $data['title_view'] = "E-PKWT Form";
        $data['karyawan'] = $this->db->select('npk, nama_lengkap')->get('kps_karyawan')->result_array();
        $data['title'] = 'backend/kps_karyawan/kps_pkwt_form';
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
        $data['title'] = 'backend/kps_karyawan/kps_karyawan_update';
        $this->load->view('backend/home', $data);
    }

    function edit_form_pkwt($id)
    {
        $data['aksi'] = 'edit';
        $data['id'] = $id;
        $data['karyawan'] = $this->db->select('npk, nama_lengkap')->get('kps_karyawan')->result_array();
        $data['transaksi'] = $this->M_kps_karyawan->get_by_id2($id);
        $data['title_view'] = "Edit Data E-PKWT";
        $data['title'] = 'backend/kps_karyawan/kps_pkwt_form';
        $this->load->view('backend/home', $data);
    }

    function edit_data($id)
    {
        $data['master'] = $this->M_kps_karyawan->get_by_id($id);
        echo json_encode($data);
    }

    function edit_data2($id)
    {
        $data['transaksi'] = $this->M_kps_karyawan->get_by_id2($id);
        echo json_encode($data);
    }

    function get_id($id)
    {
        $data = $this->M_kps_karyawan->get_by_id($id);
        echo json_encode($data);
    }

    public function generate_no_perjanjian()
    {
        $npk = $this->input->post('npk');
        $jk_awal = $this->input->post('jk_awal');
        $jk_akhir = $this->input->post('jk_akhir');

        if (!$npk || !$jk_awal || !$jk_akhir) {
            echo json_encode(['status' => false, 'message' => 'Data tidak lengkap']);
            return;
        }

        // Hitung urutan dokumen (ambil max urutan di tahun ini, +1)
        $tahun = date('Y', strtotime($jk_awal));
        $this->db->select('MAX(SUBSTRING_INDEX(no_perjanjian, "/", 1)) as max_urut');
        $this->db->like('no_perjanjian', "/$tahun", 'before');
        $max = $this->db->get('kps_kontrak_pkwt')->row();
        $urut = str_pad((int)$max->max_urut + 1, 3, '0', STR_PAD_LEFT);

        $perusahaan = 'PKWT-KPS';

        // Kontrak ke berapa untuk karyawan ini
        $this->db->where('npk', $npk);
        $kontrak_ke = $this->db->count_all_results('kps_kontrak_pkwt') + 1;
        $kontrak_ke_romawi = $this->to_roman($kontrak_ke);

        // Hitung masa kontrak (bulan)
        $date1 = new DateTime($jk_awal);
        $date2 = new DateTime($jk_akhir);
        $interval = $date1->diff($date2);
        $masa_kontrak = $interval->m + ($interval->y * 12);
        $masa_kontrak_romawi = $this->to_roman($masa_kontrak);

        // Bulan & tahun dokumen
        $bulan_romawi = $this->to_roman(date('n', strtotime($jk_awal)));
        $tahun_dok = date('Y', strtotime($jk_awal));

        // Format: 001/PKWT-KPS/I-III/III/2024
        $no_perjanjian = "{$urut}/{$perusahaan}/{$kontrak_ke_romawi}-{$masa_kontrak_romawi}/{$bulan_romawi}/{$tahun_dok}";

        echo json_encode(['status' => true, 'no_perjanjian' => $no_perjanjian]);
    }

    // Helper untuk angka ke romawi
    private function to_roman($num)
    {
        $map = [
            'M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400,
            'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40,
            'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1
        ];
        $returnValue = '';
        while ($num > 0) {
            foreach ($map as $roman => $int) {
                if ($num >= $int) {
                    $num -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
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

        $tgl_phk = null;
        if (strtolower($this->input->post('status_kerja')) !== 'aktif') {
            $tgl_phk = date('Y-m-d', strtotime($this->input->post('tgl_phk')));
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
            'unit_bisnis' => $this->input->post('unit_bisnis'),
            'posisi' => $this->input->post('posisi'),
            'jabatan' => $this->input->post('jabatan'),
            'department' => $this->input->post('department'),
            'grade' => $this->input->post('grade'),
            'status_karyawan' => $this->input->post('status_karyawan'),
            'tgl_masuk' => date('Y-m-d', strtotime($this->input->post('tgl_masuk'))),
            'tgl_rekrut' => date('Y-m-d', strtotime($this->input->post('tgl_rekrut'))),
            'tgl_permanen' => date('Y-m-d', strtotime($this->input->post('tgl_permanen'))),
            'tgl_akhir_kontrak' => date('Y-m-d', strtotime($this->input->post('tgl_akhir_kontrak'))),
            'tgl_phk' => $tgl_phk,
            'masa_kerja' => $this->input->post('masa_kerja'),
            'total_bulan' => $this->input->post('total_bulan'),
            'no_rek' => $this->input->post('no_rek'),
            'nama_pemilik_rek' => $this->input->post('nama_pemilik_rek'),
            'nama_bank' => $this->input->post('nama_bank'),
            'asal_karyawan' => $this->input->post('asal_karyawan'),
            'keahlian' => $this->input->post('keahlian'),
            'pelatihan_internal' => $this->input->post('pelatihan_internal'),
            'pelatihan_eksternal' => $this->input->post('pelatihan_eksternal'),
            'created_at' => date('Y-m-d H:i:s')
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
            'no_perjanjian' => $this->input->post('no_perjanjian'),
            'npk' => $this->input->post('npk'),
            'jk_awal' => date('Y-m-d', strtotime($this->input->post('jk_awal'))),
            'jk_akhir' => date('Y-m-d', strtotime($this->input->post('jk_akhir'))),
            'hari' => $this->input->post('hari'),
            'bulan' => $this->input->post('bulan'),
            'tahun' => $this->input->post('tahun'),
            'jangka_waktu' => $this->input->post('jangka_waktu'),
            'gaji' => preg_replace('/\D/', '', $this->input->post('gaji')),
            'tj_pulsa' => preg_replace('/\D/', '', $this->input->post('tj_pulsa')),
            'tj_ops' => preg_replace('/\D/', '', $this->input->post('tj_ops')),
            'thr' => $this->input->post('thr'),
            'tj_kehadiran' => preg_replace('/\D/', '', $this->input->post('tj_kehadiran')),
            'insentif' => $this->input->post('insentif'),
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

        $tgl_phk = null;
        if (strtolower($this->input->post('status_kerja')) !== 'aktif') {
            $tgl_phk = date('Y-m-d', strtotime($this->input->post('tgl_phk')));
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
            'unit_bisnis' => $this->input->post('unit_bisnis'),
            'posisi' => $this->input->post('posisi'),
            'jabatan' => $this->input->post('jabatan'),
            'department' => $this->input->post('department'),
            'grade' => $this->input->post('grade'),
            'status_karyawan' => $this->input->post('status_karyawan'),
            'tgl_masuk' => date('Y-m-d', strtotime($this->input->post('tgl_masuk'))),
            'tgl_rekrut' => date('Y-m-d', strtotime($this->input->post('tgl_rekrut'))),
            'tgl_permanen' => date('Y-m-d', strtotime($this->input->post('tgl_permanen'))),
            'tgl_akhir_kontrak' => date('Y-m-d', strtotime($this->input->post('tgl_akhir_kontrak'))),
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
            'tgl_phk' => $tgl_phk,
            'updated_at' => date('Y-m-d H:i:s')
        );

        $this->db->where('id', $id_master);
        $this->db->update('kps_karyawan', $data);

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

    public function update_kontrak_karyawan($id)
    {
        $data = [
            'hari' => $this->input->post('hari'),
            'bulan' => $this->input->post('bulan'),
            'tahun' => $this->input->post('tahun'),
            'jangka_waktu' => $this->input->post('jangka_waktu'),
            'gaji' => preg_replace('/\D/', '', $this->input->post('gaji')),
            'tj_pulsa' => preg_replace('/\D/', '', $this->input->post('tj_pulsa')),
            'tj_ops' => preg_replace('/\D/', '', $this->input->post('tj_ops')),
            'thr' => $this->input->post('thr'),
            'tj_kehadiran' => preg_replace('/\D/', '', $this->input->post('tj_kehadiran')),
            'insentif' => $this->input->post('insentif'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->db->where('id', $id);
        $this->db->update('kps_kontrak_pkwt', $data);

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

    public function delete_data_pkwt($id)
    {
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

    public function e_pkwt_pdf($id)
    {
        // Load Dompdf
        $this->load->library('Pdf');
        $dompdf = new Dompdf\Dompdf();
        $dompdf->setPaper('A4', 'portrait');

        // Render view ke string
        $data['transaksi'] = $this->M_kps_karyawan->get_by_id2($id);
        $data['master'] = $this->db->get_where('kps_karyawan', array('npk' => $data['transaksi']->npk))->row();
        $html = $this->load->view('backend/kps_karyawan/e_pkwt_pdf', $data, true);

        $dompdf->loadHtml($html);
        $dompdf->render();
        $dompdf->stream("contoh_dompdf.pdf", array("Attachment" => false));
    }

    public function approve_pkwt()
    {
        $id = $this->input->post('id');
        $app_status = $this->input->post('app_status');

        if (!$id || !$app_status) {
            echo json_encode(['status' => false, 'message' => 'Data tidak lengkap']);
            return;
        }

        $this->db->where('id', $id);
        $update = $this->db->update('kps_kontrak_pkwt', [
            'app_status' => $app_status,
            'app_date' => date('Y-m-d H:i:s')
        ]);

        if ($update) {
            echo json_encode(['status' => true]);
        } else {
            echo json_encode(['status' => false, 'message' => 'Gagal update status']);
        }
    }
}
