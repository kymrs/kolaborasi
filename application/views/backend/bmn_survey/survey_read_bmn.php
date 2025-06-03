<head>
    <?php $this->load->view('template/header'); ?>
</head>

<style>
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

    /* Thumb Rating Modern */
    .thumb-rating-modern {
        display: flex;
        gap: 22px;
        margin: 20px 0 10px 0;
        flex-wrap: wrap;
        justify-content: flex-start;
        text-align: left;
    }

    .thumb-rating-modern input[type="radio"] {
        display: none;
    }

    .thumb-rating-modern label {
        display: flex;
        flex-direction: column;
        align-items: center;
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
        min-width: 80px;
        padding: 8px 2px;
        border-radius: 12px;
        opacity: 0.7;
        border: 2px solid transparent;
        background: transparent;
    }

    .thumb-rating-modern input[type="radio"]:checked+label,
    .thumb-rating-modern label:hover,
    .thumb-rating-modern label:focus {
        opacity: 1;
        transform: scale(1.08);
        background: linear-gradient(135deg, #e0f7fa 60%, #ffe0e0 100%);
        border: 2px solid #00bcd4;
        box-shadow: 0 2px 12px #b2ebf2;
    }

    .thumb-rating-modern .thumb-label {
        font-size: 0.95rem;
        color: #444;
        margin-top: 6px;
        text-align: center;
        font-weight: 500;
        letter-spacing: 0.1px;
    }

    .thumb-rating-modern .thumb {
        font-size: 1.8rem;
        transition: font-size 0.2s;
    }

    @media (max-width: 768px) {
        .thumb-rating-modern {
            gap: 10px;
        }

        .thumb-rating-modern label {
            min-width: 60px;
            padding: 6px 1px;
        }

        .thumb-rating-modern .thumb {
            font-size: 2rem;
        }

        .thumb-rating-modern .thumb-label {
            font-size: 0.85rem;
        }
    }

    @media (max-width: 480px) {
        .thumb-rating-modern {
            gap: 6px;
        }

        .thumb-rating-modern label {
            min-width: 48px;
            padding: 4px 0;
        }

        .thumb-rating-modern .thumb {
            font-size: 1.4rem;
        }

        .thumb-rating-modern .thumb-label {
            font-size: 0.75rem;
        }
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

    @media (max-width: 576px) {
        .star-rating-custom label {
            font-size: 1.3rem;
        }

        .star-rating-modern .star {
            font-size: 1.3rem;
        }

        .star-rating-modern label {
            min-width: 50px;
        }

        .star-rating-modern .star-label {
            font-size: 0.75rem;
        }

        .face-rating-modern label {
            min-width: 50px;
        }

        .face-rating-modern .face-label {
            font-size: 0.75rem;
        }

        .thumb-rating-modern {
            gap: 2vw;
            margin: 10px 0 10px 0;
        }

        .thumb-rating-modern label {
            min-width: 38px;
            padding: 2px 0;
        }

        .thumb-rating-modern .thumb {
            font-size: 1.1rem;
        }

        .thumb-rating-modern .thumb-label {
            font-size: 0.65rem;
            max-width: 60px;
            white-space: normal;
            line-height: 1.1;
            text-align: center;
            word-break: break-word;
        }
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
                            <h2 class="title-header">Form Survey</h2>
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

                        <div class="row mb-3">
                            <div class="col-md-9 col-12">
                                <legend class="col-form-label pt-0 mb-2">1. Bagaimana kamu menilai keseluruhan layanan By Moment?</legend>
                                <div class="thumb-rating-modern thumb-rating-left">
                                    <input type="radio" name="q1" value="1" id="q1_1" <?= $survey->q1 == 1 ? 'checked' : '' ?> required>
                                    <label for="q1_1">
                                        <span class="thumb">👎</span>
                                        <span class="thumb-label">Tidak Memuaskan</span>
                                    </label>
                                    <input type="radio" name="q1" value="2" id="q1_2" <?= $survey->q1 == 2 ? 'checked' : '' ?>>
                                    <label for="q1_2">
                                        <span class="thumb">🙂</span>
                                        <span class="thumb-label">Kurang</span>
                                    </label>
                                    <input type="radio" name="q1" value="3" id="q1_3" <?= $survey->q1 == 3 ? 'checked' : '' ?>>
                                    <label for="q1_3">
                                        <span class="thumb">👌</span>
                                        <span class="thumb-label">Puas</span>
                                    </label>
                                    <input type="radio" name="q1" value="4" id="q1_4" <?= $survey->q1 == 4 ? 'checked' : '' ?>>
                                    <label for="q1_4">
                                        <span class="thumb">👍</span>
                                        <span class="thumb-label">Sangat Puas</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-9 col-12">
                                <legend class="col-form-label pt-0 mb-2">2. Seberapa baik komunikasi dan respons tim By Moment selama persiapan?</legend>
                                <div class="thumb-rating-modern thumb-rating-left">
                                    <input type="radio" name="q2" value="1" id="q2_1" required <?= $survey->q2 == 1 ? 'checked' : '' ?>>
                                    <label for="q2_1">
                                        <span class="thumb">👎</span>
                                        <span class="thumb-label">Tidak Memuaskan</span>
                                    </label>
                                    <input type="radio" name="q2" value="2" id="q2_2" <?= $survey->q2 == 2 ? 'checked' : '' ?>>
                                    <label for="q2_2">
                                        <span class="thumb">🙂</span>
                                        <span class="thumb-label">Kurang</span>
                                    </label>
                                    <input type="radio" name="q2" value="3" id="q2_3" <?= $survey->q2 == 3 ? 'checked' : '' ?>>
                                    <label for="q2_3">
                                        <span class="thumb">👌</span>
                                        <span class="thumb-label">Baik</span>
                                    </label>
                                    <input type="radio" name="q2" value="4" id="q2_4" <?= $survey->q2 == 4 ? 'checked' : '' ?>>
                                    <label for="q2_4">
                                        <span class="thumb">👍</span>
                                        <span class="thumb-label">Sangat Baik</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-9 col-12">
                                <legend class="col-form-label pt-0 mb-2">3. Bagaimana kualitas koordinasi dan eksekusi pada hari pernikahan?</legend>
                                <div class="thumb-rating-modern thumb-rating-left">
                                    <input type="radio" name="q3" value="1" id="q3_1" required <?= $survey->q3 == 1 ? 'checked' : '' ?>>
                                    <label for="q3_1">
                                        <span class="thumb">👎</span>
                                        <span class="thumb-label">Tidak Memuaskan</span>
                                    </label>
                                    <input type="radio" name="q3" value="2" id="q3_2" <?= $survey->q3 == 2 ? 'checked' : '' ?>>
                                    <label for="q3_2">
                                        <span class="thumb">🙂</span>
                                        <span class="thumb-label">Kurang</span>
                                    </label>
                                    <input type="radio" name="q3" value="3" id="q3_3" <?= $survey->q3 == 3 ? 'checked' : '' ?>>
                                    <label for="q3_3">
                                        <span class="thumb">👌</span>
                                        <span class="thumb-label">Baik</span>
                                    </label>
                                    <input type="radio" name="q3" value="4" id="q3_4" <?= $survey->q3 == 4 ? 'checked' : '' ?>>
                                    <label for="q3_4">
                                        <span class="thumb">👍</span>
                                        <span class="thumb-label">Sangat Baik</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-9 col-12">
                                <legend class="col-form-label pt-0 mb-2">4. Apakah hasil team dekorasi kami sesuai dengan ekspetasi Anda?</legend>
                                <div class="thumb-rating-modern thumb-rating-left">
                                    <input type="radio" name="q4" value="1" id="q4_1" required <?= $survey->q4 == 1 ? 'checked' : '' ?>>
                                    <label for="q4_1">
                                        <span class="thumb">👎</span>
                                        <span class="thumb-label">Tidak Sesuai</span>
                                    </label>
                                    <input type="radio" name="q4" value="2" id="q4_2" <?= $survey->q4 == 2 ? 'checked' : '' ?>>
                                    <label for="q4_2">
                                        <span class="thumb">🙂</span>
                                        <span class="thumb-label">Kurang Sesuai</span>
                                    </label>
                                    <input type="radio" name="q4" value="3" id="q4_3" <?= $survey->q4 == 3 ? 'checked' : '' ?>>
                                    <label for="q4_3">
                                        <span class="thumb">👌</span>
                                        <span class="thumb-label">Sesuai</span>
                                    </label>
                                    <input type="radio" name="q4" value="4" id="q4_4" <?= $survey->q4 == 4 ? 'checked' : '' ?>>
                                    <label for="q4_4">
                                        <span class="thumb">👍</span>
                                        <span class="thumb-label">Sangat Sesuai</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-9 col-12">
                                <legend class="col-form-label pt-0 mb-2">5. Apakah kamu puas dengan hasil team MUA Kami?</legend>
                                <div class="thumb-rating-modern thumb-rating-left">
                                    <input type="radio" name="q5" value="1" id="q5_1" required <?= $survey->q5 == 1 ? 'checked' : '' ?>>
                                    <label for="q5_1">
                                        <span class="thumb">👎</span>
                                        <span class="thumb-label">Tidak Puas</span>
                                    </label>
                                    <input type="radio" name="q5" value="2" id="q5_2" <?= $survey->q5 == 2 ? 'checked' : '' ?>>
                                    <label for="q5_2">
                                        <span class="thumb">🙂</span>
                                        <span class="thumb-label">Kurang</span>
                                    </label>
                                    <input type="radio" name="q5" value="3" id="q5_3" <?= $survey->q5 == 3 ? 'checked' : '' ?>>
                                    <label for="q5_3">
                                        <span class="thumb">👌</span>
                                        <span class="thumb-label">Puas</span>
                                    </label>
                                    <input type="radio" name="q5" value="4" id="q5_4" <?= $survey->q5 == 4 ? 'checked' : '' ?>>
                                    <label for="q5_4">
                                        <span class="thumb">👍</span>
                                        <span class="thumb-label">Sangat Puas</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-9 col-12">
                                <legend class="col-form-label pt-0 mb-2">6. Bagaimana pendapat kamu tentang proses dan hasil Foto Video team Dokumentasi Kami?</legend>
                                <div class="thumb-rating-modern thumb-rating-left">
                                    <input type="radio" name="q6" value="1" id="q6_1" required <?= $survey->q6 == 1 ? 'checked' : '' ?>>
                                    <label for="q6_1">
                                        <span class="thumb">👎</span>
                                        <span class="thumb-label">Tidak Worth It</span>
                                    </label>
                                    <input type="radio" name="q6" value="2" id="q6_2" <?= $survey->q6 == 2 ? 'checked' : '' ?>>
                                    <label for="q6_2">
                                        <span class="thumb">🙂</span>
                                        <span class="thumb-label">Kurang Worth It</span>
                                    </label>
                                    <input type="radio" name="q6" value="3" id="q6_3" <?= $survey->q6 == 3 ? 'checked' : '' ?>>
                                    <label for="q6_3">
                                        <span class="thumb">👌</span>
                                        <span class="thumb-label">Worth It</span>
                                    </label>
                                    <input type="radio" name="q6" value="4" id="q6_4" <?= $survey->q6 == 4 ? 'checked' : '' ?>>
                                    <label for="q6_4">
                                        <span class="thumb">👍</span>
                                        <span class="thumb-label">Sangat Worth it</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-9 col-12">
                                <legend class="col-form-label pt-0 mb-2">7. Apakah team Entertainment Kami berhasil menghibur acara pernikahan Anda?</legend>
                                <div class="thumb-rating-modern thumb-rating-left">
                                    <input type="radio" name="q7" value="1" id="q7_1" required <?= $survey->q7 == 1 ? 'checked' : '' ?>>
                                    <label for="q7_1">
                                        <span class="thumb">👎</span>
                                        <span class="thumb-label">Tidak Memuaskan</span>
                                    </label>
                                    <input type="radio" name="q7" value="2" id="q7_2" <?= $survey->q7 == 2 ? 'checked' : '' ?>>
                                    <label for="q7_2">
                                        <span class="thumb">🙂</span>
                                        <span class="thumb-label">Kurang Menghibur</span>
                                    </label>
                                    <input type="radio" name="q7" value="3" id="q7_3" <?= $survey->q7 == 3 ? 'checked' : '' ?>>
                                    <label for="q7_3">
                                        <span class="thumb">👌</span>
                                        <span class="thumb-label">Menghibur</span>
                                    </label>
                                    <input type="radio" name="q7" value="4" id="q7_4" <?= $survey->q7 == 4 ? 'checked' : '' ?>>
                                    <label for="q7_4">
                                        <span class="thumb">👍</span>
                                        <span class="thumb-label">Sangat Menghibur</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-9 col-12">
                                <legend class="col-form-label pt-0 mb-2">8. Apakah MC Kami berhasil membawakan acara pernikahan Anda dengan baik?</legend>
                                <div class="thumb-rating-modern thumb-rating-left">
                                    <input type="radio" name="q8" value="1" id="q8_1" required <?= $survey->q8 == 1 ? 'checked' : '' ?>>
                                    <label for="q8_1">
                                        <span class="thumb">👎</span>
                                        <span class="thumb-label">Tidak Memuaskan</span>
                                    </label>
                                    <input type="radio" name="q8" value="2" id="q8_2" <?= $survey->q8 == 2 ? 'checked' : '' ?>>
                                    <label for="q8_2">
                                        <span class="thumb">🙂</span>
                                        <span class="thumb-label">Kurang</span>
                                    </label>
                                    <input type="radio" name="q8" value="3" id="q8_3" <?= $survey->q8 == 3 ? 'checked' : '' ?>>
                                    <label for="q8_3">
                                        <span class="thumb">👌</span>
                                        <span class="thumb-label">Baik</span>
                                    </label>
                                    <input type="radio" name="q8" value="4" id="q8_4" <?= $survey->q8 == 4 ? 'checked' : '' ?>>
                                    <label for="q8_4">
                                        <span class="thumb">👍</span>
                                        <span class="thumb-label">Sangat Baik</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-9 col-12">
                                <legend class="col-form-label pt-0 mb-2">9. Bagaimana pendapat kamu mengenai cita rasa dari Catering Kami?</legend>
                                <div class="thumb-rating-modern thumb-rating-left">
                                    <input type="radio" name="q9" value="1" id="q9_1" required <?= $survey->q9 == 1 ? 'checked' : '' ?>>
                                    <label for="q9_1">
                                        <span class="thumb">👎</span>
                                        <span class="thumb-label">Tidak Puas</span>
                                    </label>
                                    <input type="radio" name="q9" value="2" id="q9_2" <?= $survey->q9 == 2 ? 'checked' : '' ?>>
                                    <label for="q9_2">
                                        <span class="thumb">🙂</span>
                                        <span class="thumb-label">Kurang</span>
                                    </label>
                                    <input type="radio" name="q9" value="3" id="q9_3" <?= $survey->q9 == 3 ? 'checked' : '' ?>>
                                    <label for="q9_3">
                                        <span class="thumb">👌</span>
                                        <span class="thumb-label">Puas</span>
                                    </label>
                                    <input type="radio" name="q9" value="4" id="q9_4" <?= $survey->q9 == 4 ? 'checked' : '' ?>>
                                    <label for="q9_4">
                                        <span class="thumb">👍</span>
                                        <span class="thumb-label">Sangat Puas</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-9 col-12">
                                <legend class="col-form-label pt-0 mb-2">10. Apakah kamu akan merekomendasikan By Moment ke teman atau keluarga?</legend>
                                <div class="thumb-rating-modern thumb-rating-left">
                                    <input type="radio" name="q10" value="1" id="q10_1" required <?= $survey->q10 == 1 ? 'checked' : '' ?>>
                                    <label for="q10_1">
                                        <span class="thumb">👎</span>
                                        <span class="thumb-label">Tidak</span>
                                    </label>
                                    <input type="radio" name="q10" value="2" id="q10_2" <?= $survey->q10 == 2 ? 'checked' : '' ?>>
                                    <label for="q10_2">
                                        <span class="thumb">🙂</span>
                                        <span class="thumb-label">Mungkin Tidak</span>
                                    </label>
                                    <input type="radio" name="q10" value="3" id="q10_3" <?= $survey->q10 == 3 ? 'checked' : '' ?>>
                                    <label for="q10_3">
                                        <span class="thumb">👌</span>
                                        <span class="thumb-label">Mungkin</span>
                                    </label>
                                    <input type="radio" name="q10" value="4" id="q10_4" <?= $survey->q10 == 4 ? 'checked' : '' ?>>
                                    <label for="q10_4">
                                        <span class="thumb">👍</span>
                                        <span class="thumb-label">Pasti</span>
                                    </label>
                                </div>
                            </div>
                        </div>
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