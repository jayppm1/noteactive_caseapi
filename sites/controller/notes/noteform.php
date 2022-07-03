<?php
class Controllernotesnoteform extends Controller {
private $error = array();

	public function forminsert(){
		
	    $this->data['form_outputkey'] = $this->formkey->outputKey();
		if (($this->request->post['form_submit'] == '1') && $this->validateForm2()) {
			
			if($this->request->get['updatenotes_id'] != null && $this->request->get['updatenotes_id'] != ""){
				$this->session->data['ssincedentform'] = $this->request->post['ssincedentform'];
				$this->session->data['success2'] = 'Form Created successfully!';
				$this->session->data['ssincedentform']['update_form'] = '1'; 
				
			}else{
			
			
				$this->session->data['ssincedentform'] = $this->request->post['ssincedentform'];
				$this->session->data['success'] = 'Form Created successfully!';
			}
		}
		
		
		
		/*
		
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
		
		if (isset($this->session->data['success3'])) {
			$this->data['success3'] = $this->session->data['success3'];

			unset($this->session->data['success3']);
		} else {
			$this->data['success3'] = '';
		}
		
		if (isset($this->session->data['success_add_form'])) {
			$this->data['success_add_form'] = $this->session->data['success_add_form'];

			unset($this->session->data['success_add_form']);
		} else {
			$this->data['success_add_form'] = '';
		}
		
		if (isset($this->session->data['success_update_form'])) {
			$this->data['success_update_form'] = $this->session->data['success_update_form'];

			unset($this->session->data['success_update_form']);
		} else {
			$this->data['success_update_form'] = '';
		}
		
		
		$url2 = "";
		
		//$this->data['config_tag_status'] = $this->customer->isTag();
		
		
		if ($this->request->get['updatenotes_id'] != null && $this->request->get['updatenotes_id'] != "") {
			$url2 .= '&updatenotes_id=' . $this->request->get['updatenotes_id'];
		}
		
		if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
			$url2 .= '&searchdate=' . $this->request->get['searchdate'];
		}
		
		
			$config_admin_limit1 = $this->config->get('config_front_limit');
			if($config_admin_limit1 != null && $config_admin_limit1 != ""){
				$config_admin_limit = $config_admin_limit1;
			}else{
				$config_admin_limit = "50";
			}
			
			$timezone_name = $this->customer->isTimezone();
			date_default_timezone_set($timezone_name);
			
			$data = array(
				'searchdate' => date('m-d-Y'),
				'searchdate_app' => '1',
				'facilities_id' => $this->customer->getId(),
			);
	 
			$this->load->model('notes/notes');
			$notes_total = $this->model_notes_notes->getTotalnotess($data);
			$pagenumber_all = ceil($notes_total/$config_admin_limit);
		
			if ($pagenumber_all != null && $pagenumber_all != "") {
				if($pagenumber_all > 1){
				$url2 .= '&page=' . $pagenumber_all;
				}
			}
			
			$this->data['config_tag_status'] = $this->customer->isTag();
		
		if (!isset($this->request->get['incidentform_id'])) {
			$this->data['action2'] = $this->url->link('notes/noteform/forminsert', '' . $url2, 'SSL');
		} else {
			$url2 .= '&incidentform_id=' . $this->request->get['incidentform_id'];
			$this->data['action2'] = $this->url->link('notes/noteform/update', '' . $url2, 'SSL');
			
			$notesID = (string) $this->request->get['incidentform_id'];
			
			require_once(DIR_APPLICATION . 'aws/getItem.php');
					
					
			$response = $dynamodb->getItem([
				'TableName' => DYNAMODBINCIDENT,
				'Key' => [
					'incidentform_id' => [ 'N' => $notesID ] 
				]
			]);
			
			$form_info = array();
			
		 
			$form_info['ssincedentform']['incidentform_id'] = $response['Item']['incidentform_id']['N'];
			$form_info['ssincedentform']['notes_id'] = $response['Item']['notes_id']['N'];
			$form_info['ssincedentform']['incident_number'] = str_replace("&nbsp;","",$response['Item']['incident_number']['S']);
			$form_info['ssincedentform']['restraint_involved'] = str_replace("&nbsp;","",$response['Item']['restraint_involved']['S']);
			$form_info['ssincedentform']['region'] = str_replace("&nbsp;","",$response['Item']['region']['S']);
			$form_info['ssincedentform']['program_code'] = str_replace("&nbsp;","",$response['Item']['program_code']['S']);
			$form_info['ssincedentform']['staff_par_certified'] = str_replace("&nbsp;","",$response['Item']['staff_par_certified']['S']);
			$form_info['ssincedentform']['incident_time'] = str_replace("&nbsp;","",$response['Item']['incident_time']['S']);
			$form_info['ssincedentform']['incident_category'] = str_replace("&nbsp;","",$response['Item']['incident_category']['S']);
			
			$form_info['ssincedentform']['incident_date'] = str_replace("&nbsp;","",$response['Item']['incident_date']['S']);
			$form_info['ssincedentform']['staff_to_youth_ratio'] = str_replace("&nbsp;","",$response['Item']['staff_to_youth_ratio']['S']);
			$form_info['ssincedentform']['duty_officer'] = str_replace("&nbsp;","",$response['Item']['duty_officer']['S']);
			$form_info['ssincedentform']['place_of_occurrence'] = str_replace("&nbsp;","",$response['Item']['place_of_occurrence']['S']);
			$form_info['ssincedentform']['report_time'] = str_replace("&nbsp;","",$response['Item']['report_time']['S']);
			$form_info['ssincedentform']['report_date'] = str_replace("&nbsp;","",$response['Item']['report_date']['S']);
			$form_info['ssincedentform']['program_name'] = str_replace("&nbsp;","",$response['Item']['program_name']['S']);
			$form_info['ssincedentform']['background_information'] = str_replace("&nbsp;","",$response['Item']['background_information']['S']);
			$form_info['ssincedentform']['investigation_initiated'] = str_replace("&nbsp;","",$response['Item']['investigation_initiated']['S']);
			$form_info['ssincedentform']['immediate_action_taken'] =str_replace("&nbsp;","", $response['Item']['immediate_action_taken']['S']);
			$form_info['ssincedentform']['emp_tag_id'] = str_replace("&nbsp;","",$response['Item']['emp_tag_id']['S']);
			$form_info['ssincedentform']['upload_file'] = str_replace("&nbsp;","",$response['Item']['upload_file']['S']);
			$form_info['ssincedentform']['form_signature'] = str_replace("&nbsp;","",$response['Item']['form_signature']['S']);
		}
		
		//var_dump($form_info['ssincedentform']);
		
		$notedetails = $this->model_notes_notes->getnotes($response['Item']['notes_id']['N']);
		$this->data['text_cut_value'] = $notedetails['text_color_cut'];
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->request->post['ssincedentform'])) {
			$this->data['ssincedentform'] = $this->request->post['ssincedentform'];
		} elseif (!empty($form_info)) {
			$this->data['ssincedentform'] = $form_info['ssincedentform'];
		} else {
			$this->data['ssincedentform'] = '';
		}
		
		
		$this->data['incidentform_id'] = $this->request->get['incidentform_id'];
		if($this->request->get['updatenotes_id'] != null && $this->request->get['updatenotes_id'] != ""){
			$this->data['updatenotes_id'] = $this->request->get['updatenotes_id'];
		}
		
		$this->load->model('facilities/facilities');
		$this->data['facilities_info'] = $this->model_facilities_facilities->getfacilities($this->customer->getId());
		
		$facilities_info = $this->data['facilities_info'];
		$this->load->model('setting/timezone'); 
					
		$this->data['timezone_info'] = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
					
		
		$this->data['redirect_url'] = str_replace('&amp;', '&',$this->url->link('notes/noteform/insert2', '' . $url2, 'SSL'));
		$this->data['redirect_url2'] = str_replace('&amp;', '&',$this->url->link('notes/noteform/insert3', '' . $url2, 'SSL'));
		*/
		$this->template = $this->config->get('config_template') . '/template/notes/insert_form_notes.php';
		$this->response->setOutput($this->render());
	}
	
	
	protected function validateForm2() {
	    if ($this->request->post['form_key'] != null && $this->request->post['form_key'] != "") {
	        $formkeyerror = $this->formkey->validate($this->request->post['form_key']);
	    }
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	
	protected function validateFormu() {
	    if ($this->request->post['form_key'] != null && $this->request->post['form_key'] != "") {
	        $formkeyerror = $this->formkey->validate($this->request->post['form_key']);
	    }

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	
	public function update(){
	    $this->data['form_outputkey'] = $this->formkey->outputKey();
			if (($this->request->post['form_submit'] == '1') && $this->validateFormu()) {
			
			if($this->request->get['incidentform_id'] != null && $this->request->get['incidentform_id'] != ""){
				
			//var_dump($this->request->post['ssincedentform']);die; 
			
				$this->session->data['ssincedentform'] = $this->request->post['ssincedentform'];
				$this->session->data['ssincedentform']['incidentform_id'] = (string) $this->request->get['incidentform_id'];
				$this->session->data['ssincedentform']['randID'] = (string) rand ();
				
				$this->session->data['ssincedentform']['update_form'] = '1';
				 
				//require_once(DIR_APPLICATION . 'aws/updatedb.php');
				
				$this->session->data['success3'] = 'Form updated successfully!';
				
				
				
			}
			
			$url2 = "";
		
		//$this->data['config_tag_status'] = $this->customer->isTag();
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		
		if ($this->request->get['updatenotes_id'] != null && $this->request->get['updatenotes_id'] != "") {
			$url2 .= '&updatenotes_id=' . $this->request->get['updatenotes_id'];
		}
		
		if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
			$url2 .= '&searchdate=' . $this->request->get['searchdate'];
		}
		
		
			$config_admin_limit1 = $this->config->get('config_front_limit');
			if($config_admin_limit1 != null && $config_admin_limit1 != ""){
				$config_admin_limit = $config_admin_limit1;
			}else{
				$config_admin_limit = "50";
			}
			
			$timezone_name = $this->customer->isTimezone();
			date_default_timezone_set($timezone_name);
			
			$data = array(
				'searchdate' => date('m-d-Y'),
				'searchdate_app' => '1',
				'facilities_id' => $this->customer->getId(),
			);
	 
			$this->load->model('notes/notes');
			$notes_total = $this->model_notes_notes->getTotalnotess($data);
			$pagenumber_all = ceil($notes_total/$config_admin_limit);
		
			if ($pagenumber_all != null && $pagenumber_all != "") {
				if($pagenumber_all > 1){
				$url2 .= '&page=' . $pagenumber_all;
				}
			}
			
			
			if ($this->request->get['incidentform_id'] != null && $this->request->get['incidentform_id'] != "") {
				$url2 .= '&incidentform_id=' . $this->request->get['incidentform_id'];
			}
			
			$this->redirect($this->url->link('notes/noteform/forminsert', ''. $url2, 'SSL'));

			
		}
	}
	
	
	public function insert2() {
		$this->language->load('notes/notes');

		$this->document->setTitle($this->language->get('heading_title'));
		$this->data['form_outputkey'] = $this->formkey->outputKey();
		$this->load->model('notes/notes');

		if (($this->request->post['form_submit'] == '1') && $this->validateForm23()) {
			
			//$this->model_notes_notes->updatenotes($this->request->post, $this->customer->getId(), $this->session->data['notes_id']);
			
			$this->session->data['ssincedentform']['notes_id'] = (string) $this->request->get['updatenotes_id'];
			$this->session->data['ssincedentform']['randID'] = (string) rand ();
			
			
			$this->session->data['ssincedentform']['notes_pin'] = $this->request->post['notes_pin'];
			$this->session->data['ssincedentform']['user_id'] = $this->request->post['user_id'];
			$this->session->data['ssincedentform']['signature'] = $this->request->post['imgOutput'];
			
			$timezone_name = $this->customer->isTimezone();
			
			$timeZone = date_default_timezone_set($timezone_name);
			
			//$noteDate = date('Y-m-d H:i:s', strtotime('now'));
			
			$timezone_name = $this->customer->isTimezone();
			$timeZone = date_default_timezone_set($timezone_name);
			$noteDate = date('Y-m-d H:i:s', strtotime('now'));
			$date_added = (string) $noteDate;
			
			$this->session->data['ssincedentform']['form_date_added'] = (string) $noteDate;
			$this->session->data['ssincedentform']['facilities_id'] = (string) $this->customer->getId();
			
			
			/*
			var_dump($this->session->data['ssincedentform'] );
			echo "<hr>";*/
			require_once(DIR_APPLICATION . 'aws/insert.php');
			
			$form_description = "";
			if($this->session->data['ssincedentform']['program_code'] != null && $this->session->data['ssincedentform']['program_code'] != ""){
				$form_description .= $this->session->data['ssincedentform']['program_code'] ." ";
			}
			
			if($this->session->data['ssincedentform']['program_name'] != null && $this->session->data['ssincedentform']['program_name'] != ""){
				$form_description .= $this->session->data['ssincedentform']['program_name'] ." ";
			}
			if($this->session->data['ssincedentform']['incident_number'] != null && $this->session->data['ssincedentform']['incident_number'] != ""){
				$form_description .= $this->session->data['ssincedentform']['incident_number'] ." ";
			}
			if($this->session->data['ssincedentform']['duty_officer'] != null && $this->session->data['ssincedentform']['duty_officer'] != ""){
				$form_description .= $this->session->data['ssincedentform']['duty_officer'] ." ";
			}
			if($this->session->data['ssincedentform']['place_of_occurrence'] != null && $this->session->data['ssincedentform']['place_of_occurrence'] != ""){
				$form_description .= $this->session->data['ssincedentform']['place_of_occurrence'] ." ";
			}
			if($this->session->data['ssincedentform']['restraint_involved'] != null && $this->session->data['ssincedentform']['restraint_involved'] != ""){
				$form_description .= $this->session->data['ssincedentform']['restraint_involved'] ." ";
			}
			if($this->session->data['ssincedentform']['staff_par_certified'] != null && $this->session->data['ssincedentform']['staff_par_certified'] != ""){
				$form_description .= $this->session->data['ssincedentform']['staff_par_certified'] ." ";
			}
			if($this->session->data['ssincedentform']['staff_to_youth_ratio'] != null && $this->session->data['ssincedentform']['staff_to_youth_ratio'] != ""){
				$form_description .= $this->session->data['ssincedentform']['staff_to_youth_ratio'] ." ";
			}
			if($this->session->data['ssincedentform']['investigation_initiated'] != null && $this->session->data['ssincedentform']['investigation_initiated'] != ""){
				$form_description .= $this->session->data['ssincedentform']['investigation_initiated'] ." ";
			}
			if($this->session->data['ssincedentform']['incident_category'] != null && $this->session->data['ssincedentform']['incident_category'] != ""){
				$form_description .= $this->session->data['ssincedentform']['incident_category'] ." ";
			}
			if($this->session->data['ssincedentform']['background_information'] != null && $this->session->data['ssincedentform']['background_information'] != ""){
				$form_description .= $this->session->data['ssincedentform']['background_information'] ." ";
			}
			if($this->session->data['ssincedentform']['immediate_action_taken'] != null && $this->session->data['ssincedentform']['immediate_action_taken'] != ""){
				$form_description .= $this->session->data['ssincedentform']['immediate_action_taken'] ." ";
			}
			if($this->session->data['ssincedentform']['region'] != null && $this->session->data['ssincedentform']['region'] != ""){
				$form_description .= $this->session->data['ssincedentform']['region'] ." ";
			}
			
			if($this->session->data['ssincedentform']['emp_tag_id'] != null && $this->session->data['ssincedentform']['emp_tag_id'] != ""){
				$form_description .= $this->session->data['ssincedentform']['emp_tag_id'] ." ";
			}
			
			$this->load->model('notes/notes');
			$noteDetails = $this->model_notes_notes->getnotes($this->session->data['ssincedentform']['notes_id']);
			$date_added1 = $noteDetails['date_added'];
			
			$notes_description = $noteDetails['notes_description']; 
			
			$pcode = "";
			if($this->session->data['ssincedentform']['program_code'] == '1'){
				$pcode = incident_severity1;
			}
			
			if($this->session->data['ssincedentform']['program_code'] == '2'){
				$pcode = incident_severity2;
			}
			
			if($this->session->data['ssincedentform']['program_code'] == '3'){
				$pcode = incident_severity3;
			}
			if($this->session->data['ssincedentform']['program_code'] == '4'){
				$pcode = incident_severity4;
			}
			if($this->session->data['ssincedentform']['program_code'] == '5'){
				$pcode = incident_severity5;
			}
			
			if($pcode){
				$notes_description = $notes_description .' | '. $pcode;
			}else{
				$notes_description = $notes_description;
			}
			
				/*
				$ifdata = array();
				$ifdata['incident_severity'] = $this->session->data['ssincedentform']['program_code'];
				$ifdata['keyword_search'] = $this->session->data['ssincedentform']['immediate_action_taken'];
				$ifdata['restraint_involved'] = $this->session->data['ssincedentform']['restraint_involved'];
				$ifdata['staff_par_certified'] = $this->session->data['ssincedentform']['staff_par_certified'];
				$ifdata['youth_ratio'] = $this->session->data['ssincedentform']['staff_to_youth_ratio'];
				$ifdata['investigation_initiated'] = $this->session->data['ssincedentform']['investigation_initiated'];
				$ifdata['facilities_id'] = $this->customer->getId();
				
				
				
				$this->load->model('notes/assessment');
				$assessment_info = $this->model_notes_assessment->getassessments($ifdata);
				//var_dump($assessment_info);
				$notes_id = $this->session->data['ssincedentform']['notes_id'];
				
				if($assessment_info){
					$tigger_type = $assessment_info['tigger_type'];
					$assessment_id = $assessment_info['assessment_id'];
					
					if($tigger_type == '1'){
						$highlighter_id = $assessment_info['highlighter_id'];
						$this->load->model('setting/highlighter');
						$highlighterData = $this->model_setting_highlighter->gethighlighter($highlighter_id);
						$highlighter_value = $highlighterData['highlighter_value'];
						
						if($highlighter_id == '21'){
							$highlighter = '0';
							$highlighter_value1 = '';
						}else{
							$highlighter = $highlighter_id;
							$highlighter_value1 = $highlighter_value;
						}
						
						$sql = "UPDATE `" . DB_PREFIX . "notes` SET highlighter_id = '".$highlighter."', highlighter_value = '" . $highlighter_value1 . "', assessment_id = '" . $assessment_id . "' WHERE notes_id = '" . (int)$notes_id . "' ";
						
						$this->db->query($sql);
					}
					
					if($tigger_type == '2'){
						$keyword_id = $assessment_info['keyword_id'];
						
						$this->load->model('setting/keywords');
						
						$keyword_info = $this->model_setting_keywords->getkeywordDetail($keyword_id);
						//var_dump($keyword_info);
						//echo "<hr>";
						
						$this->load->model('setting/image');
					
						$file16 = 'icon/'.$keyword_info['keyword_image'];

						$newfile84 = $this->model_setting_image->resize($file16, 50, 50);
						$newfile216 = DIR_IMAGE . $newfile84;
						$file124 = HTTP_SERVER . 'image/icon/'.$newfile84;
						$imageData132 = base64_encode(file_get_contents($newfile216));
						
						if($newfile84 != null && $newfile84 != ""){
							$keyword_icon = 'data:'.$this->mime_content_type($file124).';base64,'.$imageData132;
						}else{
							$keyword_icon = '';
						}
						$keyword_file = $keyword_info['keyword_image'];
						
						
						$keywordData2 = $this->model_setting_keywords->getkeywordDetaildesc($keyword_info['keyword_image']);
						
						//$notes_description2 = str_replace($keywordData2['keyword_name'],$keywordData2['keyword_name'], $noteDetails['notes_description']);
						$notes_description2 =$keywordData2['keyword_name'] .' '. $noteDetails['notes_description'];
						
						
						$sql12 = "UPDATE `" . DB_PREFIX . "notes` SET notes_description = '" . $notes_description2 . "', keyword_file = '" .$this->db->escape($keyword_file). "', keyword_file_url = '" .$this->db->escape($keyword_icon). "', assessment_id = '" . $assessment_id . "' WHERE notes_id = '" . (int)$notes_id . "' ";
						$this->db->query($sql12);
						
					}
					
					if($tigger_type == '3'){
						$color_id = '#'.$assessment_info['color_id'];
						
						$sql12 = "UPDATE `" . DB_PREFIX . "notes` SET text_color = '" . $color_id . "', assessment_id = '" . $assessment_id . "' WHERE notes_id = '" . (int)$notes_id . "' ";
						$this->db->query($sql12);
					}
					
				
				}*/
			
			$fsql = "INSERT INTO `" . DB_PREFIX . "forms` SET notes_id = '" . $this->session->data['ssincedentform']['notes_id'] . "',form_type_id = '" . $this->session->data['ssincedentform']['randID'] . "', form_type = '1', form_description = '" . $form_description . "', user_id = '" . $this->request->post['user_id'] . "', signature = '" . $this->request->post['imgOutput'] . "', notes_pin = '" . $this->request->post['notes_pin'] . "', form_date_added = '" . $noteDate . "', date_added = '" . $date_added1 . "', incident_number = '" . $this->session->data['ssincedentform']['incident_number'] . "', form_signature = '" . $this->session->data['ssincedentform']['form_signature'] . "', facilities_id = '" . $this->customer->getId() . "', assessment_id = '" . $assessment_id . "' ";
		
			$this->db->query($fsql);
			
			
			if($this->request->post['emp_tag_id'] != null && $this->request->post['emp_tag_id'] != ""){
				$this->load->model('notes/notes');
				$timezone_name = $this->customer->isTimezone();
				date_default_timezone_set($timezone_name);
				$update_date = date('Y-m-d H:i:s', strtotime('now'));
				
				$this->model_notes_notes->updateNotesTag($this->request->post['emp_tag_id'], $this->session->data['ssincedentform']['notes_id'],$this->request->post['tags_id'], $update_date);
			}
			
			
			$timezone_name = $this->customer->isTimezone(); 
			date_default_timezone_set($timezone_name);
			$update_date2 = date('Y-m-d H:i:s', strtotime('now'));
						
			$sql1 = "UPDATE `" . DB_PREFIX . "notes` SET notes_description = '" . $notes_description . "', update_date = '" . $update_date2 . "', notes_conut='0' WHERE notes_id = '" . (int)$this->session->data['ssincedentform']['notes_id'] . "' ";
		
			$this->db->query($sql1);
			
			
			

			$this->session->data['success_add_form'] = $this->language->get('text_success');
			//$this->session->data['add_form'] = '1';

			unset($this->session->data['notesdatas']);
			unset($this->session->data['highlighter_id']);
			unset($this->session->data['notes_id']);
			unset($this->session->data['text_color_cut']);
			unset($this->session->data['text_color']);
			unset($this->session->data['note_date']);
			unset($this->session->data['notes_file']);
			unset($this->session->data['update_reminder']);
			unset($this->session->data['ssincedentform']);
			
			unset($this->session->data['ssbedcheckform']);
			
			unset($this->session->data['keyword_file']);
			//unset($this->session->data['pagenumber']);
			
			$url2 = "";
			if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
				$url2 = '&searchdate=' . $this->request->get['searchdate'];
			}
			/*if ($this->session->data['pagenumber'] != null && $this->session->data['pagenumber'] != "") {
				$url2. = '&page=' . $this->session->data['pagenumber'];
			}*/
			
			$this->redirect(str_replace('&amp;', '&', $this->url->link('notes/noteform/forminsert', '' . $url2, 'SSL')));
		}

		$this->data['entry_pin'] = $this->language->get('entry_pin');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['text_select'] = $this->language->get('text_select');

		$this->load->model('user/user');
		$this->data['users'] = $this->model_user_user->getUsersByFacility($this->customer->getId());

		$this->data['config_tag_status'] = $this->customer->isTag();
		
		$url2 = "";
		
		
		
		if ($this->request->get['updatenotes_id'] != null && $this->request->get['updatenotes_id'] != "") {
			$url2 .= '&updatenotes_id=' . $this->request->get['updatenotes_id'];
		}
		
		if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
			$url2 .= '&searchdate=' . $this->request->get['searchdate'];
		}
		
		
		$config_admin_limit1 = $this->config->get('config_front_limit');
			if($config_admin_limit1 != null && $config_admin_limit1 != ""){
				$config_admin_limit = $config_admin_limit1;
			}else{
				$config_admin_limit = "50";
			}
			
