<?php
class Modelnotestags extends Model {
	private $additional_enc = 'noteactive';
	public function getTag($tags_id) {
		$query = $this->db->query("SELECT tags_id,emp_tag_id,emp_first_name,emp_last_name,privacy,date_added,dob,locations_id,facilities_id,upload_file,tags_pin,gender,discharge,age,role_call,location_address,latitude,longitude,emp_extid,address_street2,person_screening,date_of_screening,ssn,state,city,zipcode,room,restriction_notes,prescription,alert_info,constant_sight,med_mental_health,tagstatus,forms_id,tags_forms_id,discharge_date,stickynote,customlistvalues_id,tags_status,tags_status_in,referred_facility FROM `" . DB_PREFIX . "tags` WHERE tags_id = '" . (int)$tags_id . "'");

		$result = $query->row;
		$keys = array_keys($result);
		foreach($keys as $key) {
			$result[$key] = $this->formattedValue($result[$key],$key);
		}
		return $result;
		
		//return $query->row;
	}
	
	public function getTagbyEMPID($emp_tag_id) {
		
		$sql = "SELECT tags_id,emp_tag_id,emp_first_name,emp_last_name,privacy,date_added,dob,locations_id,facilities_id,upload_file,tags_pin,gender,discharge,age,role_call,location_address,latitude,longitude,emp_extid,address_street2,person_screening,date_of_screening,ssn,state,city,zipcode,room,restriction_notes,prescription,alert_info,constant_sight,med_mental_health,tagstatus,forms_id,tags_forms_id,discharge_date,stickynote,customlistvalues_id,tags_status,tags_status_in,referred_facility FROM `" . DB_PREFIX . "tags` WHERE emp_tag_id = '" . $emp_tag_id . "' and privacy ='2' ";
		$query = $this->db->query($sql);
		
		$result = $query->row;
		$keys = array_keys($result);
		foreach($keys as $key) {
			$result[$key] = $this->formattedValue($result[$key],$key);
		}
		return $result;

		//return $query->row;
	}

	public function getTags($data = array()) {
		$sql = "SELECT tags_id,emp_tag_id,emp_first_name,emp_last_name,privacy,date_added,dob,locations_id,facilities_id,upload_file,tags_pin,gender,discharge,age,role_call,location_address,latitude,longitude,emp_extid,address_street2,person_screening,date_of_screening,ssn,state,city,zipcode,room,restriction_notes,prescription,alert_info,constant_sight,med_mental_health,tagstatus,forms_id,tags_forms_id,discharge_date,stickynote,customlistvalues_id,tags_status,tags_status_in,referred_facility FROM `" . DB_PREFIX . "tags`";

		$sql .= 'where 1 = 1 ';
		
		if ($data['emp_tag_id'] != null && $data['emp_tag_id'] != "") {
			$sql .= " and emp_tag_id like '%".$data['emp_tag_id']."%'";
			$sql .= " or LOWER(emp_first_name) like '%".strtolower($data['emp_tag_id'])."%'";
			$sql .= " or LOWER(emp_first_name) like '%".strtolower($data['emp_tag_id'])."%'";
		}
		
		if ($data['emp_first_name'] != null && $data['emp_first_name'] != "") {
			$sql .= " and emp_first_name like '%".$data['emp_first_name']."%'";
		}
		if ($data['emp_last_name'] != null && $data['emp_last_name'] != "") {
			$sql .= " and emp_last_name like '%".$data['emp_last_name']."%'";
		}
		
		if ($data['privacy'] != null && $data['privacy'] != "") {
			$sql .= " and privacy = '".$data['privacy']."'";
		}
		if ($data['status'] != null && $data['status'] != "") {
			//$sql .= " and status = '".$data['status']."'";
		}
		$sql .= " and status = '1'";
		$sort_data = array(
			'emp_tag_id',
			'status',
			'emp_first_name',
			'emp_last_name',
			'privacy',
			'sort_order',
			'modify_date',
			'date_added'
		);	

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY sort_order";	
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}			

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		
		$query = $this->db->query($sql);
		
		$results = $query->rows;
		foreach($results as $result) {
			$keys = array_keys($result);
			foreach($keys as $key) {
				$result[$key] = $this->formattedValue($result[$key],$key);
			}
			$resultsArr[] = $result;
		}
		return $resultsArr;

