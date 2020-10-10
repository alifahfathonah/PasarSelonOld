<?php
/**
 * Login_model Class
 *
 */
class Encryption extends  CI_Model {
	/**
	 * Constructor
	 */
	function __construct()
         {
        parent::__construct();
        $this->load->library('encrypt_2015');
	}
	
	function encrypt_password_callback($password) {        
         $encrypt = new Encrypt_2015();         
         $ress = $encrypt->_base64_encrypt($password, SECURITY_KEY);
         return $ress;
     }
     
     function decrypt_password_callback($password) {        
         $encrypt = new Encrypt_2015();         
         $ress = $encrypt->_base64_decrypt($password, SECURITY_KEY);
         return $ress;
     }
	 
}
// END Login_model Class

/* End of file login_model.php */ 
/* Location: ./system/application/models/login_model.php */