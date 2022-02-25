<aside class="main-sidebar sidebar-light-primary elevation-4">
  <a href="../../index3.html" class="brand-link">
    <img src="<?php echo base_url('assets/AdminLTE-3.1.0/dist/img/AdminLTELogo.png')?>" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">AdminLTE 3</span>
  </a>

  <div class="sidebar">
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="<?php echo base_url('assets/AdminLTE-3.1.0/dist/img/user2-160x160.jpg')?>" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block">Alexander Pierce</a>
      </div>
    </div>
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-th"></i>
            <p>
              Dashboard
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?php echo base_url('test_main/c_main')?>" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Dashboard v1</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="<?php echo base_url('satuan/c_satuan')?>" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Satuan</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="<?php echo base_url('rak/c_rak')?>" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Rak</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="<?php echo base_url('jenisbarang/c_jenisbarang')?>" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Jenis Barang</p>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-item">
          <a href="<?php echo base_url('IndexLog/logout_proses')?>" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>Log Out</p>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</aside>