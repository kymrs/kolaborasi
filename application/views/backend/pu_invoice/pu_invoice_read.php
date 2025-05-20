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
            <a class="btn btn-secondary btn-sm" onclick="history.back()" style="float: right"><i class="fas fa-chevron-left"></i>&nbsp;Back</a>
            <hr class="line-header">
            <header>
                <img class="header-image" src="<?= base_url('assets/backend/img/header.png') ?>" alt="">
                <div style="clear:both"></div>
                <div class="row">
                    <div class="left-side">
                        <h3>PT. KOLABORASI PARA SAHABAT</h3>
                    </div>
                    <div class="right-side">
                        <h1>INVOICE</h1>
                        <table>
                            <tr>
                                <td>No</td>
                                <td>:</td>
                                <td>INVPU050001</td>
                            </tr>
                            <tr>
                                <td>Tanggal Invoice</td>
                                <td>:</td>
                                <td>10/05/2025</td>
                            </tr>
                            <tr>
                                <td>Tanggal Jatuh Tempo</td>
                                <td>:</td>
                                <td>17/05/2025</td>
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
                        <p class="address">Alamat : Kp. Jatake RT 002 RW 001 Jatake, Jatiuwung, Tangerang</p>
                    </div>
                    <div class="right-side">
                        <h1>30.000.000</h1>
                    </div>
                </div>
            </div>
            <div class="line-title">Data Jamaah :<span>Detail Pesanan</span></div>
            <div class="data-jamaah">
                <div class="row">
                    <div class="left-side">
                        <div class="jamaah">
                            <ol>
                                <li data-list="ordered"><span class="ql-ui" contenteditable="false"></span>Renaldo</li>
                                <li data-list="ordered"><span class="ql-ui" contenteditable="false"></span>Jamilah</li>
                                <li data-list="ordered"><span class="ql-ui" contenteditable="false"></span>Setiabudi</li>
                            </ol>
                        </div>
                    </div>
                    <div class="right-side">
                        <div class="detail-pesanan">
                            Umroh 4 September 2025<br>
                            Plus City Tour Thaif - 9 Hari<br>
                            Kamar : Triple<br>
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
                    <tr>
                        <td>DP Umroh 4 September 2025</td>
                        <td>5</td>
                        <td>10.000.000</td>
                        <td>30.000.000</td>
                    </tr>
                    <tr>
                        <td style="border-color: #fff;"></td>
                        <td style="border-bottom-color: #fff;"></td>
                        <td style="text-align: left; font-weight: bold">Total</td>
                        <td>tes</td>
                    </tr>
                </table>
            </div>
            <div class="metode-pembayaran">
                <p>Metode Pembayaran</p>
                <ol>
                    <li>Bank : <span>Bank Syariah Indonesia</span><br>No Rekening : <span>7215671498</span></li>
                </ol>
                <p>a/n : PT. Kolaborasi Para Sahabat</p>
            </div>
            <img class="footer-image" src="<?= base_url('assets/backend/img/footer.png') ?>" alt="">
        </div>
    </div>

    <?php $this->load->view('template/footer'); ?>
    <?php $this->load->view('template/script'); ?>