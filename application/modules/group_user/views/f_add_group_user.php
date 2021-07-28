<style>
    .tableFixHead2 { overflow-y: auto; height: 250px; width : 600px; }
</style>
<form id="form_group" autocomplete="off">
    <div class="table-responsive">
        <table class="table table-striped">
            <tr>
                <td style="width: 10% !important;">Id Group</td>
                <td style="width: 2% !important; ">:</td>
                <td>
                    <input type="text" name="max_id" id="max_id" class="transparant" readonly value="<?php echo $max_id->row()->IDGROUP ?>">
                </td>
            </tr>
            <tr>
                <td>Nama Group</td>
                <td>:</td>
                <td>
                    <input type="text" name="nama_group" id="nama_group" maxlength="200" style="max-width: 400px; text-transform:uppercase;">
                </td>
            </tr>
        </table>
        
        <div class="tableFixHead2">
            <table class="customers">
                <thead>
                    <th style="width: 20px;">No</th>
                    <th style="width: 30px;">Pilih</th>
                    <th style="width: 75px;">Id Menu</th>
                    <th style="width: 200px;">Group Menu</th>
                    <th style="width: 200px;">Nama Menu</th>
                    <th style="width: 200px;">Deskripsi</th>
                </thead>

                <?php
                $no=0;
                foreach ($list_menu->result() as $data) {
                    $no++; ?>
                    <tr>
                        <td><?php echo $no ?></td>
                        <td>
                            <input type="checkbox" name="pilih_menu[]" id="pilih_menu_<?php echo $no ?>" style="transform: scale(1.5);" onclick="set_pilih('<?php echo $no ?>')">
                            <label for="pilih_menu_<?php echo $no ?>" style="width: 99%;"></label>
                            <input type="hidden" name="pilih_menu_set[]" id="pilih_menu_fix_<?php echo $no ?>" readonly>
                        </td>
                        <td><input style="width: 75px;" type="text" name="id_menu[]" id="id_menu_<?php echo $no ?>" class="transparant" readonly value="<?php echo $data->MsMenu_ID ?>"></td>
                        <td><?php echo $data->group_menu ?></td>
                        <td><?php echo $data->nama_menu ?></td>
                        <td><?php echo $data->deskripsi_menu ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
    <table class="customers_ts">
        <tr>
            <td><button class="btn btn-primary btn-sm" id="btn_simpan" type="button" onclick="simpan_data(this);">Simpan</button></td>
            <td><button class="btn btn-danger btn-sm" type="button" onclick="close_modal();">Close</button></td>
        </tr>
    </table>

</form>


<script>
    function simpan_data(){
        nama_group = $("#nama_group").val();
        if(nama_group==""){
            error_msg("Nama Group Tidak Boleh Kosong");
            $("#nama_group").focus()
            return false;
        }

        $.post("<?php echo base_url()?>group_user/c_group_user/cek_nama_group",{nama_group : nama_group},function(data){
            if(data.pesan=="ada"){
                error_msg("Nama Group "+nama_group+" Sudah Ada, Silahkan Input Nama Lain");
                return false;
            } else{
                simpan_data_nex();
            }
        },"json");
        
    }

    function simpan_data_nex(){
        var cek = document.getElementsByName('pilih_menu[]');
		var hasChecked = false;
		for (var i = 0; i < cek.length; i++)
		{
			if (cek[i].checked)
			{
				hasChecked = true;
				break;
			}
		}

		if (hasChecked == false)
		{
			error_msg("Silahkan Ceklis Menu Minimal 1")
            $("#pilih_menu_1").focus();
			return false;
		}

        $("#btn_simpan").prop("disabled",true)
        $.post("<?php echo base_url()?>group_user/c_group_user/simpan_data",$("#form_group").serialize(),function(data){
            if(data.pesan=="ok"){
                table.ajax.reload();
                succes_msg("Data Berhasil Disimpan");
                close_modal();
            } 
        },"json").fail(function(data){
            error_msg("Error Hubungi IT");
            $("#btn_simpan").prop("disabled",false)
            return false;
        });
    }

    function set_pilih(urut){
        if ($('#pilih_menu_'+urut).is(':checked')) {
            $("#pilih_menu_fix_"+urut).val($("#id_menu_"+urut).val())
        } else {
            $("#pilih_menu_fix_"+urut).val("")
        }
    }
    var $th = $('.tableFixHead2').find('thead th')
    $('.tableFixHead2').on('scroll', function() {
        $th.css('transform', 'translateY('+ this.scrollTop +'px)');
    });
</script>