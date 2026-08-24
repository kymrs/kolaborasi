<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title_view ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header text-right">
                    <a class="btn btn-primary btn-sm" href="<?= base_url('mac_peminjaman') ?>">
                        <i class="fas fa-chevron-left"></i>&nbsp;Back
                    </a>
                </div>
                <div class="card-body">
                    <form id="form">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-4">Kode Peminjaman</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="kode_pinjam" readonly placeholder="Kode Peminjaman">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4">Tgl Pinjam</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control datepicker" id="tgl_pinjam" name="tgl_pinjam" placeholder="DD-MM-YYYY" autocomplete="off" style="cursor: pointer;">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4">Est. Tgl Kembali</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control datepicker" id="tgl_kembali" name="tgl_kembali" placeholder="DD-MM-YYYY" autocomplete="off" style="cursor: pointer;">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-4">Keterangan</label>
                                    <div class="col-sm-8">
                                        <textarea class="form-control" id="keterangan" name="keterangan" rows="4" placeholder="Keperluan Peminjaman"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="mb-3">
                            <button type="button" class="btn btn-primary btn-sm" id="btn-add-item">
                                <i class="fa fa-plus"></i> Tambah Barang
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead style="background:#242d4a; color:white; text-align:center;">
                                    <tr>
                                        <th width="4%">No</th>
                                        <th width="40%">Barang</th>
                                        <th width="15%">Stok Tersedia</th>
                                        <th width="15%">Qty Pinjam</th>
                                        <th width="15%">Keterangan</th>
                                        <th width="11%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="item-container"></tbody>
                            </table>
                        </div>

                        <div id="loading" style="display:none;"><p>Loading...</p></div>

                        <input type="hidden" name="id" id="id" value="<?= $id ?>">
                        <button type="submit" class="btn btn-primary btn-sm aksi mt-3"></button>
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
    var pageId   = $('#id').val();
    var rowCount = 0;

    // ===== DATEPICKER =====
    $('.datepicker').datepicker({ dateFormat: 'dd-mm-yy', changeMonth: true, changeYear: true });

    // ===== MODE =====
    if (pageId == 0) {
        $('.aksi').text('Simpan');
    } else {
        $('.aksi').text('Update');
        loadEdit();
    }

    // ===== BUILD ROW =====
    function buildRow(num, item) {
        item = item || {};
        return '<tr id="row-' + num + '">' +
            '<td class="text-center row-number">' + num + '</td>' +
            '<td>' +
                '<select class="form-control form-control-sm item-select" id="item_' + num + '" style="width:100%">' +
                    (item.inventory_id ? '<option value="' + item.inventory_id + '" selected>' + item.kode_produk + ' - ' + item.nama_produk + '</option>' : '') +
                '</select>' +
                '<input type="hidden" name="inventory_id[' + num + ']" id="inv_id_' + num + '" value="' + (item.inventory_id || '') + '">' +
            '</td>' +
            '<td class="text-center">' +
                '<span id="stok_label_' + num + '" class="font-weight-bold">' + (item.stok_efektif || '-') + '</span>' +
                '<small id="satuan_label_' + num + '" class="ml-1 text-muted">' + (item.satuan || '') + '</small>' +
                '<input type="hidden" id="stok_' + num + '" value="' + (item.stok_efektif || 0) + '">' +
                '<input type="hidden" id="satuan_' + num + '" value="' + (item.satuan || '') + '">' +
            '</td>' +
            '<td>' +
                '<input type="number" class="form-control form-control-sm qty-input" ' +
                    'name="qty_pinjam[' + num + ']" id="qty_' + num + '" ' +
                    'min="1" value="' + (item.qty_pinjam || 1) + '" placeholder="0">' +
            '</td>' +
            '<td>' +
                '<input type="text" class="form-control form-control-sm" ' +
                    'name="keterangan_detail[' + num + ']" placeholder="Opsional" value="' + (item.keterangan || '') + '">' +
            '</td>' +
            '<td class="text-center">' +
                '<button type="button" class="btn btn-danger btn-sm btn-remove" data-id="' + num + '">Del</button>' +
            '</td>' +
        '</tr>';
    }

    // ===== SELECT2 BARANG =====
    function initBarangSelect2(num) {
        var $sel = $('#item_' + num);

        if ($sel.hasClass('select2-hidden-accessible')) $sel.select2('destroy');

        $sel.select2({
            width: '100%',
            placeholder: '-- Pilih Barang --',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: "<?= site_url('mac_peminjaman/get_inventory') ?>",
                type: 'POST',
                dataType: 'JSON',
                delay: 300,
                cache: false,
                data: function(params) {
                    return {
                        search: params.term !== undefined ? params.term : '',
                        _ts:    new Date().getTime()
                    };
                },
                processResults: function(data) {
                    return {
                        results: (data || []).map(function(d) {
                            return {
                                id:           d.id,
                                text:         d.kode_produk + ' - ' + d.nama_produk,
                                nama_produk:  d.nama_produk,
                                stok_efektif: parseFloat(d.stok_efektif) || 0,
                                satuan:       d.satuan || ''
                            };
                        })
                    };
                }
            },
            matcher: function() { return true; }
        });

        $sel.off('select2:select select2:clear')
            .on('select2:select', function(e) {
                var d = e.params.data;
                $('#inv_id_' + num).val(d.id);
                $('#stok_' + num).val(d.stok_efektif);
                $('#satuan_' + num).val(d.satuan);
                $('#stok_label_' + num).text(d.stok_efektif);
                $('#satuan_label_' + num).text(d.satuan);
                $('#qty_' + num).val(1).attr('max', d.stok_efektif);
            })
            .on('select2:clear', function() {
                $('#inv_id_' + num).val('');
                $('#stok_' + num).val(0);
                $('#satuan_' + num).val('');
                $('#stok_label_' + num).text('-');
                $('#satuan_label_' + num).text('');
                $('#qty_' + num).removeAttr('max');
            });
    }

    // ===== TAMBAH BARIS =====
    $('#btn-add-item').on('click', function() {
        rowCount++;
        $('#item-container').append(buildRow(rowCount));
        initBarangSelect2(rowCount);
        reorder();
    });

    // ===== HAPUS BARIS =====
    $(document).on('click', '.btn-remove', function() {
        $('#row-' + $(this).data('id')).remove();
        reorder();
    });

    // ===== REORDER =====
    function reorder() {
        $('#item-container tr').each(function(i) {
            var n = i + 1;
            $(this).attr('id', 'row-' + n).find('.row-number').text(n);
            $(this).find('select.item-select').attr('id', 'item_' + n);
            $(this).find('input[id^="inv_id_"]').attr('id', 'inv_id_' + n).attr('name', 'inventory_id[' + n + ']');
            $(this).find('input[id^="stok_"]').attr('id', 'stok_' + n);
            $(this).find('span[id^="stok_label_"]').attr('id', 'stok_label_' + n);
            $(this).find('small[id^="satuan_label_"]').attr('id', 'satuan_label_' + n);
            $(this).find('input[id^="satuan_"]').attr('id', 'satuan_' + n);
            $(this).find('.qty-input').attr('id', 'qty_' + n).attr('name', 'qty_pinjam[' + n + ']');
            $(this).find('input[name^="keterangan_detail"]').attr('name', 'keterangan_detail[' + n + ']');
            $(this).find('.btn-remove').attr('data-id', n);
        });
        rowCount = $('#item-container tr').length;
    }

    // ===== VALIDASI QTY =====
    $(document).on('input', '.qty-input', function() {
        var num    = $(this).attr('id').replace('qty_', '');
        var val    = parseFloat($(this).val()) || 0;
        var stok   = parseFloat($('#stok_' + num).val()) || 0;
        var satuan = $('#satuan_' + num).val() || '';
        var invId  = $('#inv_id_' + num).val();
        var nama   = $('#item_' + num).find('option:selected').text();

        if (invId && stok > 0 && val > stok) {
            Swal.fire({
                icon: 'warning', title: 'Stok Tidak Cukup',
                text: nama + ' — stok efektif tersedia: ' + stok + ' ' + satuan,
                confirmButtonText: 'OK'
            });
            $(this).val(stok);
        }
    });

    // ===== LOAD EDIT =====
    function loadEdit() {
        $.ajax({
            url: "<?= site_url('mac_peminjaman/get_data/') ?>" + pageId,
            type: 'GET', dataType: 'JSON',
            success: function(res) {
                var m = res.master;
                $('#kode_pinjam').val(m.kode_pinjam);
                $('#tgl_pinjam').val(moment(m.tgl_pinjam).format('DD-MM-YYYY'));
                $('#tgl_kembali').val(moment(m.tgl_kembali).format('DD-MM-YYYY'));
                $('#keterangan').val(m.keterangan);

                // Pada edit, tgl_pinjam readonly (tidak bisa diubah)
                $('#tgl_pinjam').prop('readonly', true).css('cursor', 'not-allowed');

                $.each(res.detail, function(i, d) {
                    rowCount++;
                    $('#item-container').append(buildRow(rowCount, d));
                    initBarangSelect2(rowCount);

                    // Inject option terpilih ke select2
                    var opt = new Option(d.kode_produk + ' - ' + d.nama_produk, d.inventory_id, true, true);
                    $('#item_' + rowCount).append(opt).trigger('change.select2');
                    $('#inv_id_' + rowCount).val(d.inventory_id);
                    $('#stok_' + rowCount).val(d.stok_saat_ini);
                    $('#stok_label_' + rowCount).text(d.stok_saat_ini);
                    $('#satuan_label_' + rowCount).text(d.satuan);
                });
            }
        });
    }

    // ===== SUBMIT =====
    $('#form').on('submit', function(e) {
        e.preventDefault();

        // Validasi form dasar
        if (!$('#tgl_pinjam').val() || !$('#tgl_kembali').val()) {
            Swal.fire({ icon: 'warning', title: 'Lengkapi form', text: 'Tanggal pinjam dan kembali wajib diisi.' });
            return;
        }
        if ($('#item-container tr').length === 0) {
            Swal.fire({ icon: 'warning', title: 'Belum ada barang', text: 'Tambahkan minimal satu barang.' });
            return;
        }

        var url = pageId == 0
            ? "<?= site_url('mac_peminjaman/add') ?>"
            : "<?= site_url('mac_peminjaman/update') ?>";

        $('#loading').show();
        $('.aksi').prop('disabled', true);

        $.ajax({
            url: url, type: 'POST',
            data: new FormData(this),
            processData: false, contentType: false, dataType: 'JSON',
            success: function(res) {
                $('#loading').hide();
                $('.aksi').prop('disabled', false);
                if (res.status) {
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: res.message, timer: 1500, showConfirmButton: false })
                        .then(function() { location.href = "<?= base_url('mac_peminjaman') ?>"; });
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: res.error });
                }
            }
        });
    });
});
</script>
