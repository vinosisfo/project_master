<!-- jQuery -->
<script src="<?php echo base_url('assets/AdminLTE-3.1.0/plugins/jquery/jquery.min.js')?>"></script>
<script src="<?php echo base_url('assets/AdminLTE-3.1.0/plugins/jquery-ui/jquery-ui.min.js')?>"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url('assets/AdminLTE-3.1.0/plugins/bootstrap/js/bootstrap.bundle.min.js')?>"></script>
<!-- DataTables  & Plugins -->
<script src="<?php echo base_url('assets/AdminLTE-3.1.0/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo base_url('assets/AdminLTE-3.1.0/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')?>"></script>
<script src="<?php echo base_url('assets/AdminLTE-3.1.0/plugins/datatables-responsive/js/dataTables.responsive.min.js')?>"></script>
<script src="<?php echo base_url('assets/AdminLTE-3.1.0/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')?>"></script>
<script src="<?php echo base_url('assets/AdminLTE-3.1.0/plugins/datatables-buttons/js/dataTables.buttons.min.js')?>"></script>
<script src="<?php echo base_url('assets/AdminLTE-3.1.0/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')?>"></script>
<script src="<?php echo base_url('assets/AdminLTE-3.1.0/plugins/jszip/jszip.min.js')?>"></script>
<script src="<?php echo base_url('assets/AdminLTE-3.1.0/plugins/pdfmake/pdfmake.min.js')?>"></script>
<script src="<?php echo base_url('assets/AdminLTE-3.1.0/plugins/pdfmake/vfs_fonts.js')?>"></script>
<script src="<?php echo base_url('assets/AdminLTE-3.1.0/plugins/datatables-buttons/js/buttons.html5.min.js')?>"></script>
<script src="<?php echo base_url('assets/AdminLTE-3.1.0/plugins/datatables-buttons/js/buttons.print.min.js')?>"></script>
<script src="<?php echo base_url('assets/AdminLTE-3.1.0/plugins/datatables-buttons/js/buttons.colVis.min.js')?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url('assets/AdminLTE-3.1.0/dist/js/adminlte.min.js')?>"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url('assets/AdminLTE-3.1.0/dist/js/demo.j')?>s"></script>
<script src="<?php echo base_url('assets/AdminLTE-3.1.0/plugins/sweetalert2/sweetalert2.min.js')?>"></script>
<script src="<?php echo base_url('assets/AdminLTE-3.1.0/plugins/toastr/toastr.min.js') ?>"></script>
<script src="<?php echo base_url('assets/izitoast/dist/js/iziToast.min.js')?>"></script>
<!-- Page specific script -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
  
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons"   : ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    
    $('#example2').DataTable({
      "paging"      : true,
      "lengthChange": false,
      "searching"   : false,
      "ordering"    : true,
      "info"        : true,
      "autoWidth"   : false,
      "responsive"  : true,
    });
  });


  function error_msg(pesan){
		iziToast.error({
			title   : 'Error !!',
			message : pesan,
			position: 'topCenter'
		});
	}

  function hapus_msg(pesan){
		iziToast.error({
			title   : 'Berhasil !!',
			message : pesan,
			position: 'topCenter'
		});
	}

	function succes_msg(pesan)
	{
		iziToast.success({
			title   : 'Berhasil!',
			message : pesan,
			position: 'topCenter'
		});
	}

	function info_msg(pesan)
	{
		iziToast.info({
			title   : 'info!',
			message : pesan,
			position: 'topCenter'
		});
	}
</script>