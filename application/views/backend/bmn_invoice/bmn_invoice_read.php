<?php $this->load->view('template/header'); ?>

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/backend/plugins/style-invoice.css') ?>">
</head>

<style>
    .detail-pemesanan .header {
        background-color: #45577b;
        padding: 3px 7px;
        color: #fff;
    }

    .watermark {
        border: 4px solid rgb(69, 87, 123);
        display: inline-block;
        border-radius: 50%;
        color: rgb(69, 87, 123);
        background-color: transparent;
        opacity: 0.3;
        box-sizing: border-box;
        position: absolute;
        /* transform: scale(1.5) rotate(-30deg); */
        transform: none !important;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    @media (max-width: 576px) {

        /* Untuk tampilan mobile */
        .watermark {
            font-size: 2rem;
            padding: 10px 20px;
            left: 25%;
            top: 56%;
            /* Perbesar watermark */
            width: 160px;
            /* Atur ukuran box */
            height: 80px;
        }
    }

    @media (min-width: 576px) {

        /* Untuk tampilan desktop */
        .watermark {
            font-size: 25px;
            padding: 20px 50px;
            left: 78%;
            top: 80%;
            /* Perbesar watermark */
            width: 80px;
            /* Atur ukuran box */
            height: 80px;
        }
    }
</style>

<body>
    <div class="container">
        <div class="canvas">
            <a class="btn btn-secondary btn-sm" onclick="history.back()" style="float: right"><i class="fas fa-chevron-left"></i>&nbsp;Back</a>
            <!-- FITUR PAYMENT -->
            <?php if ($name == 'eko' || $invoice['id_user'] == $id_user) { ?>
                <a class="btn btn-success btn-sm mr-2" id="paymentBtn" data-toggle="modal" style="float: right;" data-target="#paymentModal"><i class="fas fa-money-bill"></i>&nbsp;Payment</a>
            <?php } ?>
            <!-- FITUR SEND EMAIL -->
            <!-- <a class="btn btn-info btn-sm" id="send_email" data-id="<?= $id ?>" style="float: right; margin-right: 10px"><i class="fas fa-envelope"></i>&nbsp;&nbsp;Send Email</a> -->
            <hr class="line-header">
            <header>
                <div class="left-side">
                    <div class="logo">
                        <img src="<?= base_url('assets/backend/img/bymoment.png') ?>" alt="logo pengenumroh">
                    </div>
                </div>
                <div class="right-side">
                    <div class="email">cs@bymoment.id</div>
                    <div class="noHP">0812-90700033</div>
                </div>
            </header>
            <hr class="line">
            <main>
                <div class="invoice">
                    <h3>INVOICE</h3>
                    <?php $result = substr($invoice['kode_invoice'], 0, 5) . substr($invoice['kode_invoice'], 7); ?>
                    <p>No. Invoice : <?= $result; ?></p>
                </div>
                <div class="header-content">
                    <div class="left-side">
                        <h2>Kepada Yth.</h2>
                        <h1><?= $invoice['ctc2_nama'] ?></h1>
                        <div style="display: flex; gap: 5px;">
                            <label for="">Email</label>
                            <span style="margin-left: 28px;">:</span>
                            <p class="alamat"><?= $invoice['ctc2_email'] ?></p>
                        </div>
                        <div style="display: flex; gap: 5px;">
                            <label for="">Telepon</label>
                            <span style="margin-left: 10px;">:</span>
                            <p class="alamat"><?= $invoice['ctc2_nomor'] ?></p>
                        </div>
                        <div style="display: flex; gap: 5px;">
                            <label for="">Alamat</label>
                            <span style="margin-left: 14px;">:</span>
                            <p class="alamat"><?= $invoice['ctc2_alamat'] ?></p>
                        </div>
                    </div>
                    <div class="right-side">
                        <table>
                            <tr>
                                <td>Tanggal Invoice</td>
                                <td>:</td>
                                <td><?= date('d/m/Y', strtotime($invoice['tgl_invoice'])) ?></td>
                            </tr>
                            <tr>
                                <td>Tanggal Jatuh Tempo</td>
                                <td>:</td>
                                <td><?= date('d/m/Y', strtotime($invoice['tgl_tempo'])) ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="detail-pemesanan" style="position: relative;">
                    <?php if ($status == 1) { ?>
                        <div class="watermark">LUNAS</div>
                    <?php } ?>
                    <div class="header">
                        Detail Pemesanan :
                    </div>
                    <table>
                        <tr>
                            <th>DESKRIPSI</th>
                            <th>JUMLAH</th>
                            <th>HARGA</th>
                            <th>TOTAL</th>
                        </tr>
                        <?php
                        $total = 0; // Inisialisasi variabel total
                        $diskon = $invoice['diskon'];
                        foreach ($detail as $data) :
                            $total += $data['total']; // Tambahkan harga ke total
                        ?>
                            <tr>
                                <td><?= $data['deskripsi'] ?></td>
                                <td class="jumlah"><?= $data['jumlah'] ?></td>
                                <td class="harga"><?= "Rp. " . number_format($data['harga'], 0, ',', '.') ?></td>
                                <td class="total"><?= "Rp. " . number_format($data['total'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <?php if ($diskon != 0 || $diskon == '') { ?>
                                <td colspan="2" style="border-left-color: #fff; border-bottom-color: #fff"></td>
                                <td style="text-align: center;">Diskon</td>
                                <td style="text-align: center"><?= "Rp. " . number_format($invoice['diskon'], 0, ',', '.') ?></td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td colspan="2" style="border-left-color: #fff; border-bottom-color: #fff"></td>
                            <th>Total</th>
                            <td style="text-align: center"><?= "Rp. " . number_format($total - $invoice['diskon'], 0, ',', '.') ?></td>
                        </tr>
                    </table>
                </div>
                <div class="metode-pembayaran">
                    <p>Metode Pembayaran</p>
                    <ol>
                        <?php foreach ($rekening as $data) : ?>
                            <li>Nama : <?= $data['nama'] ?> <br> Bank : <?= $data['nama_bank'] ?> <br> No. Rekening : <?= $data['no_rek'] ?> </li>
                        <?php endforeach ?>
                    </ol>
                    <p>Atas Nama : PT. Kolaborasi Para Sahabat </p>
                </div>
                <div class="">
                    <p>Catatan :</p>
                    <div style="margin-top: -16px;">
                        <?= $invoice['keterangan'] ?>
                    </div>
                </div>
                <div class="" style="text-align: right;">
                    <p><b>Terima Kasih</b>, By Moment</p>
                </div>
            </main>
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
                        <!-- <div class="form-group">
                            <label style="font-size: 107%;"><span style="font-weight: bold">No Rekening</span> <span style="margin-left: 20px;">:</span> <span id="no_rek"></span></label> <br> -->
                        <!-- <label style="font-size: 107%;"><span style="font-weight: bold">Jenis Rekening</span> <span style="margin-left: 5px;">:</span> <span id="jenis_rek"></span></label> -->
                        <!-- </div> -->
                        <div class="form-group">
                            <label for="payment_status">Status <span class="text-danger">*</span></label>
                            <select id="payment_status" name="payment_status" class="form-control" style="cursor: pointer;" required>
                                <option selected disabled>Choose status...</option>
                                <option value="1">Lunas</option>
                                <option value="0">Belum Lunas</option>
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

    <?php $this->load->view('template/script'); ?>
    <script>
        $(document).ready(function() {
            $('#send_email').on('click', function(e) {
                e.preventDefault();

                let email = "<?= $invoice['ctc2_email'] ?>"; // Ambil email dari PHP
                let id = $(this).data('id');

                Swal.fire({
                    title: 'Apakah anda ingin mengirim email ke:',
                    html: `<strong>${email}</strong>`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'YA',
                    cancelButtonText: 'TIDAK',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Tampilkan loading spinner
                        const loadingSwal = Swal.fire({
                            title: 'Sedang mengirim email...',
                            html: 'Harap tunggu sebentar',
                            didOpen: () => {
                                Swal.showLoading(); // Menampilkan spinner loading
                            },
                            allowOutsideClick: false, // Jangan biarkan swal ditutup saat loading
                        });

                        // Kirim data email ke controller via AJAX
                        $.ajax({
                            url: "<?= base_url('bmn_invoice/send_email') ?>",
                            type: "POST",
                            data: {
                                email: email,
                                id: id
                            },
                            success: function(response) {
                                loadingSwal.close(); // Tutup loading spinner
                                console.log(response); // Lihat apa yang dikembalikan dari controller
                                Swal.fire('Terkirim!', 'Email berhasil dikirim.', 'success');
                            },
                            error: function() {
                                loadingSwal.close(); // Tutup loading spinner
                                Swal.fire('Error!', 'Terjadi kesalahan saat mengirim email.', 'error');
                            }
                        });

                    } else {
                        Swal.fire('Dibatalkan', 'Pengiriman email dibatalkan.', 'info');
                    }
                });
            });


            $('#paymentBtn').click(function() {
                $('#paymentForm').attr('action', '<?= site_url('bmn_invoice/payment') ?>');
                const id = $('#hidden_id').val();

                $.ajax({
                    url: "<?php echo site_url('bmn_invoice/edit_data') ?>/" + id,
                    type: "GET",
                    dataType: "JSON",
                    success: function(data) {
                        console.log(data);
                        // $('#jenis_rek').html(data['master']['jenis_rek'] ? data['master']['jenis_rek'] : '-');
                        // $('#no_rek').html(data['master']['no_rek'] ? data['master']['no_rek'] : '-');
                        $('#payment_status').val(data['master']['payment_status']);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error get data from ajax');
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

        });
    </script>

    <?php $this->load->view('template/footer'); ?>