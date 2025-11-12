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

    .tgl_keberangkatan,
    .tgl_kepulangan {
        width: 150px;
        min-width: 150px;
        /* Batas minimal agar tidak terlalu kecil */
    }

    .jenis,
    .harga {
        width: 150px;
        min-width: 150px;
    }

    .jumlah {
        width: 120px;
        min-width: 120px;
    }

    .tgl_keberangkatan {
        margin-bottom: 10px;
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

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header text-right">
                    <a class="btn btn-secondary btn-sm" href="<?= base_url('swi_penawaran') ?>"><i class="fas fa-chevron-left"></i>&nbsp;Back</a>
                </div>
                <div class="card-body">
                    <form id="form">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Tanggal Dokumen</label>
                                    <div class="col-sm-8">
                                        <div class="input-group date">
                                            <input type="text" class="form-control" name="tgl_dokumen" id="tgl_dokumen" placeholder="DD-MM-YYYY" autocomplete="off" readonly />
                                            <div class="input-group-append">
                                                <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Kode Penawaran</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="kode_penawaran" name="kode_penawaran" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Nama</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Nama....">
                                    </div>
                                </div>
                            </div>

                            <!-- SEBELAH KANAN -->
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label prepayment-field">Asal</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="asal" name="asal" placeholder="Asal....">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label prepayment-field">Tujuan</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="tujuan" name="tujuan" placeholder="Tujuan....">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label prepayment-field">Kendaraan</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="kendaraan" name="kendaraan" placeholder="Kendaraan....">
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
                                        <th scope="col" class="text-center">Tanggal</th>
                                        <th scope="col" class="text-center">Jenis</th>
                                        <th scope="col" class="text-center">Jumlah</th>
                                        <th scope="col" class="text-center">Harga</th>
                                        <th scope="col" class="text-center">Keterangan</th>
                                        <th scope="col" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="input-container">
                                    <!-- CONTAINER INPUTAN -->
                                </tbody>
                                <tr class="font-weight-bold">
                                    <td colspan="6" id="total_nominal_row" class="text-right">Total</td>
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
    $('#tgl_dokumen').datepicker({
        dateFormat: 'dd-mm-yy',
        minDate: new Date(),
        maxDate: new Date(),

        // MENGENERATE KODE PREPAYMENT SETELAH PILIH TANGGAL
        onSelect: function(dateText) {
            var id = dateText;
            $('#tgl_dokumen').removeClass("is-invalid");

            // Menghapus label error secara manual jika ada
            if ($("#tgl_dokumen-error").length) {
                $("#tgl_dokumen-error").remove(); // Menghapus label error
            }
            $.ajax({
                url: "<?php echo site_url('swi_penawaran/generate_kode') ?>",
                type: "POST",
                data: {
                    "date": dateText
                },
                dataType: "JSON",
                success: function(data) {
                    console.log(data);
                    $('#kode_penawaran').val(data.toUpperCase());
                    $('#kode').val(data);
                },
                error: function(error) {
                    alert("error" + error);
                }
            });
        }
    });

    $(document).ready(function() {

        // INISIASI VARIABEL JAVASCRIPT/JQUERY
        var id = $('#id').val();
        var aksi = $('#aksi').val();
        var kode = $('#kode').val();
        let inputCount = 0;
        let deletedRows = [];

        let rowCount = 0;

        // Fungsi untuk memformat input nominal memiliki titik (ribuan)
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

                // Pastikan elemen hidden diperbarui
                let rowId = $(this).attr('id').split('-')[1];
                $(`#hidden_nominal-${rowId}`).val(cleanValue);

                // Hitung total nominal setelah nilai berubah
                calculateTotalNominal();
            });
        }

        // Fungsi untuk memastikan input jumlah hanya angka
        $(document).on('input', '.jumlah', function() {
            let jumlah = $(this).val().replace(/\D/g, '');
            $(this).val(jumlah);
            calculateTotalNominal();
        });

        // Fungsi menghitung total nominal
        function calculateTotalNominal() {
            let total = 0; // Simpan total keseluruhan

            $('.jumlah').each(function() {
                let rowId = $(this).attr('id').replace('jumlah-', ''); // Ambil nomor row dari ID

                let jumlah = parseInt($(this).val()) || 0; // Ambil jumlah (default 0)
                let nominal = parseInt($(`#hidden_nominal-${rowId}`).val()) || 0; // Ambil nominal tersembunyi

                let subtotal = jumlah * nominal; // Hitung total per baris
                total += subtotal; // Tambahkan ke total keseluruhan

                // Simpan subtotal di hidden input tiap row
                $(`#hidden_total-${rowId}`).val(subtotal);

                // Update tampilan total per baris
                $(`#total-${rowId}`).val(subtotal.toLocaleString('id-ID')); // Format ribuan
            });

            // Update total keseluruhan ke tampilan & hidden input
            $('#total_nominal_view').text(total.toLocaleString('id-ID')); // Format ribuan
            $('#total_nominal').val(total); // Simpan nilai total tanpa pemisah ribuan
        }


        // Fungsi untuk menambahkan baris input
        function addRow() {
            rowCount++;
            const row = `
    <tr id="row-${rowCount}">
        <td class="row-number">${rowCount}</td>
        <td>
            <input type="text" class="form-control tgl_keberangkatan" name="tgl_keberangkatan[${rowCount}]" placeholder="Keberangkatan..." readonly />
            <input type="text" class="form-control tgl_kepulangan" name="tgl_kepulangan[${rowCount}]" placeholder="Kepulangan..." readonly />
        </td>
        <td><input type="text" class="form-control jenis" name="jenis[${rowCount}]" placeholder="Jenis..." /></td>
        <td><input type="text" class="form-control jumlah" id="jumlah-${rowCount}" name="jumlah[${rowCount}]" placeholder="Jumlah..." /></td>
        <td>
            <input type="text" class="form-control harga" id="nominal-${rowCount}" name="nominal[${rowCount}]" placeholder="Harga..." />
            <input type="hidden" id="hidden_nominal-${rowCount}" name="hidden_nominal[${rowCount}]" value="">
            <input type="hidden" id="hidden_total-${rowCount}" name="hidden_total[${rowCount}]" value=""> <!-- Hidden untuk total per row -->
        </td>
        <td><textarea name="keterangan[${rowCount}]" cols="25" rows="3"></textarea></td>
        <td><span class="btn delete-btn btn-danger" data-id="${rowCount}">Delete</span></td>
    </tr>
`;
            $('#input-container').append(row);
            formatJumlahInput(`#nominal-${rowCount}`); // Format input nominal

            updateSubmitButtonState()

            // Hitung ulang total setelah menambahkan baris baru
            calculateTotalNominal();

            // Validasi form untuk elemen baru
            $("#form").validate().settings.rules[`tgl_keberangkatan[${rowCount}]`] = {
                required: true
            };
            $("#form").validate().settings.rules[`tgl_kepulangan[${rowCount}]`] = {
                required: true
            };
            $("#form").validate().settings.rules[`jenis[${rowCount}]`] = {
                required: true
            };
            $("#form").validate().settings.rules[`jumlah[${rowCount}]`] = {
                required: true
            };
            $("#form").validate().settings.rules[`nominal[${rowCount}]`] = {
                required: true
            };

            $("#form").validate().settings.messages[`tgl_keberangkatan[${rowCount}]`] = {
                required: "*Tanggal keberangkatan wajib diisi"
            };
            $("#form").validate().settings.messages[`tgl_kepulangan[${rowCount}]`] = {
                required: "*Tanggal kepulangan wajib diisi"
            };
            $("#form").validate().settings.messages[`jenis[${rowCount}]`] = {
                required: "*Jenis wajib diisi"
            };
            $("#form").validate().settings.messages[`jumlah[${rowCount}]`] = {
                required: "*Jumlah wajib diisi"
            };
            $("#form").validate().settings.messages[`nominal[${rowCount}]`] = {
                required: "*Harga wajib diisi"
            };

            // Aktifkan datepicker pada elemen baru
            $('.tgl_keberangkatan').datepicker({
                dateFormat: 'dd-mm-yy'
            });
            $('.tgl_kepulangan').datepicker({
                dateFormat: 'dd-mm-yy'
            });
        }

        function deleteRow(id) {
            const row = $(`#row-${id}`);
            if (!row.length) return;

            // Ambil nilai subtotal dari hidden input sebelum menghapus row
            let subtotal = parseInt($(`#hidden_total-${id}`).val()) || 0;

            // Kurangi subtotal dari total keseluruhan
            let total = parseInt($('#total_nominal').val()) || 0;
            total -= subtotal;

            // Update tampilan total setelah penghapusan
            $('#total_nominal_view').text(total.toLocaleString('id-ID'));
            $('#total_nominal').val(total);

            // Jika row berasal dari database, tambahkan ke `deletedRows`
            const rowIdInput = row.find('input:hidden[id^="hidden_id_detail"]');
            if (rowIdInput.length) {
                const rowId = rowIdInput.val();
                if (rowId) deletedRows.push(rowId);
            }

            // Hapus row dari DOM
            row.remove();

            updateSubmitButtonState()

            // Update kembali urutan ID row
            reorderRows();
        }

        // MENHATUR ULANG URUTAN ROW SAAT DIHAPUS
        function reorderRows() {
            $('#input-container tr').each(function(index) {
                const newRowNumber = index + 1;
                $(this).attr('id', `row-${newRowNumber}`);
                $(this).find('.row-number').text(newRowNumber);
                $(this).find('.delete-btn').attr('data-id', newRowNumber);

                // Perbarui ID input dan name agar tetap sesuai
                $(this).find('input[name^="jumlah"]').attr('name', `jumlah[${newRowNumber}]`).attr('id', `jumlah-${newRowNumber}`);
                $(this).find('input[name^="nominal"]').attr('name', `nominal[${newRowNumber}]`).attr('id', `nominal-${newRowNumber}`);
                $(this).find('input[name^="hidden_nominal"]').attr('name', `hidden_nominal[${newRowNumber}]`).attr('id', `hidden_nominal-${newRowNumber}`);
                $(this).find('input[name^="hidden_total"]').attr('name', `hidden_total[${newRowNumber}]`).attr('id', `hidden_total-${newRowNumber}`);
            });

            rowCount = $('#input-container tr').length; // Update rowCount agar tetap akurat
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
            // console.log(id);
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
                url: "<?php echo site_url('swi_penawaran/edit_data') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    // moment.locale('id')
                    let total_nominal = 0;
                    //SET VALUE DATA MASTER PREPAYMENT
                    $('#id').val(data['master']['id']);
                    $('#kode_penawaran').val(data['master']['kode'].toUpperCase()).attr('readonly', true);
                    $('#tgl_dokumen').val(moment(data['master']['created_at']).format('DD-MM-YYYY'));
                    $('#name').val(data['master']['name']);
                    $('#asal').val(data['master']['asal']);
                    $('#tujuan').val(data['master']['tujuan']);
                    $('#kendaraan').val(data['master']['kendaraan']);
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
                            const nominalFormatted = data['transaksi'][index]['harga'].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                            const row = `
                                <tr id="row-${index + 1}">
                                    <td class="row-number">${index + 1}</td>
                                    <td>
                                        <input type="text" class="form-control tgl_keberangkatan" name="tgl_keberangkatan[${index + 1}]" value="${moment(data['transaksi'][index]['tgl_keberangkatan']).format('DD-MM-YYYY')}" placeholder="Keberangkatan..." readonly />
                                        <input type="text" class="form-control tgl_kepulangan" name="tgl_kepulangan[${index + 1}]" value="${moment(data['transaksi'][index]['tgl_kepulangan']).format('DD-MM-YYYY')}" placeholder="Kepulangan..." readonly />
                                        <input type="hidden" id="hidden_id${index + 1}" name="hidden_id" value="${data['master']['id']}">
                                        <input type="hidden" id="hidden_id_detail${index + 1}" name="hidden_id_detail[${index + 1}]" value="${data['transaksi'][index]['id']}">
                                    </td>
                                    <td><input type="text" class="form-control jenis" name="jenis[${index + 1}]" value="${data['transaksi'][index]['jenis']}" placeholder="Jenis..." /></td>
                                    <td><input type="text" class="form-control jumlah" id="jumlah-${index + 1}" name="jumlah[${index + 1}]" value="${data['transaksi'][index]['jumlah']}" placeholder="Jumlah..." /></td>
                                    <td>
                                        <input type="text" class="form-control harga" id="nominal-${index + 1}" name="nominal[${index + 1}]" value="${nominalFormatted}" placeholder="Harga..." />
                                        <input type="hidden" id="hidden_nominal-${index + 1}" name="hidden_nominal[${index + 1}]" value="${data['transaksi'][index]['harga']}">
                                        <input type="hidden" id="hidden_total-${index + 1}" name="hidden_total[${index + 1}]" value="${data['transaksi'][index]['jumlah'] * data['transaksi'][index]['harga']}">
                                    </td>
                                    <td><textarea name="keterangan[${index + 1}]" cols="25" rows="3">${data['transaksi'][index]['keterangan']}</textarea></td>
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

                            // Aktifkan datepicker pada elemen baru
                            $('.tgl_keberangkatan').datepicker({
                                dateFormat: 'dd-mm-yy'
                            });
                            $('.tgl_kepulangan').datepicker({
                                dateFormat: 'dd-mm-yy'
                            });
                            rowCount = index + 1;
                        });

                        // 🔹 Panggil calculateTotalNominal() setelah semua transaksi dimasukkan
                        calculateTotalNominal();

                        // 🔹 Panggil reorderRow() agar nomor urut tetap teratur
                        // reorderRow();
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
            $('#tgl_dokumen').prop('disabled', true);
            $('#nama').prop('readonly', true);
            $('#prepayment').prop('readonly', true);
            $('#tujuan').prop('readonly', true);
            $('#total_nominal_row').attr('colspan', 3);
            $('#add-row').toggle();
            $('th:last-child').remove();

            $.ajax({
                url: "<?php echo site_url('swi_penawaran/read_detail/') ?>" + id,
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
            // console.log('testing');
            e.preventDefault();
            var $form = $(this);
            if (!$form.valid()) return false;
            var url;
            if (id == 0) {
                url = "<?php echo site_url('swi_penawaran/add') ?>";
            } else {
                url = "<?php echo site_url('swi_penawaran/update') ?>";
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
                    console.log(data);
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
                            location.href = "<?= base_url('swi_penawaran') ?>";
                        })
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
                tgl_dokumen: {
                    required: true,
                },
                name: {
                    required: true,
                },
                asal: {
                    required: true,
                },
                tujuan: {
                    required: true,
                },
                kendaraan: {
                    required: true,
                    maxlength: 22,
                }
            },
            messages: {
                tgl_dokumen: {
                    required: "*Tanggal is required",
                },
                name: {
                    required: "*Nama is required",
                },
                asal: {
                    required: "*Asal is required",
                },
                tujuan: {
                    required: "*Tujuan is required",
                },
                kendaraan: {
                    required: "*Kendaraan rekening perlu diisi"
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