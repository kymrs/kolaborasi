<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $titleview ?></h1>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <!-- List Config Insentif -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <strong><i class="fa fa-cog"></i> Nominal Insentif per Level</strong>
                    <button type="button" class="btn btn-primary btn-sm" id="btn-save-config">
                        <i class="fa fa-save"></i> Simpan Semua
                    </button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- MOBIL -->
                        <div class="col-md-6">
                            <h6 class="font-weight-bold mb-3">
                                <i class="fa fa-car text-primary"></i> Mobil
                            </h6>
                            <table class="table table-bordered table-sm" id="table-mobil">
                                <thead style="background:#242d4a; color:white;">
                                    <tr>
                                        <th class="text-center">Level</th>
                                        <th>Nominal (Rp)</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-mobil"></tbody>
                            </table>
                        </div>
                        <!-- MOTOR -->
                        <div class="col-md-6">
                            <h6 class="font-weight-bold mb-3">
                                <i class="fa fa-motorcycle text-success"></i> Motor
                            </h6>
                            <table class="table table-bordered table-sm" id="table-motor">
                                <thead style="background:#242d4a; color:white;">
                                    <tr>
                                        <th class="text-center">Level</th>
                                        <th>Nominal (Rp)</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-motor"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- List Rekap Insentif Mekanik -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <strong><i class="fa fa-chart-bar"></i> Rekap Insentif Mekanik</strong>
                </div>
                <div class="card-body">

                    <!-- Filter -->
                    <div class="row align-items-end mb-3">
                        <div class="col-md-2">
                            <label>Bulan</label>
                            <input type="month" class="form-control form-control-sm" id="filter-bulan-insentif">
                        </div>

                        <?php if ($is_nasional): ?>
                        <div class="col-md-3">
                            <label>Cabang</label>
                            <select class="form-control form-control-sm" id="filter-cabang-insentif">
                                <option value="0">Semua Cabang</option>
                                <?php foreach ($list_cabang as $c): ?>
                                <option value="<?= $c->id ?>">
                                    <?= ucwords(strtolower(str_replace('MAC ', '', $c->nama_cabang))) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php endif; ?>

                        <div class="col-md-3">
                            <label>Mekanik</label>
                            <select class="form-control form-control-sm" id="filter-mekanik-insentif">
                                <option value="0">Semua Mekanik</option>
                                <?php foreach ($list_mekanik as $m): ?>
                                <option value="<?= $m->id ?>">
                                    <?= $m->nama ?><?= $m->npk ? ' (' . $m->npk . ')' : '' ?>
                                    <?php if ($is_nasional): ?>
                                        — <?= ucwords(strtolower(str_replace('MAC ', '', $m->nama_cabang))) ?>
                                    <?php endif; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <button type="button" class="btn btn-primary btn-sm" id="btn-tampilkan-insentif">
                                <i class="fa fa-search"></i> Tampilkan
                            </button>
                            <button type="button" class="btn btn-success btn-sm" id="btn-export-insentif"
                                    style="display:none;">
                                <i class="fa fa-table"></i> Export Excel
                            </button>
                            <!-- <button type="button" class="btn btn-outline-success btn-sm"
                                    id="btn-export-insentif-raw" style="display:none;">
                                <i class="fa fa-table"></i> Export Raw Data
                            </button> -->
                        </div>
                    </div>

                    <div id="rekap-insentif-wrapper" style="display:none;">

                        <!-- Summary per Mekanik -->
                        <h6 class="font-weight-bold mb-2">Ringkasan per Mekanik</h6>
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered table-sm">
                                <thead style="background:#242d4a; color:white;">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Nama</th>
                                        <th width="10%">NPK</th>
                                        <th width="15%">Cabang</th>
                                        <th width="12%" class="text-center">Jml Invoice</th>
                                        <th width="15%" class="text-right">Total Insentif</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-summary-insentif"></tbody>
                                <tfoot id="tfoot-summary-insentif"></tfoot>
                            </table>
                        </div>

                        <!-- Detail per Invoice -->
                        <h6 class="font-weight-bold mb-2">Detail per Invoice</h6>
                        <table class="table table-bordered table-sm"
                            id="table-detail-insentif" style="width:100%">
                            <thead style="background:#242d4a; color:white; text-align:center;">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Mekanik</th>
                                    <th>NPK</th>
                                    <th>Cabang</th>
                                    <th>Invoice</th>
                                    <th>Tgl Service</th>
                                    <th>Nopol</th>
                                    <th class="text-center">Level</th>
                                    <th class="text-center">Kategori</th>
                                    <th class="text-right">Insentif</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-detail-insentif"></tbody>
                        </table>

                    </div>
                </div>
            </div>
            <!-- List Rekap Produktivitas Mekanik -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <strong><i class="fa fa-chart-line"></i> Produktivitas Mekanik</strong>
                </div>
                <div class="card-body">

                    <!-- Filter -->
                    <div class="row align-items-end mb-3">
                        <div class="col-md-2">
                            <label>Dari Tanggal</label>
                            <input type="text" class="form-control form-control-sm datepicker"
                                id="filter-tgl-dari-prod" placeholder="DD-MM-YYYY" autocomplete="off">
                        </div>
                        <div class="col-md-2">
                            <label>Sampai Tanggal</label>
                            <input type="text" class="form-control form-control-sm datepicker"
                                id="filter-tgl-sampai-prod" placeholder="DD-MM-YYYY" autocomplete="off">
                        </div>

                        <?php if ($is_nasional): ?>
                        <div class="col-md-3">
                            <label>Cabang</label>
                            <select class="form-control form-control-sm" id="filter-cabang-prod">
                                <option value="0">Semua Cabang</option>
                                <?php foreach ($list_cabang as $c): ?>
                                <option value="<?= $c->id ?>">
                                    <?= ucwords(strtolower(str_replace('MAC ', '', $c->nama_cabang))) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php endif; ?>

                        <div class="col-md-3">
                            <label>Mekanik</label>
                            <select class="form-control form-control-sm" id="filter-mekanik-prod">
                                <option value="0">Semua Mekanik</option>
                                <?php foreach ($list_mekanik as $m): ?>
                                <option value="<?= $m->id ?>">
                                    <?= $m->nama ?><?= $m->npk ? ' (' . $m->npk . ')' : '' ?>
                                    <?php if ($is_nasional): ?>
                                        — <?= ucwords(strtolower(str_replace('MAC ', '', $m->nama_cabang))) ?>
                                    <?php endif; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <button type="button" class="btn btn-primary btn-sm" id="btn-tampilkan-prod">
                                <i class="fa fa-search"></i> Tampilkan
                            </button>
                            <button type="button" class="btn btn-success btn-sm"
                                    id="btn-export-prod-raw" style="display:none;">
                                <i class="fa fa-table"></i> Export Excel
                            </button>
                        </div>
                    </div>

                    <div id="rekap-prod-wrapper" style="display:none;">

                        <!-- Summary 4 kotak -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <div class="card border-left-primary shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Total Service Mobil
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold" id="prod-total-mobil">0</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-left-success shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Total Service Motor
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold" id="prod-total-motor">0</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-left-info shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Total Semua Service
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold" id="prod-total-service">0</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-left-warning shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Total Insentif
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold" id="prod-total-insentif">Rp 0</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabel -->
                        <table class="table table-bordered table-sm"
                            id="table-prod" style="width:100%">
                            <thead style="background:#242d4a; color:white; text-align:center;">
                                <tr>
                                    <th width="4%">No</th>
                                    <th>Nama Mekanik</th>
                                    <th width="10%">NPK</th>
                                    <th width="12%">Cabang</th>
                                    <th width="10%" class="text-center">Service Mobil</th>
                                    <th width="10%" class="text-center">Service Motor</th>
                                    <th width="10%" class="text-center">Total Service</th>
                                    <th width="13%" class="text-right">Total Insentif</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-prod"></tbody>
                            <tfoot id="tfoot-prod"></tfoot>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>

