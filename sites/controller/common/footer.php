<?php

class ControllerCommonFooter extends Controller
{

    protected function index ()
    {
        $this->language->load('common/footer');
        
        $this->load->model('facilities/online');
        $userId = $this->customer->isLogged();
        $ip = $this->request->server['REMOTE_ADDR'];
        $this->model_facilities_online->updatefacilitiesOnline2($userId, $ip);
        
        $this->data['text_footer'] = sprintf($this->language->get('text_footer'), VERSION);
        
        $this->template = $this->config->get('config_template') . '/template/common/footer.php';
        
        $this->render();
    }
}

?>