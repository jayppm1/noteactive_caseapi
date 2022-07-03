<?php   
class ControllerCommonHeader extends Controller {
	protected function index() {
		try{
		$this->language->load('common/header');
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
		
		
		if ($this->customer->isLogged()) { 
			
			$this->data['privateusername'] = $this->session->data['username']; 
			$this->data['isPrivate'] = $this->session->data['isPrivate']; 
			$this->data['privaterole']  = $this->customer->getUserpRole();
			
			//$this->data['userPrivateGroups']  = $this->customer->getPrivateUsersByRole();
			
			
			
		}
		
		
		$this->data['description'] = $this->document->getDescription();
		$this->data['keywords'] = $this->document->getKeywords();
		$this->data['links'] = $this->document->getLinks();	 
		$this->data['name'] = $this->config->get('config_name');
		
		$this->data['text_logout'] = $this->language->get('text_logout');
		
		
		if (!$this->customer->isLogged()) {
			$this->data['logged'] = '';

			$this->data['home'] = $this->url->link('common/login', '', 'SSL');
			
			
		} else {
		
		$this->data['loggeddata'] = sprintf($this->language->get('text_logged'), $this->customer->getfacility());
		$this->data['username'] = $this->customer->getfacility();
		
		$this->data['logout'] = $this->url->link('common/logout', '' , 'SSL');
		
		$this->data['home'] = $this->url->link('notes/notes');
		$this->data['facility_url'] = $this->url->link('facilities/facilities/update', '', 'SSL');
		
		
		$this->data['resident_url'] = str_replace('&amp;', '&', $this->url->link('resident/resident', '' . $url2, 'SSL'));
		
		/*$url = "";
		
		if ($this->session->data['pagenumber_all'] != null && $this->session->data['pagenumber_all'] != "") {
			$url .= '&page=' . $this->session->data['pagenumber_all'];
		}*/
		
		
		
		$this->data['config_tag_status'] = $this->customer->isTag();
		
		$this->data['config_taskform_status'] = $this->customer->isTaskform();
		$this->data['config_noteform_status'] = $this->customer->isNoteform();
		$this->data['config_rules_status'] = $this->customer->isRule();
		
		$url = "";
			if($this->request->post['advance_search'] != '1'){
				
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
					$url .= '&page=' . $pagenumber_all;
					}
				}
			}
			
			
			if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
				$noteTime =  date('H:i:s');
				
				$date = str_replace('-', '/', $this->request->get['searchdate']);
				$res = explode("/", $date);
				$changedDate = $res[1]."-".$res[0]."-".$res[2];
				
				$this->data['note_date'] = $changedDate.' '.$noteTime;
				$searchdate = $this->request->get['searchdate'];
				$this->data['searchdate'] = $this->request->get['searchdate'];
				
				$currentdate = $changedDate;
				
				if( ($searchdate) >= (date('m-d-Y')) ) {
					$this->data['back_date_check'] = "1";
				}else{
					$this->data['back_date_check'] = "2";
				}
			} else {
				$this->data['note_date'] =  date('Y-m-d H:i:s');
				$this->data['searchdate']  =  date('m-d-Y');
				
				$currentdate = date('d-m-Y');
			}
			
			$this->load->model('createtask/createtask');
		
			$this->data['complteteTaskLists'] = $this->model_createtask_createtask->gettaskLists($currentdate, $this->customer->getId());
			
