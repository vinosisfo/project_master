<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class IndexLog extends CI_Controller {

	function index()
	{
		$this->login('');
	}

    function login($pesan="")
    {
        $data["pesan"] = $pesan;
        $this->load->view('login',$data);
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
            $data           = $sql->row();
            $ip_address     = $this->get_client_ip();
            $nama_perangkat = gethostbyaddr($_SERVER['REMOTE_ADDR']);
            $now            = date("Y-m-d H:i:s");

            $sess_data['iduser']   = $data->IdUser;
            $sess_data['password'] = $data->Password;
            $sess_data['username'] = $data->Username;
            $this->session->set_userdata($sess_data);

            redirect(base_url('mainview'));
        } else {
            $this->login('Error');
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
        return $ipaddress;
    }

    function logout_proses(){
        $this->session->sess_destroy();
		$this->login();
    }
}
