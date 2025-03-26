<?php $this->load->view('template/header'); ?>

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/backend/plugins/style-invoice.css') ?>">
</head>

<style>
    .detail-pemesanan .header {
        background-color: #00be63;
        padding: 3px 7px;
        color: #fff;
    }
</style>

<body>
    <div class="container">
        <div class="canvas">
            <a class="btn btn-success btn-sm mr-2" id="paymentBtn" data-toggle="modal" data-target="#paymentModal"><i class="fas fa-money-bill"></i>&nbsp;Payment</a>
            <a class="btn btn-secondary btn-sm" onclick="history.back()" style="float: right"><i class="fas fa-chevron-left"></i>&nbsp;Back</a>
            <hr class="line-header">
            <header>
                <div class="left-side">
                    <div class="logo">
                        <img src="<?= base_url('assets/backend/img/qubagift.png') ?>" alt="logo pengenumroh">
                    </div>
                </div>
                <div class="right-side">
                    <div class="email">qubagift@gmail.com</div>
                    <div class="noHP">081290399933</div>
                </div>
            </header>
            <hr class="line">
            <main>
                <div class="invoice">
                    <h3>INVOICE</h3>
                    <p>No. Invoice : <?= $invoice['kode_invoice']; ?></p>
                </div>
                <div class="header-content">
                    <div class="left-side">
                        <h2>Kepada Yth.</h2>
                        <h1><?= $invoice['nama_customer'] ?></h1>
                        <div style="display: flex; gap: 5px;">
                            <label for="">Email</label>
                            <span style="margin-left: 28px;">:</span>
                            <p class="email"><?= $invoice['email_customer'] ? $invoice['email_customer'] : '-' ?></p>
                        </div>
                        <div style="display: flex; gap: 5px;">
                            <label for="">Telepon</label>
                            <span style="margin-left: 10px;">:</span>
                            <p class="nomor"><?= $invoice['nomor_customer'] ?></p>
                        </div>
                        <div style="display: flex; gap: 5px;">
                            <label for="">Alamat</label>
                            <span style="margin-left: 14px;">:</span>
                            <p class="alamat"><?= $invoice['alamat_customer'] ?></p>
                        </div>
                    </div>
                    <div class="right-side">
                        <table>
                            <tr>
                                <td>Tanggal Invoice</td>
                                <td>:</td>
                                <td><?= date('d/m/Y', strtotime($invoice['tgl_invoice'])) ?></td>
                            </tr>
                            <tr>
                                <td>Tanggal Jatuh Tempo</td>
                                <td>:</td>
                                <td><?= date('d/m/Y', strtotime($invoice['tgl_tempo'])) ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="detail-pemesanan">
                    <div class="header">
                        Detail Pemesanan :
                    </div>
                    <table>
                        <tr>
                            <th>PRODUK</th>
                            <th>JUMLAH</th>
                            <th>HARGA</th>
                            <th>TOTAL</th>
                        </tr>
                        <?php foreach ($detail as $data) : ?>
                            <!-- Mengambil data produk berdasarkan kode produk -->
                            <?php $produk = $this->db->select('nama_produk, berat, satuan')->where('kode_produk', $data['kode_produk'])->get('qbg_produk')->row_array(); ?>
                            <tr>
                                <td><?= $produk['nama_produk'] . ' ' . $produk['berat'] . ' ' . $produk['satuan'] ?></td>
                                <td class="jumlah"><?= $data['jumlah'] ?></td>
                                <td class="harga"><?= "Rp. " . number_format($data['harga'], 0, ',', '.') ?></td>
                                <td class="total"><?= "Rp. " . number_format($data['total'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="2" style="border-left-color: #fff; border-bottom-color: #fff"></td>
                            <td style="text-align: center;">Biaya Pengiriman</td>
                            <td style="text-align: center"><?= "Rp. " . number_format($invoice['ongkir'], 0, ',', '.') ?></td>
                        </tr>
                        <?php if ($invoice['potongan_harga'] != 0) : ?>
                            <tr>
                                <td colspan="2" style="border-left-color: #fff; border-bottom-color: #fff"></td>
                                <td style="text-align: center;">Potongan Harga</td>
                                <td style="text-align: center"><?= "Rp. -" . number_format($invoice['potongan_harga'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endif ?>
                        <tr>
                            <td colspan="2" style="border-left-color: #fff; border-bottom-color: #fff"></td>
                            <th>Total</th>
                            <td style="text-align: center"><?= "Rp. " . number_format($invoice['grand_total'], 0, ',', '.') ?></td>
                        </tr>
                    </table>
                </div>
                <div class="metode-pembayaran">
                    <p>Metode Pembayaran</p>
                    <ol>
                        <?php foreach ($rekening as $data) : ?>
                            <li>Bank : <?= $data['nama_bank'] ?> <br> No. Rekening : <?= $data['no_rek'] ?> <br> Atas Nama : <b><?= $data['atas_nama'] ?></b></li>
                        <?php endforeach ?>
                    </ol>
                </div>
                <div class="">
                    <p>Catatan :</p>
                    <div style="margin-top: -16px;">
                        <?= $invoice['keterangan'] ?>
                    </div>
                </div>
                <div class="" style="text-align: right;">
                    <p><b>Terima Kasih</b>, QubaGift</p>
                </div>
            </main>
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
                            <!-- <label style="font-size: 107%;"><span style="font-weight: bold">No Rekening</span> <span style="margin-left: 5px;">:</span> <span id="no_rek"></span></label> -->
                        </div>
                        <div class="form-group">
                            <div style="display: flex; justify-content: space-between">
                                <label for="payment_status">Status <span class="text-danger">*</span></label>
                            </div>
                            <select id="payment_status" name="payment_status" class="form-control" style="cursor: pointer;" required>
                                <option selected disabled>Choose status...</option>
                                <option value="paid">Paid</option>
                                <option value="unpaid">Unpaid</option>
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
            $('#paymentForm').attr('action', '<?= site_url('qbg_invoice/payment') ?>');

            $.ajax({
                url: "<?php echo site_url('qbg_invoice/edit_data') ?>/" + id,
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

        $(document).ready(function() {
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
    </script>