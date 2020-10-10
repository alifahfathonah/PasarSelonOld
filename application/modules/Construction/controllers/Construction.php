<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Construction extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		if($this->session->userdata('login')==false){
			redirect(base_url().'Login');
		}

	}

	public function index()
	{
		
		$data['title'] = "Under Construction";
		$data['subtitle'] = "Sorry your module still under construction";
		$this->load->view('index', $data);
	}

}
