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
                        <table id="hotel-table" class="table table-bordered table-striped display nowrap w-100 mb-4">
                            <!-- Added margin-bottom -->
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th style="width: 120px;">Action</th>
                                    <th>Nama Hotel</th>
                                    <th>Rating</th>
                                    <th>Kota</th>
                                    <th>Negara</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be populated by DataTables -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th style="width: 120px;">Action</th>
                                    <th>Nama Hotel</th>
                                    <th>Rating</th>
                                    <th>Kota</th>
                                    <th>Negara</th>
                                    <th>Created At</th>
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
                        <h5 class="card-title" style="margin: 0;">Data</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -23px;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <!-- /.card-header -->
                    <div class="modal-body">
                        <input type="hidden" name="id" />
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label">Nama Hotel</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="nama_hotel" name="nama_hotel" placeholder="Nama Hotel" required>
                                <input type="hidden" class="form-control" id="id" name="id" placeholder="id">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label">Kota</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="kota" name="kota" placeholder="Kota" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label">Negara</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="negara" name="negara" placeholder="Negara" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label">Rating</label>
                            <div class="col-sm-9">
                                <div class="rating">
                                    <input type="radio" id="star5" name="rating" value="5">
                                    <label for="star5" title="5 Bintang">★</label>
                                    <input type="radio" id="star4" name="rating" value="4">
                                    <label for="star4" title="4 Bintang">★</label>
                                    <input type="radio" id="star3" name="rating" value="3">
                                    <label for="star3" title="3 Bintang">★</label>
                                    <input type="radio" id="star2" name="rating" value="2">
                                    <label for="star2" title="2 Bintang">★</label>
                                    <input type="radio" id="star1" name="rating" value="1">
                                    <label for="star1" title="1 Bintang">★</label>
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

</div>

<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>

<script type="text/javascript">
    var table;
    $(document).ready(function() {
        table = $('#hotel-table').DataTable({
            "responsive": true,
            "scrollX": true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('hotel_pu/get_list') ?>",
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
            url = "<?php echo site_url('hotel_pu/add') ?>";
        } else {
            url = "<?php echo site_url('hotel_pu/update') ?>";
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


    function edit_data(id) {
        method = 'update';
        $('#modalform')[0].reset();
        var validator = $("#modalform").validate();
        validator.resetForm();
        $('.form-control').removeClass('error');
        $('#modal-default').modal('show');
        $('.card-title').text('Edit Data');
        $('.aksi').text('Update');
        $.ajax({
            url: "<?php echo site_url('hotel_pu/get_id/') ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('[name="id"]').val(data.id);
                $('[name="nama_hotel"]').val(data.nama_hotel);
                $('[name="kota"]').val(data.kota);
                $('[name="negara"]').val(data.negara);

                if (data.rating == 1) {
                    $('#star1').prop('checked', true);
                } else if (data.rating == 2) {
                    $('#star2').prop('checked', true);
                } else if (data.rating == 3) {
                    $('#star3').prop('checked', true);
                } else if (data.rating == 4) {
                    $('#star4').prop('checked', true);
                } else if (data.rating == 5) {
                    $('#star5').prop('checked', true);
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
                    url: "<?php echo site_url('hotel_pu/delete') ?>/" + id,
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