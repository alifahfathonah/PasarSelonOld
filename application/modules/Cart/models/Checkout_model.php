<?php

class Checkout_model extends CI_Model { //Our Cart_model class extends the Model class

    public function __construct() {
        parent::__construct();
        $this->customerId = ($this->session->userdata('logged_in')) ? $this->session->userdata('user')->id : 0;
    }

    function getAllCartByMerchantWithApi($customerId)
    {

        $params = [
            'link' => API_DATA . 'Customers/' . $customerId . '/cart/detail?access_token=' . $this->session->userdata('token') . '&isGift=false',
            'data' => array(),
        ];

        $data = $this->api->getApiData($params);
        return $data;
    }

    public function escrowList() {
        return $this->db->get('EscrowBankAccount')->result();
    }

    public function getAddressListApi($customerId) {
        $params = [
            'link' => API_DATA . 'Customers/'.$customerId.'/address?skip=&limit=&access_token='.$this->session->userdata('token'),
            'data' => [],
        ];

        $data = $this->api->getApiData($params);
        return $data;
    }

    function countGetOrder($customerId,$status) {
        return $this->db->select('Order.*, Order.id as OrderId, Merchant.*')
            ->join('Merchant','Merchant.id = Order.merchantId')
            ->join('Courier','Courier.id = shippingCourierId','left')
            ->where_in('status', $status)
            ->get_where('Order', array('Order.customerId'=>$customerId))->num_rows();
    }

    function getOrder($customerId, $status) {
        $getData = array();

        $order = $this->db->select('Order.*, Order.id as OrderId, Merchant.*')
            ->join('Merchant','Merchant.id = Order.merchantId')
            ->join('Courier','Courier.id = shippingCourierId','left')
            ->order_by('Order.createdAt', 'desc')
            ->where_in('status', $status)
            ->get_where('Order', array('Order.customerId'=>$customerId))->result();
        //echo '<pre>';print_r($this->db->last_query());die;  

        foreach($order as $key => $value) {
            $jmlUlasanPerOrder = 0;

            $value->orderDetail = $this->getDetailDeliveredOrder($value->OrderId);
            $value->merchant = json_decode($value->merchant);
            $value->courier = $this->getCourierCost($value->courierCostId);

            foreach ($value->orderDetail as $detail) {
                $check = $this->check_review($value->OrderId,$detail->productId);

                if($check == 'not reviewed') {
                    $jmlUlasanPerOrder += 1;
                }
            }

            $value->jmlUlasanPerOrder = $jmlUlasanPerOrder;

            $getData[] = $value;

        }

        return $getData;
    }

    function getUlasanProduct($customerId, $status) {
        $order = $this->db->select('Order.*, Order.id as OrderId, Merchant.*, OrderDetail.product')
            ->join('Order','Order.id = OrderDetail.orderId','left')
            ->join('Merchant','Merchant.id = Order.merchantId')
            ->join('Courier','Courier.id = shippingCourierId','left')
            ->order_by('Order.createdAt', 'desc')
            ->where('orderId IN (SELECT id FROM `Order` WHERE `status` IN ('.$status.') AND customerId='.$customerId.')')
            ->get('OrderDetail')
            ->result();

        return $order;
    }

    function getUlasanProductApi($customerId) {
        $params = [
            'link' => API_DATA . 'Customers/'.$customerId.'/reviews?productId&orderId&type=all&skip&limit&order&lastUpdated=&access_token='.$this->session->userdata('token'),
            'data' => [],
        ];

        $data = $this->api->getApiData($params);
        return $data;
    }

    function getOrderWithLimit($customerId, $status, $page, $limit) {
        $getData = array();


        //$order = $this->db->select('Order.*, Order.id as OrderId, Merchant.*')

        $order = $this->db->select('Order.*, Order.id as OrderId, Order.createdAt as tglTransaksi, Merchant.*, Courier.name as courierName')
            ->join('Merchant','Merchant.id = Order.merchantId')
            ->join('Courier','Courier.id = Order.shippingCourierId','left')
            ->order_by('Order.createdAt', 'desc')
            ->where_in('status', $status)
            ->limit($limit, $page)
            ->get_where('Order', array('Order.customerId'=>$customerId))->result();
        //echo '<pre>';print_r($this->db->last_query());die;

        foreach($order as $key => $value) {
            $jmlUlasanPerOrder = 0;

            $value->orderDetail = $this->getDetailDeliveredOrder($value->OrderId);
            $value->merchant = json_decode($value->merchant);
            $value->courier = $this->getCourierCost($value->courierCostId);

            foreach ($value->orderDetail as $detail) {
                $check = $this->check_review($value->OrderId,$detail->productId);

                if($check == 'not reviewed') {
                    $jmlUlasanPerOrder += 1;
                }
            }

            $value->jmlUlasanPerOrder = $jmlUlasanPerOrder;

            $getData[] = $value;

        }

        return $getData;
    }

