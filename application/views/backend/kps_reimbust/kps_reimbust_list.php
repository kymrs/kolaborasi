<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $titleview ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <?php if ($add == 'Y') { ?>
                        <a class="btn btn-primary btn-sm" href="<?= base_url('kps_reimbust/add_form') ?>">
                            <i class="fa fa-plus"></i>&nbsp;Add Data
                        </a>
                    <?php } ?>
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
                        <a class="nav-link active" id="personalTab" href="#" data-tab="personal">User</a>
                    </li>
                    <?php if ($approval > 0) { ?>
                        <li class="nav-item">
                            <a class="nav-link" id="employeeTab" href="#" data-tab="employee">Approval</a>
                        </li>
                    <?php } ?>
                    <?php if ($alias == "eko") { ?>
                        <li class="nav-item">
                            <a class="nav-link" id="adminTab" href="#" data-tab="admin">Admin</a>
                        </li>
                    <?php } ?>
                </ul>
                <div class="card-body">
                    <table id="table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Action</th>
                                <th>Status Pembayaran</th>
                                <th>Kode Reimbust</th>
                                <th>Nama</th>
                                <!-- <th>Jabatan</th>
                                <th>Departemen</th> -->
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
                                <th>Status Pembayaran</th>
                                <th>Kode Reimbust</th>
                                <th>Nama</th>
                                <!-- <th>Jabatan</th>
                                <th>Departemen</th> -->
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

<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>

<script type="text/javascript">
    var table;

    $(document).ready(function() {
        // Set locale moment ke Indonesia sejak awal
        moment.locale('id');

        // Set active tab on page load
        const activeTab = sessionStorage.getItem('activeTab');

        // Cek apakah tab approval ada
        const approvalTabExists = $('#employeeTab').length > 0;

        // console.log(activeTab)

        if (activeTab && (activeTab == 'employee' || approvalTabExists)) {
            $('.nav-tabs .nav-link').removeClass('active');
            $(`.nav-tabs .nav-link[data-tab="${activeTab}"]`).addClass('active');
            // You can load content for the active tab here if needed
        } else if (activeTab == 'admin') {
            $('.nav-tabs .nav-link').removeClass('active');
            $(`.nav-tabs .nav-link[data-tab="${activeTab}"]`).addClass('active');
        } else {
            // Default to the "User" tab if session storage is empty or approval tab doesn't exist
            $('.nav-tabs .nav-link').removeClass('active');
            $('#personalTab').addClass('active');
        }

        $('.collapse-item').on('click', function(e) {
            localStorage.removeItem('appFilterStatus'); // Hapus filter yang tersimpan
        })

        // Tab click event
        $('.nav-tabs .nav-link').on('click', function() {
            const tab = $(this).data('tab');
            sessionStorage.setItem('activeTab', tab);
            $('.nav-tabs .nav-link').removeClass('active');
            $(this).addClass('active');
            table.ajax.reload(); // Muat ulang data di DataTable saat tab berubah
        });

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
                "url": "<?php echo site_url('kps_reimbust/get_list') ?>",
                "type": "POST",
                "data": function(d) {
                    d.status = $('#appFilter').val(); // Tambahkan parameter status ke permintaan server
                    d.tab = $('.nav-tabs .nav-link.active').data('tab'); // Tambahkan parameter tab ke permintaan server
                }
            },
            "language": {
                "infoFiltered": ""
            },
            "columnDefs": [{
                    "targets": [2, 3, 5, 6, 8],
                    "className": 'dt-head-nowrap'
                },
                {
                    "targets": [1, 4, 6, 9],
                    "className": 'dt-body-nowrap'
                }, {
                    "targets": [0, 1],
                    "orderable": false,
                },
            ],
        });

        // Simpan nilai filter ke localStorage setiap kali berubah
        $('#appFilter').on('change', function() {
            localStorage.setItem('appFilterStatus', $(this).val());
            table.ajax.reload(); // Muat ulang DataTables dengan filter baru
        });
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
                    url: "<?php echo site_url('kps_reimbust/delete/') ?>" + id,
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
                                location.href = "<?= base_url('kps_reimbust') ?>";
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
                            location.href = "<?= base_url('kps_reimbust') ?>";
                        });
                    }
                });
            }
        });
    }

    // Handle Payment Detail Modal
    $('#paymentDetailModal').on('show.bs.modal', function(e) {
        var paymentBtn = $(e.relatedTarget);
        var id = paymentBtn.data('id');
        var baseUrl = "<?= base_url() ?>";
        var attachmentPath = baseUrl + "assets/backend/document/reimbust/attachment/kps_attachment/";
        
        $.ajax({
            url: "<?= site_url('kps_reimbust/edit_data') ?>/" + id,
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
                
                // Handle attachment
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
</script>