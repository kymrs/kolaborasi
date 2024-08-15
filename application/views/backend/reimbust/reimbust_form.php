<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title_view ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header text-right">
                    <a class="btn btn-secondary btn-sm" href="<?= base_url('reimbust') ?>"><i class="fas fa-chevron-left"></i>&nbsp;Back</a>
                </div>
                <div class="card-body">
                    <form id="form" enctype="multipart/form-data" action="<?= base_url('reimbust/add') ?>" method="post">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-5">Sifat Pelaporan</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" id="sifat_pelaporan" name="sifat_pelaporan">
                                            <option value="">-- Pilih --</option>
                                            <option value="Reimbust">Reimbust</option>
                                            <option value="Pelaporan">Pelaporan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5">Kode Reimbust</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="kode_reimbust" name="kode_reimbust">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5">Nama</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="nama" name="nama">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5">Departemen</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" name="departemen" id="departemen">
                                            <option value="">-- Pilih --</option>
                                            <option value="Marketing">Marketing</option>
                                            <option value="IT">IT</option>
                                            <option value="General Affair">General Affair</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5">Jabatan</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="jabatan" name="jabatan">
                                    </div>
                                </div>
                            </div>

                            <!-- SEBELAH KANAN -->
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-5">Tanggal Pengajuan</label>
                                    <div class="col-sm-7">
                                        <div class="input-group date">
                                            <input type="text" class="form-control" name="tgl_pengajuan" id="tgl_pengajuan" placeholder="DD-MM-YYYY" autocomplete="off">
                                            <div class="input-group-append">
                                                <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5">Tujuan</label>
                                    <div class="col-sm-7">
                                        <div class="form-floating">
                                            <textarea class="form-control" placeholder="Tujuan" id="tujuan" name="tujuan"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5">Status</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" name="status" id="status">
                                            <option value="">-- Pilih --</option>
                                            <option value="Waiting">Waiting</option>
                                            <option value="On Proccess">On Proccess</option>
                                            <option value="Done">Done</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5">Jumlah Prepayment</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="jumlah_prepayment">
                                        <input type="hidden" id="hidden_jumlah_prepayment" name="jumlah_prepayment">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- BUTTON TAMBAH FORM -->
                        <div class="mt-3">
                            <button type="button" class="btn btn-success btn-sm" id="add-row"><i class="fa fa-plus" aria-hidden="true"></i> Add</button>
                        </div>
                        <!-- TABLE INPUT -->
                        <div class="mt-2">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Pemakaian</th>
                                        <th scope="col">Tanggal Nota</th>
                                        <th scope="col">Jumlah</th>
                                        <th scope="col">Kwitansi</th>
                                        <th scope="col">Deklarasi</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="input-container">
                                    <!-- CONTAINER INPUTAN -->
                                </tbody>
                            </table>
                        </div>
                        <input type="hidden" name="id" id="id" value="<?= $id ?>">
                        <?php if (!empty($aksi)) { ?>
                            <input type="hidden" name="aksi" id="aksi" value="<?= $aksi ?>">
                        <?php } ?>
                        <?php if ($id == 0) { ?>
                            <input type="hidden" name="kode" id="kode" value="<?= $kode ?>">
                        <?php } ?>
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
    $(document).ready(function() {
        var id = $('#id').val();
        var aksi = $('#aksi').val();
        var kode = $('#kode').val();
        var sifat_pelaporan = $('#sifat_pelaporan').val();
        let inputCount = 0;

        //MEMBUAT TAMPILAN HARGA MENJADI ADA TITIK
        $('#jumlah_prepayment').on('input', function() {
            let value = $(this).val().replace(/[^,\d]/g, '');
            let parts = value.split(',');
            let integerPart = parts[0];

            // Format tampilan dengan pemisah ribuan
            integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            // Set nilai yang diformat ke tampilan
            $(this).val(parts[1] !== undefined ? integerPart + ',' + parts[1] : integerPart);

            // Hapus semua pemisah ribuan untuk pengiriman ke server
            let cleanValue = value.replace(/\./g, '');

            // Anda mungkin ingin menyimpan nilai bersih ini di input hidden atau langsung mengirimkannya ke server
            $('#hidden_jumlah_prepayment').val(cleanValue);
        });

        // Tambahkan fungsi untuk memformat input jumlah memiliki titik
        function formatJumlahInput(selector) {
            $(document).on('input', selector, function() {
                let value = $(this).val().replace(/[^,\d]/g, '');
                let parts = value.split(',');
                let integerPart = parts[0];

                // Format tampilan dengan pemisah ribuan
                integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

                // Set nilai yang diformat ke tampilan
                $(this).val(parts[1] !== undefined ? integerPart + ',' + parts[1] : integerPart);

                // Hapus semua pemisah ribuan untuk pengiriman ke server
                let cleanValue = value.replace(/\./g, '');

                // Pastikan elemen hidden dengan ID yang benar diperbarui
                const hiddenId = `#hidden_${$(this).attr('id').replace('jumlah-', 'jumlah')}`;
                $(hiddenId).val(cleanValue);
            });
        }

        //MENAMBAH FORM INPUTAN DI ADD FORM
        let rowCount = 0;

        // Panggil fungsi saat baris baru ditambahkan
        function addRow() {
            rowCount++;
            const row = `
                <tr id="row-${rowCount}">
                    <td class="row-number">${rowCount}</td>
                    <td><input type="text" class="form-control" name="pemakaian[${rowCount}]" placeholder="Pemakaian ${rowCount}"></td>
                    <td><input type="text" class="form-control" name="tgl_nota[${rowCount}]" placeholder="Tanggal Nota ${rowCount}"></td>
                    <td>
                        <input type="text" class="form-control" id="jumlah-${rowCount}" placeholder="Jumlah ${rowCount}" />
                        <input type="hidden" id="hidden_jumlah${rowCount}" name="jumlah[${rowCount}]" value="">
                    </td>
                    <td>
                        <label for="file-upload" class="custom-file-upload">
                            <i class="fas fa-solid fa-upload"></i> Upload Foto</label>
                        <input type="file" class="form-control" name="gambar" size="20" id="file-upload">
                    </td>
                    <td width="150" style="padding: 15px 10px"><div class="btn btn-primary btn-lg btn-block btn-sm">Deklarasi ${rowCount}</div></td>
                    <td><span class="btn delete-btn btn-danger" data-id="${rowCount}">Delete</span></td>
                </tr>
                `;
            $('#input-container').append(row);
            // Tambahkan format ke input jumlah yang baru
            formatJumlahInput(`#jumlah-${rowCount}`);
        }

        function deleteRow(id) {
            $(`#row-${id}`).remove();
            // Reorder rows and update row numbers
            reorderRows();
        }

        function reorderRows() {
            $('#input-container tr').each(function(index) {
                const newRowNumber = index + 1;
                $(this).attr('id', `row-${newRowNumber}`);
                $(this).find('.row-number').text(newRowNumber);
                $(this).find('input').attr('id', `pemakaian[${newRowNumber}]`).attr('placeholder', `Input ${newRowNumber}`);
                $(this).find('input').attr('id', `tgl_nota[${newRowNumber}]`).attr('placeholder', `Input ${newRowNumber}`);
                $(this).find('input').attr('id', `jumlah[${newRowNumber}]`).attr('placeholder', `Input ${newRowNumber}`);
                $(this).find('input').attr('id', `kwitansi[${newRowNumber}]`).attr('placeholder', `Input ${newRowNumber}`);
                $(this).find('input').attr('id', `deklarasi[${newRowNumber}]`).attr('placeholder', `Input ${newRowNumber}`);
                $(this).find('.delete-btn').attr('data-id', newRowNumber).text('Delete');
            });
            rowCount = $('#input-container tr').length; // Update rowCount to the current number of rows
        }

        $('#add-row').click(function() {
            addRow();
        });

        $(document).on('click', '.delete-btn', function() {
            const id = $(this).data('id');
            deleteRow(id);
        });

        // Event listener untuk perubahan pada select "sifat_pelaporan"
        $('#sifat_pelaporan').change(function() {
            // Ambil nilai dari sifat_pelaporan
            var sifatPelaporan = $(this).val();

            // Jika nilai adalah "pelaporan", disable input "nama"
            if (aksi == 'add') {
                if (sifatPelaporan === 'Reimbust') {
                    $('#nama').prop('disabled', false).css('cursor', 'auto');
                    $('#departemen').prop('disabled', false).css('cursor', 'pointer');
                    $('#jabatan').prop('disabled', false).css('cursor', 'auto');
                    $('#tgl_pengajuan').prop('disabled', false).css('cursor', 'pointer');
                    $('#tujuan').prop('disabled', false).css('cursor', 'auto');
                    $('#status').prop('disabled', false).css('cursor', 'pointer');
                    $('#jumlah_prepayment').prop('disabled', false).css('cursor', 'auto');
                } else if (sifatPelaporan === 'Pelaporan') {
                    $('#nama').prop('disabled', false).css('cursor', 'auto');
                    $('#departemen').prop('disabled', false).css('cursor', 'pointer');
                    $('#jabatan').prop('disabled', false).css('cursor', 'auto');
                    $('#tgl_pengajuan').prop('disabled', false).css('cursor', 'pointer');
                    $('#tujuan').prop('disabled', false).css('cursor', 'auto');
                    $('#status').prop('disabled', false).css('cursor', 'pointer');
                    $('#jumlah_prepayment').prop('disabled', false).css('cursor', 'auto');
                } else {
                    $('#sifat_pelaporan').prop('disabled', false).css('cursor', 'pointer');
                    $('#kode_reimbust').css('cursor', 'not-allowed');
                    $('#nama').prop('disabled', true).css('cursor', 'not-allowed');
                    $('#departemen').prop('disabled', true).css('cursor', 'not-allowed');
                    $('#jabatan').prop('disabled', true).css('cursor', 'not-allowed');
                    $('#tgl_pengajuan').prop('disabled', true).css('cursor', 'not-allowed');
                    $('#tujuan').prop('disabled', true).css('cursor', 'not-allowed');
                    $('#status').prop('disabled', true).css('cursor', 'not-allowed');
                    $('#jumlah_prepayment').prop('disabled', true).css('cursor', 'not-allowed');
                }
            } else if (aksi == 'update') {
                if (sifatPelaporan != 'Reimbust' && sifatPelaporan != 'Pelaporan') {
                    $('#sifat_pelaporan').prop('disabled', false).css('cursor', 'pointer');
                    $('#nama').prop('disabled', false).css('cursor', 'auto');
                    $('#departemen').prop('disabled', false).css('cursor', 'pointer');
                    $('#jabatan').prop('disabled', false).css('cursor', 'auto');
                    $('#tgl_pengajuan').prop('disabled', false).css('cursor', 'pointer');
                    $('#tujuan').prop('disabled', false).css('cursor', 'auto');
                    $('#status').prop('disabled', false).css('cursor', 'pointer');
                    $('#jumlah_prepayment').prop('disabled', false).css('cursor', 'auto');
                }
            }
        });

        // Panggil change event secara manual untuk mengatur state awal saat halaman dimuat
        $('#sifat_pelaporan').trigger('change');

        if (id == 0) {
            $('.aksi').text('Save');
            $('#kode_reimbust').val(kode).attr('readonly', true);
        } else {
            $('.aksi').text('Update');
            $("select option[value='']").hide();
            $.ajax({
                url: "<?= site_url('reimbust/edit_data') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    moment.locale('id')

                    if (aksi == 'update') {
                        $(document).ready(function() {
                            // Set nilai untuk setiap field dari data master
                            $('#sifat_pelaporan').val(data['master']['sifat_pelaporan']);
                            $('#id').val(data['master']['id']);
                            $('#kode_reimbust').val(data['master']['kode_reimbust']).attr('readonly', true);
                            $('#nama').val(data['master']['nama']);
                            $('#jabatan').val(data['master']['jabatan']);
                            $('#departemen').val(data['master']['departemen']);
                            $('#tgl_pengajuan').val(moment(data['master']['tgl_pengajuan']).format('dddd, DD MMMM YYYY'));
                            $('#tujuan').val(data['master']['tujuan']);
                            $('#status').val(data['master']['status']);
                            $('#jumlah_prepayment').val(data['master']['jumlah_prepayment'].replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
                        });

                        //APPEND DATA TRANSAKSI DETAIL REIMBUST
                        $(data['transaksi']).each(function(index) {
                            //Nilai jumlah diformat menggunakan pemisah ribuan sebelum dimasukkan ke dalam elemen input.
                            const jumlahFormatted = data['transaksi'][index]['jumlah'].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                            const tglNotaFormatted = moment(data['transaksi'][index]['tgl_nota']).format('dddd, DD MMMM YYYY'); // Format tanggal

                            const row = `
                                <tr id="row-${index}">
                                    <td class="row-number">${index + 1}</td>
                                    <td>
                                        <input type="text" class="form-control" name="pemakaian[${index + 1}]" value="${data['transaksi'][index]['pemakaian']}" />
                                        <input type="hidden" id="hidden_id${index}" name="hidden_id" value="${data['master']['id']}">
                                        <input type="hidden" id="hidden_id_reimbust${index}" name="hidden_id_reimbust[${index + 1}]" value="${data['transaksi'][index]['id_reimbust']}">
                                    </td>
                                    <td>
                                    <div class="input-group date">
                                        <input type="text" class="form-control" name="tgl_nota[${rowCount}]" id="tgl_nota" value="${tglNotaFormatted}" style="cursor: pointer">
                                    </div>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" id="jumlah-${index}" name="jumlah[${index + 1}]" value="${jumlahFormatted}" />
                                        <input type="hidden" id="hidden_jumlah${index}" name="hidden_jumlah[${index + 1}]" value="${data['transaksi'][index]['jumlah']}">
                                    </td>
                                    <td>
                                        <label for="file-upload" class="custom-file-upload">
                                            <i class="fas fa-solid fa-upload"></i> Upload Foto</label>
                                        <input type="file" class="form-control" name="gambar" size="20" id="file-upload">
                                    </td>
                                    <td width="150" style="padding: 15px 10px">
                                        <div class="btn btn-primary btn-lg btn-block btn-sm">Deklarasi ${rowCount + 1}</div>
                                    </td>
                                    <td><span class="btn delete-btn btn-danger" data-id="${index + 1}">Delete</span></td>
                                </tr>
                                `;
                            $('#input-container').append(row);
                            // Tambahkan format ke input jumlah yang baru
                            formatJumlahInput(`#jumlah-${index}`);
                            rowCount = index + 1;
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        if (aksi == "read") {
            $('.aksi').hide();
            $('#id').prop('readonly', true);
            $('#nama').prop('readonly', true);
            $('#departemen').prop('disabled', true);
            $('#jabatan').prop('readonly', true);
            $('#sifat_pelaporan').prop('disabled', true);
            $('#tgl_pengajuan').prop('disabled', true);
            $('#tujuan').prop('readonly', true);
            $('#status').prop('disabled', true);
        }

        $("#form").submit(function(e) {
            e.preventDefault();
            var $form = $(this);
            if (!$form.valid()) return false;
            var url;
            if (id == 0) {
                url = "<?php echo site_url('reimbust/add') ?>";
            } else {
                url = "<?php echo site_url('reimbust/update') ?>";
            }

            $.ajax({
                url: url,
                type: "POST",
                data: $('#form').serialize(),
                dataType: "JSON",
                success: function(data) {
                    if (data.status) //if success close modal and reload ajax table
                    {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Your data has been saved',
                            showConfirmButton: false,
                            timer: 1500
                        }).then((result) => {
                            location.href = "<?= base_url('reimbust') ?>";
                        })
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                }
            });
        });

        $("#form").validate({
            rules: {
                nama: {
                    required: true,
                },
                departemen: {
                    required: true,
                },
                jabatan: {
                    required: true,
                },
                sifat_pelaporan: {
                    required: true,
                },
                tgl_pengajuan: {
                    required: true,
                },
                tujuan: {
                    required: true,
                },
                status: {
                    required: true,
                },
            },
            messages: {
                nama: {
                    required: "Nama is required",
                },
                departemen: {
                    required: "Departemen is required",
                },
                jabatan: {
                    required: "Jabatan is required",
                },
                sifat_pelaporan: {
                    required: "Sifat Pelaporan is required",
                },
                tgl_pengajuan: {
                    required: "Tanggal Pengajuan is required",
                },
                tujuan: {
                    required: "Tujuan is required",
                },
                status: {
                    required: "Status is required",
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
    })

    $('#tgl_pengajuan').datepicker({
        dateFormat: 'dd-mm-yy',
        minDate: new Date(),
    });

    // Inisialisasi Datepicker pada elemen dengan id 'tgl_nota'
    $(document).on('focus', '#tgl_nota', function() {
        $(this).datepicker({
            dateFormat: 'dd-mm-yy', // Format tanggal
            changeMonth: true, // Untuk menampilkan dropdown bulan
            changeYear: true // Untuk menampilkan dropdown tahun
        });
    });
</script>