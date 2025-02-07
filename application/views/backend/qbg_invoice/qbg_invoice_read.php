<?php $this->load->view('template/header'); ?>

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/backend/plugins/style-invoice.css') ?>">
</head>

<style>
    .detail-pemesanan .header {
        background-color: #00be63;
        padding: 3px 7px;
        color: #fff;
    }
</style>

<body>
    <div class="container">
        <div class="canvas">
            <a class="btn btn-secondary btn-sm" onclick="history.back()" style="float: right"><i class="fas fa-chevron-left"></i>&nbsp;Back</a>
            <hr class="line-header">
            <header>
                <div class="left-side">
                    <div class="logo">
                        <img src="<?= base_url('assets/backend/img/qubagift.png') ?>" alt="logo pengenumroh">
                    </div>
                </div>
                <div class="right-side">
                    <div class="email">qubahgift@gmail.com</div>
                    <div class="noHP">081290399933</div>
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
                        <?php
                        $total = 0; // Inisialisasi variabel total
                        $diskon = $invoice['diskon'];
                        foreach ($detail as $data) :
                            if ($diskon != 0) {
                                $total += $data['total'] - ($data['total'] * $diskon / 100);
                            } else {
                                $total += $data['total']; // Tambahkan harga ke total
                            }
                        ?>
                            <tr>
                                <td><?= $data['deskripsi'] ?></td>
                                <td class="jumlah"><?= $data['jumlah'] ?></td>
                                <td class="harga"><?= "Rp. " . number_format($data['harga'], 0, ',', '.') ?></td>
                                <td class="total"><?= "Rp. " . number_format($data['total'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="2" style="border-left-color: #fff; border-bottom-color: #fff"></td>
                            <td style="text-align: center;">Diskon</td>
                            <td style="text-align: center"><?= $invoice['diskon'] ?>%</td>
                        </tr>
                        <tr>
                            <td colspan="2" style="border-left-color: #fff; border-bottom-color: #fff"></td>
                            <th>Total</th>
                            <td style="text-align: center"><?= "Rp. " . number_format($total, 0, ',', '.') ?></td>
                        </tr>
                    </table>
                </div>
                <div class="metode-pembayaran">
                    <p>Metode Pembayaran</p>
                    <ol>
                        <?php foreach ($rekening as $data) : ?>
                            <li>Bank : <?= $data['nama_bank'] ?> <br> No. Rekening : <?= $data['no_rek'] ?> </li>
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

    <?php $this->load->view('template/footer'); ?>
    <?php $this->load->view('template/script'); ?>