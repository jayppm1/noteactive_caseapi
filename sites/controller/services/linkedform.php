<?php
class Controllerserviceslinkedform extends Controller {
	private $error = array();
	
	public function index() {
		
		$this->load->model('notes/notes');
		if($this->request->get['forms_design_id']  != NULL && $this->request->get['forms_design_id'] !=""){
			$forms_design_id = $this->request->get['forms_design_id'];
		
		}
		
		$this->getForm();
	}
	
	protected function getForm() {
		
		$this->load->language('form/form');
		$this->load->model('form/form');
		
		$this->language->load('notes/notes');
		
		
		if($this->request->get['facilities_id']){
			$this->load->model('facilities/facilities');
				
			$facilities_info = $this->model_facilities_facilities->getfacilities($this->request->get['facilities_id']);
				
			$this->load->model('setting/timezone');
				
			$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
			$facilitytimezone = $timezone_info['timezone_value'];
		}
		
		date_default_timezone_set($facilitytimezone);
		
		$this->data['forms_design_id'] = $this->request->get['forms_design_id'];
		$this->data['forms_id'] = $this->request->get['forms_id'];
		
		$fromdatas = $this->model_form_form->getFormdata($this->request->get['forms_design_id']);
		
		$this->data['form_name'] = $fromdatas['form_name'];
		$this->data['client_reqired'] = $fromdatas['client_reqired'];
		$this->data['form_type'] = $fromdatas['form_type'];
		
		
		$this->load->model('setting/tags');
		$this->load->model('form/form');
		
		if ($this->request->get['facilities_id'] != '' && $this->request->get['facilities_id'] != null) {
            $facilities_id = $this->request->get['facilities_id'];
        } else {
            $facilities_id = '';
        }
		
			$data = array();
			if (($this->request->post['form_submit'] == '1') && $this->validateForm()) {	
				
				
				$this->data['addbutton'] = '1';	
				
				$forminfo = $this->model_form_form->getFormdata($this->request->get['forms_design_id']);
				
				if($forminfo['form_type'] == 'Screening' || $forminfo['form_type'] == 'Intake'){
					
					$data = array(
					 'tags_exits' => '1',
					 //'discharge' => '2',
					 'external_id' => $this->request->post['external_id'],
					 'exits_ssn' => $this->request->post['ssn'],
					 'exits_dob' => $this->request->post['dob'],
					 'emp_first_name' => $this->request->post['first_name'],
					 'emp_last_name' => $this->request->post['last_name'],
					 'facilities_id' => $facilities_id,
					 'status' => '1',
					 'order' => 'ASC',
					);
					
					$formvalues = $this->model_setting_tags->getTags($data);
					
					foreach ($formvalues as $result) {
				
					//var_dump($result);
				
						if($result['date_of_screening'] != "0000-00-00"){
							$date_of_screening = date('m-d-Y', strtotime($result['date_of_screening']));
						}else{
							$date_of_screening = date('m-d-Y');
						}
						if($result['dob'] != "0000-00-00"){
							$dob = date('m-d-Y', strtotime($result['dob']));
						}else{
							$dob = '';
						}
						
						/*if($result['gender'] == '1'){
							$gender = 'Male';
						}
						if($result['gender'] == '2'){
							$gender = 'Female';
						}
						*/
						
						
						if($result['ssn']){
							$ssn = $result['ssn'] .' ';
						}else{
							$ssn = '';
						}
						if($result['emp_extid']){
							$emp_extid = $result['emp_extid'] .' ';
						}else{
							$emp_extid = '';
						}
						
						$fullname = $result['emp_tag_id'].': '.$result['emp_first_name'].' '. $result['emp_last_name'] . $ssn . $emp_extid . $dob;
						
						
						$screening_tags_id = 0;
						$screening_forms_id = 0;
						$intake_forms_id = 0;
						
						if($result['discharge'] == 0){
							
							$addtags_info = $this->model_form_form->gettagsforma($result['tags_id']);
							$addtags_info2 = $this->model_form_form->gettagsforma2($result['tags_id']);
							
							$screening_tags_id = $addtags_info['tags_id'];
							$screening_forms_id = $addtags_info['forms_id'];
							$screening_notes_id = $addtags_info['notes_id'];
							
							$intake_forms_id = $addtags_info2['forms_id'];
							$intake_notes_id = $addtags_info2['notes_id'];
						}
						
						$this->data['formvalues'][] = array(
							'tags_id' => $result['tags_id'], 
							'screening_tags_id' => $screening_tags_id, 
							'screening_forms_id' => $screening_forms_id, 
							'screening_notes_id' => $screening_notes_id, 
							'intake_forms_id' => $intake_forms_id, 
							'intake_notes_id' => $intake_notes_id, 
							'emp_tag_id' => $result['emp_tag_id'], 
							'emp_first_name' => $result['emp_first_name'], 
							'emp_last_name' => $result['emp_last_name'], 
							'location_address'=> $result['location_address'], 
							'discharge'=> $result['discharge'], 
							'dob'=> $dob, 
							'medication'=> $result['medication'], 
							'gender'=> $result['gender'], 
							'person_screening'=> $result['person_screening'], 
							'date_of_screening'=> $date_of_screening, 
							'ssn'=> $result['ssn'], 
							'state'=> $result['state'], 
							'city'=> $result['city'], 
							'zipcode'=> $result['zipcode'], 
							'room'=> $result['room'], 
							'restriction_notes'=> $result['restriction_notes'], 
							'prescription'=> $result['prescription'], 
							'constant_sight'=> $result['constant_sight'], 
							'alert_info'=> $result['alert_info'], 
							'med_mental_health'=> $result['med_mental_health'], 
							'tagstatus'=> $result['tagstatus'], 
							'emp_extid'=> $result['emp_extid'],
							'upload_file'=> $upload_file,
							'image_url1'=> $image_url1,
						);
						
					}
					
					
				}else{
					$data = array(
					 'tags_exits' => '1',
					 'discharge' => '1',
					 'external_id' => $this->request->post['external_id'],
					 'exits_ssn' => $this->request->post['ssn'],
					 'exits_dob' => $this->request->post['dob'],
					 'emp_first_name' => $this->request->post['first_name'],
					 'emp_last_name' => $this->request->post['last_name'],
					 'facilities_id' => $facilities_id,
					 'status' => '1',
					 'order' => 'ASC',
					);
					
					$formvalues = $this->model_setting_tags->getTags($data);
					
					//var_dump($formvalues);
					
					foreach ($formvalues as $result) {
				
						if($result['date_of_screening'] != "0000-00-00"){
							$date_of_screening = date('m-d-Y', strtotime($result['date_of_screening']));
						}else{
							$date_of_screening = date('m-d-Y');
						}
						if($result['dob'] != "0000-00-00"){
							$dob = date('m-d-Y', strtotime($result['dob']));
						}else{
							$dob = '';
						}
						
						/*if($result['gender'] == '1'){
							$gender = 'Male';
						}
						if($result['gender'] == '2'){
							$gender = 'Female';
						}
						*/
						
						
						if($result['ssn']){
							$ssn = $result['ssn'] .' ';
						}else{
							$ssn = '';
						}
						if($result['emp_extid']){
							$emp_extid = $result['emp_extid'] .' ';
						}else{
							$emp_extid = '';
						}
						
						$fullname = $result['emp_tag_id'].': '.$result['emp_first_name'].' '. $result['emp_last_name'] . $ssn . $emp_extid . $dob;
						
						$screening_tags_id = 0;
						$screening_forms_id = 0;
						$intake_forms_id = 0;
						
						if($result['discharge'] == 0){
							
							$addtags_info = $this->model_form_form->gettagsforma($result['tags_id']);
							$addtags_info2 = $this->model_form_form->gettagsforma2($result['tags_id']);
							
							$screening_tags_id = $addtags_info['tags_id'];
							$screening_forms_id = $addtags_info['forms_id'];
							$screening_notes_id = $addtags_info['notes_id'];
							
							$intake_forms_id = $addtags_info2['forms_id'];
							$intake_notes_id = $addtags_info2['notes_id'];
						}
						
						$this->data['formvalues'][] = array(
							'tags_id' => $result['tags_id'], 
							
							'screening_tags_id' => $screening_tags_id, 
							//'screening_forms_id' => $screening_forms_id, 
							'screening_notes_id' => $screening_notes_id, 
							//'intake_forms_id' => $intake_forms_id, 
							'intake_notes_id' => $intake_notes_id, 
							
							'emp_tag_id' => $result['emp_tag_id'], 
							'emp_first_name' => $result['emp_first_name'], 
							'emp_last_name' => $result['emp_last_name'], 
							'location_address'=> $result['location_address'], 
							'discharge'=> $result['discharge'], 
							'dob'=> $dob, 
							'medication'=> $result['medication'], 
							'gender'=> $result['gender'], 
							'person_screening'=> $result['person_screening'], 
							'date_of_screening'=> $date_of_screening, 
							'ssn'=> $result['ssn'], 
							'state'=> $result['state'], 
							'city'=> $result['city'], 
							'zipcode'=> $result['zipcode'], 
							'room'=> $result['room'], 
							'restriction_notes'=> $result['restriction_notes'], 
							'prescription'=> $result['prescription'], 
							'constant_sight'=> $result['constant_sight'], 
							'alert_info'=> $result['alert_info'], 
							'med_mental_health'=> $result['med_mental_health'], 
							'tagstatus'=> $result['tagstatus'], 
							'emp_extid'=> $result['emp_extid'],
							'upload_file'=> $upload_file,
							'image_url1'=> $image_url1,
						);
						
					}
					
					
				}
				
			
			
				
				if($forminfo['form_type'] == 'Screening' || $forminfo['form_type'] == 'Intake'){
				
				if( ($this->request->post['external_id'] !=NULL && $this->request->post['external_id'] !="")  || ($this->request->post['ssn'] !=NULL && $this->request->post['ssn'] !="") || ($this->request->post['first_name'] !=NULL && $this->request->post['first_name'] !="") ||  ($this->request->post['last_name'] !=NULL && $this->request->post['last_name'] !="")|| ($this->request->post['dob'] !=NULL && $this->request->post['dob'] !="") ){
					
					$fdata['forms_fields_values'] = array(
						'' . TAG_EXTID . '' => $this->request->post['external_id'],
						'' . TAG_SSN . '' => $this->request->post['ssn'],
						'' . TAG_FNAME . '' => $this->request->post['first_name'],
						'' . TAG_LNAME . '' => $this->request->post['last_name'],
						'' . TAG_DOB . '' => $this->request->post['dob'],
					);
		
					$results = $this->model_form_form->getscrnneningFormdata($fdata, $facilities_id);
					}
					
					
					foreach ($results as $result) {
						$design_forms = unserialize($result['design_forms']);
						
						$notes_info = $this->model_notes_notes->getnotes($result['notes_id']);
						
						// echo "<hr>";
						
						$clientname = "";
						if ($design_forms[0][0]['' . TAG_FNAME . ''] != null && $design_forms[0][0]['' . TAG_FNAME . ''] != "") {
							$clientname = $design_forms[0][0]['' . TAG_FNAME . ''] . ' ' . $design_forms[0][0]['' . TAG_MNAME . ''] . ' ' . $design_forms[0][0]['' . TAG_LNAME . ''] ;
						} else {
							$clientname = $result['incident_number'] . ' ' . date('m-d-Y', strtotime($result['date_added']));
						}
						
						if ($design_forms[0][0]['' . TAG_SCREENING . ''] != "0000-00-00") {
							$date_of_screening = $design_forms[0][0]['' . TAG_SCREENING . ''];
						} else {
							$date_of_screening = date('m-d-Y');
						}
						if ($design_forms[0][0]['' . TAG_DOB . ''] != "0000-00-00") {
							$dob = $design_forms[0][0]['' . TAG_DOB . ''];
							
							$res2 = explode("-", $design_forms[0][0]['' . TAG_DOB . '']);
							$dob222 = $res2[2]."-".$res2[0]."-".$res2[1];
							
							$dobm = $res2[0];
							$dobd = $res2[1];
							$doby = $res2[2];
					
						} else {
							$dob = '';
							$dobm = '';
							$dobd = '';
							$doby = '';
						}
						
					
					
						$this->data['forms'][] = array(
								'incident_number' => $clientname,
								'custom_form_type' => $result['custom_form_type'],
								'forms_id' => $result['forms_id'],
								'emp_first_name' => $design_forms[0][0]['' . TAG_FNAME . ''],
								'emp_middle_name' => $design_forms[0][0]['' . TAG_MNAME . ''],
								'emp_last_name' => $design_forms[0][0]['' . TAG_LNAME . ''],
								'emergency_contact' => $design_forms[0][0]['' . TAG_PHONE . ''],
								'dob' => $dob,
								'month' => $dobm,
								'date' => $dobd,
								'year' => $doby,
								'age' => $design_forms[0][0]['' . TAG_AGE . ''],
								'gender' => $design_forms[0][0]['' . TAG_GENDER . ''],
								'location_address' => $design_forms[0][0]['' . TAG_ADDRESS . ''],
								'address_street2' => '', // $design_forms[0][0]['text_75675662'],
								'person_screening' => $notes_info['user_id'],
								'date_of_screening' => $date_of_screening,
								'ssn' => $design_forms[0][0]['' . TAG_SSN . ''],
								'emp_extid' => $design_forms[0][0]['' . TAG_EXTID . ''],
								'upload_file' => $upload_file,
								'image_url1' => $image_url1,
								'form_date_added' => date('m-d-Y', strtotime($result['date_added'])),
								
								'date_added' => date('m-d-Y', strtotime($notes_info['date_added'])),
								'signature' => $notes_info['signature'],
								'notes_pin' => $notes_info['notes_pin'],
								'notes_type' => $notes_info['notes_type'],
								'username' => $notes_info['user_id']
						);
					}
					
				
				}
				
				if($this->request->post['type'] != null &&  $this->request->post['type'] != null){
					
					$type = explode('##',$this->request->post['type']);
					
					//die;
					$url2 = "";
			
					if ($this->request->get['forms_design_id'] != null && $this->request->get['forms_design_id'] != "") {
						$url2 .= '&forms_design_id=' . $this->request->get['forms_design_id'];
					}
					
					

					if ($type[0] != '0') {
						$url2 .= '&tags_id=' . $type[0];
					}
					
					if($forminfo['form_type'] == 'Screening'){
						if ($type[1] != '0') {
							$url2 .= '&forms_id=' . $type[1];
						}
					}
					
					if($forminfo['form_type'] == 'Intake'){
						if ($type[1] != '0') {
							$url2 .= '&link_forms_id=' . $type[1];
						}
					}
					
					if ($type[2] != '0' ) {
						$url2 .= '&forms_id=' . $type[2];
					}
					
					if ($type[3] != '0' ) {
						$url2 .= '&forms_id=' . $type[3];
					}
					
					if ($type[4] != '0' ) {
						$url2 .= '&notes_id=' . $type[4];
					}
					
					
					if ($type[5] != '0' ) {
						$url2 .= '&notes_id=' . $type[5];
					}
					
					
					if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
						$url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
					}
					
					if ($this->request->get['keyword_id'] != null && $this->request->get['keyword_id'] != "") {
						$url2 .= '&keyword_id=' . $this->request->get['keyword_id'];
					}
					
					if ($this->request->get['activeform_id'] != null && $this->request->get['activeform_id'] != "") {
						$url2 .= '&activeform_id=' . $this->request->get['activeform_id'];
					}
					
					//var_dump($url2);
					//die;
										
					$this->redirect(str_replace('&amp;', '&', $this->url->link('services/form',''. $url2, 'SSL')));
				}
				
			}
			
