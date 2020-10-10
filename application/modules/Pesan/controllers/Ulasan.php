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

//        $data['allOrder'] = $this->checkout->getUlasanProduct( $this->customerId, 7 );
        $data['allOrder'] = $this->checkout->getUlasanProductApi($this->customerId);
        $data['customerId'] = $this->customerId;
//        echo '<pre>';print_r($data);die;
        /*load view*/
        //echo '<pre>'; print_r($data['allOrder']);die;
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
            
            $this->db->update('Order', array('status' => 7), array('id' => $this->input->post('orderId')));

            echo json_encode(array('status' => 'success', 'text' => $this->input->post('ulasan'), 'rating' => $this->input->post('rating')));
        }else{
            echo json_encode(array('status' => 'failed'));
        }
    }

    public function getUlasan() {

    }

}
