<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title_view ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header text-right">
                    <a class="btn btn-secondary btn-sm" href="<?= base_url('penawaran_la_pu') ?>"><i class="fas fa-chevron-left"></i>&nbsp;Back</a>
                </div>
                <div class="card-body">
                    <form id="form" method="POST" action="<?= base_url('penawaran_la_pu/add') ?>">
                        <div class="row p-3">
                            <div class="col-md-6">
                                <div class="mb-3 row">
                                    <label class="col-sm-4">No Pelayanan</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="no_pelayanan" name="no_pelayanan" readonly>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-sm-4">Kepada</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="pelanggan" name="pelanggan">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-sm-4">Produk</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="produk" name="produk">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-sm-4">Tanggal berlaku:</label>
                                    <div class="col-sm-8">
                                        <input type="date" id="tgl_berlaku" name="tgl_berlaku" class="form-control">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-sm-4">Keberangkatan:</label>
                                    <div class="col-sm-8">
                                        <input type="date" id="tgl_keberangkatan" name="tgl_keberangkatan" class="form-control">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-sm-4">Durasi:</label>
                                    <div class="col-sm-8">
                                        <input type="number" id="durasi" name="durasi" class="form-control w-50">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3 row">
                                    <label class="col-sm-4">Berangkat Dari:</label>
                                    <div class="col-sm-8">
                                        <input type="text" id="berangkat_dari" name="berangkat_dari" class="form-control w-50">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-sm-4">Biaya:</label>
                                    <div class="col-sm-8">
                                        <input type="text" id="biaya" name="biaya" class="form-control w-50">
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
                                <div class="mb-3 row">
                                    <label class="col-sm-4">Deskripsi:</label>
                                    <div class="col-sm-8">
                                        <textarea id="deskripsi" rows="4" name="deskripsi" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Layanan -->
                        <div class="container-custom mx-auto mt-2 mb-4 row">
                            <!-- Layanan Termasuk -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Layanan Termasuk:</label>
                                <!-- <textarea name="layanan_content" id="layanan_content" cols="5" rows="5" class="form-control"></textarea> -->
                                <div id="layanan_termasuk" name="layanan_termasuk" class="border p-2" style="height: 200px;"></div>
                                <input type="hidden" name="layanan_content" id="layanan_content">
                            </div>
                        </div>

                        <div class="container-custom mx-auto mt-2 mb-4 row">
                            <!-- Layanan Tidak Termasuk -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Layanan Tidak Termasuk:</label>
                                <!-- <textarea name="layanan_content2" id="layanan_content2" cols="5" rows="5" class="form-control"></textarea> -->
                                <div id="layanan_tidak_termasuk" name="layanan_tidak_termasuk" class="border p-2" style="height: 200px;"></div>
                                <input type="hidden" name="layanan_content2" id="layanan_content2">
                            </div>
                        </div>

                        <!-- Ekstra Section -->
                        <div class="container-custom mx-auto mt-2 mb-4">
                            <div class="col">
                                <label class="section-title">Extra:</label>
                                <div id="extra" name="extra" class="border p-2" style="height: 150px;"></div>
                                <input type="hidden" name="catatan_content" id="catatan_content">
                            </div>
                        </div>

                        <!-- BUTTON TAMBAH FORM -->
                        <div class="mt-3">
                            <button type="button" class="btn-special btn-success btn-sm" id="add-row" style="background-color: green;"><span class="front front-add"><i class="fa fa-plus" aria-hidden="true"></i> Add</span></button>
                        </div>
                        <!-- Rundown Kegiatan -->
                        <div class="mt-2 mb-3" style="overflow-x: scroll;">
                            <table class="table table-bordered">
                                <thead class="thead-dark">
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
                        <div class="col-md-6">
                            <button type="submit" class="btn-special btn-primary btn-sm aksi"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<script src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>

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

    const quill = new Quill('#layanan_termasuk', {
        modules: {
            toolbar: toolbarOptions
        },
        placeholder: 'Field layanan',
        theme: 'snow',
    });

    const quill2 = new Quill('#layanan_tidak_termasuk', {
        modules: {
            toolbar: toolbarOptions
        },
        placeholder: 'Field layanan',
        theme: 'snow',
    });

    const quill3 = new Quill('#extra', {
        modules: {
            toolbar: toolbarOptions
        },
        placeholder: 'Field Extra',
        theme: 'snow',
    });

    document.getElementById("form").onsubmit = function() {
        // Get HTML content from Quill editor
        var layananContent = quill.root.innerHTML;
        var layananContent2 = quill2.root.innerHTML;
        var catatanContent = quill3.root.innerHTML;
        // Set it to hidden input
        document.getElementById("layanan_content").value = layananContent;
        document.getElementById("layanan_content2").value = layananContent2;
        document.getElementById("catatan_content").value = catatanContent;
    };

    //GENERATE NOMOR PELAYANAN
    $.ajax({
        url: "<?php echo site_url('penawaran_la_pu/generate_kode') ?>",
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

    $(document).ready(function() {

        // INISIASI VARIABEL JAVASCRIPT/JQUERY
        var id = $('#id').val();
        var aksi = $('#aksi').val();
        var kode = $('#kode').val();
        let inputCount = 0;
        let deletedRows = []; // Ubah nama variabel

        $('#form').submit(function(event) {
            // Tambahkan array deletedRows ke dalam form data sebelum submit
            $('<input>').attr({
                type: 'hidden',
                name: 'deleted_rows',
                value: JSON.stringify(deletedRows) // Gunakan nama variabel yang baru
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
                url: "<?php echo site_url('penawaran_la_pu/edit_data') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    moment.locale('id')
                    //APPEND DATA TRANSAKSI DETAIL PREPAYMENT
                    if (aksi == 'update') {
                        $biaya = formatRupiah(data['master']['biaya'], "Rp");
                        $('#no_pelayanan').val(data['master']['no_pelayanan']);
                        $('#pelanggan').val(data['master']['pelanggan']);
                        $('#produk').val(data['master']['produk']);
                        $('#alamat').val(data['master']['alamat']);
                        $('#deskripsi').val(data['master']['deskripsi']);
                        $('#tgl_berlaku').val(data['master']['tgl_berlaku']);
                        $('#tgl_keberangkatan').val(data['master']['tgl_keberangkatan']);
                        $('#durasi').val(data['master']['durasi']);
                        $('#berangkat_dari').val(data['master']['berangkat_dari']);
                        quill.clipboard.dangerouslyPasteHTML(data['master']['layanan_trmsk']);
                        $('#biaya').val($biaya);
                        quill2.clipboard.dangerouslyPasteHTML(data['master']['layanan_tdk_trmsk']);
                        quill3.clipboard.dangerouslyPasteHTML(data['master']['catatan']);
                        // console.log(data['transaksi']);

                        for (let index = 0; index <= data['transaksi'].length; index++) {
                            const row = `
                            <tr id="row-${index+1}">
                                <td class="row-number">${index+1}</td>
                                <td>
                                    <input type="text" class="form-control" name="hari[${index+1}]" placeholder="Input here..." value="${data['transaksi'][index]['hari']}" />
                                </td>
                                <td>
                                    <input type="date" class="form-control" id="tanggal-${index+1}" name="tanggal[${index+1}]" placeholder="Input here..." value="${data['transaksi'][index]['tanggal']}" />
                                    <input type="text" id="hidden_id${index+1}" name="hidden_id[${index+1}]" value="${data['transaksi'][index]['id']}">
                                </td>
                                <td>
                                    <div id="kegiatan-${index+1}" class="border p-2" style="height: 200px;">${data['transaksi'][index]['kegiatan']}</div>
                                    <input type="text" name="hidden_kegiatan_[${index+1}]" id="hidden_kegiatan_${index+1}" value="">
                                </td>
                                <td>
                                    <span class="btn delete-btn btn-danger" data-id="${index+1}">Delete</span>
                                </td>
                            </tr>
                        `;

                            // Tambahkan baris ke container
                            $('#input-container').append(row);

                            // Inisialisasi Quill
                            initializeQuill(index + 1);

                            rowCount = index + 1;

                        }
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        // Fungsi untuk memformat angka menjadi format mata uang dengan Rp dan titik ribuan
        function formatRupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, "").toString(),
                split = number_string.split(","),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                var separator = sisa ? "." : "";
                rupiah += separator + ribuan.join(".");
            }

            rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? "Rp " + rupiah : "");
        }

        // Event listener untuk memformat input setiap kali pengguna mengetik
        document.getElementById("biaya").addEventListener("input", function(e) {
            this.value = formatRupiah(this.value, "Rp");
        });

        // Fungsi untuk mengonversi input format rupiah menjadi integer
        function getIntegerValue(rupiah) {
            // Hapus semua karakter selain angka
            return parseInt(rupiah.replace(/[^,\d]/g, ''));
        }

        // INSERT ATAU UPDATE
        $("#form").submit(function(e) {
            e.preventDefault();
            var $form = $(this);
            var formattedValue = document.getElementById("biaya").value;
            var integerValue = getIntegerValue(formattedValue);
            console.log(integerValue); // Menampilkan nilai integer (tanpa "Rp" dan titik)
            var textContent = quill.getText().trim();
            if (textContent === '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Layanan Masih Kosong',
                    text: 'Mohon Untuk Mengisi Layanan'
                });
            }

            if (!$form.valid()) return false;
            var url;
            if (id == 0) {
                url = "<?php echo site_url('penawaran_la_pu/add') ?>";
            } else {
                url = "<?php echo site_url('penawaran_la_pu/update/') ?>" + id;
            }

            // Tambahkan integerValue ke dalam data form
            var formData = $form.serializeArray(); // Mengambil semua data form sebagai array
            formData.push({
                name: "biaya_integer",
                value: integerValue
            }); // Tambahkan nilai integer

            $.ajax({
                url: url,
                type: "POST",
                data: $.param(formData), // Gunakan data form yang sudah ditambahkan nilai integer
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
                            location.href = "<?= base_url('penawaran_la_pu') ?>";
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
                durasi: {
                    required: true,
                },
                tempat: {
                    required: true,
                },
                biaya: {
                    required: true,
                }
            },
            messages: {
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
                    required: "Tanggal berlaku is required",
                },
                keberangkatan: {
                    required: "Keberangkatan is required",
                },
                durasi: {
                    required: "Durasi is required",
                },
                tempat: {
                    required: "Tempat is required",
                },
                biaya: {
                    required: "Biaya is required",
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

        let rowCount = 0;

        // Objek untuk menyimpan referensi Quill instances
        const quillInstances = {};

        // Delete row function
        function deleteRow(id) {

            const rowId = $(`#row-${id}`).find('input:text[id^="hidden_id"]').val();
            console.log(rowId);
            // if (rowId) {
            deletedRows.push(rowId);
            // }

            // Hapus instance Quill yang terkait
            if (quillInstances[id]) {
                quillInstances[id].off('text-change');
                delete quillInstances[id];
            }

            // Hapus baris dari DOM
            $(`#row-${id}`).remove();

            // Reorder baris
            reorderRows();

            // Perbarui tombol submit
            updateSubmitButtonState();
        }

        // Reorder rows after a row is deleted
        function reorderRows() {
            $('#input-container tr').each(function(index) {
                const newRowNumber = index + 1;
                const oldRowNumber = $(this).attr('id').split('-')[1];

                // Pindahkan instance Quill ke nomor baru dan perbarui event listener
                if (quillInstances[oldRowNumber]) {
                    const quillEditor = quillInstances[oldRowNumber];
                    delete quillInstances[oldRowNumber];
                    quillInstances[newRowNumber] = quillEditor;

                    // Perbarui event listener untuk sinkronisasi dengan hidden input baru
                    quillEditor.off('text-change');
                    quillEditor.on('text-change', function() {
                        const hiddenInput = document.getElementById(`hidden_kegiatan_${newRowNumber}`);
                        if (hiddenInput) {
                            hiddenInput.value = quillEditor.root.innerHTML;
                        }
                    });
                }

                // Update atribut ID dan Name
                $(this).attr('id', `row-${newRowNumber}`);
                $(this).find('.row-number').text(newRowNumber);
                $(this).find('input[name^="hari"]').attr('name', `hari[${newRowNumber}]`);
                $(this).find('input[name^="tanggal"]').attr('name', `tanggal[${newRowNumber}]`).attr('id', `tanggal-${newRowNumber}`);
                $(this).find('div[id^="kegiatan"]').attr('id', `kegiatan-${newRowNumber}`);
                $(this).find('input[name^="hidden_kegiatan"]').attr('name', `hidden_kegiatan_[${newRowNumber}]`).attr('id', `hidden_kegiatan_${newRowNumber}`);
                $(this).find('input[name^=hidden_id]').attr('name', `hidden_id[${newRowNumber}]`).attr('id', `hidden_id[${newRowNumber}]`);
                $(this).find('.delete-btn').attr('data-id', newRowNumber);
            });

            // Perbarui nilai rowCount
            rowCount = $('#input-container tr').length;
        }

        // Fungsi untuk menambah baris
        function addRow() {
            rowCount++;
            const row = `
        <tr id="row-${rowCount}">
            <td class="row-number">${rowCount}</td>
            <td>
                <input type="text" class="form-control" name="hari[${rowCount}]" placeholder="Input here..." />
            </td>
            <td>
                <input type="date" class="form-control" id="tanggal-${rowCount}" name="tanggal[${rowCount}]" placeholder="Input here..." />
                <input type="text" id="hidden_id${rowCount}" name="hidden_id[${rowCount}]" value="">
            </td>
            <td>
                <div id="kegiatan-${rowCount}" class="border p-2" style="height: 200px;"></div>
                <input type="text" name="hidden_kegiatan_[${rowCount}]" id="hidden_kegiatan_${rowCount}" value="">
            </td>
            <td>
                <span class="btn delete-btn btn-danger" data-id="${rowCount}">Delete</span>
            </td>
        </tr>
    `;

            // Tambahkan baris ke container
            $('#input-container').append(row);

            // Inisialisasi Quill
            initializeQuill(rowCount);

            // Perbarui tombol submit
            updateSubmitButtonState();

            // Tambahkan validasi untuk input baru
            $("#form").validate().settings.rules[`hari[${rowCount}]`] = {
                required: true
            };
            $("#form").validate().settings.rules[`tanggal[${rowCount}]`] = {
                required: true
            };
        }

        // Inisialisasi Quill editor
        function initializeQuill(rowCount) {
            const quillKegiatan = new Quill(`#kegiatan-${rowCount}`, {
                theme: 'snow'
            });

            // Simpan referensi editor di dalam objek
            quillInstances[rowCount] = quillKegiatan;

            // Sinkronkan konten Quill dengan input tersembunyi
            quillKegiatan.on('text-change', function() {
                const hiddenInput = document.getElementById(`hidden_kegiatan_${rowCount}`);
                if (hiddenInput) {
                    hiddenInput.value = quillKegiatan.root.innerHTML;
                }
            });
        }

        // Event untuk tombol delete
        $(document).on('click', '.delete-btn', function() {
            const id = $(this).data('id');
            deleteRow(id);
        });

        // Perbarui tombol submit berdasarkan jumlah baris
        function updateSubmitButtonState() {
            const rowCount = $('#input-container tr').length;
            $('.aksi').prop('disabled', rowCount === 0);
        }

        $('#add-row').click(function() {
            addRow();
        });


    })
</script>