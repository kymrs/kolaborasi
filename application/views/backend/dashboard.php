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
        $item = $this->db->select('id_menu, nama_menu, sub_image, sub_color')
            ->where('sub_image !=', null)
            ->where('sub_color !=', null)
            ->order_by('urutan', 'ASC') // Mengurutkan berdasarkan kolom urutan secara ascending
            ->get('tbl_menu')
            ->result_array();
        ?>
    
        <!-- Card -->
        <?php foreach ($item as $data) : ?>
            <?php if (in_array($data['id_menu'], $core_array)) : // Cek apakah id_menu ada di dalam core_array 
            ?>
                <?php
                // Ambil nama user
                $id = $this->session->userdata('id_user');
                $name = $this->db->select('fullname')
                    ->from('tbl_user')
                    ->where('id_user', $id)
                    ->get()
                    ->row('fullname');

                // Tentukan prefix berdasarkan nama_menu
                $prefix = '';
                if (stripos($data['nama_menu'], 'pengenumroh') !== false) $prefix = 'pu'; 
                else if (stripos($data['nama_menu'], 'bymoment') !== false) $prefix = 'bmn';
                else if (stripos($data['nama_menu'], 'carstensz') !== false) $prefix = 'ctz';
                else if (stripos($data['nama_menu'], 'mobileautocare') !==      false) $prefix = 'mac';
                else if (stripos($data['nama_menu'], 'KPS') !== false) $prefix = 'kps';
                else if (stripos($data['nama_menu'], 'sebelaswarna') !== false) $prefix = 'sw';
                else if (stripos($data['nama_menu'], 'sobatwisata') !== false) $prefix = 'swi';
                else if (stripos($data['nama_menu'], 'quba') !== false) $prefix = 'qbg';
                else if (stripos($data['nama_menu'], 'samlog') !== false) $prefix = 'sml';

                // Cek jumlah data pending berdasarkan approval untuk user
                $pending = 0;
                if ($prefix) {
                    $tables = ['reimbust', 'prepayment', 'deklarasi', 'notifikasi']; // sesuaikan nama tabel
                    foreach ($tables as $table) {
                        $table_name = $prefix . '_' . $table;
                        if ($this->db->table_exists($table_name)) {
                            $fields = $this->db->list_fields($table_name);
                            $where = [];

                            if (in_array('app4_name', $fields) && in_array('app4_status', $fields)) {
                                $where[] = "(LOWER(app4_name) = " . $this->db->escape(strtolower($name)) . " AND app4_status = 'waiting' AND app4_name IS NOT NULL)";
                            }

                            if (in_array('app_name', $fields) && in_array('app_status', $fields)) {
                                if (in_array('app4_status', $fields) && in_array('app4_name', $fields)) {
                                    $where[] = "(" .
                                        "LOWER(app_name) = " . $this->db->escape(strtolower($name)) . " AND " .
                                        "app_status = 'waiting' AND " .
                                        "(app4_name IS NULL OR app4_name = '' OR app4_status = 'approved')" .
                                        ")";
                                } else {
                                    $where[] = "(" .
                                        "LOWER(app_name) = " . $this->db->escape(strtolower($name)) . " AND " .
                                        "app_status = 'waiting'" .
                                        ")";
                                }
                            }

                            if (in_array('app2_name', $fields) && in_array('app2_status', $fields)) {
                                if (in_array('app_status', $fields)) {
                                    $where[] = "(LOWER(app2_name) = " . $this->db->escape(strtolower($name)) . " AND app2_status = 'waiting' AND app_status = 'approved')";
                                } else {
                                    $where[] = "(LOWER(app2_name) = " . $this->db->escape(strtolower($name)) . " AND app2_status = 'waiting')";
                                }
                            }

                            if (!empty($where)) {
                                $this->db->from($table_name)
                                    ->where(implode(' OR ', $where), null, false);
                                $count = $this->db->count_all_results();
                                $pending += $count;
                            }
                        }
                    }
                }
                $notif_count = $pending;
                ?>
                <div class="col-xl-3 col-md-6 mb-4" style="height: 244px;">
                    <i class=""></i>
                    <!-- Card with hover effect and click action -->
                    <a href="#" class="card shadow h-100 py-4 text-decoration-none notification-card" data-menu-id="<?=$data['id_menu']?>" style="border-left: 5px solid #<?= $data['sub_color'] ?>">
                        <div class="card-body">
                            <?php if ($notif_count > 0) : ?>
                                <i class="fas fa-bell bell-shake" style="position: absolute; top: 10px; right: 10px; color: red;"></i>
                            <?php endif; ?>
                            <div class="d-flex justify-content-center">
                                <!-- Image inside card, properly scaled and responsive -->
                                <img src="<?= base_url('assets/backend/img/' . $data['sub_image']); ?>" class="img-fluid" alt="Logo" style="max-width: 100%; height: auto; border-radius: 10px;">
                            </div>
                        </div>
                    </a>
                </div>
            <?php endif; ?>
            <?php if (in_array($data['id_menu'], $core_array)) : ?>
                <div class="modal fade notification-modal" id="notification-modal-<?=$data['id_menu']?>" tabindex="-1" role="dialog" aria-labelledby="notificationModalLabel<?=$data['id_menu']?>" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="notificationModalLabel<?=$data['id_menu']?>">Notifikasi <?= $data['nama_menu'] ?></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <!-- Loading spinner -->
                                <div class="notification-loading text-center" style="display: none;">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <p class="mt-2">Memuat data...</p>
                                </div>
                                <!-- Content will be loaded here -->
                                <div class="notification-content"></div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
        
    </div>
