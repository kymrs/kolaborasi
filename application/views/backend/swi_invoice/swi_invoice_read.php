<?php $this->load->view('template/header'); ?>

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/backend/plugins/style-invoice-swi.css') ?>">
</head>

<body>
    <a class="btn btn-secondary btn-sm btn-back" onclick="history.back()"><i class="fas fa-chevron-left"></i>&nbsp;Back</a>
    <div style="clear: both;"></div>
    <div class="container">
        <div class="canvas-bg-swi">
            <div class="header">
                <div class="logo">
                    <img src="<?= base_url('assets/backend/img/sobatwisata.png') ?>" alt="">
                </div>
                <div class="ellipse">
                    <img src="<?= base_url('assets/backend/img/ellipse-invoice-swi.png') ?>" alt="">
                    <div class="ellipse-content">
                        <h1>INVOICE</h1>
                        <table>
                            <tr>
                                <td>No</td>
                                <td>:</td>
                                <td><?= $invoice['kode_invoice'] ?></td>
                            </tr>
                            <tr>
                                <td>Tgl</td>
                                <td>:</td>
                                <td><?= date('d M Y', strtotime($invoice['tgl_invoice'])) ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="data-content">
                <div class="from-to-group">
                    <div class="from">
                        <h4>FROM</h4>
                        <p><?= $invoice['ctc_from'] ?></p>
                    </div>
                    <div class="to">
                        <h4>TO</h4>
                        <p><?= $invoice['ctc_to'] ?></p>
                    </div>
                </div>
                <div class="address">
                    <h4>ADDRESS</h4>
                    <p><?= $invoice['ctc_address'] ?></p>
                </div>
            </div>
            <div class="table-data">
                <div class="table-header">
                    <span style="width: 35%;">ITEM</span>
                    <span style="width: 15%;">QTY</span>
                    <span style="width: 15%;">DAY</span>
                    <span style="width: 24%;">PRICE</span>
                    <span style="width: 24%;">TOTAL</span>
                </div>
                <div class="data-container">
                    <?php foreach ($detail as $data) : ?>
                        <div class="data">
                            <span style="width: 35%;"><?= $data['item'] ?></span>
                            <span style="width: 15%;"><?= $data['qty'] ?></span>
                            <span style="width: 15%;"><?= $data['day'] ?></span>
                            <span style="width: 24%;"><?= 'Rp. ' . number_format($data['price'], 0, ',', '.') ?></span>
                            <span style="width: 24%;"><?= 'Rp. ' . number_format($data['total'], 0, ',', '.') ?></span>
                        </div>
                    <?php endforeach ?>
                </div>
                <div class="total">
                    <span style="float: right;"><?= 'Rp. ' . number_format($invoice['total'], 0, ',', '.') ?></span>
                    <span style="float: right; margin-right: 50px; font-weight: bold">TOTAL</span>
                    <div style="clear: both"></div>
                </div>
            </div>
            <div class="bank-details">
                <h4>BANK DETAILS</h4>
                <?php foreach ($rekening as $data) : ?>
                    <p><?= $data['nama_bank'] ?></p>
                    <p style="margin-bottom: 5px;"><?= $data['no_rek'] ?></p>
                <?php endforeach ?>
                <p> PT. Quick Project Indonesia</p>
            </div>
            <div class="footer">
                <img src="<?= base_url('assets/backend/img/footer-invoice-swi.png') ?>" alt="footer">
            </div>
        </div>
    </div>

    <?php $this->load->view('template/footer'); ?>
    <?php $this->load->view('template/script'); ?>