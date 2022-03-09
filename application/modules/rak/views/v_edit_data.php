<style>
    .tableFixHead { overflow-y: auto; height: 280px; width : 95%; }
</style>
<form id="form_data" autocomplete="off">
    <div style="margin: 2px;">
        <?php $head = $list->row() ?>
        <table class="table table-condensed" style="max-width: 50%;">
            <tr>
                <td>Nama Rak</td>
                <td>:</td>
                <td>
                    <input type="hidden" name="id_rak" id="id_rak" maxlength="100" readonly value="<?php echo $head->idRak ?>">
                    <input type="text" class="transparant" name="nama_rak" id="nama_rak" maxlength="100" style="text-transform: uppercase;" readonly value="<?php echo $head->NamaRak ?>">
                </td>
            </tr>
            <tr>
                <td>Aktif</td>
                <td>:</td>
                <td>
                    <select name="aktif_rak" id="aktif_rak">
                        <?php if($head->Aktif=="1"){ ?>
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
        <button class="btn btn-danger btn-xs" type="button" onclick="tambah_baris(this)">Tambah</button>
        <div class="tableFixHead">
            <table class="table table-condensed" style="max-width: 95%;">
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                        <th>Alias</th>
                        <th>Aktif</th>
                    </tr>
                </thead>
                <tbody id="row_baru">
                    <?php 
                    $no = 0;
                    foreach ($list->result() as $data) {
                        $no++; ?>
                        <tr>
                            <td>
                                <?php echo $no ?>
                                <input type="hidden" name="no_urut[]" id="no_urut_<?php echo $no ?>" class="no_urut" value="<?php echo $no ?>" readonly>
                                <input type="hidden" name="idrak_dt[]" id="idrak_dt_<?php echo $no ?>" class="idrak_dt" value="<?php echo $data->idRakDetail ?>" readonly>
                            </td>
                            <td></td>
                            <td>
                                <input type="text" name="alias_rak[]" id="alias_rak_'+no_akhir_set+'" maxlength="50" style="width : 98%;" value="<?php echo $data->Alias ?>" onblur="cek_data_rak(this,'<?php echo $no ?>')">
                            </td>
                            <td>
                                <select name="aktif_rak_alias[]" id="aktif_rak_alias_<?php echo $no ?>">
                                    <?php if($data->rak_alias_aktif=="1"){ ?>
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
        <button class="btn btn-primary btn-xs" id="btn_simpan" type="button" onclick="simpan_data(this)">Simpan</button>
        <button class="btn btn-danger btn-xs" type="button" onclick="close_modal(this)">Close</button>
    </div>

</form>

<script>
    function cek_data_rak(data,urut){
        idrak    = $("#id_rak").val()
        nama_rak = $("#nama_rak").val()
        alias    = $("#alias_rak_"+urut).val()
        $.post('<?php echo base_url('rak/c_rak/cek_data_detail')?>',$("#form_data").serialize(),function(data){
            if(data.pesan=="ada"){
                error_msg("Alias Tidak Boleh Sama "+alias);
                $("#alias_rak_"+urut).val("")
                return false
            }
        },"json")
    }

    function tambah_baris(urut=''){
        no           = parseInt(urut);                                        //parseInt('<?php //echo $no ?>')
        no_urut      = document.getElementsByClassName("no_urut");
        no_akhir     = (no==1) ? 0 : (no_urut[no_urut.length-1].value);
        no_akhir_set = (parseInt(no_akhir) > 0) ? (parseInt(no_akhir)+1) : (1)

        btn_hapus = (no_akhir_set>1) ? '<button class="btn bg-warning btn-xs" type="button" onclick="hapus_row(this,'+no_akhir_set+')" style="font-size: 10px;">Hapus</button>' : "";
        row_baris = '<tr>'+
                        '<td>'+
                            +no_akhir_set+
                            '<input type="hidden" name="no_urut[]" id="no_urut_'+no_akhir_set+'" class="no_urut" value="'+no_akhir_set+'" readonly>'+
                            '<input type="hidden" name="idrak_dt[]" id="idrak_dt_'+no_akhir_set+'" class="idrak_dt" value="i" readonly>'+
                        '</td>'+
                        '<td>'+btn_hapus+'</td>'+
                        '<td>'+
                            '<input type="text" name="alias_rak[]" id="alias_rak_'+no_akhir_set+'" maxlength="50" style="width : 98%;" onblur="cek_data_rak(this,'+no_akhir_set+')">'+
                        '</td>'+
                        '<td>'+
                            '<select name="aktif_rak_alias[]" id="aktif_rak_alias_'+no_akhir_set+'">'+
                                '<option value="1">Ya</option>'+
                            '</select>'+
                        '</td>'+
                    '</tr>';
        
        $("#row_baru").append(row_baris)
    }

    function hapus_row(data){
        $(data).closest('tr').remove();
		return false;
    }

    

    function simpan_data(){
        nama_rak     = $("#nama_rak").val()
        nama_rak_cek = nama_rak.replace(/\s+/g, '');

        if(nama_rak_cek==0){
            error_msg("Nama Rak Tidak Boleh Kosong")
            $("#nama_rak").focus()
            return false
        }

        v_alias = document.getElementsByName('alias_rak[]');
        for (i=0; i<v_alias.length; i++)
        {
            nomor = parseInt(i)+1;
            if (v_alias[i].value == "")
            {
                error_msg("Alias Rak Tidak Boleh Kosong")
                $("#alias_rak_"+nomor).focus()   
                return false;
            }
        }
        $("#btn_simpan").prop("disabled",true)
        $.post("<?php echo base_url('rak/c_rak/update_data')?>",$("#form_data").serialize(),function(data){
            if(data.status=="duplikat"){
                error_msg("Duplikat Data Rak "+nama_rak+" Sudah Ada. Proses Tidak Dapat Dilanjutkan")
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

    var $th = $('.tableFixHead').find('thead th')
    $('.tableFixHead').on('scroll', function() {
        $th.css('transform', 'translateY('+ this.scrollTop +'px)');
    });
</script>