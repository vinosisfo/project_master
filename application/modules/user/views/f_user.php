<style>
    /* .tableFixHead2 { overflow-y: auto; height: 250px; width : 1180px; } */
    th, td { white-space: nowrap; }
    table.table tr th{
        background-color: #4CAF50 !important;
        color           : white !important;
    }

    .table-condensed>thead>tr>th, .table-condensed>tbody>tr>th, .table-condensed>tfoot>tr>th, .table-condensed>thead>tr>td, .table-condensed>tbody>tr>td, .table-condensed>tfoot>tr>td{
        padding  : 1px;
        font-size: 12px;
    }
</style>
<section class="content">
  <div class="container-fluid">
    <div class="row clearfix">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
          <div class="header">
            <button class="btn btn-rounded btn-primary btn-sm">Group User</button>
            <button class="btn btn-rounded btn-danger btn-sm" onclick="add_data(this)">Input Data</button>
          </div>
          <div class="body">
            <div class="table-responsive">
              <form id="form_cari" autocomplete="off">
              <table class="customers_ts">
                  <tr>
                    <td>Id / Nama</td>
                    <td>:</td>
                    <td>
                        <input type="text" name="id_nama" id="id_nama" style="width: 100px;">
                    </td>
                    <td style="width: 100px;">
                      <button type="button" class="btn btn-primary btn-sm" id='btn-cari' value="cari">Cari</button>
                    </td>
                  </tr>
                </table>
              </form>
              <table class="table table-bordered table-condensed" id="tb_data_list">
                <thead>
                  <tr>
                    <th >No</th>
                    <th></th>
                    <!-- <th></th> -->
                    <th >Nip</th>
                    <th >Nama</th>
                    <th>Departemen</th>
                    <th>Bagian</th>
                    <th>Jabatan</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php
$agent = $_SERVER['HTTP_USER_AGENT'];
if(preg_match('/iPhone|Android|Blackberry/i', $agent)){
  $width_set = "";
} else{
  $width_set = 'style="min-width: 800px;"';
}
?>
<div class="modal fade" id="modal_master" data-keyboard="false" data-backdrop="static"> 
  <div class="modal-dialog">
    <div class="modal-content" <?php echo $width_set ?>>
      <div class="modal-header">
        <h4 class="modal-title judul"></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <div id="form_data"></div>
      </div>
      <div class="modal-footer"></div>
    </div>
  </div>
</div>

<script>
  function add_data()
  {
    $.post("<?php echo base_url('user/c_user/get_form_input')?>",{"format" : "input"}, function(data){
      $(".judul").html("Input User");
      $("#form_data").html(data);
      $("#modal_master").modal("show");
    }) 
  }

  function edit_data(id_user)
  {
    $.post("<?php echo base_url('user/c_user/get_form_edit')?>",{id_user : id_user}, function(data){
      $(".judul").html("Edit User");
      $("#form_data").html(data);
      $("#modal_master").modal("show");
    }) 
  }

  function close_modal(){
    $("#modal_master").modal("hide");
  }

  function edit_email(periode='',nip)
  {
    $.post("<?php echo base_url('list_gaji/c_list_gaji/edit_email')?>",{periode : periode, nip : nip}, function(data){
      $(".judul").html("Edit Email ");
      $("#form_data").html(data);
      $("#modal_master").modal("show");
    }) 
  }


  var table;
  table = $('#tb_data_list').DataTable({ 
    "processing"  : true,
    "serverSide"  : true,
    'responsive'  : true,
    'ordering'    : false,
    'lengthChange': false,
    "scrollY"     : 250,
    "scrollX"     : true,
    'sDom'        : '"top"i',
    "order"       : [],
    "ajax"        : {
      "url" : "<?php echo base_url('user/c_user/get_data')?>",
      "type": "POST",
      "data": function ( data ) {
                data.id_nama     = $('#id_nama').val();
          }
    },
    fixedColumns: true,
    select      : true,
  });

  $('#btn-cari').click(function(){ //button filter event click
    table.ajax.reload();  //just reload table
  });
  $('#btn-reset').click(function(){ //button reset event click
    $('#form-cari')[0].reset();
    table.ajax.reload();  //just reload table
  });

  $("#tb_data_list_filter").css("display","none");

  $(".periode").datepicker({
        autoclose  : true,
        format     : 'yyyy-mm-01',
        changeMonth: true,
        changeYear : true,
    });

</script>

