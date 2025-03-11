<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $titleview ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <table id="table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Action</th>
                                <th>No. Dokumen</th>
                                <th>User</th>
                                <th>Agenda</th>
                                <th>Date</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Lokasi</th>
                                <th>Peserta</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Action</th>
                                <th>No. Dokumen</th>
                                <th>User</th>
                                <th>Agenda</th>
                                <th>Date</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Lokasi</th>
                                <th>Peserta</th>
                            </tr>
                        </tfoot>
                    </table>
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
        table = $('#table').DataTable({
            "responsive": true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('pu_mom_admin/get_list') ?>",
                "type": "POST"
            },
            "columnDefs": [{
                "targets": [2, 5, 6],
                "className": 'dt-head-nowrap'
            }, {
                "targets": [1],
                "className": 'dt-body-nowrap'
            }, {
                "targets": [0, 1],
                "orderable": false,
            }, ],
        });
    });
</script>