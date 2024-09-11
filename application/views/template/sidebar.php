<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('dashboard') ?>">
                <div class="sidebar-brand-icon rotate-n-15">
                    <!-- <i class="fas fa-laugh-wink"></i> -->
                </div>
                <div class="sidebar-brand-text mx-3">Sebelas Warna</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="<?= base_url('dashboard') ?>">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Menu
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePagesSW" aria-expanded="true" aria-controls="collapsePagesSW">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Data SW</span>
                </a>
                <div id="collapsePagesSW" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="<?= base_url('datadeklarasi_sw') ?>">Deklarasi SW</a>
                        <a class="collapse-item" href="<?= base_url('datanotifikasi_sw') ?>">Notifikasi SW</a>
                        <a class="collapse-item" href="<?= base_url('prepayment_sw') ?>">Prepayment SW</a>
                        <a class="collapse-item" href="<?= base_url('reimbust_sw') ?>">Reimbust SW</a>
                    </div>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePagesPU" aria-expanded="true" aria-controls="collapsePagesPU">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Data PU</span>
                </a>
                <div id="collapsePagesPU" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="<?= base_url('datadeklarasi_pu') ?>">Deklarasi PU</a>
                        <a class="collapse-item" href="<?= base_url('datanotifikasi_pu') ?>">Notifikasi PU</a>
                        <a class="collapse-item" href="<?= base_url('prepayment_pu') ?>">Prepayment PU</a>
                        <a class="collapse-item" href="<?= base_url('reimbust_pu') ?>">Reimbust PU</a>
                    </div>
                </div>
            </li>
        </ul>