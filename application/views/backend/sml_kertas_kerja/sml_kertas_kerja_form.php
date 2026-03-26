<?php
// role aktif berdasarkan username login.
$username = strtolower(trim((string) $this->session->userdata('username')));

// Role context disuplai dari controller (berdasarkan tabel sml_akses_kertas_kerja)
$roles_for_user = isset($roles_for_user) ? (array) $roles_for_user : array();
$active_role = isset($active_role) ? strtolower(trim((string) $active_role)) : '';
$is_authorized = isset($is_authorized) ? (bool) $is_authorized : !empty($roles_for_user);
$is_create = isset($is_create) ? (bool) $is_create : (((int) $id) === 0);
$is_marketing = isset($is_marketing) ? (bool) $is_marketing : ($active_role === 'marketing');
$can_create = isset($can_create) ? (bool) $can_create : ($is_marketing && $is_authorized);
$can_update = isset($can_update) ? (bool) $can_update : ($is_authorized && !$is_create);

$all_modes = array('marketing', 'plotting', 'monitoring', 'finance');

if (!function_exists('kk_render_fields_grid')) {
    function kk_render_fields_grid($fields, $sec_readonly, $rupiah_fields)
    {
        foreach ((array) $fields as $f) {
            $name = isset($f['name']) ? (string) $f['name'] : '';
            $label = isset($f['label']) ? (string) $f['label'] : '';
            $type = isset($f['type']) ? (string) $f['type'] : 'text';
            $step = isset($f['step']) ? $f['step'] : null;

            // Skip placeholder fields (name kosong) agar tidak bikin id/name invalid dan kolom kosong.
            if ($name === '' && $type === 'hidden') {
                continue;
            }

            $is_rupiah = ($name !== '') ? in_array($name, (array) $rupiah_fields, true) : false;
            $is_periode = ($name === 'periode');
            $force_readonly = !empty($f['readonly']);
            ?>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="<?= $name ?>"><?= htmlspecialchars($label) ?></label>
                    <?php if ($type === 'select') { ?>
                        <select class="form-control form-control-sm" id="<?= $name ?>" name="<?= $name ?>" <?= (!$sec_readonly) ? 'required' : '' ?> <?= $sec_readonly ? 'disabled' : '' ?>>
                            <option value="">- pilih -</option>
                            <?php foreach ((array) $f['options'] as $opt) { ?>
                                <option value="<?= htmlspecialchars($opt) ?>"><?= htmlspecialchars($opt) ?></option>
                            <?php } ?>
                        </select>
                    <?php } elseif ($type === 'date') { ?>
                        <input
                            type="text"
                            class="form-control form-control-sm kk-datepicker"
                            id="<?= $name ?>"
                            name="<?= $name ?>"
                            autocomplete="off"
                            placeholder="yyyy-mm-dd"
                            <?= $force_readonly ? 'readonly' : '' ?>
                            <?= (!$sec_readonly) ? 'required' : '' ?>
                            <?= $sec_readonly ? 'disabled' : '' ?>
                        >
                    <?php } elseif ($type === 'number' && $is_rupiah) { ?>
                        <input
                            type="text"
                            class="form-control form-control-sm kk-rupiah"
                            id="<?= $name ?>"
                            name="<?= $name ?>"
                            inputmode="numeric"
                            autocomplete="off"
                            placeholder="0"
                            <?= $force_readonly ? 'readonly' : '' ?>
                            <?= $sec_readonly ? 'disabled' : '' ?>
                        >
                    <?php } else { ?>
                        <input
                            type="<?= htmlspecialchars($type) ?>"
                            class="form-control form-control-sm<?= ($type === 'time') ? ' kk-time' : '' ?>"
                            id="<?= $name ?>"
                            name="<?= $name ?>"
                            <?= $is_periode ? 'readonly' : '' ?>
                            <?= (!$is_periode && $force_readonly) ? 'readonly' : '' ?>
                            <?= ($type === 'number' && $step) ? 'step="' . htmlspecialchars($step) . '"' : '' ?>
                            <?= (!$sec_readonly) ? 'required' : '' ?>
                            autocomplete="off"
                            <?= $is_periode ? 'placeholder="YYYYMM"' : '' ?>
                            <?= $sec_readonly ? 'disabled' : '' ?>
                        >
                    <?php } ?>
                </div>
            </div>
            <?php
        }
    }
}
?>

<style>
    .kk-section-title {
        font-weight: 600;
        margin-bottom: 0;
    }

    .kk-subbox {
        border: 1px solid rgba(0, 0, 0, .08);
        border-radius: .35rem;
        background: #fff;
    }

    .kk-subbox-title {
        font-weight: 600;
        font-size: 14px;
        margin: 0;
    }

    /* Compact sizing: kecilkan input & teks sedikit */
    .kk-card .form-group {
        margin-bottom: .65rem;
    }

    .kk-card label {
        font-size: 15px;
        margin-bottom: .25rem;
    }

    .kk-card .form-control {
        font-size: 15px;
    }

    /* Rapihkan Select2 agar benar-benar mirip Bootstrap form-control-sm */
    .kk-card .select2-container {
        width: 100% !important;
    }

    .kk-card .select2-container--default .select2-selection--single {
        height: 32.8px;
        border: 1px solid #D1D3E2;
        border-radius: .2rem;
        padding: 0 .5rem;
        display: block;
        box-sizing: border-box;
    }

    .kk-card .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 29px;
        padding-left: 0;
        padding-right: 1.5rem;
        font-size: 15px;
        color: inherit;
    }

    .kk-card .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 29px;
        top: 1px;
        right: .35rem;
    }

    .kk-card .select2-container--default.select2-container--disabled .select2-selection--single {
        background: #f8f9fc;
        cursor: not-allowed;
    }

    .kk-card .select2-container--default .select2-dropdown {
        border: 1px solid #D1D3E2;
        font-size: 15px;
    }

    .kk-card .select2-container--default .select2-results__option {
        font-size: 15px;
    }

    .kk-help {
        font-size: 12px;
        color: #6c757d;
        margin-top: 4px;
    }

    .kk-card {
        border: 1px solid rgba(0, 0, 0, .08);
        border-radius: .35rem;
    }

    .kk-readonly {
        background: #f8f9fc;
        border-color: rgba(0, 0, 0, .06);
    }
