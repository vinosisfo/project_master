<style>
    .tableFixHead { overflow-y: auto; height: 280px; width : 95%; }
</style>
<form id="form_data" autocomplete="off">
    <div style="margin: 2px;">
        <button class="btn btn-danger btn-xs" type="button" onclick="tambah_baris(this)">Tambah</button>
        <div class="tableFixHead">
            <table class="table table-condensed" style="max-width: 150%;">
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                        <th>Nama Barang</th>
                        <th>Satuan Besar</th>
                        <th>Satuan Kecil</th>
                        <th>Jenis</th>
                        <th>Asal</th>
                        <th>Rak</th>
                        <th>Manufacture</th>
                        <th>Stok Min</th>
                        <th>Harga</th>
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
                            '<input type="text" name="nama_barang[]" id="nama_barang_'+no_akhir_set+'" maxlength="200" style="width : 200px;" onblur="cek_data_barang(this,'+no_akhir_set+')">'+
                        '</td>'+
                        '<td>'+
                            '<select name="satuan_besar[]" id="satuan_besar_'+no_akhir_set+'" style="width : 120%;">'+
                                '<option value="">PILIH</option>'+
                                <?php foreach ($satuan->result() as $stn) { ?>
                                    '<option value="<?php echo $stn->IdSatuan ?>"><?php echo $stn->NamaSatuan ?></option>'+
                                <?php } ?>
                            '</select>'+
                        '</td>'+
                        '<td>'+
                            '<select name="satuan_kecil[]" id="satuan_kecil_'+no_akhir_set+'" style="width : 120%;">'+
                                '<option value="">PILIH</option>'+
                                <?php foreach ($satuan->result() as $stn) { ?>
                                    '<option value="<?php echo $stn->IdSatuan ?>"><?php echo $stn->NamaSatuan ?></option>'+
                                <?php } ?>
                            '</select>'+
                        '</td>'+
                        '<td>'+
                            '<select name="jenis_barang[]" id="jenis_barang_'+no_akhir_set+'" style="width : 120%;" onchange="cek_data_barang(this,'+no_akhir_set+')">'+
                                '<option value="">PILIH</option>'+
                                <?php foreach ($jenis->result() as $jns) { ?>
                                    '<option value="<?php echo $jns->idJenisBarang ?>"><?php echo $jns->JenisBarang ?></option>'+
                                <?php } ?>
                            '</select>'+
                        '</td>'+
                        '<td>'+
                            '<select name="asal_barang[]" id="asal_barang_'+no_akhir_set+'" style="width : 120%;">'+
                                '<option value="">PILIH</option>'+
                                <?php foreach ($asal->result() as $asl) { ?>
                                    '<option value="<?php echo $asl->idJenisPesan ?>"><?php echo $asl->NamaJenisPesan ?></option>'+
                                <?php } ?>
                            '</select>'+
                        '</td>'+
                        '<td>'+
                            '<select name="rak_barang[]" id="rak_barang_'+no_akhir_set+'" style="width : 120%;">'+
                                '<option value="">PILIH</option>'+
                                <?php foreach ($rak->result() as $rk) { ?>
                                    '<option value="<?php echo $rk->idRakDetail ?>"><?php echo $rk->NamaRak." - ".$rk->Alias ?></option>'+
                                <?php } ?>
                            '</select>'+
                        '</td>'+
                        '<td>'+
                            '<select name="manufacture[]" id="manufacture_'+no_akhir_set+'" style="width : 120%;" onchange="cek_data_barang(this,'+no_akhir_set+')">'+
                                '<option value="">PILIH</option>'+
                                <?php foreach ($manuf->result() as $mnf) { ?>
                                    '<option value="<?php echo $mnf->idmanufacture ?>"><?php echo $mnf->NamaManufacture ?></option>'+
                                <?php } ?>
                            '</select>'+
                        '</td>'+
                        '<td>'+
                            '<input type="text" name="stok_min[]" id="stok_min_'+no_akhir_set+'" maxlength="10" style="width : 60px; text-align : right;" onkeyup="hanya_angka(this,'+no_akhir_set+')">'+
                        '</td>'+
                        '<td>'+
                            '<input type="text" name="harga[]" id="harga_'+no_akhir_set+'" maxlength="20" style="width : 80px; text-align : right;" onkeyup="hanya_angka(this,'+no_akhir_set+')">'+
                        '</td>'+
                    '</tr>';
        
        $("#row_baru").append(row_baris)
    }

    function cek_data_barang(data,urut){
        $.post("<?php echo base_url('barang/c_barang/cek_data_barang')?>",$("#form_data").serialize(),function(data){
            if(data.hasil=="ada"){
                error_msg("Duplikat Data Pada Baris "+urut+" Data Sudah Ada.\nSilahkan Isi Dengan Data Lain");
                $("#nama_barang_"+urut).val("");
                $("#nama_barang_"+urut).focus()
                return false;
            } else if(data.hasil=="ok"){
                cek_duplikat(data,urut)
            }
        },"json")
    }

    function cek_duplikat(data,urut){
        $.post("<?php echo base_url('barang/c_barang/cek_data_duplikat')?>",$("#form_data").serialize(),function(data){
            if(data.hasil=="ada"){
                error_msg("Duplikat Data Pada Baris "+urut+" Data Tidak Boleh Sama Dalam Satu Form");
                $("#nama_barang_"+urut).val("");
                $("#nama_barang_"+urut).focus()
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
        v_barang = document.getElementsByName('nama_barang[]');
        for (i=0; i<v_barang.length; i++)
        {
            nomor           = parseInt(i)+1;
            nama_barang     = $("#nama_barang_"+nomor).val()
            nama_barang_cek = nama_barang.replace(/\s+/g, '');
            if (nama_barang_cek == "0")
            {
                error_msg("Nama Barang Tidak Boleh Kosong")
                $("#nama_barang_"+nomor).focus()   
                return false;
            }
        }

        v_sbesar = document.getElementsByName('satuan_besar[]');
        for (i=0; i<v_sbesar.length; i++)
        {
            nomor = parseInt(i)+1;
            if (v_sbesar[i].value == "")
            {
                error_msg("Satuan Besar Tidak Boleh Kosong")
                $("#satuan_besar_"+nomor).focus()   
                return false;
            }
        }

        v_skecil = document.getElementsByName('satuan_kecil[]');
        for (i=0; i<v_skecil.length; i++)
        {
            nomor = parseInt(i)+1;
            if (v_skecil[i].value == "")
            {
                error_msg("Satuan Kecil Tidak Boleh Kosong")
                $("#satuan_kecil_"+nomor).focus()   
                return false;
            }
        }

        v_jenis = document.getElementsByName('jenis_barang[]');
        for (i=0; i<v_jenis.length; i++)
        {
            nomor = parseInt(i)+1;
            if (v_jenis[i].value == "")
            {
                error_msg("jenis Barang Tidak Boleh Kosong")
                $("#jenis_barang_"+nomor).focus()   
                return false;
            }
        }

        v_asal = document.getElementsByName('asal_barang[]');
        for (i=0; i<v_asal.length; i++)
        {
            nomor = parseInt(i)+1;
            if (v_asal[i].value == "")
            {
                error_msg("Asal Barang Tidak Boleh Kosong")
                $("#asal_barang_"+nomor).focus()   
                return false;
            }
        }

        v_rak = document.getElementsByName('rak_barang[]');
        for (i=0; i<v_rak.length; i++)
        {
            nomor = parseInt(i)+1;
            if (v_rak[i].value == "")
            {
                error_msg("Rak Barang Tidak Boleh Kosong")
                $("#rak_barang_"+nomor).focus()   
                return false;
            }
        }

        v_manuf = document.getElementsByName('manufacture[]');
        for (i=0; i<v_manuf.length; i++)
        {
            nomor = parseInt(i)+1;
            if (v_manuf[i].value == "")
            {
                error_msg("Manufacture Tidak Boleh Kosong")
                $("#manufacture_"+nomor).focus()   
                return false;
            }
        }

        v_stok_min = document.getElementsByName('stok_min[]');
        for (i=0; i<v_stok_min.length; i++)
        {
            nomor = parseInt(i)+1;
            if (v_stok_min[i].value == "")
            {
                error_msg("Stok Minimal Tidak Boleh Kosong")
                $("#stok_min_"+nomor).focus()   
                return false;
            }
        }

        v_harga = document.getElementsByName('harga[]');
        for (i=0; i<v_harga.length; i++)
        {
            nomor = parseInt(i)+1;
            if (v_harga[i].value == "")
            {
                error_msg("Harga Tidak Boleh Kosong")
                $("#harga_"+nomor).focus()   
                return false;
            }
        }

        $("#btn_simpan").prop("disabled",true)
        $.post("<?php echo base_url('barang/c_barang/simpan_data')?>",$("#form_data").serialize(),function(data){
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