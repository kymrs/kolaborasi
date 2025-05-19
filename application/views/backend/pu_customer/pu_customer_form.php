<style>
    /* Styling box input Select2 */
    .select2-container .select2-selection--single {
        height: 38px;
        /* Sama seperti form-control Bootstrap */
        padding: 6px 12px;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        transition: 300ms;
    }

    /* Saat select2 dalam keadaan fokus */
    .select2-container--default .select2-selection--single:focus,
    .select2-container--default .select2-selection--single:hover {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    /* Styling dropdown Select2 */
    .select2-container--default .select2-results__option {
        padding: 10px;
        font-size: 1rem;
    }

    /* Hover pada dropdown */
    .select2-container--default .select2-results__option--highlighted {
        background-color: #007bff;
        color: white;
    }

    /* Styling placeholder */
    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #6c757d;
    }
</style>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title_view ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header text-right">
                    <a style="background-color: rgb(36, 44, 73);" class="btn btn-secondary btn-sm" href="<?= base_url('pu_customer/customer') ?>">
                        <i class="fas fa-chevron-left"></i>&nbsp;Back
                    </a>
                </div>
                <div class="card-body">
                    <form id="form">
                        <div class="row">
                            <div class="col-md-6">
                                <!-- First Set of Fields -->
                                <div class="form-group row">
                                    <label class="col-sm-5" for="customer_id">Customer ID</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="customer_id" name="customer_id" value="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="group_id">Group ID</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" id="group_id" name="group_id" style="cursor: pointer;">
                                            <option value="" hidden>Pilih Group</option>
                                            <option id="group-option" value="group">Group</option>
                                            <option value="-">Non Group</option>
                                            <?php foreach ($group_id as $data) : ?>
                                                <option value="<?= $data['group_id'] ?>"><?= $data['group_id'] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="title">Title</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" id="title" name="title" style="cursor: pointer;" required>
                                            <option value="" hidden>Pilih Title</option>
                                            <option value="Tn">Tn</option>
                                            <option value="Ny">Ny</option>
                                            <option value="Nn">Nn</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="nama">Nama</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="nama" name="nama" required autocomplete="off">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="jenis_kelamin">Jenis Kelamin</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required style="cursor: pointer;">
                                            <option value="" hidden>Pilih Jenis Kelamin</option>
                                            <option value="Laki-laki">Laki-laki</option>
                                            <option value="Perempuan">Perempuan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="no_hp">No Telp</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="no_hp" name="no_hp" required autocomplete="off">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="asal">Asal</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="asal" name="asal" required autocomplete="off">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="akun">Akun</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="akun" name="akun" autocomplete="off">
                                    </div>
                                </div>
                                <div class="form-group row" style="margin-top: 23px; margin-bottom: 9px">
                                    <label class="col-sm-5" for="pas_foto">Pas Foto</label>
                                    <div class="col-sm-7">
                                        <img alt="foto" width="100" style="display: none" id="pas_foto_image">
                                        <span id="delete_pas_foto" style="display: none; background-color: #E74A3B; color: white; padding: 5px 7px; cursor: pointer; font-size: 13px; margin-left: 10px; border-radius: 3px"><i class="fa fa-solid fa-trash"></i></span>
                                        <p id="pas_foto_tidak_ada" style="display: none;"></p>
                                        <div class="form-group" style="margin-bottom: 0;">
                                            <input type="file" class="form-control-file" id="pas_foto" name="pas_foto" style="border: 1px solid rgb(209,211,226); padding: 3px 5px; cursor: pointer; border-radius: 6px">
                                            <span class="kwitansi-label">Max Size : 3MB</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="ktp">KTP</label>
                                    <div class="col-sm-7">
                                        <img alt="foto" width="100" style="display: none" id="ktp_foto">
                                        <span id="delete_ktp" style="display: none; background-color: #E74A3B; color: white; padding: 5px 7px; cursor: pointer; font-size: 13px; margin-left: 10px; border-radius: 3px"><i class="fa fa-solid fa-trash"></i></span>
                                        <p id="ktp_tidak_ada" style="display: none;"></p>
                                        <div class="form-group" style="margin-bottom: 0;">
                                            <input type="file" class="form-control-file" id="ktp" name="ktp" style="border: 1px solid rgb(209,211,226); padding: 3px 5px; cursor: pointer; border-radius: 6px">
                                            <span class="kwitansi-label">Max Size : 3MB</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Second Set of Fields -->
                                <div class="form-group row">
                                    <label class="col-sm-5" for="kk">KK</label>
                                    <div class="col-sm-7">
                                        <img alt="foto" width="100" style="display: none" id="kk_foto">
                                        <span id="delete_kk" style="display: none; background-color: #E74A3B; color: white; padding: 5px 7px; cursor: pointer; font-size: 13px; margin-left: 10px; border-radius: 3px"><i class="fa fa-solid fa-trash"></i></span>
                                        <p id="kk_tidak_ada" style="display: none;"></p>
                                        <div class="form-group" style="margin-bottom: 0;">
                                            <input type="file" class="form-control-file" id="kk" name="kk" style="border: 1px solid rgb(209,211,226); padding: 3px 5px; cursor: pointer; border-radius: 6px">
                                            <span class="kwitansi-label">Max Size : 3MB</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="buku_nikah">Buku Nikah</label>
                                    <div class="col-sm-7">
                                        <img alt="foto" width="100" style="display: none" id="buku_nikah_foto">
                                        <span id="delete_buku_nikah" style="display: none; background-color: #E74A3B; color: white; padding: 5px 7px; cursor: pointer; font-size: 13px; margin-left: 10px; border-radius: 3px"><i class="fa fa-solid fa-trash"></i></span>
                                        <p id="buku_nikah_tidak_ada" style="display: none;"></p>
                                        <div class="form-group" style="margin-bottom: 0;">
                                            <input type="file" class="form-control-file" id="buku_nikah" name="buku_nikah" style="border: 1px solid rgb(209,211,226); padding: 3px 5px; cursor: pointer; border-radius: 6px">
                                            <span class="kwitansi-label">Max Size : 3MB</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="paspor">Paspor</label>
                                    <div class="col-sm-7">
                                        <img alt="foto" width="100" style="display: none" id="paspor_foto">
                                        <span id="delete_paspor" style="display: none; background-color: #E74A3B; color: white; padding: 5px 7px; cursor: pointer; font-size: 13px; margin-left: 10px; border-radius: 3px"><i class="fa fa-solid fa-trash"></i></span>
                                        <p id="paspor_tidak_ada" style="display: none;"></p>
                                        <div class="form-group" style="margin-bottom: 0;">
                                            <input type="file" class="form-control-file" id="paspor" name="paspor" style="border: 1px solid rgb(209,211,226); padding: 3px 5px; cursor: pointer; border-radius: 6px">
                                            <span class="kwitansi-label">Max Size : 3MB</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="akta_lahir">Akta Lahir</label>
                                    <div class="col-sm-7">
                                        <img alt="foto" width="100" style="display: none" id="akta_lahir_foto">
                                        <span id="delete_akta_lahir" style="display: none; background-color: #E74A3B; color: white; padding: 5px 7px; cursor: pointer; font-size: 13px; margin-left: 10px; border-radius: 3px"><i class="fa fa-solid fa-trash"></i></span>
                                        <p id="akta_lahir_tidak_ada" style="display: none;"></p>
                                        <div class="form-group" style="margin-bottom: 0;">
                                            <input type="file" class="form-control-file" id="akta_lahir" name="akta_lahir" style="border: 1px solid rgb(209,211,226); padding: 3px 5px; cursor: pointer; border-radius: 6px">
                                            <span class="kwitansi-label">Max Size : 3MB</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="kartu_kuning">Kartu Kuning</label>
                                    <div class="col-sm-7">
                                        <img alt="foto" width="100" style="display: none" id="kartu_kuning_foto">
                                        <span id="delete_kartu_kuning" style="display: none; background-color: #E74A3B; color: white; padding: 5px 7px; cursor: pointer; font-size: 13px; margin-left: 10px; border-radius: 3px"><i class="fa fa-solid fa-trash"></i></span>
                                        <p id="kartu_kuning_tidak_ada" style="display: none;"></p>
                                        <div class="form-group" style="margin-bottom: 0;">
                                            <input type="file" class="form-control-file" id="kartu_kuning" name="kartu_kuning" style="border: 1px solid rgb(209,211,226); padding: 3px 5px; cursor: pointer; border-radius: 6px">
                                            <span class="kwitansi-label">Max Size : 3MB</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden inputs -->
                        <input type="hidden" name="id" id="id" value="<?= $id ?>">
                        <?php if (!empty($aksi)) { ?>
                            <input type="hidden" name="aksi" id="aksi" value="<?= $aksi ?>">
                        <?php } ?>
                        <?php if ($id == 0) { ?>
                            <input type="hidden" name="kode" id="kode" value="">
                        <?php } ?>

                        <!-- Submit button -->
                        <button type="submit" class="btn btn-primary btn-sm aksi"></button>
                    </form>

                    <!-- Modal -->
                    <div id="imageModal" class="modal fade" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-body text-center">
                                    <img id="modalImage" src="" alt="Gambar" class="img-fluid" />
                                    <br>
                                    <a id="downloadImageBtn" href="#" class="btn btn-primary mt-3" download target="_blank">
                                        <i class="fa fa-download"></i> Download Gambar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>

