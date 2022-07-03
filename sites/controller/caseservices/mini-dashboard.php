<?php
class ControllercaseservicesminiDashboard extends Controller {
	private $error = array ();
	
	
	public function getTaskCounts() {
		
		
		
		try{
			$this->load->model ( 'setting/tags' );
			$status = false;
			$message  = '';
			$datamd = array ();
			$data2 = array();
			$aaa = array();
			
			
			$this->load->model ( 'facilities/facilities' );
			
			if($this->request->post['facilities_id']){
				$this->load->model('facilities/facilities');
					
				$facilities_info = $this->model_facilities_facilities->getfacilities($this->request->post['facilities_id']);
					
				$this->load->model('setting/timezone');
					
				$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
				$facilitytimezone = $timezone_info['timezone_value'];
			}
			
			
			
			$timezone_name = $facilitytimezone;
			$timeZone = date_default_timezone_set($timezone_name);
			$facilities_id = $this->request->post['facilities_id'];
			
			if (isset ( $this->request->post ['searchdate'] )) {
				$res = explode ( "-", $this->request->post ['searchdate'] );
				$createdate1 = $res [1] . "-" . $res [0] . "-" . $res [2];
				
				//$this->data ['note_date'] = date ( 'D F j, Y', strtotime ( $createdate1 ) );
				$currentdate = $createdate1;
				
				//$this->data ['searchdate'] = $this->request->post ['searchdate'];
			} else {
				//$this->data ['note_date'] = date ( 'D F j, Y' ); // date('m-d-Y');
				
				$currentdate = date ( 'd-m-Y' );
				//$this->data ['searchdate'] = date ( 'm-d-Y' );
			}
			
			
			$date = str_replace ( '-', '/', $currentdate );
			$res = explode ( "/", $date );
			$changedDate = $res [2] . "-" . $res [1] . "-" . $res [0];
			$startDate = $changedDate.' 00:00:00'; 
			$endDate = $changedDate.' 23:59:59';
			$datamd['startDate'] = $startDate;
			$datamd['endDate'] = $endDate;
			$datamd['total_data'] = 0;
			$datamd['username'] = $this->request->post['username'];
			$datamd['details_of'] = 'task';
			$this->load->model ( 'resident/minidashboard' );
			
			
			$datamd ['total_data'] = $this->model_resident_minidashboard->getcaseTotal ( $datamd );
			
			
			
			//var_dump($this->request->post);
			
			
			if($datamd ['total_data']>0){
				
				$taskListData = $this->model_resident_minidashboard->case_task_gettotaltask_list_groupby($datamd);
				
				//echo '<pre>'; print_r($taskListData); echo '</pre>'; //die;
				foreach($taskListData AS $taskrow){
					
					$this->load->model ( 'createtask/createtask' );
					$top = '1';
					
					$listtasks = $this->model_createtask_createtask->getTasklist ( $facilities_id, $currentdate, $top, $taskrow['emp_tag_id'] );
					
					//echo '<pre>'; print_r($listtasks); echo '</pre>'; //die;
					
					$currenttime = date ( 'H:i:s', strtotime ( 'now' ) );
					$this->load->model ( 'setting/locations' );
					$this->load->model ( 'setting/tags' );
					
					$this->load->model ( 'api/permision' );
					
					$timeinfo = $this->model_api_permision->getcustomerdatetime ($facilities_id);
					
					foreach ( $listtasks as $list ) {
						
						$taskstarttime1111 = date ( 'H:i:s', strtotime ( $list ['task_time'] ) );
						$taskstarttime11 = date ( 'Y-m-d', strtotime ( $list ['task_date'] ) );
						$taskstarttime = $taskstarttime11 . ' ' . $taskstarttime1111;
						// var_dump($taskstarttime);
						
						$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName ( $list ['tasktype'], $list ['facilityId'] );
						
						$tasktypetype = $tasktype_info ['type'];
						$is_task_rule = $tasktype_info ['is_task_rule'];
						
						// var_dump($is_task_rule);
						
						if ($tasktype_info ['custom_completion_rule'] == '1') {
							$addTime = $tasktype_info ['config_task_complete'];
						} else {
							$addTime = $this->config->get ( 'config_task_complete' );
						}
						// var_dump($addTime);
						// var_dump(date('H:i:s'));
						$currenttimePlus = date ( 'Y-m-d H:i:s', strtotime ( ' +' . $addTime . ' minutes', strtotime ( 'now' ) ) );
						
						// var_dump($currenttimePlus);
						// echo "<hr>=====";
						
						// echo $currenttimePlus .' >= '. $taskstarttime ;
						if ($is_task_rule != '1') {
							if ($tasktypetype != '5') {
								if ($currenttimePlus >= $taskstarttime) {
									$taskDuration = '1';
								} else {
									if ($list ['is_pause'] == '1') {
										$taskDuration = '1';
									} else {
										$taskDuration = '2';
									}
								}
							} else {
								$taskDuration = '1';
							}
						} else {
							$taskDuration = '1';
						}
						
						// var_dump( $taskDuration);
						
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
								$location_type = "";
								
								$location_typea = $locData ['location_type'];
								if ($location_typea == '1') {
									$location_type .= "Boys";
								}
								
								if ($location_typea == '2') {
									$location_type .= "Girls";
								}
								
								if ($location_typea == '3') {
									$location_type .= "Inmates";
								}
								
								if ($locData ['upload_file'] != null && $locData ['upload_file'] != "") {
									$upload_file = $locData ['upload_file'];
								} else {
									$upload_file = "";
								}
								$locationDatab [] = array (
										'locations_id' => $locData ['locations_id'],
										'location_name' => $locData ['location_name'],
										'location_address' => $locData ['location_address'],
										'location_detail' => $locData ['location_detail'],
										'capacity' => $locData ['capacity'],
										'location_type' => $location_type,
										'upload_file' => $upload_file,
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
								);
								
								$bedcheckdata [] = array (
										'task_form_location_id' => $formData ['task_form_location_id'],
										'location_name' => $formData ['location_name'],
										'location_detail' => $formData ['location_detail'],
										'current_occupency' => $formData ['current_occupency'],
										'bedcheck_locations' => $locationDatab 
								);
							}
						}
						
						$medications = array ();
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
										'emp_tag_id' => $emp_tag_id 
								);
							}
						}
						
						
						
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
									
