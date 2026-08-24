<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $titleview ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <label for="appFilter" class="mr-2 mb-0">Filter:</label>
                        <select id="appFilter" name="appFilter" class="form-control form-control-sm" style="cursor: pointer;">
                            <!-- <option value="" selected>Show all....</option> -->
                            <option value="on-process" selected>On-Process</option>
                            <option value="approved">Approved</option>
                            <option value="revised">Revised</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                </div>

                <!-- NAV TABS -->
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" id="reimbustTab" href="#" data-tab="reimbust">Reimbust</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" id="prepaymentTab" href="#" data-tab="prepayment">Prepayment</a>
                    </li>
                </ul>
                <div class="card-body" id="reimbustData">
                    <table id="table1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Action</th>
                                <th>Status Pembayaran</th>
                                <th>Kode Reimbust</th>
                                <th>Nama</th>
                                <th>Sifat Pelaporan</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Tujuan</th>
                                <th>Jumlah Pelaporan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Action</th>
                                <th>Status Pembayaran</th>
                                <th>Kode Reimbust</th>
                                <th>Nama</th>
                                <th>Sifat Pelaporan</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Tujuan</th>
                                <th>Jumlah Pelaporan</th>
                                <th>Status</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="card-body" id="prepaymentData">
                    <table id="table2" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Action</th>
                                <th>Status Pembayaran</th>
                                <th>Kode Prepayment</th>
                                <th>Nama</th>
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
                                <th>Status Pembayaran</th>
                                <th>Kode Prepayment</th>
                                <th>Nama</th>
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

<!-- Modal Payment Detail -->
<div class="modal fade" id="paymentDetailModal" tabindex="-1" aria-labelledby="paymentDetailLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="paymentDetailLabel">
                     Detail Pembayaran
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label><strong>Tanggal :</strong></label>
                    <p id="modalTanggal">-</p>
                </div>
                <div class="form-group">
                    <label><strong>Attachment :</strong></label>
                    <div id="modalAttachment">
                        <p>-</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Payment Detail Prepayment -->
<div class="modal fade" id="paymentDetailModalPrepayment" tabindex="-1" aria-labelledby="paymentDetailLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="paymentDetailLabel">
                     Detail Pembayaran
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label><strong>Tanggal :</strong></label>
                    <p id="modalTanggalPrepayment">-</p>
                </div>
                <div class="form-group">
                    <label><strong>Attachment :</strong></label>
                    <div id="modalAttachmentPrepayment">
                        <p>-</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>

