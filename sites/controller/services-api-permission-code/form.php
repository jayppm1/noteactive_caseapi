<?php 
 header('Access-Control-Allow-Origin:'.$_SERVER['HTTP_ORIGIN']);
 header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
 header('Access-Control-Max-Age: 1000');
 header('Access-Control-Allow-Headers: Content-Type');
 header('Content-type: application/json');
 header('Content-Type: text/html; charset=utf-8');
 header("Content-type: bitmap; charset=utf-8");
 
class Controllerservicesform extends Controller { 
	private $error = array();
	public function index() {
		
		$this->load->model('api/encrypt');
		$cre_array = array();
		$cre_array['phone_device_id'] = $this->request->post['phone_device_id'];
		$cre_array['facilities_id'] = $this->request->post['facilities_id'];
		
		$api_device_info = $this->model_api_encrypt->getdevicedetails($cre_array);
		
		if($api_device_info == false){
			$errorMessage = $this->model_api_encrypt->errorMessage();
			return $errorMessage;
		}
		
		$api_header_value = $this->model_api_encrypt->getallheaders1();
		
		if($api_header_value == false){
			$errorMessage = $this->model_api_encrypt->errorMessage();
			return $errorMessage;
		}
		
		$this->load->language('form/form');
		$this->load->model('form/form');
		
		$this->data['serviceforms_id'] = '1';
		
		$this->data['forms_design_id'] = $this->request->get['forms_design_id'];
		$this->data['forms_id'] = $this->request->get['forms_id'];
		
		$this->getForm();
	}
	
	
	public function insert(){
		
		$this->load->model('api/encrypt');
		$cre_array = array();
		$cre_array['phone_device_id'] = $this->request->post['phone_device_id'];
		$cre_array['facilities_id'] = $this->request->post['facilities_id'];
		
		$api_device_info = $this->model_api_encrypt->getdevicedetails($cre_array);
		
		if($api_device_info == false){
			$errorMessage = $this->model_api_encrypt->errorMessage();
			return $errorMessage;
		}
		
		$api_header_value = $this->model_api_encrypt->getallheaders1();
		
		if($api_header_value == false){
			$errorMessage = $this->model_api_encrypt->errorMessage();
			return $errorMessage;
		}
		
		$this->load->language('form/form'); 
		$this->load->model('form/form');
		$this->data['forms_design_id'] = $this->request->get['forms_design_id'];
		$this->data['forms_id'] = $this->request->get['forms_id'];
		
		if ($this->request->post['form_submit'] == '1' && $this->validateForm() ) {
			$data2 = array();
			$data2['forms_design_id'] = $this->request->get['forms_design_id'];
			//$data2['notes_id'] = $this->request->get['notes_id'];
			$data2['facilities_id'] = $this->request->get['facilities_id'];
			
			
 			$formreturn_id = $this->model_form_form->addFormdata($this->request->post, $data2);	
			
			$url2 = "";
			
			if ($formreturn_id != null && $formreturn_id != "") {
				$url2 .= '&formreturn_id=' . $formreturn_id;
			}
			
			if ($this->request->post['emp_tag_id'] != null && $this->request->post['emp_tag_id'] != "") {
				$url2 .= '&emp_tag_id=' . $this->request->post['emp_tag_id'];
			}
					
			if ($this->request->get['forms_id'] != null && $this->request->get['forms_id'] != "") {
				$url2 .= '&forms_id=' . $this->request->get['forms_id'];
				
				$forms_id = $this->request->get['forms_id'];
			}else{
				$forms_id = '';
			}
			
			if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
				$url2 .= '&notes_id=' . $this->request->get['notes_id'];
				$url2 .= '&new_form=1';
				$new_form = '1';
				$notes_id = $this->request->get['notes_id'];
			}else{
				$new_form = '2';
				$notes_id = '';
				$url2 .= '&new_form=2';
			}
				
			if ($this->request->get['forms_design_id'] != null && $this->request->get['forms_design_id'] != "") {
				$url2 .= '&forms_design_id=' . $this->request->get['forms_design_id'];
				$forms_design_id = $this->request->get['forms_design_id'];
			}else{
				$forms_design_id = '';
			}
			
			if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
				$url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
				$facilities_id = $this->request->get['facilities_id'];
			}else{
				$facilities_id = '';
			}
			
			
			if ($this->request->get['task_id'] != null && $this->request->get['task_id'] != "") {
				$url2 .= '&task_id=' . $this->request->get['task_id'];
				$task_id = $this->request->get['task_id'];
			}else{
				$task_id = '';
			}
			
