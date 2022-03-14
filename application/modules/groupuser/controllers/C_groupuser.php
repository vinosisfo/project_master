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
        $singkatan_src    = $this->input->post("singkatan_src");
        $status_aktif_src = $this->input->post("status_aktif_src");

        $where_nama      = (empty($nama_src)) ? "" : " AND A.NamaDepartemen LIKE '%$nama_src%'";
        $where_singkatan = (empty($singkatan_src)) ? "" : " AND A.Singkatan LIKE '%$singkatan_src%'";
        $where_status    = (strlen($status_aktif_src)==0) ? "" : " AND A.Aktif='$status_aktif_src'";

        $sql = "(SELECT A.KodeGroupUser,A.NamaGroupUser,A.Aktif 
                FROM groupuser A WHERE 1 = 1
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
                                    ->where_in("replace(A.NamaGroupUser,' ', '')",$nama_group_cek)
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
        $no_urut    = $this->input->post("no_urut");
        $nama_group = $this->input->post("nama_group");

        $this->db->trans_start();
            $status = "";
            $detail = [];
            $no     = 0;
            foreach ($nama_group as $key => $value) {
                $no++;
                $this->db->select('RIGHT(A.KodeGroupUser,3) as kode', FALSE);
                $this->db->order_by('A.KodeGroupUser','DESC');    
                $this->db->limit(1);
                $query = $this->db->get("groupuser A");
                if($query->num_rows() <> 0){      
                    $data = $query->row();
                    $kode = intval($data->kode) + $no;
                }
                else 
                { 
                    $kode = $no;
                }
                $kodemax  = str_pad($kode, 3, "0", STR_PAD_LEFT);
                $kode_res = "GU".$tahun.$bulan.$hari.$kodemax;

                $data_detail = [
                    "KodeGroupUser" => $kode_res,
                    "NamaGroupUser" => $nama_group[$key],
                    "Aktif"         => 1,
                    "UserInput"     => $user_id,
                    "TglInput"      => $tgl,
                ];
                array_push($detail,$data_detail);
            }

            $simpan_data_detail = $this->db->insert_batch("groupuser",$detail);

        $this->db->trans_complete();
        $pesan = ($this->db->trans_status()) ? "ok" : "gagal";

        $hasil["pesan"]  = $pesan;
        $hasil["status"] = $status;

        echo json_encode($hasil);
    }

    function edit_data(){
        $kode = $this->input->post("kode");

        $sql       = $this->db->query("SELECT A.KodeGroupUser,A.NamaGroupUser,A.Aktif 
                                    FROM groupuser A 
                                    WHERE 1=1 AND A.KodeGroupUser='$kode'");
        
        $data["kode"] = $kode;
        $data["list"] = $sql;
        $this->load->view("v_edit_data",$data);
    }

    function update_data(){
        $tgl          = date("Y-m-d H:i:s");
        $tahun        = date('y');
        $bulan        = date('m');
        $hari         = date('d');
        $user_id      = $this->session->userdata("iduser");
        $no_urut      = $this->input->post("no_urut");
        $id_group     = $this->input->post("id_group");
        $nama_group   = $this->input->post("nama_group");
        $status_aktif = $this->input->post("status_aktif");

        $this->db->trans_start();
            $status = "";
            $detail = [];
            $no     = 0;
            foreach ($nama_group as $key => $value) {
                $no++;
                $data_detail = [
                    "KodeGroupUser" => $id_group[$key],
                    "NamaGroupUser" => $nama_group[$key],
                    "Aktif"         => $status_aktif[$key],
                    "UserEdit"     => $user_id,
                    "TglEdit"      => $tgl,
                ];
                array_push($detail,$data_detail);
            }

            $simpan_data_detail = $this->db->update_batch("groupuser",$detail,"KodeGroupUser");

        $this->db->trans_complete();
        $pesan = ($this->db->trans_status()) ? "ok" : "gagal";

        $hasil["pesan"]  = $pesan;
        $hasil["status"] = $status;

        echo json_encode($hasil);
    }
	
}
