<style>
    .select2-container--default .select2-selection--single {
        height: 38px !important;
        border: 1px solid #ced4da !important;
        border-radius: 4px !important;
        padding: 0 !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 38px !important;
        padding-left: 12px !important;
        color: #495057 !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px !important;
        right: 6px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #6c757d !important;
    }
    .select2-container {
        width: 100% !important;
    }
    /* error state */
    .select2-container--default.select2-is-invalid .select2-selection--single {
        border-color: #dc3545 !important;
    }
</style>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $titleview ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">

            <!-- FILTER CARD -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <strong><i class="fa fa-filter"></i> Filter</strong>
                    <a class="btn btn-primary btn-sm" href="<?= base_url('mac_inventory') ?>">
                        <i class="fas fa-chevron-left"></i>&nbsp;Back
                    </a>
                </div>
                <div class="card-body">
                    <div class="row align-items-end">
                        <!-- Pilih Barang -->
                        <div class="col-md-3">
                            <label>Pilih Barang <span class="text-danger">*</span></label>
                            <select id="select-barang" style="width:100%">
                                <option value="">-- Cari kode / nama barang --</option>
                            </select>
                        </div>
                        <!-- Pilih Cabang untuk User Nasional -->
                        <?php if ($is_nasional): ?>
                            <div class="col-md-2">
                                <label>Cabang</label>
                                <select class="form-control" id="filter-cabang-mutasi">
                                    <option value="0">Semua Cabang</option>
                                    <?php foreach ($list_cabang as $c): ?>
                                        <?php if ($c->id == 1) continue; ?>
                                        <option value="<?= $c->id ?>">
                                            <?= ucwords(strtolower(str_replace('MAC ', '', $c->nama_cabang))) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php endif; ?>
                        <!-- Tanggal Dari -->
                        <div class="col-md-2">
                            <label>Dari Tanggal</label>
                            <input type="text" class="form-control datepicker" id="tgl_dari"
                                   placeholder= "DD-MM-YYYY" autocomplete="off" style="cursor:pointer;">
                        </div>
                        <!-- Tanggal Sampai -->
                        <div class="col-md-2">
                            <label>Sampai Tanggal</label>
                            <input type="text" class="form-control datepicker" id="tgl_sampai"
                                   placeholder="DD-MM-YYYY" autocomplete="off" style="cursor:pointer;">
                        </div>
                        <!-- Tombol -->
                        <div class="col-md-3">
                            <button type="button" class="btn btn-primary btn" id="btn-tampilkan">
                                <i class="fa fa-search"></i> Cari
                            </button>
                            <!-- <button type="button" class="btn btn-success btn" id="btn-excel" style="display:none;">
                                <i class="fa fa-file-excel"></i> Export
                            </button> -->
                            <button type="button" class="btn btn-secondary btn" id="btn-reset">
                                <i class="fa fa-undo"></i> Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- HASIL MUTASI STOK -->
            <div id="result-wrapper" style="display:none;">

                <!-- INFO BARANG -->
                <div class="card shadow mb-3">
                    <div class="card-body py-3">
                        <div class="row">
                            <div class="col-md-4">
                                <table class="table table-borderless table-sm mb-0">
                                    <tr>
                                        <td width="130"><strong>Kode Produk</strong></td>
                                        <td>: <span id="info-kode" class="font-weight-bold text-primary"></span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nama Produk</strong></td>
                                        <td>: <span id="info-nama"></span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Kategori</strong></td>
                                        <td>: <span id="info-kategori"></span></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-4">
                                <table class="table table-borderless table-sm mb-0">
                                    <tr>
                                        <td width="130"><strong>Satuan</strong></td>
                                        <td>: <span id="info-satuan"></span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Stok Saat Ini</strong></td>
                                        <td>: <span id="info-stok-sekarang" class="font-weight-bold"></span></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-4">
                                <!-- Summary kotak -->
                                <div class="row text-center">
                                    <!-- <div class="col-4">
                                        <div class="border rounded py-2 px-1 bg-light">
                                            <div class="small text-muted">Stok Awal</div>
                                            <div class="font-weight-bold" id="sum-awal">0</div>
                                        </div>
                                    </div>   -->
                                    <div class="col-6">
                                        <div class="border rounded py-2 px-1" style="background:#d4edda;">
                                            <div class="small text-muted">Total Masuk</div>
                                            <div class="font-weight-bold text-success" id="sum-masuk">0</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="border rounded py-2 px-1" style="background:#f8d7da;">
                                            <div class="small text-muted">Total Keluar</div>
                                            <div class="font-weight-bold text-danger" id="sum-keluar">0</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row text-center mt-2">
                                    <div class="col-12">
                                        <div class="border rounded py-2 px-1" style="background:#cce5ff;">
                                            <div class="small text-muted">Stok Akhir Periode</div>
                                            <div class="font-weight-bold text-primary h5 mb-0" id="sum-akhir">0</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TABEL MUTASI STOK -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <strong><i class="fa fa-history"></i> Histori Transaksi</strong>
                        <span id="periode-label" class="text-muted ml-2 small"></span>
                    </div>
                    <div class="card-body p-2">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm mb-0" id="table-mutasi-stok" style="font-size:14px;">
                                <thead style="background:#242d4a; color:white; text-align:center;">
                                    <tr>
                                        <th width="3%">No</th>
                                        <th width="8%">Tanggal</th>
                                        <th width="6%">Tipe</th>
                                        <th width="22%">Keterangan</th>
                                        <th width="9%">Dokumen</th>
                                        <th width="6%">Masuk</th>
                                        <th width="6%">Keluar</th>
                                        <th width="6%">Stok</th>
                                        <th width="8%">Harga Beli</th>
                                        <th width="8%">Harga Jual</th>
                                        <th width="15%">Nilai</th>
                                        <th width="15%">Profit</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-mutasi-stok">
                                    <tr>
                                        <td colspan="10" class="text-center text-muted py-4">
                                            Pilih barang dan klik Tampilkan
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot id="tfoot-mutasi-stok"></tfoot>
                            </table>
                        </div>
                    </div>
                </div>

            </div><!-- end result-wrapper -->

        </div>
    </div>
