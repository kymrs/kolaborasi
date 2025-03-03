<style>
    :root {
        --checked-color: #28a745;
        /* Warna hijau saat aktif */
        --unchecked-color: #ddd;
        /* Warna default */
    }

    .core-check,
    .approval-check {
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.3s ease;
        background-color: var(--unchecked-color);
        color: black;
        width: 45px;
        text-align: center;
        border-radius: 5px;
        position: relative;
    }

    /* Saat checkbox aktif */
    .core-check[data-checked="true"],
    .approval-check[data-checked="true"] {
        background-color: var(--checked-color);
        color: white;
        transform: scale(1.1);
    }

    /* Animasi ikon */
    .fa-icon {
        transition: opacity 0.2s ease, transform 0.3s ease;
        position: absolute;
    }

    .fa-minus {
        opacity: 0;
        transform: rotate(-90deg);
    }

    .core-check[data-checked="true"] .fa-plus,
    .approval-check[data-checked="true"] .fa-plus {
        opacity: 0;
        transform: rotate(90deg);
    }

    .core-check[data-checked="true"] .fa-minus,
    .approval-check[data-checked="true"] .fa-minus {
        opacity: 1;
        transform: rotate(0deg);
    }

    /* Animasi input text */
    .input-check,
    .input-check2 {
        transition: background-color 0.3s ease, transform 0.3s ease, border 0.3s ease;
        border: 2.5px solid var(--unchecked-color);
    }

    .input-check[data-checked="true"],
    .input-check2[data-checked="true"] {
        background-color: #e6f9e6;
        transform: scale(1.1);
        border: 2.5px solid var(--checked-color);
    }

    /* Efek hover pada opsi dropdown Select2 */
    .select2-container--bootstrap4 .select2-results__option--highlighted[aria-selected] {
        background-color: #007bff !important;
        /* Warna biru Bootstrap */
        color: white !important;
    }

    /* Efek hover pada opsi yang belum dipilih */
    .select2-container--bootstrap4 .select2-results__option:hover {
        background-color: #007bff !important;
        color: white !important;
        transition: background-color 0.2s;
    }

    .select2-container--bootstrap4 .select2-selection {
        border: 1px solid #ced4da !important;
        /* Warna border standar Bootstrap */
        border-radius: 0.25rem !important;
        /* Sesuai form-control Bootstrap */
        height: calc(2.25rem + 2px) !important;
        /* Tinggi standar */
        padding: 0.375rem 0.75rem !important;
    }

    .select2-container--bootstrap4 .select2-selection__rendered {
        line-height: 1.5 !important;
        padding-left: 0 !important;
    }

    .select2-container--bootstrap4 .select2-selection__arrow {
        height: 100% !important;
        right: 10px !important;
    }

    /* .approval-field {
        width: 75%;
        margin: auto;
        border: 2px solid #ccc; */
    /* Warna abu-abu */
    /* padding: 15px;
        border-radius: 5px; */
    /* Opsional: Membuat sudut border melengkung */
    /* background-color: #f9f9f9; */
    /* Opsional: Memberikan warna latar belakang */
    /* } */


    @media (max-width: 576px) {

        /* Untuk tampilan mobile */
        /* .form-group.row {
            display: flex;
            flex-direction: column;
        }

        .form-group.row label {
            margin-bottom: 5px;
        }

        .form-group.row .col-sm-10 {
            width: 100%;
        } */
    }

    @media (min-width: 576px) {

        /* Untuk tampilan desktop */
        /* .form-group.row {
            display: flex;
            align-items: center; */
        /* Sejajarkan label dan input */
        /* }

        .form-group.row label {
            text-align: right; */
        /* Agar label sejajar dengan input */
        /* } */
    }
