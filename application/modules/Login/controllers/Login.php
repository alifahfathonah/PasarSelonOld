<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Login extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();

        /* load libraries */
        $this->load->library(array('Breadcrumbs', 'Regex', 'Form_validation', 'bcrypt', 'Api', 'uuid'));
        /* load model */
        $this->load->model('Login_model', 'login');
        /* default breadcrumb */
        $this->breadcrumbs->push('<i class="fa fa-home"></i> Home </a>', '' . base_url() . '');
    }

    public function index($info = '')
    {
        $data = array();
        /* breadcrumbs active */
        $this->breadcrumbs->push('Login', get_class($this) . '/' . __FUNCTION__);
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        $data['title'] = 'Login - Halal Shopping';
        $data['info'] = $info;
        //        print_r($data['info']);

        if (isset($this->session->userdata('user')->id)) {
            redirect(base_url());
        } else {
            $this->template->load($data, 'login');
            //$this->output->enable_profiler(TRUE);
        }
    }

    public function processLogin()
    {

        $data = array();
        /* breadcrumbs active */
        $this->breadcrumbs->push('Login', get_class($this) . '/' . __FUNCTION__);
        $data['breadcrumbs'] = $this->breadcrumbs->show();

        // Post form
        $username = $this->regex->_genRegex($this->input->post('username'), 'RGXQSL');
        $password = $this->input->post('password');

        // form validation
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        //$this->form_validation->set_rules('captcha_code', 'Captcha', 'callback_validate_captcha');
        // set message error
        $this->form_validation->set_message('required', "Silahkan isi field \"%s\"");
        $this->form_validation->set_message('min_length', "\"%s\" minimal 6 karakter");

        if ($this->form_validation->run() == FALSE) {
            /* set error message */
            $this->form_validation->set_error_delimiters('<div style="color:red"><i>', '</i></div>');
            /* $captcha = $this->create_captcha();
              $data['captchaImg'] = $captcha['image']; */
            $data['info'] = '';
            $this->template->load($data, 'login');
        } else {
            //set session expire time, after that user should login again
            $this->session->sess_expiration = '1800'; // expiration 30 minutes
            $this->session->sess_expire_on_close = 'true';

            $params = [
                'link' => API_DATA . 'Customers/login',
                'data' => array('username' => $username, 'password' => $password, 'fcmToken' => 'token', 'source' => 'web'),
            ];

            $result_from_api = $this->api->getApiData($params);
            //            echo '<pre>';print_r($result_from_api);die;
            //cek user account
            if (!empty($result_from_api)) {
                if (isset($result_from_api->error)) {
                    $this->session->set_flashdata('failed', 'Username atau password salah!');
                    redirect(base_url('Login'));
                    //                    print_r($result_from_api->error->message);
                } else {
                    if ($result_from_api->status == 200) {
                        $session_user = array(
                            'token' => $result_from_api->result->accessToken,
                            'user' => $this->db->select('*')
                                ->select("CONCAT(firstName,' ',lastName) as customerName", false)
                                ->join('Customer', 'Customer.id=User.id', 'left')
                                ->get_where('User', array('User.id' => $result_from_api->result->userId))
                                ->row(),
                            'logged_in' => TRUE,
                            'avatarFile' => $result_from_api->result->avatarFile
                        );

                        //                            $this->session->unset_userdata('');
                        $this->session->set_userdata($session_user);
                        $this->session->set_flashdata('loginProcess', 'success');

                        //                        echo '<pre>'; print_r($this->session->userdata()); die;
                        redirect(base_url());
                        //                        echo '<meta http-equiv="refresh" content="0;URL=\'' . base_url() . '" />';
                    } else {
                        $this->session->set_flashdata('failed', 'Username atau password salah!');
                        redirect(base_url('Login'));
                        //                        print_r($result_from_api->message);die;
                    }
                }
            } else {
                echo $result_from_api->error;
            }
        }
    }

    public function processLoginCart()
    {

        $data = array();
        /* breadcrumbs active */
        $this->breadcrumbs->push('Login', get_class($this) . '/' . __FUNCTION__);
        $data['breadcrumbs'] = $this->breadcrumbs->show();

        // Post form
        $username = $this->regex->_genRegex($this->input->post('username'), 'RGXQSL');
        $password = $this->input->post('password');

        // form validation
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]');
        //$this->form_validation->set_rules('captcha_code', 'Captcha', 'callback_validate_captcha');
        // set message error
        $this->form_validation->set_message('required', "Silahkan isi field \"%s\"");
        $this->form_validation->set_message('min_length', "\"%s\" minimal 6 karakter");

        if ($this->form_validation->run() == FALSE) {
            /* set error message */
            $this->form_validation->set_error_delimiters('<div style="color:red"><i>', '</i></div>');
            /* $captcha = $this->create_captcha();
              $data['captchaImg'] = $captcha['image']; */
            $data['info'] = '';
            $this->template->load($data, 'login');
        } else {
            //set session expire time, after that user should login again
            $this->session->sess_expiration = '1800'; // expiration 30 minutes
            $this->session->sess_expire_on_close = 'true';

            $params = [
                'link' => API_DATA . 'Customers/login',
                'data' => array('username' => $username, 'password' => $password, 'fcmToken' => 'token'),
            ];

            $result_from_api = $this->api->getApiData($params);
            //cek user account
            if (!empty($result_from_api)) {

                if (isset($result_from_api->error)) {
                    $this->session->set_flashdata('failed', 'Username atau password salah!');
                    redirect(base_url('Login'));
                    //                    print_r($result_from_api->error->message);
                } else {
                    if ($result_from_api->status == 200) {
                        $check_username = $this->login->check_username_exist($username);
                        if (!empty($check_username)) {
                            // jika username dan password true response 1
                            $session_user = array(
                                'token' => $result_from_api->result->accessToken,
                                'user' => $check_username,
                                'logged_in' => TRUE,
                                //                                'avatarFile' => $result_from_api->result->avatarFile
                            );
                            $this->session->set_userdata($session_user);
                            //redirect(base_url());
                            foreach ($this->session->userdata('sess_cart') as $row_cart) {
                                $data = array(
                                    'id' => $this->uuid->v4(),
                                    'productId' => $row_cart['productId'],
                                    'customerId' => $check_username->id,
                                    'quantity' => $row_cart['quantity'],
                                    'note' => ''
                                );
                                $query = $this->db->insert('Cart', $data);
                            }
                            $this->session->set_flashdata('loginProcess', 'success');
                            $this->session->unset_userdata('sess_cart');
                            echo '<meta http-equiv="refresh" content="0;URL=\'' . base_url() . 'Cart/Checkout\'" />';
                        } else {
                            echo "<script>alert('Akun anda salah');</script>";
                            redirect(base_url() . 'Login/Login/index/cart');
                        }
                    } else {
                        $this->session->set_flashdata('failed', 'Username atau password salah!');
                        redirect(base_url('Login/Login/index/cart'));
                        //                        print_r($result_from_api->message);die;
                    }
                }
            } else {
                echo $result_from_api->error;
            }
        }
    }

    public function lupa_password()
    {
        $data = array();
        /* breadcrumbs active */
        $this->breadcrumbs->push('Lupa Password', get_class($this) . '/' . __FUNCTION__);
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        $data['title'] = 'Lupa Password - Halal Shopping';
        $this->template->load($data, 'lupa_password');
    }

    public function processLupaPassword()
    {
        $data = array();
        $email = $this->input->post('email');
        $params = [
            'link' => API_DATA . 'Users/reset',
            'data' => array('email' => $email),
        ];

        $result_from_api = $this->api->getApiData($params);  //echo '<pre>';print_r($result_from_api);die;

        if (empty($result_from_api)) {
            $this->breadcrumbs->push('Cek Email', get_class($this) . '/' . __FUNCTION__);
            $data['breadcrumbs'] = $this->breadcrumbs->show();
            $data['title'] = 'Silahkan Cek Email - Halal Shopping';
            $this->template->load($data, 'cek_email');
        } else {
            $this->session->set_flashdata('failed', 'Email tidak ditemukan!');
            redirect(base_url('Login/lupa_password'));
        }
    }

    public function reset_password()
    {
        $data = array();
        /* breadcrumbs active */
        $this->breadcrumbs->push('Reset Password', get_class($this) . '/' . __FUNCTION__);
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        $data['title'] = 'Reset Password - Halal Shopping';
        $this->template->load($data, 'reset_password');
    }

    public function logout()
    {
        $this->session->sess_destroy();
        echo '<meta http-equiv="refresh" content="0;URL=\'' . base_url() . '\'" />';
    }
}
