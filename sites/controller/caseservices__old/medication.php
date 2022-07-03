<?php 
 header('Access-Control-Allow-Origin:'.$_SERVER['HTTP_ORIGIN']);
 header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
 header('Access-Control-Max-Age: 1000');
 header('Access-Control-Allow-Headers: Content-Type');
 header('Content-type: application/json');
 header('Content-Type: text/html; charset=utf-8');
 header("Content-type: bitmap; charset=utf-8");
class Controllercaseservicesmedication extends Controller { 
	
	
	public function index(){
		try{
			
		$this->load->model ( 'activity/activity' );
		$this->model_activity_activity->addActivitySave ( 'medicationindex', $this->request->post, 'request' );
			
		$this->data['facilitiess'] = array();
		$this->load->model('facilities/facilities');
		$this->load->model('notes/notes');
		$this->load->model('setting/tags');
		$this->load->model('api/encrypt');
		$cre_array = array();
		$cre_array['phone_device_id'] = $this->request->post['phone_device_id'];
		$cre_array['facilities_id'] = $this->request->post['facilities_id'];
		/*$api_device_info = $this->model_api_encrypt->getdevicedetails($cre_array);
		
		if($api_device_info == false){
			$errorMessage = $this->model_api_encrypt->errorMessage();
			return $errorMessage;
		}
		
		$api_header_value = $this->model_api_encrypt->getallheaders1();
		
		if($api_header_value == false){
			$errorMessage = $this->model_api_encrypt->errorMessage();
			return $errorMessage;
		}*/
	
		if ($this->request->post ['facilities_id'] == null && $this->request->post ['facilities_id'] == "") {
				$json ['warning'] = 'Warning: Enter Facilities Id';
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
		
		if ($this->request->post ['tags_id'] == null && $this->request->post ['tags_id'] == "") {
				$json ['warning'] = 'Warning: Enter tags Id';
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
				$this->load->model ( 'resident/resident' );
				$muduled = $this->model_resident_resident->gettagModule ( $this->request->post ['tags_id'], "0", $this->request->post ['notes_id'] );
				
				 $medicine_info = $this->model_resident_resident->gettagmedicine($this->request->post['tags_id'], $this->request->post['is_archive'], $this->request->post['notes_id']);
            
				$medication_fields = unserialize($medicine_info['medication_fields']);
				$allergies = "";
				if($medication_fields['allergies'] != null && $medication_fields['allergies'] != ""){
					$allergies = $medication_fields['allergies'];
				}
				
				$this->load->model ( 'setting/locations' );
				$data2 = array (
						'location_name' => $this->request->post ['filter_name'],
						'facilities_id' =>$this->request->post ['facilities_id'],
						'status' => '1',
						'type' => 'medication',
						'sort' => 'task_form_name',
						'order' => 'ASC' 
				);
				
				$rresult6s = $this->model_setting_locations->getlocations ( $data2 );
				
				foreach ( $rresult6s as $result1 ) {
					
					$this->data ['medications'] [] = array (
							'locations_id' => $result1 ['locations_id'],
							'location_name' => $result1 ['location_name'] 
					);
				}
				
				$data = array (
					'location_name' => $this->request->post['filter_name'],
					'facilities_id' =>$this->request->post ['facilities_id'],
					'status' => '1',
				);
		
				$this->load->model ( 'medicationtype/medicationtype' );
				$results = $this->model_medicationtype_medicationtype->getmedicationtypes ( $data );
				
				foreach ( $results as $result ) {
					
					$this->data ['medication_types'] [] = array (
							'medicationtype_id' => $result ['medicationtype_id'],
							'type_name' => $result ['type_name'],
							'type' => $result ['type'],
							'measurement_type' => $result ['measurement_type'],
							'status' => $result ['status'] 
					);
				}
				
				
				if (! empty ( $muduled )) {
					
					foreach ( $muduled['new_module'] as $result ) {
						
						$tags_medication_details_ids1 = array();
						foreach($result ['tags_medication_details_ids'] as $id){
							$tags_medication_details_ids1[] = array(
								'id' => $id,
							);
						}
						
						$daily_times1 = array();
						foreach($result ['daily_times'] as $time){
							$daily_times1[] = array(
								'time' => $time,
							);
						}
					
						$this->data ['facilitiess'] [] = array (
								'tags_medication_details_id' => $result ['tags_medication_details_id'],
								'tags_medication_id' => $result ['tags_medication_id'],
								'drug_name' => $result ['drug_name'],
								'drug_mg' => $result ['drug_mg'],
								'drug_am' => $result ['drug_am'],
								'drug_pm' => $result ['drug_pm'],
								'drug_alertnate' => $result ['drug_alertnate'],
								'drug_prn' => $result ['drug_prn'],
								'instructions' => $result ['instructions'],
								//'status' => $result ['status'],
								'image' => $result ['image'],
								'recurrence' => $result ['recurrence'],
								'recurnce_hrly_recurnce' => $result ['recurnce_hrly_recurnce'],
								'date_from' => $result ['date_from'],
								'date_to' => $result ['date_to'],
								'is_schedule_medication' => $result ['is_schedule_medication'],
								'route' => $result ['route'],
								'doctors' => $result ['doctors'],
								'reasons' => $result ['reasons'],
								'type_name' => $result ['type_name'],
								'type' => $result['type'],
								'tags_medication_details_ids' => $tags_medication_details_ids1,
								'daily_times' => $daily_times1,
						);
						
					}
					
					$error = true;
					
				} else {
					$this->data ['facilitiess'] [] = array (
							'warning' => "medication not found" 
					);
					$error = false;
					
					$value = array (
							'results' => $this->data ['facilitiess'],
							'allergies' => $allergies,
							'status' => $error 
					);
					
					$this->response->setOutput ( json_encode ( $value ) );
				}
				
			} else {
				$this->data ['facilitiess'] [] = array (
						'warning' => $json ['warning'] 
				);
				$error = false;
			}
			
			$value = array (
					'results' => $this->data ['facilitiess'],
					'medications' => $this->data ['medications'],
					'medication_types' => $this->data ['medication_types'],
					'allergies' => $medication_fields['allergies'],
					'status' => $error 
			);
			$this->response->setOutput ( json_encode ( $value ) );
			
			
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in medication index '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('index', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		} 	
	
	}


	public function addmedication(){
		try{
			
		$this->load->model ( 'activity/activity' );
		$this->model_activity_activity->addActivitySave ( 'addmedication', $this->request->post, 'request' );
			
		$this->data['facilitiess'] = array();
		$this->load->model('facilities/facilities');
		$this->load->model('notes/notes');
		$this->load->model('setting/tags');
		$this->load->model('api/encrypt');
		$cre_array = array();
		$cre_array['phone_device_id'] = $this->request->post['phone_device_id'];
		$cre_array['facilities_id'] = $this->request->post['facilities_id'];
		  /*$api_device_info = $this->model_api_encrypt->getdevicedetails($cre_array);
			
			if($api_device_info == false){
				$errorMessage = $this->model_api_encrypt->errorMessage();
				return $errorMessage;
			}
			
			$api_header_value = $this->model_api_encrypt->getallheaders1();
			
			if($api_header_value == false){
				$errorMessage = $this->model_api_encrypt->errorMessage();
				return $errorMessage;
			}*/
			if ($this->request->post ['tags_id'] == null && $this->request->post ['tags_id'] == "") {
					$json ['warning'] = 'Warning: Enter tags Id';
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
			
			if ($this->request->post ['facilities_id'] == null && $this->request->post ['facilities_id'] == "") {
					$json ['warning'] = 'Warning: Enter Facilities Id';
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
			
			
			if($this->request->post['notes_pin'] != null && $this->request->post['notes_pin'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);
			
			

			if (($this->request->post['notes_pin'] != $user_info['user_pin'])) {
				$json['warning'] = 'User Pin not valid!.';
				$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
			}
		}
		
		if($this->request->post['user_id'] != null && $this->request->post['user_id'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if (($user_info['status'] == '0')) {
				$json['warning'] = 'User not exit!';
				$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
			}
			
			$this->load->model('facilities/facilities');
				$facility = $this->model_facilities_facilities->getfacilities($this->request->post['facilities_id']);
				$unique_id = $facility['customer_key'];
				
				
				$this->load->model('customer/customer');
				$customer_info = $this->model_customer_customer->getcustomerid($unique_id);
				
				if($user_info['customer_key'] != $customer_info['activecustomer_id']){
					$json['warning'] = $this->language->get('error_customer');
					$facilitiessee = array();
							$facilitiessee[] = array(
								'warning'  => $json['warning'],
							);
							$error = false;
							
							$value = array('results'=>$facilitiessee,'status'=>false);

						return $this->response->setOutput(json_encode($value));
				}
		}
		
		
		
		if($this->request->post['current_enroll_image1'] == "1"){
			$this->load->model('api/facerekognition');
			$fre_array = array();
			$fre_array['current_enroll_image1'] = $this->request->post['current_enroll_image1'];
			$fre_array['facilities_id'] = $this->request->post['facilities_id'];
			$fre_array['user_id'] = $this->request->post['user_id'];
			$facerekognition_response = $this->model_api_facerekognition->checkfacerekognition($fre_array, $this->request->post);
			
			$json['warning'] = $facerekognition_response['warning1'];
			
			$facilitiessee = array();
				$facilitiessee[] = array(
					'warning'  => $json['warning'],
				);
				$error = false;
				
				$value = array('results'=>$facilitiessee,'status'=>false);

			return $this->response->setOutput(json_encode($value));
			}
			
			if ($json ['warning'] == null && $json ['warning'] == "") {
				$this->load->model ( 'notes/notes' );
				
				$this->load->model ( 'resident/resident' );
				
				
				
				
				if($this->request->post['givemedication'] == "1"){
					$tdata = array ();
					$tdata ['tags_id'] = $this->request->post ['tags_id'];
					$tdata ['medication_tags'] = $this->request->post ['medication_tags'];
					
					$taginfo = $this->model_setting_tags->getTag ( $this->request->post ['tags_id'] );
					//$result = $this->model_facilities_facilities->getfacilities ( $taginfo ['facilities_id'] );
					
					
					if($this->request->post['facilities_id']){
						$this->load->model('facilities/facilities');
						$facilities_info = $this->model_facilities_facilities->getfacilities($this->request->post['facilities_id']);
						
						$this->load->model('setting/timezone');
							
						$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
						$facilitytimezone = $timezone_info['timezone_value'];
					}
					$timeZone = date_default_timezone_set ( $facilitytimezone );
					$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
					$date_added = ( string ) $noteDate;
					
					$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
						
					$tdata ['facilities_id'] = $this->request->post['facilities_id'];
					
					
					$tdata ['facilitytimezone'] = $facilitytimezone;				


					$this->model_resident_resident->tagmedication ( $this->request->post, $tdata );
				}else{
				
				$jsonData1 = stripslashes ( html_entity_decode ( $_REQUEST ['new_module'] ) );
				$new_module = json_decode ( $jsonData1, true );
				
				
				foreach($new_module as $key=>$newmodule){
					
					/*if($newmodule['image'] != null && $newmodule['image'] != ""){
						$outputFolder = $this->request->post['image'];
						$image_parts = explode(";base64,", $outputFolder);
						$image_type_aux = explode("image/", $image_parts[0]);
						$image_type = $image_type_aux[1];
						//$image_base64 = $image_parts[1];
						//var_dump($image_base64);

						//$notes_file = uniqid() . '.'.$image_type;
						$notes_file = uniqid() . '.jpeg';
				
						$s3file = $this->awsimageconfig->uploadFile3($notes_file, $outputFolder,$this->request->post ['facilities_id']);
						$new_module[$key]['image'] = $s3file;
					}*/
					
					if($newmodule['daily_times'] != null && $newmodule['daily_times'] != ""){
						$sssssdds2 = explode ( ",", $newmodule['daily_times'] );
						$abdcds = array_unique ( $sssssdds2 );
						$new_module[$key]['daily_times'] = $abdcds;
					}
					
					if($newmodule['assign_to'] != null && $newmodule['assign_to'] != ""){
						$sssssdds22 = explode ( ",", $newmodule['assign_to'] );
						$abdcds2 = array_unique ( $sssssdds22 );
						$new_module[$key]['assign_to'] = $abdcds2;
					}
					if($newmodule['user_role_assign_ids'] != null && $newmodule['user_role_assign_ids'] != ""){
						$sssssddsr = explode ( ",", $newmodule['user_role_assign_ids'] );
						$abdcdsr = array_unique ( $sssssddsr );
						$new_module[$key]['user_role_assign_ids'] = $abdcdsr;
					}
					if($newmodule['tags_medication_details_ids'] != null && $newmodule['tags_medication_details_ids'] != ""){
						$sssssddsri = explode ( ",", $newmodule['tags_medication_details_ids'] );
						$abdcdsri = array_unique ( $sssssddsri );
						$new_module[$key]['tags_medication_details_ids'] = $abdcdsri;
					}
				}
				
				$this->request->post['new_module'] = $new_module;
				
				$medication_fields = array();
				if($this->request->post['allergies'] != null && $this->request->post['allergies'] != ""){
					$medication_fields['allergies']  = $this->request->post['allergies'];
					
					$this->request->post['medication_fields'] = $medication_fields;
				}
				
				$user_role_assign_ids = array();
				if($this->request->post['user_role_assign_ids'] != null && $this->request->post['user_role_assign_ids'] != ""){
					$user_role_assign_ids['user_role_assign_ids']  = $this->request->post['user_role_assign_ids'];
					
					$this->request->post['user_role_assign_ids'] = $user_role_assign_ids;
				}
				
				$assign_to = array();
				if($this->request->post['assign_to'] != null && $this->request->post['assign_to'] != ""){
					$assign_to['assign_to']  = $this->request->post['assign_to'];
					
					$this->request->post['assign_to'] = $assign_to;
				}
				
				
				$archive_tags_medication_id = $this->model_resident_resident->addTagsMedication ( $this->request->post, $this->request->post ['tags_id'], $this->request->post ['updateMedication'], $this->request->post ['facilities_id'], $this->request->post ['addmedication']);
				
				
				
				if($this->request->post['facilities_id']){
				$this->load->model('facilities/facilities');
					$facilities_info = $this->model_facilities_facilities->getfacilities($this->request->post['facilities_id']);
					
					$this->load->model('setting/timezone');
						
					$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
					$facilitytimezone = $timezone_info['timezone_value'];
				}
				$timeZone = date_default_timezone_set ( $facilitytimezone );
				$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
				$date_added = ( string ) $noteDate;
				
				$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
				
				if ($this->request->post ['imgOutput']) {
					$data ['imgOutput'] = $this->request->post ['imgOutput'];
				} else {
					$data ['imgOutput'] = $this->request->post ['signature'];
				}
				
				$data ['notes_pin'] = $this->request->post ['notes_pin'];
				$data ['user_id'] = $this->request->post ['user_id'];
				
				$this->load->model ( 'setting/tags' );
				$tag_info = $this->model_setting_tags->getTag ( $this->request->post ['tags_id']);
				
				$data ['emp_tag_id'] = $tag_info ['emp_tag_id'];
				$data ['tags_id'] = $tag_info ['tags_id'];
				
				if ($tag_info ['emp_first_name']) {
					$emp_tag_id = $tag_info ['emp_tag_id'] . ':' . $tag_info ['emp_first_name'];
					
					$this->load->model ( 'setting/locations' );
					$location_info = $this->model_setting_locations->getlocation ( $tag_info ['room'] );
					
					$emp_tag_id = $tag_info ['emp_last_name'] . ', ' . $tag_info ['emp_first_name'] . ' | ' . $tag_info ['ssn'] . ' | ' . $location_info ['location_name'] . ' | ';
				} else {
					$emp_tag_id = $tag_info ['emp_tag_id'];
				}
				
				if ($tag_info) {
					$medication_tags .= $emp_tag_id . ' ';
				}
				
				$notes_description = '';
				// $description .= $keywordData2['keyword_name'];
				// $description .= ' | ';
				// $description .= ' Completed for | '.date('h:i A',
				// strtotime($notetime)) .' ';
				
				if ($this->request->post ['addmedication'] != null && $this->request->post ['addmedication'] != "") {
					
					$notes_description .= ' Medication Form updated | ';
					//$data ['addmedication'] = $this->request->get ['addmedication'];
				} else {
					
					$notes_description .= ' Health Form updated | ';
				}
				
				$notes_description .= ' ' . $medication_tags;
				
				if ($this->request->post ['comments'] != null && $this->request->post ['comments']) {
					$notes_description .= ' | ' . $this->db->escape ( $this->request->post ['comments'] );
				}
				
				// $description .= ' | ';
				
				// $data['notes_description'] = $keywordData2['keyword_name'].' | '.
				// $tag_info['emp_tag_id'].':'.$tag_info['emp_first_name'] .' |
				// '.$medicationf . $comments;

				if($this->request->post['addmedication']=="1"){

					$is_forms='1';

				}else{

					$is_forms='0';


				}


				
				$data ['notes_description'] = $notes_description;
				$data ['date_added'] = $date_added;
				$data ['note_date'] = $date_added;
				$data ['notetime'] = $notetime;
				$data ['addmedication'] = $is_forms;
				
				$this->model_notes_notes->updatetagsmedicinearchive1 ( $this->request->post ['tags_id'] );
				
				
				$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $this->request->post ['facilities_id'] );
					
				$mdata2 = array ();
				$mdata2 ['notes_id'] = $notes_id;
				$mdata2 ['tags_id'] = $this->request->post ['tags_id'];
				$mdata2 ['archive_tags_medication_id'] = $archive_tags_medication_id;
				
				$this->model_notes_notes->updatetagsmedicinearchive2 ( $mdata2 );
				
			}
			
				$this->load->model('api/facerekognition');
				$fre_array2 = array();
				$fre_array2['face_notes_file'] = $this->request->post['face_notes_file'];
				$fre_array2['outputFolder'] = $this->request->post['outputFolder'];
				$fre_array2['face_not_verify'] = $this->request->post['face_not_verify'];
				$fre_array2['facilities_id'] = $this->request->post['facilities_id'];
				$fre_array2['notes_file'] = $facerekognition_response['imagedata']['notes_file'];
				$fre_array2['outputFolder_1'] = $facerekognition_response['imagedata']['outputFolder'];
				$fre_array2['notes_id'] = $notes_id;
				$this->model_api_facerekognition->savefacerekognitionnotes($fre_array2);
					
						
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
			
			
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in medication addmedication '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('addmedication', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		} 	
	
	}

	public function givemedication(){
		try{
			
		$this->load->model ( 'activity/activity' );
		$this->model_activity_activity->addActivitySave ( 'givemedication', $this->request->post, 'request' );
			
		$this->data['facilitiess'] = array();
		$this->load->model('facilities/facilities');
		$this->load->model('notes/notes');
		$this->load->model('setting/tags');
		$this->load->model('api/encrypt');
		$cre_array = array();
		$cre_array['phone_device_id'] = $this->request->post['phone_device_id'];
		$cre_array['facilities_id'] = $this->request->post['facilities_id'];
		  /*$api_device_info = $this->model_api_encrypt->getdevicedetails($cre_array);
			
			if($api_device_info == false){
				$errorMessage = $this->model_api_encrypt->errorMessage();
				return $errorMessage;
			}
			
			$api_header_value = $this->model_api_encrypt->getallheaders1();
			
			if($api_header_value == false){
				$errorMessage = $this->model_api_encrypt->errorMessage();
				return $errorMessage;
			}*/
			if ($this->request->post ['tags_id'] == null && $this->request->post ['tags_id'] == "") {
					$json ['warning'] = 'Warning: Enter tags Id';
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
			
			if ($this->request->post ['facilities_id'] == null && $this->request->post ['facilities_id'] == "") {
					$json ['warning'] = 'Warning: Enter Facilities Id';
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
			
			
			if($this->request->post['notes_pin'] != null && $this->request->post['notes_pin'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);
			
			

			if (($this->request->post['notes_pin'] != $user_info['user_pin'])) {
				$json['warning'] = 'User Pin not valid!.';
				$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
			}
		}
		
		if($this->request->post['user_id'] != null && $this->request->post['user_id'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if (($user_info['status'] == '0')) {
				$json['warning'] = 'User not exit!';
				$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
			}
			
			$this->load->model('facilities/facilities');
				$facility = $this->model_facilities_facilities->getfacilities($this->request->post['facilities_id']);
				$unique_id = $facility['customer_key'];
				
				
				$this->load->model('customer/customer');
				$customer_info = $this->model_customer_customer->getcustomerid($unique_id);
				
				if($user_info['customer_key'] != $customer_info['activecustomer_id']){
					$json['warning'] = $this->language->get('error_customer');
					$facilitiessee = array();
							$facilitiessee[] = array(
								'warning'  => $json['warning'],
							);
							$error = false;
							
							$value = array('results'=>$facilitiessee,'status'=>false);

						return $this->response->setOutput(json_encode($value));
				}
		}
		
		
		
		if($this->request->post['current_enroll_image1'] == "1"){
			$this->load->model('api/facerekognition');
			$fre_array = array();
			$fre_array['current_enroll_image1'] = $this->request->post['current_enroll_image1'];
			$fre_array['facilities_id'] = $this->request->post['facilities_id'];
			$fre_array['user_id'] = $this->request->post['user_id'];
			$facerekognition_response = $this->model_api_facerekognition->checkfacerekognition($fre_array, $this->request->post);
			
			$json['warning'] = $facerekognition_response['warning1'];
			
			$facilitiessee = array();
				$facilitiessee[] = array(
					'warning'  => $json['warning'],
				);
				$error = false;
				
				$value = array('results'=>$facilitiessee,'status'=>false);

			return $this->response->setOutput(json_encode($value));
			}
			
			if ($json ['warning'] == null && $json ['warning'] == "") {
				$this->load->model ( 'notes/notes' );
				
				$this->load->model ( 'resident/resident' );
				
				
				$tdata = array ();
				$tdata ['tags_id'] = $this->request->post ['tags_id'];
				$tdata ['medication_tags'] = $this->request->post ['medication_tags'];
				
				$taginfo = $this->model_setting_tags->getTag ( $this->request->post ['tags_id'] );
				//$result = $this->model_facilities_facilities->getfacilities ( $taginfo ['facilities_id'] );
				
				
				if($this->request->post['facilities_id']){
					$this->load->model('facilities/facilities');
					$facilities_info = $this->model_facilities_facilities->getfacilities($this->request->post['facilities_id']);
					
					$this->load->model('setting/timezone');
						
					$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
					$facilitytimezone = $timezone_info['timezone_value'];
				}
				$timeZone = date_default_timezone_set ( $facilitytimezone );
				$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
				$date_added = ( string ) $noteDate;
				
				$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
					
				$tdata ['facilities_id'] = $this->request->post['facilities_id'];
				
				
				$tdata ['facilitytimezone'] = $facilitytimezone;				


				$this->model_resident_resident->tagmedication ( $this->request->post, $tdata );
				
			
				$this->load->model('api/facerekognition');
				$fre_array2 = array();
				$fre_array2['face_notes_file'] = $this->request->post['face_notes_file'];
				$fre_array2['outputFolder'] = $this->request->post['outputFolder'];
				$fre_array2['face_not_verify'] = $this->request->post['face_not_verify'];
				$fre_array2['facilities_id'] = $this->request->post['facilities_id'];
				$fre_array2['notes_file'] = $facerekognition_response['imagedata']['notes_file'];
				$fre_array2['outputFolder_1'] = $facerekognition_response['imagedata']['outputFolder'];
				$fre_array2['notes_id'] = $notes_id;
				$this->model_api_facerekognition->savefacerekognitionnotes($fre_array2);
					
						
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
			
			
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in medication givemedication '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('givemedication', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		} 	
	
	}
	
	public function getClientdetail(){
		
		try{
			
		$this->load->model ( 'activity/activity' );
		$this->model_activity_activity->addActivitySave ( 'getClientdetail', $this->request->post, 'request' );
			
		$this->data['facilitiess'] = array();
		$this->load->model('facilities/facilities');
		$this->load->model('notes/notes');
		$this->load->model('setting/tags');
		$this->load->model('api/encrypt');
		$cre_array = array();

		$cre_array['phone_device_id'] = $this->request->post['phone_device_id'];
		$cre_array['facilities_id'] = $this->request->post['facilities_id'];
		
			/*
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
			
			*/
			
			$data = array();
			$data['facilities_id'] = $this->request->post['facilities_id'];
			
			if($this->request->post['facilities_id']){
				$this->load->model('facilities/facilities');
					$facilities_info = $this->model_facilities_facilities->getfacilities($this->request->post['facilities_id']);
					
					$this->load->model('setting/timezone');
						
					$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
					$facilitytimezone = $timezone_info['timezone_value'];
			}
			
			$tag = $this->model_setting_tags->getTag($this->request->post['tags_id']);
			
			if(!empty($tag)){
				
				$get_img = $this->model_setting_tags->getImage($tag['tags_id']);			
				
				
                if ($get_img['upload_file_thumb'] != null && $get_img['upload_file_thumb'] != "") {
                    $upload_file_thumb_1 = $get_img['upload_file_thumb'];
                } else {
                    $upload_file_thumb_1 = $get_img['enroll_image'];
                }
				
				$this->data['facilitiess'][] = array(
					'name' => $tag['emp_first_name'].' '.$tag['emp_last_name'],
					'tags_id' => $tag['tags_id'],
					'facilities_id' => $tag['facilities_id'],
					'emp_first_name' => $tag['emp_first_name'],
					'emp_last_name' => $tag['emp_last_name'],
					'emp_middle_name' => $tag['emp_middle_name'],
					'emp_tag_id' => $tag['emp_tag_id'],
					'emp_extid' => $tag['emp_extid'],
					'ccn' => $tag['ccn'],
					'date_added' => date ( 'm-d-Y', strtotime ($tag['date_added'])),
					'gender' => $tag['gender'],
					'customlistvalues_id' => $tag['customlistvalues_id'],
					'dob' => $tag['dob'],
					'tag_status_id' => $tag['tag_status_id'],
					'tag_classification_id' => $tag['tag_classification_id'],
					'upload_file_thumb' => $upload_file_thumb_1,
					'upload_file' => $upload_file_thumb_1,
				);
				$error = true;
			
			}else{
				
				$this->data['facilitiess'][] = array(
					'warning'  => "Tags not found",
				);
				$error = false;
			}
			
			$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
			$this->response->setOutput(json_encode($value));
			return;
				
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices jsongetClientdetail '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('app_getClientdetail', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		}   
		
	}
	
	
}