									$drugs [] = array (
											'drug_name' => $mdrug_info ['drug_name'] 
									);
								}
								
								$medication_tags [] = array (
										'tags_id' => $tags_info1 ['tags_id'],
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
						} else {
							$visitation_tag_id = "";
						}
						
						if ($list ['emp_tag_id']) {
							$visitation_tag = $this->model_setting_tags->getTag ( $list ['emp_tag_id'] );
							
							if ($visitation_tag ['emp_first_name']) {
								$emp_tag_id = $visitation_tag ['emp_last_name'] . ', ' . $visitation_tag ['emp_first_name'];
							} else {
								$emp_tag_id = $visitation_tag ['emp_tag_id'];
							}
							
							$rresults = $this->model_setting_locations->getlocation ( $visitation_tag ['room'] );
							$location_name = $rresults ['location_name'];
							$emp_extid = $visitation_tag ['emp_extid'];
							$ssn = $visitation_tag ['ssn'];
						} else {
							$emp_tag_id = "";
							$location_name = "";
							$emp_extid = "";
							$ssn = "";
						}
						
						if ($list ['formreturn_id'] > 0) {
							$this->load->model ( 'form/form' );
							$result_info = $this->model_form_form->getFormDatas ( $list ['formreturn_id'] );
						}
						
						$total_minutes = $addTime;
						$taskstarttime1111 = date ( 'H:i:s', strtotime ( $list ['task_time'] ) );
						$taskstarttime11 = date ( 'Y-m-d', strtotime ( $list ['task_date'] ) );
						$taskstarttime = strtotime($taskstarttime11 . ' ' . $taskstarttime1111);
						$currenttimePlus = strtotime(date ( 'Y-m-d H:i:s', strtotime ( 'now' ) ) );
						$minutes = round(abs($currenttimePlus - $taskstarttime) / 60);
						$percent = round(abs(($minutes/$total_minutes)*100));
						//$hoursx = floor($minutes / 60); // Get the number of whole hours
						//$minutesx = $minutes % 60; // Get the remainder of the hours
						//$time_to_note = sprintf ("%02d:%02d", $hoursx, $minutesx);
						
						if($percent>=71){
							$color_code = '#28a745;';
						}else if( $percent <= 70 && $percent > 30){
							$color_code = '#ffc107;';
						}else if($percent <=30){
							$color_code = '#dc3545;';
						}
					
						$listtask[] = array (
							'tasktype' => $list ['tasktype'],
							'assign_to' => $list ['assign_to'],
							'emp_tag_id' => $emp_tag_id,
							'location_name' => $location_name,
							'emp_extid' => $emp_extid,
							'ssn' => $ssn,
							'color_code' => $color_code,
							'percent' => $percent,
							'colored_minutes' => $minutes,
							'formreturn_id' => $list ['formreturn_id'],
							'notes_id' => $result_info ['notes_id'],
							'enable_requires_approval' => $list ['enable_requires_approval'],
							'attachement_form' => $list ['attachement_form'],
							'tasktype_form_id' => $list ['tasktype_form_id'],
							'send_notification' => $list ['send_notification'],
							'checklist' => $list ['checklist'],
							'date' => date ( 'j, M Y', strtotime ( $list ['task_date'] ) ),
							'end_recurrence_date' => date ( 'j, M Y', strtotime ( $list ['end_recurrence_date'] ) ),
							'id' => $list ['id'],
							'description' => $list ['description'],
							'taskDuration' => $taskDuration,
							'task_time' => date ( $timeinfo ['time_format'], strtotime ( $list ['task_time'] ) ),
							'task_form_id' => $list ['task_form_id'],
							'tags_id' => $list ['tags_id'],
							'pickup_facilities_id' => $list ['pickup_facilities_id'],
							'pickup_locations_address' => $list ['pickup_locations_address'],
							'pickup_locations_time' => date ( 'h:i A', strtotime ( $list ['pickup_locations_time'] ) ),
							'pickup_locations_latitude' => $list ['pickup_locations_latitude'],
							'pickup_locations_longitude' => $list ['pickup_locations_longitude'],
							'dropoff_facilities_id' => $list ['dropoff_facilities_id'],
							'dropoff_locations_address' => $list ['dropoff_locations_address'],
							'dropoff_locations_time' => date ( $timeinfo ['time_format'], strtotime ( $list ['dropoff_locations_time'] ) ),
							'dropoff_locations_latitude' => $list ['dropoff_locations_latitude'],
							'dropoff_locations_longitude' => $list ['dropoff_locations_longitude'],
							'transport_tags' => $transport_tags,
							'medications' => $medications,
							'bedchecks' => $bedcheckdata,
							'medication_tags' => $medication_tags,
							'visitation_tags' => $list ['visitation_tags'],
							'visitation_tag_id' => $visitation_tag_id,
							'visitation_start_facilities_id' => $list ['visitation_start_facilities_id'],
							'visitation_start_address' => $list ['visitation_start_address'],
							'visitation_start_time' => date ( $timeinfo ['time_format'], strtotime ( $list ['visitation_start_time'] ) ),
							'visitation_start_address_latitude' => $list ['visitation_start_address_latitude'],
							'visitation_start_address_longitude' => $list ['visitation_start_address_longitude'],
							'visitation_appoitment_facilities_id' => $list ['visitation_appoitment_facilities_id'],
							'visitation_appoitment_address' => $list ['visitation_appoitment_address'],
							'visitation_appoitment_time' => date ( $timeinfo ['time_format'], strtotime ( $list ['visitation_appoitment_time'] ) ),
							'visitation_appoitment_address_latitude' => $list ['visitation_appoitment_address_latitude'],
							'visitation_appoitment_address_longitude' => $list ['visitation_appoitment_address_longitude'] 
						);
					}
					
					$tagsData = $this->model_setting_tags->getTag($taskrow['emp_tag_id']);
					$inmate_name = $tagsData['emp_last_name'].' '.$tagsData['emp_first_name'];	
					
					$notess [] = array ('tag_id'=>$taskrow['emp_tag_id'],'inmate_name'=>$inmate_name,'data'=>$listtask);
					
					$listtask = array();
					/*$data2['emp_tag_id'] = $taskrow['emp_tag_id'];
					//$taskListData = $this->model_resident_minidashboard->getToDoTaskList($data2);
					foreach($taskListData AS $taskrow2){
						$description [] = $taskrow2['description'];
					}
					
					$tagsData = $this->model_setting_tags->getTag($taskrow['emp_tag_id']);
					$inmate_name = $tagsData['emp_last_name'].' '.$tagsData['emp_first_name'];
					//echo '<pre>'; print_r($tagsData); echo '</pre>'; die;
					$notess [] = array ('tag_id'=>$taskrow['emp_tag_id'],'inmate_name'=>$inmate_name,'description'=>$description);
					$description = array();
					*/
					
					
				}
				
				
				//echo '<pre>xxx'; print_r($this->data ['listtask']); echo '</pre>';
				
				// die;
				$status = true;
				$message  = 'success';
			}else{
				$status = false;
				$message  = 'false';
			}
			$value = array('results'=>$datamd,'result2' =>$notess,'status'=>$status,'message'=>$message);
			$this->response->setOutput ( json_encode ( $value ) );
		}catch(Exception $e){
			$this->load->model('activity/activity');
			$activity_data = array(
				'data' => 'Error in mini-dashboard getCounts '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('getCounts', $activity_data);
		}
	}
	
	
	
	
	
	public function getToDoTaskList() {
		try{
			
			//$unique_id = $this->request->post['customer_key'];
			//$this->load->model ( 'customer/customer' );
			//$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
			
			$status = false;
			$message  = '';
			$datamd = array ();
			$datamd['total_data'] = 0;
			$datamd['activecustomer_id'] = $this->request->post['activecustomer_id'];
			$datamd['username'] = $this->request->post['username'];
			$datamd['details_of'] = 'todotasklist';
			$this->load->model ( 'resident/minidashboard' );
			$datamd ['total_data'] = $this->model_resident_minidashboard->getToDoTaskList ( $datamd );
			$toto_arr =array();
			if(count($datamd ['total_data'])>0){
				foreach($datamd ['total_data'] AS $row){
					$toto_arr[] = $row['description'];
				}
				$status = true;
				$message  = 'success';
			}else{
				$status = false;
				$message  = 'Fail';
			}
			
			$value = array('results'=>$toto_arr,'status'=>$status,'message'=>$message);
			$this->response->setOutput ( json_encode ( $value ) );
		}catch(Exception $e){
			$this->load->model('activity/activity');
			$activity_data = array(
				'data' => 'Error in mini-dashboard getCounts '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('getCounts', $activity_data);
		}
	}
	
	public function getNotesCounts() {
		try{
			$activecustomer_id = $this->request->post['activecustomer_id'];
			$this->load->model ( 'customer/customer' );
			$customer_info = $this->model_customer_customer->getcustomer ( $activecustomer_id );
			$status = false;
			$message  = '';
			$datamd = array ();
			$datamd['total_data'] = 0;
			$datamd['activecustomer_id'] = $customer_info['activecustomer_id'];
			$datamd['username'] = $this->request->post['username'];
			$datamd['customer_key'] = $customer_info['customer_key'];
			$datamd['details_of'] = 'notes';
			$this->load->model ( 'resident/minidashboard' );
			$datamd ['total_data'] = $this->model_resident_minidashboard->getcaseTotal ( $datamd );
			if($datamd ['total_data']>0){
				$status = true;
				$message  = 'success';
			}else{
				$status = false;
				$message  = 'fail';
			}
			$value = array('results'=>$datamd,'status'=>$status,'message'=>$message);
			$this->response->setOutput ( json_encode ( $value ) );
		}catch(Exception $e){
			$this->load->model('activity/activity');
			$activity_data = array(
				'data' => 'Error in mini-dashboard getNotesCounts '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('getNotesCounts', $activity_data);
		}
	}
	
	public function getIncidentCounts() {
		try{
			$activecustomer_id = $this->request->post['activecustomer_id'];
			$this->load->model ( 'customer/customer' );
			$customer_info = $this->model_customer_customer->getcustomer ( $activecustomer_id );
			$status = false;
			$message  = '';
			$datamd = array ();
			$datamd['total_data'] = 0;
			$datamd['activecustomer_id'] = $this->request->post['activecustomer_id'];
			$datamd['username'] = $this->request->post['username'];
			$datamd['customer_key'] = $customer_info['customer_key'];
			$datamd['details_of'] = 'incident';
			$this->load->model ( 'resident/minidashboard' );
			$datamd ['total_data'] = $this->model_resident_minidashboard->getcaseTotal ( $datamd );
			if($datamd ['total_data']>0){
				$status = true;
				$message  = 'success';
			}else{
				$status = false;
				$message  = 'fail';
			}
			
			$value = array('results'=>$datamd,'status'=>$status,'message'=>$message);
			$this->response->setOutput ( json_encode ( $value ) );
		}catch(Exception $e){
			$this->load->model('activity/activity');
			$activity_data = array(
				'data' => 'Error in mini-dashboard getCounts '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('getCounts', $activity_data);
		}
	}
	
	public function getInmetCounts() {
		try{
			$activecustomer_id = $this->request->post['activecustomer_id'];
			$this->load->model ( 'customer/customer' );
			$customer_info = $this->model_customer_customer->getcustomer ( $activecustomer_id );
			
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUserByUsernamebysaml($this->request->post['username']);
				
			//echo '<pre>'; print_r($user_info); echo '</pre>';
			
			$status = false;
			$message  = '';
			$datamd = array ();
			$datamd['total_data'] = 0;
			$datamd['activecustomer_id'] = $this->request->post['activecustomer_id'];
			$datamd['username'] = $this->request->post['username'];
			$datamd['user_id'] = $user_info['user_id'];
			$datamd['customer_key'] = $customer_info['customer_key'];
			$datamd['details_of'] = 'inmate';
			$this->load->model ( 'resident/minidashboard' );
			$datamd ['total_data'] = $this->model_resident_minidashboard->getcaseTotal ( $datamd );
			if($datamd ['total_data']>0){
				$status = true;
				$message  = 'success';
			}else{
				$status = false;
				$message  = 'fail';
			}
			
			$value = array('results'=>$datamd,'status'=>$status,'message'=>$message);
			$this->response->setOutput ( json_encode ( $value ) );
		}catch(Exception $e){
			$this->load->model('activity/activity');
			$activity_data = array(
				'data' => 'Error in mini-dashboard getCounts '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('getCounts', $activity_data);
		}
	}
	
	
	public function getACACounts() {
		try{
			$this->load->model('notes/acarules');
			$this->load->model('notes/notes');
			$this->load->model ( 'setting/keywords' );
			$datamd = array ();
			$aca_data = array();
			$this->load->model('user/user');
			$this->load->model ( 'customer/customer' );
			$activecustomer_id = $this->request->post['activecustomer_id'];
			$customer_info = $this->model_customer_customer->getcustomer ( $activecustomer_id );
			$customer_key = $customer_info['customer_key'];
			$user_id = $this->request->post['username'];
			$datamd['activecustomer_id'] = $activecustomer_id;
			$acas = $this->model_notes_acarules->getAcarules($datamd);
			$i=0;
			foreach ( $acas as $aca ) {
				//echo '<pre>'; print_r($aca['keyword_id']); echo '</pre>';
				$data = array(
					//'group' => '1',
					//'searchdate_app' => '1',
					'keyword_id' => $aca['keyword_id'],
					'user_id' => $user_id,
					'activecustomer_id' => $customer_key
					//'emp_tag_id' => $search_emp_tag_id,
					//'advance_searchapp' => $advance_search,
					//'tasktype' => $tasktype,
					//'start' => ($page - 1) * $config_admin_limit,
					//'limit' => $config_admin_limit
				);
        
				$results = $this->model_notes_notes->getAllNotebyactivenotes($data);
				$total_notes[] = $results['total'];
				
				$keywordinfo = $this->model_setting_keywords->getkeywordDetail($aca['keyword_id']);
				if($keywordinfo['keyword_image'] != ''){
					$keyword_file = $keywordinfo['keyword_image'];
				}else{
					$keyword_file ='';
				}
				
				$aca_data2 [] = array(
					'rules_name' => $aca['rules_name'],
					'keyword_file' => $keyword_file
				);
				$i++;
			}
			
			arsort($total_notes);
			foreach($total_notes AS $each_notes){
				$total_notes2[] = $each_notes;
			}
			//print_r($total_notes2);
			if(count($aca_data2)>0){
				$i=0;
				foreach($aca_data2 AS $ddd){
					$aca_data [] = array(
						'rules_name' => $ddd['rules_name'],
						'keyword_file' => $ddd['keyword_file'],
						'total_notes'=> $total_notes2[$i]
					);	
					$i++;
				}
			}
			
		
			if(count($aca_data)>0){
				$status = true;
				$message  = 'ACA Standard Listed';
			}else{
				$status = false;
				$message  = 'Data not found';
			}
			
			$newArray = array_slice($aca_data, 0, 6, true);
			$value = array('results'=>$newArray,'status'=>$status,'message'=>$message);
			$this->response->setOutput ( json_encode ( $value ) );
			
		}catch(Exception $e){
			$this->load->model('activity/activity');
			$activity_data = array(
				'data' => 'Error in mini-dashboard getACACounts '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('getACACounts', $activity_data);
		}
	}
	
	public function getACACounts_old2() {
		try{
			$this->load->model('notes/acarules');
			$this->load->model ( 'setting/keywords' );
			$datamd = array ();
			$aca_data = array();
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUserByUsernamebysaml($this->request->post['username']);
			$user_id = $user_info['user_id'];
			$datamd['activecustomer_id'] = $this->request->post['activecustomer_id'];
			$acas = $this->model_notes_acarules->getAcarules($datamd);
		
			foreach ( $acas as $aca ) {
			
				$rac =  unserialize($aca['rule_action_content']);
				if(in_array($user_id,$rac['auserids'])){
					//echo '<pre>'; print_r($rac['auserids']); echo '</pre>';
					$keywordinfo = $this->model_setting_keywords->getkeywordDetail($aca['keyword_id']);
					if($keywordinfo['keyword_image'] != ''){
						$keyword_file = $keywordinfo['keyword_image'];
					}else{
						$keyword_file ='';
					}
					$aca_data [] = array(
						'rules_name' => $aca['rules_name'],
						'keyword_file' => $keyword_file,
						'total_notes'=> 5,
					);
				}
			}
		
			if(count($aca_data)>0){
				$status = true;
				$message  = 'ACA Standard Listed';
			}else{
				$status = false;
				$message  = 'Data not found';
			}
			
			$newArray = array_slice($aca_data, 0, 5, true);
			$value = array('results'=>$newArray,'status'=>$status,'message'=>$message);
			$this->response->setOutput ( json_encode ( $value ) );
			
		}catch(Exception $e){
			$this->load->model('activity/activity');
			$activity_data = array(
				'data' => 'Error in mini-dashboard getACACounts '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('getACACounts', $activity_data);
		}
	}
	
	
	
	public function getACACounts_old() {
		try{
			$this->load->model('notes/acarules');
			$this->load->model ( 'setting/keywords' );
			$acas = $this->model_notes_acarules->getAcarules();
			
			foreach ( $acas as $aca ) {
				
				//echo '<pre>'; print_r($aca); echo '</pre>';
				
				$ddd =  unserialize($aca['rule_action_content']);
				
				//echo '<pre>'; print_r($ddd); echo '</pre>';
				
				$keywordinfo = $this->model_setting_keywords->getkeywordDetail($aca['keyword_id']);
				if($keywordinfo['keyword_image'] != ''){
					$keyword_file = $keywordinfo['keyword_image'];
				}else{
					$keyword_file ='';
				}
				
				$aca_data [] = array(
					'rules_name' => $aca['rules_name'],
					'keyword_file' => $keyword_file,
					'total_notes'=> 5,
				);		
			}
		
		
			if(count($aca_data)>0){
				$status = true;
				$message  = 'ACA Standard Listed';
			}else{
				$status = false;
				$message  = 'Data not found';
			}
			
			$value = array('results'=>$aca_data,'status'=>$status,'message'=>$message);
			$this->response->setOutput ( json_encode ( $value ) );
		}catch(Exception $e){
			$this->load->model('activity/activity');
			$activity_data = array(
				'data' => 'Error in mini-dashboard getACACounts '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('getACACounts', $activity_data);
		}
	}
	
	/*
	public function getCounts() {
		try{
			$status = false;
			$message  = '';
			$total_for = array('notes','outofthecell','incident','activenotes','task','attachment','medicine','case');
			foreach($total_for AS $row){
				$datamd = array ();
				$datamd ['total_data'] = 0;
				$datamd ['facilities_id'] = $this->request->post['facilities_id'];
				$datamd['details_of'] = $row;
				$datamd['count_required'] = 1;
				$datamd['total_for'] = $this->request->post['tags_id'];
				$datamd['from_date'] = isset($this->request->post['from_date']) ? date('Y-m-d', strtotime($this->request->post['from_date'])) : date ( 'Y-m-d' );
				$datamd['to_date'] = isset($this->request->post['to_date']) ? date('Y-m-d', strtotime($this->request->post['to_date'])) : date ( 'Y-m-d' );
				$this->load->model ( 'resident/minidashboard' );
				if($datamd['count_required']) { 
					$datamd ['total_data'] = $this->model_resident_minidashboard->getcaseTotal ( $datamd );
				}
				
				//echo json_encode ( $datamd );
				$total_data[] = $datamd;
				$status = true;
				$message  = 'success';
			}	
			$value = array('results'=>$total_data,'status'=>$status,'message'=>$message);
			$this->response->setOutput ( json_encode ( $value ) );
		}catch(Exception $e){
			$this->load->model('activity/activity');
			$activity_data = array(
				'data' => 'Error in mini-dashboard getCounts '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('getCounts', $activity_data);
		}
	}
	
	public function getOutofthecellCounts() {
		try{
			$status = false;
			$message  = '';
			$datamd = array ();
			$datamd['total_data'] = 0;
			$datamd['facilities_id'] = $this->request->post['facilities_id'];
			$datamd['details_of'] = 'outofthecell';
			$this->load->model ( 'resident/minidashboard' );
			$datamd ['total_data'] = $this->model_resident_minidashboard->getcaseTotal ( $datamd );
			$status = true;
			$message  = 'success';
			$value = array('results'=>$datamd,'status'=>$status,'message'=>$message);
			$this->response->setOutput ( json_encode ( $value ) );
		}catch(Exception $e){
			$this->load->model('activity/activity');
			$activity_data = array(
				'data' => 'Error in mini-dashboard getCounts '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('getCounts', $activity_data);
		}
	}
	
	public function getActivenotesCounts() {
		try{
			$status = false;
			$message  = '';
			$datamd = array ();
			$datamd['total_data'] = 0;
			$datamd['facilities_id'] = $this->request->post['facilities_id'];
			$datamd['details_of'] = 'activenotes';
			$this->load->model ( 'resident/minidashboard' );
			$datamd ['total_data'] = $this->model_resident_minidashboard->getcaseTotal ( $datamd );
			$status = true;
			$message  = 'success';
			$value = array('results'=>$datamd,'status'=>$status,'message'=>$message);
			$this->response->setOutput ( json_encode ( $value ) );
		}catch(Exception $e){
			$this->load->model('activity/activity');
			$activity_data = array(
				'data' => 'Error in mini-dashboard getCounts '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('getCounts', $activity_data);
		}
	}
	
	public function getAttachmentCounts() {
		try{
			$status = false;
			$message  = '';
			$datamd = array ();
			$datamd['total_data'] = 0;
			$datamd['facilities_id'] = $this->request->post['facilities_id'];
			$datamd['details_of'] = 'attachment';
			$this->load->model ( 'resident/minidashboard' );
			$datamd ['total_data'] = $this->model_resident_minidashboard->getcaseTotal ( $datamd );
			$status = true;
			$message  = 'success';
			$value = array('results'=>$datamd,'status'=>$status,'message'=>$message);
			$this->response->setOutput ( json_encode ( $value ) );
		}catch(Exception $e){
			$this->load->model('activity/activity');
			$activity_data = array(
				'data' => 'Error in mini-dashboard getCounts '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('getCounts', $activity_data);
		}
	}
	
	public function getMedicineCounts() {
		try{
			$status = false;
			$message  = '';
			$datamd = array ();
			$datamd['total_data'] = 0;
			$datamd['facilities_id'] = $this->request->post['facilities_id'];
			$datamd['details_of'] = 'medicine';
			$this->load->model ( 'resident/minidashboard' );
			$datamd ['total_data'] = $this->model_resident_minidashboard->getcaseTotal ( $datamd );
			$status = true;
			$message  = 'success';
			$value = array('results'=>$datamd,'status'=>$status,'message'=>$message);
			$this->response->setOutput ( json_encode ( $value ) );
		}catch(Exception $e){
			$this->load->model('activity/activity');
			$activity_data = array(
				'data' => 'Error in mini-dashboard getCounts '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('getCounts', $activity_data);
		}
	}
	
	public function getCaseCounts() {
		try{
			$status = false;
			$message  = '';
			$datamd = array ();
			$datamd['total_data'] = 0;
			$datamd['facilities_id'] = $this->request->post['facilities_id'];
			$datamd['details_of'] = 'case';
			$this->load->model ( 'resident/minidashboard' );
			$datamd ['total_data'] = $this->model_resident_minidashboard->getcaseTotal ( $datamd );
			$status = true;
			$message  = 'success';
			$value = array('results'=>$datamd,'status'=>$status,'message'=>$message);
			$this->response->setOutput ( json_encode ( $value ) );
		}catch(Exception $e){
			$this->load->model('activity/activity');
			$activity_data = array(
				'data' => 'Error in mini-dashboard getCounts '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('getCounts', $activity_data);
		}
	}
	
	public function getOutOfTheCell() {
		try{
			$datamd = array ();
			$datamd ['total_data'] = 0;
			$datamd ['facilities_id'] = $this->request->post['facilities_id'];
			$datamd ['required_details'] = 1;
			$datamd['data_for'] = $this->request->post['tags_id'];
			$datamd['from_date'] = isset($this->request->post['from_date']) ? date('Y-m-d', strtotime($this->request->post['from_date'])) : date ( 'Y-m-d' );
			$datamd['to_date'] = isset($this->request->post['to_date']) ? date('Y-m-d', strtotime($this->request->post['to_date'])) : date ( 'Y-m-d' );
			$this->load->model ( 'resident/minidashboard' );
			$datamd = $this->model_resident_minidashboard->getOTCDetails ( $datamd );
			$value = array('results'=>$datamd,'status'=>true,'message'=>'success');
			$this->response->setOutput ( json_encode ( $datamd ) );
		}catch(Exception $e){
			$this->load->model('activity/activity');
			$activity_data = array(
				'data' => 'Error in mini-dashboard getOutOfTheCell '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('getOutOfTheCell', $activity_data);
		}	
			
			
			
	}

	public function jsongetMovementData() { 
		try{
			$datamd = array ();
			$datamd ['total_data'] = 0;
			$datamd ['facilities_id'] = $this->request->post['facilities_id'];
			$datamd['data_for'] = $this->request->post['tags_id'];
			$datamd['required_details'] = 1;
			//$this->request->post['from'] = '2021-03-02'; //Dummy
			//$this->request->post['to'] = '2021-04-14'; //Dummy
			$datamd['from_date'] = isset($this->request->post['from_date']) ? date('Y-m-d', strtotime($this->request->post['from_date'])) : date ( 'Y-m-d' );
			$datamd['to_date'] = isset($this->request->post['to_date']) ? date('Y-m-d', strtotime($this->request->post['to_date'])) : date ( 'Y-m-d' );
			$this->load->model ( 'resident/minidashboard' );
			$datamd = $this->model_resident_minidashboard->getMovementDetails ( $datamd );
			$this->response->setOutput ( json_encode ( $datamd ) );
		}catch(Exception $e){
			$this->load->model('activity/activity');
			$activity_data = array(
				'data' => 'Error in mini-dashboard getMovementData '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('getMovementData', $activity_data);
		}	
	}
	*/
		
}

/*caseservices/mini-dashboard/getTaskCounts
caseservices/mini-dashboard/getToDoTaskList
caseservices/mini-dashboard/getNotesCounts
caseservices/mini-dashboard/getIncidentCounts
caseservices/mini-dashboard/getACACounts
caseservices/mini-dashboard/getInmetCounts*/