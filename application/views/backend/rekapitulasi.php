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

    @media (max-width: 1000px) {
        .tgl-header {
            display: inline-block;
        }

        .tgl-akhir {
            margin: 1rem 0;
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
                        <button class="btn btn-primary" id="tgl_btn" type="button">DONE</button>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div>
                            Jumlah dari <strong id="labelPengeluaran"></strong> adalah <strong id="totalPengeluaran"></strong>
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
                <div class="card">
                    <div class="card-body">
                        This is some text within a card body.
                    </div>
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
        $('#labelPengeluaran').text(activeTab.charAt(0).toUpperCase() + activeTab.slice(1));

        $('#tgl_awal').datepicker({
            dateFormat: 'dd-mm-yy'
        });

        $('#tgl_akhir').datepicker({
            dateFormat: 'dd-mm-yy'
        });

        function formatTanggal(dateStr) {
            const [day, month, year] = dateStr.split("-");
            return `${year}-${month}-${day}`;
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
                    <th>Tanggal</th>
                    <th>Nama</th>
                    <th>Keterangan</th>
                    <th>Kode Prepayment</th>
                    <th>Kode Reimbust</th>
                    <th>Pengeluaran</th>
                </tr>
            `);
                tableFooter.append(`
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nama</th>
                    <th>Keterangan</th>
                    <th>Kode Prepayment</th>
                    <th>Kode Reimbust</th>
                    <th>Pengeluaran</th>
                </tr>
            `);
            } else if (tab === 'reimbust') {
                tableHeader.append(`
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nama</th>
                    <th>Keterangan</th>
                    <th>Kode Prepayment</th>
                    <th>Kode Reimbust</th>
                    <th>Pengeluaran</th>
                </tr>
            `);
                tableFooter.append(`
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nama</th>
                    <th>Keterangan</th>
                    <th>Kode Prepayment</th>
                    <th>Kode Reimbust</th>
                    <th>Pengeluaran</th>
                </tr>
            `);
            }
        }

        // Event listener untuk nav tabs
        $('.nav-tabs a').on('click', function(e) {
            e.preventDefault();
            $('.nav-tabs a').removeClass('active'); // Hapus kelas aktif dari semua tab
            $(this).addClass('active'); // Tambahkan kelas aktif ke tab yang diklik

            // Get the active tab
            var activeTab = $(this).data('tab');
            $('#labelPengeluaran').text(activeTab.charAt(0).toUpperCase() + activeTab.slice(1));

            // Update the table header based on the active tab
            updateTableHeader(activeTab);

            // Reload DataTables to reflect the new header
            table.ajax.reload();
        });

        // Initialize the table for the first time (pelaporan tab is active by default)
        updateTableHeader('pelaporan');

        table = $('#table').DataTable({
            "responsive": false,
            "scrollX": true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('rekapitulasi/get_list') ?>",
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
            // "language": {
            //     "infoFiltered": ""
            // },
            "columnDefs": [{
                    "targets": [3, 4, 5],
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
                // Fungsi untuk menghitung total pengeluaran
                var totalPengeluaran = 0;

                // Loop melalui semua baris yang ditampilkan
                $('#table tbody tr').each(function() {
                    var data = table.row(this).data();

                    // Hilangkan "Rp." dan titik dari string
                    var cleanedValue = data[6].replace(/Rp\.|\./g, '').trim();

                    var pengeluaran = parseInt(cleanedValue) || 0; // Ambil nilai kolom pengeluaran, default ke 0 jika NaN
                    totalPengeluaran += pengeluaran;
                    // console.log(data[6]);
                    // console.log(totalPengeluaran);
                });

                // Masukkan nilai total pengeluaran ke input field
                $('#totalPengeluaran').text('Rp. ' + totalPengeluaran.toLocaleString('id-ID')); // Format dengan pemisah ribuan
            }
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

    // MENGHAPUS DATA MENGGUNAKAN METHODE POST JQUERY
    function delete_data(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?php echo site_url('prepayment_pu/delete/') ?>" + id,
                    type: "POST",
                    dataType: "JSON",
                    success: function(data) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Your data has been deleted',
                            showConfirmButton: false,
                            timer: 1500
                        }).then((result) => {
                            location.href = "<?= base_url('prepayment_pu') ?>";
                        })
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error deleting data');
                    }
                });
            }
        })
    };
</script>