<head>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .container-custom {
            max-width: 1000px;
            /* Maksimal lebar container */
        }

        .editor-with-border .ql-container {
            border: 1px solid #ccc;
            /* Border warna abu-abu */
            border-radius: 5px;
            /* Membuat sudut sedikit melengkung */
            padding: 10px;
            /* Tambahkan padding di dalam editor */
            min-height: 150px;
            /* Pastikan tinggi minimum editor */
        }

        .logo-custom {
            width: 550px;
            height: auto;
        }

        .label-inline {
            display: inline-block;
            min-width: 150px;
            /* Lebar minimum untuk label */
            font-weight: bold;
        }

        .value-inline {
            display: inline-block;
        }

        .orange-box,
        .biaya-box,
        .ekstra-box {
            background-color: #FC7714;
            /* Warna oranye lebih gelap */
            color: white;
            padding: 10px;
            text-align: center;
            /* Teks di tengah */
            font-size: 1.00rem;
            /* Ukuran teks */
            font-weight: bold;
            /* Teks tebal */
            margin-bottom: 1rem;
            /* Jarak di bawah setiap box */
        }

        .description {
            color: #333;
            font-size: 1rem;
            margin-bottom: 1rem;
            line-height: 1.75;
        }

        .list-item {
            list-style: decimal;
            margin-left: 1.5rem;
            color: #333;
            margin-bottom: 1rem;
        }

        .section-title {
            font-weight: bold;
            color: #FC7714;
            margin-bottom: 0.5rem;
        }

        .right-section {
            padding-left: 1rem;
        }

        .price-text {
            font-size: 2rem;
            /* Ukuran teks harga lebih besar */
            color: #333;
            text-align: center;
            font-weight: bold;
            margin-bottom: 1.5rem;
        }

        .promo-text {
            text-align: left;
            color: #333;
            font-size: 1rem;
            margin-top: 1.5rem;
            margin-bottom: 1.5rem;
        }

        /* modal */
        .modal-layanan {
            background-color: #FC7714;
            padding: 7px 12px;
            border-radius: 5px;
            color: white;
            transition: 300ms;
        }

        .modal-layanan:hover {
            background-color: #FC7714;
            scale: 0.965;
        }

        #layananModal .modal-body .layanan {
            display: flex;
            align-items: center;
        }

        #layananModal .modal-body .layanan label {
            margin: 0;
            cursor: pointer;
        }

        #layananModal .modal-body .input-biaya {
            border: 2px solid rgba(0, 0, 0, 0.5);
            padding: 5px 10px;
            box-sizing: border-box;
            border-radius: 7px;
        }

        /* Style untuk tombol */
        .checklistButton {
            width: 50px;
            height: 50px;
            border: none;
            outline: none;
            background-color: transparent;
            cursor: pointer;
            font-size: 24px;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Tambahkan warna untuk masing-masing status */
        .uncheck i {
            color: gray;
            /* Warna untuk kotak kosong */
        }

        .check i {
            color: green;
            /* Warna untuk centang */
        }

        .times i {
            color: red;
            /* Warna untuk silang */
        }

        #durasi {
            width: 50px;
        }

        /* Responsive Adjustments */
        @media (max-width: 1024px) {
            .logo-custom {
                max-width: 400px;
            }

            .label-inline {
                min-width: 120px;
            }

            .description {
                font-size: 0.95rem;
            }

            .section-title {
                font-size: 1.1rem;
            }

            .price-text {
                font-size: 1.75rem;
            }
        }

        @media (max-width: 768px) {
            .label-inline {
                min-width: 100px;
            }

            .description {
                font-size: 0.9rem;
            }

            .section-title {
                font-size: 1rem;
            }

            .price-text {
                font-size: 1.5rem;
            }

            .promo-text {
                font-size: 0.9rem;
            }
        }

        @media (max-width: 640px) {
            .logo-custom {
                max-width: 300px;
            }

            .label-inline {
                display: block;
                min-width: unset;
            }

            .value-inline {
                display: block;
            }

            .description {
                font-size: 0.85rem;
            }

            .section-title {
                font-size: 0.95rem;
            }

            .price-text {
                font-size: 1.25rem;
            }

            .promo-text {
                font-size: 0.85rem;
                text-align: center;
            }
        }

        @media (max-width: 480px) {
            .price-text {
                font-size: 1rem;
            }
        }
    </style>
