<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title_view ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <?php
                            $username_session = $this->session->userdata('username');
                            $approvers        = ['dwi', 'bhakti', 'sopandi'];
                            if (in_array($username_session, $approvers)): ?>
                                <button type="button" class="btn btn-success btn-sm" id="btn-approve"
                                        style="display:none;">
                                    <i class="fa fa-check-circle"></i>&nbsp;Approval
                                </button>
                            <?php endif; ?>
                        </div>
                        <a class="btn btn-primary btn-sm" href="<?= base_url('mac_peminjaman') ?>">
                            <i class="fas fa-chevron-left"></i>&nbsp;Back
                        </a>
                    </div>
                <div class="card-body">

                    <!-- HEADER INFO -->
                    <div class="row mb-4">
                        <!-- KOTAK 1: INFO PEMINJAM -->
                        <div class="col-md-6 mb-3">
                            <div class="card border-left-primary h-100">
                                <div class="card-header py-2 bg-primary text-white">
                                    <strong><i class="fa fa-user"></i> Data Peminjam</strong>
                                </div>
                                <div class="card-body py-3">
                                    <table class="table table-borderless table-sm mb-0">
                                        <tr>
                                            <td width="150"><strong>Kode Pinjam</strong></td>
                                            <td>: <span id="kode_pinjam" class="font-weight-bold text-primary"></span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Peminjam</strong></td>
                                            <td>: <span id="peminjam"></span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tgl Pinjam</strong></td>
                                            <td>: <span id="tgl_pinjam"></span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Est. Tgl Kembali</strong></td>
                                            <td>: <span id="tgl_kembali"></span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tgl Kembali Aktual</strong></td>
                                            <td>: <span id="tgl_kembali_aktual">-</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status</strong></td>
                                            <td>: <span id="status_badge"></span></td>
                                        </tr>
                                    </table>
                                    <div id="keterangan-wrapper" class="mt-2" style="display:none;">
                                        <strong>Keterangan:</strong>
                                        <p id="keterangan_text" class="mb-0 text-muted"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- KOTAK 2: INFO APPROVAL -->
                        <div class="col-md-6 mb-3">
                            <div class="card border-left-success h-100" id="approval-card">
                                <div class="card-header py-2 bg-success text-white">
                                    <strong><i class="fa fa-check-circle"></i> Status Approval</strong>
                                </div>
                                <div class="card-body py-3" id="approval-card-body">
                                    <!-- diisi via JS -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3" id="keterangan-wrapper" style="display:none;">
                        <strong>Keterangan:</strong>
                        <p id="keterangan_text" class="mb-0"></p>
                    </div>

                    <!-- DETAIL BARANG -->
                    <h6 class="font-weight-bold mb-2"><i class="fa fa-box"></i> Detail Barang Dipinjam</h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-sm">
                            <thead style="background:#242d4a; color:white;">
                                <tr>
                                    <th>No</th>
                                    <th>Barang</th>
                                    <th class="text-center">Qty Pinjam</th>
                                    <th class="text-center">Qty Kembali</th>
                                    <th class="text-center">Sisa Pinjam</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody id="detail-container"></tbody>
                        </table>
                    </div>

                    <!-- HISTORI / LOG -->
                    <h6 class="font-weight-bold mb-2"><i class="fa fa-history"></i> Riwayat Aktivitas</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead style="background:#6c757d; color:white;">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                    <th>Barang</th>
                                    <th class="text-center">Qty</th>
                                    <th>Oleh</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody id="log-container"></tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="approvalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fa fa-check-circle"></i> Approval Peminjaman</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="approvalForm">
                    <input type="hidden" name="id" id="approval_id" value="<?= $id ?>">
                    <div class="form-group">
                        <label>Status <span class="text-danger">*</span></label>
                        <select class="form-control" name="app_status" id="app_status_select">
                            <option value="">-- Pilih --</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea class="form-control" name="app_keterangan"
                                  placeholder="Opsional..." rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" id="btn-save-approval">
                    <i class="fa fa-save"></i> Simpan
                </button>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>