				if (isset($this->error['warning'])) {
					$this->data['error_warning'] = $this->error['warning'];
				} else {
					$this->data['error_warning'] = '';
				}
				
				if (isset($this->session->data['success'])) {
					$this->data['success'] = $this->session->data['success'];
					unset($this->session->data['success']);
				} else {
					$this->data['success'] = '';
				}

		
			if (isset($this->request->post['external_id'])) {
				$this->data['external_id'] = $this->request->post['external_id'];
			} else {
				$this->data['external_id'] = '';
			}
			
			if (isset($this->request->post['tags_id'])) {
				$this->data['tags_id'] = $this->request->post['tags_id'];
			} else {
				$this->data['tags_id'] = '';
			}
			
			
			if($this->request->get['search_tags_id']){
				$tags_id = $this->request->get['search_tags_id'];
				$this->data['search_tags_id'] = $this->request->get['search_tags_id'];
				
				$this->load->model('setting/tags');
				$tag_info = $this->model_setting_tags->getTagbyEMPID($tags_id);
				$this->data['tagdetails'] = $tag_info;
				
			}
			
			if (isset($this->request->post['first_name'])) {
				$this->data['first_name'] = $this->request->post['first_name'];
			} else {
				$this->data['first_name'] = $tag_info['emp_first_name'];
			}
			
			
			
