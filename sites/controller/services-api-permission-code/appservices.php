<?php 
 header('Access-Control-Allow-Origin:'.$_SERVER['HTTP_ORIGIN']);
 header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
 header('Access-Control-Max-Age: 1000');
 header('Access-Control-Allow-Headers: Content-Type');
 header('Content-type: application/json');
 header('Content-Type: text/html; charset=utf-8');
 header("Content-type: bitmap; charset=utf-8");
class Controllerservicesappservices extends Controller { 
	
	
	public function jsonFacilities(){
		
		try{
		$this->data['facilitiess'] = array();
		
		
		if($this->request->post['is_facilities'] != '1'){
			$this->load->model('api/encrypt');
			$cre_array = array();
			$cre_array['phone_device_id'] = $this->request->post['phone_device_id'];
			$cre_array['facilities_id'] = $this->request->post['facilities_id'];
			$cre_array['facilities1'] = '1';
			
			$api_device_info = $this->model_api_encrypt->getdevicedetails($cre_array);
			
			if($api_device_info == false){
				$errorMessage = $this->model_api_encrypt->errorMessage();
				return $errorMessage;
			}
		}
		
		$this->load->model('facilities/facilities');
		
		$data2 = array();
		$data2['facilities'] = $this->request->post['facilities'];  
		
		$results = $this->model_facilities_facilities->getfacilitiess($data2);
    	
		foreach ($results as $result) {
					
      		$this->data['facilitiess'][] = array(
				'facilities_id'    => $result['facilities_id'],
				'facility'   => $result['facility'],
				'firstname'   => $result['firstname'],
				'lastname'   => $result['lastname'],
				'email'   => $result['email'],
				'password'   => $result['password'],
				'salt'   => $result['salt'],
				'status'   => $result['status'],
			);
		}
		
		$value = array('results'=>$this->data['facilitiess'],'status'=>true,'config_face_recognition'  => $this->config->get('config_face_recognition'));
		/*echo json_encode($value);*/
		$this->response->setOutput(json_encode($value));
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices jsonFacilities',
			);
			$this->model_activity_activity->addActivity('app_jsonFacilities', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		} 	
	
	}
	
	
	public function jsonUsers(){
		
		try{
		$this->data['facilitiess'] = array();
		
		
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
		
		if($this->request->post['facilities_id'] != null && $this->request->post['facilities_id'] != ""){
			$this->load->model('user/user'); 
			$users = $this->model_user_user->getUsersByFacility($this->request->post['facilities_id'], $this->request->post['allusers']);
			
			
			
			foreach ($users as $user) {
						
				$this->data['facilitiess'][] = array(
					'user_id'    => $user['user_id'],
					'username'   => $user['username'],
					'firstname'   => $user['firstname'],
					'lastname'   => $user['lastname'],
					'user_pin'   => $user['user_pin'],
					'phone_number'   => $user['phone_number'],
					'email'   => $user['email'],
					'status'   => $user['status'],
					'user_group_id'   => $user['user_group_id'],
				);
			}
			$value = array('results'=>$this->data['facilitiess'],'status'=>true);
		}else{
			$error = false;
			$value = array('results'=>"No User Found",'status'=>false);
		}
		
		$this->response->setOutput(json_encode($value));
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices jsonUsers',
			);
			$this->model_activity_activity->addActivity('app_jsonUsers', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		} 
	
	}
	
	
	public function jsonRoles(){
		
		try{
		$this->data['facilitiess'] = array();
		
		
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
		
		if($this->request->post['facilities_id'] != null && $this->request->post['facilities_id'] != ""){
		
		$this->load->model('user/user_group'); 
		$userroles = $this->model_user_user_group->getUserGroups();
    	
		
		foreach ($userroles as $userrole) {
					
      		$this->data['facilitiess'][] = array(
				'user_group_id'    => $userrole['user_group_id'],
				'name'   => $userrole['name'],
			);
		}
			$value = array('results'=>$this->data['facilitiess'],'status'=>true);
		}else{
			$error = false;
			$value = array('results'=>"No User Found",'status'=>false);
		}
		
		$this->response->setOutput(json_encode($value));
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices jsonRoles',
			);
			$this->model_activity_activity->addActivity('app_jsonRoles', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		} 
	
	}
	
