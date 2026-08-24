<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_mac_peminjaman extends CI_Model
{
    var $table         = 'mac_peminjaman';
    var $table_detail  = 'mac_peminjaman_detail';
    var $table_log     = 'mac_peminjaman_log';
    var $column_order  = [null, null, 'kode_pinjam', 'peminjam', 'tgl_pinjam', 'tgl_kembali', 'status'];
    var $column_search = ['kode_pinjam', 'peminjam', 'status'];
    var $order         = ['id' => 'desc'];

    public function __construct()
    {
        parent::__construct();
    }

    // ================================================================
    // DATATABLES
    // ================================================================

    private function _get_datatables_query()
    {
        $this->db->select('mp.*, tbl_data_user.name')
            ->from($this->table . ' mp')
            ->join('tbl_data_user', 'tbl_data_user.id_user = mp.id_user', 'left');

        $i = 0;
        foreach ($this->column_search as $item) {
            if (!empty($_POST['search']['value'])) {
                if ($i === 0) {
                    $this->db->group_start()
                        ->like('mp.' . $item, $_POST['search']['value']);
                } else {
                    $this->db->or_like('mp.' . $item, $_POST['search']['value']);
                }
                if (count($this->column_search) - 1 == $i) {
                    $this->db->group_end();
                }
            }
            $i++;
        }

        // Filter status
        if (!empty($_POST['status'])) {
            $this->db->where('mp.status', $_POST['status']);
        }

        // Filter user (tab personal)
        if (!empty($_POST['tab']) && $_POST['tab'] === 'personal') {
            $this->db->where('mp.id_user', $this->session->userdata('id_user'));
        }

        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        return $this->db->get()->result();
    }

    public function count_filtered()
    {
        $this->_get_datatables_query();
        return $this->db->get()->num_rows();
    }

    public function count_all()
    {
        return $this->db->count_all($this->table);
    }

    // ================================================================
    // CRUD
    // ================================================================

    public function get_by_id($id)
    {
        return $this->db
            ->select("
                p.*,
                c.nama_cabang
            ")
            ->from($this->table . " p")
            ->join(
                "mac_cabang c",
                "c.id = p.cabang_id",
                "left"
            )
            ->where("p.id", $id)
            ->get()
            ->row();
    }

    public function get_detail($peminjaman_id)
    {
        return $this->db
            ->select("
                d.*,
                i.nama_produk,
                i.kode_produk,
                i.satuan,
                COALESCE(s.stok_saat_ini,0) AS stok_saat_ini
            ", false)
            ->from($this->table_detail . ' d')

            ->join(
                $this->table . ' p',
                'p.id = d.peminjaman_id',
                'left'
            )

            ->join(
                'mac_inventory i',
                'i.id = d.inventory_id',
                'left'
            )

            ->join(
                'mac_inventory_stok s',
                's.inventory_id = d.inventory_id
                AND s.cabang_id = p.cabang_id',
                'left'
            )

            ->where('d.peminjaman_id', $peminjaman_id)

            ->get()
            ->result_array();
    }

    public function get_log($peminjaman_id)
    {
        return $this->db->select('l.*, tbl_data_user.name, i.nama_produk')
            ->from($this->table_log . ' l')
            ->join('tbl_data_user', 'tbl_data_user.id_user = l.created_by', 'left')
            ->join('mac_inventory i', 'i.id = l.inventory_id', 'left')
            ->where('l.peminjaman_id', $peminjaman_id)
            ->order_by('l.created_at', 'DESC')
            ->get()->result_array();
    }

    public function save($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function save_detail($data)
    {
        $this->db->insert_batch($this->table_detail, $data);
    }

    public function save_log($data)
    {
        $this->db->insert($this->table_log, $data);
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id)->update($this->table, $data);
    }

    public function update_detail($id, $data)
    {
        $this->db->where('id', $id)->update($this->table_detail, $data);
    }

    public function delete($id)
    {
        // Hapus log dan detail dulu sebelum header
        $this->db->where('peminjaman_id', $id)->delete($this->table_log);
        $this->db->where('peminjaman_id', $id)->delete($this->table_detail);
        $this->db->where('id', $id)->delete($this->table);
    }

    // ================================================================
    // KODE OTOMATIS: PMJ + YYMM + 4 DIGIT
    // ================================================================

    public function generate_kode()
    {
        $prefix = 'PMJ' . date('ym');
        $last   = $this->db->select('kode_pinjam')
            ->from($this->table)
            ->like('kode_pinjam', $prefix, 'after')
            ->order_by('id', 'DESC')
            ->limit(1)->get()->row();
        $urutan = empty($last) ? 1 : ((int) substr($last->kode_pinjam, -4) + 1);
        return $prefix . str_pad($urutan, 4, '0', STR_PAD_LEFT);
    }
}