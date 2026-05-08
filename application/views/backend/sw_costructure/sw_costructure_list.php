<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $titleview ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <a class="btn btn-primary btn-sm" href="<?= base_url('sw_costructure/add_form') ?>">
                        <i class="fa fa-plus"></i>&nbsp;Add Data
                    </a>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table id="costructure-table" class="table table-bordered table-striped display nowrap w-100 mb-4">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th style="width: 100px;">Action</th>
                                    <th>Company Name</th>
                                    <th>Event Type</th>
                                    <th>Participants</th>
                                    <th>Grand Total</th>
                                    <th>Selling Price</th>
                                    <th>Date Created</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        table = $('#costructure-table').DataTable({
            "responsive": false,
            "scrollX": true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('sw_costructure/get_list') ?>",
                "type": "POST"
            },
            "language": {
                "infoFiltered": ""
            },
            "columnDefs": [{
                    "targets": [2],
                    "className": 'dt-head-nowrap'
                },
                {
                    "targets": [1],
                    "className": 'dt-body-nowrap'
                }, {
                    "targets": [0, 1],
                    "orderable": false,
                },
            ],
        });

        // Delete function
        window.delete_data = function(id) {
            if (confirm('Are you sure you want to delete this cost structure?')) {
                $.ajax({
                    url: '<?= site_url('sw_costructure/delete') ?>',
                    type: 'POST',
                    data: { id: id },
                    dataType: 'JSON',
                    success: function(response) {
                        if (response.status) {
                            alert(response.message);
                            table.ajax.reload();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(error) {
                        alert('An error occurred while deleting data');
                        console.error(error);
                    }
                });
            }
        };
    });
</script>