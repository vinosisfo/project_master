<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MainView extends MX_Controller  {
	
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
		$this->load->view('template/css/css');
		$this->load->view('template/bar/nav_bar');
		$this->load->view('template/bar/menu');
		$this->load->view('template/js/js');

		$this->load->view('template/bar/body');
		$this->load->view('template/bar/footer');

	}
}
?>