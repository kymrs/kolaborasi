<head>
    <?php $this->load->view('template/header'); ?>
    <style>
        body .container-main {
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

        .table-approve table tr:nth-child(1) td:nth-child(1),
        .table-approve table tr:nth-child(1) td:nth-child(2),
        .table-approve table tr:nth-child(1) td:nth-child(3) {
            width: 25%;
        }
    </style>
</head>

<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <div class="container-main">
                <div class="form-container">
                    <div class="d-flex justify-content-end mb-3">
                        <?php if ($user->app_name == $app_name && $user->app2_status != 'rejected' && !in_array($user->status, ['approved'])) { ?>
                            <a class="btn btn-warning btn-sm mr-2" id="appBtn" data-toggle="modal" data-target="#appModal"><i class="fas fa-check-circle"></i>&nbsp;Approval</a>
                        <?php } elseif ($user->app2_name == $app2_name && !in_array($user->app2_status, ['approved', 'rejected'])) { ?>
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
                                <td id="namaTxt"></td>
                            </tr>
                            <tr>
                                <td>Jabatan</td>
                                <td>:</td>
                                <td id="jabatanTxt"></td>
                            </tr>
                            <tr>
                                <td>Department</td>
                                <td>:</td>
                                <td id="departementTxt"></td>
                            </tr>
                        </table>
                        <div class="status1">
                            <p>
                                Mengajukan Izin :
                                ( <i class="fas fa-check" id="izin" style="display: none;"></i> )Tidak Masuk
                                ( <i class="fas fa-check" id="pulang" style="display: none;"></i> )Pulang Awal
                                ( <i class="fas fa-check" id="terlambat" style="display: none;"></i> )Datang Terlambat
                            </p>
                        </div>
                        <table>
                            <tr>
                                <td>Tanggal</td>
                                <td>:</td>
                                <td id="tanggalTxt"></td>
                            </tr>
                            <tr>
                                <td>Waktu</td>
                                <td>:</td>
                                <td id="waktuTxt"></td>
                            </tr>
                            <tr>
                                <td>Alasan</td>
                                <td>:</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td class="alasan" id="alasanTxt">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ipsum laboriosam voluptate non accusantium modi inventore ab consequatur corporis id aspernatur.</td>
                            </tr>
                        </table>
                        <div class="status2">
                            <h1>DIISI OLEH ATASAN KARYAWAN BERSANGKUTAN</h1>
                            <p>
                                <span style="margin-right: 30px;">Notifikasi ini</span>
                                Disetujui ( <i class="fas fa-check" id="boleh" style="display: none;"></i> )
                                Tidak Disetujui ( <i class="fas fa-check" id="tidakBoleh" style="display: none;"></i> )
                            </p>
                        </div>

                        <div class="note">
                            <h4>Dengan catatan <span style="position: relative; left: 12px">:</span></h4>
                            <p id="catatan">Lorem ipsum dolor sit amet consectetur adipisicing elit. Unde quam, autem labore praesentium perspiciatis enim magnam ex. Illo, soluta cumque.</p>
                        </div>

                        <div class="note-hc">
                            <h1>CATATAN HUMAN CAPITAL DEPARTEMENT</h1>
                            <table>
                                <tr>
                                    <td>NOTIFIKASI KE</td>
                                    <td>:</td>
                                    <td>
                                        <span id="no_notifikasi"><?= $ke ?></span>
                                        <span id="tahun_notifikasi">( <?= date('Y', strtotime($user->created_at)) ?> )</span>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="table-approve">
                            <table>
                                <tr>
                                    <td>KARYAWAN</td>
                                    <td>DEPT. HEAD</td>
                                    <td>HC-DEPARTMENT</td>
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
                        <input type="hidden" name="hidden_id" id="hidden_id" value="<?= $id ?>">
                    </div>
                    <div class="form-group" id="append_catatan">
                        <!-- APPEND CATATAN -->
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
            // $('#hidden_id').val(id);
        });

        $('#appBtn').click(function() {
            $('#app_keterangan').attr('name', 'app_keterangan');
            $('#app_status').attr('name', 'app_status');
            $('#approvalForm').attr('action', '<?= site_url('datanotifikasi/approve') ?>');

            if ($('#app_catatan').length === 0) {
                $('#append_catatan').append(`<label for="app_catatan" class="col-form-label">Catatan:</label>
                            <textarea class="form-control" name="app_catatan" id="app_catatan" placeholder="Add your comments here"></textarea>`);
            }

            $("#approvalForm").validate().settings.rules[`app_catatan`] = {
                required: true
            };

            $("#approvalForm").valid();

            $.ajax({
                url: "<?php echo site_url('datanotifikasi/edit_data') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {

                    var nama, status, keterangan;
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
                    if (data['master']['catatan'] != null || data['master']['catatan'] != '') {
                        $('#app_catatan').val(data['master']['catatan']);
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
            $('#approvalForm').attr('action', '<?= site_url('datanotifikasi/approve2') ?>');

            $.ajax({
                url: "<?php echo site_url('datanotifikasi/edit_data') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    // DATA APPROVAL NOTIFIKASI
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
            url: "<?php echo site_url('datanotifikasi/edit_data') ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                moment.locale('id');
                // DATA NOTIFIKASI
                $('#namaTxt').text(data.nama);
                $('#departementTxt').text(data['master']['departemen']);
                $('#tanggalTxt').text(moment(data['master']['tgl_notifikasi']).format('DD MMMM YYYY'));
                $('#nama_pembayar').text(data['nama']);
                $('#jabatanTxt').text(data['master']['jabatan']);
                $('#tujuanTxt').text(data['master']['tujuan']);
                $('#waktuTxt').text(data['master']['waktu']);
                $('#alasanTxt').text(data['master']['alasan']);
                $('#catatan').text(data['master']['catatan']);

                // STATUS IZIN
                if (data['master']['pengajuan'] === 'izin') {
                    $('#izin').show();
                } else if (data['master']['pengajuan'] === 'pulang awal') {
                    $('#pulang').show();
                } else if (data['master']['pengajuan'] === 'datang terlambat') {
                    $('#terlambat').show();
                }

                // STATUS PERSETUJUAN
                if (data['master']['app_status'] == 'approved') {
                    $('#boleh').show();
                }
                if (data['master']['app_status'] == 'rejected') {
                    $('#tidakBoleh').show();
                }

                //KETERANGAN
                if (data['master']['app_keterangan'] != null) {
                    $('#keterangan').append(`<span class="form-control-plaintext">*${data['master']['app_keterangan']}</span>`);
                }
                if (data['master']['app2_keterangan'] != null) {
                    $('#keterangan').append(`<span class="form-control-plaintext">*${data['master']['app2_keterangan']}</span>`);
                }

                //DATE APPROVAL 1
                if (data['master']['app_date'] == null) {
                    date = '';
                }
                if (data['master']['app_date'] != null) {
                    date = data['master']['app_date'];
                }

                //DATE APPROVAL 2
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
                                location.href = "<?= base_url('datanotifikasi') ?>";
                            })
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error adding / update data');
                    }
                });
            }
        });

        $('#keterangan').text('This prepayment is requested for the ongoing project development phase.');
    });
</script>