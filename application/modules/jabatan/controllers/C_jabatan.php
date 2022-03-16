<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_jabatan extends MX_Controller  {
	
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

        $data["back_menu"]    = $back_menu;
        $data["main_content"] = 'v_jabatan';
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
            $row [] = '<button type="button" onclick="edit_data('.$field->IdJabatan.')" class="btn btn-info btn-xs">Edit</button>';
            $row [] = '<p style="text-align : left; margin-right:10px;">'.$field->Namajabatan.'</p>';
            $row [] = '<p style="text-align : left; margin-right:10px;">'.$field->NoUrut.'</p>';
            $row [] = '<p style="text-align : left; margin-right:10px;">'.$field->Aktif.'</p>';
            
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
        $this->db->order_by("NoUrut,Namajabatan");
        $query = $this->db->get();
        return $query->result();
    }

    function query_data()
    {
        $jabatan_src      = $this->input->post("jabatan_src");
        $status_aktif_src = $this->input->post("status_aktif_src");

        $where_jabatan = (empty($jabatan_src)) ? "" : " AND A.Namajabatan LIKE '%$jabatan_src%'";
        $where_status  = (strlen($status_aktif_src)==0) ? "" : " AND A.Aktif='$status_aktif_src'";

        $sql = "(SELECT A.IdJabatan,A.Namajabatan,A.NoUrut,A.Aktif 
                FROM jabatan A WHERE 1=1
                $where_jabatan
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

    function cek_data(){
        $nama_jabatan     = $this->input->post("nama_jabatan");
        $nama_jabatan_cek = preg_replace('/\s+/', '', $nama_jabatan);
        $jenis            = $this->input->post("jenis");
        $status_aktif     = $this->input->post("status_aktif");
        $nourut           = $this->input->post("nourut");

        $where_jenis = ($jenis=="update") ? " AND A.Aktif='$status_aktif' AND A.NoUrut='$nourut'" : "";
        $cek_satuan  = $this->db->query("SELECT A.IdJabatan 
                                        FROM jabatan A 
                                        WHERE 1=1
                                        AND replace(A.NamaJabatan,' ', '')='$nama_jabatan_cek'
                                        $where_jenis");
        
        $hasil["status"] = ($cek_satuan->num_rows() > 0) ? "ada" : "ok";
        echo json_encode($hasil);
    }

    function simpan_data(){
        $tgl          = date("Y-m-d H:i:s");
        $user_id      = $this->session->userdata("iduser");
        $nama_jabatan = strtoupper($this->input->post("nama_jabatan"));
        $nourut       = str_replace(",","",$this->input->post("nourut"));
        

        $this->db->trans_start();
            $status = "";
            $data = [
                    "NamaJabatan" => $nama_jabatan,
                    "NoUrut"      => $nourut,
                    "UserInput"   => $user_id,
                    "Aktif"       => 1
                    ];
            
            $simpan_data = $this->db->insert("jabatan",$data);
        $this->db->trans_complete();
        $pesan = ($this->db->trans_status()) ? "ok" : "gagal";

        $hasil["pesan"]  = $pesan;
        $hasil["status"] = $status;

        echo json_encode($hasil);
    }

    function edit_data(){
        $kode = $this->input->post("kode");
        $sql  = $this->db->query("SELECT A.IdJabatan,A.Namajabatan,A.NoUrut,A.Aktif 
                                        FROM jabatan A WHERE 1=1 AND A.IdJabatan='$kode'");
        
        $data["kode"] = $kode;
        $data["list"] = $sql;
        $this->load->view("v_edit_data",$data);
    }

    function update_data(){
        $tgl          = date("Y-m-d H:i:s");
        $user_id      = $this->session->userdata("iduser");
        $kode         = $this->input->post("kode");
        $nama_jabatan = strtoupper($this->input->post("nama_jabatan"));
        $nourut       = str_replace(",","",$this->input->post("nourut"));
        $status_aktif = $this->input->post("status_aktif");
        

        $this->db->trans_start();
            $status = "";
            $data = [
                        "NamaJabatan" => $nama_jabatan,
                        "NoUrut"      => $nourut,
                        "UserInput"   => $user_id,
                        "Aktif"       => $status_aktif,
                    ];
            
            $simpan_data = $this->db->update("jabatan",$data,["IdJabatan" => $kode]);
        $this->db->trans_complete();
        $pesan = ($this->db->trans_status()) ? "ok" : "gagal";

        $hasil["pesan"]  = $pesan;
        $hasil["status"] = $status;

        echo json_encode($hasil);
    }
	
}
