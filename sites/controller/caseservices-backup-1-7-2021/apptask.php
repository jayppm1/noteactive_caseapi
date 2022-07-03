<?php
header ( 'Access-Control-Allow-Origin:' . $_SERVER ['HTTP_ORIGIN'] );
header ( 'Access-Control-Allow-Methods: POST, GET, OPTIONS' );
header ( 'Access-Control-Max-Age: 1000' );
header ( 'Access-Control-Allow-Headers: Content-Type' );
header ( 'Content-type: application/json;' );
header ( 'Content-Type: text/html; charset=utf-8' );
class Controllercaseservicesapptask extends Controller {
	
	public function jsonTasktype() {
		try {
			
			$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			/*
			
			$api_device_info = $this->model_api_encrypt->getdevicedetails ( $cre_array );
			
			if ($api_device_info == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$api_header_value = $this->model_api_encrypt->getallheaders1 ();
			
			if ($api_header_value == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			*/
			$this->data ['facilitiess'] = array ();
			$this->load->model ( 'createtask/createtask' );
			$results = $this->model_createtask_createtask->getTaskdetails ( $this->request->post ['facilities_id'] );
			if (! empty ( $results )) {
				foreach ( $results as $result ) {
					
					if ($result ['android_audio_file'] != NULL && $result ['android_audio_file'] != "") {
						$android_audio_file = HTTP_SERVER . 'image/ringtone/' . $result ['android_audio_file'];
					} else {
						$android_audio_file = '';
					}
					
					if ($result ['ios_audio_file'] != NULL && $result ['ios_audio_file'] != "") {
						$ios_audio_file = HTTP_SERVER . 'image/ringtone/' . $result ['ios_audio_file'];
					} else {
						$ios_audio_file = '';
					}
					
					if ($this->request->post ['config_taskform_status'] == 1) {
						
						$this->data ['facilitiess'] [] = array (
								// 'task_id' => $result['task_id'],
								'tasktype_name' => $result ['tasktype_name'],
								'enable_location' => $result ['enable_location'],
								'enable_location_tracking' => $result ['enable_location_tracking'],
								// 'status' => $result['status'],
								'is_android_notification' => $result ['is_android_notification'],
								'android_audio_file' => $android_audio_file,
								'is_android_snooze' => $result ['is_android_snooze'],
								'is_android_dismiss' => $result ['is_android_dismiss'],
								'is_ios_notification' => $result ['is_ios_notification'],
								'ios_audio_file' => $ios_audio_file,
								'is_ios_snooze' => $result ['is_ios_snooze'],
								'is_ios_dismiss' => $result ['is_ios_dismiss'],
								'enable_requires_approval' => $result ['enable_requires_approval'],
								'client_required' => $result ['client_required'],
								'field_required' => $result ['field_required'],
								'type' => $result ['type'] 
						)
						;
					} else {
						
						if ($result ['tasktype_name'] != 'Form') {
							$this->data ['facilitiess'] [] = array (
									// 'task_id' => $result['task_id'],
									'tasktype_name' => $result ['tasktype_name'],
									'enable_location' => $result ['enable_location'],
									'enable_location_tracking' => $result ['enable_location_tracking'],
									// 'status' => $result['status'],
									'is_android_notification' => $result ['is_android_notification'],
									'android_audio_file' => $android_audio_file,
									'is_android_snooze' => $result ['is_android_snooze'],
									'is_android_dismiss' => $result ['is_android_dismiss'],
									'is_ios_notification' => $result ['is_ios_notification'],
									'ios_audio_file' => $ios_audio_file,
									'is_ios_snooze' => $result ['is_ios_snooze'],
									'is_ios_dismiss' => $result ['is_ios_dismiss'],
									'enable_requires_approval' => $result ['enable_requires_approval'],
									'client_required' => $result ['client_required'],
									'field_required' => $result ['field_required'],
									'type' => $result ['type'] 
							)
							;
						}
					}
				}
				
				$value = array (
						'results' => $this->data ['facilitiess'],
						'status' => true 
				);
				/* echo json_encode($value); */
				$this->response->setOutput ( json_encode ( $value ) );
			} else {
				$this->data ['facilitiess'] [] = array (
						'warning' => "Task Type not found" 
				);
				$error = false;
				
				$value = array (
						'results' => $this->data ['facilitiess'],
						'status' => $error 
				);
				
				$this->response->setOutput ( json_encode ( $value ) );
			}
		} catch ( Exception $e ) {
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in apptask jsonTasktype ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_jsonTasktype', $activity_data2 );
			// echo 'Caught exception: ', $e->getMessage(), "\n";
		}
	}
	public function jsonInterval() {
		try {
			
			$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			
			$api_device_info = $this->model_api_encrypt->getdevicedetails ( $cre_array );
			
			if ($api_device_info == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$api_header_value = $this->model_api_encrypt->getallheaders1 ();
			
			if ($api_header_value == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$this->data ['facilitiess'] = array ();
			$this->load->model ( 'createtask/createtask' );
			$results = $this->model_createtask_createtask->getTaskintervals ( $this->request->post ['facilities_id'] );
			
			if (! empty ( $results )) {
				foreach ( $results as $result ) {
					
					$this->data ['facilitiess'] [] = array (
							// 'interval_id' => $result['interval_id'],
							'interval_name' => $result ['interval_name'],
							'interval_value' => $result ['interval_value'] 
					)
					// 'status' => $result['status'],
					
					;
				}
				
				$value = array (
						'results' => $this->data ['facilitiess'],
						'status' => true 
				);
				/* echo json_encode($value); */
				$this->response->setOutput ( json_encode ( $value ) );
			} else {
				$this->data ['facilitiess'] [] = array (
						'warning' => "Interval not found" 
				);
				$error = false;
				
				$value = array (
						'results' => $this->data ['facilitiess'],
						'status' => $error 
				);
				
				$this->response->setOutput ( json_encode ( $value ) );
			}
		} catch ( Exception $e ) {
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in apptask jsonInterval ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_jsonInterval', $activity_data2 );
			// echo 'Caught exception: ', $e->getMessage(), "\n";
		}
	}
	public function jsonTasklist() {
		try {
			
			/*
			 * $this->load->model('api/encrypt');
			 * $cre_array = array();
			 * $cre_array['phone_device_id'] = $this->request->post['phone_device_id'];
			 * $cre_array['facilities_id'] = $this->request->post['facilities_id'];
			 *
			 * $api_device_info = $this->model_api_encrypt->getdevicedetails($cre_array);
			 *
			 * if($api_device_info == false){
			 * $errorMessage = $this->model_api_encrypt->errorMessage();
			 * return $errorMessage;
			 * }
			 *
			 * $api_header_value = $this->model_api_encrypt->getallheaders1();
			 *
			 * if($api_header_value == false){
			 * $errorMessage = $this->model_api_encrypt->errorMessage();
			 * return $errorMessage;
			 * }
			 */
			
			$this->data ['listtask'] = array ();
			$this->data ['listtask2'] = array ();
			
			if($this->request->post ['tags_id'] != null && $this->request->post ['tags_id'] != ""){
				$tags_id = $this->request->post ['tags_id'];
			}else{
				$tags_id = $this->request->get ['tags_id'];
			}
			
				$this->load->model ( 'setting/tags' );
				$tags_info = $this->model_setting_tags->getTag($tags_id);


				if($this->request->post ['facilities_id'] !=NULL && $this->request->post ['facilities_id'] !=""){
					$facilities_id = $this->request->post ['facilities_id'];
				}else{
					$facilities_id = $tags_info['facilities_id'];
				}
			
			if ($facilities_id != null && $facilities_id != "") {
				$this->load->model ( 'facilities/facilities' );
				$facilities_info = $this->model_facilities_facilities->getfacilities ( $facilities_id );
				
				$this->load->model ( 'setting/timezone' );
				$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
				$facilitytimezone = $timezone_info['timezone_value'];
				
				if ($facilities_info ['config_task_status'] == '1') {
					
					if (isset ( $facilitytimezone )) {
						$facilities_timezone = $facilitytimezone;
						date_default_timezone_set ( $facilities_timezone );
					}
					
					if (isset ( $this->request->post ['currentdate'] )) {
						$date = str_replace ( '-', '/', $this->request->post ['currentdate'] );
						$res = explode ( "/", $date );
						
						$changedDate = $res [1] . "-" . $res [0] . "-" . $res [2];
						
						$currentdate = $changedDate;
					} else {
						$currentdate = date ( 'd-m-Y' );
					}
					
					if (isset ( $this->request->post ['facilities_id'] )) {
						$facilities_id = $this->request->post ['facilities_id'];
					}
					
					$this->data ['deleteTime'] = $deleteTime;
					$this->load->model ( 'createtask/createtask' );
					$top = '2';
					$listtasks = $this->model_createtask_createtask->getTasklist ( $facilities_id, $currentdate, $top, $tags_id );
					
					$tasktypes = $this->model_createtask_createtask->getTaskdetails ( $facilities_id );
					
					
					// var_dump($tasktypes);
					
					foreach ( $tasktypes as $tasktype ) {
						
						$taskTotal1 = $this->model_createtask_createtask->getCountTasklist ( $facilities_id, $currentdate, $top, $facilitytimezone, $tags_id, $tasktype ['task_id'] );
						
						$taskTotal = $taskTotal + $taskTotal1;
					}
					
					date_default_timezone_set ( $facilitytimezone );
					$currenttime = date ( 'H:i:s', strtotime ( 'now' ) );
					
					$currentdate = date ( 'Y-m-d', strtotime ( 'now' ) );
					
					$this->load->model ( 'setting/locations' );
					$this->load->model ( 'setting/tags' );
					
					$this->load->model ( 'notes/notes' );
					
					foreach ( $listtasks as $list ) {
						
						$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName ( $list ['tasktype'],$list['facilityId'] );
						
						$tasktypetype = $tasktype_info ['type'];
						$is_task_rule = $tasktype_info ['is_task_rule'];
						
						if ($tasktype_info ['display_custom_list'] == '1') {
							$display_custom_list = $tasktype_info ['display_custom_list'];
						} else {
							$display_custom_list = 0;
						}
						
						if ($tasktype_info ['custom_completion_rule'] == '1') {
							$addTime = $tasktype_info ['config_task_complete'];
						} else {
							$addTime = $this->config->get ( 'config_task_complete' );
						}
						
						$currenttimePlus = date ( 'Y-m-d H:i:s', strtotime ( ' +' . $addTime . ' minutes', strtotime ( 'now' ) ) );
						
						$taskstarttime1111 = date ( 'H:i:s', strtotime ( $list ['task_time'] ) );
						
						$taskstarttime11 = date ( 'Y-m-d', strtotime ( $list ['task_date'] ) );
						$taskstarttime = $taskstarttime11 . ' ' . $taskstarttime1111;
						
						
						$taskstartime = date ( 'h:i a', strtotime ( ' -' . $addTime . ' minutes',  strtotime ( $list ['task_time'] ) ) );
						
						if($is_task_rule != '1'){
							if ($tasktypetype != '5') {
								if ($currenttimePlus >= $taskstarttime) {
									$taskDuration = '1';
								} else {
									$taskDuration = '2';
								}
							} else {
								$taskDuration = '1';
							}
						}else{
							$taskDuration = '1';
						}
						
						if (strlen ( $list ['description'] ) > 50) {
							$description_more = '1';
						} else {
							$description_more = '0';
						}
						
						$url2 = "";
						if ($list ['formreturn_id'] > 0) {
							$url2 .= '&forms_id=' . $list ['formreturn_id'];
							
							$this->load->model ( 'form/form' );
							$result_info = $this->model_form_form->getFormDatas ( $list ['formreturn_id'] );
							if ($result_info ['notes_id'] != null && $result_info ['notes_id'] != "") {
								$url2 .= '&notes_id=' . $result_info ['notes_id'];
							}
						}
						
						if ($list ['checklist'] == "incident_form") {
							$form_url = str_replace ( '&amp;', '&', $this->url->link ( 'services/noteform/taskforminsert', '' . 'task_id=' . $list ['id'] . '&facilities_id=' . $list ['facilityId'], 'SSL' ) );
						} else if ($list ['checklist'] == "bed_check") {
							$form_url = str_replace ( '&amp;', '&', $this->url->link ( 'services/noteform/checklistform', '' . 'task_id=' . $list ['id'] . '&facilities_id=' . $list ['facilityId'], 'SSL' ) );
						} elseif (is_numeric ( $list ['checklist'] )) {
							$form_url = str_replace ( '&amp;', '&', $this->url->link ( 'services/form', '' . 'forms_design_id=' . $list ['checklist'] . '&task_id=' . $list ['id'] . '&facilities_id=' . $list ['facilityId'] . $url2 ) );
						} elseif ($list ['attachement_form'] == '1') {
							$form_url = str_replace ( '&amp;', '&', $this->url->link ( 'services/form', '' . 'forms_design_id=' . $list ['tasktype_form_id'] . '&task_id=' . $list ['id'] . '&facilities_id=' . $list ['facilityId'] . $url2 ) );
						} else {
							$form_url = '';
						}
						
						$bedcheckdata = array ();
						
						if ($list ['task_form_id'] != 0 && $list ['task_form_id'] != NULL) {
							
							if ($list ['bed_check_location_ids'] != null && $list ['bed_check_location_ids'] != "") {
								$formDatas = $this->model_setting_locations->getformid2 ( $list ['bed_check_location_ids'] );
							} else {
								$formDatas = $this->model_setting_locations->getformid ( $list ['task_form_id'] );
							}
							
							foreach ( $formDatas as $formData ) {
								
								$locData = $this->model_setting_locations->getlocation ( $formData ['locations_id'] );
								
								$locationDatab = array ();
								
								$locationDatab [] = array (
										'locations_id' => $locData ['locations_id'],
										'location_name' => $locData ['location_name'],
										'location_address' => $locData ['location_address'],
										'location_detail' => $locData ['location_detail'],
										'capacity' => $locData ['capacity'],
										'location_type' => $locData ['location_type'],
										'nfc_location_tag' => $locData ['nfc_location_tag'],
										'nfc_location_tag_required' => $locData ['nfc_location_tag_required'],
										// 'gps_location_tag' =>$locData['gps_location_tag'],
										// 'gps_location_tag_required' =>$locData['gps_location_tag_required'],
										'latitude' => $locData ['latitude'],
										'longitude' => $locData ['longitude'],
										// 'other_location_tag' =>$locData['other_location_tag'],
										// 'other_location_tag_required' =>$locData['other_location_tag_required'],
										'other_type_id' => $locData ['other_type_id'],
										'facilities_id' => $locData ['facilities_id'] 
								)
								;
								
								$bedcheckdata [] = array (
										'task_form_location_id' => $formData ['task_form_location_id'],
										'location_name' => $formData ['location_name'],
										'location_detail' => $formData ['location_detail'],
										'current_occupency' => $formData ['current_occupency'],
										'bedcheck_locations' => $locationDatab 
								);
							}
							
							/*
							 * $this->load->model('setting/bedchecktaskform');
							 * $taskformData = $this->model_setting_bedchecktaskform->getbedchecktaskform($list['task_form_id']);
							 *
							 * foreach($taskformData as $frmData){
							 * $taskformsData[] = array(
							 * 'task_form_name' =>$frmData['task_form_name'],
							 * 'facilities_id' =>$frmData['facilities_id'],
							 * 'form_type' =>$frmData['form_type']
							 * );
							 * }
							 */
						}
						
						/*
						 * if($list['tags_id'] != 0 && $list['tags_id'] != NULL ){
						 * $tags_info = $this->model_setting_tags->getTag($list['tags_id']);
						 * $locationData = array();
						 * $locData = $this->model_setting_locations->getlocation($tags_info['locations_id']);
						 *
						 * $locationData[] = array(
						 * 'locations_id' =>$locData['locations_id'],
						 * 'location_name' =>$locData['location_name'],
						 * 'location_address' =>$locData['location_address'],
						 * 'location_detail' =>$locData['location_detail'],
						 * 'capacity' =>$locData['capacity'],
						 * 'location_type' =>$locData['location_type'],
						 * 'nfc_location_tag' =>$locData['nfc_location_tag'],
						 * 'nfc_location_tag_required' =>$locData['nfc_location_tag_required'],
						 * 'gps_location_tag' =>$locData['gps_location_tag'],
						 * 'gps_location_tag_required' =>$locData['gps_location_tag_required'],
						 * 'latitude' =>$locData['latitude'],
						 * 'longitude' =>$locData['longitude'],
						 * 'other_location_tag' =>$locData['other_location_tag'],
						 * 'other_location_tag_required' =>$locData['other_location_tag_required'],
						 * 'other_type_id' =>$locData['other_type_id'],
						 * 'facilities_id' =>$locData['facilities_id']
						 *
						 * );
						 *
						 *
						 * if($tags_info['upload_file'] != null && $tags_info['upload_file'] != ""){
						 * $upload_file2 = $tags_info['upload_file'];
						 * }else{
						 * $upload_file2 = "";
						 * }
						 *
						 *
						 * $drugaData = array();
						 * $drugDatas = $this->model_setting_tags->getDrugs($list['id']);
						 *
						 * foreach($drugDatas as $drugData){
						 * $drugaData[] = array(
						 * 'createtask_by_group_id' =>$drugData['createtask_by_group_id'],
						 * 'facilities_id' =>$drugData['facilities_id'],
						 * 'locations_id' =>$drugData['locations_id'],
						 * 'tags_id' =>$drugData['tags_id'],
						 * 'medication_id' =>$drugData['medication_id'],
						 * 'drug_name' =>$drugData['drug_name'],
						 * 'dose' =>$drugData['dose'],
						 * 'drug_type' =>$drugData['drug_type'],
						 * 'quantity' =>$drugData['quantity'],
						 * 'frequency' =>$drugData['frequency'],
						 * 'start_time' =>$drugData['start_time'],
						 * 'instructions' =>$drugData['instructions'],
						 * 'count' =>$drugData['count'],
						 * 'complete_status' =>$drugData['complete_status'],
						 * 'upload_file' =>$upload_file2,
						 * );
						 * }
						 *
						 *
						 * $medications[] = array(
						 * 'tags_id' =>$tags_info['tags_id'],
						 * 'upload_file' =>$upload_file2,
						 * 'emp_tag_id' =>$tags_info['emp_tag_id'],
						 * 'emp_first_name' =>$tags_info['emp_first_name'],
						 * 'tags_pin' =>$tags_info['tags_pin'],
						 * 'emp_last_name' =>$tags_info['emp_last_name'],
						 * 'doctor_name' =>$tags_info['doctor_name'],
						 * 'emergency_contact' =>$tags_info['emergency_contact'],
						 * 'dob' =>$tags_info['dob'],
						 * 'medications_locations' =>$locationData,
						 * 'medications_drugs' =>$drugaData
						 * );
						 *
						 * }
						 */
						
						$this->data ['transport_tags'] = array ();
						$this->load->model ( 'setting/tags' );
						
						if (! empty ( $list ['transport_tags'] )) {
							$transport_tags1 = explode ( ',', $list ['transport_tags'] );
						} else {
							$transport_tags1 = array ();
						}
						$transport_tags = array ();
						foreach ( $transport_tags1 as $tag1 ) {
							$tags_info = $this->model_setting_tags->getTag ( $tag1 );
							
							if ($tags_info ['emp_first_name']) {
								$emp_tag_id = $tags_info ['emp_tag_id'] . ': ' . $tags_info ['emp_first_name'] . ' ' . $tags_info ['emp_last_name'];
							} else {
								$emp_tag_id = $tags_info ['emp_tag_id'];
							}
							
							if ($tags_info) {
								$transport_tags [] = array (
										'tags_id' => $tags_info ['tags_id'],
										'emp_tag_id' => $emp_tag_id,
										'emp_first_name' => $tags_info ['emp_first_name'],
										'emp_last_name' => $tags_info ['emp_last_name'] 
								);
							}
						}
						
						/*
						 * if($list['iswaypoint'] == '1'){
						 * $transport_tags[] = array(
						 * 'tags_id' => 'Yes',
						 * 'emp_tag_id' => 'Round Trip'
						 * );
						 * }
						 */
						
						$medication_tags = array ();
						$this->data ['medication_tags'] = array ();
						$this->load->model ( 'setting/tags' );
						
						if (! empty ( $list ['medication_tags'] )) {
							$medication_tags1 = explode ( ',', $list ['medication_tags'] );
						} else {
							$medication_tags1 = array ();
						}
						
						foreach ( $medication_tags1 as $medicationtag ) {
							$tags_info1 = $this->model_setting_tags->getTag ( $medicationtag );
							
							if ($tags_info1 ['emp_first_name']) {
								$emp_tag_id = $tags_info1 ['emp_tag_id'] . ': ' . $tags_info1 ['emp_first_name'] . ' ' . $tags_info1 ['emp_last_name'];
							} else {
								$emp_tag_id = $tags_info1 ['emp_tag_id'];
							}
							
							if ($tags_info1) {
								
								$drugs = array ();
								
								$mdrugs = $this->model_setting_tags->getTagsMedicationdetailsByID ( $list ['id'], $medicationtag );
								
								foreach ( $mdrugs as $mdrug ) {
									
									$mdrug_info = $this->model_setting_tags->getTagsMedicationdruglByID ( $mdrug ['tags_medication_details_id'] );
									// $medicine_info = $this->model_setting_tags->getTagsMedicationlByID($mdrug_info['tags_medication_id']);
									if($mdrug_info ['drug_name'] != null && $mdrug_info ['drug_name'] != ""){
										$drugs [] = array (
												'tags_medication_details_id' => $mdrug ['tags_medication_details_id'],
												'drug_name' => $mdrug_info ['drug_name'],
												'tags_medication_id' => $mdrug_info ['tags_medication_id'],
												'drug_mg' => $mdrug_info ['drug_mg'],
												'drug_alertnate' => $mdrug_info ['drug_alertnate'],
												'drug_prn' => $mdrug_info ['drug_prn'],
												'instructions' => $mdrug_info ['instructions'],
												'drug_am' => date ( 'h:i A', strtotime ( $mdrug_info ['drug_am'] ) ),
												'drug_pm' => date ( 'h:i A', strtotime ( $mdrug_info ['drug_pm'] ) ) 
										);
									}
								}
								
								$medication_tags [] = array (
										'tags_id' => $tags_info1 ['tags_id'],
										'emp_first_name' => $tags_info1 ['emp_first_name'],
										'emp_last_name' => $tags_info1 ['emp_last_name'],
										'emp_tag_id' => $emp_tag_id,
										'tagsmedications' => $drugs 
								);
							}
						}
						
						if ($list ['visitation_tag_id']) {
							$visitation_tag = $this->model_setting_tags->getTag ( $list ['visitation_tag_id'] );
							
							if ($visitation_tag ['emp_first_name']) {
								$visitation_tag_id = $visitation_tag ['emp_tag_id'] . ': ' . $visitation_tag ['emp_first_name'] . ' ' . $visitation_tag ['emp_last_name'];
							} else {
								$visitation_tag_id = $visitation_tag ['emp_tag_id'];
							}
						}
						
						$customlists1 = array ();
						$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName ( $list ['tasktype'],$list['facilityId'] );
						$tasktype_id = $tasktype_info ['task_id'];
						
						if ($tasktype_info ['customlist_id']) {
							
							$d = array ();
							$d ['customlist_id'] = $tasktype_info ['customlist_id'];
							$customlists = $this->model_notes_notes->getcustomlists ( $d );
							
							if ($customlists) {
								foreach ( $customlists as $customlist ) {
									$d2 = array ();
									$d2 ['customlist_id'] = $customlist ['customlist_id'];
									
									$customlistvalues = $this->model_notes_notes->getcustomlistvalues ( $d2 );
									
									$customlists1 [] = array (
											'customlist_id' => $customlist ['customlist_id'],
											'customlist_name' => $customlist ['customlist_name'],
											'customlistvalues' => $customlistvalues 
									);
								}
							}
						}
						
						if ($list ['emp_tag_id']) {
							$visitation_tag = $this->model_setting_tags->getTag ( $list ['emp_tag_id'] );
							
							if ($visitation_tag ['emp_first_name']) {
								$emp_tag_id = $visitation_tag ['emp_last_name'] . ', ' . $visitation_tag ['emp_first_name'];
							} else {
								$emp_tag_id = $visitation_tag ['emp_tag_id'];
							}
							
							$rresults = $this->model_setting_locations->getlocation ( $visitation_tag ['room'] );
							$location_name = " | ".$rresults ['location_name'];
							
							$emp_extid = ' | '.$visitation_tag ['emp_extid'];
							$ssn = $visitation_tag ['ssn'];
				
						} else {
							$emp_tag_id = "";
							$location_name = "";
							$emp_extid = "";
							$ssn = "";
						}
						
							$tags_infoNew = $this->model_setting_tags->getTag ( $list ['tags_id'] );
							
							if ($tags_infoNew ['emp_first_name']) {
								$emp_tagNew = $tags_infoNew ['emp_first_name'] . ' ' . $tags_infoNew ['emp_last_name'];
							} else {
								$emp_tagNew = $tags_infoNew ['emp_tag_id'];
							}
							
						
						$this->data ['listtask'] [] = array (
								'assign_to' => $list ['assign_to'],
								'emp_tag_id' => $emp_tag_id,
								'location_name' => $location_name,
								'emp_extid' => $emp_extid,
								'ssn' => $ssn,
								'required_assign' => $list ['required_assign'],
								'facilities_id' => $list ['facilityId'],
								'display_custom_list' => $display_custom_list,
								'task_group_by' => $list ['task_group_by'],
								'iswaypoint' => $list ['iswaypoint'],
								'enable_requires_approval' => $list ['enable_requires_approval'],
								'is_approval_required_forms_id' => $list ['is_approval_required_forms_id'],
								'recurrence' => $list ['recurrence'],
								'is_transport' => $list ['is_transport'],
								'attachement_form' => $list ['attachement_form'],
								'tasktype_form_id' => $list ['tasktype_form_id'],
								'tasktype' => $list ['tasktype'],
								'checklist' => $list ['checklist'],
								'task_complettion' => $list ['task_complettion'],
								'device_id' => $list ['device_id'],
								'date' => date ( 'j, M Y', strtotime ( $list ['task_date'] ) ),
								'id' => $list ['id'],
								'description' => html_entity_decode ( str_replace ( '&#039;', '\'', $list ['description'] ) ).' '.$emp_tag_id.' '.$ssn.' '.$location_name,
								'description_more' => $description_more,
								'taskDuration' => $taskDuration,
								'taskstarttime1111' => $taskstartime,
								'tasktagcolor' =>  $tasktagcolor,
								
								
								'client_name' => $emp_tagNew,
								
								
								'task_time' => date ( 'h:i A', strtotime ( $list ['task_time'] ) ),
								'checklist_url' => $form_url,
								'task_form_id' => $list ['task_form_id'],
								'tags_id' => $list ['tags_id'],
								'pickup_facilities_id' => $list ['pickup_facilities_id'],
								'pickup_locations_address' => $list ['pickup_locations_address'],
								'pickup_locations_time' => $list ['pickup_locations_time'],
								'pickup_locations_latitude' => $list ['pickup_locations_latitude'],
								'pickup_locations_longitude' => $list ['pickup_locations_longitude'],
								'dropoff_facilities_id' => $list ['dropoff_facilities_id'],
								'dropoff_locations_address' => $list ['dropoff_locations_address'],
								'dropoff_locations_time' => $list ['dropoff_locations_time'],
								'dropoff_locations_latitude' => $list ['dropoff_locations_latitude'],
								'dropoff_locations_longitude' => $list ['dropoff_locations_longitude'],
								// 'transport_tags' =>$list['transport_tags'],
								// 'medications' =>$medications,
								// 'bedchecks' =>$bedcheckdata,
								
								'transport_tags' => $transport_tags,
								'medications' => $medications,
								'bedchecks' => $bedcheckdata,
								'medication_tags' => $medication_tags,
								
								'visitation_tags' => $list ['visitation_tags'],
								'visitation_tag_id' => $visitation_tag_id,
								'visitation_start_facilities_id' => $list ['visitation_start_facilities_id'],
								'visitation_start_address' => $list ['visitation_start_address'],
								'visitation_start_time' => date ( 'h:i A', strtotime ( $list ['visitation_start_time'] ) ),
								'visitation_start_address_latitude' => $list ['visitation_start_address_latitude'],
								'visitation_start_address_longitude' => $list ['visitation_start_address_longitude'],
								'visitation_appoitment_facilities_id' => $list ['visitation_appoitment_facilities_id'],
								'visitation_appoitment_address' => $list ['visitation_appoitment_address'],
								'visitation_appoitment_time' => date ( 'h:i A', strtotime ( $list ['visitation_appoitment_time'] ) ),
								'visitation_appoitment_address_latitude' => $list ['visitation_appoitment_address_latitude'],
								'visitation_appoitment_address_longitude' => $list ['visitation_appoitment_address_longitude'],
								'customlists' => $customlists1 
						)
						;
					}
				} else {
					$this->data ['listtask'] [] = array (
							'warning' => '0' 
					);
					$taskTotal = '0';
				}
			} else {
				$this->data ['listtask'] [] = array (
						'warning' => '0' 
				);
				$taskTotal = '0';
			}
			$value = array (
					'results' => $this->data ['listtask'],
					'Tasktotal' => $taskTotal,
					'status' => true 
			);
			/* echo json_encode($value); */
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in apptask jsonTasklist ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_jsonTasklist', $activity_data2 );
			// echo 'Caught exception: ', $e->getMessage(), "\n";
		}
	}
	public function jsonAddTasknote() {
		try {
			
			$this->load->model ( 'activity/activity' );
			$this->model_activity_activity->addActivitySave ( 'jsonAddTasknote', $this->request->post, 'request' );
			
			/*
			$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			
			$api_device_info = $this->model_api_encrypt->getdevicedetails ( $cre_array );
			
			if ($api_device_info == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$api_header_value = $this->model_api_encrypt->getallheaders1 ();
			
			if ($api_header_value == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}	
			
			*/
			
			$this->data ['facilitiess'] = array ();
			$json = array ();
			
			if (! $this->request->post ['description']) {
				$json ['warning'] = 'Please insert required!.';
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
			
			if ($this->request->post ['recurrence'] == 'weekly') {
				if ($this->request->post ['recurnce_week'] == null && $this->request->post ['recurnce_week'] == "") {
					$json ['warning'] = 'Please check the weekly day';
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
			
			/*
			 * if($this->request->post['recurrence'] == "hourly"){
			 * if($this->request->post['endtime'] != null && $this->request->post['endtime'] != ""){
			 * $taskTime = strtotime($this->request->post['taskTime']);
			 * $endtime = strtotime($this->request->post['endtime']);
			 *
			 * if($taskTime > $endtime){
			 * $json['warning'] = "Please select correct end time";
			 * }
			 * }
			 * }
			 */
			
			/*
			 * if($this->request->post['recurrence'] != "none"){
			 * if($this->request->post['end_recurrence_date'] != null && $this->request->post['end_recurrence_date'] != ""){
			 *
			 * $date = str_replace('-', '/', $this->request->post['task_date']);
			 * $res = explode("/", $date);
			 * $changedDate = $res[2]."-".$res[0]."-".$res[1];
			 *
			 * $date2 = str_replace('-', '/', $this->request->post['end_recurrence_date']);
			 * $res2 = explode("/", $date2);
			 * $changedDate2 = $res2[2]."-".$res2[0]."-".$res2[1];
			 *
			 * $task_date = strtotime($changedDate);
			 * $end_recurrence_date = strtotime($changedDate2);
			 *
			 *
			 *
			 * if($task_date > $end_recurrence_date){
			 * $json['warning'] = "Please select correct end date";
			 * }
			 * }
			 * }
			 */
			
			if ($this->request->post ['recurrence'] == "hourly") {
				if ($this->request->post ['endtime'] != null && $this->request->post ['endtime'] != "") {
					
					if ($this->request->post ['recurrence'] == 'hourly') {
						$recurnce_hrly = $this->request->post ['recurnce_hrly'];
						
						$taskDate = $this->request->post ['task_date'];
						$date = str_replace ( '-', '/', $taskDate );
						$res = explode ( "/", $date );
						$changedDate = $res [2] . "-" . $res [0] . "-" . $res [1];
						
						$newdate1 = date ( 'H:i:s', strtotime ( $this->request->post ['taskTime'] ) );
						$endnewdate1 = date ( 'H:i:s', strtotime ( $this->request->post ['endtime'] ) );
						
						$time1 = strtotime ( $newdate1 );
						$time2 = strtotime ( $endnewdate1 );
						$difference = round ( abs ( $time2 - $time1 ) / 3600, 2 );
						
						date_default_timezone_set ( $this->request->post ['facilitytimezone'] );
						
						$current_time = date ( "H:i:s" );
						
						if ($current_time > $endnewdate1) {
							$total_hour = 24 - $difference;
						} else {
							$total_hour = $difference;
						}
						
						$newdate = $this->request->post ['taskTime'];
						$endnewdate = $this->request->post ['endtime'];
						
						$taskTime = date ( 'm-d-Y H:i:s', strtotime ( $newdate ) );
						$endtime = date ( 'm-d-Y H:i:s', strtotime ( $endnewdate ) );
					} else {
						$taskTime = strtotime ( $this->request->post ['taskTime'] );
						$endtime = strtotime ( $this->request->post ['endtime'] );
						
						if ($taskTime > $endtime) {
							$json ['warning'] = "Please select correct end time";
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
				}
			}
			
			/*if ($this->request->post ['assignto'] != '') {
				$this->load->model ( 'user/user' );
				$user_info = $this->model_user_user->getUser ( $this->request->post ['assignto'] );
				
				if (empty ( $user_info )) {
					$json ['warning'] = 'incorrect username';
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
			}*/
			
			/*if (($this->request->post ['alert_type_email'] == '1') || ($this->request->post ['assignto_sms'] == '1')) {
				
				if ($this->request->post ['assignto'] == '') {
					$json ['warning'] = 'Required username';
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
			
			if ($this->request->post ['assignto_sms'] != null && $this->request->post ['assignto_sms'] != "") {
				$this->load->model ( 'user/user' );
				$userphone = $this->model_user_user->getUserByPhone ( $this->request->post ['assignto_sms'] );
				if ($userphone != "0" && $userphone != NULL) {
					$json ['warning'] = "Phone number already exists";
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
			
			if ($this->request->post ['assignto_email'] != null && $this->request->post ['assignto_email'] != "") {
				
				$this->load->model ( 'user/user' );
				$useremail = $this->model_user_user->getTotalUsersByEmail ( $this->request->post ['assignto_email'] );
				if ($useremail > '0') {
					$json ['warning'] = "Email already exists";
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
			}*/
			
			if ($this->request->post ['tasktype'] == "11") {
				if ($this->request->post ['task_form_id'] == null && $this->request->post ['task_form_id'] == "") {
					$json ['warning'] = 'Warning: Select Bed Check Form';
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
			
			if ($this->request->post ['tasktype'] == "8") {
				if ($this->request->post ['numChecklist'] == null && $this->request->post ['numChecklist'] == "") {
					$json ['warning'] = 'Warning: Select Form';
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
			
			if ($this->request->post ['tasktype'] == "10") {
				if ($this->request->post ['pickup_locations_address'] == null && $this->request->post ['pickup_locations_address'] == "") {
					$json ['warning'] = 'Warning: Enter Location Pickup Address';
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
				/*
				 * if($this->request->post['pickup_locations_time'] == null && $this->request->post['pickup_locations_time'] == ""){
				 * $json['warning'] = 'Warning: Enter Location Pickup Time';
				 * }
				 */
				
				if ($this->request->post ['dropoff_locations_address'] == null && $this->request->post ['dropoff_locations_address'] == "") {
					$json ['warning'] = 'Warning: Enter Location Dropoff Address';
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
				/*
				 * if($this->request->post['dropoff_locations_time'] == null && $this->request->post['dropoff_locations_time'] == ""){
				 * $json['warning'] = 'Warning: Enter Location Dropoff Time';
				 * }
				 */
			}
			
			if ($this->request->post ['completion_alert'] == '1') {
				if (($this->request->post ['completion_alert_type_sms'] == '') && ($this->request->post ['completion_alert_type_email'] == '')) {
					$json ['warning'] = 'Completion Alert Type is required field';
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
				
				if (($this->request->post ['completion_alert_type_sms'] != '') || ($this->request->post ['completion_alert_type_email'] != '')) {
					if (($this->request->post ['user_roles'] == '') && ($this->request->post ['userids'] == '')) {
						$json ['warning'] = 'Please select a Role or a User for Completion Notification';
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
			}
			
			$this->load->model ( 'createtask/createtask' );
			$tasktype_info = $this->model_createtask_createtask->gettasktyperow ( $this->request->post ['tasktype'] );
			
			if ($tasktype_info ['client_required'] == '1') {
				if (($this->request->post ['emp_tag_id'] == null) && ($this->request->post ['emp_tag_id'] == '')) {
					$json ['warning'] = 'Client is required field';
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
			
			if ($tasktype_info ['field_required'] == '1') {
				if (($this->request->post ['emp_tag_id1'] == null) && ($this->request->post ['emp_tag_id1'] == '')) {
					$json ['warning'] = 'Client field is required';
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
			
			/*
			 * if ($this->request->post['recurrence'] == 'Perpetual') {
			 * if ($this->request->post['tasktype'] == '25') {
			 * if (($this->request->post['emp_tag_id1'] == '') && ($this->request->post['emp_tag_id1'] == '')) {
			 * $json['warning'] = 'Client is required field';
			 * $facilitiessee = array();
			 * $facilitiessee[] = array(
			 * 'warning' => $json['warning'],
			 * );
			 * $error = false;
			 *
			 * $value = array('results'=>$facilitiessee,'status'=>false);
			 *
			 * return $this->response->setOutput(json_encode($value));
			 * }
			 * }
			 *
			 * }
			 */
			
			if ($this->request->post ['attachement_form'] == '1') {
				if (empty ( $this->request->post ['tasktype_form_id'] )) {
					$json ['warning'] = 'Warning: Select Form';
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
			
			if ($this->request->post ['recurrence'] == "none" || $this->request->post ['recurrence'] == "hourly" || $this->request->post ['daily'] == "hourly") {
				// if($this->request->post['tasktype'] != "10"){
				if ($this->request->post ['taskTime'] != null && $this->request->post ['taskTime'] != "") {
					date_default_timezone_set ( $this->request->post ['facilitytimezone'] );
					
					$tasksTiming = date ( 'H:i:s', strtotime ( $this->request->post ['taskTime'] ) );
					
					$taskTime = strtotime ( $tasksTiming );
					
					// var_dump($taskTime);
					
					$current_time = date ( "h:i A" );
					$current_date = date ( "Y-m-d" );
					
					$tasksTiming2 = date ( 'H:i:s', strtotime ( $current_time ) );
					
					// var_dump($tasksTiming2);
					$current_time1 = date ( 'H:i:s', strtotime ( $tasksTiming2 ) );
					// var_dump($current_time1);
					
					$date1 = str_replace ( '-', '/', $this->request->post ['task_date'] );
					$res1 = explode ( "/", $date1 );
					$changedDate1 = $res1 [2] . "-" . $res1 [0] . "-" . $res1 [1];
					
					$recurnce_hrly = 3;
					$taskTime1 = date ( 'H:i:s', strtotime ( ' +' . $recurnce_hrly . ' minutes', strtotime ( $this->request->post ['taskTime'] ) ) );
					
					$taskTime = date ( 'H:i:s', strtotime ( $taskTime1 ) );
					
					if ($current_date == $changedDate1) {
						
						// if($this->request->post['tasktype'] != '10'){
						if ($this->request->post ['recurrence'] == 'daily') {
							// var_dump($this->request->post['daily_times']);
							
							if ($this->request->post ['daily_times'] != null && $this->request->post ['daily_times'] != "") {
								/*
								 * foreach($this->request->post['daily_times'] as $daily_time){
								 * $daily_time11 = date('H:i:s', strtotime($daily_time));
								 * $daily_time1 = date('h:i A', strtotime($daily_time11));
								 * //var_dump($daily_time1);
								 * //$daily_time1 = strtotime($daily_time);
								 *
								 * if($current_time1 > $daily_time1){
								 * $json['warning'] = "The Task Time must be greater than the Current Time";
								 * }
								 * }
								 */
							} else {
								
								$daily_endtime1 = date ( 'H:i:s', strtotime ( ' +' . $recurnce_hrly . ' minutes', strtotime ( $this->request->post ['daily_endtime'] ) ) );
								
								// $daily_endtime1 = date('H:i:s', strtotime($this->request->post['daily_endtime']));
								$daily_endtime = date ( 'H:i:s', strtotime ( $daily_endtime1 ) );
								
								// $daily_endtime = strtotime($this->request->post['daily_endtime']);
								
								if ($current_time1 > $daily_endtime) {
									$json ['warning'] = "The Task Time must be greater than the Current Time";
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
						} else {
							if ($current_time1 > $taskTime) {
								$json ['warning'] = "The Task Time must be greater than the Current Time";
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
						/*
						 * }else{
						 *
						 * $pickup_locations_time1 = date('H:i:s', strtotime(' +'.$recurnce_hrly.' minutes',strtotime($this->request->post['pickup_locations_time'])));
						 *
						 * //$pickup_locations_time1 = date('H:i:s', strtotime($this->request->post['pickup_locations_time']));
						 * $pickup_locations_time = date('h:i A', strtotime($pickup_locations_time1));
						 *
						 *
						 * if($current_time1 > $pickup_locations_time){
						 * $json['warning'] = "The Task Time must be greater than the Current Time";
						 * }
						 * }
						 */
					}
				}
			}
			// }
			
			// var_dump($json['warning']);
			
			// die;
			
			if ($json ['warning'] == null && $json ['warning'] == "") {
				$data = array ();
				
				$data ['taskDate'] = $this->request->post ['task_date'];
				$data ['taskTime'] = $this->request->post ['taskTime'];
				$data ['endtime'] = $this->request->post ['endtime'];
				$data ['description'] = $this->request->post ['description'];
				$data ['end_recurrence_date'] = $this->request->post ['end_recurrence_date'];
				$data ['assignto'] = $this->request->post ['assignto'];
				$data ['recurrence'] = $this->request->post ['recurrence'];
				$data ['recurnce_hrly'] = $this->request->post ['recurnce_hrly'];
				$data ['tasktype'] = $this->request->post ['tasktype'];
				
				
				
				if( $data ['is_angular'] == '1'){
					
					$data ['recurnce_week'] = explode(',',$this->request->post ['recurnce_week']);
				}else{
					$data ['recurnce_week'] = $this->request->post ['recurnce_week'];
				}
				
				$data ['recurnce_month'] = $this->request->post ['recurnce_month'];
				$data ['recurnce_day'] = $this->request->post ['recurnce_day'];
				
				$data ['task_alert'] = $this->request->post ['task_alert'];
				$data ['alert_type_sms'] = $this->request->post ['alert_type_sms'];
				$data ['alert_type_notification'] = $this->request->post ['alert_type_notification'];
				$data ['alert_type_email'] = $this->request->post ['alert_type_email'];
				$data ['numChecklist'] = $this->request->post ['numChecklist'];
				
				$data ['assignto_sms'] = $this->request->post ['assignto_sms'];
				$data ['assignto_email'] = $this->request->post ['assignto_email'];
				
				$data ['task_form_id'] = $this->request->post ['task_form_id'];
				$data ['tags_id'] = $this->request->post ['tags_id'];
				
				/**
				 * ********transport_tags***************
				 */
				
				$data ['pickup_locations_address'] = $this->request->post ['pickup_locations_address'];
				$data ['pickup_locations_latitude'] = $this->request->post ['pickup_locations_latitude'];
				$data ['pickup_locations_longitude'] = $this->request->post ['pickup_locations_longitude'];
				
				$data ['pickup_locations_time'] = $this->request->post ['pickup_locations_time'];
				$data ['pickup_facilities_id'] = $this->request->post ['pickup_facilities_id'];
				$data ['dropoff_facilities_id'] = $this->request->post ['dropoff_facilities_id'];
				$data ['transport_tags'] = $this->request->post ['transport_tags'];
				$data ['dropoff_locations_address'] = $this->request->post ['dropoff_locations_address'];
				
				$data ['dropoff_locations_latitude'] = $this->request->post ['dropoff_locations_latitude'];
				$data ['dropoff_locations_longitude'] = $this->request->post ['dropoff_locations_longitude'];
				
				$data ['dropoff_locations_time'] = $this->request->post ['dropoff_locations_time'];
				
				$data ['current_locations_address'] = $this->request->post ['current_locations_address'];
				$data ['current_lat'] = $this->request->post ['current_lat'];
				$data ['current_log'] = $this->request->post ['current_log'];
				
				$data ['facilitytimezone'] = $this->request->post ['facilitytimezone'];
				$data ['recurnce_hrly_recurnce'] = $this->request->post ['recurnce_hrly_recurnce'];
				
				$data ['emp_tag_id'] = $this->request->post ['emp_tag_id'];
				$data ['daily_endtime'] = $this->request->post ['daily_endtime'];
				$data ['daily_times'] = $this->request->post ['daily_times'];
				
				$data ['bed_check_location_ids'] = $this->request->post ['bed_check_location_ids'];
				
				/**
				 * ***Perpetual******
				 */
				$data ['recurnce_hrly_perpetual'] = $this->request->post ['recurnce_hrly_perpetual'];
				
				/**
				 * Visitation**
				 */
				$data ['visitation_category_title'] = $this->request->post ['visitation_category_title'];
				$data ['visitation_start_address'] = $this->request->post ['visitation_start_address'];
				$data ['visitation_start_address_latitude'] = $this->request->post ['visitation_start_address_latitude'];
				$data ['visitation_start_address_longitude'] = $this->request->post ['visitation_start_address_longitude'];
				
				$data ['visitation_appoitment_address'] = $this->request->post ['visitation_appoitment_address'];
				$data ['visitation_appoitment_address_latitude'] = $this->request->post ['visitation_appoitment_address_latitude'];
				$data ['visitation_appoitment_address_longitude'] = $this->request->post ['visitation_appoitment_address_longitude'];
				
				$data ['visitation_start_time'] = $this->request->post ['visitation_start_time'];
				$data ['visitation_appoitment_time'] = $this->request->post ['visitation_appoitment_time'];
				$data ['visitation_tag_id'] = $this->request->post ['visitation_tag_id'];
				
				/**
				 * Completion**
				 */
				$data ['completion_alert'] = $this->request->post ['completion_alert'];
				$data ['completion_alert_type_email'] = $this->request->post ['completion_alert_type_email'];
				$data ['completion_alert_type_sms'] = $this->request->post ['completion_alert_type_sms'];
				
				$data ['completed_alert'] = $this->request->post ['completed_alert'];
				$data ['completed_late_alert'] = $this->request->post ['completed_late_alert'];
				$data ['incomplete_alert'] = $this->request->post ['incomplete_alert'];
				$data ['deleted_alert'] = $this->request->post ['deleted_alert'];
				
				$data ['complete_endtime'] = $this->request->post ['complete_endtime'];
				$data ['completed_times'] = $this->request->post ['completed_times'];
				$data ['user_roles'] = $this->request->post ['user_roles'];
				$data ['userids'] = $this->request->post ['userids'];
				$data ['emp_tag_id1'] = $this->request->post ['emp_tag_id1'];
				
				/**
				 * Medication**
				 */
				$data ['medication_tags'] = $this->request->post ['medication_tags'];
				$data ['tags_medication_details_ids'] = $this->request->post ['tags_medication_details_ids'];
				
				/**
				 * Interval - Daily - End Date **
				 */
				
				$data ['recurnce_hrly_recurnce'] = $this->request->post ['recurnce_hrly_recurnce'];
				$data ['end_recurrence_date'] = $this->request->post ['end_recurrence_date'];
				
				/*
				 * $data['transport_tags'] = $this->request->post['transport_tags'];
				 * $data['pickup_locations_address'] = $this->request->post['pickup_locations_address'];
				 * $data['pickup_locations_time'] = $this->request->post['pickup_locations_time'];
				 * $data['dropoff_locations_address'] = $this->request->post['dropoff_locations_address'];
				 * $data['dropoff_locations_time'] = $this->request->post['dropoff_locations_time'];
				 */
				
				$data ['attachement_form'] = $this->request->post ['attachement_form'];
				$data ['tasktype_form_id'] = $this->request->post ['tasktype_form_id'];
				$data ['iswaypoint'] = $this->request->post ['iswaypoint'];
				$data ['locations'] = $this->request->post ['locations'];
				$data ['google_map_image_url'] = $this->request->post ['google_map_image_url'];
				
				$data ['weekly_interval'] = $this->request->post ['weekly_interval'];
				
				$data ['phone_device_id'] = $this->request->post ['phone_device_id'];
				$data ['device_unique_id'] = $this->request->post ['device_unique_id'];
				$data ['required_approval'] = $this->request->post ['required_approval'];
				
				$data ['reminder_alert'] = $this->request->post ['reminder_alert'];
				$data ['reminderplus'] = $this->request->post ['reminderplus'];
				$data ['reminderminus'] = $this->request->post ['reminderminus'];
				$data ['facilitydrop'] = $this->request->post ['move_facility'];
				$data ['is_move'] = $this->request->post ['is_move'];
				
				$data ['assign_to_type'] = $this->request->post ['assign_to_type'];
				$data ['assign_to'] = $this->request->post ['assign_to'];
				$data ['user_role_assign_ids'] = $this->request->post ['user_role_assign_ids'];
				$data ['form_task_creation'] = $this->request->post ['form_task_creation'];
				$data ['bedcheck_occupancy'] = $this->request->post ['bedcheck_occupancy'];
				$data ['required_assign'] = $this->request->post ['required_assign'];
				
				if ($this->request->post ['is_android'] != null && $this->request->post ['is_android'] != "") {
					$data ['is_android'] = $this->request->post ['is_android'];
				} else {
					$data ['is_android'] = '1';
				}
				
				$data ['app_add'] = '1';
				
				
				$this->load->model ( 'createtask/createtask' );
				
				if($this->request->post['facilities']!=null && $this->request->post['facilities']!=""){
					$facilities = explode(",",$this->request->post['facilities']);
				 	foreach ($facilities as $facilities_id) {
						$this->model_createtask_createtask->addcreatetask ( $data, $facilities_id );
					}
				}else{
					$this->model_createtask_createtask->addcreatetask ( $data, $this->request->post ['facilities_id'] );
				}
				
				$this->data ['facilitiess'] [] = array (
						'warning' => '1' 
				);
				$error = true;
			} else {
				$this->data ['facilitiess'] [] = array (
						'warning' => $json ['warning'] 
				);
				$error = false;
			}
			
			$value = array (
					'results' => $this->data ['facilitiess'],
					'status' => $error 
			);
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in apptask jsonAddTasknote ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_jsonAddTasknote', $activity_data2 );
			// echo 'Caught exception: ', $e->getMessage(), "\n";
		}
	}
	public function jsonUpdateStriketask() {
		try {
			$this->load->model ( 'activity/activity' );
			$this->model_activity_activity->addActivitySave ( 'jsonUpdateStriketask', $this->request->post, 'request' );
			
			$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			
			$api_device_info = $this->model_api_encrypt->getdevicedetails ( $cre_array );
			
			if ($api_device_info == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$api_header_value = $this->model_api_encrypt->getallheaders1 ();
			
			if ($api_header_value == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$this->data ['facilitiess'] = array ();
			$this->load->model ( 'createtask/createtask' );
			$this->load->model ( 'notes/notes' );
			$json = array ();
			
			if (! $this->request->post ['task_id']) {
				$json ['warning'] = 'Please select id!.';
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
			
			if ($this->request->post ['user_id'] == '') {
				$json ['warning'] = 'Please select user id!.';
				
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
			
			/*
			 * if ($this->request->post['select_one'] == '') {
			 * $json['warning'] = 'Please Select One';
			 * }
			 */
			
			if ($this->request->post ['notes_pin'] != null && $this->request->post ['notes_pin'] != "") {
				$this->load->model ( 'user/user' );
				$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
				
				if (($this->request->post ['notes_pin'] != $user_info ['user_pin'])) {
					$json ['warning'] = 'Pin is incorrect, please try again..';
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
			
			if ($this->request->post ['user_id'] != '') {
				$this->load->model ( 'user/user' );
				$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
				
				if (empty ( $user_info )) {
					$json ['warning'] = 'incorrect username';
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
			
			if ($this->request->post ['perpetual_checkbox'] == '1') {
				if ($this->request->post ['perpetual_checkbox_notes_pin'] == '') {
					$json ['perpetual_checkbox_notes_pin'] = 'This is required field!';
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
				if ($this->request->post ['perpetual_checkbox_notes_pin'] != null && $this->request->post ['perpetual_checkbox_notes_pin'] != "") {
					$this->load->model ( 'user/user' );
					$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
					
					if (($this->request->post ['perpetual_checkbox_notes_pin'] != $user_info ['user_pin'])) {
						$json ['warning'] = 'Pin is incorrect, please try again.';
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
					
					/*$this->load->model ( 'user/user_group' );
					$user_role_info = $this->model_user_user_group->getUserGroup ( $user_info ['user_group_id'] );
					
					$perpetual_task = $user_role_info ['perpetual_task'];
					
					if ($perpetual_task != '1') {
						$json ['warning'] = "You are not authorized to end the task!";
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
					}*/
				}
			}
			
			if ($this->request->post ['enable_requires_approval_c11'] == '1') {
				
				if ($this->request->post ['enable_requires_approval_value'] == '') {
					$json ['warning'] = 'This is required field!';
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
				if ($this->request->post ['enable_requires_approval_value'] != null && $this->request->post ['enable_requires_approval_value'] != "") {
					$this->load->model ( 'user/user' );
					$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
					
					if (($this->request->post ['enable_requires_approval_value'] != $user_info ['user_pin'])) {
						$json ['warning'] = 'Pin is incorrect, please try again.';
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
					
					/*$this->load->model ( 'user/user_group' );
					$user_role_info = $this->model_user_user_group->getUserGroup ( $user_info ['user_group_id'] );
					
					$enable_requires_approval = $user_role_info ['enable_requires_approval'];
					
					if ($enable_requires_approval != '1') {
						$json ['warning'] = "You are not authorized to Complete the task!";
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
					}*/
				}
			}
			
			if ($this->request->post ['task_id'] != '') {
				
				$this->load->model ( 'createtask/createtask' );
				$result = $this->model_createtask_createtask->getStrikedatadetails ( $this->request->post ['task_id'] );
				$task_date = date ( 'm-d-Y', strtotime ( $result ['task_date'] ) );
				
				date_default_timezone_set ( $this->request->post ['facilitytimezone'] );
				
				$current_date = date ( 'm-d-Y', strtotime ( 'now' ) );
				
				if ($task_date > $current_date) {
					$json ['warning'] = "Task cannot be completed before designated time";
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
				
				if ($result ['id'] == null && $result ['id'] == "") {
					$json ['warning'] = "This task has been already completed. Please cancel and refresh the notes to review the task.";
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
				
				
				
				$this->load->model ( 'user/user' );
				//$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
				if( $this->request->post ['user_id'] != null &&  $this->request->post ['user_id'] != ""){
					$user_info = $this->model_user_user->getUserByUsername (  $this->request->post ['user_id']);
				}else{
					$user_info = $this->model_user_user->getUserByUsernamebynotes ($this->request->post['username'],$this->request->post ['facilities_id'] );
				}
				$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName ( $result ['tasktype'],$this->request->post ['facilities_id'] );
				
				
				if($tasktype_info['completed_user_roles'] != null && $tasktype_info['completed_user_roles'] != ""){
					$user_roles2 = explode ( ',', $tasktype_info ['completed_user_roles'] );
					if (!in_array ( $user_info['user_group_id'], $user_roles2 )) {
						$json ['warning'] = "You are not authorized to Complete the task!";
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
				if ($this->request->post ['perpetual_checkbox'] == '2') {
					if($tasktype_info['pause_user_roles'] != null && $tasktype_info['pause_user_roles'] != ""){
						$user_roles3 = explode ( ',', $tasktype_info ['pause_user_roles'] );
						if (!in_array ( $user_info['user_group_id'], $user_roles3 )) {
							
							$json ['warning'] = "You are not authorized to Pause on this task!";
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
				}
				
				if ($this->request->post ['perpetual_checkbox'] == '3') {
				if($tasktype_info['move_user_roles'] != null && $tasktype_info['move_user_roles'] != ""){
					$user_roles4 = explode ( ',', $tasktype_info ['move_user_roles'] );
					if (!in_array ( $user_info['user_group_id'], $user_roles4 )) {
						$json ['warning'] = "You are not authorized to Move on this task!";
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
				}
				if ($this->request->post ['perpetual_checkbox'] == '4') {
				if($tasktype_info['changeinterval_user_roles'] != null && $tasktype_info['changeinterval_user_roles'] != ""){
					$user_roles6 = explode ( ',', $tasktype_info ['changeinterval_user_roles'] );
					if (!in_array ( $user_info['user_group_id'], $user_roles6 )) {
						$json ['warning'] = "You are not authorized to Change Interval on this task!";
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
				}
				
				if ($this->request->post ['perpetual_checkbox'] == '1') {
					
					if($tasktype_info['user_roles'] != null && $tasktype_info['user_roles'] != ""){
						$user_roles1 = explode ( ',', $tasktype_info ['user_roles'] );
						
						if (!in_array ( $user_info['user_group_id'], $user_roles1 )) {
							$json ['warning'] = "You are not authorized to end the task!";
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
				}
				
				
				if ($this->request->post ['enable_requires_approval_c11'] == '1') {
					
					if ($this->request->post ['enable_requires_approval_value'] != null && $this->request->post ['enable_requires_approval_value'] != "") {
						
						if (($this->request->post ['enable_requires_approval_value'] != $user_info ['user_pin'])) {
							$this->error ['warning'] = $this->language->get ( 'error_exists' );
						}
						
						
						if($tasktype_info['requires_approval_user_roles'] != null && $tasktype_info['requires_approval_user_roles'] != ""){
							$user_roles21 = explode ( ',', $tasktype_info ['requires_approval_user_roles'] );
							
							if (!in_array ( $user_info['user_group_id'], $user_roles21 )) {
								$json ['warning'] = "You are not authorized to Complete the task!";
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
						
					
					}
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
				$data = array ();
				
				if ($this->request->post ['signature'] != null && $this->request->post ['signature'] != "") {
					$data ['imgOutput'] = $this->request->post ['signature'];
				}
				
				$data ['notes_pin'] = $this->request->post ['notes_pin'];
				$data ['user_id'] = $this->request->post ['user_id'];
				$data ['strike_note_type'] = $this->request->post ['strike_note_type'];
				
				$data ['comments'] = $this->request->post ['comments'];
				$data ['facilitytimezone'] = $this->request->post ['facilitytimezone'];
				
				$data ['perpetual_checkbox'] = $this->request->post ['perpetual_checkbox'];
				$data ['perpetual_checkbox_notes_pin'] = $this->request->post ['perpetual_checkbox_notes_pin'];
				$data ['customlistvalues_id'] = $this->request->post ['customlistvalues_id'];
				$data ['customlistvalues_ids'] = $this->request->post ['customlistvalues_ids'];
				
				$data ['current_locations_address'] = $this->request->post ['current_locations_address'];
				$data ['current_lat'] = $this->request->post ['current_lat'];
				$data ['current_log'] = $this->request->post ['current_log'];
				
				$data ['location_tracking_url'] = $this->request->post ['location_tracking_url'];
				$data ['location_tracking_route'] = $this->request->post ['location_tracking_route'];
				$data ['location_tracking_time_start'] = $this->request->post ['location_tracking_time_start'];
				$data ['location_tracking_time_end'] = $this->request->post ['location_tracking_time_end'];
				$data ['google_map_image_url'] = $this->request->post ['google_map_image_url'];
				
				$data ['is_pause'] = $this->request->post ['is_pause'];
				$data ['pause_time'] = $this->request->post ['pause_time'];
				$data ['pause_date'] = $this->request->post ['pause_date'];
				$data ['acttion_interval_id'] = $this->request->post ['acttion_interval_id'];
				
				$data ['phone_device_id'] = $this->request->post ['phone_device_id'];
				$data ['device_unique_id'] = $this->request->post ['device_unique_id'];
				if ($this->request->post ['is_android'] != null && $this->request->post ['is_android'] != "") {
					$data ['is_android'] = $this->request->post ['is_android'];
				} else {
					$data ['is_android'] = '1';
				}
				
				if ($this->request->post ['task_id'] != Null && $this->request->post ['task_id'] != "") {
					
					if ($this->request->post ['requires_approval'] == 'decline') {
						
						$approvaltask_info = $this->model_createtask_createtask->getNBYTaksLists ( $this->request->post ['task_id'] );
						
						if ($approvaltask_info != null && $approvaltask_info != "") {
							$this->model_createtask_createtask->deleteNBYTaksLists ( $this->request->post ['task_id'] );
						}
						
						$approvaltasklists = $this->model_createtask_createtask->getApprovaltasklist ( $this->request->post ['task_id'] );
						
						if ($approvaltasklists != null && $approvaltasklists != "") {
							foreach ( $approvaltasklists as $approvaltasklist ) {
								$this->model_createtask_createtask->deleteNBYTaksLists ( $approvaltasklist ['id'] );
							}
							
							foreach ( $approvaltasklists as $approvaltasklist ) {
								$this->model_createtask_createtask->addapproveTask ( $approvaltasklist );
							}
						}
					}
					
					$result = $this->model_createtask_createtask->getStrikedatadetails ( $this->request->post ['task_id'] );
					$notes_id = $this->model_createtask_createtask->insertDatadetails ( $result, $data, $this->request->post ['facilities_id'], $this->request->post ['requires_approval'] );
					
					$fre_array2 = array ();
					$this->load->model ( 'api/facerekognition' );
					$fre_array2 ['face_notes_file'] = $this->request->post ['face_notes_file'];
					$fre_array2 ['outputFolder'] = $this->request->post ['outputFolder'];
					$fre_array2 ['face_not_verify'] = $this->request->post ['face_not_verify'];
					$fre_array2 ['facilities_id'] = $this->request->post ['facilities_id'];
					$fre_array2 ['notes_file'] = $facerekognition_response ['imagedata'] ['notes_file'];
					$fre_array2 ['outputFolder_1'] = $facerekognition_response ['imagedata'] ['outputFolder'];
					$fre_array2 ['notes_id'] = $notes_id;
					$this->model_api_facerekognition->savefacerekognitionnotes ( $fre_array2 );
					
					if ($this->request->post ['requires_approval'] == 'decline') {
						
						$this->model_createtask_createtask->deleteApprovaltasklist ( $this->request->post ['task_id'] );
					}
					
					if ($this->request->post ['perpetual_checkbox'] == '1') {
						
						$this->load->model ( 'notes/notes' );
						$timeZone = date_default_timezone_set ( $this->request->post ['facilitytimezone'] );
						$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
						$date_added = ( string ) $noteDate;
						
						$data = array ();
						
						$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
						$data ['imgOutput'] = $this->request->post ['signature'];
						
						$data ['notes_pin'] = $this->request->post ['notes_pin'];
						$data ['user_id'] = $this->request->post ['user_id'];
						
						$data ['notetime'] = $notetime;
						$data ['note_date'] = $date_added;
						
						/*
						 * if($this->request->post['comments'] != null && $this->request->post['comments']){
						 * $comments = ' | '.$this->request->post['comments'];
						 * }
						 */
						
						$this->load->model ( 'createtask/createtask' );
						
						$this->load->model ( 'setting/keywords' );
						
						$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName ( $result ['tasktype'],$result['facilityId'] );
						
						$keywordData12 = $this->model_setting_keywords->getkeywordDetail ( $tasktype_info ['relation_keyword_id'] );
						$keywordData13 = $this->model_setting_keywords->getkeywordDetail ( $keywordData12 ['relation_keyword_id'] );
						
						$data ['keyword_file'] = $keywordData13 ['keyword_image'];
						
						$keywordData2 = $this->model_setting_keywords->getkeywordDetaildesc ( $data ['keyword_file'], $this->request->post ['facilities_id'] );
						
						$notetasktime = date ( 'H:i:s', strtotime ( $result ['task_time'] ) );
						
						$data ['notes_description'] = $keywordData2 ['keyword_name'] . ' | ENDED | ' . date ( 'h:i A', strtotime ( $notetasktime ) ) . ' ' . $comments;
						
						$data ['date_added'] = $date_added;
						$data ['phone_device_id'] = $this->request->post ['phone_device_id'];
						$data ['device_unique_id'] = $this->request->post ['device_unique_id'];
						$data ['linked_id'] = $result ['linked_id'];
						
						if ($this->request->post ['is_android'] != null && $this->request->post ['is_android'] != "") {
							$data ['is_android'] = $this->request->post ['is_android'];
						} else {
							$data ['is_android'] = '1';
						}
						
						$notesid = $this->model_notes_notes->jsonaddnotes ( $data, $this->request->post ['facilities_id'] );
						
						$this->model_notes_notes->updatenotetask ( $result ['parent_id'], $notesid );
						
						if ($result ['emp_tag_id'] != null && $result ['emp_tag_id'] != "") {
							$this->load->model ( 'notes/notes' );
							
							date_default_timezone_set ( $this->request->post ['facilitytimezone'] );
							$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
							
							$this->load->model ( 'notes/tags' );
							$taginfo = $this->model_notes_tags->getTag ( $result ['emp_tag_id'] );
							$tadata = array();
							$this->model_notes_notes->updateNotesTag ( $taginfo ['emp_tag_id'], $notesid, $taginfo ['tags_id'], $update_date,$tadata );
						}
					}
					
					$this->load->model ( 'createtask/createtask' );
					$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName ( $result ['tasktype'],$result['facilityId'] );
					$relation_keyword_id = $tasktype_info ['relation_keyword_id'];
					
					if ($relation_keyword_id) {
						$this->load->model ( 'notes/notes' );
						$noteDetails = $this->model_notes_notes->getnotes ( $notes_id );
						
						$this->load->model ( 'setting/keywords' );
						$keyword_info = $this->model_setting_keywords->getkeywordDetail ( $relation_keyword_id );
						
						$data3 = array ();
						$data3 ['keyword_file'] = $keyword_info ['keyword_image'];
						$data3 ['notes_description'] = $noteDetails ['notes_description'];
						
						$this->model_notes_notes->addactiveNote ( $data3, $notes_id );
					}
					
					$this->model_createtask_createtask->updatetaskStrike ( $this->request->post ['task_id'] );
					$this->model_createtask_createtask->deteteIncomTask ( $result['facilityId'] );
				}
				
				$error = true;
				
				//$this->load->model ( 'notes/notes' );
				//$noteinfo = $this->model_notes_notes->getnotes ( $notes_id );
				
				$this->data ['facilitiess'] [] = array (
						'warning' => '1',
						'notes_id' => $notes_id ,
						//'noteinfo' => $noteinfo ,
				);
			} else {
				$this->data ['facilitiess'] [] = array (
						'warning' => $json ['warning'] 
				);
				$error = false;
			}
			
			$value = array (
					'results' => $this->data ['facilitiess'],
					'status' => $error 
			);
			
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in apptask jsonUpdateStriketask ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_jsonUpdateStriketask', $activity_data2 );
			// echo 'Caught exception: ', $e->getMessage(), "\n";
		}
	}
	public function jsonSavetask() {
		try {
			$this->load->model ( 'activity/activity' );
			$this->model_activity_activity->addActivitySave ( 'jsonSavetask', $this->request->post, 'request' );
			
			$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			
			$api_device_info = $this->model_api_encrypt->getdevicedetails ( $cre_array );
			
			if ($api_device_info == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$api_header_value = $this->model_api_encrypt->getallheaders1 ();
			
			if ($api_header_value == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$this->data ['facilitiess'] = array ();
			$this->load->model ( 'createtask/createtask' );
			$this->load->model ( 'notes/notes' );
			$json = array ();
			
			if (! $this->request->post ['task_id']) {
				$json ['warning'] = 'Please select id!.';
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
			
			if ($this->request->post ['user_id'] == '') {
				$json ['warning'] = 'Please select user id!.';
				
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
			
			/*
			 * if ($this->request->post['select_one'] == '') {
			 * $json['warning'] = 'Please Select One';
			 * }
			 */
			
			if ($this->request->post ['notes_pin'] != null && $this->request->post ['notes_pin'] != "") {
				$this->load->model ( 'user/user' );
				$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
				
				if (($this->request->post ['notes_pin'] != $user_info ['user_pin'])) {
					$json ['warning'] = 'Pin is incorrect, please try again..';
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
			
			if ($this->request->post ['user_id'] != '') {
				$this->load->model ( 'user/user' );
				$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
				
				if (empty ( $user_info )) {
					$json ['warning'] = 'incorrect username';
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
			
			if ($this->request->post ['perpetual_checkbox'] == '1') {
				if ($this->request->post ['perpetual_checkbox_notes_pin'] == '') {
					$json ['perpetual_checkbox_notes_pin'] = 'This is required field!';
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
				if ($this->request->post ['perpetual_checkbox_notes_pin'] != null && $this->request->post ['perpetual_checkbox_notes_pin'] != "") {
					$this->load->model ( 'user/user' );
					$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
					
					if (($this->request->post ['perpetual_checkbox_notes_pin'] != $user_info ['user_pin'])) {
						$json ['warning'] = 'Pin is incorrect, please try again.';
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
					
					/*$this->load->model ( 'user/user_group' );
					$user_role_info = $this->model_user_user_group->getUserGroup ( $user_info ['user_group_id'] );
					
					$perpetual_task = $user_role_info ['perpetual_task'];
					
					if ($perpetual_task != '1') {
						$json ['warning'] = "You are not authorized to end the task!";
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
					}*/
				}
			}
			
			if ($this->request->post ['enable_requires_approval_c11'] == '1') {
				
				if ($this->request->post ['enable_requires_approval_value'] == '') {
					$json ['warning'] = 'This is required field!';
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
				if ($this->request->post ['enable_requires_approval_value'] != null && $this->request->post ['enable_requires_approval_value'] != "") {
					$this->load->model ( 'user/user' );
					$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
					
					if (($this->request->post ['enable_requires_approval_value'] != $user_info ['user_pin'])) {
						$json ['warning'] = 'Pin is incorrect, please try again.';
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
					
					/*$this->load->model ( 'user/user_group' );
					$user_role_info = $this->model_user_user_group->getUserGroup ( $user_info ['user_group_id'] );
					
					$enable_requires_approval = $user_role_info ['enable_requires_approval'];
					
					if ($enable_requires_approval != '1') {
						$json ['warning'] = "You are not authorized to Complete the task!";
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
					}*/
				}
			}
			
			if ($this->request->post ['task_id'] != '') {
				
				$this->load->model ( 'createtask/createtask' );
				$result = $this->model_createtask_createtask->getStrikedatadetails ( $this->request->post ['task_id'] );
				$task_date = date ( 'm-d-Y', strtotime ( $result ['task_date'] ) );
				
				date_default_timezone_set ( $this->request->post ['facilitytimezone'] );
				
				$current_date = date ( 'm-d-Y', strtotime ( 'now' ) );
				
				if ($task_date > $current_date) {
					$json ['warning'] = "Task cannot be completed before designated time";
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
				
				if ($result ['id'] == null && $result ['id'] == "") {
					$json ['warning'] = "This task has been already completed. Please cancel and refresh the notes to review the task.";
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
				
				
				
				$this->load->model ( 'user/user' );
				//$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
				if( $this->request->post ['user_id'] != null &&  $this->request->post ['user_id'] != ""){
					$user_info = $this->model_user_user->getUserByUsername (  $this->request->post ['user_id']);
				}else{
					$user_info = $this->model_user_user->getUserByUsernamebynotes ($this->request->post['username'],$this->request->post ['facilities_id'] );
				}
				$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName ( $result ['tasktype'],$this->request->post ['facilities_id'] );
				
				
				if($tasktype_info['completed_user_roles'] != null && $tasktype_info['completed_user_roles'] != ""){
					$user_roles2 = explode ( ',', $tasktype_info ['completed_user_roles'] );
					if (!in_array ( $user_info['user_group_id'], $user_roles2 )) {
						$json ['warning'] = "You are not authorized to Complete the task!";
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
				if ($this->request->post ['perpetual_checkbox'] == '2') {
					if($tasktype_info['pause_user_roles'] != null && $tasktype_info['pause_user_roles'] != ""){
						$user_roles3 = explode ( ',', $tasktype_info ['pause_user_roles'] );
						if (!in_array ( $user_info['user_group_id'], $user_roles3 )) {
							
							$json ['warning'] = "You are not authorized to Pause on this task!";
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
				}
				
				if ($this->request->post ['perpetual_checkbox'] == '3') {
				if($tasktype_info['move_user_roles'] != null && $tasktype_info['move_user_roles'] != ""){
					$user_roles4 = explode ( ',', $tasktype_info ['move_user_roles'] );
					if (!in_array ( $user_info['user_group_id'], $user_roles4 )) {
						$json ['warning'] = "You are not authorized to Move on this task!";
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
				}
				if ($this->request->post ['perpetual_checkbox'] == '4') {
				if($tasktype_info['changeinterval_user_roles'] != null && $tasktype_info['changeinterval_user_roles'] != ""){
					$user_roles6 = explode ( ',', $tasktype_info ['changeinterval_user_roles'] );
					if (!in_array ( $user_info['user_group_id'], $user_roles6 )) {
						$json ['warning'] = "You are not authorized to Change Interval on this task!";
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
				}
				
				if ($this->request->post ['perpetual_checkbox'] == '1') {
					
					if($tasktype_info['user_roles'] != null && $tasktype_info['user_roles'] != ""){
						$user_roles1 = explode ( ',', $tasktype_info ['user_roles'] );
						
						if (!in_array ( $user_info['user_group_id'], $user_roles1 )) {
							$json ['warning'] = "You are not authorized to end the task!";
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
				}
				
				
				if ($this->request->post ['enable_requires_approval_c11'] == '1') {
					
					if ($this->request->post ['enable_requires_approval_value'] != null && $this->request->post ['enable_requires_approval_value'] != "") {
						
						if (($this->request->post ['enable_requires_approval_value'] != $user_info ['user_pin'])) {
							$this->error ['warning'] = $this->language->get ( 'error_exists' );
						}
						
						
						if($tasktype_info['requires_approval_user_roles'] != null && $tasktype_info['requires_approval_user_roles'] != ""){
							$user_roles21 = explode ( ',', $tasktype_info ['requires_approval_user_roles'] );
							
							if (!in_array ( $user_info['user_group_id'], $user_roles21 )) {
								$json ['warning'] = "You are not authorized to Complete the task!";
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
						
					
					}
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
			
			// var_dump($json['warning']);die;
			
			if ($json ['warning'] == null && $json ['warning'] == "") {
				$data = array ();
				
				$data ['notes_pin'] = $this->request->post ['notes_pin'];
				$data ['user_id'] = $this->request->post ['user_id'];
				$data ['notes_type'] = $this->request->post ['notes_type'];
				
				$data ['comments'] = $this->request->post ['comments'];
				
				$data ['perpetual_checkbox'] = $this->request->post ['perpetual_checkbox'];
				$data ['perpetual_checkbox_notes_pin'] = $this->request->post ['perpetual_checkbox_notes_pin'];
				$data ['customlistvalues_id'] = $this->request->post ['customlistvalues_id'];
				$data ['customlistvalues_ids'] = $this->request->post ['customlistvalues_ids'];
				
				$data ['current_locations_address'] = $this->request->post ['current_locations_address'];
				$data ['current_lat'] = $this->request->post ['current_lat'];
				$data ['current_log'] = $this->request->post ['current_log'];
				
				$data ['location_tracking_url'] = $this->request->post ['location_tracking_url'];
				
				$data ['location_tracking_route'] = $this->request->post ['location_tracking_route'];
				$data ['location_tracking_time_start'] = $this->request->post ['location_tracking_time_start'];
				$data ['location_tracking_time_end'] = $this->request->post ['location_tracking_time_end'];
				$data ['google_map_image_url'] = $this->request->post ['google_map_image_url'];
				
				$data ['drop_tags_ids'] = $this->request->post ['drop_tags_ids'];
				$data ['pickup_tags_ids'] = $this->request->post ['pickup_tags_ids'];
				$data ['pick_up_tags_id'] = $this->request->post ['pick_up_tags_id'];
				
				$data ['travel_blank_start'] = $this->request->post ['travel_blank_start'];
				
				$data ['phone_device_id'] = $this->request->post ['phone_device_id'];
				$data ['device_unique_id'] = $this->request->post ['device_unique_id'];
				
				$data ['is_pause'] = $this->request->post ['is_pause'];
				$data ['pause_time'] = $this->request->post ['pause_time'];
				$data ['pause_date'] = $this->request->post ['pause_date'];
				$data ['acttion_interval_id'] = $this->request->post ['acttion_interval_id'];
				$data ['reassign_id'] = $this->request->post ['reassign_id'];
				
				
				if ($this->request->post ['is_android'] != null && $this->request->post ['is_android'] != "") {
					$data ['is_android'] = $this->request->post ['is_android'];
				} else {
					$data ['is_android'] = '1';
				}
				
				if ($this->request->post ['signature'] != null && $this->request->post ['signature'] != "") {
					$data ['imgOutput'] = $this->request->post ['signature'];
				}
				$data ['facilitytimezone'] = $this->request->post ['facilitytimezone'];
				
				$data ['facilitydrop'] = $this->request->post ['move_facility'];
				$data ['is_move'] = $this->request->post ['is_move'];
				
				$this->load->model ( 'createtask/createtask' );
				
				if ($this->request->post ['requires_approval'] == "approve") {
					$approvaltask_info = $this->model_createtask_createtask->getNBYTaksLists ( $this->request->post ['task_id'] );
					
					if ($approvaltask_info != null && $approvaltask_info != "") {
						$this->model_createtask_createtask->deleteNBYTaksLists ( $this->request->post ['task_id'] );
					}
					
					$approvaltasklists = $this->model_createtask_createtask->getApprovaltasklist ( $this->request->post ['task_id'] );
					
					if ($approvaltasklists != null && $approvaltasklists != "") {
						
						foreach ( $approvaltasklists as $approvaltasklist ) {
							$this->model_createtask_createtask->deleteNBYTaksLists ( $approvaltasklist ['id'] );
						}
						
						foreach ( $approvaltasklists as $approvaltasklist ) {
							$this->model_createtask_createtask->addapproveTask ( $approvaltasklist );
						}
					}
				}
				
				$result = $this->model_createtask_createtask->getStrikedatadetails ( $this->request->post ['task_id'] );
				
				$facilities_id = $result['facilityId'];
				
				$notes_id = $this->model_createtask_createtask->inserttask ( $result, $data, $facilities_id, $this->request->post ['requires_approval'] );
				
				$fre_array2 = array ();
				$this->load->model ( 'api/facerekognition' );
				$fre_array2 ['face_notes_file'] = $this->request->post ['face_notes_file'];
				$fre_array2 ['outputFolder'] = $this->request->post ['outputFolder'];
				$fre_array2 ['face_not_verify'] = $this->request->post ['face_not_verify'];
				$fre_array2 ['facilities_id'] = $facilities_id;
				$fre_array2 ['notes_file'] = $facerekognition_response ['imagedata'] ['notes_file'];
				$fre_array2 ['outputFolder_1'] = $facerekognition_response ['imagedata'] ['outputFolder'];
				$fre_array2 ['notes_id'] = $notes_id;
				$this->model_api_facerekognition->savefacerekognitionnotes ( $fre_array2 );
				
				if ($this->request->post ['perpetual_checkbox'] == '1') {
					
					$this->load->model ( 'notes/notes' );
					$timeZone = date_default_timezone_set ( $this->request->post ['facilitytimezone'] );
					$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
					$date_added = ( string ) $noteDate;
					
					$data = array ();
					
					$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
					$data ['imgOutput'] = $this->request->post ['signature'];
					
					$data ['notes_pin'] = $this->request->post ['notes_pin'];
					$data ['user_id'] = $this->request->post ['user_id'];
					
					$data ['notetime'] = $notetime;
					$data ['note_date'] = $date_added;
					
					/*
					 * if($this->request->post['comments'] != null && $this->request->post['comments']){
					 * $comments = ' | '.$this->request->post['comments'];
					 * }
					 */
					
					$this->load->model ( 'createtask/createtask' );
					
					$this->load->model ( 'setting/keywords' );
					
					$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName ( $result ['tasktype'],$result['facilityId'] );
					
					$keywordData12 = $this->model_setting_keywords->getkeywordDetail ( $tasktype_info ['relation_keyword_id'] );
					$keywordData13 = $this->model_setting_keywords->getkeywordDetail ( $keywordData12 ['relation_keyword_id'] );
					
					$data ['keyword_file'] = $keywordData13 ['keyword_image'];
					
					$keywordData2 = $this->model_setting_keywords->getkeywordDetaildesc ( $data ['keyword_file'], $facilities_id );
					
					$notetasktime = date ( 'H:i:s', strtotime ( $result ['task_time'] ) );
					
					$data ['notes_description'] = $keywordData2 ['keyword_name'] . ' | ENDED | ' . date ( 'h:i A', strtotime ( $notetasktime ) ) . ' ' . $comments;
					
					$data ['date_added'] = $date_added;
					
					$data ['phone_device_id'] = $this->request->post ['phone_device_id'];
					$data ['device_unique_id'] = $this->request->post ['device_unique_id'];
					
					$data ['linked_id'] = $result ['linked_id'];
					
					if ($this->request->post ['is_android'] != null && $this->request->post ['is_android'] != "") {
						$data ['is_android'] = $this->request->post ['is_android'];
					} else {
						$data ['is_android'] = '1';
					}
					
					$notesid = $this->model_notes_notes->jsonaddnotes ( $data, $result['facilityId'] );
					
					$this->model_notes_notes->updatenotetask ( $result ['parent_id'], $notesid );
					
					if ($result ['emp_tag_id'] != null && $result ['emp_tag_id'] != "") {
						$this->load->model ( 'notes/notes' );
						
						date_default_timezone_set ( $this->request->post ['facilitytimezone'] );
						$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
						
						$this->load->model ( 'notes/tags' );
						$taginfo = $this->model_notes_tags->getTag ( $result ['emp_tag_id'] );
						$tadata = array();
						$this->model_notes_notes->updateNotesTag ( $taginfo ['emp_tag_id'], $notesid, $taginfo ['tags_id'], $update_date,$tadata );
					}
				}
				
				$this->load->model ( 'createtask/createtask' );
				$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName ( $result ['tasktype'],$result['facilityId'] );
				$relation_keyword_id = $tasktype_info ['relation_keyword_id'];
				
				if ($relation_keyword_id) {
					$this->load->model ( 'notes/notes' );
					$noteDetails = $this->model_notes_notes->getnotes ( $notes_id );
					
					$this->load->model ( 'setting/keywords' );
					$keyword_info = $this->model_setting_keywords->getkeywordDetail ( $relation_keyword_id );
					
					$data3 = array ();
					$data3 ['keyword_file'] = $keyword_info ['keyword_image'];
					$data3 ['notes_description'] = $noteDetails ['notes_description'];
					
					$this->model_notes_notes->addactiveNote ( $data3, $notes_id );
				}
				
				$this->model_createtask_createtask->updatetaskNote ( $this->request->post ['task_id'] );
				$this->model_createtask_createtask->deteteIncomTask ( $result['facilityId'] );
				
				
				//$this->load->model ( 'notes/notes' );
				//$noteinfo = $this->model_notes_notes->getnotes ( $notes_id );
				
				$this->data ['facilitiess'] [] = array (
						'warning' => '1',
						'notes_id' => $notes_id,
						//'noteinfo' => $noteinfo,
				);
				$error = true;
			} else {
				
				$this->data ['facilitiess'] [] = array (
						'warning' => $json ['warning'] 
				);
				$error = false;
			}
			
			$value = array (
					'results' => $this->data ['facilitiess'],
					'status' => $error 
			);
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in apptask jsonSavetask ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_jsonSavetask', $activity_data2 );
			// echo 'Caught exception: ', $e->getMessage(), "\n";
		}
	}
	public function jsonTaskData() {
		try {
			
			$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			
			$api_device_info = $this->model_api_encrypt->getdevicedetails ( $cre_array );
			/*
			if ($api_device_info == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$api_header_value = $this->model_api_encrypt->getallheaders1 ();
			
			if ($api_header_value == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			*/
			
			$facilities_id = $this->request->post ['facilities_id'];
			
			if ($facilities_id != NULL && $facilities_id != "") {
				$this->data ['tasktypes'] = array ();
				$this->load->model ( 'createtask/createtask' );
				$results = $this->model_createtask_createtask->getTaskdetails ( $facilities_id );
				
				foreach ( $results as $result ) {
					if ($this->request->post ['config_taskform_status'] == 1) {
						
						$this->data ['tasktypes'] [] = array (
								'task_id' => $result ['task_id'],
								'tasktype_name' => $result ['tasktype_name'],
								'client_required' => $result ['client_required'],
								'field_required' => $result ['field_required'],
								'is_facility' => $result ['is_facility'],
								'facility_type' => $result ['facility_type'],
								'type' => $result ['type'] 
						)
						// 'status' => $result['status'],
						
						;
					} else {
						
						if ($result ['tasktype_name'] != 'Form') {
							$this->data ['tasktypes'] [] = array (
									'task_id' => $result ['task_id'],
									'tasktype_name' => $result ['tasktype_name'],
									'client_required' => $result ['client_required'],
									'field_required' => $result ['field_required'],
									'is_facility' => $result ['is_facility'],
									'facility_type' => $result ['facility_type'],
									'type' => $result ['type'] 
							)
							// 'status' => $result['status'],
							
							;
						}
					}
				}
				
				$this->data ['intervalss'] = array ();
				$this->load->model ( 'createtask/createtask' );
				$intervals = $this->model_createtask_createtask->getTaskintervals ( $facilities_id );
				
				foreach ( $intervals as $interval ) {
					
					$this->data ['intervalss'] [] = array (
							'interval_name' => $interval ['interval_name'],
							'interval_value' => $interval ['interval_value'] 
					);
				}
				
				$this->data ['userss'] = array ();
				
				// $facilities_id = '22';
				$this->load->model ( 'user/user' );
				$users = $this->data ['users'] = $this->model_user_user->getUsersByFacility ( $facilities_id );
				
				foreach ( $users as $user ) {
					
					if ($user ['email'] != NULL && $user ['email'] != "") {
						$uemail = $user ['email'];
					} else {
						$uemail = '';
					}
					
					if ($user ['phone_number'] != NULL && $user ['phone_number'] != "") {
						$uphone = $user ['phone_number'];
					} else {
						$uphone = '0';
					}
					
					$this->data ['userss'] [] = array (
							'user' => $user ['username'],
							'user_id' => $user ['user_id'],
							'facilities' => $user ['facilities'],
							'user_group_id' => $user ['user_group_id'],
							'email' => $uemail,
							'phone_number' => $uphone 
					)
					;
				}
				
				$this->data ['bedchecktaskforms'] = array ();
				
				$this->load->model ( 'setting/bedchecktaskform' );
				
				$data2 = array ();
				$data2 ['status'] = '1';
				$data2 ['facilities_id'] = $facilities_id;
				$bedchecktaskforms = $this->model_setting_bedchecktaskform->getBCTFs ( $data2 );
				
				foreach ( $bedchecktaskforms as $bedchecktaskform ) {
					
					$this->data ['bedchecktaskforms'] [] = array (
							'task_form_id' => $bedchecktaskform ['task_form_id'],
							'facilities_id' => $bedchecktaskform ['facilities_id'],
							'task_form_name' => $bedchecktaskform ['task_form_name'] 
					);
				}
				
				$this->data ['tags'] = array ();
				
				//$config_admin_limit='5';
				
				$data = array ();
				$data ['status'] = '1';
				$data ['discharge'] = '1';
				$data ['facilities_id'] = $facilities_id;
				//$data ['limit'] = $config_admin_limit;
				//$data ['start'] =($page - 1) * $config_admin_limit;
					
					
				$this->load->model ( 'setting/tags' );
				$tags = $this->model_setting_tags->getTags ( $data );
				
				
				
				foreach ( $tags as $tag ) {
					
					$this->data ['tags'] [] = array (
							'tags_id' => $tag ['tags_id'],
							'emp_tag_id' => $tag ['emp_tag_id'],
							'facilities_id' => $tag ['facilities_id'],
							'tags_status_in' => $tag ['tags_status_in'],
							'role_call' => $tag ['role_call'],
							'tags_display' => $tag ['emp_tag_id'] . ': ' . $tag ['emp_first_name'] . ' ' . $tag ['emp_last_name'] 
					);
				}
				
				$this->data ['tags_client'] = array ();
				$data = array (
						'client_name' => $this->request->get ['filter_name'],
						'status' => '1',
						'sort' => 'client_name',
						'order' => 'ASC',
						'start' => 0,
						'limit' => CONFIG_LIMIT 
				);
				
				$this->load->model ( 'createtask/createtask' );
				
				$cresults = $this->model_createtask_createtask->getclients ( $data );
				
				foreach ( $cresults as $cresult ) {
					$this->data ['tags_client'] [] = array (
							'tags_id' => $cresult ['client_id'],
							'emp_tag_id' => $cresult ['client_name'],
							'tags_display' => $cresult ['client_name'] 
					);
				}
				
				$this->data ['tagmedicationData'] = array ();
				
				/*$this->load->model ( 'setting/tags' );
				$data = array (
						'status' => '1',
						'discharge' => '1',
						'is_master' => '1',
						'is_submaster' => '1',
						'facilities_id' => $facilities_id 
				)
				;
				
				$results = $this->model_setting_tags->getTags ( $data );
				
				foreach ( $results as $info ) {
					$tagsmedications = $this->model_setting_tags->getTagsMedicationdetails ( $info ['tags_id'] );
					
					if (! empty ( $tagsmedications )) {
						$this->data ['tagmedicationData'] [] = array (
								'tags_id' => $info ['tags_id'],
								'emp_tag_id' => $info ['emp_tag_id'],
								'tags_status_in' => $info ['tags_status_in'],
								'role_call' => $info ['role_call'],
								'emp_first_name' => $info ['emp_first_name'],
								'emp_last_name' => $info ['emp_last_name'],
								'tagsmedications' => $this->model_setting_tags->getTagsMedicationdetails ( $info ['tags_id'] ) 
						)
						;
					}
				}*/
				
				$this->data ['roleData'] = array ();
				$this->load->model ( 'user/user_group' );
				
				$ffdata = array (
						'filter_name' => $this->request->post ['filter_name'],
						'facilities_id' => $this->request->post ['facilities_id'] 
				);
				
				$user_infos = $this->model_user_user_group->getUserGroups ( $ffdata );
				
				foreach ( $user_infos as $info ) {
					$this->data ['roleData'] [] = array (
							'name' => $info ['name'],
							'inventory_permission' => $info ['inventory_permission'],
							'enable_requires_approval' => $info ['enable_requires_approval'],
							'perpetual_task' => $info ['perpetual_task'],
							'share_notes' => $info ['share_notes'],
							'is_private' => $info ['is_private'],
							'user_group_id' => $info ['user_group_id'],
							'enable_form_open' => $info ['enable_form_open'],
							'enable_mark_final' => $info ['enable_mark_final'],
					);
				}
				
				$this->load->model ( 'form/form' );
				
				$this->data ['customs_forms'] = array ();
				$data3 = array ();
				$data3 ['status'] = '1';
				// $data3['order'] = 'sort_order';
				$data3 ['is_parent'] = '1';
				$data3 ['facilities_id'] = $facilities_id;
				
				$results = $this->model_form_form->getforms ( $data3 );
				
				foreach ( $results as $custom_form ) {
					
					$this->data ['customs_forms'] [] = array (
							'forms_id' => $custom_form ['forms_id'],
							'form_name' => $custom_form ['form_name'] 
					);
				}
				
				//var_dump($this->data ['tags']);
				
				
				$this->data ['facilitiess'] = array ();
				$this->data ['facilitiess'] [] = array (
						'tasktypes' => $this->data ['tasktypes'],
						'intervalss' => $this->data ['intervalss'],
						'users' => $this->data ['userss'],
						'bedchecktaskforms' => $this->data ['bedchecktaskforms'],
						'tags' => $this->data ['tags'],
						'tags_client' => $this->data ['tags_client'],
						
						'roleData' => $this->data ['roleData'],
						'tagmedicationData' => $this->data ['tagmedicationData'],
						'customs_forms' => $this->data ['customs_forms'] 
				);
				
				
				$error = true;
			} else {
				$this->data ['facilitiess'] [] = array (
						'warning' => "Please send Id" 
				);
				$error = false;
			}
			
			$value = array (
					'results' => $this->data ['facilitiess'],
					'status' => true 
			);
			/* echo json_encode($value); */
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in apptask jsonTaskData ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_jsonTaskData', $activity_data2 );
			// echo 'Caught exception: ', $e->getMessage(), "\n";
		}
	}
	public function getNotification() {
		try {
			$json = array ();
			
			$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			
			$api_device_info = $this->model_api_encrypt->getdevicedetails ( $cre_array );
			
			if ($api_device_info == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$api_header_value = $this->model_api_encrypt->getallheaders1 ();
			
			if ($api_header_value == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			if ($this->request->post ['facilities_id'] != null && $this->request->post ['facilities_id'] != "") {
				$this->load->model ( 'facilities/facilities' );
				$facilities_info = $this->model_facilities_facilities->getfacilities ( $this->request->post ['facilities_id'] );
				
				if ($facilities_info ['config_task_status'] == '1') {
					if (isset ( $this->request->post ['facilitytimezone'] )) {
						$facilities_timezone = $this->request->post ['facilitytimezone'];
						date_default_timezone_set ( $facilities_timezone );
					}
					
					if (isset ( $this->request->post ['currentdate'] )) {
						$date = str_replace ( '-', '/', $this->request->post ['currentdate'] );
						$res = explode ( "/", $date );
						
						$changedDate = $res [2] . "-" . $res [1] . "-" . $res [0];
						
						$currentdate = $changedDate;
					} else {
						$currentdate = date ( 'd-m-Y' );
					}
					// var_dump($currentdate);
					
					if (isset ( $this->request->post ['facilities_id'] )) {
						$facilities_id = $this->request->post ['facilities_id'];
					}
					
					$timeZone = date_default_timezone_set ( $facilitytimezone );
					
					$this->load->model ( 'createtask/createtask' );
					
					$data1 = array ();
					
					$data1 ['currentdate'] = $currentdate;
					$data1 ['notification'] = '1';
					$data1 ['top'] = '2';
					$data1 ['facilities_id'] = $facilities_id;
					
					$compltetecountTaskLists = $this->model_createtask_createtask->getCountallTaskLists ( $data1 ); /* total */
					
					$complteteTaskLists = $this->model_createtask_createtask->getallTaskLists ( $data1 ); /* task */
					
					if ($compltetecountTaskLists > 0) {
						foreach ( $complteteTaskLists as $list ) {
							$this->data ['listtask'] [] = array (
									'task_id' => $list ['id'],
									'assign_to' => $list ['assign_to'],
									'tasktype' => $list ['tasktype'],
									// 'date' => date('j, M Y', strtotime($list['task_date'])),
									// 'id' =>$list['id'],
									'description' => $list ['description'],
									'task_time' => date ( 'h:i A', strtotime ( $list ['task_time'] ) ) 
							);
						}
						
						// $total = $compltetecountTaskLists;
						$error = true;
					} else {
						$this->data ['listtask'] [] = array (
								'warning' => '0' 
						);
						$total = '0';
						$error = true;
					}
				} else {
					$this->data ['listtask'] [] = array (
							'warning' => '0' 
					);
					$total = '0';
					$error = true;
				}
			} else {
				$this->data ['listtask'] [] = array (
						'warning' => '0' 
				);
				$total = '0';
				$error = true;
			}
			$this->response->setOutput ( json_encode ( $json ) );
			$value = array (
					'results' => $this->data ['listtask'],
					'status' => $error 
			);
			/* echo json_encode($value); */
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in apptask getNotification ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_getNotification', $activity_data2 );
			// echo 'Caught exception: ', $e->getMessage(), "\n";
		}
	}
	public function getNotificationWear() {
		try {
			$json = array ();
			
			$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			
			$api_device_info = $this->model_api_encrypt->getdevicedetails ( $cre_array );
			
			if ($api_device_info == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$api_header_value = $this->model_api_encrypt->getallheaders1 ();
			
			if ($api_header_value == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			if ($this->request->post ['facilities_id'] != null && $this->request->post ['facilities_id'] != "") {
				$this->load->model ( 'facilities/facilities' );
				$facilities_info = $this->model_facilities_facilities->getfacilities ( $this->request->post ['facilities_id'] );
				
				if ($facilities_info ['config_task_status'] == '1') {
					if (isset ( $this->request->post ['facilitytimezone'] )) {
						$facilities_timezone = $this->request->post ['facilitytimezone'];
						date_default_timezone_set ( $facilities_timezone );
					}
					
					if (isset ( $this->request->post ['currentdate'] )) {
						$date = str_replace ( '-', '/', $this->request->post ['currentdate'] );
						$res = explode ( "/", $date );
						
						$changedDate = $res [2] . "-" . $res [1] . "-" . $res [0];
						
						$currentdate = $changedDate;
					} else {
						$currentdate = date ( 'd-m-Y' );
					}
					// var_dump($currentdate);
					
					if (isset ( $this->request->post ['facilities_id'] )) {
						$facilities_id = $this->request->post ['facilities_id'];
					}
					
					$timeZone = date_default_timezone_set ( $facilitytimezone );
					
					$this->load->model ( 'createtask/createtask' );
					
					$data1 = array ();
					
					$data1 ['currentdate'] = $currentdate;
					// $data1['notification'] = '1';
					$data1 ['top'] = '2';
					$data1 ['facilities_id'] = $facilities_id;
					
					$compltetecountTaskLists = $this->model_createtask_createtask->getCountallTaskLists ( $data1 ); /* total */
					
					$complteteTaskLists = $this->model_createtask_createtask->getallTaskLists ( $data1 ); /* task */
					
					if ($compltetecountTaskLists > 0) {
						foreach ( $complteteTaskLists as $list ) {
							$this->data ['listtask'] [] = array (
									'task_id' => $list ['id'],
									'assign_to' => $list ['assign_to'],
									'tasktype' => $list ['tasktype'],
									// 'date' => date('j, M Y', strtotime($list['task_date'])),
									// 'id' =>$list['id'],
									'description' => $list ['description'],
									'task_time' => date ( 'h:i A', strtotime ( $list ['task_time'] ) ) 
							);
						}
						
						// $total = $compltetecountTaskLists;
						$error = true;
					} else {
						$this->data ['listtask'] [] = array (
								'warning' => '0' 
						);
						$total = '0';
						$error = true;
					}
				} else {
					$this->data ['listtask'] [] = array (
							'warning' => '0' 
					);
					$total = '0';
					$error = true;
				}
			} else {
				$this->data ['listtask'] [] = array (
						'warning' => '0' 
				);
				$total = '0';
				$error = true;
			}
			$this->response->setOutput ( json_encode ( $json ) );
			$value = array (
					'results' => $this->data ['listtask'],
					'status' => $error 
			);
			/* echo json_encode($value); */
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in apptask getNotificationWear ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_getNotificationWear', $activity_data2 );
			// echo 'Caught exception: ', $e->getMessage(), "\n";
		}
	}
	public function jsongetTaskdetail() {
		try {
			
			/*
			 * $this->load->model('api/encrypt');
			 * $cre_array = array();
			 * $cre_array['phone_device_id'] = $this->request->post['phone_device_id'];
			 * $cre_array['facilities_id'] = $this->request->post['facilities_id'];
			 *
			 * $api_device_info = $this->model_api_encrypt->getdevicedetails($cre_array);
			 *
			 * if($api_device_info == false){
			 * $errorMessage = $this->model_api_encrypt->errorMessage();
			 * return $errorMessage;
			 * }
			 *
			 * $api_header_value = $this->model_api_encrypt->getallheaders1();
			 *
			 * if($api_header_value == false){
			 * $errorMessage = $this->model_api_encrypt->errorMessage();
			 * return $errorMessage;
			 * }
			 */
			
			$this->data ['listtask'] = array ();
			$this->data ['listtask2'] = array ();
			
			if ($this->request->post ['task_id'] != null && $this->request->post ['task_id'] != "") {
				$this->load->model ( 'facilities/facilities' );
				$this->load->model('setting/locations');
				
				$facilities_info = $this->model_facilities_facilities->getfacilities ( $this->request->post ['facilities_id'] ); 
				
				$unique_id = $facilities_info ['customer_key'];

			   // var_dump($unique_id); die;
				
				$this->load->model ( 'customer/customer' );
				
				$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
				
			   
				$client_info = unserialize($customer_info['client_info_notes']);

				$client_view_options2 = $client_info["client_view_options"]; 
				
				if ($facilities_info ['config_task_status'] == '1') {
					
					$this->load->model ( 'createtask/createtask' );
					
					$list = $this->model_createtask_createtask->getnotesInfo2 ( $this->request->post ['task_id'] );
					
					if ($list != null && $list != "") {
						
						// date_default_timezone_set($this->session->data['time_zone_1']);
						$currenttime = date ( 'H:i:s', strtotime ( 'now' ) );
						$currenttimePlus = date ( 'H:i:s', strtotime ( ' +' . $addTime . ' minutes', strtotime ( 'now' ) ) );
						$currentdate = date ( 'Y-m-d', strtotime ( 'now' ) );
						
						$this->load->model ( 'setting/locations' );
						$this->load->model ( 'setting/tags' );
						$this->load->model ( 'form/form' );
						
						$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName($list['tasktype'],$list['facilityId']);
					
						$tasktypetype = $tasktype_info ['type'];
						$is_task_rule = $tasktype_info ['is_task_rule'];
						
						$taskstarttime = date ( 'H:i:s', strtotime ( $list ['task_time'] ) );
						
						if($is_task_rule != '1'){
							if ($currenttimePlus >= $taskstarttime) {
								$taskDuration = '1';
							} else {
								$taskDuration = '2';
							}
						}else{
							$taskDuration = '1';
						}
						
						if (strlen ( $list ['description'] ) > 50) {
							$description_more = '1';
						} else {
							$description_more = '0';
						}
						
						if ($list ['checklist'] == "incident_form") {
							$form_url = str_replace ( '&amp;', '&', $this->url->link ( 'services/noteform/taskforminsert', '' . 'task_id=' . $list ['id'] . '&facilities_id=' . $list ['facilityId'], 'SSL' ) );
						} else if ($list ['checklist'] == "bed_check") {
							$form_url = str_replace ( '&amp;', '&', $this->url->link ( 'services/noteform/checklistform', '' . 'task_id=' . $list ['id'] . '&facilities_id=' . $list ['facilityId'], 'SSL' ) );
						} elseif (is_numeric ( $list ['checklist'] )) {
							$form_url = str_replace ( '&amp;', '&', $this->url->link ( 'form/form', '' . 'forms_design_id=' . $list ['checklist'] . '&task_id=' . $list ['id'] . '&facilities_id=' . $list ['facilityId'] ) );
						} else {
							$form_url = '';
						}
						
						$bedcheckdata = array ();
						
						if ($list ['task_form_id'] != 0 && $list ['task_form_id'] != NULL) {
							
							/*if ($list ['bed_check_location_ids'] != null && $list ['bed_check_location_ids'] != "") {
								$formDatas = $this->model_setting_locations->getformid2 ( $list ['bed_check_location_ids'] );
							} else { */
								$formDatas = $this->model_setting_locations->getformid ( $list ['task_form_id'] );
							/* } */
							
							foreach ( $formDatas as $formData ) {
								
								$locData = $this->model_setting_locations->getlocation ( $formData ['locations_id'] );
								
								// var_dump($locData);
								
								$locationDatab = array ();
								$location_type = "";
								if ($locData ['locations_id'] > 0) {
									$location_typea = $locData ['location_type'];
									
									/*
									 * if($location_typea == '1'){
									 * $customlistvalues_info = $this->model_form_form->getcustomlistvaluebyid($locData['customlistvalues_id'], $locData['location_type']);
									 *
									 * $location_type .= $customlistvalues_info['customlistvalues_name'];
									 * }
									 *
									 * if($location_typea == '2'){
									 * $customlistvalues_info = $this->model_form_form->getcustomlistvaluebyid($locData['customlistvalues_id'], $locData['location_type']);
									 *
									 * $location_type .= $customlistvalues_info['customlistvalues_name'];
									 * }
									 *
									 * if($location_typea == '3'){
									 * $customlistvalues_info = $this->model_form_form->getcustomlistvaluebyid($locData['customlistvalues_id'], $locData['location_type']);
									 *
									 * $location_type .= $customlistvalues_info['customlistvalues_name'];
									 * }
									 */
									
									if ($locData ['upload_file'] != null && $locData ['upload_file'] != "") {
										$upload_file = $locData ['upload_file'];
									} else {
										$upload_file = "";
									}
									
									//var_dump($list ['bedcheck_occupancy']);
									
									$customlistvalues_tags = $this->model_setting_tags->gettagscustomlistvaluebyid ( $locData ['locations_id'], '1', $this->request->post ['facilities_id'] );
									
									
									if($list ['bedcheck_occupancy'] == "1"){
									
										if(!empty($customlistvalues_tags)){
										
											$custom_tags = array ();
											if ($customlistvalues_tags) {
												foreach ( $customlistvalues_tags as $tag ) {
													
													$client_view_options_flag = $tag['emp_first_name'].' '.$tag['emp_last_name'];
													
													
													$custom_tags [] = array (
															'tags_id' => $tag ['tags_id'],
															'name' => $client_view_options_flag,
															'emp_tag_id' => $tag ['emp_tag_id'],
															'emp_first_name' => $tag ['emp_first_name'],
															'emp_last_name' => $tag ['emp_last_name'],
															'upload_file' => $tag ['upload_file'] 
													);
												}
											}
											
											$locationDatab [] = array (
													'locations_id' => $locData ['locations_id'],
													'location_name' => $locData ['location_name'],
													'location_address' => $locData ['location_address'],
													'location_detail' => $locData ['location_detail'],
													'capacity' => $locData ['capacity'],
													// 'location_type' =>'',
													'upload_file' => $upload_file,
													'nfc_location_tag' => $locData ['nfc_location_tag'],
													'nfc_location_tag_required' => $locData ['nfc_location_tag_required'],
													// 'gps_location_tag' =>$locData['gps_location_tag'],
													// 'gps_location_tag_required' =>$locData['gps_location_tag_required'],
													'latitude' => $locData ['latitude'],
													'longitude' => $locData ['longitude'],
													// 'other_location_tag' =>$locData['other_location_tag'],
													// 'other_location_tag_required' =>$locData['other_location_tag_required'],
													'other_type_id' => $locData ['other_type_id'],
													'facilities_id' => $locData ['facilities_id'],
													'custom_tags' => $custom_tags 
											) ;
										}
									}else{
										
										$custom_tags = array ();
										if ($customlistvalues_tags) {
											foreach ( $customlistvalues_tags as $tag ) {
												
												$client_view_options = $client_view_options2;

											  if(isset($tag['emp_first_name']) && $tag['emp_first_name']!=''){
												$client_view_options = str_replace('[emp_first_name]', $tag['emp_first_name'], $client_view_options); 
											  }else{
												$client_view_options = str_replace('[emp_first_name]', '', $client_view_options); 
											  }


											  if(isset($tag['emp_middle_name']) && $tag['emp_middle_name']!=''){
												$client_view_options = str_replace('[emp_middle_name]', $tag['emp_middle_name'], $client_view_options);
											  } else{
												$client_view_options = str_replace('[emp_middle_name]', '', $client_view_options);
											  } 

											  if(isset($tag['emp_last_name']) && $tag['emp_last_name']!=''){
												$client_view_options = str_replace('[emp_last_name]', $tag['emp_last_name'], $client_view_options);
											  } else{
												$client_view_options = str_replace('[emp_last_name]', '', $client_view_options);
											  } 

											  if(isset($tag['emergency_contact']) && $tag['emergency_contact']!=''){
												$client_view_options = str_replace('[emergency_contact]', $tag['emergency_contact'], $client_view_options);
											  } else{
												$client_view_options = str_replace('[emergency_contact]', '', $client_view_options);
											  } 

											  if(isset($tag['facilities_id']) && $tag['facilities_id']!=''){
												  $result_info = $this->model_facilities_facilities->getfacilities($tag['facilities_id']);
												$client_view_options = str_replace('[facilities_id]', $result_info['facility'], $client_view_options); 
											  } else{
												$client_view_options = str_replace('[facilities_id]', '', $client_view_options); 
											  } 

											  if(isset($tag['room']) && $tag['room']!=''){
												  $rresults = $this->model_setting_locations->getlocation($tag['room']);
												$client_view_options = str_replace('[room]', $rresults['location_name'], $client_view_options);
											  } else{
												$client_view_options = str_replace('[room]', '', $client_view_options);
											  } 

											  if(isset($tag['dob']) && $tag['dob']!=''){
												$client_view_options = str_replace('[dob]', $tag['dob'], $client_view_options);
											  } else{
												$client_view_options = str_replace('[dob]', '', $client_view_options);
											  }
											  
											  if(isset($tag['gender']) && $tag['gender']!=''){  
												$client_view_options = str_replace('[gender]', $tag['gender'], $client_view_options);
											  } else{
												$client_view_options = str_replace('[gender]', '', $client_view_options);
											  }
											   
											  if(isset($tag['age']) && $tag['age']!=''){  
												$client_view_options = str_replace('[age]', $tag['age'], $client_view_options); 
											  } else{
												$client_view_options = str_replace('[age]', '', $client_view_options); 
											  }
												
											  if(isset($tag['ssn']) && $tag['ssn']!=NULL){  
												$client_view_options = str_replace('[ssn]', $tag['ssn'], $client_view_options);
											  }else{
												$client_view_options = str_replace('[ssn]', '', $client_view_options);
											  } 
											  
											  if(isset($tag['emp_tag_id']) && $tag['emp_tag_id']!=''){
												$client_view_options = str_replace('[emp_tag_id]', $tag['emp_tag_id'], $client_view_options);
											  } else{
												$client_view_options = str_replace('[emp_tag_id]', '', $client_view_options);
											  }

											  if(isset($tag['emp_extid']) && $tag['emp_extid']!=''){
												$client_view_options = str_replace('[emp_extid]', $tag['emp_extid'], $client_view_options);
											  } else{
												$client_view_options = str_replace('[emp_extid]', '', $client_view_options);
											  }
											  
											 
											  if($client_view_options != "" && $client_view_options != null){
												  $client_view_options_flag = nl2br($client_view_options);
												}else{
												  $client_view_options_flag = $tag['emp_first_name'].' '.$tag['emp_last_name'];
												}
												
												if($client_view_options != "" && $client_view_options != null){
												  $client_view_options_flag = nl2br($client_view_options);
												}else{
												  $client_view_options_flag = $tag['emp_first_name'].' '.$tag['emp_last_name'];
												}
												
												$custom_tags [] = array (
														'tags_id' => $tag ['tags_id'],
														'name' => $tag['emp_first_name'].' '.$tag['emp_last_name'],
														'name2' => $client_view_options_flag,
														'emp_tag_id' => $tag ['emp_tag_id'],
														'emp_first_name' => $tag ['emp_first_name'],
														'emp_last_name' => $tag ['emp_last_name'],
														'upload_file' => $tag ['upload_file'] 
												);
											}
										}
										
										$locationDatab [] = array (
												'locations_id' => $locData ['locations_id'],
												'location_name' => $locData ['location_name'],
												'location_address' => $locData ['location_address'],
												'location_detail' => $locData ['location_detail'],
												'capacity' => $locData ['capacity'],
												// 'location_type' =>'',
												'upload_file' => $upload_file,
												'nfc_location_tag' => $locData ['nfc_location_tag'],
												'nfc_location_tag_required' => $locData ['nfc_location_tag_required'],
												// 'gps_location_tag' =>$locData['gps_location_tag'],
												// 'gps_location_tag_required' =>$locData['gps_location_tag_required'],
												'latitude' => $locData ['latitude'],
												'longitude' => $locData ['longitude'],
												// 'other_location_tag' =>$locData['other_location_tag'],
												// 'other_location_tag_required' =>$locData['other_location_tag_required'],
												'other_type_id' => $locData ['other_type_id'],
												'facilities_id' => $locData ['facilities_id'],
												'custom_tags' => $custom_tags 
										);
									}
									
									$bedcheckdata [] = array (
											'task_form_location_id' => $formData ['task_form_location_id'],
											'location_name' => $formData ['location_name'],
											'location_detail' => $formData ['location_detail'],
											'current_occupency' => $formData ['current_occupency'],
											'bedcheck_locations' => $locationDatab 
									);
									
									// var_dump($bedcheckdata);
								}
							}
							
							/*
							 * $this->load->model('setting/bedchecktaskform');
							 * $taskformData = $this->model_setting_bedchecktaskform->getbedchecktaskform($list['task_form_id']);
							 *
							 * foreach($taskformData as $frmData){
							 * $taskformsData[] = array(
							 * 'task_form_name' =>$frmData['task_form_name'],
							 * 'facilities_id' =>$frmData['facilities_id'],
							 * 'form_type' =>$frmData['form_type']
							 * );
							 * }
							 */
						}
						
						/*
						 * if($list['tags_id'] != 0 && $list['tags_id'] != NULL ){
						 * $tags_info = $this->model_setting_tags->getTag($list['tags_id']);
						 * $locationData = array();
						 * $locData = $this->model_setting_locations->getlocation($tags_info['locations_id']);
						 *
						 * $locationData[] = array(
						 * 'locations_id' =>$locData['locations_id'],
						 * 'location_name' =>$locData['location_name'],
						 * 'location_address' =>$locData['location_address'],
						 * 'location_detail' =>$locData['location_detail'],
						 * 'capacity' =>$locData['capacity'],
						 * 'location_type' =>$locData['location_type'],
						 * 'nfc_location_tag' =>$locData['nfc_location_tag'],
						 * 'nfc_location_tag_required' =>$locData['nfc_location_tag_required'],
						 * 'gps_location_tag' =>$locData['gps_location_tag'],
						 * 'gps_location_tag_required' =>$locData['gps_location_tag_required'],
						 * 'latitude' =>$locData['latitude'],
						 * 'longitude' =>$locData['longitude'],
						 * 'other_location_tag' =>$locData['other_location_tag'],
						 * 'other_location_tag_required' =>$locData['other_location_tag_required'],
						 * 'other_type_id' =>$locData['other_type_id'],
						 * 'facilities_id' =>$locData['facilities_id']
						 *
						 * );
						 *
						 *
						 * if($tags_info['upload_file'] != null && $tags_info['upload_file'] != ""){
						 * $upload_file2 = $tags_info['upload_file'];
						 * }else{
						 * $upload_file2 = "";
						 * }
						 *
						 *
						 * $drugaData = array();
						 * $drugDatas = $this->model_setting_tags->getDrugs($list['id']);
						 *
						 * foreach($drugDatas as $drugData){
						 * $drugaData[] = array(
						 * 'createtask_by_group_id' =>$drugData['createtask_by_group_id'],
						 * 'facilities_id' =>$drugData['facilities_id'],
						 * 'locations_id' =>$drugData['locations_id'],
						 * 'tags_id' =>$drugData['tags_id'],
						 * 'medication_id' =>$drugData['medication_id'],
						 * 'drug_name' =>$drugData['drug_name'],
						 * 'dose' =>$drugData['dose'],
						 * 'drug_type' =>$drugData['drug_type'],
						 * 'quantity' =>$drugData['quantity'],
						 * 'frequency' =>$drugData['frequency'],
						 * 'start_time' =>$drugData['start_time'],
						 * 'instructions' =>$drugData['instructions'],
						 * 'count' =>$drugData['count'],
						 * 'complete_status' =>$drugData['complete_status'],
						 * 'upload_file' =>$upload_file2,
						 * );
						 * }
						 *
						 *
						 * $medications[] = array(
						 * 'tags_id' =>$tags_info['tags_id'],
						 * 'upload_file' =>$upload_file2,
						 * 'emp_tag_id' =>$tags_info['emp_tag_id'],
						 * 'emp_first_name' =>$tags_info['emp_first_name'],
						 * 'tags_pin' =>$tags_info['tags_pin'],
						 * 'emp_last_name' =>$tags_info['emp_last_name'],
						 * 'doctor_name' =>$tags_info['doctor_name'],
						 * 'emergency_contact' =>$tags_info['emergency_contact'],
						 * 'dob' =>$tags_info['dob'],
						 * 'medications_locations' =>$locationData,
						 * 'medications_drugs' =>$drugaData
						 * );
						 *
						 * }
						 */
						
						$transport_tags = array ();
						$this->load->model ( 'setting/tags' );
						
						if (! empty ( $list ['transport_tags'] )) {
							$transport_tags1 = explode ( ',', $list ['transport_tags'] );
						} else {
							$transport_tags1 = array ();
						}
						
						foreach ( $transport_tags1 as $tag1 ) {
							$tags_info = $this->model_setting_tags->getTag ( $tag1 );
							
							if ($tags_info ['emp_first_name']) {
								$emp_tag_id = $tags_info ['emp_tag_id'] . ': ' . $tags_info ['emp_first_name'] . ' ' . $tags_info ['emp_last_name'];
							} else {
								$emp_tag_id = $tags_info ['emp_tag_id'];
							}
							
							if ($tags_info) {
								$transport_tags [] = array (
										'tags_id' => $tags_info ['tags_id'],
										'emp_tag_id_1' => $tags_info ['emp_tag_id'],
										'emp_tag_id' => $emp_tag_id,
										'emp_first_name' => $tags_info ['emp_first_name'],
										'emp_last_name' => $tags_info ['emp_last_name'] 
								);
							}
						}
						
						/*
						 * if($list['iswaypoint'] == '1'){
						 * $transport_tags[] = array(
						 * 'tags_id' => 'Yes',
						 * 'emp_tag_id' => 'Round Trip'
						 * );
						 * }
						 */
						
						$medication_tags = array ();
						$this->data ['medication_tags'] = array ();
						$this->load->model ( 'setting/tags' );
						
						if (! empty ( $list ['medication_tags'] )) {
							$medication_tags1 = explode ( ',', $list ['medication_tags'] );
						} else {
							$medication_tags1 = array ();
						}
						
						foreach ( $medication_tags1 as $medicationtag ) {
							$tags_info1 = $this->model_setting_tags->getTag ( $medicationtag );
							
							if ($tags_info1 ['emp_first_name']) {
								$emp_tag_id = $tags_info1 ['emp_tag_id'] . ': ' . $tags_info1 ['emp_first_name'] . ' ' . $tags_info1 ['emp_last_name'];
							} else {
								$emp_tag_id = $tags_info1 ['emp_tag_id'];
							}
							
							if ($tags_info1) {
								
								$locationData = array ();
								$locData = $this->model_setting_locations->getlocation ( $tags_info1 ['locations_id'] );
								
								if ($locData) {
									$locationData [] = array (
											'locations_id' => $locData ['locations_id'],
											'location_name' => $locData ['location_name'],
											'location_address' => $locData ['location_address'],
											'location_detail' => $locData ['location_detail'],
											'capacity' => $locData ['capacity'],
											'location_type' => $locData ['location_type'],
											'nfc_location_tag' => $locData ['nfc_location_tag'],
											'nfc_location_tag_required' => $locData ['nfc_location_tag_required'],
											'gps_location_tag' => $locData ['gps_location_tag'],
											'gps_location_tag_required' => $locData ['gps_location_tag_required'],
											'latitude' => $locData ['latitude'],
											'longitude' => $locData ['longitude'],
											'other_location_tag' => $locData ['other_location_tag'],
											'other_location_tag_required' => $locData ['other_location_tag_required'],
											'other_type_id' => $locData ['other_type_id'],
											'facilities_id' => $locData ['facilities_id'] 
									)
									;
								}
								
								if ($tags_info1 ['upload_file'] != null && $tags_info1 ['upload_file'] != "") {
									$upload_file2 = $tags_info1 ['upload_file'];
								} else {
									$upload_file2 = "";
								}
								
								$drugs = array ();
								
								$mdrugs = $this->model_setting_tags->getTagsMedicationdetailsByID ( $list ['id'], $medicationtag );
								
								foreach ( $mdrugs as $mdrug ) {
									
									$mdrug_info = $this->model_setting_tags->getTagsMedicationdruglByID ( $mdrug ['tags_medication_details_id'] );
									if (! empty ( $mdrug_info )) {
										
										if($mdrug_info ['drug_name'] != null && $mdrug_info ['drug_name'] != ""){
										$drugs [] = array (
												'tags_medication_details_id' => $mdrug ['tags_medication_details_id'],
												'drug_name' => $mdrug_info ['drug_name'],
												'tags_medication_id' => $mdrug_info ['tags_medication_id'],
												'quantity' => $mdrug_info ['drug_mg'],
												'dose' => $mdrug_info ['drug_alertnate'],
												'drug_prn' => $mdrug_info ['drug_prn'],
												'instructions' => $mdrug_info ['instructions'],
												// 'drug_am' =>date('h:i A', strtotime($mdrug_info['drug_am'])),
												// 'drug_pm' =>date('h:i A', strtotime($mdrug_info['drug_pm'])),
												'upload_file' => $upload_file2,
												
												'createtask_by_group_id' => '',
												'facilities_id' => $tags_info1 ['facilities_id'],
												'locations_id' => '',
												'tags_id' => $mdrug_info ['tags_id'],
												'medication_id' => $mdrug_info ['tags_medication_id'] 
										)
										// 'dose' =>'',
										// 'drug_type' =>'',
										// 'quantity' =>'',
										// 'frequency' =>'',
										// 'start_time' =>'',
										// 'count' =>'',
										// 'complete_status' =>'',
										;
										}
									}
								}
								
								$medication_tags [] = array (
										'tags_id' => $tags_info1 ['tags_id'],
										'upload_file' => $upload_file2,
										'emp_tag_id' => $tags_info1 ['emp_tag_id'],
										'emp_tag_id_full' => $emp_tag_id,
										'emp_first_name' => $tags_info1 ['emp_first_name'],
										'tags_pin' => $tags_info1 ['tags_pin'],
										'emp_last_name' => $tags_info1 ['emp_last_name'],
										'doctor_name' => $tags_info1 ['doctor_name'],
										'emergency_contact' => $tags_info1 ['emergency_contact'],
										'dob' => $tags_info1 ['dob'],
										'medications_locations' => $locationData,
										'medications_drugs' => $drugs 
								)
								;
							}
						}
						
						$visitation_tag_id = "";
						if ($list ['visitation_tag_id']) {
							$visitation_tag = $this->model_setting_tags->getTag ( $list ['visitation_tag_id'] );
							
							if ($visitation_tag ['emp_first_name']) {
								$visitation_tag_id = $visitation_tag ['emp_tag_id'] . ': ' . $visitation_tag ['emp_first_name'] . ' ' . $visitation_tag ['emp_last_name'];
							} else {
								$visitation_tag_id = $visitation_tag ['emp_tag_id'];
							}
						}
						
						$this->data ['listtask'] [] = array (
								'task_group_by' => $list ['task_group_by'],
								'is_approval_required_forms_id' => $list ['is_approval_required_forms_id'],
								'iswaypoint' => $list ['iswaypoint'],
								'assign_to' => $list ['assign_to'],
								'facilities_id' => $list ['facilityId'],
								'recurrence' => $list ['recurrence'],
								'tasktype' => $list ['tasktype'],
								'checklist' => $list ['checklist'],
								'task_complettion' => $list ['task_complettion'],
								'device_id' => $list ['device_id'],
								'date' => date ( 'j, M Y', strtotime ( $list ['task_date'] ) ),
								'id' => $list ['id'],
								'description' => html_entity_decode ( str_replace ( '&#039;', '\'', $list ['description'] ) ),
								'description_more' => $description_more,
								'taskDuration' => $taskDuration,
								'task_time' => date ( 'h:i A', strtotime ( $list ['task_time'] ) ),
								'checklist_url' => $form_url,
								'task_form_id' => $list ['task_form_id'],
								'tags_id' => $list ['tags_id'],
								'pickup_facilities_id' => $list ['pickup_facilities_id'],
								'pickup_locations_address' => $list ['pickup_locations_address'],
								'pickup_locations_time' => $list ['pickup_locations_time'],
								'pickup_locations_latitude' => $list ['pickup_locations_latitude'],
								'pickup_locations_longitude' => $list ['pickup_locations_longitude'],
								'dropoff_facilities_id' => $list ['dropoff_facilities_id'],
								'dropoff_locations_address' => $list ['dropoff_locations_address'],
								'dropoff_locations_time' => $list ['dropoff_locations_time'],
								'dropoff_locations_latitude' => $list ['dropoff_locations_latitude'],
								'dropoff_locations_longitude' => $list ['dropoff_locations_longitude'],
								// 'transport_tags' =>$list['transport_tags'],
								// 'medications' =>$medications,
								// 'bedchecks' =>$bedcheckdata,
								
								'transport_tags' => $transport_tags,
								'medications' => $medication_tags,
								'bedchecks' => $bedcheckdata,
								
								// 'medication_tags' =>$medication_tags,
								'visitation_tags' => $list ['visitation_tags'],
								'visitation_tag_id' => $visitation_tag_id,
								'visitation_start_facilities_id' => $list ['visitation_start_facilities_id'],
								'visitation_start_address' => $list ['visitation_start_address'],
								'visitation_start_time' => date ( 'h:i A', strtotime ( $list ['visitation_start_time'] ) ),
								'visitation_start_address_latitude' => $list ['visitation_start_address_latitude'],
								'visitation_start_address_longitude' => $list ['visitation_start_address_longitude'],
								'visitation_appoitment_facilities_id' => $list ['visitation_appoitment_facilities_id'],
								'visitation_appoitment_address' => $list ['visitation_appoitment_address'],
								'visitation_appoitment_time' => date ( 'h:i A', strtotime ( $list ['visitation_appoitment_time'] ) ),
								'visitation_appoitment_address_latitude' => $list ['visitation_appoitment_address_latitude'],
								'visitation_appoitment_address_longitude' => $list ['visitation_appoitment_address_longitude'] 
						)
						;
						
						$sqlu = "UPDATE `" . DB_PREFIX . "createtask` SET task_complettion = '1',device_id = '" . $this->request->post ['device_id'] . "' where id = '" . $this->request->post ['task_id'] . "' ";
						$this->db->query ( $sqlu );
						
						$trstatus = true;
					} else {
						$this->data ['listtask'] [] = array (
								'warning' => '0' 
						);
						$taskTotal = '0';
						$trstatus = false;
					}
				} else {
					$this->data ['listtask'] [] = array (
							'warning' => '0' 
					);
					$taskTotal = '0';
					$trstatus = false;
				}
			} else {
				$this->data ['listtask'] [] = array (
						'warning' => '0' 
				);
				$taskTotal = '0';
				$trstatus = false;
			}
			$value = array (
					'results' => $this->data ['listtask'],
					'status' => $trstatus 
			);
			/* echo json_encode($value); */
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in apptask jsongetTaskdetail ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_jsongetTaskdetail', $activity_data2 );
			// echo 'Caught exception: ', $e->getMessage(), "\n";
		}
	}
	public function jsonSavetaskBy2() {
		try {
			
			$this->load->model ( 'activity/activity' );
			$this->model_activity_activity->addActivitySave ( 'jsonSavetaskBy2', $this->request->post, 'request' );
			
			$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			
			/*
			 * $api_device_info = $this->model_api_encrypt->getdevicedetails($cre_array);
			 *
			 * if($api_device_info == false){
			 * $errorMessage = $this->model_api_encrypt->errorMessage();
			 * return $errorMessage;
			 * }
			 *
			 * $api_header_value = $this->model_api_encrypt->getallheaders1();
			 *
			 * if($api_header_value == false){
			 * $errorMessage = $this->model_api_encrypt->errorMessage();
			 * return $errorMessage;
			 * }
			 */
			
			$this->data ['facilitiess'] = array ();
			$this->load->model ( 'createtask/createtask' );
			$this->load->model ( 'notes/notes' );
			$json = array ();
			
			if (! $this->request->post ['task_id']) {
				$json ['warning'] = 'Please select id!.';
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
			
			if ($this->request->post ['user_id'] == '') {
				$json ['warning'] = 'Please select user id!.';
				
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
			
			/*
			 * if ($this->request->post['select_one'] == '') {
			 * $json['warning'] = 'Please Select One';
			 * }
			 */
			
			if ($this->request->post ['notes_pin'] != null && $this->request->post ['notes_pin'] != "") {
				$this->load->model ( 'user/user' );
				$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
				
				if (($this->request->post ['notes_pin'] != $user_info ['user_pin'])) {
					$json ['warning'] = 'Pin is incorrect, please try again..';
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
			
			if ($this->request->post ['user_id'] != '') {
				$this->load->model ( 'user/user' );
				$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
				
				if (empty ( $user_info )) {
					$json ['warning'] = 'incorrect username';
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
			
			if ($this->request->post ['perpetual_checkbox'] == '1') {
				if ($this->request->post ['perpetual_checkbox_notes_pin'] == '') {
					$json ['perpetual_checkbox_notes_pin'] = 'This is required field!';
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
				if ($this->request->post ['perpetual_checkbox_notes_pin'] != null && $this->request->post ['perpetual_checkbox_notes_pin'] != "") {
					$this->load->model ( 'user/user' );
					$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
					
					if (($this->request->post ['perpetual_checkbox_notes_pin'] != $user_info ['user_pin'])) {
						$json ['warning'] = 'Pin is incorrect, please try again.';
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
					
					/*$this->load->model ( 'user/user_group' );
					$user_role_info = $this->model_user_user_group->getUserGroup ( $user_info ['user_group_id'] );
					
					$perpetual_task = $user_role_info ['perpetual_task'];
					
					if ($perpetual_task != '1') {
						$json ['warning'] = "You are not authorized to end the task!";
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
					}*/
				}
			}
			
			if ($this->request->post ['enable_requires_approval_c11'] == '1') {
				
				if ($this->request->post ['enable_requires_approval_value'] == '') {
					$json ['warning'] = 'This is required field!';
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
				if ($this->request->post ['enable_requires_approval_value'] != null && $this->request->post ['enable_requires_approval_value'] != "") {
					$this->load->model ( 'user/user' );
					$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
					
					if (($this->request->post ['enable_requires_approval_value'] != $user_info ['user_pin'])) {
						$json ['warning'] = 'Pin is incorrect, please try again.';
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
					
					/*$this->load->model ( 'user/user_group' );
					$user_role_info = $this->model_user_user_group->getUserGroup ( $user_info ['user_group_id'] );
					
					$enable_requires_approval = $user_role_info ['enable_requires_approval'];
					
					if ($enable_requires_approval != '1') {
						$json ['warning'] = "You are not authorized to Complete the task!";
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
					}*/
				}
			}
			
			if ($this->request->post ['task_id'] != '') {
				
				$this->load->model ( 'createtask/createtask' );
				$result = $this->model_createtask_createtask->getStrikedatadetails ( $this->request->post ['task_id'] );
				$task_date = date ( 'm-d-Y', strtotime ( $result ['task_date'] ) );
				
				date_default_timezone_set ( $this->request->post ['facilitytimezone'] );
				
				$current_date = date ( 'm-d-Y', strtotime ( 'now' ) );
				
				if ($task_date > $current_date) {
					$json ['warning'] = "Task cannot be completed before designated time";
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
				
				if ($result ['id'] == null && $result ['id'] == "") {
					$json ['warning'] = "This task has been already completed. Please cancel and refresh the notes to review the task.";
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
				
				
				
				$this->load->model ( 'user/user' );
				//$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
				if( $this->request->post ['user_id'] != null &&  $this->request->post ['user_id'] != ""){
					$user_info = $this->model_user_user->getUserByUsername (  $this->request->post ['user_id']);
				}else{
					$user_info = $this->model_user_user->getUserByUsernamebynotes ($this->request->post['username'],$this->request->post ['facilities_id'] );
				}
				$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName ( $result ['tasktype'],$this->request->post ['facilities_id'] );
				
				
				if($tasktype_info['completed_user_roles'] != null && $tasktype_info['completed_user_roles'] != ""){
					$user_roles2 = explode ( ',', $tasktype_info ['completed_user_roles'] );
					if (!in_array ( $user_info['user_group_id'], $user_roles2 )) {
						$json ['warning'] = "You are not authorized to Complete the task!";
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
				if ($this->request->post ['perpetual_checkbox'] == '2') {
					if($tasktype_info['pause_user_roles'] != null && $tasktype_info['pause_user_roles'] != ""){
						$user_roles3 = explode ( ',', $tasktype_info ['pause_user_roles'] );
						if (!in_array ( $user_info['user_group_id'], $user_roles3 )) {
							
							$json ['warning'] = "You are not authorized to Pause on this task!";
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
				}
				
				if ($this->request->post ['perpetual_checkbox'] == '3') {
				if($tasktype_info['move_user_roles'] != null && $tasktype_info['move_user_roles'] != ""){
					$user_roles4 = explode ( ',', $tasktype_info ['move_user_roles'] );
					if (!in_array ( $user_info['user_group_id'], $user_roles4 )) {
						$json ['warning'] = "You are not authorized to Move on this task!";
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
				}
				if ($this->request->post ['perpetual_checkbox'] == '4') {
				if($tasktype_info['changeinterval_user_roles'] != null && $tasktype_info['changeinterval_user_roles'] != ""){
					$user_roles6 = explode ( ',', $tasktype_info ['changeinterval_user_roles'] );
					if (!in_array ( $user_info['user_group_id'], $user_roles6 )) {
						$json ['warning'] = "You are not authorized to Change Interval on this task!";
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
				}
				
				if ($this->request->post ['perpetual_checkbox'] == '1') {
					
					if($tasktype_info['user_roles'] != null && $tasktype_info['user_roles'] != ""){
						$user_roles1 = explode ( ',', $tasktype_info ['user_roles'] );
						
						if (!in_array ( $user_info['user_group_id'], $user_roles1 )) {
							$json ['warning'] = "You are not authorized to end the task!";
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
				}
				
				
				if ($this->request->post ['enable_requires_approval_c11'] == '1') {
					
					if ($this->request->post ['enable_requires_approval_value'] != null && $this->request->post ['enable_requires_approval_value'] != "") {
						
						if (($this->request->post ['enable_requires_approval_value'] != $user_info ['user_pin'])) {
							$this->error ['warning'] = $this->language->get ( 'error_exists' );
						}
						
						
						if($tasktype_info['requires_approval_user_roles'] != null && $tasktype_info['requires_approval_user_roles'] != ""){
							$user_roles21 = explode ( ',', $tasktype_info ['requires_approval_user_roles'] );
							
							if (!in_array ( $user_info['user_group_id'], $user_roles21 )) {
								$json ['warning'] = "You are not authorized to Complete the task!";
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
						
					
					}
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
				$data = array ();
				
				$data ['notes_pin'] = $this->request->post ['notes_pin'];
				$data ['user_id'] = $this->request->post ['user_id'];
				$data ['notes_type'] = $this->request->post ['notes_type'];
				
				$data ['comments'] = $this->request->post ['comments'];
				
				$data ['perpetual_checkbox'] = $this->request->post ['perpetual_checkbox'];
				$data ['perpetual_checkbox_notes_pin'] = $this->request->post ['perpetual_checkbox_notes_pin'];
				$data ['customlistvalues_id'] = $this->request->post ['customlistvalues_id'];
				$data ['customlistvalues_ids'] = $this->request->post ['customlistvalues_ids'];
				
				if ($this->request->post ['signature'] != null && $this->request->post ['signature'] != "") {
					$data ['imgOutput'] = $this->request->post ['signature'];
				}
				
				/*
				 * $jsonDecode = json_decode('{"task_type":"Bed Check","task_time":"06:59 PM","locations_id":"2","media_url":"","capacity":"Boys General"}', TRUE);
				 *
				 * var_dump($jsonDecode );
				 * echo "HHHHHHHHHHHHHHHHHHHHHHHHHHHH ";
				 * var_dump(json_decode($_POST['aaa'], true));
				 */
				
				$data ['facilitytimezone'] = $this->request->post ['facilitytimezone'];
				
				$data ['phone_device_id'] = $this->request->post ['phone_device_id'];
				
				$data ['is_pause'] = $this->request->post ['is_pause'];
				$data ['pause_time'] = $this->request->post ['pause_time'];
				$data ['pause_date'] = $this->request->post ['pause_date'];
				$data ['acttion_interval_id'] = $this->request->post ['acttion_interval_id'];
				$data ['facilitydrop'] = $this->request->post ['move_facility'];
				$data ['is_move'] = $this->request->post ['is_move'];
				$data ['notetime'] = $this->request->post ['notetime'];
				
				$data ['perpetual_checkbox'] = $this->request->post ['perpetual_checkbox'];
				$data ['perpetual_checkbox_notes_pin'] = $this->request->post ['perpetual_checkbox_notes_pin'];
				
				if ($this->request->post ['is_android'] != null && $this->request->post ['is_android'] != "") {
					$data ['is_android'] = $this->request->post ['is_android'];
				} else {
					$data ['is_android'] = '1';
				}
				
				/*
				 * var_dump($this->request->post['tasklocations']);
				 * var_dump($_POST['tasklocations']);
				 * var_dump(json_decode($_POST['tasklocations'], true));
				 * var_dump($_POST['carts']);
				 * var_dump(json_decode($_POST['carts'], true));
				 * $tasklocations = $_POST['tasklocations'];
				 * echo "BBBBBBBBBBBBBBBBB ";
				 * var_dump($tasklocations);
				 */
				
				// var_dump(json_decode($_POST['tasklocations']));
				
				// var_dump(json_decode($this->request->post['tasklocations']));
				
				$this->load->model ( 'createtask/createtask' );
				$result = $this->model_createtask_createtask->getStrikedatadetails ( $this->request->post ['task_id'] );
				
				$facilities_id = $result['facilityId'];
				
				$notes_id = $this->model_createtask_createtask->inserttask ( $result, $data, $facilities_id,'' );
			
				$this->load->model ( 'api/facerekognition' );
				$fre_array2 = array ();
				$fre_array2 ['face_notes_file'] = $this->request->post ['face_notes_file'];
				$fre_array2 ['outputFolder'] = $this->request->post ['outputFolder'];
				$fre_array2 ['face_not_verify'] = $this->request->post ['face_not_verify'];
				$fre_array2 ['facilities_id'] = $facilities_id;
				$fre_array2 ['notes_file'] = $facerekognition_response ['imagedata'] ['notes_file'];
				$fre_array2 ['outputFolder_1'] = $facerekognition_response ['imagedata'] ['outputFolder'];
				$fre_array2 ['notes_id'] = $notes_id;
				$this->model_api_facerekognition->savefacerekognitionnotes ( $fre_array2 );
				
				$this->load->model ( 'createtask/createtask' );
				$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName ( $result ['tasktype'],$result['facilityId'] );
				$relation_keyword_id = $tasktype_info ['relation_keyword_id'];
				
				if ($relation_keyword_id) {
					$this->load->model ( 'notes/notes' );
					$noteDetails = $this->model_notes_notes->getnotes ( $notes_id );
					
					$this->load->model ( 'setting/keywords' );
					$keyword_info = $this->model_setting_keywords->getkeywordDetail ( $relation_keyword_id );
					
					$data3 = array ();
					$data3 ['keyword_file'] = $keyword_info ['keyword_image'];
					$data3 ['notes_description'] = $noteDetails ['notes_description'];
					
					$this->model_notes_notes->addactiveNote ( $data3, $notes_id );
				}
				
				/*
				 * if($this->request->post['is_android'] == '2'){
				 * $tasklocations = $this->request->post['tasklocations'];
				 * }else{
				 */
				$jsonData = stripslashes ( html_entity_decode ( $_REQUEST ['tasklocations'] ) );
				$tasklocations = json_decode ( $jsonData, true );
				// }
				
				// echo "CCCCCCCCCCCCCC ";
				// print_r($tasklocations);
				
				$this->load->model ( 'setting/tags' );
				
				if(!empty($this->request->post ['rooms_remove'])){
					foreach ($this->request->post ['rooms_remove'] as $locationid){
						$this->model_setting_tags->updatetagroomblank ($locationid);
					}
				}
				
				if(!empty($this->request->post ['rooms'])){
					foreach ($this->request->post ['rooms'] as $key1=>$location_id){
						$this->model_setting_tags->updatetagroom ($location_id,$key1);
					}
				}
				
				
				$this->load->model ( 'user/user' );
				$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
				
				if ($tasklocations != null && $tasklocations != "") {
					foreach ( $tasklocations as $tasklocation ) {
						$task_content = $tasklocation ['location_name'] . ': ' . $tasklocation ['capacity'] . ' ' . $tasklocation ['location_type'];
						
						if ($tasklocation ['comments'] != null && $tasklocation ['comments'] != "") {
							// $task_content .= ' | ' .$tasklocation['comments'];
						}
						$task_content .= ' | Check at ' . date ( 'h:i A', strtotime ( $tasklocation ['task_time'])) . ' By ' . $user_info['username'];
						
						if ($tasklocation ['task_type'] != "manual") {
							$task_content .= ' | ' . $tasklocation ['task_type'];
						}
						
						date_default_timezone_set ( $this->request->post ['facilitytimezone'] );
						$date_added = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
						
						$this->load->model ( 'setting/locations' );
						$location_info = $this->model_setting_locations->getlocation ( $tasklocation ['locations_id'] );
						
						$comments = '';
						if ($tasklocation ['customlistvalues_id']) {
							
							$this->load->model ( 'notes/notes' );
							$custom_info = $this->model_notes_notes->getcustomlistvalue ( $tasklocation ['customlistvalues_id'] );
							
							$customlistvalues_name = str_replace ( "'", "&#039;", html_entity_decode ( $custom_info ['customlistvalues_name'], ENT_QUOTES ) );
							
							$comments .= ' | ' . $customlistvalues_name;
							$task_content .= ' | ' . $customlistvalues_name;
						}
						
						if ($tasklocation ['customlistvalues_ids']) {
							
							$this->load->model ( 'notes/notes' );
							
							$customlistvalues_ids = explode ( ',', $tasklocation ['customlistvalues_ids'] );
							
							foreach ( $customlistvalues_ids as $customlistvalues_id ) {
								
								$custom_info = $this->model_notes_notes->getcustomlistvalue ( $customlistvalues_id );
								
								$customlistvalues_name = $custom_info ['customlistvalues_name'];
								
								$comments .= ' | ' . $customlistvalues_name;
								$task_content .= ' | ' . $customlistvalues_name;
							}
						}
						
						if ($tasklocation ['comments'] != null && $tasklocation ['comments']) {
							$comments .= ' | ' . $tasklocation ['comments'];
							$task_content .= ' | ' . $tasklocation ['comments'];
						}
						
						if ($tasklocation ['tags_ids'] != null && $tasklocation ['tags_ids'] != "") {
							$tags_ids1 = explode ( ',', $tasklocation ['tags_ids'] );
							
							foreach ( $tags_ids1 as $tag1 ) {
								$tags_info1 = $this->model_setting_tags->getTag ( $tag1 );
								
								if ($tags_info1 ['emp_first_name']) {
									$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
								} else {
									$emp_tag_id = $tags_info1 ['emp_tag_id'];
								}
								
								if ($tags_info1) {
									$task_content .= ' | ' . $emp_tag_id . ', ';
								}
							}
						}
						
						if ($tasklocation ['out_tags_ids'] != null && $tasklocation ['out_tags_ids'] != "") {
							$tags_ids1 = explode ( ',', $tasklocation ['out_tags_ids'] );
							
							foreach ( $tags_ids1 as $tag1 ) {
								$tags_info1 = $this->model_setting_tags->getTag ( $tag1 );
								
								if ($tags_info1 ['emp_first_name']) {
									$emp_tag_id = $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
								} else {
									$emp_tag_id = $tags_info1 ['emp_tag_id'];
								}
								
								if ($tags_info1) {
									$task_content .= ' | ' . $emp_tag_id . ', ';
								}
							}
							
							$out_capacity1 = count ( $tags_ids1 );
						}
						
						if ($tasklocation ['out_capacity'] != null && $tasklocation ['out_capacity'] != "") {
							$out_capacity = $tasklocation ['out_capacity'];
						} else {
							$out_capacity = $out_capacity1;
						}
						
						$fre_array2c = array ();
						$fre_array2c ['client_face_notes_file'] = $tasklocation ['client_face_notes_file'];
						$fre_array2c ['client_outputFolder'] = $tasklocation ['client_outputFolder'];
						$fre_array2c ['facilities_id'] = $facilities_id;
						$fre_array2c ['notes_id'] = $notes_id;
						// $client_file = $this->model_api_facerekognition->savefacerekognitiontask($fre_array2c);
						
						// unlink($this->request->post['client_outputFolder_small']);
						
						$tdata1 = array ();
						$tdata1 ['notes_id'] = $notes_id;
						$tdata1 ['task_content'] = $task_content;
						$tdata1 ['date_added'] = $date_added;
						$tdata1 ['location_type'] = $location_info ['location_type'];
						$tdata1 ['customlistvalues_id'] = $location_info ['customlistvalues_id'];
						$tdata1 ['facilities_id'] = $facilities_id;
						$tdata1 ['task_comments'] = $comments;
						$tdata1 ['out_capacity'] = $out_capacity;
						$tdata1 ['medication_attach_url'] = $tasklocation ['client_outputFolderUrl'];
						
						$this->model_createtask_createtask->insertTaskbedcheck ( $tasklocation, $this->request->post, $tdata1 );
					}
				}
				
				$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
				
				$this->load->model ( 'notes/notes' );
				$this->model_notes_notes->updatenotetaskbedcheck ( $this->request->post ['notes_task_type'], $update_date, $notes_id );
				
				
				if ($this->request->post ['perpetual_checkbox'] == '1') {
					
					$this->load->model ( 'notes/notes' );
					$timeZone = date_default_timezone_set ( $this->request->post ['facilitytimezone'] );
					$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
					$date_added = ( string ) $noteDate;
					
					$data = array ();
					
					$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
					$data ['imgOutput'] = $this->request->post ['signature'];
					
					$data ['notes_pin'] = $this->request->post ['notes_pin'];
					$data ['user_id'] = $this->request->post ['user_id'];
					
					$data ['notetime'] = $notetime;
					$data ['note_date'] = $date_added;
					
					/*
					 * if($this->request->post['comments'] != null && $this->request->post['comments']){
					 * $comments = ' | '.$this->request->post['comments'];
					 * }
					 */
					
					$this->load->model ( 'createtask/createtask' );
					
					$this->load->model ( 'setting/keywords' );
					
					$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName ( $result ['tasktype'],$result['facilityId'] );
					
					$keywordData12 = $this->model_setting_keywords->getkeywordDetail ( $tasktype_info ['relation_keyword_id'] );
					$keywordData13 = $this->model_setting_keywords->getkeywordDetail ( $keywordData12 ['relation_keyword_id'] );
					
					$data ['keyword_file'] = $keywordData13 ['keyword_image'];
					
					$keywordData2 = $this->model_setting_keywords->getkeywordDetaildesc ( $data ['keyword_file'], $facilities_id );
					
					$notetasktime = date ( 'H:i:s', strtotime ( $result ['task_time'] ) );
					
					$data ['notes_description'] = $keywordData2 ['keyword_name'] . ' | ENDED | ' . date ( 'h:i A', strtotime ( $notetasktime ) ) . ' ' . $comments;
					
					$data ['date_added'] = $date_added;
					
					$data ['phone_device_id'] = $this->request->post ['phone_device_id'];
					$data ['device_unique_id'] = $this->request->post ['device_unique_id'];
					
					$data ['linked_id'] = $result ['linked_id'];
					
					if ($this->request->post ['is_android'] != null && $this->request->post ['is_android'] != "") {
						$data ['is_android'] = $this->request->post ['is_android'];
					} else {
						$data ['is_android'] = '1';
					}
					
					$notesid = $this->model_notes_notes->jsonaddnotes ( $data, $facilities_id );
					
					$this->model_notes_notes->updatenotetask ( $result ['parent_id'], $notesid );
					
					if ($result ['emp_tag_id'] != null && $result ['emp_tag_id'] != "") {
						$this->load->model ( 'notes/notes' );
						
						date_default_timezone_set ( $this->request->post ['facilitytimezone'] );
						$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
						
						$this->load->model ( 'notes/tags' );
						$taginfo = $this->model_notes_tags->getTag ( $result ['emp_tag_id'] );
						$tadata = array();
						$this->model_notes_notes->updateNotesTag ( $taginfo ['emp_tag_id'], $notesid, $taginfo ['tags_id'], $update_date,$tadata );
					}
				}
				
				$this->model_createtask_createtask->updatetaskNote ( $this->request->post ['task_id'] );
				$this->model_createtask_createtask->deteteIncomTask ( $facilities_id );
				
				
				//$this->load->model ( 'notes/notes' );
				//$noteinfo = $this->model_notes_notes->getnotes ( $notes_id );
				
				$this->data ['facilitiess'] [] = array (
					'warning' => '1' ,
					'notes_id' =>$notes_id,
					
				);
				$error = true;
			} else {
				
				$this->data ['facilitiess'] [] = array (
						'warning' => $json ['warning'] 
				);
				$error = false;
			}
			
			$value = array (
					'results' => $this->data ['facilitiess'],
					'status' => $error 
			);
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in apptask jsonSavetaskBy2 ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_jsonSavetaskBy2', $activity_data2 );
			// echo 'Caught exception: ', $e->getMessage(), "\n";
		}
	}
	public function jsonSavetaskBymedication() {
		try {
			
			$this->load->model ( 'activity/activity' );
			$this->model_activity_activity->addActivitySave ( 'jsonSavetaskBymedication', $this->request->post, 'request' );
			
			$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			
			$api_device_info = $this->model_api_encrypt->getdevicedetails ( $cre_array );
			
			if ($api_device_info == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$api_header_value = $this->model_api_encrypt->getallheaders1 ();
			
			if ($api_header_value == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			$this->data ['facilitiess'] = array ();
			$this->load->model ( 'createtask/createtask' );
			$json = array ();
			
			if (! $this->request->post ['task_id']) {
				$json ['warning'] = 'Please select id!.';
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
			
			if ($this->request->post ['user_id'] == '') {
				$json ['warning'] = 'Please select user id!.';
				
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
			
			/*
			 * if ($this->request->post['select_one'] == '') {
			 * $json['warning'] = 'Please Select One';
			 * }
			 */
			
			if ($this->request->post ['notes_pin'] != null && $this->request->post ['notes_pin'] != "") {
				$this->load->model ( 'user/user' );
				$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
				
				if (($this->request->post ['notes_pin'] != $user_info ['user_pin'])) {
					$json ['warning'] = 'Pin is incorrect, please try again..';
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
			
			if ($this->request->post ['user_id'] != '') {
				$this->load->model ( 'user/user' );
				$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
				
				if (empty ( $user_info )) {
					$json ['warning'] = 'incorrect username';
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
			
			if ($this->request->post ['perpetual_checkbox'] == '1') {
				if ($this->request->post ['perpetual_checkbox_notes_pin'] == '') {
					$json ['perpetual_checkbox_notes_pin'] = 'This is required field!';
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
				if ($this->request->post ['perpetual_checkbox_notes_pin'] != null && $this->request->post ['perpetual_checkbox_notes_pin'] != "") {
					$this->load->model ( 'user/user' );
					$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
					
					if (($this->request->post ['perpetual_checkbox_notes_pin'] != $user_info ['user_pin'])) {
						$json ['warning'] = 'Pin is incorrect, please try again.';
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
					
					/*$this->load->model ( 'user/user_group' );
					$user_role_info = $this->model_user_user_group->getUserGroup ( $user_info ['user_group_id'] );
					
					$perpetual_task = $user_role_info ['perpetual_task'];
					
					if ($perpetual_task != '1') {
						$json ['warning'] = "You are not authorized to end the task!";
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
					}*/
				}
			}
			
			if ($this->request->post ['enable_requires_approval_c11'] == '1') {
				
				if ($this->request->post ['enable_requires_approval_value'] == '') {
					$json ['warning'] = 'This is required field!';
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
				if ($this->request->post ['enable_requires_approval_value'] != null && $this->request->post ['enable_requires_approval_value'] != "") {
					$this->load->model ( 'user/user' );
					$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
					
					if (($this->request->post ['enable_requires_approval_value'] != $user_info ['user_pin'])) {
						$json ['warning'] = 'Pin is incorrect, please try again.';
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
					
					/*$this->load->model ( 'user/user_group' );
					$user_role_info = $this->model_user_user_group->getUserGroup ( $user_info ['user_group_id'] );
					
					$enable_requires_approval = $user_role_info ['enable_requires_approval'];
					
					if ($enable_requires_approval != '1') {
						$json ['warning'] = "You are not authorized to Complete the task!";
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
					}*/
				}
			}
			
			if ($this->request->post ['task_id'] != '') {
				
				$this->load->model ( 'createtask/createtask' );
				$result = $this->model_createtask_createtask->getStrikedatadetails ( $this->request->post ['task_id'] );
				$task_date = date ( 'm-d-Y', strtotime ( $result ['task_date'] ) );
				
				date_default_timezone_set ( $this->request->post ['facilitytimezone'] );
				
				$current_date = date ( 'm-d-Y', strtotime ( 'now' ) );
				
				if ($task_date > $current_date) {
					$json ['warning'] = "Task cannot be completed before designated time";
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
				
				if ($result ['id'] == null && $result ['id'] == "") {
					$json ['warning'] = "This task has been already completed. Please cancel and refresh the notes to review the task.";
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
				
				
				
				$this->load->model ( 'user/user' );
				//$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
				if( $this->request->post ['user_id'] != null &&  $this->request->post ['user_id'] != ""){
					$user_info = $this->model_user_user->getUserByUsername (  $this->request->post ['user_id']);
				}else{
					$user_info = $this->model_user_user->getUserByUsernamebynotes ($this->request->post['username'],$this->request->post ['facilities_id'] );
				}
				$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName ( $result ['tasktype'],$this->request->post ['facilities_id'] );
				
				
				if($tasktype_info['completed_user_roles'] != null && $tasktype_info['completed_user_roles'] != ""){
					$user_roles2 = explode ( ',', $tasktype_info ['completed_user_roles'] );
					if (!in_array ( $user_info['user_group_id'], $user_roles2 )) {
						$json ['warning'] = "You are not authorized to Complete the task!";
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
				if ($this->request->post ['perpetual_checkbox'] == '2') {
					if($tasktype_info['pause_user_roles'] != null && $tasktype_info['pause_user_roles'] != ""){
						$user_roles3 = explode ( ',', $tasktype_info ['pause_user_roles'] );
						if (!in_array ( $user_info['user_group_id'], $user_roles3 )) {
							
							$json ['warning'] = "You are not authorized to Pause on this task!";
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
				}
				
				if ($this->request->post ['perpetual_checkbox'] == '3') {
				if($tasktype_info['move_user_roles'] != null && $tasktype_info['move_user_roles'] != ""){
					$user_roles4 = explode ( ',', $tasktype_info ['move_user_roles'] );
					if (!in_array ( $user_info['user_group_id'], $user_roles4 )) {
						$json ['warning'] = "You are not authorized to Move on this task!";
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
				}
				if ($this->request->post ['perpetual_checkbox'] == '4') {
				if($tasktype_info['changeinterval_user_roles'] != null && $tasktype_info['changeinterval_user_roles'] != ""){
					$user_roles6 = explode ( ',', $tasktype_info ['changeinterval_user_roles'] );
					if (!in_array ( $user_info['user_group_id'], $user_roles6 )) {
						$json ['warning'] = "You are not authorized to Change Interval on this task!";
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
				}
				
				if ($this->request->post ['perpetual_checkbox'] == '1') {
					
					if($tasktype_info['user_roles'] != null && $tasktype_info['user_roles'] != ""){
						$user_roles1 = explode ( ',', $tasktype_info ['user_roles'] );
						
						if (!in_array ( $user_info['user_group_id'], $user_roles1 )) {
							$json ['warning'] = "You are not authorized to end the task!";
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
				}
				
				
				if ($this->request->post ['enable_requires_approval_c11'] == '1') {
					
					if ($this->request->post ['enable_requires_approval_value'] != null && $this->request->post ['enable_requires_approval_value'] != "") {
						
						if (($this->request->post ['enable_requires_approval_value'] != $user_info ['user_pin'])) {
							$this->error ['warning'] = $this->language->get ( 'error_exists' );
						}
						
						
						if($tasktype_info['requires_approval_user_roles'] != null && $tasktype_info['requires_approval_user_roles'] != ""){
							$user_roles21 = explode ( ',', $tasktype_info ['requires_approval_user_roles'] );
							
							if (!in_array ( $user_info['user_group_id'], $user_roles21 )) {
								$json ['warning'] = "You are not authorized to Complete the task!";
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
						
					
					}
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
				$data = array ();
				
				$data ['notes_pin'] = $this->request->post ['notes_pin'];
				$data ['user_id'] = $this->request->post ['user_id'];
				$data ['notes_type'] = $this->request->post ['notes_type'];
				
				$data ['comments'] = $this->request->post ['comments'];
				
				$data ['perpetual_checkbox'] = $this->request->post ['perpetual_checkbox'];
				$data ['perpetual_checkbox_notes_pin'] = $this->request->post ['perpetual_checkbox_notes_pin'];
				$data ['customlistvalues_id'] = $this->request->post ['customlistvalues_id'];
				$data ['customlistvalues_ids'] = $this->request->post ['customlistvalues_ids'];
				
				$data ['phone_device_id'] = $this->request->post ['phone_device_id'];
				
				$data ['is_pause'] = $this->request->post ['is_pause'];
				$data ['pause_time'] = $this->request->post ['pause_time'];
				$data ['pause_date'] = $this->request->post ['pause_date'];
				$data ['acttion_interval_id'] = $this->request->post ['acttion_interval_id'];
				$data ['facilitydrop'] = $this->request->post ['move_facility'];
				$data ['is_move'] = $this->request->post ['is_move'];
				
				if ($this->request->post ['is_android'] != null && $this->request->post ['is_android'] != "") {
					$data ['is_android'] = $this->request->post ['is_android'];
				} else {
					$data ['is_android'] = '1';
				}
				
				if ($this->request->post ['signature'] != null && $this->request->post ['signature'] != "") {
					$data ['imgOutput'] = $this->request->post ['signature'];
				}
				
				/*
				 * $jsonDecode = json_decode('{"task_type":"Bed Check","task_time":"06:59 PM","locations_id":"2","media_url":"","capacity":"Boys General"}', TRUE);
				 *
				 * var_dump($jsonDecode );
				 * echo "HHHHHHHHHHHHHHHHHHHHHHHHHHHH ";
				 * var_dump(json_decode($_POST['aaa'], true));
				 */
				
				$data ['facilitytimezone'] = $this->request->post ['facilitytimezone'];
				
				/*
				 * var_dump($this->request->post['tasklocations']);
				 * var_dump($_POST['tasklocations']);
				 * var_dump(json_decode($_POST['tasklocations'], true));
				 * var_dump($_POST['carts']);
				 * var_dump(json_decode($_POST['carts'], true));
				 * $tasklocations = $_POST['tasklocations'];
				 * echo "BBBBBBBBBBBBBBBBB ";
				 * var_dump($tasklocations);
				 */
				
				// var_dump(json_decode($_POST['tasklocations']));
				
				// var_dump(json_decode($this->request->post['tasklocations']));
				
				$this->load->model ( 'createtask/createtask' );
				$result = $this->model_createtask_createtask->getStrikedatadetails ( $this->request->post ['task_id'] );
				
				$facilities_id = $result['facilityId'];
				
				$notes_id = $this->model_createtask_createtask->inserttask ( $result, $data, $facilities_id,'' );
				
				$this->load->model ( 'api/facerekognition' );
				$fre_array2 = array ();
				$fre_array2 ['face_notes_file'] = $this->request->post ['face_notes_file'];
				$fre_array2 ['outputFolder'] = $this->request->post ['outputFolder'];
				$fre_array2 ['face_not_verify'] = $this->request->post ['face_not_verify'];
				$fre_array2 ['facilities_id'] = $facilities_id;
				$fre_array2 ['notes_file'] = $facerekognition_response ['imagedata'] ['notes_file'];
				$fre_array2 ['outputFolder_1'] = $facerekognition_response ['imagedata'] ['outputFolder'];
				$fre_array2 ['notes_id'] = $notes_id;
				// var_dump($fre_array2);
				$this->model_api_facerekognition->savefacerekognitionnotes ( $fre_array2 );
				
				$this->load->model ( 'createtask/createtask' );
				$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName ( $result ['tasktype'],$result['facilityId'] );
				$relation_keyword_id = $tasktype_info ['relation_keyword_id'];
				
				if ($relation_keyword_id) {
					$this->load->model ( 'notes/notes' );
					$noteDetails = $this->model_notes_notes->getnotes ( $notes_id );
					
					$this->load->model ( 'setting/keywords' );
					$keyword_info = $this->model_setting_keywords->getkeywordDetail ( $relation_keyword_id );
					
					$data3 = array ();
					$data3 ['keyword_file'] = $keyword_info ['keyword_image'];
					$data3 ['notes_description'] = $noteDetails ['notes_description'];
					
					$this->model_notes_notes->addactiveNote ( $data3, $notes_id );
				}
				
				/*
				 * if($this->request->post['is_android'] == '2'){
				 * $tasklocations = $this->request->post['tasklocations'];
				 *
				 * }else{
				 */
				$jsonData = html_entity_decode ( $_REQUEST ['tasklocations'] );
				$tasklocations = json_decode ( $jsonData, true );
				// }
				
				// echo "CCCCCCCCCCCCCC ";
				// print_r($tasklocations);
				
				if ($tasklocations != null && $tasklocations != "") {
					$mediaUrl = array ();
					foreach ( $tasklocations as $tasklocation ) {
						$task_content = 'Resident ' . $this->request->post ['emp_tag_id'] . ':' . $this->request->post ['emp_first_name'];
						
						date_default_timezone_set ( $this->request->post ['facilitytimezone'] );
						$date_added = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
						
						$comments = '';
						if ($tasklocation ['customlistvalues_id']) {
							
							$this->load->model ( 'notes/notes' );
							$custom_info = $this->model_notes_notes->getcustomlistvalue ( $tasklocation ['customlistvalues_id'] );
							
							$customlistvalues_name = str_replace ( "'", "&#039;", html_entity_decode ( $custom_info ['customlistvalues_name'], ENT_QUOTES ) );
							
							$comments .= ' | ' . $customlistvalues_name;
						}
						
						if ($tasklocation ['customlistvalues_ids']) {
							
							$this->load->model ( 'notes/notes' );
							
							$customlistvalues_ids = explode ( ',', $tasklocation ['customlistvalues_ids'] );
							
							foreach ( $customlistvalues_ids as $customlistvalues_id ) {
								
								$custom_info = $this->model_notes_notes->getcustomlistvalue ( $customlistvalues_id );
								
								$customlistvalues_name = $custom_info ['customlistvalues_name'];
								
								$comments .= ' | ' . $customlistvalues_name;
							}
						}
						
						if ($tasklocation ['comments'] != null && $tasklocation ['comments']) {
							$comments .= ' | ' . $tasklocation ['comments'];
						}
						
						$tdata1 = array ();
						$tdata1 ['notes_id'] = $notes_id;
						$tdata1 ['task_content'] = $task_content;
						$tdata1 ['date_added'] = $date_added;
						$tdata1 ['tags_id'] = $this->request->post ['tag_id'];
						$tdata1 ['drug_name'] = $tasklocation ['drug_name'];
						$tdata1 ['dose'] = $tasklocation ['dose'];
						$tdata1 ['drug_type'] = $tasklocation ['drug_type'];
						$tdata1 ['frequency'] = $tasklocation ['frequency'];
						$tdata1 ['instructions'] = $tasklocation ['instructions'];
						$tdata1 ['count'] = $tasklocation ['count'];
						$tdata1 ['task_type'] = $tasklocation ['task_type'];
						$tdata1 ['refuse'] = $tasklocation ['refuse'];
						$tdata1 ['facilities_id'] = $facilities_id;
						$tdata1 ['task_comments'] = $comments;
						
						$medication_info = $this->model_createtask_createtask->gettaskmedicationdetail ( $result ['id'], $tasklocation ['tags_medication_details_id'] );
						
						$tdata1 ['complete_status'] = $medication_info ['complete_status'];
						
						$this->load->model ( 'setting/tags' );
						$mdrug_info = $this->model_setting_tags->getTagsMedicationdruglByID ( $tasklocation ['tags_medication_details_id'] );
						
						$pre_quantity = $mdrug_info ['drug_mg'];
						$dosage = $mdrug_info ['drug_alertnate'];
						$final = $pre_quantity - $dosage;
						$drug_quantity = $final;
						
						$notes_by_task_id = $this->model_createtask_createtask->insertTaskmedicine ( $tasklocation, $this->request->post, $tdata1 );
						
						if($tasklocation ['refuse'] == '0'){
							$this->model_setting_tags->updateQuantityMedication ( $tasklocation ['tags_medication_details_id'], $drug_quantity );
						}
						
						/*
						 * $sql2 = "INSERT INTO `" . DB_PREFIX . "notes_by_task` SET
						 * notes_id = '".$notes_id."', locations_id ='".$tasklocation['locations_id']."', task_type= '".$tasklocation['task_type']."', task_content = '".$this->db->escape($task_content)."', signature= '".$this->db->escape($tasklocation['medication_signature'])."', user_id= '".$this->db->escape($tasklocation['medication_user_id'])."', date_added = '".$date_added."', notes_pin = '".$tasklocation['medication_notes_pin']."', notes_type = '".$this->request->post['notes_type']."', task_time = '".$tasklocation['task_time']."' , media_url = '".$tasklocation['media_url']."', capacity = '".$tasklocation['capacity']."', location_name = '".$this->db->escape($tasklocation['location_name'])."', location_type = '".$tasklocation['location_type']."', notes_task_type = '".$this->request->post['notes_task_type']."', tags_id = '".$this->request->post['tag_id']."', drug_name = '".$this->db->escape($tasklocation['drug_name'])."', dose = '".$tasklocation['dose']."', drug_type = '".$tasklocation['drug_type']."', quantity = '".$tasklocation['quantity']."', frequency = '".$tasklocation['frequency']."', instructions = '".$this->db->escape($tasklocation['instructions'])."', count = '".$tasklocation['count']."', createtask_by_group_id = '".$tasklocation['createtask_by_group_id']."', task_comments = '".$this->db->escape($tasklocation['comments'])."', medication_attach_url = '".$tasklocation['medication_attach_url']."',medication_file_upload='1' , tags_medication_details_id = '".$tasklocation['tags_medication_details_id']."' , tags_medication_id = '".$tasklocation['tags_medication_id']."' ";
						 *
						 * $this->db->query($sql2);
						 * $notes_by_task_id = $this->db->getLastId();
						 */
						$mediaUrl [] = array (
								'notes_by_task_id' => $notes_by_task_id,
								'path' => $tasklocation ['media_url'] 
						);
					}
				}
				
				$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
				
				if ($this->request->post ['emp_tag_id'] != null && $this->request->post ['emp_tag_id'] != "") {
					$this->load->model ( 'notes/notes' );
					$tadata = array();
					$this->model_notes_notes->updateNotesTag ( $this->request->post ['emp_tag_id'], $notes_id, $this->request->post ['tag_id'], $update_date,$tadata );
				}
				
				if ($this->request->post ['perpetual_checkbox'] == '1') {
						
					$this->load->model ( 'notes/notes' );
					$timeZone = date_default_timezone_set ( $this->request->post ['facilitytimezone'] );
					$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
					$date_added = ( string ) $noteDate;
						
					$data = array ();
						
					$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
					$data ['imgOutput'] = $this->request->post ['signature'];
						
					$data ['notes_pin'] = $this->request->post ['notes_pin'];
					$data ['user_id'] = $this->request->post ['user_id'];
						
					$data ['notetime'] = $notetime;
					$data ['note_date'] = $date_added;
						
					/*
					 * if($this->request->post['comments'] != null && $this->request->post['comments']){
					 * $comments = ' | '.$this->request->post['comments'];
					 * }
					 */
						
					$this->load->model ( 'createtask/createtask' );
						
					$this->load->model ( 'setting/keywords' );
						
					$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName ( $result ['tasktype'],$result['facilityId'] );
						
					$keywordData12 = $this->model_setting_keywords->getkeywordDetail ( $tasktype_info ['relation_keyword_id'] );
					$keywordData13 = $this->model_setting_keywords->getkeywordDetail ( $keywordData12 ['relation_keyword_id'] );
						
					$data ['keyword_file'] = $keywordData13 ['keyword_image'];
						
					$keywordData2 = $this->model_setting_keywords->getkeywordDetaildesc ( $data ['keyword_file'], $facilities_id );
						
					$notetasktime = date ( 'H:i:s', strtotime ( $result ['task_time'] ) );
						
					$data ['notes_description'] = $keywordData2 ['keyword_name'] . ' | ENDED | ' . date ( 'h:i A', strtotime ( $notetasktime ) ) . ' ' . $comments;
						
					$data ['date_added'] = $date_added;
						
					$data ['phone_device_id'] = $this->request->post ['phone_device_id'];
					$data ['device_unique_id'] = $this->request->post ['device_unique_id'];
						
					$data ['linked_id'] = $result ['linked_id'];
						
					if ($this->request->post ['is_android'] != null && $this->request->post ['is_android'] != "") {
						$data ['is_android'] = $this->request->post ['is_android'];
					} else {
						$data ['is_android'] = '1';
					}
						
					$notesid = $this->model_notes_notes->jsonaddnotes ( $data, $facilities_id );
						
					$this->model_notes_notes->updatenotetask ( $result ['parent_id'], $notesid );
						
					if ($result ['emp_tag_id'] != null && $result ['emp_tag_id'] != "") {
						$this->load->model ( 'notes/notes' );
				
						date_default_timezone_set ( $this->request->post ['facilitytimezone'] );
						$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
				
						$this->load->model ( 'notes/tags' );
						$taginfo = $this->model_notes_tags->getTag ( $result ['emp_tag_id'] );
						$tadata = array();
						$this->model_notes_notes->updateNotesTag ( $taginfo ['emp_tag_id'], $notesid, $taginfo ['tags_id'], $update_date,$tadata );
					}
				}
				
				$this->model_createtask_createtask->updatetaskNote ( $this->request->post ['task_id'] );
				$this->model_createtask_createtask->deteteIncomTask ( $facilities_id );
				
				$sql12 = "DELETE FROM`" . DB_PREFIX . "createtask_by_medication` where id = '" . $this->request->post ['task_id'] . "' ";
				$this->db->query ( $sql12 );
				
				
				//$this->load->model ( 'notes/notes' );
				//$noteinfo = $this->model_notes_notes->getnotes ( $notes_id );
				
				$this->data ['facilitiess'] [] = array (
						'warning' => '1',
						'mediaUrl' => $mediaUrl ,
						'notes_id' => $notes_id ,
				);
				$error = true;
			} else {
				
				$this->data ['facilitiess'] [] = array (
						'warning' => $json ['warning'] 
				);
				$error = false;
			}
			
			$value = array (
					'results' => $this->data ['facilitiess'],
					'status' => $error 
			);
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in apptask jsonSavetaskBy2 ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_jsonSavetaskBy2', $activity_data2 );
			// echo 'Caught exception: ', $e->getMessage(), "\n";
		}
	}
	public function jsonuploadmedicationFile() {
		try {
			
			$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			
			$api_device_info = $this->model_api_encrypt->getdevicedetails ( $cre_array );
			
			if ($api_device_info == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$api_header_value = $this->model_api_encrypt->getallheaders1 ();
			
			if ($api_header_value == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$json = array ();
			$this->data ['facilitiess'] = array ();
			
			if ($this->request->files ["upload_file"] != null && $this->request->files ["upload_file"] != "") {
				
				$extension = end ( explode ( ".", $this->request->files ["upload_file"] ["name"] ) );
				
				if ($this->request->files ["upload_file"] ["size"] < 42214400) {
					$neextension = strtolower ( $extension );
					// if($neextension != 'mp4' && $neextension != 'mp3' && $neextension != 'flv' && $neextension != '3gp' && $neextension != 'wav' && $neextension != 'mkv' && $neextension != 'avi'){
					
					// $notes_file = uniqid( ) . "." . $extension;
					// $outputFolder = DIR_IMAGE.'files/' . $notes_file;
					// move_uploaded_file($this->request->files["upload_file"]["tmp_name"], $outputFolder);
					
					// $outputFolderUrl = HTTP_SERVER.'image/files/' . $notes_file;
					
					$notes_file = 'devbolb' . rand () . '.' . $extension;
					$outputFolder = $this->request->files ["upload_file"] ["tmp_name"];
					
					// $outputFolderUrl = 'https://dev1cdn.azureedge.net/'.$notes_file;
					// require_once(DIR_SYSTEM . 'library/awsstorage/s3_config.php');
					$s3file = $this->awsimageconfig->uploadFile ( $notes_file, $outputFolder, $this->request->post ['facilities_id'] );
					
					$outputFolderUrl = $s3file;
					
					// require_once(DIR_SYSTEM . 'library/azure_storage/config.php');
					
					$sqlu = "UPDATE `" . DB_PREFIX . "notes_by_task` SET media_url = '" . $outputFolderUrl . "',medication_file_upload='0' where notes_by_task_id = '" . $this->request->post ['notes_by_task_id'] . "' ";
					$this->db->query ( $sqlu );
					
					$error = true;
					
					$this->data ['facilitiess'] [] = array (
							'success' => '1',
							'notes_file' => $outputFolderUrl,
							'notes_file_url' => $outputFolderUrl,
							'notes_media_extention' => $extension 
					);
					/*
					 * }else{
					 * $this->data['facilitiess'][] = array(
					 * 'warning' => 'video or audio file not valid!',
					 * );
					 * $error = false;
					 * }
					 */
				} else {
					$this->data ['facilitiess'] [] = array (
							'warning' => 'Maximum size file upload!' 
					);
					$error = false;
				}
			} else {
				$this->data ['facilitiess'] [] = array (
						'warning' => 'Please select file!' 
				);
				$error = false;
			}
			
			$value = array (
					'results' => $this->data ['facilitiess'],
					'status' => $error 
			);
			
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in appservices jsonuploadFile ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_jsonuploadFile', $activity_data2 );
			
			// echo 'Caught exception: ', $e->getMessage(), "\n";
		}
	}
	public function createclient() {
		try {
			$json = array ();
			
			$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			
			$api_device_info = $this->model_api_encrypt->getdevicedetails ( $cre_array );
			
			if ($api_device_info == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$api_header_value = $this->model_api_encrypt->getallheaders1 ();
			
			if ($api_header_value == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$this->data ['facilitiess'] = array ();
			
			if ($this->request->post ['client_name']) {
				$this->load->model ( 'notes/tags' );
				
				$client_info = $this->model_notes_tags->getClient ( $this->request->post ['client_name'] );
				
				if ($client_info) {
					$this->data ['facilitiess'] = array (
							'client_name' => $client_info ['client_name'],
							'client_id' => $client_info ['client_id'] 
					);
				} else {
					$client_id = $this->model_notes_tags->createClient ( $this->request->post ['client_name'] );
					$this->data ['facilitiess'] = array (
							'client_name' => $this->request->post ['client_name'],
							'client_id' => $client_id 
					);
				}
				
				$error = true;
			} else {
				$this->data ['facilitiess'] [] = array (
						'warning' => 'Please enter name' 
				);
				$error = false;
			}
			
			$value = array (
					'results' => $this->data ['facilitiess'],
					'status' => $error 
			);
			
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in Task create client ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'createclient', $activity_data2 );
		}
	}
	public function jsongetcustomelist() {
		try {
			
			$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			/*
			$api_device_info = $this->model_api_encrypt->getdevicedetails ( $cre_array );
			
			if ($api_device_info == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$api_header_value = $this->model_api_encrypt->getallheaders1 ();
			
			if ($api_header_value == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			*/
			
			$this->data ['facilitiess'] = array ();
			$this->load->model ( 'createtask/createtask' );
			
			$result = $this->model_createtask_createtask->gettaskrow ( $this->request->post ['task_id'] );
			
			$assign_to = $result ['assign_to'];
			
			$this->data ['recurrence_save_1'] = $result ['recurrence'];
			
			$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName ( $result ['tasktype'],$result['facilityId'] );
			$tasktype_id = $tasktype_info ['task_id'];
			
			$this->load->model ( 'notes/notes' );
			
			if ($tasktype_info ['customlist_id']) {
				
				$d = array ();
				$d ['customlist_id'] = $tasktype_info ['customlist_id'];
				$customlists = $this->model_notes_notes->getcustomlists ( $d );
				
				if (! empty ( $customlists )) {
					foreach ( $customlists as $customlist ) {
						$d2 = array ();
						$d2 ['customlist_id'] = $customlist ['customlist_id'];
						
						$customlistvalues = $this->model_notes_notes->getcustomlistvalues ( $d2 );
						
						$this->data ['facilitiess'] [] = array (
								'customlist_id' => $customlist ['customlist_id'],
								'customlist_name' => $customlist ['customlist_name'],
								'customlistvalues' => $customlistvalues 
						);
					}
					$error = true;
				} else {
					$this->data ['facilitiess'] [] = array (
							'warning' => "Custom list not found" 
					);
					$error = false;
					
					$value = array (
							'results' => $this->data ['facilitiess'],
							'status' => $error 
					);
				}
			} else {
				$this->data ['facilitiess'] [] = array (
						'warning' => "Custom list not found" 
				);
				$error = false;
				
				$value = array (
						'results' => $this->data ['facilitiess'],
						'status' => $error 
				);
			}
			
			$value = array (
					'results' => $this->data ['facilitiess'],
					'status' => $error 
			);
			/* echo json_encode($value); */
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in apptask jsongetcustomelist ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_jsongetcustomelist', $activity_data2 );
			// echo 'Caught exception: ', $e->getMessage(), "\n";
		}
	}
	public function jsonaddcoordinates() {
		try {
			
			$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			
			$api_device_info = $this->model_api_encrypt->getdevicedetails ( $cre_array );
			
			if ($api_device_info == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$api_header_value = $this->model_api_encrypt->getallheaders1 ();
			
			if ($api_header_value == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			$json = array ();
			$this->data ['facilitiess'] = array ();
			
			if ($this->request->post ['task_id'] != null && $this->request->post ['task_id'] != "") {
				$this->load->model ( 'createtask/createtask' );
				
				$this->model_createtask_createtask->addcoordinates ( $this->request->post, $this->request->post ['task_id'] );
				
				$this->data ['facilitiess'] = array (
						'warning' => '1' 
				);
				
				$error = true;
			} else {
				$this->data ['facilitiess'] [] = array (
						'warning' => 'Please enter Task id' 
				);
				$error = false;
			}
			
			$value = array (
					'results' => $this->data ['facilitiess'],
					'status' => $error 
			);
			
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in jsonaddcoordinatest ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'jsonaddcoordinates', $activity_data2 );
		}
	}
	public function jsongetcoordinates() {
		try {
			
			$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			
			$api_device_info = $this->model_api_encrypt->getdevicedetails ( $cre_array );
			
			if ($api_device_info == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$api_header_value = $this->model_api_encrypt->getallheaders1 ();
			
			if ($api_header_value == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$this->data ['facilitiess'] = array ();
			$this->load->model ( 'createtask/createtask' );
			
			if ($this->request->post ['task_id'] != null && $this->request->post ['task_id'] != "") {
				$results = $this->model_createtask_createtask->getcoordinates ( $this->request->post ['task_id'] );
				
				if (! empty ( $results )) {
					foreach ( $results as $result ) {
						$this->data ['facilitiess'] [] = array (
								'notes_by_travel_task_coordinates_id' => $result ['notes_by_travel_task_coordinates_id'],
								'task_id' => $result ['task_id'],
								'notes_id' => $result ['notes_id'],
								'latitude' => $result ['latitude'],
								'longitude' => $result ['longitude'] 
						);
					}
					$error = true;
				} else {
					$this->data ['facilitiess'] [] = array (
							'warning' => "Coordinates not found" 
					);
					$error = false;
				}
			} else {
				$this->data ['facilitiess'] [] = array (
						'warning' => 'Please enter Task id' 
				);
				$error = false;
			}
			
			$value = array (
					'results' => $this->data ['facilitiess'],
					'status' => $error 
			);
			/* echo json_encode($value); */
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in apptask jsongetcoordinates ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_jsongetcoordinates', $activity_data2 );
			// echo 'Caught exception: ', $e->getMessage(), "\n";
		}
	}
	public function jsonTraveltask() {
		try {
			
			$this->load->model ( 'activity/activity' );
			$this->model_activity_activity->addActivitySave ( 'jsonTraveltask', $this->request->post, 'request' );
			
			$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			
			$api_device_info = $this->model_api_encrypt->getdevicedetails ( $cre_array );
			
			if ($api_device_info == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$api_header_value = $this->model_api_encrypt->getallheaders1 ();
			
			if ($api_header_value == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			$this->data ['facilitiess'] = array ();
			
			$json = array ();
			
			$this->load->model ( 'notes/notes' );
			$this->language->load ( 'notes/notes' );
			
			if ($this->request->post ['notes_pin'] != null && $this->request->post ['notes_pin'] != "") {
				$this->load->model ( 'user/user' );
				$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
				
				if (($this->request->post ['notes_pin'] != $user_info ['user_pin'])) {
					$json ['warning'] = 'Pin is incorrect, please try again..';
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
			
			if ($this->request->post ['perpetual_checkbox'] == '1') {
				if ($this->request->post ['perpetual_checkbox_notes_pin'] == '') {
					$json ['perpetual_checkbox_notes_pin'] = 'This is required field!';
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
				if ($this->request->post ['perpetual_checkbox_notes_pin'] != null && $this->request->post ['perpetual_checkbox_notes_pin'] != "") {
					$this->load->model ( 'user/user' );
					$user_info = $this->model_user_user->getUser ( $this->request->post ['user_id'] );
						
					if (($this->request->post ['perpetual_checkbox_notes_pin'] != $user_info ['user_pin'])) {
						$json ['warning'] = 'Pin is incorrect, please try again.';
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
						
					$this->load->model ( 'user/user_group' );
					$user_role_info = $this->model_user_user_group->getUserGroup ( $user_info ['user_group_id'] );
						
					$perpetual_task = $user_role_info ['perpetual_task'];
						
					if ($perpetual_task != '1') {
						$json ['warning'] = "You are not authorized to end the task!";
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
				
				$this->load->model ( 'notes/notes' );
				$this->load->model ( 'form/form' );
				
				$this->load->model ( 'notes/notes' );
				
				if ($this->request->post ['facilities_id']) {
					$this->load->model ( 'facilities/facilities' );
					
					$facilities_info = $this->model_facilities_facilities->getfacilities ( $this->request->post ['facilities_id'] );
					
					$this->load->model ( 'setting/timezone' );
					
					$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
					$facilitytimezone = $timezone_info ['timezone_value'];
				}
				
				$timezone_name = $facilitytimezone;
				$timeZone = date_default_timezone_set ( $timezone_name );
				$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
				$date_added = ( string ) $noteDate;
				
				$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
				$data ['imgOutput'] = $this->request->post ['signature'];
				
				$data ['notes_pin'] = $this->request->post ['notes_pin'];
				$data ['user_id'] = $this->request->post ['user_id'];
				$data ['notes_type'] = $this->request->post ['notes_type'];
				
				$this->load->model ( 'setting/tags' );
				$this->load->model ( 'createtask/createtask' );
				
				$transport_tags = "";
				
				$is_pick_up = $this->request->post ['is_pick_up'];
				$is_drop_off = $this->request->post ['is_drop_off'];
				
				$this->load->model ( 'createtask/createtask' );
				
				$result = $this->model_createtask_createtask->getStrikedatadetails ( $this->request->post ['task_id'] );
				
				$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName ( $result ['tasktype'],$result['facilityId'] );
				$relation_keyword_id = $tasktype_info ['relation_keyword_id'];
				
				if ($is_drop_off == '1') {
					
					$tags_ids11 = implode ( ',', $this->request->post ['tags_ids'] );
					
					foreach ( $this->request->post ['tags_ids'] as $tags_ids ) {
						$tag_info = $this->model_setting_tags->getTag ( $tags_ids );
						
						$data ['emp_tag_id'] = $tag_info ['emp_tag_id'];
						$data ['tags_id'] = $tag_info ['tags_id'];
						
						$transport_tags .= $tag_info ['emp_tag_id'] . ':' . $tag_info ['emp_first_name'] . ', ';
					}
					
					if (! empty ( $this->request->post ['tags_ids'] )) {
						$tagsidsaa = $this->request->post ['tags_ids'];
						
						$transport_tags_pi_1 = explode ( ',', $result ['transport_tags'] );
						
						$transport_tags_pi11 = array_diff ( $transport_tags_pi_1, $tagsidsaa );
						
						$transport_tags_pi = implode ( ',', $transport_tags_pi11 );
						
						$sql2p = "UPDATE  " . DB_PREFIX . "createtask SET transport_tags = '" . $this->db->escape ( $transport_tags_pi ) . "' where id= '" . $result ['id'] . "' ";
						$this->db->query ( $sql2p );
						
						if ($relation_keyword_id) {
							
							$this->load->model ( 'setting/keywords' );
							$keyword_info = $this->model_setting_keywords->getkeywordDetail ( $relation_keyword_id );
							
							$data ['keyword_file'] = $keyword_info ['keyword_image'];
						}
					}
				}
				
				if ($this->request->post ['current_locations_address'] != null && $this->request->post ['current_locations_address'] != "") {
					$current_locations_address = $this->request->post ['current_locations_address'];
				} else {
					
					if ($this->request->post ['current_lat'] != null && $this->request->post ['current_lat'] != "") {
						$url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng=' . trim ( $this->request->post ['current_lat'] ) . ',' . trim ( $this->request->post ['current_log'] ) . '&sensor=false';
						$json = @file_get_contents ( $url );
						$ldata = json_decode ( $json );
						$status = $ldata->status;
						if ($status == "OK") {
							$current_locations_address = $ldata->results [0]->formatted_address;
						}
					}
				}
				
				$data ['current_locations_address'] = $current_locations_address;
				$data ['current_lat'] = $this->request->post ['current_lat'];
				$data ['current_log'] = $this->request->post ['current_log'];
				
				$pick_up_tags_id = $this->request->post ['pick_up_tags_id'];
				
				$pick_up_tags_id11 = implode ( ',', $this->request->post ['pick_up_tags_id'] );
				
				foreach ( $this->request->post ['pick_up_tags_id'] as $pick_up_tags_id ) {
					$tag_info = $this->model_setting_tags->getTag ( $pick_up_tags_id );
					
					$data ['emp_tag_id'] = $tag_info ['emp_tag_id'];
					$data ['tags_id'] = $tag_info ['tags_id'];
					
					$picktransport_tags .= $tag_info ['emp_tag_id'] . ':' . $tag_info ['emp_first_name'] . ', ';
				}
				
				if ($result ['parent_id'] > 0) {
					$this->load->model ( 'notes/notes' );
					$notes_info = $this->model_notes_notes->getNote ( $result ['parent_id'] );
					
					$start_date = new DateTime ( $notes_info ['date_added'] );
					$since_start = $start_date->diff ( new DateTime ( $date_added ) );
					
					$caltime = "";
					$caltime1 = "";
					
					if ($since_start->y > 0) {
						$caltime .= $since_start->y . ' years ';
					}
					if ($since_start->m > 0) {
						$caltime .= $since_start->m . ' months ';
					}
					if ($since_start->d > 0) {
						$caltime .= $since_start->d . ' days ';
					}
					if ($since_start->h > 0) {
						$caltime .= $since_start->h . ' hours ';
					}
					if ($since_start->i > 0) {
						$caltime .= $since_start->i . ' minutes ';
					}
					
					$caltime1 = ' | Total Travel Time ' . $caltime;
				}
				
				$description = "";
				
				// $description .= ' Travel Ended completed at | '.$result['dropoff_locations_address'];
				// $description .= ' ended at | '. date('h:i A', strtotime($result['dropoff_locations_time']));
				
				if ($is_drop_off == '1') {
					$description .= " Travel completed at | " . $current_locations_address;
					$description .= " ended at | " . date ( 'h:i A' ) . $caltime1;
					$description .= " for | " . $transport_tags;
				}
				
				if ($is_pick_up == '1') {
					$description .= "\n";
					$description .= " Travel Started from | " . $current_locations_address;
					$description .= " at | " . date ( 'h:i A' );
					$description .= " for the following | " . $picktransport_tags;
				}
				
				if ($data ['customlistvalues_id']) {
					
					$this->load->model ( 'notes/notes' );
					$custom_info = $this->model_notes_notes->getcustomlistvalue ( $data ['customlistvalues_id'] );
					
					$customlistvalues_name = str_replace ( "'", "&#039;", html_entity_decode ( $custom_info ['customlistvalues_name'], ENT_QUOTES ) );
					
					$customlistvalues_id = $data ['customlistvalues_id'];
				}
				
				if ($data ['customlistvalues_ids']) {
					
					$this->load->model ( 'notes/notes' );
					
					foreach ( $data ['customlistvalues_ids'] as $customlistvalues_id ) {
						
						$custom_info = $this->model_notes_notes->getcustomlistvalue ( $customlistvalues_id );
						
						$customlistvalues_name1 = $custom_info ['customlistvalues_name'];
						
						$customlistvalues_name .= " | " . $customlistvalues_name1;
					}
					
					$customlistvalues_id = implode ( ',', $data ['customlistvalues_ids'] );
				}
				
				if ($customlistvalues_name) {
					$description .= " " . $customlistvalues_name;
				}
				
				if ($this->request->post ['comments'] != null && $this->request->post ['comments']) {
					$description .= " | " . $this->db->escape ( $this->request->post ['comments'] );
				}
				
				$data ['notes_description'] = $description;
				
				$data ['date_added'] = $date_added;
				$data ['note_date'] = $date_added;
				$data ['notetime'] = $notetime;
				
				$data ['phone_device_id'] = $this->request->post ['phone_device_id'];
				$data ['device_unique_id'] = $this->request->post ['device_unique_id'];
				
				if ($this->request->post ['is_android'] != null && $this->request->post ['is_android'] != "") {
					$data ['is_android'] = $this->request->post ['is_android'];
				} else {
					$data ['is_android'] = '1';
				}
				
				// var_dump($data);
				
				// die;
				
				$data ['location_tracking_url'] = $this->request->post ['location_tracking_url'];
				$data ['google_map_image_url'] = $this->request->post ['google_map_image_url'];
				
				$data ['location_tracking_route'] = $this->request->post ['location_tracking_route'];
				$data ['location_tracking_time_start'] = $this->request->post ['location_tracking_time_start'];
				$data ['location_tracking_time_end'] = $this->request->post ['location_tracking_time_end'];
				
				
				$data ['is_pause'] = $this->request->post ['is_pause'];
				$data ['pause_time'] = $this->request->post ['pause_time'];
				$data ['pause_date'] = $this->request->post ['pause_date'];
				$data ['acttion_interval_id'] = $this->request->post ['acttion_interval_id'];
				$data ['facilitydrop'] = $this->request->post ['move_facility'];
				$data ['is_move'] = $this->request->post ['is_move'];
				
				$data ['perpetual_checkbox'] = $this->request->post ['perpetual_checkbox'];
				$data ['perpetual_checkbox_notes_pin'] = $this->request->post ['perpetual_checkbox_notes_pin'];
				
				$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $this->request->post ['facilities_id'] );
				$this->load->model ( 'api/facerekognition' );
				$fre_array2 = array ();
				$fre_array2 ['face_notes_file'] = $this->request->post ['face_notes_file'];
				$fre_array2 ['outputFolder'] = $this->request->post ['outputFolder'];
				$fre_array2 ['face_not_verify'] = $this->request->post ['face_not_verify'];
				$fre_array2 ['facilities_id'] = $this->request->post ['facilities_id'];
				$fre_array2 ['notes_file'] = $facerekognition_response ['imagedata'] ['notes_file'];
				$fre_array2 ['outputFolder_1'] = $facerekognition_response ['imagedata'] ['outputFolder'];
				$fre_array2 ['notes_id'] = $notes_id;
				$this->model_api_facerekognition->savefacerekognitionnotes ( $fre_array2 );
				
				$notetasktime = date ( 'H:i:s', strtotime ( $result ['task_time'] ) );
				
				$taskstarttime = date ( 'H:i:s', strtotime ( ' +' . $addTime . ' minutes', strtotime ( $result ['task_time'] ) ) );
				date_default_timezone_set ( $facilitytimezone );
				
				$currenttime = date ( 'H:i:s', strtotime ( 'now' ) );
				
				if ($currenttime > $taskstarttime) {
					$taskDuration = '3';
				} else {
					$taskDuration = '2';
				}
				
				$tdata3 = array ();
				$tdata3 ['notes_id'] = $notes_id;
				$tdata3 ['task_id'] = $tasktype_info ['task_id'];
				$tdata3 ['notetasktime'] = $notetasktime;
				$tdata3 ['taskDuration'] = $taskDuration;
				$tdata3 ['customlistvalues_id'] = $customlistvalues_id;
				$this->model_notes_notes->updatetnotestravel ( $result, $tdata3 );
				
				if ($tasktype_info ['enable_location'] == '1') {
					
					if ($result ['pickup_locations_address'] != null && $result ['pickup_locations_address'] != "") {
						$google_url = "https://www.google.com/maps/dir/" . $result ['pickup_locations_address'] . '/' . $result ['dropoff_locations_address'];
					}
					
					if (($data ['current_lat'] != null && $data ['current_lat'] != "") && ($data ['current_log'] != null && $data ['current_log'] != "")) {
						
						if ($result ['is_transport'] == '0') {
							$latitude = $result ['pickup_locations_latitude'];
							$longitude = $result ['pickup_locations_longitude'];
						} else {
							$latitude = $result ['dropoff_locations_latitude'];
							$longitude = $result ['dropoff_locations_longitude'];
						}
						
						$distanced = $this->distance ( $data ['current_lat'], $data ['current_log'], $latitude, $longitude, "K" );
						
						if (($distanced >= '5')) {
							$keyword_id = $tasktype_info ['relation_keyword_id'];
						}
						
						/*
						 * if($data['current_locations_address'] != null && $data['current_locations_address'] != ""){
						 * $current_locations_address = $data['current_locations_address'];
						 * }else{
						 *
						 * $url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($data['current_lat']).','.trim($data['current_log']).'&sensor=false';
						 * $json = @file_get_contents($url);
						 * $ldata = json_decode($json);
						 * $status = $ldata->status;
						 * if($status=="OK"){
						 * $current_locations_address = $ldata->results[0]->formatted_address;
						 * }
						 * }
						 */
						
						$current_google_url = "https://www.google.com/maps/place/" . $current_locations_address . '/' . $data ['current_lat'] . ',' . $data ['current_log'];
					}
					
					/*
					 * $this->load->model('createtask/createtask');
					 * $waypoints = $this->model_createtask_createtask->gettravelWaypoints($result['id']);
					 *
					 * if($waypoints != "" && $waypoints != null){
					 * $waypoint_google_url1 = "";
					 * foreach($waypoints as $waypoint){
					 * $waypoint_google_url1 .= '/'.$waypoint['locations_address'];
					 * }
					 *
					 * $waypoint_google_url = "https://www.google.com/maps/dir/".$result['pickup_locations_address'].$waypoint_google_url1.'/'.$result['dropoff_locations_address'];
					 *
					 * }
					 */
					
					$tdata4 = array ();
					$tdata4 ['notes_id'] = $notes_id;
					$tdata4 ['task_id'] = $tasktype_info ['task_id'];
					$tdata4 ['facilities_id'] = $this->request->post ['facilities_id'];
					$tdata4 ['google_url'] = $google_url;
					$tdata4 ['current_locations_address'] = $current_locations_address;
					$tdata4 ['current_lat'] = $data ['current_lat'];
					$tdata4 ['current_log'] = $data ['current_log'];
					$tdata4 ['current_google_url'] = $current_google_url;
					$tdata4 ['location_tracking_url'] = $data ['location_tracking_url'];
					$tdata4 ['location_tracking_route'] = $data ['location_tracking_route'];
					$tdata4 ['location_tracking_time_start'] = $data ['location_tracking_time_start'];
					$tdata4 ['location_tracking_time_end'] = $data ['location_tracking_time_end'];
					$tdata4 ['google_map_image_url'] = $data ['google_map_image_url'];
					$tdata4 ['date_added'] = $date_added;
					$tdata4 ['tags_ids11'] = $tags_ids11;
					$tdata4 ['waypoint_google_url'] = $waypoint_google_url;
					$tdata4 ['keyword_id'] = $keyword_id;
					
					if ($this->request->post ['pickup_locations_address'] != null && $this->request->post ['pickup_locations_address'] != "") {
						$pickup_locations_address = $this->request->post ['pickup_locations_address'];
					} else {
						$pickup_locations_address = $result ['pickup_locations_address'];
					}
					
					if ($this->request->post ['pickup_locations_latitude'] != null && $this->request->post ['pickup_locations_latitude'] != "") {
						$pickup_locations_latitude = $this->request->post ['pickup_locations_latitude'];
					} else {
						$pickup_locations_latitude = $result ['pickup_locations_latitude'];
					}
					
					if ($this->request->post ['pickup_locations_longitude'] != null && $this->request->post ['pickup_locations_longitude'] != "") {
						$pickup_locations_longitude = $this->request->post ['pickup_locations_longitude'];
					} else {
						$pickup_locations_longitude = $result ['pickup_locations_longitude'];
					}
					
					$tdata4 ['pickup_locations_address'] = $pickup_locations_address;
					$tdata4 ['pickup_locations_latitude'] = $pickup_locations_latitude;
					$tdata4 ['pickup_locations_longitude'] = $pickup_locations_longitude;
					
					if ($this->request->post ['dropoff_locations_address'] != null && $this->request->post ['dropoff_locations_address'] != "") {
						$dropoff_locations_address = $this->request->post ['dropoff_locations_address'];
					} else {
						$dropoff_locations_address = $result ['dropoff_locations_address'];
					}
					
					if ($this->request->post ['dropoff_locations_latitude'] != null && $this->request->post ['dropoff_locations_latitude'] != "") {
						$dropoff_locations_latitude = $this->request->post ['dropoff_locations_latitude'];
					} else {
						$dropoff_locations_latitude = $result ['dropoff_locations_latitude'];
					}
					
					if ($this->request->post ['dropoff_locations_longitude'] != null && $this->request->post ['dropoff_locations_longitude'] != "") {
						$dropoff_locations_longitude = $this->request->post ['dropoff_locations_longitude'];
					} else {
						$dropoff_locations_longitude = $result ['dropoff_locations_longitude'];
					}
					
					$tdata4 ['dropoff_locations_address'] = $dropoff_locations_address;
					$tdata4 ['dropoff_locations_latitude'] = $dropoff_locations_latitude;
					$tdata4 ['dropoff_locations_longitude'] = $dropoff_locations_longitude;
					
					$tdata4 ['pickup_locations_address_2'] = $this->request->post ['pickup_locations_address_2'];
					$tdata4 ['pickup_locations_latitude_2'] = $this->request->post ['pickup_locations_latitude_2'];
					$tdata4 ['pickup_locations_longitude_2'] = $this->request->post ['pickup_locations_longitude_2'];
					
					$tdata4 ['dropoff_locations_address_2'] = $this->request->post ['dropoff_locations_address_2'];
					$tdata4 ['dropoff_locations_latitude_2'] = $this->request->post ['dropoff_locations_latitude_2'];
					$tdata4 ['dropoff_locations_longitude_2'] = $this->request->post ['dropoff_locations_longitude_2'];
					$tdata4 ['pick_up_tags_id'] = $pick_up_tags_id11;
					
					$tdata4 ['is_pick_up'] = $is_pick_up;
					$tdata4 ['is_drop_off'] = $is_drop_off;
					
					$this->model_notes_notes->updatetnotestravel_location ( $result, $tdata4 );
				}
				
				
				if ($this->request->post ['perpetual_checkbox'] == '1') {
						
					$this->load->model ( 'notes/notes' );
					$timeZone = date_default_timezone_set ( $this->request->post ['facilitytimezone'] );
					$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
					$date_added = ( string ) $noteDate;
						
					$data = array ();
						
					$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
					$data ['imgOutput'] = $this->request->post ['signature'];
						
					$data ['notes_pin'] = $this->request->post ['notes_pin'];
					$data ['user_id'] = $this->request->post ['user_id'];
						
					$data ['notetime'] = $notetime;
					$data ['note_date'] = $date_added;
						
					/*
					 * if($this->request->post['comments'] != null && $this->request->post['comments']){
					 * $comments = ' | '.$this->request->post['comments'];
					 * }
					 */
						
					$this->load->model ( 'createtask/createtask' );
						
					$this->load->model ( 'setting/keywords' );
						
					$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName ( $result ['tasktype'],$result['facilityId'] );
						
					$keywordData12 = $this->model_setting_keywords->getkeywordDetail ( $tasktype_info ['relation_keyword_id'] );
					$keywordData13 = $this->model_setting_keywords->getkeywordDetail ( $keywordData12 ['relation_keyword_id'] );
						
					$data ['keyword_file'] = $keywordData13 ['keyword_image'];
						
					$keywordData2 = $this->model_setting_keywords->getkeywordDetaildesc ( $data ['keyword_file'], $this->request->post ['facilities_id'] );
						
					$notetasktime = date ( 'H:i:s', strtotime ( $result ['task_time'] ) );
						
					$data ['notes_description'] = $keywordData2 ['keyword_name'] . ' | ENDED | ' . date ( 'h:i A', strtotime ( $notetasktime ) ) . ' ' . $comments;
						
					$data ['date_added'] = $date_added;
						
					$data ['phone_device_id'] = $this->request->post ['phone_device_id'];
					$data ['device_unique_id'] = $this->request->post ['device_unique_id'];
						
					$data ['linked_id'] = $result ['linked_id'];
						
					if ($this->request->post ['is_android'] != null && $this->request->post ['is_android'] != "") {
						$data ['is_android'] = $this->request->post ['is_android'];
					} else {
						$data ['is_android'] = '1';
					}
						
					$notesid = $this->model_notes_notes->jsonaddnotes ( $data, $this->request->post ['facilities_id'] );
						
					$this->model_notes_notes->updatenotetask ( $result ['parent_id'], $notesid );
						
					if ($result ['emp_tag_id'] != null && $result ['emp_tag_id'] != "") {
						$this->load->model ( 'notes/notes' );
				
						date_default_timezone_set ( $this->request->post ['facilitytimezone'] );
						$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
				
						$this->load->model ( 'notes/tags' );
						$taginfo = $this->model_notes_tags->getTag ( $result ['emp_tag_id'] );
						$tadata = array();
						$this->model_notes_notes->updateNotesTag ( $taginfo ['emp_tag_id'], $notesid, $taginfo ['tags_id'], $update_date,$tadata );
					}
				}
				
				
				//$this->load->model ( 'notes/notes' );
				//$noteinfo = $this->model_notes_notes->getnotes ( $notes_id );
				
				$this->data ['facilitiess'] [] = array (
						'warning' => '1',
						'notes_id' => $notes_id ,
						//'noteinfo' => $noteinfo ,
				);
				$error = true;
			} else {
				$this->data ['facilitiess'] [] = array (
						'warning' => $json ['warning'] 
				);
				$error = false;
			}
			
			$value = array (
					'results' => $this->data ['facilitiess'],
					'status' => $error 
			);
			
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in jsonTraveltask ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_jsonTraveltask', $activity_data2 );
		}
	}
	public function jsonupdateTraveltaskcoordinates() {
		try {
			
			$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			
			$api_device_info = $this->model_api_encrypt->getdevicedetails ( $cre_array );
			
			if ($api_device_info == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$api_header_value = $this->model_api_encrypt->getallheaders1 ();
			
			if ($api_header_value == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$this->data ['facilitiess'] = array ();
			
			$json = array ();
			
			$this->load->model ( 'notes/notes' );
			$this->language->load ( 'notes/notes' );
			
			if ($this->request->post ['task_id'] == null && $this->request->post ['task_id'] == "") {
				$json ['warning'] = 'Please Enter task id';
			}
			if ($this->request->post ['notes_id'] == null && $this->request->post ['notes_id'] == "") {
				$json ['warning'] = 'Please Enter notes id';
			}
			
			if ($json ['warning'] == null && $json ['warning'] == "") {
				
				$sqlc = "UPDATE `" . DB_PREFIX . "notes_by_travel_task_coordinates` SET notes_id = '" . $this->request->post ['notes_id'] . "' where task_id = '" . $this->request->post ['task_id'] . "' and  notes_id = '0' ";
				$this->db->query ( $sqlc );
				
				$this->data ['facilitiess'] [] = array (
						'warning' => '1',
						'notes_id' => $this->request->post ['task_id'] 
				);
				$error = true;
			} else {
				$this->data ['facilitiess'] [] = array (
						'warning' => $json ['warning'] 
				);
				$error = false;
			}
			
			$value = array (
					'results' => $this->data ['facilitiess'],
					'status' => $error 
			);
			
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in jsonTraveltask ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_jsonTraveltask', $activity_data2 );
		}
	}
	public function distance($lat1, $lon1, $lat2, $lon2, $unit) {
		$theta = $lon1 - $lon2;
		$dist = sin ( deg2rad ( $lat1 ) ) * sin ( deg2rad ( $lat2 ) ) + cos ( deg2rad ( $lat1 ) ) * cos ( deg2rad ( $lat2 ) ) * cos ( deg2rad ( $theta ) );
		$dist = acos ( $dist );
		$dist = rad2deg ( $dist );
		$miles = $dist * 60 * 1.1515;
		$unit = strtoupper ( $unit );
		
		if ($unit == "K") {
			return ($miles * 1.609344);
		} else if ($unit == "N") {
			return ($miles * 0.8684);
		} else {
			return $miles;
		}
	}
	public function jsonapprovalTasklist() {
		try {
			
			$this->load->model ( 'api/encrypt' );
			$cre_array = array ();
			$cre_array ['phone_device_id'] = $this->request->post ['phone_device_id'];
			$cre_array ['facilities_id'] = $this->request->post ['facilities_id'];
			
			$api_device_info = $this->model_api_encrypt->getdevicedetails ( $cre_array );
			
			if ($api_device_info == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$api_header_value = $this->model_api_encrypt->getallheaders1 ();
			
			if ($api_header_value == false) {
				$errorMessage = $this->model_api_encrypt->errorMessage ();
				return $errorMessage;
			}
			
			$this->data ['listtask'] = array ();
			$this->data ['listtask2'] = array ();
			
			if ($this->request->post ['facilities_id'] != null && $this->request->post ['facilities_id'] != "") {
				
				$this->load->model ( 'facilities/facilities' );
				$facilities_info = $this->model_facilities_facilities->getfacilities ( $this->request->post ['facilities_id'] );
				
				if ($facilities_info ['config_task_status'] == '1') {
					
					if (isset ( $this->request->post ['facilitytimezone'] )) {
						$facilities_timezone = $this->request->post ['facilitytimezone'];
						date_default_timezone_set ( $facilities_timezone );
					}
					
					if (isset ( $this->request->post ['currentdate'] )) {
						$date = str_replace ( '-', '/', $this->request->post ['currentdate'] );
						$res = explode ( "/", $date );
						
						$changedDate = $res [1] . "-" . $res [0] . "-" . $res [2];
						
						$currentdate = $changedDate;
					} else {
						$currentdate = date ( 'd-m-Y' );
					}
					
					if (isset ( $this->request->post ['facilities_id'] )) {
						$facilities_id = $this->request->post ['facilities_id'];
					}
					
					$this->data ['deleteTime'] = $deleteTime;
					$this->load->model ( 'createtask/createtask' );
					
					date_default_timezone_set ( $this->request->post ['facilitytimezone'] );
					$currenttime = date ( 'H:i:s', strtotime ( 'now' ) );
					
					$currentdate = date ( 'Y-m-d', strtotime ( 'now' ) );
					
					$this->load->model ( 'setting/locations' );
					$this->load->model ( 'setting/tags' );
					
					$this->load->model ( 'notes/notes' );
					
					$this->language->load ( 'notes/notes' );
					
					$task_ino = $this->model_createtask_createtask->gettaskrow ( $this->request->post ['task_id'] );
					
					if ($task_ino ['is_approval_required_forms_id'] > 0) {
						$this->load->model ( 'form/form' );
						$form_info = $this->model_form_form->getFormDatas ( $task_ino ['is_approval_required_forms_id'] );
						
						$note_info = $this->model_notes_notes->getNote ( $form_info ['notes_id'] );
						
						if ($form_info ['user_id'] != null && $form_info ['user_id'] != "") {
							$user_id = $form_info ['user_id'];
							$signature = $form_info ['signature'];
							$notes_pin = $form_info ['notes_pin'];
							$notes_type = $form_info ['notes_type'];
							
							if ($form_info ['form_date_added'] != null && $form_info ['form_date_added'] != "0000-00-00 00:00:00") {
								$form_date_added = date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $form_info ['form_date_added'] ) );
							} else {
								$form_date_added = '';
							}
						} else {
							$user_id = $note_info ['user_id'];
							$signature = $note_info ['signature'];
							$notes_pin = $note_info ['notes_pin'];
							$notes_type = $note_info ['notes_type'];
							
							if ($note_info ['note_date'] != null && $note_info ['note_date'] != "0000-00-00 00:00:00") {
								$form_date_added = date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $note_info ['note_date'] ) );
							} else {
								$form_date_added = '';
							}
						}
						
						$this->data ['listtask'] [] = array (
								'user_id' => $user_id,
								'signature' => $signature,
								'notes_pin' => $notes_pin,
								'notes_type' => $notes_type,
								'form_date_added' => $form_date_added,
								'notes_description' => $notes_description,
								'incident_number' => $incident_number,
								'user_is_approval_required_forms_id' => '1' 
						)
						;
					} else {
						
						if ($this->request->post ['notes_id'] != NULL && $this->request->post ['notes_id'] != "") {
							
							$note_info1 = $this->model_notes_notes->getNote ( $this->request->post ['notes_id'] );
							// var_dump($note_info);
							
							if ($note_info1 ['is_approval_required_forms_id'] > 0) {
								$this->load->model ( 'form/form' );
								$form_info = $this->model_form_form->getFormDatas ( $note_info1 ['is_approval_required_forms_id'] );
								// var_dump($form_info);
								$note_info = $this->model_notes_notes->getNote ( $form_info ['notes_id'] );
								if ($form_info ['user_id'] != null && $form_info ['user_id'] != "") {
									$user_id = $form_info ['user_id'];
									$signature = $form_info ['signature'];
									$notes_pin = $form_info ['notes_pin'];
									$notes_type = $form_info ['notes_type'];
									
									if ($form_info ['form_date_added'] != null && $form_info ['form_date_added'] != "0000-00-00 00:00:00") {
										$form_date_added = date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $form_info ['form_date_added'] ) );
									} else {
										$form_date_added = '';
									}
								} else {
									$user_id = $note_info ['user_id'];
									$signature = $note_info ['signature'];
									$notes_pin = $note_info ['notes_pin'];
									$notes_type = $note_info ['notes_type'];
									
									if ($note_info ['note_date'] != null && $note_info ['note_date'] != "0000-00-00 00:00:00") {
										$form_date_added = date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $note_info ['note_date'] ) );
									} else {
										$form_date_added = '';
									}
								}
								
								$this->data ['listtask'] [] = array (
										'user_id' => $user_id,
										'signature' => $signature,
										'notes_pin' => $notes_pin,
										'notes_type' => $notes_type,
										'form_date_added' => $form_date_added,
										'notes_description' => $notes_description,
										'incident_number' => $incident_number,
										'user_is_approval_required_forms_id' => '1' 
								)
								;
							} else {
								
								$listtasks = $this->model_notes_notes->getApprovaltasklist ( $this->request->post ['task_id'] );
							}
						} else {
							$listtasks = $this->model_createtask_createtask->getApprovaltasklist ( $this->request->post ['task_id'] );
						}
					}
					if (! empty ( $listtasks )) {
						foreach ( $listtasks as $list ) {
							
							$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName ( $list ['tasktype'],$list['facilityId'] );
							
							if ($tasktype_info ['custom_completion_rule'] == '1') {
								$addTime = $tasktype_info ['config_task_complete'];
							} else {
								$addTime = $this->config->get ( 'config_task_complete' );
							}
							
							$currenttimePlus = date ( 'H:i:s', strtotime ( ' +' . $addTime . ' minutes', strtotime ( 'now' ) ) );
							
							
						$taskstarttime1111 = date ( 'H:i:s', strtotime ( $list ['task_time'] ) );
						
						$taskstarttime11 = date ( 'Y-m-d', strtotime ( $list ['task_date'] ) );
						$taskstarttime = $taskstarttime11 . ' ' . $taskstarttime1111;
						
						
						
						$taskstartime = date ( 'h:i a', strtotime ( ' -' . $addTime . ' minutes',  strtotime ( $list ['task_time'] ) ) );
						
						
						if($is_task_rule != '1'){
							if ($tasktypetype != '5') {
								if ($currenttimePlus >= $taskstarttime) {
									$taskDuration = '1';
								} else {
									$taskDuration = '2';
								}
							} else {
								$taskDuration = '1';
							}
						}else{
							$taskDuration = '1';
						}
						
						
						
						if($taskDuration == '1'){
							 $tasktagcolor = 'green';
						}else{
							 $tasktagcolor = 'yellow';
						}	
						
						
					
						
						/*
						if($taskDuration == '1'){
							 $tasktagcolor = 'green';
						}else{
							 $tasktagcolor = 'yellow';
						}	
						
							$tasktypetype = $tasktype_info ['type'];
							$is_task_rule = $tasktype_info ['is_task_rule'];
							if($is_task_rule != '1'){
								if ($currenttimePlus >= $taskstarttime) {
									$taskDuration = '1';
								} else {
									$taskDuration = '2';
								}
							}else{
								$taskDuration = '1';
							}
							
							*/
							
							if (strlen ( $list ['description'] ) > 50) {
								$description_more = '1';
							} else {
								$description_more = '0';
							}
							
							
								$url2 = "";
							if ($list ['formreturn_id'] > 0) {
								$url2 .= '&forms_id=' . $list ['formreturn_id'];
								
								$this->load->model ( 'form/form' );
								$result_info = $this->model_form_form->getFormDatas ( $list ['formreturn_id'] );
								if ($result_info ['notes_id'] != null && $result_info ['notes_id'] != "") {
									$url2 .= '&notes_id=' . $result_info ['notes_id'];
								}
							}
						
							if ($list ['checklist'] == "incident_form") {
								$form_url = str_replace ( '&amp;', '&', $this->url->link ( 'services/noteform/taskforminsert', '' . 'task_id=' . $list ['id'] . '&facilities_id=' . $list ['facilityId'], 'SSL' ) );
							} else if ($list ['checklist'] == "bed_check") {
								$form_url = str_replace ( '&amp;', '&', $this->url->link ( 'services/noteform/checklistform', '' . 'task_id=' . $list ['id'] . '&facilities_id=' . $list ['facilityId'], 'SSL' ) );
							} elseif (is_numeric ( $list ['checklist'] )) {
								$form_url = str_replace ( '&amp;', '&', $this->url->link ( 'services/form', '' . 'forms_design_id=' . $list ['checklist'] . '&task_id=' . $list ['id'] . '&facilities_id=' . $list ['facilityId'] ) );
							} elseif ($list ['attachement_form'] == '1') {
								$form_url = str_replace ( '&amp;', '&', $this->url->link ( 'services/form', '' . 'forms_design_id=' . $list ['tasktype_form_id'] . '&task_id=' . $list ['id'] . '&facilities_id=' . $list ['facilityId'] ) );
							} else {
								$form_url = '';
							}
							
							$bedcheckdata = array ();
							
							if ($list ['task_form_id'] != 0 && $list ['task_form_id'] != NULL) {
								
								if ($list ['bed_check_location_ids'] != null && $list ['bed_check_location_ids'] != "") {
									$formDatas = $this->model_setting_locations->getformid2 ( $list ['bed_check_location_ids'] );
								} else {
									$formDatas = $this->model_setting_locations->getformid ( $list ['task_form_id'] );
								}
								
								foreach ( $formDatas as $formData ) {
									
									$locData = $this->model_setting_locations->getlocation ( $formData ['locations_id'] );
									
									$locationDatab = array ();
									
									$locationDatab [] = array (
											'locations_id' => $locData ['locations_id'],
											'location_name' => $locData ['location_name'],
											'location_address' => $locData ['location_address'],
											'location_detail' => $locData ['location_detail'],
											'capacity' => $locData ['capacity'],
											'location_type' => $locData ['location_type'],
											'nfc_location_tag' => $locData ['nfc_location_tag'],
											'nfc_location_tag_required' => $locData ['nfc_location_tag_required'],
											'gps_location_tag' => $locData ['gps_location_tag'],
											'gps_location_tag_required' => $locData ['gps_location_tag_required'],
											'latitude' => $locData ['latitude'],
											'longitude' => $locData ['longitude'],
											'other_location_tag' => $locData ['other_location_tag'],
											'other_location_tag_required' => $locData ['other_location_tag_required'],
											'other_type_id' => $locData ['other_type_id'],
											'facilities_id' => $locData ['facilities_id'] 
									)
									;
									
									$bedcheckdata [] = array (
											'task_form_location_id' => $formData ['task_form_location_id'],
											'location_name' => $formData ['location_name'],
											'location_detail' => $formData ['location_detail'],
											'current_occupency' => $formData ['current_occupency'],
											'bedcheck_locations' => $locationDatab 
									);
								}
								
								/*
								 * $this->load->model('setting/bedchecktaskform');
								 * $taskformData = $this->model_setting_bedchecktaskform->getbedchecktaskform($list['task_form_id']);
								 *
								 * foreach($taskformData as $frmData){
								 * $taskformsData[] = array(
								 * 'task_form_name' =>$frmData['task_form_name'],
								 * 'facilities_id' =>$frmData['facilities_id'],
								 * 'form_type' =>$frmData['form_type']
								 * );
								 * }
								 */
							}
							
							/*
							 * if($list['tags_id'] != 0 && $list['tags_id'] != NULL ){
							 * $tags_info = $this->model_setting_tags->getTag($list['tags_id']);
							 * $locationData = array();
							 * $locData = $this->model_setting_locations->getlocation($tags_info['locations_id']);
							 *
							 * $locationData[] = array(
							 * 'locations_id' =>$locData['locations_id'],
							 * 'location_name' =>$locData['location_name'],
							 * 'location_address' =>$locData['location_address'],
							 * 'location_detail' =>$locData['location_detail'],
							 * 'capacity' =>$locData['capacity'],
							 * 'location_type' =>$locData['location_type'],
							 * 'nfc_location_tag' =>$locData['nfc_location_tag'],
							 * 'nfc_location_tag_required' =>$locData['nfc_location_tag_required'],
							 * 'gps_location_tag' =>$locData['gps_location_tag'],
							 * 'gps_location_tag_required' =>$locData['gps_location_tag_required'],
							 * 'latitude' =>$locData['latitude'],
							 * 'longitude' =>$locData['longitude'],
							 * 'other_location_tag' =>$locData['other_location_tag'],
							 * 'other_location_tag_required' =>$locData['other_location_tag_required'],
							 * 'other_type_id' =>$locData['other_type_id'],
							 * 'facilities_id' =>$locData['facilities_id']
							 *
							 * );
							 *
							 *
							 * if($tags_info['upload_file'] != null && $tags_info['upload_file'] != ""){
							 * $upload_file2 = $tags_info['upload_file'];
							 * }else{
							 * $upload_file2 = "";
							 * }
							 *
							 *
							 * $drugaData = array();
							 * $drugDatas = $this->model_setting_tags->getDrugs($list['id']);
							 *
							 * foreach($drugDatas as $drugData){
							 * $drugaData[] = array(
							 * 'createtask_by_group_id' =>$drugData['createtask_by_group_id'],
							 * 'facilities_id' =>$drugData['facilities_id'],
							 * 'locations_id' =>$drugData['locations_id'],
							 * 'tags_id' =>$drugData['tags_id'],
							 * 'medication_id' =>$drugData['medication_id'],
							 * 'drug_name' =>$drugData['drug_name'],
							 * 'dose' =>$drugData['dose'],
							 * 'drug_type' =>$drugData['drug_type'],
							 * 'quantity' =>$drugData['quantity'],
							 * 'frequency' =>$drugData['frequency'],
							 * 'start_time' =>$drugData['start_time'],
							 * 'instructions' =>$drugData['instructions'],
							 * 'count' =>$drugData['count'],
							 * 'complete_status' =>$drugData['complete_status'],
							 * 'upload_file' =>$upload_file2,
							 * );
							 * }
							 *
							 *
							 * $medications[] = array(
							 * 'tags_id' =>$tags_info['tags_id'],
							 * 'upload_file' =>$upload_file2,
							 * 'emp_tag_id' =>$tags_info['emp_tag_id'],
							 * 'emp_first_name' =>$tags_info['emp_first_name'],
							 * 'tags_pin' =>$tags_info['tags_pin'],
							 * 'emp_last_name' =>$tags_info['emp_last_name'],
							 * 'doctor_name' =>$tags_info['doctor_name'],
							 * 'emergency_contact' =>$tags_info['emergency_contact'],
							 * 'dob' =>$tags_info['dob'],
							 * 'medications_locations' =>$locationData,
							 * 'medications_drugs' =>$drugaData
							 * );
							 *
							 * }
							 */
							
							$this->data ['transport_tags'] = array ();
							$this->load->model ( 'setting/tags' );
							
							if (! empty ( $list ['transport_tags'] )) {
								$transport_tags1 = explode ( ',', $list ['transport_tags'] );
							} else {
								$transport_tags1 = array ();
							}
							$transport_tags = array ();
							
							$t = "";
							
							foreach ( $transport_tags1 as $tag1 ) {
								$tags_info = $this->model_setting_tags->getTag ( $tag1 );
								
								if ($tags_info ['emp_first_name']) {
									$emp_tag_id = $tags_info ['emp_tag_id'] . ': ' . $tags_info ['emp_first_name'] . ' ' . $tags_info ['emp_last_name'];
								} else {
									$emp_tag_id = $tags_info ['emp_tag_id'];
								}
								
								if ($tags_info) {
									
									$t .= '<br>Client Name: ' . $emp_tag_id . '';
									
									$transport_tags [] = array (
											'tags_id' => $tags_info ['tags_id'],
											'emp_tag_id' => $emp_tag_id,
											'emp_first_name' => $tags_info ['emp_first_name'],
											'emp_last_name' => $tags_info ['emp_last_name'] 
									);
								}
							}
							
							if ($list ['pickup_locations_address'] != null && $list ['pickup_locations_address'] != "") {
								
								$t .= '<br>Pickup Address: ' . $list ['pickup_locations_address'] . '';
								$t .= '<br>Pickup Time: ' . date ( 'h:i A', strtotime ( $list ['task_time'] ) ) . '';
								$t .= '<br>Dropoff Address: ' . $list ['dropoff_locations_address'] . '<br>';
								$t .= '<br>Dropoff Time: ' . date ( 'h:i A', strtotime ( $list ['dropoff_locations_time'] ) ) . '';
							}
							
							/*
							 * if($list['iswaypoint'] == '1'){
							 * $transport_tags[] = array(
							 * 'tags_id' => 'Yes',
							 * 'emp_tag_id' => 'Round Trip'
							 * );
							 * }
							 */
							
							$medication_tags = array ();
							$this->data ['medication_tags'] = array ();
							$this->load->model ( 'setting/tags' );
							
							if (! empty ( $list ['medication_tags'] )) {
								$medication_tags1 = explode ( ',', $list ['medication_tags'] );
							} else {
								$medication_tags1 = array ();
							}
							$t2 = "";
							foreach ( $medication_tags1 as $medicationtag ) {
								
								$tags_info1 = $this->model_setting_tags->getTag ( $medicationtag );
								
								if ($tags_info1 ['emp_first_name']) {
									$emp_tag_id = $tags_info1 ['emp_tag_id'] . ': ' . $tags_info1 ['emp_first_name'] . ' ' . $tags_info1 ['emp_last_name'];
								} else {
									$emp_tag_id = $tags_info1 ['emp_tag_id'];
								}
								
								if ($tags_info1) {
									
									$t2 .= '<br>Client Name: ' . $emp_tag_id . '';
									
									$drugs = array ();
									
									$mdrugs = $this->model_setting_tags->getTagsMedicationdetailsByID ( $list ['id'], $medicationtag );
									
									foreach ( $mdrugs as $mdrug ) {
										
										$mdrug_info = $this->model_setting_tags->getTagsMedicationdruglByID ( $mdrug ['tags_medication_details_id'] );
										// $medicine_info = $this->model_setting_tags->getTagsMedicationlByID($mdrug_info['tags_medication_id']);
										
										$t2 .= '<br>Drug Name: ' . $mdrug_info ['drug_name'] . '';
										
										$drugs [] = array (
												'tags_medication_details_id' => $mdrug ['tags_medication_details_id'],
												'drug_name' => $mdrug_info ['drug_name'],
												'tags_medication_id' => $mdrug_info ['tags_medication_id'],
												'drug_mg' => $mdrug_info ['drug_mg'],
												'drug_alertnate' => $mdrug_info ['drug_alertnate'],
												'drug_prn' => $mdrug_info ['drug_prn'],
												'instructions' => $mdrug_info ['instructions'],
												'drug_am' => date ( 'h:i A', strtotime ( $mdrug_info ['drug_am'] ) ),
												'drug_pm' => date ( 'h:i A', strtotime ( $mdrug_info ['drug_pm'] ) ) 
										)
										;
									}
									
									$medication_tags [] = array (
											'tags_id' => $tags_info1 ['tags_id'],
											'emp_first_name' => $tags_info1 ['emp_first_name'],
											'emp_last_name' => $tags_info1 ['emp_last_name'],
											'emp_tag_id' => $emp_tag_id,
											'tagsmedications' => $drugs 
									);
								}
							}
							
							if ($list ['visitation_tag_id']) {
								$visitation_tag = $this->model_setting_tags->getTag ( $list ['visitation_tag_id'] );
								
								if ($visitation_tag ['emp_first_name']) {
									$visitation_tag_id = $visitation_tag ['emp_tag_id'] . ': ' . $visitation_tag ['emp_first_name'] . ' ' . $visitation_tag ['emp_last_name'];
								} else {
									$visitation_tag_id = $visitation_tag ['emp_tag_id'];
								}
							}
							
							$fulldescription = html_entity_decode ( str_replace ( '&#039;', '\'', $list ['description'] ) );
							$fulldescription .= $t;
							$fulldescription .= $t2;
							
							$this->data ['listtask'] [] = array (
									'assign_to' => $list ['assign_to'],
									'task_group_by' => $list ['task_group_by'],
									'iswaypoint' => $list ['iswaypoint'],
									'enable_requires_approval' => $list ['enable_requires_approval'],
									'recurrence' => $list ['recurrence'],
									'is_transport' => $list ['is_transport'],
									'attachement_form' => $list ['attachement_form'],
									'tasktype_form_id' => $list ['tasktype_form_id'],
									'tasktype' => $list ['tasktype'],
									'checklist' => $list ['checklist'],
									'task_complettion' => $list ['task_complettion'],
									'device_id' => $list ['device_id'],
									'date' => date ( 'j, M Y', strtotime ( $list ['task_date'] ) ),
									'id' => $list ['id'],
									'description' => html_entity_decode ( str_replace ( '&#039;', '\'', $list ['description'] ) ),
									'description_more' => $description_more,
									'fulldescription' => $fulldescription,
									'taskDuration' => $taskDuration,
									'taskstarttime1111' => $taskstartime,
									'tasktagcolor' =>  $tasktagcolor,
									
									
									'task_time' => date ( 'h:i A', strtotime ( $list ['task_time'] ) ),
									'checklist_url' => $form_url,
									'task_form_id' => $list ['task_form_id'],
									'tags_id' => $list ['tags_id'],
									'pickup_facilities_id' => $list ['pickup_facilities_id'],
									'pickup_locations_address' => $list ['pickup_locations_address'],
									'pickup_locations_time' => $list ['pickup_locations_time'],
									'pickup_locations_latitude' => $list ['pickup_locations_latitude'],
									'pickup_locations_longitude' => $list ['pickup_locations_longitude'],
									'dropoff_facilities_id' => $list ['dropoff_facilities_id'],
									'dropoff_locations_address' => $list ['dropoff_locations_address'],
									'dropoff_locations_time' => $list ['dropoff_locations_time'],
									'dropoff_locations_latitude' => $list ['dropoff_locations_latitude'],
									'dropoff_locations_longitude' => $list ['dropoff_locations_longitude'],
									'transport_tags' => $transport_tags,
									'medications' => $medications,
									'bedchecks' => $bedcheckdata,
									'medication_tags' => $medication_tags 
							)
							;
						}
						
						$error = true;
					} else {
						$this->data ['listtask'] [] = array (
								'warning' => "Aproval Task not found" 
						);
						$error = false;
					}
				} else {
					$this->data ['listtask'] [] = array (
							'warning' => '0' 
					);
					$taskTotal = '0';
					$error = false;
				}
			} else {
				$this->data ['listtask'] [] = array (
						'warning' => '0' 
				);
				$taskTotal = '0';
				$error = false;
			}
			$value = array (
					'results' => $this->data ['listtask'],
					'status' => $error 
			);
			/* echo json_encode($value); */
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in apptask jsonapprovalTasklist ' . $e->getMessage () 
			);
			$this->model_activity_activity->addActivity ( 'app_jsonapprovalTasklist', $activity_data2 );
			// echo 'Caught exception: ', $e->getMessage(), "\n";
		}
	}
	public function checkTask() {
		try {
			$json = array ();
			
			if ($this->request->post ['recurrence'] == "hourly") {
				
				$taskDate = $this->request->post ['task_date'];
				$date = str_replace ( '-', '/', $taskDate );
				$res = explode ( "/", $date );
				$changedDate = $res [2] . "-" . $res [0] . "-" . $res [1];
				
				$taskTime = date ( 'H:i:s', strtotime ( $this->request->post ['taskTime'] ) );
				$endtime = date ( 'H:i:s', strtotime ( $this->request->post ['endtime'] ) );
				
				$time1 = strtotime ( $taskTime );
				$time2 = strtotime ( $endtime );
				$difference = round ( abs ( $time2 - $time1 ) / 3600, 2 );
				// echo $difference;
				// echo "<hr>";
				
				$timezone_name = $this->request->post ['facilitytimezone'];
				date_default_timezone_set ( $timezone_name );
				
				$current_time = date ( "H:i:s" );
				
				if ($current_time > $endtime) {
					$total_hour = 24 - $difference;
					$recData = $total_hour * 60;
					$taskinterval = round ( $recData / $this->request->post ['recurnce_hrly'] ) + 1;
				} else {
					
					$interval = abs ( strtotime ( $endtime ) - strtotime ( $taskTime ) );
					$recData = round ( $interval / 60 );
					$taskinterval = round ( $recData / $this->request->post ['recurnce_hrly'] ) + 1;
				}
				
				$json ['success'] = $taskinterval;
				$error = true;
				
				$this->data ['facilitiess'] [] = array (
						'success' => '1',
						'taskinterval' => $taskinterval,
						'recurnce_hrly' => $this->request->post ['recurnce_hrly'],
						'task_date' => $this->request->post ['task_date'],
						'taskTime' => $this->request->post ['taskTime'],
						'endtime' => $this->request->post ['endtime'],
						'end_recurrence_date' => $this->request->post ['end_recurrence_date'] 
				);
			} else {
				$this->data ['facilitiess'] [] = array (
						'warning' => 'Please select recurrence!' 
				);
				$error = false;
			}
			
			$value = array (
					'results' => $this->data ['facilitiess'],
					'status' => $error 
			);
			/* echo json_encode($value); */
			$this->response->setOutput ( json_encode ( $value ) );
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in app Checktask' 
			);
			$this->model_activity_activity->addActivity ( 'siteschecktask', $activity_data2 );
			
			// echo 'Caught exception: ', $e->getMessage(), "\n";
		}
	}
	
	
}
 
 