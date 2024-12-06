<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title_view ?></h1>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header text-right">
                    <a class="btn btn-secondary btn-sm" href="<?= base_url('user') ?>"><i class="fas fa-chevron-left"></i>&nbsp;Back</a>
                </div>
                <div class="card-body">
                    <form id="form" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">

                                <!-- FIELD USER -->
                                <!-- First Set of Fields -->
                                <input type="hidden" id="hidden_id" name="hidden_id" />
                                <div class="form-group row">
                                    <label for="username" class="col-sm-4 col-form-label">Username</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="username" name="username" placeholder="Username">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="fullname" class="col-sm-4 col-form-label">Fullname</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Fullname">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="fullname" class="col-sm-4 col-form-label">Password</label>
                                    <div class="col-sm-8">
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="core" class="col-sm-4 col-form-label">Core</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" id="core" name="core">
                                            <option value="" selected disabled>No Selected</option>
                                            <option value="all">All</option>
                                            <option value="kps">KPS</option>
                                            <option value="pu">Pengenumroh</option>
                                            <option value="sw">Sebelaswarna</option>
                                            <option value="pw">Sobat Wisata</option>
                                            <option value="bmn">By.Momemnt</option>
                                            <option value="qbg">Qubagift</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="image" class="col-sm-4 col-form-label">Image</label>
                                    <div class="col-sm-8">
                                        <input type="file" class="" id="image" name="image">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="level" class="col-sm-4 col-form-label">Level</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" id="level" name="level">
                                            <option value="" selected disabled>No Selected</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="aktif" class="col-sm-4 col-form-label">Active</label>
                                    <div class="col-sm-8 form-inline row ml-1">
                                        <div class="custom-control custom-radio col-sm-2">
                                            <input class="custom-control-input" type="radio" id="customRadio1" name="aktif" value="Y">
                                            <label for="customRadio1" class="custom-control-label">Yes</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" id="customRadio2" name="aktif" value="N" checked>
                                            <label for="customRadio2" class="custom-control-label">No</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="no_rek" class="col-sm-4 col-form-label">No Rek</label>
                                    <div class="col-sm-8">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="inputGroup-sizing-default">BCA</span>
                                            <input type="number" name="no_rek" id="no_rek" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- FIELD APPROVAL -->
                            <div style="width: 100%; margin: 15px 20px 30px; height: 0.9px; background-color: rgba(0,0,0,0.2); text-align: center"></div>
                            <div class="col-md-6">
                                <!-- First Set of Fields -->
                                <div class="form-group row">
                                    <input type="hidden" id="id_user" name="id_user" value="">

                                    <label class="col-sm-4" for="divisi">Divisi</label>
                                    <div class="col-sm-8">
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
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="jabatan" name="jabatan">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4" for="app_id">Approval Pertama</label>
                                    <div class="col-sm-8">
                                        <select class="form-control app_id" id="app_id" name="app_id">
                                            <option value="" selected disabled>Pilih opsi...</option>
                                            <?php foreach ($approvals as $approval) { ?>
                                                <option value="<?= $approval->id_user ?>"><?= $approval->fullname ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-4" for="app2_id">Approval Kedua</label>
                                    <div class="col-sm-8">
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
                                    <div class="col-sm-8">
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
                                    <div class="col-sm-8">
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
                url: "<?php echo site_url('user/edit_data') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    // console.log(data);
                    moment.locale('id');
                    $('#hidden_id').val(data.user.id_user);
                    $('#password').prop('readonly', true);
                    $('#core').val(data.user.core);
                    $('#username').val(data.user.username);
                    $('#fullname').val(data.user.fullname);
                    $('#password').val(data.user.password);
                    $('#level').val(data.user.id_level);
                    $('#aktif').val(data.user.is_active);
                    $('#no_rek').val(data.user.no_rek);
                    $('#divisi').val(data.approval.divisi);
                    $('#jabatan').val(data.approval.jabatan);
                    $('#app_id').val(data.approval.app_id);
                    $('#app2_id').val(data.approval.app2_id);
                    $('#app3_id').val(data.approval.app3_id);
                    $('#app4_id').val(data.approval.app4_id);
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
            var url = (id == 0) ? "<?php echo site_url('user/add') ?>" : "<?php echo site_url('user/update') ?>";

            $.ajax({
                url: url,
                type: "POST",
                data: new FormData(this),
                contentType: false,
                processData: false,
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
                            location.href = "<?= base_url('user') ?>";
                        });
                    } else {
                        if (data.error === "user") {
                            Swal.fire({
                                position: 'center',
                                icon: 'error',
                                title: 'Failed to save user!',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        } else if (data.error === "approval") {
                            Swal.fire({
                                position: 'center',
                                icon: 'error',
                                title: 'Failed to save approval!',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        } else if (data.error === "size") {
                            Swal.fire({
                                position: 'center',
                                icon: 'error',
                                title: 'File upload size is more than 3MB!',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        } else if (data.error === "type") {
                            Swal.fire({
                                position: 'center',
                                icon: 'error',
                                title: 'Format files is not supported!',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error adding / updating data');
                    console.error("Error Response:", jqXHR.responseText);
                    console.error("Text Status:", textStatus);
                    console.error("Error Thrown:", errorThrown);
                }
            });
        });

        $("#form").validate({
            rules: {
                username: {
                    required: true
                },
                fullname: {
                    required: true
                },
                password: {
                    required: true
                },
                core: {
                    required: true
                },
                level: {
                    required: true
                },
                aktif: {
                    required: true
                },
                name: {
                    required: true
                },
                divisi: {
                    required: true
                },
                jabatan: {
                    required: true
                },
                app_id: {
                    required: true
                },
                app2_id: {
                    required: true
                },
                no_rek: { // Tambahkan aturan untuk no_rek
                    required: true,
                    minlength: 10,
                    digits: true // Memastikan input hanya angka
                }
            },
            messages: {
                username: {
                    required: "Username is required"
                },
                fullname: {
                    required: "Fullname is required"
                },
                password: {
                    required: "Password is required"
                },
                level: {
                    required: "User Level is required"
                },
                aktif: {
                    required: "Active is required"
                },
                divisi: {
                    required: "Divisi is required"
                },
                jabatan: {
                    required: "Jabatan is required"
                },
                app_id: {
                    required: "Approval 1 is required"
                },
                app2_id: {
                    required: "Approval 2 is required"
                },
                no_rek: { // Tambahkan pesan untuk no_rek
                    required: "Nomor rekening harus diisi",
                    minlength: "Nomor rekening harus minimal 10 digit",
                    digits: "Nomor rekening hanya boleh berisi angka"
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

    });
</script>