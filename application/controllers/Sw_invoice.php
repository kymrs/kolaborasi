<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sw_invoice extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_sw_invoice');
        $this->M_login->getsecurity();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $data['title'] = "backend/sw_invoice/sw_invoice_list";
        $data['titleview'] = "Invoice";
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
        $list = $this->M_sw_invoice->get_datatables($status);
        $data = array();
        $no = $_POST['start'];

        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        $read = $akses->view_level;
        $edit = $akses->edit_level;
        $delete = $akses->delete_level;
        $print = $akses->print_level;

        foreach ($list as $field) {

            // MENENTUKAN ACTION APA YANG AKAN DITAMPILKAN DI LIST DATA TABLES
            $action_read = ($read == 'Y') ? '<a href="sw_invoice/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-file-pdf"></i></a>&nbsp;' : '';
            $action_edit = ($edit == 'Y') ? '<a href="sw_invoice/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;' : '';
            $action_delete = ($delete == 'Y') ? '<a onclick="delete_data(' . "'" . $field->id . "'" . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;' : '';
            // $action_print = ($print == 'Y') ? '<a class="btn btn-success btn-circle btn-sm" title="Print" onclick="print_data(' . "'" . $field->id . "'" . ')"><i class="fa fa-file-pdf"></i></a>&nbsp;' : '';

            $action = $action_read . $action_edit . $action_delete;

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $action;
            $row[] = $field->letter_number;
            $row[] = $field->company_name;
            $row[] = $field->event_type;
            $row[] = date('d-m-Y H:i:s', strtotime($field->created_at));
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_sw_invoice->count_all(),
            "recordsFiltered" => $this->M_sw_invoice->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    // function read_form($id)
    // {
    //     $mpdf = new \Mpdf\Mpdf(['format' => 'A4']);
    //     $data = $this->M_sw_invoice->get_by_id($id);
    //     $data2 = $this->load->view('backend/sw_invoice/sw_invoice_pdf', $data, TRUE);
    //     $mpdf->WriteHTML($data2);
    //     $mpdf->Output();
    // }

    function read_form($id)
    {
        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4',
            'margin_top' => 45,
            'margin_bottom' => 15,
        ]);

        $mpdf->SetAuthor('Sebelaswarna EO');
        $mpdf->SetSubject('Event Confirmation');
        $mpdf->SetCreator('System Sebelaswarna');

        // HEADER LOGO
        // $mpdf->SetHTMLHeader('
        //     <div style="text-align: left;">
        //         <img src="assets/backend/img/sebelaswarna.png" width="160">
        //     </div>
        // ');

        $path = FCPATH . 'assets/backend/img/kop_surat_sw.png';

        // Watermark image
        $mpdf->SetWatermarkImage(
            $path,
            1,
            [210, 297],
            [0, 0]
        );

        $mpdf->showWatermarkImage = true;
        $mpdf->watermarkImgBehind = true;

        $mpdf->SetHTMLFooter('
            <div style="text-align: center; font-size: 12px;">
                PT SOBAT WISATA DUNIA<br>
                Kp. Empu No.1, RT.001/RW.007,<br>
                Kelurahan Setu Sari, Kec. Cileungsi, Kab. Bogor.<br>
                0812-8222-9700 | <a href="http://www.sebelaswarna.com" target="_blank">www.sebelaswarna.com</a>
            </div>
        ');

        $mpdf->showWatermarkImage = true;

        $data = $this->M_sw_invoice->get_by_id_print($id);
        $mpdf->SetTitle('Invoice - ' . $data->company_name);
        $html = $this->load->view('backend/sw_invoice/sw_invoice_pdf', $data, TRUE);

        $mpdf->WriteHTML($html);
        $mpdf->Output('Invoice - ' . $data->company_name . '.pdf', 'I');
    }

    // function read_form($id)
    // {
    //     $data['master'] = $this->M_sw_invoice->get_by_id($id);
    //     $data['title_view'] = "Data Hotel";
    //     $data['title'] = 'backend/sw_invoice/sw_invoice_read';
    //     $this->load->view('backend/home', $data);
    // }

    function add_form()
    {
        $data['id'] = 0;
        $data['title_view'] = "Invoice Form";
        $data['title'] = 'backend/sw_invoice/sw_invoice_form';
        $this->load->view('backend/home', $data);
    }

    function edit_form($id)
    {
        $data['id'] = $id;
        $data['title_view'] = "Edit Invoice";
        $data['title'] = 'backend/sw_invoice/sw_invoice_form';
        $this->load->view('backend/home', $data);
    }

    // ========== GENERATE LETTER NUMBER ==========
    function generate_letter_number()
    {
        // Format: 013/SW-EO/X/2025
        // 013 = nomor urut, SW-EO = fixed, X = bulan (I, II, III, dst), 2025 = tahun
        
        $current_month = date('m');
        $current_year = date('Y');
        
        // Count records created in current month
        $count = $this->db
            ->where('MONTH(created_at)', $current_month)
            ->where('YEAR(created_at)', $current_year)
            ->count_all_results('sw_invoice');
        
        $sequence = $count + 1;
        $roman_month = $this->convert_to_roman($current_month);
        
        $letter_number = str_pad($sequence, 3, '0', STR_PAD_LEFT) . '/SW-EO/' . $roman_month . '/' . $current_year;
        
        echo json_encode(array('letter_number' => $letter_number));
    }

    // ========== CONVERT NUMBER TO ROMAN NUMERALS ==========
    private function convert_to_roman($num)
    {
        $roman_numerals = array(
            12 => 'XII', 11 => 'XI', 10 => 'X', 9 => 'IX', 8 => 'VIII',
            7 => 'VII', 6 => 'VI', 5 => 'V', 4 => 'IV', 3 => 'III',
            2 => 'II', 1 => 'I'
        );
        
        foreach ($roman_numerals as $int => $roman) {
            if ($num == $int) {
                return $roman;
            }
        }
        return '';
    }

    // ========== GET DATA FOR EDIT ==========
    function get_data($id)
    {
        $master = $this->M_sw_invoice->get_by_id($id);
        $items = $this->db->get_where('sw_invoice_detail', ['invoice_id' => $id])->result();
        
        $data = array(
            'id' => $master->id,
            'letter_number' => $master->letter_number,
            'letter_date' => $master->letter_date,
            'company_name' => $master->company_name,
            'event_type' => $master->event_type,
            'total_amount' => $master->total_amount,
            'final_date' => $master->final_date,
            'items' => $items
        );
        echo json_encode($data);
    }

    function get_id($id)
    {
        $data = $this->M_sw_invoice->get_by_id($id);
        echo json_encode($data);
    }

    public function add()
    {
        // Convert date format from dd-mm-yyyy to yyyy-mm-dd
        $letter_date = $this->convert_date_format($this->input->post('letter_date'));
        $final_date = $this->convert_date_format($this->input->post('final_date'));

        // Clean currency values - remove dots for database storage
        $total_amount = str_replace('.', '', $this->input->post('total_amount')) ?: 0;

        $data = array(
            'letter_number' => $this->input->post('letter_number'),
            'letter_date' => $letter_date,
            'company_name' => $this->input->post('company_name'),
            'event_type' => $this->input->post('event_type'),
            'total_amount' => $total_amount,
            'final_date' => $final_date,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        );

        try {
            $invoice_id = $this->M_sw_invoice->save($data);
            
            if (!$invoice_id) {
                throw new Exception('Failed to save letter data');
            }
            
            // Save item details
            $remarks_list = $this->input->post('remarks');
            $unit_prices_clean = $this->input->post('unit_price_clean');
            $quantities = $this->input->post('qty');
            $total_prices_clean = $this->input->post('total_price_clean');
            
            if (!empty($remarks_list)) {
                foreach ($remarks_list as $i => $val) {
                    $item_detail = array(
                        'invoice_id' => $invoice_id,
                        'remarks' => $val ?? '',
                        'unit_price' => isset($unit_prices_clean[$i]) ? intval(str_replace('.', '', $unit_prices_clean[$i])) : 0,
                        'qty' => isset($quantities[$i]) ? intval($quantities[$i]) : 0,
                        'total_price' => isset($total_prices_clean[$i]) ? intval(str_replace('.', '', $total_prices_clean[$i])) : 0,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    );

                    $this->db->insert('sw_invoice_detail', $item_detail);
                }
            }
            
            echo json_encode(array("status" => TRUE, "message" => "Data saved successfully"));
        } catch (Exception $e) {
            // Rollback master if items failed
            if (isset($invoice_id) && $invoice_id) {
                $this->db->where('id', $invoice_id);
                $this->db->delete('sw_invoice');
            }
            echo json_encode(array("status" => FALSE, "error" => "An error occurred: " . $e->getMessage()));
        }
    }

    public function update()
    {
        $id = $this->input->post('id');
        
        // Convert date format from dd-mm-yyyy to yyyy-mm-dd
        $letter_date = $this->convert_date_format($this->input->post('letter_date'));
        $final_date = $this->convert_date_format($this->input->post('final_date'));

        // Clean currency values - remove dots for database storage
        $total_amount = str_replace('.', '', $this->input->post('total_amount')) ?: 0;

        $data = array(
            'letter_number' => $this->input->post('letter_number'),
            'letter_date' => $letter_date,
            'company_name' => $this->input->post('company_name'),
            'event_type' => $this->input->post('event_type'),
            'total_amount' => $total_amount,
            'final_date' => $final_date,
            'updated_at' => date('Y-m-d H:i:s')
        );

        try {
            $this->db->where('id', $id);
            if (!$this->db->update('sw_invoice', $data)) {
                throw new Exception('Failed to update letter data');
            }
            
            // Delete old item details and insert new ones
            $this->db->where('invoice_id', $id);
            $this->db->delete('sw_invoice_detail');
            
            // Save item details
            $remarks_list = $this->input->post('remarks');
            $unit_prices_clean = $this->input->post('unit_price_clean');
            $quantities = $this->input->post('qty');
            $total_prices_clean = $this->input->post('total_price_clean');
            
            if (!empty($remarks_list)) {
                foreach ($remarks_list as $i => $val) {
                    $item_detail = array(
                        'invoice_id' => $id,
                        'remarks' => $val ?? '',
                        'unit_price' => isset($unit_prices_clean[$i]) ? intval(str_replace('.', '', $unit_prices_clean[$i])) : 0,
                        'qty' => isset($quantities[$i]) ? intval($quantities[$i]) : 0,
                        'total_price' => isset($total_prices_clean[$i]) ? intval(str_replace('.', '', $total_prices_clean[$i])) : 0,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    );

                    if (!$this->db->insert('sw_invoice_detail', $item_detail)) {
                        throw new Exception('Failed to save item details');
                    }
                }
            }
            
            echo json_encode(array("status" => TRUE, "message" => "Data updated successfully"));
        } catch (Exception $e) {
            echo json_encode(array("status" => FALSE, "error" => "An error occurred: " . $e->getMessage()));
        }
    }

    // ========== HELPER FUNCTION - CONVERT DATE FORMAT ==========
    private function convert_date_format($date_string)
    {
        // Convert from dd-mm-yyyy to yyyy-mm-dd
        if (empty($date_string)) {
            return null;
        }
        
        $parts = explode('-', $date_string);
        if (count($parts) === 3) {
            return $parts[2] . '-' . $parts[1] . '-' . $parts[0];
        }
        
        return $date_string;
    }

    function delete($id)
    {
        $this->M_sw_invoice->delete($id);
        echo json_encode(array("status" => TRUE));
    }
}
