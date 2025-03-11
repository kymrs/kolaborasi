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
                    <a class="btn btn-secondary btn-sm" href="<?= base_url('pu_mom_admin') ?>"><i class="fas fa-chevron-left"></i>&nbsp;Back</a>
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
                                <div class="form-group row">
                                    <label class="col-sm-3">Agenda</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="agenda" name="agenda" placeholder="Agenda" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3">Date</label>
                                    <div class="col-sm-9">
                                        <div class="input-group date">
                                            <input type="text" class="form-control" name="date" id="date" placeholder="DD-MM-YYYY" autocomplete="off" style="cursor:pointer;" required />
                                            <div class="input-group-append">
                                                <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3">Time</label>
                                    <div class="col-sm-4 input-group date" id="st" data-target-input="nearest" onchange="cek1()">
                                        <input type="text" class="form-control datetimepicker-input" data-toggle="datetimepicker" data-target="#st" name="start_time" id="start_time" placeholder="HH:MM" autocomplete="off" required />
                                        <div class="input-group-append" data-target="#st" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="far fa-clock"></i></div>
                                        </div>
                                    </div>
                                    <label for="" class="col-form-label col-sm-1">sd</label>
                                    <div class="col-sm-4 input-group date" id="et" data-target-input="nearest" onchange="cek2()">
                                        <input type="text" class="form-control datetimepicker-input" data-toggle="datetimepicker" data-target="#et" name="end_time" id="end_time" placeholder="HH:MM" autocomplete="off" required />
                                        <div class="input-group-append" data-target="#et" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="far fa-clock"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3">Lokasi</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" name="lokasi" id="lokasi" placeholder="Lokasi" required></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Foto</label>
                                    <div class="col-sm-9">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="foto" id="foto" required>
                                            <label class="custom-file-label" id="foto_label" for="customFile">Choose file</label>
                                            <p class="small text-danger">*jpg, jpeg, png</p>
                                            <a id="lihat_foto" class="btn btn-sm btn-info" style="margin-top:-20px;">Lihat</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-4">
                                <div class="form-group row">
                                    <label class="col-sm-2">Peserta</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" name="peserta" id="peserta"><?= (!empty($data) ? $data->peserta : '') ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2">Konten</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" name="konten" id="konten"><?= (!empty($data) ? $data->konten : '') ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="id" id="id" value="<?= $id ?>">
                        <?php if (!empty($aksi)) { ?>
                            <input type="hidden" name="aksi" id="aksi" value="<?= $aksi ?>">
                        <?php } ?>
                        <?php if ($id == 0) { ?>
                            <input type="hidden" name="kode" id="kode" value="<?= $kode ?>">
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
                url: "<?php echo site_url('pu_mom_admin/edit_data') ?>/" + id,
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
                    $('#lihat_foto').attr({
                        "href": "<?= site_url('assets/backend/document/mom/foto_mom_pu/') ?>" + data.foto,
                        "target": "_blank"
                    });
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
    });
</script>