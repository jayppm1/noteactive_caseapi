<?php 
 header('Access-Control-Allow-Origin:'.$_SERVER['HTTP_ORIGIN']);
 header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
 header('Access-Control-Max-Age: 1000');
 header('Access-Control-Allow-Headers: Content-Type');
 header('Content-type: application/json');
 header('Content-Type: text/html; charset=utf-8');
 header("Content-type: bitmap; charset=utf-8");
class Controllerservices2updatetime extends Controller { 
	
	public function index(){
		try{
			
		$this->load->model ( 'activity/activity' );
		$this->model_activity_activity->addActivitySave ( 'updatetime', $this->request->post, 'request' );
			
		$this->data['facilitiess'] = array();
		$this->load->model('facilities/facilities');
		$this->load->model('notes/updatetime');
		
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

		if ($this->request->post ['notes_id'] == null && $this->request->post ['notes_id'] == "") {
			$json ['warning'] = 'Warning: Enter Note ID';
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
		if ($this->request->post ['notetime'] == null && $this->request->post ['notetime'] == "") {
			$json ['warning'] = 'Warning: Enter Note Time';
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

				if($this->request->post['facilities_id']){
					$this->load->model('facilities/facilities');
					$facilities_info = $this->model_facilities_facilities->getfacilities($this->request->post['facilities_id']);
					
					$this->load->model('setting/timezone');
						
					$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
					$facilitytimezone = $timezone_info['timezone_value'];
				}
				
				$data2 = array();
				$data2['facilities_id'] = $this->request->post['facilities_id'];
				$data2['notetime'] = $this->request->post['notetime'];
				$data2['notes_id'] = $this->request->post['notes_id'];
				$data2['facilitytimezone'] = $facilitytimezone;
				
				$data2['phone_device_id'] = $this->request->post['phone_device_id'];
						
				if($this->request->post['is_android'] != null && $this->request->post['is_android'] != ""){
					$data2['is_android'] = $this->request->post['is_android'];
				}else{
					$data2['is_android'] = '1';
				}
				
				$time_id = $this->model_notes_updatetime->updatetime($this->request->post, $data2);
				
				$this->load->model('api/facerekognition');
				$fre_array2 = array();
				$fre_array2['face_notes_file'] = $this->request->post['face_notes_file'];
				$fre_array2['outputFolder'] = $this->request->post['outputFolder'];
				$fre_array2['face_not_verify'] = $this->request->post['face_not_verify'];
				$fre_array2['facilities_id'] = $facilities_id;
				$fre_array2['notes_file'] = $facerekognition_response['imagedata']['notes_file'];
				$fre_array2['outputFolder_1'] = $facerekognition_response['imagedata']['outputFolder'];
				$fre_array2['notes_id'] = $this->request->post['notes_id'];
				$fre_array2['time_id'] = $time_id;
				$this->model_api_facerekognition->savefacerekognitionnotestime($fre_array2);
				
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
				'data' => 'Error in appservices updatetime '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('app_updatetime', $activity_data2);
		
		
		} 	
	
	}
	
	public function allnotetime(){
		try{
			
		$this->load->model ( 'activity/activity' );
		$this->model_activity_activity->addActivitySave ( 'allnotetime', $this->request->post, 'request' );
			
		$this->data['facilitiess'] = array();
		$this->load->model('facilities/facilities');
		$this->load->model('notes/updatetime');
		$this->language->load ( 'notes/notes' );
		
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

		if ($this->request->post ['notes_id'] == null && $this->request->post ['notes_id'] == "") {
			$json ['warning'] = 'Warning: Enter Note ID';
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
		
		$original_notetime = "";
		if ($json ['warning'] == null && $json ['warning'] == "") {

				$this->load->model ( 'notes/notes' );
				$uptimes = $this->model_notes_notes->getupdatetimes ($this->request->post ['notes_id']);
				
				$uptime_original = $this->model_notes_notes->getupdatetimesa ($this->request->post ['notes_id']);
				
				$original_notetime = "";
				//if(!empty($uptime_original)){
				$original_notetime = date ('h:i A', strtotime($uptime_original['original_notetime']));
					
				//}
				
				$this->data ['facilitiess'] = array ();
				foreach ( $uptimes as $uptime ) {
					
					$this->data ['facilitiess'] [] = array (
							'time_id' => $uptime ['time_id'],
							'notetime' => date ( 'h:i A', strtotime ( $uptime ['notetime'] ) ),
							'user_id' => $uptime ['user_id'],
							'notes_pin' => $uptime ['notes_pin'],
							'user_file' => $uptime ['user_file'],
							'is_user_face' => $uptime ['is_user_face'],
							'signature' => $uptime ['signature'],
							'date_added' => date ( $this->language->get ( 'date_format_short_2' ), strtotime ( $uptime ['date_added'] ) ),
					);
					
				}
				
				$error = true;
				
			} else {
				$this->data ['facilitiess'] [] = array (
						'warning' => $json ['warning'] 
				);
				$error = false;
			}
			
			$value = array (
					'results' => $this->data ['facilitiess'],
					'original_notetime' => $original_notetime,
					'status' => $error 
			);
			$this->response->setOutput ( json_encode ( $value ) );
			
			
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in allnotetime '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('app_allnotetime', $activity_data2);
		
		
		} 	
	
	}
}