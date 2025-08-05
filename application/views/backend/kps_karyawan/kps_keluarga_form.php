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
</style>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title_view ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header text-right">
                    <a style="background-color: rgb(36, 44, 73);" class="btn btn-secondary btn-sm" href="<?= base_url('kps_karyawan') ?>">
                        <i class="fas fa-chevron-left"></i>&nbsp;Back
                    </a>
                </div>
                <div class="card-body">
                    <form id="form">
                        <div class="row">
                            <div class="col-md-6">
                                <!-- First Set of Fields -->
                                <div class="form-group row">
                                    <label class="col-sm-5" for="npk">Nama Karyawan</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" name="npk" id="npk">
                                            <option value="" hidden>Pilih Karyawan</option>
                                            <?php foreach ($karyawan as $data) : ?>
                                                <option value="<?= $data['npk'] ?>"><?= $data['nama_lengkap'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="status_wp">Status Wajib Pajak</label>
                                    <div class="col-sm-7">
                                        <select id="status_wp" name="status_wp" class="form-control">
                                            <option value="" hidden>Pilih Status WP</option>
                                            <option value="TK/0">TK/0 - Tidak Kawin, tanpa tanggungan</option>
                                            <option value="TK/1">TK/1 - Tidak Kawin, dengan 1 tanggungan</option>
                                            <option value="TK/2">TK/2 - Tidak Kawin, dengan 2 tanggungan</option>
                                            <option value="TK/3">TK/3 - Tidak Kawin, dengan 3 tanggungan</option>
                                            <option value="K/0">K/0 - Kawin, tanpa tanggungan</option>
                                            <option value="K/1">K/1 - Kawin, dengan 1 tanggungan</option>
                                            <option value="K/2">K/2 - Kawin, dengan 2 tanggungan</option>
                                            <option value="K/3">K/3 - Kawin, dengan 3 tanggungan</option>
                                            <option value="HB/0">HB/0 - Hidup Berpisah, tanpa tanggungan</option>
                                            <option value="HB/1">HB/1 - Hidup Berpisah, dengan 1 tanggungan</option>
                                            <option value="HB/2">HB/2 - Hidup Berpisah, dengan 2 tanggungan</option>
                                            <option value="HB/3">HB/3 - Hidup Berpisah, dengan 3 tanggungan</option>
                                            <option value="PH/0">PH/0 - Pisah Harta, tanpa tanggungan</option>
                                            <option value="PH/1">PH/1 - Pisah Harta, dengan 1 tanggungan</option>
                                            <option value="PH/2">PH/2 - Pisah Harta, dengan 2 tanggungan</option>
                                            <option value="PH/3">PH/3 - Pisah Harta, dengan 3 tanggungan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="nama_anggota">Nama Anggota</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="nama_anggota" id="nama_anggota">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="jenis_kelamin">Jenis Kelamin</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" name="jenis_kelamin" id="jenis_kelamin">
                                            <option value="" hidden>Pilih Jenis Kelamin</option>
                                            <option value="laki-laki">Laki-laki</option>
                                            <option value="perempuan">Perempuan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="tgl_lahir">Tanggal Lahir</label>
                                    <div class="col-sm-7">
                                        <input type="date" class="form-control datepicker" name="tgl_lahir" id="tgl_lahir">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Second Set of Fields -->
                                <div class="form-group row">
                                    <label for="keanggotaan" class="col-sm-5 col-form-label">Keanggotaan</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" name="keanggotaan" id="keanggotaan">
                                            <option value="" hidden>Pilih Keanggotaan</option>
                                            <option value="pasangan">Pasangan</option>
                                            <option value="anak">Anak</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="lokasi_kerja" class="col-sm-5 col-form-label">Lokasi Kerja</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="lokasi_kerja" id="lokasi_kerja">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="wilayah_kerja" class="col-sm-5 col-form-label">Wilayah Kerja</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="wilayah_kerja" id="wilayah_kerja">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="umur">Umur</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="umur" id="umur" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="pendidikan">Pendidikan</label>
                                    <div class="col-sm-7">
                                        <select id="pendidikan" name="pendidikan" class="form-control">
                                            <option value="" hidden>Pilih Pendidikan Terakhir</option>
                                            <option value="Tidak Sekolah">Tidak/Belum Sekolah</option>
                                            <option value="Belum Tamat SD">Belum Tamat SD/Sederajat</option>
                                            <option value="SD">SD/Sederajat</option>
                                            <option value="SMP">SMP/Sederajat</option>
                                            <option value="SMA">SMA/SMK/MA/Sederajat</option>
                                            <option value="Diploma I (D1)">Diploma I (D1)</option>
                                            <option value="Diploma II (D2)">Diploma II (D2)</option>
                                            <option value="Diplom a III (D3)">Diploma III (D3)</option>
                                            <option value="Sarjana (S1)">Sarjana (S1)/Diploma IV (D4)</option>
                                            <option value="Magister (S2)">Magister (S2)</option>
                                            <option value="Doktor (S3)">Doktor (S3)</option>
                                            <option value="Lainnya">Lainnya</option>
                                        </select>
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
                        <button type="submit" class="btn btn-primary btn-sm aksi mt-3"></button>
                    </form>

                    <!-- Modal -->
                    <div id="imageModal" class="modal fade" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-body text-center">
                                    <img id="modalImage" src="" alt="Gambar" class="img-fluid" />
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
    $('#npk').select2({
        placeholder: 'Pilih Karyawan'
    });
    $('#pendidikan').select2({
        placeholder: 'Pilih Pendidikan'
    });

    $(function() {
        $('.datepicker').datepicker({
            dateFormat: 'yy-mm-dd', // atau format lain sesuai kebutuhan
            changeMonth: true,
            yearRange: "1900:2099", // bisa disesuaikan
            changeYear: true,
            autoclose: true
        });
    });

    $(document).on('change', '#tgl_lahir', function() {
        var tgl = $(this).val();
        if (tgl) {
            var birthYear = new Date(tgl).getFullYear();
            var nowYear = new Date().getFullYear();
            var umur = nowYear - birthYear;
            if (umur < 0) umur = 0;
            $('#umur').val(umur);
        } else {
            $('#umur').val('');
        }
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
                url: "<?php echo site_url('kps_karyawan/edit_data') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    console.log(data);
                    moment.locale('id');
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
                        url: '<?php echo base_url("kps_karyawan/delete_file"); ?>', // URL controller untuk menghapus file
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

        if (aksi == "read") {
            $('#form input, #form select, #form textarea').prop('disabled', true);
            $('.aksi').hide(); // Sembunyikan tombol aksi jika mode read
        }

        $("#form").submit(function(e) {
            e.preventDefault();
            var $form = $(this);
            if (!$form.valid()) return false;

            var url;
            if (id == 0) {
                url = "<?php echo site_url('kps_karyawan/add_data_keluarga') ?>";
            } else {
                url = "<?php echo site_url('kps_karyawan/update_data_keluarga') ?>";
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
                            location.href = "<?= base_url('kps_karyawan') ?>";
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
                npk: {
                    required: true,
                },
                status_wp: {
                    required: true,
                },
                nama_anggota: {
                    required: true,
                },
                jenis_kelamin: {
                    required: true,
                },
                tgl_lahir: {
                    required: true,
                },
                keanggotaan: {
                    required: true,
                },
                lokasi_kerja: {
                    required: true,
                },
                wilayah_kerja: {
                    required: true,
                },
                umur: {
                    required: true,
                },
                pendidikan: {
                    required: true,
                },
            },
            messages: {},
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

    });
</script>