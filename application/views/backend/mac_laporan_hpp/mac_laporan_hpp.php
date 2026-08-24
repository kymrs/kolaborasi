<style>
    #table-hpp thead th:nth-child(1), #table-hpp tbody td:nth-child(1) {
        width: 1%;
    }
    #table-hpp thead th:nth-child(2), #table-hpp tbody td:nth-child(2) {
        width: 4%;
    }
    #table-hpp thead th:nth-child(3), #table-hpp tbody td:nth-child(3) {
        width: 10%;
    }
    #table-hpp thead th:nth-child(4), #table-hpp tbody td:nth-child(4) {
        width: 9%;
    }
    #table-hpp thead th:nth-child(5), #table-hpp tbody td:nth-child(5) {
        width: 7%;
    }
    #table-hpp thead th:nth-child(6), #table-hpp tbody td:nth-child(6) {
        width: 27%;
    }
    #table-hpp thead th:nth-child(7), #table-hpp tbody td:nth-child(7) {
        width: 10%;
    }
    #table-hpp thead th:nth-child(8), #table-hpp tbody td:nth-child(8) {
        width: 9%;
    }
    #table-hpp thead th:nth-child(9), #table-hpp tbody td:nth-child(9) {
        width: 8%;
    }
    #table-hpp thead th:nth-child(10), #table-hpp tbody td:nth-child(10) {
        width: 7%;
    }
</style>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $titleview ?></h1>
    </div>

    <!-- FILTER -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <strong><i class="fa fa-filter"></i> Filter</strong>
        </div>
        <div class="card-body">
            <div class="row align-items-end">
                <div class="col-md-2">
                    <label>Dari Tanggal</label>
                    <input type="text" class="form-control form-control-sm datepicker"
                           id="tgl_dari" placeholder="DD-MM-YYYY" autocomplete="off" style="cursor: pointer;">
                </div>
                <div class="col-md-2">
                    <label>Sampai Tanggal</label>
                    <input type="text" class="form-control form-control-sm datepicker"
                           id="tgl_sampai" placeholder="DD-MM-YYYY" autocomplete="off" style="cursor: pointer;">
                </div>
                <?php if ($is_nasional): ?>
                <div class="col-md-3">
                    <label>Cabang</label>
                    <select class="form-control form-control-sm" id="filter-cabang">
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
                    <button type="button" class="btn btn-primary btn-sm" id="btn-tampilkan">
                        <i class="fa fa-search"></i> Tampilkan
                    </button>
                    <div class="btn-group" id="export-group">
                        <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-file-excel"></i> Export
                        </button>

                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="javascript:void(0)" id="btn-export">
                                <i class="fa fa-file-excel text-success mr-2"></i>
                                Export Laporan
                            </a>

                            <a class="dropdown-item" href="javascript:void(0)" id="btn-export-raw">
                                <i class="fa fa-table text-primary mr-2"></i>
                                Export Raw Data
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="result-wrapper" style="display:none;">

        <!-- Tambahkan setelah div row 4 kotak summary -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <strong><i class="fa fa-chart-bar"></i> Chart</strong>
                <span class="periode-label" class="text-muted ml-2 small" style="font-size: 12px;"></span>
                <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-primary active" id="btn-chart-bar">
                        <i class="fa fa-chart-bar"></i> Bar
                    </button>
                    <button type="button" class="btn btn-outline-primary" id="btn-chart-line">
                        <i class="fa fa-chart-line"></i> Trend
                    </button>
                </div>
            </div>
            <div class="card-body">
                <canvas id="chartHpp" height="100"></canvas>
            </div>
        </div>

        <!-- SUMMARY 4 KOTAK -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Penjualan
                        </div>
                        <div class="h5 mb-0 font-weight-bold" id="sum-penjualan">Rp 0</div>
                        <div class="text-xs text-muted mt-1" id="sum-invoice">0 Invoice</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Total HPP
                        </div>
                        <div class="h5 mb-0 font-weight-bold" id="sum-hpp">Rp 0</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Gross Profit
                        </div>
                        <div class="h5 mb-0 font-weight-bold" id="sum-profit">Rp 0</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                        Overall Margin
                    </div>
                    <div class="h5 mb-0 font-weight-bold" id="sum-margin">0%</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TABEL DETAIL -->
        <div class="card shadow mb-4">
            <div class="card-header py-2">
                <strong><i class="fa fa-table"></i> Detail per Invoice</strong>
                <span class="periode-label" class="text-muted ml-2 small" style="font-size: 12px;"></span>
            </div>
            <div class="card-body p-2" style="overflow-x: auto;">
                <table class="table table-bordered" id="table-hpp" style="width:100%">
                    <thead style="background:#242d4a; color:white; text-align:center; font-size: 13px;">
                        <tr>
                            <th>No</th>
                            <th>Invoice</th>
                            <th>Tgl Service</th>
                            <th>Nopol</th>
                            <th>KM</th>
                            <th>Customer</th>
                            <th>Cabang</th>
                            <th>Total Jual</th>
                            <th>HPP</th>
                            <th>Profit</th>
                            <th>Margin</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-hpp" style="font-size: 13px;"></tbody>
                    <tfoot id="tfoot-hpp" style="font-size: 13px;"></tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail HPP per Item -->
