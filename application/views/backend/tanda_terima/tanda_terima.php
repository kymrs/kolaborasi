<!-- Begin Page Content -->
<div class="container-fluid" id="konten">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tanda Terima</h1>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a class="d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#modal-default" onclick="add_data()"><i class="fas fa-plus fa-sm text-white-50"></i> Add Data</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="table" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Action</th>
                            <th>Nomor</th>
                            <th>Tanggal</th>
                            <th>Pengirim</th>
                            <th>Penerima</th>
                            <th>Uraian</th>
                            <th>Jumlah</th>
                            <th>Foto</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Action</th>
                            <th>Nomor</th>
                            <th>Tanggal</th>
                            <th>Pengirim</th>
                            <th>Penerima</th>
                            <th>Uraian</th>
                            <th>Jumlah</th>
                            <th>Foto</th>
                        </tr>
                    </tfoot>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="modalform">
                <div class="modal-header bg-primary text-gray-100">
                    <h5 class="card-title">Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- /.card-header -->
                <div class="modal-body">
                    <input type="hidden" name="id" />
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Nomor</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="nomor" name="nomor" placeholder="Nomor" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Tanggal</label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" id="tanggal" name="tanggal" placeholder="Tanggal">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Nama Pengirim</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="nama_pengirim" name="nama_pengirim" placeholder="Nama Pengirim">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="level" class="col-sm-3 col-form-label">Title Penerima</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="title" name="title">
                                <option value="">-- Pilih --</option>
                                <option value="Tn.">Tn.</option>
                                <option value="Ny.">Ny.</option>
                                <option value="Nn.">Nn.</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Nama Penerima</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="nama_penerima" name="nama_penerima" placeholder="Nama Penerima">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Uraian</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="barang" name="barang" placeholder="Contoh: perlengkapan jamaah / paspor / dokumen lain">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Jumlah</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="qty" name="qty" placeholder="Jumlah">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Rincian</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="keterangan" name="keterangan" placeholder="Contoh: Rincian Jumlah perlengkapan / nomor dokumen" rows=5></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="image" class="col-sm-3 col-form-label">Image</label>
                        <div class="col-sm-9">
                            <input type="file" class="" id="image" name="image"> <br />
                            <img id="img_name" height="200px">
                        </div>
                    </div>

                </div>
                <div class="modal-footer text-right">
                    <button type="submit" class="btn btn-primary aksi">Save</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->

        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

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
                    "url": "<?php echo site_url('tanda_terima/get_list') ?>",
                    "type": "POST"
                },
                "columnDefs": [{
                        "targets": [],
                        "className": 'dt-head-nowrap'
                    },
                    {
                        "targets": [1, 3, 4, 5, 6],
                        "className": 'dt-body-nowrap'
                    }, {
                        "targets": [0, 7, 8],
                        "orderable": false,
                    },
                ],
            });

            $("#modalform").validate({
                rules: {
                    nomor: {
                        required: true,
                    },
                    tanggal: {
                        required: true,
                    },
                    nama_pengirim: {
                        required: true,
                    },
                    title: {
                        required: true,
                    },
                    nama_penerima: {
                        required: true,
                    },
                },
                messages: {
                    //   nomor: {
                    //     required: "Username is required",
                    //   },
                    //   tanggal: {
                    //     required: "Fullname is required",
                    //   },
                    //   password: {
                    //     required: "Password is required",
                    //   },
                    //   level: {
                    //     required: "Level is required",
                    //   },
                    //   aktif: {
                    //     required: "Aktif is required",
                    //   },
                },
            })

            $("#modalform").submit(function(e) {
                e.preventDefault();
                var url;
                var $form = $(this);
                if (!$form.valid()) return false;
                if (method == 'add') {
                    url = "<?php echo site_url('tanda_terima/add') ?>";
                } else {
                    url = "<?php echo site_url('tanda_terima/update') ?>";
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
        });

        function reload_table() {
            table.ajax.reload(null, false);
        };

        function add_data() {
            method = 'add';
            $('#modalform')[0].reset();
            var validator = $("#modalform").validate();
            validator.resetForm();
            $('#modal-default').modal('show');
            $('.card-title').text('Data Baru');
            $('.aksi').text('Save');
            get_nomor();
            $('#img_name').attr('hidden', true);
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
                url: "<?php echo site_url('tanda_terima/get_id/') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('[name="id"]').val(data.id);
                    $('[name="nomor"]').val(data.nomor);
                    $('[name="tanggal"]').val(data.tanggal);
                    $('[name="nama_pengirim"]').val(data.nama_pengirim);
                    $('[name="title"]').val(data.title);
                    $('[name="nama_penerima"]').val(data.nama_penerima);
                    $('[name="barang"]').val(data.barang);
                    $('[name="qty"]').val(data.qty);
                    $('[name="keterangan"]').val(data.keterangan);
                    $('#img_name').attr('src', '<?= base_url("assets/img/") ?>' + data.foto);
                    $('#img_name').attr('hidden', false);
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
                        url: "<?php echo site_url('tanda_terima/delete') ?>/" + id,
                        type: "POST",
                        dataType: "JSON",
                        success: function(data) {
                            Swal.fire({
                                position: 'top-end',
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

        function get_nomor() {
            $.ajax({
                url: "<?php echo site_url('tanda_terima/get_no/') ?>",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    if (data === null) {
                        kode = 'PU00001';
                    } else {
                        no = data.nomor.substr(2 - data.nomor.length);
                        no_baru = parseInt(no) + 1;
                        kode = 'PU' + no_baru.toString().padStart(5, '0');
                    }
                    $('[name="nomor"]').val(kode);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function view_data(id) {
            window.open("<?php echo site_url('tanda_terima/preview/') ?>" + id, '_blank');
        }

        function print_data(id) {
            window.open('<?= base_url("tanda_terima/print/") ?>' + id, '_blank')
        }

        function back() {
            window.open('<?= base_url("tanda_terima/") ?>', '_self')
        }

        function pdf(id) {
            window.open('<?= base_url("tanda_terima/pdf/") ?>' + id, '_blank')
        }
    </script>