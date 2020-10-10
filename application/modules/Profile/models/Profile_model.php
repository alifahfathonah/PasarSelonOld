<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile_model extends CI_Model {

    /*address*/
    var $table = 'CustomerAddress';
    var $column = array('id', 'name','recipientName','recipientPhone','address','districtName','cityName','countryName');
    var $order = array('id' => 'desc');

    /*bank account*/
    var $table_bank_account = 'CustomerBankAccount';
    var $column_bank_account = array('id', 'bankName','location','accountNo','accountName');
    var $order_bank_account = array('id' => 'desc');

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->customerId = ($this->session->userdata('logged_in')) ? $this->session->userdata('user')->id : 0;
    }

    public function simpan_biodata($alias,$birthday) {
        /*update user profile*/
        $query = $this->db->update('Customer',array('alias' => $alias, 'birthday' => $birthday), array('id' => $this->session->userdata('user')->id));
        return $query;
    }

    function getProfileCustomerWithApi() {
        $params = [
            'link'   => API_DATA . 'Customers/'.$this->customerId.'/profile?access_token='.$this->session->userdata('token'),
            'data'  => array(),
        ];

        $data = $this->api->getApiData($params);
        //print_r($data);die;
        return $data;
    }

    function getUserTabel() {
        return $this->db->get_where('User', ['id' => $this->customerId])->row();
    }

    function getProfileCustomer() {
        $params = [
            'link'   => CMS. 'v1/customer/'.$this->customerId.'/profile',
            'data'  => array(),
        ];

        $data = $this->api->getApiData($params);
        return $data;
    }

    private function _get_datatables_address_query()
    {
        
        $this->db->from($this->table);
        $this->db->where(array('customerId' => $this->customerId));

        $i = 0;
    
        foreach ($this->column as $item) 
        {
            if(isset($_POST['search']) && $_POST['search']['value'])
                ($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item, $_POST['search']['value']);
            $column[$i] = $item;
            $i++;
        }
        
        if(isset($_POST['order']))
        {
            $this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables_address()
    {
        $this->_get_datatables_address_query();
        if(isset($_POST['length']) && $_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_address_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        $this->db->where(array('customerId' => $this->customerId));
        return $this->db->count_all_results();
    }

    private function _get_datatables_bank_account_query()
    {
        
        $this->db->from($this->table_bank_account);
        $this->db->where(array('customerId' => $this->customerId));

        $i = 0;
    
        foreach ($this->column_bank_account as $item) 
        {
            if(isset($_POST['search']) && $_POST['search']['value'])
                ($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item, $_POST['search']['value']);
            $column[$i] = $item;
            $i++;
        }
        
        if(isset($_POST['order']))
        {
            $this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order_bank_account))
        {
            $order = $this->order_bank_account;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables_bank_account()
    {
        $this->_get_datatables_bank_account_query();
        if(isset($_POST['length']) && $_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered_bank_account()
    {
        $this->_get_datatables_bank_account_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all_bank_account()
    {
        $this->db->from($this->table_bank_account);
        $this->db->where(array('customerId' => $this->customerId));
        return $this->db->count_all_results();
    }


}
