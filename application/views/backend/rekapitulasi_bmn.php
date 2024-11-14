<style>
    #appFilter {
        border: 1px solid #ccc;
        padding: 5px;
        border-radius: 4px;
    }

    .tgl-header {
        display: flex;
    }

    .tgl-awal-text,
    .tgl-akhir-text {
        width: 180px;
    }

    .export-excel {
        margin-left: 1rem;
    }

    .labelPengeluaran {
        display: inline-block;
        /* Agar label tetap satu baris dengan konten */
        width: 100px;
        /* Atur lebar yang sama untuk setiap label */
        text-align: left;
        /* Ratakan teks ke kanan untuk sejajar dengan tanda ':' */
        margin-right: 5px;
        /* Tambah sedikit jarak antara label dan konten */
    }

    .contentPengeluaran {
        display: inline-block;
        text-align: right;
        margin-left: 10px;
    }

    @media (max-width: 1000px) {
        .tgl-header {
            display: inline-block;
        }

        .tgl-akhir {
            margin: 1rem 0;
        }

        .export-excel {
            margin-left: 0;
            margin-top: 1rem;
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
                <div class="card-header py-3 justify-content-start align-items-center tgl-header">
                    <div class="d-flex align-items-center mr-3 w-30 tgl-awal">
                        <label for="tgl_awal" class="mr-2 mb-0 tgl-awal-text">Tanggal Awal:</label>
                        <div class="input-group date">
                            <input type="text" class="form-control" name="tgl_awal" id="tgl_awal" style="width: 70px;" placeholder="DD-MM-YYYY" autocomplete="off" readonly />
                            <div class="input-group-append">
                                <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mr-3 w-30 tgl-akhir">
                        <label for="tgl_akhir" class="mr-2 mb-0 tgl-akhir-text">Tanggal Akhir:</label>
                        <div class="input-group date">
                            <input type="text" class="form-control" name="tgl_akhir" id="tgl_akhir" style="width: 70px;" placeholder="DD-MM-YYYY" autocomplete="off" readonly />
                            <div class="input-group-append">
                                <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-item-center">
                        <button class="btn btn-primary" id="tgl_btn" type="button">FILTER</button>
                    </div>
                    <div class="d-flex align-item-center export-excel">
                        <a class="btn btn-success" id="btn-export-excel">
                            <i class="fa fa-file-excel" style="margin-right: 6px"></i>Export to Excel
                        </a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div>
                            <strong class="labelPengeluaran">Prepayment</strong> : <div style="display: inline-block; width: 135px; text-align: right"><strong class="contentPengeluaran" id="totalPrepayment"></strong></div>
                        </div>
                        <div>
                            <strong class="labelPengeluaran">Reimbust</strong> : <div style="display: inline-block; width: 135px; text-align: right"><strong class="contentPengeluaran" id="totalReimbust"></strong></div>
                        </div>
                        <div>
                            <strong class="labelPengeluaran">Pelaporan</strong> : <div style="display: inline-block; width: 135px; text-align: right"><strong class="contentPengeluaran" id="totalPelaporan"></strong></div>
                        </div>
                        <div>
                            <strong class="labelPengeluaran">Pengeluaran</strong> : <div style="display: inline-block; width: 135px; text-align: right"><strong class="contentPengeluaran" id="total"></strong></div>
                        </div>
                    </div>
                </div>

                <!-- NAV TABS -->
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" id="pelaporanTab" href="#" data-tab="pelaporan">Pelaporan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="reimbustTab" href="#" data-tab="reimbust">Reimbust</a>
                    </li>
                </ul>

                <div class="card-body">
                    <table id="table" class="table table-bordered table-striped">
                        <thead id="table-header">
                            <!-- GENERATE THEAD -->
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot id="table-footer">
                            <!-- GENERATE TFOOTER -->
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>

<script type="text/javascript">
    var table;

    // METHOD POST MENAMPILKAN DATA KE DATA TABLE
    $(document).ready(function() {

        var activeTab = $('.nav-link.active').data('tab');
        // $('#labelPengeluaran').text(activeTab.charAt(0).toUpperCase() + activeTab.slice(1));

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
                // Mengatur minimal tanggal yang diperbolehkan untuk tgl_akhir
                var minimumDate = $('#tgl_awal').datepicker('getDate');
                // localStorage.setItem('minDate', minimumDate.toISOString());
                $('#tgl_akhir').datepicker('option', 'minDate', minimumDate);
            }
        });

        // Inisialisasi Datepicker untuk Tanggal Akhir
        $('#tgl_akhir').datepicker({
            dateFormat: 'dd-mm-yy',
            onSelect: function(selectedDate) {
                // Mengatur maksimal tanggal yang diperbolehkan untuk tgl_awal
                var maximumDate = $('#tgl_akhir').datepicker('getDate');
                // localStorage.setItem('maxDate', maximumDate.toISOString());
                $('#tgl_awal').datepicker('option', 'maxDate', maximumDate);
            }
        });

        function formatTanggal(dateStr) {
            const [day, month, year] = dateStr.split("-");
            return `${year}-${month}-${day}`;
        }

        function initializeDataTable() {
            table = $('#table').DataTable({
                "destroy": true, // Destroy the previous DataTable instance
                "responsive": true,
                "scrollX": true,
                "processing": true,
                "serverSide": true,
                "order": [],
                "ajax": {
                    "url": "<?php echo site_url('rekapitulasi_bmn/get_list') ?>",
                    "type": "POST",
                    "data": function(d) {
                        let tgl_awal = $('#tgl_awal').val();
                        let tgl_akhir = $('#tgl_akhir').val();

                        // Konversi format tanggal jika diperlukan
                        if (tgl_awal && tgl_akhir) {
                            tgl_awal = formatTanggal(tgl_awal);
                            tgl_akhir = formatTanggal(tgl_akhir);
                        }

                        d.awal = tgl_awal;
                        d.akhir = tgl_akhir;
                        d.tab = $('.nav-tabs .nav-link.active').data('tab'); // Tambahkan parameter tab ke permintaan server
                    }
                },
                "columnDefs": [{
                        "targets": [1, 2],
                        "className": 'dt-head-nowrap'
                    },
                    {
                        "targets": [1, 5, 6],
                        "className": 'dt-body-nowrap'
                    }, {
                        "targets": [0],
                        "orderable": false,
                    },
                ],
                "drawCallback": function(settings) {
                    $.ajax({
                        "url": "<?php echo site_url('rekapitulasi_bmn/get_total') ?>",
                        "type": "POST",
                        "data": {
                            "awal": $('#tgl_awal').val(),
                            "akhir": $('#tgl_akhir').val(),
                            "tab": $('.nav-tabs .nav-link.active').data('tab')
                        },
                        success: function(response) {
                            var total = JSON.parse(response);
                            // console.log('Success logging data to second URL' + response);
                            // console.log(response);
                            $('#totalPrepayment').text('Rp. ' + parseInt(total.total_prepayment).toLocaleString('id-ID'));
                            $('#totalReimbust').text('Rp. ' + parseInt(total.total_reimbust).toLocaleString('id-ID'));
                            $('#totalPelaporan').text('Rp. ' + parseInt(total.total_pelaporan).toLocaleString('id-ID'));
                            $('#total').text('Rp. ' + parseInt(total.total_pengeluaran).toLocaleString('id-ID'));
                        },
                        error: function(error) {
                            console.log('Error logging data to second URL');
                        }
                    });
                }
            });
        }

        // Function to update the table header based on the active tab
        function updateTableHeader(tab) {
            var tableHeader = $('#table-header');
            var tableFooter = $('#table-footer');

            // Clear the current table header
            tableHeader.empty();

            // Define headers for each tab
            if (tab === 'pelaporan') {
                tableHeader.append(`
                <tr>
                    <th>No</th>
                    <th>Kode Prepayment</th>
                    <th>Kode Reimbust</th>
                    <th>Nama</th>
                    <th>Keterangan</th>
                    <th>Tanggal</th>
                    <th>Pengeluaran</th>
                </tr>
            `);
                tableFooter.append(`
                <tr>
                    <th>No</th>
                    <th>Kode Prepayment</th>
                    <th>Kode Reimbust</th>
                    <th>Nama</th>
                    <th>Keterangan</th>
                    <th>Tanggal</th>
                    <th>Pengeluaran</th>
                </tr>
            `);
            } else if (tab === 'reimbust') {
                tableHeader.append(`
                <tr>
                    <th>No</th>
                    <th>Kode Prepayment</th>
                    <th>Kode Reimbust</th>
                    <th>Nama</th>
                    <th>Keterangan</th>
                    <th>Tanggal</th>
                    <th>Pengeluaran</th>
                </tr>
            `);
                tableFooter.append(`
                <tr>
                    <th>No</th>
                    <th>Kode Prepayment</th>
                    <th>Kode Reimbust</th>
                    <th>Nama</th>
                    <th>Keterangan</th>
                    <th>Tanggal</th>
                    <th>Pengeluaran</th>
                </tr>
            `);
            }
            initializeDataTable();
        }

        // Initialize the table for the first time (pelaporan tab is active by default)
        updateTableHeader('pelaporan');

        // Event listener untuk nav tabs
        $('.nav-tabs a').on('click', function(e) {
            e.preventDefault();
            $('.nav-tabs a').removeClass('active'); // Hapus kelas aktif dari semua tab
            $(this).addClass('active'); // Tambahkan kelas aktif ke tab yang diklik

            // Get the active tab
            var activeTab = $(this).data('tab');
            // $('#labelPengeluaran').text(activeTab.charAt(0).toUpperCase() + activeTab.slice(1));

            // Update the table header based on the active tab
            updateTableHeader(activeTab);

            // Reload DataTables to reflect the new header
            table.ajax.reload();
        });
    });

    // Event listener untuk nav tabs
    $('#tgl_btn').on('click', function(e) {
        e.preventDefault();
        table.ajax.reload(); // Muat ulang data di DataTable saat tab berubah
    });

    // Event listener untuk nav tabs
    $('.nav-tabs a').on('click', function(e) {
        e.preventDefault();
        $('.nav-tabs a').removeClass('active'); // Hapus kelas aktif dari semua tab
        $(this).addClass('active'); // Tambahkan kelas aktif ke tab yang diklik

        table.ajax.reload(); // Muat ulang data di DataTable saat tab berubah
    });

    $('#btn-export-excel').click(function() {
        // Ambil nilai dari datepicker
        var tgl_awal = $('#tgl_awal').val();
        var tgl_akhir = $('#tgl_akhir').val();

        // // Arahkan ke URL controller untuk export Excel dengan parameter
        window.location.href = "<?= site_url('rekapitulasi_bmn/export_excel'); ?>?tgl_awal=" + tgl_awal + "&tgl_akhir=" + tgl_akhir;
    });
</script>