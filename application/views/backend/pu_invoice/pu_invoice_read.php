<?php $this->load->view('template/header'); ?>

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/backend/plugins/style-invoice-pu.css') ?>">
</head>

<body>
    <div class="container">
        <div class="canvas">
            <a class="btn btn-secondary btn-sm" onclick="history.back()" style="float: right; margin-left: 8px;"><i class="fas fa-chevron-left"></i>&nbsp;Back</a>
            <a class="btn btn-success btn-sm" id="print-btn" data-id="<?= $invoice['id'] ?>" style="float: right;"><i class="fas fa-file-pdf"></i>&nbsp;Print</a>
            <a class="btn btn-info btn-sm" id="send_email" data-id="<?= $invoice['id'] ?>" style="float: right; margin-right: 10px"><i class="fas fa-envelope"></i>&nbsp;&nbsp;Send Email</a>

            <select class="js-example-basic-single" id="tagihan_select" name="tagihan_select" style="float: right; margin-right: 10px; width: 200px; height: 30px;">
                <option value="default" selected>Tagihan 1</option>
                <?php $i = 2; ?>
                <?php foreach ($kwitansi as $option) { ?>
                    <?php if ($option['sisa_tagihan'] != 0) { ?>
                        <option value="<?= $option['id'] ?>" data-id_invoice="<?= $option['id_invoice'] ?>">Tagihan <?= $i ?></option>
                    <?php } ?>
                    <?php $i++; ?>
                <?php } ?>
            </select>

            <hr class="line-header">
            <header>
                <img class="header-image" src="<?= base_url('assets/backend/img/header.png') ?>" alt="">
                <div style="clear:both"></div>
                <div class="row" style="margin-top: 130px;">
                    <div class="left-side">
                        <h3>PT. KOLABORASI PARA SAHABAT</h3>
                    </div>
                    <div class="right-side">
                        <h1>INVOICE</h1>
                        <table>
                            <tr>
                                <td>No</td>
                                <td>:</td>
                                <td><?= $invoice['kode_invoice'] ?></td>
                            </tr>
                            <tr>
                                <td>Tanggal Invoice</td>
                                <td>:</td>
                                <td><?= $tgl_invoice ?></td>
                            </tr>
                            <tr>
                                <td>Tanggal Jatuh Tempo</td>
                                <td>:</td>
                                <td><?= $tgl_tempo ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </header>
            <div class="line-title">Data Pemesanan :<span>Jumlah Yang Harus Dibayar</span></div>
            <div class="data-pemesanan">
                <div class="row">
                    <div class="left-side">
                        <p>Kpd Yth.</p>
                        <h2><?= $invoice['ctc_nama'] ?></h2>
                        <p class="address">Alamat : <?= $invoice['ctc_alamat'] ?></p>
                    </div>
                    <div class="right-side">
                        <h1>Rp. <?= number_format($total_tagihan, 0, ',', '.') ?></h1>
                    </div>
                </div>
            </div>
            <div class="line-title">Data Jamaah :<span>Detail Pesanan</span></div>
            <div class="data-jamaah">
                <div class="row">
                    <div class="left-side">
                        <div class="jamaah">
                            <?= $invoice['jamaah'] ?>
                        </div>
                    </div>
                    <div class="right-side">
                        <div class="detail-pesanan">
                            <?= $invoice['detail_pesanan'] ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="line-title">Detail Pemesanan :</div>
            <div class="detail-pemesanan">
                <table>
                    <tr>
                        <th>DESKRIPSI</th>
                        <th>JUMLAH</th>
                        <th>HARGA</th>
                        <th>TOTAL</th>
                    </tr>
                    <?php foreach ($details as $key => $value) { ?>
                        <tr>
                            <td><?= $value['deskripsi'] ?></td>
                            <td><?= $value['jumlah'] ?></td>
                            <td>Rp. <?= number_format($value['harga'], 0, ',', '.') ?></td>
                            <td>Rp. <?= number_format($value['total'], 0, ',', '.') ?></td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td style="border-color: #fff;"></td>
                        <td style="border-bottom-color: #fff;"></td>
                        <td style="text-align: center; font-weight: bold">Total Tagihan</td>
                        <td>Rp. <?= number_format($total_tagihan, 0, ',', '.') ?></td>
                    </tr>
                    <tbody id="detail_tagihan">

                    </tbody>
                </table>
            </div>
            <div class="metode-pembayaran">
                <p>Metode Pembayaran</p>
                <p>Bank : <span><?= $rekening->nama_bank ?></span></p>
                <p>No rek : <span><?= $rekening->no_rek ?></span></p>
                <p><b>a/n : <span><?= $rekening->travel ?></span></b></p>
            </div>
            <!-- Tambahkan catatan di bawah metode pembayaran -->
            <div class="catatan-invoice" style="margin-top:20px; margin-bottom:40px;">
                <table>
                    <tr>
                        <td style="font-weight:bold; width:120px; vertical-align: top;">Catatan</td>
                        <td style="vertical-align: top;">:</td>
                        <td style="vertical-align: top;"><?= !empty($invoice['keterangan']) ? nl2br($invoice['keterangan']) : '-' ?></td>
                    </tr>
                </table>
            </div>
            <img class="footer-image" src="<?= base_url('assets/backend/img/footer.png') ?>" alt="">
        </div>
    </div>

    <?php $this->load->view('template/footer'); ?>
    <?php $this->load->view('template/script'); ?>

    <script>
        $(document).ready(function() {

            $('#tagihan_select').on('change', function() {
                $('#detail_kwitansi').empty(); // Clear previous detail_kwitansi
                var selectedValue = $(this).val();
                var idInvoice = $('#tagihan_select option:selected').data('id_invoice');
                $('#detail_tagihan').empty();

                if ($('#tagihan_select').val() != 'default') {
                    // Mengisi detail kwitansi
                    $.ajax({
                        url: "<?= base_url('/pu_invoice/get_kwitansi') ?>",
                        type: "POST",
                        data: {
                            id: selectedValue,
                            id_invoice: idInvoice
                        },
                        dataType: "json",
                        success: function(data) {
                            // console.log(data);
                            $('#detail_tagihan').append(`
                            <tr>
                                <td style="border-color: #fff;"></td>
                                <td style="border-bottom-color: #fff;"></td>
                                <td style="text-align: center; font-weight: bold">Telah Terbayar</td>
                                <td>Rp. ${Number(data.total_nominal_dibayar).toLocaleString('id-ID')}</td>
                            </tr>
                            <tr>
                                <td style="border-color: #fff;"></td>
                                <td style="border-bottom-color: #fff;"></td>
                                <td style="text-align: center; font-weight: bold">Sisa Tagihan</td>
                                <td>Rp. ${Number(data.kwitansi.sisa_tagihan).toLocaleString('id-ID')}</td>
                            </tr>
                        `);
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                        }
                    });
                }
            });

            //PRINT
            $('#print-btn').on('click', function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                let value = $('#tagihan_select').val() != 'default' ? $('#tagihan_select').val() : '';
                console.log(value);

                // Gunakan form POST tersembunyi untuk membuka tab baru
                let form = $('<form>', {
                    action: "<?= base_url('pu_invoice/prepare_print_invoice') ?>",
                    method: 'POST',
                    target: '_blank'
                }).append($('<input>', {
                    type: 'hidden',
                    name: 'id_invoice',
                    value: id
                }), $('<input>', {
                    type: 'hidden',
                    name: 'id',
                    value: value
                }));

                // Append form ke body dan submit
                form.appendTo('body').submit().remove();
            });


            // SEND EMAIL
            $('#send_email').on('click', function(e) {
                e.preventDefault();

                let email = "<?= $invoice['ctc_email'] ?>"; // Ambil email dari PHP
                let id_invoice = $(this).data('id');
                let id = $('#tagihan_select').val();
                console.log('ID:', id); // Debugging untuk melihat ID yang dikirim

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
                            url: "<?= base_url('pu_invoice/send_email_invoice') ?>",
                            type: "POST",
                            dataType: "json",
                            data: {
                                email: email,
                                id_invoice: id_invoice,
                                id: id
                            },
                            success: function(response) {
                                loadingSwal.close(); // Tutup loading spinner
                                console.log(response); // Lihat apa yang dikembalikan dari controller
                                if (response.status) {
                                    Swal.fire('Terkirim!', 'Email berhasil dikirim.', 'success');
                                } else {
                                    Swal.fire('Gagal!', 'Email tidak berhasil dikirim.', 'error');
                                    return;
                                }
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
        });
    </script>