	public function jsonhighlighters(){ 
	
		try{
			
		$this->data['facilitiess'] = array();
		
		
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
		
		$this->load->model('setting/highlighter');
		$this->load->model('setting/image'); 
		$this->load->model('notes/image'); 
		
		$highlighters = $this->model_setting_highlighter->gethighlighters();
    	
		foreach ($highlighters as $highlighter) {
			
		if ($highlighter['highlighter_icon'] && file_exists(DIR_IMAGE . 'highlighter/'.$highlighter['highlighter_icon'])) {
				$file1 = '/highlighter/'.$highlighter['highlighter_icon'];
				$newfile4 = $this->model_setting_image->resize($file1, 70, 70);
				$newfile21 = DIR_IMAGE . $newfile4;
				$file12 = HTTP_SERVER . 'image/highlighter/'.$newfile4;
						
				$imageData1 = base64_encode(file_get_contents($newfile21));
				$strike_signature = 'data:'.$this->mime_content_type($file12).';base64,'.$imageData1;
			}else{
				$strike_signature = '';
			}
					
      		$this->data['facilitiess'][] = array(
				'highlighter_id'    => $highlighter['highlighter_id'],
				'highlighter_name'   => $highlighter['highlighter_name'],
				'highlighter_value'   => $highlighter['highlighter_value'],
				'highlighter_icon'   => $strike_signature,
			);
		}
		$value = array('results'=>$this->data['facilitiess'],'status'=>true);
		/*echo json_encode($value);*/
		$this->response->setOutput(json_encode($value));
		
			}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices jsonhighlighters',
			);
			$this->model_activity_activity->addActivity('app_jsonhighlighters', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		} 
	
	}
	
	public function jsonhoursFunction() {
		
		try{
		$this->load->model('setting/hoursminutes');
		$this->data['facilitiess'] = array();
		$results = $this->model_setting_hoursminutes->hoursFunction();
		
		foreach($results as $key=>$result){
			$this->data['facilitiess'][] = array(
					'key_id'  => $key,
					'value'       => $result,
				);
		}
		$value = array('results'=>$this->data['facilitiess'],'status'=>true);
			
		$this->response->setOutput(json_encode($value));
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices jsonhoursFunction',
			);
			$this->model_activity_activity->addActivity('app_jsonhoursFunction', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		} 
	}

	public function jsonminutesFunction() {
		try{
		$this->load->model('setting/hoursminutes');
		$results = $this->model_setting_hoursminutes->minutesFunction();
		$this->data['facilitiess'] = array();
		foreach($results as $key=>$result){
			$this->data['facilitiess'][] = array(
					'key_id'  => $key,
					'value'       => $result,
				);
		}
		$value = array('results'=>$this->data['facilitiess'],'status'=>true);
		
		$this->response->setOutput(json_encode($value));
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices jsonminutesFunction',
			);
			$this->model_activity_activity->addActivity('app_jsonminutesFunction', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		} 
	}
	
	public function jsonFacilityLogin() {
		
		try{
		
		$this->data['facilitiess'] = array();
		
		/*$this->request->post['facility'] = 'test';
		$this->request->post['password'] = '123456';
		$this->request->post['ipaddress'] = '125';
		$this->request->post['http_host'] = 'servitium.com';
		$this->request->post['http_referer'] = 'servitium.com';
		*/
		
		$json = array();
		
		if (!$this->customer->apploginlogin($this->request->post['facility'], $this->request->post['password'], $this->request->post['ipaddress'])) {
			$json['warning'] = 'Password does not match.';
		}
		
		$this->load->model('facilities/facilities');
		
		$facility_info = $this->model_facilities_facilities->getfacilitiesByfacility($this->request->post['facility']);
			
		if ($facility_info && !$facility_info['status']) {
			$json['warning'] = 'Your account requires approval before you can login.';
		}
		
		if($facility_info['facilities_id'] != null && $facility_info['facilities_id'] != ""){
			
			
			$facilityResult = $this->model_facilities_facilities->getfacilities($facility_info['facilities_id']);
			
			$users = $facilityResult['users'];
			
			/*if($users == null && $users == ""){
				$json['warning'] = 'You have not users Please create user';
			}else{ */
				/*$sql = "SELECT * FROM `" . DB_PREFIX . "user` ";
				$sql .= 'where 1 = 1 ';
				if ($facility_info['facilities_id'] != null && $facility_info['facilities_id'] != "") {
					$sql .= " and FIND_IN_SET('". $facility_info['facilities_id']."', facilities) ";
				}
				$query = $this->db->query($sql);
				$results = $query->rows;
				*/
				$udata = array();
				$udata['facilities_id'] = $facility_info['facilities_id'];
				
				$this->load->model('user/user');
				$results = $this->model_user_user->getUsers($udata);
				
				if((empty($results)) && ($users == null && $users == "")){
					$json['warning'] = 'You have not users Please create user';
				}
			/*}*/
		}
			
			
		if($json['warning'] == null && $json['warning'] == ""){
			
			if ($this->config->get('config_facility_online')) {
				$this->load->model('facilities/online');

				if (isset($this->request->post['ipaddress'])) {
					$ip = $this->request->post['ipaddress'];	
				} else {
					$ip = ''; 
				}

				if (isset($this->request->post['http_host'])) {
					$url = 'http://' . $this->request->post['http_host'];	
				} else {
					$url = ''; 
				}
				
				if (isset($this->request->post['http_referer'])) {
					$referer = $this->request->post['http_referer'];	
				} else {
					$referer = ''; 
				}

				
				$userId = $facility_info['facilities_id'];
				$activationkey = $this->request->post['activationkey'];
				
				$datal = array();
				$datal['facilities_id'] = $userId;
				$datal['url'] = $url;
				$datal['referer'] = $referer;
				$datal['username'] = $this->request->post['username'];
				$datal['activationkey'] = $this->request->post['activationkey'];
				$datal['ip'] = $ip;
				$datal['type'] = '1';

				$this->model_facilities_online->whosonline($datal);
			}
			
			$error = true;
			/*
			//$this->data['facilitiess'][]['facility'] = $facility_info['facility'];
			$this->data['facilitiess'][]['facilities_id'] = $facility_info['facilities_id'];
			*/
			
			$this->load->model('setting/timezone');
			$timezone_info = $this->model_setting_timezone->gettimezone($facility_info['timezone_id']);
			
			if($this->config->get('config_date_picker') != null && $this->config->get('config_date_picker') != ""){
				$config_date_picker = $this->config->get('config_date_picker');
			}else{
				$config_date_picker = '0';
			}
			
			if($this->config->get('config_time_picker') != null && $this->config->get('config_time_picker') != ""){
				$config_time_picker = $this->config->get('config_time_picker');
			}else{
				$config_time_picker = '0';
			}
			
			/*if($this->config->get('config_task_status') != null && $this->config->get('config_task_status') != ""){
				$config_task_status = $this->config->get('config_task_status');
			}else{
				$config_task_status = '0';
			}*/
			
			$config_admin_limit1 = $this->config->get('config_android_front_limit');
			if($config_admin_limit1 != null && $config_admin_limit1 != ""){
				$config_admin_limit = $config_admin_limit1;
			}else{
				$config_admin_limit = "25";
			}
			
			$error_config_android_front_limit1 = $this->config->get('config_android_front_limit');
			if($error_config_android_front_limit1 != null && $error_config_android_front_limit1 != ""){
				$config_android_front_limit = $error_config_android_front_limit1;
			}else{
				$config_android_front_limit = "25";
			}
			
			$mobile_db_page_limit1 = $this->config->get('mobile_db_page_limit');
			if($mobile_db_page_limit1 != null && $mobile_db_page_limit1 != ""){
				$mobile_db_page_limit = $mobile_db_page_limit1;
			}else{
				$mobile_db_page_limit = "20";
			}
			$mobile_list_view_page_limit1 = $this->config->get('mobile_list_view_page_limit');
			if($mobile_list_view_page_limit1 != null && $mobile_list_view_page_limit1 != ""){
				$mobile_list_view_page_limit = $mobile_list_view_page_limit1;
			}else{
				$mobile_list_view_page_limit = "25";
			}
			
			
			if ($this->request->post['searchdate'] != null && $this->request->post['searchdate'] != "") {
				$searchdate = date('Y-m-d', strtotime($this->request->post['searchdate']));
			} 
			
			$data = array(
				'facilities_id' => $facility_info['facilities_id'],
				'searchdate' => $searchdate,
				'advance_search' => '1',
			);
		
			$this->load->model('notes/notes');
			$notes_total = $this->model_notes_notes->getTotalnotess($data);
			
			if($this->config->get('config_secure') == "1"){
				$configUrl = '1';
				$configUrl2 = $this->config->get('config_ssl').'index.php?route=services/';
			} else { 
				$configUrl = '0';
				$configUrl2 = $this->config->get('config_url').'index.php?route=services/';
			}
			
			$notes_total2 = ceil($notes_total/$config_admin_limit);
			
			if($this->config->get('config_all_notification') != null && $this->config->get('config_all_notification') != ""){
				$config_all_notification = $this->config->get('config_all_notification');
			}else{
				$config_all_notification = '0';
			}
			
			if($this->config->get('config_task_sms') != null && $this->config->get('config_task_sms') != ""){
				$config_task_sms = $this->config->get('config_task_sms');
			}else{
				$config_task_sms = '0';
			}
			
			if($this->config->get('config_task_notification') != null && $this->config->get('config_task_notification') != ""){
				$config_task_notification = $this->config->get('config_task_notification');
			}else{
				$config_task_notification = '0';
			}
			
			if($this->config->get('config_task_email') != null && $this->config->get('config_task_email') != ""){
				$config_task_email = $this->config->get('config_task_email');
			}else{
				$config_task_email = '0';
			}
			
			if($this->config->get('config_task_deleted_time') != null && $this->config->get('config_task_deleted_time') != ""){
				$config_task_deleted_time = $this->config->get('config_task_deleted_time');
			}else{
				$config_task_deleted_time = '0';
			}
			
			if($facility_info['config_taskform_status'] !=NULL && $facility_info['config_taskform_status'] !=""){
			$taskstatus = $facility_info['config_taskform_status']; 
			}else{
				$taskstatus = '0';
			}
			
			if($facility_info['config_noteform_status'] !=NULL && $facility_info['config_noteform_status'] !=""){
				$noteformtatus = $facility_info['config_noteform_status']; 
			}else{
				$noteformtatus = '0';
			}
			
			if($facility_info['config_rules_status'] !=NULL && $facility_info['config_rules_status'] !=""){
				$ruletatus = $facility_info['config_rules_status']; 
			}else{
				$ruletatus = '0';
			}
			
			if($facility_info['config_display_camera'] !=NULL && $facility_info['config_display_camera'] !=""){
				$config_display_camera = $facility_info['config_display_camera']; 
			}else{
				$config_display_camera = '0';
			}
			
			if($this->config->get('config_transcription') != null && $this->config->get('config_transcription') != ""){
				$config_transcription = $this->config->get('config_transcription');
			}else{
				$config_transcription = '0';
			}
			
			if($facility_info['config_share_notes'] !=NULL && $facility_info['config_share_notes'] !=""){
				$config_share_notes = $facility_info['config_share_notes']; 
			}else{
				$config_share_notes = '0';
			}
			
			if($facility_info['config_sharepin_status'] !=NULL && $facility_info['config_sharepin_status'] !=""){
				$config_sharepin_status = $facility_info['config_sharepin_status']; 
			}else{
				$config_sharepin_status = '0';
			}
			
			if($facility_info['config_multiple_activenote'] !=NULL && $facility_info['config_multiple_activenote'] !=""){
				$config_multiple_activenote = $facility_info['config_multiple_activenote']; 
			}else{
				$config_multiple_activenote = '0';
			}
			
			
			if($facility_info['android_audio_file'] !=NULL && $facility_info['android_audio_file'] !=""){
				$android_audio_file = HTTP_SERVER .'image/ringtone/'.$facility_info['android_audio_file']; 
			}else{
				$android_audio_file = '';
			}
			
			if($facility_info['ios_audio_file'] !=NULL && $facility_info['ios_audio_file'] !=""){
				$ios_audio_file = HTTP_SERVER .'image/ringtone/'.$facility_info['ios_audio_file']; 
			}else{
				$ios_audio_file = '';
			}
			
			$this->load->model('api/encrypt');
			$edevice_username = $this->model_api_encrypt->encrypt($this->config->get('device_username'));
			$edevice_token = $this->model_api_encrypt->encrypt($this->config->get('device_token'));
			
			
			$this->data['facilitiess'][] = array(
				'facility'  => $facility_info['facility'],
				'timezone_value'  => $timezone_info['timezone_value'],
				'facilities_id'       => $facility_info['facilities_id'],
				'config_display_camera'  => $config_display_camera,
				'config_share_notes'  => $config_share_notes,
				'config_sharepin_status'  => $config_sharepin_status,
				'config_multiple_activenote'  => $config_multiple_activenote,
				'config_date_picker'  => $config_date_picker,
				'config_time_picker'  => $config_time_picker,
				'config_task_status'  => $facility_info['config_task_status'],
				'config_tag_status'  => $facility_info['config_tag_status'],
				'config_admin_limit'  => $config_admin_limit, 
				'notes_total'  => $notes_total2,
				'notes_total1'  => $notes_total,
				'http_check'  => $configUrl,
				'http_url'  => $configUrl2,
				'config_all_notification'  => $config_all_notification,
				'config_task_sms'  => $config_task_sms,
				'config_task_notification'  => $config_task_notification,
				'config_task_email'  => $config_task_email,
				'config_task_deleted_time'  => $config_task_deleted_time,
				'config_taskform_status'  => $taskstatus,
				'config_noteform_status'  => $noteformtatus,
				'config_rules_status'  => $ruletatus,
				'config_android_front_limit'  => $config_android_front_limit,
				'config_transcription'  => $config_transcription,
				'mobile_db_page_limit'  => $mobile_db_page_limit,
				'mobile_list_view_page_limit'  => $mobile_list_view_page_limit,
				
				'face_similar_percent'  => $facility_info['face_similar_percent'],
				'allow_face_without_verified'  => $facility_info['allow_face_without_verified'],
				'allow_quick_save'  => $facility_info['allow_quick_save'],
				'display_attchament'  => $facility_info['display_attchament'],
				'config_face_recognition'  => $this->config->get('config_face_recognition'),
				'active_notification'  => $this->config->get('active_notification'),
				
				'is_android_notification'  => $facility_info['is_android_notification'],
				'android_audio_file'  => $android_audio_file,
				'is_android_snooze'  => $facility_info['is_android_snooze'],
				'is_android_dismiss'  => $facility_info['is_android_dismiss'],
				'is_ios_notification'  => $facility_info['is_ios_notification'],
				'ios_audio_file'  => $ios_audio_file,
				'is_ios_snooze'  => $facility_info['is_ios_snooze'],
				'is_ios_dismiss'  => $facility_info['is_ios_dismiss'],
				
				'is_enable_beacon'  => $facility_info['is_enable_beacon'],
				'beacon_range'  => $facility_info['beacon_range'],
				'beacon_data_type_range'  => $facility_info['beacon_data_type_range'],
				
				'device_username'  => $edevice_username,
				'device_token'  => $edevice_token,
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

		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices jsonFacilityLogin',
			);
			$this->model_activity_activity->addActivity('app_jsonFacilityLogin', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		} 		
	}
	
	public function jsonGetFacility(){
		try{
		$this->data['facilitiess'] = array();
		
		$this->load->model('facilities/facilities');
		/*$facilities_id = '5';*/
		$facilities_id = $this->request->get['facilities_id'];
		$facility_info = $this->model_facilities_facilities->getfacilities($facilities_id);
		
		if($facility_info != null && $facility_info != ""){
			
			$this->load->model('setting/timezone');
			$timezone_info = $this->model_setting_timezone->gettimezone($facility_info['timezone_id']);
			
			$this->load->model('setting/country');
			$country_info = $this->model_setting_country->getCountry($facility_info['country_id']);
			
			$this->load->model('setting/zone');
			$zone_info = $this->model_setting_zone->getZone($facility_info['country_id']);
			
			$error = true;
			$this->data['facilitiess'][] = array(
				'facility'  => $facility_info['facility'],
				'firstname'  => $facility_info['firstname'],
				'lastname'  => $facility_info['lastname'],
				'email'  => $facility_info['email'],
				'description'  => $facility_info['description'],
				'address'  => $facility_info['address'],
				'location'  => $facility_info['location'],
				'zipcode'  => $facility_info['zipcode'],
				'timezone_name'  => $facility_info['timezone_name'],
				'timezone_value'  => $facility_info['timezone_value'],
				'country_name'  => $facility_info['country_name'],
				'iso_code_2'  => $facility_info['iso_code_2'],
				'zone_name'  => $facility_info['zone_name'],
				'code'  => $facility_info['code'],
				'facilities_id'       => $facility_info['facilities_id'],
			);
			
		}else{
			$this->data['facilitiess'][] = array(
				'warning'  => 'Not valid id',
			);
			$error = false;
		}
		
		$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
		
		$this->response->setOutput(json_encode($value));
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices jsonGetFacility',
			);
			$this->model_activity_activity->addActivity('app_jsonGetFacility', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		} 
		
	}

	public function jsongetNotes() { 
	
	try{
		$this->data['facilitiess'] = array();
		$this->language->load('notes/notes');
		$this->load->model('notes/notes');
		$this->load->model('setting/image'); 
		$this->load->model('notes/image'); 
		
		
		if (isset($this->request->post['keyword'])) {
      		$keyword = $this->request->post['keyword'];
    	} 
		
		if (isset($this->request->post['facilities_id'])) {
      		$facilities_id = $this->request->post['facilities_id'];
    	} 
		
		if (isset($this->request->post['user_id'])) {
      		$user_id = $this->request->post['user_id'];
    	} 
		
		if($this->request->post['note_date_from'] != null && $this->request->post['note_date_from'] != ""){
			$note_date_from = date('Y-m-d', strtotime($this->request->post['note_date_from']));
		}
		if($this->request->post['note_date_to'] != null && $this->request->post['note_date_to'] != ""){
			$note_date_to = date('Y-m-d', strtotime($this->request->post['note_date_to']));
		}
		
		
		if ($this->request->post['searchdate'] != null && $this->request->post['searchdate'] != "") {
			$this->data['note_date'] = $this->request->post['searchdate'];
			$searchdate = date('Y-m-d', strtotime($this->request->post['searchdate']));
		} else {
			$this->data['note_date'] =  date('d-m-Y');
		}
		
		if (isset($this->request->post['advance_search'])) {
      		$advance_search = $this->request->post['advance_search'];
    	} 

		
		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'facilities_id' => $facilities_id,
			'searchdate' => $searchdate,
			'note_date_from' => $note_date_from,
			'note_date_to' => $note_date_to,
			'keyword' => $keyword,
			'user_id' => $user_id,
			'advance_search' => $advance_search,
		);
		
		$results = $this->model_notes_notes->getnotess($data);
		
		$this->load->model('setting/highlighter');
		$this->load->model('user/user');
		$this->load->model('setting/keywords');
		
		$keywords = $this->model_setting_keywords->getkeywords();
		$keyarray = array();
		foreach($keywords as $keyword){
			$keyarray[] = $keyword['keyword_name'];
		}
    	
		if($results != null && $results != ""){
			foreach ($results as $result) {
			
				$highlighterData = $this->model_setting_highlighter->gethighlighter($result['highlighter_id']);
				$user_info = $this->model_user_user->getUser($result['user_id']);
				$strikeuser_info = $this->model_user_user->getUser($result['strike_user_id']);
				
				if($highlighterData['highlighter_value'] != null && $highlighterData['highlighter_value'] != ""){
					$highlighter_value = $highlighterData['highlighter_value'];
				}else{
					$highlighter_value = '';
				}
				
				if($strikeuser_info['username'] != null && $strikeuser_info['username'] != ""){
					$strikeusername = $strikeuser_info['username'];
				}else{
					$strikeusername = '';
				}
				
				if($result['strike_date_added'] != null && $result['strike_date_added'] != "0000-00-00 00:00:00"){
					$strikeDate = date($this->language->get('date_format_short'), strtotime($result['strike_date_added']));
				}else{
					$strikeDate = '';
				}
				
				
				/************** for signature and password key icon image size 300, 55 **************/
				
				if($result['notes_pin'] != null && $result['notes_pin'] != ""){
					$file = '/key.gif';
					
					$newfile = $this->model_setting_image->resize($file, 300, 55);
					$newfile2 = DIR_IMAGE . $newfile;
					$file1 = HTTP_SERVER . 'image/'.$newfile;
					
					$imageData = base64_encode(file_get_contents($newfile2));
					$signaturesrc = '';
					
				}else				
				if($result['signature'] != null && $result['signature'] != ""){
					
					$file5 = DIR_IMAGE . '/signature/'.$result['signature_image'];
					$file = 'signature/'.$result['signature_image'];
					
					$newfile = $this->model_setting_image->resize($file, 300, 55);
					$newfile2 = DIR_IMAGE . $newfile;
					$file1 = HTTP_SERVER . 'image/'.$newfile;
					
					//$newImage = $this->createThumbnail($file5);
					//var_dump($file1);
					
					$imageData = base64_encode(file_get_contents($newfile2));
					$signaturesrc = 'data:'.$this->mime_content_type($file1).';base64,'.$imageData;
					
					/*$signature = $result['signature'];*/
				}else{
					$signaturesrc = '';
				}
				
				
				if($result['strike_pin'] != null && $result['strike_pin'] != ""){
					$file13 = '/key.gif';
					
					$newfile4 = $this->model_setting_image->resize($file13, 300, 55);
					$newfile21 = DIR_IMAGE . $newfile4;
					$file12 = HTTP_SERVER . 'image/signature/'.$newfile4;
					
					$imageData1 = base64_encode(file_get_contents($newfile21));
					$strike_signature = '';
				}else
				if($result['strike_signature'] != null && $result['strike_signature'] != ""){
					
					$file13 = '/signature/'.$result['strike_signature_image'];
					
					$newfile4 = $this->model_setting_image->resize($file13, 300, 55);
					$newfile21 = DIR_IMAGE . $newfile4;
					$file12 = HTTP_SERVER . 'image/signature/'.$newfile4;
					
					
					
					$imageData1 = base64_encode(file_get_contents($newfile21));
					$strike_signature = 'data:'.$this->mime_content_type($file12).';base64,'.$imageData1;
					/*$strikesignature = $result['strike_signature'];*/
				}else{
					$strike_signature = '';
				}
				
				if($result['strike_pin'] != null && $result['strike_pin'] != ""){
					$strikePin = $result['strike_pin'];
				}else{
					$strikePin = '';
				}
				
				if($result['notes_pin'] != null && $result['notes_pin'] != ""){
					$notesPin = $result['notes_pin'];
				}else{
					$notesPin = '';
				}
				
				
				
				
				$matchData = $this->arrayInString( $keyarray , $result['notes_description']);
				if ($matchData != null && $matchData != "") {
					$dataKeyword = $matchData;
					$keywordData = $this->model_setting_keywords->getkeyword($dataKeyword);
				}else{
					$keywordData = "";
				}
					
				if ($keywordData['keyword_image'] && file_exists(DIR_IMAGE . 'icon/'.$keywordData['keyword_image'])) {
					
					$file16 = '/icon/'.$keywordData['keyword_image'];
					$newfile84 = $this->model_setting_image->resize($file16, 30, 30);
					$newfile216 = DIR_IMAGE . $newfile84;
					$file124 = HTTP_SERVER . 'image/icon/'.$newfile84;
							
					$imageData132 = base64_encode(file_get_contents($newfile216));
					$keyword_icon = 'data:'.$this->mime_content_type($file124).';base64,'.$imageData132;
						
				} elseif($result['notes_file'] != null && $result['notes_file'] != ""){
					$extension = strtolower(end(explode(".", $result['notes_file'])));
					if($extension == 'png' || $extension == 'jpg' || $extension == 'jpeg' || $extension == 'gif' || $extension == 'bmp'){
						$keyImageSrc = 'img';
					}else
					if($extension == 'doc' || $extension == 'docx'){
						$keyImageSrc = 'doc';
					}else
					if($extension == 'ppt' || $extension == 'pptx'){
						$keyImageSrc = 'ppt';
					}else
					if($extension == 'xls' || $extension == 'xlsx'){
						$keyImageSrc = 'xls';
					}else
					if($extension == 'pdf'){
						$keyImageSrc = 'pdf';
					}else
					if($extension == 'txt'){
						$keyImageSrc = 'txt';
					}else{
						$keyImageSrc = '';
					}
					$keyword_icon = '';
				}else{
					$keyword_icon = '';
					$keyImageSrc = '';
				}
				
				
				
				if($result['notes_file'] != null && $result['notes_file'] != ""){
					$outputFolderUrl = HTTP_SERVER.'image/files/' . $result['notes_file'];
					$extension = strtolower(end(explode(".", $result['notes_file'])));
					if($extension == 'png' || $extension == 'jpg' || $extension == 'jpeg' || $extension == 'gif' || $extension == 'bmp'){
						$keyImageSrc = 'img';
					}else
					if($extension == 'doc' || $extension == 'docx'){
						$keyImageSrc = 'doc';
					}else
					if($extension == 'ppt' || $extension == 'pptx'){
						$keyImageSrc = 'ppt';
					}else
					if($extension == 'xls' || $extension == 'xlsx'){
						$keyImageSrc = 'xls';
					}else
					if($extension == 'pdf'){
						$keyImageSrc = 'pdf';
					}else
					if($extension == 'txt'){
						$keyImageSrc = 'txt';
					}else{
						$keyImageSrc = '';
					}
				}else{
					$outputFolderUrl = "";
					$keyImageSrc = "";
				}
				
				$reminder_info = $this->model_notes_notes->getReminder($result['notes_id']);
			
				$reminder_time = $reminder_info['reminder_time'];
				$reminder_title = $reminder_info['reminder_title'];
				
				if($reminder_time != null && $reminder_time != ""){
					$reminderTime = $reminder_time;
				}else{
					$reminderTime = "";
				}
				if($reminder_title != null && $reminder_title != ""){
					$reminderTitle = $reminder_title;
				}else{
					$reminderTitle = "";
				}
				$this->data['facilitiess'][] = array(
					'notes_id'    => $result['notes_id'],
					'highlighter_value'   => $highlighter_value,
					'notes_description'   => $result['notes_description'],
					'attachment_icon'   => $keyImageSrc,
					'attachment_url'   => $outputFolderUrl,
					'keyword_icon'   => $keyword_icon,
					'notetime'   => $result['notetime'],
					'username'      => $user_info['username'],
					'signature'   => $signaturesrc,
					'notes_pin'   => $notesPin,
					
					'text_color_cut'   => $result['text_color_cut'],
					'text_color'   => $result['text_color'],
					'note_date'   => date($this->language->get('date_format_short'), strtotime($result['note_date'])),
					'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'strike_user_name'   => $strikeusername,
					'strike_signature'   => $strike_signature,
					'strike_date_added'   => $strikeDate,
					'strike_pin'   => $strikePin,
					'reminder_title'   => $reminderTitle,
					'reminder_time'   => $reminderTime,
					
					
				); 
			}
			$error = true;
		}else{
			$this->data['facilitiess'] = array();
			$error = true;
			
		}
		$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
		/*echo json_encode($value);*/
		$this->response->setOutput(json_encode($value));
		
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices jsongetNotes',
			);
			$this->model_activity_activity->addActivity('app_jsongetNotes', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		} 
		
		
	}
	
	public function jsongetColor(){
		try{
			
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
			
		$colors = array(
			"Black"  => "#000000",
			"Red" 	 => "#FF0000",
			"Green"  => "#008000",
			"Blue" 	 => "#0000FF",
		);
		$this->data['facilitiess'] = array();
		foreach($colors as $key=>$result){
			$this->data['facilitiess'][] = array(
					'key_id'  => $key,
					'value'       => $result,
				);
		}
		$value = array('results'=>$this->data['facilitiess'],'status'=>true);
			
		$this->response->setOutput(json_encode($value));
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices jsongetColor',
			);
			$this->model_activity_activity->addActivity('app_jsongetColor', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		} 
	}
	
	public function mime_content_type($filename) {

		try{
		
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
		
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices mime_content_type',
			);
			$this->model_activity_activity->addActivity('app_mime_content_type', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		} 
    }

	public function jsonAddNotes(){
		
		try{
		$this->data['facilitiess'] = array();
		
		$json = array();
		
		$this->load->model('notes/notes');
		$this->language->load('notes/notes');
		
		
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
		
		
		if (!$this->request->post['notes_description']) {
			$json['warning'] = 'Please insert required!.';
		}
		
		if($this->request->post['notes_pin'] != null && $this->request->post['notes_pin'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if (($this->request->post['notes_pin'] != $user_info['user_pin'])) {
				$json['warning'] = 'User Pin not valid!.';
			}
		}
		
		if($this->request->post['user_id'] != null && $this->request->post['user_id'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if (($user_info['status'] == '0')) {
				$json['warning'] = 'User not exit!';
			}
		}
		
		if($this->request->post['note_date'] != null && $this->request->post['note_date'] != ""){
				$note_date = date('m-d-Y',strtotime($this->request->post['note_date']));
				
				date_default_timezone_set($this->request->post['facilitytimezone']);
				$current_date = date('m-d-Y', strtotime('now'));
				
				if($current_date < $note_date){
					$json['warning'] = "You can not add future notes";
				}
		}


		if($this->config->get('config_face_recognition') == '1'){
			
			$facilities_id = $this->request->post['facilities_id'];
		
			$this->load->model('facilities/facilities');
			$facilities_info = $this->model_facilities_facilities->getfacilities($facilities_id);
			
			
			if($this->request->post['current_enroll_image1'] == '1'){
				
				$this->load->model('notes/facerekognition');
					
				$this->load->model('user/user');
				$user_info = $this->model_user_user->getUserByUsername($this->request->post['user_id']);
				
				$user_result = $this->model_user_user->getenroll_images($user_info['user_id']);
				
				if(!empty($user_result)){
					
					$imagedata = $this->model_notes_facerekognition->jsonuploadfacerekognitioncompare($this->request->files);
					
					if($imagedata['notes_file'] != null && $imagedata['notes_file'] != ""){
						
						if($facilities_info['allow_face_without_verified'] != '1'){
							$facematch = $this->model_notes_facerekognition->jsongetfacerekognitioncompare($user_result, $facilities_info['face_similar_percent'], $imagedata, $this->request->post['user_id']);			
							
							if ($facematch == '2') {
								$json['warning'] = 'Sorry i am having trouble in recognizing you. Lets try again!!';
							}
						}
						
					}else{
						$json['warning'] = $imagedata['warning_e'];
					}
					
					
				}else{
					$json['warning'] = 'Please contact your admin to enroll your picture!';
				}
				
			}
		}
		
		if($json['warning'] == null && $json['warning'] == ""){
			$data = array();
			
			if($this->request->post['signature'] != null && $this->request->post['signature'] != ""){
				$data['imgOutput'] = $this->request->post['signature'];
			}
			
			$data['highlighter_id'] = $this->request->post['highlighter_id'];
			$data['highlighter_value'] = $this->request->post['highlighter_value'];
			$data['notes_description'] = $this->request->post['notes_description'];
			$data['notes_pin'] = $this->request->post['notes_pin'];
			$data['user_id'] = $this->request->post['user_id'];
			
			$data['notetime'] = $this->request->post['notetime'];
			$data['text_color'] = $this->request->post['text_color'];
			$data['note_date'] = $this->request->post['note_date'];

			$data['notes_file'] = $this->request->post['notes_file'];
			$data['facilitytimezone'] = $this->request->post['facilitytimezone'];
			
			$data['keyword_file'] = $this->request->post['keyword_file'];
			$data['offline'] = $this->request->post['offline'];
			$data['emp_tag_id'] = $this->request->post['emp_tag_id'];
			$data['tags_id'] = $this->request->post['tags_id'];
			
			
			$data['date_added'] = $this->request->post['date_added'];
			$notes_file_url = $this->request->post['notes_file_url'];
			
			$data['notes_type'] = $this->request->post['notes_type'];
			$data['incidentform_id'] = $this->request->post['incidentform_id'];
			$data['checklist_id'] = $this->request->post['checklist_id'];
			$data['strike_note_type'] = $this->request->post['strike_note_type'];
			$data['formsids'] = $this->request->post['formsids'];
			
			$data['override_monitor_time_user_id_checkbox'] = $this->request->post['override_monitor_time_user_id_checkbox'];
			$data['override_monitor_time_user_id'] = $this->request->post['override_monitor_time_user_id'];
			$data['comments'] = $this->request->post['comments'];
			$data['monitor_time_1'] = '2';
			
			$data['phone_device_id'] = $this->request->post['phone_device_id'];
						
			if($this->request->post['is_android'] != null && $this->request->post['is_android'] != ""){
				$data['is_android'] = $this->request->post['is_android'];
			}else{
				$data['is_android'] = '1';
			}
			
			$facilities_id = $this->request->post['facilities_id'];
		
			$this->load->model('facilities/facilities');
			$facilities_info = $this->model_facilities_facilities->getfacilities($facilities_id);
			
			$data['config_multiple_activenote'] = $facilities_info['config_multiple_activenote'];
			
			
			if($this->request->post['clienttags_id'] == null && $this->request->post['clienttags_id'] == ""){
				$notes_id = $this->model_notes_notes->jsonaddnotes($data, $this->request->post['facilities_id']);
			}
			
			if($this->config->get('config_face_recognition') == '1'){
				if($this->request->post['face_notes_file'] != null && $this->request->post['face_notes_file'] != ""){
						
					$notes_file = $this->request->post['face_notes_file'];
					$outputFolder = $this->request->post['outputFolder'];
			
					require_once(DIR_SYSTEM . 'library/awsstorage/s3_config.php');						
					
					$this->model_notes_notes->updateuserpicture($s3file, $notes_id);
					$this->model_notes_notes->updateuserverified('1', $notes_id);
					
					unlink($outputFolder);
				}
				
				/*
				if($this->request->post['face_not_verify'] == "1"){
					$this->model_notes_notes->updateuserverified('2', $notes_id);
				}*/
				
				if($imagedata['notes_file'] != null && $imagedata['notes_file'] != ""){
				
					$notes_file = $imagedata['notes_file'];
					$outputFolder = $imagedata['outputFolder'];
			
					require_once(DIR_SYSTEM . 'library/awsstorage/s3_config.php');						
					
					$this->model_notes_notes->updateuserpicture($s3file, $notes_id);
					
					if ($facematch == '1') {
						$this->model_notes_notes->updateuserverified('1', $notes_id);
					}else{
						$this->model_notes_notes->updateuserverified('2', $notes_id);
					}
					
					unlink($outputFolder);
				}
			}
			
			if($this->request->post['clienttags_id'] != null && $this->request->post['clienttags_id'] != ""){
				
				$this->load->model('setting/tags');
				
				$data2 = array();
				$data2['tags_id'] = $this->request->post['clienttags_id'];
				$data2['facilities_id'] = $facilities_id;
				$data2['facilitytimezone'] = $this->request->post['facilitytimezone'];
				
				$data['comments'] = $this->request->post['notes_description'];
				
				$notes_id = $this->model_setting_tags->addclientsign($data, $data2);
			}
			
			 
			
			$audio_attach_url = $this->request->post['audio_attach_url'];
			
			
		
			$this->data['facilitiess'][] = array(
				'warning'  => '1',
				'notes_id'  => $notes_id,
				'notes_file_url'  => $notes_file_url,
				'audio_attach_url'  => $audio_attach_url,
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
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices jsonAddNotes',
			);
			$this->model_activity_activity->addActivity('app_jsonAddNotes', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		} 
	}
	
	
	public function jsonuploadFile(){
	
	try{
		$json = array();
		$this->data['facilitiess'] = array();
		
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
		
		if($this->request->files["upload_file"] != null && $this->request->files["upload_file"] != ""){

			$extension = end(explode(".", $this->request->files["upload_file"]["name"]));

			if($this->request->files["upload_file"]["size"] < 42214400){
				$neextension  = strtolower($extension);
				//if($neextension != 'mp4' && $neextension != 'mp3' && $neextension != 'flv' && $neextension != '3gp' && $neextension != 'wav' && $neextension != 'mkv' && $neextension != 'avi'){
					
					//$notes_file = uniqid( ) . "." . $extension;
					//$outputFolder = DIR_IMAGE.'files/' . $notes_file;
					//move_uploaded_file($this->request->files["upload_file"]["tmp_name"], $outputFolder);
					
					//$outputFolderUrl = HTTP_SERVER.'image/files/' . $notes_file;
					
					$notes_file = 'devbolb'.rand().'.'.$extension;
					$outputFolder = $this->request->files["upload_file"]["tmp_name"];
					//require_once(DIR_SYSTEM . 'library/awsstorage/s3_config.php');
					//$outputFolderUrl = 'https://dev1cdn.azureedge.net/'.$notes_file;
					
					
					//require_once(DIR_SYSTEM . 'library/azure_storage/config.php');
					
					
					if($this->config->get('enable_storage') == '1'){
						/* AWS */
						
						require_once(DIR_SYSTEM . 'library/awsstorage/s3_config.php');
						 $outputFolderUrl =  $s3file;
					}
					
					if($this->config->get('enable_storage') == '2'){
						/* AZURE */
						
						require_once(DIR_SYSTEM . 'library/azure_storage/config.php');					
						//uploadBlobSample($blobClient, $outputFolder, $notes_file);
						$s3file = AZURE_URL. $notes_file;
						 $outputFolderUrl =  $s3file;
					}
					
					if($this->config->get('enable_storage') == '3'){
						/* LOCAL */
						$outputFolder = DIR_IMAGE.'storage/' . $notes_file;
						move_uploaded_file($this->request->files["upload_file"]["tmp_name"], $outputFolder);
						$s3file = HTTPS_SERVER.'image/storage/' . $notes_file;
						 $outputFolderUrl =  $s3file;
					}
					
					
					
					$error = true;
					
					$this->data['facilitiess'][] = array(
						'success'  => '1',
						'notes_file'  => $outputFolderUrl,
						'notes_file_url'  => $outputFolderUrl,
						'notes_media_extention'  => $extension,
					);
				/*}else{
					$this->data['facilitiess'][] = array(
						'warning'  => 'video or audio file not valid!',
					);
					$error = false;
				}*/
			}else{
					$this->data['facilitiess'][] = array(
						'warning'  => 'Maximum size file upload!',
					);
					$error = false;
				}

		}else{
			$this->data['facilitiess'][] = array(
				'warning'  => 'Please select file!',
			);
			$error = false;
		}

		$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
		
		$this->response->setOutput(json_encode($value));
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices jsonuploadFile',
			);
			$this->model_activity_activity->addActivity('app_jsonuploadFile', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		} 
		
	}
	
	public function jsonupdateText(){
		
		try{
		
		$this->load->model('notes/notes');
		
		
		
		
		$json = array();
		$this->data['facilitiess'] = array();
		
		
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
		
		if ($this->request->post['notes_id'] != null && $this->request->post['type'] == 'text') {
		
		$facilities_timezone = $this->request->post['facilities_timezone'];
		date_default_timezone_set($facilities_timezone);
		$update_date = date('Y-m-d H:i:s', strtotime('now'));
		
		$this->model_notes_notes->updateNoteColor($this->request->post['notes_id'], $this->request->post['text_color'], $update_date);
		
		$error = true;
			
		$this->data['facilitiess'][] = array(
			'success'  => '1',
		);
		
		}else{
			$this->data['facilitiess'][] = array(
				'warning'  => 'Please select text!',
			);
			$error = false;
		}
		
			
		$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
		$this->response->setOutput(json_encode($value));
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices jsonupdateText',
			);
			$this->model_activity_activity->addActivity('app_jsonupdateText', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		} 

	}
	
	public function jsonupdateHighliter(){
	
	try{
		$this->load->model('notes/notes');
		
		$json = array();
		$this->data['facilitiess'] = array();
		
		
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
		
		if ($this->request->post['notes_id'] != null && $this->request->post['notes_id'] != "") {
			
			
			$facilities_timezone = $this->request->post['facilities_timezone'];
			date_default_timezone_set($facilities_timezone);
			$update_date = date('Y-m-d H:i:s', strtotime('now'));
				
			$this->model_notes_notes->updateNoteHigh($this->request->post['notes_id'], $this->request->post['highlighter_id'], $update_date);
			
			$error = true;
			
			$this->data['facilitiess'][] = array(
				'success'  => '1',
			);
			
		}else{
			$this->data['facilitiess'][] = array(
				'warning'  => 'Please select text!',
			);
			$error = false;
		}
			
		$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
		$this->response->setOutput(json_encode($value));
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices jsonupdateHighliter',
			);
			$this->model_activity_activity->addActivity('app_jsonupdateHighliter', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		} 

	}
	
	public function jsonUpdateStrike(){
		
		try{
			
		$this->data['facilitiess'] = array();
		
		$json = array();
		
		$this->load->model('notes/notes');
		
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
		
		if (!$this->request->post['notes_id']) {
			$json['warning'] = 'Please select notes id!.';
		}
		
		if($this->request->post['notes_pin'] != null && $this->request->post['notes_pin'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if (($this->request->post['notes_pin'] != $user_info['user_pin'])) {
				$json['warning'] = 'User Pin not valid!.';
			}
		}
		
		if($this->request->post['user_id'] != null && $this->request->post['user_id'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if (($user_info['status'] == '0')) {
				$json['warning'] = 'User not exit!';
			}
		}
			
		
		if($json['warning'] == null && $json['warning'] == ""){
			$data = array();
			
			if($this->request->post['signature'] != null && $this->request->post['signature'] != ""){
				$data['imgOutput'] = $this->request->post['signature'];
			}
			
			$data['notes_pin'] = $this->request->post['notes_pin'];
			$data['user_id'] = $this->request->post['user_id'];
			$data['notes_id'] = $this->request->post['notes_id'];
			
			$data['note_date'] = $this->request->post['note_date'];
			$data['facilitytimezone'] = $this->request->post['facilitytimezone'];
			$data['strike_note_type'] = $this->request->post['strike_note_type'];
			
			
			$this->model_notes_notes->jsonupdateStrikeNotes($data, $this->request->post['facilities_id']);
			
			$error = true;
			
			$this->data['facilitiess'][] = array(
				'success'  => '1',
			);
		}else{
			$this->data['facilitiess'][] = array(
				'warning'  => $json['warning'],
			);
			$error = false;
		}
		
		$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
		
		$this->response->setOutput(json_encode($value));
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices jsonUpdateStrike',
			);
			$this->model_activity_activity->addActivity('app_jsonUpdateStrike', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		} 
	}
	
	
	public function jsonAddreview(){
		
		try{
			
		$this->data['facilitiess'] = array();
		
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
		
		$json = array();
		
		$this->load->model('notes/notes');
		
		
		if($this->request->post['notes_pin'] != null && $this->request->post['notes_pin'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if (($this->request->post['notes_pin'] != $user_info['user_pin'])) {
				$json['warning'] = 'User Pin not valid!.';
			}
		}
		
		if($this->request->post['user_id'] != null && $this->request->post['user_id'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if (($user_info['status'] == '0')) {
				$json['warning'] = 'User not exit!';
			}
		}
			
		
		if($json['warning'] == null && $json['warning'] == ""){
			$data = array();
			
			if($this->request->post['signature'] != null && $this->request->post['signature'] != ""){
				$data['imgOutput'] = $this->request->post['signature'];
			}
			
			$data['notes_pin'] = $this->request->post['notes_pin'];
			$data['user_id'] = $this->request->post['user_id'];
			$data['note_date'] = $this->request->post['note_date'];
			$data['date_added'] = $this->request->post['date_added'];
			
			$data['facilitytimezone'] = $this->request->post['facilitytimezone'];
			$data['notes_type'] = $this->request->post['notes_type'];
			
			//$this->model_notes_notes->jsonaddreview($data, $this->request->post['facilities_id']);
			
				
			$timeZone = date_default_timezone_set($this->request->post['facilitytimezone']);
			
			$reviewdata = array();
			
			if ($this->request->post['fromdate'] != null && $this->request->post['fromdate'] != "") {
				//$reviewdate =  $this->request->post['fromdate'].' To '.date('m-d-Y');
				$reviewdate =  $this->request->post['fromdate'].' To '.date('m-d-Y');
				
				$reviewdate1 =  date('m-d-Y', strtotime($this->request->post['fromdate']));
				$reviewdate =  $reviewdate1.' To '.date('m-d-Y');
			}
		
			
			$reviewdata['keyword_file'] = CONFIG_REVIEW_NOTES;
			
			$this->load->model('setting/keywords');
			$keywordData2 = $this->model_setting_keywords->getkeywordDetaildesc($reviewdata['keyword_file']);
		
			$reviewdata['notes_description'] = $keywordData2['keyword_name'].' | '.$reviewdate .' '.$this->request->post['comments'];
			
			
			$reviewdata['note_date'] = date('Y-m-d H:i:s');
			$reviewdata['notetime'] = date('h:i A');
			
			
			
			$notes_id = $this->model_notes_notes->addnotes($reviewdata, $this->request->post['facilities_id']);
			$this->model_notes_notes->updatenotes($data, $this->request->post['facilities_id'], $notes_id );
			$this->model_notes_notes->updatereviewnotes($notes_id);
			
			
			//$this->model_notes_notes->jsonaddreview($data, $this->request->post['facilities_id']);
			
			$error = true;
			
			$this->data['facilitiess'][] = array(
				'success'  => '1',
			);
		}else{
			$this->data['facilitiess'][] = array(
				'warning'  => $json['warning'],
			);
			$error = false;
		}
		
		$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
		
		$this->response->setOutput(json_encode($value));
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices jsonAddreview',
			);
			$this->model_activity_activity->addActivity('app_jsonAddreview', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		} 
	}
	
	
	/*public function jsonAddreviewbyNoteID(){
		$this->data['facilitiess'] = array();
		
		$json = array();
		
		$this->load->model('notes/notes');
		
		
		if($this->request->post['notes_pin'] != null && $this->request->post['notes_pin'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if (($this->request->post['notes_pin'] != $user_info['user_pin'])) {
				$json['warning'] = 'User Pin not valid!.';
			}
		}
		
		if($this->request->post['user_id'] != null && $this->request->post['user_id'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if (($user_info['status'] == '0')) {
				$json['warning'] = 'User not exit!';
			}
		}
			
		
		if($json['warning'] == null && $json['warning'] == ""){
			$data = array();
			
			if($this->request->post['signature'] != null && $this->request->post['signature'] != ""){
				$data['imgOutput'] = $this->request->post['signature'];
			}
			
			$data['notes_pin'] = $this->request->post['notes_pin'];
			$data['user_id'] = $this->request->post['user_id'];
			$data['notes_id'] = $this->request->post['notes_id'];
			
			$this->model_notes_notes->jsonaddreviewbyID($data, $this->request->post['facilities_id']);
			
			$error = true;
			
			$this->data['facilitiess'][] = array(
				'success'  => '1',
			);
		}else{
			$this->data['facilitiess'][] = array(
				'warning'  => $json['warning'],
			);
			$error = false;
		}
		
		$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
		
		$this->response->setOutput(json_encode($value));
	}*/

	
	
	
	public function jsongetreviews(){ 
	
	try{
		
		$this->data['facilitiess'] = array();
		
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
		
		$this->language->load('notes/notes');
		$this->load->model('setting/image'); 
		
		if($this->request->post['facilities_id'] != null && $this->request->post['facilities_id'] != ""){
			
			$this->load->model('notes/notes');
			
			$this->load->model('setting/highlighter');
			$this->load->model('user/user');
			
			$this->load->model('facilities/facilities');
			
			
			if ($this->request->post['searchdate'] != null && $this->request->post['searchdate'] != "") {
				$searchdate = date('Y-m-d', strtotime($this->request->post['searchdate']));
			} 

			$data = array(
				'facilities_id' => $this->request->post['facilities_id'],
				'searchdate' => $searchdate,
			);
			
			$highlighters = $this->model_notes_notes->jsongetReviewModel($data);
			
			foreach ($highlighters as $highlighter) {
				
				if($highlighter['date_added'] != null && $highlighter['date_added'] != "0000-00-00 00:00:00"){
						$date_added = date($this->language->get('date_format_short'), strtotime($highlighter['date_added']));
					}else{
						$date_added = '';
					}
					
				if($highlighter['note_date'] != null && $highlighter['note_date'] != "0000-00-00 00:00:00"){
					$reviewnote_date = date($this->language->get('date_format_short'), strtotime($highlighter['note_date']));
				}else{
					$reviewnote_date = '';
				}
				
						
				if($highlighter['signature'] != null && $highlighter['signature'] != ""){
						$strike_signature = $highlighter['signature'];
					}else{
						$strike_signature = '';
					}
					
				if($highlighter['notes_pin'] != null && $highlighter['notes_pin'] != ""){
					$notesPin = '1';
				}else{
					$notesPin = '';
				}
				
				$this->data['facilitiess'][] = array(
					'username'      => $highlighter['user_id'],
					'notes_pin'   => $notesPin,
					'reviewnote_date'   => $reviewnote_date,
					'date_added' => $date_added,
					'signature'   => $strike_signature,
				);
			}
			$error = true;
			
		}else{
			$this->data['facilitiess'][] = array(
				'results'  => "No reviews Found",
			);
			$error = false;
			
		}
		$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
		
		$this->response->setOutput(json_encode($value));
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices jsongetreviews',
			);
			$this->model_activity_activity->addActivity('app_jsongetreviews', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		} 
	
	}
	
	public function jsonAddReminder(){
		
		try{
		$this->data['facilitiess'] = array();
		
		
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
		
		$json = array();
		
		$this->load->model('notes/notes');
		
		if($this->request->post['notes_id'] == null && $this->request->post['notes_id'] == ""){
			$json['warning'] = 'Note id is required!.';
		}
		if($this->request->post['reminder_time'] == null && $this->request->post['reminder_time'] == ""){
			$json['warning'] = 'Reminder is required!.';
		}
			
		
		if($json['warning'] == null && $json['warning'] == ""){
			$data = array();
			
			$data['notes_id'] = $this->request->post['notes_id'];
			$data['reminder_time'] = $this->request->post['reminder_time'];
			$data['date_added'] = $this->request->post['date_added'];
			
			$this->model_notes_notes->jsonaddReminder($data, $this->request->post['facilities_id']);
			
			$error = true;
			
			$this->data['facilitiess'][] = array(
				'success'  => '1',
			);
		}else{
			$this->data['facilitiess'][] = array(
				'warning'  => $json['warning'],
			);
			$error = false;
		}
		
		$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
		
		$this->response->setOutput(json_encode($value));
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices jsonAddReminder',
			);
			$this->model_activity_activity->addActivity('app_jsonAddReminder', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		} 
	}
	
	
	public function jsondeleteReminder(){
		try{
		$this->data['facilitiess'] = array();
		
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
		
		$json = array();
		
		$this->load->model('notes/notes');
		
		if($this->request->post['notes_id'] == null && $this->request->post['notes_id'] == ""){
			$json['warning'] = 'Note id is required!.';
		}
		
		if($json['warning'] == null && $json['warning'] == ""){
			$data = array();
			
			$data['notes_id'] = $this->request->post['notes_id'];
			$data['facilities_id'] = $this->request->post['facilities_id'];
			
			$this->model_notes_notes->jsonDeleteReminder($data);
			
			$error = true;
			
			$this->data['facilitiess'][] = array(
				'success'  => '1',
			);
		}else{
			$this->data['facilitiess'][] = array(
				'warning'  => $json['warning'],
			);
			$error = false;
		}
		
		$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
		
		$this->response->setOutput(json_encode($value));
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices jsondeleteReminder',
			);
			$this->model_activity_activity->addActivity('app_jsondeleteReminder', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		} 
	}
	
	
	public function jsonKeywords(){
		try{
		$this->data['facilitiess'] = array();
		$this->load->model('setting/keywords');
		$this->load->model('setting/image'); 
		
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
		
		$data3 = array(
			'facilities_id' => $this->request->post['facilities_id'],
		);
		
		$results = $this->model_setting_keywords->getkeywords($data3);
    	
		foreach ($results as $result) {
			
			if($result['keyword_image'] != null && $result['keyword_image'] != ""){
				$file1 = '/icon/'.$result['keyword_image'];
				$newfile4 = $this->model_setting_image->resize($file1, 70, 70);
				$newfile21 = DIR_IMAGE . $newfile4;
				$file12 = HTTP_SERVER . 'image/icon/'.$newfile4;
						
				$imageData1 = base64_encode(file_get_contents($newfile21));
				$strike_signature = 'data:'.$this->mime_content_type($file12).';base64,'.$imageData1;
			}else{
				$strike_signature = '';
			}
			
			if($result['monitor_time_image'] != null && $result['monitor_time_image'] != ""){
				$file111 = '/icon/'.$result['monitor_time_image'];
				$newfile4222 = $this->model_setting_image->resize($file111, 70, 70);
				$newfile2ww1 = DIR_IMAGE . $newfile4222;
				$file1ddd2 = HTTP_SERVER . 'image/icon/'.$newfile4222;
						
				$imageDatasss1 = base64_encode(file_get_contents($newfile2ww1));
				$monitor_time_image = 'data:'.$this->mime_content_type($file1ddd2).';base64,'.$imageDatasss1;
			}else{
				$monitor_time_image = '';
			}
			
      		$this->data['facilitiess'][] = array(
				'keyword_id'    => $result['keyword_id'],
				'active_tag'    => $result['active_tag'],
				'keyword_name'   => $result['keyword_name'],
				'keyword_image'   => $result['keyword_image'],
				'img_icon'   => $strike_signature,
				'relation_keyword_id'   => $result['relation_keyword_id'],
				'sort_order'   => $result['sort_order'],
				'monitor_time'   => $result['monitor_time'],
				'end_relation_keyword'   => $result['end_relation_keyword'],
				'relation_hastag'   => $result['relation_hastag'],
				'monitor_time_image'   => $result['monitor_time_image'],
				'monitor_time_image'   => $monitor_time_image,
			);
		}
		
		$value = array('results'=>$this->data['facilitiess'],'status'=>true);
		/*echo json_encode($value);*/
		$this->response->setOutput(json_encode($value));
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices jsonKeywords',
			);
			$this->model_activity_activity->addActivity('app_jsonKeywords', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		} 
	
	}
	
	public function arrayInString( $inArray , $inString ){
	  if( is_array( $inArray ) ){
		foreach( $inArray as $e ){
		  if( strpos( $inString , $e )!==false )
			return $e;
		}
		return "";
	  }else{
		return ( strpos( $inString , $inArray )!==false );
	  }
	}
	
	
	public function jsongetdefault(){
		try{
			
			
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
			
		if($this->config->get('config_default_sign') != null && $this->config->get('config_default_sign') != ""){
			$config_default_sign = $this->config->get('config_default_sign');
		}else{
			$config_default_sign = '2';
		}
		$this->data['facilitiess'] = array();
		$this->data['facilitiess'][] = array(
				'config_default_sign'  => $config_default_sign,
		);
		
		$value = array('results'=>$this->data['facilitiess'],'status'=>true);
			
		$this->response->setOutput(json_encode($value));
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices jsongetdefault',
			);
			$this->model_activity_activity->addActivity('app_jsongetdefault', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		} 
	}

	public function jsonGetActiveNoteDetail(){
		
		try{
		$this->data['facilitiess'] = array();
		
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
		
		$this->load->model('setting/keywords');
		$this->load->model('setting/image');
		
		$keyword_id = $this->request->post['keyword_id'];
		$keyword_info = $this->model_setting_keywords->getkeywordDetail($keyword_id);
		
		if($keyword_info != null && $keyword_info != ""){
			$error = true;
			
			if($keyword_info['keyword_image'] != null && $keyword_info['keyword_image'] != ""){
				$file1 = '/icon/'.$keyword_info['keyword_image'];
				$newfile4 = $this->model_setting_image->resize($file1, 70, 70);
				$newfile21 = DIR_IMAGE . $newfile4;
				$file12 = HTTP_SERVER . 'image/icon/'.$newfile4;
						
				$imageData1 = base64_encode(file_get_contents($newfile21));
				$strike_signature = 'data:'.$this->mime_content_type($file12).';base64,'.$imageData1;
			}else{
				$strike_signature = '';
			}
			
			$this->data['facilitiess'][] = array(
				'img_icon'   => $strike_signature,
			);
			
		}else{
			$this->data['facilitiess'][] = array(
				'warning'  => 'Not valid id',
			);
			$error = false;
		}
		
		$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
		
		$this->response->setOutput(json_encode($value));
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices jsonGetActiveNoteDetail',
			);
			$this->model_activity_activity->addActivity('app_jsonGetActiveNoteDetail', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		} 
		
	}
	
	public function jsonupdateFile(){
		
		try{
		$this->load->model('notes/notes');
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
		
		if($this->request->files["upload_file"] != null && $this->request->files["upload_file"] != ""){

			if($this->request->post['notes_id'] != null && $this->request->post['notes_id'] != ""){
				$extension = end(explode(".", $this->request->files["upload_file"]["name"]));

				
				if($this->request->files["upload_file"]["size"] < 42214400){
				$neextension  = strtolower($extension);
				//if($neextension != 'mp4' && $neextension != 'mp3' && $neextension != 'flv' && $neextension != '3gp' && $neextension != 'wav' && $neextension != 'mkv' && $neextension != 'avi'){

					/*$notes_file = uniqid( ) . "." . $extension;
					$outputFolder = DIR_IMAGE.'files/' . $notes_file;
					move_uploaded_file($this->request->files["upload_file"]["tmp_name"], $outputFolder);
					
					*/
					
					$notes_file = 'devbolb'.rand().'.'.$extension;
					$outputFolder = $this->request->files["upload_file"]["tmp_name"];
						
					//require_once(DIR_SYSTEM . 'library/azure_storage/config.php');
					
					//require_once(DIR_SYSTEM . 'library/awsstorage/s3_config.php');
					
					if($this->config->get('enable_storage') == '1'){
						/* AWS */
						
						require_once(DIR_SYSTEM . 'library/awsstorage/s3_config.php');
					}
					
					if($this->config->get('enable_storage') == '2'){
						/* AZURE */
						
						require_once(DIR_SYSTEM . 'library/azure_storage/config.php');					
						//uploadBlobSample($blobClient, $outputFolder, $notes_file);
						$s3file = AZURE_URL. $notes_file;
					}
					
					if($this->config->get('enable_storage') == '3'){
						/* LOCAL */
						$outputFolder = DIR_IMAGE.'storage/' . $notes_file;
						move_uploaded_file($this->request->files["upload_file"]["tmp_name"], $outputFolder);
						$s3file = HTTPS_SERVER.'image/storage/' . $notes_file;
					}
					
					
					$notes_media_extention = $extension;
					$notes_file_url = $s3file;
					
					
					$formData = array();
					$formData['media_user_id'] = $this->request->post['media_user_id'];
					$formData['media_signature'] = $this->request->post['media_signature'];
					$formData['media_pin'] = $this->request->post['media_pin'];
					$formData['notes_type'] = $this->request->post['notes_type'];
					
					$note_info = $this->model_notes_notes->getNote($this->request->post['notes_id']);
					$formData['facilities_id'] = $note_info['facilities_id'];
						
					date_default_timezone_set($this->request->post['facilitytimezone']);
					$formData['noteDate'] = date('Y-m-d H:i:s', strtotime('now'));

					$this->model_notes_notes->updateNoteFile($this->request->post['notes_id'], $notes_file_url, $notes_media_extention, $formData);
					
					
					date_default_timezone_set($this->request->post['facilitytimezone']);
					$update_date = date('Y-m-d H:i:s', strtotime('now'));
					
					$sql = "UPDATE `" . DB_PREFIX . "notes` SET update_date = '".$update_date."', notes_conut='0' WHERE notes_id = '" . (int)$this->request->post['notes_id'] . "' ";
					$this->db->query($sql);
					
					$error = true;
					
					$this->data['facilitiess'][] = array(
						'success'   => '1',
					);
				
				/*}else{
					$this->data['facilitiess'][] = array(
						'warning'  => 'video or audio file not valid!',
					);
					$error = false;
				}*/
				}else{
					$this->data['facilitiess'][] = array(
						'warning'  => 'Maximum size file upload!',
					);
					$error = false;
				}

			}else{
				$this->data['facilitiess'][] = array(
				'warning'  => 'Note not update please update again',
			);
				$error = false;
			}
		}else{
			$this->data['facilitiess'][] = array(
				'warning'  => 'Please select file!',
			);
			$error = false;
		}

		
		$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
		$this->response->setOutput(json_encode($value));
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices jsonupdateFile',
			);
			$this->model_activity_activity->addActivity('app_jsonupdateFile', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		} 
		
	}
	
	
	public function jsonupdateFile2(){
		
		try{
		$this->load->model('notes/notes');
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
		
		if($this->request->files["audio_upload_file"] != null && $this->request->files["audio_upload_file"] != ""){

			if($this->request->post['notes_id'] != null && $this->request->post['notes_id'] != ""){
				$extension = end(explode(".", $this->request->files["audio_upload_file"]["name"]));

				
				if($this->request->files["audio_upload_file"]["size"] < 42214400){
				$neextension  = strtolower($extension);
				//if($neextension != 'mp4' && $neextension != 'mp3' && $neextension != 'flv' && $neextension != '3gp' && $neextension != 'wav' && $neextension != 'mkv' && $neextension != 'avi'){

					/*$notes_file = uniqid( ) . "." . $extension;
					$outputFolder = DIR_IMAGE.'files/' . $notes_file;
					move_uploaded_file($this->request->files["upload_file"]["tmp_name"], $outputFolder);
					
					*/
					
					$notes_file = 'devbolb'.rand().'.'.$extension;
					$outputFolder = $this->request->files["audio_upload_file"]["tmp_name"];
						
					//require_once(DIR_SYSTEM . 'library/azure_storage/config.php');
					
					//require_once(DIR_SYSTEM . 'library/awsstorage/s3_config.php');
					
					if($this->config->get('enable_storage') == '1'){
						/* AWS */
						
						require_once(DIR_SYSTEM . 'library/awsstorage/s3_config.php');
					}
					
					if($this->config->get('enable_storage') == '2'){
						/* AZURE */
						
						require_once(DIR_SYSTEM . 'library/azure_storage/config.php');					
						//uploadBlobSample($blobClient, $outputFolder, $notes_file);
						$s3file = AZURE_URL. $notes_file;
					}
					
					if($this->config->get('enable_storage') == '3'){
						/* LOCAL */
						$outputFolder = DIR_IMAGE.'storage/' . $notes_file;
						move_uploaded_file($this->request->files["upload_file"]["tmp_name"], $outputFolder);
						$s3file = HTTPS_SERVER.'image/storage/' . $notes_file;
					}
					
					
					if($this->config->get('config_transcription') == '1'){
					
						$filePath = DIR_IMAGE.'audio/'.$notes_file;
						move_uploaded_file($this->request->files["audio_upload_file"]["tmp_name"], $filePath);
					
						$notes_media_extention = $extension;
						$audio_file_url = $s3file;
						
						$formData = array();
						$formData['media_user_id'] = $this->request->post['media_user_id'];
						$formData['media_signature'] = $this->request->post['media_signature'];
						$formData['media_pin'] = $this->request->post['media_pin'];
						$formData['notes_type'] = $this->request->post['notes_type'];
						$formData['audio_upload_file'] = $notes_file;
						$formData['audio_attach_type'] = '1';
							
						date_default_timezone_set($this->request->post['facilitytimezone']);
						$formData['noteDate'] = date('Y-m-d H:i:s', strtotime('now'));

						$this->model_notes_notes->updateNoteaudioFile($this->request->post['notes_id'], $audio_file_url, $notes_media_extention, $formData);
					
					}else{
						$notes_media_extention = $extension;
						$audio_file_url = $s3file;
						
						$formData = array();
						$formData['media_user_id'] = $this->request->post['media_user_id'];
						$formData['media_signature'] = $this->request->post['media_signature'];
						$formData['media_pin'] = $this->request->post['media_pin'];
						$formData['notes_type'] = $this->request->post['notes_type'];
						$formData['audio_upload_file'] = $notes_file;
						$formData['audio_attach_type'] = '0';
							
						date_default_timezone_set($this->request->post['facilitytimezone']);
						$formData['noteDate'] = date('Y-m-d H:i:s', strtotime('now'));
						

						$notes_media_id = $this->model_notes_notes->updateNoteaudioFile($this->request->post['notes_id'], '', $notes_media_extention, $formData);
						
						
						$sql5 = "UPDATE `" . DB_PREFIX . "notes_media` SET notes_file = '".$audio_file_url."' WHERE notes_media_id = '" . (int)$notes_media_id . "' ";
						$this->db->query($sql5);
						
					
					}
					
					date_default_timezone_set($this->request->post['facilitytimezone']);
					$noteDate1 = date('Y-m-d H:i:s', strtotime('now'));
					
					$sql = "UPDATE `" . DB_PREFIX . "notes` SET update_date = '".$noteDate1."', notes_conut='0' WHERE notes_id = '" . (int)$this->request->post['notes_id'] . "' ";
					$this->db->query($sql);
					
					$error = true;
					
					$this->data['facilitiess'][] = array(
						'success'   => '1',
					);
				
				/*}else{
					$this->data['facilitiess'][] = array(
						'warning'  => 'video or audio file not valid!',
					);
					$error = false;
				}*/
				}else{
					$this->data['facilitiess'][] = array(
						'warning'  => 'Maximum size file upload!',
					);
					$error = false;
				}

			}else{
				$this->data['facilitiess'][] = array(
				'warning'  => 'Note not update please update again',
			);
				$error = false;
			}
		}else{
			$this->data['facilitiess'][] = array(
				'warning'  => 'Please select file!',
			);
			$error = false;
		}

		
		$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
		$this->response->setOutput(json_encode($value));
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices jsonupdate audio File',
			);
			$this->model_activity_activity->addActivity('app_jsonupdateaudioFile', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		} 
		
	}
	
	
	
	
	public function jsongetNotesByPage() {
	
	try{	
		
		$this->data['facilitiess'] = array();
		$this->language->load('notes/notes');
		$this->load->model('notes/notes');
		$this->load->model('setting/image'); 
		$this->load->model('notes/image'); 
		
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
		
		
		if (isset($this->request->post['keyword'])) {
      		$keyword = $this->request->post['keyword'];
    	} 
		
		if (isset($this->request->post['facilities_id'])) {
      		$facilities_id = $this->request->post['facilities_id'];
    	} 
		
		if (isset($this->request->post['user_id'])) {
      		$user_id = $this->request->post['user_id'];
    	} 
		
		if($this->request->post['note_date_from'] != null && $this->request->post['note_date_from'] != ""){
			$note_date_from = date('Y-m-d', strtotime($this->request->post['note_date_from']));
		}
		if($this->request->post['note_date_to'] != null && $this->request->post['note_date_to'] != ""){
			$note_date_to = date('Y-m-d', strtotime($this->request->post['note_date_to']));
		}
		
		
		if ($this->request->post['searchdate'] != null && $this->request->post['searchdate'] != "") {
			$this->data['note_date'] = $this->request->post['searchdate'];
			$searchdate = date('Y-m-d', strtotime($this->request->post['searchdate']));
		} else {
			$this->data['note_date'] =  date('d-m-Y');
		}
		
		if (isset($this->request->post['advance_search'])) {
      		$advance_search = $this->request->post['advance_search'];
    	} 

		
		if (isset($this->request->post['page'])) {
			$page = $this->request->post['page'];
		} else {
			$page = 1;
		}
	
		
		$config_admin_limit1 = $this->config->get('config_android_front_limit');
		if($config_admin_limit1 != null && $config_admin_limit1 != ""){
			$config_admin_limit = $config_admin_limit1;
		}else{
			$config_admin_limit = "25";
		}
		
		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'facilities_id' => $facilities_id,
			'searchdate' => $searchdate,
			'note_date_from' => $note_date_from,
			'note_date_to' => $note_date_to,
			'keyword' => $keyword,
			'user_id' => $user_id,
			'advance_search' => $advance_search,
			//'start' => ($page - 1) * $config_admin_limit,
			//'limit' => $config_admin_limit
		);
		
		$results = $this->model_notes_notes->getnotess($data);
		
		$notes_total = $this->model_notes_notes->getTotalnotess($data);
		
		$this->load->model('setting/highlighter');
		$this->load->model('user/user');
		$this->load->model('setting/keywords');
		
		$keywords = $this->model_setting_keywords->getkeywords();
		$keyarray = array();
		foreach($keywords as $keyword){
			$keyarray[] = $keyword['keyword_name'];
		}
    	
		if($results != null && $results != ""){
			foreach ($results as $result) {
			
				$highlighterData = $this->model_setting_highlighter->gethighlighter($result['highlighter_id']);
				$user_info = $this->model_user_user->getUser($result['user_id']);
				$strikeuser_info = $this->model_user_user->getUser($result['strike_user_id']);
				
				
				
				if($highlighterData['highlighter_value'] != null && $highlighterData['highlighter_value'] != ""){
					$highlighter_value = $highlighterData['highlighter_value'];
				}else{
					$highlighter_value = '';
				}
				
				if($strikeuser_info['username'] != null && $strikeuser_info['username'] != ""){
					$strikeusername = $strikeuser_info['username'];
				}else{
					$strikeusername = '';
				}
				
				if($result['strike_date_added'] != null && $result['strike_date_added'] != "0000-00-00 00:00:00"){
					$strikeDate = date($this->language->get('date_format_short'), strtotime($result['strike_date_added']));
				}else{
					$strikeDate = '';
				}
				
				
				if($result['notes_pin'] != null && $result['notes_pin'] != ""){
					$file = '/key.gif';
					
					$newfile = $this->model_setting_image->resize($file, 300, 55);
					$newfile2 = DIR_IMAGE . $newfile;
					$file1 = HTTP_SERVER . 'image/'.$newfile;
					
					$imageData = base64_encode(file_get_contents($newfile2));
					$signaturesrc = '';
					
				}else				
				if($result['signature'] != null && $result['signature'] != ""){
					
					//$file = DIR_IMAGE . '/signature/'.$result['signature_image'];
					$file = '/signature/'.$result['signature_image'];
					
					$newfile = $this->model_setting_image->resize($file, 300, 55);
					$newfile2 = DIR_IMAGE . $newfile;
					$file1 = HTTP_SERVER . 'image/signature/'.$newfile;
					
					$imageData = base64_encode(file_get_contents($newfile2));
					$signaturesrc = 'data:'.$this->mime_content_type($file1).';base64,'.$imageData;
					
					/*$signature = $result['signature'];*/
				}else{
					$signaturesrc = '';
				}
				
				
				if($result['strike_pin'] != null && $result['strike_pin'] != ""){
					$file13 = '/key.gif';
					
					$newfile4 = $this->model_setting_image->resize($file13, 300, 55);
					$newfile21 = DIR_IMAGE . $newfile4;
					$file12 = HTTP_SERVER . 'image/signature/'.$newfile4;
					
					$imageData1 = base64_encode(file_get_contents($newfile21));
					$strike_signature = '';
				}else
				if($result['strike_signature'] != null && $result['strike_signature'] != ""){
					
					$file13 = '/signature/'.$result['strike_signature_image'];
					
					$newfile4 = $this->model_setting_image->resize($file13, 300, 55);
					$newfile21 = DIR_IMAGE . $newfile4;
					$file12 = HTTP_SERVER . 'image/signature/'.$newfile4;
					
					$imageData1 = base64_encode(file_get_contents($newfile21));
					$strike_signature = 'data:'.$this->mime_content_type($file12).';base64,'.$imageData1;
					/*$strikesignature = $result['strike_signature'];*/
				}else{
					$strike_signature = '';
				}
				
				if($result['strike_pin'] != null && $result['strike_pin'] != ""){
					$strikePin = $result['strike_pin'];
				}else{
					$strikePin = '';
				}
				
				if($result['notes_pin'] != null && $result['notes_pin'] != ""){
					$notesPin = $result['notes_pin'];
				}else{
					$notesPin = '';
				}
				
				
				if ($result['notes_file'] && file_exists(DIR_IMAGE . 'icon/'.$result['notes_file'])) {
					
					$file16 = '/icon/'.$result['notes_file'];
					$newfile84 = $this->model_setting_image->resize($file16, 30, 30);
					$newfile216 = DIR_IMAGE . $newfile84;
					$file124 = HTTP_SERVER . 'image/icon/'.$newfile84;
							
					$imageData132 = base64_encode(file_get_contents($newfile216));
					$keyword_icon = 'data:'.$this->mime_content_type($file124).';base64,'.$imageData132;
						
				} else{
					$keyword_icon = '';
				}
					
				
				if($result['notes_file'] != null && $result['notes_file'] != ""){
					$outputFolderUrl = HTTP_SERVER.'image/files/' . $result['notes_file'];
					$keyImageSrc = 'img';
				}else{
					$outputFolderUrl = "";
					$keyImageSrc = "";
				}
				
				$reminder_info = $this->model_notes_notes->getReminder($result['notes_id']);
			
				$reminder_time = $reminder_info['reminder_time'];
				$reminder_title = $reminder_info['reminder_title'];
				
				if($reminder_time != null && $reminder_time != ""){
					$reminderTime = $reminder_time;
				}else{
					$reminderTime = "";
				}
				if($reminder_title != null && $reminder_title != ""){
					$reminderTitle = $reminder_title;
				}else{
					$reminderTitle = "";
				}
				
				if($result['task_time'] != null && $result['task_time'] != "00:00:00"){ 
					$notes_description = date('h:i A', strtotime($result['task_time'])) .'&nbsp;'.$result['notes_description'];
				}else{
					$notes_description = $result['notes_description'];
				}
				
				$this->data['facilitiess'][] = array(
					'notes_id'    => $result['notes_id'],
					'taskadded'    => $result['taskadded'],
					'highlighter_value'   => $highlighter_value,
					'notes_description'   =>$notes_description,
					'attachment_icon'   => $keyImageSrc,
					'attachment_url'   => $outputFolderUrl,
					'keyword_icon'   => $keyword_icon,
					'notetime'   => $result['notetime'],
					'username'      => $user_info['username'],
					'signature'   => $signaturesrc,
					'notes_pin'   => $notesPin,
					
					'text_color_cut'   => $result['text_color_cut'],
					'text_color'   => $result['text_color'],
					'note_date'   => date($this->language->get('date_format_short'), strtotime($result['note_date'])),
					'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'strike_user_name'   => $strikeusername,
					'strike_signature'   => $strike_signature,
					'strike_date_added'   => $strikeDate,
					'strike_pin'   => $strikePin,
					'reminder_title'   => $reminderTitle,
					'reminder_time'   => $reminderTime,
					
				); 
			}
			$error = true;
		}else{
			$this->data['facilitiess'] = array();
			$error = true;
			
		}
		$value = array('results'=>$this->data['facilitiess'],'status'=>$error,'total_note'=>$notes_total);
		/*echo json_encode($value);*/
		$this->response->setOutput(json_encode($value));
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices jsongetNotesByPage',
			);
			$this->model_activity_activity->addActivity('app_jsongetNotesByPage', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		} 
		
	}
	
	public function imageResize($imageUrl){
		try{
		
		$img = imagecreatefrompng("http://www.servitium.com/digitalnotebook/image/cache/signature/56e9226765028.png");

		//find the size of the borders
		$b_top = 0;
		$b_btm = 0;
		$b_lft = 0;
		$b_rt = 0;

		//top
		for(; $b_top < imagesy($img); ++$b_top) {
		  for($x = 0; $x < imagesx($img); ++$x) {
			if(imagecolorat($img, $x, $b_top) != 0xFFFFFF) {
			   break 2; //out of the 'top' loop
			}
		  }
		}

		//bottom
		for(; $b_btm < imagesy($img); ++$b_btm) {
		  for($x = 0; $x < imagesx($img); ++$x) {
			if(imagecolorat($img, $x, imagesy($img) - $b_btm-1) != 0xFFFFFF) {
			   break 2; //out of the 'bottom' loop
			}
		  }
		}

		//left
		for(; $b_lft < imagesx($img); ++$b_lft) {
		  for($y = 0; $y < imagesy($img); ++$y) {
			if(imagecolorat($img, $b_lft, $y) != 0xFFFFFF) {
			   break 2; //out of the 'left' loop
			}
		  }
		}

		//right
		for(; $b_rt < imagesx($img); ++$b_rt) {
		  for($y = 0; $y < imagesy($img); ++$y) {
			if(imagecolorat($img, imagesx($img) - $b_rt-1, $y) != 0xFFFFFF) {
			   break 2; //out of the 'right' loop
			}
		  }
		}

		//copy the contents, excluding the border
		$newimg = imagecreatetruecolor(
			imagesx($img)-($b_lft+$b_rt), imagesy($img)-($b_top+$b_btm));

		imagecopy($newimg, $img, 0, 0, $b_lft, $b_top, imagesx($newimg), imagesy($newimg));

		//finally, output the image
		header("Content-Type: image/png");
		return imagejpeg($newimg);
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices imageResize',
			);
			$this->model_activity_activity->addActivity('app_imageResize', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		} 
	}
	
	
	public function createThumbnail($pathToImage, $thumbWidth = 180) {
    
	try{
    if (is_file($pathToImage)) {
        $info = pathinfo($pathToImage);

        $extension = strtolower($info['extension']);
        if (in_array($extension, array('jpg', 'jpeg', 'png', 'gif'))) {

            switch ($extension) {
                case 'jpg':
                    $img = imagecreatefromjpeg("{$pathToImage}");
                    break;
                case 'jpeg':
                    $img = imagecreatefromjpeg("{$pathToImage}");
                    break;
                case 'png':
                    $img = imagecreatefrompng("{$pathToImage}");
                    break;
                case 'gif':
                    $img = imagecreatefromgif("{$pathToImage}");
                    break;
                default:
                    $img = imagecreatefromjpeg("{$pathToImage}");
            }
            // load image and get image size

            $width = imagesx($img);
            $height = imagesy($img);

            // calculate thumbnail size
            $new_width = $thumbWidth;
            $new_height = floor($height * ( $thumbWidth / $width ));

            // create a new temporary image
            $tmp_img = imagecreatetruecolor($new_width, $new_height);

            // copy and resize old image into new image
            imagecopyresized($tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                $pathToImage = $pathToImage . '.thumb.' . $extension;
            // save thumbnail into a file
            imagejpeg($tmp_img, "{$pathToImage}");
            $result = $pathToImage;
        } else {
            $result = 'Failed|Not an accepted image type (JPG, PNG, GIF).';
        }
    } else {
        $result = 'Failed|Image file does not exist.';
    }
    return $result;
	
	}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices createThumbnail',
			);
			$this->model_activity_activity->addActivity('app_createThumbnail', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		} 
}

	public function jsongetNotesByApp() { 
	try{
		$this->data['facilitiess'] = array();
		$this->language->load('notes/notes');
		$this->load->model('notes/notes');
		$this->load->model('setting/image'); 
		$this->load->model('notes/image'); 
		
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
		
		
		if (isset($this->request->post['keyword'])) {
      		$keyword = $this->request->post['keyword'];
    	} 
		
		if (isset($this->request->post['facilities_id'])) {
      		$facilities_id = $this->request->post['facilities_id'];
    	} 
		
		if (isset($this->request->post['user_id'])) {
      		$user_id = $this->request->post['user_id'];
    	} 
		if (isset($this->request->post['tasktype'])) {
      		$tasktype = $this->request->post['tasktype'];
    	} 
		
		if($this->request->post['note_date_from'] != null && $this->request->post['note_date_from'] != ""){
			$note_date_from = date('Y-m-d', strtotime($this->request->post['note_date_from']));
		}
		if($this->request->post['note_date_to'] != null && $this->request->post['note_date_to'] != ""){
			$note_date_to = date('Y-m-d', strtotime($this->request->post['note_date_to']));
		}
		
		
		if ($this->request->post['searchdate'] != null && $this->request->post['searchdate'] != "") {
			$this->data['note_date'] = $this->request->post['searchdate'];
				$date = str_replace('-', '/', $this->request->post['searchdate']);
				$res = explode("/", $date);
				$changedDate = $res[0]."-".$res[1]."-".$res[2];
			
			$searchdate = $changedDate; //date('Y-m-d', strtotime($this->request->post['searchdate']));
		} else {
			$this->data['note_date'] =  date('d-m-Y');
		}
		
		if (isset($this->request->post['advance_search'])) {
      		$advance_search = $this->request->post['advance_search'];
    	}

		if (isset($this->request->post['page'])) {
			$page = $this->request->post['page'];
		} else {
			$page = 1;
		}
	
		$config_admin_limit1 = $this->config->get('config_android_front_limit');
		if($config_admin_limit1 != null && $config_admin_limit1 != ""){
			$config_admin_limit = $config_admin_limit1;
		}else{
			$config_admin_limit = "25";
		}
		
		if (isset($this->request->post['sync'])) {
      		$sync_data = $this->request->post['sync'];
    	}
		if (isset($this->request->post['notetime'])) {
      		$notetime = $this->request->post['notetime'];
    	}

		
		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'facilities_id' => $facilities_id,
			'facilities_timezone' => $this->request->post['facilities_timezone'],
			//'current_date' => $this->request->post['current_date'],
			'notetime' => $notetime,
			'sync_data' => $sync_data,
			'searchdate' => $searchdate,
			'note_date_from' => $note_date_from,
			'note_date_to' => $note_date_to,
			'keyword' => $keyword,
			'tasktype' => $tasktype,
			'user_id' => $user_id,
			'advance_search' => $advance_search,
			'start' => ($page - 1) * $config_admin_limit,
			'limit' => $config_admin_limit
		);
		
		
		//var_dump($data);
		
		$results = $this->model_notes_notes->getnotess($data);
		
		$notes_total = $this->model_notes_notes->getTotalnotess($data);
		
		$this->load->model('setting/highlighter');
		$this->load->model('user/user');
		$this->load->model('setting/keywords');
		$this->load->model('notes/tags');
		
		$this->load->model('setting/tags');
		
		if($results != null && $results != ""){
			foreach ($results as $result) {
			
				$images = array();
				if($result['notes_file'] == '1'){
					$allimages = $this->model_notes_notes->getImages($result['notes_id']);
					
					foreach ($allimages as $image) {
						if( $image['media_date_added'] != "0000-00-00 00:00:00"){
							$mdate = date($this->language->get('date_format_short_2'), strtotime($image['media_date_added']));
						}else{
							$mdate = "";
						}
						
						$images[] = array(
							'attachment_icon' => 'img',
							'media_user_id' => $image['media_user_id'],
							'media_date_added' => $mdate,
							'media_signature' => $image['media_signature'],
							'media_pin' => $image['media_pin'],
							'attachment_url' => $image['notes_file'],
							'audio_attach_url' => $image['audio_attach_url']
						);
					}
				}
			
				
				if($result['highlighter_id'] > 0){
					$highlighterData = $this->model_setting_highlighter->gethighlighter($result['highlighter_id']);
					$highlighter_value = $highlighterData['highlighter_value'];
					
				}else{
					$highlighterData = array();
					$highlighter_value = '';
				}
				
				
				if($result['strike_date_added'] != null && $result['strike_date_added'] != "0000-00-00 00:00:00"){
					$strikeDate = date($this->language->get('date_format_short'), strtotime($result['strike_date_added']));
				}else{
					$strikeDate = '';
				}
				
				
								
				if($result['signature'] != null && $result['signature'] != ""){
					$signaturesrc = $result['signature'];
				}else{
					$signaturesrc = '';
				}
				
				
				if($result['strike_signature'] != null && $result['strike_signature'] != ""){
					$strike_signature = $result['strike_signature'];
				}else{
					$strike_signature = '';
				}
				
				if($result['strike_pin'] != null && $result['strike_pin'] != ""){
					$strikePin = '1';
				}else{
					$strikePin = '';
				}
				
				if($result['notes_pin'] != null && $result['notes_pin'] != ""){
					$notesPin = '1';
				}else{
					$notesPin = '';
				}
				
				/*if ($result['keyword_file'] != null && $result['keyword_file'] != "") {
					if($result['keyword_file_url'] != 'data:image/png;base64,'){
						$keyword_icon = $result['keyword_file_url'];
					}else{
						$keyword_icon = '';
					}
						
				} else{
					
				}*/
				$keyword_icon = '';
				/*if($result['notes_file'] != null && $result['notes_file'] != ""){
					$outputFolderUrl = HTTP_SERVER.'image/files/' . $result['notes_file'];
					$keyImageSrc = 'img';
					
				}else{
					$outputFolderUrl = "";
					$keyImageSrc = '';
				}*/
				
				if($result['is_reminder'] == '1'){
					$reminder_info = $this->model_notes_notes->getReminder($result['notes_id']);
			
					$reminder_time = $reminder_info['reminder_time'];
					$reminder_title = $reminder_info['reminder_title'];
				}else{
					$reminder_time = "";
					$reminder_title = "";
				}
				
				if($reminder_time != null && $reminder_time != ""){
					$reminderTime = $reminder_time;
				}else{
					$reminderTime = "";
				}
				if($reminder_title != null && $reminder_title != ""){
					$reminderTitle = $reminder_title;
				}else{
					$reminderTitle = "";
				}
				if($result['text_color'] != null && $result['text_color'] != ""){
					$text_color = $result['text_color'];
				}else{
					$text_color = '';
				}
				
				if($result['task_time'] != null && $result['task_time'] != "00:00:00"){ 
					$task_time = date('h:i A', strtotime($result['task_time']));
				}else{
					$task_time = "";
				}
				
				$alltag =  array(); 
				$alltaga =  array(); 
				if ($this->request->post['config_tag_status'] == '1') {
					if($result['emp_tag_id'] == '1'){
						$alltag = $this->model_notes_notes->getNotesTags($result['notes_id']);
						
						$alltaga[] = array(
							'notes_tags_id' => $alltag['notes_tags_id'],
							'tags_id' => $alltag['tags_id'],
							'emp_tag_id' => $alltag['emp_tag_id'],
							'user_id' => $alltag['user_id'],
							'notes_type' => $alltag['notes_type'],
							'notes_pin' => $alltag['notes_pin'],
							'signature' => $alltag['signature'],
							'date_added' => date($this->language->get('date_format_short'), strtotime($alltag['date_added'])),
						);
						
					}else{
						$alltag =  array(); 
						$alltaga =  array(); 
					}
					
					if($alltag['emp_tag_id'] != null && $alltag['emp_tag_id'] != ""){
						$tagdata = $this->model_notes_tags->getTagbyEMPID($alltag['emp_tag_id']);
						$privacy = $tagdata['privacy'];
							
						$emp_tag_id = $alltag['emp_tag_id'].': ';
					}else{
						$emp_tag_id = '';
						$privacy = '';
					}
				}else{
					$privacy = '';
				}
				
				
				if($privacy == '2'){
					if($this->request->post['unloack_success'] == '1'){
						$notes_description = $keyImageSrc1 .' '. $emp_tag_id . html_entity_decode(str_replace('&#039;', '\'',$result['notes_description']));
						$privacy = '1';
					}else{
						$notes_description = $emp_tag_id;
					}
				}else{
					$notes_description = $keyImageSrc1 .' '. html_entity_decode(str_replace('&#039;', '\'',$result['notes_description']));
				}
				
				
				/*
				if($result['notes_id'] != null && $result['notes_id'] != ""){
					$notesID = (string) $result['notes_id'];
					require_once(DIR_APPLICATION . 'aws/getItem.php');
					
					
					
					$response = $dynamodb->scan([
					'TableName' => 'incidentform',
					'ProjectionExpression' => 'incidentform_id, notes_id, user_id, signature, notes_pin, form_date_added,facilities_id ',
					'ExpressionAttributeValues' => [
						':val1' => ['N' =>  $notesID]] ,
					'FilterExpression' => 'notes_id = :val1',
					]);
					 
					
					//$response = $dynamodb->scan($params);
					
					//var_dump($response['Items']);
					//echo '<hr>  ';
					
					$forms = array();
					foreach($response['Items'] as $item){
						$form_date_added1 = str_replace("&nbsp;","",$item['form_date_added']['S']);
						if($form_date_added1 != null && $form_date_added1 != ""){
							$form_date_added = date($this->language->get('date_format_short_2'), strtotime($item['form_date_added']['S']));
						}else{
							$form_date_added = ""; 
						}
						$forms[] = array(
							'incidentform_id' => $item['incidentform_id']['N'],
							'notes_id' => $item['notes_id']['N'],
							'user_id' => str_replace("&nbsp;","",$item['user_id']['S']),
							'signature' => str_replace("&nbsp;","",$item['signature']['S']),
							'notes_pin' => str_replace("&nbsp;","",$item['notes_pin']['S']),
							'form_date_added' => $form_date_added,
							'href'        => str_replace('&amp;', '&', $this->url->link('services/noteform/forminsert/', '' . 'incidentform_id=' . $item['incidentform_id']['N']. '&facilities_id=' . $item['facilities_id']['S']. '&notes_id=' . $item['notes_id']['N']))
							
						); 
					}
				}else{
					$forms = array();
				}*/
				
				$forms = array();
				
				if($result['is_forms'] == '1'){
					$allforms = $this->model_notes_notes->getforms($result['notes_id']);
					
					foreach ($allforms as $allform) {
						if($allform['form_type'] == '1'){
						$form_url =	str_replace('&amp;', '&', $this->url->link('services/noteform/forminsert/', '' . 'incidentform_id=' . $allform['form_type_id']. '&facilities_id=' . $allform['facilities_id']. '&notes_id=' . $allform['notes_id']));
						}
						
						if($allform['form_type'] == '2'){
						$form_url =	str_replace('&amp;', '&', $this->url->link('services/noteform/noteschecklistform', '' . 'checklist_id=' . $allform['form_type_id']. '&facilities_id=' . $allform['facilities_id']. '&notes_id=' . $allform['notes_id']));
						}
						
						$forms[] = array(
								'form_type_id' => $allform['form_type_id'],
								'notes_id' => $allform['notes_id'],
								'form_type' => $allform['form_type'],
								'user_id' => $allform['user_id'],
								'signature' => $allform['signature'],
								'notes_pin' => $allform['notes_pin'],
								'incident_number' => $allform['incident_number'],
								'form_date_added' => date($this->language->get('date_format_short_2'), strtotime($allform['form_date_added'])),
								'href'        => $form_url
								
							); 
					}
				}
				
				
				$notestasks = array();
				$grandtotal = 0;
				$ograndtotal = 0;
				if($result['task_type'] == '1'){
					$alltasks = $this->model_notes_notes->getnotesBytasks($result['notes_id'], '1');
					foreach ($alltasks as $alltask) {
						$grandtotal = $grandtotal + $alltask['capacity'];
						$tags_ids_names = '';
						if($alltask['tags_ids'] != null && $alltask['tags_ids'] != ""){
							$tags_ids1 = explode(',',$alltask['tags_ids']);
							
							foreach ($tags_ids1 as $tag1) {
								$tags_info1 = $this->model_setting_tags->getTag($tag1);

								if($tags_info1['emp_first_name']){
									$emp_tag_id = $tags_info1['emp_tag_id'].':'.$tags_info1['emp_first_name'];
								}else{
									$emp_tag_id = $tags_info1['emp_tag_id'];
								}
									
								if ($tags_info1) {
									$tags_ids_names .= $emp_tag_id.', ';

								}
							}
						}
						$out_tags_ids_names = "";
						$ograndtotal = $ograndtotal + $alltask['out_capacity'];
						
						if($alltask['out_tags_ids'] != null && $alltask['out_tags_ids'] != ""){
							$tags_ids1 = explode(',',$alltask['out_tags_ids']);
							$i=0;
							foreach ($tags_ids1 as $tag1) {
								$tags_info1 = $this->model_setting_tags->getTag($tag1);

								if($tags_info1['emp_first_name']){
									$emp_tag_id = $tags_info1['emp_tag_id'].':'.$tags_info1['emp_first_name'];
								}else{
									$emp_tag_id = $tags_info1['emp_tag_id'];
								}
									
								if ($tags_info1) {
									$out_tags_ids_names .= $emp_tag_id.', ';

								}
								$i++;
							}
							//$ograndtotal = $i;
						}
						
						$notestasks[] = array(
								'notes_by_task_id' => $alltask['notes_by_task_id'],
								'locations_id' => $alltask['locations_id'],
								'task_type' => $alltask['task_type'],
								'task_content' => $alltask['task_content'],
								'user_id' => $alltask['user_id'],
								//'signature' => $alltask['signature'],
								//'notes_pin' => $alltask['notes_pin'],
								//'task_time' => $alltask['task_time'],
								//'media_url' => $alltask['media_url'],
								'capacity' => $alltask['capacity'],
								'location_name' => $alltask['location_name'],
								'location_type' => $alltask['location_type'],
								'notes_task_type' => $alltask['notes_task_type'],
								'task_comments' => $alltask['task_comments'],
								'role_call' => $alltask['role_call'],
								'date_added' => date($this->language->get('date_format_short_2'), strtotime($alltask['date_added'])),
								'room_current_date_time' => date('h:i A', strtotime($alltask['room_current_date_time'])),
								'tags_ids_names' => $tags_ids_names,
								'out_tags_ids_names' => $out_tags_ids_names,
								
							); 
							
					}
				}

				
				
				$notesmedicationtasks = array();
				if($result['task_type'] == '2'){
					$alltmasks = $this->model_notes_notes->getnotesBytasks($result['notes_id'], '2');
					
					foreach ($alltmasks as $alltmask) {
						
						if($alltmask['task_time'] != null && $alltmask['task_time'] != '00:00:00'){
							$taskTime = date('h:i A', strtotime($alltmask['task_time']));
						}
						
						$notesmedicationtasks[] = array(
								'notes_by_task_id' => $alltmask['notes_by_task_id'],
								'locations_id' => $alltmask['locations_id'],
								'task_type' => $alltmask['task_type'],
								'task_content' => $alltmask['task_content'],
								'user_id' => $alltmask['user_id'],
								'signature' => $alltmask['signature'],
								'notes_pin' => $alltmask['notes_pin'],
								'task_time' => $taskTime,
								'media_url' => $alltmask['media_url'],
								'capacity' => $alltmask['capacity'],
								'location_name' => $alltmask['location_name'],
								'location_type' => $alltmask['location_type'],
								'notes_task_type' => $alltmask['notes_task_type'],
								'tags_id' => $alltmask['tags_id'],
								'drug_name' => $alltmask['drug_name'],
								'dose' => $alltmask['dose'],
								'drug_type' => $alltmask['drug_type'],
								'quantity' => $alltmask['quantity'],
								'frequency' => $alltmask['frequency'],
								'instructions' => $alltmask['instructions'],
								'count' => $alltmask['count'],
								'createtask_by_group_id' => $alltmask['createtask_by_group_id'],
								'task_comments' => $alltmask['task_comments'],
								'role_call' => $alltmask['role_call'],
								'medication_file_upload' => $alltmask['medication_file_upload'],
								'date_added' => date($this->language->get('date_format_short_2'), strtotime($alltmask['date_added'])),
								
							); 
							
							
					}
				
				
				}
				
				
				$noteskeywords = array();
				if($result['keyword_file'] == '1'){
					$allkeywords = $this->model_notes_notes->getnoteskeywors($result['notes_id']);
				}else{
					$allkeywords = array();
				}
				
				if($allkeywords){
					$keyImageSrc12 = array();
					$keyname = array();					
					foreach ($allkeywords as $allkeyword) {
						$noteskeywords[] = array(
							'notes_by_keyword_id' => $allkeyword['notes_by_keyword_id'],
							'notes_id' => $allkeyword['notes_id'],
							'keyword_id' => $allkeyword['keyword_id'],
							'keyword_name' => $allkeyword['keyword_name'],
							'keyword_file_url' => $allkeyword['keyword_file_url'],
						);
						
						$keyImageSrc11 = $allkeyword['keyword_file_url'];
						$keyImageSrc12[] = $keyImageSrc11 .'&nbsp;' . $allkeyword['keyword_name'];
						$keyname[] = $allkeyword['keyword_name'];
					}
					$keyword_description = str_replace($keyname, $keyImageSrc12, $result['notes_description']);
							
					$notes_description2 = $keyword_description;
				}else{
					$notes_description2 = '';
				}
				
				
				if($result['is_census'] == '1'){
					$is_census_url =	str_replace('&amp;', '&', $this->url->link('resident/dailycensus/censusdetail', ''. '&notes_id=' . $result['notes_id']. '&facilities_id=' . $result['facilities_id']));
				}else{
					$is_census_url = '';
				}
				
				if($result['is_tag'] != '0'){
					$is_tag_url =	str_replace('&amp;', '&', $this->url->link('services/resident/addclient', ''. '&tags_id=' . $result['is_tag']. '&notes_id=' . $result['notes_id']. '&facilities_id=' . $result['facilities_id']. '&is_archive=' . $note_info['is_archive']));
				}else{
					$is_tag_url = '';
				}
				
				
				if($result['task_type'] == '3'){
					$geolocation_info = $this->model_notes_notes->getGeolocation($result['notes_id']);
				}else{
					$geolocation_info = array();
				}
				if($result['task_type'] == '6'){
					$approvaltask = $this->model_notes_notes->getapprovaltask($result['task_id']);
				}else{
					$approvaltask = array();
				}
				
				if($result['original_task_time'] != null && $result['original_task_time'] != "00:00:00"){ 
					$original_task_time = date('h:i A', strtotime($result['original_task_time']));
				}else{
					$original_task_time = "";
				}
				
				if($result['user_id'] == SYSTEM_GENERATED ){
					$auto_generate = '1';
				}else{
					$auto_generate = '0';
				}
				
				$this->data['facilitiess'][] = array(
					'notes_id'  => $result['notes_id'],
					'is_user_face'    => $result['is_user_face'],
					'is_approval_required_forms_id'    => $result['is_approval_required_forms_id'],
					'user_file'    => $result['user_file'],
					'auto_generate'    => $auto_generate,
					'original_task_time'    => $original_task_time,
					'geolocation_info'    => $geolocation_info,
					'approvaltask'    => $approvaltask,
					'notes_file'    => $result['notes_file'],
					'keyword_file'    => $result['keyword_file'],
					'emp_tag_id'    => $result['emp_tag_id'],
					'is_forms'    => $result['is_forms'],
					'is_reminder'    => $result['is_reminder'],
					'task_type'    => $result['task_type'],
					'checklist_status'    => $result['checklist_status'],
					'visitor_log'    => $result['visitor_log'],
					'is_tag'    => $result['is_tag'],
					'is_archive'    => $result['is_archive'],
					'is_tag_url'    => $is_tag_url,
					'form_type'    => $result['form_type'],
					'generate_report'    => $result['generate_report'],
					'is_census'    => $result['is_census'],
					'is_census_url'    => $is_census_url,
					'is_android'    => $result['is_android'],
					'task_time'    => $task_time,
					'review_notes'    => $result['review_notes'],
					'is_offline'    => $result['is_offline'],
					'noteskeywords'   => $noteskeywords,
					'alltag'    => $alltaga,
					'images'   => $images,
					'incidentforms'    => $forms, 
					'notestasks'    => $notestasks, 
					'grandtotal'    => $grandtotal, 
					'ograndtotal'    => $ograndtotal, 
					'boytotals'    => $boytotals, 
					'girltotals'    => $girltotals, 
					'generaltotals'    => $generaltotals, 
					'residentstotals'    => $residentstotals, 
					'notesmedicationtasks'    => $notesmedicationtasks, 
					
					'tag_privacy'    => $privacy,
					'taskadded'    => $result['taskadded'],
					'notes_type'    => $result['notes_type'],
					'highlighter_value'   => $highlighter_value,
					'notes_description'   => html_entity_decode(str_replace('&#039;', '\'',$result['notes_description'])),
					//'notes_description2'   => $notes_description2,
					//'attachment_icon'   => $keyImageSrc,
					//'attachment_url'   => $outputFolderUrl,
					'keyword_icon'   => $keyword_icon,
					'notetime'   => $result['notetime'],
					'username'      => $result['user_id'],
					'signature'   => $signaturesrc,
					'notes_pin'   => $notesPin,
					'text_color_cut'   => $result['text_color_cut'],
					'text_color'   => $text_color,
					'note_date'   => date($this->language->get('date_format_short'), strtotime($result['note_date'])),
					'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'update_date_time' => date('H:i:s', strtotime($result['update_date'])),
					'strike_user_name'   => $result['strike_user_id'],
					'strike_signature'   => $strike_signature,
					'strike_date_added'   => $strikeDate,
					'strike_pin'   => $strikePin,
					'reminder_title'   => '',//$reminderTitle,
					'reminder_time'   => '',//$reminderTime,
				); 
			}
			$error = true;
		}else{
			$this->data['facilitiess'] = array();
			$error = true;
			$taskTotal = 0;
			
		}
		
		if($this->config->get('config_task_status') == '1'){
			
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
			
			$this->load->model('createtask/createtask');
			$top = '2';
			
			if (isset($this->request->post['facilities_timezone'])) {
      		$facilities_timezone = $this->request->post['facilities_timezone'];
				date_default_timezone_set($facilities_timezone);
			}
			
				$date = str_replace('-', '/', $searchdate);
				$res = explode("/", $date);
				
				$changedDate = $res[2]."-".$res[1]."-".$res[0];
				
      		$currentdate = $changedDate;
			
			$taskTotal = $this->model_createtask_createtask->getCountTasklist($facilities_id, $currentdate, $top);	
			
			
		}else{
			$taskTotal = 0;
		}
		
		$notes_total2 = ceil($notes_total/$config_admin_limit);
		
		$value = array('results'=>$this->data['facilitiess'],'Tasktotal'=>$taskTotal,'status'=>$error,'total_note'=>$notes_total2,'total_note1'=>$notes_total);
		/*echo json_encode($value);*/
		$this->response->setOutput(json_encode($value));
		
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices jsongetNotesByApp',
			);
			$this->model_activity_activity->addActivity('app_jsongetNotesByApp', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		} 
	}

	
	public function jsongetNotesByPageByApp() { 
	
	try{
		$this->data['facilitiess'] = array();
		$this->language->load('notes/notes');
		$this->load->model('notes/notes');
		$this->load->model('setting/image'); 
		$this->load->model('notes/image'); 
		
		
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
		
		if (isset($this->request->post['keyword'])) {
      		$keyword = $this->request->post['keyword'];
    	} 
		
		if (isset($this->request->post['form_search'])) {
      		$form_search = $this->request->post['form_search'];
    	} 
		
		
		
		if (isset($this->request->post['facilities_id'])) {
      		$facilities_id = $this->request->post['facilities_id'];
    	} 
		
		if (isset($this->request->post['highlighter'])) {
      		$highlighter = $this->request->post['highlighter'];
    	}

		
		if (isset($this->request->post['activenote'])) {
      		$activenote = $this->request->post['activenote'];
    	}
		
		if (isset($this->request->post['sync'])) {
      		$sync_data = $this->request->post['sync'];
    	}
		
		
		if (isset($this->request->post['user_id'])) {
      		$user_id = $this->request->post['user_id'];
    	} 
		if (isset($this->request->post['tasktype'])) {
      		$tasktype = $this->request->post['tasktype'];
    	} 
		
		if($this->request->post['note_date_from'] != null && $this->request->post['note_date_from'] != ""){
			$date = str_replace('-', '/', $this->request->post['note_date_from']);
			$res = explode("/", $date);
			$changedDate = $res[2]."-".$res[1]."-".$res[0];
			
				
			$note_date_from = $changedDate; //date('Y-m-d', strtotime($this->request->post['note_date_from']));
		}
		if($this->request->post['note_date_to'] != null && $this->request->post['note_date_to'] != ""){
			$date1 = str_replace('-', '/', $this->request->post['note_date_to']);
			$res1 = explode("/", $date1);
			$changedDate1 = $res1[2]."-".$res1[1]."-".$res1[0];
			
			$note_date_to = $changedDate1; //date('Y-m-d', strtotime($this->request->post['note_date_to']));
		}
		
		
		if ($this->request->post['searchdate'] != null && $this->request->post['searchdate'] != "") {
			$this->data['note_date'] = $this->request->post['searchdate'];
			$searchdate = date('Y-m-d', strtotime($this->request->post['searchdate']));
		} else {
			$this->data['note_date'] =  date('d-m-Y');
		}
		
		if (isset($this->request->post['advance_search'])) {
      		$advance_search = $this->request->post['advance_search'];
			$group = '1';
    	} 
		
		if (isset($this->request->post['emp_tag_id'])) {
      		$emp_tag_id = $this->request->post['emp_tag_id'];
    	} 

		
		if (isset($this->request->post['search_time_start'])) {
			$search_time_start = $this->request->post['search_time_start'];
		} 
		
		if (isset($this->request->post['search_time_to'])) {
			$search_time_to = $this->request->post['search_time_to'];
		} 
		
		
		if (isset($this->request->post['page'])) {
			$page = $this->request->post['page'];
		} else {
			$page = 1;
		}
	
		$config_admin_limit1 = $this->config->get('config_android_front_limit');
		if($config_admin_limit1 != null && $config_admin_limit1 != ""){
			$config_admin_limit = $config_admin_limit1;
		}else{
			$config_admin_limit = "25";
		}
		
		$dataform = array( 
			'sort'  => $sort,
			'order' => $order,
			'facilities_id' => $facilities_id,
			'facilities_timezone' => $this->request->post['facilities_timezone'],
			'sync_data' => $sync_data,
			'searchdate' => $searchdate,
			'note_date_from' => $note_date_from,
			'note_date_to' => $note_date_to,
			'group' => $group,
			
			'search_time_start' => $search_time_start,
			'search_time_to' => $search_time_to,
			
			'keyword' => $keyword,
			'tasktype' => $tasktype,
			'highlighter' => $highlighter,
			'activenote' => $activenote,
			'form_search' => $form_search,
			'user_id' => $user_id,
			'advance_search' => $advance_search,
			'emp_tag_id' => $emp_tag_id,
			'start' => ($page - 1) * $config_admin_limit,
			'limit' => $config_admin_limit
		);
		
		$results = $this->model_notes_notes->getnotess($dataform);
		
		$notes_total = $this->model_notes_notes->getTotalnotess($dataform);
		
		$this->load->model('setting/highlighter');
		$this->load->model('user/user');
		$this->load->model('setting/keywords');
		$this->load->model('notes/tags');
		$this->load->model('setting/tags');
    	
		if($results != null && $results != ""){
			foreach ($results as $result) {
				
				$images = array();
				if($result['notes_file'] == '1'){
					$allimages = $this->model_notes_notes->getImages($result['notes_id']);
					
					foreach ($allimages as $image) {
						if( $image['media_date_added'] != "0000-00-00 00:00:00"){
							$mdate = date($this->language->get('date_format_short_2'), strtotime($image['media_date_added']));
						}else{
							$mdate = "";
						}
						
						$images[] = array(
							'attachment_icon' => 'img',
							'media_user_id' => $image['media_user_id'],
							'media_date_added' => $mdate,
							'media_signature' => $image['media_signature'],
							'media_pin' => $image['media_pin'],
							'attachment_url' => $image['notes_file'],
							'audio_attach_url' => $image['audio_attach_url']
						);
					}
				}
			
				
				if($result['highlighter_id'] > 0){
					$highlighterData = $this->model_setting_highlighter->gethighlighter($result['highlighter_id']);
					$highlighter_value = $highlighterData['highlighter_value'];
					
				}else{
					$highlighterData = array();
					$highlighter_value = '';
				}
				
				
				if($result['strike_date_added'] != null && $result['strike_date_added'] != "0000-00-00 00:00:00"){
					$strikeDate = date($this->language->get('date_format_short'), strtotime($result['strike_date_added']));
				}else{
					$strikeDate = '';
				}
				
				
								
				if($result['signature'] != null && $result['signature'] != ""){
					$signaturesrc = $result['signature'];
				}else{
					$signaturesrc = '';
				}
				
				
				if($result['strike_signature'] != null && $result['strike_signature'] != ""){
					$strike_signature = $result['strike_signature'];
				}else{
					$strike_signature = '';
				}
				
				if($result['strike_pin'] != null && $result['strike_pin'] != ""){
					$strikePin = '1';
				}else{
					$strikePin = '';
				}
				
				if($result['notes_pin'] != null && $result['notes_pin'] != ""){
					$notesPin = '1';
				}else{
					$notesPin = '';
				}
				
				/*if ($result['keyword_file'] != null && $result['keyword_file'] != "") {
					if($result['keyword_file_url'] != 'data:image/png;base64,'){
						$keyword_icon = $result['keyword_file_url'];
					}else{
						$keyword_icon = '';
					}
						
				} else{
					
				}*/
				$keyword_icon = '';
				/*if($result['notes_file'] != null && $result['notes_file'] != ""){
					$outputFolderUrl = HTTP_SERVER.'image/files/' . $result['notes_file'];
					$keyImageSrc = 'img';
					
				}else{
					$outputFolderUrl = "";
					$keyImageSrc = '';
				}*/
				
				if($result['is_reminder'] == '1'){
					$reminder_info = $this->model_notes_notes->getReminder($result['notes_id']);
			
					$reminder_time = $reminder_info['reminder_time'];
					$reminder_title = $reminder_info['reminder_title'];
				}else{
					$reminder_time = "";
					$reminder_title = "";
				}
				
				if($reminder_time != null && $reminder_time != ""){
					$reminderTime = $reminder_time;
				}else{
					$reminderTime = "";
				}
				if($reminder_title != null && $reminder_title != ""){
					$reminderTitle = $reminder_title;
				}else{
					$reminderTitle = "";
				}
				if($result['text_color'] != null && $result['text_color'] != ""){
					$text_color = $result['text_color'];
				}else{
					$text_color = '';
				}
				
				if($result['task_time'] != null && $result['task_time'] != "00:00:00"){ 
					$task_time = date('h:i A', strtotime($result['task_time']));
				}else{
					$task_time = "";
				}
				
				$alltag =  array(); 
				$alltaga =  array(); 
				if ($this->request->post['config_tag_status'] == '1') {
					if($result['emp_tag_id'] == '1'){
						$alltag = $this->model_notes_notes->getNotesTags($result['notes_id']);
						
						$alltaga[] = array(
							'notes_tags_id' => $alltag['notes_tags_id'],
							'tags_id' => $alltag['tags_id'],
							'emp_tag_id' => $alltag['emp_tag_id'],
							'user_id' => $alltag['user_id'],
							'notes_type' => $alltag['notes_type'],
							'notes_pin' => $alltag['notes_pin'],
							'signature' => $alltag['signature'],
							'date_added' => date($this->language->get('date_format_short'), strtotime($alltag['date_added'])),
						);
						
					}else{
						$alltag =  array(); 
						$alltaga =  array(); 
					}
					
					if($alltag['emp_tag_id'] != null && $alltag['emp_tag_id'] != ""){
						$tagdata = $this->model_notes_tags->getTagbyEMPID($alltag['emp_tag_id']);
						$privacy = $tagdata['privacy'];
							
						$emp_tag_id = $alltag['emp_tag_id'].': ';
					}else{
						$emp_tag_id = '';
						$privacy = '';
					}
				}else{
					$privacy = '';
				}
				
				
				if($privacy == '2'){
					if($this->request->post['unloack_success'] == '1'){
						$notes_description = $keyImageSrc1 .' '. $emp_tag_id . html_entity_decode(str_replace('&#039;', '\'',$result['notes_description']));
						$privacy = '1';
					}else{
						$notes_description = $emp_tag_id;
					}
				}else{
					$notes_description = $keyImageSrc1 .' '. html_entity_decode(str_replace('&#039;', '\'',$result['notes_description']));
				}
				
				
				/*
				if($result['notes_id'] != null && $result['notes_id'] != ""){
					$notesID = (string) $result['notes_id'];
					require_once(DIR_APPLICATION . 'aws/getItem.php');
					
					
					
					$response = $dynamodb->scan([
					'TableName' => 'incidentform',
					'ProjectionExpression' => 'incidentform_id, notes_id, user_id, signature, notes_pin, form_date_added,facilities_id ',
					'ExpressionAttributeValues' => [
						':val1' => ['N' =>  $notesID]] ,
					'FilterExpression' => 'notes_id = :val1',
					]);
					 
					
					//$response = $dynamodb->scan($params);
					
					//var_dump($response['Items']);
					//echo '<hr>  ';
					
					$forms = array();
					foreach($response['Items'] as $item){
						$form_date_added1 = str_replace("&nbsp;","",$item['form_date_added']['S']);
						if($form_date_added1 != null && $form_date_added1 != ""){
							$form_date_added = date($this->language->get('date_format_short_2'), strtotime($item['form_date_added']['S']));
						}else{
							$form_date_added = ""; 
						}
						$forms[] = array(
							'incidentform_id' => $item['incidentform_id']['N'],
							'notes_id' => $item['notes_id']['N'],
							'user_id' => str_replace("&nbsp;","",$item['user_id']['S']),
							'signature' => str_replace("&nbsp;","",$item['signature']['S']),
							'notes_pin' => str_replace("&nbsp;","",$item['notes_pin']['S']),
							'form_date_added' => $form_date_added,
							'href'        => str_replace('&amp;', '&', $this->url->link('services/noteform/forminsert/', '' . 'incidentform_id=' . $item['incidentform_id']['N']. '&facilities_id=' . $item['facilities_id']['S']. '&notes_id=' . $item['notes_id']['N']))
							
						); 
					}
				}else{
					$forms = array();
				}*/
				
				$forms = array();
				
				if($result['is_forms'] == '1'){
					$allforms = $this->model_notes_notes->getforms($result['notes_id']);
					
					foreach ($allforms as $allform) {
						if($allform['form_type'] == '1'){
						$form_url =	str_replace('&amp;', '&', $this->url->link('services/noteform/forminsert/', '' . 'incidentform_id=' . $allform['form_type_id']. '&facilities_id=' . $allform['facilities_id']. '&notes_id=' . $allform['notes_id']));
						}
						
						if($allform['form_type'] == '2'){
						$form_url =	str_replace('&amp;', '&', $this->url->link('services/noteform/noteschecklistform', '' . 'checklist_id=' . $allform['form_type_id']. '&facilities_id=' . $allform['facilities_id']. '&notes_id=' . $allform['notes_id']));
						}
						
						$forms[] = array(
								'form_type_id' => $allform['form_type_id'],
								'notes_id' => $allform['notes_id'],
								'form_type' => $allform['form_type'],
								'user_id' => $allform['user_id'],
								'signature' => $allform['signature'],
								'notes_pin' => $allform['notes_pin'],
								'incident_number' => $allform['incident_number'],
								'form_date_added' => date($this->language->get('date_format_short_2'), strtotime($allform['form_date_added'])),
								'href'        => $form_url
								
							); 
					}
				}
				
				$notestasks = array();
				$grandtotal = 0;
				$ograndtotal = 0;
				if($result['task_type'] == '1'){
					$alltasks = $this->model_notes_notes->getnotesBytasks($result['notes_id'], '1');
					foreach ($alltasks as $alltask) {
						$grandtotal = $grandtotal + $alltask['capacity'];
						$tags_ids_names = '';
						if($alltask['tags_ids'] != null && $alltask['tags_ids'] != ""){
							$tags_ids1 = explode(',',$alltask['tags_ids']);
							
							foreach ($tags_ids1 as $tag1) {
								$tags_info1 = $this->model_setting_tags->getTag($tag1);

								if($tags_info1['emp_first_name']){
									$emp_tag_id = $tags_info1['emp_tag_id'].':'.$tags_info1['emp_first_name'];
								}else{
									$emp_tag_id = $tags_info1['emp_tag_id'];
								}
									
								if ($tags_info1) {
									$tags_ids_names .= $emp_tag_id.', ';

								}
							}
						}
						$out_tags_ids_names = "";
						$ograndtotal = $ograndtotal + $alltask['out_capacity'];
						
						if($alltask['out_tags_ids'] != null && $alltask['out_tags_ids'] != ""){
							$tags_ids1 = explode(',',$alltask['out_tags_ids']);
							$i=0;
							foreach ($tags_ids1 as $tag1) {
								$tags_info1 = $this->model_setting_tags->getTag($tag1);
								
								if($tags_info1['emp_first_name']){
									$emp_tag_id = $tags_info1['emp_tag_id'].':'.$tags_info1['emp_first_name'];
								}else{
									$emp_tag_id = $tags_info1['emp_tag_id'];
								}
									
								if ($tags_info1) {
									$out_tags_ids_names .= $emp_tag_id.', ';

								}
								$i++;
							}
							//$ograndtotal = $i;
						}
						
						$notestasks[] = array(
								'notes_by_task_id' => $alltask['notes_by_task_id'],
								'locations_id' => $alltask['locations_id'],
								'task_type' => $alltask['task_type'],
								'task_content' => $alltask['task_content'],
								//'user_id' => $alltask['user_id'],
								//'signature' => $alltask['signature'],
								//'notes_pin' => $alltask['notes_pin'],
								//'task_time' => $alltask['task_time'],
								//'media_url' => $alltask['media_url'],
								'capacity' => $alltask['capacity'],
								'location_name' => $alltask['location_name'],
								'location_type' => $alltask['location_type'],
								'notes_task_type' => $alltask['notes_task_type'],
								'task_comments' => $alltask['task_comments'],
								'role_call' => $alltask['role_call'],
								
								'date_added' => date($this->language->get('date_format_short_2'), strtotime($alltask['date_added'])),
								'room_current_date_time' => date('h:i A', strtotime($alltask['room_current_date_time'])),
								'tags_ids_names' => $tags_ids_names,
								'out_tags_ids_names' => $out_tags_ids_names,
								
							); 
							
					}
				}
				
				$notesmedicationtasks = array();
				if($result['task_type'] == '2'){
					$alltmasks = $this->model_notes_notes->getnotesBytasks($result['notes_id'], '2');
					
					foreach ($alltmasks as $alltmask) {
						
						if($alltmask['task_time'] != null && $alltmask['task_time'] != '00:00:00'){
							$taskTime = date('h:i A', strtotime($alltmask['task_time']));
						}
						
						$notesmedicationtasks[] = array(
								'notes_by_task_id' => $alltmask['notes_by_task_id'],
								'locations_id' => $alltmask['locations_id'],
								'task_type' => $alltmask['task_type'],
								'task_content' => $alltmask['task_content'],
								'user_id' => $alltmask['user_id'],
								'signature' => $alltmask['signature'],
								'notes_pin' => $alltmask['notes_pin'],
								'task_time' => $taskTime,
								'media_url' => $alltmask['media_url'],
								'capacity' => $alltmask['capacity'],
								'location_name' => $alltmask['location_name'],
								'location_type' => $alltmask['location_type'],
								'notes_task_type' => $alltmask['notes_task_type'],
								'tags_id' => $alltmask['tags_id'],
								'drug_name' => $alltmask['drug_name'],
								'dose' => $alltmask['dose'],
								'drug_type' => $alltmask['drug_type'],
								'quantity' => $alltmask['quantity'],
								'frequency' => $alltmask['frequency'],
								'instructions' => $alltmask['instructions'],
								'count' => $alltmask['count'],
								'createtask_by_group_id' => $alltmask['createtask_by_group_id'],
								'task_comments' => $alltmask['task_comments'],
								'role_call' => $alltmask['role_call'],
								'medication_file_upload' => $alltmask['medication_file_upload'],
								'date_added' => date($this->language->get('date_format_short_2'), strtotime($alltmask['date_added'])),
								
							); 
							
							
					}
				
				
				}
				
				
				$noteskeywords = array();
				if($result['keyword_file'] == '1'){
					$allkeywords = $this->model_notes_notes->getnoteskeywors($result['notes_id']);
				}else{
					$allkeywords = array();
				}
				
				if($allkeywords){
					$keyImageSrc12 = array();
					$keyname = array();					
					foreach ($allkeywords as $allkeyword) {
						$noteskeywords[] = array(
							'notes_by_keyword_id' => $allkeyword['notes_by_keyword_id'],
							'notes_id' => $allkeyword['notes_id'],
							'keyword_id' => $allkeyword['keyword_id'],
							'keyword_name' => $allkeyword['keyword_name'],
							'keyword_file_url' => $allkeyword['keyword_file_url'],
						);
						
						$keyImageSrc11 = $allkeyword['keyword_file_url'];
						$keyImageSrc12[] = $keyImageSrc11 .'&nbsp;' . $allkeyword['keyword_name'];
						$keyname[] = $allkeyword['keyword_name'];
					}
					$keyword_description = str_replace($keyname, $keyImageSrc12, $result['notes_description']);
							
					$notes_description2 = $keyword_description;
				}else{
					$notes_description2 = '';
				}
				
				
				if($result['is_census'] == '1'){
					$is_census_url =	str_replace('&amp;', '&', $this->url->link('resident/dailycensus/censusdetail', ''. '&notes_id=' . $result['notes_id']. '&facilities_id=' . $result['facilities_id']));
				}else{
					$is_census_url = '';
				}
				
				if($result['is_tag'] != '0'){
					$is_tag_url =	str_replace('&amp;', '&', $this->url->link('services/resident/addclient', ''. '&tags_id=' . $result['is_tag']. '&notes_id=' . $result['notes_id']. '&facilities_id=' . $result['facilities_id']. '&is_archive=' . $note_info['is_archive']));
				}else{
					$is_tag_url = '';
				}
				
				
				if($result['task_type'] == '3'){
					$geolocation_info = $this->model_notes_notes->getGeolocation($result['notes_id']);
				}else{
					$geolocation_info = array();
				}
				if($result['task_type'] == '6'){
					$approvaltask = $this->model_notes_notes->getapprovaltask($result['task_id']);
				}else{
					$approvaltask = array();
				}
				
				if($result['original_task_time'] != null && $result['original_task_time'] != "00:00:00"){ 
					$original_task_time = date('h:i A', strtotime($result['original_task_time']));
				}else{
					$original_task_time = "";
				}
				
				if($result['user_id'] == SYSTEM_GENERATED ){
					$auto_generate = '1';
				}else{
					$auto_generate = '0';
				}
				
				$this->data['facilitiess'][] = array(
					'notes_id'  => $result['notes_id'],
					'is_user_face'    => $result['is_user_face'],
					'is_approval_required_forms_id'    => $result['is_approval_required_forms_id'],
					'user_file'    => $result['user_file'],
					'auto_generate'    => $auto_generate,
					'original_task_time'    => $original_task_time,
					'geolocation_info'    => $geolocation_info,
					'approvaltask'    => $approvaltask,
					'notes_file'    => $result['notes_file'],
					'keyword_file'    => $result['keyword_file'],
					'emp_tag_id'    => $result['emp_tag_id'],
					'is_forms'    => $result['is_forms'],
					'is_reminder'    => $result['is_reminder'],
					'task_type'    => $result['task_type'],
					'is_offline'  => $result['is_offline'],
					'visitor_log'  => $result['visitor_log'],
					'is_tag'    => $result['is_tag'],
					'is_archive'    => $result['is_archive'],
					'is_tag_url'    => $is_tag_url,
					'generate_report'    => $result['generate_report'],
					'form_type'    => $result['form_type'],
					'is_census'    => $result['is_census'],
					'is_census_url'    => $is_census_url,
					'is_android'    => $result['is_android'],
					'review_notes'    => $result['review_notes'],
					'checklist_status'    => $result['checklist_status'],
					'task_time'    => $task_time,
					'alltag'    => $alltaga,
					'noteskeywords'   => $noteskeywords,
					'images'   => $images,
					'incidentforms'    => $forms, 
					'notestasks'    => $notestasks, 
					'grandtotal'    => $grandtotal, 
					'ograndtotal'    => $ograndtotal, 
					'boytotals'    => $boytotals, 
					'girltotals'    => $girltotals, 
					'generaltotals'    => $generaltotals, 
					'residentstotals'    => $residentstotals, 
					'notesmedicationtasks'    => $notesmedicationtasks, 
					'tag_privacy'    => $privacy,
					'taskadded'    => $result['taskadded'],
					'notes_type'    => $result['notes_type'],
					'highlighter_value'   => $highlighter_value,
					'notes_description'   => html_entity_decode(str_replace('&#039;', '\'',$result['notes_description'])),
					//'notes_description2'   => $notes_description2,
					//'attachment_icon'   => $keyImageSrc,
					//'attachment_url'   => $outputFolderUrl,
					'keyword_icon'   => $keyword_icon,
					'notetime'   => $result['notetime'],
					'username'      => $result['user_id'],
					'signature'   => $signaturesrc,
					'notes_pin'   => $notesPin,
					'text_color_cut'   => $result['text_color_cut'],
					'text_color'   => $text_color,
					'note_date'   => date($this->language->get('date_format_short'), strtotime($result['note_date'])),
					'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'date_added2' => date('D F j, Y', strtotime($result['date_added'])),
					'update_date_time' => date('H:i:s', strtotime($result['update_date'])),
					'strike_user_name'   => $result['strike_user_id'],
					'strike_signature'   => $strike_signature,
					'strike_date_added'   => $strikeDate,
					'strike_pin'   => $strikePin,
					'reminder_title'   => '',//$reminderTitle,
					'reminder_time'   => '',//$reminderTime,
				); 
			}
			$error = true;
		}else{
			$this->data['facilitiess'] = array();
			$error = true;
			
		}
		$value = array('results'=>$this->data['facilitiess'],'status'=>$error,'total_note'=>$notes_total);
		/*echo json_encode($value);*/
		$this->response->setOutput(json_encode($value));
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices jsongetNotesByPageByApp',
			);
			$this->model_activity_activity->addActivity('app_jsongetNotesByPageByApp', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		}
		
	}
	
	public function jsongetNoteDescription(){
		try{
		$this->data['facilitiess'] = array();
		$this->language->load('notes/notes');
		$this->load->model('notes/notes');
		$this->load->model('setting/image'); 
		$this->load->model('notes/image'); 
		
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
		
		if (isset($this->request->post['notes_id'])) {
      		$notes_id = $this->request->post['notes_id'];
    	} 
		
		$notesData = $this->model_notes_notes->getnotes($notes_id);
		
		if($notesData != null && $notesData != ""){
			$error = true;
			$this->data['facilitiess'][] = array(
				'notes_description'  => $notesData['notes_description'],
			);
		}else{
			$this->data['facilitiess'][] = array(
				'warning'  => 'Not valid id',
			);
			$error = false;
		}	
		$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
		
		$this->response->setOutput(json_encode($value));
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices jsongetNoteDescription',
			);
			$this->model_activity_activity->addActivity('app_jsongetNoteDescription', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		}
	}

	public function jsongetdefaultDate(){
		
		try{
			
			
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
			
		if($this->config->get('config_date_picker') != null && $this->config->get('config_date_picker') != ""){
			$config_date_picker = $this->config->get('config_date_picker');
		}else{
			$config_date_picker = '0';
		}
		$this->data['facilitiess'] = array();
		$this->data['facilitiess'][] = array(
				'config_date_picker'  => $config_date_picker,
		);
		
		$value = array('results'=>$this->data['facilitiess'],'status'=>true);
			
		$this->response->setOutput(json_encode($value));
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices jsongetdefaultDate',
			);
			$this->model_activity_activity->addActivity('app_jsongetdefaultDate', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		}
	}
	
	public function jsongetdefaultTime(){
		
		try{
			
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
			
		if($this->config->get('config_time_picker') != null && $this->config->get('config_time_picker') != ""){
			$config_time_picker = $this->config->get('config_time_picker');
		}else{
			$config_time_picker = '0';
		}
		$this->data['facilitiess'] = array();
		$this->data['facilitiess'][] = array(
				'config_time_picker'  => $config_time_picker,
		);
		
		$value = array('results'=>$this->data['facilitiess'],'status'=>true);
			
		$this->response->setOutput(json_encode($value));
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices jsongetdefaultTime',
			);
			$this->model_activity_activity->addActivity('app_jsongetdefaultTime', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		}
	}
	
	
	public function jsonAddWatchNotes(){
		
		try{
		$this->data['facilitiess'] = array();
		
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
		
		$json = array();
		
		$this->load->model('notes/notes');
		$this->language->load('notes/notes');
		
		
		if (!$this->request->post['notes_description']) {
			$json['warning'] = 'Please insert required!.';
		}
		
		/*if($this->request->post['notes_pin'] != null && $this->request->post['notes_pin'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if (($this->request->post['notes_pin'] != $user_info['user_pin'])) {
				$json['warning'] = 'User Pin not valid!.';
			}
		}
		
		if($this->request->post['user_id'] != null && $this->request->post['user_id'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if (($user_info['status'] == '0')) {
				$json['warning'] = 'User not exit!';
			}
		}
		*/
			
		
		if($json['warning'] == null && $json['warning'] == ""){
			$data = array();
			
			if($this->request->post['signature'] != null && $this->request->post['signature'] != ""){
				$data['imgOutput'] = $this->request->post['signature'];
			}
			
			$data['highlighter_id'] = '0';//$this->request->post['highlighter_id'];
			$data['highlighter_value'] = $this->request->post['highlighter_value'];
			$data['notes_description'] = $this->request->post['notes_description'];
			$data['notes_pin'] = $this->request->post['notes_pin'];
			$data['user_id'] = $this->request->post['user_id'];
			
			$data['notetime'] = $this->request->post['notetime'];
			$data['text_color'] = $this->request->post['text_color'];
			$data['note_date'] = $this->request->post['note_date'];

			$data['notes_file'] = $this->request->post['notes_file'];
			$data['facilitytimezone'] = $this->request->post['facilitytimezone'];
			
			$data['keyword_file'] = $this->request->post['keyword_file'];
			$data['notes_type'] = $this->request->post['notes_type'];
			$data['strike_note_type'] = $this->request->post['strike_note_type'];
			
			
			$data['date_added'] = $this->request->post['date_added'];
			$this->model_notes_notes->jsonaddnotes($data, $this->request->post['facilities_id']);
		
			$this->data['facilitiess'][] = array(
				'warning'  => '1',
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
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices jsonAddWatchNotes',
			);
			$this->model_activity_activity->addActivity('app_jsonAddWatchNotes', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		}
	}
	
	public function jsoncheckSSL(){
		
		try{
		
		
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
		
		if($this->config->get('config_secure') == "1"){
			$configUrl = '1';
		} else { 
			$configUrl = '0';
		}
			
		
		$this->data['facilitiess'] = array();
		$this->data['facilitiess'][] = array(
			'http_check'  => $configUrl,
		);
		
		$value = array('results'=>$this->data['facilitiess'],'status'=>true);
			
		$this->response->setOutput(json_encode($value));
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices jsoncheckSSL',
			);
			$this->model_activity_activity->addActivity('app_jsoncheckSSL', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		}
	}

	public function jsonFacilityByUser(){
		
		try{
			
			
			
		$this->data['facilitiess'] = array();
		
		
		
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
		
		/*$this->request->post['facility'] = 'test';
		$this->request->post['password'] = '123456';
		$this->request->post['ipaddress'] = '125';
		$this->request->post['http_host'] = 'servitium.com';
		$this->request->post['http_referer'] = 'servitium.com';
		*/
		
		$json = array();
		
		$this->load->model('user/user'); 
		if(!$this->model_user_user->getUsersByPin($this->request->post['username'],$this->request->post['user_pin'])){
			$json['warning'] = 'No match for username and/or Pin.';
		}
		
		if (!$this->customer->apploginlogin($this->request->post['facility'], $this->request->post['password'], $this->request->post['ipaddress'])) {
			$json['warning'] = 'No match for Facility and/or Password.';
		}
		
		$this->load->model('facilities/facilities');
		
		$facility_info = $this->model_facilities_facilities->getfacilitiesByfacility($this->request->post['facility']);
			
		if ($facility_info && !$facility_info['status']) {
			$json['warning'] = 'Your account requires approval before you can login.';
		}
		
		if($facility_info['facilities_id'] != null && $facility_info['facilities_id'] != ""){
			$uquery = $this->db->query("SELECT * FROM `" . DB_PREFIX . "facilities` WHERE facilities_id = '" . (int)$facility_info['facilities_id'] . "'");
		
			$facilityResult = $uquery->row;
			
			$users = $facilityResult['users'];
			
			
				$sql = "SELECT * FROM `" . DB_PREFIX . "user` ";
				$sql .= 'where 1 = 1 ';
				if ($facility_info['facilities_id'] != null && $facility_info['facilities_id'] != "") {
					$sql .= " and FIND_IN_SET('". $facility_info['facilities_id']."', facilities) ";
				}
				$query = $this->db->query($sql);
				$results = $query->rows;
				
				if((empty($results)) && ($users == null && $users == "")){
					$json['warning'] = 'You have not users Please create user';
				}
		
		}
			
			
		if($json['warning'] == null && $json['warning'] == ""){
			
			if ($this->config->get('config_facility_online')) {
				$this->load->model('facilities/online');

				if (isset($this->request->post['ipaddress'])) {
					$ip = $this->request->post['ipaddress'];	
				} else {
					$ip = ''; 
				}

				if (isset($this->request->post['http_host'])) {
					$url = 'http://' . $this->request->post['http_host'];	
				} else {
					$url = ''; 
				}
				
				if (isset($this->request->post['http_referer'])) {
					$referer = $this->request->post['http_referer'];	
				} else {
					$referer = ''; 
				}

				
				$userId = $facility_info['facilities_id'];

				$this->model_facilities_online->whosonline($ip, $userId, $url, $referer);
			}
			
			$error = true;
			
			
			$this->load->model('setting/timezone');
			$timezone_info = $this->model_setting_timezone->gettimezone($facility_info['timezone_id']);
			
			if($this->config->get('config_date_picker') != null && $this->config->get('config_date_picker') != ""){
				$config_date_picker = $this->config->get('config_date_picker');
			}else{
				$config_date_picker = '0';
			}
			
			if($this->config->get('config_time_picker') != null && $this->config->get('config_time_picker') != ""){
				$config_time_picker = $this->config->get('config_time_picker');
			}else{
				$config_time_picker = '0';
			}
			
			if($this->config->get('config_task_status') != null && $this->config->get('config_task_status') != ""){
				$config_task_status = $this->config->get('config_task_status');
			}else{
				$config_task_status = '0';
			}
			
			$config_admin_limit1 = $this->config->get('config_android_front_limit');
			if($config_admin_limit1 != null && $config_admin_limit1 != ""){
				$config_admin_limit = $config_admin_limit1;
			}else{
				$config_admin_limit = "25";
			}
			
			
			if ($this->request->post['searchdate'] != null && $this->request->post['searchdate'] != "") {
				$searchdate = date('Y-m-d', strtotime($this->request->post['searchdate']));
			} 
			
			$data = array(
				'facilities_id' => $facility_info['facilities_id'],
				'searchdate' => $searchdate,
				'advance_search' => '1',
			);
		
			$this->load->model('notes/notes');
			$notes_total = $this->model_notes_notes->getTotalnotess($data);
			
			if($this->config->get('config_secure') == "1"){
				$configUrl = '1';
				$configUrl2 = $this->config->get('config_ssl').'index.php?route=services/';
			} else { 
				$configUrl = '0';
				$configUrl2 = $this->config->get('config_url').'index.php?route=services/';
			}
			
			$notes_total2 = ceil($notes_total/$config_admin_limit);
			
			if($this->config->get('config_all_notification') != null && $this->config->get('config_all_notification') != ""){
				$config_all_notification = $this->config->get('config_all_notification');
			}else{
				$config_all_notification = '0';
			}
			
			if($this->config->get('config_task_sms') != null && $this->config->get('config_task_sms') != ""){
				$config_task_sms = $this->config->get('config_task_sms');
			}else{
				$config_task_sms = '0';
			}
			
			if($this->config->get('config_task_notification') != null && $this->config->get('config_task_notification') != ""){
				$config_task_notification = $this->config->get('config_task_notification');
			}else{
				$config_task_notification = '0';
			}
			
			if($this->config->get('config_task_email') != null && $this->config->get('config_task_email') != ""){
				$config_task_email = $this->config->get('config_task_email');
			}else{
				$config_task_email = '0';
			}
			
			$this->data['facilitiess'][] = array(
				'facility'  => $facility_info['facility'],
				'timezone_value'  => $timezone_info['timezone_value'],
				'facilities_id'       => $facility_info['facilities_id'],
				'config_date_picker'  => $config_date_picker,
				'config_time_picker'  => $config_time_picker,
				'config_task_status'  => $config_task_status,
				'config_admin_limit'  => $config_admin_limit,
				'notes_total'  => $notes_total2,
				'http_check'  => $configUrl,
				'http_url'  => $configUrl2,
				'config_all_notification'  => $config_all_notification,
				'config_task_sms'  => $config_task_sms,
				'config_task_notification'  => $config_task_notification,
				'config_task_email'  => $config_task_email,
				'username'  => $this->request->post['username'],
				'user_pin'  => $this->request->post['user_pin'],
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

			
			}catch(Exception $e){
				
				$this->load->model('activity/activity');
				$activity_data2 = array(
					'data' => 'Error in appservices jsonFacilityByUser',
				);
				$this->model_activity_activity->addActivity('app_jsonFacilityByUser', $activity_data2);
			
			//echo 'Caught exception: ',  $e->getMessage(), "\n";
			
			}		
	}
	
	public function jsonUsersByFacilityName(){
		
		try{
			
		$this->data['facilitiess'] = array();
		
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
		
		$this->load->model('facilities/facilities');
		
		$facility = $this->request->post['facility'];
		$facility_info = $this->model_facilities_facilities->getfacilitiesByfacility($facility);
		
		if($facility_info != null && $facility_info != ""){
			$this->load->model('user/user'); 
			$users = $this->model_user_user->getUsersByFacility($facility_info['facilities_id']);
			
			foreach ($users as $user) {
						
				$this->data['facilitiess'][] = array(
					'user_id'    => $user['user_id'],
					'username'   => $user['username'],
					'firstname'   => $user['firstname'],
					'lastname'   => $user['lastname'],
					'user_pin'   => $user['user_pin'],
					'email'   => $user['email'],
				);
			}
			$value = array('results'=>$this->data['facilitiess'],'status'=>true);
		}else{
			$error = false;
			$value = array('results'=>"No User Found",'status'=>false);
		}
		
		$this->response->setOutput(json_encode($value));
		
		}catch(Exception $e){
				
				$this->load->model('activity/activity');
				$activity_data2 = array(
					'data' => 'Error in appservices jsonUsersByFacilityName',
				);
				$this->model_activity_activity->addActivity('app_jsonUsersByFacilityName', $activity_data2);
			
			//echo 'Caught exception: ',  $e->getMessage(), "\n";
			
			}
	
	}
	

	public function jsonachaments(){
		
		try{
		$this->data['facilitiess'] = array();
		
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
		
		if($this->request->post['notes_id'] != null && $this->request->post['notes_id'] != ""){
			$this->load->model('notes/notes'); 
    	
			$allimages = $this->model_notes_notes->getImages($this->request->post['notes_id']);
			$images = array();
			foreach ($allimages as $image) {
				
				$extension = $image['notes_media_extention'];
				
				$this->data['facilitiess'][] = array(
					'keyImageSrc' =>'img',
					'media_user_id' => $image['media_user_id'],
					'notes_media_extention' => $image['notes_media_extention'],
					'media_date_added' => date($this->language->get('date_format_short_2'), strtotime($image['media_date_added'])),
					'media_signature' => $image['media_signature'],
					'media_pin' => $image['media_pin'],
					'notes_file_url' => $image['notes_file']
					
				);
			}
			
		
			$value = array('results'=>$this->data['facilitiess'],'status'=>true);
		}else{
			$error = false;
			$value = array('results'=>"No User Found",'status'=>false);
		}
		
		$this->response->setOutput(json_encode($value));
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices jsonachaments',
			);
			$this->model_activity_activity->addActivity('app_jsonachaments', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		} 
	
	}
	
	
	public function searchTags(){
		$this->data['facilitiess'] = array();

		
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
		
		//if($this->request->get['emp_tag_id'] != null && $this->request->get['emp_tag_id'] != "") {
			$this->load->model('notes/tags');

			if (isset($this->request->get['emp_tag_id'])) {
				$emp_tag_id = $this->request->get['emp_tag_id'];
			} else {
				$emp_tag_id = '';
			}

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];	
			} else {
				$limit = CONFIG_LIMIT;	
			}			

			$data = array(
				'emp_tag_id'  => $emp_tag_id,
				'status'        => 1,
				'start'        => 0,
				'limit'        => $limit
			);
			
			$tags = $this->model_notes_tags->getTags($data);
			
			foreach ($tags as $tag) {
				$this->data['facilitiess'][] = array(
					'name' => $tag['emp_tag_id'].': '.$tag['emp_first_name'].' '.$tag['emp_last_name'],
					'emp_tag_id' => $tag['emp_tag_id'],
					'tags_id' => $tag['tags_id'],
					'tags_status_in' => $tag['tags_status_in'],
				);	
			}
		//}

		
		$value = array('results'=>$this->data['facilitiess'],'status'=>true);
		
		$this->response->setOutput(json_encode($value));
	}
	
	
	public function searchfullTags(){
		$this->data['facilitiess'] = array();
		
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

		//if($this->request->get['emp_tag_id'] != null && $this->request->get['emp_tag_id'] != "") {
			$this->load->model('notes/tags');

			if (isset($this->request->get['emp_tag_id'])) {
				$emp_tag_id = $this->request->get['emp_tag_id'];
			} else {
				$emp_tag_id = '';
			}

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];	
			} else {
				//$limit = 20;	
			}			

			$data = array(
				'emp_tag_id'  => $emp_tag_id,
				'status'        => 1,
				//'start'        => 0,
				//'limit'        => $limit
			);
			
			$tags = $this->model_notes_tags->getTags($data);
			
			foreach ($tags as $tag) {
				$this->data['facilitiess'][] = array(
					'name' => $tag['emp_tag_id'].': '.$tag['emp_first_name'].' '.$tag['emp_last_name'],
					'emp_tag_id' => $tag['emp_tag_id'],
					'tags_id' => $tag['tags_id'],
					'role_call' => $tag['role_call'],
					'tags_status_in' => $tag['tags_status_in'],
				);	
			}
		//}

		
		$value = array('results'=>$this->data['facilitiess'],'status'=>true);
		
		$this->response->setOutput(json_encode($value));
	}
	
	public function unlockUser() {
		
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
		
		$this->language->load('notes/notes');
		if ($this->request->post['user_id'] == '') {
			$json['warning'] = $this->language->get('error_required');
		}

		if ($this->request->post['user_id'] != '') {
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if(empty($user_info)){
				$json['warning'] = $this->language->get('error_required');
			}
		}
		
			if ($this->request->post['notes_pin'] == '') {
				$json['notes_pin'] = $this->language->get('error_required');
			}
			if($this->request->post['notes_pin'] != null && $this->request->post['notes_pin'] != ""){
				$this->load->model('user/user');
				
				$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

				if (($this->request->post['notes_pin'] != $user_info['user_pin'])) {
					$json['warning'] = $this->language->get('error_exists');
				}
			}
		
		
		if($json['warning'] == null && $json['warning'] == ""){
			$this->data['facilitiess'][] = array(
				'warning'  => '1',
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
	
	public function jsonFacilityTags(){
		
		try{
		$this->data['facilitiess'] = array();
		
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
		
			$this->load->model('setting/tags');
			
			if($this->request->post['allclients'] != '1'){
				$discharge = '1';
				$all_record = '1';
				
			}
			
			$filter_name = explode(':',$this->request->post['filter_name']);
			
			$data = array(
				'emp_tag_id_2' => trim($filter_name[0]),
				'facilities_id' => $this->request->post['facilities_id'],
				'status' => '1',
				'discharge' => $discharge,
				'all_record' => $all_record,
				'sort' => 'emp_tag_id',
				'order' => 'ASC',
				//'start'       => 0,
				//'limit'       => 10
			);

			$results = $this->model_setting_tags->getTags($data);
		
    	
			foreach ($results as $result) {
						
				$this->data['facilitiess'][] = array(
					'tags_id' => $result['tags_id'], 
					'name' => $result['emp_tag_id'].': '.$result['emp_first_name'].' '. $result['emp_last_name'], 
					'emp_tag_id' => $result['emp_tag_id'], 
					'role_call' => $result['role_call'],
					'tags_status_in' => $result['tags_status_in'],
					'discharge' => $result['discharge'], 
				);
			}
			
			$value = array('results'=>$this->data['facilitiess'],'status'=>true);
		
		
		$this->response->setOutput(json_encode($value));
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices jsonTag',
			);
			$this->model_activity_activity->addActivity('app_jsonTags', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		} 
	
	}
	
	
	public function jsonforms(){
		
		try{
		$this->data['facilitiess'] = array();
		
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
		
		$this->language->load('notes/notes'); 
		if($this->request->post['notes_id'] != null && $this->request->post['notes_id'] != ""){
			$this->load->model('notes/notes'); 
    	
			$notesID = (string) $this->request->post['notes_id'];
			
				$allforms = $this->model_notes_notes->getforms($this->request->post['notes_id']);
				
				//var_dump($allforms);
				$forms = array();
				foreach ($allforms as $allform) {
					if($allform['form_type'] == '1'){
						$form_url =	str_replace('&amp;', '&', $this->url->link('services/noteform/forminsert/', '' . 'incidentform_id=' . $allform['form_type_id']. '&facilities_id=' . $allform['facilities_id']. '&notes_id=' . $allform['notes_id']));
					}
					
					if($allform['form_type'] == '2'){
						$form_url =	str_replace('&amp;', '&', $this->url->link('services/noteform/noteschecklistform', '' . 'checklist_id=' . $allform['form_type_id']. '&facilities_id=' . $allform['facilities_id']. '&notes_id=' . $allform['notes_id']));
					}
					
					if($allform['form_type'] == '3'){
					$form_url =	str_replace('&amp;', '&', $this->url->link('services/form', '' . 'forms_id=' . $allform['forms_id']. '&facilities_id=' . $allform['facilities_id']. '&notes_id=' . $allform['notes_id']. '&forms_design_id=' . $allform['custom_form_type']));
					}
					if($note_info['is_archive'] == '4'){
					$form_url =	str_replace('&amp;', '&', $this->url->link('services/form', '' . 'forms_id=' . $allform['forms_id']. '&facilities_id=' . $allform['facilities_id']. '&notes_id=' . $allform['notes_id']. '&forms_design_id=' . $allform['custom_form_type']. '&is_archive=' . $note_info['is_archive']));
					}
					
					if($allform['date_added'] != null && $allform['date_added'] != "0000-00-00 00:00:00"){
						$form_date_added = date($this->language->get('date_format_short_2'), strtotime($allform['date_added']));
					}else{
						$form_date_added = '';
					}
					
					$this->data['facilitiess'][] = array(
							'form_type_id' => $allform['form_type_id'],
							'notes_id' => $allform['notes_id'],
							'form_type' => $allform['form_type'],
							'user_id' => $allform['user_id'],
							'signature' => $allform['signature'],
							'notes_pin' => $allform['notes_pin'],
							'incident_number' => $allform['incident_number'],
							'form_date_added' => $form_date_added,
							'href'        => $form_url
							
						); 
				}
				
					
		
			$value = array('results'=>$this->data['facilitiess'],'status'=>true);
		}else{
			$error = false;
			$value = array('results'=>$this->data['facilitiess'],'status'=>true);
		}
		
		$this->response->setOutput(json_encode($value));
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices jsonforms',
			);
			$this->model_activity_activity->addActivity('app_jsonforms', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		} 
	
	}
	
	
	public function jsongetallAttchments(){
		
		try{
			
		$this->data['facilitiess'] = array();
		
		
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
		
		$this->language->load('notes/notes'); 
		if($this->request->post['notes_id'] != null && $this->request->post['notes_id'] != ""){
			$this->load->model('notes/notes'); 
    	
				$notesID = (string) $this->request->post['notes_id'];
				
				$note_info = $this->model_notes_notes->getNote($this->request->post['notes_id']);
				
				if($note_info['is_approval_required_forms_id'] > 0 ){
					
					if($note_info['text_color_cut'] != '1'){
						$muser_id = $note_info['user_id'];
						$msignature = $note_info['signature'];
						$mnotes_pin = $note_info['notes_pin'];
						$notes_type = $note_info['notes_type'];
						
						if($note_info['note_date'] != null && $note_info['note_date'] != "0000-00-00 00:00:00"){
							$media_date_added = date($this->language->get('date_format_short_2'), strtotime($note_info['note_date']));
						}else{
							$media_date_added = '';
						}
						
					}else{
					
						$muser_id = $note_info['strike_user_id'];
						$msignature = $note_info['strike_signature'];
						$mnotes_pin = $note_info['strike_pin'];
						$notes_type = $note_info['strike_note_type'];
						
						if($note_info['strike_date_added'] != null && $note_info['strike_date_added'] != "0000-00-00 00:00:00"){
							$media_date_added = date($this->language->get('date_format_short_2'), strtotime($note_info['strike_date_added']));
						}else{
							$media_date_added = '';
						}
					}
					
					
					if($note_info['user_id'] == SYSTEM_GENERATED ){
						$auto_generate = '1';
					}else{
						$auto_generate = '0';
					}
					
					$is_approvaltask_url =	'';
					$task_id = $note_info['task_id'];
					
					//$approvaltask = $this->model_notes_notes->getapprovaltask($result['task_id']);
					
					$this->data['facilitiess'][] = array(
						'form_type_id' =>'img_approvaltask',
						'notes_id' => $this->request->post['notes_id'],
						'user_id' => $muser_id,
						'auto_generate' => $auto_generate,
						'incident_number' => '',
						'form_type' => '',
						'form_date_added' => $media_date_added,
						'signature' => $msignature,
						'notes_pin' => $mnotes_pin,
						'notes_type' => $notes_type,
						'href' => $is_approvaltask_url,
						'audio_attach_url' => '',
						'current_locations_latitude' => '',
						'current_locations_longitude' => '',
						'travel_state' =>'',
						'location_tracking_route' => '',
						'location_tracking_url' => '',
						'waypoint_google_url' => '',
						'notes_by_task_id' => '',
						'locations_id' => '',
						'task_type' => '',
						'task_content' => '',
						'task_time' => '',
						'media_url' => '',
						'capacity' => '',
						'location_name' => '',
						'location_type' => '',
						'notes_task_type' => '',
						'tags_id' => '',
						'drug_name' => '',
						'dose' => '',
						'drug_type' => '',
						'quantity' =>'',
						'frequency' => '',
						'instructions' => '',
						'count' =>'',
						'createtask_by_group_id' => '',
						'task_comments' => '',
						'medication_file_upload' => '',
						'date_added' => '',
						'is_tag_url' => '',
						'is_census_url' => '',
						'print_url' => '',
						'google_map_image_url' => '',
						'task_id' => $task_id,
						'is_approval_required_forms_id' => $note_info['is_approval_required_forms_id'],
						'attachment_type' =>'img_approvaltask',
						
					);
				
				}
				
				if($note_info['task_type'] == "6"){
					
					if($note_info['text_color_cut'] != '1'){
						$muser_id = $note_info['user_id'];
						$msignature = $note_info['signature'];
						$mnotes_pin = $note_info['notes_pin'];
						$notes_type = $note_info['notes_type'];
						
						if($note_info['note_date'] != null && $note_info['note_date'] != "0000-00-00 00:00:00"){
							$media_date_added = date($this->language->get('date_format_short_2'), strtotime($note_info['note_date']));
						}else{
							$media_date_added = '';
						}
						
					}else{
					
						$muser_id = $note_info['strike_user_id'];
						$msignature = $note_info['strike_signature'];
						$mnotes_pin = $note_info['strike_pin'];
						$notes_type = $note_info['strike_note_type'];
						
						if($note_info['strike_date_added'] != null && $note_info['strike_date_added'] != "0000-00-00 00:00:00"){
							$media_date_added = date($this->language->get('date_format_short_2'), strtotime($note_info['strike_date_added']));
						}else{
							$media_date_added = '';
						}
					}
					
					
					if($note_info['user_id'] == SYSTEM_GENERATED ){
						$auto_generate = '1';
					}else{
						$auto_generate = '0';
					}
					
					$is_approvaltask_url =	'';
					$task_id = $note_info['task_id'];
					
					//$approvaltask = $this->model_notes_notes->getapprovaltask($result['task_id']);
					
					$this->data['facilitiess'][] = array(
						'form_type_id' =>'img_approvaltask',
						'notes_id' => $this->request->post['notes_id'],
						'user_id' => $muser_id,
						'auto_generate' => $auto_generate,
						'incident_number' => '',
						'form_type' => '',
						'form_date_added' => $media_date_added,
						'signature' => $msignature,
						'notes_pin' => $mnotes_pin,
						'notes_type' => $notes_type,
						'href' => $is_approvaltask_url,
						'audio_attach_url' => '',
						'current_locations_latitude' => '',
						'current_locations_longitude' => '',
						'travel_state' =>'',
						'location_tracking_route' => '',
						'location_tracking_url' => '',
						'waypoint_google_url' => '',
						'notes_by_task_id' => '',
						'locations_id' => '',
						'task_type' => '',
						'task_content' => '',
						'task_time' => '',
						'media_url' => '',
						'capacity' => '',
						'location_name' => '',
						'location_type' => '',
						'notes_task_type' => '',
						'tags_id' => '',
						'drug_name' => '',
						'dose' => '',
						'drug_type' => '',
						'quantity' =>'',
						'frequency' => '',
						'instructions' => '',
						'count' =>'',
						'createtask_by_group_id' => '',
						'task_comments' => '',
						'medication_file_upload' => '',
						'date_added' => '',
						'is_tag_url' => '',
						'is_census_url' => '',
						'print_url' => '',
						'google_map_image_url' => '',
						'task_id' => $task_id,
						'is_approval_required_forms_id' => '',
						'attachment_type' =>'img_approvaltask_2',
						
					);
				
				}
				
				if($note_info['user_file'] != null && $note_info['user_file'] != ""){
					
					if($note_info['text_color_cut'] != '1'){
						$muser_id = $note_info['user_id'];
						$msignature = $note_info['signature'];
						$mnotes_pin = $note_info['notes_pin'];
						$notes_type = $note_info['notes_type'];
						
						if($note_info['note_date'] != null && $note_info['note_date'] != "0000-00-00 00:00:00"){
							$media_date_added = date($this->language->get('date_format_short_2'), strtotime($note_info['note_date']));
						}else{
							$media_date_added = '';
						}
						
					}else{
					
						$muser_id = $note_info['strike_user_id'];
						$msignature = $note_info['strike_signature'];
						$mnotes_pin = $note_info['strike_pin'];
						$notes_type = $note_info['strike_note_type'];
						
						if($note_info['strike_date_added'] != null && $note_info['strike_date_added'] != "0000-00-00 00:00:00"){
							$media_date_added = date($this->language->get('date_format_short_2'), strtotime($note_info['strike_date_added']));
						}else{
							$media_date_added = '';
						}
					}
					
					
					if($note_info['user_id'] == SYSTEM_GENERATED ){
						$auto_generate = '1';
					}else{
						$auto_generate = '0';
					}
					 
					
					$this->data['facilitiess'][] = array(
						'form_type_id' =>'img_user_file',
						'notes_id' => $this->request->post['notes_id'],
						'user_id' => $muser_id,
						'auto_generate' => $auto_generate,
						'incident_number' => '',
						'form_type' => '',
						'form_date_added' => $media_date_added,
						'signature' => $msignature,
						'notes_pin' => $mnotes_pin,
						'notes_type' => $notes_type,
						'href' => $note_info['user_file'],
						'audio_attach_url' => '',
						'current_locations_latitude' => '',
						'current_locations_longitude' => '',
						'travel_state' =>'',
						'location_tracking_route' => '',
						'location_tracking_url' => '',
						'waypoint_google_url' => '',
						'notes_by_task_id' => '',
						'locations_id' => '',
						'task_type' => '',
						'task_content' => '',
						'task_time' => '',
						'media_url' => '',
						'capacity' => '',
						'location_name' => '',
						'location_type' => '',
						'notes_task_type' => '',
						'tags_id' => '',
						'drug_name' => '',
						'dose' => '',
						'drug_type' => '',
						'quantity' =>'',
						'frequency' => '',
						'instructions' => '',
						'count' =>'',
						'createtask_by_group_id' => '',
						'task_comments' => '',
						'medication_file_upload' => '',
						'date_added' => '',
						'is_tag_url' => '',
						'is_census_url' => '',
						'print_url' => '',
						'google_map_image_url' => '',
						'task_id' => '',
						'is_approval_required_forms_id' => '',
						'attachment_type' =>'img_user_file',
						
					);
				
				}
				
				
				$geolocation_info = $this->model_notes_notes->getGeolocation($note_info['notes_id']);
					
				if($geolocation_info != null && $geolocation_info != ""){
					
					if($note_info['text_color_cut'] != '1'){
						$muser_id = $note_info['user_id'];
						$msignature = $note_info['signature'];
						$mnotes_pin = $note_info['notes_pin'];
						$notes_type = $note_info['notes_type'];
						
						if($note_info['note_date'] != null && $note_info['note_date'] != "0000-00-00 00:00:00"){
							$media_date_added = date($this->language->get('date_format_short_2'), strtotime($note_info['note_date']));
						}else{
							$media_date_added = '';
						}
						
					}else{
					
						$muser_id = $note_info['strike_user_id'];
						$msignature = $note_info['strike_signature'];
						$mnotes_pin = $note_info['strike_pin'];
						$notes_type = $note_info['strike_note_type'];
						
						if($note_info['strike_date_added'] != null && $note_info['strike_date_added'] != "0000-00-00 00:00:00"){
							$media_date_added = date($this->language->get('date_format_short_2'), strtotime($note_info['strike_date_added']));
						}else{
							$media_date_added = '';
						}
					}
					
					if($geolocation_info['travel_state'] != '0'){
						$google_url = '';
						$google_url = $geolocation_info['google_url'];
					}else{
						$google_url = $geolocation_info['google_url'];
						//$google_url = '';
					}
					
					
					
					if($geolocation_info['travel_state'] == '1'){
						$location_tracking_route = str_replace('&quot;', '"', $geolocation_info['location_tracking_route']);
					}else{
						$location_tracking_route = '';
					}
					if($geolocation_info['travel_state'] == '1'){
						$location_tracking_url = $geolocation_info['location_tracking_url'];
						$google_map_image_url = $geolocation_info['google_map_image_url'];
					}else{
						$location_tracking_url = '';
						$google_map_image_url = '';
					}
					
					if($geolocation_info['travel_state'] == '0'){
						$waypoint_google_url = $geolocation_info['waypoint_google_url'];
					}else{
						$waypoint_google_url = '';
					}
					
					if($note_info['user_id'] == SYSTEM_GENERATED ){
						$auto_generate = '1';
					}else{
						$auto_generate = '0';
					}
					
					$drop_tags = array();
					if($geolocation_info['tags_id'] != null && $geolocation_info['tags_id'] != ""){
						$this->load->model('setting/tags');
						
						$this->load->model('notes/notes');
						
						$transport_tags1 = explode(',',$geolocation_info['tags_id']);
						
						$transport_tags = '';
						foreach ($transport_tags1 as $tag1) {
							$tags_info1 = $this->model_setting_tags->getTag($tag1);

							if($tags_info1['emp_first_name']){
								$emp_tag_id = $tags_info1['emp_tag_id'].':'.$tags_info1['emp_first_name'];
							}else{
								$emp_tag_id = $tags_info1['emp_tag_id'];
							}
								
							if ($tags_info1) {
								$transport_tags .= $emp_tag_id.', ';

							}
							
							$pickup_info1 = $this->model_notes_notes->getTagpickup($tag1, '1', $note_info['task_group_by']);
							
							
							$drop_tags[] = array(
								'emp_tag_id' => $emp_tag_id,
								'pickup_locations_address' => $pickup_info1['pickup_locations_address'],
								'pickup_locations_latitude' => $pickup_info1['pickup_locations_latitude'],
								'pickup_locations_longitude' => $pickup_info1['pickup_locations_longitude'],
								
								'dropoff_locations_address' => $geolocation_info['dropoff_locations_address'],
								'dropoff_locations_latitude' => $geolocation_info['dropoff_locations_latitude'],
								'dropoff_locations_longitude' => $geolocation_info['dropoff_locations_longitude'],
								
								'current_locations_address' => $pickup_info1['current_locations_address'],
								'current_locations_latitude' => $pickup_info1['current_locations_latitude'],
								'current_locations_longitude' => $pickup_info1['current_locations_longitude'],
							);
						}
						
						//$tags_id = $transport_tags;
					}
					
					
					$pick_up_tags_id = "";
					if($geolocation_info['pick_up_tags_id'] != null && $geolocation_info['pick_up_tags_id'] != ""){
						$this->load->model('setting/tags');
						$transport_tags1 = explode(',',$geolocation_info['pick_up_tags_id']);
						
						$transport_tags13333 = '';
						foreach ($transport_tags1 as $tag1) {
							$tags_info12 = $this->model_setting_tags->getTag($tag1);

							if($tags_info12['emp_first_name']){
								$emp_tag_id = $tags_info12['emp_tag_id'].':'.$tags_info12['emp_first_name'];
							}else{
								$emp_tag_id = $tags_info12['emp_tag_id'];
							}
								
							if ($tags_info12) {
								$transport_tags13333 .= $emp_tag_id.', ';

							}
						}
						
						$pick_up_tags_id = $transport_tags13333;
					}
					
					$this->data['facilitiess'][] = array(
						'form_type_id' =>'img_location',
						'notes_id' => $this->request->post['notes_id'],
						'user_id' => $muser_id,
						'auto_generate' => $auto_generate,
						'incident_number' => '',
						'form_type' => '',
						'form_date_added' => $media_date_added,
						'signature' => $msignature,
						'notes_pin' => $mnotes_pin,
						'notes_type' => $notes_type,
						'href' => $geolocation_info['google_url'],
						'audio_attach_url' => $geolocation_info['current_google_url'],
						'current_locations_latitude' => $geolocation_info['current_locations_latitude'],
						'current_locations_longitude' => $geolocation_info['current_locations_longitude'],
						'travel_state' => $geolocation_info['travel_state'],
						'location_tracking_route' => $location_tracking_route,
						'location_tracking_url' => $location_tracking_url,
						'google_map_image_url' => $google_map_image_url,
						'waypoint_google_url' => $waypoint_google_url,
						'notes_by_task_id' => '',
						'locations_id' => '',
						'task_type' => '',
						'task_content' => '',
						'task_time' => '',
						'media_url' => '',
						'capacity' => '',
						'location_name' => '',
						'location_type' => '',
						'notes_task_type' => '',
						
						'drug_name' => '',
						'dose' => '',
						'drug_type' => '',
						'quantity' =>'',
						'frequency' => '',
						'instructions' => '',
						'count' =>'',
						'createtask_by_group_id' => '',
						'task_comments' => '',
						'medication_file_upload' => '',
						'date_added' => '',
						'is_tag_url' => '',
						'is_census_url' => '',
						'print_url' => '',
						'task_id' => '',
						'is_approval_required_forms_id' => '',
						'attachment_type' =>'img_location',
						
						
						'pick_up_tags_id' => $pick_up_tags_id,
						'is_pick_up' => $geolocation_info['is_pick_up'],
						'is_drop_off' => $geolocation_info['is_drop_off'],		

						/*
						'pickup_locations_address_2' => $geolocation_info['pickup_locations_address_2'],
						'pickup_locations_latitude_2' => $geolocation_info['pickup_locations_latitude_2'],
						'pickup_locations_longitude_2' => $geolocation_info['pickup_locations_longitude_2'],
						'dropoff_locations_address_2' => $geolocation_info['dropoff_locations_address_2'],
						'dropoff_locations_latitude_2' => $geolocation_info['dropoff_locations_latitude_2'],
						'dropoff_locations_longitude_2' => $geolocation_info['dropoff_locations_longitude_2'],
						
						'pickup_locations_address' => $geolocation_info['pickup_locations_address'],
						'pickup_locations_latitude' => $geolocation_info['pickup_locations_latitude'],
						'pickup_locations_longitude' => $geolocation_info['pickup_locations_longitude'],
						'dropoff_locations_address' => $geolocation_info['dropoff_locations_address'],
						'dropoff_locations_latitude' => $geolocation_info['dropoff_locations_latitude'],
						'dropoff_locations_longitude' => $geolocation_info['dropoff_locations_longitude'],	
						'tags_id' => $tags_id,*/
						'drop_tags' => $drop_tags,
						
					);
				
				}
				
				if($note_info['generate_report'] == '3'){
					
						$is_tag_url =	str_replace('&amp;', '&', $this->url->link('notes/printbedcheck&is_bedchk=1', ''. '&notes_id=' . $note_info['notes_id']. '&facilities_id=' . $note_info['facilities_id']. '&is_archive=' . $note_info['is_archive']));
						
						$incident_number = 'Bed Check Report';
					
						$user_id = $note_info['user_id'];
						$signature = $note_info['signature'];
						$notes_pin = $note_info['notes_pin'];
						$notes_type = $note_info['notes_type'];
						
						if($note_info['note_date'] != null && $note_info['note_date'] != "0000-00-00 00:00:00"){
							$form_date_added = date($this->language->get('date_format_short_2'), strtotime($note_info['note_date']));
						}else{
							$form_date_added = '';
						}
						
						if($note_info['user_id'] == SYSTEM_GENERATED ){
							$auto_generate = '1';
						}else{
							$auto_generate = '0';
						}
						
					$this->data['facilitiess'][] = array(
							'form_type_id' => 'openpdf',
							'notes_id' => '',
							'form_type' => '',
							'notes_type' => $notes_type,
							'user_id' => $user_id,
							'auto_generate' => $auto_generate,
							'signature' => $signature,
							'notes_pin' => $notes_pin,
							'incident_number' => $incident_number,
							'form_date_added' => $form_date_added,
							'href'        => $is_tag_url,
							'audio_attach_url' => '',
							'notes_by_task_id' => '',
							'locations_id' => '',
							'task_type' => '',
							'task_content' => '',
							'task_time' => '',
							'media_url' => '',
							'capacity' => '',
							'location_name' => '',
							'location_type' => '',
							'notes_task_type' => '',
							'tags_id' => '',
							'drug_name' => '',
							'dose' => '',
							'drug_type' => '',
							'quantity' =>'',
							'frequency' => '',
							'instructions' => '',
							'count' =>'',
							'createtask_by_group_id' => '',
							'task_comments' => '',
							'medication_file_upload' => '',
							'date_added' => '',
							'is_census_url' => '',
							'print_url' =>$is_tag_url,
							'is_tag_url' => $is_tag_url,
							'current_locations_latitude' => '',
							'current_locations_longitude' => '',
							'location_tracking_route' => '',
							'location_tracking_url' => '',
							'google_map_image_url' => '',
							'waypoint_google_url' => '',
							'travel_state' => '',
							'task_id' => '',
							'is_approval_required_forms_id' => '',
							'attachment_type' =>'openpdf',
						); 
						
				}
				
				if($note_info['is_tag'] != '0'){
					
					if($note_info['form_type'] == '2'){
						$is_tag_url =	str_replace('&amp;', '&', $this->url->link('services/resident/updateclient&isandroid=1', ''. '&tags_id=' . $note_info['is_tag']. '&notes_id=' . $note_info['notes_id']. '&facilities_id=' . $note_info['facilities_id']. '&is_archive=' . $note_info['is_archive']));
						
						$incident_number = 'Update Client';
						
						$print_url =	str_replace('&amp;', '&', $this->url->link('notes/tags/printform&isandroid=1', ''. '&tags_id=' . $note_info['is_tag']. '&notes_id=' . $note_info['notes_id']. '&facilities_id=' . $note_info['facilities_id']. '&is_archive=' . $note_info['is_archive']));
					}
					
					if($note_info['form_type'] == '1'){
						$is_tag_url =	str_replace('&amp;', '&', $this->url->link('services/resident/tagsmedication&isandroid=1', ''. '&tags_id=' . $note_info['is_tag']. '&notes_id=' . $note_info['notes_id']. '&facilities_id=' . $note_info['facilities_id']. '&is_archive=' . $note_info['is_archive']));
						
						$incident_number = 'Health Form';
						
						
						$print_url =	str_replace('&amp;', '&', $this->url->link('notes/tags/printmedicationform&isandroid=1', ''. '&tags_id=' . $note_info['is_tag']. '&notes_id=' . $note_info['notes_id']. '&facilities_id=' . $note_info['facilities_id']. '&is_archive=' . $note_info['is_archive']));
					}
					
					
					if($note_info['form_type'] == '4'){
						$is_tag_url =	str_replace('&amp;', '&', $this->url->link('notes/case&isandroid=1', ''. '&tags_id=' . $note_info['is_tag']. '&notes_id=' . $note_info['notes_id']. '&facilities_id=' . $note_info['facilities_id']. '&is_archive=' . $note_info['is_archive']));
						
						$incident_number = 'Discharge Report';
						
						
						$print_url =	str_replace('&amp;', '&', $this->url->link('notes/case&isandroid=1', ''. '&tags_id=' . $note_info['is_tag']. '&notes_id=' . $note_info['notes_id']. '&facilities_id=' . $note_info['facilities_id']. '&is_archive=' . $note_info['is_archive']));
					}
					
					
						$user_id = $note_info['user_id'];
						$signature = $note_info['signature'];
						$notes_pin = $note_info['notes_pin'];
						$notes_type = $note_info['notes_type'];
						
						if($note_info['note_date'] != null && $note_info['note_date'] != "0000-00-00 00:00:00"){
							$form_date_added = date($this->language->get('date_format_short_2'), strtotime($note_info['note_date']));
						}else{
							$form_date_added = '';
						}
						
						if($note_info['user_id'] == SYSTEM_GENERATED ){
							$auto_generate = '1';
						}else{
							$auto_generate = '0';
						}
						
					$this->data['facilitiess'][] = array(
							'form_type_id' => 'form',
							'notes_id' => '',
							'form_type' => $note_info['form_type'],
							'notes_type' => $notes_type,
							'auto_generate' => $auto_generate,
							'user_id' => $user_id,
							'signature' => $signature,
							'notes_pin' => $notes_pin,
							'incident_number' => $incident_number,
							'form_date_added' => $form_date_added,
							'href'        => $is_tag_url,
							'audio_attach_url' => '',
							'notes_by_task_id' => '',
							'locations_id' => '',
							'task_type' => '',
							'task_content' => '',
							'task_time' => '',
							'media_url' => '',
							'capacity' => '',
							'location_name' => '',
							'location_type' => '',
							'notes_task_type' => '',
							'tags_id' => '',
							'drug_name' => '',
							'dose' => '',
							'drug_type' => '',
							'quantity' =>'',
							'frequency' => '',
							'instructions' => '',
							'count' =>'',
							'createtask_by_group_id' => '',
							'task_comments' => '',
							'medication_file_upload' => '',
							'date_added' => '',
							'is_census_url' => '',
							'print_url' => $print_url,
							'is_tag_url' => $is_tag_url,
							'current_locations_latitude' => '',
							'current_locations_longitude' => '',
							'location_tracking_route' => '',
							'travel_state' => '',
							'location_tracking_url' => '',
							'google_map_image_url' => '',
							'waypoint_google_url' => '',
							'task_id' => '',
							'is_approval_required_forms_id' => '',
							'attachment_type' =>'is_client',
						); 
						
				}
				
				if($note_info['is_census'] == '1'){
					$is_census_url =	str_replace('&amp;', '&', $this->url->link('resident/dailycensus/censusdetail&isandroid=1', ''. '&notes_id=' . $note_info['notes_id']. '&facilities_id=' . $note_info['facilities_id']));
					
						$user_id = $note_info['user_id'];
						$signature = $note_info['signature'];
						$notes_pin = $note_info['notes_pin'];
						$notes_type = $note_info['notes_type'];
						
						if($note_info['note_date'] != null && $note_info['note_date'] != "0000-00-00 00:00:00"){
							$form_date_added = date($this->language->get('date_format_short_2'), strtotime($note_info['note_date']));
						}else{
							$form_date_added = '';
						}
						
						$incident_number = 'Census';
						
						if($note_info['user_id'] == SYSTEM_GENERATED ){
							$auto_generate = '1';
						}else{
							$auto_generate = '0';
						}
					
					$this->data['facilitiess'][] = array(
							'form_type_id' => 'form',
							'notes_id' => '',
							'form_type' => '',
							'notes_type' => $notes_type,
							'user_id' => $user_id,
							'auto_generate' => $auto_generate,
							'signature' => $signature,
							'notes_pin' => $notes_pin,
							'incident_number' => $incident_number,
							'form_date_added' =>$form_date_added,
							'href'        => $is_census_url,
							'audio_attach_url' => '',
							'notes_by_task_id' => '',
							'locations_id' => '',
							'task_type' => '',
							'task_content' => '',
							'task_time' => '',
							'media_url' => '',
							'capacity' => '',
							'location_name' => '',
							'location_type' => '',
							'notes_task_type' => '',
							'tags_id' => '',
							'drug_name' => '',
							'dose' => '',
							'drug_type' => '',
							'quantity' =>'',
							'frequency' => '',
							'instructions' => '',
							'count' =>'',
							'createtask_by_group_id' => '',
							'task_comments' => '',
							'medication_file_upload' => '',
							'date_added' => '',
							'is_census_url' => $is_census_url,
							'is_tag_url' => '',
							'print_url' => '',
							'current_locations_latitude' => '',
							'current_locations_longitude' => '',
							'location_tracking_route' => '',
							'travel_state' => '',
							'location_tracking_url' => '',
							'google_map_image_url' => '',
							'waypoint_google_url' => '',
							'task_id' => '',
							'is_approval_required_forms_id' => '',
							'attachment_type' =>'is_census',
						); 
						
				}
				
			
				$allforms = $this->model_notes_notes->getforms($this->request->post['notes_id']);
				
				$forms = array();
				foreach ($allforms as $allform) {
					if($allform['form_type'] == '1'){
					$form_url =	str_replace('&amp;', '&', $this->url->link('services/noteform/forminsert/', '' . 'incidentform_id=' . $allform['form_type_id']. '&facilities_id=' . $allform['facilities_id']. '&notes_id=' . $allform['notes_id']));
					}
					
					if($allform['form_type'] == '2'){
					$form_url =	str_replace('&amp;', '&', $this->url->link('services/noteform/noteschecklistform', '' . 'checklist_id=' . $allform['form_type_id']. '&facilities_id=' . $allform['facilities_id']. '&notes_id=' . $allform['notes_id']));
					}
					
					 
					if($allform['form_type'] == '3'){
					$form_url =	str_replace('&amp;', '&', $this->url->link('services/form', '' . 'forms_id=' . $allform['forms_id']. '&facilities_id=' . $allform['facilities_id']. '&notes_id=' . $allform['notes_id']. '&forms_design_id=' . $allform['custom_form_type']));
					}
					if($note_info['is_archive'] == '4'){
					$form_url =	str_replace('&amp;', '&', $this->url->link('services/form', '' . 'forms_id=' . $allform['forms_id']. '&facilities_id=' . $allform['facilities_id']. '&notes_id=' . $allform['notes_id']. '&forms_design_id=' . $allform['custom_form_type']. '&is_archive=' . $note_info['is_archive']));
					}
					
					if($allform['user_id'] != null && $allform['user_id'] != ""){
						$user_id = $allform['user_id'];
						$signature = $allform['signature'];
						$notes_pin = $allform['notes_pin'];
						$notes_type = $allform['notes_type'];
						
						if($allform['form_date_added'] != null && $allform['form_date_added'] != "0000-00-00 00:00:00"){
							$form_date_added = date($this->language->get('date_format_short_2'), strtotime($allform['form_date_added']));
						}else{
							$form_date_added = '';
						}
						
					}else{
						$user_id = $note_info['user_id'];
						$signature = $note_info['signature'];
						$notes_pin = $note_info['notes_pin'];
						$notes_type = $note_info['notes_type'];
						
						if($note_info['note_date'] != null && $note_info['note_date'] != "0000-00-00 00:00:00"){
							$form_date_added = date($this->language->get('date_format_short_2'), strtotime($note_info['note_date']));
						}else{
							$form_date_added = '';
						}
					}
					
					
					if($allform['custom_form_type'] == '13' ){
						$print_url = str_replace('&amp;', '&', $this->url->link('form/form/printformfldjj', '' . 'forms_id=' . $allform['forms_id']. '&facilities_id=' . $allform['facilities_id']. '&notes_id=' . $allform['notes_id']. '&forms_design_id=' . $allform['custom_form_type'], true));
					}elseif($allform['custom_form_type'] == '9' ){
						//$print_url = str_replace('&amp;', '&', $this->url->link('form/form/printmonthly_firredrill', '' . 'forms_id=' . $allform['forms_id']. '&facilities_id=' . $allform['facilities_id']. '&notes_id=' . $allform['notes_id']. '&forms_design_id=' . $allform['custom_form_type'], true));
						$print_url = str_replace('&amp;', '&', $this->url->link('form/form/printform', '' . 'forms_id=' . $allform['forms_id']. '&facilities_id=' . $allform['facilities_id']. '&notes_id=' . $allform['notes_id']. '&forms_design_id=' . $allform['custom_form_type'], true));
					}elseif($allform['custom_form_type'] == '10' ){
						//$print_url = str_replace('&amp;', '&', $this->url->link('form/form/printincidentform', '' . 'forms_id=' . $allform['forms_id']. '&facilities_id=' . $allform['facilities_id']. '&notes_id=' . $allform['notes_id']. '&forms_design_id=' . $allform['custom_form_type'], true));
						$print_url = str_replace('&amp;', '&', $this->url->link('form/form/printform', '' . 'forms_id=' . $allform['forms_id']. '&facilities_id=' . $allform['facilities_id']. '&notes_id=' . $allform['notes_id']. '&forms_design_id=' . $allform['custom_form_type'], true));
					}elseif($allform['custom_form_type'] == '2' ){
						//$print_url = str_replace('&amp;', '&', $this->url->link('form/form/printintakeform', '' . 'forms_id=' . $allform['forms_id']. '&facilities_id=' . $allform['facilities_id']. '&notes_id=' . $allform['notes_id']. '&forms_design_id=' . $allform['custom_form_type'], true));
						$print_url = str_replace('&amp;', '&', $this->url->link('form/form/printform', '' . 'forms_id=' . $allform['forms_id']. '&facilities_id=' . $allform['facilities_id']. '&notes_id=' . $allform['notes_id']. '&forms_design_id=' . $allform['custom_form_type'], true));
					}elseif($allform['custom_form_type'] == '12' ){
						//$print_url = str_replace('&amp;', '&', $this->url->link('form/form/printintakeform', '' . 'forms_id=' . $allform['forms_id']. '&facilities_id=' . $allform['facilities_id']. '&notes_id=' . $allform['notes_id']. '&forms_design_id=' . $allform['custom_form_type'], true));
						$print_url = str_replace('&amp;', '&', $this->url->link('form/form/printform', '' . 'forms_id=' . $allform['forms_id']. '&facilities_id=' . $allform['facilities_id']. '&notes_id=' . $allform['notes_id']. '&forms_design_id=' . $allform['custom_form_type'], true));
					}else{
						$print_url = '';
					}
					
					if($note_info['user_id'] == SYSTEM_GENERATED ){
						$auto_generate = '1';
					}else{
						$auto_generate = '0';
					}
					
					$this->data['facilitiess'][] = array(
							'form_type_id' => $allform['form_type_id'],
							'notes_id' => $allform['notes_id'],
							'form_type' => $allform['form_type'],
							'notes_type' => $notes_type,
							'user_id' => $user_id,
							'auto_generate' => $auto_generate,
							'signature' => $signature,
							'notes_pin' => $notes_pin,
							'incident_number' => $allform['incident_number'],
							'form_date_added' => $form_date_added,
							'href'        => $form_url,
							'print_url'        => $print_url,
							'audio_attach_url' => '',
							'notes_by_task_id' => '',
							'locations_id' => '',
							'task_type' => '',
							'task_content' => '',
							'task_time' => '',
							'media_url' => '',
							'capacity' => '',
							'location_name' => '',
							'location_type' => '',
							'notes_task_type' => '',
							'tags_id' => '',
							'drug_name' => '',
							'dose' => '',
							'drug_type' => '',
							'quantity' =>'',
							'frequency' => '',
							'instructions' => '',
							'count' =>'',
							'createtask_by_group_id' => '',
							'task_comments' => '',
							'medication_file_upload' => '',
							'date_added' => '',
							'is_tag_url' => '',
							'is_census_url' => '',
							'current_locations_latitude' => '',
							'current_locations_longitude' => '',
							'location_tracking_route' => '',
							'travel_state' => '',
							'location_tracking_url' => '',
							'google_map_image_url' => '',
							'waypoint_google_url' => '',
							'task_id' => '',
							'is_approval_required_forms_id' => '',
							'attachment_type' =>'is_forms',
						); 
				}
				
				$this->load->model('notes/notes'); 
			
				$allimages = $this->model_notes_notes->getImages($this->request->post['notes_id']);
				$images = array();
				foreach ($allimages as $image) {
					
					$extension = $image['notes_media_extention'];
					
					
					
					if($image['media_user_id'] != null && $image['media_user_id'] != ""){ 
						$muser_id = $image['media_user_id'];
						$msignature = $image['media_signature'];
						$mnotes_pin = $image['media_pin'];
						$notes_type = $image['notes_type'];
						
						if($image['media_date_added'] != null && $image['media_date_added'] != "0000-00-00 00:00:00"){
							$media_date_added = date($this->language->get('date_format_short_2'), strtotime($image['media_date_added']));
						}else{
							$media_date_added = '';
						}
						
					}else{
						$muser_id = $note_info['user_id'];
						$msignature = $note_info['signature'];
						$mnotes_pin = $note_info['notes_pin'];
						$notes_type = $note_info['notes_type'];
						
						if($note_info['note_date'] != null && $note_info['note_date'] != "0000-00-00 00:00:00"){
								$media_date_added = date($this->language->get('date_format_short_2'), strtotime($note_info['note_date']));
							}else{
								$media_date_added = '';
							}
						
					}
					if($image['audio_attach_url'] != null && $image['audio_attach_url'] != ""){
						$audio_tag = '1';
					}
					
					if($note_info['user_id'] == SYSTEM_GENERATED ){
						$auto_generate = '1';
					}else{
						$auto_generate = '0';
					}
					
					$this->data['facilitiess'][] = array(
						'form_type_id' =>'img',
						'notes_id' => $this->request->post['notes_id'],
						'user_id' => $muser_id,
						'auto_generate' => $auto_generate,
						'incident_number' => '',
						'form_type' => $image['notes_media_extention'],
						'form_date_added' => $media_date_added,
						'signature' => $msignature,
						'notes_pin' => $mnotes_pin,
						'notes_type' => $notes_type,
						'href' => $image['notes_file'],
						'audio_attach_url' => $image['audio_attach_url'],
						'notes_by_task_id' => '',
						'locations_id' => '',
						'task_type' => '',
						'task_content' => '',
						'task_time' => '',
						'media_url' => '',
						'capacity' => '',
						'location_name' => '',
						'location_type' => '',
						'notes_task_type' => '',
						'tags_id' => '',
						'drug_name' => '',
						'dose' => '',
						'drug_type' => '',
						'quantity' =>'',
						'frequency' => '',
						'instructions' => '',
						'count' =>'',
						'createtask_by_group_id' => '',
						'task_comments' => '',
						'medication_file_upload' => '',
						'date_added' => '',
						'is_tag_url' => '',
						'is_census_url' => '',
						'print_url' => '',
						'current_locations_latitude' => '',
						'current_locations_longitude' => '',
						'location_tracking_route' => '',
						'travel_state' => '',
						'location_tracking_url' => '',
						'google_map_image_url' => '',
						'waypoint_google_url' => '',
						'task_id' => '',
						'is_approval_required_forms_id' => '',
						'attachment_type' =>'is_img',
					);
				}
				
				
					$alltmasks = $this->model_notes_notes->getnotesBytasks($this->request->post['notes_id'], '2');
					
					foreach ($alltmasks as $alltmask) {
						
						if($alltmask['task_time'] != null && $alltmask['task_time'] != '00:00:00'){
							$taskTime = date('h:i A', strtotime($alltmask['task_time']));
						}
						
						if($note_info['user_id'] == SYSTEM_GENERATED ){
							$auto_generate = '1';
						}else{
							$auto_generate = '0';
						}
						
						$this->data['facilitiess'][] = array(
								'form_type_id'=>'medication',
								'notes_id' => $this->request->post['notes_id'],
								'user_id' => $alltmask['user_id'],
								'auto_generate' => $auto_generate,
								'incident_number' => '',
								'form_type' =>'',
								'form_date_added' => '',
								'signature' => $alltmask['signature'],
								'notes_pin' => $alltmask['notes_pin'],
								'notes_type' => $alltmask['notes_type'],
								'href' => $alltmask['media_url'],
								'audio_attach_url' => '',
								
								'notes_by_task_id' => $alltmask['notes_by_task_id'],
								'locations_id' => $alltmask['locations_id'],
								'task_type' => $alltmask['task_type'],
								'task_content' => $alltmask['task_content'],
								'task_time' => $taskTime,
								'media_url' => $alltmask['media_url'],
								'capacity' => $alltmask['capacity'],
								'location_name' => $alltmask['location_name'],
								'location_type' => $alltmask['location_type'],
								'notes_task_type' => $alltmask['notes_task_type'],
								'tags_id' => $alltmask['tags_id'],
								'drug_name' => $alltmask['drug_name'],
								'dose' => $alltmask['dose'],
								'drug_type' => $alltmask['drug_type'],
								'quantity' => $alltmask['quantity'],
								'frequency' => $alltmask['frequency'],
								'instructions' => $alltmask['instructions'],
								'count' => $alltmask['count'],
								'createtask_by_group_id' => $alltmask['createtask_by_group_id'],
								'task_comments' => $alltmask['task_comments'],
								'role_call' => $alltmask['role_call'],
								'medication_file_upload' => $alltmask['medication_file_upload'],
								'date_added' => date($this->language->get('date_format_short_2'), strtotime($alltmask['date_added'])),
								'is_tag_url' => '',
								'is_census_url' => '',
								'print_url' => '',
								'current_locations_latitude' => '',
								'current_locations_longitude' => '',
								'location_tracking_route' => '',
								'travel_state' => '',
								'location_tracking_url' => '',
								'google_map_image_url' => '',
								'waypoint_google_url' => '',
								'task_id' => '',
								'is_approval_required_forms_id' => '',
								'attachment_type' =>'is_medication',
							); 
							
							
					}
					
		
			$value = array('results'=>$this->data['facilitiess'],'status'=>true);
		}else{
			$error = false;
			$value = array('results'=>$this->data['facilitiess'],'status'=>true);
		}
		
		$this->response->setOutput(json_encode($value));
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices jsonforms',
			);
			$this->model_activity_activity->addActivity('app_jsonforms', $activity_data2);
		
		
		} 
	
	}
	
	
	public function jsongetallMedications(){
		
		try{
		$this->data['facilitiess'] = array();
		
		
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
		
		$this->language->load('notes/notes'); 
		if($this->request->post['notes_id'] != null && $this->request->post['notes_id'] != ""){
			$this->load->model('notes/notes'); 
    	
				$notesID = (string) $this->request->post['notes_id'];
				
				$note_info = $this->model_notes_notes->getNote($this->request->post['notes_id']);
			
					$alltmasks = $this->model_notes_notes->getnotesBytasks($this->request->post['notes_id'], '2');
					
					foreach ($alltmasks as $alltmask) {
						
						if($alltmask['task_time'] != null && $alltmask['task_time'] != '00:00:00'){
							$taskTime = date('h:i A', strtotime($alltmask['task_time']));
						}
						
						$this->data['facilitiess'][] = array(
								'notes_by_task_id' => $alltmask['notes_by_task_id'],
								'locations_id' => $alltmask['locations_id'],
								'task_type' => $alltmask['task_type'],
								'task_content' => $alltmask['task_content'],
								'user_id' => $alltmask['user_id'],
								'signature' => $alltmask['signature'],
								'notes_pin' => $alltmask['notes_pin'],
								'task_time' => $taskTime,
								'media_url' => $alltmask['media_url'],
								'capacity' => $alltmask['capacity'],
								'location_name' => $alltmask['location_name'],
								'location_type' => $alltmask['location_type'],
								'notes_task_type' => $alltmask['notes_task_type'],
								'tags_id' => $alltmask['tags_id'],
								'drug_name' => $alltmask['drug_name'],
								'dose' => $alltmask['dose'],
								'drug_type' => $alltmask['drug_type'],
								'quantity' => $alltmask['quantity'],
								'frequency' => $alltmask['frequency'],
								'instructions' => $alltmask['instructions'],
								'count' => $alltmask['count'],
								'createtask_by_group_id' => $alltmask['createtask_by_group_id'],
								'task_comments' => $alltmask['task_comments'],
								'medication_file_upload' => $alltmask['medication_file_upload'],
								'date_added' => date($this->language->get('date_format_short_2'), strtotime($alltmask['date_added'])),
								
							); 
							
							
					}
				
				
		
			$value = array('results'=>$this->data['facilitiess'],'status'=>true);
		}else{
			$error = false;
			$value = array('results'=>$this->data['facilitiess'],'status'=>true);
		}
		
		$this->response->setOutput(json_encode($value));
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices jsonforms',
			);
			$this->model_activity_activity->addActivity('app_jsonforms', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		} 
	
	}


	public function reviewNotes() {
		
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
		
		$this->language->load('notes/notes');
		if ($this->request->post['reviewed_by'] != '3') {
			if ($this->request->post['user_id'] == '') {
				$json['warning'] = $this->language->get('error_required');
			}
		}

		if ($this->request->post['user_id'] != '') {
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if(empty($user_info)){
				$json['warning'] = $this->language->get('error_required');
			}
		}
		
		if ($this->request->post['reviewed_by'] == '3') {
			if ($this->request->post['date_from'] == '') {
				$json['warning'] = $this->language->get('error_required');
			}
		}
		
		
		if($json['warning'] == null && $json['warning'] == ""){
			
			$this->load->model('notes/notes');
			$notes_info = $this->model_notes_notes->getnotesbyUser($this->request->post, $this->request->post['facilities_id'], $this->request->post['facilitytimezone']);
			
			
			if($notes_info['date_added'] != null && $notes_info['date_added'] != ""){
				$date_added = date('Y-m-d', strtotime($notes_info['date_added']));
			}else{
				$date_added = date('Y-m-d');
			} 
			
			$this->data['facilitiess'][] = array(
				'warning'  => '1',
				'date_added'  => $date_added,
				'highlighter'  => $this->request->post['highlighter'],
				'activenote'  => $this->request->post['activenote'],
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

	public function updateTags() {
		
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
		
		$this->language->load('notes/notes');
		if ($this->request->post['user_id'] == '') {
			$json['warning'] = $this->language->get('error_required');
		}
		if ($this->request->post['emp_tag_id'] == '') {
			$json['warning'] = $this->language->get('error_required');
		}

		if ($this->request->post['user_id'] != '') {
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if(empty($user_info)){
				$json['warning'] = $this->language->get('error_required');
			}
		}
		
			if ($this->request->post['notes_pin'] == '') {
				$json['notes_pin'] = $this->language->get('error_required');
			}
			if($this->request->post['notes_pin'] != null && $this->request->post['notes_pin'] != ""){
				$this->load->model('user/user');
				
				$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

				if (($this->request->post['notes_pin'] != $user_info['user_pin'])) {
					$json['warning'] = $this->language->get('error_exists');
				}
			}
		
		
		if($json['warning'] == null && $json['warning'] == ""){
			
			$facilitytimezone = $this->request->post['facilitytimezone'];
			
			$this->model_notes_notes->updatenotesTags($this->request->post, $this->request->post['notes_id'], $facilitytimezone);
			
			$this->data['facilitiess'][] = array(
				'warning'  => '1',
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

	
	public function jsonCustomForms(){
		
		try{
		$this->data['facilitiess'] = array();
		
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
		
		$this->load->model('form/form');
		
		$data3 = array();
		$data3['status'] = '1';
		//$data3['order'] = 'sort_order';
		$data3['facilities_id'] = $this->request->post['facilities_id'];
		
		$results = $this->model_form_form->getforms($data3);
		//$results = $this->model_form_form->getforms();
    	
		foreach ($results as $custom_form) {
					
      		$this->data['facilitiess'][] = array(
				'forms_id'  => $custom_form['forms_id'],
				'form_name' => $custom_form['form_name'],
				'form_href' => $this->url->link('services/form', '' . '&forms_design_id='.$custom_form['forms_id'], 'SSL'),
			);
		}
		
		$value = array('results'=>$this->data['facilitiess'],'status'=>true);
		/*echo json_encode($value);*/
		$this->response->setOutput(json_encode($value));
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices CustomsForm',
			);
			$this->model_activity_activity->addActivity('app_jsonCustomsForm', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		} 	
	
	}
	
	public function jsonnoteupdate(){
		try{
		$this->data['facilitiess'] = array();
		
		
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
		
		$this->load->model('setting/timezone');
		$this->load->model('facilities/facilities');
		
		
		$facilities_id = $this->request->post['facilities_id'];
		$notes_id = $this->request->post['notes_id'];
		$strikeout = $this->request->post['strikeout'];
		$text_color = $this->request->post['text_color'];
		$highlighter_id = $this->request->post['highlighter_id'];
		$attachment = $this->request->post['attachment'];
		
		
		
		$json = array();
		
		if($notes_id == null && $notes_id == ""){
			$json['warning'] = 'Please select notes id!';
		}
		
		if($facilities_id == null && $facilities_id == ""){
			$json['warning'] = 'Please select facilities id!';
		}
		
		if($strikeout == "1"){
			if($this->request->post['notes_pin'] != null && $this->request->post['notes_pin'] != ""){
				$this->load->model('user/user');
				$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

				if (($this->request->post['notes_pin'] != $user_info['user_pin'])) {
					$json['warning'] = 'User Pin not valid!.';
				}
			}
			
			if($this->request->post['user_id'] != null && $this->request->post['user_id'] != ""){
				$this->load->model('user/user');
				$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

				if (($user_info['status'] == '0')) {
					$json['warning'] = 'User not exit!';
				}
			}
		}
		
		if($attachment == "1"){
			if($this->request->post['media_pin'] != null && $this->request->post['media_pin'] != ""){
				$this->load->model('user/user');
				$user_info = $this->model_user_user->getUser($this->request->post['media_user_id']);

				if (($this->request->post['media_pin'] != $user_info['user_pin'])) {
					$json['warning'] = 'User Pin not valid!.';
				}
			}
			
			if($this->request->post['media_user_id'] != null && $this->request->post['media_user_id'] != ""){
				$this->load->model('user/user');
				$user_info = $this->model_user_user->getUser($this->request->post['media_user_id']);

				if (($user_info['status'] == '0')) {
					$json['warning'] = 'User not exit!';
				}
			}
		}
			
		
		if($json['warning'] == null && $json['warning'] == ""){

			$result = $this->model_notes_notes->getnotes($notes_id);
			
			$facility = $this->model_facilities_facilities->getfacilities($facilities_id);
								
			$timezone_info = $this->model_setting_timezone->gettimezone($facility['timezone_id']);
								
			date_default_timezone_set($timezone_info['timezone_value']);
			
			$update_date = date('Y-m-d H:i:s', strtotime('now'));
			
			
			if($result){
				if($highlighter_id != null && $highlighter_id != ""){
					$result = $this->model_notes_notes->updateNoteHigh($notes_id,$highlighter_id, $update_date);
					
					$this->data['facilitiess'][] = array(
						'warning'  => '1',
					);
					$error = true;
				}
				
				if($text_color != null && $text_color != ""){
					$this->model_notes_notes->updateNoteColor($notes_id, $text_color, $update_date);
					
					$this->data['facilitiess'][] = array(
						'warning'  => '1',
					);
					$error = true;
				}
				
				
				if($strikeout == "1"){
					
					$data = array();
			
					if($this->request->post['signature'] != null && $this->request->post['signature'] != ""){
						$data['imgOutput'] = $this->request->post['signature'];
					}
					
					$data['notes_pin'] = $this->request->post['notes_pin'];
					$data['user_id'] = $this->request->post['user_id'];
					$data['notes_id'] = $notes_id;
					
					$data['note_date'] = $this->request->post['note_date'];
					$data['facilitytimezone'] = $this->request->post['facilitytimezone'];
					$data['strike_note_type'] = $this->request->post['strike_note_type'];
					$this->model_notes_notes->jsonupdateStrikeNotes($data, $facilities_id);
					
					$this->data['facilitiess'][] = array(
						'warning'  => '1',
					);
					$error = true;
				}
				
				if($attachment == '1'){
					
					if($this->request->files["upload_file"] != null && $this->request->files["upload_file"] != ""){
						
						$extension = end(explode(".", $this->request->files["upload_file"]["name"]));

						if($this->request->files["upload_file"]["size"] < 42214400){
							$neextension  = strtolower($extension);
							
							$notes_file = 'devbolb'.rand().'.'.$extension;
							$outputFolder = $this->request->files["upload_file"]["tmp_name"];
								
							//require_once(DIR_SYSTEM . 'library/azure_storage/config.php');
							
							//require_once(DIR_SYSTEM . 'library/awsstorage/s3_config.php');
							
							if($this->config->get('enable_storage') == '1'){
								/* AWS */
								
								require_once(DIR_SYSTEM . 'library/awsstorage/s3_config.php');
							}
							
							if($this->config->get('enable_storage') == '2'){
								/* AZURE */
								
								require_once(DIR_SYSTEM . 'library/azure_storage/config.php');					
								//uploadBlobSample($blobClient, $outputFolder, $notes_file);
								$s3file = AZURE_URL. $notes_file;
							}
							
							if($this->config->get('enable_storage') == '3'){
								/* LOCAL */
								$outputFolder = DIR_IMAGE.'storage/' . $notes_file;
								move_uploaded_file($this->request->files["upload_file"]["tmp_name"], $outputFolder);
								$s3file = HTTPS_SERVER.'image/storage/' . $notes_file;
							}
							
							
							$notes_media_extention = $extension;
							$notes_file_url = $s3file;
							
							
							$formData = array();
							$formData['media_user_id'] = $this->request->post['media_user_id'];
							$formData['media_signature'] = $this->request->post['media_signature'];
							$formData['media_pin'] = $this->request->post['media_pin'];
							$formData['notes_type'] = $this->request->post['notes_type'];
							
							$note_info = $this->model_notes_notes->getNote($notes_id);
							
							$formData['facilities_id'] = $note_info['facilities_id'];
								
							date_default_timezone_set($this->request->post['facilitytimezone']);
							$formData['noteDate'] = date('Y-m-d H:i:s', strtotime('now'));

							$this->model_notes_notes->updateNoteFile($notes_id, $notes_file_url, $notes_media_extention, $formData);
							
							
							date_default_timezone_set($this->request->post['facilitytimezone']);
							$update_date = date('Y-m-d H:i:s', strtotime('now'));
							
							$sql = "UPDATE `" . DB_PREFIX . "notes` SET update_date = '".$update_date."', notes_conut='0' WHERE notes_id = '" . (int)$notes_id . "' ";
							$this->db->query($sql);
							
							$error = true;
							
							$this->data['facilitiess'][] = array(
								'success'   => '1',
							);
						
						
						}else{
							$this->data['facilitiess'][] = array(
								'warning'  => 'Maximum size file upload!',
							);
							$error = false;
						}

						
					}else{
						$this->data['facilitiess'][] = array(
							'warning'  => 'Please select file!',
						);
						$error = false;
					}
					
				}
				
			
				
			}else{
				$this->data['facilitiess'][] = array(
					'warning'  => 'Error',
				);
				$error = false;
			}
		
		}else{
			$this->data['facilitiess'][] = array(
				'warning'  => $json['warning'],
			);
			$error = false;
		}
		
		$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
		
		$this->response->setOutput(json_encode($value));
		
		
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices multiple update',
			);
			$this->model_activity_activity->addActivity('app_multipleupdate', $activity_data2);
		
		
		} 	
		
		
	}

	
	public function jsongetactivenote(){
		
		try{
		$this->data['facilitiess'] = array();
		
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
		
		$this->language->load('notes/notes');
		$this->load->model('notes/notes');
		
		//if($this->request->post['keyword_ids'] != null && $this->request->post['keyword_ids'] != ""){
			$a212 = array();
			//$a212['keyword_ids'] = $this->request->post['keyword_ids'];
			$a212['is_monitor_time'] = '1';
			$a212['facilities_id'] = $this->request->post['facilities_id'];
		
			$active_note_info_actives = $this->model_notes_notes->getNotebyactivenotes($a212);
			
			
			if($active_note_info_actives != null && $active_note_info_actives != ""){
				foreach($active_note_info_actives as $active_note_info_active){
					
					$this->load->model('setting/keywords');
					$keywordData2 = $this->model_setting_keywords->getkeywordDetail($active_note_info_active['keyword_id']);
					//$monitor_time[] = $keywordData2['monitor_time'];
					
					if($keywordData2['monitor_time'] == '1'){
						$note_info = $this->model_notes_notes->getNote($active_note_info_active['notes_id']);	
						$this->data['facilitiess'][] = array(
							'keyword_name' => $active_note_info_active['keyword_name'],
							'user_id'  => $note_info['user_id'],
							'notes_id' => $note_info['notes_id'],
							'caltime'  => date($this->language->get('date_format_short_2'), strtotime($note_info['date_added'])),
						);
						
						
						$error = true;
						
					}
				
				}
			}else{
				$this->data['facilitiess'][] = array();
				$error = true;
			}
		
		/*}else{
			$this->data['facilitiess'][] = array(
				'warning'  => 'Please Send Notes id',
			);
			$error = false;
		}*/
		
		$value = array('results'=>$this->data['facilitiess'],'status'=>true);
		/*echo json_encode($value);*/
		$this->response->setOutput(json_encode($value));
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices jsongeyactivenote',
			);
			$this->model_activity_activity->addActivity('app_jsongeyactivenote', $activity_data2);
		
		
		} 	
	
	}
}	