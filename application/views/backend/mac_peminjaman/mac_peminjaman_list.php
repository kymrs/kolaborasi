<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $titleview ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <a class="btn btn-primary btn-sm" href="<?= base_url('mac_peminjaman/add_form') ?>">
                        <i class="fa fa-plus"></i>&nbsp;Add Data
                    </a>
                    <div class="d-flex align-items-center">
                        <label class="mr-2 mb-0">Filter:</label>
                        <select id="statusFilter" class="form-control form-control-sm" style="cursor:pointer;">
                            <option value="" selected>Semua Status</option>
                            <option value="aktif">Aktif</option>
                            <option value="kembali">Kembali</option>
                            <option value="batal">Batal</option>
                        </select>
                    </div>
                </div>

                <!-- TAB -->
                <ul class="nav nav-tabs px-3 pt-2">
                    <li class="nav-item">
                        <a class="nav-link active" href="#" data-tab="all">Semua</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-tab="personal">Data Pribadi</a>
                    </li>
                </ul>

                <div class="card-body">
                    <table id="table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Action</th>
                                <th>Kode</th>
                                <th>Nama Peminjam</th>
                                <th>Tanggal Pinjam</th>
                                <th>Tanggal Kembali</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Action</th>
                                <th>Kode</th>
                                <th>Nama Peminjam</th>
                                <th>Tanggal Pinjam</th>
                                <th>Tanggal Kembali</th>
                                <th>Status</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL PENGEMBALIAN -->
