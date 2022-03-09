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
                        <th></th>
                        <th></th>
                        <th>Nama Departemen</th>
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
                                <input type="hidden" name="id_departemen[]" id="id_departemen_<?php echo $no ?>" value="<?php echo $data->IdDepartemen ?>" readonly>
                                <input type="hidden" name="no_urut[]" id="no_urut_<?php echo $no ?>" class="no_urut" value="<?php echo $no ?>" readonly>
                            </td>
                            <td></td>
                            <td>
                                <input value="<?php echo $data->NamaDepartemen ?>" type="text" name="nama_departemen[]" id="nama_departemen_<?php echo $no ?>" maxlength="200" style="width : 98%;">
                            </td>
                            <td>
                                <input value="<?php echo $data->Singkatan ?>" type="text" name="singkatan_departemen[]" id="singkatan_departemen_<?php echo $no ?>" maxlength="50" style="width : 98%;">
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
        $.post("<?php echo base_url('departemen/c_departemen/cek_data_dept')?>",$("#form_data").serialize()+"&jenis_update=update",function(data){
            if(data.hasil=="ada"){
                error_msg("Duplikat Data Pada Baris "+urut+" Data Sudah Ada.\nSilahkan Isi Dengan Data Lain");
                $("#nama_departemen_"+urut).val("");
                $("#nama_departemen_"+urut).focus()
                return false;
            } else if(data.hasil=="ok")
            {
                simpan_data()
                console.log("aaa")
            }
        },"json")
    }

    function simpan_data(){
        v_dept = document.getElementsByName('nama_departemen[]');
        for (i=0; i<v_dept.length; i++)
        {
            nomor           = parseInt(i)+1;
            nama_dept     = $("#nama_departemen_"+nomor).val()
            nama_barang_cek = nama_dept.replace(/\s+/g, '');
            if (nama_barang_cek == "0")
            {
                error_msg("Nama Departemen Tidak Boleh Kosong")
                $("#nama_departemen_"+nomor).focus()   
                return false;
            }
        }

        $("#btn_simpan").prop("disabled",true)
        $.post("<?php echo base_url('departemen/c_departemen/update_data')?>",$("#form_data").serialize(),function(data){
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