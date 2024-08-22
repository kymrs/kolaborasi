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
                    <form id="form" enctype="multipart/form-data">
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
                                    <label class="col-sm-5">Tanggal Pengajuan</label>
                                    <div class="col-sm-7">
                                        <div class="input-group date">
                                            <input type="text" class="form-control" onchange="ubahPengajuan()" name="tgl_pengajuan" id="tgl_pengajuan" placeholder="DD-MM-YYYY" autocomplete="off">
                                            <div class="input-group-append">
                                                <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5">Kode Reimbust</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="kode_reimbust" name="kode_reimbust" placeholder="Kode Reimbust">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5">Nama</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="nama" name="nama" autocomplete="off" placeholder="Nama">
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
                            </div>

                            <!-- SEBELAH KANAN -->
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-5">Jabatan</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="jabatan" name="jabatan" autocomplete="off" placeholder="Jabatan">
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
                                        <input type="text" class="form-control" id="jumlah_prepayment" name="jumlah" autocomplete="off" placeholder="Jumlah Prepayment">
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
                                        <th scope="col" id="action">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="input-container">
                                    <!-- CONTAINER INPUTAN -->
                                </tbody>
                            </table>
                        </div>
                        <!-- PENENTUAN UPDATE ATAU ADD -->
                        <input type="hidden" name="id" id="id" value="<?= $id ?>">
                        <?php if (!empty($aksi)) { ?>
                            <input type="hidden" name="aksi" id="aksi" value="<?= $aksi ?>">
                        <?php } ?>
                        <?php if ($id == 0) { ?>
                            <input type="hidden" name="kode" id="kode" value="">
                            <button type="submit" class="btn btn-primary btn-sm aksi"></button>
                        <?php } else { ?>
                            <button type="submit" class="btn btn-primary btn-sm aksi"></button>
                        <?php } ?>
                        <!-- END PENENTUAN UPDATE ATAU ADD -->

                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Deklarasi Form</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="input1">Input 1</label>
                                            <input type="email" class="form-control" id="input1" aria-describedby="emailHelp">
                                        </div>
                                        <div class="form-group">
                                            <label for="input2">Input 2</label>
                                            <input type="email" class="form-control" id="input1" aria-describedby="emailHelp">
                                        </div>
                                        <div class="form-group">
                                            <label for="input3">Input 3</label>
                                            <input type="email" class="form-control" id="input1" aria-describedby="emailHelp">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary">Save changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal -->
                        <div id="myModal" class="kwitansi-modal">
                            <span class="close">&times;</span>
                            <img class="modal-content" id="img01">
                            <!-- <div id="caption"></div> -->
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>

