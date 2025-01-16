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
                            <p><img src="<?= base_url('assets/backend/img/pengenumroh.png') ?>" style="width: 200px;">
                            <h2 class="title-header">Read Form Survey Kepuasan Jamaah</h2>
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
                            <label class="col-sm-3 col-form-label">Tanggal Keberangkatan</label>
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
                                <input type="text" class="form-control" name="tgl_keberangkatan" id="tgl_keberangkatan" placeholder="Email" value="<?= date('d ', strtotime($survey->tgl_keberangkatan)) . bulanIndonesia($survey->tgl_keberangkatan) . date(' Y', strtotime($survey->tgl_keberangkatan)) ?>" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">No. Handphone</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="no_hp" id="no_hp" placeholder="No. Handphone" value="<?= $survey->no_hp ?>" disabled>
                            </div>
                        </div>

                        <h4 style="font-weight: bold;" class="text-center h-travel">Kepuasan Pelanggan Terhadap Travel</h4>


                        <h5>1. Kelengkapan Fasilitas</h5>
                        <fieldset class="form-group row">
                            <legend class="col-form-label col-sm-9 float-sm-left pt-0">A. Apakah hotel yang disediakan sudah nyaman dan memenuhi kebutuhan Bapak/Ibu selama Umroh (check-in, kebersihan, makanan dan fasilitas)?</legend>
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
                            <legend class="col-form-label col-sm-9 float-sm-left pt-0">B. Apakah perlengkapan dari travel (koper, kain ihram, dll.) sudah sesuai dan nyaman?</legend>
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
                            <legend class="col-form-label col-sm-9 float-sm-left pt-0">C. Apakah fasilitas transportasi (bus) memberikan pelayanan terbaik dengan ketepatan waktu penjemputan dan keberangkatan?</legend>
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

                        <h5>2. Pembimbing Umroh</h5>
                        <fieldset class="form-group row">
                            <legend class="col-form-label col-sm-9 float-sm-left pt-0">A. Apakah bimbingan ibadah Umroh sudah cukup, dan mudah dipahami melalui pemaparan dan tanya jawab?</legend>
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
                            <legend class="col-form-label col-sm-9 float-sm-left pt-0">b. Apakah pembimbing atau muthowwif sangat membantu jamaah?</legend>
                            <div class="col-sm-3">
                                <span class="star-rating">
                                    <input type="radio" name="q5" value="1" <?= $survey->q5 == 1 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q5" value="2" <?= $survey->q5 == 2 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q5" value="3" <?= $survey->q5 == 3 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q5" value="5" <?= $survey->q5 == 4 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q5" value="5" <?= $survey->q5 == 5 ? 'checked' : '' ?>><i></i>
                                </span>
                            </div>
                        </fieldset>

                        <h5>3. Program Umroh</h5>
                        <fieldset class="form-group row">
                            <legend class="col-form-label col-sm-9 float-sm-left pt-0">A. Apakah program Umroh sesuai dengan harapan, termasuk itinerary yang sesuai dengan informasi waktu dan tempat?</legend>
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

                        <h5>4. Harga vs Kualitas</h5>
                        <fieldset class="form-group row">
                            <legend class="col-form-label col-sm-9 float-sm-left pt-0">A. Apakah harga yang Bapak/Ibu bayar sesuai dengan kualitas pelayanan dan fasilitas yang didapatkan?</legend>
                            <div class="col-sm-12">
                                <textarea class="form-control" name="q7" id="q7" placeholder="Type in here..." style="resize:none; height: 90px;"><?= $survey->q7 ?></textarea>
                            </div>
                        </fieldset>

                        <h5>5. Pelayanan di Makkah dan Madinah</h5>
                        <fieldset class="form-group row">
                            <legend class="col-form-label col-sm-9 float-sm-left pt-0">A. Apakah Bapak/Ibu mendapatkan pelayanan terbaik selama di Makkah dan Madinah, termasuk hotel, transportasi, tour leader, muthowwif, dan layanan lainnya?</legend>
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

                        <h5>5. Saran dan Masukan</h5>
                        <fieldset class="form-group row">
                            <legend class="col-form-label col-sm-9 float-sm-left pt-0">A. Menurut Bapak / Ibu apa yang perlu di perbaiki dari layanan travel untuk meningkatkan kualitas pelayanan di masa depan?</legend>
                            <div class="col-sm-12">
                                <textarea class="form-control" name="q9" id="q9" placeholder="Type in here..." style="resize:none; height: 90px;"><?= $survey->q9 ?></textarea>
                            </div>
                        </fieldset>

                        <h5>6. Penilaian Travel</h5>
                        <fieldset class="form-group row">
                            <legend class="col-form-label col-sm-9 float-sm-left pt-0">A. Penilaian secara menyeluruh terhadap layanan travel?</legend>
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

                        <h4 style="font-weight: bold;" class="text-center h-pu">Kepuasan Pelanggan Terhadap pengenumroh.com</h4>

                        <div class="form-group row">
                            <h5 style="margin-left: 15px;">1. Pelayanan Administrasi</h5>

                            <label class="col-sm-12">A. Sejauh mana Bapak/Ibu puas dengan proses pendaftaran, pendataan, dan pembayaran di pengenumroh? Apakah Bapak/Ibu mendapatkan kemudahan dan informasi yang jelas?</label>
                            <div class="col-sm-3">
                                <span class="star-rating">
                                    <input type="radio" name="q11" value="1" <?= $survey->q11 == 1 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q11" value="2" <?= $survey->q11 == 2 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q11" value="3" <?= $survey->q11 == 3 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q11" value="4" <?= $survey->q11 == 4 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q11" value="5" <?= $survey->q11 == 5 ? 'checked' : '' ?>><i></i>
                                </span>
                            </div>
                        </div>
                        <br />
                        <div class="form-group row">
                            <label class="col-sm-12">B. Apakah informasi yang diberikan oleh staff pengenumroh sebelum keberangkatan sudah cukup jelas, termasuk cara pendaftaran, pemilihan produk, pembayaran, dan penyaluran perlengkapan?</label>
                            <div class="col-sm-3">
                                <span class="star-rating">
                                    <input type="radio" name="q12" value="1" <?= $survey->q12 == 1 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q12" value="2" <?= $survey->q12 == 2 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q12" value="3" <?= $survey->q12 == 3 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q12" value="4" <?= $survey->q12 == 4 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q12" value="5" <?= $survey->q12 == 5 ? 'checked' : '' ?>><i></i>
                                </span>
                            </div>
                        </div>
                        <br />
                        <div class="form-group row">
                            <h5 style="margin-left: 15px;">2. Pelayanan Penanganan Masalah Jamaah</h5>
                            <label class="col-sm-12">A. Bagaimana penilaian Bapak/Ibu terhadap pelayanan pengenumroh saat menghadapi kendala sebelum keberangkatan, seperti Paspor, vaksin, seragam, atau perlengkapan lainnya?</label>
                            <div class="col-sm-3">
                                <span class="star-rating">
                                    <input type="radio" name="q13" value="1" <?= $survey->q13 == 1 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q13" value="2" <?= $survey->q13 == 2 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q13" value="3" <?= $survey->q13 == 3 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q13" value="4" <?= $survey->q13 == 4 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q13" value="5" <?= $survey->q13 == 5 ? 'checked' : '' ?>><i></i>
                                </span>
                            </div>
                        </div>
                        <br />
                        <div class="form-group row">
                            <h5 style="margin-left: 15px;">3. Pelayanan Selama di Indonesia</h5>
                            <label class="col-sm-12">A. Seberapa puas Bapak/Ibu dengan kualitas pelayanan kami, seperti pembuatan paspor, penyediaan vaksin dan buku kuning, antar jemput berkas, serta handling Manasik, keberangkatan, dan kepulangan?</label>
                            <div class="col-sm-3">
                                <span class="star-rating">
                                    <input type="radio" name="q14" value="1" <?= $survey->q14 == 1 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q14" value="2" <?= $survey->q14 == 2 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q14" value="3" <?= $survey->q14 == 3 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q14" value="4" <?= $survey->q14 == 4 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q14" value="5" <?= $survey->q14 == 5 ? 'checked' : '' ?>><i></i>
                                </span>
                            </div>
                        </div>
                        <br />
                        <div class="form-group row">
                            <h5 style="margin-left: 15px;">4. Kepuasan Pelanggan</h5>
                            <label class="col-sm-12">A. Apakah Bapak / Ibu akan mempercayakan untuk menggunakan layanan pengenumroh di kemudian hari? (repeat order)</label>
                            <div class="col-sm-12">
                                <input type="radio" id="q15y" name="q15" value="Ya" <?= $survey->q15 == 'Ya' ? 'checked' : '' ?>>
                                <label for="q15y" style="position: relative; bottom: 2px; left: 4px; margin-right: 10px">Ya</label>

                                <input type="radio" id="q15n" name="q15" value="Tidak" <?= $survey->q15 == 'Tidak' ? 'checked' : '' ?>>
                                <label for="q15n" style="position: relative; bottom: 2px; left: 4px">Tidak</label>
                            </div>
                        </div>
                        <br />
                        <div class="form-group row">
                            <label class="col-sm-12">B. Apakah Bapak / Ibu akan merekomendasikan pengenumroh kepada khalayak ramai?
                            </label>
                            <div class="col-sm-12">
                                <input type="radio" id="q16y" name="q16" value="Ya" <?= $survey->q16 == 'Ya' ? 'checked' : '' ?>>
                                <label for="q16y" style="position: relative; bottom: 2px; left: 4px; margin-right: 10px">Ya</label>

                                <input type="radio" id="q16n" name="q16" value="Tidak" <?= $survey->q16 == 'Tidak' ? 'checked' : '' ?>>
                                <label for="q16n" style="position: relative; bottom: 2px; left: 4px">Tidak</label>
                            </div>
                        </div>
                        <br>
                        <div class="form-group row">
                            <h5 style="margin-left: 15px;">5. Saran dan Masukan</h5>
                            <label class="col-sm-12">A. Menurut Bapak / Ibu apa yang perlu di perbaiki dari layanan pengenumroh untuk meningkatkan kualitas pelayanan di masa depan?</label>
                            <div class="col-sm-12">
                                <textarea class="form-control" name="q17" id="q17" placeholder="Type in here..." style="resize:none; height: 90px;"><?= $survey->q17 ?></textarea>
                            </div>
                        </div>
                        <h5>6. Penilaian pengenumroh</h5>
                        <fieldset class="form-group row">
                            <legend class="col-form-label col-sm-9 float-sm-left pt-0">A. Penilaian secara menyeluruh terhadap pengenumroh?</legend>
                            <div class="col-sm-3">
                                <span class="star-rating">
                                    <input type="radio" name="q18" value="1" <?= $survey->q18 == 1 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q18" value="2" <?= $survey->q18 == 2 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q18" value="3" <?= $survey->q18 == 3 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q18" value="4" <?= $survey->q18 == 4 ? 'checked' : '' ?>><i></i>
                                    <input type="radio" name="q18" value="5" <?= $survey->q18 == 5 ? 'checked' : '' ?>><i></i>
                                </span>
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