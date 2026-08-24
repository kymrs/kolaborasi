<style>
    .btn-paid:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        background-color: #28a745;
    }
</style>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $titleview ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 align-items-center">
                    <a class="btn btn-primary btn-sm" href="<?= base_url('mac_invoice/add_form') ?>">
                        <i class="fa fa-plus"></i>&nbsp;Add Data
                    </a>
                    <!-- <a class="btn btn-primary btn-sm" href="<?= base_url('mac_invoice/rekap_insentif') ?>">
                        <i class="fa fa-plus"></i>&nbsp;Rekap Insentif
                    </a> -->
                    <!-- <a class="btn btn-success btn-sm" id="btn-export-excel" style="float: right; padding: 4px 8px; margin-left: 8px;" href="#">
                        <i class="fa fa-file-excel" style="margin-right: 6px"></i>Export
                    </a> -->
                    <select id="filter-status" class="form-control form-control-sm" style="width: 125px; background-color: #242d4a; color: #fff; float: right; margin-left: 8px;">
                        <option value="">Semua Status</option>
                        <option value="waiting">Waiting</option>
                        <option value="approved">Approved</option>
                        <option value="revised">Revised</option>
                        <option value="rejected">Rejected</option>
                    </select>
                    <label style="float: right; position: relative; top: 3px; margin-left: 10px">Approval :</label>
                    <select id="payment-status" class="form-control form-control-sm" style="width: 125px; background-color: #242d4a; color: #fff; float: right; margin-left: 8px;">
                        <option value="">Semua Status</option>
                        <option value="unpaid">Unpaid</option>
                        <option value="paid">Paid</option>
                        <option value="partial">Partial</option>
                    </select>
                    <label style="float: right; position: relative; top: 3px; margin-left: 10px;">Payment :</label>
                    <input type="text" id="filter-date-end" class="form-control form-control-sm" placeholder="Sampai" autocomplete="off" style="width: 110px; float: right; cursor: not-allowed; margin-left: 8px; padding: 0 5px;" disabled>
                    <span style="float: right; margin-left: 7px; position: relative; top: 3px;">-</span>
                    <input type="text" id="filter-date-start" class="form-control form-control-sm" placeholder="Dari" autocomplete="off" style="width: 110px; float: right; cursor: pointer; margin-left: 8px;">
                    <span style="float: right; position: relative; top: 3px;">Set Periode :</span>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table id="table-list" class="table table-bordered table-striped display nowrap w-100 mb-4">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th style="width: 120px;">Action</th>
                                    <th>Payment Status</th>
                                    <th>Invoice Number</th>
                                    <th>Nama Customer</th>
                                    <th>Nopol</th>
                                    <th>Service Date</th>
                                    <th>Grand Total</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Created By</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th style="width: 120px;">Action</th>
                                    <th>Payment Status</th>
                                    <th>Invoice Number</th>
                                    <th>Nama Customer</th>
                                    <th>Nopol</th>
                                    <th>Service Date</th>
                                    <th>Grand Total</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Created By</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalApprove" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Status Invoice</h5>
                <!-- <button type="button" class="btn-close" data-bs-dismiss="modal"></button> -->
            </div>

            <div class="modal-body">
                <input type="hidden" id="approve_id">

                <div class="mb-3">
                    <label>Status Approved <span class="text-danger">*</span></label>
                    <select class="form-control" id="app_status">
                        <option value="waiting" hidden>-- Pilih Status --</option>
                        <option value="approved">Approved</option>
                        <option value="revised">Revised</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    Close
                </button>
                <button type="button" class="btn btn-primary" id="btn-submit-approve" onclick="submit_approve()" disabled>
                    Submit
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL PAYMENT CICILAN -->
<div class="modal fade" id="paymentDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-money-bill-wave"></i>
                    <span id="payment-modal-title-text">Detail Pembayaran</span>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">

                <!-- INFO INVOICE -->
                <div class="row mb-3">
                    <div class="col-md-8">
                        <div class="alert alert-light border mb-0" id="payment-invoice-info"></div>
                    </div>
                    <div class="col-md-4 text-right">
                        <div class="small text-muted">Status Pembayaran</div>
                        <span id="payment-status-badge" class="badge" style="font-size:13px; padding:6px 12px;"></span>
                    </div>
                </div>

                <!-- PROGRESS PEMBAYARAN -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <small>Terbayar: <strong id="info-terbayar">Rp 0</strong></small>
                        <small>Sisa: <strong id="info-sisa" class="text-danger">Rp 0</strong></small>
                    </div>
                    <div class="progress" style="height:10px;">
                        <div class="progress-bar bg-success" id="progress-bayar"
                             role="progressbar" style="width:0%"></div>
                    </div>
                </div>

                <!-- DAFTAR CICILAN -->
                <h6 class="font-weight-bold mb-2">Riwayat Pembayaran</h6>
                <div class="table-responsive mb-3">
                    <table class="table table-bordered table-sm">
                        <thead style="background:#242d4a; color:white;">
                            <tr>
                                <th width="5%">No</th>
                                <th width="13%">Tgl Bayar</th>
                                <th width="18%">Nominal</th>
                                <th width="12%">Metode</th>
                                <th width="22%">Keterangan</th>
                                <th width="15%">Bukti</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-cicilan">
                            <tr>
                                <td colspan="7" class="text-center text-muted">Belum ada pembayaran</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- FORM TAMBAH CICILAN -->
                <div id="form-tambah-cicilan-wrapper">
                    <h6 class="font-weight-bold mb-2">
                        Tambah Pembayaran
                    </h6>
                    <form id="form-cicilan" enctype="multipart/form-data">
                        <input type="hidden" name="invoice_id" id="cicilan_invoice_id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Bayar <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm datepicker-cicilan"
                                           name="tgl_bayar" id="cicilan_tgl_bayar"
                                           placeholder="DD-MM-YYYY" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nominal <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="text" class="form-control" name="nominal"
                                               id="cicilan_nominal" placeholder="0" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="col-md-4">
                                <div class="form-group">
                                    <label>Metode Pembayaran <span class="text-danger">*</span></label>
                                    <select class="form-control form-control-sm" name="metode" id="cicilan_metode">
                                        <option value="">-- Pilih --</option>
                                        <option value="Transfer">Transfer</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>
                            </div> -->
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Keterangan</label>
                                    <input type="text" class="form-control form-control-sm"
                                           name="keterangan" placeholder="Keterangan">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Bukti Transfer <span class="text-danger">*</span></label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="bukti_cicilan"
                                               id="bukti_cicilan" accept=".jpg,.jpeg,.png,.pdf">
                                        <label class="custom-file-label" for="bukti_cicilan">
                                            Pilih file... (maks 5MB)
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary btn-sm" id="btn-save-cicilan">
                    <i class="fa fa-save"></i> Save
                </button>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>

