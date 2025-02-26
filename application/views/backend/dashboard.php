<div class="container-fluid">
    <div class="row">

        <?php
        // Ambil nilai core dari user yang sedang login
        $user = $this->db->select('core')
            ->where('id_user', $this->session->userdata('id_user'))
            ->get('tbl_user')
            ->row_array();

        $core = $user['core'] ?? ''; // Default kosong kalau core tidak ada
        $core_array = explode(',', $core);

        // Ambil data dari tbl_menu
        $item = $this->db->select('urutan, sub_image, sub_color')
            ->where('sub_image !=', null)
            ->where('sub_color !=', null)
            ->order_by('urutan', 'ASC') // Mengurutkan berdasarkan kolom urutan secara ascending
            ->get('tbl_menu')
            ->result_array();
        ?>

        <!-- Card -->
        <?php foreach ($item as $data) : ?>
            <?php if (in_array($data['urutan'], $core_array)) : // Cek apakah id_menu ada di dalam core_array 
            ?>
                <div class="col-xl-3 col-md-6 mb-4">
                    <!-- Card with hover effect and clickable action -->
                    <a href="#" class="card shadow h-100 py-4 text-decoration-none" style="border-left: 5px solid #<?= $data['sub_color'] ?>">
                        <div class="card-body">
                            <div class="d-flex justify-content-center">
                                <!-- Image inside card, properly scaled and responsive -->
                                <img src="<?= base_url('assets/backend/img/' . $data['sub_image']); ?>" class="img-fluid" alt="Logo" style="max-width: 100%; height: auto; border-radius: 10px;">
                            </div>
                        </div>
                    </a>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
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