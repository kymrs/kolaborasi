<head>
    <?php $this->load->view('template/header'); ?>
</head>

    <style>
        :root {
            --primary-color: #EB2427;
            /* Warna Merah Utama */
            --primary-dark: #c21a1d;
            /* Merah lebih gelap untuk hover */
            --secondary-color: #f8f9fa;
            --text-dark: #333;
            --text-muted: #6c757d;
            --star-color: #ffc107;
            --star-inactive: #e4e5e9;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #eef2f5;
            color: var(--text-dark);
            padding-bottom: 50px;
        }

        .main-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            background: #fff;
            margin-top: 30px;
            margin-bottom: 30px;
        }

        .header-banner {
            /* Gradient Merah */
            background: linear-gradient(135deg, #EB2427 0%, #b01518 100%);
            padding: 40px 20px;
            text-align: center;
            color: white;
            border-bottom-left-radius: 30px;
            border-bottom-right-radius: 30px;
            margin-bottom: 20px;
        }

        .logo-img {
            max-width: 300px;
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1));
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 10px;
        }

        h2.title-header {
            font-weight: 700;
            font-size: 1.8rem;
            margin-bottom: 0;
        }

        .section-title {
            color: var(--primary-color);
            font-weight: 700;
            border-left: 5px solid var(--primary-color);
            padding-left: 15px;
            margin-top: 30px;
            margin-bottom: 20px;
            font-size: 1.25rem;
            /* Latar belakang merah sangat muda */
            background: #fff5f5;
            padding-top: 10px;
            padding-bottom: 10px;
            border-radius: 0 10px 10px 0;
        }

        .question-box {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 15px;
            border: 1px solid #f0f0f0;
            transition: all 0.3s ease;
        }

        .question-box:hover {
            border-color: var(--primary-color);
            /* Shadow Merah */
            box-shadow: 0 4px 15px rgba(235, 36, 39, 0.1);
        }

        .question-label {
            font-weight: 500;
            margin-bottom: 10px;
            display: block;
            font-size: 0.95rem;
        }

        /* Modern Form Inputs */
        .form-control {
            border-radius: 10px;
            border: 1px solid #ddd;
            padding: 12px 15px;
            background-color: #fcfcfc;
        }

        .form-control:focus {
            /* Shadow Merah saat fokus */
            box-shadow: 0 0 0 3px rgba(235, 36, 39, 0.2);
            border-color: var(--primary-color);
        }

        /* --- New Star Rating System (Pure CSS + FontAwesome) --- */
        .star-rating-group {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
            gap: 5px;
        }

        .star-rating-group input {
            display: none;
        }

        .star-rating-group label {
            font-size: 24px;
            color: var(--star-inactive);
            cursor: pointer;
            transition: color 0.2s;
        }

        .star-rating-group label:hover,
        .star-rating-group label:hover~label,
        .star-rating-group input:checked~label {
            color: var(--star-color);
        }

        .star-rating-group label:hover,
        .star-rating-group label:hover~label {
            transform: scale(1.1);
        }

        /* --- Radio Button Groups (Yes/No) --- */
        .radio-group {
            display: flex;
            gap: 20px;
        }

        .custom-radio {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .custom-radio input {
            margin-right: 8px;
            width: 18px;
            height: 18px;
            accent-color: var(--primary-color);
        }

        /* Button Styling */
        .btn-submit {
            background: var(--primary-color);
            color: white;
            padding: 15px 40px;
            border-radius: 50px;
            font-weight: 600;
            letter-spacing: 1px;
            border: none;
            /* Shadow tombol merah */
            box-shadow: 0 4px 15px rgba(235, 36, 39, 0.4);
            transition: all 0.3s;
            width: 100%;
            margin-top: 20px;
        }

        .btn-submit:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(235, 36, 39, 0.5);
        }

        .instruction-box {
            background: #fff3cd;
            color: #856404;
            padding: 15px;
            border-radius: 10px;
            font-size: 0.9rem;
            margin-bottom: 25px;
            border-left: 5px solid #ffc107;
        }

        /* Footer Info Styles */
        .contact-footer {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #e9ecef;
        }

        .contact-info-item {
            text-align: center;
            padding: 10px;
            transition: transform 0.2s;
        }

        .contact-info-item:hover {
            transform: translateY(-3px);
        }

        .contact-info-item i {
            font-size: 1.5rem;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .contact-info-item a {
            text-decoration: none;
            color: var(--text-muted);
            font-weight: 500;
            font-size: 0.9rem;
            transition: color 0.2s;
            display: block;
        }

        .contact-info-item a:hover {
            color: var(--primary-color);
        }

        .question-box {
            pointer-events: none;
        }

        @media (min-width: 768px) {
            .btn-submit {
                width: auto;
                min-width: 200px;
            }
        }
    </style>

<div class="content">
    <div class="container">
        <a class="btn btn-primary btn-sm float-right" onclick="history.back()"><i class="fas fa-chevron-left"></i>&nbsp;Back</a><br><br>
            <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-10">

                <div class="main-card">

                    <div class="header-banner">
                        <img src="<?= base_url('assets/backend/img/logo-putih-pengenumroh.png') ?>" alt="Logo" class="logo-img">
                        <h2 class="title-header">Hasil Survey Kepuasan Jamaah</h2>
                        <p class="mb-0 opacity-75">Suara Anda adalah Semangat Kami</p>
                    </div>

                    <div class="card-body p-4 p-md-5">

                        <div class="flash-data" data-flashdata=""></div>

                        <form id="surveyform">

                            <!-- <div class="instruction-box">
                                <strong><i class="fas fa-info-circle"></i> Petunjuk Pengisian:</strong><br>
                                1. Berikan bintang (1-5) pada setiap pertanyaan sesuai kepuasan Anda.<br>
                                2. Pastikan tidak ada pertanyaan yang terlewat sebelum mengirim.
                            </div> -->

                            <h4 class="section-title">Identitas Responden</h4>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <div class="form-floating">
                                        <label for="nama">Nama Lengkap</label>
                                        <input style="margin-bottom: 12px" type="text" class="form-control" name="nama" id="nama"
                                            placeholder="Nama Lengkap" value="<?= $survey->nama ?>" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <label for="tgl_keberangkatan">Tanggal Keberangkatan</label>
                                        <input type="text" class="form-control" disabled name="tgl_keberangkatan"
                                            id="tgl_keberangkatan" placeholder="Tanggal" required readonly
                                              value="<?= date('d-m-Y', strtotime($survey->tgl_keberangkatan)) ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <label for="no_hp">No. Handphone</label>
                                        <input type="tel" class="form-control" name="no_hp" id="no_hp"
                                            placeholder="No HP" required  value="<?= $survey->no_hp ?>" disabled>
                                    </div>
                                </div>
                            </div>

                            <h4 class="section-title text-center mt-4">Kepuasan Pelanggan Terhadap Travel</h4>

                            <div class="question-box">
                                <span class="badge bg-secondary mb-2">Fasilitas</span>
                                <label class="question-label">1. Apakah hotel yang disediakan sudah nyaman dan memenuhi
                                    kebutuhan Bapak/Ibu selama Umroh (check-in, kebersihan, makanan
                                    dan fasilitas)?</label>
                                <div class="star-rating-group">
                                    <input type="radio" id="q1-5" name="q1" value="5" required <?= $survey->q1 == 5 ? 'checked' : '' ?>><label for="q1-5"
                                        title="Sangat Puas"><i class="fas fa-star"></i></label>
                                    <input type="radio" id="q1-4" name="q1" value="4" <?= $survey->q1 == 4 ? 'checked' : '' ?>><label for="q1-4"
                                        title="Puas"><i class="fas fa-star"></i></label>
                                    <input type="radio" id="q1-3" name="q1" value="3" <?= $survey->q1 == 3 ? 'checked' : '' ?>><label for="q1-3"
                                        title="Cukup"><i class="fas fa-star"></i></label>
                                    <input type="radio" id="q1-2" name="q1" value="2" <?= $survey->q1 == 2 ? 'checked' : '' ?>><label for="q1-2"
                                        title="Kurang"><i class="fas fa-star"></i></label>
                                    <input type="radio" id="q1-1" name="q1" value="1" <?= $survey->q1 == 1 ? 'checked' : '' ?>><label for="q1-1"
                                        title="Sangat Kurang"><i class="fas fa-star"></i></label>
                                </div>
                            </div>

                            <div class="question-box">
                                <span class="badge bg-secondary mb-2">Perlengkapan</span>
                                <label class="question-label">2. Apakah perlengkapan dari travel (koper, kain ihram,
                                    dll.) sudah sesuai dan nyaman?</label>
                                <div class="star-rating-group">
                                    <input type="radio" id="q2-5" name="q2" value="5" required <?= $survey->q2 == 5 ? 'checked' : '' ?>><label for="q2-5"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" id="q2-4" name="q2" value="4" <?= $survey->q2 == 4 ? 'checked' : '' ?>><label for="q2-4"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" id="q2-3" name="q2" value="3" <?= $survey->q2 == 3 ? 'checked' : '' ?>><label for="q2-3"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" id="q2-2" name="q2" value="2" <?= $survey->q2 == 2 ? 'checked' : '' ?>><label for="q2-2"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" id="q2-1" name="q2" value="1" <?= $survey->q2 == 1 ? 'checked' : '' ?>><label for="q2-1"><i
                                            class="fas fa-star"></i></label>
                                </div>
                                </div>

                            <div class="question-box">
                                <span class="badge bg-secondary mb-2">Transportasi</span>
                                <label class="question-label">3. Apakah fasilitas transportasi (bus) memberikan
                                    pelayanan terbaik dengan ketepatan waktu penjemputan dan keberangkatan?</label>
                                <div class="star-rating-group">
                                    <input type="radio" id="q3-5" name="q3" value="5" required <?= $survey->q3 == 5 ? 'checked' : '' ?>><label for="q3-5"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" id="q3-4" name="q3" value="4" <?= $survey->q3 == 4 ? 'checked' : '' ?>><label for="q3-4"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" id="q3-3" name="q3" value="3" <?= $survey->q3 == 3 ? 'checked' : '' ?>><label for="q3-3"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" id="q3-2" name="q3" value="2" <?= $survey->q3 == 2 ? 'checked' : '' ?>><label for="q3-2"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" id="q3-1" name="q3" value="1" <?= $survey->q3 == 1 ? 'checked' : '' ?>><label for="q3-1"><i
                                            class="fas fa-star"></i></label>
                                </div>
                            </div>

                            <div class="question-box">
                                <span class="badge bg-secondary mb-2">Bimbingan</span>
                                <label class="question-label">4. Apakah bimbingan ibadah Umroh sudah cukup, dan mudah
                                    dipahami melalui pemaparan dan tanya jawab?</label>
                                <div class="star-rating-group">
                                    <input type="radio" id="q4-5" name="q4" value="5" required <?= $survey->q4 == 5 ? 'checked' : '' ?>><label for="q4-5"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" id="q4-4" name="q4" value="4" <?= $survey->q4 == 4 ? 'checked' : '' ?>><label for="q4-4"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" id="q4-3" name="q4" value="3" <?= $survey->q4 == 3 ? 'checked' : '' ?>><label for="q4-3"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" id="q4-2" name="q4" value="2" <?= $survey->q4 == 2 ? 'checked' : '' ?>><label for="q4-2"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" id="q4-1" name="q4" value="1" <?= $survey->q4 == 1 ? 'checked' : '' ?>><label for="q4-1"><i
                                            class="fas fa-star"></i></label>
                                </div>
                            </div>

                            <div class="question-box">
                                <span class="badge bg-secondary mb-2">Muthowwif</span>
                                <label class="question-label">5. Apakah pembimbing atau muthowwif sangat membantu
                                    jamaah?</label>
                                <div class="star-rating-group">
                                    <input type="radio" id="q5-5" name="q5" value="5" required <?= $survey->q5 == 5 ? 'checked' : '' ?>><label for="q5-5"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" id="q5-4" name="q5" value="4" <?= $survey->q5 == 4 ? 'checked' : '' ?>><label for="q5-4"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" id="q5-3" name="q5" value="3" <?= $survey->q5 == 3 ? 'checked' : '' ?>><label for="q5-3"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" id="q5-2" name="q5" value="2" <?= $survey->q5 == 2 ? 'checked' : '' ?>><label for="q5-2"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" id="q5-1" name="q5" value="1" <?= $survey->q5 == 1 ? 'checked' : '' ?>><label for="q5-1"><i
                                            class="fas fa-star"></i></label>
                                </div>
                            </div>

                            <div class="question-box">
                                <span class="badge bg-secondary mb-2">Program</span>
                                <label class="question-label">6. Apakah program Umroh sesuai dengan harapan, termasuk
                                    itinerary yang sesuai dengan informasi waktu dan tempat?</label>
                                <div class="star-rating-group">
                                    <input type="radio" id="q6-5" name="q6" value="5" required <?= $survey->q6 == 5 ? 'checked' : '' ?>><label for="q6-5"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" id="q6-4" name="q6" value="4" <?= $survey->q6 == 4 ? 'checked' : '' ?>><label for="q6-4"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" id="q6-3" name="q6" value="3" <?= $survey->q6 == 3 ? 'checked' : '' ?>><label for="q6-3"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" id="q6-2" name="q6" value="2" <?= $survey->q6 == 2 ? 'checked' : '' ?>><label for="q6-2"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" id="q6-1" name="q6" value="1" <?= $survey->q6 == 1 ? 'checked' : '' ?>><label for="q6-1"><i
                                            class="fas fa-star"></i></label>
                                </div>
                            </div>

                            <div class="question-box">
                                <span class="badge bg-secondary mb-2">Harga vs Kualitas</span>
                                <label class="question-label">7. Apakah harga yang Bapak/Ibu bayar sesuai dengan
                                    kualitas pelayanan dan fasilitas yang didapatkan?</label>
                                <textarea class="form-control" name="q7" id="q7"
                                    placeholder="Tulis pendapat Anda disini..." style="height: 200px;"
                                    required><?= $survey->q7 ?></textarea>
                            </div>

                            <div class="question-box">
                                <span class="badge bg-secondary mb-2">Pelayanan di Makkah dan Madinah</span>
                                <label class="question-label">8. Apakah Bapak/Ibu mendapatkan pelayanan terbaik selama
                                    di Makkah dan Madinah, termasuk hotel, transportasi, tour leader,
                                    muthowwif, dan layanan lainnya?</label>
                                <div class="star-rating-group">
                                    <input type="radio" id="q8-5" name="q8" value="5" required <?= $survey->q8 == 5 ? 'checked' : '' ?>><label for="q8-5"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" id="q8-4" name="q8" value="4" <?= $survey->q8 == 4 ? 'checked' : '' ?>><label for="q8-4"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" id="q8-3" name="q8" value="3" <?= $survey->q8 == 3 ? 'checked' : '' ?>><label for="q8-3"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" id="q8-2" name="q8" value="2" <?= $survey->q8 == 2 ? 'checked' : '' ?>><label for="q8-2"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" id="q8-1" name="q8" value="1" <?= $survey->q8 == 1 ? 'checked' : '' ?>><label for="q8-1"><i
                                            class="fas fa-star"></i></label>
                                </div>
                            </div>

                            <div class="question-box">
                                <span class="badge bg-secondary mb-2">Saran dan Masukan</span>
                                <label class="question-label">9. Menurut Bapak / Ibu apa yang perlu di perbaiki dari
                                    layanan travel untuk meningkatkan kualitas pelayanan di masa depan?</label>
                                <textarea class="form-control" name="q9" id="q9" placeholder="Masukkan saran Anda..."
                                    style="height: 200px;" required><?= $survey->q9 ?></textarea>
                            </div>

                            <div class="question-box">
                                <span class="badge mb-2" style="background-color: #F9A21D; color: #fff;">Penilaian
                                    Travel</span>
                                <label class="question-label">10. Penilaian secara menyeluruh terhadap layanan travel?
                                    travel?</label>
                                <div class="star-rating-group">
                                    <input type="radio" id="q10-5" name="q10" value="5" required <?= $survey->q10 == 5 ? 'checked' : '' ?>><label for="q10-5"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" id="q10-4" name="q10" value="4" <?= $survey->q10 == 4 ? 'checked' : '' ?>><label for="q10-4"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" id="q10-3" name="q10" value="3" <?= $survey->q10 == 3 ? 'checked' : '' ?>><label for="q10-3"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" id="q10-2" name="q10" value="2" <?= $survey->q10 == 2 ? 'checked' : '' ?>><label for="q10-2"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" id="q10-1" name="q10" value="1" <?= $survey->q10 == 1 ? 'checked' : '' ?>><label for="q10-1"><i
                                            class="fas fa-star"></i></label>
                                </div>
                            </div>


                            <h4 class="section-title text-center mt-4">Kepuasan Pelanggan Terhadap pengenumroh.com</h4>

                            <div class="question-box">
                                <label class="question-label">1. Sejauh mana Bapak/Ibu puas dengan proses pendaftaran,
                                    pendataan, dan pembayaran di pengenumroh? Apakah Bapak/Ibu
                                    mendapatkan kemudahan dan informasi yang jelas?</label>
                                <div class="star-rating-group">
                                    <input type="radio" id="q11-5" name="q11" value="5" required <?= $survey->q11 == 5 ? 'checked' : '' ?>><label for="q11-5"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" id="q11-4" name="q11" value="4" <?= $survey->q11 == 4 ? 'checked' : '' ?>><label for="q11-4"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" id="q11-3" name="q11" value="3" <?= $survey->q11 == 3 ? 'checked' : '' ?>><label for="q11-3"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" id="q11-2" name="q11" value="2" <?= $survey->q11 == 2 ? 'checked' : '' ?>><label for="q11-2"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" id="q11-1" name="q11" value="1" <?= $survey->q11 == 1 ? 'checked' : '' ?>><label for="q11-1"><i
                                            class="fas fa-star"></i></label>
                                </div>
                            </div>

                            <div class="question-box">
                                <label class="question-label">2. Apakah informasi yang diberikan oleh staff pengenumroh
                                    sebelum keberangkatan sudah cukup jelas, termasuk cara
                                    pendaftaran, pemilihan produk, pembayaran, dan penyaluran perlengkapan?</label>
                                <div class="star-rating-group">
                                    <input type="radio" id="q12-5" name="q12" value="5" required <?= $survey->q12 == 5 ? 'checked' : '' ?>><label for="q12-5"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" id="q12-4" name="q12" value="4" <?= $survey->q12 == 4 ? 'checked' : '' ?>><label for="q12-4"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" id="q12-3" name="q12" value="3" <?= $survey->q12 == 3 ? 'checked' : '' ?>><label for="q12-3"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" id="q12-2" name="q12" value="2" <?= $survey->q12 == 2 ? 'checked' : '' ?>><label for="q12-2"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" id="q12-1" name="q12" value="1" <?= $survey->q12 == 1 ? 'checked' : '' ?>><label for="q12-1"><i
                                            class="fas fa-star"></i></label>
                                </div>
                            </div>

                            <div class="question-box">
                                <label class="question-label">3. Bagaimana penilaian Bapak/Ibu terhadap pelayanan
                                    pengenumroh saat menghadapi kendala sebelum keberangkatan, seperti
                                    Paspor, vaksin, seragam, atau perlengkapan lainnya?</label>
                                <div class="star-rating-group">
                                    <input type="radio" id="q13-5" name="q13" value="5" required <?= $survey->q13 == 5 ? 'checked' : '' ?>><label for="q13-5"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" id="q13-4" name="q13" value="4" <?= $survey->q13 == 4 ? 'checked' : '' ?>><label for="q13-4"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" id="q13-3" name="q13" value="3" <?= $survey->q13 == 3 ? 'checked' : '' ?>><label for="q13-3"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" id="q13-2" name="q13" value="2" <?= $survey->q13 == 2 ? 'checked' : '' ?>><label for="q13-2"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" id="q13-1" name="q13" value="1" <?= $survey->q13 == 1 ? 'checked' : '' ?>><label for="q13-1"><i
                                            class="fas fa-star"></i></label>
                                </div>
                            </div>

                            <div class="question-box">
                                <label class="question-label">4. Seberapa puas Bapak/Ibu dengan kualitas pelayanan kami,
                                    seperti pembuatan paspor, penyediaan vaksin dan buku kuning,
                                    antar jemput berkas, serta handling Manasik, keberangkatan, dan kepulangan?</label>
                                <div class="star-rating-group">
                                    <input type="radio" id="q14-5" name="q14" value="5" required <?= $survey->q14 == 5 ? 'checked' : '' ?>><label for="q14-5"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" id="q14-4" name="q14" value="4" <?= $survey->q14 == 4 ? 'checked' : '' ?>><label for="q14-4"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" id="q14-3" name="q14" value="3" <?= $survey->q14 == 3 ? 'checked' : '' ?>><label for="q14-3"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" id="q14-2" name="q14" value="2" <?= $survey->q14 == 2 ? 'checked' : '' ?>><label for="q14-2"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" id="q14-1" name="q14" value="1" <?= $survey->q14 == 1 ? 'checked' : '' ?>><label for="q14-1"><i
                                            class="fas fa-star"></i></label>
                                </div>
                            </div>

                            <div class="question-box">
                                <label class="question-label">5. Apakah Bapak / Ibu akan mempercayakan untuk menggunakan
                                    layanan pengenumroh di kemudian hari? (repeat order)</label>
                                <div class="radio-group mt-2">
                                    <label class="custom-radio">
                                        <input type="radio" name="q15" value="Ya" required <?= $survey->q15 == 'Ya' ? 'checked' : '' ?>> <span>Ya, Tentu</span>
                                    </label>
                                    <label class="custom-radio">
                                        <input type="radio" name="q15" value="Tidak" <?= $survey->q15 == 'Tidak' ? 'checked' : '' ?>> <span>Tidak</span>
                                    </label>
                                </div>
                            </div>

                            <div class="question-box">
                                <label class="question-label">6. Apakah Bapak / Ibu akan merekomendasikan pengenumroh
                                    kepada khalayak ramai?</label>
                                <div class="radio-group mt-2">
                                    <label class="custom-radio">
                                        <input type="radio" name="q16" value="Ya" required <?= $survey->q16 == 'Ya' ? 'checked' : '' ?>> <span>Ya, Pasti</span>
                                    </label>
                                    <label class="custom-radio">
                                        <input type="radio" name="q16" value="Tidak" <?= $survey->q16 == 'Tidak' ? 'checked' : '' ?>> <span>Tidak</span>
                                    </label>
                                </div>
                            </div>

                            <div class="question-box">
                                <label class="question-label">7. Menurut Bapak / Ibu apa yang perlu di perbaiki dari
                                    layanan pengenumroh untuk meningkatkan kualitas pelayanan di masa
                                    depan?</label>
                                <textarea class="form-control" name="q17" id="q17" placeholder="Masukan saran Anda..."
                                    style="height: 200px;" required><?= $survey->q17 ?></textarea>
                            </div>

                            <div class="question-box">
                                <span class="badge mb-2" style="background-color: #F9A21D; color: #fff;">Final
                                    Review</span>
                                <label class="question-label">8. Penilaian secara menyeluruh terhadap
                                    pengenumroh?</label>
                                <div class="star-rating-group">
                                    <input type="radio" id="q18-5" name="q18" value="5" required <?= $survey->q18 == 5 ? 'checked' : '' ?>><label for="q18-5"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" id="q18-4" name="q18" value="4" <?= $survey->q18 == 4 ? 'checked' : '' ?>><label for="q18-4"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" id="q18-3" name="q18" value="3" <?= $survey->q18 == 3 ? 'checked' : '' ?>><label for="q18-3"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" id="q18-2" name="q18" value="2" <?= $survey->q18 == 2 ? 'checked' : '' ?>><label for="q18-2"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" id="q18-1" name="q18" value="1" <?= $survey->q18 == 1 ? 'checked' : '' ?>><label for="q18-1"><i
                                            class="fas fa-star"></i></label>
                                </div>
                            </div>

                            <!-- <div class="text-center mt-5">
                                <button type="submit" class="btn btn-submit">
                                    <i class="fas fa-paper-plane me-2"></i> Kirim Survey
                                </button>
                            </div> -->

                            <div class="contact-footer">
                                <div class="row text-center justify-content-center">
                                    <div class="col-md-4 col-sm-12 mb-4 mb-md-0">
                                        <div class="contact-info-item">
                                            <i class="fas fa-globe"></i>
                                            <a href="https://pengenumroh.com" target="_blank">pengenumroh.com</a>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 mb-4 mb-md-0">
                                        <div class="contact-info-item">
                                            <i class="fas fa-phone"></i>
                                            <a href="tel:+6281234567890">0811-917-988</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            // Datepicker setup with modern styling adjustments
            $('#tgl_keberangkatan').datepicker({
                dateFormat: 'dd-mm-yyyy',
                showAnim: "slideDown"
            });

            // Input number only logic
            $('#no_hp').on('input', function () {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        });
    </script>

<!-- Include jQuery and Bootstrap JS -->
<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>