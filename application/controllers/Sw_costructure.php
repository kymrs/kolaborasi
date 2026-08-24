<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sw_costructure extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('backend/M_sw_costructure');
        $this->M_login->getsecurity();
        date_default_timezone_set('Asia/Jakarta');
    }

    // ========== LIST VIEW ==========
    /**
     * Display list view dengan datatable
     */
    public function index()
    {
        $data['title'] = "backend/sw_costructure/sw_costructure_list";
        $data['titleview'] = "Cost Structure";
        $this->load->view('backend/home', $data);
    }

    /**
     * Get list data untuk datatable (AJAX)
     */
    public function get_list()
    {
        $list = $this->M_sw_costructure->get_datatables();
        $data = array();
        $no = $_POST['start'];

        // Get access control
        $akses = $this->M_app->hak_akses($this->session->userdata('id_level'), $this->router->fetch_class());
        $read = $akses->view_level;
        $edit = $akses->edit_level;
        $delete = $akses->delete_level;

        foreach ($list as $field) {
            // Build action buttons
            $action_read = ($read == 'Y') ? '<a href="sw_costructure/read_form/' . $field->id . '" class="btn btn-info btn-circle btn-sm" title="Preview/Read"><i class="fa fa-file-pdf"></i></a>&nbsp;' : '';
            $action_edit = ($edit == 'Y') ? '<a href="sw_costructure/edit_form/' . $field->id . '" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;' : '';
            $action_delete = ($delete == 'Y') ? '<a onclick="delete_data(' . $field->id . ')" class="btn btn-danger btn-circle btn-sm" title="Delete"><i class="fa fa-trash"></i></a>&nbsp;' : '';

            $action = $action_read . $action_edit . $action_delete;

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $action;
            $row[] = $field->company_name;
            $row[] = $field->event_type;
            $row[] = $field->number_of_participants;
            $row[] = 'Rp ' . number_format($field->grand_total ?? 0, 0, ',', '.');
            $row[] = 'Rp ' . number_format($field->received_by_eo ?? 0, 0, ',', '.');
            $row[] = date('d-m-Y H:i:s', strtotime($field->created_at));
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_sw_costructure->count_all(),
            "recordsFiltered" => $this->M_sw_costructure->count_filtered(),
            "data" => $data,
        );

        echo json_encode($output);
    }

    public function add_form()
    {
        $data['id'] = 0;
        $data['title_view'] = "Cost Structure Form";
        $data['title'] = 'backend/sw_costructure/sw_costructure_form';
        $this->load->view('backend/home', $data);
    }

    public function edit_form($id)
    {
        $data['id'] = $id;
        $data['title_view'] = "Edit Cost Structure";
        $data['title'] = 'backend/sw_costructure/sw_costructure_form';
        $this->load->view('backend/home', $data);
    }

    // ========== GET DATA (FOR EDIT/PREVIEW) ==========
    /**
     * Get complete data untuk edit (AJAX)
     * Return: master data + categories + items
     */
    public function get_data($id)
    {
        // Get all data dengan structure yang sesuai
        $master = $this->M_sw_costructure->get_complete_data($id);

        if (!$master) {
            echo json_encode(array('status' => FALSE, 'message' => 'Data not found'));
            return;
        }

        // Format data untuk frontend
        $response = array(
            'status' => TRUE,
            'id' => $master->id,
            'company_name' => $master->company_name,
            'event_type' => $master->event_type,
            'number_of_participants' => $master->number_of_participants,
            'margin' => $master->margin ?? 0,
            'fee_mediator' => $master->fee_mediator ?? 0,
            'cashback' => $master->cashback ?? 0,
            'grand_total' => $master->grand_total ?? 0,
            'received_by_eo' => $master->received_by_eo ?? 0,
            'adjustment' => $master->adjustment ?? 0,
            'rounding' => $master->rounding ?? 50000,
            'categories' => array()
        );

        // Format categories dengan items
        if (!empty($master->categories)) {
            foreach ($master->categories as $category) {
                $cat_data = array(
                    'id' => $category->id,
                    'name' => $category->name,
                    'subtotal' => $category->subtotal ?? 0,
                    'items' => array()
                );

                if (!empty($category->items)) {
                    foreach ($category->items as $item) {
                        $cat_data['items'][] = array(
                            'id' => $item->id,
                            'name' => $item->name,
                            'qty' => $item->qty,
                            'price' => $item->price,
                            'subtotal' => $item->subtotal
                        );
                    }
                }

                $response['categories'][] = $cat_data;
            }
        }

        echo json_encode($response);
    }

    // ========== SAVE/CREATE ==========
    /**
     * Save cost structure baru dengan categories dan items
     */
    public function add()
    {
        // Validate required fields
        if (!$this->input->post('company_name') || !$this->input->post('event_type')) {
            echo json_encode(array('status' => FALSE, 'message' => 'Company name and event type are required'));
            return;
        }

        try {
            // Prepare master data
            $master_data = array(
                'company_name' => $this->input->post('company_name'),
                'event_type' => $this->input->post('event_type'),
                'number_of_participants' => intval($this->input->post('number_of_participants')) ?: 0,
                'margin' => floatval(str_replace('.', '', $this->input->post('margin'))) ?: 0,
                'fee_mediator' => floatval(str_replace('.', '', $this->input->post('fee_mediator'))) ?: 0,
                'cashback' => floatval(str_replace('.', '', $this->input->post('cashback'))) ?: 0,
                'grand_total' => floatval(str_replace('.', '', $this->input->post('grand_total'))) ?: 0,
                'received_by_eo' => floatval(str_replace('.', '', $this->input->post('received_by_eo'))) ?: 0,
                'rounding' => intval($this->input->post('rounding')),
                'created_at' => date('Y-m-d H:i:s')
            );

            // Prepare categories dengan items
            $categories_input = $this->input->post('categories');
            $categories = array();

            if (!empty($categories_input)) {
                foreach ($categories_input as $category_data) {
                    if (empty($category_data['name'])) continue;

                    $category = array(
                        'name' => $category_data['name'],
                        'items' => array()
                    );

                    // Process items di category ini
                    if (isset($category_data['items']) && is_array($category_data['items'])) {
                        foreach ($category_data['items'] as $item_data) {
                            if (empty($item_data['name'])) continue;

                            $category['items'][] = array(
                                'name' => $item_data['name'],
                                'qty' => intval($item_data['qty']) ?: 1,
                                'price' => $item_data['price'] ?? 0,
                                'subtotal' => $item_data['subtotal'] ?? 0
                            );
                        }
                    }

                    if (!empty($category['items'])) {
                        $categories[] = $category;
                    }
                }
            }

            // Save menggunakan transaction
            $costructure_id = $this->M_sw_costructure->save_with_categories($master_data, $categories);

            if (!$costructure_id) {
                throw new Exception('Failed to save cost structure');
            }

            echo json_encode(array(
                'status' => TRUE,
                'message' => 'Cost structure saved successfully',
                'id' => $costructure_id
            ));
        } catch (Exception $e) {
            echo json_encode(array('status' => FALSE, 'message' => $e->getMessage()));
        }
    }

    // ========== UPDATE/EDIT ==========
    /**
     * Update existing cost structure
     */
    public function update()
    {
        $id = intval($this->input->post('id'));

        if (!$id) {
            echo json_encode(array('status' => FALSE, 'message' => 'Invalid ID'));
            return;
        }

        // Validate required fields
        if (!$this->input->post('company_name') || !$this->input->post('event_type')) {
            echo json_encode(array('status' => FALSE, 'message' => 'Company name and event type are required'));
            return;
        }

        try {
            // Prepare master data
            $master_data = array(
                'company_name' => $this->input->post('company_name'),
                'event_type' => $this->input->post('event_type'),
                'number_of_participants' => intval($this->input->post('number_of_participants')) ?: 0,
                'margin' => floatval(str_replace('.', '', $this->input->post('margin'))) ?: 0,
                'fee_mediator' => floatval(str_replace('.', '', $this->input->post('fee_mediator'))) ?: 0,
                'cashback' => floatval(str_replace('.', '', $this->input->post('cashback'))) ?: 0,
                'grand_total' => floatval(str_replace('.', '', $this->input->post('grand_total'))) ?: 0,
                'received_by_eo' => floatval(str_replace('.', '', $this->input->post('received_by_eo'))) ?: 0,
                'adjustment' => floatval(str_replace('.', '', $this->input->post('adjustment'))) ?: 0,
                'rounding' => intval($this->input->post('rounding'))
            );

            // Prepare categories dengan items
            $categories_input = $this->input->post('categories');
            $categories = array();

            if (!empty($categories_input)) {
                foreach ($categories_input as $index => $category_data) {
                    if (empty($category_data['name'])) continue;

                    $category = array(
                        'name' => $index + 1 . '. ' . $category_data['name'],
                        'items' => array()
                    );

                    if (isset($category_data['items']) && is_array($category_data['items'])) {
                        foreach ($category_data['items'] as $item_data) {
                            if (empty($item_data['name'])) continue;

                            $category['items'][] = array(
                                'name' => $item_data['name'],
                                'qty' => intval($item_data['qty']) ?: 1,
                                'price' => $item_data['price'] ?? 0,
                                'subtotal' => $item_data['subtotal'] ?? 0
                            );
                        }
                    }

                    if (!empty($category['items'])) {
                        $categories[] = $category;
                    }
                }
            }

            // Update menggunakan transaction
            $result = $this->M_sw_costructure->update_with_categories($id, $master_data, $categories);

            if (!$result) {
                throw new Exception('Failed to update cost structure');
            }

            echo json_encode(array(
                'status' => TRUE,
                'message' => 'Updated successfully'
            ));
        } catch (Exception $e) {
            echo json_encode(array('status' => FALSE, 'message' => $e->getMessage()));
        }
    }

    // ========== DELETE ==========
    /**
     * Delete cost structure (cascade delete categories + items)
     */
    public function delete()
    {
        $id = intval($this->input->post('id'));

        if (!$id) {
            echo json_encode(array('status' => FALSE, 'message' => 'Invalid ID'));
            return;
        }

        try {
            $result = $this->M_sw_costructure->delete($id);

            if (!$result) {
                throw new Exception('Failed to delete cost structure');
            }

            echo json_encode(array('status' => TRUE, 'message' => 'Cost structure deleted successfully'));
        } catch (Exception $e) {
            echo json_encode(array('status' => FALSE, 'message' => $e->getMessage()));
        }
    }

    // ========== READ/PREVIEW FORM ==========
    /**
     * Display read-only form untuk preview
     */
    public function read_form($id)
    {
        $data = $this->M_sw_costructure->get_complete_data($id);

        if (!$data) {
            show_404();
            return;
        }

        // Generate PDF
        $this->generate_pdf($id, $data);
    }

    // ========== PDF EXPORT ==========
    /**
     * Generate dan output PDF menggunakan mPDF
     */
    private function generate_pdf($id, $data = null)
    {
        if (!$data) {
            $data = $this->M_sw_costructure->get_complete_data($id);
        }

        if (!$data) {
            show_404();
            return;
        }

        try {
            // Initialize mPDF
            $mpdf = new \Mpdf\Mpdf([
                'format' => 'A4',
                'margin_top' => 32,
                'margin_left' => 15,
                'margin_right' => 15,
                'default_font' => 'helvetica'
            ]);

            $mpdf->SetAuthor('Sebelaswarna EO');
            $mpdf->SetSubject('Cost Structure - ' . $data->company_name);
            $mpdf->SetCreator('System Sebelaswarna');
            $mpdf->SetTitle('Cost Structure - ' . $data->company_name);

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

            // Load HTML
            $html = $this->load->view(
                'backend/sw_costructure/sw_costructure_pdf',
                ['data' => $data],
                TRUE
            );

            $mpdf->WriteHTML($html);

            $mpdf->Output(
                'Cost-Structure-' . $data->company_name . '.pdf',
                'I'
            );

        } catch (Exception $e) {
            log_message('error', 'Sw_costructure::generate_pdf - ' . $e->getMessage());

            show_error(
                'Error generating PDF: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Generate PDF dan download
     */
    public function download_pdf($id)
    {
        $data = $this->M_sw_costructure->get_complete_data($id);

        if (!$data) {
            show_404();
            return;
        }

        $this->generate_pdf($id, $data);
    }
}
