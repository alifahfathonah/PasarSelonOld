<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Merchant extends MX_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->library(
            array('Breadcrumbs','Master', 'uuid', 'pagination','Notification'));
        /*default breadcrumb*/
        $this->breadcrumbs->push('<i class="fa fa-home"></i> Home </a>', ''.base_url().'');

//        $this->load->model('Profile_model','profile');

        $this->customerId = ($this->session->userdata('logged_in')) ? $this->session->userdata('user')->id : 0;

        /*cek session logged in*/
//        if(!($this->session->userdata('logged_in'))){
//            redirect(base_url().'Login');
//        }

    }

    public function index($merchantName)
    {
        $merchantId = $this->input->get('id');

        /*breadcrumbs active*/
        $data = array();
        $data['merchantInfo'] = $this->templates_model->merchantInfo($merchantId);
        $this->breadcrumbs->push('About Merchant', get_class($this).'/'.__FUNCTION__.'/');
        $data['breadcrumbs'] = $this->breadcrumbs->show();

        $config = array();
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config["base_url"] = base_url('Profile/Merchant/'.$merchantName);
        $config["total_rows"] = $this->templates_model->retrieveProductsByMerchant($merchantId)->num_rows();
        $config["per_page"] = 12;
//        $config["uri_segment"] = 5;

        $query_string = $_GET;
        if (isset($query_string['page']))
        {
            unset($query_string['page']);
        }

        if (count($query_string) > 0)
        {
            $config['suffix'] = '&' . http_build_query($query_string, '', "&");
            $config['first_url'] = $config['base_url'] . '?' . http_build_query($query_string, '', "&");
        }

        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';

        $page = $this->input->get('page') ? $this->input->get('page') : 0;
        $config["page"] = $page;

        $data['merchantProducts'] = $this->templates_model->retrieveProductsByMerchant($merchantId, $config["per_page"], $page)->result();

        $this->pagination->initialize($config);

        $after_page = $page + $config["per_page"];
        $config['after_page'] = ($after_page > $config['total_rows']) ? $config['total_rows'] : $after_page ;
        $data["results"] = $this->templates_model->retrieveProductsByMerchant($config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();
        $data['config_pagination'] = $config;


//        var_dump($data['merchantInfo']);
//        print_r($data['products']);
        $this->template->load($data,'merchant','merchant','Profile');
    }

    public function kirimPesan() {
        
        $messageId = $this->uuid->v4();
        $merchantId = $this->input->post('merchantId');
        $subject = $this->input->post('subject');
        $message = $this->input->post('message');

        $messageHeader = [
            'id' => $messageId,
            'merchantId' => $merchantId,
            'customerId' => $this->customerId,
            'subject' => $subject
        ];

        $this->db->insert('Message', $messageHeader);
         /*notification*/
        $this->notification->save(array('id' => $this->uuid->v4(),'userId'=>$merchantId,'contentId'=>$messageId,'contentType'=>'Message', 'contentLink'=> '','contentImage'=>'fa fa-comment','content'=>'Anda punya pesan baru'));

        if($this->session->userdata('user')->realm == 'customer') {
            $roleId = 1;
        }elseif($this->session->userdata('user')->realm == 'merchant') {
            $roleId = 2;
        }

        $messageConversation = [
            'id' => $this->uuid->v4(),
            'messageId' => $messageId,
            'message' => $message,
            'modifiedBy' => $this->customerId,
            'modifiedByRole' => $roleId
        ];

        $query = $this->db->insert('MessageConversation', $messageConversation);

        if($query) {
            echo json_encode(['status' => 'success']);
        }else{
            echo json_encode(['status' => 'failed']);
        }
    }
   

}
