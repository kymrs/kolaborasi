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

                    <ul class="nav nav-tabs" style="border-bottom: none;">
                        <li class="nav-item">
                            <select id="filter_status" class="form-control" style="cursor: pointer;">
                                <option value="all">All</option>
                                <option value="unpaid" selected>Unpaid</option>
                                <option value="paid">Paid</option>
                            </select>
                        </li>   
                        <li class="nav-item ml-2">
                            <div id="invoice_totals" class="text-muted" style="font-size: 0.9rem; line-height: 1.25; display: grid; grid-template-columns: 64px 10px minmax(110px, 1fr) 14px 44px 10px minmax(110px, 1fr); grid-template-rows: auto auto; column-gap: 4px; row-gap: 2px; align-items: center;">
                                <div class="invoice_row_paid" sty   le="grid-column: 1; grid-row: 1;">Paid</div>
                                <div class="invoice_row_paid" style="grid-column: 2; grid-row: 1;">:</div>
                                <div id="invoice_total_paid" class="invoice_row_paid" style="grid-column: 3; grid-row: 1; text-align: right; white-space: nowrap;">-</div>

                                <div class="invoice_row_unpaid" style="grid-column: 1; grid-row: 2;">Unpaid</div>
                                <div class="invoice_row_unpaid" style="grid-column: 2; grid-row: 2;">:</div>
                                <div id="invoice_total_unpaid" class="invoice_row_unpaid" style="grid-column: 3; grid-row: 2; text-align: right; white-space: nowrap;">-</div>

                                <div class="invoice_row_total" style="grid-column: 5; grid-row: 1 / span 2;">Total</div>
                                <div class="invoice_row_total" style="grid-column: 6; grid-row: 1 / span 2;">:</div>
                                <div id="invoice_total_all" class="invoice_row_total" style="grid-column: 7; grid-row: 1 / span 2; text-align: right; white-space: nowrap;">-</div>
                            </div>
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

    function formatRupiah(value) {
        var number = Number(value || 0);
        return 'Rp. ' + number.toLocaleString('id-ID');
    }

    function applyInvoiceTotalsVisibility(status) {
        var filter = status || 'all';
        if (filter === 'paid') {
            $('.invoice_row_paid').show();
            $('.invoice_row_unpaid').hide();
            $('.invoice_row_total').hide();
            $('.invoice_row_unpaid').css('grid-row', 2);
        } else if (filter === 'unpaid') {
            $('.invoice_row_paid').hide();
            $('.invoice_row_unpaid').show();
            $('.invoice_row_total').hide();
            $('.invoice_row_unpaid').css('grid-row', 1);
        } else {
            $('.invoice_row_paid').show();
            $('.invoice_row_unpaid').show();
            $('.invoice_row_total').show();
            $('.invoice_row_unpaid').css('grid-row', 2);
        }
    }

    function loadInvoiceTotals() {
        $.ajax({
            url: "<?php echo site_url('qbg_invoice/get_totals') ?>",
            type: "POST",
            dataType: "JSON",
            success: function(res) {
                var paid = Number((res && res.total_paid) ? res.total_paid : 0);
                var unpaid = Number((res && res.total_unpaid) ? res.total_unpaid : 0);
                var total = paid + unpaid;
                $('#invoice_total_paid').text(formatRupiah(paid));
                $('#invoice_total_unpaid').text(formatRupiah(unpaid));
                $('#invoice_total_all').text(formatRupiah(total));
            },
            error: function() {
                $('#invoice_total_paid').text('-');
                $('#invoice_total_unpaid').text('-');
                $('#invoice_total_all').text('-');
            }
        });
    }

    // METHOD POST MENAMPILKAN DATA KE DATA TABLE
    $(document).ready(function() {

        // Restore filter dari localStorage (kalau ada) sebelum DataTables pertama kali load
        var savedFilter = localStorage.getItem('filterStatus');
        if (savedFilter) {
            $('#filter_status').val(savedFilter);
        }

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

        // Simpan nilai filter ke localStorage setiap kali berubah
        $('#filter_status').on('change', function() {
            var status = $(this).val();
            localStorage.setItem('filterStatus', status);
            applyInvoiceTotalsVisibility(status);
            table.ajax.reload();
            loadInvoiceTotals();
        });

        // Tampilkan total Paid/Unpaid saat halaman dibuka
        loadInvoiceTotals();

        // Tampilkan/sembunyikan bagian totals sesuai filter awal
        applyInvoiceTotalsVisibility($('#filter_status').val());
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