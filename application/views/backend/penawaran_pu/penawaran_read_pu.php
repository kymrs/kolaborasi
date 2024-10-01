    <?php $this->load->view('template/header'); ?>

    <head>
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
        <style>
            .container-custom {
                max-width: 1050px;
                /* Maksimal lebar container */
            }

            .logo-custom {
                width: 300px;
                height: auto;
            }

            .label-inline {
                display: inline-block;
                min-width: 150px;
                /* Lebar minimum untuk label */
                font-weight: bold;
            }

            .value-inline {
                display: inline-block;
            }

            /* 
            img.qr-code {
                width: 175px;
            } */

            .orange-box,
            .biaya-box,
            .ekstra-box {
                background-color: #FC7714;
                /* Warna oranye lebih gelap */
                color: white;
                padding: 10px;
                text-align: center;
                /* Teks di tengah */
                font-size: 1.25rem;
                /* Ukuran teks */
                font-weight: bold;
                /* Teks tebal */
                margin-bottom: 1rem;
                /* Jarak di bawah setiap box */
            }

            .description {
                color: #333;
                font-size: 1rem;
                margin-bottom: 1rem;
                line-height: 1.75;
            }

            .list-item {
                list-style: decimal;
                margin-left: 1.5rem;
                color: #333;
                margin-bottom: 1rem;
            }

            .section-title {
                font-weight: bold;
                color: #FC7714;
                margin-bottom: 0.5rem;
            }

            .right-section {
                padding-left: 1rem;
            }

            .price-text {
                font-size: 2rem;
                /* Ukuran teks harga lebih besar */
                color: #333;
                text-align: center;
                font-weight: bold;
                margin-bottom: 1.5rem;
            }

            .promo-text {
                text-align: left;
                color: #333;
                font-size: 1rem;
                margin-top: 1.5rem;
                margin-bottom: 1.5rem;
            }

            /* Responsive Adjustments */
            @media (max-width: 1366px) {
                .container-custom {
                    max-width: 900px;
                }
            }

            @media (max-width: 1024px) {
                .logo-custom {
                    max-width: 400px;
                }

                .label-inline {
                    min-width: 120px;
                }

                .description {
                    font-size: 0.95rem;
                }

                .section-title {
                    font-size: 1.1rem;
                }

                .price-text {
                    font-size: 1.75rem;
                }
            }

            @media (max-width: 768px) {
                .label-inline {
                    min-width: 100px;
                }

                .description {
                    font-size: 0.9rem;
                }

                .section-title {
                    font-size: 1rem;
                }

                .price-text {
                    font-size: 1.5rem;
                }

                .promo-text {
                    font-size: 0.9rem;
                }
            }

            @media (max-width: 640px) {
                .logo-custom {
                    max-width: 300px;
                }

                .label-inline {
                    display: block;
                    min-width: unset;
                }

                .value-inline {
                    display: block;
                }

                .description {
                    font-size: 0.85rem;
                }

                .section-title {
                    font-size: 0.95rem;
                }

                .price-text {
                    font-size: 1.25rem;
                }

                .promo-text {
                    font-size: 0.85rem;
                    text-align: center;
                }
            }

            @media (max-width: 480px) {
                .price-text {
                    font-size: 1rem;
                }
            }
        </style>
    </head>

    <!-- Logo Section -->
    <div class="container-custom mx-auto mt-10">
        <div class="text-center">
            <img src="<?= base_url() ?>/assets/backend/img/pengenumroh.png" alt="Logo" class="mx-auto logo-custom">
        </div>
    </div>

    <!-- Document Info and QR Code Section -->
    <div class="container-custom mx-auto mt-10 flex flex-wrap justify-between items-center">
        <!-- Left Section: Document Info -->
        <div class="w-full md:w-2/3 p-4" id="Penawaran_label">
            <h1 class="text-3xl font-bold text-left text-gray-700 mb-4">PENAWARAN</h1>
            <div class="mb-1">
                <span class="label-inline text-gray-600">Nomor</span>
                <span class="value-inline text-gray-800">: <?= $penawaran['no_pelayanan'] ?></span>
            </div>
            <div class="mb-1">
                <span class="label-inline text-gray-600">Tanggal Dokumen</span>
                <span class="value-inline text-gray-800">: <?= $this->M_penawaran_pu->getTanggal($penawaran['created_at']) ?></span>
            </div>
            <div class="mb-1">
                <span class="label-inline text-gray-600">Berlaku s.d</span>
                <span class="value-inline text-gray-800">: <?= $this->M_penawaran_pu->getTanggal($penawaran['tgl_berlaku']) ?></span>
            </div>
            <div class="mb-1">
                <span class="label-inline text-gray-600">Produk</span>
                <span class="value-inline text-gray-800">: <?= $penawaran['produk'] ?></span>
            </div>
            <div class="mb-1">
                <span class="label-inline text-gray-600">Kepada</span>
                <span class="value-inline text-gray-800">: <?= $penawaran['pelanggan'] ?></span>
            </div>
        </div>

        <!-- Right Section: QR Code -->
        <div class="w-full md:w-1/3 p-4 text-center right-section">
            <img src="<?= base_url() ?>assets/backend/document/qrcode/qr-<?= $penawaran['no_arsip'] ?>.png" alt="QR Code" class="mx-auto w-64 h-64">
        </div>
    </div>

    <!-- Orange Box Section (Layanan) -->
    <div class="container-custom mx-auto mt-5">
        <div class="orange-box">
            LAYANAN
        </div>
    </div>

    <!-- Combined Info Section -->
    <div class="container-custom mx-auto mt-5 flex flex-wrap justify-between">
        <!-- Left Section: Deskripsi dan Layanan Termasuk -->
        <div class="w-full md:w-2/3 p-4">
            <!-- Deskripsi -->
            <div class="description mb-4">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur pretium, mauris ac varius efficitur,
                tortor mi dignissim justo, id feugiat justo eros nec metus,Lorem ipsum dolor sit amet, consectetur
                tortor mi dignissim justo, id feugiat justo eros nec metus.
            </div>

            <!-- Layanan Termasuk -->
            <h2 class="section-title">Layanan Termasuk:</h2>
            <ul class="list-item mb-4">
                <?php foreach ($layanan_termasuk as $data) : ?>
                    <?php if ($data['id_layanan'] != 9) : ?>
                        <li><?= $data['nama_layanan'] ?></li>
                    <?php endif ?>
                    <?php if ($data['id_layanan'] == 9) : ?>
                        <span>
                            <li><?= $data['nama_layanan'] ?> Rp. <?= number_format(preg_replace('/\D/', '', $data['is_active']), 0, ',', '.') ?></li>
                        </span>
                    <?php endif ?>
                <?php endforeach ?>
            </ul>
        </div>

        <!-- Right Section: Informasi Tambahan -->
        <div class="w-full md:w-1/3 p-4">
            <!-- Keberangkatan -->
            <div class="mb-1">
                <span class="label-inline text-gray-600">Keberangkatan</span>
                <span class="value-inline text-gray-800">: <?= $this->M_penawaran_pu->getTanggal($penawaran['keberangkatan']) ?></span>
                <!-- <span class="value-inline text-gray-800">: 01 September 2024</span> -->
            </div>
            <!-- Durasi -->
            <div class="mb-1">
                <span class="label-inline text-gray-600">Durasi</span>
                <span class="value-inline text-gray-800">: <?= $penawaran['durasi'] ?> Hari</span>
            </div>
            <!-- Berangkat Dari -->
            <div class="mb-1">
                <span class="label-inline text-gray-600">Berangkat dari</span>
                <span class="value-inline text-gray-800">: <?= $penawaran['tempat'] ?></span>
            </div>

            <!-- Layanan Tidak Termasuk -->
            <h2 class="section-title mt-5">Layanan Tidak Termasuk :</h2>
            <ul class="list-item mb-4">
                <?php foreach ($layanan_tidak_termasuk as $data) : ?>
                    <li><?= $data['nama_layanan'] ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    </div>

    <!-- Biaya Section -->
    <div class="container-custom mx-auto mt-5">
        <div class="biaya-box">
            BIAYA
        </div>
        <div class="price-text">
            Rp. <?= number_format($penawaran['biaya'], 0, ',', '.') ?>,- /pax
        </div>
    </div>

    <!-- Ekstra Section -->
    <div class="container-custom mx-auto mt-5">
        <div class="ekstra-box">
            EKSTRA
        </div>
        <div class="promo-text">
            Informasi tambahan tentang layanan ekstra yang mungkin tersedia.
        </div>
    </div>
    <?php $this->load->view('template/footer'); ?>
    <?php $this->load->view('template/script'); ?>

    <script>

    </script>