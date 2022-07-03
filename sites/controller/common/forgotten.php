<?php

class Controllercommonforgotten extends Controller
{

    private $error = array();

    public function index ()
    {
        if ($this->customer->isLogged()) {
            $this->redirect($this->url->link('common/login', '', 'SSL'));
        }
        
        $this->language->load('common/forgotten');
        
        $this->load->model('facilities/facilities');
        
        $this->document->setTitle($this->language->get('heading_title'));
        $this->data['form_outputkey'] = $this->formkey->outputKey();
        // $this->load->model('common/customer');
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->language->load('mail/forgotten');
            
            $password = substr(sha1(uniqid(mt_rand(), true)), 0, 10);
            
            $this->model_facilities_facilities->editPassword($this->request->post['email'], $password);
            
            $subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));
            
            $message = sprintf($this->language->get('text_greeting'), $this->config->get('config_name')) . "\n\n";
            $message .= $this->language->get('text_password') . "\n\n";
            $message .= $password;
            
            $mail = new Mail();
            $mail->protocol = $this->config->get('config_mail_protocol');
            $mail->parameter = $this->config->get('config_mail_parameter');
            $mail->hostname = $this->config->get('config_smtp_host');
            $mail->username = $this->config->get('config_smtp_username');
            $mail->password = $this->config->get('config_smtp_password');
            $mail->port = $this->config->get('config_smtp_port');
            $mail->timeout = $this->config->get('config_smtp_timeout');
            $mail->setTo($this->request->post['email']);
            $mail->setFrom($this->config->get('config_email'));
            $mail->setSender($this->config->get('config_name'));
            $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
            $mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
            $mail->send();
            
            $this->session->data['success'] = $this->language->get('text_success');
            
            $this->redirect($this->url->link('common/login', '', 'SSL'));
        }
        
        $this->data['breadcrumbs'] = array();
        
        $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/home'),
                'separator' => false
        );
        
        $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_common'),
                'href' => $this->url->link('common/common', '', 'SSL'),
                'separator' => $this->language->get('text_separator')
        );
        
        $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_forgotten'),
                'href' => $this->url->link('common/forgotten', '', 'SSL'),
                'separator' => $this->language->get('text_separator')
        );
        
        $this->data['heading_title'] = $this->language->get('heading_title');
        
        $this->data['text_your_email'] = $this->language->get('text_your_email');
        $this->data['text_email'] = $this->language->get('text_email');
        
        $this->data['entry_email'] = $this->language->get('entry_email');
        
        $this->data['button_continue'] = $this->language->get('button_continue');
        $this->data['button_back'] = $this->language->get('button_back');
        
        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }
        
        $this->data['action'] = $this->url->link('common/forgotten', '', 'SSL');
        
        $this->data['back'] = $this->url->link('common/login', '', 'SSL');
        $this->template = $this->config->get('config_template') . '/template/common/forgotten.php';
        
        $this->children = array(
                'common/footer',
                'common/header'
        );
        
        $this->response->setOutput($this->render());
    }

    protected function validate ()
    {
        if ($this->request->post['form_key'] != null && $this->request->post['form_key'] != "") {
            $formkeyerror = $this->formkey->validate($this->request->post['form_key']);
        }
        if ($this->request->post['email'] == null && $this->request->post['email'] == "") {
            $this->error['warning'] = $this->language->get('error_email');
        } elseif (! $this->model_facilities_facilities->getTotalfacilitiessByEmail($this->request->post['email'])) {
            $this->error['warning'] = $this->language->get('error_email');
        }
        if (! $this->error) {
            return true;
        } else {
            return false;
        }
    }
}
?>