<script type="text/javascript">
    var table;
    function approve_data(id, current_status)
    {
        $('#approve_id').val(id);

        if (current_status) {
            $('#app_status').val(current_status);
        } else {
            $('#app_status').val('');
        }

        $('#modalApprove').modal('show');
    }

    $(document).ready(function() {
        var filterStatus    = '';
        var filterPayment   = '';
        var filterDateStart = '';
        var filterDateEnd   = '';

        table = $('#table-list').DataTable({
            "responsive": true,
            "scrollX": true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('mac_invoice/get_list') ?>",
                "type": "POST",
                "data": function(d) {
                    d.filter_status     = filterStatus;
                    d.filter_payment    = filterPayment;
                    d.filter_date_start = filterDateStart;
                    d.filter_date_end   = filterDateEnd;
                }
            },
            "language": { "infoFiltered": "" },
            "columnDefs": [
                { "targets": [0, 1], "orderable": false },
                { "targets": [1], "className": 'dt-body-nowrap' }
            ],
            // ========== CEK DATA SETELAH RENDER ==========
            "drawCallback": function(settings) {
                var totalData = settings.fnRecordsDisplay();
                if (totalData > 0) {
                    $('#btn-export-excel').removeClass('disabled').css('opacity', '1').css('pointer-events', 'auto');
                } else {
                    $('#btn-export-excel').addClass('disabled').css('opacity', '0.5').css({'pointer-events': 'none'});
                }
            }
        });

        // Set disabled saat pertama load
        $('#btn-export-excel').addClass('disabled').css('opacity', '0.5').css({'pointer-events': 'none'});

        $('#filter-date-start').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            onSelect: function(selectedDate) {
                filterDateStart = selectedDate;

                // Enable dan set minDate filter-date-end
                $('#filter-date-end')
                    .prop('disabled', false)
                    .css('cursor', 'pointer')
                    .datepicker('option', 'minDate', selectedDate);

                // Reset jika tanggal akhir lebih kecil
                var endVal = $('#filter-date-end').val();
                if (endVal && endVal < selectedDate) {
                    $('#filter-date-end').val('');
                    filterDateEnd = '';
                }

                table.ajax.reload();
            }
        });

        $('#filter-date-end').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            minDate: 0, // tidak bisa pilih masa lalu secara default
            onSelect: function(selectedDate) {
                filterDateEnd = selectedDate;
                table.ajax.reload();
            }
        });

        $('#filter-status').on('change', function() {
            filterStatus = $(this).val();
            table.ajax.reload();
        });

        $('#payment-status').on('change', function() {
            filterPayment = $(this).val();
            table.ajax.reload();
        });

        $('#btn-export-excel').on('click', function(e) {
            e.preventDefault();

            var url    = "<?= base_url('mac_invoice/export_excel') ?>";
            var params = [];

            if (filterDateStart) params.push('date_start=' + filterDateStart);
            if (filterDateEnd)   params.push('date_end='   + filterDateEnd);
            if (filterStatus)    params.push('status='     + filterStatus);
            if (filterPayment)   params.push('payment='   + filterPayment);
            if (params.length) url += '?' + params.join('&');

            // Debug — hapus setelah dicek
            console.log('Export URL:', url);

            window.location.href = url;
        });

        // ===== PAYMENT MODAL =====

        var currentInvoiceId = null;

        // Init datepicker untuk cicilan
        $(document).on('focus', '.datepicker-cicilan', function() {
            if (!$(this).hasClass('hasDatepicker')) {
                $(this).datepicker({ dateFormat: 'dd-mm-yy', changeMonth: true, changeYear: true });
                $(this).datepicker('show');
            }
        });

        // Label file bukti
        $(document).on('change', '#bukti_cicilan', function() {
            var file = this.files[0];
            if (!file) return;
            if (file.size > 5 * 1024 * 1024) {
                Swal.fire({ icon: 'warning', title: 'File terlalu besar', text: 'Maksimal 5 MB.' });
                $(this).val('');
                $(this).next('.custom-file-label').text('Pilih file...');
                return;
            }
            $(this).next('.custom-file-label').text(file.name);
        });

        // Buka modal
        $(document).on('click', '[data-target="#paymentDetailModal"]', function(e) {
            e.preventDefault();
            currentInvoiceId = $(this).data('id');
            $('#form-cicilan')[0].reset();
            $('#bukti_cicilan').next('.custom-file-label').text('Pilih file...');
            $('#cicilan_invoice_id').val(currentInvoiceId);
            loadPaymentDetail(currentInvoiceId);
            $('#paymentDetailModal').modal('show');
        });

        function loadPaymentDetail(invoiceId) {
            $.ajax({
                url: "<?= site_url('mac_invoice/get_payment_detail') ?>",
                type: 'POST',
                data: { id: invoiceId },
                dataType: 'JSON',
                success: function(res) {
                    if (!res.status) return;

                    var baseUrl = "<?= base_url('assets/backend/document/mac_invoice_payment/') ?>";

                    // Info invoice
                    $('#payment-invoice-info').html(
                        '<strong>' + res.invoice_number + '</strong>' +
                        ' &nbsp;|&nbsp; ' + res.customer_name +
                        ' &nbsp;|&nbsp; Total: <strong>Rp ' + parseInt(res.sub_total).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') + '</strong>'
                    );

                    // Badge status
                    var badgeClass = { paid: 'badge-success', partial: 'badge-warning', unpaid: 'badge-danger' };
                    var badgeLabel = { paid: 'LUNAS', partial: 'SEBAGIAN', unpaid: 'BELUM BAYAR' };
                    $('#payment-status-badge')
                        .attr('class', 'badge ' + (badgeClass[res.payment_status] || 'badge-secondary'))
                        .text(badgeLabel[res.payment_status] || res.payment_status);

                    // Progress
                    var persen = res.sub_total > 0 ? Math.min(100, (res.total_bayar / res.sub_total) * 100) : 0;
                    $('#info-terbayar').text('Rp ' + parseInt(res.total_bayar).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
                    $('#info-sisa').text('Rp ' + Math.max(0, parseInt(res.sisa)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
                    $('#progress-bayar').css('width', persen.toFixed(1) + '%');

                    // Format nominal cicilan
                    $(document).on('input', '#cicilan_nominal', function() {
                        var raw = $(this).val().replace(/[^0-9]/g, '');
                        // Jika kosong
                        if (raw === '') {
                            $(this).val('');
                            return;
                        }
                        var nominal = parseInt(raw);
                        var sisa = parseInt(res.sisa); // pastikan res.sisa berupa angka
                        // Tidak boleh lebih dari sisa
                        if (nominal > sisa) {
                            nominal = sisa;
                        }
                        $(this).val(nominal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
                    });

                    // Daftar cicilan
                    var rows = '';
                    if (!res.cicilan || res.cicilan.length === 0) {
                        rows = '<tr><td colspan="7" class="text-center text-muted py-2">Belum ada pembayaran</td></tr>';
                    } else {
                        $.each(res.cicilan, function(i, c) {
                            var tgl    = c.tgl_bayar
                                ? c.tgl_bayar.split('-').reverse().join('-') : '-';
                            var nominal = 'Rp ' + parseInt(c.nominal).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');

                            var buktiBadge = '';
                            if (c.bukti) {
                                var ext = c.bukti.split('.').pop().toLowerCase();
                                var url = baseUrl + c.bukti;
                                if (['jpg','jpeg','png'].includes(ext)) {
                                    buktiBadge = '<a href="' + url + '" target="_blank">' +
                                        '<img src="' + url + '" style="height:40px; border-radius:4px; cursor:pointer;" title="Lihat bukti">' +
                                        '</a>';
                                } else {
                                    buktiBadge = '<a href="' + url + '" target="_blank" class="btn btn-outline-danger btn-sm">' +
                                        '<i class="fa fa-file-pdf"></i> PDF</a>';
                                }
                            } else {
                                buktiBadge = '<span class="text-muted small">—</span>';
                            }

                            rows += '<tr>' +
                                '<td class="text-center">' + (i + 1) + '</td>' +
                                '<td>' + tgl + '</td>' +
                                '<td class="font-weight-bold text-success">' + nominal + '</td>' +
                                '<td>' + (c.metode || '—') + '</td>' +
                                '<td>' + (c.keterangan || '—') + '</td>' +
                                '<td class="text-center">' + buktiBadge + '</td>' +
                                '<td class="text-center">' +
                                    '<button type="button" class="btn btn-danger btn-circle btn-sm btn-delete-cicilan" ' +
                                    'data-id="' + c.id + '" title="Hapus">' +
                                    '<i class="fa fa-trash"></i></button>' +
                                '</td>' +
                            '</tr>';
                        });
                    }
                    $('#tbody-cicilan').html(rows);

                    // Sembunyikan form tambah jika sudah lunas
                    if (res.payment_status === 'paid') {
                        $('#form-tambah-cicilan-wrapper').hide();
                    } else {
                        $('#form-tambah-cicilan-wrapper').show();
                    }
                }
            });
        }

        // Simpan cicilan baru
        $('#btn-save-cicilan').on('click', function() {
            if (!$('#cicilan_tgl_bayar').val()) {
                Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Tanggal bayar wajib diisi.' }); return;
            }
            if (!$('#cicilan_nominal').val() || parseInt($('#cicilan_nominal').val().replace(/\./g, '')) <= 0) {
                Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Nominal wajib diisi.' }); return;
            }
            // if (!$('#cicilan_metode').val()) {
            //     Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Metode pembayaran wajib diisi.' }); return;
            // }
            if (!$('#bukti_cicilan').val()) {
                Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Bukti pembayaran wajib diisi.' }); return;
            }

            $('#btn-save-cicilan').prop('disabled', true);

            var formData = new FormData($('#form-cicilan')[0]);
            formData.set('nominal', $('#cicilan_nominal').val().replace(/\./g, ''));

            $.ajax({
                url: "<?= site_url('mac_invoice/add_payment') ?>",
                type: 'POST',
                data: formData,
                processData: false, contentType: false, dataType: 'JSON',
                success: function(res) {
                    $('#btn-save-cicilan').prop('disabled', false);
                    if (res.status) {
                        $('#form-cicilan')[0].reset();
                        $('#bukti_cicilan').next('.custom-file-label').text('Pilih file...');
                        $('#cicilan_invoice_id').val(currentInvoiceId);
                        loadPaymentDetail(currentInvoiceId); // reload data
                        table.ajax.reload(null, false);       // reload datatable
                        Swal.fire({ icon: 'success', title: 'Berhasil!', text: res.message,
                            timer: 1500, showConfirmButton: false });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: res.error });
                    }
                },
                error: function() {
                    $('#btn-save-cicilan').prop('disabled', false);
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan server.' });
                }
            });
        });

        // Hapus cicilan
        $(document).on('click', '.btn-delete-cicilan', function() {
            var paymentId = $(this).data('id');
            Swal.fire({
                icon: 'warning',
                title: 'Hapus pembayaran ini?',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (result.isConfirmed) {
                    $.post("<?= site_url('mac_invoice/delete_payment/') ?>" + paymentId, {}, function(res) {
                        if (res.status) {
                            loadPaymentDetail(currentInvoiceId);
                            table.ajax.reload(null, false);
                        } else {
                            Swal.fire({ icon: 'error', title: 'Gagal', text: res.error });
                        }
                    }, 'json');
                }
            });
        });

        function formatDateIndo(dateString) {
            if (!dateString) return '';
            var datePart = dateString.split(' ')[0];
            var parts = datePart.split('-');
            if (parts.length < 3) return dateString;
            var year = parseInt(parts[0], 10);
            var month = parseInt(parts[1], 10) - 1;
            var day = parseInt(parts[2], 10);
            if (isNaN(year) || isNaN(month) || isNaN(day)) return dateString;
            var bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            return day + ' ' + bulan[month] + ' ' + year;
        }
    });

    function reload_table() {
        table.ajax.reload(null, false);
    }

    $('#app_status').on('change', function() {
        if ($(this).val()) {
            $('#btn-submit-approve').prop('disabled', false);
        } else {
            $('#btn-submit-approve').prop('disabled', true);
        }
    });

    function submit_approve()
    {
        let id = $('#approve_id').val();
        let status = $('#app_status').val();

        if (status == '') {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Status wajib dipilih'
            });
            return;
        }

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Pastikan data sudah benar. Invoice yang disetujui tidak dapat diubah lagi.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#242d4a',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, approved!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                url: "<?php echo site_url('mac_invoice/approve') ?>",
                type: "POST",
                dataType: "JSON",
                data: {
                    id: id,
                    app_status: status
                },
                success: function(data) {
                    if (data.status) {
                        $('#modalApprove').modal('hide');
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        reload_table();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: data.error
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan saat update status'
                    });
                }
            });
            }
        });
    }

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
                    url: "<?php echo site_url('mac_invoice/delete') ?>/" + id,
                    type: "POST",
                    dataType: "JSON",
                    success: function(data) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Data has been deleted',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        reload_table();
                    },
                    error: function() {
                        alert('Error deleting data');
                    }
                });
            }
        });
    }
</script>