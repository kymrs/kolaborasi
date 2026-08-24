<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mac_reimbust extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_mac_reimbust');
        $this->load->model('backend/M_notifikasi');
        $this->load->model('backend/M_mac_inventory_stok');
        $this->M_login->getsecurity();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        ($akses->view_level == 'N' ? redirect('auth') : '');

        if (empty($this->session->userdata('cabang_id'))) {
            echo "
            <script>
                alert('Cabang belum diatur. Silakan hubungi Admin untuk melanjutkan.');
                window.location.href = '" . site_url('dashboard') . "';
            </script>";
            exit;
        }

        $data['add'] = $akses->add_level;
        $data['alias'] = $this->session->userdata('username');
        $data['title'] = "backend/mac_reimbust/mac_reimbust_list";
        $data['titleview'] = "Data Reimbust";
        $name = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $this->session->userdata('id_user'))
            ->get()
            ->row('name');
        $data['approval'] = $this->db->select('COUNT(*) as total_approval')
            ->from('mac_reimbust')
            ->where('app_name', $name)
            ->or_where('app2_name', $name)
            ->or_where('app4_name', $name)
            ->get()
            ->row('total_approval');
        $this->load->view('backend/home', $data);
    }

    // get list reimbust
    function get_list()
    {
        // INISIAI VARIABLE YANG DIBUTUHKAN
        $fullname = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $this->session->userdata('id_user'))
            ->get()
            ->row('name');
        $list = $this->M_mac_reimbust->get_datatables();
        $data = array();
        $no = $_POST['start'];

        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        $read = $akses->view_level;
        $edit = $akses->edit_level;
        $delete = $akses->delete_level;
        $print = $akses->print_level;

        foreach ($list as $field) {

            // MENENTUKAN ACTION APA YANG AKAN DITAMPILKAN DI LIST DATA TABLES
            $action_read = ($read == 'Y') ? '<a href="mac_reimbust/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>&nbsp;' : '';
            $action_edit = ($edit == 'Y') ? '<a href="mac_reimbust/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;' : '';
            $action_delete = ($delete == 'Y') ? '<a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;' : '';
            $action_print = ($print == 'Y') ? '<a class="btn btn-success btn-circle btn-sm" target="_blank" href="mac_reimbust/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>' : '';

            // MENENTUKAN ACTION APA YANG AKAN DITAMPILKAN DI LIST DATA TABLES
            if ($this->session->userdata('username') == 'eko') {
                $action = $action_read . $action_edit . $action_delete . $action_print;
            } elseif ($field->id_user == $this->session->userdata('id_user') && !in_array($field->status, ['rejected', 'approved', 'revised']) && $field->app_status == "waiting") {
                $action = $action_read . $action_edit . $action_delete . $action_print;
            } elseif (($field->id_user == $this->session->userdata('id_user') || $this->session->userdata('username') == 'eko') && $field->status == 'revised') {
                $action = $action_read . $action_edit . $action_print;
            } else {
                $action = $action_read . $action_print;
            }

            //MENENSTUKAN SATTSU PROGRESS PENGAJUAN PERMINTAAN
            if ($field->app_status == 'approved' && $field->app2_status == 'waiting' && $field->status == 'on-process') {
                $status = $field->status . ' (' . $field->app2_name . ')';
            } elseif ($field->app_status == 'waiting' && $field->app2_status == 'waiting' && $field->status == 'on-process') {
                $status = $field->status . ' (' . $field->app_name . ')';
            } else {
                $status = $field->status;
            }

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $action;
            if ($field->payment_status == 'paid') {
                $row[] = '<div class="text-center"><button class="btn btn-primary btn-circle btn-sm" data-toggle="modal" data-target="#paymentDetailModal" data-id="' . $field->id . '" title="Detail Pembayaran"><i class="fas fa-check" style="color: #1cc88a;"></i></button></div>';
            } else if ($field->payment_status == 'unpaid') {
                $row[] = '<div class="text-center"><i class="fas fa-times" style="color: red;"></i></div>';
            }
            $row[] = strtoupper($field->kode_reimbust);
            $row[] = $field->name;
            // $row[] = $field->jabatan;
            // $row[] = $field->departemen;
            $row[] = $field->sifat_pelaporan;
            // Array bulan bahasa Indonesia
            $bulanIndo = array(
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
            $row[] = date("d", strtotime($field->tgl_pengajuan)) . " " . $bulanIndo[date("n", strtotime($field->tgl_pengajuan))] . " " . date("Y", strtotime($field->tgl_pengajuan));
            $row[] = $field->tujuan;
            $row[] = 'Rp. ' . number_format($field->jumlah_prepayment, 0, ',', '.');;
            $row[] = ucwords($status);

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_mac_reimbust->count_all(),
            "recordsFiltered" => $this->M_mac_reimbust->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    // get list deklarasi
    function get_list2()
    {
        // INISIAI VARIABLE YANG DIBUTUHKAN
        $fullname = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $this->session->userdata('id_user'))
            ->get()
            ->row('name');
        $list = $this->M_mac_reimbust->get_datatables2();
        $data = array();
        $no = $_POST['start'];

        //LOOPING DATATABLES
        foreach ($list as $field) {

            // MENENTUKAN ACTION APA YANG AKAN DITAMPILKAN DI LIST DATA TABLES
            if ($field->app_name == $fullname) {
                $action = '<a href="mac_datadeklarasi/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                                <a class="btn btn-success btn-circle btn-sm" href="mac_datadeklarasi/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } elseif ($field->app2_name == $fullname) {
                $action = '<a href="mac_datadeklarasi/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>     
                                <a class="btn btn-success btn-circle btn-sm" href="mac_datadeklarasi/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } elseif (in_array($field->status, ['rejected', 'approved'])) {
                $action = '<a href="mac_datadeklarasi/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                <a class="btn btn-success btn-circle btn-sm" href="mac_datadeklarasi/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } elseif ($field->app_status == 'revised' || $field->app2_status == 'revised') {
                $action = '<a href="mac_datadeklarasi/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                    <a href="mac_datadeklarasi/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
                    <a class="btn btn-success btn-circle btn-sm" href="mac_datadeklarasi/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } elseif ($field->app_status == 'approved') {
                $action = '<a href="mac_datadeklarasi/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                            <a class="btn btn-success btn-circle btn-sm" href="mac_datadeklarasi/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } else {
                $action = '<a href="mac_datadeklarasi/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                        <a href="mac_datadeklarasi/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
			            <a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>
                        <a class="btn btn-success btn-circle btn-sm" href="mac_datadeklarasi/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            }

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $action;
            $row[] = strtoupper($field->kode_deklarasi);
            $row[] = date("d M Y", strtotime($field->tgl_deklarasi));
            $row[] = $field->name;
            $row[] = $field->jabatan;
            $row[] = $field->nama_dibayar;
            $row[] = $field->tujuan;
            $row[] = 'Rp. ' . number_format($field->sebesar, 0, ',', '.');;
            // $row[] = $field->sebesar;
            $row[] = $field->status;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_mac_reimbust->count_all2(),
            "recordsFiltered" => $this->M_mac_reimbust->count_filtered2(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    // get list mac_prepayment
    function get_list3()
    {
        // INISIAI VARIABLE YANG DIBUTUHKAN
        $fullname = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $this->session->userdata('id_user'))
            ->get()
            ->row('name');
        $list = $this->M_mac_reimbust->get_datatables3();
        $data = array();
        $no = $_POST['start'];

        //LOOPING DATATABLES
        foreach ($list as $field) {

            // MENENTUKAN ACTION APA YANG AKAN DITAMPILKAN DI LIST DATA TABLES
            if ($field->app_name == $fullname) {
                $action = '<a href="mac_prepayment/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                                <a class="btn btn-success btn-circle btn-sm" href="mac_prepayment/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } elseif ($field->app2_name == $fullname) {
                $action = '<a href="mac_prepayment/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>     
                                <a class="btn btn-success btn-circle btn-sm" href="mac_prepayment/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } elseif (in_array($field->status, ['rejected', 'approved'])) {
                $action = '<a href="mac_prepayment/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                <a class="btn btn-success btn-circle btn-sm" href="mac_prepayment/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } elseif ($field->app_status == 'revised' || $field->app2_status == 'revised') {
                $action = '<a href="mac_prepayment/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                    <a href="mac_prepayment/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
                    <a class="btn btn-success btn-circle btn-sm" href="mac_prepayment/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } elseif ($field->app_status == 'approved') {
                $action = '<a href="mac_prepayment/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                            <a class="btn btn-success btn-circle btn-sm" href="mac_prepayment/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            } else {
                $action = '<a href="mac_prepayment/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
                        <a href="mac_prepayment/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
                        <a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>
                        <a class="btn btn-success btn-circle btn-sm" href="mac_prepayment/generate_pdf/' . $field->id . '"><i class="fas fa-file-pdf"></i></a>';
            }


            $formatted_nominal = number_format($field->total_nominal, 0, ',', '.');
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $action;
            $row[] = strtoupper($field->kode_prepayment);
            $row[] = $field->name;
            $row[] = strtoupper($field->divisi);
            $row[] = strtoupper($field->jabatan);
            $row[] = date("d M Y", strtotime($field->tgl_prepayment));
            $row[] = $field->prepayment;
            $row[] = $formatted_nominal;
            // $row[] = $field->tujuan;
            $row[] = $field->status;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_mac_reimbust->count_all3(),
            "recordsFiltered" => $this->M_mac_reimbust->count_filtered3(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    function read_form($id)
    {
        $data['aksi'] = 'read';
        $data['user'] = $this->M_mac_reimbust->get_by_id($id);
        $data['app_name'] = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $this->session->userdata('id_user'))
            ->get()
            ->row('name');
        $data['app2_name'] = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $this->session->userdata('id_user'))
            ->get()
            ->row('name');
        $data['app4_name'] = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $this->session->userdata('id_user'))
            ->get()
            ->row('name');
        $data['id'] = $id;
        $data['title_view'] = "Data Reimbust";
        $data['title'] = 'backend/mac_reimbust/mac_reimbust_read';
        $this->db->select('kwitansi');
        $this->db->where('reimbust_id', $id);
        $data['kwitansi'] = $this->db->get('mac_reimbust_detail')->result_array();
        $this->load->view('backend/home', $data);
    }

    public function add_form()
    {
        $id_user = $this->session->userdata('id_user');
        $data['id_user'] = $id_user;
        $data['id_pembuat'] = 0;
        $data['id'] = 0;
        $data['aksi'] = 'add';
        $data['rek_options'] = $this->M_mac_reimbust->options($id_user)->result_array();
        $data['inventory_options'] = $this->M_mac_reimbust->get_inventory_options()->result_array(); // BARU
        $data['title_view'] = "Reimbust Form";
        $data['title'] = 'backend/mac_reimbust/mac_reimbust_form';
        $this->load->view('backend/home', $data);
    }

    // MEREGENERATE KODE REIMBUST
    public function generate_kode()
    {
        $date = $this->input->post('date');
        $kode = $this->M_mac_reimbust->max_kode($date)->row();
        if (empty($kode->kode_reimbust)) {
            $no_urut = 1;
        } else {
            $bln = substr($kode->kode_reimbust, 3, 2);
            $no_urut = substr($kode->kode_reimbust, 5) + 1;
        }
        $urutan = str_pad($no_urut, 4, "0", STR_PAD_LEFT);
        $month = substr($date, 3, 2);
        $year = substr($date, 8, 2);
        $data = 'R' . $year . $month . $urutan;
        echo json_encode($data);
    }

    function edit_form($id)
    {
        $data['id_user'] = $this->session->userdata('id_user');
        $data['id_pembuat'] = $this->M_mac_reimbust->get_by_id($id)->id_user;
        $data['reimbust'] = $this->M_mac_reimbust->get_by_id($id);
        $data['id'] = $id;
        $data['aksi'] = 'update';
        $data['title_view'] = "Edit Reimbust";
        $data['rek_options'] = $this->M_mac_reimbust->options($data['id_user'])->result_array();
        $data['inventory_options'] = $this->M_mac_reimbust->get_inventory_options()->result_array(); // BARU
        $data['title'] = 'backend/mac_reimbust/mac_reimbust_form';
        $this->load->view('backend/home', $data);
    }
    
    function edit_data($id)
    {
        $data['master'] = $this->M_mac_reimbust->get_by_id($id);

        $data['transaksi'] = $this->db
            ->select('a.*, b.satuan')
            ->from('mac_reimbust_detail as a')
            ->join('mac_inventory as b', 'b.id = a.inventory_id', 'left')
            ->where('a.reimbust_id', $id)
            ->get()
            ->result();

        $data['nama'] = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $data['master']->id_user)
            ->get()
            ->row('name');

        echo json_encode($data);
    }

    function read_detail($id)
    {
        $data = $this->M_mac_reimbust->get_by_id_detail($id);
        echo json_encode($data);
    }

    public function detail_deklarasi()
    {
        if ($this->input->is_ajax_request()) {
            $deklarasi = $this->input->post('deklarasi');

            // Mengambil data deklarasi dari database
            $deklarasiRecord = $this->db->get_where('mac_deklarasi', ['kode_deklarasi' => $deklarasi])->row_array();

            // Debug log
            log_message('debug', 'Deklarasi: ' . print_r($deklarasi, true));
            log_message('debug', 'Deklarasi Record: ' . print_r($deklarasiRecord, true));

            if ($deklarasiRecord) {
                // Mengambil ID dari record yang ditemukan
                $deklarasiId = $deklarasiRecord['id']; // Pastikan 'id' adalah nama kolom yang sesuai
                $redirect_url = site_url('mac_datadeklarasi/read_form/' . $deklarasiId);

                $response = array(
                    'status' => 'success',
                    'message' => 'Data berhasil diproses',
                    'redirect_url' => $redirect_url
                );
            } else {
                $response = array(
                    'status' => 'error',
                    'message' => 'Data deklarasi tidak ditemukan'
                );
            }

            // Mengirimkan response JSON
            echo json_encode($response);
        } else {
            show_error('No direct access allowed', 403);
        }
    }

    public function add()
    {
        $this->load->library('upload');
 
        $sifat_pelaporan = $this->input->post('sifat_pelaporan');
        $pemakaian       = $this->input->post('pemakaian');
        $tgl_nota        = $this->input->post('tgl_nota');
        $jumlah          = $this->input->post('jumlah');
        $inventory_id    = $this->input->post('inventory_id') ?: [];
        $qty             = $this->input->post('qty') ?: [];
        $deklarasi       = $this->input->post('deklarasi');  
        $jumlahClean     = preg_replace('/\D/', '', $jumlah);
        $id_user         = $this->session->userdata('id_user');

        $cabang_id = $this->session->userdata('cabang_id');
 
        // Validasi barang & qty wajib untuk Pelaporan
        if ($sifat_pelaporan == 'Pelaporan') {
            for ($i = 1; $i <= count($pemakaian); $i++) {
                if (empty($qty[$i]) || !is_numeric($qty[$i]) || floatval($qty[$i]) <= 0) {
                    echo json_encode(["status"=>FALSE,"error"=>"Qty pada baris ke-{$i} harus diisi dan lebih dari 0."]);
                    return;
                }
            }
        }
 
        // Validasi file
        $allowed_types = ['image/jpeg','image/jpg','image/png','application/pdf'];
        for ($i = 1; $i <= count($pemakaian); $i++) {
            if (!empty($_FILES['kwitansi']['name'][$i])) {
                if (!in_array($_FILES['kwitansi']['type'][$i], $allowed_types)) {
                    echo json_encode(["status"=>FALSE,"error"=>"Tipe file tidak diizinkan untuk file ke-{$i}."]);
                    return;
                }
                if ($_FILES['kwitansi']['size'][$i] > 3072 * 1024) {
                    echo json_encode(["status"=>FALSE,"error"=>"Ukuran file ke-{$i} melebihi 3 MB."]);
                    return;
                }
            }
        }
 
        // Cari approver
        $id_menu = $this->db->select('id_menu')->where('link', $this->router->fetch_class())->get('tbl_submenu')->row();
        $app     = $this->db->select('app_id, app2_id, app4_id')->from('tbl_approval')->where('id_menu', $id_menu->id_menu)->get()->row();
        if (empty($app->app_id)) {
            echo json_encode(["status"=>FALSE,"error"=>"Approval belum ditentukan, hubungi admin."]);
            return;
        }
 
        // Rekening
        $no_rek = !empty($_POST['nama_rek'])
            ? $this->input->post('nama_rek')."-".$this->input->post('nama_bank')."-".$this->input->post('nomor_rekening')
            : $this->input->post('rekening');
 
        // Data user
        $data_user = $this->db->get_where('tbl_data_user', ['id_user'=>$id_user])->row_array();
 
        // Generate kode reimbust
        $date    = $this->input->post('tgl_pengajuan');
        $kode    = $this->M_mac_reimbust->max_kode($date)->row();
        $no_urut = empty($kode->kode_reimbust) ? 1 : (int)substr($kode->kode_reimbust, 5) + 1;
        $urutan  = str_pad($no_urut, 4, "0", STR_PAD_LEFT);
        $kode_reimbust = 'R'.substr($date, 8, 2).substr($date, 3, 2).$urutan;

        // ── TAMBAHAN: baca flag kas dari form ──────────────────────────
        $is_pelaporan_kas = intval($this->input->post('is_pelaporan_kas'));
        $kas_id           = intval($this->input->post('kas_id'));

        // Validasi saldo kas jika pelaporan dari kas
        if ($is_pelaporan_kas && $kas_id) {
            $kas = $this->db
                ->where('id', $kas_id)
                ->where('cabang_id', $cabang_id)
                ->where('status', 'aktif')
                ->get('mac_kas')->row();

            if (!$kas) {
                echo json_encode(["status" => FALSE,
                    "error" => "Kas tidak ditemukan atau sudah tidak aktif."]);
                return;
            }

            $total_nominal_clean = intval(preg_replace('/\D/', '',
                $this->input->post('total_nominal')));

            if ($total_nominal_clean > floatval($kas->sisa_kas)) {
                echo json_encode(["status" => FALSE,
                    "error" => "Total pelaporan (Rp " .
                        number_format($total_nominal_clean, 0, ',', '.') .
                        ") melebihi sisa kas (Rp " .
                        number_format($kas->sisa_kas, 0, ',', '.') . ")."]);
                return;
            }

            // Cek apakah ini pelaporan kas pertama atau lanjutan
            $jumlah_laporan_sebelumnya = $this->db
                ->where('kas_id', $kas_id)
                ->where('status', 'approved')
                ->count_all_results('mac_reimbust');

            $is_kas_lanjutan = $jumlah_laporan_sebelumnya > 0 ? 1 : 0;
        } else {
            $is_kas_lanjutan = 0;
        }
 
        // Insert header
        $reimbust_id = $this->M_mac_reimbust->save([
            'is_pelaporan_kas'  => $is_pelaporan_kas,
            'kas_id'            => ($is_pelaporan_kas && $kas_id) ? $kas_id : null,
            'is_kas_lanjutan'   => $is_kas_lanjutan,
            'cabang_id'         => $cabang_id,
            'kode_reimbust'     => $kode_reimbust,
            'kode_prepayment'   => $this->input->post('kode_prepayment'),
            'id_user'           => $id_user,
            'jabatan'           => $data_user['jabatan'],
            'departemen'        => $data_user['divisi'],
            'sifat_pelaporan'   => $sifat_pelaporan,
            'tgl_pengajuan'     => date('Y-m-d', strtotime($this->input->post('tgl_pengajuan'))),
            'tujuan'            => ucwords(strtolower(trim($this->input->post('tujuan')))),
            'jumlah_prepayment' => $this->input->post('jumlah_prepayment'),
            'total_nominal'     => preg_replace('/\D/', '', $this->input->post('total_nominal')),
            'no_rek'            => $no_rek,
            'app_name'          => $this->db->select('name')->from('tbl_data_user')->where('id_user', $app->app_id)->get()->row('name'),
            'app2_name'         => $this->db->select('name')->from('tbl_data_user')->where('id_user', $app->app2_id)->get()->row('name'),
            'app4_name'         => $this->db->select('name')->from('tbl_data_user')->where('id_user', $app->app4_id)->get()->row('name'),
            'created_at'        => date('Y-m-d H:i:s'),
        ]);
 
        // Insert detail
        $data2 = [];
        for ($i = 1; $i <= count($pemakaian); $i++) {
            $kwitansi = null;
            if (!empty($_FILES['kwitansi']['name'][$i])) {
                $_FILES['file'] = [
                    'name'     => $_FILES['kwitansi']['name'][$i],
                    'type'     => $_FILES['kwitansi']['type'][$i],
                    'tmp_name' => $_FILES['kwitansi']['tmp_name'][$i],
                    'error'    => $_FILES['kwitansi']['error'][$i],
                    'size'     => $_FILES['kwitansi']['size'][$i],
                ];
                $this->upload->initialize([
                    'upload_path'   => './assets/backend/document/reimbust/kwitansi/kwitansi_mac/',
                    'allowed_types' => 'jpeg|jpg|png|pdf',
                    'max_size'      => 3072,
                    'encrypt_name'  => TRUE,
                ]);
                if ($this->upload->do_upload('file')) {
                    $kwitansi = $this->upload->data('file_name');
                } else {
                    echo json_encode(["status"=>FALSE,"error"=>$this->upload->display_errors()]);
                    return;
                }
            }
 
            $data2[] = [
                'reimbust_id'  => $reimbust_id,
                'inventory_id' => !empty($inventory_id[$i]) ? (int)$inventory_id[$i] : null,
                'qty'          => !empty($qty[$i]) ? floatval($qty[$i]) : null,
                'pemakaian'    => $pemakaian[$i],
                'tgl_nota'     => !empty($tgl_nota[$i]) ? date('Y-m-d', strtotime($tgl_nota[$i])) : date('Y-m-d'),
                'jumlah'       => !empty($jumlahClean[$i]) ? $jumlahClean[$i] : 0,
                'kwitansi'     => $kwitansi,
                'deklarasi'    => $deklarasi[$i] ?? null,
            ];
 
            if (!empty($deklarasi[$i])) {
                $this->db->update('mac_deklarasi', ['is_active'=>0], ['kode_deklarasi'=>$deklarasi[$i]]);
            }
        }
 
        $this->db->update('mac_prepayment', ['is_active'=>0], ['kode_prepayment'=>$this->input->post('kode_prepayment')]);
        $this->M_mac_reimbust->save_detail($data2);
 
        echo json_encode(["status"=>TRUE]);
    }
 
    // =========================================================
    // UPDATE — tolak jika sudah approved
    // =========================================================
 
    public function update()
    {
        $this->load->library('upload');
 
        $reimbust_id = $this->input->post('id');
 
        // Guard: tolak edit jika sudah final approved (batch sudah terbentuk)
        $existing = $this->db->select('status')->where('id', $reimbust_id)->get('mac_reimbust')->row();
        if ($existing && $existing->status === 'approved') {
            echo json_encode(["status"=>FALSE,"error"=>"Data yang sudah approved tidak dapat diubah."]);
            return;
        }
 
        $sifat_pelaporan = $this->input->post('sifat_pelaporan');
        $pemakaian       = $this->input->post('pemakaian');
        $jumlah          = $this->input->post('jumlah');
        $inventory_id    = $this->input->post('inventory_id') ?: [];
        $qty             = $this->input->post('qty') ?: [];
        $jumlahClean     = preg_replace('/\D/', '', $jumlah);
        $tgl_nota        = $this->input->post('tgl_nota');
        $detail_id       = $this->input->post('detail_id');
        $kwitansi_image  = $this->input->post('kwitansi_image');
        $deklarasi       = $this->input->post('deklarasi');
        $deklarasi_old   = $this->input->post('deklarasi_old');
        $cabang_id       = $this->session->userdata('cabang_id');

        // ── TAMBAHAN: baca flag kas ─────────────────────────────────────
        $is_pelaporan_kas = intval($this->input->post('is_pelaporan_kas'));
        $kas_id           = intval($this->input->post('kas_id'));

        // Validasi saldo kas jika pelaporan dari kas
        if ($is_pelaporan_kas && $kas_id) {
            $kas = $this->db
                ->where('id', $kas_id)
                ->where('cabang_id', $cabang_id)
                ->where('status', 'aktif')
                ->get('mac_kas')->row();

            if (!$kas) {
                echo json_encode(["status" => FALSE,
                    "error" => "Kas tidak ditemukan atau sudah tidak aktif."]);
                return;
            }

            $total_nominal_clean = intval(preg_replace('/\D/', '',
                $this->input->post('total_nominal')));

            // Ambil total_nominal lama agar sisa kas tidak dihitung ganda
            $existing_nominal = $this->db->select('total_nominal, kas_id')
                ->where('id', $reimbust_id)
                ->get('mac_reimbust')->row();

            // Sisa kas = sisa saat ini + nominal lama (karena ini edit, bukan baru)
            $sisa_efektif = floatval($kas->sisa_kas);
            if ($existing_nominal && intval($existing_nominal->kas_id) === $kas_id) {
                $sisa_efektif += floatval($existing_nominal->total_nominal);
            }

            if ($total_nominal_clean > $sisa_efektif) {
                echo json_encode(["status" => FALSE,
                    "error" => "Total pelaporan (Rp " .
                        number_format($total_nominal_clean, 0, ',', '.') .
                        ") melebihi sisa kas (Rp " .
                        number_format($sisa_efektif, 0, ',', '.') . ")."]);
                return;
            }

            $jumlah_laporan_sebelumnya = $this->db
                ->where('kas_id', $kas_id)
                ->where('status', 'approved')
                ->where('id !=', $reimbust_id)
                ->count_all_results('mac_reimbust');

            $is_kas_lanjutan = $jumlah_laporan_sebelumnya > 0 ? 1 : 0;
        } else {
            $is_kas_lanjutan = 0;
        }
 
        // Validasi barang & qty untuk Pelaporan
        if ($sifat_pelaporan == 'Pelaporan') {
            foreach ($pemakaian as $i => $p) {
                if (empty($inventory_id[$i])) {
                    echo json_encode(["status"=>FALSE,"error"=>"Barang pada baris ke-{$i} belum dipilih."]);
                    return;
                }
                if (empty($qty[$i]) || !is_numeric($qty[$i]) || floatval($qty[$i]) <= 0) {
                    echo json_encode(["status"=>FALSE,"error"=>"Qty pada baris ke-{$i} harus diisi dan lebih dari 0."]);
                    return;
                }
            }
        }
 
        // Rekening
        $no_rek = !empty($_POST['nama_rek'])
            ? $this->input->post('nama_rek')."-".$this->input->post('nama_bank')."-".$this->input->post('nomor_rekening')
            : $this->input->post('rekening');
 
        $this->db->where('id', $reimbust_id)->update('mac_reimbust', [
            'cabang_id'         => $cabang_id,
            'is_pelaporan_kas' => $is_pelaporan_kas,
            'kas_id'           => ($is_pelaporan_kas && $kas_id) ? $kas_id : null,
            'is_kas_lanjutan'  => $is_kas_lanjutan,
            'sifat_pelaporan'  => $sifat_pelaporan,
            'tgl_pengajuan'    => date('Y-m-d', strtotime($this->input->post('tgl_pengajuan'))),
            'kode_reimbust'    => $this->input->post('kode_reimbust'),
            'tujuan'           => ucwords(strtolower(trim($this->input->post('tujuan')))),
            'jumlah_prepayment'=> $this->input->post('jumlah_prepayment'),
            'total_nominal' => preg_replace('/\D/', '', $this->input->post('total_nominal')),
            'no_rek'           => $no_rek,
            'kode_prepayment'  => $this->input->post('kode_prepayment'),
            'app_status'       => 'waiting',
            'app_date'         => null,
            'app_keterangan'   => null,
            'app2_status'      => 'waiting',
            'app2_date'        => null,
            'app2_keterangan'  => null,
            'status'           => 'on-process',
        ]);
 
        // Hapus baris yang dihapus user
        $deletedRows = json_decode($this->input->post('deleted_rows'), true);
        if (!empty($deletedRows)) {
            foreach ($deletedRows as $id2) {
                $rd = $this->db->get_where('mac_reimbust_detail', ['id'=>$id2])->row_array();
                if ($rd) {
                    if ($rd['kwitansi'] && $rd['kwitansi'] != 'default.jpg') {
                        @unlink(FCPATH.'./assets/backend/document/reimbust/kwitansi/kwitansi_mac/'.$rd['kwitansi']);
                    }
                    $this->db->where('id', $id2)->delete('mac_reimbust_detail');
                    if (!empty($rd['deklarasi'])) {
                        $this->db->update('mac_deklarasi', ['is_active'=>1], ['kode_deklarasi'=>$rd['deklarasi']]);
                    }
                }
            }
        }
 
        // Update/insert detail baris
        foreach ($pemakaian as $i => $p) {
            $kwitansi = '';
 
            if (!empty($_FILES['kwitansi']['name'][$i])) {
                $_FILES['file'] = [
                    'name'     => $_FILES['kwitansi']['name'][$i],
                    'type'     => $_FILES['kwitansi']['type'][$i],
                    'tmp_name' => $_FILES['kwitansi']['tmp_name'][$i],
                    'error'    => $_FILES['kwitansi']['error'][$i],
                    'size'     => $_FILES['kwitansi']['size'][$i],
                ];
 
                if ($_FILES['file']['size'] > 3072 * 1024) {
                    echo json_encode(["status"=>FALSE,"error"=>"Ukuran file melebihi 3 MB."]);
                    return;
                }
 
                $this->upload->initialize([
                    'upload_path'   => './assets/backend/document/reimbust/kwitansi/kwitansi_mac/',
                    'allowed_types' => 'jpeg|jpg|png|pdf',
                    'max_size'      => 3072,
                    'encrypt_name'  => TRUE,
                ]);
 
                if ($this->upload->do_upload('file')) {
                    $det_id = !empty($detail_id[$i]) ? $detail_id[$i] : null;
                    if ($det_id) {
                        $rd = $this->db->get_where('mac_reimbust_detail', ['id'=>$det_id])->row_array();
                        if ($rd && !empty($rd['kwitansi']) && $rd['kwitansi'] != 'default.jpg') {
                            @unlink(FCPATH.'./assets/backend/document/reimbust/kwitansi/kwitansi_mac/'.$rd['kwitansi']);
                        }
                    }
                    $kwitansi = $this->upload->data('file_name');
                } else {
                    echo json_encode(["status"=>FALSE,"error"=>$this->upload->display_errors()]);
                    return;
                }
            }
 
            $det_id = !empty($detail_id[$i]) ? $detail_id[$i] : null;
 
            $data2 = [
                'id'           => $det_id,
                'reimbust_id'  => $reimbust_id,
                'inventory_id' => !empty($inventory_id[$i]) ? (int)$inventory_id[$i] : null,
                'qty'          => !empty($qty[$i]) ? floatval($qty[$i]) : null,
                'tgl_nota'     => !empty($tgl_nota[$i]) ? date('Y-m-d', strtotime($tgl_nota[$i])) : date('Y-m-d'),
                'pemakaian'    => $pemakaian[$i],
                'jumlah'       => !empty($jumlahClean[$i]) ? $jumlahClean[$i] : 0,
                'kwitansi'     => !empty($kwitansi) ? $kwitansi : ($kwitansi_image[$i] ?? ''),
            ];
 
            if (isset($deklarasi[$i])) {
                $data2['deklarasi'] = $deklarasi[$i];
            }
 
            $kode_prepayment = $this->input->post('kode_prepayment');
            if (!empty($kode_prepayment)) {
                $this->db->update('mac_prepayment', ['is_active'=>0], ['kode_prepayment'=>$kode_prepayment]);
            }
            $kode_prepayment_old = $this->input->post('kode_prepayment_old');
            if ($kode_prepayment != $kode_prepayment_old && !empty($kode_prepayment_old)) {
                $this->db->update('mac_prepayment', ['is_active'=>1], ['kode_prepayment'=>$kode_prepayment_old]);
            }
 
            $this->db->replace('mac_reimbust_detail', $data2);
 
            if (isset($deklarasi_old[$i]) && $deklarasi_old[$i]) {
                $this->db->update('mac_deklarasi', ['is_active'=>1], ['kode_deklarasi'=>$deklarasi_old[$i]]);
                if (!empty($deklarasi[$i])) {
                    $this->db->update('mac_deklarasi', ['is_active'=>0], ['kode_deklarasi'=>$deklarasi[$i]]);
                }
            } elseif (!empty($deklarasi[$i])) {
                $this->db->update('mac_deklarasi', ['is_active'=>0], ['kode_deklarasi'=>$deklarasi[$i]]);
            }
        }
 
        echo json_encode(["status"=>TRUE]);
    }

    function delete($id)
    {
        $this->M_mac_reimbust->delete($id);
        echo json_encode(array("status" => TRUE));
    }

    //APPROVE DATA
    // public function approve()
    // {
    //     $data = array(
    //         'app_keterangan' => $this->input->post('app_keterangan'),
    //         'app_status' => $this->input->post('app_status'),
    //         'app_date' => date('Y-m-d H:i:s'),
    //     );

    //     // UPDATE STATUS DEKLARASI
    //     if ($this->input->post('app_status') === 'revised') {
    //         $data['status'] = 'revised';
    //     } elseif ($this->input->post('app_status') === 'approved') {
    //         $data['status'] = 'on-process';
    //     } elseif ($this->input->post('app_status') === 'rejected') {
    //         $data['status'] = 'rejected';
    //     }

    //     //UPDATE APPROVAL PERTAMA
    //     $this->db->where('id', $this->input->post('hidden_id'));
    //     $this->db->update('mac_reimbust', $data);

    //     echo json_encode(array("status" => TRUE));
    // }

    public function approve()
    {
        $reimbust_id = $this->input->post('hidden_id');

        // Guard: jika pelaporan kas lanjutan, skip approval pertama
        $reimbust = $this->db->select('is_kas_lanjutan, is_pelaporan_kas')
            ->where('id', $reimbust_id)
            ->get('mac_reimbust')->row();

        if ($reimbust && $reimbust->is_pelaporan_kas && $reimbust->is_kas_lanjutan) {
            echo json_encode([
                "status"  => FALSE,
                "error"   => "Pelaporan kas lanjutan tidak memerlukan approval pertama. Langsung ke approval kedua."
            ]);
            return;
        }

        $data = array(
            'app_keterangan' => $this->input->post('app_keterangan'),
            'app_status'     => $this->input->post('app_status'),
            'app_date'       => date('Y-m-d H:i:s'),
        );

        if ($this->input->post('app_status') === 'revised') {
            $data['status'] = 'revised';
        } elseif ($this->input->post('app_status') === 'approved') {
            $data['status'] = 'on-process';
        } elseif ($this->input->post('app_status') === 'rejected') {
            $data['status'] = 'rejected';
        }

        $this->db->where('id', $reimbust_id)->update('mac_reimbust', $data);

        echo json_encode(array("status" => TRUE));
    }

    //APPROVE DATA
    // public function approve2()
    // {
    //     $reimbust_id = $this->input->post('hidden_id');
    //     $app2_status = $this->input->post('app2_status');
 
    //     $this->db->where('id', $reimbust_id)->update('mac_reimbust', [
    //         'app2_status'    => $app2_status,
    //         'app2_date'      => date('Y-m-d H:i:s'),
    //         'app2_keterangan'=> $this->input->post('app2_keterangan'),
    //         'status'         => $app2_status === 'approved' ? 'approved'
    //                           : ($app2_status === 'rejected' ? 'rejected' : 'revised'),
    //     ]);
 
    //     // ==================================================
    //     // TITIK KONEKSI KE STOK
    //     // Hanya berjalan saat approval final disetujui dan
    //     // sifat_pelaporan adalah "Pelaporan" (barang masuk).
    //     // ==================================================
    //     if ($app2_status === 'approved') {
    //         $hasil = $this->_proses_stok_masuk($reimbust_id);
 
    //         if ($hasil !== true) {
    //             // Stok berhasil disimpan sebagian / ada error — tetap return
    //             // sukses ke UI (approval sudah tersimpan) tapi log errornya
    //             log_message('error', 'proses_stok_masuk error: '.print_r($hasil, true));
    //         }
    //     }
 
    //     echo json_encode(["status"=>TRUE]);
    // }

    public function approve2()
    {
        $reimbust_id = $this->input->post('hidden_id');
        $app2_status = $this->input->post('app2_status');

        // Ambil data reimbust untuk cek flag kas
        $reimbust = $this->db
            ->where('id', $reimbust_id)
            ->get('mac_reimbust')->row();

        if (!$reimbust) {
            echo json_encode(["status" => FALSE, "error" => "Data tidak ditemukan."]);
            return;
        }

        // Guard: jika kas lanjutan, hanya bhakti/dwi yang bisa approve2
        if ($reimbust->is_pelaporan_kas && $reimbust->is_kas_lanjutan) {
            $username = $this->session->userdata('username');
            if (!in_array($username, ['bhakti', 'dwi'])) {
                echo json_encode([
                    "status" => FALSE,
                    "error"  => "Pelaporan kas lanjutan hanya bisa disetujui oleh Bhakti atau Dwi."
                ]);
                return;
            }
        }

        // Update approval 2
        $this->db->where('id', $reimbust_id)->update('mac_reimbust', [
            'app2_status'     => $app2_status,
            'app2_date'       => date('Y-m-d H:i:s'),
            'app2_keterangan' => $this->input->post('app2_keterangan'),
            'status'          => $app2_status === 'approved' ? 'approved'
                            : ($app2_status === 'rejected' ? 'rejected' : 'revised'),
        ]);

        if ($app2_status === 'approved') {

            // ── TITIK KONEKSI KE STOK ─────────────────────────────────
            $hasil = $this->_proses_stok_masuk($reimbust_id);
            if ($hasil !== true) {
                log_message('error', 'proses_stok_masuk error: ' . print_r($hasil, true));
            }

            // ── UPDATE SALDO KAS ───────────────────────────────────────
            // Hanya jika pelaporan ini dari kas
            if ($reimbust->is_pelaporan_kas && $reimbust->kas_id) {
                $kas = $this->db->where('id', $reimbust->kas_id)
                    ->get('mac_kas')->row();

                if ($kas && $kas->status === 'aktif') {
                    $nominal_lapor    = floatval(preg_replace('/\D/', '',
                        $reimbust->total_nominal));
                    $total_dilaporkan = floatval($kas->total_dilaporkan) + $nominal_lapor;
                    $sisa_kas         = floatval($kas->nominal_awal) - $total_dilaporkan;

                    $this->db->where('id', $reimbust->kas_id)->update('mac_kas', [
                        'total_dilaporkan' => $total_dilaporkan,
                        'sisa_kas'         => max(0, $sisa_kas),
                        // Otomatis selesai jika sisa kas = 0
                        'status'           => $sisa_kas <= 0 ? 'selesai' : 'aktif',
                    ]);
                }
            }
        }

        echo json_encode(["status" => TRUE]);
    }

    // approved biasa sebelum di ubah
    // function approve2()
    // {
    //     $data = array(
    //         'app2_keterangan' => $this->input->post('app2_keterangan'),
    //         'app2_status' => $this->input->post('app2_status'),
    //         'app2_date' => date('Y-m-d H:i:s'),
    //     );

    //     // UPDATE STATUS DEKLARASI
    //     if ($this->input->post('app2_status') === 'revised') {
    //         $data['status'] = 'revised';
    //     } elseif ($this->input->post('app2_status') === 'approved') {
    //         $data['status'] = 'approved';
    //     } elseif ($this->input->post('app2_status') === 'rejected') {
    //         $data['status'] = 'rejected';
    //     }

    //     // UPDATE APPROVAL 2
    //     $this->db->where('id', $this->input->post('hidden_id'));
    //     $this->db->update('mac_reimbust', $data);

    //     echo json_encode(array("status" => TRUE));
    // }

    private function _proses_stok_masuk($reimbust_id)
    {
        $master = $this->M_mac_reimbust->get_by_id($reimbust_id);

        if (!$master) {
            return ['Data reimbust tidak ditemukan'];
        }

        if (empty($master->cabang_id)) {
            return ['Cabang pada reimbust belum ditentukan.'];
        }

        $cabang_id = (int) $master->cabang_id;

        // UBAH 1: izinkan Pelaporan DAN Reimbust
        if (!in_array($master->sifat_pelaporan, ['Pelaporan', 'Reimbust'])) {
            return true;
        }

        $details    = $this->M_mac_reimbust->get_by_id_detail($reimbust_id);
        $created_by = $this->session->userdata('username');
        $tanggal    = !empty($master->tgl_pengajuan)
                        ? $master->tgl_pengajuan
                        : date('Y-m-d');

        $errors = [];

        foreach ($details as $row) {
            if (
                empty($row['inventory_id']) ||
                empty($row['qty']) ||
                floatval($row['qty']) <= 0
            ) {
                continue;
            }

            // UBAH 2: referensi_tipe ikut sifat_pelaporan (bukan hardcode 'Pelaporan')
            if ($this->M_mac_inventory_stok->sudah_diproses(
                $master->sifat_pelaporan,
                $row['id'],
                $cabang_id
            )) {
                continue;
            }

            $qty_row    = (float) $row['qty'];
            $harga_beli = (float) $row['jumlah'];

            $batch_id = $this->M_mac_inventory_stok->tambah_stok_masuk(
                (int) $row['inventory_id'],
                $cabang_id,
                $qty_row,
                $harga_beli,
                $tanggal,
                $master->sifat_pelaporan, // UBAH 3: ikut sifat, bukan hardcode
                (int) $row['id'],
                $created_by
            );

            if (!$batch_id) {
                $errors[] = "Gagal proses stok untuk detail ID {$row['id']}";
            }
        }

        return empty($errors) ? true : $errors;
    }

    // GET INVENTORY UNTUK SELECT2 DI FORM REIMBUST
    public function get_inventory_options_ajax()
    {
        $search = $this->input->post('search');
        $session_cabang = (int)$this->session->userdata('cabang_id');

        $this->db->select("
            i.id,
            i.kode_produk,
            i.nama_produk,
            i.satuan,
            COALESCE(s.stok_saat_ini,0) as stok_fisik
        ", FALSE);

        $this->db->from('mac_inventory i');

        $this->db->join(
            'mac_inventory_stok s',
            's.inventory_id = i.id
            AND s.cabang_id = '.$session_cabang,
            'left'
        );

        $this->db->where('i.is_active', 1);
        $this->db->where('i.kategori !=', 'Jasa');

        if (!empty($search)) {
            $this->db->group_start()
                ->like('i.nama_produk', $search)
                ->or_like('i.kode_produk', $search)
                ->group_end();
        }

        $this->db->order_by('i.nama_produk', 'ASC');

        $items = $this->db->get()->result();

        $this->load->model('backend/M_mac_inventory_stok');

        foreach ($items as &$item) {
            $item->stok_aktual = $this->M_mac_inventory_stok->get_stok(
                $item->id,
                false,
                $session_cabang
            );
        }

        echo json_encode($items);
    }

    public function payment()
    {
        $id = $this->input->post('id');
        $payment_status = $this->input->post('payment_status');
        $tgl_pembayaran = $this->input->post('tgl_pembayaran');
        // Validasi dasar
        if (empty($id)) {
            echo json_encode([
                "status" => FALSE,
                "message" => "ID tidak ditemukan"
            ]);
            return;
        }
        // Data awal
        $data = [
            'payment_status' => $payment_status
        ];
        // Jika status paid → set tanggal (tanpa jam)
        if ($payment_status === 'paid' && !empty($tgl_pembayaran)) {
            $date_parts = explode('-', $tgl_pembayaran);
            if (count($date_parts) === 3) {
                // DD-MM-YYYY → YYYY-MM-DD H:i:s
                $data['tgl_pembayaran'] = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0] . ' ' . date('H:i:s');
            } else {
                echo json_encode([
                    "status" => FALSE,
                    "message" => "Format tanggal tidak valid"
                ]);
                return;
            }
        } else {
            // Hapus file attachment jika status bukan paid
            $reimbust = $this->db->get_where('mac_reimbust', ['id' => $id])->row_array();

            if ($reimbust && $reimbust['attachment'] && $reimbust['attachment'] != 'default.pdf') {
                @unlink(FCPATH . './assets/backend/document/reimbust/attachment/mac_attachment/' . $reimbust['attachment']);
                }
                $data['tgl_pembayaran'] = null;
                $data['attachment'] = null;
        }

        // Ambil data lama
        $reimbust = $this->db->get_where('mac_reimbust', ['id' => $id])->row_array();

        // Jika ada file lama, hapus dulu (replace)
        if (!empty($_FILES['attachment']['name'])) {
            if ($reimbust && !empty($reimbust['attachment']) && $reimbust['attachment'] != 'default.pdf') {
                $oldFile = FCPATH . 'assets/backend/document/reimbust/attachment/mac_attachment/' . $reimbust['attachment'];
                
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }
        }

        // Handle upload file
        if (!empty($_FILES['attachment']['name'])) {
            $config['upload_path'] = 'assets/backend/document/reimbust/attachment/mac_attachment/';
            $config['allowed_types'] = 'pdf|jpg|jpeg|png';
            $config['max_size'] = 3072; // 3MB
            $config['file_name'] = 'attachment_' . $id . '_' . date('YmdHis');
            $this->load->library('upload', $config);
            if ($this->upload->do_upload('attachment')) {
                $upload_data = $this->upload->data();
                $data['attachment'] = $upload_data['file_name'];
            } else {
                echo json_encode([
                    "status" => FALSE,
                    "message" => strip_tags($this->upload->display_errors())
                ]);
                return;
            }
        }
        // Update database
        $this->db->where('id', $id);
        $result = $this->db->update('mac_reimbust', $data);
        if ($result) {
            echo json_encode([
                "status" => TRUE
            ]);
        } else {
            echo json_encode([
                "status" => FALSE,
                "message" => "Gagal update data"
            ]);
        }
    }

    public function generate_pdf($id)
    {
        // Load FPDF library
        $this->load->library('Fpdf_generate');

        // Load data from database based on $id
        $data['master'] = $this->M_mac_reimbust->get_by_id($id);
        $data['transaksi'] = $this->M_mac_reimbust->get_by_id_detail($id);
        $data['user'] = $this->db->select('name')
            ->from('tbl_data_user')
            ->where('id_user', $data['master']->id_user)
            ->get()
            ->row('name');
        $data['app_status'] = strtoupper($data['master']->app_status);
        $data['app2_status'] = strtoupper($data['master']->app2_status);

        // Start FPDF
        $pdf = new FPDF('L', 'mm', 'Letter');
        $pdf->SetTitle('Form Pengajuan Reimbust');
        $pdf->AddPage();

        // Mengatur margin kiri, atas, dan kanan
        $pdf->SetMargins(10, 10, 0); // Margin kiri 20mm, atas 10mm, kanan 20mm

        // Mengatur margin bawah (dan aktifkan auto page break)
        $pdf->SetAutoPageBreak(true, 5); // Margin bawah 15mm

        // Logo
        $pdf->Image(base_url('') . '/assets/backend/img/mobileautocare.png', 11, 5, 45, 25);

        // Set font
        $pdf->AddFont('Poppins-Regular', '', 'Poppins-Regular.php');
        $pdf->AddFont('Poppins-Bold', '', 'Poppins-Bold.php');

        $pdf->SetFont('Poppins-Bold', '', 16);

        // Teks yang ingin ditampilkan
        $text1 = 'FORM PELAPORAN / REIMBUST';
        $text2 = 'MAC';

        // Menghitung lebar teks
        $textWidth1 = $pdf->GetStringWidth($text1);
        $textWidth2 = $pdf->GetStringWidth($text2);

        // Menghitung posisi X agar teks berada di tengah halaman
        $pageWidth = $pdf->GetPageWidth();
        $x1 = ($pageWidth - $textWidth1) / 2;
        $x2 = ($pageWidth - $textWidth2) / 2;

        // Menempatkan teks di tengah halaman secara horizontal
        $pdf->SetXY($x1, 9); // Y posisi diatur dengan nilai tetap
        $pdf->Cell($textWidth1, 10, $text1, 0, 1, 'C');

        $pdf->SetXY($x2, 18); // Y posisi diatur dengan nilai tetap
        $pdf->Cell($textWidth2, 10, $text2, 0, 1, 'C');

        // Enter
        $pdf->SetY(35); // Posisi Y (vertikal) dari garis
        $pdf->SetX(10); // Posisi X (horizontal) dari garis

        // Field Master

        $pdf->SetFont('Poppins-Regular', '', 10);

        function Cell($pdf, $width, $height, $text, $align = 'L', $fill = false)
        {
            $pdf->Cell($width, $height, $text, 0, 0, $align, $fill);
        }

        // Function to create a row in the table
        function Row($pdf, $height, $data, $widths, $fill = false)
        {
            $pdf->SetX(10); // Start at X position

            for ($i = 0; $i < count($data); $i++) {
                // Adjust the width of columns as needed to reduce space
                $pdf->Cell($widths[$i], $height, $data[$i], 0, 0, 'L', $fill);
            }

            $pdf->Ln(); // Move to the next line
        }

        // Set column widths (adjust widths as necessary to reduce spacing)
        $widths = array(43, 3.5, 60); // Adjusted width for the columns

        $tanggal = $data['master']->tgl_pengajuan;
        $formatted_date = date('d F Y', strtotime($tanggal));

        // Array untuk mengubah nama bulan
        $months = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember'
        ];

        // Mengganti nama bulan
        $month = date('F', strtotime($tanggal));
        $translated_month = $months[$month];
        $formatted_date = str_replace($month, $translated_month, $formatted_date);

        // Add some data with adjusted column widths
        Row($pdf, 10, array('NAMA', ':', $data['user']), $widths, false);
        $pdf->Ln(-3);
        // Row($pdf, 10, array('JABATAN', ':', $data['master']->jabatan), $widths, false);
        // $pdf->Ln(-3);
        // Row($pdf, 10, array('DEPARTEMEN', ':', $data['master']->departemen), $widths, false);
        // $pdf->Ln(-3);
        Row($pdf, 10, array('SIFAT PELAPORAN', ':', $data['master']->sifat_pelaporan), $widths, false);
        $pdf->Ln(-3);
        Row($pdf, 10, array('TANGGAL', ':', $formatted_date), $widths, false);
        $pdf->Ln(-3);
        Row($pdf, 10, array('TUJUAN', ':',  $data['master']->tujuan), $widths, false);

        // Set font for the table header
        $pdf->SetY($pdf->GetY() + -1); // Move down from previous cell

        $jumlah_prepayment = number_format($data['master']->jumlah_prepayment, 0, ',', '.');

        // Add table headers
        // Tambahkan "JUMLAH PREPAYMENT" dalam satu Cell
        $pdf->SetFont('Poppins-Regular', '', 9);
        $pdf->Cell(193, 7, 'No. Prepayment : ' . (!empty($data['master']->kode_prepayment) ? $data['master']->kode_prepayment : '-'), 0, 1, 'R');

        $pdf->SetFont('Poppins-Bold', '', 10);

        $pdf->Cell(193, 8.5, 'JUMLAH PREPAYMENT', 1, 0, 'L');
        $pdf->Cell(66, 8.5, 'BUKTI PENGELUARAN', 1, 0, 'C');

        // Tambahkan "Rp. 500.000" dalam Cell terpisah, dengan posisi terpisah dari teks
        $pdf->Cell(-67, 8.5, 'Rp. ' . $jumlah_prepayment, 0, 1, 'R');
        $pdf->Cell(120, 8.5, 'PEMAKAIAN', 1);
        $pdf->Cell(40, 8.5, 'TGL NOTA', 1, 0, 'C');
        $pdf->Cell(33, 8.5, 'JUMLAH', 1, 0, 'C');
        $pdf->Cell(33, 8.5, 'KWITANSI', 1, 0, 'C');
        $pdf->Cell(33, 8.5, 'DEKLARASI', 1, 1, 'C');

        // Set font for table body
        $pdf->SetFont('Poppins-Regular', '', 10);

        // Add table data
        $no = 1;
        $totalJumlah = 0;
        $jumlahPengurangan = $data['master']->jumlah_prepayment;
        foreach ($data['transaksi'] as $row) {
            $tanggal = $row['tgl_nota'];
            $formatted_date = date('d F Y', strtotime($tanggal));

            // Array untuk mengubah nama bulan
            $months = [
                'January' => 'Januari',
                'February' => 'Februari',
                'March' => 'Maret',
                'April' => 'April',
                'May' => 'Mei',
                'June' => 'Juni',
                'July' => 'Juli',
                'August' => 'Agustus',
                'September' => 'September',
                'October' => 'Oktober',
                'November' => 'November',
                'December' => 'Desember'
            ];

            // Mengganti nama bulan
            $month = date('F', strtotime($tanggal));
            $translated_month = $months[$month];
            $formatted_date = str_replace($month, $translated_month, $formatted_date);

            $pdf->Cell(120, 8.5, $no++ . '. ' . $row['pemakaian'], 1);
            $pdf->Cell(40, 8.5, $formatted_date, 1, 0, 'C');
            $jumlah = $row['jumlah'];

            $totalJumlah += $jumlah;
            $sisaPrepayment = $jumlahPengurangan - $totalJumlah;

            $pdf->Cell(33, 8.5, number_format($jumlah, 0, ',', '.'), 1, 0, 'C');
            $pdf->Cell(33, 8.5, $row['kwitansi'] ? 'Ada' : '-', 1, 0, 'C');
            $pdf->Cell(33, 8.5, $row['deklarasi'] ? 'Ada' : '-', 1, 1, 'C');
        }

        // Add total and remaining mac_prepayment
        $pdf->SetFont('Poppins-Bold', '', 10);
        $pdf->Cell(259, 8.5, 'TOTAL PEMAKAIAN', 1, 0, 'L');
        $pdf->Cell(-1, 8.5, 'Rp. ' . number_format($totalJumlah, 0, ',', '.'), 0, 1, 'R');
        $pdf->Cell(259, 8.5, 'SISA PREPAYMENT', 1, 0, 'L');
        $pdf->Cell(-1, 8.5, 'Rp. ' . number_format($sisaPrepayment, 0, ',', '.'), 0, 1, 'R');

        $pdf->Ln(10);

        $pdf->SetFont('Poppins-Regular', '', 10);
        $pdf->Cell(50, 8.5, 'YANG MELAKUKAN', 1, 0, 'C');
        $pdf->Cell(50, 8.5, 'MENGETAHUI', 1, 0, 'C');
        $pdf->Cell(50, 8.5, 'MENYETUJUI', 1, 1, 'C');

        $pdf->Cell(50, 13.5, 'CREATED', 0, 0, 'C');

        $x = $pdf->GetX();
        $y = $pdf->GetY();

        $pdf->SetXY($x + -50, $y + 0); // Menambahkan margin horizontal dan vertikal
        $pdf->Cell(50, 18, '', 1, 0, 'C');

        // Kembali ke posisi sebelumnya untuk elemen berikutnya
        $pdf->SetXY($x + 0, $y); // Mengatur posisi untuk elemen berikutnya jika diperlukan

        // Menyimpan posisi saat ini
        $x = $pdf->GetX();
        $y = $pdf->GetY();

        // Mengatur posisi X dan Y dengan margin tambahan untuk teks tanggal
        $pdf->SetXY($x + -50, $y + 4.5); // Menambahkan margin horizontal dan vertikal

        // Menggunakan Cell() untuk mencetak teks tanggal dengan margin
        $pdf->Cell(50, 15, date('d-m-Y H:i:s', strtotime($data['master']->created_at)), 0, 0, 'C');

        // Kembali ke posisi sebelumnya untuk elemen berikutnya
        $pdf->SetXY($x + 0, $y); // Mengatur posisi untuk elemen berikutnya jika diperlukan

        // Approval 1
        $pdf->Cell(50, 13.5, strtoupper($data['master']->app_status), 0, 0, 'C');

        $x = $pdf->GetX();
        $y = $pdf->GetY();

        $pdf->SetXY($x + -50, $y + 0); // Menambahkan margin horizontal dan vertikal
        $pdf->Cell(50, 18, '', 1, 0, 'C');

        // Kembali ke posisi sebelumnya untuk elemen berikutnya
        $pdf->SetXY($x + 0, $y); // Mengatur posisi untuk elemen berikutnya jika diperlukan

        // Menyimpan posisi saat ini
        $x = $pdf->GetX();
        $y = $pdf->GetY();

        // Mengatur posisi X dan Y dengan margin tambahan untuk teks tanggal
        $pdf->SetXY($x + -50, $y + 4.5); // Menambahkan margin horizontal dan vertikal

        if ($data['master']->app_date == null) {
            $date = '';
        }
        if ($data['master']->app_date != null) {
            $date = date('d-m-Y H:i:s', strtotime($data['master']->app_date));
        }

        // Menggunakan Cell() untuk mencetak teks tanggal dengan margin
        $pdf->Cell(50, 15, $date, 0, 0, 'C');

        // Kembali ke posisi sebelumnya untuk elemen berikutnya
        $pdf->SetXY($x + 0, $y); // Mengatur posisi untuk elemen berikutnya jika diperlukan

        // Approval 2
        $pdf->Cell(50, 13.5, strtoupper($data['master']->app2_status), 0, 0, 'C');

        $x = $pdf->GetX();
        $y = $pdf->GetY();

        $pdf->SetXY($x + -50, $y + 0); // Menambahkan margin horizontal dan vertikal
        $pdf->Cell(50, 18, '', 1, 0, 'C');

        // Kembali ke posisi sebelumnya untuk elemen berikutnya
        $pdf->SetXY($x + 0, $y); // Mengatur posisi untuk elemen berikutnya jika diperlukan

        // Menyimpan posisi saat ini
        $x = $pdf->GetX();
        $y = $pdf->GetY();

        // Mengatur posisi X dan Y dengan margin tambahan untuk teks tanggal
        $pdf->SetXY($x + -50, $y + 4.5); // Menambahkan margin horizontal dan vertikal

        if ($data['master']->app2_date == null) {
            $date2 = '';
        }
        if ($data['master']->app2_date != null) {
            $date2 = date('d-m-Y H:i:s', strtotime($data['master']->app2_date));
        }

        // Menggunakan Cell() untuk mencetak teks tanggal dengan margin
        $pdf->Cell(50, 15, $date2, 0, 0, 'C');

        // Kembali ke posisi sebelumnya untuk elemen berikutnya 
        $pdf->SetXY($x + -150, $y + 18); // Mengatur posisi untuk elemen berikutnya jika diperlukan

        // Menulis elemen selanjutnya dengan ukuran baris yang lebih kecil
        $pdf->Cell(50, 8.5, $data['user'], 1, 0, 'C');
        $pdf->Cell(50, 8.5, $data['master']->app4_name, 1, 1, 'C');
        $pdf->Cell(50, 8.5, $data['master']->app_name, 1, 0, 'C');
        $pdf->Cell(50, 8.5, $data['master']->app2_name, 1, 1, 'C');


        // Output the PDF
        $pdf->Output('I', 'mac_reimbust - ' . $data['master']->kode_reimbust . '.pdf');
    }
}
