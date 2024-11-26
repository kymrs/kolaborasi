<div id="content-wrapper" class="d-flex flex-column">
  <div id="content">
    <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
      <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
      </button>
      <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown no-arrow">
          <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $this->session->userdata('fullname') ?></span>
            <img class="img-profile rounded-circle" src="<?= ($this->session->userdata('image') != '' ? base_url('assets/backend/img/user/') . $this->session->userdata('image') : base_url('assets/backend/img/') . 'default.jpg'); ?>" class="img-circle elevation-2" alt="User Image">
          </a>
          <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
              <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
              Logout
            </a>
            <a class="dropdown-item" target="_blank" href="<?= base_url() ?>assets/backend/document/tata_cara_approval.pdf">
              <i class="fas fa-file-pdf fa-sm fa-fw mr-2 text-gray-400"></i>
              Tatacara
            </a>
          </div>
        </li>
      </ul>
    </nav>