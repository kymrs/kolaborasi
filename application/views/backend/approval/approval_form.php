<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title_view ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header text-right">
                    <a class="btn btn-secondary btn-sm" href="<?= base_url('approval') ?>">
                        <i class="fas fa-chevron-left"></i>&nbsp;Back
                    </a>
                </div>
                <div class="card-body">
                    <form id="form">
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Second Set of Fields -->
                                <div class="form-group row">
                                    <label class="col-sm-4" for="name">Nama</label>
                                    <div class="col-sm-7">
                                        <select class="form-control name" name="name" id="name">
                                            <option value="" selected disabled>Pilih opsi...</option>
                                            <?php foreach ($users as $user) { ?>
                                                <option value="<?= $user->id_user ?>"><?= $user->fullname ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4" for="divisi">Divisi</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" id="divisi" name="divisi">
                                            <option value="" selected disabled>Pilih opsi...</option>
                                            <option value="Operational">OPERATIONAL</option>
                                            <option value="Finance">FINANCE</option>
                                            <option value="HC & GA">HC & GA</option>
                                            <option value="IT">IT</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4" for="jabatan">Jabatan</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="jabatan" name="jabatan">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-4" for="app_id">Approval Pertama</label>
                                    <div class="col-sm-7">
                                        <select class="form-control app_id" id="app_id" name="app_id">
                                            <option value="" selected disabled>Pilih opsi...</option>
                                            <?php foreach ($approvals as $approval) { ?>
                                                <option value="<?= $approval->id_user ?>"><?= $approval->fullname ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4" for="app2_id">Approval Kedua</label>
                                    <div class="col-sm-7">
                                        <select class="form-control app2_id" id="app2_id" name="app2_id">
                                            <option value="" selected disabled>Pilih opsi...</option>
                                            <?php foreach ($approvals as $approval) { ?>
                                                <option value="<?= $approval->id_user ?>"><?= $approval->fullname ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4" for="app3_id">Approval HC</label>
                                    <div class="col-sm-7">
                                        <select class="form-control app3_id" id="app3_id" name="app3_id">
                                            <option value="" selected disabled>Pilih opsi...</option>
                                            <?php foreach ($approvals as $approval) { ?>
                                                <option value="<?= $approval->id_user ?>"><?= $approval->fullname ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4" for="app4_id">Approval Captain</label>
                                    <div class="col-sm-7">
                                        <select class="form-control app4_id" id="app4_id" name="app4_id">
                                            <option value="" selected disabled>Pilih opsi...</option>
                                            <?php foreach ($approvals as $approval) { ?>
                                                <option value="<?= $approval->id_user ?>"><?= $approval->fullname ?></option>
                                            <?php } ?>
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        var id = $('#id').val();
        var aksi = $('#aksi').val();
        var kode = $('#kode').val();

        $('.name').select2();
        $('.app_id').select2();
        $('.app2_id').select2();
        $('.app3_id').select2();
        $('.app4_id').select2();

        if (id == 0) {
            $('.aksi').text('Save');
        } else {
            $('.aksi').text('Update');
            $("select option[value='']").hide();
            $('#name').prop('disabled', true);
            $.ajax({
                url: "<?php echo site_url('approval/edit_data') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    // console.log(data);
                    $('#name').val(data['master']['id_user']).trigger('change');
                    $('#divisi').val(data['master']['divisi']).trigger('change');
                    $('#jabatan').val(data['master']['jabatan']).trigger('change');
                    $('#app_id').val(data['master']['app_id']).trigger('change');
                    $('#app2_id').val(data['master']['app2_id']).trigger('change');
                    $('#app3_id').val(data['master']['app3_id']).trigger('change');
                    $('#app4_id').val(data['master']['app4_id']).trigger('change');
                    // console.log(data);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error getting data from ajax');
                }
            });
        }

        $("#form").submit(function(e) {
            e.preventDefault();
            var $form = $(this);
            if (!$form.valid()) return false;
            var url = (id == 0) ? "<?php echo site_url('approval/add') ?>" : "<?php echo site_url('approval/update') ?>/" + id;
            var selectedText = $('#name option:selected').text();
            // console.log(selectedText);

            var formData = $form.serialize() + "&selectedText=" + encodeURIComponent(selectedText);

            $.ajax({
                url: url,
                type: "POST",
                data: formData,
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
                            location.href = "<?= base_url('approval') ?>";
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.fire({
                        position: 'center',
                        icon: 'warning',
                        title: 'Data approval sudah ada',
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
            });
        });


        $("#form").validate({
            rules: {
                name: {
                    required: true,
                },
                divisi: {
                    required: true,
                },
                jabatan: {
                    required: true,
                },
                app_id: {
                    required: true,
                },
                app2_id: {
                    required: true,
                },
                app3_id: {
                    required: true,
                }
            },
            messages: {
                name: {
                    required: "Nama is required",
                },
                divisi: {
                    required: "Divisi is required",
                },

                jabatan: {
                    required: "Jabatan is required",
                },
                app_id: {
                    required: "Approval Pertama is required",
                },
                app2_id: {
                    required: "Approval Kedua is required",
                },
                app3_id: {
                    required: "Approval Ketiga is required",
                }
            },
            errorPlacement: function(error, element) {
                // Cek jika elemen adalah Select2
                if (element.hasClass('select2-hidden-accessible')) {
                    // Jika elemen adalah Select2, masukkan error di bawah elemen Select2
                    error.insertAfter(element.next('.select2')); // Masukkan error setelah elemen Select2
                } else if (element.parent().hasClass('input-group')) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function(element) {
                $(element).addClass('is-invalid'); // Tambahkan kelas untuk menandai input tidak valid
                if ($(element).hasClass('select2-hidden-accessible')) {
                    $(element).next('.select2').find('.select2-selection').addClass('is-invalid'); // Tandai Select2
                }
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid'); // Hapus kelas jika input valid
                if ($(element).hasClass('select2-hidden-accessible')) {
                    $(element).next('.select2').find('.select2-selection').removeClass('is-invalid'); // Hapus kelas dari Select2
                }
            },
            focusInvalid: false, // Disable auto-focus on the first invalid field
        });

    })
</script>