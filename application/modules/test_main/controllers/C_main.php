<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_main extends MX_Controller  {
	
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
		$data["main_content"] = 'v_main';
        $this->load->view("template/bar/main_content",$data);
        // test edit disini
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
            $row [] = '<button type="button" onclick="test_modal(this)" class="btn btn-info btn-xs">Test</button>';
            $row [] = '<p style="text-align : left; margin-right:10px;">'.$field->IdUser.'</p>';
            $row [] = '<p style="text-align : left; margin-right:10px;">'.$field->Password.'</p>';
            $row [] = '<p style="text-align : left; margin-right:10px;">'.$field->Username.'</p>';
            $row [] = '<p style="text-align : left; margin-right:10px;">'.$field->Aktif.'</p>';
            for ($i=1; $i <=15 ; $i++) {
                $row [] = '<p style="text-align : left; margin-right:10px;">'.$field->Aktif.'</p>';
            }
            
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
        $kode_pegawai = $this->session->userdata("kode_pegawai");
        
        $sql = "(SELECT * FROM tbuser A
                WHERE 1=1 ) A1";
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
	
}
