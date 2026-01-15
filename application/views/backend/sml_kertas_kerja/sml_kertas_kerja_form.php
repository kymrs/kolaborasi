<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/42.0.0/ckeditor5.css" />
<style>
    .ck.ck-content[role='textbox'] {
        font-size: 13px;
        line-height: 1.5;
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
                    <a class="btn btn-primary btn-sm" href="<?= base_url('sml_kertas_kerja') ?>"><i class="fas fa-chevron-left"></i>&nbsp;Back</a>
                </div>
                <div class="card-body">
                    <form id="form">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3">No. Dokumen</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="no_dok" name="no_dok">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3">No. Dokumen</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="no_dok" name="no_dok">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="id" id="id" value="<?= $id ?>">
                        <?php if (!empty($aksi)) { ?>
                            <input type="hidden" name="aksi" id="aksi" value="<?= $aksi ?>">
                        <?php } ?>
                        <button type="submit" class="btn btn-primary btn-sm aksi"></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>

<script type="importmap">
    {
        "imports": {
            "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/42.0.0/ckeditor5.js",
            "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/42.0.0/"
        }
    }
</script>

<script type="module">
    var aksi = $('#aksi').val();

    import {
        ClassicEditor,
        Essentials,
        Bold,
        Italic,
        Alignment,
        Font,
        Paragraph,
        Indent,
        IndentBlock,
    } from 'ckeditor5';

    ClassicEditor
        .create(document.querySelector('#peserta'), {
            plugins: [Essentials, Bold, Italic, Alignment, Font, Paragraph, Indent, IndentBlock],
            toolbar: {
                items: [
                    'undo', 'redo', '|', 'bold', 'italic', '|', 'alignment', '|',
                    'outdent', 'indent', 'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor'
                ],
            },
        })
        .then(editor => {
            if (aksi == "read") {
                editor.enableReadOnlyMode("peserta");
                console.log(editor);
            }
        }).catch(error => {
            console.error(error);
        });

    ClassicEditor
        .create(document.querySelector('#konten'), {
            plugins: [Essentials, Bold, Italic, Alignment, Font, Paragraph, Indent, IndentBlock],
            toolbar: {
                items: [
                    'undo', 'redo', '|', 'bold', 'italic', '|', 'alignment', '|',
                    'outdent', 'indent', 'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor'
                ],
            },
        })
        .then(editor => {
            if (aksi == "read") {
                editor.enableReadOnlyMode("konten");
                console.log(editor);
            }
        }).catch(error => {
            console.error(error);
        });
</script>

<script>
    $(document).ready(function() {
        var id = $('#id').val();
        var aksi = $('#aksi').val();
        var kode = $('#kode').val();

        if (id == 0) {
            $('.aksi').text('Save');
            $('#no_dok').val(kode).attr('readonly', true);
            $('#lihat_foto').hide();
        } else {
            $('.aksi').text('Update');
            $("select option[value='']").hide();
            $('#lihat_foto').hide();
            $.ajax({
                url: "<?php echo site_url('sml_kertas_kerja/edit_data') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    moment.locale('id')
                    $('#id').val(data.id);
                    $('#no_dok').val(data.no_dok).attr('readonly', true);
                    $('#agenda').val(data.agenda);
                    $('#date').val(moment(data.date).format('DD-MM-YYYY'));
                    $('#start_time').val(data.start_time);
                    $('#end_time').val(data.end_time);
                    $('#lokasi').val(data.lokasi);
                    $('#peserta').val(data.peserta);
                    $("#foto").prop('required', false);
                    $('#foto_label').text(data.foto);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        if (aksi == "read") {
            $('.aksi').hide();
            $('#lihat_foto').show();
            $('#id').prop('readonly', true);
            $('#no_dok').prop('readonly', true);
            $('#agenda').prop('readonly', true);
            $('#date').prop('disabled', true);
            $('#start_time').prop('readonly', true);
            $('#end_time').prop('readonly', true);
            $('#lokasi').prop('readonly', true);
            $('#foto').prop('disabled', true);
        }


        $("#form").submit(function(e) {
            e.preventDefault();
            var url;
            if (id == 0) {
                url = "<?php echo site_url('sml_kertas_kerja/add') ?>";
            } else {
                url = "<?php echo site_url('sml_kertas_kerja/update') ?>";
            }

            if ($('#peserta').val() == "" || $('#konten').val() == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oopss...',
                    text: 'Peserta atau konten harus diisi!!!',
                    showConfirmButton: false,
                    timer: 1500
                })
            } else {
                $.ajax({
                    url: url,
                    type: "POST",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    async: false,
                    dataType: "JSON",
                    success: function(data) {
                        if (data.status) //if success close modal and reload ajax table
                        {
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'Your data has been saved',
                                showConfirmButton: false,
                                timer: 1500
                            }).then((result) => {
                                location.href = "<?= base_url('sml_kertas_kerja') ?>";
                            })
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error adding / update data');
                    }
                });
            }
        });
    })

    $('#date').datepicker({
        dateFormat: 'dd-mm-yy',
        minDate: new Date()
    });

    $('#st').datetimepicker({
        format: 'HH:mm',
    });

    $('#et').datetimepicker({
        format: 'HH:mm',
    });

    function cek1() {
        start = $('#start_time').val();
        end = $('#end_time').val();
        hours = end.split(':')[0] - start.split(':')[0];
        minutes = end.split(':')[1] - start.split(':')[1];

        minutes = minutes.toString().length < 2 ? '0' + minutes : minutes;
        if (minutes < 0) {
            hours--;
            minutes = 60 + minutes;
        }
        hours = hours.toString().length < 2 ? '0' + hours : hours;

        if ($('#end_time').val() != "") {
            if (hours < 0) {
                Swal.fire({
                    position: 'center',
                    icon: 'warning',
                    title: 'Jam Mulai tidak boleh lebih dari Jam Selesai',
                    showConfirmButton: false,
                    timer: 2000
                });
                $('#start_time').val('');
            }
        }
    }

    function cek2() {
        start = $('#start_time').val();
        end = $('#end_time').val();
        hours = end.split(':')[0] - start.split(':')[0];
        minutes = end.split(':')[1] - start.split(':')[1];

        minutes = minutes.toString().length < 2 ? '0' + minutes : minutes;
        if (minutes < 0) {
            hours--;
            minutes = 60 + minutes;
        }
        hours = hours.toString().length < 2 ? '0' + hours : hours;

        if (hours < 0) {
            Swal.fire({
                position: 'center',
                icon: 'warning',
                title: 'Jam Selesai tidak boleh kurang dari Jam Mulai',
                showConfirmButton: false,
                timer: 2000
            });
            $('#end_time').val('');
        }
    }

    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        var filePath = $(this).val();
        var allowedExtensions =
            /(\.jpg|\.jpeg|\.png|\.pdf)$/i;
        const size =
            (this.files[0].size / 1024 / 1024).toFixed(2);

        if (!allowedExtensions.exec(filePath)) {
            Swal.fire({
                icon: 'error',
                title: 'Sorry',
                text: 'Invalid file type',
            })
            $(this).val("");
            $(this).siblings(".custom-file-label").addClass("selected").html("Choose file");
            return false;
        }

        if (size > 3) {
            Swal.fire({
                icon: 'error',
                title: 'Sorry',
                text: 'File size must be under 3 MB',
            });
            $(this).val("");
            $(this).siblings(".custom-file-label").addClass("selected").html("Choose file");
        } else {
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        }
    });
</script>