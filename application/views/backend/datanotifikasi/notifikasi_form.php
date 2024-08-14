<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title_view ?></h1>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header text-right">
                    <a class="btn btn-secondary btn-sm" href="<?= base_url('datanotifikasi') ?>"><i class="fas fa-chevron-left"></i>&nbsp;Back</a>
                </div>
                <div class="card-body">
                    <form id="form">
                        <div class="row">
                            <div class="col-md-6"> 
                                <!-- First Set of Fields -->
                                <div class="form-group row">
                                    <label class="col-sm-5" for="kode_notifikasi">Kode Notifikasi</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="kode_notifikasi" name="kode_notifikasi" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="nama">Nama</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="nama" name="nama" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="jabatan">Jabatan</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="jabatan" name="jabatan" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="departemen">Departemen</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="departemen" name="departemen" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="pengajuan">Pengajuan</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" id="pengajuan" name="pengajuan">
                                            <option value="izin">Izin Tidak Masuk</option>
                                            <option value="pulang awal">Pulang Awal</option>
                                            <option value="datang terlambat">Datang Terlambat</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- Added Date, Time, and Reason Fields -->
                                <div class="form-group row">
                                    <label class="col-sm-5" for="tanggal">Tanggal</label>
                                    <div class="col-sm-7">
                                        <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="waktu">Waktu</label>
                                    <div class="col-sm-7">
                                        <input type="time" class="form-control" id="waktu" name="waktu" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="alasan">Alasan</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="alasan" name="alasan" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Status and Catatan Fields Moved Here -->
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
                                <div class="form-group row">
                                    <label class="col-sm-5" for="catatan">Catatan</label>
                                    <div class="col-sm-7">
                                        <textarea class="form-control" id="catatan" name="catatan"></textarea>
                                    </div>
                                </div>
                                <!-- Notifikasi ke (Tahun Berjalan) -->
                                <div class="form-group row">
                                    <label class="col-sm-5" for="notifikasi">Notifikasi ke (Tahun Berjalan)</label>
                                    <div class="col-sm-7">
                                        <input type="int" class="form-control" id="notifikasi" name="notifikasi" required>
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
                        <button type="submit" class="btn btn-primary btn-sm aksi">Save</button>
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
            $('#kode_notifikasi').val(kode).attr('readonly', true);
        } else {
            $('.aksi').text('Update');
            $("select option[value='']").hide();
            $.ajax({
                url: "<?php echo site_url('datanotifikasi/edit_data') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    moment.locale('id')
                    $('#id').val(data.id);
                    $('#kode_notifikasi').val(data.kode_notifikasi).attr('readonly', true);
                    $('#nama').val(data.nama);
                    $('#jabatan').val(data.jabatan);
                    $('#departemen').val(data.departemen); // Corrected from depertemen
                    $('#pengajuan').val(data.pengajuan);
                    $('#tanggal').val(moment(data.tanggal).format('YYYY-MM-DD')); // Changed to 'YYYY-MM-DD'
                    $('#waktu').val(data.waktu);
                    $('#alasan').val(data.alasan);
                    $('#status').val(data.status);
                    $('#catatan').val(data.catatan);
                    $('#notifikasi').val(data.notifikasi);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error getting data from ajax');
                }
            });
        }

        if (aksi == "read") {
            $('.aksi').hide();
            $('#id').prop('readonly', true);
            $('#nama').prop('readonly', true);
            $('#jabatan').prop('readonly', true);
            $('#departemen').prop('readonly', true);
            $('#tanggal').prop('disabled', true);
            $('#waktu').prop('disabled', true); // Added this
            $('#alasan').prop('readonly', true); // Added this
            $('#status').prop('disabled', true);
            $('#catatan').prop('readonly', true); // Added this
            $('#notifikasi').prop('readonly', true); // Added this
        }

        $("#form").submit(function(e) {
            e.preventDefault();
            var $form = $(this);
            if (!$form.valid()) return false;
            var url;
            if (id == 0) {
                url = "<?php echo site_url('datanotifikasi/add') ?>";
            } else {
                url = "<?php echo site_url('datanotifikasi/update') ?>";
            }

            $.ajax({
                url: url,
                type: "POST",
                data: $('#form').serialize(),
                dataType: "JSON",
                success: function(data) {
                    if (data.status) {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Your data has been saved',
                            showConfirmButton: false,
                            timer: 1500
                        }).then((result) => {
                            location.href = "<?= base_url('datanotifikasi') ?>";
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error adding / updating data');
                }
            });
        });

        $("#form").validate({
            rules: {
                nama: {
                    required: true,
                },
                jabatan: {
                    required: true,
                },
                departemen: {
                    required: true,
                },
                pengajuan: {
                    required: true,
                },
                tanggal: {
                    required: true,
                },
                waktu: {
                    required: true,
                },
                alasan: {
                    required: true,
                },
                status: {
                    required: true,
                },
                catatan: {
                    required: true,
                },
                notifikasi: {
                    required: true,
                },
            },
            messages: {
                nama: {
                    required: "Nama Harus Diisi",
                },
                jabatan: {
                    required: "Jabatan Harus Diisi",
                },
                departemen: {
                    required: "Departemen Harus Diisi",
                },
                pengajuan: {
                    required: "Pengajuan Harus Diisi",
                },
                tanggal: {
                    required: "Tanggal Harus Diisi",
                },
                waktu: {
                    required: "Waktu Harus Diisi",
                },
                alasan: {
                    required: "Alasan Harus Diisi",
                },
                status: {
                    required: "Status Harus Diisi",
                },
                catatan: {
                    required: "Catatan Harus Diisi",
                },
                notifikasi: {
                    required: "Notifikasi Harus Diisi",
                },
            },
            errorPlacement: function(error, element) {
                if (element.parent().hasClass('input-group')) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
        });
    });
</script>