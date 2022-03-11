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
        <?php
        $menu_head = $this->get_support_lib_new->get_menu_head();
        foreach ($menu_head->result() as $m_head) { 
          $kode_menu = $m_head->KodeMenu;
          $sub_menu  = $m_head->SubMenu;
          ?>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                <?php echo $m_head->NamaMenu ?>
              </p>
            </a>
            <?php
            $sub_menu = $this->get_support_lib_new->get_sub_menu($kode_menu);
            foreach ($sub_menu->result() as $m_sub) {
              $url_menu      = $m_sub->UrlMenu;
              $nama_menu     = $m_sub->NamaMenu;
              $kode_menu_sub = $m_sub->KodeMenu; ?>

              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="<?php echo base_url(''.$url_menu.'')?>/<?php echo $kode_menu_sub ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p><?php echo $nama_menu ?></p>
                  </a>
                </li>
              </ul>
            <?php } ?>
          </li>
        <?php } ?>
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