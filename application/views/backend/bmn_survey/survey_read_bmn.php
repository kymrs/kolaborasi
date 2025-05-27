<head>
    <?php $this->load->view('template/header'); ?>
</head>

<style>
    .star-rating {
        font-size: 0;
        white-space: nowrap;
        display: inline-block;
        width: 200px;
        height: 40px;
        overflow: hidden;
        position: relative;
        background: url('data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IiB3aWR0aD0iMjBweCIgaGVpZ2h0PSIyMHB4IiB2aWV3Qm94PSIwIDAgMjAgMjAiIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDIwIDIwIiB4bWw6c3BhY2U9InByZXNlcnZlIj48cG9seWdvbiBmaWxsPSIjREREREREIiBwb2ludHM9IjEwLDAgMTMuMDksNi41ODMgMjAsNy42MzkgMTUsMTIuNzY0IDE2LjE4LDIwIDEwLDE2LjU4MyAzLjgyLDIwIDUsMTIuNzY0IDAsNy42MzkgNi45MSw2LjU4MyAiLz48L3N2Zz4=');
        background-size: contain;
    }

    .star-rating i {
        opacity: 0;
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 20%;
        z-index: 1;
        background: url('data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IiB3aWR0aD0iMjBweCIgaGVpZ2h0PSIyMHB4IiB2aWV3Qm94PSIwIDAgMjAgMjAiIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDIwIDIwIiB4bWw6c3BhY2U9InByZXNlcnZlIj48cG9seWdvbiBmaWxsPSIjRkZERjg4IiBwb2ludHM9IjEwLDAgMTMuMDksNi41ODMgMjAsNy42MzkgMTUsMTIuNzY0IDE2LjE4LDIwIDEwLDE2LjU4MyAzLjgyLDIwIDUsMTIuNzY0IDAsNy42MzkgNi45MSw2LjU4MyAiLz48L3N2Zz4=');
        background-size: contain;
    }

    .star-rating input {
        -moz-appearance: none;
        -webkit-appearance: none;
        opacity: 0;
        display: inline-block;
        width: 20%;
        height: 100%;
        margin: 0;
        padding: 0;
        z-index: 2;
        position: relative;
    }

    .star-rating input:hover+i,
    .star-rating input:checked+i {
        opacity: 1;
    }

    .star-rating i~i {
        width: 40%;
    }

    .star-rating i~i~i {
        width: 60%;
    }

    .star-rating i~i~i~i {
        width: 80%;
    }

    .star-rating i~i~i~i~i {
        width: 100%;
    }

    ::after,
    ::before {
        height: 100%;
        padding: 0;
        margin: 0;
        box-sizing: border-box;
        text-align: center;
        vertical-align: middle;
    }

    .title-header {
        margin: 30px 0;
        text-align: center
    }

    .h-pu,
    .h-travel {
        margin-top: 40px;
        margin-bottom: 20px;
    }

    .sd {
        margin-top: 6px;
    }

    input,
    textarea,
    label {
        pointer-events: none;
    }

    @media (max-width: 546px) {

        .title-header {
            font-size: 1.4rem;
        }

        .sd {
            margin-top: 10px;
            margin-left: 15px;
        }

        .h-pu,
        .h-travel {
            font-size: 1.2rem;
            margin-top: 40px;
            margin-bottom: 20px;
        }

        .header-content p {
            text-align: center;
        }
    }
</style>

