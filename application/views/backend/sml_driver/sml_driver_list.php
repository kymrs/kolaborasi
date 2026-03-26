<style>
    .rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: center;
        font-size: 2em;
    }

    .rating input {
        display: none;
    }

    .rating label {
        color: #ccc;
        cursor: pointer;
        transition: color 0.3s;
        position: relative;
        right: 107px;
        bottom: 5px;
    }

    .rating input:checked~label,
    .rating label:hover,
    .rating label:hover~label {
        color: #f5b301;
    }
</style>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $titleview ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <a class="btn btn-primary btn-sm" id="add_btn" data-toggle="modal" data-target="#modal-default" onclick="add_data()">
                        <i class="fa fa-plus"></i>&nbsp;Add Data
                    </a>
                </div>
                <div class="card-body p-4">
                    <!-- Added padding for spacing -->
                    <div class="table-responsive">
                        <!-- Table wrapper -->
                        <table id="driver-table" class="table table-bordered table-striped display nowrap w-100 mb-4">
                            <!-- Added margin-bottom -->
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th style="width: 120px;">Action</th>
                                    <th>Nama Driver 1</th>
                                    <th>No HP Driver 1</th>
                                    <th>Nama Driver 2</th>
                                    <th>No HP Driver 2</th>
                                    <th>Nopol</th>
                                    <th>Tipe Unit</th>
                                    <th>Tanggal STNK</th>
                                    <th>Tanggal KEUR</th>
                                    <th>Tanggal Dibuat</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be populated by DataTables -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th style="width: 120px;">Action</th>
                                    <th>Nama Driver 1</th>
                                    <th>No HP Driver 1</th>
                                    <th>Nama Driver 2</th>
                                    <th>No HP Driver 2</th>
                                    <th>Nopol</th>
                                    <th>Tipe Unit</th>
                                    <th>Tanggal STNK</th>
                                    <th>Tanggal KEUR</th>
                                    <th>Tanggal Dibuat</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="modalform">
                    <div class="modal-header bg-primary text-gray-100">
                        <h5 class="card-title" style="margin: 0;">Tambah Data Driver</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -23px;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <!-- /.card-header -->
                    <div class="modal-body">
                        <input type="hidden" name="id" />
                        <div class="form-group row">
                            <label for="" class="col-sm-4 col-form-label">Nama Driver 1</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="nama_driver" name="nama_driver" placeholder="Nama Driver 1" required>
                                <input type="hidden" class="form-control" id="id" name="id" placeholder="id">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-4 col-form-label">No HP Driver 1</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="no_hp" name="no_hp" placeholder="No HP Driver 1" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-4 col-form-label">Nama Driver 2</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="nama_driver2" name="nama_driver2" placeholder="Nama Driver 2" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-4 col-form-label">No HP Driver 2</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="no_hp2" name="no_hp2" placeholder="No HP Driver 2" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-4 col-form-label">Nopol</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="nopol" name="nopol" placeholder="Nopol" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-4 col-form-label">Tipe Unit</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="tipe_unit" name="tipe_unit" placeholder="Tipe Unit" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-4 col-form-label">Tanggal STNK</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control kk-datepicker" id="tgl_stnk" name="tgl_stnk" placeholder="yyyy-mm-dd" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-4 col-form-label">Tanggal KEUR</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control kk-datepicker" id="tgl_keur" name="tgl_keur" placeholder="yyyy-mm-dd" autocomplete="off">
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

</div>

<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>

<script type="text/javascript">
    var table;
    $(document).ready(function() {
        // Datepicker untuk input tanggal (STNK/KEUR)
        function initDriverDatepicker() {
            if ($.fn.datepicker) {
                $('#modal-default .kk-datepicker').datepicker({
                    dateFormat: 'yy-mm-dd',
                    changeMonth: true,
                    changeYear: true
                });
            }
        }

        initDriverDatepicker();
        $('#modal-default').on('shown.bs.modal', function() {
            initDriverDatepicker();
        });

        table = $('#driver-table').DataTable({
            "responsive": true,
            "scrollX": true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('sml_driver/get_list') ?>",
                "type": "POST",
            },
            "language": {
                "infoFiltered": ""
            },
            "columnDefs": [{
                    "targets": [], // Adjusted indices to match the number of columns
                    "className": 'dt-head-nowrap'
                },
                {
                    "targets": [1],
                    "className": 'dt-body-nowrap'
                },
                {
                    "targets": [0, 1], // Indices for non-orderable columns
                    "orderable": false,
                }
            ],
        });
    });

    
    $("#modalform").submit(function(e) {
        e.preventDefault();
        var url;
        var $form = $(this);
        if (!$form.valid()) return false;
        if (method == 'add') {
            url = "<?php echo site_url('sml_driver/add') ?>";
        } else {
            url = "<?php echo site_url('sml_driver/update') ?>";
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
                    position: 'center',
                    icon: 'success',
                    title: 'Your data has been saved',
                    showConfirmButton: false,
                    timer: 1500
                });
                reload_table();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
            }
        });
    });

    function reload_table() {
        table.ajax.reload(null, false);
    };

    function add_data() {
        method = 'add';
        $('#modalform')[0].reset();
        var validator = $("#modalform").validate();
        validator.resetForm();
    };

    // Mengambil URL saat ini
    const params = new URLSearchParams(window.location.search);

    // Mengambil parameter tertentu
    const action = params.get('action'); // "John"

    if (action == 'add') {
        $('#add_btn').click();
    }

    function edit_data(id) {
        method = 'update';
        $('#modalform')[0].reset();
        var validator = $("#modalform").validate();
        validator.resetForm();
        $('.form-control').removeClass('error');
        $('#modal-default').modal('show');
        $('.card-title').text('Edit Data Driver');
        $('.aksi').text('Update');
        $.ajax({
            url: "<?php echo site_url('sml_driver/get_id/') ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('[name="id"]').val(data.id);
                $('[name="nama_driver"]').val(data.nama_driver);
                $('[name="no_hp"]').val(data.no_hp);
                $('[name="nama_driver2"]').val(data.nama_driver2);
                $('[name="no_hp2"]').val(data.no_hp2);
                $('[name="nopol"]').val(data.nopol);
                $('[name="tipe_unit"]').val(data.tipe_unit);
                $('[name="tgl_stnk"]').val(data.tgl_stnk);
                $('[name="tgl_keur"]').val(data.tgl_keur);
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
                    url: "<?php echo site_url('sml_driver/delete') ?>/" + id,
                    type: "POST",
                    dataType: "JSON",
                    success: function(data) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Your data has been deleted',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        reload_table();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error deleting data');
                    }
                });
            }
        })
    };
</script>