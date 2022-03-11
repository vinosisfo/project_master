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
<!-- Select2 -->
<script src="<?php echo base_url('assets/AdminLTE-3.1.0/plugins/select2/js/select2.full.min.js')?>"></script>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
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

		//Initialize Select2 Elements
		$('.select2').select2()

		//Initialize Select2 Elements
		$('.select2bs4').select2({
			theme: 'bootstrap4'
		})
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

	function hanya_angka(data,urut='')
	{
		var isi   = data.value;
		var isi2  = $(this);
		let hasil = format_number(isi);
		$(data).val(hasil);
		console.log(isi);
    }

	function format_number(number, prefix, thousand_separator, decimal_separator)
    {
        var thousand_separator = thousand_separator || ',',
            decimal_separator  = decimal_separator || '.',
            regex              = new RegExp('[^' + decimal_separator + '\\d]', 'g'),
            number_string      = number.replace(regex, '').toString(),
            split              = number_string.split(decimal_separator),
            rest               = split[0].length % 3,
            result             = split[0].substr(0, rest),
            thousands          = split[0].substr(rest).match(/\d{3}/g);
        
        if (thousands) {
            separator  = rest ? thousand_separator : '';
            result    += separator + thousands.join(thousand_separator);
        }
        result = split[1] != undefined ? result + decimal_separator + split[1] : result;
        return prefix == undefined ? result : (result ?  result  + prefix: '');
    };
</script>