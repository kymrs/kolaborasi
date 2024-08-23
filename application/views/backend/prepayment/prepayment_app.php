<style>
    .form-header {
        text-align: center;
        margin-bottom: 20px;
    }

    .table-container {
        margin-top: 20px;
    }

    .table th,
    .table td {
        text-align: left;
        vertical-align: middle;
    }

    .signatures {
        margin-top: 30px;
    }

    .signature-block {
        text-align: center;
        margin-top: 30px;
    }

    .note {
        font-size: 12px;
        margin-top: 10px;
    }
</style>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title_view ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header text-right">
                    <!-- <a class="btn btn-danger btn-sm" href="<?= base_url('prepayment') ?>"><i class="fas fa-file-pdf"></i>&nbsp;Print Out</a> -->
                    <a class="btn btn-secondary btn-sm" href="<?= base_url('prepayment') ?>"><i class="fas fa-chevron-left"></i>&nbsp;Back</a>
                </div>
                <div class="card-body">
                    <!-- CONTAINER -->
                    <div class="container mt-5">
                        <div class="form-header">
                            <h4>PT. MANDIRI CIPTA SEJAHTERA</h4>
                            <p>Divisi:
                            <div id="divisiCol"></div>
                            </p>
                            <p>Prepayment:
                            <div id="prepaymentCol"></div>
                            </p>
                            <h5>FORM PENGAJUAN PREPAYMENT</h5>
                        </div>


                        <div class="form-group row">
                            <label for="tanggal" class="col-sm-2 col-form-label">Tanggal:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="tanggal" nama="tanggal">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="nama" class="col-sm-2 col-form-label">Nama:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="nama" name="nama">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="jabatan" class="col-sm-2 col-form-label">Jabatan:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="jabatan" name="jabatan">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="prepayment-untuk" class=" col-form-label">Dengan ini bermaksud mengajukan prepayment untuk:</label>
                        </div>

                        <div class="form-group row">
                            <label for="tujuan" class="col-sm-2 col-form-label">Tujuan:</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" id="tujuan" name="tujuan" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="table-container">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Rincian</th>
                                        <th>Nominal</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody id="input-container">
                                    <!-- INPUT CONTAINER -->
                                    <!-- END INPUT CONTAINER -->
                                </tbody>
                            </table>
                        </div>

                        <div class="container">
                            <!-- Header and Content for "Yang Melakukan" -->
                            <div class="row text-center">
                                <div class="col-12 col-md-4 mb-3">
                                    <p>Yang Melakukan,</p>
                                    <!-- <div class="signature-pad">
                                        <canvas id="signature-pad" class="signature-pad" width=400 height=100></canvas>
                                    </div> -->
                                    <div><br><br></div>
                                    <div id="doName"></div>
                                    <!-- <button id="clear" class="btn btn-danger">Clear</button>
                                    <button id="save" class="btn btn-primary">Save</button> -->
                                </div>

                                <!-- Header and Content for "Mengetahui" -->
                                <div class="col-12 col-md-4 mb-3">
                                    <p>Mengetahui,</p>
                                    <br>
                                    <div id="knowStts"></div>
                                    <br>
                                    <div id="knowName"></div>
                                    <?php if (strtolower($this->session->userdata('fullname')) != 'finance') { ?>
                                        <button type="button" id="appBtn" class="btn btn-warning btn-block" data-toggle="modal" data-target="#appModal" data-id="<?= $id ?>" disabled>Approval</button>
                                    <?php } else { ?>
                                        <button type="button" id="appBtn" class="btn btn-warning btn-block" data-toggle="modal" data-target="#appModal" data-id="<?= $id ?>">Approval</button>
                                    <?php } ?>
                                </div>

                                <!-- Header and Content for "Menyetujui" -->
                                <div class="col-12 col-md-4 mb-3">
                                    <p>Menyetujui,</p>
                                    <br>
                                    <div id="agreeStts"></div>
                                    <br>
                                    <div id="agreeName"></div>
                                    <?php if (strtolower($this->session->userdata('fullname')) != 'head manager') { ?>
                                        <button type="button" id="appBtn2" class="btn btn-warning btn-block" data-toggle="modal" data-target="#appModal" data-id="<?= $id ?>" disabled>Approval</button>
                                    <?php } else { ?>
                                        <button type="button" id="appBtn2" class="btn btn-warning btn-block" data-toggle="modal" data-target="#appModal" data-id="<?= $id ?>">Approval</button>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="id" id="id" value="<?= $id ?>">
                        <div class="note text-center" id="note_id">
                            <p>* Pengajuan prepayment maksimal 10 hari dari tanggal pengajuan</p>
                        </div>

                    </div>
                    <!-- END CONTAINER -->
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
                        <input type="hidden" id="hidden_id" name="hidden_id" value="">
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

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

<script>
    $(document).ready(function() {
        //INISIAI VARIABLE JQUERY
        var id = $('#id').val();
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
                $('#tanggal').val(data['master']['tgl_prepayment']).attr('readonly', true);
                $('#nama').val(data['master']['nama']).attr('readonly', true);
                $('#jabatan').val(data['master']['jabatan']).attr('readonly', true);
                $('#tujuan').val(data['master']['tujuan']).attr('readonly', true);
                // DATA APPROVAL PREPAYMENT
                var nama, status, keterangan, nama2, status2, keterangan2, url;

                // Memeriksa apakah data yang mengetahui ada
                if (data['master']['app_name'] != null) {
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
                if (data['master']['app2_name'] != null) {
                    nama2 = data['master']['app2_name'];
                    status2 = data['master']['app2_status'];
                    keterangan2 = data['master']['app2_keterangan'];
                    url = "<?php echo site_url('prepayment/approve2') ?>";
                    $('#note_id').append(`<p>* ${keterangan2}</p>`);
                } else {
                    nama2 = `<br><br><br>`;
                    status2 = `_____________________`;
                }

                $('#doName').html(data['master']['nama']);
                $('#knowName').html(nama);
                $('#knowStts').html(status);
                $('#agreeName').html(nama2);
                $('#agreeStts').html(status2);

                $('#divisiCol').html(`<p>${data['master']['divisi'].toUpperCase()}</p>`);
                $('#prepaymentCol').html(`<p>${data['master']['prepayment']}</p>`);


                //DATA PREPAYMENT DETAIL
                let total = 0;
                for (let index = 0; index < data['transaksi'].length; index++) {
                    const row = `<tr>
                                    <td><input type="text" class="form-control" name="rincian[${index + 1}]" value="${data['transaksi'][index]['rincian']}" readonly></td>
                                    <td><input type="text" class="form-control" name="nominal[${index + 1}]" value="${data['transaksi'][index]['nominal']}" readonly></td>
                                    <td><input type="text" class="form-control" name="keterangan[${index + 1}]" value="${data['transaksi'][index]['keterangan']}"readonly></td>
                                </tr>`;
                    $('#input-container').append(row);
                    total += Number(data['transaksi'][index]['nominal']);
                }
                const ttl_row = `<tr>
                                        <td colspan="2" class="text-right">Total:</td>
                                        <td><input type="text" value="${total}" class="form-control" name="total" readonly></td>
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
    var canvas = document.getElementById('signature-pad');
    var signaturePad = new SignaturePad(canvas);

    // Clear button
    document.getElementById('clear').addEventListener('click', function() {
        signaturePad.clear();
    });

    // Save button
    document.getElementById('save').addEventListener('click', function() {
        if (signaturePad.isEmpty()) {
            alert("Please provide a signature first.");
        } else {
            var dataURL = signaturePad.toDataURL('image/png');
            // Send the data to the server
            $.ajax({
                url: '<?= site_url('prepayment/signature'); ?>',
                type: 'POST',
                data: {
                    imgBase64: dataURL
                },
                success: function(response) {
                    alert('Signature saved successfully!');
                },
                error: function() {
                    alert('Error saving signature.');
                }
            });
        }
    });
</script>