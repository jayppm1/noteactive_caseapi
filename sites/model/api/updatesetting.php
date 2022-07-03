<?php
class Modelapiupdatesetting extends Model {

	/*----------------------------Replica for app_user_date and some fields------------------------------------*/

	public function getfacilitiessettingReplica($udata = array()) {
		$ufresult = array ();
		$sql = "SELECT facilities_id,timezone_id,facility,password,salt,firstname,lastname,email,status,users,config_task_status,config_tag_status,sms_number,config_taskform_status,config_noteform_status,config_rules_status,latitude,longitude,config_display_camera,config_display_dashboard,config_share_notes,config_sharepin_status,config_multiple_activenote,sharenotes_print,sharenotes_modify,sharenotes_copy,sharenotes_assemble,config_send_email_share_notes,config_rolecall_customlist_id,config_tags_customlist_id,config_bedcheck_customlist_id,form_print_layout,is_web_notification,web_audio_file,web_is_snooze,web_is_dismiss,is_android_notification,android_audio_file,is_android_snooze,is_android_dismiss,is_ios_notification,ios_audio_file,is_ios_snooze,is_ios_dismiss,device_ids,device_username,device_token,is_enable_beacon,beacon_range,beacon_data_type_range,config_current_location,is_discharge_form_enable,discharge_form_id,is_data_sync,data_sync_date_to,data_sync_date_from,is_fingerprint_enable,is_sms_enable,is_pin_enable,is_enable_add_notes_by,allow_quick_save,allow_face_without_verified,face_similar_percent,display_attchament,is_client_facial,is_master_facility,notes_facilities_ids,client_facilities_ids,required_escorted,enable_escorted FROM `" . DB_PREFIX . "facilities` WHERE facilities_id = '" . ( int ) $udata ['facilities_id'] . "' ";
		$sql .= " and `update_date` BETWEEN  '" . $udata ['app_user_date'] . "' AND  '" . $udata ['current_date_user'] . " 23:59:59' ";
		
		$query = $this->db->query ( $sql );
		$facility_info = $query->row;
		
		if (! empty ( $facility_info )) {
			
			if ($this->config->get ( 'config_date_picker' ) != null && $this->config->get ( 'config_date_picker' ) != "") {
				$config_date_picker = $this->config->get ( 'config_date_picker' );
			} else {
				$config_date_picker = '0';
			}
			
			if ($this->config->get ( 'config_time_picker' ) != null && $this->config->get ( 'config_time_picker' ) != "") {
				$config_time_picker = $this->config->get ( 'config_time_picker' );
			} else {
				$config_time_picker = '0';
			}
			
			$config_admin_limit1 = $this->config->get ( 'config_android_front_limit' );
			if ($config_admin_limit1 != null && $config_admin_limit1 != "") {
				$config_admin_limit = $config_admin_limit1;
			} else {
				$config_admin_limit = "25";
			}
			
			$error_config_android_front_limit1 = $this->config->get ( 'config_android_front_limit' );
			if ($error_config_android_front_limit1 != null && $error_config_android_front_limit1 != "") {
				$config_android_front_limit = $error_config_android_front_limit1;
			} else {
				$config_android_front_limit = "25";
			}
			
			$mobile_db_page_limit1 = $this->config->get ( 'mobile_db_page_limit' );
			if ($mobile_db_page_limit1 != null && $mobile_db_page_limit1 != "") {
				$mobile_db_page_limit = $mobile_db_page_limit1;
			} else {
				$mobile_db_page_limit = "20";
			}
			$mobile_list_view_page_limit1 = $this->config->get ( 'mobile_list_view_page_limit' );
			if ($mobile_list_view_page_limit1 != null && $mobile_list_view_page_limit1 != "") {
				$mobile_list_view_page_limit = $mobile_list_view_page_limit1;
			} else {
				$mobile_list_view_page_limit = "25";
			}
			
			if ($this->config->get ( 'config_secure' ) == "1") {
				$configUrl = '1';
				$configUrl2 = $this->config->get ( 'config_ssl' ) . 'index.php?route=services/';
			} else {
				$configUrl = '0';
				$configUrl2 = $this->config->get ( 'config_url' ) . 'index.php?route=services/';
			}
			
			if ($this->config->get ( 'config_all_notification' ) != null && $this->config->get ( 'config_all_notification' ) != "") {
				$config_all_notification = $this->config->get ( 'config_all_notification' );
			} else {
				$config_all_notification = '0';
			}
			
			if ($this->config->get ( 'config_task_sms' ) != null && $this->config->get ( 'config_task_sms' ) != "") {
				$config_task_sms = $this->config->get ( 'config_task_sms' );
			} else {
				$config_task_sms = '0';
			}
			
			if ($this->config->get ( 'config_task_notification' ) != null && $this->config->get ( 'config_task_notification' ) != "") {
				$config_task_notification = $this->config->get ( 'config_task_notification' );
			} else {
				$config_task_notification = '0';
			}
			
			if ($this->config->get ( 'config_task_email' ) != null && $this->config->get ( 'config_task_email' ) != "") {
				$config_task_email = $this->config->get ( 'config_task_email' );
			} else {
				$config_task_email = '0';
			}
			
			if ($this->config->get ( 'config_task_deleted_time' ) != null && $this->config->get ( 'config_task_deleted_time' ) != "") {
				$config_task_deleted_time = $this->config->get ( 'config_task_deleted_time' );
			} else {
				$config_task_deleted_time = '0';
			}
			
			if ($facility_info ['config_taskform_status'] != NULL && $facility_info ['config_taskform_status'] != "") {
				$taskstatus = $facility_info ['config_taskform_status'];
			} else {
				$taskstatus = '0';
			}
			
			if ($facility_info ['config_noteform_status'] != NULL && $facility_info ['config_noteform_status'] != "") {
				$noteformtatus = $facility_info ['config_noteform_status'];
			} else {
				$noteformtatus = '0';
			}
			
			if ($facility_info ['config_rules_status'] != NULL && $facility_info ['config_rules_status'] != "") {
				$ruletatus = $facility_info ['config_rules_status'];
			} else {
				$ruletatus = '0';
			}
			
			if ($facility_info ['config_display_camera'] != NULL && $facility_info ['config_display_camera'] != "") {
				$config_display_camera = $facility_info ['config_display_camera'];
			} else {
				$config_display_camera = '0';
			}
			
			if ($this->config->get ( 'config_transcription' ) != null && $this->config->get ( 'config_transcription' ) != "") {
				$config_transcription = $this->config->get ( 'config_transcription' );
			} else {
				$config_transcription = '0';
			}
			
			if ($facility_info ['config_share_notes'] != NULL && $facility_info ['config_share_notes'] != "") {
				$config_share_notes = $facility_info ['config_share_notes'];
			} else {
				$config_share_notes = '0';
			}
			
			if ($facility_info ['config_sharepin_status'] != NULL && $facility_info ['config_sharepin_status'] != "") {
				$config_sharepin_status = $facility_info ['config_sharepin_status'];
			} else {
				$config_sharepin_status = '0';
			}
			
			if ($facility_info ['config_multiple_activenote'] != NULL && $facility_info ['config_multiple_activenote'] != "") {
				$config_multiple_activenote = $facility_info ['config_multiple_activenote'];
			} else {
				$config_multiple_activenote = '0';
			}
			
			if ($facility_info ['android_audio_file'] != NULL && $facility_info ['android_audio_file'] != "") {
				$android_audio_file = HTTP_SERVER . 'image/ringtone/' . $facility_info ['android_audio_file'];
			} else {
				$android_audio_file = '';
			}
			
			if ($facility_info ['ios_audio_file'] != NULL && $facility_info ['ios_audio_file'] != "") {
				$ios_audio_file = HTTP_SERVER . 'image/ringtone/' . $facility_info ['ios_audio_file'];
			} else {
				$ios_audio_file = '';
			}
			
			$this->load->model ( 'api/encrypt' );
			$edevice_username = $this->model_api_encrypt->encrypt ( $this->config->get ( 'device_username' ) );
			$edevice_token = $this->model_api_encrypt->encrypt ( $this->config->get ( 'device_token' ) );
			
			$this->load->model ( 'setting/timezone' );
			$timezone_info = $this->model_setting_timezone->gettimezone ( $facility_info ['timezone_id'] );
			
			$fsql = "SELECT DISTINCT customer_key,forms_id,form_name,forms_fields FROM " . DB_PREFIX . "forms_design WHERE form_type = 'Screening' and FIND_IN_SET('" . $facility_info ['facilities_id'] . "', facilities) ";
			$queryf = $this->db->query ( $fsql );
			
			$form_info = $queryf->row;
			if ($form_info ['customer_key'] != null && $form_info ['customer_key'] != "") {
				$screening_id = $form_info ['forms_id'];
			} else {
				$screening_id = "";
			}
			
			$fsqli = "SELECT DISTINCT customer_key,forms_id,form_name,forms_fields FROM " . DB_PREFIX . "forms_design WHERE form_type = 'Intake' and FIND_IN_SET('" . $facility_info ['facilities_id'] . "', facilities) ";
			$queryfi = $this->db->query ( $fsqli );
			
			$form_intake_info = $queryfi->row;
			
			if ($form_intake_info ['customer_key'] != null && $form_intake_info ['customer_key'] != "") {
				$intake_id = $form_intake_info ['forms_id'];
				
				$add_client_url = str_replace ( '&amp;', '&', $this->url->link ( 'services/form', '' . '&addclient=1&forms_design_id=' . $intake_id . '&facilities_id=' . $facility_info ['facilities_id'], 'SSL' ) );
			} else {
				$intake_id = "";
				$add_client_url = "";
			}
			
			$this->load->model ( 'api/permision' );
			$current_permission = $this->model_api_permision->getpermision ( $facility_info ['facilities_id'] );
			
			$this->load->model ( 'customer/apiurl' );
			$apiurls = $this->model_customer_apiurl->getapiurls ( $facility_info ['customer_key'] );
			$apiurls1 = array ();
			foreach ( $apiurls as $apiurl ) {
				$apiurls1 [] = array (
						'keyname' => $apiurl ['keyname'],
						'api_full_url' => $apiurl ['api_full_url'] 
				);
			}
			
			$unique_id = $facility_info ['customer_key'];
			$this->load->model ( 'customer/customer' );
			$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
			
			$client_info = unserialize($customer_info['client_info_notes']);
			$setting_data = unserialize($customer_info['setting_data']);
			
			
			$this->load->model ( 'notes/shift' );
			$shifs = $this->model_notes_shift->getshifts ( $facility_info ['customer_key'] );
			$shifts1 = array ();
			foreach ( $shifs as $shift ) {
				$shifts1 [] = array (
						'shift_id' => $shift ['shift_id'],
						'shift_name' => $shift ['shift_name'], 
						'shift_starttime' => $shift ['shift_starttime'], 
						'shift_endtime' => $shift ['shift_endtime'], 
						'shift_color_value' => $shift ['shift_color_value'], 
						'date_added' => $shift ['date_added'], 
				);
			}
			
			if($this->config->get ( 'all_sync_pagination' ) != null && $this->config->get ( 'all_sync_pagination' ) != ""){
				$all_sync_pagination = $this->config->get ( 'all_sync_pagination' );
			}else{
				$all_sync_pagination = "50";
			}
			
			$subfacilities = array();
			if($facility_info ['is_master_facility'] == 1){
				
				$subfacilities [] = array (
					'facilities_id' => $facility_info ['facilities_id'],
					'is_master_facility' => $facility_info ['is_master_facility'],
					'notes_facilities_ids' => $facility_info ['notes_facilities_ids'],
					'client_facilities_ids' => $facility_info ['client_facilities_ids'],
					'enable_facilityinout' => $facility_info ['enable_facilityinout'],
				);
				
				$cids = array();
				if($facility_info ['client_facilities_ids'] != null && $facility_info ['client_facilities_ids'] != ""){
					$sssssddsg = explode(",",$facility_info ['client_facilities_ids']);
					$abdcg = array_unique($sssssddsg);
					foreach($abdcg as $fid){
						$facilityinfo = $this->model_facilities_facilities->getfacilities ( $fid );
						$subfacilities [] = array (
							'facilities_id' => $facilityinfo ['facilities_id'],
							'is_master_facility' => $facilityinfo ['is_master_facility'],
							'notes_facilities_ids' => $facilityinfo ['notes_facilities_ids'],
							'client_facilities_ids' => $facilityinfo ['client_facilities_ids'],
							'enable_facilityinout' => $facilityinfo ['enable_facilityinout'],
						);
					}
				}
				
				if($facility_info ['notes_facilities_ids'] != null && $facility_info ['notes_facilities_ids'] != ""){
					$sssssddsg2 = explode(",",$facility_info ['notes_facilities_ids']);
					$abdcgs = array_unique($sssssddsg2);
					foreach($abdcgs as $fidd){
						$facilityinfo = $this->model_facilities_facilities->getfacilities ( $fidd );
						$subfacilities [] = array (
							'facilities_id' => $facilityinfo ['facilities_id'],
							'is_master_facility' => $facilityinfo ['is_master_facility'],
							'notes_facilities_ids' => $facilityinfo ['notes_facilities_ids'],
							'client_facilities_ids' => $facilityinfo ['client_facilities_ids'],
							'enable_facilityinout' => $facilityinfo ['enable_facilityinout'],
						);
					}
				}
				
				
			}
			
			$ufresult [] = array (
					'facility' => $facility_info ['facility'],
					'timezone_value' => $timezone_info ['timezone_value'],
					'facilities_id' => $facility_info ['facilities_id'],
					'config_display_camera' => $config_display_camera,
					'config_share_notes' => $config_share_notes,
					'config_sharepin_status' => $config_sharepin_status,
					'config_multiple_activenote' => $config_multiple_activenote,
					'config_date_picker' => $config_date_picker,
					'config_time_picker' => $config_time_picker,
					'config_task_status' => $facility_info ['config_task_status'],
					'config_tag_status' => $facility_info ['config_tag_status'],
					'config_admin_limit' => $config_admin_limit,
					'notes_total' => '',
					'notes_total1' => '',
					'http_check' => $configUrl,
					'http_url' => $configUrl2,
					'config_all_notification' => $config_all_notification,
					'config_task_sms' => $config_task_sms,
					'config_task_notification' => $config_task_notification,
					'config_task_email' => $config_task_email,
					'config_task_deleted_time' => $config_task_deleted_time,
					'config_taskform_status' => $taskstatus,
					'config_noteform_status' => $noteformtatus,
					'config_rules_status' => $ruletatus,
					'config_android_front_limit' => $config_android_front_limit,
					'config_transcription' => $config_transcription,
					'mobile_db_page_limit' => $mobile_db_page_limit,
					'mobile_list_view_page_limit' => $mobile_list_view_page_limit,
					
					'face_similar_percent' => $facility_info ['face_similar_percent'],
					'allow_face_without_verified' => $facility_info ['allow_face_without_verified'],
					'allow_quick_save' => $facility_info ['allow_quick_save'],
					'display_attchament' => $facility_info ['display_attchament'],
					'config_face_recognition' => $this->config->get ( 'config_face_recognition' ),
					'active_notification' => $this->config->get ( 'active_notification' ),
					
					'is_android_notification' => $facility_info ['is_android_notification'],
					'android_audio_file' => $android_audio_file,
					'is_android_snooze' => $facility_info ['is_android_snooze'],
					'is_android_dismiss' => $facility_info ['is_android_dismiss'],
					'is_ios_notification' => $facility_info ['is_ios_notification'],
					'ios_audio_file' => $ios_audio_file,
					'is_ios_snooze' => $facility_info ['is_ios_snooze'],
					'is_ios_dismiss' => $facility_info ['is_ios_dismiss'],
					
					'is_enable_beacon' => $facility_info ['is_enable_beacon'],
					'beacon_range' => $facility_info ['beacon_range'],
					'beacon_data_type_range' => $facility_info ['beacon_data_type_range'],
					
					'device_username' => $edevice_username,
					'device_token' => $edevice_token,
					'screening_id' => $screening_id,
					
					'is_discharge_form_enable' => $facility_info ['is_discharge_form_enable'],
					'discharge_form_id' => $facility_info ['discharge_form_id'],
					'is_fingerprint_enable' => $facility_info ['is_fingerprint_enable'],
					
					'is_pin_enable' => $facility_info ['is_pin_enable'],
					'is_sms_enable' => $facility_info ['is_sms_enable'],
					'is_enable_add_notes_by' => $facility_info ['is_enable_add_notes_by'],
					
					'is_client_facial' => $facility_info ['is_client_facial'],
					'is_master_facility' => $facility_info ['is_master_facility'],
					'notes_facilities_ids' => $facility_info ['notes_facilities_ids'],
					'client_facilities_ids' => $facility_info ['client_facilities_ids'],
					'add_client_url' => $add_client_url,
					'intake_id' => $intake_id,
					'current_permission' => $current_permission,
					'apiurls' => $apiurls1,
					'face_similar_percent' => $facility_info ['face_similar_percent'],
					'user_collection' => $customer_info ['user_collection'],
					'client_collection' => $customer_info ['client_collection'],
					'data_stream_name' => $customer_info ['data_stream_name'],
					
					'is_required_activenote' => $facility_info ['is_required_activenote'],
					'config_inventory_allow' => $facility_info ['config_inventory_allow'],
					'no_distribution' => $facility_info ['no_distribution'],
					'facility_type' => $facility_info ['facility_type'],
					'enable_facilityinout' => $facility_info ['enable_facilityinout'],
					'enable_escorted' => $facility_info ['enable_escorted'],
					'required_escorted' => $facility_info ['required_escorted'],
					'approval_required' => $facility_info ['approval_required'],
					'shifts' => $shifts1,
					'client_info' => $client_info,
					'setting_data' => $setting_data,
					'show_case' => $customer_info ['show_case'],
					'show_task' => $customer_info ['show_task'],
					'show_form_tag' => $customer_info ['show_form_tag'],
					'all_sync_pagination' => $all_sync_pagination,
					'subfacilities' => $subfacilities,
					'facility_setting' =>unserialize($facility_info['setting_data']),
					'allhouroutreport' =>  str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/allhouroutreport', '' , 'SSL' ) ),
			);
		}
		
		return $ufresult;
	}

