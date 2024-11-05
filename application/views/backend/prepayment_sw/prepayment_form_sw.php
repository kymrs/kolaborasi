<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title_view ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header text-right">
                    <a class="btn btn-secondary btn-sm" href="<?= base_url('prepayment_sw') ?>"><i class="fas fa-chevron-left"></i>&nbsp;Back</a>
                </div>
                <div class="card-body">
                    <form id="form">
                        <div class="row">
                            <div class="col-md-6" style="padding-right: 4rem">
                                <div class="form-group row">
                                    <label class="col-sm-5">Tanggal Prepayment</label>
                                    <div class="col-sm-7">
                                        <div class="input-group date">
                                            <input type="text" class="form-control" name="tgl_prepayment" id="tgl_prepayment" placeholder="DD-MM-YYYY" autocomplete="off" readonly>
                                            <div class="input-group-append">
                                                <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5">Kode Prepayment</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="kode_prepayment" name="kode_prepayment" readonly>
                                    </div>
                                </div>
                                <!-- <div class="form-group row">
                                    <label class="col-sm-5">Divisi</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" name="divisi" id="divisi">
                                            <option value="">-- Pilih --</option>
                                            <option value="marketing">Marketing</option>
                                            <option value="it">IT</option>
                                            <option value="ga">General Affair</option>
                                        </select>
                                    </div>
                                </div> -->
                            </div>

                            <!-- SEBELAH KANAN -->
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-5">Event</label>
                                    <div class="row-sm-10" style="margin-left: 14px; width: 51%;">
                                        <select class="form-control event_sw" id="event" name="event" style="width: 83%;">
                                            <option value="" selected disabled>Pilih opsi...</option>
                                            <?php foreach ($events as $event) { ?>
                                                <option value="<?= $event->id ?>"><?= $event->event_name ?></option>
                                            <?php } ?>
                                        </select>
                                        <?php if (in_array($hak_akses, [1, 2])) { ?>
                                            <button class="btn btn-success btn-sm" type="button" data-toggle="modal" data-target="#staticBackdrop">
                                                <i class="fa fa-plus" aria-hidden="true"></i>
                                            </button>
                                        <?php } ?>
                                    </div>
                                </div>
                                <!-- <div class="form-group row">
                                    <label class="col-sm-5">Jabatan</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="jabatan" name="jabatan" placeholder="Jabatan....">
                                    </div>
                                </div> -->
                                <div class="form-group row">
                                    <label class="col-sm-5">Tujuan</label>
                                    <div class="col-sm-7">
                                        <textarea class="form-control" id="tujuan" name="tujuan" rows="2"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- BUTTON TAMBAH FORM -->
                        <div class="mt-3">
                            <button type="button" class="btn btn-success btn-sm" id="add-row"><i class="fa fa-plus" aria-hidden="true"></i> Add</button>
                        </div>
                        <!-- TABLE INPUT -->
                        <div class="mt-4">
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
                        <!-- PENENTUAN UPDATE ATAU ADD -->
                        <input type="hidden" name="id" id="id" value="<?= $id ?>">
                        <?php if (!empty($aksi)) { ?>
                            <input type="hidden" name="aksi" id="aksi" value="<?= $aksi ?>">
                        <?php } ?>
                        <?php if ($id == 0) { ?>
                            <input type="hidden" name="kode" id="kode" value="">
                            <button type="submit" class="btn btn-primary btn-sm aksi" disabled></button>
                        <?php } else { ?>
                            <button type="submit" class="btn btn-primary btn-sm aksi"></button>
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
                                <button style="margin-left: 20px" type="submit" class="btn btn-danger">Delete</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm">
                            </div>
                        </div>
                    </div>
                </form>

                <!-- TABLE EVENT -->
                <div class="card-body">
                    <table id="table_event" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID</th>
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
                url: "<?php echo site_url('prepayment_sw/generate_kode') ?>",
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

        $('.event_sw').select2({
            width: 'style' // Menggunakan lebar yang ditentukan pada elemen HTML
        });

        table = $('#table_event').DataTable({
            "responsive": true,
            "autoWidth": false,
            // "scrollX": true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('prepayment_sw/get_list_event') ?>",
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
            // console.log(data);

            $('#event_id').val(data[1]);
            $('#event_name').val(data[2]);

            if (data[3] == 'Active') {
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

        // function checkDeleteButtonState() {
        //     const rowCount = $('#input-container tr').length;
        //     if (rowCount === 1) {
        //         $('#input-container .delete-btn').prop('disabled', true); // Disable delete button if only one row
        //     } else {
        //         $('#input-container .delete-btn').prop('disabled', false); // Enable delete button if more than one row
        //     }
        // }

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
            $('.aksi').text('Save');
        } else {
            $('.aksi').text('Update');
            $("select option[value='']").hide();
            $.ajax({
                url: "<?php echo site_url('prepayment_sw/edit_data') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    // moment.locale('id')
                    let total_nominal = 0;
                    // console.log(data);
                    for (let index = 0; index < data['transaksi'].length; index++) {
                        total_nominal += parseInt(data['transaksi'][index]['nominal'], 10);
                    }
                    //SET VALUE DATA MASTER PREPAYMENT
                    $('#id').val(data['master']['id']);
                    $('#kode_prepayment').val(data['master']['kode_prepayment'].toUpperCase()).attr('readonly', true);
                    $('#tgl_prepayment').val(moment(data['master']['tgl_prepayment']).format('DD-MM-YYYY'));
                    $('#nama').val(data['master']['nama']);
                    // $('#divisi').val(data['master']['divisi']);
                    // $('#jabatan').val(data['master']['jabatan']);
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
                url: "<?php echo site_url('prepayment_sw/read_detail/') ?>" + id,
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
                url = "<?php echo site_url('prepayment_sw/add') ?>";
            } else {
                url = "<?php echo site_url('prepayment_sw/update') ?>";
            }

            $.ajax({
                url: url,
                type: "POST",
                data: $('#form').serialize(),
                dataType: "JSON",
                success: function(data) {
                    // console.log(data);
                    if (data.status) //if success close modal and reload ajax table
                    {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Your data has been saved',
                            showConfirmButton: false,
                            timer: 1500
                        }).then((result) => {
                            location.href = "<?= base_url('prepayment_sw') ?>";
                        })
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                }
            });
        });

        //INSER/UPDATE EVENT_SW
        $("#form_event").submit(function(e) {
            e.preventDefault();
            var $form = $(this);
            if (!$form.valid()) return false;
            // console.log($form);
            var url = "<?php echo site_url('prepayment_sw/add_event') ?>";

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
                            location.href = "<?= base_url('prepayment_sw/add_form') ?>";
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
                }
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
                }
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