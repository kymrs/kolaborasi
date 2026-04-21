<?php
$roles_for_user = isset($roles_for_user) ? (array) $roles_for_user : array();
$viewer_role = isset($viewer_role) ? strtolower(trim((string) $viewer_role)) : (!empty($roles_for_user) ? $roles_for_user[0] : '');
$is_authorized = isset($is_authorized) ? (bool) $is_authorized : !empty($roles_for_user);

$btn_modes = array(
    'marketing' => array('label' => 'Marketing', 'class' => 'btn-primary'),
    'plotting' => array('label' => 'Plotting', 'class' => 'btn-warning'),
    'monitoring' => array('label' => 'Monitoring', 'class' => 'btn-info'),
    'finance' => array('label' => 'Finance', 'class' => 'btn-success'),
);
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $titleview ?></h1>
    </div>

    <?php if (!$is_authorized) { ?>
        <div class="alert alert-danger">
            Username ini belum memiliki akses untuk form Kertas Kerja.
            Silakan minta admin untuk menambahkan akses</b>.
        </div>
    <?php } ?>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <?php if ($add == 'Y') { ?>
                    <div class="card-header py-3">
                        <!-- <?php if ($is_authorized) { ?>
                            <a class="btn btn-sm btn-secondary" href="<?= base_url('sml_kertas_kerja/transaksi_list') ?>">
                                <i class="fa fa-clock"></i>&nbsp;Transaksi / Timestamp
                            </a>
                        <?php } ?> -->
                        <?php if ($is_authorized && in_array('marketing', $roles_for_user, true)) { ?>
                            <a class="btn btn-sm btn-primary" href="<?= base_url('sml_kertas_kerja/add_form?mode=marketing') ?>">
                                <i class="fa fa-plus"></i>&nbsp;Add Marketing
                            </a>
                            <!-- <div class="small text-muted mt-2">Plotting/Monitoring/Finance melengkapi data via tombol Action per baris.</div> -->
                        <?php } ?>
                        <?php if ($is_authorized && in_array('marketing', $roles_for_user, true) || in_array('plotting', $roles_for_user, true) || in_array('monitoring', $roles_for_user, true) || in_array('finance', $roles_for_user, true)) { ?>
                            <a class="btn btn-success" id="btn-export-excel" style="font-size: 12.5px">
                                <i class="fa fa-file-excel" style="margin-right: 6px"></i>Export to Excel
                            </a>
                        <?php } ?>
                        <div class="float-right">
                            <label for="statusFilter">Status Filter:</label>
                            <select id="statusFilter" class="form-control form-control-sm d-inline-block" style="width: auto;">
                                <option value="all">All</option>
                                <option value="proses">Proses</option>
                                <option value="selesai">Selesai</option>
                            </select>
                        </div>  
                    </div>
                <?php } ?>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Action</th>
                                <th>Periode</th>
                                <th>Tanggal</th>
                                <th>Konsumen/Customer</th>
                                <th>Project</th>
                                <th>Route</th>
                                <th>Nopol</th>
                                <th>Driver</th>
                                <th>Est Tanggal Tujuan</th>
                                <th>Tanggal Muat</th>
                                <th>Lokasi Bongkar</th>
                                <th>Uang Jalan</th>
                                <th>No Invoice</th>
                                <th>Status Bayar</th>
                                <th>Progress</th>
                                <th>Status</th>
                                <th>Last Update</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Action</th>
                                <th>Periode</th>
                                <th>Tanggal</th>
                                <th>Konsumen/Customer</th>
                                <th>Project</th>
                                <th>Route</th>
                                <th>Nopol</th>
                                <th>Driver</th>
                                <th>Est Tanggal Tujuan</th>
                                <th>Tanggal Muat</th>
                                <th>Lokasi Bongkar</th>
                                <th>Uang Jalan</th>
                                <th>No Invoice</th>
                                <th>Status Bayar</th>
                                <th>Progress</th>
                                <th>Status</th>
                                <th>Last Update</th>
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

<style>
    /* Tabel lebar: izinkan scroll horizontal */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    #table {
        width: 100% !important;
        white-space: nowrap;
    }

    div.dataTables_wrapper div.dataTables_scrollBody {
        overflow-x: auto !important;
    }
</style>

<script type="text/javascript">
    var table;

    function getQueryParam(name) {
        try {
            var params = new URLSearchParams(window.location.search);
            return params.get(name);
        } catch (e) {
            return null;
        }
    }

    $(document).ready(function() {
        // 0 No, 1 Action, 2 Periode, 3 Tanggal, 4 Konsumen, 5 Project, 6 Route,
        // 7 Nopol, 8 Driver, 9 Est Tanggal Tujuan, 
        // 10 Tanggal Muat, 11 Lokasi Bongkar,
        // 12 Uang Jalan, 13 No Invoice, 14 Status Bayar,
        // 15 Progress, 16 Status, 17 Last Update
        var viewerRole = "<?= htmlspecialchars($viewer_role) ?>";
        var hiddenCols = [];
        if (viewerRole === 'marketing') {
            hiddenCols = [7, 8, 9, 10, 11, 12, 13, 14];
        } else if (viewerRole === 'plotting') {
            hiddenCols = [3, 4, 5, 10, 11, 12, 13, 14];
        } else if (viewerRole === 'monitoring') {
            hiddenCols = [3, 4, 5, 7, 8, 9, 12, 13, 14];
        } else if (viewerRole === 'finance') {
            hiddenCols = [3, 4, 5, 7, 8, 9, 10, 11, 12];
        }

        var statusFilter = $('#statusFilter');
        var initialFilter = (getQueryParam('status_filter') || getQueryParam('status') || 'all');
        initialFilter = String(initialFilter || '').toLowerCase().trim();
        if (['all', 'proses', 'selesai'].indexOf(initialFilter) < 0) {
            initialFilter = 'all';
        }
        statusFilter.val(initialFilter);

        table = $('#table').DataTable({
            "responsive": false,
            "scrollX": true,
            "scrollCollapse": true,
            "processing": true,
            "serverSide": true,
            "ordering": true,
            "order": [[17, "desc"]],
            "ajax": {
                "url": "<?php echo site_url('sml_kertas_kerja/get_list') ?>",
                "type": "POST",
                "data": function(d) {
                    d.status_filter = statusFilter.val();
                }
            },
            "columnDefs": [{
                // Hanya kolom Action yang tidak bisa disortir
                "targets": [1],
                "orderable": false
            }, {
                "targets": hiddenCols,
                "visible": false
            }],
        });

        statusFilter.on('change', function() {
            table.ajax.reload(null, true);
        });

        table.columns.adjust();
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
                    url: "<?php echo site_url('sml_kertas_kerja/delete/') ?>" + id,
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
                            location.href = "<?= base_url('sml_kertas_kerja') ?>";
                        })
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error deleting data');
                    }
                });
            }
        })
    };

    $('#btn-export-excel').click(function() {
        var statusVal = $('#statusFilter').val();
        var url = "<?= site_url('sml_kertas_kerja/export_excel'); ?>";
        if (statusVal) {
            url += '?status_filter=' + encodeURIComponent(statusVal);
        }
        window.location.href = url;
    });
</script>