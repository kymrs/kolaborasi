<head>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .container-custom {
            max-width: 1000px;
            /* Maksimal lebar container */
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
                    <form id="form" method="POST" action="<?= base_url('penawaran_pu/add') ?>">
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
                                    <label class="col-sm-5" for="name">Produk</label>
                                    <div class="col-sm-7">
                                        <select class="form-control name" name="name" id="name">
                                            <option value="" selected disabled>Pilih opsi...</option>
                                            <?php foreach ($products as $product) { ?>
                                                <option value="<?= $product->id ?>"><?= $product->nama ?></option>
                                            <?php } ?>
                                        </select>
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
                            <div class="w-full md:w-2/3 p-4">
                                <!-- Deskripsi -->
                                <div class="description mb-4" id="deskripsi">

                                </div>

                                <!-- Layanan Termasuk -->
                                <div id="layananTermasuk">

                                </div>
                            </div>

                            <!-- Right Section: Informasi Tambahan -->
                            <div class="w-full md:w-1/3 p-4">
                                <!-- Keberangkatan -->
                                <div class="mb-1">
                                    <div id="keberangkatan">

                                    </div>
                                </div>
                                <!-- Durasi -->
                                <div class="mb-1">
                                    <div id="durasi">

                                    </div>
                                </div>
                                <!-- Berangkat Dari -->
                                <div class="mb-1">
                                    <div id="tempatKeberangkatan">

                                    </div>
                                </div>

                                <!-- Layanan Tidak Termasuk -->
                                <div id="layananTdkTermasuk">

                                </div>
                            </div>
                        </div>

                        <!-- Biaya Section -->
                        <div class="container-custom mx-auto mt-3">
                            <div class="biaya-box">
                                BIAYA
                            </div>
                            <div class="price-text" id="priceTxt">

                            </div>
                        </div>

                        <!-- Ekstra Section -->
                        <div class="container-custom mx-auto mt-5 mb-3">
                            <div class="ekstra-box">
                                EKSTRA
                            </div>
                            <div id="editor" name="editor">

                            </div>
                            <input type="hidden" name="editor_content" id="editor_content">
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
    var quill = new Quill('#editor', {
        theme: 'snow'
    });

    // var quillContent = `<p> Lalaland </p>`;

    // quill.clipboard.dangerouslyPasteHTML(quillContent);

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
                    moment.locale('id')

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
                url: "<?php echo site_url('penawaran_pu/read_detail/') ?>" + id,
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
                url = "<?php echo site_url('penawaran_pu/add') ?>";
            } else {
                url = "<?php echo site_url('penawaran_pu/update') ?>";
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