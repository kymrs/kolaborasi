<head>
    <?php $this->load->view('template/header'); ?>
    <style>
        body .container {
            font-family: Arial, Helvetica, sans-serif;
            padding: 0;
            color: #333;
        }

        .form-container {
            max-width: 800px;
            margin: 15px auto;
            padding: 25px;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        /* Header */

        .header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }

        .header .logo {
            width: 140px;
            position: relative;
            right: -3px;
            bottom: 80px;
            margin-bottom: -70px;
        }

        .header h1 {
            font-size: 1.4rem;
            font-weight: bold;
        }

        .header-field {
            margin-top: 25px;
        }

        .header-field tr td:nth-child(2) {
            padding-left: 20px;
            padding-right: 5px;
        }

        .title {
            text-align: center;
            margin-top: 10px;
        }

        .title h1 {
            font-weight: bold;
            font-size: 1.4rem;
        }

        /* Main field */
        .main-field tr td:nth-child(2) {
            padding-left: 25px;
            padding-right: 5px;
        }

        .main-field tr td:nth-child(3) {
            width: 100%;
            border-bottom: 1.5px solid #444;
        }

        /* Transaction Field */
        .transaction-field table {
            width: 100%;
        }

        .transaction-field table tr,
        .transaction-field table tr th,
        .transaction-field table tr td {
            border: 1.5px solid #444;
            padding: 5px;
        }

        .transaction-field table tr th:nth-child(1) {
            width: 30%;
        }

        .transaction-field table tr th:nth-child(2) {
            width: 30%;
        }

        .transaction-field table tr th:nth-child(3) {
            width: 42%;
        }

        .transaction-field table tr th {
            text-align: center;
        }

        .transaction-field table {
            margin-top: 30px;
        }

        /* Table Approve */
        .table-approve {
            margin-top: 35px;
        }

        .table-approve table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-approve table tr td {
            text-align: center;
            width: 25%;
            padding: 10px;
            font-size: 14px;
            border: 1.4px solid black;
        }

        /* Keterangan */
        .keterangan-field {
            margin-top: 35px;
        }


        @media (max-width: 546px) {
            .main-field table tr td {
                padding: 5px 0;
            }

            .transaction-field {
                overflow-x: scroll;
            }

            .table-approve {
                overflow-x: scroll;
            }
        }
    </style>
</head>

