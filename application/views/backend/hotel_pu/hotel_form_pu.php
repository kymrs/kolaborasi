<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title_view ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header text-right">
                    <a class="btn btn-secondary btn-sm" href="<?= base_url('hotel_pu') ?>">
                        <i class="fas fa-chevron-left"></i>&nbsp;Back
                    </a>
                </div>
                <div class="card-body">
                    <form id="form">
                        <div class="row">
                            <div class="col-md-6">
                                <!-- First Set of Fields -->
                                <div class="form-group row">
                                    <label class="col-sm-5" for="no_hp">No Telp</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="no_hp" name="no_hp" required autocomplete="off">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Second Set of Fields -->
                                <div class="form-group row">
                                    <label class="col-sm-5" for="pelunasan">Pelunasan</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="pelunasan" name="pelunasan" autocomplete="off">
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
        var id = $('#id').val();
        var aksi = $('#aksi').val();
        var kode = $('#kode').val();

        if (id == 0) {
            $('.aksi').text('Save');
            $('#customer_id').val(kode).attr('readonly', true);
        } else {
            $('.aksi').text('Update');
            $("select option[value='']").hide();
            $.ajax({
                url: "<?php echo site_url('hotel_pu/edit_data') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    moment.locale('id');
                    $('#id').val(data['master'].id);
                    $('#group_id').val(data['master'].group_id);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });

        }


        $("#form").submit(function(e) {
            e.preventDefault();
            var $form = $(this);
            if (!$form.valid()) return false;

            var url;
            if (id == 0) {
                url = "<?php echo site_url('hotel_pu/add') ?>";
            } else {
                url = "<?php echo site_url('hotel_pu/update') ?>";
            }

            // Gunakan FormData untuk menangani file upload
            var formData = new FormData($form[0]); // Ambil semua data form termasuk file

            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                contentType: false, // Agar tidak mengubah tipe konten FormData
                processData: false, // Agar data FormData tidak diproses menjadi string
                dataType: "JSON",
                success: function(data) {
                    if (data.status) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Your data has been saved',
                            showConfirmButton: false,
                            timer: 1500
                        }).then((result) => {
                            location.href = "<?= base_url('hotel_pu') ?>";
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error adding / updating data');
                }
            });
        });

        $("#form").validate({
            rules: {
                customer_id: {
                    required: true,
                },
                tgl_berangkat: {
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
                travel: {
                    required: true,
                },
                asal: {
                    required: true,
                },
                produk: {
                    required: true,
                },
                harga: {
                    required: true,
                },
                promo_diskon: {
                    required: true,
                },
            },
            messages: {
                customer_id: {
                    required: "ID Customer is required",
                },
                tgl_berangkat: {
                    required: "Tanggal Berangkat is required",
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
                travel: {
                    required: "Travel is required",
                },
                asal: {
                    required: "Asal is required",
                },
                produk: {
                    required: "Produk is required",
                },
                harga: {
                    required: "Harga is required",
                },
                promo_diskon: {
                    required: "Promo Diskon is required",
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
</script>