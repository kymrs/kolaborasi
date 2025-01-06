<?php $this->load->view('template/header'); ?>

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/backend/plugins/style-invoice.css') ?>">
</head>

<body>
    <div class="container">
        <div class="canvas">
            <header>
                <div class="left-side">
                    <div class="logo">
                        <img src="<?= base_url('assets/backend/img/pengenumroh.png') ?>" alt="logo pengenumroh">
                    </div>
                </div>
                <div class="right-side">
                    <div class="email">pengenumroh@gmail.com</div>
                    <div class="noHP">089602984422</div>
                </div>
            </header>
            <hr class="line">
            <main>
                <div class="invoice">
                    <h3>INVOICE</h3>
                    <p>No. Invoice : 01234</p>
                </div>
                <div class="header-content">
                    <div class="left-side">
                        <h2>Kepada Yth.</h2>
                        <h1>Aldo Kurniawan</h1>
                        <p class="alamat">Alamat : Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsam, quasi?</p>
                    </div>
                    <div class="right-side">
                        <table>
                            <tr>
                                <td>Tanggal Invoice</td>
                                <td>:</td>
                                <td>02/01/2025</td>
                            </tr>
                            <tr>
                                <td>Tanggal Jatuh Tempo</td>
                                <td>:</td>
                                <td>02/01/2025</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="detail-pemesanan">
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
                        <tr>
                            <td>Cokelat Krikil</td>
                            <td class="jumlah">1 Kg</td>
                            <td class="harga">100.000</td>
                            <td class="total">100.000</td>
                        </tr>
                        <tr>
                            <td>Air Zam-zam</td>
                            <td class="jumlah">1 L</td>
                            <td class="harga">150.000</td>
                            <td class="total">150.000</td>
                        </tr>
                        <tr>
                            <td>Kacang Almond</td>
                            <td class="jumlah">2 Kg</td>
                            <td class="harga">200.000</td>
                            <td class="total">200.000</td>
                        </tr>
                        <tr>
                            <td>Kacang kacangan</td>
                            <td class="jumlah">5 Kg</td>
                            <td class="harga">400.000</td>
                            <td class="total">400.000</td>
                        </tr>
                        <tr>
                            <td colspan="2" style="border-left-color: #fff; border-bottom-color: #fff"></td>
                            <td style="text-align: center;">Diskon</td>
                            <td style="text-align: center">0</td>
                        </tr>
                        <tr>
                            <td colspan="2" style="border-left-color: #fff; border-bottom-color: #fff"></td>
                            <th>Total</th>
                            <td style="text-align: center">850.000</td>
                        </tr>
                    </table>
                </div>
                <div class="metode-pembayaran">
                    <p>Metode Pembayaran</p>
                    <ol>
                        <li>Bank : Bank Syariah Indonesia <br> No. Rekening : 7215671498 </li>
                        <li>Bank : Bank Central Asia <br> No. Rekening : 7131720452 </li>
                    </ol>
                    <p>Atas Nama : PT. Kolaborasi Para Sahabat </p>
                </div>
            </main>
        </div>

    </div>

    <?php $this->load->view('template/footer'); ?>
    <?php $this->load->view('template/script'); ?>