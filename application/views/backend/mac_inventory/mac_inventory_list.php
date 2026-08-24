<style>
    .text-ellipsis {
        display: inline-block;
        max-width: 250px; /* sesuaikan */
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        vertical-align: middle;
    }
</style>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Master Barang <span id="title">Nasional</span></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <a class="btn btn-primary btn-sm" onclick="open_modal(0)">
                        <i class="fa fa-plus"></i>&nbsp;Add Data
                    </a>
                    <div class="d-flex align-items-center">
                        <?php if ($is_nasional): ?>
                            <div class="d-flex align-items-center">
                                <label class="mr-2 mb-0 text-muted">Cabang:</label>
                                <select id="filter-cabang" class="form-control form-control-sm"
                                        style="background:#242d4a; color:#fff; border:none;">
                                    <option value="0" selected>Nasional</option>
                                    <?php foreach ($list_cabang as $c): ?>
                                        <?php if ($c->id == 1) continue; ?>
                                        <option value="<?= $c->id ?>">
                                            <?= ucwords(strtolower(str_replace('MAC ', '', $c->nama_cabang))) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <?php else: ?>
                        <?php endif; ?>
                        <a class="btn btn-primary btn-sm m-1" href="<?= site_url('mac_mutasi_stok') ?>">
                            <i class="fa fa-list"></i>&nbsp;Mutasi Stok
                        </a>
                        <button type="button" class="btn btn-primary btn-sm" id="btn-stok-menipis">
                            <i class="fa fa-box"></i>&nbsp;&nbsp;Stok Menipis
                            <span class="badge badge-danger ml-1" id="badge-stok-menipis" style="display:none;"></span>
                        </button>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table id="table-list" class="table table-bordered table-striped display nowrap w-100 mb-4">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th style="width:140px;">Action</th>
                                    <th>Kode</th>
                                    <th>Nama Barang</th>
                                    <th>Kategori</th>
                                    <th>Satuan</th>
                                    <th>
                                        Harga Beli
                                        <span
                                            data-toggle="tooltip"
                                            title="Harga beli yang ditampilkan adalah harga beli terakhir dari barang yang masuk."
                                            style="cursor:help;color:#17a2b8">ⓘ</span>
                                    </th>
                                    <th>
                                        Harga Jual
                                        <span
                                            data-toggle="tooltip"
                                            title="Harga jual terbaru yang berlaku untuk cabang ini."
                                            style="cursor:help;color:#17a2b8">ⓘ</span>
                                    </th>
                                    <th>
                                        Stok Aktual
                                        <span
                                            data-toggle="tooltip"
                                            title="Stok aktual adalah jumlah stok fisik yang tersedia di sistem. Nilainya mencerminkan stok riil yang tercatat di gudang."
                                            style="cursor:help;color:#17a2b8">ⓘ</span>
                                    </th>

                                    <th>
                                        Stok Efektif
                                        <span
                                            data-toggle="tooltip"
                                            title="Stok efektif adalah stok yang dapat digunakan. Dihitung dari stok aktual dikurangi jumlah barang yang sedang dipinjam dan belum dikembalikan."
                                            style="cursor:help;color:#17a2b8">ⓘ</span>
                                    </th>
                                    <th>Dibuat</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Action</th>
                                    <th>Kode</th>
                                    <th>Nama Barang</th>
                                    <th>Kategori</th>
                                    <th>Satuan</th>
                                    <th>Harga Beli</th>
                                    <th>Harga Jual</th>
                                    <th>Stok Aktual</th>
                                    <th>Stok Efektif</th>
                                    <th>Dibuat</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========== MODAL FORM TAMBAH / EDIT ========== -->
