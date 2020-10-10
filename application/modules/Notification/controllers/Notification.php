<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notification extends MX_Controller {

    public function index($id='') {
        /*get notif by id*/
        $notif = $this->db->get_where('UserNotification', array('id' => $id))->row();

        $link = '';

        if($notif->contentType == 'Complaint') {
            $link = 'Pesan/Komplain/detail?tiket='.$notif->contentId;
        }elseif ($notif->contentType == 'Invoice') {
            $link = $notif->contentLink;
        }elseif ($notif->contentType == 'Order') {
            $link = $notif->contentLink;
        }else{
            $link = $notif->contentLink;
        }

		$this->db->delete('UserNotification', array('id' => $id));

		redirect($link);
    }

}