<?php
class Modelsettingtags extends Model {
	private $additional_enc = 'noteactive';
	public function addTags($data, $facilities_id) {
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		$this->load->model ( 'setting/timezone' );
		
		if ($data ['room_id'] > 0) {
			$this->load->model ( 'setting/locations' );
			$location_info = $this->model_setting_locations->getlocation ( $data ['room_id'] );
			$lname = $location_info ['location_name'];
		}
		
		if ($data ['gender'] != null && $data ['gender'] != "") {
			$this->load->model ( 'form/form' );
			$customlistvalues_info = $this->model_form_form->getcustomlistvalues ( $data ['gender'] );
			
			if ($customlistvalues_info ['gender'] != null && $customlistvalues_info ['gender'] != '0') {
				$gender = $customlistvalues_info ['gender'];
				$gname = $customlistvalues_info ['customlistvalues_name'];
			} else {
				$gender = '1';
				$gname = "Male";
			}
		} else {
			$gender = '1';
			$gname = "Male";
		}
		
		$alldata = "";
		foreach ( $data as $a ) {
			$alldata .= $a . ' ';
		}
		$alldata .= $facilities_info ['facility'] . ' ';
		$alldata .= $gname . ' ';
		$alldata .= $lname . ' ';
		
		$dob111 = $data ['month_1'] . '-' . $data ['day_1'] . '-' . $data ['year_1'];
		
		$date = str_replace ( '-', '/', $dob111 );
		
		$res = explode ( "/", $date );
		$createdate1 = $res [2] . "-" . $res [0] . "-" . $res [1];
		
		$dob = date ( 'Y-m-d', strtotime ( $createdate1 ) );
		
		$age = (date ( 'Y' ) - date ( 'Y', strtotime ( $dob ) ));
		
		if ($data ['lat'] != null && $data ['lat'] != "") {
			$latitude2 = $data ['lat'];
			$longitude2 = $data ['lng'];
		} else {
			
			$address = $data ['location_address'];
			// Get JSON results from this request
			$geo = file_get_contents ( 'http://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode ( $address ) . '&sensor=false' );
			
			// Convert the JSON to an array
			$geo = json_decode ( $geo, true );
			
			if ($geo ['status'] == 'OK') {
				// Get Lat & Long
				$latitude = $geo ['results'] [0] ['geometry'] ['location'] ['lat'];
				$longitude = $geo ['results'] [0] ['geometry'] ['location'] ['lng'];
			}
			
			$latitude2 = $latitude;
			$longitude2 = $longitude;
		}
		
		$date2 = str_replace ( '-', '/', $data ['date_of_screening'] );
		$res2 = explode ( "/", $date2 );
		$date_of_screening = $res2 [2] . "-" . $res2 [0] . "-" . $res2 [1];
		
		if ($data ['facilities_id'] != null && $data ['facilities_id'] != "") {
			$facilities_id = $data ['facilities_id'];
		} else {
			$facilities_id = $facilities_id;
		}
		
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		$this->load->model ( 'setting/timezone' );
		
		$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
		$facilitytimezone = $timezone_info ['timezone_value'];
		$timeZone = date_default_timezone_set ( $timezone_name );
		
		if ($data ['date_added'] != null && $data ['date_added'] != "") {
			$date23 = str_replace ( '-', '/', $data ['date_added'] );
			
			$res23 = explode ( "/", $date23 );
			$createdate144 = $res23 [2] . "-" . $res23 [0] . "-" . $res23 [1];
			$time = date ( 'H:i:s' );
			$date_added = $createdate144 . ' ' . $time;
		} else {
			$date_added = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		}
		
		$modify_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		
		$room = $data ['room_id'];
		
		$date = str_replace ( '-', '/', $data ['reminder_date'] );
		$res = explode ( "/", $date );
		$dateRange = $res [2] . "-" . $res [0] . "-" . $res [1];
		$time = date ( 'H:i:s' );
		$reminder_date = $dateRange . ' ' . $time;
		$reminder_time = date ( 'H:i:s', strtotime ( $data ['reminder_time'] ) );
		
		$hidden_fields = $this->getHiddenFields ();
		$this->load->model ( 'api/encrypt' );
		$emp_first_name = $this->db->escape ( $data ['emp_first_name'] );
		
		$emp_last_name = $this->db->escape ( $data ['emp_last_name'] );
		
		if (in_array ( 'ssn', $hidden_fields )) {
			$ssn = $this->model_api_encrypt->encrypt ( $this->db->escape ( $data ['ssn'] ) ) . '::' . $this->model_api_encrypt->encrypt ( $this->additional_enc );
		} else {
			$ssn = $this->db->escape ( $data ['ssn'] );
		}
		if (in_array ( 'emp_extid', $hidden_fields )) {
			$emp_extid = $this->model_api_encrypt->encrypt ( $this->db->escape ( $data ['emp_extid'] ) ) . '::' . $this->model_api_encrypt->encrypt ( $this->additional_enc );
		} else {
			$emp_extid = $this->db->escape ( $data ['emp_extid'] );
		}
		
		if (in_array ( 'emergency_contact', $hidden_fields )) {
			$emergency_contact = $this->model_api_encrypt->encrypt ( $this->db->escape ( $data ['emergency_contact'] ) ) . '::' . $this->model_api_encrypt->encrypt ( $this->additional_enc );
		} else {
			$emergency_contact = $this->db->escape ( $data ['emergency_contact'] );
		}
		if (in_array ( 'location_address', $hidden_fields )) {
			$address = $this->model_api_encrypt->encrypt ( $data ['location_address'] ) . '::' . $this->model_api_encrypt->encrypt ( $this->additional_enc );
		} else {
			$address = $this->db->escape ( $data ['location_address'] );
		}
		
		if (in_array ( 'person_screening', $hidden_fields )) {
			$person_screening = $this->model_api_encrypt->encrypt ( $this->db->escape ( $data ['person_screening'] ) ) . '::' . $this->model_api_encrypt->encrypt ( $this->additional_enc );
		} else {
			$person_screening = $this->db->escape ( $data ['person_screening'] );
		}
		if (in_array ( 'tagstatus', $hidden_fields )) {
			$tagstatus = $this->model_api_encrypt->encrypt ( $this->db->escape ( $data ['tagstatus'] ) ) . '::' . $this->model_api_encrypt->encrypt ( $this->additional_enc );
		} else {
			$tagstatus = $this->db->escape ( $data ['tagstatus'] );
		}
		if (in_array ( 'med_mental_health', $hidden_fields )) {
			$med_mental_health = $this->model_api_encrypt->encrypt ( $this->db->escape ( $data ['med_mental_health'] ) ) . '::' . $this->model_api_encrypt->encrypt ( $this->additional_enc );
		} else {
			$med_mental_health = $this->db->escape ( $data ['med_mental_health'] );
		}
		if (in_array ( 'constant_sight', $hidden_fields )) {
			$constant_sight = $this->model_api_encrypt->encrypt ( $this->db->escape ( $data ['constant_sight'] ) ) . '::' . $this->model_api_encrypt->encrypt ( $this->additional_enc );
		} else {
			$constant_sight = $this->db->escape ( $data ['constant_sight'] );
		}
		if (in_array ( 'alert_info', $hidden_fields )) {
			$alert_info = $this->model_api_encrypt->encrypt ( $this->db->escape ( $data ['alert_info'] ) ) . '::' . $this->model_api_encrypt->encrypt ( $this->additional_enc );
		} else {
			$alert_info = $this->db->escape ( $data ['alert_info'] );
		}
		if (in_array ( 'prescription', $hidden_fields )) {
			$prescription = $this->model_api_encrypt->encrypt ( $this->db->escape ( $data ['prescription'] ) ) . '::' . $this->model_api_encrypt->encrypt ( $this->additional_enc );
		} else {
			$prescription = $this->db->escape ( $data ['prescription'] );
		}
		if (in_array ( 'restriction_notes', $hidden_fields )) {
			$restriction_notes = $this->model_api_encrypt->encrypt ( $this->db->escape ( $data ['restriction_notes'] ) ) . '::' . $this->model_api_encrypt->encrypt ( $this->additional_enc );
		} else {
			$restriction_notes = $this->db->escape ( $data ['restriction_notes'] );
		}
		
		/*
		 * $sql1 = "INSERT INTO `" . DB_PREFIX . "tags` SET emp_tag_id = '" . $this->db->escape($data['emp_tag_id']) . "', emp_first_name = '" . $this->db->escape($data['emp_first_name']) . "', emp_middle_name = '" . $this->db->escape($data['emp_middle_name']) . "', emp_last_name = '" . $this->db->escape($data['emp_last_name']) . "', privacy = '" . $this->db->escape($data['privacy']) . "', sort_order = '" . (int)$data['sort_order'] . "', status = '0', doctor_name = '" . $this->db->escape($data['doctor_name']) . "',emergency_contact = '" . $emergency_contact . "', dob = '" . $dob . "', medication = '" . $data['medication'] . "', locations_id = '" . $data['locations_id'] . "', facilities_id = '" . $facilities_id . "', upload_file = '" . $this->db->escape($data['upload_file']) . "', tags_pin = '" . $this->db->escape($data['tags_pin']) . "', gender = '" . $this->db->escape($gender) . "', role_call = '1', age = '" . $age . "', date_added = '".$date_added."' , emp_extid = '" . $emp_extid . "',address_street2 = '" . $this->db->escape($data['address_street2']) . "',person_screening = '" . $person_screening . "',date_of_screening = '" . $date_of_screening . "', ssn = '" . $ssn . "', state = '" . $this->db->escape($data['state']) . "',city = '" . $this->db->escape($data['city']) . "', zipcode = '" . $this->db->escape($data['zipcode']) . "', location_address = '" . $address . "', latitude = '" . $latitude2 . "', longitude = '" . $longitude2 . "',room = '" . $this->db->escape($room) . "', restriction_notes = '" . $restriction_notes . "',prescription = '" . $prescription . "',alert_info = '" . $alert_info . "', constant_sight = '" . $constant_sight . "' ,med_mental_health = '" . $med_mental_health . "',tagstatus = '" . $tagstatus . "',customlistvalues_id = '" . $data['gender'] . "',tags_status_in = '" . $this->db->escape($data['tags_status_in']) . "',referred_facility = '" . $this->db->escape($data['referred_facility']) . "', discharge_date = '', reminder_date = '".$reminder_date."', reminder_time = '".$reminder_time."',upload_file_thumb='', discharge = '1',phone_device_id = '" . $this->db->escape($data['phone_device_id']) . "',is_android = '" . $this->db->escape($data['is_android']) . "' ,modify_date = '" . $this->db->escape($date_added) . "' ";
		 *
		 * $this->db->query($sql1);
		 * $tags_id = $this->db->getLastId();
		 */
		
		if ($facilities_id) {
			$this->load->model ( 'facilities/facilities' );
			$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
			$unique_id = $facility ['customer_key'];
		}
		
		if ($data ['tags_status_in'] != null && $data ['tags_status_in'] != "") {
			$tags_status_in = $data ['tags_status_in'];
		} else {
			$tags_status_in = 'Admitted';
		}
		
		
		if($data ['ssn'] != null && $data ['ssn'] != ""){
			$ssn = $data ['ssn'];
		}else{
			$ssn = rand();
		}
		
		$sql1 = "CALL insertTags('" . $this->db->escape ( $data ['emp_tag_id'] ) . "','" . $this->db->escape ( $data ['emp_first_name'] ) . "','" . $this->db->escape ( $data ['emp_middle_name'] ) . "','" . $this->db->escape ( $data ['emp_last_name'] ) . "','" . $this->db->escape ( $data ['privacy'] ) . "','" . ( int ) $data ['sort_order'] . "','" . $this->db->escape ( $data ['doctor_name'] ) . "','" . $this->db->escape ( $data ['emergency_contact'] ) . "','" . $dob . "','" . $this->db->escape ( $data ['medication'] ) . "','" . $this->db->escape ( $data ['locations_id'] ) . "','" . $facilities_id . "','" . $this->db->escape ( $data ['upload_file'] ) . "','" . $this->db->escape ( $data ['tags_pin'] ) . "','" . $this->db->escape ( $gender ) . "','" . $age . "','" . $date_added . "','" . $this->db->escape ( $data ['emp_extid'] ) . "','" . $this->db->escape ( $data ['address_street2'] ) . "','" . $this->db->escape ( $data ['person_screening'] ) . "','" . $this->db->escape ( $date_of_screening ) . "','" . $this->db->escape ( $ssn ) . "','" . $this->db->escape ( $data ['state'] ) . "','" . $this->db->escape ( $data ['city'] ) . "','" . $this->db->escape ( $data ['zipcode'] ) . "','" . $this->db->escape ( $address ) . "','" . $this->db->escape ( $latitude2 ) . "','" . $this->db->escape ( $longitude2 ) . "','" . $this->db->escape ( $room ) . "','" . $this->db->escape ( $data ['restriction_notes'] ) . "','" . $this->db->escape ( $data ['prescription'] ) . "','" . $this->db->escape ( $data ['alert_info'] ) . "','" . $this->db->escape ( $data ['constant_sight'] ) . "','" . $this->db->escape ( $data ['med_mental_health'] ) . "','" . $this->db->escape ( $data ['tagstatus'] ) . "','" . $this->db->escape ( $data ['gender'] ) . "','" . $this->db->escape ( $tags_status_in ) . "'
		,'" . $this->db->escape ( $data ['referred_facility'] ) . "','" . $reminder_date . "','" . $reminder_time . "','" . $this->db->escape ( $data ['phone_device_id'] ) . "','" . $this->db->escape ( $data ['is_android'] ) . "','" . $this->db->escape ( $modify_date ) . "','" . $this->db->escape ( $unique_id ) . "' )";
		
		$lastId = $this->db->query ( $sql1 );
		
		$tags_id = $lastId->row ['tags_id'];
		
		$this->load->model ( 'customer/customer' );
		$customer_info = $this->model_customer_customer->getcustomerid ( $unique_id );
		
		$client_info = unserialize ( $customer_info ['setting_data'] );
		
		if ($client_info ['defaultrole_call'] != null && $client_info ['defaultrole_call'] != "") {
			$defaultrole_call = $client_info ['defaultrole_call'];
		} else {
			$defaultrole_call = 0;
		}
		
		$this->db->query ( "UPDATE `" . DB_PREFIX . "tags` SET customer_key = '" . $customer_info ['activecustomer_id'] . "', modify_date = '" . $modify_date . "',tag_data = '" . $alldata . "',role_call = '" . $defaultrole_call . "',ccn = '" . $this->db->escape ( $data ['ccn'] ) . "',bed_number = '" . ( int )$data ['bed_number'] . "' WHERE tags_id = '" . ( int ) $tags_id . "'" );
		
		$emp_first_name12 = str_replace ( ' ', '', str_replace ( "'", '', $data ['emp_first_name'] ) );
		
		if (! preg_match ( "/^[a-zA-Z ]*$/", $emp_first_name12 )) {
			$emp_first_name1 = substr ( str_shuffle ( str_repeat ( "0123456789abcdefghijklmnopqrstuvwxyz", 5 ) ), 0, 5 );
		} else {
			$emp_first_name1 = str_replace ( ' ', '', str_replace ( "'", '', $data ['emp_first_name'] ) );
		}
		
		$emp_tag_id = $emp_first_name1 . $tags_id;
		
		
		if($data ['ssn'] == null && $data ['ssn'] == ""){
			$this->db->query ( "UPDATE `" . DB_PREFIX . "tags` SET ssn = '" . $this->db->escape ( $emp_tag_id ) . "' WHERE tags_id = '" . ( int ) $tags_id . "'" );
		}
		
		$this->db->query ( "UPDATE `" . DB_PREFIX . "tags` SET emp_tag_id = '" . $this->db->escape ( $emp_tag_id ) . "' WHERE tags_id = '" . ( int ) $tags_id . "'" );
		
		if($data ['emp_extid'] == null && $data ['emp_extid'] ==""){
			$this->db->query ( "UPDATE `" . DB_PREFIX . "tags` SET emp_extid = '" . $this->db->escape ( $emp_tag_id ) . "' WHERE tags_id = '" . ( int ) $tags_id . "'" );
		}
		
		$sqlta = "INSERT INTO `" . DB_PREFIX . "tags_all_facility` SET emp_tag_id = '" . $this->db->escape ( $emp_tag_id ) . "', emp_first_name = '" . $this->db->escape ( $data ['emp_first_name'] ) . "', emp_last_name = '" . $this->db->escape ( $data ['emp_last_name'] ) . "', facilities_id = '" . $facilities_id . "',tags_id = '" . ( int ) $tags_id . "', location_address = '" . $this->db->escape ( $address ) . "', latitude = '" . $latitude2 . "', longitude = '" . $longitude2 . "', unique_id = '" . $this->db->escape ( $unique_id ) . "' ";
		$this->db->query ( $sqlta );
		
		if ($data ['forms_id'] != null && $data ['forms_id'] != "") {
			// $this->db->query("UPDATE `" . DB_PREFIX . "forms` SET tags_id = '" . $this->db->escape($tags_id) . "', date_updated = '" . $this->db->escape($date_added) . "' WHERE forms_id = '" . (int)$data['forms_id'] . "'");
			
			$this->db->query ( "UPDATE `" . DB_PREFIX . "tags` SET forms_id = '" . $this->db->escape ( $data ['forms_id'] ) . "' WHERE tags_id = '" . ( int ) $tags_id . "'" );
		}
		
		if ($data ['imageName_url'] != null && $data ['imageName_url'] != "") {
			
			$notes_file = $data ['imageName'];
			$outputFolder = $data ['imageName_path'];
			/*
			 * if($this->config->get('enable_storage') == '1'){
			 *
			 * require_once(DIR_SYSTEM . 'library/awsstorage/s3_config.php');
			 * }
			 *
			 * if($this->config->get('enable_storage') == '2'){
			 *
			 *
			 * require_once(DIR_SYSTEM . 'library/azure_storage/config.php');
			 * //uploadBlobSample($blobClient, $outputFolder, $notes_file);
			 * $s3file = AZURE_URL. $notes_file;
			 * }
			 *
			 * if($this->config->get('enable_storage') == '3'){
			 *
			 * //$outputFolder = DIR_IMAGE.'storage/' . $notes_file;
			 * //move_uploaded_file($this->request->files["file"]["tmp_name"], $outputFolder);
			 * //$s3file = HTTPS_SERVER.'image/storage/' . $notes_file;
			 * $s3file = $data['imageName_url'];
			 * }
			 */
			
			$s3file = $data ['imageName_url'];
			// $this->db->query("UPDATE `" . DB_PREFIX . "tags` SET upload_file = '" . $this->db->escape($s3file) . "', upload_file_thumb = '' WHERE tags_id = '" . (int)$tags_id . "'");
			
			$this->load->model ( 'facilities/facilities' );
			$facilities_info = $this->model_facilities_facilities->getfacilities ( $facilities_id );
			
			if ($facilities_info ['is_client_facial'] == '1') {
				
				if ($tags_id != '' && $tags_id != null) {
					
					$this->load->model ( 'setting/tags' );
					$taginfo_a = $this->model_setting_tags->getTag ( $tags_id );
					
					if ($taginfo_a ['emp_tag_id'] != null && $taginfo_a ['emp_tag_id'] != "") {
						$femp_tag_id = $taginfo_a ['emp_tag_id'];
						
						$outputFolderUrl = $s3file;
						// require_once(DIR_APPLICATION_AWS . 'facerecognition_insert_tags_config.php');
						
						$result_inser_user_img22 = $this->awsimageconfig->indexFacesbytag ( $outputFolderUrl, $femp_tag_id, $facilities_id );
						
						foreach ( $result_inser_user_img22 ['FaceRecords'] as $b ) {
							$FaceId = $b ['Face'] ['FaceId'];
							$ImageId = $b ['Face'] ['ImageId'];
						}
						
						$this->model_setting_tags->insertTagimageenroll ( $tags_id, $FaceId, $ImageId, $s3file, $facilities_id );
					}
				}
			} else {
				$date_added = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
				
				$tsql = "INSERT INTO " . DB_PREFIX . "tags_enroll SET enroll_image = '" . $this->db->escape ( $s3file ) . "',tags_id = '" . $this->db->escape ( $tags_id ) . "',FaceId = '" . $this->db->escape ( $FaceId ) . "', ImageId = '" . $this->db->escape ( $ImageId ) . "', date_added = '" . $date_added . "', date_updated = '" . $date_added . "' ";
				
				$this->db->query ( $tsql );
			}
		}
		
		if ($data ['new_module']) {
			
			$i = 0;
			
			foreach ( $data ['new_module'] as $mediactiondata ) {
				
				$date = str_replace ( '-', '/', $mediactiondata ['start_date'] );
				$res = explode ( "/", $date );
				$dateRange = $res [2] . "-" . $res [0] . "-" . $res [1];
				
				$time = date ( 'H:i:s' );
				$start_date = $dateRange . ' ' . $time;
				
				$date2 = str_replace ( '-', '/', $mediactiondata ['end_date'] );
				$res3 = explode ( "/", $date2 );
				$dateRange2 = $res3 [2] . "-" . $res3 [0] . "-" . $res3 [1];
				
				$time2 = date ( 'H:i:s' );
				$end_date = $dateRange2 . ' ' . $time2;
				
				$this->db->query ( "INSERT INTO `" . DB_PREFIX . "medication` SET drug_name = '" . $this->db->escape ( $mediactiondata ['drug_name'] ) . "', dose = '" . $this->db->escape ( $mediactiondata ['dose'] ) . "', drug_type = '" . $this->db->escape ( $mediactiondata ['drug_type'] ) . "', quantity = '" . $this->db->escape ( $mediactiondata ['quantity'] ) . "', frequency = '" . $this->db->escape ( $mediactiondata ['frequency'] ) . "', start_time = '" . $this->db->escape ( $mediactiondata ['start_time'] ) . "', instructions = '" . $this->db->escape ( $mediactiondata ['instructions'] ) . "', start_date = '" . $this->db->escape ( $start_date ) . "', end_date = '" . $this->db->escape ( $end_date ) . "' , status = '" . $this->db->escape ( $mediactiondata ['status'] ) . "', count = '" . $this->db->escape ( $mediactiondata ['count'] ) . "', tags_id = '" . $tags_id . "'" );
				
				$medications_id = $this->db->getLastId ();
				
				if ($mediactiondata ['start_time']) {
					foreach ( $mediactiondata ['start_time'] as $time ) {
						
						$tasksTiming = date ( 'H:i:s', strtotime ( $time ) );
						$this->db->query ( "INSERT INTO `" . DB_PREFIX . "medication_time` SET start_time = '" . $this->db->escape ( $tasksTiming ) . "', medication_id = '" . $medications_id . "', tags_id = '" . $tags_id . "' " );
					}
				}
				
				$i ++;
			}
		}
		
		$this->load->model ( 'activity/activity' );
		$adata ['tags_id'] = $tags_id;
		$adata ['enroll_image'] = $s3file;
		$adata ['emp_tag_id'] = $emp_tag_id;
		$adata ['phone_device_id'] = $data ['phone_device_id'];
		$adata ['is_android'] = $data ['is_android'];
		$adata ['referred_facility'] = $data ['referred_facility'];
		$adata ['emp_first_name'] = $data ['emp_first_name'];
		$adata ['emp_last_name'] = $data ['emp_last_name'];
		$adata ['gender'] = $data ['gender'];
		$adata ['dob'] = $dob;
		$adata ['facilities_id'] = $facilities_id;
		$adata ['date_added'] = $date_added;
		$this->model_activity_activity->addActivitySave ( 'addTags', $adata, 'query' );
		return $tags_id;
	}
	public function addclientsign($data, $data2) {
		
		// //var_dump($data2);
		// die;
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'setting/tags' );
		
		$facilities_id = $data2 ['facilities_id'];
		
		$facilitytimezone = $data2 ['facilitytimezone'];
		$tags_id = $data2 ['tags_id'];
		
		$timezone_name = $facilitytimezone;
		$timeZone = date_default_timezone_set ( $timezone_name );
		$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		$date_added = ( string ) $noteDate;
		
		$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
		
		if ($data ['imgOutput']) {
			$data ['imgOutput'] = $data ['imgOutput'];
		} else {
			$data ['imgOutput'] = $data ['signature'];
		}
		
		$data ['notes_pin'] = $data ['notes_pin'];
		$data ['user_id'] = $data ['user_id'];
		$data ['notes_type'] = $data ['notes_type'];
		$data ['phone_device_id'] = $data ['phone_device_id'];
		$data ['is_android'] = $data ['is_android'];
		
		$tag_info = $this->model_setting_tags->getTag ( $tags_id );
		
		$data ['emp_tag_id'] = $tag_info ['emp_tag_id'];
		$data ['tags_id'] = $tag_info ['tags_id'];
		
		$data ['tag_classification_id'] = $data2 ['tag_classification_id'];
		$data ['tag_status_id'] = $data2 ['tag_status_id'];
		
		$data ['keyword_file'] = INTAKE_ICON;
		
		$this->load->model ( 'setting/keywords' );
		$keywordData2 = $this->model_setting_keywords->getkeywordDetaildesc ( $data ['keyword_file'], $tag_info ['facilities_id'] );
		
		if ($data ['comments'] != null && $data ['comments']) {
			$comments = ' | ' . $data ['comments'];
		}
		
		$this->load->model ( 'facilities/facilities' );
		$facility_info = $this->model_facilities_facilities->getfacilities ( $tag_info ['facilities_id'] );
		
		// $data['notes_description'] = $keywordData2['keyword_name'].' | '. $tag_info['emp_tag_id'].':'.$tag_info['emp_first_name'] .''.$comments;
		
		$client_t = "";
		if ($tag_info ['tags_status_in'] == 'Admitted') {
			$client_t = "admitted";
		}
		if ($tag_info ['tags_status_in'] == 'Wait listed') {
			$client_t = "wait listed";
		}
		if ($tag_info ['tags_status_in'] == 'Referred') {
			$client_t = "referred";
		}
		if ($tag_info ['tags_status_in'] == 'Closed') {
			$client_t = "closed";
		}
		
		$data ['notes_description'] = $keywordData2 ['keyword_name'] . ' | ' . $tag_info ['tags_status_in'] . '-' . $tag_info ['emp_tag_id'] . ':' . $tag_info ['emp_first_name'] . ' has been ' . $client_t . ' to ' . $facility_info ['facility'] . ' ' . $comments;
		
		$data ['date_added'] = $date_added;
		$data ['note_date'] = $date_added;
		$data ['notetime'] = $notetime;
		
		
		