		//return $query->rows;
	}

	public function getTotalTags($data = array()) {
		$sql .= 'where 1 = 1 ';
		
		
		if ($data['emp_tag_id'] != null && $data['emp_tag_id'] != "") {
			$sql .= " and emp_tag_id like '%".$data['emp_tag_id']."%'";
			$sql .= " or LOWER(emp_first_name) like '%".strtolower($data['emp_tag_id'])."%'";
			$sql .= " or LOWER(emp_first_name) like '%".strtolower($data['emp_tag_id'])."%'";
		}
		
		if ($data['emp_first_name'] != null && $data['emp_first_name'] != "") {
			$sql .= " and emp_first_name like '%".$data['emp_first_name']."%'";
		}
		if ($data['emp_last_name'] != null && $data['emp_last_name'] != "") {
			$sql .= " and emp_last_name like '%".$data['emp_last_name']."%'";
		}
		
		if ($data['privacy'] != null && $data['privacy'] != "") {
			$sql .= " and privacy = '".$data['privacy']."'";
		}
		if ($data['status'] != null && $data['status'] != "") {
			$sql .= " and status = '".$data['status']."'";
		}
		
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "tags` ".$sql."");

		return $query->row['total'];
	}
	
	public function getHiddenFields() {
		$result = array();
		$query = $this->db->query("SELECT field_name FROM `" . DB_PREFIX . "hide_info` WHERE active = '1' AND is_encrypted = '1'");
		foreach($query->rows as $row) {
			$result[] = $row['field_name'];
		}
		return $result;
	}
	public function getHiddenMaskedFields() {
		$result = array();
		$query = $this->db->query("SELECT field_name FROM `" . DB_PREFIX . "hide_info` WHERE active = '1' AND is_masked = '1'");
		foreach($query->rows as $row) {
			$result[] = $row['field_name'];
		}
		return $result;
	}
	
	public function formattedValue($value,$field_name) {
		$masked_fields = $this->getHiddenMaskedFields();
		$hidden_fields = $this->getHiddenFields();
		$this->load->model('api/encrypt');
		$add = '::'.$this->model_api_encrypt->encrypt($this->additional_enc);
		if(in_array($field_name,$masked_fields) || in_array($field_name,$hidden_fields)) {	
			if(!empty($value) && !empty($field_name)) {
				if (strpos($value,$add) !== false ) {
					$temp_arr = explode('::',$value);
					$value = $temp_arr[0]; 
					if(strlen($this->model_api_encrypt->decrypt($value)) >= 4) {
						$formatted_value = str_repeat("*",strlen($this->model_api_encrypt->decrypt($value))-3).substr($this->model_api_encrypt->decrypt($value), (strlen($this->model_api_encrypt->decrypt($value))-3)-strlen($this->model_api_encrypt->decrypt($value)));
					}
					else {
						$formatted_value = str_repeat("*",strlen($this->model_api_encrypt->decrypt($value)));
					}
					if($this->session->data['show_hidden_info'] == 1) {
						$formatted_value = $this->model_api_encrypt->decrypt($value);
					}
				}
				else {
					$formatted_value = $value;
					if(in_array($field_name,$masked_fields) || in_array($field_name,$hidden_fields)) {
						if(strlen($value) >= 4) {
							$formatted_value = str_repeat("*",strlen($value)-3).substr($value, (strlen($value)-3)-strlen($value));
						}
						else {
							$formatted_value = str_repeat("*",strlen($value));
						}
						if($this->session->data['show_hidden_info'] == 1) {
							$formatted_value = $value;
							if($field_name=='contant_sight') {
								echo $formatted_value; exit;
							}
						}
					}
				}
			}
		}
		else {
			$formatted_value = $value;
		}
		return $formatted_value;
	}

	public function getLocationNamebyLocationId($data=array()){          


		$query = $this->db->query("SELECT location_name FROM `" . DB_PREFIX . "locations` WHERE locations_id = '".$data['locations_id']."' AND facilities_id = '".$data['facilities_id']."'");

		$result = $query->row;
		return $result;
	}
	
	public function getnotesmultiKey($notes_id){
		$sql .= "SELECT * FROM `" . DB_PREFIX . "notes_by_multikeyword` WHERE notes_id = '" . $notes_id . "' ";
		
		$query = $this->db->query ( $sql );
		return $query->rows;
		
	}
}
?>