			$timezone_name = $this->customer->isTimezone();
			date_default_timezone_set($timezone_name);
			
			$data = array(
				'searchdate' => date('m-d-Y'),
				'searchdate_app' => '1',
				'facilities_id' => $this->customer->getId(),
			);
	 
			$this->load->model('notes/notes');
			$notes_total = $this->model_notes_notes->getTotalnotess($data);
			$pagenumber_all = ceil($notes_total/$config_admin_limit);
		
			if ($pagenumber_all != null && $pagenumber_all != "") {
				if($pagenumber_all > 1){
				$url2 .= '&page=' . $pagenumber_all;
				}
			}
		 
		$this->data['action2'] = str_replace('&amp;', '&',$this->url->link('notes/noteform/insert2', '' . $url2, 'SSL'));
		$this->data['redirect_url'] = str_replace('&amp;', '&',$this->url->link('notes/notes/insert', '' . $url2, 'SSL'));
		
		if (isset($this->session->data['pagenumber'])) {
			$this->data['pagenumber'] = $this->session->data['pagenumber'];
		} else {
			$this->data['pagenumber'] = '';
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


		if (isset($this->error['select_one'])) {
			$this->data['error_select_one'] = $this->error['select_one'];
		} else {
			$this->data['error_select_one'] = '';
		}
		
		if (isset($this->error['notes_pin'])) {
			$this->data['error_notes_pin'] = $this->error['notes_pin'];
		} else {
			$this->data['error_notes_pin'] = '';
		}

		if (isset($this->error['highlighter_id'])) {
			$this->data['error_highlighter_id'] = $this->error['highlighter_id'];
		} else {
			$this->data['error_highlighter_id'] = '';
		}

		if (isset($this->error['user_id'])) {
			$this->data['error_user_id'] = $this->error['user_id'];
		} else {
			$this->data['error_user_id'] = '';
		}
		
		if (isset($this->request->post['select_one'])) {
			$this->data['select_one'] = $this->request->post['select_one'];
		}  else {
			if($this->config->get('config_default_sign') != null && $this->config->get('config_default_sign') != ""){
				$config_default_sign = '1';//$this->config->get('config_default_sign');
			}else{
				$config_default_sign = '2';
			}
			$this->data['select_one'] = $config_default_sign;
		}

		if($this->config->get('config_default_sign') != null && $this->config->get('config_default_sign') != ""){
			$this->data['default_sign'] = '1';//$this->config->get('config_default_sign');
		}else{
			$this->data['default_sign'] = '2';
		}

		if (isset($this->request->post['notes_pin'])) {
			$this->data['notes_pin'] = $this->request->post['notes_pin'];
		} elseif (!empty($notes_info)) {
			$this->data['notes_pin'] = $notes_info['notes_pin'];
		} else {
			$this->data['notes_pin'] = '';
		}

		if (isset($this->request->post['user_id'])) {
			$this->data['user_id'] = $this->request->post['user_id'];
		} elseif (!empty($notes_info)) {
			$this->data['user_id'] = $notes_info['user_id'];
		} else {
			$this->data['user_id'] = '';
		}
		
		if (isset($this->request->post['emp_tag_id'])) {
			$this->data['emp_tag_id'] = $this->request->post['emp_tag_id'];
		} elseif (!empty($notes_info)) {
			$this->data['emp_tag_id'] = $notes_info['emp_tag_id'];
		} else {
			$this->data['emp_tag_id'] = '';
		}
		
		if (isset($this->request->post['tags_id'])) {
			$this->data['tags_id'] = $this->request->post['tags_id'];
		} elseif (!empty($notes_info)) {
			$this->data['tags_id'] = $notes_info['tags_id'];
		} else {
			$this->data['tags_id'] = '';
		}
		
		if (isset($this->request->post['emp_tag_id_2'])) {
			$this->data['emp_tag_id_2'] = $this->request->post['emp_tag_id_2'];
		} else {
			$this->data['emp_tag_id_2'] = '';
		}


		$this->template = $this->config->get('config_template') . '/template/notes/notes_form2.php';

		$this->response->setOutput($this->render());
			
	}

