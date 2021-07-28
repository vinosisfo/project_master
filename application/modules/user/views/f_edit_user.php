<style>
    .tableFixHead2 { overflow-y: auto; height: 250px; width : 600px; }
</style>
<form id="form_user" autocomplete="off">
    <?php $head = $list_head->row(); ?>
    <table class="customers_ts">
        <tr>
            <td>Nip</td>
            <td>:</td>
            <td>
                <input value="<?php echo $head->HrdMsKaryawan_Id ?>" type="text" name="id_user" id="id_user" class="transparant" readonly>
            </td>
        </tr>
        <tr>
            <td>Nama</td>
            <td>:</td>
            <td><?php echo $head->HrdMsKaryawan_Nama ?></td>
        </tr>
    </table>
    <div class="tableFixHead2">
        <table class="customers">
            <thead>
                <th style="width: 20px;">No</th>
                <th style="width: 30px;">Pilih</th>
                <th style="width: 75px;">Id Group</th>
                <th style="width: 200px;">Nama Group</th>
            </thead>

            <?php
            $no=0;
            foreach ($list_menu->result() as $data) {
                $no++;
                $checked     = ($data->MsGroupId==$data->id_group_user) ? "checked" : "";
                $checked_val = ($data->MsGroupId==$data->id_group_user) ? ($data->MsGroupId) : ""; ?>
                <tr>
                    <td><?php echo $no ?></td>
                    <td>
                        <input <?php echo $checked ?> type="checkbox" name="pilih_menu[]" id="pilih_menu_<?php echo $no ?>" style="transform: scale(1.5);" onclick="set_pilih('<?php echo $no ?>')">
                        <label for="pilih_menu_<?php echo $no ?>" style="width: 99%;"></label>
                        <input value="<?php echo $checked_val ?>" type="hidden" name="pilih_menu_set[]" id="pilih_menu_fix_<?php echo $no ?>" readonly>
                    </td>
                    <td><input style="width: 75px;" type="text" name="id_group[]" id="id_group_<?php echo $no ?>" class="transparant" readonly value="<?php echo $data->MsGroupId ?>"></td>
                    <td><?php echo $data->MsGroupNama ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</form>
<table class="customers_ts">
    <tr>
        <td colspan="2" style="text-align: right;">
            <button type="button" id="btn_simpan" class="btn btn-primary btn-sm" onclick="simpan_data_next(this)">Simpan</button>
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
        </td>
    </tr>
</table>

<script>

    var $th = $('.tableFixHead2').find('thead th')
    $('.tableFixHead2').on('scroll', function() {
        $th.css('transform', 'translateY('+ this.scrollTop +'px)');
    });

    function set_pilih(urut){
        if ($('#pilih_menu_'+urut).is(':checked')) {
            $("#pilih_menu_fix_"+urut).val($("#id_group_"+urut).val())
        } else {
            $("#pilih_menu_fix_"+urut).val("")
        }
    }

    function simpan_data_next(){
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
			error_msg("Silahkan Ceklis Group Minimal 1")
            $("#pilih_menu_1").focus();
			return false;
		}

        $("#btn_simpan").prop("disabled",true)
        $.post("<?php echo base_url()?>user/c_user/update_data",$("#form_user").serialize(),function(data){
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
</script>