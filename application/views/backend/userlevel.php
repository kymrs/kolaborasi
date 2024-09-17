<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $titleview ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <?php if ($add == 'Y') { ?>
                    <div class="card-header py-3">
                        <a class="btn btn-primary btn-sm" onclick="add_data()"><i class="fa fa-plus"></i>&nbsp;Add User Level</a>
                    </div>
                <?php } ?>
                <div class="card-body">
                    <div id="content"></div>
                    <table id="table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Action</th>
                                <th>Level</th>
                                <th>Akses</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Action</th>
                                <th>Level</th>
                                <th>Akses</th>
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
                    <h3 class="modal-title" style="color: white;">Add Level</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" />
                    <div class="form-group row">
                        <label for="username" class="col-sm-2 col-form-label">Level</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="level" name="level" placeholder="Level">
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

<!-- modal hak akses -->
<div class="modal fade" id="modal-akses">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title ">View Level</h4>
                <button type="button" class="close" onclick="btn_x()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center" id="md_def">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="btn_x()">Back</button>
            </div>
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
                "url": "<?php echo site_url('userlevel/get_list') ?>",
                "type": "POST"
            },
            "columnDefs": [{
                "targets": [0, 1],
                "orderable": false,
            }, ],
        });

        $("#form").validate({
            rules: {
                level: {
                    required: true,
                },
            },
            messages: {
                level: {
                    required: "Level is required",
                },
            },
        })

        $("#form").submit(function(e) {
            e.preventDefault();
            var $form = $(this);
            if (!$form.valid()) return false;
            var url;
            if (method == 'add') {
                url = "<?php echo site_url('userlevel/add') ?>";
            } else {
                url = "<?php echo site_url('userlevel/update') ?>";
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
        $('.modal-title').text('Add Level');
        $('.aksi').text('Save');
        $(".modal-dialog").removeClass('modal-xl');
    };

    function edit_data(id) {
        method = 'update';
        $('#form')[0].reset();
        var validator = $("#form").validate();
        validator.resetForm();
        $('.form-control').removeClass('error');
        $('#modal-default').modal('show');
        $('.modal-title').text('Edit Data Level');
        $('.aksi').text('Update');
        $(".modal-dialog").removeClass('modal-xl');

        $.ajax({
            url: "<?php echo site_url('userlevel/get_id/') ?>" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('[name="id"]').val(data.id_level);
                $('[name="level"]').val(data.nama_level);
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
                    url: "<?php echo site_url('userlevel/delete/') ?>" + id,
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

    //tambah script hak akses
    function aksesmenu(id) {
        $('.modal-title').text('Hak Akses Menu');
        $("#modal-akses").modal({
            backdrop: 'static',
            keyboard: false
        });
        $(".modal-dialog").addClass('modal-xl');
        $.ajax({
            url: '<?= base_url('userlevel/update_tbl_akses_menu'); ?>',
            type: 'post',
            data: 'id=' + id,
            success: function(data) {
                $.ajax({
                    url: '<?= base_url('userlevel/view_akses_menu'); ?>',
                    type: 'post',
                    data: 'id=' + id,
                    success: function(respon) {
                        $("#md_def").html(respon);
                    }
                })
            }
        })
    }

    function btn_x() {
        $('#modal-akses').modal('hide');
        location.reload()
    }
</script>