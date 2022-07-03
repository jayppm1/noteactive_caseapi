<?php

class ControllerCaseSearchresult extends Controller
{

    public function index ()
    {
        $this->language->load('common/home');
        $this->document->setTitle($this->config->get('config_title'));
        $this->document->setDescription($this->config->get('config_meta_description'));
        
        $this->data['heading_title'] = $this->config->get('config_title');
        
        $this->data['form_outputkey'] = $this->formkey->outputKey();
        // $this->load->model('resident/report');
        // $this->data['assigntos'] =
        // $this->model_resident_report->getassigns();
        
        $this->load->model('notes/image');
        $this->load->model('setting/highlighter');
        $this->load->model('user/user');
        $this->load->model('notes/tags');
        
        unset($this->session->data['media_user_id']);
        unset($this->session->data['media_signature']);
        unset($this->session->data['media_pin']);
        unset($this->session->data['emp_tag_id']);
        unset($this->session->data['tags_id']);
        
        $this->template = $this->config->get('config_template') . '/template/case/report.php';
        
        $this->children = array(
                'common/footer',
                'common/header'
        )
        ;
        
        $this->response->setOutput($this->render());
    }
}
	