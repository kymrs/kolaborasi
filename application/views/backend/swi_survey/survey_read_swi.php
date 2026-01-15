<head>
    <?php $this->load->view('template/header'); ?>
</head>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobatwisata | Survey Kepuasan</title>
    <link rel="icon" href="img/favicon-sobatwisata.png">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <style>
        :root {
            --primary-color: #008AD9;
            --primary-dark: #0072b5;
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
            background: linear-gradient(135deg, #008AD9 0%, #0069a3 100%);
            padding: 40px 20px;
            text-align: center;
            color: white;
            border-bottom-left-radius: 30px;
            border-bottom-right-radius: 30px;
            margin-bottom: 20px;
        }

        .logo-img {
            max-width: 180px;
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1));
            margin-bottom: 15px;
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
            background: #f0f9ff;
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
            box-shadow: 0 4px 15px rgba(0, 138, 217, 0.1);
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
            box-shadow: 0 0 0 3px rgba(0, 138, 217, 0.2);
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
            box-shadow: 0 4px 15px rgba(0, 138, 217, 0.4);
            transition: all 0.3s;
            width: 100%;
            margin-top: 20px;
        }

        .btn-submit:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 138, 217, 0.5);
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

        @media (min-width: 768px) {
            .btn-submit {
                width: auto;
                min-width: 200px;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <a class="btn btn-primary btn-sm float-right" style="background-color: #0075b6;" onclick="history.back()"><i class="fas fa-chevron-left"></i>&nbsp;Back</a>

                <div class="main-card mt-5">

                    <div class="header-banner">
                        <img src="<?= base_url('assets/backend/img/logo-putih-sobatwisata.png') ?>" alt="Logo" class="logo-img">
                        <h2 class="title-header">Hasil Survey Kepuasan Pelanggan</h2>
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
                                        <input type="text" class="form-control" name="nama" id="nama"
                                            placeholder="Nama Lengkap" value="<?= $survey->nama ?>" disabled>
                                        <label for="nama">Nama Lengkap</label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-floating">
                                        <input type="tel" class="form-control" name="no_hp" id="no_hp"
                                            placeholder="No HP" value="<?= $survey->no_hp ?>" disabled>
                                        <label for="no_hp">No. Handphone</label>
                                    </div>
                                </div>
                            </div>

                            <h4 class="section-title text-center mt-4">Kepuasan Terhadap Travel</h4>

                            <div class="question-box" style="pointer-events: none;">
                                <span class="badge bg-secondary mb-2">Pelayanan Admin</span>
                                <label class="question-label">1. Seberapa puas Anda dengan proses pemesanan dan
                                    komunikasi
                                    dengan Admin Sobat Wisata?</label>
                                <div class="star-rating-group">
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

                            <div class="question-box" style="pointer-events: none;">
                                <span class="badge bg-secondary mb-2">Kejelasan Informasi</span>
                                <label class="question-label">2. Seberapa jelas informasi yang diberikan oleh Admin
                                    Sobat
                                    Wisata? (Harga, Fasilitas, dll)</label>
                                <div class="star-rating-group">
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

                            <div class="question-box" style="pointer-events: none;">
                                <span class="badge bg-secondary mb-2">Kualitas Armada</span>
                                <label class="question-label">3. Seberapa puas Anda dengan armada yang diberikan oleh
                                    Sobat
                                    Wisata? (Fasilitas, kebersihan dan kenyamanan)</label>
                                <div class="star-rating-group">
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

                            <div class="question-box" style="pointer-events: none;">
                                <span class="badge bg-secondary mb-2">Pelayanan Crew</span>
                                <label class="question-label">4. Seberapa puas Anda dengan pelayanan dari Crew armada
                                    selama
                                    perjalanan wisata Anda?</label>
                                <div class="star-rating-group">
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

                            <div class="question-box" style="pointer-events: none;">
                                <span class="badge bg-secondary mb-2">Loyalitas Pelanggan</span>
                                <label class="question-label">5. Seberapa besar kemungkinan Anda akan kembali
                                    menggunakan
                                    layanan Sobat Wisata?</label>
                                <div class="star-rating-group">
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

                            <div class="question-box" style="pointer-events: none;">
                                <label class="question-label">6. Silakan tulis testimoni atau kesan Anda tentang layanan
                                    Sobat
                                    Wisata</label>
                                <textarea class="form-control" name="q6" id="q6" placeholder="Masukkan saran Anda..."
                                    style="height: 155px;" required><?= $survey->q6 ?></textarea>
                            </div>

                            <div class="question-box" style="pointer-events: none;">
                                <label class="question-label">7. Berikan masukan terhadap layanan Sobat Wisata</label>
                                <textarea class="form-control" name="q7" id="q7" placeholder="Masukan saran Anda..."
                                    style="height: 155px;" required><?= $survey->q7 ?></textarea>
                            </div>
                            
                            <div class="question-box" style="pointer-events: none;">
                                <span class="badge bg-primary mb-2">Final Review</span>
                                <label class="question-label">8. Penilaian secara menyeluruh terhadap
                                    sobatwisata?</label>
                                <div class="star-rating-group">
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
                                            <a href="https://sobatwisata.id" target="_blank">sobatwisata.id</a>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 mb-4 mb-md-0">
                                        <div class="contact-info-item">
                                            <i class="fas fa-phone"></i>
                                            <a href="tel:+6281234567890">0822-2922-00</a>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <div class="contact-info-item">
                                            <i class="fas fa-envelope"></i>
                                            <a href="mailto:admin@sobatwisata.id">cs@sobatwisata.id</a>
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

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            // Datepicker setup with modern styling adjustments
            $('#tgl_keberangkatan').datepicker({
                dateFormat: 'yy-mm-dd',
                showAnim: "slideDown"
            });

            // Input number only logic
            $('#no_hp').on('input', function () {
                this.value = this.value.replace(/[^0-9]/g, '');
            });

            // Form Submission Logic
            $("#surveyform").submit(function (e) {
                e.preventDefault();

                // Show loading state on button
                let btn = $('.btn-submit');
                let originalText = btn.html();
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> Mengirim...');

                $.ajax({
                    type: 'POST',
                    url: "https://kolaborasigroup.com/admin/pu_survey/add",
                    data: $(this).serialize(),
                    dataType: 'JSON',
                    success: function (data) {
                        Swal.fire({
                            title: "Terima Kasih!",
                            text: "Masukan Anda sangat berarti bagi kami untuk pelayanan yang lebih baik.",
                            icon: 'success',
                            confirmButtonColor: '#008AD9', // Warna Tombol diubah ke Biru
                            confirmButtonText: 'Kembali ke Beranda',
                            showConfirmButton: true
                        }).then(function () {
                            window.location.href = "https://pengenumroh.com";
                        });
                    },
                    error: function () {
                        // Error handling visual
                        btn.prop('disabled', false).html(originalText);
                        Swal.fire({
                            title: "Oops...",
                            text: "Terjadi kesalahan saat mengirim data, silakan coba lagi.",
                            icon: 'error'
                        });
                    },
                    complete: function () {
                        // Just in case success doesn't redirect immediately
                        // btn.prop('disabled', false).html(originalText);
                    }
                });
            });
        });
    </script>
</body>

</html>

<!-- Include jQuery and Bootstrap JS -->
<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>