<?php $this->load->view('template/header'); ?>

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/backend/plugins/style-penawaran-swi.css') ?>">
</head>

<body>
    <a class="btn btn-secondary btn-sm btn-back" onclick="history.back()"><i class="fas fa-chevron-left"></i>&nbsp;Back</a>
    <div style="clear: both;"></div>
    <div class="container">
        <div class="canvas-bg-swi-penawaran">
            <img class="ellipse" src="<?= base_url('assets/backend/img/ellipse-invoice-swi.png') ?>" alt="ellipse">
            <div class="header">
                <div class="col">
                    <table>
                        <tr>
                            <td>No</td>
                            <td>:</td>
                            <td>2525875456</td>
                        </tr>
                        <tr>
                            <td>Tgl</td>
                            <td>:</td>
                            <td>12 Februari 1893</td>
                        </tr>
                        <tr>
                            <td>Perihal</td>
                            <td>:</td>
                            <td>Penawaran Harga Sewa Armada</td>
                        </tr>
                    </table>
                    <p>Kepada Yth,</p>
                    <div class="group">
                        <p><?= $data['name'] ?></p>
                        <p>Di</p>
                        <p>Tempat</p>
                    </div>
                </div>
                <div class="col">
                    <img src="<?= base_url('assets/backend/img/sobatwisata.png') ?>" alt="logo sobatwisata">
                </div>
            </div>
            <div class="content">
                <p>Dengan Hormat,</p>
                <p>Bersama surat ini kami sampaikan penawaran penyewaan Armada <span><?= $data['kendaraan'] ?></span> untuk kebutuhan perjalanan dari <span><?= $data['asal'] ?></span> ke <span><?= $data['tujuan'] ?></span> dengan rincian sebagai berikut:</p>
                <div class="table-data">
                    <div class="table-header">
                        <span style="width: 35%;">TANGGAL</span>
                        <span style="width: 15%; position: relative; right: 20px">JENIS</span>
                        <span style="width: 15%;">JUMLAH</span>
                        <span style="width: 24%;">HARGA</span>
                        <span style="width: 24%;">KETERANGAN</span>
                    </div>
                    <div class="data-container">
                        <?php foreach ($data_detail as $data) : ?>
                            <div class="data">
                                <span style="width: 37%;"><?= $data['tgl_keberangkatan'] ?></span>
                                <span style="width: 15%; position: relative; right: 25px"><?= $data['jenis'] ?></span>
                                <span style="width: 15%; position: relative; right: 5px"><?= $data['jumlah'] ?></span>
                                <span style="width: 24%;"><?= $data['harga'] ?></span>
                                <span style="width: 24%;"><?= $data['keterangan'] ?></span>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
            <div class="content-2">
                Note :
                <ul>
                    <li>DP Minimal 30%</li>
                    <li>Pelunasan H-5 sebelum hari keberangkatan</li>
                    <li>Pembayaran melalui transfer ke BCA PT Quick Project Indonesia dengan nomor rekening 713 172 8003</li>
                    <li>Harga dan ketersediaan unit tidak mengikat jika tidak melakukan pembayaran DP</li>
                </ul>
                Demikian Surat penawaran harga ini kami buat, kami tunggu kabar baik dari Bapak/Ibu, atas perhatiannya kamu ucapkan terimakasih.
                <div class="sign">
                    <p>Hormat kami,</p>
                    <br><br>
                    <p><b><u>Sobat Wisata</u></b></p>
                </div>
                <div style="clear: both;"></div>
            </div>
            <div class="footer">
                <img src="<?= base_url('assets/backend/img/footer-invoice-swi.png') ?>" alt="footer">
            </div>
        </div>
        <div class="canvas-bg-swi-penawaran">
            <img class="page-2" src="<?= base_url('assets/backend/img/ketentuan-sewa-swi.png') ?>" alt="page 2">
        </div>
    </div>

    <?php $this->load->view('template/footer'); ?>
    <?php $this->load->view('template/script'); ?>