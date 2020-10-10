<?php

/*
 * To change this template, choose Tools | templates
 * and open the template in the editor.
 */

final class Notification {

    public function save($params) {
        $CI =& get_instance();
        $db = $CI->load->database('default', TRUE);
        return $db->insert('UserNotification', $params);
    }

}

?>