    function getDeliveredOrder($customerId, $status) {
        $getData = array();


        $order = $this->db->select('Order.*, Order.id as OrderId, Order.createdAt as tglTransaksi, Order.updatedAt as tglOrderUpdate, Merchant.*, Courier.name as courierName, OrderStatus.name as statusOrder')
            ->join('Merchant','Merchant.id = Order.merchantId')
            ->join('Courier','Courier.id = Order.shippingCourierId','left')
            ->join('OrderStatus','OrderStatus.id = Order.status','left')
            ->order_by('Order.createdAt', 'desc')
            ->where_in('status', $status)
            ->get_where('Order', array('Order.customerId'=>$customerId))->result();

        foreach($order as $key => $value) {
            $jmlUlasanPerOrder = 0;

            $value->orderDetail = $this->getDetailDeliveredOrder($value->OrderId);
            $value->merchant = json_decode($value->merchant);
            $value->courier = $this->getCourierCost($value->courierCostId);

            foreach ($value->orderDetail as $detail) {
                $check = $this->check_review($value->OrderId,$detail->productId);

                if($check == 'not reviewed') {
                    $jmlUlasanPerOrder += 1;
                }
            }

            $value->jmlUlasanPerOrder = $jmlUlasanPerOrder;

            $getData[] = $value;

        }

        return $getData;
    }

    function getReason($idOrder) {
        return $this->db->get_where('ReturnedPayment',['orderId' => $idOrder])->result();
    }

    function jmlTotalUlasan($customerId,$status) {
        $jmlTotalUlasan = 0;

        $order = $this->db->select('Order.*, Order.id as OrderId, Merchant.*')
            ->join('Merchant','Merchant.id = Order.merchantId')
            ->join('Courier','Courier.id = shippingCourierId','left')
            ->where_in('status', $status)
            ->get_where('Order', array('Order.customerId'=>$customerId))->result();

        foreach($order as $key => $value) {

            $value->orderDetail = $this->getDetailDeliveredOrder($value->OrderId);
            foreach ($value->orderDetail as $detail) {
                $check2 = $this->check_review($value->OrderId,$detail->productId);

                if($check2 == 'not reviewed') {
                    $jmlTotalUlasan += 1;
                }
            }

        }

        return $jmlTotalUlasan;
    }

    function check_review($orderId,$productId) {
        $check = $this->db->get_where('Review', ['orderId' => $orderId, 'productId' => $productId])->num_rows();
//        var_dump($check);exit;

        if($check > 0) {
            return 'reviewed';
        }else{
            return 'not reviewed';
        }
    }

    function getCourierCost($id) {
        $getData = array();
        $courierCost = $this->db->get_where('CourierCost', array('id' => $id))->result();
        foreach($courierCost as $value) {
            $getData[] = $value;
        }

        return $getData;
    }

    function getDetailDeliveredOrder($idOrder) {
        $getData = array();
        $orderDetail = $this->db->get_where('OrderDetail',array('OrderId' => $idOrder))->result();

        foreach($orderDetail as $value) {
            $value->product = json_decode($value->product);
            $getData[] = $value;
        }
        return $getData;
    }

    function getAllCartByMerchant($customerId) 
    {
        /*defined variabel*/
        $getData = array();
        /*get cart group by merchant */
        $merchant = $this->getCartGroupByMerchant($customerId);
        
        /*looping merchant*/
        foreach ($merchant as $key => $value) {

            /*get cart by id merchant and customer id*/

            $cartByMerchant = $this->getCartByMerchant($value->id, $customerId); //print_r($this->db->last_query());did;

            /*cart data by merchant*/

            $value->subTotalMerchant = $value->sub_total_merchant;

            $value->cartByMerchant = $cartByMerchant;

            /*all data cart*/

            $getData[] = $value;

        }
        //echo '<pre>'; print_r($getData);die;

    return $getData;
    }

