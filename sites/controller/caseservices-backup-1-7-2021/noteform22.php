<?php
class Controllerservicesnoteform extends Controller {
private $error = array();

	public function forminsert(){
		
		if (($this->request->post['form_submit'] == '1') && $this->validateForm2()) {
			
			//var_dump($this->request->get['updatenotes_id']);
			
			//die;
			
			if($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != ""){
				//$this->session->data['ssincedentform'] = $this->request->post['ssincedentform'];
					$this->load->model('facilities/facilities');
				
					$facilities_info = $this->model_facilities_facilities->getfacilities($this->request->post['ssincedentform']['facilities_id']);
					
					
					$this->load->model('setting/timezone');
					
					$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
					
					date_default_timezone_set($timezone_info['timezone_value']);
					
					$noteDate = date('Y-m-d H:i:s', strtotime('now'));
					$date_added = (string) $noteDate;
					
					$noteDate = date('Y-m-d H:i:s', strtotime('now'));
				
					$form_date_added = (string) $noteDate;
					
					$notes_id = (string) $this->request->get['notes_id'];
					$incidentform_id = (string) rand ();
					
					require_once(DIR_APPLICATION . 'aws/awsconfig.php');   
					$tableName = 'incidentform';
					
					$result  = $dynamodb->putItem([
						'TableName' => $tableName,
						'Item' => [
							
							'incidentform_id'       => ['N' => $incidentform_id ], 
							'program_code'       => ['S' => empty($this->request->post['ssincedentform']['program_code']) ? '&nbsp;': $this->request->post['ssincedentform']['program_code'] ],
							'program_name'    => ['S'   => empty($this->request->post['ssincedentform']['program_name']) ? '&nbsp;': $this->request->post['ssincedentform']['program_name'] ],
							'incident_number'     => ['S'      => empty($this->request->post['ssincedentform']['incident_number']) ? '&nbsp;': $this->request->post['ssincedentform']['incident_number'] ],
							'duty_officer'    => ['S'      => empty($this->request->post['ssincedentform']['duty_officer']) ? '&nbsp;': $this->request->post['ssincedentform']['duty_officer'] ],
							'report_date'    => ['S'      => empty($this->request->post['ssincedentform']['report_date']) ? '&nbsp;': $this->request->post['ssincedentform']['report_date'] ],
							'report_time'    => ['S'      => empty($this->request->post['ssincedentform']['report_time']) ? '&nbsp;': $this->request->post['ssincedentform']['report_time'] ],
							'place_of_occurrence'    => ['S'      => empty($this->request->post['ssincedentform']['place_of_occurrence']) ? '&nbsp;': $this->request->post['ssincedentform']['place_of_occurrence'] ],
							'restraint_involved'    => ['S'      => empty($this->request->post['ssincedentform']['restraint_involved']) ? '&nbsp;': $this->request->post['ssincedentform']['restraint_involved'] ],
							'staff_par_certified'    => ['S'      => empty($this->request->post['ssincedentform']['staff_par_certified']) ? '&nbsp;': $this->request->post['ssincedentform']['staff_par_certified'] ],
							'staff_to_youth_ratio'    => ['S'      => empty($this->request->post['ssincedentform']['staff_to_youth_ratio']) ? '&nbsp;': $this->request->post['ssincedentform']['staff_to_youth_ratio'] ],
							'investigation_initiated'    => ['S'      => empty($this->request->post['ssincedentform']['investigation_initiated']) ? '&nbsp;': $this->request->post['ssincedentform']['investigation_initiated'] ],
							'incident_category'    => ['S'      => empty($this->request->post['ssincedentform']['incident_category']) ? '&nbsp;': $this->request->post['ssincedentform']['incident_category'] ],
							'background_information'    => ['S'      => empty($this->request->post['ssincedentform']['background_information']) ? '&nbsp;': $this->request->post['ssincedentform']['background_information'] ],
							'immediate_action_taken'    => ['S'      => empty($this->request->post['ssincedentform']['immediate_action_taken']) ? '&nbsp;': $this->request->post['ssincedentform']['immediate_action_taken'] ],
							'incident_date'    => ['S'      => empty($this->request->post['ssincedentform']['incident_date']) ? '&nbsp;': $this->request->post['ssincedentform']['incident_date'] ],
							'incident_time'    => ['S'      => empty($this->request->post['ssincedentform']['incident_time']) ? '&nbsp;': $this->request->post['ssincedentform']['incident_time'] ],
							'notes_id'    => ['N'      => empty($notes_id) ? '0': $notes_id ],
							'region'    => ['S'      => empty($this->request->post['ssincedentform']['region']) ? '&nbsp;': $this->request->post['ssincedentform']['region'] ],
							'emp_tag_id'    => ['S'      => empty($this->request->post['ssincedentform']['emp_tag_id']) ? '&nbsp;': $this->request->post['ssincedentform']['emp_tag_id'] ],
							'upload_file'    => ['S'      => empty($this->request->post['ssincedentform']['upload_file']) ? '&nbsp;': $this->request->post['ssincedentform']['upload_file'] ],		
							'notes_pin'    => ['S'      => empty($this->request->post['ssincedentform']['notes_pin']) ? '&nbsp;': $this->request->post['ssincedentform']['notes_pin'] ],
							'user_id'    => ['S'      => empty($this->request->post['ssincedentform']['user_id']) ? '&nbsp;': $this->request->post['ssincedentform']['user_id'] ],
							'signature'    => ['S'      => empty($this->request->post['ssincedentform']['signature']) ? '&nbsp;': $this->request->post['ssincedentform']['signature'] ],
							'form_date_added'    => ['S'      => empty($this->request->post['ssincedentform']['form_date_added']) ? '&nbsp;': $this->request->post['ssincedentform']['form_date_added'] ],
							
						   'date_added'    => ['S'      => empty($date_added) ? '&nbsp;': $date_added ],
							'facilities_id'    => ['S'      => empty($this->request->post['ssincedentform']['facilities_id']) ? '0': $this->request->post['ssincedentform']['facilities_id'] ]
						]
						
					]);
				
				$this->session->data['success2'] = 'Form Created successfully!';
				
			}else{
				//var_dump($this->request->post['ssincedentform']);
				//die;
				//$this->session->data['ssincedentform'] = $this->request->post['ssincedentform'];
				//$this->session->data['success'] = 'Form Created successfully!';
				
				if($this->request->post['ssincedentform'] != null && $this->request->post['ssincedentform'] != ""){
					$notes_id = (string) $notes_id;
					$incidentform_id = (string) rand ();
					
					require_once(DIR_APPLICATION . 'aws/awsconfig.php');   
					$tableName = 'incidentform';
					
					$result  = $dynamodb->putItem([
						'TableName' => $tableName,
						'Item' => [
							
							'incidentform_id'       => ['N' => $incidentform_id ], 
							'program_code'       => ['S' => empty($this->request->post['ssincedentform']['program_code']) ? '&nbsp;': $this->request->post['ssincedentform']['program_code'] ],
							'program_name'    => ['S'   => empty($this->request->post['ssincedentform']['program_name']) ? '&nbsp;': $this->request->post['ssincedentform']['program_name'] ],
							'incident_number'     => ['S'      => empty($this->request->post['ssincedentform']['incident_number']) ? '&nbsp;': $this->request->post['ssincedentform']['incident_number'] ],
							'duty_officer'    => ['S'      => empty($this->request->post['ssincedentform']['duty_officer']) ? '&nbsp;': $this->request->post['ssincedentform']['duty_officer'] ],
							'report_date'    => ['S'      => empty($this->request->post['ssincedentform']['report_date']) ? '&nbsp;': $this->request->post['ssincedentform']['report_date'] ],
							'report_time'    => ['S'      => empty($this->request->post['ssincedentform']['report_time']) ? '&nbsp;': $this->request->post['ssincedentform']['report_time'] ],
							'place_of_occurrence'    => ['S'      => empty($this->request->post['ssincedentform']['place_of_occurrence']) ? '&nbsp;': $this->request->post['ssincedentform']['place_of_occurrence'] ],
							'restraint_involved'    => ['S'      => empty($this->request->post['ssincedentform']['restraint_involved']) ? '&nbsp;': $this->request->post['ssincedentform']['restraint_involved'] ],
							'staff_par_certified'    => ['S'      => empty($this->request->post['ssincedentform']['staff_par_certified']) ? '&nbsp;': $this->request->post['ssincedentform']['staff_par_certified'] ],
							'staff_to_youth_ratio'    => ['S'      => empty($this->request->post['ssincedentform']['staff_to_youth_ratio']) ? '&nbsp;': $this->request->post['ssincedentform']['staff_to_youth_ratio'] ],
							'investigation_initiated'    => ['S'      => empty($this->request->post['ssincedentform']['investigation_initiated']) ? '&nbsp;': $this->request->post['ssincedentform']['investigation_initiated'] ],
							'incident_category'    => ['S'      => empty($this->request->post['ssincedentform']['incident_category']) ? '&nbsp;': $this->request->post['ssincedentform']['incident_category'] ],
							'background_information'    => ['S'      => empty($this->request->post['ssincedentform']['background_information']) ? '&nbsp;': $this->request->post['ssincedentform']['background_information'] ],
							'immediate_action_taken'    => ['S'      => empty($this->request->post['ssincedentform']['immediate_action_taken']) ? '&nbsp;': $this->request->post['ssincedentform']['immediate_action_taken'] ],
							'incident_date'    => ['S'      => empty($this->request->post['ssincedentform']['incident_date']) ? '&nbsp;': $this->request->post['ssincedentform']['incident_date'] ],
							'incident_time'    => ['S'      => empty($this->request->post['ssincedentform']['incident_time']) ? '&nbsp;': $this->request->post['ssincedentform']['incident_time'] ],
							'notes_id'    => ['N'      => empty($notes_id) ? '0': $notes_id ],
							'region'    => ['S'      => empty($this->request->post['ssincedentform']['region']) ? '&nbsp;': $this->request->post['ssincedentform']['region'] ],
							'emp_tag_id'    => ['S'      => empty($this->request->post['ssincedentform']['emp_tag_id']) ? '&nbsp;': $this->request->post['ssincedentform']['emp_tag_id'] ],
							'upload_file'    => ['S'      => empty($this->request->post['ssincedentform']['upload_file']) ? '&nbsp;': $this->request->post['ssincedentform']['upload_file'] ],		
							'notes_pin'    => ['S'      => empty($this->request->post['ssincedentform']['notes_pin']) ? '&nbsp;': $this->request->post['ssincedentform']['notes_pin'] ],
							'user_id'    => ['S'      => empty($this->request->post['ssincedentform']['user_id']) ? '&nbsp;': $this->request->post['ssincedentform']['user_id'] ],
							'signature'    => ['S'      => empty($this->request->post['ssincedentform']['signature']) ? '&nbsp;': $this->request->post['ssincedentform']['signature'] ],
							'form_date_added'    => ['S'      => empty($this->request->post['ssincedentform']['form_date_added']) ? '&nbsp;': $this->request->post['ssincedentform']['form_date_added'] ],
							
						   
						]
						
					]);
					
					$url2 = "";
					$url2 = '&incidentform_id=' . $incidentform_id;
					$this->redirect($this->url->link('services/noteform/jsonForm', '' . $url2, 'SSL'));
					
					/*$this->data['facilitiess'][] = array(
							'warning'  => '1',
							'incidentform_id'  => $incidentform_id,
						);
						$error = true;
						
					
					$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
					$this->response->setOutput(json_encode($value));
					*/
				}
				
			}
		}
		
		
		if ($this->request->get['add_form'] == "1") {
			$url2 = "";
			$url2 = '&incidentform_id=' . $this->request->get['incidentform_id'];
			var_dump($url2);
			$this->redirect($this->url->link('services/noteform/jsonForm', '' . $url2, 'SSL'));
		}
		
		//
		
		
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
		
		
		
		if ($incidentform_id != null && $incidentform_id != "") {
			$url2 .= '&incidentform_id=' . $incidentform_id;
		}
		
		if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
			$url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
		}
		
