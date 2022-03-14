<form id="form_data_input" autocomplete="off">
    <?php $head = $list->row(); ?>
    <table class="customers_ts">
        <tr>
            <td>Periode</td>
            <td>:</td>
            <td>
                <input type="text" name="periode_input" id="periode_input" value="<?php echo $head->Periode ?>" class="transparant" readonly>
            </td>
        </tr>
        <tr>
            <td>Kd Pegawai</td>
            <td>:</td>
            <td>
                <input type="text" name="kdpegawai_input" id="kdpegawai_input" value="<?php echo $head->KdPegawai ?>" class="transparant" readonly>
            </td>
        </tr>
        <tr>
            <td>Nama</td>
            <td>:</td>
            <td>
                <?php echo $head->HrdMsKaryawan_Nama ?>
            </td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>:</td>
            <td>
                <?php echo $head->NamaJabatan ?>
            </td>
        </tr>
        <tr>
            <td>Departemen</td>
            <td>:</td>
            <td>
                <?php echo $head->NamaDepartemen ?>
            </td>
        </tr>
        <tr>
            <td>Bagian</td>
            <td>:</td>
            <td>
                <?php echo $head->NamaBagian ?>
            </td>
        </tr>
        <tr>
            <td>Jml Hari</td>
            <td>:</td>
            <td>
                <input type="text" name="jml_hari_input" id="jml_hari_input" value="<?php echo $head->JmlHari ?>" onkeypress="return hanyaAngka(event)">
            </td>
        </tr>
    </table>
    <br>
    <div>
        <button type="button" id="btn_simpan" class="btn btn-primary btn-sm" onclick="simpan_data(this)">Simpan</button>&nbsp;
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
    </div>
</form>

<script>
    function simpan_data(){
        jml_hari_input = $("#jml_hari_input").val()

        if(jml_hari_input==""){
            error_msg("Jml Hari Tidak Boleh Kosong")
            $("#jml_hari_input").focus()
            return false;
        }

        $("#btn_simpan").prop("disabled",true)
        if(confirm("Data Akan Diupdate ?")){
            $.post("<?php echo base_url('uang_makan/c_uang_makan/update_data')?>",$("#form_data_input").serialize(),function(data){
                if(data.pesan=="ok"){
                    table.ajax.reload();
                    succes_msg("Data Berhasil Disimpan");
                    $("#modal_master").modal("hide")
                }
            },"json").fail(function(data){
                error_msg("Hubungi IT!")
                $("#btn_simpan").prop("disabled",false)
                return false;
            })
        } else {
            $("#btn_simpan").prop("disabled",false)
        }

    }
</script>