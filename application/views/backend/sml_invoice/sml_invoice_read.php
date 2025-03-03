<?php $this->load->view('template/header'); ?>

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/backend/plugins/style-invoice-sml.css') ?>">
</head>

<body>
    <!-- <a class="btn btn-secondary btn-sm btn-back" onclick="history.back()"><i class="fas fa-chevron-left"></i>&nbsp;Back</a> -->
    <div style="clear: both;"></div>
    <div class="container">
        <div class="canvas-bg-sml">
            <header>
                <div class="col">
                    <div class="logo">
                        <img src="<?= base_url('assets/backend/img/sml.png') ?>" alt="SAHABAT MULTI LOGISTIK">
                    </div>
                </div>
                <div class="col">
                    <h1>INVOICE</h1>
                </div>
            </header>
            <div class="content">
                <div class="col">
                    <p> Jl. Kp. Tunggilis RT 001 RW 007 Situsari Kec. Cileungsi Kab. Bogor</p>
                    <p>Ditujukan Kepada:</p>
                    <p>PT. Mandiri Cipta Sejahtera</p>
                    <p>Ruko Niaga Citra Grand Blok R9 3-6</p>
                </div>
                <div class="col">
                    <table>
                        <tr>
                            <td>No. Invoice</td>
                            <td>:</td>
                            <td><?= $invoice['kode_invoice'] ?></td>
                        </tr>
                        <tr>
                            <td>Tanggal</td>
                            <td>:</td>
                            <td><?= date('d-m-Y', strtotime($invoice['tgl_invoice'])) ?></td>
                        </tr>
                        <tr>
                            <td>Jatuh Tempo</td>
                            <td>:</td>
                            <td><?= date('d-m-Y', strtotime($invoice['tgl_tempo'])) ?></td>
                        </tr>
                        <tr>
                            <td>Metode</td>
                            <td>:</td>
                            <td><?= $invoice['metode'] ?></td>
                        </tr>
                    </table>
                    <div style="clear: both"></div>
                </div>
            </div>
            <div class="table-transaksi">
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Deskripsi</th>
                            <th>Nopol</th>
                            <th>Tipe</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($details as $detail) { ?>
                            <tr>
                                <td>1</td>
                                <td><?= $detail->deskripsi ?></td>
                                <td><?= $detail->nopol ?></td>
                                <td><?= $detail->tipe ?></td>
                                <td style="padding-right: 5px;"><?= number_format($detail->total, 0, ',', '.') ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <?php if ($invoice['tax'] > 0) { ?>
                        <tr>
                            <td style="border-color: white"></td>
                            <td style="border-color: white"></td>
                            <td style="background-color: white; position: relative; top: 5px"></td>
                            <td>PPN</td>
                            <td style="padding-right: 5px;"><?= number_format($invoice['tax'], 0, ',', '.') ?></td>
                        </tr>
                    <?php } ?>
                    <?php if ($invoice['diskon']) { ?>
                        <tr>
                            <td style="border-color: white"></td>
                            <td style="border-color: white"></td>
                            <td style="background-color: white; position: relative; top: 5px"></td>
                            <td>Diskon</td>
                            <td style="padding-right: 5px;"><?= number_format($invoice['diskon'], 0, ',', '.') ?></td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td style="border-color: white"></td>
                        <td style="border-color: white"></td>
                        <td style="background-color: white; position: relative; top: 5px"></td>
                        <td>Total</td>
                        <td style="padding-right: 5px;"><?= number_format($invoice['total'] + $invoice['tax'] - $invoice['diskon'], 0, ',', '.') ?></td>
                    </tr>
                </table>
            </div>
            <div class="payment">
                <p>Pembayaran Transfer Melalui</p>
                <p>BCA Cab. Cibodas</p>
                <p>No. Rekening : 7131720380</p>
                <p>a/n PT. Sahabat Multi Logistik</p>
            </div>
            <div class="approval">
                <table>
                    <tr>
                        <td>PT. SAHABAT MULTI LOGISTIK</td>
                    </tr>
                    <tr>
                        <td>
                            <img src="<?= base_url('assets/backend/img/capsml.png') ?>" alt="" class="cap">
                            <img src="<?= base_url('assets/backend/img/ttdsml.png') ?>" alt="" class="ttd">
                        </td>
                    </tr>
                    <tr>
                        <td>M. Charles Manalu</td>
                    </tr>
                </table>
                <div style="clear: both"></div>
            </div>
        </div>
    </div>

    <?php $this->load->view('template/footer'); ?>
    <?php $this->load->view('template/script'); ?>