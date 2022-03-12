<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_bagian extends MX_Controller  {
	
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
        $back_menu = $this->get_support_lib_new->get_url_set();
        $data["back_menu"]  = $back_menu;
		$data["main_content"] = 'v_bagian';
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
            $row [] = '<button type="button" onclick="edit_data(\''.$field->IdBagianDepartemen.'\')" class="btn btn-info btn-xs">Edit</button>';
            $row [] = '<p style="text-align : left; margin-right:7px;">'.$field->NamaDepartemen.'</p>';
            $row [] = '<p style="text-align : left; margin-right:7px;">'.$field->Singkatan_Dept.'</p>';
            $row [] = '<p style="text-align : left; margin-right:7px;">'.$field->Aktif_Dept.'</p>';
            $row [] = '<p style="text-align : left; margin-right:7px;">'.$field->NamaBagian.'</p>';
            $row [] = '<p style="text-align : left; margin-right:7px;">'.$field->SingkatanBagian.'</p>';
            $row [] = '<p style="text-align : left; margin-right:7px;">'.$field->Aktif_Bagian.'</p>';
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
        $this->db->order_by("NamaDepartemen");
        $query = $this->db->get();
        return $query->result();
    }

    function query_data()
    {
        $nama_src         = $this->input->post("nama_src");
        $singkatan_src    = $this->input->post("singkatan_src");
        $status_aktif_src = $this->input->post("status_aktif_src");

        $where_nama      = (empty($nama_src)) ? "" : " AND A.NamaDepartemen LIKE '%$nama_src%'";
        $where_singkatan = (empty($singkatan_src)) ? "" : " AND A.Singkatan LIKE '%$singkatan_src%'";
        $where_status    = (strlen($status_aktif_src)==0) ? "" : " AND A.Aktif='$status_aktif_src'";

        $sql = "(SELECT A.IdDepartemen,A.NamaDepartemen,A.Singkatan AS Singkatan_Dept ,A.Aktif AS Aktif_Dept,B.IdBagianDepartemen,B.NamaBagian,B.SingkatanBagian
                ,B.Aktif AS Aktif_Bagian
                FROM departemen A 
                INNER JOIN bagiandepartemen B ON B.IdDepartemen=A.IdDepartemen
                WHERE 1=1
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
        $dept  = $this->db->query("SELECT DISTINCT A.IdDepartemen,A.NamaDepartemen,A.Singkatan 
                                FROM departemen A WHERE A.Aktif=1 ORDER BY A.NamaDepartemen");

        $data["input"] = $input;
        $data["dept"]  = $dept;
        $this->load->view("v_input_data",$data);
    }

    function cek_data_bagian(){
        $departemen       = $this->input->post("departemen");
        $nama_bagian      = $this->input->post("nama_bagian");
        $singkatan_bagian = $this->input->post("singkatan_bagian");
        $aktif            = $this->input->post("status_aktif");
        $jenis_update     = $this->input->post("jenis_update");
        
        $nama_bagian_cek = preg_replace('/\s+/', '', $nama_bagian);
        
        $this->db->select("A.IdBagianDepartemen");
        $this->db->from("bagiandepartemen A");
        $this->db->where("A.IdDepartemen",$departemen);
        $this->db->where_in("replace(A.NamaBagian,' ', '')",$nama_bagian_cek);
        if($jenis_update=="update"){
            $this->db->where_in("A.Aktif",$aktif);
        }
        $cek_data = $this->db->get();
        
        $hasil["hasil"] = ($cek_data->num_rows() > 0) ? "ada" : "ok";
        echo json_encode($hasil);
    }

    function cek_data_duplikat(){
        $nama_bagian      = $this->input->post("nama_bagian");
        $singkatan_bagian = $this->input->post("singkatan_bagian");

        $cek_data = array();
        foreach ($nama_bagian as $key => $value) {
            $nama_barang_cek = preg_replace('/\s+/', '', $nama_bagian[$key]);
            if (strlen($nama_barang_cek) > 0)
            {
                $cek_data[] = strtoupper($nama_bagian[$key]);
            }
            
        }
        $get_data = array_diff_key($cek_data, array_unique($cek_data));
        $pesan["hasil"] 	= (empty($get_data)) ? "ok" : "ada";
        echo json_encode($pesan);
    }

    function simpan_data(){
        $tgl              = date("Y-m-d H:i:s");
        $tahun            = date('y');
        $bulan            = date('m');
        $hari             = date('d');
        $user_id          = $this->session->userdata("iduser");
        $no_urut          = $this->input->post("no_urut");
        $departemen       = $this->input->post("departemen");
        $nama_bagian      = $this->input->post("nama_bagian");
        $singkatan_bagian = $this->input->post("singkatan_bagian");

        $this->db->trans_start();
            $status = "";
            $detail = [];
            $no     = 0;
            foreach ($nama_bagian as $key => $value) {
                $no++;
                $data_detail = [
                    "IdDepartemen"    => $departemen,
                    "NamaBagian"      => $nama_bagian[$key],
                    "SingkatanBagian" => $singkatan_bagian[$key],
                    "Aktif"           => 1,
                    "UserInput"       => $user_id,
                    "TglInput"        => $tgl,
                ];
                array_push($detail,$data_detail);
            }

            $simpan_data_detail = $this->db->insert_batch("bagiandepartemen",$detail);

        $this->db->trans_complete();
        $pesan = ($this->db->trans_status()) ? "ok" : "gagal";

        $hasil["pesan"]  = $pesan;
        $hasil["status"] = $status;

        echo json_encode($hasil);
    }

    function edit_data(){
        $kode = $this->input->post("kode");

        $sql       = $this->db->query("SELECT A.IdDepartemen,A.NamaDepartemen,A.Singkatan AS Singkatan_Dept 
                                    ,A.Aktif AS Aktif_Dept,B.IdBagianDepartemen,B.NamaBagian,B.SingkatanBagian
                                    ,B.Aktif AS Aktif_Bagian
                                    FROM departemen A 
                                    INNER JOIN bagiandepartemen B ON B.IdDepartemen=A.IdDepartemen
                                    WHERE 1=1
                                    AND B.IdBagianDepartemen='$kode'");
        
        $data["kode"]   = $kode;
        $data["list"]   = $sql;
        $this->load->view("v_edit_data",$data);
    }

    function update_data(){
        $tgl              = date("Y-m-d H:i:s");
        $tahun            = date('y');
        $bulan            = date('m');
        $hari             = date('d');
        $user_id          = $this->session->userdata("iduser");
        $no_urut          = $this->input->post("no_urut");
        $departemen       = $this->input->post("departemen");
        $id_bagian        = $this->input->post("id_bagian");
        $nama_bagian      = $this->input->post("nama_bagian");
        $singkatan_bagian = $this->input->post("singkatan_bagian");
        $status_aktif     = $this->input->post("status_aktif");

        $this->db->trans_start();
            $status = "";
            $detail = [];
            $no     = 0;
            foreach ($nama_bagian as $key => $value) {
                $no++;
                $data_detail = [
                    "IdbagianDepartemen" => $id_bagian[$key],
                    "IdDepartemen"       => $departemen,
                    "NamaBagian"         => $nama_bagian[$key],
                    "SingkatanBagian"    => $singkatan_bagian[$key],
                    "Aktif"              => $status_aktif[$key],
                    "UserInput"          => $user_id,
                    "TglInput"           => $tgl,
                ];
                array_push($detail,$data_detail);
            }

            $simpan_data_detail = $this->db->update_batch("bagiandepartemen",$detail,"IdbagianDepartemen");

        $this->db->trans_complete();
        $pesan = ($this->db->trans_status()) ? "ok" : "gagal";

        $hasil["pesan"]  = $pesan;
        $hasil["status"] = $status;

        echo json_encode($hasil);
    }
	
}
