
    <section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <!-- User Info -->
            <div class="user-info">
                <div class="image">
                    <img src="<?php echo base_url('assets/adminbsb/images/animation-bg.jpg') ?>" width="48" height="48" alt="User" />
                </div>
                <div class="info-container">
                    <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo strtoupper($this->session->userdata("username")) ?></div>
                    <div class="btn-group user-helper-dropdown">
                        <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                        <ul class="dropdown-menu pull-right">
                            <li role="separator" class="divider"></li>
                            <li><a href="<?php echo base_url('admin/logout') ?>"><i class="material-icons">input</i>Sign Out</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- #User Info -->
            <!-- Menu -->

            <div class="menu">
                <ul class="list">
                    <li class="header">MAIN MENU</li>
                    <?php 
                    $this->load->library("get_lib_support");
                    $kode_pegawai  = $this->session->userdata("id_user");
                    $akses_admin   = $this->get_lib_support->akses_admin($kode_pegawai);
                    $where_pegawai = ($akses_admin > 0) ? "" :" AND C.KdPegawai='$kode_pegawai'";

                    $sql_menu_head = $this->db->query("SELECT DISTINCT A.MsMenu_ID,A.MsMenu_Display,A.MsMenu_Path,A.MsMenuNoUrut
                                                        FROM PayrollMnu A 
                                                        LEFT JOIN PayrollMnu_GroupMenu B ON B.MsMenu_ID=A.MsMenu_ID
                                                        LEFT JOIN PayrollMnu_USer C ON C.MsGroupId=B.MsGroupId
                                                        WHERE A.MsMenu_parent1 IS NULL AND A.MsMenuAktif=1
                                                        $where_pegawai
                                                        ORDER BY A.MsMenuNoUrut");
                    foreach ($sql_menu_head->result() as $head_menu) {
                        $id_menu_hasil_head = $this->session->userdata("id_menu_head_ses");
                        $active_set_head         = ($head_menu->MsMenu_ID==$id_menu_hasil_head) ? 'class="active"' : ""; ?>
                    <li <?php echo $active_set_head ?>>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">view_list</i>
                            <span><?php echo $head_menu->MsMenu_Display ?></span>
                        </a>
                        <ul class="ml-menu">
                            <?php
                            $sql_menu_parent1 = $this->db->query("SELECT DISTINCT A.MsMenu_ID,A.MsMenu_Display,A.MsMenu_Path,A.MsMenuNoUrut
                                                                    FROM PayrollMnu A 
                                                                    LEFT JOIN PayrollMnu_GroupMenu B ON B.MsMenu_ID=A.MsMenu_ID
                                                                    LEFT JOIN PayrollMnu_USer C ON C.MsGroupId=B.MsGroupId
                                                                    WHERE A.MsMenu_parent1='$head_menu->MsMenu_ID' AND A.MsMenuAktif=1
                                                                    AND A.MsMenu_parent2 IS NULL
                                                                    $where_pegawai
                                                                    ORDER BY A.MsMenuNoUrut");
                            foreach ($sql_menu_parent1->result() as $menu_parent) {
                                $path_menu     = $menu_parent->MsMenu_Path;
                                $id_menu_hasil = $this->session->userdata("id_menu_ses");
                                $active_set    = ($menu_parent->MsMenu_ID==$id_menu_hasil) ? 'class="active"' : ""; ?>

                            <li <?php echo $active_set ?> style="font-weight: normal !important;">
                                <a onclick="set_seasion_menu('<?php echo $menu_parent->MsMenu_ID ?>')" href="<?php echo base_url(''.$path_menu.'')?>"><?php echo $menu_parent->MsMenu_Display ?></a>
                            </li>
                            <?php } ?>
                        </ul>
                    </li>
                    <?php } ?>
                </ul>
            </div>

            <!-- <div class="menu">
                <ul class="list">
                    <li class="header">MAIN MENU</li>
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">view_list</i>
                            <span>Master</span>
                        </a>
                        <ul class="ml-menu">
                            <li class="active">
                                <a href="<?php echo base_url('email')?>">Email Pengumuman</a>
                            </li>
                            <li class="active">
                                <a href="<?php echo base_url('email_slip')?>">Email Slip</a>
                            </li>
                            <li class="active">
                                <a href="<?php echo base_url('email_slip_monitoring')?>">Monitoring Email Slip</a>
                            </li>
                            <li class="active">
                                <a href="<?php echo base_url('periode_gaji/c_periode')?>">Periode Gaji</a>
                            </li>
                            <li class="active">
                                <a href="<?php echo base_url('list_gaji/c_list_gaji')?>">List Gaji Bulanan</a>
                            </li>
                            <li class="active">
                                <a href="<?php echo base_url('master_gaji/c_master_gaji')?>">Master Gaji</a>
                            </li>
                            <li class="active">
                                <a href="<?php echo base_url('gaji_hl/c_gaji_hl')?>">Gaji HL Mingguan</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">view_list</i>
                            <span>Revisi Makan & Absen</span>
                        </a>
                        <ul class="ml-menu">
                            <li class="active">
                                <a href="<?php echo base_url('uang_makan/c_uang_makan')?>">Makan</a>
                            </li>
                            <li class="active">
                                <a href="<?php echo base_url('absen/c_absen')?>">Absen</a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">view_list</i>
                            <span>SETTING</span>
                        </a>
                        <ul class="ml-menu">
                            <li class="active">
                                <a href="<?php echo base_url('user/c_user')?>">User</a>
                            </li>
                            <li class="active">
                                <a href="<?php echo base_url('group_user/c_group_user')?>">Group User</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div> -->
            <!-- #Menu -->
            <!-- Footer -->
            <div class="legal" style="max-height: 30px;">
                <div class="copyright">
                    &copy; 2021
                </div>
            </div>
            <!-- #Footer -->
        </aside>
    </section>

    <script>

        function set_seasion_menu(id_menu){
            $.post("<?php echo base_url('admin/set_seasion_menu')?>",{id_menu : id_menu},function(data){
                console.log(data.id_menu)
                },"json")
        }
        // function kirim_slip_ot(){
        //     $.post("<?php //echo base_url('email_slip/email_slip_otomatis')?>",{oto : "oto"},function(data){
        //         console.log(data)
        //     },"json").fail(function(data){
        //     })
        // }

        // // setTimeout(function(){ kirim_slip_ot() }, 5000);
        // setInterval(function() { kirim_slip_ot() }, 300000);

        function kirim_pengumuman_ot(){
            $.post("<?php echo base_url('email/kirim_email_auto')?>",{oto : "oto"},function(data){
                console.log(data)
            },"json").fail(function(data){
            })
        }

        // setTimeout(function(){ kirim_pengumuman_ot() }, 5000);
        // setInterval(function() { kirim_pengumuman_ot() }, 300000);
    </script>