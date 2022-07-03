<?php

class ControllerCommongetreports extends Controller
{

    private $error = array();

    public function index () {
		
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			
			if ($this->request->get['id'] != null && $this->request->get['id'] != "") {
				$url2 .= '&id=' . $this->request->get['id'];
			}
			//$url2 .= '&authorised=1';
			$this->session->data['authorised'] = "1";
			$this->redirect($this->url->link('common/getreports', ''.$url2, 'SSL'));
		}
		
		if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }
		
		if ($this->request->get['id'] != null && $this->request->get['id'] != "") {
            $url2 .= '&id=' . $this->request->get['id'];
        }
		
		if (isset($this->request->post['username'])) {
            $this->data['username'] = $this->request->post['username'];
        } else {
            $this->data['username'] = '';
        }
		
		if (isset($this->request->post['user_pin'])) {
            $this->data['user_pin'] = $this->request->post['user_pin'];
        } else {
            $this->data['user_pin'] = '';
        }
		
		if (isset($this->session->data['authorised'])) {
            $this->data['authorised'] = $this->session->data['authorised'];
			
			$this->load->model('customer/customer');
		
			$getreport_info = $this->model_customer_customer->getbulkreport($this->request->get['id']);
			
			$reponse = json_decode($getreport_info['response']);
			
			$this->data['filedatas'] = $reponse->filedata;
			
            unset($this->session->data['authorised']);
        } else {
            $this->data['authorised'] = '';
        }
		
		
		
        $this->data['action'] = $this->url->link('common/getreports', '' . $url2, 'SSL');
		
		
		$this->template = $this->config->get('config_template') . '/template/common/getreports.php';
        $this->children = array(
                'common/headerlogin',
                'common/footerlogin'
        );
        
        $this->response->setOutput($this->render());
		
		
	}
	
	
	 public function validateForm ()
    {
	
		if($this->request->post['user_pin'] == NULL && $this->request->post['user_pin'] == "" ){
			$this->error['warning'] = 'This is required field!';
		}
		if($this->request->post['user_pin'] != NULL && $this->request->post['user_pin'] != "" ){
			$this->load->model('user/user');
			$getUser = $this->model_user_user->getUserdetailuserpin($this->request->post['user_pin']);
			
			$this->load->model('customer/customer');
		
			$getreport_info = $this->model_customer_customer->getbulkreport($this->request->get['id']);
			
			
			if(empty($getUser)){
				$this->error['warning'] = 'Please enter valid user!';
				
			}else{
				if($getreport_info['user_ids'] != null && $getreport_info['user_ids'] != ""){
					$sssssddsg = explode ( ",", $getreport_info['user_ids']);
					
					if(!in_array($getUser['user_id'], $sssssddsg)){
						$this->error['warning'] = 'Please enter valid user!';
					}
				}else{
					$this->error['warning'] = 'Please enter valid user!';
				}
			}
		}
	
		if (! $this->error) {
            return true;
        } else {
            return false;
        }
		
	}
	
}