<script type="text/javascript">
    var table, table2;

    $(document).ready(function() {
        // Set locale moment ke Indonesia sejak awal
        moment.locale('id');

        // --- NORMALISASI NAV TAB (hapus kelas active ganda) ---
        $('.nav-tabs .nav-link').removeClass('active');

        // Ambil tab aktif dari sessionStorage (default 'reimbust')
        var activeTab = sessionStorage.getItem('activeTab') || 'reimbust';

        // Set kelas active pada tab yang sesuai
        $('.nav-tabs .nav-link[data-tab="' + activeTab + '"]').addClass('active');

                // Event click pada nav tabs
        $('.nav-tabs .nav-link').on('click', function(e) {
            e.preventDefault();
            var tab = $(this).data('tab');
            // Set active class
            $('.nav-tabs .nav-link').removeClass('active');
            $(this).addClass('active');
            // Simpan pilihan
            sessionStorage.setItem('activeTab', tab);
            // Tampilkan kontainer sesuai tab dan reload tabel yang aktif
            showTab(tab);
        });

        // Simpan nilai filter ke localStorage dan reload tabel aktif
        $('#appFilter').on('change', function() {
            localStorage.setItem('appFilterStatus', $(this).val());
            var currentTab = $('.nav-tabs .nav-link.active').data('tab');
            if (currentTab === 'prepayment') table2.ajax.reload();
            else table.ajax.reload();
        });

        // Apply saved filter jika ada
        var savedFilter = localStorage.getItem('appFilterStatus');
        if (savedFilter) {
            $('#appFilter').val(savedFilter);
        }

        console.log($('#appFilter').val());

        // Inisialisasi DataTables (tetap inisialisasi keduanya)
        table = $('#table1').DataTable({
            "responsive": false,
            "scrollX": true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('ctz_reimbust/get_list') ?>",
                "type": "POST",
                "data": function(d) {
                    d.status = $('#appFilter').val();
                    d.tab = $('.nav-tabs .nav-link.active').data('tab');
                }
            },
            "language": {"infoFiltered": ""},
            "columnDefs": [
                {"targets": [2, 3, 5, 6, 8], "className": 'dt-head-nowrap'},
                {"targets": [1, 9], "className": 'dt-body-nowrap'},
                {"targets": [0, 1], "orderable": false}
            ]
        });

        table2 = $('#table2').DataTable({
            "responsive": false,
            "scrollX": true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('ctz_prepayment/get_list') ?>",
                "type": "POST",
                "data": function(d) {
                    d.status = $('#appFilter').val();
                    d.tab = $('.nav-tabs .nav-link.active').data('tab');
                }
            },
            "columnDefs": [
                {"targets": [2, 3, 5, 7], "className": 'dt-head-nowrap'},
                {"targets": [1, 4, 5, 8], "className": 'dt-body-nowrap'},
                {"targets": [0, 1], "orderable": false}
            ]
        });

        // Fungsi bantu tampilkan kontainer sesuai tab
        function showTab(tab) {
            if (tab === 'prepayment') {
                $('#reimbustData').hide();
                $('#prepaymentData').show();
                table2.ajax.reload(null, false);
            } else {
                $('#prepaymentData').hide();
                $('#reimbustData').show();
                table.ajax.reload(null, false);
            }
        }

        // Tampilkan tab awal berdasarkan sessionStorage (atau default)
        showTab(activeTab);
    });

    // Handle Payment Detail Modal
    $('#paymentDetailModal').on('show.bs.modal', function(e) {
        var paymentBtn = $(e.relatedTarget);
        var id = paymentBtn.data('id');
        var baseUrl = "<?= base_url() ?>";
        var attachmentPath = baseUrl + "assets/backend/document/reimbust/attachment/ctz_attachment/";
        
        $.ajax({
            url: "<?= site_url('ctz_reimbust/edit_data') ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                var tglPembayaran = data.master.tgl_pembayaran;
                var attachment = data.master.attachment;
                
                // Format tanggal pembayaran terpisah
                if (tglPembayaran) {
                    var momentDate = moment(tglPembayaran);
                    // var tanggalFormatted = momentDate.format('D MMMM YYYY - HH:mm:ss');
                    var tanggalFormatted = momentDate.format('D MMMM YYYY');
                    
                    $('#modalTanggal').html(tanggalFormatted);
                } else {
                    $('#modalTanggal').html('-');
                }
                
                // Hande attachment
                if (attachment) {
                    var fileExtension = attachment.split('.').pop().toLowerCase();
                    var attachmentHtml = '';
                    var fullPath = attachmentPath + attachment;
                    
                    if (fileExtension === 'png' || fileExtension === 'jpg' || fileExtension === 'jpeg') {
                        // Tampilkan image preview
                        attachmentHtml = '<a href="' + fullPath + '" download title="Download Image"><img src="' + fullPath + '" alt="Attachment" class="img-fluid" style="max-width: 400px; border: 1px solid #ddd; padding: 5px; border-radius: 5px;"></a>';
                    } else if (fileExtension === 'pdf') {
                        // Tampilkan tombol preview untuk PDF
                        attachmentHtml = '<button class="btn btn-primary btn-sm" onclick="window.open(\'' + fullPath + '\', \'_blank\')">';
                        attachmentHtml += '<i class="fas fa-file-pdf"></i> Preview PDF';
                        attachmentHtml += '</button>';
                    } else {
                        // Untuk tipe file lain
                        attachmentHtml = '<button class="btn btn-primary btn-sm" onclick="window.open(\'' + fullPath + '\', \'_blank\')">';
                        attachmentHtml += '<i class="fas fa-download"></i> Download File';
                        attachmentHtml += '</button>';
                    }
                    
                    $('#modalAttachment').html(attachmentHtml);
                } else {
                    $('#modalAttachment').html('<p>-</p>');
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',  
                    title: 'Error',
                    text: 'Gagal mengambil data pembayaran'
                });
            }
        });
    });

    // Handle Payment Detail Modal
    $('#paymentDetailModalPrepayment').on('show.bs.modal', function(e) {
        var paymentBtn = $(e.relatedTarget);
        var id = paymentBtn.data('id');
        var baseUrl = "<?= base_url() ?>";
        var attachmentPath = baseUrl + "assets/backend/document/prepayment/attachment/ctz_attachment/";
        
        $.ajax({
            url: "<?= site_url('ctz_prepayment/edit_data') ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                var tglPembayaran = data.master.tgl_pembayaran;
                var attachment = data.master.attachment;
                
                // Format tanggal pembayaran terpisah
                if (tglPembayaran) {
                    var momentDate = moment(tglPembayaran);
                    // var tanggalFormatted = momentDate.format('D MMMM YYYY - HH:mm:ss');
                    var tanggalFormatted = momentDate.format('D MMMM YYYY');
                    
                    $('#modalTanggalPrepayment').html(tanggalFormatted);
                } else {
                    $('#modalTanggalPrepayment').html('-');
                }
                
                // Hande attachment
                if (attachment) {
                    var fileExtension = attachment.split('.').pop().toLowerCase();
                    var attachmentHtml = '';
                    var fullPath = attachmentPath + attachment;
                    
                    if (fileExtension === 'png' || fileExtension === 'jpg' || fileExtension === 'jpeg') {
                        // Tampilkan image preview
                        attachmentHtml = '<a href="' + fullPath + '" download title="Download Image"><img src="' + fullPath + '" alt="Attachment" class="img-fluid" style="max-width: 400px; border: 1px solid #ddd; padding: 5px; border-radius: 5px;"></a>';
                    } else if (fileExtension === 'pdf') {
                        // Tampilkan tombol preview untuk PDF
                        attachmentHtml = '<button class="btn btn-primary btn-sm" onclick="window.open(\'' + fullPath + '\', \'_blank\')">';
                        attachmentHtml += '<i class="fas fa-file-pdf"></i> Preview PDF';
                        attachmentHtml += '</button>';
                    } else {
                        // Untuk tipe file lain
                        attachmentHtml = '<button class="btn btn-primary btn-sm" onclick="window.open(\'' + fullPath + '\', \'_blank\')">';
                        attachmentHtml += '<i class="fas fa-download"></i> Download File';
                        attachmentHtml += '</button>';
                    }
                    
                    $('#modalAttachmentPrepayment').html(attachmentHtml);
                } else {
                    $('#modalAttachmentPrepayment').html('<p>-</p>');
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Gagal mengambil data pembayaran'
                });
            }
        });
    });
</script>