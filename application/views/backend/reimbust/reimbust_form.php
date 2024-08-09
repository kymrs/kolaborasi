<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title_view ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header text-right">
                    <a class="btn btn-secondary btn-sm" href="<?= base_url('prepayment') ?>"><i class="fas fa-chevron-left"></i>&nbsp;Back</a>
                </div>
                <div class="card-body">
                    <form id="form">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-5">Kode Prepayment</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="kode_prepayment" name="kode_prepayment">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5">Nama</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="nama" name="nama">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5">Departemen</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" name="departemen" id="departemen">
                                            <option value="">-- Pilih --</option>
                                            <option value="marketing">Marketing</option>
                                            <option value="it">IT</option>
                                            <option value="ga">General Affair</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5">Jabatan</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="jabatan" name="jabatan">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5">Sifat Pelaporan</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="sifat_pelaporan" name="sifat_pelaporan">
                                    </div>
                                </div>
                            </div>

                            <!-- SEBELAH KANAN -->
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-5">Tanggal Pengajuan</label>
                                    <div class="col-sm-7">
                                        <div class="input-group date">
                                            <input type="text" class="form-control" name="tgl_pengajuan" id="tgl_pengajuan" placeholder="DD-MM-YYYY" autocomplete="off" readonly />
                                            <div class="input-group-append">
                                                <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5">Tujuan</label>
                                    <div class="col-sm-7">
                                        <div class="form-floating">
                                            <textarea class="form-control" placeholder="Tujuan" id="tujuan" name="tujuan"></textarea>
                                        </div>
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
            $('#kode_prepayment').val(kode).attr('readonly', true);
        } else {
            $('.aksi').text('Update');
            $("select option[value='']").hide();
            $.ajax({
                url: "<?php echo site_url('reimbust/edit_data') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    moment.locale('id')
                    $('#id').val(data.id);
                    $('#kode_prepayment').val(data.kode_prepayment).attr('readonly', true);
                    $('#nama').val(data.nama);
                    $('#jabatan').val(data.jabatan);
                    $('#departemen').val(data.departemen);
                    $('#sifat_pelaporan').val(data.sifat_pelaporan);
                    $('#tgl_pengajuan').val(moment(data.tgl_pengajuan).format('DD-MM-YYYY'));
                    $('#tujuan').val(data.tujuan);
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
            $('#departemen').prop('disabled', true);
            $('#jabatan').prop('readonly', true);
            $('#sifat_pelaporan').prop('disabled', true);
            $('#tgl_pengajuan').prop('disabled', true);
            $('#tujuan').prop('readonly', true);
            $('#status').prop('disabled', true);
        }

        $("#form").submit(function(e) {
            e.preventDefault();
            var $form = $(this);
            if (!$form.valid()) return false;
            var url;
            if (id == 0) {
                url = "<?php echo site_url('reimbust/add') ?>";
            } else {
                url = "<?php echo site_url('reimbust/update') ?>";
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
                            location.href = "<?= base_url('reimbust') ?>";
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
                departemen: {
                    required: true,
                },
                jabatan: {
                    required: true,
                },
                sifat_pelaporan: {
                    required: true,
                },
                tgl_pengajuan: {
                    required: true,
                },
                tujuan: {
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
                departemen: {
                    required: "Departemen is required",
                },
                jabatan: {
                    required: "Jabatan is required",
                },
                sifat_pelaporan: {
                    required: "Sifat Pelaporan is required",
                },
                tgl_pengajuan: {
                    required: "Tanggal Pengajuan is required",
                },
                tujuan: {
                    required: "Tujuan is required",
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
            highlight: function(element) {
                $(element).addClass('is-invalid'); // Tambahkan kelas untuk menandai input tidak valid
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid'); // Hapus kelas jika input valid
            },
            focusInvalid: false, // Disable auto-focus on the first invalid field
        });


        // $("#form").validate({
        //     rules: {
        //         nama: {
        //             required: true,
        //         },
        //         departemen: {
        //             required: true,
        //         },
        //         jabatan: {
        //             required: true,
        //         },
        //         sifat_pelaporan: {
        //             required: true,
        //         },
        //         tgl_pengajuan: {
        //             required: true,
        //         },
        //         tujuan: {
        //             required: true,
        //         },
        //         status: {
        //             required: true,
        //         },
        //     },
        //     messages: {
        //         nama: {
        //             required: "Nama is required",
        //         },
        //         departemen: {
        //             required: "Departemen is required",
        //         },
        //         jabatan: {
        //             required: "Jabatan is required",
        //         },
        //         sifat_pelaporan: {
        //             required: "Sifat Pelaporan is required",
        //         },
        //         tgl_pengajuan: {
        //             required: "Tanggal Pengajuan is required",
        //         },
        //         tujuan: {
        //             required: "tujuan is required",
        //         },
        //         status: {
        //             required: "Status is required",
        //         },
        //     },
        //     errorPlacement: function(error, element) {
        //         if (element.parent().hasClass('input-group')) {
        //             error.insertAfter(element.parent());
        //         } else {
        //             error.insertAfter(element);
        //         }
        //     },
        // })
    })

    $('#tgl_pengajuan').datepicker({
        dateFormat: 'dd-mm-yy',
        minDate: new Date(),
    });

    // $('#jp').datetimepicker({
    //     format: 'HH:mm',
    // });
</script>