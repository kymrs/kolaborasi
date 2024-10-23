<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title_view ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header text-right">
                    <a class="btn btn-secondary btn-sm" href="<?= base_url('customer_pu') ?>">
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
                                            <option value="">Non Group</option>
                                            <?php foreach ($group_id as $data) : ?>
                                                <option value="<?= $data['group_id'] ?>"><?= $data['group_id'] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="tgl_berangkat">Tanggal Berangkat</label>
                                    <div class="col-sm-7">
                                        <div class="input-group date">
                                            <input type="text" class="form-control" name="tgl_berangkat" id="tgl_berangkat" placeholder="DD-MM-YYYY" autocomplete="off" style="cursor: pointer;">
                                            <div class="input-group-append">
                                                <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="title">Title</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" id="title" name="title" style="cursor: pointer;">
                                            <option value="" hidden>Pilih Title</option>
                                            <option value="Tn">Tn</option>
                                            <option value="Yn">Yn</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="nama">Nama</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="nama" name="nama" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="jenis_kelamin">Jenis Kelamin</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" style="cursor: pointer;">
                                            <option value="" hidden>Pilih Jenis Kelamin</option>
                                            <option value="Laki-laki">Laki-laki</option>
                                            <option value="Perempuan">Perempuan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="no_hp">No Telp</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="no_hp" name="no_hp" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="travel">Travel</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" id="travel" name="travel" style="cursor: pointer;">
                                            <option value="" hidden>Pilih Travel</option>
                                            <?php foreach ($travel as $data) : ?>
                                                <option value="<?= $data['travel'] ?>"><?= $data['travel'] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="asal">Asal</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="asal" name="asal" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="produk">Produk</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="produk" name="produk" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="harga">Harga</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="harga" name="harga" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="harga_promo">Harga Promo</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="harga_promo" name="harga_promo" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="tipe_kamar">Tipe Kamar</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" id="tipe_kamar" name="tipe_kamar" style="cursor: pointer;">
                                            <option value="" hidden>Pilih Tipe Kamar</option>
                                            <option value="Double">Double</option>
                                            <option value="Triple">Triple</option>
                                            <option value="Quad">Quad</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="promo_diskon">Promo Diskon</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="promo_diskon" name="promo_diskon" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="paspor">Paspor</label>
                                    <div class="col-sm-7">
                                        <img alt="foto" width="185" style="display: none" id="customer_foto">
                                        <div class="form-group">
                                            <input type="file" class="form-control-file" id="paspor" name="paspor">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="kartu_kuning">Kartu Kuning</label>
                                    <div class="col-sm-7">
                                        <img alt="foto" width="185" style="display: none" id="customer_foto">
                                        <div class="form-group">
                                            <input type="file" class="form-control-file" id="kartu_kuning" name="kartu_kuning">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Second Set of Fields -->
                                <div class="form-group row">
                                    <label class="col-sm-5" for="ktp">KTP</label>
                                    <div class="col-sm-7">
                                        <img alt="foto" width="185" style="display: none" id="customer_foto">
                                        <div class="form-group">
                                            <input type="file" class="form-control-file" id="ktp" name="ktp">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="kk">KK</label>
                                    <div class="col-sm-7">
                                        <img alt="foto" width="185" style="display: none" id="customer_foto">
                                        <div class="form-group">
                                            <input type="file" class="form-control-file" id="kk" name="kk">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="buku_nikah">Buku Nikah</label>
                                    <div class="col-sm-7">
                                        <img alt="foto" width="185" style="display: none" id="customer_foto">
                                        <div class="form-group">
                                            <input type="file" class="form-control-file" id="buku_nikah" name="buku_nikah">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="akta_lahir">Akta Lahir</label>
                                    <div class="col-sm-7">
                                        <img alt="foto" width="185" style="display: none" id="customer_foto">
                                        <div class="form-group">
                                            <input type="file" class="form-control-file" id="akta_lahir" name="akta_lahir">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row" style="margin-top: 23px; margin-bottom: 9px">
                                    <label class="col-sm-5" for="pas_foto">Pas Foto</label>
                                    <div class="col-sm-7">
                                        <img alt="foto" width="185" style="display: none" id="customer_foto">
                                        <div class="form-group">
                                            <input type="file" class="form-control-file" id="pas_foto" name="pas_foto">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="dp">DP</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="dp" name="dp" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="pembayaran_1">Pembayaran 1</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="pembayaran_1" name="pembayaran_1" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="pembayaran_2">Pembayaran 2</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="pembayaran_2" name="pembayaran_2" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="pelunasan">Pelunasan</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="pelunasan" name="pelunasan" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="cashback">Cashback</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="cashback" name="cashback" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="akun">Akun</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="akun" name="akun" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="pakaian">Pakaian</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="pakaian" name="pakaian" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="ukuran">Ukuran</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="ukuran" name="ukuran" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="kirim">Kirim</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="kirim" name="kirim" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="perlengkapan">Perlengkapan</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="perlengkapan" name="perlengkapan" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5" for="status">Status</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="status" name="status" required>
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
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>

