<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Information extends MX_Controller {

    public function __construct()
    {
        parent::__construct();

        /*load libraries*/
        $this->load->library(
            array('Breadcrumbs', 'Regex', 'Form_validation', 'bcrypt', 'Api','Master','Tanggal','uuid'));


        /*default breadcrumb*/
        $this->breadcrumbs->push('<i class="fa fa-home"></i> Home </a>', ''.base_url().'');

        /*define varibel for customer id from session*/
        $this->customerId = ($this->session->userdata('logged_in')) ? $this->session->userdata('user')->id : 0;
    }

    public function tentang_kisel() {
        /*breadcrumbs active*/
        $this->breadcrumbs->push('Tentang Kisel', get_class($this).'/'.__FUNCTION__);
        $data['breadcrumbs'] = $this->breadcrumbs->show();

        $params = [
            'link' => CMS.'api/v1/about-kisel',
            'data' => [],
        ];

        $data['tentang'] = $this->api->getApiData($params);
        //print_r($params);die;

        $this->template->load($data,'tentang_kisel','tentang_kisel','Information');
    }

    public function panduan_berbelanja() {
        /*breadcrumbs active*/
        $this->breadcrumbs->push('Panduan Berbelanja', get_class($this).'/'.__FUNCTION__);
        $data['breadcrumbs'] = $this->breadcrumbs->show();

        $params = [
            'link' => CMS.'api/v1/shopping-guide',
            'data' => [],
        ];

        $data['tentang'] = $this->api->getApiData($params);
        //print_r($params);die;

        $this->template->load($data,'panduan_berbelanja','panduan_berbelanja','Information');

    }

    public function faq() {
        /*breadcrumbs active*/
        $this->breadcrumbs->push('FAQ', get_class($this).'/'.__FUNCTION__);
        $data['breadcrumbs'] = $this->breadcrumbs->show();

        $params = [
            'link' => CMS.'api/v1/faqs',
            'data' => [],
        ];

        $data['faqs'] = $this->api->getApiData($params);
        //print_r($params);die;

        $this->template->load($data,'faq','faq','Information');
    }

    public function informasi_pengantar() {
        /*breadcrumbs active*/
        $this->breadcrumbs->push('Informasi Pengantar', get_class($this).'/'.__FUNCTION__);
        $data['breadcrumbs'] = $this->breadcrumbs->show();

        $params = [
            'link' => CMS.'api/v1/delivery-information',
            'data' => [],
        ];

        $data['inf_pengantar'] = $this->api->getApiData($params);
        //print_r($params);die;

        $this->template->load($data,'informasi_pengantar','informasi_pengantar','Information');

    }

    public function aturan_penggunaan() {
        /*breadcrumbs active*/
        $this->breadcrumbs->push('Aturan Pengguna', get_class($this).'/'.__FUNCTION__);
        $data['breadcrumbs'] = $this->breadcrumbs->show();

        $params = [
            'link' => CMS.'api/v1/rules',
            'data' => [],
        ];

        $data['aturan_pengguna'] = $this->api->getApiData($params);
        //print_r($params);die;

        $this->template->load($data,'aturan_penggunaan','aturan_penggunaan','Information');
    }

    public function privacy_policy() {
        /*breadcrumbs active*/
        $this->breadcrumbs->push('Privacy Policy', get_class($this).'/'.__FUNCTION__);
        $data['breadcrumbs'] = $this->breadcrumbs->show();

        $params = [
            'link' => CMS.'api/v1/privacy-policy',
            'data' => [],
        ];

        $data['privacy_policy'] = $this->api->getApiData($params);
        //print_r($params);die;

        $this->template->load($data,'privacy_policy','privacy_policy','Information');
    }

    public function syarat_ketentuan() {
        /*breadcrumbs active*/
        $this->breadcrumbs->push('Syarat & Ketentuan', get_class($this).'/'.__FUNCTION__);
        $data['breadcrumbs'] = $this->breadcrumbs->show();

        $params = [
            'link' => CMS.'api/v1/tnc',
            'data' => [],
        ];

        $data['syarat_ketentuan'] = $this->api->getApiData($params);
        //print_r($params);die;

        $this->template->load($data,'syarat_ketentuan','syarat_ketentuan','Information');
    }

    public function hubungi_kami() {
        /*breadcrumbs active*/
        $this->breadcrumbs->push('Hubungi Kami', get_class($this).'/'.__FUNCTION__);
        $data['breadcrumbs'] = $this->breadcrumbs->show();

        $params = [
            'link' => CMS.'api/v1/tnc',
            'data' => [],
        ];

        $data['syarat_ketentuan'] = $this->api->getApiData($params);
        //print_r($params);die;

        $this->template->load($data,'hubungi_kami','hubungi_kami','Information');
    }
}
