<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Komplain extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();

        /*load libraries*/
        $this->load->library(array('Breadcrumbs', 'Regex', 'Form_validation', 'bcrypt', 'Api','Master','Tanggal', 'pagination', 'uuid','Notification'));

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
//        $this->output->enable_profiler(TRUE);
        $this->breadcrumbs->push('Komplain', get_class($this).'/'.__FUNCTION__);
        $data['breadcrumbs'] = $this->breadcrumbs->show();

        //$data['allPesan'] = $this->pesan->allPesan();

//        echo '<pre>';print_r($data['allPesan']);
        /*load view*/
        $user = $this->session->userdata('user'); 
        $data['tiket'] = $this->db->query('select a.*, concat(b.firstName,b.lastName) as name_customer, c.name as name_merchant, d.name as name_product, e.name as name_status  from Complaint a LEFT JOIN Customer b ON (a.customerId = b.id) '
                . 'LEFT JOIN Merchant c ON(a.merchantId = c.id) '
                . 'LEFT JOIN Product d ON(a.productId = d.id) '
                . 'LEFT JOIN ComplaintStatus e ON(a.status = e.id) '
                . 'where customerId = "'.$user->id.'" and status IN(1,2,3) '
                . 'order by createdAt DESC')->result_array();
        
       $this->template->load($data, 'komplain', 'komplain', 'Pesan');
    }

    public function detail() {
//        $messageId = $this->input->get('messageId');
//        $subject = $this->input->get('subject');
        
        $tiket = $this->input->get('tiket');
        
        /*breadcrumbs active*/
        $this->breadcrumbs->push('Komplain', base_url('Pesan/Komplain'));
        $this->breadcrumbs->push('Komplain Detail', get_class($this).'/'.__FUNCTION__);
        $data['breadcrumbs'] = $this->breadcrumbs->show();

//        $data['allPesanDetail'] = $this->pesan->allPesanDetail($messageId);
//        $data['subject'] = $subject;
//        $data['messageId'] = $messageId;

//        print_r($data['allPesanDetail']);
        /*load view*/

        $params = [
            'link'   => API_DATA . 'Customers/'.$this->customerId.'/complaint?access_token='.$this->session->userdata('token').'&complaintId='.$this->input->get('tiket').'&limit&skip=&order&lastUpdated=1495477678108',
            'data'  => [],
        ];
        
        $api = $this->api->getApiData($params)->result;
        $data['tiket'] = $api;
        //print_r($data['tiket']);die;
        
         $data['conf'] = $api->conversation;
         $data['sess'] = $this->session->userdata('user');
         $data['returnProduct'] = $this->db->get_where('ReturnedProduct', array('complaintId' => $tiket))->row();
        
        $this->template->load($data,'komplain_detail','komplain_detail','Pesan');
    }

    public function addComplaint() {
        $merchantId = $this->input->post('merchantId');
        $input = [
            'message' => $this->input->post('message')
        ];

        $params = [
            'link'   => API_DATA . 'Customers/'.$this->customerId.'/complaint/reply?access_token='.$this->session->userdata('token').'&complaintId='.$this->input->post('Id'),
            'data'  => $input,
        ];

        $data = $this->api->getApiData($params);

        $true = 0;
        for($i=1;$i<=2;$i++) {
            if($i == 1) {
                $userId = $merchantId;
            }else{
                $userId = 1;
            }
            $insert = [
                'id' => $this->uuid->v4(),
                'userId' => $userId,
                'contentId' => $this->input->post('complaintId'),
                'contentType' => 'Complaint',
                'content' => 'Anda punya balasan complaint baru',
                'contentImage' => 'fa fa-comment'
            ];
            $query = $this->db->insert('UserNotification',$insert);
            if($query) {
                $true++;
            }
        }

        if($true==2) {
            echo json_encode($data);
        }else{
            echo json_encode(['result'=>'Gagal insert db']);
        }
    }

    public function returBarang() {
        $complaintId = $this->input->post('complaintId');
        $remark = $this->input->post('remark');
//        print_r($this->session->userdata('user'));die;
        $insert = [
            'id' => $this->uuid->v4(),

            'complaintId' => $this->input->post('complaintId'),
            'remark' => $this->input->post('remark'),
            'status' => 2,
            'modifiedBy' => $this->customerId,
            'modifiedByRole' => 1
        ];

        $query = $this->db->insert('ReturnedProduct',$insert);
        if($query) {
            $this->notification->save(array('id' => $this->uuid->v4(),'userId'=>$this->customerId,'contentId'=> $insert['complaintId'],'contentType'=>'Complaint', 'contentLink'=> '','contentImage'=>'fa fa-truck','content'=>$this->session->userdata('user')->firstName.' '.$this->session->userdata('user')->lastName.' telah mengirim kembali barang yang dikomplain'));
        }

        redirect(base_url('Pesan/Komplain/detail?tiket='.$insert['complaintId']));
    }

    public function accept_product() {
        $ticket = $this->input->get('ticket');
        $query = $this->db->update('ReturnedProduct', array('status' => 5), array('complaintId' => $ticket) ); //print_r($this->db->last_query());die;
        if($query) {
            $this->notification->save(array('id' => $this->uuid->v4(),'userId'=>$this->customerId,'contentId'=> $ticket,'contentType'=>'Complaint', 'contentLink'=> '','contentImage'=>'fa fa-truck','content'=>$this->session->userdata('user')->firstName.' '.$this->session->userdata('user')->lastName.' barang yang dikomplain sudah diterima customer'));
        }

        echo json_encode(array('messages' => 'success'));
    }

}
