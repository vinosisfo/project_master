<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Group_menu extends MX_Controller  {
	
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

    function master_hrd($kode_menu){
        $sql_nama = $this->sql_nama_menu($kode_menu);
        $sql      = $this->sql_menu_detail($kode_menu);

        $data["nama_menu"] = ($sql_nama->num_rows() > 0) ? ($sql_nama->row()->NamaMenu) : "";
        $data["list"]      = $sql;
        $this->load->view("template/bar/main_content_group",$data);
    }

    function master_gudang($kode_menu){
        $sql_nama = $this->sql_nama_menu($kode_menu);
        $sql      = $this->sql_menu_detail($kode_menu);

        $data["nama_menu"] = ($sql_nama->num_rows() > 0) ? ($sql_nama->row()->NamaMenu) : "";
        $data["list"]      = $sql;
        $this->load->view("template/bar/main_content_group",$data);
    }

    function sql_nama_menu($kode_menu){
        $sql = $this->db->query("SELECT DISTINCT A.KodeMenu,A.NamaMenu,A.SubMenu,A.UrlMenu 
                            FROM menu A 
                            WHERE 1=1
                            AND A.Aktif=1
                            AND A.KodeMenu='$kode_menu'");
        return $sql;
    }

    function sql_menu_detail($kode_menu=''){
        $sql = $this->db->query("SELECT DISTINCT A.KodeMenu,A.NamaMenu,A.SubMenu,A.UrlMenu 
                            FROM menu A 
                            WHERE 1=1
                            AND A.Aktif=1
                            AND A.DetailMenu='$kode_menu'
                            ORDER BY A.NoUrut");
        return $sql;
    }
}
?>