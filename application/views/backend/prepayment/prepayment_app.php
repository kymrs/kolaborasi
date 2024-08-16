<style>
        .form-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .table-container {
            margin-top: 20px;
        }
        .table th, .table td {
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
                    <a class="btn btn-secondary btn-sm" href="<?= base_url('prepayment') ?>"><i class="fas fa-chevron-left"></i>&nbsp;Back</a>
                </div>
                <div class="card-body">
                    <!-- CONTAINER -->
                    <div class="container mt-5">
                        <div class="form-header">
                            <h4>PT. MANDIRI CIPTA SEJAHTERA</h4>
                            <p>Divisi: <span>_____________________</span></p>
                            <p>Prepayment: <span>_____________________</span></p>
                            <h5>FORM PENGAJUAN PREPAYMENT</h5>
                        </div>

                        <form>
                            <div class="form-group row">
                                <label for="tanggal" class="col-sm-2 col-form-label">Tanggal:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="tanggal" name="tanggal">
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
                                    <tbody>
                                        <tr>
                                            <td><input type="text" class="form-control" name="rincian1"></td>
                                            <td><input type="text" class="form-control" name="nominal1"></td>
                                            <td><input type="text" class="form-control" name="keterangan1"></td>
                                        </tr>
                                        <tr>
                                            <td><input type="text" class="form-control" name="rincian2"></td>
                                            <td><input type="text" class="form-control" name="nominal2"></td>
                                            <td><input type="text" class="form-control" name="keterangan2"></td>
                                        </tr>
                                        <tr>
                                            <td><input type="text" class="form-control" name="rincian3"></td>
                                            <td><input type="text" class="form-control" name="nominal3"></td>
                                            <td><input type="text" class="form-control" name="keterangan3"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="text-right">Total:</td>
                                            <td><input type="text" class="form-control" name="total"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="signatures row">
                                <div class="col-sm-4 signature-block">
                                    <p>Yang Melakukan,</p>
                                    <br><br><br>
                                    <p>_____________________</p>
                                </div>
                                <div class="col-sm-4 signature-block">
                                    <p>Mengetahui,</p>
                                    <br><br><br>
                                    <p>_____________________</p>
                                </div>
                                <div class="col-sm-4 signature-block">
                                    <p>Menyetujui,</p>
                                    <br><br><br>
                                    <p>_____________________</p>
                                </div>
                            </div>
                            <input type="hidden" name="id" id="id" value="<?= $id ?>">
                            <div class="note text-center">
                                <p>* Pengajuan prepayment maksimal 10 hari dari tanggal pengajuan</p>
                            </div>
                        </form>
                    </div>
                    <!-- END CONTAINER -->
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>

<script>
    $(document).ready(function(){
        //INISIAI VARIABLE JQUERY
        var id = $('#id').val();

        $.ajax({
                url: "<?php echo site_url('prepayment/edit_data') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
    });
</script>