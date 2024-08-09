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
                    <form id="form">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-5">Kode Prepayment</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="kode_prepayment" name="kode_prepayment">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5">Tanggal Prepayment</label>
                                    <div class="col-sm-7">
                                        <div class="input-group date">
                                            <input type="text" class="form-control" name="tgl_prepayment" id="tgl_prepayment" placeholder="DD-MM-YYYY" autocomplete="off" readonly />
                                            <div class="input-group-append">
                                                <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5">Nama</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5">Jabatan</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" name="jabatan" id="jabatan">
                                            <option value="">-- Pilih --</option>
                                            <option value="marketing">Marketing</option>
                                            <option value="it">IT</option>
                                            <option value="ga">General Affair</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- SEBELAH KANAN -->
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-5">Prepayment</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="prepayment" name="prepayment" placeholder="Prepayment for....">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5">Tujuan</label>
                                    <div class="col-sm-7">
                                        <textarea class="form-control" id="exampleFormControlTextarea1" name="tujuan" rows="2"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- PENENTUAN UPDATE ATAU ADD -->
                        
                        <!-- END PENENTUAN UPDATE ATAU ADD -->
                        <button type="submit" class="btn btn-primary btn-sm aksi">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>

<script>
    
    $('#tgl_prepayment').datepicker({
        dateFormat: 'dd-mm-yy',
        minDate: new Date(),
    });

    $(document).ready(function() {

        // INSERT ATAU UPDATE
        $("#form").submit(function(e) {
            e.preventDefault();
            var $form = $(this);
            if (!$form.valid()) return false;
            var url;
            url = "<?php echo site_url('prepayment/add') ?>";

            $.ajax({
                url: url,
                type: "POST",
                data: $('#form').serialize(),
                dataType: "JSON",
                success: function(data) {
                    if (data.status) //if success close modal and reload ajax table
                    {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Your prepayment data has been saved',
                            showConfirmButton: false,
                            timer: 1500
                        }).then((result) => {
                            location.href = "<?= base_url('prepayment/add_form') ?>";
                        })
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                }
            });
        });

        // VALIDASI
        $("#form").validate({
            rules: {
                kode_prepayment: {
                    required: true,
                },
                tgl_prepayment: {
                    required: true,
                },
                nama: {
                    required: true,
                },
                jabatan: {
                    required: true,
                },
                prepayment: {
                    required: true,
                },
                tujuan: {
                    required: true,
                }
            },
            messages: {
                kode_prepayment: {
                    required: "Kode is required",
                },
                tgl_prepayment: {
                    required: "Tanggal is required",
                },
                nama: {
                    required: "Nama is required",
                },
                jabatan: {
                    required: "Jabatan is required",
                },
                prepayment: {
                    required: "Prepayment is required",
                },
                tujuan: {
                    required: "Tujuan is required",
                }
            },
            errorPlacement: function(error, element) {
                if (element.parent().hasClass('input-group')) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
        })


    })
</script>