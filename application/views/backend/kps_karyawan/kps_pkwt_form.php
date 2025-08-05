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

    /* ...existing code... */
    .tag {
        display: inline-block;
        background: #1a2035;
        color: #fff;
        border-radius: 15px;
        padding: 3px 12px 3px 10px;
        margin: 2px 2px 2px 0;
        font-size: 0.95em;
        position: relative;
    }

    .tag .remove-tag {
        margin-left: 8px;
        cursor: pointer;
        color: #fff;
        font-weight: bold;
    }

    .tag .remove-tag:hover {
        color: red;
    }

    /* ...existing code... */

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
                    <a style="background-color: rgb(36, 44, 73);" class="btn btn-secondary btn-sm" onclick="window.history.back()">
                        <i class="fas fa-chevron-left"></i>&nbsp;Back
                    </a>
                </div>
                <div class="card-body">
                    <form id="form">
                        <div class="row">
                            <div class="col-md-6">
                                <!-- First Set of Fields -->
                                <div class="form-group row">
                                    <label class="col-sm-5" for="no_perjanjian">No Perjanjian</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="no_perjanjian" id="no_perjanjian" placeholder="No Perjanjian" readonly>
                                    </div>
                                </div>
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
                                    <label class="col-sm-5" for="jk_awal">Kontrak Awal</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control datepicker" name="jk_awal" id="jk_awal" placeholder="Pilih Tanggal Kontrak Awal" style="cursor: pointer">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="jk_akhir">Kontrak Akhir</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control datepicker" name="jk_akhir" id="jk_akhir" placeholder="Pilih Tanggal Kontrak Akhir" style="cursor: pointer">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="hari">Hari</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="hari" id="hari" placeholder="Hari">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="bulan">Bulan</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="bulan" id="bulan" placeholder="Bulan">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="tahun">Tahun</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="tahun" id="tahun" placeholder="Tahun">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Second Set of Fields -->
                                <div class="form-group row">
                                    <label class="col-sm-5" for="jangka_waktu">Jangka Waktu</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="jangka_waktu" id="jangka_waktu">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="gaji">Gaji</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control rupiah" name="gaji" id="gaji">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="tj_pulsa">Tunjangan Pulsa</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control rupiah" name="tj_pulsa" id="tj_pulsa">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="tj_ops">Tunjangan Operasional</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control rupiah" name="tj_ops" id="tj_ops">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="thr">THR</label>
                                    <div class="col-sm-7">
                                        <select name="thr" id="thr" class="form-control">
                                            <option value="" hidden>Pilih THR</option>
                                            <option value="Ada">Ada</option>
                                            <option value="Tidak Ada">Tidak Ada</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="tj_kehadiran">Tunjangan Kehadiran</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control rupiah" name="tj_kehadiran" id="tj_kehadiran">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="insentif">Insentif</label>
                                    <div class="col-sm-7">
                                        <select name="insentif" id="insentif" class="form-control">
                                            <option value="" hidden>Pilih Insentif</option>
                                            <option value="Ada">Ada</option>
                                            <option value="Tidak Ada">Tidak Ada</option>
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

    $(document).on('input', '.only-number', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    function getRoman(num) {
        // Fungsi untuk mengubah angka ke romawi
        const romans = ["", "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII"];
        return romans[parseInt(num)] || num;
    }

    function generateNoPerjanjian() {
        let npk = $('#npk').val();
        let jk_awal = $('#jk_awal').val();
        let jk_akhir = $('#jk_akhir').val();

        if (npk && jk_awal && jk_akhir) {
            $.ajax({
                url: '<?= site_url("kps_karyawan/generate_no_perjanjian") ?>',
                type: 'POST',
                data: {
                    npk: npk,
                    jk_awal: jk_awal,
                    jk_akhir: jk_akhir
                },
                dataType: 'json',
                success: function(res) {
                    if (res.status) {
                        $('#no_perjanjian').val(res.no_perjanjian);
                    } else {
                        $('#no_perjanjian').val('');
                    }
                }
            });
        }
    }

    // Trigger generate saat ketiga input terisi/berubah
    $('#npk, #jk_awal, #jk_akhir').on('change', generateNoPerjanjian);

    // Validasi tanggal kontrak awal dan akhir
    $('#jk_awal, #jk_akhir').on('change', function() {
        let jk_awal = $('#jk_awal').val();
        let jk_akhir = $('#jk_akhir').val();

        if (jk_awal && jk_akhir) {
            let awal = new Date(jk_awal);
            let akhir = new Date(jk_akhir);

            if (awal.getMonth() === akhir.getMonth() && awal.getFullYear() === akhir.getFullYear()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Tanggal kontrak awal dan kontrak akhir tidak boleh berada di bulan yang sama!',
                    showConfirmButton: true
                });
                $('#jk_awal').val('')
                $('#jk_akhir').val('')
            }
        }
    });

    $(document).ready(function() {
        $('.rupiah').on('input', function() {
            let input = $(this).val().replace(/[^,\d]/g, '').toString();
            let split = input.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            $(this).val('Rp ' + rupiah);
        });
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

    $('#status_kerja').on('change', function() {
        if ($(this).val() == 'nonaktif') {
            $('#tgl_phk_container').show();
        } else {
            $('#tgl_phk_container').hide();
        }
    });

    $('#foto').on('change', function() {
        const file = this.files[0];

        if (file) {
            const maxSize = 3 * 1024 * 1024; // 3 MB dalam byte

            if (file.size > maxSize) {
                swal.fire({
                    icon: 'warning',
                    text: "Ukuran file tidak boleh melebihi dari 3MB!",
                    confirmButtonColor: "#0e131d"
                });
                $(this).val(''); // reset input file
            }
        }
    });

    $(document).ready(function() {
        $('#nama_bank').select2({
            placeholder: "Pilih Nama Bank",
            allowClear: true
        });
        $('#jabatan').select2({
            placeholder: "Pilih Jabatan",
            allowClear: true
        });
        $('#grade').select2({
            placeholder: "Pilih Grade",
            allowClear: true
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
            $('#npk').attr('disabled', true);
            $('#jk_awal').attr('readonly', true).css('pointer-events', 'none');
            $('#jk_akhir').attr('readonly', true).css('pointer-events', 'none');
            $.ajax({
                url: "<?php echo site_url('kps_karyawan/edit_data2') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#id').val(data['transaksi'].id);
                    // // ISI FIELD PRODUK AGEN
                    $('#npk').val(data['transaksi'].npk).change();
                    $('#no_perjanjian').val(data['transaksi'].no_perjanjian);
                    $('#jk_awal').val(data['transaksi'].jk_awal);
                    $('#jk_akhir').val(data['transaksi'].jk_akhir);
                    $('#hari').val(data['transaksi'].hari);
                    $('#bulan').val(data['transaksi'].bulan);
                    $('#tahun').val(data['transaksi'].tahun);
                    $('#jangka_waktu').val(data['transaksi'].jangka_waktu);
                    $('#gaji').val(data['transaksi']['gaji'].replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
                    $('#tj_pulsa').val(data['transaksi']['tj_pulsa'].replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
                    $('#tj_ops').val(data['transaksi']['tj_ops'].replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
                    $('#thr').val(data['transaksi']['thr'].replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
                    $('#tj_kehadiran').val(data['transaksi']['tj_kehadiran'].replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
                    $('#insentif').val(data['transaksi'].insentif);
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
            $('#form input, #form select, #form textarea').prop('disabled', true).css('cursor', 'not-allowed');
            $('.aksi').hide(); // Sembunyikan tombol aksi jika mode read
        }

        $("#form").submit(function(e) {
            e.preventDefault();
            var $form = $(this);
            if (!$form.valid()) return false;

            var url;
            if (id == 0) {
                url = "<?php echo site_url('kps_karyawan/add_kontrak_karyawan') ?>";
            } else {
                url = "<?php echo site_url('kps_karyawan/update_kontrak_karyawan/') ?>" + id;
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
                            location.href = "<?= base_url('kps_karyawan/e_pkwt') ?>";
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
                status_kerja: {
                    required: true,
                }
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