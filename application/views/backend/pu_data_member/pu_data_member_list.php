<style>
    .rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: center;
        font-size: 2em;
    }

    .rating input {
        display: none;
    }

    .rating label {
        color: #ccc;
        cursor: pointer;
        transition: color 0.3s;
        position: relative;
        right: 107px;
        bottom: 5px;
    }

    .rating input:checked~label,
    .rating label:hover,
    .rating label:hover~label {
        color: #f5b301;
    }

    table tbody td:nth-child(2) {
        text-align: center;
    }
</style>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $titleview ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header d-flex justify-content-between align-items-center py-3">
                    <div style="width: 1px;"></div>
                    <a class="btn btn-primary btn-sm" href="<?= base_url('pu_data_agen') ?>">
                        <i class="fas fa-chevron-left"></i>&nbsp;Back
                    </a>
                </div>
                <div class="card-body p-4">
                    <!-- Added padding for spacing -->
                    <div class="table-responsive">
                        <!-- Table wrapper -->
                        <table id="member-table" class="table table-bordered table-striped display nowrap w-100 mb-4">
                            <!-- Added margin-bottom -->
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Action</th>
                                    <th>Status</th>
                                    <th>Nama Member</th>
                                    <th>No Telepon</th>
                                    <th>Kode Referral</th>
                                    <th>Saldo</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be populated by DataTables -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Action</th>
                                    <th>Status</th>
                                    <th>Nama Member</th>
                                    <th>No Telepon</th>
                                    <th>Kode Referral</th>
                                    <th>Saldo</th>
                                    <th>Created At</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Ubah Status Member -->
    <div class="modal fade" id="modalStatusMember" tabindex="-1" role="dialog" aria-labelledby="modalStatusMemberLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="formStatusMember">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalStatusMemberLabel">Ubah Status Member</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="statusMemberId" name="id">
                        <div class="form-group" id="form-group-kode-referral">
                            <label for="kode_referral">Kode Referral</label>
                            <input type="text" class="form-control" id="kode_referral" name="kode_referral" placeholder="Masukkan kode referral" required>
                        </div>
                        <div class="form-group">
                            <label for="is_active">Status Akun</label>
                            <select class="form-control" id="is_active" name="is_active">
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Saldo -->
    <div class="modal fade" id="modalEditSaldo" tabindex="-1" role="dialog" aria-labelledby="modalEditSaldoLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="formEditSaldo">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditSaldoLabel">Edit Saldo Member</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="editSaldoId" name="id">
                        <div class="form-group">
                            <label for="edit_saldo">Jumlah Saldo (Fee)</label>
                            <input type="text" class="form-control" id="edit_saldo" name="saldo" required oninput="formatRupiah(this)">
                            <script>
                                function formatRupiah(input) {
                                    let value = input.value.replace(/[^,\d]/g, '').toString();
                                    let split = value.split(',');
                                    let sisa = split[0].length % 3;
                                    let rupiah = split[0].substr(0, sisa);
                                    let ribuan = split[0].substr(sisa).match(/\d{3}/g);

                                    if (ribuan) {
                                        let separator = sisa ? '.' : '';
                                        rupiah += separator + ribuan.join('.');
                                    }

                                    rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
                                    input.value = rupiah;
                                }
                            </script>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Insentif Member -->
    <div class="modal fade" id="modalInsentifMember" tabindex="-1" role="dialog" aria-labelledby="modalInsentifMemberLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalInsentifMemberLabel">Insentif Member</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Tambahkan tombol di bawah judul modal -->
                    <button type="button" class="btn btn-primary btn-sm mb-3" id="btnTambahInsentif" style="font-size: 0.85rem; padding: 0.25rem 0.75rem;">
                        <i class="fa fa-plus"></i> Tambah Insentif
                    </button>
                    <table id="tableInsentifMember" class="table table-bordered table-striped" width="100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Status Pembayaran</th>
                                <th>Nama Member</th>
                                <th>Jenis Transaksi</th>
                                <th>Nominal</th>
                                <th>Deskripsi</th>
                                <th>Dibuat Pada</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data akan diisi via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Payment Status -->
    <div class="modal fade" id="modalPaymentStatus" tabindex="-1" role="dialog" aria-labelledby="modalPaymentStatusLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="formPaymentStatus">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalPaymentStatusLabel">Ubah Status Pembayaran</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="paymentStatusId" name="id">
                        <div class="form-group">
                            <label>Nama Pemilik Rekening</label>
                            <div id="payment_nama_pemilik_rek" class="form-control-plaintext" style="font-weight: bold;"></div>
                        </div>
                        <div class="form-group">
                            <label>No Rekening</label>
                            <div id="payment_no_rek" class="form-control-plaintext" style="font-weight: bold;"></div>
                        </div>
                        <div class="form-group">
                            <label>Nama Bank</label>
                            <div id="payment_nama_bank" class="form-control-plaintext" style="font-weight: bold;"></div>
                        </div>
                        <div class="form-group">
                            <label for="payment_status">Status Pembayaran</label>
                            <select class="form-control" id="payment_status" name="payment_status">
                                <option value="1">Sudah Dibayar</option>
                                <option value="2">Belum Dibayar</option>
                                <option value="0">Batalkan Pembayaran</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Tambah Insentif -->
    <div class="modal fade" id="modalTambahInsentif" tabindex="-1" role="dialog" aria-labelledby="modalTambahInsentifLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="formTambahInsentif">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahInsentifLabel">Tambah Insentif Member</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="hidden" name="id" id="insentif_id">
                            <label>No Telepon</label>
                            <input type="text" class="form-control" name="no_telp" required readonly>
                        </div>
                        <div class="form-group">
                            <label>Nama Member</label>
                            <input type="text" class="form-control" name="nama_member" required readonly>
                        </div>
                        <div class="form-group">
                            <label>Nominal</label>
                            <input type="text" class="form-control" name="nominal" id="nominal" required oninput="formatRupiah(this)">
                            <script>
                                function formatRupiah(input) {
                                    let value = input.value.replace(/[^,\d]/g, '').toString();
                                    let split = value.split(',');
                                    let sisa = split[0].length % 3;
                                    let rupiah = split[0].substr(0, sisa);
                                    let ribuan = split[0].substr(sisa).match(/\d{3}/g);

                                    if (ribuan) {
                                        let separator = sisa ? '.' : '';
                                        rupiah += separator + ribuan.join('.');
                                    }

                                    rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
                                    input.value = rupiah;
                                }
                            </script>
                        </div>
                        <div class="form-group">
                            <label>Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>

