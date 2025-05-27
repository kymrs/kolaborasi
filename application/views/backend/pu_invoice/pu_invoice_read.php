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
            <a class="btn btn-success btn-sm" href="<?= base_url('pu_invoice/generate_pdf_invoice/' . $invoice['id']) ?>" style="float: right;"><i class="fas fa-file-pdf"></i>&nbsp;Print</a>
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
                        <h2>Bapak Nana Suryana</h2>
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
                        <td style="text-align: center; font-weight: bold">Total</td>
                        <td>Rp. <?= number_format($total_tagihan, 0, ',', '.') ?></td>
                    </tr>
                    <?php if ($total_nominal_dibayar > 0) { ?>
                        <tr>
                            <td style="border-color: #fff;"></td>
                            <td style="border-bottom-color: #fff;"></td>
                            <td style="text-align: center; font-weight: bold">Total Dibayar</td>
                            <td>Rp. <?= number_format($total_nominal_dibayar, 0, ',', '.') ?></td>
                        </tr>
                        <tr>
                            <td style="border-color: #fff;"></td>
                            <td style="border-bottom-color: #fff;"></td>
                            <td style="text-align: center; font-weight: bold">Sisa Tagihan</td>
                            <td>Rp. <?= number_format($total_tagihan - $total_nominal_dibayar, 0, ',', '.') ?></td>
                        </tr>
                    <?php } ?>
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