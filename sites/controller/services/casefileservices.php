<?php
header ( 'Access-Control-Allow-Origin:' . $_SERVER ['HTTP_ORIGIN'] );
header ( 'Access-Control-Allow-Methods: POST, GET, OPTIONS' );
header ( 'Access-Control-Max-Age: 1000' );
header ( 'Access-Control-Allow-Headers: Content-Type' );
header ( 'Content-type: application/json;' );
header ( 'Content-Type: text/html; charset=utf-8' );
class Controllerservicescasefileservices extends Controller {

	public function jsonCaseList() {
		
		try {
			$this->load->model ( 'setting/tags' );
			$this->load->model ( 'form/form' );
			$this->load->model ( 'notes/notes' );
			$this->load->model ( 'resident/resident' );
			$this->language->load ( 'notes/notes' );
			
			$tags_id = $this->request->post['tags_id'];
			
			
			
			$cdata = array (
				'case_number' => 1,
				'tags_id' => $tags_id
			);
			
			//echo '<pre>fff'; print_r($data); echo '</pre>'; //die;
			
			$this->load->model ( 'resident/casefile' );
			$case_info = $this->model_resident_casefile->getcasefiles ( $cdata );

			//echo '<pre>'; print_r($case_info); echo '</pre>'; //die;
			
			$this->data ['cases'] = array ();
			if(!empty($case_info)){
			
				foreach ( $case_info as $allform ) {
					
					$note_info = $this->model_notes_notes->getNote ( $allform ['notes_id'] );
					//echo '<pre>'; print_r($note_info); echo '</pre>'; //die;
					if ($allform ['user_id'] != null && $allform ['user_id'] != "") {
						$user_id = $note_info ['user_id'];
						$signature = $allform ['signature'];
						$notes_pin = $allform ['notes_pin'];
						$notes_type = $allform ['notes_type'];
						
						if ($allform ['date_added'] != null && $allform ['date_added'] != "0000-00-00 00:00:00") {
							$form_date_added = $allform ['date_added'];
						} else {
							$form_date_added = '';
						}
						// echo 'ssss';
					}
					
					if ($allform ['case_status'] == '0') {
						$client_status = 'Open';
					} else if ($allform ['case_status'] == '1') {
						$client_status = 'Closed';
					} else if ($allform ['case_status'] == '2') {
						$client_status = 'Marked Final';
					}
					
					$this->data ['cases'] [] = array (
							'notes_by_case_file_id' => $allform ['notes_by_case_file_id'],
							'forms_id' => $allform ['forms_ids'],
							'notes_type' => $notes_type,
							'user_id' => $user_id,
							'tags_id' => $allform ['tags_ids'],
							'signature' => $signature,
							'notes_id' => $allform ['notes_id'],
							'case_number' => $allform ['case_number'],
							'case_status' => $allform ['case_status'],
							'client_status2' => $client_status,
							'notes_pin' => $notes_pin,
							'form_date_added' => $form_date_added 
					);
				}

				$value = array (
					'results' => $this->data ['cases'] ,
					'status' => true,
					'message' => 'Success'
				);

			}else{
				$value = array (
					'results' => '' ,
					'status' => false,
					'message' => 'There are no cases assigned to this person. Please Add new case to start a file'
				);
			}
			
			$this->response->setOutput ( json_encode ( $value ) );
			
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in casefileservices jsonCaseList ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_jsonCaseList', $activity_data2 );
		}
	}

