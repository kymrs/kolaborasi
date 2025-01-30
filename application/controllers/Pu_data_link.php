<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pu_data_link extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
	}

	public function index()
	{
		$this->M_login->getsecurity();
		$data['title'] = "backend/pu_data_link/pu_data_link_list";
		$data['titleview'] = "Data Link Crew & Member";
		$this->load->view('backend/home', $data);
	}

	function get_list1()
	{
		// INISIALISASI VARIABEL YANG DIBUTUHKAN
		$fullname = $this->db->select('name')
			->from('tbl_data_user')
			->where('id_user', $this->session->userdata('id_user'))
			->get()
			->row('name');

		// Membaca data dari file JSON
		$file_path = FCPATH . '../linkgroup/link.pengenumroh/data-crew.json';

		if (file_exists($file_path)) {
			$json_data = file_get_contents($file_path);
			$data = json_decode($json_data, true); // Mengubah JSON menjadi array

			$list = $data; // Semua data yang sudah dibaca dari JSON
		} else {
			$list = array();
		}

		$data = array();
		$no = $_POST['start'];

		$akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
		$edit = $akses->edit_level;
		$delete = $akses->delete_level;

		foreach ($list as $field) {

			// MENENTUKAN ACTION APA YANG AKAN DITAMPILKAN DI LIST DATA TABLES
			$action_edit = ($edit == 'Y') ? '<a class="btn btn-warning btn-circle btn-sm" title="Edit" onclick="edit_data_crew(' . "'" . $field['noHP'] . "'" . ')"><i class="fa fa-edit"></i></a>&nbsp;' : '';
			$action_delete = ($delete == 'Y') ? '<a onclick="delete_data_crew(' . "'" . $field['noHP'] . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;' : '';

			$action = $action_edit . $action_delete;

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $action;
			$row[] = ucwords(strtolower($field['nama']));
			$row[] = $field['noHP'];
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => count($data), // Menghitung semua data
			"recordsFiltered" => count($data), // Menghitung data yang difilter
			"data" => $data,
		);
		// output dalam format JSON
		echo json_encode($output);
	}

	function get_list2()
	{
		// INISIALISASI VARIABEL YANG DIBUTUHKAN
		$fullname = $this->db->select('name')
			->from('tbl_data_user')
			->where('id_user', $this->session->userdata('id_user'))
			->get()
			->row('name');

		// Membaca data dari file JSON
		$file_path = FCPATH . '../linkgroup/link.pengenumroh/data-member.json';

		if (file_exists($file_path)) {
			$json_data = file_get_contents($file_path);
			$data = json_decode($json_data, true); // Mengubah JSON menjadi array

			$list = $data; // Semua data yang sudah dibaca dari JSON
		} else {
			$list = array();
		}

		$data = array();
		$no = $_POST['start'];

		$akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
		$edit = $akses->edit_level;
		$delete = $akses->delete_level;

		foreach ($list as $field) {

			// MENENTUKAN ACTION APA YANG AKAN DITAMPILKAN DI LIST DATA TABLES
			$action_edit = ($edit == 'Y') ? '<a class="btn btn-warning btn-circle btn-sm" title="Edit" onclick="edit_data_member(' . "'" . $field['noHP'] . "'" . ')"><i class="fa fa-edit"></i></a>&nbsp;' : '';
			$action_delete = ($delete == 'Y') ? '<a onclick="delete_data_member(' . "'" . $field['noHP'] . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;' : '';

			$action = $action_edit . $action_delete;

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $action;
			$row[] = ucwords(strtolower($field['namaMember']));
			$row[] = $field['noHP'];
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => count($data), // Menghitung semua data
			"recordsFiltered" => count($data), // Menghitung data yang difilter
			"data" => $data,
		);
		// output dalam format JSON
		echo json_encode($output);
	}

	function get_noHP($noHP)
	{
		// Path file JSON
		$file_path = FCPATH . '../linkgroup/link.pengenumroh/data-crew.json';

		if (file_exists($file_path)) {
			$json_data = file_get_contents($file_path);
			$data = json_decode($json_data, true); // Mengubah JSON menjadi array

			// Cari data berdasarkan noHP
			$result = array_filter($data, function ($crew) use ($noHP) {
				return $crew['noHP'] == $noHP;
			});

			// Mengembalikan hasil pertama jika ditemukan
			$data = reset($result);
		} else {
			$data = null; // Jika file JSON tidak ditemukan
		}

		echo json_encode($data);
	}

	function get_noHpMember($noHP)
	{
		// Path file JSON
		$file_path = FCPATH . '../linkgroup/link.pengenumroh/data-member.json';

		if (file_exists($file_path)) {
			$json_data = file_get_contents($file_path);
			$data = json_decode($json_data, true); // Mengubah JSON menjadi array

			// Cari data berdasarkan noHP
			$result = array_filter($data, function ($crew) use ($noHP) {
				return $crew['noHP'] == $noHP;
			});

			// Mengembalikan hasil pertama jika ditemukan
			$data = reset($result);
		} else {
			$data = null; // Jika file JSON tidak ditemukan
		}

		echo json_encode($data);
	}

	public function addCrew()
	{
		// Ambil input dari form atau AJAX
		$nama = $this->input->post('nama_crew'); // Data nama
		$noHP = $this->input->post('no_hp'); // Data noHP

		// Pastikan data tidak kosong
		if (empty($nama) || empty($noHP)) {
			echo json_encode(['status' => 'error', 'message' => 'Nama dan noHP harus diisi']);
			return;
		}

		// Path file data.json
		$file_path = FCPATH . '../linkgroup/link.pengenumroh/data-crew.json'; // Sesuaikan dengan lokasi file

		// Baca data yang sudah ada di data.json
		$existing_data = [];
		if (file_exists($file_path)) {
			$file_content = file_get_contents($file_path);
			$existing_data = json_decode($file_content, true); // Decode JSON ke array
		}

		// Tambahkan data baru ke array existing_data
		$new_data = [
			'nama' => $nama,
			'noHP' => (int)$noHP // Pastikan noHP menjadi integer
		];
		$existing_data[] = $new_data;

		// Encode data ke format JSON
		$json_data = json_encode($existing_data, JSON_PRETTY_PRINT);

		// Simpan data ke file data.json
		if (file_put_contents($file_path, $json_data)) {
			echo json_encode(['status' => 'success', 'message' => 'Data berhasil disimpan']);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data']);
		}
	}

	public function addMember()
	{
		// Ambil input dari form atau AJAX
		$nama = $this->input->post('nama_member'); // Data nama
		$noHP = $this->input->post('no_hp'); // Data noHP

		// Pastikan data tidak kosong
		if (empty($nama) || empty($noHP)) {
			echo json_encode(['status' => 'error', 'message' => 'Nama dan noHP harus diisi']);
			return;
		}

		// Path file data.json
		$file_path = FCPATH . '../linkgroup/link.pengenumroh/data-member.json'; // Sesuaikan dengan lokasi file

		// Baca data yang sudah ada di data.json
		$existing_data = [];
		if (file_exists($file_path)) {
			$file_content = file_get_contents($file_path);
			$existing_data = json_decode($file_content, true); // Decode JSON ke array
		}

		// Tambahkan data baru ke array existing_data
		$new_data = [
			'namaMember' => $nama,
			'noHP' => (int)$noHP // Pastikan noHP menjadi integer
		];
		$existing_data[] = $new_data;

		// Encode data ke format JSON
		$json_data = json_encode($existing_data, JSON_PRETTY_PRINT);

		// Simpan data ke file data.json
		if (file_put_contents($file_path, $json_data)) {
			echo json_encode(['status' => 'success', 'message' => 'Data berhasil disimpan']);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data']);
		}
	}

	public function updateCrew()
	{
		// Lokasi file JSON
		$file_path = FCPATH . '../linkgroup/link.pengenumroh/data-crew.json';

		// Pastikan file JSON ada
		if (!file_exists($file_path)) {
			echo json_encode(['status' => FALSE, 'message' => 'File JSON tidak ditemukan']);
			return;
		}

		// Ambil data dari file JSON
		$json_content = file_get_contents($file_path);
		$data_crew = json_decode($json_content, true);

		// Input no_hp dari form
		$no_hp = $this->input->post('no_hp');

		// Data yang diupdate
		$nama_crew = strtolower($this->input->post('nama_crew'));
		$no_hp_update = $this->input->post('no_hp');

		// Cari dan update data berdasarkan no_hp
		$updated = false;
		foreach ($data_crew as &$crew) {
			if ($crew['noHP'] == (int)$no_hp) {
				$crew['nama'] = $nama_crew;
				$updated = true;
				break;
			}
		}

		if ($updated) {
			// Tulis kembali data ke file JSON
			if (file_put_contents($file_path, json_encode($data_crew, JSON_PRETTY_PRINT))) {
				echo json_encode(['status' => TRUE, 'message' => 'Data berhasil diperbarui']);
			} else {
				echo json_encode(['status' => FALSE, 'message' => 'Gagal menyimpan ke file JSON']);
			}
		} else {
			echo json_encode(['status' => FALSE, 'message' => 'Data tidak ditemukan']);
		}
	}

	public function updateMember()
	{
		// Lokasi file JSON
		$file_path = FCPATH . '../linkgroup/link.pengenumroh/data-member.json';

		// Pastikan file JSON ada
		if (!file_exists($file_path)) {
			echo json_encode(['status' => FALSE, 'message' => 'File JSON tidak ditemukan']);
			return;
		}

		// Ambil data dari file JSON
		$json_content = file_get_contents($file_path);
		$data_member = json_decode($json_content, true);

		// Input no_hp dari form
		$no_hp = $this->input->post('no_hp');

		// Data yang diupdate
		$nama_member = strtolower($this->input->post('nama_member'));
		$no_hp_update = $this->input->post('no_hp');

		// Cari dan update data berdasarkan no_hp
		$updated = false;
		foreach ($data_member as &$member) {
			if ($member['noHP'] == (int)$no_hp) {
				$member['namaMember'] = $nama_member;
				$updated = true;
				break;
			}
		}

		if ($updated) {
			// Tulis kembali data ke file JSON
			if (file_put_contents($file_path, json_encode($data_member, JSON_PRETTY_PRINT))) {
				echo json_encode(['status' => TRUE, 'message' => 'Data berhasil diperbarui']);
			} else {
				echo json_encode(['status' => FALSE, 'message' => 'Gagal menyimpan ke file JSON']);
			}
		} else {
			echo json_encode(['status' => FALSE, 'message' => 'Data tidak ditemukan']);
		}
	}


	function delete($noHP)
	{
		$file_path = FCPATH . '../linkgroup/link.pengenumroh/data-crew.json';

		if (file_exists($file_path)) {
			$json_data = file_get_contents($file_path);
			$data = json_decode($json_data, true); // Mengubah JSON menjadi array

			// Cari dan hapus data berdasarkan noHP
			$updated_data = array_filter($data, function ($crew) use ($noHP) {
				return $crew['noHP'] != $noHP;
			});

			// Simpan kembali data setelah dihapus
			if (count($updated_data) < count($data)) {
				file_put_contents($file_path, json_encode(array_values($updated_data), JSON_PRETTY_PRINT));
				echo json_encode(array("status" => TRUE));
			} else {
				echo json_encode(array("status" => FALSE, "message" => "Data tidak ditemukan."));
			}
		} else {
			echo json_encode(array("status" => FALSE, "message" => "File tidak ditemukan."));
		}
	}

	function deleteMember($noHP)
	{
		$file_path = FCPATH . '../linkgroup/link.pengenumroh/data-member.json';

		if (file_exists($file_path)) {
			$json_data = file_get_contents($file_path);
			$data = json_decode($json_data, true); // Mengubah JSON menjadi array

			// Cari dan hapus data berdasarkan noHP
			$updated_data = array_filter($data, function ($member) use ($noHP) {
				return $member['noHP'] != $noHP;
			});

			// Simpan kembali data setelah dihapus
			if (count($updated_data) < count($data)) {
				file_put_contents($file_path, json_encode(array_values($updated_data), JSON_PRETTY_PRINT));
				echo json_encode(array("status" => TRUE));
			} else {
				echo json_encode(array("status" => FALSE, "message" => "Data tidak ditemukan."));
			}
		} else {
			echo json_encode(array("status" => FALSE, "message" => "File tidak ditemukan."));
		}
	}


	public function fetch_crew_data()
	{
		// Path ke file JSON
		$file_path = FCPATH . '../linkgroup/link.pengenumroh/data-crew.json'; // Sesuaikan dengan lokasi file

		if (file_exists($file_path)) {
			// Membaca file JSON
			$json_data = file_get_contents($file_path);

			// Mengubah JSON menjadi array PHP
			$data = json_decode($json_data, true);

			// Kirim data sebagai JSON untuk DataTables
			echo json_encode([
				"data" => $data
			]);
		} else {
			// Jika file tidak ditemukan
			echo json_encode([
				"data" => [],
				"message" => "File data-crew.json tidak ditemukan!"
			]);
		}
	}
}