	protected function validateForm23() {
			
	    if ($this->request->post['form_key'] != null && $this->request->post['form_key'] != "") {
	        $formkeyerror = $this->formkey->validate($this->request->post['form_key']);
	    }
		if ($this->request->post ['username'] == '') {
			$this->error ['user_id'] = $this->language->get ( 'error_required' );
		}
		
		if ($this->request->post ['username'] != '') {
			$this->load->model ( 'user/user' );
			$user_info = $this->model_user_user->getUserByUsernamebynotes ( $this->request->post ['username'],$this->customer->getId () );
			if (empty ( $user_info )) {
				$this->error ['user_id'] = "Enter a valid user.";
			}
		}

		if ($this->request->post['user_id'] != '') {
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if(empty($user_info)){
				$this->error['user_id'] = $this->language->get('error_required');
			}
		}
		
		
		if ($this->request->post['select_one'] == '') {
			$this->error['select_one'] = $this->language->get('error_required');
		}
		
		if ($this->request->post['select_one'] == '1') {
			if ($this->request->post['notes_pin'] == '') {
				$this->error['notes_pin'] = $this->language->get('error_required');
			}
			if($this->request->post['notes_pin'] != null && $this->request->post['notes_pin'] != ""){
				$this->load->model('user/user');
				
				if( $this->request->post ['user_id'] != null &&  $this->request->post ['user_id'] != ""){
					$user_info = $this->model_user_user->getUserByUsername (  $this->request->post ['user_id']);
				}else{
					$user_info = $this->model_user_user->getUserByUsernamebynotes ($this->request->post['username'],$this->customer->getId () );
				}

				
				if (($this->request->post['notes_pin'] != $user_info['user_pin'])) {
					$this->error['warning'] = $this->language->get('error_exists');
				}
			}
		}

		
		if ($this->request->get['task_id'] != '') {
			//var_dump($this->request->get['task_id']);
			$this->load->model('createtask/createtask');
			$result = $this->model_createtask_createtask->getStrikedatadetails($this->request->get['task_id']);
			$task_date = date('m-d-Y', strtotime($result['task_date']));
			
			$timezone_name = $this->customer->isTimezone();
			
			date_default_timezone_set($timezone_name);
			
			$current_date = date('m-d-Y', strtotime('now'));
			
			if($task_date > $current_date){
				$this->error['warning'] = "You connot update future task";
			}
			
			if(empty($result)){
				$this->error['warning'] = "You connot update task because task not exit";
			}
			
			
		}

		/*if(($this->request->post['notes_pin'] == null && $this->request->post['notes_pin'] == "") && ($this->request->post['imgOutput'] == null && $this->request->post['imgOutput'] == "")){
			$this->error['warning'] = 'Please insert at least one required!';

			}*/
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	
	public function insert3() {
		$this->language->load('notes/notes');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('notes/notes');
		$this->data['form_outputkey'] = $this->formkey->outputKey();
			
		if (($this->request->post['form_submit'] == '1') && $this->validateForm23()) {
			
			//$this->model_notes_notes->updatenotes($this->request->post, $this->customer->getId(), $this->session->data['notes_id']);
			
			
			
			
			$this->session->data['ssincedentform']['notes_pin'] = $this->request->post['notes_pin'];
			$this->session->data['ssincedentform']['user_id'] = $this->request->post['user_id'];
			$this->session->data['ssincedentform']['signature'] = $this->request->post['imgOutput'];
			
			//$timezone_name = $this->customer->isTimezone();
			//$timeZone = date_default_timezone_set($timezone_name);
			//$noteDate = date('Y-m-d H:i:s', strtotime('now'));
			
			
			$timezone_name = $this->customer->isTimezone();
			$timeZone = date_default_timezone_set($timezone_name);
			$noteDate = date('Y-m-d H:i:s', strtotime('now'));
			$date_added = (string) $noteDate;
			
			$this->session->data['ssincedentform']['form_date_added'] = (string) $noteDate;
			$this->session->data['ssincedentform']['facilities_id'] = (string) $this->customer->getId();
			
			/*
			var_dump($this->session->data['ssincedentform'] );
			echo "<hr>";*/
			require_once(DIR_APPLICATION . 'aws/updatedb.php');
			
			$form_description = "";
			if($this->session->data['ssincedentform']['program_code'] != null && $this->session->data['ssincedentform']['program_code'] != ""){
				$form_description .= $this->session->data['ssincedentform']['program_code'] ." ";
			}
			
			if($this->session->data['ssincedentform']['program_name'] != null && $this->session->data['ssincedentform']['program_name'] != ""){
				$form_description .= $this->session->data['ssincedentform']['program_name'] ." ";
			}
			if($this->session->data['ssincedentform']['incident_number'] != null && $this->session->data['ssincedentform']['incident_number'] != ""){
				$form_description .= $this->session->data['ssincedentform']['incident_number'] ." ";
			}
			if($this->session->data['ssincedentform']['duty_officer'] != null && $this->session->data['ssincedentform']['duty_officer'] != ""){
				$form_description .= $this->session->data['ssincedentform']['duty_officer'] ." ";
			}
			if($this->session->data['ssincedentform']['place_of_occurrence'] != null && $this->session->data['ssincedentform']['place_of_occurrence'] != ""){
				$form_description .= $this->session->data['ssincedentform']['place_of_occurrence'] ." ";
			}
			if($this->session->data['ssincedentform']['restraint_involved'] != null && $this->session->data['ssincedentform']['restraint_involved'] != ""){
				$form_description .= $this->session->data['ssincedentform']['restraint_involved'] ." ";
			}
			if($this->session->data['ssincedentform']['staff_par_certified'] != null && $this->session->data['ssincedentform']['staff_par_certified'] != ""){
				$form_description .= $this->session->data['ssincedentform']['staff_par_certified'] ." ";
			}
			if($this->session->data['ssincedentform']['staff_to_youth_ratio'] != null && $this->session->data['ssincedentform']['staff_to_youth_ratio'] != ""){
				$form_description .= $this->session->data['ssincedentform']['staff_to_youth_ratio'] ." ";
			}
			if($this->session->data['ssincedentform']['investigation_initiated'] != null && $this->session->data['ssincedentform']['investigation_initiated'] != ""){
				$form_description .= $this->session->data['ssincedentform']['investigation_initiated'] ." ";
			}
			if($this->session->data['ssincedentform']['incident_category'] != null && $this->session->data['ssincedentform']['incident_category'] != ""){
				$form_description .= $this->session->data['ssincedentform']['incident_category'] ." ";
			}
			if($this->session->data['ssincedentform']['background_information'] != null && $this->session->data['ssincedentform']['background_information'] != ""){
				$form_description .= $this->session->data['ssincedentform']['background_information'] ." ";
			}
			if($this->session->data['ssincedentform']['immediate_action_taken'] != null && $this->session->data['ssincedentform']['immediate_action_taken'] != ""){
				$form_description .= $this->session->data['ssincedentform']['immediate_action_taken'] ." ";
			}
			if($this->session->data['ssincedentform']['region'] != null && $this->session->data['ssincedentform']['region'] != ""){
				$form_description .= $this->session->data['ssincedentform']['region'] ." ";
			}
			
			if($this->session->data['ssincedentform']['emp_tag_id'] != null && $this->session->data['ssincedentform']['emp_tag_id'] != ""){
				$form_description .= $this->session->data['ssincedentform']['emp_tag_id'] ." ";
			}
			/*
			$ifdata = array();
				$ifdata['incident_severity'] = $this->session->data['ssincedentform']['program_code'];
				$ifdata['keyword_search'] = $this->session->data['ssincedentform']['immediate_action_taken'];
				$ifdata['restraint_involved'] = $this->session->data['ssincedentform']['restraint_involved'];
				$ifdata['staff_par_certified'] = $this->session->data['ssincedentform']['staff_par_certified'];
				$ifdata['youth_ratio'] = $this->session->data['ssincedentform']['staff_to_youth_ratio'];
				$ifdata['investigation_initiated'] = $this->session->data['ssincedentform']['investigation_initiated'];
				$ifdata['facilities_id'] = $this->customer->getId();
				
				
				
				$this->load->model('notes/assessment');
				$assessment_info = $this->model_notes_assessment->getassessments($ifdata);
				//var_dump($assessment_info);
				$notes_id = $this->session->data['ssincedentform']['notes_id'];
				
				if($assessment_info){
					$tigger_type = $assessment_info['tigger_type'];
					$assessment_id = $assessment_info['assessment_id'];
					
					if($tigger_type == '1'){
						$highlighter_id = $assessment_info['highlighter_id'];
						$this->load->model('setting/highlighter');
						$highlighterData = $this->model_setting_highlighter->gethighlighter($highlighter_id);
						$highlighter_value = $highlighterData['highlighter_value'];
						
						if($highlighter_id == '21'){
							$highlighter = '0';
							$highlighter_value1 = '';
						}else{
							$highlighter = $highlighter_id;
							$highlighter_value1 = $highlighter_value;
						}
						
						$sql = "UPDATE `" . DB_PREFIX . "notes` SET highlighter_id = '".$highlighter."', highlighter_value = '" . $highlighter_value1 . "', assessment_id = '" . $assessment_id . "' WHERE notes_id = '" . (int)$notes_id . "' ";
						
						$this->db->query($sql);
					}
					
					if($tigger_type == '2'){
						$keyword_id = $assessment_info['keyword_id'];
						
						$this->load->model('setting/keywords');
						
						$keyword_info = $this->model_setting_keywords->getkeywordDetail($keyword_id);
						//var_dump($keyword_info);
						//echo "<hr>";
						
						$this->load->model('setting/image');
					
						$file16 = 'icon/'.$keyword_info['keyword_image'];

						$newfile84 = $this->model_setting_image->resize($file16, 50, 50);
						$newfile216 = DIR_IMAGE . $newfile84;
						$file124 = HTTP_SERVER . 'image/icon/'.$newfile84;
						$imageData132 = base64_encode(file_get_contents($newfile216));
						
						if($newfile84 != null && $newfile84 != ""){
							$keyword_icon = 'data:'.$this->mime_content_type($file124).';base64,'.$imageData132;
						}else{
							$keyword_icon = '';
						}
						$keyword_file = $keyword_info['keyword_image'];
						
						
						$keywordData2 = $this->model_setting_keywords->getkeywordDetaildesc($keyword_info['keyword_image']);
						
						//$notes_description2 = str_replace($keywordData2['keyword_name'],$keywordData2['keyword_name'], $noteDetails['notes_description']);
						$notes_description2 =$keywordData2['keyword_name'] .' '. $noteDetails['notes_description'];
						
						
						$sql12 = "UPDATE `" . DB_PREFIX . "notes` SET notes_description = '" . $notes_description2 . "', keyword_file = '" .$this->db->escape($keyword_file). "', keyword_file_url = '" .$this->db->escape($keyword_icon). "', assessment_id = '" . $assessment_id . "' WHERE notes_id = '" . (int)$notes_id . "' ";
						$this->db->query($sql12);
						
					}
					
					if($tigger_type == '3'){
						$color_id = '#'.$assessment_info['color_id'];
						
						$sql12 = "UPDATE `" . DB_PREFIX . "notes` SET text_color = '" . $color_id . "', assessment_id = '" . $assessment_id . "' WHERE notes_id = '" . (int)$notes_id . "' ";
						$this->db->query($sql12);
					}
					
				
				}*/
			
			
			 $fsql = "UPDATE `" . DB_PREFIX . "forms` SET notes_id = '" . $this->session->data['ssincedentform']['notes_id'] . "', form_type = '1', form_description = '" . $form_description . "', user_id = '" . $this->request->post['user_id'] . "', signature = '" . $this->request->post['imgOutput'] . "', notes_pin = '" . $this->request->post['notes_pin'] . "', form_date_added = '" . $noteDate . "', incident_number = '" . $this->session->data['ssincedentform']['incident_number'] . "', form_signature = '" . $this->session->data['ssincedentform']['form_signature'] . "', facilities_id = '" . $this->customer->getId() . "', assessment_id = '" . $assessment_id . "' WHERE form_type_id = '" . $this->session->data['ssincedentform']['incidentform_id'] . "' ";
		
			$this->db->query($fsql); 
			
			if($this->request->post['emp_tag_id'] != null && $this->request->post['emp_tag_id'] != ""){
				$this->load->model('notes/notes');
				
				$timezone_name = $this->customer->isTimezone();
				date_default_timezone_set($timezone_name);
				$update_date = date('Y-m-d H:i:s', strtotime('now'));
				
				$this->model_notes_notes->updateNotesTag($this->request->post['emp_tag_id'], $this->session->data['ssincedentform']['notes_id'],$this->request->post['tags_id'], $update_date);
			}
			

			$this->session->data['success_update_form'] = $this->language->get('text_success');
			//$this->session->data['add_form'] = '1';

			unset($this->session->data['notesdatas']);
			unset($this->session->data['highlighter_id']);
			unset($this->session->data['notes_id']);
			unset($this->session->data['text_color_cut']);
			unset($this->session->data['text_color']);
			unset($this->session->data['note_date']);
			unset($this->session->data['notes_file']);
			unset($this->session->data['update_reminder']);
			unset($this->session->data['ssincedentform']);
			
			unset($this->session->data['ssbedcheckform']);
			
			unset($this->session->data['keyword_file']);
			//unset($this->session->data['pagenumber']);
			
			$url2 = "";
			if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
				$url2 = '&searchdate=' . $this->request->get['searchdate'];
			}
			/*if ($this->session->data['pagenumber'] != null && $this->session->data['pagenumber'] != "") {
				$url2. = '&page=' . $this->session->data['pagenumber'];
			}*/
			
			$this->redirect(str_replace('&amp;', '&', $this->url->link('notes/noteform/forminsert', '' . $url2, 'SSL')));
		}

		$this->data['entry_pin'] = $this->language->get('entry_pin');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['text_select'] = $this->language->get('text_select');

		$this->load->model('user/user');
		$this->data['users'] = $this->model_user_user->getUsersByFacility($this->customer->getId());

		
		$url2 = "";
		
		$this->data['config_tag_status'] = $this->customer->isTag();
		
		if ($this->request->get['updatenotes_id'] != null && $this->request->get['updatenotes_id'] != "") {
			$url2 .= '&updatenotes_id=' . $this->request->get['updatenotes_id'];
		}
		
		if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
			$url2 .= '&searchdate=' . $this->request->get['searchdate'];
		}
		
		
		$config_admin_limit1 = $this->config->get('config_front_limit');
			if($config_admin_limit1 != null && $config_admin_limit1 != ""){
				$config_admin_limit = $config_admin_limit1;
			}else{
				$config_admin_limit = "50";
			}
			
