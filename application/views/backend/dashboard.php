<div class="container-fluid">
    <div class="row">

        <?php $core = $this->session->userdata('core') ?>

        <?php
        $statement1 = $core == "all";
        $statement2 = $core == "kps";
        $statement3 = $core == "pu";
        $statement4 = $core == "swa";
        $statement5 = $core == "swi";
        $statement6 = $core == "bmn";
        $statement7 = $core == "qbg";
        $statement8 = $core == "ctz";
        $statement9 = $core == "mac";
        ?>

        <!-- ALL -->
        <?php if ($statement1) : ?>
            <!-- Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <!-- Card with hover effect and clickable action -->
                <a href="https://kolaborasigroup.com/" target="_blank" class="card shadow h-100 py-4 text-decoration-none" style="border-left: 5px solid #3BB8EA;">
                    <div class="card-body">
                        <div class="d-flex justify-content-center">
                            <!-- Image inside card, properly scaled and responsive -->
                            <img src="<?= base_url('assets/backend/img/kolaborasi.png'); ?>" class="img-fluid" alt="Logo" style="max-width: 100%; height: auto; border-radius: 10px;">
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <!-- Card with hover effect and clickable action -->
                <a href="https://pengenumroh.com/" target="_blank" class="card border-left-warning shadow h-100 py-4 text-decoration-none">
                    <div class="card-body">
                        <div class="d-flex justify-content-center">
                            <!-- Image inside card, properly scaled and responsive -->
                            <img src="<?= base_url('assets/backend/img/pengenumroh.png'); ?>" class="img-fluid" alt="Logo" style="max-width: 100%; height: auto; auto; border-radius: 10px;">
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <!-- Card with hover effect and clickable action -->
                <a href="https://sebelaswarna.com/" target="_blank" class="card shadow h-100 py-4 text-decoration-none" style="border-left: 5px solid #594093;">
                    <div class="card-body">
                        <div class="d-flex justify-content-center">
                            <!-- Image inside card, properly scaled and responsive -->
                            <img src="<?= base_url('assets/backend/img/sebelaswarna.png'); ?>" class="img-fluid" alt="Logo" style="max-width: 100%; height: auto; border-radius: 10px;">
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <!-- Card with hover effect and clickable action -->
                <a href="#" class="card shadow h-100 py-4 text-decoration-none" style="border-left: 5px solid #3770C1;">
                    <div class="card-body">
                        <div class="d-flex justify-content-center">
                            <!-- Image inside card, properly scaled and responsive -->
                            <img src="<?= base_url('assets/backend/img/sobatwisata.png'); ?>" class="img-fluid" alt="Logo" style="max-width: 100%; height: auto; border-radius: 10px;">
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <!-- Card with hover effect and clickable action -->
                <a href="#" class="card shadow h-100 py-4 text-decoration-none" style="border-left: 5px solid #4B4D4A;">
                    <div class="card-body">
                        <div class="d-flex justify-content-center">
                            <!-- Image inside card, properly scaled and responsive -->
                            <img src="<?= base_url('assets/backend/img/bymoment.png'); ?>" class="img-fluid" alt="Logo" style="max-width: 100%; height: auto; border-radius: 10px;">
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <!-- Card with hover effect and clickable action -->
                <a href="#" class="card shadow h-100 py-4 text-decoration-none" style="border-left: 5px solid #00BE64;">
                    <div class="card-body">
                        <div class="d-flex justify-content-center">
                            <!-- Image inside card, properly scaled and responsive -->
                            <img src="<?= base_url('assets/backend/img/qubagift.png'); ?>" class="img-fluid" alt="Logo" style="max-width: 100%; height: auto; border-radius: 10px;">
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <!-- Card with hover effect and clickable action -->
                <a href="#" class="card shadow h-100 py-4 text-decoration-none" style="border-left: 5px solid #110A69;">
                    <div class="card-body">
                        <div class="d-flex justify-content-center">
                            <!-- Image inside card, properly scaled and responsive -->
                            <img src="<?= base_url('assets/backend/img/carstensz.png'); ?>" class="img-fluid" alt="Logo" style="max-width: 100%; height: auto; border-radius: 10px;">
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <!-- Card with hover effect and clickable action -->
                <a href="#" class="card shadow h-100 py-4 text-decoration-none" style="border-left: 5px solid #E91E24;">
                    <div class="card-body">
                        <div class="d-flex justify-content-center">
                            <!-- Image inside card, properly scaled and responsive -->
                            <img src="<?= base_url('assets/backend/img/mobileautocare.png'); ?>" class="img-fluid" alt="Logo" style="max-width: 100%; height: auto; border-radius: 10px;">
                        </div>
                    </div>
                </a>
            </div>

        <?php endif ?>

        <!-- KPS -->
        <?php if ($statement2) : ?>
            <!-- Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <!-- Card with hover effect and clickable action -->
                <a href="#" class="card shadow h-100 py-4 text-decoration-none" style="border-left: 5px solid #3BB8EA;">
                    <div class="card-body">
                        <div class="d-flex justify-content-center">
                            <!-- Image inside card, properly scaled and responsive -->
                            <img src="<?= base_url('assets/backend/img/kolaborasi.png'); ?>" class="img-fluid" alt="Logo" style="max-width: 100%; height: auto; border-radius: 10px;">
                        </div>
                    </div>
                </a>
            </div>
        <?php endif ?>

        <!-- PU -->
        <?php if ($statement3) : ?>
            <!-- Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <!-- Card with hover effect and clickable action -->
                <a href="#" class="card border-left-warning shadow h-100 py-4 text-decoration-none">
                    <div class="card-body">
                        <div class="d-flex justify-content-center">
                            <!-- Image inside card, properly scaled and responsive -->
                            <img src="<?= base_url('assets/backend/img/pengenumroh.png'); ?>" class="img-fluid" alt="Logo" style="max-width: 100%; height: auto; auto; border-radius: 10px;">
                        </div>
                    </div>
                </a>
            </div>

        <?php endif ?>

        <!-- SW -->
        <?php if ($statement4) : ?>
            <!-- Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <!-- Card with hover effect and clickable action -->
                <a href="#" class="card shadow h-100 py-4 text-decoration-none" style="border-left: 5px solid #594093;">
                    <div class="card-body">
                        <div class="d-flex justify-content-center">
                            <!-- Image inside card, properly scaled and responsive -->
                            <img src="<?= base_url('assets/backend/img/sebelaswarna.png'); ?>" class="img-fluid" alt="Logo" style="max-width: 100%; height: auto; border-radius: 10px;">
                        </div>
                    </div>
                </a>
            </div>
        <?php endif ?>

        <!-- PW -->
        <?php if ($statement5) : ?>
            <!-- Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <!-- Card with hover effect and clickable action -->
                <a href="#" class="card shadow h-100 py-4 text-decoration-none" style="border-left: 5px solid #3770C1;">
                    <div class="card-body">
                        <div class="d-flex justify-content-center">
                            <!-- Image inside card, properly scaled and responsive -->
                            <img src="<?= base_url('assets/backend/img/sobatwisata.png'); ?>" class="img-fluid" alt="Logo" style="max-width: 100%; height: auto; border-radius: 10px;">
                        </div>
                    </div>
                </a>
            </div>
        <?php endif ?>

        <!-- BMN -->
        <?php if ($statement6) : ?>
            <!-- Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <!-- Card with hover effect and clickable action -->
                <a href="#" class="card shadow h-100 py-4 text-decoration-none" style="border-left: 5px solid #4B4D4A;">
                    <div class="card-body">
                        <div class="d-flex justify-content-center">
                            <!-- Image inside card, properly scaled and responsive -->
                            <img src="<?= base_url('assets/backend/img/bymoment.png'); ?>" class="img-fluid" alt="Logo" style="max-width: 100%; height: auto; border-radius: 10px;">
                        </div>
                    </div>
                </a>
            </div>
        <?php endif ?>

        <!-- QBG -->
        <?php if ($statement7) : ?>
            <!-- Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <!-- Card with hover effect and clickable action -->
                <a href="#" class="card shadow h-100 py-4 text-decoration-none" style="border-left: 5px solid #00BE64;">
                    <div class="card-body">
                        <div class="d-flex justify-content-center">
                            <!-- Image inside card, properly scaled and responsive -->
                            <img src="<?= base_url('assets/backend/img/qubagift.png'); ?>" class="img-fluid" alt="Logo" style="max-width: 100%; height: auto; border-radius: 10px;">
                        </div>
                    </div>
                </a>
            </div>
        <?php endif ?>

        <!-- CTZ -->
        <?php if ($statement8) : ?>
            <!-- Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <!-- Card with hover effect and clickable action -->
                <a href="#" class="card shadow h-100 py-4 text-decoration-none" style="border-left: 5px solid #110A69;">
                    <div class="card-body">
                        <div class="d-flex justify-content-center">
                            <!-- Image inside card, properly scaled and responsive -->
                            <img src="<?= base_url('assets/backend/img/carstensz.png'); ?>" class="img-fluid" alt="Logo" style="max-width: 100%; height: auto; border-radius: 10px;">
                        </div>
                    </div>
                </a>
            </div>
        <?php endif ?>

        <!-- MAC -->
        <?php if ($statement9) : ?>
            <!-- Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <!-- Card with hover effect and clickable action -->
                <a href="#" class="card shadow h-100 py-4 text-decoration-none" style="border-left: 5px solid #E91E24;">
                    <div class="card-body">
                        <div class="d-flex justify-content-center">
                            <!-- Image inside card, properly scaled and responsive -->
                            <img src="<?= base_url('assets/backend/img/mobileautocare.png'); ?>" class="img-fluid" alt="Logo" style="max-width: 100%; height: auto; border-radius: 10px;">
                        </div>
                    </div>
                </a>
            </div>
        <?php endif ?>

    </div>
</div>

<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>

<!-- CSS for hover effect and card transitions -->
<style>
    .card {
        transition: all 0.3s ease;
        /* Smooth transition for hover */
    }

    .card:hover {
        transform: translateY(-5px);
        /* Slightly lifts the card */
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        /* Deeper shadow on hover */
        background-color: #f8f9fa;
        /* Subtle background change */
    }

    .card-body {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .card img {
        max-width: 80%;
        /* Image scaling */
        height: auto;
        border-radius: 10px;
    }
</style>