	public function getupdateusersReplica($udata = array()) {
		$uresult = array ();
		$sql2 = "SELECT user_id,user_group_id,username,firstname,lastname,email,user_pin,facilities,phone_number,activationKey,default_facilities_id,default_highlighter_id,default_color,user_otp,message_sid,facilities_display,default_facility_id,enroll_image,status,reset_password_otp FROM `" . DB_PREFIX . "user` ";
		$sql2 .= "where 1 = '1' ";
		
		$sql2 .= " and status = '1' ";
		
		$sql2 .= " and `update_date` BETWEEN  '" . $udata ['app_user_date'] . "' AND  '" . $udata ['current_date_user'] . " 23:59:59' ";
		
		$this->load->model('facilities/facilities');
		$facility_info = $this->model_facilities_facilities->getfacilities($udata ['facilities_id']);
		$ddss = array();
		if ( $facility_info['client_facilities_ids'] != null && $facility_info['client_facilities_ids'] != "" ) {
			
			$ddss[] = $facility_info['client_facilities_ids'];
			$ddss[] = $udata ['facilities_id'];
			
			$sssssddsg = explode(",",$facility_info ['client_facilities_ids']);
			$abdcg = array_unique($sssssddsg);
			$cids = array();
			foreach($abdcg as $fid){
				$cids[] = $fid;
			}
			$abdcgs = array_unique($cids);
			foreach($abdcgs as $fid2){
				$facilityinfo = $this->model_facilities_facilities->getfacilities ( $fid2 );
				if ( $facilityinfo['client_facilities_ids'] != null && $facilityinfo['client_facilities_ids'] != "" ) {
					$ddss[] = $facilityinfo['client_facilities_ids'];
				}
			}
			
			
			$sssssdd = implode(",",$ddss);
			$sql2.= " and facilities in (" . $sssssdd . ") ";
			
		}else{
			$sql2 .= " and FIND_IN_SET('" . $udata ['facilities_id'] . "', facilities) ";
		}
		
		
		
		$query = $this->db->query ( $sql2 );
		$frusers = $query->rows;
		
		foreach ( $frusers as $user ) {
			$uresult [] = array (
					'user_id' => $user ['user_id'],
					'username' => $user ['username'],
					'firstname' => $user ['firstname'],
					'lastname' => $user ['lastname'],
					'user_pin' => $user ['user_pin'],
					'facilities' => $user ['facilities'],
					'phone_number' => $user ['phone_number'],
					'email' => $user ['email'],
					'status' => $user ['status'],
					'user_group_id' => $user ['user_group_id'] 
			);
		}
		
		return $uresult;
	}

