<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ulasan extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();

        /*load libraries*/
        $this->load->library(array('Breadcrumbs', 'Regex', 'Form_validation', 'bcrypt', 'Api','Master','Tanggal', 'pagination','uuid'));

        $this->load->module('Cart');
        /*load models*/

        $this->load->model('checkout_model', 'checkout');

        /*default breadcrumb*/
        $this->breadcrumbs->push('<i class="fa fa-home"></i> Home </a>', '' . base_url() . '');

        /*define varibel for customer id from session*/
        $this->customerId = ($this->session->userdata('logged_in')) ? $this->session->userdata('user')->id : 0;

        /*cek session logged in*/
        if (!($this->session->userdata('logged_in'))) {
            redirect(base_url() . 'Login');
        }

    }

    public function index() {
        /*breadcrumbs active*/
        $this->breadcrumbs->push('Ulasan', get_class($this).'/'.__FUNCTION__);
        $data['breadcrumbs'] = $this->breadcrumbs->show();

        $config = array();
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config["base_url"] = base_url('Pesan/Ulasan');
        $config["total_rows"] = $this->checkout->countGetOrder($this->customerId, [6]);
        $config["per_page"] = 3;

        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';

        $query_string = $_GET;
        if (isset($query_string['page']))
        {
            unset($query_string['page']);
        }

        if (count($query_string) > 0)
        {
            $config['suffix'] = '&' . http_build_query($query_string, '', "&");
            $config['first_url'] = $config['base_url'] . '?' . http_build_query($query_string, '', "&");
        }

        $page = $this->input->get('page') ? $this->input->get('page') : 0;
        $config["page"] = $page;
        $after_page = $page + $config["per_page"];

        $config['after_page'] = ($after_page > $config['total_rows']) ? $config['total_rows'] : $after_page ;

        $this->pagination->initialize($config);

        $data['allOrder'] = $this->checkout->getOrderWithLimit($this->customerId,[7], $config["page"], $config["per_page"]);
        $data['customerId'] = $this->customerId;
        $data["links"] = $this->pagination->create_links();
//        echo '<pre>';print_r($data);die;
        /*load view*/
//        echo '<pre>'; print_r($data['allOrder']);
        $this->template->load($data, 'Ulasan','ulasan','Pesan');
    }

    public function submit() {

        $data = [
            'id' => $this->uuid->v4(),
            'productId' => $this->input->post('productId'),
            'orderId' => $this->input->post('orderId'),
            'customerId' => $this->session->userdata('user')->id,
            'merchantId' => $this->input->post('merchantId'),
            'isApproved' => 0,
            'text' => $this->input->post('ulasan'),
            'rating' => $this->input->post('rating')
        ];
        $query = $this->db->insert('Review', $data);

        if($query) {
            echo json_encode(array('status' => 'success', 'text' => $this->input->post('ulasan'), 'rating' => $this->input->post('rating')));
        }else{
            echo json_encode(array('status' => 'failed'));
        }
    }

    public function getUlasan() {

    }

}
