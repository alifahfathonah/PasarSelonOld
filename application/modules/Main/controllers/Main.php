<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Main extends MX_Controller
{

    function __construct()
    {

        parent::__construct();
        //        $this->_checkRedis();
	//$this->output->enable_profiler(TRUE);
    }

    public function index()
    {
        //        $this->output->enable_profiler(false);
        // Initialize the array with a 'title' element for use for the <title> tag.
        $data = array(
            'title' => 'Halal Shopping',
        );
        $data['page'] = 'home';

        $this->template->load($data, 'main');
    }
}

/* End of file empty_module.php */
/* Location: ./application/modules/empty_module/controllers/empty_module.php */
