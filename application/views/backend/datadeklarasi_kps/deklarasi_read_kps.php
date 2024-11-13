<head>
    <?php $this->load->view('template/header'); ?>
    <style>
        body .container-main {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
            padding: 0;
            color: #333;
        }

        .form-container {
            max-width: 800px;
            margin: 15px auto;
            padding: 25px;
            border: 1px solid #e0e0 e0;
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        /* header */
        .header {
            height: 60px;
        }

        .header .logo {
            width: 140px;
            position: relative;
            bottom: 50px;
        }

        .header .title {
            width: 100%;
            position: relative;
            bottom: 90px;
        }

        .header .title h1 {
            margin-bottom: 25px;
        }

        .header .title h1,
        .header .title h2 {
            font-size: 1.4rem;
            font-weight: bold;
            text-align: center;
        }

        /* Main Field */
        .main-field table {
            margin-top: 20px;
            width: 100%;
        }

        .main-field table tr td {
            padding: 5px;
        }

        .main-field table tr td:nth-child(1) {
            width: 16%;
        }

        .main-field table tr td:nth-child(2) {
            width: 2%;
        }

        .main-field table tr td:nth-child(3) {
            width: 82%;
            border-bottom: 1.5px solid #444;
        }

        /* Table Approve */
        .table-approve {
            margin-top: 35px;
            /* border: 1px solid #444; */
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

            /* header */
            .header {
                height: 100px;
            }

            .header .logo {
                width: 200px;
                position: relative;
                bottom: 50px;
            }

            .header .title {
                width: 100%;
                position: relative;
                bottom: 30px;
            }
        }
    </style>
</head>

<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <div class="container-main">
                <div class="form-container">
                    <div class="d-flex justify-content-end mb-3">
                        <?php if ($user->app_name == $app_name && !in_array($user->app2_status, ['revised', 'rejected']) && !in_array($user->status, ['approved'])) { ?>
                            <a class="btn btn-warning btn-sm mr-2" id="appBtn" data-toggle="modal" data-target="#appModal"><i class="fas fa-check-circle"></i>&nbsp;Approval</a>
                        <?php } elseif ($user->app2_name == $app2_name && !in_array($user->app2_status, ['approved', 'rejected']) && $user->app_status == 'approved') { ?>
                            <a class="btn btn-warning btn-sm mr-2" id="appBtn2" data-toggle="modal" data-target="#appModal"><i class="fas fa-check-circle"></i>&nbsp;Approval</a>
                        <?php } ?>
                        <a class="btn btn-secondary btn-sm" onclick="history.back()"><i class="fas fa-chevron-left"></i>&nbsp;Back</a>
                    </div>

                    <!-- Header Section -->
                    <div class="header">
                        <img src="<?= base_url('assets/backend/img/kps.png') ?>" alt="" class="logo">
                        <div class="title">
                            <!-- <h1>SEBELASWARNA</h1> -->
                            <h2>FORM DEKLARASI</h2>
                        </div>
                    </div>

                    <div class="main-field">
                        <table>
                            <tr>
                                <td>Tanggal</td>
                                <td>:</td>
                                <td class="line" id="tanggalTxt"></td>
                            </tr>
                            <tr>
                                <td>Nama</td>
                                <td>:</td>
                                <td class="line" id="namaTxt"></td>
                            </tr>
                            <tr>
                                <td>Jabatan</td>
                                <td>:</td>
                                <td class="line" id="jabatanTxt"></td>
                            </tr>
                            <tr>
                                <td colspan="3">Telah/akan melakukan pembayaran kepada :</td>
                            </tr>
                            <tr>
                                <td>Nama</td>
                                <td>:</td>
                                <td class="line" id="nama2Txt"></td>
                            </tr>
                            <tr>
                                <td>Tujuan</td>
                                <td>:</td>
                                <td class="line" id="tujuanTxt"></td>
                            </tr>
                            <tr>
                                <td>Sebesar</td>
                                <td>:</td>
                                <td class="line" id="sebesarTxt"></td>
                                <!-- HIDDEN INPUT -->
                                <input type="hidden" name="hidden_id" id="hidden_id" value="<?= $id ?>">
                            </tr>
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
            $('#approvalForm').attr('action', '<?= site_url('datadeklarasi_kps/approve') ?>');

            // Additional logic to dynamically load data into the form
            $.ajax({
                url: "<?php echo site_url('datadeklarasi_kps/edit_data') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    // DATA APPROVAL PREPAYMENT
                    var nama, status, Keterangan;

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
                    if (data['master']['app_date'] == null) {
                        date = '';
                    }
                    if (data['master']['app_date'] != null) {
                        date = data['master']['app_date'];
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
            $('#approvalForm').attr('action', '<?= site_url('datadeklarasi_kps/approve2') ?>');

            // Additional logic to dynamically load data into the form
            $.ajax({
                url: "<?php echo site_url('datadeklarasi_kps/edit_data') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    // DATA APPROVAL PREPAYMENT
                    var nama2, status2, keterangan2, url;

                    // Memeriksa apakah data yang menyetujui ada
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
                    if (data['master']['app2_date'] == null) {
                        date2 = '';
                    }
                    if (data['master']['app2_date'] != null) {
                        date2 = data['master']['app2_date'];
                    }
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
            url: "<?php echo site_url('datadeklarasi_kps/edit_data') ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                moment.locale('id');
                // Format tampilan dengan pemisah ribuan
                formatedNumber = data['master']['sebesar'].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                // DATA PREPAYMENT
                $('#tanggalTxt').text(moment(data['master']['tgl_deklarasi']).format('DD MMMM YYYY'));
                $('#namaTxt').text(data['nama']);
                $('#jabatanTxt').text(data['master']['jabatan']);
                $('#nama2Txt').text(data['master']['nama_dibayar']);
                $('#tujuanTxt').text(data['master']['tujuan']);
                $('#sebesarTxt').text(formatedNumber);
                if ((data['master']['app_keterangan'] !== null && data['master']['app_keterangan'] !== '') ||
                    (data['master']['app2_keterangan'] !== null && data['master']['app2_keterangan'] !== '')) {
                    $('#keterangan').append(`<span>Keterangan :</span>`);
                }
                if (data['master']['app_keterangan'] != '' && data['master']['app_keterangan'] != null) {
                    $('#keterangan').append(`<span class="form-control-plaintext">*(${data['master']['app_name']}) ${data['master']['app_keterangan']}</span>`);
                }
                if (data['master']['app2_keterangan'] != '' && data['master']['app2_keterangan'] != null) {
                    $('#keterangan').append(`<span class="form-control-plaintext">*(${data['master']['app2_name']}) ${data['master']['app2_keterangan']}</span>`);
                }
                // DATA APPROVAL PREPAYMENT
                var nama, status, keterangan, nama2, status2, keterangan2, url;

                // Memeriksa apakah data yang mengetahui ada
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
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });

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
                                window.history.back(); // Kembali ke halaman sebelumnya
                            })
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error adding / update data');
                    }
                });
            }
        });
    });
</script>