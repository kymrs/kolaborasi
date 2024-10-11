<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Tanda Terima <?= $nomor ?></title>
    <link rel="icon" href="<?php echo base_url(); ?>assets/icon/favicon.png" type="image/jpg">

    <!-- Custom fonts for this template-->
    <link href="<?php echo base_url(); ?>assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?php echo base_url(); ?>assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body style="font-family: Courier; font-size: 32px">
    <div class="wrapper container">

        <section class="invoice">
            <br />
            <div class="row">
                <!-- <div class="col-12">
                    <h2 class="page-header">
                        <u style='text-decoration-style: dotted;' class="float-left">Tanda Terima</u>
                        <img class="float-right" width="45%" src="<? //= base_url('assets/icon/logo_new.png'); 
                                                                    ?>">
                    </h2>
                </div> -->
                <div style="height: 100px; line-height: 100px; text-align: center;">
                    <u style='text-decoration-style: dotted;' class="float-left">Tanda Terima</u>
                    <img class="float-right" width="45%" src="<?= base_url('assets/backend/img/PU_NEW.png'); ?>">
                </div>
            </div>

            <div class="row invoice-info">
                <div class="col-sm-8 invoice-col">
                    Pengirim: <?= $pengirim; ?><br>
                    Kepada Yth: <?= $title . " " . $penerima; ?>
                </div>

                <!-- <div class="col-sm-2 invoice-col">
                </div> -->

                <div class="col-sm-4 invoice-col" style='text-align: right'>
                    <?= $tanggal ?><br>
                    No: <?= $nomor ?><br>
                </div>
            </div>
            <br />
            <!-- <div class="row">
                <div class="col-sm-8">
                    Kepada Yth: <? //= $title . " " . $penerima; 
                                ?>
                </div>
            </div> -->
            <br />
            <div class="row">
                <div class="col-12 table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Uraian</th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?= $barang; ?></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><?= $qty; ?></td>
                            </tr>
                            <tr>
                                <td colspan="4">
                                    <?php
                                    $list = explode("\n", $keterangan);
                                    for ($i = 0; $i < count($list); $i++) {
                                        echo $list[$i] . '<br/>';
                                    }
                                    ?>
                                </td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>

            <div class="row">
                <div class="col-12 text-center">
                    <p class="lead" style="font-size: 32px">Bukti Serah Terima:</p>
                    <img src="<?= base_url('assets/img/') . $foto ?>" height="300px">
                </div>
            </div><br />
            <div class="row">
                <div class="col-12 text-center">
                    <p class="lead font-weight-bold">PENGENUMROH.COM <br />
                        YOUR TRUSTED UMRAH & HAJJ MARKETPLACE</p>
                </div>
            </div>
        </section>

    </div>
    <style>
        .watermark {
            color: orange;
            font-size: 80px;
            position: absolute;
            opacity: 0.1;
            transform: rotate(-30deg);
            top: 35%;
            left: 30%;
            border: 10px solid;
            border-radius: 25px;
        }
    </style>
    <?= ($foto == '' ? '' : '<div class="watermark">Diterima</div>') ?>

    <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
    <script>
        window.addEventListener("load", window.print());
    </script>
</body>

</html>