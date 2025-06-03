<?php $this->load->view('template/header'); ?>

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/backend/plugins/style-kwitansi-pu.css') ?>">
</head>

<body>
    <div class="container">
        <div class="canvas">
            <a class="btn btn-secondary btn-sm" onclick="history.back()" style="float: right; margin-left: 8px;"><i class="fas fa-chevron-left"></i>&nbsp;Back</a>
            <a class="btn btn-success btn-sm" href="#" id="btnPrintKwitansi" style="float: right;">
                <i class="fas fa-file-pdf"></i>&nbsp;Print
            </a>
            <hr class="line-header">
            <header>
                <img class="header-image" src="<?= base_url('assets/backend/img/header.png') ?>" alt="">
                <div style="clear:both"></div>
                <div class="row" style="margin-top: 125px;">
                    <p>BUKTI TERIMA PEMBAYARAN</p>
                    <table>
                        <tr>
                            <td>Nomor Invoice</td>
                            <td>:</td>
                            <td><?= $invoice['kode_invoice'] ?></td>
                        </tr>
                        <tr>
                            <td>Tanggal Invoice</td>
                            <td>:</td>
                            <td><?= $tgl_invoice ?></td>
                        </tr>
                        <tr>
                            <td>Tanggal Pembayaran</td>
                            <td>:</td>
                            <td>
                                <select class="js-example-basic-single" id="tgl_pembayaran" name="tgl_pembayaran" style="width: 200px;">
                                    <option value="" selected disabled>Pilih tanggal</option>
                                    <?php foreach ($kwitansi as $option) { ?>
                                        <option value="<?= $option['id'] ?>" data-id_invoice="<?= $option['id_invoice'] ?>"><?= $option['tanggal_pembayaran'] ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>
            </header>
            <div class="line-title">DATA PEMESANAN: <span>STATUS PEMBAYARAN</span></div>
            <div class="data-pemesan">
                <div class="row">
                    <div class="col-md-6">
                        <p>Kpd Yth.</p>
                        <h3><?= $invoice['ctc_nama'] ?></h3>
                    </div>
                    <div class="col-md-6 text-right">
                        <h1 style="text-transform: uppercase;" id="status_pembayaran">Status Pembayaran</h1>
                    </div>
                </div>
            </div>
            <div class="line-title">RINCIAN PEMESANAN:</div>
            <div class="rincian-pemesanan">
                <table style="width:100%;">
                    <!-- Tambahkan tbody khusus untuk detail kwitansi -->
                    <tbody id="detail_kwitansi"></tbody>
                </table>
            </div>
            <img class="footer-image" src="<?= base_url('assets/backend/img/footer.png') ?>" alt="">
        </div>
    </div>

    <?php $this->load->view('template/footer'); ?>
    <?php $this->load->view('template/script'); ?>

    <script>
        $('.js-example-basic-single').select2();

        // Fungsi untuk format tanggal Indonesia
        function formatTanggalIndo(tgl) {
            const bulanIndo = [
                "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                "Juli", "Agustus", "September", "Oktober", "November", "Desember"
            ];
            if (!tgl) return '';
            const parts = tgl.split('-');
            if (parts.length !== 3) return tgl;
            return parseInt(parts[2], 10) + ' ' + bulanIndo[parseInt(parts[1], 10) - 1] + ' ' + parts[0];
        }

        $('#tgl_pembayaran').on('change', function() {
            $('#detail_kwitansi').empty(); // Clear previous detail_kwitansi
            var selectedValue = $(this).val();
            var idInvoice = $('#tgl_pembayaran option:selected').data('id_invoice');
            const totalTagihan = $('#total_tagihan').data('total');
            let total = 0;

            // console.log('Selected value:', selectedValue);

            // Update href tombol Print
            var printUrl = "<?= base_url('pu_invoice/generate_pdf_kwitansi/') ?>" + selectedValue;
            $('#btnPrintKwitansi').attr('href', printUrl);

            $.ajax({
                url: "<?= base_url('/pu_invoice/get_kwitansi') ?>",
                type: "POST",
                data: {
                    id: selectedValue,
                    id_invoice: idInvoice
                },
                dataType: "json",
                success: function(data) {
                    $('#status_pembayaran').html(data.kwitansi.status_pembayaran);

                    var detail_kwitansi = '';
                    if (Array.isArray(data.detail)) {
                        data.detail.forEach(function(item) {
                            total = Number(item.nominal_dibayar);
                        });
                        detail_kwitansi += `
                                <tr>
                                    <td style="text-align: left; width: 150px; font-weight: bold; border:none">Banyak Uang</td>
                                    <td style="text-align: center; width: 20px; font-weight: bold; border:none">:</td>
                                    <td colspan="2" style="text-align: left; font-weight: bold; border:none">Rp. ${Number(total).toLocaleString('id-ID')}</td>
                                </tr>
                            `;
                    }
                    // Tambahkan row total nominal di paling bawah
                    detail_kwitansi += `
                        <tr>
                            <td style="text-align: left; vertical-align:top; font-weight: bold; border:none">Untuk Pembayaran</td>
                            <td style="text-align: center; vertical-align:top; font-weight: bold; border:none">:</td>
                            <td colspan="2" style="text-align: left; font-weight: bold; border:none">
                            ${data.kwitansi.keterangan ? data.kwitansi.keterangan : '-'}
                            </td>
                        </tr>
                    `;

                    $('#detail_kwitansi').append(detail_kwitansi);
                    // console.log('total nominal:', data.total_nominal_dibayar);
                    // console.log('total tagihan:', totalTagihan);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });
    </script>