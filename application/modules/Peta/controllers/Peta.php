<?php defined('BASEPATH') or exit('No direct script access allowed');

class Peta extends MX_Controller
{
    const API_KEY='proAbr24bB3DV1H7bKiE5uM9nJQooJzV';
    public function __construct(){
      parent::__construct();
    }
    public function petaku(){
        $data = ['api_key'=>self::API_KEY,
                'tomtom_links'=>[
                                 'https://api.tomtom.com/maps-sdk-for-web/cdn/5.x/5.60.0/maps/maps.css', 
                                 base_url('assets/tomtom-assets/ui-library/index.css')
                ],
                'tomtom_scripts'=>[
                  'https://api.tomtom.com/maps-sdk-for-web/cdn/5.x/5.60.0/maps/maps-web.min.js',
                  base_url('assets/tomtom-assets/js/mobile-or-tablet.js') 
                ]
        ];
        $this->template->load($data,'peta');
    }
}