	public function jsonViewcase() {
		
		try {
			$this->load->model ( 'setting/tags' );
			$this->load->model ( 'form/form' );
			$this->load->model ( 'notes/notes' );
			$this->load->model ( 'resident/resident' );
			$this->language->load ( 'notes/notes' );
			
			$case_file_id = $this->request->post['case_file_id'];
			
			$cdata = array (
				'case_number' => 1,
				'case_file_id' => $case_file_id,
				'sort' => $sort,
				'order' => $order,
				'start' => ($page - 1) * $config_admin_limit,
				'limit' => $config_admin_limit 
			);
			
			//echo '<pre>'; print_r($data); echo '</pre>'; die;
			
			$this->load->model ( 'resident/casefile' );
			
			$allform1 = $this->model_resident_casefile->getcasefile ( $cdata );

			if(!empty($allform1)){

			
			if($allform1 ['forms_ids']!=''){
				$forms_ids_arr = explode ( ',', $allform1 ['forms_ids'] );
			}else{
				$forms_ids_arr =array();
			}

			//echo '<pre>'; print_r($forms_ids_arr); echo '</pre>'; die;
			
			$this->data ['tagsforms'] = array ();
			
			foreach ( $forms_ids_arr as $form_id ) {
				
				$allform = $this->model_form_form->getFormDatas ( $form_id );
				
				
				//echo '<pre>'; print_r($allform); echo '</pre>'; //die;
				
				$note_info = $this->model_notes_notes->getNote ( $allform ['notes_id'] );
				
				// echo '<pre>'; print_r($note_info); echo '</pre>'; //die;
				
				
				if ($allform ['user_id'] != null && $allform ['user_id'] != "") {
					$user_id = $note_info ['user_id'];
					$signature = $allform ['signature'];
					$notes_pin = $allform ['notes_pin'];
					$notes_type = $allform ['notes_type'];
					$notes_description = $note_info ['notes_description'];
					
					if ($allform ['date_added'] != null && $allform ['date_added'] != "0000-00-00 00:00:00") {
						$form_date_added = date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $allform ['date_added'] ) );
					} else {
						$form_date_added = '';
					}
				} else {
					// var_dump($note_info);
					$user_id = $note_info ['user_id'];
					$signature = $note_info ['signature'];
					$notes_pin = $note_info ['notes_pin'];
					$notes_type = $note_info ['notes_type'];
					$notes_description = $note_info ['notes_description'];
					
					if ($note_info ['note_date'] != null && $note_info ['note_date'] != "0000-00-00 00:00:00") {
						$form_date_added = date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $note_info ['note_date'] ) );
					} else {
						$form_date_added = '';
					}
				}
				
				if ($allform ['image_url'] != null && $allform ['image_url'] != "") {
					$hrurl = $allform ['image_url'];
					$form_name = $allform ['image_name'];
					$fileOpen = $this->url->link('notes/notes/displayFile', '' . '&notes_media_id='.$hrurl . $url, 'SSL');
				} else {
					//$hrurl = $this->url->link ( 'form/form', '' . '&forms_id=' . $form_id . '&tags_id=' . $tags_id . '&notes_id=' . $allform ['notes_id'] . '&forms_design_id=' . $allform ['custom_form_type'], 'SSL' );
					
					$hrurl = str_replace ( '&amp;', '&', $this->url->link ( 'services/form', '' . 'forms_id=' . $allform ['forms_id'] . '&facilities_id=' . $allform ['facilities_id'] . '&notes_id=' . $allform ['notes_id'] . '&forms_design_id=' . $allform ['custom_form_type']. '&case_file_id=' . $case_file_id ));
					
					$form_name = $allform ['incident_number'];
				}
				
				if ($allform ['case_status'] == '0') {
					$client_status = 'Open';
				} else if ($allform ['case_status'] == '1') {
					$client_status = 'Closed';
				} else if ($allform ['case_status'] == '2') {
					$client_status = 'Marked Final';
				}
				
