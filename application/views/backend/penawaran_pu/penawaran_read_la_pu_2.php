<?php $this->load->view('template/header'); ?>

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/backend/plugins/style-penawaran.css') ?>">
</head>

<body>
    <div class="container">
        <div class="canvas">
            <div class="header-logo">
                <img src="<?= base_url('assets/backend/img/header.png') ?>" alt="header pengenumroh">
            </div>
            <main>
                <div class="header-content">
                    <h1>PENAWARAN</h1>
                    <div class="row">
                        <table>
                            <tr>
                                <td>No</td>
                                <td>:</td>
                                <td><?= $penawaran->no_pelayanan ?></td>
                            </tr>
                            <tr>
                                <td>Tanggal Dokumen</td>
                                <td>:</td>
                                <td><?= date("d/m/Y", strtotime($penawaran->created_at)) ?></td>
                            </tr>
                            <tr>
                                <td>Berlaku s.d.</td>
                                <td>:</td>
                                <td><?= date("d/m/Y", strtotime($penawaran->tgl_berlaku)) ?></td>
                            </tr>
                            <tr>
                                <td>Produk</td>
                                <td>:</td>
                                <td><?= $penawaran->produk ?></td>
                            </tr>
                            <tr>
                                <td>Kepada</td>
                                <td>:</td>
                                <td>Mr. <?= $penawaran->pelanggan ?></td>
                            </tr>
                        </table>
                        <div class="qr-code">
                            <img src="<?= base_url('assets/backend/document/qrcode/qr-PU240902.png') ?>" alt="qrcode" class="img-qrcode">
                            <img src="<?= base_url('assets/backend/img/favicon-pu.png') ?>" alt="pengenumroh-logo" class="pu-logo">
                        </div>
                    </div>
                </div>
                <div class="layanan">
                    <div class="header">
                        <h1>LAYANAN</h1>
                    </div>
                    <div class="row">
                        <div class="left-side">
                            <p>Deskripsi :</p>
                            <p><?= $penawaran->deskripsi ?></p>
                        </div>
                        <div class="right-side">
                            <table>
                                <tr>
                                    <td>Keberangkatan</td>
                                    <td>:</td>
                                    <td><?= $penawaran->tgl_keberangkatan ?></td>
                                </tr>
                                <tr>
                                    <td>Durasi</td>
                                    <td>:</td>
                                    <td><?= $penawaran->durasi ?> <span>Hari</span></td>
                                </tr>
                                <tr>
                                    <td>Berangkat Dari</td>
                                    <td>:</td>
                                    <td><?= $penawaran->berangkat_dari ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="layanan-list">
                        <div class="left-side">
                            <p>Layanan Termasuk :</p>
                            <?= $penawaran->layanan_trmsk ?>
                        </div>
                        <div class="right-side">
                            <p>Layanan Tidak Termasuk :</p>
                            <?= $penawaran->layanan_tdk_trmsk ?>
                        </div>
                    </div>
                </div>
                <div class="extra-content">
                    <table>
                        <tr>
                            <td>Hotel Makkah<i class="fas fa-solid fa-star"></i><i class="fas fa-solid fa-star"></i><i class="fas fa-solid fa-star"></i><i class="fas fa-solid fa-star"></i><i class="fas fa-solid fa-star"></i></td>
                            <td>:</td>
                            <td>Sofwah Orchid</td>
                        </tr>
                        <tr>
                            <td>Hotel Madinah<i class="fas fa-solid fa-star"></i><i class="fas fa-solid fa-star"></i><i class="fas fa-solid fa-star"></i><i class="fas fa-solid fa-star"></i><i class="fas fa-solid fa-star"></i></td>
                            <td>:</td>
                            <td>Taiba Front</td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-solid fa-plane"></i>Keberangkatan</td>
                            <td>:</td>
                            <td>Direct Saudia Airlines SV817</td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-solid fa-plane"></i>Kepulangan</td>
                            <td>:</td>
                            <td>Direct Saudia Airlines SV826</td>
                        </tr>
                    </table>
                </div>
                <div class="harga-paket">
                    <div class="header">
                        <h1>HARGA PAKET</h1>
                    </div>
                    <table>
                        <tr>
                            <td>Biaya</td>
                            <td>:</td>
                            <td><?= "Rp. " . number_format(preg_replace('/\D/', '', $penawaran->biaya), 0, ',', '.') ?></td>
                        </tr>
                    </table>
                </div>

                <div class="layanan-pasti">
                    <div class="header">
                        <h1>LAYANAN PASTI</h1>
                    </div>
                    <div class="row">
                        <div class="left-side">
                            <ol>
                                <li>Konsultasi Gratis </li>
                                <li>Gratis Bantuan Pembuatan Paspor</li>
                                <li>Gratis Antar Dokumen & Perlengkapan</li>
                                <li>Gratis Pendampingan Manasik</li>
                            </ol>
                        </div>
                        <div class="right-side">
                            <ol>
                                <li>Gratis Handling Keberangkatan</li>
                                <li>Gratis Handling Kepulangan</li>
                                <li>Jaminan Pasti Berangkat</li>
                                <li>Garansi 100% Uang Kembali Apabila Travel Gagal Memberangkatkan</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </main>
            <div class="footer-logo">
                <img src="<?= base_url('assets/backend/img/footer.png') ?>" alt="footer pengenumroh">
            </div>
        </div>
    </div>
    <div class="container">
        <div class="canvas2">
            <div class="header-logo">
                <img src="<?= base_url('assets/backend/img/header.png') ?>" alt="header pengenumroh">
            </div>
            <main>
                <table class="rundown">
                    <tr>
                        <th>Hari</th>
                        <th>Tanggal</th>
                        <th>Kegiatan</th>
                    </tr>
                    <tr>
                        <td>Kesatu</td>
                        <td>25/01/2025</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Kesatu</td>
                        <td>25/01/2025</td>
                        <td></td>
                    </tr>
                </table>
            </main>
            <div class="footer-logo">
                <img src="<?= base_url('assets/backend/img/footer.png') ?>" alt="footer pengenumroh">
            </div>
        </div>
    </div>

    <?php $this->load->view('template/footer'); ?>
    <?php $this->load->view('template/script'); ?>