    function getCartGroupByMerchant($customerId)
    {

    	$this->db->select('Cart.id as CartId, Merchant.*, Product.currencyCode')
                 ->select('SUM((Cart.quantity * Product.price)) AS sub_total_merchant')
    			 ->select('SUM((Product.weight / 1000)) AS total_weight_merchant')
    			 ->from('Cart')
    			 ->join('Product','Product.id = Cart.productId', 'left')
    			 ->join('Merchant','Product.merchantId = Merchant.id', 'left')
    			 ->where('Cart.customerId', $customerId)
    			 ->group_by('Merchant.id');
    			 
    	$query = $this->db->get(); 
    	return $query->result();

    }

    function getCartByMerchant($merchantId, $customerId) {

        $this->db->select('Cart.id as CartId, Cart.*, Product.*')
        		 ->select('(Cart.quantity * Product.price) AS price_total')
        		 ->select('( (Product.discount * (Cart.quantity * Product.price) ) / 100 ) AS total_discount')
        		 ->select('((Cart.quantity * Product.weight)/1000) AS weight_kg')
        		 ->select('((Cart.quantity * (Cart.quantity * Product.weight)/1000) ) AS weight_total')
        		 ->where('customerId',$customerId)
        		 ->where('merchantId',$merchantId)
        		 ->join('Product','Product.id = Cart.productId', 'left');

        return $this->db->get('Cart')->result();
    }

    function getAllCart($customerId) {
        $this->db->select('Cart.*, Product.*')
            ->select('(Cart.quantity * Product.price) AS price_total')
            ->select('( (Product.discount * (Cart.quantity * Product.price) ) / 100 ) AS total_discount')
            ->select('((Cart.quantity * Product.weight)/1000) AS weight_kg')
            ->select('((Cart.quantity * (Cart.quantity * Product.weight)/1000) ) AS weight_total')
            ->where('customerId',$customerId)
            ->join('Product','Product.id = Cart.productId', 'left');

        return $this->db->get('Cart')->result();
    }

    function getInvoiceById($orderId) {

        $this->db->select('*')
        		 ->where('id',$orderId);

        return $this->db->get('Invoice')->row();
    }

    function rejectedInvoice($customerId) {
        $this->db->select('Invoice.*, PaymentMethod.name as PaymentMethodName, PaymentStatus.name as PaymentStatusName, InvoiceLog.response')
            ->join('PaymentMethod','PaymentMethod.id=Invoice.method', 'left')
            ->join('PaymentStatus','PaymentStatus.id=Invoice.status', 'left')
            ->join('InvoiceLog','InvoiceLog.invoiceId=Invoice.id', 'left')
            ->where('customerId',$customerId)
            ->where('Invoice.status',4);

        return $this->db->get('Invoice')->result();
    }

    function getCustomerOrder($customerId) {

        $this->db->select('Order.*')
        		 ->where('customerId',$customerId);

        return $this->db->get('Order')->result();
    }

    function checkUlasan($orderId,$productId) {
        return $this->db->get_where('Review', ['productId' => $productId, 'orderId' => $orderId])->row();
    }

