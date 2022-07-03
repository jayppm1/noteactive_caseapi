<?php

class ControllerCommonFooterlogin extends Controller
{

    protected function index ()
    {
        $this->language->load('common/footer');
        
        $this->data['text_footer'] = sprintf($this->language->get('text_footer'), VERSION);
        
        $this->data['route'] = $this->request->get['route'];
        
        $this->template = $this->config->get('config_template') . '/template/common/footerlogin.php';
        
        $this->render();
    }
}

?>