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
		$this->data['facilitiess'] = array();
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
		
		$this->load->model('notes/notes');
		$this->language->load('notes/notes');
			
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
			if($this->request->post['notes_id'] == null && $this->request->post['notes_id'] == ""){
				$json['warning'] = 'Please enter Notes ID!';
			}
			
			if($this->request->post['tags_id'] == null && $this->request->post['tags_id'] == ""){
				$json['warning'] = 'Please select a tag!';
			}
		
		if($json['warning'] == null && $json['warning'] == ""){
			
			$this->load->model('notes/notes');
		  
				$timezone_name = $this->request->post['facilitytimezone'];
				$timeZone = date_default_timezone_set($timezone_name);
				$data = array( );
				$data['notes_pin'] = $this->request->post['notes_pin'];
				$data['imgOutput'] = $this->request->post['signature'];
				$data['tags_id'] = $this->request->post['tags_id'];
				$data['user_id'] = $this->request->post['user_id'];
				$data['emp_tag_id'] = $this->request->post['emp_tag_id'];
				$data['facilities_id'] = $this->request->post['facilities_id'];
				$data['notes_type'] = $this->request->post['notes_type'];
				
				$this->model_notes_notes->updatenotesTags($data, $this->request->post['notes_id'], $timezone_name);
			
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
					'data' => 'Error in Update Client List',
				);
				$this->model_activity_activity->addActivity('updateclient_jsonClientUpdate', $activity_data2);
		}
	
	}
	
}