</div>

<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>

<script>
$(document).ready(function() {
    var selectedInventoryId = null;
    var filterCabangMutasi = 0;

    <?php if ($is_nasional): ?>
        $('#filter-cabang-mutasi').on('change', function() {
            filterCabangMutasi = $(this).val();
        });
    <?php endif; ?>

    // ===== DATEPICKER =====
    $('.datepicker').datepicker({
        dateFormat: 'dd-mm-yy',
        changeMonth: true,
        changeYear: true
    });

    // ===== SELECT2 BARANG =====
    $('#select-barang').select2({
        placeholder: '-- Cari kode / nama barang --',
        allowClear: true,
        minimumInputLength: 0,
        ajax: {
            url: "<?= site_url('mac_mutasi_stok/get_inventory') ?>",
            type: 'POST',
            dataType: 'JSON',
            delay: 300,
            cache: false,
            data: function(params) {
                return {
                     search: params.term || '',
                     filter_cabang: filterCabangMutasi || 0,
                     _ts: new Date().getTime()
                };
            },
            processResults: function(data) {
                return {
                    results: (data || []).map(function(d) {
                        return {
                            id:   d.id,
                            text: d.kode_produk + ' - ' + d.nama_produk
                        };
                    })
                };
            }
        },
        matcher: function() { return true; }
    });

    $('#select-barang').on('select2:select', function(e) {
        selectedInventoryId = e.params.data.id;

        // Ambil rentang tanggal valid untuk barang ini
        $.ajax({
            url: "<?= site_url('mac_mutasi_stok/get_rentang_tanggal') ?>",
            type: 'POST',
            dataType: 'JSON',
            data: { inventory_id: selectedInventoryId },
            success: function(res) {
                if (!res.status) return;

                // Set ulang datepicker tgl_dari dengan minDate & maxDate
                $('#tgl_dari').datepicker('option', {
                    minDate: res.tgl_awal,       // tanggal transaksi/created pertama
                    maxDate: res.tgl_hari_ini,   // hari ini
                }).val(''); // reset nilai sebelumnya

                $('#tgl_sampai').datepicker('option', {
                    minDate: res.tgl_awal,
                    maxDate: res.tgl_hari_ini,
                }).val('');
            }
        });

    }).on('select2:clear', function() {
        selectedInventoryId = null;
        $('#result-wrapper').hide();
        $('#btn-excel').hide();

        // Reset datepicker ke kondisi bebas
        $('#tgl_dari').datepicker('option', { minDate: null, maxDate: null }).val('');
        $('#tgl_sampai').datepicker('option', { minDate: null, maxDate: null }).val('');
    });

    // ===== TAMPILKAN =====
    $('#btn-tampilkan').on('click', function() {
        if (!selectedInventoryId) {
            Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Pilih barang terlebih dahulu.' });
            return;
        }

        var tglDari    = $('#tgl_dari').val();
        var tglSampai  = $('#tgl_sampai').val();

        $.ajax({
            url: "<?= site_url('mac_mutasi_stok/get_mutasi_stok') ?>",
            type: 'POST',
            dataType: 'JSON',
            data: {
                inventory_id: selectedInventoryId,
                tgl_dari:     tglDari,
                tgl_sampai:   tglSampai,
                filter_cabang: filterCabangMutasi
            },
            beforeSend: function() {
                $('#btn-tampilkan').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
            },
            success: function(res) {
                $('#btn-tampilkan').prop('disabled', false).html('<i class="fa fa-search"></i> Cari');
                if (!res.status) {
                    Swal.fire({ icon: 'error', title: 'Error', text: res.error }); return;
                }
                renderHasil(res, tglDari, tglSampai);
            },
            error: function() {
                $('#btn-tampilkan').prop('disabled', false).html('<i class="fa fa-search"></i> Cari');
                Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal mengambil data.' });
            }
        });
    });

    // ===== RENDER HASIL =====
    function renderHasil(res, tglDari, tglSampai) {
        var b = res.barang;

        // Info barang
        $('#info-kode').text(b.kode_produk);
        $('#info-nama').text(b.nama_produk);
        $('#info-kategori').text(b.kategori);
        $('#info-satuan').text(b.satuan);

        var stokSekarang = parseFloat(b.stok_saat_ini) || 0;
        var warna = stokSekarang <= 0 ? 'text-danger' :
                    (stokSekarang <= b.stok_minimal ? 'text-warning' : 'text-success');
        $('#info-stok-sekarang').text(stokSekarang + ' ' + b.satuan)
            .removeClass('text-danger text-warning text-success').addClass(warna);

        // Summary
        $('#sum-awal').text(res.stok_awal);
        $('#sum-masuk').text(res.total_masuk);
        $('#sum-keluar').text(res.total_keluar);
        $('#sum-akhir').text(res.stok_akhir);

        // Label periode
        if (tglDari || tglSampai) {
            $('#periode-label').text('Periode: ' + (tglDari || '-') + ' s/d ' + (tglSampai || '-'));
        } else {
            $('#periode-label').text('Semua periode');
        }

        var thCabang = res.use_cabang_id === null
            ? '<th width="10%">Cabang</th>'
            : '';

        // Update thead
        $('#table-mutasi-stok thead tr').html(
            '<th width="3%">No</th>' +
            '<th width="10%">Tanggal</th>' +
            '<th width="7%">Tipe</th>' +
            thCabang +
            '<th width="22%">Keterangan</th>' +
            '<th width="9%">Dokumen</th>' +
            '<th width="6%">Masuk</th>' +
            '<th width="6%">Keluar</th>' +
            '<th width="6%">Stok</th>' +
            '<th width="8%">Harga Beli</th>' +
            '<th width="8%">Harga Jual</th>' +
            '<th width="10%">Nilai</th>' +
            '<th width="10%">Profit</th>'
        );

        // Tabel transaksi
        var tbody = '';
        var tipeMap = {
            'masuk':              { label: 'Masuk',        class: 'badge-success' },
            'keluar':             { label: 'Keluar',       class: 'badge-danger'  },
            'penyesuaian_masuk':  { label: 'Adj +',        class: 'badge-info'    },
            'penyesuaian_keluar': { label: 'Adj −',        class: 'badge-warning' },
            'stok_awal':          { label: 'Stok Awal',    class: 'badge-secondary'},
        };

        // Baris stok awal periode (jika ada filter tanggal)
        if (tglDari) {
            tbody += '<tr style="background:#f8f9fc;">' +
                '<td colspan="5" class="text-right text-muted"><em>Stok awal per ' + tglDari + '</em></td>' +
                '<td class="text-center">-</td>' +
                '<td class="text-center">-</td>' +
                '<td class="text-center font-weight-bold">' + res.stok_awal + '</td>' +
                '<td class="text-center">-</td>' +
                '<td class="text-center">-</td>' +
            '</tr>';
        }

        if (res.transaksi.length === 0) {
            tbody += '<tr><td colspan="10" class="text-center text-muted py-3">Tidak ada transaksi di periode ini</td></tr>';
        }

        $.each(res.transaksi, function(i, row) {
            console.log(row);
            var tipeKey  = row.tipe.toLowerCase();
            var tipeInfo = tipeMap[tipeKey] || { label: row.tipe, class: 'badge-light' };
            var isMasuk  = (tipeKey === 'masuk' || tipeKey === 'penyesuaian_masuk' || tipeKey === 'stok_awal');

            // Keterangan & dokumen sumber
            var keterangan = buildKeterangan(row);
            var dokumen    = buildDokumen(row);

            // Harga & nilai
            var harga = parseFloat(row.harga_beli_saat_transaksi) || 0;
            var nilai = harga * parseFloat(row.jumlah);

            var tdCabang = res.use_cabang_id === null
                ? '<td class="text-center">' +
                    (row.nama_cabang
                        ? row.nama_cabang.replace(/^MAC\s+/i,'').toLowerCase()
                            .replace(/\b\w/g, function(l){ return l.toUpperCase(); })
                        : '-') +
                '</td>'
                : '';

            tbody += '<tr>' +
                '<td class="text-center" style="vertical-align: middle;">' + (i + 1) + '</td>' +
                '<td class="text-center" style="vertical-align: middle;">' + moment(row.transaksi_date).format('DD-MM-YYYY') + '</td>' +
                '<td class="text-center" style="vertical-align: middle;"><span class="badge ' + tipeInfo.class + '">' + tipeInfo.label + '</span></td>' +
                tdCabang + 
                '<td style="vertical-align: middle;">' + keterangan + '</td>' +
                '<td class="text-center" style="vertical-align: middle;">' + dokumen + '</td>' +
                '<td class="text-center font-weight-bold text-success" style="vertical-align: middle;">' + (isMasuk  ? '+' + row.jumlah : '-') + '</td>' +
                '<td class="text-center font-weight-bold text-danger" style="vertical-align: middle;">'  + (!isMasuk ? '-' + row.jumlah : '-') + '</td>' +
                '<td class="text-center font-weight-bold" style="vertical-align: middle;">' + row.stok + '</td>' +
                '<td class="text-right" style="vertical-align: middle;font-weight:600;">' + (harga > 0 ? 'Rp ' + formatRupiah(harga) : '-') + '</td>' +
                '<td class="text-right" style="vertical-align: middle;font-weight:600;">' + (harga > 0 ? 'Rp ' + formatRupiah(harga) : '-') + '</td>' +
                '<td class="text-right" style="vertical-align: middle;font-weight:600;">' + (nilai > 0 ? 'Rp ' + formatRupiah(nilai) : '-') + '</td>' +
                '<td class="text-right" style="vertical-align: middle;font-weight:600;">' + (nilai > 0 ? 'Rp ' + formatRupiah(nilai) : '-') + '</td>' +
            '</tr>';
        });

        // Footer total
        var colspanTotal = (res.use_cabang_id === null) ? 8 : 7;

        // Nilai total
        var totalNilaiMasuk  = 0;
        var totalNilaiKeluar = 0;
        $.each(res.transaksi, function(i, row) {
            var tipeKey = row.tipe.toLowerCase();
            var harga   = parseFloat(row.harga_beli_saat_transaksi) || 0;
            var nilai   = harga * parseFloat(row.jumlah);
            if (['masuk', 'penyesuaian_masuk', 'stok_awal'].includes(tipeKey)) {
                totalNilaiMasuk += nilai;
            } else {
                totalNilaiKeluar += nilai;
            }
        });
        var nilaiPersediaan = totalNilaiMasuk - totalNilaiKeluar;

        var tfoot = '<tr style="background:#242d4a; color:white; font-weight:bold;">' +
            '<td style="vertical-align: middle;" colspan="' + colspanTotal + '" class="text-right">TOTAL</td>' +
            '<td class="text-center" style="vertical-align: middle;">' + res.total_masuk  + '</td>' +
            '<td class="text-center" style="vertical-align: middle;">' + res.total_keluar + '</td>' +
            '<td class="text-center" style="vertical-align: middle;">' + res.stok_akhir   + '</td>' +
            '<td class="text-center" style="vertical-align: middle;">-</td>' +
            '<td class="text-right" style="vertical-align: middle;">Total Nilai Masuk: Rp ' + formatRupiah(totalNilaiMasuk) + '</td>' +
        '</tr>';

        $('#tbody-mutasi-stok').html(tbody);
        $('#tfoot-mutasi-stok').html(tfoot);
        $('#result-wrapper').show();
        $('#btn-excel').show();
    }

    // ===== HELPER: KETERANGAN =====
    function buildKeterangan(row) {
        var ket = (row.keterangan || '').replace(/\s*#\d+$/, '');
        if (row.nama_pelapor)  ket += '<br><small class="text-muted">User: ' + row.nama_pelapor + '</small>';
        if (row.customer_name) ket += '<br><small class="text-muted">Customer: ' + row.customer_name + '</small>';
        if (row.nopol)         ket += ' <small class="text-muted">(' + row.nopol + ')</small>';
        if (row.kode_batch)    ket += '<br><small class="text-muted">Batch: ' + row.kode_batch + '</small>';
        return ket || '-';
    }

    // ===== HELPER: DOKUMEN SUMBER =====
    function buildDokumen(row) {
        if (row.kode_reimbust) {
            return '<a href="<?= site_url("mac_reimbust/read_form/") ?>' + row.reimbust_id + '" ' +
                'style="font-size:12px;" ' +
                'class="btn btn-sm btn-primary text-white" target="_blank">' +
                row.kode_reimbust +
                '</a>';
        }
        if (row.invoice_number) {
            return '<a href="<?= site_url("mac_invoice/read_form/") ?>' + row.invoice_id + '" ' +
                'class="btn btn-sm btn-primary text-white" ' +
                'style="font-size:12px;" ' +
                'target="_blank">' +
                row.invoice_number +
                '</a>';
        }
        if (row.referensi) {
            return '<span class="badge badge-secondary">' + row.referensi + '</span>';
        }
        return '-';
    }

    // ===== FORMAT RUPIAH =====
    function formatRupiah(angka) {
        return parseInt(angka).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    // ===== RESET =====
    $('#btn-reset').on('click', function() {
        $('#select-barang').val(null).trigger('change');
        $('#tgl_dari').val('');
        $('#tgl_sampai').val('');
        $('#result-wrapper').hide();
        $('#btn-pdf').hide();
        selectedInventoryId = null;
    });

    // ===== EXPORT PDF =====
    $('#btn-excel').on('click', function() {
        if (!selectedInventoryId) return;
        var url = "<?= site_url('mac_mutasi_stok/export_excel') ?>"
            + '?inventory_id=' + selectedInventoryId
            + '&tgl_dari='     + encodeURIComponent($('#tgl_dari').val())
            + '&tgl_sampai='   + encodeURIComponent($('#tgl_sampai').val());
        window.location.href = url; // download langsung, bukan buka tab baru
    });
});
</script>