			if (isset($this->request->post['last_name'])) {
				$this->data['last_name'] = $this->request->post['last_name'];
			} else {
				$this->data['last_name'] = $tag_info['emp_last_name'];
			}
		
		
		
		if (isset($this->request->post['ssn'])) {
			$this->data['ssn'] = $this->request->post['ssn'];
		} else {
			$this->data['ssn'] = '';
		}
		
		
		if (isset($this->request->post['dob'])) {
			$this->data['dob'] = $this->request->post['dob'];
		} else {
			$this->data['dob'] = '';
		}
		
		
		if (isset($this->request->post['phone'])) {
			$this->data['phone'] = $this->request->post['phone'];
		} else {
			$this->data['phone'] = '';
		}
		
		
		if (isset($this->request->post['address'])) {
			$this->data['address'] = $this->request->post['address'];
		} else {
			$this->data['address'] = '';
		}
		
		
		
			
		$url2 = "";
			
		if ($this->request->get['forms_design_id'] != null && $this->request->get['forms_design_id'] != "") {
			$url2 .= '&forms_design_id=' . $this->request->get['forms_design_id'];
		}
		if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
			$url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
		}
		
		if ($this->request->get['keyword_id'] != null && $this->request->get['keyword_id'] != "") {
			$url2 .= '&keyword_id=' . $this->request->get['keyword_id'];
		}
		
		if ($this->request->get['activeform_id'] != null && $this->request->get['activeform_id'] != "") {
			$url2 .= '&activeform_id=' . $this->request->get['activeform_id'];
		}
			
		$this->data['action'] = $this->url->link('services/linkedform', $url2, true);
		$this->data['newfrm'] = str_replace('&amp;', '&',$this->url->link('services/form', $url2, true));
		
		
		$this->template = $this->config->get('config_template') . '/template/form/linkedform.php';
		
		$this->children = array(
			'common/headerpopup',
		);
		$this->response->setOutput($this->render());
		
	}
	

	
	protected function validateForm() {
		
		if($this->request->post['type'] == null &&  $this->request->post['type'] == null){
			
			if( $this->request->post['ssn'] == "" && $this->request->post['dob'] == ""  && $this->request->post['phone'] == "" && $this->request->post['address'] == "" && $this->request->post['first_name'] == "" &&  $this->request->post['last_name'] == "" ){
				$this->error['warning'] = "Invalid Input !!";
			}
		}
		
		
		$forminfo = $this->model_form_form->getFormdata($this->request->get['forms_design_id']);
				
		if($forminfo['form_type'] == 'Screening'){
		
			if($this->request->post['type'] != null &&  $this->request->post['type'] != null){
				$type = explode('##',$this->request->post['type']);
				if($type[0] != 0){
					$tag_info = $this->model_setting_tags->getTag($type[0]);
					if($tag_info['discharge'] == 1){
						$this->error['warning'] = "You can not add screening of discharge client";
					}
				}
			}
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	
	


}