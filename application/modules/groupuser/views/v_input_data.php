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
                        <th>Nama Group</th>
                    </tr>
                </thead>
                <tbody id="row_baru"></tbody>
            </table>
        </div>
    </div>
    <div>
        <button class="btn btn-primary btn-xs" id="btn_simpan" type="button" onclick="cek_data_group(this,'0','simpan')">Simpan</button>
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
                            '<input type="text" name="nama_group[]" id="nama_group_'+no_akhir_set+'" maxlength="200" style="width : 98%;" onblur="cek_duplikat(this,'+no_akhir_set+')">'+
                        '</td>'+
                    '</tr>';
        
        $("#row_baru").append(row_baris)
    }

    function cek_data_group(data,urut,simpan=''){
        $.post("<?php echo base_url('groupuser/c_groupuser/cek_data_group')?>",$("#form_data").serialize(),function(data){
            if(data.hasil=="ada"){
                error_msg("Duplikat Data, Nama Group "+data.nama_group+" Data Sudah Ada.\nSilahkan Isi Dengan Data Lain");
                $("#nama_group_"+urut).val("");
                $("#nama_group_"+urut).focus()
                return false;
            } else if((data.hasil=="ok") &&(simpan=="")){
                cek_duplikat(data,urut)
            } else if((data.hasil=="ok") &&(simpan=="simpan")){
                simpan_data()
            }
        },"json")
    }

    function cek_duplikat(data,urut){
        $.post("<?php echo base_url('groupuser/c_groupuser/cek_data_duplikat')?>",$("#form_data").serialize(),function(data){
            if(data.hasil=="ada"){
                error_msg("Duplikat Data Pada Baris "+urut+" Data Tidak Boleh Sama Dalam Satu Form");
                $("#nama_group_"+urut).val("");
                $("#nama_group_"+urut).focus()
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
        $.post("<?php echo base_url('groupuser/c_groupuser/simpan_data')?>",$("#form_data").serialize(),function(data){
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