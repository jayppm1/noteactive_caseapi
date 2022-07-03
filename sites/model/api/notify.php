<?php
class Modelapinotify extends Model {
	
public function sendnotification($jsondata = array(), $registration_id) {
	
	define('API_ACCESS_KEY','AIzaSyDYKbRciBJybysc9YzpA_pdOXM_TsTUCSI' );
	//define('API_ACCESS_KEY','AIzaSyANoDaxPh2mmxiFGO8d-fBjAqrXMaYDd10' );
	$registrationIds = array($registration_id);
	$message = array();
	
	$this->load->model('createtask/createtask');
		
	
	if( !empty($jsondata['tasklits'] )){
		foreach($jsondata['tasklits'] as $list){
			
			/*$message = array
			(
			'tasktype' 	=> $list['tasktype'],
			'assign_to'		=> $list['assign_to'],
			'description'	=> $list['description'],
			'date'		=> $list['date'],
			'tickerText' => $list['recurrence'],
			'vibrate'	=> 1,
			'sound'		=> 1,
			
			);*/
			
			
			
			$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName($list['tasktype'],$list['facilities_info']['facilities_id']);
			
			if($tasktype_info['android_audio_file'] !=NULL && $tasktype_info['android_audio_file'] !=""){
				$facility_android_audio_file = HTTP_SERVER .'image/ringtone/'.$tasktype_info['android_audio_file']; 
			}else{
				$facility_android_audio_file = '';
			}
			
			if($tasktype_info['ios_audio_file'] !=NULL && $tasktype_info['ios_audio_file'] !=""){
				$facility_ios_audio_file = HTTP_SERVER .'image/ringtone/'.$tasktype_info['ios_audio_file']; 
			}else{
				$facility_ios_audio_file = '';
			}
			
			if($tasktype_info['display_custom_list'] == '1'){
				$display_custom_list = $tasktype_info['display_custom_list'];
			}else{
				$display_custom_list = 0;
			}
			
			$message = array
			(
			'taskDuration' =>$list['taskDuration'],
			'assign_to' =>$list['assign_to'],
			'required_assign' =>$list['required_assign'],
			'task_group_by' =>$list['task_group_by'],
			'iswaypoint' =>$list['iswaypoint'],
			'enable_requires_approval' =>$list['enable_requires_approval'],
			'is_approval_required_forms_id' =>$list['is_approval_required_forms_id'],
			'attachement_form' =>$list['attachement_form'],
			'tasktype_form_id' =>$list['tasktype_form_id'],
			'recurrence' =>$list['recurrence'],
			'tasktype' =>$list['tasktype'],
			'checklist' =>$list['checklist'],
			'task_complettion' =>$list['task_complettion'],
			'device_id' =>$list['device_id'],
			'date' => $list['task_date'],
			'id' =>$list['id'],
			'description' =>$list['description'],
			'task_time' =>$list['task_time'],
			'snooze_time' =>$list['snooze_time'],
			'strice_href' => $list['strice_href'],
			//'insert_href' => $list['insert_href'],
			'task_form_id' =>  $list['task_form_id'],
			'tags_id' =>$list['tags_id'],
			
			'display_custom_list' =>$tasktype_info['display_custom_list'],
			'is_android_snooze' =>$tasktype_info['is_android_snooze'],
			'is_android_dismiss' =>$tasktype_info['is_android_dismiss'],
			'is_ios_snooze' =>$tasktype_info['is_ios_snooze'],
			'is_ios_dismiss' =>$tasktype_info['is_ios_dismiss'],
			'facility_ios_audio_file' =>$facility_ios_audio_file,
			'facility_android_audio_file' =>$facility_android_audio_file,
			
			'facilities_id' =>$list['facilities_info']['facilities_id'],
			'facility' =>$list['facilities_info']['facility'],
			'is_enable_add_notes_by' =>$list['facilities_info']['is_enable_add_notes_by'],
			'is_client_facial' =>$list['facilities_info']['is_client_facial'],
			'allow_quick_save' =>$list['facilities_info']['allow_quick_save'],
			
			'vibrate'	=> 1,
			'sound'		=> '',
			
			);
			
			$fields = array
			(
			'registration_ids' 	=> $registrationIds,
			'data'			=> array ('message' 	=> $message)
			);
		
			$headers = array
				(
				'Authorization: key=' . API_ACCESS_KEY,
				'Content-Type: application/json'
				);
			
			
		   $ch = curl_init();
			//curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
			curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
			curl_setopt( $ch,CURLOPT_POST, true );
			curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
			curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
			$result = curl_exec($ch );
			
			if ($result === FALSE)
				{
				die('Curl failed: ' . curl_error($ch));
				$value = array('result'=>$result ,'status'=>false);
				}else{
				 $value = array('result'=>$result ,'status'=>true);
				}
								
			curl_close( $ch );
			$this->response->setOutput(json_encode($value));
			
			$barray1 = array(); 
			$barray1['registration_id']= $registration_id;
			$barray1['postdata']= $jsondata;
			$barray1['request']= $message;
			$barray1['response']= $value;
			
			$this->load->model('activity/activity');
			$this->model_activity_activity->addActivitySave('apptasklits', $barray1, 'query');
		}
	}
	
	
	if(!empty($jsondata['rulenotes'])){
		foreach($jsondata['rulenotes'] as $data){
			
			$notes_description = substr($data['notes_description'], 0, 150) .((strlen($data['notes_description']) > 150) ? '..' : '');
			$messageru = array
			(
				'notes_id' 	=> $data['notes_id'],
				'rules_id'		=> $data['rules_id'],
				'notes_description'	=> $notes_description,
				'username'	=> $data['username'],
				'email'		=> $data['email'],
				'facilities_id' =>$list['facilities_info']['facilities_id'],
				'facility' =>$list['facilities_info']['facility'],
				'is_enable_add_notes_by' =>$list['facilities_info']['is_enable_add_notes_by'],
				'is_client_facial' =>$list['facilities_info']['is_client_facial'],
				'allow_quick_save' =>$list['facilities_info']['allow_quick_save'],
				'vibrate'	=> 1,
				'sound'		=> '',
				'task_group_by'		=> '',
			);
			
			$fields = array
			(
			'registration_ids' 	=> $registrationIds,
			'data'			=> array ('message' 	=> $messageru)
			);
		
			$headers = array
				(
				'Authorization: key=' . API_ACCESS_KEY,
				'Content-Type: application/json'
				);
			
			
		   $ch = curl_init();
			//curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
			curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
			curl_setopt( $ch,CURLOPT_POST, true );
			curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
			curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
			$result = curl_exec($ch );
			
			if ($result === FALSE)
				{
				die('Curl failed: ' . curl_error($ch));
				$value = array('result'=>$result ,'status'=>false);
				}else{
				 $value = array('result'=>$result ,'status'=>true);
				}
								
			curl_close( $ch );
			$this->response->setOutput(json_encode($value));
			
			$barray1 = array(); 
			$barray1['registration_id']= $registration_id;
			$barray1['postdata']= $jsondata;
			$barray1['request']= $message;
			$barray1['response']= $value;
			
			$this->load->model('activity/activity');
			$this->model_activity_activity->addActivitySave('apprulenotes', $barray1, 'query');
		}
	}
	
	
	if(!empty($jsondata['formrules'])){
		foreach($jsondata['formrules'] as $data){
			
			$notes_description = substr($data['notes_description'], 0, 150) .((strlen($data['notes_description']) > 150) ? '..' : '');
			
			$messager = array
			(
			'notes_id' 	=> $data['notes_id'],
			'rules_id'		=> $data['rules_id'],
			'notes_description'	=> $notes_description,
			'username'	=> $data['username'],
			'email'		=> $data['email'],
			'facilities_id' =>$list['facilities_info']['facilities_id'],
			'facility' =>$list['facilities_info']['facility'],
			'is_enable_add_notes_by' =>$list['facilities_info']['is_enable_add_notes_by'],
			'is_client_facial' =>$list['facilities_info']['is_client_facial'],
			'allow_quick_save' =>$list['facilities_info']['allow_quick_save'],
			'vibrate'	=> 1,
			'sound'		=> '',
			'task_group_by'		=> '',
			);
			
			$fields = array
			(
			'registration_ids' 	=> $registrationIds,
			'data'			=> array ('message' 	=> $messager)
			);
		
		$headers = array
			(
			'Authorization: key=' . API_ACCESS_KEY,
			'Content-Type: application/json'
			);
		
		
	   $ch = curl_init();
		//curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
		curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch );
		
		if ($result === FALSE)
			{
			die('Curl failed: ' . curl_error($ch));
			$value = array('result'=>$result ,'status'=>false);
			}else{
			 $value = array('result'=>$result ,'status'=>true);
			}
							
		curl_close( $ch );
		$this->response->setOutput(json_encode($value));
		
		$barray1 = array(); 
		$barray1['registration_id']= $registration_id;
		$barray1['postdata']= $jsondata;
		$barray1['request']= $message;
		$barray1['response']= $value;
		
		$this->load->model('activity/activity');
		$this->model_activity_activity->addActivitySave('appformrules', $barray1, 'query');
		}
	}
	
	
	
}