			if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
				$url2 .= '&tags_id=' . $this->request->get['tags_id'];
				$tags_id = $this->request->get['tags_id'];
			}else{
				$tags_id = '';
			}
			
			if ($this->request->get['updatenotes_id'] != null && $this->request->get['updatenotes_id'] != "") {
				$url2 .= '&updatenotes_id=' . $this->request->get['updatenotes_id'];
				$updatenotes_id = $this->request->get['updatenotes_id'];
			}else{
				$updatenotes_id = '';
			}
			
			if ($this->request->get['is_html'] != null && $this->request->get['is_html'] != "") {
				$url2 .= '&is_html=' . $this->request->get['is_html'];
				$is_html = $this->request->get['is_html'];
			}else{
				$is_html = '';
			}
			
			
			
			
			$this->redirect($this->url->link('services/form/jsoncustomsForm', '' . $url2, 'SSL'));
			
		}
		
		/*
		$this->load->language('form/form');
		$this->load->model('form/form');
		
		$fromdatas = $this->model_form_form->getFormdata($this->request->get['forms_design_id']);

		$this->data['fields'] = unserialize($fromdatas['forms_fields']);
		
		$this->data['layouts'] = explode(",",$fromdatas['form_layout']);
		
		
		$this->data['form_name'] = $fromdatas['form_name'];
		$this->data['display_image'] = $fromdatas['display_image'];
		$this->data['display_signature'] = $fromdatas['display_signature'];
		$this->data['forms_setting'] = $fromdatas['forms_setting'];
		
		$url2 = "";
				if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
					$url2 .= '&searchdate=' . $this->request->get['searchdate'];
				}
				if ($this->request->get['updatenotes_id'] != null && $this->request->get['updatenotes_id'] != "") {
					$url2 .= '&updatenotes_id=' . $this->request->get['updatenotes_id'];
				}
				if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
					$url2 .= '&notes_id=' . $this->request->get['notes_id'];
				}
				
				if ($this->request->get['forms_design_id'] != null && $this->request->get['forms_design_id'] != "") {
					$url2 .= '&forms_design_id=' . $this->request->get['forms_design_id'];
				}
				
				
				if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
					$url2 .= '&tags_id=' . $this->request->get['tags_id'];
				}
				
				
				$this->data['action'] = $this->url->link('services/form/insert', $url2, true);
				
		
		if (isset($this->request->post['design_forms'])) {
			$this->data['formdatas'] = $this->request->post['design_forms'];
		} else {
			$this->data['formdatas'] = array();
		}
		
		if (isset($this->request->post['upload_file'])) {
			$this->data['upload_file'] = $this->request->post['upload_file'];
		} else {
			$this->data['upload_file'] ='';
		}
		if (isset($this->request->post['form_signature'])) {
			$this->data['form_signature'] = $this->request->post['form_signature'];
		} else {
			$this->data['form_signature'] = '';
		}
		
		if($this->request->get['tags_id']){
			$tags_id = $this->request->get['tags_id'];
		}elseif($this->request->post['emp_tag_id']){
			$tags_id = $this->request->post['emp_tag_id'];
		}
		
		$this->load->model('setting/tags');
		$tag_info = $this->model_setting_tags->getTag($tags_id);
		
		if (isset($this->request->post['emp_tag_id'])) {
			$this->data['emp_tag_id'] = $this->request->post['emp_tag_id'];
		}elseif (!empty($tag_info)) {
			$this->data['emp_tag_id'] = $tag_info['tags_id'];
		} else {
			$this->data['emp_tag_id'] = '';
		}
		
		if (isset($this->request->post['emp_tag_id1'])) {
			$this->data['emp_tag_id1'] = $this->request->post['emp_tag_id1'];
		}elseif (!empty($tag_info)) {
			$this->data['emp_tag_id1'] = $tag_info['emp_tag_id'].' : '.$tag_info['emp_first_name'] .' '.$tag_info['emp_last_name'];
		} else {
			$this->data['emp_tag_id1'] = '';
		}
		
		
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success2'])) {
			$this->data['success2'] = $this->session->data['success2'];

			unset($this->session->data['success2']);
		} else {
			$this->data['success2'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
		
		
		
		$this->template = $this->config->get('config_template') . '/template/form/form.tpl';
		$this->response->setOutput($this->render());
		*/
		
		$this->getForm();
	}
	
	protected function getForm() {
		$this->load->language('form/form');
		$this->load->model('form/form');
		
		$this->language->load('notes/notes');
		
		$this->data['forms_design_id'] = $this->request->get['forms_design_id'];
		$this->data['forms_id'] = $this->request->get['forms_id'];
		
		if ($this->request->get['task_id'] != null && $this->request->get['task_id'] != "") {
			$this->data['task_id_url'] = '&task_id=' . $this->request->get['task_id'];
		}
		
		if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
			$this->data['facilities_id_url'] = '&facilities_id=' . $this->request->get['facilities_id'];
		}
		
		$this->data['serviceforms_id'] = '1';
		
		$fromdatas = $this->model_form_form->getFormdata($this->request->get['forms_design_id']);

		//$this->data['fields'] = unserialize($fromdatas['forms_fields']);
		$this->data['fields'] = $fromdatas['forms_fields'];
		
		//if($fromdatas['display_observation'] == '1'){
			//$this->load->model('notes/notes');
			//$this->data['observationdatas'] = $this->model_notes_notes->getcustomlists();
		//}
		
		
		//var_dump($this->data['fields']);
		
		$this->data['layouts'] = explode(",",$fromdatas['form_layout']);
		
		
		$this->data['form_name'] = $fromdatas['form_name'];
		$this->data['display_image'] = $fromdatas['display_image'];
		$this->data['display_signature'] = $fromdatas['display_signature'];
		$this->data['forms_setting'] = $fromdatas['forms_setting'];
		$this->data['form_name'] = $fromdatas['form_name'];
		$this->data['display_add_row'] = $fromdatas['display_add_row'];
		$this->data['display_content_postion'] = $fromdatas['display_content_postion'];
		
		
		$this->load->model('facilities/facilities');
		$this->load->model('setting/timezone');
				
		$facilities_info = $this->model_facilities_facilities->getfacilities($this->request->get['facilities_id']);
				
		$this->data['facility_name'] = $facilities_info['facility'];
			
		$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
		
		date_default_timezone_set($timezone_info['timezone_value']);
		
		if($this->request->get['forms_id'] != "" && $this->request->get['forms_id'] != NULL){
			
			if ($this->request->get['is_archive'] == "4") {
				$results = $this->model_form_form->getFormDatas3($this->request->get['forms_id'],$this->request->get['notes_id']);	
				
				if($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != ""){
					$this->load->model('notes/notes');
					$notes_info = $this->model_notes_notes->getNote($this->request->get['notes_id']);
				
					$this->data['note_date_added'] = date('m-d-Y h:i A', strtotime($notes_info['date_added']));
					$this->data['is_archive'] = $this->request->get['is_archive'];
				}
				
			}else{
				$results = $this->model_form_form->getFormDatas($this->request->get['forms_id']);	
			}	
			
			$this->data['custom_form_type'] = $results['custom_form_type'];
			$this->data['is_discharge'] = $results['is_discharge'];
		
		
			//var_dump($this->data['is_discharge']);
			
			
			if($results['parent_id'] > 0 ){
				$this->load->model('notes/notes');		
				$this->load->model('notes/tags');
				$this->load->model('user/user');
				
				$notesresults = $this->model_notes_notes->getnotesbyparent($results['parent_id']);	
		
				foreach($notesresults as $result){
					
					if($result['notes_pin'] != null && $result['notes_pin'] != ""){
						$userPin = $result['notes_pin'];
					}else{
						$userPin = '';
					}
					
					
					
					if ($config_tag_status == '1') {
						
						$alltag = $this->model_notes_notes->getNotesTags($result['notes_id']);
						
						
						if($alltag['emp_tag_id'] != null && $alltag['emp_tag_id'] != ""){
							$tagdata = $this->model_notes_tags->getTagbyEMPID($alltag['emp_tag_id']);
							$privacy = $tagdata['privacy'];
							
							$emp_tag_id = '';//$alltag['emp_tag_id'].': ';
							
						}else{
							$emp_tag_id = '';
							$privacy = '';
							
						}
					}
					
					
					
					$allkeywords = $this->model_notes_notes->getnoteskeywors($result['notes_id']);
					$noteskeywords = array();
						
					if($allkeywords){
						$keyImageSrc12 = array();
						$keyname = array();
						$keyImageSrc11 = "";
						foreach ($allkeywords as $keyword) {

							$keyImageSrc11 .= '<img src="'.$keyword['keyword_file_url'].'" wisth="35px" height="35px">';
							
							$noteskeywords[]= array(
								'keyword_file_url' =>$keyword['keyword_file_url'],
							);
						}
						
						$keyword_description = $keyImageSrc11.'&nbsp;'.$result['notes_description'];
						$notes_description = $emp_tag_id . $keyword_description;
				
						
					}
					
				
				
					$this->data['notess'][] = array(
						'notes_id'    => $result['notes_id'],
						'task_type'    => $result['task_type'],
						'taskadded'    => $result['taskadded'],
						'assign_to'    => $result['assign_to'],
						'highlighter_value'   => $highlighterData['highlighter_value'],
						'notes_description'   => $notes_description,
						'notetime'   => date('h:i A', strtotime($result['notetime'])),
						'username'      => $result['user_id'],
						'notes_pin'      => $userPin,
						'signature'   => $result['signature'],
						'note_date'   => date($this->language->get('date_format_short_2'), strtotime($result['note_date'])),
						
					);
				}
			}
					
			
			
			
		}
		
		
		//var_dump($this->data['formsimages']);
		//var_dump($this->data['formssigns']);
		
		
		if($this->request->get['forms_id'] == "" && $this->request->get['forms_id'] == NULL){
			
				$url2 = "";
				if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
					$url2 .= '&searchdate=' . $this->request->get['searchdate'];
				}
				if ($this->request->get['updatenotes_id'] != null && $this->request->get['updatenotes_id'] != "") {
					$url2 .= '&updatenotes_id=' . $this->request->get['updatenotes_id'];
				}
				if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
					$url2 .= '&notes_id=' . $this->request->get['notes_id'];
				}
				
				if ($this->request->get['forms_design_id'] != null && $this->request->get['forms_design_id'] != "") {
					$url2 .= '&forms_design_id=' . $this->request->get['forms_design_id'];
				}
				
				if ($this->request->get['task_id'] != null && $this->request->get['task_id'] != "") {
					$url2 .= '&task_id=' . $this->request->get['task_id'];
				}
				
				if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
					$url2 .= '&tags_id=' . $this->request->get['tags_id'];
				}
				
				if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
						$url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
					}
					
				if ($this->request->get['is_archive'] != null && $this->request->get['is_archive'] != "") {
					$url2 .= '&is_archive=' . $this->request->get['is_archive'];
				}
				
				if ($this->request->get['is_html'] != null && $this->request->get['is_html'] != "") {
					$url2 .= '&is_html=' . $this->request->get['is_html'];
				}
				
				$this->data['action'] = $this->url->link('services/form/insert', $url2, true);
				
		}else{
				$url2 = "";
				$url3 = "";
				if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
					$url2 .= '&searchdate=' . $this->request->get['searchdate'];
					$url3 .= '&searchdate=' . $this->request->get['searchdate'];
				}
				if ($this->request->get['forms_id'] != null && $this->request->get['forms_id'] != "") {
					$url2 .= '&forms_id=' . $this->request->get['forms_id'];
					$url3 .= '&forms_id=' . $this->request->get['forms_id'];
					$this->data['forms_id'] = $this->request->get['forms_id'];
				}
				
				if ($this->request->get['updatenotes_id'] != null && $this->request->get['updatenotes_id'] != "") {
					$url2 .= '&updatenotes_id=' . $this->request->get['updatenotes_id'];
					$url3 .= '&updatenotes_id=' . $this->request->get['updatenotes_id'];
				}
				
				if ($this->request->get['forms_design_id'] != null && $this->request->get['forms_design_id'] != "") {
					$url2 .= '&forms_design_id=' . $this->request->get['forms_design_id'];
					$url3 .= '&forms_design_id=' . $this->request->get['forms_design_id'];
				}
				
				if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
					$url2 .= '&notes_id=' . $this->request->get['notes_id'];
				}
				if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
					$url2 .= '&tags_id=' . $this->request->get['tags_id'];
					$url3 .= '&tags_id=' . $this->request->get['tags_id'];
				}
				
				if ($this->request->get['task_id'] != null && $this->request->get['task_id'] != "") {
					$url2 .= '&task_id=' . $this->request->get['task_id'];
					$url3 .= '&task_id=' . $this->request->get['task_id'];
				}
				
				if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
					$url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
					$url3 .= '&facilities_id=' . $this->request->get['facilities_id'];
				}
				
				if ($this->request->get['is_archive'] != null && $this->request->get['is_archive'] != "") {
					$url2 .= '&is_archive=' . $this->request->get['is_archive'];
				}
				
				if ($this->request->get['is_html'] != null && $this->request->get['is_html'] != "") {
					$url2 .= '&is_html=' . $this->request->get['is_html'];
				}
				
				if ($this->request->get['is_archive'] == "4") {
					$form_info = $this->model_form_form->getFormDatas($this->request->get['forms_id']);	
					$url3 .= '&notes_id=' . $form_info['notes_id'];
				}
		
			$this->data['action'] = $this->url->link('services/form/edit', $url2, true);
			
			$this->data['currentt_url'] = str_replace('&amp;', '&',$this->url->link('services/form/edit', '' . $url3, 'SSL'));
			
			/*if($this->request->get['forms_design_id'] == '13' ){
				$this->data['print_url'] = $this->url->link('form/form/printform', $url2, true);
			}
			
			if($this->request->get['forms_design_id'] == '9' ){
				$this->data['print_url'] = $this->url->link('form/form/printmonthly_firredrill', $url2, true);
			}
				
			if($this->request->get['forms_design_id'] == '10' ){
				$this->data['print_url'] = $this->url->link('form/form/printincidentform', $url2, true);
			}
			
			
			if($this->request->get['forms_design_id'] == '2' ){
				$this->data['print_url'] = $this->url->link('form/form/printintakeform', $url2, true);
			}*/
		}
		
		
		if (isset($this->session->data['success_add_form'])) {
			$this->data['success_add_form'] = $this->session->data['success_add_form'];

			unset($this->session->data['success_add_form']);
		} else {
			$this->data['success_add_form'] = '';
		}
		
		$this->data['formdatas'] = array();
		
		if (isset($this->request->post['design_forms'])) {
			$this->data['formdatas'] = $this->request->post['design_forms'];
		} elseif (!empty($results)) {
			$this->data['formdatas'] =  unserialize($results['design_forms']);
		}
		
		
		if ($this->request->get['is_archive'] == "4") {
			$formmedias = $this->model_form_form->getFormmedia3($this->request->get['forms_id'],$this->request->get['notes_id']);
			
		}else{
			$formmedias = $this->model_form_form->getFormmedia($this->request->get['forms_id']);
		}
		
		if($formmedias != null && $formmedias != ""){	
			$this->data['formsimages'] = array();
			$this->data['formssigns'] = array();
			
			foreach($formmedias as $formmedia){
				
				
				if($formmedia['media_type'] == '1'){
					$this->data['formdatas'][$formmedia['media_name']][] = $formmedia['media_url'];
				}
				
				if($formmedia['media_type'] == '2'){
					$this->data['formdatas'][$formmedia['media_name']][] = $formmedia['media_url'];
				}
			}
		}
		
		if (isset($this->request->post['upload_file'])) {
			$this->data['upload_file'] = $this->request->post['upload_file'];
		}elseif (!empty($results)) {
			$this->data['upload_file'] =  $results['upload_file'];
		}  else {
			$this->data['upload_file'] = '';
		}
		
		if (isset($this->request->post['form_signature'])) {
			$this->data['form_signature'] = $this->request->post['form_signature'];
		}elseif (!empty($results)) {
			$this->data['form_signature'] =  $results['form_signature'];
		} else {
			$this->data['form_signature'] = '';
		}
		
		if (isset($this->request->post['is_final'])) {
			$this->data['is_final'] = $this->request->post['is_final'];
		}elseif (!empty($results)) {
			$this->data['is_final'] =  $results['is_final'];
		} else {
			$this->data['is_final'] = '';
		}
		
		if (isset($this->request->post['is_approval_required'])) {
			$this->data['is_approval_required'] = $this->request->post['is_approval_required'];
		}elseif (!empty($results)) {
			$this->data['is_approval_required'] =  $results['is_approval_required'];
		} else {
			$this->data['is_approval_required'] = '';
		}
		
		if($this->request->get['tags_id']){
			$tags_id = $this->request->get['tags_id'];
		}elseif($this->request->post['emp_tag_id']){
			$tags_id = $this->request->post['emp_tag_id'];
		}elseif (!empty($results)) {
			$tags_id = $results['tags_id'];
		} 
		
		$this->load->model('setting/tags');
		$tag_info = $this->model_setting_tags->getTag($tags_id);
		
		if (isset($this->request->post['emp_tag_id'])) {
			$this->data['emp_tag_id'] = $this->request->post['emp_tag_id'];
		}elseif (!empty($tag_info)) {
			$this->data['emp_tag_id'] = $tag_info['tags_id'];
		} else {
			$this->data['emp_tag_id'] = '';
		}
		
		if (isset($this->request->post['emp_tag_id1'])) {
			$this->data['emp_tag_id1'] = $this->request->post['emp_tag_id1'];
		}elseif (!empty($tag_info)) {
			$this->data['emp_tag_id1'] = $tag_info['emp_tag_id'].' : '.$tag_info['emp_first_name'] .' '.$tag_info['emp_last_name'];
		} else {
			$this->data['emp_tag_id1'] = '';
		}
		
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success2'])) {
			$this->data['success2'] = $this->session->data['success2'];

			unset($this->session->data['success2']);
		} else {
			$this->data['success2'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
		
		if (isset($this->request->post['allclients'])) {
			$this->data['allclients'] = $this->request->post['allclients'];
		}  else {
			$this->data['allclients'] = '0';
		}
		
		
		if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
			$this->data['facilities_id'] = $this->request->get['facilities_id'];
		}
		
		$this->load->model('facilities/facilities');
		$this->data['sfacilities'] = $this->model_facilities_facilities->getfacilitiess();
		
		$this->template = $this->config->get('config_template') . '/template/form/form.tpl';
		
		$this->children = array(
			'common/headerform',
		);
		
		$this->response->setOutput($this->render());
		
	}
	
	
	protected function validateForm() {
		
		/*
		if($this->request->get['updatenotes_id'] != null && $this->request->get['updatenotes_id'] != ""){
			if($this->request->get['forms_design_id'] == CUSTOME_INTAKEID){
				
				$this->load->model('form/form');
				$form_info = $this->model_form_form->getFormwithNotes($this->request->get['updatenotes_id'], CUSTOME_INTAKEID);	
			
				if ($form_info != null && $form_info != "") {
					$this->error['warning'] = 'Intake form already added in this note';
				}
				
				
			}
		}
		*/
		
		
		
		if($this->request->get['forms_design_id'] == CUSTOME_HOMEVISIT){
			if($this->request->post['emp_tag_id1'] == "" && $this->request->post['emp_tag_id1'] == ""){
				$this->error['warning'] = 'Client is required!';
			}
			
			if ($this->request->post['emp_tag_id'] == null && $this->request->post['emp_tag_id'] == "") {
				$this->error['warning'] = 'Invalid Client!';
			}
		}
		
		//var_dump($this->session->data['formreturn_id']);
		if($this->session->data['formreturn_id'] != null && $this->session->data['formreturn_id'] != ""){
			
		}
		
		if ($this->request->post['design_forms']['ssn'] != '') {
			
			$this->load->model('setting/tags');
			$tag_info = $this->model_setting_tags->getTagsbySSN($this->request->post['design_forms']['ssn']);
						
			if (!isset($this->request->post['emp_tag_id'])) {
				
				if($tag_info){
					$this->error['warning'] = 'This Record already exists in the System. Would you like to use this information for the Intake? Yes/No';
				}
			} else {
				
				if ($tag_info && ($this->request->post['emp_tag_id'] != $tag_info['tags_id'])) {
					$this->error['warning'] = 'This Record already exists in the System. Would you like to use this information for the Intake?  Yes/No';
				}
			}
		}
		
		
		
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	
	public function insert2() {
		
		$this->data['facilitiess'] = array();
		
		$json = array();
		
		$this->load->model('api/encrypt');
		$cre_array = array();
		$cre_array['phone_device_id'] = $this->request->post['phone_device_id'];
		$cre_array['facilities_id'] = $this->request->post['facilities_id'];
		
		$api_device_info = $this->model_api_encrypt->getdevicedetails($cre_array);
		
		if($api_device_info == false){
			$errorMessage = $this->model_api_encrypt->errorMessage();
			return $errorMessage;
		}
		
		$api_header_value = $this->model_api_encrypt->getallheaders1();
		
		if($api_header_value == false){
			$errorMessage = $this->model_api_encrypt->errorMessage();
			return $errorMessage;
		}
		
		$this->load->model('notes/notes');
		$this->language->load('notes/notes');
		
		if($this->request->post['notes_pin'] != null && $this->request->post['notes_pin'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if (($this->request->post['notes_pin'] != $user_info['user_pin'])) {
				$json['warning'] = 'User Pin not valid!';
			}
		}
		
		if($this->request->post['user_id'] != null && $this->request->post['user_id'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if (($user_info['status'] == '0')) {
				$json['warning'] = 'User not exit!';
			}
		}
		
		
		if ($this->request->post['perpetual_checkbox'] == '1') {
			if ($this->request->post['perpetual_checkbox_notes_pin'] == '') {
				$json['perpetual_checkbox_notes_pin'] = 'This is required field!';
			}
			if($this->request->post['perpetual_checkbox_notes_pin'] != null && $this->request->post['perpetual_checkbox_notes_pin'] != ""){
				$this->load->model('user/user');
				$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

				if (($this->request->post['perpetual_checkbox_notes_pin'] != $user_info['user_pin'])) {
					$json['warning'] = 'User Pin not valid!';
				}
				
				
				$this->load->model('user/user_group');
				$user_role_info = $this->model_user_user_group->getUserGroup($user_info['user_group_id']);
					
				$perpetual_task = $user_role_info['perpetual_task'];
				
				if($perpetual_task != '1'){
					$json['warning'] =  "You are not authorized to end the task!";
				}
				
				
			}
		}
		
		
		
		if($json['warning'] == null && $json['warning'] == ""){
			
				$this->load->model('facilities/facilities');
				
				$this->load->model('form/form');
				
				$facilities_info = $this->model_facilities_facilities->getfacilities($this->request->get['facilities_id']);
				
				
				$this->load->model('setting/timezone');
				
				$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
				
				date_default_timezone_set($timezone_info['timezone_value']);
				
				$noteDate = date('Y-m-d H:i:s', strtotime('now'));
				$date_added = (string) $noteDate;
			
				$form_date_added = (string) $noteDate;
				
					$formreturn_id = (string) $this->request->get['formreturn_id'];
				
				
					if($this->request->get['task_id'] !=null && $this->request->get['task_id']!=""){
						
						$this->load->model('createtask/createtask');
						
						
						if($this->request->get['forms_design_id'] == CUSTOME_INTAKEID){
							$form_info = $this->model_form_form->getFormDatas($this->request->get['formreturn_id']);
							$notes_description = ' | '.$form_info['incident_number']. ' has been added ';
						}else{
							$notes_description = '';
						}
						
						if($this->request->post['comments'] != null && $this->request->post['comments']){
							$this->request->post['comments'] = $notes_description .' '.$this->request->post['comments'];
						}else{
							$this->request->post['comments'] =  $notes_description;
						}
						
						
						
						$this->request->post['imgOutput'] =  $this->request->post['signature'];
						
						
						
						$result2 = $this->model_createtask_createtask->getStrikedatadetails($this->request->get['task_id']);
						
						$notesId = $this->model_createtask_createtask->inserttask($result2, $this->request->post, $this->request->get['facilities_id']);
						
						$this->model_createtask_createtask->updatetaskNote($this->request->get['task_id']);
						$this->model_createtask_createtask->deteteIncomTask($this->request->get['facilities_id']);
						//var_dump($notesId);
						
						$ttstatus = "1";
						//$timezone_name = $this->request->get['facilities_id'];
						
						$this->load->model('facilities/facilities');
				
						$facilities_info = $this->model_facilities_facilities->getfacilities($this->request->get['facilities_id']);
						
						
						$this->load->model('setting/timezone');
						
						$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
						
						date_default_timezone_set($timezone_info['timezone_value']);
						
						$update_date = date('Y-m-d H:i:s', strtotime('now'));
						$this->model_createtask_createtask->updateForm($notesId, $checklist_status, $ttstatus,$update_date);
						
						$notes_id = $notesId;
						
						$fdata3 = array();
						$fdata3['notes_id'] = $notes_id;
						$fdata3['form_date_added'] = $date_added;
						$fdata3['date_added'] = $date_added;
						$fdata3['date_updated'] = $date_added;
						$fdata3['forms_id'] = $formreturn_id;
									
						$this->model_form_form->updatetaskformnotes($fdata3);
						
						
						if($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != ""){
							
							$this->model_notes_notes->updatenotesparentnotification($this->request->get['notes_id'], $notes_id);
						
						}
						
						$form_info = $this->model_form_form->getFormDatas($formreturn_id);
						if($form_info['is_approval_required'] == '1'){
							if($form_info['is_final'] == '0'){
								$ftdata = array();
								$ftdata['forms_id'] = $formreturn_id;
								$ftdata['incident_number'] = $form_info['incident_number'];
								$ftdata['facilitytimezone'] = $timezone_info['timezone_value'];
								$ftdata['facilities_id'] = $this->request->get['facilities_id'];
								
								$this->load->model('createtask/createtask');
								$this->model_createtask_createtask->createapprovalTak($ftdata);
							}
						}
					
						
					}else					
					if($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != ""){
						
						
						$this->load->model('setting/tags');
						
						$noteDate = date('Y-m-d H:i:s', strtotime('now'));
						$date_added = (string) $noteDate;
						
						
						$data = array();
						
						$notetime = date('H:i:s', strtotime('now'));
						$data['imgOutput'] = $this->request->post['signature'];
						
						$data['notes_pin'] = $this->request->post['notes_pin'];
						$data['user_id'] = $this->request->post['user_id'];
						$data['notes_type'] = $this->request->post['notes_type'];
						
						$data['notetime'] = $notetime;
						$data['note_date'] = $date_added;
						$data['facilitytimezone'] = $timezone_name;
						
						
						$form_data = $this->model_form_form->getFormdata($this->request->get['forms_design_id']);
						$form_name = $form_data['form_name'];
						
						
						$tag_info = $this->model_setting_tags->getTag($this->request->get['tags_id']);
						
						$data['emp_tag_id'] = $tag_info['emp_tag_id'];
						$data['tags_id'] = $tag_info['tags_id'];
						
						
						if($this->request->post['comments'] != null && $this->request->post['comments']){
							$comments = ' | '.$this->request->post['comments'];
						}
						
						$data['notes_description'] = $tag_info['emp_tag_id'].':'.$tag_info['emp_first_name'] .' | '.$form_name.' has been added' . $comments;
						
						$data['date_added'] = $date_added;
						
						$data['phone_device_id'] = $this->request->post['phone_device_id'];
								
						if($this->request->post['is_android'] != null && $this->request->post['is_android'] != ""){
							$data['is_android'] = $this->request->post['is_android'];
						}else{
							$data['is_android'] = '1';
						}
						
						$notes_id = $this->model_notes_notes->jsonaddnotes($data, $this->request->get['facilities_id']);
						
						
			
						$form_info = $this->model_form_form->getFormDatas($formreturn_id);
						$formdesign_info = $this->model_form_form->getFormDatadesign($form_info['custom_form_type']);
						$relation_keyword_id = $formdesign_info['relation_keyword_id'];
						
						
						if($form_info['is_approval_required'] == '1'){
							if($form_info['is_final'] == '0'){
								$ftdata = array();
								$ftdata['forms_id'] = $formreturn_id;
								$ftdata['incident_number'] = $form_info['incident_number'];
								$ftdata['facilitytimezone'] = $timezone_info['timezone_value'];
								$ftdata['facilities_id'] = $this->request->get['facilities_id'];
								
								$this->load->model('createtask/createtask');
								$this->model_createtask_createtask->createapprovalTak($ftdata);
							}
						}
								
									
						/*if($relation_keyword_id){
							$this->load->model('notes/notes');
							$noteDetails = $this->model_notes_notes->getnotes($notes_id);
										
							$this->load->model('setting/keywords');
							$keyword_info = $this->model_setting_keywords->getkeywordDetail($relation_keyword_id);
										
							$data3 = array();
							$data3['keyword_file'] = $keyword_info['keyword_image'];
							$data3['notes_description'] = $noteDetails['notes_description'];
										
							$this->model_notes_notes->addactiveNote($data3, $notes_id);
						}*/
					
					}elseif($this->request->get['updatenotes_id'] != null && $this->request->get['updatenotes_id'] != ""){
						$notes_id = $this->request->get['updatenotes_id'];
					}else{
						$notes_id = $this->request->get['notes_id'];
					}
					
					
					$this->load->model('notes/notes');
					$noteDetails = $this->model_notes_notes->getnotes($notes_id);
					$date_added1 = $noteDetails['date_added']; 
					
					if( $this->request->get['new_form'] == '1'){
						
						if($notes_id == null && $notes_id == ""){
							
							$noteDate = date('Y-m-d H:i:s', strtotime('now'));
							$date_added = (string) $noteDate;
							
							
							$notetime = date('H:i:s', strtotime('now'));
							$data['imgOutput'] = $this->request->post['signature'];
							
							$data['notes_pin'] = $this->request->post['notes_pin'];
							$data['user_id'] = $this->request->post['user_id'];
							
							
							if($this->request->post['comments'] != null && $this->request->post['comments']){
								$comments = ' | '.$this->request->post['comments'];
							}
							
							
							$this->load->model('form/form');
							
							$form_info = $this->model_form_form->getFormDatas($formreturn_id);	
							$custom_form_type = $form_info['custom_form_type'];
								
							$form_data = $this->model_form_form->getFormdata($custom_form_type);
							
							$data['notes_description'] = ' | '.$form_data['form_name']. ' has been added '.$comments;
							
							
							
							$data['date_added'] = $date_added;
							$data['note_date'] = $date_added;
							$data['notetime'] = $notetime;
							
							$data['phone_device_id'] = $this->request->post['phone_device_id'];
						
							if($this->request->post['is_android'] != null && $this->request->post['is_android'] != ""){
								$data['is_android'] = $this->request->post['is_android'];
							}else{
								$data['is_android'] = '1';
							}
							
							$notes_id = $this->model_notes_notes->jsonaddnotes($data, $this->request->get['facilities_id']);
							
							
							if($form_info['is_approval_required'] == '1'){
								if($form_info['is_final'] == '0'){
									$ftdata = array();
									$ftdata['forms_id'] = $formreturn_id;
									$ftdata['incident_number'] = $form_info['incident_number'];
									$ftdata['facilitytimezone'] = $timezone_info['timezone_value'];
									$ftdata['facilities_id'] = $this->request->get['facilities_id'];
									
									$this->load->model('createtask/createtask');
									$this->model_createtask_createtask->createapprovalTak($ftdata);
								}
							}
							
						}
						
						
						$fdata3 = array();
						$fdata3['notes_id'] = $notes_id;
						$fdata3['form_date_added'] = $date_added;
						$fdata3['date_added'] = $date_added;
						$fdata3['date_updated'] = $date_added;
						$fdata3['forms_id'] = $formreturn_id;
									
						$this->model_form_form->updatetaskformnotes($fdata3);
						
						
						if($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != ""){
							
							$this->model_form_form->updateformstags($this->request->get['tags_id'], $formreturn_id);
						}
						
					}else{
						
						
						$fdata3 = array();
						
						$fdata3['user_id'] = $this->request->post['user_id'];
						$fdata3['signature'] = $this->request->post['signature'];
						$fdata3['notes_pin'] = $this->request->post['notes_pin'];
						$fdata3['notes_type'] = $this->request->post['notes_type'];
						
						$fdata3['form_date_added'] = $date_added;
						$fdata3['date_added'] = $date_added;
						$fdata3['date_updated'] = $date_added;
						$fdata3['forms_id'] = $formreturn_id;
									
						$this->model_form_form->updatetaskformnotes($fdata3);
						
						
						
						if($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != ""){
							
							$this->model_form_form->updateformstags($this->request->get['tags_id'], $formreturn_id);
						}
						
						
						$noteDate = date('Y-m-d H:i:s', strtotime('now'));
						$date_added = (string) $noteDate;
						
						
						$notetime = date('H:i:s', strtotime('now'));
						$data['imgOutput'] = $this->request->post['signature'];
						
						$data['notes_pin'] = $this->request->post['notes_pin'];
						$data['user_id'] = $this->request->post['user_id'];
						
						
						if($this->request->post['comments'] != null && $this->request->post['comments']){
							$comments = ' | '.$this->request->post['comments'];
						}
						
						
						
						
						$form_info = $this->model_form_form->getFormDatas($formreturn_id);	
						$custom_form_type = $form_info['custom_form_type'];
							
						$form_data = $this->model_form_form->getFormdata($custom_form_type);
						
						$data['notes_description'] = $form_data['form_name']. ' updated '.$comments;
						
						
						
						$data['date_added'] = $date_added;
						$data['note_date'] = $date_added;
						$data['notetime'] = $notetime;
						
						$data['phone_device_id'] = $this->request->post['phone_device_id'];
						
						if($this->request->post['is_android'] != null && $this->request->post['is_android'] != ""){
							$data['is_android'] = $this->request->post['is_android'];
						}else{
							$data['is_android'] = '1';
						}
						
						$notes_id = $this->model_notes_notes->jsonaddnotes($data, $this->request->get['facilities_id']);
						
						$this->model_notes_notes->updatenotesparentnotification($this->request->get['notes_id'], $notes_id);
						
						
				
						$form_info = $this->model_form_form->getFormDatas($formreturn_id);	
						
						date_default_timezone_set($timezone_info['timezone_value']);
						$update_date = date('Y-m-d H:i:s', strtotime('now'));
						
						$fdata34 = array();
						$fdata34['notes_id'] = $notes_id;
						$fdata34['archive_notes_id'] = $form_info['notes_id'];
						$fdata34['archive_forms_id'] = $this->request->get['archive_forms_id'];
						$fdata34['forms_id'] = $formreturn_id;
						$fdata34['update_date'] = $update_date;
							
						$this->model_form_form->updateformnotesinfo($fdata34);
						
						//var_dump($form_info);
						
						
						
						$this->load->model('notes/notes');
						date_default_timezone_set($timezone_info['timezone_value']);
						$update_date = date('Y-m-d H:i:s', strtotime('now'));
								
						/*$fdata2 = array();
						$fdata2['forms_id'] = $formreturn_id;
						$fdata2['emp_tag_id'] = $this->request->post['emp_tag_id'];
						$fdata2['tags_id'] = $this->request->post['tags_id'];
						$fdata2['update_date'] = $update_date;
						$fdata2['notes_id'] = $notes_id;
								
						$this->model_form_form->updateform2($form_info, $fdata2);
						*/
						
					} 
					
					if($this->request->get['emp_tag_id'] != null && $this->request->get['emp_tag_id'] != ""){
						
						$this->model_form_form->updateformstags($this->request->get['emp_tag_id'], $formreturn_id);
						
					}
					
					date_default_timezone_set($timezone_info['timezone_value']);
					$update_date2 = date('Y-m-d H:i:s', strtotime('now'));
					
					
					$this->model_notes_notes->updatedatecount($notes_id, $update_date2);
					
					$form_info = $this->model_form_form->getFormDatas($formreturn_id);
					
					if($this->request->post['emp_tag_id'] != null && $this->request->post['emp_tag_id'] != ""){
						$this->load->model('notes/notes');
						
						date_default_timezone_set($timezone_info['timezone_value']);
						$update_date = date('Y-m-d H:i:s', strtotime('now'));
						
						$this->load->model('notes/tags');
						$taginfo = $this->model_notes_tags->getTag($this->request->post['tags_id']);
						
						
						$this->model_notes_notes->updateNotesTag($taginfo['emp_tag_id'], $notes_id, $taginfo['tags_id'], $update_date);
						
						$fdata = array();
						$fdata['forms_id'] = $formreturn_id;
						$fdata['emp_tag_id'] = $taginfo['emp_tag_id'];
						$fdata['tags_id'] = $taginfo['tags_id'];
						$fdata['update_date'] = $update_date;
						
						$this->load->model('form/form');
						$this->model_form_form->updateformTag($fdata);
						
					}else if($form_info['tags_id']){
				
						date_default_timezone_set($timezone_info['timezone_value']);
						$update_date = date('Y-m-d H:i:s', strtotime('now'));
						
						$this->load->model('notes/tags');
						$taginfo = $this->model_notes_tags->getTag($form_info['tags_id']);
						
						$this->model_notes_notes->updateNotesTag($taginfo['emp_tag_id'], $notes_id,$taginfo['tags_id'], $update_date);
					}
					
					if( $this->request->get['new_form'] != '2'){
						$this->load->model('form/form');
				
						$form_info = $this->model_form_form->getFormDatas($formreturn_id);
						$formdesign_info = $this->model_form_form->getFormDatadesign($form_info['custom_form_type']);
						$relation_keyword_id = $formdesign_info['relation_keyword_id'];
								
									
						if($relation_keyword_id){
							$this->load->model('notes/notes');
							$noteDetails = $this->model_notes_notes->getnotes($notes_id);
										
							$this->load->model('setting/keywords');
							$keyword_info = $this->model_setting_keywords->getkeywordDetail($relation_keyword_id);
										
							$data3 = array();
							$data3['keyword_file'] = $keyword_info['keyword_image'];
							$data3['notes_description'] = $noteDetails['notes_description'];
										
							$this->model_notes_notes->addactiveNote($data3, $notes_id);
						}
					}
					
					
					$this->model_notes_notes->updatenoteform($notes_id);
					
					
					if($this->request->get['forms_design_id'] == CUSTOME_INTAKEID){
				
						//$notes_id = $this->request->get['notes_id'];
						/*
						$this->load->model('resident/resident');
						$tags_form_info = $this->model_resident_resident->get_formbynotesid($notes_id);	
						
						$tags_id = $tags_form_info['tags_id'];
						*/
				
						
						$form_info = $this->model_form_form->getFormDatas($formreturn_id);	
						if($form_info['tags_id'] != null && $form_info['tags_id'] != "0"){
							$date_added = date('Y-m-d H:i:s', strtotime('now'));
							
							$fdata1 = array();
							$fdata1['design_forms'] = $form_info['design_forms'];
							$fdata1['form_description'] = $form_info['form_description'];
							$fdata1['rules_form_description'] = $form_info['rules_form_description'];
							$fdata1['date_updated'] = $date_added;
							$fdata1['upload_file'] = $form_info['upload_file'];
							$fdata1['form_signature'] = $form_info['form_signature'];
							$fdata1['tags_id'] = $form_info['tags_id'];
							
							$this->model_form_form->updateforminfo($fdata1);
							
							
							$tags_id = $form_info['tags_id'];
							$formdata =  unserialize($form_info['design_forms']);
							
							$emp_first_name =$formdata[0][0]['text_59815482'];
							$emp_last_name =$formdata[0][0]['text_2637670'];
							
							
							$privacy = '';
							$sort_order = '0';
							$status = '1';
							$doctor_name = '';
							$emergency_contact = $formdata[0][0]['text_84980038'];
							
							$date = str_replace('-', '/', $formdata[0][0]['date_70767270']);
							
							$res = explode("/", $date);
							$createdate1 = $res[2]."-".$res[0]."-".$res[1];
							
							$dob = date('Y-m-d',strtotime($createdate1));	
							
							if($formdata[0][0]['text_50839890']){
								$age = $formdata[0][0]['text_50839890'];
							}else{
								$age = (date('Y') - date('Y',strtotime($dob)));
							}
							$medication = '';
							$locations_id = '';
							$facilities_id = $this->request->get['facilities_id'];
							$upload_file = $form_info['upload_file'];
							$tags_pin = '';
							
							if($formdata[0][0]['select_40322663'] == 'Male'){
								$gender = '1';
							}
							if($formdata[0][0]['select_40322663'] == 'Female'){
								$gender = '2';
							}
							if($formdata[0][0]['select_40322663'] == 'Inmate'){
								$gender = '1';
							}
							if($formdata[0][0]['select_40322663'] == 'Patient'){
								$gender = '1';
							}
							
							
							
					
							if($formdata[0][0]['select_40322663'] == ''){
								$gender = '1';
							}
							
							$emp_extid = $formdata[0][0]['text_92710969'];
							$ssn = $formdata[0][0]['text_59058963'];
							$location_address = $formdata[0][0]['text_67156164'];
							$city = $formdata[0][0]['text_36668004'];
							$state = $formdata[0][0]['text_49932949'];
							$zipcode = $formdata[0][0]['text_64928499'];
							
							$fcdata1 = array();
							$fcdata1['emp_first_name'] = $emp_first_name;
							$fcdata1['emp_last_name'] = $emp_last_name;
							$fcdata1['privacy'] = $privacy;
							$fcdata1['sort_order'] = $sort_order;
							$fcdata1['status'] = $status;
							$fcdata1['doctor_name'] = $doctor_name;
							$fcdata1['emergency_contact'] = $emergency_contact;
							$fcdata1['dob'] = $dob;
							$fcdata1['medication'] = $medication;
							$fcdata1['locations_id'] = $locations_id;
							$fcdata1['facilities_id'] = $facilities_id;
							$fcdata1['upload_file'] = $upload_file;
							$fcdata1['tags_pin'] = $tags_pin;
							$fcdata1['gender'] = $gender;
							$fcdata1['age'] = $age;
							$fcdata1['emp_extid'] = $emp_extid;
							$fcdata1['ssn'] = $ssn;
							$fcdata1['location_address'] = $location_address;
							$fcdata1['city'] = $city;
							$fcdata1['state'] = $state;
							$fcdata1['zipcode'] = $zipcode;
							$fcdata1['tags_id'] = $tags_id;
							
							
							$this->load->model('setting/tags');
							$this->model_setting_tags->updatetagsinfo($fcdata1);
							
							
						}
					}
					
					
					
				
				$this->data['facilitiess'][] = array(
					'warning'  => '1',
					'formreturn_id'  => $formreturn_id,
					'notes_id'  => $notes_id,
					'facilities_id'  => $this->request->get['facilities_id'],
				);
				$error = true;
		}else{
			$this->data['facilitiess'][] = array(
				'warning'  => $json['warning'],
			);
			$error = false;
		}
		
		$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
		
		$this->response->setOutput(json_encode($value));

	}
	
	
	public function edit(){
		
		$this->load->model('api/encrypt');
		$cre_array = array();
		$cre_array['phone_device_id'] = $this->request->post['phone_device_id'];
		$cre_array['facilities_id'] = $this->request->post['facilities_id'];
		
		$api_device_info = $this->model_api_encrypt->getdevicedetails($cre_array);
		
		if($api_device_info == false){
			$errorMessage = $this->model_api_encrypt->errorMessage();
			return $errorMessage;
		}
		
		$api_header_value = $this->model_api_encrypt->getallheaders1();
		
		if($api_header_value == false){
			$errorMessage = $this->model_api_encrypt->errorMessage();
			return $errorMessage;
		}
		
		$this->load->language('form/form');
		$this->load->model('form/form');
		 
		 $this->data['forms_design_id'] = $this->request->get['forms_design_id'];
		 $this->data['forms_id'] = $this->request->get['forms_id'];
		if ($this->request->post['form_submit'] == '1'  && $this->validateForm()) {
			$data2 = array();
			$data2['forms_design_id'] = $this->request->get['forms_design_id'];
			//$data2['notes_id'] = $this->request->get['notes_id'];
			$data2['facilities_id'] = $this->request->get['facilities_id'];
			
			
			$archive_forms_id = $this->model_form_form->editFormdata($this->request->post['design_forms'], $this->request->get['forms_id'], $this->request->post['upload_file'], $this->request->post['file'] , $this->request->post['signature'], $this->request->post['form_signature'], $this->request->post['is_final']);
			 
			$url2 = "";
			
			if ($this->request->post['emp_tag_id'] != null && $this->request->post['emp_tag_id'] != "") {
				$url2 .= '&emp_tag_id=' . $this->request->post['emp_tag_id'];
			}
			if ($archive_forms_id != null && $archive_forms_id != "") {
				$url2 .= '&archive_forms_id=' . $archive_forms_id;
				
			}
			
			if ($this->request->get['forms_id'] != null && $this->request->get['forms_id'] != "") {
				$url2 .= '&forms_id=' . $this->request->get['forms_id'];
				
				$forms_id = $this->request->get['forms_id'];
			}else{
				$forms_id = '';
			}
			
			if ($this->request->get['forms_id'] != null && $this->request->get['forms_id'] != "") {
				$url2 .= '&formreturn_id=' . $this->request->get['forms_id'];
				
				$formreturn_id = $this->request->get['forms_id'];
			}else{
				$formreturn_id = '';
			}
			
			if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
				$url2 .= '&notes_id=' . $this->request->get['notes_id'];
				$new_form = '2';
				$notes_id = $this->request->get['notes_id'];
				$url2 .= '&new_form=2';
			}else{
				$new_form = '2';
				$notes_id = '';
				$url2 .= '&new_form=2';
			}
				
			if ($this->request->get['forms_design_id'] != null && $this->request->get['forms_design_id'] != "") {
				$url2 .= '&forms_design_id=' . $this->request->get['forms_design_id'];
				$forms_design_id = $this->request->get['forms_design_id'];
			}else{
				$forms_design_id = '';
			}
			
			if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
				$url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
				$facilities_id = $this->request->get['facilities_id'];
			}else{
				$facilities_id = '';
			}
			if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
				$url2 .= '&tags_id=' . $this->request->get['tags_id'];
				$tags_id = $this->request->get['tags_id'];
			}else{
				$tags_id = '';
			}
			
			if ($this->request->get['task_id'] != null && $this->request->get['task_id'] != "") {
				$url2 .= '&task_id=' . $this->request->get['task_id'];
				$task_id = $this->request->get['task_id'];
			}else{
				$task_id = '';
			}
			
			if ($this->request->get['is_html'] != null && $this->request->get['is_html'] != "") {
				$url2 .= '&is_html=' . $this->request->get['is_html'];
				$is_html = $this->request->get['is_html'];
			}else{
				$is_html = '';
			}
			
			$this->redirect($this->url->link('services/form/jsoncustomsForm', '' . $url2, 'SSL'));
		}
		
		/*
		$this->load->language('form/form');
		$this->load->model('form/form');
		
		$fromdatas = $this->model_form_form->getFormdata($this->request->get['forms_design_id']);

		$this->data['fields'] = unserialize($fromdatas['forms_fields']);
		
		$this->data['layouts'] = explode(",",$fromdatas['form_layout']);
		
		
		$this->data['form_name'] = $fromdatas['form_name'];
		$this->data['display_image'] = $fromdatas['display_image'];
		$this->data['display_signature'] = $fromdatas['display_signature'];
		$this->data['forms_setting'] = $fromdatas['forms_setting'];
		
		$url2 = "";
				if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
					$url2 .= '&searchdate=' . $this->request->get['searchdate'];
				}
				if ($this->request->get['updatenotes_id'] != null && $this->request->get['updatenotes_id'] != "") {
					$url2 .= '&updatenotes_id=' . $this->request->get['updatenotes_id'];
				}
				if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
					$url2 .= '&notes_id=' . $this->request->get['notes_id'];
				}
				
				if ($this->request->get['forms_design_id'] != null && $this->request->get['forms_design_id'] != "") {
					$url2 .= '&forms_design_id=' . $this->request->get['forms_design_id'];
				}
				
				
				if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
					$url2 .= '&tags_id=' . $this->request->get['tags_id'];
				}
				
				
				$this->data['action'] = $this->url->link('services/form/edit', $url2, true);
				
		
		if (isset($this->request->post['design_forms'])) {
			$this->data['formdatas'] = $this->request->post['design_forms'];
		} else {
			$this->data['formdatas'] = array();
		}
		
		if (isset($this->request->post['upload_file'])) {
			$this->data['upload_file'] = $this->request->post['upload_file'];
		} else {
			$this->data['upload_file'] ='';
		}
		if (isset($this->request->post['form_signature'])) {
			$this->data['form_signature'] = $this->request->post['form_signature'];
		} else {
			$this->data['form_signature'] = '';
		}
		
		if($this->request->get['tags_id']){
			$tags_id = $this->request->get['tags_id'];
		}elseif($this->request->post['emp_tag_id']){
			$tags_id = $this->request->post['emp_tag_id'];
		}elseif (!empty($results)) {
			$tags_id = $results['tags_id'];
		} 
		
		$this->load->model('setting/tags');
		$tag_info = $this->model_setting_tags->getTag($tags_id);
		
		if (isset($this->request->post['emp_tag_id'])) {
			$this->data['emp_tag_id'] = $this->request->post['emp_tag_id'];
		}elseif (!empty($tag_info)) {
			$this->data['emp_tag_id'] = $tag_info['tags_id'];
		} else {
			$this->data['emp_tag_id'] = '';
		}
		
		if (isset($this->request->post['emp_tag_id1'])) {
			$this->data['emp_tag_id1'] = $this->request->post['emp_tag_id1'];
		}elseif (!empty($tag_info)) {
			$this->data['emp_tag_id1'] = $tag_info['emp_tag_id'].' : '.$tag_info['emp_first_name'] .' '.$tag_info['emp_last_name'];
		} else {
			$this->data['emp_tag_id1'] = '';
		}
		
		
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success2'])) {
			$this->data['success2'] = $this->session->data['success2'];

			unset($this->session->data['success2']);
		} else {
			$this->data['success2'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
		
		
		
		$this->template = $this->config->get('config_template') . '/template/form/form.tpl';
		$this->response->setOutput($this->render());
		*/
	
	
		$this->getForm();
	
	}
	
	public function jsoncustomsForm(){
		
		$url2 = "";
			
			if ($this->request->get['formreturn_id'] != null && $this->request->get['formreturn_id'] != "") {
				$url2 .= '&formreturn_id=' . $this->request->get['formreturn_id'];
				$formreturn_id = $this->request->get['formreturn_id'];
			}else{
				$formreturn_id = '';
			}
			
			if ($this->request->get['forms_id'] != null && $this->request->get['forms_id'] != "") {
				$url2 .= '&forms_id=' . $this->request->get['forms_id'];
				
				$forms_id = $this->request->get['forms_id'];
			}else{
				$forms_id = '';
			}
			
			
			if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
				$url2 .= '&notes_id=' . $this->request->get['notes_id'];
				$new_form = '2';
				$notes_id = $this->request->get['notes_id'];
				$url2 .= '&new_form=2';
			}else{
				$new_form = '1';
				$notes_id = '';
				$url2 .= '&new_form=1';
			}
				
			if ($this->request->get['forms_design_id'] != null && $this->request->get['forms_design_id'] != "") {
				$url2 .= '&forms_design_id=' . $this->request->get['forms_design_id'];
				$forms_design_id = $this->request->get['forms_design_id'];
			}else{
				$forms_design_id = '';
			}
			
			if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
				$url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
				$facilities_id = $this->request->get['facilities_id'];
			}else{
				$facilities_id = '';
			}
		
		
			if ($this->request->get['task_id'] != null && $this->request->get['task_id'] != "") {
				$url2 .= '&task_id=' . $this->request->get['task_id'];
				$task_id = $this->request->get['task_id'];
			}else{
				$task_id = '';
			}
			if ($this->request->get['emp_tag_id'] != null && $this->request->get['emp_tag_id'] != "") {
				$url2 .= '&emp_tag_id=' . $this->request->get['emp_tag_id'];
				$emp_tag_id = $this->request->get['emp_tag_id'];
			}else{
				$emp_tag_id = '';
			}
			if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
				$url2 .= '&tags_id=' . $this->request->get['tags_id'];
				$tags_id = $this->request->get['tags_id'];
			}else{
				$tags_id = '';
			}
			if ($this->request->get['updatenotes_id'] != null && $this->request->get['updatenotes_id'] != "") {
				$url2 .= '&updatenotes_id=' . $this->request->get['updatenotes_id'];
				$updatenotes_id = $this->request->get['updatenotes_id'];
			}else{
				$updatenotes_id = '';
			}
			
			if($this->request->get['tags_id']){
				$tags_id = $this->request->get['tags_id'];
			}elseif($this->request->get['emp_tag_id']){
				$tags_id = $this->request->get['emp_tag_id'];
			}
			
			if($tags_id != null && $tags_id != ""){
				$this->load->model('setting/tags');
				$tag_info = $this->model_setting_tags->getTag($tags_id);
				$name = $tag_info['emp_tag_id'].' : '.$tag_info['emp_first_name'] .' '.$tag_info['emp_last_name'];
			}else{
				$name = '';
			}
			
			if ($this->request->get['archive_forms_id'] != null && $this->request->get['archive_forms_id'] != "") {
				$url2 .= '&archive_forms_id=' . $this->request->get['archive_forms_id'];
				
				$archive_forms_id = $this->request->get['archive_forms_id'];
			}else{
				$archive_forms_id = '';
			}
			
			
			$this->data['facilitiess'][] = array(
				'task_form'    => '',
				'archive_forms_id'    => $archive_forms_id,
				'formreturn_id'    => $formreturn_id,
				'task_id'    => $task_id,
				'emp_tag_id'    => $emp_tag_id,
				'name'    => $name,
				'tags_id'    => $tags_id,
				'new_form'    => $new_form,
				'forms_id'    => $forms_id,
				'notes_id'    => $notes_id,
				'updatenotes_id'    => $updatenotes_id,
				'facilities_id'    => $facilities_id,
				'forms_design_id'    => $forms_design_id,
				'signature_url'    => str_replace('&amp;', '&',$this->url->link('services/form/insert2', '' . $url2, 'SSL')),
			);
		
		
		if($this->request->get['is_html'] == '1'){
			
			//$this->data['signature_url'] = str_replace('&amp;', '&',$this->url->link('services/form/insert2', '' . $url2, 'SSL'));
			$this->template = $this->config->get('config_template') . '/template/form/jsoncustom.tpl';
			
			$this->response->setOutput($this->render());
		}else{
			
			
			
			$value = array('results'=>$this->data['facilitiess'],'status'=>true);
		
			$this->response->setOutput(json_encode($value));
		}
		
	}
}