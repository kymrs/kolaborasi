<style>
    table #items-container tr td:nth-child(1),
    table #items-container tr td:nth-child(7) {
        text-align: center;
    }

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
    /* error state */
    .select2-container--default.select2-is-invalid .select2-selection--single {
        border-color: #dc3545 !important;
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
                    <a class="btn btn-primary btn-sm" href="<?= base_url('mac_invoice') ?>">
                        <i class="fas fa-chevron-left"></i>&nbsp;Back
                    </a>
                </div>
                <div class="card-body">
                    <form id="form">
                        <!-- Row 1: Master Input Fields -->
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Invoice Number -->
                                <div class="row align-items-center mb-3">
                                    <label class="col-lg-4" for="invoice_number">Kode Invoice</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" id="invoice_number" name="invoice_number" readonly>
                                    </div>
                                </div>

                                <!-- Customer -->
                                <div class="row align-items-center mb-3">
                                    <label class="col-lg-4" for="customer_id">Customer</label>
                                    <div class="col-lg-8">
                                        <div class="input-group">
                                            <select class="form-control select2-customer"
                                                    id="customer_id"
                                                    name="customer_id">
                                                <option value="">-- Pilih Customer --</option>
                                            </select>

                                            <div class="input-group-append">
                                                <a href="<?= site_url('mac_customer') ?>"
                                                class="btn btn-primary"
                                                title="Tambah Customer">
                                                    <i class="fa fa-plus"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- PIC -->
                                <div class="row align-items-center mb-3">
                                    <label class="col-lg-4" for="pic">PIC</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" id="pic" name="pic" placeholder="Person in Charge">
                                    </div>
                                </div>

                                <!-- Uraian -->
                                <div class="row align-items-center mb-3">
                                    <label class="col-lg-4" for="uraian">Uraian</label>
                                    <div class="col-lg-8">
                                        <select class="form-control" id="uraian" name="uraian">
                                            <option value="" hidden>-- Pilih Uraian --</option>
                                            <option value="Service">Service</option>
                                            <option value="Body Repair">Body Repair</option>
                                            <option value="Towing">Towing</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Lampiran -->
                                <div class="row align-items-center mb-3">
                                    <label class="col-lg-4" for="lampiran">Lampiran</label>
                                    <div class="col-lg-8">
                                        <select class="form-control" id="lampiran" name="lampiran">
                                            <option value="" hidden>-- Pilih Lampiran --</option>
                                            <option value="Ya" selected>Ya</option>
                                            <option value="Tidak">Tidak</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Jenis Kendaraan -->
                                <div class="row align-items-center mb-3">
                                    <label class="col-lg-4" for="jenis_kendaraan">Jenis Kendaraan</label>
                                    <div class="col-lg-8">
                                        <select name="jenis_kendaraan" id="jenis_kendaraan" class="form-control">
                                            <option value="" hidden>-- Pilih Jenis Kendaraan --</option>
                                            <option value="Mobil">Mobil</option>
                                            <option value="Motor">Motor</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Nopol -->
                                <div class="row align-items-center mb-3">
                                    <label class="col-lg-4" for="nopol">No. Polisi</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" id="nopol" name="nopol" placeholder="Nomor Polisi Kendaraan">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Tipe Kendaraan -->
                                <div class="row align-items-center mb-3">
                                    <label class="col-lg-4" for="tipe">Tipe Kendaraan</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" id="tipe" name="tipe" placeholder="Tipe Kendaraan">
                                    </div>
                                </div>

                                <!-- KM -->
                                <div class="row align-items-center mb-3">
                                    <label class="col-lg-4" for="km">KM</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" id="km" name="km" placeholder="0" autocomplete="off">
                                    </div>
                                </div>

                                <!-- Lokasi Service -->
                                <div class="row align-items-center mb-3">
                                    <label class="col-lg-4" for="lokasi_service">Lokasi Service</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" id="lokasi_service" name="lokasi_service" placeholder="Lokasi Service">
                                    </div>
                                </div>

                                <!-- Service Date -->
                                <div class="row align-items-center mb-3">
                                    <label class="col-lg-4" for="awal_service">Tgl. Mulai Service</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" id="awal_service" name="awal_service" placeholder="dd-mm-yyyy" autocomplete="off" style="cursor: pointer;">
                                    </div>
                                </div>

                                <!-- Service Date -->
                                <div class="row align-items-center mb-3">
                                    <label class="col-lg-4" for="akhir_service">Tgl. Selesai Service</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" id="akhir_service" name="akhir_service" placeholder="dd-mm-yyyy" autocomplete="off" style="cursor: pointer;">
                                    </div>
                                </div>

                                <!-- Due Date -->
                                <div class="row align-items-center mb-3">
                                    <label class="col-lg-4" for="due_date">Tgl. Jatuh Tempo</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" id="due_date" name="due_date" placeholder="dd-mm-yyyy" autocomplete="off" style="cursor: pointer;">
                                    </div>
                                </div>

                                <!-- Sub Total -->
                                <div class="row align-items-center mb-3">
                                    <label class="col-lg-4" for="sub_total">Sub Total</label>
                                    <div class="col-lg-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="sub_total" name="sub_total" placeholder="0" readonly>
                                            <input type="hidden" id="sub_total_hidden" name="sub_total_value">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="mb-2 d-flex justify-content-between align-items-center">
                            <h5 class="font-weight-bold mb-0">
                                Insentif Mekanik
                            </h5>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label>Kategori Kendaraan</label>
                                <select class="form-control form-control-sm" name="kategori" id="kategori_insentif">
                                    <option value="">-- Pilih --</option>
                                    <option value="Mobil">Mobil</option>
                                    <option value="Motor">Motor</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Level Pekerjaan</label>
                                <select class="form-control form-control-sm" name="level_insentif" id="level_insentif">
                                    <option value="">-- Pilih Level --</option>

                                    <?php for ($i = 0; $i <= 6; $i++): ?>
                                        <option value="<?= $i ?>">Level <?= $i ?></option>
                                    <?php endfor; ?>

                                    <option value="7">Level Khusus</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Pilih Mekanik</label>

                                <div class="d-flex align-items-start">
                                    <select class="form-control form-control-sm select2-mekanik"
                                            name="mekanik_ids[]"
                                            id="select-mekanik"
                                            multiple
                                            style="width: calc(100% - 42px);">
                                    </select>

                                    <a href="<?= site_url('mac_mekanik') ?>"
                                    class="btn btn-primary btn-sm"
                                    style="width: 38px;"
                                    title="Tambah Mekanik">
                                        <i class="fa fa-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Preview insentif -->
                        <div id="preview-insentif" style="display:none;" class="alert alert-info py-2 mb-3">
                            <div class="row text-center">
                                <div class="col-4">
                                    <small class="text-muted">Nominal Total</small>
                                    <div class="font-weight-bold" id="preview-nominal-total">Rp 0</div>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted">Jumlah Mekanik</small>
                                    <div class="font-weight-bold" id="preview-jumlah-mekanik">0</div>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted">Per Mekanik</small>
                                    <div class="font-weight-bold text-success" id="preview-per-mekanik">Rp 0</div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabel level khusus -->
                        <div id="custom-insentif-wrapper" style="display:none;" class="mb-3">
                            <div class="alert alert-warning py-2 mb-2">
                                <!-- <i class="fa fa-exclamation-triangle"></i> -->
                                <strong>Level Khusus</strong>
                                <span class="ml-2 text-muted small">
                                    Nominal maksimal per mekanik:
                                    <strong id="max-per-mekanik-label">Rp 0</strong>
                                </span>
                            </div>
                            <table class="table table-bordered table-sm">
                                <thead style="background:#242d4a; color:white;">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Nama Mekanik</th>
                                        <th width="15%">NPK</th>
                                        <th width="28%">Nominal Custom</th>
                                        <th width="12%" class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-custom-insentif">
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">
                                            Pilih mekanik terlebih dahulu
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <!-- Hidden inputs untuk dikirim ke server -->
                            <div id="hidden-custom-insentif"></div>
                        </div>

                        <!-- Item Details Section -->
                        <hr>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <button type="button" class="btn btn-primary btn-sm" id="btn-add-item">
                                    <i class="fas fa-plus"></i> Add Item
                                </button>
                            </div>
                        </div>

                        <!-- Items Container Table -->
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered" id="items-table">
                                <thead class="table-light" style="background-color: #242d4a;">
                                    <tr style="color: white; text-align: center;">
                                        <td width="4%" style="padding: 10px;">No</td>
                                        <td width="10%" style="padding: 10px;">Tipe Item</td>
                                        <td width="25%" style="padding: 10px;">Item</td>
                                        <td width="10%" style="padding: 10px;">Biaya</td>
                                        <td width="10%" style="padding: 10px;">Diskon</td>
                                        <td width="6%" style="padding: 10px;">Qty</td>
                                        <td width="10%" style="padding: 10px;">Total</td>
                                        <td width="10%" style="padding: 10px;">Action</td>
                                    </tr>
                                </thead>
                                <tbody id="items-container">
                                    <!-- Item rows will be appended here -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Loading indicator -->
                        <div id="loading" style="display: none;">
                            <p>Loading...</p>
                        </div>

                        <!-- Hidden inputs -->
                        <input type="hidden" name="id" id="id" value="<?= $id ?>">
                        <?php if (!empty($aksi)) { ?>
                            <input type="hidden" name="aksi" id="aksi" value="<?= $aksi ?>">
                        <?php } ?>
                        <?php if ($id == 0) { ?>
                            <input type="hidden" name="kode" id="kode" value="">
                        <?php } ?>

                        <!-- Submit button -->
                        <button type="submit" class="btn btn-primary btn-sm aksi"></button>

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

        // ========== GENERATE INVOICE NUMBER ==========
        function generateInvoiceNumber() {
            $.ajax({
                url: "<?php echo site_url('mac_invoice/generate_invoice_number') ?>",
                type: "POST",
                dataType: "JSON",
                success: function(data) {
                    $('#invoice_number').val(data.invoice_number);
                },
                error: function(error) {
                    console.error("Error generating invoice number:", error);
                }
            });
        }

        // ========== SELECT2 CUSTOMER ==========
        $('.select2-customer').select2({
            placeholder: '-- Pilih Customer --',
            allowClear: true,
            ajax: {
                url: "<?php echo site_url('mac_invoice/get_customers') ?>",
                type: "POST",
                dataType: "JSON",
                delay: 300,
                data: function(params) {
                    return {
                        search: params.term || '',
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.map(function(item) {
                            return {
                                id: item.id,
                                text: item.customer_name,
                                address: item.address
                            };
                        })
                    };
                },
                cache: true
            },
            minimumInputLength: 0,
        });

        // ========== KM FORMAT ==========
        $('#km').on('input', function() {
            $(this).val(formatCurrency($(this).val()));
        });

        // $('#jenis_kendaraan').on('change', function() {
        //     var selectedJenis = $(this).val();
        //     if (selectedJenis === 'Motor') {
        //         $('#kategori_insentif').val('Motor');
        //     } else if (selectedJenis === 'Mobil') {
        //         $('#kategori_insentif').val('Mobil');
        //     } else {
        //         $('#kategori_insentif').val('');
        //     }
        // });

        // ========== DATEPICKER INITIALIZATION ==========
        var datePickerOptions = {
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true
        };

        $('#invoice_date').datepicker(datePickerOptions);
        $('#awal_service').datepicker(datePickerOptions);
        $('#akhir_service').datepicker(datePickerOptions);
        $('#due_date').datepicker(datePickerOptions);

        // ========== CURRENCY FORMAT FUNCTION ==========
        function formatCurrency(value) {
            let cleanValue = value.toString().replace(/[^,\d]/g, '');
            let integerPart = cleanValue.replace(/\D/g, '');
            if (integerPart) {
                integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }
            return integerPart;
        }

        // Tampilkan info stok di bawah select item
        function showStokInfo(num, label, warna) {
            var elId = 'stok-info-item-' + num;
            if ($('#' + elId).length === 0) {
                $('#item_' + num).closest('td')
                    .append('<div id="' + elId + '" style="font-size:11px; margin-top:3px; ' +
                            'padding:2px 7px; border-radius:3px; background:#f8f9fc; ' +
                            'border:1px solid #e3e6f0;"></div>');
            }
            $('#' + elId).html('<span style="color:' + warna + '; font-weight:600;">' + label + '</span>').show();

            checkStokStatus(); // TAMBAH INI
        }

        function hideStokInfo(num) {
            $('#stok-info-item-' + num).hide().html('');
        }

        // Update info stok saat qty berubah (tampilkan sisa setelah dikurangi qty input)
        $(document).on('input', '.qty', function() {
            var $input      = $(this);
            var num         = $input.attr('id').replace('qty_', '');
            var qtyInput    = parseFloat($input.val()) || 0;
            var stok        = parseFloat($('#stok_tersedia_' + num).val()) || 0;
            var stokMinimal = parseFloat($('#stok_minimal_' + num).val()) || 0;
            var tipe        = $('#tipe_item_' + num).val();
            var invId       = $('#inv_id_' + num).val();
            var satuan      = $('#satuan_' + num).val() || '';
            var stokable    = ['Sparepart', 'Bahan', 'Pelumas'];

            if (stokable.includes(tipe) && invId) {

                // Jika stok belum ter-load (masih 0), fetch dulu dari server
                if (stok <= 0) {
                    $.ajax({
                        url: "<?= site_url('mac_invoice/get_stok_item') ?>",
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            inventory_id: invId,
                            invoice_id:   $('#id').val() || 0
                        },
                        success: function(res) {
                            stok        = parseFloat(res.stok) || 0;
                            stokMinimal = parseFloat(res.stok_minimal) || 0;
                            satuan      = res.satuan || '';

                            $('#stok_tersedia_' + num).val(stok);
                            $('#satuan_' + num).val(satuan);
                            $('#stok_minimal_' + num).val(stokMinimal);

                            if (stok > 0) {
                                // Paksa qty ke max stok jika melebihi
                                if (qtyInput > stok) {
                                    Swal.fire({
                                        icon:              'warning',
                                        title:             'Stok Tidak Cukup',
                                        text:              $('#item_text_' + num).val() + ' — sisa stok: ' + stok + ' ' + satuan,
                                        confirmButtonText: 'OK'
                                    });
                                    $input.val(stok);
                                }

                                // Hitung sisa dari nilai yang sudah dipaksa
                                var sisaAktual = stok - (parseFloat($input.val()) || 0);
                                var warna      = sisaAktual <= stokMinimal ? '#f6c23e' : '#1cc88a';
                                showStokInfo(num,
                                    'Sisa stok setelah transaksi: <strong>' + sisaAktual + ' ' + satuan + '</strong>',
                                    warna
                                );
                            }

                            calculateRowTotal($input.closest('tr'));
                            calculateMasterTotal();
                        }
                    });
                    return; // tunggu ajax selesai
                }

                // Stok sudah ada, langsung validasi
                // Paksa qty ke max stok jika melebihi
                if (qtyInput > stok) {
                    Swal.fire({
                        icon:              'warning',
                        title:             'Stok Tidak Cukup',
                        text:              $('#item_text_' + num).val() + ' — sisa stok: ' + stok + ' ' + satuan,
                        confirmButtonText: 'OK'
                    });
                    $input.val(stok);
                }

                // Hitung sisa dari nilai yang sudah dipaksa
                var sisaAktual = stok - (parseFloat($input.val()) || 0);
                var warna = sisaAktual <= 0 ? '#e74a3b' : (sisaAktual <= stokMinimal ? '#f6c23e' : '#1cc88a');
                showStokInfo(num,
                    'Sisa stok setelah transaksi: <strong>' + sisaAktual + ' ' + satuan + '</strong>',
                    warna
                );
            }

            calculateRowTotal($input.closest('tr'));
            calculateMasterTotal();
        });

        // Cek semua baris — jika ada 1 saja stok habis, disable tombol save
        function checkStokStatus() {
            var adaStokHabis = false;

            $('#items-container tr').each(function() {
                var num   = $(this).attr('id').replace('item-row-', '');
                var tipe  = $('#tipe_item_' + num).val();
                var invId = $('#inv_id_' + num).val();
                var stok  = parseFloat($('#stok_tersedia_' + num).val()) || 0;
                var stokable = ['Sparepart', 'Bahan', 'Pelumas'];

                if (stokable.includes(tipe) && invId && stok <= 0) {
                    adaStokHabis = true;
                    return false; // break loop
                }
            });

            if (adaStokHabis) {
                $('.aksi').prop('disabled', true)
                    .css('cursor', 'not-allowed')
                    .attr('title', 'Ada barang dengan stok habis');
            } else {
                $('.aksi').prop('disabled', false)
                    .css('cursor', 'pointer')
                    .removeAttr('title');
            }
        }

        // Kumpulkan semua inventory_id yang sudah dipilih di baris lain
        // excludeNum = nomor baris yang sedang diinit (jangan exclude dirinya sendiri)
        function getSelectedInventoryIds(excludeNum) {
            var used = [];
            $('input[id^="inv_id_"]').each(function() {
                var rowNum = $(this).attr('id').replace('inv_id_', '');
                if (rowNum != excludeNum) {
                    var val = $(this).val();
                    if (val) used.push(String(val));
                }
            });
            return used;
        }

        function initItemSelect2(num) {
            var $select = $('#item_' + num);

            if ($select.hasClass('select2-hidden-accessible')) {
                $select.select2('destroy');
            }

            $select.select2({
                width: '100%',
                placeholder: '-- Pilih Item --',
                allowClear: true,
                minimumInputLength: 0,
                ajax: {
                    url: "<?= site_url('mac_invoice/get_inventory_by_kategori') ?>",
                    type: 'POST',
                    dataType: 'JSON',
                    delay: 300,
                    cache: false,
                    data: function(params) {
                        // Baca dari data-attribute — tidak bergantung closure atau DOM traversal
                        var kategori = $select.data('kategori');
                        return {
                            kategori: kategori,
                            search:   params.term !== undefined ? params.term : '',
                            _ts:      new Date().getTime()
                        };
                    },
                    processResults: function(data) {
                        var used = getSelectedInventoryIds(num); // ambil yang sudah dipilih
                        return {
                            results: (data || [])
                                .filter(function(d) {
                                    return !used.includes(String(d.id)); // hilangkan yang sudah dipakai
                                })
                                .map(function(d) {
                                    return {
                                        id:           d.id,
                                        text:         d.kode_produk + ' - ' + d.nama_produk,
                                        nama_produk:  d.nama_produk,
                                        harga_jual:   parseFloat(d.harga_jual) || 0,
                                        stok:         parseFloat(d.stok) || 0,
                                        satuan:       d.satuan || '',
                                        inventory_id: d.id
                                    };
                                })
                        };
                    }
                },
                // PENTING: matikan filter client-side, biar server yang filter
                matcher: function() { return true; }
            });

            $select.off('select2:select select2:clear')
                .on('select2:select', function(e) {
                    var d = e.params.data;

                    // Cek harga jual belum di-set
                    if (!d.harga_jual || parseFloat(d.harga_jual) <= 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Harga Jual Belum Di-set',
                            html: '<b>' + d.nama_produk + '</b><br>' +
                                'Silakan set harga jual terlebih dahulu di Master Barang.',
                            showCancelButton: true,
                            confirmButtonText: 'Ke Master Barang',
                            cancelButtonText: 'OK',
                            confirmButtonColor: '#3085d6'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                const inventoryId = d.id;
                                const cabangId = <?= $this->session->userdata('cabang_id') ?>;
                                const url = '<?= site_url("mac_inventory") ?>' +
                                    '?inventory_id=' + inventoryId +
                                    '&cabang_id=' + cabangId;
                                window.open(url, '_blank');
                            }
                        });
                        // Reset select — batalkan pemilihan
                        $('#item_text_' + num).val('');
                        $('#inv_id_' + num).val('');
                        $('#stok_tersedia_' + num).val(0);
                        $('#stok_minimal_' + num).val(0);
                        $('#satuan_' + num).val('');
                        $('#biaya_' + num).val('');
                        $('#hidden_biaya_' + num).val('');
                        hideStokInfo(num); // sembunyikan info stok
                        calculateRowTotal($select.closest('tr'));
                        calculateMasterTotal();
                        $select.val(null).trigger('change');
                        return;
                    }

                    $('#item_text_' + num).val(d.nama_produk);
                    $('#inv_id_' + num).val(d.inventory_id);
                    $('#satuan_' + num).val(d.satuan);
                    $('#biaya_' + num).val(formatCurrency(String(d.harga_jual)));
                    $('#hidden_biaya_' + num).val(d.harga_jual);

                    $.ajax({
                        url: "<?= site_url('mac_invoice/get_stok_item') ?>",
                        type: 'POST',
                        dataType: 'JSON',
                        data: { inventory_id: d.inventory_id, invoice_id: $('#id').val() || 0 },
                        success: function(res) {
                            var stok   = parseFloat(res.stok) || 0;
                            var satuan = res.satuan || '';
                            $('#stok_tersedia_' + num).val(stok);
                            $('#stok_minimal_' + num).val(res.stok_minimal || 0);
                            $('#satuan_' + num).val(satuan);

                            var warna = stok <= 0 ? '#e74a3b' : (stok <= parseFloat($('#stok_minimal_' + num).val()) ? '#f6c23e' : '#1cc88a');
                            var label = stok <= 0 ? 'Stok habis' : 'Sisa stok: <strong>' + stok + ' ' + satuan + '</strong>';
                            showStokInfo(num, label, warna);
                        }
                    });

                    $('#qty_' + num).val(1);
                    calculateRowTotal($select.closest('tr'));
                    calculateMasterTotal();
                })
                .on('select2:clear', function() {
                    $('#item_text_' + num).val('');
                    $('#inv_id_' + num).val('');
                    $('#stok_tersedia_' + num).val(0);
                    $('#stok_minimal_' + num).val(0);
                    $('#satuan_' + num).val('');
                    $('#biaya_' + num).val('');
                    $('#hidden_biaya_' + num).val('');
                    hideStokInfo(num); // sembunyikan info stok
                    calculateRowTotal($select.closest('tr'));
                    calculateMasterTotal();
                });
        }

        function initJasaSelect2(num) {
            var $select = $('#item_' + num);

            if ($select.hasClass('select2-hidden-accessible')) {
                $select.select2('destroy');
            }

            $select.select2({
                width: '100%',
                placeholder: '-- Pilih Jasa --',
                allowClear: true,
                minimumInputLength: 0,
                ajax: {
                    url: "<?= site_url('mac_invoice/get_jasa') ?>",
                    type: 'POST',
                    dataType: 'JSON',
                    delay: 300,
                    cache: false,
                    data: function(params) {
                        return {
                            search: params.term !== undefined ? params.term : '',
                            _ts:    new Date().getTime()
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: (data || []).map(function(d) {
                                var isExternal = parseFloat(d.harga_beli) > 0;
                                return {
                                    id:          d.id,
                                    text:        d.nama + (d.paket ? ' - ' + d.paket : '')
                                                + (isExternal ? ' (External)' : ''),
                                    nama:        d.nama,
                                    harga_jual:       parseFloat(d.harga_jual) || 0, // ← GANTI harga ke harga_jual
                                    harga_beli:  parseFloat(d.harga_beli) || 0,
                                    satuan:      d.satuan || '',
                                    is_external: isExternal
                                };
                            })
                        };
                    },
                    // Aktifkan render HTML di select2
                    templateResult: function(d) {
                        if (!d.id) return d.text;
                        return $('<span>').html(d.text);
                    },
                    templateSelection: function(d) {
                        if (!d.id) return d.text;
                        // Di selection box, tampilkan tanpa HTML badge
                        var isExternal = d.is_external;
                        return $('<span>').text(
                            d.nama + (d.paket ? ' - ' + d.paket : '')
                            + (isExternal ? ' [External]' : '')
                        );
                    },
                },
                matcher: function() { return true; } // filter diserahkan ke server
            });

            $select.off('select2:select select2:clear')
                .on('select2:select', function(e) {
                    var d = e.params.data;

                    // Cek harga jual belum di-set
                    if (!d.harga_jual || parseFloat(d.harga_jual) <= 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Harga Jual Belum Di-set',
                            html: '<b>' + d.nama + '</b><br>' +
                                'Harga jual jasa ini belum diatur untuk cabang ini.<br>' +
                                'Silakan set harga jual terlebih dahulu di Master Jasa.',
                            confirmButtonText: 'OK'
                        });
                        // Reset select — batalkan pemilihan
                        $select.val(null).trigger('change');
                        $('#item_text_' + num).val('');
                        $('#biaya_' + num).val('');
                        $('#hidden_biaya_' + num).val('');
                        $('#hidden_harga_beli_jasa_' + num).val(0); // TAMBAHAN
                        calculateRowTotal($select.closest('tr'));
                        calculateMasterTotal();
                        return;
                    }

                    $('#item_text_' + num).val(d.nama);
                    $('#inv_id_' + num).val('');
                    $('#stok_tersedia_' + num).val(0);
                    $('#stok_minimal_' + num).val(0);

                    $('#biaya_' + num).val(formatCurrency(String(d.harga_jual)));
                    $('#hidden_biaya_' + num).val(d.harga_jual);
                    $('#hidden_harga_beli_jasa_' + num).val(d.harga_beli);

                    calculateRowTotal($select.closest('tr'));
                    calculateMasterTotal();
                })
                .on('select2:clear', function() {
                    $('#item_text_' + num).val('');
                    $('#biaya_' + num).val('');
                    $('#hidden_biaya_' + num).val('');
                    $('#hidden_harga_beli_jasa_' + num).val(0); // TAMBAHAN
                    calculateRowTotal($select.closest('tr'));
                    calculateMasterTotal();
                });
        }

        // ========== LOAD DATA FOR EDIT ==========
        if (id != 0) {
            $('.aksi').text('Update');

            $.ajax({
                url: "<?php echo site_url('mac_invoice/get_data') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    moment.locale('id');
                    $('#id').val(data.id);
                    $('#invoice_number').val(data.invoice_number);
                    $('#title').val(data.title);
                    $('#pic').val(data.pic);
                    $('#nopol').val(data.nopol);
                    $('#tipe').val(data.tipe);
                    $('#km').val(formatCurrency(String(data.km)));
                    $('#lokasi_service').val(data.lokasi_service);
                    $('#invoice_date').val(moment(data.invoice_date).format('DD-MM-YYYY'));
                    $('#awal_service').val(moment(data.awal_service).format('DD-MM-YYYY'));
                    $('#akhir_service').val(moment(data.akhir_service).format('DD-MM-YYYY'));
                    $('#due_date').val(moment(data.due_date).format('DD-MM-YYYY'));
                    $('#sub_total').val(formatCurrency(data.sub_total));
                    $('#sub_total_hidden').val(data.sub_total);
                    $('#uraian').val(data.uraian);
                    $('#lampiran').val(data.lampiran);

                    // Load data mekanik saat edit
                    // Tambahkan di dalam blok $.ajax success get_data, setelah load items:
                    if (data.kategori)       $('#kategori_insentif').val(data.kategori);
                    if (data.level_insentif !== null) $('#level_insentif').val(data.level_insentif);

                    if (data.mekanik && data.mekanik.length > 0) {
                        $.each(data.mekanik, function(i, m) {
                            var label = m.nama + (m.npk ? ' (' + m.npk + ')' : '');
                            var opt   = new Option(label, m.mekanik_id, true, true);
                            $('#select-mekanik').append(opt);
                        });
                        $('#select-mekanik').trigger('change');
                        updatePreviewInsentif();
                    }

                    if (data.level_insentif == 7 && data.mekanik && data.mekanik.length > 0) {
                        // Tunggu select2 selesai render baru render tabel
                        setTimeout(function() {
                            var kategori   = data.kategori;
                            var maxNominal = maxPerMekanikLevel7[kategori] || 0;
                            renderTabelCustomInsentif(
                                data.mekanik.map(function(m) { return m.mekanik_id; }),
                                maxNominal
                            );
                            // Isi nilai custom dari data existing
                            $.each(data.mekanik, function(i, m) {
                                $('#custom_nominal_mekanik_' + m.mekanik_id)
                                    .val(m.nominal_per_mekanik > 0
                                        ? parseInt(m.nominal_per_mekanik).toLocaleString('id-ID')
                                        : '');
                                $('#hidden_custom_insentif_' + m.mekanik_id)
                                    .val(m.nominal_per_mekanik || 0);
                            });
                            $('#custom-insentif-wrapper').show();
                        }, 300);
                    }

                    if (data.customer_id && data.customer_name) {
                        var option = new Option(data.customer_name, data.customer_id, true, true);
                        $('.select2-customer').append(option).trigger('change');
                    }

                    if (data.items && data.items.length > 0) {
                        $.each(data.items, function(index) {
                            itemCount++;

                            // FIX: capture nilai itemCount saat ini dengan IIFE
                            // supaya tidak tertimpa iterasi berikutnya saat AJAX async selesai
                            (function(currentNum, item) {
                                $('#items-container').append(buildItemRow(currentNum, item));

                                if (item.tipe_item && item.tipe_item !== 'Jasa') {
                                    // Init select2 barang
                                    initItemSelect2(currentNum);

                                    // Inject option terpilih ke select2
                                    var opt = new Option(item.item, item.item, true, true);
                                    $(opt).data('harga_jual', item.biaya);
                                    $(opt).data('inventory_id', item.inventory_id);
                                    $('#item_' + currentNum).append(opt).trigger('change.select2');
                                    $('#inv_id_' + currentNum).val(item.inventory_id);
                                    $('#hidden_biaya_' + currentNum).val(item.biaya);

                                    // Ambil stok real-time
                                    if (item.inventory_id) {
                                        $.ajax({
                                            url: "<?= site_url('mac_invoice/get_stok_item') ?>",
                                            type: 'POST',
                                            dataType: 'JSON',
                                            data: { inventory_id: item.inventory_id, invoice_id: id },
                                            success: function(res) {
                                                var stok   = parseFloat(res.stok) || 0;
                                                var satuan = res.satuan || '';
                                                $('#stok_tersedia_' + currentNum).val(stok);
                                                $('#stok_minimal_' + currentNum).val(res.stok_minimal || 0);
                                                $('#satuan_' + currentNum).val(satuan);

                                                var warna = stok <= 0
                                                    ? '#e74a3b'
                                                    : (stok <= parseFloat($('#stok_minimal_' + currentNum).val()) ? '#f6c23e' : '#1cc88a');
                                                var label = stok <= 0
                                                    ? 'Stok habis'
                                                    : 'Sisa stok: <strong>' + stok + ' ' + satuan + '</strong>';
                                                showStokInfo(currentNum, label, warna);
                                            }
                                        });
                                    }

                                } else if (item.tipe_item === 'Jasa') {
                                    // Init select2 jasa
                                    initJasaSelect2(currentNum);

                                    var optJasa = new Option(item.item, item.item, true, true);
                                    $('#item_' + currentNum).append(optJasa).trigger('change.select2');
                                    $('#hidden_biaya_' + currentNum).val(item.biaya);
                                }

                            })(itemCount, data.items[index]); // IIFE — passing nilai saat ini
                        });

                        calculateMasterTotal();
                    }
                },
                error: function(jqXHR, textStatus) {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Error loading data: ' + textStatus });
                }
            });

        } else {
            generateInvoiceNumber();
            $('.aksi').text('Save');
        }

        // ========== ITEM DETAILS MANAGEMENT ==========
        let itemCount = 0;

        // ========== ITEM ROW TEMPLATE ==========
        function buildItemRow(num, item = {}) {
            return `
                <tr id="item-row-${num}">
                    <td class="item-number">${num}</td>
                    <td>
                        <select class="form-control form-control tipe-item" name="tipe_item[${num}]" id="tipe_item_${num}">
                            <option value="">-- Pilih --</option>
                            <option value="Jasa"      ${(item.tipe_item === 'Jasa')      ? 'selected' : ''}>Jasa</option>
                            <option value="Sparepart" ${(item.tipe_item === 'Sparepart') ? 'selected' : ''}>Sparepart</option>
                            <option value="Bahan"     ${(item.tipe_item === 'Bahan')     ? 'selected' : ''}>Bahan</option>
                            <option value="Pelumas"   ${(item.tipe_item === 'Pelumas')   ? 'selected' : ''}>Pelumas</option>
                        </select>
                    </td>
                    <td>
                        <select class="form-control form-control item-select"
                                id="item_${num}" style="width:100%;">
                            ${item.item ? `<option value="${item.inventory_id || ''}" selected>${item.item}</option>` : ''}
                        </select>
                        <input type="hidden" name="item[${num}]" id="item_text_${num}" value="${item.item || ''}">
                        <input type="hidden" name="inventory_id[${num}]" id="inv_id_${num}" value="${item.inventory_id || ''}">
                        <input type="hidden" id="stok_tersedia_${num}" value="0">
                        <input type="hidden" id="stok_minimal_${num}" value="0">
                        <input type="hidden" id="satuan_${num}" value="">
                        <input type="hidden" name="harga_beli_jasa[${num}]" id="hidden_harga_beli_jasa_${num}" value="${item.harga_beli_jasa || 0}">
                    </td>
                    <td>
                        <input type="text" class="form-control form-control biaya" name="biaya[${num}]" id="biaya_${num}" placeholder="0" value="${item.biaya ? formatCurrency(item.biaya) : ''}" readonly>
                        <input type="hidden" id="hidden_biaya_${num}" name="biaya_clean[${num}]" value="${item.biaya || ''}">
                    </td>
                    <td>
                        <input type="text" class="form-control form-control diskon" name="diskon[${num}]" id="diskon_${num}" placeholder="0" value="${item.diskon ? formatCurrency(item.diskon) : ''}">
                        <input type="hidden" id="hidden_diskon_${num}" name="diskon_clean[${num}]" value="${item.diskon || ''}">
                    </td>
                    <td>
                        <input type="number" class="form-control form-control qty" name="qty[${num}]" id="qty_${num}" placeholder="0" min="0" value="${item.qty || 1}">
                    </td>
                    <td>
                        <input type="text" class="form-control form-control total-price" name="total[${num}]" id="total_${num}" placeholder="0" readonly value="${item.total ? formatCurrency(item.total) : ''}">
                        <input type="hidden" id="hidden_total_${num}" name="total_clean[${num}]" value="${item.total || ''}">
                    </td>
                    <td style="text-align: center">
                        <button type="button" class="btn btn-danger btn btn-remove-item" data-id="${num}" style="background-color: #dc0808; padding: 5px 15px; border: none; border-radius: 4px;">
                            Delete
                        </button>
                    </td>
                </tr>
            `;
        }

        // Add Item Button Click
        $(document).on('click', '#btn-add-item', function() {
            itemCount++;
            $('#items-container').append(buildItemRow(itemCount));
            reorderItemRows();
            calculateMasterTotal();
        });

        // ========== CALCULATE ROW TOTAL ==========
        function calculateRowTotal(row) {
            const biayaId  = row.find('.biaya').attr('id');
            const diskonId = row.find('.diskon').attr('id');
            const biaya    = parseFloat($('#hidden_' + biayaId).val())  || 0;
            const diskon   = parseFloat($('#hidden_' + diskonId).val()) || 0;
            const qty      = parseFloat(row.find('.qty').val())          || 0;

            // Total = (biaya * qty) - diskon
            const total   = (biaya * qty) - diskon;

            const totalId = row.find('.total-price').attr('id');
            row.find('.total-price').val(formatCurrency(total.toString()));
            $('#hidden_' + totalId).val(total.toFixed(0));
        }

        // ========== INPUT HANDLERS ==========
        $(document).on('input', '.biaya', function() {
            let formatted = formatCurrency($(this).val());
            $(this).val(formatted);
            $('#hidden_' + $(this).attr('id')).val(formatted.replace(/\./g, ''));
            calculateRowTotal($(this).closest('tr'));
            calculateMasterTotal();
        });

        $(document).on('input', '.diskon', function() {
            let formatted = formatCurrency($(this).val());
            $(this).val(formatted);
            $('#hidden_' + $(this).attr('id')).val(formatted.replace(/\./g, ''));
            calculateRowTotal($(this).closest('tr'));
            calculateMasterTotal();
        });

        // Saat tipe_item berubah: reset item, aktifkan/nonaktifkan select
        $(document).on('change', '.tipe-item', function() {
            var num  = $(this).attr('id').replace('tipe_item_', '');
            var tipe = $(this).val();

            // Reset semua nilai baris
            $('#biaya_' + num).val('');
            $('#hidden_biaya_' + num).val('');
            $('#stok_tersedia_' + num).val(0);
            $('#stok_minimal_' + num).val(0);
            $('#satuan_' + num).val('');
            $('#inv_id_' + num).val('');
            $('#item_text_' + num).val('');
            calculateRowTotal($('#item_' + num).closest('tr'));
            calculateMasterTotal();

            var tdItem   = $('#item_' + num).closest('td');
            var stokable = ['Sparepart', 'Bahan', 'Pelumas'];

            var htmlInner = `
                <select class="form-control form-control-sm item-select"
                        id="item_${num}"
                        data-kategori="${tipe}"
                        style="width:100%;"></select>
                <input type="hidden" name="item[${num}]"             id="item_text_${num}"            value="">
                <input type="hidden" name="inventory_id[${num}]"     id="inv_id_${num}"               value="">
                <input type="hidden" name="harga_beli_jasa[${num}]"  id="hidden_harga_beli_jasa_${num}" value="0">
                <input type="hidden"                                  id="stok_tersedia_${num}"        value="0">
                <input type="hidden"                                  id="stok_minimal_${num}"         value="0">
                <input type="hidden"                                  id="satuan_${num}"               value="">
            `;

            tdItem.html(htmlInner);

            if (tipe === 'Jasa') {
                initJasaSelect2(num);
            } else if (stokable.includes(tipe)) {
                initItemSelect2(num);
            }
        });

        // ========== CALCULATE MASTER SUBTOTAL ==========
        function calculateMasterTotal() {
            let masterTotal = 0;
            $('#items-container tr').each(function() {
                const totalId = $(this).find('.total-price').attr('id');
                masterTotal += parseFloat($('#hidden_' + totalId).val()) || 0;
            });
            $('#sub_total').val(formatCurrency(masterTotal.toString()));
            $('#sub_total_hidden').val(masterTotal.toFixed(0));
        }

        // ========== REMOVE ITEM ==========
        $(document).on('click', '.btn-remove-item', function() {
            var rowId = $(this).data('id');
            $('#item-row-' + rowId).remove();
            reorderItemRows();
            calculateMasterTotal();
            checkStokStatus(); // TAMBAH INI
        });

        // ========== REORDER ITEM ROWS ==========
        function reorderItemRows() {
            $('#items-container tr').each(function(index) {
                const num       = index + 1;
                const tipeVal   = $(this).find('.tipe-item').val();
                const itemVal   = $(this).find('.item-desc').val();
                const biayaVal  = $(this).find('.biaya').val();
                const diskonVal = $(this).find('.diskon').val();
                const qtyVal    = $(this).find('.qty').val();
                const totalVal  = $(this).find('.total-price').val();

                $(this).attr('id', `item-row-${num}`);
                $(this).find('.item-number').text(num);

                $(this).find('.tipe-item').attr('name', `tipe_item[${num}]`).attr('id', `tipe_item_${num}`).val(tipeVal);
                $(this).find('.item-desc').attr('name', `item[${num}]`).attr('id', `item_${num}`).val(itemVal);

                $(this).find('.biaya').attr('name', `biaya[${num}]`).attr('id', `biaya_${num}`).val(biayaVal);
                $(this).find('input[id^="hidden_biaya"]').attr('id', `hidden_biaya_${num}`).attr('name', `biaya_clean[${num}]`).val(biayaVal ? biayaVal.replace(/\./g, '') : '');

                $(this).find('.diskon').attr('name', `diskon[${num}]`).attr('id', `diskon_${num}`).val(diskonVal);
                $(this).find('input[id^="hidden_diskon"]').attr('id', `hidden_diskon_${num}`).attr('name', `diskon_clean[${num}]`).val(diskonVal ? diskonVal.replace(/\./g, '') : '');

                $(this).find('.qty').attr('name', `qty[${num}]`).attr('id', `qty_${num}`).val(qtyVal);

                $(this).find('input[id^="inv_id_"]').attr('id', `inv_id_${num}`).attr('name', `inventory_id[${num}]`);
                $(this).find('input[id^="stok_tersedia_"]').attr('id', `stok_tersedia_${num}`);
                $(this).find('input[id^="stok_minimal_"]').attr('id', `stok_minimal_${num}`);

                $(this).find('.total-price').attr('name', `total[${num}]`).attr('id', `total_${num}`).val(totalVal);
                $(this).find('input[id^="hidden_total"]').attr('id', `hidden_total_${num}`).attr('name', `total_clean[${num}]`).val(totalVal ? totalVal.replace(/\./g, '') : '');

                $(this).find('input[id^="item_text_"]').attr('id', `item_text_${num}`).attr('name', `item[${num}]`);
                $(this).find('input[id^="satuan_"]').attr('id', `satuan_${num}`);
                // select tidak pakai name, cukup id:
                $(this).find('.item-select').attr('id', `item_${num}`);

                $(this).find('.btn-remove-item').attr('data-id', num);
            });
            itemCount = $('#items-container tr').length;
        }

        // ===== INSENTIF MEKANIK =====

        // Select2 mekanik (multiple)
        $('#select-mekanik').select2({
            width: '100%',
            placeholder: '-- Cari nama / NPK mekanik --',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: "<?= site_url('mac_invoice/get_mekanik') ?>",
                type: 'POST',
                dataType: 'JSON',
                delay: 300,
                cache: false,
                data: function(params) {
                    return { search: params.term || '', _ts: new Date().getTime() };
                },
                processResults: function(data) {
                    return {
                        results: (data || []).map(function(d) {
                            return {
                                id:   d.id,
                                // text: d.nama + (d.npk ? ' (' + d.npk + ')' : '') + (d.cabang ? ' - ' + d.cabang : '')
                                text: d.nama + (d.npk ? ' (' + d.npk + ')' : '')
                            };
                        })
                    };
                }
            },
            matcher: function() { return true; }
        });

        // ===== VARIABEL GLOBAL INSENTIF =====
        var maxPerMekanikLevel7 = { Mobil: 0, Motor: 0 };

        // ===== LOAD NOMINAL LEVEL 7 SAAT HALAMAN SIAP =====
        $.ajax({
            url: "<?= site_url('mac_invoice/get_nominal_level7') ?>",
            type: 'POST',
            dataType: 'JSON',
            success: function(res) {
                if (res.status) {
                    maxPerMekanikLevel7 = res.data;
                }
            }
        });

        // Fungsi preview insentif
        function updatePreviewInsentif() {
            var kategori = $('#kategori_insentif').val();
            var level    = $('#level_insentif').val();
            var mekanik  = $('#select-mekanik').val() || [];

            // Sembunyikan semua dulu
            $('#preview-insentif').hide();
            $('#custom-insentif-wrapper').hide();

            if (!kategori || level === '' || mekanik.length === 0) return;

            if (level == 7) {
                // Level 7 — tampilkan tabel custom
                var maxNominal = maxPerMekanikLevel7[kategori] || 0;
                $('#max-per-mekanik-label').text(
                    'Rp ' + parseInt(maxNominal).toLocaleString('id-ID')
                );
                renderTabelCustomInsentif(mekanik, maxNominal);
                $('#custom-insentif-wrapper').show();
            } else {
                // Level 0-6 — tampilkan preview biasa
                $.ajax({
                    url: "<?= site_url('mac_invoice/get_preview_insentif') ?>",
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        kategori:       kategori,
                        level:          level,
                        jumlah_mekanik: mekanik.length
                    },
                    success: function(res) {
                        if (!res.status) return;
                        var fmt = function(n) {
                            return 'Rp ' + parseInt(n).toLocaleString('id-ID');
                        };
                        $('#preview-nominal-total').text(fmt(res.nominal_total));
                        $('#preview-jumlah-mekanik').text(mekanik.length + ' orang');
                        $('#preview-per-mekanik').text(fmt(res.nominal_per_mekanik));
                        $('#preview-insentif').show();
                    }
                });
            }
        }

        // ===== RENDER TABEL CUSTOM LEVEL 7 =====
        function renderTabelCustomInsentif(mekanikIds, maxNominal) {
            var $tbody  = $('#tbody-custom-insentif');
            var $hidden = $('#hidden-custom-insentif');

            if (!mekanikIds || mekanikIds.length === 0) {
                $tbody.html('<tr><td colspan="5" class="text-center text-muted">Pilih mekanik terlebih dahulu</td></tr>');
                $hidden.html('');
                return;
            }

            // Ambil data mekanik yang sudah dipilih dari select2
            var selectedData = [];
            $('#select-mekanik option:selected').each(function() {
                var $opt = $(this);
                selectedData.push({
                    id:   $opt.val(),
                    text: $opt.text()
                });
            });

            var rows   = '';
            var hiddens = '';

            $.each(selectedData, function(i, m) {
                // Ambil nilai existing jika sudah pernah diisi
                var existingVal = $('#custom_nominal_mekanik_' + m.id).val() || '';
                var npk = m.text.match(/\(([^)]+)\)/) ? m.text.match(/\(([^)]+)\)/)[1] : '—';
                var nama = m.text.split(' (')[0];

                rows += '<tr>' +
                    '<td class="text-center">' + (i + 1) + '</td>' +
                    '<td>' + nama + '</td>' +
                    '<td class="text-center">' + npk + '</td>' +
                    '<td>' +
                        '<div class="input-group input-group-sm">' +
                            '<div class="input-group-prepend">' +
                                '<span class="input-group-text">Rp</span>' +
                            '</div>' +
                            '<input type="text" class="form-control input-custom-insentif"' +
                            ' id="custom_nominal_mekanik_' + m.id + '"' +
                            ' data-mekanik-id="' + m.id + '"' +
                            ' data-max="' + maxNominal + '"' +
                            ' placeholder="0"' +
                            ' value="' + existingVal + '">' +
                        '</div>' +
                    '</td>' +
                    '<td class="text-center" id="status-custom-' + m.id + '">—</td>' +
                '</tr>';

                hiddens += '<input type="hidden"' +
                    ' name="custom_insentif[' + m.id + ']"' +
                    ' id="hidden_custom_insentif_' + m.id + '"' +
                    ' value="' + (existingVal ? existingVal.replace(/\./g, '') : '0') + '">';
            });

            $tbody.html(rows);
            $hidden.html(hiddens);
        }

        $(document).on('input', '.input-custom-insentif', function() {
            var $input     = $(this);
            var mekanikId  = $input.data('mekanik-id');
            var maxNominal = parseFloat($input.data('max')) || 0;
            var raw        = $input.val().replace(/[^0-9]/g, '');
            var nilai      = parseInt(raw) || 0;

            // Format tampilan
            $input.val(nilai > 0 ? nilai.toLocaleString('id-ID') : '');

            // Validasi max
            var $status = $('#status-custom-' + mekanikId);
            var $hidden = $('#hidden_custom_insentif_' + mekanikId);

            if (maxNominal > 0 && nilai > maxNominal) {
                $status.html('<span class="badge badge-danger">Melebihi batas</span>');
                $input.addClass('is-invalid');
                // Paksa ke max
                $input.val(maxNominal.toLocaleString('id-ID'));
                $hidden.val(maxNominal);
            } else if (nilai > 0) {
                $status.html('<span class="badge badge-success">OK</span>');
                $input.removeClass('is-invalid');
                $hidden.val(nilai);
            } else {
                $status.html('—');
                $input.removeClass('is-invalid');
                $hidden.val(0);
            }
        });

        // Trigger preview saat ada perubahan
        $('#kategori_insentif, #level_insentif').on('change', updatePreviewInsentif);
        $('#select-mekanik').on('change', function() {
            updatePreviewInsentif();
            // Jika level 7, re-render tabel saat mekanik berubah
            if ($('#level_insentif').val() == 7) {
                var kategori   = $('#kategori_insentif').val();
                var maxNominal = maxPerMekanikLevel7[kategori] || 0;
                renderTabelCustomInsentif($('#select-mekanik').val(), maxNominal);
            }
        });

        // ========== FORM SUBMIT ==========
        $("#form").submit(function(e) {
            e.preventDefault();
            var $form = $(this);
            if (!$form.valid()) return false;

            var url = (id == 0)
                ? "<?php echo site_url('mac_invoice/add') ?>"
                : "<?php echo site_url('mac_invoice/update') ?>";

            var formDataObj = new FormData($form[0]);
            formDataObj.set('sub_total', $('#sub_total_hidden').val() || 0);

            $('#loading').show();
            $('.aksi').prop('disabled', true);

            $.ajax({
                url: url,
                type: "POST",
                data: formDataObj,
                dataType: "JSON",
                processData: false,
                contentType: false,
                cache: false,
                success: function(data) {
                    $('#loading').hide();
                    $('.aksi').prop('disabled', false);
                    if (data.status) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: data.message || 'Data saved successfully',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(function() {
                            location.href = "<?= base_url('mac_invoice') ?>";
                        });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Oops...', text: data.error || 'Terjadi kesalahan saat menyimpan data' });
                    }
                },
                error: function(jqXHR, textStatus) {
                    $('#loading').hide();
                    $('.aksi').prop('disabled', false);
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Error: ' + textStatus });
                }
            });
        });

        // ========== FORM VALIDATION ==========
        $("#form").validate({
            rules: {
                customer_id:    { required: true },
                invoice_number: { required: true },
                pic:            { required: true },
                lampiran:       { required: true },
                nopol:          { required: true },
                tipe:           { required: true },
                km:             { required: true, min: 0 },
                lokasi_service: { required: true },
                invoice_date:   { required: true },
                awal_service:   { required: true },
                akhir_service:  { required: true },
                due_date:       { required: true },
            },
            messages: {
                customer_id:    { required: "Customer wajib dipilih" },
                invoice_number: { required: "Kode invoice wajib diisi" },
                pic:            { required: "PIC wajib diisi" },
                lampiran:       { required: "Lampiran wajib dipilih" },
                nopol:          { required: "No. Polisi wajib diisi" },
                tipe:           { required: "Tipe kendaraan wajib diisi" },
                km:             { required: "KM wajib diisi", min: "KM tidak boleh negatif" },
                lokasi_service: { required: "Lokasi service wajib diisi" },
                invoice_date:   { required: "Invoice date wajib diisi" },
                awal_service:   { required: "Service date wajib diisi" },
                akhir_service:  { required: "Service date wajib diisi" },
                due_date:       { required: "Due date wajib diisi" },
            },
            errorPlacement: function(error, element) {
                var row = element.closest('.row');
                error.appendTo(row);
            },
            highlight: function(element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid');
            },
            focusInvalid: false,
        });
    });
</script>