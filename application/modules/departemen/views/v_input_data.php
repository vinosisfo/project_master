<style>
    .tableFixHead { overflow-y: auto; height: 280px; width : 95%; }
</style>
<form id="form_data" autocomplete="off">
    <div style="margin: 2px;">
        <button class="btn btn-danger btn-xs" type="button" onclick="tambah_baris(this)">Tambah</button>
        <div class="tableFixHead">
            <table class="table table-condensed" style="table-layout:auto;">
                <thead>
                    <tr>
                        <th style="width: 10px;"></th>
                        <th style="width: 20px;"></th>
                        <th>Nama Departemen</th>
                        <th>Singkatan</th>
                    </tr>
                </thead>
                <tbody id="row_baru"></tbody>
            </table>
        </div>
    </div>
    <div>
        <button class="btn btn-primary btn-xs" id="btn_simpan" type="button" onclick="simpan_data(this)">Simpan</button>
        <button class="btn btn-danger btn-xs" type="button" onclick="close_modal(this)">Close</button>
    </div>

</form>

<script>
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
                        '</td>'+
                        '<td>'+btn_hapus+'</td>'+
                        '<td>'+
                            '<input type="text" name="nama_departemen[]" id="nama_departemen_'+no_akhir_set+'" maxlength="200" style="width : 98%;" onblur="cek_data_dept(this,'+no_akhir_set+')">'+
                        '</td>'+
                        '<td>'+
                            '<input type="text" name="singkatan_departemen[]" id="singkatan_departemen_'+no_akhir_set+'" maxlength="50" style="width : 98%;" onblur="cek_data_barang(this,'+no_akhir_set+')">'+
                        '</td>'+
                    '</tr>';
        
        $("#row_baru").append(row_baris)
    }

    function cek_data_dept(data,urut){
        $.post("<?php echo base_url('departemen/c_departemen/cek_data_dept')?>",$("#form_data").serialize(),function(data){
            if(data.hasil=="ada"){
                error_msg("Duplikat Data Pada Baris "+urut+" Data Sudah Ada.\nSilahkan Isi Dengan Data Lain");
                $("#nama_departemen_"+urut).val("");
                $("#nama_departemen_"+urut).focus()
                return false;
            } else if(data.hasil=="ok"){
                cek_duplikat(data,urut)
            }
        },"json")
    }

    function cek_duplikat(data,urut){
        $.post("<?php echo base_url('departemen/c_departemen/cek_data_duplikat')?>",$("#form_data").serialize(),function(data){
            if(data.hasil=="ada"){
                error_msg("Duplikat Data Pada Baris "+urut+" Data Tidak Boleh Sama Dalam Satu Form");
                $("#nama_departemen_"+urut).val("");
                $("#nama_departemen_"+urut).focus()
                return false;
            } 
        },"json")
    }

    function hapus_row(data){
        $(data).closest('tr').remove();
		return false;
    }

    $(function(){
        tambah_baris(1);
    })
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
        $.post("<?php echo base_url('departemen/c_departemen/simpan_data')?>",$("#form_data").serialize(),function(data){
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