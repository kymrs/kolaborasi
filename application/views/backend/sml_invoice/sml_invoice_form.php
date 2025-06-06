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
        background-color: #242C49;
    }

    .front-aksi {
        background-color: #242C49;
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

    .header-table-transaksi {
        background-color: rgb(36, 44, 73);
        color: white;
    }

    .header-table-transaksi th {
        border: 1px solid rgb(255, 255, 255, 0.2);
        font-weight: 400;
        text-align: center;
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

        .colomn-kanan {
            overflow-x: scroll;
            height: auto;
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
                    <a class="btn btn-sm" style="background-color: #242C49; color: white" href="<?= base_url('sml_invoice') ?>"><i class="fas fa-chevron-left"></i>&nbsp;Back</a>
                </div>
                <div class="card-body">
                    <form id="form">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Tanggal Invoice</label>
                                    <div class="col-sm-8">
                                        <div class="input-group date">
                                            <input type="text" class="form-control" name="tgl_invoice" id="tgl_invoice" placeholder="DD-MM-YYYY" autocomplete="off" readonly style="cursor: pointer">
                                            <div class="input-group-append">
                                                <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Kode Invoice</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="kode_invoice" name="kode_invoice" readonly placeholder="Kode Invoice">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Tanggal Jatuh Tempo</label>
                                    <div class="col-sm-8">
                                        <div class="input-group date">
                                            <input type="text" class="form-control" name="tgl_tempo" id="tgl_tempo" placeholder="DD-MM-YYYY" autocomplete="off" readonly style="cursor: pointer">
                                            <div class="input-group-append">
                                                <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-4 col-form-label">Contact To</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="ctc_to" name="ctc_to" placeholder="Contact To">
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-4 col-form-label">Contact Address</label>
                                    <div class="col-sm-8">
                                        <textarea name="ctc_address" id="ctc_address" class="form-control" placeholder="Contact Address"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-4 col-form-label">Tax</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="tax" name="tax" placeholder="Rp.">
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-4 col-form-label">Diskon</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="diskon" name="diskon" placeholder="Rp.">
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-sm-4 col-form-label">Metode</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="metode" name="metode" placeholder="Metode Pembayaran">
                                    </div>
                                </div>
                            </div>

                            <!-- SEBELAH KANAN -->
                            <div class="col-md-6 colomn-kanan">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">No Rekening</label>
                                    <div class="col-sm-8">
                                        <div class="input-group mb-3">
                                            <!-- RADIO BUTTON UNTUK PEMILIHAN INPUTAN REKENING -->
                                            <div class="form-check form-check-inline" style="margin-bottom: 5px;">
                                                <input class="form-check-input" type="radio" name="radioNoLabel" id="exist" value="" aria-label="..." checked><label for="exist" style="margin-right: 14px; margin-top: 8px; cursor: pointer">Rekening terdaftar</label>
                                                <input class="form-check-input" type="radio" name="radioNoLabel" id="new" value="" aria-label="..."><label for="new" style="margin-top: 8px; cursor: pointer">Rekening baru</label>
                                            </div>
                                            <select class="js-example-basic-single" id="rekening" name="rekening">
                                                <option value="" selected disabled>Pilih rekening tujuan</option>
                                                <?php foreach ($rek_options as $option) { ?>
                                                    <option data-pengaju="<?= $option->nama ?>" data-bank="<?= $option->nama_bank ?>" data-rek="<?= $option->no_rek ?>" value=""><?= $option->nama . '-' . $option->nama_bank . '-' . $option->no_rek ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="input-group rekening-text">
                                                <input type="text" class="form-control col-sm-4" style="font-size: 13px;" id="nama_rek" name="nama_rek" placeholder="Nama Pengaju">&nbsp;
                                                <span class="py-2">-</span>&nbsp;
                                                <input type="text" class="form-control col-sm-4" style="font-size: 13px;" id="nama_bank" name="nama_bank" placeholder="Nama Bank">&nbsp;
                                                <span class="py-2">-</span>&nbsp;
                                                <input type="text" class="form-control col-sm-6" style="font-size: 13px;" id="nomor_rekening" name="nomor_rekening" placeholder="No Rekening">
                                                <span class="py-2"></span>&nbsp;
                                                <button type="button" class="btn-primary" id="btn-rek" style="height: 33.5px; width: 40px"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12" style="overflow-x: scroll">
                                        <table id="rek-table" class=" table table-bordered">
                                            <thead>
                                                <th>No</th>
                                                <th class="col-sm-4">Nama Pengaju</th>
                                                <th class="col-sm-4">Nama Bank</th>
                                                <th class="col-sm-8">No Rekening</th>
                                                <th>Delete</th>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <input type="hidden" class="form-control" id="total_akhir" name="total_akhir" placeholder="Contact To">
                            </div>
                        </div>

                        <!-- BUTTON TAMBAH FORM -->
                        <div class="mt-4">
                            <button type="button" class="btn-special btn-sm" id="add-row" style="background-color:rgb(51, 65, 112);"><span class="front front-add"><i class="fa fa-plus" aria-hidden="true"></i> Add</span></button>
                        </div>
                        <!-- TABLE INPUT -->
                        <div class="mt-3 mb-3" style="overflow-x: scroll;">
                            <table class="table table-bordered table-hover">
                                <thead class="header-table-transaksi">
                                    <tr>
                                        <th scope="col" class="text-center">No</th>
                                        <th scope="col">Item</th>
                                        <th scope="col">Nopol</th>
                                        <th scope="col">Tipe</th>
                                        <th scope="col">Total</th>
                                        <th scope="col" class="text-center">Action</th>
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
                            <button type="submit" class="btn-special btn-sm aksi" style="background-color: rgb(51, 65, 112);" disabled></button>
                        <?php } else { ?>
                            <button type="submit" class="btn-special btn-sm aksi" style="background-color: rgb(51, 65, 112);"></button>
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
    const toolbarOptions = [
        ['bold', 'italic', 'underline', 'strike'], // toggled buttons
        ['blockquote', 'code-block'],
        ['link', 'image', 'video', 'formula'],

        [{
            'header': 1
        }, {
            'header': 2
        }], // custom button values
        [{
            'list': 'ordered'
        }, {
            'list': 'bullet'
        }, {
            'list': 'check'
        }],
        [{
            'script': 'sub'
        }, {
            'script': 'super'
        }], // superscript/subscript
        [{
            'indent': '-1'
        }, {
            'indent': '+1'
        }], // outdent/indent
        [{
            'direction': 'rtl'
        }], // text direction

        [{
            'size': ['small', false, 'large', 'huge']
        }], // custom dropdown
        [{
            'header': [1, 2, 3, 4, 5, 6, false]
        }],

        [{
            'color': []
        }, {
            'background': []
        }], // dropdown with defaults from theme
        [{
            'font': []
        }],
        [{
            'align': []
        }],

        ['clean']
    ];

    $(document).ready(function() {
        $('#ctc_nomor1').on('input', function() {
            // Ambil nilai input
            let value = $(this).val();

            // Hapus semua karakter yang bukan angka
            $(this).val(value.replace(/[^0-9]/g, ''));
        });
        $('#ctc_nomor2').on('input', function() {
            // Ambil nilai input
            let value = $(this).val();

            // Hapus semua karakter yang bukan angka
            $(this).val(value.replace(/[^0-9]/g, ''));
        });
        $('#diskon').on('input', function() {
            // Ambil nilai input
            let value = $(this).val();

            // Hapus semua karakter yang bukan angka
            $(this).val(value.replace(/[^0-9]/g, ''));
        });
    });


    $('#tgl_invoice').datepicker({
        dateFormat: 'dd-mm-yy',
        // minDate: new Date(),
        // maxDate: new Date(),

        // MENGENERATE KODE INVOICE SETELAH PILIH TANGGAL
        onSelect: function(dateText) {
            var id = dateText;
            $('#tgl_invoice').removeClass("is-invalid");

            // Menghapus label error secara manual jika ada
            if ($("#tgl_invoice-error").length) {
                $("#tgl_invoice-error").remove(); // Menghapus label error
            }
            $.ajax({
                url: "<?php echo site_url('sml_invoice/generate_kode') ?>",
                type: "POST",
                data: {
                    "date": dateText
                },
                dataType: "JSON",
                success: function(data) {
                    console.log(data);
                    $('#kode_invoice').val(data.toUpperCase());
                    $('#kode').val(data);
                },
                error: function(error) {
                    alert("error" + error);
                }
            });
        }
    });

    $(document).ready(function() {
        $('#tax').on('keyup', function(e) {
            let value = $(this).val().replace(/[^,\d]/g, ''); // Hanya angka dan koma yang diperbolehkan
            if (value) {
                $(this).val(formatRupiah(value));
            } else {
                $(this).val('');
            }
        });
        $('#diskon').on('keyup', function(e) {
            let value = $(this).val().replace(/[^,\d]/g, ''); // Hanya angka dan koma yang diperbolehkan
            if (value) {
                $(this).val(formatRupiah(value));
            } else {
                $(this).val('');
            }
        });

    });

    function formatRupiah(angka) {
        let numberString = angka.replace(/[^,\d]/g, '').toString(),
            split = numberString.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        return split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
    }


    $(document).ready(function() {
        // Event ketika tanggal diubah
        $('#tgl_invoice, #tgl_tempo').on('change', function() {
            let tglInvoice = $('#tgl_invoice').val(); // Ambil nilai tanggal invoice
            let tglTempo = $('#tgl_tempo').val(); // Ambil nilai tanggal tempo

            if (tglInvoice && tglTempo) { // Cek jika kedua tanggal sudah terisi
                // Format tanggal ke objek Date
                let dateInvoice = moment(tglInvoice, "DD-MM-YYYY");
                let dateTempo = moment(tglTempo, "DD-MM-YYYY");

                if (dateTempo.isBefore(dateInvoice)) { // Cek apakah tanggal tempo sebelum tanggal invoice
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan',
                        text: 'Tanggal tempo tidak boleh mundur dari tanggal invoice!',
                    });

                    // Reset input tanggal
                    $('#tgl_invoice').val('');
                    $('#tgl_tempo').val('');
                }
            }
        });
    });

    $('#tgl_tempo').datepicker({
        dateFormat: 'dd-mm-yy',
        // minDate: new Date(),
        // maxDate: new Date(),
    });

    $(document).ready(function() {

        // INISIASI VARIABEL JAVASCRIPT/JQUERY
        var id = $('#id').val();
        var aksi = $('#aksi').val();
        var kode = $('#kode').val();
        let inputCount = 0;
        let deletedRows = [];
        let deletedRekRows = [];

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

        // AGAR INPUT FIELD HANYA BISA NOMOR
        document.getElementById('nomor_rekening').addEventListener('input', function(e) {
            let value = this.value.replace(/[^0-9]/g, '');
            if (value.length > 14) {
                value = value.slice(0, 10);
            }
            this.value = value;
        });

        // Tambahkan fungsi untuk memformat input nominal memiliki titik
        function formatnopolInput(selector) {
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

                console.log(cleanValue);

                // Pastikan elemen hidden dengan ID yang benar diperbarui
                const hiddenId = `#hidden_${$(this).attr('id').replace('total-', 'nominal')}`;
                console.log(hiddenId);
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
        let rowRekCount = 0;

        //ADD ROW NOMOR REKENING
        function addRekRow(pengaju, bank, rek) {
            // Ambil nilai dari input
            const namaPengaju = pengaju;
            const namaBank = bank;
            const nomorRekening = rek;

            rowRekCount++;
            if (namaPengaju != '' && namaBank != '' && nomorRekening != '') {
                const rekRow = `
                <tr id="rek-${rowRekCount}">
                    <td class="rek-number">${rowRekCount}</td>
                    <td>
                    <input name="nama_rek[${rowRekCount}]" id="nama_rek-${rowRekCount}" value="${namaPengaju}" style="border: none; pointer-events: none; color: #666">
                    <input type="hidden" id="hidden_rekId${rowRekCount}" name="hidden_rekId[${rowRekCount}]" value="">
                    </td>
                    <td>
                    <input name="nama_bank[${rowRekCount}]" id="nama_bank-${rowRekCount}" value="${namaBank}" style="border: none; pointer-events: none; color: #666">
                    </td>
                    <td><input name="no_rek[${rowRekCount}]" id="no_rek-${rowRekCount}" value="${nomorRekening}" style="border: none; pointer-events: none; color: #666"></td>
                    <td><button type="button" class="btn rek-delete btn-danger" data-id="${rowRekCount}">Delete</button></td>
                </tr>
            `;
                $('#rek-table tbody').append(rekRow);
            }
        }

        // ADD Row Detail Invoice
        function addRow() {
            rowCount++;
            const row = `
                <tr id="row-${rowCount}">
                    <td class="row-number">${rowCount}</td>
                    <td>
                        <input type="text" class="form-control" name="item[${rowCount}]" value="" placeholder="Item" /></td>
                        <input type="hidden" id="hidden_id${rowCount}" name="hidden_id[${rowCount}]" value="">
                        <input type="hidden" name="hidden_invoiceId[${rowCount}]" id="hidden_invoiceId${rowCount}" value="">
                    <td>
                        <input type="text" class="form-control nopol" id="nopol-${rowCount}" name="nopol[${rowCount}]" value="" placeholder="nopol">
                    </td>
                    <td>
                        <input type="text" class="form-control tipe" id="tipe-${rowCount}" name="tipe[${rowCount}]" value="" placeholder="tipe" />
                    </td>
                    <td>
                        <input type="text" class="form-control total" id="total-${rowCount}" name="total[${rowCount}]" value="" placeholder="Total"/>
                        <input type="hidden" id="hidden_nominal${rowCount}" name="hidden_nominal[${rowCount}]" value="">
                    </td>
                    <td><span class="btn delete-btn" style="background-color: red; color: white" data-id="${rowCount}">Delete</span></td>
                </tr>
                `;
            $('#input-container').append(row);
            // Tambahkan format ke input nominal yang baru
            formatnopolInput(`#total-${rowCount}`);
            updateSubmitButtonState(); // Perbarui status tombol submit
            //checkDeleteButtonState(); // Cek tombol delete setelah baris ditambahkan

            // Hitung total nominal setelah baris baru ditambahkan
            calculateTotalNominal();

            //VALIDASI ROW YANG TELAH DI APPEND
            $("#form").validate().settings.rules[`item[${rowCount}]`] = {
                required: true
            };
            $("#form").validate().settings.rules[`nopol[${rowCount}]`] = {
                required: true
            };
            $("#form").validate().settings.rules[`day[${rowCount}]`] = {
                required: true
            };
            $("#form").validate().settings.rules[`tipe[${rowCount}]`] = {
                required: true
            };
            $("#form").validate().settings.rules[`total[${rowCount}]`] = {
                required: true
            };
        }

        // MENGHAPUS ROW
        function deleteRow(id) {
            // Simpan ID dari row yang dihapus
            const rowId = $(`#row-${id}`).find('input:hidden[id^="hidden_id"]').val();
            if (rowId) {
                deletedRows.push(rowId);
            }

            // console.log(rowId);

            $(`#row-${id}`).remove();
            // Reorder rows and update row numbers
            reorderRows();
            updateSubmitButtonState(); // Perbarui status tombol 
            //checkDeleteButtonState(); // Cek tombol delete setelah baris dihapus

            // Hitung total nominal setelah baris dihapus
            calculateTotalNominal();
        }

        // MENGHAPUS REKENING
        function deleteRekRow(id) {
            // Simpan ID dari row yang dihapus
            const rowRekId = $(`#rek-${id}`).find('input:hidden[id^="hidden_rekId"]').val();
            if (rowRekId) {
                deletedRekRows.push(rowRekId);
            }

            console.log(rowRekId);

            $(`#rek-${id}`).remove();
            // Reorder rows and update row numbers
            reorderRekRows();
        }

        // REORDER DETAIL INVOICE
        function reorderRows() {
            $('#input-container tr').each(function(index) {
                //INISIASI VARIABLE UNTUK reorderRows
                const newRowNumber = index + 1;
                const itemValue = $(this).find('input[name^="item"]').val();
                const nopolValue = $(this).find('input[name^="nopol"]').val();
                const tipeValue = $(this).find('input[name^="tipe"]').val();
                const totalValue = $(this).find('input[name^="total"]').val();
                const hiddenNominalValue = $(this).find('input[name^="hidden_nominal"]').val();
                const hiddenInvoiceIdValue = $(this).find('input[name^="hidden_invoiceId"]').val();
                const hiddenIdValue = $(this).find('input[name^="hidden_id"]').val();

                $(this).attr('id', `row-${newRowNumber}`);
                $(this).find('.row-number').text(newRowNumber);
                $(this).find('input[name^="item"]').attr('name', `item[${newRowNumber}]`).attr('placeholder', `Item`).val(itemValue);
                $(this).find('input[name^="nopol"]').attr('name', `nopol[${newRowNumber}]`).attr('id', `nopol-${newRowNumber}`).attr('placeholder', `nopol`).val(nopolValue);
                $(this).find('input[name^="tipe"]').attr('name', `tipe[${newRowNumber}]`).attr('id', `tipe-${newRowNumber}`).attr('placeholder', `tipe`).val(tipeValue);
                $(this).find('input[name^="total"]').attr('name', `total[${newRowNumber}]`).attr('id', `total-${newRowNumber}`).attr('placeholder', `Total`).val(totalValue);
                $(this).find('input[name^="hidden_nominal"]').attr('name', `hidden_nominal[${newRowNumber}]`).attr('id', `hidden_nominal${newRowNumber}`).val(hiddenNominalValue);
                $(this).find('input[name^="hidden_invoiceId"]').attr('name', `hidden_invoiceId[${newRowNumber}]`).attr('id', `hidden_invoiceId${newRowNumber}`).val(hiddenInvoiceIdValue);
                $(this).find('input[name^=hidden_id]').attr('name', `hidden_id[${newRowNumber}]`).attr('id', `hidden_id[${newRowNumber}]`).val(hiddenIdValue);
                $(this).find('.delete-btn').attr('data-id', newRowNumber).text('Delete');
            });
            rowCount = $('#input-container tr').length; // Update rowCount to the current number of rows
        }

        // REORDER NOMOR REKENING
        function reorderRekRows() {
            $('#rek-table tbody tr').each(function(index) {
                const newRekRowNumber = index + 1;
                const hiddenRekIdValue = $(this).find('input[name^="hidden_rekId"]').val();
                const namaRekValue = $(this).find('input[name^="nama_rek"]').val();
                const namaBankValue = $(this).find('input[name^="nama_bank"]').val();
                const noRekValue = $(this).find('input[name^="no_rek"]').val();

                $(this).attr('id', `rek-${newRekRowNumber}`);
                $(this).find('.rek-number').text(newRekRowNumber);
                $(this).find('input[name^="nama_rek"]').attr('name', `nama_rek[${newRekRowNumber}]`).attr('id', `nama_rek-${newRekRowNumber}`).attr('placeholder', `Nama...`).val(namaRekValue);
                $(this).find('input[name^="nama_bank"]').attr('name', `nama_bank[${newRekRowNumber}]`).attr('id', `nama_bank-${newRekRowNumber}`).attr('placeholder', `Bank...`).val(namaBankValue);
                $(this).find('input[name^="no_rek"]').attr('name', `no_rek[${newRekRowNumber}]`).attr('id', `no_rek-${newRekRowNumber}`).attr('placeholder', `Nomor Rekening...`).val(noRekValue);
                $(this).find('input[name^="hidden_rekId"]').attr('name', `hidden_rekId[${newRekRowNumber}]`).attr('id', `hidden_rekId${newRekRowNumber}`).val(hiddenRekIdValue);
                $(this).find('.rek-delete').attr('data-id', newRekRowNumber).text('Delete');
            });
            rowRekCount = $('#rek-table tbody tr').length;
        }

        // BUTTON ADD ROW DETAIL TRANSAKSI
        $('#add-row').click(function() {
            addRow();
        });

        // BUTTON ADD ROW NOMOR REKENING
        $('#btn-rek').click(function() {
            var pengaju = $('#nama_rek').val();
            var bank = $('#nama_bank').val();
            var rek = $('#nomor_rekening').val();
            addRekRow(pengaju, bank, rek);
            $('#nama_rek').val('');
            $('#nama_bank').val('');
            $('#nomor_rekening').val('');
        });

        // SELECT ADD ROW NOMOR REKENING
        $('#rekening').change(function() {
            // Ambil elemen yang dipilih
            var selectedOption = $(this).find(':selected');

            // Ambil nilai atribut data
            var pengaju = selectedOption.data('pengaju');
            var bank = selectedOption.data('bank');
            var rek = selectedOption.data('rek');

            addRekRow(pengaju, bank, rek);
            // // Cetak ke konsol untuk memastikan
            // console.log('Bank:', bank);
            // console.log('Rekening:', rek);
        });

        function updateSubmitButtonState() {
            const rowCount = $('#input-container tr').length;
            if (rowCount > 0) {
                $('.aksi').prop('disabled', false); // Enable submit button
            } else {
                $('.aksi').prop('disabled', true); // Disable submit button
            }
        }

        // BUTTON HAPUS TRANSAKSI INVOICE
        $(document).on('click', '.delete-btn', function() {
            const id = $(this).data('id');
            deleteRow(id);
            hitungTotalAkhir();
        });

        // BUTTON HAPUS NOMOR REKENING
        $(document).on('click', '.rek-delete', function() {
            const rowId = $(this).data('id');
            // console.log(rowId);
            deleteRekRow(rowId);
            reorderRekRows();
        });

        $('#form').submit(function(event) {
            // Tambahkan array deletedRows ke dalam form data sebelum submit
            $('<input>').attr({
                type: 'hidden',
                name: 'deleted_rows',
                value: JSON.stringify(deletedRows)
            }).appendTo('#form');

            $('<input>').attr({
                type: 'hidden',
                name: 'deletedRekRows',
                value: JSON.stringify(deletedRekRows)
            }).appendTo('#form');

            // Lanjutkan dengan submit form
        });

        function hitungTotalAkhir() {
            let totalAkhir = 0;

            $('.total').each(function() {
                let totalValue = parseFloat($(this).val().replace(/\./g, '').replace(',', '.')) || 0;
                totalAkhir += totalValue;
            });

            $('#total_akhir').val(totalAkhir.toLocaleString('id-ID'));
        }

        // Panggil fungsi setelah semua baris ditambahkan
        $(document).ready(function() {
            hitungTotalAkhir(); // Hitung total saat halaman selesai dimuat
        });

        // Panggil fungsi setiap kali total berubah
        $(document).on('input', '.total', function() {
            hitungTotalAkhir();
        });

        // MENGISI FORM UPDATE
        if (id == 0) {
            $('.aksi').append('<span class="front front-aksi">Save</span>');
        } else {
            $('.aksi').append('<span class="front front-aksi">Update</span>');
            $("select option[value='']").hide();
            $.ajax({
                url: "<?php echo site_url('sml_invoice/edit_data') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    // console.log(data);
                    let total_nominal = 0;
                    for (let index = 0; index < data['detail_invoice'].length; index++) {
                        total_nominal += parseInt(data['detail_invoice'][index]['total'], 10);
                    }
                    console.log(total_nominal);
                    // SET VALUE DATA MASTER PREPAYMENT
                    $('#id').val(data['master']['id']);
                    $('#tgl_invoice').val(data['master']['tgl_invoice']);
                    $('#tgl_tempo').val(data['master']['tgl_tempo']);
                    $('#kode_invoice').val(data['master']['kode_invoice']);
                    $('#ctc_to').val(data['master']['ctc_to']);
                    $('#ctc_address').val(data['master']['ctc_address']);
                    $('#metode').val(data['master']['metode']);
                    if (data['master']['tax'] > 0) {
                        $('#tax').val(formatRupiah(data['master']['tax']));
                    }
                    if (data['master']['tax'] > 0) {
                        $('#diskon').val(formatRupiah(data['master']['diskon']));
                    }
                    // if (data['master']['total'] == null) {
                    $('#total_nominal_view').text(total_nominal.toLocaleString());
                    $('#total_nominal').val(total_nominal);
                    // } else {
                    //     total_nominal = parseInt(data['master']['total_nominal'], 10);
                    //     $('#total_nominal_view').text(total_nominal.toLocaleString());
                    //     $('#total_nominal').val(data['master']['total_nominal']);
                    // }

                    //APPEND DATA swi_rek_invoice DETAIL PREPAYMENT
                    console.log(data['rek_invoice']);
                    if (aksi == 'update') {
                        // Rekening
                        $(data['rek_invoice']).each(function(index) {
                            const row = `
                            <tr id="rek-${index + 1}">
                                <td class="rek-number">${index + 1}</td>
                                <td>
                                <input name="nama_rek[${index+1}]" id="nama_rek-${index + 1}" value="${data['rek_invoice'][index]['nama']}" style="border: none; pointer-events: none; color: #666">
                                <input type="hidden" id="hidden_rekId${index + 1}" name="hidden_rekId[${index + 1}]" value="${data['rek_invoice'][index]['id']}">
                                </td>
                                <td>
                                <input name="nama_bank[${index+1}]" id="nama_bank-${index + 1}" value="${data['rek_invoice'][index]['nama_bank']}" style="border: none; pointer-events: none; color: #666">
                                </td>
                                <td><input name="no_rek[${index+1}]" id="no_rek-${index + 1}" value="${data['rek_invoice'][index]['no_rek']}" style="border: none; pointer-events: none; color: #666"></td>
                                <td><button type="button" class="btn rek-delete btn-danger" data-id="${index + 1}">Delete</button></td>
                            </tr>
                            `;
                            $('#rek-table tbody').append(row);
                            rowRekCount = index + 1;
                        });

                        // Detail pemesanan
                        $(data['detail_invoice']).each(function(index) {
                            const tipeFormatted = data['detail_invoice'][index]['tipe'].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                            const totalFormatted = data['detail_invoice'][index]['total'].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                            const row = `
                            <tr id="row-${index + 1}">
                                <td class="row-number">${index + 1}</td>
                                <td>
                                    <input type="text" class="form-control" name="item[${index + 1}]" value="${data['detail_invoice'][index]['deskripsi']}" placeholder="Item" /></td>
                                    <input type="hidden" id="hidden_id${index + 1}" name="hidden_id[${index + 1}]" value="${data['detail_invoice'][index]['id']}">
                                    <input type="hidden" name="hidden_invoiceId[${index + 1}]" id="hidden_invoiceId${index + 1}" value="${data['detail_invoice'][index]['invoice_id']}">
                                <td>
                                    <input type="text" class="form-control nopol" id="nopol-${index + 1}" name="nopol[${index + 1}]" value="${data['detail_invoice'][index]['nopol']}" placeholder="nopol">
                                </td>
                                <td>
                                    <input type="text" class="form-control tipe" id="tipe-${index + 1}" name="tipe[${index + 1}]" value="${tipeFormatted}" placeholder="tipe" />
                                </td>
                                <td>
                                    <input type="text" class="form-control total" id="total-${index + 1}" name="total[${index + 1}]" value="${totalFormatted}" placeholder="Total" />
                                    <input type="hidden" id="hidden_nominal${index + 1}" name="hidden_nominal[${index + 1}]" value="${data['detail_invoice'][index]['total']}">
                                </td>

                                <td><span class="btn delete-btn btn-danger" data-id="${index + 1}">Delete</span></td>
                            </tr>
                            `;
                            $('#input-container').append(row);
                            rowCount = index + 1;

                            hitungTotalAkhir();

                            $('#tgl_invoice').css({
                                'pointer-events': 'none'
                            });

                            $('.tipe, .total').on('input', function() {
                                // Ambil nilai input
                                let value = $(this).val();

                                // Hapus semua karakter yang bukan angka
                                value = value.replace(/[^0-9]/g, '');

                                // Format ke Rupiah
                                let formatted = new Intl.NumberFormat('id-ID').format(value);

                                // Set nilai input dengan format Rupiah
                                $(this).val(formatted);
                            });
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
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
                url = "<?php echo site_url('sml_invoice/add') ?>";
            } else {
                url = "<?php echo site_url('sml_invoice/update') ?>";
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
                            checkNotifications();
                            location.href = "<?= base_url('sml_invoice') ?>";
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

        $("#form").validate({
            rules: {
                tgl_invoice: {
                    required: true,
                },
                kode_invoice: {
                    required: true,
                },
                ctc_to: {
                    required: true,
                },
                ctc_address: {
                    required: true,
                },
                // tax: {
                //     required: true,
                // },
            },
            messages: {
                tgl_invoice: {
                    required: "Tanggal Invoice is required",
                },
                kode_invoice: {
                    required: "Kode Invoice is required",
                },
                ctc_to: {
                    required: "Contact To is required",
                },
                ctc_address: {
                    required: "Address is required",
                },
                // tax: {
                //     required: "Tax is required",
                // },
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