<style>
    .ui-datepicker {
        z-index: 1060 !important;
    }

    .btn-group:hover .dropdown-menu {
        display: block;
        margin-top: 0;
        z-index: 1060;
        /* remove spacing if any */
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
                            <option value="1">Belum Lunas</option>
                            <option value="0">Lunas</option>
                        </select>
                    </div>
                </div>


                <div class="card-body">
                    <table id="table" class="table table-bordered table-striped" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th class="action-column">Action</th>
                                <th>Kode Invoice</th>
                                <th>Nama</th>
                                <th>Tanggal Invoice</th>
                                <th>Jatuh Tempo</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th class="action-column">Action</th>
                                <th>Kode Invoice</th>
                                <th>Nama</th>
                                <th>Tanggal Invoice</th>
                                <th>Jatuh Tempo</th>
                                <th>Status</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="floatingDropdown" class="dropdown-menu" style="position:absolute; display:none; z-index:9999;">
        <a class="dropdown-item" href="pu_invoice/read_invoice" id="lihatInvoice" target="_blank"><i class="fas fa-file-invoice"></i> Lihat Invoice</a>
        <a class="dropdown-item" href="pu_invoice/read_kwitansi" id="lihatKwitansi" target="_blank"><i class="fas fa-receipt"></i> Lihat Kwitansi</a>
    </div>
    <div id="floatingPrintDropdown" class="dropdown-menu" style="position:absolute; display:none; z-index:9999;">
        <a class="dropdown-item" href="#" id="cetakInvoice" target="_blank"><i class="fas fa-file-invoice"></i> Cetak Invoice</a>
        <a class="dropdown-item" href="#" id="cetakKwitansi" target="_blank"><i class="fas fa-receipt"></i> Cetak Kwitansi</a>
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
                        <input type="hidden" name="invoice_id" id="payment_invoice_id" value="">
                        <?php if ($edit == 'Y') { ?>
                            <div class="form-group">
                                <label for="tgl_update_pembayaran">Tanggal Pembayaran ini digunakan untuk melakukan update</label>
                                <select class="form-control" id="tgl_update_pembayaran" name="tgl_update_pembayaran">
                                    <option value="" selected disabled>-- Pilih Tanggal Invoice --</option>
                                </select>
                            </div>
                        <?php } ?>
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
                            <label for="nominal_dibayar">Jumlah Pembayaran</label>
                            <input type="text" class="form-control" id="nominal_dibayar" name="nominal_dibayar" required>
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <div class="input-group">
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="2" style="resize: vertical;" autocomplete="off" required></textarea>
                            </div>
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
        console.log(invoiceId);
        $('#paymentForm')[0].reset();
        $('#payment_invoice_id').val(invoiceId);
        $.ajax({
            url: "<?php echo site_url('pu_invoice/get_kwitansi_dates/') ?>" + invoiceId,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                // Fungsi untuk format tanggal Indonesia
                function formatTanggalIndo(tanggal) {
                    const bulanIndo = [
                        "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
                    ];
                    const parts = tanggal.split('-');
                    if (parts.length !== 3) return tanggal;
                    const tahun = parts[0];
                    const bulan = parseInt(parts[1], 10) - 1;
                    const hari = parts[2];
                    return `${hari} ${bulanIndo[bulan]} ${tahun}`;
                }

                $('#tgl_update_pembayaran').empty();
                $('#tgl_update_pembayaran').append('<option value="" selected disabled>-- Pilih Tanggal Invoice --</option>');
                if (Array.isArray(data) && data.length > 0) {
                    $.each(data, function(index, item) {
                        // Format tanggal_pembayaran ke format Indonesia
                        let tglIndo = formatTanggalIndo(item.tanggal_pembayaran);
                        $('#tgl_update_pembayaran').append('<option value="' + item.id + '">' + tglIndo + '</option>');
                    });
                } else {
                    $('#tgl_update_pembayaran').append('<option value="" disabled>Tidak ada data</option>');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error fetching invoice dates');
            }
        });
        $('#paymentModal').modal('show');
    }

    $('#tgl_update_pembayaran').on('change', function() {
        var selectedValue = $(this).val();
        if (selectedValue) {
            // Set nilai input tanggal_pembayaran dengan tanggal yang dipilih
            var selectedText = $(this).find('option:selected').text();
            $.ajax({
                url: "<?php echo site_url('pu_invoice/edit_data_kwitansi/') ?>" + selectedValue,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#tanggal_pembayaran').val(data.tanggal_pembayaran);
                    $('#status_pembayaran').val(data.status_pembayaran);
                    // Format nominal_dibayar dengan tanda titik sebagai pemisah ribuan
                    let nominal = data.nominal_dibayar ? data.nominal_dibayar.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") : '';
                    $('#nominal_dibayar').val(nominal);
                    // console.log(data);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error fetching kwitansi data');
                }
            });
        } else {
            $('#tanggal_pembayaran').val('');
        }
    });

    $('#tanggal_pembayaran').datepicker({
        dateFormat: 'yy-mm-dd',
    });
    $('#tgl_tempo').datepicker({
        dateFormat: 'yy-mm-dd',
    });

    // METHOD POST MENAMPILKAN DATA KE DATA TABLE
    $(document).ready(function() {

        const $dropdown = $('#floatingDropdown');
        const $printDropdown = $('#floatingPrintDropdown');

        // --- Untuk Read ---
        $('#table').on('click', '.show-dropdown', function(e) {
            e.preventDefault();
            const $btn = $(this);
            const id = $btn.data('id');
            const is_active = $btn.data('is_active');
            const offset = $btn.offset();

            $('#lihatInvoice').attr('href', 'pu_invoice/read_invoice/' + id);

            // Tampilkan/hidden kwitansi berdasarkan is_active (dari data attribute, bukan PHP)
            if (is_active == 0) {
                $('#lihatKwitansi').show().attr('href', 'pu_invoice/read_kwitansi/' + id);
            } else {
                $('#lihatKwitansi').hide();
            }

            $dropdown.css({
                top: offset.top + $btn.outerHeight(),
                left: offset.left,
                display: 'block'
            });

            $printDropdown.hide(); // pastikan yang lain disembunyikan
        });

        // --- Untuk Print ---
        $('#table').on('click', '.show-print-dropdown', function(e) {
            e.preventDefault();
            const $btn = $(this);
            const id = $btn.data('id');
            const is_active = $btn.data('is_active');
            const offset = $btn.offset();

            $('#cetakInvoice').attr('href', 'pu_invoice/generate_pdf_invoice/' + id);

            if (is_active == 0) {
                $('#cetakKwitansi').show().attr('href', 'pu_invoice/generate_pdf_kwitansi/' + id);
            } else {
                $('#cetakKwitansi').hide();
            }

            $printDropdown.css({
                top: offset.top + $btn.outerHeight(),
                left: offset.left,
                display: 'block'
            });

            $dropdown.hide(); // pastikan yang lain disembunyikan
        });

        // --- Klik di luar dropdown untuk menyembunyikan ---
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.show-dropdown, #floatingDropdown, .show-print-dropdown, #floatingPrintDropdown').length) {
                $dropdown.hide();
                $printDropdown.hide();
            }
        });

        // Sembunyikan saat klik di luar
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.show-dropdown, #floatingDropdown').length) {
                $dropdown.hide();
            }
        });

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

            // Cek apakah ada nilai dari input tgl_update_pembayaran
            var selectedValue = $('#tgl_update_pembayaran').val();
            var isUpdate = selectedValue !== null && selectedValue !== "";

            console.log('selectedValue:', '"' + selectedValue + '"');
            console.log('isUpdate:', isUpdate);

            // Tentukan URL berdasarkan kondisi tersebut
            var actionUrl = isUpdate ?
                "<?php echo site_url('pu_invoice/update_kwitansi/') ?>" :
                "<?php echo site_url('pu_invoice/add_kwitansi/') ?>";

            // Kirim data ke server menggunakan AJAX
            $.ajax({
                url: actionUrl,
                type: "POST",
                data: $('#paymentForm').serialize(),
                dataType: "JSON",
                success: function(data) {
                    if (data.status) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: isUpdate ? 'Pembayaran berhasil diupdate' : 'Pembayaran berhasil dilakukan',
                            showConfirmButton: false,
                            timer: 1500
                        }).then((result) => {
                            location.href = "<?= base_url('pu_invoice') ?>";
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: data.message,
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error adding / updating data');
                }
            });
        });


        // Set active tab on page load
        const activeTab = sessionStorage.getItem('activeTab');

        // Cek apakah tab approval ada
        const approvalTabExists = $('#employeeTab').length > 0;

        $('.collapse-item').on('click', function(e) {
            localStorage.removeItem('appFilterStatus'); // Hapus filter yang tersimpan
            // localStorage.removeItem('activeTab'); // Hapus filter yang tersimpan
        })

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
                    "targets": [],
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