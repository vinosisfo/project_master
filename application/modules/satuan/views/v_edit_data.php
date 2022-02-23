<form id="form_data" autocomplete="off">
    <div style="margin: 2px;">
    <?php $head = $list->row() ?>
        <table class="table table-condensed" style="max-width: 50%;">
            <tr>
                <td>Nama Satuan</td>
                <td>:</td>
                <td>
                    <input type="hidden" name="id_satuan" id="id_satuan" maxlength="100" readonly value="<?php echo $id_satuan ?>">
                    <input type="text" name="nama_satuan" id="nama_satuan" maxlength="100" style="text-transform: uppercase;" value="<?php echo $head->NamaSatuan ?>">
                </td>
            </tr>
            <tr>
                <td>Aktif</td>
                <td>:</td>
                <td>
                    <select name="status_aktif" id="status_aktif">
                        <?php if($head->Aktif==1){ ?>
                            <option value="1">Ya</option>
                            <option value="0">Tdk</option>
                        <?php } else { ?>
                            <option value="0">Tdk</option>
                            <option value="1">Ya</option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
        </table>
    </div>
    <div>
        <button class="btn btn-primary btn-xs" id="btn_simpan" type="button" onclick="simpan_data(this)">Simpan</button>
        <button class="btn btn-danger btn-xs" type="button" onclick="close_modal(this)">Close</button>
    </div>

</form>

<script>
    
    function simpan_data(){
        nama_satuan     = $("#nama_satuan").val()
        nama_satuan_cek = nama_satuan.replace(/\s+/g, '');

        if(nama_satuan_cek==0){
            error_msg("Satuan Tidak Boleh Kosong")
            $("#nama_satuan").focus()
            return false
        }
        $("#btn_simpan").prop("disabled",true)
        $.post("<?php echo base_url('satuan/c_satuan/update_data')?>",$("#form_data").serialize(),function(data){
            if(data.status=="duplikat"){
                error_msg("Nama Satuan "+nama_satuan+" Sudah Ada. Duplikat")
                $("#btn_simpan").prop("disabled",false)
                return false;
            }
            else {
                if(data.pesan=="ok"){
                    table.ajax.reload();
                    succes_msg("Data Berhasil Disimpan")
                    close_modal()
                } else {
                    error_msg("Error Hubungi IT")
                    $("#btn_simpan").prop("disabled",false)
                    return false
                }
            }
        },"json")
    }
</script>