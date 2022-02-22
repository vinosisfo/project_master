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
                            <h3 class="card-title">DataTable with default features</h3>
                        </div> -->
                        <div class="card-body">
                            <?php $this->load->view($main_content); ?>
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

<div class="modal fade" class="modal-default" id="modal-default" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog_df modal-xl">
        <div class="modal-content modal-content_df">
            <div class="modal-header">
                <h4 class="modal-title">Default Modal</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="data_detail"></div>
            </div>
        </div>
    </div>
</div>

<script>
    function close_modal(){
        $("#modal-default").modal("hide")
    }
</script>