</div>

<?php $this->load->view('template/footer'); ?>
<?php $this->load->view('template/script'); ?>

<!-- <script>
$(document).ready(function() {
    $('.modal').on('shown.bs.modal', function () {
        var table = $(this).find('table');
        console.log('Table URL:', table.data('url')); // Debug log
            if (!table.hasClass('dataTable')) {
                table.DataTable({
                "responsive": true,
                "scrollX": true,
                "processing": true,
                "serverSide": true,
                "order": [],
                "ajax": {
                    "url": table.data('url'),
                    "type": "POST",
                },
                "columns": [
                    { "data": "no" },
                    { "data": "data1" },
                    { "data": "data2" }
                ],
            });
        }
    });
});
</script> -->

<script>
$(document).ready(function() {
    // Handle card click - check data first before showing modal
    $('.notification-card').on('click', function(e) {
        e.preventDefault();
        var menuId = $(this).data('menu-id');
        var modalId = 'notification-modal-' + menuId;
        
        // Check if data exists first
        checkAndShowModal(modalId, menuId);
    });

    $('.notification-modal').on('shown.bs.modal', function () {
        var modalId = $(this).attr('id');
        var menuId = modalId.replace('notification-modal-', '');
        loadNotificationData(modalId, menuId);
    });

    // Auto search on input
    $(document).on('change', '.search-jenis', function() {
        var menuId = $(this).attr('id').split('-').pop(); // get menuId from id like search-jenis-1
        var modalId = 'notification-modal-' + menuId;
        loadNotificationData(modalId, menuId);
    });

    function checkAndShowModal(modalId, menuId) {
        var searchJenis = $('#search-jenis-' + menuId).val();
        
        // Check if data exists before showing modal
        $.ajax({
            url: '<?= base_url("notifikasi/get_data_pending_notifications") ?>',
            type: 'GET',
            data: {
                jenis: searchJenis,
                menu_id: menuId
            },
            dataType: 'json',
            success: function(data) {
                var nama_menu = $('#' + modalId + ' .modal-title').text().replace('Notifikasi ', '');
                var prefix = '';
                if (nama_menu.toLowerCase().includes('pengenumroh')) prefix = 'pu';
                else if (nama_menu.toLowerCase().includes('bymoment')) prefix = 'bmn';
                else if (nama_menu.toLowerCase().includes('carstensz')) prefix = 'ctz';
                else if (nama_menu.toLowerCase().includes('mobileautocare')) prefix = 'mac';
                else if (nama_menu.toLowerCase().includes('kps')) prefix = 'kps';
                else if (nama_menu.toLowerCase().includes('sebelaswarna')) prefix = 'sw';  
                else if (nama_menu.toLowerCase().includes('sobatwisata')) prefix = 'swi';
                else if (nama_menu.toLowerCase().includes('quba')) prefix = 'qbg';
                else if (nama_menu.toLowerCase().includes('samlog')) prefix = 'sml';
                
                var filteredData = data.filter(function(item) {
                    return item.form.startsWith(prefix + '_');
                });
                
                // Check if data exists
                if (filteredData.length > 0) {
                    // Data exists, show modal
                    $('#' + modalId).modal('show');
                }
                // If no data, do nothing - don't show any modal or popup
            },
            error: function() {
                // On error, do nothing - don't show any popup
            }
        });
    }

    function loadNotificationData(modalId, menuId) {
        var searchJenis = $('#search-jenis-' + menuId).val();
        
        // Show loading spinner
        $('#' + modalId + ' .notification-loading').show();
        $('#' + modalId + ' .notification-content').html('');
        
        // Load pending details for this menu
        $.ajax({
            url: '<?= base_url("notifikasi/get_data_pending_notifications") ?>',
            type: 'GET',
            data: {
                jenis: searchJenis,
                menu_id: menuId
            },
            dataType: 'json',
            success: function(data) {
                console.log(data);
                var html = '';
                var nama_menu = $('#' + modalId + ' .modal-title').text().replace('Notifikasi ', '');
                var prefix = '';
                if (nama_menu.toLowerCase().includes('pengenumroh')) prefix = 'pu';
                else if (nama_menu.toLowerCase().includes('bymoment')) prefix = 'bmn';
                else if (nama_menu.toLowerCase().includes('carstensz')) prefix = 'ctz';
                else if (nama_menu.toLowerCase().includes('mobileautocare')) prefix = 'mac';
                else if (nama_menu.toLowerCase().includes('kps')) prefix = 'kps';
                else if (nama_menu.toLowerCase().includes('sebelaswarna')) prefix = 'sw';  
                else if (nama_menu.toLowerCase().includes('sobatwisata')) prefix = 'swi';
                else if (nama_menu.toLowerCase().includes('quba')) prefix = 'qbg';
                else if (nama_menu.toLowerCase().includes('samlog')) prefix = 'sml';
                
                var filteredData = data.filter(function(item) {
                    return item.form.startsWith(prefix + '_');
                });
                if (filteredData.length > 0) {
                    // Versi list baru
                    html += '<ul class="list-group list-group-flush w-100">';
                    $.each(filteredData, function(index, item) {
                        var now = new Date();
                        var pengajuanDate = new Date(item.tanggal_pengajuan);
                        var diffMs = now - pengajuanDate;
                        var diffHours = Math.floor(diffMs / (1000 * 60 * 60));
                        var diffDays = Math.floor(diffHours / 24);
                        var timeAgo = '';
                        if (diffDays > 0) {
                            timeAgo = diffDays + ' hari yang lalu';
                        } else if (diffHours > 0) {
                            timeAgo = diffHours + ' jam yang lalu';
                        } else {
                            timeAgo = 'Baru saja';
                        }
                        
                        var dateObj = new Date(item.tanggal_pengajuan);
                        var tanggal = dateObj.getDate();
                        var bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'][dateObj.getMonth()];
                        var tahun = dateObj.getFullYear();
                        
                        html += '<li class="list-group-item d-flex justify-content-between align-items-center w-100">';
                        html += '<div class="flex-grow-1">';
                        html += '<h6 class="mb-1"><i class="fas fa-file-alt"></i> ' + item.nama_pengaju + '</h6>';
                        html += '<p class="mb-1"><strong>Tanggal:</strong> ' + tanggal + ' ' + bulan + ' ' + tahun + ' <small class="text-muted">(' + timeAgo + ')</small></p>';
                        html += '<p class="mb-1"><strong>Jenis:</strong> ' + item.form.split('_')[1].charAt(0).toUpperCase() + item.form.split('_')[1].slice(1) + ' | <strong>Kode:</strong> ' + item.kode.toUpperCase() + '</p>';
                        html += '</div>';
                        html += '<a href="' + item.link + '" class="btn btn-primary btn-sm ml-3">Lihat Detail</a>';
                        html += '</li>';
                    });
                    html += '</ul>';
                } else {
                    html += '<div class="alert alert-info">Tidak ada data yang belum di-approve.</div>';
                }
                // Hide loading spinner and show content
                $('#' + modalId + ' .notification-loading').hide();
                $('#' + modalId + ' .notification-content').html(html);
            },
            error: function() {
                // Hide loading spinner and show error message
                $('#' + modalId + ' .notification-loading').hide();
                $('#' + modalId + ' .notification-content').html('<p>Error loading data.</p>');
            }
        });
    }
});
</script>

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
        position: relative;
    }

    .card img {
        max-width: 80%;
        /* Image scaling */
        height: auto;
        border-radius: 10px;
    }

    /* Bell shake animation */
    .bell-shake {
        animation: bell-shake 1s ease-in-out infinite;
    }

    @keyframes bell-shake {
        0%, 100% { transform: rotate(0deg); }
        10%, 30%, 50%, 70%, 90% { transform: rotate(-10deg); }
        20%, 40%, 60%, 80% { transform: rotate(10deg); }
    }
</style>