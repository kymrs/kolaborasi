<style>
    #appFilter {
        border: 1px solid #ccc;
        padding: 5px;
        border-radius: 4px;
    }
</style>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $titleview ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <?php if ($add == 'Y') { ?>
                        <a class="btn btn-primary btn-sm" href="<?= base_url('penawaran_la_pu/add_form') ?>">
                            <i class="fa fa-plus"></i>&nbsp;Add Data
                        </a>
                    <?php } ?>
                </div>

                <div class="card-body">
                    <table id="table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Action</th>
                                <th>Kode Pelayanan</th>
                                <th>Pelanggan</th>
                                <th>Produk</th>
                                <th>Tanggal Dokumen</th>
                                <th>Tanggal Berlaku</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Action</th>
                                <th>Kode Pelayanan</th>
                                <th>Pelanggan</th>
                                <th>Produk</th>
                                <th>Tanggal Dokumen</th>
                                <th>Tanggal Berlaku</th>
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

    // METHOD POST MENAMPILKAN DATA KE DATA TABLE
    $(document).ready(function() {
        table = $('#table').DataTable({
            "responsive": false,
            "scrollX": true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('penawaran_la_pu/get_list') ?>",
                "type": "POST",
                "data": function(d) {
                    d.status = $('#appFilter').val(); // Tambahkan parameter status ke permintaan server
                    d.tab = $('.nav-tabs .nav-link.active').data('tab'); // Tambahkan parameter tab ke permintaan server
                }
            },
            // "language": {
            //     "infoFiltered": ""
            // },
            "columnDefs": [{
                    "targets": [5, 6],
                    "className": 'dt-head-nowrap'
                },
                {
                    "targets": [1, 3],
                    "className": 'dt-body-nowrap'
                }, {
                    "targets": [0, 1],
                    "orderable": false,
                },
            ]
        });
    });

    // MENGHAPUS DATA MENGGUNAKAN METHODE POST JQUERY
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
                    url: "<?php echo site_url('penawaran_la_pu/delete/') ?>" + id,
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
                            location.href = "<?= base_url('penawaran_la_pu') ?>";
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