<style>
    .tableFixHead2 { overflow-y: auto; height: 250px; width : 600px; }
</style>
<form id="form_user" autocomplete="off">
    <table class="customers_ts">
        <tr>
            <td>Nip</td>
            <td>:</td>
            <td>
                <select name="id_user" id="id_user" style="width : 400px;" class="select2_new" onchange="get_detail_user(this)">
                    <option value="">PILIH</option>
                    <?php foreach ($data_user->result() as $usr) { ?>
                        <option value="<?php echo $usr->HrdMsKaryawan_Id ?>"><?php echo $usr->HrdMsKaryawan_Id." - ".$usr->HrdMsKaryawan_Nama." (".$usr->NamaDepartemen.")" ?></option>
                    <?php } ?>
                </select>
                
            </td>
        </tr>
        <tr>
            <td>Nama</td>
            <td>:</td>
            <td><span id="nama_user"></span></td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>:</td>
            <td><span id="jabatan"></span></td>
        </tr>
        <tr>
            <td>Departemen (Bagian)</td>
            <td>:</td>
            <td><span id="dept_bagian"></span></td>
        </tr>
    </table>
    <br>
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
                $no++; ?>
                <tr>
                    <td><?php echo $no ?></td>
                    <td>
                        <input type="checkbox" name="pilih_menu[]" id="pilih_menu_<?php echo $no ?>" style="transform: scale(1.5);" onclick="set_pilih('<?php echo $no ?>')">
                        <label for="pilih_menu_<?php echo $no ?>" style="width: 99%;"></label>
                        <input  type="hidden" name="pilih_menu_set[]" id="pilih_menu_fix_<?php echo $no ?>" readonly>
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
    function get_detail_user(){
        id_user = $("#id_user").val();
        $.post("<?php echo base_url()?>user/c_user/get_user_detail",{id_user : id_user},function(data){
            $("#nama_user").html(data.nama)
            $("#jabatan").html(data.jabatan)
            $("#dept_bagian").html(data.dept+" ("+data.bagian+")");
        },"json")
    }


    $(function () {
		$(".select2_new").select2({
			allowClear       : true,
			placeholder      : 'Pilih',
			dropdownAutoWidth: true,
		});
    })

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
        id_user = $("#id_user").val()
        if(id_user==""){
            error_msg("Nip Tidak Boleh Kosong");
            return false;
        }

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
        $.post("<?php echo base_url()?>user/c_user/simpan_data",$("#form_user").serialize(),function(data){
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