	public function getupdatetagsReplica($udata = array()) {
		$utresult = array ();
		$sql = "SELECT tags_id,emp_tag_id,emp_first_name,emp_middle_name,emp_last_name,privacy,date_added,dob,locations_id,facilities_id,upload_file,tags_pin,gender,discharge,age,role_call,location_address,latitude,longitude,emp_extid,address_street2,person_screening,date_of_screening,ssn,state,city,zipcode,room,restriction_notes,prescription,alert_info,constant_sight,med_mental_health,tagstatus,forms_id,tags_forms_id,discharge_date,stickynote,customlistvalues_id,tags_status,tags_status_in,referred_facility,emergency_contact,reminder_time,reminder_date,upload_file_thumb,medication_inout FROM `" . DB_PREFIX . "tags` where status = 1  ";
		
		$sql .= " and `modify_date` BETWEEN  '" . $udata ['app_user_date'] . "' AND  '" . $udata ['current_date_user'] . " 23:59:59' ";
		
		if($udata['facilities_id'] != null && $udata['facilities_id'] != ""){
			$this->load->model('facilities/facilities');
			$facility_info = $this->model_facilities_facilities->getfacilities($udata['facilities_id']);
			$ddss = array();
			if ( $facility_info['client_facilities_ids'] != null && $facility_info['client_facilities_ids'] != "" ) {
				
				$ddss[] = $facility_info['client_facilities_ids'];
				
				$ddss[] = $udata['facilities_id'];
				$sssssdd = implode(",",$ddss);
				
				$sql.= " and facilities_id in (" . $sssssdd . ") ";
				
			}else{
				if ($udata['facilities_id'] != null && $udata['facilities_id'] != "") {
				$sql.= " and facilities_id = '".$udata['facilities_id']."'";
				
				}
			}
		}

		//echo $sql;
		
		//$sql .= " and facilities_id = '" . $udata ['facilities_id'] . "'";
		
		$query = $this->db->query ( $sql );
		$krusers = $query->rows;

		//print_r($krusers);
		
		$this->load->model ( 'setting/tags' );
		$this->load->model ( 'form/form' );
		
		$this->load->model ( 'setting/image' );
		$this->load->model ( 'facilities/facilities' );
		$this->load->model ( 'resident/resident' );
		$this->load->model ( 'createtask/createtask' );
		$this->load->model('notes/clientstatus');
		$this->load->model ( 'setting/locations' );
		
		$this->load->model ( 'facilities/facilities' );

		$facility = $this->model_facilities_facilities->getfacilities ( $udata['facilities_id']);
		
		$unique_id = $facility ['customer_key'];

	   // var_dump($unique_id); die;
		
		$this->load->model ( 'customer/customer' );
		
		$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
		
	   
		$client_info = unserialize($customer_info['client_info_notes']);

		$client_view_options2 = $client_info["client_view_options"]; 

		//echo '<pre>'; print_r($client_info); echo '</pre>';  die;

		// $this->data['client_view_options'] = '[emp_first_name],[emp_middle_name],[emp_last_name],[emergency_contact],[gender],[age]';
		$this->data['show_client_image'] = $client_info["show_client_image"];
		$this->data['show_form_tag'] = $client_info["show_form_tag"];
		$this->data['show_task'] = $client_info["show_task"];
		$this->data['show_case'] = $client_info["show_case"];
		
		foreach ( $krusers as $tag ) {
			
			$role_call = $tag ['role_call'];
			
			$get_img = $this->model_setting_tags->getImage ( $tag ['tags_id'] );

			if($get_img ['enroll_image']){
				$enroll_image = $get_img ['enroll_image'];
			}else{
				$enroll_image = '';
			}
			
			if ($get_img ['upload_file_thumb'] != null && $get_img ['upload_file_thumb'] != "") {
				$upload_file_thumb_1 = $get_img ['upload_file_thumb'];
			} else {
				$upload_file_thumb_1 = $enroll_image;
			}
			

			
			// $upload_file = $tag['upload_file_thumb'];
			$emp_last_name = $tag ['emp_last_name'];
			
			// $image_url = file_get_contents($upload_file);
			$image_url1 = ''; // 'data:image/jpg;base64,'.base64_encode($image_url);
			
			$check_img = $this->model_setting_image->checkresize ( $get_img ['enroll_image'] );
			
			$client_medicine = $this->model_resident_resident->gettagModule ( $tag ['tags_id'],'','' );

			//print_r($client_medicine);
			
			if ($client_medicine != null && $client_medicine != "") {
				$tagmed = '1';
			} else {
				$tagmed = '2';
			}
			
			$tagstatusinfo = $this->model_resident_resident->getTagstatusbyId ( $tag ['tags_id'] );
			
			if ($tagstatusinfo != NULL && $tagstatusinfo != "") {
				
				$status = $tagstatusinfo ['status'];
			} else {
				$status = '';
			}
			
			$alldata = $this->model_createtask_createtask->getalltaskbyid ( $tag ['tags_id'] );
			if ($alldata != NULL && $alldata != "") {
				$confirm_alert = "1";
			} else {
				$confirm_alert = "2";
			}
			
			$rresults = $this->model_setting_locations->getlocation($tag['room']);
			
			
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
				$client_view_options = str_replace('[facilities_id]', $result_info['facility'], $client_view_options); 
			  } else{
				$client_view_options = str_replace('[facilities_id]', '', $client_view_options); 
			  } 

			  if(isset($tag['room']) && $tag['room']!=''){
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


			if($tag['emp_first_name']){
				$emp_first_name = $tag['emp_first_name'];
			}else{
				$emp_first_name = '';
			}

			if($tag['emp_last_name']){
				$emp_last_name = $tag['emp_last_name'];
			}else{
				$emp_last_name = '';
			}

			  
			 
			if($client_view_options != "" && $client_view_options != null){
		  		$client_view_options_flag = nl2br($client_view_options);
			}else{
		  		$client_view_options_flag = $emp_first_name.' '.$emp_last_name;
			}
			
			$facilitynames = $this->model_facilities_facilities->getfacilities ( $tag ['facilities_id'] );
			
			if($facilitynames ['facility']!='' && $facilitynames ['facility']!=null){
				$facilityname = $facilitynames ['facility'];
			}else{
				$facilityname = '';
			}
			
			
			$role_call = $tag['role_call'];
			$role_callname = "";
			$color_code = "";
			$role_type = "0";
			$clientstatus_info = $this->model_notes_clientstatus->getclientstatus($role_call);
			if($clientstatus_info['name'] != null && $clientstatus_info['name'] != ""){
				$role_callname = $clientstatus_info['name'];
				$color_code = $clientstatus_info['color_code'];
				$role_type = $clientstatus_info['type'];
			}
			
			$this->load->model('form/form');
			$customlistvalues_info = $this->model_form_form->getcustomlistvalues($tag['customlistvalues_id']);
			
			if($customlistvalues_info['customlistvalues_name'] != null && $customlistvalues_info['customlistvalues_name'] != ""){
				$customlistvalues_name = $customlistvalues_info['customlistvalues_name'];
			}else{
				$customlistvalues_name = "";
			}

			$utresult [] = array (
					'emp_first_name' => $tag ['emp_first_name'] ? $tag ['emp_first_name'] : "",
					'emp_last_name' => $tag ['emp_last_name'] ? $tag ['emp_last_name'] : "",
					'discharge' => $tag ['discharge'],
					'emp_middle_name' => $tag['emp_middle_name'] ? $tag['emp_middle_name'] : "",
					'ssn' => $tag['ssn'],
					'location_name' => $rresults['location_name'],
					'facilityname' => $facilityname,
					'facilities_id' => $tag ['facilities_id'],
					'emp_first_name' => $emp_first_name,
					'emp_tag_id' => $tag ['emp_tag_id'] ? $tag ['emp_tag_id'] : "",
					'age' => $tag ['age'],
					'tags_id' => $tag ['tags_id'],
					'gender' => $customlistvalues_name,
					'emp_extid' => $tag ['emp_extid'] ? $tag ['emp_extid'] : "",
					'role_call' => $tag ['role_call'],
					'color_code' => $color_code,
					'role_type' => $role_type,
					'upload_file_thumb_1' => $upload_file_thumb_1 ? $upload_file_thumb_1 : "", 
					'privacy' => $tag ['privacy'],
					'stickynote' => $tag ['stickynote'] ? $tag ['stickynote'] : "",
					'age' => $tag ['age'],
					'room' => $tag ['room'] ? $tag ['room'] : "",
					'date_added' => date ( 'm-d-Y', strtotime ( $tag ['date_added'] ) ),
					'sticky_href' => $this->url->link ( 'resident/resident/getstickynote', '' . '&tags_id=' . $tag ['tags_id'], 'SSL' ),
					'tagstatus_info' => $status,
					'client_medicine' => $tagmed,
					'discharge_href' => $this->url->link ( 'notes/case', '' . '&tags_id=' . $tag ['tags_id'] . '&facilities_id=' . $tag ['facilities_id'], 'SSL' ) 
			);
		}
		
		return $utresult;
	}

	public function getupdatekeywordsReplica($udata = array()) {
		$ukresult = array ();
		$sql = "SELECT keyword_id,keyword_name,keyword_value,active_tag,keyword_image,facilities_id,relation_keyword_id,monitor_time,monitor_time_image,end_relation_keyword,is_special,sort_order,keyword_ids,recognition_type,is_recent,facility_type,user_group_ids,multiples_module,client_type,location_type,client_status FROM " . DB_PREFIX . "keyword where status = '1' ";
		$sql .= " and `update_date` BETWEEN  '" . $udata ['app_user_date'] . "' AND  '" . $udata ['current_date_user'] . " 23:59:59' ";
		$sql .= " and FIND_IN_SET('" . $udata ['facilities_id'] . "',facilities_id) ";
		
		$query = $this->db->query ( $sql );
		$krusers = $query->rows;
		
		$url2 = "";
		if ($udata ['facilities_id'] != null && $udata ['facilities_id'] != "") {
			$url2 .= '&facilities_id=' . $udata ['facilities_id'];
		}
		
		foreach ( $krusers as $result ) {
			/*
			 * if($result['keyword_image'] != null && $result['keyword_image'] != ""){
			 * $file1 = '/icon/'.$result['keyword_image'];
			 * $newfile4 = $this->model_setting_image->resize($file1, 70, 70);
			 * $newfile21 = DIR_IMAGE . $newfile4;
			 * $file12 = HTTP_SERVER . 'image/icon/'.$newfile4;
			 *
			 * $imageData1 = base64_encode(file_get_contents($newfile21));
			 * $strike_signature = 'data:'.$this->mime_content_type($file12).';base64,'.$imageData1;
			 * }else{
			 * $strike_signature = '';
			 * }
			 *
			 * if($result['monitor_time_image'] != null && $result['monitor_time_image'] != ""){
			 * $file111 = '/icon/'.$result['monitor_time_image'];
			 * $newfile4222 = $this->model_setting_image->resize($file111, 70, 70);
			 * $newfile2ww1 = DIR_IMAGE . $newfile4222;
			 * $file1ddd2 = HTTP_SERVER . 'image/icon/'.$newfile4222;
			 *
			 * $imageDatasss1 = base64_encode(file_get_contents($newfile2ww1));
			 * $monitor_time_image = 'data:'.$this->mime_content_type($file1ddd2).';base64,'.$imageDatasss1;
			 * }else{
			 * $monitor_time_image = '';
			 * }
			 */
			
			if ($result ['keyword_image'] && file_exists ( DIR_IMAGE . 'icon/' . $result ['keyword_image'] )) {
				$file1 = 'icon/' . $result ['keyword_image'];
				$newfile4 = $this->model_setting_image->resize ( $file1, 54, 54 );
				$file12 = HTTP_SERVER . 'image/' . $newfile4;
			} else {
				$file12 = '';
			}
			
			if ($result ['monitor_time'] == "2") {
				$is_special = '1';
			} else {
				$is_special = '0';
			}
			
			$activenote_url = "";
			if ($result ['monitor_time'] == "3") {
				
				$this->load->model ( 'setting/activeforms' );
				$this->load->model ( 'setting/keywords' );
				$dataforms = array (
						'facilities_id' => $udata ['facilities_id'],
						'monitor_time' => '3' 
				);
				
				$activefrom = $this->model_setting_activeforms->getActiveForm23 ( $result ['keyword_id'], $udata ['facilities_id'] );
				
				if (! empty ( $activefrom )) {
					
					$keydetail = $this->model_setting_keywords->getkeywordDetail ( $activefrom ['keyword_id'] );
					$formdetails = $this->model_form_form->getFormdata ( $activefrom ['forms_id'] );
					
					if ($formdetails ['open_search'] == '1') {
						
						$activenote_url = str_replace ( '&amp;', '&', $this->url->link ( 'services/linkedform', '' . '&forms_design_id=' . $activefrom ['forms_id'] . '&keyword_id=' . $activefrom ['keyword_id'] . '&activeform_id=' . $activefrom ['activeform_id'] . $url2, 'SSL' ) );
					} else {
						$activenote_url = str_replace ( '&amp;', '&', $this->url->link ( 'services/form', '' . '&forms_design_id=' . $activefrom ['forms_id'] . '&keyword_id=' . $activefrom ['keyword_id'] . '&activeform_id=' . $activefrom ['activeform_id'] . $url2, 'SSL' ) );
					}
				}
			}
			
			$modules = array();
			if($result['multiples_module'] != null && $result['multiples_module'] != ""){
				$taskmodules = unserialize ( $result ['multiples_module'] );
				foreach($taskmodules as $taskmodule){
					$modules[] = array(
						'name' => $taskmodule['name'],
						'action' => $taskmodule['action'],
						'measurement' => $taskmodule['measurement'],
						
						'autocomplete' => $taskmodule['autocomplete'],
						'autocomplete_type' => $taskmodule['autocomplete_type'],
						'data_type' => $taskmodule['data_type'],
						'default_value' => $taskmodule['default_value'],
					);
				}
			}
			
			$ukresult [] = array (
					'keyword_id' => $result ['keyword_id'],
					'active_tag' => $result ['active_tag'],
					'keyword_name' => $result ['keyword_name'],
					'keyword_image' => $result ['keyword_image'],
					'monitor_time' => $result ['monitor_time'],
					'is_special' => $is_special,
					'activenote_url' => $activenote_url,
					'modules' => $modules ,
					'is_recent' => $result ['is_recent'],
					'facility_type' => $result ['facility_type'],
					'client_type' => $result ['client_type'],
					'client_status' => $result ['client_status'],
					'user_group_ids' => $result ['user_group_ids'],
					'recognition_type' => $result ['recognition_type'],
					'facilities_id' => $result ['facilities_id'],

					//'location_type' => $result ['location_type'],
					//'img_icon' => $file12,
					//'relation_keyword_id' => $result ['relation_keyword_id'],
					//'sort_order' => $result ['sort_order'],
					//'end_relation_keyword' => $result ['end_relation_keyword'],
					//'keyword_ids' => $result ['keyword_ids'],
					
			)
			// 'monitor_time_image' => $file1ddd2,
			// 'is_monitor_time_sign' => $is_monitor_time_sign,
			;
		}
		return $ukresult;
	}