<div class="content">
    <div class="container">
        <a class="btn btn-secondary btn-sm" onclick="history.back()"><i class="fas fa-chevron-left"></i>&nbsp;Back</a><br><br>
        <div class="card" style="margin-bottom: 50px">
            <div class="flash-data" data-flashdata=""></div>
            <div class="card-body">
                <form id="surveyform" class="form-horizontal">
                    <div class="container my-3">
                        <div class="header-content">
                            <p><img src="<?= base_url('assets/backend/img/bymoment.png') ?>" style="width: 200px;">
                            <h2 class="title-header">Form Survey Kepuasan Customer</h2>
                            </p>
                        </div>
                        <p style="font-weight:bold;">Identitas Responden</p>
                        <div class="form-group row mt-3">
                            <label class="col-sm-3 col-form-label">Nama Lengkap</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="nama" id="nama" placeholder="Nama" value="<?= $survey->nama ?>" disabled>
                            </div>
                        </div>
                        <div class="form-group row mt-3">
                            <label class="col-sm-3 col-form-label">Tanggal Pernikahan</label>
                            <?php
                            function bulanIndonesia($tanggal)
                            {
                                $bulan = array(
                                    '01' => 'Januari',
                                    '02' => 'Februari',
                                    '03' => 'Maret',
                                    '04' => 'April',
                                    '05' => 'Mei',
                                    '06' => 'Juni',
                                    '07' => 'Juli',
                                    '08' => 'Agustus',
                                    '09' => 'September',
                                    '10' => 'Oktober',
                                    '11' => 'November',
                                    '12' => 'Desember'
                                );

                                return $bulan[date('m', strtotime($tanggal))];
                            }
                            ?>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="tgl_pernikahan" id="tgl_pernikahan" placeholder="Email" value="<?= date('d ', strtotime($survey->tgl_pernikahan)) . bulanIndonesia($survey->tgl_pernikahan) . date(' Y', strtotime($survey->tgl_pernikahan)) ?>" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">No. Handphone</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="no_hp" id="no_hp" placeholder="No. Handphone" value="<?= $survey->no_hp ?>" disabled>
                            </div>
                        </div>

                        <h4 style="font-weight: bold;" class="text-center h-travel">Kepuasan Pelanggan Terhadap By.Moment</h4>

                        <fieldset class="form-group row">
                            <legend class="col-form-label col-sm-9 float-sm-left pt-0">A. Bagaimana kamu menilai keseluruhan layanan By Moment?</legend>
                            <div class="col-sm-3">
                                <span class="star-rating">
                                    <input type="radio" name="q1" value="1" <?= $survey->q1 == 1 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q1" value="2" <?= $survey->q1 == 2 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q1" value="3" <?= $survey->q1 == 3 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q1" value="4" <?= $survey->q1 == 4 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q1" value="5" <?= $survey->q1 == 5 ? 'checked' : '' ?>><i></i>
                                </span>
                            </div>
                        </fieldset>
                        <fieldset class="form-group row">
                            <legend class="col-form-label col-sm-9 float-sm-left pt-0">B. Seberapa baik komunikasi dan respons tim By Moment selama persiapan?</legend>
                            <div class="col-sm-3">
                                <span class="star-rating">
                                    <input type="radio" name="q2" value="1" <?= $survey->q2 == 1 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q2" value="2" <?= $survey->q2 == 2 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q2" value="3" <?= $survey->q2 == 3 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q2" value="4" <?= $survey->q2 == 4 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q2" value="5" <?= $survey->q2 == 5 ? 'checked' : '' ?>><i></i>
                                </span>
                            </div>
                        </fieldset>
                        <fieldset class="form-group row">
                            <legend class="col-form-label col-sm-9 float-sm-left pt-0">C. Bagaimana kualitas koordinasi dan eksekusi pada hari pernikahan?</legend>
                            <div class="col-sm-3">
                                <span class="star-rating">
                                    <input type="radio" name="q3" value="1" <?= $survey->q3 == 1 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q3" value="2" <?= $survey->q3 == 2 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q3" value="3" <?= $survey->q3 == 3 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q3" value="4" <?= $survey->q3 == 4 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q3" value="5" <?= $survey->q3 == 5 ? 'checked' : '' ?>><i></i>
                                </span>
                            </div>
                        </fieldset>
                        <fieldset class="form-group row">
                            <legend class="col-form-label col-sm-9 float-sm-left pt-0">D. Apakah hasil team dekorasi kami sesuai dengan ekspetasi Anda?</legend>
                            <div class="col-sm-3">
                                <span class="star-rating">
                                    <input type="radio" name="q4" value="1" <?= $survey->q4 == 1 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q4" value="2" <?= $survey->q4 == 2 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q4" value="3" <?= $survey->q4 == 3 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q4" value="4" <?= $survey->q4 == 4 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q4" value="5" <?= $survey->q4 == 5 ? 'checked' : '' ?>><i></i>
                                </span>
                            </div>
                        </fieldset>
                        <fieldset class="form-group row">
                            <legend class="col-form-label col-sm-9 float-sm-left pt-0">E. Apakah kamu puas dengan hasil team MUA Kami?</legend>
                            <div class="col-sm-3">
                                <span class="star-rating">
                                    <input type="radio" name="q5" value="1" <?= $survey->q5 == 1 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q5" value="2" <?= $survey->q5 == 2 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q5" value="3" <?= $survey->q5 == 3 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q5" value="4" <?= $survey->q5 == 4 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q5" value="5" <?= $survey->q5 == 5 ? 'checked' : '' ?>><i></i>
                                </span>
                            </div>
                        </fieldset>
                        <fieldset class="form-group row">
                            <legend class="col-form-label col-sm-9 float-sm-left pt-0">F. Bagaimana pendapat kamu tentang proses dan hasil Foto Video team Dokumentasi Kami?</legend>
                            <div class="col-sm-3">
                                <span class="star-rating">
                                    <input type="radio" name="q6" value="1" <?= $survey->q6 == 1 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q6" value="2" <?= $survey->q6 == 2 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q6" value="3" <?= $survey->q6 == 3 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q6" value="4" <?= $survey->q6 == 4 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q6" value="5" <?= $survey->q6 == 5 ? 'checked' : '' ?>><i></i>
                                </span>
                            </div>
                        </fieldset>
                        <fieldset class="form-group row">
                            <legend class="col-form-label col-sm-9 float-sm-left pt-0">G. Apakah team Entertainment Kami berhasil menghibur acara pernikahan Anda?</legend>
                            <div class="col-sm-3">
                                <span class="star-rating">
                                    <input type="radio" name="q7" value="1" <?= $survey->q7 == 1 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q7" value="2" <?= $survey->q7 == 2 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q7" value="3" <?= $survey->q7 == 3 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q7" value="4" <?= $survey->q7 == 4 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q7" value="5" <?= $survey->q7 == 5 ? 'checked' : '' ?>><i></i>
                                </span>
                            </div>
                        </fieldset>
                        <fieldset class="form-group row">
                            <legend class="col-form-label col-sm-9 float-sm-left pt-0">H. Apakah MC Kami berhasil membawakan acara pernikahan Anda dengan baik?</legend>
                            <div class="col-sm-3">
                                <span class="star-rating">
                                    <input type="radio" name="q8" value="1" <?= $survey->q8 == 1 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q8" value="2" <?= $survey->q8 == 2 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q8" value="3" <?= $survey->q8 == 3 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q8" value="4" <?= $survey->q8 == 4 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q8" value="5" <?= $survey->q8 == 5 ? 'checked' : '' ?>><i></i>
                                </span>
                            </div>
                        </fieldset>
                        <fieldset class="form-group row">
                            <legend class="col-form-label col-sm-9 float-sm-left pt-0">I. Bagaimana pendapat kamu mengenai cita rasa dari Catering Kami?</legend>
                            <div class="col-sm-3">
                                <span class="star-rating">
                                    <input type="radio" name="q9" value="1" <?= $survey->q9 == 1 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q9" value="2" <?= $survey->q9 == 2 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q9" value="3" <?= $survey->q9 == 3 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q9" value="4" <?= $survey->q9 == 4 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q9" value="5" <?= $survey->q9 == 5 ? 'checked' : '' ?>><i></i>
                                </span>
                            </div>
                        </fieldset>
                        <fieldset class="form-group row">
                            <legend class="col-form-label col-sm-9 float-sm-left pt-0">J. Apakah kamu akan merekomendasikan By Moment ke teman atau keluarga</legend>
                            <div class="col-sm-3">
                                <span class="star-rating">
                                    <input type="radio" name="q10" value="1" <?= $survey->q10 == 1 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q10" value="2" <?= $survey->q10 == 2 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q10" value="3" <?= $survey->q10 == 3 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q10" value="4" <?= $survey->q10 == 4 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q10" value="5" <?= $survey->q10 == 5 ? 'checked' : '' ?>><i></i>
                                </span>
                            </div>
                        </fieldset>
                        <fieldset class="form-group row">
                            <legend class="col-form-label col-sm-9 float-sm-left pt-0">K. Kritik dan Saran</legend>
                            <div class="col-sm-12">
                                <textarea class="form-control" name="kritik_saran" id="kritik_saran" placeholder="Type in here..." style="resize:none; height: 130px;" required><?= $survey->kritik_saran ?></textarea>
                            </div>
                        </fieldset>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Include jQuery and Bootstrap JS -->
<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>