			$timezone_name = $this->customer->isTimezone();
			date_default_timezone_set($timezone_name);
			
			$data = array(
				'searchdate' => date('m-d-Y'),
				'searchdate_app' => '1',
				'facilities_id' => $this->customer->getId(),
			);
	 
			$this->load->model('notes/notes');
			$notes_total = $this->model_notes_notes->getTotalnotess($data);
			$pagenumber_all = ceil($notes_total/$config_admin_limit);
		
			if ($pagenumber_all != null && $pagenumber_all != "") {
				if($pagenumber_all > 1){
				$url2 .= '&page=' . $pagenumber_all;
				}
			}
		 
		$this->data['action2'] = str_replace('&amp;', '&',$this->url->link('notes/noteform/insert3', '' . $url2, 'SSL'));
		$this->data['redirect_url'] = str_replace('&amp;', '&',$this->url->link('notes/notes/insert', '' . $url2, 'SSL'));
		
		if (isset($this->session->data['pagenumber'])) {
			$this->data['pagenumber'] = $this->session->data['pagenumber'];
		} else {
			$this->data['pagenumber'] = '';
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


		if (isset($this->error['select_one'])) {
			$this->data['error_select_one'] = $this->error['select_one'];
		} else {
			$this->data['error_select_one'] = '';
		}
		
		if (isset($this->error['notes_pin'])) {
			$this->data['error_notes_pin'] = $this->error['notes_pin'];
		} else {
			$this->data['error_notes_pin'] = '';
		}

		if (isset($this->error['highlighter_id'])) {
			$this->data['error_highlighter_id'] = $this->error['highlighter_id'];
		} else {
			$this->data['error_highlighter_id'] = '';
		}

		if (isset($this->error['user_id'])) {
			$this->data['error_user_id'] = $this->error['user_id'];
		} else {
			$this->data['error_user_id'] = '';
		}
		
		if (isset($this->request->post['select_one'])) {
			$this->data['select_one'] = $this->request->post['select_one'];
		}  else {
			if($this->config->get('config_default_sign') != null && $this->config->get('config_default_sign') != ""){
				$config_default_sign = '1';//$this->config->get('config_default_sign');
			}else{
				$config_default_sign = '2';
			}
			$this->data['select_one'] = $config_default_sign;
		}

		if($this->config->get('config_default_sign') != null && $this->config->get('config_default_sign') != ""){
			$this->data['default_sign'] = '1';//$this->config->get('config_default_sign');
		}else{
			$this->data['default_sign'] = '2';
		}

		if (isset($this->request->post['notes_pin'])) {
			$this->data['notes_pin'] = $this->request->post['notes_pin'];
		} elseif (!empty($notes_info)) {
			$this->data['notes_pin'] = $notes_info['notes_pin'];
		} else {
			$this->data['notes_pin'] = '';
		}

		if (isset($this->request->post['user_id'])) {
			$this->data['user_id'] = $this->request->post['user_id'];
		} elseif (!empty($notes_info)) {
			$this->data['user_id'] = $notes_info['user_id'];
		} else {
			$this->data['user_id'] = '';
		}
		
		if (isset($this->request->post['emp_tag_id'])) {
			$this->data['emp_tag_id'] = $this->request->post['emp_tag_id'];
		} elseif (!empty($notes_info)) {
			$this->data['emp_tag_id'] = $notes_info['emp_tag_id'];
		} else {
			$this->data['emp_tag_id'] = '';
		}
		
		
		if (isset($this->request->post['tags_id'])) {
			$this->data['tags_id'] = $this->request->post['tags_id'];
		} elseif (!empty($notes_info)) {
			$this->data['tags_id'] = $notes_info['tags_id'];
		} else {
			$this->data['tags_id'] = '';
		}
		
		if (isset($this->request->post['emp_tag_id_2'])) {
			$this->data['emp_tag_id_2'] = $this->request->post['emp_tag_id_2'];
		} else {
			$this->data['emp_tag_id_2'] = '';
		}


		$this->template = $this->config->get('config_template') . '/template/notes/notes_form2.php';

		$this->response->setOutput($this->render());
			
	}
	
	
	public function taskforminsert(){
		
		$task_id = $this->request->get['task_id'];
		$this->load->model('createtask/createtask');
		$this->load->model('facilities/facilities');
		$this->data['task_info'] = $this->model_createtask_createtask->getnotesInfo2($task_id);
		$this->data['form_outputkey'] = $this->formkey->outputKey();
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm2()) {
			
			$this->session->data['ssincedentform'] = $this->request->post['ssincedentform'];
			
			$this->session->data['ssincedentform']['update_form'] = '1'; 
			
			$this->session->data['success3'] = 'Form Created successfully!';
			
		}
		
