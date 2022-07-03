<?php 
header ( 'Access-Control-Allow-Origin:' . $_SERVER ['HTTP_ORIGIN'] );
header ( 'Access-Control-Allow-Methods: POST, GET, OPTIONS' );
header ( 'Access-Control-Max-Age: 1000' );
header ( 'Access-Control-Allow-Headers: Content-Type' );
header ( 'Content-type: application/json' );
header ( 'Content-Type: text/html; charset=utf-8' );
header ( "Content-type: bitmap; charset=utf-8" );
class Controllerservices2clientintake extends Controller { 
	
	
	public function index(){
		try{
			
		$this->load->model ( 'activity/activity' );
		$this->model_activity_activity->addActivitySave ( 'clientintake', $this->request->post, 'request' );
			
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
		
			if ($this->request->post ['emp_first_name'] == null && $this->request->post ['emp_first_name'] == "") {
					$json ['warning'] = 'Warning: Enter First Name';
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
			
			
			if ($this->request->post ['emp_last_name'] == null && $this->request->post ['emp_last_name'] == "") {
					$json ['warning'] = 'Warning: Enter Last Name';
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
			
			
			/*if ($this->request->post ['emp_extid'] == null && $this->request->post ['emp_extid'] == "") {
					$json ['warning'] = 'Warning: Enter Booking Id';
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

				$data = array ();
				$data ['facilities_id'] = $this->request->post ['facilities_id'];
				$data ['emp_first_name'] = $this->request->post ['emp_first_name'];
				$data ['emp_last_name'] = $this->request->post ['emp_last_name'];
				$data ['emp_middle_name'] = $this->request->post ['emp_middle_name'];
				$data ['ssn'] = $this->request->post ['ssn'];
				$data ['emp_extid'] = $this->request->post ['emp_extid'];
				//$data ['imageName_url'] = $this->request->post ['imageName_url'];
				$data ['imageName'] = $this->request->post ['imageName'];
				$data ['room_id'] = $this->request->post ['room_id'];
				$data ['user_id'] = $this->request->post ['user_id'];
				$data ['notes_pin'] = $this->request->post ['notes_pin'];
				$data ['current_enroll_image1'] = $this->request->post ['current_enroll_image1'];
				$data ['customlistvalues_id'] = $this->request->post ['customlistvalues_id'];
				$data ['gender'] = $this->request->post ['gender'];
				$data ['month_1'] = $this->request->post ['month_1'];
				$data ['day_1'] = $this->request->post ['day_1'];
				$data ['year_1'] = $this->request->post ['year_1'];
				$data ['tag_classification_id'] = $this->request->post ['tag_classification_id'];
				$data ['tag_status_id'] = $this->request->post ['tag_status_id'];
				$data ['date_added'] = $this->request->post ['date_added'];
				$data ['ccn'] = $this->request->post ['ccn'];
				$data ['tags_status_in'] = 'Admitted';
				$data ['discharge'] = '0';
				$data ['status'] = '1';
				
				if ($this->request->files ["imageName_url"] != null && $this->request->files ["imageName_url"] != "") {
					$extension = end ( explode ( ".", $this->request->files ["imageName_url"] ["name"] ) );
					$notes_file = 'devbolb' . rand () . '.' . $extension;
					$outputFolder = $this->request->files ["imageName_url"] ["tmp_name"];
					
					if ($this->config->get ( 'enable_storage' ) == '1') {
						/* AWS */
						// require_once(DIR_SYSTEM . 'library/awsstorage/s3_config.php');
						$s3file = $this->awsimageconfig->uploadFile ( $notes_file, $outputFolder, $this->request->post ['facilities_id'] );
						$outputFolderUrl = $s3file;
					}
					
					if ($this->config->get ( 'enable_storage' ) == '2') {
						/* AZURE */
						require_once (DIR_SYSTEM . 'library/azure_storage/config.php');
						// uploadBlobSample($blobClient, $outputFolder, $notes_file);
						$s3file = AZURE_URL . $notes_file;
						$outputFolderUrl = $s3file;
					}
					
					if ($this->config->get ( 'enable_storage' ) == '3') {
						/* LOCAL */
						$outputFolder = DIR_IMAGE . 'storage/' . $notes_file;
						move_uploaded_file ( $this->request->files ["file"] ["tmp_name"], $outputFolder );
						$s3file = HTTPS_SERVER . 'image/storage/' . $notes_file;
						$outputFolderUrl = $s3file;
					}
					
					$data ['imageName_url'] = $s3file;
					
				}
				
				
				$this->load->model('setting/tags');
				$tags_id = $this->model_setting_tags->addTags($data, $this->request->post ['facilities_id']);
				
				//$this->db->query("UPDATE `" . DB_PREFIX . "tags` SET status = '1' , discharge = '0' WHERE tags_id = '" . (int)$tags_id . "'");
		
				$this->load->model('setting/tags');
				
				if($this->request->post['facilities_id']){
				$this->load->model('facilities/facilities');
					$facilities_info = $this->model_facilities_facilities->getfacilities($this->request->post['facilities_id']);
					
					$this->load->model('setting/timezone');
						
					$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
					$facilitytimezone = $timezone_info['timezone_value'];
				}
				
				$data2 = array();
				$data2['tags_id'] = $tags_id;
				$data2['facilities_id'] = $this->request->post['facilities_id'];
				$data2['facilitytimezone'] = $facilitytimezone;
				
				$data2['phone_device_id'] = $this->request->post['phone_device_id'];
				
				$data2['tag_classification_id'] = $this->request->post ['tag_classification_id'];;
				$data2['tag_status_id'] = $this->request->post['tag_status_id'];
				
						
				if($this->request->post['is_android'] != null && $this->request->post['is_android'] != ""){
					$data2['is_android'] = $this->request->post['is_android'];
				}else{
					$data2['is_android'] = '1';
				}
				
				$notes_id = $this->model_setting_tags->addclientsign($this->request->post, $data2);
				
				
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
				'data' => 'Error in appservices jsonClientintake '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('app_jsonClientintake', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		} 	
	
	}


	public function editClient(){
		try{
				
				
			
		$this->load->model ( 'activity/activity' );
		$this->model_activity_activity->addActivitySave ( 'editClient', $this->request->post, 'request' );
			
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
		
			if ($this->request->post ['emp_first_name'] == null && $this->request->post ['emp_first_name'] == "") {
					$json ['warning'] = 'Warning: Enter First Name';
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
			
			
			if ($this->request->post ['emp_last_name'] == null && $this->request->post ['emp_last_name'] == "") {
					$json ['warning'] = 'Warning: Enter Last Name';
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
			
			
			/*if ($this->request->post ['emp_extid'] == null && $this->request->post ['emp_extid'] == "") {
					$json ['warning'] = 'Warning: Enter Booking Id';
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
			*/
			
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
			
			
			/*
			if($this->request->post['notes_pin'] != null && $this->request->post['notes_pin'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);
			
				//var_dump($user_info);

				
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
		*/
		
		
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
			
			/*
			
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
				*/ 
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
			
		
			
			$old_date_timestamp = strtotime($this->request->post ['dob']);
			$new_date = date('y-m-d', $old_date_timestamp); 
			
			if ($json ['warning'] == null && $json ['warning'] == "") {

				$data = array ();
				$data ['facilities_id'] = $this->request->post ['facilities_id'];
				$data ['emp_first_name'] = $this->request->post ['emp_first_name'];
				$data ['emp_last_name'] = $this->request->post ['emp_last_name'];
				$data ['emp_middle_name'] = $this->request->post ['emp_middle_name'];
				$data ['ssn'] = $this->request->post ['ssn'];
				$data ['emp_extid'] = $this->request->post ['emp_extid'];
				//$data ['imageName_url'] = $this->request->post ['imageName_url'];
				$data ['imageName'] = $this->request->post ['imageName'];
				$data ['room_id'] = $this->request->post ['room_id'];
				$data ['user_id'] = $this->request->post ['user_id'];
				$data ['notes_pin'] = $this->request->post ['notes_pin'];
				$data ['current_enroll_image1'] = $this->request->post ['current_enroll_image1'];
				$data ['customlistvalues_id'] = $this->request->post ['customlistvalues_id'];
				
				$data ['gender'] = $this->request->post ['gender'];
				$data ['month_1'] = date("m", strtotime($new_date)); 
				$data ['day_1'] = date("d", strtotime($new_date));
				$data ['year_1'] = date("Y", strtotime($new_date)); 
				$data ['tag_classification_id'] = $this->request->post ['tag_classification_id'];
				$data ['tag_status_id'] = $this->request->post ['tag_status_id'];
				$data ['date_added'] = $this->request->post ['date_added'];
				$data ['ccn'] = $this->request->post ['ccn'];
				
				$data ['tags_status_in'] = 'Admitted';
				$data ['discharge'] = '0';
				$data ['status'] = '1';
				
				
			
				
				if ($this->request->post ['client_url'] != null && $this->request->post ['client_url'] != "") {
            
						$image_parts = explode(";base64,", $this->request->post ['client_url']);
						
						
						$explode = explode(',', $this->request->post ['client_url']);
						$allowedExtensions = ['png', 'jpg', 'jpeg'];
						$format = str_replace(
								['data:image/', ';', 'base64'], 
								['', '', '',], 
								$explode[0]
						);
						
						
						
						$notes_file = uniqid() . '.'.$format;
						
						if ($format == 'jpeg') {
							$img = $this->request->post ['client_url'];
							$img = str_replace('data:image/jpeg;base64,', '', $img);
							$img = str_replace(' ', '+', $img);
							$Imgdata = base64_decode($img);
							
						}
						
						if ($format == 'jpg') {
							$img = $this->request->post ['client_url'];
							$img = str_replace('data:image/jpg;base64,', '', $img);
							$img = str_replace(' ', '+', $img);
							$Imgdata = base64_decode($img);
							
						}
						
						if ($format == 'png') {
							$img = $this->request->post ['client_url'];
							$img = str_replace('data:image/png;base64,', '', $img);
							$img = str_replace(' ', '+', $img);
							$Imgdata = base64_decode($img);
							
						}
						
						$outfolderdir = DIR_IMAGE.$notes_file;
						$success = file_put_contents($outfolderdir, $Imgdata);
						
					

						$s3file = $this->awsimageconfig->uploadFile ($notes_file, $outfolderdir, 47 );

						$data ['imageName_url'] = $s3file;
						
						//var_dump($s3file);
						unset($outfolderdir);
						
					}else{
					
				
							if ($this->request->files ["imageName_url"] != null && $this->request->files ["imageName_url"] != "") {
								$extension = end ( explode ( ".", $this->request->files ["imageName_url"] ["name"] ) );
								$notes_file = 'devbolb' . rand () . '.' . $extension;
								$outputFolder = $this->request->files ["imageName_url"] ["tmp_name"];
								
								if ($this->config->get ( 'enable_storage' ) == '1') {
									/* AWS */
									// require_once(DIR_SYSTEM . 'library/awsstorage/s3_config.php');
									$s3file = $this->awsimageconfig->uploadFile ( $notes_file, $outputFolder, $this->request->post ['facilities_id'] );
									$outputFolderUrl = $s3file;
								}
								
								if ($this->config->get ( 'enable_storage' ) == '2') {
									/* AZURE */
									require_once (DIR_SYSTEM . 'library/azure_storage/config.php');
									// uploadBlobSample($blobClient, $outputFolder, $notes_file);
									$s3file = AZURE_URL . $notes_file;
									$outputFolderUrl = $s3file;
								}
								
								if ($this->config->get ( 'enable_storage' ) == '3') {
									/* LOCAL */
									$outputFolder = DIR_IMAGE . 'storage/' . $notes_file;
									move_uploaded_file ( $this->request->files ["file"] ["tmp_name"], $outputFolder );
									$s3file = HTTPS_SERVER . 'image/storage/' . $notes_file;
									$outputFolderUrl = $s3file;
								}
								
								$data ['imageName_url'] = $s3file;
								
							}
					}
							
			
				$this->load->model('setting/tags');
				$archive_tags_id = $this->model_setting_tags->editTags($this->request->post['tags_id'], $data, $this->request->post['facilities_id']);
				
				
				if($this->request->post['facilities_id']){
				$this->load->model('facilities/facilities');
					$facilities_info = $this->model_facilities_facilities->getfacilities($this->request->post['facilities_id']);
					
					$this->load->model('setting/timezone');
						
					$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
					$facilitytimezone = $timezone_info['timezone_value'];
				}
				
				$data2 = array();
				$data2['tags_id'] = $this->request->post['tags_id'];
				$data2['notes_id'] = $this->request->post['notes_id'];
				$data2['archive_tags_id'] = $archive_tags_id;
				$data2['facilities_id'] = $this->request->post['facilities_id'];
				$data2['facilitytimezone'] = $facilitytimezone;
				
				$data2['phone_device_id'] = $this->request->post['phone_device_id'];
				
				$data2['tags_status_in_change'] = $this->request->post['tags_status_in_change'];
						
				if($this->request->post['is_android'] != null && $this->request->post['is_android'] != ""){
					$data2['is_android'] = $this->request->post['is_android'];
				}else{
					$data2['is_android'] = '1';
				}
				
				$data2['tag_classification_id'] = $this->request->post ['tag_classification_id'];;
				$data2['tag_status_id'] = $this->request->post['tag_status_id'];
				
				$notes_id = $this->model_setting_tags->updateclientsign($this->request->post, $data2);
			
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
				'data' => 'Error in appservices jsonClientintake '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('app_jsonClientintake', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		} 	
	
	}

	
	public function jsoninfo(){
						
		try{
		
		$this->load->model ( 'activity/activity' );
		$this->model_activity_activity->addActivitySave ( 'jsoninfo', $this->request->post, 'request' );
			
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
			
			//var_dump($tag);
			if(!empty($tag)){
				
				$get_img = $this->model_setting_tags->getImage($tag['tags_id']);			
				
				
                if ($get_img['upload_file_thumb'] != null && $get_img['upload_file_thumb'] != "") {
                    $upload_file_thumb_1 = $get_img['upload_file_thumb'];
                } else {
                    $upload_file_thumb_1 = $get_img['enroll_image'];
                }
				
				$this->load->model('resident/resident');
				
				if($tag['role_call'] !=NULL && $tag['role_call'] !=""){
					$client_statuses_value = $this->model_resident_resident->getClientStatusById ($tag['role_call']); 
				}
				 
				
			
				if($tag['classification_id'] !=NULL && $tag['classification_id'] !=""){
					$classification_value = $this->model_resident_resident->getClassificationValue ($tag['classification_id']); 
				}
				
				//var_dump($client_statuses_value);
				//var_dump($classification_value);
				
				$this->data['facilitiess'][] = array(
					'name' => $tag['emp_first_name'].' '.$tag['emp_last_name'],
					'tags_id' => $tag['tags_id'],
					'facilities_id' => $tag['facilities_id'],
					'emp_first_name' => $tag['emp_first_name'],
					'emp_last_name' => $tag['emp_last_name'],
					'emp_middle_name' => $tag['emp_middle_name'],
					'room_id' => $tag['room'],
					'ssn' => $tag['ssn'],
					'emp_tag_id' => $tag['emp_tag_id'],
					'emp_extid' => $tag['emp_extid'],
					'ccn' => $tag['ccn'],
					'date_added' => date ( 'm-d-Y', strtotime ($tag['date_added'])),
					'gender' => $tag['gender'],
					'customlistvalues_id' => $tag['customlistvalues_id'],
					'dob' => date ( 'm-d-Y', strtotime ($tag['dob'])),
					'tag_status_id' => $tag['role_call'],
					'tag_classification_id' => $tag['classification_id'],
					'upload_file_thumb' => $upload_file_thumb_1,
					'upload_file' => $upload_file_thumb_1,
					
					'client_status_name' => $client_statuses_value['name'],
					'client_status_image' => $client_statuses_value['image'],
					
					'classification_name'=>$classification_value['classification_name'],
					'color_code'=> $classification_value['color_code'],
					
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