<?php

class ControllerCommonHeaderlogin extends Controller
{

    protected function index ()
    {
        try {
            $this->language->load('common/header');
            $this->data['title'] = $this->document->getTitle();
            $this->data['form_outputkey'] = $this->formkey->outputKey();
            if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
                $server = $this->config->get('config_ssl');
            } else {
                $server = $this->config->get('config_url');
            }
            
            if (isset($this->session->data['error']) && ! empty($this->session->data['error'])) {
                $this->data['error'] = $this->session->data['error'];
                
                unset($this->session->data['error']);
            } else {
                $this->data['error'] = '';
            }
            
            $this->data['base'] = $server;
            
            $this->data['route'] = $this->request->get['route'];
            
            $this->template = $this->config->get('config_template') . '/template/common/headerlogin.php';
            
            $this->render();
        } catch (Exception $e) {
            
            $this->load->model('activity/activity');
            $activity_data2 = array(
                    'data' => 'Error in Sites Common Header List login'
            );
            $this->model_activity_activity->addActivity('sitesheaderlogin_list', $activity_data2);
        }
    }
}
?>
