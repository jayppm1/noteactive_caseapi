<?php 
 header('Access-Control-Allow-Origin:'.$_SERVER['HTTP_ORIGIN']);
 header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
 header('Access-Control-Max-Age: 1000');
 header('Access-Control-Allow-Headers: Content-Type');
 header('Content-type: application/json');
 header('Content-Type: text/html; charset=utf-8');
 header("Content-type: bitmap; charset=utf-8");
 
class Controllerservices2user extends Controller { 
	private $error = array();
	
	public function validateuser(){
		
		
		try{
			
			$this->load->model('activity/activity');
		$this->model_activity_activity->addActivitySave('validateuser', $this->request->post, 'request');
		
		$this->data['facilitiess'] = array();
		
		
		$json = array();
		
		$this->load->model('notes/notes');
		$this->language->load('notes/notes');
	
		
		if($this->request->post['user_id'] != null && $this->request->post['user_id'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUserByAccessKey($this->request->post['user_id']);

			if (($user_info['status'] == '0')) {
				$json['warning'] = 'User not exist!';
				$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
			}
			
			
		}
		
		
		
		if($json['warning'] == null && $json['warning'] == ""){
			
			$this->load->model('facilities/facilities');
			$this->load->model('user/user');
			$userdetail = $this->model_user_user->getUserByAccessKey($this->request->post['user_id']);
			
			if(!empty($userdetail)){
			$phone_number = $userdetail['phone_number'];
			$randomNum = mt_rand(100000, 999999);
			$message = 'Your OTP for activation is '.$randomNum;
			$this->load->model('api/smsapi');
			$sdata = array();
			$sdata['message'] = $message;
			$sdata['phone_number'] = $phone_number;
			
			if ($userdetail['facilities'] != null && $userdetail['facilities'] != "") {
				$facilities = explode(',', $userdetail['facilities']);
				
				
				foreach ($facilities as $facility) {
					$facilities_info = $this->model_facilities_facilities->getfacilities($facility);
					
						$facilities_id = $facilities_info['facilities_id'];
						break;
				}
			}
			
			$sdata['facilities_id'] = $facilities_id;
				
			if($userdetail['phone_number'] != null && $userdetail['phone_number'] != ""){	
				$response = $this->model_api_smsapi->sendsms($sdata);
			}
			
			
			$facilities_info = $this->model_facilities_facilities->getfacilities($facilities_id);
			
			$this->load->model('setting/timezone');
				
			$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
			$timezone_name = $timezone_info['timezone_value'];
			$timeZone = date_default_timezone_set($timezone_name);
			
			$date_added11 = date('Y-m-d H:i:s', strtotime('now'));
			
			$share_note_otp = rand(0,100000);
			
			$dataotp = array(
				'user_id' => $userdetail['user_id'],
				'otp' => $randomNum,
				'date_added' => $date_added11,
				'response' => $response,
				'facilities_id' => $facilities_id,
				'notes_id' => '0',
				'alternate_email' => $userdetail['email'],
				'share_note_otp' => $share_note_otp,
				'status' => '1',
				'otp_type' => 'adminuservalidate',
			);
			
			$this->model_user_user->insertUserOTP($dataotp);
			
			if($userdetail['email'] != null && $userdetail['email'] != ""){
				
				$this->load->model('api/emailapi');
			
				/*$edata = array();
				$edata['message'] = $message;
				$edata['subject'] = $facilities_info['facility'].' | '.'Your verification code';
				$edata['user_email'] = $userdetail['email'];
					
				$email_status = $this->model_api_emailapi->sendmail($edata);*/

				 $edata['facility'] = $facilities_info['facility'];
                $edata['user_email'] = $userdetail['email'];
                $edata['when_date']=date("l");
                $edata['who_user']=$userdetail['username'];
                $edata['type']="10";
                $edata['notes_description']=$message;
                $email_status=$this->model_api_emailapi->createMails($edata);
			}
			
			
			$this->data['facilitiess'][] = array(
				 'warning'  => '1',
				 'facilities_id' => $facilities_id,
				 'share_note_otp' => $share_note_otp,
				
			);
			
			$error = true;
			}else{
				$this->data['facilitiess'][] = array(
					'warning'  => "Please enter valid activation key",
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
				'data' => 'Error in validateuser '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('validateuser', $activity_data2);
		
		
		} 
	}
	
	
	public function resenduserotp(){
		
		
		try{
			
			$this->load->model('activity/activity');
		$this->model_activity_activity->addActivitySave('resenduserotp', $this->request->post, 'request');
		
		$this->data['facilitiess'] = array();
		
		
		$json = array();
		
		$this->load->model('notes/notes');
		$this->language->load('notes/notes');
	
		
		if($this->request->post['user_id'] != null && $this->request->post['user_id'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUserByAccessKey($this->request->post['user_id']);

			if (($user_info['status'] == '0')) {
				$json['warning'] = 'User not exist!';
				$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
			}
			
			
		}
		
		
		
		if($json['warning'] == null && $json['warning'] == ""){
			
			$this->load->model('facilities/facilities');
			$this->load->model('user/user');
			$getUserdetail = $this->model_user_user->getUserByAccessKey($this->request->post['user_id']);
			$phone_number = $getUserdetail['phone_number'];
			
			$facilities_info = $this->model_facilities_facilities->getfacilities($this->request->post['facilities_id']);
					
			$this->load->model('setting/timezone');
				
			$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
			$timezone_name = $timezone_info['timezone_value'];
			
			$timeZone = date_default_timezone_set($timezone_name);
			$date_added11 = date('Y-m-d H:i:s', strtotime('now'));
			
			$date_added1221 = date('Y-m-d H:i:s', strtotime(' 0 minutes', strtotime($date_added11)));
			$current_date_plus = date('Y-m-d H:i:s', strtotime(' +15 minutes', strtotime($date_added11)));
			
			$data = array(
					'user_id' => $getUserdetail['user_id'],
					'otp_type' => 'adminuservalidate',
					'date_added_from' => $date_added1221,
					'date_added_to' => $current_date_plus,
					'facilities_id' => $this->request->post['facilities_id'],
					'notes_id' => '0',
					'share_note_otp' => $this->request->post['share_note_otp']
			);
			
			$getUserdetail1 = $this->model_user_user->getuserOPT($data);
		   
			if ($getUserdetail1['otp'] != null && $getUserdetail1['otp'] != "") {
				$randomNum = $getUserdetail1['otp'];
				$message = 'Your OTP for activation is ' . $randomNum;
				
				$this->load->model('api/smsapi');
				$sdata = array();
				$sdata['message'] = $message;
				$sdata['phone_number'] = $phone_number;
				
				
				$sdata['facilities_id'] = $this->request->post['facilities_id'];
				
				if($phone_number != null && $phone_number != ""){
					$response = $this->model_api_smsapi->sendsms($sdata);
				}
				
				
				if($getUserdetail['email'] != null && $getUserdetail['email'] != ""){
					
					$this->load->model('api/emailapi');
				
					/*$edata = array();
					$edata['message'] = $message;
					$edata['subject'] = $facilities_info['facility'].' | '.'Your verification code';
					$edata['user_email'] = $getUserdetail['email'];
						
					$email_status = $this->model_api_emailapi->sendmail($edata);*/

					 $edata['facility'] = $facilities_info['facility'];
                $edata['user_email'] = $getUserdetail['email'];
                $edata['when_date']=date("l");
                $edata['who_user']=$getUserdetail['username'];
                $edata['type']="10";
                $edata['notes_description']=$message;
                $email_status=$this->model_api_emailapi->createMails($edata);
				}
				
				
				$this->data['facilitiess'][] = array(
					 'warning'  => '1',
					 'facilities_id' => $this->request->post['facilities_id'],
					 'share_note_otp' => $this->request->post['share_note_otp'],
					
				);
				
				$error = true;
				
			} else {
				
				$json['warning'] = 'Your OTP expire';
				$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
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
				'data' => 'Error in resenduserotp '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('resenduserotp', $activity_data2);
		
		
		} 
	}
	
	
	public function validateuserotp(){
		
		
		try{
			
			$this->load->model('activity/activity');
		$this->model_activity_activity->addActivitySave('validateuserotp', $this->request->post, 'request');
		
		$this->data['facilitiess'] = array();
		
		
		$json = array();
		
		$this->load->model('notes/notes');
		$this->language->load('notes/notes');
		$this->load->model('facilities/facilities');
		
		if($this->request->post['user_id'] != null && $this->request->post['user_id'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUserByAccessKey($this->request->post['user_id']);

			if (($user_info['status'] == '0')) {
				$json['warning'] = 'User not exist!';
				$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
			}
			
			
			if (($user_info['user_pin'] != $this->request->post['user_pin'])) {
				$json['warning'] = 'Please enter valid user pin!';
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
			$getUserdetail = $this->model_user_user->getUserByAccessKey($this->request->post['user_id']);
			
			$facilities_info = $this->model_facilities_facilities->getfacilities($this->request->post['facilities_id']);
					
			$this->load->model('setting/timezone');
				
			$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
			$timezone_name = $timezone_info['timezone_value'];
			$timeZone = date_default_timezone_set($timezone_name);
			$date_added11 = date('Y-m-d H:i:s', strtotime('now'));
			
			$date_added1221 = date('Y-m-d H:i:s', strtotime(' 0 minutes', strtotime($date_added11)));
			$current_date_plus = date('Y-m-d H:i:s', strtotime(' +15 minutes', strtotime($date_added11)));
			
			$data = array(
					'user_id' => $getUserdetail['user_id'],
					'otp_type' => 'adminuservalidate',
					'date_added_from' => $date_added1221,
					'date_added_to' => $current_date_plus,
					'facilities_id' => $this->request->post['facilities_id'],
					'notes_id' => '0',
					'share_note_otp' => $this->request->post['share_note_otp']
			);
			
			//var_dump($data);
			$getUserdetail1 = $this->model_user_user->getuserOPT($data);
			
			
			if ($this->request->post['user_otp'] != $getUserdetail1['otp']) {
				$json['warning'] = 'Please enter valid OTP';
				$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
			}
				
		}
		
		
		
		if($json['warning'] == null && $json['warning'] == ""){
			
			$this->load->model('facilities/facilities');
			$this->load->model('user/user');
			$userdetail = $this->model_user_user->getUserByAccessKey($this->request->post['user_id']);
			
			
			$this->load->model('user/user_group');
			$user_role_info = $this->model_user_user_group->getUserGroup($userdetail['user_group_id']);
			
			$length = 32;
			$session_id = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
			
			$this->model_user_user->updateUserSession($userdetail['user_id'], $session_id);
			
			$this->data['facilitiess'][] = array(
				 'warning'  => '1',
				 'parent_user_id' => $userdetail['user_id'],
				 'session_id' => $session_id,
				 'access_dashboard' => $user_role_info['access_dashboard'],
				
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
				'data' => 'Error in validateuser '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('validateuser', $activity_data2);
		
		
		} 
	}
	
	
	public function updateuserinfo(){
		
		
		try{
			
			$this->load->model('activity/activity');
		$this->model_activity_activity->addActivitySave('updateuserinfo', $this->request->post, 'request');
		
		$this->data['facilitiess'] = array();
		
		
		$json = array();
		
		$this->load->model('notes/notes');
		$this->language->load('notes/notes');
	
		
		if($this->request->post['user_id'] != null && $this->request->post['user_id'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUserByAccessKey($this->request->post['user_id']);

			if (($user_info['status'] == '0')) {
				$json['warning'] = 'User not exist!';
				$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
			}
			
			if (($user_info['session_id'] != $this->request->post['session_id'])) {
				$json['warning'] = 'Unauthorized error!';
				$facilitiessee = array();
					$facilitiessee[] = array(
						'warning'  => $json['warning'],
					);
					$error = false;
					
					$value = array('results'=>$facilitiessee,'status'=>false);

				return $this->response->setOutput(json_encode($value));
				
			}
			
			
		}
		
		
		 if ($this->request->post['user_pin'] != '' && $this->request->post['user_pin'] != null) {
            if ($this->request->post['user_pin'] != $this->request->post['confirm_user_pin']) {
				$json['warning'] = 'Userpin and Userpin confirmation do not match';
				$facilitiessee = array();
					$facilitiessee[] = array(
						'warning'  => $json['warning'],
					);
					$error = false;
					
					$value = array('results'=>$facilitiessee,'status'=>false);

				return $this->response->setOutput(json_encode($value));
            }
        }
		
		if ($this->request->post['password'] != '' && $this->request->post['password'] != null) {
            if ($this->config->get('config_password_protected') == '1') {
                
                $password = $this->request->post['password'];
                $uppercase = preg_match('@[A-Z]@', $password);
                $lowercase = preg_match('@[a-z]@', $password);
                $number = preg_match('@[0-9]@', $password);
                $length = preg_match("@^.{8,32}$@", $password);
                
                if (! $uppercase || ! $lowercase || ! $number || ! $length) {
					$json['warning'] = "The password must contain lower case characters, upper case characters and numbers. It's length should be between 8 and 32 characters.";
					$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
                }
            } 
            if ($this->request->post['password'] != $this->request->post['confirm_password']) {
				$json['warning'] = "Password and password confirmation do not match!";
					$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
            }
        }
		
		
		if($json['warning'] == null && $json['warning'] == ""){
			
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUserByAccessKey($this->request->post['user_id']);
			
			$newpin = substr($user_info['user_pin'], 2); 
			$user_pin111 = $newpin;
			//var_dump($user_pin111);
			
			$randomChar = str_split($user_info['user_pin'], 2);
			$prefix_userpin = $randomChar[0];
			
			$user_prefix = $prefix_userpin.$this->request->post['user_pin'];
		
			$q1total = $this->model_user_user->getDatabyuserpin($user_prefix);
			
			if($q1total > 1){
				$activeKey = $accessKey.$this->request->post['lastname'];
				
				$randomChar = str_split($activeKey, 2);
				foreach($randomChar as $characters){
					$user_prefix1 = $characters.$this->request->post['user_pin'];
					$q2total = $this->model_user_user->getDatabyuserpin($user_prefix1);
					if($q2total == 0){
						$user_prefix = $user_prefix1;
						break;
					}
				}
			}else{
				$user_prefix = $prefix_userpin.$this->request->post['user_pin'];
			}
			
			$fdata = array();
			$fdata['user_id'] = $user_info['user_id'];
			$fdata['fname'] = $this->request->post['firstname'];
			$fdata['lname'] = $this->request->post['lastname'];
			$fdata['email'] = $this->request->post['email'];
			$fdata['contact'] = $this->request->post['std_code'].$this->request->post['phone_number'];
			$fdata['std_code'] = $this->request->post['std_code'];
			$fdata['password'] = $this->request->post['password'];
			$fdata['user_pin'] = $user_prefix;
			
			$this->model_user_user->editUserByKey($fdata);
			
			if(!empty($_FILES["files"])){
				foreach($_FILES["files"]["tmp_name"] as $key=>$tmp_name){
					$file_name=$_FILES["files"]["name"][$key];
					$outputFolder=$_FILES["files"]["tmp_name"][$key];
					$ext=pathinfo($file_name,PATHINFO_EXTENSION);
					
					//var_dump($_FILES["files"]["tmp_name"][$key]);
					//var_dump($directory);
					
					$notes_file = 'user' . rand() . '.' . $ext;
					//$notes_file = $file_name;
					
					//require_once(DIR_SYSTEM . 'library/awsstorage/s3_config_facerecognition.php');
					
					$enroll_image = $s3file;
					$outputFolderUrl = $s3file;
					
					/*$result_inser_user_img22 = $this->awsimageconfig->indexFacesbyuser($outputFolderUrl, $user_info['username']);
					
					foreach($result_inser_user_img22['FaceRecords'] as $b){
						$FaceId = $b['Face']['FaceId'];
						$ImageId = $b['Face']['ImageId'];
					}*/
					
					$date_added = date('Y-m-d H:i:s', strtotime('now'));
					$fdata = array();
					$fdata['user_id'] = $user_info['user_id'];
					$fdata['enroll_image'] = $enroll_image;
					$fdata['FaceId'] = $FaceId;
					$fdata['ImageId'] = $ImageId;
					$fdata['date_added'] = $date_added;
					
					//var_dump($fdata);
					
					
					$user_enroll_id = $this->model_user_user->addUserenroll($fdata);
					
					$metadata = array();
					$metadata['username'] = $user_info['username'];
					$metadata['user_id'] = $user_info['user_id'];
					$metadata['firstname'] = $user_info['firstname'];
					$metadata['lastname'] = $user_info['lastname'];
					$metadata['facilities'] = $user_info['facilities'];
					$metadata['user_enroll_id'] = $user_enroll_id;
					
					
					$this->load->model('customer/customer');
					$customer_info = $this->model_customer_customer->getcustomer($user_info['customer_key']);
					$customer_bucket = $customer_info['bucket'];
					
					
					$s3file = $this->awsimageconfig->uploadFile2($customer_bucket, $notes_file, $outputFolder, $metadata);
					
					$this->model_user_user->updateUserenroll($s3file, $user_enroll_id);
					
					
				}
			}
				
			
			$this->data['facilitiess'][] = array(
				'warning'  => '1',
				'enroll_image'  => $enroll_image,
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
				'data' => 'Error in updateuserinfo '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('updateuserinfo', $activity_data2);
		
		
		} 
	}
	
	
	public function getuserbyaccesskey(){
		
		
		try{
		$this->data['facilitiess'] = array();
		
		
		$json = array();
		
		$this->load->model('notes/notes');
		$this->language->load('notes/notes');
	
		
		if($this->request->post['user_id'] != null && $this->request->post['user_id'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUserByAccessKey($this->request->post['user_id']);

			if (($user_info['status'] == '0')) {
				$json['warning'] = 'User not exist!';
				$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
			}
			
			/*if (($user_info['session_id'] != $this->request->post['session_id'])) {
				$json['warning'] = 'Unauthorized error!';
				$facilitiessee = array();
					$facilitiessee[] = array(
						'warning'  => $json['warning'],
					);
					$error = false;
					
					$value = array('results'=>$facilitiessee,'status'=>false);

				return $this->response->setOutput(json_encode($value));
				
			}*/
			
			
		}
		
		
		
		if($json['warning'] == null && $json['warning'] == ""){
			
			$this->load->model('notes/notes');
			
			$this->load->model('user/user');
			$user = $this->model_user_user->getUserByAccessKey($this->request->post['user_id']);
			
			if(!empty($user)){
		
			 $pin = str_split($user['user_pin'], 2);
			$newpin = substr($user['user_pin'], 2);
			
			if ($newpin) {
				$newpin11 = $newpin;
			} else {
				$newpin11 = "";
			}
			
			
			
			
			if($user['std_code'] != null && $user['std_code'] != ""){
				$std_code = $user['std_code'];
			}else{
				
				$number = $user['phone_number'];
				$countrys = array(

				'1' => '1',
				'2' => '2',
				'3' => '3',
				'44' => '44',
				'123' => '123',
				'971' =>'972',
				'91' =>'91',
				'92' =>'92'
				);

				$i = 4;
				$country = "";
				while ($i > 0) {
					if (isset($countrys[substr($number, 0, $i)])) {
						$country = $countrys[substr($number, 0, $i)];
						break;
					} else {
						$i--;
					}
				}
				
				$std_code = $country;
			}
			
			
			$phone_number = "";
			if($user['phone_number'] != null && $user['phone_number'] != ""){
				$country_code = $std_code;
				$phone_no = $user['phone_number'];
				$phone_number = preg_replace('/^\+?'.$country_code.'|\|'.$country_code.'|\D/', '', ($phone_no));
				
				//$phone_number = str_replace($user['std_code'],'',$user['phone_number']);
			}
			
			$this->data['facilitiess'][] = array(
				 'user_id' => $user['user_id'],
					'username' => $user['username'],
					'firstname' => $user['firstname'],
					'lastname' => $user['lastname'],
					'email' => $user['email'],
					// 'user_pin' => $user['user_pin'],
					'user_pin' => $newpin11,
					'prefix_userpin' => $pin[0],
					'phone_number' => $phone_number,
					'std_code' => $std_code,
					'activationKey' => $user['activationKey'],
					//'enroll_image' => $user['enroll_image']
			);
			$error = true;
			}else{
				$this->data['facilitiess'][] = array(
				'warning'  => "Plaease enter valid user",
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
				'data' => 'Error in getuserbyaccesskey '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('getuserbyaccesskey', $activity_data2);
		
		
		} 
	}
	
	public function getuserenroll(){
		
		
		try{
		$this->data['facilitiess'] = array();
		
		
		$json = array();
		
		$this->load->model('notes/notes');
		$this->language->load('notes/notes');
	
		
		if($this->request->post['user_id'] != null && $this->request->post['user_id'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUserByAccessKey($this->request->post['user_id']);

			if (($user_info['status'] == '0')) {
				$json['warning'] = 'User not exist!';
				$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
			}
			
			/*if (($user_info['session_id'] != $this->request->post['session_id'])) {
				$json['warning'] = 'Unauthorized error!';
				$facilitiessee = array();
					$facilitiessee[] = array(
						'warning'  => $json['warning'],
					);
					$error = false;
					
					$value = array('results'=>$facilitiessee,'status'=>false);

				return $this->response->setOutput(json_encode($value));
				
			}
			*/
			
		}
		
		
		
		if($json['warning'] == null && $json['warning'] == ""){
			
			$this->load->model('notes/notes');
			
			$this->load->model('user/user');
			$user = $this->model_user_user->getUserByAccessKey($this->request->post['user_id']);
			
			
			$user_enrolls = $this->model_user_user->getenroll_images($user['user_id']);
			
			foreach ($user_enrolls as $user_enroll) {
				$this->data['facilitiess'][] = array(
					 'user_id' => $user['user_id'],
						'user_enroll_id' => $user_enroll['user_enroll_id'],
						'enroll_image' => $user_enroll['enroll_image'],
				);
			}
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
				'data' => 'Error in getuserenroll '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('getuserenroll', $activity_data2);
		
		
		} 
	}
	
	public function deleteuserenroll(){
		
		
		try{
			
			$this->load->model('activity/activity');
		$this->model_activity_activity->addActivitySave('deleteuserenroll', $this->request->post, 'request');
		
		$this->data['facilitiess'] = array();
		
		
		$json = array();
		
		$this->load->model('notes/notes');
		$this->language->load('notes/notes');
	
		
		if($this->request->post['user_id'] != null && $this->request->post['user_id'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUserByAccessKey($this->request->post['user_id']);

			if (($user_info['status'] == '0')) {
				$json['warning'] = 'User not exist!';
				$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
			}
			
			if (($user_info['session_id'] != $this->request->post['session_id'])) {
				$json['warning'] = 'Unauthorized error!';
				$facilitiessee = array();
					$facilitiessee[] = array(
						'warning'  => $json['warning'],
					);
					$error = false;
					
					$value = array('results'=>$facilitiessee,'status'=>false);

				return $this->response->setOutput(json_encode($value));
				
			}
			
		}
		
		
		if($this->request->post['user_enroll_id'] == null && $this->request->post['user_enroll_id'] == ""){
			$json['warning'] = 'Please send enroll id!';
				$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
		}
		
		if($json['warning'] == null && $json['warning'] == ""){
			
			$this->load->model('notes/notes');
			
			$this->load->model('user/user');
			$this->model_user_user->deleteenrollid($this->request->post['user_enroll_id']);
			
			
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
				'data' => 'Error in deleteuserenroll '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('deleteuserenroll', $activity_data2);
		
		
		} 
	}
	
	public function getuserfacility(){
		
		
		try{
		$this->data['facilitiess'] = array();
		
		
		$json = array();
		
		$this->load->model('notes/notes');
		$this->language->load('notes/notes');
		$this->load->model('facilities/facilities');
		$this->load->model('customer/customer');
		
		if($this->request->post['parent_user_id'] != null && $this->request->post['parent_user_id'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUserbyupdate($this->request->post['parent_user_id']);

			if (($user_info['status'] == '0')) {
				$json['warning'] = 'User not exist!';
				$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
			}
			
			if (($user_info['session_id'] != $this->request->post['session_id'])) {
				$json['warning'] = 'Unauthorized error!';
				$facilitiessee = array();
					$facilitiessee[] = array(
						'warning'  => $json['warning'],
					);
					$error = false;
					
					$value = array('results'=>$facilitiessee,'status'=>false);

				return $this->response->setOutput(json_encode($value));
				
			}
			
		}
		
		if($json['warning'] == null && $json['warning'] == ""){
			
			$this->load->model('notes/notes');
			
			$this->load->model('user/user');
			$userdetail = $this->model_user_user->getUserbyupdate($this->request->post['parent_user_id']);
			
			$facilitiess = array();
			$user_roles = array();
			if ($userdetail['facilities'] != null && $userdetail['facilities'] != "") {
				$facilities = explode(',', $userdetail['facilities']);
				$ssdata = array();
				
				$customer_info = $this->model_customer_customer->getcustomer($userdetail['customer_key']);
				
				$ssdata['customer_key'] = $customer_info['customer_key'];
				$facilities1 = $this->model_facilities_facilities->getfacilitiess($ssdata);
				foreach ($facilities1 as $facility) {
					$facilitiess[] = array(
						'facilities_id' => $facility['facilities_id'],
						'facility' => $facility['facility'],
					);
				}
			}
			
			$fdata = array();
			$fdata['customer_key'] = $userdetail['customer_key'];
			$this->load->model('user/user_group');
			$userroles = $this->model_user_user_group->getUserGroups($fdata);
			
			if ($userroles != null && $userroles != "") {
				foreach ($userroles as $userrole) {
					$user_roles[] = array(
						'user_group_id' => $userrole['user_group_id'],
						'name' => $userrole['name'],
					);
				}
			}
			
			$this->data['facilitiess'][] = array(
					'facilitiess' => $facilitiess,
					'user_roles' => $user_roles,
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
				'data' => 'Error in getuserfacility '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('getuserfacility', $activity_data2);
		
		
		} 
	}
	
	
	public function getusers(){
		
		
		try{
		$this->data['facilitiess'] = array();
		
		
		$json = array();
		
		$this->load->model('notes/notes');
		$this->language->load('notes/notes');
		$this->load->model('facilities/facilities');
		
		if($this->request->post['parent_user_id'] != null && $this->request->post['parent_user_id'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUserbyupdate($this->request->post['parent_user_id']);

			if (($user_info['status'] == '0')) {
				$json['warning'] = 'User not exist!';
				$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
			}
			
			if (($user_info['session_id'] != $this->request->post['session_id'])) {
				$json['warning'] = 'Unauthorized error!';
				$facilitiessee = array();
					$facilitiessee[] = array(
						'warning'  => $json['warning'],
					);
					$error = false;
					
					$value = array('results'=>$facilitiessee,'status'=>false);

				return $this->response->setOutput(json_encode($value));
				
			}
			
		}
		
		
		
		if($json['warning'] == null && $json['warning'] == ""){
			
			$this->load->model('notes/notes');
			
			$this->load->model('user/user');
			$userdetail = $this->model_user_user->getUserbyupdate($this->request->post['parent_user_id']);
			
			
			$fdata = array();
			$fdata['customer_key'] = $userdetail['customer_key'];
			$userroles = $this->model_user_user->getallUsers($fdata);
			
			if ($userroles != null && $userroles != "") {
				
				
				foreach ($userroles as $userrole) {
					if($userrole['facilities'] != null && $userrole['facilities'] != ""){
					$fata = array();
					$fata['facilities'] = $userrole['facilities'];
					
					$facilities = $this->model_facilities_facilities->getfacilitiess($fata);
					
					$fffname = "";
					if (! empty($facilities)) {
						foreach ($facilities as $faciliti) {
							$fffname .= $faciliti['facility'] . ", ";
						}
					}
					}else{
						$fffname = "";
					}
				
					$this->data['facilitiess'][] = array(
						'user_id' => $userrole['user_id'],
						'facility' => $fffname,
						'firstname' => $userrole['firstname'],
						'lastname' => $userrole['lastname'],
						'username' => $userrole['username'],
						'email' => $userrole['email'],
						'status' => $userrole['status'],
						'activationKey' => $userrole['activationKey'],
					);
				}
			}
			
			
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
				'data' => 'Error in getusers '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('getusers', $activity_data2);
		
		
		} 
	}
	
	
	public function jsonaddUser(){
		try{
		
		$this->load->model('activity/activity');
		$this->model_activity_activity->addActivitySave('jsonaddUser', $this->request->post, 'request');
		
		$this->data['facilitiess'] = array();
		
		$json = array();
		
		$this->load->model('licence/licence');
		$this->load->model('user/user');
		$this->load->model('facilities/facilities');
		
		
		if($this->request->post['parent_user_id'] == null && $this->request->post['parent_user_id'] == ""){
			$json['warning'] = 'Parent user id is required!';
					$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
		}
		
		if($this->request->post['parent_user_id'] != null && $this->request->post['parent_user_id'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUserbyupdate($this->request->post['parent_user_id']);

			if (($user_info['session_id'] != $this->request->post['session_id'])) {
				$json['warning'] = 'Unauthorized error!';
				$facilitiessee = array();
					$facilitiessee[] = array(
						'warning'  => $json['warning'],
					);
					$error = false;
					
					$value = array('results'=>$facilitiessee,'status'=>false);

				return $this->response->setOutput(json_encode($value));
				
			}
			
		}
		
		if(trim(preg_replace('/\s+/',' ',$this->request->post['username'])) == null && trim(preg_replace('/\s+/',' ',$this->request->post['username'])) == ""){
			$json['warning'] = 'Username is required!';
					$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
		}
		
		
		if($this->request->post['username'] != null && $this->request->post['username'] != ""){
			
			$user_info = $this->model_user_user->getUserByUsername($this->request->post['username']);
			
			if ($user_info && ($this->request->get['user_id'] != $user_info['user_id'])) {
				$json['warning'] = 'Username already exist!';
				$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
            }
			
		}
		
		
		if($this->request->post['activationKey'] == null && $this->request->post['activationKey'] == ""){
			
			  
			$json['warning'] = 'ActivationKey is required!';
					$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
		}
		
		
		if($this->request->post['activationKey'] != null && $this->request->post['activationKey'] != ""){
			
			$this->load->model('licence/licence');
            $key_info = $this->model_licence_licence->checkaccessKey($this->request->post['activationKey']);
		
		
			if ($key_info && ($this->request->post['username'] != $key_info->username)) {
				$json['warning'] = 'Access key already exist!';
				$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
            }
		}
		
		if($this->request->post['user_pin'] != null && $this->request->post['user_pin'] != ""){
			if ((utf8_strlen($this->request->post['user_pin']) >= 4) && (utf8_strlen($this->request->post['user_pin']) <= 15)) {
				
				
				$ac4 = strtolower($this->request->post['activationKey']);
				$ac1 = str_replace('o', '', $ac4);
				$ac2 = str_replace('i', '', $ac1);
				$ac3 = str_replace('l', '', $ac2);
				
				
				$firstchar = substr($ac3, 0, 1);
				$lastchar = substr($ac3, - 1, 1);
				
				//$user_prefix = $firstchar . $lastchar . $this->request->post['user_pin'];
				$user_prefix = $this->request->post['user_pin'];
				
				$this->load->model('user/user');
				$qtotal = $this->model_user_user->getDatabyuserpin($user_prefix);
				
				if ($qtotal > 1) {
					
					$json['warning'] = 'User pin already exist!';
					$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
					
				}
			} else {
				$json['warning'] = 'User pin must be between 4 and 15 length!!';
					$facilitiessee = array();
							$facilitiessee[] = array(
								'warning'  => $json['warning'],
							);
							$error = false;
							
							$value = array('results'=>$facilitiessee,'status'=>false);

						return $this->response->setOutput(json_encode($value));
			}
        }
		
		
		if($json['warning'] == null && $json['warning'] == ""){
			$user_info = $this->model_user_user->getUserbyupdate($this->request->post['parent_user_id']);
			
			
			
			
			$user_id = $this->model_user_user->addUser($this->request->post, $user_info['customer_key']);
			
			$key = str_replace(' ', '', $this->request->post['activationKey']);
			$this->model_licence_licence->addKeyactivation($key, $this->request->post['username'], $user_id);
			
			
			if(!empty($_FILES["files"])){
				foreach($_FILES["files"]["tmp_name"] as $key=>$tmp_name){
					$file_name=$_FILES["files"]["name"][$key];
					$outputFolder=$_FILES["files"]["tmp_name"][$key];
					$ext=pathinfo($file_name,PATHINFO_EXTENSION);
					
					//var_dump($_FILES["files"]["tmp_name"][$key]);
					//var_dump($directory);
					
					$notes_file = 'user' . rand() . '.' . $ext;
					//$notes_file = $file_name;
					
					//require_once(DIR_SYSTEM . 'library/awsstorage/s3_config_facerecognition.php');
					
					
					
					$enroll_image = $s3file;
					$outputFolderUrl = $s3file;
					
					/*$result_inser_user_img22 = $this->awsimageconfig->indexFacesbyuser($outputFolderUrl, $this->request->post['username']);
					
					foreach($result_inser_user_img22['FaceRecords'] as $b){
						$FaceId = $b['Face']['FaceId'];
						$ImageId = $b['Face']['ImageId'];
					}*/
					
					$date_added = date('Y-m-d H:i:s', strtotime('now'));
					$fdata = array();
					$fdata['user_id'] = $user_id;
					$fdata['enroll_image'] = $enroll_image;
					$fdata['FaceId'] = $FaceId;
					$fdata['ImageId'] = $ImageId;
					$fdata['date_added'] = $date_added;
					
					//var_dump($fdata);
					
					
					//$this->model_user_user->addUserenroll($fdata);
					
					$user_enroll_id = $this->model_user_user->addUserenroll($fdata);
					
					$metadata = array();
					$metadata['username'] = $this->request->post['username'];
					$metadata['user_id'] = $user_id;
					$metadata['firstname'] = $this->request->post['firstname'];
					$metadata['lastname'] = $this->request->post['lastname'];
					$metadata['facilities'] = $this->request->post['facilities'];
					$metadata['user_enroll_id'] = $user_enroll_id;
					
					
					$this->load->model('customer/customer');
					$customer_info = $this->model_customer_customer->getcustomer($user_info['customer_key']);
					$customer_bucket = $customer_info['bucket'];
					
					$s3file = $this->awsimageconfig->uploadFile2($customer_bucket, $notes_file, $outputFolder, $metadata);
					
					$this->model_user_user->updateUserenroll($s3file, $user_enroll_id);
					
					
				}
			}
		
		
		$this->data['facilitiess'][] = array(
				'warning'  => '1',
				'user_id'  => $user_id,
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
				'data' => 'Error in appservices jsonaddUser '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('jsonaddUser', $activity_data2);
		} 
	}
	
	public function jsoneditUser(){
		
		try{
			$this->load->model('activity/activity');
		$this->model_activity_activity->addActivitySave('jsoneditUser', $this->request->post, 'request');
		
			$this->data['facilitiess'] = array();
			$this->load->model('user/user');	
			$this->load->model('facilities/facilities');
			$json = array();
			
			if($this->request->post['parent_user_id'] == null && $this->request->post['parent_user_id'] == ""){
				$json['warning'] = 'Parent user id is required!';
						$facilitiessee = array();
							$facilitiessee[] = array(
								'warning'  => $json['warning'],
							);
							$error = false;
							
							$value = array('results'=>$facilitiessee,'status'=>false);

						return $this->response->setOutput(json_encode($value));
			}
			
			if($this->request->post['parent_user_id'] != null && $this->request->post['parent_user_id'] != ""){
				$this->load->model('user/user');
				$user_info = $this->model_user_user->getUserbyupdate($this->request->post['parent_user_id']);

				if (($user_info['session_id'] != $this->request->post['session_id'])) {
					$json['warning'] = 'Unauthorized error!';
					$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
					
				}
				
			}
		
		
			if($this->request->post['user_id'] == null && $this->request->post['user_id'] == ""){
				$json['warning'] = 'User Id is required!';
					$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
			}
			
			if($this->request->post['user_pin'] != null && $this->request->post['user_pin'] != ""){
				if ((utf8_strlen($this->request->post['user_pin']) >= 4) && (utf8_strlen($this->request->post['user_pin']) <= 15)) {
					
					
					$ac4 = strtolower($this->request->post['activationKey']);
					$ac1 = str_replace('o', '', $ac4);
					$ac2 = str_replace('i', '', $ac1);
					$ac3 = str_replace('l', '', $ac2);
					
					
					$firstchar = substr($ac3, 0, 1);
					$lastchar = substr($ac3, - 1, 1);
					
					//$user_prefix = $firstchar . $lastchar . $this->request->post['user_pin'];
					$user_prefix = $this->request->post['user_pin'];
					
					$this->load->model('user/user');
					$qtotal_info = $this->model_user_user->getDatabyuserpin2($user_prefix);
					
					if ($qtotal_info && ($this->request->post['user_id'] != $qtotal_info['user_id'])) {
						
						$json['warning'] = 'User pin already exist!';
						$facilitiessee = array();
							$facilitiessee[] = array(
								'warning'  => $json['warning'],
							);
							$error = false;
							
							$value = array('results'=>$facilitiessee,'status'=>false);

						return $this->response->setOutput(json_encode($value));
						
					}
				} else {
					$json['warning'] = 'User pin must be between 4 and 15 length!!';
						$facilitiessee = array();
								$facilitiessee[] = array(
									'warning'  => $json['warning'],
								);
								$error = false;
								
								$value = array('results'=>$facilitiessee,'status'=>false);

							return $this->response->setOutput(json_encode($value));
				}
			}
		
		
			if($json['warning'] == null && $json['warning'] == ""){
				
				$this->load->model('user/user');
				
				$user_info = $this->model_user_user->getUserbyupdate($this->request->post['parent_user_id']);
				
				$user_info2 = $this->model_user_user->getUserbyupdate($this->request->post['user_id']);
				
				$this->model_user_user->editUser($this->request->post['user_id'], $this->request->post, $user_info['customer_key']);
				
				
				if(!empty($_FILES["files"])){
					foreach($_FILES["files"]["tmp_name"] as $key=>$tmp_name){
						$file_name=$_FILES["files"]["name"][$key];
						$outputFolder=$_FILES["files"]["tmp_name"][$key];
						$ext=pathinfo($file_name,PATHINFO_EXTENSION);
						
						//var_dump($_FILES["files"]["tmp_name"][$key]);
						//var_dump($directory);
						
						$notes_file = 'user' . rand() . '.' . $ext;
						//$notes_file = $file_name;
						
						//require_once(DIR_SYSTEM . 'library/awsstorage/s3_config_facerecognition.php');
						
						
						
						$enroll_image = $s3file;
						$outputFolderUrl = $s3file;
						
						/*$result_inser_user_img22 = $this->awsimageconfig->indexFacesbyuser($outputFolderUrl, $user_info2['username']);
						
						foreach($result_inser_user_img22['FaceRecords'] as $b){
							$FaceId = $b['Face']['FaceId'];
							$ImageId = $b['Face']['ImageId'];
						}*/
						
						$date_added = date('Y-m-d H:i:s', strtotime('now'));
						$fdata = array();
						$fdata['user_id'] = $this->request->post['user_id'];
						$fdata['enroll_image'] = $enroll_image;
						$fdata['FaceId'] = $FaceId;
						$fdata['ImageId'] = $ImageId;
						$fdata['date_added'] = $date_added;
						
						//var_dump($fdata);
						
						
						$user_enroll_id = $this->model_user_user->addUserenroll($fdata);
					
						$metadata = array();
						$metadata['username'] = $user_info2['username'];
						$metadata['user_id'] = $user_info2['user_id'];
						$metadata['firstname'] = $user_info2['firstname'];
						$metadata['lastname'] = $user_info2['lastname'];
						$metadata['facilities'] = $user_info2['facilities'];
						$metadata['user_enroll_id'] = $user_enroll_id;
						
						$this->load->model('customer/customer');
						$customer_info = $this->model_customer_customer->getcustomer($user_info2['customer_key']);
						$customer_bucket = $customer_info['bucket'];
						
						$s3file = $this->awsimageconfig->uploadFile2($customer_bucket, $notes_file, $outputFolder, $metadata);
						
						$this->model_user_user->updateUserenroll($s3file, $user_enroll_id);
						
						
					}
				}
				
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
					'data' => 'Error in appservices jsoneditUser '.$e->getMessage(),
				);
				$this->model_activity_activity->addActivity('jsoneditUser', $activity_data2);
			} 
		
		
	}
	
	
	public function jsoneditUserstatus(){
		
		try{
			
			$this->load->model('activity/activity');
		$this->model_activity_activity->addActivitySave('jsoneditUserstatus', $this->request->post, 'request');
		
			$this->data['facilitiess'] = array();
			$this->load->model('user/user');	
			$this->load->model('facilities/facilities');
			$json = array();
			
			if($this->request->post['parent_user_id'] == null && $this->request->post['parent_user_id'] == ""){
				$json['warning'] = 'Parent user id is required!';
						$facilitiessee = array();
							$facilitiessee[] = array(
								'warning'  => $json['warning'],
							);
							$error = false;
							
							$value = array('results'=>$facilitiessee,'status'=>false);

						return $this->response->setOutput(json_encode($value));
			}
			
			if($this->request->post['parent_user_id'] != null && $this->request->post['parent_user_id'] != ""){
				$this->load->model('user/user');
				$user_info = $this->model_user_user->getUserbyupdate($this->request->post['parent_user_id']);

				if (($user_info['session_id'] != $this->request->post['session_id'])) {
					$json['warning'] = 'Unauthorized error!';
					$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
					
				}
				
			}
		
		
			if($this->request->post['user_id'] == null && $this->request->post['user_id'] == ""){
				$json['warning'] = 'User Id is required!';
					$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
			}
		
			if($json['warning'] == null && $json['warning'] == ""){
				
				$this->load->model('user/user');
				
				$user_info = $this->model_user_user->getUserbyupdate($this->request->post['parent_user_id']);
				
				$this->model_user_user->updateStatus($this->request->post['user_id']);
				
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
					'data' => 'Error in appservices jsoneditUserstatus '.$e->getMessage(),
				);
				$this->model_activity_activity->addActivity('jsoneditUserstatus', $activity_data2);
			} 
		
		
	}
	
	public function getuser(){
		
		
		try{
		$this->data['facilitiess'] = array();
		
		
		$json = array();
		
		$this->load->model('notes/notes');
		$this->language->load('notes/notes');
	
		
		if($this->request->post['parent_user_id'] == null && $this->request->post['parent_user_id'] == ""){
				$json['warning'] = 'Parent user id is required!';
						$facilitiessee = array();
							$facilitiessee[] = array(
								'warning'  => $json['warning'],
							);
							$error = false;
							
							$value = array('results'=>$facilitiessee,'status'=>false);

						return $this->response->setOutput(json_encode($value));
			}
			
			if($this->request->post['parent_user_id'] != null && $this->request->post['parent_user_id'] != ""){
				$this->load->model('user/user');
				$user_info = $this->model_user_user->getUserbyupdate($this->request->post['parent_user_id']);

				if (($user_info['session_id'] != $this->request->post['session_id'])) {
					$json['warning'] = 'Unauthorized error!';
					$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
					
				}
				
			}
		
		
			if($this->request->post['user_id'] == null && $this->request->post['user_id'] == ""){
				$json['warning'] = 'User Id is required!';
					$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
			}
			
			
		
		if($json['warning'] == null && $json['warning'] == ""){
			
			$this->load->model('notes/notes');
			
			$this->load->model('user/user');
			$user = $this->model_user_user->getUserbyupdate2($this->request->post['user_id']);
			
		
			 $pin = str_split($user['user_pin'], 2);
			$newpin = substr($user['user_pin'], 2);
			
			if ($newpin) {
				$newpin11 = $newpin;
			} else {
				$newpin11 = "";
			}
			
			
			
			if($user['std_code'] != null && $user['std_code'] != ""){
				$std_code = $user['std_code'];
			}else{
				
				$number = $user['phone_number'];
				$countrys = array(

				'1' => '1',
				'2' => '2',
				'3' => '3',
				'44' => '44',
				'123' => '123',
				'971' =>'972',
				'91' =>'91',
				'92' =>'92'
				);

				$i = 4;
				$country = "";
				while ($i > 0) {
					if (isset($countrys[substr($number, 0, $i)])) {
						$country = $countrys[substr($number, 0, $i)];
						break;
					} else {
						$i--;
					}
				}
				
				$std_code = $country;
			}
			
			$phone_number = "";
			if($user['phone_number'] != null && $user['phone_number'] != ""){
				
				$country_code = $std_code;
				$phone_no = $user['phone_number'];
				$phone_number = preg_replace('/^\+?'.$country_code.'|\|'.$country_code.'|\D/', '', ($phone_no));
				
				//$phone_number = str_replace($user['std_code'],'',$user['phone_number']);
			}
			
			
			$this->data['facilitiess'][] = array(
				 'user_id' => $user['user_id'],
					'username' => $user['username'],
					'firstname' => $user['firstname'],
					'lastname' => $user['lastname'],
					'email' => $user['email'],
					'user_pin' => $newpin11,
					'prefix_userpin' => $pin[0],
					'phone_number' => $phone_number,
					'std_code' => $std_code,
					'user_group_id' => $user['user_group_id'],
					'facilities' => $user['facilities'],
					'default_facilities_id' => $user['default_facilities_id'],
					'default_highlighter_id' => $user['default_highlighter_id'],
					'default_color' => $user['default_color'],
					'activationKey' => $user['activationKey'],
					'status' => $user['status'],
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
				'data' => 'Error in getuser '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('getuser', $activity_data2);
		
		
		} 
	}
	
	
	public function getuserimages(){
		
		
		try{
		$this->data['facilitiess'] = array();
		
		
		$json = array();
		
		$this->load->model('notes/notes');
		$this->language->load('notes/notes');
	
		
		if($this->request->post['parent_user_id'] == null && $this->request->post['parent_user_id'] == ""){
				$json['warning'] = 'Parent user id is required!';
						$facilitiessee = array();
							$facilitiessee[] = array(
								'warning'  => $json['warning'],
							);
							$error = false;
							
							$value = array('results'=>$facilitiessee,'status'=>false);

						return $this->response->setOutput(json_encode($value));
			}
			
			if($this->request->post['parent_user_id'] != null && $this->request->post['parent_user_id'] != ""){
				$this->load->model('user/user');
				$user_info = $this->model_user_user->getUserbyupdate($this->request->post['parent_user_id']);

				if (($user_info['session_id'] != $this->request->post['session_id'])) {
					$json['warning'] = 'Unauthorized error!';
					$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
					
				}
				
			}
		
		
			if($this->request->post['user_id'] == null && $this->request->post['user_id'] == ""){
				$json['warning'] = 'User Id is required!';
					$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
			}
			
			
		
		if($json['warning'] == null && $json['warning'] == ""){
			
			$this->load->model('notes/notes');
			
			$this->load->model('user/user');
			$user = $this->model_user_user->getUserbyupdate2($this->request->post['user_id']);
			
			
			$user_enrolls = $this->model_user_user->getenroll_images($user['user_id']);
			
			foreach ($user_enrolls as $user_enroll) {
				$this->data['facilitiess'][] = array(
					'user_id' => $user['user_id'],
					'user_enroll_id' => $user_enroll['user_enroll_id'],
					'enroll_image' => $user_enroll['enroll_image'],
				);
			}
			
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
				'data' => 'Error in getuserimages '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('getuserimages', $activity_data2);
		
		
		} 
	}
	
	
	public function jsoneditUserimage(){
		
		try{
			
			$this->load->model('activity/activity');
			$this->model_activity_activity->addActivitySave('jsoneditUserimage', $this->request->post, 'request');
		
			$this->data['facilitiess'] = array();
			$this->load->model('user/user');	
			$this->load->model('facilities/facilities');
			$json = array();
			
			if($this->request->post['parent_user_id'] == null && $this->request->post['parent_user_id'] == ""){
				$json['warning'] = 'Parent user id is required!';
						$facilitiessee = array();
							$facilitiessee[] = array(
								'warning'  => $json['warning'],
							);
							$error = false;
							
							$value = array('results'=>$facilitiessee,'status'=>false);

						return $this->response->setOutput(json_encode($value));
			}
			
			if($this->request->post['parent_user_id'] != null && $this->request->post['parent_user_id'] != ""){
				$this->load->model('user/user');
				$user_info = $this->model_user_user->getUserbyupdate($this->request->post['parent_user_id']);

				if (($user_info['session_id'] != $this->request->post['session_id'])) {
					$json['warning'] = 'Unauthorized error!';
					$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
					
				}
				
			}
		
		
			if($this->request->post['user_id'] == null && $this->request->post['user_id'] == ""){
				$json['warning'] = 'User Id is required!';
					$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
			}
			
			
			
			if($json['warning'] == null && $json['warning'] == ""){
				
				$this->load->model('user/user');
				
				$user_info = $this->model_user_user->getUserbyupdate($this->request->post['user_id']);
				
				
				if(!empty($_FILES["files"])){
					foreach($_FILES["files"]["tmp_name"] as $key=>$tmp_name){
						$file_name=$_FILES["files"]["name"][$key];
						$outputFolder=$_FILES["files"]["tmp_name"][$key];
						$ext=pathinfo($file_name,PATHINFO_EXTENSION);
						
						//var_dump($_FILES["files"]["tmp_name"][$key]);
						//var_dump($directory);
						
						$notes_file = 'user' . rand() . '.' . $ext;
						//$notes_file = $file_name;
						
						//require_once(DIR_SYSTEM . 'library/awsstorage/s3_config_facerecognition.php');
						
						
						
						$enroll_image = $s3file;
						$outputFolderUrl = $s3file;
						
						/*$result_inser_user_img22 = $this->awsimageconfig->indexFacesbyuser($outputFolderUrl, $user_info['username']);
						
						foreach($result_inser_user_img22['FaceRecords'] as $b){
							$FaceId = $b['Face']['FaceId'];
							$ImageId = $b['Face']['ImageId'];
						}*/
						
						$date_added = date('Y-m-d H:i:s', strtotime('now'));
						$fdata = array();
						$fdata['user_id'] = $this->request->post['user_id'];
						$fdata['enroll_image'] = $enroll_image;
						$fdata['FaceId'] = $FaceId;
						$fdata['ImageId'] = $ImageId;
						$fdata['date_added'] = $date_added;
						
						//var_dump($fdata);
						
						
						$user_enroll_id = $this->model_user_user->addUserenroll($fdata);
					
						$metadata = array();
						$metadata['username'] = $user_info['username'];
						$metadata['user_id'] = $user_info['user_id'];
						$metadata['firstname'] = $user_info['firstname'];
						$metadata['lastname'] = $user_info['lastname'];
						$metadata['facilities'] = $user_info['facilities'];
						$metadata['user_enroll_id'] = $user_enroll_id;
						
						
						$this->load->model('customer/customer');
						$customer_info = $this->model_customer_customer->getcustomer($user_info['customer_key']);
						$customer_bucket = $customer_info['bucket'];
						
						$s3file = $this->awsimageconfig->uploadFile2($customer_bucket, $notes_file, $outputFolder, $metadata);
						
						$this->model_user_user->updateUserenroll($s3file, $user_enroll_id);
						
						
					}
				}
				
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
					'data' => 'Error in appservices jsoneditUser '.$e->getMessage(),
				);
				$this->model_activity_activity->addActivity('jsoneditUser', $activity_data2);
			} 
		
		
	}
	
	
	public function getuserkey(){
		
		
		try{
		$this->data['facilitiess'] = array();
		
		
		$json = array();
		
		$this->load->model('notes/notes');
		$this->language->load('notes/notes');
	
		
		if($this->request->post['parent_user_id'] == null && $this->request->post['parent_user_id'] == ""){
				$json['warning'] = 'Parent user id is required!';
						$facilitiessee = array();
							$facilitiessee[] = array(
								'warning'  => $json['warning'],
							);
							$error = false;
							
							$value = array('results'=>$facilitiessee,'status'=>false);

						return $this->response->setOutput(json_encode($value));
			}
			
			if($this->request->post['parent_user_id'] != null && $this->request->post['parent_user_id'] != ""){
				$this->load->model('user/user');
				$user_info = $this->model_user_user->getUserbyupdate($this->request->post['parent_user_id']);

				if (($user_info['session_id'] != $this->request->post['session_id'])) {
					$json['warning'] = 'Unauthorized error!';
					$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
					
				}
			
			}
		
		
			if($this->request->post['firstname'] == null && $this->request->post['firstname'] == ""){
				$json['warning'] = 'First name is required!';
					$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
			}
			
			if($this->request->post['lastname'] == null && $this->request->post['lastname'] == ""){
				$json['warning'] = 'Last name is required!';
					$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
			}
			
			
		
		if($json['warning'] == null && $json['warning'] == ""){
			
			$firstchar = substr($this->request->post['firstname'], 0, 1);
			$lastchar = substr($this->request->post['lastname'], -1, 1);
			
			$user_prefix = $firstchar.$lastchar.rand(0, 100000);
			$this->load->model('user/user');
			$q1total = $this->model_user_user->getDatabyuserpin($user_prefix);
			
			if($q1total > 1){
				$activeKey = $data['firstname'].$data['lastname'];
				$randomChar = str_split($activeKey, 2);
				foreach($randomChar as $characters){
					$user_prefix1 = $characters.$data['user_pin'];
					$q2total = $this->model_user_user->getDatabyuserpin($user_prefix1);
					if($q2total == 0){
						$user_prefix = $user_prefix1;
						break;
					}
				}
			
			}
			
			
			$firstchar = substr($this->request->post['firstname'], 0, 1);
			$lastchar = substr($this->request->post['lastname'], -1, 1);
			
			
			$activationKey = $firstchar.$lastchar. $this->getactivation();
			
			$this->load->model('licence/licence');
            $key_info = $this->model_licence_licence->checkaccessKey($activationKey);
		
		
			if (!empty($key_info)) {
				$activationKey = $firstchar.$lastchar. $this->getactivation();
				$this->load->model('licence/licence');
				$key_info = $this->model_licence_licence->checkaccessKey($activationKey);
				if (!empty($key_info)) {
					$activationKey = $firstchar.$lastchar. $this->getactivation();
					$this->load->model('licence/licence');
					$key_info = $this->model_licence_licence->checkaccessKey($activationKey);
					if (!empty($key_info)) {
						$activationKey = $firstchar.$lastchar. $this->getactivation();
						$this->load->model('licence/licence');
						$key_info = $this->model_licence_licence->checkaccessKey($activationKey);
					}
				}
            }
			
			
			$this->data['facilitiess'][] = array(
					'user_pin' => $user_prefix,
					'activationKey' => $activationKey,
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
				'data' => 'Error in getuserkey '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('getuserkey', $activity_data2);
		
		
		} 
	}
	
	public function getactivation($n = 4) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
		$randomString = ''; 
	  
		for ($i = 0; $i < $n; $i++) { 
			$index = rand(0, strlen($characters) - 1); 
			$randomString .= $characters[$index]; 
		} 
	  
		return $randomString; 
	} 
	
	public function deleteuserimages(){
		
		
		try{
			
			$this->load->model('activity/activity');
			$this->model_activity_activity->addActivitySave('deleteuserimages', $this->request->post, 'request');
			
		$this->data['facilitiess'] = array();
		
		
		$json = array();
		
		$this->load->model('notes/notes');
		$this->language->load('notes/notes');
	
		
		if($this->request->post['parent_user_id'] == null && $this->request->post['parent_user_id'] == ""){
				$json['warning'] = 'Parent user id is required!';
						$facilitiessee = array();
							$facilitiessee[] = array(
								'warning'  => $json['warning'],
							);
							$error = false;
							
							$value = array('results'=>$facilitiessee,'status'=>false);

						return $this->response->setOutput(json_encode($value));
			}
			
			if($this->request->post['parent_user_id'] != null && $this->request->post['parent_user_id'] != ""){
				$this->load->model('user/user');
				$user_info = $this->model_user_user->getUserbyupdate($this->request->post['parent_user_id']);

				if (($user_info['session_id'] != $this->request->post['session_id'])) {
					$json['warning'] = 'Unauthorized error!';
					$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
					
				}
			
			}
		
		
		if($this->request->post['user_enroll_id'] == null && $this->request->post['user_enroll_id'] == ""){
			$json['warning'] = 'Please send enroll id!';
				$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
		}
		
		if($json['warning'] == null && $json['warning'] == ""){
			
			$this->load->model('notes/notes');
			
			$this->load->model('user/user');
			$this->model_user_user->deleteenrollid($this->request->post['user_enroll_id']);
			
			
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
				'data' => 'Error in deleteuserimages '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('deleteuserimages', $activity_data2);
		
		
		} 
	}

}