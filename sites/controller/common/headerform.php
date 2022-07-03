<?php   
class Controllercommonheaderform extends Controller {
	protected function index() {
		try{
			/*if (!$this->customer->isLogged()) {
				$this->redirect($this->url->link('common/login', '', 'SSL'));
			}
			*/
			$this->document->setTitle('Forms');
			$this->data['heading_title'] = 'Forms';
				
			$this->load->model('facilities/online');
			$datafa = array();
			$datafa['username'] = $this->session->data['webuser_id'];
			$datafa['activationkey'] = $this->session->data['activationkey'];
			$datafa['facilities_id'] = $this->customer->getId();
			$datafa['ip'] = $this->request->server['REMOTE_ADDR'];
			$this->data['form_outputkey'] = $this->formkey->outputKey();
			$this->language->load('notes/notes');
			
			$this->model_facilities_online->updatefacilitiesOnline2($datafa);

			
			$this->data['title'] = $this->document->getTitle();
		
			if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
				$server = $this->config->get('config_ssl');
			} else {
				$server = $this->config->get('config_url');
			}
			
			if (isset($this->session->data['error']) && !empty($this->session->data['error'])) {
				$this->data['error'] = $this->session->data['error'];
				
				unset($this->session->data['error']);
			} else {
				$this->data['error'] = '';
			}

			$this->data['base'] = $server;
			
			
			$this->template = $this->config->get('config_template') . '/template/common/headerform.php';

			$this->render();
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in Sites Common headerform',
			);
			$this->model_activity_activity->addActivity('sitesheaderform', $activity_data2);
		
		
		} 
	} 	
}
?>