	public function getupdatehlightersReplica($udata = array()) {
		$uhresult = array ();
		$sql2 = "SELECT highlighter_id,highlighter_name,highlighter_value,highlighter_icon FROM " . DB_PREFIX . "highlighter where status = '1' ";
		$sql2 .= " and `update_date` BETWEEN  '" . $udata ['app_user_date'] . "' AND  '" . $udata ['current_date_user'] . " 23:59:59' ";
		
		$query = $this->db->query ( $sql2 );
		$frhlighters = $query->rows;
		
		foreach ( $frhlighters as $highlighter ) {
			if ($highlighter ['highlighter_icon'] && file_exists ( DIR_IMAGE . 'highlighter/' . $highlighter ['highlighter_icon'] )) {
				$file1 = '/highlighter/' . $highlighter ['highlighter_icon'];
				$newfile4 = $this->model_setting_image->resize ( $file1, 70, 70 );
				$newfile21 = DIR_IMAGE . $newfile4;
				$file12 = HTTP_SERVER . 'image/highlighter/' . $newfile4;
				
				$imageData1 = base64_encode ( file_get_contents ( $newfile21 ) );
				$strike_signature = 'data:' . $this->mime_content_type ( $file12 ) . ';base64,' . $imageData1;
			} else {
				$strike_signature = '';
			}
			
			$uhresult [] = array (
					'highlighter_id' => $highlighter ['highlighter_id'],
					'highlighter_name' => $highlighter ['highlighter_name'],
					'highlighter_value' => $highlighter ['highlighter_value'],
					'highlighter_icon' => $strike_signature 
			);
		}
		
		return $uhresult;
	}

	public function getupdatelocationsReplica($udata = array()) {
		$uhresult = array ();
		$sql2 = "SELECT * FROM " . DB_PREFIX . "locations where status = '1' ";
		$sql2 .= " and `update_date` BETWEEN  '" . $udata ['app_user_date'] . "' AND  '" . $udata ['current_date_user'] . " 23:59:59' ";
		
		if($udata['facilities_id'] != null && $udata['facilities_id'] != ""){
			$this->load->model('facilities/facilities');
			$facility_info = $this->model_facilities_facilities->getfacilities($udata['facilities_id']);
			$ddss = array();
			if ( $facility_info['client_facilities_ids'] != null && $facility_info['client_facilities_ids'] != "" ) {
				
				$ddss[] = $facility_info['client_facilities_ids'];
				$ddss[] = $udata['facilities_id'];
				
				$sssssddsg = explode(",",$facility_info ['client_facilities_ids']);
				$abdcg = array_unique($sssssddsg);
				$cids = array();
				foreach($abdcg as $fid){
					$cids[] = $fid;
				}
				$abdcgs = array_unique($cids);
				foreach($abdcgs as $fid2){
					$facilityinfo = $this->model_facilities_facilities->getfacilities ( $fid2 );
					if ( $facilityinfo['client_facilities_ids'] != null && $facilityinfo['client_facilities_ids'] != "" ) {
						$ddss[] = $facilityinfo['client_facilities_ids'];
					}
				}
				
				$sssssdd = implode(",",$ddss);
				
				$sql2.= " and facilities_id in (" . $sssssdd . ") ";
				
			}else{
				if ($udata['facilities_id'] != null && $udata['facilities_id'] != "") {
				$sql2.= " and facilities_id = '".$udata['facilities_id']."'";
				
				}
			}
		}
		
		$query = $this->db->query ( $sql2 );
		$ffrhlighters = $query->rows;
		
		foreach ( $ffrhlighters as $locData ) {
			
			$uhresult [] = array (
				'locations_id' => $locData ['locations_id'],
				'location_name' => $locData ['location_name'],
				'location_address' => $locData ['location_address'],
				'location_detail' => $locData ['location_detail'],
				'capacity' => $locData ['capacity'],
				'location_type' => $locData ['location_type'],
				'nfc_location_tag' => $locData ['nfc_location_tag'],
				'nfc_location_tag_required' => $locData ['nfc_location_tag_required'],
				'gps_location_tag' =>$locData['gps_location_tag'],
				'gps_location_tag_required' =>$locData['gps_location_tag_required'],
				'latitude' => $locData ['latitude'],
				'longitude' => $locData ['longitude'],
				'other_location_tag' =>$locData['other_location_tag'],
				'other_location_tag_required' =>$locData['other_location_tag_required'],
				'other_type_id' => $locData ['other_type_id'],
				'facilities_id' => $locData ['facilities_id'] 
			);
		}
		
		return $uhresult;
	}

	public function getupdatestatussReplica($udata = array()) {
		$uhresult = array ();
		$sql2 = "SELECT * FROM " . DB_PREFIX . "tag_status where status = '1' ";
		$sql2 .= " and `date_updated` BETWEEN  '" . $udata ['app_user_date'] . "' AND  '" . $udata ['current_date_user'] . " 23:59:59' ";
		
		if($udata['facilities_id'] != null && $udata['facilities_id'] != ""){
			$this->load->model('facilities/facilities');
			$facility_info = $this->model_facilities_facilities->getfacilities($udata['facilities_id']);
			$ddss = array();
			if ( $facility_info['client_facilities_ids'] != null && $facility_info['client_facilities_ids'] != "" ) {
				
				$ddss[] = $facility_info['client_facilities_ids'];
				
				$ddss[] = $udata['facilities_id'];
				$sssssdd = implode(",",$ddss);
				
				$sql2.= " and facilities_id in (" . $sssssdd . ") ";
				
			}else{
				if ($udata['facilities_id'] != null && $udata['facilities_id'] != "") {
				$sql2.= " and facilities_id = '".$udata['facilities_id']."'";
				
				}
			}
		}
		
		$query = $this->db->query ( $sql2 );
		$ffrhlighterss = $query->rows;
		
		foreach ( $ffrhlighterss as $customform ) {
			$rule_action_content = unserialize($customform['rule_action_content']);
			$out_from_cell = 0;
			if ($rule_action_content['out_from_cell'] == "1") {
				$out_from_cell = $rule_action_content['out_from_cell'];
			}
			
			$parent_ids = array();
			$parentids = unserialize($customform['parent_ids']);
			foreach($parentids as $parentid){
				$parent_ids[]['name'] = $parentid['name'];
			}
			
			
			if ($rule_action_content ['forms_id'] != '' && $rule_action_content ['forms_id'] != null) {
				$href = $this->url->link ( 'form/form', '' . '&forms_design_id=' . $rule_action_content ['forms_id'] . '&tag_status_id=' . $sclient_status ['tag_status_id'] . $url2, 'SSL' );
			} else {
				$href = "";
			}
			$uhresult [] = array (
				'tag_status_id' => $customform['tag_status_id'],
				'name' => $customform['name'],
				'facilities_id' => $customform['facilities_id'],
				'display_client' => $customform['display_client'],
				'image' => $customform['image'],
				'type' => $customform['type'],
				'status_type' => $customform['status_type'],
				'is_facility' => $customform['is_facility'],
				'disabled_escorted' => $customform['disabled_escorted'],
				'facility_type' => $customform['facility_type'],
				'parent_ids' =>  $parent_ids,
				'out_from_cell' => $out_from_cell,
				'rule_action_content' =>  $rule_action_content,
				'formhref' => $href 
			);
		}
		
		return $uhresult;
	}

	public function getupdateclassificationsReplica($udata = array()) {
		$uhresult = array ();
		$sql2 = "SELECT * FROM " . DB_PREFIX . "tag_classification where status = '1' ";
		$sql2 .= " and `date_updated` BETWEEN  '" . $udata ['app_user_date'] . "' AND  '" . $udata ['current_date_user'] . " 23:59:59' ";
		
		if($udata['facilities_id'] != null && $udata['facilities_id'] != ""){
			$this->load->model('facilities/facilities');
			$facility_info = $this->model_facilities_facilities->getfacilities($udata['facilities_id']);
			$ddss = array();
			if ( $facility_info['client_facilities_ids'] != null && $facility_info['client_facilities_ids'] != "" ) {
				
				$ddss[] = $facility_info['client_facilities_ids'];
				
				$ddss[] = $udata['facilities_id'];
				$sssssdd = implode(",",$ddss);
				
				$sql2.= " and facilities_id in (" . $sssssdd . ") ";
				
			}else{
				if ($udata['facilities_id'] != null && $udata['facilities_id'] != "") {
				$sql2.= " and facilities_id = '".$udata['facilities_id']."'";
				
				}
			}
		}

		//echo $sql2;
		
		$query = $this->db->query ( $sql2 );
		$ffrhlighterss = $query->rows;
		
		foreach ( $ffrhlighterss as $customform1 ) {
			
			$uhresult [] = array (
				'tag_classification_id' => $customform1['tag_classification_id'],
				'classification_name' => $customform1['classification_name'],
				'facilities_id' => $customform1['facilities_id'],
				'color_code' => $customform1['color_code'],
			);
		}
		
		return $uhresult;
	}

	public function getcustomlistsReplica($udata = array()) {
		$uhresult = array ();
		
		
		$this->load->model('facilities/facilities');
		$facilityinfo = $this->model_facilities_facilities->getfacilities($udata['facilities_id']);
		$this->load->model('notes/notes');
		
		if ($facilityinfo['config_tags_customlist_id'] != NULL && $facilityinfo['config_tags_customlist_id'] != "") {
			
			$d = array();
			$d['customlist_id'] = $facilityinfo['config_tags_customlist_id'];
			$customlists = $this->model_notes_notes->getcustomlists($d);
			
			if ($customlists) {
				foreach ($customlists as $customlist) {
					$d2 = array();
					$d2['customlist_id'] = $customlist['customlist_id'];
					$d2['current_date_user'] = $udata['current_date_user'];
					$d2['app_user_date'] = $udata['app_user_date'];
					
					$customlistvalues = $this->model_notes_notes->getcustomlistvaluesReplica($d2);
					
					$uhresult[] = array(
							'customlist_id' => $customlist['customlist_id'],
							'customlist_name' => $customlist['customlist_name'],
							'customlistvalues' => $customlistvalues
					);
				}
			}
		}	
		return $uhresult;
	}

	/*--------------------------------Replica for app_user_date and some fields end-----------------------------*/



	public function getcustomlists($udata = array()) {
		$uhresult = array ();
		
		
		$this->load->model('facilities/facilities');
		$facilityinfo = $this->model_facilities_facilities->getfacilities($udata['facilities_id']);
		$this->load->model('notes/notes');
		
		if ($facilityinfo['config_tags_customlist_id'] != NULL && $facilityinfo['config_tags_customlist_id'] != "") {
			
			$d = array();
			$d['customlist_id'] = $facilityinfo['config_tags_customlist_id'];
			$customlists = $this->model_notes_notes->getcustomlists($d);
			
			if ($customlists) {
				foreach ($customlists as $customlist) {
					$d2 = array();
					$d2['customlist_id'] = $customlist['customlist_id'];
					$d2['current_date_user'] = $udata['current_date_user'];
					
					$customlistvalues = $this->model_notes_notes->getcustomlistvalues($d2);
					
					$uhresult[] = array(
							'customlist_id' => $customlist['customlist_id'],
							'customlist_name' => $customlist['customlist_name'],
							'customlistvalues' => $customlistvalues
					);
				}
			}
		}
		
		
		return $uhresult;
	}
	public function getupdateclassifications($udata = array()) {
		$uhresult = array ();
		$sql2 = "SELECT * FROM " . DB_PREFIX . "tag_classification where status = '1' ";
		$sql2 .= " and `date_updated` BETWEEN  '" . $udata ['current_date_user'] . " 00:00:00' AND  '" . $udata ['current_date_user'] . " 23:59:59' ";
		
		if($udata['facilities_id'] != null && $udata['facilities_id'] != ""){
			$this->load->model('facilities/facilities');
			$facility_info = $this->model_facilities_facilities->getfacilities($udata['facilities_id']);
			$ddss = array();
			if ( $facility_info['client_facilities_ids'] != null && $facility_info['client_facilities_ids'] != "" ) {
				
				$ddss[] = $facility_info['client_facilities_ids'];
				
				$ddss[] = $udata['facilities_id'];
				$sssssdd = implode(",",$ddss);
				
				$sql2.= " and facilities_id in (" . $sssssdd . ") ";
				
			}else{
				if ($udata['facilities_id'] != null && $udata['facilities_id'] != "") {
				$sql2.= " and facilities_id = '".$udata['facilities_id']."'";
				
				}
			}
		}
		
		$query = $this->db->query ( $sql2 );
		$ffrhlighterss = $query->rows;
		
		foreach ( $ffrhlighterss as $customform1 ) {
			
			$uhresult [] = array (
				'tag_classification_id' => $customform1['tag_classification_id'],
				'classification_name' => $customform1['classification_name'],
				'facilities_id' => $customform1['facilities_id'],
				'color_code' => $customform1['color_code'],
			);
		}
		
		return $uhresult;
	}
	
