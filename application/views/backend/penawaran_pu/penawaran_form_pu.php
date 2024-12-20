<head>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        /* modal */
        .modal-input {
            background-color: rgb(36, 44, 73);
            color: white;
            width: 130px;
            border: none;
            padding: 8px 10px;
            font-size: 14px;
            border-radius: 5px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.15), -4px 4px 6px rgba(0, 0, 0, 0.15), 4px 4px 6px rgba(0, 0, 0, 0.15);
            /* Bayangan bawah dan kiri-kanan */
            cursor: pointer;
            transition: all 0.055s ease;
        }

        .modal-input:hover {
            scale: 1.020;
        }

        .modal-input:active {
            transform: translateY(2px);
            box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1), -2px 2px 6px rgba(0, 0, 0, 0.1), 2px 2px 6px rgba(0, 0, 0, 0.1);
        }

        #layananModal .modal-body .layanan {
            display: flex;
            align-items: center;
        }

        #hotelModal .modal-body .hotel {
            display: flex;
            align-items: center;
        }

        #layananModal .modal-body .layanan label {
            margin: 0;
            cursor: pointer;
        }

        #hotelModal .modal-body .hotel label {
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
        .checklistButtonLayanan,
        .checklistButtonHotel {
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

        /* Style Form Rundown */
        .table-transaksi {
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        .table-transaksi td {
            border: 1px solid rgba(26, 32, 53, 0.1);
        }

        .table-transaksi tbody tr {
            transition: 200ms;
        }

        .table-transaksi tbody tr:hover {
            background-color: rgba(234, 236, 244, 0.5);
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

        #durasi {
            width: 50px;
        }

        i.fa-plane {
            transform: rotate3d(3, 1, 3, -30deg);
            margin-right: 5px;
        }

        .btn-style {
            color: white;
            cursor: pointer;
            transition: all 0.055s ease;
        }

        .btn-style:hover {
            scale: 1.020;
            color: white;
        }

        .btn-style:active {
            transform: translateY(2px);
            color: white;
            box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1), -2px 2px 6px rgba(0, 0, 0, 0.1), 2px 2px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-delete {
            background-color: #DC0808;
            position: relative;
            top: 1px;
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
                    <a class="btn btn-secondary btn-sm" style="background-color: rgb(36, 44, 73);" href="<?= base_url('penawaran_pu') ?>"><i class="fas fa-chevron-left"></i>&nbsp;Back</a>
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
                                        <input type="text" class="form-control" id="pelanggan" name="pelanggan" placeholder="Kepada">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5">Title</label>
                                    <div class="col-sm-7">
                                        <select name="title" id="title" class="form-control" style="cursor: pointer;">
                                            <option selected value="" hidden>Pilih Title</option>
                                            <option value="Ny">Ny.</option>
                                            <option value="Nn">Nn.</option>
                                            <option value="Tn">Tn.</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5">Produk</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="produk" name="produk" placeholder="Produk">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5">Tanggal Keberangkatan</label>
                                    <div class="col-sm-7">
                                        <input type="text" placeholder="Tanggal Keberangkatan" class="input-date-style form-control" name="tgl_keberangkatan" id="tgl_keberangkatan" autocomplete="off" style="cursor: pointer;">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5">Berangkat Dari</label>
                                    <div class="col-sm-7">
                                        <input type="text" id="berangkat_dari" name="berangkat_dari" placeholder="Berangkat Dari" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5">Deskripsi</label>
                                    <div class="col-sm-7">
                                        <textarea id="deskripsi" rows="4" name="deskripsi" class="form-control" placeholder="Deskripsi"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-4">Tanggal Berlaku</label>
                                    <div class="col-sm-8">
                                        <input type="text" placeholder="Tanggal Berlaku" class="input-date-style form-control" name="tgl_berlaku" id="tgl_berlaku" autocomplete="off" style="cursor: pointer;">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4">Durasi</label>
                                    <div class="col-sm-8">
                                        <input type="text" id="durasi" name="durasi" class="form-control" style="display: inline;"><span style="position: relative; bottom: 2px; left: 5px">Hari</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4">Layanan</label>
                                    <div class="col-sm-8">
                                        <button type="button" class="modal-input" data-toggle="modal" data-target="#layananModal">
                                            Pilih Layanan
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4">Paket Quad</label>
                                    <div class="col-sm-8">
                                        <input type="text" id="pkt_quad" name="pkt_quad" placeholder="Paket Quad" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4">Paket Triple</label>
                                    <div class="col-sm-8">
                                        <input type="text" id="pkt_triple" name="pkt_triple" placeholder="Paket Triple" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4">Paket Double</label>
                                    <div class="col-sm-8">
                                        <input type="text" id="pkt_double" name="pkt_double" placeholder="Paket Double" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4">Hotel</label>
                                    <div class="col-sm-8">
                                        <button type="button" class="modal-input" data-toggle="modal" data-target="#hotelModal">
                                            Pilih Hotel
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4"><i class="fas fa-plane"></i>Keberangkatan</label>
                                    <div class="col-sm-8">
                                        <input type="text" id="keberangkatan" name="keberangkatan" placeholder="Keberangkatan" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4"><i class="fas fa-plane"></i>Kepulangan</label>
                                    <div class="col-sm-8">
                                        <input type="text" id="kepulangan" name="kepulangan" placeholder="Kepulangan" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Rundown Tabel -->
                        <!-- BUTTON TAMBAH FORM -->
                        <div class="mt-3">
                            <button type="button" class="btn-sm modal-input" id="add-row" style="width: 75px;"><span class="front front-add"><i class="fa fa-plus" aria-hidden="true"></i> Add</span></button>
                        </div>
                        <!-- Rundown Kegiatan -->
                        <div class="mt-2 mb-3" style="overflow-x: scroll;">
                            <table class="table table-bordered table-transaksi">
                                <thead class="header-table-transaksi">
                                    <tr>
                                        <th scope="col" style="width: 20px;" class="text-center">No</th>
                                        <th scope="col" style="width: 80px;" class="text-center">Hari</th>
                                        <th scope="col" style="width: 80px;" class="text-center">Tanggal</th>
                                        <th scope="col" style="width: 330px;" class="text-center">Kegiatan</th>
                                        <th scope="col" style="width: 80px;" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="input-container">
                                    <!-- CONTAINER INPUTAN -->
                                </tbody>
                            </table>
                        </div>


                        <!-- Layanan Modal -->
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
                                                <button type="button" id="buttonLayanan-<?= htmlspecialchars($data['id'], ENT_QUOTES) ?>" class="uncheck checklistButtonLayanan">
                                                    <i class="fa fa-square"></i>
                                                </button>
                                                <label for="buttonLayanan-<?= htmlspecialchars($data['id'], ENT_QUOTES) ?>">
                                                    <?= htmlspecialchars($data['nama_layanan'], ENT_QUOTES) ?>
                                                </label>
                                                <input type="hidden" name="status[]" id="inputLayanan-<?= htmlspecialchars($data['id'], ENT_QUOTES) ?>" value="">
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

                        <!-- Hotel Modal -->
                        <div class="modal fade" id="hotelModal" tabindex="-1" aria-labelledby="hotelModal" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="hotelModal">Hotel</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div style="text-align: center;">
                                            <i class="fa fa-check" style="color: green; margin: 0 7px 0 15px"></i>Ditampilkan
                                            <i class="fa fa-square" style="color: gray; margin: 0 7px 0 15px"></i>Tidak Ditampilkan
                                            <hr style="margin-top: 10px;">
                                        </div>
                                        <?php foreach ($hotel as $data) : ?>
                                            <div class="hotel">
                                                <!-- Rating -->
                                                <?php
                                                $rating = '';
                                                if ($data['rating'] == 5) {
                                                    $rating = '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>';
                                                } else if ($data['rating'] == 4) {
                                                    $rating = '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>';
                                                } else if ($data['rating'] == 3) {
                                                    $rating = '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>';
                                                } else if ($data['rating'] == 2) {
                                                    $rating = '<i class="fas fa-star"></i><i class="fas fa-star"></i>';
                                                } else if ($data['rating'] == 1) {
                                                    $rating = '<i class="fas fa-star"></i>';
                                                }
                                                ?>
                                                <input type="hidden" name="id_hotel[]" value="<?= htmlspecialchars($data['id'], ENT_QUOTES) ?>">
                                                <button type="button" id="buttonHotel-<?= htmlspecialchars($data['id'], ENT_QUOTES) ?>" class="uncheck checklistButtonHotel">
                                                    <i class="fa fa-square"></i>
                                                </button>
                                                <label for="buttonHotel-<?= htmlspecialchars($data['id'], ENT_QUOTES) ?>">
                                                    <?= htmlspecialchars($data['nama_hotel'], ENT_QUOTES) . " " . $rating ?>
                                                </label>
                                                <input type="hidden" name="status2[]" id="inputHotel-<?= htmlspecialchars($data['id'], ENT_QUOTES) ?>" value="">
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" data-dismiss="modal">Simpan</button>
                                    </div>
                                </div>
                            </div>
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
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>


<script>
    let id = $('#id').val();

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
        $("#tgl_berlaku").datepicker({
            dateFormat: "yy-mm-dd", // Format tanggal: Tahun-Bulan-Hari
            changeMonth: true,
            changeYear: true
        });
    });

    $(document).ready(function() {
        $("#tgl_keberangkatan").datepicker({
            dateFormat: "yy-mm-dd", // Format tanggal: Tahun-Bulan-Hari
            changeMonth: true,
            changeYear: true
        });
    });

    // Fungsi untuk format angka tanpa "Rp"
    function formatAngka(angka) {
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

    // Fungsi untuk format angka dengan "Rp"
    function formatRupiah(angka) {
        return 'Rp ' + formatAngka(angka);
    }

    // Event Listener untuk Format Angka
    document.querySelectorAll('.input-biaya').forEach(function(input) {
        // Saat mengetik, format hanya angka
        input.addEventListener('keyup', function() {
            this.value = formatAngka(this.value);
        });

        // Saat kehilangan fokus, tambahkan "Rp"
        input.addEventListener('blur', function() {
            if (this.value) {
                this.value = formatRupiah(this.value);
            }
        });

        // Saat fokus kembali, hapus "Rp"
        input.addEventListener('focus', function() {
            this.value = this.value.replace(/^Rp\s+/g, '');
        });
    });

    // Fungsi untuk memformat angka ke dalam format Rupiah Paket
    function formatRupiah(angka) {
        let numberString = angka.replace(/[^,\d]/g, '').toString();
        let split = numberString.split(',');
        let sisa = split[0].length % 3;
        let rupiah = split[0].substr(0, sisa);
        let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
        return rupiah ? 'Rp ' + rupiah : '';
    }

    // Fungsi untuk mencegah input selain angka dan memformat otomatis
    function handleInput(event) {
        let input = event.target;
        let value = input.value;

        // Membersihkan input selain angka
        value = value.replace(/[^\d]/g, '');

        // Format ke dalam Rupiah
        input.value = formatRupiah(value);
    }

    // Menambahkan event listener ke semua input
    document.getElementById('pkt_quad').addEventListener('input', handleInput);
    document.getElementById('pkt_triple').addEventListener('input', handleInput);
    document.getElementById('pkt_double').addEventListener('input', handleInput);


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

    // Layanan
    document.addEventListener('DOMContentLoaded', function() {
        // Mengambil semua tombol yang memiliki class 'checklistButton'
        const buttons = document.querySelectorAll('.checklistButtonLayanan');

        buttons.forEach(function(button) {
            const icon = button.querySelector('i');
            const inputField = document.getElementById('inputLayanan-' + button.id.split('-')[1]);
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

                    // Jika tombol dengan id "button-9" diklik, sembunyikan input tambahan
                    if (button.id === 'buttonLayanan-9') {
                        extraInput.style.display = 'none'; // Sembunyikan input tambahan
                    }


                } else if (currentState === 'check') {
                    button.classList.remove('check');
                    button.classList.add('times');
                    icon.classList.remove('fa-check');
                    icon.classList.add('fa-times');
                    inputField.value = 'N'; // Set input value to 'N'

                    // Jika tombol dengan id "button-9" diklik, tampilkan input tambahan
                    if (button.id === 'buttonLayanan-9') {
                        extraInput.style.display = 'block'; // Tampilkan input tambahan
                    }

                } else if (currentState === 'times') {
                    button.classList.remove('times');
                    button.classList.add('uncheck');
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-square');
                    inputField.value = ''; // Clear input value

                    // Sembunyikan input tambahan jika dibutuhkan
                    if (button.id === 'buttonLayanan-9') {
                        extraInput.style.display = 'none';
                    }
                }
            });
        });

    });

    // Hotel
    document.addEventListener('DOMContentLoaded', function() {
        // Mengambil semua tombol yang memiliki class 'checklistButton'
        const buttons = document.querySelectorAll('.checklistButtonHotel');

        buttons.forEach(function(button) {
            const icon = button.querySelector('i');
            const inputField = document.getElementById('inputHotel-' + button.id.split('-')[1]);

            button.addEventListener('click', function() {
                // Inisialisasi currentState berdasarkan nilai inputField
                let currentState = inputField.value === 'Y' ? 'check' : 'uncheck';

                if (currentState === 'uncheck') {
                    button.classList.remove('uncheck');
                    button.classList.add('check');
                    icon.classList.remove('fa-square');
                    icon.classList.add('fa-check');
                    inputField.value = 'Y';
                } else if (currentState === 'check') {
                    button.classList.remove('check');
                    button.classList.add('uncheck');
                    icon.classList.remove('fa-check');
                    icon.classList.add('fa-square');
                    inputField.value = '';
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
    if (id == 0) {
        $.ajax({
            url: "<?php echo site_url('penawaran_pu/generate_kode') ?>",
            type: "POST",
            data: {},
            dataType: "JSON",
            success: function(data) {
                $('#no_pelayanan').val(data.toUpperCase());
                $('#kode').val(data);
            },
            error: function(error) {
                alert("error" + error);
            }
        });
    }

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
                    $('#pelanggan').val(data['master']['pelanggan'].substring(4));
                    $('#title').val(data['master']['pelanggan'].substring(0, 2));
                    $('#produk').val(data['master']['produk']).trigger('change');
                    $('#deskripsi').val(data['master']['deskripsi']).trigger('change');
                    $('#tgl_berlaku').val(data['master']['tgl_berlaku']).trigger('change');
                    $('#tgl_keberangkatan').val(data['master']['tgl_keberangkatan']).trigger('change');
                    $('#durasi').val(data['master']['durasi']).trigger('change');
                    $('#berangkat_dari').val(data['master']['berangkat_dari']).trigger('change');
                    $('#pkt_quad').val(formatRupiah(data['master']['pkt_quad'])).trigger('change');
                    $('#pkt_triple').val(formatRupiah(data['master']['pkt_triple'])).trigger('change');
                    $('#pkt_double').val(formatRupiah(data['master']['pkt_double'])).trigger('change');
                    $('#keberangkatan').val(data['master']['keberangkatan']).trigger('change');
                    $('#kepulangan').val(data['master']['kepulangan']).trigger('change');


                    // Set status layanan (Y/N) dan nominal
                    data['layanan'].forEach(function(layanan) {
                        const button = document.getElementById('buttonLayanan-' + layanan.id_layanan);
                        const inputField = document.getElementById('inputLayanan-' + layanan.id_layanan);
                        const extraInput = document.getElementById('extra-input-' + layanan.id_layanan);
                        const icon = button.querySelector('i');

                        // Set status layanan (Y/N)
                        if (layanan.is_active.startsWith('Y')) {
                            // Update tampilan untuk status "checked"
                            button.classList.remove('uncheck');
                            button.classList.add('check');
                            icon.classList.remove('fa-square');
                            icon.classList.add('fa-check');
                            inputField.value = 'Y'; // Set input value to 'Y'

                            // Sembunyikan input tambahan (nominal)
                            if (layanan.id_layanan === 9) {
                                extraInput.style.display = 'none'; // Sembunyikan jika id_layanan 9
                            }
                        } else if (layanan.is_active.startsWith('N')) {
                            // Update tampilan untuk status "times"
                            button.classList.remove('uncheck');
                            button.classList.add('times');
                            icon.classList.remove('fa-square');
                            icon.classList.add('fa-times');
                            inputField.value = 'N'; // Set input value to 'N'

                            // Tampilkan input tambahan (nominal) hanya untuk id_layanan 9
                            if (layanan.id_layanan == 9) {
                                extraInput.style.display = 'block'; // Tampilkan input tambahan
                                // Ambil nilai nominal dari is_active dan format
                                const nominal = layanan.is_active.split(' ')[1]; // Ambil nominal setelah 'Y'
                                extraInput.value = formatRupiah(nominal); // Format nominal
                            } else {
                                extraInput.style.display = 'none'; // Sembunyikan jika bukan id_layanan 9
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

                    // Set status hotel (Y) dan nominal
                    data['hotel'].forEach(function(hotel) {
                        const button2 = document.getElementById('buttonHotel-' + hotel.id_hotel);
                        const inputField2 = document.getElementById('inputHotel-' + hotel.id_hotel);
                        const icon2 = button2.querySelector('i');

                        // Set status hotel (Y/N)
                        if (hotel.is_active.startsWith('Y')) {
                            button2.classList.add('check');
                            icon2.classList.remove('fa-square');
                            icon2.classList.add('fa-check');

                            inputField2.value = 'Y'; // Set input value to 'Y'
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

        // Rundown Add
        let rowCount = 0;

        function addRow() {
            rowCount++;
            //         const row = `
            //     <tr id="row-${rowCount}">
            //         <td class="row-number">${rowCount}</td>
            //         <td>
            //             <input type="text" class="form-control" name="hari[${rowCount}]" placeholder="Input here..." />
            //         </td>
            //         <td>
            //             <input type="date" class="form-control" id="tanggal-${rowCount}" name="tanggal[${rowCount}]" placeholder="Input here..." />
            //             <input type="hidden" id="hidden_nominal${rowCount}" name="hidden_nominal[${rowCount}]" value="">
            //         </td>
            //         <td>
            //             <div id="kegiatan-${rowCount}" class="border p-2" style="height: 200px;"></div>
            //             <input type="text" name="hidden_kegiatan[${rowCount}]" id="hidden_kegiatan[${rowCount}]" value="">
            //         </td>
            //         <td>
            //             <span class="btn delete-btn btn-danger" data-id="${rowCount}">Delete</span>
            //         </td>
            //     </tr>
            // `;
            const row = `
        <tr id="row-${rowCount}">
            <td class="row-number">${rowCount}</td>
            <td>
                <input type="text" class="form-control" name="hari[${rowCount}]" placeholder="Input here..." />
            </td>
            <td>
                <input type="date" class="form-control" id="tanggal-${rowCount}" name="tanggal[${rowCount}]" placeholder="Input here..." />
                <input type="hidden" id="hidden_nominal${rowCount}" name="hidden_nominal[${rowCount}]" value="">
            </td>
            <td>
                <textarea name="kegiatan[${rowCount}]" id="kegiatan[${rowCount}]" cols="10" rows="5" class="form-control"></textarea>
            </td>
            <td>
                <span class="btn delete-btn btn-style btn-delete" data-id="${rowCount}">Delete</span>
            </td>
        </tr>
    `;

            // Append row to container
            $('#input-container').append(row);

            // // Initialize Quill editor
            // const quillKegiatan = new Quill(`#kegiatan-${rowCount}`, {
            //     theme: 'snow'
            // });

            // // Sync Quill content with hidden input
            // quillKegiatan.on('text-change', function() {
            //     document.getElementById(`hidden_kegiatan[${rowCount}]`).value = quillKegiatan.root.innerHTML;
            // });

            updateSubmitButtonState();

            // Validation rules for dynamically added inputs
            $("#form").validate().settings.rules[`hari[${rowCount}]`] = {
                required: true
            };
            $("#form").validate().settings.rules[`tanggal[${rowCount}]`] = {
                required: true
            };
            // $("#form").validate().settings.rules[`hidden_kegiatan[${rowCount}]`] = {
            //     required: true
            // };
        }

        // Delete row function
        function deleteRow(id) {
            $(`#row-${id}`).remove();
            reorderRows();
            updateSubmitButtonState();
        }

        // Reorder rows after a row is deleted
        function reorderRows() {
            $('#input-container tr').each(function(index) {
                const newRowNumber = index + 1;
                $(this).attr('id', `row-${newRowNumber}`);
                $(this).find('.row-number').text(newRowNumber);
                $(this).find('input[name^="hari"]').attr('name', `hari[${newRowNumber}]`);
                $(this).find('input[name^="tanggal"]').attr('name', `tanggal[${newRowNumber}]`).attr('id', `tanggal-${newRowNumber}`);
                $(this).find('textarea[name^="kegiatan"]').attr('name', `kegiatan[${newRowNumber}]`).attr('id', `kegiatan-${newRowNumber}`);
                // $(this).find('div[id^="kegiatan"]').attr('id', `kegiatan-${newRowNumber}`);
                // $(this).find('input[name^="hidden_kegiatan"]').attr('name', `hidden_kegiatan[${newRowNumber}]`).attr('id', `hidden_kegiatan[${newRowNumber}]`);
                $(this).find('.delete-btn').attr('data-id', newRowNumber);
            });
            rowCount = $('#input-container tr').length;
        }

        $('#add-row').click(function() {
            addRow();
        });

        function updateSubmitButtonState() {
            const rowCount = $('#input-container tr').length;
            $('.aksi').prop('disabled', rowCount === 0);
        }

        $(document).on('click', '.delete-btn', function() {
            const id = $(this).data('id');
            deleteRow(id);
        });

        // // INSERT ATAU UPDATE
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
                title: {
                    required: true,
                },
                produk: {
                    required: true,
                },
                deskripsi: {
                    required: true,
                },
                tgl_berlaku: {
                    required: true,
                },
                tgl_keberangkatan: {
                    required: true,
                },
                berangkat_dari: {
                    required: true,
                },
                durasi: {
                    required: true,
                },
                pkt_quad: {
                    required: true,
                },
                pkt_triple: {
                    required: true,
                },
                pkt_double: {
                    required: true,
                },
                keberangkatan: {
                    required: true,
                },
                kepulangan: {
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
                title: {
                    required: "Title is required",
                },
                produk: {
                    required: "Produk is required",
                },
                deskripsi: {
                    required: "Deskripsi is required",
                },
                tgl_berlaku: {
                    required: "Tanggal Berlaku is required",
                },
                tgl_keberangkatan: {
                    required: "Tanggal Keberangkatan is required",
                },
                berangkat_dari: {
                    required: "Berangkat Dari is required",
                },
                durasi: {
                    required: "Durasi is required",
                },
                pkt_quad: {
                    required: "Paket Quad is required",
                },
                pkt_triple: {
                    required: "Paket Triple is required",
                },
                pkt_double: {
                    required: "Paket Double is required",
                },
                keberangkatan: {
                    required: "Keberangkatan is required",
                },
                kepulangan: {
                    required: "Kepulangan is required",
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