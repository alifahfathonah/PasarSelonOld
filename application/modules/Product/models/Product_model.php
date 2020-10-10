<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model {

    public function __construct()
    {

        parent::__construct();

    }

    public function _query($params)
    {
        $this->db->select('Product.*,Merchant.name as merchantName, Merchant.cityName as merchantCity')
                 ->select('(price * (priceMargin/100)) as totalMargin')
                 ->select('(price * (discount/100)) as totalDiscount')
                 ->select('(price + (price * (priceMargin/100))) as priceAfterDiscount')
                 ->select('(price + (price * (priceMargin/100))) as priceAfterMargin')
                 ->select('(price + (price * (priceMargin/100))) + ((price + (price * (priceMargin/100))) * (priceMargin/100) ) as priceNett');

        if(isset($params['sort'])){
            $this->db->order_by($params['sort']);
        }

        if($params['keyword'] != '' && isset($params['keyword'])){
            $this->db->like('Product.name',$params['keyword'],'both');
        }

        if($params['merchant'] != 0 && isset($params['merchant'])){
            $this->db->where('merchantId',$params['merchant']);
        }

        if($params['kategori'] != '' && isset($params['kategori'])){
            $this->db->where('productCategoryId',$params['kategori']);
        }

        if($params['rating'] != '' && isset($params['rating'])){
            $this->db->where('ratings',$params['rating']);
        }

        if($params['priceRange'] != '' && isset($params['priceRange'])){
            $price = explode('-', $params['priceRange']);
            $this->db->where("price >= $price[0]");
            $this->db->where("price <= $price[1]");
        }

        $this->db->where('Product.isDeleted', 0);
    }

    public function _queryMerchant($params)
    {

        if($params['keyword'] != '' && isset($params['keyword'])){
            $this->db->like('Product.name',$params['keyword'],'both');
        }

        if($params['merchant'] != 0 && isset($params['merchant'])){
            $this->db->where('merchantId',$params['merchant']);
        }

        if($params['kategori'] != '' && isset($params['kategori'])){
            $this->db->where('productCategoryId',$params['kategori']);
        }

        if($params['rating'] != '' && isset($params['rating'])){
            $this->db->where('ratings',$params['rating']);
        }

        if($params['priceRange'] != '' && isset($params['priceRange'])){
            $price = explode('-', $params['priceRange']);
            $this->db->where("price >= $price[0]");
            $this->db->where("price <= $price[1]");
        }

        $this->db->where('Product.isDeleted', 0);
    }

    public function get_all_product($params)
    {
        $this->_query($params);
        return $this->db->select('Product.*, Merchant.name as merchantName, Merchant.cityName as merchantCity')->join('Merchant','Merchant.id = Product.merchantId','left')->get_where('Product', array('isPublished'=>1, 'Product.statusApproval' => 'APPROVED', 'isDeleted' => 0));
    }

    public function fetch_product($limit,$pos, $params)
    {
        $query = $this->db->query('SELECT * FROM (SELECT `Product`.*, `Merchant`.`name` as `merchantName`, `Merchant`.`cityName` as `merchantCity`, (price + (price * (priceMargin/100))) - ((price + (price * (priceMargin/100))) * discount/100) as priceNett FROM `Product` LEFT JOIN `Merchant` ON `Merchant`.`id` = `Product`.`merchantId` WHERE `Product`.`isDeleted` =0 AND `isPublished` = 1 AND `Product`.`statusApproval` = \'APPROVED\' AND `isDeleted` =0) AS innertable')->limit($limit,$pos);

//        if(isset($params['sort'])){
//            $this->db->order_by($params['sort']);
//        }
//
//        if($params['keyword'] != '' && isset($params['keyword'])){
//            $this->db->like('Product.name',$params['keyword'],'both');
//        }
//
//        if($params['merchant'] != 0 && isset($params['merchant'])){
//            $this->db->where('merchantId',$params['merchant']);
//        }
//
//        if($params['kategori'] != '' && isset($params['kategori'])){
//            $this->db->where('productCategoryId',$params['kategori']);
//        }
//
//        if($params['rating'] != '' && isset($params['rating'])){
//            $this->db->where('ratings',$params['rating']);
//        }
//
//        if($params['priceRange'] != '' && isset($params['priceRange'])){
//            $price = explode('-', $params['priceRange']);
//            $this->db->where("priceNett >= $price[0]");
//            $this->db->where("priceNett <= $price[1]");
//        }

        return $query->result();

    }

    public function fetch_merchant($params) {
        $this->_queryMerchant($params);
        $this->db->select('Merchant.name as merchantName, Merchant.cityName as merchantCity, COUNT(Product.id) as jmlProduk')->join('Merchant','Merchant.id = Product.merchantId','left')->group_by('Product.merchantId');
        return $this->db->get_where('Product', array('isPublished'=>1, 'Product.statusApproval' => 'APPROVED', 'isDeleted' => 0))->result();
    }

    public function priceRange($params) {
        $this->_query($params);
        $this->db->select('MIN(Product.price) as lowestPrice, MAX(Product.price) as highestPrice')->join('Merchant','Merchant.id = Product.merchantId','left');
        return $this->db->get_where('Product', array('isPublished'=>1, 'Product.statusApproval' => 'APPROVED', 'isDeleted' => 0))->row();
    }

    public function get_product_detail($id)
    {
        $this->db->select('Product.*, Merchant.name as merchantName, Merchant.cityName as merchantCity');
        $this->db->select('(price * (priceMargin/100)) as totalMargin');
        $this->db->select('(price * (discount/100)) as totalDiscount');
        $this->db->select('(price - (price * (discount/100))) as priceAfterDiscount');
        $this->db->select('(price + (price * (priceMargin/100))) as priceAfterMargin');
        $this->db->select('(price - (price * (discount/100))) + ((price - (price * (discount/100))) * (priceMargin/100)) as priceNett');
        $this->db->join('Merchant','Merchant.id=Product.merchantId', 'left');
        $this->db->where('Product.id',$id);
        $query = $this->db->get('Product');
        //echo '<pre>'; print_r($query);die;
        if($query->num_rows() != 0) {
            $data = array();
            $data['product'] = $query->row();
            $data['product']->spesification = json_decode($query->row()->specification);
            $data['product']->discussion = $this->getProductDiscussion($query->row()->id);
            $data['product']->related = $this->getProductRelated($query->row()->productCategoryId, $query->row()->productCategoryName);
            $data['product']->productMerchant = $this->getProductByMerchant($query->row()->merchantId);
            $data['product']->review = $this->getReview($query->row()->id);
        }else {
            $data = null;
        }

        return $data;

    }

    function getProductSpesification($productId) {
        $query = "SELECT ProductSpesification.`productId`, Product.`name`, ProductSpesificationKey.`key`, ProductSpesificationValue.`value` FROM ProductSpesification
            LEFT JOIN Product ON Product.`id`=ProductSpesification.`productId`
            LEFT JOIN ProductSpesificationKey ON ProductSpesificationKey.`id`=ProductSpesification.`key`
            LEFT JOIN ProductSpesificationValue ON ProductSpesificationValue.`id`=ProductSpesification.`value`
            WHERE ProductSpesification.ProductId = '$productId'
            GROUP BY ProductSpesificationKey.`id`";
        

        return $this->db->query($query)->result();
    }

    function getProductDiscussion($productId) {
        $query = "SELECT ProductDiscussion.* FROM ProductDiscussion
                    LEFT JOIN Customer ON Customer.id = ProductDiscussion.customerId
                    WHERE ProductDiscussion.id IN (SELECT id FROM ProductDiscussion WHERE ProductId='$productId') ORDER BY ProductDiscussion.createdAt DESC";

        return $this->db->query($query)->result();
    }

    function getDetailDiscussion($productDiscussionId) {
        return $this->db->order_by('createdAt','asc')->get_where('ProductDiscussionConversation',['productDiscussionId'=>$productDiscussionId])->result();
    }

    function getProductRelated($productCategoryId, $name) {
        $format = json_encode(array('id' => $productCategoryId, 'name' => $name));
        $query = "SELECT * FROM Product WHERE statusApproval = 'APPROVED' AND productCategoryId='$productCategoryId' OR productCategoryId IN 
                    (SELECT id FROM ProductCategory WHERE JSON_CONTAINS(parent, '$format'))";
        return $this->db->query($query)->result();
    }

    function getReview($idProduct) {
        return $this->db->join('Customer','Customer.id=Review.customerId','left')->get_where('Review',array('productId' => $idProduct))->result();
    }

    function getProductByMerchant($idMerch) {
        return $this->db->where('merchantId',$idMerch)->get_where('Product',['isPublished'=>1,'statusApproval'=>'APPROVED','isDeleted'=>0])->result_array();
    }
 

}
