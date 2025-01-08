<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view('template/header'); ?>
    <!-- Include Bootstrap CSS -->
    <!-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> -->
    <style>
        body .container {
            font-family: Arial, Helvetica, sans-serif;
            padding: 0;
            color: #333;
        }

        .form-container {
            max-width: 1080px;
            margin: 15px auto;
            padding: 25px;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        .header {
            text-align: center;
            display: flex;
            align-items: center;
        }

        .header .logo {
            /* height: 150px; */
            display: flex;
            align-items: center;
            width: 170px;
            position: relative;
            left: 0px;
            bottom: 14px;
        }

        .header .logo img {
            width: 100%;
        }

        .header h1,
        .header h2 {
            font-size: 24px;
            margin-right: 150px;
            font-weight: bold;
        }

        .header .title {
            width: 100%;
        }

        /* Field Data */
        .field-data table tr td:nth-child(1) {
            padding-right: 42px;
        }

        .field-data table tr td:nth-child(2) {
            position: relative;
            bottom: 1.5px;
            padding-right: 10px;
        }

        /* Table Main */
        .no-prepayment {
            margin-top: 30px;
            float: right;
            margin-right: 200px;
        }

        .table-main {
            border: 1px solid #444;
        }

        .table-main table {
            width: 100%;
        }

        .table-main table tr td,
        .table-main table tr th {
            border: 1.5px solid #444;
            padding: 2.5px;
        }

        /* Table Approve */
        .table-approve {
            margin-top: 35px;
            /* border: 1px solid #444; */
        }

        .table-approve table {
            width: 60%;
        }

        .table-approve table tr td {
            border: 1.5px solid #444;
            padding: 2.5px;
            width: 100px;
            text-align: center;
        }

        /* Transaction Field */
        .kwitansi {
            background-color: #4268D6;
            padding: 5px;
            color: #fff;
            display: inline-block;
            font-size: 0.8rem;
            border-radius: 7px;
            cursor: pointer;
            transition: 300ms;
        }

        .kwitansi:hover {
            scale: 0.95;
        }

        .clear {
            clear: both;
        }

        /* Keterangan Field */
        .keterangan-field {
            margin-top: 20px;
        }

        @media (max-width: 546px) {
            .table-main {
                overflow-x: scroll;
                font-size: 75%;
            }

            .table-approve table {
                width: 100%;
            }

            .header h1,
            .header h2 {
                font-size: 90%;
                margin-right: 0;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="d-flex justify-content-end mb-3" style="margin-right: 19px">
            <?php if ($user->app_name == $app_name && $user->status == 'approved') { ?>
                <a class="btn btn-success btn-sm mr-2" id="paymentBtn" data-toggle="modal" data-target="#paymentModal"><i class="fas fa-money-bill"></i>&nbsp;Payment</a>
            <?php } ?>
            <?php if ($user->app_name == $app_name && !in_array($user->app2_status, ['revised', 'rejected']) && !in_array($user->status, ['approved'])) { ?>
                <a class="btn btn-warning btn-sm mr-2" id="appBtn" data-toggle="modal" data-target="#appModal"><i class="fas fa-check-circle"></i>&nbsp;Approval</a>
            <?php } elseif ($user->app2_name == $app2_name && !in_array($user->app2_status, ['approved', 'rejected']) && $user->app_status == 'approved') { ?>
                <a class="btn btn-warning btn-sm mr-2" id="appBtn2" data-toggle="modal" data-target="#appModal"><i class="fas fa-check-circle"></i>&nbsp;Approval</a>
            <?php } ?>
            <a class="btn btn-secondary btn-sm" onclick="history.back()"><i class="fas fa-chevron-left"></i>&nbsp;Back</a>
        </div>
        <div class="form-container">
            <!-- Header -->
            <div class="header">
                <div class="logo">
                    <img src="<?= base_url() ?>assets/backend/img/sobatwisata.png" alt="Logo">
                </div>
                <div class="title">
                    <h1>FORM PELAPORAN / REIMBUST</h1>
                    <h2>SOBATWISATA</h2>
                </div>
            </div>

            <!-- Field Data -->
            <div class="field-data">
                <table>
                    <tr>
                        <td>NAMA</td>
                        <td>:</td>
                        <td id="nama"></td>
                    </tr>
                    <tr>
                        <td>JABATAN</td>
                        <td>:</td>
                        <td id="jabatan"></td>
                    </tr>
                    <tr>
                        <td>DEPARTEMEN</td>
                        <td>:</td>
                        <td id="departemen"></td>
                    </tr>
                    <tr>
                        <td>SIFAT PELAPORAN</td>
                        <td>:</td>
                        <td id="sifat_pelaporan"></td>
                    </tr>
                    <tr>
                        <td>TANGGAL</td>
                        <td>:</td>
                        <td id="tgl_pengajuan"></td>
                    </tr>
                    <tr>
                        <td>TUJUAN</td>
                        <td>:</td>
                        <td id="tujuan"></td>
                    </tr>
                </table>
            </div>

            <span class="no-prepayment">No. Prepayment : <span id="kode_reimbust"></span></span>
            <div class="clear"></div>
            <div class="table-main">
                <table>
                    <thead>
                        <tr>
                            <td colspan="4" style="font-weight: bold">JUMLAH PREPAYMENT <span style="float: right; margin-right: 10px">Rp. <span id="jumlah_prepayment"></span></span></td>
                            <td colspan="2" style="text-align: center; font-weight: bold">BUKTI PENGELUARAN</td>
                        </tr>
                        <tr>
                            <th colspan="2" style="width: 150px;">PEMAKAIAN</th>
                            <th style="text-align: center">TGL NOTA</th>
                            <th style="text-align: center">JUMLAH</th>
                            <th style="text-align: center">KWITANSI</th>
                            <th style="text-align: center">DEKLARASI</th>
                        </tr>
                    </thead>

                    <tbody id="input-container">
                        <!-- <tr>
                            <td colspan="2">1. Makan</td>
                            <td style="text-align: center">23-08-2024</td>
                            <td>Rp. 50.000</td>
                            <td style="text-align: center">Tes</td>
                            <td style="text-align: center">Tes</td>
                        </tr> -->
                    </tbody>
                    <!-- <tr>
                        <td colspan="6" style="font-weight: bold">TOTAL PEMAKAIAN <span style="float: right; margin-right: 10px">Rp. </span></td>
                    </tr>
                    <tr>
                        <td colspan="6" style="font-weight: bold">SISA PREPAYMENT <span style="float: right; margin-right: 10px">Rp. </span></td>
                    </tr> -->
                </table>
            </div>
            <div class="table-approve">
                <table>
                    <tr>
                        <td>Yang melakukan</td>
                        <td>Mengetahui</td>
                        <td>Menyetujui</td>
                    </tr>
                    <tr style="height: 75px">
                        <td id="statusMelakukan"></td>
                        <td id="statusMengetahui"></td>
                        <td id="statusMenyetujui"></td>
                    </tr>
                    <tr>
                        <td id="melakukan"></td>
                        <td id="mengetahui"></td>
                        <td id="menyetujui"></td>
                    </tr>
                </table>
            </div>
            <div class="keterangan-field" id="keterangan-field" style="display: none;">
                <!-- <span>Keterangan :</span> -->
                <div id="keterangan">
                    <!-- GENERATE KETERANGAN -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="appModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="exampleModalLabel">
                        <i class="fas fa-check-circle"></i> Approval
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="approvalForm" action="">
                        <div class="form-group">
                            <label for="app_status">Status <span class="text-danger">*</span></label>
                            <select id="app_status" name="app_status" class="form-control" style="cursor: pointer;" required>
                                <option selected disabled>Choose status...</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                                <option value="revised">Revised</option>
                            </select>
                            <input type="hidden" id="hidden_id" value="<?php echo $id ?>">
                        </div>
                        <div class="form-group">
                            <label for="app_keterangan" class="col-form-label">Keterangan:</label>
                            <textarea class="form-control" name="app_keterangan" id="app_keterangan" placeholder="Add your comments here"></textarea>
                            <!-- HIDDEN INPUT -->
                            <input type="hidden" name="app_name" id="app_name" value="<?= $this->session->userdata('fullname'); ?>">
                            <input type="hidden" id="hidden_id" name="hidden_id" value="<?= $id ?>">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                <i class="fas fa-times"></i> Close
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Payment -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="exampleModalLabel">
                        <i class="fas fa-check-circle"></i> Payment
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="paymentForm" action="">
                        <div class="form-group">
                            <label style="font-size: 107%;"><span style="font-weight: bold">No Rekening</span> <span style="margin-left: 20px;">:</span> <span id="no_rek"></span></label> <br>
                        </div>
                        <div class="form-group">
                            <div style="display: flex; justify-content: space-between">
                                <label for="payment_status">Status <span class="text-danger">*</span></label>
                            </div>
                            <select id="payment_status" name="payment_status" class="form-control" style="cursor: pointer;" required>
                                <option selected disabled>Choose status...</option>
                                <option value="paid">Paid</option>
                                <option value="unpaid">Unpaid</option>
                            </select>
                            <input type="hidden" id="hidden_id" value="<?php echo $id ?>" name="id">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                <i class="fas fa-times"></i> Close
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div id="myModal" class="kwitansi-modal">
        <span class="close">&times;</span>
        <img class="modal-content-kwitansi" id="img01">
        <!-- <div id="caption"></div> -->
    </div>

    <!-- Include jQuery and Bootstrap JS -->
    <?php $this->load->view('template/footer'); ?>
    <?php $this->load->view('template/script'); ?>
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->

    <script>
        $(document).ready(function() {

            //INISIAI VARIABLE JQUERY
            var id = $('#hidden_id').val();
            let url = "";

            $('#appModal').on('show.bs.modal', function(e) {
                var id = $(e.relatedTarget).data('id');
                $('#hidden_id').val(id);
            });

            $('#appBtn').click(function() {
                $('#app_keterangan').attr('name', 'app_keterangan');
                $('#app_status').attr('name', 'app_status');
                $('#approvalForm').attr('action', '<?= site_url('reimbust_pw/approve') ?>');
                $.ajax({
                    url: "<?php echo site_url('reimbust_pw/edit_data') ?>/" + id,
                    type: "GET",
                    dataType: "JSON",
                    success: function(data) {
                        var nama, date, status, keterangan;
                        // Memeriksa apakah data yang mengetahui ada
                        if (data['master']['app_status'] == 'waiting') {
                            $('#app_status').val();
                            $('#app_keterangan').val();
                        } else {
                            nama = data['master']['app_name'];
                            status = data['master']['app_status'];
                            keterangan = data['master']['app_keterangan'];
                            $('#app_status').val(status);
                            $('#app_keterangan').val(keterangan);
                            // $('#note_id').append(`<p>* ${keterangan}</p>`);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error get data from ajax');
                    }
                });
            });

            $('#appBtn2').click(function() {
                $('#app_keterangan').attr('name', 'app2_keterangan').attr('id', 'app2_keterangan');
                $('#app_status').attr('name', 'app2_status').attr('id', 'app2_status');
                $('#approvalForm').attr('action', '<?= site_url('reimbust_pw/approve2') ?>');

                $.ajax({
                    url: "<?php echo site_url('reimbust_pw/edit_data') ?>/" + id,
                    type: "GET",
                    dataType: "JSON",
                    success: function(data) {
                        var nama2, date2, status2, keterangan2;
                        if (data['master']['app2_status'] == 'waiting') {
                            $('#app2_status').val();
                            $('#app2_keterangan').val();
                        } else {
                            nama2 = data['master']['app2_name'];
                            status2 = data['master']['app2_status'];
                            keterangan2 = data['master']['app2_keterangan'];
                            $('#app2_status').val(status2);
                            $('#app2_keterangan').val(keterangan2);
                            // $('#note_id').append(`<p>* ${keterangan2}</p>`);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error get data from ajax');
                    }
                });
            });

            $('#paymentBtn').click(function() {
                $('#paymentForm').attr('action', '<?= site_url('reimbust_pw/payment') ?>');

                $.ajax({
                    url: "<?php echo site_url('reimbust_pw/edit_data') ?>/" + id,
                    type: "GET",
                    dataType: "JSON",
                    success: function(data) {
                        $('#payment_status').val(data['master']['payment_status']);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error get data from ajax');
                    }
                });
            });

            // Handle the approval button click event
            $('#confirmApproval').click(function() {
                const id = $('#hidden_id').val();
                // Implement the logic to process the approval
                console.log('Approval confirmed for ID:', id);
                // Close the modal
                $('#appModal').modal('hide');
            });

            // Additional logic to dynamically load data into the form
            $.ajax({
                url: "<?php echo site_url('reimbust_pw/edit_data') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    // console.log(data);
                    moment.locale('id')
                    // DATA REIMBUST
                    $('#nama').html(data['nama']);
                    $('#jabatan').html(data['master']['jabatan']);
                    $('#departemen').html(data['master']['departemen']);
                    $('#sifat_pelaporan').html(data['master']['sifat_pelaporan']);
                    $('#tgl_pengajuan').html(getFormattedDate(moment(data['master']['tgl_pengajuan']).format('DD MM YYYY')));
                    $('#tujuan').html(data['master']['tujuan']);
                    $('#kode_reimbust').html(data['master']['kode_prepayment'] ? data['master']['kode_prepayment'] : '-');
                    $('#jumlah_prepayment').html(data['master']['jumlah_prepayment'].replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
                    $('#no_rek').html(data['master']['no_rek'] ? data['master']['no_rek'] : '-');
                    if ((data['master']['app_keterangan'] !== null && data['master']['app_keterangan'] !== '') ||
                        (data['master']['app2_keterangan'] !== null && data['master']['app2_keterangan'] !== '')) {
                        $('#keterangan').append(`<span>Keterangan :</span>`);
                    }
                    if (data['master']['app_keterangan'] != null && data['master']['app_keterangan'] != '') {
                        $('#keterangan').append(`<span class="form-control-plaintext">*(${data['master']['app_name']}) ${data['master']['app_keterangan']}</span>`);
                    }
                    if (data['master']['app2_keterangan'] != null && data['master']['app2_keterangan'] != '') {
                        $('#keterangan').append(`<span class="form-control-plaintext">*(${data['master']['app2_name']}) ${data['master']['app2_keterangan']}</span>`);
                    }

                    // DATA APPROVAL REIMBUST
                    var nama, status, keterangan, nama2, status2, keterangan2, url;

                    // Memeriksa apakah data yang mengetahui ada
                    if (data['master']['app_status'] != null) {
                        nama = data['master']['app_name'];
                        status = data['master']['app_status'];
                        keterangan = data['master']['app_keterangan'];
                        url = "<?php echo site_url('reimbust_pw/approve') ?>";
                        $('#note_id').append(`<p>* ${keterangan}</p>`);
                    }

                    if (data['master']['app_date'] == null) {
                        date = '';
                    }
                    if (data['master']['app_date'] != null) {
                        date = moment(data['master']['app_date']).format('D MMMM YYYY');
                    }

                    // Memeriksa apakah data yang mengetahui ada
                    if (data['master']['app_status'] != null) {
                        nama = data['master']['app_name'];
                        status = data['master']['app_status'];
                        keterangan = data['master']['app_keterangan'];
                        url = "<?php echo site_url('reimbust_pw/approve') ?>";
                        $('#note_id').append(`<p>* ${keterangan}</p>`);
                    }
                    if (data['master']['app_date'] == null) {
                        date = '';
                    }
                    if (data['master']['app_date'] != null) {
                        date = moment(data['master']['app_date']).format('D-MM-YYYY HH:mm:ss');
                    }

                    // Memeriksa apakah data yang menyetujui ada
                    if (data['master']['app2_status'] != null) {
                        nama2 = data['master']['app2_name'];
                        status2 = data['master']['app2_status'];
                        keterangan2 = data['master']['app2_keterangan'];
                        url = "<?php echo site_url('prepayment/approve2') ?>";
                        $('#note_id').append(`<p>* ${keterangan2}</p>`);
                    }
                    if (data['master']['app2_date'] == null) {
                        date2 = '';
                    }
                    if (data['master']['app2_date'] != null) {
                        date2 = moment(data['master']['app2_date']).format('D-MM-YYYY HH:mm:ss');
                    }

                    // Keterangan
                    if (data['master']['app_keterangan'] || data['master']['app2_keterangan'] != null) {
                        $('#keterangan-field').css('display', 'inline-block');
                    }

                    //DATA REIMBUST DETAIL
                    let total = 0;
                    let sisa = data['master']['jumlah_prepayment'];
                    for (let index = 0; index < data['transaksi'].length; index++) {
                        const row = `
                                    <tr>
                                        <td colspan="2">${index + 1}. ${data['transaksi'][index]['pemakaian']}</td>
                                        <td style="text-align: center">${getFormattedDate(moment(data['transaksi'][index]['tgl_nota']).format('DD MM YYYY'))}</td>
                                        <td style="text-align: center">${data['transaksi'][index]['jumlah'].replace(/\B(?=(\d{3})+(?!\d))/g, '.')}</td>
                                        <td>
                                            <div style="scale: 0.8; text-align: center" class="openModal" data-kwitansi="${data['transaksi'][index]['kwitansi']}">${data['transaksi'][index]['kwitansi'] ? '<div class="btn btn-primary btn-block btn-sm ">Lihat Foto</div>' : '-'}</div>
                                        </td>
                                        <td style="text-align: center">
                                            <div style="scale: 0.8" data-id="${index + 1}" id="deklarasi-modal${index + 1}">
                                                ${data['transaksi'][index]['deklarasi'] ? `<div class="btn btn-primary btn-block btn-sm" data-deklarasi="${data['transaksi'][index]['deklarasi']}">${data['transaksi'][index]['deklarasi']}</div>` : '-'}
                                            </div>
                                        </div>
                                        </td>
                                    </tr>
                                `;
                        $('#input-container').append(row);
                        total += Number(data['transaksi'][index]['jumlah']);
                        sisa -= Number(data['transaksi'][index]['jumlah']);
                    }

                    // Mendapatkan modal
                    var modal = $('#myModal');

                    // Mendapatkan gambar modal dan caption
                    var modalImg = $("#img01");
                    var captionText = $("#caption");

                    // Ketika button diklik, tampilkan modal dengan gambar
                    $('.openModal').on('click', function() {
                        const kwitansi = $(this).data('kwitansi');
                        if (kwitansi) {
                            // Jika data kwitansi ada, lanjutkan dengan membuka modal
                            modal.css("display", "block");
                            modalImg.attr('src', `<?= base_url() ?>/assets/backend/document/reimbust/kwitansi_pw/${kwitansi}`);
                            // captionText.text('Deskripsi gambar Anda di sini'); // Ubah dengan deskripsi gambar
                        }
                    });

                    $(document).ready(function() {
                        // Inisialisasi tombol submit dalam keadaan disabled
                        $('#approvalForm button[type="submit"]').prop('disabled', true).css('cursor', 'not-allowed');

                        // Event listener untuk elemen select
                        $('#app_status').change(function() {
                            if ($(this).val() === null || $(this).val() === 'Choose status...') {
                                // Nonaktifkan tombol submit jika tidak ada status yang dipilih
                                $('#approvalForm button[type="submit"]').prop('disabled', true);
                            } else {
                                // Aktifkan tombol submit jika status telah dipilih
                                $('#approvalForm button[type="submit"]').prop('disabled', false).css('cursor', 'pointer');
                            }
                        });
                    });

                    $(document).ready(function() {
                        // Inisialisasi tombol submit dalam keadaan disabled
                        $('#paymentForm button[type="submit"]').prop('disabled', true).css('cursor', 'not-allowed');

                        // Event listener untuk elemen select
                        $('#payment_status').change(function() {
                            if ($(this).val() === null || $(this).val() === 'Choose status...') {
                                // Nonaktifkan tombol submit jika tidak ada status yang dipilih
                                $('#paymentForm button[type="submit"]').prop('disabled', true);
                            } else {
                                // Aktifkan tombol submit jika status telah dipilih
                                $('#paymentForm button[type="submit"]').prop('disabled', false).css('cursor', 'pointer');
                            }
                        });
                    });

                    // Ketika tombol close diklik, sembunyikan modal
                    $(document).ready(function() {
                        // Ketika tombol close diklik
                        $('.close').on('click', function() {
                            modal.css("display", "none");
                        });

                        // Ketika klik di luar modal, modal akan ditutup
                        $(window).on('click', function(event) {
                            // Cek jika klik terjadi di luar modal
                            if ($(event.target).is(modal)) {
                                modal.css("display", "none");
                            }
                        });
                    });

                    $(document).on('click', '[data-deklarasi]', function() {
                        var deklarasi = $(this).data('deklarasi');

                        $.ajax({
                            url: '<?= site_url('reimbust_pw/detail_deklarasi') ?>', // URL method controller
                            method: 'POST',
                            data: {
                                deklarasi: deklarasi
                            },
                            success: function(response) {
                                var data = JSON.parse(response);
                                if (data.status === 'success') {
                                    // Buka URL yang dikirim dari server di tab baru
                                    window.open(data.redirect_url, '_blank');
                                } else {
                                    console.log('Error: ' + data.message);
                                }
                            },
                            error: function(error) {
                                console.log('Terjadi kesalahan: ', error);
                            }
                        });

                    });

                    $(document).on('mouseenter', '[data-deklarasi]', function() {
                        // Simpan teks asli sebagai data atribut
                        var originalText = $(this).text();
                        $(this).data('original-text', originalText);

                        // Ubah teks menjadi "detail"
                        $(this).text('Detail');

                        // Ubah background menjadi merah (opsional)
                        // $(this).css('background-color', 'red');
                    });

                    $(document).on('mouseleave', '[data-deklarasi]', function() {
                        // Kembalikan teks ke nilai aslinya
                        var originalText = $(this).data('original-text');
                        $(this).text(originalText);

                        // Kembalikan background ke default (opsional)
                        // $(this).css('background-color', '');
                    });


                    const totalFormatted = total.toLocaleString('de-DE');
                    const sisaFormatted = sisa.toLocaleString('de-DE');
                    const ttl_row = `
                                    <tr>
                                        <td colspan="6" style="font-weight: bold">TOTAL PEMAKAIAN <span style="float: right; margin-right: 10px">Rp. ${totalFormatted}</span></td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" style="font-weight: bold">SISA PREPAYMENT <span style="float: right; margin-right: 10px">Rp. ${sisaFormatted}</span></td>
                                    </tr>
                                    `;
                    $('#input-container').append(ttl_row);

                    $('#melakukan').text(`${data['nama']}`);
                    $('#mengetahui').text(`${data['master']['app_name']}`);
                    $('#menyetujui').text(`${data['master']['app2_name']}`);
                    $('#statusMelakukan').html('CREATED<br>' + `${moment(data['master']['created_at']).format('D-MM-YYYY HH:mm:ss')}`);
                    $('#statusMengetahui').html(`<div>${data['master']['app_status'].toUpperCase()}<br><span>${date}</span></div></div>`);
                    $('#statusMenyetujui').html(`<div>${data['master']['app2_status'].toUpperCase()}<br><span>${date2}</span></div></div>`);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });

            function getFormattedDate(dateString) {
                // Memisahkan string berdasarkan spasi
                var parts = dateString.split(" ");

                // Mengambil bagian hari, bulan, dan tahun
                var day = parts[0];
                var month = parts[1];
                var year = parts[2];

                // Mengubah bulan menjadi nama bulan dalam bahasa Indonesia
                switch (month) {
                    case '01':
                        month = 'Januari';
                        break;
                    case '02':
                        month = 'Februari';
                        break;
                    case '03':
                        month = 'Maret';
                        break;
                    case '04':
                        month = 'April';
                        break;
                    case '05':
                        month = 'Mei';
                        break;
                    case '06':
                        month = 'Juni';
                        break;
                    case '07':
                        month = 'Juli';
                        break;
                    case '08':
                        month = 'Agustus';
                        break;
                    case '09':
                        month = 'September';
                        break;
                    case '10':
                        month = 'Oktober';
                        break;
                    case '11':
                        month = 'November';
                        break;
                    case '12':
                        month = 'Desember';
                        break;
                }

                // Menggabungkan hari, bulan, dan tahun menjadi satu string
                return day + " " + month + " " + year;
            }

            // APPROVE
            $('#approvalForm').submit(function(e) {
                e.preventDefault();
                var url = $(this).attr('action');
                // MENGINPUT APPROVAL
                $.ajax({
                    url: url, // Mengambil action dari form
                    type: "POST",
                    data: $(this).serialize(), // Mengambil semua data dari form
                    dataType: "JSON",
                    success: function(data) {
                        console.log(data);
                        if (data.status) //if success close modal and reload ajax table
                        {
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'Your data has been saved',
                                showConfirmButton: false,
                                timer: 1500
                            }).then((result) => {
                                window.history.back(); // Kembali ke halaman sebelumnya
                            })
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error adding / update data');
                    }
                });
            });

            // PAYMENT
            $('#paymentForm').submit(function(e) {
                e.preventDefault();
                var url = $(this).attr('action');
                // MENGINPUT PAYMENT
                $.ajax({
                    url: url, // Mengambil action dari form
                    type: "POST",
                    data: $(this).serialize(), // Mengambil semua data dari form
                    dataType: "JSON",
                    success: function(data) {
                        console.log(data);
                        if (data.status) //if success close modal and reload ajax table
                        {
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'Your data has been saved',
                                showConfirmButton: false,
                                timer: 1500
                            }).then((result) => {
                                window.history.back(); // Kembali ke halaman sebelumnya
                            })
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error adding / update data');
                    }
                });
            });

            // Example: Load data into the form fields and tables
            // $('#divisiCol').text('Finance');
            // $('#prepaymentCol').text('001234');
            // $('#tanggal').text('29 August 2024');
            // $('#nama').text('Rakha Rizki');
            // $('#jabatan').text('Software Developer');
            // $('#tujuan').text('Project Development');

            // Example: Append rows to the rincian table
            // $('#input-container').append(`
            //     <tr>
            //         <td>Consultation Fees</td>
            //         <td>Rp. 5,000,000</td>
            //         <td>Consulting on project scope</td>
            //     </tr>
            //     <tr>
            //         <td>Development Tools</td>
            //         <td>Rp. 3,000,000</td>
            //         <td>Purchase of software licenses</td>
            //     </tr>
            // `);

            // $('#keterangan').append(`<span class="form-control-plaintext">*Berikut ini merupakan catatan keterangan prepayment.</span>`);
        });
    </script>
</body>

</html>