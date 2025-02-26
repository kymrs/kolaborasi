<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title_view ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header text-right">
                    <a class="btn btn-secondary btn-sm" href="<?= base_url('datadeklarasi_kps') ?>">
                        <i class="fas fa-chevron-left"></i>&nbsp;Back
                    </a>
                </div>
                <div class="card-body">
                    <form id="form">
                        <div class="row">
                            <div class="col-md-6">
                                <!-- First Set of Fields -->
                                <div class="form-group row">
                                    <label class="col-sm-5" for="tgl_deklarasi">Tanggal</label>
                                    <div class="col-sm-7">
                                        <div class="input-group date">
                                            <input type="text" class="form-control" name="tgl_deklarasi" id="tgl_deklarasi" placeholder="DD-MM-YYYY" autocomplete="off" readonly />
                                            <div class="input-group-append">
                                                <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="kode_deklarasi">Kode Deklarasi</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="kode_deklarasi" name="kode_deklarasi" value="">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Second Set of Fields -->
                                <div class="form-group row">
                                    <label class="col-sm-5" for="nama_dibayar">Nama Penerima </label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="nama_dibayar" name="nama_dibayar" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="tujuan">Tujuan</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="tujuan" name="tujuan">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="sebesar">Sebesar</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="sebesar" id="sebesar">
                                        <input type="hidden" class="form-control" id="hidden_sebesar" name="hidden_sebesar">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Loading indicator -->
                        <div id="loading" style="display: none;">
                            <p>Loading...</p>
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

<script>
    $('#tgl_deklarasi').datepicker({
        dateFormat: 'dd-mm-yy',
        minDate: new Date(),
        maxDate: new Date(),

        // MENGENERATE KODE DEKLARASI SETELAH PILIH TANGGAL
        onSelect: function(dateText) {
            var id = dateText;
            $('#tgl_deklarasi').removeClass("is-invalid");

            // Menghapus label error secara manual jika ada
            if ($("#tgl_deklarasi-error").length) {
                $("#tgl_deklarasi-error").remove(); // Menghapus label error
            }
            $.ajax({
                url: "<?php echo site_url('datadeklarasi_kps/generate_kode') ?>",
                type: "POST",
                data: {
                    "date": dateText
                },
                dataType: "JSON",
                success: function(data) {
                    $('#kode_deklarasi').val(data.toUpperCase());
                    $('#kode').val(data);
                },
                error: function(error) {
                    alert("error" + error);
                }
            });
        }
    });

    $(document).ready(function() {
        var id = $('#id').val();
        var aksi = $('#aksi').val();
        var kode = $('#kode').val();

        if (id == 0) {
            $('.aksi').text('Save');
            $('#kode_deklarasi').val(kode).attr('readonly', true);
        } else {
            $('.aksi').text('Update');
            $("select option[value='']").hide();
            $.ajax({
                url: "<?php echo site_url('datadeklarasi_kps/edit_data') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    // console.log(data);
                    moment.locale('id')
                    $('#id').val(data['master'].id);
                    $('#kode_deklarasi').val(data['master'].kode_deklarasi.toUpperCase()).attr('readonly', true);
                    $('#tgl_deklarasi').val(moment(data['master'].tgl_deklarasi).format('DD-MM-YYYY'));
                    $('#nama_dibayar').val(data['master'].nama_dibayar);
                    $('#tujuan').val(data['master'].tujuan);
                    $('#sebesar').val(data['master'].sebesar.replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
                    $('#hidden_sebesar').val(data['master'].sebesar);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        if (aksi == "read") {
            $('.aksi').hide();
            $('#id').prop('readonly', true);
            $('#tgl_deklarasi').prop('disabled', true);
            $('#nama_pengajuan').prop('readonly', true);
            $('#jabatan').prop('disabled', true);
            $('#nama_dibayar').prop('readonly', true);
            $('#tujuan').prop('readonly', true);
            $('#sebesar').prop('readonly', true).val(data.sebesar.replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
            $('#app_name').prop('disabled', true);
            $('#app2_name').prop('disabled', true);
        }

        $("#form").submit(function(e) {
            e.preventDefault();
            var $form = $(this);
            if (!$form.valid()) return false;
            var url;
            if (id == 0) {
                url = "<?php echo site_url('datadeklarasi_kps/add') ?>";
            } else {
                url = "<?php echo site_url('datadeklarasi_kps/update') ?>";
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
                    // console.log(data);
                    // Sembunyikan loading saat respons diterima
                    $('#loading').hide();

                    if (data.status) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Your data has been saved',
                            showConfirmButton: false,
                            timer: 1500
                        }).then((result) => {
                            location.href = "<?= base_url('datadeklarasi_kps') ?>";
                        })
                    } else {
                        // Sembunyikan loading saat respons diterima
                        $('#loading').hide();

                        // Tampilkan pesan kesalahan
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: data.error
                        });
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
                tgl_deklarasi: {
                    required: true,
                },
                // kode_deklarasi: {
                //     required: true,
                // },
                nama_pengajuan: {
                    required: true,
                },
                jabatan: {
                    required: true,
                },
                nama_dibayar: {
                    required: true,
                },
                tujuan: {
                    required: true,
                },
                sebesar: {
                    required: true,
                },
                app_name: {
                    required: true,
                },
                app2_name: {
                    required: true,
                }
            },
            messages: {
                tgl_deklarasi: {
                    required: "Tanggal is required",
                },
                // kode_deklarasi: {
                //     required: "Kode is required",
                // },
                nama_pengajuan: {
                    required: "Nama Pengaju is required",
                },
                jabatan: {
                    required: "Jabatan is required",
                },
                nama_dibayar: {
                    required: "Nama Penerima is required",
                },
                tujuan: {
                    required: "Tujuan is required",
                },
                sebesar: {
                    required: "Sebesar is required",
                },
                app_name: {
                    required: "Yang Mengetahui is required",
                },
                app2_name: {
                    required: "Yang Menyetujui is required",
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

        // MEMBUAT TAMPILAN HARGA MENJADI ADA TITIK
        $('#sebesar').on('input', function() {
            let value = $(this).val().replace(/[^,\d]/g, '');
            let parts = value.split(',');
            let integerPart = parts[0];

            // Format tampilan dengan pemisah ribuan
            integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            // Set nilai yang diformat ke tampilan
            $(this).val(parts[1] !== undefined ? integerPart + ',' + parts[1] : integerPart);

            // Hapus semua pemisah ribuan untuk pengiriman ke server
            let cleanValue = value.replace(/\./g, '');

            // Anda mungkin ingin menyimpan nilai bersih ini di input hidden atau langsung mengirimkannya ke server
            $('#hidden_sebesar').val(cleanValue);
        });

    });
</script>