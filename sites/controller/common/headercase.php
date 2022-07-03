<?php   
class Controllercommonheadercase extends Controller {
	protected function index() {
		try{
			
			$this->document->setTitle('Forms');
			$this->data['heading_title'] = 'Forms';
				
			$this->load->model('facilities/online');
			$datafa = array();
			$datafa['username'] = $this->session->data['username'];
			$datafa['activationkey'] = $this->session->data['activationkey'];
			$datafa['facilities_id'] = $this->customer->getId();
			$datafa['ip'] = $this->request->server['REMOTE_ADDR'];
			$this->data['form_outputkey'] = $this->formkey->outputKey();
			$this->language->load('notes/notes');
			
			$this->model_facilities_online->updatefacilitiesOnline2($datafa);

			
			$this->data['title'] = $this->document->getTitle();
			$this->data['description'] = $this->document->getDescription();
            $this->data['keywords'] = $this->document->getKeywords();
            $this->data['links'] = $this->document->getLinks();
            $this->data['name'] = $this->config->get('config_name');
            $this->data['form_outputkey'] = $this->formkey->outputKey();
            $this->data['text_logout'] = $this->language->get('text_logout');
			
			if ($this->request->get['popup'] != null && $this->request->get['popup'] != "") {
				$url2 .= '&popup=' . $this->request->get['popup'];
				$url3 .= '&popup=' . $this->request->get['popup'];
				$this->data['popup'] =  $this->request->get['popup'];
			}
		
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
			
			$this->data['logout'] = $this->url->link('common/logout', '', 'SSL');
                
            $this->data['home'] = $this->url->link('common/home');
			
            $this->data['clienturl'] = $this->url->link('case/clients');
            $this->data['calendarurl'] = $this->url->link('case/calendar&type=4');
            $this->data['taskurl'] = $this->url->link('case/clients/tasks');
            $this->data['medicationurl'] = $this->url->link('case/clients/detail&type=3');
            $this->data['formurl'] = $this->url->link('case/forms');
			
            $this->data['archiveclienturl'] = $this->url->link('report/report');
            $this->data['archiveformurl'] = $this->url->link('report/forms');
			
			 $this->data['allclient_url'] = $this->url->link('case/clients', '' . $url2, 'SSL');
			$this->data['waitclient_url'] = $this->url->link('case/clients&wait_list=1', '' . $url2, 'SSL');
			$this->data['disclient_url'] = $this->url->link('case/clients&discharge=1', '' . $url2, 'SSL');
			
			
			$this->data['fromsclient_url'] = $this->url->link('case/clients&froms=1', '' . $url2, 'SSL');
			$this->data['taskclient_url'] = $this->url->link('case/clients&task=1', '' . $url2, 'SSL');
			$this->data['keywordsclient_url'] = $this->url->link('case/clients&keyword=1', '' . $url2, 'SSL');
		
		
			$this->template = $this->config->get('config_template') . '/template/common/headercase.php';

			$this->render(); 
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in Sites Common headercase',
			);
			$this->model_activity_activity->addActivity('headercase', $activity_data2);
		
		
		} 
	} 	
}
?>
