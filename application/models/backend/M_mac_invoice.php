<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_mac_invoice extends CI_Model
{
    var $id           = 'id';
    var $table        = 'mac_invoice';
    var $column_order = array(
        null,           // 0: No
        null,           // 1: Action
        'a.payment_status',
        'a.invoice_number',
        'b.customer_name',
        'a.nopol',
        'a.awal_service',
        'a.sub_total',
        'a.app_status',
        'a.created_at',
        'a.created_by'
    );
    var $column_search = array(
        'a.invoice_number',
        'b.customer_name',
        'a.nopol',
        'a.awal_service',
        'a.sub_total',
        'a.payment_status',
        'a.created_at',
        'a.created_by'
    );
    var $order = array('a.id' => 'desc');

    public function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query($filter_status = '', $filter_payment = '', $filter_date_start = '', $filter_date_end = '', $cabang_id = null)
    {
        $this->db->select('a.id, a.invoice_number, b.customer_name, a.nopol, a.awal_service, a.sub_total, a.app_status, a.app_date, a.payment_status, a.created_at, a.created_by');
        $this->db->from($this->table . ' a');
        $this->db->join('mac_customer b', 'a.customer_id = b.id', 'left');
        $this->db->where('a.is_active', 1);

        // Filter cabang — jika ada, filter per cabang
        // Jika null (Nasional semua cabang), tampil semua
        if (!is_null($cabang_id)) {
            $this->db->where('a.cabang_id', $cabang_id);
        }

        // Filter approval berdasarkan username HANYA jika bukan nasional
        // dan bukan approver — agar user biasa hanya lihat invoice sendiri
        $username    = $this->session->userdata('username');
        $is_nasional = $this->session->userdata('is_nasional') ? true : false;
        $can_approve = in_array($username, ['dwi', 'bhakti']);

        if (!$is_nasional && !$can_approve) {
            $this->db->where('a.created_by', $username);
        }

        if ($filter_status !== '' && $filter_status !== null) {
            $this->db->where('a.app_status', $filter_status);
        }
        if ($filter_payment !== '' && $filter_payment !== null) {
            $this->db->where('a.payment_status', $filter_payment);
        }
        if (!empty($filter_date_start)) {
            $parts = explode('-', $filter_date_start);
            $this->db->where('DATE(a.invoice_date) >=', $parts[2] . '-' . $parts[1] . '-' . $parts[0]);
        }
        if (!empty($filter_date_end)) {
            $parts = explode('-', $filter_date_end);
            $this->db->where('DATE(a.invoice_date) <=', $parts[2] . '-' . $parts[1] . '-' . $parts[0]);
        }

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
        } elseif (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function get_datatables($filter_status = '', $filter_payment = '', $filter_date_start = '', $filter_date_end = '', $cabang_id = null)
    {
        $this->_get_datatables_query($filter_status, $filter_payment, $filter_date_start, $filter_date_end, $cabang_id);
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        return $this->db->get()->result();
    }

    public function count_filtered($filter_status = '', $filter_payment = '', $filter_date_start = '', $filter_date_end = '', $cabang_id = null)
    {
        $this->_get_datatables_query($filter_status, $filter_payment, $filter_date_start, $filter_date_end, $cabang_id);
        return $this->db->get()->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function get_by_id($id)
    {
        $this->db->where($this->id, $id);
        return $this->db->get($this->table)->row();
    }

    public function get_by_id_print($id)
    {
        $header = $this->db
            ->get_where($this->table, [$this->id => $id])
            ->row();

        if (!$header) return null;

        $details        = $this->db->get_where('mac_invoice_detail', ['invoice_id' => $id])->result();
        $header->items  = $details;

        return $header;
    }

    public function save($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function delete($id)
    {
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
    }
}