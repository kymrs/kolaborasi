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

        // INISIASI VARIABEL INPUT DETAIL REIMBUST
        $pemakaian = $this->input->post('pemakaian');
        $tgl_nota = $this->input->post('tgl_nota');
        $jumlah = $this->input->post('jumlah');

        // PERULANGAN UNTUK INSER QUERY DETAIL REIMBUST
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

                // Cek jika ukuran file melebihi batas
                if ($_FILES['file']['error'] == UPLOAD_ERR_INI_SIZE || $_FILES['file']['size'] > 3072 * 1024) {
                    echo json_encode(array("status" => FALSE, "error" => "Ukuran file tidak boleh melebihi dari 3 MB."));
                    return;
                }

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

            $reimbust_id = $this->M_reimbust->save($data1);

            $data2[] = [
                'reimbust_id' => $reimbust_id,
                'pemakaian' => $pemakaian[$i],
                'tgl_nota' => date('Y-m-d', strtotime($tgl_nota[$i])),
                'jumlah' => $jumlah[$i],
                'kwitansi' => $kwitansi
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

        //UPDATE DETAIL PREPAYMENT
        $detail_id = $this->input->post('detail_id');
        $reimbust_id = $this->input->post('id');
        $pemakaian = $this->input->post('pemakaian');
        $jumlah = $this->input->post('jumlah');
        $tgl_nota = $this->input->post('tgl_nota');
        $kwitansi_image = $this->input->post('kwitansi_image');

        if ($this->db->update('tbl_reimbust', $data)) {
            // UNTUK MENGHAPUS ROW YANG TELAH DIDELETE
            $deletedRows = json_decode($this->input->post('deleted_rows'), true);
            if (!empty($deletedRows)) {
                foreach ($deletedRows as $id2) {
                    // Hapus row dari database berdasarkan id
                    $reimbust_detail = $this->db->get_where('tbl_reimbust_detail', ['id' => $id2])->row_array();

                    $old_image = $reimbust_detail['kwitansi'];
                    if ($old_image != 'default.jpg') {
                        unlink(FCPATH . './assets/backend/img/reimbust/kwitansi/' . $old_image);
                    }

                    $this->db->where('id', $id2);
                    $this->db->delete('tbl_reimbust_detail');
                }
            }

            //MELAKUKAN REPLACE DATA LAMA DENGAN YANG BARU
            for ($i = 1; $i <= count($_POST['pemakaian']); $i++) {

                if (!empty($_FILES['kwitansi']['name'][$i])) {
                    $_FILES['file']['name'] = $_FILES['kwitansi']['name'][$i];
                    $_FILES['file']['type'] = $_FILES['kwitansi']['type'][$i];
                    $_FILES['file']['tmp_name'] = $_FILES['kwitansi']['tmp_name'][$i];
                    $_FILES['file']['error'] = $_FILES['kwitansi']['error'][$i];
                    $_FILES['file']['size'] = $_FILES['kwitansi']['size'][$i];

                    // Cek jika ukuran file melebihi batas
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

                            if ($old_image) {
                                if ($old_image != 'default.jpg') {
                                    unlink(FCPATH . './assets/backend/img/reimbust/kwitansi/' . $old_image);
                                }
                            }
                        }
                        $kwitansi = $this->upload->data('file_name');
                    } else {
                        echo json_encode(array("status" => FALSE, "error" => $this->upload->display_errors()));
                        return;
                    }
                }

                // Set id menjadi NULL jika reimbust_id tidak ada atau kosong
                $id = !empty($detail_id[$i]) ? $detail_id[$i] : NULL;

                if (!empty($kwitansi)) {
                    $data2[] = array(
                        'id' => $id,
                        'reimbust_id' => $reimbust_id,
                        'tgl_nota' => date('Y-m-d', strtotime($tgl_nota[$i])),
                        'pemakaian' => $pemakaian[$i],
                        'jumlah' => $jumlah[$i],
                        'kwitansi' => $kwitansi
                    );
                } else {
                    $data2[] = array(
                        'id' => $id,
                        'reimbust_id' => $reimbust_id,
                        'tgl_nota' => date('Y-m-d', strtotime($tgl_nota[$i])),
                        'pemakaian' => $pemakaian[$i],
                        'jumlah' => $jumlah[$i],
                        'kwitansi' => $kwitansi_image[$i]
                    );
                }

                // Menggunakan db->replace untuk memasukkan atau menggantikan data
                $this->db->replace('tbl_reimbust_detail', $data2[$i - 1]);
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
