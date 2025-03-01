<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $titleview ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <?php if ($add == 'Y') { ?>
                    <div class="card-header py-3">
                        <a class="btn btn-primary btn-sm" onclick="add_data()"><i class="fa fa-plus"></i>&nbsp;Add Sub Menu</a>
                    </div>
                <?php } ?>
                <div class="card-body">
                    <table id="table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Action</th>
                                <th>Submenu</th>
                                <th>Link</th>
                                <th>Icon</th>
                                <th>Menu</th>
                                <th>Active</th>
                                <th>Urutan</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Action</th>
                                <th>Submenu</th>
                                <th>Link</th>
                                <th>Icon</th>
                                <th>Menu</th>
                                <th>Active</th>
                                <th>Urutan</th>
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
                    <h3 class="modal-title" style="color: white;">Add Submenu</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" />
                    <div class="form-group row">
                        <label for="menu" class="col-sm-2 col-form-label">Menu</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="menu" name="menu">
                                <option value="">No Selected</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="submenu" class="col-sm-2 col-form-label">Submenu</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="submenu" name="submenu" placeholder="Submenu">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="link" class="col-sm-2 col-form-label">Link</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="link" name="link" placeholder="Link">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="nama_tbl" class="col-sm-2 col-form-label">Table</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama_tbl" name="nama_tbl" placeholder="Table">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="icon" class="col-sm-2 col-form-label">Icon</label>
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
        table = $('#table').DataTable({
            "responsive": true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('submenu/get_list') ?>",
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
                submenu: {
                    required: true,
                },
            },
            messages: {
                menu: {
                    required: "Menu is required",
                },
                submenu: {
                    required: "Submenu is required",
                },
            },
        });

        $("#form").submit(function(e) {
            e.preventDefault();
            var $form = $(this);
            if (!$form.valid()) return false;
            var url;
            if (method == 'add') {
                url = "<?php echo site_url('submenu/add') ?>";
            } else {
                url = "<?php echo site_url('submenu/update') ?>";
            }

            $.ajax({
                url: url,
                type: "POST",
                data: $('#form').serialize(),
                dataType: "JSON",
                success: function(data) {
                    if (data.status) {
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

        $.ajax({
            url: "<?php echo site_url('submenu/get_menu') ?>",
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                var html = '';
                var i;
                html += '<option value="">-- Choose --</option>';
                for (i = 0; i < data.length; i++) {
                    html += '<option value=' + data[i].id_menu + '>' + data[i].nama_menu + '</option>';
                }
                $('#menu').html(html);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    });

    function add_data() {
        method = 'add';
        $('#form')[0].reset();
        var validator = $("#form").validate();
        validator.resetForm();
        $('#modal-default').modal('show');
        $('.modal-title').text('Add Sub Menu');
        $('.aksi').text('Save');

        $.ajax({
            url: "<?php echo site_url('submenu/get_max') ?>",
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
        $('.modal-title').text('Edit Data Submenu');
        $('.aksi').text('Update');

        $.ajax({
            url: "<?php echo site_url('submenu/get_id/') ?>" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('[name="id"]').val(data.id_submenu);
                $('[name="menu"]').val(data.id_menu);
                $('[name="submenu"]').val(data.nama_submenu);
                $('[name="link"]').val(data.link);
                $('[name="nama_tbl"]').val(data.nama_tbl);
                $('[name="icon"]').val(data.icon);
                $('[name="urutan"]').val(data.urutan).prop('readonly', false);
                var elements = $('[name="aktif"]');
                for (i = 0; i < elements.length; i++) {
                    if (elements[i].value == data.is_active) {
                        elements[i].checked = true;
                    }
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
                    url: "<?php echo site_url('submenu/delete/') ?>" + id,
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