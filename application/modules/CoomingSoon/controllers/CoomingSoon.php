<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class CoomingSoon extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();

        /* load libraries */
        $this->load->library(
            array('Breadcrumbs', 'Regex', 'Form_validation', 'bcrypt', 'Master', 'uuid', 'Notification'));

        /* load models */
//        $this->load->model('checkout_model', 'checkout');
//        $this->load->model('cart_model', 'cart');

        /* default breadcrumb */
        $this->breadcrumbs->push('<i class="fa fa-home"></i> Home </a>', '' . base_url() . '');

        /* define varibel for customer id from session */
        $this->customerId = ($this->session->userdata('logged_in')) ? $this->session->userdata('user')->id : 0;
    }

    public function index() {
        /*breadcrumbs active*/
        $this->breadcrumbs->push('Cooming Soon', get_class($this).'/'.__FUNCTION__);
        $data['breadcrumbs'] = $this->breadcrumbs->show();

        //print_r($params);die;

        $this->template->load($data,'cooming_soon','cooming_soon','CoomingSoon');
    }
}