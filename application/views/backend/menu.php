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

<div class="modal fade" id="modal-default">
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
                        <label for="aktif" class="col-sm-2 col-form-label">Core</label>
                        <div class="col-sm-10 form-inline row ml-1">
                            <div class="custom-control custom-radio col-sm-2">
                                <input class="custom-control-input" type="checkbox" id="coreCheckbox" name="aktif" value="Y" style="cursor: pointer;">
                                <label for="coreCheckbox" class="custom-control-label" style="cursor: pointer;">Yes</label>
                            </div>
                        </div>
                    </div>
                    <div id="core" style="display: none;">
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
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label" for="sub_name">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="sub_name" name="sub_name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label" for="app_id">Approval Pertama</label>
                        <div class="col-sm-10">
                            <select class="form-control app_id" id="app_id" name="app_id">
                                <option value="" selected disabled>Pilih opsi...</option>
                                <?php foreach ($approvals as $approval) { ?>
                                    <option value="<?= $approval->id_user ?>"><?= $approval->fullname ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label" for="app2_id">Approval Kedua</label>
                        <div class="col-sm-10">
                            <select class="form-control app2_id" id="app2_id" name="app2_id">
                                <option value="" selected disabled>Pilih opsi...</option>
                                <?php foreach ($approvals as $approval) { ?>
                                    <option value="<?= $approval->id_user ?>"><?= $approval->fullname ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label" for="app3_id">Approval HC</label>
                        <div class="col-sm-10">
                            <select class="form-control app3_id" id="app3_id" name="app3_id">
                                <option value="" selected disabled>Pilih opsi...</option>
                                <?php foreach ($approvals as $approval) { ?>
                                    <option value="<?= $approval->id_user ?>"><?= $approval->fullname ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label" for="app4_id">Approval Captain</label>
                        <div class="col-sm-10">
                            <select class="form-control app4_id" id="app4_id" name="app4_id">
                                <option value="" selected disabled>Pilih opsi...</option>
                                <?php foreach ($approvals as $approval) { ?>
                                    <option value="<?= $approval->id_user ?>"><?= $approval->fullname ?></option>
                                <?php } ?>
                            </select>
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
    $('#coreCheckbox').on('change', function() {
        if ($(this).is(':checked')) {
            $('#core').show(); // Menampilkan elemen dengan ID core
        } else {
            $('#core').hide(); // Menyembunyikan elemen dengan ID core
        }
    });


    var table;
    $(document).ready(function() {
        $('.name').select2();
        $('.app_id').select2({
            width: '100%'
        });
        $('.app2_id').select2({
            width: '100%'
        });
        $('.app3_id').select2({
            width: '100%'
        });
        $('.app4_id').select2({
            width: '100%'
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
                link: {
                    required: true,
                },
            },
            messages: {
                menu: {
                    required: "Menu is required",
                },
                link: {
                    required: "Link is required",
                },
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
                        $('#modal-default').modal('hide');
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
        $('#modal-default').modal('show');
        $('.card-title').text('Add Menu');
        $('.aksi').text('Save');
        $('#core').hide();

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
        $('#modal-default').modal('show');
        $('.card-title').text('Edit Data Menu');
        $('.aksi').text('Update');

        $.ajax({
            url: "<?php echo site_url('menu/get_id/') ?>" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('[name="id"]').val(data.id_menu);
                $('[name="menu"]').val(data.nama_menu);
                $('[name="link"]').val(data.link);
                $('[name="sub_image"]').val(data.sub_image);
                $('[name="sub_color"]').val(data.sub_color);
                $('[name="icon"]').val(data.icon);
                $('[name="urutan"]').val(data.urutan).prop('readonly', false);
                var elements = $('[name="aktif"]');
                for (i = 0; i < elements.length; i++) {
                    if (elements[i].value == data['menu']['is_active']) {
                        elements[i].checked = true;
                    }
                }
                $('#sub_name').val(data['approval']['sub_name']);
                $('#app_id').val(data['approval']['app_id']).trigger('change');
                $('#app2_id').val(data['approval']['app2_id']).trigger('change');
                $('#app3_id').val(data['approval']['app3_id']).trigger('change');
                $('#app4_id').val(data['approval']['app4_id']).trigger('change');
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
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Your data has been deleted',
                            showConfirmButton: false,
                            timer: 1500
                        }).then((result) => {
                            location.reload();
                        })
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error deleting data');
                    }
                });
            }
        })
    };
</script>