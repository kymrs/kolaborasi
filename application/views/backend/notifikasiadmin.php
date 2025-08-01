<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $titleview ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">


                <div class="card-body">
                    <table id="table" class="table table-bordered table-striped" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Menu</th>
                                <th>Submenu</th>
                                <th>Notifikasi</th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Menu</th>
                                <th>Submenu</th>
                                <th>Notifikasi</th>
                                <th>Detail</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content shadow-sm">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="detailModalLabel">Detail Notifikasi</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Tutup">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="table-responsive p-3">
                <table class="table table-bordered small">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th style="width: 120px;">Kode</th>
                            <th style="width: 130px;">Approval Pertama</th>
                            <th style="width: 130px;">Approval Kedua</th>
                            <th style="width: 130px;">Approval Ketiga</th>
                        </tr>
                    </thead>
                    <tbody id="tabel-detail-notifikasi">
                        <!-- isinya di-append lewat JS -->
                    </tbody>
                </table>
            </div>


            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
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
                "url": "<?php echo site_url('notifikasiadmin/get_list') ?>",
                "type": "POST",
            },
            "columns": [{
                    "data": "no"
                },
                {
                    "data": "menu"
                },
                {
                    "data": "submenu"
                },
                {
                    "data": "jumlah"
                }, {
                    "data": "detail",
                    "orderable": false
                }
            ],
            "columnDefs": [{
                    "targets": [0],
                    "orderable": false
                },
                {
                    "targets": [0, 1, 2, 3],
                    "className": 'dt-head-nowrap dt-body-nowrap'
                }
            ]
        });

        function capitalizeFirst(str) {
            if (!str) return 'none';
            return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
        }


        // Event delegasi agar bekerja untuk tombol yang di-generate oleh DataTables
        $('#table').on('click', '.detail-btn', function() {
            $('#tabel-detail-notifikasi').html('');
            const nama_tbl = $(this).data('nama_tbl');

            $.ajax({
                url: "<?php echo site_url('notifikasiadmin/get_link/') ?>" + nama_tbl,
                type: "POST",
                dataType: "JSON",
                success: function(data) {
                    console.log(data);

                    if (!data.data.length) {
                        $('#detailModalTable').html('<tr><td colspan="6">Tidak ada data ditemukan</td></tr>');
                        return;
                    }

                    let rows = '';

                    data.data.forEach((item, index) => {
                        $app_status = '';
                        $app2_status = '';
                        $app4_status = '';

                        if (item.app_status == 'waiting') {
                            app_status = `<span class="badge bg-danger">Waiting</span>`;
                        } else if (item.app_status == 'approved') {
                            app_status = `<span class="badge bg-success">Approved</span>`;
                        }

                        if (item.app2_status == 'waiting') {
                            app2_status = `<span class="badge bg-danger">Waiting</span>`;
                        } else if (item.app2_status == 'approved') {
                            app2_status = `<span class="badge bg-success">Approved</span>`;
                        }

                        if (item.app4_status && item.app_status == 'waiting') {
                            app4_status = `<span class="badge bg-danger">Waiting</span>`;
                        } else if (item.app4_status && item.app_status == 'approved') {
                            app4_status = `<span class="badge bg-success">Approved</span>`;
                        } else {
                            app4_status = `<span class="badge bg-primary">None</span>`
                        }
                        rows += `
                    <tr class="bg-info text-white">
                        <td>${index + 1}</td>
                        <td>${capitalizeFirst(item.kode_deklarasi || item.kode_prepayment || item.kode_reimbust)}</td>
                        <td>${capitalizeFirst(item.app_name)} ${app_status}</td>
                        <td>${capitalizeFirst(item.app2_name)} ${app2_status}</td>
                        <td>${item.app4_name? capitalizeFirst(item.app4_name) : '' } ${app4_status}</td>
                    </tr>
                    `;
                    });

                    $('#tabel-detail-notifikasi').append(rows);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error');
                }
            });
        });


    });
</script>