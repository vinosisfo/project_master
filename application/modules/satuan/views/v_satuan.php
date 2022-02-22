<table class="table table-bordered table-striped table-condensed" style="padding-bottom: 2px;">
  <tr>
    <td>Satuan</td>
    <td>
      <button class="btn btn-primary btn-xs" type="button" onclick="input_data(this)">Input</button>
    </td>
  </tr>
</table>
<table id="tb_data_list" class="table table-bordered table-striped table-condensed">
  <thead>
      <tr>
        <th></th>
        <th></th>
        <th>Nama Satuan</th>
        <th>Aktif</th>
      </tr>
  </thead>
  <tbody></tbody>
</table>

<script type="text/javascript">

    function input_data(){
      $.post("<?php echo base_url('satuan/c_satuan/input_data')?>",{inpt : "input"},function(data){
        $("#modal-default").modal("show")
        $("#data_detail").html(data)
      })
    }

    function test_modal(){
      $("#modal-default").modal("show")
    }
    var table;
    table = $('#tb_data_list').DataTable({ 
      "processing"  : true,
      "serverSide"  : true,
      'ordering'    : false,
      'lengthChange': false,
      "scrollY"     : 250,
      "scrollX"     : true,
      'sDom'        : 'lrtip',
      language      : {
          processing    : '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>',
          "infoFiltered": ""
      },
      "order"       : [],
      "ajax"        : {
          "url" : "<?php echo base_url('satuan/c_satuan/get_data')?>",
          "type": "POST",
          "data": function ( data ) {
              data.departemen_src = $('#departemen_src').val()
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

    $('#tb_data_list tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    } );

    $('#button').click( function () {
        table.row('.selected').remove().draw( false );
    } );
  </script>