<style>
    .btn-special {
        transform: translateY(-6px);
        /* background: hsl(134deg 61% 41%); */
        border: none;
        border-radius: 12px;
        padding: 0;
        cursor: pointer;
    }

    .front {
        will-change: transform;
        transition: transform 250ms;
        display: block;
        padding: 8px 20px;
        border-radius: 12px;
        font-size: 12px;
        /* background-color: green; */
        color: white;
        transform: translateY(-2px);
    }

    .front-add {
        background-color: #242d4a;
    }

    .front-aksi {
        background-color: #242d4a;
    }


    .btn-special:focus:not(:focus-visible) {
        outline: none;
    }

    .btn-special:active .front {
        transform: translateY(-1px);
    }

    #rekening {
        width: 100%;
    }

    .rekening-text {
        margin-bottom: -2px;
    }

    /* Mengubah gaya dropdown */
    .select2-container--default .select2-selection--single {
        background-color: #fff;
        border: 1px solid #D1D3E2;
        border-radius: 4px;
        font-size: 14px;
        padding: 5px;
        height: 38px;
        line-height: 38px;
    }

    /* Mengubah warna teks dalam opsi dropdown */
    .select2-container--default .select2-results__option {
        color: #777;
    }

    /* Mengubah posisi ikon panah */
    .select2-selection__arrow {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(20%);
    }

    /* Info stok di bawah select2 rincian */
    .stok-info-box {
        font-size: 11px;
        margin-top: 4px;
        padding: 4px 8px;
        border-radius: 4px;
        background: #f8f9fc;
        border: 1px solid #e3e6f0;
        display: none; /* tampil hanya saat item dipilih */
    }
    .stok-info-box .stok-ok    { color: #1cc88a; font-weight: 600; }
    .stok-info-box .stok-warn  { color: #f6c23e; font-weight: 600; }
    .stok-info-box .stok-empty { color: #e74a3b; font-weight: 600; }

    @media (min-width: 768px) {

        .tujuan-field,
        .prepayment-field {
            margin-left: 15px;
        }
    }

    @media (max-width: 768px) {

        .table-transaksi {
            overflow-x: scroll;
        }
    }
</style>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title_view ?></h1>
    </div>
    <!-- <div id="info-kas-kecil" class="alert alert-info py-2 mt-2"
        style="display:none;">
        <i class="fa fa-info-circle"></i>
        Dana kas yang disetujui akan otomatis ditambahkan ke saldo kas cabang ini.
        Jika ada sisa kas sebelumnya, akan otomatis digabungkan.
    </div> -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header text-right">
                    <a class="btn btn-primary btn-sm" href="<?= base_url('mac_prepayment') ?>"><i class="fas fa-chevron-left"></i>&nbsp;Back</a>
                </div>
                <div class="card-body">
                    <form id="form">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Tanggal Prepayment</label>
                                    <div class="col-sm-8">
                                        <div class="input-group date">
                                            <input type="text" class="form-control" name="tgl_prepayment" id="tgl_prepayment" placeholder="DD-MM-YYYY" autocomplete="off" style="cursor: pointer;">
                                            <div class="input-group-append">
                                                <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Kode Prepayment</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="kode_prepayment" name="kode_prepayment" placeholder="Kode Prepayment" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">No Rekening</label>
                                    <div class="col-sm-8">
                                        <div class="input-group mb-3">
                                            <div class="form-check form-check-inline" style="margin-bottom: 5px;">
                                                <?php if ($id_pembuat != $id_user && !empty($aksi)) { ?>
                                                    <input class="form-check-input" type="radio" name="radioNoLabel" id="new" value="" aria-label="..." checked><label for="new" style="margin-top: 8px; cursor: pointer">Rekening</label>
                                                <?php } else { ?>
                                                    <input class="form-check-input" type="radio" name="radioNoLabel" id="exist" value="" aria-label="..." checked><label for="exist" style="margin-right: 14px; margin-top: 8px; cursor: pointer">Rekening terdaftar</label>
                                                    <input class="form-check-input" type="radio" name="radioNoLabel" id="new" value="" aria-label="..."><label for="new" style="margin-top: 8px; cursor: pointer">Rekening baru</label>
                                                <?php } ?>
                                            </div>
                                            <select class="js-example-basic-single" id="rekening" name="rekening">
                                                <option value="Pilih rekening tujuan" selected disabled>Pilih rekening tujuan</option>
                                                <?php foreach ($rek_options as $option) { ?>
                                                    <option value="<?= $option['no_rek'] ?>"><?= $option['no_rek'] ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="input-group rekening-text">
                                                <input type="text" class="form-control col-sm-4" style="font-size: 13px;" id="nama_rek" name="nama_rek" placeholder="Nama Pengaju">&nbsp;
                                                <span class="py-2">-</span>&nbsp;
                                                <input type="text" class="form-control col-sm-3" style="font-size: 13px;" id="nama_bank" name="nama_bank" placeholder="Nama Bank">&nbsp;
                                                <span class="py-2">-</span>&nbsp;
                                                <input type="text" class="form-control col-sm-7" style="font-size: 13px;" id="nomor_rekening" name="nomor_rekening" placeholder="No Rekening">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- SEBELAH KANAN -->
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label prepayment-field">Jenis Pengajuan</label>
                                    <div class="col-sm-7 mt-2">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input"
                                                id="toggle-is-kas" name="is_kas" value="1"
                                                <?= (isset($prepayment) && $prepayment->is_kas) ? 'checked' : '' ?>>
                                            <label class="custom-control-label" for="toggle-is-kas">
                                                <strong>Pengajuan Kas</strong>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label prepayment-field">Tujuan Prepayment</label>
                                    <div class="col-sm-7">
                                        <textarea class="form-control" id="prepayment" name="prepayment" rows="2" placeholder="Tujuan Prepayment"></textarea>
                                    </div>
                                </div>
                                <!-- <div class="form-group row">
                                    <label class="col-sm-4 col-form-label tujuan-field">Tujuan</label>
                                    <div class="col-sm-7">
                                        <textarea class="form-control" id="tujuan" name="tujuan" rows="2"></textarea>
                                    </div>
                                </div> -->
                                <?php if(!$is_nasional) : ?>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label tujuan-field">Mode Inputan</label>
                                        <div class="col-sm-7">
                                            <div class="btn-group btn-group" role="group">
                                                <button type="button" class="btn btn-primary active"
                                                        id="btn-mode-bebas" data-mode="bebas">
                                                    <i class="fa fa-pencil-alt"></i> Teks Bebas
                                                </button>
                                                    <button type="button" class="btn btn-outline-success"
                                                            id="btn-mode-barang" data-mode="barang">
                                                        <i class="fa fa-box"></i> Pilih Barang
                                                    </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif ?>
                            </div>
                        </div>
                        <!-- Tambahkan setelah field tujuan / sebelum tabel rincian -->
                        <!-- <div class="form-group row align-items-center">
                            <label class="col-sm-3 col-form-label font-weight-bold">
                                Jenis Pengajuan
                            </label>
                            <div class="col-sm-9">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input"
                                        id="toggle-is-kas" name="is_kas" value="1"
                                        <?= (isset($prepayment) && $prepayment->is_kas) ? 'checked' : '' ?>>
                                    <label class="custom-control-label" for="toggle-is-kas">
                                        <strong>Pengajuan Kas Kecil</strong>
                                        <small class="text-muted ml-2">
                                            Centang jika ini pengajuan dana kas yang bisa dilaporkan beberapa kali
                                        </small>
                                    </label>
                                </div>
                            </div>
                        </div> -->
                        <!-- BUTTON TAMBAH FORM -->
                        <div class="mt-3">
                            <button type="button" class="btn-special btn-primary btn-sm" id="add-row"><span class="front front-add"><i class="fa fa-plus" aria-hidden="true"></i> Add</span></button>
                        </div>
                        <!-- TABLE INPUT -->
                        <div class="mt-4 mb-3" style="overflow-x: scroll;">
                            <table class="table table-bordered table-hover">
                                <thead style="background-color: #242d4a; color: white;">
                                    <tr>
                                        <th scope="col" class="text-center" width="5%">No</th>
                                        <th scope="col" class="text-center" width="30%">Rincian / Barang</th>
                                        <th scope="col" class="text-center" width="15%">Harga Satuan</th>
                                        <th scope="col" class="text-center" width="10%">Qty</th>
                                        <th scope="col" class="text-center" width="15%">Keterangan</th>
                                        <th scope="col" class="text-center" width="10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="input-container">
                                    <!-- CONTAINER INPUTAN -->
                                </tbody>
                                <tr class="font-weight-bold">
                                    <td colspan="5" id="total_nominal_row" class="text-right">Total</td>
                                    <td id="total_nominal_view"></td>
                                    <input type="hidden" id="total_nominal" name="total_nominal" value="">
                                </tr>
                            </table>
                        </div>
                        <!-- Loading indicator -->
                        <div id="loading" style="display: none;">
                            <p>Loading...</p>
                        </div>

                        <!-- PENENTUAN UPDATE ATAU ADD -->
                        <input type="hidden" name="id" id="id" value="<?= $id ?>">
                        <?php if (!empty($aksi)) { ?>
                            <input type="hidden" name="aksi" id="aksi" value="<?= $aksi ?>">
                        <?php } ?>
                        <?php if ($id == 0) { ?>
                            <input type="hidden" name="kode" id="kode" value="">
                            <button type="submit" class="btn-special btn-sm aksi" style="background-color: #1f558f;" disabled></button>
                        <?php } else { ?>
                            <button type="submit" class="btn-special btn-sm aksi" style="background-color: #1f558f;"></button>
                        <?php } ?>
                        <!-- END PENENTUAN UPDATE ATAU ADD -->
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>

<script>
    $('#tgl_prepayment').datepicker({
        dateFormat: 'dd-mm-yy',
        // minDate: new Date(),
        // maxDate: new Date(),

        // MENGENERATE KODE PREPAYMENT SETELAH PILIH TANGGAL
        onSelect: function(dateText) {
            var id = dateText;
            $('#tgl_prepayment').removeClass("is-invalid");

            // Menghapus label error secara manual jika ada
            if ($("#tgl_prepayment-error").length) {
                $("#tgl_prepayment-error").remove(); // Menghapus label error
            }
            $.ajax({
                url: "<?php echo site_url('mac_prepayment/generate_kode') ?>",
                type: "POST",
                data: {
                    "date": dateText
                },
                dataType: "JSON",
                success: function(data) {
                    // console.log(data);
                    $('#kode_prepayment').val(data.toUpperCase());
                    $('#kode').val(data);
                },
                error: function(error) {
                    alert("error" + error);
                }
            });
        }
    });

    $(document).ready(function() {
        // Toggle info kas kecil
        $('#toggle-is-kas').on('change', function() {
            if ($(this).is(':checked')) {
                $('#prepayment').val('Pengisian Kas');
                $('#btn-mode-barang').hide();
            } else {
                $('#prepayment').val('');
                $('#btn-mode-barang').show();
            }
        });

        // Inisiasi saat edit — jika is_kas sudah 1
        <?php if (isset($prepayment) && $prepayment->is_kas): ?>
        $('#toggle-is-kas').prop('checked', true);
        $('#info-kas-kecil').show();
        <?php endif; ?>

        // PIC kas per cabang — dari list yang sudah ditentukan
        var picKas = {
            2: 'indah', 3: 'titik', 4: 'sri',   5: 'pitri',
            6: 'andro', 7: 'agung', 8: 'fatkhur', 9: 'anton',
            10: 'hermawanta', 11: 'eko', 12: 'saryanto'
        };

        var sessionCabang   = <?= intval($this->session->userdata('cabang_id')) ?>;
        var sessionUsername = "<?= $this->session->userdata('username') ?>";
        var isPic           = picKas[sessionCabang] === sessionUsername;

        // Jika bukan PIC kas, sembunyikan toggle
        if (!isPic) {
            $('#toggle-is-kas').closest('.form-group').hide();
        }

        // INISIASI VARIABEL JAVASCRIPT/JQUERY
        var id = $('#id').val();
        var aksi = $('#aksi').val();
        var kode = $('#kode').val();
        let inputCount = 0;
        let deletedRows = [];

        // ===== STATE MODE INPUTAN =====
        var currentMode = 'bebas';

        // Handler toggle mode
        $(document).on('click', '#btn-mode-bebas-pp, #btn-mode-barang-pp', function() {
            var newMode = $(this).data('mode');
            if (newMode === currentMode) return;

            // Cek apakah ada baris terisi
            var adaTerisi = false;
            $('#input-container tr').each(function() {
                var rincian = $(this).find('input[name^="rincian"]').val();
                var invId   = $(this).find('input[id^="inv-id-"]').val();
                if (rincian || invId) { adaTerisi = true; return false; }
            });

            if (adaTerisi) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Ubah Mode?',
                    text: 'Semua baris detail akan direset.',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, ubah',
                    cancelButtonText: 'Batal'
                }).then(function(result) {
                    if (result.isConfirmed) applyModePP(newMode);
                });
            } else {
                applyModePP(newMode);
            }
        });

        function applyModePP(newMode) {
            currentMode = newMode;
            $('#input_mode_pp').val(newMode);

            if (newMode === 'barang') {
                $('#btn-mode-bebas-pp').removeClass('btn-primary active').addClass('btn-outline-primary');
                $('#btn-mode-barang-pp').removeClass('btn-outline-success').addClass('btn-success active');
            } else {
                $('#btn-mode-barang-pp').removeClass('btn-success active').addClass('btn-outline-success');
                $('#btn-mode-bebas-pp').removeClass('btn-outline-primary').addClass('btn-primary active');
            }

            // Reset semua baris
            $('#input-container').empty();
            rowCount = 0;
            updateSubmitButtonState();
            calculateTotalNominal();
        }

        $('.js-example-basic-single').select2();

        // Fungsi untuk mengatur enabled/disabled elemen berdasarkan radio button yang dipilih
        function toggleInputs() {
            const isExistChecked = $('#exist').is(':checked');

            // Atur visibility dropdown dan input fields
            if (isExistChecked) {
                $('#rekening').prop('disabled', false).show(); // Aktifkan dan tampilkan elemen asli
                $('#rekening').next('.select2-container').show(); // Tampilkan elemen Select2
                $('.input-group.rekening-text input[type="text"]').prop('disabled', true).parent().hide(); // Sembunyikan input fields
            } else {
                $('#rekening').prop('disabled', true).hide(); // Nonaktifkan dan sembunyikan elemen asli
                $('#rekening').next('.select2-container').hide(); // Sembunyikan elemen Select2
                $('.input-group.rekening-text input[type="text"]').prop('disabled', false).parent().show(); // Tampilkan input fields
            }
        }

        // Panggil fungsi saat halaman dimuat
        toggleInputs();

        // Panggil fungsi saat radio button berubah
        $('input[name="radioNoLabel"]').change(toggleInputs);

        // Tambahkan fungsi untuk memformat input nominal memiliki titik
        function formatJumlahInput(selector) {
            $(document).on('input', selector, function() {
                let value = $(this).val().replace(/[^,\d]/g, '');
                let parts = value.split(',');
                let integerPart = parts[0];

                // Format tampilan dengan pemisah ribuan
                integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

                // Set nilai yang diformat ke tampilan
                $(this).val(parts[1] !== undefined ? integerPart + ',' + parts[1] : integerPart);

                // Hapus semua pemisah ribuan untuk pengiriman ke server
                let cleanValue = value.replace(/\./g, '');

                // Pastikan elemen hidden dengan ID yang benar diperbarui
                const hiddenId = `#hidden_${$(this).attr('id').replace('nominal-', 'nominal')}`;
                $(hiddenId).val(cleanValue);

                // Hitung total nominal setelah nilai berubah
                calculateTotalNominal();
            });
        }

        $(document).on('input', 'input[name^="qty"]', function() {
            calculateTotalNominal();
        });

        // function calculateTotalNominal() {
        //     let total = 0;
        //     $('input[name^="hidden_nominal"]').each(function() {
        //         var nominalHidden = parseInt($(this).val()) || 0;

        //         // Ambil qty dari baris yang sama
        //         var rowId = $(this).attr('id').replace('hidden_nominal', '');
        //         var qty   = parseInt($('#qty-' + rowId).val()) || 1;

        //         total += nominalHidden * qty;
        //     });
        //     $('#total_nominal_view').text(total.toLocaleString());
        //     $('#total_nominal').val(total);
        // }

        function calculateTotalNominal() {
            var $rows = $('input[name^="hidden_nominal"]');
            if ($rows.length === 0) {
                return;
            }
            let total = 0;
            $rows.each(function() {
                var nominalHidden = parseInt($(this).val()) || 0;
                var rowId = $(this).attr('id').replace('hidden_nominal', '');
                var qty = parseInt($('#qty-' + rowId).val()) || 1;
                total += nominalHidden * qty;
            });
            $('#total_nominal_view').text(total.toLocaleString());
            $('#total_nominal').val(total);
        }

        // ===== HANDLER TOGGLE MODE =====
        var currentMode = 'bebas';

        $(document).on('click', '#btn-mode-bebas, #btn-mode-barang', function() {
            var newMode = $(this).data('mode');

            // Jika mode sama, tidak perlu proses
            if (newMode === currentMode) return;

            // Cek apakah ada baris yang sudah terisi
            var inputTerisi = false;
            if (newMode === 'bebas') {
                // Cek apakah ada baris dengan inventory_id terisi
                $('#input-container tr').each(function() {
                    if ($(this).find('[id^="inventory_id"]').val()) {
                        inputTerisi = true;
                        return false; // break
                    }
                });
            }

            if (inputTerisi) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Ubah Mode?',
                    text: 'Semua baris detail yang sudah terisi akan dihapus.',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, ubah',
                    cancelButtonText: 'Batal'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        applyModeChange(newMode);
                    }
                });
            } else {
                applyModeChange(newMode);
            }
        });

        function applyModeChange(newMode) {
            currentMode = newMode;
            $('#input_mode').val(newMode);

            // Update tampilan tombol
            if (newMode === 'barang') {
                $('#btn-mode-bebas').removeClass('btn-primary active').addClass('btn-outline-primary');
                $('#btn-mode-barang').removeClass('btn-outline-success').addClass('btn-success active');
                $('#th-harga').text('Harga Satuan');
            } else {
                $('#btn-mode-barang').removeClass('btn-success active').addClass('btn-outline-success');
                $('#btn-mode-bebas').removeClass('btn-outline-primary').addClass('btn-primary active');
                $('#th-harga').text('Harga');
            }

            // Hapus semua baris detail yang ada
            $('#input-container').empty();
            rowCount = 0;
            deletedRows = []; // reset deleted rows juga
            updateSubmitButtonState();
            reorderRows();
        }

        // Kumpulkan semua inventory_id yang sudah dipilih di baris lain
        // id barang di prepayment menggunakan hidden input id="inv-id-{num}"
        function getSelectedInventoryIdsPrepayment(excludeNum) {
            var used = [];
            $('input[id^="inv-id-"]').each(function() {
                var rowNum = $(this).attr('id').replace('inv-id-', '');
                if (rowNum != excludeNum) {
                    var val = $(this).val();
                    if (val) used.push(String(val));
                }
            });
            return used;
        }

        // ===== SELECT2 BARANG UNTUK PREPAYMENT =====
        function initBarangSelect2Prepayment(num) {
            var $sel = $('#barang-select-' + num);

            $sel.select2({
                width: '100%',
                placeholder: '-- Pilih Barang --',
                allowClear: true,
                minimumInputLength: 0,
                ajax: {
                    url: "<?= site_url('mac_prepayment/get_inventory_prepayment') ?>",
                    type: 'POST',
                    dataType: 'JSON',
                    delay: 300,
                    cache: false,
                    data: function(params) {
                        return {
                            search: params.term !== undefined ? params.term : '',
                            filter_cabang: $('#filter_cabang').val(),
                            _ts: new Date().getTime()
                        };
                    },
                    processResults: function(data) {
                        var used = getSelectedInventoryIdsPrepayment(num);

                        return {
                            results: (data || [])
                                .filter(function(d) {
                                    return !used.includes(String(d.id));
                                })
                                .map(function(d) {
                                    return {
                                        id: d.id,
                                        text: d.kode_produk + ' - ' + d.nama_produk,
                                        nama_produk: d.nama_produk,
                                        satuan: d.satuan,
                                        stok_fisik: d.stok_fisik,
                                        stok_efektif: d.stok_efektif,
                                        stok_minimal: d.stok_minimal
                                    };
                                })
                        };
                    }
                },
                matcher: function() { return true; }
            });

            $sel.off('select2:select select2:clear')
                .on('select2:select', function(e) {

                    if (!e.params || !e.params.data) {
                        return;
                    }

                    var d = e.params.data;

                    $('#inv-id-' + num).val(d.id);
                    $('#rincian-' + num).val(d.nama_produk);

                    var stokAktual  = parseFloat(d.stok_fisik || 0);
                    var stokEfektif = parseFloat(d.stok_efektif || stokAktual);
                    var stokMinimal = parseFloat(d.stok_minimal || 0);

                    var stokClass = stokEfektif <= 0
                        ? 'stok-empty'
                        : (stokEfektif <= stokMinimal ? 'stok-warn' : 'stok-ok');

                    $('#stok-info-' + num)
                        .html(
                            '<span class="' + stokClass + '">' +
                            'Stok Aktual: <b>' + stokAktual + ' ' + d.satuan + '</b>' +
                            ' | ' +
                            'Stok Efektif: <b>' + stokEfektif + ' ' + d.satuan + '</b>' +
                            '</span>'
                        )
                        .show();
                })
                .on('select2:clear', function() {
                    $('#inv-id-' + num).val('');
                    $('#rincian-' + num).val('');
                    $('#stok-info-' + num).hide().html('');
                });
        }

        // ===== TOGGLE MODE TEKS BEBAS / PILIH BARANG =====
        $(document).on('click', '.btn-toggle-mode', function() {
            var num  = $(this).data('row');
            var mode = $(this).data('mode');

            // Update tampilan tombol
            $(this).closest('.btn-group').find('.btn-toggle-mode').removeClass('active');
            $(this).addClass('active');

            if (mode === 'barang') {
                $('#mode-bebas-' + num).hide();
                $('#mode-barang-' + num).show();
                // Kosongkan rincian teks bebas supaya tidak ikut tersubmit
                $('#rincian-' + num).val('');
            } else {
                $('#mode-barang-' + num).hide();
                $('#mode-bebas-' + num).show();
                // Reset pilihan barang
                $('#barang-select-' + num).val(null).trigger('change');
                $('#inv-id-' + num).val('');
                $('#stok-info-' + num).hide().html('');
            }
        });

        //MENAMBAH FORM INPUTAN DI ADD FORM
        let rowCount = 0;

        function addRow() {
            rowCount++;
            var mode = currentMode;
            var row  = '';

            if (mode === 'barang') {
                row = `
                    <tr id="row-${rowCount}">
                        <td class="row-number">${rowCount}</td>
                        <td>
                            <select class="form-control select2-barang-prepayment"
                                    id="barang-select-${rowCount}" style="width:100%;">
                                <option value="">-- Pilih Barang --</option>
                            </select>
                            <input type="hidden" name="rincian[${rowCount}]"
                                id="rincian-${rowCount}" value="">
                            <input type="hidden" name="inventory_id_detail[${rowCount}]"
                                id="inv-id-${rowCount}" value="">
                            <div class="stok-info-box" id="stok-info-${rowCount}"></div>
                        </td>
                        <td>
                            <input type="text" class="form-control" id="nominal-${rowCount}"
                                name="nominal[${rowCount}]" placeholder="Harga">
                            <input type="hidden" id="hidden_nominal${rowCount}"
                                name="hidden_nominal[${rowCount}]" value="">
                        </td>
                        <td>
                            <input type="number" class="form-control" name="qty[${rowCount}]"
                                id="qty-${rowCount}" min="1" value="1" placeholder="0" autocomplete="off">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="keterangan[${rowCount}]"
                                placeholder="Keterangan">
                        </td>
                        <td class="text-center">
                            <span class="btn delete-btn btn-danger" data-id="${rowCount}">Delete</span>
                        </td>
                    </tr>`;
            } else {
                row = `
                    <tr id="row-${rowCount}">
                        <td class="row-number">${rowCount}</td>
                        <td>
                            <input type="text" class="form-control" name="rincian[${rowCount}]"
                                id="rincian-${rowCount}" placeholder="Rincian">
                            <input type="hidden" name="inventory_id_detail[${rowCount}]"
                                id="inv-id-${rowCount}" value="">
                        </td>
                        <td>
                            <input type="text" class="form-control" id="nominal-${rowCount}"
                                name="nominal[${rowCount}]" placeholder="Nominal">
                            <input type="hidden" id="hidden_nominal${rowCount}"
                                name="hidden_nominal[${rowCount}]" value="">
                        </td>
                        <td>
                            <input type="number" class="form-control" name="qty[${rowCount}]"
                                id="qty-${rowCount}" min="1" value="1" placeholder="0" autocomplete="off">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="keterangan[${rowCount}]"
                                placeholder="Keterangan">
                        </td>
                        <td class="text-center">
                            <span class="btn delete-btn btn-danger" data-id="${rowCount}">Delete</span>
                        </td>
                    </tr>`;
            }

            $('#input-container').append(row);
            formatJumlahInput(`#nominal-${rowCount}`);

            if (mode === 'barang') {
                initBarangSelect2Prepayment(rowCount);
            }

            updateSubmitButtonState();
            calculateTotalNominal();

            $("#form").validate().settings.rules[`nominal[${rowCount}]`] = { required: true };
        }

        // MENGHAPUS ROW
        function deleteRow(id) {
            // Simpan ID dari row yang dihapus
            const rowId = $(`#row-${id}`).find('input:hidden[id^="hidden_id_detail"]').val();
            if (rowId) {
                deletedRows.push(rowId);
            }

            console.log(rowId);

            $(`#row-${id}`).remove();
            // Reorder rows and update row numbers
            reorderRows();
            updateSubmitButtonState(); // Perbarui status tombol 
            //checkDeleteButtonState(); // Cek tombol delete setelah baris dihapus

            // Hitung total nominal setelah baris dihapus
            calculateTotalNominal();
        }

        // MENHATUR ULANG URUTAN ROW SAAT DIHAPUS
        function reorderRows() {
            $('#input-container tr').each(function(index) {
                const newRowNumber       = index + 1;
                const rincianValue       = $(this).find('input[name^="rincian"]').val();
                const nominalValue       = $(this).find('input[name^="nominal"]').val();
                const hiddenIdValue      = $(this).find('input[name^="hidden_id_detail"]').val();
                const hiddenNominalValue = $(this).find('input[name^="hidden_nominal"]').val();
                const keteranganValue    = $(this).find('input[name^="keterangan"]').val();

                $(this).attr('id', `row-${newRowNumber}`);
                $(this).find('.row-number').text(newRowNumber);

                // FIX: pakai newRowNumber, bukan num
                $(this).find('select[id^="barang-select-"]').attr('id', 'barang-select-' + newRowNumber);
                $(this).find('input[id^="inv-id-"]').attr('id', 'inv-id-' + newRowNumber).attr('name', 'inventory_id_detail[' + newRowNumber + ']');
                $(this).find('input[id^="rincian-"]').attr('id', 'rincian-' + newRowNumber).attr('name', 'rincian[' + newRowNumber + ']');
                $(this).find('[id^="stok-info-"]').attr('id', 'stok-info-' + newRowNumber);
                $(this).find('input[name^="rincian"]').attr('name', `rincian[${newRowNumber}]`).val(rincianValue);
                $(this).find('input[name^="nominal"]').attr('name', `nominal[${newRowNumber}]`).attr('id', `nominal-${newRowNumber}`).val(nominalValue);
                $(this).find('input[id^="qty-"]').attr('id', 'qty-' + newRowNumber).attr('name', 'qty[' + newRowNumber + ']');
                $(this).find('input[name^="hidden_id_detail"]').attr('name', `hidden_id_detail[${newRowNumber}]`).val(hiddenIdValue);
                $(this).find('input[name^="hidden_nominal"]').attr('name', `hidden_nominal[${newRowNumber}]`).attr('id', `hidden_nominal${newRowNumber}`).val(hiddenNominalValue);
                $(this).find('input[name^="keterangan"]').attr('name', `keterangan[${newRowNumber}]`).val(keteranganValue);
                $(this).find('.delete-btn').attr('data-id', newRowNumber);
            });
            rowCount = $('#input-container tr').length;
        }

        $('#add-row').click(function() {
            addRow();
        });

        function updateSubmitButtonState() {
            const rowCount = $('#input-container tr').length;
            if (rowCount > 0) {
                $('.aksi').prop('disabled', false); // Enable submit button
            } else {
                $('.aksi').prop('disabled', true); // Disable submit button
            }
        }

        $(document).on('click', '.delete-btn', function() {
            const id = $(this).data('id');
            deleteRow(id);
        });

        $('#form').submit(function(event) {
            // Tambahkan array deletedRows ke dalam form data sebelum submit
            $('<input>').attr({
                type: 'hidden',
                name: 'deleted_rows',
                value: JSON.stringify(deletedRows)
            }).appendTo('#form');

            // Lanjutkan dengan submit form
        });

        // MENGISI FORM UPDATE
        if (id == 0) {
            $('.aksi').append('<span class="front front-aksi">Save</span>');
        } else {
            $('.aksi').append('<span class="front front-aksi">Update</span>');
            $("select option[value='']").hide();
            $.ajax({
                url: "<?php echo site_url('mac_prepayment/edit_data') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    // moment.locale('id')
                    let total_nominal = 0;
                    // console.log(data);
                    for (let index = 0; index < data['transaksi'].length; index++) {
                        total_nominal += parseInt(data['transaksi'][index]['nominal'], 10);
                    }

                    var detectedMode = 'bebas';
                    if (data.transaksi.length > 0 && data.transaksi[0].inventory_id) {
                        detectedMode = 'barang';
                    }
    
                    // Terapkan mode tanpa konfirmasi (karena ini load data, bukan user mengubah)
                    currentMode = detectedMode;
                    $('#input_mode').val(detectedMode);
                    if (detectedMode === 'barang') {
                        $('#btn-mode-bebas').removeClass('btn-primary active').addClass('btn-outline-primary');
                        $('#btn-mode-barang').removeClass('btn-outline-success').addClass('btn-success active');
                    } else {
                        $('#btn-mode-barang').removeClass('btn-success active').addClass('btn-outline-success');
                        $('#btn-mode-bebas').removeClass('btn-outline-primary').addClass('btn-primary active');
                    }

                    //SET VALUE DATA MASTER PREPAYMENT
                    $('#id').val(data['master']['id']);
                    $('#kode_prepayment').val(data['master']['kode_prepayment'].toUpperCase()).attr('readonly', true);
                    $('#tgl_prepayment').val(moment(data['master']['tgl_prepayment']).format('DD-MM-YYYY'));
                    $('#nama').val(data['master']['nama']);

                    // safe-check no_rek before splitting to avoid errors if empty or null
                    var noRekRaw = (data['master']['no_rek'] || '').toString();
                    $('#rekening').val(noRekRaw).trigger('change');
                    if (noRekRaw.trim() !== '') {
                        var parts = noRekRaw.split("-");
                        if (parts.length === 3) {
                            $("#nama_rek").val(parts[0]);
                            $("#nama_bank").val(parts[1]);
                            $("#nomor_rekening").val(parts[2]);
                        } else {
                            // fallback: if format not "a-b-c", set the whole value into nomor_rekening
                            $("#nama_rek").val('');
                            $("#nama_bank").val('');
                            $("#nomor_rekening").val(noRekRaw);
                        }
                    } else {
                        // clear fields when no_rek is empty
                        $("#nama_rek").val('');
                        $("#nama_bank").val('');
                        $("#nomor_rekening").val('');
                    }
                    $('#prepayment').val(data['master']['prepayment']);
                    $('#tujuan').val(data['master']['tujuan']);
                    if (data['master']['total_nominal'] == null) {
                        $('#total_nominal_view').text(total_nominal.toLocaleString());
                        $('#total_nominal').val(total_nominal);
                    } else {
                        total_nominal = parseInt(data['master']['total_nominal'], 10);
                        $('#total_nominal_view').text(total_nominal.toLocaleString());
                        $('#total_nominal').val(data['master']['total_nominal']);
                    }

                    //APPEND DATA TRANSAKSI DETAIL PREPAYMENT
                    if (aksi == 'update') {
                    // Deteksi mode dari baris pertama
                    if (data['transaksi'].length > 0 && data['transaksi'][0].inventory_id) {
                        applyModePP('barang');
                    } else {
                        applyModePP('bebas');
                    }

                    $(data['transaksi']).each(function(index) {
                        rowCount++;
                        var d    = data['transaksi'][index];
                        var mode = currentMode;
                        var nominalFormatted = d.nominal.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                        var row  = '';

                        if (mode === 'barang') {
                            row = `
                                <tr id="row-${rowCount}">
                                    <td class="row-number">${rowCount}</td>
                                    <td>
                                        <select class="form-control select2-barang-prepayment"
                                                id="barang-select-${rowCount}" style="width:100%;">
                                            <option value="${d.inventory_id}" selected>${d.rincian}</option>
                                        </select>
                                        <input type="hidden" name="rincian[${rowCount}]"
                                            id="rincian-${rowCount}" value="${d.rincian}">
                                        <input type="hidden" name="inventory_id_detail[${rowCount}]"
                                            id="inv-id-${rowCount}" value="${d.inventory_id || ''}">
                                        <input type="hidden" id="hidden_id_detail${rowCount}"
                                            name="hidden_id_detail[${rowCount}]" value="${d.id}">
                                        <div class="stok-info-box" id="stok-info-${rowCount}"></div>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" id="nominal-${rowCount}"
                                            name="nominal[${rowCount}]" value="${nominalFormatted}">
                                        <input type="hidden" id="hidden_nominal${rowCount}"
                                            name="hidden_nominal[${rowCount}]" value="${d.nominal}">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" name="qty[${rowCount}]"
                                            id="qty-${rowCount}" min="1" value="${d.qty}" placeholder="0" autocomplete="off">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="keterangan[${rowCount}]"
                                            value="${d.keterangan}" placeholder="Keterangan">
                                    </td>
                                    <td>
                                        <span class="btn delete-btn btn-danger" data-id="${rowCount}">Delete</span>
                                    </td>
                                </tr>`;
                        } else {
                            row = `
                                <tr id="row-${rowCount}">
                                    <td class="row-number">${rowCount}</td>
                                    <td>
                                        <input type="text" class="form-control" name="rincian[${rowCount}]"
                                            id="rincian-${rowCount}" value="${d.rincian}">
                                        <input type="hidden" name="inventory_id_detail[${rowCount}]"
                                            id="inv-id-${rowCount}" value="">
                                        <input type="hidden" id="hidden_id_detail${rowCount}"
                                            name="hidden_id_detail[${rowCount}]" value="${d.id}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" id="nominal-${rowCount}"
                                            name="nominal[${rowCount}]" value="${nominalFormatted}">
                                        <input type="hidden" id="hidden_nominal${rowCount}"
                                            name="hidden_nominal[${rowCount}]" value="${d.nominal}">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" name="qty[${rowCount}]"
                                            id="qty-${rowCount}" min="1"  value="${d.qty}" placeholder="0" autocomplete="off">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="keterangan[${rowCount}]"
                                            value="${d.keterangan}" placeholder="Keterangan">
                                    </td>
                                    <td>
                                        <span class="btn delete-btn btn-danger" data-id="${rowCount}">Delete</span>
                                    </td>
                                </tr>`;
                        }

                        $('#input-container').append(row);
                        formatJumlahInput(`#nominal-${rowCount}`);

                        if (mode === 'barang' && d.inventory_id) {
                            initBarangSelect2Prepayment(rowCount);
                            var opt = new Option(d.rincian, d.inventory_id, true, true);
                            $('#barang-select-' + rowCount).append(opt).trigger('change.select2');
                        }

                        $("#form").validate().settings.rules[`nominal[${rowCount}]`] = { required: true };
                    });
                    }
                    // Enable submit button after data is loaded
                    updateSubmitButtonState();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        // UNTUK TAMPILAN READ ONLY
        if (aksi == "read") {
            $('.aksi').hide();
            $('#id').prop('readonly', true);
            $('#tgl_prepayment').prop('disabled', true);
            $('#nama').prop('readonly', true);
            $('#prepayment').prop('readonly', true);
            $('#tujuan').prop('readonly', true);
            $('#total_nominal_row').attr('colspan', 3);
            $('#add-row').toggle();
            $('th:last-child').remove();

            $.ajax({
                url: "<?php echo site_url('mac_prepayment/read_detail/') ?>" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $(data).each(function(index) {
                        //Nilai nominal diformat menggunakan pemisah ribuan sebelum dimasukkan ke dalam elemen input.
                        const nominalReadFormatted = data[index]['nominal'].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                        const row = `
                        <tr id="row-${index}">
                            <td class="row-number">${index + 1}</td>
                            <td><input readonly type="text" class="form-control" name="rincian[${index}]" value="${data[index]['rincian']}" /></td>
                            <td><input readonly type="text" class="form-control" name="nominal[${index}]" value="${nominalReadFormatted}" /></td>
                            <td><input readonly type="text" class="form-control" name="keterangan[${index}]" value="${data[index]['keterangan']}" /></td>
                        </tr>
                        `;
                        $('#input-container').append(row);
                    });
                }
            });
        }

        // INSERT ATAU UPDATE
        $("#form").submit(function(e) {
            e.preventDefault();
            var $form = $(this);
            if (!$form.valid()) return false;
            var url;
            if (id == 0) {
                url = "<?php echo site_url('mac_prepayment/add') ?>";
            } else {
                url = "<?php echo site_url('mac_prepayment/update') ?>";
            }

            // Tampilkan loading
            $('#loading').show();

            $('.aksi').prop('disabled', true);

            $.ajax({
                url: url,
                type: "POST",
                data: $('#form').serialize(),
                dataType: "JSON",
                success: function(data) {
                    // console.log(data);
                    // Sembunyikan loading saat respons diterima
                    $('#loading').hide();

                    if (data.status) //if success close modal and reload ajax table
                    {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Your data has been saved',
                            showConfirmButton: false,
                            timer: 1500
                        }).then((result) => {
                            checkNotifications();
                            location.href = "<?= base_url('mac_prepayment') ?>";
                        })
                    } else {
                        // Sembunyikan loading saat respons diterima
                        $('#loading').hide();
                        
                        // Enable button kembali saat ada error
                        $('.aksi').prop('disabled', false);

                        // Tampilkan pesan kesalahan
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: data.error
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Sembunyikan loading saat respons diterima
                    $('#loading').hide();
                    
                    // Enable button kembali saat ada error
                    $('.aksi').prop('disabled', false);

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error adding / updating data: ' + textStatus
                    });
                }
            });
        });

        $("#form").validate({
            rules: {
                tgl_prepayment: {
                    required: true,
                },
                nama: {
                    required: true,
                },
                prepayment: {
                    required: true,
                },
                // tujuan: {
                //     required: true,
                // },
                nama_rek: {
                    required: true,
                    maxlength: 22,
                },
                nama_bank: {
                    required: true,
                },
                nomor_rekening: {
                    required: true,
                },
            },
            messages: {
                tgl_prepayment: {
                    required: "Tanggal is required",
                },
                nama: {
                    required: "Nama is required",
                },

                prepayment: {
                    required: "Prepayment is required",
                },
                // tujuan: {
                //     required: "Tujuan is required",
                // },
                nama_rek: {
                    required: "*Nama rekening perlu diisi",
                    maxlength: "*Nama rekening tidak boleh lebih dari 22 digit",
                },
                nama_bank: {
                    required: "*Nama Bank perlu diisi",
                },
                nomor_rekening: {
                    required: "*Nomor rekening perlu diisi",
                },
            },
            errorPlacement: function(error, element) {
                if (element.parent().hasClass('input-group')) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function(element) {
                $(element).addClass('is-invalid'); // Tambahkan kelas untuk menandai input tidak valid
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid'); // Hapus kelas jika input valid
            },
            focusInvalid: false, // Disable auto-focus on the first invalid field
        });


    })
</script>