<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <div class="container">
                <div class="form-container">
                    <div class="d-flex justify-content-end mb-3">
                        <?php if ($user->app_name == $app_name && $user->status == 'approved') { ?>
                            <a class="btn btn-success btn-sm mr-2" id="paymentBtn" data-toggle="modal" data-target="#paymentModal"><i class="fas fa-money-bill"></i>&nbsp;Payment</a>
                        <?php } ?>
                        <?php if ($user->app_name == $app_name && $user->app2_status != 'rejected' && !in_array($user->status, ['approved'])) { ?>
                            <a class="btn btn-warning btn-sm mr-2" id="appBtn" data-toggle="modal" data-target="#appModal"><i class="fas fa-check-circle"></i>&nbsp;Approval</a>
                        <?php } elseif ($user->app2_name == $app2_name && !in_array($user->status, ['rejected', 'approved'])  && $user->app_status == 'approved') { ?>
                            <a class="btn btn-warning btn-sm mr-2" id="appBtn2" data-toggle="modal" data-target="#appModal"><i class="fas fa-check-circle"></i>&nbsp;Approval</a>
                        <?php } ?>
                        <a class="btn btn-secondary btn-sm" href="<?= base_url('prepayment_pu') ?>"><i class="fas fa-chevron-left"></i>&nbsp;Back</a>
                    </div>

                    <!-- Header Section -->
                    <div class="header">
                        <div class="header-field">
                            <img src="<?= base_url('assets/backend/img/pengenumroh.png') ?>" alt="" class="logo">
                            <table>
                                <tr>
                                    <td style="font-weight: bold;">Divisi</td>
                                    <td>:</td>
                                    <td id="divisiTxt">tess</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold;">Prepayment</td>
                                    <td>:</td>
                                    <td id="prepaymentTxt">tess</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="title">
                        <h1>FORM PENGAJUAN PREPAYMENT</h1>
                    </div>
                    <div class="main-field">
                        <table>
                            <tr>
                                <td>Tanggal</td>
                                <td>:</td>
                                <td class="line" id="tanggalTxt">tess</td>
                            </tr>
                            <tr>
                                <td>Nama</td>
                                <td>:</td>
                                <td class="line" id="namaTxt">tess</td>
                            </tr>
                            <tr>
                                <td>Jabatan</td>
                                <td>:</td>
                                <td class="line" id="jabatanTxt">tess</td>
                            </tr>
                            <tr>
                                <td colspan="3">Dengan ini bermaksud mengajukan prepayment untuk :</td>
                            </tr>
                            <tr>
                                <td>Tujuan</td>
                                <td>:</td>
                                <td class="line" id="tujuanTxt"></td>
                                <!-- HIDDEN INPUT -->
                                <input type="hidden" name="hidden_id" id="hidden_id" value="<?= $id ?>">
                            </tr>
                        </table>
                    </div>

                    <div class="transaction-field">
                        <table>
                            <thead>
                                <tr>
                                    <th>Rincian</th>
                                    <th>Nominal</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody id="input-container">
                                <!-- GENERATE ROW DETAIL PREPAYMENT -->
                            </tbody>
                        </table>
                    </div>

                    <div class="table-approve">
                        <table>
                            <tr>
                                <td>Yang melakukan</td>
                                <td>Mengetahui</td>
                                <td>Menyetujui</td>
                            </tr>
                            <tr style="height: 75px">
                                <td id="statusMelakukan"></td>
                                <td id="statusMengetahui"></td>
                                <td id="statusMenyetujui"></td>
                            </tr>
                            <tr>
                                <td id="melakukan"></td>
                                <td id="mengetahui"></td>
                                <td id="menyetujui"></td>
                            </tr>
                        </table>
                    </div>

                    <div class="keterangan-field">
                        <!-- <span>Keterangan :</span> -->
                        <div id="keterangan">
                            <!-- GENERATE KETERANGAN -->
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>



<!-- Modal -->
<div class="modal fade" id="appModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="exampleModalLabel">
                    <i class="fas fa-check-circle"></i> Approval
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="approvalForm" action="">
                    <div class="form-group">
                        <label for="app_status">Status <span class="text-danger">*</span></label>
                        <select id="app_status" name="app_status" class="form-control" required>
                            <option selected disabled value="Choose status...">Choose status...</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                            <option value="revised">Revised</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="app_keterangan" class="col-form-label">Keterangan:</label>
                        <textarea class="form-control" name="app_keterangan" id="app_keterangan" placeholder="Add your comments here"></textarea>
                        <!-- HIDDEN INPUT -->
                        <input type="hidden" name="app_name" id="app_name" value="<?= $this->session->userdata('fullname'); ?>">
                        <input type="hidden" id="hidden_id" name="hidden_id" value="<?= $id ?>">
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
                        <label for="payment_status">Status <span class="text-danger">*</span></label>
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


<!-- Include jQuery and Bootstrap JS -->
<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>
<!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->