<script>
    $('#tgl_pengajuan').datepicker({
        dateFormat: 'dd-mm-yy',
        minDate: new Date(),

        // MENGENERATE KODE PREPAYMENT SETELAH PILIH TANGGAL
        onSelect: function(dateText) {
            var date = $('#tgl_pengajuan').val();
            var id = dateText;
            $.ajax({
                url: "<?php echo site_url('reimbust/generate_kode') ?>",
                type: "POST",
                data: {
                    "date": dateText
                },
                dataType: "JSON",
                success: function(data) {
                    $('#kode_reimbust').val(data.toUpperCase());
                    $('#kode').val(data);
                },
                error: function(error) {
                    alert("error" + error);
                }
            });
        }
    });

    $(document).ready(function() {
        var id = $('#id').val();
        var aksi = $('#aksi').val();
        var kode = $('#kode').val();
        var sifat_pelaporan = $('#sifat_pelaporan').val();
        let inputCount = 0;
        let deletedRows = [];
        console.log(aksi);

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

        // Append dari form ADD
        function addRow() {
            rowCount++;
            const row = `
                <tr id="row-${rowCount}">
                    <td class="row-number">${rowCount}</td>
                    <td><input type="text" class="form-control" name="pemakaian[${rowCount}]" value="" placeholder="Pemakaian ${rowCount}"  autocomplete="off"></td>
                    <td>
                        <input type="text" class="form-control tgl_nota" name="tgl_nota[${rowCount}]" id="tgl_nota_${rowCount}" style="cursor: pointer" autocomplete="off" placeholder="Tanggal Nota ${rowCount}">
                    </td>
                    <td>
                        <input type="text" class="form-control" id="jumlah-${rowCount}" placeholder="Jumlah ${rowCount}" name="jml[${rowCount}]" autocomplete="off">
                        <input type="hidden" id="hidden_jumlah${rowCount}" name="jumlah[${rowCount}]" value="">
                    </td>
                    <td style="padding: 12px 12px 5px !important">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="kwitansi[${rowCount}]" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">
                            <label class="custom-file-label" for="inputGroupFile01">Upload..</label>
                            <span class="kwitansi-label">Max Size : 3MB</span>
                        </div>
                    </td>
                    <td width="150" style="padding: 15px 10px"><div class="btn btn-primary btn-lg btn-block btn-sm" data-toggle="modal" data-target="#exampleModal" id="deklarasi">Deklarasi</div></td>
                    <td><span class="btn delete-btn btn-danger" data-id="${rowCount}">Delete</span></td>
                </tr>
                `;
            $('#input-container').append(row);
            // Tambahkan format ke input jumlah yang baru
            formatJumlahInput(`#jumlah-${rowCount}`);
            updateSubmitButtonState(); // Perbarui status tombol submit
            // checkDeleteButtonState(); // Cek tombol delete setelah baris ditambahkan

            //VALIDASI ROW YANG TELAH DI APPEND
            $("#form").validate().settings.rules[`pemakaian[${rowCount}]`] = {
                required: true
            };
            $("#form").validate().settings.rules[`tgl_nota[${rowCount}]`] = {
                required: true
            };
            $("#form").validate().settings.rules[`jml[${rowCount}]`] = {
                required: true
            };

            // Inisialisasi Datepicker pada elemen dengan id 'tgl_nota'
            $(document).on('focus', '.tgl_nota', function() {
                $(this).datepicker({
                    dateFormat: 'dd-mm-yy', // Format default sementara
                    changeMonth: true,
                    changeYear: true,
                    onSelect: function(dateText, inst) {
                        // Hapus kelas error dan elemen pesan error saat tanggal dipilih
                        $(this).removeClass('is-invalid');

                        for (i = 1; i <= rowCount; i++) {
                            if ($(`#tgl_nota_${i}-error`).length) {
                                $(`#tgl_nota_${i}-error`).remove();
                            }
                        }
                    }
                }).datepicker('show');
            });

        }

        function deleteRow(id) {
            // Simpan ID dari row yang dihapus
            const rowId = $(`#row-${id}`).find('input:hidden[id^="hidden_detail_id"]').val();
            if (rowId) {
                deletedRows.push(rowId);
            }
            console.log(rowId);

            $(`#row-${id}`).remove();
            // Reorder rows and update row numbers
            reorderRows();
            checkDeleteButtonState(); // Cek tombol delete setelah baris dihapus
            updateSubmitButtonState(); // Perbarui status tombol
        }

        function reorderRows() {
            $('#input-container tr').each(function(index) {
                const newRowNumber = index + 1;
                const detailIdValue = $(this).find('input[name^="detail_id"]').val();
                const pemakaianValue = $(this).find('input[name^="pemakaian"]').val();
                const tgl_notaValue = $(this).find('input[name^="tgl_nota"]').val();
                const jumlahValue = $(this).find('input[name^="jml"]').val();
                const kwitansiValue = $(this).find('input[name^="kwitansi_image"]').val();

                $(this).attr('id', `row-${newRowNumber}`);
                $(this).find('.row-number').text(newRowNumber);
                $(this).find('input[name^="detail_id"]').attr('name', `detail_id[${newRowNumber}]`).attr('placeholder', `detail_id ${newRowNumber}`).val(detailIdValue);
                $(this).find('input[name^="pemakaian"]').attr('name', `pemakaian[${newRowNumber}]`).attr('placeholder', `Pemakaian ${newRowNumber}`).val(pemakaianValue);
                $(this).find('input[name^="tgl_nota"]').attr('name', `tgl_nota[${newRowNumber}]`).attr('placeholder', `Tanggal Nota ${newRowNumber}`).val(tgl_notaValue);
                $(this).find('input[name^="jml"]').attr('name', `jml[${newRowNumber}]`).attr('placeholder', `Jumlah ${newRowNumber}`).val(jumlahValue);
                $(this).find('input[name^="kwitansi_image"]').attr('name', `kwitansi_image[${newRowNumber}]`).attr('placeholder', `Input ${newRowNumber}`).val(kwitansiValue);
                $(this).find('.delete-btn').attr('data-id', newRowNumber).text('Delete');
            });
            rowCount = $('#input-container tr').length; // Update rowCount to the current number of rows
        }

        $('#add-row').click(function() {
            addRow();
        });

        function updateSubmitButtonState() {
            const rowCount = $('#input-container tr').length;
            if (rowCount > 0) {
                $('.aksi').prop('disabled', false).css('cursor', 'pointer'); // Enable submit button
            } else {
                $('.aksi').prop('disabled', true).css('cursor', 'not-allowed'); // Disable submit button
            }
        }

        function checkDeleteButtonState() {
            const rowCount = $('#input-container tr').length;
            if (rowCount === 1) {
                $('#input-container .delete-btn').prop('disabled', true); // Disable delete button if only one row
            } else {
                $('#input-container .delete-btn').prop('disabled', false); // Enable delete button if more than one row
            }
        }

        $(document).on('click', '.delete-btn', function() {
            const id = $(this).data('id');
            deleteRow(id);
        });

        $('#form').submit(function(event) {
            // Tambahkan array deletedRows ke dalam form data sebelum submit
            $('<input>').attr({
                type: 'hidden',
                name: 'deleted_rows',
                value: JSON.stringify(deletedRows)
            }).appendTo('#form');

            // Lanjutkan dengan submit form
        });

        // Script file input
        $(document).on('change', '.custom-file-input', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });

        $('#sifat_pelaporan').on('input', function() {
            var sifatPelaporan = $(this).val();
            handleSifatPelaporanChange(sifatPelaporan);
        });

        // Event listener untuk perubahan pada select "sifat_pelaporan"
        function handleSifatPelaporanChange(sifatPelaporan) {
            if (aksi == 'add') {
                if (sifatPelaporan == 'Reimbust') {
                    $('#nama').prop('disabled', false).css('cursor', 'auto');
                    $('#departemen').prop('disabled', false).css('cursor', 'pointer');
                    $('#jabatan').prop('disabled', false).css('cursor', 'auto');
                    $('#tgl_pengajuan').prop('disabled', false).css('cursor', 'pointer');
                    $('#tujuan').prop('disabled', false).css('cursor', 'auto');
                    $('#status').prop('disabled', false).css('cursor', 'pointer');
                    $('#jumlah_prepayment').prop('disabled', false).css('cursor', 'auto');
                } else if (sifatPelaporan == 'Pelaporan') {
                    $('#nama').prop('disabled', true).css('cursor', 'auto');
                    $('#departemen').prop('disabled', true).css('cursor', 'pointer');
                    $('#jabatan').prop('disabled', false).css('cursor', 'auto');
                    $('#tgl_pengajuan').prop('disabled', false).css('cursor', 'pointer');
                    $('#tujuan').prop('disabled', false).css('cursor', 'auto');
                    $('#status').prop('disabled', false).css('cursor', 'pointer');
                    $('#jumlah_prepayment').prop('disabled', false).css('cursor', 'auto');
                } else {
                    $('#nama').prop('disabled', true).css('cursor', 'not-allowed');
                    $('#departemen').prop('disabled', true).css('cursor', 'not-allowed');
                    $('#jabatan').prop('disabled', true).css('cursor', 'not-allowed');
                    $('#tgl_pengajuan').prop('disabled', true).css('cursor', 'not-allowed');
                    $('#tujuan').prop('disabled', true).css('cursor', 'not-allowed');
                    $('#status').prop('disabled', true).css('cursor', 'not-allowed');
                    $('#jumlah_prepayment').prop('disabled', true).css('cursor', 'not-allowed');
                }
            } else if (aksi == 'update') {
                if (sifatPelaporan == 'Reimbust') {
                    $('#nama').prop('readonly', false).css('cursor', 'auto');
                    $('#departemen').prop('readonly', false).css('cursor', 'pointer');
                    $('#jabatan').prop('readonly', false).css('cursor', 'auto');
                    $('#tgl_pengajuan').prop('readonly', false).css('cursor', 'pointer');
                    $('#tujuan').prop('readonly', false).css('cursor', 'auto');
                    $('#status').prop('readonly', false).css('cursor', 'pointer');
                    $('#jumlah_prepayment').prop('readonly', false).css('cursor', 'auto');
                } else if (sifatPelaporan == 'Pelaporan') {
                    $('#nama').prop('readonly', true).css('cursor', 'auto');
                    $('#departemen').prop('readonly', true).css('cursor', 'pointer');
                    $('#jabatan').prop('readonly', false).css('cursor', 'auto');
                    $('#tgl_pengajuan').prop('readonly', false).css('cursor', 'pointer');
                    $('#tujuan').prop('readonly', false).css('cursor', 'auto');
                    $('#status').prop('readonly', false).css('cursor', 'pointer');
                    $('#jumlah_prepayment').prop('readonly', false).css('cursor', 'auto');
                } else {
                    $('#nama').prop('readonly', true).css('cursor', 'not-allowed');
                    $('#departemen').prop('readonly', true).css('cursor', 'not-allowed');
                    $('#jabatan').prop('readonly', true).css('cursor', 'not-allowed');
                    $('#tgl_pengajuan').prop('readonly', true).css('cursor', 'not-allowed');
                    $('#tujuan').prop('readonly', true).css('cursor', 'not-allowed');
                    $('#status').prop('readonly', true).css('cursor', 'not-allowed');
                    $('#jumlah_prepayment').prop('readonly', true).css('cursor', 'not-allowed');
                }
            } else if (aksi == 'read') {
                if (sifatPelaporan == 'Reimbust' || sifatPelaporan == 'Pelaporan') {
                    $('input').prop('disabled', true).css('cursor', 'not-allowed');
                    $('select').prop('disabled', true).css('cursor', 'not-allowed');
                    $('textarea').prop('disabled', true).css('cursor', 'not-allowed');
                } else {
                    $('input').prop('disabled', true).css('cursor', 'not-allowed');
                    $('select').prop('disabled', true).css('cursor', 'not-allowed');
                    $('textarea').prop('disabled', true).css('cursor', 'not-allowed');
                }
            }
        }

        setInterval(function() {
            var sifatPelaporan = $('#sifat_pelaporan').val();
            handleSifatPelaporanChange(sifatPelaporan);
        }, 01); // Memeriksa setiap detik

        // // Panggil change event secara manual untuk mengatur state awal saat halaman dimuat
        // $('#sifat_pelaporan').trigger('change');

        if (id == 0) {
            $('.aksi').text('Save');
            $('#sifat_pelaporan').prop('disabled', false).css('cursor', 'pointer');
            $('#kode_reimbust').val(kode).prop('readonly', true).css('cursor', 'not-allowed');
        } else {
            $('.aksi').text('Update');
            $('#sifat_pelaporan').prop('disabled', false).css('cursor', 'pointer');
            $('#kode_reimbust').val(kode).prop('readonly', true).css('cursor', 'not-allowed');
            $("select option[value='']").hide();
            $.ajax({
                url: "<?= site_url('reimbust/edit_data') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    moment.locale('id')

                    if (aksi == 'update') {
                        // Set nilai untuk setiap field dari data master
                        $('#sifat_pelaporan').val(data['master']['sifat_pelaporan']);
                        $('#id').val(data['master']['id']);
                        $('#kode_reimbust').val(data['master']['kode_reimbust']).attr('readonly', true);
                        $('#nama').val(data['master']['nama']);
                        $('#jabatan').val(data['master']['jabatan']);
                        $('#departemen').val(data['master']['departemen']);
                        $('#tgl_pengajuan').val(moment(data['master']['tgl_pengajuan']).format('DD-MM-YYYY'));
                        $('#tujuan').val(data['master']['tujuan']);
                        $('#status').val(data['master']['status']);
                        $('#jumlah_prepayment').val(data['master']['jumlah_prepayment'].replace(/\B(?=(\d{3})+(?!\d))/g, '.'));

                        //APPEND DATA TRANSAKSI DETAIL REIMBUST
                        $(data['transaksi']).each(function(index) {
                            //Nilai jumlah diformat menggunakan pemisah ribuan sebelum dimasukkan ke dalam elemen input.
                            const jumlahFormatted = data['transaksi'][index]['jumlah'].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                            const tglNotaFormatted = moment(data['transaksi'][index]['tgl_nota']).format('DD-MM-YYYY');
                            // Append Dari Form UPDATE
                            const row = `
                                <tr id="row-${index + 1}">
                                    <td class="row-number">${index + 1}</td>
                                    <td>
                                        <input type="text" class="form-control" name="pemakaian[${index + 1}]" value="${data['transaksi'][index]['pemakaian']}" autocomplete="off">
                                        
                                        <input type="hidden" id="hidden_reimbust_id${index}" name="reimbust_id" value="${data['master']['id']}">
                                        <input type="hidden" id="hidden_detail_id${index}" name="detail_id[${index + 1}]" value="${data['transaksi'][index]['id']}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control tgl_nota" name="tgl_nota[${index + 1}]" style="cursor: pointer" autocomplete="off" value="${tglNotaFormatted}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" id="jumlah-${index}" value="${jumlahFormatted}" name="jml[${index + 1}]" autocomplete="off">
                                        <input type="hidden" id="hidden_jumlah${index}" name="jumlah[${index + 1}]" value="${data['transaksi'][index]['jumlah']}">
                                    </td>
                                    <td>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="kwitansi[${index + 1}]" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">
                                            <label class="custom-file-label" for="inputGroupFile01">${data['transaksi'][index]['kwitansi']}</label>
                                        </div>
                                        <input type="hidden" class="form-control" name="kwitansi_image[${index + 1}]" value="${data['transaksi'][index]['kwitansi']}">
                                        <span class="kwitansi-label">Max Size : 3MB</span>
                                    </td>
                                    <td width="125" style="padding: 16px 10px !important">
                                        <div class="btn btn-primary btn-lg btn-block btn-sm" data-toggle="modal" data-target="#exampleModal">Deklarasi</div>
                                    </td>
                                    <td><span class="btn delete-btn btn-danger" data-id="${index + 1}">Delete</span></td>
                                </tr>
                                `;
                            $('#input-container').append(row);
                            // Tambahkan format ke input jumlah yang baru
                            formatJumlahInput(`#jumlah-${index}`);

                            //VALIDASI ROW YANG TELAH DI APPEND
                            $("#form").validate().settings.rules[`rincian[${index + 1}]`] = {
                                required: true
                            };
                            $("#form").validate().settings.rules[`nominal[${index + 1}]`] = {
                                required: true
                            };
                            $("#form").validate().settings.rules[`keterangan[${index + 1}]`] = {
                                required: true
                            };
                            rowCount = index + 1;
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        // UNTUK TAMPILAN READ ONLY
        if (aksi == "read") {
            // $('.aksi').hide();
            // $('#id').prop('readonly', true);
            // $('#sifat_pelaporan').prop('disabled', true);
            // $('#tgl_pengajuan').prop('disabled', true);
            // $('#nama').prop('disabled', false);
            // $('#departemen').prop('disabled', true);
            // $('#jabatan').prop('disabled', true);
            // $('#tujuan').prop('disabled', true);
            // $('#status').prop('disabled', true);
            // $('#jumlah_prepayment').prop('disabled', true);
            // $('th:last-child').remove();

            $.ajax({
                url: "<?php echo site_url('reimbust/read_detail/') ?>" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $(data).each(function(index) {
                        //Nilai nominal diformat menggunakan pemisah ribuan sebelum dimasukkan ke dalam elemen input.
                        const nominalReadFormatted = data[index]['nominal'].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                        const row = `
                        <tr id="row-${index}">
                            <td class="row-number">${index + 1}</td>
                            <td><input readonly type="text" class="form-control" name="sifat_pelaporan[${index}]" value="${data[index]['sifat_pelaporan']}"></td>
                        </tr>
                        `;
                        $('#input-container').append(row);
                    });
                }
            });
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

            var formData = new FormData(this);

            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
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
                            location.href = "<?= base_url('reimbust') ?>";
                        });
                    } else {
                        // Tampilkan pesan kesalahan
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: data.error
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error adding / updating data: ' + textStatus
                    });
                }
            });
        });


        // $("#form").submit(function(e) {
        //     e.preventDefault();
        //     var $form = $(this);
        //     if (!$form.valid()) return false;
        //     var url;
        //     if (id == 0) {
        //         url = "<?php echo site_url('reimbust/add') ?>";
        //     } else {
        //         url = "<?php echo site_url('reimbust/update') ?>";
        //     }

        //     $.ajax({
        //         url: url,
        //         type: "POST",
        //         data: $('#form').serialize(),
        //         dataType: "JSON",
        //         success: function(data) {
        //             if (data.status) //if success close modal and reload ajax table
        //             {
        //                 Swal.fire({
        //                     position: 'center',
        //                     icon: 'success',
        //                     title: 'Your data has been saved',
        //                     showConfirmButton: false,
        //                     timer: 1500
        //                 }).then((result) => {
        //                     location.href = "<?= base_url('reimbust') ?>";
        //                 })
        //             }
        //         },
        //         error: function(jqXHR, textStatus, errorThrown) {
        //             alert('Error adding / update data');
        //         }
        //     });
        // });

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
                jumlah: {
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
                jumlah: {
                    required: "Jumlah Prepayment is required",
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

    $(document).on('focus', '#tgl_pengajuan', function() {
        // Inisialisasi dan tampilkan datepicker
        $(this).datepicker({
            dateFormat: 'dd-mm-yy', // Format default sementara
            changeMonth: true,
            changeYear: true,
        }).datepicker('show');
    });
</script>