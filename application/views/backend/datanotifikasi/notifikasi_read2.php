<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view('template/header'); ?>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f4f6f9;
            padding: 0;
            color: #333;
        }

        .form-container {
            max-width: 700px;
            margin: 15px auto;
            padding: 25px;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        /* header */
        .header h1 {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 50px;
        }

        .header h2 {
            font-size: 1.2rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        /* main field */
        .main-field table tr td {
            padding: 3px 0;
        }

        .main-field table tr td:nth-child(1) {
            width: 20%;
        }

        .main-field table tr td:nth-child(2) {
            width: 2%;
        }

        .main-field table {
            width: 100%;
        }

        .main-field .status1 {
            margin: 15px 0;
        }

        .main-field .status2 {
            margin: 15px 0;
        }

        .main-field .status2 h1 {
            font-size: 1rem;
            margin-top: 20px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .main-field .alasan {
            position: relative;
            bottom: 30px;
            text-align: justify;
            margin: 0;
        }

        /* Note */
        .note h4 {
            margin-top: 20px;
            font-size: 1rem;
        }

        .note p {
            margin-top: 20px;
            border-bottom: 2px dotted #444;
            text-align: justify;
        }

        /* note hc */
        .note-hc h1 {
            margin-top: 25px;
            font-size: 1rem;
            font-weight: bold;
        }

        .note-hc table tr td:nth-child(1) {
            width: 25%;
        }

        /* Table Approve */
        .table-approve {
            margin-top: 35px;
        }

        .table-approve table {
            width: 70%;
        }

        .table-approve table tr td {
            border: 1.5px solid #444;
            padding: 2.5px;
            width: 100px;
            text-align: center;
        }

        .table-approve table tr:nth-child(1) td:nth-child(1),
        .table-approve table tr:nth-child(1) td:nth-child(2),
        .table-approve table tr:nth-child(1) td:nth-child(3) {
            width: 25%;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="form-container">
            <div class="d-flex justify-content-end mb-3">
                <?php if ($user->app_name == $app_name) { ?>
                    <a class="btn btn-warning btn-sm mr-2" id="appBtn" data-toggle="modal" data-target="#appModal"><i class="fas fa-check-circle"></i>&nbsp;Approval</a>
                <?php } elseif ($user->app2_name == $app2_name) { ?>
                    <a class="btn btn-warning btn-sm mr-2" id="appBtn2" data-toggle="modal" data-target="#appModal"><i class="fas fa-check-circle"></i>&nbsp;Approval</a>
                <?php } ?>
                <a class="btn btn-secondary btn-sm" href="<?= base_url('datanotifikasi') ?>"><i class="fas fa-chevron-left"></i>&nbsp;Back</a>
            </div>

            <div class="header">
                <h1>PT. MANDIRI CIPTA SEJAHTERA</h1>
                <h2>NOTIFIKASI</h2>
            </div>

            <div class="main-field">
                <p>Saya yang bertanda tangan dibawah ini :</p>
                <table>
                    <tr>
                        <td>Nama</td>
                        <td>:</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Jabatan</td>
                        <td>:</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Department</td>
                        <td>:</td>
                        <td></td>
                    </tr>
                </table>
                <div class="status1">
                    <p>
                        Mengajukan Izin ( <i class="fas fa-check" id="izin" style="display: inline;"></i> )
                        Tidak Masuk ( <i class="fas fa-check" id="izin" style="display: none;"></i> )
                        Pulang Awal ( <i class="fas fa-check" id="pulang" style="display: none;"></i> )
                        Datang Terlambat ( <i class="fas fa-check" id="terlambat" style="display: none;"></i> )
                    </p>
                </div>
                <table>
                    <tr>
                        <td>Tanggal</td>
                        <td>:</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Waktu</td>
                        <td>:</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Alasan</td>
                        <td>:</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td class="alasan">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ipsum laboriosam voluptate non accusantium modi inventore ab consequatur corporis id aspernatur.</td>
                    </tr>
                </table>
                <div class="status2">
                    <h1>DIISI OLEH ATASAN KARYAWAN BERSANGKUTAN</h1>
                    <p>
                        <span style="margin-right: 30px;">Notifikasi ini</span>
                        Disetujui ( <i class="fas fa-check" id="izin" style="display: inline;"></i> )
                        Tidak Disetujui ( <i class="fas fa-check" id="pulang" style="display: none;"></i> )
                    </p>
                </div>

                <div class="note">
                    <h4>Dengan catatan <span style="position: relative; left: 12px">:</span></h4>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Unde quam, autem labore praesentium perspiciatis enim magnam ex. Illo, soluta cumque.</p>
                </div>

                <div class="note-hc">
                    <h1>CATATAN HUMAN CAPITAL DEPARTEMENT</h1>
                    <table>
                        <tr>
                            <td>NOTIFIKASI KE</td>
                            <td>:</td>
                            <td>
                                <span id="tahun">teess</span>
                                <span>( TAHUN BERJALAN )</span>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="table-approve">
                    <table>
                        <tr>
                            <td>DEPT. HEAD</td>
                            <td>HC-DEPARTMENT</td>
                            <td>KARYAWAN</td>
                        </tr>
                        <tr style="height: 75px">
                            <td></td>
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
                $('#approvalForm').attr('action', '<?= site_url('datanotifikasi/approve') ?>');
            });

            $('#appBtn2').click(function() {
                $('#app_name').attr('name', 'app2_name');
                $('#app_keterangan').attr('name', 'app2_keterangan');
                $('#app_status').attr('name', 'app2_status');
                $('#approvalForm').attr('action', '<?= site_url('datanotifikasi/approve2') ?>');
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
                url: "<?php echo site_url('datanotifikasi/edit_data') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    // console.log(data);
                    // DATA NOTIFIKASI
                    $('#pengajuan').text(data['master']['pengajuan']);
                    $('#tanggal').text(data['master']['tgl_notifikasi']);
                    $('#nama_pembayar').text(data['nama']);
                    $('#jabatan').text(data['master']['jabatan']);
                    $('#tujuan').text(data['master']['tujuan']);
                    $('#waktu').text(data['master']['waktu']);
                    $('#alasan').text(data['master']['alasan']);
                    // DATA APPROVAL NOTIFIKASI
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

                    if (data['master']['app_keterangan'] != null) {
                        $('#keterangan').append(`<span class="form-control-plaintext">*${data['master']['app_keterangan']}</span>`);
                    }
                    if (data['master']['app2_keterangan'] != null) {
                        $('#keterangan').append(`<span class="form-control-plaintext">*${data['master']['app2_keterangan']}</span>`);
                    }

                    $('#melakukan').html(`<div class="signature-text text-center">${data['nama']}</div>`);
                    $('#mengetahui').html(`<div class="signature-text text-center">${data['master']['app_name']}</div>`);
                    $('#menyetujui').html(`<div class="signature-text text-center">${data['master']['app2_name']}</div>`);
                    $('#signature_mengetahui').html(`<div class="signature-text text-center">${data['master']['app_status'].toUpperCase()}</div>`);
                    $('#signature_menyetujui').html(`<div class="signature-text text-center">${data['master']['app2_status'].toUpperCase()}</div>`);

                    $('#divisiCol').html(data['master']['divisi']);
                    $('#prepaymentCol').html(data['master']['prepayment']);
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
                                location.href = "<?= base_url('datanotifikasi') ?>";
                            })
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error adding / update data');
                    }
                });

            });

            $('#keterangan').text('This prepayment is requested for the ongoing project development phase.');
        });
    </script>
</body>

</html>