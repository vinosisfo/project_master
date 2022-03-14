<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_group_user extends MX_Controller  {
	
	public function __construct()
	{
		parent::__construct();
        ini_set('memory_limit','256M');  
		ini_set('sqlsrv.ClientBufferMaxKBSize','524288');  
		ini_set('pdo_sqlsrv.client_buffer_max_kb_size','524288');
        $this->load->library("get_lib_support");
        if(empty($this->session->userdata("id_user")))
        {
            redirect(base_url('admin'));
        }
	}

	public function index()
	{	
		$this->load->view('template/css/css_2');
        $this->load->view('template/css/css_table');
        $this->load->view('template/menu_bar/top_bar_2');
        $this->load->view('template/menu_bar/side_bar_2');
        $this->load->view("template/js/js_2");

        $data["periode"] = "";
        $this->load->view("f_group_user",$data);
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
            $row [] = ($field->MsGroupId==1) ? "" : '<button type="button" class="btn btn-info btn-xs" onclick="edit_data(\''.$field->MsGroupId.'\')">Edit</button>';
            // $row [] = ($field->MsGroupId==1) ? "" : '<button type="button" class="btn btn-success btn-xs" onclick="detail(\''.$field->MsGroupId.'\')">Detail</button>';
            $row [] = '<p style="text-align : left;">'.$field->MsGroupId.'</p>';
            $row [] = '<p style="text-align : left;">'.$field->MsGroupNama.'</p>';
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
        $query = $this->sql_list();
        return $query->result();
    }

    function sql_list($filter=''){
        $kode_pegawai = $this->session->userdata("id_user");
        $id_nama      = $this->input->post("id_nama");

        $akhir         = $_POST['length'];
        $awal          = $_POST['start'];
        $where_row_num = "";
        $where_periode = "";
        if($_POST['length'] != -1){
			$akhir_set     = ($awal > 0) ? (($awal>$akhir) ? ($akhir+$awal) : ($akhir+$akhir)) : ($akhir);
			$awal_set      = ($awal > 0) ?($awal+1) : ($awal);
			$where_row_num = ($filter=="") ? " AND RowNum BETWEEN '$awal_set' and '$akhir_set'" : "";
		}

        $where_id_nama   = (empty($id_nama)) ? "" : " AND (A.MsGroupId LIKE '%$id_nama%' OR A.MsGroupNama LIKE '%$id_nama%')";

        $sql = "SELECT * FROM (
                    SELECT ROW_NUMBER() OVER (ORDER BY A1.MsGroupNama) AS RowNum,A1.* 
                    FROM (
                        SELECT A.MsGroupId,A.MsGroupNama
                        FROM PayrollMnu_Group A 
                        WHERE 1=1
                        $where_id_nama
                    ) A1
                ) A2 WHERE 1=1 $where_row_num
                ORDER BY A2.MsGroupNama";

        $query = $this->db->query($sql);
        return $query;
    }

    function query_data()
    {
        $sql = $this->sql_list();
    }

    function count_filtered()
    {
        $sql = $this->sql_list('filter')->num_rows();
        return $sql;
    }

    public function count_all()
    {
        $kode_pegawai = $this->session->userdata("id_user");
        $sql          = "(SELECT A.MsGroupId FROM PayrollMnu_Group A WHERE 1=1) A1";
        $this->db->from($sql);
        return $this->db->count_all_results();
    }  

    function get_form_input(){
        $format = $this->input->post("format");
        $sql = $this->db->query("SELECT * FROM (
                                    SELECT A1.MsMenu_ID,group_menu = CASE WHEN A1.group_menu IS NULL THEN A1.nama_menu ELSE A1.group_menu END 
                                    ,nama_menu = CASE WHEN (SELECT TOP 1 X.MsMenu_Display FROM PayrollMnu X WHERE X.MsMenu_ID=A1.MsMenu_ID)=(CASE WHEN A1.group_menu IS NULL THEN A1.nama_menu ELSE A1.group_menu END) THEN '' ELSE A1.nama_menu END
                                    ,A1.deskripsi_menu
                                    FROM (
                                    SELECT A.MsMenu_ID,group_menu = (SELECT TOP 1 X.MsMenu_Display FROM PayrollMnu X WHERE X.MsMenu_ID=A.MsMenu_parent1),nama_menu = A.MsMenu_Display,deskripsi_menu = A.MsMenuDeskripsi
                                    FROM PayrollMnu A 
                                    WHERE A.MsMenuAktif=1 
                                
                                    ) A1 
                                ) A2 ORDER BY A2.group_menu,A2.nama_menu");

        $sql_max_id = $this->db->query("SELECT IDGROUP = MAX(A.MsGroupId)+1 FROM PayrollMnu_Group A");
        
        $data["list_menu"] = $sql;
        $data["max_id"]    = $sql_max_id;
        $this->load->view("f_add_group_user",$data);
    }

    function cek_nama_group(){
        $nama_group     = $this->input->post("nama_group");
        $nama_group_old = $this->input->post("nama_group_old");
        $edit           = $this->input->post("edit");
        $nama_group_set = preg_replace('/\s/', '', $nama_group);
        if(($nama_group==$nama_group_old) AND ($edit=="edit")){
            $pesan["pesan"] = "ok";
        } else {
            $cek   = $this->db->query("SELECT REPLACE(A.MsGroupNama,' ','') FROM PayrollMnu_Group A WHERE REPLACE(A.MsGroupNama,' ','')='$nama_group_set'");
            $pesan["pesan"] = ($cek->num_rows() > 0) ? "ada" : "ok";
        }
        echo json_encode($pesan);
    }

    function simpan_data(){
        $max_id         = $this->input->post("max_id");
        $nama_group     = strtoupper($this->input->post("nama_group"));
        $pilih_menu_set = $this->input->post("pilih_menu_set");

        $this->db->trans_start();

        $data_group = ["MsGroupId" => $max_id,
                        "MsGroupNama" => $nama_group,
                    ];
        $sql_group = $this->db->insert("PayrollMnu_Group",$data_group);

        foreach ($pilih_menu_set as $key => $value) {
            $pilih_menu_cek = $pilih_menu_set[$key];
            if(strlen($pilih_menu_cek) > 7){
                
                $get_group_menu = $this->db->query("SELECT A.MsMenu_parent1 FROM PayrollMnu A WHERE A.MsMenu_ID='$pilih_menu_cek'");
                $id_menu_head   = $get_group_menu->row()->MsMenu_parent1;
                $sql = $this->db->query("INSERT INTO PayrollMnu_GroupMenu(MsGroupId, MsMenu_ID) VALUES('$max_id','$pilih_menu_cek')");
            
                if(strlen($id_menu_head) > 7){
                    $cek_head = $this->db->query("SELECT A.MsGroupId FROM PayrollMnu_GroupMenu A WHERE A.MsGroupId='$max_id' AND A.MsMenu_ID='$id_menu_head'");
                    if($cek_head->num_rows() == 0){
                        $sql = $this->db->query("INSERT INTO PayrollMnu_GroupMenu(MsGroupId, MsMenu_ID) VALUES('$max_id','$id_menu_head')");
                    }
                }
            }
        }
        $this->db->trans_complete();
        $pesan["pesan"] = ($this->db->trans_status()) ? "ok" : "gagal";
        echo json_encode($pesan);
    }

    function get_form_edit(){
        $idgroup  = $this->input->post("idgroup");
        $sql_head = $this->db->query("SELECT A.* FROM PayrollMnu_Group A 
                                    WHERE A.MsGroupId=$idgroup");

                                    
        $sql_menu = $this->db->query("SELECT * FROM (
                                        SELECT A1.MsMenu_ID,group_menu = CASE WHEN A1.group_menu IS NULL THEN A1.nama_menu ELSE A1.group_menu END 
                                        ,nama_menu = CASE WHEN (SELECT TOP 1 X.MsMenu_Display FROM PayrollMnu X WHERE X.MsMenu_ID=A1.MsMenu_ID)=(CASE WHEN A1.group_menu IS NULL THEN A1.nama_menu ELSE A1.group_menu END) THEN '' ELSE A1.nama_menu END
                                        ,A1.deskripsi_menu
                                        ,ID_GROUP_MENU = (SELECT X.MsMenu_ID FROM PayrollMnu_GroupMenu X WHERE X.MsMenu_ID=A1.MsMenu_ID AND X.MsGroupId=$idgroup)
                                        FROM (
                                        SELECT A.MsMenu_ID,group_menu = (SELECT TOP 1 X.MsMenu_Display FROM PayrollMnu X WHERE X.MsMenu_ID=A.MsMenu_parent1),nama_menu = A.MsMenu_Display,deskripsi_menu = A.MsMenuDeskripsi
                                        FROM PayrollMnu A 
                                        WHERE A.MsMenuAktif=1 
                                    
                                        ) A1 
                                    ) A2 ORDER BY A2.group_menu,A2.nama_menu");
        $data["list_head"] = $sql_head;
        $data["list_menu"] = $sql_menu;

        $this->load->view("f_edit_group_user",$data);
    }

    function update_data(){
        $max_id         = $this->input->post("max_id");
        $nama_group     = strtoupper($this->input->post("nama_group"));
        $nama_group_old = strtoupper($this->input->post("nama_group_old"));
        $pilih_menu_set = $this->input->post("pilih_menu_set");

        $this->db->trans_start();
        
        $delete_data = $this->db->query("DELETE A FROM PayrollMnu_GroupMenu A WHERE A.MsGroupId='$max_id'");
        $data_group  = ["MsGroupNama" => $nama_group,
                    ];
        $sql_group   = $this->db->update("PayrollMnu_Group",$data_group,["MsGroupId" => $max_id]);
        

        foreach ($pilih_menu_set as $key => $value) {
            $pilih_menu_cek = $pilih_menu_set[$key];
            if(strlen($pilih_menu_cek) > 7){
                
                $get_group_menu = $this->db->query("SELECT A.MsMenu_parent1 FROM PayrollMnu A WHERE A.MsMenu_ID='$pilih_menu_cek'");
                $id_menu_head   = $get_group_menu->row()->MsMenu_parent1;
                $sql = $this->db->query("INSERT INTO PayrollMnu_GroupMenu(MsGroupId, MsMenu_ID) VALUES('$max_id','$pilih_menu_cek')");
            
                if(strlen($id_menu_head) > 7){
                    $cek_head = $this->db->query("SELECT A.MsGroupId FROM PayrollMnu_GroupMenu A WHERE A.MsGroupId='$max_id' AND A.MsMenu_ID='$id_menu_head'");
                    if($cek_head->num_rows() == 0){
                        $sql = $this->db->query("INSERT INTO PayrollMnu_GroupMenu(MsGroupId, MsMenu_ID) VALUES('$max_id','$id_menu_head')");
                    }
                }
            }
        }
        $this->db->trans_complete();
        $pesan["pesan"] = ($this->db->trans_status()) ? "ok" : "gagal";
        echo json_encode($pesan);
    }
	
}
