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
                    <form id="form" method="post" action="<?=base_url('prepayment/add') ?>">
                        <div class="row">
                            <div class="col-md-6">
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
                                    <label class="col-sm-5">Kode Prepayment</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="kode_prepayment" name="kode_prepayment" readonly>
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
                                        <textarea class="form-control" id="tujuan" name="tujuan" rows="2"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- BUTTON TAMBAH FORM -->
                         <div class="mt-3">
                            <button type="button" class="btn btn-success btn-sm" id="add-row"><i class="fa fa-plus" aria-hidden="true"></i> Add</button>
                         </div>
                        <!-- TABLE INPUT -->
                        <div class="mt-2">
                        <table class="table">
                            <thead>
                                <tr>
                                <th scope="col">No</th>
                                <th scope="col">Rincian</th>
                                <th scope="col">Nominal</th>
                                <th scope="col">Keterangan</th>
                                <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody id="input-container">
                                <!-- CONTAINER INPUTAN -->
                            </tbody>
                            </table>
                        </div>
                        <!-- PENENTUAN UPDATE ATAU ADD -->
                        <input type="hidden" name="id" id="id" value="<?= $id ?>">
                        <?php if (!empty($aksi)) { ?>
                            <input type="hidden" name="aksi" id="aksi" value="<?= $aksi ?>">
                        <?php } ?>
                        <?php if ($id == 0) { ?>
                            <!-- <input type="hidden" name="kode" id="kode" value="<?= $kode ?>"> -->
                        <?php } ?>
                        <!-- END PENENTUAN UPDATE ATAU ADD -->
                        <button type="submit" class="btn btn-primary btn-sm aksi"></button>
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

        // MENGENERATE KODE PREPAYMENT SETELAH PILIH TANGGAL
        onSelect: function(dateText) {
            var id = dateText;
            $.ajax({
                url: "<?php echo site_url('prepayment/generate_kode') ?>",
                type: "POST",
                data: {
                    "date" : dateText
                },
                dataType: "JSON",
                success: function(data) {
                    $('#kode_prepayment').val(data.toUpperCase());
                },
                error: function(error) {
                alert("error" + error);
            }
            });
        }
    });

    $(document).ready(function() {

        // INISIASI VARIABEL JAVASCRIPT/JQUERY
        var id = $('#id').val();
        var aksi = $('#aksi').val();
        var kode = $('#kode').val();
        let inputCount = 0;

        //MENAMBAH FORM INPUTAN DI ADD FORM
        let rowCount = 0;

        function addRow() {
            rowCount++;
            const row = `
                <tr id="row-${rowCount}">
                    <td class="row-number">${rowCount}</td>
                    <td><input type="text" name="rincian[${rowCount}]" placeholder="Input ${rowCount}" /></td>
                    <td><input type="number" name="nominal[${rowCount}]" placeholder="Input ${rowCount}" /></td>
                    <td><input type="text" name="keterangan[${rowCount}]" placeholder="Input ${rowCount}" /></td>
                    <td><span class="btn delete-btn btn-danger" data-id="${rowCount}">Delete</span></td>
                </tr>
                `;
                $('#input-container').append(row);
            }

            function deleteRow(id) {
                $(`#row-${id}`).remove();
                // Reorder rows and update row numbers
                reorderRows();
            }

            function reorderRows() {
            $('#input-container tr').each(function(index) {
                const newRowNumber = index + 1;
                $(this).attr('id', `row-${newRowNumber}`);
                $(this).find('.row-number').text(newRowNumber);
                $(this).find('input').attr('name', `rincian[${newRowNumber}]`).attr('placeholder', `Input ${newRowNumber}`);
                $(this).find('input').attr('name', `nominal[${newRowNumber}]`).attr('placeholder', `Input ${newRowNumber}`);
                $(this).find('input').attr('name', `keterangan[${newRowNumber}]`).attr('placeholder', `Input ${newRowNumber}`);
                $(this).find('.delete-btn').attr('data-id', newRowNumber).text('Delete');
            });
            rowCount = $('#input-container tr').length; // Update rowCount to the current number of rows
            }

            $('#add-row').click(function() {
                addRow();
            });

            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id');
                deleteRow(id);
            });

        // MENGISI FORM UPDATE
        if (id == 0) {
            $('.aksi').text('Save');
        } else {
            $('.aksi').text('Update');
            $("select option[value='']").hide();
            $.ajax({
                url: "<?php echo site_url('prepayment/edit_data') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    // console.log(data);
                    moment.locale('id')
                    $('#id').val(data.id);
                    $('#kode_prepayment').val(data.kode_prepayment.toUpperCase()).attr('readonly', true);
                    $('#tgl_prepayment').val(moment(data.tgl_prepayment).format('DD-MM-YYYY'));
                    $('#nama').val(data.nama);
                    $('#jabatan').val(data.jabatan);
                    $('#prepayment').val(data.prepayment);
                    $('#tujuan').val(data.tujuan);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        // UNTUK TAMPILAN READ ONLY
        if (aksi == "read") {
            $('.aksi').hide();
            $('#id').prop('readonly', true);
            $('#tgl_prepayment').prop('disabled', true);
            $('#nama').prop('readonly', true);
            $('#jabatan').prop('disabled', true);
            $('#prepayment').prop('readonly', true);
            $('#tujuan').prop('readonly', true);
        }

        // INSERT ATAU UPDATE
        // $("#form").submit(function(e) {
        //     e.preventDefault();
        //     var $form = $(this);
        //     if (!$form.valid()) return false;
        //     var url;
        //     if (id == 0) {
        //         url = "<?php echo site_url('prepayment/add') ?>";
        //     } else {
        //         url = "<?php echo site_url('prepayment/update') ?>";
        //     }

        //     $.ajax({
        //         url: url,
        //         type: "POST",
        //         data: $('#form').serialize(),
        //         dataType: "JSON",
        //         success: function(data) {
        //             if (data.status) //if success close modal and reload ajax table
        //             {
        //                 Swal.fire({
        //                     position: 'center',
        //                     icon: 'success',
        //                     title: 'Your data has been saved',
        //                     showConfirmButton: false,
        //                     timer: 1500
        //                 }).then((result) => {
        //                     location.href = "<?= base_url('prepayment') ?>";
        //                 })
        //             }
        //         },
        //         error: function(jqXHR, textStatus, errorThrown) {
        //             alert('Error adding / update data');
        //         }
        //     });
        // }); 

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
            highlight: function(element) {
                $(element).addClass('is-invalid'); // Tambahkan kelas untuk menandai input tidak valid
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid'); // Hapus kelas jika input valid
            },
            focusInvalid: false, // Disable auto-focus on the first invalid field
});


    })
</script>