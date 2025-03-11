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

    .transaksi-filter {
        display: flex;
    }

    .transaksi-filter .col-custom {
        margin: 0 10px;
    }

    .transaksi-filter .tgl-awal,
    .transaksi-filter .tgl-akhir {
        background-color: #fff;
        cursor: pointer;
    }

    @media (max-width: 1400px) {

        .transaksi-filter .tgl-awal,
        .transaksi-filter .tgl-akhir {
            width: 150px;
        }
    }

    @media (max-width: 1060px) {
        .transaksi-filter {
            display: block;
        }
    }
</style>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $titleview ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <div class="transaksi-filter">
                        <div class="col-custom">
                            <label for="">Produk :</label>
                            <select id="kode_produk" class="form-control" style="cursor: pointer;">
                                <option value="all" selected>All</option>
                                <?php foreach ($produk as $data) : ?>
                                    <option value="<?= $data['kode_produk'] ?>"><?= $data['nama_produk'] ?> (<?= $data['kode_produk'] ?>)</option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="col-custom">
                            <label for="">Jenis Transaksi :</label>
                            <select id="jenis_transaksi" class="form-control" style="cursor: pointer;">
                                <option value="all" selected>All</option>
                                <option value="masuk">Masuk</option>
                                <option value="keluar">Keluar</option>
                            </select>
                        </div>
                        <div class="col-custom">
                            <label for="">Keterangan :</label>
                            <select id="keterangan" class="form-control" style="cursor: pointer;">
                                <option value="all" selected>All</option>
                                <option value="penjualan">Penjualan</option>
                                <option value="penambahan">Penambahan</option>
                                <option value="pengurangan">Pengurangan</option>
                            </select>
                        </div>
                        <div class="col-custom">
                            <label for="">Dari Tanggal :</label>
                            <div class="input-group date">
                                <input type="text" class="form-control tgl-awal" name="tgl_awal" id="tgl_awal" placeholder="DD-MM-YYYY" autocomplete="off" readonly />
                                <div class="input-group-append">
                                    <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-custom">
                            <label for="">Sampai Tanggal :</label>
                            <div class="input-group date">
                                <input type="text" class="form-control tgl-akhir" name="tgl_akhir" id="tgl_akhir" placeholder="DD-MM-YYYY" autocomplete="off" readonly />
                                <div class="input-group-append">
                                    <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Added padding for spacing -->
                    <div class="table-responsive">
                        <!-- Table wrapper -->
                        <table id="transaksi-table" class="table table-bordered table-striped display nowrap w-100 mb-4">
                            <!-- Added margin-bottom -->
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Produk</th>
                                    <th>Nama Produk</th>
                                    <th>Berat</th>
                                    <th>Jenis Transaksi</th>
                                    <th>Jumlah</th>
                                    <th>Keterangan</th>
                                    <th>Waktu Transaksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be populated by DataTables -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Produk</th>
                                    <th>Nama Produk</th>
                                    <th>Berat</th>
                                    <th>Jenis Transaksi</th>
                                    <th>Jumlah</th>
                                    <th>Keterangan</th>
                                    <th>Waktu Transaksi</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>

