<style>
    .tableFixHead { overflow-y: auto; height: 280px; width : 95%; }
</style>
<form id="form_data" autocomplete="off">
    <div style="margin: 2px;">
        <!-- <button class="btn btn-danger btn-xs" type="button" onclick="tambah_baris(this)">Tambah</button> -->
        <div class="tableFixHead">
            <table class="table table-condensed" style="max-width: 150%;">
                <thead>
                    <tr>
                        <th style="width: 10px;"></th>
                        <th style="width: 20px;"></th>
                        <th>Nama Group</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="row_baru">
                    <?php 
                    $no=0;
                    foreach ($list->result() as $data) {
                        $no++; ?>
                        <tr>
                            <td>
                                <?php echo $no ?>
                                <input type="hidden" name="id_group[]" id="id_group_<?php echo $no ?>" value="<?php echo $data->KodeGroupUser ?>" readonly>
                                <input type="hidden" name="no_urut[]" id="no_urut_<?php echo $no ?>" class="no_urut" value="<?php echo $no ?>" readonly>
                            </td>
                            <td></td>
                            <td>
                                <input value="<?php echo $data->NamaGroupUser ?>" type="text" name="nama_group[]" id="nama_group_<?php echo $no ?>" maxlength="200" style="width : 98%;">
                            </td>
                            <td>
                            <select name="status_aktif" id="status_aktif">
                                <?php if($data->Aktif==1){ ?>
                                    <option value="1">Ya</option>
                                    <option value="0">Tdk</option>
                                <?php } else { ?>
                                    <option value="0">Tdk</option>
                                    <option value="1">Ya</option>
                                <?php } ?>
                            </select>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div>
        <button class="btn btn-primary btn-xs" id="btn_simpan" type="button" onclick="simpan_data_cek(this)">Simpan</button>
        <button class="btn btn-danger btn-xs" type="button" onclick="close_modal(this)">Close</button>
    </div>

</form>

<script>
    function simpan_data_cek(data,urut){
        $.post("<?php echo base_url('groupuser/c_groupuser/cek_data_group')?>",$("#form_data").serialize()+"&jenis_update=update",function(data){
            if(data.hasil=="ada"){
                error_msg("Duplikat Data Pada Baris "+data.nama_group+" Data Sudah Ada.\nSilahkan Isi Dengan Data Lain");
                $("#nama_group_"+urut).val("");
                $("#nama_group_"+urut).focus()
                return false;
            } else if(data.hasil=="ok")
            {
                simpan_data()
            }
        },"json")
    }

    function simpan_data(){
        v_group = document.getElementsByName('nama_group[]');
        for (i=0; i<v_group.length; i++)
        {
            nomor          = parseInt(i)+1;
            nama_group     = $("#nama_group_"+nomor).val()
            nama_group_cek = nama_group.replace(/\s+/g, '');
            if (nama_group_cek == "")
            {
                error_msg("Nama Group Tidak Boleh Kosong")
                $("#nama_group_"+nomor).focus()   
                return false;
            }
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