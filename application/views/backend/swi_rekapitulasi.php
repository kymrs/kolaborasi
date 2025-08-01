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

    .labelPemasukan {
        display: inline-block;
        /* Agar label tetap satu baris dengan konten */
        width: 200px;
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

    .flexing {
        display: flex;
    }

    .flexing .left-side {
        margin-right: 30px;
    }

    .flexing .right-side,
    .flexing .left-side {
        border: 1px solid #000;
        padding: 10px;
        border-radius: 8px;
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

        .flexing {
            display: inline-block;
            font-size: 70%;
        }

        .flexing .left-side {
            margin-bottom: 20px;
        }

        .flexing .right-side,
        .flexing .left-side {
            width: 100%;
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
                        <div class="flexing">
                            <div class="left-side">
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
                            <div class="right-side">
                                <div>
                                    <strong class="labelPemasukan">Invoice(Lunas)</strong> : <div style="display: inline-block; width: 135px; text-align: right"><strong class="contentPengeluaran" id="invoiceLunas"></strong></div>
                                </div>
                                <div>
                                    <strong class="labelPemasukan">Invoice(Belum Lunas)</strong> : <div style="display: inline-block; width: 135px; text-align: right"><strong class="contentPengeluaran" id="invoiceBelumLunas"></strong></div>
                                </div>
                                <div>
                                    <strong class="labelPemasukan">Margin</strong> : <div style="display: inline-block; width: 135px; text-align: right"><strong class="contentPengeluaran" id="margin"></strong></div>
                                </div>
                            </div>
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
                    <li class="nav-item">
                        <a class="nav-link" id="invoiceTab" href="#" data-tab="invoice">invoice</a>
                    </li>
                </ul>

                <div class="card-body">
                    <table id="table" style="width: 100%;" class="table table-bordered table-striped">
                        <thead id="table-header">
                            <!-- GENERATE THEAD -->
                        </thead>
                        <tbody id="table-body">
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

        var activeTab = 'pelaporan';
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

        function getAjaxUrl(tab) {
            switch (tab) {
                case 'pelaporan':
                    return "<?php echo site_url('swi_rekapitulasi/get_list_pelaporan') ?>";
                case 'reimbust':
                    return "<?php echo site_url('swi_rekapitulasi/get_list_reimbust') ?>";
                case 'invoice':
                    return "<?php echo site_url('swi_rekapitulasi/get_list_invoice') ?>";
                default:
                    return "<?php echo site_url('swi_rekapitulasi/get_list') ?>"; // fallback
            }
        }


        function initializeDataTable() {
            if ($.fn.DataTable.isDataTable('#table')) {
                $('#table').DataTable().destroy();
            }

            var ajaxUrl = getAjaxUrl(activeTab); // Gunakan variabel global

            table = $('#table').DataTable({
                "destroy": true,
                "responsive": true,
                "scrollX": true,
                "processing": true,
                "serverSide": true,
                "order": [],
                "ajax": {
                    "url": ajaxUrl, // <-- pakai URL sesuai tab
                    "type": "POST",
                    "data": function(d) {
                        let tgl_awal = $('#tgl_awal').val();
                        let tgl_akhir = $('#tgl_akhir').val();

                        if (tgl_awal && tgl_akhir) {
                            tgl_awal = formatTanggal(tgl_awal);
                            tgl_akhir = formatTanggal(tgl_akhir);
                        }

                        d.awal = tgl_awal;
                        d.akhir = tgl_akhir;
                        d.tab = activeTab;
                    }
                },
                footerCallback: function(row, data, start, end, display) {
                    // cukup trigger agar footer bisa terpakai
                },
                "drawCallback": function(settings) {
                    $.ajax({
                        "url": "<?php echo site_url('swi_rekapitulasi/get_total') ?>",
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
                            var pengeluaran = total.pengeluaran ? total.pengeluaran : 0;
                            var pemasukan = total.pemasukan ? total.pemasukan : 0;
                            var totalPrepayment = pengeluaran.total_prepayment ? pengeluaran.total_prepayment : 0;
                            var totalReimbust = pengeluaran.total_reimbust ? pengeluaran.total_reimbust : 0;
                            var totalPelaporan = pengeluaran.total_pelaporan ? pengeluaran.total_pelaporan : 0;
                            var total = pengeluaran.total_pengeluaran ? pengeluaran.total_pengeluaran : 0;
                            var invoiceLunas = pemasukan.lunas ? pemasukan.lunas : 0;
                            var invoiceBelumLunas = pemasukan.tidak_lunas ? pemasukan.tidak_lunas : 0;

                            $('#totalPrepayment').text('Rp. ' + parseInt(totalPrepayment).toLocaleString('id-ID'));
                            $('#totalReimbust').text('Rp. ' + parseInt(totalReimbust).toLocaleString('id-ID'));
                            $('#totalPelaporan').text('Rp. ' + parseInt(totalPelaporan).toLocaleString('id-ID'));
                            $('#total').text('Rp. ' + parseInt(total).toLocaleString('id-ID'));

                            $('#invoiceLunas').text('Rp. ' + parseInt(invoiceLunas).toLocaleString('id-ID'));
                            $('#invoiceBelumLunas').text('Rp. ' + parseInt(invoiceBelumLunas).toLocaleString('id-ID'));

                            $('#margin').text('Rp. ' + parseInt(invoiceLunas - total).toLocaleString('id-ID'));
                        },
                        error: function(error) {
                            console.log('Error logging data to second URL');
                        }
                    });
                }
            });
        }


        function updateTableHeader(tab) {
            // Destroy DataTable jika sudah ada
            if ($.fn.DataTable.isDataTable('#table')) {
                $('#table').DataTable().clear().destroy();
            }

            // Kosongkan header & footer
            $('#table-header').empty();
            $('#table-footer').empty();

            // Masukkan header & footer sesuai tab
            let headerHtml = '';
            let footerHtml = '';

            if (tab === 'pelaporan' || tab === 'reimbust') {
                headerHtml = `
            <tr>
                <th>No</th>
                <th>Kode Prepayment</th>
                <th>Kode Reimbust</th>
                <th>Tanggal</th>
                <th>Nama</th>
                <th>Keterangan</th>
                <th>Pengeluaran</th>
            </tr>`;
                footerHtml = headerHtml;
            } else if (tab === 'invoice') {
                headerHtml = `
            <tr>
                <th>No</th>
                <th>Kode Invoice</th>
                <th>Tanggal Invoice</th>
                <th>Nama</th>
                <th>Total</th>
                <th>Status</th>
            </tr>`;
                footerHtml = headerHtml;
            }

            // Tambahkan ke DOM
            $('#table-header').html(headerHtml);
            $('#table-footer').html(footerHtml);

            // Setelah struktur benar, inisialisasi DataTables
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
            activeTab = $(this).data('tab');
            // $('#labelPengeluaran').text(activeTab.charAt(0).toUpperCase() + activeTab.slice(1));

            // Update the table header based on the active tab
            updateTableHeader(activeTab);

            // Reload DataTables to reflect the new header
            // table.ajax.reload();
        });
    });

    // Event listener untuk nav tabs
    $('#tgl_btn').on('click', function(e) {
        e.preventDefault();
        table.ajax.reload(); // Muat ulang data di DataTable saat tab berubah
    });

    $('#btn-export-excel').click(function() {
        // Ambil nilai dari datepicker
        var tgl_awal = $('#tgl_awal').val();
        var tgl_akhir = $('#tgl_akhir').val();

        // // Arahkan ke URL controller untuk export Excel dengan parameter
        window.location.href = "<?= site_url('swi_rekapitulasi/export_excel'); ?>?tgl_awal=" + tgl_awal + "&tgl_akhir=" + tgl_akhir;
    });
</script>