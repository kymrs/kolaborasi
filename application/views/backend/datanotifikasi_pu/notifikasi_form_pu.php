<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title_view ?></h1>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header text-right">
                    <a class="btn btn-secondary btn-sm" href="<?= base_url('datanotifikasi_pu') ?>"><i class="fas fa-chevron-left"></i>&nbsp;Back</a>
                </div>
                <div class="card-body">
                    <form id="form">
                        <div class="row">
                            <div class="col-md-6">
                                <!-- First Set of Fields -->
                                <div class="form-group row">
                                    <label class="col-sm-5" for="tgl_notifikasi">Tanggal</label>
                                    <div class="col-sm-7">
                                        <div class="input-group date">
                                            <input type="text" class="form-control" name="tgl_notifikasi" id="tgl_notifikasi" placeholder="DD-MM-YYYY" autocomplete="off" readonly />
                                            <div class="input-group-append">
                                                <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="kode_notifikasi">Kode Notifikasi</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="kode_notifikasi" name="kode_notifikasi">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="pengajuan">Pengajuan</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" id="pengajuan" name="pengajuan">
                                            <option value="" selected disabled>Pilih opsi...</option>
                                            <option value="izin">Izin Tidak Masuk</option>
                                            <option value="pulang awal">Pulang Awal</option>
                                            <option value="datang terlambat">Datang Terlambat</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-5" for="waktu">Waktu</label>
                                    <div class="col-sm-7">
                                        <input type="time" class="form-control" id="waktu" name="waktu" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="alasan">Alasan</label>
                                    <div class="col-sm-7">
                                        <textarea class="form-control" name="alasan" id="alasan"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="id" id="id" value="<?= $id ?>">
                        <?php if (!empty($aksi)) { ?>
                            <input type="hidden" name="aksi" id="aksi" value="<?= $aksi ?>">
                        <?php } ?>
                        <?php if ($id == 0) { ?>
                            <input type="hidden" name="kode" id="kode" value="">
                        <?php } ?>
                        <button type="submit" class="btn btn-primary btn-sm aksi">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="appModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Approval</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="<?= base_url('deklarasi/approve') ?>">
                    <div class="form-group">
                        <label for="app_hc_name">Nama</label>
                        <input type="text" class="form-control" name="app_hc_name" id="app_hc_name" aria-describedby="emailHelp">
                        <!-- HIDDEN INPUT -->
                        <input type="hidden" id="hidden_id" name="hidden_id" value="">
                    </div>
                    <div class="form-group">
                        <label for="app_hc_status">Approve</label>
                        <select id="app_hc_status" name="app_hc_status" class="form-control">
                            <option selected disabled>Choose...</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                            <option value="revised">Revised</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>

<script>
    $('#tgl_notifikasi').datepicker({
        dateFormat: 'dd-mm-yy',
        minDate: new Date(),
        maxDate: new Date(),

        // MENGENERATE KODE NOTIFIKASI SETELAH PILIH TANGGAL
        onSelect: function(dateText) {
            var id = dateText;
            $('#tgl_notifikasi').removeClass("is-invalid");

            // Menghapus label error secara manual jika ada
            if ($("#tgl_notifikasi-error").length) {
                $("#tgl_notifikasi-error").remove(); // Menghapus label error
            }
            $.ajax({
                url: "<?php echo site_url('datanotifikasi_pu/generate_kode') ?>",
                type: "POST",
                data: {
                    "date": dateText
                },
                dataType: "JSON",
                success: function(data) {
                    // console.log(data);
                    $('#kode_notifikasi').val(data.toUpperCase());
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
            $('#kode_notifikasi').val(kode).attr('readonly', true);
        } else {
            $('.aksi').text('Update');
            $("select option[value='']").hide();
            $.ajax({
                url: "<?php echo site_url('datanotifikasi_pu/edit_data') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    // console.log(data);
                    moment.locale('id');
                    $('#id').val(data['master'].id);
                    $('#kode_notifikasi').val(data['master'].kode_notifikasi.toUpperCase()).attr('readonly', true);
                    $('#nama').val(data['nama']);
                    $('#jabatan').val(data['master'].jabatan);
                    $('#departemen').val(data['master'].departemen);
                    $('#pengajuan').val(data['master'].pengajuan);
                    $('#tgl_notifikasi').val(moment(data['master'].tgl_notifikasi).format('DD-MM-YYYY')).attr('disabled', true); // Changed to 'DD-MM-YYYY'
                    $('#waktu').val(data['master'].waktu);
                    $('#alasan').val(data['master'].alasan);
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
            $('#jabatan').prop('disabled', true);
            $('#departemen').prop('readonly', true);
            $('#pengajuan').prop('disabled', true);
            $('#tanggal').prop('disabled', true);
            $('#waktu').prop('disabled', true);
            $('#alasan').prop('readonly', true);
            $('#status').prop('disabled', true);
            $('#catatan').prop('readonly', true);
        }

        $("#form").submit(function(e) {
            e.preventDefault();
            var $form = $(this);
            if (!$form.valid()) return false;
            var url = (id == 0) ? "<?php echo site_url('datanotifikasi_pu/add') ?>" : "<?php echo site_url('datanotifikasi_pu/update') ?>";

            $.ajax({
                url: url,
                type: "POST",
                data: $form.serialize(),
                dataType: "JSON",
                success: function(data) {
                    console.log(data);
                    if (data.status) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Your data has been saved',
                            showConfirmButton: false,
                            timer: 1500
                        }).then((result) => {
                            location.href = "<?= base_url('datanotifikasi_pu') ?>";
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
                pengajuan: {
                    required: true
                },
                tgl_notifikasi: {
                    required: true
                },
                waktu: {
                    required: true
                },
                alasan: {
                    required: true
                },
            },
            messages: {
                pengajuan: {
                    required: "Pengajuan is required"
                },
                tgl_notifikasi: {
                    required: "Tanggal is required"
                },
                waktu: {
                    required: "Waktu is required"
                },
                alasan: {
                    required: "Alasan is required"
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