		$this->data['facilities_info'] = $this->model_facilities_facilities->getfacilities($this->customer->getId());
		
		
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
		
		if (isset($this->session->data['success3'])) {
			$this->data['success3'] = $this->session->data['success3'];

			unset($this->session->data['success3']);
		} else {
			$this->data['success3'] = '';
		}
		
		if (isset($this->session->data['success_add_form'])) {
			$this->data['success_add_form'] = $this->session->data['success_add_form'];

			unset($this->session->data['success_add_form']);
		} else {
			$this->data['success_add_form'] = '';
		}
		
		if (isset($this->session->data['success_update_form'])) {
			$this->data['success_update_form'] = $this->session->data['success_update_form'];

			unset($this->session->data['success_update_form']);
		} else {
			$this->data['success_update_form'] = '';
		}
		
		
		$url2 = "";
		
		//$this->data['config_tag_status'] = $this->customer->isTag();
		
		if ($this->request->get['task_id'] != null && $this->request->get['task_id'] != "") {
			$url2 .= '&task_id=' . $this->request->get['task_id'];
		}
		
		if ($this->request->get['updatenotes_id'] != null && $this->request->get['updatenotes_id'] != "") {
			$url2 .= '&updatenotes_id=' . $this->request->get['updatenotes_id'];
		}
		
