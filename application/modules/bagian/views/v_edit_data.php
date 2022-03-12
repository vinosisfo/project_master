<style>
    .tableFixHead { overflow-y: auto; height: 280px; width : 95%; }
</style>
<form id="form_data" autocomplete="off">
    <?php $head = $list->row(); ?>
    <div style="margin: 2px;">
        <table class="table table-condensed" style="white-space: nowrap;">
            <tr>
                <td style="width: 100px;">Departemen</td>
                <td style="width: 1px;">:</td>
                <td>
                    <select name="departemen" id="departemen" style="width: 200px;">
                        <option value="<?php echo $head->IdDepartemen ?>"><?php echo $head->NamaDepartemen ?></option>
                    </select>
                </td>
            </tr>
        </table>

        <div class="tableFixHead" style="margin-top: 10px;">
            <table class="table table-condensed" style="max-width: 150%;">
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                        <th>Nama Bagian</th>
                        <th>Singkatan</th>
                        <th>Aktif</th>
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
                                <input type="hidden" name="id_bagian[]" id="id_bagian_<?php echo $no ?>" value="<?php echo $data->IdBagianDepartemen ?>" readonly>
                                <input type="hidden" name="no_urut[]" id="no_urut_<?php echo $no ?>" class="no_urut" value="<?php echo $no ?>" readonly>
                            </td>
                            <td></td>
                            <td>
                                <input value="<?php echo $data->NamaBagian ?>" type="text" name="nama_bagian[]" id="nama_bagian_<?php echo $no ?>" maxlength="200" style="width : 98%;">
                            </td>
                            <td>
                                <input value="<?php echo $data->SingkatanBagian ?>" type="text" name="singkatan_bagian[]" id="singkatan_bagian<?php echo $no ?>" maxlength="50" style="width : 98%;">
                            </td>
                            <td>
                            <select name="status_aktif" id="status_aktif">
                                <?php if($data->Aktif_Bagian==1){ ?>
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
        $.post("<?php echo base_url('bagian/c_bagian/cek_data_bagian')?>",$("#form_data").serialize()+"&jenis_update=update",function(data){
            if(data.hasil=="ada"){
                error_msg("Duplikat Data Pada Baris "+urut+" Data Sudah Ada.\nSilahkan Isi Dengan Data Lain");
                $("#nama_bagian_"+urut).val("");
                $("#nama_bagian_"+urut).focus()
                return false;
            } else if(data.hasil=="ok")
            {
                simpan_data()
                console.log("aaa")
            }
        },"json")
    }

    function simpan_data(){
        v_bagian = document.getElementsByName('nama_bagian[]');
        for (i=0; i<v_bagian.length; i++)
        {
            nomor           = parseInt(i)+1;
            nama_bagian     = $("#nama_bagian_"+nomor).val()
            nama_bagian_cek = nama_bagian.replace(/\s+/g, '');
            if (nama_bagian_cek == "0")
            {
                error_msg("Nama Bagian Tidak Boleh Kosong")
                $("#nama_bagian_"+nomor).focus()   
                return false;
            }
        }

        $("#btn_simpan").prop("disabled",true)
        $.post("<?php echo base_url('bagian/c_bagian/update_data')?>",$("#form_data").serialize(),function(data){
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