<script type="text/javascript">
    $('#tgl_awal').on('click', function() {
        // console.log('berhasil');
        $('#tgl_awal').datepicker('setDate', null);
    });

    $('#tgl_akhir').on('click', function() {
        $('#tgl_akhir').datepicker('setDate', null);
    });

    // Inisialisasi Datepicker untuk Tanggal Awal
    $('#tgl_awal').datepicker({
        dateFormat: 'dd-mm-yy',
        onSelect: function(selectedDate) {
            // var minimumDate = $('#tgl_awal').datepicker('getDate');
            // $('#tgl_akhir').datepicker('option', 'minDate', minimumDate);

            // Kirim AJAX ke server
            $.ajax({
                url: "<?php echo site_url('qbg_transaksi/get_list') ?>", // Ganti dengan endpoint yang sesuai
                type: 'POST',
                data: {
                    tgl_awal: selectedDate
                },
                success: function(response) {
                    console.log('Data berhasil dikirim:', response);
                    // Bisa tambahin aksi lain sesuai kebutuhan
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
            table.ajax.reload();
        }
    });


    // Inisialisasi Datepicker untuk Tanggal Akhir
    $('#tgl_akhir').datepicker({
        dateFormat: 'dd-mm-yy',
        onSelect: function(selectedDate) {
            // Mengatur maksimal tanggal yang diperbolehkan untuk tgl_awal
            // var maximumDate = $('#tgl_akhir').datepicker('getDate');
            // localStorage.setItem('maxDate', maximumDate.toISOString());
            // $('#tgl_awal').datepicker('option', 'maxDate', maximumDate);

            // Kirim AJAX ke server
            $.ajax({
                url: "<?php echo site_url('qbg_transaksi/get_list') ?>", // Ganti dengan endpoint yang sesuai
                type: 'POST',
                data: {
                    tgl_akhir: selectedDate
                },
                success: function(response) {
                    console.log('Data berhasil dikirim:', response);
                    // Bisa tambahin aksi lain sesuai kebutuhan
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
            table.ajax.reload();
        }
    });

    var table;
    $(document).ready(function() {
        table = $('#transaksi-table').DataTable({
            "responsive": true,
            "scrollX": true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('qbg_transaksi/get_list') ?>",
                "type": "POST",
                "data": function(d) {
                    d.kode_produk = $('#kode_produk').val(); // Tambahkan parameter status ke permintaan server
                    d.jenis_transaksi = $('#jenis_transaksi').val();
                    d.keterangan = $('#keterangan').val();
                    d.tgl_awal = $('#tgl_awal').val();
                    d.tgl_akhir = $('#tgl_akhir').val();
                }
            },
            "language": {
                "infoFiltered": ""
            },
            "columnDefs": [{
                    "targets": [], // Adjusted indices to match the number of columns
                    "className": 'dt-head-nowrap'
                },
                {
                    "targets": [],
                    "className": 'dt-body-nowrap'
                },
                {
                    "targets": [0], // Indices for non-orderable columns
                    "orderable": false,
                }
            ],
        });
    });

    // Cek apakah ada nilai filter yang tersimpan di localStorage = kode produk
    var savedKodeProduk = localStorage.getItem('kodeProdukStatus');
    if (savedKodeProduk) {
        $('#kode_produk').val(savedKodeProduk).change(); // Set filter dengan nilai yang tersimpan
    }

    // Simpan nilai filter ke localStorage setiap kali berubah
    $('#kode_produk').on('change', function() {
        localStorage.setItem('kodeProdukStatus', $(this).val());
        table.ajax.reload(); // Muat ulang DataTables dengan filter baru
    });

    // Cek apakah ada nilai filter yang tersimpan di localStorage = jenis transaksi
    var jenisTransaksi = localStorage.getItem('jenisTransaksiStatus');
    if (jenisTransaksi) {
        $('#jenis_transaksi').val(jenisTransaksi).change(); // Set filter dengan nilai yang tersimpan
    }

    // Simpan nilai filter ke localStorage setiap kali berubah
    $('#jenis_transaksi').on('change', function() {
        localStorage.setItem('jenisTransaksiStatus', $(this).val());
        table.ajax.reload(); // Muat ulang DataTables dengan filter baru
    });

    // Cek apakah ada nilai filter yang tersimpan di localStorage = keterangan
    var keterangan = localStorage.getItem('keteranganStatus');
    if (keterangan) {
        $('#keterangan').val(keterangan).change(); // Set filter dengan nilai yang tersimpan
    }

    // Simpan nilai filter ke localStorage setiap kali berubah
    $('#keterangan').on('change', function() {
        localStorage.setItem('keteranganStatus', $(this).val());
        table.ajax.reload(); // Muat ulang DataTables dengan filter baru
    });
</script>