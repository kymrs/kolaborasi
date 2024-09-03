<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $titleview ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <a class="btn btn-primary btn-sm" href="<?= base_url('reimbust/add_form') ?>">
                        <i class="fa fa-plus"></i>&nbsp;Add Data
                    </a>
                    <div class="d-flex align-items-center">
                        <label for="appFilter" class="mr-2 mb-0">Filter:</label>
                        <select id="appFilter" name="appFilter" class="form-control form-control-sm" style="cursor: pointer;">
                            <option value="" selected>Show all....</option>
                            <option value="on-process">On-Process</option>
                            <option value="approved">Approved</option>
                            <option value="revised">Revised</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <table id="table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Action</th>
                                <th>Kode Reimbust</th>
                                <th>Nama</th>
                                <th>Jabatan</th>
                                <th>Departemen</th>
                                <th>Sifat Pelaporan</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Tujuan</th>
                                <th>Jumlah Prepayment</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Action</th>
                                <th>Kode Reimbust</th>
                                <th>Nama</th>
                                <th>Jabatan</th>
                                <th>Departemen</th>
                                <th>Sifat Pelaporan</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Tujuan</th>
                                <th>Jumlah Prepayment</th>
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
                "url": "<?php echo site_url('reimbust/get_list') ?>",
                "type": "POST",
                "data": function(d) {
                    d.status = $('#appFilter').val(); // Tambahkan parameter status ke permintaan server
                }
            },
            "language": {
                "infoFiltered": ""
            },
            "columnDefs": [{
                    "targets": [2, 6, 7, 9],
                    "className": 'dt-head-nowrap'
                },
                {
                    "targets": [1, 3, 8, 10],
                    "className": 'dt-body-nowrap'
                }, {
                    "targets": [0, 1],
                    "orderable": false,
                },
            ],
        });
    });

    $('#appFilter').change(function() {
        table.ajax.reload(); // Muat ulang data di DataTable dengan filter baru
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
                    url: "<?php echo site_url('reimbust/delete/') ?>" + id,
                    type: "POST",
                    dataType: "JSON",
                    success: function(data) {
                        if (data.status) {
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'Your data has been deleted',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                location.href = "<?= base_url('reimbust') ?>";
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.error // Menampilkan pesan kesalahan dari server
                            });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Your data has been deleted',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.href = "<?= base_url('reimbust') ?>";
                        });
                    }
                });
            }
        });
    }
</script>