public function websendnotification($jsondata = array(), $tokenID){

		define( 'API_ACCESS_KEY', 'AAAAght1l8s:APA91bFgf4C6-2LIozViuEeX-SZkEys3K78dKLVWPxLK6q3IUYkwH4WPVnSQKVIrXcrwnmjb8Cw9c7Q1qHpb7Hb981aMeFbgrsvVPVqTEWK3wP_271ngWDEvO34lh9rDnzESKcEIh1OU' );
		
		$too = $tokenID;
		//var_dump($jsondata);

		
		$message = array();
		$message22 = array();
		
		if( !empty($jsondata['tasklits'] )){
			foreach($jsondata['tasklits'] as $list){
				
				$description = substr($list['description'], 0, 150) .((strlen($list['description']) > 150) ? '..' : '');
				
				$message = array
				(
				
				'popup' 	=> 'tasklits',							
				'taskDuration' =>$list['taskDuration'],
				'required_assign' =>$list['required_assign'],
				'assign_to' =>$list['assign_to'],
				'tasktype' =>$list['tasktype'],
				'attachement_form' =>$list['attachement_form'],
				'enable_requires_approval' =>$list['enable_requires_approval'],
				'tasktype_form_id' =>$list['tasktype_form_id'],
				'checklist' =>$list['checklist'],
				'date' => date('j, M Y', strtotime($list['task_date'])),
				'id' =>$list['id'],
				'description' =>$list['description'],
				'task_time' =>date('h:i A', strtotime($list['task_time'])),
				'strice_href' => str_replace('&amp;', '&', $this->url->link('notes/createtask/updateStriketask', '' . 'task_id=' . $list['id'])),
				'incident_form_href' => $list['incident_form_href'],
				'bed_check_href' => $list['bed_check_href'],
				
				'insert_href' => $list['insert_href'],
				'task_form_id' =>  $list['task_form_id'],
				'tags_id' =>$list['tags_id'],
				'pickup_facilities_id' => $list['pickup_facilities_id'],
				'pickup_locations_address' =>$list['pickup_locations_address'],
				'pickup_locations_time' =>$list['pickup_locations_time'],
				'pickup_locations_latitude' =>$list['pickup_locations_latitude'],
				'pickup_locations_longitude' =>$list['pickup_locations_longitude'],
				'dropoff_facilities_id' =>$list['dropoff_facilities_id'],
				'dropoff_locations_address' =>$list['dropoff_locations_address'],
				'dropoff_locations_time' =>$list['dropoff_locations_time'],
				'dropoff_locations_latitude' =>$list['dropoff_locations_latitude'],
				'dropoff_locations_longitude' =>$list['dropoff_locations_longitude'],
				'transport_tags' =>$list['transport_tags'],
				'medications' =>$list['medications'],
				'bedchecks' =>$list['bedcheckdata'],
				
				'custom_form_href' => $list['custom_form_href'],
				'custom_form_href2' => $list['custom_form_href2'],
				'medication_tags' =>$list['medication_tags'],
				'visitation_tags' => $list['visitation_tags'],
				'visitation_tag_id' =>$list['visitation_tag_id'],
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
				
				'web_audio_file' => $list['web_audio_file'],
				'is_web_notification' => $list['is_web_notification'],
				'web_is_snooze' => $list['web_is_snooze'],
				'web_is_dismiss' => $list['web_is_dismiss'],
				
				'title' 	=> $list['tasktype'],
				'body'	=> $list['description'],
				'click_action' =>$list['insert_href'],
				'view_task'=>str_replace('&amp;', '&', $this->url->link('notes/notes/insert'/*, '' . 'task_id=' . $list['id']*/))
				);

											
				$message22[] = $message;
				
				$data = array("to" => $too,
						//"notification" => array( "title" => "Noteactive", "body" => "FCM Notification","click_action" => "http://noteactive.com")
						"notification" =>$message, "webpush"=> array("headers"=> array("Urgency"=> "high")),"data" => $message
				);  
				
				$data_string = json_encode($data); 							

				$headers = array
				(
					 'Authorization: key=' . API_ACCESS_KEY, 
					 'Content-Type: application/json'
				);                                                                                 

				$ch = curl_init();  

				curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );                                                                  
				curl_setopt( $ch,CURLOPT_POST, true );  
				curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
				curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
				curl_setopt( $ch,CURLOPT_POSTFIELDS, $data_string);                                                                  

				$result = curl_exec($ch);

				curl_close ($ch);
				
				$barray1 = array(); 
				$barray1['registration_id']= $too;
				$barray1['postdata']= $jsondata;
				$barray1['request']= $message;
				$barray1['response']= $result;

				$this->load->model('activity/activity');
				$this->model_activity_activity->addActivitySave('webnotification', $barray1, 'query');
			}
		}
		
		if(!empty($jsondata['rulenotes'])){
			foreach($jsondata['rulenotes'] as $data){
				
				$notes_description = substr($data['notes_description'], 0, 150) .((strlen($data['notes_description']) > 150) ? '..' : '');
				
				$message = array
				(
				'popup' 	=> 'rulenotes',
				'notes_id' 	=> $data['notes_id'],
				'rules_id'		=> $data['rules_id'],
				'notes_description'	=> $notes_description,
				'username'	=> $data['username'],
				'email'		=> $data['email'],
				'vibrate'	=> 1,
				'sound'		=> 1,
				'registrationIds'	=> $registrationIds,
				'task_id'	=> $data['id'],
				'task_time' => $data['task_time'],
				'enable_requires_approval' => $data['enable_requires_approval'],
				'device_id' => $data['device_id'],
				'task_group_by' => $data['task_group_by'],
				'iswaypoint' => $data['iswaypoint'],
				'insert_href' => $data['insert_href'],
				'attachement_form' => $data['attachement_form'],
				'checklist' => $data['checklist'],
				
				'web_audio_file' => $data['web_audio_file'],
				'is_web_notification' => $data['is_web_notification'],
				'web_is_snooze' => $data['web_is_snooze'],
				'web_is_dismiss' => $data['web_is_dismiss'],
				);
				
				$message22[] = $message;
				
				$data = array("to" => $too,
						//"notification" => array( "title" => "Noteactive", "body" => "FCM Notification","click_action" => "http://noteactive.com")
						"notification" =>$message, "webpush"=> array("headers"=> array("Urgency"=> "high")),"data" => $message
				);  
				
				$data_string = json_encode($data); 							

				$headers = array
				(
					 'Authorization: key=' . API_ACCESS_KEY, 
					 'Content-Type: application/json'
				);                                                                                 

				$ch = curl_init();  

				curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );                                                                  
				curl_setopt( $ch,CURLOPT_POST, true );  
				curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
				curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
				curl_setopt( $ch,CURLOPT_POSTFIELDS, $data_string);                                                                  

				$result = curl_exec($ch);

				curl_close ($ch);
				
				$barray1 = array(); 
				$barray1['registration_id']= $too;
				$barray1['postdata']= $jsondata;
				$barray1['request']= $message;
				$barray1['response']= $result;

				$this->load->model('activity/activity');
				$this->model_activity_activity->addActivitySave('webnotification', $barray1, 'query');
				
			}
		}
		
		
		if(!empty($jsondata['formrules'])){
			foreach($jsondata['formrules'] as $data){
				
				$notes_description = substr($data['notes_description'], 0, 150) .((strlen($data['notes_description']) > 150) ? '..' : '');
				
				$message = array
				(
				'popup' 	=> 'formrules',
				'notes_id' 	=> $data['notes_id'],
				'rules_id'		=> $data['rules_id'],
				'notes_description'	=> $notes_description,
				'username'	=> $data['username'],
				'email'		=> $data['email'],
				'vibrate'	=> 1,
				'sound'		=> 1,
				'registrationIds'	=> $registrationIds,
				'task_id'	=> $data['id'],
				'task_time' => $data['task_time'],
				'enable_requires_approval' => $data['enable_requires_approval'],
				'device_id' => $data['device_id'],
				'task_group_by' => $data['task_group_by'],
				'iswaypoint' => $data['iswaypoint'],
				'insert_href' => $data['insert_href'],
				'attachement_form' => $data['attachement_form'],
				'checklist' => $data['checklist'],
				
				'web_audio_file' => $data['web_audio_file'],
				'is_web_notification' => $data['is_web_notification'],
				'web_is_snooze' => $data['web_is_snooze'],
				'web_is_dismiss' => $data['web_is_dismiss'],
				);
				
				$message22[] = $message;
				
				$data = array("to" => $too,
						//"notification" => array( "title" => "Noteactive", "body" => "FCM Notification","click_action" => "http://noteactive.com")
						"notification" =>$message, "webpush"=> array("headers"=> array("Urgency"=> "high")),"data" => $message
				);  
				
				$data_string = json_encode($data); 							

				$headers = array
				(
					 'Authorization: key=' . API_ACCESS_KEY, 
					 'Content-Type: application/json'
				);                                                                                 

				$ch = curl_init();  

				curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );                                                                  
				curl_setopt( $ch,CURLOPT_POST, true );  
				curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
				curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
				curl_setopt( $ch,CURLOPT_POSTFIELDS, $data_string);                                                                  

				$result = curl_exec($ch);

				curl_close ($ch);
				
				$barray1 = array(); 
				$barray1['registration_id']= $too;
				$barray1['postdata']= $jsondata;
				$barray1['request']= $message;
				$barray1['response']= $result;

				$this->load->model('activity/activity');
				$this->model_activity_activity->addActivitySave('webnotification', $barray1, 'query');
			}
		}
					
				
	}	
			
}

