<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_pu_invoice extends CI_Model
{
    // INISIASI VARIABLE
    var $id = 'id';
    var $table = 'pu_invoice';
    var $table2 = 'pu_rek_invoice';
    var $column_order = array(null, null, 'pu_order.id', 'pu_invoice.id', 'pu_order.created_at', 'ctc_nama', 'pu_order.status', 'pu_order.total_order', 'kode_order');
    var $column_search = array('pu_order.id', 'pu_invoice.id', 'pu_order.created_at', 'ctc_nama', 'pu_order.status', 'pu_order.total_order', 'kode_order');
    var $order = array('pu_order.id' => 'desc');


    function _get_datatables_query()
    {
        $this->db->select('
            pu_order.id AS pu_order_id,
            pu_order.created_at AS pu_order_createdAt,
            pu_order.kode_order,
            pu_order.status,
            pu_order.total_order,
            MAX(pu_invoice.ctc_nama) AS ctc_nama -- contoh ambil salah satu nama invoice
        ');
        $this->db->from('pu_order');
        $this->db->join('pu_invoice', 'pu_order.id = pu_invoice.order_id', 'left');
        $this->db->group_by('pu_order.id');

        // Mapping bulan Indonesia ke angka
        $bulan_indonesia = [
            'januari' => '01',
            'februari' => '02',
            'maret' => '03',
            'april' => '04',
            'mei' => '05',
            'juni' => '06',
            'juli' => '07',
            'agustus' => '08',
            'september' => '09',
            'oktober' => '10',
            'november' => '11',
            'desember' => '12',
        ];

        $search_value = strtolower(trim($_POST['search']['value'] ?? ''));
        $tahun_sekarang = date('Y');
        $tanggal_db = false;
        $bulan_tahun_like = false;
        $bulan_saja_like = false;

        if ($search_value) {
            // Format: 6 juni 2025
            if (preg_match('/(\d{1,2})\s+([a-z]+)\s+(\d{4})/', $search_value, $match)) {
                $hari = str_pad($match[1], 2, '0', STR_PAD_LEFT);
                $bulan = $bulan_indonesia[$match[2]] ?? null;
                $tahun = $match[3];
                if ($bulan) {
                    $tanggal_db = "$tahun-$bulan-$hari";
                }
            }
            // Format: 6 juni (tanpa tahun)
            else if (preg_match('/(\d{1,2})\s+([a-z]+)/', $search_value, $match)) {
                $hari = str_pad($match[1], 2, '0', STR_PAD_LEFT);
                $bulan = $bulan_indonesia[$match[2]] ?? null;
                if ($bulan) {
                    $tanggal_db = "$tahun_sekarang-$bulan-$hari";
                }
            }
            // Format: juni 2025
            else if (preg_match('/([a-z]+)\s+(\d{4})/', $search_value, $match)) {
                $bulan = $bulan_indonesia[$match[1]] ?? null;
                $tahun = $match[2];
                if ($bulan) {
                    $bulan_tahun_like = "$tahun-$bulan"; // akan dipakai di LIKE '2025-06%'
                }
            }
            // Format: juni
            else if (preg_match('/\b([a-z]+)\b/', $search_value, $match)) {
                $bulan = $bulan_indonesia[$match[1]] ?? null;
                if ($bulan) {
                    $bulan_saja_like = "-$bulan-"; // akan dipakai di LIKE '%-06-%'
                }
            }
        }

        $i = 0;
        foreach ($this->column_search as $item) {
            if ($search_value) {
                if ($i === 0) {
                    $this->db->group_start();
                }

                // KHUSUS untuk kolom tanggal
                if (in_array($item, ['pu_order.created_at'])) {
                    if ($tanggal_db) {
                        $this->db->or_like($item, $tanggal_db);
                    } elseif ($bulan_tahun_like) {
                        $this->db->or_like($item, $bulan_tahun_like);
                    } elseif ($bulan_saja_like) {
                        $this->db->or_like($item, $bulan_saja_like);
                    }
                } else {
                    // Kolom lain hanya jika tidak sedang cari tanggal
                    if (!$tanggal_db && !$bulan_tahun_like && !$bulan_saja_like) {
                        $this->db->or_like($item, $search_value);
                    }
                }

                if ($i === count($this->column_search) - 1) {
                    $this->db->group_end();
                }
            }
            $i++;
        }

        if (isset($_POST['status'])) {
            if ($_POST['status'] === '1') {
                $this->db->where('pu_order.status', 1);
            } elseif ($_POST['status'] === '0') {
                $this->db->where('pu_order.status', 0);
            }
        }


        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    // UNTUK MENAMPILKAN HASIL QUERY KE DATA TABLES
    function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->select('
            pu_order.id AS pu_order_id,
            pu_order.created_at AS pu_order_createdAt,
            pu_order.kode_order,
            pu_order.status,
            pu_order.total_order,
            MAX(pu_invoice.ctc_nama) AS ctc_nama -- contoh ambil salah satu nama invoice
        ');
        $this->db->from('pu_order');
        $this->db->join('pu_invoice', 'pu_order.id = pu_invoice.order_id', 'left');
        $this->db->group_by('pu_order.id');

        // Mapping bulan Indonesia ke angka
        $bulan_indonesia = [
            'januari' => '01',
            'februari' => '02',
            'maret' => '03',
            'april' => '04',
            'mei' => '05',
            'juni' => '06',
            'juli' => '07',
            'agustus' => '08',
            'september' => '09',
            'oktober' => '10',
            'november' => '11',
            'desember' => '12',
        ];

        $search_value = strtolower(trim($_POST['search']['value'] ?? ''));
        $tahun_sekarang = date('Y');
        $tanggal_db = false;
        $bulan_tahun_like = false;
        $bulan_saja_like = false;

        if ($search_value) {
            // Format: 6 juni 2025
            if (preg_match('/(\d{1,2})\s+([a-z]+)\s+(\d{4})/', $search_value, $match)) {
                $hari = str_pad($match[1], 2, '0', STR_PAD_LEFT);
                $bulan = $bulan_indonesia[$match[2]] ?? null;
                $tahun = $match[3];
                if ($bulan) {
                    $tanggal_db = "$tahun-$bulan-$hari";
                }
            }
            // Format: 6 juni (tanpa tahun)
            else if (preg_match('/(\d{1,2})\s+([a-z]+)/', $search_value, $match)) {
                $hari = str_pad($match[1], 2, '0', STR_PAD_LEFT);
                $bulan = $bulan_indonesia[$match[2]] ?? null;
                if ($bulan) {
                    $tanggal_db = "$tahun_sekarang-$bulan-$hari";
                }
            }
            // Format: juni 2025
            else if (preg_match('/([a-z]+)\s+(\d{4})/', $search_value, $match)) {
                $bulan = $bulan_indonesia[$match[1]] ?? null;
                $tahun = $match[2];
                if ($bulan) {
                    $bulan_tahun_like = "$tahun-$bulan"; // akan dipakai di LIKE '2025-06%'
                }
            }
            // Format: juni
            else if (preg_match('/\b([a-z]+)\b/', $search_value, $match)) {
                $bulan = $bulan_indonesia[$match[1]] ?? null;
                if ($bulan) {
                    $bulan_saja_like = "-$bulan-"; // akan dipakai di LIKE '%-06-%'
                }
            }
        }

        $i = 0;
        foreach ($this->column_search as $item) {
            if ($search_value) {
                if ($i === 0) {
                    $this->db->group_start();
                }

                // KHUSUS untuk kolom tanggal
                if (in_array($item, ['pu_order.created_at'])) {
                    if ($tanggal_db) {
                        $this->db->or_like($item, $tanggal_db);
                    } elseif ($bulan_tahun_like) {
                        $this->db->or_like($item, $bulan_tahun_like);
                    } elseif ($bulan_saja_like) {
                        $this->db->or_like($item, $bulan_saja_like);
                    }
                } else {
                    // Kolom lain hanya jika tidak sedang cari tanggal
                    if (!$tanggal_db && !$bulan_tahun_like && !$bulan_saja_like) {
                        $this->db->or_like($item, $search_value);
                    }
                }

                if ($i === count($this->column_search) - 1) {
                    $this->db->group_end();
                }
            }
            $i++;
        }

        if ($_POST['status'] === '1') {
            $this->db->where('pu_order.status', 1);
        } elseif ($_POST['status'] === '0') {
            $this->db->where('pu_order.status', 0);
        }

        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }

        return $this->db->count_all_results();
    }

    // GET BY ID TABLE INVOICE MASTER
    public function get_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('pu_invoice')->row();
    }

    // GET BY ID KWITANSI
    public function get_kwitansi($id)
    {
        $this->db->where('id', $id);
        $this->db->from('pu_kwitansi');
        return $this->db->get()->row();
    }

    // GET BY ID TABLE DETAIL INVOICE TRANSAKSI
    public function get_detail($id)
    {
        $this->db->where('invoice_id', $id);
        return $this->db->get('pu_detail_invoice')->result();
    }

    public function getInvoiceData($id)
    {
        $this->db->select('id, tgl_invoice, kode_invoice, travel_id, total_tagihan, jamaah, detail_pesanan, diskon, tgl_tempo, ctc_nama, ctc_alamat, ctc_email, keterangan');
        $this->db->from('pu_invoice');
        $this->db->where('id', $id);
        $data = $this->db->get()->row_array();
        return $data;
    }

    // UNTUK QUERY MENGAMBIL KODE UNTUK DIGENERATE DI CONTROLLER
    public function max_kode($date)
    {
        $formatted_date = date('ym', strtotime($date));
        $this->db->select('kode_invoice');
        $where = 'id=(SELECT max(id) FROM pu_invoice where SUBSTRING(kode_invoice, 6, 4) = ' . $formatted_date . ')';
        $this->db->where($where);
        $this->db->from('pu_invoice');
        $query = $this->db->get();
        return $query;
    }

    public function max_kode_order($date)
    {
        $year = date('y', strtotime($date));

        $this->db->select('kode_order');
        $this->db->from('pu_order');
        $this->db->where("SUBSTRING(kode_order, 4, 2) = '$year'");
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);

        return $this->db->get(); // ⬅️ return objek query
    }


    // UNTUK QUERY MENENTUKAN SIAPA YANG MELAKUKAN APPROVAL
    public function approval($id)
    {
        $this->db->select('app_id, app2_id');
        $this->db->from('tbl_data_user');
        $this->db->where('id_user', $id);
        $query = $this->db->get();
        return $query->row();
    }

    // UNTUK QUERY INSERT DATA PREPAYMENT
    public function save($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    // UNTUK QUERY INSERT DATA KE PU_DETAIL_INVOICE
    public function save_detail2($data)
    {
        $this->db->insert_batch('pu_detail_invoice', $data);
        return $this->db->insert_id();
    }

    // MENGHAPUS ORDER
    public function delete_order($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('pu_order');

        return true;
    }

    // UNTUK QUERY DELETE DATA PREPAYMENT
    public function delete($id)
    {
        // Hapus data master
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);

        // Hapus data detail
        $this->db->where('invoice_id', $id);
        $this->db->delete('pu_detail_invoice');

        // Hapus data kwitansi jika ada
        // Cek apakah ada kwitansi dengan invoice_id = $id
        $this->db->where('id_invoice', $id);
        $query = $this->db->get('pu_kwitansi');
        if ($query->num_rows() > 0) {
            $this->db->where('invoice_id', $id);
            $this->db->delete('pu_kwitansi');
        }

        return true;
    }

    // OPSI REKENING
    public function options()
    {
        return $this->db->distinct()->select('id, travel, nama_bank, no_rek')->from('pu_travel')->get();
    }

    // PAYMENT
    public function save_kwitansi($data)
    {
        $this->db->insert('pu_kwitansi', $data);
        return $this->db->insert_id();
    }
}
