<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $titleview ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <button type="button" class="btn btn-primary btn-sm" onclick="open_modal(0)">
                        <i class="fa fa-plus"></i> Add Data
                    </button>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped" id="table-cabang" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Aksi</th>
                                <th>Kode</th>
                                <th>Nama Cabang</th>
                                <th>No. Telp</th>
                                <th>Alamat</th>
                                <th>Status</th>
                                <th>Dibuat</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Aksi</th>
                                <th>Kode</th>
                                <th>Nama Cabang</th>
                                <th>No. Telp</th>
                                <th>Alamat</th>
                                <th>Status</th>
                                <th>Dibuat</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ADD / EDIT -->
<div class="modal fade" id="modalCabang" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalCabangTitle">Tambah Cabang</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="form-cabang">
                    <input type="hidden" id="cabang_id" name="id" value="0">

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Kode <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="cabang_kode" name="kode" autocomplete="off" placeholder="Kode Cabang">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Nama Cabang <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="cabang_nama" name="nama_cabang"
                                   placeholder="Nama Cabang" autocomplete="off">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">No. Telp</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="cabang_telp" name="no_telp"
                                   placeholder="No. Telp" autocomplete="off">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Alamat</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" id="cabang_alamat" name="alamat"
                                      rows="3" placeholder="Alamat cabang"></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Status</label>
                        <div class="col-sm-8">
                            <select class="form-control" id="cabang_status" name="status">
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary btn-sm" id="btn-save-cabang">
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

    // ===== DATATABLES =====
    var table = $('#table-cabang').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        order: [],
        ajax: {
            url: "<?= site_url('mac_cabang/get_list') ?>",
            type: 'POST'
        },
        columns: [
            { data: 0 }, { data: 1 }, { data: 2 },
            { data: 3 }, { data: 4 }, { data: 5 }, { data: 6 }, { data: 7 }
        ],
        columnDefs: [
            { targets: [0, 1], orderable: false },
        ]
    });

    // ===== BUKA MODAL =====
    window.open_modal = function(id) {
        // Reset form
        $('#form-cabang')[0].reset();
        $('#cabang_id').val(0);

        if (id == 0) {
            $('#modalCabangTitle').text('Tambah Cabang');
            $('#modalCabang').modal('show');
        } else {
            $('#modalCabangTitle').text('Edit Cabang');
            $.ajax({
                url: "<?= site_url('mac_cabang/get_data') ?>/" + id,
                type: 'GET',
                dataType: 'JSON',
                success: function(d) {
                    if (!d || !d.id) return;
                    $('#cabang_id').val(d.id);
                    $('#cabang_kode').val(d.kode);
                    $('#cabang_nama').val(d.nama_cabang);
                    $('#cabang_telp').val(d.no_telp);
                    $('#cabang_alamat').val(d.alamat);
                    $('#cabang_status').val(d.status);
                    $('#modalCabang').modal('show');
                }
            });
        }
    };

    // ===== SIMPAN =====
    $('#btn-save-cabang').on('click', function() {
        var id   = $('#cabang_id').val();
        var kode = $('#cabang_kode').val().trim();
        var nama = $('#cabang_nama').val().trim();

        if (!kode) { Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Kode cabang wajib diisi.' }); return; }
        if (!nama)  { Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Nama cabang wajib diisi.' }); return; }

        var url = (id == 0)
            ? "<?= site_url('mac_cabang/add') ?>"
            : "<?= site_url('mac_cabang/update') ?>";

        $('#btn-save-cabang').prop('disabled', true);

        $.ajax({
            url: url,
            type: 'POST',
            data: $('#form-cabang').serialize(),
            dataType: 'JSON',
            success: function(res) {
                $('#btn-save-cabang').prop('disabled', false);
                if (res.status) {
                    $('#modalCabang').modal('hide');
                    table.ajax.reload(null, false);
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: res.message,
                        timer: 1500, showConfirmButton: false });
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: res.error });
                }
            },
            error: function() {
                $('#btn-save-cabang').prop('disabled', false);
                Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan server.' });
            }
        });
    });

    // ===== DELETE =====
    window.delete_data = function(id) {
        Swal.fire({
            icon: 'warning',
            title: 'Hapus cabang ini?',
            text: 'Cabang yang sudah digunakan di data stok tidak dapat dihapus.',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#e74a3b'
        }).then(function(result) {
            if (result.isConfirmed) {
                $.post("<?= site_url('mac_cabang/delete') ?>/" + id, {}, function(res) {
                    if (res.status) {
                        table.ajax.reload(null, false);
                        Swal.fire({ icon: 'success', title: 'Berhasil dihapus!',
                            timer: 1500, showConfirmButton: false });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: res.error });
                    }
                }, 'json');
            }
        });
    };

    // Auto uppercase kode
    $('#cabang_kode').on('input', function() {
        $(this).val($(this).val().toUpperCase());
    });
});
</script>