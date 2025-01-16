<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Tanda Terima <?= $data->nomor ?></title>
    <link rel="icon" href="https://portal.pengenumroh.com/assets/icon/favicon.png" type="image/jpg">

    <!-- Custom fonts and styles -->
    <link href="https://portal.pengenumroh.com/assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://portal.pengenumroh.com/assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://portal.pengenumroh.com/assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <style>
        /* Styling header, footer, and content */
        .header,
        .footer {
            width: 100%;
            text-align: center;
        }

        .header-image,
        .footer-image {
            width: 100%;
        }

        /* Menambahkan margin pembatas di konten agar tidak mengenai header dan footer */
        .content {
            margin-top: 300px;
            /* Jarak dari header */
            margin-bottom: 200px;
            /* Jarak dari footer */
        }

        /* CSS khusus untuk print */
        @media print {

            /* Mengatur margin untuk setiap halaman */
            body {
                margin-top: 200px;
                margin-bottom: 100px;
            }

            /* Header dan footer tetap di posisi yang sama */
            .header,
            .footer {
                position: fixed;
                left: 0;
                right: 0;
                width: 100%;
            }

            .header {
                top: 0;
            }

            .footer {
                bottom: 0;
            }

            /* Jarak di konten agar tidak mengenai header dan footer */
            .content,
            .page-break+.content {
                margin-top: 100px;
                margin-bottom: 200px;
            }

            /* Memastikan konten baru selalu dimulai di bawah header pada halaman baru */
            .page-break {
                page-break-before: always;
                margin-top: 300px;
            }
        }
    </style>
</head>

<body style="font-family: Courier; font-size: 32px">
    <!-- Header Tetap di Atas -->
    <div class="header">
        <img src="<?= base_url() ?>/assets/backend/img/header.png" alt="Header Image" class="header-image">
    </div>

    <!-- Konten Utama -->
    <div class="content">
        <section class="invoice">
            <br />
            <div class="row">
                <div class="col-sm-8 invoice-col">
                    <table>
                        <tr>
                            <td>Pengirim</td>
                            <td>:</td>
                            <td><?= $data->nama_pengirim ?></td>
                        </tr>
                        <tr>
                            <td>Kepada Yth</td>
                            <td>:</td>
                            <td><?= $data->nama_penerima ?></td>
                        </tr>
                    </table>
                </div>

                <div class="col-sm-4" style="text-align: right">
                    <p style="margin: 0"><?= date('d/m/Y', strtotime($data->tanggal)) ?></p>
                    <table style="float: right">
                        <tr>
                            <td>No</td>
                            <td>:</td>
                            <td><?= $data->nomor ?></td>
                        </tr>
                    </table>
                </div>
            </div>
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
                                <td><?= $data->barang ?></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><?= $data->qty ?></td>
                            </tr>
                            <tr>
                                <td colspan="4">
                                    <?= nl2br(preg_replace('/(\d+\.)/', '<br>$1', $data->keterangan)); ?>
                                </td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="page-break"></div> <!-- Menambah page break untuk halaman berikutnya -->

            <div class="content">
                <!-- Konten di halaman kedua -->
                <div class="row">
                    <div class="col-12 text-center">
                        <p class="lead" style="font-size: 32px">Bukti Serah Terima:</p>
                        <img src="<?= base_url() ?>/assets/backend/document/tanda_terima_pu/<?= $data->foto ?>" height="300px">
                    </div>
                </div><br />

                <div class="row">
                    <div class="col-12 text-center">
                        <p class="lead font-weight-bold">PENGENUMROH.COM <br />
                            YOUR TRUSTED UMRAH & HAJJ MARKETPLACE</p>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Footer Tetap di Bawah -->
    <div class="footer">
        <img src="<?= base_url() ?>/assets/backend/img/footer.png" alt="Footer Image" class="footer-image">
    </div>

    <style>
        /* Watermark */
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

    <!-- Watermark Text -->
    <div class="watermark">Diterima</div>

    <!-- Script to Print Automatically -->
    <script>
        window.addEventListener("load", () => window.print());
    </script>
</body>

</html>