<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="modalFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title mb-0" id="modalFormLabel">Add Data</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="scale: 0.6;">
                    <span aria-hidden="true" style="position: relative; top: -10px; left: 20px">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form">
                    <input type="hidden" name="id" id="id" value="0">

                    <div class="form-group row align-items-center">
                        <label class="col-4 col-form-label col-form-label">Kode Barang <span class="text-danger">*</span></label>
                        <div class="col-8">
                            <input type="text" name="kode_produk" id="kode_produk"
                                class="form-control form-control" placeholder="Kode Barang" readonly>
                        </div>
                    </div>

                    <div class="form-group row align-items-center">
                        <label class="col-4 col-form-label col-form-label">Nama Barang <span class="text-danger">*</span></label>
                        <div class="col-8">
                            <input type="text" name="nama_produk" id="nama_produk"
                                class="form-control form-control" placeholder="Nama Barang">
                        </div>
                    </div>
                    
                    <div class="form-group row align-items-center" id="kategori-col">
                        <label class="col-4 col-form-label col-form-label">Kategori <span class="text-danger">*</span></label>
                        <div class="col-8">
                            <select name="kategori" id="kategori" class="form-control form-control">
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Sparepart">Sparepart</option>
                                <option value="Pelumas">Pelumas</option>
                                <option value="Bahan">Bahan</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row align-items-center" id="satuan-col">
                        <label class="col-4 col-form-label col-form-label">Satuan <span class="text-danger">*</span></label>
                        <div class="col-8">
                            <select name="satuan" id="satuan" class="form-control form-control">
                                <option value="">-- Pilih Satuan --</option>
                                <option value="Pcs">Pcs</option>
                                <option value="Btl">Btl</option>
                                <option value="Cm">Cm</option>
                                <option value="Gram">Gram</option>
                                <option value="M">M</option>
                                <option value="Set">Set</option>
                                <option value="Ltr">Ltr</option>
                                <option value="Ml">Ml</option>
                                <option value="Kg">Kg</option>
                                <option value="Mm">Mm</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row align-items-center" id="harga-jual-col">
                        <label class="col-4 col-form-label col-form-label">Harga Jual <span class="text-danger">*</span></label>
                        <div class="col-8">
                            <div class="input-group input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" name="harga_jual" id="harga_jual"
                                    class="form-control form-control" placeholder="0">
                            </div>
                        </div>
                    </div>

                    <!-- Stok minimal -->
                    <div class="form-group row align-items-center" id="stok-minimal-col">
                        <label class="col-4 col-form-label col-form-label">Stok Minimal</label>
                        <div class="col-8">
                            <input type="number" name="stok_minimal" id="stok_minimal"
                                class="form-control form-control" placeholder="0" min="0">
                        </div>
                    </div>

                    <div class="form-group row align-items-center" id="is-active-col">
                        <label class="col-4 col-form-label col-form-label">Is Active <span class="text-danger">*</span></label>
                        <div class="col-8">
                            <input type="radio" name="is_active" id="is_active1" style="position: relative; top: 6px" value="1" checked><label for="is_active1" style="position: relative; top: 4px;left: 7px">Active</label>
                            <span style="margin-right: 14px;"></span>
                            <input type="radio" name="is_active" id="is_active2" style="position: relative; top: 6px" value="0"><label for="is_active2" style="position: relative; top: 4px;left: 7px">Non Active</label>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer py-2">
                <div id="loading-modal" style="display:none;" class="mr-auto">
                    <i class="fa fa-spinner fa-spin"></i> Menyimpan...
                </div>
                <button type="button" class="btn btn-secondary btn" data-dismiss="modal">
                    Batal
                </button>
                <button type="button" class="btn btn-primary btn" id="btn-simpan" onclick="submit_form()">
                    <i class="fa fa-save"></i>&nbsp;<span id="label-simpan">Save</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Stok Menipis -->
<div class="modal fade" id="stokMenipisModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title font-weight-bold" style="color:white;">
                    <i class="fa fa-box"></i> Stok Menipis / Habis
                </h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body p-1">
                <table class="table table-bordered table-sm mb-0 mt-1">
                    <thead id="thead-stok-menipis"></thead>
                    <tbody id="tbody-stok-menipis">
                        <tr>
                            <td colspan="7" class="text-center text-muted py-3">Loading...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDetailCabang" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    Stok per Cabang - <span id="modal-cabang-nama-barang"></span>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body p-2">
                <table class="table table-bordered table-sm mb-0">
                    <thead style="background:#242d4a; color:white;">
                        <tr>
                            <th width="5%">No</th>
                            <th>Cabang</th>
                            <th width="12%">Kode</th>
                            <th width="12%" class="text-center">Stok</th>
                            <th width="14%" class="text-center">Stok Minimal</th>
                            <th width="12%" class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-detail-cabang">
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">Loading...</td>
                        </tr>
                    </tbody>
                    <tfoot id="tfoot-detail-cabang"></tfoot>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-sm" id="btn-export-cabang">
                    <i class="fa fa-file-excel"></i> Export Excel
                </button>

                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ========== MODAL SET STOK AWAL ========== -->
