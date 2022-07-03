<?php
class ModelUserUser extends Model {
	public function getactivation($n = 2) {
		$characters = 'abcdefghjkmnpqrstuvwxyz';
		$randomString = '';
		
		for($i = 0; $i < $n; $i ++) {
			$index = rand ( 0, strlen ( $characters ) - 1 );
			$randomString .= $characters [$index];
		}
		
		return $randomString;
	}
	public function getactivation1($n = 8) {
		$characters = 'abcdefghjkmnpqrstuvwxyz';
		$randomString = '';
		
		for($i = 0; $i < $n; $i ++) {
			$index = rand ( 0, strlen ( $characters ) - 1 );
			$randomString .= $characters [$index];
		}
		
		return $randomString;
	}
	public function addUser($data, $customer_key) {
		$facilities2 = implode ( ',', $data ['facilities'] );
		$parent_id = $data ['parent_user_id'];
		$facilities_chk = implode ( ',', $data ['facilities_chk'] );
		
		$tworand = $this->getactivation ();
		
		// $firstchar = substr($data['firstname'], 0, 1);
		// $lastchar = substr($data['lastname'], -1, 1);
		
		// $user_prefix = $tworand.$data['user_pin'];
		// $user_prefix = 'sasatya';
		
		$q1total = $this->getDatabyuserpin ( $user_prefix );
		
		if ($q1total > 1) {
			
			$tworand22 = $this->getactivation1 ();
			
			$activeKey = $tworand22;
			$randomChar = str_split ( $activeKey, 2 );
			foreach ( $randomChar as $characters ) {
				$user_prefix1 = $characters . $data ['user_pin'];
				$q2total = $this->getDatabyuserpin ( $user_prefix1 );
				if ($q2total == 0) {
					// $user_prefix = $user_prefix1;
					break;
				}
			}
		} else {
			// $user_prefix = $tworand.$data['user_pin'];
		}
		
		if ($data ['phone_number'] != null && $data ['phone_number'] != "") {
			$phone_number = $data ['std_code'] . $data ['phone_number'];
		} else {
			$phone_number = "";
		}
		
		$user_prefix = $data ['user_pin'];
		
		$sql = "INSERT INTO `" . DB_PREFIX . "user` SET username = '" . $this->db->escape ( $data ['username'] ) . "', salt = '" . $this->db->escape ( $salt = substr ( md5 ( uniqid ( rand (), true ) ), 0, 9 ) ) . "', password = '" . $this->db->escape ( sha1 ( $salt . sha1 ( $salt . sha1 ( $data ['password'] ) ) ) ) . "', firstname = '" . $this->db->escape ( $data ['firstname'] ) . "', lastname = '" . $this->db->escape ( $data ['lastname'] ) . "', email = '" . $this->db->escape ( $data ['email'] ) . "', user_group_id = '" . ( int ) $data ['user_group_id'] . "', status = '" . ( int ) $data ['status'] . "', facilities = '" . $facilities2 . "', facilities_display = '" . $facilities_chk . "', user_pin = '" . $this->db->escape ( $user_prefix ) . "', default_facilities_id = '" . $data ['default_facilities_id'] . "', default_highlighter_id = '" . $data ['default_highlighter_id'] . "', default_color = '" . $data ['default_color'] . "', phone_number = '" . $phone_number . "', activationKey = '" . str_replace ( ' ', '', $data ['activationKey'] ) . "', customer_key = '" . $customer_key . "', parent_id = '" . $parent_id . "', date_added = NOW(), update_date = NOW(), std_code = '" . $data ['std_code'] . "' ";
		
		$this->db->query ( $sql );
		
		$user_id = $this->db->getLastId ();
		
		if ($data ['email'] != null && $data ['email'] != "") {
			$facility = "";
			$this->load->model ( 'facilities/facilities' );
			foreach ( $data ['facilities'] as $f ) {
				$facilities_info = $this->model_facilities_facilities->getfacilities ( $f );
				
				$facility .= $facilities_info ['facility'] . ", ";
			}
			
			$message = $this->completeemailtemplate ( $data, $facility );
			// var_dump($message);die;
			$this->load->model ( 'api/emailapi' );
			
			$edata = array ();
			$edata ['message'] = $message;
			$edata ['subject'] = 'New User Added';
			$edata ['user_email'] = $data ['email'];
			
			$email_status = $this->model_api_emailapi->sendmail ( $edata );
		}
		
		return $user_id;
	}
	public function editUser($user_id, $data, $customer_key) {
		$facilities2 = implode ( ',', $data ['facilities'] );
		$parent_id = $data ['parent_user_id'];
		
		/* username = '" . $this->db->escape($data['username']) . "', */
		
		// $firstchar = substr($data['activationKey'], 0, 1);
		// $lastchar = substr($data['activationKey'], -1, 1);
		
		$user_info = $this->getUserbyupdate2 ( $user_id );
		
		$pin = str_split ( $user_info ['user_pin'], 2 );
		// $user_prefix = $pin[0].$data['user_pin'];
		
		// $user_prefix = $firstchar.$lastchar.$data['user_pin'];
		// $user_prefix = 'sasatya';
		
		if ($data ['phone_number'] != null && $data ['phone_number'] != "") {
			$phone_number = $data ['std_code'] . $data ['phone_number'];
		} else {
			$phone_number = "";
		}
		
		$user_prefix = $data ['user_pin'];
		
		$sql = "UPDATE `" . DB_PREFIX . "user` SET firstname = '" . $this->db->escape ( $data ['firstname'] ) . "', lastname = '" . $this->db->escape ( $data ['lastname'] ) . "', email = '" . $this->db->escape ( $data ['email'] ) . "', user_group_id = '" . ( int ) $data ['user_group_id'] . "', status = '" . ( int ) $data ['status'] . "', user_pin = '" . $this->db->escape ( $user_prefix ) . "', facilities = '" . $facilities2 . "', phone_number = '" . $phone_number . "', default_facilities_id = '" . $data ['default_facilities_id'] . "', default_highlighter_id = '" . $data ['default_highlighter_id'] . "', default_color = '" . $data ['default_color'] . "', activationKey = '" . str_replace ( ' ', '', $data ['activationKey'] ) . "', update_date = NOW(), customer_key = '" . $customer_key . "',parent_id = '" . $parent_id . "', std_code = '" . $data ['std_code'] . "' WHERE user_id = '" . ( int ) $user_id . "'";
		$this->db->query ( $sql );
		
		if ($data ['password']) {
			$this->db->query ( "UPDATE `" . DB_PREFIX . "user` SET salt = '" . $this->db->escape ( $salt = substr ( md5 ( uniqid ( rand (), true ) ), 0, 9 ) ) . "', password = '" . $this->db->escape ( sha1 ( $salt . sha1 ( $salt . sha1 ( $data ['password'] ) ) ) ) . "' WHERE user_id = '" . ( int ) $user_id . "'" );
		}
	}
	public function getUser($user_id) {
		$query = $this->db->query ( "SELECT user_id,user_group_id,username,firstname,lastname,email,user_pin,facilities,phone_number,activationKey,default_facilities_id,default_highlighter_id,default_color,user_otp,message_sid,facilities_display,default_facility_id,enroll_image,reset_password_otp,customer_key,session_id,status,std_code FROM `" . DB_PREFIX . "user` WHERE user_id = '" . $user_id . "' and status = '1' " );
		return $query->row;
	}
	public function getUserbyupdate2($user_id) {
		$query = $this->db->query ( "SELECT user_id,user_group_id,username,firstname,lastname,email,user_pin,facilities,phone_number,activationKey,default_facilities_id,default_highlighter_id,default_color,user_otp,message_sid,facilities_display,default_facility_id,enroll_image,reset_password_otp,customer_key,session_id,status,std_code FROM `" . DB_PREFIX . "user` WHERE user_id = '" . $user_id . "' " );
		
		return $query->row;
	}
	public function updateStatus($user_id) {
		$user_info = $this->getUserbyupdate2 ( $user_id );
		
		if ($user_info ['status'] == '1') {
			$this->db->query ( "UPDATE `" . DB_PREFIX . "user` SET status = '0' WHERE user_id = '" . $user_id . "'" );
		} else {
			$this->db->query ( "UPDATE `" . DB_PREFIX . "user` SET status = '1', update_date = NOW() WHERE user_id = '" . $user_id . "'" );
		}
	}
	public function getallUsers($data = array()) {
		$sql = "SELECT user_id,user_group_id,username,firstname,lastname,email,user_pin,facilities,phone_number,activationKey,default_facilities_id,default_highlighter_id,default_color,user_otp,message_sid,facilities_display,default_facility_id,enroll_image,reset_password_otp,customer_key,session_id,status,std_code FROM `" . DB_PREFIX . "user`";
		
		$sql .= 'where 1 = 1 and user_id != 1 ';
		if ($data ['username'] != null && $data ['username'] != "") {
			$sql .= " and username like '%" . $data ['username'] . "%'";
		}
		if ($data ['user_group_id'] != null && $data ['user_group_id'] != "") {
			$sql .= " and user_group_id = '" . $data ['user_group_id'] . "'";
		}
		
		if ($data ['customer_key'] != null && $data ['customer_key'] != "") {
			$sql .= " and customer_key = '" . $data ['customer_key'] . "'";
		}
		
		if ($data ['facilities_id'] != null && $data ['facilities_id'] != "") {
			$sql .= " and FIND_IN_SET('" . $data ['facilities_id'] . "', facilities) ";
		}
		
		$sort_data = array (
				'username',
				'status',
				'date_added' 
		);
		
		if (isset ( $data ['sort'] ) && in_array ( $data ['sort'], $sort_data )) {
			$sql .= " ORDER BY " . $data ['sort'];
		} else {
			$sql .= " ORDER BY username";
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
	public function getUserbyupdatefacility($username, $facilities_id) {
		
		$sql = "SELECT user_id,user_group_id,username,firstname,lastname,email,user_pin,facilities,phone_number,activationKey,default_facilities_id,default_highlighter_id,default_color,user_otp,message_sid,facilities_display,default_facility_id,enroll_image,reset_password_otp,customer_key,session_id,std_code FROM `" . DB_PREFIX . "user` WHERE user_id = '" . $this->db->escape ( $username ) . "' and status = '1' and FIND_IN_SET('" . $facilities_id . "', facilities) ";
		$query = $this->db->query ( $sql );
		
		return $query->row;
	}
	public function getUserbyupdate($user_id) {
		$query = $this->db->query ( "SELECT user_id,user_group_id,username,firstname,lastname,email,user_pin,facilities,phone_number,activationKey,default_facilities_id,default_highlighter_id,default_color,user_otp,message_sid,facilities_display,default_facility_id,enroll_image,reset_password_otp,customer_key,session_id,status,std_code FROM `" . DB_PREFIX . "user` WHERE user_id = '" . $user_id . "' and status = '1' " );
		
		return $query->row;
	}
	public function getUserByUsername2($username) {
		$query = $this->db->query ( "SELECT user_id,user_group_id,username,firstname,lastname,email,user_pin,facilities,phone_number,activationKey,default_facilities_id,default_highlighter_id,default_color,user_otp,message_sid,facilities_display,default_facility_id,enroll_image,reset_password_otp,customer_key,session_id,std_code FROM `" . DB_PREFIX . "user` WHERE user_id = '" . $this->db->escape ( $username ) . "' " );
		
		return $query->row;
	}
	public function getUserByUsernamebynotes($username, $facilities_id) {
		$this->load->model ( 'api/permision' );
		$current_customer = $this->model_api_permision->getcustomerid ( $facilities_id );
		
		$sqln = "";
		if ($current_customer != null && $current_customer != "") {
			$sqln .= " and customer_key = '" . $current_customer . "' ";
		}
		
		$query = $this->db->query ( "SELECT user_id,user_group_id,username,firstname,lastname,email,user_pin,facilities,phone_number,activationKey,default_facilities_id,default_highlighter_id,default_color,user_otp,message_sid,facilities_display,default_facility_id,enroll_image,reset_password_otp,customer_key,session_id,std_code FROM `" . DB_PREFIX . "user` WHERE username = '" . $this->db->escape ( $username ) . "' " . $sqln . " " );
		
		return $query->row;
	}
	public function getUserByUsernamebysaml($username) {
		$where = "";
		if ($this->session->data ['activationkey'] != '' && $this->session->data ['activationkey'] != "") {
			//$where .= " and activationKey = '" . $this->session->data ['activationkey'] . "'";
		}
		
		$query = $this->db->query ( "SELECT user_id,user_group_id,username,firstname,lastname,email,user_pin,facilities,phone_number,activationKey,default_facilities_id,default_highlighter_id,default_color,user_otp,message_sid,facilities_display,default_facility_id,enroll_image,reset_password_otp,customer_key,session_id,std_code FROM `" . DB_PREFIX . "user` WHERE username = '" . $this->db->escape ( $username ) . "' and status = '1' " . $where . " " );
		
		return $query->row;
	}
	public function getUserByUsername($username) {
		$where = "";
		if ($this->session->data ['activationkey'] != '' && $this->session->data ['activationkey'] != "") {
			//$where .= " and activationKey = '" . $this->session->data ['activationkey'] . "'";
		}
		
		$query = $this->db->query ( "SELECT user_id,user_group_id,username,firstname,lastname,email,user_pin,facilities,phone_number,activationKey,default_facilities_id,default_highlighter_id,default_color,user_otp,message_sid,facilities_display,default_facility_id,enroll_image,reset_password_otp,customer_key,session_id,std_code FROM `" . DB_PREFIX . "user` WHERE user_id = '" . $this->db->escape ( $username ) . "' and status = '1' " . $where . " " );
		
		return $query->row;
	}
	public function getUserByCode($code) {
		$query = $this->db->query ( "SELECT user_id,user_group_id,username,firstname,lastname,email,user_pin,facilities,phone_number,activationKey,default_facilities_id,default_highlighter_id,default_color,user_otp,message_sid,facilities_display,default_facility_id,enroll_image,reset_password_otp,customer_key,session_id,std_code FROM `" . DB_PREFIX . "user` WHERE code = '" . $this->db->escape ( $code ) . "' AND code != '' and status = '1' " );
		
		return $query->row;
	}
	public function getUsers($data = array()) {
		$sql = "SELECT user_id,user_group_id,username,firstname,lastname,email,user_pin,facilities,phone_number,activationKey,default_facilities_id,default_highlighter_id,default_color,user_otp,message_sid,facilities_display,default_facility_id,enroll_image,reset_password_otp,customer_key,session_id,status,std_code FROM `" . DB_PREFIX . "user`";
		
		$sql .= 'where 1 = 1 and status = 1 and user_id != 1 ';
		if ($data ['username'] != null && $data ['username'] != "") {
			$sql .= " and username like '%" . $data ['username'] . "%'";
		}
		if ($data ['user_group_id'] != null && $data ['user_group_id'] != "") {
			$sql .= " and user_group_id = '" . $data ['user_group_id'] . "'";
		}
		if ($data ['status'] != null && $data ['status'] != "") {
			// $sql .= " and status = '".$data['status']."'";
		}
		
		if ($data ['status'] != null && $data ['status'] != "") {
			// $sql .= " and status = '".$data['status']."'";
		}
		
		if ($data ['customer_key'] != null && $data ['customer_key'] != "") {
			$sql .= " and customer_key = '" . $data ['customer_key'] . "'";
		}
		
		if ($data ['facilities_id'] != null && $data ['facilities_id'] != "") {
			$sql .= " and FIND_IN_SET('" . $data ['facilities_id'] . "', facilities) ";
		}
		
		$sort_data = array (
				'username',
				'status',
				'date_added' 
		);
		
		if (isset ( $data ['sort'] ) && in_array ( $data ['sort'], $sort_data )) {
			$sql .= " ORDER BY " . $data ['sort'];
		} else {
			$sql .= " ORDER BY username";
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
		
		/*
		 * $cacheid = $data['facilities_id'].'.getUsers';
		 *
		 * $this->load->model('api/cache');
		 * $rusers = $this->model_api_cache->getcache($cacheid);
		 *
		 * if (!$rusers) {
		 * $query = $this->db->query($sql);
		 * $rusers = $query->rows;
		 * $this->model_api_cache->setcache($cacheid,$rusers);
		 * }
		 *
		 *
		 * return $rusers;
		 */
		
		$query = $this->db->query ( $sql );
		return $query->rows;
	}
	public function getTotalUsers($data = array()) {
		$sql .= 'where 1 = 1 and status = 1 and user_id != 1 ';
		if ($data ['username'] != null && $data ['username'] != "") {
			$sql .= " and username like '%" . $data ['username'] . "%'";
		}
		if ($data ['user_group_id'] != null && $data ['user_group_id'] != "") {
			$sql .= " and user_group_id = '" . $data ['user_group_id'] . "'";
		}
		
		if ($data ['status'] != null && $data ['status'] != "") {
			// $sql .= " and status = '".$data['status']."'";
		}
		
		$query = $this->db->query ( "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user` " . $sql . "" );
		
		return $query->row ['total'];
	}
	public function getTotalUsersByGroupId($user_group_id) {
		$query = $this->db->query ( "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user` WHERE user_group_id = '" . ( int ) $user_group_id . "'  and status = '1' " );
		
		return $query->row ['total'];
	}
	public function getTotalUsersByEmail($email) {
		$query = $this->db->query ( "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user` WHERE LCASE(email) = '" . $this->db->escape ( utf8_strtolower ( $email ) ) . "' and status = '1' " );
		
		return $query->row ['total'];
	}
	public function getUserByPhone($phone_number) {
		$query = $this->db->query ( "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user` WHERE phone_number = '" . $this->db->escape ( $phone_number ) . "' and status = '1' " );
		
		return $query->row ['total'];
	}
	public function getUserByPhonenumber($phone_number) {
		$query = $this->db->query ( "SELECT user_id,user_group_id,username,firstname,lastname,email,user_pin,facilities,phone_number,activationKey,default_facilities_id,default_highlighter_id,default_color,user_otp,message_sid,facilities_display,default_facility_id,enroll_image,reset_password_otp,customer_key,session_id,std_code FROM `" . DB_PREFIX . "user` WHERE phone_number = '" . $this->db->escape ( $phone_number ) . "'  and status = '1' " );
		return $query->row;
	}
	public function getUsersByFacility($facilities_id) {
		if ($facilities_id != null && $facilities_id != "") {
			$sql2 = "SELECT user_id,user_group_id,username,firstname,lastname,email,user_pin,facilities,phone_number,activationKey,default_facilities_id,default_highlighter_id,default_color,user_otp,message_sid,facilities_display,default_facility_id,enroll_image,status,reset_password_otp,customer_key,session_id,std_code FROM `" . DB_PREFIX . "user` ";
			$sql2 .= "where 1 = '1' and user_id != 1 ";
			if ($allusers != '1') {
				$sql2 .= " and status = '1' ";
			}
			
			if ($data ['user_group_id'] != null && $data ['user_group_id'] != "") {
				$sql2 .= " and user_group_id = '" . $data ['user_group_id'] . "'";
			}
			
			$sql2 .= " and FIND_IN_SET('" . $facilities_id . "', facilities) ";
			
			$sort_data = array (
					'username',
					'status',
					'date_added' 
			);
			
			if (isset ( $data ['sort'] ) && in_array ( $data ['sort'], $sort_data )) {
				$sql2 .= " ORDER BY " . $data ['sort'];
			} else {
				$sql2 .= " ORDER BY username";
			}
			
			if (isset ( $data ['order'] ) && ($data ['order'] == 'DESC')) {
				$sql2 .= " DESC";
			} else {
				$sql2 .= " ASC";
			}
			
			if (isset ( $data ['start'] ) || isset ( $data ['limit'] )) {
				if ($data ['start'] < 0) {
					$data ['start'] = 0;
				}
				
				if ($data ['limit'] < 1) {
					$data ['limit'] = 20;
				}
				
				$sql2 .= " LIMIT " . ( int ) $data ['start'] . "," . ( int ) $data ['limit'];
			}
			
			/*
			 * $cacheid = $facilities_id.'.getUsersByFacility';
			 *
			 * $this->load->model('api/cache');
			 * $frusers = $this->model_api_cache->getcache($cacheid);
			 *
			 * if (!$frusers) {
			 * $query = $this->db->query($sql2);
			 * $frusers = $query->rows;
			 * $this->model_api_cache->setcache($cacheid,$frusers);
			 * }
			 *
			 *
			 * return $frusers;
			 */
			
			$query2 = $this->db->query ( $sql2 );
			return $query2->rows;
		}
	}
	
	public function getUsersByFacilityapp($facilities_id, $allusers, $data = array()) {
		//if ($facilities_id != null && $facilities_id != "") {
			$sql2 = "SELECT user_id,user_group_id,username,firstname,lastname,email,user_pin,facilities,phone_number,activationKey,default_facilities_id,default_highlighter_id,default_color,user_otp,message_sid,facilities_display,default_facility_id,enroll_image,status,reset_password_otp,customer_key,session_id,std_code FROM `" . DB_PREFIX . "user` ";
			$sql2 .= "where 1 = '1' and user_id != 1 ";
			if ($allusers != '1') {
				$sql2 .= " and status = '1' ";
			}
			
			if ($data ['user_group_id'] != null && $data ['user_group_id'] != "") {
				$sql2 .= " and user_group_id = '" . $data ['user_group_id'] . "'";
			}
			
			if($data['is_master'] == '1'){
				$this->load->model('facilities/facilities');
				$facility_info = $this->model_facilities_facilities->getfacilities($data['facilities_id']);
				$ddss = array();
				$ddsss = array();
				if ( $facility_info['client_facilities_ids'] != null && $facility_info['client_facilities_ids'] != "" ) {
					
					$ddss[] = $facility_info['client_facilities_ids'];
					
					
					
					$ddss[] = $data['facilities_id'];
					$ddsss[] = $data['facilities_id'];
					
					if($data['is_submaster'] == '1'){
						$sssssddsg = explode(",",$facility_info ['client_facilities_ids']);
						$abdcg = array_unique($sssssddsg);
						$cids = array();
						foreach($abdcg as $fid){
							$cids[] = $fid;
							$ddsss[] = $fid;
						}
						$abdcgs = array_unique($cids);
						foreach($abdcgs as $fid2){
							$facilityinfo = $this->model_facilities_facilities->getfacilities ( $fid2 );
							if ( $facilityinfo['client_facilities_ids'] != null && $facilityinfo['client_facilities_ids'] != "" ) {
								$cids[] = $facilityinfo['client_facilities_ids'];
								
								$sssssddsg11 = explode(",",$facilityinfo ['client_facilities_ids']);
						
							}
						}
						
						foreach($sssssddsg11 as $id){
							$ddsss[] = $id;
						}
					}
					//var_dump($ddsss);
					//$sssssdd = implode(",",$ddss);
					$abdcgss = array_unique($ddsss);
					//var_dump($abdcgss);
					$sql2 .= " and ( ";
					$i=0;
					foreach($abdcgss as $dds){
						if($i != 0){
							$sql2 .= " or ";
						}
						$sql2 .= " FIND_IN_SET('" . $dds . "', facilities) ";
						$i++;
					}
					$sql2 .= " ) ";
					//$sql2.= " and facilities in (" . $sssssdd . ") ";
					//$faculities_ids = $sssssdd;
				}else{
					if ($facilities_id != null && $facilities_id != "") {
						$sql2 .= " and FIND_IN_SET('" . $facilities_id . "', facilities) ";
					}
				}
			}else{
				if($facilities_id != null && $facilities_id != ""){
					$sql2 .= " and FIND_IN_SET('" . $facilities_id . "', facilities) ";
				}
			}
			
			if($data['app_user_date']!='' && $data['current_date_user']!=''){
				$sql2 .= " and `update_date` BETWEEN  '" . $data ['app_user_date'] . "' AND  '" . $data ['current_date_user'] . " 23:59:59' ";
			}
			
			$sort_data = array (
					'username',
					'status',
					'date_added' 
			);
			
			if (isset ( $data ['sort'] ) && in_array ( $data ['sort'], $sort_data )) {
				$sql2 .= " ORDER BY " . $data ['sort'];
			} else {
				$sql2 .= " ORDER BY username";
			}
			
			if (isset ( $data ['order'] ) && ($data ['order'] == 'DESC')) {
				$sql2 .= " DESC";
			} else {
				$sql2 .= " ASC";
			}
			
			if (isset ( $data ['start'] ) || isset ( $data ['limit'] )) {
				if ($data ['start'] < 0) {
					$data ['start'] = 0;
				}
				
				if ($data ['limit'] < 1) {
					$data ['limit'] = 20;
				}
				
				$sql2 .= " LIMIT " . ( int ) $data ['start'] . "," . ( int ) $data ['limit'];
			}
			
			/*
			 * $cacheid = $facilities_id.'.getUsersByFacility';
			 *
			 * $this->load->model('api/cache');
			 * $frusers = $this->model_api_cache->getcache($cacheid);
			 *
			 * if (!$frusers) {
			 * $query = $this->db->query($sql2);
			 * $frusers = $query->rows;
			 * $this->model_api_cache->setcache($cacheid,$frusers);
			 * }
			 *
			 *
			 * return $frusers;
			 */
			//echo  $sql2 ;
			$query2 = $this->db->query ( $sql2 );
			return $query2->rows;
		//}
	}
	
	public function getTotalusersapp($facilities_id, $allusers, $data = array()) {
		
		$sql2 = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user` ";
		$sql2 .= 'where 1 = 1 ';
		
		if ($allusers != '1') {
			$sql2 .= " and status = '1' ";
		}
		
		if ($data ['user_group_id'] != null && $data ['user_group_id'] != "") {
			$sql2 .= " and user_group_id = '" . $data ['user_group_id'] . "'";
		}
		
		if($data['is_master'] == '1'){
			$this->load->model('facilities/facilities');
			$facility_info = $this->model_facilities_facilities->getfacilities($data['facilities_id']);
			$ddss = array();
			$ddsss = array();
			if ( $facility_info['client_facilities_ids'] != null && $facility_info['client_facilities_ids'] != "" ) {
				
				$ddss[] = $facility_info['client_facilities_ids'];
				
				
				
				$ddss[] = $data['facilities_id'];
				$ddsss[] = $data['facilities_id'];
				
				if($data['is_submaster'] == '1'){
					$sssssddsg = explode(",",$facility_info ['client_facilities_ids']);
					$abdcg = array_unique($sssssddsg);
					$cids = array();
					foreach($abdcg as $fid){
						$cids[] = $fid;
						$ddsss[] = $fid;
					}
					$abdcgs = array_unique($cids);
					foreach($abdcgs as $fid2){
						$facilityinfo = $this->model_facilities_facilities->getfacilities ( $fid2 );
						if ( $facilityinfo['client_facilities_ids'] != null && $facilityinfo['client_facilities_ids'] != "" ) {
							$cids[] = $facilityinfo['client_facilities_ids'];
							
							$sssssddsg11 = explode(",",$facilityinfo ['client_facilities_ids']);
					
						}
					}
					
					foreach($sssssddsg11 as $id){
						$ddsss[] = $id;
					}
				}
				//var_dump($ddsss);
				//$sssssdd = implode(",",$ddss);
				$abdcgss = array_unique($ddsss);
				//var_dump($abdcgss);
				$sql2 .= " and ( ";
				$i=0;
				foreach($abdcgss as $dds){
					if($i != 0){
						$sql2 .= " or ";
					}
					$sql2 .= " FIND_IN_SET('" . $dds . "', facilities) ";
					$i++;
				}
				$sql2 .= " ) ";
				//$sql2.= " and facilities in (" . $sssssdd . ") ";
				//$faculities_ids = $sssssdd;
			}else{
				if ($facilities_id != null && $facilities_id != "") {
					$sql2 .= " and FIND_IN_SET('" . $facilities_id . "', facilities) ";
				}
			}
		}else{
			if($facilities_id != null && $facilities_id != ""){
				$sql2 .= " and FIND_IN_SET('" . $facilities_id . "', facilities) ";
			}
		}
		
		if($data['app_user_date']!='' && $data['current_date_user']!=''){
			//$sql2 .= " and `update_date` BETWEEN  '" . $data ['app_user_date'] . "' AND  '" . $data ['current_date_user'] . " 23:59:59' ";
		}
		
		//echo $sql;
		//echo "<hr>";
		
		$query = $this->db->query($sql2);

		return $query->row['total'];
	}
	
	public function getUsersByFacilityUser($data) {
		if ($data ['facilities_id'] != null && $data ['facilities_id'] != "") {
			$sql2 = "SELECT user_id,user_group_id,username,firstname,lastname,email,user_pin,facilities,phone_number,activationKey,default_facilities_id,default_highlighter_id,default_color,user_otp,message_sid,facilities_display,default_facility_id,enroll_image,reset_password_otp,customer_key,session_id,std_code FROM `" . DB_PREFIX . "user` ";
			
			$sql2 .= "where 1 = '1' and user_id != 1 ";
			if ($data ['allusers'] != '1') {
				$sql2 .= " and status = '1' ";
			}
			
			if ($data ['user_group_id'] != null && $data ['user_group_id'] != "") {
				$sql2 .= " and user_group_id = '" . $data ['user_group_id'] . "'";
			}
			
			$sql2 .= " and FIND_IN_SET('" . $data ['facilities_id'] . "', facilities) ";
			
			if (! empty ( $data ['user_id'] )) {
				$sql2 .= " and LOWER(username) like '%" . strtolower ( $data ['user_id'] ) . "%'";
			}
			$sort_data = array (
					'username',
					'status',
					'date_added' 
			);
			
			if (isset ( $data ['sort'] ) && in_array ( $data ['sort'], $sort_data )) {
				$sql2 .= " ORDER BY " . $data ['sort'];
			} else {
				$sql2 .= " ORDER BY username";
			}
			
			if (isset ( $data ['order'] ) && ($data ['order'] == 'DESC')) {
				$sql2 .= " DESC";
			} else {
				$sql2 .= " ASC";
			}
			
			if (isset ( $data ['start'] ) || isset ( $data ['limit'] )) {
				if ($data ['start'] < 0) {
					$data ['start'] = 0;
				}
				
				if ($data ['limit'] < 1) {
					$data ['limit'] = 20;
				}
				
				$sql2 .= " LIMIT " . ( int ) $data ['start'] . "," . ( int ) $data ['limit'];
			}
			
			/*
			 * $cacheid = $facilities_id.'.getUsersByFacilityUser';
			 *
			 * $this->load->model('api/cache');
			 * $ufrusers = $this->model_api_cache->getcache($cacheid);
			 *
			 * if (!$ufrusers) {
			 * $query = $this->db->query($sql2);
			 * $ufrusers = $query->rows;
			 * $this->model_api_cache->setcache($cacheid,$ufrusers);
			 * }
			 *
			 *
			 *
			 * return $ufrusers;
			 */
			
			$query2 = $this->db->query ( $sql2 );
			return $query2->rows;
		}
	}
	public function getUsersByPin($user_id, $user_pin) {
		$query = $this->db->query ( "SELECT user_id,user_group_id,username,firstname,lastname,email,user_pin,facilities,phone_number,activationKey,default_facilities_id,default_highlighter_id,default_color,user_otp,message_sid,facilities_display,default_facility_id,enroll_image,reset_password_otp,customer_key,session_id,std_code FROM `" . DB_PREFIX . "user` WHERE user_id = '" . $user_id . "' and user_pin= '" . $user_pin . "'  and status = '1' " );
		return $query->row;
	}
	public function editUserByKey($data) {
		if ($data ['fname']) {
			$this->db->query ( "UPDATE `" . DB_PREFIX . "user` SET firstname = '" . $this->db->escape ( $data ['fname'] ) . "' WHERE user_id = '" . ( int ) $data ['user_id'] . "'" );
		}
		
		if ($data ['lname']) {
			$this->db->query ( "UPDATE `" . DB_PREFIX . "user` SET lastname = '" . $this->db->escape ( $data ['lname'] ) . "' WHERE user_id = '" . ( int ) $data ['user_id'] . "'" );
		}
		
		if ($data ['email']) {
			$this->db->query ( "UPDATE `" . DB_PREFIX . "user` SET email = '" . $this->db->escape ( $data ['email'] ) . "' WHERE user_id = '" . ( int ) $data ['user_id'] . "'" );
		}
		
		if ($data ['contact']) {
			$this->db->query ( "UPDATE `" . DB_PREFIX . "user` SET phone_number = '" . $this->db->escape ( $data ['std_code'] . $data ['contact'] ) . "', std_code = '" . $this->db->escape ( $data ['std_code'] ) . "' WHERE user_id = '" . ( int ) $data ['user_id'] . "'" );
		}
		
		if ($data ['user_pin']) {
			$this->db->query ( "UPDATE `" . DB_PREFIX . "user` SET user_pin = '" . $this->db->escape ( $data ['user_pin'] ) . "' WHERE user_id = '" . ( int ) $data ['user_id'] . "'" );
		}
		
		if ($data ['password']) {
			
			$sql = "UPDATE `" . DB_PREFIX . "user` SET salt = '" . $this->db->escape ( $salt = substr ( md5 ( uniqid ( rand (), true ) ), 0, 9 ) ) . "', password = '" . $this->db->escape ( sha1 ( $salt . sha1 ( $salt . sha1 ( $data ['password'] ) ) ) ) . "' WHERE `user_id` = '" . ( int ) $data ['user_id'] . "' ";
			$this->db->query ( $sql );
		}
	}
	public function getUserByAccessKey($accessKey) {
		$sql = "SELECT user_id,user_group_id,username,firstname,lastname,email,user_pin,facilities,phone_number,activationKey,default_facilities_id,default_highlighter_id,default_color,user_otp,message_sid,facilities_display,default_facility_id,enroll_image,reset_password_otp,customer_key,session_id,std_code FROM `" . DB_PREFIX . "user` WHERE activationKey = '" . $this->db->escape ( $accessKey ) . "' and activationKey != '' and status = '1' ";
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	public function editUserdetail($accessKey, $data) {
		$user_info = $this->getUserByAccessKey ( $accessKey );
		
		// var_dump($user_info);
		
		$newpin = substr ( $user_info ['user_pin'], 2 );
		$user_pin111 = $newpin;
		// var_dump($user_pin111);
		
		$randomChar = str_split ( $user_info ['user_pin'], 2 );
		$prefix_userpin = $randomChar [0];
		
		// var_dump($prefix_userpin);
		// $firstchar = substr($accessKey, 0, 1);
		// $lastchar = substr($accessKey, -1, 1);
		$user_prefix = $prefix_userpin . $data ['user_pin'];
		
		$q1total = $this->getDatabyuserpin ( $user_prefix );
		
		if ($q1total > 1) {
			$activeKey = $accessKey . $data ['lastname'];
			
			$randomChar = str_split ( $activeKey, 2 );
			foreach ( $randomChar as $characters ) {
				$user_prefix1 = $characters . $data ['user_pin'];
				$q2total = $this->getDatabyuserpin ( $user_prefix1 );
				if ($q2total == 0) {
					$user_prefix = $user_prefix1;
					break;
				}
			}
		} else {
			$user_prefix = $prefix_userpin . $data ['user_pin'];
		}
		
		$sql1 = "UPDATE `" . DB_PREFIX . "user` SET firstname = '" . $this->db->escape ( $data ['firstname'] ) . "', lastname = '" . $this->db->escape ( $data ['lastname'] ) . "', email = '" . $this->db->escape ( $data ['email'] ) . "', phone_number = '" . $this->db->escape ( $data ['phone_number'] ) . "', user_pin = '" . $this->db->escape ( $data ['user_pin'] ) . "' WHERE `activationKey` = '" . $this->db->escape ( $accessKey ) . "' ";
		$this->db->query ( $sql1 );
		
		if ($data ['password']) {
			
			$sql = "UPDATE `" . DB_PREFIX . "user` SET salt = '" . $this->db->escape ( $salt = substr ( md5 ( uniqid ( rand (), true ) ), 0, 9 ) ) . "', password = '" . $this->db->escape ( sha1 ( $salt . sha1 ( $salt . sha1 ( $data ['password'] ) ) ) ) . "' WHERE `activationKey` = '" . $this->db->escape ( $accessKey ) . "' ";
			$this->db->query ( $sql );
		}
		
		if (! empty ( $data ['enroll_image'] )) {
			
			if ($data ['enroll_image1'] == '1') {
				
				foreach ( $data ['enroll_image'] as $img_a ) {
					
					$img = $img_a;
					$img = str_replace ( 'data:image/jpeg;base64,', '', $img );
					$img = str_replace ( ' ', '+', $img );
					$Imgdata = base64_decode ( $img );
					
					$notes_file = uniqid () . '.jpeg';
					
					$file = DIR_IMAGE . 'facerecognition/' . $notes_file;
					$success = file_put_contents ( $file, $Imgdata );
					
					$outputFolder = $file;
					
					$this->load->model ( 'user/user' );
					$user_info = $this->model_user_user->getUserByAccessKey ( $accessKey );
					
					$user_id = $user_info ['user_id'];
					$username = $user_info ['username'];
					// $FaceId = $user_info['FaceId'];
					
					$outputFolderUrl = HTTP_SERVER . 'image/facerecognition/' . $notes_file;
					
					// require_once(DIR_APPLICATION_AWS . 'facerecognition_insert_user_config.php');
					
					/*
					 * $result_inser_user_img22 = $this->awsimageconfig->indexFacesbyuser($outputFolderUrl, $username);
					 *
					 * foreach($result_inser_user_img22['FaceRecords'] as $b){
					 * $FaceId = $b['Face']['FaceId'];
					 * $ImageId = $b['Face']['ImageId'];
					 * }
					 */
					
					$enroll_image = $s3file;
					
					$susql = "SELECT COUNT(DISTINCT user_enroll_id) AS total FROM `" . DB_PREFIX . "user_enroll` WHERE user_id = '" . $user_id . "' ";
					$query = $this->db->query ( $susql );
					
					/* if($query->row['total'] < CUSTOM_USERPIC){ */
					
					$date_added = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
					
					$usql = "INSERT INTO " . DB_PREFIX . "user_enroll SET enroll_image = '" . $this->db->escape ( $enroll_image ) . "',user_id = '" . $this->db->escape ( $user_id ) . "',FaceId = '" . $this->db->escape ( $FaceId ) . "', ImageId = '" . $this->db->escape ( $ImageId ) . "', date_added = '" . $date_added . "', date_updated = '" . $date_added . "' ";
					
					$this->db->query ( $usql );
					$user_enroll_id = $this->db->getLastId ();
					
					$metadata = array ();
					$metadata ['username'] = $username;
					$metadata ['user_id'] = $user_id;
					$metadata ['firstname'] = $user_info ['firstname'];
					$metadata ['lastname'] = $user_info ['lastname'];
					$metadata ['facilities'] = $user_info ['facilities'];
					$metadata ['user_enroll_id'] = $user_enroll_id;
					
					$this->load->model ( 'customer/customer' );
					$customer_info = $this->model_customer_customer->getcustomer ( $user_info ['customer_key'] );
					$customer_bucket = $customer_info ['bucket'];
					
					$s3file = $this->awsimageconfig->uploadFile2 ( $customer_bucket, $notes_file, $outputFolder, $metadata );
					
					$usql2 = "UPDATE " . DB_PREFIX . "user_enroll SET enroll_image = '" . $this->db->escape ( $s3file ) . "' WHERE user_enroll_id = '" . $user_enroll_id . "' ";
					
					$this->db->query ( $usql2 );
					
					/*
					 * }else{
					 *
					 * $sqlt = "SELECT * FROM `" . DB_PREFIX . "user_enroll` WHERE user_id = '".$user_id."' order by RAND() LIMIT 1 ";
					 * $bedtu = $this->db->query($sqlt);
					 *
					 * $user_enroll_id = $bedtu->row['user_enroll_id'];
					 * $FaceId = $bedtu->row['FaceId'];
					 * if($FaceId != null && $FaceId != ""){
					 * //require_once(DIR_APPLICATION_AWS . 'facerecognition_detele_oldfaces_user_config.php');
					 * }
					 *
					 * $date_added = date('Y-m-d H:i:s', strtotime('now'));
					 *
					 * $usql2 = "UPDATE " . DB_PREFIX . "user_enroll SET enroll_image = '" . $this->db->escape($enroll_image) . "',user_id = '" . $this->db->escape($user_id) . "',FaceId = '" . $this->db->escape($FaceId) . "', ImageId = '" . $this->db->escape($ImageId) . "', date_updated = '".$date_added."' WHERE user_enroll_id = '" . $user_enroll_id . "' ";
					 *
					 * $this->db->query($usql2);
					 * }
					 */
					
					unlink ( $file );
				}
			}
		}
		
		$this->load->model ( 'activity/activity' );
		$adata ['enroll_image'] = $enroll_image;
		$adata ['user_id'] = $user_id;
		$adata ['email'] = $data ['email'];
		$adata ['date_added'] = $date_added;
		$this->model_activity_activity->addActivitySave ( 'editUserdetail', $adata, 'query' );
		
		/*
		 * if ($data['enroll_image']) {
		 *
		 * if ($data['enroll_image1'] == '1') {
		 *
		 * $img = $data['enroll_image'];
		 * $img = str_replace('data:image/jpeg;base64,', '', $img);
		 * $img = str_replace(' ', '+', $img);
		 * $Imgdata = base64_decode($img);
		 *
		 * $notes_file = uniqid() . '.jpeg';
		 *
		 * $file = DIR_IMAGE .'facerecognition/' . $notes_file;
		 * $success = file_put_contents($file, $Imgdata);
		 *
		 * $outputFolder = $file;
		 *
		 *
		 * $this->load->model('user/user');
		 * $user_info = $this->model_user_user->getUserByAccessKey($accessKey);
		 *
		 * $user_id = $user_info['user_id'];
		 * //$FaceId = $user_info['FaceId'];
		 *
		 *
		 * $outputFolderUrl = HTTP_SERVER.'image/facerecognition/' . $notes_file;
		 *
		 *
		 * require_once(DIR_SYSTEM . 'library/awsstorage/s3_config_facerecognition.php');
		 *
		 * require_once(DIR_APPLICATION_AWS . 'facerecognition_insert_user_config.php');
		 *
		 * $enroll_image = $s3file;
		 *
		 *
		 * $susql = "SELECT COUNT(DISTINCT user_enroll_id) AS total FROM `" . DB_PREFIX . "user_enroll` WHERE user_id = '".$user_id."' ";
		 * $query = $this->db->query($susql);
		 *
		 * if($query->row['total'] < CUSTOM_USERPIC){
		 *
		 * $date_added = date('Y-m-d H:i:s', strtotime('now'));
		 *
		 * $usql = "INSERT INTO " . DB_PREFIX . "user_enroll SET enroll_image = '" . $this->db->escape($enroll_image) . "',user_id = '" . $this->db->escape($user_id) . "',FaceId = '" . $this->db->escape($FaceId) . "', ImageId = '" . $this->db->escape($ImageId) . "', date_added = '".$date_added."', date_updated = '".$date_added."' ";
		 *
		 * $this->db->query($usql);
		 * }else{
		 *
		 * $sqlt = "SELECT * FROM `" . DB_PREFIX . "user_enroll` WHERE user_id = '".$user_id."' order by RAND() LIMIT 1 ";
		 * $bedtu = $this->db->query($sqlt);
		 *
		 * $user_enroll_id = $bedtu->row['user_enroll_id'];
		 *
		 * $date_added = date('Y-m-d H:i:s', strtotime('now'));
		 *
		 * $usql2 = "UPDATE " . DB_PREFIX . "user_enroll SET enroll_image = '" . $this->db->escape($enroll_image) . "',user_id = '" . $this->db->escape($user_id) . "',FaceId = '" . $this->db->escape($FaceId) . "', ImageId = '" . $this->db->escape($ImageId) . "', date_updated = '".$date_added."' WHERE user_enroll_id = '" . $user_enroll_id . "' ";
		 *
		 * $this->db->query($usql2);
		 * }
		 *
		 *
		 *
		 * }else{
		 * $enroll_image = $data['enroll_image'];
		 * }
		 *
		 *
		 * unlink($file);
		 *
		 * }
		 */
	}
	public function getenroll_images($user_id) {
		$query = $this->db->query ( "SELECT user_enroll_id,user_id,enroll_image,date_added,date_updated,status,deleted FROM `" . DB_PREFIX . "user_enroll` WHERE user_id = '" . $this->db->escape ( $user_id ) . "' " );
		
		return $query->rows;
	}
	public function getenroll_image($user_id) {
		$query = $this->db->query ( "SELECT user_enroll_id,user_id,enroll_image,date_added,date_updated,status,deleted FROM `" . DB_PREFIX . "user_enroll` WHERE user_id = '" . $this->db->escape ( $user_id ) . "' " );
		
		return $query->row;
	}
	public function geteuser_info_byfaceid($FaceId) {
		$sql1 = "SELECT user_id FROM `" . DB_PREFIX . "user_enroll` WHERE FaceId in ('" . implode ( "','", $FaceId ) . "') ";
		
		$query = $this->db->query ( $sql1 );
		
		return $query->row;
	}
	public function updateUserOTP($reset_password_otp, $user_id) {
		$sql = "UPDATE `" . DB_PREFIX . "user` SET reset_password_otp = '" . $reset_password_otp . "'  WHERE user_id = '" . $user_id . "' ";
		$query = $this->db->query ( $sql );
		
		$this->load->model ( 'activity/activity' );
		$adata ['user_id'] = $user_id;
		$adata ['reset_password_otp'] = $reset_password_otp;
		$this->model_activity_activity->addActivitySave ( 'updateUserOTP', $adata, 'query' );
	}
	public function insertUserOTP($data) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "user_otp` where user_id = '" . $data ['user_id'] . "' and facilities_id = '" . $this->db->escape ( $data ['facilities_id'] ) . "' and notes_id = '" . $this->db->escape ( $data ['notes_id'] ) . "' and share_note_otp = '" . $this->db->escape ( $data ['share_note_otp'] ) . "' ";
		$query = $this->db->query ( $sql );
		
		if ($query->num_rows > 0) {
			$usql = "UPDATE  " . DB_PREFIX . "user_otp SET user_id = '" . $this->db->escape ( $data ['user_id'] ) . "',otp = '" . $this->db->escape ( $data ['otp'] ) . "',	response = '" . $this->db->escape ( $data ['response'] ) . "', date_added = '" . $this->db->escape ( $data ['date_added'] ) . "', 	status = '" . $this->db->escape ( $data ['status'] ) . "' , otp_type = '" . $this->db->escape ( $data ['otp_type'] ) . "', alternate_email = '" . $this->db->escape ( $data ['alternate_email'] ) . "' WHERE  user_id = '" . $this->db->escape ( $data ['user_id'] ) . "' ";
			
			$this->db->query ( $usql );
		} else {
			$usql = "INSERT INTO " . DB_PREFIX . "user_otp SET user_id = '" . $this->db->escape ( $data ['user_id'] ) . "',otp = '" . $this->db->escape ( $data ['otp'] ) . "',	response = '" . $this->db->escape ( $data ['response'] ) . "', date_added = '" . $this->db->escape ( $data ['date_added'] ) . "', 	status = '" . $this->db->escape ( $data ['status'] ) . "' , otp_type = '" . $this->db->escape ( $data ['otp_type'] ) . "', facilities_id = '" . $this->db->escape ( $data ['facilities_id'] ) . "', notes_id = '" . $this->db->escape ( $data ['notes_id'] ) . "', share_note_otp = '" . $this->db->escape ( $data ['share_note_otp'] ) . "', alternate_email = '" . $this->db->escape ( $data ['alternate_email'] ) . "' ";
			
			$this->db->query ( $usql );
		}
		
		$this->load->model ( 'activity/activity' );
		$adata ['notes_id'] = $data ['notes_id'];
		$adata ['user_id'] = $data ['user_id'];
		$adata ['share_note_otp'] = $data ['share_note_otp'];
		$adata ['facilities_id'] = $data ['facilities_id'];
		$adata ['date_added'] = $data ['date_added'];
		$this->model_activity_activity->addActivitySave ( 'insertUserOTP', $adata, 'query' );
	}
	public function getuserOPT($data) {
		if (($data ['date_added_from'] != null && $data ['date_added_from'] != "") && ($data ['date_added_to'] != null && $data ['date_added_to'] != "")) {
			// $sql11 = " and DATE_ADD(date_added, INTERVAL 30 MINUTE); ( `date_added` <= '".$data['date_added_to']."' ) ";
			
			$sql11 = " and date_added >= DATE_SUB('" . $data ['date_added_from'] . "', INTERVAL 15 MINUTE)";
		}
		
		$sql11 .= " and facilities_id = '" . $this->db->escape ( $data ['facilities_id'] ) . "'";
		
		if ($data ['notes_id'] != null && $data ['notes_id'] != "") {
			$sql11 .= " and notes_id = '" . $this->db->escape ( $data ['notes_id'] ) . "'";
		}
		if ($data ['share_note_otp'] != null && $data ['share_note_otp'] != "") {
			$sql11 .= " and share_note_otp = '" . $this->db->escape ( $data ['share_note_otp'] ) . "'";
		}
		
		$sql = "SELECT * FROM `" . DB_PREFIX . "user_otp` WHERE user_id = '" . $data ['user_id'] . "' and otp_type = '" . $data ['otp_type'] . "' " . $sql11 . "  ";
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	public function getDatabyuserpin($user_prefix) {
		$sql1 = "SELECT DISTINCT COUNT(DISTINCT user_id) AS total FROM `" . DB_PREFIX . "user` where user_pin = '" . $this->db->escape ( $user_prefix ) . "' ";
		$q1333 = $this->db->query ( $sql1 );
		
		return $q1333->row ['total'];
	}
	public function getUserdetailuserpin($user_pin) {
		$user = "SELECT * FROM `" . DB_PREFIX . "user` where user_pin = '" . $this->db->escape ( $user_pin ) . "' ";
		$upin = $this->db->query ( $user );
		return $upin->row;
	}
	public function insertUser($data) {
		$user_group_id = $this->config->get ( 'default_user_group_id' );
		
		$sql = "INSERT INTO `" . DB_PREFIX . "user` SET username = '" . $this->db->escape ( $data ['username'] ) . "', firstname = '" . $this->db->escape ( $data ['firstname'] ) . "', lastname = '" . $this->db->escape ( $data ['lastname'] ) . "', email = '" . $this->db->escape ( $data ['email'] ) . "', user_group_id = '" . ( int ) $user_group_id . "', status = '1', phone_number = '" . $data ['phone_number'] . "', activationKey = '" . str_replace ( ' ', '', $data ['activationKey'] ) . "', date_added = NOW(), date_added = NOW(), update_date = NOW() ";
		
		$this->db->query ( $sql );
		
		$user_id = $this->db->getLastId ();
		
		return $user_id;
	}
	public function getuserbynamenpass($username, $password) {
		$query = $this->db->query ( "SELECT user_id,user_group_id,username,firstname,lastname,email,user_pin,facilities,phone_number,activationKey,default_facilities_id,default_highlighter_id,default_color,user_otp,message_sid,facilities_display,default_facility_id,enroll_image,reset_password_otp,customer_key,session_id,std_code FROM `" . DB_PREFIX . "user` WHERE username = '" . $username . "' and password= '" . md5 ( $password ) . "'  and status = '1' " );
		return $query->row;
	}
	public function getuseip($address) {
		$query = $this->db->query ( "SELECT * FROM `" . DB_PREFIX . "user_ip` WHERE address = '" . $address . "' and status = '1' and login_allow = '1' " );
		return $query->row;
	}
	public function deleteenrollid($user_enroll_id) {
		$query = $this->db->query ( "DELETE FROM `" . DB_PREFIX . "user_enroll` WHERE user_enroll_id = '" . $user_enroll_id . "' " );
		return $query->row;
	}
	public function addUserenroll($fdata = array()) {
		$usql = "INSERT INTO " . DB_PREFIX . "user_enroll SET enroll_image = '" . $this->db->escape ( $fdata ['enroll_image'] ) . "',user_id = '" . $this->db->escape ( $fdata ['user_id'] ) . "',FaceId = '" . $this->db->escape ( $fdata ['FaceId'] ) . "', ImageId = '" . $this->db->escape ( $fdata ['ImageId'] ) . "', date_added = '" . $fdata ['date_added'] . "', date_updated = '" . $fdata ['date_added'] . "' ";
		
		$this->db->query ( $usql );
		
		$user_enroll_id = $this->db->getLastId ();
		
		return $user_enroll_id;
	}
	public function updateUserenroll($s3file, $user_enroll_id) {
		$usql2 = "UPDATE " . DB_PREFIX . "user_enroll SET enroll_image = '" . $this->db->escape ( $s3file ) . "' WHERE user_enroll_id = '" . $user_enroll_id . "' ";
		
		$this->db->query ( $usql2 );
	}
	public function updateUserSession($user_id, $session_id) {
		$sql = "UPDATE `" . DB_PREFIX . "user` SET session_id = '" . $session_id . "'  WHERE user_id = '" . $user_id . "' ";
		$query = $this->db->query ( $sql );
		
		$this->load->model ( 'activity/activity' );
		$adata ['user_id'] = $user_id;
		$adata ['session_id'] = $session_id;
		$this->model_activity_activity->addActivitySave ( 'updateUserSession', $adata, 'query' );
	}
	public function completeemailtemplate($result, $facility) {
		$html = "";
		
		$html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>New User has been created</title>

<style>
@media screen and (max-width:500px) {
   h6 {
        font-size: 12px !important;
    }
}
</style>
</head>
 
<body bgcolor="#FFFFFF" topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style=" -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none;width: 100%!important;height: 100%;padding: 0;margin: 0;font-family: Open Sans, sans-serif;">

<table class="head-wrap" style="width: 100%;background: #fff; border-spacing: 0;">
	<tr>
		<td></td>
		<td class="header container" align="" style="display: block!important;max-width: 600px!important;margin: 0 auto!important; clear: both!important;">
			

			<div class="content" style="padding: 15px;max-width: 600px;margin: 0 auto;display: block;padding-right: 0;padding-left: 0;">
				<table style="width: 100%;">
					<tr>
						<td><img src="' . HTTP_SERVER . 'view/stylesheet/email/logo.png" style="width: 100%;" /></td>
						<td align="right" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">New User has been created</h6></td>
					</tr>
				</table>
			</div>
			
		</td>
		<td></td>
	</tr>
</table>

<table class="body-wrap" bgcolor="" style="width: 100%;    border-spacing: 0;">
	<tr>
		<td></td>
		<td class="container" align="" bgcolor="#c1c1c1" style="display: block!important;max-width: 600px!important;margin: 0 auto!important; clear: both!important;">

			<div class="content" style="padding: 15px;max-width: 600px;margin: 0 auto;display: block; background: #c1c1c1;border-bottom: 2px solid #2c3742;">
				<table>
					<tr>
						<td>
							
							<h1 style="font-weight: 200;font-size: 30px;padding: 0;margin: 0;">Hello ' . $result ['username'] . '!</h1>
							
							
						</td>
					</tr>
				</table>
			</div>
			<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="#">
						<img src="' . HTTP_SERVER . 'view/stylesheet/email/what.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">What <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">New User Created</small></h4>';
		
		$html .= '<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
								<b>Facility:</b> ' . $facility . '<br>
								<b>Username:</b> ' . $result ['username'] . '<br>
								<b>Activation Key:</b>' . $result ['activationKey'] . '<br>
								<b>Password:</b> ' . $result ['password'] . '<br>
								<b>User Pin:</b> ' . $result ['user_pin'] . '
								</p>';
		
		$html .= '</td>
					</tr>
				</table>
			
			</div>
			

		</td>
		<td></td>
	</tr>
</table>

</body>
</html>';
		return $html;
	}
	public function getDatabyuserpin2($user_prefix) {
		$sql1 = "SELECT DISTINCT * FROM `" . DB_PREFIX . "user` where user_pin = '" . $this->db->escape ( $user_prefix ) . "' ";
		$q222 = $this->db->query ( $sql1 );
		return $q222->row;
	}
	
	
	public function getkinisesdatas($type) {
		$sql1 = "SELECT DISTINCT * FROM `" . DB_PREFIX . "kinesis_data` where type = '" . $this->db->escape ( $type ) . "' and status='1' ";
		$q222 = $this->db->query ( $sql1 );
		return $q222->rows;
	}
	
	public function getUserByUserPin($user_pin) {
		$query = $this->db->query ( "SELECT user_id,user_group_id,username,firstname,lastname,email,user_pin,facilities,phone_number,activationKey,default_facilities_id,default_highlighter_id,default_color,user_otp,message_sid,facilities_display,default_facility_id,enroll_image,reset_password_otp,customer_key,session_id,std_code FROM `" . DB_PREFIX . "user` WHERE user_pin= '" . $user_pin . "'  and status = '1' " );
		return $query->row;
	}
	
	public function getAjaxUsersByFacility($data=array()) {
		if ($facilities_id != null && $facilities_id != "") {
			$sql2 = "SELECT user_id,user_group_id,username,firstname,lastname,email,user_pin,facilities,phone_number,activationKey,default_facilities_id,default_highlighter_id,default_color,user_otp,message_sid,facilities_display,default_facility_id,enroll_image,status,reset_password_otp,customer_key,session_id,std_code FROM `" . DB_PREFIX . "user` ";
			$sql2 .= "where 1 = '1' and user_id != 1 ";
			if ($allusers != '1') {
				$sql2 .= " and status = '1' ";
			}
			
			if ($data ['user_group_id'] != null && $data ['user_group_id'] != "") {
				$sql2 .= " and user_group_id = '" . $data ['user_group_id'] . "'";
			}
			
			$sql2 .= " and FIND_IN_SET('" . $data['facilities'] . "', facilities) ";
			
			$sort_data = array (
					'username',
					'status',
					'date_added' 
			);
			
			if (isset ( $data ['sort'] ) && in_array ( $data ['sort'], $sort_data )) {
				$sql2 .= " ORDER BY " . $data ['sort'];
			} else {
				$sql2 .= " ORDER BY username";
			}
			
			if (isset ( $data ['order'] ) && ($data ['order'] == 'DESC')) {
				$sql2 .= " DESC";
			} else {
				$sql2 .= " ASC";
			}
			
			if (isset ( $data ['start'] ) || isset ( $data ['limit'] )) {
				if ($data ['start'] < 0) {
					$data ['start'] = 0;
				}
				
				if ($data ['limit'] < 1) {
					$data ['limit'] = 20;
				}
				
				$sql2 .= " LIMIT " . ( int ) $data ['start'] . "," . ( int ) $data ['limit'];
			}
			
			/*
			 * $cacheid = $facilities_id.'.getUsersByFacility';
			 *
			 * $this->load->model('api/cache');
			 * $frusers = $this->model_api_cache->getcache($cacheid);
			 *
			 * if (!$frusers) {
			 * $query = $this->db->query($sql2);
			 * $frusers = $query->rows;
			 * $this->model_api_cache->setcache($cacheid,$frusers);
			 * }
			 *
			 *
			 * return $frusers;
			 */
			
			$query2 = $this->db->query ( $sql2 );
			return $query2->rows;
		}
	}
}
?>