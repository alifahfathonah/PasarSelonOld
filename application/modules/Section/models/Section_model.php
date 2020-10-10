<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Section_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->customerId = ($this->session->userdata('logged_in')) ? $this->session->userdata('user')->id : 0;
    }

    /*get data with API*/
    function getBannerByCategory($categoryId)
    {
        $params = [
            'link'   => CMS . 'api/v1/banners/category/' . $categoryId . '',
            'data'  => array(),
        ];

        $data = $this->api->getApiData($params);
        return $data;
    }

    function getBanners()
    {
        $params = [
            'link'   => CMS . 'api/v1/banners',
            'data'  => array(),
        ];

        $data = $this->api->getApiData($params);
        return $data;
    }

    function getKelebihan()
    {
        $params = [
            'link'   => CMS . 'api/v1/advantages',
            'data'  => array(),
        ];

        $data = $this->api->getApiData($params);
        return $data;
    }

    function get_banner_category($bannerCategoryId)
    {
        $params = [
            'link'   => CMS . '/api/v1/banners/category/' . $bannerCategoryId,
            'data'  => array(),
        ];

        $data = $this->api->getApiData($params);
        return $data;
    }

    function getLogoMerchant($merchantId = '')
    {
        $id = ($merchantId != '') ? '/' . $merchantId . '' : '';
        $params = [
            'link'   => CMS . 'api/v1/best-merchant' . $id . '',
            'data'  => array(),
        ];

        $data = $this->api->getApiData($params);
        //print_r($data);die;
        return $data;
    }

    function retrieve_products_main_slider($limit)
    {

        $this->db->select('Product.*,Merchant.name as merchantName, Merchant.cityName as merchantCity')
            ->select('(price * (priceMargin/100)) as totalMargin')
            ->select('(price * (discount/100)) as totalDiscount')
            ->select('(price - (price * (discount/100))) as priceAfterDiscount')
            ->select('(price + (price * (priceMargin/100))) as priceAfterMargin')
            ->select('(price - (price * (discount/100))) + ((price - (price * (discount/100))) * (priceMargin/100) ) as priceNett')
            ->join('Merchant', 'Merchant.id = Product.merchantId', 'left')
            ->where(array('isDeleted' => 0, 'Product.statusApproval' => 'APPROVED', 'isPublished' => 1))
            ->limit($limit);

        $query = $this->db->get('Product'); // Return the results in a array.
        return $query; // Return the results in a array.
    }

    function retrieve_products_discount($limit)
    {

        $this->db->select('Product.*,Merchant.name as merchantName, Merchant.cityName as merchantCity')
            ->select('(price * (priceMargin/100)) as totalMargin')
            ->select('(price * (discount/100)) as totalDiscount')
            ->select('(price - (price * (discount/100))) as priceAfterDiscount')
            ->select('(price + (price * (priceMargin/100))) as priceAfterMargin')
            ->select('(price - (price * (discount/100))) + ((price - (price * (discount/100))) * (priceMargin/100) ) as priceNett')
            ->join('Merchant', 'Merchant.id = Product.merchantId', 'left')
            ->where(array('isDeleted' => 0, 'Product.statusApproval' => 'APPROVED', 'isPublished' => 1))
            ->order_by('discount', 'desc')
            ->limit($limit);

        $query = $this->db->get('Product'); // Return the results in a array.
        return $query; // Return the results in a array.
    }

    //    function retrieve_products_group_by_category($limit) {
    //
    //        $this->db->select('Product.*,Merchant.name as merchantName, Merchant.cityName as merchantCity, ProductCategory.name as categoryName')
    //            ->select('(price * (priceMargin/100)) as totalMargin')
    //            ->select('(price * (discount/100)) as totalDiscount')
    //            ->select('(price - (price * (discount/100))) as priceAfterDiscount')
    //            ->select('(price + (price * (priceMargin/100))) as priceAfterMargin')
    //            ->select('(price - (price * (discount/100))) + ((price - (price * (discount/100))) * (priceMargin/100) ) as priceNett')
    //            ->join('Merchant','Merchant.id = Product.merchantId','left')
    //            ->join('ProductCategory','ProductCategory.id = Product.productCategoryId','left')
    //            ->where(array('isDeleted' => 0, 'Product.statusApproval' => 'APPROVED', 'isPublished' => 1))
    //            ->group_by('productCategoryId')
    //            ->limit($limit);
    //
    //        $query = $this->db->get('Product'); // Return the results in a array.
    //        return $query; // Return the results in a array.
    //    }

    function getBestSellersProduct()
    {
        /*$this->db->select('`Product`.id,Product.name,Product.discount,Product.images,Product.ratings, SUM(OrderDetail.quantity) as jmlTerjual')
            ->select('(price * (Product.priceMargin/100)) as totalMargin')
            ->select('(price * (discount/100)) as totalDiscount')
            ->select('(price - (price * (discount/100))) as priceAfterDiscount')
            ->select('(price + (price * (Product.priceMargin/100))) as priceAfterMargin')
            ->select('(price - (price * (discount/100))) + ((price - (price * (discount/100))) * (Product.priceMargin/100) ) as priceNett')
            ->join('Product','Product.id = OrderDetail.productId')
            ->join('Order','Order.id = OrderDetail.orderId')
            ->where_in('Order.status',[3,4,5,6,7])
            ->where(array('Product.isDeleted' => 0, 'Product.statusApproval' => 'APPROVED', 'Product.isPublished' => 1))
            ->group_by('productId')
            ->order_by('jmlTerjual','DESC')
            ->limit(20);*/
        $query = "SELECT `Product`.`id`, `Product`.`name`, `Product`.`discount`, `Product`.`images`, `Product`.`ratings`, 
                    (price * (Product.priceMargin/100)) AS totalMargin, 
                    (price * (discount/100)) AS totalDiscount, (price - (price * (discount/100))) AS priceAfterDiscount, 
                    (price + (price * (Product.priceMargin/100))) AS priceAfterMargin, 
                    (price - (price * (discount/100))) + ((price - (price * (discount/100))) * (Product.priceMargin/100) ) AS priceNett,
                    (SELECT SUM(OrderDetail.quantity) FROM OrderDetail WHERE productId=Product.id) AS jmlTerjual
                    FROM Product
                    WHERE id IN (SELECT productId FROM OrderDetail GROUP BY productId)
                    ORDER BY jmlTerjual DESC";
        $query = $this->db->query($query); // Return the results in a array.
        //print_r($this->db->last_query());die;
        return $query; // Return the results in a array.
    }

    /*function retrieve_products(){
        $query = $this->db->select('Product.*, Merchant.name as merchantName, Merchant.cityName as merchantCity, ((price - (price * discount/100)) + (price * (priceMargin/100) )) as priceNett')->join('Merchant','Merchant.id = Product.merchantId','left')->limit(10)->get('Product');
        return $query->result(); // Return the results in a array.
    }*/

    function getUserTabel()
    {
        return $this->db->get_where('User', ['id' => $this->customerId])->row();
    }

    function getProfileCustomer()
    {
        $params = [
            'link'   => API_DATA . 'Customers/' . $this->customerId . '/profile?access_token=' . $this->session->userdata('token'),
            'data'  => array(),
        ];

        //        $data = file_get_contents($params['link']);
        $data = $this->api->getApiData($params);
        return $data;
    }


    public function getCustomerAddress()
    {
        return $this->db->get_where('CustomerAddress', ['customerId' => $this->customerId])->result();
    }
    public function getListBank()
    {
        return $this->db->get('Bank')->result();
    }

    public function listProdByCategory()
    {
        $this->db->join()->get_where('ProductCategory', array('productCategoryId' => $this));
    }

    public function getPromoProduct()
    {
        $this->db->from('Promo');
        $this->db->join('PromotedProduct', 'PromotedProduct.promoId=Promo.id', 'left');
        $result = $this->db->get();
        return $result;
    }

    /*
    public function getProductCategory()
    {   
    	$getData = array();
    	$this->db->from('ProductCategory');
    	$this->db->where(array('parent' => NULL));
        $result = $this->db->get();
        foreach($result->result() as $row){
        	$sub = $this->db->like('parent', ''.$row->id.'')->get('ProductCategory'); 
        	$row->subProductCategory = $sub->result();
        	$getData[] = $row;
        }
        return $getData;
    }
    */
    public function getProductCategory()
    {
        $getData = array();
        $this->db->from('ProductCategory');
        $this->db->where(array('parent' => NULL));
        $result = $this->db->get();
        foreach ($result->result() as $row) {
            $sub = $this->db->like('parent', '' . $row->id . '')->get('ProductCategory');
            $row->subProductCategory = $sub->result();
            $getData[] = $row;
        }
        return $getData;
    }
    // Function to retrieve an array with all product information




    //    Return review of all products group by merchant id
    function retrieveUlasanByMerchant($merchantId)
    {
        return $this->db
            ->join('Product', 'Product.id=Review.productId', 'left')
            ->join('Customer', 'Customer.id=Review.customerId', 'left')
            ->get_where('Review', ['Review.merchantId' => $merchantId])
            ->result();
    }


    function cart_total()
    {
        $this->db->where('customerId', $this->customerId);
        $query = $this->db->get('Cart');
        return $query->num_rows();
    }

    function cart_total_price()
    {
        $this->db->select('sum(Cart.quantity*(Product.price+(Product.price*Product.priceMargin/100)-(Product.price*Product.discount/100))) as jml');
        $this->db->where('customerId', $this->customerId);
        $this->db->join('Product', 'Product.id = Cart.productId', 'left');
        $query = $this->db->get('Cart');
        return $query->row();
    }

    public function getAllCartNoApi()
    {
        $this->db->where('customerId', $this->customerId);
        $this->db->join('Product', 'Product.id = Cart.productId', 'left');
        $query = $this->db->get('Cart');
        return $query->result_array();
    }



    function deleteCart($id)
    {
        $this->db->where(array('customerId' => $this->customerId, 'productId' => $id));
        $query = $this->db->delete('Cart');
        return $query;
    }

    function checkoutProdMerch($idMerch)
    {
        $this->db->where('customerId', $this->customerId);
        $this->db->where('merchantId', $idMerch);
        $this->db->join('Product', 'Product.id = Cart.productId', 'left');
        return $this->db->get('Cart')->result_array();
    }

    function subTotalMerchant($idMerch)
    {
        $this->db->select('sum(Cart.quantity*Product.price) as total');
        $this->db->where('merchantId', $idMerch);
        $this->db->join('Product', 'Product.id = Cart.productId', 'left');
        echo $this->db->get('Cart')->row();
        //        return $this->db->get('Cart')->row();
    }

    function getUserDetail()
    {
        $this->db->where('User.id', $this->customerId)
            ->join('Customer', 'Customer.id = User.id', 'left');
        return $this->db->get('User')->row();
    }

    function getListBankAccount()
    {
        $this->db->where('customerId', $this->customerId)->group_by('accountNo');
        return $this->db->get('CustomerBankAccount')->result();
    }

    function getListAddress($customerId)
    {
        $params = [
            'link'   => API_DATA . 'Customers/' . $customerId . '/address?skip=&limit=&access_token=jT6SG2tOOlXZ2GE3JswiAzHCB7Uxr2XgNyMXyChDYrwV3SqF1n6iD4ZrwauGPwZP',
            'data'  => array(),
        ];

        $data = $this->api->getApiData($params);
        return $data;
    }

    function getDefaultAddress()
    {
        return $this->db->where('Customer.id', $this->customerId)
            ->join('CustomerAddress', 'CustomerAddress.id = Customer.defaultAddress', 'left')
            ->get('Customer')->row();
    }

    function getDefaultBank()
    {
        return $this->db->where('Customer.id', $this->customerId)
            ->join('CustomerBankAccount', 'CustomerBankAccount.id = Customer.defaultBank', 'left')
            ->get('Customer')->row();
    }



    function search_m($search)
    {
        return $this->db->like('name', $search, 'both')->get('Product');
    }

    function getTestimony()
    {
        $params = [
            'link'   => CMS . 'api/v1/testimonials',
            'data'  => array(),
        ];

        $data = $this->api->getApiData($params);
        //print_r($data);die;
        return $data;
    }

    public function getAllCart()
    {
        $params = [
            'link'   => 'http://103.195.30.142:3011/api/Customers/' . $this->customerId . '/cart?access_token=' . $this->session->userdata('token') . '&limit&skip',
            'data'  => array()
        ];

        $data = $this->api->getApiData($params);

        //        if(isset($this->session->userdata('user')->id)) {
        //            foreach($data->result as $row) {
        //                $html =
        //            }
        //        }
        return $data;
    }

    public function bankNameOnlyValue()
    {
        $query = $this->db->get('Bank')->result();

        $row = array();
        foreach ($query as $bankName) {
            array_push($row, $bankName->name);
        }

        return json_encode($row);
    }

    function getDelivered($idUser)
    {
        $this->db->get_where('Order', 'customerId', $idUser);
    }

    function retrieve_most_popular()
    {
        $params = [
            'link' => CMS . 'api/v1/most-popular',
            'data' => array()
        ];
        $data = $this->api->getApiData($params);

        return $data;
    }

    function getProductSubCategory()
    {

        $params = [
            'link' => CMS . 'api/v1/product-category',
            'data' => array()
        ];
        $data = $this->api->getApiData($params);
        //print_r($params);die;
        return $data;
    }

    function getKategoriProdukTerbaru()
    {
        $params = [
            'link' => CMS . 'api/v1/new-product-category',
            'data' => array()
        ];
        $data = $this->api->getApiData($params);

        return $data;
    }

    function getPaymentMethod()
    {
        return $this->db->get('PaymentMethod')->result();
    }

    function checkIfReviewed($productId, $orderId)
    {
        $query = $this->db->get_where('Review', array('productId' => $productId, 'orderId' => $orderId))->row();

        return $query;
    }

    function merchantInfo($merchantId)
    {
        return $this->db->get_where('Merchant', ['id' => $merchantId])->row();
    }

    function getCategoryPPOB()
    {
        $params = [
            'link' => API_DATA . 'ppob/categories',
            'data' => array()
        ];
        $data = $this->api->getApiData($params);
        return $data;
    }
}
