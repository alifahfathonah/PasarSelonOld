<?php

class Cart_model extends CI_Model { // Our Cart_model class extends the Model class

    public function __construct() {
        parent::__construct();
        $this->customerId = ($this->session->userdata('logged_in')) ? $this->session->userdata('user')->id : 0;
    }

    function check_item($productId, $customerId) {
        /*query get previous cart */
        $query = $this->db->where(array('productId' => $productId, 'customerId' => $customerId))->get('Cart');
        /*if query is not null*/
        if($query->num_rows() > 0 ) {
            return $query;
        }else{
            return false;
        }
    }

    public function productQty($productId) {
        $product = $this->db->where(['id' => $productId])->get('Product')->row();

        return $product->stock;
    }

    public function retrieve_products()
    {
        return [];
    }

    private function _get_datatables_query()
    {
        
        $this->db->from($this->table);

        $i = 0;
    
        foreach ($this->column as $item) 
        {
            if($_POST['search']['value'])
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

    function get_data_pembayaran()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    function deleteCart($id) {
        $this->db->where(array('customerId' => $this->customerId, 'productId' => $id));
        $query = $this->db->delete('Cart');
        return $query;
    }
    
    function complaintProses($orderId, $productId, $merchantId,$subject,$msg) {
        $data = array();
        $data['orderId'] = $orderId;
        $data['productId'] = $productId;
        $data['merchantId'] = $merchantId;
        $data['subject'] = $subject;
        $data['message'] = $msg;
        
        $user = $this->session->userdata('user');
        
        $params = [
            'link'   => API_DATA . 'Customers/'.$user->id.'/'.'complaint?access_token='.$this->session->userdata('token'),
            'data'  => $data
        ];

        $ress = $this->api->getApiData($params);
        
        return $ress;
    }

    function getComplaintByOrder($orderId, $productId){
        return $this->db->join('ComplaintStatus','ComplaintStatus.id=Complaint.status','left')->get_where('Complaint', ['orderId' => $orderId, 'productId' => $productId]);
    }

    function getStatusOrder($orderId){
        $query = 'SELECT orderId, OrderLog.updatedAt, MAX(`status`) AS `status`, (SELECT NAME FROM OrderStatus WHERE id=MAX(OrderLog.status)) AS status_name
            FROM `OrderLog`
            WHERE orderId="'.str_replace(' ','',$orderId).'"';
        return $this->db->query($query)->row();
    }
    
}

/* End of file cart_model.php */
/* Location: ./application/models/cart_model.php */