		if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
			$url .= '&searchdate=' . $this->request->get['searchdate'];
		}
			
		$this->data['unloackUrl'] = $this->url->link('notes/notes/unlockUser', ''. $url, 'SSL');
		$this->data['config_tag_status'] = $this->customer->isTag();
		
		$this->data['config_taskform_status'] = $this->customer->isTaskform();
		$this->data['config_noteform_status'] = $this->customer->isNoteform();
		$this->data['config_rules_status'] = $this->customer->isRule();
		
		
		//$this->data['notes_url'] = $this->url->link('notes/notes', '', 'SSL');
		$this->data['add_notes_url'] = $this->url->link('notes/notes/insert', '' . '&reset=1', 'SSL');
		
		
			
		$this->data['notes_url'] = $this->url->link('notes/notes/insert', '' . '&reset=1' . $url, 'SSL');
		$this->data['notes_url_close'] = $this->url->link('notes/notes/insert', '' . '&reset=1' , 'SSL');
		$this->data['support_url'] = $this->url->link('notes/support', '', 'SSL');
		$this->data['searchUlr'] = $this->url->link('notes/notes/search', '', 'SSL');
		
		$this->data['createtask_url'] = $this->url->link('notes/createtask', '', 'SSL');
		$this->data['updatestriketask_url'] = $this->url->link('notes/createtask/updateStriketask', '', 'SSL');

		$this->data['updatestriketask_url'] = $this->url->link('notes/createtask/updateStriketask', '', 'SSL');
		$this->data['addtasktask_url'] = $this->url->link('notes/notes/index', '', 'SSL');
		$this->data['inserttask_url'] = $this->url->link('notes/createtask/inserttask', '', 'SSL');
		
		
		$this->data['checklist_url'] = str_replace('&amp;', '&', $this->url->link('notes/createtask/checklistform', '' . $url2, 'SSL'));
		$this->data['incident_url'] = str_replace('&amp;', '&', $this->url->link('notes/noteform/taskforminsert', '' . $url2, 'SSL'));
		
		
		$this->data['custom_form_url'] = str_replace('&amp;', '&', $this->url->link('form/form', '' . $url2, 'SSL'));
		
		$this->data['reviewnoted_url'] = str_replace('&amp;', '&', $this->url->link('notes/notes/reviewNotes', '' . $url2, 'SSL'));
		
		
		$url2 = "";
		if ($this->request->get['searchdate'] != null && $this->request->get['searchdate'] != "") {
			$url2 .= '&searchdate=' . $this->request->get['searchdate'];
		}
		
		$this->data['update_strike_url'] = str_replace('&amp;', '&', $this->url->link('notes/notes/updateStrike', '' . $url2, 'SSL'));
		$this->data['update_strike_url_private'] = str_replace('&amp;', '&', $this->url->link('notes/notes/updateStrikeprivate', '' . $url2, 'SSL'));
		$this->data['alarm_url'] = $this->url->link('notes/notes/setAlarm', '', 'SSL');
		
		$this->data['logged'] = $this->customer->isLogged();
		
		$this->load->model('setting/highlighter');
		$this->data['highlighters'] = $this->model_setting_highlighter->gethighlighters($data);
		
		$route = $this->request->get['route'];
		if($route == 'notes/notes'){
			$this->data['urlanchor'] = '1';
		}else{
			$this->data['urlanchor'] = '2';
		}
		
		$timezone_name = $this->customer->isTimezone();
		date_default_timezone_set($timezone_name);
		
		if (isset($this->request->get['searchdate'])) {
			$res = explode("-", $this->request->get['searchdate']);
			$createdate1 = $res[1]."-".$res[0]."-".$res[2];
			
      		$this->data['note_date'] = date('D F j, Y', strtotime($createdate1));
			$currentdate = $createdate1;
    	} else {
      		$this->data['note_date'] =  date('D F j, Y');//date('m-d-Y');
			
			$currentdate = date('d-m-Y');
    	}
		
		}
		
		
		$this->data['listtask'] = array();
		$this->data['listtask2'] = array();
		
		$config_task_status = $this->customer->isTask();
		
		
		$this->data['checkTask'] = $config_task_status;
		
		if($config_task_status == '1'){
			
			$config_task_complete = $this->config->get('config_task_complete');
		
			if($config_task_complete == '5min'){
				$addTime = '5';
			}else
			if($config_task_complete == '10min'){
				$addTime = '10';
			}
			else
			if($config_task_complete == '15min'){
				$addTime = '15';
			}else
			if($config_task_complete == '20min'){
				$addTime = '20';
			}else
			if($config_task_complete == '25min'){
				$addTime = '25';
			}else
			if($config_task_complete == '30min'){
				$addTime = '30';
			}else
			if($config_task_complete == '45min'){
				$addTime = '45';
			}
			
			
			
			$this->data['deleteTime'] = $deleteTime;
			
			$this->load->model('createtask/createtask');
			$top = '1';
			$listtasks = $this->model_createtask_createtask->getTasklist($this->customer->getId(), $currentdate, $top);
			
			$this->data['taskTotal'] = $this->model_createtask_createtask->getCountTasklist($this->customer->getId(), $currentdate, $top, '');
			
			//date_default_timezone_set($this->session->data['time_zone_1']);
			$timezone_name = $this->customer->isTimezone();
			date_default_timezone_set($timezone_name);
			 
			$currenttime = date('H:i:s', strtotime('now'));
			$currenttimePlus = date('H:i:s', strtotime(' +'.$addTime.' minutes',strtotime('now')));
			$currentdate = date('Y-m-d', strtotime('now'));
			/*var_dump($currenttime);
			echo "<hr>";
			var_dump($currenttimePlus);
			echo "<hr>";*/
			
			$this->load->model('setting/locations');
			$this->load->model('setting/tags');
			
			foreach($listtasks as $list){
				
				$taskstarttime = date('H:i:s', strtotime($list['task_time']));
				//var_dump($taskstarttime);
				//echo "<hr>=====";

				//echo $currenttimePlus .' >= '. $taskstarttime ;
				
				
				if($currenttimePlus >= $taskstarttime){
					$taskDuration = '1';
				}else{
					$taskDuration = '2';
				}
				
				$bedcheckdata = array();
				
					if($list['task_form_id'] != 0 && $list['task_form_id'] != NULL ){
						
						
						$formDatas = $this->model_setting_locations->getformid($list['task_form_id']);	
							
						foreach($formDatas as $formData){
							
							
							$locData = $this->model_setting_locations->getlocation($formData['locations_id']);
						
								$locationDatab = array();
								$location_type = "";
							
								$location_typea = $locData['location_type'];
								if($location_typea == '1'){
									$location_type .= "Boys";
								} 
								
								if($location_typea == '2'){
									$location_type .= "Girls";
								}
								
								if($location_typea == '3'){
									$location_type .= "General";
								}
							
							
							if($locData['upload_file'] != null && $locData['upload_file'] != ""){
									$upload_file = $locData['upload_file'];
								}else{
									$upload_file = "";
								}
								$locationDatab[] = array(
									'locations_id' =>$locData['locations_id'],
									'location_name' =>$locData['location_name'],
									'location_address' =>$locData['location_address'],
									'location_detail' =>$locData['location_detail'],
									'capacity' =>$locData['capacity'],
									'location_type' =>$location_type,
									'upload_file' =>$upload_file,
									'nfc_location_tag' =>$locData['nfc_location_tag'],
									'nfc_location_tag_required' =>$locData['nfc_location_tag_required'],
									'gps_location_tag' =>$locData['gps_location_tag'],
									'gps_location_tag_required' =>$locData['gps_location_tag_required'],
									'latitude' =>$locData['latitude'],
									'longitude' =>$locData['longitude'],
									'other_location_tag' =>$locData['other_location_tag'],
									'other_location_tag_required' =>$locData['other_location_tag_required'],
									'other_type_id' =>$locData['other_type_id'],
									'facilities_id' =>$locData['facilities_id']
									
								);
							
							
							  
							$bedcheckdata[] = array(
								'task_form_location_id' =>$formData['task_form_location_id'],
								'location_name' =>$formData['location_name'],
								'location_detail' =>$formData['location_detail'],
								'current_occupency' =>$formData['current_occupency'],
								'bedcheck_locations' =>$locationDatab
								);
						}
					
						/*$this->load->model('setting/bedchecktaskform');
						$taskformData = $this->model_setting_bedchecktaskform->getbedchecktaskform($list['task_form_id']);
					
						foreach($taskformData  as $frmData){
							$taskformsData[] = array(
							'task_form_name' =>$frmData['task_form_name'],
							'facilities_id' =>$frmData['facilities_id'],
							'form_type' =>$frmData['form_type']
							);
						}*/
					 
					}
					 
					 $medications = array();
					
					if($list['tags_id'] != 0 && $list['tags_id'] != NULL ){
						$tags_info = $this->model_setting_tags->getTag($list['tags_id']);
						$locationData = array();
						$locData = $this->model_setting_locations->getlocation($tags_info['locations_id']);
					
							$locationData[] = array(
								'locations_id' =>$locData['locations_id'],
								'location_name' =>$locData['location_name'],
								'location_address' =>$locData['location_address'],
								'location_detail' =>$locData['location_detail'],
								'capacity' =>$locData['capacity'],
								'location_type' =>$locData['location_type'],
								'nfc_location_tag' =>$locData['nfc_location_tag'],
								'nfc_location_tag_required' =>$locData['nfc_location_tag_required'],
								'gps_location_tag' =>$locData['gps_location_tag'],
								'gps_location_tag_required' =>$locData['gps_location_tag_required'],
								'latitude' =>$locData['latitude'],
								'longitude' =>$locData['longitude'],
								'other_location_tag' =>$locData['other_location_tag'],
								'other_location_tag_required' =>$locData['other_location_tag_required'],
								'other_type_id' =>$locData['other_type_id'],
								'facilities_id' =>$locData['facilities_id']
								
							);
							
							
							if($tags_info['upload_file'] != null && $tags_info['upload_file'] != ""){
									$upload_file2 = $tags_info['upload_file'];
								}else{
									$upload_file2 = "";
								}
							
							
							
							$drugDatas = $this->model_setting_tags->getDrugs($list['id']);
						    $drugaData = array();
							foreach($drugDatas as $drugData){
								$drugaData[] = array(
									'createtask_by_group_id' =>$drugData['createtask_by_group_id'],
									'facilities_id' =>$drugData['facilities_id'],
									'locations_id' =>$drugData['locations_id'],
									'tags_id' =>$drugData['tags_id'],
									'medication_id' =>$drugData['medication_id'],
									'drug_name' =>$drugData['drug_name'],
									'dose' =>$drugData['dose'],
									'drug_type' =>$drugData['drug_type'],
									'quantity' =>$drugData['quantity'],
									'frequency' =>$drugData['frequency'],
									'start_time' =>$drugData['start_time'],
									'instructions' =>$drugData['instructions'],
									'count' =>$drugData['count'],
									'complete_status' =>$drugData['complete_status'],
									'upload_file' =>$upload_file2,
								);
							}
						
						
						$medications[] = array(
								'tags_id' =>$tags_info['tags_id'],
								'upload_file' =>$upload_file2,
								'emp_tag_id' =>$tags_info['emp_tag_id'],
								'emp_first_name' =>$tags_info['emp_first_name'],
								'emp_last_name' =>$tags_info['emp_last_name'],
								'doctor_name' =>$tags_info['doctor_name'],
								'emergency_contact' =>$tags_info['emergency_contact'],
								'dob' =>$tags_info['dob'],
								'medications_locations' =>$locationData,
								'medications_drugs' =>$drugaData
								);
					


					
					}
					
					$this->data['transport_tags'] = array();
					$this->load->model('setting/tags');
					
					if (!empty($list['transport_tags'])) {		
						$transport_tags1 = explode(',',$list['transport_tags']);
					} else {
						$transport_tags1 = array();
					}

					foreach ($transport_tags1 as $tag1) {
						$tags_info = $this->model_setting_tags->getTag($tag1);

						if($tags_info['emp_first_name']){
							$emp_tag_id = $tags_info['emp_tag_id'].': '.$tags_info['emp_first_name'].' '. $tags_info['emp_last_name'];
						}else{
							$emp_tag_id = $tags_info['emp_tag_id'];
						}
						
						if ($tags_info) {
							$transport_tags[] = array(
								'tags_id' => $tags_info['tags_id'],
								'emp_tag_id'        => $emp_tag_id
							);
						}
					}
					
					
					$medication_tags = array();
					$this->data['medication_tags'] = array();
					$this->load->model('setting/tags');
					
					if (!empty($list['medication_tags'])) {		
						$medication_tags1 = explode(',',$list['medication_tags']);
					} else {
						$medication_tags1 = array();
					}

					foreach ($medication_tags1 as $medicationtag) {
						$tags_info1 = $this->model_setting_tags->getTag($medicationtag);

						if($tags_info1['emp_first_name']){
							$emp_tag_id = $tags_info1['emp_tag_id'].': '.$tags_info1['emp_first_name'].' '. $tags_info1['emp_last_name'];
						}else{
							$emp_tag_id = $tags_info1['emp_tag_id'];
						}
						
						if ($tags_info1) {
							$medication_tags[] = array(
								'tags_id' => $tags_info1['tags_id'],
								'emp_tag_id'        => $emp_tag_id,
								'tagsmedications' => $this->model_setting_tags->getTagsMedicationdetails($medicationtag), 
							);
						}
					}
					
					
					if($list['visitation_tag_id']){
						$visitation_tag = $this->model_setting_tags->getTag($list['visitation_tag_id']);
						
						if($visitation_tag['emp_first_name']){
							$visitation_tag_id = $visitation_tag['emp_tag_id'].': '.$visitation_tag['emp_first_name'].' '. $visitation_tag['emp_last_name'];
						}else{
							$visitation_tag_id = $visitation_tag['emp_tag_id'];
						}
					}
					
				$this->data['listtask'][] = array(
				'assign_to' =>$list['assign_to'],
				'tasktype' =>$list['tasktype'],
				'send_notification' =>$list['send_notification'],
				'checklist' =>$list['checklist'],
				'date' => date('j, M Y', strtotime($list['task_date'])),
				'id' =>$list['id'],
				'description' =>$list['description'],
				'taskDuration' =>$taskDuration,
				'task_time' =>date('h:i A', strtotime($list['task_time'])),
				'task_form_id' =>  $list['task_form_id'],
				'tags_id' =>$list['tags_id'],
				'pickup_facilities_id' => $list['pickup_facilities_id'],
				'pickup_locations_address' =>$list['pickup_locations_address'],
				'pickup_locations_time' =>date('h:i A', strtotime($list['pickup_locations_time'])),
				'pickup_locations_latitude' =>$list['pickup_locations_latitude'],
				'pickup_locations_longitude' =>$list['pickup_locations_longitude'],
				'dropoff_facilities_id' =>$list['dropoff_facilities_id'],
				'dropoff_locations_address' =>$list['dropoff_locations_address'],
				'dropoff_locations_time' =>date('h:i A', strtotime($list['dropoff_locations_time'])),
				'dropoff_locations_latitude' =>$list['dropoff_locations_latitude'],
				'dropoff_locations_longitude' =>$list['dropoff_locations_longitude'],
				'transport_tags' =>$transport_tags,
				'medications' =>$medications,
				'bedchecks' =>$bedcheckdata,
				'medication_tags' =>$medication_tags,
				
				'visitation_tags' => $list['visitation_tags'],
				'visitation_tag_id' =>$visitation_tag_id,
				'visitation_start_facilities_id' =>$list['visitation_start_facilities_id'],
				'visitation_start_address' =>$list['visitation_start_address'],
				'visitation_start_time' =>date('h:i A', strtotime($list['visitation_start_time'])),
				'visitation_start_address_latitude' =>$list['visitation_start_address_latitude'],
				'visitation_start_address_longitude' =>$list['visitation_start_address_longitude'],
				'visitation_appoitment_facilities_id' =>$list['visitation_appoitment_facilities_id'],
				'visitation_appoitment_address' =>$list['visitation_appoitment_address'],
				'visitation_appoitment_time' =>date('h:i A', strtotime($list['visitation_appoitment_time'])),
				'visitation_appoitment_address_latitude' =>$list['visitation_appoitment_address_latitude'],
				'visitation_appoitment_address_longitude' =>$list['visitation_appoitment_address_longitude'],
				
				);
			}
			
			
		}
		
		//var_dump($this->data['listtask']);

		$this->template = $this->config->get('config_template') . '/template/common/header.php';

		$this->render();
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in Sites Common Header List',
			);
			$this->model_activity_activity->addActivity('sitesheader_list', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		} 
	} 	
}
?>