		$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $facilities_id );
		
		$this->db->query ( "UPDATE `" . DB_PREFIX . "tags` SET status = '1', discharge = '0' WHERE tags_id = '" . ( int ) $tags_id . "'" );
		
		if ($tag_info ['forms_id'] > 0) {
			$this->db->query ( "UPDATE `" . DB_PREFIX . "forms` SET tags_id = '" . $this->db->escape ( $tags_id ) . "', date_updated = '" . $this->db->escape ( $date_added ) . "' WHERE forms_id = '" . ( int ) $tag_info ['forms_id'] . "'" );
		}
		
		$this->db->query ( "UPDATE `" . DB_PREFIX . "notes` SET is_tag = '" . ( int ) $tags_id . "', notes_conut ='0', form_type = '2' WHERE notes_id = '" . ( int ) $notes_id . "'" );
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		
		if ($data ['notes_type'] == null && $data ['notes_type'] == "") {
			if ($facility ['is_enable_add_notes_by'] == '1') {
				$sql122 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '4', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
				$this->db->query ( $sql122 );
			}
			if ($facility ['is_enable_add_notes_by'] == '3') {
				$sql13 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '5', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
				$this->db->query ( $sql13 );
			}
		}
		
		if ($facility ['is_enable_add_notes_by'] == '1') {
			if ($this->session->data ['local_image_dir'] != null && $this->session->data ['local_image_dir'] != "") {
				
				$notes_file = $this->session->data ['local_notes_file'];
				$outputFolder = $this->session->data ['local_image_dir'];
				// $facilities_id = $facilities_id;
				
				require_once (DIR_SYSTEM . 'library/awsstorage/s3_config.php');
				$this->load->model ( 'notes/notes' );
				$this->model_notes_notes->updateuserpicture ( $s3file, $notes_id );
				
				if ($this->session->data ['username_confirm'] != null && $this->session->data ['username_confirm'] != "") {
					$this->model_notes_notes->updateuserverified ( '2', $notes_id );
				}
				
				if ($this->session->data ['username_confirm'] == null && $this->session->data ['username_confirm'] == "") {
					$this->model_notes_notes->updateuserverified ( '1', $notes_id );
				}
				
				unlink ( $this->session->data ['local_image_dir'] );
				unset ( $this->session->data ['username_confirm'] );
				unset ( $this->session->data ['local_image_dir'] );
				unset ( $this->session->data ['local_image_url'] );
				unset ( $this->session->data ['local_notes_file'] );
			}
		}
		/*
		 * $design_forms = array();
		 * $form_description = "";
		 * $rules_form_description = "";
		 *
		 * if($tag_info['emp_tag_id'] != null && $tag_info['emp_tag_id'] != ""){
		 * $design_forms['emp_tag_id'] = $tag_info['emp_tag_id'];
		 * $form_description .= $tag_info['emp_tag_id'] .' ';
		 * $rules_form_description .= 'emp_tag_id:'.$tag_info['emp_tag_id'] .' ';
		 * }
		 *
		 * if($tag_info['emp_extid'] != null && $tag_info['emp_extid'] != ""){
		 * $design_forms['empextid'] = $tag_info['emp_extid'];
		 * $form_description .= $tag_info['emp_extid'] .' ';
		 * $rules_form_description .= 'empextid:'.$tag_info['emp_extid'] .' ';
		 * }
		 * if($tag_info['ssn'] != null && $tag_info['ssn'] != ""){
		 * $design_forms['ssn'] = $tag_info['ssn'];
		 * $form_description .= $tag_info['ssn'] .' ';
		 * $rules_form_description .= 'ssn:'.$tag_info['ssn'] .' ';
		 * }
		 * if($tag_info['emp_first_name'] != null && $tag_info['emp_first_name'] != ""){
		 * $design_forms['text11'] = $tag_info['emp_first_name'];
		 * $form_description .= $tag_info['emp_first_name'] .' ';
		 * $rules_form_description .= 'text11:'.$tag_info['emp_first_name'] .' ';
		 * }
		 * if($tag_info['emp_last_name'] != null && $tag_info['emp_last_name'] != ""){
		 * $design_forms['text44'] = $tag_info['emp_last_name'];
		 * $form_description .= $tag_info['emp_last_name'] .' ';
		 * $rules_form_description .= 'text44:'.$tag_info['emp_last_name'] .' ';
		 * }
		 * if($tag_info['dob'] != null && $tag_info['dob'] != ""){
		 * if($tag_info['dob'] != "0000-00-00"){
		 * $dob = date('m-d-Y', strtotime($tag_info['dob']));
		 * }else{
		 * $dob = '';
		 * }
		 *
		 * $age = $tag_info['age'];
		 *
		 * $design_forms['date12'] = $dob;
		 * $design_forms['text13'] = $age;
		 * $form_description .= $dob .' ';
		 * $form_description .= $age .' ';
		 * $rules_form_description .= 'date12:'.$dob .' ';
		 * $rules_form_description .= 'text13:'.$age .' ';
		 * }
		 * if($tag_info['gender'] != null && $tag_info['gender'] != ""){
		 *
		 * if($tag_info['gender'] == '1'){
		 * $gender = 'Male';
		 * }
		 * if($tag_info['gender'] == '2'){
		 * $gender = 'Female';
		 * }
		 *
		 * $design_forms['select21'] = $gender;
		 * $form_description .= $gender .' ';
		 * $rules_form_description .= 'select21:'.$gender .' ';
		 * }
		 * if($tag_info['emergency_contact'] != null && $tag_info['emergency_contact'] != ""){
		 * $design_forms['text9'] = $tag_info['emergency_contact'];
		 * $form_description .= $tag_info['emergency_contact'] .' ';
		 * $rules_form_description .= 'text9:'.$tag_info['emergency_contact'] .' ';
		 * }
		 * if($tag_info['location_address'] != null && $tag_info['location_address'] != ""){
		 * $design_forms['text14'] = $tag_info['location_address'];
		 * $form_description .= $tag_info['location_address'] .' ';
		 * $rules_form_description .= 'text14:'.$tag_info['location_address'] .' ';
		 * }
		 * if($tag_info['address_street2'] != null && $tag_info['address_street2'] != ""){
		 * $design_forms['text6'] = $tag_info['address_street2'];
		 * $form_description .= $tag_info['address_street2'] .' ';
		 * $rules_form_description .= 'text6:'.$tag_info['address_street2'] .' ';
		 * }
		 * if($tag_info['city'] != null && $tag_info['city'] != ""){
		 * $design_forms['text15'] = $tag_info['city'];
		 * $form_description .= $tag_info['city'] .' ';
		 * $rules_form_description .= 'text15:'.$tag_info['city'] .' ';
		 * }
		 * if($tag_info['state'] != null && $tag_info['state'] != ""){
		 * $design_forms['text17'] = $tag_info['state'];
		 * $form_description .= $tag_info['state'] .' ';
		 * $rules_form_description .= 'text17:'.$tag_info['state'] .' ';
		 * }
		 * if($tag_info['zipcode'] != null && $tag_info['zipcode'] != ""){
		 * $design_forms['text18'] = $tag_info['zipcode'];
		 * $form_description .= $tag_info['zipcode'] .' ';
		 * $rules_form_description .= 'text18:'.$tag_info['zipcode'] .' ';
		 * }
		 *
		 * if($tag_info['date_of_screening'] != null && $tag_info['date_of_screening'] != ""){
		 *
		 * if($tag_info['date_of_screening'] != "0000-00-00"){
		 * $date_of_screening = date('m-d-Y', strtotime($tag_info['date_of_screening']));
		 * }else{
		 * $date_of_screening = '';
		 * }
		 *
		 * $design_forms['dateofscreening'] = $date_of_screening;
		 * $form_description .= $date_of_screening .' ';
		 * $rules_form_description .= 'dateofscreening:'.$date_of_screening .' ';
		 * }
		 *
		 * if($tag_info['person_screening'] != null && $tag_info['person_screening'] != ""){
		 * $design_forms['personscreening'] = $tag_info['person_screening'];
		 * $form_description .= $tag_info['person_screening'] .' ';
		 * $rules_form_description .= 'personscreening:'.$tag_info['person_screening'] .' ';
		 * }
		 *
		 * if($tag_info['room'] != null && $tag_info['room'] != ""){
		 * $design_forms['room'] = $tag_info['room'];
		 * $form_description .= $tag_info['room'] .' ';
		 * $rules_form_description .= 'room:'.$tag_info['room'] .' ';
		 * }
		 *
		 * if($tag_info['tagstatus'] != null && $tag_info['tagstatus'] != ""){
		 * $design_forms['tagstatus'] = $tag_info['tagstatus'];
		 * $form_description .= $tag_info['tagstatus'] .' ';
		 * $rules_form_description .= 'tagstatus:'.$tag_info['tagstatus'] .' ';
		 * }
		 *
		 * if($tag_info['med_mental_health'] != null && $tag_info['med_mental_health'] != ""){
		 * $design_forms['medmentalhealth'] = $tag_info['med_mental_health'];
		 * $form_description .= $tag_info['med_mental_health'] .' ';
		 * $rules_form_description .= 'medmentalhealth:'.$tag_info['med_mental_health'] .' ';
		 * }
		 *
		 * if($tag_info['constant_sight'] != null && $tag_info['constant_sight'] != ""){
		 * $design_forms['constantsight'] = $tag_info['constant_sight'];
		 * $form_description .= $tag_info['constant_sight'] .' ';
		 * $rules_form_description .= 'constantsight:'.$tag_info['constant_sight'] .' ';
		 * }
		 *
		 * if($tag_info['alert_info'] != null && $tag_info['alert_info'] != ""){
		 * $design_forms['alertinfo'] = $tag_info['alert_info'];
		 * $form_description .= $tag_info['alert_info'] .' ';
		 * $rules_form_description .= 'alertinfo:'.$tag_info['alert_info'] .' ';
		 * }
		 *
		 * if($tag_info['prescription'] != null && $tag_info['prescription'] != ""){
		 * $design_forms['prescription'] = $tag_info['prescription'];
		 * $form_description .= $tag_info['prescription'] .' ';
		 * $rules_form_description .= 'prescription:'.$tag_info['prescription'] .' ';
		 * }
		 *
		 * if($tag_info['restriction_notes'] != null && $tag_info['restriction_notes'] != ""){
		 * $design_forms['restrictionnotes'] = $tag_info['restriction_notes'];
		 * $form_description .= $tag_info['restriction_notes'] .' ';
		 * $rules_form_description .= 'restrictionnotes:'.$tag_info['restriction_notes'] .' ';
		 * }
		 *
		 * $this->load->model('form/form');
		 *
		 * $form_data = $this->model_form_form->getFormdata(CUSTOME_INTAKEID);
		 *
		 * $form_name = $form_data['form_name'];
		 *
		 * $sql = "INSERT INTO " . DB_PREFIX . "forms SET design_forms = '" . $this->db->escape(serialize($design_forms)) . "',form_description = '" . $this->db->escape($form_description) . "',rules_form_description = '" . $this->db->escape($rules_form_description) . "', notes_id = '" . $notes_id . "', facilities_id = '" . $facilities_id . "', form_type = '3', custom_form_type = '".CUSTOME_INTAKEID."', upload_file = '".$tag_info['upload_file']."', form_signature = '".$formdata['form_signature']."', incident_number='".$form_name."', date_added = '" . $date_added . "', date_updated = '" . $date_added . "',tags_id = '" . $tags_id . "', form_date_added = '" . $date_added . "' ";
		 *
		 * $this->db->query($sql);
		 *
		 * $forms_id = $this->db->getLastId();
		 *
		 * $fsql = "INSERT INTO `" . DB_PREFIX . "tags_forms` SET tags_id = '" . $tags_id . "', notes_id = '" . $notes_id . "', forms_design_id = '".CUSTOME_INTAKEID."', forms_id = '" . $forms_id . "', facilities_id = '" . $facilities_id . "', design_forms = '" . $this->db->escape(serialize($design_forms)) . "', form_description = '" . $this->db->escape($form_description) . "',rules_form_description = '" . $this->db->escape($rules_form_description) . "',user_id = '', signature = '', notes_pin = '', notes_type = '', date_updated = '" . $date_added . "', form_date_added = '" . $date_added . "', date_added = '".$date_added."', upload_file = '".$tag_info['upload_file']."', type = '3' , status = '1' ";
		 *
		 * $this->db->query($fsql);
		 * $tags_forms_id = $this->db->getLastId();
		 *
		 *
		 * $this->db->query("UPDATE `" . DB_PREFIX . "tags` SET forms_id = '".$forms_id."', tags_forms_id = '" . $tags_forms_id . "' WHERE tags_id = '" . (int)$tags_id . "'");
		 */
		
		$this->load->model ( 'activity/activity' );
		$adata ['notes_id'] = $notes_id;
		$adata ['tags_id'] = $tags_id;
		$adata ['phone_device_id'] = $data ['phone_device_id'];
		$adata ['is_android'] = $data ['is_android'];
		$adata ['user_id'] = $data ['user_id'];
		$adata ['archive_tags_id'] = $data2 ['archive_tags_id'];
		$adata ['facilities_id'] = $facilities_id;
		$adata ['comments'] = $comments;
		$adata ['date_added'] = $date_added;
		$this->model_activity_activity->addActivitySave ( 'addclientsign', $adata, 'query' );
		
		return $notes_id;
	}
	public function editTags($tags_id, $data, $facilities_id) {
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		$this->load->model ( 'setting/timezone' );
		
		$room = $data ['room_id'];
		
		if ($data ['room_id'] > 0) {
			$this->load->model ( 'setting/locations' );
			$location_info = $this->model_setting_locations->getlocation ( $data ['room_id'] );
			$lname = $location_info ['location_name'];
		}
		
		if ($data ['gender'] != null && $data ['gender'] != "") {
			$this->load->model ( 'form/form' );
			$customlistvalues_info = $this->model_form_form->getcustomlistvalues ( $data ['gender'] );
			
			if ($customlistvalues_info ['gender'] != null && $customlistvalues_info ['gender'] != '0') {
				$gender = $customlistvalues_info ['gender'];
				$gname = $customlistvalues_info ['customlistvalues_name'];
			} else {
				$gender = '1';
				$gname = "Male";
			}
		} else {
			$gender = '1';
			$gname = "Male";
		}
		
		$alldata = "";
		foreach ( $data as $a ) {
			$alldata .= $a . ' ';
		}
		$alldata .= $facilities_info ['facility'] . ' ';
		$alldata .= $gname . ' ';
		$alldata .= $lname . ' ';
		
		
		$this->load->model ( 'api/encrypt' );
		$hidden_fields = $this->getHiddenFields ();
		$query12 = $this->db->query ( "SELECT * FROM `" . DB_PREFIX . "tags` WHERE tags_id = '" . $tags_id . "' " );
		
		if ($query12->num_rows > 0) {
			$mrow = $query12->row;
			
			$sql444 = "INSERT INTO `" . DB_PREFIX . "archive_tags` SET 
			emp_tag_id = '" . $this->db->escape ( $mrow ['emp_tag_id'] ) . "'
			,tags_id = '" . $this->db->escape ( $mrow ['tags_id'] ) . "'
			, emp_first_name = '" . $this->db->escape ( $mrow ['emp_first_name'] ) . "'
			, emp_middle_name = '" . $this->db->escape ( $mrow ['emp_middle_name'] ) . "'
			, emp_last_name = '" . $this->db->escape ( $mrow ['emp_last_name'] ) . "'
			, privacy = '" . $this->db->escape ( $mrow ['privacy'] ) . "'
			, sort_order = '" . ( int ) $mrow ['sort_order'] . "'
			, status = '" . $mrow ['status'] . "'
			, doctor_name = '" . $this->db->escape ( $mrow ['doctor_name'] ) . "'
			, emergency_contact = '" . $this->db->escape ( $mrow ['emergency_contact'] ) . "'
			, dob = '" . $mrow ['dob'] . "'
			, medication = '" . $mrow ['medication'] . "'
			, locations_id = '" . $mrow ['locations_id'] . "'
			, facilities_id = '" . $mrow ['facilities_id'] . "'
			, upload_file = '" . $this->db->escape ( $mrow ['upload_file'] ) . "'
			, tags_pin = '" . $this->db->escape ( $mrow ['tags_pin'] ) . "'
			, gender = '" . $this->db->escape ( $mrow ['gender'] ) . "'
			, discharge = '" . $this->db->escape ( $mrow ['discharge'] ) . "'
			, age = '" . $mrow ['age'] . "'
			, role_call = '" . $mrow ['role_call'] . "'
			, date_added = '" . $mrow ['date_added'] . "'
			, modify_date = '" . $mrow ['modify_date'] . "'
			, emp_extid = '" . $this->db->escape ( $mrow ['emp_extid'] ) . "'
			, address_street2 = '" . $this->db->escape ( $mrow ['address_street2'] ) . "'
			, person_screening = '" . $this->db->escape ( $mrow ['person_screening'] ) . "'
			, date_of_screening = '" . $mrow ['date_of_screening'] . "'
			, ssn = '" . $this->db->escape ( $mrow ['ssn'] ) . "'
			, state = '" . $this->db->escape ( $mrow ['state'] ) . "'
			, city = '" . $this->db->escape ( $mrow ['city'] ) . "'
			, zipcode = '" . $this->db->escape ( $mrow ['zipcode'] ) . "'
			, location_address = '" . $this->db->escape ( $mrow ['location_address'] ) . "'
			, latitude = '" . $mrow ['latitude'] . "'
			, longitude = '" . $mrow ['longitude'] . "'
			, room = '" . $this->db->escape ( $mrow ['room'] ) . "'
			, restriction_notes = '" . $this->db->escape ( $mrow ['restriction_notes'] ) . "'
			, prescription = '" . $this->db->escape ( $mrow ['prescription'] ) . "'
			, alert_info = '" . $this->db->escape ( $mrow ['alert_info'] ) . "'
			, constant_sight = '" . $this->db->escape ( $mrow ['constant_sight'] ) . "'
			, med_mental_health = '" . $this->db->escape ( $mrow ['med_mental_health'] ) . "'
			, tagstatus = '" . $this->db->escape ( $mrow ['tagstatus'] ) . "'
			, forms_id = '" . $this->db->escape ( $mrow ['forms_id'] ) . "'
			, tags_forms_id = '" . $this->db->escape ( $mrow ['tags_forms_id'] ) . "'
			, discharge_date = '" . $this->db->escape ( $mrow ['discharge_date'] ) . "'
			, stickynote = '" . $this->db->escape ( $mrow ['stickynote'] ) . "'
			, customlistvalues_id = '" . $mrow ['customlistvalues_id'] . "'
			, tags_status_in = '" . $this->db->escape ( $mrow ['tags_status_in'] ) . "'
			, tags_status = '" . $this->db->escape ( $mrow ['tags_status'] ) . "'
			, referred_facility = '" . $this->db->escape ( $mrow ['referred_facility'] ) . "'
			, reminder_time = '" . $this->db->escape ( $mrow ['reminder_time'] ) . "'
			, reminder_date = '" . $this->db->escape ( $mrow ['reminder_date'] ) . "'
			, upload_file_thumb = '" . $this->db->escape ( $mrow ['upload_file_thumb'] ) . "'
			, phone_device_id = '" . $this->db->escape ( $mrow ['phone_device_id'] ) . "'
			, is_android = '" . $this->db->escape ( $mrow ['is_android'] ) . "'
			, unique_id = '" . $this->db->escape ( $mrow ['unique_id'] ) . "'
			, customer_key = '" . $this->db->escape ( $mrow ['customer_key'] ) . "'
			, classification_id = '" . $this->db->escape ( $mrow ['classification_id'] ) . "'
			, medication_inout = '" . $this->db->escape ( $mrow ['medication_inout'] ) . "'
			, tags_notes_id = '" . $this->db->escape ( $mrow ['notes_id'] ) . "'
			, tag_data = '" . $this->db->escape ( $mrow ['tag_data'] ) . "'
			, ccn = '" . $this->db->escape ( $mrow ['ccn'] ) . "'
			, bed_number = '" . $this->db->escape ( $mrow ['bed_number'] ) . "'
			, is_archive = '1'
			
			";
			$this->db->query ( $sql444 );
		}
		
		$archive_tags_id = $this->db->getLastId ();
		
		$querya = $this->db->query ( "SELECT * FROM `" . DB_PREFIX . "tags_all_facility` WHERE tags_id = '" . $tags_id . "' " );
		
		if ($querya->num_rows > 0) {
			$this->db->query ( "INSERT INTO `" . DB_PREFIX . "archive_tags_all_facility` SET 
			emp_tag_id = '" . $this->db->escape ( $querya->row ['emp_tag_id'] ) . "'
			, emp_first_name = '" . $this->db->escape ( $querya->row ['emp_first_name'] ) . "'
			, emp_last_name = '" . $this->db->escape ( $querya->row ['emp_last_name'] ) . "'
			, facilities_id = '" . $querya->row ['facilities_id'] . "'
			, tags_id = '" . ( int ) $querya->row ['tags_id'] . "'
			, tags_all_facility_id = '" . ( int ) $querya->row ['tags_all_facility_id'] . "'
			, location_address = '" . $this->db->escape ( $querya->row ['address'] ) . "'
			, latitude = '" . $querya->row ['latitude'] . "'
			, longitude = '" . $querya->row ['longitude'] . "'
			, status = '" . $querya->row ['status'] . "'
			, unique_id = '" . $querya->row ['unique_id'] . "'
			, archive_tags_id = '" . $archive_tags_id . "'
			, is_archive = '1'
			" );
		}
		if ($data ['facilities_id'] != null && $data ['facilities_id'] != "") {
			$facilities_id = $data ['facilities_id'];
		} else {
			$facilities_id = $facilities_id;
		}
		
		$dob111 = $data ['month_1'] . '-' . $data ['day_1'] . '-' . $data ['year_1'];
		
		$date = str_replace ( '-', '/', $dob111 );
		
		$res = explode ( "/", $date );
		$createdate1 = $res [2] . "-" . $res [0] . "-" . $res [1];
		
		$dob = date ( 'Y-m-d', strtotime ( $createdate1 ) );
		
		$age = (date ( 'Y' ) - date ( 'Y', strtotime ( $dob ) ));
		
		if ($data ['lat'] != null && $data ['lat'] != "") {
			$latitude2 = $data ['lat'];
			$longitude2 = $data ['lng'];
			if (strpos ( $data ['location_address'], '*' ) === false) {
				if (in_array ( 'location_address', $hidden_fields )) {
					$address = $data ['location_address'];
					$data ['location_address'] = $this->model_api_encrypt->encrypt ( $this->db->escape ( $data ['location_address'] ) ) . '::' . $this->model_api_encrypt->encrypt ( $this->additional_enc );
					;
				} else {
					$data ['location_address'] = $this->db->escape ( $data ['location_address'] );
					$address = $data ['location_address'];
				}
			} else {
				$data ['location_address'] = $mrow ['location_address'];
				$address = $data ['location_address'];
				if (in_array ( 'location_address', $hidden_fields )) {
					$address = $this->model_api_encrypt->decrypt ( $data ['location_address'] );
				}
			}
		} else {
			if (strpos ( $data ['location_address'], '*' ) === false) {
				if (in_array ( 'location_address', $hidden_fields )) {
					$address = $data ['location_address'];
					$data ['location_address'] = $this->model_api_encrypt->encrypt ( $this->db->escape ( $data ['location_address'] ) ) . '::' . $this->model_api_encrypt->encrypt ( $this->additional_enc );
					;
				} else {
					$data ['location_address'] = $this->db->escape ( $data ['location_address'] );
					$address = $data ['location_address'];
				}
			} else {
				$data ['location_address'] = $mrow ['location_address'];
				$address = $data ['location_address'];
				if (in_array ( 'location_address', $hidden_fields )) {
					$address = $this->model_api_encrypt->decrypt ( $data ['location_address'] );
				}
			}
			
			// Get JSON results from this request
			$geo = file_get_contents ( 'http://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode ( $address ) . '&sensor=false' );
			// Convert the JSON to an array
			$geo = json_decode ( $geo, true );
			
			if ($geo ['status'] == 'OK') {
				// Get Lat & Long
				$latitude = $geo ['results'] [0] ['geometry'] ['location'] ['lat'];
				$longitude = $geo ['results'] [0] ['geometry'] ['location'] ['lng'];
			}
			
			$latitude2 = $latitude;
			$longitude2 = $longitude;
		}
		
		$date2 = str_replace ( '-', '/', $data ['date_of_screening'] );
		$res2 = explode ( "/", $date2 );
		$date_of_screening = $res2 [2] . "-" . $res2 [0] . "-" . $res2 [1];
		
		$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
		$facilitytimezone = $timezone_info ['timezone_value'];
		$timeZone = date_default_timezone_set ( $timezone_name );
		// $date_added = date('Y-m-d H:i:s', strtotime('now'));
		
		if ($data ['date_added'] != null && $data ['date_added'] != "") {
			$date23 = str_replace ( '-', '/', $data ['date_added'] );
			
			$res23 = explode ( "/", $date23 );
			$createdate144 = $res23 [2] . "-" . $res23 [0] . "-" . $res23 [1];
			$time = date ( 'H:i:s' );
			$date_added = $createdate144 . ' ' . $time;
		}
		
		$modify_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		
		$date = str_replace ( '-', '/', $data ['reminder_date'] );
		$res = explode ( "/", $date );
		$dateRange = $res [2] . "-" . $res [0] . "-" . $res [1];
		$time = date ( 'H:i:s' );
		$reminder_date = $dateRange . ' ' . $time;
		$reminder_time = date ( 'H:i:s', strtotime ( $data ['reminder_time'] ) );
		
		if (strpos ( $data ['emp_first_name'], '*' ) === false) {
			if (in_array ( 'emp_first_name', $hidden_fields )) {
				$data ['emp_first_name'] = $this->model_api_encrypt->encrypt ( $this->db->escape ( $data ['emp_first_name'] ) ) . '::' . $this->model_api_encrypt->encrypt ( $this->additional_enc );
				;
			} else {
				$data ['emp_first_name'] = $this->db->escape ( $data ['emp_first_name'] );
			}
		} else {
			$data ['emp_first_name'] = $mrow ['emp_first_name'];
		}
		if (strpos ( $data ['emp_last_name'], '*' ) === false) {
			if (in_array ( 'emp_last_name', $hidden_fields )) {
				$data ['emp_last_name'] = $this->model_api_encrypt->encrypt ( $this->db->escape ( $data ['emp_last_name'] ) ) . '::' . $this->model_api_encrypt->encrypt ( $this->additional_enc );
				;
			} else {
				$data ['emp_last_name'] = $this->db->escape ( $data ['emp_last_name'] );
			}
		} else {
			$data ['emp_last_name'] = $mrow ['emp_last_name'];
		}
		if (strpos ( $data ['emergency_contact'], '*' ) === false) {
			if (in_array ( 'emergency_contact', $hidden_fields )) {
				$data ['emergency_contact'] = $this->model_api_encrypt->encrypt ( $this->db->escape ( $data ['emergency_contact'] ) ) . '::' . $this->model_api_encrypt->encrypt ( $this->additional_enc );
				;
			} else {
				$data ['emergency_contact'] = $this->db->escape ( $data ['emergency_contact'] );
			}
		} else {
			$data ['emergency_contact'] = $mrow ['emergency_contact'];
		}
		if (in_array ( 'gender', $hidden_fields )) {
			$gender = $this->model_api_encrypt->encrypt ( $this->db->escape ( $gender ) );
		}
		if (strpos ( $data ['emp_extid'], '*' ) === false) {
			if (in_array ( 'emp_extid', $hidden_fields )) {
				$data ['emp_extid'] = $this->model_api_encrypt->encrypt ( $this->db->escape ( $data ['emp_extid'] ) ) . '::' . $this->model_api_encrypt->encrypt ( $this->additional_enc );
				;
			} else {
				$data ['emp_extid'] = $this->db->escape ( $data ['emp_extid'] );
			}
		} else {
			$data ['emp_extid'] = $data ['emp_extid'];
		}
		if (strpos ( $data ['address_street2'], '*' ) === false) {
			if (in_array ( 'address_street2', $hidden_fields )) {
				$data ['address_street2'] = $this->model_api_encrypt->encrypt ( $this->db->escape ( $data ['address_street2'] ) ) . '::' . $this->model_api_encrypt->encrypt ( $this->additional_enc );
				;
			} else {
				$data ['address_street2'] = $this->db->escape ( $data ['address_street2'] );
			}
		} else {
			$data ['address_street2'] = $data ['address_street2'];
		}
		if (strpos ( $data ['person_screening'], '*' ) === false) {
			if (in_array ( 'person_screening', $hidden_fields )) {
				$data ['person_screening'] = $this->model_api_encrypt->encrypt ( $this->db->escape ( $data ['person_screening'] ) ) . '::' . $this->model_api_encrypt->encrypt ( $this->additional_enc );
				;
			} else {
				$data ['person_screening'] = $this->db->escape ( $data ['person_screening'] );
			}
		} else {
			$data ['person_screening'] = $data ['person_screening'];
		}
		if (strpos ( $data ['ssn'], '*' ) === false) {
			if (in_array ( 'ssn', $hidden_fields )) {
				$data ['ssn'] = $this->model_api_encrypt->encrypt ( $this->db->escape ( $data ['ssn'] ) ) . '::' . $this->model_api_encrypt->encrypt ( $this->additional_enc );
				;
			} else {
				$data ['ssn'] = $this->db->escape ( $data ['ssn'] );
			}
		} else {
			$data ['ssn'] = $data ['ssn'];
		}
		if (strpos ( $data ['state'], '*' ) === false) {
			if (in_array ( 'state', $hidden_fields )) {
				$data ['state'] = $this->model_api_encrypt->encrypt ( $this->db->escape ( $data ['state'] ) ) . '::' . $this->model_api_encrypt->encrypt ( $this->additional_enc );
				;
			} else {
				$data ['state'] = $this->db->escape ( $data ['state'] );
			}
		} else {
			$data ['state'] = $data ['state'];
		}
		
		if (strpos ( $data ['city'], '*' ) === false) {
			if (in_array ( 'city', $hidden_fields )) {
				$data ['city'] = $this->model_api_encrypt->encrypt ( $this->db->escape ( $data ['city'] ) ) . '::' . $this->model_api_encrypt->encrypt ( $this->additional_enc );
				;
			} else {
				$data ['city'] = $this->db->escape ( $data ['city'] );
			}
		} else {
			$data ['city'] = $data ['city'];
		}
		if (strpos ( $data ['zipcode'], '*' ) === false) {
			if (in_array ( 'zipcode', $hidden_fields )) {
				$data ['zipcode'] = $this->model_api_encrypt->encrypt ( $this->db->escape ( $data ['zipcode'] ) ) . '::' . $this->model_api_encrypt->encrypt ( $this->additional_enc );
				;
			} else {
				$data ['zipcode'] = $this->db->escape ( $data ['zipcode'] );
			}
		} else {
			$data ['zipcode'] = $data ['zipcode'];
		}
		
		if (in_array ( 'room', $hidden_fields )) {
			$room = $this->model_api_encrypt->encrypt ( $room ) . '::' . $this->model_api_encrypt->encrypt ( $this->additional_enc );
			;
		}
		if (strpos ( $data ['restriction_notes'], '*' ) === false) {
			if (in_array ( 'restriction_notes', $hidden_fields )) {
				$data ['restriction_notes'] = $this->model_api_encrypt->encrypt ( $this->db->escape ( $data ['restriction_notes'] ) ) . '::' . $this->model_api_encrypt->encrypt ( $this->additional_enc );
				;
			} else {
				$data ['restriction_notes'] = $this->db->escape ( $data ['restriction_notes'] );
			}
		} else {
			$data ['restriction_notes'] = $data ['restriction_notes'];
		}
		if (strpos ( $data ['alert_info'], '*' ) === false) {
			if (in_array ( 'alert_info', $hidden_fields )) {
				$data ['alert_info'] = $this->model_api_encrypt->encrypt ( $this->db->escape ( $data ['alert_info'] ) ) . '::' . $this->model_api_encrypt->encrypt ( $this->additional_enc );
				;
			} else {
				$data ['alert_info'] = $this->db->escape ( $data ['alert_info'] );
			}
		} else {
			$data ['alert_info'] = $data ['alert_info'];
		}
		if (strpos ( $data ['constant_sight'], '*' ) === false) {
			if (in_array ( 'constant_sight', $hidden_fields )) {
				$data ['constant_sight'] = $this->model_api_encrypt->encrypt ( $this->db->escape ( $data ['constant_sight'] ) ) . '::' . $this->model_api_encrypt->encrypt ( $this->additional_enc );
				;
			} else {
				$data ['constant_sight'] = $this->db->escape ( $data ['constant_sight'] );
			}
		} else {
			$data ['constant_sight'] = $data ['constant_sight'];
		}
		if (strpos ( $data ['med_mental_health'], '*' ) === false) {
			if (in_array ( 'med_mental_health', $hidden_fields )) {
				$data ['med_mental_health'] = $this->model_api_encrypt->encrypt ( $this->db->escape ( $data ['med_mental_health'] ) ) . '::' . $this->model_api_encrypt->encrypt ( $this->additional_enc );
				;
			} else {
				$data ['med_mental_health'] = $this->db->escape ( $data ['med_mental_health'] );
			}
		} else {
			$data ['med_mental_health'] = $data ['med_mental_health'];
		}
		if (strpos ( $data ['tagstatus'], '*' ) === false) {
			if (in_array ( 'tagstatus', $hidden_fields )) {
				$data ['tagstatus'] = $this->model_api_encrypt->encrypt ( $this->db->escape ( $data ['tagstatus'] ) ) . '::' . $this->model_api_encrypt->encrypt ( $this->additional_enc );
				;
			} else {
				$data ['tagstatus'] = $this->db->escape ( $data ['tagstatus'] );
			}
		} else {
			$data ['tagstatus'] = $data ['tagstatus'];
		}
		if (strpos ( $data ['prescription'], '*' ) === false) {
			if (in_array ( 'prescription', $hidden_fields )) {
				$data ['prescription'] = $this->model_api_encrypt->encrypt ( $this->db->escape ( $data ['prescription'] ) ) . '::' . $this->model_api_encrypt->encrypt ( $this->additional_enc );
				;
			} else {
				$data ['prescription'] = $this->db->escape ( $data ['prescription'] );
			}
		} else {
			$data ['prescription'] = $data ['prescription'];
		}
		
		if ($date_added != null && $date_added != "") {
			$sql = "UPDATE `" . DB_PREFIX . "tags` SET date_added = '" . $date_added . "'  WHERE tags_id = '" . ( int ) $tags_id . "'";
			$this->db->query ( $sql );
		}
		
		if ($data ['emp_first_name'] != null && $data ['emp_first_name'] != "") {
			$sql = "UPDATE `" . DB_PREFIX . "tags` SET emp_first_name = '" . $data ['emp_first_name'] . "', emp_last_name = '" . $data ['emp_last_name'] . "', status = '1', modify_date = '" . $modify_date . "', facilities_id = '" . $facilities_id . "',emp_middle_name = '" . $this->db->escape ( $data ['emp_middle_name'] ) . "'  WHERE tags_id = '" . ( int ) $tags_id . "'";
			$this->db->query ( $sql );
		}
		
		if ($data ['ccn'] != null && $data ['ccn'] != "") {
			$sql1 = "UPDATE `" . DB_PREFIX . "tags` SET ccn = '" . $this->db->escape ( $data ['ccn'] ) . "'  WHERE tags_id = '" . ( int ) $tags_id . "'";
			$this->db->query ( $sql1 );
		}
		if ($data ['privacy'] != null && $data ['privacy'] != "") {
			$sql1 = "UPDATE `" . DB_PREFIX . "tags` SET privacy = '" . $this->db->escape ( $data ['privacy'] ) . "'  WHERE tags_id = '" . ( int ) $tags_id . "'";
			$this->db->query ( $sql1 );
		}
		
		if ($data ['privacy'] != null && $data ['privacy'] != "") {
			$sql1 = "UPDATE `" . DB_PREFIX . "tags` SET privacy = '" . $this->db->escape ( $data ['privacy'] ) . "'  WHERE tags_id = '" . ( int ) $tags_id . "'";
			$this->db->query ( $sql1 );
		}
		
		if ($data ['tag_status_id'] != null && $data ['tag_status_id'] != "") {
			// $sql1 = "UPDATE `" . DB_PREFIX . "tags` SET role_call = '" . $this->db->escape($data['tag_status_id']) . "' WHERE tags_id = '" . (int)$tags_id . "'";
			// $this->db->query($sql1);
			$this->load->model ( 'resident/resident' );
			$this->model_resident_resident->updatetagrolecall ( $tags_id, $data ['tag_status_id'] );
		}
		
		if ($data ['emergency_contact'] != null && $data ['emergency_contact'] != "") {
			$sql1 = "UPDATE `" . DB_PREFIX . "tags` SET emergency_contact = '" . $this->db->escape ( $data ['emergency_contact'] ) . "'  WHERE tags_id = '" . ( int ) $tags_id . "'";
			$this->db->query ( $sql1 );
		}
		
		if ($dob != null && $dob != "") {
			$sql1 = "UPDATE `" . DB_PREFIX . "tags` SET dob = '" . $dob . "', age = '" . $age . "'  WHERE tags_id = '" . ( int ) $tags_id . "'";
			$this->db->query ( $sql1 );
		}
		
		if ($address != null && $address != "") {
			$sql1 = "UPDATE `" . DB_PREFIX . "tags` SET location_address = '" . $address . "', latitude = '" . $latitude2 . "', longitude = '" . $longitude2 . "', state = '" . $data ['state'] . "',city = '" . $data ['city'] . "', zipcode = '" . $data ['zipcode'] . "',address_street2 = '" . $data ['address_street2'] . "' WHERE tags_id = '" . ( int ) $tags_id . "'";
			$this->db->query ( $sql1 );
		}
		if ($room != null && $room != "") {
			$sql1 = "UPDATE `" . DB_PREFIX . "tags` SET room = '" . $room . "' WHERE tags_id = '" . ( int ) $tags_id . "'";
			$this->db->query ( $sql1 );
		}
		
		if ($room != null && $room != "") {
			$sql1 = "UPDATE `" . DB_PREFIX . "tags` SET room = '" . $room . "' WHERE tags_id = '" . ( int ) $tags_id . "'";
			$this->db->query ( $sql1 );
		}
		
		if ($data ['emp_extid'] != null && $data ['emp_extid'] != "") {
			$sql1 = "UPDATE `" . DB_PREFIX . "tags` SET emp_extid = '" . $this->db->escape ( $data ['emp_extid'] ) . "'  WHERE tags_id = '" . ( int ) $tags_id . "'";
			$this->db->query ( $sql1 );
		}
		
		if ($data ['ssn'] != null && $data ['ssn'] != "") {
			$sql1 = "UPDATE `" . DB_PREFIX . "tags` SET ssn = '" . $this->db->escape ( $data ['ssn'] ) . "'  WHERE tags_id = '" . ( int ) $tags_id . "'";
			$this->db->query ( $sql1 );
		}
		
		if ($data ['person_screening'] != null && $data ['person_screening'] != "") {
			$sql1 = "UPDATE `" . DB_PREFIX . "tags` SET person_screening = '" . $this->db->escape ( $data ['person_screening'] ) . "'  WHERE tags_id = '" . ( int ) $tags_id . "'";
			$this->db->query ( $sql1 );
		}
		
		if ($date_of_screening != null && $date_of_screening != "") {
			$sql1 = "UPDATE `" . DB_PREFIX . "tags` SET date_of_screening = '" . $date_of_screening . "' WHERE tags_id = '" . ( int ) $tags_id . "'";
			$this->db->query ( $sql1 );
		}
		
		if ($data ['restriction_notes'] != null && $data ['restriction_notes'] != "") {
			$sql1 = "UPDATE `" . DB_PREFIX . "tags` SET restriction_notes = '" . $this->db->escape ( $data ['restriction_notes'] ) . "'  WHERE tags_id = '" . ( int ) $tags_id . "'";
			$this->db->query ( $sql1 );
		}
		
		if ($data ['prescription'] != null && $data ['prescription'] != "") {
			$sql1 = "UPDATE `" . DB_PREFIX . "tags` SET prescription = '" . $this->db->escape ( $data ['prescription'] ) . "'  WHERE tags_id = '" . ( int ) $tags_id . "'";
			$this->db->query ( $sql1 );
		}
		
		if ($data ['alert_info'] != null && $data ['alert_info'] != "") {
			$sql1 = "UPDATE `" . DB_PREFIX . "tags` SET alert_info = '" . $this->db->escape ( $data ['alert_info'] ) . "'  WHERE tags_id = '" . ( int ) $tags_id . "'";
			$this->db->query ( $sql1 );
		}
		
		if ($data ['constant_sight'] != null && $data ['constant_sight'] != "") {
			$sql1 = "UPDATE `" . DB_PREFIX . "tags` SET constant_sight = '" . $this->db->escape ( $data ['constant_sight'] ) . "'  WHERE tags_id = '" . ( int ) $tags_id . "'";
			$this->db->query ( $sql1 );
		}
		
		if ($data ['med_mental_health'] != null && $data ['med_mental_health'] != "") {
			$sql1 = "UPDATE `" . DB_PREFIX . "tags` SET med_mental_health = '" . $this->db->escape ( $data ['med_mental_health'] ) . "'  WHERE tags_id = '" . ( int ) $tags_id . "'";
			$this->db->query ( $sql1 );
		}
		
		if ($data ['tagstatus'] != null && $data ['tagstatus'] != "") {
			$sql1 = "UPDATE `" . DB_PREFIX . "tags` SET tagstatus = '" . $this->db->escape ( $data ['tagstatus'] ) . "'  WHERE tags_id = '" . ( int ) $tags_id . "'";
			$this->db->query ( $sql1 );
		}
		
		if ($data ['gender'] != null && $data ['gender'] != "") {
			$sql1 = "UPDATE `" . DB_PREFIX . "tags` SET customlistvalues_id = '" . $this->db->escape ( $data ['gender'] ) . "', gender = '" . $this->db->escape ( $gender ) . "'  WHERE tags_id = '" . ( int ) $tags_id . "'";
			$this->db->query ( $sql1 );
		}
		
		if ($data ['tags_status_in'] != null && $data ['tags_status_in'] != "") {
			$sql1 = "UPDATE `" . DB_PREFIX . "tags` SET tags_status_in = '" . $this->db->escape ( $data ['tags_status_in'] ) . "'  WHERE tags_id = '" . ( int ) $tags_id . "'";
			$this->db->query ( $sql1 );
		}
		if ($data ['referred_facility'] != null && $data ['referred_facility'] != "") {
			$sql1 = "UPDATE `" . DB_PREFIX . "tags` SET referred_facility = '" . $this->db->escape ( $data ['referred_facility'] ) . "'  WHERE tags_id = '" . ( int ) $tags_id . "'";
			$this->db->query ( $sql1 );
		}
		
		if ($reminder_time != null && $reminder_time != "") {
			$sql1 = "UPDATE `" . DB_PREFIX . "tags` SET reminder_time = '" . $reminder_time . "'  WHERE tags_id = '" . ( int ) $tags_id . "'";
			$this->db->query ( $sql1 );
		}
		
		if ($reminder_date != null && $reminder_date != "") {
			$sql1 = "UPDATE `" . DB_PREFIX . "tags` SET reminder_date = '" . $reminder_date . "'  WHERE tags_id = '" . ( int ) $tags_id . "'";
			$this->db->query ( $sql1 );
		}
		if ($data ['bed_number'] != null && $data ['bed_number'] != "") {
			$sql1 = "UPDATE `" . DB_PREFIX . "tags` SET bed_number = '" . ( int )$data ['bed_number'] . "'  WHERE tags_id = '" . ( int ) $tags_id . "'";
			$this->db->query ( $sql1 );
		}
		if ($alldata != null && $alldata != "") {
			$sql1 = "UPDATE `" . DB_PREFIX . "tags` SET tag_data = '" . $alldata . "'  WHERE tags_id = '" . ( int ) $tags_id . "'";
			$this->db->query ( $sql1 );
		}
		
		$sql1 = "UPDATE `" . DB_PREFIX . "tags` SET classification_id = '" . $this->db->escape ( $data ['tag_classification_id'] ) . "'  WHERE tags_id = '" . ( int ) $tags_id . "'";
			$this->db->query ( $sql1 );
		
		if ($facilities_id) {
			$this->load->model ( 'facilities/facilities' );
			$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
			$unique_id = $facility ['customer_key'];
		}
		
		// $sql1 = "CALL updateTags('".$this->db->escape($data['emp_first_name'])."','" . $this->db->escape($data['emp_middle_name']) . "','".$this->db->escape($data['emp_last_name'])."','".$this->db->escape($data['privacy'])."','".(int)$data['sort_order']."','".$this->db->escape($data['doctor_name'])."','".$this->db->escape($data['emergency_contact'])."','".$dob."','".$this->db->escape($data['medication'])."','".$this->db->escape($data['locations_id'])."','".$this->db->escape($data['tags_pin'])."','". $this->db->escape($gender)."','".$age."','".$this->db->escape($data['emp_extid'])."','" . $data['address_street2'] . "','".$this->db->escape($data['person_screening'])."','" . $date_of_screening . "','" . $data['ssn'] . "','" . $data['state'] . "','" . $data['city'] . "', '" . $data['zipcode'] . "', '" . $address . "', '" . $latitude2 . "', '" . $longitude2 . "','" . $this->db->escape($room) . "', '" . $data['restriction_notes'] . "','" . $data['prescription'] . "', '" . $data['alert_info'] . "','" . $data['constant_sight'] . "' ,'" . $data['med_mental_health'] . "', '" . $data['tagstatus'] . "', '".$date_added."', '" . $data['gender'] . "', '" . $this->db->escape($data['tags_status_in']) . "', '" . $this->db->escape($data['referred_facility']) . "', '" . $facilities_id . "', '".$reminder_date."', '".$reminder_time."','" . (int)$tags_id . "','" . $unique_id . "' )";
		
		// $this->db->query($sql1);
		
		$sqlta = "UPDATE `" . DB_PREFIX . "tags_all_facility` SET emp_first_name = '" . $this->db->escape ( $data ['emp_first_name'] ) . "', emp_last_name = '" . $this->db->escape ( $data ['emp_last_name'] ) . "', location_address = '" . $this->db->escape ( $address ) . "', latitude = '" . $latitude2 . "', longitude = '" . $longitude2 . "', facilities_id = '" . $facilities_id . "', unique_id = '" . $unique_id . "' WHERE tags_id = '" . ( int ) $tags_id . "' ";
		
		$this->db->query ( $sqlta );
		
		
		$get_img = $this->model_setting_tags->getImage ( $tags_id );
				
		//if ($get_img ['enroll_image'] == null && $get_img ['enroll_image'] == "") {
			
			if ($data ['imageName_url'] != null && $data ['imageName_url'] != "") {
				
				$notes_file = $data ['imageName'];
				$outputFolder = $data ['imageName_path'];
				
				/*
				 * if($this->config->get('enable_storage') == '1'){
				 *
				 * require_once(DIR_SYSTEM . 'library/awsstorage/s3_config.php');
				 * }
				 *
				 * if($this->config->get('enable_storage') == '2'){
				 *
				 *
				 * require_once(DIR_SYSTEM . 'library/azure_storage/config.php');
				 * //uploadBlobSample($blobClient, $outputFolder, $notes_file);
				 * $s3file = AZURE_URL. $notes_file;
				 * }
				 *
				 * if($this->config->get('enable_storage') == '3'){
				 *
				 * //$outputFolder = DIR_IMAGE.'storage/' . $notes_file;
				 * //move_uploaded_file($this->request->files["file"]["tmp_name"], $outputFolder);
				 * //$s3file = HTTPS_SERVER.'image/storage/' . $notes_file;
				 * $s3file = $data['imageName_url'];
				 * }
				 */
				$s3file = $data ['imageName_url'];
				
				// $this->db->query("UPDATE `" . DB_PREFIX . "tags` SET upload_file = '" . $this->db->escape($s3file) . "', upload_file_thumb = '' WHERE tags_id = '" . (int)$tags_id . "'");
				
				$this->load->model ( 'facilities/facilities' );
				$facilities_info = $this->model_facilities_facilities->getfacilities ( $facilities_id );
				
				if ($facilities_info ['is_client_facial'] == '1') {
					
					/*if ($tags_id != '' && $tags_id != null) {
						
						$this->load->model ( 'setting/tags' );
						$taginfo_a = $this->model_setting_tags->getTag ( $tags_id );
						
						if ($taginfo_a ['emp_tag_id'] != null && $taginfo_a ['emp_tag_id'] != "") {
							$femp_tag_id = $taginfo_a ['emp_tag_id'];
							
							$outputFolderUrl = $s3file;
							// require_once(DIR_APPLICATION_AWS . 'facerecognition_insert_tags_config.php');
							
							$result_inser_user_img22 = $this->awsimageconfig->indexFacesbytag ( $outputFolderUrl, $femp_tag_id, $facilities_id );
							
							foreach ( $result_inser_user_img22 ['FaceRecords'] as $b ) {
								$FaceId = $b ['Face'] ['FaceId'];
								$ImageId = $b ['Face'] ['ImageId'];
							}
							
							$this->model_setting_tags->insertTagimageenroll ( $tags_id, $FaceId, $ImageId, $s3file, $facilities_id );
						}
					}*/
					
					$date_added = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
					
						$tsql = "INSERT INTO " . DB_PREFIX . "tags_enroll SET enroll_image = '" . $this->db->escape ( $s3file ) . "',tags_id = '" . $this->db->escape ( $tags_id ) . "',FaceId = '" . $this->db->escape ( $FaceId ) . "', ImageId = '" . $this->db->escape ( $ImageId ) . "', date_added = '" . $date_added . "', date_updated = '" . $date_added . "' ";
						
						$this->db->query ( $tsql );
				} else {
					$date_added = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
					
						$tsql = "INSERT INTO " . DB_PREFIX . "tags_enroll SET enroll_image = '" . $this->db->escape ( $s3file ) . "',tags_id = '" . $this->db->escape ( $tags_id ) . "',FaceId = '" . $this->db->escape ( $FaceId ) . "', ImageId = '" . $this->db->escape ( $ImageId ) . "', date_added = '" . $date_added . "', date_updated = '" . $date_added . "' ";
						
						$this->db->query ( $tsql );
						
					
				}
			}
			
			if ($data ['upload_file'] != null && $data ['upload_file'] != "") {
				// $this->db->query("UPDATE `" . DB_PREFIX . "tags` SET upload_file = '" . $this->db->escape($data['upload_file']) . "', upload_file_thumb = '' WHERE tags_id = '" . (int)$tags_id . "'");
				
				$s3file = $data ['upload_file'];
				
				$this->load->model ( 'facilities/facilities' );
				$facilities_info = $this->model_facilities_facilities->getfacilities ( $facilities_id );
				
				if ($facilities_info ['is_client_facial'] == '1') {
					
					if ($tags_id != '' && $tags_id != null) {
						
						$this->load->model ( 'setting/tags' );
						$taginfo_a = $this->model_setting_tags->getTag ( $tags_id );
						
						if ($taginfo_a ['emp_tag_id'] != null && $taginfo_a ['emp_tag_id'] != "") {
							$femp_tag_id = $taginfo_a ['emp_tag_id'];
							
							$outputFolderUrl = $s3file;
							// require_once(DIR_APPLICATION_AWS . 'facerecognition_insert_tags_config.php');
							
							$result_inser_user_img22 = $this->awsimageconfig->indexFacesbytag ( $outputFolderUrl, $femp_tag_id, $facilities_id );
							
							foreach ( $result_inser_user_img22 ['FaceRecords'] as $b ) {
								$FaceId = $b ['Face'] ['FaceId'];
								$ImageId = $b ['Face'] ['ImageId'];
							}
							
							$this->model_setting_tags->insertTagimageenroll ( $tags_id, $FaceId, $ImageId, $s3file, $facilities_id );
						}
					}
				} else {
					$date_added = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
					
					$tsql = "INSERT INTO " . DB_PREFIX . "tags_enroll SET enroll_image = '" . $this->db->escape ( $s3file ) . "',tags_id = '" . $this->db->escape ( $tags_id ) . "',FaceId = '" . $this->db->escape ( $FaceId ) . "', ImageId = '" . $this->db->escape ( $ImageId ) . "', date_added = '" . $date_added . "', date_updated = '" . $date_added . "' ";
					
					$this->db->query ( $tsql );
					
				}
			}
		//}
		
		if ($data ['forms_id'] != null && $data ['forms_id'] != "") {
			$this->db->query ( "UPDATE `" . DB_PREFIX . "forms` SET tags_id = '" . $this->db->escape ( $tags_id ) . "', date_updated = '" . $this->db->escape ( $date_added ) . "' WHERE forms_id = '" . ( int ) $data ['forms_id'] . "'" );
		}
		
		$i = 0;
		
		if ($data ['new_module']) {
			$this->db->query ( "DELETE FROM `" . DB_PREFIX . "medication` WHERE tags_id = '" . ( int ) $tags_id . "'" );
			
			$this->db->query ( "DELETE FROM `" . DB_PREFIX . "medication_time` WHERE tags_id = '" . ( int ) $tags_id . "'" );
			
			foreach ( $data ['new_module'] as $mediactiondata ) {
				
				$date = str_replace ( '-', '/', $mediactiondata ['start_date'] );
				$res = explode ( "/", $date );
				$dateRange = $res [2] . "-" . $res [0] . "-" . $res [1];
				
				$time = date ( 'H:i:s' );
				$start_date = $dateRange . ' ' . $time;
				
				$date2 = str_replace ( '-', '/', $mediactiondata ['end_date'] );
				$res3 = explode ( "/", $date2 );
				$dateRange2 = $res3 [2] . "-" . $res3 [0] . "-" . $res3 [1];
				
				$time2 = date ( 'H:i:s' );
				$end_date = $dateRange2 . ' ' . $time2;
				
				$this->db->query ( "INSERT INTO `" . DB_PREFIX . "medication` SET drug_name = '" . $this->db->escape ( $mediactiondata ['drug_name'] ) . "', dose = '" . $this->db->escape ( $mediactiondata ['dose'] ) . "', drug_type = '" . $this->db->escape ( $mediactiondata ['drug_type'] ) . "', quantity = '" . $this->db->escape ( $mediactiondata ['quantity'] ) . "', frequency = '" . $this->db->escape ( $mediactiondata ['frequency'] ) . "', start_time = '" . $this->db->escape ( $mediactiondata ['start_time'] ) . "', instructions = '" . $this->db->escape ( $mediactiondata ['instructions'] ) . "', start_date = '" . $this->db->escape ( $start_date ) . "', end_date = '" . $this->db->escape ( $end_date ) . "' , status = '" . $this->db->escape ( $mediactiondata ['status'] ) . "', count = '" . $this->db->escape ( $mediactiondata ['count'] ) . "', tags_id = '" . $tags_id . "'" );
				
				$medications_id = $this->db->getLastId ();
				
				if ($mediactiondata ['start_time']) {
					foreach ( $mediactiondata ['start_time'] as $time ) {
						
						$tasksTiming = date ( 'H:i:s', strtotime ( $time ) );
						$this->db->query ( "INSERT INTO `" . DB_PREFIX . "medication_time` SET start_time = '" . $this->db->escape ( $tasksTiming ) . "', medication_id = '" . $medications_id . "', tags_id = '" . $tags_id . "' " );
					}
				}
				
				$i ++;
			}
		}
		
		if ($data ['tag_classification_id'] != null && $data ['tag_classification_id'] != "") {
			
			$sql = "INSERT INTO `" . DB_PREFIX . "tagstatus` SET tags_id = '" . $this->db->escape ( $tags_id ) . "', status = '" . $this->db->escape ( $data ['tag_classification_id'] ) . "'";
			$this->db->query ( $sql );
		}
		
		/* health info is added */
		
		$query1 = $this->db->query ( "SELECT tags_medication_id,tags_id,medication_fields,status,is_schedule,is_discharge FROM `" . DB_PREFIX . "tags_medication` WHERE tags_id = '" . $tags_id . "' and is_discharge = '0' " );
		
		if ($query1->num_rows > 0) {
			$this->db->query ( "UPDATE `" . DB_PREFIX . "tags_medication` SET medication_fields = '" . $this->db->escape ( serialize ( $data ['medication_fields'] ) ) . "', is_schedule = '" . $this->db->escape ( $data ['is_schedule'] ) . "', status = '1' where tags_id = '" . $tags_id . "' " );
			
			$tags_medication_id = $query1->row ['tags_medication_id'];
		} else {
			$this->db->query ( "INSERT INTO `" . DB_PREFIX . "tags_medication` SET medication_fields = '" . $this->db->escape ( serialize ( $data ['medication_fields'] ) ) . "', is_schedule = '" . $this->db->escape ( $data ['is_schedule'] ) . "' , status = '1', tags_id = '" . $tags_id . "'" );
			
			$tags_medication_id = $this->db->getLastId ();
		}
		
		/* health info is added */
		
		$this->load->model ( 'activity/activity' );
		$adata ['tags_id'] = $tags_id;
		$adata ['enroll_image'] = $s3file;
		$adata ['emp_tag_id'] = $emp_tag_id;
		$adata ['phone_device_id'] = $data ['phone_device_id'];
		$adata ['is_android'] = $data ['is_android'];
		$adata ['referred_facility'] = $data ['referred_facility'];
		$adata ['emp_first_name'] = $data ['emp_first_name'];
		$adata ['emp_last_name'] = $data ['emp_last_name'];
		$adata ['gender'] = $data ['gender'];
		$adata ['dob'] = $dob;
		$adata ['facilities_id'] = $facilities_id;
		$adata ['modify_date'] = $date_added;
		$this->model_activity_activity->addActivitySave ( 'editTags', $adata, 'query' );
		
		return $archive_tags_id;
	}
	public function updateclientsign($data, $data2) {
		$this->load->model ( 'notes/notes' );
		$this->load->model ( 'setting/tags' );
		
		$facilities_id = $data2 ['facilities_id'];
		$facilitytimezone = $data2 ['facilitytimezone'];
		$tags_id = $data2 ['tags_id'];
		$notes_id = $data2 ['notes_id'];
		
		$timezone_name = $facilitytimezone;
		$timeZone = date_default_timezone_set ( $timezone_name );
		$noteDate = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		$date_added = ( string ) $noteDate;
		
		$notetime = date ( 'H:i:s', strtotime ( 'now' ) );
		
		$tag_info = $this->model_setting_tags->getTag ( $tags_id );
		
		$forms_id = $tag_info ['forms_id'];
		$tags_forms_id = $tag_info ['tags_forms_id'];
		
		$notes_info = $this->model_notes_notes->getNote ( $notes_id );
		$notes_description = $notes_info ['notes_description'];
		
		/*
		 * if($data['comments'] != null && $data['comments']){
		 * $comments = ' | '.$data['comments'];
		 *
		 * $notes_description2 = $notes_description . $comments;
		 *
		 * $sql = "UPDATE `" . DB_PREFIX . "notes` SET notes_description = '" . $this->db->escape($notes_description2) . "' where notes_id = '" . (int)$notes_id . "' ";
		 * $this->db->query($sql);
		 *
		 * }
		 */
		
		if ($data ['imgOutput']) {
			$data ['imgOutput'] = $data ['imgOutput'];
		} else {
			$data ['imgOutput'] = $data ['signature'];
		}
		
		$data ['notes_pin'] = $data ['notes_pin'];
		$data ['user_id'] = $data ['user_id'];
		$data ['notes_type'] = $data ['notes_type'];
		
		$data ['phone_device_id'] = $data ['phone_device_id'];
		$data ['is_android'] = $data ['is_android'];
		
		$data ['tag_classification_id'] = $data2 ['tag_classification_id'];
		$data ['tag_status_id'] = $data2 ['tag_status_id'];
		
		$tag_info = $this->model_setting_tags->getTag ( $tags_id );
		
		$this->load->model ( 'facilities/facilities' );
		$facility_info = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		
		$data ['emp_tag_id'] = $tag_info ['emp_tag_id'];
		$data ['tags_id'] = $tag_info ['tags_id'];
		
		$newdes = "";
		if ($data2 ['tag_status_id'] != null && $data2 ['tag_status_id'] != "") {
			$this->load->model ( 'notes/clientstatus' );
			// $clientstatus_info = $this->model_notes_clientstatus->getclientstatus($tag_info['role_call']);
			$clientstatus_info = $this->model_notes_clientstatus->getclientstatus ( $tag_info ['role_call'] );
			$roleCall = $clientstatus_info ['name'];
			
			$caltime = "";
			$tracktime = "";
			$status_total_time = 0;
			// echo '<pre>'; print_r($clientstatus_info); echo '</pre>';
			
			if ($clientstatus_info ['track_time'] == 1) {
				$this->load->model ( 'notes/notes' );
				$notes_data = $this->model_notes_notes->getnotes ( $tag_info ['notes_id'] );
				// echo '<pre>'; print_r($notes_data); echo '</pre>';
				$current_date = date ( 'Y-m-d H:i:s' );
				$start_date = new DateTime ( $notes_data ['date_added'] );
				$since_start = $start_date->diff ( new DateTime ( $current_date ) );
				
				if ($since_start->y > 0) {
					$caltime .= $since_start->y . ' years ';
					$status_total_time = 60 * 24 * 365 * $since_start->y;
				}
				
				if ($since_start->m > 0) {
					$caltime .= $since_start->m . ' months ';
					$status_total_time += 60 * 24 * 30 * $since_start->m;
				}
				
				if ($since_start->d > 0) {
					$caltime .= $since_start->d . ' days ';
					$status_total_time += 60 * 24 * $since_start->d;
				}
				
				if ($since_start->h > 0) {
					$caltime .= $since_start->h . ' hours ';
					$status_total_time += 60 * $since_start->h;
				}
				
				if ($since_start->i > 0) {
					$caltime .= $since_start->i . ' minutes ';
					$status_total_time += $since_start->i;
				}
				
				$caltime .= ' in ' . $roleCall . ' | ';
			}
			
			$clientstatus_info2 = $this->model_notes_clientstatus->getclientstatus ( $data2 ['tag_status_id'] );
			$roleCall2 = $clientstatus_info2 ['name'];
			
			$newdes = $caltime . ' Status changed to | ' . $roleCall2;
		}
		
		if ($data ['comments'] != null && $data ['comments']) {
			$comments = ' | ' . $data ['comments'];
		}
		
		$client_t = "";
		
		if ($data2 ['tags_status_in_change'] == '2') {
			
			if ($tag_info ['tags_status_in'] == 'Admitted') {
				$client_t = "admitted";
			}
			if ($tag_info ['tags_status_in'] == 'Wait listed') {
				$client_t = "wait listed";
			}
			if ($tag_info ['tags_status_in'] == 'Referred') {
				$client_t = "referred";
			}
			if ($tag_info ['tags_status_in'] == 'Closed') {
				$client_t = "closed";
			}
			
			if ($tag_info ['tags_status_in'] == 'Discharge') {
				$client_t = "closed";
				
				$data ['keyword_file'] = DISCHARGE_ICON;
				
				$this->load->model ( 'setting/keywords' );
				$keywordData2 = $this->model_setting_keywords->getkeywordDetaildesc ( $data ['keyword_file'], $facilities_id );
				
				$keyword_name = $keywordData2 ['keyword_name'] . ' | ';
				
				$data ['notes_description'] = $keyword_name . $tag_info ['emp_tag_id'] . ':' . $tag_info ['emp_first_name'] . $comments;
				
				$this->load->model ( 'createtask/createtask' );
				$alldatas = $this->model_createtask_createtask->getalltaskbyid ( $tags_id );
				
				if ($alldatas != NULL && $alldatas != "") {
					foreach ( $alldatas as $alldata ) {
						$result = $this->model_createtask_createtask->getStrikedatadetails ( $alldata ['id'] );
						$taskdeleted_notesid = $this->model_createtask_createtask->insertTaskLists ( $result, $result ['facilityId'], '1' );
						$this->model_createtask_createtask->updatetaskStrike ( $alldata ['id'] );
						$this->model_createtask_createtask->deteteIncomTask ( $result ['facilityId'] );
					}
				}
				
				$this->model_setting_tags->addcurrentTagarchive ( $tags_id );
				$this->load->model ( 'resident/resident' );
				$this->model_resident_resident->updateDischargeTag ( $tags_id, $date_added );
			} else {
				$data ['notes_description'] = $tag_info ['tags_status_in'] . '-' . $tag_info ['emp_tag_id'] . ':' . $tag_info ['emp_first_name'] . ' has been ' . $client_t . ' to ' . $facility_info ['facility'] . ' ' . $comments;
			}
		} else {
			
			$data ['notes_description'] = $tag_info ['emp_tag_id'] . ':' . $tag_info ['emp_first_name'] . ' updated ' . $comments . $newdes;
		}
		
		$data ['status_total_time'] = $status_total_time;
		$data ['date_added'] = $date_added;
		$data ['note_date'] = $date_added;
		$data ['notetime'] = $notetime;
		
		$sql = "UPDATE `" . DB_PREFIX . "notes` SET is_archive = '3',notes_conut='0' where is_tag = '" . ( int ) $tags_id . "' and form_type = '2' and is_archive = '0' ";
		$this->db->query ( $sql );
		
		$this->db->query ( "UPDATE `" . DB_PREFIX . "archive_tags` SET notes_id = '" . ( int ) $notes_id . "' WHERE archive_tags_id = '" . ( int ) $data2 ['archive_tags_id'] . "'" );
		
		$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $facilities_id );
		
		$cdata = array ();
		$cdata ['tag_status_id'] = $data2 ['tag_status_id'];
		$cdata ['tags_id'] = $tags_id;
		$cdata ['facilities_id'] = $facilities_id;
		$cdata ['modify_date'] = $date_added;
		$cdata ['notes_id'] = $notes_id;
		//$cdata ['update_client'] = 1;
		
		
		$this->load->model ( 'resident/resident' );
		$this->model_resident_resident->updateclientnotes ( $cdata );
		
		$this->load->model ( 'facilities/facilities' );
		$facility = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		
		if ($data ['notes_type'] == null && $data ['notes_type'] == "") {
			if ($facility ['is_enable_add_notes_by'] == '1') {
				$sql122 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '4', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
				$this->db->query ( $sql122 );
			}
			if ($facility ['is_enable_add_notes_by'] == '3') {
				$sql13 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '5', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "' ";
				$this->db->query ( $sql13 );
			}
		}
		
		if ($facility ['is_enable_add_notes_by'] == '1') {
			if ($this->session->data ['local_image_dir'] != null && $this->session->data ['local_image_dir'] != "") {
				
				$notes_file = $this->session->data ['local_notes_file'];
				$outputFolder = $this->session->data ['local_image_dir'];
				
				// $facilities_id = $facilities_id;
				
				require_once (DIR_SYSTEM . 'library/awsstorage/s3_config.php');
				$this->load->model ( 'notes/notes' );
				$this->model_notes_notes->updateuserpicture ( $s3file, $notes_id );
				
				if ($this->session->data ['username_confirm'] != null && $this->session->data ['username_confirm'] != "") {
					$this->model_notes_notes->updateuserverified ( '2', $notes_id );
				}
				
				if ($this->session->data ['username_confirm'] == null && $this->session->data ['username_confirm'] == "") {
					$this->model_notes_notes->updateuserverified ( '1', $notes_id );
				}
				
				unlink ( $this->session->data ['local_image_dir'] );
				unset ( $this->session->data ['username_confirm'] );
				unset ( $this->session->data ['local_image_dir'] );
				unset ( $this->session->data ['local_image_url'] );
				unset ( $this->session->data ['local_notes_file'] );
			}
		}
		
		if ($tag_info ['tags_status_in'] == 'Discharge') {
			$this->model_setting_tags->updatecurrentTagarchive ( $tags_id, $notes_id );
		} else {
			$this->db->query ( "UPDATE `" . DB_PREFIX . "notes` SET is_tag = '" . ( int ) $tags_id . "', form_type = '2', notes_conut ='0' WHERE notes_id = '" . ( int ) $notes_id . "'" );
		}
		
		if ($tag_info ['tags_status_in'] != 'Discharge') {
			
			$design_forms = array ();
			$form_description = "";
			$rules_form_description = "";
			
			if ($tag_info ['emp_tag_id'] != null && $tag_info ['emp_tag_id'] != "") {
				$design_forms [0] [0] ['emp_tag_id'] = $tag_info ['emp_tag_id'];
				$form_description .= $tag_info ['emp_tag_id'] . ' ';
				$rules_form_description .= 'emp_tag_id:' . $tag_info ['emp_tag_id'] . ' ';
			}
			
			if ($tag_info ['emp_extid'] != null && $tag_info ['emp_extid'] != "") {
				$design_forms [0] [0] ['' . TAG_EXTID . ''] = $tag_info ['emp_extid'];
				$form_description .= $tag_info ['emp_extid'] . ' ';
				$rules_form_description .= '' . TAG_EXTID . ':' . $tag_info ['emp_extid'] . ' ';
			}
			if ($tag_info ['ssn'] != null && $tag_info ['ssn'] != "") {
				$design_forms [0] [0] ['' . TAG_SSN . ''] = $tag_info ['ssn'];
				$form_description .= $tag_info ['ssn'] . ' ';
				$rules_form_description .= '' . TAG_SSN . ':' . $tag_info ['ssn'] . ' ';
			}
			if ($tag_info ['emp_first_name'] != null && $tag_info ['emp_first_name'] != "") {
				$design_forms [0] [0] ['' . TAG_FNAME . ''] = $tag_info ['emp_first_name'];
				$form_description .= $tag_info ['emp_first_name'] . ' ';
				$rules_form_description .= '' . TAG_FNAME . ':' . $tag_info ['emp_first_name'] . ' ';
			}
			if ($tag_info ['emp_last_name'] != null && $tag_info ['emp_last_name'] != "") {
				$design_forms [0] [0] ['' . TAG_LNAME . ''] = $tag_info ['emp_last_name'];
				$form_description .= $tag_info ['emp_last_name'] . ' ';
				$rules_form_description .= '' . TAG_LNAME . ':' . $tag_info ['emp_last_name'] . ' ';
			}
			if ($tag_info ['dob'] != null && $tag_info ['dob'] != "") {
				if ($tag_info ['dob'] != "0000-00-00") {
					$dob = date ( 'm-d-Y', strtotime ( $tag_info ['dob'] ) );
				} else {
					$dob = '';
				}
				
				$age = $tag_info ['age'];
				
				$design_forms [0] [0] ['' . TAG_DOB . ''] = $dob;
				$design_forms [0] [0] ['' . TAG_AGE . ''] = $age;
				$form_description .= $dob . ' ';
				$form_description .= $age . ' ';
				$rules_form_description .= '' . TAG_DOB . ':' . $dob . ' ';
				$rules_form_description .= '' . TAG_AGE . ':' . $age . ' ';
			}
			if ($tag_info ['gender'] != null && $tag_info ['gender'] != "") {
				
				if ($tag_info ['gender'] == '1') {
					$gender = 'Male';
				}
				if ($tag_info ['gender'] == '2') {
					$gender = 'Female';
				}
				
				$design_forms [0] [0] ['' . TAG_GENDER . ''] = $gender;
				$form_description .= $gender . ' ';
				$rules_form_description .= '' . TAG_GENDER . ':' . $gender . ' ';
			}
			if ($tag_info ['emergency_contact'] != null && $tag_info ['emergency_contact'] != "") {
				$design_forms [0] [0] ['' . TAG_PHONE . ''] = $tag_info ['emergency_contact'];
				$form_description .= $tag_info ['emergency_contact'] . ' ';
				$rules_form_description .= '' . TAG_PHONE . ':' . $tag_info ['emergency_contact'] . ' ';
			}
			if ($tag_info ['location_address'] != null && $tag_info ['location_address'] != "") {
				$design_forms [0] [0] ['' . TAG_ADDRESS . ''] = $tag_info ['location_address'];
				$form_description .= $tag_info ['location_address'] . ' ';
				$rules_form_description .= '' . TAG_ADDRESS . ':' . $tag_info ['location_address'] . ' ';
			}
			if ($tag_info ['address_street2'] != null && $tag_info ['address_street2'] != "") {
				$design_forms [0] [0] ['' . TAG_ADDRESS2 . ''] = $tag_info ['address_street2'];
				$form_description .= $tag_info ['address_street2'] . ' ';
				$rules_form_description .= '' . TAG_ADDRESS2 . ':' . $tag_info ['address_street2'] . ' ';
			}
			/*
			 * if($tag_info['city'] != null && $tag_info['city'] != ""){
			 * $design_forms[0][0]['text_36668004'] = $tag_info['city'];
			 * $form_description .= $tag_info['city'] .' ';
			 * $rules_form_description .= 'text_36668004:'.$tag_info['city'] .' ';
			 * }
			 * if($tag_info['state'] != null && $tag_info['state'] != ""){
			 * $design_forms[0][0]['text_49932949'] = $tag_info['state'];
			 * $form_description .= $tag_info['state'] .' ';
			 * $rules_form_description .= 'text_49932949:'.$tag_info['state'] .' ';
			 * }
			 * if($tag_info['zipcode'] != null && $tag_info['zipcode'] != ""){
			 * $design_forms[0][0]['text_64928499'] = $tag_info['zipcode'];
			 * $form_description .= $tag_info['zipcode'] .' ';
			 * $rules_form_description .= 'text_64928499:'.$tag_info['zipcode'] .' ';
			 * }
			 */
			
			if ($tag_info ['date_of_screening'] != null && $tag_info ['date_of_screening'] != "") {
				
				if ($tag_info ['date_of_screening'] != "0000-00-00") {
					$date_of_screening = date ( 'm-d-Y', strtotime ( $tag_info ['date_of_screening'] ) );
				} else {
					$date_of_screening = '';
				}
				
				$design_forms [0] [0] ['' . TAG_SCREENING . ''] = $date_of_screening;
				$form_description .= $date_of_screening . ' ';
				$rules_form_description .= '' . TAG_SCREENING . ':' . $date_of_screening . ' ';
			}
			
			if ($tag_info ['person_screening'] != null && $tag_info ['person_screening'] != "") {
				$design_forms [0] [0] ['personscreening'] = $tag_info ['person_screening'];
				$form_description .= $tag_info ['person_screening'] . ' ';
				$rules_form_description .= 'personscreening:' . $tag_info ['person_screening'] . ' ';
			}
			
			if ($tag_info ['room'] != null && $tag_info ['room'] != "") {
				$design_forms [0] [0] ['room'] = $tag_info ['room'];
				$form_description .= $tag_info ['room'] . ' ';
				$rules_form_description .= 'room:' . $tag_info ['room'] . ' ';
			}
			
			if ($tag_info ['tagstatus'] != null && $tag_info ['tagstatus'] != "") {
				$design_forms [0] [0] ['tagstatus'] = $tag_info ['tagstatus'];
				$form_description .= $tag_info ['tagstatus'] . ' ';
				$rules_form_description .= 'tagstatus:' . $tag_info ['tagstatus'] . ' ';
			}
			
			if ($tag_info ['med_mental_health'] != null && $tag_info ['med_mental_health'] != "") {
				$design_forms [0] [0] ['medmentalhealth'] = $tag_info ['med_mental_health'];
				$form_description .= $tag_info ['med_mental_health'] . ' ';
				$rules_form_description .= 'medmentalhealth:' . $tag_info ['med_mental_health'] . ' ';
			}
			
			if ($tag_info ['constant_sight'] != null && $tag_info ['constant_sight'] != "") {
				$design_forms [0] [0] ['constantsight'] = $tag_info ['constant_sight'];
				$form_description .= $tag_info ['constant_sight'] . ' ';
				$rules_form_description .= 'constantsight:' . $tag_info ['constant_sight'] . ' ';
			}
			
			if ($tag_info ['alert_info'] != null && $tag_info ['alert_info'] != "") {
				$design_forms [0] [0] ['alertinfo'] = $tag_info ['alert_info'];
				$form_description .= $tag_info ['alert_info'] . ' ';
				$rules_form_description .= 'alertinfo:' . $tag_info ['alert_info'] . ' ';
			}
			
			if ($tag_info ['prescription'] != null && $tag_info ['prescription'] != "") {
				$design_forms [0] [0] ['prescription'] = $tag_info ['prescription'];
				$form_description .= $tag_info ['prescription'] . ' ';
				$rules_form_description .= 'prescription:' . $tag_info ['prescription'] . ' ';
			}
			
			if ($tag_info ['restriction_notes'] != null && $tag_info ['restriction_notes'] != "") {
				$design_forms [0] [0] ['restrictionnotes'] = $tag_info ['restriction_notes'];
				$form_description .= $tag_info ['restriction_notes'] . ' ';
				$rules_form_description .= 'restrictionnotes:' . $tag_info ['restriction_notes'] . ' ';
			}
			
			// if($data['notes_pin'] != null && $data['notes_pin'] != ""){
			$notes_pin = $data ['notes_pin'];
			// $signature = "";
			// }else{
			if ($data ['imgOutput']) {
				$signature = $data ['imgOutput'];
			} else {
				$signature = $data ['signature'];
			}
			// $notes_pin = '';
			// }
			$user_id = $data ['user_id'];
			$notes_type = $data ['notes_type'];
			
			$sql = "UPDATE " . DB_PREFIX . "forms SET design_forms = '" . $this->db->escape ( serialize ( $design_forms ) ) . "',form_description = '" . $this->db->escape ( $form_description ) . "',rules_form_description = '" . $this->db->escape ( $rules_form_description ) . "', upload_file = '" . $this->db->escape ( $tag_info ['upload_file'] ) . "',user_id = '" . $this->db->escape ( $user_id ) . "', signature = '" . $this->db->escape ( $signature ) . "', notes_pin = '" . $this->db->escape ( $notes_pin ) . "', notes_type = '" . $notes_type . "', date_updated = '" . $date_added . "' where forms_id = '" . $forms_id . "' and custom_form_type = '" . CUSTOME_INTAKEID . "' and is_discharge = '0' and is_final = '0' ";
			$this->db->query ( $sql );
			
			/*
			 * $fsql = "UPDATE `" . DB_PREFIX . "tags_forms` SET design_forms = '" . $this->db->escape(serialize($design_forms)) . "', form_description = '" . $this->db->escape($form_description) . "',rules_form_description = '" . $this->db->escape($rules_form_description) . "',user_id = '".$this->db->escape($user_id)."', signature = '".$this->db->escape($signature)."', notes_pin = '".$this->db->escape($notes_pin)."', notes_type = '".$notes_type."', date_updated = '" . $date_added . "', upload_file = '".$tag_info['upload_file']."', type = '3', form_signature = '".$formdata['form_signature']."' where tags_id = '" . $tags_id . "' ";
			 *
			 * $this->db->query($fsql);
			 *
			 */
		}
		
		$this->load->model ( 'activity/activity' );
		$adata ['notes_id'] = $notes_id;
		$adata ['tags_id'] = $tags_id;
		$adata ['phone_device_id'] = $data ['phone_device_id'];
		$adata ['is_android'] = $data ['is_android'];
		$adata ['user_id'] = $data ['user_id'];
		$adata ['archive_tags_id'] = $data2 ['archive_tags_id'];
		$adata ['facilities_id'] = $facilities_id;
		$adata ['comments'] = $comments;
		$adata ['date_added'] = $date_added;
		$this->model_activity_activity->addActivitySave ( 'updateclientsign', $adata, 'query' );
		
		return $notes_id;
	}
	public function updateActiveStatusTag($tags_id) {
		$this->db->query ( "UPDATE `" . DB_PREFIX . "tags` SET status = '1' WHERE tags_id = '" . ( int ) $tags_id . "'" );
		
		$this->load->model ( 'activity/activity' );
		$data ['tags_id'] = $tags_id;
		$data ['status'] = '1';
		$this->model_activity_activity->addActivitySave ( 'updateActiveStatusTag', $data, 'query' );
	}
	public function updateInactiveStatusTag($tags_id) {
		$this->db->query ( "UPDATE `" . DB_PREFIX . "tags` SET status = '0' WHERE tags_id = '" . ( int ) $tags_id . "'" );
		
		$this->load->model ( 'activity/activity' );
		$data ['tags_id'] = $tags_id;
		$data ['status'] = '0';
		$this->model_activity_activity->addActivitySave ( 'updateInactiveStatusTag', $data, 'query' );
	}
	public function updatePublicStatusTag($tags_id) {
		$this->db->query ( "UPDATE `" . DB_PREFIX . "tags` SET privacy = '1' WHERE tags_id = '" . ( int ) $tags_id . "'" );
		
		$this->load->model ( 'activity/activity' );
		$data ['tags_id'] = $tags_id;
		$data ['privacy'] = '1';
		$this->model_activity_activity->addActivitySave ( 'updatePublicStatusTag', $data, 'query' );
	}
	public function updatePrivateStatusTag($tags_id) {
		$this->db->query ( "UPDATE `" . DB_PREFIX . "tags` SET privacy = '2' WHERE tags_id = '" . ( int ) $tags_id . "'" );
		
		$this->load->model ( 'activity/activity' );
		$data ['tags_id'] = $tags_id;
		$data ['privacy'] = '2';
		$this->model_activity_activity->addActivitySave ( 'updatePrivateStatusTag', $data, 'query' );
	}
	public function updateAllPrivacyTag($tags_id) {
		$this->db->query ( "UPDATE `" . DB_PREFIX . "tags` SET privacy = '0' WHERE tags_id = '" . ( int ) $tags_id . "'" );
		
		$this->load->model ( 'activity/activity' );
		$data ['tags_id'] = $tags_id;
		$data ['privacy'] = '0';
		$this->model_activity_activity->addActivitySave ( 'updateAllPrivacyTag', $data, 'query' );
	}
	public function deleteTags($tags_id) {
		$this->db->query ( "DELETE FROM `" . DB_PREFIX . "tags` WHERE tags_id = '" . ( int ) $tags_id . "'" );
		
		$this->db->query ( "DELETE FROM `" . DB_PREFIX . "medication` WHERE tags_id = '" . ( int ) $tags_id . "'" );
	}
	
	
	
	public function getTag($tags_id) {
		$query = $this->db->query ( "SELECT tags_id,emp_tag_id,emp_first_name,emp_middle_name,emp_last_name,privacy,date_added,dob,locations_id,facilities_id,upload_file,tags_pin,gender,discharge,age,role_call,location_address,latitude,longitude,emp_extid,address_street2,person_screening,date_of_screening,ssn,state,city,zipcode,room,restriction_notes,prescription,alert_info,constant_sight,med_mental_health,tagstatus,forms_id,tags_forms_id,discharge_date,stickynote,customlistvalues_id,assign_to,assign_to_type,user_role_assign_ids,refill_percentage,tags_status,tags_status_in,referred_facility,emergency_contact,reminder_time,reminder_date,upload_file_thumb,medication_inout,notes_id,ccn,facility_inout,facility_move_id,movement_room,is_movement,classification_id,race,bed_number,fixed_status_id,tag_status_ids,comments FROM `" . DB_PREFIX . "tags` WHERE tags_id = '" . ( int ) $tags_id . "'" );
		
		
		
		if ($query->num_rows > 0) {
			// return $query->row;
			$result = $query->row;
			$keys = array_keys ( $result );
			foreach ( $keys as $key ) {
				$result [$key] = $this->formattedValue ( $result [$key], $key );
			}
			return $result;
		} else {
			$query = $this->db->query ( "SELECT client_id as tags_id, client_name as emp_tag_id  FROM `" . DB_PREFIX . "client` WHERE client_id = '" . ( int ) $tags_id . "'" );
			return $query->row;
		}
	}
	public function getTaga($tags_id, $is_archive, $notes_id) {
		if ($is_archive == '3') {
			$sql = "SELECT tags_id,emp_tag_id,status,emp_first_name,emp_middle_name,emp_last_name,privacy,sort_order,date_added,dob,locations_id,facilities_id,upload_file,tags_pin,gender,discharge,age,role_call,location_address,latitude,longitude,emp_extid,address_street2,person_screening,date_of_screening,ssn,state,city,zipcode,room,restriction_notes,prescription,alert_info,constant_sight,med_mental_health,tagstatus,forms_id,tags_forms_id,discharge_date,stickynote,customlistvalues_id,tags_status,tags_status_in,referred_facility,notes_id,is_archive,emergency_contact,reminder_time,reminder_date,upload_file_thumb,medication_inout,tags_notes_id,ccn,race,bed_number FROM `" . DB_PREFIX . "archive_tags` WHERE tags_id = '" . ( int ) $tags_id . "' and notes_id = '" . $notes_id . "' ";
			
			$query = $this->db->query ( $sql );
		} else {
			$query = $this->db->query ( "SELECT tags_id,emp_tag_id,emp_first_name,emp_middle_name,emp_last_name,privacy,date_added,dob,locations_id,facilities_id,upload_file,tags_pin,gender,discharge,age,role_call,location_address,latitude,longitude,emp_extid,address_street2,person_screening,date_of_screening,ssn,state,city,zipcode,room,restriction_notes,prescription,alert_info,constant_sight,med_mental_health,tagstatus,forms_id,tags_forms_id,discharge_date,stickynote,customlistvalues_id,tags_status,tags_status_in,referred_facility,emergency_contact,reminder_time,reminder_date,upload_file_thumb,medication_inout,notes_id,ccn,movement_room,is_movement,classification_id,race,bed_number FROM `" . DB_PREFIX . "tags` WHERE tags_id = '" . ( int ) $tags_id . "'" );
		}
		
		$result = $query->row;
		$keys = array_keys ( $result );
		foreach ( $keys as $key ) {
			$result [$key] = $this->formattedValue ( $result [$key], $key );
		}
		// return $query->row;
		return $result;
	}
	public function getTagbyEMPID($emp_tag_id) {
		$sql = "SELECT tags_id,emp_tag_id,emp_first_name,emp_middle_name,emp_last_name,privacy,date_added,dob,locations_id,facilities_id,upload_file,tags_pin,gender,discharge,age,role_call,location_address,latitude,longitude,emp_extid,address_street2,person_screening,date_of_screening,ssn,state,city,zipcode,room,restriction_notes,prescription,alert_info,constant_sight,med_mental_health,tagstatus,forms_id,tags_forms_id,discharge_date,stickynote,customlistvalues_id,tags_status,tags_status_in,referred_facility,emergency_contact,reminder_time,reminder_date,upload_file_thumb ,medication_inout,notes_id,ccn,movement_room,is_movement,classification_id,race,bed_number FROM `" . DB_PREFIX . "tags` WHERE emp_tag_id = '" . $emp_tag_id . "'";
		$query = $this->db->query ( $sql );
		
		$result = $query->row;
		$keys = array_keys ( $result );
		foreach ( $keys as $key ) {
			$result [$key] = $this->formattedValue ( $result [$key], $key );
		}
		return $result;
		// return $query->row;
	}
	public function getTags($data = array()) {
		//$sql = "SELECT tags_id,emp_tag_id,emp_first_name,emp_middle_name,emp_last_name,privacy,date_added,dob,locations_id,facilities_id,upload_file,tags_pin,gender,discharge,age,role_call,location_address,latitude,longitude,emp_extid,address_street2,person_screening,date_of_screening,ssn,state,city,zipcode,room,restriction_notes,prescription,alert_info,constant_sight,med_mental_health,tagstatus,forms_id,tags_forms_id,discharge_date,stickynote,customlistvalues_id,tags_status,tags_status_in,referred_facility,emergency_contact,reminder_time,reminder_date,upload_file_thumb,medication_inout,notes_id,ccn,facility_move_id,facility_inout,movement_room,is_movement,classification_id FROM `" . DB_PREFIX . "tags` ";
		
		
		$sql = "SELECT distinct t.*, te.enroll_image, ts.name,ts.tag_status_id,ts.type,ts.rule_action_content, ts.color_code, ts.image, ts.facility_type, ts.is_facility, ts.status_type, ts.out_from_cell, l.location_name, f.facility,c.customlistvalues_name FROM `dg_tags` t

			LEFT JOIN dg_tags_enroll te 
				ON te.tags_id = t.tags_id and substr(te.enroll_image, 1,4)='http'
				
			LEFT JOIN dg_tag_status ts 
				ON ts.tag_status_id = t.role_call 

			
				
			LEFT JOIN dg_locations l 
				ON l.locations_id = t.room

			LEFT JOIN dg_facilities f 
				ON f.facilities_id = t.facilities_id 
				
			LEFT JOIN dg_tags_assign_team dat 
				ON dat.tags_id = t.tags_id 
				
			LEFT JOIN dg_customlistvalues c 
				ON c.customlistvalues_id = t.customlistvalues_id ";
		
		$sql .= 'where 1 = 1 ';
		
		
		
		if ($data ['is_master'] == '1') {
			$this->load->model ( 'facilities/facilities' );
			$facility_info = $this->model_facilities_facilities->getfacilities ( $data ['facilities_id'] );
			$ddss = array ();
			
			if ($data ['app_user_date'] != '' && $data ['current_date_user'] != '') {
				$sql2 = " and `modify_date` BETWEEN  '" . $data ['app_user_date'] . "' AND  '" . $data ['current_date_user'] . " 23:59:59' ";
				
				$sql1l = "SELECT old_facilities_id,facilities_id FROM `dg_tags` where 1 = 1 and old_facilities_id !=0 and discharge = '0' and status = '1' " . $sql2 . " and facilities_id = '".$data ['facilities_id']."' ";
				$queryl = $this->db->query ( $sql1l );
				foreach ( $queryl->rows as $fid ) {
					$ddss [] = $fid ['old_facilities_id'];
					$ddss [] = $fid ['facilities_id'];
				}
			}
			
			if ($facility_info ['client_facilities_ids'] != null && $facility_info ['client_facilities_ids'] != "") {
				
				$ddss [] = $facility_info ['client_facilities_ids'];
				
				$ddss [] = $data ['facilities_id'];
				
				if ($data ['is_submaster'] == '1') {
					$sssssddsg = explode ( ",", $facility_info ['client_facilities_ids'] );
					$abdcg = array_unique ( $sssssddsg );
					$cids = array ();
					foreach ( $abdcg as $fid ) {
						$cids [] = $fid;
					}
					$abdcgs = array_unique ( $cids );
					foreach ( $abdcgs as $fid2 ) {
						$facilityinfo = $this->model_facilities_facilities->getfacilities ( $fid2 );
						if ($facilityinfo ['client_facilities_ids'] != null && $facilityinfo ['client_facilities_ids'] != "") {
							$ddss [] = $facilityinfo ['client_facilities_ids'];
						}
					}
				}
				
				$ddss = array_unique ( $ddss );
				$sssssdd = implode ( ",", $ddss );
				
				if ($data ['is_client_screen'] != null && $data ['is_client_screen'] != "") {
					
					if ($data ['enable_facilityinout'] == '1') {
						if ($data ['updatedtagsids'] != null && $data ['updatedtagsids'] != "") {
							$sql .= " and (t.facilities_id = '" . $data ['facilities_id'] . "' OR t.tags_id in (" . $data ['updatedtagsids'] . ") OR t.facility_move_id = '0' OR t.facility_move_id = '" . $data ['facilities_id'] . "' )";
						} else {
							$sql .= " and ( t.facilities_id in (" . $sssssdd . ") ";
							$sql .= " OR t.facility_move_id = '" . $data ['facilities_id'] . "' ) ";
						}
					} else {
						$sql .= " and ( t.facilities_id in (" . $sssssdd . ") ";
						$sql .= " OR t.facility_move_id = '" . $data ['facilities_id'] . "' ) ";
					}
				} else {
					$sql .= " and t.facilities_id in (" . $sssssdd . ") ";
				}
				
				$faculities_ids = $sssssdd;
			} else {
				if ($data ['facilities_id'] != null && $data ['facilities_id'] != "") {
					if ($data ['is_client_screen'] != null && $data ['is_client_screen'] != "") {
						
						if ($data ['enable_facilityinout'] == '1') {
							if ($data ['updatedtagsids'] != null && $data ['updatedtagsids'] != "") {
								$sql .= " and (t.facilities_id = '" . $data ['facilities_id'] . "' OR t.tags_id in (" . $data ['updatedtagsids'] . ") OR t.facility_move_id = '0' OR t.facility_move_id = '" . $data ['facilities_id'] . "' )";
							} else {
								$sql .= " and ( t.facilities_id = '" . $data ['facilities_id'] . "'";
								$sql .= " OR t.facility_move_id = '" . $data ['facilities_id'] . "' ) ";
							}
						} else {
							$sql .= " and ( t.facilities_id = '" . $data ['facilities_id'] . "'";
							$sql .= " OR t.facility_move_id = '" . $data ['facilities_id'] . "' ) ";
						}
					} else {
						if ($data ['enable_facilityinout'] == '1') {
							if ($data ['updatedtagsids'] != null && $data ['updatedtagsids'] != "") {
								$sql .= " and (t.facilities_id = '" . $data ['facilities_id'] . "' OR t.tags_id in (" . $data ['updatedtagsids'] . ") OR t.facility_move_id = '0' OR t.facility_move_id = '" . $data ['facilities_id'] . "' )";
							} else {
								$sql .= " and t.facilities_id = '" . $data ['facilities_id'] . "'";
							}
						} else {
							$sql .= " and t.facilities_id = '" . $data ['facilities_id'] . "'";
						}
					}
					// $sql.= " and facilities_id = '".$data['facilities_id']."'";
					$n_facilities_id = $data ['facilities_id'];
				}
			}
		} else {
			if ($data ['facilities_id'] != null && $data ['facilities_id'] != "") {
				
				if ($data ['is_client_screen'] != null && $data ['is_client_screen'] != "") {
					
					if ($data ['enable_facilityinout'] == '1') {
						if ($data ['updatedtagsids'] != null && $data ['updatedtagsids'] != "") {
							$sql .= " and (t.facilities_id = '" . $data ['facilities_id'] . "' OR t.tags_id in (" . $data ['updatedtagsids'] . ") OR t.facility_move_id = '0' )";
						} else {
							$sql .= " and ( t.facilities_id = '" . $data ['facilities_id'] . "'";
							$sql .= " OR t.facility_move_id = '" . $data ['facilities_id'] . "' ) ";
						}
					} else {
						$sql .= " and ( t.facilities_id = '" . $data ['facilities_id'] . "'";
						$sql .= " OR t.facility_move_id = '" . $data ['facilities_id'] . "' ) ";
					}
				} else {
					if ($data ['enable_facilityinout'] == '1') {
						if ($data ['updatedtagsids'] != null && $data ['updatedtagsids'] != "") {
							$sql .= " and (t.facilities_id = '" . $data ['facilities_id'] . "' OR t.tags_id in (" . $data ['updatedtagsids'] . ") OR t.facility_move_id = '0' )";
						} else {
							$sql .= " and t.facilities_id = '" . $data ['facilities_id'] . "'";
						}
					} else {
						$sql .= " and t.facilities_id = '" . $data ['facilities_id'] . "'";
					}
				}
			}
		}
		
		if ($data ['facility_inout'] == "1") {
			$sql .= " and t.facility_inout = '0'";
		}
		
		if ($data ['facility_inout'] == "2") {
			$sql .= " and t.facility_inout = '1'";
		}
		
		if ($data ['app_user_date'] != '' && $data ['current_date_user'] != '') {
			$sql .= " and t.`modify_date` BETWEEN  '" . $data ['app_user_date'] . "' AND  '" . $data ['current_date_user'] . " 23:59:59' ";
		}
		
		if ($data ['facilities'] != null && $data ['facilities'] != "") {
			$sql .= " and t.facilities_id in (" . $data ['facilities'] . ") ";
		}
		if ($data ['customer_key'] != null && $data ['customer_key'] != "") {
			$sql .= " and t.customer_key = '" . $data ['customer_key'] . "' ";
		}
		
		
		if ($data ['user_id'] != null && $data ['user_id'] != "") {
			$sql .= " and ( dat.userids = '" . $data ['user_id'] . "' or dat.user_roles = '" . $data ['user_group_id'] . "') ";
			
		}
		
		if ($data ['room_id'] != null && $data ['room_id'] != "") {
			$sql .= " and t.room = '" . $data ['room_id'] . "'";
			$n_room_id = $data ['room_id'];
		}
		
		if ($data ['discharge'] == "1") {
			$sql .= " and t.discharge = '0'";
			$n_discharge = '1';
		}
		if ($data ['medication_inout'] == "1") {
			$sql .= " and t.medication_inout = '1'";
		}
		
		if ($data ['medication_inout'] == "2") {
			$sql .= " and t.medication_inout = '0'";
		}
		
		if ($data ['discharge'] == "2") {
			$sql .= " and t.discharge = '1'";
			$n_discharge = '2';
		}
		
		if ($data ['is_movement'] == "1") {
			//$sql .= " and t.is_movement = '1'";
			if($data ['movecount'] != null && $data ['movecount'] != ""){
				$sql .= " and (is_movement = '1' or role_call IN (" . $data ['movecount'] . ") ) ";
			}else{
				$sql .= " and is_movement = '1'";
			}
		}
		
		/*if ($data ['client_status'] == "3") {
			//$sql .= " and t.is_movement = '1'";
			if($data ['movecount'] != null && $data ['movecount'] != ""){
				$sql .= " and (is_movement = '1' or role_call IN (" . $data ['movecount'] . ") ) ";
			}else{
				$sql .= " and is_movement = '1'";
			}
		}*/
		
		if ($data ['tags_exits'] == "1") {
			if ($data ['exits_emp_extid'] != null && $data ['exits_emp_extid'] != "") {
				$sql .= " and t.emp_extid = '" . $data ['exits_emp_extid'] . "'";
			}
			
			if ($data ['exits_ssn'] != null && $data ['exits_ssn'] != "") {
				$sql .= " and t.ssn = '" . $data ['exits_ssn'] . "'";
			}
			
			if (($data ['exits_ssn'] != null && $data ['exits_ssn'] != "") || ($data ['exits_emp_extid'] != null && $data ['exits_emp_extid'] != "")) {
				$sql .= " or ( LOWER(t.emp_first_name) LIKE '%" . strtolower ( $data ['exits_emp_first_name'] ) . "%' and LOWER(t.emp_last_name) LIKE '%" . strtolower ( $data ['exits_emp_last_name'] ) . "%' ) ";
			} else if ($data ['exits_emp_first_name'] != null && $data ['exits_emp_first_name'] != "") {
				$sql .= " and ( LOWER(t.emp_first_name) LIKE '%" . strtolower ( $data ['exits_emp_first_name'] ) . "%' and LOWER(t.emp_last_name) LIKE '%" . strtolower ( $data ['exits_emp_last_name'] ) . "%' ) ";
			}
			
			if ($data ['exits_dob'] != null && $data ['exits_dob'] != "") {
				// $sql .= " or ( dob = '".$data['exits_dob']."' or LOWER(emp_first_name) LIKE '%".strtolower($data['exits_emp_first_name'])."%' or LOWER(emp_last_name) LIKE '%".strtolower($data['exits_emp_last_name'])."%' ) ";
			}
		}
		
		if ($data ['wait_list'] != null && $data ['wait_list'] != "") {
			$sql .= " and t.tags_status_in = 'Wait listed' ";
			$n_tags_status_in = 'Wait listed';
		} else {
			if ($data ['all_record'] == "1") {
				$sql .= " and t.tags_status_in = 'Admitted' ";
				$n_tags_status_in = 'Admitted';
			}
		}
		
		if (! empty ( $data ['form_tagstatusa'] )) {
			$form_tagstatusa = implode ( '\',\'', $data ['form_tagstatusa'] );
			$sql .= " and t.tags_status_in in ('" . $form_tagstatusa . "') ";
		}
		
		if ($data ['emp_tag_id_3'] != null && $data ['emp_tag_id_3'] != "") {
			$sql .= " and ( LOWER(t.emp_tag_id) like '%" . strtolower ( $data ['emp_tag_id_3'] ) . "%' or LOWER(t.emp_first_name) LIKE '%" . strtolower ( $data ['emp_tag_id_3'] ) . "%' or LOWER(t.emp_last_name) LIKE '%" . strtolower ( $data ['emp_tag_id_3'] ) . "%' or LOWER(t.ssn) LIKE '%" . strtolower ( $data ['emp_tag_id_3'] ) . "%' or LOWER(t.emp_extid) LIKE '%" . strtolower ( $data ['emp_tag_id_3'] ) . "%' ) ";
		}
		
		if ($data ['emp_tag_id_2'] != null && $data ['emp_tag_id_2'] != "") {
			
			$searchs = explode(" ",$data ['emp_tag_id_2']);
			$i = 0;
			$sql .= " and ( ";
			foreach($searchs as $search1){
				if($i != '0'){
					$sql .= ' or ';
				}
				$sql .= " LOWER(t.tag_data like '%" . strtolower ( $search1 ) . "%' ) ";
				$i++;
			}
			$sql .= " ) ";
			
			$n_emp_tag_id_2 = $data ['emp_tag_id_2'];
		}
		
		if ($data ['emp_tag_id_all'] != null && $data ['emp_tag_id_all'] != "") {
			
			$searchs = explode(" ",$data ['emp_tag_id_all']);
			$i = 0;
			$sql .= " and ( ";
			foreach($searchs as $search1){
				if($i != '0'){
					$sql .= ' or ';
				}
				$sql .= " LOWER(t.tag_data like '%" . strtolower ( $search1 ) . "%' ) ";
				$i++;
			}
			$sql .= " ) ";
			
			$n_emp_tag_id_2 = $data ['emp_tag_id_all'];
		}
		
		/*if ($data ['emp_tag_id_all'] != null && $data ['emp_tag_id_all'] != "") {
			$sql .= " and LOWER(t.tag_data like '%" . strtolower ( $data ['emp_tag_id_all'] ) . "%' ) ";
			$emp_tag_id_all = $data ['emp_tag_id_all'];
		}
		*/
		if ($data ['searchdate'] != null && $data ['searchdate'] != "") {
			
			$startDate = date ( 'Y-m-d', strtotime ( $data ['searchdate'] ) );
			$endDate = date ( 'Y-m-d', strtotime ( $data ['searchdate'] ) );
			
			$sql .= " and (t.`date_added` BETWEEN  '" . $startDate . " 00:00:00' AND  '" . $endDate . " 23:59:59')";
			
			$n_note_date_from = $startDate . " 00:00:00";
			$n_note_date_to = $startDate . " 23:59:59";
		}
		
		if ($data ['search_tags_tag_id'] != null && $data ['search_tags_tag_id'] != "") {
			$sql .= " and t.tags_id = '" . $data ['search_tags_tag_id'] . "'";
			$n_gender = $data ['search_tags_tag_id'];
		}
		
		if ($data ['gender'] != null && $data ['gender'] != "") {
			$sql .= " and t.gender = '" . $data ['gender'] . "'";
			$n_gender = $data ['gender'];
		}
		
		/*
		 * if($data['role_call'] != null && $data['role_call'] != ""){
		 * $sql.= " and role_call = '".$data['role_call']."'";
		 * $n_role_call = $data['role_call'];
		 *
		 * }
		 */
		
		if ($data ['rolecalls'] != null && $data ['rolecalls'] != "") {
			

			if($data ['search_filter'] == 1){
				$sql .= " and t.role_call IN (" . $data ['rolecalls'] . ") and t.fixed_status_id = 0 ";
			}elseif($data ['search_filter'] == 2){
				$sql .= " and (t.role_call IN (" . $data ['rolecalls'] . ") or t.fixed_status_id IN (" . $data ['rolecalls'] . ") ) ";
			}else{
				$sql .= " and t.role_call IN (" . $data ['rolecalls'] . ") ";
			}
			
			
		}

		if ($data ['filters'] != null && $data ['filters'] != "") {
			$sql .= " and t.fixed_status_id IN (" . $data ['filters'] . ")";
			
		}
		
		if ($data ['gender2'] != null && $data ['gender2'] != "") {
			$sql .= " and t.gender = '" . $data ['gender2'] . "'";
			$n_gender2 = $data ['gender2'];
		}
		
		if ($data ['emp_tag_id'] != null && $data ['emp_tag_id'] != "") {
			$sql .= " and t.emp_tag_id like '%" . $data ['emp_tag_id'] . "%'";
			$n_emp_tag_id = $data ['emp_tag_id'];
		}
		
		if ($data ['emp_first_name'] != null && $data ['emp_first_name'] != "") {
			$sql .= " and t.emp_first_name like '%" . $data ['emp_first_name'] . "%'";
			$n_emp_first_name = $data ['emp_first_name'];
		}
		if ($data ['emp_last_name'] != null && $data ['emp_last_name'] != "") {
			$sql .= " and t.emp_last_name like '%" . $data ['emp_last_name'] . "%'";
			$n_emp_last_name = $data ['emp_last_name'];
		}
		
		if ($data ['privacy'] != null && $data ['privacy'] != "") {
			$sql .= " and t.privacy = '" . $data ['privacy'] . "'";
			$n_privacy = $data ['privacy'];
		}
		if ($data ['status'] != null && $data ['status'] != "") {
			$sql .= " and t.status = '" . $data ['status'] . "'";
			$n_status = $data ['status'];
		}
		if ($data ['tagids'] != null && $data ['tagids'] != "") {
			$form_tagids = implode ( '\',\'', $data ['tagids'] );
			$sql .= " or t.tags_id in ('" . $form_tagids . "') ";
		}

		if($data['q'] != ""){
			$sql.= " and ( emp_last_name LIKE '%".$data['q']."%' OR emp_first_name LIKE '%".$data['q']."%' )";
		}



		$sort_data = array (
				'emp_tag_id',
				'status',
				'emp_first_name',
				'emp_last_name',
				'privacy',
				'sort_order',
				'modify_date',
				'room',
				'l.location_name',
				'date_added' 
		);
		
		
	
		
		if (isset ( $data ['sort'] ) && in_array ( $data ['sort'], $sort_data )) {
			$sql .= " ORDER BY " . $data ['sort'];
			$n_orderby = $data ['sort'];
		} else {
			$sql .= " ORDER BY t.sort_order";
			$n_orderby = " t.sort_order ";
		}
		
		if (isset ( $data ['order'] ) && ($data ['order'] == 'DESC')) {
			$sql .= " DESC";
			$n_orderby .= " DESC";
		} else {
			$sql .= " ASC";
			$n_orderby .= " ASC";
		}
		
		
		if (isset ( $data ['start'] ) || isset ( $data ['limit'] )) {
			if ($data ['start'] < 0) {
				$data ['start'] = 0;
			}
			
			if ($data ['limit'] < 1) {
				$data ['limit'] = 20;
			}
			
			$sql .= " LIMIT " . ( int ) $data ['start'] . "," . ( int ) $data ['limit'];
			
			$n_start = $data ['start'];
			$n_limit = $data ['limit'];
		}
		
		// $sql = " CALL gettags('".$n_facilities_id."', '".$n_discharge."', '".$n_tags_status_in."', '".$n_emp_tag_id_2."','".$n_note_date_from."','".$n_note_date_to."','".$n_gender."','".$n_gender2."','".$n_emp_tag_id."','".$n_emp_first_name."','".$n_emp_last_name."','".$n_privacy."','".$n_status."','".$n_orderby."','".$n_start."','".$n_limit."')";
		
		//echo "<hr>";
		//echo $sql;
		//echo "<hr>";
		
		$query = $this->db->query ( $sql );
		
		 //var_dump($query);
		// die;
		
		// return $query->rows;
		$results = $query->rows;
		// var_dump($results);die;
		
		foreach ( $results as $result ) {
			$keys = array_keys ( $result );
			foreach ( $keys as $key ) {
				$result [$key] = $this->formattedValue ( $result [$key], $key );
			}
			$resultsArr [] = $result;
		}
		return $resultsArr;
	}
	public function getTotalTags($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "tags` ";
		$sql .= 'where 1 = 1 ';
		
		if ($data ['wait_list'] != null && $data ['wait_list'] != "") {
			$sql .= " and tags_status_in = 'Wait listed' ";
		} else {
			if ($data ['all_record'] == "1") {
				$sql .= " and tags_status_in = 'Admitted' ";
			}
		}
		
		if ($data ['discharge'] == "1") {
			$sql .= " and discharge = '0'";
		}
		
		if ($data ['is_movement'] == "1") {
			
			if($data ['movecount'] != null && $data ['movecount'] != ""){
				$sql .= " and (is_movement = '1' or role_call IN (" . $data ['movecount'] . ") ) ";
			}else{
				$sql .= " and is_movement = '1'";
			}
		}
		
		if ($data ['client_status'] == "3") {
			//$sql .= " and is_movement = '1'";
			
			if($data ['movecount'] != null && $data ['movecount'] != ""){
				$sql .= " and (is_movement = '1' or role_call IN (" . $data ['movecount'] . ") ) ";
			}else{
				$sql .= " and is_movement = '1'";
			}
		}
		
		if (! empty ( $data ['form_tagstatusa'] )) {
			$form_tagstatusa = implode ( '\',\'', $data ['form_tagstatusa'] );
			$sql .= " and tags_status_in in ('" . $form_tagstatusa . "') ";
		}
		
		if ($data ['discharge'] == "2") {
			// $sql.= " and discharge = '1'";
			
			if ($data ['searchdate_2'] != null && $data ['searchdate_2'] != "") {
				$startDate = date ( 'Y-m-d', strtotime ( $data ['searchdate_2'] ) );
				$endDate = date ( 'Y-m-d', strtotime ( $data ['searchdate_2'] ) );
				
				$sql .= " and (`discharge_date` BETWEEN  '" . $startDate . " 00:00:00' AND  '" . $endDate . " 23:59:59')";
			}
			
			if (($data ['dnote_date_from'] != null && $data ['dnote_date_from'] != "") && ($data ['dnote_date_to'] != null && $data ['dnote_date_to'] != "")) {
				$startDate = date ( 'Y-m-d', strtotime ( $data ['dnote_date_from'] ) );
				$endDate = date ( 'Y-m-d', strtotime ( $data ['dnote_date_to'] ) );
				$sql .= " and `discharge_date` BETWEEN  '" . $startDate . " 00:00:00 ' AND  '" . $endDate . " 23:59:59' ";
			}
		}
		
		if ($data ['emp_tag_id_3'] != null && $data ['emp_tag_id_3'] != "") {
			$sql .= " and ( LOWER(emp_tag_id) like '%" . strtolower ( $data ['emp_tag_id_3'] ) . "%' or LOWER(emp_first_name) LIKE '%" . strtolower ( $data ['emp_tag_id_3'] ) . "%' or LOWER(emp_last_name) LIKE '%" . strtolower ( $data ['emp_tag_id_3'] ) . "%' or LOWER(emp_extid) LIKE '%" . strtolower ( $data ['emp_tag_id_3'] ) . "%' or LOWER(ssn) LIKE '%" . strtolower ( $data ['emp_tag_id_3'] ) . "%' ) ";
		}
		
		if ($data ['emp_tag_id_2'] != null && $data ['emp_tag_id_2'] != "") {
			
			$searchs = explode(" ",$data ['emp_tag_id_2']);
			$i = 0;
			$sql .= " and ( ";
			foreach($searchs as $search1){
				if($i != '0'){
					$sql .= ' or ';
				}
				$sql .= " LOWER(tag_data like '%" . strtolower ( $search1 ) . "%' ) ";
				$i++;
			}
			$sql .= " ) ";
			
			$n_emp_tag_id_2 = $data ['emp_tag_id_2'];
		}
		
		/*if ($data ['emp_tag_id_2'] != null && $data ['emp_tag_id_2'] != "") {
			$sql .= " and ( LOWER(emp_tag_id) like '%" . strtolower ( $data ['emp_tag_id_2'] ) . "%' or LOWER(emp_first_name) LIKE '%" . strtolower ( $data ['emp_tag_id_2'] ) . "%' or LOWER(emp_last_name) LIKE '%" . strtolower ( $data ['emp_tag_id_2'] ) . "%' ) ";
		}
		
		if ($data ['emp_tag_id_all'] != null && $data ['emp_tag_id_all'] != "") {
			//$sql .= " and LOWER(tag_data like '%" . strtolower ( $data ['emp_tag_id_all'] ) . "%' ) ";
			
			$emp_tag_id_all = $data ['emp_tag_id_all'];
		}*/
		
		if ($data ['emp_tag_id_all'] != null && $data ['emp_tag_id_all'] != "") {
			
			$searchs = explode(" ",$data ['emp_tag_id_all']);
			$i = 0;
			$sql .= " and ( ";
			foreach($searchs as $search1){
				if($i != '0'){
					$sql .= ' or ';
				}
				$sql .= " LOWER(tag_data like '%" . strtolower ( $search1 ) . "%' ) ";
				$i++;
			}
			$sql .= " ) ";
			
			$n_emp_tag_id_2 = $data ['emp_tag_id_all'];
		}
		
		if ($data ['searchdate'] != null && $data ['searchdate'] != "") {
			
			$startDate = date ( 'Y-m-d', strtotime ( $data ['searchdate'] ) );
			$endDate = date ( 'Y-m-d', strtotime ( $data ['searchdate'] ) );
			
			$sql .= " and (`date_added` BETWEEN  '" . $startDate . " 00:00:00' AND  '" . $endDate . " 23:59:59')";
		}
		
		if ($data ['is_master'] == '1') {
			$this->load->model ( 'facilities/facilities' );
			$facility_info = $this->model_facilities_facilities->getfacilities ( $data ['facilities_id'] );
			$ddss = array ();
			
			if ($data ['app_user_date'] != '' && $data ['current_date_user'] != '') {
				$sql2 = " and `modify_date` BETWEEN  '" . $data ['app_user_date'] . "' AND  '" . $data ['current_date_user'] . " 23:59:59' ";
				
				$sql1l = "SELECT old_facilities_id,facilities_id FROM `dg_tags` where 1 = 1 and old_facilities_id !=0 and discharge = '0' and status = '1' " . $sql2 . " and facilities_id = '".$data ['facilities_id']."' ";
				$queryl = $this->db->query ( $sql1l );
				foreach ( $queryl->rows as $fid ) {
					$ddss [] = $fid ['old_facilities_id'];
					$ddss [] = $fid ['facilities_id'];
				}
			}
			
			if ($facility_info ['client_facilities_ids'] != null && $facility_info ['client_facilities_ids'] != "") {
				
				$ddss [] = $facility_info ['client_facilities_ids'];
				
				$ddss [] = $data ['facilities_id'];
				
				if ($data ['is_submaster'] == '1') {
					$sssssddsg = explode ( ",", $facility_info ['client_facilities_ids'] );
					$abdcg = array_unique ( $sssssddsg );
					$cids = array ();
					foreach ( $abdcg as $fid ) {
						$cids [] = $fid;
					}
					$abdcgs = array_unique ( $cids );
					foreach ( $abdcgs as $fid2 ) {
						$facilityinfo = $this->model_facilities_facilities->getfacilities ( $fid2 );
						if ($facilityinfo ['client_facilities_ids'] != null && $facilityinfo ['client_facilities_ids'] != "") {
							$ddss [] = $facilityinfo ['client_facilities_ids'];
						}
					}
				}
				$ddss = array_unique ( $ddss );
				$sssssdd = implode ( ",", $ddss );
				
				if ($data ['is_client_screen'] != null && $data ['is_client_screen'] != "") {
					$sql .= " and ( facilities_id in (" . $sssssdd . ") ";
					$sql .= " OR facility_move_id = '" . $data ['facilities_id'] . "' ) ";
				} else {
					$sql .= " and facilities_id in (" . $sssssdd . ") ";
				}
				
				$faculities_ids = $sssssdd;
			} else {
				if ($data ['facilities_id'] != null && $data ['facilities_id'] != "") {
					if ($data ['is_client_screen'] != null && $data ['is_client_screen'] != "") {
						$sql .= " and ( facilities_id = '" . $data ['facilities_id'] . "'";
						$sql .= " OR facility_move_id = '" . $data ['facilities_id'] . "' ) ";
					} else {
						$sql .= " and facilities_id = '" . $data ['facilities_id'] . "'";
					}
					// $sql.= " and facilities_id = '".$data['facilities_id']."'";
					$n_facilities_id = $data ['facilities_id'];
				}
			}
		} else {
			if ($data ['facilities_id'] != null && $data ['facilities_id'] != "") {
				
				if ($data ['is_client_screen'] != null && $data ['is_client_screen'] != "") {
					$sql .= " and ( facilities_id = '" . $data ['facilities_id'] . "'";
					$sql .= " OR facility_move_id = '" . $data ['facilities_id'] . "' ) ";
				} else {
					$sql .= " and facilities_id = '" . $data ['facilities_id'] . "'";
				}
			}
		}
		
		if ($data ['facility_inout'] == "1") {
			$sql .= " and facility_inout = '0'";
		}
		
		if ($data ['facility_inout'] == "2") {
			$sql .= " and facility_inout = '1'";
		}
		
		if ($data ['room_id'] != null && $data ['room_id'] != "") {
			$sql .= " and room = '" . $data ['room_id'] . "'";
			$n_room_id = $data ['room_id'];
		}
		
		if ($data ['app_user_date'] != '' && $data ['current_date_user'] != '') {
			$sql .= " and `modify_date` BETWEEN  '" . $data ['app_user_date'] . "' AND  '" . $data ['current_date_user'] . " 23:59:59' ";
		}
		
		if ($data ['search_tags_tag_id'] != null && $data ['search_tags_tag_id'] != "") {
			$sql .= " and tags_id = '" . $data ['search_tags_tag_id'] . "'";
		}
		
		if ($data ['gender'] != null && $data ['gender'] != "") {
			$sql .= " and gender = '" . $data ['gender'] . "'";
		}
		
		if ($data ['gender2'] != null && $data ['gender2'] != "") {
			$sql .= " and gender = '" . $data ['gender2'] . "'";
		}
		
		/*
		 * if($data['role_call'] == "1"){
		 * $sql.= " and role_call = '1'";
		 * }
		 *
		 * if($data['role_call'] == "2"){
		 * $sql.= " and role_call = '2'";
		 * }
		 */
		
		if ($data ['rolecalls'] != null && $data ['rolecalls'] != "") {
			

			if($data ['search_filter'] == 1){
				$sql .= " and role_call IN (" . $data ['rolecalls'] . ") and fixed_status_id = 0 ";
			}elseif($data ['search_filter'] == 2){
				$sql .= " and (role_call IN (" . $data ['rolecalls'] . ") or fixed_status_id IN (" . $data ['rolecalls'] . ") ) ";
			}else{
				$sql .= " and role_call IN (" . $data ['rolecalls'] . ") ";
			}
			
			
		}

		if ($data ['filters'] != null && $data ['filters'] != "") {
			$sql .= " and fixed_status_id IN (" . $data ['filters'] . ")";
			
		}
		
		if ($data ['emp_tag_id'] != null && $data ['emp_tag_id'] != "") {
			$sql .= " and emp_tag_id like '%" . $data ['emp_tag_id'] . "%'";
		}
		
		if ($data ['emp_first_name'] != null && $data ['emp_first_name'] != "") {
			$sql .= " and emp_first_name like '%" . $data ['emp_first_name'] . "%'";
		}
		if ($data ['emp_last_name'] != null && $data ['emp_last_name'] != "") {
			$sql .= " and emp_last_name like '%" . $data ['emp_last_name'] . "%'";
		}
		
		if ($data ['privacy'] != null && $data ['privacy'] != "") {
			$sql .= " and privacy = '" . $data ['privacy'] . "'";
		}
		if ($data ['status'] != null && $data ['status'] != "") {
			$sql .= " and status = '" . $data ['status'] . "'";
		}
		
		if (($data ['note_date_from'] != null && $data ['note_date_from'] != "") && ($data ['note_date_to'] != null && $data ['note_date_to'] != "")) {
			$startDate = date ( 'Y-m-d', strtotime ( $data ['note_date_from'] ) );
			$endDate = date ( 'Y-m-d', strtotime ( $data ['note_date_to'] ) );
			$sql .= " and (`date_added` BETWEEN  '" . $startDate . " 00:00:00 ' AND  '" . $endDate . " 23:59:59') ";
		}
		
		if ($data ['tags_id'] != NULL && $data ['tags_id'] != "") {
			$sql .= " and tags_id = '" . $data ['tags_id'] . "' ";
		}
		
		if ($data ['tagids'] != null && $data ['tagids'] != "") {
			$form_tagids = implode ( '\',\'', $data ['tagids'] );
			$sql .= " or tags_id in ('" . $form_tagids . "') ";
		}
		
		//echo $sql;
		// echo "<hr>";
		
		$query = $this->db->query ( $sql );
		
		return $query->row ['total'];
	}
	public function getcaseTotalTags($data = array()) {
		$sql = "SELECT DISTINCT t.tags_id,t.emp_tag_id,t.date_added as t_date_added,t.discharge_date as t_discharge_date,at.date_added as a_date_added,at.discharge_date as a_discharge_date FROM `" . DB_PREFIX . "tags` t ";
		$sql .= "LEFT JOIN `" . DB_PREFIX . "archive_tags` at ";
		$sql .= "ON t.tags_id = at.tags_id  ";
		
		$sql .= 'where 1 = 1 ';
		
		if ($data ['wait_list'] != null && $data ['wait_list'] != "") {
			$sql .= " and t.tags_status_in = 'Wait listed' ";
		} else {
			if ($data ['all_record'] == "1") {
				$sql .= " and t.tags_status_in = 'Admitted' ";
			}
		}
		
		if ($data ['discharge'] == "1") {
			$sql .= " and t.discharge = '0'";
		}
		
		if (! empty ( $data ['form_tagstatusa'] )) {
			$form_tagstatusa = implode ( '\',\'', $data ['form_tagstatusa'] );
			$sql .= " and t.tags_status_in in ('" . $form_tagstatusa . "') ";
		}
		
		if ($data ['discharge'] == "2") {
			$sql .= " and t.discharge = '1'";
			
			if ($data ['searchdate_2'] != null && $data ['searchdate_2'] != "") {
				$startDate = date ( 'Y-m-d', strtotime ( $data ['searchdate_2'] ) );
				$endDate = date ( 'Y-m-d', strtotime ( $data ['searchdate_2'] ) );
				
				$sql .= " and ( t.`discharge_date` BETWEEN '" . $startDate . " 00:00:00 ' AND '" . $endDate . " 23:59:59' or at.`discharge_date` BETWEEN '" . $startDate . " 00:00:00 ' AND '" . $endDate . " 23:59:59' ) ";
			}
			
			if (($data ['dnote_date_from'] != null && $data ['dnote_date_from'] != "") && ($data ['dnote_date_to'] != null && $data ['dnote_date_to'] != "")) {
				$startDate = date ( 'Y-m-d', strtotime ( $data ['dnote_date_from'] ) );
				$endDate = date ( 'Y-m-d', strtotime ( $data ['dnote_date_to'] ) );
				
				$sql .= " and ( t.`discharge_date` BETWEEN '" . $startDate . " 00:00:00 ' AND '" . $endDate . " 23:59:59' or at.`discharge_date` BETWEEN '" . $startDate . " 00:00:00 ' AND '" . $endDate . " 23:59:59' ) ";
			}
		}
		
		if ($data ['searchdate'] != null && $data ['searchdate'] != "") {
			
			$startDate = date ( 'Y-m-d', strtotime ( $data ['searchdate'] ) );
			$endDate = date ( 'Y-m-d', strtotime ( $data ['searchdate'] ) );
			
			$sql .= " and ( t.`date_added` BETWEEN '" . $startDate . " 00:00:00 ' AND '" . $endDate . " 23:59:59' or at.`date_added` BETWEEN '" . $startDate . " 00:00:00 ' AND '" . $endDate . " 23:59:59' ) ";
		}
		
		if ($data ['facilities_id'] != null && $data ['facilities_id'] != "") {
			$sql .= " and t.facilities_id = '" . $data ['facilities_id'] . "'";
		}
		
		if ($data ['search_tags_tag_id'] != null && $data ['search_tags_tag_id'] != "") {
			$sql .= " and t.tags_id = '" . $data ['search_tags_tag_id'] . "'";
		}
		
		if ($data ['gender'] != null && $data ['gender'] != "") {
			$sql .= " and t.gender = '" . $data ['gender'] . "'";
		}
		
		if ($data ['gender2'] != null && $data ['gender2'] != "") {
			$sql .= " and t.gender = '" . $data ['gender2'] . "'";
		}
		
		if ($data ['role_call'] == "1") {
			// $sql.= " and t.role_call = '1'";
		}
		
		if ($data ['role_call'] == "2") {
			// $sql.= " and t.role_call = '2'";
		}
		
		if ($data ['privacy'] != null && $data ['privacy'] != "") {
			$sql .= " and t.privacy = '" . $data ['privacy'] . "'";
		}
		if ($data ['status'] != null && $data ['status'] != "") {
			$sql .= " and t.status = '" . $data ['status'] . "'";
		}
		
		if (($data ['note_date_from'] != null && $data ['note_date_from'] != "") && ($data ['note_date_to'] != null && $data ['note_date_to'] != "")) {
			$startDate = date ( 'Y-m-d', strtotime ( $data ['note_date_from'] ) );
			$endDate = date ( 'Y-m-d', strtotime ( $data ['note_date_to'] ) );
			$sql .= " and ( t.`date_added` BETWEEN '" . $startDate . " 00:00:00 ' AND '" . $endDate . " 23:59:59' or at.`date_added` BETWEEN '" . $startDate . " 00:00:00 ' AND '" . $endDate . " 23:59:59' ) ";
		}
		
		if ($data ['tags_id'] != NULL && $data ['tags_id'] != "") {
			$sql .= " and t.tags_id = '" . $data ['tags_id'] . "' ";
		}
		
		// echo $sql;
		// echo "<hr>";
		
		$query = $this->db->query ( $sql );
		
		return $query->rows;
	}
	public function gettagassigns($notes_id) {
		$sql = "SELECT DISTINCT emp_tag_id,tags_id FROM `" . DB_PREFIX . "notes` WHERE status = '1' and emp_tag_id != '' ";
		$query = $this->db->query ( $sql );
		return $query->rows;
	}
	public function gettagModule($tags_id) {
		$query = $this->db->query ( "SELECT medication_id,tags_id,drug_name,dose,drug_type,quantity,frequency,instructions,start_date,end_date,status,count,create_task,start_time FROM `" . DB_PREFIX . "medication` WHERE tags_id = '" . $tags_id . "'" );
		
		$new_module = array ();
		
		if ($query->num_rows) {
			foreach ( $query->rows as $rows ) {
				
				if ($rows ['start_date'] != null && $rows ['start_date'] != "0000-00-00 00:00:00") {
					$start_date = date ( 'm/d/Y', strtotime ( $rows ['start_date'] ) );
				}
				
				if ($rows ['end_date'] != null && $rows ['end_date'] != "0000-00-00 00:00:00") {
					$end_date = date ( 'm/d/Y', strtotime ( $rows ['end_date'] ) );
				}
				
				$sql = "SELECT * FROM `" . DB_PREFIX . "medication_time` WHERE medication_id = '" . $rows ['medication_id'] . "'";
				
				$queryrow = $this->db->query ( $sql );
				
				$dates = array ();
				
				foreach ( $queryrow->rows as $startdates ) {
					$dates [] = array (
							'start_time' => $startdates ['start_time'] 
					);
				}
				
				$new_module ['new_module'] [] = array (
						'medication_id' => $rows ['medication_id'],
						'drug_name' => $rows ['drug_name'],
						'dose' => $rows ['dose'],
						'drug_type' => $rows ['drug_type'],
						'quantity' => $rows ['quantity'],
						'frequency' => $rows ['frequency'],
						'start_time' => $dates,
						'instructions' => $rows ['instructions'],
						'start_date' => $start_date,
						'end_date' => $end_date,
						'status' => $rows ['status'],
						'count' => $rows ['count'] 
				);
			}
		}
		return $new_module;
	}
	public function getlocations($facility_id) {
		$sql = "SELECT locations_id,location_name,location_address,location_detail,capacity,location_type,nfc_location_tag,nfc_location_tag_required,	gps_location_tag,	gps_location_tag_required,latitude	,longitude,other_location_tag,other_location_tag_required,other_type_id,facilities_id,upload_file,customlistvalues_id FROM `" . DB_PREFIX . "locations` where facilities_id = '" . $facility_id . "' ";
		
		$query = $this->db->query ( $sql );
		return $query->rows;
	}
	public function getmedication($data = array()) {
		$sql = "SELECT medication_id,tags_id,drug_name,dose,drug_type,quantity,frequency,instructions,start_date,end_date,count,create_task,start_time FROM `" . DB_PREFIX . "medication` ";
		
		$sql .= 'where 1 = 1 ';
		
		if ($data ['medications_id'] != null && $data ['medications_id'] != "") {
			$sql .= " and medication_id = '" . $data ['medications_id'] . "'";
		}
		
		if ($data ['create_task'] != null && $data ['create_task'] != "") {
			$sql .= " and create_task = '" . $data ['create_task'] . "'";
		}
		
		if ($data ['tags_id'] != null && $data ['tags_id'] != "") {
			$sql .= " and tags_id = '" . $data ['tags_id'] . "'";
		}
		
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	public function getmedicationTimes($data = array()) {
		$sql = "SELECT medication_time_id,start_time,medication_id,tags_id,create_task FROM `" . DB_PREFIX . "medication_time` ";
		
		$sql .= 'where 1 = 1 ';
		
		if ($data ['medications_id'] != null && $data ['medications_id'] != "") {
			$sql .= " and medication_id = '" . $data ['medications_id'] . "'";
		}
		
		if ($data ['tags_id'] != null && $data ['tags_id'] != "") {
			$sql .= " and tags_id = '" . $data ['tags_id'] . "'";
		}
		
		$query = $this->db->query ( $sql );
		return $query->rows;
	}
	public function getDrugs($task_id) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "createtask_by_medication` where id = '" . $task_id . "' ";
		$query = $this->db->query ( $sql );
		return $query->rows;
	}
	public function createClient($client_name) {
		$sql = "INSERT INTO `" . DB_PREFIX . "client` SET client_name = '" . $client_name . "' ";
		$this->db->query ( $sql );
		$client_id = $this->db->getLastId ();
		return $client_id;
	}
	public function getClient($client_name) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "client` where client_name = '" . $client_name . "' ";
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	public function getClientbyid($client_id) {
		$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "client WHERE client_id = '" . ( int ) $client_id . "'" );
		return $query->row;
	}
	public function getclients($data = array()) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "client`";
		
		$sql .= 'where 1 = 1 ';
		
		if ($data ['client_name'] != null && $data ['client_name'] != "") {
			$sql .= " and client_name like '%" . $data ['client_name'] . "%'";
		}
		
		$sort_data = array (
				'client_name' 
		);
		
		if (isset ( $data ['sort'] ) && in_array ( $data ['sort'], $sort_data )) {
			$sql .= " ORDER BY " . $data ['sort'];
		} else {
			$sql .= " ORDER BY client_name";
		}
		
		if (isset ( $data ['order'] ) && ($data ['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		
		if (isset ( $data ['start'] ) || isset ( $data ['limit'] )) {
			if ($data ['start'] < 0) {
				$data ['start'] = 0;
			}
			
			if ($data ['limit'] < 1) {
				$data ['limit'] = 20;
			}
			
			$sql .= " LIMIT " . ( int ) $data ['start'] . "," . ( int ) $data ['limit'];
		}
		
		$query = $this->db->query ( $sql );
		
		return $query->rows;
	}
	function getTagsMedications($tags_id) {
		$query = $this->db->query ( "SELECT tags_medication_id,tags_id,medication_fields,is_schedule,is_discharge FROM `" . DB_PREFIX . "tags_medication` WHERE tags_id = '" . $tags_id . "'" );
		return $query->row;
	}
	function getTagsMedicationdetails($tags_id) {
		$query = $this->db->query ( "SELECT tags_medication_details_id,tags_medication_id,tags_id,drug_name,drug_mg,drug_am,drug_pm,drug_alertnate,drug_prn,instructions,status,recurrence,recurnce_hrly_recurnce,end_recurrence_date,daily_endtime,daily_times,recurnce_hrly,recurnce_week,recurnce_month,recurnce_day,date_from,date_to,is_schedule_medication,create_task,is_discharge,tags_medication_details_ids,is_updated,is_schedule_id,room_id,type_name,type FROM `" . DB_PREFIX . "tags_medication_details` WHERE tags_id = '" . $tags_id . "'" );
		return $query->rows;
	}
	public function getTagsbyName($emp_first_name, $emp_last_name) {
		$sql = "SELECT tags_id,emp_tag_id,emp_first_name,emp_middle_name,emp_last_name,privacy,date_added,dob,locations_id,facilities_id,upload_file,tags_pin,gender,discharge,age,role_call,location_address,latitude,longitude,emp_extid,address_street2,person_screening,date_of_screening,ssn,state,city,zipcode,room,restriction_notes,prescription,alert_info,constant_sight,med_mental_health,tagstatus,forms_id,tags_forms_id,discharge_date,stickynote,customlistvalues_id,tags_status,tags_status_in,referred_facility,emergency_contact,reminder_time,reminder_date,upload_file_thumb,notes_id,ccn,movement_room,is_movement,classification_id FROM `" . DB_PREFIX . "tags` WHERE LCASE(emp_first_name) = '" . $this->db->escape ( utf8_strtolower ( $emp_first_name ) ) . "' and LCASE(emp_last_name) = '" . $this->db->escape ( utf8_strtolower ( $emp_last_name ) ) . "' and discharge = '0' ";
		$query = $this->db->query ( $sql );
		$result = $query->row;
		$keys = array_keys ( $result );
		foreach ( $keys as $key ) {
			$result [$key] = $this->formattedValue ( $result [$key], $key );
		}
		return $result;
		// return $query->row;
		/* */
	}
	public function getTagsbySSN($ssn) {
		$sql = "SELECT tags_id,emp_tag_id,emp_first_name,emp_middle_name,emp_last_name,privacy,date_added,dob,locations_id,facilities_id,upload_file,tags_pin,gender,discharge,age,role_call,location_address,latitude,longitude,emp_extid,address_street2,person_screening,date_of_screening,ssn,state,city,zipcode,room,restriction_notes,prescription,alert_info,constant_sight,med_mental_health,tagstatus,forms_id,tags_forms_id,discharge_date,stickynote,customlistvalues_id,tags_status,tags_status_in,referred_facility,emergency_contact,reminder_time,reminder_date,upload_file_thumb,ccn,movement_room,is_movement,classification_id FROM `" . DB_PREFIX . "tags` WHERE LCASE(ssn) = '" . $this->db->escape ( utf8_strtolower ( $ssn ) ) . "' and discharge = '0' ";
		$query = $this->db->query ( $sql );
		$result = $query->row;
		$keys = array_keys ( $result );
		foreach ( $keys as $key ) {
			$result [$key] = $this->formattedValue ( $result [$key], $key );
		}
		return $result;
		// return $query->row;
		/* */
	}
	public function getTagsbyextID($emp_extid) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "tags` WHERE LCASE(emp_extid) = '" . $this->db->escape ( utf8_strtolower ( $emp_extid ) ) . "' and discharge = '0'  ";
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	public function getTagsbyDOBName($dob, $emp_first_name, $emp_last_name) {
		$sql = "SELECT tags_id,emp_tag_id,emp_first_name,emp_middle_name,emp_last_name,privacy,date_added,dob,locations_id,facilities_id,upload_file,tags_pin,gender,discharge,age,role_call,location_address,latitude,longitude,emp_extid,address_street2,person_screening,date_of_screening,ssn,state,city,zipcode,room,restriction_notes,prescription,alert_info,constant_sight,med_mental_health,tagstatus,forms_id,tags_forms_id,discharge_date,stickynote,customlistvalues_id,tags_status,tags_status_in,referred_facility,emergency_contact,reminder_time,reminder_date,upload_file_thumb,ccn,movement_room,is_movement,classification_id FROM `" . DB_PREFIX . "tags` WHERE LCASE(emp_first_name) = '" . $this->db->escape ( utf8_strtolower ( $emp_first_name ) ) . "' and LCASE(emp_last_name) = '" . $this->db->escape ( utf8_strtolower ( $emp_last_name ) ) . "' and dob = '" . $this->db->escape ( $dob ) . "' and discharge = '0' ";
		$query = $this->db->query ( $sql );
		$result = $query->row;
		$keys = array_keys ( $result );
		foreach ( $keys as $key ) {
			$result [$key] = $this->formattedValue ( $result [$key], $key );
		}
		return $result;
		// return $query->row;
		/* */
	}
	function getTagsMedicationdetailsByID($task_id, $tags_id) {
		$sql = "SELECT createtask_medications_id,id,facilities_id,tags_id,tags_medication_id,tags_medication_details_id,drug_name,complete_status FROM `" . DB_PREFIX . "createtask_medications` WHERE id = '" . $task_id . "' and tags_id = '" . $tags_id . "' ";
		$query = $this->db->query ( $sql );
		return $query->rows;
	}
	function getTagsMedicationdruglByID($tags_medication_details_id) {
		$query = $this->db->query ( "SELECT tags_medication_details_id,tags_medication_id,tags_id,drug_name,drug_mg,drug_am,drug_pm,drug_alertnate,drug_prn,instructions,status,recurrence,recurnce_hrly_recurnce,end_recurrence_date,daily_endtime,daily_times,recurnce_hrly,recurnce_week,recurnce_month,recurnce_day,date_from,date_to,is_schedule_medication,create_task,is_discharge,tags_medication_details_ids,is_updated,is_schedule_id,room_id,type_name,type FROM `" . DB_PREFIX . "tags_medication_details` WHERE tags_medication_details_id = '" . $tags_medication_details_id . "'" );
		return $query->row;
	}
	public function addCensus($data, $notes_id, $date_added, $facilities_id, $timezone_name) {
		if ($data ['census']) {
			$this->load->model ( 'setting/tags' );
			
			foreach ( $data ['census'] as $id ) {
				
				$tag_info = $this->model_setting_tags->getTag ( $id ['tagides'] );
				
				$emp_tag_id = $tag_info ['emp_tag_id'];
				
				$sql11 = "INSERT INTO `" . DB_PREFIX . "notes_tags` SET tags_id =  '" . $id ['tagides'] . "', is_census =  '1', breakfast = '" . $id ['breakfast'] . "', dinner = '" . $id ['dinner'] . "', lunch = '" . $id ['lunch'] . "', refused = '" . $id ['refused'] . "', notes_id = '" . $notes_id . "', facilities_id = '" . $facilities_id . "', date_added = '" . $date_added . "', emp_tag_id = '" . $emp_tag_id . "' ";
				$this->db->query ( $sql11 );
			}
		}
		
		$timeZone = date_default_timezone_set ( $timezone_name );
		
		$date = str_replace ( '-', '/', $data ['census_date'] );
		
		$res = explode ( "/", $date );
		$createdate1 = $res [2] . "-" . $res [0] . "-" . $res [1];
		
		$createdate1 = date ( 'Y-m-d', strtotime ( $createdate1 ) );
		
		// $createdate1 = date('Y-m-d',strtotime($data['census_date']));
		
		$createtime1 = date ( 'H:i:s' );
		// var_dump($createtime1);
		$createDate2 = $createdate1 . $createtime1;
		
		$census_date = date ( 'Y-m-d H:i:s', strtotime ( $createDate2 ) );
		
		$userids = implode ( ',', $data ['userids'] );
		
		if ($data ['team_leaders'] != null && $data ['team_leaders'] != "") {
			$team_leaders = implode ( ',', $data ['team_leaders'] );
		} else {
			$team_leaders = $data ['team_leader'];
		}
		/*
		 * $sql1 = "INSERT INTO `" . DB_PREFIX . "notes_census_detail` SET shift_id = '" . $this->db->escape($data['shift_id']) . "', team_leader = '" . $this->db->escape($team_leaders) . "', direct_care = '" . $this->db->escape($data['direct_care']) . "',staff = '" . $this->db->escape($data['staff']) . "', spm = '" . $this->db->escape($data['spm']) . "', as_spm = '" . $this->db->escape($data['as_spm']) . "', case_manager = '" . $this->db->escape($data['case_manager']) . "', food_services = '" . $this->db->escape($data['food_services']) . "', educational_staff = '" . $this->db->escape($data['educational_staff']) . "', screenings = '" . $this->db->escape($data['screenings']) . "', date_added = '".$date_added."', census_date = '".$census_date."' , intakes = '" . $this->db->escape($data['intakes_total']) . "',discharge = '" . $this->db->escape($data['discharge_total']) . "',offsite = '" . $this->db->escape($data['offsite_total']) . "', in_house = '" . $this->db->escape($data['inhouse_total']) . "', males = '" . $this->db->escape($data['males_total']) . "',females = '" . $this->db->escape($data['females_total']) . "',non_specific_total = '" . $this->db->escape($data['non_specific_total']) . "', total = '" . $this->db->escape($data['all_total']) . "' , end_of_shift_status = '" . $this->db->escape($data['end_of_shift_status']) . "' , comment_box = '" . $this->db->escape($userids) . "', notes_id = '" . $notes_id . "', facilities_id = '" . $facilities_id . "' ";
		 * $this->db->query($sql1);
		 * $census_detail_id = $this->db->getLastId();
		 */
		
		$this->load->model ( 'activity/activity' );
		$data ['census_detail_id'] = $census_detail_id;
		$this->model_activity_activity->addActivitySave ( 'addCensus', $data, 'query' );
		
		$sql1 = "CALL insertNotesCensus('" . $data ['shift_id'] . "','" . $this->db->escape ( $team_leaders ) . "','" . $this->db->escape ( $data ['direct_care'] ) . "','" . $this->db->escape ( $data ['staff'] ) . "','" . $this->db->escape ( $data ['spm'] ) . "','" . $this->db->escape ( $data ['as_spm'] ) . "','" . $this->db->escape ( $data ['case_manager'] ) . "','" . $this->db->escape ( $data ['food_services'] ) . "','" . $this->db->escape ( $data ['educational_staff'] ) . "','" . $this->db->escape ( $data ['screenings'] ) . "','" . $date_added . "','" . $census_date . "','" . $this->db->escape ( $data ['intakes_total'] ) . "','" . $this->db->escape ( $data ['discharge_total'] ) . "','" . $this->db->escape ( $data ['offsite_total'] ) . "' ,'" . $this->db->escape ( $data ['inhouse_total'] ) . "','" . $this->db->escape ( $data ['males_total'] ) . "','" . $this->db->escape ( $data ['females_total'] ) . "','" . $this->db->escape ( $data ['non_specific_total'] ) . "','" . $this->db->escape ( $data ['all_total'] ) . "','" . $this->db->escape ( $data ['end_of_shift_status'] ) . "' ,'" . $this->db->escape ( $userids ) . "','" . $notes_id . "','" . $facilities_id . "'  )";
		
		$lastId = $this->db->query ( $sql1 );
		
		$census_detail_id = $lastId->row ['census_detail_id'];
	}
	function getTagsbyNotesID($notes_id) {
		$query = $this->db->query ( "SELECT notes_tags_id,emp_tag_id,tags_id,notes_id,user_id,date_added,signature,signature_image,notes_pin,notes_type,facilities_id,is_census,lunch,dinner,breakfast,refused FROM `" . DB_PREFIX . "notes_tags` WHERE notes_id = '" . $notes_id . "' and is_census = '1' " );
		return $query->rows;
	}
	function getTagsbyNotesID2($notes_id) {
		$query = $this->db->query ( "SELECT notes_tags_id,emp_tag_id,tags_id,notes_id,user_id,date_added,signature,signature_image,notes_pin,notes_type,facilities_id,is_census,lunch,dinner,breakfast,refused FROM `" . DB_PREFIX . "notes_tags` WHERE notes_id = '" . $notes_id . "' " );
		return $query->rows;
	}
	function getTagsbyNotesIDrow($notes_id) {
		$query = $this->db->query ( "SELECT notes_tags_id,emp_tag_id,tags_id,notes_id,user_id,date_added,signature,signature_image,notes_pin,notes_type,facilities_id,is_census,lunch,dinner,breakfast,refused FROM `" . DB_PREFIX . "notes_tags` WHERE notes_id = '" . $notes_id . "' " );
		return $query->row;
	}
	function getTagsdetailbyNotesID($notes_id) {
		$query = $this->db->query ( "SELECT notes_census_detail_id,notes_id,tags_id,shift_id,date_added,census_date,team_leader,direct_care,comment_box,spm,as_spm,case_manager,food_services,educational_staff,screenings,intakes,discharge,offsite,in_house,males,females,non_specific_total,total,end_of_shift_status,staff,facilities_id FROM `" . DB_PREFIX . "notes_census_detail` WHERE notes_id = '" . $notes_id . "'" );
		return $query->row;
	}
	public function getTagsByFacility($data) {
		$sql = "select DISTINCT t.* from `" . DB_PREFIX . "tags` t ";
		$sql .= "left JOIN " . DB_PREFIX . "tags_all_facility tg on tg.tags_id=t.tags_id  ";
		
		$sql .= " where 1 = 1 and discharge='1' ";
		
		if ($data ['emp_tag_id'] != null && $data ['emp_tag_id'] != "") {
			// $sql .= " and t.emp_tag_id like '%".$data['emp_tag_id']."%'";
			
			$sql .= " and ( LOWER(t.emp_tag_id) like '%" . strtolower ( $data ['emp_tag_id'] ) . "%' or LOWER(t.emp_first_name) LIKE '%" . strtolower ( $data ['emp_tag_id'] ) . "%' or LOWER(t.emp_last_name) LIKE '%" . strtolower ( $data ['emp_tag_id'] ) . "%' or LOWER(t.emp_extid) LIKE '%" . strtolower ( $data ['emp_tag_id'] ) . "%' or LOWER(t.ssn) LIKE '%" . strtolower ( $data ['emp_tag_id'] ) . "%' ) ";
		}
		
		if ($data ['emp_first_name'] != null && $data ['emp_first_name'] != "") {
			$sql .= " and t.emp_first_name like '%" . $data ['emp_first_name'] . "%'";
		}
		if ($data ['emp_last_name'] != null && $data ['emp_last_name'] != "") {
			$sql .= " and t.emp_last_name like '%" . $data ['emp_last_name'] . "%'";
		}
		
		if (isset ( $data ['sort'] ) && in_array ( $data ['sort'], $sort_data )) {
			$sql .= " ORDER BY " . $data ['sort'];
		} else {
			$sql .= " ORDER BY emp_tag_id";
		}
		
		if (isset ( $data ['order'] ) && ($data ['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		
		if (isset ( $data ['start'] ) || isset ( $data ['limit'] )) {
			if ($data ['start'] < 0) {
				$data ['start'] = 0;
			}
			
			if ($data ['limit'] < 1) {
				$data ['limit'] = 20;
			}
			
			$sql .= " LIMIT " . ( int ) $data ['start'] . "," . ( int ) $data ['limit'];
		}
		
		$query = $this->db->query ( $sql );
		
		$results = $query->rows;
		foreach ( $results as $result ) {
			$keys = array_keys ( $result );
			foreach ( $keys as $key ) {
				$result [$key] = $this->formattedValue ( $result [$key], $key );
			}
			$resultsArr [] = $result;
		}
		return $resultsArr;
		// return $query->rows;
	}
	public function updateSticky($data) {
		$this->load->model ( 'setting/tags' );
		$tag_info = $this->model_setting_tags->getTag ( $data ['tags_id'] );
		
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $tag_info ['facilities_id'] );
		
		$this->load->model ( 'setting/timezone' );
		$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
		$facilitytimezone = $timezone_info ['timezone_value'];
		$timeZone = date_default_timezone_set ( $timezone_name );
		$date_added = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		
		$this->db->query ( "UPDATE `" . DB_PREFIX . "tags` SET stickynote = '" . $this->db->escape ( $data ['stickynote'] ) . "', modify_date = '" . $date_added . "' WHERE tags_id = '" . $this->db->escape ( $data ['tags_id'] ) . "'" );
		
		$this->load->model ( 'activity/activity' );
		$this->model_activity_activity->addActivitySave ( 'updateSticky', $data, 'query' );
	}
	public function updateStickyclear($tags_id) {
		$this->load->model ( 'setting/tags' );
		$tag_info = $this->model_setting_tags->getTag ( $tags_id );
		
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $tag_info ['facilities_id'] );
		
		$this->load->model ( 'setting/timezone' );
		$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
		$facilitytimezone = $timezone_info ['timezone_value'];
		$timeZone = date_default_timezone_set ( $timezone_name );
		$date_added = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		
		$this->db->query ( "UPDATE `" . DB_PREFIX . "tags` SET stickynote = '', modify_date = '" . $date_added . "' WHERE tags_id = '" . $this->db->escape ( $tags_id ) . "'" );
		
		$this->load->model ( 'activity/activity' );
		$data ['tags_id'] = $tags_id;
		$data ['stickynote'] = '';
		$this->model_activity_activity->addActivitySave ( 'updateStickyclear', $data, 'query' );
	}
	public function getrolecallby($outtags, $facilities_id) {
		$sql = "SELECT tags_id,emp_tag_id,emp_first_name,emp_middle_name,emp_last_name,privacy,date_added,dob,locations_id,facilities_id,upload_file,tags_pin,gender,discharge,age,role_call,location_address,latitude,longitude,emp_extid,address_street2,person_screening,date_of_screening,ssn,state,city,zipcode,room,restriction_notes,prescription,alert_info,constant_sight,med_mental_health,tagstatus,forms_id,tags_forms_id,discharge_date,stickynote,customlistvalues_id,tags_status,tags_status_in,referred_facility,emergency_contact,reminder_time,reminder_date,upload_file_thumb FROM `" . DB_PREFIX . "tags`";
		
		$sql .= "where 1 = 1 and discharge = '0'  and tags_status_in = 'Admitted' ";
		
		$a = array ();
		$b = array ();
		foreach ( $outtags as $key => $outtag ) {
			// var_dump($key);
			// var_dump($outtag);
			$a [] = $key;
			$b [] = $outtag;
		}
		
		if ($a != null && $a != "") {
			$sql .= " and tags_id NOT IN (" . implode ( ',', $a ) . ")";
		}
		
		if ($facilities_id != null && $facilities_id != "") {
			$sql .= " and facilities_id = '" . $facilities_id . "'";
		}
		
		// $sql .= " and role_call NOT IN (".implode(',',$b).")";
		
		$query = $this->db->query ( $sql );
		
		$results = $query->rows;
		foreach ( $results as $result ) {
			$keys = array_keys ( $result );
			foreach ( $keys as $key ) {
				$result [$key] = $this->formattedValue ( $result [$key], $key );
			}
			$resultsArr [] = $result;
		}
		return $resultsArr;
		// return $query->rows;
	}
	public function updatexittag($data, $facilities_id) {
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $facilities_id );
		$this->load->model ( 'setting/timezone' );
		
		$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
		$facilitytimezone = $timezone_info ['timezone_value'];
		$timeZone = date_default_timezone_set ( $timezone_name );
		$date_added = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		
		$this->db->query ( "UPDATE `" . DB_PREFIX . "tags` SET discharge = '0', date_added = '" . $this->db->escape ( $date_added ) . "', facilities_id = '" . $this->db->escape ( $facilities_id ) . "' WHERE tags_id = '" . $this->db->escape ( $data ['emp_tag_id'] ) . "'" );
		
		$this->load->model ( 'activity/activity' );
		$data ['facilities_id'] = $facilities_id;
		$data ['discharge'] = '0';
		// $data['role_call'] = '1';
		$data ['date_added'] = $date_added;
		$data ['tags_id'] = $data ['emp_tag_id'];
		$this->model_activity_activity->addActivitySave ( 'updatexittag', $data, 'query' );
	}
	function getTagsbyNotesroomid($locations_id) {
		$sql = "SELECT tags_id,emp_tag_id,emp_first_name,emp_middle_name,emp_last_name,privacy,date_added,dob,locations_id,facilities_id,upload_file,tags_pin,gender,discharge,age,role_call,location_address,latitude,longitude,emp_extid,address_street2,person_screening,date_of_screening,ssn,state,city,zipcode,room,restriction_notes,prescription,alert_info,constant_sight,med_mental_health,tagstatus,forms_id,tags_forms_id,discharge_date,stickynote,customlistvalues_id,tags_status,tags_status_in,referred_facility,emergency_contact,reminder_time,reminder_date,upload_file_thumb FROM `" . DB_PREFIX . "tags` WHERE room = '" . $locations_id . "' ";
		$query = $this->db->query ( $sql );
		$result = $query->row;
		$keys = array_keys ( $result );
		foreach ( $keys as $key ) {
			$result [$key] = $this->formattedValue ( $result [$key], $key );
		}
		return $result;
		// return $query->row;
	}
	public function gettotalcustomlistvaluebyid($customlistvalues_id, $gender, $role_call, $facilities_id) {
		// $sql = "SELECT count(*) as total FROM " . DB_PREFIX . "tags WHERE customlistvalues_id = '" . (int)$customlistvalues_id . "' and gender = '" . $gender . "' and facilities_id = '" . $facilities_id . "' and role_call = '".$role_call."' and discharge = '0' and tags_status_in = 'Admitted' ";
		$sql = "SELECT  count(*) as total FROM " . DB_PREFIX . "tags WHERE gender = '" . $gender . "' and facilities_id = '" . $facilities_id . "' and role_call = '" . $role_call . "' and discharge = '0' and tags_status_in = 'Admitted' ";
		
		$query = $this->db->query ( $sql );
		return $query->row ['total'];
	}
	public function updatetagsinfo($data = array()) {
		if ($data ['emp_first_name']) {
			
			$sqlta1 = "update `" . DB_PREFIX . "tags` SET emp_first_name = '" . $this->db->escape ( $data ['emp_first_name'] ) . "' where tags_id = '" . ( int ) $data ['tags_id'] . "' ";
			$this->db->query ( $sqlta1 );
			
			$sqlta = "update `" . DB_PREFIX . "tags_all_facility` SET emp_first_name = '" . $this->db->escape ( $data ['emp_first_name'] ) . "' where tags_id = '" . ( int ) $data ['tags_id'] . "' ";
			$this->db->query ( $sqlta );
		}
		
		if ($data ['emp_middle_name']) {
			
			$sqlta12 = "update `" . DB_PREFIX . "tags` SET emp_middle_name = '" . $this->db->escape ( $data ['emp_middle_name'] ) . "' where tags_id = '" . ( int ) $data ['tags_id'] . "' ";
			$this->db->query ( $sqlta12 );
		}
		
		if ($data ['emp_last_name']) {
			
			$sqlta12 = "update `" . DB_PREFIX . "tags` SET emp_last_name = '" . $this->db->escape ( $data ['emp_last_name'] ) . "' where tags_id = '" . ( int ) $data ['tags_id'] . "' ";
			$this->db->query ( $sqlta12 );
			
			$sqlta = "update `" . DB_PREFIX . "tags_all_facility` SET emp_last_name = '" . $this->db->escape ( $data ['emp_last_name'] ) . "' where tags_id = '" . ( int ) $data ['tags_id'] . "' ";
			$this->db->query ( $sqlta );
		}
		
		if ($data ['emergency_contact']) {
			$sqlta12e = "update `" . DB_PREFIX . "tags` SET emergency_contact = '" . $this->db->escape ( $data ['emergency_contact'] ) . "' where tags_id = '" . ( int ) $data ['tags_id'] . "' ";
			$this->db->query ( $sqlta12e );
		}
		
		if ($data ['dob']) {
			$sqlta12es = "update `" . DB_PREFIX . "tags` SET dob = '" . $this->db->escape ( $data ['dob'] ) . "' where tags_id = '" . ( int ) $data ['tags_id'] . "' ";
			$this->db->query ( $sqlta12es );
		}
		
		if ($data ['facilities_id']) {
			$sqlta12s = "update `" . DB_PREFIX . "tags` SET facilities_id = '" . $this->db->escape ( $data ['facilities_id'] ) . "' where tags_id = '" . ( int ) $data ['tags_id'] . "' ";
			$this->db->query ( $sqlta12s );
		}
		
		if ($data ['upload_file']) {
			$sqla12es = "update `" . DB_PREFIX . "tags` SET upload_file = '" . $this->db->escape ( $data ['upload_file'] ) . "' where tags_id = '" . ( int ) $data ['tags_id'] . "' ";
			$this->db->query ( $sqla12es );
		}
		
		if ($data ['gender']) {
			$sqlta2es = "update `" . DB_PREFIX . "tags` SET gender = '" . $this->db->escape ( $data ['gender'] ) . "' where tags_id = '" . ( int ) $data ['tags_id'] . "' ";
			$this->db->query ( $sqlta2es );
		}
		
		if ($data ['age']) {
			$sqlta1s = "update `" . DB_PREFIX . "tags` SET age = '" . $this->db->escape ( $data ['age'] ) . "' where tags_id = '" . ( int ) $data ['tags_id'] . "' ";
			$this->db->query ( $sqlta1s );
		}
		
		if ($data ['emp_extid']) {
			$sql1s = "update `" . DB_PREFIX . "tags` SET emp_extid = '" . $this->db->escape ( $data ['emp_extid'] ) . "' where tags_id = '" . ( int ) $data ['tags_id'] . "' ";
			$this->db->query ( $sql1s );
		}
		
		if ($data ['ssn']) {
			$sq1s = "update `" . DB_PREFIX . "tags` SET ssn = '" . $this->db->escape ( $data ['ssn'] ) . "' where tags_id = '" . ( int ) $data ['tags_id'] . "' ";
			$this->db->query ( $sq1s );
		}
		
		if ($data ['location_address']) {
			$sq1gs = "update `" . DB_PREFIX . "tags` SET location_address = '" . $this->db->escape ( $data ['location_address'] ) . "' where tags_id = '" . ( int ) $data ['tags_id'] . "' ";
			$this->db->query ( $sq1gs );
		}
		
		if ($data ['city']) {
			$sqgs = "update `" . DB_PREFIX . "tags` SET city = '" . $this->db->escape ( $data ['city'] ) . "' where tags_id = '" . ( int ) $data ['tags_id'] . "' ";
			$this->db->query ( $sqgs );
		}
		
		if ($data ['state']) {
			$sqg5s = "update `" . DB_PREFIX . "tags` SET state = '" . $this->db->escape ( $data ['state'] ) . "' where tags_id = '" . ( int ) $data ['tags_id'] . "' ";
			$this->db->query ( $sqg5s );
		}
		
		if ($data ['zipcode']) {
			$sqgs3 = "update `" . DB_PREFIX . "tags` SET zipcode = '" . $this->db->escape ( $data ['zipcode'] ) . "' where tags_id = '" . ( int ) $data ['tags_id'] . "' ";
			$this->db->query ( $sqgs3 );
		}
	}
	public function gettagscustomlistvaluebyid($room, $role_call, $facilities_id) {
		
		/* and role_call = '".$role_call."' */
		$this->load->model ( 'notes/clientstatus' );
		$sql1 = "SELECT tags_id,role_call FROM " . DB_PREFIX . "tags WHERE room = '" . $room . "' and facilities_id = '" . $facilities_id . "' and discharge = '0' and tags_status_in = 'Admitted' ";
		
		$query1 = $this->db->query ( $sql1 );
		
		$rollids = array ();
		if ($query1->num_rows > 0) {
			foreach ( $query1->rows as $tid ) {
				
				$clientstatus_info = $this->model_notes_clientstatus->getclientstatus ( $tid ['role_call'] );
				
				if ($clientstatus_info ['tag_status_id'] != null && $clientstatus_info ['tag_status_id'] != "") {
					if ($clientstatus_info ['type'] == "3") {
						$rollids [] = $clientstatus_info ['tag_status_id'];
					}
				}
			}
			
			if (! empty ( $rollids )) {
				$fffa = implode ( ',', $rollids );
				$sql2 = " and role_call NOT IN (" . $fffa . ")";
			}
		}
		
		$sql = "SELECT tags_id,emp_tag_id,emp_first_name,emp_middle_name,emp_last_name,privacy,date_added,dob,locations_id,facilities_id,upload_file,tags_pin,gender,discharge,age,role_call,location_address,latitude,longitude,emp_extid,address_street2,person_screening,date_of_screening,ssn,state,city,zipcode,room,restriction_notes,prescription,alert_info,constant_sight,med_mental_health,tagstatus,forms_id,tags_forms_id,discharge_date,stickynote,customlistvalues_id,tags_status,tags_status_in,referred_facility,emergency_contact,reminder_time,reminder_date,upload_file_thumb,medication_inout FROM " . DB_PREFIX . "tags WHERE room = '" . $room . "' and facilities_id = '" . $facilities_id . "' " . $sql2 . " and discharge = '0' and tags_status_in = 'Admitted' ";
		
		$query = $this->db->query ( $sql );
		return $query->rows;
	}
	public function gettotalcountbyroom($locations_id) {
		$sql = "SELECT COUNT(*) as total from " . DB_PREFIX . "tags where room = '" . $locations_id . "' and discharge = '0' and tags_status_in = 'Admitted' ";
		
		$query = $this->db->query ( $sql );
		
		return $query->row ['total'];
	}
	public function gettotalarchivetags($tags_id) {
		$sql = "SELECT  count(*) as total FROM " . DB_PREFIX . "archive_tags WHERE tags_id = '" . $tags_id . "' and discharge = '0' and tags_status_in = 'Admitted' ";
		
		$query = $this->db->query ( $sql );
		return $query->row ['total'];
	}
	public function addcurrentTagarchive($tags_id) {
		$d1 = array ();
		$d1 ['tags_id'] = $tags_id;
		$d1 ['form_type'] = '2';
		$notes_info = $this->model_notes_notes->getNoteform ( $d1 );
		
		$query12 = $this->db->query ( "SELECT archive_tags_id FROM `" . DB_PREFIX . "archive_tags` WHERE tags_id = '" . $tags_id . "' and notes_id='0' " );
		
		if ($query12->num_rows > 0) {
			$mrow = $query12->row;
			
			$this->db->query ( "UPDATE `" . DB_PREFIX . "archive_tags` SET notes_id = '" . ( int ) $notes_info ['notes_id'] . "',is_archive = '3' WHERE archive_tags_id = '" . ( int ) $mrow ['archive_tags_id'] . "'" );
		}
		
		$sql = "UPDATE `" . DB_PREFIX . "notes` SET is_archive = '3',notes_conut='0' where is_tag = '" . ( int ) $tags_id . "' and form_type = '2' and is_archive = '0' ";
		$this->db->query ( $sql );
		
		$this->load->model ( 'activity/activity' );
		$data ['notes_id'] = $notes_info ['notes_id'];
		$data ['tags_id'] = $tags_id;
		$data ['archive_tags_id'] = $mrow ['archive_tags_id'];
		$this->model_activity_activity->addActivitySave ( 'addcurrentTagarchive', $data, 'query' );
	}
	public function updatecurrentTagarchive($tags_id, $notes_id) {
		$this->db->query ( "UPDATE `" . DB_PREFIX . "notes` SET is_tag = '" . ( int ) $tags_id . "', notes_conut ='0', form_type = '4' WHERE notes_id = '" . ( int ) $notes_id . "'" );
		
		$this->load->model ( 'activity/activity' );
		$data ['notes_id'] = $notes_id;
		$data ['tags_id'] = $tags_id;
		$this->model_activity_activity->addActivitySave ( 'updatecurrentTagarchive', $data, 'query' );
		
		// $this->dischargeFormAttach($tags_id, $notes_id);
	}
	public function updateTagimage($tags_id, $upload_file) {
		$this->load->model ( 'setting/tags' );
		$tag_info = $this->model_setting_tags->getTag ( $tags_id );
		
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $tag_info ['facilities_id'] );
		
		$this->load->model ( 'setting/timezone' );
		$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
		$facilitytimezone = $timezone_info ['timezone_value'];
		$timeZone = date_default_timezone_set ( $timezone_name );
		$date_added = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		
		$this->db->query ( "UPDATE `" . DB_PREFIX . "tags` SET upload_file = '" . $this->db->escape ( $upload_file ) . "', upload_file_thumb = '', modify_date = '" . $date_added . "' WHERE tags_id = '" . ( int ) $tags_id . "'" );
	}
	public function gettotaltagsextID($emp_extid) {
		$sql = "SELECT  count(*) as total FROM " . DB_PREFIX . "tags WHERE emp_extid = '" . $this->db->escape ( $emp_extid ) . "' ";
		$query = $this->db->query ( $sql );
		return $query->row ['total'];
	}
	public function gettotaltagsSSN($ssn) {
		$sql = "SELECT  count(*) as total FROM " . DB_PREFIX . "tags WHERE ssn = '" . $this->db->escape ( $ssn ) . "' ";
		$query = $this->db->query ( $sql );
		return $query->row ['total'];
	}
	public function gettotaltagName($emp_first_name, $emp_last_name) {
		$sql = "SELECT  count(*) as total FROM " . DB_PREFIX . "tags WHERE LOWER(emp_first_name) LIKE '%" . strtolower ( $this->db->escape ( $emp_first_name ) ) . "%' and LOWER(emp_last_name) LIKE '%" . strtolower ( $this->db->escape ( $emp_last_name ) ) . "%' ";
		$query = $this->db->query ( $sql );
		return $query->row ['total'];
	}
	public function gettotaltagDOB($dob, $emp_first_name, $emp_last_name) {
		$sql = "SELECT  count(*) as total FROM " . DB_PREFIX . "tags WHERE LOWER(emp_first_name) LIKE '%" . strtolower ( $this->db->escape ( $emp_first_name ) ) . "%' and LOWER(emp_last_name) LIKE '%" . strtolower ( $this->db->escape ( $emp_last_name ) ) . "%' and dob like '%" . $dob . "%' ";
		$query = $this->db->query ( $sql );
		return $query->row ['total'];
	}
	function getTagsbyNotesIDTagsrow($tags_id, $notes_id) {
		$query = $this->db->query ( "SELECT notes_tags_id,emp_tag_id,tags_id,notes_id,user_id,date_added,signature,signature_image,notes_pin,notes_type,facilities_id,is_census,lunch,dinner,breakfast,refused FROM `" . DB_PREFIX . "notes_tags` WHERE notes_id = '" . $notes_id . "' and tags_id = '" . $tags_id . "' " );
		return $query->row;
	}
	public function dischargeFormAttach($tags_id, $notes_id) {
		$this->load->model ( 'notes/notes' );
		$notes_info = $this->model_notes_notes->getNote ( $notes_id );
		$tags_info = $this->getTag ( $tags_id );
		if (! empty ( $notes_info )) {
			$data2 = array ();
			
			$data2 ['design_forms'] [0] [0] ['date_13302104'] = date ( 'm-d-Y', strtotime ( $notes_info ['date_added'] ) );
			$data2 ['design_forms'] [0] [0] ['date_38859539'] = date ( 'm-d-Y', strtotime ( $notes_info ['date_added'] ) );
			$data2 ['design_forms'] [0] [0] ['time_12989217'] = date ( 'h:i A', strtotime ( $notes_info ['date_added'] ) );
			
			$data2 ['design_forms'] [0] [0] ['text_41774561'] = $notes_info ['user_id'];
			
			$data23 = array ();
			$data23 ['forms_design_id'] = CUSTOME_DISCHARGE;
			$data23 ['notes_id'] = $notes_id;
			$data23 ['tags_id'] = $tags_id;
			$data23 ['facilities_id'] = $notes_info ['facilities_id'];
			
			$this->load->model ( 'form/form' );
			$formreturn_id = $this->model_form_form->addFormdata ( $data2, $data23 );
			
			$slq1 = "UPDATE " . DB_PREFIX . "forms SET tags_id = '" . $tags_id . "' where forms_id = '" . $formreturn_id . "'";
			$this->db->query ( $slq1 );
			
			$slq11 = "UPDATE " . DB_PREFIX . "notes SET is_forms = '1' where notes_id = '" . $notes_id . "'";
			$this->db->query ( $slq11 );
		}
	}
	public function getHiddenFields() {
		$result = array ();
		$query = $this->db->query ( "SELECT field_name FROM `" . DB_PREFIX . "hide_info` WHERE active = '1' AND is_encrypted = '1'" );
		foreach ( $query->rows as $row ) {
			$result [] = $row ['field_name'];
		}
		return $result;
	}
	public function getHiddenMaskedFields() {
		$result = array ();
		$query = $this->db->query ( "SELECT field_name FROM `" . DB_PREFIX . "hide_info` WHERE active = '1' AND is_masked = '1'" );
		foreach ( $query->rows as $row ) {
			$result [] = $row ['field_name'];
		}
		return $result;
	}
	public function formattedValue($value, $field_name) {
		/*
		 * $masked_fields = $this->getHiddenMaskedFields();
		 * $hidden_fields = $this->getHiddenFields();
		 * $this->load->model('api/encrypt');
		 *
		 * if(in_array($field_name,$masked_fields) || in_array($field_name,$hidden_fields)) {
		 *
		 * $add = '::'.$this->model_api_encrypt->encrypt($this->additional_enc);
		 * //var_dump($field_name);
		 * if(!empty($value) && !empty($field_name)) {
		 *
		 * if (strpos($value,$add) !== false ) {
		 * $temp_arr = explode('::',$value);
		 * $value = $temp_arr[0];
		 * if(strlen($this->model_api_encrypt->decrypt($value)) >= 4) {
		 * $formatted_value = str_repeat("*",strlen($this->model_api_encrypt->decrypt($value))-3).substr($this->model_api_encrypt->decrypt($value), (strlen($this->model_api_encrypt->decrypt($value))-3)-strlen($this->model_api_encrypt->decrypt($value)));
		 * }
		 * else {
		 * $formatted_value = str_repeat("*",strlen($this->model_api_encrypt->decrypt($value)));
		 * }
		 * if($this->session->data['show_hidden_info'] == 1) {
		 * $formatted_value = $this->model_api_encrypt->decrypt($value);
		 * }
		 * }
		 * else {
		 * $formatted_value = $value;
		 * if(in_array($field_name,$masked_fields) || in_array($field_name,$hidden_fields)) {
		 * if(strlen($value) >= 4) {
		 * $formatted_value = str_repeat("*",strlen($value)-3).substr($value, (strlen($value)-3)-strlen($value));
		 * }
		 * else {
		 * $formatted_value = str_repeat("*",strlen($value));
		 * }
		 * if($this->session->data['show_hidden_info'] == 1) {
		 * $formatted_value = $value;
		 *
		 * }
		 * }
		 * }
		 * }
		 * }
		 * else {
		 * $formatted_value = $value;
		 * }
		 */
		return $value;
	}
	public function gettotaltagsscreening($emp_extid) {
		$sql = "SELECT  count(*) as total FROM " . DB_PREFIX . "tags WHERE emp_extid = '" . $this->db->escape ( $emp_extid ) . "' ";
		$query = $this->db->query ( $sql );
		return $query->row ['total'];
	}
	public function getTagsbyAllName($data = array()) {
		$sql = "select DISTINCT tags_id,emp_tag_id,emp_first_name,emp_middle_name,emp_last_name,privacy,date_added,dob,locations_id,facilities_id,upload_file,tags_pin,gender,discharge,age,role_call,location_address,latitude,longitude,emp_extid,address_street2,person_screening,date_of_screening,ssn,state,city,zipcode,room,restriction_notes,prescription,alert_info,constant_sight,med_mental_health,tagstatus,forms_id,tags_forms_id,discharge_date,stickynote,customlistvalues_id,tags_status,tags_status_in,referred_facility,emergency_contact,reminder_time,reminder_date,upload_file_thumb,notes_id from `" . DB_PREFIX . "tags` ";
		
		$sql .= " where 1 = 1 and status = '1' and discharge='0' ";
		
		if ($data ['emp_first_name'] != null && $data ['emp_first_name'] != "") {
			$sql .= " and LOWER(emp_first_name) = '" . $this->db->escape ( strtolower ( $data ['emp_first_name'] ) ) . "' ";
		}
		if ($data ['emp_last_name'] != null && $data ['emp_last_name'] != "") {
			$sql .= " and LOWER(emp_last_name) = '" . $this->db->escape ( strtolower ( $data ['emp_last_name'] ) ) . "' ";
		}
		
		if ($data ['emp_extid'] != null && $data ['emp_extid'] != "") {
			$sql .= " and LOWER(emp_extid) = '" . $this->db->escape ( strtolower ( $data ['emp_extid'] ) ) . "' ";
		}
		
		if ($data ['ssn'] != null && $data ['ssn'] != "") {
			$sql .= " and LOWER(ssn) = '" . $this->db->escape ( strtolower ( $data ['ssn'] ) ) . "' ";
		}
		
		if ($data ['dob'] != null && $data ['dob'] != "") {
			$sql .= " and dob = '" . $data ['dob'] . "' ";
		}
		// echo $sql;
		$query = $this->db->query ( $sql );
		$result = $query->row;
		$keys = array_keys ( $result );
		foreach ( $keys as $key ) {
			$result [$key] = $this->formattedValue ( $result [$key], $key );
		}
		return $result;
		// return $query->row;
		/* */
	}
	public function getTagsbyAllNamedischage($data = array()) {
		$sql = "select DISTINCT tags_id,emp_tag_id,emp_first_name,emp_middle_name,emp_last_name,privacy,date_added,dob,locations_id,facilities_id,upload_file,tags_pin,gender,discharge,age,role_call,location_address,latitude,longitude,emp_extid,address_street2,person_screening,date_of_screening,ssn,state,city,zipcode,room,restriction_notes,prescription,alert_info,constant_sight,med_mental_health,tagstatus,forms_id,tags_forms_id,discharge_date,stickynote,customlistvalues_id,tags_status,tags_status_in,referred_facility,emergency_contact,reminder_time,reminder_date,upload_file_thumb,notes_id from `" . DB_PREFIX . "tags` ";
		
		$sql .= " where 1 = 1 and status = '1' and discharge='1' ";
		
		if ($data ['emp_first_name'] != null && $data ['emp_first_name'] != "") {
			$sql .= " and LOWER(emp_first_name) = '" . $this->db->escape ( strtolower ( $data ['emp_first_name'] ) ) . "' ";
		}
		if ($data ['emp_last_name'] != null && $data ['emp_last_name'] != "") {
			$sql .= " and LOWER(emp_last_name) = '" . $this->db->escape ( strtolower ( $data ['emp_last_name'] ) ) . "' ";
		}
		
		if ($data ['emp_extid'] != null && $data ['emp_extid'] != "") {
			$sql .= " and LOWER(emp_extid) = '" . $this->db->escape ( strtolower ( $data ['emp_extid'] ) ) . "' ";
		}
		
		if ($data ['ssn'] != null && $data ['ssn'] != "") {
			$sql .= " and LOWER(ssn) = '" . $this->db->escape ( strtolower ( $data ['ssn'] ) ) . "' ";
		}
		
		if ($data ['dob'] != null && $data ['dob'] != "") {
			$sql .= " and dob = '" . $data ['dob'] . "' ";
		}
		// echo $sql;
		$query = $this->db->query ( $sql );
		$result = $query->row;
		$keys = array_keys ( $result );
		foreach ( $keys as $key ) {
			$result [$key] = $this->formattedValue ( $result [$key], $key );
		}
		return $result;
		// return $query->row;
		/* */
	}
	public function updateTagimageenroll($tags_enroll_id, $FaceId, $ImageId) {
		$this->db->query ( "UPDATE `" . DB_PREFIX . "tags_enroll` SET FaceId = '" . $this->db->escape ( $FaceId ) . "',ImageId = '" . $this->db->escape ( $ImageId ) . "' WHERE tags_enroll_id = '" . ( int ) $tags_enroll_id . "'" );
		
		$this->load->model ( 'activity/activity' );
		$data ['FaceId'] = $FaceId;
		$data ['ImageId'] = $ImageId;
		$this->model_activity_activity->addActivitySave ( 'updateTagimageenroll', $data, 'query' );
	}
	public function insertTagimageenroll($tags_id, $FaceId, $ImageId, $s3file, $facilities_id) {
		$date_added = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		
		$tsql = "INSERT INTO " . DB_PREFIX . "tags_enroll SET enroll_image = '" . $this->db->escape ( $s3file ) . "',tags_id = '" . $this->db->escape ( $tags_id ) . "',FaceId = '" . $this->db->escape ( $FaceId ) . "', ImageId = '" . $this->db->escape ( $ImageId ) . "', date_added = '" . $date_added . "', date_updated = '" . $date_added . "' ";
		
		$this->db->query ( $tsql );
		
		$this->load->model ( 'activity/activity' );
		$data ['enroll_image'] = $enroll_image;
		$data ['FaceId'] = $FaceId;
		$data ['ImageId'] = $ImageId;
		$data ['date_updated'] = $date_added;
		$this->model_activity_activity->addActivitySave ( 'insertTagimageenroll', $data, 'query' );
	}
	public function getImage($tags_id) {
		$sql = "select DISTINCT tags_enroll_id,enroll_image,tags_id,upload_file_thumb,FaceId,ImageId from `" . DB_PREFIX . "tags_enroll` ";
		$sql .= " where 1 = 1 and status='0' ";
		$sql .= " and tags_id = '" . ( int ) $tags_id . "' ";
		
		$sql .= " ORDER BY tags_enroll_id DESC limit 0,1 ";
		
		$query = $this->db->query ( $sql );
		
		return $query->row;
	}
	public function getTagimages($tags_id) {
		$sql = "select DISTINCT tags_enroll_id,enroll_image,tags_id,upload_file_thumb,FaceId,ImageId from `" . DB_PREFIX . "tags_enroll` ";
		$sql .= " where 1 = 1 and status='0' ";
		$sql .= " and tags_id = '" . ( int ) $tags_id . "' ";
		
		$sql .= " ORDER BY tags_enroll_id DESC ";
		$query = $this->db->query ( $sql );
		return $query->rows;
	}
	public function deleteTagimage($tags_enroll_id) {
		$this->db->query ( "DELETE FROM `" . DB_PREFIX . "tags_enroll` WHERE tags_enroll_id = '" . ( int ) $tags_enroll_id . "'" );
	}
	function updateQuantityMedication($tags_medication_details_id, $drug_quantity) {
		$sql = "UPDATE `" . DB_PREFIX . "tags_medication_details` SET drug_mg='" . $this->db->escape ( $drug_quantity ) . "' WHERE tags_medication_details_id = '" . $tags_medication_details_id . "'";
		$this->db->query ( $sql );
	}
	function updatetagroom($room_id, $tags_id) {
		$sql = "UPDATE `" . DB_PREFIX . "tags` SET room='" . $room_id . "' WHERE tags_id = '" . $tags_id . "'";
		$this->db->query ( $sql );
	}
	function updatetagroomblank($tags_id) {
		$sql = "UPDATE `" . DB_PREFIX . "tags` SET room='0' WHERE tags_id = '" . $tags_id . "'";
		$this->db->query ( $sql );
	}
	function updatetagmed($tags_id, $medication_inout, $date_added) {
		$sql = "UPDATE `" . DB_PREFIX . "tags` SET medication_inout='" . $medication_inout . "', modify_date = '" . $date_added . "' WHERE tags_id = '" . $tags_id . "'";
		$this->db->query ( $sql );
	}
	function getINTags($data = array()) {
		
		// var_dump($data['in_client']);
		// die;
		$sql = "SELECT * FROM " . DB_PREFIX . "tags";
		
		$sql .= " where 1 = 1 and status = 1 and discharge='0' ";
		
		$facility_data = $this->model_facilities_facilities->getfacilities ( $data ['facilities_id'] );
		
		if ($facility_data ['is_master_facility'] == '1') {
			$this->load->model ( 'facilities/facilities' );
			$facility_info = $this->model_facilities_facilities->getfacilities ( $data ['facilities_id'] );
			
			$ddss = array ();
			if ($facility_info ['client_facilities_ids'] != null && $facility_info ['client_facilities_ids'] != "") {
				
				$ddss [] = $facility_info ['client_facilities_ids'];
				
				$ddss [] = $data ['facilities_id'];
				$sssssdd = implode ( ",", $ddss );
				
				$sql .= " and facilities_id in (" . $sssssdd . ") ";
				$faculities_ids = $sssssdd;
			} else {
				if ($data ['facilities_id'] != null && $data ['facilities_id'] != "") {
					$sql .= " and facilities_id = '" . $data ['facilities_id'] . "'";
					$n_facilities_id = $data ['facilities_id'];
				}
			}
		} else {
			
			if ($data ['facilities_id'] != null && $data ['facilities_id'] != "") {
				$sql .= " and facilities_id = '" . $data ['facilities_id'] . "'";
				$n_facilities_id = $data ['facilities_id'];
			}
		}
		if ($data ['in_client'] != null && $data ['in_client'] != "") {
			$sql .= " and role_call in (0," . $data ['in_client'] . ") ";
		}
		// var_dump($sql);die;
		
		$query = $this->db->query ( $sql );
		return $query->rows;
		
		/* SELECT * from dg_tags where role_call IN ('0','2') AND discharge='0' AND status='1' AND facilities_id IN ('47','52','50'); */
	}
	function getOUTTags($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "tags";
		
		$sql .= " where 1 = 1 and status = 1 and discharge='0' ";
		
		$facility_data = $this->model_facilities_facilities->getfacilities ( $data ['facilities_id'] );
		
		if ($facility_data ['is_master_facility'] == '1') {
			$this->load->model ( 'facilities/facilities' );
			$facility_info = $this->model_facilities_facilities->getfacilities ( $data ['facilities_id'] );
			
			$ddss = array ();
			if ($facility_info ['client_facilities_ids'] != null && $facility_info ['client_facilities_ids'] != "") {
				
				$ddss [] = $facility_info ['client_facilities_ids'];
				
				$ddss [] = $data ['facilities_id'];
				$sssssdd = implode ( ",", $ddss );
				
				$sql .= " and facilities_id in (" . $sssssdd . ") ";
				$faculities_ids = $sssssdd;
			} else {
				if ($data ['facilities_id'] != null && $data ['facilities_id'] != "") {
					$sql .= " and facilities_id = '" . $data ['facilities_id'] . "'";
					$n_facilities_id = $data ['facilities_id'];
				}
			}
		} else {
			
			if ($data ['facilities_id'] != null && $data ['facilities_id'] != "") {
				$sql .= " and facilities_id = '" . $data ['facilities_id'] . "'";
				$n_facilities_id = $data ['facilities_id'];
			}
		}
		
		if ($data ['out_client'] != null && $data ['out_client'] != "") {
			$sql .= " and role_call in (" . $data ['out_client'] . ") ";
		}
		
		$query = $this->db->query ( $sql );
		return $query->rows;
	}
	public function getMedicationCount($data = array()) {
		$sql = "SELECT COUNT(`drug_name`) FROM 
	" . DB_PREFIX . "tags_medication_details where `tags_id`='" . $data ['tags_id'] . "'  AND `status`='1' AND is_discharge='0'";
		
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	public function getSearchTags($data = array()) {
		$sql = "SELECT tags_id,emp_tag_id,emp_first_name,emp_middle_name,emp_last_name,privacy,date_added,dob,locations_id,facilities_id,upload_file,tags_pin,gender,discharge,age,role_call,location_address,latitude,longitude,emp_extid,address_street2,person_screening,date_of_screening,ssn,state,city,zipcode,room,restriction_notes,prescription,alert_info,constant_sight,med_mental_health,tagstatus,forms_id,tags_forms_id,discharge_date,stickynote,customlistvalues_id,tags_status,tags_status_in,referred_facility,emergency_contact,reminder_time,reminder_date,upload_file_thumb,medication_inout,notes_id,facility_move_id,facility_inout,movement_room,is_movement FROM `" . DB_PREFIX . "tags` ";
		$sql .= 'where (`emp_first_name` like "%' . $data ['data_tags'] . '%" or `emp_last_name` like "%' . $data ['data_tags'] . '%" or `dob` like "%' . $data ['data_tags'] . '%" or `emp_extid` like "%' . $data ['data_tags'] . '%" or `ssn` like "%' . $data ['data_tags'] . '%" or `emp_tag_id` like "%' . $data ['data_tags'] . '%")';
		
		if ($data ['is_master'] == '1') {
			$this->load->model ( 'facilities/facilities' );
			$facility_info = $this->model_facilities_facilities->getfacilities ( $data ['facilities_id'] );
			$ddss = array ();
			if ($facility_info ['client_facilities_ids'] != null && $facility_info ['client_facilities_ids'] != "") {
				
				$ddss [] = $facility_info ['client_facilities_ids'];
				
				$ddss [] = $data ['facilities_id'];
				
				if ($data ['is_submaster'] == '1') {
					$sssssddsg = explode ( ",", $facility_info ['client_facilities_ids'] );
					$abdcg = array_unique ( $sssssddsg );
					$cids = array ();
					foreach ( $abdcg as $fid ) {
						$cids [] = $fid;
					}
					$abdcgs = array_unique ( $cids );
					foreach ( $abdcgs as $fid2 ) {
						$facilityinfo = $this->model_facilities_facilities->getfacilities ( $fid2 );
						if ($facilityinfo ['client_facilities_ids'] != null && $facilityinfo ['client_facilities_ids'] != "") {
							$ddss [] = $facilityinfo ['client_facilities_ids'];
						}
					}
				}
				
				$sssssdd = implode ( ",", $ddss );
				$sql .= " and facilities_id in (" . $sssssdd . ") ";
				$faculities_ids = $sssssdd;
			} else {
				if ($data ['facilities_id'] != null && $data ['facilities_id'] != "") {
					$sql .= " and facilities_id = '" . $data ['facilities_id'] . "'";
					$n_facilities_id = $data ['facilities_id'];
				}
			}
		} else {
			
			if ($data ['facilities_id'] != null && $data ['facilities_id'] != "") {
				$sql .= " and facilities_id = '" . $data ['facilities_id'] . "'";
				$n_facilities_id = $data ['facilities_id'];
			}
		}
		
		if ($data ['facilities_id'] != null && $data ['facilities_id'] != "") {
			$sql .= " OR facility_move_id = '" . $data ['facilities_id'] . "'";
			$n_facility_move_id = $data ['facilities_id'];
		}
		
		if ($data ['room_id'] != null && $data ['room_id'] != "") {
			$sql .= " and room = '" . $data ['room_id'] . "'";
			$n_room_id = $data ['room_id'];
		}
		
		$sql .= "and  status = '1' ";
		
		if ($data ['wait_list'] != null && $data ['wait_list'] != "") {
			$sql .= " and tags_status_in = 'Wait listed' ";
			$n_tags_status_in = 'Wait listed';
		} else {
			if ($data ['all_record'] == "1") {
				$sql .= " and tags_status_in = 'Admitted' ";
				$n_tags_status_in = 'Admitted';
			}
		}
		
		if ($data ['rolecalls'] != null && $data ['rolecalls'] != "") {
			
			$sql .= " and role_call IN (" . $data ['rolecalls'] . ")";
		}
		
		if ($data ['discharge'] == "1") {
			$sql .= " and discharge = '0'";
			$n_discharge = '1';
		}
		
		if (isset ( $data ['start'] ) || isset ( $data ['limit'] )) {
			if ($data ['start'] < 0) {
				$data ['start'] = 0;
			}
			
			if ($data ['limit'] < 1) {
				$data ['limit'] = 20;
			}
			
			$sql .= " LIMIT " . ( int ) $data ['start'] . "," . ( int ) $data ['limit'];
			
			$n_start = $data ['start'];
			$n_limit = $data ['limit'];
		}
		
		$query = $this->db->query ( $sql );
		$results = $query->rows;
		
		foreach ( $results as $result ) {
			$keys = array_keys ( $result );
			foreach ( $keys as $key ) {
				$result [$key] = $this->formattedValue ( $result [$key], $key );
			}
			$resultsArr [] = $result;
		}
		return $resultsArr;
	}
	public function getAllStatus() {
		$sql = "SELECT * FROM `" . DB_PREFIX . "tag_status` where status='1'";
		
		$query = $this->db->query ( $sql );
		
		return $query->rows;
	}
	public function getTagsbyextID2($emp_extid1) {
		$emp_extid = substr ( $emp_extid1, 1 );
		
		$sql = "SELECT * FROM `" . DB_PREFIX . "tags` WHERE LOWER(tag_data like '%" . strtolower ( $emp_extid ) . "%' ) ";
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	
	
	public function getOutToCellTime($data) {
		//$sql = "SELECT sum(nt.status_total_time) AS totaltime FROM `" . DB_PREFIX . "notes_tags` AS nt INNER JOIN `" . DB_PREFIX . "tag_status` AS ts WHERE nt.tag_status_id=ts.tag_status_id AND nt.tags_id = '" . ( int ) $data['tags_id'] . "' and nt.date_added BETWEEN '" . $data ['currentdate'] . " 00:00:00 ' AND '" . $data ['currentdate'] . " 23:59:59' GROUP BY nt.tags_id";
		
		$sql = "SELECT sum(nt.status_total_time) AS totaltime FROM `" . DB_PREFIX . "notes_tags` AS nt INNER JOIN `" . DB_PREFIX . "tag_status` AS ts WHERE nt.tag_status_id=ts.tag_status_id AND nt.tags_id = '" . ( int ) $data['tags_id'] . "' "; 

		if($data['rules_operation'] ==1){
			
			$currentime = date('H:i:s');
			$rules_start_time = date ( 'H:i:s', strtotime ( $data ['rules_start_time'] ) );
			$rules_end_time = date ( 'H:i:s', strtotime ( $data ['rules_end_time'] ) );
			
			//var_dump($currentime);
			//var_dump($rules_start_time);
			//var_dump($rules_end_time);
			
			if ($currentime > $rules_start_time && $currentime < $rules_end_time){
				//echo 222;
				
				
				$sql .= "and nt.date_added BETWEEN '" . $data ['currentdate']. ' '. $currentime . "  ' AND '" . $data ['currentdate']. ' '. $rules_end_time .  "'";
			}else{
				if ($currentime > $rules_end_time){
					//echo 333;
					$sql .= "and nt.date_added BETWEEN '" . $data ['currentdate']. ' '. $rules_end_time . "  ' AND '" . $data ['currentdate']. " 23:59:59'";
				}else{
					//echo 444;
					$sql .= "and nt.date_added BETWEEN '" . $data ['currentdate']. " 00:00:00' AND '" . $data ['currentdate']. ' '. $rules_start_time .  "'";
				}
			}
			
			//$sql .= "and nt.date_added BETWEEN '" . $data ['currentdate']. ' '. $rules_start_time . "  ' AND '" . $data ['currentdate']. ' '. $rules_end_time .  "'";
		}else{
			$sql .= "and nt.date_added BETWEEN '" . $data ['currentdate'] . " 00:00:00 ' AND '" . $data ['currentdate'] . " 23:59:59'";
		}
		

		$sql .= " GROUP BY nt.tags_id";
		
		//echo $sql;
		//echo $sql; //die;
		//echo "<hr>";
		$query = $this->db->query ($sql);
		return $query->row;
		
	}
	
	
	
	public function getOutToTimes($data) {
		$sql = "Select dt.emp_first_name,dt.emp_last_name,dt.ssn,dt.emp_extid,dt.room,dt.race,
			dl.location_name,df.facility, dnt.status_total_time ,
			 dnt.tag_status_id, dnt.comments, dnt.tag_status_ids, 
			 (select abc.name from dg_tag_status abc  where abc.tag_status_id in ( select dd.tag_status_id from dg_notes_tags dd where dd.notes_id=dnt.notes_id)) as status_name1,
			 (select abc.name from dg_tag_status abc where abc.tag_status_id in ( select dd.tag_status_id from dg_notes_tags dd 
			 where dd.notes_id=dnt.move_notes_id )) as status_name2, (select dgnt.date_added from dg_notes dgnt where dgnt.notes_id=dnt.notes_id) as Event_Date, 
			 (select dgnt.notetime from dg_notes dgnt where dgnt.notes_id=dnt.move_notes_id) as NotesInTime, 
			 (select dgnt.notetime from dg_notes dgnt where dgnt.notes_id=dnt.notes_id) as NotesOutTime ,
			  (select dgnt.user_id from dg_notes dgnt where dgnt.notes_id=dnt.move_notes_id) as NotesInUserId, 
			 (select dgnt.user_id from dg_notes dgnt where dgnt.notes_id=dnt.notes_id) as NotesOutUserId,
			 (Select GROUP_CONCAT(cv.customlistvalues_name) 
			 from dg_customlistvalues cv
			join dg_notes nn 
			on find_in_set( cv.customlistvalues_id, nn.customlistvalues_id)>0
			 where nn.notes_id =dnt.move_notes_id ) as Customlistvalues
			 from dg_notes_tags dnt left outer join dg_tags dt on dt.tags_id= dnt.tags_id 
			 left outer join dg_tag_status dts on dnt.tag_status_id= dts.tag_status_id 
			 left outer join dg_notes dn on dt.notes_id= dn.notes_id 
			 left outer join dg_locations dl on dt.room= dl.locations_id 
			 ##left outer join  dg_customlistvalues cv on find_in_set( cv.customlistvalues_id, dn.customlistvalues_id)>0
			 left outer join dg_facilities df on dt.facilities_id= df.facilities_id
			where dts.out_from_cell=1 ";
		
		
		
		$this->load->model ( 'facilities/facilities' );
		$facility_info = $this->model_facilities_facilities->getfacilities ( $data ['facilities_id'] );
		$ddss = array ();
		
		if($data['facilities_id']!=""){
			if ($facility_info ['client_facilities_ids'] != null && $facility_info ['client_facilities_ids'] != "") {
			
				$ddss [] = $facility_info ['client_facilities_ids'];
				
				$ddss [] = $data ['facilities_id'];
				
				if ($data ['is_submaster'] == '1') {
					$sssssddsg = explode ( ",", $facility_info ['client_facilities_ids'] );
					$abdcg = array_unique ( $sssssddsg );
					$cids = array ();
					foreach ( $abdcg as $fid ) {
						$cids [] = $fid;
					}
					$abdcgs = array_unique ( $cids );
					foreach ( $abdcgs as $fid2 ) {
						$facilityinfo = $this->model_facilities_facilities->getfacilities ( $fid2 );
						if ($facilityinfo ['client_facilities_ids'] != null && $facilityinfo ['client_facilities_ids'] != "") {
							$ddss [] = $facilityinfo ['client_facilities_ids'];
						}
					}
				}
				
				$ddss = array_unique ( $ddss );
				$sssssdd = implode ( ",", $ddss );
				$sql .= " and dnt.facilities_id in (" . $sssssdd . ") ";
				
			}else{
				$sql .= " and dnt.facilities_id = '". $data['facilities_id'] ."'";
			}
		}
		
		if ($data ['currentdate'] != '' && $data ['currentdate'] != '') {
			$sql .= " and dnt.date_added BETWEEN '" . $data ['currentdate'] . " 00:00:00 ' AND '" . $data ['currentdate'] . " 23:59:59' ";
		}
		
		if ($data ['note_date_from'] != '' && $data ['note_date_to'] != '') {
			$sql .= " and dnt.date_added BETWEEN  '" . $data ['note_date_from'] . "' AND  '" . $data ['note_date_to'] . " 23:59:59' ";
		}
		
		if ($data ['shift_id'] != '' && $data ['shift_id'] != '') {
			$sql .= " and dn.shift_id = '" . $data ['shift_id'] . "' ";
		}
		
		if ($data ['tags_ids'] != '' && $data ['tags_ids'] != '') {
			$sql .= " and dnt.tags_id IN (" . $data ['tags_ids'] . ") ";
		}
		
		if ($data ['status_ids'] != '' && $data ['status_ids'] != '') {
			$sql .= " and dnt.tag_status_id IN (" . $data ['status_ids'] . ") ";
		}
		
		
		//$sql .=" GROUP BY nt.tags_id";
		
		
		$query = $this->db->query ($sql);
		return $query->rows;
	}
	
	public function getOutToCellTimes($data) {
		$sql = "SELECT nt.*,ts.name,ts.type,ts.rule_action_content FROM `" . DB_PREFIX . "notes_by_tracktime` AS nt INNER JOIN `" . DB_PREFIX . "tag_status` AS ts WHERE nt.tag_status_id=ts.tag_status_id and ts.out_from_cell=1 AND nt.tags_id = '" . ( int ) $data['tags_id'] . "' ";
		//$sql = "SELECT nt.*,ts.name,ts.type,ts.rule_action_content FROM `" . DB_PREFIX . "notes_by_tracktime` nt LEFT JOIN " . DB_PREFIX . "tag_status` ts ON nt.tag_status_id=ts.tag_status_id where ts.out_from_cell=1 AND nt.tags_id = '" . ( int ) $data['tags_id'] . "' ";
		
		if ($data ['currentdate'] != '' && $data ['currentdate'] != '') {
			$sql .= " and nt.date_added BETWEEN '" . $data ['currentdate'] . " 00:00:00 ' AND '" . $data ['currentdate'] . " 23:59:59' ";
		}
		
		if ($data ['note_date_from'] != '' && $data ['note_date_to'] != '') {
			$sql .= " and nt.date_added BETWEEN  '" . $data ['note_date_from'] . "' AND  '" . $data ['note_date_to'] . " 23:59:59' ";
		}
		
		
		$query = $this->db->query ($sql);
		return $query->rows;
	}
	
	
	public function getHourOutProgress($data) {
		
		$hourout='';
		$inPercent ='';
		$totaltime = $data['totaltime'];
		$start_date = new DateTime ( $data ['date_added'] );
		$since_start = $start_date->diff ( new DateTime ( $data['date_a'] ) );
		$status_total_time = 0;
		if ($since_start->y > 0) {
			$status_total_time = 60 * 24 * 365 * $since_start->y;
		}
		
		if ($since_start->m > 0) {
			$status_total_time += 60 * 24 * 30 * $since_start->m;
		}
		
		if ($since_start->d > 0) {
			$status_total_time += 60 * 24 * $since_start->d;
		}
		
		if ($since_start->h > 0) {
			$status_total_time += 60 * $since_start->h;
		}
		
		if ($since_start->i > 0) {
			$status_total_time += $since_start->i;
		}
		
		//var_dump($data['out_the_sell']);
		
		$totaltime = $totaltime + $status_total_time;
		$out_the_sell=0;
		if(isset($data['duration_type']) && $data['duration_type']==1){
			$out_the_sell = $data['out_the_sell'];
		}else if(isset($data['duration_type']) && $data['duration_type']==2){
			$out_the_sell = $data['out_the_sell']*60;
		}else if(isset($data['duration_type']) && $data['duration_type']==3){
			$out_the_sell = $data['out_the_sell']*60*24;
		}	

		//echo '<pre>'; print_r($out_the_sell); echo '</pre>'; 
		
		$seconds = $totaltime*60;

		$total_in_minutes= floor($seconds/60);
		$inPercent = floor(($total_in_minutes*100)/$out_the_sell);
		$days = 0;
		$hours = 0;
		$minutes=0;
		$secondss=0;
		$days = floor($seconds / 86400);
		$hours = floor(($seconds - ($days * 86400)) / 3600);
		$minutes = floor(($seconds - ($days * 86400) - ($hours * 3600))/60);
		$secondss = floor(($seconds - ($days * 86400) - ($hours * 3600) - ($minutes*60)));
		
		if($days>0){
			if($days>1){
				$hourout .= rtrim($days, '0').' Days ';
			}else{
				$hourout .= rtrim($days, '0').' Day ';
			}	
		}

		if($hours>0){
			if($hours>1){
				$hourout .= rtrim($hours, '0').' Hours ';
			}else{
				$hourout .= rtrim($hours, '0').' Hour ';
			}
		}

		if($minutes>0){
			if($minutes>1){
				$hourout .= rtrim($minutes, '0').' Minutes ';
			}else{
				$hourout .= rtrim($minutes, '0').' Minute ';
			}
		}

		if($secondss>0){
			if($secondss>1){
				$hourout .= rtrim($secondss, '0').' Seconds';
			}else{
				$hourout .= rtrim($secondss, '0').' Second';
			}
		}

		$response = array();
		$response['hourout'] = $hourout;
		$response['inPercent'] = $inPercent;


		return $response;
	}
	

	public function getHourOutCount($data){
		
		$sql = "SELECT distinct t.*, ts.tag_status_id,ts.rule_action_content, ts.name,ts.type, ts.color_code, ts.image, ts.facility_type, ts.is_facility, ts.status_type, f.facility

			FROM `". DB_PREFIX ."tags` t 

			LEFT JOIN `" . DB_PREFIX ."tag_status` ts ON ts.tag_status_id = t.role_call 

			LEFT JOIN `" . DB_PREFIX . "facilities` f ON f.facilities_id = t.facilities_id 

			where 1 = 1   ";
			
			if ($data ['rolecalls'] != '' && $data ['rolecalls'] != '') {
				$sql .= " and t.role_call IN (" . $data['rolecalls'] . ") ";
			}

			$this->load->model ( 'facilities/facilities' );
			$facility_info = $this->model_facilities_facilities->getfacilities ( $data ['facilities_id'] );
			$ddss = array ();
			
			if($data['facilities_id']!=""){
				if ($facility_info ['client_facilities_ids'] != null && $facility_info ['client_facilities_ids'] != "") {
				
					$ddss [] = $facility_info ['client_facilities_ids'];
					
					$ddss [] = $data ['facilities_id'];
					
					if ($data ['is_submaster'] == '1') {
						$sssssddsg = explode ( ",", $facility_info ['client_facilities_ids'] );
						$abdcg = array_unique ( $sssssddsg );
						$cids = array ();
						foreach ( $abdcg as $fid ) {
							$cids [] = $fid;
						}
						$abdcgs = array_unique ( $cids );
						foreach ( $abdcgs as $fid2 ) {
							$facilityinfo = $this->model_facilities_facilities->getfacilities ( $fid2 );
							if ($facilityinfo ['client_facilities_ids'] != null && $facilityinfo ['client_facilities_ids'] != "") {
								$ddss [] = $facilityinfo ['client_facilities_ids'];
							}
						}
					}
					
					$ddss = array_unique ( $ddss );
					$sssssdd = implode ( ",", $ddss );
					$sql .= " and t.facilities_id in (" . $sssssdd . ") ";
					
				}else{
					$sql .= " and t.facilities_id = '". $data['facilities_id'] ."'";
				}
			}

			$sql .= " and t.facility_inout = '0' and t.discharge = '0' and t.tags_status_in = 'Admitted' and t.status = '1' ";
			
			$query = $this->db->query ($sql);
			return $query->rows;
	}
	
	
	public function gettagsattachmets($data) {
		
		if ($data ['tags_ids'] != NULL && $data ['tags_ids'] != "") {
			$sql = "SELECT DISTINCT  nm.* 
				FROM dg_notes_tags n, dg_notes_media nm where nm.notes_id=n.notes_id 
				and  nm.notes_file is not null ";
			
			$sql .= " and n.tags_id in (" . $data ['tags_ids'] . ") ";
			
			
			if ($data ['case_number'] != '' && $data ['case_number'] != '') {
				$sql .= " and nm.case_number = '". $data['case_number'] ."'";
			}
			
			
			$sql .= " ORDER BY n.date_added";
			$sql .= " DESC";
			
			//echo $sql;
		
			$query = $this->db->query ( $sql );
			
			return $query->rows;
		}
	}
	
	public function updateTagStatusIds($data) {
		$this->load->model ( 'setting/tags' );
		$tag_info = $this->model_setting_tags->getTag ( $data ['tags_id'] );
		
		$this->load->model ( 'facilities/facilities' );
		$facilities_info = $this->model_facilities_facilities->getfacilities ( $tag_info ['facilities_id'] );
		
		$this->load->model ( 'setting/timezone' );
		$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
		$facilitytimezone = $timezone_info ['timezone_value'];
		$timeZone = date_default_timezone_set ( $timezone_name );
		$date_added = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		
		$sql = "UPDATE `" . DB_PREFIX . "tags` SET tag_status_ids = '" . $this->db->escape ( $data ['tag_status_ids'] ) . "', role_call = '" . $this->db->escape ( $data ['tag_status_id'] ) . "', modify_date = '" . $date_added . "' WHERE tags_id = '" . $this->db->escape ( $data ['tags_id'] ) . "'"; 
		
		$this->db->query ( $sql ); 
		
		$this->load->model ( 'activity/activity' );
		$this->model_activity_activity->addActivitySave ( 'updateTagStatusIds', $data, 'query' );
	}
	
	
	public function getTagStatus($tag_status_id) {
		$sql = "SELECT tag_status_id,name,image FROM `" . DB_PREFIX . "tag_status` WHERE tag_status_id = '" . ( int ) $tag_status_id . "'";
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	
	
	public function gettagsSSN($ssn) {
		$sql = "SELECT * FROM " . DB_PREFIX . "tags WHERE ssn = '" . $this->db->escape ( $ssn ) . "' and discharge = '0' ";
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	
	public function gettagsTimes($data = array()) {
		$tagtimes = array();
		$tagtimes1 = array();
		
		if ($data ['type'] == '1') {
			//$ssql = "SELECT nt.*,ts.name,ts.image,ts.rule_action_content, sum(nt.status_total_time) as total_time FROM `" . DB_PREFIX . "notes_tags` AS nt INNER JOIN `" . DB_PREFIX . "tag_status` AS ts WHERE nt.tag_status_id=ts.tag_status_id and ts.out_from_cell=1 and nt.tags_id = '".(int)$data['tags_id']."' ";
			$ssql = "SELECT nt.*,ts.name,ts.image,ts.rule_action_content FROM `" . DB_PREFIX . "notes_tags` AS nt INNER JOIN `" . DB_PREFIX . "tag_status` AS ts WHERE nt.tag_status_id=ts.tag_status_id and ts.out_from_cell=1 and nt.tags_id = '".(int)$data['tags_id']."' ";
			
			if ($data ['currentdate'] != '' && $data ['currentdate'] != '') {
				$ssql .= " and nt.date_added BETWEEN '" . $data ['currentdate'] . " 00:00:00 ' AND '" . $data ['currentdate'] . " 23:59:59' ";
			}
			//$ssql .= " group by tag_status_id order by nt.notes_tags_id DESC ";
			$ssql .= " order by nt.notes_tags_id DESC ";
		}else{
			$ssql = "SELECT nt.tag_status_id,sum(nt.status_total_time),ts.name,ts.image,ts.rule_action_content 
				FROM `" . DB_PREFIX . "notes_tags` AS nt 
				INNER JOIN `" . DB_PREFIX . "tag_status` AS ts 
				WHERE nt.tag_status_id=ts.tag_status_id and ts.out_from_cell=1 
				and nt.tags_id = '".(int)$data['tags_id']."' ";
				
				$ssql .= " and nt.date_added BETWEEN '" . $data ['currentdate'] . " 00:00:00 ' AND '" . $data ['currentdate'] . " 23:59:59' ";
				$ssql .= " group by nt.tag_status_id order by nt.notes_tags_id DESC";
		}
		
		$query3 = $this->db->query($ssql);
		
		
		foreach($query3->rows as $rowd){
			
			$status_totaltime = 0;
			
			
			if($rowd['status_total_time'] > 0){
				$status_totaltime = $rowd['status_total_time'];
			}else{
				//var_dump($data ['role_call']);
				//var_dump($rowd ['tag_status_id']);
				if($data ['tags_type'] != '2'){
					if($data['role_call'] == $rowd['tag_status_id']){
						$noesData = $this->model_notes_notes->getnotes($rowd['notes_id']);
						$date_added = $noesData ['date_added'];
						
						$start_date = new DateTime ( $date_added );
						$since_start = $start_date->diff ( new DateTime ( $data['date_a'] ) );
						
						
						if ($since_start->y > 0) {
							$status_totaltime = 60 * 24 * 365 * $since_start->y;
						}
						
						if ($since_start->m > 0) {
							$status_totaltime += 60 * 24 * 30 * $since_start->m;
						}
						
						if ($since_start->d > 0) {
							$status_totaltime += 60 * 24 * $since_start->d;
						}
						
						if ($since_start->h > 0) {
							$status_totaltime += 60 * $since_start->h;
						}
						
						if ($since_start->i > 0) {
							$status_totaltime += $since_start->i;
						}
						if ($since_start->s > 0) {
							$status_totaltime += $since_start->s/60;
						}
					}
				}
			}
			
			$out_the_sell=0;
			$inPercent = 0;
			
			if($data['out_the_sell'] != null && $data['out_the_sell'] != ""){
				if(isset($data['duration_type']) && $data['duration_type']==1){
					$out_the_sell = $data['out_the_sell'];
				}else if(isset($data['duration_type']) && $data['duration_type']==2){
					$out_the_sell = $data['out_the_sell']*60;
				}else if(isset($data['duration_type']) && $data['duration_type']==3){
					$out_the_sell = $data['out_the_sell']*60*24;
				}
			}else{
				
				$ruleaction_content = unserialize ( $rowd ['rule_action_content'] );
				
				if(isset($ruleaction_content['duration_type']) && $ruleaction_content['duration_type']==1){
					$out_the_sell = $ruleaction_content['out_the_sell'];
				}else if(isset($ruleaction_content['duration_type']) && $ruleaction_content['duration_type']==2){
					$out_the_sell = $ruleaction_content['out_the_sell']*60;
				}else if(isset($ruleaction_content['duration_type']) && $ruleaction_content['duration_type']==3){
					$out_the_sell = $ruleaction_content['out_the_sell']*60*24;
				}
				
			}
			
			$seconds1 = $status_totaltime*60;
			$total_in_minutes2= floor($seconds1/60);
			
			//var_dump($total_in_minutes2);
			//var_dump($out_the_sell);
			//$inPercent = floor(($total_in_minutes2*100)/$out_the_sell);
			$inPercent = 0;
			
			$outcelltimtime1 = $this->secondsToTime($status_totaltime*60);
		
			$tagtimes [] = array (
				'name' => $rowd['name'],
				'image' => $rowd['image'],
				//'notes_description' => $noteinfo['notes_description'],
				'outcelltimtime' => $outcelltimtime1,
				'inPercent' => $inPercent,
				'status_total_time' => $status_totaltime,
				
			);
		}
		
		
		//var_dump($tagtimes);
		//die;
		/*
		if(!empty($query3->row)){
			$notes_id = $query3->row['notes_id'];
			$noesData = $this->model_notes_notes->getnotes($notes_id);
			$date_added = $noesData ['date_added'];
			
			
			$start_date = new DateTime ( $date_added );
			$since_start = $start_date->diff ( new DateTime ( $data['date_a'] ) );
			$status_totaltime = 0;
			if ($since_start->y > 0) {
				$status_totaltime = 60 * 24 * 365 * $since_start->y;
			}
			
			if ($since_start->m > 0) {
				$status_totaltime += 60 * 24 * 30 * $since_start->m;
			}
			
			if ($since_start->d > 0) {
				$status_totaltime += 60 * 24 * $since_start->d;
			}
			
			if ($since_start->h > 0) {
				$status_totaltime += 60 * $since_start->h;
			}
			
			if ($since_start->i > 0) {
				$status_totaltime += $since_start->i;
			}
			if ($since_start->s > 0) {
				$status_totaltime += $since_start->s/60;
			}
			
			$out_the_sell=0;
			$inPercent = 0;
			if(isset($data['duration_type']) && $data['duration_type']==1){
				$out_the_sell = $data['out_the_sell'];
			}else if(isset($data['duration_type']) && $data['duration_type']==2){
				$out_the_sell = $data['out_the_sell']*60;
			}else if(isset($data['duration_type']) && $data['duration_type']==3){
				$out_the_sell = $data['out_the_sell']*60*24;
			}
			
			$seconds1 = $status_totaltime*60;
			$total_in_minutes2= floor($seconds1/60);
			$inPercent = floor(($total_in_minutes2*100)/$out_the_sell);
			
			$outcelltimtime1 = $this->secondsToTime($status_totaltime*60);
		
			$tagtimes [] = array (
				'name' => $query3->row['name'],
				'image' => $query3->row['image'],
				//'notes_description' => $noteinfo['notes_description'],
				'outcelltimtime' => $outcelltimtime1,
				'inPercent' => $inPercent,
				'status_total_time' => $status_totaltime,
				
			);
		}
		
		
		$houroutdata1 = array();
		$houroutdata1['tags_id'] = $data ['tags_id'];
		$houroutdata1['currentdate'] = $data ['currentdate'];
		$houroutdata1['rules_operation'] = $data ['rules_operation'];
		$houroutdata1['rules_start_time'] = $data ['rules_start_time'];
		$houroutdata1['rules_end_time'] = $data ['rules_end_time'];
		$utcelltimes = $this->getOutToCellTimes ( $houroutdata1 );
		
		
		$alltotaltime = 0;
		$status_total_time = 0;
		$inPercent = 0;
		foreach($utcelltimes as $sttime){
			
			$rule_action_content = unserialize($sttime['rule_action_content']);
			
			$status_total_time1 = 0;
			if ($sttime['years'] > 0) {
				$status_total_time1 = 60 * 24 * 365 * $sttime['years'] ;
			}

			if ($sttime['months'] > 0) {
				$status_total_time1 += 60 * 24 * 30 * $sttime['months'];
			}

			if ($sttime['days'] > 0) {
				$status_total_time1 += 60 * 24 * $sttime['days'];
			}

			if ($sttime['hours'] > 0) {
				$status_total_time1 += 60 * $sttime['hours'];
			}
			
			if ($sttime['minutes'] > 0) {
				$status_total_time1 += $sttime['minutes'];
			}
			
		
			$outcelltimtime = $this->secondsToTime($status_total_time1*60);
			
			
			$out_the_sell=0;
			if(isset($data['duration_type']) && $data['duration_type']==1){
				$out_the_sell = $data['out_the_sell'];
			}else if(isset($data['duration_type']) && $data['duration_type']==2){
				$out_the_sell = $data['out_the_sell']*60;
			}else if(isset($data['duration_type']) && $data['duration_type']==3){
				$out_the_sell = $data['out_the_sell']*60*24;
			}
			
			
			$seconds = $status_total_time1*60;
			$total_in_minutes= floor($seconds/60);
			$inPercent = floor(($total_in_minutes*100)/$out_the_sell);
			
			
			//$noteinfo = $this->model_notes_notes->getnotes ( $sttime ['notes_id'] );
			$tagtimes [] = array (
				'name' => $sttime['name'],
				'image' => $sttime['image'],
				//'notes_description' => $noteinfo['notes_description'],
				'outcelltimtime' => $outcelltimtime,
				'inPercent' => $inPercent,
				'status_total_time' => $status_total_time1,
				
			);
		}
		*/
		
		return $tagtimes;
	}
	
	
	public function secondsToTime($seconds) {
		$dtF = new \DateTime('@0');
		$dtT = new \DateTime("@$seconds");
		
		$since_start = $dtF->diff($dtT);
		$caltime = "";
		if ($since_start->y > 0) {
			$caltime .= $since_start->y . ' Years ';
		}

		if ($since_start->m > 0) {
			$caltime .= $since_start->m . ' Months ';
		}

		if ($since_start->d > 0) {
			$caltime .= $since_start->d . ' Days ';
		}

		if ($since_start->h > 0) {
			$caltime .= $since_start->h . ' Hour(s) ';
		}
		
		if ($since_start->i > 0) {
			$caltime .= $since_start->i . ' Minutes ';
		}
		
		return $caltime;
	}
	
	
	public function getOutToTimebyid($data) {
		$sql = "SELECT nt.* FROM `" . DB_PREFIX . "notes_tags` AS nt WHERE 1 = 1 ";
		
		if ($data ['currentdate'] != '' && $data ['currentdate'] != '') {
			$sql .= " and nt.date_added BETWEEN '" . $data ['currentdate'] . " 00:00:00 ' AND '" . $data ['currentdate'] . " 23:59:59' ";
		}
		if ($data ['tags_id'] != '' && $data ['tags_id'] != '') {
			$sql .= " and nt.tags_id = '" . $data ['tags_id'] . "' ";
		}
		if ($data ['fixed_status_id'] != '' && $data ['fixed_status_id'] != '') {
			$sql .= " and nt.fixed_status_id = '" . $data ['fixed_status_id'] . "' ";
		}
		
		if ($data ['note_date_from'] != '' && $data ['note_date_to'] != '') {
			$sql .= " and nt.date_added BETWEEN  '" . $data ['note_date_from'] . "' AND  '" . $data ['note_date_to'] . " 23:59:59' ";
		}
		
		$sql .=" limit 0,1";
		
		
		
		$query = $this->db->query ($sql);
		return $query->row;
	}

}

?>