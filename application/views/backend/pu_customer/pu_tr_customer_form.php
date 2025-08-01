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
    .select2-container--default .select2-selection--single:focus {
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
</style>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title_view ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header text-right">
                    <a style="background-color: rgb(36, 44, 73);" class="btn btn-secondary btn-sm" href="<?= base_url('pu_customer') ?>">
                        <i class="fas fa-chevron-left"></i>&nbsp;Back
                    </a>
                </div>
                <div class="card-body">
                    <form id="form">
                        <div class="row">
                            <div class="col-md-6">
                                <!-- First Set of Fields -->
                                <div class="form-group row">
                                    <label class="col-sm-4" for="tgl_berangkat">Nama Customer</label>
                                    <div class="col-sm-7" style="display: flex">
                                        <select id="nama_customer" name="customer_id" class="form-control" style="cursor: pointer;">
                                            <option value="" selected>Pilih Customer</option>
                                            <?php foreach ($customer as $data) : ?>
                                                <option value="<?= $data['customer_id'] ?>"><?= $data['title'] . '. ' . $data['nama'] ?> </option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4" for="tgl_berangkat">Tanggal Berangkat</label>
                                    <div class="col-sm-7">
                                        <div class="input-group date">
                                            <input type="text" class="form-control" name="tgl_berangkat" id="tgl_berangkat" placeholder="DD-MM-YYYY" autocomplete="off" style="cursor: pointer;">
                                            <div class="input-group-append">
                                                <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4" for="travel">Travel</label>
                                    <div class="col-sm-7" style="display: flex;">
                                        <select class="form-control" id="travel" name="travel" style="cursor: pointer;">
                                            <option value="" hidden>Pilih Travel</option>
                                            <?php foreach ($travel as $data) : ?>
                                                <option value="<?= $data['travel'] ?>"><?= $data['travel'] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                        <div class="btn btn-primary add-travel" data-toggle="modal" data-target="#add-travel" style="margin-left: 7px;"><i class="fas fa-solid fa-plus"></i></div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4" for="produk">Produk</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="produk" name="produk" required autocomplete="off">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4" for="tipe_kamar">Tipe Kamar</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" id="tipe_kamar" name="tipe_kamar" style="cursor: pointer;">
                                            <option value="" hidden>Pilih Tipe Kamar</option>
                                            <option value="Double">Double</option>
                                            <option value="Triple">Triple</option>
                                            <option value="Quad">Quad</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4" for="pakaian">Pakaian</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" id="pakaian" name="pakaian" style="cursor: pointer;">
                                            <option value="" hidden>Pilih Pakaian</option>
                                            <option value="Gamis">Gamis</option>
                                            <option value="Koko">Koko</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4" for="ukuran">Ukuran</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="ukuran" name="ukuran" autocomplete="off">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4" for="kirim_perlengkapan">Kirim Perlengkapan</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" id="kirim_perlengkapan" name="kirim_perlengkapan" style="cursor: pointer;">
                                            <option value="" hidden>Pilih Opsi</option>
                                            <option value="Sudah">Sudah</option>
                                            <option value="Belum">Belum</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4" for="status">Status Keberangkatan</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" id="status" name="status" style="cursor: pointer;">
                                            <option value="" hidden>Pilih Status</option>
                                            <option value="Keluarga">Keluarga</option>
                                            <option value="Sendiri">Sendiri</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Second Set of Fields -->
                                <div class="form-group row">
                                    <label class="col-sm-4" for="harga">Harga</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="harga" name="harga" required autocomplete="off">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4" for="harga_promo">Harga Promo</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="harga_promo" name="harga_promo" autocomplete="off">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4" for="promo_diskon">Promo Diskon</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" id="promo_diskon" name="promo_diskon" required style="cursor: pointer;">
                                            <option value="" hidden>Pilih Promo Diskon</option>
                                            <option value="Ya">Ya</option>
                                            <option value="Tidak">Tidak</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4" for="dp">DP</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="dp" name="dp" autocomplete="off">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4" for="pembayaran_1">Pembayaran 1</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="pembayaran_1" name="pembayaran_1" autocomplete="off">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4" for="pembayaran_2">Pembayaran 2</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="pembayaran_2" name="pembayaran_2" autocomplete="off">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4" for="pelunasan">Pelunasan</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="pelunasan" name="pelunasan" autocomplete="off">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4" for="cashback">Cashback</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="cashback" name="cashback" autocomplete="off">
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

                    <!-- Modal Travel -->
                    <div class="modal fade" id="add-travel">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form id="travel-form">
                                    <div class="modal-header bg-primary text-gray-100">
                                        <h5 class="card-title" style="margin: 0;">Tambah Data Travel</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -23px;">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <label for="" class="col-sm-3 col-form-label">Travel</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="travel" name="travel" placeholder="Nama Travel" required>
                                                <input type="hidden" class="form-control" id="id" name="id" placeholder="id">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="" class="col-sm-3 col-form-label">Bank</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="nama_bank" name="nama_bank" placeholder="Nama Bank" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="" class="col-sm-3 col-form-label">Norek</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="no_rek" name="no_rek" placeholder="Nomor Rekening" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer text-right">
                                        <button type="submit" class="btn btn-primary aksi">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

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
    $('#nama_customer').select2({
        placeholder: "Pilih Customer",
        allowClear: true,
        width: '100%'
    });

    $('#tgl_berangkat').datepicker({
        dateFormat: 'dd-mm-yy',
        // minDate: new Date(),
        // maxDate: new Date(),

        // MENGENERATE KODE DEKLARASI SETELAH PILIH TANGGAL
        onSelect: function(dateText) {
            var id = dateText;
            $('#tgl_berangkat').removeClass("is-invalid");

            // Menghapus label error secara manual jika ada
            if ($("#tgl_berangkat-error").length) {
                $("#tgl_berangkat-error").remove(); // Menghapus label error
            }
        }
    });

    $(document).ready(function() {
        $('#no_hp').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    });

    // Fungsi untuk memformat angka menjadi format Rupiah
    function formatRupiah(angka, prefix) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        // Tambahkan titik jika angka menjadi ribuan
        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }

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
        // Ketika halaman di-load, panggil customer ID dari server
        $.ajax({
            url: '<?= base_url("pu_customer/generate_customer_id") ?>', // Sesuaikan dengan URL yang benar
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                // Isi nilai customer_id ke input field
                $('#customer_id').val(response.customer_id);
            }
        });
    });

    // Panggil fungsi saat halaman di-load
    window.onload = function() {
        var inputIds = ['harga', 'harga_promo', 'dp', 'pembayaran_1', 'pembayaran_2', 'pelunasan', 'cashback'];
        formatMultipleInputsToRupiah(inputIds);
    }

    $(document).ready(function() {
        var id = $('#id').val();
        var aksi = $('#aksi').val();
        var kode = $('#kode').val();

        if (id == 0) {
            $('.aksi').text('Save');
            $('#customer_id').val(kode).attr('readonly', true);
        } else {
            $('#customer_id').val(kode).attr('readonly', true);
            $('.aksi').text('Update');
            $("select option[value='']").hide();
            $.ajax({
                url: "<?php echo site_url('pu_customer/edit_data_transaksi') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    console.log(data)
                    moment.locale('id');
                    $('#id').val(data['master'].id);
                    $('#nama_customer').val(data['master'].customer_id).trigger('change');
                    $('#tgl_berangkat').val(moment(data['master'].tgl_berangkat).format('DD-MM-YYYY'));
                    $('#travel').val(data['master'].travel);
                    $('#produk').val(data['master'].produk);

                    // Memformat harga dan nilai lainnya ke dalam format Rupiah
                    $('#harga').val(formatRupiah(data['master'].harga, 'Rp. '));
                    $('#harga_promo').val(formatRupiah(data['master'].harga_promo, 'Rp. '));
                    $('#dp').val(formatRupiah(data['master'].dp, 'Rp. '));
                    $('#pembayaran_1').val(formatRupiah(data['master'].pembayaran_1, 'Rp. '));
                    $('#pembayaran_2').val(formatRupiah(data['master'].pembayaran_2, 'Rp. '));
                    $('#pelunasan').val(formatRupiah(data['master'].pelunasan, 'Rp. '));
                    $('#cashback').val(formatRupiah(data['master'].cashback, 'Rp. '));

                    $('#promo_diskon').val(data['master'].promo_diskon);
                    $('#tipe_kamar').val(data['master'].tipe_kamar);
                    $('#pakaian').val(data['master'].pakaian);
                    $('#ukuran').val(data['master'].ukuran);
                    $('#kirim_perlengkapan').val(data['master'].kirim_perlengkapan);
                    $('#status').val(data['master'].status);
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
                        url: '<?php echo base_url("pu_customer/delete_file"); ?>', // URL controller untuk menghapus file
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
            $('.aksi').hide();
            $('#nama_customer').prop('disabled', true).css('cursor', 'not-allowed');
            $('#tgl_berangkat').prop('disabled', true).css('cursor', 'not-allowed');
            $('#travel').prop('disabled', true).css('cursor', 'not-allowed');
            $('#produk').prop('readonly', true).css('cursor', 'not-allowed');
            $('#harga').prop('readonly', true).css('cursor', 'not-allowed');
            $('#harga_promo').prop('readonly', true).css('cursor', 'not-allowed');
            $('#tipe_kamar').prop('disabled', true).css('cursor', 'not-allowed');
            $('#promo_diskon').prop('disabled', true).css('cursor', 'not-allowed');

            $('.add-customer').hide();
            $('.add-travel').hide();

            $('#dp').prop('readonly', true).css('cursor', 'not-allowed');
            $('#pembayaran_1').prop('readonly', true).css('cursor', 'not-allowed');
            $('#pembayaran_2').prop('readonly', true).css('cursor', 'not-allowed');
            $('#pelunasan').prop('readonly', true).css('cursor', 'not-allowed');
            $('#cashback').prop('readonly', true).css('cursor', 'not-allowed');
            $('#pakaian').prop('disabled', true).css('cursor', 'not-allowed');
            $('#ukuran').prop('readonly', true).css('cursor', 'not-allowed');
            $('#kirim_perlengkapan').prop('disabled', true).css('cursor', 'not-allowed');
            $('#status').prop('disabled', true).css('cursor', 'not-allowed');

            $('.kwitansi-label').css('display', 'none');
        }

        $("#form").submit(function(e) {
            e.preventDefault();
            var $form = $(this);
            if (!$form.valid()) return false;

            var url;
            if (id == 0) {
                url = "<?php echo site_url('pu_customer/add_transaksi') ?>";
            } else {
                url = "<?php echo site_url('pu_customer/update_transaksi') ?>";
            }

            // Gunakan FormData untuk menangani file upload
            var formData = new FormData($form[0]); // Ambil semua data form termasuk file

            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                contentType: false, // Agar tidak mengubah tipe konten FormData
                processData: false, // Agar data FormData tidak diproses menjadi string
                dataType: "JSON",
                success: function(data) {
                    if (data.status) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Your data has been saved',
                            showConfirmButton: false,
                            timer: 1500
                        }).then((result) => {
                            location.href = "<?= base_url('pu_customer') ?>";
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error adding / updating data');
                }
            });
        });

        $("#travel-form").submit(function(e) {
            e.preventDefault();
            var url;
            var $form = $(this);
            if (!$form.valid()) return false;

            url = "<?php echo site_url('pu_travel/add') ?>";

            $.ajax({
                url: url,
                type: "post",
                data: new FormData(this),
                processData: false,
                contentType: false,
                cache: false,
                async: false,
                success: function(data) {
                    $('#add-travel').modal('hide');
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Your data has been saved',
                        showConfirmButton: false,
                        timer: 1500
                    }).then((result) => {
                        location.href = "<?= base_url('pu_customer/add_form_transaksi') ?>";
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                }
            });
        });

        $("#form").validate({
            rules: {
                customer_id: {
                    required: true,
                },
                tgl_berangkat: {
                    required: true,
                },
                travel: {
                    required: true,
                },
                produk: {
                    required: true,
                },
                tipe_kamar: {
                    required: true,
                },
                pakaian: {
                    required: true,
                },
                ukuran: {
                    required: true,
                },
                kirim_perlengkapan: {
                    required: true,
                },
                status: {
                    required: true,
                },
                harga: {
                    required: true,
                },
                harga_promo: {
                    required: true,
                },
                promo_diskon: {
                    required: true,
                },
                dp: {
                    required: true,
                },
                pembayaran_1: {
                    required: true,
                },
                pembayaran_2: {
                    required: true,
                },
                pelunasan: {
                    required: true,
                },
                cashback: {
                    required: true,
                }
            },
            messages: {
                customer_id: {
                    required: "Nama Customer is required",
                },
                tgl_berangkat: {
                    required: "Tanggal Berangkat is required",
                },
                travel: {
                    required: "Travel is required",
                },
                produk: {
                    required: "Produk is required",
                },
                tipe_kamar: {
                    required: "Tipe Kamar is required",
                },
                pakaian: {
                    required: "Pakaian is required",
                },
                ukuran: {
                    required: "Ukuran is required",
                },
                kirim_perlengkapan: {
                    required: "Kirim Perlengkapan is required",
                },
                status: {
                    required: "Status is required",
                },
                harga: {
                    required: "Harga is required",
                },
                harga_promo: {
                    required: "Harga Promo is required",
                },
                promo_diskon: {
                    required: "Promo Diskon is required",
                },
                dp: {
                    required: "DP is required",
                },
                pembayaran_1: {
                    required: "Pembayaran 1 is required",
                },
                pembayaran_2: {
                    required: "Pembayaran 2 is required",
                },
                pelunasan: {
                    required: "Pelunasan is required",
                },
                cashback: {
                    required: "Cashback is required",
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