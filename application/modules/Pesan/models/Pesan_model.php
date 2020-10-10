<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pesan_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();

        $this->customerId = ($this->session->userdata('logged_in')) ? $this->session->userdata('user')->id : 0;
    }

    /*public function allPesan() {
        return $this->db->select('Message.*, MessageConversation.message, Merchant.name')
                        ->join('MessageConversation','MessageConversation.messageId=Message.id','left')
                        ->join('Merchant','Merchant.id = Message.merchantId','left')
                        ->group_by('Message.id')
                        ->order_by('MessageConversation.createdAt','asc')
                        ->get_where('Message',['customerId'=>$this->customerId])->result();
    }*/

    public function allPesan() {
        return $this->db->query("SELECT Message.id, Message.merchantId, CustomerId, Message.subject, conversationCount, Merchant.name,
            (SELECT GROUP_CONCAT(message SEPARATOR ' : ') FROM MessageConversation WHERE messageId=Message.id AND readAt IS NULL ORDER BY createdAt ASC)AS new_msg,
            last_conversation.message, last_conversation.createdAt, last_conversation.readAt,
            (SELECT COUNT(*) FROM UserNotification WHERE contentId=Message.`id` AND contentType='Message') count_notif
            FROM Message
            LEFT JOIN (SELECT * FROM MessageConversation GROUP BY messageId ) AS last_conversation
            ON last_conversation.messageId=Message.id
            LEFT JOIN Merchant ON Merchant.id=Message.merchantId
            WHERE Message.customerId = ".$this->customerId."
            ORDER BY last_conversation.createdAt ASC")->result();
    }

    public function numReadAt($messageId) {
        return $this->db->get_where('MessageConversation',['messageId' => $messageId, 'readAt' => null])->num_rows();
    }

    public function allPesanDetail($messageId) {
        return $this->db->select('MessageConversation.*, User.realm, User.username, User.email')
                        ->join('User','User.id = MessageConversation.modifiedBy','left')
                        ->order_by('MessageConversation.createdAt', 'ASC')
                        ->get_where('MessageConversation',['messageId' => $messageId])->result();
    }

    public function jmlPesanBaru($userId) {
        return $this->db->join('MessageConversation','MessageConversation.messageId = Message.id', 'left')->get_where('Message', ['customerId' => $userId, 'MessageConversation.readAt' => null])->num_rows();
    }

    public function getMsgById($messageId) {
        return $this->db->query("SELECT Message.id, Message.merchantId, CustomerId, Message.subject, conversationCount, Merchant.name, Customer.firstName,
            last_conversation.message, last_conversation.createdAt, last_conversation.readAt
            FROM Message
            LEFT JOIN (SELECT * FROM MessageConversation GROUP BY messageId ) AS last_conversation
            ON last_conversation.messageId=Message.id
            LEFT JOIN Merchant ON Merchant.id=Message.merchantId
            LEFT JOIN Customer ON Customer.id=Message.CustomerId
            WHERE Message.customerId = ".$this->customerId." AND Message.id='".$messageId."'
            ORDER BY last_conversation.readAt ASC")->row();
    }
}
