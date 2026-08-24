<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            Master Jasa
            <span id="title"><?= $title_cabang ?></span>
        </h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex align-items-center justify-content-between">
                    <button type="button" class="btn btn-primary btn-sm" onclick="open_modal(0)">
                        <i class="fa fa-plus"></i>&nbsp;Add Data
                    </button>
                    <div class="d-flex align-items-center mr-2">
                        <?php if ($is_nasional): ?>
                        <div class="d-flex align-items-center mr-2">
                            <label class="mr-2 mb-0 text-muted small">Cabang:</label>
                            <select id="filter-cabang-jasa" class="form-control form-control-sm"
                                    style="background:#242d4a; color:#fff; border:none; min-width:140px;">
                                <option value="0">Nasional</option>
                                <?php foreach ($list_cabang as $c): ?>
                                <option value="<?= $c->id ?>">
                                    <?= ucwords(strtolower(str_replace('MAC ', '', $c->nama_cabang))) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php endif; ?>
                        <div class="d-flex align-items-center mr-2 ml-3">
                            <label class="mr-2 mb-0 text-muted small">Jenis:</label>
                            <select class="form-control form-control-sm" id="filter_jenis_jasa" style="background:#242d4a; color:#fff; border:none; min-width:130px;">
                                <option value="">Semua</option>
                                <option value="internal">Internal</option>
                                <option value="external">External</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="table" class="table table-bordered table-striped" width="100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Action</th>
                                <th>Nama Jasa</th>
                                <th>Jenis</th>
                                <th>Satuan</th>
                                <th>Paket</th>
                                <th>Harga Beli</th>
                                <th>Harga Jual</th>
                                <th>Dibuat</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Action</th>
                                <th>Nama Jasa</th>
                                <th>Jenis</th>
                                <th>Satuan</th>
                                <th>Paket</th>
                                <th>Harga Beli</th>
                                <th>Harga Jual</th>
                                <th>Dibuat</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL FORM -->
<div class="modal fade" id="jasaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalTitle">Add Data</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="scale: 0.6;">
                    <span aria-hidden="true" style="position: relative; top: -10px; left: 20px">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form">
                    <input type="hidden" name="id" id="id" value="0">

                    <div class="form-group">
                        <label>Nama Jasa <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama" id="nama" placeholder="Nama Jasa">
                    </div>

                    <div class="form-group">
                        <label>Satuan</label>
                        <select class="form-control" name="satuan" id="satuan">
                            <option value="">-- Pilih Satuan --</option>
                            <option value="pcs">pcs</option>
                            <option value="unit">unit</option>
                            <option value="set">set</option>
                            <option value="Roda Set">Roda Set</option>
                            <option value="Titik">Titik</option>
                            <option value="Roda">Roda</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Paket <span class="text-danger">*</span></label>
                        <select class="form-control" name="paket" id="paket">
                            <option value="">-- Pilih Paket --</option>
                            <option value="Basic">Basic</option>
                            <option value="Medium">Medium</option>
                            <option value="Luxury">Luxury</option>
                        </select>
                    </div>

                    <!-- Pilih cabang — hanya tampil untuk Nasional -->
                    <?php if ($is_nasional): ?>
                    <div class="form-group">
                        <label>Cabang <span class="text-danger">*</span></label>
                             <select class="form-control" name="cabang_id" id="cabang_id_jasa">
                                <option value="">-- Pilih Cabang --</option>
                                <?php foreach ($list_cabang as $c): ?>
                                <option value="<?= $c->id ?>">
                                    <?= ucwords(strtolower(str_replace('MAC ', '', $c->nama_cabang))) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                    </div>
                    <?php endif; ?>

                    <!-- Jenis internal/external -->
                    <div class="form-group row align-items-center">
                        <label class="col-4 col-form-label">Jenis <span class="text-danger">*</span></label>
                        <div class="col-8">
                            <select class="form-control" name="jenis" id="jenis_jasa">
                                <option value="internal">Internal</option>
                                <option value="external">External</option>
                            </select>
                        </div>
                    </div>

                    <!-- Harga beli — hanya tampil jika external -->
                    <div class="form-group row align-items-center" id="row-harga-beli-jasa" style="display:none;">
                        <label class="col-4 col-form-label">Harga Beli / Modal</label>
                        <div class="col-8">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control" id="harga_beli_jasa"
                                    name="harga_beli" placeholder="0">
                            </div>
                        </div>
                    </div>

                    <!-- Harga jual (nama field disesuaikan) -->
                    <div class="form-group row align-items-center">
                        <label class="col-4 col-form-label">Harga Jual <span class="text-danger">*</span></label>
                        <div class="col-8">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control" id="harga_jual_jasa"
                                    name="harga_jual" placeholder="0">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btn-save">
                    <i class="fa fa-save"></i> <span id="btn-save-text">Save</span>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalHargaJasaCabang" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title">
                    <i class="fa fa-tags"></i>
                    Harga per Cabang — <span id="harga-jasa-cabang-nama"></span>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="alert alert-info m-3 mb-2 py-2">
                    <i class="fa fa-info-circle"></i>
                    Kosongkan field untuk menggunakan <strong>harga default</strong>.
                </div>
                <form id="form-harga-jasa-cabang">
                    <input type="hidden" name="jasa_id" id="harga_jasa_jasa_id">
                    <table class="table table-bordered table-sm mb-0">
                        <thead style="background:#242d4a; color:white;">
                            <tr>
                                <th width="5%">No</th>
                                <th>Cabang</th>
                                <th width="25%">Harga Default</th>
                                <th width="28%">Harga Override</th>
                                <th width="12%" class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-harga-jasa-cabang">
                            <tr><td colspan="5" class="text-center py-3">
                                <i class="fa fa-spinner fa-spin"></i> Loading...
                            </td></tr>
                        </tbody>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary btn-sm" id="btn-save-harga-jasa-cabang">
                    <i class="fa fa-save"></i> Simpan Harga
                </button>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>

