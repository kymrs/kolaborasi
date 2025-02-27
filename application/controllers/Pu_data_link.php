<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pu_data_link extends CI_Controller
{

	private $data_path_crew = '../linkgroup/link.pengenumroh/data-crew.json';
	private $data_path_member = '../linkgroup/link.pengenumroh/data-member.json';

	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->model('backend/M_pu_data_link');
	}

	public function index()
	{
		$this->M_login->getsecurity();
		$data['title'] = "backend/pu_data_link/pu_data_link_list";
		$data['titleview'] = "Data Link Crew & Member";
		$this->load->view('backend/home', $data);
	}


	public function get_list1()
	{
		// Pastikan parameter `start` dan `draw` ada di request
		$start = $this->input->post('start') ?? 0;
		$draw = $this->input->post('draw') ?? 1;

		// Ambil data dari model
		$list = $this->M_pu_data_link->get_datatables();
		$data = [];
		$no = $start;

		// Periksa hak akses edit dan delete
		$akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
		$edit = $akses->edit_level ?? 'N';
		$delete = $akses->delete_level ?? 'N';

		if (!empty($list)) {
			foreach ($list as $crew) {
				$action_edit = ($edit == 'Y') ? '<a class="btn btn-warning btn-circle btn-sm" title="Edit" onclick="edit_data_crew(' . "'" . $crew['id'] . "'" . ')"><i class="fa fa-edit"></i></a>&nbsp;' : '';
				$action_delete = ($delete == 'Y') ? '<a onclick="delete_data_crew(' . "'" . $crew['id'] . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;' : '';

				$action = $action_edit . $action_delete;

				$no++;
				$row = [];
				$row[] = $no;  // Nomor urut
				$row[] = $action;
				$row[] = isset($crew['id']) ? $crew['id'] : '';  // Kolom id
				$row[] = isset($crew['nama']) ? $crew['nama'] : '';  // Kolom nama
				$row[] = isset($crew['noHP']) ? $crew['noHP'] : '';  // Kolom noHP

				$data[] = $row;
			}
		}

		// Respons ke DataTables
		$output = [
			"draw" => intval($draw),
			"recordsTotal" => $this->M_pu_data_link->count_all(),
			"recordsFiltered" => $this->M_pu_data_link->count_filtered(),
			"data" => $data,
		];

		echo json_encode($output);
	}

	public function get_list2()
	{
		// Pastikan parameter `start` dan `draw` ada di request
		$start = $this->input->post('start') ?? 0;
		$draw = $this->input->post('draw') ?? 1;

		// Ambil data dari model
		$list = $this->M_pu_data_link->get_datatables2();
		$data = [];
		$no = $start;

		// Periksa hak akses edit dan delete
		$akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
		$edit = $akses->edit_level ?? 'N';
		$delete = $akses->delete_level ?? 'N';

		if (!empty($list)) {
			foreach ($list as $member) {
				$action_edit = ($edit == 'Y') ? '<a class="btn btn-warning btn-circle btn-sm" title="Edit" onclick="edit_data_member(' . "'" . $member['idMember'] . "'" . ')"><i class="fa fa-edit"></i></a>&nbsp;' : '';
				$action_delete = ($delete == 'Y') ? '<a onclick="delete_data_member(' . "'" . $member['idMember'] . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;' : '';

				$action = $action_edit . $action_delete;

				$no++;
				$row = [];
				$row[] = $action;
				$row[] = $member['idMember'] ?? '';
				$row[] = $member['namaMember'] ?? '';

				$data[] = $row;
			}
		}

		// Respons ke DataTables
		$output = [
			"draw" => intval($draw),
			"recordsTotal" => $this->M_pu_data_link->count_all2(),
			"recordsFiltered" => $this->M_pu_data_link->count_filtered2(),
			"data" => $data,
		];

		echo json_encode($output);
	}


	function get_id($id)
	{
		// Path file JSON
		$file_path = FCPATH . $this->data_path_crew;

		if (file_exists($file_path)) {
			$json_data = file_get_contents($file_path);
			$data = json_decode($json_data, true); // Mengubah JSON menjadi array

			// Cari data berdasarkan id
			$result = array_filter($data, function ($crew) use ($id) {
				return $crew['id'] == $id;
			});

			// Mengembalikan hasil pertama jika ditemukan
			$data = reset($result);
		} else {
			$data = null; // Jika file JSON tidak ditemukan
		}

		echo json_encode($data);
	}

	function get_idMember($idMember)
	{
		// Path file JSON
		$file_path = FCPATH . $this->data_path_member;

		if (file_exists($file_path)) {
			$json_data = file_get_contents($file_path);
			$data = json_decode($json_data, true); // Mengubah JSON menjadi array

			// Cari data berdasarkan idMember
			$result = array_filter($data, function ($crew) use ($idMember) {
				return $crew['idMember'] == $idMember;
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
		$nama = ucwords($this->input->post('nama_crew'));
		$noHP = $this->input->post('no_hp'); // Data noHP

		// Pastikan data tidak kosong
		if (empty($nama) || empty($noHP)) {
			echo json_encode(['status' => 'error', 'message' => 'Nama dan noHP harus diisi']);
			return;
		}

		// Path file data.json
		$file_path = FCPATH . $this->data_path_crew; // Sesuaikan dengan lokasi file

		// Baca data yang sudah ada di data.json
		$existing_data = [];
		if (file_exists($file_path)) {
			$file_content = file_get_contents($file_path);
			$existing_data = json_decode($file_content, true) ?? []; // Decode JSON ke array
		}

		// Ambil ID terakhir dan buat ID baru
		if (!empty($existing_data)) {
			$lastCrew = end($existing_data);
			preg_match('/PU(\d+)/', $lastCrew['id'], $matches);
			$lastIdNumber = isset($matches[1]) ? intval($matches[1]) : 0;
			$newIdNumber = $lastIdNumber + 1;
			$newId = sprintf("PU%04d", $newIdNumber); // Format jadi "PU0008"
		} else {
			$newId = "PU0001"; // Kalau data kosong, mulai dari PU0001
		}

		// Tambahkan data baru ke array existing_data
		$new_data = [
			'id' => $newId,
			'nama' => $nama,
			'noHP' => (int)$noHP // Pastikan noHP menjadi integer
		];
		$existing_data[] = $new_data;

		// Encode data ke format JSON
		$json_data = json_encode($existing_data, JSON_PRETTY_PRINT);

		// Simpan data ke file data.json
		if (file_put_contents($file_path, $json_data)) {
			echo json_encode(['status' => 'success', 'message' => 'Data berhasil disimpan', 'id' => $newId]);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data']);
		}
	}


	public function addMember()
	{
		// Ambil input dari form atau AJAX
		$nama = $this->input->post('nama_member'); // Data nama

		// Pastikan data tidak kosong
		if (empty($nama)) {
			echo json_encode(['status' => 'error', 'message' => 'Nama harus diisi']);
			return;
		}

		// Path file data.json
		$file_path = FCPATH . $this->data_path_member; // Sesuaikan dengan lokasi file

		// Baca data yang sudah ada di data.json
		$existing_data = [];
		if (file_exists($file_path)) {
			$file_content = file_get_contents($file_path);
			$existing_data = json_decode($file_content, true) ?? []; // Decode JSON ke array
		}

		// Ambil ID terakhir dan buat ID baru
		if (!empty($existing_data)) {
			$lastMember = end($existing_data);
			$lastId = intval(substr($lastMember['idMember'], 2)); // Ambil angka dari 'PUxxxx'
			$newId = 'PU' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT); // Format jadi 'PU0006', 'PU0007', dst
		} else {
			$newId = 'PU0000'; // Kalau data kosong, mulai dari PU0000
		}

		// Tambahkan data baru ke array existing_data
		$new_data = [
			'idMember' => $newId,
			'namaMember' => $nama
		];
		$existing_data[] = $new_data;

		// Encode data ke format JSON
		$json_data = json_encode($existing_data, JSON_PRETTY_PRINT);

		// Simpan data ke file data.json
		if (file_put_contents($file_path, $json_data)) {
			echo json_encode(['status' => 'success', 'message' => 'Data berhasil disimpan', 'idMember' => $newId]);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data']);
		}
	}

	public function updateCrew()
	{
		// Lokasi file JSON
		$file_path = FCPATH . $this->data_path_crew;

		// Pastikan file JSON ada
		if (!file_exists($file_path)) {
			echo json_encode(['status' => FALSE, 'message' => 'File JSON tidak ditemukan']);
			return;
		}

		// Ambil data dari file JSON
		$json_content = file_get_contents($file_path);
		$data_crew = json_decode($json_content, true);

		// Input no_hp dari form
		$id = $this->input->post('id');
		$no_hp = $this->input->post('no_hp');

		// Data yang diupdate
		$nama_crew = ucwords($this->input->post('nama_crew'));

		// Cari dan update data berdasarkan no_hp
		$updated = false;
		foreach ($data_crew as &$crew) {
			if ($crew['id'] == $id) {
				$crew['noHP'] = $no_hp;
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
		$file_path = FCPATH . $this->data_path_member;

		// Pastikan file JSON ada
		if (!file_exists($file_path)) {
			echo json_encode(['status' => FALSE, 'message' => 'File JSON tidak ditemukan']);
			return;
		}

		// Ambil data dari file JSON
		$json_content = file_get_contents($file_path);
		$data_member = json_decode($json_content, true);

		// Input no_hp dari form
		$idMember = $this->input->post('id_member');

		// Data yang diupdate
		$nama_member = ucwords($this->input->post('nama_member'));

		// Cari dan update data berdasarkan no_hp
		$updated = false;
		foreach ($data_member as &$member) {
			if ($member['idMember'] == $idMember) {
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


	function delete($id)
	{
		$file_path = FCPATH . $this->data_path_crew;

		if (file_exists($file_path)) {
			$json_data = file_get_contents($file_path);
			$data = json_decode($json_data, true); // Mengubah JSON menjadi array

			// Cari dan hapus data berdasarkan id
			$updated_data = array_filter($data, function ($crew) use ($id) {
				return $crew['id'] != $id;
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

	function deleteMember($idMember)
	{
		$file_path = FCPATH . $this->data_path_member;

		if (file_exists($file_path)) {
			$json_data = file_get_contents($file_path);
			$data = json_decode($json_data, true); // Mengubah JSON menjadi array

			// Cari dan hapus data berdasarkan idMember
			$updated_data = array_filter($data, function ($member) use ($idMember) {
				return $member['idMember'] != $idMember;
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
}
