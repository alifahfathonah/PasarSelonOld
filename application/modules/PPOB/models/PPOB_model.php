<?php

class PPOB_model extends CI_Model { // Our Cart_model class extends the Model class

    public function __construct() {
        parent::__construct();
        $this->customerId = ($this->session->userdata('logged_in')) ? $this->session->userdata('user')->id : 0;
    }

    public function listProduct(){
        $params = [
                'link' => API_DATA . 'ppob/categories/products?id=6371781566154276865',
                'data' => []
            ];

            $api = $this->api->getApiData($params);

            return $api;
    }

    public function listProductPd(){
        $params = [
                'link' => API_DATA . 'ppob/categories/products?id=6429546220208914432',
                'data' => []
            ];

            $api = $this->api->getApiData($params);

            return $api;
    }

    public function checkoutProcess(){
        $params = [
                'link' => API_DATA . 'ppob/orders/checkout?access_token='.$this->session->userdata('token').'',
                'data' => array('customerId' => $this->customerId, 'productId' => $_POST['productId'],'billNo'=>$_POST['no_hp']),
            ];
            
            $api = $this->api->getApiData($params);

            return $api;
    }

    public function requestPayWithEscrow(){
        $params = [
                'link' => API_DATA . 'ppob/orders/payment/escrow:request?access_token='.$this->session->userdata('token'),
                'data' => array('id'=> $_POST['orderId'], 'customerId' => $this->customerId, 'bankAccountId' => $_POST['escrowBankAccountId'], 'voucherId'=>$_POST['voucherId']),
            ];
            //print_r($params);die;
            $api = $this->api->getApiData($params);

            return $api;
    }

    public function getResponseFromEscrow(){
        $params = [
                'link' => API_DATA . 'ppob/orders/payment/escrow:request?access_token='.$this->session->userdata('token'),
                'data' => array('id'=> $_GET['orderId'], 'customerId' => $this->customerId, 'bankAccountId' => $_GET['escrow'], 'voucherId'=>$_GET['vcr']),
            ];
            //print_r($params);die;
            $api = $this->api->getApiData($params);

            return $api;
    }

    public function checkoutFinish(){
        $params = [
                'link' => API_DATA . 'ppob/orders/'.$_POST['orderId'].'/pay/tcash?access_token='.$this->session->userdata('token').'',
                'data' => array('customerId' => $this->customerId, 'source' => 'web'),
            ];
            
            $api = $this->api->getApiData($params);

            return $api;
    }

    public function getOrderList(){
        $params = [
                'link' => API_DATA . 'ppob/orders?access_token='.$this->session->userdata('token').'&customerId='.$this->customerId.'',
                'data' =>[],
            ];
            
            $api = $this->api->getApiData($params);

            return $api;
    }

    public function getDetailOrder($orderId){
        $params = [
                'link' => API_DATA . '/ppob/orders/'.$orderId.'?accessToken='.$this->session->userdata('token').'',
                'data' =>[],
            ];
            
            $api = $this->api->getApiData($params);

            return $api;
    }

    public function cekStatusPayment($status){
        $OrderPPOBStatus = array(
             "CREATED" => 1,
             "PAYMENT_REQUESTED" => 2,
             "PAID" => 3,
             "PAYMENT_VERIFIED" => 4,
             "PPOB_REQUESTED" => 5,
             "PPOB_PENDING" => 6,
             "PPOB_SUCCESS" => 10,
             "PPOB_FAILED" => 20,
             "PPOB_CANCELED" => 21,
             "PAYMENT_FAILED" => 22,
            );
            
            return array_search( $status, $OrderPPOBStatus);
    }

    public function getStatusDescription($status){
        $OrderPPOBStatus = array(
             1 => "Created Order Pasar Selon",
             2 => "Pembayaran Belum Dilanjutkan",
             3 => "Sudah Dibayar",
             4 => "Pembayaran Berhasil",
             5 => "Sedang Proses",
             6 => "Transaksi Pending",
             10 => "Transaksi Berhasil",
             20 => "Transaksi Gagal",
             21 => "Transaksi Dibatalkan",
             22 => "Pembayaran Gagal",
            );
            
            return $OrderPPOBStatus[$status];
    }

    public function getCategoryPPOB(){
        $params = [
            'link' => API_DATA.'ppob/categories',
            'data' => array()
        ];
        $data = $this->api->getApiData($params);
        return $data;

    }
    
}

/* End of file cart_model.php */
/* Location: ./application/models/cart_model.php */