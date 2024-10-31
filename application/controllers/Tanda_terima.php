<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tanda_terima extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('M_login');
		$this->load->model('backend/M_tandaterima');
		$this->load->model('backend/M_notifikasi');
		$this->M_login->getsecurity();
	}

	public function index()
	{
		$data['notif'] = $this->M_notifikasi->pending_notification();
		$this->M_login->getsecurity();
		$data['title'] = "backend/tanda_terima/tanda_terima";
		$data['titleview'] = "Tanda Terima";
		$this->load->view('backend/home', $data);
	}

	function get_list()
	{
		$list = $this->M_tandaterima->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $field) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = '<a class="btn btn-info btn-circle btn-sm" title="Read" onclick="view_data(' . "'" . $field->id . "'" . ')"><i class="fa fa-eye"></i></a>' .
				'&nbsp;<a class="btn btn-warning btn-circle btn-sm" title="Edit" onclick="edit_data(' . "'" . $field->id . "'" . ')"><i class="fa fa-edit"></i></a>' .
				'&nbsp;<a class="btn btn-danger btn-circle btn-sm" title="Delete" onclick="delete_data(' . "'" . $field->id . "'" . ')"><i class="fa fa-trash"></i></a>' .
				'&nbsp;<a class="btn btn-success btn-circle btn-sm" title="Print" onclick="pdf(' . "'" . $field->id . "'" . ')"><i class="fa fa-file-pdf"></i></a>';
			$row[] = $field->nomor;
			$row[] = $field->tanggal;
			$row[] = $field->nama_pengirim;
			$row[] = $field->nama_penerima;
			$row[] = $field->barang;
			$row[] = $field->qty;
			$row[] = ($field->foto != '' ? '<img src="' . site_url("assets/backend/document/tanda_terima/") . $field->foto  . '" height="40px" width="40px">' : '');

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->M_tandaterima->count_all(),
			"recordsFiltered" => $this->M_tandaterima->count_filtered(),
			"data" => $data,
		);
		//output dalam format JSON
		echo json_encode($output);
	}

	function delete($id)
	{
		$data = $this->M_tandaterima->get_by_id($id);
		$img = $data->foto;
		$path = './assets/img/' . $img;
		if (is_file($path)) {
			unlink($path);
		}
		$this->M_tandaterima->delete_id($id);
		echo json_encode(array("status" => TRUE));
	}

	function add()
	{
		$config['upload_path'] = "./assets/backend/document/tanda_terima";
		$config['allowed_types'] = 'gif|jpg|png';
		$this->load->library('upload', $config); //call library upload 
		if ($this->upload->do_upload("image")) { //upload file
			$data = array('upload_data' => $this->upload->data()); //ambil file name yang diupload
			$img = $data['upload_data']['file_name']; //set file name ke variable image
		} else {
			$img = '';
		}

		$nomor = $this->no_baru();

		$data = array(
			// 'nomor' => $this->input->post('nomor'),
			'nomor' => $nomor,
			'tanggal' => $this->input->post('tanggal'),
			'nama_pengirim' => $this->input->post('nama_pengirim'),
			'title' => $this->input->post('title'),
			'nama_penerima' => $this->input->post('nama_penerima'),
			'barang' => $this->input->post('barang'),
			'qty' => $this->input->post('qty'),
			'keterangan' => $this->input->post('keterangan'),
			'foto' => $img,
			'user' => $this->session->userdata('username'),
		);
		$insert = $this->M_tandaterima->save($data);
		echo json_encode(array("status" => TRUE));
	}

	function update()
	{
		$data1 = array(
			'nomor' => $this->input->post('nomor'),
			'tanggal' => $this->input->post('tanggal'),
			'nama_pengirim' => $this->input->post('nama_pengirim'),
			'title' => $this->input->post('title'),
			'nama_penerima' => $this->input->post('nama_penerima'),
			'barang' => $this->input->post('barang'),
			'qty' => $this->input->post('qty'),
			'keterangan' => $this->input->post('keterangan'),
			'user' => $this->session->userdata('username'),
		);

		if (!empty($_FILES['image']['name'])) {
			$config['upload_path'] = "./assets/img";
			$config['allowed_types'] = 'gif|jpg|png';
			$this->load->library('upload', $config); //call library upload 
			if ($this->upload->do_upload("image")) { //upload file
				$data = array('upload_data' => $this->upload->data()); //ambil file name yang diupload
				$img = $data['upload_data']['file_name']; //set file name ke variable image
			}
			$data = array_merge($data1, array('foto' => $img));
			$pict = $this->M_tandaterima->get_by_id($this->input->post('id'));
			$path = './assets/img/' . $pict->foto;
			if (is_file($path)) {
				unlink($path);
			}
		} else {
			$data = $data1;
		}

		$this->db->where('id', $this->input->post('id'));
		$this->db->update('tanda_terima', $data);
		echo json_encode(array("status" => TRUE));
	}

	function get_id($id)
	{
		$data = $this->M_tandaterima->get_by_id($id);
		echo json_encode($data);
	}

	public function get_no()
	{
		$this->db->select('nomor');
		$this->db->limit(1);
		$this->db->order_by('id', 'desc');
		$data = $this->db->get('tanda_terima')->row();
		echo json_encode($data);
	}

	function print($id)
	{
		$query = $this->M_tandaterima->get_by_id($id);
		$data['pengirim'] = $query->nama_pengirim;
		$data['title'] = $query->title;
		$data['penerima'] = $query->nama_penerima;
		$data['tanggal'] = date('d/m/Y', strtotime($query->tanggal));
		$data['nomor'] = $query->nomor;
		$data['barang'] = $query->barang;
		$data['qty'] = $query->qty;
		$data['keterangan'] = $query->keterangan;
		$data['foto'] = $query->foto;
		$this->load->view('/backend/tanda_terima/tanda_terima_print', $data);
	}

	function preview($id)
	{
		$query = $this->M_tandaterima->get_by_id($id);
		$data['id'] = $id;
		$data['user'] = $this->M_tandaterima->get_by_id($id);
		$data['app_hc_name'] = $this->db->select('name')
			->from('tbl_data_user')
			->where('id_user', $this->session->userdata('id_user'))
			->get()
			->row('name');
		$data['app2_name'] = $this->db->select('name')
			->from('tbl_data_user')
			->where('id_user', $this->session->userdata('id_user'))
			->get()
			->row('name');
		$data['title'] = 'backend/tanda_terima/tanda_terima_read';
		$this->load->view('backend/home', $data);
	}

	function no_baru()
	{
		$this->db->select('nomor');
		$this->db->limit(1);
		$this->db->order_by('id', 'desc');
		$data = $this->db->get('tanda_terima')->row();
		if ($data === null) {
			$nomor = 'PU00001';
		} else {
			$no = substr($data->nomor, 2);
			$no_baru = intval($no) + 1;
			$nomor = 'PU' . str_pad($no_baru, 5, "0", STR_PAD_LEFT);
		}
		return $nomor;
	}

	function pdf($id)
	{
		$this->load->library('Fpdf_generate');
		// $pdf = $this->customfpdf->getInstance();
		$pdf = new Fpdf_generate('P', 'mm', 'A4');
		// $pdf->SetTitle('Form Pengajuan Prepayment');
		// $pdf->AddPage('P', 'Letter');
		$query = $this->M_tandaterima->get_by_id($id);

		$pdf->AliasNbPages();
		$pdf->AddPage();
		// Logo
		$pdf->Image(base_url('') . '/assets/backend/img/pengenumroh.png', 11.5, 3, 35, 22);
		$pdf->Ln(16);
		$pdf->SetTitle('Tanda Terima ' . $query->nomor);
		$pdf->SetFont('Courier', '', 12);
		$pdf->Cell(140, 10, 'Pengirim: ' . $query->nama_pengirim, 0, 0);
		$pdf->Cell(47, 10, date('d/m/Y', strtotime($query->tanggal)), 0, 1, 'R');
		$pdf->Cell(140, 10, 'Kepada Yth: ' . $query->title . ' ' . $query->nama_penerima, 0, 0);
		$pdf->Cell(50, 10, 'No: ' . $query->nomor, 0, 1, 'R');
		$pdf->Ln(10);
		$pdf->SetLineWidth(0.1);
		$pdf->SetDash(1, 1);
		// $pdf->Line(10, 23, 50, 23);
		$pdf->SetDash(1, 1);
		$pdf->SetLineWidth(0.1);
		$pdf->Line(10, 66, $pdf->GetPageWidth() - 10, 66);
		$pdf->Line(10, 76, $pdf->GetPageWidth() - 10, 76);
		$pdf->Line(10, 86, $pdf->GetPageWidth() - 10, 86);
		$pdf->SetFont('Courier', 'B', 12);
		$pdf->Cell(140, 10, 'Uraian', 0, 0, 'L');
		$pdf->Cell(50, 10, 'Jumlah', 0, 1, 'L');
		$pdf->SetFont('Courier', '', 12);
		$pdf->Cell(140, 10, $query->barang, 0, 0, 'L');
		$pdf->Cell(50, 10, $query->qty, 0, 1, 'L');
		$list = explode("\n", $query->keterangan);
		for ($i = 0; $i < count($list); $i++) {
			$pdf->Cell(0, 10, $list[$i], 0, 1, 'L');
		}
		$pdf->Ln(10);
		$pdf->Cell(0, 10, 'Bukti Serah Terima:', 0, 1, 'C');
		$posisi = $pdf->GetPageWidth() / 2 - 20;
		// $pdf->Image('assets/img/' . $query->foto, $posisi, $pdf->GetY(), 40, 0);
		if ($query->foto == '') {
		} else {
			$text = 'Diterima';
			$x = $pdf->GetPageWidth() / 2 - 35;  // Calculate x position
			$y = 90;                             // Set y position
			$angle = 30;                         // Rotation angle

			// Set text color and transparency
			$pdf->SetTextColor(255, 140, 0);
			$pdf->SetAlpha(0.25);
			$pdf->SetFont('Courier', '', 40);

			// Rotate the canvas
			$pdf->StartTransform();
			$pdf->Rotate($angle, $x, $y);

			// Draw border rectangle with the same rotation as the text
			$pdf->SetDrawColor(255, 140, 0);      // Set border color to orange
			$pdf->SetLineWidth(1);                // Set border thickness
			$pdf->Rect($x - 11, $y - 28, 90, 45);  // Draw rectangle around text

			// Add the rotated text
			$pdf->Text($x, $y, $text);

			// Stop canvas rotation and reset it for further content
			$pdf->StopTransform();

			// Reset text color to black for any additional content
			$pdf->SetTextColor(0, 0, 0);
		}
		$pdf->Output();
	}
}
