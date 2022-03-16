<style>
    .tableFixHead { overflow-y: auto; height: 280px; width : 95%; }
</style>
Edit Group Menu
<form id="form_data" autocomplete="off">
    <?php $head = $list->row(); ?>
    <div style="margin: 2px;">
        <table class="customers" style="table-layout:auto;">
            <thead>
                <tr>
                    <td>Nama Group</td>
                    <td>:</td>
                    <td>
                        <input type="hidden" name="kode_group" id="kode_group" readonly value="<?php echo $head->KodeGroupUser ?>">
                        <input value="<?php echo $head->NamaGroupUser ?>" type="text" name="nama_group" id="nama_group" maxlength="200" style="width : 98%;">
                    </td>
                </tr>
            </thead>
        </table>
        <br>
        <div class="tableFixHead">
            <table class="customers_border">
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                        <th>Group Menu</th>
                        <th>Sub Menu</th>
                        <th>Nama Menu</th>
                    </tr>
                </thead>
                <?php
                $no=0;
                foreach ($menu->result() as $data) {
                    $no++;
                    $check       = (strlen($data->CEK)>8) ? "checked" : "";
                    $statuscheck = (strlen($data->CEK)>8) ? "simpan" : ""; ?>
                    <tr>
                        <td><?php echo $no ?></td>
                        <td>
                            <input <?php echo $check ?> type="checkbox" name="cek_menu[]" id="cek_menu_<?php echo $no ?>" onclick="set_pilih('<?php echo $no ?>')">
                            <input value="<?php echo $statuscheck ?>" type="hidden" name="status_cek[]" id="status_cek_<?php echo $no ?>" readonly>
                            <input value="<?php echo $data->kode_menu_group ?>" type="hidden" name="kode_menu_group[]" id="kode_menu_group_<?php echo $no ?>" readonly>
                            <input value="<?php echo $data->kode_menu_sub ?>" type="hidden" name="kode_menu_sub[]" id="kode_menu_sub_<?php echo $no ?>" readonly>
                            <input value="<?php echo $data->KodeMenu ?>" type="hidden" name="KodeMenu[]" id="KodeMenu_<?php echo $no ?>" readonly>
                        </td>
                        <td><?php echo $data->group_menu ?></td>
                        <td><?php echo $data->sub_menu ?></td>
                        <td><?php echo $data->NamaMenu ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
    <div>
        <button class="btn btn-primary btn-xs" id="btn_simpan" type="button" onclick="simpan_data(this)">Simpan</button>
        <button class="btn btn-danger btn-xs" type="button" onclick="close_modal(this)">Close</button>
    </div>

</form>

<script>
    function set_pilih(urut){
        if ($('#cek_menu_'+urut).is(':checked')) {
            $("#status_cek_"+urut).val("simpan")
        } else {
            $("#status_cek_"+urut).val("")
        }
    }

    function cek_data_group(data){
        nama_group  = $("#nama_group").val()
        $.post("<?php echo base_url('groupuser/c_groupuser/cek_data_group')?>",$("#form_data").serialize(),function(data){
            if(data.hasil=="ada"){
                error_msg("Duplikat Data, Nama Group "+nama_group+" Data Sudah Ada.\nSilahkan Isi Dengan Data Lain");
                $("#nama_group").focus()
                return false;
            } else if(data.hasil=="ok"){
                simpan_data()
            }
        },"json")
    }
    
    function simpan_data(){
        cek        = document.getElementsByName('cek_menu[]');
        hasChecked = false;
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
            $("#cek_menu_1").focus();
			return false;
		}

        $("#btn_simpan").prop("disabled",true)
        $.post("<?php echo base_url('groupuser/c_groupuser/update_data')?>",$("#form_data").serialize(),function(data){
            if(data.pesan=="ok"){
                table.ajax.reload();
                succes_msg("Data Berhasil Disimpan")
                close_modal()
            } 
        },"json").fail(function(data){
            error_msg("Error Hubungi IT")
            $("#btn_simpan").prop("disabled",false)
            return false
        })
    }

    var $th = $('.tableFixHead').find('thead th')
    $('.tableFixHead').on('scroll', function() {
        $th.css('transform', 'translateY('+ this.scrollTop +'px)');
    });
</script>