				$this->data ['tagsforms'] [] = array (
					'case_number' => $allform1 ['case_number'],
					'forms_id' => $form_id,
					'case_file_id' => $allform1 ['notes_by_case_file_id'],
					'image_url' => $allform ['image_url'],
					'notes_id' => $allform ['notes_id'],
					'assign_case' => $allform ['assign_case'],
					'forms_design_id' => $allform ['custom_form_type'],
					'form_name' => $form_name,
					'notes_type' => $notes_type,
					'notes_description' => $notes_description,
					'user_id' => $user_id,
					'case_status' => $client_status,
					'signature' => $signature,
					'notes_pin' => $notes_pin,
					'form_date_added' => $form_date_added,
					'note_date' => $note_info ['note_date'],
					'date_added' => date ( 'm-d-Y', strtotime ( $allform ['date_added'] ) ),
					'date_added2' => date ( 'D F j, Y', strtotime ( $allform ['date_added'] ) ),
					//'archivedforms' => $archivedforms,
					'form_href' => $hrurl, //fileOpen,
					'case_status2' => $allform ['case_status']
				);
		
			}

				//echo '<pre>'; print_r($this->data ['tagsforms']); echo '</pre>'; //die;
			
				$value = array (
					'results' => $this->data ['tagsforms'] ,
					'status' => true,
					'message' => 'Success'
				);

			}else{
				$value = array (
					'results' => '' ,
					'status' => false,
					'message' => 'There are no file assigned to this case. Please Add new form to start a case'
				);
			}
			
			$this->response->setOutput ( json_encode ( $value ) );
			
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in appservices jsonClassification ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_jsonClassification', $activity_data2 );
		}
	}

	public function jsonAddcaseList() {
		
		try {
			$this->load->model ( 'setting/tags' );
			$this->load->model ( 'form/form' );
			$this->load->model ( 'notes/notes' );
			$this->load->model ( 'resident/resident' );
			$this->language->load ( 'notes/notes' );
			
			
			$tags_id = $this->request->post['tags_id'];

			if($tags_id!=''){

				$cdata = array (
					'sort' => $sort,
					'order' => $order,
					'is_case' => 1,
					'tags_id' => $tags_id,
					'add_case' => '1',
					'start' => ($page - 1) * $config_admin_limit,
					'limit' => $config_admin_limit 
				);
			
				//echo '<pre>'; print_r($data); echo '</pre>'; die;
				
				$allforms = $this->model_form_form->gettagsforms ( $cdata );

				//echo '<pre>'; print_r($allforms); echo '</pre>'; //die;

				if(!empty($allforms)){

					$this->data ['tagsforms'] = array ();
					
					foreach ( $allforms as $allform ) {
						
						$form_info = $this->model_form_form->getFormdata ( $allform ['custom_form_type'] );
						$note_info = $this->model_notes_notes->getNote ( $allform ['notes_id'] );

						//echo '<pre>fff'; print_r($note_info); echo '</pre>'; //die;


						if ($allform ['user_id'] != null && $allform ['user_id'] != "") {
							$user_id = $allform ['user_id'];
							$signature = $allform ['signature'];
							$notes_pin = $allform ['notes_pin'];
							$notes_type = $allform ['notes_type'];
							$notes_description = $note_info ['notes_description'];
							
							if ($allform ['form_date_added'] != null && $allform ['form_date_added'] != "0000-00-00 00:00:00") {
								$form_date_added = date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $allform ['form_date_added'] ) );
							} else {
								$form_date_added = '';
							}
						} else {
							
							$user_id = $note_info ['user_id'];
							$signature = $note_info ['signature'];
							$notes_pin = $note_info ['notes_pin'];
							$notes_type = $note_info ['notes_type'];
							$notes_description = $note_info ['notes_description'];
							
							if ($note_info ['note_date'] != null && $note_info ['note_date'] != "0000-00-00 00:00:00") {
								$form_date_added = date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $note_info ['note_date'] ) );
							} else {
								$form_date_added = '';
							}
						}
						
						if ($allform ['image_url'] != null && $allform ['image_url'] != "") {
							$hrurl = $allform ['image_url'];
							$form_name = $allform ['image_name'];
						} else {
							//$hrurl = $this->url->link ( 'services/form', '' . '&forms_id=' . $allform ['forms_id'] . '&tags_id=' . $allform ['tags_id'] . '&notes_id=' . $allform ['notes_id'] . '&forms_design_id=' . $allform ['custom_form_type'] . '&forms_id=' . $allform ['forms_id'], 'SSL' );
							
							$hrurl = str_replace ( '&amp;', '&', $this->url->link ( 'services/form', '' . 'forms_id=' . $allform ['forms_id'] . '&facilities_id=' . $allform ['facilities_id'] . '&notes_id=' . $allform ['notes_id'] . '&forms_design_id=' . $allform ['custom_form_type'] ) );
							
							$form_name = $allform ['incident_number'];
						}
						
						if ($allform ['case_status'] == '0') {
							
							$client_status = 'Open';
						} else if ($allform ['case_status'] == '1') {
							
							$client_status = 'Closed';
						} else if ($allform ['case_status'] == '2') {
							
							$client_status = 'Marked Final';
						}
						
						$this->data ['tagsforms'] [] = array (
								'forms_id' => $allform ['forms_id'],
								'notes_id' => $allform ['notes_id'],
								'forms_design_id' => $allform ['custom_form_type'],
								'form_name' => $form_name,
								'notes_type' => $notes_type,
								'notes_description' => $notes_description,
								'user_id' => $user_id,
								'case_status' => $client_status,
								'signature' => $signature,
								'notes_pin' => $notes_pin,
								'form_date_added' => $form_date_added,
								'note_date' => $note_info ['note_date'],
								'date_added' => date ( 'm-d-Y', strtotime ( $allform ['date_added'] ) ),
								'date_added2' => date ( 'D F j, Y', strtotime ( $allform ['date_added'] ) ),
								//'archivedforms' => $archivedforms,
								'form_href' => $hrurl 
						);
					}

					$value = array (
						'results' => $this->data ['tagsforms'] ,
						'status' => true,
						'message' => 'Success'
					);

				}else{
					$value = array (
						'results' => '' ,
						'status' => false,
						'message' => 'There are no file assigned to this Client.'
					);
				}
			
			}else{
				$value = array (
					'results' => '' ,
					'status' => false,
					'message' => 'Client ID is not set'
				);
			}


			//echo '<pre>'; print_r($value); echo '</pre>'; die;
			
			$this->response->setOutput ( json_encode ( $value ) );
			
		} catch ( Exception $e ) {
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
				'data' => 'Error in casefileservices jsonClassification ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_jsonClassification', $activity_data2 );
		}
	}


	public function jsonCasenumberList() {
		
		try {
			$this->load->model ( 'setting/tags' );
			$this->load->model ( 'form/form' );
			$this->load->model ( 'notes/notes' );
			$this->load->model ( 'resident/resident' );
			$this->language->load ( 'notes/notes' );
			
			$facilities_id = $this->request->post['facilities_id'];
			
			
			
			$cdata = array (
				'facilities_id' => $facilities_id
			);
			
			//echo '<pre>fff'; print_r($data); echo '</pre>'; //die;
			
			$this->load->model ( 'resident/casefile' );
			$case_info = $this->model_resident_casefile->getCaseNumber ( $cdata );

			//echo '<pre>'; print_r($case_info); echo '</pre>'; //die;
			
			$this->data ['cases'] = array ();
			if(!empty($case_info)){
			
				foreach ( $case_info as $allform ) {
					if($allform ['case_number']!=''){
						$this->data ['cases'] [] = array (
							'notes_by_case_file_id' => $allform ['notes_by_case_file_id'],
							'case_number' => $allform ['case_number'],
							'case_status' => $allform ['case_status']
					);
					}
					
				}

				$value = array (
					'results' => $this->data ['cases'] ,
					'status' => true,
					'message' => 'Success'
				);

			}else{
				$value = array (
					'results' => '' ,
					'status' => false,
					'message' => 'Case number not found'
				);
			}
			
			$this->response->setOutput ( json_encode ( $value ) );
			
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in casefileservices jsonClassification ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_jsonClassification', $activity_data2 );
		}
	}

	public function jsonSavecase() {
		
		try {
			if ($this->request->post ['notes_pin'] != null && $this->request->post ['notes_pin'] != "") {
				$this->load->model ( 'user/user' );
				$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
				
				if (($this->request->post ['notes_pin'] != $user_info ['user_pin'])) {
					$json ['warning'] = 'User Pin not valid!.';
					$facilitiessee = array ();
					$facilitiessee [] = array (
							'warning' => $json ['warning'] 
					);
					$error = false;
					
					$value = array (
							'results' => $facilitiessee,
							'status' => false 
					);
					
					return $this->response->setOutput ( json_encode ( $value ) );
				}
			}
			
			if ($this->request->post ['user_id'] != null && $this->request->post ['user_id'] != "") {
				$this->load->model ( 'user/user' );
				$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
				
				if (($user_info ['status'] == '0')) {
					$json ['warning'] = 'User not exit!';
					$facilitiessee = array ();
					$facilitiessee [] = array (
							'warning' => $json ['warning'] 
					);
					$error = false;
					
					$value = array (
							'results' => $facilitiessee,
							'status' => false 
					);
					
					return $this->response->setOutput ( json_encode ( $value ) );
				}
				
				$this->load->model ( 'facilities/facilities' );
				$facility = $this->model_facilities_facilities->getfacilities ( $this->request->post ['facilities_id'] );
				$unique_id = $facility ['customer_key'];
				
				$this->load->model ( 'customer/customer' );
				$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
				
				if ($user_info ['customer_key'] != $customer_info ['activecustomer_id']) {
					$json ['warning'] = $this->language->get ( 'error_customer' );
					$facilitiessee = array ();
					$facilitiessee [] = array (
							'warning' => $json ['warning'] 
					);
					$error = false;
					
					$value = array (
							'results' => $facilitiessee,
							'status' => false 
					);
					
					return $this->response->setOutput ( json_encode ( $value ) );
				}
			}
			
			if ($this->request->post ['current_enroll_image1'] == "1") {
				$this->load->model ( 'api/facerekognition' );
				$fre_array = array ();
				$fre_array ['current_enroll_image1'] = $this->request->post ['current_enroll_image1'];
				$fre_array ['facilities_id'] = $this->request->post ['facilities_id'];
				$fre_array ['user_id'] = $this->request->post ['user_id'];
				$facerekognition_response = $this->model_api_facerekognition->checkfacerekognition ( $fre_array, $this->request->post );
				
				$json ['warning'] = $facerekognition_response ['warning1'];
				
				$facilitiessee = array ();
				$facilitiessee [] = array (
						'warning' => $json ['warning'] 
				);
				$error = false;
				
				$value = array (
						'results' => $facilitiessee,
						'status' => false 
				);
				
				return $this->response->setOutput ( json_encode ( $value ) );
			}
			
			if ($json ['warning'] == null && $json ['warning'] == "") {
			
			$this->load->model ( 'facilities/online' );
			$facilities_id = $this->request->post['facilities_id'];
			

			$this->language->load ( 'notes/notes' );
			$this->load->model ( 'facilities/facilities' );
			
			$facilities_info2 = $this->model_facilities_facilities->getfacilities ( $facilities_id );
			$this->load->model ( 'setting/timezone' );
			$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info2 ['timezone_id'] );
			$timezone_name = $timezone_info ['timezone_value'];
				
			date_default_timezone_set ( $timezone_name );

			$pdata =array();
			$pdata ['imgOutput'] = $this->request->post['imgOutput'];
			$pdata ['signature'] = $this->request->post['signature'];
			$pdata ['notes_pin'] = $this->request->post['notes_pin'];
			$pdata ['user_id'] = $this->request->post['user_id'];
			$pdata ['comments'] = $this->request->post['comments'];
			$pdata ['username'] = $this->request->post['username'];
			$pdata ['emp_tag_id'] = $this->request->post['emp_tag_id'];
			
			$fdata = array ();
			$fdata ['facilities_id'] = $this->request->post['facilities_id'];
			$fdata ['case_number'] = $this->request->post['case_number'];
			$fdata ['tags_id'] = $this->request->post['tags_id'];
			$fdata ['forms_id'] = $this->request->post['forms_id'];
			$fdata ['case_status'] = $this->request->post['case_status'];

			//echo '<pre>'; print_r($fdata); echo '</pre>'; die;
			$this->load->model ( 'form/form' );
			$res = $this->model_form_form->caseFormListSign ( $this->request->post, $fdata );
			
			}

			if($res){
				$value = array (
					'status' => true,
					'message' => 'Case added successfully.'
				);
			}else{
				$value = array (
					'status' => false,
					'message' => 'Case not add.'
				);
			}
			//echo '<pre>'; print_r($value); echo '</pre>'; die;
			
			$this->response->setOutput ( json_encode ( $value ) );
			
		} catch ( Exception $e ) {
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
				'data' => 'Error in casefileservices jsonClassification ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_jsonClassification', $activity_data2 );
		}
	}	

	public function jsonSaveform() {
		
		try {
			
			
			$pdata =array();
			$pdata ['imgOutput'] = $this->request->post['imgOutput'];
			$pdata ['signature'] = $this->request->post['signature'];
			$pdata ['notes_pin'] = $this->request->post['notes_pin'];
			$pdata ['user_id'] = $this->request->post['user_id'];
			$pdata ['comments'] = $this->request->post['comments'];
			$pdata ['username'] = $this->request->post['username'];
			$pdata ['emp_tag_id'] = $this->request->post['emp_tag_id'];
			$pdata ['emp_tag_id_2'] = $this->request->post['emp_tag_id_2'];
			$fdata = array ();
			$fdata ['facilities_id'] = $this->request->post['facilities_id'];
			$fdata ['parent_facilities_id'] = $this->request->post['facilities_id'];
			$fdata ['formreturn_id'] = $this->request->post['formreturn_id'];
			$fdata ['tags_id'] = $this->request->post['tags_id'];
			$fdata ['forms_id'] = $this->request->post['forms_id'];
			$fdata ['case_status'] = $this->request->post['case_status'];

			//echo '<pre>'; print_r($fdata); echo '</pre>'; die;
			$this->load->model ( 'form/form' );
			$res = $this->model_form_form->newformsign ( $pdata, $fdata );

			if($res){
				$value = array (
					'status' => true,
					'message' => 'Form added successfully.'
				);
			}else{
				$value = array (
					'status' => false,
					'message' => 'Form not added.'
				);
			}
			//echo '<pre>'; print_r($value); echo '</pre>'; die;
			
			$this->response->setOutput ( json_encode ( $value ) );
			
		} catch ( Exception $e ) {
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
				'data' => 'Error in casefileservices jsonClassification ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_jsonClassification', $activity_data2 );
		}
	}	

	public function jsonDeletecase() {
		
		try {

			$pdata =array();

			//echo '<pre>'; print_r($_POST); echo '</pre>'; //die;

			$pdata ['imgOutput'] = $this->request->post['imgOutput'];
			$pdata ['signature'] = $this->request->post['signature'];
			$pdata ['notes_pin'] = $this->request->post['notes_pin'];
			$pdata ['user_id'] = $this->request->post['user_id'];
			$pdata ['comments'] = $this->request->post['comments'];
			$pdata ['username'] = $this->request->post['username'];
			$pdata ['emp_tag_id'] = $this->request->post['emp_tag_id'];
			$pdata ['emp_tag_id_2'] = $this->request->post['emp_tag_id_2'];

			
			//echo '<pre>'; print_r($pdata); echo '</pre>';  die;
			
			$fdata = array ();
			$fdata ['facilities_id'] = $this->request->post ['facilities_id'];
			$fdata ['forms_id'] = $this->request->post ['forms_id'];
			$fdata ['case_file_id'] = $this->request->post ['case_file_id'];
			$fdata ['tags_id'] = $this->request->post ['tags_id'];
			
			
			
			//echo '<pre>'; print_r($fdata); echo '</pre>'; die;
			$this->load->model ( 'form/form' );
			$res = $this->model_form_form->deletecase ( $pdata, $fdata );
			//echo '<pre>'; print_r($value); echo '</pre>'; die;
			if($res){
				$value = array (
					'status' => true,
					'message' => 'Case deleted successfully.'
				);
			}else{
				$value = array (
					'status' => false,
					'message' => 'Case not deleted.'
				);
			}
			$this->response->setOutput ( json_encode ( $value ) );
			
		} catch ( Exception $e ) {
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
				'data' => 'Error in casefileservices jsonDeletecase ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'jsonDeletecase', $activity_data2 );
		}
	}

	public function jsonChangestatus() {
		
		try {

			$pdata =array();

			

			$pdata ['imgOutput'] = $this->request->post['imgOutput'];
			$pdata ['signature'] = $this->request->post['signature'];
			$pdata ['notes_pin'] = $this->request->post['notes_pin'];
			$pdata ['user_id'] = $this->request->post['user_id'];
			$pdata ['comments'] = $this->request->post['comments'];
			$pdata ['username'] = $this->request->post['username'];
			$pdata ['emp_tag_id'] = $this->request->post['emp_tag_id'];
			$pdata ['emp_tag_id_2'] = $this->request->post['emp_tag_id_2'];

			
			//echo '<pre>'; print_r($pdata); echo '</pre>';  die;
			
			$fdata = array ();
			$fdata ['facilities_id'] = $this->request->post ['facilities_id'];
			$fdata ['facilitytimezone'] = $this->request->post['facilitytimezone'];
			$fdata ['case_status'] = $this->request->post ['case_status'];
			$fdata ['case_file_id'] = $this->request->post ['case_file_id'];
			$fdata ['tags_id'] = $this->request->post ['tags_id'];

			$msg='';
			if($fdata ['case_status']==1){
				$msg = 'closed';
			}
			if($fdata ['case_status']==2){
				$msg = 'Marked final';
			}
			
			//echo '<pre>'; print_r($fdata); echo '</pre>'; die;
			$this->load->model ( 'form/form' );
			$res = $this->model_form_form->change_case_status ( $pdata, $fdata );
			//echo '<pre>'; print_r($value); echo '</pre>'; die;
			if($res){
				$value = array (
					'status' => true,
					'message' => 'Case '.$msg.' successfully.'
				);
			}else{
				$value = array (
					'status' => false,
					'message' => 'Case not cosed.'
				);
			}
			$this->response->setOutput ( json_encode ( $value ) );
			
		} catch ( Exception $e ) {
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
				'data' => 'Error in casefileservices jsonChangestatus ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'jsonChangestatus', $activity_data2 );
		}
	}
}
 
 