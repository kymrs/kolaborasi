<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title_view ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header text-right">
                    <a class="btn btn-secondary btn-sm" href="<?= base_url('reimbust_pu') ?>"><i class="fas fa-chevron-left"></i>&nbsp;Back</a>
                </div>
                <div class="card-body">
                    <form id="form" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-5">Sifat Pelaporan</label>
                                    <div class="col-sm-7" style="justify-content: space-between; align-items: center" id="parent_sifat_pelaporan">
                                        <select class="form-control" id="sifat_pelaporan" name="sifat_pelaporan" style="display: inline-block">
                                            <option value="">-- Pilih --</option>
                                            <option value="Reimbust">Reimbust</option>
                                            <option value="Pelaporan">Pelaporan</option>
                                        </select>
                                        <div class="btn btn-primary btn-small" data-toggle="modal" data-target="#pelaporanModal" id="pelaporan_button" style="margin-left: 7px;"><i class="fas fa-solid fa-search"></i></div>
                                    </div>
                                </div>
                                <!-- <div class="form-group row" >
                                    <label class="col-sm-5"></label>
                                    <div class="col-sm-7">
                                        <div class="btn btn-primary btn-small" data-toggle="modal" data-target="#pelaporanModal">Pelaporan</div>
                                    </div>
                                </div> -->
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
                                    <label class="col-sm-5">Kode Reimbust</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="kode_reimbust" name="kode_reimbust" placeholder="Kode Reimbust">
                                    </div>
                                </div>
                                <!-- <div class="form-group row">
                                    <label class="col-sm-5">Nama</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="nama" name="nama" autocomplete="off" placeholder="Nama" value="">
                                    </div>
                                </div> -->
                                <!-- <div class="form-group row">
                                    <label class="col-sm-5">Departemen</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" name="departemen" id="departemen">
                                            <option value="">-- Pilih --</option>
                                            <option value="Marketing">Marketing</option>
                                            <option value="IT">IT</option>
                                            <option value="General Affair">General Affair</option>
                                        </select>
                                    </div>
                                </div> -->
                                <input type="hidden" class="form-control" id="departemenPrepayment" name="departemen" autocomplete="off" placeholder="Departemen">
                            </div>

                            <!-- SEBELAH KANAN -->
                            <div class="col-md-6">
                                <!-- <div class="form-group row">
                                    <label class="col-sm-5">Jabatan</label>
                                    <div class="col-sm-7">
                                        </div>
                                    </div> -->
                                <input type="hidden" class="form-control" id="jabatan" name="jabatan" autocomplete="off" placeholder="Jabatan">
                                <div class="form-group row">
                                    <label class="col-sm-5">Tujuan</label>
                                    <div class="col-sm-7">
                                        <div class="form-floating">
                                            <textarea class="form-control" placeholder="Tujuan" id="tujuan" name="tujuan"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="form-group row">
                                    <label class="col-sm-5">Status</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" name="status" id="status">
                                            <option value="">-- Pilih --</option>
                                            <option value="Waiting">Waiting</option>
                                            <option value="On Proccess">On Proccess</option>
                                            <option value="Done">Done</option>
                                        </select>
                                    </div>
                                </div> -->
                                <div class="form-group row">
                                    <label class="col-sm-5">Jumlah Prepayment</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="jumlah_prepayment" name="jumlah" autocomplete="off" placeholder="Jumlah Prepayment">
                                        <input type="hidden" id="hidden_jumlah_prepayment" name="jumlah_prepayment">
                                    </div>
                                </div>
                                <div class="form-group row" id="kode_prepayment">
                                    <label class="col-sm-5">Kode Prepayment</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" readonly placeholder="Kode Prepayment" name="kode_prepayment" id="kode_prepayment_input" style="cursor: not-allowed;">
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
                                        <th scope="col">Jumlah Nominal</th>
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
                            <button type="submit" class="btn btn-primary btn-sm aksi" disabled style="cursor: not-allowed"></button>
                        <?php } else { ?>
                            <button type="submit" class="btn btn-primary btn-sm aksi"></button>
                        <?php } ?>
                        <!-- END PENENTUAN UPDATE ATAU ADD -->

                        <!-- Modal Data Table Prepayment -->
                        <div class="modal fade" id="pelaporanModal" tabindex="-1" aria-labelledby="pelaporanModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="pelaporanModalLabel">Data Pelaporan</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <table id="prepayment-table" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th style="display: none">Action</th>
                                                    <th>Kode Prepayment</th>
                                                    <th>Nama</th>
                                                    <th>Divisi</th>
                                                    <th>Jabatan</th>
                                                    <th>Tanggal Pengajuan</th>
                                                    <th>Prepayment</th>
                                                    <th>Nominal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>No</th>
                                                    <th style="display: none">Action</th>
                                                    <th>Kode Prepayment</th>
                                                    <th>Nama</th>
                                                    <th>Divisi</th>
                                                    <th>Jabatan</th>
                                                    <th>Tanggal Pengajuan</th>
                                                    <th>Prepayment</th>
                                                    <th>Nominal</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Data Table Deklarasi add & update -->
                        <div class="modal fade" id="deklarasiModal" tabindex="-1" aria-labelledby="deklarasiModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deklarasiModalLabel">Data Deklarasi</h5>
                                        <!-- <a style="position: relative; right: 75px" class="btn btn-primary btn-sm" href="<?= base_url('datadeklarasi_pu/add_form') ?>"><i class="fa fa-plus"></i>&nbsp;Add Data</a> -->
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span style="position: relative; bottom: 5px" aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <table id="deklarasi-table" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th style="display: none">Action</th>
                                                    <th>Kode Deklarasi</th>
                                                    <th>Tanggal</th>
                                                    <th>Pengaju</th>
                                                    <th>Jabatan</th>
                                                    <th>Penerima</th>
                                                    <th>Tujuan</th>
                                                    <th>Sebesar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>No</th>
                                                    <th style="display: none">Action</th>
                                                    <th>Kode Deklarasi</th>
                                                    <th>Tanggal</th>
                                                    <th>Pengaju</th>
                                                    <th>Jabatan</th>
                                                    <th>Penerima</th>
                                                    <th>Tujuan</th>
                                                    <th>Sebesar</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Data Table Deklarasi read -->
                        <div class="modal fade" id="deklarasiModalRead" tabindex="-1" aria-labelledby="deklarasiModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deklarasiModalLabel">Data Deklarasi</h5>
                                        <a style="position: relative; right: 75px" class="btn btn-primary btn-sm" href="<?= base_url('datadeklarasi_pu/add_form') ?>"><i class="fa fa-plus"></i>&nbsp;Add Data</a>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span style="position: relative; bottom: 5px" aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <table id="deklarasi-table-read" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th style="display: none">Action</th>
                                                    <th>Kode Deklarasi</th>
                                                    <th>Tanggal</th>
                                                    <th>Pengaju</th>
                                                    <th>Jabatan</th>
                                                    <th>Penerima</th>
                                                    <th>Tujuan</th>
                                                    <th>Sebesar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>No</th>
                                                    <th style="display: none">Action</th>
                                                    <th>Kode Deklarasi</th>
                                                    <th>Tanggal</th>
                                                    <th>Pengaju</th>
                                                    <th>Jabatan</th>
                                                    <th>Penerima</th>
                                                    <th>Tujuan</th>
                                                    <th>Sebesar</th>
                                                </tr>
                                            </tfoot>
                                        </table>
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
        maxDate: new Date(),

        // MENGENERATE KODE PREPAYMENT SETELAH PILIH TANGGAL
        onSelect: function(dateText) {
            var id = dateText;
            $('#tgl_pengajuan').removeClass("is-invalid");

            // Menghapus label error secara manual jika ada
            if ($("#tgl_pengajuan-error").length) {
                $("#tgl_pengajuan-error").remove(); // Menghapus label error
            }
            $.ajax({
                url: "<?php echo site_url('reimbust_pu/generate_kode') ?>",
                type: "POST",
                data: {
                    "date": dateText
                },
                dataType: "JSON",
                success: function(data) {
                    // console.log(data);
                    $('#kode_reimbust').val(data.toUpperCase());
                    $('#kode').val(data);
                },
                error: function(error) {
                    alert("error" + error);
                }
            });
        }
    });

    // Data table prepayment
    var table;

    // METHOD POST MENAMPILKAN DATA KE DATA TABLE
    $(document).ready(function() {
        var table = $('#prepayment-table').DataTable({
            "responsive": true,
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('reimbust_pu/get_list3') ?>",
                "type": "POST",
                "data": function(d) {
                    d.status = 'approved';
                }
            },
            "columnDefs": [{
                    "targets": [2, 5, 6],
                    "className": 'dt-head-nowrap'
                },
                {
                    "targets": [1, 3, 4, 5, 7],
                    "className": 'dt-body-nowrap'
                }, {
                    "targets": [0, 1],
                    "orderable": false,
                },
            ]
        });

        // Event listener untuk baris tabel dalam modal
        $('#prepayment-table tbody').on('click', 'tr', function() {
            // Ambil data dari baris yang diklik
            let data = table.row(this).data();

            // Masukkan data ke dalam input form di tampilan utama
            $('#kode_prepayment_input').val(data[2]);
            // $('#nama').val(data[3]);
            $('#departemenPrepayment').val(data[4]);
            $('#jabatan').val(data[5]);
            // $('#tgl_pengajuan').val(data[6]);
            $('#jumlah_prepayment').val(data[8]);
            var cleanedValue = data[8].replace(/\./g, '');
            $('#hidden_jumlah_prepayment').val(cleanedValue);
            $('#tujuan').val(data[7]);

            // Tutup modal setelah data dipilih
            $('#pelaporanModal').modal('hide');
        });
    });

    // Data table deklarasi

    // METHOD POST MENAMPILKAN DATA KE DATA TABLE
    $(document).ready(function() {
        var table = $('#deklarasi-table').DataTable({
            "responsive": true,
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('reimbust_pu/get_list2') ?>",
                "type": "POST"
            },
            "columnDefs": [{
                    "targets": [2],
                    "className": 'dt-head-nowrap'
                },
                {
                    "targets": [3, 4, 5, 7],
                    "className": 'dt-body-nowrap'
                },
                {
                    "targets": [0, 1],
                    "orderable": false,
                },
            ]
        });

        // Variabel untuk menyimpan rowCount
        var currentRowCount;

        // Event listener untuk tombol modal deklarasi
        $(document).on('click', '[id^=deklarasi-modal]', function() {
            currentRowCount = $(this).data('id');
        });

        // Event listener untuk baris tabel dalam modal
        $('#deklarasi-table tbody').on('click', 'tr', function() {
            let data = table.row(this).data();
            // console.log(data);
            $('#deklarasi' + currentRowCount).val(data[2]);
            $('#deklarasi-modal' + currentRowCount).text(data[2]);

            if ($('#deklarasi' + currentRowCount).val().trim() !== '') {
                // Disable semua input di baris yang sama
                $('#deklarasi' + currentRowCount).closest('tr').find('input').prop('readonly', true);
                $('#kwitansi-upload' + currentRowCount).css('pointer-events', 'none');
                $('#upload' + currentRowCount).css('background-color', '#EAECF4').text('Deklarasi').val('');
                $('.kwitansi_image' + currentRowCount).val('');
                $('#pemakaian' + currentRowCount).css('cursor', 'not-allowed').attr('placeholder', 'Deklarasi').val(data[7]);
                $('#inputGroupFile01' + currentRowCount).val('').attr('name', '');
                $('#tgl_nota_' + currentRowCount).css({
                    'cursor': 'not-allowed',
                    'pointer-events': 'none'
                }).attr('placeholder', 'Deklarasi').val(data[3]);

                function formatRupiah(angka) {
                    return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                }
                $('#jumlah-' + currentRowCount)
                    .css('cursor', 'not-allowed')
                    .attr('placeholder', 'Deklarasi')
                    .val(formatRupiah(data[8]));
                $('#hidden_jumlah' + currentRowCount).attr('placeholder', 'Deklarasi').val(data[8]);
                $('.jumlah-' + currentRowCount)
                    .css('cursor', 'not-allowed')
                    .attr('placeholder', 'Deklarasi')
                    .val(formatRupiah(data[8]));
                $('.hidden_jumlah' + currentRowCount).attr('placeholder', 'Deklarasi').val(data[8]);

                $("#form").validate().settings.rules[`pemakaian[${currentRowCount}]`] = {
                    required: false
                };
                $("#form").validate().settings.rules[`tgl_nota[${currentRowCount}]`] = {
                    required: false
                };
                $("#form").validate().settings.rules[`jml[${currentRowCount}]`] = {
                    required: false
                };
            }

            // Sembunyikan baris yang diklik setelah data dipilih
            $(this).hide();

            // Tutup modal setelah data dipilih
            $('#deklarasiModal').modal('hide');
        });
    });


    // Deklarasi Modal Read
    $(document).ready(function() {
        var table = $('#deklarasi-table-read').DataTable({
            "responsive": false,
            "scrollX": true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('datadeklarasi_pu/get_list') ?>",
                "type": "POST"
            },
            "columnDefs": [{
                    "targets": [2, 3, 4, 6],
                    "className": 'dt-head-nowrap'
                },
                {
                    "targets": [1, 3],
                    "className": 'dt-body-nowrap'
                },
                {
                    "targets": [0, 1],
                    "orderable": false,
                },
            ]
        });
    });

    // $('#tgl_pengajuan').datepicker({
    //     dateFormat: 'dd-mm-yy',
    //     // minDate: new Date(),

    //     // MENGENERATE KODE PREPAYMENT SETELAH PILIH TANGGAL
    //     onSelect: function(dateText) {
    //         var date = $('#tgl_pengajuan').val();
    //         var id = dateText;
    //         $('#tgl_pengajuan').removeClass('is-invalid');

    //         if ($("#tgl_pengajuan-error").length) {
    //             $("#tgl_pengajuan-error").remove();
    //         }
    //         $.ajax({
    //             url: "<?php echo site_url('reimbust_pu/generate_kode') ?>",
    //             type: "POST",
    //             data: {
    //                 "date": dateText
    //             },
    //             dataType: "JSON",
    //             success: function(data) {
    //                 $('#kode_reimbust').val(data.toUpperCase());
    //                 $('#kode').val(data);
    //             },
    //             error: function(error) {
    //                 alert("error" + error);
    //             }
    //         });
    //     }
    // });

    $(document).ready(function() {
        var id = $('#id').val();
        var aksi = $('#aksi').val();
        var kode = $('#kode').val();
        var sifat_pelaporan = $('#sifat_pelaporan').val();
        let inputCount = 0;
        let deletedRows = [];

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
                    <td><input type="text" class="form-control" name="pemakaian[${rowCount}]" value="" placeholder="Pemakaian ${rowCount}"  autocomplete="off" id="pemakaian${rowCount}"></td>
                    <td>
                        <input type="text" class="form-control tgl_nota" name="tgl_nota[${rowCount}]" id="tgl_nota_${rowCount}" style="cursor: pointer" autocomplete="off" placeholder="Tanggal Nota ${rowCount}">
                    </td>
                    <td>
                        <input type="text" class="form-control" id="jumlah-${rowCount}" placeholder="Jumlah ${rowCount}" name="jml[${rowCount}]" autocomplete="off">
                        <input type="hidden" id="hidden_jumlah${rowCount}" name="jumlah[${rowCount}]" value="">
                    </td>
                    <td style="padding: 12px 12px 5px !important" id="kwitansi-upload${rowCount}">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="kwitansi[${rowCount}]" id="inputGroupFile01${rowCount}" aria-describedby="inputGroupFileAddon01">
                            <label class="custom-file-label" for="inputGroupFile01" id="upload${rowCount}">Upload..</label>
                            <span class="kwitansi-label">Max Size : 3MB</span>
                        </div>
                    </td>
                    <td width="150" style="padding: 15px 10px">
                        <div class="btn btn-primary btn-lg btn-block btn-sm" data-toggle="modal" data-target="#deklarasiModal" data-id="${rowCount}" id="deklarasi-modal${rowCount}">Deklarasi</div>
                        <input type="hidden" class="form-control" id="deklarasi${rowCount}" placeholder="Deklarasi ${rowCount}" name="deklarasi[${rowCount}]" autocomplete="off">
                    </td>
                    <td><span class="btn delete-btn btn-danger" data-id="${rowCount}">Delete</span></td>
                </tr>
                `;
            $('#input-container').append(row);
            // Tambahkan format ke input jumlah yang baru
            formatJumlahInput(`#jumlah-${rowCount}`);
            updateSubmitButtonState(); // Perbarui status tombol submit
            checkDeleteButtonState(); // Cek tombol delete setelah baris ditambahkan

            $(`#deklarasi-modal${rowCount}`).on('click', function() {
                var rowId = $(this).data('id');
            });

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

            $(document).ready(function() {
                // Event listener untuk input dengan ID yang dimulai dengan 'deklarasi'
                $(document).on('input change', '[id^=deklarasi]', function() {
                    // Cek jika input memiliki value
                    if ($(this).val().trim() !== '') {
                        // Disable semua input kecuali input dengan ID 'deklarasi'
                        $(this).closest('tr').find('input').not(this).prop('disabled', true);
                    } else {
                        // Enable kembali semua input jika value dihapus
                        $(this).closest('tr').find('input').prop('disabled', false);
                    }
                });
            });

            // $(document).ready(function() {
            //     $('.custom-file-input').on('change', function() {
            //         if ($(this).val()) {
            //             $(`#deklarasi-modal${rowCount}`).text('tes');
            //         } else {
            //             $(`#deklarasi${rowCount}`).css('display', 'block');
            //         }
            //     });
            // });

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
                const jmlValue = $(this).find('input[name^="jml"]').val();
                const jumlahValue = $(this).find('input[name^="jumlah"]').val();
                const kwitansiValue = $(this).find('input[name^="kwitansi"]').val();
                const kwitansiImageValue = $(this).find('#kwitansi_image').val();
                const deklarasiValue = $(this).find('input[name^="deklarasi"]').val();

                $(this).attr('id', `row-${newRowNumber}`);
                $(this).find('.row-number').text(newRowNumber);
                $(this).find('input[name^="detail_id"]').attr('name', `detail_id[${newRowNumber}]`).attr('placeholder', `detail_id ${newRowNumber}`).val(detailIdValue);
                $(this).find('input[name^="pemakaian"]').attr('name', `pemakaian[${newRowNumber}]`).attr('placeholder', `Pemakaian ${newRowNumber}`).val(pemakaianValue);
                $(this).find('input[name^="tgl_nota"]').attr('name', `tgl_nota[${newRowNumber}]`).attr('placeholder', `Tanggal Nota ${newRowNumber}`).attr('id', `tgl_nota_${newRowNumber}`).val(tgl_notaValue);
                $(this).find('input[name^="jml"]').attr('name', `jml[${newRowNumber}]`).attr('placeholder', `Jumlah ${newRowNumber}`).val(jmlValue);
                $(this).find('input[name^="jumlah"]').attr('name', `jumlah[${newRowNumber}]`).attr('placeholder', `Jumlah ${newRowNumber}`).val(jumlahValue);
                $(this).find('input[name^="kwitansi"]').attr('name', `kwitansi[${newRowNumber}]`).attr('placeholder', `Input ${newRowNumber}`).val(kwitansiValue);
                $(this).find('#kwitansi_image').attr('name', `kwitansi_image[${newRowNumber}]`).attr('placeholder', `Input ${newRowNumber}`).val(kwitansiImageValue);
                $(this).find('input[name^="deklarasi"]').attr('name', `deklarasi[${newRowNumber}]`).attr('placeholder', `Input ${newRowNumber}`).val(deklarasiValue);
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
                $('.aksi').prop('disabled', true); // Disable submit button
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

        $('#sifat_pelaporan').on('change', function() {
            var sifatPelaporan = $(this).val();

            if (sifatPelaporan == 'Reimbust') {
                // $('#tgl_pengajuan').val('');
                // $('#kode_reimbust').val('');
                // $('#nama').val('');
                // $('#departemen').val('');
                // $('#departemenPrepayment').val('');
                // $('#jabatan').val('');
                $('#tujuan').val('');
                $('#jumlah_prepayment').val('');
                $('#kode_prepayment_input').val('');
            } else if (sifatPelaporan != 'Reimbust' && sifatPelaporan != 'Pelaporan') {
                $('#tgl_pengajuan').val('');
                $('#kode_reimbust').val('');
                // $('#nama').val('');
                // $('#departemen').val('');
                // $('#departemenPrepayment').val('');
                // $('#jabatan').val('');
                $('#tujuan').val('');
                $('#jumlah_prepayment').val('');
            }

        });

        $('#sifat_pelaporan').on('input', function() {
            var sifatPelaporan = $(this).val();
            handleSifatPelaporanChange(sifatPelaporan);
        });

        // Event listener untuk perubahan pada select "sifat_pelaporan"
        function handleSifatPelaporanChange(sifatPelaporan) {
            if (aksi == 'add') {
                if (sifatPelaporan == 'Reimbust') {
                    $('#pelaporan_button').css('display', 'none');
                    $('#tgl_pengajuan').prop({
                        'disabled': false,
                        'readonly': false
                    }).css('cursor', 'pointer');
                    $('#tgl_pengajuan').css('pointer-events', 'auto');
                    // $('#nama').prop({
                    //     'disabled': false,
                    //     'readonly': false
                    // }).css('cursor', 'auto');
                    // $('#departemen').prop('disabled', false).css({
                    //     'cursor': 'pointer',
                    //     'display': 'block'
                    // });
                    // $('#departemenPrepayment').prop('disabled', true).css('display', 'none');
                    // $('#jabatan').prop({
                    //     'disabled': false,
                    //     'readonly': false
                    // }).css('cursor', 'auto');
                    $('#tujuan').prop({
                        'disabled': false,
                        'readonly': false
                    }).css('cursor', 'auto');
                    $('#status').prop({
                        'disabled': false,
                        'readonly': false
                    }).css('cursor', 'pointer');
                    $('#jumlah_prepayment').prop({
                        'disabled': false,
                        'readonly': true
                    }).css('cursor', 'not-allowed').val('0');
                    $('#hidden_jumlah_prepayment').val('0');
                    $('#kode_prepayment').css({
                        'display': 'none'
                    });
                } else if (sifatPelaporan == 'Pelaporan') {
                    $('#pelaporan_button').css('display', 'inline-block');
                    $('#parent_sifat_pelaporan').css('display', 'flex');
                    $('#tgl_pengajuan').prop({
                        'disabled': false,
                        'readonly': false
                    }).css('cursor', 'pointer');
                    $('#tgl_pengajuan').css('pointer-events', 'auto');
                    // $('#nama').prop({
                    //     'disabled': false,
                    //     'readonly': true
                    // }).css('cursor', 'not-allowed');
                    // $('#departemen').prop({
                    //     'disabled': false,
                    //     'readonly': true
                    // }).css('display', 'none');
                    // $('#departemenPrepayment').prop({
                    //     'disabled': false,
                    //     'readonly': true
                    // }).css({
                    //     'display': 'block',
                    //     'cursor': 'not-allowed'
                    // });
                    // $('#jabatan').prop({
                    //     'disabled': false,
                    //     'readonly': true
                    // }).css('cursor', 'not-allowed');
                    $('#tujuan').prop({
                        'disabled': false,
                        'readonly': true
                    }).css('cursor', 'not-allowed');
                    $('#kode_prepayment').css({
                        'display': 'flex'
                    });
                    // $('#status').prop({
                    //     'disabled': false,
                    //     'readonly': true
                    // }).css('cursor', 'pointer');
                    $('#jumlah_prepayment').prop({
                        'disabled': false,
                        'readonly': true
                    }).css('cursor', 'not-allowed');
                } else {
                    $('#pelaporan_button').css('display', 'none');
                    $('#parent_sifat_pelaporan').css('display', 'inline-block');
                    // $('#nama').prop('disabled', true).css('cursor', 'not-allowed');
                    // $('#departemen').prop('disabled', true).css({
                    //     'cursor': 'not-allowed',
                    //     'display': 'block'
                    // });
                    // $('#departemenPrepayment').prop('disabled', true).css('display', 'none');
                    // $('#jabatan').prop('disabled', true).css('cursor', 'not-allowed');
                    $('#tgl_pengajuan').prop('disabled', true).css('cursor', 'not-allowed');
                    $('#tujuan').prop('disabled', true).css('cursor', 'not-allowed');
                    $('#status').prop('disabled', true).css('cursor', 'not-allowed');
                    $('#jumlah_prepayment').prop('disabled', true).css('cursor', 'not-allowed');
                    $('#kode_prepayment').css({
                        'display': 'none'
                    });
                }
            } else if (aksi == 'update') {
                if (sifatPelaporan == 'Reimbust') {
                    $('#sifat_pelaporan').prop('readonly', true).css({
                        'background-color': '#EAECF4',
                        'pointer-events': 'none',
                        'cursor': 'not-allowed'
                    });
                    $('#pelaporan_button').css('display', 'none');
                    $('#tgl_pengajuan').prop({
                        'disabled': false,
                        'readonly': false
                    }).css('cursor', 'pointer');
                    $('#tgl_pengajuan').css('pointer-events', 'auto');
                    // $('#nama').prop('readonly', false).css('cursor', 'auto');
                    // $('#departemen').prop('disabled', false).css({
                    //     'display': 'block',
                    //     'cursor': 'pointer'
                    // });
                    // $('#departemenPrepayment').prop('disabled', true).css('display', 'none');
                    // $('#jabatan').prop('readonly', false).css('cursor', 'auto');
                    $('#tujuan').prop('readonly', false).css('cursor', 'auto');
                    $('#status').prop('readonly', false).css('cursor', 'pointer');
                    $('#jumlah_prepayment').prop({
                        'disabled': false,
                        'readonly': true
                    }).css('cursor', 'not-allowed').val('0');
                    $('#hidden_jumlah_prepayment').val('0');
                    $('#kode_prepayment').css({
                        'display': 'none'
                    });
                } else if (sifatPelaporan == 'Pelaporan') {
                    $('#sifat_pelaporan').prop('readonly', true).css({
                        'background-color': '#EAECF4',
                        'pointer-events': 'none',
                        'cursor': 'not-allowed'
                    });
                    $('#parent_sifat_pelaporan').css('display', 'flex');
                    $('#pelaporan_button').css('display', 'inline-block');
                    $('#tgl_pengajuan').prop({
                        'disabled': false,
                        'readonly': true
                    }).css('cursor', 'not-allowed');
                    $('#tgl_pengajuan').css('pointer-events', 'none');
                    // $('#nama').prop({
                    //     'disabled': false,
                    //     'readonly': true
                    // }).css('cursor', 'not-allowed');
                    // $('#departemen').prop({
                    //     'disabled': false,
                    //     'readonly': true
                    // }).css('display', 'none');
                    // $('#departemenPrepayment').prop({
                    //     'disabled': false,
                    //     'readonly': true
                    // }).css({
                    //     'display': 'block',
                    //     'cursor': 'not-allowed'
                    // });
                    // $('#jabatan').prop({
                    //     'disabled': false,
                    //     'readonly': true
                    // }).css('cursor', 'not-allowed');
                    $('#tujuan').prop({
                        'disabled': false,
                        'readonly': true
                    }).css('cursor', 'not-allowed');
                    // $('#status').prop({
                    //     'disabled': false,
                    //     'readonly': true
                    // }).css('cursor', 'pointer');
                    $('#jumlah_prepayment').prop({
                        'disabled': false,
                        'readonly': true
                    }).css('cursor', 'not-allowed');
                    $('#kode_prepayment').css({
                        'display': 'flex'
                    });
                } else {
                    // $('#nama').prop('readonly', true).css('cursor', 'not-allowed');
                    // $('#departemen').prop('readonly', true).css('cursor', 'not-allowed');
                    // $('#jabatan').prop('readonly', true).css('cursor', 'not-allowed');
                    $('#tgl_pengajuan').prop('readonly', true).css('cursor', 'not-allowed');
                    $('#tujuan').prop('readonly', true).css('cursor', 'not-allowed');
                    $('#status').prop('readonly', true).css('cursor', 'not-allowed');
                    $('#jumlah_prepayment').prop('readonly', true).css('cursor', 'not-allowed');
                }
            } else if (aksi == 'read') {
                if (sifatPelaporan == 'Reimbust') {
                    $('#pelaporan_button').css('display', 'none');
                    $('input').prop('disabled', true).css('cursor', 'not-allowed');
                    $('select').prop('disabled', true).css('cursor', 'not-allowed');
                    $('textarea').prop('disabled', true).css('cursor', 'not-allowed');
                    $('#pelaporan_button').prop('disabled', true).css('display', 'none');
                } else if (sifatPelaporan == 'pelaporan') {
                    $('#pelaporan_button').css('display', 'none');
                    $('input').prop('disabled', true).css('cursor', 'not-allowed');
                    $('select').prop('disabled', true).css('cursor', 'not-allowed');
                    $('textarea').prop('disabled', true).css('cursor', 'not-allowed');
                    $('#pelaporan_button').prop('disabled', true).css('display', 'none');
                } else {
                    $('#pelaporan_button').css('display', 'none');
                    // $('#departemen').prop('disabled', true).css('display', 'none');
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
                url: "<?= site_url('reimbust_pu/edit_data') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    moment.locale('id')
                    // Set nilai untuk setiap field dari data master    
                    $('#sifat_pelaporan').val(data['master']['sifat_pelaporan']);
                    $('#id').val(data['master']['id']);
                    $('#kode_reimbust').val(data['master']['kode_reimbust']).attr('readonly', true);
                    // $('#nama').val(data['master']['nama']);
                    // $('#jabatan').val(data['master']['jabatan']);
                    // $('#departemen').val(data['master']['departemen']);
                    // $('#departemenPrepayment').val(data['master']['departemen']);
                    $('#tgl_pengajuan').val(moment(data['master']['tgl_pengajuan']).format('DD-MM-YYYY'));
                    $('#tujuan').val(data['master']['tujuan']);
                    $('#status').val(data['master']['status']);
                    $('#jumlah_prepayment').val(data['master']['jumlah_prepayment'].replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
                    $('#hidden_jumlah_prepayment').val(data['master']['jumlah_prepayment']);
                    $('#kode_prepayment_input').val(data['master']['kode_prepayment']);


                    if (aksi == 'update') {
                        //APPEND DATA TRANSAKSI DETAIL REIMBUST
                        $(data['transaksi']).each(function(index) {
                            // Nilai jumlah diformat menggunakan pemisah ribuan sebelum dimasukkan ke dalam elemen input.
                            const jumlahFormatted = data['transaksi'][index]['jumlah'].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                            const tglNotaFormatted = moment(data['transaksi'][index]['tgl_nota']).format('DD-MM-YYYY');

                            // Cek apakah deklarasi ada datanya
                            const isDeklarasiFilled = data['transaksi'][index]['deklarasi'] ? true : false;

                            // Append Dari Form UPDATE
                            const row = `
                                <tr id="row-${index + 1}">
                                    <td class="row-number">${index + 1}</td>
                                    <td>
                                        <input type="text" class="form-control" name="pemakaian[${index + 1}]" value="${data['transaksi'][index]['pemakaian']}" id="pemakaian${index + 1}" autocomplete="off" placeholder="${data['transaksi'][index]['pemakaian'] ? data['transaksi'][index]['pemakaian'] : 'Deklarasi'}">
                                        
                                        <input type="hidden" id="hidden_reimbust_id${index}" name="reimbust_id" value="${data['master']['id']}">
                                        <input type="hidden" id="hidden_detail_id${index}" name="detail_id[${index + 1}]" value="${data['transaksi'][index]['id']}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control tgl_nota ${isDeklarasiFilled ? 'not-allowed' : ''}" name="tgl_nota[${index + 1}]" id="tgl_nota_${index + 1}" 
                                            style="cursor: ${isDeklarasiFilled ? 'not-allowed' : 'pointer'}; pointer-events: ${isDeklarasiFilled ? 'none' : 'auto'}" 
                                            autocomplete="off" value="${tglNotaFormatted}" ${isDeklarasiFilled ? 'readonly' : ''}>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control jumlah-${index + 1}" id="jumlah-${index}" value="${jumlahFormatted}" name="jml[${index + 1}]" autocomplete="off">
                                        <input class="hidden_jumlah${index + 1}" type="hidden" id="hidden_jumlah${index}" name="jumlah[${index + 1}]" value="${data['transaksi'][index]['jumlah']}">
                                    </td>
                                    <td id="kwitansi-upload${index + 1}">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="kwitansi[${index + 1}]" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">
                                            <label class="custom-file-label" for="inputGroupFile01" id="upload${index + 1}">${data['transaksi'][index]['kwitansi'] ? data['transaksi'][index]['kwitansi'] : 'Deklarasi'}</label>
                                        </div>
                                        <input type="hidden" class="form-control kwitansi_image${index + 1}" id="kwitansi_image" name="kwitansi_image[${index + 1}]" value="${data['transaksi'][index]['kwitansi'] ? data['transaksi'][index]['kwitansi'] : ''}">
                                        <span class="kwitansi-label">Max Size : 3MB</span>
                                    </td>
                                    <td width="125" style="padding: 16px 10px !important">
                                        <div class="btn btn-primary btn-lg btn-block btn-sm" 
                                            data-toggle="modal" 
                                            data-target="#deklarasiModal" 
                                            data-id="${index + 1}" 
                                            id="deklarasi-modal${index + 1}">
                                            ${data['transaksi'][index]['deklarasi'] ? data['transaksi'][index]['deklarasi'] : 'Deklarasi'}
                                        </div>
                                        <input type="hidden" class="form-control" id="deklarasi${index + 1}" placeholder="Deklarasi ${index + 1}" name="deklarasi[${index + 1}]" autocomplete="off" value="${data['transaksi'][index]['deklarasi']}">
                                    </td>
                                    <td><span class="btn delete-btn btn-danger" data-id="${index + 1}">Delete</span></td>
                                </tr>
                            `;
                            $('#input-container').append(row);
                            // Tambahkan format ke input jumlah yang baru
                            formatJumlahInput(`#jumlah-${index}`);

                            //VALIDASI ROW YANG TELAH DI APPEND
                            $("#form").validate().settings.rules[`pemakaian[${index + 1}]`] = {
                                required: true
                            };
                            $("#form").validate().settings.rules[`tgl_nota[${index + 1}]`] = {
                                required: true
                            };
                            $("#form").validate().settings.rules[`jml[${index + 1}]`] = {
                                required: true
                            };
                            rowCount = index + 1;

                            $(document).ready(function() {
                                // Cek nilai input dan hapus value serta tambahkan placeholder jika perlu
                                $('input[id^="jumlah-"]').each(function() {
                                    var $input = $(this);
                                    var value = $input.val();

                                    if (value == '0') {
                                        $input.val(''); // Hapus nilai input
                                        $input.attr('placeholder', 'Deklarasi'); // Tambahkan placeholder
                                    }
                                });
                            });

                            $(document).ready(function() {
                                // Cek nilai label dan lakukan tindakan jika nilainya adalah "null"
                                $('label[id^="upload"]').each(function() {
                                    var $label = $(this);
                                    var text = $label.text().trim(); // Ambil teks dari label

                                    if (text === 'null') {
                                        $label.text('Deklarasi'); // Hapus teks label
                                    }
                                });
                            });

                            $(document).ready(function() {
                                // Iterasi setiap baris transaksi
                                $('tr[id^="row-"]').each(function() {
                                    var index = $(this).attr('id').replace('row-', ''); // Ambil indeks dari ID elemen
                                    var deklarasiValue = $('#deklarasi' + index).val(); // Ambil nilai deklarasi

                                    // Jika deklarasi kosong, buat input lainnya readonly
                                    if (deklarasiValue !== '') {
                                        $(this).find('input[type="text"]').attr('readonly', true); // Buat semua input teks dalam baris ini readonly
                                        $(this).find('.custom-file-input').attr('disabled', true); // Disable input file
                                        $(this).find('.btn-primary').attr('disabled', true); // Disable tombol modal deklarasi
                                    }
                                });
                            });

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
                        });
                    }

                    if (aksi == 'read') {
                        //APPEND DATA TRANSAKSI DETAIL REIMBUST
                        $(data['transaksi']).each(function(index) {
                            //Nilai jumlah diformat menggunakan pemisah ribuan sebelum dimasukkan ke dalam elemen input.
                            $('.aksi').hide();
                            $('#add-row').hide();
                            $('#action').hide();
                            const jumlahFormatted = data['transaksi'][index]['jumlah'].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                            const tglNotaFormatted = moment(data['transaksi'][index]['tgl_nota']).format('DD-MM-YYYY');
                            // Append Dari Form UPDATE
                            const row = `
                                <tr id="row-${index + 1}">
                                    <td class="row-number">${index + 1}</td>
                                    <td>
                                        <input type="text" class="form-control" name="pemakaian[${index + 1}]" value="${data['transaksi'][index]['pemakaian']}" autocomplete="off" disabled style="cursor: not-allowed" placeholder="${data['transaksi'][index]['pemakaian'] ? data['transaksi'][index]['pemakaian'] : 'Deklarasi'}">

                                        <input type="hidden" id="hidden_reimbust_id${index}" name="reimbust_id" value="${data['master']['id']}">
                                        <input type="hidden" id="hidden_detail_id${index}" name="detail_id[${index + 1}]" value="${data['transaksi'][index]['id']}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control tgl_nota" name="tgl_nota[${index + 1}]" style="cursor: pointer" autocomplete="off" value="${tglNotaFormatted}" disabled style="cursor: not-allowed">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" id="jumlah-${index}" value="${jumlahFormatted}" name="jml[${index + 1}]" autocomplete="off" disabled style="cursor: not-allowed">
                                        <input type="hidden" id="hidden_jumlah${index}" name="jumlah[${index + 1}]" value="${data['transaksi'][index]['jumlah']}">
                                    </td>
                                    <td width="150" style="padding: 15px 10px">
                                        <div class="btn btn-primary btn-lg btn-block btn-sm openModal" data-kwitansi="${data['transaksi'][index]['kwitansi']}">Lihat Foto</div>
                                    </td>
                                    <td width="150" style="padding: 15px 10px">
                                        <a href="<?= base_url() ?>datadeklarasi_pu/read_form/25" class="btn btn-primary btn-lg btn-block btn-sm" 
                                            data-id="${index + 1}"
                                            data-deklarasi="${data['transaksi'][index]['deklarasi']}"
                                            id="deklarasi-modal${index + 1}">
                                            ${data['transaksi'][index]['deklarasi'] ? data['transaksi'][index]['deklarasi'] : 'Deklarasi'}
                                        </a>
                                        <input type="hidden" class="form-control" id="deklarasi${index + 1}" placeholder="Deklarasi ${index + 1}" name="deklarasi[${index + 1}]" autocomplete="off" value="${data['transaksi'][index]['deklarasi']}">
                                    </td>
                                    <td><span class="btn delete-btn btn-danger" data-id="${index + 1}" style="display: none">Delete</span></td>
                                </tr>
                                `;
                            $('#input-container').append(row);
                            // Tambahkan format ke input jumlah yang baru
                            formatJumlahInput(`#jumlah-${index}`);
                            rowCount = index + 1;
                        });

                        $(document).ready(function() {
                            // Mendapatkan modal
                            var modal = $('#myModal');

                            // Mendapatkan gambar modal dan caption
                            var modalImg = $("#img01");
                            var captionText = $("#caption");

                            // Ketika button diklik, tampilkan modal dengan gambar
                            $('.openModal').on('click', function() {
                                const kwitansi = $(this).data('kwitansi');

                                if (!kwitansi) {
                                    $(this).text('Deklarasi');
                                    $(this).css({
                                        'background-color': '#EAECF4',
                                        'color': '#888',
                                        'border-color': '#EAECF4',
                                        'cursor': 'not-allowed'
                                    });
                                } else {
                                    // Jika data kwitansi ada, lanjutkan dengan membuka modal
                                    modal.css("display", "block");
                                    modalImg.attr('src', `<?= base_url() ?>/assets/backend/img/reimbust/kwitansi/${kwitansi}`);
                                    // captionText.text('Deskripsi gambar Anda di sini'); // Ubah dengan deskripsi gambar
                                }
                            });

                            // Ketika tombol close diklik, sembunyikan modal
                            $('.close').on('click', function() {
                                modal.css("display", "none");
                            });
                        });

                        $(document).ready(function() {
                            // Cek nilai input dan hapus value serta tambahkan placeholder jika perlu
                            $('input[id^="jumlah-"]').each(function() {
                                var $input = $(this);
                                var value = $input.val();

                                if (value == '0') {
                                    $input.val(''); // Hapus nilai input
                                    $input.attr('placeholder', 'Deklarasi'); // Tambahkan placeholder
                                }
                            });
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

            // $.ajax({
            //     url: "<?php echo site_url('reimbust_pu/read_detail/') ?>" + id,
            //     type: "GET",
            //     dataType: "JSON",
            //     success: function(data) {
            //         $(data).each(function(index) {
            //             //Nilai nominal diformat menggunakan pemisah ribuan sebelum dimasukkan ke dalam elemen input.
            //             const nominalReadFormatted = data[index]['nominal'].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            //             const row = `
            //             <tr id="row-${index}">
            //                 <td class="row-number">${index + 1}</td>
            //                 <td><input readonly type="text" class="form-control" name="sifat_pelaporan[${index}]" value="${data[index]['sifat_pelaporan']}"></td>
            //             </tr>
            //             `;
            //             $('#input-container').append(row);
            //         });
            //     }
            // });
        }

        $("#form").submit(function(e) {
            e.preventDefault();
            var $form = $(this);
            if (!$form.valid()) return false;

            var url;
            if (id == 0) {
                url = "<?php echo site_url('reimbust_pu/add') ?>";
            } else {
                url = "<?php echo site_url('reimbust_pu/update') ?>";
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
                            location.href = "<?= base_url('reimbust_pu') ?>";
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


        $("#form").validate({
            rules: {
                // nama: {
                //     required: true,
                // },
                // departemen: {
                //     required: true,
                // },
                // jabatan: {
                //     required: true,
                // },
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
                // nama: {
                //     required: "Nama is required",
                // },
                // departemen: {
                //     required: "Departemen is required",
                // },
                // jabatan: {
                //     required: "Jabatan is required",
                // },
                sifat_pelaporan: {
                    required: "Pilih Sifat Pelaporan!",
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