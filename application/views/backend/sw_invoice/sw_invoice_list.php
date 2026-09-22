<style>
    .rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: center;
        font-size: 2em;
    }

    .rating input {
        display: none;
    }

    .rating label {
        color: #ccc;
        cursor: pointer;
        transition: color 0.3s;
        position: relative;
        right: 107px;
        bottom: 5px;
    }

    .rating input:checked~label,
    .rating label:hover,
    .rating label:hover~label {
        color: #f5b301;
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
                    <a class="btn btn-primary btn-sm" href="<?= base_url('sw_invoice/add_form') ?>">
                        <i class="fa fa-plus"></i>&nbsp;Add Data
                    </a>
                </div>
                <div class="card-body p-4">
                    <!-- Added padding for spacing -->
                    <div class="table-responsive">
                        <!-- Table wrapper -->
                        <table id="hotel-table" class="table table-bordered table-striped display nowrap w-100 mb-4">
                            <!-- Added margin-bottom -->
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th style="width: 120px;">Action</th>
                                    <th>Payment Status</th>
                                    <th>Letter Number</th>
                                    <th>Company Name</th>
                                    <th>PIC</th>
                                    <th>No. Telp</th>
                                    <th>Event Type</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be populated by DataTables -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th style="width: 120px;">Action</th>
                                    <th>Payment Status</th>
                                    <th>Letter Number</th>
                                    <th>Company Name</th>
                                    <th>PIC</th>
                                    <th>No. Telp</th>
                                    <th>Event Type</th>
                                    <th>Created At</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
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
                                           placeholder="DD-MM-YYYY" autocomplete="off" style="cursor: pointer;">
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
    $(document).ready(function() {
        table = $('#hotel-table').DataTable({
            "responsive": true,
            "scrollX": true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('sw_invoice/get_list') ?>",
                "type": "POST",
            },
            "language": {
                "infoFiltered": ""
            },
            "columnDefs": [{
                    "targets": [], // Adjusted indices to match the number of columns
                    "className": 'dt-head-nowrap'
                },
                {
                    "targets": [1],
                    "className": 'dt-body-nowrap'
                },
                {
                    "targets": [0, 1], // Indices for non-orderable columns
                    "orderable": false,
                }
            ],
        });
    });

    
    $("#modalform").submit(function(e) {
        e.preventDefault();
        var url;
        var $form = $(this);
        if (!$form.valid()) return false;
        if (method == 'add') {
            url = "<?php echo site_url('sw_invoice/add') ?>";
        } else {
            url = "<?php echo site_url('sw_invoice/update') ?>";
        }

        $.ajax({
            url: url,
            type: "post",
            data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false,
            async: false,
            success: function(data) {
                $('#modal-default').modal('hide');
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Your data has been saved',
                    showConfirmButton: false,
                    timer: 1500
                });
                reload_table();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
            }
        });
    });

    function reload_table() {
        table.ajax.reload(null, false);
    };

    function add_data() {
        method = 'add';
        $('#modalform')[0].reset();
        var validator = $("#modalform").validate();
        validator.resetForm();
    };

    $(document).ready(function() {
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
                url: "<?= site_url('sw_invoice/get_payment_detail') ?>",
                type: 'POST',
                data: { id: invoiceId },
                dataType: 'JSON',
                success: function(res) {
                    if (!res.status) return;

                    var baseUrl = "<?= base_url('assets/backend/document/sw_invoice_payment/') ?>";

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
                        $('#btn-save-cicilan').hide();
                    } else {
                        $('#form-tambah-cicilan-wrapper').show();
                        $('#btn-save-cicilan').show();
                    }
                }
            });
        }

        $('#paymentDetailModal').on('hidden.bs.modal', function () {
            location.reload();
        });

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
                url: "<?= site_url('sw_invoice/add_payment') ?>",
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
                    $.post("<?= site_url('sw_invoice/delete_payment/') ?>" + paymentId, {}, function(res) {
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

    // Mengambil URL saat ini
    const params = new URLSearchParams(window.location.search);

    // Mengambil parameter tertentu
    const action = params.get('action'); // "John"

    if (action == 'add') {
        $('#add_btn').click();
    }

    function edit_data(id) {
        method = 'update';
        $('#modalform')[0].reset();
        var validator = $("#modalform").validate();
        validator.resetForm();
        $('.form-control').removeClass('error');
        $('#modal-default').modal('show');
        $('.card-title').text('Edit Data Hotel');
        $('.aksi').text('Update');
        $.ajax({
            url: "<?php echo site_url('sw_invoice/get_id/') ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('[name="id"]').val(data.id);
                $('[name="nama_hotel"]').val(data.nama_hotel);
                $('[name="kota"]').val(data.kota);
                $('[name="negara"]').val(data.negara);

                if (data.rating == 1) {
                    $('#star1').prop('checked', true);
                } else if (data.rating == 2) {
                    $('#star2').prop('checked', true);
                } else if (data.rating == 3) {
                    $('#star3').prop('checked', true);
                } else if (data.rating == 4) {
                    $('#star4').prop('checked', true);
                } else if (data.rating == 5) {
                    $('#star5').prop('checked', true);
                }


            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    };

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
                    url: "<?php echo site_url('sw_invoice/delete') ?>/" + id,
                    type: "POST",
                    dataType: "JSON",
                    success: function(data) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Your data has been deleted',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        reload_table();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error deleting data');
                    }
                });
            }
        })
    };
</script>