<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view('template/header'); ?>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin-left: 40px;
            margin-right: 40px;
            margin-bottom: 40px;
            padding: 0;
            line-height: 1.6;
        }

        /* Header Styling */
        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
        }

        .header p {
            margin: 5px 0;
            font-size: 14px;
        }

        .header h2 {
            margin: 10px 0;
            font-size: 20px;
            font-weight: normal;
        }

        /* Form Section Styling */
        .form-section {
            margin-bottom: 20px;
        }

        .form-section label {
            display: inline-block;
            width: 100px;
            font-weight: bold;
        }

        .form-section .input-line {
            display: inline-block;
            width: calc(100% - 110px);
            border-bottom: 1px solid #000;
            margin-left: 10px;
        }

        .form-section .input-line.short {
            width: 200px;
        }

        /* Table Styling */
        .table-rincian {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table-rincian th,
        .table-rincian td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .table-rincian th {
            background-color: #f2f2f2;
        }

        .table-rincian td {
            height: 30px;
        }

        .table-section {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table-section th,
        .table-section td {
            border: none;
            padding: 8px;
            text-align: center;
        }

        .table-section th {
            background-color: #f2f2f2;
        }

        .table-section td {
            border-bottom: 1px solid #ddd;
        }

        .table-section tr:last-child td {
            border-bottom: none;
        }

        /* Container for centering the table */
        .table-container {
            margin-top: 20px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="card-header text-right">
        <a class="btn btn-danger btn-sm" href="<?= base_url('prepayment') ?>"><i class="fas fa-file-pdf"></i>&nbsp;Print Out</a>
        <?php if ($user->app_name == $this->session->userdata('fullname')) { ?>
            <a class="btn btn-warning btn-sm" id="appBtn" data-toggle="modal" data-target="#appModal"><i class="fas fa-file-pdf"></i>&nbsp;Approval</a>
        <?php } elseif ($user->app2_name == $this->session->userdata('fullname')) { ?>
            <a class="btn btn-warning btn-sm" id="appBtn2" data-toggle="modal" data-target="#appModal"><i class="fas fa-file-pdf"></i>&nbsp;Approval</a>
        <?php } ?>
        <a class="btn btn-secondary btn-sm" href="<?= base_url('prepayment') ?>"><i class="fas fa-chevron-left"></i>&nbsp;Back</a>
    </div>
    <!-- Header Section -->
    <div class="header">
        <h1>PT. MANDIRI CIPTA SEJAHTERA</h1>
        <p>Divisi:</p>
        <p id="divisiCol"></p>
        <p>Prepayment:</p>
        <p id="prepaymentCol"></p>
        <h2>FORM PENGAJUAN PREPAYMENT</h2>
    </div>

    <!-- Form Sections -->
    <div class="form-section">
        <label for="tanggal">Tanggal:</label>
        <span id="tanggal" class="input-line short" id="tanggal"></span>
    </div>
    <div class="form-section">
        <label for="nama">Nama:</label>
        <span id="nama" class="input-line short" id="nama"></span>
    </div>
    <div class="form-section">
        <label for="jabatan">Jabatan:</label>
        <span id="jabatan" class="input-line short" id="jabatan"></span>
    </div>

    <p>Dengan ini bermaksud mengajukan prepayment untuk:</p>

    <div class="form-section">
        <label for="tujuan">Tujuan:</label>
        <span id="tujuan" class="input-line" id="tujuan"></span>
        <input type="hidden" id="hidden_id" value="<?php echo $id ?>">
    </div>

    <!-- Rincian Table -->
    <table class="table-rincian">
        <thead>
            <tr>
                <th>Rincian</th>
                <th>Nominal</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody id="input-container">
            <!-- <tr>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
            </tr> -->
        </tbody>
    </table>

    <!-- Signature Table -->
    <div class="table-container">
        <table class="table-section">
            <thead>
                <tr>
                    <th>Yang melakukan</th>
                    <th>Mengetahui</th>
                    <th>Menyetujui</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                    <td id="mengetahuiStts"></td>
                    <td></td>
                </tr>
                <tr>
                    <td id="melakukan"></td>
                    <td id="mengetahui"></td>
                    <td id="menyetujui"></td>
                </tr>
            </tbody>
        </table>
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

    <?php $this->load->view('template/script'); ?>

    <!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
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

            $.ajax({
                url: "<?php echo site_url('prepayment/edit_data') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    console.log(data);
                    // DATA PREPAYMENT
                    $('#tanggal').html(data['master']['tgl_prepayment']);
                    $('#nama').html(data['master']['nama']);
                    $('#jabatan').html(data['master']['jabatan']);
                    $('#tujuan').html(data['master']['tujuan']);
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

                    $('#melakukan').html(data['nama']);
                    $('#mengetahui').html(data['master']['app_name']);
                    $('#mengetahuiStts').html(data['master']['app_status']);
                    $('#menyetujui').html(data['master']['app2_name']);

                    $('#divisiCol').html(data['master']['divisi'].toUpperCase());
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
        });

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

        // SIGNATURE PAD UNTUK TANDA TANGAN
        // var canvas = document.getElementById('signature-pad');
        // var signaturePad = new SignaturePad(canvas);

        // // Clear button
        // document.getElementById('clear').addEventListener('click', function() {
        //     signaturePad.clear();
        // });

        // // Save button
        // document.getElementById('save').addEventListener('click', function() {
        //     if (signaturePad.isEmpty()) {
        //         alert("Please provide a signature first.");
        //     } else {
        //         var dataURL = signaturePad.toDataURL('image/png');
        //         // Send the data to the server
        //         $.ajax({
        //             url: '<?= site_url('prepayment/signature'); ?>',
        //             type: 'POST',
        //             data: {
        //                 imgBase64: dataURL
        //             },
        //             success: function(response) {
        //                 alert('Signature saved successfully!');
        //             },
        //             error: function() {
        //                 alert('Error saving signature.');
        //             }
        //         });
        //     }
        // });
    </script>

</body>

</html>