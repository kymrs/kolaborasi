<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title_view ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header text-right">
                    <a class="btn btn-secondary btn-sm" href="<?= base_url('databooking') ?>"><i class="fas fa-chevron-left"></i>&nbsp;Back</a>
                </div>
                <div class="card-body">
                    <form id="form">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-5">Kode Booking</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="kode_booking" name="kode_booking">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5">Nama</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5">No. Handphone</label>
                                    <div class="col-sm-7">
                                        <input type="number" class="form-control" id="no_hp" name="no_hp" placeholder="No. Handphone">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5">Email</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="email" name="email" placeholder="Email">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5">Tanggal Berangkat</label>
                                    <div class="col-sm-7">
                                        <div class="input-group date">
                                            <input type="text" class="form-control" onchange="cek()" name="tgl_berangkat" id="tgl_berangkat" placeholder="DD-MM-YYYY" autocomplete="off" readonly />
                                            <div class="input-group-append">
                                                <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5">Tanggal Pulang</label>
                                    <div class="col-sm-7">
                                        <div class="input-group date">
                                            <input type="text" class="form-control" onchange="cek()" name="tgl_pulang" id="tgl_pulang" placeholder="DD-MM-YYYY" autocomplete="off" readonly />
                                            <div class="input-group-append">
                                                <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-5">Jam Penjemputan</label>
                                    <div class="col-sm-7">
                                        <div class="input-group date" id="jp" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input" data-toggle="datetimepicker" data-target="#jp" name="jam_jemput" id="jam_jemput" placeholder="HH:MM" autocomplete="off" />
                                            <div class="input-group-append" data-target="#jp" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="far fa-clock"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5">Titik Penjemputan</label>
                                    <div class="col-sm-7">
                                        <textarea class="form-control" name="titik_jemput" id="titik_jemput" placeholder="Titik Penjemputan"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5">Type Kendaraan</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" name="type_kendaraan" id="type_kendaraan">
                                            <option value="">-- Pilih --</option>
                                            <option value="Hiace">Hiace (14 Seats)</option>
                                            <option value="Medium Bus">Medium Bus (31 Seats)</option>
                                            <option value="Big Bus">Big Bus (50 Seats)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5">Jumlah Kendaraan</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="jumlah" name="jumlah" placeholder="Jumlah Kendaraan">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5">Status</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" name="status" id="status">
                                            <option value="">-- Pilih --</option>
                                            <option value="Waiting">Waiting</option>
                                            <option value="On Proccess">On Proccess</option>
                                            <option value="Done">Done</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="id" id="id" value="<?= $id ?>">
                        <?php if (!empty($aksi)) { ?>
                            <input type="hidden" name="aksi" id="aksi" value="<?= $aksi ?>">
                        <?php } ?>
                        <?php if ($id == 0) { ?>
                            <input type="hidden" name="kode" id="kode" value="<?= $kode ?>">
                        <?php } ?>
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
    $(document).ready(function() {
        var id = $('#id').val();
        var aksi = $('#aksi').val();
        var kode = $('#kode').val();

        if (id == 0) {
            $('.aksi').text('Save');
            $('#kode_booking').val(kode).attr('readonly', true);
        } else {
            $('.aksi').text('Update');
            $("select option[value='']").hide();
            $.ajax({
                url: "<?php echo site_url('databooking/edit_data') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    moment.locale('id')
                    $('#id').val(data.id);
                    $('#kode_booking').val(data.kode_booking).attr('readonly', true);
                    $('#nama').val(data.nama);
                    $('#no_hp').val(data.no_hp);
                    $('#email').val(data.email);
                    $('#tgl_berangkat').val(moment(data.tgl_berangkat).format('DD-MM-YYYY'));
                    $('#tgl_pulang').val(moment(data.tgl_pulang).format('DD-MM-YYYY'));
                    $('#jam_jemput').val(data.jam_jemput);
                    $('#titik_jemput').val(data.titik_jemput);
                    $('#type_kendaraan').val(data.type_kendaraan);
                    $('#jumlah').val(data.jumlah);
                    $('#status').val(data.status);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        if (aksi == "read") {
            $('.aksi').hide();
            $('#id').prop('readonly', true);
            $('#nama').prop('readonly', true);
            $('#no_hp').prop('readonly', true);
            $('#email').prop('readonly', true);
            $('#tgl_berangkat').prop('disabled', true);
            $('#tgl_pulang').prop('disabled', true);
            $('#jam_jemput').prop('readonly', true);
            $('#titik_jemput').prop('readonly', true);
            $('#type_kendaraan').prop('disabled', true);
            $('#jumlah').prop('readonly', true);
            $('#status').prop('disabled', true);
        }

        $("#form").submit(function(e) {
            e.preventDefault();
            var $form = $(this);
            if (!$form.valid()) return false;
            var url;
            if (id == 0) {
                url = "<?php echo site_url('databooking/add') ?>";
            } else {
                url = "<?php echo site_url('databooking/update') ?>";
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
                            position: 'top-end',
                            icon: 'success',
                            title: 'Your data has been saved',
                            showConfirmButton: false,
                            timer: 1500
                        }).then((result) => {
                            location.href = "<?= base_url('databooking') ?>";
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
                nama: {
                    required: true,
                },
                no_hp: {
                    required: true,
                },
                email: {
                    required: true,
                    email: true
                },
                tgl_berangkat: {
                    required: true,
                },
                tgl_pulang: {
                    required: true,
                },
                jam_jemput: {
                    required: true,
                },
                titik_jemput: {
                    required: true,
                },
                type_kendaraan: {
                    required: true,
                },
                jumlah: {
                    required: true,
                },
                status: {
                    required: true,
                },
            },
            messages: {
                nama: {
                    required: "Nama is required",
                },
                no_hp: {
                    required: "No. Handphone is required",
                },
                email: {
                    required: "Email is required",
                },
                tgl_berangkat: {
                    required: "Tanggal Berangkat is required",
                },
                tgl_pulang: {
                    required: "Tanggal Pulang is required",
                },
                jam_jemput: {
                    required: "Jam Penjemputan is required",
                },
                titik_jemput: {
                    required: "Titik Penjemputan is required",
                },
                type_kendaraan: {
                    required: "Type Kendaraan is required",
                },
                jumlah: {
                    required: "Jumlah Kendaraan is required",
                },
                status: {
                    required: "Status is required",
                },
            },
            errorPlacement: function(error, element) {
                if (element.parent().hasClass('input-group')) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
        })
    })

    $('#tgl_berangkat').datepicker({
        dateFormat: 'dd-mm-yy',
        minDate: new Date(),
    });

    $('#tgl_pulang').datepicker({
        dateFormat: 'dd-mm-yy',
        minDate: new Date(),
    });

    $('#jp').datetimepicker({
        format: 'HH:mm',
    });

    function cek() {
        date1 = $('#tgl_berangkat').val()
        date2 = $('#tgl_pulang').val()
        if (date1 != "") {
            $('#tgl_berangkat').removeClass('error')
            $('#tgl_berangkat-error').remove()
        }
        if (date2 != "") {
            $('#tgl_pulang').removeClass('error')
            $('#tgl_pulang-error').remove()
        }
        var tgl_berangkat = date1.split("-").reverse().join("-")
        var tgl_pulang = date2.split("-").reverse().join("-")
        if (date1 != "" && date2 != "" && tgl_pulang < tgl_berangkat) {
            Swal.fire({
                icon: 'error',
                title: 'Oopss...',
                text: 'Tanggal berangkat tidak boleh melebihi tanggal pulang',
                showConfirmButton: false,
                timer: 1500
            }).then(function() {
                $('#tgl_berangkat').val("");
                $('#tgl_pulang').val("")
            })
        }
    }
</script>