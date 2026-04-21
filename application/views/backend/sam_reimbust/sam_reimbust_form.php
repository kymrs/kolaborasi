<!-- Style table transaksi -->
<link rel="stylesheet" href="<?= base_url("assets/backend/css/table-transaksi-reimbust.css") ?>">

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title_view ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header text-right">
                    <a class="btn btn-primary btn-sm btn-style" href="<?= base_url('sam_reimbust') ?>"><i class="fas fa-chevron-left"></i>&nbsp;Back</a>
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
                                        </select>
                                        <div class="btn btn-primary btn-small btn-style btn-search-prepayment" data-toggle="modal" data-target="#pelaporanModal" id="pelaporan_button" style="margin-left: 7px;"><i class="fas fa-solid fa-search"></i></div>
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
                                <div class="form-group row kode-pre-field" id="kode_prepayment">
                                    <label class="col-sm-4">Kode Prepayment</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" readonly placeholder="Kode Prepayment" name="kode_prepayment" id="kode_prepayment_input" style="cursor: not-allowed;">
                                        <input type="hidden" class="form-control" id="kode_prepayment_old" name="kode_prepayment_old">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- BUTTON TAMBAH FORM -->
                        <div class="mt-3">
                            <button type="button" class="btn-style" id="add-row"><i class="fa fa-plus" aria-hidden="true"></i> Add</button>
                        </div>
                        <!-- TABLE INPUT -->
                        <div class="mt-2 table-transaksi">
                            <table class="table">
                                <thead class="header-table-transaksi">
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Pemakaian</th>
                                        <th scope="col">Tanggal Nota</th>
                                        <th scope="col">Jumlah Nominal</th>
                                        <th scope="col">Kwitansi</th>
                                        <th scope="col">Deklarasi</th>
                                        <th scope="col" id="action">Action</th>
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

                        <!-- Modal Input Form Deklarasi -->
                        <div class="modal fade" id="deklarasiModal" tabindex="-1" aria-labelledby="deklarasiModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deklarasiModalLabel">Tambah Deklarasi</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="modalDeklarasiForm">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-5" for="tgl_deklarasi_modal">Tanggal</label>
                                                        <div class="col-sm-7">
                                                            <div class="input-group date">
                                                                <input type="text" class="form-control" name="tgl_deklarasi_modal" id="tgl_deklarasi_modal" placeholder="DD-MM-YYYY" autocomplete="off" readonly />
                                                                <div class="input-group-append">
                                                                    <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-5" for="kode_deklarasi_modal">Kode Deklarasi</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" id="kode_deklarasi_modal" name="kode_deklarasi_modal" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-5" for="nama_penerima_modal">Nama Penerima </label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" id="nama_penerima_modal" name="nama_penerima_modal" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-5" for="tujuan_modal">Tujuan</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" id="tujuan_modal" name="tujuan_modal" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-5" for="sebesar_modal">Sebesar</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" name="sebesar_modal" id="sebesar_modal" required>
                                                            <input type="hidden" class="form-control" id="hidden_sebesar_modal" name="hidden_sebesar_modal">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                        <button type="button" class="btn btn-primary" id="saveDeklarasiModal">Simpan</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal -->
                        <div id="myModal" class="kwitansi-modal">
                            <span class="close">&times;</span>
                            <img class="modal-content" id="img01">
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
    $('#tgl_pengajuan').datepicker({
        dateFormat: 'dd-mm-yy',
        minDate: new Date(),
        maxDate: new Date(),

        // MENGENERATE KODE PREPAYMENT SETELAH PILIH TANGGAL
        onSelect: function(dateText) {
            var id = dateText;
            $('#tgl_pengajuan').removeClass("is-invalid");

            // Menghapus label error secara manual jika ada
            if ($("#tgl_pengajuan-error").length) {
                $("#tgl_pengajuan-error").remove(); // Menghapus label error
            }
            $.ajax({
                url: "<?php echo site_url('sam_reimbust/generate_kode') ?>",
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
    $('.js-example-basic-single').select2();

    // Fungsi untuk mengatur enabled/disabled elemen berdasarkan radio button yang dipilih
    function toggleInputs() {
        const isExistChecked = $('#exist').is(':checked');

        if (isExistChecked) {
            $('#rekening').prop('disabled', false).show();
            $('#rekening').next('.select2-container').show();
            $('.input-group.rekening-text input[type="text"]').prop('disable', true).parent().hide();
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
        if (value.length > 14) {
            value = value.slice(0, 10);
        }
        this.value = value;
    });

    // Data table prepayment
    var table;

    // METHOD POST MENAMPILKAN DATA KE DATA TABLE
    $(document).ready(function() {
        var table = $('#prepayment-table').DataTable({
            "responsive": true,
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('sam_reimbust/get_list3') ?>",
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
                }, {
                    "targets": [0, 1],
                    "orderable": false,
                },
            ]
        });

        // Event listener untuk baris tabel dalam modal
        $('#prepayment-table tbody').on('click', 'tr', function() {
            // Ambil data dari baris yang diklik
            let data = table.row(this).data();

            // Masukkan data ke dalam input form di tampilan utama
            $('#kode_prepayment_input').val(data[2]);
            $('#departemenPrepayment').val(data[4]);
            $('#jumlah_prepayment').val(data[8]);
            var cleanedValue = data[8].replace(/\./g, '');
            $('#hidden_jumlah_prepayment').val(cleanedValue);
            $('#tujuan').val(data[7]);

            // Tutup modal setelah data dipilih
            $('#pelaporanModal').modal('hide');
        });
    });

    // Modal deklarasi simple input & buffer

    // buffer container (keyed by kode deklarasi instead of row number)
    let deklarasiBuffer = {};
    let currentDeklarasiRow = null;
    let lastDeklarasiKode = null;
    let deklarasiCounter = 0;
    let deklarasiPrefix = '';
    let isNewKodeGenerated = false; // Track if new kode was generated from generateNextKode()

    // Function to generate next kode based on counter
    function generateNextKode() {
        deklarasiCounter++;
        return deklarasiPrefix + deklarasiCounter.toString().padStart(3, '0');
    }

    // show modal and prefill if already buffered
    function openDeklarasiModal(rowId) {
        currentDeklarasiRow = rowId;
        const currentKode = $(`#deklarasi${rowId}`).val();
        const existing = currentKode ? deklarasiBuffer[currentKode] : null;
        if (existing) {
            // use buffer data keyed by kode
            $('#tgl_deklarasi_modal').val(existing.tgl);
            $('#kode_deklarasi_modal').val(existing.kode);
            $('#nama_penerima_modal').val(existing.nama);
            $('#tujuan_modal').val(existing.tujuan);
            $('#sebesar_modal').val(existing.sebesar);
            $('#hidden_sebesar_modal').val(existing.hidden);
            $('#deklarasiModal').modal('show');
        } else if (currentKode) {
            // fetch from server if kode already set (existing declaration)
            $.ajax({
                url: "<?php echo site_url('sam_datadeklarasi/get_by_kode') ?>",
                type: 'POST',
                data: { kode_deklarasi: currentKode },
                dataType: 'JSON',
                success: function(res) {
                    if (res.status) {
                        const d = res.data;
                        $('#tgl_deklarasi_modal').val(moment(d.tgl_deklarasi).format('DD-MM-YYYY'));
                        $('#kode_deklarasi_modal').val(d.kode_deklarasi);
                        $('#nama_penerima_modal').val(d.nama_dibayar);
                        $('#tujuan_modal').val(d.tujuan);
                        $('#sebesar_modal').val(d.sebesar.replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
                        $('#hidden_sebesar_modal').val(d.sebesar);
                    }
                    $('#deklarasiModal').modal('show');
                },
                error: function() {
                    // fallback: just show empty
                    $('#tgl_deklarasi_modal').val('');
                    $('#kode_deklarasi_modal').val(currentKode);
                    $('#nama_penerima_modal').val('');
                    $('#tujuan_modal').val('');
                    $('#sebesar_modal').val('');
                    $('#hidden_sebesar_modal').val('');
                    $('#deklarasiModal').modal('show');
                }
            });
        } else {
            $('#tgl_deklarasi_modal').val('');
            $('#kode_deklarasi_modal').val('');
            $('#nama_penerima_modal').val('');
            $('#tujuan_modal').val('');
            $('#sebesar_modal').val('');
            $('#hidden_sebesar_modal').val('');
            // If not first row, generate next kode sequentially
            if (rowId > 1) {
                $('#kode_deklarasi_modal').val(generateNextKode());
                isNewKodeGenerated = true; // Mark that generateNextKode was called
            }
            $('#deklarasiModal').modal('show');
        }
    }

    // attach to dynamic buttons
    $(document).on('click', '[id^=deklarasi-modal]', function() {
        const row = $(this).data('id');
        openDeklarasiModal(row);
    });

    // generate kode ketika tanggal dipilih
    $(document).on('focus', '#tgl_deklarasi_modal', function() {
        $(this).datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            zIndex: 99999,
            appendTo: 'body',
            beforeShow: function() {
                $('#deklarasiModal').on('hide.bs.modal.datepicker', function(e) {
                    if ($('.ui-datepicker').is(':visible')) {
                        e.preventDefault();
                    }
                });
            },
            onClose: function() {
                $('#deklarasiModal').off('hide.bs.modal.datepicker');
            },
            onSelect: function(dateText) {
                // Only generate kode if not already set (for sequential rows)
                if (!$('#kode_deklarasi_modal').val()) {
                    $.ajax({
                        url: "<?php echo site_url('sam_datadeklarasi/generate_kode') ?>",
                        type: "POST",
                        data: { date: dateText },
                        dataType: "JSON",
                        success: function(data) {
                            const kode = data.toUpperCase();
                            $('#kode_deklarasi_modal').val(kode);
                            // Set prefix and counter for sequential generation
                            deklarasiPrefix = kode.substring(0, kode.length - 3);
                            deklarasiCounter = parseInt(kode.substring(kode.length - 3));
                        },
                        error: function(error) {
                            alert("error" + error);
                        }
                    });
                }
            }
        }).datepicker('show');
    });

    // formatting besar in modal
    $('#sebesar_modal').on('input', function() {
        let value = $(this).val().replace(/[^,\d]/g, '');
        let parts = value.split(',');
        let integerPart = parts[0];
        integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        $(this).val(parts[1] !== undefined ? integerPart + ',' + parts[1] : integerPart);
        $('#hidden_sebesar_modal').val(value.replace(/\./g, ''));
    });

    // save button in modal
    $('#saveDeklarasiModal').on('click', function() {
        // basic validation
        if (!$('#tgl_deklarasi_modal').val() || !$('#kode_deklarasi_modal').val() || !$('#nama_penerima_modal').val() || !$('#tujuan_modal').val() || !$('#sebesar_modal').val()) {
            swal.fire('Error', 'Semua field wajib diisi', 'error');
            return;
        }
        const data = {
            tgl: $('#tgl_deklarasi_modal').val(),
            kode: $('#kode_deklarasi_modal').val(),
            nama: $('#nama_penerima_modal').val(),
            tujuan: $('#tujuan_modal').val(),
            sebesar: $('#sebesar_modal').val(),
            hidden: $('#hidden_sebesar_modal').val()
        };
        // remove old kode from buffer if changed
        const oldKode = $(`#deklarasi${currentDeklarasiRow}`).val();
        if (oldKode && oldKode !== data.kode && deklarasiBuffer[oldKode]) {
            delete deklarasiBuffer[oldKode];
        }
        // store by kode - replaces existing entry if same kode reused
        deklarasiBuffer[data.kode] = data;
        // update hidden field in row so controller still receives kode
        $(`#deklarasi${currentDeklarasiRow}`).val(data.kode);

        // Fill the row inputs
        $(`#pemakaian${currentDeklarasiRow}`).val(data.tujuan); // pemakaian = tujuan
        $(`#tgl_nota_${currentDeklarasiRow}`).val(data.tgl); // tgl_nota = tanggal_deklarasi
        $(`#jumlah-${currentDeklarasiRow}`).val(data.sebesar); // jumlah_nominal = Sebesar
        $(`#hidden_jumlah${currentDeklarasiRow}`).val(data.hidden); // hidden jumlah

        // Disable kwitansi input and change label to "Deklarasi"
        $(`#inputGroupFile01${currentDeklarasiRow}`).prop('disabled', true);
        $(`#upload${currentDeklarasiRow}`).text('Deklarasi');

        // Change button text to kode deklarasi
        $(`#deklarasi-modal${currentDeklarasiRow}`).text(data.kode);

        // Disable other inputs in row
        $(`#pemakaian${currentDeklarasiRow}`).prop('readonly', true);
        $(`#tgl_nota_${currentDeklarasiRow}`).prop('readonly', true);
        $(`#jumlah-${currentDeklarasiRow}`).prop('readonly', true);

        // Reset flags and modal form after save
        isNewKodeGenerated = false; // Reset flag after save
        $('#tgl_deklarasi_modal').val('');
        $('#kode_deklarasi_modal').val('');
        $('#nama_penerima_modal').val('');
        $('#tujuan_modal').val('');
        $('#sebesar_modal').val('');
        $('#hidden_sebesar_modal').val('');

        $('#deklarasiModal').modal('hide');
    });

    // Handle modal close without save - decrement counter if generateNextKode was used for non-first row
    $('#deklarasiModal').on('hidden.bs.modal', function() {
        if (isNewKodeGenerated && currentDeklarasiRow > 1) {
            deklarasiCounter--; // Decrement counter if generateNextKode was called but user closed modal
            isNewKodeGenerated = false; // Reset flag
        }
    });

    // when main form is submitted, append deklarasi buffer as hidden input
    $('#form').on('submit', function(ev) {
        // add buffer data only for codes that still exist in form
        const filtered = {};
        Object.entries(deklarasiBuffer).forEach(([kode, data]) => {
            // check any input for deklarasi contains this kode
            if ($(`input[name^=\"deklarasi\"][value=\"${kode}\"]`).length) {
                filtered[kode] = data;
            }
        });
        const json = JSON.stringify(filtered);
        if (json && json !== '{}' && json !== '{}') {
            $('<input>').attr({
                type: 'hidden',
                name: 'deklarasi_data',
                value: json
            }).appendTo('#form');
        }
    });

    $(document).ready(function() {
        var id = $('#id').val();
        var aksi = $('#aksi').val();
        var kode = $('#kode').val();
        var sifat_pelaporan = $('#sifat_pelaporan').val();
        let inputCount = 0;
        let deletedRows = [];

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
            });
        }

        //MENAMBAH FORM INPUTAN DI ADD FORM
        let rowCount = 0;

        // Append dari form ADD
        function addRow() {
            rowCount++;
            const row = `
                <tr id="row-${rowCount}">
                    <td class="row-number">${rowCount}</td>
                    <td><input type="text" class="form-control" name="pemakaian[${rowCount}]" value="" placeholder="Pemakaian ${rowCount}"  autocomplete="off" id="pemakaian${rowCount}"></td>
                    <td>
                        <input type="text" class="form-control tgl_nota" name="tgl_nota[${rowCount}]" id="tgl_nota_${rowCount}" style="cursor: pointer" autocomplete="off" placeholder="Tanggal Nota ${rowCount}">
                    </td>
                    <td>
                        <input type="text" class="form-control" id="jumlah-${rowCount}" placeholder="Jumlah ${rowCount}" name="jml[${rowCount}]" autocomplete="off">
                        <input type="hidden" id="hidden_jumlah${rowCount}" name="jumlah[${rowCount}]" value="">
                    </td>
                    <td style="padding: 12px 12px 5px !important" id="kwitansi-upload${rowCount}">
                        <div class="custom-file">
                            <input type="file" required class="custom-file-input" name="kwitansi[${rowCount}]" id="inputGroupFile01${rowCount}" aria-describedby="inputGroupFileAddon01">
                            <label class="custom-file-label" for="inputGroupFile01" id="upload${rowCount}">Upload..</label>
                            <span class="kwitansi-label">Max Size : 3MB</span>
                        </div>
                    </td>
                    <td width="150" style="padding: 15px 10px">
                        <div class="btn btn-primary btn-lg btn-block btn-sm btn-style" data-toggle="modal" data-target="#deklarasiModal" data-id="${rowCount}" id="deklarasi-modal${rowCount}">Deklarasi</div>
                        <input type="hidden" class="form-control" id="deklarasi${rowCount}" placeholder="Deklarasi ${rowCount}" name="deklarasi[${rowCount}]" autocomplete="off">
                        <input type="hidden" class="form-control deklarasi-old" id="deklarasi_old${rowCount}" placeholder="Deklarasi Old${rowCount}" name="deklarasi_old[${rowCount}]" autocomplete="off" value="">
                    </td>
                    <td><span class="btn delete-btn btn-danger btn-style btn-delete" data-id="${rowCount}">Delete</span></td>
                </tr>
                `;
            $('#input-container').append(row);
            // Tambahkan format ke input jumlah yang baru
            formatJumlahInput(`#jumlah-${rowCount}`);
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
            const rowId = $(`#row-${id}`).find('input:hidden[id^="hidden_detail_id"]').val();
            if (rowId) {
                deletedRows.push(rowId);
            }

            $(`#row-${id}`).remove();

            // 🔥 WAJIB: hapus juga dari buffer dulu
            const kode = $(`#deklarasi${id}`).val();
            if (kode && deklarasiBuffer[kode]) {
                delete deklarasiBuffer[kode];
            }

            // 🔥 Reorder setelah delete
            reorderDeklarasiBufferAfterDelete();

            reorderRows();
            checkDeleteButtonState();
            updateSubmitButtonState();
        }

        function reorderRows() {
            $('#input-container tr').each(function(index) {
                const newRowNumber = index + 1;
                const detailIdValue = $(this).find('input[name^="detail_id"]').val();
                const pemakaianValue = $(this).find('input[name^="pemakaian"]').val();
                const tgl_notaValue = $(this).find('input[name^="tgl_nota"]').val();
                const jmlValue = $(this).find('input[name^="jml"]').val();
                const jumlahValue = $(this).find('input[name^="jumlah"]').val();
                const kwitansiValue = $(this).find('input[name^="kwitansi"]').val();
                const kwitansiImageValue = $(this).find('#kwitansi_image').val();
                const deklarasiValue = $(this).find('input[name^="deklarasi"]').val();

                $(this).attr('id', `row-${newRowNumber}`);
                $(this).find('.row-number').text(newRowNumber);
                $(this).find('input[name^="detail_id"]').attr('name', `detail_id[${newRowNumber}]`).attr('placeholder', `detail_id ${newRowNumber}`).val(detailIdValue);
                $(this).find('input[name^="pemakaian"]').attr('name', `pemakaian[${newRowNumber}]`).attr('placeholder', `Pemakaian ${newRowNumber}`).val(pemakaianValue);
                $(this).find('input[name^="tgl_nota"]').attr('name', `tgl_nota[${newRowNumber}]`).attr('placeholder', `Tanggal Nota ${newRowNumber}`).val(tgl_notaValue);
                $(this).find('input[name^="jml"]').attr('name', `jml[${newRowNumber}]`).attr('placeholder', `Jumlah ${newRowNumber}`).val(jmlValue);
                $(this).find('input[name^="jumlah"]').attr('name', `jumlah[${newRowNumber}]`).attr('placeholder', `Jumlah ${newRowNumber}`).val(jumlahValue);
                $(this).find('input[name^="kwitansi"]').attr('name', `kwitansi[${newRowNumber}]`).attr('placeholder', `Input ${newRowNumber}`);
                $(this).find('#kwitansi_image').attr('name', `kwitansi_image[${newRowNumber}]`).attr('placeholder', `Input ${newRowNumber}`).val(kwitansiImageValue);
                $(this).find('input[name^="deklarasi"]').attr('name', `deklarasi[${newRowNumber}]`).attr('placeholder', `Deklarasi ${newRowNumber}`).val(deklarasiValue);
                $(this).find('.deklarasi-old').attr('name', `deklarasi_old[${newRowNumber}]`).attr('placeholder', `Deklarasi Old${newRowNumber}`);
                $(this).find('.delete-btn').attr('data-id', newRowNumber).text('Delete');
            });
            rowCount = $('#input-container tr').length; // Update rowCount to the current number of rows
        }

        function reorderDeklarasiBufferAfterDelete() {
            const entries = Object.entries(deklarasiBuffer);

            if (!entries.length) return;

            // Ambil prefix dari key pertama
            const firstKey = entries[0][0];
            const prefix = firstKey.slice(0, 5);

            // Urutin berdasarkan angka
            const sorted = entries
                .map(([key, value]) => ({
                    oldKey: key,
                    value: value,
                    number: parseInt(key.slice(5))
                }))
                .sort((a, b) => a.number - b.number);

            // Ambil angka awal
            let start = sorted[0].number;

            const newBuffer = {};
            const mapping = {};

            sorted.forEach((item, index) => {
                const newNumber = start + index;
                const newKey = prefix + String(newNumber).padStart(3, '0');

                // 🔥 update isi object juga
                item.value.kode = newKey;

                newBuffer[newKey] = item.value;
                mapping[item.oldKey] = newKey;
            });

            deklarasiBuffer = newBuffer;

            // 🔥 update input di DOM (penting!)
            Object.entries(mapping).forEach(([oldKey, newKey]) => {
                $(`input[name^="deklarasi"][value="${oldKey}"]`).val(newKey);
            });
        }

        $('#add-row').click(function() {
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
            } else if (sifatPelaporan != 'Reimbust' && sifatPelaporan != 'Pelaporan') {
                $('#tgl_pengajuan').val('');
                $('#kode_reimbust').val('');
                $('#tujuan').val('');
                $('#jumlah_prepayment').val('');
            }

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
                    $('#jumlah_prepayment').prop({
                        'disabled': false,
                        'readonly': true
                    }).css('cursor', 'not-allowed').val('0');
                    $('#hidden_jumlah_prepayment').val('0');
                    $('#kode_prepayment').css({
                        'display': 'none'
                    });
                } else if (sifatPelaporan == 'Pelaporan') {
                    $('#pelaporan_button').css('display', 'inline-block');
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
                    $('#kode_prepayment').css({
                        'display': 'flex'
                    });
                    $('#jumlah_prepayment').prop({
                        'disabled': false,
                        'readonly': true
                    }).css('cursor', 'not-allowed');
                } else {
                    $('#pelaporan_button').css('display', 'none');
                    $('#parent_sifat_pelaporan').css('display', 'inline-block');
                    $('#tgl_pengajuan').prop('disabled', true).css('cursor', 'not-allowed');
                    $('#tujuan').prop('disabled', true).css('cursor', 'not-allowed');
                    $('#status').prop('disabled', true).css('cursor', 'not-allowed');
                    $('#jumlah_prepayment').prop('disabled', true).css('cursor', 'not-allowed');
                    $('#kode_prepayment').css({
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
                    $('#kode_prepayment').css({
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
                    $('#kode_prepayment').css({
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
                url: "<?= site_url('sam_reimbust/edit_data') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    moment.locale('id')
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
                        //APPEND DATA TRANSAKSI DETAIL REIMBUST
                        $(data['transaksi']).each(function(index) {
                            // Nilai jumlah diformat menggunakan pemisah ribuan sebelum dimasukkan ke dalam elemen input.
                            const jumlahFormatted = data['transaksi'][index]['jumlah'].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                            const tglNotaFormatted = moment(data['transaksi'][index]['tgl_nota']).format('DD-MM-YYYY');

                            // Cek apakah deklarasi ada datanya
                            const isDeklarasiFilled = data['transaksi'][index]['deklarasi'] ? true : false;

                            // Append Dari Form UPDATE
                            const row = `
                                <tr id="row-${index + 1}">
                                    <td class="row-number">${index + 1}</td>
                                    <td>
                                        <input type="text" class="form-control" name="pemakaian[${index + 1}]" value="${data['transaksi'][index]['pemakaian']}" id="pemakaian${index + 1}" autocomplete="off" placeholder="${data['transaksi'][index]['pemakaian'] ? data['transaksi'][index]['pemakaian'] : 'Deklarasi'}">
                                        
                                        <input type="hidden" id="hidden_reimbust_id${index}" name="reimbust_id" value="${data['master']['id']}">
                                        <input type="hidden" id="hidden_detail_id${index}" name="detail_id[${index + 1}]" value="${data['transaksi'][index]['id']}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control tgl_nota ${isDeklarasiFilled ? 'not-allowed' : ''}" name="tgl_nota[${index + 1}]" id="tgl_nota_${index + 1}" 
                                            style="cursor: ${isDeklarasiFilled ? 'not-allowed' : 'pointer'}; pointer-events: ${isDeklarasiFilled ? 'none' : 'auto'}" 
                                            autocomplete="off" value="${tglNotaFormatted}" ${isDeklarasiFilled ? 'readonly' : ''}>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control jumlah-${index + 1}" id="jumlah-${index}" value="${jumlahFormatted}" name="jml[${index + 1}]" autocomplete="off">
                                        <input class="hidden_jumlah${index + 1}" type="hidden" id="hidden_jumlah${index}" name="jumlah[${index + 1}]" value="${data['transaksi'][index]['jumlah']}">
                                    </td>
                                    <td id="kwitansi-upload${index + 1}">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="kwitansi[${index + 1}]" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">
                                            <label class="custom-file-label" for="inputGroupFile01" id="upload${index + 1}">${data['transaksi'][index]['kwitansi'] ? data['transaksi'][index]['kwitansi'] : 'Deklarasi'}</label>
                                        </div>
                                        <input type="hidden" class="form-control kwitansi_image${index + 1}" id="kwitansi_image" name="kwitansi_image[${index + 1}]" value="${data['transaksi'][index]['kwitansi'] ? data['transaksi'][index]['kwitansi'] : ''}">
                                        <span class="kwitansi-label">Max Size : 3MB</span>
                                    </td>
                                    <td width="125" style="padding: 16px 10px !important">
                                        <div class="btn btn-primary btn-lg btn-block btn-sm" 
                                            data-toggle="modal" 
                                            data-target="#deklarasiModal" 
                                            data-id="${index + 1}" 
                                            id="deklarasi-modal${index + 1}">
                                            ${data['transaksi'][index]['deklarasi'] ? data['transaksi'][index]['deklarasi'] : 'Deklarasi'}
                                        </div>
                                        <input type="hidden" class="form-control" id="deklarasi${index + 1}" placeholder="Deklarasi ${index + 1}" name="deklarasi[${index + 1}]" autocomplete="off" value="${data['transaksi'][index]['deklarasi']}">
                                        <input type="hidden" class="form-control deklarasi-old" id="deklarasi_old${index + 1}" placeholder="Deklarasi Old${index + 1}" name="deklarasi_old[${index + 1}]" autocomplete="off" value="${data['transaksi'][index]['deklarasi']}">
                                    </td>
                                    <td><span class="btn delete-btn btn-danger" data-id="${index + 1}">Delete</span></td>
                                </tr>
                            `;
                            $('#input-container').append(row);
                            // Tambahkan format ke input jumlah yang baru
                            formatJumlahInput(`#jumlah-${index}`);

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

        $("#form").submit(function(e) {
            e.preventDefault();
            var $form = $(this);
            if (!$form.valid()) return false;

            var url;
            if (id == 0) {
                url = "<?php echo site_url('sam_reimbust/add') ?>";
            } else {
                url = "<?php echo site_url('sam_reimbust/update') ?>";
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
                            location.href = "<?= base_url('sam_reimbust') ?>";
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
    })

    $(document).on('focus', '#tgl_pengajuan', function() {
        // Inisialisasi dan tampilkan datepicker
        $(this).datepicker({
            dateFormat: 'dd-mm-yy', // Format default sementara
            changeMonth: true,
            changeYear: true,
        }).datepicker('show');
    });
</script>