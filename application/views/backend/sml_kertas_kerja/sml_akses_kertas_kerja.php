<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $titleview ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <?php if (!empty($add) && $add == 'Y') { ?>
                    <div class="card-header py-3">
                        <a class="btn btn-primary btn-sm" onclick="add_data()"><i class="fa fa-plus"></i>&nbsp;Add Akses</a>
                    </div>
                <?php } ?>
                <div class="card-body">
                    <!-- <div class="alert alert-info">
                        Mapping ini menentukan user boleh mengisi tahap: <b>marketing / plotting / monitoring / finance</b>.
                        Username disarankan <b>lowercase</b>.
                    </div> -->

                    <table id="table" class="table table-bordered table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Action</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Active</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data Load -->
                        </tbody>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Action</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Active</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="form">
                <div class="modal-header" style="background-color:#1E90FF;">
                    <h5 class="modal-title" style="color:#fff;">Mapping Akses Kertas Kerja</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" />

                    <div class="form-group">
                        <label>Username</label>
                        <select class="form-control" id="username" name="username">
                            <option value="">- pilih -</option>
                            <?php foreach ((array) ($usernames ?? array()) as $u) {
                                $uname = is_object($u) ? ($u->username ?? '') : (is_array($u) ? ($u['username'] ?? '') : '');
                                $uname = strtolower(trim((string) $uname));
                                if ($uname === '') continue;
                            ?>
                                <option value="<?= htmlspecialchars($uname) ?>"><?= htmlspecialchars($uname) ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Role</label>
                        <select class="form-control" name="role">
                            <option value="">- pilih -</option>
                            <option value="marketing">Marketing</option>
                            <option value="plotting">Plotting</option>
                            <option value="monitoring">Monitoring</option>
                            <option value="finance">Finance</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Active</label>
                        <div class="form-inline">
                            <div class="custom-control custom-radio mr-3">
                                <input class="custom-control-input" type="radio" id="activeY" name="is_active" value="Y">
                                <label class="custom-control-label" for="activeY">Yes</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input class="custom-control-input" type="radio" id="activeN" name="is_active" value="N" checked>
                                <label class="custom-control-label" for="activeN">No</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btnSave">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>

<script>
    var save_method;
    var table;

    function initUsernameSelect() {
        var $el = $('#username');
        if (!$el.length) return;
        if (!$.fn.select2) return;

        try {
            if ($el.data('select2')) {
                $el.select2('destroy');
            }
        } catch (e) {
            // ignore
        }

        $el.select2({
            width: '100%',
            placeholder: '- pilih -',
            allowClear: true,
            dropdownParent: $('#myModal')
        });
    }

    $(document).ready(function() {
        table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            order: [],
            ajax: {
                url: "<?= site_url('sml_akses_kertas_kerja/akses_get_list') ?>",
                type: "POST"
            },
            columnDefs: [{
                targets: [0, 1],
                orderable: false
            }]
        });

        $('#form').on('submit', function(e) {
            e.preventDefault();

            var url = (save_method === 'add') ?
                "<?= site_url('sml_akses_kertas_kerja/akses_add') ?>" :
                "<?= site_url('sml_akses_kertas_kerja/akses_update') ?>";

            $('#btnSave').text('saving...').attr('disabled', true);

            $.ajax({
                url: url,
                type: "POST",
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "JSON",
                success: function(data) {
                    if (data.status) {
                        // Show success after modal fully closed (avoid Swal behind modal/backdrop)
                        $('#myModal').one('hidden.bs.modal', function() {
                            if (window.Swal && typeof Swal.fire === 'function') {
                                Swal.fire('Berhasil!', 'Data berhasil disimpan.', 'success');
                            } else {
                                alert('Berhasil! Data berhasil disimpan.');
                            }
                        });

                        $('#myModal').modal('hide');
                        table.ajax.reload(null, false);
                    } else {
                        Swal.fire('Gagal!', (data && data.message) ? data.message : 'Gagal menyimpan', 'error');
                    }
                    $('#btnSave').text('Save').attr('disabled', false);
                },
                error: function() {
                    Swal.fire('Error!', 'Error saving data', 'error');
                    $('#btnSave').text('Save').attr('disabled', false);
                }
            });
        });

        // Init select2 once; re-init when modal shown to ensure dropdownParent works.
        initUsernameSelect();
        $('#myModal').on('shown.bs.modal', function() {
            initUsernameSelect();
        });
    });

    function reload_table() {
        table.ajax.reload(null, false);
    }

    function add_data() {
        save_method = 'add';
        $('#form')[0].reset();
        $('[name="id"]').val('');
        $('#activeN').prop('checked', true);
        if ($('#username').length) {
            $('#username').val('').trigger('change');
        }
        $('#myModal').modal('show');
    }

    function edit_data(id) {
        save_method = 'update';
        $('#form')[0].reset();

        $.ajax({
            url: "<?= site_url('sml_akses_kertas_kerja/akses_get_id') ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                if (!data) {
                    Swal.fire('Gagal!', 'Data tidak ditemukan', 'error');
                    return;
                }
                $('[name="id"]').val(data.id);
                if ($('#username').length) {
                    $('#username').val(String(data.username || '')).trigger('change');
                } else {
                    $('[name="username"]').val(data.username);
                }
                $('[name="role"]').val(data.role);
                if (String(data.is_active) === 'Y') {
                    $('#activeY').prop('checked', true);
                } else {
                    $('#activeN').prop('checked', true);
                }
                $('#myModal').modal('show');
            },
            error: function() {
                Swal.fire('Error!', 'Error get data', 'error');
            }
        });
    }

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
                    url: "<?= site_url('sml_akses_kertas_kerja/akses_delete') ?>/" + id,
                    type: "POST",
                    dataType: "JSON",
                    success: function(data) {
                        reload_table();
                        Swal.fire('Berhasil!', 'Data berhasil dihapus.', 'success');
                    },
                    error: function() {
                        Swal.fire('Error!', 'Error deleting data', 'error');
                    }
                });
            }
        })
    }
</script>
