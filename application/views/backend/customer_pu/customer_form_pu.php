<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title_view ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header text-right">
                    <a class="btn btn-secondary btn-sm" href="<?= base_url('customer_pu') ?>">
                        <i class="fas fa-chevron-left"></i>&nbsp;Back
                    </a>
                </div>
                <div class="card-body">
                    <form id="form">
                        <div class="row">
                            <div class="col-md-6">
                                <!-- First Set of Fields -->
                                <div class="form-group row">
                                    <label class="col-sm-5" for="customer_id">Customer ID</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="customer_id" name="customer_id" value="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="group_id">Group ID</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" id="group_id" name="group_id" style="cursor: pointer;">
                                            <option value="" hidden>Pilih Group</option>
                                            <option value="">Non Group</option>
                                            <?php foreach ($group_id as $data) : ?>
                                                <option value="<?= $data['group_id'] ?>"><?= $data['group_id'] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="tgl_berangkat">Tanggal Berangkat</label>
                                    <div class="col-sm-7">
                                        <div class="input-group date">
                                            <input type="text" class="form-control" name="tgl_berangkat" id="tgl_berangkat" placeholder="DD-MM-YYYY" autocomplete="off" style="cursor: pointer;">
                                            <div class="input-group-append">
                                                <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Second Set of Fields -->
                                <div class="form-group row">
                                    <label class="col-sm-5" for="nama">Nama</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="nama" name="nama" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="no_hp">No Telp</label>
                                    <div class="col-sm-7">
                                        <input type="number" class="form-control" id="no_hp" name="no_hp">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="travel_id">Travel</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" id="travel_id" name="travel_id" style="cursor: pointer;">
                                            <option value="" hidden>Pilih Travel</option>
                                            <?php foreach ($travel as $data) : ?>
                                                <option value="<?= $data['id'] ?>"><?= $data['travel'] ?></option>
                                            <?php endforeach ?>
                                        </select>
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
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>

<script>
    $('#tgl_berangkat').datepicker({
        dateFormat: 'dd-mm-yy',
        minDate: new Date(),
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
        // Ketika halaman di-load, panggil customer ID dari server
        $.ajax({
            url: '<?= base_url("customer_pu/generate_customer_id") ?>', // Sesuaikan dengan URL yang benar
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                // Isi nilai customer_id ke input field
                $('#customer_id').val(response.customer_id);
            }
        });
    });

    $(document).ready(function() {
        var id = $('#id').val();
        var aksi = $('#aksi').val();
        var kode = $('#kode').val();

        if (id == 0) {
            $('.aksi').text('Save');
            $('#customer_id').val(kode).attr('readonly', true);
        } else {
            $('.aksi').text('Update');
            $("select option[value='']").hide();
            $.ajax({
                url: "<?php echo site_url('customer_pu/edit_data') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    // console.log(data);
                    moment.locale('id')
                    $('#id').val(data['master'].id);
                    $('#customer_id').val(data['master'].customer_id.toUpperCase()).attr('readonly', true);
                    $('#group_id').val(data['master'].group_id);
                    $('#tgl_berangkat').val(moment(data['master'].tgl_berangkat).format('DD-MM-YYYY'));
                    $('#nama').val(data['master'].nama);
                    $('#no_hp').val(data['master'].no_hp);
                    $('#travel_id').val(data['master'].travel_id);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        if (aksi == "read") {
            $('.aksi').hide();
            $('#group_id').prop('disabled', true);
            $('#tgl_berangkat').prop('disabled', true);
            $('#nama').prop('readonly', true);
            $('#no_hp').prop('readonly', true);
            $('#travel_id').prop('disabled', true);
        }

        $("#form").submit(function(e) {
            e.preventDefault();
            var $form = $(this);
            if (!$form.valid()) return false;
            var url;
            if (id == 0) {
                url = "<?php echo site_url('customer_pu/add') ?>";
            } else {
                url = "<?php echo site_url('customer_pu/update') ?>";
            }

            $.ajax({
                url: url,
                type: "POST",
                data: $('#form').serialize(),
                dataType: "JSON",
                success: function(data) {
                    // console.log(data);
                    if (data.status) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Your data has been saved',
                            showConfirmButton: false,
                            timer: 1500
                        }).then((result) => {
                            location.href = "<?= base_url('customer_pu') ?>";
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
                customer_id: {
                    required: true,
                },
                tgl_berangkat: {
                    required: true,
                },
                nama: {
                    required: true,
                },
                no_hp: {
                    required: true,
                },
                travel_id: {
                    required: true,
                },
            },
            messages: {
                customer_id: {
                    required: "ID Customer is required",
                },
                tgl_berangkat: {
                    required: "Tanggal Berangkat is required",
                },
                nama: {
                    required: "Nama is required",
                },
                no_hp: {
                    required: "Contact Number is required",
                },
                travel_id: {
                    required: "Travel is required",
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

        // MEMBUAT TAMPILAN HARGA MENJADI ADA TITIK
        // $('#sebesar').on('input', function() {
        //     let value = $(this).val().replace(/[^,\d]/g, '');
        //     let parts = value.split(',');
        //     let integerPart = parts[0];

        //     // Format tampilan dengan pemisah ribuan
        //     integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

        //     // Set nilai yang diformat ke tampilan
        //     $(this).val(parts[1] !== undefined ? integerPart + ',' + parts[1] : integerPart);

        //     // Hapus semua pemisah ribuan untuk pengiriman ke server
        //     let cleanValue = value.replace(/\./g, '');

        //     // Anda mungkin ingin menyimpan nilai bersih ini di input hidden atau langsung mengirimkannya ke server
        //     $('#hidden_sebesar').val(cleanValue);
        // });

        // // Update signature name on input
        // $('#nama_pengajuan').on('input', function() {
        //     var namaPengajuan = $(this).val();
        //     $('#signNamaPengajuan').text(namaPengajuan);
        // });

    });
</script>