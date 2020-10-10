<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of site
 *
 * @author http://www.roytuts.com
 */
class Section extends MX_Controller {

    function __construct() {
        parent::__construct();
        /*load libraries*/
        $this->load->libraries(array('Api'));

        $this->load->module('Cart');

        /*load model*/
        $this->load->model(['Section_model']);
        $this->load->model('checkout_model', 'checkout');

        if($this->session->userdata('logged_in')) {
            $this->load->module('Pesan');
            $this->load->model('Pesan_model', 'pesanm');
        }

        /*define varibel for customer id from session*/
        $this->customerId = ($this->session->userdata('logged_in')) ? $this->session->userdata('user')->id : 0;
    }

    function main_slider() {
        $data = array(
            'banner' => $this->Section_model->getBanners(),
            'mainsliderpro' => $this->Section_model->retrieve_products_main_slider(4)->result_array(),
            );
        $this->load->view('main_slider', $data);
    }

    function discount_today() {
        $data = array(
            'products_new' => $this->Section_model->retrieve_products_discount(10)->result(),
            'discount_banner' => $this->Section_model->get_banner_category(5)
        );
        $this->load->view('discount_today', $data);
    }

    function new_product() {
        $data = array(
            'products_new' => $this->Section_model->retrieve_products_main_slider(10)->result(),
            );

        //echo '<pre>';print_r($data['products']); die;
        $this->load->view('new_product', $data);
    }

    function promo_product() {
        $data = array(
//            'promoProduct' => $this->Section_model->retrieve_products_group_by_category(10)->result(),
            );
        $this->load->view('promo_product', $data);
    }

    function most_popular_this_week() {
        $data = [
          'mostPopular' => $this->Section_model->retrieve_most_popular()
        ];
        $this->load->view('most_popular_this_week', $data);
    }

    function best_merchant() {
        $data = array(
            'logoMerchant' => $this->Section_model->getLogoMerchant(),
            );
        $this->load->view('best_merchant', $data);
    }

    function banner_category() {
        $this->load->view('');
    }

    function banner_full_width() {
        $data = array(
            'bannerFullWidth' => $this->Section_model->get_banner_category(5),
            );
        $this->load->view('banner_full_width', $data);
    }

    function banner_3_views() {
        $data = array(
            'banner3Views' => $this->Section_model->getBanners(),
            );
        $this->load->view('banner_3_views', $data);
    }

    function banner_4_views() {
        $data = array(
            'banner4Views' => $this->Section_model->getBanners(),
        );
        $this->load->view('banner_4_views', $data);
    }

    function best_sellers() {
        $data = [
            'best_sellers_products' => $this->Section_model->getBestSellersProduct()->result()
        ];
        //var_dump($data['best_sellers_products']);
        $this->load->view('best_sellers_view',$data);
    }

    function sidebar() {
        $profile = $this->Section_model->getProfileCustomer();
//        echo '<pre>';print_r($profile);die;
        $data['profile'] = $profile->result;
        $data['profile2'] = $this->Section_model->getUserTabel();
        $data['jmlUlasan'] = $this->checkout->jmlTotalUlasan($this->customerId,[6]);
        $data['jmlPesanMasuk'] = $this->pesanm->jmlPesanBaru($this->customerId);

        $data['avatar'] = $this->db->select('avatarFile')->get_where('Customer', ['id' => $this->customerId])->row()->avatarFile;

        /*daftar pemesanan*/
        $data['customerTagihan'] = $this->checkout->getCustomerInvoice($this->customerId, ['1', '2', '3']);
        $ress_api = $this->checkout->getCustomerOrderList($this->customerId, ['1', '2', '3']);
        /*echo '<pre>';print_r($customerData);die;*/
        $customerData = isset($ress_api->error)?$ress_api->error:$ress_api->result;
        $data['customerOrder'] = $customerData;
        $data['riwayat'] = $this->checkout->getCustomerInvoice($this->customerId, ['6','7','12']);
        $data['customerId'] = $this->customerId;

        $new_array = array();
        foreach ($data['customerTagihan'] as $key => $value) {
            if(isset($value->notificationId)){
                if (is_null($value->notificationId) === false) {
                    $new_array[$key] = $value;
                }
            }
            
        }
        $data['countNotifTagihan'] = count($new_array);

        $new_array_order = [];
        foreach ($data['customerOrder'] as $key => $value) {
            if(isset($value->notificationId)){
                if (is_null($value->notificationId) === false) {
                    $new_array_order[$key] = $value;
                }
            }
        }
        $data['countNotifOrder'] = count($new_array_order);

        $new_array_rwyt = array();
        foreach ($data['riwayat'] as $key => $value) {
            if(isset($value->notificationId)){
                if (is_null($value->notificationId) === false) {
                    $new_array_rwyt[$key] = $value;
                }
            }
        }
        $data['countNotifRiwayat'] = count($new_array_rwyt);

        $data['allDeliveredOrder'] = $this->checkout->getOrder($this->customerId, [5]);
        $data['countNotifPengiriman'] = count($data['allDeliveredOrder']);

        $this->load->view('sidebar', $data);
    }

    function list_category_product() {
        $this->load->view('list_category_product');
    }

    function testimoni() {
        $data = array(
            'testimoni' => $this->Section_model->getTestimony(),
            'promoProduct' => $this->Section_model->retrieve_products_main_slider(10)->result(),
            );
        $this->load->view('testimoni', $data);
    }

    function kelebihan() {
        $data = array(
            'kelebihan' => $this->Section_model->getKelebihan()
        );
        $this->load->view('kelebihan', $data);
    }
    function mylocation(){
        $data = array();
        $this->load->view('mylocation',$data);
    }

    function getListProviders($categoryId = '') {
        $data = $this->Section_model->getListProviders($categoryId);

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    function ppob_home() {
        $data = array(
            'category' => $this->Section_model->getCategoryPPOB(),
            );
        //echo '<pre>';print_r($data);die;
        $this->load->view('ppob_home', $data);
    }
}

/* End of file Site.php */