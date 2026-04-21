<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pu_data_member extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_pu_data_member');
        date_default_timezone_set('Asia/Jakarta');

        // CORS
        if (isset($_SERVER['HTTP_ORIGIN']) && $_SERVER['HTTP_ORIGIN'] === 'https://member.pengenumroh.com') {
            header("Access-Control-Allow-Origin: https://member.pengenumroh.com");
        }

        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");

        // Handle preflight request
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit();
        }
    }

    public function index()
    {
        $this->M_login->getsecurity();
        $data['title'] = "backend/pu_data_member/pu_data_member_list";
        $data['titleview'] = "Data Member";
        $this->load->view('backend/home', $data);
    }

    // List data member
    function get_list()
    {
        // INISIAI VARIABLE YANG DIBUTUHKAN
        $status = $this->input->post('status');
        $list = $this->M_pu_data_member->get_datatables($status);
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $field) {
            // Cek apakah ada insentif dengan payment_status = 2 (pending) untuk member ini
            $has_pending = $this->db->where('no_telp', $field->no_hp)
                ->where('payment_status', 2)
                ->count_all_results('pu_insentif_member') > 0;

            $notif_dot = $has_pending
                ? '<span style="position:absolute;top:-2px;right:-2px;width:10px;height:10px;background:red;border-radius:50%;display:inline-block;z-index:10;"></span>'
                : '';

            $action_insentif = '<span style="position:relative;display:inline-block;">'
                . '<button type="button" class="btn btn-primary btn-circle btn-sm" title="Insentif Member" onclick="showInsentifModal(\'' . $field->no_hp . '\', \'' . htmlspecialchars($field->nama, ENT_QUOTES) . '\')"><i class="fa fa-coins"></i></button>'
                . $notif_dot
                . '</span>';

            $action = $action_insentif;

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
            $row[] = $field->no_hp;
            $row[] = $field->kode_referral ? $field->kode_referral : '-';
            // $row[] = '<button type="button" class="btn btn-outline-primary btn-sm edit-saldo-trigger" 
            //     data-id="' . $field->id . '" 
            //     data-saldo="' . $field->saldo . '"
            //     style="font-weight:bold;letter-spacing:1px;display:inline-flex;align-items:center;">
            //     <i class="fa fa-money-bill-wave mr-1"></i> Rp ' . number_format($field->saldo, 0, ',', '.') . '
            // </button>';
            $row[] = number_format($field->saldo, 0, ',', '.');
            $row[] = $field->created_at;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_pu_data_member->count_all(),
            "recordsFiltered" => $this->M_pu_data_member->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    function get_id($id)
    {
        $data = $this->M_pu_data_member->get_by_id($id);
        echo json_encode($data);
    }

    public function update_status_member()
    {
        $id = $this->input->post('id');
        $kode_referral = $this->input->post('kode_referral');
        $is_active = $this->input->post('is_active');

        $data = [
            'kode_referral' => $kode_referral,
            'is_active' => $is_active
        ];
        $this->db->where('id', $id);
        $this->db->update('pu_customer', $data);

        echo json_encode(['status' => true, 'message' => 'Status berhasil diupdate']);
    }

    public function update_saldo_member()
    {
        $id = $this->input->post('id');
        $saldo = preg_replace('/\D/', '', $this->input->post('saldo'));
        $this->db->where('id', $id);
        $this->db->update('pu_customer', ['saldo' => $saldo]);
        echo json_encode(['status' => true, 'message' => 'Saldo berhasil diupdate']);
    }

    public function login_member()
    {
        header('Content-Type: application/json');

        // Ambil input JSON
        $input = json_decode(file_get_contents("php://input"), true);

        $no_hp = $input['no_telp'] ?? '';
        $password = $input['password'] ?? '';

        if (empty($no_hp) || empty($password)) {
            echo json_encode([
                "status" => false,
                "message" => "No telepon dan password wajib diisi"
            ]);
            return;
        }

        // Normalisasi nomor
        $no_hp_alt = "+62" . ltrim($no_hp, "0");

        // Ambil user
        $this->db->where("(no_hp = '$no_hp' OR no_hp = '$no_hp_alt')", NULL, FALSE);
        $query = $this->db->select('id, nama, no_hp, kode_referral, saldo, password, is_active')->get('pu_customer');

        if ($query->num_rows() == 0) {
            echo json_encode([
                "status" => false,
                "message" => "No. Telepon atau Password salah!"
            ]);
            return;
        }

        $member = $query->row_array();

        // CEK PASSWORD
        if (empty($member['password'])) {

            // Ambil 4 digit terakhir no_hp
            $clean_no_hp = preg_replace('/[^0-9]/', '', $member['no_hp']);
            $last4 = substr($clean_no_hp, -4);

            // Cocokkan langsung
            if ($password !== $last4) {
                echo json_encode([
                    "status" => false,
                    "message" => "Password salah! Hubungi admin untuk bantuan."
                ]);
                return;
            }

        } else {

            // Bandingkan MD5
            if (md5($password) !== $member['password']) {
                echo json_encode([
                    "status" => false,
                    "message" => "No. Telepon atau Password salah!"
                ]);
                return;
            }
        }

        // Hapus password
        unset($member['password']);

        echo json_encode([
            "status" => true,
            "data" => $member
        ]);
    }

    public function api_ganti_password_member()
    {
        header('Content-Type: application/json');

        // Ambil data POST (FormData)
        $no_hp        = $this->input->post('no_hp');
        $password_lama  = $this->input->post('password_lama');
        $password_baru  = $this->input->post('password_baru');

        if (empty($no_hp) || empty($password_lama) || empty($password_baru)) {
            echo json_encode([
                "status" => false,
                "message" => "Semua field wajib diisi"
            ]);
            return;
        }

        // Normalisasi nomor
        $no_hp_alt = "+62" . ltrim($no_hp, "0");

        // Ambil user
        $this->db->where("(no_hp = '$no_hp' OR no_hp = '$no_hp_alt')", NULL, FALSE);
        $query = $this->db->get('pu_customer');

        if ($query->num_rows() == 0) {
            echo json_encode([
                "status" => false,
                "message" => "User tidak ditemukan"
            ]);
            return;
        }

        $user = $query->row_array();

        // CEK PASSWORD LAMA
        $isValid = false;

        if (empty($user['password'])) {
            // pakai 4 digit terakhir no_hp
            $clean_no_hp = preg_replace('/[^0-9]/', '', $user['no_hp']);
            $last4 = substr($clean_no_hp, -4);

            if ($password_lama === $last4) {
                $isValid = true;
            }

        } else {
            // pakai MD5
            if (md5($password_lama) === $user['password']) {
                $isValid = true;
            }
        }

        if (!$isValid) {
            echo json_encode([
                "status" => false,
                "message" => "Password lama tidak sesuai"
            ]);
            return;
        }

        // Update password baru (MD5)
        $new_password_md5 = md5($password_baru);

        $this->db->where('id', $user['id']);
        $update = $this->db->update('pu_customer', [
            'password' => $new_password_md5
        ]);

        if ($update) {
            echo json_encode([
                "status" => true,
                "message" => "Password berhasil diubah"
            ]);
        } else {
            echo json_encode([
                "status" => false,
                "message" => "Gagal menyimpan password baru"
            ]);
        }
    }

    public function getSaldoMember($id)
    {
        // Ambil data member berdasarkan ID
        $member = $this->M_pu_data_member->get_by_id($id);

        if ($member) {
            echo json_encode([
                'status' => true,
                'saldo' => $member->saldo
            ]);
        } else {
            echo json_encode([
                'status' => false,
                'message' => 'Member tidak ditemukan'
            ]);
        }
    }

    public function get_insentif_member()
    {
        $no_telp = $this->input->post('no_telp');
        $draw = intval($this->input->post('draw'));
        $start = intval($this->input->post('start'));
        $length = intval($this->input->post('length'));

        // Ambil data member berdasarkan no_telp
        $member = $this->db->where('no_hp', $no_telp)->get('pu_customer')->row();
        if (!$member) {
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
        $recordsTotal = $this->db->count_all_results('pu_insentif_member');

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
        $recordsFiltered = $this->db->count_all_results('pu_insentif_member');

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
        $list = $this->db->get('pu_insentif_member')->result();

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
                            data-nama_member="' . htmlspecialchars($member->nama, ENT_QUOTES) . '"
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
                'nama_member' => $member->nama,
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

    public function update_payment_status_member()
    {
        $id = $this->input->post('id');
        $payment_status = $this->input->post('payment_status');

        $insentif = $this->db->where('id', $id)->get('pu_insentif_member')->row();

        if (!$insentif) {
            echo json_encode(['status' => false, 'message' => 'Data tidak ditemukan']);
            return;
        }

        if ($payment_status == 0 && $insentif->jenis_transaksi == 'pencairan') {
            $member = $this->db->where('no_hp', $insentif->no_telp)->get('pu_customer')->row();
            if ($member) {
                $this->db->where('no_hp', $insentif->no_telp);
                $this->db->set('saldo', 'saldo + ' . (int)$insentif->nominal, FALSE);
                $this->db->update('pu_customer');
            }
        }

        // Update status pembayaran
        $this->db->where('id', $id);
        $this->db->update('pu_insentif_member', ['payment_status' => $payment_status]);
        echo json_encode(['status' => true, 'message' => 'Status pembayaran berhasil diupdate']);
    }

    public function api_tambah_pencairan_member()
    {
        // Ambil data POST
        $no_hp = $this->input->post('no_hp', TRUE);
        $nominal = $this->input->post('nominal', TRUE);
        $no_rek = $this->input->post('no_rek', TRUE);
        $nama_bank = $this->input->post('nama_bank', TRUE);
        $nama_pemilik_rek = $this->input->post('nama_pemilik_rek', TRUE);

        // Validasi data
        if (!$no_hp || !$nominal || !$no_rek || !$nama_bank || !$nama_pemilik_rek) {
            echo json_encode(['status' => false, 'message' => 'Data tidak lengkap']);
            return;
        }

        // Cari id member berdasarkan no_hp
        $member = $this->db->where('no_hp', $no_hp)->get('pu_customer')->row();
        if (!$member) {
            echo json_encode(['status' => false, 'message' => 'Member tidak ditemukan']);
            return;
        }

        // Simpan ke tabel pu_insentif_member
        $data = [
            'no_telp' => $no_hp,
            'no_rek' => $no_rek,
            'nama_bank' => $nama_bank,
            'nama_pemilik_rek' => $nama_pemilik_rek,
            'jenis_transaksi' => 'pencairan',
            'nominal' => $nominal,
            'description' => "Menarik saldo sebesar Rp " . number_format($nominal, 0, ',', '.'),
            'payment_status' => 2,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $insert = $this->db->insert('pu_insentif_member', $data);

        if ($insert) {
            // Kurangi saldo member
            $saldo_baru = $member->saldo - $nominal;
            if ($saldo_baru < 0) $saldo_baru = 0; // Tidak boleh minus

            $this->db->where('no_hp', $no_hp);
            $this->db->update('pu_customer', ['saldo' => $saldo_baru]);

            echo json_encode(['status' => true, 'message' => 'Data pencairan berhasil ditambahkan']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Gagal menyimpan data pencairan']);
        }
    }

    public function tambah_insentif_member()
    {
        // Ambil data dari POST
        $no_telp = $this->input->post('no_telp', TRUE);
        $nama_member = $this->input->post('nama_member', TRUE);
        $nominal = preg_replace('/\D/', '', $this->input->post('nominal', TRUE));
        $description = $this->input->post('deskripsi', TRUE);

        // Validasi data
        if (!$no_telp || !$nama_member || !$nominal || !$description) {
            echo json_encode(['status' => false, 'message' => 'Data tidak lengkap']);
            return;
        }

        // Cari member berdasarkan no_hp
        $member = $this->db->where('no_hp', $no_telp)->get('pu_customer')->row();
        if (!$member) {
            echo json_encode(['status' => false, 'message' => 'Member tidak ditemukan']);
            return;
        }

        // Insert ke pu_insentif_member
        $data = [
            'no_telp' => $no_telp,
            'nominal' => $nominal,
            'description' => $description,
            'jenis_transaksi' => 'insentif',
            'payment_status' => 3, // 3 untuk insentif
            'created_at' => date('Y-m-d H:i:s')
        ];
        $insert = $this->db->insert('pu_insentif_member', $data);

        if ($insert) {
            // Tambahkan saldo ke pu_customer
            $this->db->where('no_hp', $no_telp);
            $this->db->set('saldo', 'saldo + ' . (int)$nominal, FALSE);
            $this->db->update('pu_customer');

            echo json_encode(['status' => true, 'message' => 'Insentif berhasil ditambahkan']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Gagal menambah insentif']);
        }
    }

    public function update_insentif_member()
    {
        $id = $this->input->post('id', TRUE);
        $no_telp = $this->input->post('no_telp', TRUE);
        $nama_member = $this->input->post('nama_member', TRUE);
        $nominal_baru = preg_replace('/\D/', '', $this->input->post('nominal', TRUE));
        $description = $this->input->post('deskripsi', TRUE);

        // Validasi data
        if (!$id || !$no_telp || !$nama_member || !$nominal_baru || !$description) {
            echo json_encode(['status' => false, 'message' => 'Data tidak lengkap']);
            return;
        }

        // Cari insentif lama
        $insentif = $this->db->where('id', $id)->get('pu_insentif_member')->row();
        if (!$insentif) {
            echo json_encode(['status' => false, 'message' => 'Data insentif tidak ditemukan']);
            return;
        }

        // Jika nominal berubah dan jenis_transaksi insentif, update saldo member
        if ($insentif->jenis_transaksi == 'insentif' && $insentif->nominal != $nominal_baru) {
            $selisih = $nominal_baru - $insentif->nominal;
            // Update saldo member
            $this->db->where('no_hp', $no_telp);
            $this->db->set('saldo', 'saldo + ' . (int)$selisih, FALSE);
            $this->db->update('pu_customer');
        }

        // Update data insentif
        $data = [
            'no_telp' => $no_telp,
            'description' => $description,
            'nominal' => $nominal_baru
        ];
        $this->db->where('id', $id);
        $update = $this->db->update('pu_insentif_member', $data);

        if ($update) {
            echo json_encode(['status' => true, 'message' => 'Insentif & saldo berhasil diupdate']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Gagal update insentif']);
        }
    }

    public function getHistoryTransaksiMember($no_hp)
    {
        // Ambil no_hp dari GET atau POST
        if (!$no_hp) {
            $no_hp = $this->input->post('no_hp', TRUE);
        }

        if (!$no_hp) {
            echo json_encode(['status' => false, 'message' => 'No telepon wajib diisi']);
            return;
        }

        // Ambil data history transaksi dari pu_insentif_member
        $this->db->where('no_telp', $no_hp);
        $this->db->order_by('created_at', 'DESC');
        $history = $this->db->get('pu_insentif_member')->result();

        echo json_encode([
            'status' => true,
            'data' => $history
        ]);
    }
}
