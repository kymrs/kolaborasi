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
                                <td><?= $penawaran['no_pelayanan'] ?></td>
                            </tr>
                            <tr>
                                <td>Tanggal Dokumen</td>
                                <td>:</td>
                                <td><?= date("d/m/Y", strtotime($penawaran['created_at'])) ?></td>
                            </tr>
                            <tr>
                                <td>Berlaku s.d.</td>
                                <td>:</td>
                                <td><?= date("d/m/Y", strtotime($penawaran['tgl_berlaku'])) ?></td>
                            </tr>
                            <tr>
                                <td>Produk</td>
                                <td>:</td>
                                <td><?= $penawaran['produk'] ?></td>
                            </tr>
                            <tr>
                                <td>Kepada</td>
                                <td>:</td>
                                <td><?= $penawaran['pelanggan'] ?></td>
                            </tr>
                        </table>
                        <div class="qr-code">
                            <img src="data:image/png;base64, <?= $qr_code ?>" alt="qrcode" class="img-qrcode">
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
                            <p><?= $penawaran['deskripsi'] ?></p>
                        </div>
                        <div class="right-side">
                            <table>
                                <tr>
                                    <td>Keberangkatan</td>
                                    <td>:</td>
                                    <td><?= $this->M_penawaran_pu->getTanggal($penawaran['tgl_keberangkatan']) ?></td>
                                </tr>
                                <tr>
                                    <td>Durasi</td>
                                    <td>:</td>
                                    <td><?= $penawaran['durasi'] ?> <span>Hari</span></td>
                                </tr>
                                <tr>
                                    <td>Berangkat Dari</td>
                                    <td>:</td>
                                    <td><?= $penawaran['berangkat_dari'] ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="layanan-list">
                        <div class="left-side">
                            <p>Layanan Termasuk :</p>
                            <ol>
                                <?php foreach ($layanan_termasuk as $data) : ?>
                                    <li><?= $data['nama_layanan'] ?></li>
                                <?php endforeach ?>
                            </ol>
                        </div>
                        <div class="right-side">
                            <p>Layanan Tidak Termasuk :</p>
                            <ol>
                                <?php foreach ($layanan_tidak_termasuk as $data) : ?>
                                    <?php if ($data['id_layanan'] == 9) : ?>
                                        <?php $nominal =  preg_replace('/\D/', '', $data['is_active']) ?>
                                        <?php if ($nominal) : ?>
                                            <li><?= $data['nama_layanan'] ?> <?= "Rp " . number_format($nominal, 0, ',', '.') ?></li>
                                        <?php else : ?>
                                            <li><?= $data['nama_layanan'] ?></li>
                                        <?php endif ?>
                                    <?php else : ?>
                                        <li><?= $data['nama_layanan'] ?></li>
                                    <?php endif ?>
                                <?php endforeach ?>
                            </ol>
                            <div class="extra-content">
                                <table>
                                    <?php foreach ($hotel as $data) : ?>
                                        <?php
                                        $rating = '';
                                        if ($data['rating'] == 5) {
                                            $rating = '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>';
                                        } else if ($data['rating'] == 4) {
                                            $rating = '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>';
                                        } else if ($data['rating'] == 3) {
                                            $rating = '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>';
                                        } else if ($data['rating'] == 2) {
                                            $rating = '<i class="fas fa-star"></i><i class="fas fa-star"></i>';
                                        } else if ($data['rating'] == 1) {
                                            $rating = '<i class="fas fa-star"></i>';
                                        }
                                        ?>
                                        <tr>
                                            <td>Hotel <?= $data['kota'] ?></td>
                                            <td><?= $rating ?></td>
                                            <td>:</td>
                                            <td><?= $data['nama_hotel'] ?></td>
                                        </tr>
                                    <?php endforeach ?>
                                    <tr>
                                        <td><i class="fas fa-solid fa-plane"></i>Keberangkatan</td>
                                        <td></td>
                                        <td>:</td>
                                        <td><?= $penawaran['keberangkatan'] ?></td>
                                    </tr>
                                    <tr>
                                        <td><i class="fas fa-solid fa-plane"></i>Kepulangan</td>
                                        <td></td>
                                        <td>:</td>
                                        <td><?= $penawaran['kepulangan'] ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="harga-paket">
                    <div class="header">
                        <h1>HARGA PAKET</h1>
                    </div>
                    <table>
                        <tr>
                            <td>Quad</td>
                            <td>:</td>
                            <td><?= "Rp. " . number_format(preg_replace('/\D/', '', $penawaran['pkt_quad']), 0, ',', '.') ?></td>
                        </tr>
                        <tr>
                            <td>Triple</td>
                            <td>:</td>
                            <td><?= "Rp. " . number_format(preg_replace('/\D/', '', $penawaran['pkt_triple']), 0, ',', '.') ?></td>
                        </tr>
                        <tr>
                            <td>Double</td>
                            <td>:</td>
                            <td><?= "Rp. " . number_format(preg_replace('/\D/', '', $penawaran['pkt_double']), 0, ',', '.') ?></td>
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
                    <?php foreach ($rundown as $data) : ?>
                        <tr>
                            <td><?= $data['hari'] ?></td>
                            <td><?= date('d/m/Y', strtotime($data['tanggal'])) ?></td>
                            <td><?= $data['kegiatan'] ?></td>
                        </tr>
                    <?php endforeach ?>
                </table>
            </main>
            <div class="footer-logo">
                <img src="<?= base_url('assets/backend/img/footer.png') ?>" alt="footer pengenumroh">
            </div>
        </div>
    </div>

    <?php $this->load->view('template/footer'); ?>
    <?php $this->load->view('template/script'); ?>