<?php $this->load->view('template/header'); ?>

<?php
// Helper function untuk format tanggal bahasa Indonesia
function format_date_indo($date_str) {
    $bulan = array(
        1 => 'Januari',
        2 => 'Februari', 
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    );
    
    $date = new DateTime($date_str);
    $hari = $date->format('d');
    $bulan_num = (int)$date->format('m');
    $tahun = $date->format('Y');
    
    return $hari . ' ' . $bulan[$bulan_num] . ' ' . $tahun;
}
?>

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/backend/plugins/style-penawaran-swi.css') ?>">
</head>

<body>
    <a class="btn btn-primary btn-sm btn-back" onclick="history.back()"><i class="fas fa-chevron-left"></i>&nbsp;Back</a>
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
                            <td><?= $data['kode'] ?></td>
                        </tr>
                        <tr>
                            <td>Tgl</td>
                            <td>:</td>
                            <td><?= format_date_indo($data['created_at']) ?></td>
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
                        <?php foreach ($data_detail as $detail) : ?>
                            <div class="data"> 
                                <span style="width: 37%;"><?= date('d F Y', strtotime($detail['tgl_keberangkatan'])) ?></span>
                                <span style="width: 15%; position: relative; right: 25px"><?= $detail['jenis'] ?></span>
                                <span style="width: 15%; position: relative; right: 5px"><?= $detail['jumlah'] ?></span>
                                <span style="width: 24%;"><?= number_format($detail['harga'], 0, ',', '.') ?></span>
                                <span style="width: 24%;"><?= $detail['keterangan'] ?></span>
                            </div>
                        <?php endforeach ?>
                        <div class="data" style="font-weight: bold;">
                            <span style="width: 37%;"></span>
                            <span style="width: 15%; position: relative; right: 25px"></span>
                            <span style="width: 15%; position: relative; right: 5px"></span>
                            <span style="width: 24%;">Total</span>
                            <span style="width: 24%;"><?php 
                                $total = 0;
                                foreach ($data_detail as $detail) {
                                    $total += $detail['jumlah'] * $detail['harga'];
                                }
                                echo number_format($total, 0, ',', '.');
                            ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-2">
                Note :
                <ul>
                    <li>DP Minimal 30%</li>
                    <li>Pelunasan H-5 sebelum hari keberangkatan</li>
                    <li>Harga dan ketersediaan unit tidak mengikat jika tidak melakukan pembayaran DP</li>
                    <li>Pembayaran melalui transfer dengan nomor rekening :</li>
                    <li style="list-style: none;">BCA</li>
                    <li style="list-style: none;">713 225 2222 - SOBAT WISATA DUNIA PT</li>
                    <li style="list-style: none;">MANDIRI</li>
                    <li style="list-style: none;">127 001 463 6029 - PT SOBAT WISATA DUNIA</li>
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