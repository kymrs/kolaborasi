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

        .front-aksi {
            background-color: #0075FF;
        }


        .btn-special:focus:not(:focus-visible) {
            outline: none;
        }

        .btn-special:active .front {
            transform: translateY(-2px);
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
                                    <label class="col-sm-4">Alamat</label>
                                    <div class="col-sm-8">
                                        <textarea id="alamat" rows="4" name="alamat" class="form-control"></textarea>
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
                                        <input type="datetime-local" id="tgl_berlaku" name="tgl_berlaku" class="form-control mb-3">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3 row">
                                    <label class="col-sm-4">Keberangkatan:</label>
                                    <div class="col-sm-8">
                                        <input type="datetime-local" id="keberangkatan" name="keberangkatan" class="form-control">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-sm-4">Durasi:</label>
                                    <div class="col-sm-8">
                                        <input type="number" id="durasi" name="durasi" class="form-control w-50">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-sm-4">Tempat:</label>
                                    <div class="col-sm-8">
                                        <input type="text" id="tempat" name="tempat" class="form-control w-50">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-sm-4">Biaya:</label>
                                    <div class="col-sm-8">
                                        <input type="text" id="biaya" name="biaya" class="form-control w-50">
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
                                <label class="section-title">Layanan:</label>
                                <div id="layanan" name="layanan" class="border p-2" style="height: 200px;"></div>
                                <input type="hidden" name="layanan_content" id="layanan_content">
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

    const quill = new Quill('#layanan', {
        modules: {
            toolbar: toolbarOptions
        },
        placeholder: 'Field layanan',
        theme: 'snow',
    });

    const quill2 = new Quill('#extra', {
        modules: {
            toolbar: toolbarOptions
        },
        placeholder: 'Field Extra',
        theme: 'snow',
    });

    document.getElementById("form").onsubmit = function() {
        // Get HTML content from Quill editor
        var layananContent = quill.root.innerHTML;
        var catatanContent = quill2.root.innerHTML;
        // Set it to hidden input
        document.getElementById("layanan_content").value = layananContent;
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
                        $('#keberangkatan').val(data['master']['keberangkatan']);
                        $('#durasi').val(data['master']['durasi']);
                        $('#tempat').val(data['master']['tempat']);
                        quill.clipboard.dangerouslyPasteHTML(data['master']['layanan_la']);
                        $('#biaya').val($biaya);
                        quill2.clipboard.dangerouslyPasteHTML(data['master']['catatan']);
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
            // console.log(integerValue); // Menampilkan nilai integer (tanpa "Rp" dan titik)
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
                },
                layanan_content: {
                    quillRequired: true
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
                },
                layanan_content: {
                    required: "Layanan is required"
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