<div class="modal fade" id="modalSetStokAwal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fa fa-database"></i>
                    Set Stok Awal - <span id="stok-awal-nama-barang"></span>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body p-2">
                <div class="alert alert-info m-3 mb-2 py-2">
                    <i class="fa fa-info-circle"></i>
                    Stok awal hanya bisa di-set <strong>sekali</strong> per cabang.
                </div>
                <form id="form-stok-awal">
                    <input type="hidden" name="inventory_id" id="stok_awal_inventory_id">
                    <table class="table table-bordered table-sm mb-0">
                        <thead style="background:#242d4a; color:white;">
                            <tr>
                                <th width="4%">No</th>
                                <th width="12%">Cabang</th>
                                <th width="10%" class="text-center">Stok Saat Ini</th>
                                <th width="13%">Stok Awal</th>
                                <th width="13%">Stok Minimal</th>
                                <th width="18%">Harga Beli</th>
                                <th width="18%">Harga Jual</th>
                                <th width="10%" class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-stok-awal">
                            <tr>
                                <td colspan="6" class="text-center py-3">
                                    <i class="fa fa-spinner fa-spin"></i> Loading...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                    Batal
                </button>
                <button type="button" class="btn btn-primary btn-sm" id="btn-save-stok-awal">
                    <i class="fa fa-save"></i> Save
                </button>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>

