<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pu_data_agen extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_pu_data_agen');
        date_default_timezone_set('Asia/Jakarta');

        $allowed_origin = 'https://agen.pengenumroh.com';
        $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';

        if ($origin === $allowed_origin) {
            header("Access-Control-Allow-Origin: $origin");
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
            header("Access-Control-Allow-Headers: Content-Type, Authorization");
        }

        // Tangani preflight OPTIONS
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            header("HTTP/1.1 200 OK");
            exit;
        }
    }

    public function index()
    {
        $this->M_login->getsecurity();
        $data['title'] = "backend/pu_data_agen/pu_data_agen_list";
        $data['titleview'] = "Data Agen";
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
        $list = $this->M_pu_data_agen->get_datatables($status);
        $data = array();
        $no = $_POST['start'];

        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        $edit = $akses->edit_level;
        $delete = $akses->delete_level;

        foreach ($list as $field) {
            // MENENTUKAN ACTION APA YANG AKAN DITAMPILKAN DI LIST DATA TABLES
            $action_edit = ($edit == 'Y') ? '<a class="btn btn-warning btn-circle btn-sm" title="Edit" onclick="edit_data(' . "'" . $field->id . "'" . ')"><i class="fa fa-edit"></i></a>&nbsp;' : '';
            $action_delete = ($delete == 'Y') ? '<a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;' : '';
            // Cek apakah ada insentif dengan payment_status = 2 (pending) untuk agen ini
            $has_pending = $this->db->where('no_telp', $field->no_telp)
                ->where('payment_status', 2)
                ->count_all_results('pu_insentif_agen') > 0;

            $notif_dot = $has_pending
                ? '<span style="position:absolute;top:-2px;right:-2px;width:10px;height:10px;background:red;border-radius:50%;display:inline-block;z-index:10;"></span>'
                : '';

            $action_insentif = '<span style="position:relative;display:inline-block;">'
                . '<button type="button" class="btn btn-primary btn-circle btn-sm" title="Insentif Agen" onclick="showInsentifModal(\'' . $field->no_telp . '\', \'' . htmlspecialchars($field->nama, ENT_QUOTES) . '\')"><i class="fa fa-coins"></i></button>'
                . $notif_dot
                . '</span>&nbsp;';

            $action = $action_edit . $action_delete . $action_insentif;

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $action;
            $row[] = '<button type="button" class="btn btn-sm status-modal-trigger ' .
                ($field->is_active == 1 ? 'btn-outline-success' : 'btn-outline-danger') . '" 
                data-id="' . $field->id . '" 
                data-nama="' . htmlspecialchars($field->nama) . '" 
                data-status="' . $field->is_active . '" 
                data-referral="' . (isset($field->kode_referral) ? htmlspecialchars($field->kode_referral) : '') . '">'
                . ($field->is_active == 1 ? '<i class="fa fa-check-circle"></i> Aktif' : '<i class="fa fa-times-circle"></i> Tidak Aktif')
                . '</button>';
            $row[] = $field->nama;
            $row[] = $field->no_telp;
            $row[] = $field->kode_referral ? $field->kode_referral : '-';
            // $row[] = '<button type="button" class="btn btn-outline-primary btn-sm edit-saldo-trigger" 
            //     data-id="' . $field->id . '" 
            //     data-saldo="' . $field->saldo . '"
            //     style="font-weight:bold;letter-spacing:1px;display:inline-flex;align-items:center;">
            //     <i class="fa fa-money-bill-wave mr-1"></i> Rp ' . number_format($field->saldo, 0, ',', '.') . '
            // </button>';
            $row[] = number_format($field->saldo, 0, ',', '.');
            $row[] = $field->alamat;
            $row[] = '<a href="#" class="lihat-ktp" data-img="' . base_url('assets/backend/document/pu_data_agen/' . $field->ktp) . '">Lihat KTP</a>';
            $row[] = $field->created_at;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_pu_data_agen->count_all(),
            "recordsFiltered" => $this->M_pu_data_agen->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    function add_form()
    {
        $this->M_login->getsecurity();
        $data['id'] = 0;
        $data['title_view'] = "Hotel Form";
        $data['title'] = 'backend/pu_data_agen/hotel_form_pu';
        $this->load->view('backend/home', $data);
    }

    // function edit_form($id)
    // {
    //     $data['master'] = $this->M_pu_data_agen->get_by_id($id);
    //     $data['title_view'] = "Edit Data Hotel";
    //     $data['title'] = 'backend/pu_data_agen/hotel_form_pu';
    //     $this->load->view('backend/home', $data);
    // }

    function get_id($id)
    {
        $data = $this->M_pu_data_agen->get_by_id($id);
        echo json_encode($data);
    }

    public function add()
    {
        // Konfigurasi upload
        $config['upload_path']   = './assets/backend/document/pu_data_agen/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size']      = 2048; // 2MB
        $config['encrypt_name']  = TRUE;

        $this->load->library('upload', $config);

        $ktp_file = null;
        if (!empty($_FILES['ktp']['name'])) {
            if (!$this->upload->do_upload('ktp')) {
                echo json_encode([
                    "status" => FALSE,
                    "error" => $this->upload->display_errors('', '')
                ]);
                return;
            }
            $upload_data = $this->upload->data();
            $ktp_file = $upload_data['file_name'];
        }

        $data = array(
            'nama'      => $this->input->post('nama_agen'),
            'no_telp'   => $this->input->post('no_telp'),
            'kode_referral'   => $this->input->post('kode_referral'),
            'alamat'    => $this->input->post('alamat'),
            'kelurahan' => $this->input->post('kelurahan'),
            'kota' => $this->input->post('kota'),
            'provinsi'  => $this->input->post('provinsi'),
            'ktp'       => $ktp_file,
            'password'  => md5($this->input->post('password')),
            'created_at' => date('Y-m-d H:i:s')
        );

        $this->M_pu_data_agen->save($data);
        echo json_encode(array("status" => TRUE));
    }

    public function update()
    {
        $id = $this->input->post('id');
        $agen = $this->M_pu_data_agen->get_by_id($id);
        $ktp_file = $agen->ktp;

        // Cek jika ada file KTP baru diupload
        if (!empty($_FILES['ktp']['name'])) {
            $config['upload_path']   = './assets/backend/document/pu_data_agen/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size']      = 2048; // 2MB
            $config['encrypt_name']  = TRUE;

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('ktp')) {
                // Hapus file lama jika ada
                if ($ktp_file && file_exists($config['upload_path'] . $ktp_file)) {
                    @unlink($config['upload_path'] . $ktp_file);
                }
                $upload_data = $this->upload->data();
                $ktp_file = $upload_data['file_name'];
            } else {
                echo json_encode([
                    "status" => FALSE,
                    "error" => $this->upload->display_errors('', '')
                ]);
                return;
            }
        }

        $data = array(
            'nama'      => $this->input->post('nama_agen'),
            'no_telp'   => $this->input->post('no_telp'),
            'kode_referral'   => $this->input->post('kode_referral'),
            'alamat'    => $this->input->post('alamat'),
            'kelurahan' => $this->input->post('kelurahan'),
            'kota' => $this->input->post('kota'),
            'password'  => md5($this->input->post('password')),
            'provinsi'  => $this->input->post('provinsi'), 
            'ktp'       => $ktp_file,
            'created_at' => $agen->created_at // atau update field lain sesuai kebutuhan
        );

        $this->db->where('id', $id);
        $this->db->update('pu_data_agen', $data);
        echo json_encode(array("status" => TRUE));
    }

    public function update_status()
    {
        $id = $this->input->post('id');
        $kode_referral = $this->input->post('kode_referral');
        $is_active = $this->input->post('is_active');

        $data = [
            'kode_referral' => $kode_referral,
            'is_active' => $is_active
        ];
        $this->db->where('id', $id);
        $this->db->update('pu_data_agen', $data);

        echo json_encode(['status' => true, 'message' => 'Status berhasil diupdate']);
    }

    public function update_saldo()
    {
        $id = $this->input->post('id');
        $saldo = preg_replace('/\D/', '', $this->input->post('saldo'));
        $this->db->where('id', $id);
        $this->db->update('pu_data_agen', ['saldo' => $saldo]);
        echo json_encode(['status' => true, 'message' => 'Saldo berhasil diupdate']);
    }

    function delete($id)
    {
        // Ambil data agen untuk mendapatkan no_telp dan nama file KTP
        $agen = $this->M_pu_data_agen->get_by_id($id);
        if ($agen) {
            // Hapus file KTP jika ada
            if ($agen->ktp) {
                $file_path = './assets/backend/document/pu_data_agen/' . $agen->ktp;
                if (file_exists($file_path)) {
                    @unlink($file_path);
                }
            }

            // Hapus data insentif berdasarkan no_telp
            $this->db->where('no_telp', $agen->no_telp);
            $this->db->delete('pu_insentif_agen');

            // Hapus data agen
            $this->M_pu_data_agen->delete($id);

            echo json_encode(array("status" => TRUE));
        } else {
            echo json_encode(array("status" => FALSE, "message" => "Data agen tidak ditemukan"));
        }
    }

    public function api_data_agen()
    {
        if (isset($_SERVER['HTTP_ORIGIN']) && $_SERVER['HTTP_ORIGIN'] === 'https://agen.pengenumroh.com') {
            header("Access-Control-Allow-Origin: https://agen.pengenumroh.com");
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
            header("Access-Control-Allow-Headers: Content-Type, Authorization");
        }

        // Handle preflight request
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            http_response_code(200);
            exit();
        }

        // Ambil data dari tabel pu_data_agen
        $data = $this->db->get('pu_data_agen')->result();

        // Kirim data dalam format JSON
        echo json_encode([
            "status" => true,
            "data" => $data
        ]);
    }

    public function api_tambah_agen()
    {
        if (isset($_SERVER['HTTP_ORIGIN']) && $_SERVER['HTTP_ORIGIN'] === 'https://agen.pengenumroh.com') {
            header("Access-Control-Allow-Origin: https://agen.pengenumroh.com");
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
            header("Access-Control-Allow-Headers: Content-Type, Authorization");
        }

        // // Validasi method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['status' => false, 'message' => 'Invalid request method']);
            return;
        }

        // Ambil data POST
        $nama     = $this->input->post('nama', TRUE);
        $no_telp  = $this->input->post('no_telp', TRUE);
        $alamat   = $this->input->post('alamat', TRUE);
        $alamat   = $this->input->post('alamat', TRUE);
        $kelurahan   = $this->input->post('kelurahan', TRUE);
        $kota   = $this->input->post('kota', TRUE);
        $provinsi   = $this->input->post('provinsi', TRUE);
        $password = $this->input->post('password', TRUE);

        // Validasi data
        if (!$nama || !$no_telp || !$alamat || !$password || !isset($_FILES['foto_ktp'])) {
            echo json_encode(['status' => false, 'message' => 'Data tidak lengkap']);
            return;
        }

        // Upload foto KTP
        $config['upload_path']   = './assets/backend/document/pu_data_agen/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size']      = 2048; // 2MB
        $config['encrypt_name']  = TRUE;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('foto_ktp')) {
            echo json_encode(['status' => false, 'message' => $this->upload->display_errors('', '')]);
            return;
        }

        $upload_data = $this->upload->data();
        $ktp_path = $upload_data['file_name'];

        // Hash password sebelum simpan (pakai MD5)
        $password_hash = md5($password);

        // Simpan ke database
        $data = [
            'nama'      => $nama,
            'no_telp'   => $no_telp,
            'alamat'    => $alamat,
            'kelurahan'    => $kelurahan,
            'provinsi'    => $provinsi,
            'kota'    => $kota,
            'password'  => $password_hash, // simpan hash MD5
            'ktp'       => $ktp_path,
            'is_active' => 0,
            'created_at' =>  date('Y-m-d H:i:s')
        ];

        $insert = $this->db->insert('pu_data_agen', $data);

        if ($insert) {
            echo json_encode(['status' => true, 'message' => 'Agen berhasil didaftarkan']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Gagal menyimpan data agen']);
        }
    }

    public function getSaldoAgen($id)
    {
        // CORS untuk domain tertentu
        if (isset($_SERVER['HTTP_ORIGIN']) && $_SERVER['HTTP_ORIGIN'] === 'https://agen.pengenumroh.com') {
            header("Access-Control-Allow-Origin: https://agen.pengenumroh.com");
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
            header("Access-Control-Allow-Headers: Content-Type, Authorization");
        }

        // Handle preflight OPTIONS
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit();
        }

        // Ambil data agen berdasarkan ID
        $agen = $this->M_pu_data_agen->get_by_id($id);

        if ($agen) {
            echo json_encode([
                'status' => true,
                'saldo' => $agen->saldo
            ]);
        } else {
            echo json_encode([
                'status' => false,
                'message' => 'Agen tidak ditemukan'
            ]);
        }
    }

    public function get_insentif_agen()
    {
        $no_telp = $this->input->post('no_telp');
        $draw = intval($this->input->post('draw'));
        $start = intval($this->input->post('start'));
        $length = intval($this->input->post('length'));

        // Ambil data agen berdasarkan no_telp
        $agen = $this->db->where('no_telp', $no_telp)->get('pu_data_agen')->row();
        if (!$agen) {
            echo json_encode([
                "draw" => $draw,
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => []
            ]);
            return;
        }

        // Query total records
        $this->db->where('no_telp', $no_telp);
        $recordsTotal = $this->db->count_all_results('pu_insentif_agen');

        // Query filtered records
        $this->db->where('no_telp', $no_telp);
        if (!empty($this->input->post('search')['value'])) {
            $search = $this->input->post('search')['value'];
            $this->db->group_start()
                ->like('jenis_transaksi', $search)
                ->or_like('nominal', $search)
                ->or_like('created_at', $search)
                ->group_end();
        }
        $recordsFiltered = $this->db->count_all_results('pu_insentif_agen');

        // Query data paginated
        $this->db->where('no_telp', $no_telp);
        if (!empty($this->input->post('search')['value'])) {
            $search = $this->input->post('search')['value'];
            $this->db->group_start()
                ->like('jenis_transaksi', $search)
                ->or_like('nominal', $search)
                ->or_like('created_at', $search)
                ->group_end();
        }
        $this->db->limit($length, $start);
        $this->db->order_by('created_at', 'DESC');
        $list = $this->db->get('pu_insentif_agen')->result();

        $data = [];
        $no = $start + 1;
        foreach ($list as $row) {
            if ($row->jenis_transaksi == 'pencairan') {
                if ($row->payment_status == 1) {
                    $btn_status = '<div class="text-center"><button type="button" class="btn btn-sm btn-success payment-status-trigger" data-id="' . $row->id . '" data-status="1">Sudah Dibayar</button></div>';
                } elseif ($row->payment_status == 0) {
                    $btn_status = '<div class="text-center"><button type="button" class="btn btn-sm btn-danger" disabled>Dibatalkan</button></div>';
                } else {
                    $btn_status = '<div class="text-center"><button type="button" class="btn btn-sm btn-warning payment-status-trigger" data-id="' . $row->id . '" data-status="2">Belum Dibayar</button></div>';
                }
            } else {
                // Hanya tombol insentif pada baris insentif terbaru (paling atas) yang bisa diklik
                static $first_insentif = true;
                if ($first_insentif) {
                    $btn_status = '<div class="text-center">
                        <button type="button" class="btn btn-sm btn-primary btnTambahInsentif"
                            data-id="' . $row->id . '"
                            data-no_telp="' . $row->no_telp . '"
                            data-nama_agen="' . htmlspecialchars($agen->nama, ENT_QUOTES) . '"
                            data-nominal="' . $row->nominal . '"
                            data-deskripsi="' . htmlspecialchars($row->description, ENT_QUOTES) . '"
                        >Insentif</button>
                    </div>';
                    $first_insentif = false;
                } else {
                    $btn_status = '<div class="text-center">
                        <button type="button" class="btn btn-sm btn-secondary" disabled>Insentif</button>
                    </div>';
                }
            }

            $data[] = [
                'no' => $no++,
                'payment_status' => $btn_status,
                'nama_agen' => $agen->nama,
                'no_rek' => $row->no_rek,
                'nama_bank' => $row->nama_bank,
                'nama_pemilik_rek' => $row->nama_pemilik_rek,
                'jenis_transaksi' => ucfirst($row->jenis_transaksi),
                'nominal' => 'Rp ' . number_format($row->nominal, 0, ',', '.'),
                'description' => $row->description ? $row->description : '-',
                'dibuat_pada' => $row->created_at
            ];
        }

        echo json_encode([
            "draw" => $draw,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data
        ]);
    }

    public function update_payment_status()
    {
        $id = $this->input->post('id');
        $payment_status = $this->input->post('payment_status');

        $insentif = $this->db->where('id', $id)->get('pu_insentif_agen')->row();

        if (!$insentif) {
            echo json_encode(['status' => false, 'message' => 'Data tidak ditemukan']);
            return;
        }

        if ($payment_status == 0 && $insentif->jenis_transaksi == 'pencairan') {
            $agen = $this->db->where('no_telp', $insentif->no_telp)->get('pu_data_agen')->row();
            if ($agen) {
                $this->db->where('no_telp', $insentif->no_telp);
                $this->db->set('saldo', 'saldo + ' . (int)$insentif->nominal, FALSE);
                $this->db->update('pu_data_agen');
            }
        }

        // Update status pembayaran
        $this->db->where('id', $id);
        $this->db->update('pu_insentif_agen', ['payment_status' => $payment_status]);
        echo json_encode(['status' => true, 'message' => 'Status pembayaran berhasil diupdate']);
    }

    public function api_tambah_pencairan()
    {
        // // CORS untuk domain tertentu
        if (isset($_SERVER['HTTP_ORIGIN']) && $_SERVER['HTTP_ORIGIN'] === 'https://agen.pengenumroh.com') {
            header("Access-Control-Allow-Origin: https://agen.pengenumroh.com");
            header("Access-Control-Allow-Methods: POST, OPTIONS");
            header("Access-Control-Allow-Headers: Content-Type, Authorization");
        }

        // Handle preflight OPTIONS
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit();
        }

        // Validasi method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['status' => false, 'message' => 'Invalid request method']);
            return;
        }

        // Ambil data POST
        $no_telp = $this->input->post('no_telp', TRUE);
        $nik = $this->input->post('sip', TRUE);
        $nominal = $this->input->post('nominal', TRUE);
        $no_rek = $this->input->post('no_rek', TRUE);
        $nama_bank = $this->input->post('nama_bank', TRUE);
        $nama_pemilik_rek = $this->input->post('nama_pemilik_rek', TRUE);

        // Validasi data
        if (!$no_telp || !$nominal || !$no_rek || !$nama_bank || !$nama_pemilik_rek) {
            echo json_encode(['status' => false, 'message' => 'Data tidak lengkap']);
            return;
        }

        // Cari id agen berdasarkan no_telp
        $agen = $this->db->where('no_telp', $no_telp)->get('pu_data_agen')->row();
        if (!$agen) {
            echo json_encode(['status' => false, 'message' => 'Agen tidak ditemukan']);
            return;
        }

        // Simpan ke tabel pu_insentif_agen
        $data = [
            'no_telp' => $no_telp,
            'no_rek' => $no_rek,
            'nama_bank' => $nama_bank,
            'nama_pemilik_rek' => $nama_pemilik_rek,
            'jenis_transaksi' => 'pencairan',
            'nominal' => $nominal,
            'description' => "Menarik saldo sebesar Rp " . number_format($nominal, 0, ',', '.'),
            'payment_status' => 2,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $insert = $this->db->insert('pu_insentif_agen', $data);

        if ($insert) {
            // Kurangi saldo agen
            $saldo_baru = $agen->saldo - $nominal;
            if ($saldo_baru < 0) $saldo_baru = 0; // Tidak boleh minus

            $this->db->where('no_telp', $no_telp);
            $this->db->update('pu_data_agen', ['saldo' => $saldo_baru]);

            echo json_encode(['status' => true, 'message' => 'Data pencairan berhasil ditambahkan']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Gagal menyimpan data pencairan']);
        }
    }

    public function tambah_insentif_agen()
    {
        // Ambil data dari POST
        $no_telp = $this->input->post('no_telp', TRUE);
        $nama_agen = $this->input->post('nama_agen', TRUE);
        $nominal = preg_replace('/\D/', '', $this->input->post('nominal', TRUE));
        $description = $this->input->post('deskripsi', TRUE);

        // Validasi data
        if (!$no_telp || !$nama_agen || !$nominal || !$description) {
            echo json_encode(['status' => false, 'message' => 'Data tidak lengkap']);
            return;
        }

        // Cari agen berdasarkan no_telp
        $agen = $this->db->where('no_telp', $no_telp)->get('pu_data_agen')->row();
        if (!$agen) {
            echo json_encode(['status' => false, 'message' => 'Agen tidak ditemukan']);
            return;
        }

        // Insert ke pu_insentif_agen
        $data = [
            'no_telp' => $no_telp,
            'nominal' => $nominal,
            'description' => $description,
            'jenis_transaksi' => 'insentif',
            'payment_status' => 3, // 3 untuk insentif
            'created_at' => date('Y-m-d H:i:s')
        ];
        $insert = $this->db->insert('pu_insentif_agen', $data);

        if ($insert) {
            // Tambahkan saldo ke pu_data_agen
            $this->db->where('no_telp', $no_telp);
            $this->db->set('saldo', 'saldo + ' . (int)$nominal, FALSE);
            $this->db->update('pu_data_agen');

            echo json_encode(['status' => true, 'message' => 'Insentif berhasil ditambahkan']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Gagal menambah insentif']);
        }
    }

    public function update_insentif_agen()
    {
        $id = $this->input->post('id', TRUE);
        $no_telp = $this->input->post('no_telp', TRUE);
        $nama_agen = $this->input->post('nama_agen', TRUE);
        $nominal_baru = preg_replace('/\D/', '', $this->input->post('nominal', TRUE));
        $description = $this->input->post('deskripsi', TRUE);

        // Validasi data
        if (!$id || !$no_telp || !$nama_agen || !$nominal_baru || !$description) {
            echo json_encode(['status' => false, 'message' => 'Data tidak lengkap']);
            return;
        }

        // Cari insentif lama
        $insentif = $this->db->where('id', $id)->get('pu_insentif_agen')->row();
        if (!$insentif) {
            echo json_encode(['status' => false, 'message' => 'Data insentif tidak ditemukan']);
            return;
        }

        // Jika nominal berubah dan jenis_transaksi insentif, update saldo agen
        if ($insentif->jenis_transaksi == 'insentif' && $insentif->nominal != $nominal_baru) {
            $selisih = $nominal_baru - $insentif->nominal;
            // Update saldo agen
            $this->db->where('no_telp', $no_telp);
            $this->db->set('saldo', 'saldo + ' . (int)$selisih, FALSE);
            $this->db->update('pu_data_agen');
        }

        // Update data insentif
        $data = [
            'no_telp' => $no_telp,
            'description' => $description,
            'nominal' => $nominal_baru
        ];
        $this->db->where('id', $id);
        $update = $this->db->update('pu_insentif_agen', $data);

        if ($update) {
            echo json_encode(['status' => true, 'message' => 'Insentif & saldo berhasil diupdate']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Gagal update insentif']);
        }
    }

    public function getHistoryTransaksi($no_telp)
    {
        if (isset($_SERVER['HTTP_ORIGIN']) && $_SERVER['HTTP_ORIGIN'] === 'https://agen.pengenumroh.com') {
            header("Access-Control-Allow-Origin: https://agen.pengenumroh.com");
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
            header("Access-Control-Allow-Headers: Content-Type, Authorization");
        } else {
            echo json_encode(['status' => false, 'message' => 'Akses ditolak']);
            return;
        }

        // Handle preflight OPTIONS
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit();
        }

        // Ambil no_telp dari GET atau POST
        if (!$no_telp) {
            $no_telp = $this->input->post('no_telp', TRUE);
        }

        if (!$no_telp) {
            echo json_encode(['status' => false, 'message' => 'No telepon wajib diisi']);
            return;
        }

        // Ambil data history transaksi dari pu_insentif_agen
        $this->db->where('no_telp', $no_telp);
        $this->db->order_by('created_at', 'DESC');
        $history = $this->db->get('pu_insentif_agen')->result();

        echo json_encode([
            'status' => true,
            'data' => $history
        ]);
    }
}
