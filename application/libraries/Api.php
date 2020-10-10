<?php

/*
 * To change this template, choose Tools | templates
 * and open the template in the editor.
 */

final class Api
{

    // ================================= DASHBOARD =================================== //
    public function getApiData($params,$addOpts=[])
    {
        //print_r($params);
        $url = $params['link'];
        $data = $params['data'];

        $field_string = http_build_query($data);
        $options = array(
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER         => false,    // don't return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_ENCODING       => "",       // handle all encodings
            CURLOPT_AUTOREFERER    => true,     // set referer on redirect
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
            CURLOPT_SSL_VERIFYPEER => false,     // Disabled SSL Cert checks
            CURLOPT_SSL_VERIFYHOST => false,
        );
        if(!empty($addOpts)) array_merge($options,$addOpts); 
        
        $ch = curl_init($url);
        curl_setopt_array($ch, $options);
        if (!isset($params['method'])) {
            if (!empty($data)) :
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $field_string);
            endif;
        } elseif ($params['method'] == 'PUT') {
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $field_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        } elseif ($params['method'] == 'DELETE') {
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        } elseif ($params['method'] == 'POST'){
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $field_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        }
        // execute!
        $response = curl_exec($ch);
        // close the connection, release resources used

        curl_close($ch);

        // do anything you want with your response
        return json_decode($response);
    }
}