<script>
    const params = new URLSearchParams(window.location.search);

    const inventoryId = params.get('inventory_id');
    const cabangId = params.get('cabang_id');

    if (inventoryId && cabangId) {
        open_modal(inventoryId, cabangId);
    }

    var filterCabangId = 0;

    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();

        // ===== FORMAT RUPIAH untuk input di modal =====
        $(document).on('input', '.input-rupiah', function() {
            var raw = $(this).val().replace(/[^0-9]/g, '');
            $(this).val(raw ? parseInt(raw).toLocaleString('id-ID') : '');
        });

        // ===== SET STOK AWAL =====
        window.set_stok_awal = function(inventory_id, nama_barang) {
            $('#stok_awal_inventory_id').val(inventory_id);
            $('#stok-awal-nama-barang').text(nama_barang);
            $('#tbody-stok-awal').html(
                '<tr><td colspan="8" class="text-center py-3">' +
                '<i class="fa fa-spinner fa-spin"></i> Loading...</td></tr>'
            );
            $('#modalSetStokAwal').modal('show');

            $.ajax({
                url: "<?= site_url('mac_inventory/get_stok_awal_cabang') ?>",
                type: 'POST',
                dataType: 'JSON',
                data: { inventory_id: inventory_id },
                success: function(res) {
                    if (!res.status) {
                        $('#tbody-stok-awal').html(
                            '<tr><td colspan="8" class="text-center text-danger">Gagal memuat data.</td></tr>'
                        );
                        return;
                    }

                    var rows = '';
                    $.each(res.rows, function(i, d) {
                        var namaCabang = d.nama_cabang
                            .replace(/^MAC\s*/i, '') // Hapus "MAC" di awal
                            .toLowerCase()           // Jadi huruf kecil semua
                            .replace(/\b\w/g, function(char) {
                                return char.toUpperCase(); // Ubah jadi Title Case
                            });
                        var stok     = parseFloat(d.stok_saat_ini);
                        var sudahAda = stok > 0;
                        var badge    = sudahAda
                            ? '<span class="badge badge-warning">Sudah ada stok</span>'
                            : '<span class="badge badge-secondary">Belum ada stok</span>';

                        var cid = d.cabang_id;
                        rows += '<tr>' +
                            '<td class="text-center">' + (i + 1) + '</td>' +
                            '<td>' + namaCabang +
                                // '<input type="hidden" name="cabang_id[]" value="' + d.cabang_id + '">' +
                                '<input type="hidden" name="cabang_id[' + cid + ']" value="' + cid + '">' +
                            '</td>' +
                            '<td class="text-center">' + stok + '</td>' +

                            // Stok Awal
                            '<td>' +
                                '<input type="number" class="form-control form-control-sm"' +
                                ' name="stok_awal[' + cid + ']" placeholder="0" min="0"' +
                                (sudahAda ? ' disabled' : '') + '>' +
                            '</td>' +

                            // Stok Minimal — tampilkan nilai existing jika ada
                            '<td>' +
                                '<input type="number" class="form-control form-control-sm"' +
                                ' name="stok_minimal[' + cid + ']" placeholder="0" min="0"' +
                                ' value="' + (parseFloat(d.stok_minimal) || '') + '"' +
                                (sudahAda ? ' disabled' : '') + '>' +
                            '</td>' +

                            // Harga Beli
                            '<td>' +
                                '<div class="input-group input-group-sm">' +
                                    '<div class="input-group-prepend"><span class="input-group-text">Rp</span></div>' +
                                    '<input type="text" class="form-control input-rupiah" name="harga_beli[' + cid + ']"' +
                                    ' placeholder="0"' +
                                    ' value="' + (d.harga_beli ? parseInt(d.harga_beli).toLocaleString("id-ID") : '') + '"' +
                                    (sudahAda ? ' disabled' : '') + '>' +
                                '</div>' +
                            '</td>' +
                            
                            // Harga Jual — tampilkan override jika ada, fallback ke default
                            '<td>' +
                                '<div class="input-group input-group-sm">' +
                                    '<div class="input-group-prepend"><span class="input-group-text">Rp</span></div>' +
                                    '<input type="text" class="form-control input-rupiah" name="harga_jual[' + cid + ']"' +
                                    ' placeholder="0"' +
                                    ' value="' + (d.harga_jual ? parseInt(d.harga_jual).toLocaleString("id-ID") : '') + '"' +
                                    (sudahAda ? ' disabled' : '') + '>' +
                                '</div>' +
                            '</td>' +


                            '<td class="text-center">' + badge + '</td>' +
                        '</tr>';
                    });

                    $('#tbody-stok-awal').html(
                        rows || '<tr><td colspan="8" class="text-center text-muted">Tidak ada cabang</td></tr>'
                    );
                },
                error: function() {
                    $('#tbody-stok-awal').html(
                        '<tr><td colspan="8" class="text-center text-danger">Error memuat data.</td></tr>'
                    );
                }
            });
        };

        // Simpan stok awal
        $('#btn-save-stok-awal').on('click', function() {
            // Validasi minimal 1 baris terisi
            var adaIsian = false;
            $('input[name="stok_awal[]"]:not(:disabled)').each(function() {
                if (parseFloat($(this).val()) > 0) { adaIsian = true; return false; }
            });

            // if (!adaIsian) {
            //     Swal.fire({ icon: 'warning', title: 'Perhatian',
            //         text: 'Isi minimal 1 stok awal untuk disimpan.' });
            //     return;
            // }

            $('#btn-save-stok-awal').prop('disabled', true);

            $.ajax({
                url: "<?= site_url('mac_inventory/save_stok_awal') ?>",
                type: 'POST',
                data: $('#form-stok-awal').serialize(),
                dataType: 'JSON',
                success: function(res) {
                    $('#btn-save-stok-awal').prop('disabled', false);

                    var html = '';
                    if (res.message) html += res.message;
                    if (res.warning) html += '<br><small class="text-warning">' + res.warning + '</small>';

                    Swal.fire({
                        icon:               res.status ? 'success' : 'error',
                        title:              res.status ? 'Berhasil!' : 'Gagal',
                        html:               html || res.error || '',
                        timer:              res.status ? 2000 : undefined,
                        showConfirmButton:  !res.status
                    }).then(function() {
                        if (res.status) {
                            $('#modalSetStokAwal').modal('hide');
                            table.ajax.reload(null, false);
                        }
                    });
                },
                error: function() {
                    $('#btn-save-stok-awal').prop('disabled', false);
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan server.' });
                }
            });
        });

        var table;
        let inventoryIdExport = 0;
        const sessionCabangId = <?= (int) $cabang_id ?>;

        const namaCabang = {
            1: 'Nasional',
            2: 'Jawa Tengah',
            3: 'Jatim 1',
            4: 'Jabar 1',
            5: 'Jabar 2',
            6: 'Riau',
            7: 'Lampung',
            8: 'Banten',
            9: 'Sumbagsel 1',
            10: 'Sumbagsel 2',
            11: 'Bali',
            12: 'Jatim 2'
        };

        // Set judul saat halaman pertama dibuka
        $('#title').text(namaCabang[sessionCabangId] || 'Nasional');

        $('#filter-cabang').on('change', function () {

            filterCabangId = $(this).val();

            // Kalau "Semua Cabang" dipilih, tampilkan Nasional
            if (filterCabangId == 0 || filterCabangId == '') {
                $('#title').text('Nasional');
            } else {
                $('#title').text(namaCabang[filterCabangId] || 'Nasional');
            }

            if (filterCabangId != 0 && filterCabangId != '') {
                $('#input-only-cabang').show();
                $('.harga-th').hide();
            } else {
                $('#input-only-cabang').hide();
                $('.harga-th').show();
                $('#harga-jual-col').hide();
                $('#stok-minimal-col').hide();
            }

            table.ajax.reload();
        });

        window.detail_stok_cabang = function(inventory_id, nama_barang) {
            inventoryIdExport = inventory_id;
            $('#modal-cabang-nama-barang').text(nama_barang);
            $('#tbody-detail-cabang').html(
                '<tr><td colspan="6" class="text-center py-3">' +
                '<i class="fa fa-spinner fa-spin"></i> Loading...</td></tr>'
            );
            $('#tfoot-detail-cabang').html('');
            $('#modalDetailCabang').modal('show');

            $('#btn-export-cabang').click(function () {
                window.open(
                    "<?= site_url('mac_inventory/export_stok_per_cabang/') ?>" + inventoryIdExport
                );
            });
    
            $.ajax({
                url: "<?= site_url('mac_inventory/get_stok_per_cabang') ?>",
                type: 'POST',
                dataType: 'JSON',
                data: { inventory_id: inventory_id },
                success: function(res) {
                    if (!res.status) {
                        $('#tbody-detail-cabang').html(
                            '<tr><td colspan="6" class="text-center text-danger">Gagal memuat data.</td></tr>'
                        );
                        return;
                    }
        
                    var rows = '';
                    $.each(res.rows, function(i, d) {
                        var stok    = parseFloat(d.stok_saat_ini);
                        var minimal = parseFloat(d.stok_minimal);
        
                        var statusBadge = '';
                        var stokClass   = '';
                        if (stok <= 0) {
                            statusBadge = '<span class="badge badge-danger">Habis</span>';
                            stokClass   = 'text-danger font-weight-bold';
                        } else if (stok <= minimal) {
                            statusBadge = '<span class="badge badge-warning">Menipis</span>';
                            stokClass   = 'text-warning font-weight-bold';
                        } else {
                            statusBadge = '<span class="badge badge-success">Aman</span>';
                            stokClass   = 'text-success font-weight-bold';
                        }
        
                        var namaCabang = d.nama_cabang
                            .replace(/^MAC\s+/i, '') // Hilangkan "MAC " di depan
                            .toLowerCase()
                            .replace(/\b\w/g, function(l) {
                                return l.toUpperCase();
                            });

                        rows += '<tr>' +
                            '<td class="text-center">' + (i + 1) + '</td>' +
                            '<td>' + namaCabang + '</td>' +
                            '<td class="text-center">' + d.kode + '</td>' +
                            '<td class="text-center ' + stokClass + '">' +
                                stok + ' ' + d.satuan +
                            '</td>' +
                            '<td class="text-center">' + minimal + ' ' + d.satuan + '</td>' +
                            '<td class="text-center">' + statusBadge + '</td>' +
                        '</tr>';
                    });
        
                    $('#tbody-detail-cabang').html(rows || '<tr><td colspan="6" class="text-center text-muted">Tidak ada data</td></tr>');
        
                    // Footer total
                    $('#tfoot-detail-cabang').html(
                        '<tr style="background:#242d4a; color:white; font-weight:bold;">' +
                            '<td colspan="3" class="text-right">Total Stok Nasional</td>' +
                            '<td class="text-center">' + res.total_stok + ' ' +
                                (res.rows.length > 0 ? res.rows[0].satuan : '') +
                            '</td>' +
                            '<td colspan="2"></td>' +
                        '</tr>'
                    );
                },
                error: function() {
                    $('#tbody-detail-cabang').html(
                        '<tr><td colspan="6" class="text-center text-danger">Error memuat data.</td></tr>'
                    );
                }
            });
        };

        // ===== DATATABLE =====
        table = $('#table-list').DataTable({
            responsive: true,
            scrollX: true,
            processing: true,
            serverSide: true,
            pageLength: 25, // Default Show Entries 25
            order: [],
            ajax: {
                url: "<?= site_url('mac_inventory/get_list') ?>",
                type: "POST",
                data: function(d) {
                    d.filter_cabang = filterCabangId;
                }
            },
            language: {
                infoFiltered: ""
            },
            columnDefs: [
                { targets: [0, 1], orderable: false },
                { targets: [1], className: 'dt-body-nowrap' },
                { targets: [8, 9], className: 'text-center' },
                {
                    targets: 3,
                    render: function(data, type, row) {
                        return '<span class="text-ellipsis" title="' + data + '">' +
                            data +
                            '</span>';
                    }
                }
            ]
        });

        // ===== FORMAT RUPIAH =====
        $('#harga_beli, #harga_jual').on('input', function() {
            var val = $(this).val().replace(/\D/g, '');
            $(this).val(val ? parseInt(val).toLocaleString('id-ID') : '');
        });

        // ===== AUTO-GENERATE KODE PRODUK SAAT KATEGORI DIPILIH =====
        $('#kategori').on('change', function() {
            var kategori = $(this).val();
            if (kategori && $('#id').val() == 0) { // Hanya saat tambah data (id = 0)
                $.ajax({
                    url: "<?= site_url('mac_inventory/generate_kode') ?>",
                    type: "POST",
                    data: { kategori: kategori },
                    dataType: "JSON",
                    success: function(res) {
                        if (res.status) {
                            $('#kode_produk').val(res.kode);
                        }
                    }
                });
            }
        });

        // ===== RESET FORM SAAT MODAL DITUTUP =====
        $('#modalForm').on('hidden.bs.modal', function() {
            $('#form')[0].reset();
            $('#id').val(0);
            $('#harga_beli, #harga_jual').val('');
            $('#label-simpan').text('Save');
            // hapus error validasi
            $('#form').find('.is-invalid').removeClass('is-invalid');
            $('#form').find('label.error').remove();
        });

        // ===== VALIDASI =====
        $("#form").validate({
            rules: {
                kode_produk: { required: true },
                nama_produk: { required: true },
                kategori:    { required: true },
                satuan:      { required: true },
                harga_jual:  { required: true },
            },
            messages: {
                kode_produk: { required: "Kode produk wajib diisi" },
                nama_produk: { required: "Nama produk wajib diisi" },
                kategori:    { required: "Kategori wajib diisi" },
                satuan:      { required: "Satuan wajib dipilih" },
                harga_jual:  { required: "Harga jual wajib diisi" },
            },
            highlight:   function(el) { $(el).addClass('is-invalid'); },
            unhighlight: function(el) { $(el).removeClass('is-invalid'); },
            errorPlacement: function(err, el) { err.insertAfter(el.closest('.input-group, select, input')); },
            focusInvalid: false,
        });
    });

    // ===== BUKA MODAL =====
    function open_modal(id, filter_cabang) {
        filter_cabang = filter_cabang || 0;
        $('#id').val(id);

        if (id != 0) {
            // MODE EDIT
            $('#label-simpan').text('Update');
            $('#kode_produk').prop('readonly', true); // Readonly di mode edit
            $('#modalFormLabel').html('Update Data');

            sessionCabang = <?= (int) $cabang_id ?>;

        if (filterCabangId == 0 && sessionCabang == 1){
                $('#harga-jual-col').hide();
                $('#stok-minimal-col').hide();
                $('#is-active-col').show();
            } else {
                $('#harga-jual-col').show();
                $('#stok-minimal-col').show();
                $('#is-active-col').hide();
                $('#kategori-col').hide();
                $('#satuan-col').hide();
                $('#nama_produk').prop('readonly', true);
            }

            $.ajax({
                url: "<?= site_url('mac_inventory/get_data') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                data: { filter_cabang: filter_cabang },
                success: function(d) {
                    $('#kode_produk').val(d.kode_produk);
                    $('#nama_produk').val(d.nama_produk);
                    $('#kategori').val(d.kategori);
                    $('#satuan').val(d.satuan);
                    // Harga jual — kosong jika null (belum di-set)
                    var hargaJual = (d.harga_jual_tampil !== null && d.harga_jual_tampil !== undefined && d.harga_jual_tampil > 0)
                        ? parseInt(d.harga_jual_tampil).toLocaleString('id-ID')
                        : '';
                    $('#harga_jual').val(hargaJual);
                    
                    var stokMinimal = (d.stok_minimal_tampil !== null && d.stok_minimal_tampil !== undefined)
                        ? d.stok_minimal_tampil
                        : '';
                    $('#stok_minimal').val(stokMinimal);
                    // set radio is_active
                    if (d.is_active == 1) {
                        $('#is_active1').prop('checked', true);
                    } else {
                        $('#is_active2').prop('checked', true);
                    }
                    $('#modalForm').modal('show');
                },
                error: function() {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal memuat data' });
                }
            });
        } else {
            // MODE TAMBAH
            $('#label-simpan').text('Save');
            $('#kode_produk').prop('readonly', true); // Readonly di mode tambah
            $('#modalFormLabel').html('Add Data');
            $('#modalForm').modal('show');
            $('#harga-jual-col').hide();
            $('#stok-minimal-col').hide();

            if (filterCabangId == 0 && sessionCabang == 1){
                $('#harga-jual-col').hide();
                $('#stok-minimal-col').hide();
            } else {
                $('#harga-jual-col').hide();
                $('#stok-minimal-col').hide();
                $('#kategori-col').show();
                $('#satuan-col').show();
                $('#nama_produk').prop('readonly', false);
            }

        }
    }

    // ===== SUBMIT FORM =====
    function submit_form() {
        if (!$('#form').valid()) return false;

        var id  = $('#id').val();
        var url = id == 0
            ? "<?= site_url('mac_inventory/add') ?>"
            : "<?= site_url('mac_inventory/update') ?>";

        $('#loading-modal').show();
        $('#btn-simpan').prop('disabled', true);

        let formData = $('#form').serializeArray();

        formData.push({
            name: 'filter_cabang',
            value: filterCabangId
        });

        $.ajax({
            url: url,
            type: "POST",
            data: $.param(formData),
            dataType: "JSON",
            success: function(res) {
                $('#loading-modal').hide();
                $('#btn-simpan').prop('disabled', false);

                if (res.status) {
                    $('#modalForm').modal('hide');

                    Swal.fire({
                        icon: 'success',
                        title: res.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        location.reload();
                    });

                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: res.error
                    });
                }
            },
            error: function() {
                $('#loading-modal').hide();
                $('#btn-simpan').prop('disabled', false);

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan'
                });
            }
        });
    }

    // ===== RELOAD TABLE =====
    function reload_table() { table.ajax.reload(null, false); }

    // ===== DELETE =====
    function delete_data(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?= site_url('mac_inventory/delete') ?>/" + id,
                    type: "POST",
                    dataType: "JSON",
                    success: function(data) {
                        Swal.fire({ icon: 'success', title: 'Data has been deleted', showConfirmButton: false, timer: 1500 });
                        reload_table();
                    }
                });
            }
        });
    }

    // ===== STOK MENIPIS =====

    // Load badge count saat halaman pertama kali dibuka
    function loadBadgeStokMenipis() {
        $.ajax({
            url: "<?= site_url('mac_inventory/get_stok_menipis') ?>",
            type: 'GET',
            dataType: 'JSON',
            success: function(res) {
                if (res.data && res.data.length > 0) {
                    $('#badge-stok-menipis').text(res.data.length).show();
                    $('#btn-stok-menipis').show();
                } else {
                    $('#badge-stok-menipis').hide();
                    $('#btn-stok-menipis').hide();
                }
            }
        });
    }

    loadBadgeStokMenipis();

    // Buka modal dan load data
    $('#btn-stok-menipis').on('click', function() {
        $('#stokMenipisModal').modal('show');

        $.ajax({
            url: "<?= site_url('mac_inventory/get_stok_menipis') ?>",
            type: 'GET',
            dataType: 'JSON',
            success: function(res) {
                var data        = res.data;
                var is_nasional = res.is_nasional;
                var rows        = '';

                // Update thead — tambah kolom Cabang jika Nasional
                if (is_nasional) {
                    $('#thead-stok-menipis').html(
                        '<tr>' +
                            '<th width="4%">No</th>' +
                            '<th>Kode</th>' +
                            '<th>Nama Barang</th>' +
                            '<th>Kategori</th>' +
                            '<th>Cabang</th>' +
                            '<th class="text-center">Stok Saat Ini</th>' +
                            '<th class="text-center">Stok Minimal</th>' +
                            '<th class="text-center">Status</th>' +
                        '</tr>'
                    );
                } else {
                    $('#thead-stok-menipis').html(
                        '<tr>' +
                            '<th width="4%">No</th>' +
                            '<th>Kode</th>' +
                            '<th>Nama Barang</th>' +
                            '<th>Kategori</th>' +
                            '<th class="text-center">Stok Saat Ini</th>' +
                            '<th class="text-center">Stok Minimal</th>' +
                            '<th class="text-center">Status</th>' +
                        '</tr>'
                    );
                }

                var colspan = is_nasional ? 8 : 7;

                if (data.length === 0) {
                    rows = '<tr><td colspan="' + colspan + '" class="text-center text-success py-3">' +
                        '<i class="fa fa-check-circle"></i> Semua stok dalam kondisi aman.' +
                        '</td></tr>';
                } else {
                    $.each(data, function(i, d) {
                        var stok    = parseFloat(d.stok_saat_ini);
                        var minimal = parseFloat(d.stok_minimal);
                        var status  = stok <= 0
                            ? '<span class="badge badge-danger">Habis</span>'
                            : '<span class="badge badge-warning">Menipis</span>';
                        var stokClass = stok <= 0
                            ? 'text-danger font-weight-bold'
                            : 'text-warning font-weight-bold';

                        // Nama cabang — hilangkan prefix "MAC "
                        var namaCabang = d.nama_cabang
                            ? d.nama_cabang.replace(/^MAC\s+/i, '').toLowerCase()
                                .replace(/\b\w/g, function(l) { return l.toUpperCase(); })
                            : '';

                        rows += '<tr>' +
                            '<td class="text-center">' + (i + 1) + '</td>' +
                            '<td>' + d.kode_produk + '</td>' +
                            '<td>' + d.nama_produk + '</td>' +
                            '<td>' + d.kategori + '</td>' +
                            (is_nasional ? '<td>' + namaCabang + '</td>' : '') +
                            '<td class="text-center ' + stokClass + '">' + stok + ' ' + d.satuan + '</td>' +
                            '<td class="text-center">' + minimal + ' ' + d.satuan + '</td>' +
                            '<td class="text-center">' + status + '</td>' +
                        '</tr>';
                    });
                }

                $('#tbody-stok-menipis').html(rows);
            },
            error: function() {
                $('#tbody-stok-menipis').html(
                    '<tr><td colspan="7" class="text-center text-danger">Gagal memuat data.</td></tr>'
                );
            }
        });
    });
</script>