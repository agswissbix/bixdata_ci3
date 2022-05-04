<?php

class Custom_controller extends CI_Controller {
    
    function __construct()
    {
        parent::__construct();
    }
    
    public function sync_iscritti()
    {
        $json = file_get_contents("http://www.about-x.info/registrazione/sys_viewcontroller/get_iscritti/");
        $iscritti = json_decode($json,true);
        $this->Sys_model->insert_iscritti($iscritti);
    }
    
    
    
}
