<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $titleview ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <a class="btn btn-primary btn-sm" href="<?= base_url('prepayment/add_form') ?>"><i class="fa fa-plus"></i>&nbsp;Add Data</a>
                </div>
                <div class="card-body">
                    <table id="table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Action</th>
                                <th>Kode Prepayment</th>
                                <th>Nama</th>
                                <th>Jabatan</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Prepayment</th>
                                <th>Tujuan</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Action</th>
                                <th>Kode Prepayment</th>
                                <th>Nama</th>
                                <th>Jabatan</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Prepayment</th>
                                <th>Tujuan</th>
                                <th>Jumlah</th>
                                <th>Status</th>
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
            "responsive": false,
            "scrollX": true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('prepayment/get_list') ?>",
                "type": "POST"
            }
        });
    });

</script>