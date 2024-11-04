<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* .container-custom {
            max-width: 1000px;
        } */

        .editor-with-border .ql-container {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            min-height: 150px;
        }

        .logo-custom {
            width: 550px;
            height: auto;
        }

        .label-inline {
            display: inline-block;
            min-width: 150px;
            font-weight: bold;
        }

        .value-inline {
            display: inline-block;
        }

        .orange-box,
        .biaya-box,
        .ekstra-box {
            background-color: #FC7714;
            color: white;
            padding: 10px;
            text-align: center;
            font-size: 1rem;
            font-weight: bold;
            margin-bottom: 1rem;
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
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3 row">
                                    <label class="col-sm-4">Alamat</label>
                                    <div class="col-sm-8">
                                        <textarea id="alamat" rows="4" name="alamat" class="form-control" placeholder="Write your thoughts here..."></textarea>
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

                        <!-- Informasi Layanan -->
                        <div class="container-custom mx-auto mt-3 row">
                            <!-- Left Section: Deskripsi -->
                            <div class="col-md-6 mb-3">
                                <label class="section-title">Deskripsi:</label>
                                <textarea id="deskripsi" rows="12" name="deskripsi" class="form-control" placeholder="Write your thoughts here..."></textarea>
                            </div>

                            <!-- Right Section: Informasi Tambahan -->
                            <div class="col-md-6 mb-3">
                                <label class="section-title">Tanggal berlaku:</label>
                                <input type="datetime-local" id="tgl_berlaku" name="tgl_berlaku" class="form-control mb-3">

                                <label class="section-title">Keberangkatan:</label>
                                <input type="datetime-local" id="keberangkatan" name="keberangkatan" class="form-control mb-3">

                                <label class="section-title">Durasi:</label>
                                <input type="number" id="durasi" name="durasi" class="form-control w-50 mb-3">

                                <label class="section-title">Tempat:</label>
                                <input type="text" id="tempat" name="tempat" class="form-control w-50 mb-3">
                            </div>

                            <!-- Layanan Termasuk -->
                            <div class="col-md-12 mb-3">
                                <label class="section-title">Layanan:</label>
                                <div id="layanan" name="layanan" class="border p-2" style="height: 200px;"></div>
                                <input type="hidden" name="layanan_content" id="layanan_content">
                            </div>
                        </div>

                        <!-- Biaya Section -->
                        <div class="container-custom mx-auto mt-3">
                            <div class="biaya-box">
                                BIAYA
                            </div>
                            <div class="col-md-6">
                                <label class="section-title">Biaya:</label>
                                <input type="text" id="biaya" name="biaya" class="form-control w-50">
                            </div>
                        </div>

                        <!-- Ekstra Section -->
                        <div class="container-custom mx-auto mt-5 mb-3">
                            <div class="ekstra-box">
                                EKSTRA
                            </div>
                            <div class="col">
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
                        <button type="submit" class="btn btn-primary btn-sm aksi"></button>
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
    var quill = new Quill('#layanan', {
        theme: 'snow'
    });

    var quill2 = new Quill('#extra', {
        theme: 'snow'
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
            $('.aksi').text('Save');
        } else {
            $('.aksi').text('Update');
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
                alert('Mohon untuk mengisi layanan.');
                e.preventDefault(); // Prevent form submission if content is empty
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