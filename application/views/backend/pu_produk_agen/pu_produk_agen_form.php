<style>
    /* Styling box input Select2 */
    .select2-container .select2-selection--single {
        height: 38px;
        /* Sama seperti form-control Bootstrap */
        padding: 6px 12px;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        transition: 300ms;
    }

    /* Saat select2 dalam keadaan fokus */
    .select2-container--default .select2-selection--single:focus,
    .select2-container--default .select2-selection--single:hover {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    /* Styling dropdown Select2 */
    .select2-container--default .select2-results__option {
        padding: 10px;
        font-size: 1rem;
    }

    /* Hover pada dropdown */
    .select2-container--default .select2-results__option--highlighted {
        background-color: #007bff;
        color: white;
    }

    /* Styling placeholder */
    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #6c757d;
    }

    .rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: center;
        font-size: 2em;
    }

    .rating input {
        display: none;
    }

    .rating label {
        color: #ccc;
        cursor: pointer;
        transition: color 0.3s;
        position: relative;
        right: 107px;
        bottom: 5px;
    }

    .rating input:checked~label,
    .rating label:hover,
    .rating label:hover~label {
        color: #f5b301;
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
                    <a style="background-color: rgb(36, 44, 73);" class="btn btn-secondary btn-sm" onclick="window.history.back()">
                        <i class="fas fa-chevron-left"></i>&nbsp;Back
                    </a>
                </div>
                <div class="card-body">
                    <form id="form">
                        <div class="row">
                            <div class="col-md-6">
                                <!-- First Set of Fields -->
                                <div class="form-group row">
                                    <label class="col-sm-5" for="nama_produk">Nama Produk</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="nama_produk" name="nama_produk" placeholder="Nama Produk" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-5" for="travel">Nama Travel</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" name="travel" id="travel">
                                            <option value="" hidden>Pilih Travel</option>
                                            <?php foreach ($travels as $travel) : ?>
                                                <option value="<?= $travel->travel ?>"><?= $travel->travel ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5">Tanggal Keberangkatan</label>
                                    <div class="col-sm-7">
                                        <div class="input-group date">
                                            <input type="text" class="form-control" name="tanggal_keberangkatan" id="tanggal_keberangkatan" placeholder="DD-MM-YYYY" autocomplete="off" style="cursor: pointer;">
                                            <div class="input-group-append">
                                                <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="image">Gambar Produk</label>
                                    <div class="col-sm-7">
                                        <img alt="foto" width="100" style="display: none" id="image_foto">
                                        <span id="delete_image" style="display: none; background-color: #E74A3B; color: white; padding: 5px 7px; cursor: pointer; font-size: 13px; margin-left: 10px; border-radius: 3px"><i class="fa fa-solid fa-trash"></i></span>
                                        <p id="image_tidak_ada" style="display: none;"></p>
                                        <div class="form-group" style="margin-bottom: 0;">
                                            <input type="file" class="form-control-file" id="image" name="image" style="border: 1px solid rgb(209,211,226); padding: 3px 5px; cursor: pointer; border-radius: 6px">
                                            <span class="kwitansi-label">Max Size : 4MB</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Second Set of Fields -->
                                <!-- Input Harga Paket -->
                                <div class="form-group row">
                                    <label class="col-sm-5" for="sisa_seat">Sisa Seat</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="sisa_seat" name="sisa_seat" placeholder="Sisa seat" onkeypress="return hanyaAngka(event)" onkeyup="this.value = this.value.replace(/[^0-9]/g, '')">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="harga_paket">Harga Paket</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="harga_paket" name="harga_paket" placeholder="Harga paket">
                                    </div>
                                </div>
                                <!-- Input Fee Agen -->
                                <div class="form-group row">
                                    <label class="col-sm-5" for="fee_agen">Fee Agen</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="fee_agen" name="fee_agen" placeholder="Fee agen">
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
                    </form>

                    <!-- Modal -->
                    <div id="imageModal" class="modal fade" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-body text-center">
                                    <img id="modalImage" src="" alt="Gambar" class="img-fluid" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>

<script>
    $('#tanggal_keberangkatan').datepicker({
        dateFormat: 'dd-mm-yy',
        // minDate: new Date(),
        // maxDate: new Date(),
    });

    $('#kategori_id').select2();
    $(document).ready(function() {
        $('#no_hp').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    });

    // Format input harga_paket dan fee_agen ke format rupiah
    function formatRupiah(angka, prefix) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }

    $('#harga_paket, #fee_agen').on('input', function() {
        var val = $(this).val();
        $(this).val(formatRupiah(val, 'Rp. '));
    });

    // Fungsi untuk memformat input pada saat halaman di-load dan saat input berubah
    function formatMultipleInputsToRupiah(inputIds) {
        inputIds.forEach(function(id) {
            var inputElement = document.getElementById(id);
            var currentValue = inputElement.value;

            // Format nilai awal saat halaman di-load
            if (currentValue) {
                inputElement.value = formatRupiah(currentValue, 'Rp. ');
            }

            // Event listener untuk memformat input saat pengguna mengetik
            inputElement.addEventListener('input', function(e) {
                inputElement.value = formatRupiah(this.value, 'Rp. ');
            });
        });
    }

    $(document).ready(function() {
        var id = $('#id').val();
        var aksi = $('#aksi').val();
        var kode = $('#kode').val();

        if (id == 0) {
            $('.aksi').text('Save');
            $('#customer_id').val(kode).attr('readonly', true);
        } else {
            $('.aksi').text('Update');
            $("select option[value='group']").hide();
            $.ajax({
                url: "<?php echo site_url('pu_produk_agen/edit_data') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    moment.locale('id');
                    $('#id').val(data['master'].id);
                    // // ISI FIELD PRODUK AGEN
                    $('#nama_produk').val(data['master'].nama_produk);
                    $('#travel').val(data['master'].travel);
                    $('#tanggal_keberangkatan').val(moment(data['master'].tanggal_keberangkatan).format('DD-MM-YYYY'));
                    $('#sisa_seat').val(data['master'].sisa_seat);

                    // Harga Paket & Fee Agen (format ke rupiah)
                    if (data['master'].harga_paket) {
                        $('#harga_paket').val(formatRupiah(data['master'].harga_paket, 'Rp. '));
                    }
                    if (data['master'].fee_agen) {
                        $('#fee_agen').val(formatRupiah(data['master'].fee_agen, 'Rp. '));
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });

        }

        // Script delete data file gambar

        function deleteFile(field) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "File ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?php echo base_url("pu_produk_agen/delete_file"); ?>', // URL controller untuk menghapus file
                        type: 'POST',
                        data: {
                            id: id, // Kirim ID customer untuk diupdate
                            field: field // Kirim nama field yang akan dihapus
                        },
                        success: function(response) {
                            Swal.fire(
                                'Terhapus!',
                                'File berhasil dihapus.',
                                'success'
                            );
                            // Update tampilan untuk field yang dihapus
                            $('#' + field + '_foto').hide();
                            $('#' + field + '_image').hide();
                            $('#' + field + '_tidak_ada').text('Tidak ada').show();
                            $('#delete_' + field).hide();
                        },
                        error: function() {
                            Swal.fire('Error', 'Tidak dapat menghapus file.', 'error');
                        }
                    });
                }
            });
        }
        if (aksi == "read") {
            // Disable semua input, select, textarea, radio, dan tombol plus
            $('#form input, #form select, #form textarea, #form button, #form .input-group-append button').attr('disabled', true);
            // Khusus tombol back dan submit tetap aktif
            $('.btn-secondary').attr('disabled', false);
            $('.aksi').hide(); // Sembunyikan tombol submit
        }

        $('#image').on('change', function() {
            var file = this.files[0];
            if (file) {
                // Cek ukuran file
                if (file.size > 4 * 1024 * 1024) { // 4 MB
                    Swal.fire({
                        icon: 'error',
                        title: 'Ukuran file terlalu besar',
                        text: 'Maksimal ukuran file adalah 4 MB!',
                    });
                    $(this).val(''); // reset input file
                    return;
                }
                // Cek tipe file
                var allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                if ($.inArray(file.type, allowedTypes) === -1) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Tipe file tidak didukung',
                        text: 'Hanya file JPG, JPEG, atau PNG yang diperbolehkan!',
                    });
                    $(this).val(''); // reset input file
                    return;
                }
            }
        });

        $("#form").submit(function(e) {
            e.preventDefault();
            var $form = $(this);
            if (!$form.valid()) return false;

            var url;
            if (id == 0) {
                url = "<?php echo site_url('pu_produk_agen/add') ?>";
            } else {
                url = "<?php echo site_url('pu_produk_agen/update') ?>";
            }

            // Gunakan FormData untuk menangani file upload
            var formData = new FormData($form[0]); // Ambil semua data form termasuk file

            // Tampilkan loading Swal sebelum request dikirim
            Swal.fire({
                title: 'Memproses...',
                html: 'Mohon tunggu, data sedang disimpan',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                contentType: false, // Agar tidak mengubah tipe konten FormData
                processData: false, // Agar data FormData tidak diproses menjadi string
                dataType: "JSON",
                success: function(data) {
                    Swal.close(); // Tutup loading swal setelah request selesai

                    if (data.status) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Your data has been saved',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.href = "<?= base_url('pu_produk_agen') ?>";
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.close(); // Tutup loading swal jika terjadi error
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error adding / updating data!',
                    });
                }
            });
        });

        $("#form").validate({
            rules: {
                customer_id: {
                    required: true,
                },
                group_id: {
                    required: true,
                },
                title: {
                    required: true,
                },
                nama: {
                    required: true,
                },
                jenis_kelamin: {
                    required: true,
                },
                no_hp: {
                    required: true,
                },
                asal: {
                    required: true,
                },
            },
            messages: {
                customer_id: {
                    required: "ID Customer is required",
                },
                group_id: {
                    required: "Group ID is required",
                },
                title: {
                    required: "Title is required",
                },
                nama: {
                    required: "Nama is required",
                },
                jenis_kelamin: {
                    required: "Jenis Kelamin is required",
                },
                no_hp: {
                    required: "No Telp is required",
                },
                asal: {
                    required: "Asal is required",
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

    });
</script>