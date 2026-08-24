<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_sw_costructure extends CI_Model
{
    private $table_master = 'sw_costructure';
    private $table_category = 'sw_categories_costructure';
    private $table_detail = 'sw_costructure_detail';
    private $column_search = array('company_name', 'event_type');
    private $column_order = array(null, null, 'company_name', 'event_type', 'number_of_participants', 'grand_total', 'received_by_eo', 'created_at');
    private $order = array('id' => 'desc');

    public function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query()
    {
        $this->db->from($this->table_master);

        $i = 0;
        foreach ($this->column_search as $item) {
            if ($_POST['search']['value']) {
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i)
                    $this->db->group_end();
            }
            $i++;
        }

        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    // ========== GET DATATABLES ==========
    /**
     * Get data untuk datatables list view
     */
    public function get_datatables($status = null)
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Count filtered records
     */
    public function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    /**
     * Count all records
     */
    public function count_all()
    {
        $this->db->from($this->table_master);
        return $this->db->count_all_results();
    }

    // ========== GET SINGLE RECORD ==========
    /**
     * Get master data by ID
     */
    public function get_by_id($id)
    {
        return $this->db
            ->where('id', $id)
            ->get($this->table_master)
            ->row();
    }

    /**
     * Get complete cost structure dengan semua kategori dan item
     * Digunakan untuk edit dan display
     */
    public function get_complete_data($id)
    {
        // Get master data
        $master = $this->db
            ->where('id', $id)
            ->get($this->table_master)
            ->row();

        if (!$master) {
            return null;
        }

        // Get categories dengan item-nya
        $categories = $this->db
            ->where('costructure_id', $id)
            ->order_by('sort_order', 'ASC')
            ->get($this->table_category)
            ->result();

        // Get items untuk setiap category
        if (!empty($categories)) {
            foreach ($categories as $category) {
                $category->items = $this->db
                    ->where('category_id', $category->id)
                    ->order_by('sort_order', 'ASC')
                    ->get($this->table_detail)
                    ->result();
            }
        }

        $master->categories = $categories;
        return $master;
    }

    /**
     * Get data untuk PDF export
     */
    public function get_by_id_print($id)
    {
        return $this->get_complete_data($id);
    }

    // ========== SAVE DATA (CREATE + NESTED INSERT) ==========
    /**
     * Simpan cost structure dengan categories dan items
     * Menggunakan transaction untuk consistency
     * 
     * @param array $data Master data
     * @param array $categories Array of categories dengan items
     * @return int Cost structure ID or false if failed
     */
    public function save_with_categories($data, $categories)
    {
        $this->db->trans_start();

        try {
            // 1. Insert master data
            $this->db->insert($this->table_master, $data);
            $costructure_id = $this->db->insert_id();

            if (!$costructure_id) {
                throw new Exception('Failed to insert master data');
            }

            // 2. Insert categories dengan items
            if (!empty($categories)) {
                $sort_order = 0;
                foreach ($categories as $index => $category_data) {
                    // Insert category
                    $category_insert = array(
                        'costructure_id' => $costructure_id,
                        'name' => $index + 1 . '. ' . $category_data['name'],
                        'sort_order' => $sort_order,
                        'created_at' => date('Y-m-d H:i:s')
                    );

                    $this->db->insert($this->table_category, $category_insert);
                    $category_id = $this->db->insert_id();

                    if (!$category_id) {
                        throw new Exception('Failed to insert category');
                    }

                    // Insert items untuk category ini
                    if (isset($category_data['items']) && !empty($category_data['items'])) {
                        $item_sort_order = 0;
                        foreach ($category_data['items'] as $item_data) {
                            $item_insert = array(
                                'category_id' => $category_id,
                                'name' => $item_data['name'],
                                'qty' => intval($item_data['qty']),
                                'price' => floatval(str_replace('.', '', $item_data['price'])),
                                'subtotal' => floatval(str_replace('.', '', $item_data['subtotal'])),
                                'sort_order' => $item_sort_order,
                                'created_at' => date('Y-m-d H:i:s')
                            );

                            $this->db->insert($this->table_detail, $item_insert);
                            if ($this->db->affected_rows() === 0) {
                                throw new Exception('Failed to insert item');
                            }

                            $item_sort_order++;
                        }
                    }

                    $sort_order++;
                }
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                return false;
            }

            return $costructure_id;
        } catch (Exception $e) {
            $this->db->trans_rollback();
            log_message('error', 'M_sw_costructure::save_with_categories - ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update cost structure dengan categories dan items
     * Menggunakan transaction
     * 
     * @param int $id Cost structure ID
     * @param array $data Master data
     * @param array $categories Array of categories dengan items
     * @return bool
     */
    public function update_with_categories($id, $data, $categories)
    {
        $this->db->trans_start();

        try {
            // 1. Update master data
            $this->db->where('id', $id);
            $this->db->update($this->table_master, $data);

            // 2. Delete all existing categories dan items (cascade)
            $this->db->where('costructure_id', $id);
            $this->db->delete($this->table_category);

            // 3. Insert new categories dan items
            if (!empty($categories)) {
                $sort_order = 0;
                foreach ($categories as $category_data) {
                    $category_insert = array(
                        'costructure_id' => $id,
                        'name' => $category_data['name'],
                        'sort_order' => $sort_order,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    );

                    $this->db->insert($this->table_category, $category_insert);
                    $category_id = $this->db->insert_id();

                    if (!$category_id) {
                        throw new Exception('Failed to insert category');
                    }

                    // Insert items
                    if (isset($category_data['items']) && !empty($category_data['items'])) {
                        $item_sort_order = 0;
                        foreach ($category_data['items'] as $item_data) {
                            $item_insert = array(
                                'category_id' => $category_id,
                                'name' => $item_data['name'],
                                'qty' => intval($item_data['qty']),
                                'price' => floatval(str_replace('.', '', $item_data['price'])),
                                'subtotal' => floatval(str_replace('.', '', $item_data['subtotal'])),
                                'sort_order' => $item_sort_order,
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s')
                            );

                            $this->db->insert($this->table_detail, $item_insert);
                            if ($this->db->affected_rows() === 0) {
                                throw new Exception('Failed to insert item');
                            }

                            $item_sort_order++;
                        }
                    }

                    $sort_order++;
                }
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                return false;
            }

            return true;
        } catch (Exception $e) {
            $this->db->trans_rollback();
            log_message('error', 'M_sw_costructure::update_with_categories - ' . $e->getMessage());
            return false;
        }
    }

    // ========== DELETE ==========
    /**
     * Delete cost structure dan cascade delete categories + items
     */
    public function delete($id)
    {
        $this->db->trans_start();

        try {
            // Delete akan cascade otomatis karena FOREIGN KEY ON DELETE CASCADE
            $this->db->where('id', $id);
            $this->db->delete($this->table_master);

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                return false;
            }

            return true;
        } catch (Exception $e) {
            $this->db->trans_rollback();
            log_message('error', 'M_sw_costructure::delete - ' . $e->getMessage());
            return false;
        }
    }

    // ========== HELPER METHODS ==========
    /**
     * Calculate grand total dari semua items
     */
    public function calculate_grand_total($id)
    {
        $result = $this->db
            ->select('SUM(subtotal) as total')
            ->where('category_id IN (SELECT id FROM ' . $this->table_category . ' WHERE costructure_id = ' . $id . ')')
            ->get($this->table_detail)
            ->row();

        return $result ? $result->total : 0;
    }

    /**
     * Calculate category subtotal
     */
    public function calculate_category_subtotal($category_id)
    {
        $result = $this->db
            ->select('SUM(subtotal) as total')
            ->where('category_id', $category_id)
            ->get($this->table_detail)
            ->row();

        return $result ? $result->total : 0;
    }

    /**
     * Update category subtotal
     */
    public function update_category_subtotal($category_id)
    {
        $subtotal = $this->calculate_category_subtotal($category_id);

        $this->db->where('id', $category_id);
        return $this->db->update($this->table_category, array(
            'subtotal' => $subtotal,
            'updated_at' => date('Y-m-d H:i:s')
        ));
    }
}
