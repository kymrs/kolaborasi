<style>
    .ui-datepicker {
        z-index: 1060 !important;
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
                        <a class="btn btn-primary btn-sm" href="<?= base_url('pu_invoice/add_form') ?>">
                            <i class="fa fa-plus"></i>&nbsp;Add Data
                        </a>
                    <?php } ?>
                    <div class="d-flex align-items-center">
                        <label for="appFilter" class="mr-2 mb-0">Filter:</label>
                        <select id="appFilter" name="appFilter" class="form-control form-control-sm">
                            <option value="" selected>Show all....</option>
                            <option value="pending" selected>Pending</option>
                            <option value="down payment">Down Payment</option>
                            <option value="pembayaran">Pembayaran</option>
                            <option value="pelunasan">Pelunasan</option>
                        </select>
                    </div>
                </div>


                <div class="card-body">
                    <table id="table" class="table table-bordered table-striped" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Action</th>
                                <th>Kode Invoice</th>
                                <th>Nama</th>
                                <th>Status</th>
                                <th>Tanggal Invoice</th>
                                <th>Tanggal Tempo</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Action</th>
                                <th>Kode Invoice</th>
                                <th>Nama</th>
                                <th>Status</th>
                                <th>Tanggal Invoice</th>
                                <th>Tanggal Tempo</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL UNTUK MELAKUKAN PAYMENT -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="paymentModalLabel">Pembayaran Invoice</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="paymentForm">
                        <input type="hidden" name="invoice_id" id="payment_invoice_id">
                        <div class="form-group">
                            <label for="tanggal_pembayaran">Tanggal Pembayaran</label>
                            <div class="input-group">
                                <input type="text" class="form-control tanggal_pembayaran" id="tanggal_pembayaran" name="tanggal_pembayaran" autocomplete="off" style="cursor: pointer" readonly>
                                <div class="input-group-append">
                                    <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tgl_tempo">Tanggal Tempo</label>
                            <div class="input-group">
                                <input type="text" class="form-control tgl_tempo" id="tgl_tempo" name="tgl_tempo" autocomplete="off" style="cursor: pointer" readonly>
                                <div class="input-group-append">
                                    <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nominal_dibayar">Jumlah Pembayaran</label>
                            <input type="text" class="form-control" id="nominal_dibayar" name="nominal_dibayar" required>
                        </div>
                        <div class="form-group">
                            <label for="status_pembayaran">Status Pembayaran</label>
                            <select name="status_pembayaran" id="status_pembayaran" class="form-control" required>
                                <option value="" selected disabled>-- Pilih Status Pembayaran --</option>
                                <option value="down payment">Down Payment</option>
                                <option value="pembayaran">Pembayaran</option>
                                <option value="pelunasan">Pelunasan</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Bayar</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>