<div class="modal fade" id="modalDetailHpp" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fa fa-search"></i>
                    <span id="modal-detail-hpp-title">Detail HPP per Item</span>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body p-2">
                <table class="table table-bordered table-sm mb-0">
                    <thead style="background:#242d4a; color:white; text-align:center;">
                        <tr>
                            <th width="4%">No</th>
                            <th>Item</th>
                            <th width="6%">Qty</th>
                            <th width="12%">Total Jual</th>
                            <th width="12%">HPP</th>
                            <th width="12%">Profit</th>
                            <th width="8%">Margin</th>
                            <th width="8%">Markup</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-detail-hpp">
                        <tr>
                            <td colspan="8" class="text-center py-3">Loading...</td>
                        </tr>
                    </tbody>
                    <tfoot id="tfoot-detail-hpp"></tfoot>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>

<script>
$(document).ready(function() {

    var dtTable = null;

    // Datepicker
    $('.datepicker').datepicker({
        dateFormat: 'dd-mm-yy',
        changeMonth: true,
        changeYear: true
    });

    function fmtRp(n) {
        return parseInt(n || 0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    // ===== CHART =====
    var chartHppInstance = null;

    // Tampilkan
    $('#btn-tampilkan').on('click', function() {
        var tglDari    = $('#tgl_dari').val();
        var tglSampai  = $('#tgl_sampai').val();
        <?php if ($is_nasional): ?>
            var cabangId   = $('#filter-cabang').val() || 0;
        <?php else: ?>
            var cabangId   = <?= $cabang_id ?>;
        <?php endif; ?>

        $.ajax({
            url: "<?= site_url('mac_laporan_hpp/get_data') ?>",
            type: 'POST',
            dataType: 'JSON',
            data: {
                tgl_dari:      tglDari,
                tgl_sampai:    tglSampai,
                filter_cabang: cabangId
            },
            beforeSend: function() {
                $('#btn-tampilkan').prop('disabled', true)
                    .html('<i class="fa fa-spinner fa-spin"></i>');
            },
            success: function(res) {
                console.log(res)
                $('#btn-tampilkan').prop('disabled', false)
                    .html('<i class="fa fa-search"></i> Tampilkan');

                if (!res.status) {
                    Swal.fire({ icon: 'error', title: 'Error', text: res.error });
                    return;
                }

                // Summary kotak
                $('#sum-penjualan').text('Rp ' + fmtRp(res.total_penjualan));
                $('#sum-hpp').text('Rp ' + fmtRp(res.total_hpp));
                $('#sum-invoice').text(res.jumlah_invoice + ' Invoice');

                var profitColor = res.total_profit >= 0 ? 'text-success' : 'text-danger';
                $('#sum-profit').text('Rp ' + fmtRp(res.total_profit))
                    .removeClass('text-success text-danger').addClass(profitColor);

                var marginColor = res.overall_margin >= 30 ? 'text-success'
                    : res.overall_margin >= 10 ? 'text-warning' : 'text-danger';
                $('#sum-margin').text(res.overall_margin + '%')
                    .removeClass('text-success text-warning text-danger').addClass(marginColor);

                // Periode label
                if (tglDari || tglSampai) {
                    $('.periode-label').text('Periode: ' + (tglDari || '-') + ' s/d ' + (tglSampai || '-'));
                }

                // DataTables
                if (dtTable) { dtTable.destroy(); $('#tbody-hpp').empty(); $('#tfoot-hpp').empty(); }

                var tableData = res.rows.map(function(d, i) {
                var profit      = parseFloat(d.profit);
                var profitClass = profit >= 0 ? 'text-success' : 'text-danger';
                var margin      = parseFloat(d.margin_persen);
                var marginClass = margin >= 30 ? 'text-success'
                    : margin >= 10 ? 'text-warning' : 'text-danger';

                var cabang = d.nama_cabang
                    ? d.nama_cabang.replace(/^MAC\s+/i,'').toLowerCase()
                        .replace(/\b\w/g, function(l) { return l.toUpperCase(); })
                    : '-';

                return [
                            '<td>' + (i + 1) + '</td>',
                            '<td><div class="text-center"><a href="javascript:void(0)" class="badge badge-primary btn-detail-hpp" style="padding:6px 8px;font-size:12px;border-radius:6px;box-shadow:0 .125rem .25rem rgba(0,0,0,.075); margin: 0" data-id="' + d.id + '"><i class="fa fa-search mr-1"></i>' + d.invoice_number + '</a></div></td>',
                            '<td><div class="text-center">' + (d.awal_service ? d.awal_service.substring(0, 10).split('-').reverse().join('-') : '-') + '</div></td>',
                            '<td><div class="text-center">' + (d.nopol || '-') + '</div></td>',
                            '<td><div class="text-center">' + (d.km ? Number(d.km).toLocaleString('id-ID') : '-') + '</div></td>',
                            '<td>' + (d.customer_name || '-') + '</td>',
                            '<td>' + cabang + '</td>',
                            '<td class="text-right">' + fmtRp(d.total_penjualan) + '</td>',
                            '<td class="text-right">' + fmtRp(d.total_hpp) + '</td>',
                            '<td><span class="' + profitClass + ' font-weight-bold">' + fmtRp(profit) + '</span></td>',
                            '<td><span class="' + marginClass + ' font-weight-bold">' + margin + '%</span></td>',
                        ];
                });

                function renderChart(res, type) {
                    var labels = res.rows.map(function(d) {
                        return (d.invoice_number || '').replace('INVMAC', '');
                    });
                    var dataSell = res.rows.map(function(d) { return parseFloat(d.total_penjualan); });
                    var dataHpp  = res.rows.map(function(d) { return parseFloat(d.total_hpp); });
                    var dataProfit = res.rows.map(function(d) { return parseFloat(d.profit); });

                    if (chartHppInstance) {
                        chartHppInstance.destroy();
                    }

                    var ctx = document.getElementById('chartHpp').getContext('2d');
                    
                    chartHppInstance = new Chart(ctx, {
                        type: type === 'line' ? 'line' : 'bar',
                        data: {
                            labels: labels,
                            datasets: [
                                {
                                    label: 'Total Penjualan',
                                    data: dataSell,
                                    backgroundColor: type === 'line' ? 'transparent' : 'rgba(78, 115, 223, 0.6)',
                                    borderColor: 'rgba(78, 115, 223, 1)',
                                    borderWidth: 2,
                                    pointRadius: type === 'line' ? 4 : 0,
                                    fill: false,
                                    tension: 0.3,
                                },
                                {
                                    label: 'HPP',
                                    data: dataHpp,
                                    backgroundColor: type === 'line' ? 'transparent' : 'rgba(231, 74, 59, 0.6)',
                                    borderColor: 'rgba(231, 74, 59, 1)',
                                    borderWidth: 2,
                                    pointRadius: type === 'line' ? 4 : 0,
                                    fill: false,
                                    tension: 0.3,
                                },
                                {
                                    label: 'Profit',
                                    data: dataProfit,
                                    backgroundColor: type === 'line' ? 'transparent' : 'rgba(28, 200, 138, 0.6)',
                                    borderColor: 'rgba(28, 200, 138, 1)',
                                    borderWidth: 2,
                                    pointRadius: type === 'line' ? 4 : 0,
                                    fill: false,
                                    tension: 0.3,
                                },
                            ]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: { position: 'top' },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return context.dataset.label + ': Rp ' +
                                                parseInt(context.parsed.y).toLocaleString('id-ID');
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return 'Rp ' + parseInt(value).toLocaleString('id-ID');
                                        }
                                    }
                                },
                                x: {
                                    ticks: {
                                        maxRotation: 45,
                                        minRotation: 45,
                                        // Potong label jika terlalu panjang
                                        callback: function(val, index) {
                                            var label = this.getLabelForValue(val);
                                            return label.length > 12 ? label.substr(0, 12) + '...' : label;
                                        }
                                    }
                                }
                            }
                        }
                    });
                }

                // Panggil chart saat data berhasil dimuat
                renderChart(res, 'bar');

                // Toggle chart type
                $('#btn-chart-bar').on('click', function() {
                    $(this).addClass('active');
                    $('#btn-chart-line').removeClass('active');
                    renderChart(res, 'bar');
                });

                $('#btn-chart-line').on('click', function() {
                    $(this).addClass('active');
                    $('#btn-chart-bar').removeClass('active');
                    renderChart(res, 'line');
                });

                dtTable = $('#table-hpp').DataTable({
                    data: tableData,
                    pageLength: 25,
                    language: {
                        search: 'Cari:', lengthMenu: 'Tampilkan _MENU_ baris',
                        info: '_START_ - _END_ dari _TOTAL_ baris',
                        paginate: { previous: 'Prev', next: 'Next' },
                        zeroRecords: 'Tidak ada data'
                    },
                    columnDefs: [
                        { targets: [0], className: 'text-center' },
                        { targets: [6, 7, 8], className: 'text-right' },
                        { targets: [9], className: 'text-center' },
                    ],
                    drawCallback: function() {
                        var grand_markup = res.total_hpp > 0
                            ? Math.round((res.total_profit / res.total_hpp) * 100 * 100) / 100
                            : null;

                    $('#tfoot-hpp').html(
                        '<tr style="background:#242d4a; color:white; font-weight:bold;">' +
                        '<td colspan="7" class="text-right">TOTAL</td>' +
                        '<td class="text-right">' + fmtRp(res.total_penjualan) + '</td>' +
                        '<td class="text-right">' + fmtRp(res.total_hpp)       + '</td>' +
                        '<td class="text-right">' + fmtRp(res.total_profit)    + '</td>' +
                        '<td class="text-center">' + res.overall_margin + '%</td>' +
                        '</tr>'
                    );
                    }
                });

                $('#result-wrapper').show();
                $('#btn-export').show();
                $('#btn-export-raw').show();
            },
            error: function() {
                $('#btn-tampilkan').prop('disabled', false)
                    .html('<i class="fa fa-search"></i> Tampilkan');
                Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal mengambil data.' });
            }
        });
    });

    $(document).on('click', '.btn-detail-hpp', function() {
        var invoice_id = $(this).data('id');
        openModalDetailHpp(invoice_id);
    });

    function openModalDetailHpp(invoice_id) {
        $('#tbody-detail-hpp').html(
            '<tr><td colspan="8" class="text-center py-3">' +
            '<i class="fa fa-spinner fa-spin"></i> Loading...</td></tr>'
        );
        $('#tfoot-detail-hpp').html('');
        $('#modal-detail-hpp-title').text('Loading...');
        $('#modalDetailHpp').modal('show');

        $.ajax({
            url: "<?= site_url('mac_laporan_hpp/get_detail_hpp') ?>",
            type: 'POST',
            dataType: 'JSON',
            data: { invoice_id: invoice_id },
            success: function(res) {
                if (!res.status) {
                    $('#tbody-detail-hpp').html(
                        '<tr><td colspan="8" class="text-center text-danger">' + res.error + '</td></tr>'
                    );
                    return;
                }

                var inv = res.invoice;
                $('#modal-detail-hpp-title').text(
                    inv.invoice_number + ' - ' + inv.customer_name + ' (' + (inv.nopol || '-') + ')'
                );

                var rows = '';
                $.each(res.rows, function(i, d) {
                    var profitClass = d.profit >= 0 ? 'text-success' : 'text-danger';
                    var marginClass = d.margin >= 30 ? 'text-success'
                        : d.margin >= 10 ? 'text-warning' : 'text-danger';
                    var markupText  = d.markup !== null
                        ? '<span class="' + (d.markup >= 30 ? 'text-success' : d.markup >= 10 ? 'text-warning' : 'text-danger') + '">' + d.markup + '%</span>'
                        : '<span class="text-muted" title="HPP = 0, markup tidak dapat dihitung">-</span>';

                    rows += '<tr>' +
                        '<td class="text-center">' + (i + 1) + '</td>' +
                        '<td>' + d.item + '</td>' +
                        '<td class="text-center">' + d.qty + '</td>' +
                        '<td class="text-right">' + fmtRp(d.total_jual) + '</td>' +
                        '<td class="text-right">' + (d.hpp > 0 ? fmtRp(d.hpp) : '<span class="text-muted">-</span>') + '</td>' +
                        '<td class="text-right ' + profitClass + ' font-weight-bold">' + fmtRp(d.profit) + '</td>' +
                        '<td class="text-center ' + marginClass + ' font-weight-bold">' + d.margin + '%</td>' +
                        '<td class="text-center">' + markupText + '</td>' +
                    '</tr>';
                });

                $('#tbody-detail-hpp').html(rows || '<tr><td colspan="8" class="text-center text-muted">Tidak ada data</td></tr>');

                // Footer total
                var grandMarkupText = res.grand_markup !== null
                    ? res.grand_markup + '%' : '-';

                $('#tfoot-detail-hpp').html(
                    '<tr style="background:#242d4a; color:white; font-weight:bold;">' +
                    '<td colspan="3" class="text-right">TOTAL</td>' +
                    '<td class="text-right">' + fmtRp(res.grand_jual)   + '</td>' +
                    '<td class="text-right">' + fmtRp(res.grand_hpp)    + '</td>' +
                    '<td class="text-right">' + fmtRp(res.grand_profit) + '</td>' +
                    '<td class="text-center">' + res.grand_margin + '%</td>' +
                    '<td class="text-center">' + grandMarkupText + '</td>' +
                    '</tr>'
                );
            },
            error: function() {
                $('#tbody-detail-hpp').html(
                    '<tr><td colspan="8" class="text-center text-danger">Gagal memuat data.</td></tr>'
                );
            }
        });
    }

    // Export Excel
    $('#btn-export').on('click', function() {
        var url = "<?= site_url('mac_laporan_hpp/export_excel') ?>"
            + '?tgl_dari='      + encodeURIComponent($('#tgl_dari').val())
            + '&tgl_sampai='    + encodeURIComponent($('#tgl_sampai').val())
            <?php if ($is_nasional): ?>
            + '&filter_cabang=' + ($('#filter-cabang').val() || 0);
            <?php else: ?>
                + '&filter_cabang=<?= $cabang_id ?>';
            <?php endif; ?>
        window.location.href = url;
    });

    // Export Raw Data
    $('#btn-export-raw').on('click', function() {
        var url = "<?= site_url('mac_laporan_hpp/export_excel_raw') ?>"
            + '?tgl_dari='      + encodeURIComponent($('#tgl_dari').val())
            + '&tgl_sampai='    + encodeURIComponent($('#tgl_sampai').val())
            + '&filter_cabang=' + ($('#filter-cabang').val() || 0);
        window.location.href = url;
    });
});
</script>