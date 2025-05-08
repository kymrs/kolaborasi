<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pu_data_link extends CI_Controller
{

	private $data_path_crew = 'https://link.pengenumroh.com/data-crew.json';
	private $data_path_member = 'https://link.pengenumroh.com/data-member.json';

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
				$row[] = '<a target="_blank" href="https://link.pengenumroh.com/?ref=' . $crew['id'] . '">' . 'Kunjungi</a>';
				$row[] = '<a data-label="' . $crew['id'] . '" data-link="https://link.pengenumroh.com/?ref=' . $crew['id'] . '" class="download-qr-crew" download="qrcode - ' . $crew['id'] . '.png" style="cursor: pointer">Download QR Code</a><canvas style="display: none" id="qrCanvasCrew"></canvas>';

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
				$row[] = '<a target="_blank" href="https://link.pengenumroh.com/?mb=' . $member['idMember'] . '">' . 'Kunjungi</a>';
				$row[] = '<a data-label="' . $member['idMember'] . '" data-link="https://link.pengenumroh.com/?mb=' . $member['idMember'] . '" class="download-qr-member" download="qrcode - ' . $member['idMember'] . '.png" style="cursor: pointer">Download QR Code</a><canvas style="display: none" id="qrCanvasMember"></canvas>';

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
		// Path file JSON (URL)
		$file_path = $this->data_path_crew;

		// Ambil data JSON dari URL
		$json_data = @file_get_contents($file_path);

		// Cek apakah berhasil mengambil data
		if ($json_data === false) {
			echo json_encode(["error" => "Gagal mengambil data JSON"]);
			return;
		}

		// Decode JSON ke array
		$data = json_decode($json_data, true);

		// Cek apakah JSON valid
		if ($data === null) {
			echo json_encode(["error" => "Format JSON tidak valid"]);
			return;
		}

		// Cari data berdasarkan id
		$result = array_filter($data, function ($crew) use ($id) {
			return $crew['id'] == $id;
		});

		// Ambil hasil pertama atau null jika tidak ada
		$data = reset($result) ?: null;

		echo json_encode($data);
	}


	function get_idMember($idMember)
	{
		// Path file JSON (URL)
		$file_path = $this->data_path_member;

		// Ambil data JSON dari URL
		$json_data = @file_get_contents($file_path);

		// Cek apakah berhasil mengambil data
		if ($json_data === false) {
			echo json_encode(["error" => "Gagal mengambil data JSON"]);
			return;
		}

		// Decode JSON ke array
		$data = json_decode($json_data, true);

		// Cek apakah JSON valid
		if ($data === null) {
			echo json_encode(["error" => "Format JSON tidak valid"]);
			return;
		}

		// Cari data berdasarkan idMember
		$result = array_filter($data, function ($member) use ($idMember) {
			return $member['idMember'] == $idMember;
		});

		// Ambil hasil pertama atau null jika tidak ada
		$data = reset($result) ?: null;

		echo json_encode($data);
	}

	public function addCrew()
	{
		// Ambil input dari form atau AJAX
		$nama = ucwords($this->input->post('nama_crew'));
		$noHP = $this->input->post('no_hp');

		// Pastikan data tidak kosong
		if (empty($nama) || empty($noHP)) {
			echo json_encode(['status' => 'error', 'message' => 'Nama dan noHP harus diisi']);
			return;
		}

		// Data yang mau dikirim ke API
		$data = [
			'nama' => $nama,
			'noHP' => $noHP
		];

		$api_url = "https://link.pengenumroh.com/api/addCrew.php"; // API yang kita buat

		// Setup cURL
		$ch = curl_init($api_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);

		// Eksekusi request
		$response = curl_exec($ch);
		curl_close($ch);

		// Kirim response ke frontend
		echo $response;
	}

	public function addMember()
	{
		$nama = $this->input->post('nama_member');

		if (empty($nama)) {
			echo json_encode(['status' => 'error', 'message' => 'Nama harus diisi']);
			return;
		}

		// Kirim data ke API
		$url = "https://link.pengenumroh.com/api/addMember.php";
		$postData = json_encode(['nama_member' => $nama]);

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Content-Type: application/json'
		]);

		$response = curl_exec($ch);
		curl_close($ch);

		echo $response;
	}

	public function updateCrew()
	{
		$id = $this->input->post('id');
		$no_hp = $this->input->post('no_hp');
		$nama_crew = ucwords($this->input->post('nama_crew'));

		if (empty($id) || empty($no_hp)) {
			echo json_encode(['status' => false, 'message' => 'ID dan No HP harus diisi']);
			return;
		}

		// Kirim data ke API
		$url = "https://link.pengenumroh.com/api/updateCrew.php";
		$postData = json_encode(['id' => $id, 'no_hp' => $no_hp, 'nama_crew' => $nama_crew]);

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Content-Type: application/json'
		]);

		$response = curl_exec($ch);
		curl_close($ch);

		echo $response;
	}

	public function updateMember()
	{
		$idMember = $this->input->post('id_member');
		$nama_member = ucwords($this->input->post('nama_member'));

		if (empty($idMember) || empty($nama_member)) {
			echo json_encode(['status' => false, 'message' => 'ID Member dan Nama harus diisi']);
			return;
		}

		$url = "https://link.pengenumroh.com/api/updateMember.php";
		$postData = json_encode(['id_member' => $idMember, 'nama_member' => $nama_member]);

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Content-Type: application/json'
		]);

		$response = curl_exec($ch);
		curl_close($ch);

		echo $response;
	}


	public function delete($id)
	{
		$api_url = "https://link.pengenumroh.com/api/deleteCrew.php";

		// Kirim data ke API untuk hapus berdasarkan ID
		$ch = curl_init($api_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(["id" => $id]));
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

		$response = curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		// Cek respons dari API
		if ($http_code == 200) {
			echo json_encode(["status" => TRUE, "message" => "Data berhasil dihapus."]);
		} else {
			echo json_encode(["status" => FALSE, "message" => "Gagal menghapus data di server."]);
		}
	}

	public function deleteMember($idMember)
	{
		$api_url = "https://link.pengenumroh.com/api/deleteMember.php";

		// Kirim data ke API untuk hapus berdasarkan ID Member
		$ch = curl_init($api_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(["idMember" => $idMember]));
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

		$response = curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		// Cek respons dari API
		if ($http_code == 200) {
			echo json_encode(["status" => TRUE, "message" => "Data member berhasil dihapus."]);
		} else {
			echo json_encode(["status" => FALSE, "message" => "Gagal menghapus data member di server."]);
		}
	}
}
