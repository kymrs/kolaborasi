<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $titleview ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <a class="btn btn-primary btn-sm" href="<?= base_url('datadeklarasi_pu/add_form') ?>">
                        <i class="fa fa-plus"></i>&nbsp;Add Data
                    </a>
                    <div class="d-flex align-items-center">
                        <label for="appFilter" class="mr-2 mb-0">Filter:</label>
                        <select id="appFilter" name="appFilter" class="form-control form-control-sm">
                            <option value="on-process" selected>On-Process</option>
                            <option value="approved">Approved</option>
                            <option value="revised">Revised</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                </div>
                <!-- NAV TABS -->
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" id="personalTab" href="#" data-tab="personal">Personal</a>
                    </li>
                    <?php if ($approval > 0) { ?>
                        <li class="nav-item">
                            <a class="nav-link" id="employeeTab" href="#" data-tab="employee">Employee</a>
                        </li>
                    <?php } ?>
                </ul>

                <div class="card-body p-4">
                    <!-- Added padding for spacing -->
                    <div class="table-responsive">
                        <!-- Table wrapper -->
                        <table id="declarationTable" class="table table-bordered table-striped display nowrap w-100 mb-4">
                            <!-- Added margin-bottom -->
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th style="width: 120px;">Action</th>
                                    <th>Kode Deklarasi</th>
                                    <th>Tanggal</th>
                                    <th>Pengaju</th>
                                    <th>Jabatan</th>
                                    <th>Penerima</th>
                                    <th>Tujuan</th>
                                    <th>Sebesar</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be populated by DataTables -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th style="width: 120px;">Action</th>
                                    <th>Kode Deklarasi</th>
                                    <th>Tanggal</th>
                                    <th>Pengaju</th>
                                    <th>Jabatan</th>
                                    <th>Penerima</th>
                                    <th>Tujuan</th>
                                    <th>Sebesar</th>
                                    <th>Status</th>
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
    var table;
    $(document).ready(function() {
        table = $('#declarationTable').DataTable({
            "responsive": false,
            "scrollX": true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('datadeklarasi_pu/get_list') ?>",
                "type": "POST",
                "data": function(d) {
                    d.status = $('#appFilter').val(); // Tambahkan parameter status ke permintaan server
                    d.tab = $('.nav-tabs .nav-link.active').data('tab'); // Tambahkan parameter tab ke permintaan server
                }
            },
            "language": {
                "infoFiltered": ""
            },
            "columnDefs": [{
                    "targets": [2, 4, 6], // Adjusted indices to match the number of columns
                    "className": 'dt-head-nowrap'
                },
                {
                    "targets": [1],
                    "className": 'dt-body-nowrap'
                },
                {
                    "targets": [0, 1], // Indices for non-orderable columns
                    "orderable": false,
                },
                {
                    "targets": [8], // Adjust this index to the column containing the numeric values you want to format
                    "render": function(data, type, row) {
                        return formatNumber(data);
                    }
                }
            ],
        });
    });

    // Restore filter value from localStorage
    var savedStatus = localStorage.getItem('appFilterStatus');
    if (savedStatus) {
        $('#appFilter').val(savedStatus).change();
    }

    // Save filter value to localStorage on change
    $('#appFilter').on('change', function() {
        localStorage.setItem('appFilterStatus', $(this).val());
    });

    $('#appFilter').change(function() {
        table.ajax.reload(); // Muat ulang data di DataTable dengan filter baru
    });

    // Event listener untuk nav tabs
    $('.nav-tabs a').on('click', function(e) {
        e.preventDefault();
        $('.nav-tabs a').removeClass('active'); // Hapus kelas aktif dari semua tab
        $(this).addClass('active'); // Tambahkan kelas aktif ke tab yang diklik

        table.ajax.reload(); // Muat ulang data di DataTable saat tab berubah
    });

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
                    url: "<?php echo site_url('datadeklarasi_pu/delete/') ?>" + id,
                    type: "POST",
                    dataType: "JSON",
                    success: function(data) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Your data has been deleted',
                            showConfirmButton: false,
                            timer: 1500
                        }).then((result) => {
                            location.href = "<?= base_url('datadeklarasi_pu') ?>";
                        })
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error deleting data');
                    }
                });
            }
        })
    };

    function formatNumber(value) {
        if (typeof value === 'number') {
            value = value.toString();
        }
        return value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }
</script>