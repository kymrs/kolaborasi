<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $titleview ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <!-- <?php if ($add == 'Y') { ?>
                        <a class="btn btn-primary btn-sm" href="<?= base_url('mac_reimbust/add_form') ?>">
                            <i class="fa fa-plus"></i>&nbsp;Add Data
                        </a>
                    <?php } ?> -->
                    <div class="d-flex align-items-center">
                        <label for="appFilter" class="mr-2 mb-0">Filter:</label>
                        <select id="appFilter" name="appFilter" class="form-control form-control-sm" style="cursor: pointer;">
                            <!-- <option value="" selected>Show all....</option> -->
                            <option value="on-process" selected>On-Process</option>
                            <option value="approved">Approved</option>
                            <option value="revised">Revised</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                </div>

                <!-- NAV TABS -->
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" id="reimbustTab" href="#" data-tab="reimbust">Reimbust</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" id="prepaymentTab" href="#" data-tab="prepayment">Prepayment</a>
                    </li>
                </ul>
                <div class="card-body" id="reimbustData">
                    <table id="table1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Action</th>
                                <th>Status Pembayaran</th>
                                <th>Kode Reimbust</th>
                                <th>Nama</th>
                                <!-- <th>Jabatan</th>
                                <th>Departemen</th> -->
                                <th>Sifat Pelaporan</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Tujuan</th>
                                <th>Jumlah Prepayment</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Action</th>
                                <th>Status Pembayaran</th>
                                <th>Kode Reimbust</th>
                                <th>Nama</th>
                                <!-- <th>Jabatan</th>
                                <th>Departemen</th> -->
                                <th>Sifat Pelaporan</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Tujuan</th>
                                <th>Jumlah Prepayment</th>
                                <th>Status</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="card-body" id="prepaymentData">
                    <table id="table2" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Action</th>
                                <th>Status Pembayaran</th>
                                <th>Kode Reimbust</th>
                                <th>Nama</th>
                                <!-- <th>Jabatan</th>
                                <th>Departemen</th> -->
                                <th>Sifat Pelaporan</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Tujuan</th>
                                <th>Jumlah Prepayment</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Action</th>
                                <th>Status Pembayaran</th>
                                <th>Kode Reimbust</th>
                                <th>Nama</th>
                                <!-- <th>Jabatan</th>
                                <th>Departemen</th> -->
                                <th>Sifat Pelaporan</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Tujuan</th>
                                <th>Jumlah Prepayment</th>
                                <th>Status</th>
                            </tr>
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
    var table, table2;

    $(document).ready(function() {

        // --- NORMALISASI NAV TAB (hapus kelas active ganda) ---
        $('.nav-tabs .nav-link').removeClass('active');

        // Ambil tab aktif dari sessionStorage (default 'reimbust')
        var activeTab = sessionStorage.getItem('activeTab') || 'reimbust';

        // Set kelas active pada tab yang sesuai
        $('.nav-tabs .nav-link[data-tab="' + activeTab + '"]').addClass('active');

                // Event click pada nav tabs
        $('.nav-tabs .nav-link').on('click', function(e) {
            e.preventDefault();
            var tab = $(this).data('tab');
            // Set active class
            $('.nav-tabs .nav-link').removeClass('active');
            $(this).addClass('active');
            // Simpan pilihan
            sessionStorage.setItem('activeTab', tab);
            // Tampilkan kontainer sesuai tab dan reload tabel yang aktif
            showTab(tab);
        });

        // Simpan nilai filter ke localStorage dan reload tabel aktif
        $('#appFilter').on('change', function() {
            localStorage.setItem('appFilterStatus', $(this).val());
            var currentTab = $('.nav-tabs .nav-link.active').data('tab');
            if (currentTab === 'prepayment') table2.ajax.reload();
            else table.ajax.reload();
        });

        // Apply saved filter jika ada
        var savedFilter = localStorage.getItem('appFilterStatus');
        if (savedFilter) {
            $('#appFilter').val(savedFilter);
        }

        console.log($('#appFilter').val());

        // Inisialisasi DataTables (tetap inisialisasi keduanya)
        table = $('#table1').DataTable({
            "responsive": false,
            "scrollX": true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('mac_reimpayment/get_list') ?>",
                "type": "POST",
                "data": function(d) {
                    d.status = $('#appFilter').val();
                    d.tab = $('.nav-tabs .nav-link.active').data('tab');
                }
            },
            "language": {"infoFiltered": ""},
            "columnDefs": [
                {"targets": [2, 3, 5, 6, 8], "className": 'dt-head-nowrap'},
                {"targets": [1, 9], "className": 'dt-body-nowrap'},
                {"targets": [0, 1], "orderable": false}
            ]
        });

        table2 = $('#table2').DataTable({
            "responsive": false,
            "scrollX": true,
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('mac_reimpayment/get_list3') ?>",
                "type": "POST",
                "data": function(d) {
                    d.status = $('#appFilter').val();
                    d.tab = $('.nav-tabs .nav-link.active').data('tab');
                }
            },
            "columnDefs": [
                {"targets": [2, 3, 5, 7], "className": 'dt-head-nowrap'},
                {"targets": [1, 4, 5, 8], "className": 'dt-body-nowrap'},
                {"targets": [0, 1], "orderable": false}
            ]
        });

        // Fungsi bantu tampilkan kontainer sesuai tab
        function showTab(tab) {
            if (tab === 'prepayment') {
                $('#reimbustData').hide();
                $('#prepaymentData').show();
                table2.ajax.reload(null, false);
            } else {
                $('#prepaymentData').hide();
                $('#reimbustData').show();
                table.ajax.reload(null, false);
            }
        }

        // Tampilkan tab awal berdasarkan sessionStorage (atau default)
        showTab(activeTab);
    });

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
                    url: "<?php echo site_url('mac_reimpayment/delete/') ?>" + id,
                    type: "POST",
                    dataType: "JSON",
                    success: function(data) {
                        if (data.status) {
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'Your data has been deleted',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                location.href = "<?= base_url('mac_reimpayment') ?>";
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.error // Menampilkan pesan kesalahan dari server
                            });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Your data has been deleted',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.href = "<?= base_url('mac_reimpayment') ?>";
                        });
                    }
                });
            }
        });
    }
</script>