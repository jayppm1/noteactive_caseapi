<?php 
 header('Access-Control-Allow-Origin:'.$_SERVER['HTTP_ORIGIN']);
 header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
 header('Access-Control-Max-Age: 1000');
 header('Access-Control-Allow-Headers: Content-Type');
 header('Content-type: application/json');
 header('Content-Type: text/html; charset=utf-8');
 header("Content-type: bitmap; charset=utf-8");
 
class Controllerservicesupdateclientstatus extends Controller { 
	private $error = array();
	public function jsonClientUpdate(){
		try{
			
			$this->load->model('activity/activity');
		$this->model_activity_activity->addActivitySave('jsonClientUpdate', $this->request->post, 'request');
		
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
				$json['warning'] = 'User not exist!';
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
			if($this->request->post['notes_id'] == null && $this->request->post['notes_id'] == ""){
				$json['warning'] = 'Please enter Notes ID!';
				$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
			}
			
			/*if($this->request->post['tags_id'] == null && $this->request->post['tags_id'] == ""){
				$json['warning'] = 'Please select a tag!';
			}*/
			
		if(!empty($this->request->post['tags_id_list'])){
			$this->load->model('setting/tags');
			$tagsname = "";
			foreach($this->request->post['tags_id_list'] as $tagid){
				$stag_info = $this->model_setting_tags->getTagsbyNotesIDTagsrow($tagid, $this->request->post['notes_id']);
				
			
				if(!empty($stag_info)){
					$tagsname .= $stag_info['emp_tag_id'].' | ';
				}
			}
			

			if(!empty($tagsname)){
				$json['warning'] = $tagsname ." already added in this notes ";
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
		
		if($json['warning'] == null && $json['warning'] == ""){
			
			$this->load->model('notes/notes');
		  
				$timezone_name = $this->request->post['facilitytimezone'];
				$timeZone = date_default_timezone_set($timezone_name);
				$data = array( );
				$data['notes_pin'] = $this->request->post['notes_pin'];
				$data['imgOutput'] = $this->request->post['signature'];
				//$data['tags_id'] = $this->request->post['tags_id'];
				$data['user_id'] = $this->request->post['user_id'];
				//$data['emp_tag_id'] = $this->request->post['emp_tag_id'];
				$data['facilities_id'] = $this->request->post['facilities_id'];
				$data['notes_type'] = $this->request->post['notes_type'];
				
				$data['phone_device_id'] = $this->request->post['phone_device_id'];
				$data['device_unique_id'] = $this->request->post['device_unique_id'];
						
				if($this->request->post['is_android'] != null && $this->request->post['is_android'] != ""){
					$data['is_android'] = $this->request->post['is_android'];
				}else{
					$data['is_android'] = '1';
				}
				
				$this->load->model('setting/tags');
				foreach($this->request->post['tags_id_list'] as $tagid){
					$tag_info = $this->model_setting_tags->getTag($tagid);
					if(!empty($tag_info)){
						$data['tags_id'] = $tag_info['tags_id'];
						$data['emp_tag_id'] = $tag_info['emp_tag_id'];
						//var_dump($formData);
						//echo "<hr>";
						$notes_tags_id = $this->model_notes_notes->updatenotesTags($data, $this->request->post['notes_id'], $facilitytimezone);
						
						if($this->request->post['outputFolder'] != null && $this->request->post['outputFolder'] != null){
    						
    						$notes_file = $this->request->post['face_notes_file'];
    						$outputFolder = $this->request->post['outputFolder'];
    						
    						//require_once (DIR_SYSTEM . 'library/awsstorage/s3_config.php');
							$s3file = $this->awsimageconfig->uploadFile($notes_file, $outputFolder, $this->request->post['facilities_id']);
    						$this->load->model('notes/notes');
    						$this->model_notes_notes->updateuserpicturenotestag($s3file, $this->request->post['notes_id'], $notes_tags_id);
    						 
    						//$this->model_notes_notes->updateuserverifiednotestag('1', $this->request->post['notes_id'], $notes_tags_id);
    						
						}
						
					}
				}
				
				//$this->model_notes_notes->updatenotesTags($data, $this->request->post['notes_id'], $timezone_name);
			
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
					'data' => 'Error in Update Client List '.$e->getMessage(),
				);
				$this->model_activity_activity->addActivity('updateclient_jsonClientUpdate', $activity_data2);
		}
	
	}
	
}