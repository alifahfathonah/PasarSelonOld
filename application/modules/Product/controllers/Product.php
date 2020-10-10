<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        /*load libraries*/
        $this->load->library(array('Breadcrumbs', 'Regex', 'Form_validation', 'bcrypt', 'Api', 'Master', 'Tanggal', 'pagination', 'uuid', 'Notification'));
        /*load model*/
        $this->load->model('product_model', 'product');
        /*default breadcrumb*/
        $this->breadcrumbs->push('<i class="fa fa-home"></i> Home </a>', '' . base_url() . '');
    }

    public function detail($title)
    {
        $this->output->enable_profiler(false);
        $id = $this->input->get('id');
        $result = $this->product->get_product_detail($id); //echo '<pre>';print_r($result);die;
        log_message($this->db->last_query());
        /*breadcrumbs active*/
        if ($result != null) {
            $this->breadcrumbs->push($result['product']->productCategoryName, base_url() . 'Product?kategori=' . $result['product']->productCategoryId . '');
            $this->breadcrumbs->push($result['product']->name, get_class($this) . '/' . __FUNCTION__ . '/' . $id);
            $data['breadcrumbs'] = $this->breadcrumbs->show();
            $data['title'] = 'Produk - Halal Shopping';
            $data['products'] = $result['product'];
            //         echo "<pre>"; print_r($data['products']);die;

            // put to last seen product session
            $sess = $this->session->userdata('last_seen');

            if (count($sess) != 0) {
                if ($this->search_array_custom($id) == 'tidak ada') {
                    $last_seen = ['id' => $id];
                    array_unshift($_SESSION['last_seen'], $last_seen);
                }
            } else {
                $last_seen[] = ['id' => $id];
                $this->session->set_userdata('last_seen', $last_seen);
            }

            $this->template->load($data, 'detail_product');
            //$this->output->enable_profiler(TRUE);
        } else {
            redirect(base_url());
        }
    }

    public function search_array_custom($id)
    {
        $sess_cart = $this->session->userdata('last_seen');
        foreach ($sess_cart as $key => $product) {
            if ($product['id'] === $id)
                return 'ada';
        }
        return 'tidak ada';
    }

    public function index()
    {
        /*breadcrumbs active*/
        $this->breadcrumbs->push('All Product', base_url('Product'));

        if (htmlspecialchars($this->input->get('kategori'))) {
            $productName = $this->db->get_where('ProductCategory', array('id' => $this->input->get('kategori')))->row();
            $this->breadcrumbs->push($productName->name, get_class($this) . '/' . __FUNCTION__ . '?kategori=' . $this->input->get('kategori') . '');
        }

        $data['breadcrumbs'] = $this->breadcrumbs->show();
        $data['title'] = 'Produk - Halal Shopping';

        $data['locations'] = $this->db->where('id IN (SELECT merchantId FROM Product GROUP BY merchantId)')->group_by('cityId')->get('Merchant')->result();
        //exit(print_r($this->db->last_query()));
        log_message($this->db->last_query());
        $params = [
            'keyword' => $this->input->get('keyword') ? $this->security->xss_clean($this->input->get('keyword')) : NULL,
            'merchant' => $this->input->get('merchant') ? $this->security->xss_clean($this->input->get('merchant')) : NULL,
            'location' => $this->input->get('location') ? $this->security->xss_clean($this->input->get('location')) : NULL,
            'rating' => $this->input->get('rating') ? $this->security->xss_clean($this->input->get('rating')) : NULL,
            'sort' => $this->input->get('sort') ? $this->security->xss_clean($this->input->get('sort')) : NULL,
            'priceRange' => $this->input->get('priceRange') ? $this->security->xss_clean($this->input->get('priceRange')) : '',
            'kategori' => $this->input->get('kategori') ? $this->security->xss_clean($this->input->get('kategori')) : NULL,
            'skip' => $this->input->get('page') ? $this->security->xss_clean($this->input->get('page')) : 0
        ];

        $price = explode('-', $params['priceRange']);

        if (isset($_GET['priceRange'])) {
            $link = API_DATA . 'Products?name=' . urlencode($params['keyword']) . '&categoryId=' . $params['kategori'] . '&merchantId=' . $params['merchant'] . '&merchantCity=' . urlencode($params['location']) . '&ratings=' . $params['rating'] . '&priceRange=' . $price[0] . '|' . $price[1] . '&limit=6&skip=' . $params['skip'] . '&order=' . urlencode($params['sort']) . '&lastUpdated=';
            $linkMeta = API_DATA . 'Products/meta?name=' . urlencode($params['keyword']) . '&categoryId=' . $params['kategori'] . '&merchantId=' . $params['merchant'] . '&merchantCity=' . urlencode($params['location']) . '&ratings=' . $params['rating'] . '&priceRange=' . $price[0] . '|' . $price[1];
        } else {
            $link = API_DATA . 'Products?name=' . urlencode($params['keyword']) . '&categoryId=' . $params['kategori'] . '&merchantId=' . $params['merchant'] . '&merchantCity=' . urlencode($params['location']) . '&ratings=' . $params['rating'] . '&limit=6&skip=' . $params['skip'] . '&order=' . urlencode($params['sort']) . '&lastUpdated=';
            $linkMeta = API_DATA . 'Products/meta?name=' . urlencode($params['keyword']) . '&categoryId=' . $params['kategori'] . '&merchantId=' . $params['merchant'] . '&merchantCity=' . urlencode($params['location']) . '&ratings=' . $params['rating'] . '&priceRange=';
        }

        $params_meta = [
            'link' => $linkMeta,
            'data' => []
        ];
        $meta = $this->api->getApiData($params_meta);
        if (!isset($meta->error) && $meta != null) {
            $data['merchants'] = $meta->merchants;
        } else {
            $data['merchants'] = '';
        }


        $params_address = [
            'link' => $link,
            'data' => []
        ];
        $response = $this->api->getApiData($params_address);
        //        echo '<pre>';print_r($response);die;

        $config = array();
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config["base_url"] = base_url() . "Product";

        if (!isset($response->error)) {
            $config["total_rows"] = isset($meta->count) ? $meta->count : '';
        } else {
            $meta->count = 0;
            $config["total_rows"] = $meta->count;
        }

        $config["per_page"] = 6;
        $config["uri_segment"] = 2;

        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';

        $query_string = $_GET;
        if (isset($query_string['page'])) {
            unset($query_string['page']);
        }

        if (count($query_string) > 0) {
            $config['suffix'] = '&' . http_build_query($query_string, '', "&");
            $config['first_url'] = $config['base_url'] . '?' . http_build_query($query_string, '', "&");
        }

        $page = $this->input->get('page') ? $this->input->get('page') : 0;
        $config["page"] = $page;
        $after_page = $page + $config["per_page"];

        $config['after_page'] = ($after_page > $config['total_rows']) ? $config['total_rows'] : $after_page;

        $this->pagination->initialize($config);

        //        $data["results"] = $this->product->fetch_product($config["per_page"], $page, $params);

        if (!isset($response->error)) {
            $data["results"] = isset($response->result) ? $response->result : '';
        } else {
            $response->result = null;
            $data["results"] = $response->result;
        }

        //        if(!isset($meta->error) || !is_null($meta)) {
        //            $data['merchant'] = $meta->merchants;
        //        }else {
        //            $meta->merchants = '';
        //            $data['merchant'] = $meta->merchants;
        //        }

        $data["links"] = $this->pagination->create_links();
        $data['config_pagination'] = $config;
        $data['params'] = $params;

        if ($params['priceRange'] != null) {
            $harga = explode('-', $this->input->get('priceRange'));
            $range['lowest'] = $harga[0];
            $range['highest'] = $harga[1];
            $data['priceRange'] = (object) $range;
        } else {
            $data['priceRange'] = isset($meta->price) ? $meta->price : '';
        }

        $this->template->load($data, 'product');
        //$this->output->enable_profiler(TRUE);
    }

    public function saveDiscussion()
    {

        $productId = $this->input->post('productId');

        $discussion = [
            'merchantId' => $this->input->post('merchantId'),
            'message' => $this->input->post('question')
        ];

        $params_address = [
            'link' => API_DATA . 'Customers/' . $this->customerId . '/product/' . $productId . '/discuss?access_token=' . $this->session->userdata('token'),
            'data' => $discussion
        ];
        $response = $this->api->getApiData($params_address);

        $this->notification->save(array('id' => $this->uuid->v4(), 'userId' => $discussion['merchantId'], 'contentId' => $productId, 'contentType' => 'productDiscussion', 'contentLink' => '', 'contentImage' => 'fa fa-money', 'content' => 'Anda Punya diskusi produk baru dari user ' . $this->session->userdata('user')->customerName . ''));

        //        $this->db->insert('ProductDiscussionConversation', $postDetail);
        //         echo json_encode(array('status' => 'success', 'date' => date('Y-m-d H:i:s')));

        echo json_encode($response);
    }

    public function replyDiscussion()
    {
        $reply = [
            'productDiscussionId' => $this->input->post('productDiscussionId'),
            'message' => $this->input->post('reply-message')
        ];

        $params_address = [
            'link' => API_DATA . 'Customers/' . $this->customerId . '/product/discuss/reply?access_token=' . $this->session->userdata('token'),
            'data' => $reply
        ];
        $response = $this->api->getApiData($params_address);
        $dt = $this->db->get_where('ProductDiscussion', array('id' => $reply['productDiscussionId']))->row();

        $this->notification->save(array('id' => $this->uuid->v4(), 'userId' => $dt->merchantId, 'contentId' => $reply['productDiscussionId'], 'contentType' => 'productDiscussion', 'contentLink' => '', 'contentImage' => 'fa fa-money', 'content' => 'Anda Punya diskusi produk baru dari user ' . $this->session->userdata('user')->customerName . ''));

        echo json_encode($response);
    }

    public function iklan()
    {
        $this->breadcrumbs->push('Iklan', base_url('Product/iklan'));

        $id = isset($_GET['id']) ? $this->input->get('id') : '';

        $params = [
            'link'   => CMS . 'api/v1/advertisement/' . $id,
            'data'  => array(),
        ];
        $api = $this->api->getApiData($params);
        //        print_r($api);die;

        if ($api->status == 200) {
            $data['iklanV'] = $api->data;
        }

        $data['breadcrumbs'] = $this->breadcrumbs->show();
        $data['title'] = 'Iklan - Halal Shopping';

        $this->template->load($data, 'iklan');
    }

    public function iklanDetail()
    {
        $id = isset($_GET['id']) ? $this->input->get('id') : '';
        $advertisementId = isset($_GET['advertisementId']) ? $this->input->get('advertisementId') : '';

        $this->breadcrumbs->push('Iklan', base_url('Product/iklan?id=' . $advertisementId));
        $this->breadcrumbs->push('Detail', base_url('Product/iklanDetail'));

        $params = [
            'link'   => CMS . 'api/v1/advertisementItem/' . $id,
            'data'  => array(),
        ];
        $api = $this->api->getApiData($params);

        //        echo '<pre>';print_r($api);exit;

        $data['iklanDetail2'] = $api->data;
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        $data['title'] = 'Detail Iklan - Halal Shopping';

        $this->template->load($data, 'iklan_detail');
    }
}