</head>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title_view ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header text-right">
                    <a class="btn btn-secondary btn-sm" href="<?= base_url('penawaran_pu') ?>"><i class="fas fa-chevron-left"></i>&nbsp;Back</a>
                </div>
                <div class="card-body">
                    <form id="form">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-5">No Pelayanan</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="no_pelayanan" name="no_pelayanan" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5">Kepada</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="pelanggan" name="pelanggan">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5">Produk</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="produk" name="produk">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5">Alamat</label>
                                    <div class="col-sm-7">
                                        <textarea id="alamat" rows="4" name="alamat" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Write your thoughts here..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Orange Box Section (Layanan) -->
                        <div class="container-custom mx-auto mt-3">
                            <div class="orange-box">
                                LAYANAN
                            </div>
                        </div>

                        <!-- GENERATE INFORMASI LAYANAN -->
                        <div class="container-custom mx-auto mt-3 flex flex-wrap justify-between">
                            <!-- Left Section: Deskripsi dan Layanan Termasuk -->
                            <div class="w-full md:w-1/2 p-4">
                                <!-- Deskripsi -->
                                <h2 class="section-title">Deskripsi:</h2>
                                <textarea id="deskripsi" rows="4" name="deskripsi" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Write your thoughts here..."></textarea>
                            </div>

                            <!-- Right Section: Informasi Tambahan -->
                            <div class="w-full md:w-1/2 p-4">
                                <!-- tabggal berlaku -->
                                <div class="mb-1">
                                    <h2 class="section-title">Tanggal berlaku:</h2>
                                    <input type="datetime-local" id="tgl_berlaku" name="tgl_berlaku" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                                </div>
                                <!-- Keberangkatan -->
                                <div class="mb-1">
                                    <h2 class="section-title">Keberangkatan:</h2>
                                    <input type="datetime-local" id="keberangkatan" name="keberangkatan" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                                </div>
                                <!-- Durasi -->
                                <div class="mb-1">
                                    <h2 class="section-title">Durasi:</h2>
                                    <div style="display: flex; align-items: center">
                                        <input type="text" id="durasi" name="durasi" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/2 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <h2 class="section-title" style="margin-left: 10px;">Hari</h2>
                                    </div>
                                </div>
                                <!-- Berangkat Dari -->
                                <div class="mb-1">
                                    <h2 class="section-title">Tempat:</h2>
                                    <input type="text" id="tempat" name="tempat" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/2 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                </div>
                            </div>

                            <div class="w-full md:w-1/2 p-4">
                                <!-- Layanan Termasuk -->
                                <h2 class="section-title">Layanan:</h2>
                                <!-- <div id="layanan" name="layanan" class="h-40 mb-4"></div>
                                <input type="hidden" name="editor_content" id="editor_content"> -->

                                <button type="button" class="modal-layanan" data-toggle="modal" data-target="#layananModal">
                                    Pilih Layanan
                                </button>
                                <!-- Modal -->
                                <div class="modal fade" id="layananModal" tabindex="-1" aria-labelledby="layananModal" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="layananModal">Layanan</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div style="text-align: center;">
                                                    <i class="fa fa-check" style="color: green; margin: 0 7px 0 15px"></i>Termasuk
                                                    <i class="fa fa-times" style="color: red; margin: 0 7px 0 15px"></i>Tidak Termasuk
                                                    <i class="fa fa-square" style="color: gray; margin: 0 7px 0 15px"></i>Tidak Ditampilkan
                                                    <hr style="margin-top: 10px;">
                                                </div>
                                                <?php foreach ($layanan as $data) : ?>
                                                    <div class="layanan">
                                                        <input type="hidden" name="id_layanan[]" value="<?= htmlspecialchars($data['id'], ENT_QUOTES) ?>">
                                                        <button type="button" id="button-<?= htmlspecialchars($data['id'], ENT_QUOTES) ?>" class="uncheck checklistButton">
                                                            <i class="fa fa-square"></i>
                                                        </button>
                                                        <label for="button-<?= htmlspecialchars($data['id'], ENT_QUOTES) ?>">
                                                            <?= htmlspecialchars($data['nama_layanan'], ENT_QUOTES) ?>
                                                        </label>
                                                        <input type="hidden" name="status[]" id="input-<?= htmlspecialchars($data['id'], ENT_QUOTES) ?>" value="">
                                                        <input type="text" name="nominal[]" id="extra-input-<?= htmlspecialchars($data['id'], ENT_QUOTES) ?>" class="extra-input input-biaya" style="display: none;" placeholder="Nominal" required>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-primary" data-dismiss="modal">Simpan</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Biaya Section -->
                        <div class="container-custom mx-auto mt-3">
                            <div class="biaya-box">
                                BIAYA
                            </div>
                            <input type="text" id="biaya" name="biaya" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/2 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>

                        <!-- Ekstra Section -->
                        <div class="container-custom mx-auto mt-5 mb-3">
                            <div class="ekstra-box">
                                EKSTRA
                            </div>
                            <div id="extra" name="extra" class="h-40 mb-4"></div>
                            <input type="hidden" name="catatan_content" id="catatan_content">
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
                        <!-- END PENENTUAN UPDATE ATAU ADD -->
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    // $('#tgl_berlaku').datepicker({
    //     dateFormat: 'dd-mm-yy',
    //     minDate: new Date(),
    //     maxDate: new Date(),
    // });

    // $('#keberangkatan').datepicker({
    //     dateFormat: 'dd-mm-yy',
    //     minDate: new Date(),
    //     maxDate: new Date(),
    // });

    // var quill = new Quill('#layanan', {
    //     theme: 'snow'
    // });

    // var quill2 = new Quill('#extra', {
    //     theme: 'snow'
    // });

    // var quillContent = `<p> Lalaland </p>`;

    // quill.clipboard.dangerouslyPasteHTML(quillContent);

    // document.getElementById("form").onsubmit = function() {
    //     // Get HTML content from Quill editor
    //     var layananContent = quill.root.innerHTML;
    //     var catatanContent = quill2.root.innerHTML;
    //     // Set it to hidden input
    //     document.getElementById("layanan_content").value = layananContent;
    //     document.getElementById("catatan_content").value = catatanContent;
    // };


    // Fungsi untuk format angka menjadi Rupiah
    function formatRupiah(angka, prefix) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        // Tambahkan titik (.) jika ada ribuan
        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp ' + rupiah : '');
    }

    document.getElementById('durasi').addEventListener('keydown', function(e) {
        // Allow: backspace, delete, tab, escape, enter, and .
        if ([46, 8, 9, 27, 13, 110, 190].indexOf(e.keyCode) !== -1 ||
            // Allow: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
            (e.keyCode === 65 && e.ctrlKey === true) ||
            (e.keyCode === 67 && e.ctrlKey === true) ||
            (e.keyCode === 86 && e.ctrlKey === true) ||
            (e.keyCode === 88 && e.ctrlKey === true) ||
            // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 39)) {
            // let it happen, don't do anything
            return;
        }
        // Ensure that it is a number and stop the keypress if it's not
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

    // Event Listener untuk input
    // document.getElementById('nominal').addEventListener('input', function(e) {
    //     this.value = formatRupiah(this.value, 'Rp');
    // });

    document.addEventListener('DOMContentLoaded', function() {
        // Input biaya handling layanan
        const rupiahInputs = document.querySelectorAll('.input-biaya');

        // Fungsi untuk memformat angka menjadi format rupiah
        function formatRupiah(angka, prefix) {
            let number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix === undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
        }

        // Event listener untuk semua input yang hanya bisa menerima angka dan diformat ke rupiah
        rupiahInputs.forEach(function(input) {
            input.addEventListener('input', function(e) {
                let inputValue = e.target.value;

                // Hanya izinkan angka
                inputValue = inputValue.replace(/[^0-9]/g, '');

                // Tampilkan format rupiah
                e.target.value = formatRupiah(inputValue, 'Rp.');
            });
        });

        // Biaya
        const biayaInput = document.querySelectorAll('#biaya');

        // Fungsi untuk memformat angka menjadi format rupiah
        function formatRupiah(angka, prefix) {
            let number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix === undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
        }

        // Event listener untuk semua input yang hanya bisa menerima angka dan diformat ke rupiah
        biayaInput.forEach(function(input) {
            input.addEventListener('input', function(e) {
                let inputValue = e.target.value;

                // Hanya izinkan angka
                inputValue = inputValue.replace(/[^0-9]/g, '');

                // Tampilkan format rupiah
                e.target.value = formatRupiah(inputValue, 'Rp.');
            });
        });

        // Mengambil semua tombol yang memiliki class 'checklistButton'
        const buttons = document.querySelectorAll('.checklistButton');

        buttons.forEach(function(button) {
            const icon = button.querySelector('i');
            const inputField = document.getElementById('input-' + button.id.split('-')[1]);
            const extraInput = document.getElementById('extra-input-' + button.id.split('-')[1]);

            button.addEventListener('click', function() {
                // Inisialisasi currentState berdasarkan nilai inputField
                let currentState = inputField.value === 'Y' ? 'check' : (inputField.value === 'N' ? 'times' : 'uncheck');

                if (currentState === 'uncheck') {
                    button.classList.remove('uncheck');
                    button.classList.add('check');
                    icon.classList.remove('fa-square');
                    icon.classList.add('fa-check');
                    inputField.value = 'Y'; // Set input value to 'Y'

                    // Jika tombol dengan id "button-9" diklik, tampilkan input tambahan
                    if (button.id === 'button-9') {
                        extraInput.style.display = 'block'; // Tampilkan input tambahan
                    }

                } else if (currentState === 'check') {
                    button.classList.remove('check');
                    button.classList.add('times');
                    icon.classList.remove('fa-check');
                    icon.classList.add('fa-times');
                    inputField.value = 'N'; // Set input value to 'N'

                    // Jika tombol dengan id "button-9" diklik, sembunyikan input tambahan
                    if (button.id === 'button-9') {
                        extraInput.style.display = 'none'; // Sembunyikan input tambahan
                    }

                } else if (currentState === 'times') {
                    button.classList.remove('times');
                    button.classList.add('uncheck');
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-square');
                    inputField.value = ''; // Clear input value

                    // Sembunyikan input tambahan jika dibutuhkan
                    if (button.id === 'button-9') {
                        extraInput.style.display = 'none';
                    }
                }
            });
        });

    });

    document.getElementById("form").onsubmit = function() {
        // Get HTML content from Quill editor
        var editorContent = quill.root.innerHTML;
        // Set it to hidden input
        document.getElementById("editor_content").value = editorContent;
    };

    $('.name').select2();

    //GENERATE NOMOR PELAYANAN
    $.ajax({
        url: "<?php echo site_url('penawaran_pu/generate_kode') ?>",
        type: "POST",
        data: {},
        dataType: "JSON",
        success: function(data) {
            // console.log(data);
            $('#no_pelayanan').val(data.toUpperCase());
            $('#kode').val(data);
        },
        error: function(error) {
            alert("error" + error);
        }
    });

    //GENERATE DETAIL LAYANAN
    $('#name').change(function(e) {
        $id = $(this).val();
        $.ajax({
            url: "<?php echo site_url('penawaran_pu/generate_layanan') ?>",
            type: "POST",
            data: {
                id: $id
            },
            dataType: "JSON",
            success: function(data) {
                //MENGOSONGKAN VALUE SEBELUMNYA
                $('#deskripsi').empty();
                $('#keberangkatan').empty();
                $('#durasi').empty();
                $('#tempatKeberangkatan').empty();
                $('#priceTxt').empty();
                $('#layananTermasuk').empty();
                $('#layananTdkTermasuk').empty();
                //MENGISI VALUE LAYANAN
                moment.locale('id')
                $('#deskripsi').append(`<h2 class="section-title">Deskripsi:</h2> <p>` + data['deskripsi'] + `</p>`);
                $('#keberangkatan').append(`<span class="label-inline text-gray-600">Keberangkatan:</span> <span class="value-inline text-gray-800">` + moment(data['keberangkatan']).format('DD MMMM YYYY') + `</span>`);
                $('#durasi').append(`<span class="label-inline text-gray-600">Durasi:</span> <span class="value-inline text-gray-800">` + data['durasi'] + ` Hari</span>`);
                $('#tempatKeberangkatan').append(`<span class="label-inline text-gray-600">Berangkat dari:</span> <span class="value-inline text-gray-800">` + data['tempat_keberangkatan'] + `</span>`);
                $('#priceTxt').append(`Rp. ` + data['biaya'].replace(/\B(?=(\d{3})+(?!\d))/g, '.') + `,- /pax`);
                $('#layananTermasuk').append('<h2 class="section-title">Layanan Termasuk:</h2>' + data['layanan_termasuk']);
                $('#layananTermasuk ol').prop('class', 'list-item');
                $('#layananTdkTermasuk').append('<h2 class="section-title mt-5">Layanan Tidak Termasuk:</h2>' + data['layanan_tdk_termasuk']);
                $('#layananTdkTermasuk ol').prop('class', 'list-item');
            },
            error: function(error) {
                alert("error" + error);
            }
        });
    });

    $(document).ready(function() {

        // INISIASI VARIABEL JAVASCRIPT/JQUERY
        var id = $('#id').val();
        var aksi = $('#aksi').val();
        var kode = $('#kode').val();
        let inputCount = 0;
        let deletedRows = [];

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
                url: "<?php echo site_url('penawaran_pu/edit_data') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    // Set data master
                    $('#no_pelayanan').val(data['master']['no_pelayanan']);
                    $('#pelanggan').val(data['master']['pelanggan']);
                    $('#produk').val(data['master']['produk']).trigger('change');
                    $('#alamat').val(data['master']['alamat']).trigger('change');
                    $('#deskripsi').val(data['master']['deskripsi']).trigger('change');
                    $('#tgl_berlaku').val(data['master']['tgl_berlaku']).trigger('change');
                    $('#keberangkatan').val(data['master']['keberangkatan']).trigger('change');
                    $('#durasi').val(data['master']['durasi']).trigger('change');
                    $('#tempat').val(data['master']['tempat']).trigger('change');
                    $('#biaya').val(formatRupiah(data['master']['biaya'])).trigger('change');

                    // console.log(data['layanan']);

                    // Set status layanan (Y/N) dan nominal
                    data['layanan'].forEach(function(layanan) {
                        const button = document.getElementById('button-' + layanan.id_layanan);
                        const inputField = document.getElementById('input-' + layanan.id_layanan);
                        const extraInput = document.getElementById('extra-input-' + layanan.id_layanan);
                        const icon = button.querySelector('i');

                        // Debugging: Pastikan elemen yang diambil benar
                        console.log('Layanan:', layanan);
                        console.log('Button:', button);
                        console.log('Icon:', icon);

                        // Set status layanan (Y/N)
                        if (layanan.is_active.startsWith('Y')) {
                            // Update tampilan untuk status "checked"
                            button.classList.remove('uncheck');
                            button.classList.add('check');
                            icon.classList.remove('fa-square');
                            icon.classList.add('fa-check');
                            inputField.value = 'Y'; // Set input value to 'Y'

                            // Tampilkan input tambahan (nominal) hanya untuk id_layanan 9
                            if (layanan.id_layanan == 9) {
                                extraInput.style.display = 'block'; // Tampilkan input tambahan
                                // Ambil nilai nominal dari is_active dan format
                                const nominal = layanan.is_active.split(' ')[1]; // Ambil nominal setelah 'Y'
                                extraInput.value = formatRupiah(nominal); // Format nominal
                            } else {
                                extraInput.style.display = 'none'; // Sembunyikan jika bukan id_layanan 9
                            }
                        } else if (layanan.is_active.startsWith('N')) {
                            // Update tampilan untuk status "times"
                            button.classList.remove('uncheck');
                            button.classList.add('times');
                            icon.classList.remove('fa-square');
                            icon.classList.add('fa-times');
                            inputField.value = 'N'; // Set input value to 'N'

                            // Sembunyikan input tambahan (nominal)
                            if (layanan.id_layanan === 9) {
                                extraInput.style.display = 'none'; // Sembunyikan jika id_layanan 9
                            }
                        } else {
                            // Update tampilan untuk status "uncheck" (default)
                            button.classList.add('uncheck');
                            icon.classList.add('fa-square');
                            inputField.value = ''; // Kosongkan jika tidak aktif

                            // Sembunyikan input tambahan jika tidak diperlukan
                            extraInput.style.display = 'none';
                        }
                    });

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });

            function formatRupiah(angka) {
                let rupiah = '';
                const angkarev = angka.toString().split('').reverse().join('');
                for (let i = 0; i < angkarev.length; i++) {
                    if (i % 3 === 0 && i !== 0) {
                        rupiah += '.';
                    }
                    rupiah += angkarev[i];
                }
                return 'Rp ' + rupiah.split('').reverse().join('');
            }
        }

        // INSERT ATAU UPDATE
        $("#form").submit(function(e) {
            e.preventDefault();
            var $form = $(this);
            if (!$form.valid()) return false;
            var url;
            if (id == 0) {
                url = "<?php echo site_url('penawaran_pu/add') ?>";
            } else {
                url = "<?php echo site_url('penawaran_pu/update/') ?>" + id;
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
                            location.href = "<?= base_url('penawaran_pu') ?>";
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
                no_pelayanan: {
                    required: true,
                },
                pelanggan: {
                    required: true,
                },
                produk: {
                    required: true,
                },
                alamat: {
                    required: true,
                },
                deskripsi: {
                    required: true,
                },
                tgl_berlaku: {
                    required: true,
                },
                keberangkatan: {
                    required: true,
                },
                tempat: {
                    required: true,
                },
            },
            messages: {
                no_pelayanan: {
                    required: "No Pelayanan is required",
                },
                pelanggan: {
                    required: "Pelanggan is required",
                },
                produk: {
                    required: "Produk is required",
                },
                alamat: {
                    required: "Alamat is required",
                },
                deskripsi: {
                    required: "Deskripsi is required",
                },
                tgl_berlaku: {
                    required: "Tanggal Berlaku is required",
                },
                keberangkatan: {
                    required: "Keberangkatan is required",
                },
                tempat: {
                    required: "Tempat is required",
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