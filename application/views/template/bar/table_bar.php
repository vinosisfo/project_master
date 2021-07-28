<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">DataTable with default features</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="tb_data_list" class="table table-bordered table-striped table-condensed">
                  <thead>
                    <tr>
                      <th></th>
                      <th>Id User</th>
                      <th>Username</th>
                      <th>Password</th>
                      <th>Aktif</th>
                      <th>Aktif</th>
                      <?php for ($i=1; $i <=15 ; $i++) { ?>
                        <th>Aktif</th>
                      <?php } ?>
                    </tr>
                  </thead>
                  <tbody></tbody>
                  
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

  <div class="modal fade" class="modal-default" id="modal-default">
    <div class="modal-dialog modal-dialog_df modal-xl">
      <div class="modal-content modal-content_df">
        <div class="modal-header">
          <h4 class="modal-title">Default Modal</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>One fine body&hellip;</p>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <script type="text/javascript">
    function test_modal(){
      $("#modal-default").modal("show")
    }
    var table;
    table = $('#tb_data_list').DataTable({ 
      "processing"  : true,
      "serverSide"  : true,
      // 'responsive'  : true,
      'ordering'    : false,
      'lengthChange': false,
      "scrollY"     : 300,
      "scrollX"     : true,
      'sDom'        : 'lrtip',
      language      : {
          processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>'
      },
      "order"       : [],
      "ajax"        : {
          "url" : "<?php echo base_url('welcome/get_data')?>",
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