<?php
class Modelfacilitiesfacilities extends Model {
		
	public function editfacilities($facilities_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "facilities` SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', timezone_id = '" . (int)$data['timezone_id'] . "', description = '" . $this->db->escape($data['description']) . "', address = '" . $this->db->escape($data['address']) . "', location = '" . $this->db->escape($data['location']) . "', country_id = '" . $this->db->escape($data['country_id']) . "', zone_id = '" . $this->db->escape($data['zone_id']) . "' WHERE facilities_id = '" . (int)$facilities_id . "'");
		
		if ($data['password']) {
			$this->db->query("UPDATE `" . DB_PREFIX . "facilities` SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "' WHERE facilities_id = '" . (int)$facilities_id . "'");
		}
	}
	
	public function deletefacilities($facilities_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "facilities` WHERE facilities_id = '" . (int)$facilities_id . "'");
	}

     public function getSubfacilitiess($data) {
     	//print_r($data);
        $this->load->model('facilities/facilities');
		$facility_info = $this->model_facilities_facilities->getfacilities($data['facilities_id']);
		$sql .= 'where 1 = 1 ';
        if($facility_info['is_master_facility'] == '1'){
			$ddss = array();
			if ( $facility_info['client_facilities_ids'] != null && $facility_info['client_facilities_ids'] != "" ) {
				
				$ddss[] = $facility_info['client_facilities_ids'];
				
				$ddss[] = $data['facilities_id'];
				$sssssdd = implode(",",$ddss);
				$sql.= " and facilities_id in (" . $sssssdd . ") ";
			}else{
				if ($data['facilities_id'] != null && $data['facilities_id'] != "") {
				$sql.= " and facilities_id = '".$data['facilities_id']."'";
				
				}
			}
		}else{

			$sql.= " and facilities_id = '".$data['facilities_id']."'";

		}

		if (! empty ( $data ['q'] )) {
			$sql .= " and LOWER(facility) like '%" . strtolower ( $data ['q'] ) . "%'";
		}
		
		
		if ($data['facilities_id'] != null && $data['facilities_id'] != "") {
			$facility_ino = $this->getfacilities($data['facilities_id']);
			
			if ($facility_ino['customer_key'] != null && $facility_ino['customer_key'] != "") {
				$sql .= " and customer_key = '".$facility_ino['customer_key']."'";
			}
		}
		
		
		
		$sql .= " and status = '1'";
		
		$sql = "SELECT facilities_id,timezone_id,facility,password,salt,firstname,lastname,email,status,users,config_task_status,config_tag_status,sms_number,config_taskform_status,config_noteform_status,config_rules_status,latitude,longitude,config_display_camera,config_display_dashboard,config_share_notes,config_sharepin_status,config_multiple_activenote,sharenotes_print,sharenotes_modify,sharenotes_copy,sharenotes_assemble,config_send_email_share_notes,config_rolecall_customlist_id,config_tags_customlist_id,config_bedcheck_customlist_id,form_print_layout,is_discharge_form_enable,discharge_form_id,is_data_sync,data_sync_date_to,data_sync_date_from,is_fingerprint_enable,is_sms_enable,is_pin_enable,is_enable_add_notes_by,allow_quick_save,allow_face_without_verified,face_similar_percent,display_attchament,is_client_facial,is_master_facility,notes_facilities_ids,client_facilities_ids,customer_key,is_required_activenote,config_inventory_allow,enable_facilityinout,enable_escorted,approval_required,facility_type,task_facilities_ids,required_escorted,setting_data FROM `" . DB_PREFIX . "facilities` ".$sql." ORDER BY facility ASC ";


		//echo $sql;
		
		$query = $this->db->query($sql);
	
		return $query->rows;
	}


	
	public function getfacilities($facilities_id) {
		
		//$sql = " CALL `getfacility`(47); ";
		
		$query = $this->db->query("SELECT facilities_id,timezone_id,facility,password,salt,firstname,lastname,email,status,users,config_task_status,config_tag_status,sms_number,config_taskform_status,config_noteform_status,config_rules_status,latitude,longitude,config_display_camera,config_display_dashboard,config_share_notes,config_sharepin_status,config_multiple_activenote,sharenotes_print,sharenotes_modify,sharenotes_copy,sharenotes_assemble,config_send_email_share_notes,config_rolecall_customlist_id,config_tags_customlist_id,config_bedcheck_customlist_id,form_print_layout,is_web_notification,web_audio_file,web_is_snooze,web_is_dismiss,is_android_notification,android_audio_file,is_android_snooze,is_android_dismiss,is_ios_notification,ios_audio_file,is_ios_snooze,is_ios_dismiss,device_ids,device_username,device_token,is_enable_beacon,beacon_range,beacon_data_type_range,config_current_location,is_discharge_form_enable,discharge_form_id,is_data_sync,data_sync_date_to,data_sync_date_from,is_fingerprint_enable,is_sms_enable,is_pin_enable,is_enable_add_notes_by,allow_quick_save,allow_face_without_verified,face_similar_percent,display_attchament,is_client_facial,is_master_facility,notes_facilities_ids,client_facilities_ids,customer_key,is_required_activenote,config_inventory_allow,no_distribution,facility_type,enable_facilityinout,enable_escorted,approval_required,task_facilities_ids,required_escorted,setting_data FROM `" . DB_PREFIX . "facilities` WHERE facilities_id = '" . (int)$facilities_id . "'");
	
		//$query = $this->db->query($sql);
		
		return $query->row;
	}
	
	public function getfacilitiesByfacility($facility) {
		
		$sql = "SELECT facilities_id,timezone_id,facility,password,salt,firstname,lastname,email,status,users,config_task_status,config_tag_status,sms_number,config_taskform_status,config_noteform_status,config_rules_status,latitude,longitude,config_display_camera,config_display_dashboard,config_share_notes,config_sharepin_status,config_multiple_activenote,sharenotes_print,sharenotes_modify,sharenotes_copy,sharenotes_assemble,config_send_email_share_notes,config_rolecall_customlist_id,config_tags_customlist_id,config_bedcheck_customlist_id,form_print_layout,face_similar_percent,allow_face_without_verified,allow_quick_save,display_attchament,is_web_notification,web_audio_file,web_is_snooze,web_is_dismiss,is_android_notification,android_audio_file,is_android_snooze,is_android_dismiss,is_ios_notification,ios_audio_file,is_ios_snooze,is_ios_dismiss,device_ids,device_username,device_token,is_enable_beacon,beacon_range,beacon_data_type_range,config_current_location,is_discharge_form_enable,discharge_form_id,is_data_sync,data_sync_date_to,data_sync_date_from,is_fingerprint_enable,is_sms_enable,is_pin_enable,is_enable_add_notes_by,is_client_facial,is_master_facility,notes_facilities_ids,client_facilities_ids,customer_key,face_similar_percent,is_required_activenote,config_inventory_allow,no_distribution,facility_type,enable_facilityinout,enable_escorted,approval_required,task_facilities_ids,required_escorted,setting_data FROM `" . DB_PREFIX . "facilities` WHERE facility = '" . $this->db->escape($facility) . "'";
		$query = $this->db->query($sql);
	
		return $query->row;
	}
		
	public function getfacilitiesByCode($code) {
		$query = $this->db->query("SELECT facilities_id,timezone_id,facility,password,salt,firstname,lastname,email,status,users,config_task_status,config_tag_status,sms_number,config_taskform_status,config_noteform_status,config_rules_status,latitude,longitude,config_display_camera,config_display_dashboard,config_share_notes,config_sharepin_status,config_multiple_activenote,sharenotes_print,sharenotes_modify,sharenotes_copy,sharenotes_assemble,config_send_email_share_notes,config_rolecall_customlist_id,config_tags_customlist_id,config_bedcheck_customlist_id,form_print_layout,allow_quick_save,allow_face_without_verified,face_similar_percent,display_attchament,customer_key,is_required_activenote,config_inventory_allow,no_distribution,facility_type,task_facilities_ids,required_escorted,setting_data FROM `" . DB_PREFIX . "facilities` WHERE code = '" . $this->db->escape($code) . "' AND code != ''");
	
		return $query->row;
	}
	
	public function getfacilitiess2($data = array()) {
		
			
		if ($data['facilities'] != null && $data['facilities'] != "") {
			$sql .= 'where 1 = 1 ';
			$sql .= " and facilities_id in (". $data['facilities'].") ";  
			$sql .= " and status = '1'";
			
			$sql = "SELECT facilities_id,timezone_id,facility,password,salt,firstname,lastname,email,status,users,config_task_status,config_tag_status,sms_number,config_taskform_status,config_noteform_status,config_rules_status,latitude,longitude,config_display_camera,config_display_dashboard,config_share_notes,config_sharepin_status,config_multiple_activenote,sharenotes_print,sharenotes_modify,sharenotes_copy,sharenotes_assemble,config_send_email_share_notes,config_rolecall_customlist_id,config_tags_customlist_id,config_bedcheck_customlist_id,form_print_layout,is_discharge_form_enable,discharge_form_id,is_data_sync,data_sync_date_to,data_sync_date_from,is_fingerprint_enable,is_sms_enable,is_pin_enable,is_enable_add_notes_by,allow_quick_save,allow_face_without_verified,face_similar_percent,display_attchament,is_client_facial,is_master_facility,notes_facilities_ids,client_facilities_ids,customer_key,is_required_activenote,config_inventory_allow,no_distribution,facility_type,enable_facilityinout,enable_escorted,approval_required,task_facilities_ids,required_escorted,setting_data FROM `" . DB_PREFIX . "facilities` ".$sql." ORDER BY facility ASC ";
			
			$query = $this->db->query($sql);
		
			return $query->rows;
		}
	}
		
	public function getfacilitiess($data = array()) {
		
		$sql .= 'where 1 = 1 ';
		if ($data['facilities'] != null && $data['facilities'] != "") {
			$sql .= " and facilities_id in (". $data['facilities'].") ";  
		}

		if (! empty ( $data ['q'] )) {
			$sql .= " and LOWER(facility) like '%" . strtolower ( $data ['q'] ) . "%'";
		}
		
		if ($data['facilities_id'] != null && $data['facilities_id'] != "") {
			$facility_ino = $this->getfacilities($data['facilities_id']);
			
			if ($facility_ino['customer_key'] != null && $facility_ino['customer_key'] != "") {
				$sql .= " and customer_key = '".$facility_ino['customer_key']."'";
			}
			
			$ddss = array();
			if ($facility_ino['facility_type'] == "1") {
				if ( $facility_ino['notes_facilities_ids'] != null && $facility_ino['notes_facilities_ids'] != "" ) {
					$ddss[] = $facility_ino['notes_facilities_ids'];
				}
				$ddss[] = $data['facilities_id'];
				$sssssdd = implode(",",$ddss);
				
				$sql.= " and facilities_id in (" . $sssssdd . ") ";
			}
		}
		
		if ($data['customer_key'] != null && $data['customer_key'] != "") {
			$sql .= " and customer_key = '".$data['customer_key']."'";
		}
		
		$sql .= " and status = '1'";
		
		$sql = "SELECT facilities_id,timezone_id,facility,password,salt,firstname,lastname,email,status,users,config_task_status,config_tag_status,sms_number,config_taskform_status,config_noteform_status,config_rules_status,latitude,longitude,config_display_camera,config_display_dashboard,config_share_notes,config_sharepin_status,config_multiple_activenote,sharenotes_print,sharenotes_modify,sharenotes_copy,sharenotes_assemble,config_send_email_share_notes,config_rolecall_customlist_id,config_tags_customlist_id,config_bedcheck_customlist_id,form_print_layout,is_discharge_form_enable,discharge_form_id,is_data_sync,data_sync_date_to,data_sync_date_from,is_fingerprint_enable,is_sms_enable,is_pin_enable,is_enable_add_notes_by,allow_quick_save,allow_face_without_verified,face_similar_percent,display_attchament,is_client_facial,is_master_facility,notes_facilities_ids,client_facilities_ids,customer_key,is_required_activenote,config_inventory_allow,no_distribution,facility_type,enable_facilityinout,enable_escorted,approval_required,task_facilities_ids,required_escorted,setting_data FROM `" . DB_PREFIX . "facilities` ".$sql." ORDER BY facility ASC ";
		
		$query = $this->db->query($sql);
	
		return $query->rows;
	}

	public function getTotalfacilitiess($data = array()) {
		
		$sql .= 'where 1 = 1 ';
		if ($data['facility'] != null && $data['facility'] != "") {
			$sql .= " and facility like '%".$data['facility']."%'";
		}
		if ($data['location'] != null && $data['location'] != "") {
			$sql .= " and location like '%".$data['location']."%'";
		}
		if ($data['address'] != null && $data['address'] != "") {
			$sql .= " and address like '%".$data['address']."%'";
		}
		if ($data['description'] != null && $data['description'] != "") {
			$sql .= " and description like '%".$data['description']."%'";
		}
		
		if ($data['status'] != null && $data['status'] != "") {
			$sql .= " and status = '".$data['status']."'";
		}
		
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "facilities` ".$sql."");
		
		return $query->row['total'];
	}

	public function getTotalfacilitiessByGroupId($facilities_group_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "facilities` WHERE facilities_group_id = '" . (int)$facilities_group_id . "'");
		
		return $query->row['total'];
	}
	
	public function getTotalfacilitiessByEmail($email) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "facilities` WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
		
		return $query->row['total'];
	}	
	
	public function editPassword($email, $password) {
		$this->db->query("UPDATE `" . DB_PREFIX . "facilities` SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "', code = '' WHERE email = '" . (int)$email . "'");
	}
	
	public function updateFacilityLogin($data) {
		$facility_data = $this->getfacilitiesOnline($data);
		$this->deleteFacilityLoginData($data['facilities_id']);
		
		$activationkey = array($data['activationkey']);
		
		if($facility_data['facility_login'] != null && $facility_data['facility_login'] != ""){
			$facility_loginA = explode(",",$facility_data['facility_login']);
			$facility_login1 = array_unique(array_merge($facility_loginA,$activationkey));
		}else{
			$facility_login1 = $activationkey;
		}
		
		$facility_login = implode(",",$facility_login1);
		
		
		$username1 = array($data['username']);
		
		if($facility_data['username'] != null && $facility_data['username'] != ""){
			$usernameA = explode(",",$facility_data['username']);
			$usernameA1 = array_unique(array_merge($usernameA,$username1));
		}else{
			$usernameA1 = $username1;
		}
		
		$username = implode(",",$usernameA1);
		
		if($facility_data['facility_count'] != null && $facility_data['facility_count'] != ""){
			$facility_count = $facility_data['facility_count'] + 1;
		}else{
			$facility_count = 1;
		}
		
		$sql = "UPDATE `" . DB_PREFIX . "facility_online` SET facility_login = '".$facility_login."',facility_count = '".$facility_count."',username = '".$username."' WHERE facilities_id = '" . (int)$data['facilities_id'] . "' and ip = '".$data['ip']."' ";
		
		$this->db->query($sql);
		
		
	}
	
	public function updateFacilityLogout($data) {
		
		$facility_data = $this->getfacilitiesOnline($data);
		
		//var_dump($facility_data['facility_login']);
		
		$this->deleteFacilityLoginData($data['facilities_id']);
		
		$activationkey = array($data['activationkey']);
		
		if($facility_data['facility_login'] != null && $facility_data['facility_login'] != ""){
			$facility_loginA = explode(",",$facility_data['facility_login']);
			$facility_login1 = array_unique(array_merge($facility_loginA,$activationkey));
		}else{
			$facility_login1 = $activationkey;
		}
		
		$facility_login2 = $this->array_delete($activationkey, $facility_login1);
		
		$facility_login = implode(",",$facility_login2);
		
		//var_dump($facility_login);
		
		$username1 = array($data['username']);
		
		if($facility_data['username'] != null && $facility_data['username'] != ""){
			$usernameA = explode(",",$facility_data['username']);
			$usernameA1 = array_unique(array_merge($usernameA,$username1));
		}else{
			$usernameA1 = $username1;
		}
		
		$usernameA12 = $this->array_delete($username1, $usernameA1);
		$username = implode(",",$usernameA12);
		
		
		if($facility_data['facility_count'] != null && $facility_data['facility_count'] != ""){
			$facility_count = $facility_data['facility_count'] - 1;
		}else{
			$facility_count = 0;
		}
		// $sql = "UPDATE `" . DB_PREFIX . "facility_online` SET facility_login = '".$facility_login."',facility_count = '".$facility_count."', username = '".$username."' WHERE facilities_id = '" . (int)$data['facilities_id'] . "' and ip = '".$data['ip']."' ";
		$sql = "UPDATE `" . DB_PREFIX . "facility_online` SET facility_count = '0' WHERE facilities_id = '" . (int)$data['facilities_id'] . "' and ip = '".$data['ip']."' and  username = '".$data['username']."' ";
		
		$this->db->query($sql);
	}

	
	public function getfacilitiesOnline($data) {
		$query = $this->db->query("SELECT facility_online_id,ip,facilities_id,url,referer,date_added,facility_login,facility_count,username FROM `" . DB_PREFIX . "facility_online` WHERE facilities_id = '" . (int)$data['facilities_id'] . "' and ip = '".$data['ip']."' ");
	
		
		return $query->row;
	}
	
	public function deleteFacilityLoginData($facilities_id){
		$this->db->query("DELETE FROM `" . DB_PREFIX . "facility_online` WHERE facilities_id = '" . (int)$facilities_id . "' and ip = '' ");
	}
	
	public function resetFacilityLogin($data){
		$query = $this->db->query("SELECT facility_online_id,ip,facilities_id,url,referer,date_added,facility_login,facility_count,username FROM `" . DB_PREFIX . "facility_online` WHERE FIND_IN_SET('". $data['activationkey']."', facility_login) ");
		return $query->rows;
	}
	
	
	public function array_delete($del_val, $array) {
		if(is_array($del_val)) {
			 foreach ($del_val as $del_key => $del_value) {
				foreach ($array as $key => $value){
					if ($value == $del_value) {
						unset($array[$key]);
					}
				}
			}
		} else {
			foreach ($array as $key => $value){
				if ($value == $del_val) {
					unset($array[$key]);
				}
			}
		}
		return array_values($array);
	}
	
	
	public function addLoginAttempt($email) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "facility_login WHERE email = '" . $this->db->escape($email) . "' ");
		
		if (!$query->num_rows) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "facility_login SET email = '" . $this->db->escape($email) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', total = 1, date_added = '" . $this->db->escape(date('Y-m-d H:i:s')) . "', date_modified = '" . $this->db->escape(date('Y-m-d H:i:s')) . "'");
		} else {
			$this->db->query("UPDATE " . DB_PREFIX . "facility_login SET total = (total + 1), date_modified = '" . $this->db->escape(date('Y-m-d H:i:s')) . "' WHERE facility_login_id = '" . (int)$query->row['facility_login_id'] . "'");
		}			
	}	
	
	public function getLoginAttempts($email) {
		$query = $this->db->query("SELECT email,ip,total,date_added,date_modified,username,facility_login_id FROM `" . DB_PREFIX . "facility_login` WHERE email = '" . $this->db->escape($email) . "'");

		return $query->row;
	}
	
	public function deleteLoginAttempts($email) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "facility_login` WHERE email = '" . $this->db->escape($email) . "'");
	}
	
	public function getfacilityByID($facilities_id) {
		$sql = "SELECT facilities_id,timezone_id,facility,password,salt,firstname,lastname,email,status,users,config_task_status,config_tag_status,sms_number,config_taskform_status,config_noteform_status,config_rules_status,latitude,longitude,config_display_camera,config_display_dashboard,config_share_notes,config_sharepin_status,config_multiple_activenote,sharenotes_print,sharenotes_modify,sharenotes_copy,sharenotes_assemble,config_send_email_share_notes,config_rolecall_customlist_id,config_tags_customlist_id,config_bedcheck_customlist_id,form_print_layout,is_discharge_form_enable,discharge_form_id,is_data_sync,data_sync_date_to,data_sync_date_from,is_fingerprint_enable,is_sms_enable,is_pin_enable,is_enable_add_notes_by,allow_quick_save,allow_face_without_verified,face_similar_percent,display_attchament,is_client_facial,is_master_facility,notes_facilities_ids,client_facilities_ids,customer_key,is_required_activenote,config_inventory_allow,no_distribution,facility_type,enable_facilityinout,enable_escorted,approval_required,task_facilities_ids,required_escorted,setting_data FROM `" . DB_PREFIX . "facilities` where facilities_id in (".$facilities_id.") and status = '1' ORDER BY facility ASC ";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getips($ip) {
		$sql = "SELECT facility_ip_id,ip,date_added,facility_block,facilities_id,username FROM `" . DB_PREFIX . "facility_ip` where ip = '".$ip."' and facility_block = '1'  ";
		$query = $this->db->query($sql);
		return $query->row;
	}
	public function getip($ip) {
		$sql = "SELECT facility_ip_id,ip,date_added,facility_block,facilities_id,username FROM `" . DB_PREFIX . "facility_ip` where ip = '".$ip."' ";
		$query = $this->db->query($sql);
		return $query->row;
	}
	
	public function addLocations($lat,$lon) {
		
		$address = $lat.','.$lon;
		$url = "http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$response = curl_exec($ch);
		curl_close($ch);
		$response_a = json_decode($response);
		
		$formatted_address = $response_a->results[0]->formatted_address;
		
		$sql = "INSERT INTO " . DB_PREFIX . "user_address SET lat = '" . $lat . "', lon = '" . $lon . "', address ='".$formatted_address."' ";
		$this->db->query($sql);
		
	} 
	
	public function getLocations($data = array()) {
		/*$sql ="SELECT * FROM `" . DB_PREFIX . "user_address` ";
		$sql .= 'where 1 = 1 ';
		if ($data['lat'] != null && $data['lat'] != "") {
			$sql .= " and lat = '".$data['lat']."'";
		}
		if ($data['log'] != null && $data['log'] != "") {
			$sql .= " and lon = '".$data['log']."'";
		}
		if ($data['login_allow'] != null && $data['login_allow'] != "") {
			$sql .= " and login_allow = '1'";
		}*/
		
		$sql = "SELECT  `user_address_id` , ( 3959 * ACOS( COS( RADIANS( ".$data['lat']." ) ) * COS( RADIANS( lat ) ) * COS( RADIANS( lon ) - RADIANS(  ".$data['log']." ) ) + SIN( RADIANS( ".$data['lat']." ) ) * SIN( RADIANS( lat ) ) ) ) AS distance FROM dg_user_address WHERE login_allow = '1' and facilities_id = '".$data['facilities_id']."' HAVING distance <3 ";
		
		$query = $this->db->query($sql);
	
		return $query->row; 
	}
}
?>