	public function getupdatestatuss($udata = array()) {
		$uhresult = array ();
		$sql2 = "SELECT * FROM " . DB_PREFIX . "tag_status where status = '1' ";
		$sql2 .= " and `date_updated` BETWEEN  '" . $udata ['current_date_user'] . " 00:00:00' AND  '" . $udata ['current_date_user'] . " 23:59:59' ";
		
		if($udata['facilities_id'] != null && $udata['facilities_id'] != ""){
			$this->load->model('facilities/facilities');
			$facility_info = $this->model_facilities_facilities->getfacilities($udata['facilities_id']);
			$ddss = array();
			if ( $facility_info['client_facilities_ids'] != null && $facility_info['client_facilities_ids'] != "" ) {
				
				$ddss[] = $facility_info['client_facilities_ids'];
				
				$ddss[] = $udata['facilities_id'];
				$sssssdd = implode(",",$ddss);
				
				$sql2.= " and facilities_id in (" . $sssssdd . ") ";
				
			}else{
				if ($udata['facilities_id'] != null && $udata['facilities_id'] != "") {
				$sql2.= " and facilities_id = '".$udata['facilities_id']."'";
				
				}
			}
		}
		
		$query = $this->db->query ( $sql2 );
		$ffrhlighterss = $query->rows;
		
		foreach ( $ffrhlighterss as $customform ) {
			
			$rule_action_content = unserialize($customform['rule_action_content']);
			$out_from_cell = 0;
			if ($rule_action_content['out_from_cell'] == "1") {
				$out_from_cell = $rule_action_content['out_from_cell'];
			}
			
			$parent_ids = array();
			$parentids = unserialize($customform['parent_ids']);
			foreach($parentids as $parentid){
				$parent_ids[]['name'] = $parentid['name'];
			}
			
			
			if ($rule_action_content ['forms_id'] != '' && $rule_action_content ['forms_id'] != null) {
				$href = $this->url->link ( 'form/form', '' . '&forms_design_id=' . $rule_action_content ['forms_id'] . '&tag_status_id=' . $sclient_status ['tag_status_id'] . $url2, 'SSL' );
			} else {
				$href = "";
			}

				
			$uhresult [] = array (
				'tag_status_id' => $customform['tag_status_id'],
				'name' => $customform['name'],
				'facilities_id' => $customform['facilities_id'],
				'display_client' => $customform['display_client'],
				'image' => $customform['image'],
				'type' => $customform['type'],
				'status_type' => $customform['status_type'],
				'is_facility' => $customform['is_facility'],
				'disabled_escorted' => $customform['disabled_escorted'],
				'facility_type' => $customform['facility_type'],
				'parent_ids' =>  $parent_ids,
				'rule_action_content' =>  $rule_action_content,
				'out_from_cell' => $out_from_cell,
				'formhref' => $href 
			);
		}
		
		return $uhresult;
	}
	public function getupdatelocations($udata = array()) {
		$uhresult = array ();
		$sql2 = "SELECT * FROM " . DB_PREFIX . "locations where status = '1' ";
		$sql2 .= " and `update_date` BETWEEN  '" . $udata ['current_date_user'] . " 00:00:00' AND  '" . $udata ['current_date_user'] . " 23:59:59' ";
		
		if($udata['facilities_id'] != null && $udata['facilities_id'] != ""){
			$this->load->model('facilities/facilities');
			$facility_info = $this->model_facilities_facilities->getfacilities($udata['facilities_id']);
			$ddss = array();
			if ( $facility_info['client_facilities_ids'] != null && $facility_info['client_facilities_ids'] != "" ) {
				
				$ddss[] = $facility_info['client_facilities_ids'];
				
				$ddss[] = $udata['facilities_id'];
				$sssssdd = implode(",",$ddss);
				
				$sql2.= " and facilities_id in (" . $sssssdd . ") ";
				
			}else{
				if ($udata['facilities_id'] != null && $udata['facilities_id'] != "") {
				$sql2.= " and facilities_id = '".$udata['facilities_id']."'";
				
				}
			}
		}
		
		$query = $this->db->query ( $sql2 );
		$ffrhlighters = $query->rows;
		
		foreach ( $ffrhlighters as $locData ) {
			
			$uhresult [] = array (
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
			);
		}
		
		return $uhresult;
	}
	
	public function getupdatehlighters($udata = array()) {
		$uhresult = array ();
		$sql2 = "SELECT highlighter_id,highlighter_name,highlighter_value,highlighter_icon FROM " . DB_PREFIX . "highlighter where status = '1' ";
		$sql2 .= " and `update_date` BETWEEN  '" . $udata ['current_date_user'] . " 00:00:00' AND  '" . $udata ['current_date_user'] . " 23:59:59' ";
		
		$query = $this->db->query ( $sql2 );
		$frhlighters = $query->rows;
		
		foreach ( $frhlighters as $highlighter ) {
			if ($highlighter ['highlighter_icon'] && file_exists ( DIR_IMAGE . 'highlighter/' . $highlighter ['highlighter_icon'] )) {
				$file1 = '/highlighter/' . $highlighter ['highlighter_icon'];
				$newfile4 = $this->model_setting_image->resize ( $file1, 70, 70 );
				$newfile21 = DIR_IMAGE . $newfile4;
				$file12 = HTTP_SERVER . 'image/highlighter/' . $newfile4;
				
				$imageData1 = base64_encode ( file_get_contents ( $newfile21 ) );
				$strike_signature = 'data:' . $this->mime_content_type ( $file12 ) . ';base64,' . $imageData1;
			} else {
				$strike_signature = '';
			}
			
			$uhresult [] = array (
					'highlighter_id' => $highlighter ['highlighter_id'],
					'highlighter_name' => $highlighter ['highlighter_name'],
					'highlighter_value' => $highlighter ['highlighter_value'],
					'highlighter_icon' => $strike_signature 
			);
		}
		
		return $uhresult;
	}
	public function getupdateusers($udata = array()) {
		$uresult = array ();
		$sql2 = "SELECT user_id,user_group_id,username,firstname,lastname,email,user_pin,facilities,phone_number,activationKey,default_facilities_id,default_highlighter_id,default_color,user_otp,message_sid,facilities_display,default_facility_id,enroll_image,status,reset_password_otp FROM `" . DB_PREFIX . "user` ";
		$sql2 .= "where 1 = '1' ";
		
		$sql2 .= " and status = '1' ";
		
		$sql2 .= " and `update_date` BETWEEN  '" . $udata ['current_date_user'] . " 00:00:00' AND  '" . $udata ['current_date_user'] . " 23:59:59' ";
		$sql2 .= " and FIND_IN_SET('" . $udata ['facilities_id'] . "', facilities) ";
		
		$query = $this->db->query ( $sql2 );
		$frusers = $query->rows;
		
		foreach ( $frusers as $user ) {
			$uresult [] = array (
					'user_id' => $user ['user_id'],
					'username' => $user ['username'],
					'firstname' => $user ['firstname'],
					'lastname' => $user ['lastname'],
					'user_pin' => $user ['user_pin'],
					'phone_number' => $user ['phone_number'],
					'email' => $user ['email'],
					'status' => $user ['status'],
					'user_group_id' => $user ['user_group_id'] 
			);
		}
		
		return $uresult;
	}
	
