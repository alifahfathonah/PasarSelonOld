<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Master {

    protected $CI;

    // We'll use a constructor, as you can't directly call a function
    // from a property definition.
    public function __construct()
    {
            // Assign the CodeIgniter super-object
            $this->CI =& get_instance();
    }

    public static function getAllCategories()
    {   
        $CI =& get_instance();
        $db = $CI->load->database('default', TRUE);
        $result = $CI->db->get_where('ProductCategory', array('parent' => NULL));
        $obj = new Master;
        $obj->productCategory = $result;
    }

    function get_change($params=array(), $nid='',$name,$id,$class='',$required='',$inline='') {
        
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        
        if($nid!=''){
            $data = $db->get($params['table'])->result_array();
        }else{
            $data = array();
        }

        $selected = $nid?'':'selected';
        $readonly = '';//$CI->session->userdata('nrole')=='approver'?'readonly':'';
        
        $starsign = $required?'*':'';
        
        $fieldset = $inline?'':'<fieldset>';
        $fieldsetend = $inline?'':'</fieldset>';
        
        $field='';
        $field.=$fieldset.'
        <select class="'.$class.'" name="'.$name.'" id="'.$id.'" '.$readonly.' '.$required.' >
            <option value="" '.$selected.'> - Silahkan pilih - </option>';
                foreach($data as $row){
                    $sel = $nid==$row[$params['id']]?'selected':'';
                    $field.='<option value="'.$row[$params['id']].'" '.$sel.' >'.$row[$params['name']].'</option>';
                }
                
            
        $field.='
        </select>
        '.$fieldsetend;
        return $field;
        
    }

    function get_change_city($params=array(), $nid='',$name,$id,$class='',$required='',$inline='') {

        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);

        if($nid!=''){
            $data = $db->get($params['table'])->result_array();
        }else{
            $data = array();
        }

        $selected = $nid?'':'selected';
        $readonly = '';//$CI->session->userdata('nrole')=='approver'?'readonly':'';

        $starsign = $required?'*':'';

        $fieldset = $inline?'':'<fieldset>';
        $fieldsetend = $inline?'':'</fieldset>';

        $field='';
        $field.=$fieldset.'
        <select class="'.$class.'" name="'.$name.'" id="'.$id.'" '.$readonly.' '.$required.' >
            <option value="" '.$selected.'> - Silahkan pilih - </option>';
        foreach($data as $row){
            $sel = $nid==$row[$params['id']]?'selected':'';
            $field.='<option value="'.$row[$params['id']].'" '.$sel.' >'.strtoupper($row[$params['type']]). ' - '. $row[$params['type']] .'</option>';
        }


        $field.='
        </select>
        '.$fieldsetend;
        return $field;

    }

    function get_custom($params=array(), $nid='',$name,$id,$class='',$required='',$inline='') {
        
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        if(isset($params['group_by'])){
            $db->group_by($params['group_by']);
        }
        if($params['table'] =='PaymentMethod'){
            $db->where('id != 0');
        }
        $db->where($params['where']);

        $data = $db->get($params['table'])->result_array();
        
        $selected = $nid?'':'selected';
        $readonly = '';//$CI->session->userdata('nrole')=='approver'?'readonly':'';
        
        $starsign = $required?'*':'';
        
        $fieldset = $inline?'':'<fieldset>';
        $fieldsetend = $inline?'':'</fieldset>';
        
        $field='';
        $field.='
        <select class="'.$class.'" name="'.$name.'" id="'.$id.'" '.$readonly.' '.$required.' >
           ';
        
        $field .= '<option value="" '.$selected.'> - Silahkan pilih - </option>';
        foreach($data as $row){
            $sel = $nid==$row[$params['id']]?'selected':'';
            $field.='<option value="'.$row[$params['id']].'" '.$sel.' >'.$row[$params['name']].'</option>';
        }   
            
        $field.='
        </select>
        ';
        
        return $field;
        
    }

    function get_customer_address($params=array(), $nid='',$name,$id,$class='',$required='',$inline='') {
        
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        $db->where($params['where']);

        $data = $db->get($params['table'])->result_array();
        
        $selected = $nid?'':'selected';
        $readonly = '';//$CI->session->userdata('nrole')=='approver'?'readonly':'';
        
        $starsign = $required?'*':'';
        
        $fieldset = $inline?'':'<fieldset>';
        $fieldsetend = $inline?'':'</fieldset>';
        
        $field='';
        $field.='
        <select class="'.$class.'" name="'.$name.'" id="'.$id.'" '.$readonly.' '.$required.' >
           ';
        
        $field .= '<option value="" '.$selected.'> - Silahkan pilih - </option>';
        $field .= '<option value="10c9f08d-3645-11e7-a8f3-001c429bf617"> KMART Telkomsel (TSO) </option>';
        foreach($data as $row){
            $sel = $nid==$row[$params['id']]?'selected':'';
            $field.='<option value="'.$row[$params['id']].'" '.$sel.' >'.ucwords($row[$params['name']]).' - '.substr($row['address'], 0,30).'...</option>';
        }   
            
        $field.='
        </select>
        ';
        
        return $field;
        
    }


}
