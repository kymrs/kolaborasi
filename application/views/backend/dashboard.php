<div class="container-fluid">
    <div class="row">

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
            <a href="#" class="card shadow h-100 py-4 text-decoration-none" style="border-left: 5px solid #3770C1;">
                <div class="card-body">
                    <div class="d-flex justify-content-center">
                        <!-- Image inside card, properly scaled and responsive -->
                        <img src="<?= base_url('assets/backend/img/pengenwisata.png'); ?>" class="img-fluid" alt="Logo" style="max-width: 100%; height: auto; border-radius: 10px;">
                    </div>
                </div>
            </a>
        </div>

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