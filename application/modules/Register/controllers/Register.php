<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Register extends MX_Controller
{

	public function __construct()
	{
		parent::__construct();

		/*load libraries*/
		$this->load->library(array('Breadcrumbs', 'Regex', 'Form_validation', 'bcrypt', 'Api', 'Master', 'Tanggal', 'pagination', 'Mailer'));
		/*default breadcrumb*/
		$this->breadcrumbs->push('<i class="fa fa-home"></i> Home </a>', '' . base_url() . '');
	}

	public function index()
	{
		$data = array();
		/*breadcrumbs active*/
		$this->breadcrumbs->push('Register', get_class($this) . '/' . __FUNCTION__);
		$data['breadcrumbs'] = $this->breadcrumbs->show();
		$data['title'] = 'Register - Halal Shopping';
		$this->template->load($data, 'register');
	}

	//	public function process()
	//	{
	//        //print_r($_POST);die;
	//		$data = array();
	//		/*breadcrumbs active*/
	//		$this->breadcrumbs->push('Register', get_class($this).'/'.__FUNCTION__);
	//		$data['breadcrumbs'] = $this->breadcrumbs->show();
	//
	//        // form validation
	//        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
	//        $this->form_validation->set_rules('name', 'Nama Lengkap', 'required');
	//        $this->form_validation->set_rules('phone', 'No Handphone', 'required|numeric|min_length[5]');
	//        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]');
	//        $this->form_validation->set_rules('konfirmasi_password', 'Konfirmasi Password', 'matches[password]');
	//
	//        // set message error
	//        $this->form_validation->set_message('required', "Silahkan isi field \"%s\"");
	//        $this->form_validation->set_message('min_length', "\"%s\" minimal 5 karakter");
	//        $this->form_validation->set_message('numeric', "\"%s\" harus diisi dengan angka");
	//        $this->form_validation->set_message('valid_email', "\"%s\" tidak sesuai dengan format email");
	//        $this->form_validation->set_message('matches', "\"%s\" salah");
	//
	//        if ($this->form_validation->run() == FALSE)
	//        {
	//        	/*set error message*/
	//            $this->form_validation->set_error_delimiters('<div style="color:red"><i>', '</i></div>');
	//            $this->template->load($data, 'register');
	//        }
	//        else
	//        {
	//
	//        	/*insert User*/
	//        	$user = array(
	//        		'username' => $this->regex->_genRegex($this->input->post('email'), 'RGXQSL'),
	//        		'email' => $this->regex->_genRegex($this->input->post('email'), 'RGXQSL'),
	//                'password' => password_hash($this->regex->_genRegex($this->input->post('password'), 'RGXQSL'), PASSWORD_BCRYPT),
	//        		'realm' => $this->regex->_genRegex($this->input->post('flag'), 'RGXQSL'),
	//        		);
	//            //print_r($user);die;
	//        	$this->db->insert('User', $user);
	//        	$id_user = $this->db->insert_id();
	//
	//        	/*insert merchant*/
	//	        $merchant = array();
	//            $merchant['id'] = $id_user;
	//            $merchant['firstName'] = $this->regex->_genRegex($this->input->post('name'), 'RGXQSL');
	//            $merchant['phoneNo'] = $this->regex->_genRegex($this->input->post('phone'), 'RGXQSL');
	//
	//            $params = [
	//                'link'   => API_DATA . 'Customers/login',
	//                'data'  => array('username' => $username, 'password' => $password, 'fcmToken' => 'token'),
	//            ];
	//
	//            $result_from_api = $this->api->getApiData($params);  //echo '<pre>';print_r($result_from_api);die;
	//            //cek user account
	//            if(!empty($result_from_api)){
	//
	//                if(isset($result_from_api->error)){
	//                    $this->session->set_flashdata('failed','Username atau password salah!');
	//                    redirect(base_url('Login'));
	////                    print_r($result_from_api->error->message);
	//                }else{
	//                    if($result_from_api->status ==200){
	//                        $check_username = $this->login->check_username_exist($username);
	//                        if(!empty($check_username)){
	//                             // jika username dan password true response 1
	//                            $session_user = array(
	//                                'token' => $result_from_api->result->accessToken,
	//                                'user' => $check_username,
	//                                'logged_in' => TRUE
	//                            );
	//                             $this->session->set_flashdata('register_success','Silahkan cek email untuk konfirmasi pendaftaran');
	//                             $this->session->set_userdata($session_user);
	//                            redirect(base_url());
	//                        }else{
	//                            echo "<script>alert('Akun anda salah');</script>";
	//                            redirect(base_url().'Login');
	//                        }
	//                    }else{
	//                        $this->session->set_flashdata('failed','Username atau password salah!');
	//                        redirect(base_url('Login'));
	////                        print_r($result_from_api->message);die;
	//                    }
	//
	//                }
	//
	//            }else{
	//                echo 'Error while get data from api';
	//            }
	//
	//        }
	//
	//    }

	function sendmail()
	{
		$config = array();
		$config['mail'] = $this->input->post('email') ? $this->input->post('email') : 'no-mail@mail.com';
		$config['name'] = $this->input->post('name') ? $this->input->post('name') : 'anonymous';
		if ($this->mailer->sendemail($config)) {
			echo json_encode(array('status' => 'Success'));
		} else {
			return false;
		}
	}
}