</style>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $titleview ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <?php if ($add == 'Y') { ?>
                    <div class="card-header py-3">
                        <a class="btn btn-primary btn-sm" onclick="add_data()"><i class="fa fa-plus"></i>&nbsp;Add Menu</a>
                    </div>
                <?php } ?>
                <div class="card-body">
                    <table id="table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Action</th>
                                <th>Menu</th>
                                <th>Link</th>
                                <th>Icon</th>
                                <th>Order</th>
                                <th>Active</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Action</th>
                                <th>Menu</th>
                                <th>Link</th>
                                <th>Icon</th>
                                <th>Order</th>
                                <th>Active</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form">
                <div class="modal-header" style="background-color:#1E90FF;">
                    <h3 class="modal-title" style="color: white;">Add Menu</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" />
                    <div class="form-group row">
                        <label for="username" class="col-sm-2 col-form-label">Menu</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="menu" name="menu" placeholder="Menu">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fullname" class="col-sm-2 col-form-label">Link</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="link" name="link" placeholder="Link">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fullname" class="col-sm-2 col-form-label">Icon</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="icon" name="icon" placeholder="Icon">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fullname" class="col-sm-2 col-form-label">Order</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="urutan" name="urutan" placeholder="Order Number">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="aktif" class="col-sm-2 col-form-label">Active</label>
                        <div class="col-sm-10 form-inline row ml-1">
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

                    <!-- CHECKBOX CORE FIELD -->
                    <div class="input-group col-sm-7 mb-3">
                        <div class="input-group-prepend">
                            <div class="input-group-text core-check" data-checked="false">
                                <input type="checkbox" id="check-field" name="check-field" hidden>
                                <i class="fa-icon fas fa-plus"></i>
                                <i class="fa-icon fas fa-minus"></i>
                            </div>
                        </div>
                        <input type="text" class="form-control input-check" value="Core Field" readonly data-checked="false">
                    </div>

                    <div class="core-field">
                        <div class="form-group row">
                            <label for="fullname" class="col-sm-2 col-form-label">Logo</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="sub_image" name="sub_image" placeholder="Logo">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="fullname" class="col-sm-2 col-form-label">Warna</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="sub_color" name="sub_color" placeholder="Kode Warna">
                            </div>
                        </div>
                    </div>

                    <!-- CHECKBOX APPROVAL FIELD -->
                    <div class="input-group col-sm-7 mb-3">
                        <div class="input-group-prepend">
                            <div class="input-group-text approval-check" data-checked="false">
                                <input type="checkbox" id="check-field2" name="check-field2" hidden>
                                <i class="fa-icon fas fa-plus"></i>
                                <i class="fa-icon fas fa-minus"></i>
                            </div>
                        </div>
                        <input type="text" class="form-control input-check2" aria-label="Text input with checkbox" value="Approvals" readonly>
                    </div>

                    <div class="approval-field">
                        <!-- APPROVAL FIELD -->
                        <div class="form-group row">
                            <label class="col-sm-5 col-form-label" for="app_id">Approval Pertama</label>
                            <div class="col-sm-7">
                                <select class="form-control app_id" id="app_id" name="app_id">
                                    <option value="" selected disabled>Pilih opsi...</option>
                                    <?php foreach ($approvals as $approval) { ?>
                                        <option value="<?= $approval->id_user ?>"><?= $approval->fullname ?></option>
                                    <?php } ?>
                                    <option value="null">-</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-5 col-form-label" for="app2_id">Approval Kedua</label>
                            <div class="col-sm-7">
                                <select class="form-control app2_id" id="app2_id" name="app2_id">
                                    <option value="" selected disabled>Pilih opsi...</option>
                                    <?php foreach ($approvals as $approval) { ?>
                                        <option value="<?= $approval->id_user ?>"><?= $approval->fullname ?></option>
                                    <?php } ?>
                                    <option value="null">-</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-5 col-form-label" for="app3_id">Approval HC</label>
                            <div class="col-sm-7">
                                <select class="form-control app3_id" id="app3_id" name="app3_id">
                                    <option value="" selected disabled>Pilih opsi...</option>
                                    <?php foreach ($approvals as $approval) { ?>
                                        <option value="<?= $approval->id_user ?>"><?= $approval->fullname ?></option>
                                    <?php } ?>
                                    <option value="null">-</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-5 col-form-label" for="app4_id">Approval Captain</label>
                            <div class="col-sm-7">
                                <select class="form-control app4_id" id="app4_id" name="app4_id">
                                    <option value="" selected disabled>Pilih opsi...</option>
                                    <?php foreach ($approvals as $approval) { ?>
                                        <option value="<?= $approval->id_user ?>"><?= $approval->fullname ?></option>
                                    <?php } ?>
                                    <option value="null">-</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer text-right">
                        <button type="submit" class="btn btn-primary aksi">Save</button>
                    </div>
            </form>
        </div>
    </div>
