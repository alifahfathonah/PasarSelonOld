<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model {

	var $table = 'admin';
	var $column = array('nama_lengkap','email','password','role_id');
	var $order = array('admin_id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function check_username_exist($username)
	{
		$user = $this->db->select('*')
						 ->select("CONCAT(firstName,' ',lastName) as customerName", false)
						 ->join('Customer','Customer.id=User.id','left')
						 ->get_where('User', array('username'=>$username, 'realm'=>'customer'))
						 ->row();
		return $user;
	}


}
