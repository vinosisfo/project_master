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
		$data["main_content"] = 'v_satuan';
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
            $row [] = '<button type="button" onclick="edit_data('.$field->IdSatuan.')" class="btn btn-info btn-xs">Edit</button>';
            $row [] = '<p style="text-align : left; margin-right:10px;">'.$field->NamaSatuan.'</p>';
            $row [] = '<p style="text-align : left; margin-right:10px;">'.$field->Status_aktif.'</p>';
            
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
        $status_aktif_src = $this->input->post("status_aktif_src");

        $where_nama   = (empty($nama_src)) ? "" : " AND A.NamaSatuan LIKE '%$nama_src%'";
        $where_status = (strlen($status_aktif_src)==0) ? "" : " AND A.Aktif='$status_aktif_src'";

        $sql = "(SELECT A.IdSatuan,A.NamaSatuan,A.Aktif,(CASE WHEN A.Aktif=1 THEN 'Ya' ELSE 'Tdk' END) AS Status_aktif
                ,B.Username,A.TglInput,IFNULL(C.Username,'') as user_edit,A.TglEdit 
                FROM satuan A
                INNER JOIN tbuser B ON B.IdUser=A.UserInput
                LEFT JOIN tbuser C ON C.IdUser=A.UserEdit
                WHERE 1=1 
                $where_nama
                $where_status) A1";
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
        $tgl             = date("Y-m-d H:i:s");
        $user_id         = $this->session->userdata("iduser");
        $nama_satuan     = strtoupper($this->input->post("nama_satuan"));
        $nama_satuan_cek = preg_replace('/\s+/', '', $nama_satuan);

        $this->db->trans_start();

        $cek_satuan = $this->db->query("SELECT A.IdSatuan 
                                        FROM satuan A 
                                        WHERE replace(A.NamaSatuan,' ', '')='$nama_satuan_cek'");
        
        $status = "";
        if($cek_satuan->num_rows() > 0){
            $status = "duplikat";
        } else {
            $status = "";
            $data = [
                    "NamaSatuan" => $nama_satuan,
                    "TglInput"   => $tgl,
                    "UserInput"  => $user_id,
                    "Aktif"      => 1
                    ];
            
            $simpan_data = $this->db->insert("satuan",$data);
        }

        $this->db->trans_complete();
        $pesan = ($this->db->trans_status()) ? "ok" : "gagal";

        $hasil["pesan"]  = $pesan;
        $hasil["status"] = $status;

        echo json_encode($hasil);
    }

    function edit_data(){
        $id_satuan = $this->input->post("id_satuan");
        $sql       = $this->db->query("SELECT A.IdSatuan,A.NamaSatuan,A.Aktif 
                                        FROM satuan A 
                                        WHERE A.IdSatuan='$id_satuan'");
        
        $data["id_satuan"] = $id_satuan;
        $data["list"]      = $sql;
        $this->load->view("v_edit_data",$data);
    }

    function update_data(){
        $tgl             = date("Y-m-d H:i:s");
        $user_id         = $this->session->userdata("iduser");
        $id_satuan       = strtoupper($this->input->post("id_satuan"));
        $nama_satuan     = strtoupper($this->input->post("nama_satuan"));
        $status_aktif    = strtoupper($this->input->post("status_aktif"));
        $nama_satuan_cek = preg_replace('/\s+/', '', $nama_satuan);

        $this->db->trans_start();

            $cek_satuan = $this->db->query("SELECT A.IdSatuan 
                                            FROM satuan A 
                                            WHERE replace(A.NamaSatuan,' ', '')='$nama_satuan_cek'
                                            AND A.Aktif='$status_aktif'");
            
            $status = "";
            if($cek_satuan->num_rows() > 0){
                $status = "duplikat";
            } else {
                $status = "";
                $data = [
                        "NamaSatuan" => $nama_satuan,
                        "TglEdit"    => $tgl,
                        "UserEdit"   => $user_id,
                        "Aktif"      => $status_aktif
                        ];
                
                $simpan_data = $this->db->update("satuan",$data,["IdSatuan" => $id_satuan]);
            }
        $this->db->trans_complete();
        $pesan = ($this->db->trans_status()) ? "ok" : "gagal";

        $hasil["pesan"]  = $pesan;
        $hasil["status"] = $status;

        echo json_encode($hasil);
    }
	
}
