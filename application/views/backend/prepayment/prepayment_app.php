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
                                <div class="row text-center">
                                    <!-- Header -->
                                    <div class="col-md-4">
                                        <p>Yang Melakukan,</p>
                                    </div>
                                    <div class="col-md-4">
                                        <p>Mengetahui,</p>
                                    </div>
                                    <div class="col-md-4">
                                        <p>Menyetujui,</p>
                                    </div>
                                </div>

                                <div class="row text-center">
                                    <!-- Empty space for signature -->
                                    <div class="col-md-4">
                                        <br><br><br>
                                    </div>
                                    <div class="col-md-4" id="knowName">
                                        <!-- Status will be inserted here -->
                                    </div>
                                    <div class="col-md-4" id="agreeName">
                                        <!-- Status will be inserted here -->
                                    </div>
                                </div>

                                <div class="row text-center">
                                    <!-- Signature line -->
                                    <div class="col-md-4">
                                        <p>_____________________</p>
                                    </div>
                                    <div class="col-md-4" id="knowStts">
                                        <!-- Name will be inserted here -->
                                    </div>
                                    <div class="col-md-4" id="agreeStts">
                                        <!-- Name will be inserted here -->
                                    </div>
                                </div>

                                <div class="row text-center">
                                    <!-- Buttons to trigger modal -->
                                    <div class="col-md-4">
                                        <!-- This column is left empty -->
                                    </div>
                                    <div class="col-md-4">
                                        <button type="button" id="appBtn" class="btn btn-secondary" data-toggle="modal" data-target="#appModal" data-id="<?= $id ?>">Click Me</button>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="button" id="appBtn2" class="btn btn-secondary" data-toggle="modal" data-target="#appModal" data-id="<?= $id ?>">Click Me</button>
                                    </div>
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

<!-- Modal -->
<div class="modal fade" id="appModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Approval</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="<?= base_url('prepayment/approve') ?>">
            <div class="form-group">
                <label for="app_name">Nama</label>
                <input type="text" class="form-control" name="app_name" id="app_name" aria-describedby="emailHelp">
                <!-- HIDDEN INPUT -->
                 <input type="hidden" id="hidden_id" name="hidden_id" value="">
            </div>
            <div class="form-group">
                <label for="app_status">Approve</label>
                <select id="app_status" name="app_status" class="form-control">
                    <option selected disabled>Choose...</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                    <option value="revised">Revised</option>
                </select>
            </div>
            <div class="form-group">
                <label for="app_keterangan" class="col-form-label">Keterangan:</label>
                <textarea class="form-control" name="app_keterangan" id="app_keterangan"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>

<script>

    // $('.show-app').on('click', function() {
    //     event.preventDefault();

    //     Swal.fire({
    //         title: "Approvals",
    //         html: `
    //        <div style="display: flex; flex-direction: column; align-items: flex-start; gap: 10px;">
    //             <div style="display: flex; justify-content: space-between; width: 100%;">
    //                 <label for="swal-input1" style="width: 30%;">Nama</label>
    //                 <input id="swal-input1" class="swal2-input" placeholder="Input 1" style="width: 65%;">
    //             </div>
                
    //             <div style="display: flex; justify-content: space-between; width: 100%;">
    //                 <label for="swal-select" style="width: 30%;">Status</label>
    //                 <select id="swal-select" class="swal2-select" style="width: 65%;">
    //                     <option value="" disabled selected>Select an option</option>
    //                     <option value="Option 1">Option 1</option>
    //                     <option value="Option 2">Option 2</option>
    //                     <option value="Option 3">Option 3</option>
    //                 </select>
    //             </div>

    //             <div style="display: flex; justify-content: space-between; width: 100%;">
    //                 <label for="swal-textarea" style="width: 30%;">Keterangan</label>
    //                 <textarea id="swal-textarea" class="swal2-textarea" placeholder="Enter your text here" style="width: 65%;"></textarea>
    //             </div>
                
    //         </div>
    //         `,
    //         focusConfirm: false,
    //         allowOutsideClick: false,  // Prevents the popup from closing when clicking outside
    //         preConfirm: function() {
    //         return [
    //             $('#swal-input1').val(),
    //             $('#swal-select').val(),
    //             $('#swal-textarea').val(),
    //             $('#id').val()
    //         ];
    //         }
    //     }).then(function(result) {
    //         if (result.isConfirmed && result.value) {
    //             // console.log("Input 1:", result.value[0]);
    //             // console.log("Select:", result.value[1]);
    //             // console.log("Textarea:", result.value[2]);
    //             // console.log("id:", result.value[3]);

    //             $.ajax({
    //                 url: ,
    //                 type: "POST",
    //                 data: result.value,
    //                 dataType: "JSON",
    //                 success: function(data) {
    //                     console.log(data);
    //                 }
    //             });

    //         Swal.fire(`You entered: Input 1: ${result.value[0]}
    //             Textarea: ${result.value[1]}
    //             Select: ${result.value[2]}`);
    //         }
    //     });
    //     });


    $(document).ready(function(){
        //INISIAI VARIABLE JQUERY
        var id = $('#id').val();

        $('#appModal').on('show.bs.modal', function(e) {
            var id = $(e.relatedTarget).data('id');
            $('#hidden_id').val(id);
        });

        $('#appBtn').click(function() {
            $('#app_name').attr('name', 'app_name');
            $('#app_keterangan').attr('name', 'app_keterangan');
            $('#app_status').attr('name', 'app_status');
            $('form').attr('action', '<?= base_url("prepayment/approve") ?>');
        });

        $('#appBtn2').click(function() {
            $('#app_name').attr('name', 'app2_name');
            $('#app_keterangan').attr('name', 'app2_keterangan');
            $('#app_status').attr('name', 'app2_status');
            $('form').attr('action', '<?= base_url("prepayment/approve2") ?>');
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
                    var nama, status, nama2, status2;

                    // Memeriksa apakah data yang mengetahui ada
                    if (data['master']['app_name'] != null) {
                        nama = data['master']['app_name'];
                        status = data['master']['app_status'];
                    } else {
                        nama = `<br><br><br>`;
                        status = `_____________________`;
                    }

                    // Memeriksa apakah data yang menyetujui ada
                    if (data['master']['app2_name'] != null) {
                        nama2 = data['master']['app2_name'];
                        status2 = data['master']['app2_status'];
                    } else {
                        nama2 = `<br><br><br>`;
                        status2 = `_____________________`;
                    }

                    $('#knowName').html(nama);   
                    $('#knowStts').html(status);
                    $('#agreeName').html(nama2); 
                    $('#agreeStts').html(status2);


                    //DATA PREPAYMENT DETAIL
                    let total = 0;
                    for (let index = 0; index < data['transaksi'].length; index++) {
                        const row = `<tr>
                                        <td><input type="text" class="form-control" name="rincian[${index + 1}]" value="${data['transaksi'][index]['rincian']}" readonly></td>
                                        <td><input type="text" class="form-control" name="nominal[${index + 1}]" value="${data['transaksi'][index]['nominal']}" readonly></td>
                                        <td><input type="text" class="form-control" name="keterangan[${index + 1}]" value="${data['transaksi'][index]['keterangan']}"readonly><td>
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
</script>