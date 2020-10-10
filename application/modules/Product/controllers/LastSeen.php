<?php

/**
 * Created by PhpStorm.
 * User: user-pc
 * Date: 06/07/2017
 * Time: 10.44
 */
defined('BASEPATH') or exit('No direct script access allowed');
class LastSeen extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        /*load libraries*/
        $this->load->library(array('Breadcrumbs', 'Regex', 'bcrypt', 'Api', 'Master', 'Tanggal', 'pagination', 'uuid', 'Notification'));
        /*load model*/
        $this->load->model('product_model', 'product');
        /*default breadcrumb*/
        $this->breadcrumbs->push('<i class="fa fa-home"></i> Home </a>', '' . base_url() . '');
    }

    public function index()
    {
        $this->breadcrumbs->push('Last Seen', get_class($this) . '/' . __FUNCTION__ . '/');

        $data['breadcrumbs'] = $this->breadcrumbs->show();

        $data['title'] = 'Produk Terakhir di Lihat - Halal Shopping';

        $this->template->load($data, 'last_seen', 'last_seen', 'Product');
        //$this->output->enable_profiler(TRUE);
    }
}