</div>

<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>

<script type="text/javascript">
    var table;
    $(document).ready(function() {
        $(".input-check").attr("data-checked", false);
        $('.approval-field').hide();
        $('.core-field').hide();

        // CHECK CORE FIELD
        $(".core-check").click(function() {
            let checkbox = $("#check-field");
            let isChecked = checkbox.prop("checked");

            // Toggle checkbox state
            checkbox.prop("checked", !isChecked);
            $(this).attr("data-checked", !isChecked);
            $(".input-check").attr("data-checked", !isChecked);

            // Animasi tampil/sembunyi core-field dengan fade
            $(".core-field").fadeToggle(300);

            // Animasi tampil/sembunyi core-field
            if (!isChecked) {
                $(".core-field").show();
            } else {
                $(".core-field").hide();
                // $("#sub_image, #sub_color").val(''); // Reset input
            }
        });

        // CHECK APPROVAL FIELD
        $('.approval-check').on('click', function() {
            let checkbox = $("#check-field2");
            let isChecked = checkbox.prop("checked");

            // Toggle checkbox state
            checkbox.prop("checked", !isChecked);
            $(this).attr("data-checked", !isChecked);
            $(".input-check2").attr("data-checked", !isChecked);

            // Animasi tampil/sembunyi core-field dengan fade
            $(".approval-field").fadeToggle(300);

            if (!isChecked) {
                $('.approval-field').show();
            } else {
                $('.approval-field').hide();
                // $('#app_id').val('').trigger('change');
                // $('#app2_id').val('').trigger('change');
                // $('#app3_id').val('').trigger('change');
                // $('#app4_id').val('').trigger('change');
            }
        });

        $('#myModal').on('shown.bs.modal', function() {
            $('.name, .app_id, .app2_id, .app3_id, .app4_id').select2({
                dropdownParent: $('#myModal .modal-content'),
                theme: 'bootstrap4',
                width: '100%'
            });
        });

        table = $('#table').DataTable({
            "responsive": true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('menu/get_list') ?>",
                "type": "POST"
            },
            "columnDefs": [{
                "targets": [0, 1],
                "orderable": false,
            }, ],
        });

        $("#form").validate({
            rules: {
                menu: {
                    required: true,
                },
                // link: {
                //     required: true,
                // },
            },
            messages: {
                menu: {
                    required: "Menu is required",
                },
                // link: {
                //     required: "Link is required",
                // },
            },
        })

        $("#form").submit(function(e) {
            e.preventDefault();
            var $form = $(this);
            if (!$form.valid()) return false;
            var url;
            if (method == 'add') {
                url = "<?php echo site_url('menu/add') ?>";
            } else {
                url = "<?php echo site_url('menu/update') ?>";
            }

            $.ajax({
                url: url,
                type: "POST",
                data: $('#form').serialize(),
                dataType: "JSON",
                success: function(data) {
                    if (data.status) //if success close modal and reload ajax table
                    {
                        $('#myModal').modal('hide');
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Your data has been saved',
                            showConfirmButton: false,
                            timer: 1500
                        }).then((result) => {
                            location.reload();
                        })
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                }
            });
        });
    });

    function add_data() {
        method = 'add';
        $('#form')[0].reset();
        var validator = $("#form").validate();
        validator.resetForm();
        $('#myModal').modal('show');
        $('.card-title').text('Add Menu');
        $('.aksi').text('Save');
        //CORE
        $('.core-field').hide();
        $(".core-check").attr("data-checked", false);
        $(".input-check").attr("data-checked", false);
        $('#check-field').prop('checked', false)
        $('.core-field').hide();
        $('#sub_image').val('');
        $('#sub_color').val('');
        //APPROVAL
        $(".approval-check").attr("data-checked", false);
        $(".input-check2").attr("data-checked", false);
        $('#check-field2').prop('checked', false)
        $('.approval-field').hide();
        $('#app_id').val('').trigger('change');
        $('#app2_id').val('').trigger('change');
        $('#app3_id').val('').trigger('change');
        $('#app4_id').val('').trigger('change');

        $.ajax({
            url: "<?php echo site_url('menu/get_max') ?>",
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('[name="urutan"]').val(data).prop('readonly', true);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    };

    function edit_data(id) {
        method = 'update';
        $('#form')[0].reset();
        var validator = $("#form").validate();
        validator.resetForm();
        $('.form-control').removeClass('error');
        $('#myModal').modal('show');
        $('.card-title').text('Edit Data Menu');
        $('.aksi').text('Update');

        $.ajax({
            url: "<?php echo site_url('menu/get_id/') ?>" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                // console.log(data);
                $('[name="id"]').val(data['menu']['id_menu']);
                $('[name="menu"]').val(data['menu']['nama_menu']);
                $('[name="link"]').val(data['menu']['link']);
                $('[name="icon"]').val(data['menu']['icon']);
                $('[name="urutan"]').val(data['menu']['urutan']).prop('readonly', false);
                var elements = $('[name="aktif"]');
                for (i = 0; i < elements.length; i++) {
                    if (elements[i].value == data['menu']['is_active']) {
                        elements[i].checked = true;
                    }
                }

                if (data.menu.sub_image == null) {
                    $(".core-check").attr("data-checked", false);
                    $(".input-check").attr("data-checked", false);
                    $('#check-field').prop('checked', false)
                    $('.core-field').hide();
                    $('#sub_image').val('');
                    $('#sub_color').val('');
                } else {
                    $(".core-check").attr("data-checked", true);
                    $(".input-check").attr("data-checked", true);
                    $('#check-field').prop('checked', true)
                    $('.core-field').show();
                    $('#sub_image').val(data.menu.sub_image);
                    $('#sub_color').val(data.menu.sub_color);
                }

                if (data['approval'] == null || data['approval']['app_id'] == null) {
                    $(".approval-check").attr("data-checked", false);
                    $(".input-check2").attr("data-checked", false);
                    $('#check-field2').prop('checked', false)
                    $('.approval-field').hide();
                    $('#app_id').val('').trigger('change');
                    $('#app2_id').val('').trigger('change');
                    $('#app3_id').val('').trigger('change');
                    $('#app4_id').val('').trigger('change');
                } else {
                    $(".approval-check").attr("data-checked", true);
                    $(".input-check2").attr("data-checked", true);
                    $('#check-field2').prop('checked', true)
                    $('.approval-field').show();
                    $('#app_id').val(data['approval']['app_id']).trigger('change');
                    $('#app2_id').val(data['approval']['app2_id']).trigger('change');
                    $('#app3_id').val(data['approval']['app3_id']).trigger('change');
                    $('#app4_id').val(data['approval']['app4_id']).trigger('change');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    };

    function delete_data(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?php echo site_url('menu/delete/') ?>" + id,
                    type: "POST",
                    dataType: "JSON",
                    success: function(data) {
                        console.log(data);
                        if (data['status']) { // Pastikan respons dari server memiliki `status: true`
                            Swal.fire({
                                position: "top-end",
                                icon: "success",
                                title: "Data berhasil dihapus",
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                location.reload(true); // Force reload tanpa cache
                            });
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Gagal menghapus data",
                                text: data.message || "Terjadi kesalahan",
                            });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log("AJAX Error:", textStatus, errorThrown); // Untuk debugging
                        Swal.fire({
                            icon: "error",
                            title: "Error!",
                            text: "Gagal menghapus data. Coba lagi nanti.",
                        });
                    }
                });
            }
        })
    };
</script>