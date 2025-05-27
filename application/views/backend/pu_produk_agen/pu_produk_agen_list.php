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
                    <a class="btn btn-primary btn-sm" href="<?= base_url('pu_produk_agen/add_form') ?>">
                        <i class="fa fa-plus"></i>&nbsp;Add Data
                    </a>
                </div>
                <div class="card-body p-4">
                    <!-- Added padding for spacing -->
                    <div class="table-responsive">
                        <!-- Table wrapper -->
                        <table id="produk-agen-table" class="table table-bordered table-striped display nowrap w-100 mb-4">
                            <!-- Added margin-bottom -->
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th style="width: 120px;">Action</th>
                                    <th>Nama Travel</th>
                                    <th>Harga Paket</th>
                                    <th>Fee Agen</th>
                                    <th>Tanggal Keberangkatan</th>
                                    <th>Sisa Seat</th>
                                    <th>Image</th>
                                    <th>Dibuat Pada</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be populated by DataTables -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th style="width: 120px;">Action</th>
                                    <th>Nama Travel</th>
                                    <th>Harga Paket</th>
                                    <th>Fee Agen</th>
                                    <th>Tanggal Keberangkatan</th>
                                    <th>Sisa Seat</th>
                                    <th>Image</th>
                                    <th>Dibuat Pada</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <!-- <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="modalform">
                    <div class="modal-header bg-primary text-gray-100">
                        <h5 class="card-title" style="margin: 0;">Tambah Data Agen</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -23px;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" />
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label">Nama Agen</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="nama_agen" name="nama_agen" placeholder="Nama Agen" required>
                                <input type="hidden" class="form-control" id="id" name="id" placeholder="id">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label">No Telepon</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="no_telp" name="no_telp" placeholder="No Telepon" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label">Alamat</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="alamat" id="alamat" placeholder="Alamat"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label">KTP</label>
                            <div class="col-sm-9">
                                <img src="" alt="tes" width="100px" style="margin-bottom: 10px; display: none;" id="imgProdukEdit">
                                <input type="file" class="form-control" id="ktp" name="ktp" accept=".jpg, .jpeg, .png" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer text-right">
                        <button type="submit" class="btn btn-primary aksi">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div> -->

    <!-- Modal Lihat Produk -->
    <div class="modal fade" id="modalProduk" tabindex="-1" role="dialog" aria-labelledby="modalProdukLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalProdukLabel">Lihat Produk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img id="imgProduk" src="" alt="Produk" class="img-fluid" style="max-height:400px;">
                    <br>
                    <a id="downloadProduk" href="#" class="btn btn-primary mt-3" download target="_blank">
                        <i class="fa fa-download"></i> Download Foto
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>

<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>

<script type="text/javascript">
    var table;
    $(document).ready(function() {
        table = $('#produk-agen-table').DataTable({
            "responsive": true,
            "scrollX": true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('pu_produk_agen/get_list') ?>",
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
            url = "<?php echo site_url('pu_produk_agen/add') ?>";
        } else {
            url = "<?php echo site_url('pu_produk_agen/update') ?>";
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
        $('#imgProdukEdit').hide();
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
        $('.card-title').text('Edit Data Agen');
        $('#imgProdukEdit').show();
        $('.aksi').text('Update');
        console.log(id);
        $.ajax({
            url: "<?php echo site_url('pu_produk_agen/get_id/') ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('[name="id"]').val(data.id);
                $('[name="nama_agen"]').val(data.nama);
                $('[name="no_telp"]').val(data.no_telp);
                $('[name="alamat"]').val(data.alamat);
                $('#imgProdukEdit').attr('src', '<?php echo base_url('assets/backend/document/ktp_agen_pu/') ?>' + data.ktp);

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
                    url: "<?php echo site_url('pu_produk_agen/delete') ?>/" + id,
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

    $(document).on('click', '.lihat-produk', function(e) {
        e.preventDefault();
        var imgUrl = $(this).data('img');
        $('#imgProduk').attr('src', imgUrl);
        $('#downloadProduk').attr('href', imgUrl);
        // Optional: set nama file download
        $('#downloadProduk').attr('download', 'produk-' + Date.now() + '.jpg');
        $('#modalProduk').modal('show');
    });
</script>