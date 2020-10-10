<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PPOB extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();

        /* load libraries */
        $this->load->library(array('Breadcrumbs', 'Regex', 'Form_validation', 'bcrypt', 'Master', 'uuid', 'Notification'));

        /* default breadcrumb */
        $this->breadcrumbs->push('<i class="fa fa-home"></i> Home </a>', '' . base_url() . '');

        /* load models */
        $this->load->model('PPOB_model', 'PPOB_model');

        /* define varibel for customer id from session */
        $this->customerId = ($this->session->userdata('logged_in')) ? $this->session->userdata('user')->id : 0;
        /*define margin*/
        $this->margin  = 0;
    }

    public function index()
    {
        $this->output->enable_profiler(false);
        // Initialize the array with a 'title' element for use for the <title> tag.
        $data = array(
            'title' => 'Pasar Selon - Kisel Indonesia',
            'category' => $this->PPOB_model->getCategoryPPOB(),
            'listProduct' => $this->PPOB_model->listProduct()->result,
            'listProductPd' => $this->PPOB_model->listProductPd()->result,
        );

        $data['page'] = 'home';
        //        echo '<pre>';print_r($data);die;
        if (isset($_GET['order_id'])) {
            $this->breadcrumbs->push('Transaksi berhasil', get_class($this) . '/' . __FUNCTION__);
            $data['breadcrumbs'] = $this->breadcrumbs->show();
            $data['orderId'] = $this->input->get('order_id');
            $data['paymentMethod'] = $this->input->get('payment_method');
            $data['statusOrder'] = ($this->input->get('error_code')) ? array('style' => 'danger', 'title' => '<span style="color:red">Proses Gagal !</span>', 'message' => 'Transaksi dengan nomor ' . $data['orderId'] . ' gagal diproses') : array('style' => 'success', 'title' => 'Proses Berhasil', 'message' => 'Transaksi dengan nomor ' . $data['orderId'] . ' berhasil diproses');
            $this->template->load($data, 'success', 'success', 'PPOB');
        } else {
            $this->template->load($data, 'ppob');
        }
    }

    public function getPhoneCarrier($number = '')
    {
        $phoneUtil = libphonenumber\PhoneNumberUtil::getInstance();

        if (substr($number, 0, 1) == '0') {
            $number = '62' . substr($number, 1);
        } elseif ($number == '') {
            $number = '62';
        }

        $phoneNumber = $phoneUtil->parse($number, 'ID');

        $carrierMapper = libphonenumber\PhoneNumberToCarrierMapper::getInstance();

        $carrier = $carrierMapper->getNameForNumber($phoneNumber, 'en');

        if ($phoneUtil->isValidNumber($phoneNumber)) {
            echo json_encode(['result' => $carrier, 'status' => true]);
        } else {
            echo json_encode(['result' => '', 'status' => false]);
        }
    }

    public function getProviders($categoryId)
    {
        $params = [
            'link' => API_DATA . 'ppob/categories/providers?categoryId=' . $categoryId,
            'data' => []
        ];

        $api = $this->api->getApiData($params);

        echo json_encode($api);
    }

    public function getProducts($categoryId, $providerId)
    {
        $params = [
            'link' => API_DATA . 'ppob/products?categoryId=' . $categoryId . '&providerId=' . $providerId . '&skip=&limit=&sortBy=&sortArrangement=',
            'data' => []
        ];

        $api = $this->api->getApiData($params);

        echo json_encode($api);
    }

    public function checkout()
    {
        $this->output->enable_profiler(false);
        if (!($this->session->userdata('logged_in'))) {
            redirect(base_url() . '/Login');
        }
        // Initialize the array with a 'title' element for use for the <title> tag.
        $this->breadcrumbs->push('Payment Method', get_class($this) . '/' . __FUNCTION__);
        //print_r($_POST);die;
        /*checkout*/
        $checkout = $this->PPOB_model->checkoutProcess();
        //        echo '<pre>';print_r($_POST);die;
        $data = array(
            'title' => 'Pasar Selon - Kisel Indonesia',
            'breadcrumbs' => $this->breadcrumbs->show(),
            'post_data' => $_POST,
            'margin' => $this->margin,
            'customerId' => $this->customerId,
            'orderId' => ($checkout->result->orderId) ? $checkout->result->orderId : '',
            'token' => $this->session->userdata('token'),
        );
        //        echo '<pre>';print_r($data);die;
        $this->template->load($data, 'payment_method', 'payment_method', 'PPOB');
    }

    public function checkoutFinish()
    {
        $this->output->enable_profiler(false);
        /*checkout tcash*/
        $data = $this->PPOB_model->checkoutFinish();
        $response = array(
            'checkoutUrl' => $data->result->checkoutUrl,
            'token' => $data->result->token
        );
        echo json_encode($response);
    }

    public function checkoutWithEscrow()
    {
        $this->output->enable_profiler(false);
        $this->breadcrumbs->push('Konfirmasi Pembayaran', get_class($this) . '/' . __FUNCTION__);
        /*checkout tcash*/
        $response = $this->PPOB_model->requestPayWithEscrow();

        $data = array(
            'breadcrumbs' => $this->breadcrumbs->show(),
            'result' => $response->result,
            'orderId' => $_POST['orderId'],
            'voucher' => $_POST['voucherId'],
        );
        //echo '<pre>';print_r($data);;die;

        $this->template->load($data, 'confirm_payment', 'confirm_payment', 'PPOB');
    }

    public function unggah_bukti()
    {

        /*delete notification*/
        $ntf = $this->input->get('ntf');
        if (isset($ntf)) {
            $this->db->delete('UserNotification', ['id' => $ntf]);
        }

        $params = [
            'link' => API_DATA . 'escrow/accounts?lastUpdated',
            'data' => array()
        ];

        $data['title'] = 'Unggah Bukti Transfer - Halal Shopping';
        $data['escrow_list'] = $this->api->getApiData($params);
        /*get total*/
        $response_escrow = $this->PPOB_model->getResponseFromEscrow();
        $data['escrow'] = $response_escrow->result;
        $data['customerId'] = $this->customerId;
        //echo '<pre>';print_r($data);die;
        $this->template->load($data, 'unggah_bukti', 'unggah_bukti', 'PPOB');
    }

    public function history()
    {
        /*define varibel for customer id from session*/
        $this->customerId = ($this->session->userdata('logged_in')) ? $this->session->userdata('user')->id : 0;

        /*cek session logged in*/
        if (!($this->session->userdata('logged_in'))) {
            redirect(base_url() . 'Login');
        }
        /* breadcrumbs active */
        $this->breadcrumbs->push('Riwayat Transaksi PPOB', get_class($this) . '/' . __FUNCTION__);
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /* all data ppob by customer */
        $data['ppob_data'] = $this->PPOB_model->getOrderList($this->customerId)->result;
        //        echo '<pre>';print_r($data);die;

        $data['page'] = 'PPOB';
        $data['title'] = 'Daftar Transaksi PPOB - Halal Shopping';
        /* load view */
        $this->template->load($data, 'history', 'history', 'PPOB');
    }

    public function getDetailOrder()
    {
        /* breadcrumbs active */
        $orderId = $this->input->get('orderId');
        $detailOrder = $this->PPOB_model->getDetailOrder($orderId)->result;
        if (in_array($detailOrder->status, array(1, 2, 3))) {
            /*blue*/
            $style = "alert alert-info";
            $msg = 'Transaksi pulsa anda senilai ' . $detailOrder->totalPayment . ' sedang diproses';
        } elseif (in_array($detailOrder->status, array(4, 10))) {
            /*green*/
            $style = "alert alert-success";
            $msg = 'Transaksi pulsa anda berhasil  senilai ' . $detailOrder->totalPayment . ' dengan SN ' . $detailOrder->token;
        } else {
            /*red*/
            $style = "alert alert-danger";
            $msg = 'Transaksi pembelian pulsa gagal, pengembalian dana 1x24 jam sesuai dengan metode pembayaran yang telah anda lakukan';
        }
        $html = '';
        $html .= '<div class="' . $style . '">';
        $html .= '<h4>' . $detailOrder->id . '</h4>';
        $html .= $detailOrder->productHistory->description . '<br>';
        $html .= 'Harga Rp.' . number_format($detailOrder->totalPayment) . '<br>';
        $html .= '[' . $this->PPOB_model->cekStatusPayment($detailOrder->status) . ']<br>';
        $html .= $msg;

        $html .= '</div>';

        echo json_encode(array('html' => $html));
    }

    public function validate_voucher_code()
    {
        $voucherCode = $this->input->get('vcCode');
        $orderId = $this->input->get('orderId');
        $params = [
            'link' => API_DATA . 'ppob/orders/voucher:validate?access_token=' . $this->session->userdata('token') . '&customerId=' . $this->customerId . '&orderId=' . $orderId . '&voucherCode=' . $voucherCode,
            'data' => array()
        ];

        $response = $this->api->getApiData($params);
        //print_r($response);die;
        if (isset($response->error)) {
            echo json_encode(array('result' => 0, 'message' => 'Invalid Voucher'));
        } else {
            echo json_encode(array('result' => true, 'message' => 'Success', 'totalVoucher' => $response->totalVoucher, 'voucherId' => $response->voucherId));
        }
    }

    public function process_unggah_bukti()
    {
        $invoiceId = $this->input->get('invoiceId');

        /*delete old notification*/
        $this->db->delete('UserNotification', ['userId' => $this->customerId, 'contentId' => $invoiceId, 'contentType' => 'invoice']);

        /*add notification*/
        $this->notification->save(array('id' => $this->uuid->v4(), 'userId' => $this->customerId, 'contentId' => $invoiceId, 'contentType' => 'Invoice', 'contentLink' => 'Cart/invoice?invoiceId=' . $invoiceId . '', 'contentImage' => 'fa fa-money', 'content' => 'Transaksi anda telah berhasil dilakukan (' . $invoiceId . ')'));

        //Add notification to admin
        $this->notification->save(array('id' => $this->uuid->v4(), 'userId' => 1, 'contentId' => $invoiceId, 'contentType' => 'Invoice', 'contentLink' => '', 'contentImage' => 'fa fa-money', 'content' => 'Transaksi ' . $invoiceId . ' telah dibayar, lakukan verfikasi secepatnya'));

        $order = $this->db->get_where('Order', array('invoiceId' => $invoiceId))->result();
        foreach ($order as $key_order => $value_order) {
            # code...
            $this->notification->save(array('id' => $this->uuid->v4(), 'userId' => $value_order->merchantId, 'contentId' => $value_order->id, 'contentType' => 'Order', 'contentLink' => '', 'contentImage' => 'fa fa-money', 'content' => 'Anda Punya 1 Transaksi Baru dari ' . $this->session->userdata('user')->customerName . ''));
        }

        return false;
    }
}
