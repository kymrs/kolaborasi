<?php $this->load->view('template/header'); ?>

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/backend/plugins/style-kwitansi-pu.css') ?>">
</head>

<body>
    <div class="container">
        <div class="canvas">
            <a class="btn btn-secondary btn-sm" onclick="history.back()" style="float: right"><i class="fas fa-chevron-left"></i>&nbsp;Back</a>
            <hr class="line-header">
            <header>
                <img class="header-image" src="<?= base_url('assets/backend/img/header.png') ?>" alt="">
                <div style="clear:both"></div>
                <div class="row">
                    <p>BUKTI TERIMA PEMBAYARAN</p>
                    <table>
                        <tr>
                            <td>Nomor Invoice</td>
                            <td>:</td>
                            <td>INVPU050001</td>
                        </tr>
                        <tr>
                            <td>Tanggal Invoice</td>
                            <td>:</td>
                            <td>10-05-2025</td>
                        </tr>
                        <tr>
                            <td>Tanggal Pembayaran</td>
                            <td>:</td>
                            <td>11-05-2025</td>
                        </tr>
                    </table>
                </div>
            </header>
            <div class="line-title">DATA PEMESANAN: <span>STATUS PEMBAYARAN</span></div>
            <div class="data-pemesan">
                <div class="row">
                    <div class="col-md-6">
                        <p>Kpd Yth.</p>
                        <h3>Bapak Nana Suryana</h3>
                    </div>
                    <div class="col-md-6 text-right">
                        <h1>DOWN PAYMENT</h1>
                    </div>
                </div>
            </div>
            <div class="line-title">RINCIAN PEMESANAN:</div>
            <div class="rincian-pemesanan">
                <table>
                    <tr>
                        <th>DESKRIPSI</th>
                        <th>JUMLAH</th>
                        <th>HARGA</th>
                        <th>TOTAL BAYAR</th>
                    </tr>
                    <tr>
                        <td>UMROH 4 SEPTEMBER 2025 (PLUS CITY TOUR THAIF)</td>
                        <td style="vertical-align: top">3 Pax</td>
                        <td style="vertical-align: top">Rp. 10.000.000</td>
                        <td style="vertical-align: top">Rp. 30.00.000</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td style="font-weight: bold">Total</td>
                        <td style="font-weight: bold">Rp. 30.000.000</td>
                    </tr>
                </table>
            </div>
            <img class="footer-image" src="<?= base_url('assets/backend/img/footer.png') ?>" alt="">

        </div>
    </div>

    <?php $this->load->view('template/footer'); ?>
    <?php $this->load->view('template/script'); ?>