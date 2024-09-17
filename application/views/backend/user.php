<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $titleview ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <?php if ($add == 'Y') { ?>
                    <div class="card-header py-3">
                        <a class="btn btn-primary btn-sm" onclick="add_data()"><i class="fa fa-plus"></i>&nbsp;Add User</a>
                    </div>
                <?php } ?>
                <div class="card-body">
                    <div id="content"></div>
                    <table id="table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Action</th>
                                <th>Username</th>
                                <th>Fullname</th>
                                <th>Image</th>
                                <th>Level</th>
                                <th>Active</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Action</th>
                                <th>Username</th>
                                <th>Fullname</th>
                                <th>Image</th>
                                <th>Level</th>
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
                    <h3 class="modal-title" style="color: white;">Add User</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" />
                    <div class="form-group row">
                        <label for="username" class="col-sm-2 col-form-label">Username</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="username" name="username" placeholder="Username">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fullname" class="col-sm-2 col-form-label">Fullname</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Fullname">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fullname" class="col-sm-2 col-form-label">Password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                        </div>
                    </div>
                    <div class="form-group row" id="change">
                        <label for="fullname" class="col-sm-2 col-form-label"></label>
                        <div class="col-sm-10">
                            <input type="checkbox" name="new" id="new" value="1" class="filled-in chk-col-pink">
                            <label for="new">Change Password</label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="image" class="col-sm-2 col-form-label">Image</label>
                        <div class="col-sm-10">
                            <input type="file" class="" id="image" name="image">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="level" class="col-sm-2 col-form-label">Level</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="level" name="level">
                                <option value="">No Selected</option>
                            </select>
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
                "url": "<?php echo site_url('user/get_list') ?>",
                "type": "POST"
            },
            "columnDefs": [{
                "targets": [0, 1],
                "orderable": false,
            }, ],
        });

        $("#form").validate({
            rules: {
                username: {
                    required: true,
                },
                fullname: {
                    required: true,
                },
                password: {
                    required: true,
                },
                level: {
                    required: true,
                }
            },
            messages: {
                username: {
                    required: "Username is required",
                },
                fullname: {
                    required: "Fullname is required",
                },
                password: {
                    required: "Password is required",
                },
                level: {
                    required: "Level is required",
                }
            }
        })

        $("#form").submit(function(e) {
            e.preventDefault();
            var $form = $(this);
            if (!$form.valid()) return false;
            var url;
            if (method == 'add') {
                url = "<?php echo site_url('user/add') ?>";
            } else {
                url = "<?php echo site_url('user/update') ?>";
            }
            $.ajax({
                url: url,
                type: "post",
                data: new FormData(this),
                processData: false,
                contentType: false,
                cache: false,
                async: false,
                success: function(data) {
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
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                }
            });
        });

        $.ajax({
            url: "<?php echo site_url('user/get_level') ?>",
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                var html = '';
                var i;
                html += '<option value="">-- Choose Level --</option>';
                for (i = 0; i < data.length; i++) {
                    html += '<option value=' + data[i].id_level + '>' + data[i].nama_level + '</option>';
                }
                $('#level').html(html);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });

        $("#new").click(function() {
            var x = document.getElementById("new").checked;
            if (x == true) {
                $('[name="password"]').removeAttr('disabled');
            } else {
                $('[name="password"]').attr('disabled', true);
                $('[name="password"]').removeClass('error');
            }
        });
    });

    function add_data() {
        method = 'add';
        $('#form')[0].reset();
        $('.form-control').removeClass('error');
        var validator = $("#form").validate();
        validator.resetForm();
        $('#modal-default').modal('show');
        $('.modal-title').text('Add User');
        $('.aksi').text('Save');
        $('[name="password"]').removeAttr('disabled');
        $('#change').attr('hidden', true);
    };

    function edit_data(id) {
        method = 'update';
        $('#form')[0].reset();
        $('.form-control').removeClass('error');
        var validator = $("#form").validate();
        validator.resetForm();
        $('.form-control').removeClass('error');
        $('#modal-default').modal('show');
        $('.modal-title').text('Edit Data User');
        $('.aksi').text('Update');
        $('[name="password"]').attr('disabled', true);
        $('#change').removeAttr('hidden');
        $.ajax({
            url: "<?php echo site_url('user/get_id/') ?>" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('[name="id"]').val(data.id_user);
                $('[name="username"]').val(data.username);
                $('[name="fullname"]').val(data.fullname);
                $('[name="password"]').val("");
                $('[name="level"]').val(data.id_level);
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
                    url: "<?php echo site_url('user/delete/') ?>" + id,
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