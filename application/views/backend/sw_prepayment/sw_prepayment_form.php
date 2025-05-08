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
        transform: translateY(-4px);
    }

    .front-add {
        background-color: #10b53c;
    }

    .front-aksi {
        background-color: #0075FF;
    }


    .btn-special:focus:not(:focus-visible) {
        outline: none;
    }

    .btn-special:active .front {
        transform: translateY(-2px);
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

    <!-- Form Loading indicator -->
    <div id="form_loading" style="display: none;">
        <p>Loading...</p>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header text-right">
                    <a class="btn btn-secondary btn-sm" href="<?= base_url('sw_prepayment') ?>"><i class="fas fa-chevron-left"></i>&nbsp;Back</a>
                </div>
                <div class="card-body">
                    <form id="form">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Tanggal Prepayment</label>
                                    <div class="col-sm-8">
                                        <div class="input-group date">
                                            <input type="text" class="form-control" name="tgl_prepayment" id="tgl_prepayment" placeholder="DD-MM-YYYY" autocomplete="off" readonly />
                                            <div class="input-group-append">
                                                <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Kode Prepayment</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="kode_prepayment" name="kode_prepayment" readonly>
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
                                            <select class="js-example-basic-single" id="rekening" name="rekening" required>
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
                                                <input type="text" class="form-control col-sm-7" style="font-size: 13px;" id="nomor_rekening" name="nomor_rekening" placeholder="Nomor Rekening">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- SEBELAH KANAN -->
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label prepayment-field">Event</label>
                                    <div class="row-sm-10" style="margin-left: 14px; width: 60%;">
                                        <select class="form-control event_sw" id="event" name="event" style="width: 80%;" required>
                                            <option value="" selected disabled>Pilih opsi...</option>
                                            <?php foreach ($events as $event) { ?>
                                                <?php if (isset($selected)) { ?>
                                                    <option value="<?= $event->id ?>" <?= $event->id == $selected ? 'selected' : '' ?>><?= $event->event_name ?></option>
                                                <?php } else { ?>
                                                    <option value="<?= $event->id ?>"><?= $event->event_name ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>

                                        <button class="btn btn-success btn-sm" type="button" data-toggle="modal" data-target="#staticBackdrop">
                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                        </button>

                                        <div class="invalid-feedback" style="display: none; color: red;"></div>

                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label tujuan-field">Tujuan</label>
                                    <div class="col-sm-7">
                                        <textarea class="form-control" id="tujuan" name="tujuan" rows="2"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- BUTTON TAMBAH FORM -->
                        <div class="mt-3">
                            <button type="button" class="btn-special btn-success btn-sm" id="add-row" style="background-color: green;"><span class="front front-add"><i class="fa fa-plus" aria-hidden="true"></i> Add</span></button>
                        </div>
                        <!-- TABLE INPUT -->
                        <div class="mt-4 mb-3" style="overflow-x: scroll;">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col" class="text-center">No</th>
                                        <th scope="col">Rincian</th>
                                        <th scope="col">Nominal</th>
                                        <th scope="col">Keterangan</th>
                                        <th scope="col" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="input-container">
                                    <!-- CONTAINER INPUTAN -->
                                </tbody>
                                <tr class="font-weight-bold">
                                    <td colspan="4" id="total_nominal_row" class="text-right">Total</td>
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


<!-- MODAL -->
<div class="modal fade" id="staticBackdrop" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">EVENT FORM</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form_event" action="">
                    <div class="col" style="border-bottom: 1px solid rgba(0,0,0,0.2);">
                        <div class="form-group row">
                            <label class="col-sm-2">Event</label>
                            <input type="hidden" id="event_id" name="event_id">
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="event_name" name="event_name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="aktif" class="col-sm-2 col-form-label">Active</label>
                            <div class="col-sm-10 form-inline row ml-1">
                                <div class="custom-control custom-radio col-sm-2">
                                    <input class="custom-control-input" type="radio" id="customRadio1" name="is_active" value="1">
                                    <label for="customRadio1" class="custom-control-label">Yes</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" id="customRadio2" name="is_active" value="0" checked>
                                    <label for="customRadio2" class="custom-control-label">No</label>
                                </div>
                                <button style="margin-left: 100px" type="submit" class="btn btn-success">Submit</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm">
                            </div>
                        </div>
                    </div>
                </form>

                <!-- TABLE EVENT -->
                <div class="card-body" style="overflow-x: scroll;">
                    <table id="table_event" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID</th>
                                <th>Delete</th>
                                <th>Event</th>
                                <th>Active</th>
                                <th>created</th>
                                <th>updated</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th>ID</th>
                                <th>Delete</th>
                                <th>Event</th>
                                <th>Active</th>
                                <th>created</th>
                                <th>updated</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>

<script>
    $('#tgl_prepayment').datepicker({
        dateFormat: 'dd-mm-yy',
        minDate: new Date(),
        maxDate: new Date(),

        // MENGENERATE KODE PREPAYMENT SETELAH PILIH TANGGAL
        onSelect: function(dateText) {
            var id = dateText;
            $('#tgl_prepayment').removeClass("is-invalid");

            // Menghapus label error secara manual jika ada
            if ($("#tgl_prepayment-error").length) {
                $("#tgl_prepayment-error").remove(); // Menghapus label error
            }
            $.ajax({
                url: "<?php echo site_url('sw_prepayment/generate_kode') ?>",
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

        document.getElementById('nomor_rekening').addEventListener('input', function(e) {
            let value = this.value.replace(/[^0-9]/g, '');
            if (value.length > 14) {
                value = value.slice(0, 10);
            }
            this.value = value;
        });

        $('.event_sw').select2({
            width: 'style' // Menggunakan lebar yang ditentukan pada elemen HTML
        });

        table = $('#table_event').DataTable({
            "responsive": true,
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('sw_prepayment/get_list_event') ?>",
                "type": "POST"
            },
            "columnDefs": [{
                "targets": [1], // Target the id column (index 1)
                "visible": false // Hide the id column
            }, {
                "targets": [],
                "className": 'dt-body-nowrap'
            }, {
                "targets": [0],
                "orderable": false,
            }, ]
        });

        // modal datatables on click
        $('#table_event tbody').on('click', 'tr', function() {
            let data = table.row(this).data();
            console.log(data);

            $('#event_id').val(data[1]);
            $('#event_name').val(data[3]);

            $('.delete').prop('disabled', false);

            if (data[4] == 'Active') {
                $('#customRadio1').prop('checked', true);
                $('#customRadio2').prop('checked', false);
            } else {
                $('#customRadio1').prop('checked', false);
                $('#customRadio2').prop('checked', true);
            }

        });

        // INISIASI VARIABEL JAVASCRIPT/JQUERY
        var id = $('#id').val();
        var aksi = $('#aksi').val();
        var kode = $('#kode').val();
        let inputCount = 0;
        let deletedRows = [];

        if (id != 0) {
            // Tampilkan loading
            $('#form_loading').show();
            $('.aksi').prop('disabled', true);
        }

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

        function calculateTotalNominal() {
            let total = 0;
            $('input[name^="hidden_nominal"]').each(function() {
                let value = parseInt($(this).val()) || 0; // Parse as integer, default to 0 if invalid
                total += value;
            });
            $('#total_nominal_view').text(total.toLocaleString()); // Format total dengan pemisah ribuan
            $('#total_nominal').val(total);
        }

        //MENAMBAH FORM INPUTAN DI ADD FORM
        let rowCount = 0;

        function addRow() {
            rowCount++;
            const row = `
                <tr id="row-${rowCount}">
                    <td class="row-number">${rowCount}</td>
                    <td><input type="text" class="form-control" name="rincian[${rowCount}]" value="" placeholder="Input here..." /></td>
                    <td><input type="text" class="form-control" id="nominal-${rowCount}" name="nominal[${rowCount}]" value="" placeholder="Input here..." />
                        <input type="hidden" id="hidden_nominal${rowCount}" name="hidden_nominal[${rowCount}]" value="">
                    </td>
                    <td><input type="text" class="form-control" name="keterangan[${rowCount}]" value="" placeholder="Input here..." /></td>
                    
                    <td><span class="btn delete-btn btn-danger" data-id="${rowCount}">Delete</span></td>
                </tr>
                `;
            $('#input-container').append(row);
            // Tambahkan format ke input nominal yang baru
            formatJumlahInput(`#nominal-${rowCount}`);
            updateSubmitButtonState(); // Perbarui status tombol submit
            //checkDeleteButtonState(); // Cek tombol delete setelah baris ditambahkan

            // Hitung total nominal setelah baris baru ditambahkan
            calculateTotalNominal();

            //VALIDASI ROW YANG TELAH DI APPEND
            $("#form").validate().settings.rules[`rincian[${rowCount}]`] = {
                required: true
            };
            $("#form").validate().settings.rules[`nominal[${rowCount}]`] = {
                required: true
            };
            $("#form").validate().settings.messages[`rincian[${rowCount}]`] = {
                required: "Rincian is required"
            };
            $("#form").validate().settings.messages[`nominal[${rowCount}]`] = {
                required: "Nominal is required"
            };

            // $("#form").validate().settings.rules[`keterangan[${rowCount}]`] = {
            //     required: true
            // };
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
                //INISIASI VARIABLE UNTUK reorderRows
                const newRowNumber = index + 1;
                const rincianValue = $(this).find('input[name^="rincian"]').val();
                const nominalValue = $(this).find('input[name^="nominal"]').val();
                const hiddenIdValue = $(this).find('input[name^="hidden_id_detail"]').val();
                const hiddenNominalValue = $(this).find('input[name^="hidden_nominal"]').val();
                const keteranganValue = $(this).find('input[name^="keterangan"]').val();

                $(this).attr('id', `row-${newRowNumber}`);
                $(this).find('.row-number').text(newRowNumber);
                $(this).find('input[name^="rincian"]').attr('name', `rincian[${newRowNumber}]`).attr('placeholder', `Input here...`).val(rincianValue);
                $(this).find('input[name^="nominal"]').attr('name', `nominal[${newRowNumber}]`).attr('id', `nominal-${newRowNumber}`).attr('placeholder', `Input here...`).val(nominalValue);
                $(this).find('input[name^="hidden_id_detail"]').attr('name', `hidden_id_detail[${newRowNumber}]`).val(hiddenIdValue);
                $(this).find('input[name^="hidden_nominal"]').attr('name', `hidden_nominal[${newRowNumber}]`).attr('id', `hidden_nominal${newRowNumber}`).val(hiddenNominalValue);
                $(this).find('input[name^="keterangan"]').attr('name', `keterangan[${newRowNumber}]`).attr('placeholder', `Input here...`).val(keteranganValue);
                $(this).find('.delete-btn').attr('data-id', newRowNumber).text('Delete');
            });
            rowCount = $('#input-container tr').length; // Update rowCount to the current number of rows
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
                url: "<?php echo site_url('sw_prepayment/edit_data') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    // moment.locale('id')
                    let total_nominal = 0;

                    // PENGECEKAN APAKAH DATA TRANSAKSI ADA ATAU TIDAK
                    if (data.transaksi.length > 0) {
                        $('#form_loading').hide();
                        $('.aksi').prop('disabled', false);
                    }

                    // console.log(data);
                    for (let index = 0; index < data['transaksi'].length; index++) {
                        total_nominal += parseInt(data['transaksi'][index]['nominal'], 10);
                    }
                    //SET VALUE DATA MASTER PREPAYMENT
                    $('#id').val(data['master']['id']);
                    $('#kode_prepayment').val(data['master']['kode_prepayment'].toUpperCase()).attr('readonly', true);
                    $('#tgl_prepayment').val(moment(data['master']['tgl_prepayment']).format('DD-MM-YYYY'));
                    $('#nama').val(data['master']['nama']);
                    $('#rekening').val(data['master']['no_rek']).trigger('change');
                    $('#prepayment').val(data['master']['prepayment']);
                    var parts = data['master']['no_rek'].split("-"); // Pisahkan berdasarkan "-"

                    if (parts.length === 3) {
                        $("#nama_rek").val(parts[0]);
                        $("#nama_bank").val(parts[1]);
                        $("#nomor_rekening").val(parts[2]);
                    }
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
                        $(data['transaksi']).each(function(index) {
                            //Nilai nominal diformat menggunakan pemisah ribuan sebelum dimasukkan ke dalam elemen input.
                            const nominalFormatted = data['transaksi'][index]['nominal'].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                            const row = `
                        <tr id="row-${index + 1}">
                            <td class="row-number">${index + 1}</td>
                            <td><input type="text" class="form-control" name="rincian[${index + 1}]" value="${data['transaksi'][index]['rincian']}" />
                                <input type="hidden" id="hidden_id${index + 1}" name="hidden_id" value="${data['master']['id']}">
                                <input type="hidden" id="hidden_id_detail${index + 1}" name="hidden_id_detail[${index + 1}]" value="${data['transaksi'][index]['id']}">
                            </td>
                            <td><input type="text" class="form-control" id="nominal-${index + 1}" name="nominal[${index + 1}]" value="${nominalFormatted}" />
                                <input type="hidden" id="hidden_nominal${index + 1}" name="hidden_nominal[${index + 1}]" value="${data['transaksi'][index]['nominal']}">
                            </td>
                            <td><input type="text" class="form-control" name="keterangan[${index + 1}]" value="${data['transaksi'][index]['keterangan']}" placeholder="input here...."/></td>
                            <td><span class="btn delete-btn btn-danger" data-id="${index + 1}">Delete</span></td>
                        </tr>
                        `;
                            $('#input-container').append(row);
                            // Tambahkan format ke input nominal yang baru
                            formatJumlahInput(`#nominal-${index+1}`);

                            //VALIDASI ROW YANG TELAH DI APPEND
                            $("#form").validate().settings.rules[`rincian[${index + 1}]`] = {
                                required: true
                            };
                            $("#form").validate().settings.rules[`nominal[${index + 1}]`] = {
                                required: true
                            };
                            $("#form").validate().settings.messages[`rincian[${index + 1}]`] = {
                                required: "Rincian is required"
                            };
                            $("#form").validate().settings.messages[`nominal[${index + 1}]`] = {
                                required: "Nominal is required"
                            };
                            // $("#form").validate().settings.rules[`keterangan[${index + 1}]`] = {
                            //     required: true
                            // };
                            rowCount = index + 1;
                        });
                    }
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
            // $('#jabatan').prop('disabled', true);
            // $('#divisi').prop('disabled', true);
            $('#prepayment').prop('readonly', true);
            $('#tujuan').prop('readonly', true);
            $('#total_nominal_row').attr('colspan', 3);
            $('#add-row').toggle();
            $('th:last-child').remove();

            $.ajax({
                url: "<?php echo site_url('sw_prepayment/read_detail/') ?>" + id,
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
                url = "<?php echo site_url('sw_prepayment/add') ?>";
            } else {
                url = "<?php echo site_url('sw_prepayment/update') ?>";
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
                            location.href = "<?= base_url('sw_prepayment') ?>";
                        })
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

        //INSER/UPDATE EVENT_SW
        $("#form_event").submit(function(e) {
            e.preventDefault();
            var $form = $(this);
            if (!$form.valid()) return false;
            // console.log($form);
            var url = "<?php echo site_url('sw_prepayment/add_event') ?>";

            $.ajax({
                url: url,
                type: "POST",
                data: $('#form_event').serialize(),
                dataType: "JSON",
                success: function(data) {
                    console.log(data);
                    if (data.status) //if success close modal and reload ajax table
                    {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Your data has been saved',
                            showConfirmButton: false,
                            timer: 1500
                        }).then((result) => {
                            location.href = "<?= base_url('sw_prepayment/add_form') ?>";
                        })
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
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
                tujuan: {
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
                rekening: {
                    required: true,
                },
                event: { // Tambahkan aturan untuk field event
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
                tujuan: {
                    required: "Tujuan is required",
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
                rekening: {
                    required: "Rekening is required",
                },
                event: { // Tambahkan pesan untuk event
                    required: "Event is required",
                },
            },
            errorPlacement: function(error, element) {
                if (element.parent().hasClass('input-group')) {
                    error.insertAfter(element.parent());
                } else if (element.attr("id") === "event") { // Targetkan elemen select dengan ID "event"
                    element.siblings(".invalid-feedback").html(error.html()).show(); // Masukkan pesan error ke dalam div.invalid-feedback
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

    // MENGHAPUS DATA MENGGUNAKAN METHODE POST JQUERY
    function delete_data(id) {
        // console.log($id);
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?php echo site_url('sw_prepayment/delete_event/') ?>" + id,
                    type: "POST",
                    dataType: "JSON",
                    success: function(data) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Your data has been deleted',
                            showConfirmButton: false,
                            timer: 1500
                        }).then((result) => {
                            location.href = "<?= base_url('sw_prepayment/add_form') ?>";
                        })
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error deleting data');
                    }
                });
            }
        })
    };
</script>