<script>
    $(document).ready(function() {
        $('#no_hp').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    });

    // Fungsi untuk memformat angka menjadi format Rupiah
    function formatRupiah(angka, prefix) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        // Tambahkan titik jika angka menjadi ribuan
        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }

    // Fungsi untuk memformat input pada saat halaman di-load dan saat input berubah
    function formatMultipleInputsToRupiah(inputIds) {
        inputIds.forEach(function(id) {
            var inputElement = document.getElementById(id);
            var currentValue = inputElement.value;

            // Format nilai awal saat halaman di-load
            if (currentValue) {
                inputElement.value = formatRupiah(currentValue, 'Rp. ');
            }

            // Event listener untuk memformat input saat pengguna mengetik
            inputElement.addEventListener('input', function(e) {
                inputElement.value = formatRupiah(this.value, 'Rp. ');
            });
        });
    }

    $(document).ready(function() {
        // Ketika halaman di-load, panggil customer ID dari server
        $.ajax({
            url: '<?= base_url("pu_customer/generate_customer_id") ?>', // Sesuaikan dengan URL yang benar
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                // Isi nilai customer_id ke input field
                $('#customer_id').val(response.customer_id);
            }
        });
    });

    $(document).ready(function() {
        var id = $('#id').val();
        var aksi = $('#aksi').val();
        var kode = $('#kode').val();

        if (id == 0) {
            $('.aksi').text('Save');
            $('#customer_id').val(kode).attr('readonly', true);
        } else {
            $('.aksi').text('Update');
            $("select option[value='group']").hide();
            $.ajax({
                url: "<?php echo site_url('pu_customer/edit_data') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    moment.locale('id');
                    $('#id').val(data['master'].id);
                    $('#customer_id').val(data['master'].customer_id.toUpperCase()).attr('readonly', true);
                    $('#group_id').val(data['master'].group_id);
                    $('#title').val(data['master'].title);
                    $('#nama').val(data['master'].nama);
                    $('#no_hp').val(data['master'].no_hp);
                    $('#jenis_kelamin').val(data['master'].jenis_kelamin);
                    $('#asal').val(data['master'].asal);
                    $('#akun').val(data['master'].akun);


                    // Update gambar jika ada

                    // Pas Foto
                    if (data['master'].pas_foto) {
                        $('#pas_foto_image').attr('src', "<?php echo base_url('assets/backend/document/pu_customer/pas_foto/') ?>" + data['master'].pas_foto)
                            .css({
                                'margin-bottom': '10px',
                                'cursor': 'pointer'
                            }) // Menambahkan cursor pointer
                            .show();
                        $('#pas_foto_tidak_ada').hide();
                        $('#pas_foto_image').click(function() {
                            $('#modalImage').attr('src', "<?php echo base_url('assets/backend/document/pu_customer/pas_foto/') ?>" + data['master'].pas_foto);
                            $('#imageModal').modal('show');
                        });
                        if (aksi != 'read') {
                            $('#delete_pas_foto').show();
                        }
                    } else {
                        $('#pas_foto_image').hide();
                        $('#pas_foto_tidak_ada').text('Tidak ada').show();
                    }

                    // KTP
                    if (data['master'].ktp) {
                        $('#ktp_foto').attr('src', "<?php echo base_url('assets/backend/document/pu_customer/ktp/') ?>" + data['master'].ktp)
                            .css({
                                'margin-bottom': '10px',
                                'cursor': 'pointer'
                            }) // Menambahkan cursor pointer
                            .show();
                        $('#ktp_tidak_ada').hide();
                        $('#ktp_foto').click(function() {
                            $('#modalImage').attr('src', "<?php echo base_url('assets/backend/document/pu_customer/ktp/') ?>" + data['master'].ktp);
                            $('#imageModal').modal('show');
                        });
                        if (aksi != 'read') {
                            $('#delete_ktp').show();
                        }
                    } else {
                        $('#ktp_foto').hide();
                        $('#ktp_tidak_ada').text('Tidak ada').show();
                    }

                    // KK
                    if (data['master'].kk) {
                        $('#kk_foto').attr('src', "<?php echo base_url('assets/backend/document/pu_customer/kk/') ?>" + data['master'].kk)
                            .css({
                                'margin-bottom': '10px',
                                'cursor': 'pointer'
                            }) // Menambahkan cursor pointer
                            .show();
                        $('#kk_tidak_ada').hide();
                        $('#kk_foto').click(function() {
                            $('#modalImage').attr('src', "<?php echo base_url('assets/backend/document/pu_customer/kk/') ?>" + data['master'].kk);
                            $('#imageModal').modal('show');
                        });
                        if (aksi != 'read') {
                            $('#delete_kk').show();
                        }
                    } else {
                        $('#kk_foto').hide();
                        $('#kk_tidak_ada').text('Tidak ada').show();
                    }

                    // Buku Nikah
                    if (data['master'].buku_nikah) {
                        $('#buku_nikah_foto').attr('src', "<?php echo base_url('assets/backend/document/pu_customer/buku_nikah/') ?>" + data['master'].buku_nikah)
                            .css({
                                'margin-bottom': '10px',
                                'cursor': 'pointer'
                            }) // Menambahkan cursor pointer
                            .show();
                        $('#buku_nikah_tidak_ada').hide();
                        $('#buku_nikah_foto').click(function() {
                            $('#modalImage').attr('src', "<?php echo base_url('assets/backend/document/pu_customer/buku_nikah/') ?>" + data['master'].buku_nikah);
                            $('#imageModal').modal('show');
                        });
                        if (aksi != 'read') {
                            $('#delete_buku_nikah').show();
                        }
                    } else {
                        $('#buku_nikah_foto').hide();
                        $('#buku_nikah_tidak_ada').text('Tidak ada').show();
                    }

                    // Paspor
                    if (data['master'].paspor) {
                        $('#paspor_foto').attr('src', "<?php echo base_url('assets/backend/document/pu_customer/paspor/') ?>" + data['master'].paspor)
                            .css({
                                'margin-bottom': '10px',
                                'cursor': 'pointer'
                            }) // Menambahkan cursor pointer
                            .show();
                        $('#paspor_tidak_ada').hide(); // Sembunyikan tulisan "Tidak ada" jika gambar ada
                        $('#paspor_foto').click(function() {
                            $('#modalImage').attr('src', "<?php echo base_url('assets/backend/document/pu_customer/paspor/') ?>" + data['master'].paspor);
                            $('#imageModal').modal('show');
                        });
                        if (aksi != 'read') {
                            $('#delete_paspor').show();
                        }
                    } else {
                        $('#paspor_foto').hide(); // Sembunyikan gambar jika tidak ada
                        $('#paspor_tidak_ada').text('Tidak ada').show(); // Tampilkan tulisan "Tidak ada"
                    }

                    // Akta Lahir
                    if (data['master'].akta_lahir) {
                        $('#akta_lahir_foto').attr('src', "<?php echo base_url('assets/backend/document/pu_customer/akta_lahir/') ?>" + data['master'].akta_lahir)
                            .css({
                                'margin-bottom': '10px',
                                'cursor': 'pointer'
                            }) // Menambahkan cursor pointer
                            .show();
                        $('#akta_lahir_tidak_ada').hide();
                        $('#akta_lahir_foto').click(function() {
                            $('#modalImage').attr('src', "<?php echo base_url('assets/backend/document/pu_customer/akta_lahir/') ?>" + data['master'].akta_lahir);
                            $('#imageModal').modal('show');
                        });
                        if (aksi != 'read') {
                            $('#delete_akta_lahir').show();
                        }
                    } else {
                        $('#akta_lahir_foto').hide();
                        $('#akta_lahir_tidak_ada').text('Tidak ada').show();
                    }

                    // Kartu Kuning
                    if (data['master'].kartu_kuning) {
                        $('#kartu_kuning_foto').attr('src', "<?php echo base_url('assets/backend/document/pu_customer/kartu_kuning/') ?>" + data['master'].kartu_kuning)
                            .css({
                                'margin-bottom': '10px',
                                'cursor': 'pointer'
                            }) // Menambahkan cursor pointer
                            .show();
                        $('#kartu_kuning_tidak_ada').hide();
                        $('#kartu_kuning_foto').click(function() {
                            $('#modalImage').attr('src', "<?php echo base_url('assets/backend/document/pu_customer/kartu_kuning/') ?>" + data['master'].kartu_kuning);
                            $('#imageModal').modal('show');
                        });
                        if (aksi != 'read') {
                            $('#delete_kartu_kuning').show();
                        }
                    } else {
                        $('#kartu_kuning_foto').hide();
                        $('#kartu_kuning_tidak_ada').text('Tidak ada').show();
                    }

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });

        }

        // Script delete data file gambar

        function deleteFile(field) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "File ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?php echo base_url("pu_customer/delete_file"); ?>', // URL controller untuk menghapus file
                        type: 'POST',
                        data: {
                            id: id, // Kirim ID customer untuk diupdate
                            field: field // Kirim nama field yang akan dihapus
                        },
                        success: function(response) {
                            Swal.fire(
                                'Terhapus!',
                                'File berhasil dihapus.',
                                'success'
                            );
                            // Update tampilan untuk field yang dihapus
                            $('#' + field + '_foto').hide();
                            $('#' + field + '_image').hide();
                            $('#' + field + '_tidak_ada').text('Tidak ada').show();
                            $('#delete_' + field).hide();
                        },
                        error: function() {
                            Swal.fire('Error', 'Tidak dapat menghapus file.', 'error');
                        }
                    });
                }
            });
        }

        // Event listeners untuk setiap tombol hapus
        $('#delete_pas_foto').on('click', function() {
            deleteFile('pas_foto');
        });
        $('#delete_ktp').on('click', function() {
            deleteFile('ktp');
        });
        $('#delete_kk').on('click', function() {
            deleteFile('kk');
        });
        $('#delete_buku_nikah').on('click', function() {
            deleteFile('buku_nikah');
        });
        $('#delete_paspor').on('click', function() {
            deleteFile('paspor');
        });
        $('#delete_akta_lahir').on('click', function() {
            deleteFile('akta_lahir');
        });
        $('#delete_kartu_kuning').on('click', function() {
            deleteFile('kartu_kuning');
        });

        if (aksi == "read") {
            $('.aksi').hide();
            $('#customer_id').prop('disabled', true).css('cursor', 'not-allowed');
            $('#group_id').prop('disabled', true).css('cursor', 'not-allowed');
            $('#title').prop('disabled', true).css('cursor', 'not-allowed');
            $('#nama').prop('readonly', true).css('cursor', 'not-allowed');
            $('#no_hp').prop('readonly', true).css('cursor', 'not-allowed');
            $('#jenis_kelamin').prop('disabled', true).css('cursor', 'not-allowed');
            $('#asal').prop('readonly', true).css('cursor', 'not-allowed');
            $('#akun').prop('readonly', true).css('cursor', 'not-allowed');

            $('#paspor').css('display', 'none');
            $('#kartu_kuning').css('display', 'none');
            $('#ktp').css('display', 'none');
            $('#kk').css('display', 'none');
            $('#buku_nikah').css('display', 'none');
            $('#akta_lahir').css('display', 'none');
            $('#pas_foto').css('display', 'none');

            $('#delete_paspor').hide();
            $('#delete_kartu_kuning').css('display', 'none');
            $('#delete_ktp').css('display', 'none');
            $('#delete_kk').css('display', 'none');
            $('#delete_buku_nikah').css('display', 'none');
            $('#delete_akta_lahir').css('display', 'none');
            $('#delete_pas_foto').css('display', 'none');

            $('.kwitansi-label').css('display', 'none');
        }

        $("#form").submit(function(e) {
            e.preventDefault();
            var $form = $(this);
            if (!$form.valid()) return false;

            var url;
            if (id == 0) {
                url = "<?php echo site_url('pu_customer/add') ?>";
            } else {
                url = "<?php echo site_url('pu_customer/update') ?>";
            }

            // Gunakan FormData untuk menangani file upload
            var formData = new FormData($form[0]); // Ambil semua data form termasuk file

            // Tampilkan loading Swal sebelum request dikirim
            Swal.fire({
                title: 'Memproses...',
                html: 'Mohon tunggu, data sedang disimpan',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                contentType: false, // Agar tidak mengubah tipe konten FormData
                processData: false, // Agar data FormData tidak diproses menjadi string
                dataType: "JSON",
                success: function(data) {
                    Swal.close(); // Tutup loading swal setelah request selesai

                    if (data.status) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Your data has been saved',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.href = "<?= base_url('pu_customer/customer') ?>";
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.close(); // Tutup loading swal jika terjadi error
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error adding / updating data!',
                    });
                }
            });
        });

        $("#form").validate({
            rules: {
                customer_id: {
                    required: true,
                },
                group_id: {
                    required: true,
                },
                title: {
                    required: true,
                },
                nama: {
                    required: true,
                },
                jenis_kelamin: {
                    required: true,
                },
                no_hp: {
                    required: true,
                },
                asal: {
                    required: true,
                },
            },
            messages: {
                customer_id: {
                    required: "ID Customer is required",
                },
                group_id: {
                    required: "Group ID is required",
                },
                title: {
                    required: "Title is required",
                },
                nama: {
                    required: "Nama is required",
                },
                jenis_kelamin: {
                    required: "Jenis Kelamin is required",
                },
                no_hp: {
                    required: "No Telp is required",
                },
                asal: {
                    required: "Asal is required",
                },
            },
            errorPlacement: function(error, element) {
                if (element.parent().hasClass('input-group')) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function(element) {
                $(element).addClass('is-invalid'); // Tambahkan kelas untuk menandai input tidak valid
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid'); // Hapus kelas jika input valid
            },
            focusInvalid: false, // Disable auto-focus on the first invalid field
        });

        // MEMBUAT TAMPILAN HARGA MENJADI ADA TITIK
        // $('#sebesar').on('input', function() {
        //     let value = $(this).val().replace(/[^,\d]/g, '');
        //     let parts = value.split(',');
        //     let integerPart = parts[0];

        //     // Format tampilan dengan pemisah ribuan
        //     integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

        //     // Set nilai yang diformat ke tampilan
        //     $(this).val(parts[1] !== undefined ? integerPart + ',' + parts[1] : integerPart);

        //     // Hapus semua pemisah ribuan untuk pengiriman ke server
        //     let cleanValue = value.replace(/\./g, '');

        //     // Anda mungkin ingin menyimpan nilai bersih ini di input hidden atau langsung mengirimkannya ke server
        //     $('#hidden_sebesar').val(cleanValue);
        // });

        // // Update signature name on input
        // $('#nama_pengajuan').on('input', function() {
        //     var namaPengajuan = $(this).val();
        //     $('#signNamaPengajuan').text(namaPengajuan);
        // });

    });

    // Set href tombol download setiap kali modal dibuka
    $('#modalImage').on('load', function() {
        var imgSrc = $(this).attr('src');
        $('#downloadImageBtn').attr('href', imgSrc);

        // Optional: set nama file download otomatis
        var filename = imgSrc.split('/').pop();
        $('#downloadImageBtn').attr('download', filename);
    });
</script>