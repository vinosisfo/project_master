<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_user extends MX_Controller  {
	
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
        $this->load->view("f_user",$data);
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
            $row [] = '<button type="button" class="btn btn-info btn-xs" onclick="edit_data(\''.$field->HrdMsKaryawan_Id.'\')">Edit</button>';
            $row [] = '<p style="text-align : left;">'.$field->HrdMsKaryawan_Id.'</p>';
            $row [] = '<p style="text-align : left;">'.$field->HrdMsKaryawan_Nama.'</p>';
            $row [] = '<p style="text-align : left;">'.$field->NamaDepartemen.'</p>';
            $row [] = '<p style="text-align : left;">'.$field->NamaBagian.'</p>';
            $row [] = '<p style="text-align : left;">'.$field->NamaJabatan.'</p>';

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

        $where_id_nama   = (empty($id_nama)) ? "" : " AND (X1.HrdMsKaryawan_Id LIKE '%$id_nama%' OR X1.HrdMsKaryawan_Nama LIKE '%$id_nama%')";

        $sql = "SELECT * FROM (
                    SELECT ROW_NUMBER() OVER (ORDER BY A1.HrdMsKaryawan_Nama) AS RowNum,A1.* 
                    FROM (
                        SELECT * FROM (
                            SELECT DISTINCT B.HrdMsKaryawan_Id,B.HrdMsKaryawan_Nama,B.NamaDepartemen,B.NamaBagian,B.NamaJabatan 
                            FROM _MsUser A 
                            INNER JOIN vw_mskaryawan_aktif B ON B.HrdMsKaryawan_Id=A.MsUser_Id
                            INNER JOIN PayrollMnu_USer C ON C.KdPegawai=A.MsUser_Id
                            WHERE 1=1

                            UNION ALL
                            SELECT DISTINCT A.UserMsAccounting_Id,A.UserMsAccounting_Nama,Departemen='ACC',Bagian='ACC',Jabatan='ACC' 
                            FROM UserMsAccounting A
                            INNER JOIN PayrollMnu_USer C ON C.KdPegawai=A.UserMsAccounting_Id
                        ) X1 WHERE 1=1 $where_id_nama
                    ) A1
                ) A2 WHERE 1=1 $where_row_num
                ORDER BY A2.HrdMsKaryawan_Nama";

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
        $sql          = $this->db->query("SELECT * FROM (SELECT DISTINCT B.HrdMsKaryawan_Id,B.HrdMsKaryawan_Nama,B.NamaDepartemen,B.NamaBagian,B.NamaJabatan 
                        FROM _MsUser A 
                        INNER JOIN vw_mskaryawan_aktif B ON B.HrdMsKaryawan_Id=A.MsUser_Id
                        INNER JOIN PayrollMnu_USer C ON C.KdPegawai=A.MsUser_Id

                        UNION ALL 

                        SELECT DISTINCT A.UserMsAccounting_Id,A.UserMsAccounting_Nama,'ACC' AS DEPT,'ACC' AS BAGIAN,'ACC' AS JABATAN 
                        FROM UserMsAccounting A
                        INNER JOIN PayrollMnu_USer C ON C.KdPegawai=A.UserMsAccounting_Id) A1");
        // $this->db->from($sql);
        $row = $sql->num_rows();
        return $row;
    }  

    function get_form_input(){
        $format = $this->input->post("format");
        $sql = $this->db->query("SELECT DISTINCT A.MsGroupId,A.MsGroupNama 
                                FROM PayrollMnu_Group A WHERE 1=1
                                ORDER BY A.MsGroupNama");
        
        $data_user = $this->db->query("SELECT * FROM (SELECT DISTINCT B.HrdMsKaryawan_Id,B.HrdMsKaryawan_Nama,B.NamaDepartemen,B.NamaBagian,B.NamaJabatan 
                                        FROM _MsUser A 
                                        INNER JOIN vw_mskaryawan_aktif B ON B.HrdMsKaryawan_Id=A.MsUser_Id
                                        INNER JOIN _Ms_User_GroupUser C ON C.Ms_User_GroupUser_IdUser=A.MsUser_Id 
                                        WHERE 1=1 AND C.Ms_User_GroupUser_IdGroup='54' 
                                        AND B.HrdMsKaryawan_Id NOT IN (SELECT DISTINCT X.KdPegawai FROM PayrollMnu_USer X)
                                        UNION ALL
                                        SELECT DISTINCT A.UserMsAccounting_Id,A.UserMsAccounting_Nama,Departemen='',BagianBarang='',Jabatan='' 
                                        FROM UserMsAccounting A
                                        INNER JOIN _Ms_User_GroupUser C ON C.Ms_User_GroupUser_IdUser=A.UserMsAccounting_Id 
                                        WHERE 1=1 AND C.Ms_User_GroupUser_IdGroup='54' 
                                        AND A.UserMsAccounting_Id NOT IN (SELECT DISTINCT X.KdPegawai FROM PayrollMnu_USer X)
                                        ) A1
                                        ORDER BY A1.HrdMsKaryawan_Nama");
        $data["list_menu"] = $sql;
        $data["data_user"] = $data_user;
        $this->load->view("f_add_user",$data);
    }

    function simpan_data(){
        $id_user        = $this->input->post("id_user");
        $pilih_menu_set = $this->input->post("pilih_menu_set");

        $this->db->trans_start();
            $delete_data = $this->db->query("DELETE A FROM PayrollMnu_USer A WHERE A.KdPegawai='$id_user'");
            foreach ($pilih_menu_set as $key => $value) {
                $pilih_menu_cek = $pilih_menu_set[$key];
                if(strlen($pilih_menu_cek) > 0){
                    $sql = $this->db->query("INSERT INTO PayrollMnu_USer(MsGroupId, KdPegawai) VALUES('$pilih_menu_cek','$id_user')");
                }
            }
        $this->db->trans_complete();
        $pesan["pesan"] = ($this->db->trans_status()) ? "ok" : "gagal";
        echo json_encode($pesan);
    }

    function get_user_detail(){
        $id_user = $this->input->post("id_user");

        $sql = $this->db->query("SELECT DISTINCT B.HrdMsKaryawan_Id,B.HrdMsKaryawan_Nama,B.NamaDepartemen,B.NamaBagian,B.NamaJabatan 
                                FROM _MsUser A 
                                INNER JOIN vw_mskaryawan_aktif B ON B.HrdMsKaryawan_Id=A.MsUser_Id
                                WHERE B.HrdMsKaryawan_Id='$id_user'
                                ORDER BY B.HrdMsKaryawan_Nama");
        $data["nama"]    = $sql->row()->HrdMsKaryawan_Nama;
        $data["dept"]    = $sql->row()->NamaDepartemen;
        $data["bagian"]  = $sql->row()->NamaBagian;
        $data["jabatan"] = $sql->row()->NamaJabatan;

        echo json_encode($data);
    }

    function get_form_edit(){
        $id_user  = $this->input->post("id_user");
        $sql_head = $this->db->query("SELECT A.HrdMsKaryawan_Id,A.HrdMsKaryawan_Nama 
                                    FROM vw_mskaryawan_aktif A WHERE A.HrdMsKaryawan_Id='$id_user'
                                    UNION ALL 
                                    SELECT A.UserMsAccounting_Id,A.UserMsAccounting_Nama
                                    FROM UserMsAccounting A WHERE A.UserMsAccounting_Id='$id_user'");

                                    
        $sql_menu = $this->db->query("SELECT DISTINCT A.MsGroupId,A.MsGroupNama,
                                    id_group_user = (SELECT X.MsGroupId FROM PayrollMnu_USer X 
                                                    WHERE X.MsGroupId=A.MsGroupId AND X.KdPegawai='$id_user') 
                                    FROM PayrollMnu_Group A WHERE 1=1
                                    ORDER BY A.MsGroupNama");

        $data["list_head"] = $sql_head;
        $data["list_menu"] = $sql_menu;

        $this->load->view("f_edit_user",$data);
    }

    function update_data(){
        $id_user        = $this->input->post("id_user");
        $pilih_menu_set = $this->input->post("pilih_menu_set");

        $this->db->trans_start();
        
        $delete_data = $this->db->query("DELETE A FROM PayrollMnu_USer A WHERE A.KdPegawai='$id_user'");

        foreach ($pilih_menu_set as $key => $value) {
            $pilih_menu_cek = $pilih_menu_set[$key];
            if(strlen($pilih_menu_cek) > 0){
                $sql = $this->db->query("INSERT INTO PayrollMnu_USer(MsGroupId, KdPegawai) VALUES('$pilih_menu_cek','$id_user')");
                
            }
        }
        $this->db->trans_complete();
        $pesan["pesan"] = ($this->db->trans_status()) ? "ok" : "gagal";
        echo json_encode($pesan);
    }
	
}
