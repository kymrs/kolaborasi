<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $titleview ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <?php if ($add == 'Y') { ?>
                        <a class="btn btn-primary btn-sm" id="add_btn" href="<?= base_url('pu_customer/add_form') ?>">
                            <i class="fa fa-plus"></i>&nbsp;Add Data Customer
                        </a>
                    <?php } ?>
                    <a style="background-color: rgb(36, 44, 73);" class="btn btn-secondary btn-sm float-right ml-2" href="<?= base_url('pu_customer') ?>">
                        <i class="fas fa-chevron-left"></i>&nbsp;Back
                    </a>
                    <a class="btn btn-success btn-sm float-right" id="btn-export-excel">
                        <i class="fa fa-file-excel" style="margin-right: 6px"></i>Export to Excel
                    </a>
                </div>
                <div class="card-body p-4">
                    <!-- Added padding for spacing -->
                    <div class="table-responsive">
                        <!-- Table wrapper -->
                        <table id="customerTable" class="table table-bordered table-striped display nowrap w-100 mb-4">
                            <!-- Added margin-bottom -->
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th style="width: 120px;">Action</th>
                                    <th>Customer ID</th>
                                    <th>Group ID</th>
                                    <th>Nama</th>
                                    <th>Jenis Kelamin</th>
                                    <th>No Telp</th>
                                    <th>Asal</th>
                                    <th>Dibuat pada</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be populated by DataTables -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th style="width: 120px;">Action</th>
                                    <th>Group ID</th>
                                    <th>Nama</th>
                                    <th>Jenis Kelamin</th>
                                    <th>No Telp</th>
                                    <th>Asal</th>
                                    <th>Dibuat pada</th>
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
        table = $('#customerTable').DataTable({
            "responsive": true,
            "scrollX": true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('pu_customer/get_list') ?>",
                "type": "POST",
                // "data": function(d) {
                //     d.status = $('#appFilter').val(); // Tambahkan parameter status ke permintaan server
                //     d.tab = $('.nav-tabs .nav-link.active').data('tab'); // Tambahkan parameter tab ke permintaan server
                // }
            },
            "language": {
                "infoFiltered": ""
            },
            "columnDefs": [{
                    "targets": [1, 2, 3, 4, 5, 6, 7, 8], // Adjusted indices to match the number of columns
                    "className": 'dt-head-nowrap'
                },
                {
                    "targets": [1, 2, 3, 4, 5, 6, 7, 8],
                    "className": 'dt-body-nowrap'
                },
                {
                    "targets": [0, 1], // Indices for non-orderable columns
                    "orderable": false,
                }
            ],
        });
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
                    url: "<?php echo site_url('pu_customer/delete/') ?>" + id,
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
                            location.href = "<?= base_url('pu_customer/customer') ?>";
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
        // Arahkan ke URL controller untuk export Excel
        window.location.href = "<?= site_url('pu_customer/export_excel_customer'); ?>";
    });

    // $('#add_btn').click(function(e) {
    //     window.location.href = '<?= base_url('pu_customer/set_access') ?>?link=' + 'add_form';
    // });
</script>