<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>

<script type="text/javascript">
    var table;
    $(document).ready(function() {
        table = $('#member-table').DataTable({
            "responsive": true,
            "scrollX": true,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?php echo site_url('pu_data_member/get_list') ?>",
                "type": "POST",
            },
            "language": {
                "infoFiltered": ""
            },
            "columnDefs": [{
                    "targets": [1, 2], // Adjusted indices to match the number of columns
                    "className": 'dt-head-nowrap'
                },
                {
                    "targets": [],
                    "className": 'dt-body-nowrap'
                },
                {
                    "targets": [0, 1], // Indices for non-orderable columns
                    "orderable": false,
                }
            ],
        });
    });

    $("#modalform").submit(function(e) {
        e.preventDefault();
        var url;
        var $form = $(this);
        if (!$form.valid()) return false;
        if (method == 'add') {
            url = "<?php echo site_url('pu_data_member/add') ?>";
        } else {
            url = "<?php echo site_url('pu_data_member/update') ?>";
        }

        $.ajax({
            url: url,
            type: "post",
            data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false,
            async: false,
            success: function(data) {
                $('#modal-default').modal('hide');
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Your data has been saved',
                    showConfirmButton: false,
                    timer: 1500
                });
                reload_table();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
            }
        });
    });

    function reload_table() {
        table.ajax.reload(null, false);
    };

    function add_data() {
        method = 'add';
        $('#modalform')[0].reset();
        var validator = $("#modalform").validate();
        $('#imgKTPEdit').hide();
        validator.resetForm();
        $('.card-title').text('Tambah Data Member');
        $('.aksi').text('Save');
    };

    // Mengambil URL saat ini
    const params = new URLSearchParams(window.location.search);

    // Mengambil parameter tertentu
    const action = params.get('action'); // "John"

    if (action == 'add') {
        $('#add_btn').click();
    }

    function edit_data(id) {
        method = 'update';
        $('#modalform')[0].reset();
        var validator = $("#modalform").validate();
        validator.resetForm();
        $('.form-control').removeClass('error');
        $('#modal-default').modal('show');
        $('.card-title').text('Edit Data Member');
        $('.aksi').text('Update');

        // Sembunyikan preview gambar KTP dulu
        $('#imgKTPEdit').hide().attr('src', '');

        $.ajax({
            url: "<?php echo site_url('pu_data_member/get_id/') ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('[name="id"]').val(data.id);
                $('[name="nama_member"]').val(data.nama);
                $('[name="no_telp"]').val(data.no_hp);
                $('[name="kode_referral"]').val(data.kode_referral);
                $('[name="alamat"]').val(data.alamat);
                $('[name="kota"]').val(data.kota);
                $('[name="kelurahan"]').val(data.kelurahan);
                $('[name="provinsi"]').val(data.provinsi);

                // Preview gambar KTP jika ada
                if (data.ktp) {
                    $('#imgKTPEdit')
                        .attr('src', '<?php echo base_url('assets/backend/document/pu_data_member/') ?>' + data.ktp)
                        .show();
                } else {
                    $('#imgKTPEdit').hide().attr('src', '');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    };

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
                    url: "<?php echo site_url('pu_data_member/delete') ?>/" + id,
                    type: "POST",
                    dataType: "JSON",
                    success: function(data) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Your data has been deleted',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        reload_table();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error deleting data');
                    }
                });
            }
        })
    };  

    $(document).on('click', '.lihat-ktp', function(e) {
        e.preventDefault();
        var imgUrl = $(this).data('img');
        $('#imgKTP').attr('src', imgUrl);
        $('#downloadKTP').attr('href', imgUrl);
        // Optional: set nama file download
        $('#downloadKTP').attr('download', 'ktp-' + Date.now() + '.jpg');
        $('#modalKTP').modal('show');
    });

    // Tambahkan script untuk mengubah status member
    $("#formStatusMember").submit(function(e) {
        e.preventDefault();
        var url = "<?php echo site_url('pu_data_member/update_status_member') ?>";
        $.ajax({
            url: url,
            type: "post",
            data: $(this).serialize(),
            success: function(data) {
                $('#modalStatusMember').modal('hide');
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Status member telah diperbarui',
                    showConfirmButton: false,
                    timer: 1500
                });
                reload_table();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error updating status');
            }
        });
    });

    // Fungsi untuk membuka modal ubah status member
    function ubah_status_member(id, kode_referral, is_active) {
        $('#modalStatusMember').modal('show');
        $('#statusMemberId').val(id);
        $('#kode_referral').val(kode_referral);
        $('#is_active').val(is_active);
    }

    // Trigger modal saat badge diklik
    $(document).on('click', '.status-modal-trigger', function() {
        var id = $(this).data('id');
        var status = $(this).data('status');
        var referral = $(this).data('referral');
        $('#statusMemberId').val(id);
        $('#is_active').val(status);
        $('#kode_referral').val(referral);

        $('#modalStatusMember').modal('show');
    });

    // Submit perubahan status & kode referral
    $('#formStatusMember').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '<?php echo base_url("pu_data_member/update_status_member"); ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res) {
                if (res.status) {
                    $('#modalStatusMember').modal('hide');
                    // Refresh datatable atau reload halaman
                    location.reload();
                } else {
                    alert(res.message || 'Gagal update status');
                }
            },
            error: function() {
                alert('Terjadi kesalahan saat mengupdate status');
            }
        });
    });

    $('#kode_referral').on('input', function() {
        // Ambil value, ubah ke kapital, potong maksimal 6 karakter
        var val = $(this).val().toUpperCase().slice(0, 6);
        $(this).val(val);
    });

    // Trigger modal edit saldo
    $(document).on('click', '.edit-saldo-trigger', function() {
        var id = $(this).data('id');
        var saldo = $(this).data('saldo');
        $('#editSaldoId').val(id);
        $('#edit_saldo').val(saldo);
        $('#modalEditSaldo').modal('show');
    });

    // Submit perubahan saldo
    $('#formEditSaldo').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '<?php echo base_url("pu_data_member/update_saldo_member"); ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res) {
                if (res.status) {
                    $('#modalEditSaldo').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Saldo berhasil diupdate',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    reload_table();
                } else {
                    alert(res.message || 'Gagal update saldo');
                }
            },
            error: function() {
                alert('Terjadi kesalahan saat mengupdate saldo');
            }
        });
    });

    var currentNoTelp = '';
    var currentNamaMember = '';

    function showInsentifModal(no_telp, nama_member) {
        currentNoTelp = no_telp;
        currentNamaMember = nama_member;
        $('#modalInsentifMember').modal('show');

        // Destroy datatable jika sudah ada
        if ($.fn.DataTable.isDataTable('#tableInsentifMember')) {
            $('#tableInsentifMember').DataTable().destroy();
        }

        // Inisialisasi datatable insentif (server-side)
        $('#tableInsentifMember').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?php echo site_url('pu_data_member/get_insentif_member'); ?>",
                "type": "POST",
                "data": {
                    no_telp: no_telp
                }
            },
            "columnDefs": [{
                    "targets": [0, 1, 2, 3, 6], // Adjusted indices to match the number of columns
                    "className": 'dt-head-nowrap'
                },
                {
                    "targets": [2, 5], // Adjusted indices to match the number of columns
                    "className": 'dt-body-nowrap'
                },
                {
                    "targets": [0, 1], // Indices for non-orderable columns
                    "orderable": false,
                }
            ],
            "columns": [{
                    "data": "no"
                },
                {
                    "data": "payment_status"
                },
                {
                    "data": "nama_member"
                },
                {
                    "data": "jenis_transaksi"
                },
                {
                    "data": "nominal"
                },
                {
                    "data": "description"
                },
                {
                    "data": "dibuat_pada"
                }
            ]
        });
    }

    // Trigger modal payment status
    $(document).on('click', '.payment-status-trigger', function() {
        var id = $(this).data('id');
        var status = $(this).data('status');
        var tr = $(this).closest('tr');
        var table = $('#tableInsentifMember').DataTable();
        var rowData = table.row(tr).data();

        $('#paymentStatusId').val(id);
        $('#payment_status').val(status);

        // Tampilkan info rekening sebagai list/text
        $('#payment_nama_pemilik_rek').text(rowData.nama_pemilik_rek || '-');
        $('#payment_no_rek').text(rowData.no_rek || '-');
        $('#payment_nama_bank').text(rowData.nama_bank || '-');

        $('#modalPaymentStatus').modal('show');
    });

    // Submit perubahan status payment
    $('#formPaymentStatus').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '<?php echo base_url("pu_data_member/update_payment_status_member"); ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res) {
                if (res.status) {
                    $('#modalPaymentStatus').modal('hide');
                    $('#tableInsentifMember').DataTable().ajax.reload(null, false);
                    $('#member-table').DataTable().ajax.reload(null, false);
                    Swal.fire({
                        icon: 'success',
                        title: 'Status pembayaran berhasil diupdate',
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    alert(res.message || 'Gagal update status pembayaran');
                }
            },
            error: function() {
                alert('Terjadi kesalahan saat mengupdate status pembayaran');
            }
        });
    });

    // Buka modal tambah insentif saat tombol diklik
    $(document).on('click', '#btnTambahInsentif', function() {
        $('#formTambahInsentif')[0].reset();
        $('[name="id"]').val('');
        $('[name="no_telp"]').val(currentNoTelp);
        $('[name="nama_member"]').val(currentNamaMember);
        $('#modalTambahInsentif').modal('show');
    });

    // Untuk edit insentif (id dan field lain diisi)
    $(document).on('click', '.btnTambahInsentif', function() {
        var id = $(this).data('id');
        var no_telp = $(this).data('no_telp');
        var nama_member = $(this).data('nama_member');
        var nominal = $(this).data('nominal');
        var deskripsi = $(this).data('deskripsi');

        $('#formTambahInsentif')[0].reset();
        $('[name="id"]').val(id);
        $('[name="no_telp"]').val(no_telp);
        $('[name="nama_member"]').val(nama_member);
        $('[name="nominal"]').val(nominal);
        $('[name="deskripsi"]').val(deskripsi);
        $('#modalTambahInsentif').modal('show');
    });


    // Submit form tambah insentif member
    $('#formTambahInsentif').on('submit', function(e) {
        e.preventDefault();
        var id = $('[name="id"]').val();
        var url = id ?
            '<?php echo base_url("pu_data_member/update_insentif_member"); ?>' :
            '<?php echo base_url("pu_data_member/tambah_insentif_member"); ?>';

        $.ajax({
            url: url,
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res) {
                if (res.status) {
                    $('#modalTambahInsentif').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: res.message || 'Berhasil',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $('#tableInsentifMember').DataTable().ajax.reload(null, false);
                    reload_table();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: res.message || 'Gagal menyimpan insentif'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan server'
                });
            }
        });
    });

    $('#modalTambahInsentif').on('hidden.bs.modal', function() {
        // Jika modal insentif member masih terbuka, pastikan body tetap modal-open
        if ($('#modalInsentifMember').hasClass('show')) {
            $('body').addClass('modal-open');
        }
    });
</script>