<script>
$(document).ready(function() {
    $(document).on('input', '#harga_beli_jasa_master', function() {
        var raw = $(this).val().replace(/[^0-9]/g, '');
        $(this).val(raw ? parseInt(raw).toLocaleString('id-ID') : '');
    });

    function toggleHargaBeliJasa() {
        var jenis = $('input[name="jenis"]:checked').val();

        if (jenis === 'external') {
            $('#harga-beli-wrapper').slideDown(150);
        } else {
            $('#harga-beli-wrapper').slideUp(150);

            // reset nilainya
            $('#harga_beli_jasa_master').val('0');
        }
    }

    $(document).on('change', 'input[name="jenis"]', function () {
        toggleHargaBeliJasa();
    });

    // saat modal pertama kali dibuka
    $('#jasaModal').on('shown.bs.modal', function () {
        toggleHargaBeliJasa();
    });

    var table = $('#table').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        order: [],
        ajax: {
            url: "<?= site_url('mac_jasa/get_list') ?>",
            type: 'POST',
            data: function(d) {
                d.filter_cabang = filterCabangJasa;
                d.filter_jenis  = $('#filter_jenis_jasa').val();
            }
        },
        columnDefs: [
            { targets: [0,1], orderable: false }
        ]
    });

    $('#filter-cabang-jasa').on('change', function () {

        var namaCabang = $('#filter-cabang-jasa option:selected').text();

        if ($(this).val() == '') {
            namaCabang = 'Nasional';
        }

        $('#title').text(namaCabang);

        table.ajax.reload();
    });

    $(document).on('change', '#jenis_jasa', function() {
        if ($(this).val() === 'external') {
            $('#row-harga-beli-jasa').show();
        } else {
            $('#row-harga-beli-jasa').hide();
            $('#harga_beli_jasa').val('');
        }
    });

    $('#filter_jenis_jasa').on('change', function () {
        table.ajax.reload();
    });

    // ===== FORMAT HARGA =====
    $(document).on('input', '#harga_beli_jasa, #harga_jual_jasa', function() {
        var raw = $(this).val().replace(/[^0-9]/g, '');
        $(this).val(raw ? parseInt(raw).toLocaleString('id-ID') : '');
    });
    
    // ===== BUKA MODAL =====
    window.open_modal = function(id) {
        $('#form')[0].reset();
        $('#id').val(id);
        $('.is-invalid').removeClass('is-invalid');

        if (id == 0) {
            $('#modalTitle').html('Add Data');
            $('#btn-save-text').text('Save');
            $('#jenis_internal').prop('checked', true);
            toggleHargaBeliJasa();
            $('#jasaModal').modal('show');
        } else {
            $('#modalTitle').html('Edit Jasa');
            $('#btn-save-text').text('Update');

            $.ajax({
                url: "<?= site_url('mac_jasa/get_data/') ?>" + id,
                type: 'GET', dataType: 'JSON',
                success: function(data) {
                    $('#nama').val(data.nama);
                    $('#satuan').val(data.satuan);
                    $('#paket').val(data.paket);
                    $('#harga').val(
                        parseInt(data.harga || 0).toLocaleString('id-ID')
                    );
                    $('#harga_beli_jasa_master').val(
                        parseInt(data.harga_beli || 0).toLocaleString('id-ID')
                    );
                    // Tentukan jenis jasa
                    if (parseFloat(data.harga_beli || 0) > 0) {
                        $('#jenis_external').prop('checked', true);
                    } else {
                        $('#jenis_internal').prop('checked', true);
                    }
                    // Tampilkan / sembunyikan input harga beli
                    toggleHargaBeliJasa();
                    $('#jasaModal').modal('show');
                },
                error: function() {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal mengambil data.' });
                }
            });
        }
    };

    $('#modalForm').on('hidden.bs.modal', function() {
        $('#jenis_jasa').val('internal').trigger('change');
        $('#harga_beli_jasa').val('');
        $('#harga_jual_jasa').val('');
        $('#cabang_id_jasa').val('');
    });

    // Filter cabang
    var filterCabangJasa = 0;
    $('#filter-cabang-jasa').on('change', function() {
        filterCabangJasa = $(this).val();
        table.ajax.reload();
    });

    // Buka modal
    window.modal_harga_jasa_cabang = function(jasa_id, nama) {
        $('#harga_jasa_jasa_id').val(jasa_id);
        $('#harga-jasa-cabang-nama').text(nama);
        $('#tbody-harga-jasa-cabang').html(
            '<tr><td colspan="5" class="text-center py-3"><i class="fa fa-spinner fa-spin"></i></td></tr>'
        );
        $('#modalHargaJasaCabang').modal('show');

        $.ajax({
            url: "<?= site_url('mac_jasa/get_harga_jasa_cabang') ?>",
            type: 'POST', dataType: 'JSON',
            data: { jasa_id: jasa_id },
            success: function(res) {
                if (!res.status) return;
                var rows = '';
                $.each(res.rows, function(i, d) {
                    var override = d.harga_override
                        ? parseInt(d.harga_override).toLocaleString('id-ID') : '';
                    var def    = 'Rp ' + parseInt(d.harga_default).toLocaleString('id-ID');
                    var badge  = d.harga_override
                        ? '<span class="badge badge-primary">Override</span>'
                        : '<span class="badge badge-secondary">Default</span>';
                    rows += '<tr>' +
                        '<td class="text-center">' + (i+1) + '</td>' +
                        '<td>' + d.nama_cabang +
                            '<input type="hidden" name="cabang_id[]" value="' + d.cabang_id + '">' +
                        '</td>' +
                        '<td class="text-muted">' + def + '</td>' +
                        '<td><div class="input-group input-group-sm">' +
                            '<div class="input-group-prepend"><span class="input-group-text">Rp</span></div>' +
                            '<input type="text" class="form-control input-rupiah-jasa" name="harga_jasa[]"' +
                            ' value="' + override + '" placeholder="Kosong = pakai default">' +
                        '</div></td>' +
                        '<td class="text-center">' + badge + '</td>' +
                    '</tr>';
                });
                $('#tbody-harga-jasa-cabang').html(rows);
            }
        });
    };

    // Format rupiah
    $(document).on('input', '.input-rupiah-jasa', function() {
        var raw = $(this).val().replace(/[^0-9]/g, '');
        $(this).val(raw ? parseInt(raw).toLocaleString('id-ID') : '');
    });

    // Simpan
    $('#btn-save-harga-jasa-cabang').on('click', function() {
        $('#btn-save-harga-jasa-cabang').prop('disabled', true);
        $.ajax({
            url: "<?= site_url('mac_jasa/save_harga_jasa_cabang') ?>",
            type: 'POST',
            data: $('#form-harga-jasa-cabang').serialize(),
            dataType: 'JSON',
            success: function(res) {
                $('#btn-save-harga-jasa-cabang').prop('disabled', false);
                if (res.status) {
                    $('#modalHargaJasaCabang').modal('hide');
                    table.ajax.reload(null, false);
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: res.message,
                        timer: 1500, showConfirmButton: false });
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: res.error });
                }
            },
            error: function() {
                $('#btn-save-harga-jasa-cabang').prop('disabled', false);
                Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan server.' });
            }
        });
    });

    // ===== SIMPAN (ADD/UPDATE) =====
    $('#btn-save').on('click', function() {
        var id = $('#id').val();

        // Validasi sederhana di client
        if (!$('#nama').val().trim()) {
            $('#nama').addClass('is-invalid');
            return;
        }
        if (!$('#paket').val()) {
            $('#paket').addClass('is-invalid');
            return;
        }
        // if (!$('#harga').val()) {
        //     $('#harga').addClass('is-invalid');
        //     return;
        // }

        var url = (id == 0)
            ? "<?= site_url('mac_jasa/add') ?>"
            : "<?= site_url('mac_jasa/update') ?>";

        var formData = new FormData($('#form')[0]);
        formData.set('harga_beli', $('#harga_beli_jasa').val().replace(/\./g, ''));
        formData.set('harga_jual', $('#harga_jual_jasa').val().replace(/\./g, ''));

        // $('#btn-save').prop('disabled', true);

        $.ajax({
            url: url, type: 'POST', data: formData,
            processData: false, contentType: false, dataType: 'JSON',
            success: function(res) {
                $('#btn-save').prop('disabled', false);
                if (res.status) {
                    $('#jasaModal').modal('hide');
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: res.message, timer: 1500, showConfirmButton: false });
                    table.ajax.reload(null, false);
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: res.error });
                }
            },
            error: function() {
                $('#btn-save').prop('disabled', false);
                Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan server.' });
            }
        });
    });

    // ===== DELETE =====
    window.delete_data = function(id) {
        Swal.fire({
            title: 'Hapus data jasa ini?',
            text: 'Data akan dinonaktifkan, bukan dihapus permanen.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then(function(result) {
            if (result.isConfirmed) {
                $.post("<?= site_url('mac_jasa/delete/') ?>" + id, {}, function(res) {
                    if (res.status) {
                        Swal.fire({ icon: 'success', title: 'Berhasil dihapus', timer: 1500, showConfirmButton: false });
                        table.ajax.reload(null, false);
                    } else {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: res.error });
                    }
                }, 'json');
            }
        });
    };
});
</script>
