<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mac_kas extends CI_Controller
{
    // User penanggung jawab kas per cabang
    private $penanggung_jawab = [
        2  => 'indah',
        3  => 'titik',
        4  => 'sri',
        5  => 'pitri',
        6  => 'andro',
        7  => 'agung',
        8  => 'fatkhur',
        9  => 'anton',
        10 => 'hermawanta',
        11 => 'eko',
        12 => 'saryanto',
    ];

    function __construct()
    {
        parent::__construct();
        $this->M_login->getsecurity();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $is_nasional    = $this->session->userdata('is_nasional') ? true : false;
        $session_cabang = intval($this->session->userdata('cabang_id'));
        $username       = $this->session->userdata('username');

        // Guard: hanya penanggung jawab cabang atau nasional
        if (!$is_nasional) {
            $pj = $this->penanggung_jawab[$session_cabang] ?? null;
            if ($pj !== $username) {
                redirect('dashboard');
            }
        }

        $data['title']       = 'backend/mac_kas/mac_kas_list';
        $data['titleview']   = 'Kas Kecil';
        $data['is_nasional'] = $is_nasional;
        $data['cabang_id']   = $session_cabang;

        if ($is_nasional) {
            $data['list_cabang'] = $this->db
                ->where('status', 'aktif')
                ->where('id !=', 1)
                ->order_by('nama_cabang', 'ASC')
                ->get('mac_cabang')->result();
        } else {
            $data['list_cabang'] = [];
        }

        $this->load->view('backend/home', $data);
    }

    // GET LIST kas
    public function get_list()
    {
        $is_nasional    = $this->session->userdata('is_nasional') ? true : false;
        $session_cabang = intval($this->session->userdata('cabang_id'));
        $filter_cabang  = intval($this->input->post('filter_cabang'));

        $use_cabang_id = $is_nasional
            ? ($filter_cabang > 0 ? $filter_cabang : null)
            : $session_cabang;

        $q = $this->db->select('
                k.id, k.nominal_awal, k.total_dilaporkan, k.sisa_kas,
                k.status, k.keterangan, k.created_at, k.closed_at,
                c.nama_cabang,
                u.name as nama_user
            ', FALSE)
            ->from('mac_kas k')
            ->join('mac_cabang c', 'c.id = k.cabang_id', 'left')
            ->join('tbl_data_user u', 'u.id_user = k.user_id', 'left');

        if (!is_null($use_cabang_id)) {
            $q->where('k.cabang_id', $use_cabang_id);
        }

        $q->order_by('k.created_at', 'DESC');
        $list = $q->get()->result();
        $data = [];
        $no   = intval($_POST['start']);

        foreach ($list as $field) {
            $status_badge = $field->status === 'aktif'
                ? '<span class="badge badge-success">Aktif</span>'
                : '<span class="badge badge-secondary">Selesai</span>';

            $sisa_class = floatval($field->sisa_kas) <= 0
                ? 'text-danger' : 'text-success';

            $action_tutup = ($field->status === 'aktif')
                ? '<a onclick="tutup_kas(' . $field->id . ')" class="btn btn-warning btn-circle btn-sm" title="Tutup Kas"><i class="fa fa-lock"></i></a>&nbsp;'
                : '';
            $action_lapor = ($field->status === 'aktif' && floatval($field->sisa_kas) > 0)
                ? '<a onclick="lapor_kas(' . $field->id . ')" class="btn btn-primary btn-circle btn-sm" title="Buat Pelaporan"><i class="fa fa-file-alt"></i></a>&nbsp;'
                : '';

            $no++;
            $row   = [];
            $row[] = $no;
            $row[] = $action_tutup . $action_lapor;
            $row[] = $field->nama_cabang;
            $row[] = $field->nama_user;
            $row[] = 'Rp ' . number_format($field->nominal_awal,     0, ',', '.');
            $row[] = 'Rp ' . number_format($field->total_dilaporkan, 0, ',', '.');
            $row[] = '<span class="' . $sisa_class . ' font-weight-bold">Rp ' . number_format($field->sisa_kas, 0, ',', '.') . '</span>';
            $row[] = $status_badge;
            $row[] = date('d-m-Y H:i', strtotime($field->created_at));
            $row[] = $field->closed_at ? date('d-m-Y H:i', strtotime($field->closed_at)) : '—';
            $data[] = $row;
        }

        echo json_encode([
            "draw"            => $_POST['draw'],
            "recordsTotal"    => count($list),
            "recordsFiltered" => count($list),
            "data"            => $data,
        ]);
    }

    // GET saldo kas aktif per cabang — untuk ditampilkan di list prepayment
    public function get_saldo_aktif()
    {
        $is_nasional    = $this->session->userdata('is_nasional') ? true : false;
        $session_cabang = intval($this->session->userdata('cabang_id'));

        if ($is_nasional) {
            // Semua cabang
            $rows = $this->db->select('
                    k.id, k.cabang_id, k.nominal_awal, k.sisa_kas,
                    k.total_dilaporkan, c.nama_cabang
                ', FALSE)
                ->from('mac_kas k')
                ->join('mac_cabang c', 'c.id = k.cabang_id', 'left')
                ->where('k.status', 'aktif')
                ->order_by('c.nama_cabang', 'ASC')
                ->get()->result_array();
        } else {
            $rows = $this->db->select('
                    k.id, k.cabang_id, k.nominal_awal, k.sisa_kas,
                    k.total_dilaporkan, c.nama_cabang
                ', FALSE)
                ->from('mac_kas k')
                ->join('mac_cabang c', 'c.id = k.cabang_id', 'left')
                ->where('k.status', 'aktif')
                ->where('k.cabang_id', $session_cabang)
                ->get()->result_array();
        }

        echo json_encode(['status' => TRUE, 'data' => $rows]);
    }

    // BUKA KAS BARU
    public function buka_kas()
    {
        $cabang_id = intval($this->session->userdata('cabang_id'));
        $username  = $this->session->userdata('username');
        $id_user   = intval($this->session->userdata('id_user'));
        $nominal   = intval(str_replace('.', '', $this->input->post('nominal')));

        // Guard: cek apakah user adalah penanggung jawab
        $pj = $this->penanggung_jawab[$cabang_id] ?? null;
        if ($pj !== $username) {
            echo json_encode(['status' => FALSE, 'error' => 'Anda tidak berhak membuka kas.']);
            return;
        }

        if ($nominal <= 0) {
            echo json_encode(['status' => FALSE, 'error' => 'Nominal kas harus lebih dari 0.']);
            return;
        }

        // Cek tidak ada kas aktif di cabang ini
        $kas_aktif = $this->db
            ->where('cabang_id', $cabang_id)
            ->where('status', 'aktif')
            ->count_all_results('mac_kas');

        if ($kas_aktif > 0) {
            echo json_encode(['status' => FALSE, 'error' => 'Masih ada kas aktif. Selesaikan atau tutup kas yang lama terlebih dahulu.']);
            return;
        }

        $this->db->insert('mac_kas', [
            'cabang_id'       => $cabang_id,
            'user_id'         => $id_user,
            'nominal_awal'    => $nominal,
            'total_dilaporkan'=> 0,
            'sisa_kas'        => $nominal,
            'status'          => 'aktif',
            'keterangan'      => $this->input->post('keterangan') ?: null,
            'created_at'      => date('Y-m-d H:i:s'),
            'created_by'      => $id_user,
        ]);

        echo json_encode(['status' => TRUE, 'message' => 'Kas berhasil dibuka.']);
    }

    // TUTUP KAS
    public function tutup_kas()
    {
        $kas_id   = intval($this->input->post('kas_id'));
        $id_user  = intval($this->session->userdata('id_user'));
        $username = $this->session->userdata('username');

        $kas = $this->db->where('id', $kas_id)->get('mac_kas')->row();
        if (!$kas || $kas->status !== 'aktif') {
            echo json_encode(['status' => FALSE, 'error' => 'Kas tidak ditemukan atau sudah ditutup.']);
            return;
        }

        $this->db->where('id', $kas_id)->update('mac_kas', [
            'status'     => 'selesai',
            'closed_at'  => date('Y-m-d H:i:s'),
            'closed_by'  => $id_user,
            'keterangan' => $this->input->post('keterangan') ?: $kas->keterangan,
        ]);

        echo json_encode(['status' => TRUE, 'message' => 'Kas berhasil ditutup.']);
    }

    // UPDATE saldo kas — dipanggil dari Mac_prepayment saat pelaporan kas di-approve
    public function update_saldo($kas_id, $nominal_lapor)
    {
        $kas = $this->db->where('id', $kas_id)->get('mac_kas')->row();
        if (!$kas) return;

        $total_dilaporkan = floatval($kas->total_dilaporkan) + floatval($nominal_lapor);
        $sisa_kas         = floatval($kas->nominal_awal) - $total_dilaporkan;

        $this->db->where('id', $kas_id)->update('mac_kas', [
            'total_dilaporkan' => $total_dilaporkan,
            'sisa_kas'         => max(0, $sisa_kas),
            'status'           => $sisa_kas <= 0 ? 'selesai' : 'aktif',
            'closed_at'        => $sisa_kas <= 0 ? date('Y-m-d H:i:s') : null,
        ]);
    }

    public function get_kas_aktif_cabang()
    {
        $cabang_id = intval($this->session->userdata('cabang_id'));

        $data = $this->db->select('
                k.id, k.nominal_awal, k.total_dilaporkan, k.sisa_kas,
                p.kode_prepayment
            ', FALSE)
            ->from('mac_kas k')
            ->join('mac_prepayment p', 'p.id = k.prepayment_id', 'left')
            ->where('k.cabang_id', $cabang_id)
            ->where('k.status', 'aktif')
            ->order_by('k.created_at', 'DESC')
            ->get()->result_array();

        echo json_encode(['status' => TRUE, 'data' => $data]);
    }
}