<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $titleview ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <?php if ($add == 'Y') { ?>
                        <a class="btn btn-primary btn-sm" href="<?= base_url('qbg_invoice/add_form') ?>">
                            <i class="fa fa-plus"></i>&nbsp;Add Data
                        </a>
                    <?php } ?>

                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <select id="filter_status" class="form-control" style="cursor: pointer;">
                                <option value="unpaid" selected>Unpaid</option>
                                <option value="paid">Paid</option>
                            </select>
                        </li>
                    </ul>
                </div>
                <!-- NAV TABS -->

                <div class="card-body">
                    <table id="table" class="table table-bordered table-striped" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Action</th>
                                <th>Tanggal Invoice</th>
                                <th>Kode Invoice</th>
                                <th>Nama Tujuan</th>
                                <th>Alamat Tujuan</th>
                                <th>Tanggal Tempo</th>
                                <th>Dibuat Pada</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Action</th>
                                <th>Tanggal Invoice</th>
                                <th>Kode Invoice</th>
                                <th>Nama Tujuan</th>
                                <th>Alamat Tujuan</th>
                                <th>Tanggal Tempo</th>
                                <th>Dibuat Pada</th>
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
                "url": "<?php echo site_url('qbg_invoice/get_list') ?>",
                "type": "POST",
                "data": function(d) {
                    d.status = $('#filter_status').val(); // Tambahkan parameter status ke permintaan server
                }
            },
            // "language": {
            //     "infoFiltered": ""
            // },
            "columnDefs": [{
                    "targets": [2, 3, 4, 5, 6, 7],
                    "className": 'dt-head-nowrap'
                },
                {
                    "targets": [2, 3, 4, 5, 6, 7],
                    "className": 'dt-body-nowrap'
                }, {
                    "targets": [0, 1],
                    "orderable": false,
                },
            ]
        });
    });

    // Cek apakah ada nilai filter yang tersimpan di localStorage
    var savedFilter = localStorage.getItem('filterStatus');
    if (savedFilter) {
        $('#filter_status').val(savedFilter).change(); // Set filter dengan nilai yang tersimpan
    }

    // Simpan nilai filter ke localStorage setiap kali berubah
    $('#filter_status').on('change', function() {
        localStorage.setItem('filterStatus', $(this).val());
        table.ajax.reload(); // Muat ulang DataTables dengan filter baru
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
                    url: "<?php echo site_url('qbg_invoice/delete/') ?>" + id,
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
                            location.href = "<?= base_url('qbg_invoice') ?>";
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