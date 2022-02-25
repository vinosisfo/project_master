<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_rak extends MX_Controller  {
	
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
		$data["main_content"] = 'v_rak';
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
            $row [] = '<button type="button" onclick="edit_data('.$field->idRak.')" class="btn btn-info btn-xs">Edit</button>';
            $row [] = '<p style="text-align : left; margin-right:7px;">'.$field->NamaRak.'</p>';
            $row [] = '<p style="text-align : left; margin-right:7px;">'.$field->Alias.'</p>';
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
        $alias_src        = $this->input->post("alias_src");
        $status_aktif_src = $this->input->post("status_aktif_src");

        $where_nama   = (empty($nama_src)) ? "" : " AND A.NamaRak LIKE '%$nama_src%'";
        $where_alias  = (empty($alias_src)) ? "" : " AND B.Alias LIKE '%$alias_src%'";
        $where_status = (strlen($status_aktif_src)==0) ? "" : " AND A.Aktif='$status_aktif_src'";

        $sql = "(SELECT A.idRak,B.idRakDetail,A.NamaRak,A.Aktif,B.Alias,
                    A.TglInput,C.Username,A.TglEdit,(D.Username) UsernameEdit 
                    FROM rak A
                    INNER JOIN rakdetail B ON B.IdRak=A.idRak
                    LEFT JOIN tbuser C ON C.IdUser=A.UserInput
                    LEFT JOIN tbuser D ON D.IdUser=A.UserEdit
                    WHERE 1=1
                    $where_nama
                    $where_alias
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

        $this->load->view("v_input_data");
    }

    function simpan_data(){
        $tgl          = date("Y-m-d H:i:s");
        $user_id      = $this->session->userdata("iduser");
        $nama_rak     = strtoupper($this->input->post("nama_rak"));
        $nama_rak_cek = preg_replace('/\s+/', '', $nama_rak);

        $alias_rak     = $this->input->post("alias_rak");
        $alias_rak_cek = preg_replace('/\s+/', '', $alias_rak);

        $this->db->trans_start();

        $cek_duplikat = "(SELECT A.IdRak ,B.Alias
                        FROM rak A
                        INNER JOIN rakdetail B ON B.Idrak=A.IdRak 
                        WHERE replace(A.NamaRak,' ', '')='$nama_rak') A1";
        $cek_rak = $this->db->from($cek_duplikat)
                            ->get();
        
        $status = "";
        if($cek_rak->num_rows() > 0){
            $status = "duplikat";
        } else {
            $status = "";
            $data = [
                        "NamaRak"   => $nama_rak,
                        "TglInput"  => $tgl,
                        "UserInput" => $user_id,
                        "Aktif"     => 1
                    ];
            
            $simpan_data = $this->db->insert("rak",$data);
            $last_id     = $this->db->insert_id();

            $detail = [];
            foreach ($alias_rak as $key => $value) {
                $data_detail = [
                    "IdRak" => $last_id,
                    "Alias" => strtoupper($alias_rak[$key]),
                    "Aktif" => 1,
                ];
                array_push($detail,$data_detail);
            }

            $simpan_data_detail = $this->db->insert_batch("rakdetail",$detail);
        }

        $this->db->trans_complete();
        $pesan = ($this->db->trans_status()) ? "ok" : "gagal";

        $hasil["pesan"]  = $pesan;
        $hasil["status"] = $status;

        echo json_encode($hasil);
    }

    function edit_data(){
        $id_rak = $this->input->post("id_rak");
        $sql       = $this->db->query("SELECT A.idRak,B.idRakDetail,A.NamaRak,A.Aktif,B.Alias, B.Aktif as rak_alias_aktif
                                    FROM rak A
                                    INNER JOIN rakdetail B ON B.IdRak=A.idRak
                                    WHERE 1=1 
                                    AND A.IdRak='$id_rak'");
        
        $data["id_rak"] = $id_rak;
        $data["list"]   = $sql;
        $this->load->view("v_edit_data",$data);
    }

    function update_data(){
        $tgl          = date("Y-m-d H:i:s");
        $user_id      = $this->session->userdata("iduser");
        $id_rak       = strtoupper($this->input->post("id_rak"));
        $nama_rak     = strtoupper($this->input->post("nama_rak"));
        $nama_rak_cek = preg_replace('/\s+/', '', $nama_rak);

        $idrak_dt        = $this->input->post("idrak_dt");
        $alias_rak       = $this->input->post("alias_rak");
        $aktif_rak_alias = $this->input->post("aktif_rak_alias");
        $alias_rak_cek   = preg_replace('/\s+/', '', $alias_rak);

        $this->db->trans_start();
        $status = "";
        foreach ($alias_rak as $key => $value) {
            $nama_alias = strtoupper($alias_rak[$key]);
            if($idrak_dt[$key] =="i"){
                $sql = $this->db->query("INSERT INTO rakdetail(IdRak,Alias,Aktif) VALUES('$id_rak','$nama_alias','1')");
            } else {
                $sql = $this->db->query("UPDATE rakdetail A SET A.Alias='$nama_alias',A.Aktif='$aktif_rak_alias[$key]' 
                                        WHERE A.IdRak='$id_rak' AND A.idRakDetail='$idrak_dt[$key]'");
            }
        }
        $this->db->trans_complete();
        $pesan = ($this->db->trans_status()) ? "ok" : "gagal";

        $hasil["pesan"]  = $pesan;
        $hasil["status"] = $status;

        echo json_encode($hasil);
    }

    function cek_data_detail(){
        $alias_rak = $this->input->post("alias_rak");
        $cek_data = array();
        foreach ($alias_rak as $key => $value) {
            if (!empty($alias_rak[$key]))
            {
                $cek_data[] = strtoupper($alias_rak[$key]);
            }
            
        }
        $get_data = array_diff_key($cek_data, array_unique($cek_data));
        $pesan["pesan"] 	= (empty($get_data)) ? "ok" : "ada";
        echo json_encode($pesan);

    }
	
}
