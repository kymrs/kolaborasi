<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Reimbust extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_reimbust');
        $this->M_login->getsecurity();
    }

    public function index()
    {
        $data['title'] = "backend/reimbust/reimbust_list";
        $data['titleview'] = "Data Reimbust";
        $this->load->view('backend/home', $data);
    }

    function get_list()
    {
        $list = $this->M_reimbust->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<a href="reimbust/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-eye"></i></a>
            <a href="reimbust/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
			<a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>';
            $row[] = $field->kode_reimbust;
            $row[] = $field->nama;
            $row[] = $field->jabatan;
            $row[] = $field->departemen;
            $row[] = $field->sifat_pelaporan;
            $row[] = date("d m Y", strtotime($field->tgl_pengajuan));
            $row[] = $field->tujuan;
            $row[] = number_format($field->jumlah_prepayment, 0, ',', '.');;
            $row[] = $field->status;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_reimbust->count_all(),
            "recordsFiltered" => $this->M_reimbust->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    function read_form($id)
    {
        $data['aksi'] = 'read';
        $data['id'] = $id;
        $data['title_view'] = "Data Reimbust";
        $data['title'] = 'backend/reimbust/reimbust_form';
        $this->db->select('kwitansi');
        $this->db->where('reimbust_id', $id);
        $data['kwitansi'] = $this->db->get('tbl_reimbust_detail')->result_array();
        $this->load->view('backend/home', $data);
    }

    public function add_form()
    {
        $kode = $this->M_reimbust->max_kode()->row();
        if (empty($kode->kode_reimbust)) {
            $no_urut = 1;
        } else {
            $bln = substr($kode->kode_reimbust, 3, 2);
            if ($bln != date('m')) {
                $no_urut = 1;
            } else {
                $no_urut = substr($kode->kode_reimbust, 5) + 1;
            }
        }
        $urutan = str_pad($no_urut, 3, "0", STR_PAD_LEFT);
        $data['kode'] = 'B' . date('ym') . $urutan;
        $data['id'] = 0;
        $data['aksi'] = 'add';
        $data['title_view'] = "Data Reimbust Form";
        $data['title'] = 'backend/reimbust/reimbust_form';
        $this->load->view('backend/home', $data);
    }

    public function generate_kode()
    {
        $date = $this->input->post('date');
        $kode = $this->M_reimbust->max_kode($date)->row();
        if (empty($kode->kode_reimbust)) {
            $no_urut = 1;
        } else {
            $bln = substr($kode->kode_reimbust, 3, 2);
            if ($bln != date('m')) {
                $no_urut = 1;
            } else {
                $no_urut = substr($kode->kode_reimbust, 5) + 1;
            }
        }
        $urutan = str_pad($no_urut, 4, "0", STR_PAD_LEFT);
        $month = substr($date, 3, 2);
        $year = substr($date, 8, 2);
        $data = 'p' . $year . $month . $urutan;
        echo json_encode($data);
    }

    function edit_form($id)
    {
        $data['id'] = $id;
        $data['aksi'] = 'update';
        $data['title_view'] = "Edit Data Reimbust";
        $data['title'] = 'backend/reimbust/reimbust_form';
        $this->load->view('backend/home', $data);
    }

    function edit_data($id)
    {
        $data['master'] = $this->M_reimbust->get_by_id($id);
        $data['transaksi'] = $this->M_reimbust->get_by_id_detail($id);
        echo json_encode($data);
    }

    function read_detail($id)
    {
        $data = $this->M_reimbust->get_by_id_detail($id);
        echo json_encode($data);
    }

    public function add()
    {
        // Load library upload
        $this->load->library('upload');

        // INISIASI VARIABEL INPUT DETAIL REIMBUST
        $pemakaian = $this->input->post('pemakaian');
        $tgl_nota = $this->input->post('tgl_nota');
        $jumlah = $this->input->post('jumlah');
        $deklarasi = $this->input->post('deklarasi');

        // PERULANGAN UNTUK CEK UKURAN FILE
        for ($i = 1; $i <= count($pemakaian); $i++) {
            if (!empty($_FILES['kwitansi']['name'][$i])) {
                if ($_FILES['kwitansi']['size'][$i] > 3072 * 1024) { // 3 MB in KB
                    echo json_encode(array("status" => FALSE, "error" => "Ukuran file tidak boleh melebihi dari 3 MB untuk file ke-$i."));
                    return;
                }
            }
        }

        // Inisialisasi data untuk tabel reimbust
        $data1 = array(
            'kode_reimbust' => $this->input->post('kode_reimbust'),
            'nama' => $this->input->post('nama'),
            'jabatan' => $this->input->post('jabatan'),
            'departemen' => $this->input->post('departemen'),
            'sifat_pelaporan' => $this->input->post('sifat_pelaporan'),
            'tgl_pengajuan' => date('Y-m-d', strtotime($this->input->post('tgl_pengajuan'))),
            'tujuan' => $this->input->post('tujuan'),
            'status' => $this->input->post('status'),
            'jumlah_prepayment' => $this->input->post('jumlah_prepayment')
        );

        // Hanya simpan ke database jika tidak ada file yang melebihi 3 MB
        $reimbust_id = $this->M_reimbust->save($data1);

        $data2 = [];
        for ($i = 1; $i <= count($pemakaian); $i++) {
            $kwitansi = null;

            // Handle upload file untuk 'kwitansi'
            if (!empty($_FILES['kwitansi']['name'][$i])) {
                $_FILES['file']['name'] = $_FILES['kwitansi']['name'][$i];
                $_FILES['file']['type'] = $_FILES['kwitansi']['type'][$i];
                $_FILES['file']['tmp_name'] = $_FILES['kwitansi']['tmp_name'][$i];
                $_FILES['file']['error'] = $_FILES['kwitansi']['error'][$i];
                $_FILES['file']['size'] = $_FILES['kwitansi']['size'][$i];

                $config['upload_path'] = './assets/backend/img/reimbust/kwitansi/';
                $config['allowed_types'] = 'jpg|png';
                $config['max_size'] = 3072; // Batasan ukuran file dalam kilobytes (3 MB)
                $config['encrypt_name'] = TRUE;

                $this->upload->initialize($config);

                if ($this->upload->do_upload('file')) {
                    $kwitansi = $this->upload->data('file_name');
                } else {
                    echo json_encode(array("status" => FALSE, "error" => $this->upload->display_errors()));
                    return;
                }
            }

            $data2[] = [
                'reimbust_id' => $reimbust_id,
                'pemakaian' => $pemakaian[$i],
                'tgl_nota' => !empty($tgl_nota[$i]) ? date('Y-m-d', strtotime($tgl_nota[$i])) : date('Y-m-d'),
                'jumlah' => $jumlah[$i],
                'kwitansi' => $kwitansi,
                'deklarasi' => $deklarasi[$i]
            ];
        }

        $this->M_reimbust->save_detail($data2);
        echo json_encode(array("status" => TRUE));
    }


    public function update()
    {
        $this->load->library('upload');

        $data = array(
            'nama' => $this->input->post('nama'),
            'jabatan' => $this->input->post('jabatan'),
            'departemen' => $this->input->post('departemen'),
            'sifat_pelaporan' => $this->input->post('sifat_pelaporan'),
            'tgl_pengajuan' => date('Y-m-d', strtotime($this->input->post('tgl_pengajuan'))),
            'tujuan' => $this->input->post('tujuan'),
            'status' => $this->input->post('status')
        );

        $this->db->where('id', $this->input->post('id'));

        $detail_id = $this->input->post('detail_id');
        $reimbust_id = $this->input->post('id');
        $pemakaian = $this->input->post('pemakaian');
        $jumlah = $this->input->post('jumlah');
        $tgl_nota = $this->input->post('tgl_nota');
        $kwitansi_image = $this->input->post('kwitansi_image');
        $deklarasi = $this->input->post('deklarasi');

        if ($this->db->update('tbl_reimbust', $data)) {
            // 1. Hapus Baris yang Telah Dihapus
            $deletedRows = json_decode($this->input->post('deleted_rows'), true);
            if (!empty($deletedRows)) {
                foreach ($deletedRows as $id2) {
                    $reimbust_detail = $this->db->get_where('tbl_reimbust_detail', ['id' => $id2])->row_array();

                    if ($reimbust_detail) {
                        $old_image = $reimbust_detail['kwitansi'];
                        if ($old_image != 'default.jpg') {
                            @unlink(FCPATH . './assets/backend/img/reimbust/kwitansi/' . $old_image);
                        }

                        $this->db->where('id', $id2);
                        $this->db->delete('tbl_reimbust_detail');
                    }
                }
            }

            // 2. Replace Data Lama dengan yang Baru
            foreach ($pemakaian as $i => $p) {
                $kwitansi = ''; // Inisialisasi variabel kwitansi

                // Cek apakah file kwitansi untuk indeks $i ada
                if (isset($_FILES['kwitansi']['name'][$i]) && !empty($_FILES['kwitansi']['name'][$i])) {
                    $_FILES['file']['name'] = $_FILES['kwitansi']['name'][$i];
                    $_FILES['file']['type'] = $_FILES['kwitansi']['type'][$i];
                    $_FILES['file']['tmp_name'] = $_FILES['kwitansi']['tmp_name'][$i];
                    $_FILES['file']['error'] = $_FILES['kwitansi']['error'][$i];
                    $_FILES['file']['size'] = $_FILES['kwitansi']['size'][$i];

                    if ($_FILES['file']['error'] == UPLOAD_ERR_INI_SIZE || $_FILES['file']['size'] > 3072 * 1024) {
                        echo json_encode(array("status" => FALSE, "error" => "Ukuran file tidak boleh melebihi dari 3 MB."));
                        return;
                    }

                    $config['upload_path'] = './assets/backend/img/reimbust/kwitansi/';
                    $config['allowed_types'] = 'jpg|png';
                    $config['max_size'] = 3072;
                    $config['encrypt_name'] = TRUE;

                    $this->upload->initialize($config);

                    if ($this->upload->do_upload('file')) {
                        $id = !empty($detail_id[$i]) ? $detail_id[$i] : NULL;

                        $reimbust_detail = $this->db->get_where('tbl_reimbust_detail', ['id' => $id])->row_array();

                        if ($reimbust_detail) {
                            $old_image = $reimbust_detail['kwitansi'];

                            if ($old_image && $old_image != 'default.jpg') {
                                @unlink(FCPATH . './assets/backend/img/reimbust/kwitansi/' . $old_image);
                            }
                        }
                        $kwitansi = $this->upload->data('file_name');
                    } else {
                        echo json_encode(array("status" => FALSE, "error" => $this->upload->display_errors()));
                        return;
                    }
                }

                $id = !empty($detail_id[$i]) ? $detail_id[$i] : NULL;

                $data2 = array(
                    'id' => $id,
                    'reimbust_id' => $reimbust_id,
                    'tgl_nota' => !empty($tgl_nota[$i]) ? date('Y-m-d', strtotime($tgl_nota[$i])) : date('Y-m-d'),
                    'pemakaian' => $pemakaian[$i],
                    'jumlah' => $jumlah[$i],
                    'kwitansi' => !empty($kwitansi) ? $kwitansi : (isset($kwitansi_image[$i]) ? $kwitansi_image[$i] : ''),
                    'deklarasi' => $deklarasi[$i]
                );

                // Replace data di tbl_reimbust_detail
                $this->db->replace('tbl_reimbust_detail', $data2);
            }
        }
        echo json_encode(array("status" => TRUE));
    }

    function delete($id)
    {
        $this->M_reimbust->delete($id);
        echo json_encode(array("status" => TRUE));
    }
}
