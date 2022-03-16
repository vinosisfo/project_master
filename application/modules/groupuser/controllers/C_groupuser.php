<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_groupuser extends MX_Controller  {
	
	public function __construct()
	{
		parent:: __construct();
        ini_set('memory_limit','256M');  
		ini_set('sqlsrv.ClientBufferMaxKBSize','524288');  
		ini_set('pdo_sqlsrv.client_buffer_max_kb_size','524288');
        if(empty($this->session->userdata("iduser")))
        {
            redirect(base_url('indexlog'));
        }
	}

	public function index($kode_menu='')
	{	
              $back_menu      = $this->get_support_lib_new->get_url_set();
        $data["back_menu"]    = $back_menu;
        $data["main_content"] = 'v_group';
        $this->load->view("template/bar/main_content",$data);
	}

	function get_data()
    {
        $list = $this->get_list();
        $data = array();
        $no   = $_POST['start'];
        foreach ($list as $field) { 
            $no++;
            $row = array();

            $row [] = $no;
            $row [] = '<button type="button" onclick="edit_data(\''.$field->KodeGroupUser.'\')" class="btn btn-info btn-xs">Edit</button>';
            $row [] = '<p style="text-align : left; margin-right:7px;">'.$field->NamaGroupUser.'</p>';
            $row [] = '<p style="text-align : left; margin-right:7px;">'.$field->Aktif.'</p>';
            // $row [] = '<p style="text-align : left; margin-right:7px;">'.($field->Aktif==1) ? "Ya" : "Tdk".'</p>';
            
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
        $this->db->order_by("NamaGroupUser");
        $query = $this->db->get();
        return $query->result();
    }

    function query_data()
    {
        $nama_src         = $this->input->post("nama_src");
        $status_aktif_src = $this->input->post("status_aktif_src");

        $where_nama      = (empty($nama_src)) ? "" : " AND A.NamaGroupUser LIKE '%$nama_src%'";
        $where_status    = (strlen($status_aktif_src)==0) ? "" : " AND A.Aktif='$status_aktif_src'";

        $sql = "(SELECT A.KodeGroupUser,A.NamaGroupUser,A.Aktif 
                FROM groupuser A 
                WHERE 1 = 1
                $where_nama
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
        $input = $this->input->post("input");
        $menu = $this->db->query("SELECT * FROM (
                                    SELECT A.KodeMenu
                                    ,(SELECT X.KodeMenu FROM menu X WHERE X.KodeMenu=A.SubMenu) AS kode_menu_group
                                    ,(SELECT X.NamaMenu FROM menu X WHERE X.KodeMenu=A.SubMenu) AS group_menu
                                    ,(SELECT X.KodeMenu FROM menu X WHERE X.KodeMenu=A.DetailMenu) AS kode_menu_sub
                                    ,(SELECT X.NamaMenu FROM menu X WHERE X.KodeMenu=A.DetailMenu) AS sub_menu
                                    ,A.NamaMenu
                                    FROM menu A WHERE A.Aktif=1 AND A.JenisMenu='list'
                                ) A1
                                ORDER BY A1.group_menu,A1.kode_menu_sub,A1.sub_menu");
        
        $data["menu"]  = $menu;
        $data["input"] = $input;
        $this->load->view("v_input_data",$data);
    }

    function cek_data_group(){
        $nama_group   = $this->input->post("nama_group");
        $aktif        = $this->input->post("status_aktif");
        $jenis_update = $this->input->post("jenis_update");
        
        $nama_group_cek = preg_replace('/\s+/', '', $nama_group);
        $where_aktif    = ($jenis_update=="update") ? " 1=1 AND A.Aktif='$aktif[0]'" : "1=1";
        $cek_data       = $this->db->select("A.KodeGroupUser,A.NamaGroupUser")
                                    ->from("groupuser A")
                                    ->where($where_aktif)
                                    ->where("replace(A.NamaGroupUser,' ', '')='$nama_group_cek'")
                                    ->get();
        
        $hasil["nama_group"] = ($cek_data->num_rows() > 0) ? ($cek_data->row()->NamaGroupUser) : "";
        $hasil["hasil"]      = ($cek_data->num_rows() > 0) ? "ada" : "ok";
        echo json_encode($hasil);
    }

    function cek_data_duplikat(){
        $nama_group = $this->input->post("nama_group");
        $cek_data   = array();
        foreach ($nama_group as $key => $value) {
            $nama_barang_cek = preg_replace('/\s+/', '', $nama_group[$key]);
            if (strlen($nama_barang_cek) > 0)
            {
                $cek_data[] = strtoupper($nama_group[$key]);
            }
            
        }
        $get_data = array_diff_key($cek_data, array_unique($cek_data));
        $pesan["hasil"]  = (empty($get_data)) ? "ok" : "ada";
        echo json_encode($pesan);
    }

    function simpan_data(){
        $tgl        = date("Y-m-d H:i:s");
        $tahun      = date('y');
        $bulan      = date('m');
        $hari       = date('d');
        $user_id    = $this->session->userdata("iduser");
        $nama_group = $this->input->post("nama_group");

        $status_cek      = $this->input->post("status_cek");
        $kode_menu_group = $this->input->post("kode_menu_group");
        $kode_menu_sub   = $this->input->post("kode_menu_sub");
        $KodeMenu        = $this->input->post("KodeMenu");

        $this->db->trans_start();
            $this->db->select('RIGHT(A.KodeGroupUser,3) as kode', FALSE);
            $this->db->order_by('A.KodeGroupUser','DESC');    
            $this->db->limit(1);
            $query = $this->db->get("groupuser A");
            if($query->num_rows() <> 0){      
                $data = $query->row();
                $kode = intval($data->kode) + 1;
            }
            else 
            { 
                $kode = 1;
            }
            $kodemax  = str_pad($kode, 3, "0", STR_PAD_LEFT);
            $kode_res = "GU".$tahun.$bulan.$hari.$kodemax;

            $data_group = [
                            "KodeGroupUser" => $kode_res,
                            "NamaGroupUser" => $nama_group,
                            "Aktif"         => 1,
                            "UserInput"     => $user_id,
                            "TglInput"      => $tgl,
                        ];

            $simpan_data_detail = $this->db->insert("groupuser",$data_group);

            $status = "";
            $detail = [];
            $no     = 0;
            foreach ($status_cek as $key => $value) {
                $no++;
                if($status_cek[$key]=="simpan"){
                    if(strlen($kode_menu_group[$key]) > 8){
                        $cek_header = $this->db->query("SELECT A.KodeUserMenuGroup 
                                                        FROM usermenugroup A 
                                                        WHERE A.KodeUserMenuGroup='$kode_res' 
                                                        AND A.KodeGroupUserMenu='$kode_menu_group[$key]'");
                        if($cek_header->num_rows() ==0){
                            $simpan_header = $this->db->query("INSERT INTO usermenugroup(KodeUserMenuGroup,KodeGroupUserMenu)
                                                        VALUES('$kode_res','$kode_menu_group[$key]')");
                        }
                    }

                    if(strlen($kode_menu_sub[$key]) > 8){
                        $cek_sub = $this->db->query("SELECT A.KodeUserMenuGroup 
                                                        FROM usermenugroup A 
                                                        WHERE A.KodeUserMenuGroup='$kode_res' 
                                                        AND A.KodeGroupUserMenu='$kode_menu_sub[$key]'");
                        if($cek_sub->num_rows() ==0){
                            $simpan_header = $this->db->query("INSERT INTO usermenugroup(KodeUserMenuGroup,KodeGroupUserMenu)
                                                            VALUES('$kode_res','$kode_menu_sub[$key]')");
                        }
                    }

                    if(strlen($KodeMenu[$key]) > 8){
                        $cek_menu = $this->db->query("SELECT A.KodeUserMenuGroup 
                                                        FROM usermenugroup A 
                                                        WHERE A.KodeUserMenuGroup='$kode_res' 
                                                        AND A.KodeGroupUserMenu='$KodeMenu[$key]'");
                        if($cek_menu->num_rows() ==0){
                            $simpan_header = $this->db->query("INSERT INTO usermenugroup(KodeUserMenuGroup,KodeGroupUserMenu)
                                                            VALUES('$kode_res','$KodeMenu[$key]')");
                        }
                    }
                }
                
            }
        $this->db->trans_complete();

        $pesan = ($this->db->trans_status()) ? "ok" : "gagal";

        $hasil["pesan"]  = $pesan;
        $hasil["status"] = $status;

        echo json_encode($hasil);
    }

    function edit_data(){
        $kode = $this->input->post("kode");
        $menu = $this->db->query("SELECT A.KodeMenu
                                ,(SELECT X.KodeMenu FROM menu X WHERE X.KodeMenu=A.SubMenu) AS kode_menu_group
                                ,(SELECT X.NamaMenu FROM menu X WHERE X.KodeMenu=A.SubMenu) AS group_menu
                                ,(SELECT X.KodeMenu FROM menu X WHERE X.KodeMenu=A.DetailMenu) AS kode_menu_sub
                                ,(SELECT X.NamaMenu FROM menu X WHERE X.KodeMenu=A.DetailMenu) AS sub_menu
                                ,A.NamaMenu
                                ,(SELECT X.KodeUserMenuGroup FROM usermenugroup X WHERE X.KodeGroupUserMenu=A.KodeMenu AND X.KodeUserMenuGroup='$kode' LIMIT 1) AS CEK
                                FROM menu A WHERE A.Aktif=1 AND A.JenisMenu='list'");

        $sql       = $this->db->query("SELECT DISTINCT A.KodeGroupUser,A.NamaGroupUser,A.Aktif ,B.KodeGroupUserMenu,C.NamaMenu
                                        FROM groupuser A 
                                        INNER JOIN usermenugroup B ON B.KodeUserMenuGroup=A.KodeGroupUser
                                        INNER JOIN menu C ON C.KodeMenu=B.KodeGroupUserMenu
                                        WHERE 1=1 AND A.KodeGroupUser='$kode'");
        
        $data["kode"] = $kode;
        $data["list"] = $sql;
        $data["menu"] = $menu;
        $this->load->view("v_edit_data",$data);
    }

    function update_data(){
        $tgl        = date("Y-m-d H:i:s");
        $tahun      = date('y');
        $bulan      = date('m');
        $hari       = date('d');
        $user_id    = $this->session->userdata("iduser");
        $kode_group = $this->input->post("kode_group");
        $nama_group = $this->input->post("nama_group");

        $status_cek      = $this->input->post("status_cek");
        $kode_menu_group = $this->input->post("kode_menu_group");
        $kode_menu_sub   = $this->input->post("kode_menu_sub");
        $KodeMenu        = $this->input->post("KodeMenu");

        $this->db->trans_start();
            $data_group = [
                            "NamaGroupUser" => $nama_group,
                            "UserInput"     => $user_id,
                            "TglInput"      => $tgl,
                        ];

            $simpan_data_detail = $this->db->update("groupuser",$data_group,["KodeGroupUser" => $kode_group]);

            $status = "";
            $detail = [];
            $no     = 0;
            $delete_all_group_menu = $this->db->where("KodeUserMenuGroup='$kode_group'")
                                            ->delete("usermenugroup");

            foreach ($status_cek as $key => $value) {
                $no++;
                if($status_cek[$key]=="simpan"){
                    if(strlen($kode_menu_group[$key]) > 8){
                        $cek_header = $this->db->query("SELECT A.KodeUserMenuGroup 
                                                        FROM usermenugroup A 
                                                        WHERE A.KodeUserMenuGroup='$kode_group' 
                                                        AND A.KodeGroupUserMenu='$kode_menu_group[$key]'");
                        if($cek_header->num_rows() ==0){
                            $simpan_header = $this->db->query("INSERT INTO usermenugroup(KodeUserMenuGroup,KodeGroupUserMenu)
                                                        VALUES('$kode_group','$kode_menu_group[$key]')");
                        }
                    }

                    if(strlen($kode_menu_sub[$key]) > 8){
                        $cek_sub = $this->db->query("SELECT A.KodeUserMenuGroup 
                                                        FROM usermenugroup A 
                                                        WHERE A.KodeUserMenuGroup='$kode_group' 
                                                        AND A.KodeGroupUserMenu='$kode_menu_sub[$key]'");
                        if($cek_sub->num_rows() ==0){
                            $simpan_header = $this->db->query("INSERT INTO usermenugroup(KodeUserMenuGroup,KodeGroupUserMenu)
                                                            VALUES('$kode_group','$kode_menu_sub[$key]')");
                        }
                    }

                    if(strlen($KodeMenu[$key]) > 8){
                        $cek_menu = $this->db->query("SELECT A.KodeUserMenuGroup 
                                                        FROM usermenugroup A 
                                                        WHERE A.KodeUserMenuGroup='$kode_group' 
                                                        AND A.KodeGroupUserMenu='$KodeMenu[$key]'");
                        if($cek_menu->num_rows() ==0){
                            $simpan_header = $this->db->query("INSERT INTO usermenugroup(KodeUserMenuGroup,KodeGroupUserMenu)
                                                            VALUES('$kode_group','$KodeMenu[$key]')");
                        }
                    }
                }
                
            }
        $this->db->trans_complete();
        $pesan = ($this->db->trans_status()) ? "ok" : "gagal";

        $hasil["pesan"]  = $pesan;
        $hasil["status"] = $status;

        echo json_encode($hasil);
    }
	
}