<script>
    $(document).ready(function() {
        //INISIAI VARIABLE JQUERY
        var id = $('#hidden_id').val();
        let url = "";

        $('#appModal').on('show.bs.modal', function(e) {
            var id = $(e.relatedTarget).data('id');
            $('#hidden_id').val(id);
        });

        $('#appBtn').click(function() {
            $('#app_keterangan').attr('name', 'app_keterangan');
            $('#app_status').attr('name', 'app_status');
            $('#approvalForm').attr('action', '<?= site_url('prepayment_pu/approve') ?>');
            $.ajax({
                url: "<?php echo site_url('prepayment_pu/edit_data') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    var nama, date, status, keterangan;
                    // Memeriksa apakah data yang mengetahui ada
                    if (data['master']['app_status'] == 'waiting') {
                        $('#app_status').val();
                        $('#app_keterangan').val();
                    } else {
                        nama = data['master']['app_name'];
                        status = data['master']['app_status'];
                        keterangan = data['master']['app_keterangan'];
                        $('#app_status').val(status);
                        $('#app_keterangan').val(keterangan);
                        // $('#note_id').append(`<p>* ${keterangan}</p>`);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        });

        $('#appBtn2').click(function() {
            $('#app_keterangan').attr('name', 'app2_keterangan').attr('id', 'app2_keterangan');
            $('#app_status').attr('name', 'app2_status').attr('id', 'app2_status');
            $('#approvalForm').attr('action', '<?= site_url('prepayment_pu/approve2') ?>');

            $.ajax({
                url: "<?php echo site_url('prepayment_pu/edit_data') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    var nama2, date2, status2, keterangan2;
                    if (data['master']['app2_status'] == 'waiting') {
                        $('#app2_status').val();
                        $('#app2_keterangan').val();
                    } else {
                        nama2 = data['master']['app2_name'];
                        status2 = data['master']['app2_status'];
                        keterangan2 = data['master']['app2_keterangan'];
                        $('#app2_status').val(status2);
                        $('#app2_keterangan').val(keterangan2);
                        // $('#note_id').append(`<p>* ${keterangan2}</p>`);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        });

        $('#paymentBtn').click(function() {
            $('#paymentForm').attr('action', '<?= site_url('prepayment_pu/payment') ?>');

            $.ajax({
                url: "<?php echo site_url('prepayment_pu/edit_data') ?>/" + id,
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
        // Handle the approval button click event
        $('#confirmApproval').click(function() {
            const id = $('#hidden_id').val();
            // Implement the logic to process the approval
            console.log('Approval confirmed for ID:', id);
            // Close the modal
            $('#appModal').modal('hide');
        });

        // Additional logic to dynamically load data into the form
        $.ajax({
            url: "<?php echo site_url('prepayment_pu/edit_data') ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                moment.locale('id')
                // DATA PREPAYMENT
                $('#divisiTxt').text(data['master']['divisi']);
                $('#prepaymentTxt').text(data['master']['prepayment']);
                $('#tanggalTxt').text(moment(data['master']['tgl_prepayment']).format('D MMMM YYYY'));
                $('#namaTxt').text(data['nama']);
                $('#jabatanTxt').text(data['master']['jabatan']);
                $('#tujuanTxt').text(data['master']['tujuan']);
                if ((data['master']['app_keterangan'] !== null && data['master']['app_keterangan'] !== '') ||
                    (data['master']['app2_keterangan'] !== null && data['master']['app2_keterangan'] !== '')) {
                    $('#keterangan').append(`<span>Keterangan :</span>`);
                }
                if (data['master']['app_keterangan'] !== null && data['master']['app_keterangan'] !== '') {
                    $('#keterangan').append(`<span class="form-control-plaintext">*${data['master']['app_keterangan']}(${data['master']['app_name']})</span>`);
                }
                if (data['master']['app2_keterangan'] !== null && data['master']['app2_keterangan'] !== '') {
                    $('#keterangan').append(`<span class="form-control-plaintext">*${data['master']['app2_keterangan']}(${data['master']['app_name']})</span>`);
                }
                // DATA APPROVAL PREPAYMENT

                if (data['master']['app_date'] == null) {
                    date = '';
                }
                if (data['master']['app_date'] != null) {
                    date = data['master']['app_date'];
                }

                // Memeriksa apakah data yang menyetujui ada
                if (data['master']['app2_date'] == null) {
                    date2 = '';
                }
                if (data['master']['app2_date'] != null) {
                    date2 = data['master']['app2_date'];
                }

                $('#melakukan').html(`<div class="signature-text text-center">${data['nama']}</div>`);
                $('#mengetahui').html(`<div class="signature-text text-center">${data['master']['app_name']}</div>`);
                $('#menyetujui').html(`<div class="signature-text text-center">${data['master']['app2_name']}</div>`);
                $('#statusMelakukan').html(`<div class="signature-text text-center">CREATED<br><span>${data['master']['created_at']}</span></div>`);
                $('#statusMengetahui').html(`<div class="signature-text text-center">${data['master']['app_status'].toUpperCase()}<br><span>${date}</span></div>`);
                $('#statusMenyetujui').html(`<div class="signature-text text-center">${data['master']['app2_status'].toUpperCase()}<br><span>${date2}</span></div>`);

                $('#divisiCol').html(data['master']['divisi']);
                $('#prepaymentCol').html(data['master']['prepayment']);


                //DATA PREPAYMENT DETAIL
                let total = 0;
                for (let index = 0; index < data['transaksi'].length; index++) {
                    const row = `<tr>
                                    <td>${data['transaksi'][index]['rincian']}</td>
                                    <td>Rp. <span style="float: right">${data['transaksi'][index]['nominal'].replace(/\B(?=(\d{3})+(?!\d))/g, '.')}</span></td>
                                    <td>${data['transaksi'][index]['keterangan']}</td>
                                </tr>`;
                    $('#input-container').append(row);
                    total += Number(data['transaksi'][index]['nominal']);
                }
                const totalFormatted = total.toLocaleString('de-DE');
                const ttl_row = `<tr>
                                        <td colspan="3"><span style="font-weight: bold">Total : </span><span style="float: right">Rp. <span id="total">${totalFormatted}</span></span></td>
                                    </tr>`;
                $('#input-container').append(ttl_row);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });

        // APPROVE
        // APPROVE
        $("#approvalForm").validate({
            rules: {
                app_status: {
                    required: true,
                },
                app2_status: {
                    required: true,
                }

            },
            messages: {
                app_status: {
                    required: "Status is required",
                },
                app2_status: {
                    required: "Status is required",
                }

            },
            errorPlacement: function(error, element) {
                if (element.parent().hasClass('input-group')) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function(element) {
                $(element).addClass('is-invalid'); // Tambahkan kelas untuk menandai input tidak valid
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid'); // Hapus kelas jika input valid
            },
            focusInvalid: false, // Disable auto-focus on the first invalid field
            submitHandler: function(form) {
                // MENGINPUT APPROVAL
                $.ajax({
                    url: $(form).attr('action'), // Mengambil action dari form
                    type: "POST",
                    data: $(form).serialize(), // Mengambil semua data dari form
                    dataType: "JSON",
                    success: function(data) {
                        // console.log(data);
                        if (data.status) //if success close modal and reload ajax table
                        {
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'Your data has been saved',
                                showConfirmButton: false,
                                timer: 1500
                            }).then((result) => {
                                location.href = "<?= base_url('prepayment_pu') ?>";
                            })
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error adding / update data');
                    }
                });
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
                            location.href = "<?= base_url('prepayment_pu') ?>";
                        })
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                }
            });
        });

        // Example: Load data into the form fields and tables
        // $('#divisiCol').text('Finance');
        // $('#prepaymentCol').text('001234');
        // $('#tanggal').text('29 August 2024');
        // $('#nama').text('Rakha Rizki');
        // $('#jabatan').text('Software Developer');
        // $('#tujuan').text('Project Development');

        // Example: Append rows to the rincian table
        // $('#input-container').append(`
        //     <tr>
        //         <td>Consultation Fees</td>
        //         <td>Rp. 5,000,000</td>
        //         <td>Consulting on project scope</td>
        //     </tr>
        //     <tr>
        //         <td>Development Tools</td>
        //         <td>Rp. 3,000,000</td>
        //         <td>Purchase of software licenses</td>
        //     </tr>
        // `);

    });
</script>