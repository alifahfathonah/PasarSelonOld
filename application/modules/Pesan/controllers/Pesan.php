<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pesan extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();

        /*load libraries*/
        $this->load->library(array('Breadcrumbs', 'Regex', 'Form_validation', 'bcrypt', 'Api','Master','Tanggal', 'pagination', 'uuid', 'Notification'));

        /* load model */
        $this->load->model('pesan_model', 'pesan');

        /*default breadcrumb*/
        $this->breadcrumbs->push('<i class="fa fa-home"></i> Home </a>', '' . base_url() . '');

        /*define varibel for customer id from session*/
        $this->customerId = ($this->session->userdata('logged_in')) ? $this->session->userdata('user')->id : 0;

        /*cek session logged in*/
        if (!($this->session->userdata('logged_in'))) {
            redirect(base_url() . 'login');
        }

    }

    public function index() {
        /*breadcrumbs active*/
        $this->breadcrumbs->push('Pesan', get_class($this).'/'.__FUNCTION__);
        $data['breadcrumbs'] = $this->breadcrumbs->show();

        $data['allPesan'] = $this->pesan->allPesan();
        /*print_r($this->db->last_query());die;*/

//        echo '<pre>';print_r($data['allPesan']);
        /*load view*/
        $this->template->load($data, 'Pesan');
    }

    public function detail() {
        $messageId = $this->input->get('messageId');
        $subject = $this->input->get('subject');

        /*breadcrumbs active*/
        $this->breadcrumbs->push('Pesan', get_class($this).'/'.__FUNCTION__);
        $data['breadcrumbs'] = $this->breadcrumbs->show();

        $data['allPesanDetail'] = $this->pesan->allPesanDetail($messageId);
        $data['subject'] = $subject;
        $data['messageId'] = $messageId;
        $data['msg'] = $this->pesan->getMsgById($messageId);
        $data['avatar'] = $this->db->select('avatarFile')->get_where('Customer', ['id' => $this->customerId])->row()->avatarFile;
        // update readAt
        $this->db->where(['messageId' => $messageId]);
        $this->db->update('MessageConversation', ['readAt' => date("Y-m-d H:i:s")]);

        /*delete notification*/
        $this->db->delete('UserNotification', ['contentId' => $messageId]);

//        print_r($data['allPesanDetail']);
        /*load view*/
        $this->template->load($data,'pesan_detail','pesan_detail','Pesan');
    }


    public function addConversation() {
        
        $messageId = $this->input->post('messageId');
        $message = $this->input->post('message');

         /*notification*/
        $this->notification->save(array('id' => $this->uuid->v4(), 'userId'=>$this->customerId,'contentId'=>$messageId,'contentType'=>'Message', 'contentLink'=> '','contentImage'=>'fa fa-comment','content'=>'Anda punya pesan baru'));

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
