<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title_view ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header text-right">
                    <a class="btn btn-secondary btn-sm" href="<?= base_url('datadeklarasi') ?>"><i class="fas fa-chevron-left"></i>&nbsp;Back</a>
                </div>
                <div class="card-body">
                    <form id="form">
                        <div class="row">
                            <div class="col-md-6">
                                <!-- First Set of Fields -->
                                <div class="form-group row">
                                    <label class="col-sm-5" for="kode_deklarasi">Kode Deklarasi</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="kode_deklarasi" name="kode_deklarasi" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="declarationDate">Tanggal</label>
                                    <div class="col-sm-7">
                                        <input type="teks" class="form-control" id="tanggal" name="tanggal" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="declarationName">Nama yang mengajukan</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="nama_pengajuan" name="nama_pengajuan" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5">Jabatan</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" name="jabatan" id="jabatan">
                                            <option value="">-- Pilih --</option>
                                            <option value="Waiting">Magang</option>
                                            <option value="On Proccess">Karyawan</option>
                                            <option value="Done">Supervisor</option>
                                            <option value="Done">Manager</option>
                                            <option value="Done">General Manager</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Second Set of Fields -->
                                <div class="form-group row">
                                    <label class="col-sm-5" for="paymentName">Nama yang menerima pembayaran</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="nama_dibayar" name="nama_dibayar" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="paymentPurpose">Tujuan</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="tujuan" name="tujuan" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="paymentAmount">Sebesar</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="sebesar" name="sebesar" required>
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
            $('#kode_deklarasi').val(kode).attr('readonly', true);
        } else {
            $('.aksi').text('Update');
            $("select option[value='']").hide();
            $.ajax({
                url: "<?php echo site_url('datadeklarasi/edit_data') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    moment.locale('id')
                    $('#id').val(data.id);
                    $('#kode_deklarasi').val(data.kode_deklarasi).attr('readonly', true);
                    $('#tanggal').val(moment(data.tanggal).format('DD-MM-YYYY'));
                    $('#nama_pengajuan').val(data.nama_pengajuan);
                    $('#jabatan').val(data.jabatan);
                    $('#nama_dibayar').val(data.nama_dibayar);
                    $('#tujuan').val(data.tujuan);
                    $('#sebesar').val(data.sebesar);
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
            $('#tanggal').prop('disabled', true);
            $('#nama_pengajuan').prop('readonly', true);
            $('#jabatan').prop('readonly', true);
            $('#nama_dibayar').prop('readonly', true);
            $('#tujuan').prop('readonly', true);
            $('#sebesar').prop('readonly', true);
            $('#status').prop('disabled', true);
        }

        $("#form").submit(function(e) {
            e.preventDefault();
            var $form = $(this);
            if (!$form.valid()) return false;
            var url;
            if (id == 0) {
                url = "<?php echo site_url('datadeklarasi/add') ?>";
            } else {
                url = "<?php echo site_url('datadeklarasi/update') ?>";
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
                            location.href = "<?= base_url('datadeklarasi') ?>";
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
                tanggal: {
                    required: true,
                },
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
                status: {
                    required: true,
                },
            },
            messages: {
                tanggal: {
                    required: "tanggal Harus Diisi",
                },
                nama_pengajuan: {
                    required: "Nama Yang Mengajukan Harus Diisi",
                },
                jabatan: {
                    required: "Jabatan Harus Diisi",
                },
                nama_dibayar: {
                    required: "Nama Yang Menerima Pembayaran Harus Diisi",
                },
                tujuan: {
                    required: "Tujuan Harus Diisi",
                },
                sebesar: {
                    required: "Sebesar Harus Diisi",
                },
                status: {
                    required: "Status Harus Diisi",
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

    $('#tanggal').datepicker({
        dateFormat: 'dd-mm-yy',
        minDate: new Date(),
    });

function formatNumber(value) {
    return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

$(document).ready(function() {
    // Format input field on keyup
    $('#sebesar').on('keyup', function() {
        var input = $(this).val().replace(/\./g, ''); // Remove existing dots
        if ($.isNumeric(input)) {
            $(this).val(formatNumber(input));
        }
    });

    // Optionally, format input on blur
    $('#sebesar').on('blur', function() {
        var input = $(this).val().replace(/\./g, ''); // Remove existing dots
        if ($.isNumeric(input)) {
            $(this).val(formatNumber(input));
        }
    });
});


</script>