<script>
$(document).ready(function() {
    var id = <?= (int) $id ?>;

    moment.locale('id');

    var badgeMap = {
        'aktif':   '<span class="badge badge-warning">Aktif</span>',
        'kembali': '<span class="badge badge-success">Kembali</span>',
        'batal':   '<span class="badge badge-danger">Batal</span>',
    };

    var aksiLabelMap = {
        'pinjam':            '<span class="badge badge-primary">Pinjam</span>',
        'kembali_sebagian':  '<span class="badge badge-info">Kembali Sebagian</span>',
        'kembali_semua':     '<span class="badge badge-success">Kembali Semua</span>',
        'batal':             '<span class="badge badge-danger">Batal</span>',
    };

    // Buka modal approval
    $('#btn-approve').on('click', function() {
        $('#approvalForm')[0].reset();
        $('#approvalModal').modal('show');
    });

    // Simpan approval
    $('#btn-save-approval').on('click', function() {
        if (!$('#app_status_select').val()) {
            Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Status wajib dipilih.' });
            return;
        }

        $('#btn-save-approval').prop('disabled', true);

        $.ajax({
            url: "<?= site_url('mac_peminjaman/approve') ?>",
            type: 'POST',
            data: new FormData($('#approvalForm')[0]),
            processData: false, contentType: false, dataType: 'JSON',
            success: function(res) {
                $('#btn-save-approval').prop('disabled', false);
                if (res.status) {
                    $('#approvalModal').modal('hide');
                    Swal.fire({
                        icon: 'success', title: 'Berhasil!',
                        text: res.message, timer: 1500, showConfirmButton: false
                    }).then(function() {
                        location.reload(); // reload agar status terbaru terbaca
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: res.error });
                }
            },
            error: function() {
                $('#btn-save-approval').prop('disabled', false);
                Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan server.' });
            }
        });
    });

    $.ajax({
        url: "<?= site_url('mac_peminjaman/get_data/') ?>" + id,
        type: 'GET',
        dataType: 'JSON',
        success: function(res) {
            var m = res.master;

            // ===== HEADER =====
            $('#kode_pinjam').text(m.kode_pinjam ? m.kode_pinjam.toUpperCase() : '-');
            $('#peminjam').text(m.peminjam || '-');
            $('#tgl_pinjam').text(m.tgl_pinjam ? moment(m.tgl_pinjam).format('DD MMMM YYYY') : '-');
            $('#tgl_kembali').text(m.tgl_kembali ? moment(m.tgl_kembali).format('DD MMMM YYYY') : '-');
            $('#tgl_kembali_aktual').text(m.tgl_kembali_aktual ? moment(m.tgl_kembali_aktual).format('DD MMMM YYYY') : '-');
            $('#status_badge').html(badgeMap[m.status] || m.status);

            if (m.keterangan) {
                $('#keterangan_text').text(m.keterangan);
                $('#keterangan-wrapper').show();
            }

            // ===== DETAIL BARANG =====
            var detailRows = '';
            $.each(res.detail, function(i, d) {
                var sisa = parseFloat(d.qty_pinjam) - parseFloat(d.qty_kembali);
                detailRows += '<tr>' +
                    '<td>' + (i + 1) + '</td>' +
                    '<td>' + d.kode_produk + ' - ' + d.nama_produk + '</td>' +
                    '<td class="text-center">' + d.qty_pinjam + ' ' + d.satuan + '</td>' +
                    '<td class="text-center">' + d.qty_kembali + ' ' + d.satuan + '</td>' +
                    '<td class="text-center">' +
                        (sisa > 0
                            ? '<span class="badge badge-warning">' + sisa + ' ' + d.satuan + '</span>'
                            : '<span class="badge badge-success">Lunas</span>') +
                    '</td>' +
                    '<td>' + (d.keterangan || '-') + '</td>' +
                '</tr>';
            });
            $('#detail-container').html(detailRows || '<tr><td colspan="6" class="text-center">Tidak ada data</td></tr>');

            // ===== KOTAK APPROVAL INFO =====
            var appBadgeMap = {
                'waiting':  '<span class="badge badge-secondary" style="font-size:13px; padding:5px 10px;">Menunggu Persetujuan</span>',
                'approved': '<span class="badge badge-success"   style="font-size:13px; padding:5px 10px;">Approved</span>',
                'rejected': '<span class="badge badge-danger"    style="font-size:13px; padding:5px 10px;">Rejected</span>',
            };

            if (m.app_status === 'waiting') {
                // Belum ada yang approve — tampilkan pesan menunggu
                $('#approval-card-body').html(
                    '<div class="text-center text-muted py-3">' +
                        '<i class="fa fa-clock fa-2x mb-2 d-block"></i>' +
                        '<span>Menunggu persetujuan.</span>' +
                    '</div>'
                );
                $('#approval-card').removeClass('border-left-success').addClass('border-left-secondary');
                $('#approval-card .card-header').removeClass('bg-success').addClass('bg-secondary');

            } else {
                // Sudah diproses — tampilkan detail approval
                var approvedColor = m.app_status === 'approved' ? 'success' : 'danger';
                $('#approval-card').removeClass('border-left-success border-left-secondary')
                    .addClass('border-left-' + approvedColor);
                $('#approval-card .card-header').removeClass('bg-success bg-secondary')
                    .addClass('bg-' + approvedColor);

                $('#approval-card-body').html(
                    '<table class="table table-borderless table-sm mb-0">' +
                        '<tr>' +
                            '<td width="150"><strong>Status</strong></td>' +
                            '<td>: ' + (appBadgeMap[m.app_status] || m.app_status) + '</td>' +
                        '</tr>' +
                        '<tr>' +
                            '<td><strong>Disetujui Oleh</strong></td>' +
                            '<td>: <strong>' + (m.app_by || '-') + '</strong></td>' +
                        '</tr>' +
                        '<tr>' +
                            '<td><strong>Tanggal</strong></td>' +
                            '<td>: ' + (m.app_date ? moment(m.app_date).format('DD MMMM YYYY, HH:mm') : '-') + '</td>' +
                        '</tr>' +
                        (m.app_keterangan
                            ? '<tr>' +
                                '<td><strong>Keterangan</strong></td>' +
                                '<td>: <span class="text-muted">' + m.app_keterangan + '</span></td>' +
                            '</tr>'
                            : '') +
                    '</table>'
                );
            }

            // Tombol approval hanya muncul jika masih waiting & status tidak batal
            if (m.app_status === 'waiting' && m.status !== 'batal') {
                $('#btn-approve').show();
            } else {
                $('#btn-approve').hide();
            }

            // ===== LOG / HISTORI =====
            var logRows = '';
            $.each(res.log, function(i, l) {
                logRows += '<tr>' +
                    '<td>' + moment(l.created_at).format('DD-MM-YYYY HH:mm') + '</td>' +
                    '<td>' + (aksiLabelMap[l.aksi] || l.aksi) + '</td>' +
                    '<td>' + (l.nama_produk || '-') + '</td>' +
                    '<td class="text-center">' + (l.qty !== null ? l.qty : '-') + '</td>' +
                    '<td>' + (l.name || '-') + '</td>' +
                    '<td>' + (l.keterangan || '-') + '</td>' +
                '</tr>';
            });
            $('#log-container').html(logRows || '<tr><td colspan="6" class="text-center">Belum ada riwayat</td></tr>');
        },
        error: function() {
            Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal memuat data peminjaman.' });
        }
    });
});
</script>