		if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
			$url2 .= '&searchdate=' . $this->request->get['searchdate'];
		}
		
		
			$config_admin_limit1 = $this->config->get('config_front_limit');
			if($config_admin_limit1 != null && $config_admin_limit1 != ""){
				$config_admin_limit = $config_admin_limit1;
			}else{
				$config_admin_limit = "50";
			}
			
			$timezone_name = $this->customer->isTimezone();
			date_default_timezone_set($timezone_name);
			
			$data = array(
				'searchdate' => date('m-d-Y'),
				'searchdate_app' => '1',
				'facilities_id' => $this->customer->getId(),
			);
	 
			$this->load->model('notes/notes');
			$notes_total = $this->model_notes_notes->getTotalnotess($data);
			$pagenumber_all = ceil($notes_total/$config_admin_limit);
		
			if ($pagenumber_all != null && $pagenumber_all != "") {
				if($pagenumber_all > 1){
				$url2 .= '&page=' . $pagenumber_all;
				}
			}
			
			$this->data['config_tag_status'] = $this->customer->isTag();
		
		
		
		//var_dump($form_info['ssincedentform']);
		
		$notedetails = $this->model_notes_notes->getnotes($response['Item']['notes_id']['N']);
		$this->data['text_cut_value'] = $notedetails['text_color_cut'];
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->request->post['ssincedentform'])) {
			$this->data['ssincedentform'] = $this->request->post['ssincedentform'];
		} elseif (!empty($form_info)) {
			$this->data['ssincedentform'] = $form_info['ssincedentform'];
		} else {
			$this->data['ssincedentform'] = '';
		}
		
		
		$this->data['incidentform_id'] = $this->request->get['incidentform_id'];
		if($this->request->get['updatenotes_id'] != null && $this->request->get['updatenotes_id'] != ""){
			$this->data['updatenotes_id'] = $this->request->get['updatenotes_id'];
		}
		
		
		$this->data['redirect_url2'] = str_replace('&amp;', '&',$this->url->link('notes/noteform/taskinsert3', '' . $url2, 'SSL'));
		
		$this->template = $this->config->get('config_template') . '/template/notes/insert_form_notes.php';
		$this->response->setOutput($this->render());
	}
	
	public function taskinsert3() {
		$this->language->load('notes/notes');

		$this->document->setTitle($this->language->get('heading_title'));
		$this->data['form_outputkey'] = $this->formkey->outputKey();
		$this->load->model('notes/notes');

		if (($this->request->post['form_submit'] == '1') && $this->validateForm23()) {
			// var_dump($this->request->post);
			//$this->model_notes_notes->updatenotes($this->request->post, $this->customer->getId(), $this->session->data['notes_id']);
			
			//$this->session->data['ssincedentform']['notes_id'] = (string) $this->request->get['updatenotes_id'];
			//$this->session->data['ssincedentform']['randID'] = (string) rand ();
			
			
			//$this->session->data['ssincedentform']['notes_pin'] = $this->request->post['notes_pin'];
			//$this->session->data['ssincedentform']['user_id'] = $this->request->post['user_id'];
			//$this->session->data['ssincedentform']['signature'] = $this->request->post['imgOutput'];
			
			$timezone_name = $this->customer->isTimezone();
			
			$timeZone = date_default_timezone_set($timezone_name);
			
			//$noteDate = (string) date('Y-m-d H:i:s', strtotime('now'));
			
			
			
			//$this->session->data['ssincedentform']['form_date_added'] = (string) $noteDate;
			
			$incidentform_id = (string) rand ();
			$facilities_id = (string) $this->customer->getId();
			/*
			var_dump($this->session->data['ssincedentform'] );
			echo "<hr>";*/
			require_once(DIR_APPLICATION . 'aws/getItem.php');
			
			
			if($this->request->get['task_id'] !=null && $this->request->get['task_id']!=""){
					$this->load->model('createtask/createtask');
					if($this->request->post['comments'] != null && $this->request->post['comments']){
						$this->request->post['comments'] = $this->request->post['comments'];
					}else{
						$this->request->post['comments'] =  '';
					}
					
					//var_dump($this->request->post['comments']);
					//var_dump($checklist_status);
					
					
					
					$result2 = $this->model_createtask_createtask->getStrikedatadetails($this->request->get['task_id']);
					
					$facilities_id = $result2['facilityId'];
					
					$notesId = $this->model_createtask_createtask->inserttask($result2, $this->request->post, $facilities_id);
					
					$this->model_createtask_createtask->updatetaskNote($this->request->get['task_id']);
					$this->model_createtask_createtask->deteteIncomTask($facilities_id);
					//var_dump($notesId);
					
					$ttstatus = "1";
					$timezone_name = $this->customer->isTimezone();
					$timeZone = date_default_timezone_set($timezone_name);
					$update_date = date('Y-m-d H:i:s', strtotime('now'));
					$this->model_createtask_createtask->updateForm($notesId, $checklist_status, $ttstatus,$update_date);
					
					//die;
			}
			
			// var_dump($notesId );
			
			
			$timezone_name = $this->customer->isTimezone();
			$timeZone = date_default_timezone_set($timezone_name);
			$noteDate = date('Y-m-d H:i:s', strtotime('now'));
			$date_added = (string) $noteDate;
			
			$tableName = DYNAMODBINCIDENT;
			
			$result  = $dynamodb->putItem([
				'TableName' => $tableName,
				'Item' => [
					
					'incidentform_id'       => ['N' => $incidentform_id ], 
					'program_code'       => ['S' => empty($this->session->data['ssincedentform']['program_code']) ? '&nbsp;': $this->session->data['ssincedentform']['program_code'] ],
					'program_name'    => ['S'   => empty($this->session->data['ssincedentform']['program_name']) ? '&nbsp;': $this->session->data['ssincedentform']['program_name'] ],
					'incident_number'     => ['S'      => empty($this->session->data['ssincedentform']['incident_number']) ? '&nbsp;': $this->session->data['ssincedentform']['incident_number'] ],
					'duty_officer'    => ['S'      => empty($this->session->data['ssincedentform']['duty_officer']) ? '&nbsp;': $this->session->data['ssincedentform']['duty_officer'] ],
					'report_date'    => ['S'      => empty($this->session->data['ssincedentform']['report_date']) ? '&nbsp;': $this->session->data['ssincedentform']['report_date'] ],
					'report_time'    => ['S'      => empty($this->session->data['ssincedentform']['report_time']) ? '&nbsp;': $this->session->data['ssincedentform']['report_time'] ],
					'place_of_occurrence'    => ['S'      => empty($this->session->data['ssincedentform']['place_of_occurrence']) ? '&nbsp;': $this->session->data['ssincedentform']['place_of_occurrence'] ],
					'restraint_involved'    => ['S'      => empty($this->session->data['ssincedentform']['restraint_involved']) ? '&nbsp;': $this->session->data['ssincedentform']['restraint_involved'] ],
					'staff_par_certified'    => ['S'      => empty($this->session->data['ssincedentform']['staff_par_certified']) ? '&nbsp;': $this->session->data['ssincedentform']['staff_par_certified'] ],
					'staff_to_youth_ratio'    => ['S'      => empty($this->session->data['ssincedentform']['staff_to_youth_ratio']) ? '&nbsp;': $this->session->data['ssincedentform']['staff_to_youth_ratio'] ],
					'investigation_initiated'    => ['S'      => empty($this->session->data['ssincedentform']['investigation_initiated']) ? '&nbsp;': $this->session->data['ssincedentform']['investigation_initiated'] ],
					'incident_category'    => ['S'      => empty($this->session->data['ssincedentform']['incident_category']) ? '&nbsp;': $this->session->data['ssincedentform']['incident_category'] ],
					'background_information'    => ['S'      => empty($this->session->data['ssincedentform']['background_information']) ? '&nbsp;': $this->session->data['ssincedentform']['background_information'] ],
					'immediate_action_taken'    => ['S'      => empty($this->session->data['ssincedentform']['immediate_action_taken']) ? '&nbsp;': $this->session->data['ssincedentform']['immediate_action_taken'] ],
					'incident_date'    => ['S'      => empty($this->session->data['ssincedentform']['incident_date']) ? '&nbsp;': $this->session->data['ssincedentform']['incident_date'] ],
					'incident_time'    => ['S'      => empty($this->session->data['ssincedentform']['incident_time']) ? '&nbsp;': $this->session->data['ssincedentform']['incident_time'] ],
					'notes_id'    => ['N'      => (string)$notesId ],
					'region'    => ['S'      => empty($this->session->data['ssincedentform']['region']) ? '&nbsp;': $this->session->data['ssincedentform']['region'] ],
					'emp_tag_id'    => ['S'      => empty($this->session->data['ssincedentform']['emp_tag_id']) ? '&nbsp;': $this->session->data['ssincedentform']['emp_tag_id'] ],
					'upload_file'    => ['S'      => empty($this->session->data['ssincedentform']['upload_file']) ? '&nbsp;': $this->session->data['ssincedentform']['upload_file'] ],		
					'notes_pin'    => ['S'      => empty($this->session->data['ssincedentform']['notes_pin']) ? '&nbsp;': $this->session->data['ssincedentform']['notes_pin'] ],
					'user_id'    => ['S'      => empty($this->session->data['ssincedentform']['user_id']) ? '&nbsp;': $this->session->data['ssincedentform']['user_id'] ],
					'signature'    => ['S'      => empty($this->session->data['ssincedentform']['signature']) ? '&nbsp;': $this->session->data['ssincedentform']['signature'] ],
					'form_date_added'    => ['S'      => empty($this->session->data['ssincedentform']['form_date_added']) ? '&nbsp;': $this->session->data['ssincedentform']['form_date_added'] ],
					'date_added'    => ['S'      => empty($noteDate) ? '&nbsp;': $noteDate ],
					'facilities_id'    => ['S'      => empty($facilities_id) ? '0': $facilities_id ],
					'form_signature'    => ['S'      => empty($this->session->data['ssincedentform']['form_signature']) ? '&nbsp;': $this->session->data['ssincedentform']['form_signature'] ],
				   
				 ]
				 
				
			]);
			
			$form_description = "";
			if($this->session->data['ssincedentform']['program_code'] != null && $this->session->data['ssincedentform']['program_code'] != ""){
				$form_description .= $this->session->data['ssincedentform']['program_code'] ." ";
			}
			
			if($this->session->data['ssincedentform']['program_name'] != null && $this->session->data['ssincedentform']['program_name'] != ""){
				$form_description .= $this->session->data['ssincedentform']['program_name'] ." ";
			}
			if($this->session->data['ssincedentform']['incident_number'] != null && $this->session->data['ssincedentform']['incident_number'] != ""){
				$form_description .= $this->session->data['ssincedentform']['incident_number'] ." ";
			}
			if($this->session->data['ssincedentform']['duty_officer'] != null && $this->session->data['ssincedentform']['duty_officer'] != ""){
				$form_description .= $this->session->data['ssincedentform']['duty_officer'] ." ";
			}
			if($this->session->data['ssincedentform']['place_of_occurrence'] != null && $this->session->data['ssincedentform']['place_of_occurrence'] != ""){
				$form_description .= $this->session->data['ssincedentform']['place_of_occurrence'] ." ";
			}
			if($this->session->data['ssincedentform']['restraint_involved'] != null && $this->session->data['ssincedentform']['restraint_involved'] != ""){
				$form_description .= $this->session->data['ssincedentform']['restraint_involved'] ." ";
			}
			if($this->session->data['ssincedentform']['staff_par_certified'] != null && $this->session->data['ssincedentform']['staff_par_certified'] != ""){
				$form_description .= $this->session->data['ssincedentform']['staff_par_certified'] ." ";
			}
			if($this->session->data['ssincedentform']['staff_to_youth_ratio'] != null && $this->session->data['ssincedentform']['staff_to_youth_ratio'] != ""){
				$form_description .= $this->session->data['ssincedentform']['staff_to_youth_ratio'] ." ";
			}
			if($this->session->data['ssincedentform']['investigation_initiated'] != null && $this->session->data['ssincedentform']['investigation_initiated'] != ""){
				$form_description .= $this->session->data['ssincedentform']['investigation_initiated'] ." ";
			}
			if($this->session->data['ssincedentform']['incident_category'] != null && $this->session->data['ssincedentform']['incident_category'] != ""){
				$form_description .= $this->session->data['ssincedentform']['incident_category'] ." ";
			}
			if($this->session->data['ssincedentform']['background_information'] != null && $this->session->data['ssincedentform']['background_information'] != ""){
				$form_description .= $this->session->data['ssincedentform']['background_information'] ." ";
			}
			if($this->session->data['ssincedentform']['immediate_action_taken'] != null && $this->session->data['ssincedentform']['immediate_action_taken'] != ""){
				$form_description .= $this->session->data['ssincedentform']['immediate_action_taken'] ." ";
			}
			if($this->session->data['ssincedentform']['region'] != null && $this->session->data['ssincedentform']['region'] != ""){
				$form_description .= $this->session->data['ssincedentform']['region'] ." ";
			}
			
			if($this->session->data['ssincedentform']['emp_tag_id'] != null && $this->session->data['ssincedentform']['emp_tag_id'] != ""){
				$form_description .= $this->session->data['ssincedentform']['emp_tag_id'] ." ";
			}
			
			/*
			$ifdata = array();
			$ifdata['incident_severity'] = $this->session->data['ssincedentform']['program_code'];
			$ifdata['keyword_search'] = $this->session->data['ssincedentform']['immediate_action_taken'];
			$ifdata['restraint_involved'] = $this->session->data['ssincedentform']['restraint_involved'];
			$ifdata['staff_par_certified'] = $this->session->data['ssincedentform']['staff_par_certified'];
			$ifdata['youth_ratio'] = $this->session->data['ssincedentform']['staff_to_youth_ratio'];
			$ifdata['investigation_initiated'] = $this->session->data['ssincedentform']['investigation_initiated'];
			$ifdata['facilities_id'] = $this->customer->getId();
			
			
			
			$this->load->model('notes/assessment');
			$assessment_info = $this->model_notes_assessment->getassessments($ifdata);
			//var_dump($assessment_info);
			
			if($assessment_info){
				$tigger_type = $assessment_info['tigger_type'];
				$assessment_id = $assessment_info['assessment_id'];
				
				$notes_id = $notesId;
				
				if($tigger_type == '1'){
					$highlighter_id = $assessment_info['highlighter_id'];
					$this->load->model('setting/highlighter');
					$highlighterData = $this->model_setting_highlighter->gethighlighter($highlighter_id);
					$highlighter_value = $highlighterData['highlighter_value'];
					
					if($highlighter_id == '21'){
						$highlighter = '0';
						$highlighter_value1 = '';
					}else{
						$highlighter = $highlighter_id;
						$highlighter_value1 = $highlighter_value;
					}
					
					$sql = "UPDATE `" . DB_PREFIX . "notes` SET highlighter_id = '".$highlighter."', highlighter_value = '" . $highlighter_value1 . "', assessment_id = '" . $assessment_id . "' WHERE notes_id = '" . (int)$notes_id . "' ";
					
					$this->db->query($sql);
				}
				
				if($tigger_type == '2'){
					$keyword_id = $assessment_info['keyword_id'];
					
					$this->load->model('setting/keywords');
					
					$keyword_info = $this->model_setting_keywords->getkeywordDetail($keyword_id);
					//var_dump($keyword_info);
					//echo "<hr>";
					
					$this->load->model('setting/image');
				
					$file16 = 'icon/'.$keyword_info['keyword_image'];

					$newfile84 = $this->model_setting_image->resize($file16, 50, 50);
					$newfile216 = DIR_IMAGE . $newfile84;
					$file124 = HTTP_SERVER . 'image/icon/'.$newfile84;
					$imageData132 = base64_encode(file_get_contents($newfile216));
					
					if($newfile84 != null && $newfile84 != ""){
						$keyword_icon = 'data:'.$this->mime_content_type($file124).';base64,'.$imageData132;
					}else{
						$keyword_icon = '';
					}
					$keyword_file = $keyword_info['keyword_image'];
					
					
					$keywordData2 = $this->model_setting_keywords->getkeywordDetaildesc($keyword_info['keyword_image']);
					
					//$notes_description2 = str_replace($keywordData2['keyword_name'],$keywordData2['keyword_name'], $noteDetails['notes_description']);
					
					$this->load->model('notes/notes');
					$noteDetails = $this->model_notes_notes->getnotes($notes_id);
					
					$notes_description2 =$keywordData2['keyword_name'] .' '. $noteDetails['notes_description'];
					
					
					$sql12 = "UPDATE `" . DB_PREFIX . "notes` SET notes_description = '" . $notes_description2 . "', keyword_file = '" .$this->db->escape($keyword_file). "', keyword_file_url = '" .$this->db->escape($keyword_icon). "', assessment_id = '" . $assessment_id . "' WHERE notes_id = '" . (int)$notes_id . "' ";
					$this->db->query($sql12);
					
				}
				
				if($tigger_type == '3'){
					$color_id = '#'.$assessment_info['color_id'];
					
					$sql12 = "UPDATE `" . DB_PREFIX . "notes` SET text_color = '" . $color_id . "', assessment_id = '" . $assessment_id . "' WHERE notes_id = '" . (int)$notes_id . "' ";
					$this->db->query($sql12);
				}
				
			
			}*/
			
			
			
			$fsql = "INSERT INTO `" . DB_PREFIX . "forms` SET notes_id = '" . $notesId . "',form_type_id = '" . $incidentform_id . "', form_type = '1', form_description = '" . $form_description . "', user_id = '', signature = '', notes_pin = '', form_date_added = '" . $noteDate . "', date_added = '" . $noteDate . "', incident_number = '" . $this->session->data['ssincedentform']['incident_number'] . "', form_signature = '" . $this->session->data['ssincedentform']['form_signature'] . "', facilities_id = '" . $this->customer->getId() . "', assessment_id = '" . $assessment_id . "' ";
		
			$this->db->query($fsql);
			
			if($this->request->post['emp_tag_id'] != null && $this->request->post['emp_tag_id'] != ""){
				$this->load->model('notes/notes');
				
				$timezone_name = $this->customer->isTimezone();
				date_default_timezone_set($timezone_name);
				$update_date = date('Y-m-d H:i:s', strtotime('now'));
				
				$this->model_notes_notes->updateNotesTag($this->request->post['emp_tag_id'], $notesId,$this->request->post['tags_id'], $update_date);
			}
			

			$this->session->data['success_add_form'] = $this->language->get('text_success');
			//$this->session->data['add_form'] = '1';

			unset($this->session->data['notesdatas']);
			unset($this->session->data['highlighter_id']);
			unset($this->session->data['notes_id']);
			unset($this->session->data['text_color_cut']);
			unset($this->session->data['text_color']);
			unset($this->session->data['note_date']);
			unset($this->session->data['notes_file']);
			unset($this->session->data['update_reminder']);
			unset($this->session->data['ssincedentform']);
			unset($this->session->data['ssbedcheckform']);
		
			unset($this->session->data['keyword_file']);
			//unset($this->session->data['pagenumber']);
			
			$url2 = "";
			if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
				$url2 = '&searchdate=' . $this->request->get['searchdate'];
			}
			/*if ($this->session->data['pagenumber'] != null && $this->session->data['pagenumber'] != "") {
				$url2. = '&page=' . $this->session->data['pagenumber'];
			}*/
			
			$this->redirect(str_replace('&amp;', '&', $this->url->link('notes/noteform/taskforminsert', '' . $url2, 'SSL')));
		}

		$this->data['entry_pin'] = $this->language->get('entry_pin');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['text_select'] = $this->language->get('text_select');

		$this->load->model('user/user');
		$this->data['users'] = $this->model_user_user->getUsersByFacility($this->customer->getId());

		$this->data['config_tag_status'] = $this->customer->isTag();
		
		$this->data['createtask'] = 1;
		
		$url2 = "";
		
		
		
		if ($this->request->get['updatenotes_id'] != null && $this->request->get['updatenotes_id'] != "") {
			$url2 .= '&updatenotes_id=' . $this->request->get['updatenotes_id'];
		}
		
		if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
			$url2 .= '&searchdate=' . $this->request->get['searchdate'];
		}
		
		if ($this->request->get['task_id'] != null && $this->request->get['task_id'] != "") {
			$url2 .= '&task_id=' . $this->request->get['task_id'];
		}
		
		
		$config_admin_limit1 = $this->config->get('config_front_limit');
			if($config_admin_limit1 != null && $config_admin_limit1 != ""){
				$config_admin_limit = $config_admin_limit1;
			}else{
				$config_admin_limit = "50";
			}
			
			$timezone_name = $this->customer->isTimezone();
			date_default_timezone_set($timezone_name);
			
			$data = array(
				'searchdate' => date('m-d-Y'),
				'searchdate_app' => '1',
				'facilities_id' => $this->customer->getId(),
			);
	 
			$this->load->model('notes/notes');
			$notes_total = $this->model_notes_notes->getTotalnotess($data);
			$pagenumber_all = ceil($notes_total/$config_admin_limit);
		
			if ($pagenumber_all != null && $pagenumber_all != "") {
				if($pagenumber_all > 1){
				$url2 .= '&page=' . $pagenumber_all;
				}
			}
		 
		$this->data['action2'] = str_replace('&amp;', '&',$this->url->link('notes/noteform/taskinsert3', '' . $url2, 'SSL'));
		$this->data['redirect_url'] = str_replace('&amp;', '&',$this->url->link('notes/notes/insert', '' . $url2, 'SSL'));
		
		if (isset($this->session->data['pagenumber'])) {
			$this->data['pagenumber'] = $this->session->data['pagenumber'];
		} else {
			$this->data['pagenumber'] = '';
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


		if (isset($this->error['select_one'])) {
			$this->data['error_select_one'] = $this->error['select_one'];
		} else {
			$this->data['error_select_one'] = '';
		}
		
		if (isset($this->error['notes_pin'])) {
			$this->data['error_notes_pin'] = $this->error['notes_pin'];
		} else {
			$this->data['error_notes_pin'] = '';
		}

		if (isset($this->error['highlighter_id'])) {
			$this->data['error_highlighter_id'] = $this->error['highlighter_id'];
		} else {
			$this->data['error_highlighter_id'] = '';
		}

		if (isset($this->error['user_id'])) {
			$this->data['error_user_id'] = $this->error['user_id'];
		} else {
			$this->data['error_user_id'] = '';
		}
		
		if (isset($this->request->post['select_one'])) {
			$this->data['select_one'] = $this->request->post['select_one'];
		}  else {
			if($this->config->get('config_default_sign') != null && $this->config->get('config_default_sign') != ""){
				$config_default_sign = '1';//$this->config->get('config_default_sign');
			}else{
				$config_default_sign = '2';
			}
			$this->data['select_one'] = $config_default_sign;
		}

		if($this->config->get('config_default_sign') != null && $this->config->get('config_default_sign') != ""){
			$this->data['default_sign'] = '1';//$this->config->get('config_default_sign');
		}else{
			$this->data['default_sign'] = '2';
		}

		if (isset($this->request->post['notes_pin'])) {
			$this->data['notes_pin'] = $this->request->post['notes_pin'];
		} elseif (!empty($notes_info)) {
			$this->data['notes_pin'] = $notes_info['notes_pin'];
		} else {
			$this->data['notes_pin'] = '';
		}

		if (isset($this->request->post['user_id'])) {
			$this->data['user_id'] = $this->request->post['user_id'];
		} elseif (!empty($notes_info)) {
			$this->data['user_id'] = $notes_info['user_id'];
		} else {
			$this->data['user_id'] = '';
		}
		
		if (isset($this->request->post['emp_tag_id'])) {
			$this->data['emp_tag_id'] = $this->request->post['emp_tag_id'];
		} elseif (!empty($notes_info)) {
			$this->data['emp_tag_id'] = $notes_info['emp_tag_id'];
		} else {
			$this->data['emp_tag_id'] = '';
		}
		
		if (isset($this->request->post['tags_id'])) {
			$this->data['tags_id'] = $this->request->post['tags_id'];
		} elseif (!empty($notes_info)) {
			$this->data['tags_id'] = $notes_info['tags_id'];
		} else {
			$this->data['tags_id'] = '';
		}
		
		if (isset($this->request->post['emp_tag_id_2'])) {
			$this->data['emp_tag_id_2'] = $this->request->post['emp_tag_id_2'];
		} else {
			$this->data['emp_tag_id_2'] = '';
		}


		$this->template = $this->config->get('config_template') . '/template/notes/notes_form2.php';

		$this->response->setOutput($this->render());
			
	}
	
	public function mime_content_type($filename) {



        $mime_types = array(



            'txt' => 'text/plain',

            'htm' => 'text/html',

            'html' => 'text/html',

            'php' => 'text/html',

            'css' => 'text/css',

            'js' => 'application/javascript',

            'json' => 'application/json',

            'xml' => 'application/xml',

            'swf' => 'application/x-shockwave-flash',

            'flv' => 'video/x-flv',



            // images

            'png' => 'image/png',

            'jpe' => 'image/jpeg',

            'jpeg' => 'image/jpeg',

            'jpg' => 'image/jpeg',

            'gif' => 'image/gif',

            'bmp' => 'image/bmp',

            'ico' => 'image/vnd.microsoft.icon',

            'tiff' => 'image/tiff',

            'tif' => 'image/tiff',

            'svg' => 'image/svg+xml',

            'svgz' => 'image/svg+xml',



            // archives

            'zip' => 'application/zip',

            'rar' => 'application/x-rar-compressed',

            'exe' => 'application/x-msdownload',

            'msi' => 'application/x-msdownload',

            'cab' => 'application/vnd.ms-cab-compressed',



            // audio/video

            'mp3' => 'audio/mpeg',

            'qt' => 'video/quicktime',

            'mov' => 'video/quicktime',



            // adobe

            'pdf' => 'application/pdf',

            'psd' => 'image/vnd.adobe.photoshop',

            'ai' => 'application/postscript',

            'eps' => 'application/postscript',

            'ps' => 'application/postscript',



            // ms office

            'doc' => 'application/msword',

            'rtf' => 'application/rtf',

            'xls' => 'application/vnd.ms-excel',

            'ppt' => 'application/vnd.ms-powerpoint',



            // open office

            'odt' => 'application/vnd.oasis.opendocument.text',

            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',

        );



        $ext = strtolower(array_pop(explode('.',$filename)));

        if (array_key_exists($ext, $mime_types)) {

            return $mime_types[$ext];

        }

        elseif (function_exists('finfo_open')) {

            $finfo = finfo_open(FILEINFO_MIME);

            $mimetype = finfo_file($finfo, $filename);

            finfo_close($finfo);

            return $mimetype;

        }

        else {

            return 'application/octet-stream';

        }

    }
}

?>