<script>
$(document).ready(function() {

    // Load semua config
    $.ajax({
        url: "<?= site_url('mac_insentif/get_all') ?>",
        type: 'GET', dataType: 'JSON',
        success: function(data) {
            var rowsMobil = '';
            var rowsMotor = '';

            $.each(data, function(i, d) {
                var row = '<tr>' +
                    '<td class="text-center font-weight-bold">Level ' + d.level + '</td>' +
                    '<td>' +
                        '<input type="hidden" name="id[]" value="' + d.id + '">' +
                        '<div class="input-group input-group-sm">' +
                            '<div class="input-group-prepend">' +
                                '<span class="input-group-text">Rp</span>' +
                            '</div>' +
                            '<input type="text" class="form-control nominal-config" ' +
                                   'name="nominal[]" ' +
                                   'value="' + parseInt(d.nominal).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') + '">' +
                        '</div>' +
                    '</td>' +
                '</tr>';

                if (d.kategori === 'Mobil') {
                    rowsMobil += row;
                } else {
                    rowsMotor += row;
                }
            });

            $('#tbody-mobil').html(rowsMobil);
            $('#tbody-motor').html(rowsMotor);
        }
    });

    // Format nominal
    $(document).on('input', '.nominal-config', function() {
        var raw = $(this).val().replace(/[^0-9]/g, '');
        $(this).val(raw.replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
    });

    // Simpan semua
    $('#btn-save-config').on('click', function() {
        var ids     = [];
        var nominals = [];

        $('input[name="id[]"]').each(function() { ids.push($(this).val()); });
        $('input[name="nominal[]"]').each(function() {
            nominals.push($(this).val().replace(/\./g, ''));
        });

        $.ajax({
            url: "<?= site_url('mac_insentif/update') ?>",
            type: 'POST',
            data: { id: ids, nominal: nominals },
            dataType: 'JSON',
            success: function(res) {
                if (res.status) {
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: res.message,
                        timer: 1500, showConfirmButton: false });
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: res.error });
                }
            }
        });
    });

    // ===== REKAP INSENTIF =====
    var dtInsentif = null;

    function fmtRp(n) {
        return 'Rp ' + parseInt(n || 0).toLocaleString('id-ID');
    }

    $('#btn-tampilkan-insentif').on('click', function() {
        var bulan      = $('#filter-bulan-insentif').val();
        console.log(bulan);
        var mekanikId  = $('#filter-mekanik-insentif').val() || 0;
        var cabangId   = $('#filter-cabang-insentif').val()  || 0;

        $.ajax({
            url: "<?= site_url('mac_insentif/get_rekap_insentif') ?>",
            type: 'POST',
            dataType: 'JSON',
            data: {
                bulan:          bulan,
                mekanik_id:     mekanikId,
                filter_cabang:  cabangId,
            },
            beforeSend: function() {
                $('#btn-tampilkan-insentif').prop('disabled', true)
                    .html('<i class="fa fa-spinner fa-spin"></i>');
            },
            success: function(res) {
                $('#btn-tampilkan-insentif').prop('disabled', false)
                    .html('<i class="fa fa-search"></i> Tampilkan');

                if (!res.status) return;

                // Summary
                var summaryRows = '';
                var grandTotal  = 0;
                $.each(res.summary, function(i, s) {
                    grandTotal += parseFloat(s.total_insentif);
                    var cabang = s.nama_cabang
                        ? s.nama_cabang.replace(/^MAC\s+/i, '').toLowerCase()
                            .replace(/\b\w/g, function(l){ return l.toUpperCase(); })
                        : '—';
                    summaryRows += '<tr>' +
                        '<td class="text-center">' + (i + 1) + '</td>' +
                        '<td><strong>' + s.nama + '</strong></td>' +
                        '<td>' + (s.npk || '—') + '</td>' +
                        '<td>' + cabang + '</td>' +
                        '<td class="text-center">' + s.jumlah_invoice + '</td>' +
                        '<td class="text-right font-weight-bold text-success">' +
                            fmtRp(s.total_insentif) + '</td>' +
                    '</tr>';
                });

                $('#tbody-summary-insentif').html(
                    summaryRows || '<tr><td colspan="6" class="text-center text-muted">Tidak ada data</td></tr>'
                );
                $('#tfoot-summary-insentif').html(
                    '<tr style="background:#242d4a; color:white; font-weight:bold;">' +
                    '<td colspan="5" class="text-right">TOTAL</td>' +
                    '<td class="text-right">' + fmtRp(grandTotal) + '</td>' +
                    '</tr>'
                );

                // Detail DataTables
                if (dtInsentif) {
                    dtInsentif.destroy();
                    $('#tbody-detail-insentif').empty();
                }

                var detailData = res.detail.map(function(d, i) {
                    var cabang = d.nama_cabang
                        ? d.nama_cabang.replace(/^MAC\s+/i, '').toLowerCase()
                            .replace(/\b\w/g, function(l){ return l.toUpperCase(); })
                        : '—';
                    return [
                        i + 1,
                        d.nama,
                        d.npk || '—',
                        cabang,
                        d.invoice_number,
                        d.awal_service
                            ? d.awal_service.substring(0, 10).split('-').reverse().join('-')
                            : '—',
                        d.nopol || '—',
                        '<span class="badge badge-primary">Level ' + d.level + '</span>',
                        d.kategori,
                        '<span class="font-weight-bold text-success">' +
                            fmtRp(d.nominal_per_mekanik) + '</span>',
                    ];
                });

                dtInsentif = $('#table-detail-insentif').DataTable({
                    data: detailData,
                    pageLength: 10,
                    language: {
                        search: 'Cari:', lengthMenu: 'Tampilkan _MENU_ baris',
                        info: '_START_ - _END_ dari _TOTAL_ baris',
                        paginate: { previous: 'Prev', next: 'Next' },
                        zeroRecords: 'Tidak ada data'
                    },
                    columnDefs: [
                        { targets: [0, 7, 8], className: 'text-center' },
                        { targets: [9], className: 'text-right' },
                    ]
                });

                $('#rekap-insentif-wrapper').show();
                $('#btn-export-insentif').show();
                $('#btn-export-insentif-raw').show();
            },
            error: function() {
                $('#btn-tampilkan-insentif').prop('disabled', false)
                    .html('<i class="fa fa-search"></i> Tampilkan');
                Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal memuat data.' });
            }
        });
    });

    // Export Excel
    $('#btn-export-insentif').on('click', function() {
        var url = "<?= site_url('mac_insentif/export_rekap_insentif') ?>"
            + '?bulan='         + encodeURIComponent($('#filter-bulan-insentif').val())
            + '&mekanik_id='    + ($('#filter-mekanik-insentif').val() || 0)
            + '&filter_cabang=' + ($('#filter-cabang-insentif').val()  || 0);
        window.location.href = url;
    });

    // Export Raw Data
    $('#btn-export-insentif-raw').on('click', function() {
        var url = "<?= site_url('mac_insentif/export_rekap_insentif_raw') ?>"
            + '?bulan='         + encodeURIComponent($('#filter-bulan-insentif').val())
            + '&mekanik_id='    + ($('#filter-mekanik-insentif').val() || 0)
            + '&filter_cabang=' + ($('#filter-cabang-insentif').val()  || 0);
        window.location.href = url;
    });

    // Filter cabang → update dropdown mekanik (Nasional only)
    $('#filter-cabang-insentif').on('change', function() {
        var cabangId = $(this).val();
        var $sel     = $('#filter-mekanik-insentif');

        // Reset mekanik
        $sel.find('option:not(:first)').remove();

        if (cabangId == 0) {
            // Kembalikan semua mekanik
            <?php foreach ($list_mekanik as $m): ?>
            $sel.append('<option value="<?= $m->id ?>"><?= $m->nama ?><?= $m->npk ? " ({$m->npk})" : "" ?> — <?= ucwords(strtolower(str_replace("MAC ", "", $m->nama_cabang))) ?></option>');
            <?php endforeach; ?>
        } else {
            // Filter mekanik sesuai cabang yang dipilih via AJAX
            $.ajax({
                url: "<?= site_url('mac_invoice/get_mekanik') ?>",
                type: 'POST',
                dataType: 'JSON',
                data: { search: '', cabang_id: cabangId },
                success: function(data) {
                    $.each(data, function(i, m) {
                        $sel.append(
                            '<option value="' + m.id + '">' +
                            m.nama + (m.npk ? ' (' + m.npk + ')' : '') +
                            '</option>'
                        );
                    });
                }
            });
        }
    });

    // ===== PRODUKTIVITAS MEKANIK =====
    var dtProd = null;

    function fmtRpProd(n) {
        return 'Rp ' + parseInt(n || 0).toLocaleString('id-ID');
    }

    $('#btn-tampilkan-prod').on('click', function() {
        var tglDari   = $('#filter-tgl-dari-prod').val();
        var tglSampai = $('#filter-tgl-sampai-prod').val();
        var cabangId  = $('#filter-cabang-prod').val()  || 0;
        var mekanikId = $('#filter-mekanik-prod').val() || 0;

        $.ajax({
            url: "<?= site_url('mac_insentif/get_rekap_produktivitas') ?>",
            type: 'POST',
            dataType: 'JSON',
            data: {
                tgl_dari:      tglDari,
                tgl_sampai:    tglSampai,
                filter_cabang: cabangId,
                mekanik_id:    mekanikId,
            },
            beforeSend: function() {
                $('#btn-tampilkan-prod').prop('disabled', true)
                    .html('<i class="fa fa-spinner fa-spin"></i>');
            },
            success: function(res) {
                $('#btn-tampilkan-prod').prop('disabled', false)
                    .html('<i class="fa fa-search"></i> Tampilkan');

                if (!res.status) return;

                // Update summary kotak
                $('#prod-total-mobil').text(res.total_mobil);
                $('#prod-total-motor').text(res.total_motor);
                $('#prod-total-service').text(res.total_service);
                $('#prod-total-insentif').text(fmtRpProd(res.total_insentif));

                // DataTables
                if (dtProd) {
                    dtProd.destroy();
                    $('#tbody-prod').empty();
                    $('#tfoot-prod').empty();
                }

                var tableData = res.rows.map(function(d, i) {
                    var avgPerService = d.total_service > 0
                        ? Math.round(d.total_insentif / d.total_service)
                        : 0;

                    var cabang = d.nama_cabang
                        ? d.nama_cabang.replace(/^MAC\s+/i, '').toLowerCase()
                            .replace(/\b\w/g, function(l) { return l.toUpperCase(); })
                        : '—';

                    return [
                        i + 1,
                        '<strong>' + d.nama + '</strong>',
                        d.npk || '—',
                        cabang,
                        '<span class="badge badge-primary">' + d.jml_mobil + '</span>',
                        '<span class="badge badge-success">' + d.jml_motor + '</span>',
                        '<span class="badge badge-info font-weight-bold">' + d.total_service + '</span>',
                        fmtRpProd(d.total_insentif),
                        fmtRpProd(avgPerService),
                    ];
                });

                dtProd = $('#table-prod').DataTable({
                    data: tableData,
                    pageLength: 10,
                    language: {
                        search: 'Cari:', lengthMenu: 'Tampilkan _MENU_ baris',
                        info: '_START_ - _END_ dari _TOTAL_ baris',
                        paginate: { previous: 'Prev', next: 'Next' },
                        zeroRecords: 'Tidak ada data'
                    },
                    columnDefs: [
                        { targets: [0, 4, 5, 6], className: 'text-center' },
                        { targets: [7], className: 'text-right' },
                    ],
                    drawCallback: function() {
                        var avgTotal = res.total_service > 0
                            ? Math.round(res.total_insentif / res.total_service) : 0;

                        $('#tfoot-prod').html(
                            '<tr style="background:#242d4a; color:white; font-weight:bold;">' +
                            '<td colspan="4" class="text-right">TOTAL</td>' +
                            '<td class="text-center">' + res.total_mobil   + '</td>' +
                            '<td class="text-center">' + res.total_motor   + '</td>' +
                            '<td class="text-center">' + res.total_service + '</td>' +
                            '<td class="text-right">'  + fmtRpProd(res.total_insentif) + '</td>' +
                            '</tr>'
                        );
                    }
                });

                $('#rekap-prod-wrapper').show();
                $('#btn-export-prod-raw').show();
            },
            error: function() {
                $('#btn-tampilkan-prod').prop('disabled', false)
                    .html('<i class="fa fa-search"></i> Tampilkan');
                Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal memuat data.' });
            }
        });
    });

    // Export Raw
    $('#btn-export-prod-raw').on('click', function() {
        var url = "<?= site_url('mac_insentif/export_produktivitas_raw') ?>"
            + '?tgl_dari='      + encodeURIComponent($('#filter-tgl-dari-prod').val())
            + '&tgl_sampai='    + encodeURIComponent($('#filter-tgl-sampai-prod').val())
            + '&filter_cabang=' + ($('#filter-cabang-prod').val()  || 0)
            + '&mekanik_id='    + ($('#filter-mekanik-prod').val() || 0);
        window.location.href = url;
    });

    // Filter cabang → update dropdown mekanik (Nasional only)
    $('#filter-cabang-prod').on('change', function() {
        var cabangId = $(this).val();
        var $sel     = $('#filter-mekanik-prod');
        $sel.find('option:not(:first)').remove();

        if (cabangId == 0) {
            <?php foreach ($list_mekanik as $m): ?>
            $sel.append('<option value="<?= $m->id ?>"><?= $m->nama ?><?= $m->npk ? " ({$m->npk})" : "" ?> — <?= ucwords(strtolower(str_replace("MAC ", "", $m->nama_cabang))) ?></option>');
            <?php endforeach; ?>
        } else {
            $.ajax({
                url: "<?= site_url('mac_invoice/get_mekanik') ?>",
                type: 'POST',
                dataType: 'JSON',
                data: { search: '', cabang_id: cabangId },
                success: function(data) {
                    $.each(data, function(i, m) {
                        $sel.append(
                            '<option value="' + m.id + '">' +
                            m.nama + (m.npk ? ' (' + m.npk + ')' : '') +
                            '</option>'
                        );
                    });
                }
            });
        }
    });

});
</script>