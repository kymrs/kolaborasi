<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title_view ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header text-right">
                    <!-- <a class="btn btn-danger btn-sm" href="<?= base_url('datadeklarasi') ?>"><i class="fas fa-file-pdf"></i>&nbsp;Print Out</a> -->
                    <a class="btn btn-secondary btn-sm" href="<?= base_url('datadeklarasi') ?>"><i class="fas fa-chevron-left"></i>&nbsp;Back</a>
                </div>
                <div class="card-body">
                    <!-- CONTAINER -->
                    <div class="container mt-5">
                        <h5 class="text-center">PT. MANDIRI CIPTA SEJAHTERA</h5>
                        <h6 class="text-center">FORM DEKLARASI</h6>
                        <form>
                            <div class="form-group row">
                                <label for="tanggal" class="col-sm-2 col-form-label">Tanggal:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="tanggal" nama="tanggal">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama" class="col-sm-2 col-form-label">Nama:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="nama" placeholder="Masukkan Nama">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="jabatan" class="col-sm-2 col-form-label">Jabatan:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="jabatan" placeholder="Masukkan Jabatan">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="prepayment-untuk" class=" col-form-label">Telah/akan melakukan pembayaran kepada:</label>
                            </div>

                            <div class="form-group row">
                                <label for="nama_pembayaran" class="col-sm-2 col-form-label">Nama:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="nama_pembayaran" placeholder="Masukkan Nama Pembayaran">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="tujuan" class="col-sm-2 col-form-label">Tujuan:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="tujuan" placeholder="Masukkan Tujuan Pembayaran">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="sebesar" class="col-sm-2 col-form-label">Sebesar:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="sebesar" placeholder="Masukkan Jumlah Nominal">
                                    <!-- HIDDEN INPUT -->
                                    <input type="hidden" name="id" id="id" value="<?= $id ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-2">
                                    <?php if ($this->session->userdata('id_level') == 3) { ?>
                                        <button type="button" id="appBtn" class="btn btn-warning btn-block" data-toggle="modal" data-target="#appModal" data-id="<?= $id ?>">Approval</button>
                                    <?php } elseif ($this->session->userdata('id_level') == 4) { ?>
                                        <button type="button" id="appBtn2" class="btn btn-warning btn-block" data-toggle="modal" data-target="#appModal" data-id="<?= $id ?>">Approval</button>
                                    <?php } else { ?>

                                    <?php } ?>
                                </div>
                            </div>
                            <div class="table-responsive mt-5">
                                <table class="table table-bordered text-center">
                                    <thead>
                                        <tr>
                                            <th>Yang Melakukan</th>
                                            <th>Mengetahui</th>
                                            <th>Menyetujui</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td id="mengetahuiStts"></td>
                                            <td id="menyetujuiStts"></td>
                                        </tr>
                                        <tr>
                                            <td id="melakukan"></td>
                                            <td id="mengetahui"></td>
                                            <td id="menyetujui"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </form>
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

<script>
    $(document).ready(function() {
        // INISIASI VARIABLE
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
            $('#approvalForm').attr('action', '<?= site_url('datadeklarasi/approve') ?>');
        });

        $('#appBtn2').click(function() {
            $('#app_name').attr('name', 'app2_name');
            $('#app_keterangan').attr('name', 'app2_keterangan');
            $('#app_status').attr('name', 'app2_status');
            $('#approvalForm').attr('action', '<?= site_url('datadeklarasi/approve2') ?>');
        });

        $.ajax({
            url: "<?php echo site_url('datadeklarasi/edit_data') ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                console.log(data);
                // MENGISI FORM DEKLARASI
                $('#tanggal').val(data['tgl_deklarasi']).attr('readonly', true);
                $('#nama').val(data['nama_pengajuan']).attr('readonly', true);
                $('#jabatan').val(data['jabatan']).attr('readonly', true);
                $('#nama_pembayaran').val(data['nama_dibayar']).attr('readonly', true);
                $('#tujuan').val(data['tujuan']).attr('readonly', true);
                $('#sebesar').val(data['sebesar']).attr('readonly', true);

                // APPROVE MODAL
                if (data['app_status'] != null) {
                    status = data['app_status'];
                    keterangan = data['app_keterangan'];
                    $('#mengetahuiStts').html(status);
                }
                if (data['app2_status'] != null) {
                    status2 = data['app2_status'];
                    keterangan2 = data['app2_keterangan'];
                    $('#menyetujuiStts').html(status2);
                }

                //MENGISI APPROVAL
                $('#melakukan').html(data['nama_pengajuan']);
                $('#mengetahui').html(data['app_name']);
                $('#menyetujui').html(data['app2_name']);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
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
                            location.href = "<?= base_url('datadeklarasi') ?>";
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