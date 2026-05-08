<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sw_confirm_letter extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_sw_confirm_letter');
        $this->M_login->getsecurity();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $data['title'] = "backend/sw_confirm_letter/sw_confirm_letter_list";
        $data['titleview'] = "Confirmation Letter";
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
        $list = $this->M_sw_confirm_letter->get_datatables($status);
        $data = array();
        $no = $_POST['start'];

        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        $read = $akses->view_level;
        $edit = $akses->edit_level;
        $delete = $akses->delete_level;
        $print = $akses->print_level;

        foreach ($list as $field) {

            // MENENTUKAN ACTION APA YANG AKAN DITAMPILKAN DI LIST DATA TABLES
            $action_read = ($read == 'Y') ? '<a href="sw_confirm_letter/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Read"><i class="fa fa-file-pdf"></i></a>&nbsp;' : '';
            $action_edit = ($edit == 'Y') ? '<a href="sw_confirm_letter/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;' : '';
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
            $row[] = $field->venue;
            $row[] = date('d-m-Y H:i:s', strtotime($field->created_at));
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_sw_confirm_letter->count_all(),
            "recordsFiltered" => $this->M_sw_confirm_letter->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    // function read_form($id)
    // {
    //     $mpdf = new \Mpdf\Mpdf(['format' => 'A4']);
    //     $data = $this->M_sw_confirm_letter->get_by_id($id);
    //     $data2 = $this->load->view('backend/sw_confirm_letter/sw_confirm_letter_pdf', $data, TRUE);
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
        $mpdf->SetHTMLHeader('
            <div style="text-align: left;">
                <img src="assets/backend/img/sebelaswarna.png" width="175">
            </div>
        ');

        $mpdf->SetHTMLFooter('
            <div style="text-align: center; font-size: 12px;">
                PT SOBAT WISATA DUNIA<br>
                Kp. Tunggilis RT 001 RW 007,<br>
                Kelurahan Situsari, Kec. Cileungsi, Kota Bogor.<br>
                0812-8222-9700 | <a href="http://www.sebelaswarna.com" target="_blank">www.sebelaswarna.com</a>
            </div>
        ');

        $mpdf->showWatermarkImage = true;

        $data = $this->M_sw_confirm_letter->get_by_id_print($id);
        $mpdf->SetTitle('Confirmation Letter - ' . $data->company_name);
        $html = $this->load->view('backend/sw_confirm_letter/sw_confirm_letter_pdf', $data, TRUE);

        $mpdf->WriteHTML($html);
        $mpdf->Output('Confirmation Letter - ' . $data->company_name . '.pdf', 'I');
    }

    // function read_form($id)
    // {
    //     $data['master'] = $this->M_sw_confirm_letter->get_by_id($id);
    //     $data['title_view'] = "Data Hotel";
    //     $data['title'] = 'backend/sw_confirm_letter/sw_confirm_letter_read';
    //     $this->load->view('backend/home', $data);
    // }

    function add_form()
    {
        $data['id'] = 0;
        $data['title_view'] = "Confirmation Letter Form";
        $data['title'] = 'backend/sw_confirm_letter/sw_confirm_letter_form';
        $this->load->view('backend/home', $data);
    }

    function edit_form($id)
    {
        $data['id'] = $id;
        $data['title_view'] = "Edit Confirmation Letter";
        $data['title'] = 'backend/sw_confirm_letter/sw_confirm_letter_form';
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
            ->count_all_results('sw_confirm_letter');
        
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
        $master = $this->M_sw_confirm_letter->get_by_id($id);
        $items = $this->db->get_where('sw_confirm_letter_detail', ['letter_id' => $id])->result();
        
        $data = array(
            'id' => $master->id,
            'letter_number' => $master->letter_number,
            'letter_date' => $master->letter_date,
            'company_name' => $master->company_name,
            'event_type' => $master->event_type,
            'venue' => $master->venue,
            'setup' => $master->setup,
            'start_date' => $master->start_date,
            'end_date' => $master->end_date,
            'start_time' => $master->start_time,
            'end_time' => $master->end_time,
            'total_amount' => $master->total_amount,
            'dp_date' => $master->dp_date,
            'final_date' => $master->final_date,
            'items' => $items
        );
        echo json_encode($data);
    }

    function get_id($id)
    {
        $data = $this->M_sw_confirm_letter->get_by_id($id);
        echo json_encode($data);
    }

    public function add()
    {
        // Convert date format from dd-mm-yyyy to yyyy-mm-dd
        $letter_date = $this->convert_date_format($this->input->post('letter_date'));
        $start_date = $this->convert_date_format($this->input->post('start_date'));
        $end_date = $this->convert_date_format($this->input->post('end_date'));
        $dp_date = $this->convert_date_format($this->input->post('dp_date'));
        $final_date = $this->convert_date_format($this->input->post('final_date'));

        // Clean currency values - remove dots for database storage
        $total_amount = str_replace('.', '', $this->input->post('total_amount')) ?: 0;

        $data = array(
            'letter_number' => $this->input->post('letter_number'),
            'letter_date' => $letter_date,
            'company_name' => $this->input->post('company_name'),
            'event_type' => $this->input->post('event_type'),
            'venue' => $this->input->post('venue'),
            'setup' => $this->input->post('setup'),
            'start_date' => $start_date,
            'end_date' => $end_date,
            'start_time' => $this->input->post('start_time'),
            'end_time' => $this->input->post('end_time'),
            'total_amount' => $total_amount,
            'dp_date' => $dp_date,
            'final_date' => $final_date,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        );

        try {
            $letter_id = $this->M_sw_confirm_letter->save($data);
            
            if (!$letter_id) {
                throw new Exception('Failed to save letter data');
            }
            
            // Save item details
            $item_types = $this->input->post('item_type');
            $item_names = $this->input->post('item_name');
            $package_names = $this->input->post('package_name');
            $unit_prices_clean = $this->input->post('unit_price_clean');
            $quantities = $this->input->post('qty');
            $total_prices_clean = $this->input->post('total_price_clean');
            
            if (!empty($item_types)) {
                foreach ($item_types as $i => $val) {
                    if (empty($val)) continue;

                    $item_detail = array(
                        'letter_id' => $letter_id,
                        'item_type' => $val,
                        'item_name' => $item_names[$i] ?? '',
                        'package_name' => $package_names[$i] ?? '',
                        'unit_price' => isset($unit_prices_clean[$i]) ? intval(str_replace('.', '', $unit_prices_clean[$i])) : 0,
                        'qty' => isset($quantities[$i]) ? intval($quantities[$i]) : 0,
                        'total_price' => isset($total_prices_clean[$i]) ? intval(str_replace('.', '', $total_prices_clean[$i])) : 0,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    );

                    $this->db->insert('sw_confirm_letter_detail', $item_detail);
                }
            }
            
            echo json_encode(array("status" => TRUE, "message" => "Data saved successfully"));
        } catch (Exception $e) {
            // Rollback master if items failed
            if (isset($letter_id) && $letter_id) {
                $this->db->where('id', $letter_id);
                $this->db->delete('sw_confirm_letter');
            }
            echo json_encode(array("status" => FALSE, "error" => "An error occurred: " . $e->getMessage()));
        }
    }

    public function update()
    {
        $id = $this->input->post('id');
        
        // Convert date format from dd-mm-yyyy to yyyy-mm-dd
        $letter_date = $this->convert_date_format($this->input->post('letter_date'));
        $start_date = $this->convert_date_format($this->input->post('start_date'));
        $end_date = $this->convert_date_format($this->input->post('end_date'));
        $dp_date = $this->convert_date_format($this->input->post('dp_date'));
        $final_date = $this->convert_date_format($this->input->post('final_date'));

        // Clean currency values - remove dots for database storage
        $total_amount = str_replace('.', '', $this->input->post('total_amount')) ?: 0;

        $data = array(
            'letter_number' => $this->input->post('letter_number'),
            'letter_date' => $letter_date,
            'company_name' => $this->input->post('company_name'),
            'event_type' => $this->input->post('event_type'),
            'venue' => $this->input->post('venue'),
            'setup' => $this->input->post('setup'),
            'start_date' => $start_date,
            'end_date' => $end_date,
            'start_time' => $this->input->post('start_time'),
            'end_time' => $this->input->post('end_time'),
            'total_amount' => $total_amount,
            'dp_date' => $dp_date,
            'final_date' => $final_date,
            'updated_at' => date('Y-m-d H:i:s')
        );

        try {
            $this->db->where('id', $id);
            if (!$this->db->update('sw_confirm_letter', $data)) {
                throw new Exception('Failed to update letter data');
            }
            
            // Delete old item details and insert new ones
            $this->db->where('letter_id', $id);
            $this->db->delete('sw_confirm_letter_detail');
            
            // Save item details
            $item_types = $this->input->post('item_type');
            $item_names = $this->input->post('item_name');
            $package_names = $this->input->post('package_name');
            $unit_prices_clean = $this->input->post('unit_price_clean');
            $quantities = $this->input->post('qty');
            $total_prices_clean = $this->input->post('total_price_clean');
            
            if (!empty($item_types)) {
                $this->db->where('letter_id', $id);
                $this->db->delete('sw_confirm_letter_detail');

                foreach ($item_types as $i => $val) {

                    if (empty($val)) continue;

                    $item_detail = array(
                        'letter_id' => $id,
                        'item_type' => $val,
                        'item_name' => $item_names[$i] ?? '',
                        'package_name' => $package_names[$i] ?? '',
                        'unit_price' => isset($unit_prices_clean[$i]) ? intval(str_replace('.', '', $unit_prices_clean[$i])) : 0,
                        'qty' => isset($quantities[$i]) ? intval($quantities[$i]) : 0,
                        'total_price' => isset($total_prices_clean[$i]) ? intval(str_replace('.', '', $total_prices_clean[$i])) : 0,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    );

                    if (!$this->db->insert('sw_confirm_letter_detail', $item_detail)) {
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
        $this->M_sw_confirm_letter->delete($id);
        echo json_encode(array("status" => TRUE));
    }
}
