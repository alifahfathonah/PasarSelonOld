<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
//date_default_timezone_set('Asia/Jakarta');
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;

class Templates_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();

        $this->customerId = ($this->session->userdata('logged_in')) ? $this->session->userdata('user')->id : 0;
    }

    function cart_total()
    {
        $this->db->where('customerId', $this->customerId);
        $query = $this->db->get('Cart');
        return $query->num_rows();
    }

    function cart_total_price()
    {
        $params = [
            'link' => API_DATA . 'Customers/' . $this->customerId . '/cart?access_token=' . $this->session->userdata('token') . '&limit&skip&lastUpdated=',
            'data' => array(),
        ];

        $data = $this->api->getApiData($params);
        $total = 0;

        //        echo '<pre>'; print_r($data);exit;
        if (isset($data->result)) {
            foreach ($data->result as $row) {
                $total += $row->netTotal;
            }
        }

        return $total;
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

    function getUserDetail()
    {
        $this->db->where('User.id', $this->customerId)
            ->join('Customer', 'Customer.id = User.id', 'left');
        return $this->db->get('User')->row();
    }

    function getDefaultAddress()
    {
        return $this->db->where('Customer.id', $this->customerId)
            ->join('CustomerAddress', 'CustomerAddress.id = Customer.defaultAddress', 'left')
            ->get('Customer')->row();
    }

    public function getCustomerAddress()
    {
        return $this->db->get_where('CustomerAddress', ['customerId' => $this->customerId])->result();
    }

    function getDefaultBank()
    {
        return $this->db->where('Customer.id', $this->customerId)
            ->join('CustomerBankAccount', 'CustomerBankAccount.id = Customer.defaultBankAccount', 'left')
            ->get('Customer')->row();
    }

    public function getListBank()
    {
        return $this->db->get('Bank')->result();
    }

    function searchColArray($arrays, $key, $search, $level)
    {
        $count = 0;
        foreach ($arrays as $object) {
            if ($object[$key] == $search) {
                $count++;
            }
        }
        return $count;
    }

    function searchSubMenu($arrays, $parent, $level)
    {

        $result = [];
        foreach ($arrays as $object) {
            $find = $this->searchColArray($object['parent'], 'id', $parent, $level);
            if ($find > 0) {
                if (count($object['parent']) == $level) {
                    $result[] = $object;
                }
            }
        }
        return $result;
    }


    public function getAllProductCategory()
    {
        //        $get = $this->redis->get('product_category');
        //        $ProductCategory = json_decode($get,TRUE);
        //        $resultData = array();
        //        $rowsecond = array();
        //
        //        foreach($ProductCategory['result'] as $row){
        //
        //            if(count($row['parent']) == 0){
        //                $getData['main'][] = $row;
        //            }
        //
        //            if(count($row['parent']) == 1){
        //                $getData['first'][] = $row;
        //            }
        //
        //            if(count($row['parent']) == 2){
        //                $getData['second'][] = $row;
        //            }
        //
        //        }
        //
        //        //echo '<pre>';print_r($getData['second']);die;
        //        foreach ($getData['main'] as $rows) {
        //            $first = $this->searchSubMenu($getData['first'], $rows['id'], 1 );
        //            //$rows['first'] = $first;
        //            foreach($first as $rows2){
        //                $second = $this->searchSubMenu($getData['second'], $rows2['id'], 2 );
        //                $rows2['second'] = $second;
        //                $rows['first'][] = $rows2;
        //            }
        //
        //            $resultData[] = $rows;
        //        }

        //echo '<pre>';print_r($resultData);die;

        $params = [
            'link'   => API_DATA . 'Products/categories?name=&limit=&skip=&order=&lastUpdated=&output=tree',
            'data'  => array(),
        ];

        $data = $this->api->getApiData($params);
        return $data;
    }

    //    public function getProfileCustomer(){
    //        $get = $this->redis->get('profile_customer');
    //        return json_decode($get,TRUE);
    //    }

    function getAllProductCategoryApi()
    {
        $params = [
            'link'   => API_DATA . 'Products/categories?name=&skip=&order=&lastUpdated=&output=tree',
            'data'  => array(),
        ];
        $data = $this->api->getApiData($params);
        //echo '<pre>'; print_r($data);die;
        return $data;
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

    function iklan()
    {
        $params = [
            'link'   => CMS . 'api/v1/advertisement/',
            'data'  => array(),
        ];
        $data = $this->api->getApiData($params);
        return $data;
    }

    function getNavigationBar()
    {
        $params = [
            'link'   => CMS . 'api/v1/navigation-bar',
            'data'  => array(),
        ];
        $data = $this->api->getApiData($params);
        return $data;
    }

    function getLabel()
    {
        $params = [
            'link'   => CMS . 'api/v1/labeling',
            'data'  => array(),
        ];
        $data = $this->api->getApiData($params);
        return $data;
    }

    public function getAllCart()
    {
        $params = [
            'link'   => API_DATA . 'Customers/' . $this->customerId . '/cart?access_token=' . $this->session->userdata('token') . '&limit&skip&lastUpdated=',
            'data'  => array()
        ];

        $data = $this->api->getApiData($params);
        //echo '<pre>';print_r($data);die;
        return $data;
    }

    function merchantInfo($merchantId)
    {
        return $this->db->get_where('Merchant', ['id' => $merchantId])->row();
    }

    function retrieveProductsByMerchant($merchantId, $per_page = null, $page = null)
    {
        return $this->db->limit($per_page, $page)->get_where('Product', ['merchantId' => $merchantId, 'isPublished' => 1, 'Product.statusApproval' => 'APPROVED', 'isDeleted' => 0]);
    }

    function retrieveUlasanByMerchant($merchantId)
    {
        return $this->db
            ->join('Product', 'Product.id=Review.productId', 'left')
            ->join('Customer', 'Customer.id=Review.customerId', 'left')
            ->get_where('Review', ['Review.merchantId' => $merchantId])
            ->result();
    }

    function getProfileKisel()
    {
        $params = [
            'link'   => CMS . 'api/v1/profile-kisel',
            'data'  => array(),
        ];
        if (count($this->session->userdata('profile-kisel')) == 0) {
            $data = $this->api->getApiData($params);
            if(is_object($data)) $this->session->set_userdata(array('profile-kisel' => $data->data));
        }
        //echo '<pre>';print_r($this->session->all_userdata());die;
        return $this->session->userdata('profile-kisel');
    }

    function headerInformation()
    {
        $params = [
            'link'   => CMS . 'api/v1/header-information',
            'data'  => array(),
        ];
        $data = $this->api->getApiData($params);

        return $data;
    }

    function socmed()
    {
        $params = [
            'link'   => CMS . 'api/v1/social-media',
            'data'  => array(),
        ];
        $data = $this->api->getApiData($params);

        return $data;
    }

    function getFieldName($table, $fieldId, $fieldName, $value)
    {
        return $this->db->get_where($table, array($fieldId => $value))->row()->$fieldName;
    }

    function CountProduct($id)
    {
        $query = $this->db->query('SELECT COUNT(id) AS totalProduct FROM Product WHERE productCategoryId="' . $id . '" AND statusApproval="APPROVED"')->row();
        $count = ($query->totalProduct > 0) ? '(' . $query->totalProduct . ')' : '';
        return $count;
    }
}
