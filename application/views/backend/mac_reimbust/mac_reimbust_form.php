<!-- Style table transaksi -->
<link rel="stylesheet" href="<?= base_url("assets/backend/css/table-transaksi-reimbust.css") ?>">

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title_view ?></h1>
    </div>

    <!-- Form Loading indicator -->
    <div id="form_loading" style="display: none;">
        <p>Loading...</p>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header text-right">
                    <a class="btn btn-primary btn-sm btn-style" href="<?= base_url('mac_reimbust') ?>"><i class="fas fa-chevron-left"></i>&nbsp;Back</a>
                </div>
                <div class="card-body">
                    <form id="form" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-4">Sifat Pelaporan</label>
                                    <div class="col-sm-8" style="justify-content: space-between; align-items: center" id="parent_sifat_pelaporan">
                                        <select class="form-control" id="sifat_pelaporan" name="sifat_pelaporan" style="display: inline-block">
                                            <option value="">-- Pilih --</option>
                                            <option value="Reimbust">Reimbust</option>
                                            <option value="Pelaporan">Pelaporan</option>
                                            <option value="Pelaporan Kas">Pelaporan Kas</option>
                                        </select>
                                        <div class="btn btn-primary btn-small btn-style btn-search-prepayment"
                                            data-toggle="modal" data-target="#pelaporanModal"
                                            id="pelaporan_button" style="margin-left: 7px;">
                                            <i class="fas fa-solid fa-search"></i>
                                        </div>
                                    </div>
                                </div>

                                <!-- Section saldo kas — muncul otomatis saat pilih "Pelaporan Kas" -->
                                <div class="form-group row" id="section-saldo-kas" style="display:none;">
                                        <label class="col-sm-4">Saldo Kas</label>
                                    <div class="col-sm-8">
                                            <input type="hidden" name="kas_id" id="kas_id" value="">

                                            <small id="msg-kas-kosong" class="text-danger" style="display:none;">
                                                <i class="fa fa-exclamation-circle"></i>
                                                Tidak ada kas aktif di cabang ini. Ajukan prepayment kas terlebih dahulu.
                                            </small>

                                            <!-- Info saldo — langsung tampil tanpa perlu pilih -->
                                            <div id="info-saldo-kas" class="alert alert-info py-2 mb-0" style="display:none;">
                                                <div class="row text-center">
                                                    <div class="col-6">
                                                        <small class="text-muted d-block">Sisa Saldo</small>
                                                        <span class="font-weight-bold text-success" id="kas-sisa">—</span>
                                                    </div>
                                                    <div class="col-6">
                                                        <small class="text-muted d-block">Sudah Dilaporkan</small>
                                                        <span class="font-weight-bold text-danger" id="kas-sudah-dilaporkan">—</span>
                                                    </div>
                                                </div>
                                            </div>
                                        <input type="hidden" name="is_pelaporan_kas" id="is_pelaporan_kas" value="0">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4">Tanggal Pengajuan</label>
                                    <div class="col-sm-8">
                                        <div class="input-group date">
                                            <input type="text" class="form-control" name="tgl_pengajuan" id="tgl_pengajuan" placeholder="DD-MM-YYYY" autocomplete="off">
                                            <div class="input-group-append">
                                                <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4">Kode Reimbust</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="kode_reimbust" name="kode_reimbust" placeholder="Kode Reimbust">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">No Rekening</label>
                                    <div class="col-sm-8">
                                        <div class="input-group mb-3">
                                            <div class="form-check form-check-inline" style="margin-bottom: 5px;">
                                                <?php if ($id_pembuat != $id_user && $aksi == 'update') { ?>
                                                    <input class="form-check-input" type="radio" name="radioNoLabel" id="new" value="" aria-label="..." checked><label for="new" style="margin-top: 8px; cursor: pointer">Rekening</label>
                                                <?php } else { ?>
                                                    <input class="form-check-input" type="radio" name="radioNoLabel" id="exist" value="" aria-label="..." checked><label for="exist" style="margin-right: 14px; margin-top: 8px; cursor: pointer">Rekening terdaftar</label>
                                                    <input class="form-check-input" type="radio" name="radioNoLabel" id="new" value="" aria-label="..."><label for="new" style="margin-top: 8px; cursor: pointer">Rekening baru</label>
                                                <?php } ?>
                                            </div>
                                            <select class="input-rekening" id="rekening" name="rekening">
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
                                <input type="hidden" class="form-control" id="departemenPrepayment" name="departemen" autocomplete="off" placeholder="Departemen">
                            </div>
                            <!-- SEBELAH KANAN -->
                            <div class="col-md-6">
                                <input type="hidden" class="form-control" id="jabatan" name="jabatan" autocomplete="off" placeholder="Jabatan">
                                <div class="form-group row tujuan-field">
                                    <label class="col-sm-4">Tujuan</label>
                                    <div class="col-sm-8">
                                        <div class="form-floating">
                                            <textarea class="form-control" placeholder="Tujuan" id="tujuan" name="tujuan"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row jml-pre-field">
                                    <label class="col-sm-4">Jumlah Prepayment</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="jumlah_prepayment" name="jumlah" autocomplete="off" placeholder="Jumlah Prepayment">
                                        <input type="hidden" id="hidden_jumlah_prepayment" name="jumlah_prepayment">
                                    </div>
                                </div>
                                <div class="form-group row kode-pre-field kode_prepayment">
                                    <label class="col-sm-4">Kode Prepayment</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" readonly placeholder="Kode Prepayment" name="kode_prepayment" id="kode_prepayment_input" style="cursor: not-allowed;">
                                        <input type="hidden" class="form-control" id="kode_prepayment_old" name="kode_prepayment_old">
                                    </div>
                                </div>
                                <div class="form-group row kode-pre-field">
                                    <label class="col-sm-4">Total Nominal</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" readonly placeholder="Total Nominal" name="total_nominal" id="total_nominal" style="cursor: not-allowed;">
                                    </div>
                                </div>
                                <div class="form-group row kode-pre-field" id="mode-inputan" style="display: none">
                                    <label class="col-sm-4">Mode Inputan</label>
                                    <div class="col-sm-8">
                                        <div class="btn-group btn-group" role="group">
                                            <button type="button"
                                                    class="btn btn-primary btn"
                                                    id="btn-mode-bebas"
                                                    data-mode="bebas">
                                                <i class="fa fa-pencil-alt"></i> Teks Bebas
                                            </button>

                                            <button type="button"
                                                    class="btn btn-outline-success btn"
                                                    id="btn-mode-barang"
                                                    data-mode="barang">
                                                <i class="fa fa-box"></i> Pilih Barang
                                            </button>
                                            <input type="hidden" id="input_mode" value="bebas">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- BUTTON TAMBAH FORM -->
                        <div class="d-flex justify-content-between align-items-center">
                            <button type="button" class="btn-style" id="add-row">
                                <i class="fa fa-plus" aria-hidden="true"></i> Add
                            </button>
                        </div>
                        <!-- TABLE INPUT -->
                        <div class="mt-2 table-transaksi">
                            <table class="table">
                                <thead class="header-table-transaksi">
                                    <tr>
                                        <th scope="col" width="5%">No</th>
                                        <th scope="col" width="25%">Pemakaian</th>
                                        <th scope="col" width="12%">Tanggal Nota</th>
                                        <th scope="col" id="th-harga" width="13%">Harga</th>
                                        <th scope="col" width="9%">Qty</th>
                                        <th scope="col" width="13%">Kwitansi</th>
                                        <th scope="col" width="12%">Deklarasi</th>
                                        <th scope="col" id="action" width="10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="input-container">
                                    <!-- CONTAINER INPUTAN -->
                                </tbody>
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
                            <button type="submit" class="btn btn-primary btn-sm aksi btn-style" disabled style="cursor: not-allowed"></button>
                        <?php } else { ?>
                            <button type="submit" class="btn btn-primary btn-sm aksi btn-style"></button>
                        <?php } ?>
                        <!-- END PENENTUAN UPDATE ATAU ADD -->

                        <!-- Modal Data Table Prepayment -->
                        <div class="modal fade" id="pelaporanModal" tabindex="-1" aria-labelledby="pelaporanModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="pelaporanModalLabel">Data Pelaporan</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <table id="prepayment-table" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th style="display: none">Action</th>
                                                    <th>Kode Prepayment</th>
                                                    <th>Nama</th>
                                                    <th>Divisi</th>
                                                    <th>Jabatan</th>
                                                    <th>Tanggal Pengajuan</th>
                                                    <th>Prepayment</th>
                                                    <th>Nominal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>No</th>
                                                    <th style="display: none">Action</th>
                                                    <th>Kode Prepayment</th>
                                                    <th>Nama</th>
                                                    <th>Divisi</th>
                                                    <th>Jabatan</th>
                                                    <th>Tanggal Pengajuan</th>
                                                    <th>Prepayment</th>
                                                    <th>Nominal</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Data Table Deklarasi add & update -->
                        <div class="modal fade" id="deklarasiModal" tabindex="-1" aria-labelledby="deklarasiModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deklarasiModalLabel">Data Deklarasi</h5>
                                        <!-- <a style="position: relative; right: 75px" class="btn btn-primary btn-sm" href="<?= base_url('mac_datadeklarasi/add_form') ?>"><i class="fa fa-plus"></i>&nbsp;Add Data</a> -->
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span style="position: relative; bottom: 5px" aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <table id="deklarasi-table" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th style="display: none">Action</th>
                                                    <th>Kode Deklarasi</th>
                                                    <th>Tanggal</th>
                                                    <th>Pengaju</th>
                                                    <th>Jabatan</th>
                                                    <th>Penerima</th>
                                                    <th>Tujuan</th>
                                                    <th>Sebesar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>No</th>
                                                    <th style="display: none">Action</th>
                                                    <th>Kode Deklarasi</th>
                                                    <th>Tanggal</th>
                                                    <th>Pengaju</th>
                                                    <th>Jabatan</th>
                                                    <th>Penerima</th>
                                                    <th>Tujuan</th>
                                                    <th>Sebesar</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal -->
                        <div id="myModal" class="kwitansi-modal">
                            <span class="close">&times;</span>
                            <img class="modal-content" id="img01">
                            <!-- <div id="caption"></div> -->
                        </div>
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
        // ===== KAS =====
        function fmtRp(n) {
            return 'Rp ' + parseInt(n || 0).toLocaleString('id-ID');
        }

        // Handler perubahan sifat pelaporan
        $('#sifat_pelaporan').on('change', function() {
            var val = $(this).val();

            if (val === 'Pelaporan Kas') {
                $('#is_pelaporan_kas').val(1);
                $('#section-saldo-kas').show();
                loadKasAktif(null);
            } else {
                $('#is_pelaporan_kas').val(0);
                $('#section-saldo-kas').hide();
                $('#info-saldo-kas').hide();
                $('#kas_id').val('');
                $('#msg-kas-kosong').hide();

                // Kosongkan kode_prepayment jika ganti ke sifat lain
                $('#kode_prepayment_input').val('');
            }
        });

        // Load kas aktif cabang
        function loadKasAktif(selectedKasId) {
            selectedKasId = selectedKasId || null;

            $.ajax({
                url: "<?= site_url('mac_kas/get_kas_aktif_cabang') ?>",
                type: 'GET',
                dataType: 'JSON',
                success: function(res) {
                    $('#msg-kas-kosong').hide();
                    $('#info-saldo-kas').hide();
                    $('#kas_id').val('');

                    if (!res.status || res.data.length === 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Kas Tidak Tersedia',
                            text: 'Tidak ada kas aktif di cabang ini.'
                        });
                        $('#sifat_pelaporan').val('').trigger('change');
                        
                        // Kosongkan kode_prepayment jika tidak ada kas
                        $('#kode_prepayment_input').val('');
                        return;
                    }

                    var d = res.data[0]; // hanya 1 kas aktif per cabang

                    // Set hidden kas_id
                    $('#kas_id').val(d.id);

                    // Auto-isi kode_prepayment dari kas aktif
                    $('#kode_prepayment_input').val(d.kode_prepayment);
                    $('#jumlah_prepayment').val(parseInt(d.sisa_kas).toLocaleString('id-ID'));

                    // Tampilkan info saldo
                    $('#kas-nominal-awal').text('Rp ' + parseInt(d.nominal_awal).toLocaleString('id-ID'));
                    $('#kas-sudah-dilaporkan').text('Rp ' + parseInt(d.total_dilaporkan).toLocaleString('id-ID'));
                    $('#kas-sisa').text('Rp ' + parseInt(d.sisa_kas).toLocaleString('id-ID'));
                    $('#info-saldo-kas').show();
                }
            });
        }

        // Update info saldo saat kas dipilih
        $('#select-kas').on('change', function() {
            var $opt = $(this).find(':selected');
            if (!$(this).val()) {
                $('#info-saldo-kas').hide();
                return;
            }
            $('#kas-nominal-awal').text(fmtRp($opt.data('nominal')));
            $('#kas-sudah-dilaporkan').text(fmtRp($opt.data('dilaporkan')));
            $('#kas-sisa').text(fmtRp($opt.data('sisa')));
            $('#info-saldo-kas').show();
        });

        // Inisiasi saat edit
        // Tambahkan di dalam success callback edit_data, setelah set sifat_pelaporan:
        // if (data['master']['is_pelaporan_kas'] == 1) {
        //     $('#sifat_pelaporan').val('Pelaporan Kas').trigger('change');
        //     loadKasAktif(data['master']['kas_id']);
        // }

        // Validasi total_nominal tidak melebihi sisa kas sebelum submit
        // Tambahkan di dalam fungsi submit form sebelum $.ajax
        // function validateKas() {
        //     if (!$('#toggle-kas').is(':checked')) return true;

        //     var kasId = $('#select-kas').val();
        //     if (!kasId) {
        //         Swal.fire({ icon: 'warning', title: 'Perhatian',
        //             text: 'Pilih kas terlebih dahulu.' });
        //         return false;
        //     }

        //     var sisa         = parseFloat($('#select-kas').find(':selected').data('sisa')) || 0;
        //     var totalNominal = parseInt($('#total_nominal').val().replace(/\./g, '')) || 0;

        //     if (totalNominal > sisa) {
        //         Swal.fire({
        //             icon: 'error',
        //             title: 'Melebihi Sisa Kas',
        //             html: 'Total pelaporan <b>' + fmtRp(totalNominal) + '</b>' +
        //                 ' melebihi sisa kas <b>' + fmtRp(sisa) + '</b>.'
        //         });
        //         return false;
        //     }

        //     return true;
        // }

        $('#tgl_pengajuan').datepicker({
            dateFormat: 'dd-mm-yy',
            // minDate: new Date(),
            // maxDate: new Date(),

            // MENGENERATE KODE PREPAYMENT SETELAH PILIH TANGGAL
            onSelect: function(dateText) {
                var id = dateText;
                $('#tgl_pengajuan').removeClass("is-invalid");

                // Menghapus label error secara manual jika ada
                if ($("#tgl_pengajuan-error").length) {
                    $("#tgl_pengajuan-error").remove(); // Menghapus label error
                }
                $.ajax({
                    url: "<?php echo site_url('mac_reimbust/generate_kode') ?>",
                    type: "POST",
                    data: {
                        "date": dateText
                    },
                    dataType: "JSON",
                    success: function(data) {
                        $('#kode_reimbust').val(data.toUpperCase());
                        $('#kode').val(data);
                    },
                    error: function(error) {
                        alert("error" + error);
                    }
                });
            }
        });

        // Select 2 No Rekening
        $('.input-rekening').select2();

        // Data barang untuk select2 pemakaian (sifat_pelaporan = Pelaporan)
        // var inventoryOptions = <?= json_encode($inventory_options ?? []) ?>;

        // function buildInventoryOptionsHtml(selectedId) {
        //     let html = '<option value="">-- Pilih Barang --</option>';
        //     inventoryOptions.forEach(function(item) {
        //         const selected = (selectedId && selectedId == item.id) ? 'selected' : '';
        //         html += `<option value="${item.id}" ${selected}>${item.kode_produk} - ${item.nama_produk}</option>`;
        //     });
        //     return html;
        // }

        function buildModeToggle(rowNum, activeMode, jenis) {
            activeMode = activeMode || 'bebas';
            if (jenis === 'Pelaporan') {
                return `
                    <div class="btn-group btn-group-sm w-100" role="group" style="margin-top: 5px">
                        <button type="button"
                                class="btn btn-sm btn-toggle-pemakaian ${activeMode === 'bebas' ? 'btn-primary' : 'btn-outline-primary'}"
                                data-row="${rowNum}" data-mode="bebas" title="Teks Bebas">
                            <i class="fa fa-pencil-alt fa-xs"></i>
                        </button>
                        <button type="button"
                                class="btn btn-sm btn-toggle-pemakaian ${activeMode === 'barang' ? 'btn-success' : 'btn-outline-success'}"
                                data-row="${rowNum}" data-mode="barang" title="Pilih Barang">
                            <i class="fa fa-box fa-xs"></i>
                        </button>
                    </div>`;
            } else {
                return `
                <div style="text-align: center; margin-top: 5px">
                    -
                </div>`;
            }
        }

        function buildPemakaianCell(rowNum, mode, selectedInventoryId, pemakaianText) {
            if (mode === 'barang') {
                return `
                    <select class="form-control form-control select2-barang-reimbust"
                            id="barang_select${rowNum}" style="width:100%;">
                        ${selectedInventoryId
                            ? `<option value="${selectedInventoryId}" selected>${pemakaianText || ''}</option>`
                            : '<option value="">-- Pilih Barang --</option>'}
                    </select>
                    <div id="stok-info-${rowNum}" style="font-size:11px; margin-top:3px;
                        padding:2px 6px; background:#f8f9fc; border:1px solid #e3e6f0;
                        border-radius:3px; ${selectedInventoryId ? '' : 'display:none;'}"></div>
                    <input type="hidden" name="pemakaian[${rowNum}]"    id="pemakaian${rowNum}"
                        value="${pemakaianText || ''}">
                    <input type="hidden" name="inventory_id[${rowNum}]" id="inventory_id${rowNum}"
                        value="${selectedInventoryId || ''}">
                `;
            }

            // Mode teks bebas
            return `
                <input type="text" class="form-control form-control"
                    name="pemakaian[${rowNum}]" id="pemakaian${rowNum}"
                    value="${pemakaianText || ''}"
                    placeholder="Pemakaian ${rowNum}" autocomplete="off">
                <input type="hidden" name="inventory_id[${rowNum}]" id="inventory_id${rowNum}" value="">
            `;
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
        // excludeNum = nomor baris yang sedang diinit (tidak dihitung)
        function getSelectedInventoryIdsReimbust(excludeNum) {
            var used = [];
            $('input[id^="inventory_id"]').each(function() {
                var rowNum = $(this).attr('id').replace('inventory_id', '');
                if (rowNum != excludeNum) {
                    var val = $(this).val();
                    if (val) used.push(String(val));
                }
            });
            return used;
        }

        // ===== SELECT2 BARANG UNTUK REIMBUST =====
        function initBarangSelect2Reimbust(num) {
            var $sel = $('#barang_select' + num);
            if ($sel.hasClass('select2-hidden-accessible')) $sel.select2('destroy');

            $sel.select2({
                width: '100%',
                placeholder: '-- Pilih Barang --',
                allowClear: true,
                minimumInputLength: 0,
                ajax: {
                    url: "<?= site_url('mac_reimbust/get_inventory_options_ajax') ?>",
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
                        var used = getSelectedInventoryIdsReimbust(num);
                        return {
                            results: (data || [])
                                .filter(function(d) {
                                    return !used.includes(String(d.id)); // hilangkan yang sudah dipilih di baris lain
                                })
                                .map(function(d) {
                                    return {
                                        id:          d.id,
                                        text:        d.kode_produk + ' - ' + d.nama_produk,
                                        nama_produk: d.nama_produk,
                                        stok_aktual: parseFloat(d.stok_aktual) || 0,
                                        satuan:      d.satuan || ''
                                    };
                                })
                        };
                    }
                },
                matcher: function() { return true; }
            });

            $sel.off('select2:select select2:clear')
                .on('select2:select', function(e) {
                    var d = e.params.data;
                    $('#pemakaian' + num).val(d.nama_produk);
                    $('#inventory_id' + num).val(d.id);

                    var stokColor = d.stok_aktual <= 0
                        ? '#e74a3b' : (d.stok_aktual <= 5 ? '#f6c23e' : '#1cc88a');
                    $('#stok-info-' + num)
                        .html('<span style="color:' + stokColor + '; font-weight:600;">' +
                            'Sisa stok aktual: ' + d.stok_aktual + ' ' + d.satuan + '</span>')
                        .show();
                })
                .on('select2:clear', function() {
                    $('#pemakaian' + num).val('');
                    $('#inventory_id' + num).val('');
                    $('#stok-info-' + num).hide().html('');
                });
        }

        // ===== FUNGSI BARU: Toggle mode bebas/barang =====
        $(document).on('click', '.btn-toggle-pemakaian', function() {
            var num  = $(this).data('row');
            var mode = $(this).data('mode');

            // Update tampilan tombol
            $('[data-row="' + num + '"].btn-toggle-pemakaian').each(function() {
                var btnMode = $(this).data('mode');
                if (btnMode === 'bebas') {
                    $(this).removeClass('btn-primary btn-outline-primary')
                        .addClass(mode === 'bebas' ? 'btn-primary' : 'btn-outline-primary');
                } else {
                    $(this).removeClass('btn-success btn-outline-success')
                        .addClass(mode === 'barang' ? 'btn-success' : 'btn-outline-success');
                }
            });

            $('#active_mode_' + num).val(mode);

            if (mode === 'barang') {
                $('#mode-bebas-' + num).hide();
                $('#mode-barang-' + num).show();
                // Kosongkan pemakaian teks bebas
                $('#pemakaian' + num).val('');
                initBarangSelect2Reimbust(num);
            } else {
                $('#mode-barang-' + num).hide();
                $('#mode-bebas-' + num).show();
                // Reset barang
                if ($('#barang_select' + num).hasClass('select2-hidden-accessible')) {
                    $('#barang_select' + num).val(null).trigger('change');
                }
                $('#inventory_id' + num).val('');
                $('#pemakaian_val' + num).val('');
                $('#stok-info-' + num).hide().html('');
            }
        });

        // Sync pemakaian teks bebas ke hidden input saat diketik
        $(document).on('input', '[id^="pemakaian"]', function() {
            var id  = $(this).attr('id');
            var num = id.replace('pemakaian', '');
            // Hanya sync jika bukan hidden (pemakaian_val)
            if (id.indexOf('pemakaian_val') === -1) {
                $('#pemakaian_val' + num).val($(this).val());
            }
        });

        // Fungsi untuk mengatur enabled/disabled elemen berdasarkan radio button yang dipilih
        function toggleInputs() {
            const isExistChecked = $('#exist').is(':checked');

            if (isExistChecked) {
                $('#rekening').prop('disabled', false).show();
                $('#rekening').next('.select2-container').show();
                $('.input-group.rekening-text input[type="text"]').prop('disabled', true).parent().hide();
            } else {
                $('#rekening').prop('disabled', true).hide();
                $('#rekening').next('.select2-container').hide();
                $('.input-group.rekening-text input[type="text"]').prop('disabled', false).parent().show();
            }
        }

        // Panggil fungsi saat halaman dimuat
        toggleInputs();

        // Panggil fungsi saat radio button berubah
        $('input[name="radioNoLabel"]').change(toggleInputs);

        document.getElementById('nomor_rekening').addEventListener('input', function(e) {
            let value = this.value.replace(/[^0-9]/g, '');
            if (value.length > 60) {
                value = value.slice(0, 10);
            }
            this.value = value;
        });

        // Data table prepayment
        
        // METHOD POST MENAMPILKAN DATA KE DATA TABLE
        var prepaymentTable = $('#prepayment-table').DataTable({
            "responsive": true,
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('mac_reimbust/get_list3') ?>",
                "type": "POST",
                "data": function(d) {
                    d.status = 'approved';
                }
            },
            "columnDefs": [{
                "targets": [2, 5, 6],
                "className": 'dt-head-nowrap'
            },
            {
                "targets": [1, 3, 4, 5, 7],
                "className": 'dt-body-nowrap'
            },
            {
                "targets": [0, 1],
                "orderable": false,
            }]
        });

        // Cek data pelaporan, jika kosong tampilkan swal
        $('#pelaporan_button').on('click', function () {
            if (prepaymentTable.data().count() === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Data prepayment kosong',
                    text: 'Tidak ada data yang bisa diproses',
                    showConfirmButton: false,
                    timer: 2000
                });
                return false;
            }
        });

        // Event listener untuk baris tabel dalam modal
        $('#prepayment-table tbody').on('click', 'tr', function() {
            // Ambil data dari baris yang diklik
            let data = prepaymentTable.row(this).data();

            // Masukkan data ke dalam input form di tampilan utama
            $('#kode_prepayment_input').val(data[2]);
            $('#departemenPrepayment').val(data[4]);
            $('#jabatan').val(data[5]);
            $('#jumlah_prepayment').val(data[8]);
            var cleanedValue = data[8].replace(/\./g, '');
            $('#hidden_jumlah_prepayment').val(cleanedValue);
            $('#tujuan').val(data[7]);

            // Tutup modal setelah data dipilih
            $('#pelaporanModal').modal('hide');
        });

        // Data table deklarasi
        var deklarasiTable = $('#deklarasi-table').DataTable({
            "responsive": true,
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('mac_reimbust/get_list2') ?>",
                "type": "POST"
            },
            "columnDefs": [{
                    "targets": [2],
                    "className": 'dt-head-nowrap'
                },
                {
                    "targets": [3, 4, 5, 7],
                    "className": 'dt-body-nowrap'
                },
                {
                    "targets": [0, 1],
                    "orderable": false,
                },
            ]
        });

        // Variabel untuk menyimpan rowCount
        var currentRowCount;
        
        // Event listener untuk tombol modal deklarasi
        $(document).on('click', '[id^=deklarasi-modal]', function() {
            currentRowCount = $(this).data('id');
        });

        // Event listener untuk baris tabel dalam modal
        $('#deklarasi-table tbody').on('click', 'tr', function() {
            let data = deklarasiTable.row(this).data();

            $('#deklarasi' + currentRowCount).val(data[2]);
            $('#deklarasi-modal' + currentRowCount).text(data[2]);

            if ($('#deklarasi' + currentRowCount).val().trim() !== '') {
                // Disable semua input di baris yang sama
                $('#deklarasi' + currentRowCount).closest('tr').find('input').prop('readonly', true);
                $('#inventory_id' + currentRowCount).prop('disabled', true);
                $('#kwitansi-upload' + currentRowCount).css('pointer-events', 'none');
                $('#upload' + currentRowCount).css('background-color', '#EAECF4').text('Deklarasi').val('');
                $('.kwitansi_image' + currentRowCount).val('');
                $('#pemakaian' + currentRowCount).css('cursor', 'not-allowed').attr('placeholder', 'Deklarasi').val(data[7]);
                $('#inputGroupFile01' + currentRowCount).val('').attr('name', '');
                // Menghapus atribut required dari input file
                $('#inputGroupFile01' + currentRowCount).removeAttr('required').val('');
                $('#tgl_nota_' + currentRowCount).css({
                    'cursor': 'not-allowed',
                    'pointer-events': 'none'
                }).attr('placeholder', 'Deklarasi').val(data[3]);
                // Set nominal: extract numeric value, format visible input, store cleaned numeric value in hidden input
                var nominalRaw = (data[8] || '').toString();
                // Remove all non-digit characters (e.g., 'Rp', spaces, dots, commas)
                var numericOnly = nominalRaw.replace(/[^0-9]/g, '');
                var nominalFormatted = numericOnly.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                $('#jumlah-' + currentRowCount)
                    .css('cursor', 'not-allowed')
                    .attr('placeholder', 'Deklarasi')
                    .val(nominalFormatted);
                $('#qty-' + currentRowCount)
                    .css('cursor', 'not-allowed')
                    .val(1);
                // Store pure numeric string in hidden input
                var nominalClean = numericOnly;
                $('#hidden_jumlah' + currentRowCount).attr('placeholder', 'Deklarasi').val(nominalClean).addClass('hidden_jumlah');
                $("#form").validate().settings.rules[`pemakaian[${currentRowCount}]`] = {
                    required: false
                };
                $("#form").validate().settings.rules[`tgl_nota[${currentRowCount}]`] = {
                    required: false
                };
                $("#form").validate().settings.rules[`jml[${currentRowCount}]`] = {
                    required: false
                };
                $("#form").validate().settings.rules[`qty[${currentRowCount}]`] = {
                    required: false
                };
            }
            updateTotalNominal();
            // Sembunyikan baris yang diklik setelah data dipilih
            $(this).hide();
            // Tutup modal setelah data dipilih
            $('#deklarasiModal').modal('hide');
        });

        //MEMBUAT TAMPILAN HARGA MENJADI ADA TITIK
        $('#jumlah_prepayment').on('input', function() {
            let value = $(this).val().replace(/[^,\d]/g, '');
            let parts = value.split(',');
            let integerPart = parts[0];
            // Format tampilan dengan pemisah ribuan
            integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            // Set nilai yang diformat ke tampilan
            $(this).val(parts[1] !== undefined ? integerPart + ',' + parts[1] : integerPart);
            // Hapus semua pemisah ribuan untuk pengiriman ke server
            let cleanValue = value.replace(/\./g, '');
            // Anda mungkin ingin menyimpan nilai bersih ini di input hidden atau langsung mengirimkannya ke server
            $('#hidden_jumlah_prepayment').val(cleanValue);
        });

        // Tambahkan fungsi untuk memformat input jumlah memiliki titik
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
                const hiddenId = `#hidden_${$(this).attr('id').replace('jumlah-', 'jumlah')}`;
                $(hiddenId).val(cleanValue);
                updateTotalNominal();
            });
        }

        function parseRupiahToNumber(value) {
            if (!value) return 0;
            var s = value.toString();
            // Remove any non-digit, non-comma, non-dot, non-minus characters (e.g., 'Rp', spaces)
            s = s.replace(/[^0-9,\.\-]/g, '');
            // Remove thousand separators and normalize decimals
            s = s.replace(/\./g, '').replace(/,/g, '.');
            return Number(s) || 0;
        }

        function formatRupiah(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        function updateTotalNominal() {
            let total = 0;
            $('#input-container tr').each(function(index) {
                const nominalRaw = $(this).find('.hidden_jumlah').val();
                const nominal = parseRupiahToNumber(nominalRaw);
                const qty = Number($(this).find('input[id^="qty-"]').val()) || 0;
                const rowTotal = nominal * qty;
                total += rowTotal;
            });
            $('#total_nominal').val(formatRupiah(total));
        }

        $(document).on('input', 'input[id^="qty-"]', function() {
            const qty = $(this).val();
            if (qty !== '' && Number(qty) < 1) {
                $(this).val(1);
            }
            updateTotalNominal();
        });

        //MENAMBAH FORM INPUTAN DI ADD FORM
        var id = $('#id').val();
        var aksi = $('#aksi').val();
        var kode = $('#kode').val();
        var sifat_pelaporan = $('#sifat_pelaporan').val();
        let deletedRows = [];
        
        if (id != 0) {
            // Tampilkan loading
            $('#form_loading').show();
            $('.aksi').prop('disabled', true);
        }
        let rowCount = 0;

        // Append dari form ADD
        function addRow() {
                    rowCount++;
                    const mode = currentMode;

                    const row = `
                    <tr id="row-${rowCount}">
                        <td class="row-number text-center">${rowCount}</td>
                        <td>
                            ${buildPemakaianCell(rowCount, mode, null, '')}
                        </td>
                        <td>
                            <input type="text" class="form-control tgl_nota" name="tgl_nota[${rowCount}]"
                                id="tgl_nota_${rowCount}" style="cursor:pointer" autocomplete="off"
                                placeholder="Tanggal Nota ${rowCount}">
                        </td>
                        <td>
                            <input type="text" class="form-control" id="jumlah-${rowCount}"
                                placeholder="Jumlah ${rowCount}" name="jml[${rowCount}]" autocomplete="off">
                            <input type="hidden" class="hidden_jumlah" id="hidden_jumlah${rowCount}"
                                name="jumlah[${rowCount}]" value="">
                        </td>
                        <td>
                            <input type="number" class="form-control" id="qty-${rowCount}"
                                placeholder="Qty" name="qty[${rowCount}]" min="1" value="1"
                                autocomplete="off">
                        </td>
                        <td style="padding:12px 12px 5px !important" id="kwitansi-upload${rowCount}">
                            <div class="custom-file">
                                <input type="file" required class="custom-file-input"
                                    name="kwitansi[${rowCount}]" id="inputGroupFile01${rowCount}">
                                <label class="custom-file-label" for="inputGroupFile01${rowCount}"
                                    id="upload${rowCount}">Upload..</label>
                                <span class="kwitansi-label">Max Size : 3MB</span>
                            </div>
                        </td>
                        <td width="150" style="padding:15px 10px">
                            <div class="btn btn-primary btn-lg btn-block btn-sm btn-style"
                                data-toggle="modal" data-target="#deklarasiModal"
                                data-id="${rowCount}" id="deklarasi-modal${rowCount}">Deklarasi</div>
                            <input type="hidden" id="deklarasi${rowCount}"
                                name="deklarasi[${rowCount}]" autocomplete="off">
                            <input type="hidden" class="deklarasi-old" id="deklarasi_old${rowCount}"
                                name="deklarasi_old[${rowCount}]" autocomplete="off" value="">
                        </td>
                        <td>
                            <span class="btn delete-btn btn-danger btn-style btn-delete"
                                data-id="${rowCount}">Delete</span>
                        </td>
                    </tr>`;

                    $('#input-container').append(row);

                // Init select2 jika mode barang
                if (mode === 'barang') {
                    initBarangSelect2Reimbust(rowCount);
                }
                const currentSifat = $('#sifat_pelaporan').val();
                if (currentSifat == 'Pelaporan') {
                    // initPemakaianSelect2(rowCount);
                    $("#form").validate().settings.rules[`inventory_id[${rowCount}]`] = { required: true };
                } else {
                    $("#form").validate().settings.rules[`pemakaian[${rowCount}]`] = { required: true };
                }
                // Tambahkan format ke input jumlah yang baru
                formatJumlahInput(`#jumlah-${rowCount}`);
                updateTotalNominal();
                updateSubmitButtonState(); // Perbarui status tombol submit
                checkDeleteButtonState(); // Cek tombol delete setelah baris ditambahkan

                $(`#deklarasi-modal${rowCount}`).on('click', function() {
                    var rowId = $(this).data('id');
                });

                //VALIDASI ROW YANG TELAH DI APPEND
                $("#form").validate().settings.rules[`pemakaian[${rowCount}]`] = {
                    required: true
                };
                $("#form").validate().settings.rules[`tgl_nota[${rowCount}]`] = {
                    required: true
                };
                $("#form").validate().settings.rules[`jml[${rowCount}]`] = {
                    required: true
                };
                $("#form").validate().settings.rules[`qty[${rowCount}]`] = {
                    required: true,
                    min: 1
                };

                $(document).ready(function() {
                    // Event listener untuk input dengan ID yang dimulai dengan 'deklarasi'
                    $(document).on('input change', '[id^=deklarasi]', function() {
                        // Cek jika input memiliki value
                        if ($(this).val().trim() !== '') {
                            // Disable semua input kecuali input dengan ID 'deklarasi'
                            $(this).closest('tr').find('input').not(this).prop('disabled', true);
                        } else {
                            // Enable kembali semua input jika value dihapus
                            $(this).closest('tr').find('input').prop('disabled', false);
                        }
                    });
                });

                // Inisialisasi Datepicker pada elemen dengan id 'tgl_nota'
                $(document).on('focus', '.tgl_nota', function() {
                    $(this).datepicker({
                        dateFormat: 'dd-mm-yy', // Format default sementara
                        changeMonth: true,
                        changeYear: true,
                        onSelect: function(dateText, inst) {
                            // Hapus kelas error dan elemen pesan error saat tanggal dipilih
                            $(this).removeClass('is-invalid');

                            for (i = 1; i <= rowCount; i++) {
                                if ($(`#tgl_nota_${i}-error`).length) {
                                    $(`#tgl_nota_${i}-error`).remove();
                                }
                            }
                        }
                    }).datepicker('show');
                });
        }

        function deleteRow(id) {
                // Simpan ID dari row yang dihapus
                const rowId = $(`#row-${id}`).find('input:hidden[id^="hidden_detail_id"]').val();
                if (rowId) {
                    deletedRows.push(rowId);
                }

                $(`#row-${id}`).remove();
                // Reorder rows and update row numbers
                reorderRows();
                checkDeleteButtonState(); // Cek tombol delete setelah baris dihapus
                updateSubmitButtonState(); // Perbarui status tombol
                updateTotalNominal();
        }

        function reorderRows() {
                $('#input-container tr').each(function(index) {
                    const newRowNumber = index + 1;
                    const $row = $(this);
                    const detailIdValue = $row.find('input[name^="detail_id"]').val();
                    const pemakaianValue = $row.find('input[name^="pemakaian"]').val();
                    const inventoryIdValue = $row.find('input[name^="inventory_id"]').val();
                    const tgl_notaValue = $row.find('input[name^="tgl_nota"]').val();
                    const jmlValue = $row.find('input[name^="jml"]').val();
                    const jumlahValue = $row.find('input[name^="jumlah"]').val();
                    const qtyValue = $row.find('input[name^="qty"]').val();
                    const kwitansiValue = $row.find('input[name^="kwitansi"]').val();
                    const kwitansiImageValue = $row.find('input[name^="kwitansi_image"]').val();
                    const deklarasiValue = $row.find('input[name^="deklarasi"]').val();

                    $row.attr('id', `row-${newRowNumber}`);
                    $row.find('.row-number').text(newRowNumber);
                    $row.find('input[name^="detail_id"]').attr('name', `detail_id[${newRowNumber}]`).attr('placeholder', `detail_id ${newRowNumber}`).val(detailIdValue);

                    $row.find('.btn-toggle-pemakaian').attr('data-row', newRowNumber);
                    $row.find('[id^="mode-bebas-"]').attr('id', 'mode-bebas-' + newRowNumber);
                    $row.find('[id^="mode-barang-"]').attr('id', 'mode-barang-' + newRowNumber);
                    $row.find('[id^="barang_select"]').attr('id', 'barang_select' + newRowNumber);
                    $row.find('[id^="stok-info-"]').attr('id', 'stok-info-' + newRowNumber);
                    $row.find('[id^="pemakaian"]').attr('id', 'pemakaian' + newRowNumber)
                        .attr('name', 'pemakaian[' + newRowNumber + ']')
                        .attr('placeholder', 'Pemakaian ' + newRowNumber)
                        .val(pemakaianValue);
                    $row.find('[id^="inventory_id"]').attr('id', 'inventory_id' + newRowNumber)
                        .attr('name', 'inventory_id[' + newRowNumber + ']')
                        .val(inventoryIdValue);

                    $row.find('input[name^="tgl_nota"]').attr('name', `tgl_nota[${newRowNumber}]`)
                        .attr('id', `tgl_nota_${newRowNumber}`)
                        .attr('placeholder', `Tanggal Nota ${newRowNumber}`)
                        .val(tgl_notaValue);
                    $row.find('input[name^="jml"]').attr('name', `jml[${newRowNumber}]`)
                        .attr('id', `jumlah-${newRowNumber}`)
                        .attr('placeholder', `Jumlah ${newRowNumber}`)
                        .val(jmlValue);
                    $row.find('input[name^="jumlah"]').attr('name', `jumlah[${newRowNumber}]`)
                        .attr('id', `hidden_jumlah${newRowNumber}`)
                        .attr('placeholder', `Jumlah ${newRowNumber}`)
                        .val(jumlahValue);
                    $row.find('input[name^="qty"]').attr('name', `qty[${newRowNumber}]`)
                        .attr('id', `qty-${newRowNumber}`)
                        .val(qtyValue);
                    $row.find('input[name^="kwitansi"]').attr('name', `kwitansi[${newRowNumber}]`)
                        .attr('id', `inputGroupFile01${newRowNumber}`)
                        .attr('placeholder', `Input ${newRowNumber}`)
                        .val(kwitansiValue);
                    $row.find('[id^="upload"]').attr('for', `inputGroupFile01${newRowNumber}`)
                        .attr('id', `upload${newRowNumber}`);
                    $row.find('input[name^="kwitansi_image"]').attr('name', `kwitansi_image[${newRowNumber}]`)
                        .attr('placeholder', `Input ${newRowNumber}`)
                        .val(kwitansiImageValue);
                    $row.find('input[name^="deklarasi"]').attr('name', `deklarasi[${newRowNumber}]`)
                        .attr('id', `deklarasi${newRowNumber}`)
                        .attr('placeholder', `Input ${newRowNumber}`)
                        .val(deklarasiValue);
                    $row.find('.deklarasi-old').attr('name', `deklarasi_old[${newRowNumber}]`)
                        .attr('id', `deklarasi_old${newRowNumber}`)
                        .attr('placeholder', `Deklarasi Old${newRowNumber}`);
                    $row.find('[id^="deklarasi-modal"]').attr('id', `deklarasi-modal${newRowNumber}`)
                        .attr('data-id', newRowNumber);
                    $row.find('.delete-btn').attr('data-id', newRowNumber).text('Delete');
                });
                rowCount = $('#input-container tr').length; // Update rowCount to the current number of rows
                // Recalculate totals after reordering
                updateTotalNominal();
        }

        $('#add-row').click(function() {
                if ($('#sifat_pelaporan').val() === '') {
                    swal.fire({
                        icon: 'warning',
                        title: 'Sifat Pelaporan harus dipilih terlebih dahulu',
                        showConfirmButton: false,
                        timer: 2000
                    });
                    return;
                }
                addRow();
        });

        function updateSubmitButtonState() {
                const rowCount = $('#input-container tr').length;
                if (rowCount > 0) {
                    $('.aksi').prop('disabled', false).css('cursor', 'pointer'); // Enable submit button
                } else {
                    $('.aksi').prop('disabled', true); // Disable submit button
                }
        }

        function checkDeleteButtonState() {
                const rowCount = $('#input-container tr').length;
                if (rowCount === 1) {
                    $('#input-container .delete-btn').prop('disabled', true); // Disable delete button if only one row
                } else {
                    $('#input-container .delete-btn').prop('disabled', false); // Enable delete button if more than one row
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

        // Script file input
        $(document).on('change', '.custom-file-input', function() {
                let fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });

        $('#sifat_pelaporan').on('change', function() {
                var sifatPelaporan = $(this).val();

                if (sifatPelaporan == 'Reimbust') {
                    $('#tujuan').val('');
                    $('#jumlah_prepayment').val('');
                    $('#kode_prepayment_input').val('');
                    $('#tgl_pengajuan').val('');
                    applyModeChange('bebas'); // Set mode ke inputan bebas saat sifat pelaporan = reimbust
                } else if (sifatPelaporan == 'Pelaporan') {
                    $('#tgl_pengajuan').val('');
                    $('#kode_reimbust').val('');
                    $('#tujuan').val('');
                    $('#jumlah_prepayment').val('');
                } 

                // Hapus semua baris detail yang ada
                $('#input-container').empty();
                rowCount = 0;
                deletedRows = []; // reset deleted rows juga
                updateSubmitButtonState();
                reorderRows();
        });

        $('#sifat_pelaporan').on('input', function() {
                var sifatPelaporan = $(this).val();
                handleSifatPelaporanChange(sifatPelaporan);
        });

        // Event listener untuk perubahan pada select "sifat_pelaporan"
        function handleSifatPelaporanChange(sifatPelaporan) {
            if (aksi == 'add') {
                if (sifatPelaporan == 'Reimbust') {
                    $('#pelaporan_button').css('display', 'none');
                    $('#tgl_pengajuan').prop({
                        'disabled': false,
                        'readonly': false
                    }).css('cursor', 'pointer');
                    $('#tgl_pengajuan').css('pointer-events', 'auto');
                    $('#tujuan').prop({
                        'disabled': false,
                        'readonly': false
                    }).css('cursor', 'auto');
                    $('#status').prop({
                        'disabled': false,
                        'readonly': false
                    }).css('cursor', 'pointer');
                    $('.jml-pre-field').css({
                        'display': 'none'
                    }).css('cursor', 'not-allowed').val('0');
                    $('#hidden_jumlah_prepayment').val('0');
                    $('.kode_prepayment').css({
                        'display': 'none'
                    });
                    $('#mode-inputan').css('display', 'flex');
                } else if (sifatPelaporan == 'Pelaporan') {
                    $('#pelaporan_button').css('display', 'inline-block');
                    $('#mode-inputan').css('display', 'flex');
                    $('#parent_sifat_pelaporan').css('display', 'flex');
                    $('#tgl_pengajuan').prop({
                        'disabled': false,
                        'readonly': false
                    }).css('cursor', 'pointer');
                    $('#tgl_pengajuan').css('pointer-events', 'auto');
                    $('#tujuan').prop({
                        'disabled': false,
                        'readonly': true
                    }).css('cursor', 'not-allowed');
                    $('.kode_prepayment').css({
                        'display': 'flex'
                    });
                    $('#jumlah_prepayment').prop({
                        'disabled': false,
                        'readonly': true
                    }).css('cursor', 'not-allowed');
                } else if (sifatPelaporan == 'Pelaporan Kas') {
                    $('#pelaporan_button').css('display', 'none');
                    $('#tgl_pengajuan').prop({
                        'disabled': false,
                        'readonly': false
                    }).css('cursor', 'pointer');
                    $('#tgl_pengajuan').css('pointer-events', 'auto');
                    $('#tujuan').prop({
                        'disabled': false,
                        'readonly': false
                    }).css('cursor', 'auto');
                    $('#status').prop({
                        'disabled': false,
                        'readonly': false
                    }).css('cursor', 'pointer');
                    // $('.jml-pre-field').css({
                    //     'display': 'none'
                    // }).css('cursor', 'not-allowed').val('0');
                    $('#hidden_jumlah_prepayment').val('0');
                    $('.kode_prepayment').css({
                        'display': 'flex'
                    });
                    $('#jumlah_prepayment').prop({
                        'disabled': false,
                        'readonly': true
                    }).css('cursor', 'not-allowed');
                    $('#mode-inputan').css('display', 'flex');
                } else {
                    $('#mode-inputan').css('display', 'none');
                    $('#pelaporan_button').css('display', 'none');
                    $('#parent_sifat_pelaporan').css('display', 'inline-block');
                    $('#tgl_pengajuan').prop('disabled', true).css('cursor', 'not-allowed');
                    $('#tujuan').prop('disabled', true).css('cursor', 'not-allowed');
                    $('#status').prop('disabled', true).css('cursor', 'not-allowed');
                    $('#jumlah_prepayment').prop('disabled', true).css('cursor', 'not-allowed');
                    $('.kode_prepayment').css({
                        'display': 'none'
                    });
                }
            } else if (aksi == 'update') {
                if (sifatPelaporan == 'Reimbust') {
                    $('#sifat_pelaporan').prop('readonly', true).css({
                        'background-color': '#EAECF4',
                        'pointer-events': 'none',
                        'cursor': 'not-allowed'
                    });
                    $('#pelaporan_button').css('display', 'none');
                    $('#tgl_pengajuan').prop({
                        'disabled': false,
                        'readonly': false
                    }).css('cursor', 'pointer');
                    $('#tgl_pengajuan').css({
                        'pointer-events': 'none',
                        'background-color': '#EAECF4'
                    });
                    $('#tujuan').prop('readonly', false).css('cursor', 'auto');
                    $('#status').prop('readonly', false).css('cursor', 'pointer');
                    $('#jumlah_prepayment').prop({
                        'disabled': false,
                        'readonly': true
                    }).css('cursor', 'not-allowed').val('0');
                    $('#hidden_jumlah_prepayment').val('0');
                    $('.kode_prepayment').css({
                        'display': 'none'
                    });
                } else if (sifatPelaporan == 'Pelaporan') {
                    $('#sifat_pelaporan').prop('readonly', true).css({
                        'background-color': '#EAECF4',
                        'pointer-events': 'none',
                        'cursor': 'not-allowed'
                    });
                    $('#parent_sifat_pelaporan').css('display', 'flex');
                    $('#pelaporan_button').css('display', 'inline-block');
                    $('#tgl_pengajuan').prop({
                        'disabled': false,
                        'readonly': true
                    }).css('cursor', 'not-allowed');
                    $('#tgl_pengajuan').css('pointer-events', 'none');
                    $('#tujuan').prop({
                        'disabled': false,
                        'readonly': true
                    }).css('cursor', 'not-allowed');
                    $('#jumlah_prepayment').prop({
                        'disabled': false,
                        'readonly': true
                    }).css('cursor', 'not-allowed');
                    $('.kode_prepayment').css({
                        'display': 'flex'
                    });
                } else {
                    $('#tgl_pengajuan').prop('readonly', true).css('cursor', 'not-allowed');
                    $('#tujuan').prop('readonly', true).css('cursor', 'not-allowed');
                    $('#status').prop('readonly', true).css('cursor', 'not-allowed');
                    $('#jumlah_prepayment').prop('readonly', true).css('cursor', 'not-allowed');
                }
            }
        }
        setInterval(function() {
                var sifatPelaporan = $('#sifat_pelaporan').val();
                handleSifatPelaporanChange(sifatPelaporan);
        }, 01); // Memeriksa setiap detik
        // // Panggil change event secara manual untuk mengatur state awal saat halaman dimuat
        if (id == 0) {
                $('.aksi').text('Save');
                $('#sifat_pelaporan').prop('disabled', false).css('cursor', 'pointer');
                $('#kode_reimbust').val(kode).prop('readonly', true).css('cursor', 'not-allowed');
        } else {
                $('.aksi').text('Update');
                $('#sifat_pelaporan').prop('disabled', false).css('cursor', 'pointer');
                $('#kode_reimbust').val(kode).prop('readonly', true).css('cursor', 'not-allowed');
                $("select option[value='']").hide();
                $.ajax({
                    url: "<?= site_url('mac_reimbust/edit_data') ?>/" + id,
                    type: "GET",
                    dataType: "JSON",
                    success: function(data) {
                        moment.locale('id')

                        // PENGECEKAN APAKAH DATA TRANSAKSI ADA ATAU TIDAK
                        if (data.transaksi.length > 0) {
                            $('#form_loading').hide();
                            $('.aksi').prop('disabled', false);
                        }

                        if (data['master']['is_pelaporan_kas'] == 1) {
                            $('#sifat_pelaporan').val('Pelaporan Kas').trigger('change');
                            // Tunggu sebentar agar kas aktif selesai di-load
                            setTimeout(function() {
                                loadKasAktif(data['master']['kas_id']);
                            }, 300);
                        } else {
                            $('#toggle-kas').prop('checked', false);
                            $('#kas-detail').hide();
                            $('#info-kas').hide();
                        }

                        // Set nilai untuk setiap field dari data master    
                        $('#sifat_pelaporan').val(data['master']['sifat_pelaporan']);
                        $('#id').val(data['master']['id']);
                        $('#kode_reimbust').val(data['master']['kode_reimbust']).attr('readonly', true);
                        $('#tgl_pengajuan').val(moment(data['master']['tgl_pengajuan']).format('DD-MM-YYYY'));
                        $('#tujuan').val(data['master']['tujuan']);
                        $('#status').val(data['master']['status']);
                        $('#jumlah_prepayment').val(data['master']['jumlah_prepayment'].replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
                        $('#rekening').val(data['master']['no_rek']).trigger('change');
                        var parts = data['master']['no_rek'].split("-"); // Pisahkan berdasarkan "-"

                        if (parts.length === 3) {
                            $("#nama_rek").val(parts[0]);
                            $("#nama_bank").val(parts[1]);
                            $("#nomor_rekening").val(parts[2]);
                        }
                        $('#hidden_jumlah_prepayment').val(data['master']['jumlah_prepayment']);
                        $('#kode_prepayment_input').val(data['master']['kode_prepayment']);
                        $('#kode_prepayment_old').val(data['master']['kode_prepayment']);


                        if (aksi == 'update') {
                            // Set mode dari data yang sudah ada
                            // Deteksi dari baris pertama yang memiliki inventory_id
                            var detectedMode = 'bebas';
                            if (data.transaksi.length > 0 && data.transaksi[0].inventory_id) {
                                detectedMode = 'barang';
                            }

                            // Terapkan mode tanpa konfirmasi (karena ini load data, bukan user mengubah)
                            currentMode = detectedMode;
                            $('#input_mode').val(detectedMode);
                            if (detectedMode === 'barang') {
                                $('#btn-mode-bebas').removeClass('btn-primary').addClass('btn-outline-primary');
                                $('#btn-mode-barang').removeClass('btn-outline-success').addClass('btn-success active');
                            }
                            //APPEND DATA TRANSAKSI DETAIL REIMBUST
                            $(data['transaksi']).each(function(index) {
                                // Nilai jumlah diformat menggunakan pemisah ribuan sebelum dimasukkan ke dalam elemen input.
                                const jumlahFormatted = data['transaksi'][index]['jumlah']?.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') ?? '-';

                                const tglNotaFormatted = moment(data['transaksi'][index]['tgl_nota']).format('DD-MM-YYYY');

                                // Cek apakah deklarasi ada datanya
                                const isDeklarasiFilled = data['transaksi'][index]['deklarasi'] ? true : false;

                                // Append Dari Form UPDATE
                                const activeMode = data['transaksi'][index]['inventory_id'] ? 'barang' : 'bebas';

                                const row = `
                                    <tr id="row-${index + 1}">
                                        <td class="row-number">${index + 1}</td>
                                        <td style="width:100px; vertical-align:top; padding-top:10px;">
                                            ${buildModeToggle(index + 1, activeMode, $('#sifat_pelaporan').val())}
                                        </td>
                                        <td>
                                            ${buildPemakaianCell(index + 1, detectedMode, data['transaksi'][index]['inventory_id'], data['transaksi'][index]['pemakaian'])}
                                            <input type="hidden" id="hidden_reimbust_id${index}"
                                                name="reimbust_id" value="${data['master']['id']}">
                                            <input type="hidden" id="hidden_detail_id${index}"
                                                name="detail_id[${index + 1}]" value="${data['transaksi'][index]['id']}">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control tgl_nota ${isDeklarasiFilled ? 'not-allowed' : ''}"
                                                name="tgl_nota[${index + 1}]" id="tgl_nota_${index + 1}"
                                                style="cursor:${isDeklarasiFilled ? 'not-allowed' : 'pointer'};
                                                        pointer-events:${isDeklarasiFilled ? 'none' : 'auto'}"
                                                autocomplete="off" value="${tglNotaFormatted}"
                                                ${isDeklarasiFilled ? 'readonly' : ''}>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control jumlah-${index + 1}"
                                                id="jumlah-${index + 1}" value="${jumlahFormatted}"
                                                name="jml[${index + 1}]" autocomplete="off">
                                            <input type="hidden" class="hidden_jumlah" id="hidden_jumlah${index + 1}"
                                                name="jumlah[${index + 1}]"
                                                value="${data['transaksi'][index]['jumlah'] ?? ''}">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" id="qty-${index + 1}"
                                                name="qty[${index + 1}]" min="1"
                                                value="${data['transaksi'][index]['qty'] ?? 1}" autocomplete="off">
                                        </td>
                                        <td id="kwitansi-upload${index + 1}">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input"
                                                    name="kwitansi[${index + 1}]" id="inputGroupFile01${index + 1}">
                                                <label class="custom-file-label" for="inputGroupFile01${index + 1}"
                                                    id="upload${index + 1}">
                                                    ${data['transaksi'][index]['kwitansi'] || 'Upload..'}
                                                </label>
                                            </div>
                                            <input type="hidden" class="kwitansi_image${index + 1}" id="kwitansi_image"
                                                name="kwitansi_image[${index + 1}]"
                                                value="${data['transaksi'][index]['kwitansi'] || ''}">
                                            <span class="kwitansi-label">Max Size : 3MB</span>
                                        </td>
                                        <td width="125" style="padding:16px 10px !important">
                                            <div class="btn btn-primary btn-lg btn-block btn-sm"
                                                data-toggle="modal" data-target="#deklarasiModal"
                                                data-id="${index + 1}" id="deklarasi-modal${index + 1}">
                                                ${data['transaksi'][index]['deklarasi'] || 'Deklarasi'}
                                            </div>
                                            <input type="hidden" id="deklarasi${index + 1}"
                                                name="deklarasi[${index + 1}]" autocomplete="off"
                                                value="${data['transaksi'][index]['deklarasi'] || ''}">
                                            <input type="hidden" class="deklarasi-old" id="deklarasi_old${index + 1}"
                                                name="deklarasi_old[${index + 1}]" autocomplete="off"
                                                value="${data['transaksi'][index]['deklarasi'] || ''}">
                                        </td>
                                        <td>
                                            <span class="btn delete-btn btn-danger" data-id="${index + 1}">Delete</span>
                                        </td>
                                    </tr>`;

                                // Setelah append, init select2 jika mode barang
                                if (detectedMode === 'barang' && data['transaksi'][index]['inventory_id']) {
                                    initBarangSelect2Reimbust(index + 1);
                                    var opt = new Option(
                                        data['transaksi'][index]['pemakaian'],
                                        data['transaksi'][index]['inventory_id'],
                                        true, true
                                    );
                                    $('#barang_select' + (index + 1)).append(opt).trigger('change.select2');
                                    $('#stok-info-' + (index + 1))
                                        .html('<span style="color:#6c757d; font-size:11px;">Data tersimpan sebelumnya</span>')
                                        .show();
                                }
                                $('#input-container').append(row);
                                // if (data['master']['sifat_pelaporan'] == 'Pelaporan') {
                                //     initPemakaianSelect2(index + 1);
                                // }

                                // Tambahkan format ke input jumlah yang baru
                                formatJumlahInput(`#jumlah-${index + 1}`);

                                //VALIDASI ROW YANG TELAH DI APPEND
                                $("#form").validate().settings.rules[`pemakaian[${index + 1}]`] = {
                                    required: true
                                };
                                $("#form").validate().settings.rules[`tgl_nota[${index + 1}]`] = {
                                    required: true
                                };
                                $("#form").validate().settings.rules[`jml[${index + 1}]`] = {
                                    required: true
                                };
                                $("#form").validate().settings.rules[`qty[${index + 1}]`] = {
                                    required: true,
                                    min: 1
                                };
                                rowCount = index + 1;

                                $(document).ready(function() {
                                    // Cek nilai input dan hapus value serta tambahkan placeholder jika perlu
                                    $('input[id^="jumlah-"]').each(function() {
                                        var $input = $(this);
                                        var value = $input.val();

                                        if (value == '0') {
                                            $input.val(''); // Hapus nilai input
                                            $input.attr('placeholder', 'Deklarasi'); // Tambahkan placeholder
                                        }
                                    });
                                });

                                $(document).ready(function() {
                                    // Cek nilai label dan lakukan tindakan jika nilainya adalah "null"
                                    $('label[id^="upload"]').each(function() {
                                        var $label = $(this);
                                        var text = $label.text().trim(); // Ambil teks dari label

                                        if (text === 'null') {
                                            $label.text('Deklarasi'); // Hapus teks label
                                        }
                                    });
                                });

                                $(document).ready(function() {
                                    // Iterasi setiap baris transaksi
                                    $('tr[id^="row-"]').each(function() {
                                        var index = $(this).attr('id').replace('row-', ''); // Ambil indeks dari ID elemen
                                        var deklarasiValue = $('#deklarasi' + index).val(); // Ambil nilai deklarasi

                                        // Jika deklarasi kosong, buat input lainnya readonly
                                        if (deklarasiValue !== '') {
                                            $(this).find('input[type="text"]').attr('readonly', true); // Buat semua input teks dalam baris ini readonly
                                            $(this).find('.custom-file-input').attr('disabled', true); // Disable input file
                                            $(this).find('.btn-primary').attr('disabled', true); // Disable tombol modal deklarasi
                                        }
                                    });
                                });

                                updateTotalNominal();

                                // Inisialisasi Datepicker pada elemen dengan id 'tgl_nota'
                                $(document).on('focus', '.tgl_nota', function() {
                                    $(this).datepicker({
                                        dateFormat: 'dd-mm-yy', // Format default sementara
                                        changeMonth: true,
                                        changeYear: true,
                                        onSelect: function(dateText, inst) {
                                            // Hapus kelas error dan elemen pesan error saat tanggal dipilih
                                            $(this).removeClass('is-invalid');

                                            for (i = 1; i <= rowCount; i++) {
                                                if ($(`#tgl_nota_${i}-error`).length) {
                                                    $(`#tgl_nota_${i}-error`).remove();
                                                }
                                            }
                                        }
                                    }).datepicker('show');
                                });
                            });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error get data from ajax');
                    }
                });
        }

        // function validateKas() {
        //     if ($('#sifat_pelaporan').val() !== 'Pelaporan Kas') return true;

        //     if (!$('#kas_id').val()) {
        //         Swal.fire({
        //             icon: 'error',
        //             title: 'Kas Tidak Tersedia',
        //             text: 'Tidak ada kas aktif di cabang ini. Ajukan prepayment kas terlebih dahulu.'
        //         });
        //         return false;
        //     }

        //     var sisa = parseFloat($('#kas-sisa').text().replace(/[^0-9]/g, '')) || 0;
        //     var totalNominal = parseInt($('#total_nominal_hidden').val()) || 0;

        //     if (totalNominal > sisa) {
        //         Swal.fire({
        //             icon: 'error',
        //             title: 'Melebihi Sisa Kas',
        //             html: 'Total pelaporan <b>Rp ' + totalNominal.toLocaleString('id-ID') + '</b>' +
        //                 ' melebihi sisa kas <b>Rp ' + sisa.toLocaleString('id-ID') + '</b>.'
        //         });
        //         return false;
        //     }

        //     return true;
        // }
        
        $("#form").submit(function(e) {
                e.preventDefault();
                var $form = $(this);
                if (!$form.valid()) return false;

                var url;
                if (id == 0) {
                    url = "<?php echo site_url('mac_reimbust/add') ?>";
                } else {
                    url = "<?php echo site_url('mac_reimbust/update') ?>";
                }

                var formData = new FormData(this);

                // Tampilkan loading
                $('#loading').show();

                $.ajax({
                    url: url,
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: "JSON",
                    success: function(data) {

                        // Sembunyikan loading saat respons diterima
                        $('#loading').hide();

                        if (data.status) {
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'Your data has been saved',
                                showConfirmButton: false,
                                timer: 1500
                            }).then((result) => {
                                location.href = "<?= base_url('mac_reimbust') ?>";
                            });
                        } else {
                            // Sembunyikan loading saat respons diterima
                            $('#loading').hide();

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
                    sifat_pelaporan: {
                        required: true,
                    },
                    tgl_pengajuan: {
                        required: true,
                    },
                    tujuan: {
                        required: true,
                    },
                    status: {
                        required: true,
                    },
                    jumlah: {
                        required: true,
                    },
                    rekening: {
                        required: true,
                    },
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
                    sifat_pelaporan: {
                        required: "Pilih Sifat Pelaporan!",
                    },
                    tgl_pengajuan: {
                        required: "Tanggal Pengajuan is required",
                    },
                    tujuan: {
                        required: "Tujuan is required",
                    },
                    status: {
                        required: "Status is required",
                    },
                    jumlah: {
                        required: "Jumlah Prepayment is required",
                    },
                    rekening: {
                        required: "Rekening is required",
                    },
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
    });

</script>