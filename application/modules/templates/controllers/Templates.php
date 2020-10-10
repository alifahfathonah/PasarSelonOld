<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise;

class Templates extends MX_Controller {

	/**
	 *
	 * This is the Modular Template controller. Pass a data object here and it loads the data into view templates.
	 * This controller is called from the templates.php library.
	 *
	 * It can also be loaded as a module using:
	 * $this->load->module('templates');
	 * making the method and its functions available:
	 * $this->templates->index($data);
	 * *note: requires index function explicitly
	 *
	 * It can also be run as a module using:
	 * echo Modules::run('templates', $data);
	 * *note: requires data['body'] be defined.
 	 */

	function __construct() {
        parent::__construct();
        /*load libraries*/
        $this->load->libraries(array('Api'));
        /*load model*/
        $this->load->model(array('Templates/templates_model'));
        //$this->_checkRedis();
         /* define varibel for customer id from session */
        $this->customerId = ($this->session->userdata('logged_in')) ? $this->session->userdata('user')->id : 0;
    
    }


    public function index($data, $template_name = null)
    {

        //print_r($data);die;

        /*
        |
        | If $data['body'] is null then we will get the content from the
        | module's default view file, which is <module_name>_view.php
        | within the application/modules/<module_name>/views directory
        |
        */

        if ( ! array_key_exists('body', $data) )
        {
            // We get the name of the class that called this method so we
            // can get its view file.
            $caller = debug_backtrace();
            $caller_module = $caller[1]['class'];

            // Get the default view file for the module and return as a string.
            $data['body'] = $this->load->view(ucfirst($caller_module).'/'.strtolower($caller_module).'_view', $data, TRUE);
        }

        if ( ! isset($template_name) )
        {
            // If there is no template name parameter passed, we just use the default.
            $template_name = 'default';
        }

        // With the $data['body'] we now can load the template views.
        // Note that currently there is no value included to specify any
        // header or footer file other than default.

        $data['ProductCategory'] = $this->templates_model->getAllProductCategoryApi(); // API
//		$data['ProfileCustomer'] = $this->templates_model->getProfileCustomer();
        $data['ProfileKisel'] = $this->templates_model->getProfileKisel(); // API
        $data['headerInformation'] = $this->templates_model->headerInformation(); // API
        $data['greymenu'] = $this->templates_model->getNavigationBar();
        $data['label'] = $this->templates_model->getLabel();
        $data['socmed'] = $this->templates_model->socmed();
        $data['iklan'] = $this->templates_model->iklan();
        $data['notification'] = $this->db->order_by('createdAt', 'DESC')->get_where('UserNotification', array('userId' => $this->customerId));
        /**
         * Tambahan buat default_footer_view data
         */
        $data['default_footer']=[
            'about'=>[
                'url'=>'Information/tentang_kisel',
                'content'=>'Apa itu Halal Shopping',
            ],
        ];
//		echo '<pre>';print_r($data['headerInformation']);die;
        $this->load->view('default_header_view', $data);
        $this->load->view(strtolower($template_name).'_view', $data);
        $this->load->view('default_footer_view', $data);

    }

	public function main_menu(){

		$this->load->view('main_menu');
	}

	public function top_header(){
		$data = array();
		
		$this->load->view('header_top_area', $data);
	}

	public function buttom_header(){

		$this->load->view('header_buttom_area');
	}

	public function plugin(){

		$this->load->view('plugin');
	}

	public function reset_pass_form(){

		$this->load->view('lupa password');
	}

}

/* End of file templates.php */
/* Location: ./application/modules/templates/controllers/templates.php */
