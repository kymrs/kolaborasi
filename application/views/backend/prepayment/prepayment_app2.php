<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view('template/header'); ?>
    <!-- Include Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
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

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
        }

        .header .d-flex {
            margin-top: 15px;
            /* Added margin-top for spacing */
            margin-bottom: 10px;
        }

        .header p {
            margin: 0;
            font-size: 14px;
        }

        .header h2 {
            margin-top: 20px;
            font-size: 20px;
            font-weight: normal;
        }

        .d-flex p {
            margin-bottom: 0;
        }

        .form-section {
            margin-bottom: 25px;
        }

        .form-section label {
            font-weight: 600;
            color: #495057;
        }

        .table-rincian,
        .table-section {
            width: 100%;
            margin-top: 25px;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            overflow: hidden;
        }

        .table-rincian th,
        .table-rincian td,
        .table-section th,
        .table-section td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        .table-rincian th {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .table-rincian td,
        .table-section td {
            height: 40px;
            font-size: 14px;
            color: #495057;
        }

        .table-section th {
            background-color: #007bff;
            color: #ffffff;
            text-align: center;
        }

        .signature-text {
            margin-top: 10px;
            font-size: 14px;
            color: #495057;
        }

        .form-control {
            border-radius: 4px;
            padding: 10px;
            font-size: 14px;
        }

        .btn {
            border-radius: 5px;
            font-weight: 600;
        }

        .modal-content {
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            border-bottom: 1px solid #e0e0e0;
        }

        .modal-footer {
            border-top: 1px solid #e0e0e0;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .header h1 {
                font-size: 22px;
            }

            .header h2 {
                font-size: 18px;
            }

            .table-rincian th,
            .table-rincian td,
            .table-section th,
            .table-section td {
                font-size: 12px;
                padding: 8px;
            }
        }

        @media (max-width: 480px) {
            .header h1 {
                font-size: 20px;
            }

            .header h2 {
                font-size: 16px;
            }

            .table-rincian th,
            .table-rincian td,
            .table-section th,
            .table-section td {
                font-size: 10px;
                padding: 6px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="form-container">
            <div class="d-flex justify-content-end mb-3">
                <a class="btn btn-danger btn-sm mr-2" href="<?= base_url('prepayment/generate_pdf/' . $id) ?>"><i class="fas fa-file-pdf"></i>&nbsp;Print Out</a>
                <?php if ($user->app_name == $app_name) { ?>
                    <a class="btn btn-warning btn-sm mr-2" id="appBtn" data-toggle="modal" data-target="#appModal"><i class="fas fa-check-circle"></i>&nbsp;Approval</a>
                <?php } elseif ($user->app2_name == $app2_name) { ?>
                    <a class="btn btn-warning btn-sm mr-2" id="appBtn2" data-toggle="modal" data-target="#appModal"><i class="fas fa-check-circle"></i>&nbsp;Approval</a>
                <?php } ?>
                <a class="btn btn-secondary btn-sm" href="<?= base_url('prepayment') ?>"><i class="fas fa-chevron-left"></i>&nbsp;Back</a>
            </div>

            <!-- Header Section -->
            <div class="header">
                <h1>PT. MANDIRI CIPTA SEJAHTERA</h1>
                <div class="d-flex justify-content-center align-items-center">
                    <p class="mb-1 mr-3"><strong>Divisi:</strong> <span id="divisiCol"></span></p>
                    <p class="mb-1"><strong>Prepayment:</strong> <span id="prepaymentCol"></span></p>
                </div>
                <h2>FORM PENGAJUAN PREPAYMENT</h2>
            </div>

            <!-- Form Sections -->
            <div class="form-group row">
                <label for="tanggal" class="col-sm-3 col-form-label">Tanggal:</label>
                <div class="col-sm-9">
                    <span id="tanggal" class="form-control-plaintext"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="nama" class="col-sm-3 col-form-label">Nama:</label>
                <div class="col-sm-9">
                    <span id="nama" class="form-control-plaintext"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="jabatan" class="col-sm-3 col-form-label">Jabatan:</label>
                <div class="col-sm-9">
                    <span id="jabatan" class="form-control-plaintext"></span>
                </div>
            </div>

            <p>Dengan ini bermaksud mengajukan prepayment untuk:</p>

            <div class="form-group row">
                <label for="tujuan" class="col-sm-3 col-form-label">Tujuan:</label>
                <div class="col-sm-9">
                    <span id="tujuan" class="form-control-plaintext"></span>
                    <input type="hidden" id="hidden_id" value="<?php echo $id ?>">
                </div>
            </div>

            <!-- Rincian Table -->
            <table class="table table-bordered table-rincian">
                <thead>
                    <tr>
                        <th>Rincian</th>
                        <th>Nominal</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody id="input-container">
                    <!-- Rows will be appended here -->
                </tbody>
            </table>

            <!-- Signature Table -->
            <table class="table table-bordered table-section">
                <thead>
                    <tr>
                        <th>Yang melakukan</th>
                        <th>Mengetahui</th>
                        <th>Menyetujui</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <!-- GENERATE INPUTAN -->
                        </td>
                        <td id="statusMengetahui">
                            <!-- GENERATE INPUTAN -->
                        </td>
                        <td id="statusMenyetujui">
                            <!-- GENERATE INPUTAN -->
                        </td>
                    </tr>
                    <tr>
                        <td id="melakukan">
                            <!-- GENERATE INPUTAN -->
                        </td>
                        <td id="mengetahui">
                            <!-- GENERATE INPUTAN -->
                        </td>
                        <td id="menyetujui">
                            <!-- GENERATE INPUTAN -->
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Comment Section -->
            <div class="form-group row">
                <label for="keterangan" class="col-sm-3 col-form-label">Keterangan:</label>
                <!-- HIDDEN INPUT -->
                <input type="hidden" id="hidden_id" name="hidden_id" value="<?= $id ?>">
                <div class="col-sm-9">
                    <div id="keterangan"></div>
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
                                <option selected disabled>Choose status...</option>
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

    <!-- Include jQuery and Bootstrap JS -->
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
                $('#app_name').attr('name', 'app_name');
                $('#app_keterangan').attr('name', 'app_keterangan');
                $('#app_status').attr('name', 'app_status');
                $('#approvalForm').attr('action', '<?= site_url('prepayment/approve') ?>');
            });

            $('#appBtn2').click(function() {
                $('#app_name').attr('name', 'app2_name');
                $('#app_keterangan').attr('name', 'app2_keterangan');
                $('#app_status').attr('name', 'app2_status');
                $('#approvalForm').attr('action', '<?= site_url('prepayment/approve2') ?>');
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
                url: "<?php echo site_url('prepayment/edit_data') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    console.log(data);
                    // DATA PREPAYMENT
                    $('#tanggal').html(data['master']['tgl_prepayment']);
                    $('#nama').html(data['nama']);
                    $('#jabatan').html(data['master']['jabatan']);
                    $('#tujuan').html(data['master']['tujuan']);
                    if (data['master']['app_keterangan'] != null) {
                        $('#keterangan').append(`<span class="form-control-plaintext">*${data['master']['app_keterangan']}</span>`);
                    }
                    if (data['master']['app2_keterangan'] != null) {
                        $('#keterangan').append(`<span class="form-control-plaintext">*${data['master']['app2_keterangan']}</span>`);
                    }
                    // DATA APPROVAL PREPAYMENT
                    var nama, status, keterangan, nama2, status2, keterangan2, url;

                    // Memeriksa apakah data yang mengetahui ada
                    if (data['master']['app_status'] != null) {
                        nama = data['master']['app_name'];
                        status = data['master']['app_status'];
                        keterangan = data['master']['app_keterangan'];
                        url = "<?php echo site_url('prepayment/approve') ?>";
                        $('#note_id').append(`<p>* ${keterangan}</p>`);
                    } else {
                        nama = `<br><br><br>`;
                        status = `_____________________`;
                    }

                    // Memeriksa apakah data yang menyetujui ada
                    if (data['master']['app2_status'] != null) {
                        nama2 = data['master']['app2_name'];
                        status2 = data['master']['app2_status'];
                        keterangan2 = data['master']['app2_keterangan'];
                        url = "<?php echo site_url('prepayment/approve2') ?>";
                        $('#note_id').append(`<p>* ${keterangan2}</p>`);
                    } else {
                        nama2 = `<br><br><br>`;
                        status2 = `_____________________`;
                    }

                    $('#melakukan').html(`<div class="signature-text text-center">${data['nama']}</div>`);
                    $('#mengetahui').html(`<div class="signature-text text-center">${data['master']['app_name']}</div>`);
                    $('#menyetujui').html(`<div class="signature-text text-center">${data['master']['app2_name']}</div>`);
                    $('#statusMengetahui').html(`<div class="signature-text text-center">${data['master']['app_status'].toUpperCase()}</div>`);
                    $('#statusMenyetujui').html(`<div class="signature-text text-center">${data['master']['app2_status'].toUpperCase()}</div>`);

                    $('#divisiCol').html(data['master']['divisi']);
                    $('#prepaymentCol').html(data['master']['prepayment']);


                    //DATA PREPAYMENT DETAIL
                    let total = 0;
                    for (let index = 0; index < data['transaksi'].length; index++) {
                        const row = `<tr>
                                    <td>${data['transaksi'][index]['rincian']}</td>
                                    <td>${data['transaksi'][index]['nominal'].replace(/\B(?=(\d{3})+(?!\d))/g, '.')}</td>
                                    <td>${data['transaksi'][index]['keterangan']}</td>
                                </tr>`;
                        $('#input-container').append(row);
                        total += Number(data['transaksi'][index]['nominal']);
                    }
                    const totalFormatted = total.toLocaleString('de-DE');
                    const ttl_row = `<tr>
                                        <td colspan="2" class="text-right">Total:</td>
                                        <td>${totalFormatted}</td>
                                    </tr>`;
                    $('#input-container').append(ttl_row);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });

            // APPROVE
            $('#approvalForm').submit(function(e) {
                e.preventDefault();
                var url = $(this).attr('action');
                // MENGINPUT APPROVAL
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
                                location.href = "<?= base_url('prepayment') ?>";
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

            $('#keterangan').append(`<span class="form-control-plaintext">*Berikut ini merupakan catatan keterangan prepayment.</span>`);
        });
    </script>
</body>

</html>