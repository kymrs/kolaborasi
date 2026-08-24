<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title_view ?></h1>
    </div>
    <div class="row">
        <div class="col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header text-right">
                    <a class="btn btn-secondary btn-sm" href="<?= base_url('mac_inventory') ?>">
                        <i class="fas fa-chevron-left"></i>&nbsp;Kembali
                    </a>
                </div>
                <div class="card-body">

                    <!-- Info Barang -->
                    <div class="alert alert-info" id="info-barang">
                        <i class="fa fa-spinner fa-spin"></i> Memuat data barang...
                    </div>

                    <form id="form-mutasi">
                        <input type="hidden" name="inventory_id" id="inventory_id" value="<?= $id ?>">

                        <div class="form-group">
                            <label>Jenis Mutasi <span class="text-danger">*</span></label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="tipe" id="tipe_masuk" value="masuk" checked>
                                    <label class="form-check-label text-success" for="tipe_masuk">
                                        <i class="fa fa-arrow-down"></i> <strong>Stok Masuk</strong>
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="tipe" id="tipe_keluar" value="keluar">
                                    <label class="form-check-label text-danger" for="tipe_keluar">
                                        <i class="fa fa-arrow-up"></i> <strong>Stok Keluar</strong>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Jumlah <span class="text-danger">*</span></label>
                            <input type="number" name="jumlah" id="jumlah" class="form-control form-control-sm" min="1" placeholder="0">
                        </div>

                        <div class="form-group">
                            <label>No. Referensi / Transaksi</label>
                            <input type="text" name="referensi" id="referensi" class="form-control form-control-sm" placeholder="Contoh: INV-2025-001 atau WO-001">
                            <small class="text-muted">Isi dengan nomor invoice/work order terkait (opsional)</small>
                        </div>

                        <div class="form-group">
                            <label>Keterangan</label>
                            <textarea name="keterangan" id="keterangan" class="form-control form-control-sm" rows="3" placeholder="Keterangan tambahan..."></textarea>
                        </div>

                        <hr>
                        <div id="loading-mutasi" style="display:none;"><p><i class="fa fa-spinner fa-spin"></i> Menyimpan...</p></div>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fa fa-save"></i>&nbsp;Simpan Mutasi
                        </button>
                        <a href="<?= base_url('mac_inventory/log/' . $id) ?>" class="btn btn-info btn-sm">
                            <i class="fa fa-history"></i>&nbsp;Lihat Riwayat
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>

<script>
$(document).ready(function() {
    var id = <?= $id ?>;

    // ===== LOAD INFO BARANG =====
    $.ajax({
        url: "<?= site_url('mac_inventory/get_data') ?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(d) {
            $('#info-barang').html(
                '<strong>' + d.kode_produk + ' — ' + d.nama_produk + '</strong><br>' +
                'Kategori: ' + d.kategori + ' | Satuan: ' + d.satuan + '<br>' +
                '<span class="badge badge-' + (d.stok_aktual <= 0 ? 'danger' : (d.stok_aktual <= 10 ? 'warning' : 'success')) + ' mt-1">' +
                'Stok Saat Ini: ' + d.stok_aktual + ' ' + d.satuan +
                '</span>'
            );
        }
    });

    // ===== SUBMIT MUTASI =====
    $('#form-mutasi').submit(function(e) {
        e.preventDefault();
        if (!$(this).valid()) return false;

        $('#loading-mutasi').show();
        $('[type=submit]').prop('disabled', true);

        $.ajax({
            url: "<?= site_url('mac_inventory/mutasi_save') ?>",
            type: "POST",
            data: $(this).serialize(),
            dataType: "JSON",
            success: function(res) {
                $('#loading-mutasi').hide();
                $('[type=submit]').prop('disabled', false);
                if (res.status) {
                    Swal.fire({
                        icon: 'success',
                        title: res.message,
                        text: 'Stok sekarang: ' + res.stok_sesudah,
                        showConfirmButton: false,
                        timer: 2000
                    }).then(function() {
                        location.reload();
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: res.error });
                }
            },
            error: function() {
                $('#loading-mutasi').hide();
                $('[type=submit]').prop('disabled', false);
                Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan' });
            }
        });
    });

    // ===== VALIDASI =====
    $('#form-mutasi').validate({
        rules: {
            jumlah: { required: true, min: 1 },
        },
        messages: {
            jumlah: { required: "Jumlah wajib diisi", min: "Jumlah minimal 1" }
        },
        highlight:   function(el) { $(el).addClass('is-invalid'); },
        unhighlight: function(el) { $(el).removeClass('is-invalid'); },
        focusInvalid: false,
    });
});
</script>