		if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
			$url2 .= '&notes_id=' . $this->request->get['notes_id'];
		}
			
		
		if (!isset($this->request->get['incidentform_id'])) {
			$this->data['action2'] = $this->url->link('services/noteform/forminsert', '' . $url2, 'SSL');
		} else {
			
			$url2 .= '&incidentform_id=' . $this->request->get['incidentform_id'];
			
			
			
			$this->data['action2'] = $this->url->link('services/noteform/update', '' . $url2, 'SSL');
		
			$notesID = (string) $this->request->get['incidentform_id'];
			
			require_once(DIR_APPLICATION . 'aws/getItem.php');
					
					
			$response = $dynamodb->getItem([ 
				'TableName' => 'incidentform',
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
		}
		
		//var_dump($form_info['ssincedentform']);
		
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
		
		
		if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
			$this->data['facilities_id'] = $this->request->get['facilities_id'];
		}
		
		if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
			$this->data['notes_id'] = $this->request->get['notes_id'];
		}
		
		$url2 = "";
		
		if ($this->request->get['updatenotes_id'] != null && $this->request->get['updatenotes_id'] != "") {
			$url2 .= '&updatenotes_id=' . $this->request->get['updatenotes_id'];
		}
		
		if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
			$url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
		}
		
		if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
			$url2 .= '&notes_id=' . $this->request->get['notes_id'];
		}
		
		if ($this->request->get['incidentform_id'] != null && $this->request->get['incidentform_id'] != "") {
			$url2 .= '&incidentform_id=' . $this->request->get['incidentform_id'];
		}
		
		if ($incidentform_id != null && $incidentform_id != "") {
			$url2 .= '&incidentform_id=' . $incidentform_id;
		}
		 
 		
		$this->data['redirect_url'] = str_replace('&amp;', '&',$this->url->link('services/noteform/insert2', '' . $url2, 'SSL'));
		$this->data['redirect_url2'] = str_replace('&amp;', '&',$this->url->link('services/noteform/insert3', '' . $url2, 'SSL'));
		
		$this->template = $this->config->get('config_template') . '/template/notes/insert_form_notes.tpl';
		$this->response->setOutput($this->render());
		
		
	
	}
	
	
	protected function validateForm2() {
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	
	protected function validateFormu() {

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	
	public function update(){
		
		if (($this->request->post['form_submit'] == '1') && $this->validateFormu()) {
			
			if($this->request->get['incidentform_id'] != null && $this->request->get['incidentform_id'] != ""){
				
				//$this->session->data['ssincedentform'] = $this->request->post['ssincedentform'];
				$incidentform_id = (string) $this->request->get['incidentform_id'];
				//$this->session->data['ssincedentform']['randID'] = (string) rand ();
				 
				//require_once(DIR_APPLICATION . 'aws/updatedb.php');
				/*
				
				*/
				
				//$notes_id = (string) $notes_id;
				
				$this->load->model('facilities/facilities');
				
				$facilities_info = $this->model_facilities_facilities->getfacilities($this->request->post['ssincedentform']['facilities_id']);
				
				
				$this->load->model('setting/timezone');
				
				$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
				
				date_default_timezone_set($timezone_info['timezone_value']);
				
				$noteDate = date('Y-m-d H:i:s', strtotime('now'));
				$date_added = (string) $noteDate;
				
				//$incidentform_id = (string) $this->request->get['incidentform_id'];
					
					require_once(DIR_APPLICATION . 'aws/awsconfig.php');   
					$tableName = 'incidentform';
					
					$result  = $dynamodb->putItem([
						'TableName' => $tableName,
						'Item' => [
							
							'incidentform_id'       => ['N' => $incidentform_id ], 
							'program_code'       => ['S' => empty($this->request->post['ssincedentform']['program_code']) ? '&nbsp;': $this->request->post['ssincedentform']['program_code'] ],
							'program_name'    => ['S'   => empty($this->request->post['ssincedentform']['program_name']) ? '&nbsp;': $this->request->post['ssincedentform']['program_name'] ],
							'incident_number'     => ['S'      => empty($this->request->post['ssincedentform']['incident_number']) ? '&nbsp;': $this->request->post['ssincedentform']['incident_number'] ],
							'duty_officer'    => ['S'      => empty($this->request->post['ssincedentform']['duty_officer']) ? '&nbsp;': $this->request->post['ssincedentform']['duty_officer'] ],
							'report_date'    => ['S'      => empty($this->request->post['ssincedentform']['report_date']) ? '&nbsp;': $this->request->post['ssincedentform']['report_date'] ],
							'report_time'    => ['S'      => empty($this->request->post['ssincedentform']['report_time']) ? '&nbsp;': $this->request->post['ssincedentform']['report_time'] ],
							'place_of_occurrence'    => ['S'      => empty($this->request->post['ssincedentform']['place_of_occurrence']) ? '&nbsp;': $this->request->post['ssincedentform']['place_of_occurrence'] ],
							'restraint_involved'    => ['S'      => empty($this->request->post['ssincedentform']['restraint_involved']) ? '&nbsp;': $this->request->post['ssincedentform']['restraint_involved'] ],
							'staff_par_certified'    => ['S'      => empty($this->request->post['ssincedentform']['staff_par_certified']) ? '&nbsp;': $this->request->post['ssincedentform']['staff_par_certified'] ],
							'staff_to_youth_ratio'    => ['S'      => empty($this->request->post['ssincedentform']['staff_to_youth_ratio']) ? '&nbsp;': $this->request->post['ssincedentform']['staff_to_youth_ratio'] ],
							'investigation_initiated'    => ['S'      => empty($this->request->post['ssincedentform']['investigation_initiated']) ? '&nbsp;': $this->request->post['ssincedentform']['investigation_initiated'] ],
							'incident_category'    => ['S'      => empty($this->request->post['ssincedentform']['incident_category']) ? '&nbsp;': $this->request->post['ssincedentform']['incident_category'] ],
							'background_information'    => ['S'      => empty($this->request->post['ssincedentform']['background_information']) ? '&nbsp;': $this->request->post['ssincedentform']['background_information'] ],
							'immediate_action_taken'    => ['S'      => empty($this->request->post['ssincedentform']['immediate_action_taken']) ? '&nbsp;': $this->request->post['ssincedentform']['immediate_action_taken'] ],
							'incident_date'    => ['S'      => empty($this->request->post['ssincedentform']['incident_date']) ? '&nbsp;': $this->request->post['ssincedentform']['incident_date'] ],
							'incident_time'    => ['S'      => empty($this->request->post['ssincedentform']['incident_time']) ? '&nbsp;': $this->request->post['ssincedentform']['incident_time'] ],
							 'notes_id'    => ['N'      => (string) $this->request->get['notes_id'] ],
							'region'    => ['S'      => empty($this->request->post['ssincedentform']['region']) ? '&nbsp;': $this->request->post['ssincedentform']['region'] ],
							'emp_tag_id'    => ['S'      => empty($this->request->post['ssincedentform']['emp_tag_id']) ? '&nbsp;': $this->request->post['ssincedentform']['emp_tag_id'] ],
							'upload_file'    => ['S'      => empty($this->request->post['ssincedentform']['upload_file']) ? '&nbsp;': $this->request->post['ssincedentform']['upload_file'] ],		
							'notes_pin'    => ['S'      => empty($this->request->post['ssincedentform']['notes_pin']) ? '&nbsp;': $this->request->post['ssincedentform']['notes_pin'] ],
							'user_id'    => ['S'      => empty($this->request->post['ssincedentform']['user_id']) ? '&nbsp;': $this->request->post['ssincedentform']['user_id'] ],
							'signature'    => ['S'      => empty($this->request->post['ssincedentform']['signature']) ? '&nbsp;': $this->request->post['ssincedentform']['signature'] ],
							'form_date_added'    => ['S'      => empty($this->request->post['ssincedentform']['form_date_added']) ? '&nbsp;': $this->request->post['ssincedentform']['form_date_added'] ],
							
							'date_added'    => ['S'      => empty($date_added) ? '&nbsp;': $date_added ],
							'facilities_id'    => ['S'      => empty($this->request->post['ssincedentform']['facilities_id']) ? '0': $this->request->post['ssincedentform']['facilities_id'] ]
						   
						]
						
					]);
					
					
				
				$this->session->data['success3'] = 'Form updated successfully!';
				
			}
			
			
		
		//$this->data['config_tag_status'] = $this->customer->isTag();
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		$url2 = "";
		if ($this->request->get['updatenotes_id'] != null && $this->request->get['updatenotes_id'] != "") {
			$url2 .= '&updatenotes_id=' . $this->request->get['updatenotes_id'];
		}
		
		if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
			$url2 .= '&searchdate=' . $this->request->get['searchdate'];
		}
		
		if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
			$url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
		}
		
		if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
			$url2 .= '&notes_id=' . $this->request->get['notes_id'];
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
			
			$this->redirect($this->url->link('services/noteform/forminsert', ''. $url2, 'SSL'));

			
		}
	}
	
	
	public function insert2() {
		$this->language->load('notes/notes');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('notes/notes');
		
		if (($this->request->post['form_submit'] == '1') && $this->validateForm23()) {
			
				$this->load->model('facilities/facilities');
				
				$facilities_info = $this->model_facilities_facilities->getfacilities($this->request->get['facilities_id']);
				
				
				$this->load->model('setting/timezone');
				
				$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
				
				date_default_timezone_set($timezone_info['timezone_value']);
				
				$noteDate = date('Y-m-d H:i:s', strtotime('now'));
				$date_added = (string) $noteDate;
				
				$noteDate = date('Y-m-d H:i:s', strtotime('now'));
			
				$form_date_added = (string) $noteDate;
				
				$incidentform_id = (string) $this->request->get['incidentform_id'];
				
				
					require_once(DIR_APPLICATION . 'aws/awsconfig.php');   
					
					
					$response = $dynamodb->getItem([ 
							'TableName' => 'incidentform',
							'Key' => [
								'incidentform_id' => [ 'N' => $incidentform_id ] 
							]
						]);
					
					$tableName = 'incidentform';
					
					$result  = $dynamodb->putItem([
						'TableName' => $tableName,
						'Item' => [
							'incidentform_id'       => ['N' => $incidentform_id ], 
							
							'program_code'       => ['S' => $response['Item']['incident_number']['S'] ],
							'program_name'    => ['S'   => $response['Item']['program_name']['S'] ],
							'incident_number'     => ['S'      => $response['Item']['incident_number']['S'] ],
							'duty_officer'    => ['S'      => $response['Item']['duty_officer']['S'] ],
							'report_date'    => ['S'      => $response['Item']['report_date']['S'] ],
							'report_time'    => ['S'      => $response['Item']['report_time']['S'] ],
							'place_of_occurrence'    => ['S'      => $response['Item']['place_of_occurrence']['S'] ],
							'restraint_involved'    => ['S'      => $response['Item']['restraint_involved']['S'] ],
							'staff_par_certified'    => ['S'      => $response['Item']['staff_par_certified']['S'] ],
							'staff_to_youth_ratio'    => ['S'      => $response['Item']['staff_to_youth_ratio']['S'] ],
							'investigation_initiated'    => ['S'      => $response['Item']['investigation_initiated']['S'] ],
							'incident_category'    => ['S'      => $response['Item']['incident_category']['S'] ],
							'background_information'    => ['S'      => $response['Item']['background_information']['S'] ],
							'immediate_action_taken'    => ['S'      => $response['Item']['immediate_action_taken']['S'] ],
							'incident_date'    => ['S'      => $response['Item']['incident_date']['S'] ],
							'incident_time'    => ['S'      => $response['Item']['incident_time']['S'] ],
							 'notes_id'    => ['N'      => (string) $response['Item']['notes_id']['N'] ],
							'region'    => ['S'      => $response['Item']['region']['S'] ],
							'emp_tag_id'    => ['S'      => $response['Item']['emp_tag_id']['S'] ],
							'upload_file'    => ['S'      => $response['Item']['upload_file']['S'] ],	
							
							'notes_pin'    => ['S'      => empty($this->request->post['notes_pin']) ? '&nbsp;': $this->request->post['notes_pin'] ],
							'user_id'    => ['S'      => empty($this->request->post['user_id']) ? '&nbsp;': $this->request->post['user_id'] ],
							'signature'    => ['S'      => empty($this->request->post['imgOutput']) ? '&nbsp;': $this->request->post['imgOutput'] ],
							'form_date_added'    => ['S'      => empty($form_date_added) ? '&nbsp;': $form_date_added ],
							
							'date_added'    => ['S'      => $response['Item']['date_added']['S'] ],
							'facilities_id'    => ['S'      => (string)$response['Item']['facilities_id']['S'] ]
							 
						   
						]
						
					]); 
					
			
					//$url2 = "";
					//$url2 = '&incidentform_id=' . $incidentform_id;
					//$this->redirect($this->url->link('services/noteform/jsonForm', '' . $url2, 'SSL'));
					$url2 = "";
					if ($this->request->get['updatenotes_id'] != null && $this->request->get['updatenotes_id'] != "") {
						$url2 .= '&updatenotes_id=' . $this->request->get['updatenotes_id'];
					}
					
					if ($this->request->get['incidentform_id'] != null && $this->request->get['incidentform_id'] != "") {
						$url2 .= '&incidentform_id=' . $this->request->get['incidentform_id'];
					}
					
					if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
						$url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
					}
					
					if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
						$url2 .= '&notes_id=' . $this->request->get['notes_id'];
					}
					$url2 .= '&add_form=1'; 

					$this->session->data['success_update_form'] = $this->language->get('text_success');
			
			//$this->session->data['add_form'] = '1';
			$this->redirect(str_replace('&amp;', '&', $this->url->link('services/noteform/insert2', '' . $url2, 'SSL')));

			 
		}

		$this->data['entry_pin'] = $this->language->get('entry_pin');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['text_select'] = $this->language->get('text_select');

		$this->load->model('user/user');
		$this->data['users'] = $this->model_user_user->getUsersByFacility($this->customer->getId());

		
		$url2 = "";
		
		
		
		if ($this->request->get['updatenotes_id'] != null && $this->request->get['updatenotes_id'] != "") {
			$url2 .= '&updatenotes_id=' . $this->request->get['updatenotes_id'];
		}
		
		if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
			$url2 .= '&searchdate=' . $this->request->get['searchdate'];
		}
		
		if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
			$url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
		}
		
		if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
			$url2 .= '&notes_id=' . $this->request->get['notes_id'];
		}
		
		if ($this->request->get['incidentform_id'] != null && $this->request->get['incidentform_id'] != "") {
			$url2 .= '&incidentform_id=' . $this->request->get['incidentform_id'];
		}
		
		
		 
		$this->data['action2'] = str_replace('&amp;', '&',$this->url->link('services/noteform/insert2', '' . $url2, 'SSL'));
		$this->data['search_url'] = str_replace('&amp;', '&',$this->url->link('services/noteform/searchUser', '' . $url2, 'SSL'));
		
		$this->data['redirect_url'] = str_replace('&amp;', '&',$this->url->link('services/noteform/forminsert', '' . $url2, 'SSL'));
		
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
		
		if (isset($this->session->data['success_update_form'])) {
			$this->data['success_update_form'] = $this->session->data['success_update_form'];

			unset($this->session->data['success_update_form']);
		} else {
			$this->data['success_update_form'] = '';
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
				$config_default_sign = $this->config->get('config_default_sign');
			}else{
				$config_default_sign = '2';
			}
			$this->data['select_one'] = $config_default_sign;
		}

		if($this->config->get('config_default_sign') != null && $this->config->get('config_default_sign') != ""){
			$this->data['default_sign'] = $this->config->get('config_default_sign');
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


		$this->template = $this->config->get('config_template') . '/template/notes/notes_form2.tpl';

		$this->response->setOutput($this->render());
			
	}

	protected function validateForm23() {
			

		if ($this->request->post['user_id'] == '') {
			$this->error['user_id'] = $this->language->get('error_required');
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
				
				$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

				
				if (($this->request->post['notes_pin'] != $user_info['user_pin'])) {
					$this->error['warning'] = $this->language->get('error_exists');
				}
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

		if (($this->request->post['form_submit'] == '1') && $this->validateForm23()) {
			
				$this->load->model('facilities/facilities');
				
				$facilities_info = $this->model_facilities_facilities->getfacilities($this->request->get['facilities_id']);
				
				
				$this->load->model('setting/timezone');
				
				$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
				
				date_default_timezone_set($timezone_info['timezone_value']);
				
				$noteDate = date('Y-m-d H:i:s', strtotime('now'));
				$date_added = (string) $noteDate;
				
				$noteDate = date('Y-m-d H:i:s', strtotime('now'));
			
				$form_date_added = (string) $noteDate;
				
				$incidentform_id = (string) $this->request->get['incidentform_id'];
				
				
					require_once(DIR_APPLICATION . 'aws/awsconfig.php');   
					
					
					$response = $dynamodb->getItem([ 
							'TableName' => 'incidentform',
							'Key' => [
								'incidentform_id' => [ 'N' => $incidentform_id ] 
							]
						]);
					
					$tableName = 'incidentform';
					
					$result  = $dynamodb->putItem([
						'TableName' => $tableName,
						'Item' => [
							'incidentform_id'       => ['N' => $incidentform_id ], 
							
							'program_code'       => ['S' => $response['Item']['incident_number']['S'] ],
							'program_name'    => ['S'   => $response['Item']['program_name']['S'] ],
							'incident_number'     => ['S'      => $response['Item']['incident_number']['S'] ],
							'duty_officer'    => ['S'      => $response['Item']['duty_officer']['S'] ],
							'report_date'    => ['S'      => $response['Item']['report_date']['S'] ],
							'report_time'    => ['S'      => $response['Item']['report_time']['S'] ],
							'place_of_occurrence'    => ['S'      => $response['Item']['place_of_occurrence']['S'] ],
							'restraint_involved'    => ['S'      => $response['Item']['restraint_involved']['S'] ],
							'staff_par_certified'    => ['S'      => $response['Item']['staff_par_certified']['S'] ],
							'staff_to_youth_ratio'    => ['S'      => $response['Item']['staff_to_youth_ratio']['S'] ],
							'investigation_initiated'    => ['S'      => $response['Item']['investigation_initiated']['S'] ],
							'incident_category'    => ['S'      => $response['Item']['incident_category']['S'] ],
							'background_information'    => ['S'      => $response['Item']['background_information']['S'] ],
							'immediate_action_taken'    => ['S'      => $response['Item']['immediate_action_taken']['S'] ],
							'incident_date'    => ['S'      => $response['Item']['incident_date']['S'] ],
							'incident_time'    => ['S'      => $response['Item']['incident_time']['S'] ],
							 'notes_id'    => ['N'      => (string) $response['Item']['notes_id']['N'] ],
							'region'    => ['S'      => $response['Item']['region']['S'] ],
							'emp_tag_id'    => ['S'      => $response['Item']['emp_tag_id']['S'] ],
							'upload_file'    => ['S'      => $response['Item']['upload_file']['S'] ],	
							
							'notes_pin'    => ['S'      => empty($this->request->post['notes_pin']) ? '&nbsp;': $this->request->post['notes_pin'] ],
							'user_id'    => ['S'      => empty($this->request->post['user_id']) ? '&nbsp;': $this->request->post['user_id'] ],
							'signature'    => ['S'      => empty($this->request->post['imgOutput']) ? '&nbsp;': $this->request->post['imgOutput'] ],
							'form_date_added'    => ['S'      => empty($form_date_added) ? '&nbsp;': $form_date_added ],
							
							'date_added'    => ['S'      => $response['Item']['date_added']['S'] ],
							'facilities_id'    => ['S'      => (string) $response['Item']['facilities_id']['S'] ]
							 
						   
						]
						
					]); 
					
			
					//$url2 = "";
					//$url2 = '&incidentform_id=' . $incidentform_id;
					//$this->redirect($this->url->link('services/noteform/jsonForm', '' . $url2, 'SSL'));
			$url2 = "";
			if ($this->request->get['updatenotes_id'] != null && $this->request->get['updatenotes_id'] != "") {
				$url2 .= '&updatenotes_id=' . $this->request->get['updatenotes_id'];
			}
			
			if ($this->request->get['incidentform_id'] != null && $this->request->get['incidentform_id'] != "") {
				$url2 .= '&incidentform_id=' . $this->request->get['incidentform_id'];
			}
			
			if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
				$url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
			}
			
			if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
				$url2 .= '&notes_id=' . $this->request->get['notes_id'];
			}
			$url2 .= '&add_form=1'; 

			$this->session->data['success_update_form'] = $this->language->get('text_success');
			
			//$this->session->data['add_form'] = '1';
			$this->redirect(str_replace('&amp;', '&', $this->url->link('services/noteform/insert3', '' . $url2, 'SSL')));

			 
		}

		$this->data['entry_pin'] = $this->language->get('entry_pin');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['text_select'] = $this->language->get('text_select');

		$this->load->model('user/user');
		$this->data['users'] = $this->model_user_user->getUsersByFacility($this->request->get['facilities_id']);

		
		$url2 = "";
		
		
		
		if ($this->request->get['updatenotes_id'] != null && $this->request->get['updatenotes_id'] != "") {
			$url2 .= '&updatenotes_id=' . $this->request->get['updatenotes_id'];
		}
		
		if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
			$url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
		}
		
		if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
			$url2 .= '&notes_id=' . $this->request->get['notes_id'];
		}
		
		if ($this->request->get['incidentform_id'] != null && $this->request->get['incidentform_id'] != "") {
			$url2 .= '&incidentform_id=' . $this->request->get['incidentform_id'];
		}
		
		 
		$this->data['action2'] = str_replace('&amp;', '&',$this->url->link('services/noteform/insert3', '' . $url2, 'SSL'));
		
		$this->data['search_url'] = str_replace('&amp;', '&',$this->url->link('services/noteform/searchUser', '' . $url2, 'SSL'));
		
		$this->data['redirect_url'] = str_replace('&amp;', '&',$this->url->link('services/noteform/forminsert', '' . $url2, 'SSL'));
		
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
		
		if (isset($this->session->data['success_update_form'])) {
			$this->data['success_update_form'] = $this->session->data['success_update_form'];

			unset($this->session->data['success_update_form']);
		} else {
			$this->data['success_update_form'] = '';
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
				$config_default_sign = $this->config->get('config_default_sign');
			}else{
				$config_default_sign = '2';
			}
			$this->data['select_one'] = $config_default_sign;
		}

		if($this->config->get('config_default_sign') != null && $this->config->get('config_default_sign') != ""){
			$this->data['default_sign'] = $this->config->get('config_default_sign');
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


		$this->template = $this->config->get('config_template') . '/template/notes/notes_form2.tpl';

		$this->response->setOutput($this->render());
			
	}

	public function jsonForm(){
		
		if ($this->request->get['incidentform_id'] != null && $this->request->get['incidentform_id'] != "") {
			$incidentform_id = $this->request->get['incidentform_id'];
		}
			
		$this->data['facilitiess'][] = array(
			'incidentform_id'    => $incidentform_id,
		);
		$value = array('results'=>$this->data['facilitiess'],'status'=>true);
		
		$this->response->setOutput(json_encode($value));
	}
	
	
	public function searchUser(){
		$json = array();

		//if($this->request->get['user_id'] != null && $this->request->get['user_id'] != "") {
			$this->load->model('user/user');

			if (isset($this->request->get['user_id'])) {
				$user_id = $this->request->get['user_id'];
			} else {
				$user_id = '';
			}

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];	
			} else {
				$limit = 20;	
			}			

			$data = array(
				'user_id'  => $user_id,
				'facilities_id'  => $this->request->get['facilities_id'],
				'start'        => 0,
				'limit'        => $limit
			);
			
			$users = $this->model_user_user->getUsersByFacilityUser($data);
			
			foreach ($users as $user) {
				$json[] = array(
					'username' => $user['username'],
					'user_id' => $user['user_id'],
				);	
			}
		//}

		$this->response->setOutput(json_encode($json));
	}

	public function checklistform(){
			
		$task_id = $this->request->get['task_id'];
		$this->load->model('createtask/createtask');
		$this->data['task_info'] = $this->model_createtask_createtask->getnotesInfo2($task_id);
		
		$this->data['daytime'] = date('j, F Y');
		
		
		if (($this->request->post['form_submit'] == '1') && $this->validateForm3checlist()) {
				
			$this->load->model('facilities/facilities');
				
			$facilities_info = $this->model_facilities_facilities->getfacilities($this->request->post['ssincedentform']['facilities_id']);
					
					
			$this->load->model('setting/timezone');
					
			$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
					
			date_default_timezone_set($timezone_info['timezone_value']);
					
			$noteDate = date('Y-m-d H:i:s', strtotime('now'));
			$date_added = (string) $noteDate;
					
			$noteDate = date('Y-m-d H:i:s', strtotime('now'));
				
			$form_date_added = (string) $noteDate;
					
			require_once(DIR_APPLICATION . 'aws/awsconfig.php');
				
			//$this->session->data['ssbedcheckform'] = $this->request->post['ssbedcheckform'];
			$this->session->data['success2'] = 'Form Created successfully!';
				
			//var_dump($result);die;
				
		} 
		
		if (isset($this->session->data['success2'])) {
			$this->data['success2'] = $this->session->data['success2'];

			unset($this->session->data['success2']);
		} else {
			$this->data['success2'] = '';
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
		
		if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
			$url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
		}
		
		if ($this->request->get['task_id'] != null && $this->request->get['task_id'] != "") {
			$url2 .= '&task_id=' . $this->request->get['task_id'];
		}
		
		
		$this->data['action2'] = $this->url->link('services/noteform/checklistform', '' . $url2, 'SSL');
		
		//var_dump($this->data['action2']); 
		
		$this->data['redirect_url'] = str_replace('&amp;', '&',$this->url->link('services/noteform/insertchecklist', '' . $url2, 'SSL'));
		
			$this->template = $this->config->get('config_template') . '/template/notes/checklist_form.tpl';
			$this->response->setOutput($this->render());
			
		}
		
	protected function validateForm3checlist() {
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}



	
	public function insertchecklist() {
		$this->language->load('notes/notes');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('notes/notes');

		if (($this->request->post['form_submit'] == '1') && $this->validateForm23()) {

			$timezone_name = $this->customer->isTimezone();
			$timeZone = date_default_timezone_set($timezone_name);
			$noteDate = date('Y-m-d H:i:s', strtotime('now')); 
			
			   $timezone_name = $this->customer->isTimezone();
			   $timeZone = date_default_timezone_set($timezone_name);
			   $noteDate = date('Y-m-d H:i:s', strtotime('now'));
			   $date_added = (string) $noteDate;
			   $facilities_id = (string) $this->customer->getId();
				
				require_once(DIR_APPLICATION . 'aws/getItem.php');
				
				
				 if(($this->session->data['ssbedcheckform']['chk_box1'] != null && $this->session->data['ssbedcheckform']['chk_box1'] != "") && ($this->session->data['ssbedcheckform']['chk_box2'] != null && $this->session->data['ssbedcheckform']['chk_box2'] != "") && ($this->session->data['ssbedcheckform']['chk_box3'] != null && $this->session->data['ssbedcheckform']['chk_box3'] != "") && ($this->session->data['ssbedcheckform']['chk_box4'] != null && $this->session->data['ssbedcheckform']['chk_box4'] != "") && ($this->session->data['ssbedcheckform']['chk_box5'] != null && $this->session->data['ssbedcheckform']['chk_box5'] != "")){
					$checklist_status = "1";
				 }else{
					 $checklist_status = "2";
				 }
				 
				// var_dump($checklist_status);
				
				
				//$this->load->model('notes/notes');
				//$this->data['task_info'] = $this->model_notes_notes->addnotes($data, $facility_id); 
				$boyTotal = 0;
				if($this->session->data['ssbedcheckform']['boys_1'] != null && $this->session->data['ssbedcheckform']['boys_1'] != ""){
					$boyTotal = $boyTotal + $this->session->data['ssbedcheckform']['boys_1'];
				}
				
				if($this->session->data['ssbedcheckform']['boys_2'] != null && $this->session->data['ssbedcheckform']['boys_2'] != ""){
					$boyTotal = $boyTotal + $this->session->data['ssbedcheckform']['boys_2'];
				}
				
				if($this->session->data['ssbedcheckform']['boys_3'] != null && $this->session->data['ssbedcheckform']['boys_3'] != ""){
					$boyTotal = $boyTotal + $this->session->data['ssbedcheckform']['boys_3'];
				}
				
				if($this->session->data['ssbedcheckform']['boys_4'] != null && $this->session->data['ssbedcheckform']['boys_4'] != ""){
					$boyTotal = $boyTotal + $this->session->data['ssbedcheckform']['boys_4'];
				}
				
				if($this->session->data['ssbedcheckform']['boys_5'] != null && $this->session->data['ssbedcheckform']['boys_5'] != ""){
					$boyTotal = $boyTotal + $this->session->data['ssbedcheckform']['boys_5'];
				}
				
				$girlTotal = 0;
				if($this->session->data['ssbedcheckform']['girl_1'] != null && $this->session->data['ssbedcheckform']['girl_1'] != ""){
					$girlTotal = $girlTotal + $this->session->data['ssbedcheckform']['girl_1'];
				}
				
				if($this->session->data['ssbedcheckform']['girl_2'] != null && $this->session->data['ssbedcheckform']['girl_2'] != ""){
					$girlTotal = $girlTotal + $this->session->data['ssbedcheckform']['girl_2'];
				}
				
				if($this->session->data['ssbedcheckform']['girl_3'] != null && $this->session->data['ssbedcheckform']['girl_3'] != ""){
					$girlTotal = $girlTotal + $this->session->data['ssbedcheckform']['girl_3'];
				}
				
				if($this->session->data['ssbedcheckform']['girl_4'] != null && $this->session->data['ssbedcheckform']['girl_4'] != ""){
					$girlTotal = $girlTotal + $this->session->data['ssbedcheckform']['girl_4'];
				}
				
				if($this->session->data['ssbedcheckform']['girl_5'] != null && $this->session->data['ssbedcheckform']['girl_5'] != ""){
					$girlTotal = $girlTotal + $this->session->data['ssbedcheckform']['girl_5'];
				}
				
				
				if($this->request->get['task_id'] !=null && $this->request->get['task_id']!=""){
					$this->load->model('createtask/createtask');
					if($this->request->post['comments'] != null && $this->request->post['comments']){
						$this->request->post['comments'] = $this->request->post['comments'] .' '. $boyTotal .' Boys'  .' '. $girlTotal .' Girls' ;
					}else{
						$this->request->post['comments'] =  $boyTotal .' Boys'  .' '. $girlTotal .' Girls' ;
					}
					
					//var_dump($this->request->post['comments']);
					//var_dump($checklist_status);
					
					
					
					$result2 = $this->model_createtask_createtask->getStrikedatadetails($this->request->get['task_id']);
					
					$notesId = $this->model_createtask_createtask->inserttask($result2, $this->request->post, $this->customer->getId());
					
					$this->model_createtask_createtask->updatetaskNote($this->request->get['task_id']);
					$this->model_createtask_createtask->deteteIncomTask($this->customer->getId());
					//var_dump($notesId);
					
					$this->model_createtask_createtask->updateForm($notesId, $checklist_status);
					
					//die;
				}
				//var_dump($result2);
					
				
				$tableName = 'checklist';
				$result  = $dynamodb->putItem([
					
					
					
					'TableName' => $tableName,
					
					'Item' => [
		
					'checklist_id'       => ['N' => (string) rand () ], 
					'boys1'       => ['S' => empty($this->session->data['ssbedcheckform']['boys_1']) ? '&nbsp;': $this->session->data['ssbedcheckform']['boys_1'] ],
					'chk_box1'    => ['S'   => empty($this->session->data['ssbedcheckform']['chk_box1']) ? '&nbsp;': $this->session->data['ssbedcheckform']['chk_box1'] ],
					'girl1'     => ['S'      => empty($this->session->data['ssbedcheckform']['girl_1']) ? '&nbsp;': $this->session->data['ssbedcheckform']['girl_1'] ],
					
					'boys_2'       => ['S' => empty($this->session->data['ssbedcheckform']['boys_2']) ? '&nbsp;': $this->session->data['ssbedcheckform']['boys_2'] ],
					
					'chk_box2'    => ['S'   => empty($this->session->data['ssbedcheckform']['chk_box2']) ? '&nbsp;': $this->session->data['ssbedcheckform']['chk_box2'] ],
					
					'girl_2'     => ['S'      => empty($this->session->data['ssbedcheckform']['girl_2']) ? '&nbsp;': $this->session->data['ssbedcheckform']['girl_2'] ],
					
					'boys_3'       => ['S' => empty($this->session->data['ssbedcheckform']['boys_3']) ? '&nbsp;': $this->session->data['ssbedcheckform']['boys_3'] ],
					
					'chk_box3'    => ['S'   => empty($this->session->data['ssbedcheckform']['chk_box3']) ? '&nbsp;': $this->session->data['ssbedcheckform']['chk_box3'] ],
					
					'girl_3'     => ['S'      => empty($this->session->data['ssbedcheckform']['girl_3']) ? '&nbsp;': $this->session->data['ssbedcheckform']['girl_3'] ],
					
					'boys_4'       => ['S' => empty($this->session->data['ssbedcheckform']['boys_4']) ? '&nbsp;': $this->session->data['ssbedcheckform']['boys_4'] ],
					
					'chk_box4'    => ['S'   => empty($this->session->data['ssbedcheckform']['chk_box4']) ? '&nbsp;': $this->session->data['ssbedcheckform']['chk_box4'] ],
					
					'girl_4'     => ['S'      => empty($this->session->data['ssbedcheckform']['girl_4']) ? '&nbsp;': $this->session->data['ssbedcheckform']['girl_4'] ],
					
					
					'boys_5'       => ['S' => empty($this->session->data['ssbedcheckform']['boys_5']) ? '&nbsp;': $this->session->data['ssbedcheckform']['boys_5'] ],
					
					'chk_box5'    => ['S'   => empty($this->session->data['ssbedcheckform']['chk_box5']) ? '&nbsp;': $this->session->data['ssbedcheckform']['chk_box5'] ],
					
					'girl_5'     => ['S'      => empty($this->session->data['ssbedcheckform']['girl_5']) ? '&nbsp;': $this->session->data['ssbedcheckform']['girl_5'] ],
					
					'notes_id'     => ['S'      => empty($this->session->data['ssbedcheckform']['notes_id']) ? '&nbsp;': $this->session->data['ssbedcheckform']['notes_id'] ],
					
					
					'signature'     => ['S'      => empty($this->request->post['imgOutput']) ? '&nbsp;': $this->request->post['imgOutput']],
					
					'facilities_id'     => ['S'      => empty($facilities_id) ? '0': $facilities_id ],
					
					'date_added'     => ['S'      => empty($date_added) ? '&nbsp;': $date_added ],
					
					
					'checklist_status'     => ['S'      => empty($checklist_status) ? '0' : $checklist_status ],
					'user_id'     => ['S'      => empty($this->request->post['user_id'])? '0' :  $this->request->post['user_id']],
					'notes_pin'     => ['S'      => empty($this->request->post['notes_pin']) ? '0' : $this->request->post['notes_pin'] ],
					'form_date_added'     => ['S'      => (string) $noteDate ]
					
			]
			
			
	  
    
			]);
			//var_dump($result);
			

			$this->session->data['success_update_form'] = $this->language->get('text_success');
			//$this->session->data['add_form'] = '1';

			
			$url2 = "";
			if ($this->request->get['task_id'] != null && $this->request->get['task_id'] != "") {
				$url2 .= '&task_id=' . $this->request->get['task_id'];
			}
			
			
			
			/*if ($this->session->data['pagenumber'] != null && $this->session->data['pagenumber'] != "") {
				$url2. = '&page=' . $this->session->data['pagenumber'];
			}*/
			
			$this->redirect(str_replace('&amp;', '&', $this->url->link('services/noteform/checklistform', '' . $url2, 'SSL')));
		}
		
		$this->data['createtask'] = 1;

		$this->data['entry_pin'] = $this->language->get('entry_pin');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['text_select'] = $this->language->get('text_select');

		$this->load->model('user/user');
		$this->data['users'] = $this->model_user_user->getUsersByFacility($this->customer->getId());

		
		$url2 = "";
		
		
		if ($this->request->get['task_id'] != null && $this->request->get['task_id'] != "") {
			$url2 .= '&task_id=' . $this->request->get['task_id'];
		}
		
		if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
			$url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
		}
		
		 
		$this->data['action2'] = str_replace('&amp;', '&',$this->url->link('services/noteform/insertchecklist', '' . $url2, 'SSL'));
		
		$this->data['search_url'] = str_replace('&amp;', '&',$this->url->link('services/noteform/searchUser', '' . $url2, 'SSL'));
		
		
		
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
				$config_default_sign = $this->config->get('config_default_sign');
			}else{
				$config_default_sign = '2';
			}
			$this->data['select_one'] = $config_default_sign;
		}

		if($this->config->get('config_default_sign') != null && $this->config->get('config_default_sign') != ""){
			$this->data['default_sign'] = $this->config->get('config_default_sign');
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


		$this->template = $this->config->get('config_template') . '/template/notes/notes_form2.tpl';

		$this->response->setOutput($this->render());
			
	}
	
	
	public function checklistform(){
			
		$task_id = $this->request->get['task_id'];
		$this->load->model('createtask/createtask');
		$this->data['task_info'] = $this->model_createtask_createtask->getnotesInfo2($task_id);
		
		$this->data['daytime'] = date('j, F Y');
		
		
		if (($this->request->post['form_submit'] == '1') && $this->validateForm3checlist()) {
				
			$this->load->model('facilities/facilities');
				
			$facilities_info = $this->model_facilities_facilities->getfacilities($this->request->get['facilities_id']);
					
					
			$this->load->model('setting/timezone');
					
			$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
					
			date_default_timezone_set($timezone_info['timezone_value']);
					
			$noteDate = date('Y-m-d H:i:s', strtotime('now'));
			$date_added = (string) $noteDate;
					
			$noteDate = date('Y-m-d H:i:s', strtotime('now'));
				
			$form_date_added = (string) $noteDate;
			
			$facilities_id = (string) $this->request->get['facilities_id'];
			
			$checklist_id = (string) rand ();
					
			require_once(DIR_APPLICATION . 'aws/awsconfig.php');
			
				
				
				if(($this->request->post['ssbedcheckform']['chk_box1'] != null && $this->request->post['ssbedcheckform']['chk_box1'] != "") && ($this->request->post['ssbedcheckform']['chk_box2'] != null && $this->request->post['ssbedcheckform']['chk_box2'] != "") && ($this->request->post['ssbedcheckform']['chk_box3'] != null && $this->request->post['ssbedcheckform']['chk_box3'] != "") && ($this->request->post['ssbedcheckform']['chk_box4'] != null && $this->request->post['ssbedcheckform']['chk_box4'] != "") && ($this->request->post['ssbedcheckform']['chk_box5'] != null && $this->request->post['ssbedcheckform']['chk_box5'] != "")){
					$checklist_status = "1";
				}else{
					 $checklist_status = "2";
				}
				 
				// var_dump($checklist_status);
				
				
				//$this->load->model('notes/notes');
				//$this->data['task_info'] = $this->model_notes_notes->addnotes($data, $facility_id); 
				$boyTotal = 0;
				if($this->request->post['ssbedcheckform']['boys_1'] != null && $this->request->post['ssbedcheckform']['boys_1'] != ""){
					$boyTotal = $boyTotal + $this->request->post['ssbedcheckform']['boys_1'];
				}
				
				if($this->request->post['ssbedcheckform']['boys_2'] != null && $this->request->post['ssbedcheckform']['boys_2'] != ""){
					$boyTotal = $boyTotal + $this->request->post['ssbedcheckform']['boys_2'];
				}
				
				if($this->request->post['ssbedcheckform']['boys_3'] != null && $this->request->post['ssbedcheckform']['boys_3'] != ""){
					$boyTotal = $boyTotal + $this->request->post['ssbedcheckform']['boys_3'];
				}
				
				if($this->request->post['ssbedcheckform']['boys_4'] != null && $this->request->post['ssbedcheckform']['boys_4'] != ""){
					$boyTotal = $boyTotal + $this->request->post['ssbedcheckform']['boys_4'];
				}
				
				if($this->request->post['ssbedcheckform']['boys_5'] != null && $this->request->post['ssbedcheckform']['boys_5'] != ""){
					$boyTotal = $boyTotal + $this->request->post['ssbedcheckform']['boys_5'];
				}
				
				$girlTotal = 0;
				if($this->request->post['ssbedcheckform']['girl_1'] != null && $this->request->post['ssbedcheckform']['girl_1'] != ""){
					$girlTotal = $girlTotal + $this->request->post['ssbedcheckform']['girl_1'];
				}
				
				if($this->request->post['ssbedcheckform']['girl_2'] != null && $this->request->post['ssbedcheckform']['girl_2'] != ""){
					$girlTotal = $girlTotal + $this->request->post['ssbedcheckform']['girl_2'];
				}
				
				if($this->request->post['ssbedcheckform']['girl_3'] != null && $this->request->post['ssbedcheckform']['girl_3'] != ""){
					$girlTotal = $girlTotal + $this->request->post['ssbedcheckform']['girl_3'];
				}
				
				if($this->request->post['ssbedcheckform']['girl_4'] != null && $this->request->post['ssbedcheckform']['girl_4'] != ""){
					$girlTotal = $girlTotal + $this->request->post['ssbedcheckform']['girl_4'];
				}
				
				if($this->request->post['ssbedcheckform']['girl_5'] != null && $this->request->post['ssbedcheckform']['girl_5'] != ""){
					$girlTotal = $girlTotal + $this->request->post['ssbedcheckform']['girl_5'];
				}
				
				$mTotal = $boyTotal + $girlTotal;
				
				if($this->request->get['task_id'] !=null && $this->request->get['task_id']!=""){
					$this->load->model('createtask/createtask');
					if($this->request->post['comments'] != null && $this->request->post['comments']){
						$this->request->post['comments'] = $this->request->post['comments'] .' '. $boyTotal .' Boys'  .' '. $girlTotal .' Girls. Total residence count '. $mTotal ;
					}else{
						$this->request->post['comments'] =  $boyTotal .' Boys'  .' '. $girlTotal .' Girls. Total total residence count '. $mTotal ;
					}
					//var_dump($this->request->post['comments']);
					//var_dump($checklist_status);
					
					$result2 = $this->model_createtask_createtask->getStrikedatadetails($this->request->get['task_id']);
					
					$notesId = $this->model_createtask_createtask->inserttask($result2, $this->request->post, $this->request->get['facilities_id']);
					
					$this->model_createtask_createtask->updatetaskNote($this->request->get['task_id']);
					$this->model_createtask_createtask->deteteIncomTask($this->request->get['facilities_id']);
					//var_dump($notesId);
					$ttstatus = "0";
					$this->model_createtask_createtask->updateForm($notesId, $checklist_status, $ttstatus);
					
					//die;
				}
					
					$tableName = 'checklist';
					$result  = $dynamodb->putItem([
					
					'TableName' => $tableName,
					
					'Item' => [
		
					'checklist_id'       => ['N' => $checklist_id ], 
					'notes_id'       => ['S' => (string) $notesId ], 
					'boys1'       => ['S' => empty($this->request->post['ssbedcheckform']['boys_1']) ? '&nbsp;': $this->request->post['ssbedcheckform']['boys_1'] ],
					'chk_box1'    => ['S'   => empty($this->request->post['ssbedcheckform']['chk_box1']) ? '&nbsp;': $this->request->post['ssbedcheckform']['chk_box1'] ],
					'girl1'     => ['S'      => empty($this->request->post['ssbedcheckform']['girl_1']) ? '&nbsp;': $this->request->post['ssbedcheckform']['girl_1'] ],
					
					'boys_2'       => ['S' => empty($this->request->post['ssbedcheckform']['boys_2']) ? '&nbsp;': $this->request->post['ssbedcheckform']['boys_2'] ],
					
					'chk_box2'    => ['S'   => empty($this->request->post['ssbedcheckform']['chk_box2']) ? '&nbsp;': $this->request->post['ssbedcheckform']['chk_box2'] ],
					
					'girl_2'     => ['S'      => empty($this->request->post['ssbedcheckform']['girl_2']) ? '&nbsp;': $this->request->post['ssbedcheckform']['girl_2'] ],
					
					'boys_3'       => ['S' => empty($this->request->post['ssbedcheckform']['boys_3']) ? '&nbsp;': $this->request->post['ssbedcheckform']['boys_3'] ],
					
					'chk_box3'    => ['S'   => empty($this->request->post['ssbedcheckform']['chk_box3']) ? '&nbsp;': $this->request->post['ssbedcheckform']['chk_box3'] ],
					
					'girl_3'     => ['S'      => empty($this->request->post['ssbedcheckform']['girl_3']) ? '&nbsp;': $this->request->post['ssbedcheckform']['girl_3'] ],
					
					'boys_4'       => ['S' => empty($this->request->post['ssbedcheckform']['boys_4']) ? '&nbsp;': $this->request->post['ssbedcheckform']['boys_4'] ],
					
					'chk_box4'    => ['S'   => empty($this->request->post['ssbedcheckform']['chk_box4']) ? '&nbsp;': $this->request->post['ssbedcheckform']['chk_box4'] ],
					
					'girl_4'     => ['S'      => empty($this->request->post['ssbedcheckform']['girl_4']) ? '&nbsp;': $this->request->post['ssbedcheckform']['girl_4'] ],
					
					
					'boys_5'       => ['S' => empty($this->request->post['ssbedcheckform']['boys_5']) ? '&nbsp;': $this->request->post['ssbedcheckform']['boys_5'] ],
					
					'chk_box5'    => ['S'   => empty($this->request->post['ssbedcheckform']['chk_box5']) ? '&nbsp;': $this->request->post['ssbedcheckform']['chk_box5'] ],
					
					'girl_5'     => ['S'      => empty($this->request->post['ssbedcheckform']['girl_5']) ? '&nbsp;': $this->request->post['ssbedcheckform']['girl_5'] ],
					
					'signature'     => ['S'      => empty($this->request->post['imgOutput']) ? '&nbsp;': $this->request->post['imgOutput']],
					
					'facilities_id'     => ['S'      => empty($facilities_id) ? '0': $facilities_id ],
					
					'date_added'     => ['S'      => empty($date_added) ? '&nbsp;': $date_added ],
					
					'checklist_status'     => ['S'      => empty($checklist_status) ? '0' : $checklist_status ],
					'user_id'     => ['S'      => empty($this->request->post['user_id'])? '0' :  $this->request->post['user_id']],
					'notes_pin'     => ['S'      => empty($this->request->post['notes_pin']) ? '0' : $this->request->post['notes_pin'] ],
					'form_date_added'     => ['S'      => (string) $noteDate ]
					
			]
    
			]);
			
			//$this->session->data['ssbedcheckform'] = $this->request->post['ssbedcheckform'];
			
			$this->session->data['success2'] = 'Form Created successfully!';
				
			//var_dump($result);die;
				
		} 
		
		if (isset($this->session->data['success2'])) {
			$this->data['success2'] = $this->session->data['success2'];

			unset($this->session->data['success2']);
		} else {
			$this->data['success2'] = '';
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
		
		if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
			$url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
		}
		
		if ($this->request->get['task_id'] != null && $this->request->get['task_id'] != "") {
			$url2 .= '&task_id=' . $this->request->get['task_id'];
		}
		
		if ($checklist_id != null && $checklist_id != "") {
			$url2 .= '&checklist_id=' . $checklist_id;
		}
		
		
		
		$this->data['action2'] = $this->url->link('services/noteform/checklistform', '' . $url2, 'SSL');
		
		
		
		$this->data['redirect_url'] = str_replace('&amp;', '&',$this->url->link('services/noteform/insertchecklist', '' . $url2, 'SSL'));
		
	//	var_dump($this->data['redirect_url']); die;
		
			$this->template = $this->config->get('config_template') . '/template/notes/checklist_form.tpl';
			$this->response->setOutput($this->render());
			
		}
		
	protected function validateForm3checlist() {
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}



	
	public function insertchecklist() {
		$this->language->load('notes/notes');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('notes/notes');

		if (($this->request->post['form_submit'] == '1') && $this->validateForm23()) {

				$this->load->model('facilities/facilities');
				
				$facilities_info = $this->model_facilities_facilities->getfacilities($this->request->get['facilities_id']);
						
						
				$this->load->model('setting/timezone');
						
				$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
						
				date_default_timezone_set($timezone_info['timezone_value']);
						
				$noteDate = date('Y-m-d H:i:s', strtotime('now'));
				$date_added = (string) $noteDate;
						
				$noteDate = date('Y-m-d H:i:s', strtotime('now'));
					
				$form_date_added = (string) $noteDate;
				
				$facilities_id = (string) $this->request->get['facilities_id'];
				$checklist_id = (string) $this->request->get['checklist_id'];
						
				require_once(DIR_APPLICATION . 'aws/awsconfig.php');
				
				/*var_dump($this->request->post);
				echo "<hr>";
				*/
				$response = $dynamodb->getItem([ 
							'TableName' => 'checklist',
							'Key' => [
								'checklist_id' => [ 'N' => $checklist_id ] 
							]
						]);
				
				/*var_dump($response);
				echo "<hr>";
				*/
				if($response['Item']['notes_id']['S'] !=null && $response['Item']['notes_id']['S'] !=""){
					$this->load->model('createtask/createtask');
					
					$ttstatus = "1";
					$this->model_createtask_createtask->updateForm($response['Item']['notes_id']['S'],$response['Item']['checklist_status']['S'], $ttstatus);
					
					//die;
				}
				
				$tableName = 'checklist';
				$result  = $dynamodb->putItem([
					
					'TableName' => $tableName,
					
					'Item' => [
		
					'checklist_id'       => ['N' => $checklist_id ], 
					'notes_id'       => ['N' => $response['Item']['notes_id']['S'] ], 
					'boys1'       => ['S' => $response['Item']['boys1']['S'] ],
					'chk_box1'    => ['S'   => $response['Item']['chk_box1']['S'] ],
					'girl1'     => ['S'      => $response['Item']['girl1']['S'] ],
					
					'boys_2'       => ['S' => $response['Item']['boys_2']['S'] ],
					
					'chk_box2'    => ['S'   => $response['Item']['chk_box2']['S'] ],
					
					'girl_2'     => ['S'      => $response['Item']['girl_2']['S'] ],
					
					'boys_3'       => ['S' => $response['Item']['boys_3']['S'] ],
					
					'chk_box3'    => ['S'   => $response['Item']['chk_box3']['S'] ],
					
					'girl_3'     => ['S'      => $response['Item']['girl_3']['S'] ],
					
					'boys_4'       => ['S' => $response['Item']['boys_4']['S'] ],
					
					'chk_box4'    => ['S'   => $response['Item']['chk_box4']['S'] ],
					
					'girl_4'     => ['S'      => $response['Item']['girl_4']['S'] ],
					
					
					'boys_5'       => ['S' => $response['Item']['boys_5']['S'] ],
					
					'chk_box5'    => ['S'   => $response['Item']['chk_box5']['S'] ],
					
					'girl_5'     => ['S'      => $response['Item']['girl_5']['S'] ],
					
					'signature'     => ['S'      => empty($this->request->post['imgOutput']) ? '&nbsp;': $this->request->post['imgOutput']],
					
					'facilities_id'     => ['S'      => $response['Item']['facilities_id']['S'] ],
					
					'date_added'     => ['S'      => $response['Item']['date_added']['S'] ],
					
					
					'checklist_status'     => ['S'      => $response['Item']['checklist_status']['S'] ],
					'user_id'     => ['S'      => empty($this->request->post['user_id'])? '0' :  $this->request->post['user_id']],
					'notes_pin'     => ['S'      => empty($this->request->post['notes_pin']) ? '0' : $this->request->post['notes_pin'] ],
					'form_date_added'     => ['S'      => $response['Item']['form_date_added']['S'] ]
					
			]
			
			
	  
    
			]);
			/*
			var_dump($result);
			die;
			*/

			$this->session->data['success_update_form'] = $this->language->get('text_success');
			//$this->session->data['add_form'] = '1';

			
			$url2 = "";
			if ($this->request->get['task_id'] != null && $this->request->get['task_id'] != "") {
				$url2 .= '&task_id=' . $this->request->get['task_id'];
			}
			
			if ($this->request->get['checklist_id'] != null && $this->request->get['checklist_id'] != "") {
				$url2 .= '&checklist_id=' . $this->request->get['checklist_id'];
			}
			
			$url2 .= '&add_form=1';
			
			/*if ($this->session->data['pagenumber'] != null && $this->session->data['pagenumber'] != "") {
				$url2. = '&page=' . $this->session->data['pagenumber'];
			}*/
			
			$this->redirect(str_replace('&amp;', '&', $this->url->link('services/noteform/checklistform', '' . $url2, 'SSL')));
		}
		
		$this->data['createtask'] = 1;

		$this->data['entry_pin'] = $this->language->get('entry_pin');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['text_select'] = $this->language->get('text_select');

		$this->load->model('user/user');
		$this->data['users'] = $this->model_user_user->getUsersByFacility($this->customer->getId());

		
		$url2 = "";
		
		
		if ($this->request->get['task_id'] != null && $this->request->get['task_id'] != "") {
			$url2 .= '&task_id=' . $this->request->get['task_id'];
		}
		
		if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
			$url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
		}
		
		if ($this->request->get['checklist_id'] != null && $this->request->get['checklist_id'] != "") {
			$url2 .= '&checklist_id=' . $this->request->get['checklist_id'];
		}
		 
		$this->data['action2'] = str_replace('&amp;', '&',$this->url->link('services/noteform/insertchecklist', '' . $url2, 'SSL'));
		
		$this->data['search_url'] = str_replace('&amp;', '&',$this->url->link('services/noteform/searchUser', '' . $url2, 'SSL'));
		
		
		
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
				$config_default_sign = $this->config->get('config_default_sign');
			}else{
				$config_default_sign = '2';
			}
			$this->data['select_one'] = $config_default_sign;
		}

		if($this->config->get('config_default_sign') != null && $this->config->get('config_default_sign') != ""){
			$this->data['default_sign'] = $this->config->get('config_default_sign');
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


		$this->template = $this->config->get('config_template') . '/template/notes/notes_form2.tpl';

		$this->response->setOutput($this->render());
			
	}
	
	
}

?> 