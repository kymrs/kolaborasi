<?php $this->load->view('template/header'); ?>

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/backend/plugins/style-invoice-sml.css') ?>">
</head>

<body>
    <a class="btn btn-secondary btn-sm btn-back-sml" onclick="history.back()"><i class="fas fa-chevron-left"></i>&nbsp;Back</a>
    <a class="btn btn-success btn-sm mr-2 btn-payment-sml" id="paymentBtn" data-toggle="modal" data-target="#paymentModal"><i class="fas fa-money-bill"></i>&nbsp;Payment</a>
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
                <?php if (isset($rekening)) { ?>
                    <ol style="margin-bottom: -1px;">
                        <?php foreach ($rekening as $data) : ?>
                            <li>Nama : <?= $data['nama'] ?> <br> Bank : <?= $data['nama_bank'] ?> <br> No. Rekening : <?= $data['no_rek'] ?></li>
                        <?php endforeach ?>
                    </ol>
                <?php } ?>
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

    <!-- Modal Payment -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="exampleModalLabel">
                        <i class="fas fa-check-circle"></i> Payment
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="paymentForm" action="">
                        <div class="form-group">
                            <div style="display: flex; justify-content: space-between">
                                <label for="payment_status">Status <span class="text-danger">*</span></label>
                            </div>
                            <select id="payment_status" name="payment_status" class="form-control" style="cursor: pointer;" required>
                                <option selected disabled>Choose status...</option>
                                <option value="1">Lunas</option>
                                <option value="0">Belum Lunas</option>
                            </select>
                            <input type="hidden" id="hidden_id" value="<?php echo $id ?>" name="id">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                <i class="fas fa-times"></i> Close
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php $this->load->view('template/footer'); ?>
    <?php $this->load->view('template/script'); ?>

    <script>
        var id = $('#hidden_id').val();

        $('#paymentBtn').click(function() {
            $('#paymentForm').attr('action', '<?= site_url('sml_invoice/payment') ?>');

            $.ajax({
                url: "<?php echo site_url('sml_invoice/edit_data') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#payment_status').val(data['master']['payment_status']);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        });

        // Inisialisasi tombol submit dalam keadaan disabled
        $('#paymentForm button[type="submit"]').prop('disabled', true).css('cursor', 'not-allowed');

        // Event listener untuk elemen select
        $('#payment_status').change(function() {
            if ($(this).val() === null || $(this).val() === 'Choose status...') {
                // Nonaktifkan tombol submit jika tidak ada status yang dipilih
                $('#paymentForm button[type="submit"]').prop('disabled', true);
            } else {
                // Aktifkan tombol submit jika status telah dipilih
                $('#paymentForm button[type="submit"]').prop('disabled', false).css('cursor', 'pointer');
            }
        });

        // PAYMENT
        $('#paymentForm').submit(function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            // MENGINPUT PAYMENT
            $.ajax({
                url: url, // Mengambil action dari form
                type: "POST",
                data: $(this).serialize(), // Mengambil semua data dari form
                dataType: "JSON",
                success: function(data) {
                    if (data.status) //if success close modal and reload ajax table
                    {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Your data has been saved',
                            showConfirmButton: false,
                            timer: 1500
                        }).then((result) => {
                            window.history.back(); // Kembali ke halaman sebelumnya
                        })
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                }
            });
        });
    </script>