    function getCustomerInvoice($customerId, $status) {

        $this->db->select('Invoice.*, (totalPrice+totalMargin-totalDiscount) as totalNett, (totalPrice+totalMargin-totalDiscount+totalShippingCost) as totalBayar, PaymentMethod.name as PaymentMethodName, PaymentStatus.name as PaymentStatusName, InvoiceLog.response, Notification.id AS notificationId, InvoiceLog.notes AS LogNotes, InvoiceLog.updatedAt as logUpdatedAt, InvoiceLog.modifiedBy as logModifiedBy, InvoiceLog.modifiedByRole as logModifiedByRole, (SELECT COUNT(OrderDetail.orderId) 
            FROM OrderDetail 
            LEFT JOIN `Order` ON `Order`.`id`=`OrderDetail`.`orderId` 
            WHERE `Order`.`invoiceId`=Invoice.`id`) AS total_rejected')
                 ->join('PaymentMethod','PaymentMethod.id=Invoice.method', 'left')
                 ->join('PaymentStatus','PaymentStatus.id=Invoice.status', 'left')
                 ->join('(SELECT a.* FROM InvoiceLog a WHERE a.id IN (SELECT MAX(id) FROM InvoiceLog WHERE invoiceId=a.`invoiceId` GROUP BY invoiceId )) AS InvoiceLog','InvoiceLog.invoiceId=Invoice.id', 'left')
                 ->join('(SELECT * FROM UserNotification WHERE contentType="Invoice") AS Notification','Notification.contentId=Invoice.id','left')
                 ->where('customerId',$customerId)
//                 ->where('status = 1 or status=2',null, false)

                 ->where_in('Invoice.status', $status)
                 //->order_by('Notification.id','desc')
                 ->order_by('Invoice.createdAt','desc')
                 ->order_by('Invoice.status','asc')
                 ->group_by('Invoice.id');

        return $this->db->get_where('Invoice', array('customerId' => $customerId))->result();
    }

    function complaintId($orderId, $productId) {
        return $this->db->get_where('Complaint',['orderId' => $orderId, 'productId' => $productId])->row();
    }

    function getCustomerInvoiceById($InvoiceId) {
        $params = [
            'link' => API_DATA . 'Customers/' . $this->customerId . '/invoice?access_token=' . $this->session->userdata('token') . '&invoiceId='.$InvoiceId,
            'data' => array(),
        ];

        $data = $this->api->getApiData($params);

        //echo '<pre>';print_r($invoice);die;
        return $data;

    }

    function getInvoiceLog($invoiceId) {
        return $this->db->order_by('updatedAt', 'desc')->get_where('InvoiceLog', ['invoiceId' => $invoiceId, 'status' => 1])->row();
    }

    function getCustomerInvoiceByIdApi($customerId, $InvoiceId) {

        $params = [
            'link' => API_DATA . 'Customers/'.$customerId.'/pay/bank-transfer?access_token='.$this->session->userdata('token').'',
            'data' => array('invoiceId' => $InvoiceId),
        ];
        
        $data = $this->api->getApiData($params);
        //print_r($params);die;
        return $data;

    }

    function checkComplaint($orderId) {
        $row = $this->db->get_where('Complaint',['orderId' => $orderId])->row();
        if(count($row) > 0) {
            return 'yes';
        }else{
            return 'no';
        }
    }

    function getCustomerOrderList($customerId,$orderStatus='') {
        $params = [
            'link' => API_DATA . 'Customers/'.$customerId.'/orders?startDate=&endDate=&status=&limit=&skip=&order=&lastUpdated=&access_token='.$this->session->userdata('token'),
            'data' => []
        ];

        $data = $this->api->getApiData($params);
        
        return $data;
    }

    function getCustomerInvoiceByApi($customerId) {

        $params = [
            'link' => API_DATA . 'Customers/'.$customerId.'/invoices?access_token='.$this->session->userdata('token').'&lastUpdated=',
            'data' => array(),
        ];
        
        $data = $this->api->getApiData($params);
        //echo '<pre>';print_r($data);die;
        return $data;

    }

    function getCustomerById($customerId) {

        $customer = $this->db->select('Customer.*')->where('id',$customerId)->get('Customer')->row();
        $data['customer'] = $customer;
        $data['customer']->address = $this->db->get_where('CustomerAddress', array('customerId' => $customer->id))->result();
        $data['customer']->bankAccount = $this->db->get_where('CustomerBankAccount', array('customerId' => $customer->id))->result();

        return $data;
    }

    function checkIfReviewed($productId,$orderId) {
        $query = $this->db->get_where('Review', array('productId' => $productId, 'orderId' => $orderId))->row();

        return $query;
    }

    function getRedirectUrlFromFinpaydev($invoiceId) {
        $query = "SELECT JSON_EXTRACT(response,'$.body.redirecturl') AS url FROM InvoiceLog 
WHERE invoiceId='$invoiceId' AND JSON_EXTRACT(response, '$.body.redirecturl') IS NOT NULL LIMIT 1";

        return $this->db->query($query)->row();
    }

}

/* End of file cart_model.php */
/* Location: ./application/models/cart_model.php */