	public function getupdatetags($udata = array()) {
		$utresult = array ();
		$sql = "SELECT tags_id,emp_tag_id,emp_first_name,emp_middle_name,emp_last_name,privacy,date_added,dob,locations_id,facilities_id,upload_file,tags_pin,gender,discharge,age,role_call,location_address,latitude,longitude,emp_extid,address_street2,person_screening,date_of_screening,ssn,state,city,zipcode,room,restriction_notes,prescription,alert_info,constant_sight,med_mental_health,tagstatus,forms_id,tags_forms_id,discharge_date,stickynote,customlistvalues_id,tags_status,tags_status_in,referred_facility,emergency_contact,reminder_time,reminder_date,upload_file_thumb,medication_inout FROM `" . DB_PREFIX . "tags` where status = 1  ";
		
		$sql .= " and `modify_date` BETWEEN  '" . $udata ['current_date_user'] . " 00:00:00' AND  '" . $udata ['current_date_user'] . " 23:59:59' ";
		
		if($udata['facilities_id'] != null && $udata['facilities_id'] != ""){
			$this->load->model('facilities/facilities');
			$facility_info = $this->model_facilities_facilities->getfacilities($udata['facilities_id']);
			$ddss = array();
			if ( $facility_info['client_facilities_ids'] != null && $facility_info['client_facilities_ids'] != "" ) {
				
				$ddss[] = $facility_info['client_facilities_ids'];
				
				$ddss[] = $udata['facilities_id'];
				$sssssdd = implode(",",$ddss);
				
				$sql.= " and facilities_id in (" . $sssssdd . ") ";
				
			}else{
				if ($udata['facilities_id'] != null && $udata['facilities_id'] != "") {
				$sql.= " and facilities_id = '".$udata['facilities_id']."'";
				
				}
			}
		}
		
		//$sql .= " and facilities_id = '" . $udata ['facilities_id'] . "'";
		
		$query = $this->db->query ( $sql );
		$krusers = $query->rows;
		
		$this->load->model ( 'setting/tags' );
		$this->load->model ( 'form/form' );
		
		$this->load->model ( 'setting/image' );
		$this->load->model ( 'facilities/facilities' );
		$this->load->model ( 'resident/resident' );
		$this->load->model ( 'createtask/createtask' );
		$this->load->model('notes/clientstatus');
		
		$this->load->model ( 'facilities/facilities' );
		$this->load->model ( 'setting/locations' );

		$facility = $this->model_facilities_facilities->getfacilities ( $udata['facilities_id']);
		
		$unique_id = $facility ['customer_key'];

	   // var_dump($unique_id); die;
		
		$this->load->model ( 'customer/customer' );
		
		$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
		
	   
		$client_info = unserialize($customer_info['client_info_notes']);

		$client_view_options2 = $client_info["client_view_options"]; 

		//echo '<pre>'; print_r($client_info); echo '</pre>';  die;

		// $this->data['client_view_options'] = '[emp_first_name],[emp_middle_name],[emp_last_name],[emergency_contact],[gender],[age]';
		$this->data['show_client_image'] = $client_info["show_client_image"];
		$this->data['show_form_tag'] = $client_info["show_form_tag"];
		$this->data['show_task'] = $client_info["show_task"];
		$this->data['show_case'] = $client_info["show_case"];
		
		foreach ( $krusers as $tag ) {
			
			$role_call = $tag ['role_call'];
			
			$get_img = $this->model_setting_tags->getImage ( $tag ['tags_id'] );
			
			if ($get_img ['upload_file_thumb'] != null && $get_img ['upload_file_thumb'] != "") {
				$upload_file_thumb_1 = $get_img ['upload_file_thumb'];
			} else {
				$upload_file_thumb_1 = $get_img ['enroll_image'];
			}
			
			$enroll_image = $get_img ['enroll_image'];
			// $upload_file = $tag['upload_file_thumb'];
			$emp_last_name = $tag ['emp_last_name'];
			
			// $image_url = file_get_contents($upload_file);
			$image_url1 = ''; // 'data:image/jpg;base64,'.base64_encode($image_url);
			
			$check_img = $this->model_setting_image->checkresize ( $get_img ['enroll_image'] );
			
			$client_medicine = $this->model_resident_resident->gettagModule ( $tag ['tags_id'] );
			
			if ($client_medicine != null && $client_medicine != "") {
				$tagmed = '1';
			} else {
				$tagmed = '2';
			}
			
			$tagstatusinfo = $this->model_resident_resident->getTagstatusbyId ( $tag ['tags_id'] );
			
			if ($tagstatusinfo != NULL && $tagstatusinfo != "") {
				
				$status = $tagstatusinfo ['status'];
			} else {
				$status = '';
			}
			
			$alldata = $this->model_createtask_createtask->getalltaskbyid ( $tag ['tags_id'] );
			if ($alldata != NULL && $alldata != "") {
				$confirm_alert = "1";
			} else {
				$confirm_alert = "2";
			}
			$rresults = $this->model_setting_locations->getlocation($tag['room']);
			
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
				$client_view_options = str_replace('[facilities_id]', $result_info['facility'], $client_view_options); 
			  } else{
				$client_view_options = str_replace('[facilities_id]', '', $client_view_options); 
			  } 

			  if(isset($tag['room']) && $tag['room']!=''){
				  
				
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
			
			$facilitynames = $this->model_facilities_facilities->getfacilities ( $tag ['facilities_id'] );
			$facilityname = $facilitynames ['facility'];
			
			$role_call = $tag['role_call'];
			$role_callname = "";
			$color_code = "";
			$role_type = "0";
			$clientstatus_info = $this->model_notes_clientstatus->getclientstatus($role_call);
			if($clientstatus_info['name'] != null && $clientstatus_info['name'] != ""){
				$role_callname = $clientstatus_info['name'];
				$color_code = $clientstatus_info['color_code'];
				$role_type = $clientstatus_info['type'];
			}
			
			$this->load->model('form/form');
			$customlistvalues_info = $this->model_form_form->getcustomlistvalues($tag['customlistvalues_id']);
			if($customlistvalues_info['customlistvalues_name'] != null && $customlistvalues_info['customlistvalues_name'] != ""){
				$customlistvalues_name = $customlistvalues_info['customlistvalues_name'];
			}else{
				$customlistvalues_name = "";
			}
			
			$utresult [] = array (
					'emp_first_name' => $tag ['emp_first_name'],
					'emp_last_name' => $tag ['emp_last_name'],
					'discharge' => $tag ['discharge'],
					'medication_inout' => $tag ['medication_inout'],
					'emp_middle_name' => $tag['emp_middle_name'],
					'ssn' => $tag['ssn'],
					'location_name' => $rresults['location_name'],
					
					'name' => $tag['emp_first_name'].' '.$tag['emp_last_name'],
					'name2' => $client_view_options_flag,
					'facilityname' => $facilityname,
					'facilities_id' => $tag ['facilities_id'],
					'emp_first_name' => $tag ['emp_first_name'],
					'emp_tag_id' => $tag ['emp_tag_id'],
					'age' => $tag ['age'],
					'tags_id' => $tag ['tags_id'],
					'gender' => $customlistvalues_name,
					'emp_extid' => $tag ['emp_extid'],
					'role_call' => $tag ['role_call'],
					'role_callname' => $role_callname,
					'color_code' => $color_code,
					'role_type' => $role_type,
					'upload_file' => $enroll_image,
					'upload_file_thumb' => $get_img ['upload_file_thumb'],
					'upload_file_thumb_1' => $upload_file_thumb_1 ? $upload_file_thumb_1 : "",
					'check_img' => $check_img,
					// 'upload_file' => $upload_file,
					'image_url1' => $image_url1,
					'privacy' => $tag ['privacy'],
					'stickynote' => $tag ['stickynote'],
					'tagallforms' => $forms,
					'tagcolors' => $tagcolors,
					'age' => $tag ['age'],
					'tagstatus' => $tag ['tagstatus'],
					'tags_status_in' => $tag ['tags_status_in'],
					'med_mental_health' => $tag ['med_mental_health'],
					'alert_info' => $tag ['alert_info'],
					'prescription' => $tag ['prescription'],
					'restriction_notes' => $tag ['restriction_notes'],
					'room' => $tag ['room'],
					'date_added' => date ( 'm-d-Y', strtotime ( $tag ['date_added'] ) ),
					'sticky_href' => $this->url->link ( 'resident/resident/getstickynote', '' . '&tags_id=' . $tag ['tags_id'], 'SSL' ),
					'tasksinfo' => $tasksinfo1,
					'tagstatus_info' => $status,
					'taskTotal' => $tttaskTotal,
					'recentnote' => $nnotes_description,
					'recenttasks' => $tdescription,
					'ndate_added' => $ndate_added,
					'client_medicine' => $tagmed,
					'screenig_url' => $screenig_url,
					'confirm_alert' => $confirm_alert,
					'discharge_href' => $this->url->link ( 'notes/case', '' . '&tags_id=' . $tag ['tags_id'] . '&facilities_id=' . $tag ['facilities_id'], 'SSL' ) 
			);
		}
		
		return $utresult;
	}
	public function getupdatekeywords($udata = array()) {
		$ukresult = array ();
		$sql = "SELECT keyword_id,keyword_name,keyword_value,active_tag,keyword_image,facilities_id,relation_keyword_id,monitor_time,monitor_time_image,end_relation_keyword,is_special,sort_order,keyword_ids,recognition_type,is_recent,facility_type,user_group_ids,multiples_module,client_type,location_type,client_status FROM " . DB_PREFIX . "keyword where status = '1' ";
		$sql .= " and `update_date` BETWEEN  '" . $udata ['current_date_user'] . " 00:00:00' AND  '" . $udata ['current_date_user'] . " 23:59:59' ";
		$sql .= " and FIND_IN_SET('" . $udata ['facilities_id'] . "',facilities_id) ";
		
		$query = $this->db->query ( $sql );
		$krusers = $query->rows;
		
		$url2 = "";
		if ($udata ['facilities_id'] != null && $udata ['facilities_id'] != "") {
			$url2 .= '&facilities_id=' . $udata ['facilities_id'];
		}
		
		foreach ( $krusers as $result ) {
			/*
			 * if($result['keyword_image'] != null && $result['keyword_image'] != ""){
			 * $file1 = '/icon/'.$result['keyword_image'];
			 * $newfile4 = $this->model_setting_image->resize($file1, 70, 70);
			 * $newfile21 = DIR_IMAGE . $newfile4;
			 * $file12 = HTTP_SERVER . 'image/icon/'.$newfile4;
			 *
			 * $imageData1 = base64_encode(file_get_contents($newfile21));
			 * $strike_signature = 'data:'.$this->mime_content_type($file12).';base64,'.$imageData1;
			 * }else{
			 * $strike_signature = '';
			 * }
			 *
			 * if($result['monitor_time_image'] != null && $result['monitor_time_image'] != ""){
			 * $file111 = '/icon/'.$result['monitor_time_image'];
			 * $newfile4222 = $this->model_setting_image->resize($file111, 70, 70);
			 * $newfile2ww1 = DIR_IMAGE . $newfile4222;
			 * $file1ddd2 = HTTP_SERVER . 'image/icon/'.$newfile4222;
			 *
			 * $imageDatasss1 = base64_encode(file_get_contents($newfile2ww1));
			 * $monitor_time_image = 'data:'.$this->mime_content_type($file1ddd2).';base64,'.$imageDatasss1;
			 * }else{
			 * $monitor_time_image = '';
			 * }
			 */
			
			if ($result ['keyword_image'] && file_exists ( DIR_IMAGE . 'icon/' . $result ['keyword_image'] )) {
				$file1 = 'icon/' . $result ['keyword_image'];
				$newfile4 = $this->model_setting_image->resize ( $file1, 54, 54 );
				$file12 = HTTP_SERVER . 'image/' . $newfile4;
			} else {
				$file12 = '';
			}
			
			if ($result ['monitor_time'] == "2") {
				$is_special = '1';
			} else {
				$is_special = '0';
			}
			
			$activenote_url = "";
			if ($result ['monitor_time'] == "3") {
				
				$this->load->model ( 'setting/activeforms' );
				$this->load->model ( 'setting/keywords' );
				$dataforms = array (
						'facilities_id' => $udata ['facilities_id'],
						'monitor_time' => '3' 
				);
				
				$activefrom = $this->model_setting_activeforms->getActiveForm23 ( $result ['keyword_id'], $udata ['facilities_id'] );
				
				if (! empty ( $activefrom )) {
					
					$keydetail = $this->model_setting_keywords->getkeywordDetail ( $activefrom ['keyword_id'] );
					$formdetails = $this->model_form_form->getFormdata ( $activefrom ['forms_id'] );
					
					if ($formdetails ['open_search'] == '1') {
						
						$activenote_url = str_replace ( '&amp;', '&', $this->url->link ( 'services/linkedform', '' . '&forms_design_id=' . $activefrom ['forms_id'] . '&keyword_id=' . $activefrom ['keyword_id'] . '&activeform_id=' . $activefrom ['activeform_id'] . $url2, 'SSL' ) );
					} else {
						$activenote_url = str_replace ( '&amp;', '&', $this->url->link ( 'services/form', '' . '&forms_design_id=' . $activefrom ['forms_id'] . '&keyword_id=' . $activefrom ['keyword_id'] . '&activeform_id=' . $activefrom ['activeform_id'] . $url2, 'SSL' ) );
					}
				}
			}
			
			$modules = array();
			if($result['multiples_module'] != null && $result['multiples_module'] != ""){
				$taskmodules = unserialize ( $result ['multiples_module'] );
				foreach($taskmodules as $taskmodule){
					$modules[] = array(
						'name' => $taskmodule['name'],
						'action' => $taskmodule['action'],
						'measurement' => $taskmodule['measurement'],
						'autocomplete' => $taskmodule['autocomplete'],
						'autocomplete_type' => $taskmodule['autocomplete_type'],
						'data_type' => $taskmodule['data_type'],
						'default_value' => $taskmodule['default_value'],
					);
				}
			}
			
			$ukresult [] = array (
					'keyword_id' => $result ['keyword_id'],
					'active_tag' => $result ['active_tag'],
					'keyword_name' => $result ['keyword_name'],
					'keyword_image' => $result ['keyword_image'],
					'img_icon' => $file12,
					'relation_keyword_id' => $result ['relation_keyword_id'],
					'sort_order' => $result ['sort_order'],
					'monitor_time' => $result ['monitor_time'],
					'end_relation_keyword' => $result ['end_relation_keyword'],
					//'relation_hastag' => $result['relation_hastag'],
					//'monitor_time_image' => $result['monitor_time_image'],
					'is_special' => $is_special,
					'activenote_url' => $activenote_url,
					'modules' => $modules ,
					'keyword_ids' => $result ['keyword_ids'],
					'recognition_type' => $result ['recognition_type'],
					'is_recent' => $result ['is_recent'],
					'facility_type' => $result ['facility_type'],
					'client_type' => $result ['client_type'],
					'location_type' => $result ['location_type'],
					'client_status' => $result ['client_status'],
					'user_group_ids' => $result ['user_group_ids'],
					'facilities_id' => $result ['facilities_id'],
			)
			// 'monitor_time_image' => $file1ddd2,
			// 'is_monitor_time_sign' => $is_monitor_time_sign,
			;
		}
		return $ukresult;
	}
	