<script type="text/javascript">
    var table;

    function showPaymentModal(invoiceId) {
        $('#payment_invoice_id').val(invoiceId);
        $('#paymentForm')[0].reset();
        $('#paymentModal').modal('show');
    }

    $('#tanggal_pembayaran').datepicker({
        dateFormat: 'yy-mm-dd',
    });
    $('#tgl_tempo').datepicker({
        dateFormat: 'yy-mm-dd',
    });

    // METHOD POST MENAMPILKAN DATA KE DATA TABLE
    $(document).ready(function() {

        $('#nominal_dibayar').on('input', function() {
            // Ambil nilai input
            let value = $(this).val();

            // Hapus semua karakter yang bukan angka
            value = value.replace(/[^0-9]/g, '');

            // Format ke Rupiah
            let formatted = new Intl.NumberFormat('id-ID').format(value);

            // Set nilai input dengan format Rupiah
            $(this).val(formatted);
        });

        $('#paymentForm').on('submit', function(e) {
            e.preventDefault(); // Mencegah pengiriman form default

            // Ambil data dari form
            var invoiceId = $('#payment_invoice_id').val();
            var nominalDibayar = $('#nominal_dibayar').val().replace(/\./g, ''); // Menghapus titik dari format Rupiah
            var statusPembayaran = $('#status_pembayaran').val();
            var tanggalPembayaran = $('#tanggal_pembayaran').val();
            var tglTempo = $('#tgl_tempo').val();


            // Kirim data ke server menggunakan AJAX
            $.ajax({
                url: "<?php echo site_url('pu_invoice/payment/') ?>" + invoiceId,
                type: "POST",
                data: {
                    nominal_dibayar: nominalDibayar,
                    status_pembayaran: statusPembayaran,
                    tanggal_pembayaran: tanggalPembayaran,
                    tgl_tempo: tglTempo
                },
                dataType: "JSON",
                success: function(data) {
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Pembayaran berhasil dilakukan',
                        showConfirmButton: false,
                        timer: 1500
                    }).then((result) => {
                        location.href = "<?= base_url('pu_invoice') ?>";
                    })
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                }
            });
        });

        // Set active tab on page load
        const activeTab = sessionStorage.getItem('activeTab');

        // Cek apakah tab approval ada
        const approvalTabExists = $('#employeeTab').length > 0;

        // console.log(activeTab)

        // if (activeTab && (activeTab == 'employee' || approvalTabExists)) {
        //     $('.nav-tabs .nav-link').removeClass('active');
        //     $(`.nav-tabs .nav-link[data-tab="${activeTab}"]`).addClass('active');
        //     // console.log('labubu');
        //     // You can load content for the active tab here if needed
        // } else {
        //     // Default to the "User" tab if session storage is empty or approval tab doesn't exist
        //     $('.nav-tabs .nav-link').removeClass('active');
        //     $('#personalTab').addClass('active');
        //     // console.log('ladada');
        // }

        $('.collapse-item').on('click', function(e) {
            localStorage.removeItem('appFilterStatus'); // Hapus filter yang tersimpan
            // localStorage.removeItem('activeTab'); // Hapus filter yang tersimpan
        })

        // Tab click event
        // $('.nav-tabs .nav-link').on('click', function() {
        //     const tab = $(this).data('tab');
        //     sessionStorage.setItem('activeTab', tab);
        //     $('.nav-tabs .nav-link').removeClass('active');
        //     $(this).addClass('active');
        //     table.ajax.reload(); // Muat ulang data di DataTable saat tab berubah
        // });

        // Cek apakah ada nilai filter yang tersimpan di localStorage
        var savedFilter = localStorage.getItem('appFilterStatus');
        if (savedFilter) {
            $('#appFilter').val(savedFilter).change(); // Set filter dengan nilai yang tersimpan
        }

        table = $('#table').DataTable({
            "responsive": false,
            "scrollX": true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('pu_invoice/get_list') ?>",
                "type": "POST",
                "data": function(d) {
                    d.status = $('#appFilter').val(); // Tambahkan parameter status ke permintaan server
                }
            },
            // "language": {
            //     "infoFiltered": ""
            // },
            "columnDefs": [{
                    "targets": [2, 3, 4, 6],
                    "className": 'dt-head-nowrap'
                },
                {
                    "targets": [1],
                    "className": 'dt-body-nowrap'
                }, {
                    "targets": [0, 1],
                    "orderable": false,
                },
            ]
        });

        // Simpan nilai filter ke localStorage setiap kali berubah
        $('#appFilter').on('change', function() {
            localStorage.setItem('appFilterStatus', $(this).val());
            table.ajax.reload(); // Muat ulang DataTables dengan filter baru
            console.log($('#appFilter'));
        });

        // Event listener untuk nav tabs
        // $('.nav-tabs a').on('click', function(e) {
        //     e.preventDefault();
        //     $('.nav-tabs a').removeClass('active'); // Hapus kelas aktif dari semua tab
        //     $(this).addClass('active'); // Tambahkan kelas aktif ke tab yang diklik

        //     table.ajax.reload(); // Muat ulang data di DataTable saat tab berubah
        // });

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
                    url: "<?php echo site_url('pu_invoice/delete/') ?>" + id,
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
                            location.href = "<?= base_url('pu_invoice') ?>";
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