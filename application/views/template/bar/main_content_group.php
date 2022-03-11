<?php
$this->load->view('template/css/css');
$this->load->view('template/bar/nav_bar');
$this->load->view('template/bar/menu');
$this->load->view('template/js/js'); ?>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <!-- <div class="card-header">
                            <h3 class="card-title"><?php //echo $nama_menu ?></h3>
                        </div> -->
                        <div class="card-body">
                            <div class="body">
                                <div class="row">
                                <?php foreach ($list->result() as $data) { ?>
                                    <div class="col-md-2 col-xs-4 col-sm-4" style="text-align: center;">
                                        <a href="<?php echo base_url(''.$data->UrlMenu.'')?>" style="color:black;">
                                            <div class="img-reponsive" style="display: flex;justify-content: center;">
                                                <img style="width: 40px;" class="img-responsive" src="<?php echo base_url()?>assets/img/menu/<?php echo $data->KodeMenu.".png" ?>" alt="">
                                            </div>
                                            <label style="text-align: center;font-weight : normal;font-size:12px;"><?php echo $data->NamaMenu ?></label>
                                        </a>
                                    </div>
                                <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<?php 
// $this->load->view('template/bar/footer');
?>