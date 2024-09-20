<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_penawaran_pu extends CI_Model
{
    // INISIASI VARIABLE
    var $id = 'id';
    var $table = 'tbl_prepayment';
    var $table2 = 'tbl_produk';
    var $column_order = array(null, null, 'no_pelayanan', 'pelanggan', 'tgl_berlaku', 'id_produk', 'created_at', 'catatan');
    var $column_search = array('no_pelayanan', 'pelanggan', 'tgl_berlaku', 'id_produk', 'created_at', 'catatan'); //field yang diizin untuk pencarian
    var $order = array('id' => 'desc');
}
