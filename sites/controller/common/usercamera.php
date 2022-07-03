<?php

class ControllerCommonusercamera extends Controller
{

    private $error = array();

    public function index ()
    {
        $this->template = $this->config->get('config_template') . '/template/common/usercamera.php';
        
        $this->response->setOutput($this->render());
    }
}