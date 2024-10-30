<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view('template/header'); ?>
    <!-- Include Bootstrap CSS -->
    <!-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> -->
    <style>
        body .container {
            font-family: Arial, Helvetica, sans-serif;
            padding: 0;
            color: #333;
        }

        .form-container {
            max-width: 1080px;
            margin: 15px auto;
            padding: 25px;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            height: 800px;
        }

        /* header */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            font-size: 1.8rem;
        }

        header img {
            width: 270px;
        }

        .header-content {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
        }

        .header-content table tr td {
            padding: 0 0 20px;
            text-align: left;
        }

        .header-content table tr td:nth-child(2) {
            padding: 0 5px 20px 10px;
        }

        .watermark {
            border: 7px solid #ED4722;
            display: inline-block;
            color: #ED4722;
            border-radius: 15px;
            padding: 0 10px;
            opacity: 0.1;
            box-sizing: border-box;
            font-size: 5rem;
            position: absolute;
            left: 45%;
            transform: rotate(-30deg);
        }

        @media (max-width: 1286px) {
            .form-container {
                width: 95%;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="d-flex justify-content-end mb-3" style="margin-right: 19px">
            <a class="btn btn-danger btn-sm mr-2" id="paymentBtn" data-toggle="modal" data-target="#paymentModal"><i class="fas fa-file-pdf"></i>&nbsp;PDF</a>
            <a class="btn btn-primary btn-sm mr-2" id="paymentBtn" data-toggle="modal" data-target="#paymentModal"><i class="fas fa-print"></i>&nbsp;Print</a>
            <a class="btn btn-secondary btn-sm" onclick="history.back()"><i class="fas fa-chevron-left"></i>&nbsp;Back</a>
        </div>
        <div class="form-container">
            <header>
                <h1>Tanda Terima</h1>
                <div>
                    <img src="<?= base_url() ?>/assets/backend/img/logo_new.png" alt="Logo pengenumroh.com">
                </div>
            </header>
            <div class="header-content">
                <div>
                    <table>
                        <tr>
                            <td>Pengirim</td>
                            <td>:</td>
                            <td>Aldo Rakha</td>
                        </tr>
                        <tr>
                            <td>Kepada</td>
                            <td>:</td>
                            <td>Tn. Yanto</td>
                        </tr>
                    </table>
                </div>
                <div>
                    <p style="text-align: right;">21/22/2024</p>
                    <table>
                        <tr>
                            <td>No</td>
                            <td>:</td>
                            <td>PU00001</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="watermark">Diterima</div>

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
                        <td>Dokumen &amp; Perlengkapan Jamaah</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>5 pax</td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            Rincian:
                            <br>2 Invoice Pembayaran
                        </td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Include jQuery and Bootstrap JS -->
    <?php $this->load->view('template/footer'); ?>
    <?php $this->load->view('template/script'); ?>
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->

    <script>
    </script>
</body>

</html>