<?php
$roles_for_user = isset($roles_for_user) ? (array) $roles_for_user : array();
$is_authorized = isset($is_authorized) ? (bool) $is_authorized : !empty($roles_for_user);
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $titleview ?></h1>
        <!-- <div>
            <a class="btn btn-sm btn-primary" href="<?= base_url('sml_kertas_kerja') ?>"><i class="fa fa-chevron-left"></i>&nbsp;Kembali</a>
        </div> -->
    </div>

    <?php if (!$is_authorized) { ?>
        <div class="alert alert-danger">Anda belum punya mapping role kertas kerja.</div>
    <?php } ?>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tableTransaksi" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Action</th>
                                    <th>Periode</th>
                                    <th>Route</th>
                                    <th>Marketing (Update)</th>
                                    <th>Plotting (Update)</th>
                                    <th>Monitoring (Update)</th>
                                    <th>Finance (Update)</th>
                                    <th>Last Update</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Action</th>
                                    <th>Periode</th>
                                    <th>Route</th>
                                    <th>Marketing (Update)</th>
                                    <th>Plotting (Update)</th>
                                    <th>Monitoring (Update)</th>
                                    <th>Finance (Update)</th>
                                    <th>Last Update</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="small text-muted mt-2">
                        Catatan: detail waktu input pertama & update terakhir per section ada di tombol Action.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>

<style>
    .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    #tableTransaksi { width: 100% !important; white-space: nowrap; }
    div.dataTables_wrapper div.dataTables_scrollBody { overflow-x: auto !important; }
</style>

<script type="text/javascript">
    var table;
    $(document).ready(function() {
        table = $('#tableTransaksi').DataTable({
            "responsive": false,
            "scrollX": true,
            "scrollCollapse": true,
            "processing": true,
            "serverSide": true,
            "ordering": false,
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('sml_kertas_kerja/transaksi_get_list') ?>",
                "type": "POST"
            },
            "columnDefs": [{
                "targets": [0, 1],
                "orderable": false
            }],
        });
        table.columns.adjust();
    });
</script>