<div class="modal fade" id="returnModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fa fa-undo"></i> Catat Pengembalian</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div id="return-info" class="mb-3"></div>
                <table class="table table-bordered table-sm">
                    <thead class="thead-light">
                        <tr>
                            <th>Barang</th>
                            <th>Qty Pinjam</th>
                            <th>Sudah Kembali</th>
                            <th>Sisa</th>
                            <th>Qty Kembali</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody id="return-detail-container"></tbody>
                </table>
                <input type="hidden" id="return_peminjaman_id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="btn-save-return">
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
    var table;
    var activeTab = 'all';

    // ===== INIT DATATABLE =====
    function initTable() {
        if (table) table.destroy();

        table = $('#table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            order: [],
            ajax: {
                url: "<?= site_url('mac_peminjaman/get_list') ?>",
                type: 'POST',
                data: function(d) {
                    d.status = $('#statusFilter').val();
                    d.tab    = activeTab;
                }
            },
            columnDefs: [{ targets: [0, 1], orderable: false }]
        });
    }

    initTable();

    // ===== FILTER & TAB =====
    $('#statusFilter').on('change', function() { table.ajax.reload(); });

    $(document).on('click', '.nav-link[data-tab]', function(e) {
        e.preventDefault();
        $('.nav-link[data-tab]').removeClass('active');
        $(this).addClass('active');
        activeTab = $(this).data('tab');
        table.ajax.reload();
    });

    // ===== DELETE / BATAL =====
    window.delete_data = function(id) {
        Swal.fire({
            title: 'Batalkan peminjaman ini?',
            text: 'Status akan diubah menjadi Batal.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, batalkan',
            cancelButtonText: 'Tidak'
        }).then(function(result) {
            if (result.isConfirmed) {
                $.post("<?= site_url('mac_peminjaman/delete/') ?>" + id, {}, function(data) {
                    if (data.status) {
                        Swal.fire({ icon: 'success', title: 'Berhasil dibatalkan', timer: 1500, showConfirmButton: false });
                        table.ajax.reload();
                    } else {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: data.error });
                    }
                }, 'json');
            }
        });
    };

    // ===== PENGEMBALIAN =====
    window.open_return = function(id) {
        $.ajax({
            url: "<?= site_url('mac_peminjaman/get_data/') ?>" + id,
            type: 'GET', dataType: 'JSON',
            success: function(res) {
                var m = res.master;
                $('#return_peminjaman_id').val(m.id);
                $('#return-info').html(
                    '<strong>Kode:</strong> ' + m.kode_pinjam +
                    ' &nbsp;|&nbsp; <strong>Peminjam:</strong> ' + m.peminjam +
                    ' &nbsp;|&nbsp; <strong>Est. Kembali:</strong> ' + m.tgl_kembali
                );

                var rows = '';
                $.each(res.detail, function(i, d) {
                    var sisa = parseFloat(d.qty_pinjam) - parseFloat(d.qty_kembali);

                    rows += '<tr>' +
                        '<td>' + d.nama_produk + '</td>' +
                        '<td class="text-center">' + d.qty_pinjam + ' ' + d.satuan + '</td>' +
                        '<td class="text-center">' + d.qty_kembali + '</td>' +
                        '<td class="text-center"><strong>' + sisa + '</strong></td>' +

                        // Qty kembali
                        '<td>' +
                            '<input type="hidden" name="detail_id[]" value="' + d.id + '">' +
                            (sisa > 0
                                ? '<input type="number" class="form-control form-control-sm qty-kembali" ' +
                                'name="qty_kembali[]" min="0" max="' + sisa + '" value="0" ' +
                                'data-sisa="' + sisa + '" ' +
                                'data-nama="' + d.nama_produk + '" ' +
                                'data-satuan="' + d.satuan + '">'
                                : '<span class="badge badge-success">Sudah kembali</span>' +
                                '<input type="hidden" name="qty_kembali[]" value="0">')
                            +
                        '</td>' +

                        // Keterangan pengembalian
                        '<td>' +
                            (sisa > 0
                                ? '<select class="form-control form-control-sm keterangan-kembali" ' +
                                    'data-detail-id="' + d.id + '">' +
                                        '<option value="">-- Pilih Status --</option>' +
                                        '<option value="kembali_gudang">📦 Kembali ke Gudang</option>' +
                                        '<option value="terjual_invoice">🧾 Berhasil Terjual / Keluar Invoice</option>' +
                                    '</select>'
                                : '<span class="badge badge-success">Selesai</span>' +
                                '<input type="hidden" name="keterangan_kembali[]" value="">'
                            ) +
                        '</td>' +
                    '</tr>';
                });

                $('#return-detail-container').html(rows);
                $('#returnModal').modal('show');
            }
        });
    };

    $(document).on('change', '.keterangan-kembali', function() {
        var $select = $(this);
        var value = $select.val();

        $select.next('.info-kembali').remove();

        if (value === 'kembali_gudang') {
            $select.after(
                '<small class="text-success d-block mt-1 info-kembali">' +
                '<i class="fa fa-box"></i> Barang akan dikembalikan ke stok gudang.' +
                '</small>'
            );
        }

        if (value === 'terjual_invoice') {
            $select.after(
                '<small class="text-primary d-block mt-1 info-kembali">' +
                '<i class="fa fa-file-invoice"></i> Barang dianggap sudah terjual dan tidak masuk kembali ke stok.' +
                '</small>'
            );
        }
    });

    // Validasi qty kembali tidak melebihi sisa
    $(document).on('input', '.qty-kembali', function() {
        var max   = parseFloat($(this).data('sisa'));
        var val   = parseFloat($(this).val()) || 0;
        var nama  = $(this).data('nama');
        var satuan = $(this).data('satuan');
        if (val > max) {
            Swal.fire({
                icon: 'warning', title: 'Melebihi Sisa',
                text: nama + ': maksimal ' + max + ' ' + satuan,
                confirmButtonText: 'OK'
            });
            $(this).val(max);
        }
    });

    // Simpan pengembalian
    $('#btn-save-return').on('click', function() {
        var peminjaman_id = $('#return_peminjaman_id').val();
        var detail_ids    = $('input[name="detail_id[]"]').map(function() { return $(this).val(); }).get();
        var qty_kembalian = $('input[name="qty_kembali[]"]').map(function() { return $(this).val(); }).get();

        var formData = new FormData();
        formData.append('peminjaman_id', peminjaman_id);
        detail_ids.forEach(function(v) { formData.append('detail_id[]', v); });
        qty_kembalian.forEach(function(v) { formData.append('qty_kembali[]', v); });

        $.ajax({
            url: "<?= site_url('mac_peminjaman/catat_kembali') ?>",
            type: 'POST', data: formData,
            processData: false, contentType: false, dataType: 'JSON',
            success: function(res) {
                if (res.status) {
                    $('#returnModal').modal('hide');
                    Swal.fire({ icon: 'success', title: 'Pengembalian dicatat!', timer: 1500, showConfirmButton: false });
                    table.ajax.reload();
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: res.error });
                }
            }
        });
    });
});
</script>