<script>
    $('#tgl_berangkat').datepicker({
        dateFormat: 'dd-mm-yy',
        minDate: new Date(),
        // maxDate: new Date(),

        // MENGENERATE KODE DEKLARASI SETELAH PILIH TANGGAL
        onSelect: function(dateText) {
            var id = dateText;
            $('#tgl_berangkat').removeClass("is-invalid");

            // Menghapus label error secara manual jika ada
            if ($("#tgl_berangkat-error").length) {
                $("#tgl_berangkat-error").remove(); // Menghapus label error
            }
        }
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

    // Panggil fungsi saat halaman di-load
    window.onload = function() {
        var inputIds = ['harga', 'harga_promo', 'promo_diskon', 'dp', 'pembayaran_1', 'pembayaran_2', 'pelunasan', 'cashback'];
        formatMultipleInputsToRupiah(inputIds);
    }

    $(document).ready(function() {
        // Ketika halaman di-load, panggil customer ID dari server
        $.ajax({
            url: '<?= base_url("customer_pu/generate_customer_id") ?>', // Sesuaikan dengan URL yang benar
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
            $("select option[value='']").hide();
            $.ajax({
                url: "<?php echo site_url('customer_pu/edit_data') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    moment.locale('id');
                    $('#id').val(data['master'].id);
                    $('#customer_id').val(data['master'].customer_id.toUpperCase()).attr('readonly', true);
                    $('#group_id').val(data['master'].group_id);
                    $('#tgl_berangkat').val(moment(data['master'].tgl_berangkat).format('DD-MM-YYYY'));
                    $('#title').val(data['master'].title);
                    $('#nama').val(data['master'].nama);
                    $('#no_hp').val(data['master'].no_hp);
                    $('#travel').val(data['master'].travel);
                    $('#jenis_kelamin').val(data['master'].jenis_kelamin);
                    $('#asal').val(data['master'].asal);
                    $('#produk').val(data['master'].produk);

                    // Memformat harga dan nilai lainnya ke dalam format Rupiah
                    $('#harga').val(formatRupiah(data['master'].harga, 'Rp. '));
                    $('#harga_promo').val(formatRupiah(data['master'].harga_promo, 'Rp. '));
                    $('#promo_diskon').val(formatRupiah(data['master'].promo_diskon, 'Rp. '));
                    $('#dp').val(formatRupiah(data['master'].dp, 'Rp. '));
                    $('#pembayaran_1').val(formatRupiah(data['master'].pembayaran_1, 'Rp. '));
                    $('#pembayaran_2').val(formatRupiah(data['master'].pembayaran_2, 'Rp. '));
                    $('#pelunasan').val(formatRupiah(data['master'].pelunasan, 'Rp. '));
                    $('#cashback').val(formatRupiah(data['master'].cashback, 'Rp. '));

                    $('#tipe_kamar').val(data['master'].tipe_kamar);
                    $('#paspor').val(data['master'].paspor);
                    $('#kartu_kuning').val(data['master'].kartu_kuning);
                    $('#ktp').val(data['master'].ktp);
                    $('#kk').val(data['master'].kk);
                    $('#buku_nikah').val(data['master'].buku_nikah);
                    $('#akta_lahir').val(data['master'].akta_lahir);
                    $('#akun').val(data['master'].akun);
                    $('#pakaian').val(data['master'].pakaian);
                    $('#ukuran').val(data['master'].ukuran);
                    $('#kirim').val(data['master'].kirim);
                    $('#perlengkapan').val(data['master'].perlengkapan);
                    $('#status').val(data['master'].status);

                    // Update gambar jika ada
                    if (data['master'].pas_foto) {
                        $('#customer_foto').attr('src', "<?php echo base_url('assets/backend/document/customer/') ?>" + data['master'].pas_foto).css('margin-bottom', '14px');
                        $('#customer_foto').show(); // Menampilkan gambar jika ada
                    } else {
                        $('#customer_foto').hide(); // Sembunyikan gambar jika tidak ada
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });

        }

        if (aksi == "read") {
            $('.aksi').hide();
            $('#group_id').prop('disabled', true);
            $('#tgl_berangkat').prop('disabled', true);
            $('#title').prop('readonly', true);
            $('#nama').prop('readonly', true);
            $('#no_hp').prop('readonly', true);
            $('#jenis_kelamin').prop('disabled', true);
            $('#travel').prop('disabled', true);
            $('#asal').prop('readonly', true);
            $('#produk').prop('readonly', true);
            $('#harga').prop('readonly', true);
            $('#harga_promo').prop('readonly', true);
            $('#tipe_kamar').prop('readonly', true);
            $('#promo_diskon').prop('readonly', true);
            $('#paspor').prop('readonly', true);
            $('#kartu_kuning').prop('readonly', true);
            $('#ktp').prop('readonly', true);
            $('#kk').prop('readonly', true);
            $('#buku_nikah').prop('readonly', true);
            $('#dp').prop('readonly', true);
            $('#akta_lahir').prop('readonly', true);
            $('#pas_foto').css('display', 'none');
            $('#pembayaran_1').prop('readonly', true);
            $('#pembayaran_2').prop('readonly', true);
            $('#pelunasan').prop('readonly', true);
            $('#cashback').prop('readonly', true);
            $('#akun').prop('readonly', true);
            $('#pakaian').prop('readonly', true);
            $('#ukuran').prop('readonly', true);
            $('#kirim').prop('readonly', true);
            $('#perlengkapan').prop('readonly', true);
            $('#status').prop('readonly', true);
        }

        $("#form").submit(function(e) {
            e.preventDefault();
            var $form = $(this);
            if (!$form.valid()) return false;

            var url;
            if (id == 0) {
                url = "<?php echo site_url('customer_pu/add') ?>";
            } else {
                url = "<?php echo site_url('customer_pu/update') ?>";
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
                            location.href = "<?= base_url('customer_pu') ?>";
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
                nama: {
                    required: true,
                },
                no_hp: {
                    required: true,
                },
                travel: {
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
                nama: {
                    required: "Nama is required",
                },
                no_hp: {
                    required: "Contact Number is required",
                },
                travel: {
                    required: "Travel is required",
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