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
                    <a class="btn btn-secondary btn-sm" href="<?= base_url('approval_sw') ?>">
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
                                            <?php foreach ($approvals as $approval) { ?>
                                                <option value="<?= $approval->id_user ?>"><?= $approval->fullname ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4" for="divisi">Divisi</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" id="divisi" name="divisi">
                                            <option value="" selected disabled>Pilih opsi...</option>
                                            <option value="Marketing">MARKETING</option>
                                            <option value="Service Area">SERVICE AREA</option>
                                            <option value="Corporate Communication">CORPORATE COMMUNICATION</option>
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

        if (id == 0) {
            $('.aksi').text('Save');
        } else {
            $('.aksi').text('Update');
            $("select option[value='']").hide();
            $.ajax({
                url: "<?php echo site_url('approval_sw/edit_data') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    // console.log(data);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error getting data from ajax');
                }
            });
        }

        $('#name').val(11).trigger('change');

        $("#form").submit(function(e) {
            e.preventDefault();
            var $form = $(this);
            if (!$form.valid()) return false;
            var url = (id == 0) ? "<?php echo site_url('approval_sw/add') ?>" : "<?php echo site_url('approval_sw/update') ?>";
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
                            location.href = "<?= base_url('approval_sw') ?>";
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error adding / updating data');
                }
            });
        });

    })
</script>