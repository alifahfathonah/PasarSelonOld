<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Diskusi extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();

        /*load libraries*/
        $this->load->library(array('Breadcrumbs', 'Regex', 'Form_validation', 'Api', 'Master', 'Tanggal', 'pagination'));

        /* load model */
        $this->load->model('pesan_model', 'pesan');

        /*default breadcrumb*/
        $this->breadcrumbs->push('<i class="fa fa-home"></i> Home </a>', '' . base_url() . '');

        /*define varibel for customer id from session*/
        $this->customerId = ($this->session->userdata('logged_in')) ? $this->session->userdata('user')->id : 0;

        /*cek session logged in*/
        if (!($this->session->userdata('logged_in'))) {
            redirect(base_url() . 'login');
        }

    }

    public function index()
    {
        $data = [];
        $this->template->load($data,'Diskusi');
    }
}

?>