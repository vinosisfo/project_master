<form id="form_data" autocomplete="off">
    <div style="margin: 2px;">
        <table class="customers" style="max-width: 50%;">
            <tr>
                <td>Nama Jabatan</td>
                <td>:</td>
                <td>
                    <input type="text" name="nama_jabatan" id="nama_jabatan" maxlength="100" style="text-transform: uppercase;">
                </td>
            </tr>
            <tr>
                <td>No Urut</td>
                <td>:</td>
                <td>
                    <input type="text" name="nourut" id="nourut" onkeyup="hanya_angka(this)">
                </td>
            </tr>
        </table>
    </div>
    <div>
        <button class="btn btn-primary btn-xs" id="btn_simpan" type="button" onclick="cek_jabatan(this)">Simpan</button>
        <button class="btn btn-danger btn-xs" type="button" onclick="close_modal(this)">Close</button>
    </div>

</form>

<script>
    function cek_jabatan(){
        $.post("<?php echo base_url('jabatan/c_jabatan/cek_data')?>",$("#form_data").serialize(),function(data){
            if(data.status=="ada"){
                error_msg("Data Jabatan Sudah Ada! Silahkan Input Data Lain")
                $("#nama_jabatan").focus()
                return false
            } else {
                simpan_data();
            }
        },"json")
    }
    function simpan_data(){
        nama_jabatan     = $("#nama_jabatan").val()
        nama_jabatan_cek = nama_jabatan.replace(/\s+/g, '');

        if(nama_jabatan_cek==""){
            error_msg("Jabatan Tidak Boleh Kosong")
            $("#nama_jabatan").focus()
            return false
        }
        $("#btn_simpan").prop("disabled",true)
        $.post("<?php echo base_url('jabatan/c_jabatan/simpan_data')?>",$("#form_data").serialize(),function(data){
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
</script>