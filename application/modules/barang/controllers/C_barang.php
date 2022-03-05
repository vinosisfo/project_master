<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_barang extends MX_Controller  {
	
	public function __construct()
	{
		parent::__construct();
        ini_set('memory_limit','256M');  
		ini_set('sqlsrv.ClientBufferMaxKBSize','524288');  
		ini_set('pdo_sqlsrv.client_buffer_max_kb_size','524288');
        if(empty($this->session->userdata("iduser")))
        {
            redirect(base_url('indexlog'));
        }
	}

	public function index()
	{	
		$data["main_content"] = 'v_barang';
        $this->load->view("template/bar/main_content",$data);
	}

	function get_data()
    {
        $list = $this->get_list();
        $data = array();
        $no = $_POST['start']; 
        foreach ($list as $field) { 
            $no++;
            $row = array();

            $row [] = $no;
            $row [] = '<button type="button" onclick="edit_data(\''.$field->KodeBarang.'\')" class="btn btn-info btn-xs">Edit</button>';
            $row [] = '<p style="text-align : left; margin-right:7px;">'.$field->NamaBarang.'</p>';
            $row [] = '<p style="text-align : left; margin-right:7px;">'.$field->NamaSatuan.'</p>';
            $row [] = '<p style="text-align : left; margin-right:7px;">'.$field->SATUAN_KECIL.'</p>';
            $row [] = '<p style="text-align : left; margin-right:7px;">'.$field->JenisBarang.'</p>';
            $row [] = '<p style="text-align : left; margin-right:7px;">'.$field->NamaJenisPesan.'</p>';
            $row [] = '<p style="text-align : left; margin-right:7px;">'.$field->NamaRak.' - '.$field->Alias.'</p>';
            $row [] = '<p style="text-align : left; margin-right:7px;">'.number_format($field->Harga).'</p>';
            $row [] = '<p style="text-align : left; margin-right:7px;">'.number_format($field->StokMinimal).'</p>';
            $row [] = '<p style="text-align : left; margin-right:7px;">'.$field->NamaManufacture.'</p>';
            $row [] = '<p style="text-align : left; margin-right:7px;">'.($field->Aktif==1) ? "Ya" : "Tdk".'</p>';
            
            $data[] = $row;
        }

        $output = array(
            "draw"            => $_POST['draw'],
            "recordsTotal"    => $this->count_all(),
            "recordsFiltered" => $this->count_filtered(),
            "data"            => $data,
        );
        echo json_encode($output);
    }

    function get_list()
    {
        $this->query_data();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function query_data()
    {
        $nama_src         = $this->input->post("nama_src");
        $jenis_src        = $this->input->post("jenis_src");
        $manuf_src        = $this->input->post("manuf_src");
        $asal_src        = $this->input->post("asal_src");
        $status_aktif_src = $this->input->post("status_aktif_src");

        $where_nama   = (empty($nama_src)) ? "" : " AND A.NamaBarang LIKE '%$nama_src%'";
        $where_jenis  = (empty($jenis_src)) ? "" : " AND D.JenisBarang LIKE '%$jenis_src%'";
        $where_manuf  = (empty($manuf_src)) ? "" : " AND G.NamaManufacture LIKE '%$manuf_src%'";
        $where_asal  = (empty($asal_src)) ? "" : " AND E.NamaJenisPesan LIKE '%$asal_src%'";
        $where_status = (strlen($status_aktif_src)==0) ? "" : " AND A.Aktif='$status_aktif_src'";

        $sql = "(SELECT A.KodeBarang,A.IdSatuanBesar,B.NamaSatuan,A.IdSatuanKecil,C.NamaSatuan AS SATUAN_KECIL  
                ,A.IdJenis,D.JenisBarang,A.IdAsal,E.NamaJenisPesan,A.IdRakDetail,F.NamaRak,F.Alias,A.Harga,A.NamaBarang,A.Aktif
                ,G.idmanufacture,G.NamaManufacture,A.StokMinimal
                FROM barang A
                INNER JOIN satuan B ON B.IdSatuan=A.IdSatuanBesar
                INNER JOIN satuan C ON C.IdSatuan=A.IdSatuanKecil
                INNER JOIN jenisbarang D ON D.idJenisBarang=A.IdJenis
                INNER JOIN jenispesan E ON E.idJenisPesan=A.IdAsal
                INNER JOIN (SELECT DISTINCT X.idRak,X.NamaRak,Y.idRakDetail,Y.Alias FROM rak X INNER JOIN rakdetail Y ON X.idRak=Y.idRak) F ON F.idRakDetail=A.IdRakDetail 
                INNER JOIN manufacture G ON G.idmanufacture=A.IdManufacture
                WHERE 1=1
                $where_nama
                $where_jenis
                $where_manuf
                $where_asal
                $where_status
                ) A1";
        $this->db->from($sql);
    }

    function count_filtered()
    {
        $this->query_data();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $sql = 0;
        return $sql;
    }
    
    function input_data(){
        $input  = $this->input->post("input");
        $satuan = $this->db->query("SELECT DISTINCT A.IdSatuan,A.NamaSatuan FROM satuan A WHERE A.Aktif=1 ORDER BY A.NamaSatuan ASC");
        $jenis  = $this->db->query("SELECT DISTINCT A.idJenisBarang,A.JenisBarang FROM jenisbarang A WHERE A.Aktif=1 ORDER BY A.JenisBarang");
        $asal   = $this->db->query("SELECT DISTINCT A.idJenisPesan,A.NamaJenisPesan FROM jenispesan A WHERE A.Aktif=1 ORDER BY A.NamaJenisPesan");
        $manuf  = $this->db->query("SELECT DISTINCT A.idmanufacture,A.NamaManufacture FROM manufacture A WHERE A.Aktif=1 ORDER BY A.NamaManufacture");
        $rak    = $this->db->query("SELECT DISTINCT A.idRakDetail,B.idRak,B.NamaRak,A.Alias 
                                    FROM rakdetail A INNER JOIN rak B ON B.idRak=A.IdRak
                                    WHERE A.Aktif=1 AND B.Aktif=1
                                    ORDER BY B.NamaRak,A.Alias");

        $data["satuan"] = $satuan;
        $data["jenis"]  = $jenis;
        $data["asal"]   = $asal;
        $data["manuf"]  = $manuf;
        $data["rak"]    = $rak;
        $this->load->view("v_input_data",$data);
    }

    function cek_data_barang(){
        $nama_barang  = $this->input->post("nama_barang");
        $satuan_besar = $this->input->post("satuan_besar");
        $satuan_kecil = $this->input->post("satuan_kecil");
        $jenis_barang = $this->input->post("jenis_barang");
        $asal_barang  = $this->input->post("asal_barang");
        $rak_barang   = $this->input->post("rak_barang");
        $manufacture  = $this->input->post("manufacture");
        $stok_min     = $this->input->post("stok_min");
        $harga        = $this->input->post("harga");
        $aktif        = $this->input->post("aktif");
        $jenis_update = $this->input->post("jenis_update");
        
        $nama_barang_cek = preg_replace('/\s+/', '', $nama_barang);
        $stok_min_set    = str_replace(",","",$stok_min[0]);
        $harga_set       = str_replace(",","",$harga[0]);
        $where_aktif     = ($jenis_update=="update") ? " 1=1 AND A.Aktif='$aktif[0]' AND A.IdSatuanBesar='$satuan_besar[0]' 
                                                        AND A.IdSatuanKecil='$satuan_kecil[0]' AND A.IdAsal='$asal_barang[0]' AND A.IdRakDetail='$rak_barang[0]'
                                                        AND A.StokMinimal='$stok_min_set' AND A.Harga='$harga_set'" : "1=1";
        $cek_data        = $this->db->select("A.KodeBarang")
                                    ->from("Barang A")
                                    ->where($where_aktif)
                                    ->where_in("replace(A.NamaBarang,' ', '')",$nama_barang_cek)
                                    ->where_in("A.IdJenis",$jenis_barang)
                                    ->where_in("A.IdManufacture",$manufacture)
                                    ->get();
        
        $hasil["hasil"] = ($cek_data->num_rows() > 0) ? "ada" : "ok";
        echo json_encode($hasil);
    }

    function cek_data_duplikat(){
        $nama_barang  = $this->input->post("nama_barang");
        $satuan_besar = $this->input->post("satuan_besar");
        $satuan_kecil = $this->input->post("satuan_kecil");
        $jenis_barang = $this->input->post("jenis_barang");
        $asal_barang  = $this->input->post("asal_barang");
        $rak_barang   = $this->input->post("rak_barang");
        $manufacture  = $this->input->post("manufacture");
        $stok_min     = $this->input->post("stok_min");
        $harga        = $this->input->post("harga");
        $cek_data     = array();
        foreach ($nama_barang as $key => $value) {
            $nama_barang_cek = preg_replace('/\s+/', '', $nama_barang[$key]);
            if (strlen($nama_barang_cek) > 0)
            {
                $cek_data[] = strtoupper($nama_barang[$key]).$jenis_barang[$key].$manufacture[$key];
            }
            
        }
        $get_data = array_diff_key($cek_data, array_unique($cek_data));
        $pesan["hasil"] 	= (empty($get_data)) ? "ok" : "ada";
        echo json_encode($pesan);
    }

    function simpan_data(){
        $tgl          = date("Y-m-d H:i:s");
        $tahun        = date('y');
        $bulan        = date('m');
        $hari         = date('d');
        $user_id      = $this->session->userdata("iduser");
        $no_urut      = $this->input->post("no_urut");
        $nama_barang  = $this->input->post("nama_barang");
        $satuan_besar = $this->input->post("satuan_besar");
        $satuan_kecil = $this->input->post("satuan_kecil");
        $jenis_barang = $this->input->post("jenis_barang");
        $asal_barang  = $this->input->post("asal_barang");
        $rak_barang   = $this->input->post("rak_barang");
        $manufacture  = $this->input->post("manufacture");
        $stok_min     = $this->input->post("stok_min");
        $harga        = $this->input->post("harga");

        $this->db->trans_start();
            $status = "";
            $detail = [];
            $no     = 0;
            foreach ($nama_barang as $key => $value) {
                $no++;
                $this->db->select('RIGHT(A.KodeBarang,3) as kode', FALSE);
                $this->db->order_by('A.KodeBarang','DESC');    
                $this->db->limit(1);
                $query = $this->db->get("barang A");
                if($query->num_rows() <> 0){      
                    $data = $query->row();
                    $kode = intval($data->kode) + $no_urut[$key];
                }
                else 
                { 
                    $kode = $no_urut[$key];    
                }
                $kodemax  = str_pad($kode, 3, "0", STR_PAD_LEFT);
                $kode_res = "BG".$tahun.$bulan.$hari.$kodemax;
                $data_detail = [
                    "KodeBarang"    => $kode_res,
                    "IdSatuanBesar" => $satuan_besar[$key],
                    "IdSatuanKecil" => $satuan_kecil[$key],
                    "IdJenis"       => $jenis_barang[$key],
                    "IdAsal"        => $asal_barang[$key],
                    "IdRakDetail"   => $rak_barang[$key],
                    "IdManufacture" => $manufacture[$key],
                    "NamaBarang"    => strtoupper($nama_barang[$key]),
                    "Harga"         => str_replace(",","",$harga[$key]),
                    "StokMinimal"   => str_replace(",","",$stok_min[$key]),
                    "Aktif"         => 1,
                    "UserInput"     => $user_id,
                    "TglInput"      => $tgl,
                ];
                array_push($detail,$data_detail);
            }

            $simpan_data_detail = $this->db->insert_batch("barang",$detail);

        $this->db->trans_complete();
        $pesan = ($this->db->trans_status()) ? "ok" : "gagal";

        $hasil["pesan"]  = $pesan;
        $hasil["status"] = $status;

        echo json_encode($hasil);
    }

    function edit_data(){
        $kode = $this->input->post("kode");
        $satuan = $this->db->query("SELECT DISTINCT A.IdSatuan,A.NamaSatuan FROM satuan A WHERE A.Aktif=1 ORDER BY A.NamaSatuan ASC");
        $jenis  = $this->db->query("SELECT DISTINCT A.idJenisBarang,A.JenisBarang FROM jenisbarang A WHERE A.Aktif=1 ORDER BY A.JenisBarang");
        $asal   = $this->db->query("SELECT DISTINCT A.idJenisPesan,A.NamaJenisPesan FROM jenispesan A WHERE A.Aktif=1 ORDER BY A.NamaJenisPesan");
        $manuf  = $this->db->query("SELECT DISTINCT A.idmanufacture,A.NamaManufacture FROM manufacture A WHERE A.Aktif=1 ORDER BY A.NamaManufacture");
        $rak    = $this->db->query("SELECT DISTINCT A.idRakDetail,B.idRak,B.NamaRak,A.Alias 
                                    FROM rakdetail A INNER JOIN rak B ON B.idRak=A.IdRak
                                    WHERE A.Aktif=1 AND B.Aktif=1
                                    ORDER BY B.NamaRak,A.Alias");

        $sql       = $this->db->query("SELECT A.KodeBarang,A.IdSatuanBesar,B.NamaSatuan,A.IdSatuanKecil,C.NamaSatuan AS SATUAN_KECIL  
                                    ,A.IdJenis,D.JenisBarang,A.IdAsal,E.NamaJenisPesan,A.IdRakDetail,F.NamaRak,F.Alias,A.Harga,A.NamaBarang,A.Aktif
                                    ,G.idmanufacture,G.NamaManufacture,A.StokMinimal
                                    FROM barang A
                                    INNER JOIN satuan B ON B.IdSatuan=A.IdSatuanBesar
                                    INNER JOIN satuan C ON C.IdSatuan=A.IdSatuanKecil
                                    INNER JOIN jenisbarang D ON D.idJenisBarang=A.IdJenis
                                    INNER JOIN jenispesan E ON E.idJenisPesan=A.IdAsal
                                    INNER JOIN (SELECT DISTINCT X.idRak,X.NamaRak,Y.idRakDetail,Y.Alias FROM rak X INNER JOIN rakdetail Y ON X.idRak=Y.idRak) F ON F.idRakDetail=A.IdRakDetail 
                                    INNER JOIN manufacture G ON G.idmanufacture=A.IdManufacture
                                    WHERE 1=1
                                    AND A.KodeBarang='$kode'");
        
        $data["kode"]   = $kode;
        $data["list"]   = $sql;
        $data["satuan"] = $satuan;
        $data["jenis"]  = $jenis;
        $data["asal"]   = $asal;
        $data["manuf"]  = $manuf;
        $data["rak"]    = $rak;
        $this->load->view("v_edit_data",$data);
    }

    function update_data(){
        $tgl          = date("Y-m-d H:i:s");
        $tahun        = date('y');
        $bulan        = date('m');
        $hari         = date('d');
        $user_id      = $this->session->userdata("iduser");
        $kode_barang  = $this->input->post("kode_barang");
        $no_urut      = $this->input->post("no_urut");
        $nama_barang  = $this->input->post("nama_barang");
        $satuan_besar = $this->input->post("satuan_besar");
        $satuan_kecil = $this->input->post("satuan_kecil");
        $jenis_barang = $this->input->post("jenis_barang");
        $asal_barang  = $this->input->post("asal_barang");
        $rak_barang   = $this->input->post("rak_barang");
        $manufacture  = $this->input->post("manufacture");
        $stok_min     = $this->input->post("stok_min");
        $harga        = $this->input->post("harga");
        $aktif        = $this->input->post("aktif");

        $this->db->trans_start();
            $status = "";
            $no     = 0;
            foreach ($nama_barang as $key => $value) {
                $no++;
                $data_detail = [
                    "IdSatuanBesar" => $satuan_besar[$key],
                    "IdSatuanKecil" => $satuan_kecil[$key],
                    "IdJenis"       => $jenis_barang[$key],
                    "IdAsal"        => $asal_barang[$key],
                    "IdRakDetail"   => $rak_barang[$key],
                    "IdManufacture" => $manufacture[$key],
                    "NamaBarang"    => strtoupper($nama_barang[$key]),
                    "Harga"         => str_replace(",","",$harga[$key]),
                    "StokMinimal"   => str_replace(",","",$stok_min[$key]),
                    "Aktif"         => $aktif[$key],
                    "UserInput"     => $user_id,
                    "TglInput"      => $tgl,
                ];
                $simpan_data_detail = $this->db->update("barang",$data_detail,["KodeBarang" => $kode_barang[$key]]);
            }

        $this->db->trans_complete();
        $pesan = ($this->db->trans_status()) ? "ok" : "gagal";

        $hasil["pesan"]  = $pesan;
        $hasil["status"] = $status;

        echo json_encode($hasil);
    }
	
}
