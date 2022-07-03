<?php

class ControllerCommonfooterclient extends Controller
{

    protected function index ()
    {
        $this->language->load('common/footer');
        
        $this->template = $this->config->get('config_template') . '/template/common/footerclient.php';
        
        $this->render();
    }
}

?>