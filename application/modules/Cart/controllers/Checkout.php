<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Checkout extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();

        /* load libraries */
        $this->load->library(array('Breadcrumbs', 'Regex', 'Form_validation', 'bcrypt', 'Master', 'uuid', 'Notification'));

        /* load models */
        $this->load->model('checkout_model', 'checkout');

        /* default breadcrumb */
        $this->breadcrumbs->push('<i class="fa fa-home"></i> Home </a>', '' . base_url() . '');

        /* define varibel for customer id from session */
        $this->customerId = ($this->session->userdata('logged_in')) ? $this->session->userdata('user')->id : 0;

        /* cek session logged in */
        if (!($this->session->userdata('logged_in'))) {
            redirect(base_url());
        }
	//$this->output->enable_profiler(TRUE);
    }

    public function index()
    {
        /* breadcrumbs active */
        $this->breadcrumbs->push('Cart detail', get_class($this) . '/' . __FUNCTION__);
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /* all data cart by merchant */
        $data['allCartByMerchant'] = $this->checkout->getAllCartByMerchantWithApi($this->customerId);
        $data['customerId'] = $this->customerId;
        //echo'<pre>';print_r($data['allCartByMerchant']);die;
        /* load view */

        $data['title'] = 'Checkout - Halal Shopping';
        if (count($data['allCartByMerchant']->result->carts) > 0) {
            $this->template->load($data, 'checkout', 'checkout', 'Cart');
        } else {
            redirect(base_url('/'));
        }
    }

    public function update()
    {
        $jmlProduk = count($this->input->post('productId'));
        $item = [];

        for ($i = 0; $i < $jmlProduk; $i++) {
            $item[] = ['productId' => $this->input->post('productId')[$i], 'quantity' => $this->input->post('qty')[$i], 'notes' => $this->input->post('notes')[$i]];
        }

        $params = [
            'link' => API_DATA . 'Customers/' . $this->customerId . '/cart/bulk?access_token=' . $this->session->userdata('token'),
            'data' => ['items' => $item],
            'method' => 'PUT'
        ];

        $data = $this->api->getApiData($params);

        echo json_encode($data);
    }

    public function getAddressList()
    {
        $listAddress = $this->checkout->getAddressListApi($this->customerId);
        echo json_encode($listAddress->result);
    }

    public function process()
    {

        /* breadcrumbs active */
        $this->breadcrumbs->push('Checkout', get_class($this) . '/' . __FUNCTION__);
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        $data['customerId'] = $this->customerId;

        /* all data cart by merchant */
        $data['allCartByMerchant'] = $this->checkout->getAllCartByMerchantWithApi($this->customerId);
        $data['listAddress'] = $this->checkout->getAddressListApi($this->customerId);


        $data['title'] = 'Checkout - Halal Shopping';

        $params = [
            'link' => API_DATA . 'Couriers?lastUpdated=',
            'data' => []
        ];
        //
        $data['kurir'] = $this->api->getApiData($params);

        //        echo '<pre>';print_r($data['allCartByMerchant']);die;

        if (!isset($data['allCartByMerchant']->error->statusCode) || $data['allCartByMerchant']->error->statusCode != 404 && count($data['allCartByMerchant']->result->carts) > 0) {
            $this->template->load($data, 'checkout_process', 'checkout_process', 'Cart');
        } else {
            redirect(base_url('Cart/Checkout'));
        }
    }

    public function checkoutFinish()
    {

        /*print_r($_POST);die;*/

        $shippingAddress = $this->input->post('shippingAddressId');
        $data = [
            'shippingAddressId' => $shippingAddress,
            'courierPackageId' => $this->input->post('courierPackageId'),
            'courierId' => $this->input->post('courierId'),
            //'paymentMethod' => $this->input->post('paymentMethod'),
            //'voucherCode' => $this->input->post('voucherCode'),
            'isGift' => $this->input->post('isGift'),
            'notes' => $this->input->post('notes')
        ];

        //        echo json_encode($data);
        $params = [
            'link' => API_DATA . 'Customers/' . $this->customerId . '/cart/checkout?access_token=' . $this->session->userdata('token'),
            'data' => $data
        ];
        //
        $result_from_api = $this->api->getApiData($params);

        /*print_r($result_from_api);die;*/

        if (!isset($result_from_api->invoiceId)) {
            echo json_encode($result_from_api->error);
        } else {
            $this->notification->save(array('id' => $this->uuid->v4(), 'userId' => $this->customerId, 'contentId' => $result_from_api->invoiceId, 'contentType' => 'Invoice', 'contentLink' => 'Cart/Checkout/payment_method?invoiceId=' . $result_from_api->invoiceId . '', 'contentImage' => 'fa fa-money', 'content' => 'Anda Punya Transaksi yang masih dalam Proses'));
            $order = $this->db->get_where('Order', array('invoiceId' => $result_from_api->invoiceId))->result();
            foreach ($order as $key_order => $value_order) {
                # code...
                $this->notification->save(array('id' => $this->uuid->v4(), 'userId' => $value_order->merchantId, 'contentId' => $value_order->id, 'contentType' => 'Order', 'contentLink' => '', 'contentImage' => 'fa fa-money', 'content' => 'Anda Punya 1 Transaksi Baru dari ' . $this->session->userdata('user')->customerName . ''));
            }
            echo json_encode($result_from_api);
        }
    }

    public function payment_method()
    {
        /* breadcrumbs active */
        $this->breadcrumbs->push('Payment Method', get_class($this) . '/' . __FUNCTION__);
        $data['breadcrumbs'] = $this->breadcrumbs->show();

        $invoiceId = $this->input->get('invoiceId');
        $voucherId = $this->input->get('vcid');

        $data['customerOrder'] = $this->checkout->getCustomerInvoiceById($invoiceId)->result;
        $data['invoiceLog'] = $this->checkout->getInvoiceLog($invoiceId);

        if ($voucherId) {
            $data['voucher'] = $this->db->get_where('Voucher', array('id' => $voucherId))->row();
        }

        $data['escrow_list'] = $this->checkout->escrowList();
        $data['customerId'] = $this->customerId;
        $data['invoiceId'] = $invoiceId;

        /*delete notification*/
        $ntf = $this->input->get('ntf');
        if (isset($ntf)) {
            $this->db->delete('UserNotification', ['id' => $ntf]);
        }

        //echo '<pre>';print_r($data);die;
        $this->template->load($data, 'payment_method', 'payment_method', 'Cart');
        //$this->output->enable_profiler(TRUE);
    }

    public function escrowFind()
    {
        $escrowId = $this->input->get('id');
        $query = $this->db->select('bankName, accountNo, accountName, location, logoFile')->get_where('EscrowBankAccount', ['id' => $escrowId])->row();

        echo json_encode(['status' => 200, 'bankName' => $query->bankName, 'accountNo' => $query->accountNo, 'accountName' => $query->accountName, 'location' => $query->location, 'logoFile' => $query->logoFile]);
    }

    public function confirm_payment()
    {

        /* post or get invoiceId */
        $invoiceId = $this->input->post('invoiceId') ? $this->input->post('invoiceId') : $this->input->get('invoiceId');

        //print_r($invoiceId);die;


        if ($_POST) {

            if ($this->input->post('paymentMethod') == 3) {

                //print_r($_POST);die;
                /* breadcrumbs active */
                $this->breadcrumbs->push('Cart detail', get_class($this) . '/' . __FUNCTION__);
                $data['breadcrumbs'] = $this->breadcrumbs->show();
                /* all data cart by merchant */
                $link = $this->input->post('linkCheckoutUrl');
                $tCashToken = $this->input->post('tCashToken');

                $data = [
                    'message' => $tCashToken
                ];
                $params = [
                    'link' => $link,
                    'data' => $data
                ];

                $result_from_api = $this->api->getApiData($params);

                //print_r($result_from_api);

                /* load view */
                //                $data['title'] = 'Tcash Checkout - Halal Shopping';
                //                $this->template->load($data, 'tcash_checkout', 'tcash_checkout', 'Cart');
            } else {

                $data = [
                    'invoiceId' => $this->input->post('invoiceId'),
                    'escrowBankAccountId' => $this->input->post('escrowBankAccountId'),
                    'voucherId' => $this->input->post('voucherId')
                ];
                $params = [
                    'link' => API_DATA . 'Customers/' . $this->customerId . '/pay/bank-transfer?access_token=' . $this->session->userdata('token'),
                    'data' => $data
                ];

                $result_from_api = $this->api->getApiData($params);

                //                echo '<pre>';print_r($result_from_api);die;

                if (!empty($result_from_api)) {
                    $data['result'] = $result_from_api->result;
                } else {
                    echo "Gagal akses API";
                    exit;
                }

                /* breadcrumbs active */
                $this->breadcrumbs->push('Konfirmasi Pembayaran', get_class($this) . '/' . __FUNCTION__);
                $data['breadcrumbs'] = $this->breadcrumbs->show();
                $data['title'] = 'Confirm Payment - Halal Shopping';
                $data['invoiceId'] = $this->input->post('invoiceId');
                $data['createdAt'] = $this->db->select('createdAt')->get_where('Invoice', ['id' => $invoiceId])->row();
                //echo '<pre>';print_r($data);die;
                $this->template->load($data, 'confirm_payment', 'confirm_payment', 'Cart');
            }
        } else {

            /* check invoiceLog */
            $invoiceLog = $this->db->get_where('InvoiceLog', array('invoiceId' => $invoiceId));
            if ($invoiceLog->num_rows() > 0) {
                $response = json_decode($invoiceLog->row()->response);
                //echo '<pre>';print_r($response);die;

                if (isset($response->escrowBankAccount)) {
                    $dataInvoiceLog = $invoiceLog->row();
                    $invoice = $this->checkout->getCustomerInvoiceByIdApi($this->customerId, $invoiceId);
                    //echo '<pre>';print_r($invoice);die;
                    /* breadcrumbs active */
                    $this->breadcrumbs->push('Konfirmasi Pembayaran', get_class($this) . '/' . __FUNCTION__);
                    $data['breadcrumbs'] = $this->breadcrumbs->show();
                    $data['title'] = 'Confirm Payment - Halal Shopping';
                    $data['invoiceId'] = $invoiceId;
                    $data['result'] = $invoice->result;
                    $data['createdAt'] = $this->db->select('createdAt')->get_where('Invoice', ['id' => $invoiceId])->row();
                    //                    echo '<pre>';print_r($data['result']);die;
                    //                    $this->template->load($data, 'confirm_payment', 'confirm_payment', 'Cart');
                } else {

                    redirect(base_url() . 'Cart/checkout/payment_method?invoiceId=' . $invoiceId . '');
                }
            } else {

                redirect(base_url() . 'Cart/checkout/payment_method?invoiceId=' . $invoiceId . '');
            }
        }
    }

    public function finish()
    {

        $this->db->trans_begin();
        //echo '<pre>';print_r($_POST);die;
        $data = array();
        /* breadcrumbs active */
        $this->breadcrumbs->push('Cart', get_class($this) . '/' . __FUNCTION__);
        $data['breadcrumbs'] = $this->breadcrumbs->show();


        // form validation
        $this->form_validation->set_rules('recipientName', 'Nama Penerima', 'required');
        $this->form_validation->set_rules('recipientPhone', 'No Telp', 'required');
        $this->form_validation->set_rules('Courier', 'Kurir', 'required');
        $this->form_validation->set_rules('payment', 'Metode Payment', 'required');
        //$this->form_validation->set_rules('address_default', 'Alamat', 'required');
        //$this->form_validation->set_rules('captcha_code', 'Captcha', 'callback_validate_captcha');
        // set message error
        $this->form_validation->set_message('required', "Silahkan isi field \"%s\"");
        $this->form_validation->set_message('min_length', "\"%s\" minimal 6 karakter");

        if ($this->form_validation->run() == FALSE) {
            /* set error message */
            $this->form_validation->set_error_delimiters('<div style="color:red"><i>', '</i></div>');
            $this->process();
        } else {

            /* var customer address */
            if ($this->input->post('alamat_default')) {
                $customerAdressId = $this->input->post('alamat_default');
            } else {
                $this->form_validation->set_rules('address_default', 'Alamat', 'required');
                $customerAdress = array(
                    'id' => $this->uuid->v4(),
                    'customerId' => $this->input->post('customerId'),
                    'name' => $this->input->post('customerName'),
                    'recipientName' => $this->input->post('recipientName'),
                    'recipientPhone' => $this->input->post('recipientPhone'),
                    'address' => $this->input->post('address_default'),
                    'zipCode' => $this->input->post('zipCode'),
                    'districtId' => 1,
                    'districtName' => 'Ciputat',
                    'cityId' => 1,
                    'cityName' => 'Tangerang Selatan',
                    'provinceId' => 2,
                    'provinceName' => 'Banten',
                    'countryId' => 1,
                    'countryName' => 'Indonesia'
                );

                $this->db->insert('CustomerAddress', $customerAdress);
                $customerAdressId = $customerAdress['id'];
            }


            /* var customer bank account */
            $customerBank = array(
                'id' => $this->uuid->v4(),
                'customerId' => $this->input->post('customerId'),
                'bankId' => '344b68a2-07b4-42cf-bcee-8df0dbd34bb5',
                'bankName' => 'BCA',
                'accountNo' => '303122311900004',
                'accountName' => ''
            );

            $this->db->insert('CustomerBankAccount', $customerBank);
            $customerBankId = $customerBank['id'];

            /* var order */
            $invoice = array(
                'id' => $this->uuid->v4(),
                'customerId' => $this->input->post('customerId'),
                'customer' => json_encode($this->checkout->getCustomerById($this->input->post('customerId'))),
                'totalPrice' => $this->input->post('totalPrice'),
                'totalWeight' => $this->input->post('totalWeight'),
                'totalShippingCost' => $this->input->post('totalShippingCost'),
                'totalDiscount' => $this->input->post('totalDiscount'),
                'totalVoucher' => $this->input->post('totalVoucher'),
                'totalMargin' => $this->input->post('totalMargin'),
                'totalTaxes' => $this->input->post('totalTax'),
                'status' => 1,
                'taxId' => $this->input->post('taxId'),
                'tax' => json_encode([]),
                'method' => $this->input->post('payment')
            );

            $this->db->insert('Invoice', $invoice);
            $invoiceId = $invoice['id'];

            /* var order merchant */
            $merchantPosted = $this->input->post('merchantId');


            $cartProductMerchant = $this->input->post('cartPruductMerchant');
            $getOrderId = array();

            foreach ($merchantPosted as $key => $rowMp) {

                $merchant = $this->db->where('id', $rowMp)->get('Merchant')->row();
                $merchantDetail = json_encode($merchant);

                $totalWeightMerchant = array_sum($this->input->post('totalWeightProduct' . $rowMp . ''));
                $totalPriceMerchant = array_sum($this->input->post('totalPriceProduct' . $rowMp . ''));
                $customerAdress = json_encode($this->db->get_where('CustomerAddress', 'id', $this->input->post('customerId'))->row());

                $order = [
                    'id' => $this->uuid->v4(),
                    'invoiceId' => $invoiceId,
                    'merchantId' => $rowMp,
                    'merchant' => $merchantDetail,
                    'customerId' => $this->input->post('customerId'),
                    'courierCostId' => 1,
                    'courierCost' => json_encode([]),
                    'customerAddressId' => $customerAdressId,
                    'customerAddress' => $customerAdress,
                    'totalPrice' => $totalPriceMerchant,
                    'totalWeight' => $totalWeightMerchant,
                    'totalShippingCost' => 0,
                    'totalDiscount' => 0,
                    'totalMargin' => 0,
                    'totalGift' => 0,
                    'status' => 1,
                    'notes' => json_encode($this->input->post('notes')),
                    'modifiedBy' => $this->customerId,
                    'modifiedByRole' => 1
                ];
                $this->db->insert('Order', $order);
                $orderId = $order['id'];

                $cartMerchant = $this->input->post('cartId' . $rowMp . '');
                foreach ($cartMerchant as $key2 => $rowCpm) {

                    $product = $this->db->get_where('Product', array('id' => $this->input->post('ProductId' . $rowMp . '')[$key2]))->row();
                    $productDetail = json_encode($product);

                    //                    var_dump($product);
                    //                    echo $productDetail;
                    //                    echo $this->input->post('ProductId'.$rowMp.'')[$key2];

                    $orderDetail = array(
                        'orderId' => $orderId,
                        'productId' => $this->input->post('ProductId' . $rowMp . '')[$key2],
                        'customerId' => $this->input->post('customerId'),
                        'product' => $productDetail,
                        'priceMarginId' => 1,
                        'options' => json_encode([]),
                        'priceMargin' => json_encode([]),
                        'quantity' => $this->input->post('qtyProduct' . $rowMp . '')[$key2],
                        'notes' => $this->input->post('notes'),
                        'subtotalPrice' => $this->input->post('totalPriceProduct' . $rowMp . '')[$key2],
                        'subtotalWeight' => $this->input->post('totalWeightProduct' . $rowMp . '')[$key2],
                        'subtotalDiscount' => $this->input->post('discountProduct' . $rowMp . '')[$key2],
                        'subtotalMargin' => 0,
                        'subtotalGift' => 0
                    );
                    $this->db->insert('OrderDetail', $orderDetail);

                    $this->db->update('Product', array('stock' => $product->stock - $this->input->post('qtyProduct' . $rowMp . '')[$key2]), ['id' => $this->input->post('ProductId' . $rowMp . '')[$key2]]);
                }
            }

            /* drop cart after success */
            $this->db->delete('Cart', array('customerId' => $this->input->post('customerId')));

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
                redirect(base_url() . 'Cart/Checkout/confirm_payment?invoiceId=' . $invoiceId);
            }
        }
    }

    public function checkoutUrl()
    {
        /* breadcrumbs active */
        $this->breadcrumbs->push('Cart detail', get_class($this) . '/' . __FUNCTION__);
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /* all data cart by merchant */
        $data['checkoutUrl'] = $this->input->post('checkoutUrl');
        /* load view */
        $data['title'] = 'Tcash Checkout - Halal Shopping';
        $this->template->load($data, 'tcash_checkout', 'tcash_checkout', 'Cart');
    }

    public function checkShippingCost()
    {
        $params_address = [
            'link' => API_DATA . 'Customers/' . $this->customerId . '/cart/shipping-cost?courierId=' . $_POST['courierId'] . '&courierPackageId=' . $_POST['courierPackageId'] . '&shippingAddressId=' . $_POST['shippingAddressId'] . '&access_token=' . $this->session->userdata('token'),
            'data' => array()
        ];

        $response = $this->api->getApiData($params_address);
        /*print_r($response);die;*/
        if (isset($response->error)) {
            echo json_encode(array('result' => 0, 'message' => 'Alamat tidak ditemukan, Silahkan klik tombol Cek Ongkir'));
        } else {
            echo json_encode(array('result' => $response->shippingCost->totalShippingCost, 'message' => 'Success', 'data' => $response->shippingCost));
        }
    }

    public function validate_voucher_code()
    {
        $voucherCode = $this->input->get('vcCode');
        $invoiceId = $this->input->get('invoiceId');
        $params = [
            'link' => API_DATA . 'Customers/' . $this->customerId . '/invoice/validate-voucher?access_token=' . $this->session->userdata('token') . '&voucherCode=' . $voucherCode . '&invoiceId=' . $invoiceId,
            'data' => array()
        ];

        /*print_r($params);die;*/
        $response = $this->api->getApiData($params);
        if (isset($response->error)) {
            echo json_encode(array('result' => 0, 'message' => 'Invalid Voucher'));
        } else {
            echo json_encode(array('result' => true, 'message' => 'Success', 'totalVoucher' => $response->totalVoucher, 'voucherId' => $response->voucherId));
        }
    }
    public function get_bsecure_token(){
       $voucher_id = $this->input->get('voucher_id');
       $invoice_id = $this->input->get('invoice_id');
       $params = [
          'link'=>API_DATA.'Customers/'.$this->customerId.'/pay/bsecure/token?source=web&invoiceId='.$invoice_id.'&access_token='.$this->session->userdata('token').'&voucherId='.$voucher_id,
          'method'=>'POST',
          'data'=>json_encode([
              'name'=>'aabeben',
              'email'=>'bhasanudin@gmail.com',
              'msisdn'=>'081280100911',
              'merchant'=>'Halalshopping',
              'merchantId'=>$invoice_id
          ])
       ];
       $response = $this->api->getApiData($params);
       echo json_encode($response);
    }
    public function get_tcash_token(){
        $voucher_id = $this->input->get('voucher_id');
        $invoice_id = $this->input->get('invoice_id');
        $params = [
           'link'=>API_DATA.'Customers/'.$this->customerId.'/pay/tcash/token?source=web&invoiceId='.$invoice_id.'&access_token='.$this->session->userdata('token').'&voucherId='.$voucher_id,
           'method'=>'POST'
        ];
        $response = $this->api->getApiData($params);
        echo json_encode($response);
 }
}