</style>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title_view ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header text-right">
                    <a class="btn btn-primary btn-sm" href="<?= base_url('sml_kertas_kerja') ?>"><i class="fas fa-chevron-left"></i>&nbsp;Back</a>
                </div>
                <div class="card-body">
                    <?php if (!$is_authorized) { ?>
                        <div class="alert alert-danger">
                            Username <b><?= htmlspecialchars($username ?: '-') ?></b> belum punya mapping role kertas kerja.
                            Silakan tambahkan username ke mapping role di form ini.
                        </div>
                    <?php } else { ?>
                        <?php
                        // Jika username punya semua access, tampilkan semuanya di alert-info.
                        $all_roles = array('marketing', 'plotting', 'monitoring', 'finance');
                        $roles_norm = array();
                        foreach ((array) $roles_for_user as $r) {
                            $r = strtolower(trim((string) $r));
                            if ($r !== '') {
                                $roles_norm[] = $r;
                            }
                        }
                        $roles_norm = array_values(array_unique($roles_norm));
                        $has_all_access = (count(array_intersect($all_roles, $roles_norm)) === count($all_roles));

                        if ($has_all_access) {
                            $access_display = 'Marketing, Plotting, Monitoring, Finance';
                        } else {
                            $access_display = ucfirst((string) $active_role);
                        }
                        ?>
                        <div class="alert alert-info">
                            Username: <b><?= htmlspecialchars($username) ?></b> | Access form: <b><?= htmlspecialchars($access_display) ?></b>
                        </div>
                    <?php } ?>

                    <?php if ($is_authorized && $is_create && !$is_marketing) { ?>
                        <div class="alert alert-warning">
                            Skema baru: hanya <b>Marketing</b> yang boleh <b>create</b> data.
                            Role <b><?= ucfirst(htmlspecialchars($active_role)) ?></b> hanya boleh <b>update</b> data yang sudah dibuat Marketing.
                            Silakan kembali ke list dan klik tombol Action pada baris yang ingin Anda lengkapi.
                        </div>
                    <?php } ?>

                    <?php if (!$is_create) { ?>
                    <div class="mb-3">
                        <?php
                        $btn_modes = array(
                            'marketing' => array('label' => 'Data Marketing', 'class' => 'btn-primary'),
                            'plotting' => array('label' => 'Plotting', 'class' => 'btn-warning'),
                            'monitoring' => array('label' => 'Monitoring', 'class' => 'btn-info'),
                            'finance' => array('label' => 'Finance', 'class' => 'btn-success')
                        );

                        // Tombol yang tampil hanya role yang user punya.
                        $visible_modes = $is_authorized ? $roles_for_user : array();
                        ?>

                        <div class="btn-group" role="group" aria-label="Mode Kertas Kerja">
                            <?php foreach ($visible_modes as $m) {
                                $meta = $btn_modes[$m];
                                $is_active = ($active_role === $m) ? 'active' : '';

                                // Saat create: hanya marketing yang boleh ke add_form.
                                // Saat edit: switch mode harus tetap ke edit_form/{id}.
                                if ($is_create) {
                                    if ($m !== 'marketing') {
                                        continue;
                                    }
                                    $href = base_url('sml_kertas_kerja/add_form?mode=' . $m);
                                } else {
                                    $href = base_url('sml_kertas_kerja/edit_form/' . $id . '?mode=' . $m);
                                }
                            ?>
                                <a class="btn btn-sm <?= $meta['class'] ?> <?= $is_active ?>" href="<?= $href ?>">
                                    <?php if ($is_create) { ?>
                                        <i class="fa fa-plus"></i>&nbsp;Create <?= $meta['label'] ?>
                                    <?php } else { ?>
                                        <i class="fa fa-edit"></i>&nbsp;Edit <?= $meta['label'] ?>
                                    <?php } ?>
                                </a>
                            <?php } ?>
                        </div>
                        <?php if ($is_authorized) { ?>
                            <div class="kk-help">Marketing membuat data baru; role lain melengkapi via update.</div>
                        <?php } ?>
                    </div>
                    <?php } ?>

                    <form id="form">
                        <div class="row">
                            <!-- <div class="col-md-6">
                                <div class="form-group">
                                    <label>No. Dokumen</label>
                                    <input type="text" class="form-control" id="no_dok" name="no_dok" placeholder="Auto / Manual sesuai kebutuhan" <?= ($active_role !== 'marketing') ? 'readonly' : '' ?>>
                                </div>
                            </div> -->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Role Input</label>
                                    <input type="text" class="form-control" id="input_role" name="input_role" value="<?= htmlspecialchars($active_role ?: '-') ?>" readonly>
                                    <!-- <div class="kk-help">Diset dari mode form. Dipakai untuk audit / filter list nanti (opsional).</div> -->
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="id" id="id" value="<?= $id ?>">
                        <?php if (!empty($aksi)) { ?>
                            <input type="hidden" name="aksi" id="aksi" value="<?= $aksi ?>">
                        <?php } ?>

                        <?php
                        $konsumen_options = isset($konsumen_options) ? (array) $konsumen_options : array();
                        $destinasi_options = isset($destinasi_options) ? (array) $destinasi_options : array();

                        // Konfigurasi field per role
                        $fields_marketing = array(
                            array('name' => 'periode', 'label' => 'Periode', 'type' => 'text'),
                            array('name' => 'tanggal', 'label' => 'Tanggal Order', 'type' => 'date'),
                            array('name' => 'konsumen', 'label' => 'Konsumen', 'type' => 'select', 'options' => $konsumen_options),
                            array('name' => 'project', 'label' => 'Project', 'type' => 'text'),
                            array('name' => 'origin', 'label' => 'Origin', 'type' => 'text'),
                            array('name' => 'destinasi', 'label' => 'Destinasi', 'type' => 'select', 'options' => $destinasi_options),
                            array('name' => 'wilayah', 'label' => 'Wilayah', 'type' => 'text'),
                            array('name' => 'tipe_trip', 'label' => 'Tipe Trip', 'type' => 'select', 'options' => array('One Way', 'Round Trip')),
                            array('name' => 'jenis_unit', 'label' => 'Jenis Unit', 'type' => 'select', 'options' => array('CDDL')),
                            array('name' => 'service', 'label' => 'Service', 'type' => 'text'),
                            array('name' => 'tipe_kiriman', 'label' => 'Tipe Kiriman', 'type' => 'text'),
                            array('name' => '', 'label' => '', 'type' => 'hidden', 'step' => '0.01'),
                            array('name' => 'selling', 'label' => 'Selling 1', 'type' => 'number', 'step' => '0.01'),
                            array('name' => 'multidrop_s', 'label' => 'Multidrop (S)', 'type' => 'number', 'step' => '0.01'),
                            array('name' => 'qty_selling', 'label' => 'QTY Selling', 'type' => 'number', 'step' => '1'),
                            array('name' => 'multidrop_selling', 'label' => 'Multidrop Selling', 'type' => 'number', 'step' => '0.01', 'readonly' => true),
                            array('name' => 'tkbm_selling', 'label' => 'TKBM Selling', 'type' => 'number', 'step' => '0.01'),
                            array('name' => 'inap_selling', 'label' => 'Inap Selling', 'type' => 'number', 'step' => '0.01'),
                            array('name' => 'additional_cost', 'label' => 'Additional Cost', 'type' => 'number', 'step' => '0.01'),
                            array('name' => 'keterangan_addcost', 'label' => 'Keterangan Addcost', 'type' => 'select', 'options' => array('Parkir', 'Tol', 'Parkir & Tol')),
                            array('name' => 'selling_2', 'label' => 'Selling 2', 'type' => 'number', 'step' => '0.01', 'readonly' => true),
                            array('name' => 'pelaksana', 'label' => 'Pelaksana', 'type' => 'select', 'options' => array('Vendor', 'Asset')),
                            array('name' => '', 'label' => '', 'type' => 'hidden', 'step' => '0.01'),
                            array('name' => '', 'label' => '', 'type' => 'hidden', 'step' => '0.01'),
                            array('name' => 'buying_uj', 'label' => 'Buying / UJ', 'type' => 'number', 'step' => '0.01'),
                            array('name' => 'multidrop_b', 'label' => 'Multidrop (B)', 'type' => 'number', 'step' => '0.01'),
                            array('name' => 'qty_buying', 'label' => 'QTY Buying', 'type' => 'number', 'step' => '1'),
                            array('name' => 'multidrop_buying', 'label' => 'Multidrop Buying', 'type' => 'number', 'step' => '0.01', 'readonly' => true),
                            array('name' => 'tkbm_buying', 'label' => 'TKBM Buying', 'type' => 'number', 'step' => '0.01'),
                            array('name' => 'inap_buying', 'label' => 'Inap Buying', 'type' => 'number', 'step' => '0.01'),
                            array('name' => 'additional_cost2', 'label' => 'Additional Cost', 'type' => 'number', 'step' => '0.01'),
                            array('name' => 'buying', 'label' => 'Buying', 'type' => 'number', 'step' => '0.01', 'readonly' => true),
                            array('name' => 'estimasi_margin', 'label' => 'Estimasi Margin', 'type' => 'number', 'step' => '0.01', 'readonly' => true),
                            array('name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => array('On Going', 'Done'))
                        );

                        $fields_plotting = array(
                            array('name' => 'id_asset', 'label' => 'ID Asset', 'type' => 'select', 'options' => array('Asset', 'Vendor')),
                            array('name' => 'vendor', 'label' => 'Vendor', 'type' => 'select', 'options' => array('-', 'Hantu Laut', 'RTL', 'Jiun')),
                            array('name' => 'driver', 'label' => 'Nama Driver 1', 'type' => 'text'),
                            array('name' => 'no_hp', 'label' => 'No HP Driver 1', 'type' => 'tel'),
                            array('name' => 'driver2', 'label' => 'Nama Driver 2', 'type' => 'text'),
                            array('name' => 'no_hp2', 'label' => 'No HP Driver 2', 'type' => 'tel'),
                            array('name' => 'tipe_unit', 'label' => 'Tipe Unit', 'type' => 'text'),
                            array('name' => 'tgl_stnk', 'label' => 'Tanggal STNK', 'type' => 'date'),
                            array('name' => 'tgl_keur', 'label' => 'Tanggal KEUR', 'type' => 'date'),
                            array('name' => 'tgl_berangkat', 'label' => 'Tanggal Berangkat', 'type' => 'date'),
                            array('name' => 'sla', 'label' => 'SLA', 'type' => 'number'),
                            array('name' => 'est_tgl_tujuan', 'label' => 'Estimasi Tanggal Tujuan', 'type' => 'date', 'readonly' => true),
                            array('name' => 'km_berangkat', 'label' => 'KM Berangkat', 'type' => 'number', 'step' => '1'),
                            array('name' => 'buying_actual', 'label' => 'Buying Actual', 'type' => 'number', 'step' => '0.01'),
                        );

                        $fields_monitoring = array(
                            array('name' => 'no_do', 'label' => 'No DO', 'type' => 'text'),
                            array('name' => 'tgl_muat_monitoring', 'label' => 'Tanggal Muat (Monitoring)', 'type' => 'date'),
                            array('name' => 'waktu_muat', 'label' => 'Waktu Muat', 'type' => 'time'),
                            array('name' => 'lokasi_bongkar', 'label' => 'Lokasi Bongkar', 'type' => 'text', 'readonly' => true),
                            array('name' => 'tgl_bongkar', 'label' => 'Tanggal Bongkar', 'type' => 'date'),
                            array('name' => 'waktu_bongkar', 'label' => 'Waktu Bongkar', 'type' => 'time'),
                            array('name' => 'aktual_jam', 'label' => 'Aktual Jam', 'type' => 'text', 'readonly' => true),
                            array('name' => 'tgl_kirim_sj', 'label' => 'Tanggal Kirim SJ', 'type' => 'date'),
                            array('name' => 'no_resi_kirim', 'label' => 'No Resi Kirim', 'type' => 'text'),
                            array('name' => 'tgl_masuk_pool', 'label' => 'Tanggal Masuk Pool', 'type' => 'date'),
                            array('name' => 'uang_balikan', 'label' => 'Uang Balikan', 'type' => 'number', 'step' => '0.01'),
                            array('name' => 'tgl_transfer', 'label' => 'Tanggal Transfer', 'type' => 'date'),
                        );

                        $fields_finance = array(
                            // Info dasar (auto dari Marketing/Plotting) - tampil di Finance agar tidak perlu scroll
                            array('name' => 'no_invoice', 'label' => 'No Invoice', 'type' => 'text'),
                            array('name' => 'tgl_invoice', 'label' => 'Tanggal Invoice', 'type' => 'date'),
                            array('name' => 'tgl_kirim_inv', 'label' => 'Tanggal Kirim Invoice', 'type' => 'date'),
                            array('name' => 'customer', 'label' => 'Customer', 'type' => 'text', 'readonly' => true),
                            array('name' => 'project_finance', 'label' => 'Project', 'type' => 'text', 'readonly' => true),
                            array('name' => 'no_do', 'label' => 'No DO', 'type' => 'text', 'readonly' => true),
                            array('name' => 'nopol_finance', 'label' => 'Nopol', 'type' => 'text', 'readonly' => true),
                            array('name' => 'tipe_unit_finance', 'label' => 'Tipe Unit', 'type' => 'text', 'readonly' => true),
                            array('name' => 'origin_finance', 'label' => 'Origin', 'type' => 'text', 'readonly' => true),
                            array('name' => 'destination_finance', 'label' => 'Destination', 'type' => 'text', 'readonly' => true),
                            array('name' => 'nominal', 'label' => 'Nominal', 'type' => 'number', 'step' => '0.01', 'readonly' => true),
                            array('name' => 'pph2', 'label' => 'PPH 2%', 'type' => 'number', 'step' => '0.01', 'readonly' => true),
                            array('name' => 'ppn_persen', 'label' => 'PPN (%)', 'type' => 'select', 'options' => array('1,1%', '11%')),
                            array('name' => 'ppn', 'label' => 'PPN', 'type' => 'number', 'step' => '0.01', 'readonly' => true),
                            array('name' => 'total_tagihan', 'label' => 'Total Tagihan', 'type' => 'number', 'step' => '0.01', 'readonly' => true),
                            array('name' => 'jatuh_tempo', 'label' => 'Jatuh Tempo', 'type' => 'date'),
                            array('name' => 'tgl_bayar', 'label' => 'Tanggal Bayar', 'type' => 'date'),
                            array('name' => 'nominal_pembayaran', 'label' => 'Nominal Pembayaran', 'type' => 'number', 'step' => '0.01'),
                            array('name' => 'status_pembayaran', 'label' => 'Status Pembayaran', 'type' => 'select', 'options' => array('Belum Bayar', 'Sebagian', 'Lunas', 'Kelebihan Bayaran')),
                            array('name' => 'selisih_pembayaran', 'label' => 'Selisih Pembayaran', 'type' => 'number', 'step' => '0.01', 'readonly' => true),

                            array('name' => 'actual_margin', 'label' => 'Actual Margin', 'type' => 'number', 'step' => '0.01', 'readonly' => true),
                            array('name' => 'actual_margin_persen', 'label' => '% Actual Margin', 'type' => 'text', 'readonly' => true),
                            array('name' => 'roi', 'label' => 'ROI', 'type' => 'text', 'readonly' => true),
                        );

                        // Field yang dianggap nominal rupiah (akan diformat 1.000.000 saat input)
                        $rupiah_fields = array(
                            // Marketing
                            'selling', 'multidrop_s', 'multidrop_selling', 'tkbm_selling', 'inap_selling', 'additional_cost', 'selling_2',
                            'buying_uj', 'multidrop_b', 'multidrop_buying', 'tkbm_buying', 'inap_buying', 'additional_cost2', 'buying', 'estimasi_margin',
                            // Plotting
                            'km_berangkat', 'buying_actual',
                            // Monitoring
                            'uang_jalan', 'uang_balikan',
                            // Finance
                            'nominal', 'pph2', 'ppn', 'total_tagihan', 'nominal_pembayaran', 'selisih_pembayaran', 'actual_margin',
                        );

                        $sections = array(
                            'marketing' => array('title' => 'Marketing', 'fields' => $fields_marketing, 'hint' => 'Input dari Marketing.'),
                            'plotting' => array('title' => 'Plotting', 'fields' => $fields_plotting, 'hint' => 'Input dari Plotting'),
                            'monitoring' => array('title' => 'Monitoring', 'fields' => $fields_monitoring, 'hint' => 'Input progress perjalanan/pengiriman.'),
                            'finance' => array('title' => 'Finance', 'fields' => $fields_finance, 'hint' => 'Input invoice & pembayaran.'),
                        );

                        // Tampilkan berjenjang: stage sebelumnya visible read-only, stage aktif editable, stage setelahnya disembunyikan
                        $role_order = array('marketing' => 1, 'plotting' => 2, 'monitoring' => 3, 'finance' => 4);
                        $active_rank = isset($role_order[$active_role]) ? $role_order[$active_role] : 0;

                        // Finance hanya boleh input jika status Marketing = Done
                        $marketing_status = '';
                        if (isset($data) && is_object($data) && isset($data->status)) {
                            $marketing_status = trim((string) $data->status);
                        }
                        $marketing_status_done = (strtolower($marketing_status) === 'done');
                        $finance_blocked_by_status = (!$is_create && $active_role === 'finance' && !$marketing_status_done);
                        if ($finance_blocked_by_status) {
                            $can_update = false;
                        }
                        ?>

                        <?php foreach ($sections as $sec_key => $sec) {
                            $sec_rank = isset($role_order[$sec_key]) ? $role_order[$sec_key] : 0;
                            if ($active_rank > 0 && $sec_rank > $active_rank) {
                                continue; // future sections hidden
                            }
                            $sec_readonly = ($active_rank > 0 && $sec_rank < $active_rank);
                            if ($sec_key === 'finance' && $finance_blocked_by_status) {
                                $sec_readonly = true;
                            }
                        ?>
                            <div class="kk-card p-3 mb-3 <?= $sec_readonly ? 'kk-readonly' : '' ?>" data-kk-section="<?= $sec_key ?>">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <h6 class="kk-section-title"><?= htmlspecialchars($sec['title']) ?></h6>
                                    <span class="badge badge-light text-uppercase"><?= htmlspecialchars($sec_key) ?></span>
                                </div>
                                <div class="kk-help mb-3">
                                    <?= htmlspecialchars($sec['hint']) ?>
                                    <?php if ($sec_readonly) { ?><span class="text-muted">(read-only)</span><?php } ?>
                                </div>

                                <?php if ($sec_key === 'marketing') {
                                    $marketing_pre = array();
                                    $marketing_selling = array();
                                    $marketing_buying = array();
                                    $bucket = 'pre';

                                    foreach ((array) $sec['fields'] as $f) {
                                        $fname = isset($f['name']) ? (string) $f['name'] : '';
                                        $ftype = isset($f['type']) ? (string) $f['type'] : '';

                                        // Skip placeholder fields
                                        if ($fname === '' && $ftype === 'hidden') {
                                            continue;
                                        }

                                        if ($fname === 'selling') {
                                            $bucket = 'selling';
                                        }
                                        if ($fname === 'buying_uj') {
                                            $bucket = 'buying';
                                        }

                                        if ($bucket === 'pre') {
                                            $marketing_pre[] = $f;
                                        } elseif ($bucket === 'selling') {
                                            $marketing_selling[] = $f;
                                            if ($fname === 'pelaksana') {
                                                $bucket = 'after_selling';
                                            }
                                        } elseif ($bucket === 'buying') {
                                            $marketing_buying[] = $f;
                                        } else {
                                            // after_selling (kalau ada field lain), letakkan kembali di pre
                                            $marketing_pre[] = $f;
                                        }
                                    }
                                ?>
                                    <?php if (!empty($marketing_pre)) { ?>
                                        <div class="row">
                                            <?php kk_render_fields_grid($marketing_pre, $sec_readonly, $rupiah_fields); ?>
                                        </div>
                                    <?php } ?>

                                    <?php if (!empty($marketing_selling)) { ?>
                                        <div class="kk-subbox p-3 mb-3">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <div class="kk-subbox-title">Selling</div>
                                                <span class="badge badge-light bg-success">SELLING</span>
                                            </div>
                                            <div class="row">
                                                <?php kk_render_fields_grid($marketing_selling, $sec_readonly, $rupiah_fields); ?>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <?php if (!empty($marketing_buying)) { ?>
                                        <div class="kk-subbox p-3">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <div class="kk-subbox-title">Buying</div>
                                                <span class="badge badge-light bg-warning">BUYING</span>
                                            </div>
                                            <div class="row">
                                                <?php kk_render_fields_grid($marketing_buying, $sec_readonly, $rupiah_fields); ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                <?php } elseif ($sec_key === 'plotting') { ?>
                                    <?php
                                    // Render plotting custom: nopol = select2 (Asset) / input manual (Vendor)
                                    $plot_pre = array();
                                    $plot_post = array();
                                    foreach ((array) $sec['fields'] as $f) {
                                        $fname = isset($f['name']) ? (string) $f['name'] : '';
                                        if ($fname === 'id_asset' || $fname === 'vendor') {
                                            $plot_pre[] = $f;
                                        } else {
                                            $plot_post[] = $f;
                                        }
                                    }

                                    // Tentukan disabled jika section readonly (stage sebelumnya)
                                    $plotting_disabled = $sec_readonly ? 'disabled' : '';
                                    ?>

                                    <div class="row">
                                        <?php kk_render_fields_grid($plot_pre, $sec_readonly, $rupiah_fields); ?>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="nopol_asset_select">Nopol</label>

                                                <div id="wrap_nopol_asset">
                                                    <select class="form-control form-control-sm" id="nopol_asset_select" <?= $plotting_disabled ?>></select>
                                                </div>

                                                <div id="wrap_nopol_vendor" style="display:none;">
                                                    <input type="text" class="form-control form-control-sm" id="nopol_vendor_input" autocomplete="off" placeholder="Input nopol" <?= $plotting_disabled ?>>
                                                </div>

                                                <input type="hidden" name="nopol" id="nopol">
                                            </div>
                                        </div>

                                        <?php kk_render_fields_grid($plot_post, $sec_readonly, $rupiah_fields); ?>
                                    </div>
                                <?php } else { ?>
                                    <div class="row">
                                        <?php kk_render_fields_grid($sec['fields'], $sec_readonly, $rupiah_fields); ?>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>

                        <button type="submit" class="btn btn-primary btn-sm aksi" <?= (!$is_authorized || ($is_create && !$can_create) || (!$is_create && !$can_update)) ? 'disabled' : '' ?>></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>

<script>
    $(document).ready(function() {
        var id = $('#id').val();
        var aksi = $('#aksi').val();

        var driverNopolLoaded = false;

        function normalizePeriode(val) {
            if (val === null || val === undefined) return '';
            var s = String(val).trim();
            if (!s) return '';
            // YYYY-MM-DD -> YYYYMM
            var m = s.match(/^(\d{4})-(\d{2})/);
            if (m) return m[1] + m[2];
            // YYYYMM -> keep
            m = s.match(/^(\d{6})$/);
            if (m) return m[1];
            // fallback: remove non-digits
            s = s.replace(/\D/g, '');
            return s.length >= 6 ? s.slice(0, 6) : s;
        }

        function currentPeriode() {
            var d = new Date();
            var y = String(d.getFullYear());
            var m = String(d.getMonth() + 1).padStart(2, '0');
            return y + m;
        }

        function stripRupiah(val) {
            if (val === null || val === undefined) return '';
            var s = String(val).trim();
            if (!s) return '';
            // Hapus pemisah ribuan, simpan angka + minus di depan (jika ada)
            s = s.replace(/\./g, '');
            s = s.replace(/[^0-9-]/g, '');
            // Minus hanya boleh di awal
            s = s.replace(/(?!^)-/g, '');
            return s;
        }

        function formatRupiah(val) {
            var clean = stripRupiah(val);
            if (clean === '') return '';

            var neg = false;
            if (clean.charAt(0) === '-') {
                neg = true;
                clean = clean.slice(1);
            }

            if (!clean) return '';
            // Remove leading zeros but keep single zero
            clean = clean.replace(/^0+(\d)/, '$1');
            var out = clean.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            return neg ? '-' + out : out;
        }

        function formatPercentDisplay(ratio, decimals) {    
            // ratio: 0.1234 -> "12,34%" (default)
            var n = Number(ratio);
            if (!isFinite(n)) n = 0;
            var pct = n * 100;
            var d = (decimals === undefined || decimals === null) ? 2 : Number(decimals);
            if (!isFinite(d)) d = 2;
            d = Math.max(0, Math.min(6, Math.round(d)));

            // Keep sign, show decimals (ID style comma)
            var s = pct.toFixed(d);
            if (d > 0) {
                s = s.replace('.', ',');
            }
            return s + '%';
        }

        function stripPercent(val) {
            if (val === null || val === undefined) return '';
            var s = String(val).trim();
            if (!s) return '';
            s = s.replace(/%/g, '');
            s = s.replace(/\s+/g, '');
            // allow comma decimal, normalize to dot
            s = s.replace(/,/g, '.');
            // keep only digits, dot, minus
            s = s.replace(/[^0-9\.-]/g, '');
            // minus only at beginning
            s = s.replace(/(?!^)-/g, '');
            return s;
        }

        function formatDateIndonesia(val) {
            if (val === null || val === undefined) return '';
            var s = String(val).trim();
            if (!s) return '';
            if (s === '0000-00-00' || s === '0000-00-00 00:00:00') return '';

            // Support: YYYY-MM-DD or YYYY-MM-DD HH:MM:SS
            var m = s.match(/^(\d{4})-(\d{2})-(\d{2})/);
            if (!m) return s;

            var year = m[1];
            var month = parseInt(m[2], 10);
            var day = parseInt(m[3], 10);

            var months = {
                1: 'Januari',
                2: 'Februari',
                3: 'Maret',
                4: 'April',
                5: 'Mei',
                6: 'Juni',
                7: 'Juli',
                8: 'Agustus',
                9: 'September',
                10: 'Oktober',
                11: 'November',
                12: 'Desember'
            };

            var monthName = months[month] || m[2];
            return String(day) + ' ' + monthName + ' ' + year;
        }

        // Semua input tanggal pakai jQuery UI datepicker
        if ($.fn.datepicker) {
            $('.kk-datepicker').datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true
            });
        }

        function plottingIsLocked() {
            // Jangan unlock field jika sedang read mode atau section plotting read-only.
            if (aksi == 'read') return true;
            var $sec = $('[data-kk-section="plotting"]');
            return $sec.length && $sec.hasClass('kk-readonly');
        }

        function initSelect2IfAvailable() {
            if ($.fn.select2 && $('#nopol_asset_select').length) {
                try {
                    $('#nopol_asset_select').select2({
                        width: '100%',
                        placeholder: '- pilih -'
                    });
                } catch (e) {
                    // ignore
                }
            }
        }

        function loadDriverNopolOptions(selectedNopol) {
            if (!$('#nopol_asset_select').length) return;
            if (driverNopolLoaded) {
                if (selectedNopol) {
                    $('#nopol_asset_select').val(selectedNopol).trigger('change');
                }
                return;
            }

            $.ajax({
                url: "<?php echo site_url('sml_kertas_kerja/driver_nopol_list') ?>",
                type: 'GET',
                dataType: 'JSON',
                success: function(resp) {
                    var results = (resp && resp.results) ? resp.results : [];
                    var $sel = $('#nopol_asset_select');
                    $sel.empty();
                    $sel.append('<option value="">- pilih -</option>');
                    results.forEach(function(r) {
                        if (!r || !r.id) return;
                        $sel.append(new Option(r.text || r.id, r.id, false, false));
                    });
                    driverNopolLoaded = true;
                    if (selectedNopol) {
                        $sel.val(selectedNopol).trigger('change');
                    }
                },
                error: function() {
                    // ignore
                }
            });
        }

        function setPlottingReadonly(on) {
            var names = ['driver', 'no_hp', 'driver2', 'no_hp2', 'tipe_unit', 'tgl_stnk', 'tgl_keur'];
            names.forEach(function(n) {
                var $el = $('[name="' + n + '"]');
                if (!$el.length) return;
                $el.prop('readonly', !!on);
            });
        }

        function fetchDriverByNopol(nopol) {
            if (!nopol) return;
            $.ajax({
                url: "<?php echo site_url('sml_kertas_kerja/driver_detail_by_nopol') ?>",
                type: 'GET',
                dataType: 'JSON',
                data: { nopol: nopol },
                success: function(d) {
                    if (!d || typeof d !== 'object') return;

                    if ($('[name="driver"]').length) $('[name="driver"]').val(d.nama_driver || '');
                    if ($('[name="no_hp"]').length) $('[name="no_hp"]').val(d.no_hp || '');
                    if ($('[name="driver2"]').length) $('[name="driver2"]').val(d.driver2 || '');
                    if ($('[name="no_hp2"]').length) $('[name="no_hp2"]').val(d.no_hp2 || '');
                    if ($('[name="tipe_unit"]').length) $('[name="tipe_unit"]').val(d.tipe_unit || '');
                    if ($('[name="tgl_stnk"]').length) $('[name="tgl_stnk"]').val(d.tgl_stnk || '');
                    if ($('[name="tgl_keur"]').length) $('[name="tgl_keur"]').val(d.tgl_keur || '');

                    // Sync finance preview fields
                    recalcFinanceDerived();
                },
                error: function() {
                    // ignore
                }
            });
        }

        function loadBuyingActualFromDestinasi(force) {
            // Plotting: buying_actual otomatis mengikuti harga pada master sml_destinasi sesuai Marketing destinasi.
            // Default: hanya isi jika buying_actual kosong/0, kecuali mode Asset (force).
            var dest = $('[name="destinasi"]').length ? $.trim(String($('[name="destinasi"]').val() || '')) : '';
            if (!dest) return;
            if (!$('[name="buying_actual"]').length) return;

            var mode = $.trim(String($('[name="id_asset"]').val() || ''));
            var isAsset = (mode === 'Asset');

            var raw = $.trim(String($('[name="buying_actual"]').val() || ''));
            var current = getNumeric('buying_actual');
            var shouldSet = !!force || isAsset || (!raw || current === 0);
            if (!shouldSet) {
                return;
            }

            $.ajax({
                url: "<?php echo site_url('sml_kertas_kerja/destinasi_harga_by_destinasi') ?>",
                type: 'GET',
                dataType: 'JSON',
                data: { destinasi: dest },
                success: function(resp) {
                    var harga = 0;
                    if (resp && typeof resp === 'object' && resp.harga !== undefined && resp.harga !== null) {
                        harga = Number(resp.harga) || 0;
                    }
                    setRupiahField('buying_actual', harga);
                    recalcFinanceDerived();
                },
                error: function() {
                    // ignore
                }
            });
        }

        function applyPlottingModeFromSelection() {
            if (!$('#nopol_asset_select').length) return;

            var mode = String($('[name="id_asset"]').val() || '').trim();
            var isAsset = (mode === 'Asset');
            var locked = plottingIsLocked();

            // buying_actual hanya bisa diinput jika mode Vendor
            // Jika mode Asset atau form terkunci (read/readonly section), jadikan readonly.
            var $buyingActual = $('[name="buying_actual"]');
            if ($buyingActual.length) {
                $buyingActual.prop('readonly', isAsset || locked);
            }

            // Toggle nopol controls
            $('#wrap_nopol_asset').toggle(isAsset);
            $('#wrap_nopol_vendor').toggle(!isAsset);

            var $vendor = $('[name="vendor"]');
            if (isAsset) {
                if ($vendor.length) {
                    $vendor.val('-');
                    if (!locked) {
                        // Jangan disable (nanti tidak ikut submit). Kunci interaksi agar terasa readonly.
                        $vendor.css('pointer-events', 'none');
                        $vendor.attr('tabindex', '-1');
                    }
                }

                setPlottingReadonly(true);
                loadDriverNopolOptions($('[name="nopol"]').val());

                var np = String($('#nopol_asset_select').val() || '').trim();
                $('[name="nopol"]').val(np);
                if (np) {
                    fetchDriverByNopol(np);
                }
            } else {
                if ($vendor.length && !locked) {
                    $vendor.css('pointer-events', '');
                    $vendor.removeAttr('tabindex');
                    if (String($vendor.val() || '') === '-') {
                        $vendor.val('');
                    }
                }

                setPlottingReadonly(false);
                var npv = String($('#nopol_vendor_input').val() || '').trim();
                $('[name="nopol"]').val(npv);
            }
        }

        function initPlottingUI() {
            if (!$('#nopol_asset_select').length) return;

            initSelect2IfAvailable();

            // Sync from hidden nopol value loaded via edit_data
            var np = String($('[name="nopol"]').val() || '').trim();
            $('#nopol_vendor_input').val(np);

            // Set default if kosong
            var mode = String($('[name="id_asset"]').val() || '').trim();
            if (!mode) {
                $('[name="id_asset"]').val('Asset');
            }

            // Apply initial mode
            applyPlottingModeFromSelection();
        }

        // UX: klik di mana saja pada input time akan membuka time picker (tidak harus klik ikon jam)
        // Catatan: showPicker didukung di Chromium modern; fallback tetap fokus saja.
        $(document).on('click focus', 'input.kk-time', function() {
            try {
                if (this && typeof this.showPicker === 'function' && !this.disabled && !this.readOnly) {
                    this.showPicker();
                }
            } catch (e) {
                // ignore
            }
        });

        if (id == 0) {
            $('.aksi').text('Save');

            // Default periode saat create
            if ($('[name="periode"]').length && !$.trim($('[name="periode"]').val())) {
                $('[name="periode"]').val(currentPeriode());
            }

            // Default status saat create
            if ($('[name="status"]').length && !$.trim(String($('[name="status"]').val() || ''))) {
                $('[name="status"]').val('On Going');
            }
        } else {
            $('.aksi').text('Update');
            $.ajax({
                url: "<?php echo site_url('sml_kertas_kerja/edit_data') ?>/" + id + "?mode=<?= urlencode($active_role) ?>",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    // isi form secara generik: key dari response harus sama dengan name input
                    if (data && typeof data === 'object') {
                        Object.keys(data).forEach(function(key) {
                            var $el = $('[name="' + key + '"]');
                            if (!$el.length) return;

                            if ($el.is('select')) {
                                $el.val(data[key]);
                            } else if ($el.attr('type') === 'checkbox') {
                                $el.prop('checked', !!data[key]);
                            } else if ($el.attr('type') === 'time') {
                                // DB sering menyimpan TIME sebagai HH:MM:SS, sementara input type=time butuh HH:MM
                                var t = (data[key] === null || data[key] === undefined) ? '' : String(data[key]).trim();
                                var mTime = t.match(/^([01]?\d|2[0-3]):([0-5]\d)(?::([0-5]\d))?/);
                                if (mTime) {
                                    $el.val(mTime[1].padStart(2, '0') + ':' + mTime[2]);
                                } else {
                                    $el.val(t);
                                }
                            } else {
                                $el.val(data[key]);
                            }
                        });
                    }

                    // Init Plotting UI setelah hidden nopol & id_asset terisi
                    initPlottingUI();

                    // Format semua nominal rupiah setelah load
                    $('.kk-rupiah').each(function() {
                        var v = $(this).val();
                        $(this).val(formatRupiah(v));
                    });

                    // Normalisasi periode setelah load
                    if ($('[name="periode"]').length) {
                        $('[name="periode"]').val(normalizePeriode($('[name="periode"]').val()));
                    }

                    // Kalkulasi ulang derived fields setelah load + formatting
                    recalcMarketingDerived();

                    // Plotting: est_tgl_tujuan = tgl_berangkat + sla
                    recalcPlottingDerived();

                    // Autofill finance fields (customer/nominal/pph + info dasar) setelah load
                    recalcFinanceDerived();

                    // Plotting: default buying_actual dari master destinasi
                    loadBuyingActualFromDestinasi(false);

                    // Monitoring: lokasi bongkar ikut dari destinasi (jika masih kosong)
                    syncLokasiBongkarFromDestinasi(false);

                    // Kalkulasi aktual jam setelah load
                    recalcAktualJam();

                    // Mode read: tampilkan tanggal dengan bulan Indonesia
                    if (aksi == "read") {
                        $('.kk-datepicker').each(function() {
                            var v = $(this).val();
                            $(this).val(formatDateIndonesia(v));
                        });
                    }

                    // no_dok biasanya readonly saat edit
                    $('#no_dok').attr('readonly', true);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        // Plotting: toggle mode Asset/Vendor
        $(document).on('change', '[name="id_asset"]', function() {
            applyPlottingModeFromSelection();
            loadBuyingActualFromDestinasi(false);
        });

        // Marketing: saat destinasi berubah, sync lokasi bongkar dan update buying_actual
        $(document).on('change', '[name="destinasi"]', function() {
            syncLokasiBongkarFromDestinasi(false);
            loadBuyingActualFromDestinasi(true);
        });

        // Plotting (Asset): nopol select2 changed
        $(document).on('change', '#nopol_asset_select', function() {
            var np = String($(this).val() || '').trim();
            $('[name="nopol"]').val(np);
            if (np) {
                fetchDriverByNopol(np);
            } else {
                // Clear autofill if no selection
                if (!plottingIsLocked()) {
                    $('[name="driver"]').val('');
                    $('[name="no_hp"]').val('');
                    $('[name="driver2"]').val('');
                    $('[name="no_hp2"]').val('');
                    $('[name="tipe_unit"]').val('');
                    $('[name="tgl_stnk"]').val('');
                    $('[name="tgl_keur"]').val('');
                }
            }
        });

        // Plotting (Vendor): manual nopol input
        $(document).on('input', '#nopol_vendor_input', function() {
            var np = String($(this).val() || '').trim();
            $('[name="nopol"]').val(np);
            recalcFinanceDerived();
        });

        // Init plotting UI for non-edit (mis. sudah terisi via default)
        initPlottingUI();

        // Jika tanggal berubah, periode ikut update (YYYYMM)
        $(document).on('change', '[name="tanggal"]', function() {
            if (!$('[name="periode"]').length) return;
            var p = normalizePeriode($(this).val());
            if (p) {
                $('[name="periode"]').val(p);
            }
        });

        // Monitoring: lokasi_bongkar mengikuti destinasi (isi jika masih kosong)
        $(document).on('input change', '[name="destinasi"]', function() {
            syncLokasiBongkarFromDestinasi(false);
        });

        // Format nominal rupiah saat mengetik
        $(document).on('input', '.kk-rupiah', function() {
            var v = $(this).val();
            $(this).val(formatRupiah(v));
        });

        function getNumeric(name) {
            var $el = $('[name="' + name + '"]');
            if (!$el.length) return 0;

            var v = ($el.val() === null || $el.val() === undefined) ? '' : String($el.val()).trim();
            if (!v) return 0;

            if ($el.hasClass('kk-rupiah')) {
                var s = stripRupiah(v);
                if (!s) return 0;
                var n = parseFloat(s);
                return isNaN(n) ? 0 : n;
            }

            v = v.replace(',', '.');
            var n2 = parseFloat(v);
            return isNaN(n2) ? 0 : n2;
        }

        function setRupiahField(name, numberVal) {
            var $el = $('[name="' + name + '"]');
            if (!$el.length) return;

            // Untuk rupiah: dibulatkan ke integer agar format ribuan konsisten
            if ($el.hasClass('kk-rupiah')) {
                $el.val(formatRupiah(String(Math.round(numberVal))));
                return;
            }

            $el.val(numberVal);
        }

        function parseTimeToMinutes(hhmm) {
            if (hhmm === null || hhmm === undefined) return null;
            var s = String(hhmm).trim();
            if (!s) return null;
            // Terima HH:MM maupun HH:MM:SS (seconds diabaikan)
            var m = s.match(/^([01]?\d|2[0-3]):([0-5]\d)(?::([0-5]\d))?$/);
            if (!m) return null;
            var h = parseInt(m[1], 10);
            var mi = parseInt(m[2], 10);
            return (h * 60) + mi;
        }

        function formatMinutesToHHMM(totalMinutes) {
            if (totalMinutes === null || totalMinutes === undefined) return '';
            var mins = parseInt(totalMinutes, 10);
            if (isNaN(mins)) return '';
            if (mins < 0) mins = 0;
            var h = Math.floor(mins / 60);
            var m = mins % 60;
            return String(h).padStart(2, '0') + ':' + String(m).padStart(2, '0');
        }

        function parseDateToDays(ymd) {
            if (ymd === null || ymd === undefined) return null;
            var s = String(ymd).trim();
            if (!s) return null;
            var m = s.match(/^(\d{4})-(\d{2})-(\d{2})$/);
            if (!m) return null;
            var y = parseInt(m[1], 10);
            var mo = parseInt(m[2], 10) - 1;
            var d = parseInt(m[3], 10);
            if (isNaN(y) || isNaN(mo) || isNaN(d)) return null;
            return Math.floor(Date.UTC(y, mo, d) / 86400000);
        }

        function daysToDateString(dayCount) {
            if (dayCount === null || dayCount === undefined) return '';
            var n = parseInt(dayCount, 10);
            if (isNaN(n)) return '';
            var dt = new Date(n * 86400000);
            var y = String(dt.getUTCFullYear());
            var m = String(dt.getUTCMonth() + 1).padStart(2, '0');
            var d = String(dt.getUTCDate()).padStart(2, '0');
            return y + '-' + m + '-' + d;
        }

        function parseSlaDays(val) {
            if (val === null || val === undefined) return null;
            var s = String(val).trim();
            if (!s) return null;

            // Terima "5", "5 hari", dll (ambil angka pertama)
            var m = s.match(/(\d+)/);
            if (!m) return null;
            var n = parseInt(m[1], 10);
            if (isNaN(n)) return null;
            if (n < 0) n = 0;
            return n;
        }

        function recalcPlottingDerived() {
            var $out = $('[name="est_tgl_tujuan"]');
            if (!$out.length) return;

            var startDate = $('[name="tgl_berangkat"]').val();
            var slaDays = parseSlaDays($('[name="sla"]').val());
            var startDay = parseDateToDays(startDate);

            if (startDay === null || slaDays === null) {
                $out.val('');
                return;
            }

            var endDay = startDay + slaDays;
            $out.val(daysToDateString(endDay));
        }

        function recalcAktualJam(trigger) {
            var $out = $('[name="aktual_jam"]');
            if (!$out.length) return;

            var startDate = $('[name="tgl_muat_monitoring"]').val();
            var endDate   = $('[name="tgl_bongkar"]').val();

            var startMin = parseTimeToMinutes($('[name="waktu_muat"]').val());
            var endMin   = parseTimeToMinutes($('[name="waktu_bongkar"]').val());

            var startDay = parseDateToDays(startDate);
            var endDay   = parseDateToDays(endDate);

            if (startDay === null || endDay === null || startMin === null || endMin === null) {
                $out.val('');
                return;
            }

            // 🚨 Tanggal sama & jam bongkar lebih kecil
            if (startDay === endDay && endMin < startMin) {
                $out.val('');

                if (trigger === 'bongkar') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Waktu tidak valid',
                        text: 'Jika tanggal muat dan bongkar sama, waktu bongkar tidak boleh lebih kecil dari waktu muat.',
                        confirmButtonText: 'OK'
                    });
                }
                return;
            }

            var diffDays = endDay - startDay;

            // 🚨 Tanggal bongkar lebih kecil dari tanggal muat
            if (diffDays < 0) {
                $out.val('');

                if (trigger === 'bongkar') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Tanggal tidak valid',
                        text: 'Tanggal bongkar tidak boleh lebih kecil dari tanggal muat.',
                        confirmButtonText: 'OK'
                    });
                }
                return;
            }

            var diff = (diffDays * 24 * 60) + (endMin - startMin);

            if (diff < 0) {
                $out.val('');
                return;
            }

            $out.val(formatMinutesToHHMM(diff));
        }

        function recalcMarketingDerived() {
            // Logic 1: multidrop_selling = multidrop_s * qty_selling
            // Logic 2: selling_2 = selling + tkbm_selling + multidrop_selling + inap_selling + additional_cost
            // Logic 3: multidrop_buying = multidrop_b * qty_buying
            // Logic 4: buying = buying_uj + multidrop_buying + inap_buying + additional_cost2
            // Logic 5: estimasi_margin = selling_2 - buying
            if (!$('[name="multidrop_s"]').length || !$('[name="qty_selling"]').length) return;

            var multidropS = getNumeric('multidrop_s');
            var qtySelling = getNumeric('qty_selling');
            var selling1 = getNumeric('selling');
            var tkbmSelling = getNumeric('tkbm_selling');
            var inapSelling = getNumeric('inap_selling');
            var additionalCost = getNumeric('additional_cost');
            var additionalCost2 = getNumeric('additional_cost2');

            var multidropSelling = multidropS * qtySelling;
            var selling2 = selling1 + multidropSelling + tkbmSelling + inapSelling + additionalCost;

            setRupiahField('multidrop_selling', multidropSelling);
            setRupiahField('selling_2', selling2);

            // Buying side
            var multidropB = getNumeric('multidrop_b');
            var qtyBuying = getNumeric('qty_buying');
            var buyingUj = getNumeric('buying_uj');
            var tkbmBuying = getNumeric('tkbm_buying');
            var inapBuying = getNumeric('inap_buying');

            var multidropBuying = multidropB * qtyBuying;
            var buying = buyingUj + multidropBuying + tkbmBuying + inapBuying + additionalCost2;
            var estimasiMargin = selling2 - buying;

            setRupiahField('multidrop_buying', multidropBuying);
            setRupiahField('buying', buying);
            setRupiahField('estimasi_margin', estimasiMargin);
        }

        function recalcFinanceDerived() {
            // Rule:
            // customer = konsumen (marketing)
            // project_finance = project (marketing)
            // no_do = no_do (monitoring)
            // nopol/tipe_unit = dari plotting
            // origin = marketing
            // destination = destinasi (marketing)
            // nominal = selling_2 (marketing)
            // pph2 = nominal * 2%
            // ppn = nominal * (ppn_persen)

            // Isi field tampilan di section Finance
            var konsumen = $('[name="konsumen"]').length ? String($('[name="konsumen"]').val() || '') : '';
            var project = $('[name="project"]').length ? String($('[name="project"]').val() || '') : '';
            var nopol = $('[name="nopol"]').length ? String($('[name="nopol"]').val() || '') : '';
            var tipeUnit = $('[name="tipe_unit"]').length ? String($('[name="tipe_unit"]').val() || '') : '';
            var origin = $('[name="origin"]').length ? String($('[name="origin"]').val() || '') : '';
            var destinasi = $('[name="destinasi"]').length ? String($('[name="destinasi"]').val() || '') : '';

            if ($('[name="nopol_finance"]').length) $('[name="nopol_finance"]').val(nopol);
            if ($('[name="tipe_unit_finance"]').length) $('[name="tipe_unit_finance"]').val(tipeUnit);
            if ($('[name="origin_finance"]').length) $('[name="origin_finance"]').val(origin);
            if ($('[name="destination_finance"]').length) $('[name="destination_finance"]').val(destinasi);

            // Autofill kolom finance yang tersimpan di DB (hanya jika kosong)
            if ($('[name="customer"]').length && !$.trim(String($('[name="customer"]').val() || ''))) {
                $('[name="customer"]').val(konsumen);
            }

            if ($('[name="project_finance"]').length && !$.trim(String($('[name="project_finance"]').val() || ''))) {
                $('[name="project_finance"]').val(project);
            }

            // No DO ambil dari section Monitoring
            var monitoringNoDo = $('[data-kk-section="monitoring"] [name="no_do"]').length ? String($('[data-kk-section="monitoring"] [name="no_do"]').val() || '') : '';
            var $noDoFin = $('[data-kk-section="finance"] [name="no_do"]');
            if ($noDoFin.length && !$.trim(String($noDoFin.val() || '')) && $.trim(monitoringNoDo)) {
                $noDoFin.val(monitoringNoDo);
            }

            // nominal mengikuti selling_2 (isi jika kosong/0)
            if ($('[name="nominal"]').length) {
                var nominalCurrentRaw = $.trim(String($('[name="nominal"]').val() || ''));
                var nominalCurrent = getNumeric('nominal');
                if (nominalCurrentRaw === '' || nominalCurrent === 0) {
                    var selling2 = getNumeric('selling_2');
                    setRupiahField('nominal', selling2);
                }
            }

            // Hitung pph berdasarkan nominal (selalu overwrite agar sesuai rumus)
            var nominal = getNumeric('nominal');
            setRupiahField('pph2', nominal * 0.02);

            // Default ppn persen
            var $ppnPersen = $('[name="ppn_persen"]');
            if ($ppnPersen.length) {
                var cur = $.trim(String($ppnPersen.val() || ''));
                if (!cur) {
                    // coba infer dari ppn existing
                    var existingPpn = getNumeric('ppn');
                    var inferred = '';
                    if (nominal > 0 && existingPpn > 0) {
                        var ratio = existingPpn / nominal;
                        inferred = (Math.abs(ratio - 0.011) < Math.abs(ratio - 0.11)) ? '1,1%' : '11%';
                    }
                    $ppnPersen.val(inferred || '11%');
                }
            }

            updatePpnLabel();

            var ppnRate = 0.11;
            var ppnSel = $ppnPersen.length ? $.trim(String($ppnPersen.val() || '')) : '';
            if (ppnSel === '1,1%') {
                ppnRate = 0.011;
            }
            setRupiahField('ppn', nominal * ppnRate);

            // Total tagihan = nominal + pph2 + ppn
            var pph2 = getNumeric('pph2');
            var ppn = getNumeric('ppn');
            setRupiahField('total_tagihan', nominal + pph2 + ppn);

            // Actual Margin / % / ROI
            // actual_margin = selling_2 - multidrop_buying - tkbm_buying - inap_buying - additional_cost2 - buying_actual
            // % actual_margin = actual_margin / selling_2
            // ROI = actual_margin / (multidrop_buying + tkbm_buying + inap_buying + additional_cost2 + buying_actual)
            var selling2ForMargin = getNumeric('selling_2');
            var multidropBuying = getNumeric('multidrop_buying');
            var tkbmBuying = getNumeric('tkbm_buying');
            var inapBuying = getNumeric('inap_buying');
            var addCost2 = getNumeric('additional_cost2');
            var buyingActual = getNumeric('buying_actual');

            var actualMargin = selling2ForMargin - multidropBuying - tkbmBuying - inapBuying - addCost2 - buyingActual;
            setRupiahField('actual_margin', actualMargin);

            var actualMarginPersen = 0;
            if (selling2ForMargin !== 0) {
                actualMarginPersen = actualMargin / selling2ForMargin;
            }
            if ($('[name="actual_margin_persen"]').length) {
                $('[name="actual_margin_persen"]').val(formatPercentDisplay(actualMarginPersen));
            }

            var denomRoi = (multidropBuying + tkbmBuying + inapBuying + addCost2 + buyingActual);
            var roi = 0;
            if (denomRoi !== 0) {
                roi = actualMargin / denomRoi;
            }
            if ($('[name="roi"]').length) {
                // ROI dibulatkan ke persen terdekat (contoh 209,80% -> 210%)
                $('[name="roi"]').val(formatPercentDisplay(roi, 0));
            }

            // Update status pembayaran & selisih setiap kali total berubah
            recalcFinancePaymentDerived();
        }

        function syncLokasiBongkarFromDestinasi(force) {
            // Monitoring: lokasi_bongkar mengambil data dari Marketing: destinasi.
            // Default: hanya isi jika lokasi_bongkar masih kosong (tidak override input manual).
            var $dest = $('[name="destinasi"]');
            var $lok = $('[name="lokasi_bongkar"]');
            if (!$dest.length || !$lok.length) return;  

            var d = $.trim(String($dest.val() || ''));
            if (!d) return;

            var cur = $.trim(String($lok.val() || ''));
            if (force || !cur) {
                $lok.val(d);
            }
        }

        function recalcFinancePaymentDerived() {
            // Rule:
            // - total_tagihan = nominal + pph2 + ppn
            // - nominal_pembayaran = input manual
            // - status_pembayaran:
            //   * jika nominal_pembayaran kosong -> Belum Bayar
            //   * jika total_tagihan == nominal_pembayaran -> Lunas
            //   * jika nominal_pembayaran < total_tagihan -> Sebagian
            //   * jika nominal_pembayaran > total_tagihan -> Lunas (lebih bayar)
            // - selisih_pembayaran = total_tagihan - nominal_pembayaran (bisa minus jika lebih bayar)

            var total = getNumeric('total_tagihan');
            var $bayarEl = $('[name="nominal_pembayaran"]');
            var bayarRaw = $bayarEl.length ? $.trim(String($bayarEl.val() || '')) : '';
            var bayar = getNumeric('nominal_pembayaran');

            var status = '';
            if (!bayarRaw || bayar === 0) {
                status = 'Belum Bayar';
            } else if (bayar == total && total > 0) {
                status = 'Lunas';
            } else if (total > 0 && bayar > 0 && bayar < total) {
                status = 'Sebagian';
            } else if (bayar > total && total > 0) {
                status = 'Kelebihan Bayaran';
            } else {
                status = 'Belum Bayar';
            }

            if ($('[name="status_pembayaran"]').length) {
                $('[name="status_pembayaran"]').val(status);
            }

            var selisih = total - bayar;
            setRupiahField('selisih_pembayaran', selisih);
        }

        // MUAT → hitung saja, tanpa swal
        $(document).on(
            'input change',
            '[name="tgl_muat_monitoring"], [name="waktu_muat"]',
            function () {
                recalcAktualJam('muat');
            }
        );

        // BONGKAR → hitung + swal jika invalid
        $(document).on(
            'input change',
            '[name="tgl_bongkar"], [name="waktu_bongkar"]',
            function () {
                recalcAktualJam('bongkar');
            }
        );


        // Trigger auto-calc est_tgl_tujuan saat tgl_berangkat / sla berubah
        $(document).on('input change', '[name="tgl_berangkat"], [name="sla"]', function() {
            recalcPlottingDerived();
        });

        // Trigger auto-calc saat field terkait berubah
        $(document).on('input change', '[name="multidrop_s"], [name="qty_selling"], [name="selling"], [name="tkbm_selling"], [name="inap_selling"], [name="additional_cost"], [name="multidrop_b"], [name="qty_buying"], [name="buying_uj"], [name="tkbm_buying"], [name="inap_buying"], [name="additional_cost2"]', function() {
            recalcMarketingDerived();
        });

        // Finance: jika nominal berubah (misal nanti dibuat editable), PPH ikut update
        $(document).on('input change', '[name="nominal"]', function() {
            recalcFinanceDerived();
        });

        // Finance: jika PPN persen berubah, nominal PPN & total ikut update
        $(document).on('change', '[name="ppn_persen"]', function() {
            recalcFinanceDerived();
        });

        // Finance: margin/ROI ikut update jika komponen berubah
        $(document).on('input change', '[name="selling_2"], [name="multidrop_b"], [name="qty_buying"], [name="multidrop_buying"], [name="tkbm_buying"], [name="inap_buying"], [name="additional_cost2"], [name="buying_actual"]', function() {
            recalcFinanceDerived();
        });

        function updatePpnLabel() {
            // Label input `ppn` mengikuti persen yang dipilih.
            // Contoh: 1,1% -> "PPN 1,1%".
            var $secFin = $('[data-kk-section="finance"]');
            var $lbl = $secFin.length ? $secFin.find('label[for="ppn"]') : $('label[for="ppn"]');
            if (!$lbl.length) return;

            var p = $.trim(String($('[name="ppn_persen"]').val() || ''));
            if (p) {
                $lbl.text('PPN ' + p);
            } else {
                $lbl.text('PPN');
            }
        }

        // Finance: status pembayaran & selisih mengikuti input pembayaran
        $(document).on('input change', '[name="nominal_pembayaran"]', function() {
            recalcFinancePaymentDerived();
        });

        if (aksi == "read") {
            $('.aksi').hide();
            // lock semua input saat read
            $('#form').find('input, select, textarea, button').prop('disabled', true);
            $('#form').find('button.aksi').prop('disabled', true);
        }


        $("#form").submit(function(e) {
            e.preventDefault();
            var url;
            if (id == 0) {
                url = "<?php echo site_url('sml_kertas_kerja/add') ?>";
            } else {
                url = "<?php echo site_url('sml_kertas_kerja/update') ?>";
            }

            // Pastikan periode format YYYYMM sebelum submit
            if ($('[name="periode"]').length) {
                $('[name="periode"]').val(normalizePeriode($('[name="periode"]').val()) || currentPeriode());
            }

            // Pastikan derived field terupdate sebelum submit
            recalcMarketingDerived();

            // Pastikan plotting derived field terupdate sebelum submit
            recalcPlottingDerived();

            // Pastikan finance derived field terupdate sebelum submit
            recalcFinanceDerived();

            // Pastikan status & selisih pembayaran terupdate sebelum submit
            recalcFinancePaymentDerived();

            // Pastikan aktual jam terupdate sebelum submit
            recalcAktualJam();

            // Sebelum submit: strip titik pada semua nominal rupiah
            $('.kk-rupiah').each(function() {
                var raw = stripRupiah($(this).val());
                $(this).val(raw);
            });

            // Sebelum submit: strip tanda % pada field persentase
            $('[name="actual_margin_persen"], [name="roi"]').each(function() {
                var raw = stripPercent($(this).val());
                $(this).val(raw);
            });

            $.ajax({
                url: url,
                type: "POST",
                data: new FormData(this),
                processData: false,
                contentType: false,
                cache: false,
                async: false,
                dataType: "JSON",
                success: function(data) {
                    if (data.status) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Your data has been saved',
                            showConfirmButton: false,
                            timer: 1500
                        }).then((result) => {
                            // Jika create oleh marketing: arahkan ke halaman view record yang baru dibuat
                            if (String(id) === '0' && data && data.id) {
                                location.href = "<?= base_url('sml_kertas_kerja/read_form/') ?>" + data.id;
                            } else {
                                location.href = "<?= base_url('sml_kertas_kerja') ?>";
                            }
                        })
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: (data && data.message) ? data.message : 'Gagal menyimpan data'
                        })
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                }
            });
        });
    })
</script>