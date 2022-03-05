<div class="table-responsive">
  <table class="customers" style="table-layout : auto; white-space: nowrap;">
    <tr>
      <td>Data Barang</td>
      <td>
        <button class="btn btn-primary btn-xs" type="button" onclick="input_data(this)">Input</button>
      </td>
    </tr>
  </table>
  <table class="customers" style="table-layout : auto;white-space:nowrap;">
    <tr>
      <td>Nama</td>
      <td>:</td>
      <td>
        <input type="text" name="nama_src" id="nama_src">
      </td>

      <td>Jenis</td>
      <td>:</td>
      <td>
        <input type="text" name="jenis_src" id="jenis_src">
      </td>

      <td>Manufacture</td>
      <td>:</td>
      <td>
        <input type="text" name="manuf_src" id="manuf_src">
      </td>

      <td>Asal</td>
      <td>:</td>
      <td>
        <input type="text" name="asal_src" id="asal_src" style="width: 60px;">
      </td>

      <td>Aktif</td>
      <td>:</td>
      <td>
        <select name="status_aktif_src" id="status_aktif_src">
          <option value="">ALL</option>
          <option value="1">Ya</option>
          <option value="0">Tdk</option>
        </select>
      </td>
      <td>
        <button class="btn btn-success btn-xs" type="button" onclick="cari_data(this)">Cari</button>
      </td>
    </tr>
  </table>
  <table id="tb_data_list" class="customers_border" style="min-width : 90%;">
    <thead>
        <tr>
          <th style="width: 10px;"></th>
          <th style="width: 10px;"></th>
          <th>Nama</th>
          <th>Satuan Besar</th>
          <th>Satuan Kecil</th>
          <th>Jenis</th>
          <th>Asal</th>
          <th>Rak</th>
          <th>Harga</th>
          <th>Stok Min</th>
          <th>manufacture</th>
          <th>Aktif</th>
        </tr>
    </thead>
    <tbody></tbody>
  </table>
</div>

<script type="text/javascript">

    function input_data(){
      $.post("<?php echo base_url('barang/c_barang/input_data')?>",{inpt : "input"},function(data){
        $("#modal-default").modal("show")
        $("#data_detail").html(data)
      })
    }

    function edit_data(kode){
      $.post("<?php echo base_url('barang/c_barang/edit_data')?>",{kode : kode},function(data){
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
          "url" : "<?php echo base_url('barang/c_barang/get_data')?>",
          "type": "POST",
          "data": function ( data ) {
              data.nama_src         = $('#nama_src').val()
              data.jenis_src        = $('#jenis_src').val()
              data.manuf_src        = $('#manuf_src').val()
              data.asal_src         = $('#asal_src').val()
              data.status_aktif_src = $('#status_aktif_src').val()
          }
      },
      fixedColumns: true,
      select      : true,
    });

    function cari_data(){
      table.ajax.reload();
    }

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