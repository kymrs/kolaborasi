<style>
    /* ...existing code... */
    .tag {
        display: inline-block;
        background: #1a2035;
        color: #fff;
        border-radius: 15px;
        padding: 3px 12px 3px 10px;
        margin: 2px 2px 2px 0;
        font-size: 0.95em;
        position: relative;
    }

    .tag .remove-tag {
        margin-left: 8px;
        cursor: pointer;
        color: #fff;
        font-weight: bold;
    }

    .tag .remove-tag:hover {
        color: red;
    }

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

    .colomn-kanan {
        height: 20px;
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
                    <a class="btn btn-primary btn-sm" href="javascript:window.history.back()"><i class="fas fa-chevron-left"></i>&nbsp;Back</a>
                </div>
                <div class="card-body">
                    <form id="form">

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

    const quill = new Quill('#catatan', {
        modules: {
            toolbar: toolbarOptions
        },
        placeholder: 'Catatan...',
        theme: 'snow',
    });

    // const quill2 = new Quill('#jamaah', {
    //     // modules: {
    //     //     toolbar: toolbarOptions
    //     // },
    //     placeholder: 'Jamaah...',
    //     theme: 'snow',
    // });

    // const quill3 = new Quill('#detail_pesanan', {
    //     // modules: {
    //     //     toolbar: toolbarOptions
    //     // },
    //     placeholder: 'Pesanan...',
    //     theme: 'snow',
    // });

    document.getElementById("form").onsubmit = function() {
        // Get HTML content from Quill editor
        var catatanItem = quill.root.innerHTML;
        // var jamaahItem = quill2.root.innerHTML;
        // var pesananItem = quill3.root.innerHTML;

        // Set it to hidden input
        document.getElementById("catatan_item").value = catatanItem;
        // document.getElementById("jamaah_item").value = jamaahItem;
        // document.getElementById("pesanan_item").value = pesananItem;
    };

    function handleTagInput(inputId, addBtnId, tagsDivId, hiddenInputId) {
        let tags = [];

        // Load dari hidden input saat init
        let initial = $('#' + hiddenInputId).val();
        if (initial) {
            tags = initial.split(',').map(e => e.trim()).filter(e => e);
            renderTags();
        }

        function renderTags() {
            let html = '';
            tags.forEach((tag, idx) => {
                html += `<span class="tag">${tag}<span class="remove-tag" data-idx="${idx}">&times;</span></span>`;
            });
            $('#' + tagsDivId).html(html);
            $('#' + hiddenInputId).val(tags.join(', '));
        }

        // Event input
        $('#' + addBtnId).off('click').on('click', function() {
            let val = $('#' + inputId).val().trim();
            if (val && !tags.includes(val)) {
                tags.push(val);
                renderTags();
                $('#' + inputId).val('');
            }
        });

        $('#' + inputId).off('keypress').on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                $('#' + addBtnId).click();
            }
        });

        $('#' + tagsDivId).off('click', '.remove-tag').on('click', '.remove-tag', function() {
            let idx = $(this).data('idx');
            tags.splice(idx, 1);
            renderTags();
        });

        return {
            setTags: function(arrayOfTags) {
                tags = arrayOfTags;
                renderTags();
            },
            getTags: function() {
                return tags;
            }
        };
    }


    let jamaahHandler, pesananHandler;

    $(document).ready(function() {
        jamaahHandler = handleTagInput('jamaah_input', 'add_jamaah', 'jamaah_tags', 'jamaah');
        pesananHandler = handleTagInput('pesanan_input', 'add_pesanan', 'pesanan_tags', 'pesanan');
    });


    $(document).ready(function() {
        $('#ctc_nomor').on('input', function() {
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
                url: "<?php echo site_url('pu_invoice/generate_kode') ?>",
                type: "POST",
                data: {
                    "date": dateText
                },
                dataType: "JSON",
                success: function(data) {
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
    });

    $('#tanggal_pembayaran').datepicker({
        dateFormat: 'yy-mm-dd',
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

        $('#total_order, #diskon').on('input', function() {
            // Ambil nilai input
            let value = $(this).val();

            // Hapus semua karakter yang bukan angka
            value = value.replace(/[^0-9]/g, '');

            // Format ke Rupiah
            let formatted = new Intl.NumberFormat('id-ID').format(value);

            // Set nilai input dengan format Rupiah
            $(this).val(formatted);
        });

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

        // ADD Row Detail Invoice
        function addRow() {
            rowCount++;
            const row = `
                <tr id="row-${rowCount}">
                    <td class="row-number">${rowCount}</td>
                    <td>
                        <input type="text" class="form-control" name="deskripsi[${rowCount}]" value="" placeholder="Deskripsi" /></td>
                        <input type="hidden" id="hidden_id${rowCount}" name="hidden_id[${rowCount}]" value="">
                        <input type="hidden" name="hidden_invoiceId[${rowCount}]" id="hidden_invoiceId${rowCount}" value="">
                    <td>
                        <input type="text" class="form-control jumlah" id="jumlah-${rowCount}" name="jumlah[${rowCount}]" value="" placeholder="Jumlah">
                    </td>
                    <td>
                        <input type="text" class="form-control harga" id="harga-${rowCount}" name="harga[${rowCount}]" value="" placeholder="Harga" />
                    </td>
                    <td>
                        <input type="text" class="form-control total" id="total-${rowCount}" name="total[${rowCount}]" value="" placeholder="Total" readonly />
                    </td>

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

            $('.harga, .total').on('input', function() {
                // Ambil nilai input
                let value = $(this).val();

                // Hapus semua karakter yang bukan angka
                value = value.replace(/[^0-9]/g, '');

                // Format ke Rupiah
                let formatted = new Intl.NumberFormat('id-ID').format(value);

                // Set nilai input dengan format Rupiah
                $(this).val(formatted);
            });

            $('.jumlah').on('input', function() {
                // Ambil nilai input
                let value = $(this).val();

                // Hapus semua karakter yang bukan angka
                value = value.replace(/[^0-9]/g, '');

                // Set nilai input dengan format Rupiah
                $(this).val(value);
            });

            //VALIDASI ROW YANG TELAH DI APPEND
            $("#form").validate().settings.rules[`deskripsi[${rowCount}]`] = {
                required: true
            };
            $("#form").validate().settings.rules[`jumlah[${rowCount}]`] = {
                required: true
            };
            $("#form").validate().settings.rules[`harga[${rowCount}]`] = {
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

            $(`#rek-${id}`).remove();
            // Reorder rows and update row numbers
            reorderRekRows();
        }

        // REORDER DETAIL INVOICE
        function reorderRows() {
            $('#input-container tr').each(function(index) {
                //INISIASI VARIABLE UNTUK reorderRows
                const newRowNumber = index + 1;
                const deskripsiValue = $(this).find('input[name^="deskripsi"]').val();
                const jumlahValue = $(this).find('input[name^="jumlah"]').val();
                const hargaValue = $(this).find('input[name^="harga"]').val();
                const totalValue = $(this).find('input[name^="total"]').val();
                const hiddenInvoiceIdValue = $(this).find('input[name^="hidden_invoiceId"]').val();
                const hiddenIdValue = $(this).find('input[name^="hidden_id"]').val();

                $(this).attr('id', `row-${newRowNumber}`);
                $(this).find('.row-number').text(newRowNumber);
                $(this).find('input[name^="deskripsi"]').attr('name', `deskripsi[${newRowNumber}]`).attr('placeholder', `Deskripsi`).val(deskripsiValue);
                $(this).find('input[name^="jumlah"]').attr('name', `jumlah[${newRowNumber}]`).attr('id', `jumlah-${newRowNumber}`).attr('placeholder', `Jumlah`).val(jumlahValue);
                $(this).find('input[name^="harga"]').attr('name', `harga[${newRowNumber}]`).attr('id', `harga-${newRowNumber}`).attr('placeholder', `Harga`).val(hargaValue);
                $(this).find('input[name^="total"]').attr('name', `total[${newRowNumber}]`).attr('id', `total-${newRowNumber}`).attr('placeholder', `Total`).val(totalValue);
                $(this).find('input[name^="hidden_invoiceId"]').attr('name', `hidden_invoiceId[${newRowNumber}]`).attr('id', `hidden_invoiceId${newRowNumber}`).val(hiddenInvoiceIdValue);
                $(this).find('input[name^=hidden_id]').attr('name', `hidden_id[${newRowNumber}]`).attr('id', `hidden_id[${newRowNumber}]`).val(hiddenIdValue);
                $(this).find('.delete-btn').attr('data-id', newRowNumber).text('Delete');
            });
            rowCount = $('#input-container tr').length; // Update rowCount to the current number of rows
        }

        // BUTTON ADD ROW DETAIL TRANSAKSI
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

        // BUTTON HAPUS TRANSAKSI INVOICE
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

            $('<input>').attr({
                type: 'hidden',
                name: 'deletedRekRows',
                value: JSON.stringify(deletedRekRows)
            }).appendTo('#form');

            // Lanjutkan dengan submit form
        });

        function extractListText(html) {
            const container = document.createElement('div');
            container.innerHTML = html;

            const lis = container.querySelectorAll('li');
            const result = [];

            lis.forEach(li => {
                // Ambil semua isi LI kecuali <span class="ql-ui">
                const cloned = li.cloneNode(true);
                const span = cloned.querySelector('span.ql-ui');
                if (span) span.remove();

                const text = cloned.textContent.trim();
                if (text !== '') result.push(text);
            });

            return result.join(', '); // atau pakai "\n" kalau mau multiline
        }


        // MENGISI FORM UPDATE
        if (id == 0) {
            $('.aksi').append('<span class="front front-aksi">Save</span>');
        } else if (aksi == 'new') {
            // READONLY INPUTAN INPUTAN
            $('#ctc_nama').prop('readonly', true);
            $('#jamaah_input').prop('readonly', true);
            $('#pesanan_input').prop('readonly', true);
            $('#ctc_alamat').prop('readonly', true);

            $('.aksi').append('<span class="front front-aksi">Save</span>');
            $('#total_order').prop('readonly', true);
            $.ajax({
                url: "<?php echo site_url('pu_invoice/edit_data') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    const jamaah = data.master.jamaah;
                    const list_jamaah = extractListText(jamaah).split(',').map(e => e.trim()).filter(e => e);

                    const pesanan = data.master.detail_pesanan;
                    const list_pesanan = extractListText(pesanan).split(',').map(e => e.trim()).filter(e => e);

                    $('#jamaah').val(list_jamaah.join(', ')); // isi hidden input
                    jamaahHandler.setTags(list_jamaah); // tampilkan tags otomatis!

                    $('#pesanan').val(list_pesanan.join(', '));
                    pesananHandler.setTags(list_pesanan);
                    $('#ctc_email').val(data['master']['ctc_email']);
                    $('#total_order').val((data['master']['total_order'] - data['master']['total_tagihan']).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
                    $('#ctc_nama').val(data['master']['ctc_nama']);
                    $('#order_id').val(data['master']['order_id']);
                    // $('#detail_pesanan').val(data['master']['detail_pesanan']);
                    $('#ctc_alamat').val(data['master']['ctc_alamat']);
                    $('#rekening').val(data['master']['travel_id']).trigger('change.select2');
                    // quill2.clipboard.dangerouslyPasteHTML(data['master']['jamaah']);
                    // quill3.clipboard.dangerouslyPasteHTML(data['master']['detail_pesanan']);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        } else if (aksi == 'update') {
            $('.aksi').append('<span class="front front-aksi">Update</span>');
            $("select option[value='']").hide();
            $.ajax({
                url: "<?php echo site_url('pu_invoice/edit_data') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    const jamaah = data.master.jamaah;
                    const list_jamaah = extractListText(jamaah).split(',').map(e => e.trim()).filter(e => e);

                    const pesanan = data.master.detail_pesanan;
                    const list_pesanan = extractListText(pesanan).split(',').map(e => e.trim()).filter(e => e);

                    $('#jamaah').val(list_jamaah.join(', ')); // isi hidden input
                    jamaahHandler.setTags(list_jamaah); // tampilkan tags otomatis!

                    $('#pesanan').val(list_pesanan.join(', '));
                    pesananHandler.setTags(list_pesanan);

                    //SET VALUE DATA MASTER PREPAYMENT
                    $('#id').val(data['master']['id']);
                    $('#ctc_email').val(data['master']['ctc_email']);
                    let dateParts = data['master']['tgl_invoice'].split('-'); // Pisahkan berdasarkan "-"
                    let formattedDate = `${dateParts[2]}-${dateParts[1]}-${dateParts[0]}`; // Susun jadi DD-MM-YYYY
                    $('#tgl_invoice').val(formattedDate); // Masukkan ke input
                    $('#kode_invoice').val(data['master']['kode_invoice']);
                    $('#tgl_tempo').val(data['master']['tgl_tempo']);
                    $('#tanggal_pembayaran').val(data['master']['tanggal_pembayaran']);
                    $('#rekening').val(data['master']['travel_id']).trigger('change.select2');
                    $('#diskon').val(data['master']['diskon']);
                    $('#ctc_nama').val(data['master']['ctc_nama']);
                    $('#detail_pesanan').val(data['master']['detail_pesanan']);
                    $('#ctc_alamat').val(data['master']['ctc_alamat']);
                    $('#total_order').val(data['master']['total_order'].toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
                    quill.clipboard.dangerouslyPasteHTML(data['master']['keterangan']);
                    // quill2.clipboard.dangerouslyPasteHTML(data['master']['jamaah']);
                    // quill3.clipboard.dangerouslyPasteHTML(data['master']['detail_pesanan']);
                    //APPEND DATA pu_rek_invoice DETAIL PREPAYMENT
                    if (aksi == 'update') {

                        // Detail pemesanan
                        $(data['detail_invoice']).each(function(index) {
                            const hargaFormatted = data['detail_invoice'][index]['harga'].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                            const totalFormatted = data['detail_invoice'][index]['total'].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                            const row = `
                            <tr id="row-${index + 1}">
                                <td class="row-number">${index + 1}</td>
                                <td>
                                    <input type="text" class="form-control" name="deskripsi[${index + 1}]" value="${data['detail_invoice'][index]['deskripsi']}" placeholder="Deskripsi" /></td>
                                    <input type="hidden" id="hidden_id${index + 1}" name="hidden_id[${index + 1}]" value="${data['detail_invoice'][index]['id']}">
                                    <input type="hidden" name="hidden_invoiceId[${index + 1}]" id="hidden_invoiceId${index + 1}" value="${data['detail_invoice'][index]['invoice_id']}">
                                <td>
                                    <input type="text" class="form-control jumlah" id="jumlah-${index + 1}" name="jumlah[${index + 1}]" value="${data['detail_invoice'][index]['jumlah']}" placeholder="Jumlah" style="margin-left: 10px">
                                </td>
                                <td>
                                    <input type="text" class="form-control harga" id="harga-${index + 1}" name="harga[${index + 1}]" value="${hargaFormatted}" placeholder="Harga" />
                                </td>
                                <td>
                                    <input type="text" class="form-control total" id="total-${index + 1}" name="total[${index + 1}]" value="${totalFormatted}" placeholder="Harga" readonly />
                                </td>

                                <td><span class="btn delete-btn btn-danger" data-id="${index + 1}">Delete</span></td>
                            </tr>
                            `;
                            $('#input-container').append(row);
                            rowCount = index + 1;

                            // Tambahkan event handler agar field harga & total tetap bertitik saat diketik
                            $(`#harga-${index + 1}, #total-${index + 1}`).on('input', function() {
                                let value = $(this).val();
                                value = value.replace(/[^0-9]/g, '');
                                let formatted = new Intl.NumberFormat('id-ID').format(value);
                                $(this).val(formatted);
                            });
                        });

                        // // detail jamaah
                        // $(data['jamaah']).each(function(index) {
                        //     const row = `
                        //     <tr id="jamaah-${index + 1}">
                        //         <td class="row-number">${index + 1}</td>
                        //         <td>
                        //             <input type="text" class="form-control" name="jamaah[${index + 1}]" value="${data['jamaah'][index]}" placeholder="Nama Jamaah" style="border: none; pointer-events: none; color: #666"/>
                        //         </td>
                        //         <td><span class="btn delete-jamaah btn-danger" data-id="${index + 1}">Delete</span></td>
                        //     </tr>
                        //     `;
                        //     $('#jamaah-table tbody').append(row);
                        //     jamaahCount = index + 1;
                        // });
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
            $('#tgl_invoice').prop('disabled', true);
            $('#nama').prop('readonly', true);
            // $('#jabatan').prop('disabled', true);
            // $('#divisi').prop('disabled', true);
            $('#prepayment').prop('readonly', true);
            $('#tujuan').prop('readonly', true);
            $('#total_nominal_row').attr('colspan', 3);
            $('#add-row').toggle();
            $('th:last-child').remove();

            $.ajax({
                url: "<?php echo site_url('pu_invoice/read_detail/') ?>" + id,
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
            if (id == 0 || aksi == 'new') {
                url = "<?php echo site_url('pu_invoice/add') ?>";
            } else if (aksi == 'update') {
                url = "<?php echo site_url('pu_invoice/update') ?>";
            }

            const quillText = quill.getText().trim();
            // const quill2Text = quill2.getText().trim();
            // Get HTML content from Quill editor
            var catatanItem = quill.root.innerHTML;
            // var jamaahItem = quill2.root.innerHTML;

            // Tampilkan loading
            // $('#loading').show();

            $('.aksi').prop('disabled', true);

            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin menyimpan data ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Simpan',
                cancelButtonText: 'Batal',
                allowOutsideClick: false,
                allowEscapeKey: false,
                preConfirm: () => {
                    // Tampilkan loading di swal
                    Swal.showLoading();
                    // Disable tombol submit agar tidak bisa diklik berkali-kali
                    $('.aksi').prop('disabled', true);

                    // Kembalikan promise agar swal menunggu ajax selesai
                    return new Promise((resolve, reject) => {
                        $.ajax({
                            url: url,
                            type: "POST",
                            data: $('#form').serialize(),
                            dataType: "JSON",
                            success: function(data) {
                                $('#loading').hide();
                                if (data.status) {
                                    resolve();
                                    Swal.fire({
                                        position: 'center',
                                        icon: 'success',
                                        title: 'Your data has been saved',
                                        showConfirmButton: false,
                                        timer: 1500
                                    }).then(() => {
                                        checkNotifications();
                                        location.href = "<?= base_url('pu_invoice') ?>";
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: data.message
                                    });
                                    $('.aksi').prop('disabled', false);
                                    reject();
                                }
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                // $('#loading').hide();
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Error adding / updating data: ' + textStatus
                                });
                                $('.aksi').prop('disabled', false);
                                reject();
                            }
                        });
                    });
                }
            });
            // }
        });

        $("#form").validate({
            rules: {
                ctc_nama: {
                    required: true,
                },
                tgl_invoice: {
                    required: true,
                },
                kode_invoice: {
                    required: true,
                },
                tgl_tempo: {
                    required: true,
                },
                ctc_email: {
                    required: true,
                },
                ctc_alamat: {
                    required: true,
                },
                total_order: {
                    required: true,
                }
            },
            messages: {
                ctc_nama: {
                    required: "Contact Person is required",
                },
                tgl_invoice: {
                    required: "Tanggal Invoice is required",
                },
                kode_invoice: {
                    required: "Kode Invoice is required",
                },
                tgl_tempo: {
                    required: "Tanggal Tempo is required",
                },
                ctc_email: {
                    required: "Email is required",
                },
                ctc_alamat: {
                    required: "Contact Nomor is required",
                },
                total_order: {
                    required: "Total Order is required",
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

        // Delegasi ke container yang sudah ada di awal
        $('#input-container').on('input', '.jumlah, .harga', function() {

            const currentRow = $(this).closest('tr').attr('id').split('-')[1];
            const jumlahInput = $(`#jumlah-${currentRow}`);
            const hargaInput = $(`#harga-${currentRow}`);
            const totalInput = $(`#total-${currentRow}`);

            // Ambil nilai mentah dan hilangkan titik (format ribuan)
            let jumlah = jumlahInput.val().replace(/\./g, '').replace(/[^0-9]/g, '');
            let harga = hargaInput.val().replace(/\./g, '').replace(/[^0-9]/g, '');

            jumlah = parseInt(jumlah) || 0;
            harga = parseInt(harga) || 0;

            const total = jumlah * harga;

            totalInput.val(new Intl.NumberFormat('id-ID').format(total));
        });

    })
</script>