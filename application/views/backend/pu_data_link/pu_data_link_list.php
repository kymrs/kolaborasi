<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $titleview ?></h1>
    </div>

    <div class="row">
        <!-- Data Crew -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <a class="btn btn-primary btn-sm" id="add_btn" data-toggle="modal" data-target="#modal-defaultcrew" onclick="add_data_crew()">
                        <i class="fa fa-plus"></i>&nbsp;Add Data Crew
                    </a>
                </div>
                <div class="card-body p-4">
                    <!-- Added padding for spacing -->
                    <div class="table-responsive">
                        <!-- Table wrapper -->
                        <table id="crew-table" class="table table-bordered table-striped display nowrap w-100 mb-4">
                            <!-- Added margin-bottom -->
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th style="width: 120px;">Action</th>
                                    <th>Kode Crew</th>
                                    <th>Nama Crew</th>
                                    <th>No Handphone</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be populated by DataTables -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th style="width: 120px;">Action</th>
                                    <th>Kode Crew</th>
                                    <th>Nama Crew</th>
                                    <th>No Handphone</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Member -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <a class="btn btn-primary btn-sm" id="add_btn" data-toggle="modal" data-target="#modal-defaultmember" onclick="add_data_member()">
                        <i class="fa fa-plus"></i>&nbsp;Add Data Member
                    </a>
                </div>
                <div class="card-body p-4">
                    <!-- Added padding for spacing -->
                    <div class="table-responsive">
                        <!-- Table wrapper -->
                        <table id="member-table" class="table table-bordered table-striped display nowrap w-100 mb-4">
                            <!-- Added margin-bottom -->
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th style="width: 120px;">Action</th>
                                    <th>Kode Member</th>
                                    <th>Nama Member</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be populated by DataTables -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th style="width: 120px;">Action</th>
                                    <th>Kode Member</th>
                                    <th>Nama Member</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Crew -->
    <div class="modal fade" id="modal-defaultcrew">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="modalformcrew">
                    <div class="modal-header bg-primary text-gray-100">
                        <h5 class="card-title" style="margin: 0;">Tambah Data Crew</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -23px;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <!-- /.card-header -->
                    <div class="modal-body">
                        <input type="hidden" name="id" />
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label">Nama</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="nama_crew" name="nama_crew" placeholder="Nama Crew" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label">No. Telp</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="no_hp" name="no_hp" placeholder="No. Telp" value="628" required>
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

    <!-- Modal Member -->
    <div class="modal fade" id="modal-defaultmember">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="modalformmember">
                    <div class="modal-header bg-primary text-gray-100">
                        <h5 class="card-title" style="margin: 0;">Tambah Data Member</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -23px;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <!-- /.card-header -->
                    <div class="modal-body">
                        <input type="hidden" name="id" />
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label">Nama</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="nama_member" name="nama_member" placeholder="Nama Member" required>
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
    document.addEventListener("DOMContentLoaded", function() {
        const inputNoHp = document.getElementById("no_hp");

        // Set default value to "628"
        inputNoHp.value = "628";

        // Event listener to allow only numeric input
        inputNoHp.addEventListener("input", function(e) {
            const value = e.target.value;

            // Remove any non-numeric characters
            e.target.value = value.replace(/[^0-9]/g, "");
        });
    });

    var table, table2;
    table = $('#crew-table').DataTable({
        "responsive": true,
        "scrollX": true,
        "processing": true,
        "serverSide": true,
        "paging": true,
        "order": [],
        "ajax": {
            "url": "<?php echo site_url('pu_data_link/get_list1'); ?>",
            "type": "POST"
        },
        "language": {
            "infoFiltered": ""
        },
        "columnDefs": [{
                "targets": [0],
                "className": 'dt-head-nowrap'
            },
            {
                "targets": [1],
                "className": 'dt-body-nowrap'
            },
            {
                "targets": [0, 1],
                "orderable": false
            }
        ]
    });

    table2 = $('#member-table').DataTable({
        "responsive": true,
        "scrollX": true,
        "processing": true,
        "serverSide": true,
        "paging": true,
        "order": [],
        "ajax": {
            "url": "<?php echo site_url('pu_data_link/get_list2'); ?>",
            "type": "POST",
            "dataSrc": function(json) {
                // Urutkan data berdasarkan kolom 'id' atau kolom lain yang menunjukkan data terbaru
                json.data.sort(function(a, b) {
                    return b[0] - a[0]; // Misal mengurutkan berdasarkan kolom 'id'
                });

                // Hitung nomor urut setelah data di-sort
                let no = 0;
                json.data.forEach(function(item) {
                    no++;
                    item.unshift(no); // Sisipkan nomor urut di indeks pertama
                });

                return json.data || [];
            }
        },
        "language": {
            "infoFiltered": ""
        },
        "columnDefs": [{
                "targets": [0],
                "className": 'dt-head-nowrap'
            },
            {
                "targets": [1],
                "className": 'dt-body-nowrap'
            },
            {
                "targets": [0, 1],
                "orderable": false
            }
        ]
    });

    $("#modalformcrew").submit(function(e) {
        e.preventDefault();
        var url;
        var $form = $(this);
        if (!$form.valid()) return false;
        if (method == 'add') {
            url = "<?php echo site_url('pu_data_link/addCrew') ?>";
        } else {
            url = "<?php echo site_url('pu_data_link/updateCrew') ?>";
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
                $('#modal-defaultcrew').modal('hide');
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Your data has been saved',
                    showConfirmButton: false,
                    timer: 1500
                });
                document.location.href = 'pu_data_link';
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
            }
        });
    });

    $("#modalformmember").submit(function(e) {
        e.preventDefault();
        var url;
        var $form = $(this);
        if (!$form.valid()) return false;
        if (method == 'add') {
            url = "<?php echo site_url('pu_data_link/addMember') ?>";
        } else {
            url = "<?php echo site_url('pu_data_link/updateMember') ?>";
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
                $('#modal-defaultmember').modal('hide');
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Your data has been saved',
                    showConfirmButton: false,
                    timer: 1500
                });
                document.location.href = 'pu_data_link';
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
            }
        });
    });

    function reload_table() {
        table.ajax.reload(null, false);
        table2.ajax.reload(null, false);
    };

    function add_data_crew() {
        method = 'add';
        $('#modalformcrew')[0].reset();
        $('.card-title').text('Tambah Data Crew');
        var validator = $("#modalformcrew").validate();
        validator.resetForm();
    };

    function add_data_member() {
        method = 'add';
        $('#modalformmember')[0].reset();
        $('.card-title').text('Tambah Data Member');
        var validator = $("#modalformmember").validate();
        validator.resetForm();
    };

    // Mengambil URL saat ini
    const params = new URLSearchParams(window.location.search);

    // Mengambil parameter tertentu
    const action = params.get('action'); // "John"

    if (action == 'add') {
        $('#add_btn').click();
    }

    function edit_data_crew(id) {
        method = 'update';
        $('#modalformcrew')[0].reset();
        var validator = $("#modalformcrew").validate();
        validator.resetForm();
        $('[name="id"]').prop('readonly', true);
        $('.form-control').removeClass('error');
        $('#modal-defaultcrew').modal('show');
        $('.card-title').text('Edit Data Crew');
        $('.aksi').text('Update');
        $.ajax({
            url: "<?php echo site_url('pu_data_link/get_id/') ?>" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('[name="id"]').val(data.id);
                $('[name="nama_crew"]').val(data.nama);
                $('[name="no_hp"]').val(data.noHP);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }

    function edit_data_member(idMember) {
        method = 'update';
        $('#modalformmember')[0].reset();
        var validator = $("#modalformmember").validate();
        validator.resetForm();
        $('.form-control').removeClass('error');
        $('#modal-defaultmember').modal('show');
        $('.card-title').text('Edit Data Member');
        $('.aksi').text('Update');
        $.ajax({
            url: "<?php echo site_url('pu_data_link/get_IdMember/') ?>" + idMember,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('[name="nama_member"]').val(data.namaMember);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }

    function delete_data_crew(id) {
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
                    url: "<?php echo site_url('pu_data_link/delete') ?>/" + id,
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
                        document.location.href = 'pu_data_link';
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error deleting data');
                    }
                });
            }
        })
    };

    function delete_data_member(idMember) {
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
                    url: "<?php echo site_url('pu_data_link/deleteMember') ?>/" + idMember,
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
                        document.location.href = 'pu_data_link';
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error deleting data');
                    }
                });
            }
        })
    };
</script>