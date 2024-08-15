<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $titleview ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <a class="btn btn-primary btn-sm" href="<?= base_url('databooking/add_form') ?>"><i class="fa fa-plus"></i>&nbsp;Add Data</a>
                </div>
                <div class="card-body">
                    <table id="table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Action</th>
                                <th>Kode Booking</th>
                                <th>Nama</th>
                                <th>No. Handphone</th>
                                <th>Email</th>
                                <th>Tanggal Berangkat</th>
                                <th>Tanggal Pulang</th>
                                <th>Jam Penjemputan</th>
                                <th>Titik Penjemputan</th>
                                <th>Type Kendaraan</th>
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
                                <th>Kode Booking</th>
                                <th>Nama</th>
                                <th>No. Handphone</th>
                                <th>Email</th>
                                <th>Tanggal Berangkat</th>
                                <th>Tanggal Pulang</th>
                                <th>Jam Penjemputan</th>
                                <th>Titik Penjemputan</th>
                                <th>Type Kendaraan</th>
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
                "url": "<?php echo site_url('databooking/get_list') ?>",
                "type": "POST"
            },
            "columnDefs": [{
                    "targets": [2, 4, 6, 7, 8, 9, 10],
                    "className": 'dt-head-nowrap'
                },
                {
                    "targets": [1, 12],
                    "className": 'dt-body-nowrap'
                }, {
                    "targets": [0, 1],
                    "orderable": false,
                },
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
                    url: "<?php echo site_url('databooking/delete/') ?>" + id,
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
                            location.href = "<?= base_url('databooking') ?>";
                        })
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error deleting data');
                    }
                });
            }
        })
    };
</script>