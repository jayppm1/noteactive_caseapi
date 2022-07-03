<?php
class Controllernotesvideo extends Controller {
private $error = array();

	public function index(){
	
		$this->template = $this->config->get('config_template') . '/template/notes/usercamera.php';
		$this->response->setOutput($this->render());

	
	}
	
	public function livevideo(){
		
		$this->language->load ( 'notes/notes' );
		
		$this->load->model ( 'facilities/online' );
		$datafa = array ();
		$datafa ['username'] = $this->session->data ['webuser_id'];
		$datafa ['activationkey'] = $this->session->data ['activationkey'];
		$datafa ['facilities_id'] = $this->customer->getId ();
		$datafa ['ip'] = $this->request->server ['REMOTE_ADDR'];
		$this->data ['form_outputkey'] = $this->formkey->outputKey ();
		$this->model_facilities_online->updatefacilitiesOnline2 ( $datafa );
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$this->load->model ( 'notes/notes' );
		
		if (($this->request->post['form_submit'] == '1') && $this->validateFormp2 () ) {
			
			$this->session->data ['livevideos'] =  $this->request->post;
			
			$this->redirect (  str_replace ( '&amp;', '&', $this->url->link ( 'notes/video/livevideourl', '' . $url3, 'SSL' ) ) );
		}
		
		
		$url2 = "";
		
		
		if ($this->request->get ['searchdate'] != null && $this->request->get ['searchdate'] != "") {
			$url2 = '&searchdate=' . $this->request->get ['searchdate'];
		}
		
		$this->data['action'] = str_replace('&amp;', '&', $this->url->link('notes/video/livevideo', '' . $url2, 'SSL'));
		
		
		if (isset ( $this->error ['warning'] )) {
			$this->data ['error_warning'] = $this->error ['warning'];
		} else {
			$this->data ['error_warning'] = '';
		}
		if (isset ( $this->error ['tags_id'] )) {
			$this->data ['error_tags_id'] = $this->error ['tags_id'];
		} else {
			$this->data ['error_tags_id'] = '';
		}
		if (isset ( $this->error ['user_id'] )) {
			$this->data ['error_user_id'] = $this->error ['user_id'];
		} else {
			$this->data ['error_user_id'] = '';
		}
		if (isset ( $this->error ['facility_id'] )) {
			$this->data ['error_facility_id'] = $this->error ['facility_id'];
		} else {
			$this->data ['error_facility_id'] = '';
		}
		if (isset ( $this->error ['mobile_number'] )) {
			$this->data ['error_mobile_number'] = $this->error ['mobile_number'];
		} else {
			$this->data ['error_mobile_number'] = '';
		}
		
		if (isset ( $this->session->data ['success'] )) {
			$this->data ['success'] = $this->session->data ['success'];
			
			unset ( $this->session->data ['success'] );
		} else {
			$this->data ['success'] = '';
		}
		if (isset ( $this->session->data ['review_success'] )) {
			$this->data ['review_success'] = $this->session->data ['review_success'];
			
			unset ( $this->session->data ['review_success'] );
		} else {
			$this->data ['review_success'] = '';
		}
		
		
		if (isset ( $this->request->post ['calling_by'] )) {
			$this->data ['calling_by'] = $this->request->post ['calling_by'];
		} else {
			$this->data ['calling_by'] = '';
		}
		
		if (isset ( $this->request->post ['client_name'] )) {
			$this->data ['client_name'] = $this->request->post ['client_name'];
		} else {
			$this->data ['client_name'] = '';
		}
		
		if (isset ( $this->request->post ['tags_id'] )) {
			$this->data ['tags_id'] = $this->request->post ['tags_id'];
		} else {
			$this->data ['tags_id'] = '';
		}
		if (isset ( $this->request->post ['username'] )) {
			$this->data ['username'] = $this->request->post ['username'];
		} else {
			$this->data ['username'] = '';
		}
		
		if (isset ( $this->request->post ['user_id'] )) {
			$this->data ['user_id'] = $this->request->post ['user_id'];
		} else {
			$this->data ['user_id'] = '';
		}
		
		if (isset ( $this->request->post ['facility_name'] )) {
			$this->data ['facility_name'] = $this->request->post ['facility_name'];
		} else {
			$this->data ['facility_name'] = '';
		}
		
		if (isset ( $this->request->post ['facility_id'] )) {
			$this->data ['facility_id'] = $this->request->post ['facility_id'];
		} else {
			$this->data ['facility_id'] = '';
		}
		
		if (isset ( $this->request->post ['mobile_number'] )) {
			$this->data ['mobile_number'] = $this->request->post ['mobile_number'];
		} else {
			$this->data ['mobile_number'] = '';
		}
		
		
		$this->children = array (
				'common/headerpopup' 
		);
		
		$this->template = $this->config->get('config_template') . '/template/notes/livevideo.php';
		$this->response->setOutput($this->render());

	
	}
	protected function validateFormp2() {
		if ($this->request->post ['form_key'] != null && $this->request->post ['form_key'] != "") {
			$formkeyerror = $this->formkey->validate ( $this->request->post ['form_key'] );
		}
		if ($this->request->post ['calling_by'] == '1') {
			if ($this->request->post ['username'] == null && $this->request->post ['username'] == "") {
				$this->error ['user_id'] = $this->language->get ( 'error_required' );
			}
		}
		
		if ($this->request->post ['calling_by'] == '2') {
			if ($this->request->post ['client_name'] == null && $this->request->post ['client_name'] == "") {
				$this->error ['tags_id'] = $this->language->get ( 'error_required' );
			}
		}
		if ($this->request->post ['calling_by'] == '3') {
			if ($this->request->post ['facility_name'] == null && $this->request->post ['facility_name'] == "") {
				$this->error ['facility_id'] = $this->language->get ( 'error_required' );
			}
		}
		if ($this->request->post ['calling_by'] == '4') {
			if ($this->request->post ['mobile_number'] == null && $this->request->post ['mobile_number'] == "") {
				$this->error ['mobile_number'] = $this->language->get ( 'error_required' );
			}
		}
		
		
		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}
	
	
	public function livevideourl(){
		
		
		$this->children = array (
				'common/headerpopup' 
		);
		
		$this->template = $this->config->get('config_template') . '/template/notes/livevideourl.php';
		$this->response->setOutput($this->render());
		
	}
	
}

?>