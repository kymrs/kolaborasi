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
                <div class="sidebar-brand-text mx-3">Dashboard</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading Menu -->
            <div class="sidebar-heading">
                Menu
            </div>

            <?php
            $idlevel  = $this->session->userdata('id_level');
            $menu = $this->db->select('b.nama_menu,b.icon,b.link,b.id_menu');
            $menu = $this->db->join('tbl_menu b', 'a.id_menu=b.id_menu');
            $menu = $this->db->join('tbl_userlevel c', 'a.id_level=c.id_level');
            $menu = $this->db->where('a.id_level', $idlevel);
            $menu = $this->db->where('a.view_level', 'Y');
            $menu = $this->db->where('b.is_active', 'Y');
            $menu = $this->db->order_by('b.urutan ASC');
            $menu = $this->db->get('tbl_akses_menu a');
            foreach ($menu->result() as $parent) {
                $sub = $this->db->join('tbl_submenu b', 'a.id_submenu=b.id_submenu');
                $sub = $this->db->where('a.id_level', $idlevel);
                $sub = $this->db->where('b.id_menu', $parent->id_menu);
                $sub = $this->db->where('a.view_level', 'Y');
                $sub = $this->db->where('b.is_active', 'Y');
                $sub = $this->db->order_by('b.id_submenu', 'ASC');
                $sub = $this->db->get('tbl_akses_submenu a');

                if ($sub->num_rows() > 0) {
            ?>
                    <li class="nav-item">
                        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#<?= $parent->nama_menu ?>" aria-expanded="true" aria-controls="collapseUtilities">
                            <i class="<?= $parent->icon ?>"></i>
                            <span><?= $parent->nama_menu ?></span>
                        </a>
                        <div id="<?= $parent->nama_menu ?>" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                            <div class="bg-white py-2 collapse-inner rounded">
                                <?php foreach ($sub->result() as $child) {  ?>
                                    <a class="collapse-item" href="<?= base_url() . $child->link ?>">
                                        <?= $child->nama_submenu ?>
                                        <span id="<?= $child->link ?>-notif" style="display:none; width: 17px; height: 17px; border-radius: 15px; background-color: red; color: white; font-size: 10px; display: inline-block; text-align: center; position: relative; bottom: 2px; line-height: 17px"></span>
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                    </li>
                <?php   } else { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url() . $parent->link ?>">
                            <i class="<?= $parent->icon ?>"></i>
                            <span><?= $parent->nama_menu ?></span></a>
                    </li>
                <?php } ?>
            <?php } ?>

            <hr class="sidebar-divider d-none d-md-block">

            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>