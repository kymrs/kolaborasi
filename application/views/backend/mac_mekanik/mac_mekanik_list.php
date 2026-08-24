<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $titleview ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <button type="button" class="btn btn-primary btn-sm" onclick="open_modal(0)">
                        <i class="fa fa-plus"></i>&nbsp;Add Data
                    </button>
                </div>
                <div class="card-body">
                    <table id="table" class="table table-bordered table-striped" width="100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Action</th>
                                <th>Nama Mekanik</th>
                                <th>NPK</th>
                                <th>Cabang</th>
                                <th>No Telepon</th>
                                <th>Alamat</th>
                                <th>Dibuat</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Action</th>
                                <th>Nama Mekanik</th>
                                <th>NPK</th>
                                <th>Cabang</th>
                                <th>No Telepon</th>
                                <th>Alamat</th>
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
<div class="modal fade" id="mekanikModal" tabindex="-1" aria-hidden="true">
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
                        <label>Nama Mekanik <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama" id="nama" placeholder="Nama Mekanik">
                    </div>
                    <div class="form-group">
                        <label>NPK <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="npk" id="npk" placeholder="NPK">
                    </div>
                    <div class="form-group">
                        <label>Cabang <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="cabang" id="cabang" placeholder="Cabang">
                    </div>
                    <div class="form-group">
                        <label>No Telepon</label>
                        <input type="text" class="form-control" name="no_telp" id="no_telp" placeholder="No Telepon">
                    </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea class="form-control" name="alamat" id="alamat" placeholder="Alamat" rows="3"></textarea>
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

<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>

<script>
$(document).ready(function() {
    var table = $('#table').DataTable({
        responsive: false,
        scrollX: true,
        processing: true,
        serverSide: true,
        order: [],
        ajax: {
            url: "<?= site_url('mac_mekanik/get_list') ?>",
            type: "POST"
        },
        columnDefs: [
            {
                targets: [3],
                className: "dt-head-nowrap"
            },
            {
                targets: [1, 2, 6, 7],
                className: "dt-body-nowrap"
            },
            {
                targets: [1],
                orderable: false
            }
        ]
    });

    // ===== BUKA MODAL =====
    window.open_modal = function(id) {
        $('#form')[0].reset();
        $('#id').val(id);
        $('.is-invalid').removeClass('is-invalid');

        if (id == 0) {
            $('#modalTitle').html('Add Data');
            $('#btn-save-text').text('Save');
            $('#mekanikModal').modal('show');
        } else {
            $('#modalTitle').html('Edit Mekanik');
            $('#btn-save-text').text('Update');

            $.ajax({
                url: "<?= site_url('mac_mekanik/get_data/') ?>" + id,
                type: 'GET', dataType: 'JSON',
                success: function(data) {
                    $('#nama').val(data.nama);
                    $('#npk').val(data.npk);
                    $('#cabang').val(data.cabang);
                    $('#no_telp').val(data.no_telp);
                    $('#alamat').val(data.alamat);
                    $('#mekanikModal').modal('show');
                },
                error: function() {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal mengambil data.' });
                }
            });
        }
    };

    // ===== SIMPAN (ADD/UPDATE) =====
    $('#btn-save').on('click', function() {
        var id = $('#id').val();

        // Validasi sederhana di client
        if (!$('#nama').val().trim()) {
            $('#nama').addClass('is-invalid');
            return;
        }
        if (!$('#npk').val()) {
            $('#npk').addClass('is-invalid');
            return;
        }
        if (!$('#cabang').val()) {
            $('#cabang').addClass('is-invalid');
            return;
        }
        if (!$('#no_telp').val()) {
            $('#no_telp').addClass('is-invalid');
            return;
        }
        if (!$('#alamat').val()) {
            $('#alamat').addClass('is-invalid');
            return;
        }

        var url = (id == 0)
            ? "<?= site_url('mac_mekanik/add') ?>"
            : "<?= site_url('mac_mekanik/update') ?>";

        var formData = new FormData($('#form')[0]);

        $('#btn-save').prop('disabled', true);

        $.ajax({
            url: url, type: 'POST', data: formData,
            processData: false, contentType: false, dataType: 'JSON',
            success: function(res) {
                $('#btn-save').prop('disabled', false);
                if (res.status) {
                    $('#mekanikModal').modal('hide');
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
            title: 'Hapus data mekanik ini?',
            text: 'Data akan dinonaktifkan, bukan dihapus permanen.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then(function(result) {
            if (result.isConfirmed) {
                $.post("<?= site_url('mac_mekanik/delete/') ?>" + id, {}, function(res) {
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
