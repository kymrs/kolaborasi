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

    .btn-header {
        display: inline-block;
    }

    /* Hilangkan icon panah dropdown */
    .btn.dropdown-toggle::after {
        display: none;
    }

    /* Dropdown Button */
    .btn-group .btn-primary {
        border: none;
        /* border-radius: 8px; */
        font-size: 14px;
        padding: 6px 14px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .dropdown-menu {
        border-radius: 10px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        border: none;
    }

    .dropdown-item {
        font-size: 14px;
        padding: 10px 20px;
        transition: background 0.2s;
    }

    .dropdown-item:hover {
        background-color: #242d4a;
        font-weight: 500;
        color: #fff;
        cursor: pointer;
    }

    .select2-container--default .select2-selection--single {
        height: 38px;
        /* match Bootstrap .form-control height */
        padding: 6px 12px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        background-color: #fff;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 24px;
        color: #495057;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
        right: 10px;
    }


    @media (max-width: 600px) {
        .btn-header {
            display: block;
            width: 100% !important;
            margin-bottom: 5px;
        }
    }
</style>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $titleview ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <?php if ($add == 'Y') { ?>
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-plus"></i>&nbsp; Add Data
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="<?= base_url('kps_karyawan/add_form') ?>">
                                    <i class="fas fa-user mr-2"></i> Data Karyawan
                                </a>
                                <?php if (!empty($karyawan)) : ?>
                                    <a class="dropdown-item" href="<?= base_url('kps_karyawan/add_form_keluarga') ?>">
                                        <i class="fas fa-users mr-2"></i> Data Keluarga
                                    </a>
                                <?php endif ?>
                            </div>
                            <a class="btn btn-primary btn-sm ml-2" style="border-radius: 4px" href="<?= base_url('kps_karyawan/e_pkwt') ?>">
                                <i class="fa fa-list"></i>&nbsp;&nbsp;Data E-PKWT
                            </a>
                        </div>
                    <?php } ?>
                    <select name="status_kerja" id="status_kerja" style="padding: 5px; width: 125px; float: right" class="form-control form-control-sm btn-primary btn-header">
                        <option value="">Semua Status</option>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Tidak Aktif</option>
                    </select>
                </div>
                <div class="card-body p-4">
                    <!-- Added padding for spacing -->
                    <div class="table-responsive">
                        <!-- Table wrapper -->
                        <table id="karyawan-table" class="table table-bordered table-striped display nowrap w-100 mb-4">
                            <!-- Added margin-bottom -->
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th style="width: 120px;">Action</th>
                                    <th>NPK</th>
                                    <th>Nama Lengkap</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Tempat Lahir</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Umur</th>
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
                                    <th>NPK</th>
                                    <th>Nama Lengkap</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Tempat Lahir</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Umur</th>
                                    <th>Dibuat Pada</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>

<script type="text/javascript">
    $(function() {
        $('.datepicker').datepicker({
            dateFormat: 'yy-mm-dd', // atau format lain sesuai kebutuhan
            changeMonth: true,
            changeYear: true,
            autoclose: true
        });
    });

    $(document).ready(function() {
        $(document).ready(function() {
            // Inisialisasi semua select2 yang muncul saat ini
            $('#form-kontrak').find('select.select2npk').select2({
                placeholder: "Pilih Karyawan",
                allowClear: true
            });

            // Event delegation untuk elemen dinamis
            $(document).on('focus', 'select.select2npk', function(e) {
                // Cegah inisialisasi ulang
                if (!$(this).hasClass("select2-hidden-accessible")) {
                    $(this).select2({
                        placeholder: "Pilih karyawan",
                        allowClear: true
                    });
                }
            });
        });

    });

    var table;
    $(document).ready(function() {
        table = $('#karyawan-table').DataTable({
            "responsive": true,
            "scrollX": true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('kps_karyawan/get_list') ?>",
                "type": "POST",
                "data": function(data) {
                    data.status_kerja = $('#status_kerja').val(); // Mengambil nilai dari dropdown status kerja
                }
            },
            "language": {
                "infoFiltered": ""
            },
            "columnDefs": [{
                    "targets": [1], // Adjusted indices to match the number of columns
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

    $('#status_kerja').on('change', function() {
        table.ajax.reload(); // Muat ulang DataTables dengan filter baru
    });

    $("#form-kontrak").submit(function(e) {
        $.ajax({
            url: "<?php echo site_url('kps_karyawan/add_kontrak_karyawan') ?>",
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

    $("#modalform").submit(function(e) {
        e.preventDefault();
        var url;
        var $form = $(this);
        if (!$form.valid()) return false;
        if (method == 'add') {
            url = "<?php echo site_url('kps_karyawan/add') ?>";
        } else {
            url = "<?php echo site_url('kps_karyawan/update') ?>";
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
                    url: "<?php echo site_url('kps_karyawan/delete_data_karyawan') ?>/" + id,
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