	public function getupdatekeywordsurl($udata = array()) {
		$ukresult = array ();
		$sql = "SELECT keyword_id,keyword_name,keyword_value,active_tag,keyword_image,facilities_id,relation_keyword_id,monitor_time,monitor_time_image,end_relation_keyword,is_special,sort_order,keyword_ids,recognition_type,is_recent,facility_type,user_group_ids,multiples_module,client_type,location_type,client_status FROM " . DB_PREFIX . "keyword where status = '1' ";
		$sql .= " and `update_date` BETWEEN  '" . $udata ['current_date_user'] . " 00:00:00' AND  '" . $udata ['current_date_user'] . " 23:59:59' ";
		$sql .= " and FIND_IN_SET('" . $udata ['facilities_id'] . "',facilities_id) ";
		
		$query = $this->db->query ( $sql );
		$krusers = $query->rows;
		
		$url2 = "";
		if ($udata ['facilities_id'] != null && $udata ['facilities_id'] != "") {
			$url2 .= '&facilities_id=' . $udata ['facilities_id'];
		}
		
		foreach ( $krusers as $result ) {
			if ($result ['keyword_image'] && file_exists ( DIR_IMAGE . 'icon/' . $result ['keyword_image'] )) {
				$file1 = 'icon/' . $result ['keyword_image'];
				$newfile4 = $this->model_setting_image->resize ( $file1, 54, 54 );
				$file12 = HTTP_SERVER . 'image/' . $newfile4;
			} else {
				$file12 = '';
			}
			
			if ($result ['monitor_time'] == "2") {
				$is_special = '1';
			} else {
				$is_special = '0';
			}
			
			$activenote_url = "";
			if ($result ['monitor_time'] == "3") {
				
				$this->load->model ( 'setting/activeforms' );
				$this->load->model ( 'setting/keywords' );
				$dataforms = array (
						'facilities_id' => $udata ['facilities_id'],
						'monitor_time' => '3' 
				);
				
				$activefrom = $this->model_setting_activeforms->getActiveForm23 ( $result ['keyword_id'], $udata ['facilities_id'] );
				
				if (! empty ( $activefrom )) {
					
					$keydetail = $this->model_setting_keywords->getkeywordDetail ( $activefrom ['keyword_id'] );
					$formdetails = $this->model_form_form->getFormdata ( $activefrom ['forms_id'] );
					
					if ($formdetails ['open_search'] == '1') {
						
						$activenote_url = str_replace ( '&amp;', '&', $this->url->link ( 'services/linkedform', '' . '&forms_design_id=' . $activefrom ['forms_id'] . '&keyword_id=' . $activefrom ['keyword_id'] . '&activeform_id=' . $activefrom ['activeform_id'] . $url2, 'SSL' ) );
					} else {
						$activenote_url = str_replace ( '&amp;', '&', $this->url->link ( 'services/form', '' . '&forms_design_id=' . $activefrom ['forms_id'] . '&keyword_id=' . $activefrom ['keyword_id'] . '&activeform_id=' . $activefrom ['activeform_id'] . $url2, 'SSL' ) );
					}
				}
			}
			
			$modules = array();
			if($result['multiples_module'] != null && $result['multiples_module'] != ""){
				$taskmodules = unserialize ( $result ['multiples_module'] );
				foreach($taskmodules as $taskmodule){
					$modules[] = array(
						'name' => $taskmodule['name'],
						'action' => $taskmodule['action'],
						'measurement' => $taskmodule['measurement'],
					);
				}
			}
			
			$ukresult [] = array (
					'keyword_id' => $result ['keyword_id'],
					'active_tag' => $result ['active_tag'],
					'keyword_name' => $result ['keyword_name'],
					'keyword_image' => $result ['keyword_image'],
					'img_icon' => $file12,
					'relation_keyword_id' => $result ['relation_keyword_id'],
					'sort_order' => $result ['sort_order'],
					'monitor_time' => $result ['monitor_time'],
					'end_relation_keyword' => $result ['end_relation_keyword'],
					// 'relation_hastag' => $result['relation_hastag'],
					// 'monitor_time_image' => $result['monitor_time_image'],
					'is_special' => $is_special,
					'activenote_url' => $activenote_url,
					'modules' => $modules ,
					'keyword_ids' => $result ['keyword_ids'],
					'recognition_type' => $result ['recognition_type'],
					'is_recent' => $result ['is_recent'],
					'facility_type' => $result ['facility_type'],
					'user_group_ids' => $result ['user_group_ids'],
					'client_type' => $result ['client_type'],
					'location_type' => $result ['location_type'],
					'client_status' => $result ['client_status'],
			)
			// 'monitor_time_image' => $file1ddd2,
			// 'is_monitor_time_sign' => $is_monitor_time_sign,
			;
		}
		return $ukresult;
	}
	public function getfacilitiessetting($udata = array()) {
		$ufresult = array ();
		$sql = "SELECT facilities_id,timezone_id,facility,password,salt,firstname,lastname,email,status,users,config_task_status,config_tag_status,sms_number,config_taskform_status,config_noteform_status,config_rules_status,latitude,longitude,config_display_camera,config_display_dashboard,config_share_notes,config_sharepin_status,config_multiple_activenote,sharenotes_print,sharenotes_modify,sharenotes_copy,sharenotes_assemble,config_send_email_share_notes,config_rolecall_customlist_id,config_tags_customlist_id,config_bedcheck_customlist_id,form_print_layout,is_web_notification,web_audio_file,web_is_snooze,web_is_dismiss,is_android_notification,android_audio_file,is_android_snooze,is_android_dismiss,is_ios_notification,ios_audio_file,is_ios_snooze,is_ios_dismiss,device_ids,device_username,device_token,is_enable_beacon,beacon_range,beacon_data_type_range,config_current_location,is_discharge_form_enable,discharge_form_id,is_data_sync,data_sync_date_to,data_sync_date_from,is_fingerprint_enable,is_sms_enable,is_pin_enable,is_enable_add_notes_by,allow_quick_save,allow_face_without_verified,face_similar_percent,display_attchament,is_client_facial,is_master_facility,notes_facilities_ids,client_facilities_ids,required_escorted,enable_escorted FROM `" . DB_PREFIX . "facilities` WHERE facilities_id = '" . ( int ) $udata ['facilities_id'] . "' ";
		$sql .= " and `update_date` BETWEEN  '" . $udata ['current_date_user'] . " 00:00:00' AND  '" . $udata ['current_date_user'] . " 23:59:59' ";
		
		$query = $this->db->query ( $sql );
		$facility_info = $query->row;
		
		if (! empty ( $facility_info )) {
			
			if ($this->config->get ( 'config_date_picker' ) != null && $this->config->get ( 'config_date_picker' ) != "") {
				$config_date_picker = $this->config->get ( 'config_date_picker' );
			} else {
				$config_date_picker = '0';
			}
			
			if ($this->config->get ( 'config_time_picker' ) != null && $this->config->get ( 'config_time_picker' ) != "") {
				$config_time_picker = $this->config->get ( 'config_time_picker' );
			} else {
				$config_time_picker = '0';
			}
			
			$config_admin_limit1 = $this->config->get ( 'config_android_front_limit' );
			if ($config_admin_limit1 != null && $config_admin_limit1 != "") {
				$config_admin_limit = $config_admin_limit1;
			} else {
				$config_admin_limit = "25";
			}
			
			$error_config_android_front_limit1 = $this->config->get ( 'config_android_front_limit' );
			if ($error_config_android_front_limit1 != null && $error_config_android_front_limit1 != "") {
				$config_android_front_limit = $error_config_android_front_limit1;
			} else {
				$config_android_front_limit = "25";
			}
			
			$mobile_db_page_limit1 = $this->config->get ( 'mobile_db_page_limit' );
			if ($mobile_db_page_limit1 != null && $mobile_db_page_limit1 != "") {
				$mobile_db_page_limit = $mobile_db_page_limit1;
			} else {
				$mobile_db_page_limit = "20";
			}
			$mobile_list_view_page_limit1 = $this->config->get ( 'mobile_list_view_page_limit' );
			if ($mobile_list_view_page_limit1 != null && $mobile_list_view_page_limit1 != "") {
				$mobile_list_view_page_limit = $mobile_list_view_page_limit1;
			} else {
				$mobile_list_view_page_limit = "25";
			}
			
			if ($this->config->get ( 'config_secure' ) == "1") {
				$configUrl = '1';
				$configUrl2 = $this->config->get ( 'config_ssl' ) . 'index.php?route=services/';
			} else {
				$configUrl = '0';
				$configUrl2 = $this->config->get ( 'config_url' ) . 'index.php?route=services/';
			}
			
			if ($this->config->get ( 'config_all_notification' ) != null && $this->config->get ( 'config_all_notification' ) != "") {
				$config_all_notification = $this->config->get ( 'config_all_notification' );
			} else {
				$config_all_notification = '0';
			}
			
			if ($this->config->get ( 'config_task_sms' ) != null && $this->config->get ( 'config_task_sms' ) != "") {
				$config_task_sms = $this->config->get ( 'config_task_sms' );
			} else {
				$config_task_sms = '0';
			}
			
			if ($this->config->get ( 'config_task_notification' ) != null && $this->config->get ( 'config_task_notification' ) != "") {
				$config_task_notification = $this->config->get ( 'config_task_notification' );
			} else {
				$config_task_notification = '0';
			}
			
			if ($this->config->get ( 'config_task_email' ) != null && $this->config->get ( 'config_task_email' ) != "") {
				$config_task_email = $this->config->get ( 'config_task_email' );
			} else {
				$config_task_email = '0';
			}
			
			if ($this->config->get ( 'config_task_deleted_time' ) != null && $this->config->get ( 'config_task_deleted_time' ) != "") {
				$config_task_deleted_time = $this->config->get ( 'config_task_deleted_time' );
			} else {
				$config_task_deleted_time = '0';
			}
			
			if ($facility_info ['config_taskform_status'] != NULL && $facility_info ['config_taskform_status'] != "") {
				$taskstatus = $facility_info ['config_taskform_status'];
			} else {
				$taskstatus = '0';
			}
			
			if ($facility_info ['config_noteform_status'] != NULL && $facility_info ['config_noteform_status'] != "") {
				$noteformtatus = $facility_info ['config_noteform_status'];
			} else {
				$noteformtatus = '0';
			}
			
			if ($facility_info ['config_rules_status'] != NULL && $facility_info ['config_rules_status'] != "") {
				$ruletatus = $facility_info ['config_rules_status'];
			} else {
				$ruletatus = '0';
			}
			
			if ($facility_info ['config_display_camera'] != NULL && $facility_info ['config_display_camera'] != "") {
				$config_display_camera = $facility_info ['config_display_camera'];
			} else {
				$config_display_camera = '0';
			}
			
			if ($this->config->get ( 'config_transcription' ) != null && $this->config->get ( 'config_transcription' ) != "") {
				$config_transcription = $this->config->get ( 'config_transcription' );
			} else {
				$config_transcription = '0';
			}
			
			if ($facility_info ['config_share_notes'] != NULL && $facility_info ['config_share_notes'] != "") {
				$config_share_notes = $facility_info ['config_share_notes'];
			} else {
				$config_share_notes = '0';
			}
			
			if ($facility_info ['config_sharepin_status'] != NULL && $facility_info ['config_sharepin_status'] != "") {
				$config_sharepin_status = $facility_info ['config_sharepin_status'];
			} else {
				$config_sharepin_status = '0';
			}
			
			if ($facility_info ['config_multiple_activenote'] != NULL && $facility_info ['config_multiple_activenote'] != "") {
				$config_multiple_activenote = $facility_info ['config_multiple_activenote'];
			} else {
				$config_multiple_activenote = '0';
			}
			
			if ($facility_info ['android_audio_file'] != NULL && $facility_info ['android_audio_file'] != "") {
				$android_audio_file = HTTP_SERVER . 'image/ringtone/' . $facility_info ['android_audio_file'];
			} else {
				$android_audio_file = '';
			}
			
			if ($facility_info ['ios_audio_file'] != NULL && $facility_info ['ios_audio_file'] != "") {
				$ios_audio_file = HTTP_SERVER . 'image/ringtone/' . $facility_info ['ios_audio_file'];
			} else {
				$ios_audio_file = '';
			}
			
			$this->load->model ( 'api/encrypt' );
			$edevice_username = $this->model_api_encrypt->encrypt ( $this->config->get ( 'device_username' ) );
			$edevice_token = $this->model_api_encrypt->encrypt ( $this->config->get ( 'device_token' ) );
			
			$this->load->model ( 'setting/timezone' );
			$timezone_info = $this->model_setting_timezone->gettimezone ( $facility_info ['timezone_id'] );
			
			$fsql = "SELECT DISTINCT customer_key,forms_id,form_name,forms_fields FROM " . DB_PREFIX . "forms_design WHERE form_type = 'Screening' and FIND_IN_SET('" . $facility_info ['facilities_id'] . "', facilities) ";
			$queryf = $this->db->query ( $fsql );
			
			$form_info = $queryf->row;
			if ($form_info ['customer_key'] != null && $form_info ['customer_key'] != "") {
				$screening_id = $form_info ['forms_id'];
			} else {
				$screening_id = "";
			}
			
			$fsqli = "SELECT DISTINCT customer_key,forms_id,form_name,forms_fields FROM " . DB_PREFIX . "forms_design WHERE form_type = 'Intake' and FIND_IN_SET('" . $facility_info ['facilities_id'] . "', facilities) ";
			$queryfi = $this->db->query ( $fsqli );
			
			$form_intake_info = $queryfi->row;
			
			if ($form_intake_info ['customer_key'] != null && $form_intake_info ['customer_key'] != "") {
				$intake_id = $form_intake_info ['forms_id'];
				
				$add_client_url = str_replace ( '&amp;', '&', $this->url->link ( 'services/form', '' . '&addclient=1&forms_design_id=' . $intake_id . '&facilities_id=' . $facility_info ['facilities_id'], 'SSL' ) );
			} else {
				$intake_id = "";
				$add_client_url = "";
			}
			
			$this->load->model ( 'api/permision' );
			$current_permission = $this->model_api_permision->getpermision ( $facility_info ['facilities_id'] );
			
			$this->load->model ( 'customer/apiurl' );
			$apiurls = $this->model_customer_apiurl->getapiurls ( $facility_info ['customer_key'] );
			$apiurls1 = array ();
			foreach ( $apiurls as $apiurl ) {
				$apiurls1 [] = array (
						'keyname' => $apiurl ['keyname'],
						'api_full_url' => $apiurl ['api_full_url'] 
				);
			}
			
			$unique_id = $facility_info ['customer_key'];
			$this->load->model ( 'customer/customer' );
			$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
			
			$client_info = unserialize($customer_info['client_info_notes']);
			$setting_data = unserialize($customer_info['setting_data']);
			
			
			$this->load->model ( 'notes/shift' );
			$shifs = $this->model_notes_shift->getshifts ( $facility_info ['customer_key'] );
			$shifts1 = array ();
			foreach ( $shifs as $shift ) {
				$shifts1 [] = array (
						'shift_id' => $shift ['shift_id'],
						'shift_name' => $shift ['shift_name'], 
						'shift_starttime' => $shift ['shift_starttime'], 
						'shift_endtime' => $shift ['shift_endtime'], 
						'shift_color_value' => $shift ['shift_color_value'], 
						'date_added' => $shift ['date_added'], 
				);
			}
			
			if($this->config->get ( 'all_sync_pagination' ) != null && $this->config->get ( 'all_sync_pagination' ) != ""){
				$all_sync_pagination = $this->config->get ( 'all_sync_pagination' );
			}else{
				$all_sync_pagination = "50";
			}
			
			$subfacilities = array();
			if($facility_info ['is_master_facility'] == 1){
				
				$subfacilities [] = array (
					'facilities_id' => $facility_info ['facilities_id'],
					'is_master_facility' => $facility_info ['is_master_facility'],
					'notes_facilities_ids' => $facility_info ['notes_facilities_ids'],
					'client_facilities_ids' => $facility_info ['client_facilities_ids'],
					'enable_facilityinout' => $facility_info ['enable_facilityinout'],
				);
				
				$cids = array();
				if($facility_info ['client_facilities_ids'] != null && $facility_info ['client_facilities_ids'] != ""){
					$sssssddsg = explode(",",$facility_info ['client_facilities_ids']);
					$abdcg = array_unique($sssssddsg);
					foreach($abdcg as $fid){
						$facilityinfo = $this->model_facilities_facilities->getfacilities ( $fid );
						$subfacilities [] = array (
							'facilities_id' => $facilityinfo ['facilities_id'],
							'is_master_facility' => $facilityinfo ['is_master_facility'],
							'notes_facilities_ids' => $facilityinfo ['notes_facilities_ids'],
							'client_facilities_ids' => $facilityinfo ['client_facilities_ids'],
							'enable_facilityinout' => $facilityinfo ['enable_facilityinout'],
						);
					}
				}
				
				if($facility_info ['notes_facilities_ids'] != null && $facility_info ['notes_facilities_ids'] != ""){
					$sssssddsg2 = explode(",",$facility_info ['notes_facilities_ids']);
					$abdcgs = array_unique($sssssddsg2);
					foreach($abdcgs as $fidd){
						$facilityinfo = $this->model_facilities_facilities->getfacilities ( $fidd );
						$subfacilities [] = array (
							'facilities_id' => $facilityinfo ['facilities_id'],
							'is_master_facility' => $facilityinfo ['is_master_facility'],
							'notes_facilities_ids' => $facilityinfo ['notes_facilities_ids'],
							'client_facilities_ids' => $facilityinfo ['client_facilities_ids'],
							'enable_facilityinout' => $facilityinfo ['enable_facilityinout'],
						);
					}
				}
				
				
			}
			
			$ufresult [] = array (
					'facility' => $facility_info ['facility'],
					'timezone_value' => $timezone_info ['timezone_value'],
					'facilities_id' => $facility_info ['facilities_id'],
					'config_display_camera' => $config_display_camera,
					'config_share_notes' => $config_share_notes,
					'config_sharepin_status' => $config_sharepin_status,
					'config_multiple_activenote' => $config_multiple_activenote,
					'config_date_picker' => $config_date_picker,
					'config_time_picker' => $config_time_picker,
					'config_task_status' => $facility_info ['config_task_status'],
					'config_tag_status' => $facility_info ['config_tag_status'],
					'config_admin_limit' => $config_admin_limit,
					'notes_total' => '',
					'notes_total1' => '',
					'http_check' => $configUrl,
					'http_url' => $configUrl2,
					'config_all_notification' => $config_all_notification,
					'config_task_sms' => $config_task_sms,
					'config_task_notification' => $config_task_notification,
					'config_task_email' => $config_task_email,
					'config_task_deleted_time' => $config_task_deleted_time,
					'config_taskform_status' => $taskstatus,
					'config_noteform_status' => $noteformtatus,
					'config_rules_status' => $ruletatus,
					'config_android_front_limit' => $config_android_front_limit,
					'config_transcription' => $config_transcription,
					'mobile_db_page_limit' => $mobile_db_page_limit,
					'mobile_list_view_page_limit' => $mobile_list_view_page_limit,
					
					'face_similar_percent' => $facility_info ['face_similar_percent'],
					'allow_face_without_verified' => $facility_info ['allow_face_without_verified'],
					'allow_quick_save' => $facility_info ['allow_quick_save'],
					'display_attchament' => $facility_info ['display_attchament'],
					'config_face_recognition' => $this->config->get ( 'config_face_recognition' ),
					'active_notification' => $this->config->get ( 'active_notification' ),
					
					'is_android_notification' => $facility_info ['is_android_notification'],
					'android_audio_file' => $android_audio_file,
					'is_android_snooze' => $facility_info ['is_android_snooze'],
					'is_android_dismiss' => $facility_info ['is_android_dismiss'],
					'is_ios_notification' => $facility_info ['is_ios_notification'],
					'ios_audio_file' => $ios_audio_file,
					'is_ios_snooze' => $facility_info ['is_ios_snooze'],
					'is_ios_dismiss' => $facility_info ['is_ios_dismiss'],
					
					'is_enable_beacon' => $facility_info ['is_enable_beacon'],
					'beacon_range' => $facility_info ['beacon_range'],
					'beacon_data_type_range' => $facility_info ['beacon_data_type_range'],
					
					'device_username' => $edevice_username,
					'device_token' => $edevice_token,
					'screening_id' => $screening_id,
					
					'is_discharge_form_enable' => $facility_info ['is_discharge_form_enable'],
					'discharge_form_id' => $facility_info ['discharge_form_id'],
					'is_fingerprint_enable' => $facility_info ['is_fingerprint_enable'],
					
					'is_pin_enable' => $facility_info ['is_pin_enable'],
					'is_sms_enable' => $facility_info ['is_sms_enable'],
					'is_enable_add_notes_by' => $facility_info ['is_enable_add_notes_by'],
					
					'is_client_facial' => $facility_info ['is_client_facial'],
					'is_master_facility' => $facility_info ['is_master_facility'],
					'notes_facilities_ids' => $facility_info ['notes_facilities_ids'],
					'client_facilities_ids' => $facility_info ['client_facilities_ids'],
					'add_client_url' => $add_client_url,
					'intake_id' => $intake_id,
					'current_permission' => $current_permission,
					'apiurls' => $apiurls1,
					'face_similar_percent' => $facility_info ['face_similar_percent'],
					'user_collection' => $customer_info ['user_collection'],
					'client_collection' => $customer_info ['client_collection'],
					'data_stream_name' => $customer_info ['data_stream_name'],
					
					'is_required_activenote' => $facility_info ['is_required_activenote'],
					'config_inventory_allow' => $facility_info ['config_inventory_allow'],
					'no_distribution' => $facility_info ['no_distribution'],
					'facility_type' => $facility_info ['facility_type'],
					'enable_facilityinout' => $facility_info ['enable_facilityinout'],
					'enable_escorted' => $facility_info ['enable_escorted'],
					'required_escorted' => $facility_info ['required_escorted'],
					'approval_required' => $facility_info ['approval_required'],
					'shifts' => $shifts1,
					'client_info' => $client_info,
					'setting_data' => $setting_data,
					'show_case' => $customer_info ['show_case'],
					'show_task' => $customer_info ['show_task'],
					'show_form_tag' => $customer_info ['show_form_tag'],
					'all_sync_pagination' => $all_sync_pagination,
					'subfacilities' => $subfacilities,
					'facility_setting' =>unserialize($facility_info['setting_data']),
					'allhouroutreport' =>  str_replace ( '&amp;', '&', $this->url->link ( 'resident/resident/allhouroutreport', '' , 'SSL' ) ),
			);
		}
		
		return $ufresult;
	}
	public function mime_content_type($filename) {
		try {
			
			$mime_types = array (
					
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
					'ods' => 'application/vnd.oasis.opendocument.spreadsheet' 
			);
			
			$ext = strtolower ( array_pop ( explode ( '.', $filename ) ) );
			if (array_key_exists ( $ext, $mime_types )) {
				return $mime_types [$ext];
			} elseif (function_exists ( 'finfo_open' )) {
				$finfo = finfo_open ( FILEINFO_MIME );
				$mimetype = finfo_file ( $finfo, $filename );
				finfo_close ( $finfo );
				return $mimetype;
			} else {
				return 'application/octet-stream';
			}
		} catch ( Exception $e ) {
			
			$this->load->model ( 'activity/activity' );
			$activity_data2 = array (
					'data' => 'Error in appservices mime_content_type' 
			);
			$this->model_activity_activity->addActivity ( 'app_mime_content_type', $activity_data2 );
			
		}
	}






}
	
	