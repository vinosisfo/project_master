<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('template/css/css');
		$this->load->view('template/bar/nav_bar');
		$this->load->view('template/bar/menu');
		$this->load->view('template/js/js');

		$this->load->view('template/bar/body');
		$this->load->view('template/bar/footer');
		

	}

    public function index_2()
    {
        $this->load->view('template/css/css');
        $this->load->view('template/bar/nav_bar');
        $this->load->view('template/bar/menu');
        $this->load->view('template/js/js');

        $this->load->view('template/bar/table_bar');
        $this->load->view('template/bar/footer');
        

    }

    public function login($pesan="")
    {
        // $this->load->view('template/css/css');
        // $this->load->view('template/bar/nav_bar');
        // $this->load->view('template/bar/menu');
        // $this->load->view('template/js/js');
        $data["pesan"] = $pesan;
        $this->load->view('login',$data);
        // $this->load->view('template/bar/footer');
        

    }

    function akses_login(){
        $username = $this->input->post("username");
        $password = $this->input->post("password");

        $password_set = md5($password);

        $sql = $this->db->select("*")
                        ->from("tbuser A")
                        ->where("A.Username",$username)
                        ->where("A.Password",$password)
                        ->where("A.Aktif","ya")
                        ->get();

        if($sql->num_rows() > 0){
            $data = $sql->row();

            $sess_data['iduser']   = $data->IdUser;
            $sess_data['password'] = $data->Password;
            $sess_data['username'] = $data->Username;
            $this->session->set_userdata($sess_data);

            $ip_address     = $this->get_client_ip();
            $nama_perangkat = gethostbyaddr($_SERVER['REMOTE_ADDR']);
            $now            = date("Y-m-d H:i:s");
            redirect(base_url('welcome'));
        } else {
            $this->login('salah');
        }
    }

    function get_client_ip() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else 

        if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];

        else
            $ipaddress = 'UNKNOWN';
        
            // var_dump(gethostbyaddr($_SERVER['REMOTE_ADDR']));die();
        return $ipaddress;
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
        $sql = "(SELECT * FROM tbuser A WHERE 1=1 ) A1";
        $this->db->from($sql);
        return $this->db->count_all_results();
    }  

    function insert_batch(){
    	for ($i=1; $i <=20 ; $i++) { 
    		$sql = [
    				"username" => 'user_name_'.$i,
    				"password" => 'pasword'.$i,
    				"aktif" => 'ya',
    				];
    		$this->db->insert("tbuser",$sql);
    	}
    }
}
