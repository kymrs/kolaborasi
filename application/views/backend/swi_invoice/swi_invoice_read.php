<?php $this->load->view('template/header'); ?>

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/backend/plugins/style-invoice-swi.css') ?>">
</head>

<body>
    <!-- FITUR PAYMENT -->
    <a class="btn btn-success btn-sm mr-2" id="paymentBtn" data-toggle="modal" style="float: right;" data-target="#paymentModal"><i class="fas fa-money-bill"></i>&nbsp;Payment</a>
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
                        <p>Sobat Wisata</p>
                    </div>
                    <div class="to">
                        <h4>TO</h4>
                        <p><?= $invoice['ctc_to'] ?></p>
                    </div>
                </div>
                <div class="address">
                    <div class="address-left">
                        <h4>ADDRESS</h4>
                        <p>Kp. Tunggilis RT 001 RW 007, Desa/Kelurahan Situsari, Kec. Cileungsi, Kab. Bogor, Provinsi Jawa Barat, Kode Pos: 16820</p>
                    </div>
                    <div class="address-right">
                        <h4>ADDRESS</h4>
                        <p><?= $invoice['ctc_address'] ?></p>
                    </div>
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
                    <table>
                        <tr>
                            <td>TOTAL</td>
                            <td><?= 'Rp. ' . number_format($invoice['total'], 0, ',', '.') ?></td>
                        </tr>
                        <tr>
                            <td>TAX</td>
                            <td><?= 'Rp. ' . number_format($invoice['tax'], 0, ',', '.') ?></td>
                        </tr>
                        <?php
                        $grand_total = $invoice['total'] + $invoice['tax'];
                        ?>
                        <tr>
                            <td>GRAND TOTAL</td>
                            <td><?= 'Rp. ' . number_format($grand_total, 0, ',', '.') ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="bank-details">
                <h4>BANK DETAILS</h4>
                <?php foreach ($rekening as $data) : ?>
                    <p><?= $data['nama_bank'] ?></p>
                    <p><?= $data['no_rek'] ?></p>
                    <p style="margin-bottom: 5px;"><?= $data['nama_rek'] ?></p>
                <?php endforeach ?>
            </div>
            <div class="footer">
                <img src="<?= base_url('assets/backend/img/footer-invoice-swi.png') ?>" alt="footer">
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
                        <!-- <div class="form-group">
                            <label style="font-size: 107%;"><span style="font-weight: bold">No Rekening</span> <span style="margin-left: 20px;">:</span> <span id="no_rek"></span></label> <br> -->
                        <!-- <label style="font-size: 107%;"><span style="font-weight: bold">Jenis Rekening</span> <span style="margin-left: 5px;">:</span> <span id="jenis_rek"></span></label> -->
                        <!-- </div> -->
                        <div class="form-group">
                            <label for="payment_status">Status <span class="text-danger">*</span></label>
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
        $(document).ready(function() {
            $('#paymentBtn').click(function() {
                $('#paymentForm').attr('action', '<?= site_url('swi_invoice/payment') ?>');
                const id = $('#hidden_id').val();

                $.ajax({
                    url: "<?php echo site_url('swi_invoice/edit_data') ?>/" + id,
                    type: "GET",
                    dataType: "JSON",
                    success: function(data) {
                        console.log(data);
                        $('#payment_status').val(data['master']['payment_status']);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error get data from ajax');
                    }
                });
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
                        console.log(data);
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
        });
    </script>