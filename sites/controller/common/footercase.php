<?php

class Controllercommonfootercase extends Controller
{

    protected function index ()
    {
        $this->language->load('common/footer');
		 if ($this->request->get['popup'] != null && $this->request->get['popup'] != "") {
           $url2 .= '&popup=' . $this->request->get['popup'];
			$this->data['popup'] =  $this->request->get['popup'];
        }
        
        $this->data['text_footer'] = sprintf($this->language->get('text_footer'), VERSION);
        
        $this->data['route'] = $this->request->get['route'];
        
        $this->template = $this->config->get('config_template') . '/template/common/footercase.php';
        
        $this->render();
    }
}

?>