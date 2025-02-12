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
                        <table id="layanan-table" class="table table-bordered table-striped display nowrap w-100 mb-4">
                            <!-- Added margin-bottom -->
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th style="width: 120px;">Action</th>
                                    <th>Kode</th>
                                    <th>Nama Produk</th>
                                    <th>Berat</th>
                                    <th>Satuan</th>
                                    <th>Stok Akhir</th>
                                    <th>Harga QubaGift</th>
                                    <th>Harga Reseller</th>
                                    <th>Harga Distributor</th>
                                    <th>Dibuat Pada</th>
                                    <th>Diubah Pada</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be populated by DataTables -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th style="width: 120px;">Action</th>
                                    <th>Kode</th>
                                    <th>Nama Produk</th>
                                    <th>Berat</th>
                                    <th>Satuan</th>
                                    <th>Stok Akhir</th>
                                    <th>Harga QubaGift</th>
                                    <th>Harga Reseller</th>
                                    <th>Harga Distributor</th>
                                    <th>Dibuat Pada</th>
                                    <th>Diubah Pada</th>
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
                        <h5 class="card-title" style="margin: 0;">Tambah Data Produk</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -23px;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <!-- /.card-header -->
                    <div class="modal-body">
                        <input type="hidden" name="id" />
                        <div class="form-group row">
                            <label for="" class="col-sm-4 col-form-label">Nama Produk</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="nama_produk" name="nama_produk" placeholder="Nama Produk" required>
                                <input type="hidden" class="form-control" id="id" name="id" placeholder="id">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-4 col-form-label">Berat</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="berat" name="berat" placeholder="Berat" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-4 col-form-label">Satuan</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="satuan" name="satuan" placeholder="Satuan" required>
                            </div>
                        </div>
                        <div class="form-group row" id="stok_awal">
                            <label for="" class="col-sm-4 col-form-label">Stok Awal</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="stok" name="stok" placeholder="Stok Awal" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-4 col-form-label">Harga QubaGift</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="harga_qubagift" name="harga_qubagift" placeholder="Harga QubaGift" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-4 col-form-label">Harga Reseller</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="harga_reseller" name="harga_reseller" placeholder="Harga Reseller" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-4 col-form-label">Harga Distributor</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="harga_distributor" name="harga_distributor" placeholder="Harga Distributor" required>
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

    <!-- Modal -->
    <div class="modal fade" id="modal-stok">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="modalform-stok">
                    <div class="modal-header bg-primary text-gray-100">
                        <h5 class="card-title" style="margin: 0;">Ubah Data Stok</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -23px;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <!-- /.card-header -->
                    <div class="modal-body">
                        <input type="hidden" class="form-control" id="kode_produk" name="kode_produk">
                        <table>
                            <tr>
                                <td>Nama Produk</td>
                                <td style="padding: 0 5px 0 15px;">:</td>
                                <td id="produk-info" style="font-weight: bold;"></td>
                            </tr>
                            <tr>
                                <td>Stok Saat Ini</td>
                                <td style="padding: 0 5px 0 15px;">:</td>
                                <td id="stok-info" style="font-weight: bold;"></td>
                            </tr>
                        </table>
                        <hr>
                        <div class="form-group row">
                            <label for="" class="col-sm-4 col-form-label">Pilih Opsi</label>
                            <div class="col-sm-8" style="display: flex; align-items: center">
                                <input type="radio" name="stok_option" id="tambah-stok"><label for="tambah-stok" style="margin-left: 5px; margin-right: 10px; position: relative; top: 5px; cursor: pointer">Tambah Stok</label>
                                <input type="radio" name="stok_option" id="kurangi-stok"><label for="kurangi-stok" style="margin-left: 5px; position: relative; top: 5px; cursor: pointer">Kurangi Stok</label>
                            </div>
                        </div>
                        <div class="form-group row" id="tambah-stok-container">
                            <label for="" class="col-sm-4 col-form-label">Tambah Stok</label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="tambah_stok" name="tambah_stok" placeholder="Tambah Stok" required>
                            </div>
                        </div>
                        <div class="form-group row" id="kurangi-stok-container">
                            <label for="" class="col-sm-4 col-form-label">Kurangi Stok</label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="kurangi_stok" name="kurangi_stok" placeholder="Kurangi Stok" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer text-right">
                        <button type="submit" class="btn btn-primary aksi" id="save-button"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>

<script type="text/javascript">
    $(document).ready(function() {
        $('input[name="stok_option"]').on('change', function() {
            $('#save-button').css('display', 'flex');
            if ($('#tambah-stok').is(':checked')) {
                $('#tambah-stok-container').css('display', 'flex');
                $('#kurangi-stok-container').css('display', 'none');
                $('#save-button').html('Tambah Stok');
            } else if ($('#kurangi-stok').is(':checked')) {
                $('#kurangi-stok-container').css('display', 'flex');
                $('#tambah-stok-container').css('display', 'none');
                $('#save-button').html('Kurangi Stok');
            }
        });
    });

    var table;
    $(document).ready(function() {
        table = $('#layanan-table').DataTable({
            "responsive": true,
            "scrollX": true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('qbg_produk/get_list') ?>",
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
                    "targets": [],
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
            url = "<?php echo site_url('qbg_produk/add') ?>";
        } else {
            url = "<?php echo site_url('qbg_produk/update') ?>";
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

    $("#modalform-stok").submit(function(e) {
        e.preventDefault();
        var url;
        var $form = $(this);
        if (!$form.valid()) return false;
        if (method == 'add') {
            url = "<?php echo site_url('qbg_produk/add') ?>";
        } else {
            url = "<?php echo site_url('qbg_produk/update') ?>";
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
                $('#modal-stok').modal('hide');
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
        $('#stok_awal').css('display', 'none');
        $('#modal-default').modal('show');
        $('.card-title').text('Edit Data Produk');
        $('.aksi').text('Update');
        $.ajax({
            url: "<?php echo site_url('qbg_produk/get_id/') ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('[name="id"]').val(data.id);
                $('[name="nama_produk"]').val(data.nama_produk);
                $('[name="berat"]').val(data.berat);
                $('[name="satuan"]').val(data.satuan);
                $('[name="harga_qubagift"]').val(data.harga_qubagift);
                $('[name="harga_reseller"]').val(data.harga_reseller);
                $('[name="harga_distributor"]').val(data.harga_distributor);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    };

    function edit_stok(kode) {
        method = 'update';
        $('#modalform-stok')[0].reset();
        var validator = $("#modalform-stok").validate();
        validator.resetForm();
        $('.form-control').removeClass('error');
        $('#tambah-stok-container').css('display', 'none');
        $('#kurangi-stok-container').css('display', 'none');
        $('#save-button').css('display', 'none');
        $('#modal-stok').modal('show');
        $.ajax({
            url: "<?php echo site_url('qbg_produk/get_stok_kode/') ?>/" + kode,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('[name="kode_produk"]').val(data.produk.kode_produk);
                $('#produk-info').html(data.produk.nama_produk + " " + data.produk.berat + " " + data.produk.satuan);
                $('#stok-info').html(data.stok.stok_akhir);
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
                    url: "<?php echo site_url('qbg_produk/delete') ?>/" + id,
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