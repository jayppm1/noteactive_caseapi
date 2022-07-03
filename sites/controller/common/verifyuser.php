<?php

class ControllerCommonVerifyuser extends Controller
{

    private $error = array();

    public function index ()
    {
		
		
		
		if ($this->session->data['username'] == null && $this->session->data['username'] == "") {
            $this->redirect($this->url->link('common/licence/activation', '', 'SSL'));
        }else{
			$this->data['username']  = $this->session->data['username'];
		}
		
		
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->redirect($this->url->link('common/login&step=2', '', 'SSL'));
		}
		
		if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }
		
		if ($this->request->get['share_note_otp'] != null && $this->request->get['share_note_otp'] != "") {
            $url2 .= '&share_note_otp=' . $this->request->get['share_note_otp'];
        }
		
		
        $this->data['action'] = $this->url->link('common/verifyuser', '' . $url2, 'SSL');
		
		
		$this->template = $this->config->get('config_template') . '/template/common/verifyuser.php';
        $this->children = array(
                'common/headerlogin',
                'common/footerlogin'
        );
        
        $this->response->setOutput($this->render());
		
		
	}
	
	
	 public function validateForm ()
    {
	
		if(($this->request->post['username'] == NULL && $this->request->post['username'] == "" ) && ( $this->request->post['password'] == NULL && $this->request->post['password'] == "" )){
			 $this->error['warning'] = 'Username & Password is required !';
		}
	
		$this->load->model('user/user');
		$getUser = $this->model_user_user->getuserbynamenpass($this->request->post['username'],$this->request->post['password']);
		
		
		if(empty($getUser)){
			 $this->error['warning'] = 'Invalid Username or Password !';
		}
	
		if (! $this->error) {
            return true;
        } else {
            return false;
        }
		
	}
	
}