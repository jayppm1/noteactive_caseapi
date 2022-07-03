<?php 
header('Access-Control-Allow-Origin:'.$_SERVER['HTTP_ORIGIN']);
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-type: application/json');
header('Content-Type: text/html; charset=utf-8');

class Controllerserviceswearservice extends Controller {

	public function jsonFacilities(){
		try{
			$this->data['facilitiess'] = array();
			$this->load->model('facilities/facilities');
			$data = array (
					'facilities_id' => $this->request->post['facilities_id']
			);
			$results = $this->model_facilities_facilities->getfacilitiess($data);
			
			foreach ($results as $result) {
						
				$this->data['facilitiess'][] = array(
					'facilities_id'    => $result['facilities_id'],
					'facility'   => $result['facility'],
					'firstname'   => $result['firstname'],
					'lastname'   => $result['lastname'],
					'email'   => $result['email'],
				);
			}
			
			$value = array('results'=>$this->data['facilitiess'],'status'=>true);
			/*echo json_encode($value);*/
			$this->response->setOutput(json_encode($value));
			
		}catch(Exception $e){
				$this->load->model('activity/activity');
				$activity_data2 = array(
					'data' => 'Error in wearservice jsonFacilities',
				);
				$this->model_activity_activity->addActivity('wear_jsonFacilities', $activity_data2);
			//echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
		
		}
		
	public function jsonAddNotes(){
		
		try{
			$this->load->model('activity/activity');
			$this->model_activity_activity->addActivitySave('wearservicejsonAddNotes', $this->request->post, 'request');
		$this->data['facilitiess'] = array();
		
		$json = array();
		
		$this->load->model('notes/notes');
		$this->language->load('notes/notes');
		
		
		if (!$this->request->post['notes_description']) {
			$json['warning'] = 'Please insert required!.';
			$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
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
		
			
		
		if($json['warning'] == null && $json['warning'] == ""){
			$data = array();
			
			if($this->request->post['signature'] != null && $this->request->post['signature'] != ""){
				$data['imgOutput'] = $this->request->post['signature'];
			}
			
			$data['highlighter_id'] = $this->request->post['highlighter_id'];
			$data['highlighter_value'] = $this->request->post['highlighter_value'];
			$data['notes_description'] = $this->request->post['notes_description'];
			$data['notes_pin'] = $this->request->post['notes_pin'];
			$data['user_id'] = $this->request->post['user_id'];
			
			$data['notetime'] = $this->request->post['notetime'];
			$data['text_color'] = $this->request->post['text_color'];
			$data['note_date'] = $this->request->post['note_date'];

			$data['notes_file'] = $this->request->post['notes_file'];
			$data['facilitytimezone'] = $this->request->post['facilitytimezone'];
			
			$data['keyword_file'] = $this->request->post['keyword_file'];
			
			
			$data['date_added'] = $this->request->post['date_added'];
			
			
			
			$this->model_notes_notes->jsonaddnotes($data, $this->request->post['facilities_id']);
		
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
					'data' => 'Error in wearservice jsonAddNotes',
				);
				$this->model_activity_activity->addActivity('wear_jsonAddNotes', $activity_data2);
			//echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}
		
	public function jsonSavetask(){
		
		try{
			
			$this->load->model('activity/activity');
			$this->model_activity_activity->addActivitySave('wearservicejsonSavetask', $this->request->post, 'request');
			
			$this->data['facilitiess'] = array();
			$this->load->model('createtask/createtask');
			$json = array();	
			
			if (!$this->request->post['task_id']) {
				$json['warning'] = 'Please select id!.';
				$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
			}
			
			
			if ($this->request->post['user_id'] == '') {
				$json['warning'] = 'Please select user id!.';
				$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
			}
			
			/*if ($this->request->post['select_one'] == '') {
				$json['warning'] = 'Please Select One';
			}*/
			
			if ($this->request->post['user_id'] != '') {
				$this->load->model('user/user');
				$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

				if(empty($user_info)){
					$json['warning'] = 'incorrect username';
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
			
			
			if ($this->request->post['task_id'] != '') {
			
				$this->load->model('createtask/createtask');
				$result = $this->model_createtask_createtask->getStrikedatadetails($this->request->post['task_id']);
				$task_date = date('m-d-Y', strtotime($result['task_date']));
				
				date_default_timezone_set($this->request->post['facilitytimezone']);
				
				$current_date = date('m-d-Y', strtotime('now'));
				
				if($task_date > $current_date){
					$json['warning'] = "Task cannot be completed before designated time";
				}
				
				if(empty($result)){
					$json['warning'] = "You connot update task because task not exit";
				}	
			}
			
		
		if($json['warning'] == null && $json['warning'] == ""){
			$data = array();
			
				$data['notes_pin'] = $this->request->post['notes_pin'];
				$data['user_id'] = $this->request->post['user_id'];
				
				$data['comments'] = $this->request->post['comments'];
				
				if($this->request->post['signature'] != null && $this->request->post['signature'] != ""){
					$data['imgOutput'] = $this->request->post['signature'];
				}
				$data['facilitytimezone'] = $this->request->post['facilitytimezone'];
				
				$this->load->model('createtask/createtask');
				$result = $this->model_createtask_createtask->getStrikedatadetails($this->request->post['task_id']);
				
				
			    $this->model_createtask_createtask->inserttask($result, $data, $this->request->post['facilities_id']);
				$this->model_createtask_createtask->updatetaskNote($this->request->post['task_id']);
				$this->model_createtask_createtask->deteteIncomTask($this->request->post['facilities_id']);
				
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
					'data' => 'Error in wearservice jsonSavetask',
				);
				$this->model_activity_activity->addActivity('wear_jsonSavetask', $activity_data2);
			//echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
		
		}
		
		
		public function jsonUpdateStriketask(){
		
		try{
			
			$this->load->model('activity/activity');
			$this->model_activity_activity->addActivitySave('wearservicejsonUpdateStriketask', $this->request->post, 'request');
			
		$this->data['facilitiess'] = array();
		$this->load->model('createtask/createtask');
		$json = array();
		
		if (!$this->request->post['task_id']) {
			$json['warning'] = 'Please select id!.';
		}
		
		
		if ($this->request->post['user_id'] == '') {
			$json['warning'] = 'Please select user id!.';
		}
		
		/*if ($this->request->post['select_one'] == '') {
			$json['warning'] = 'Please Select One';
		}*/
		
		
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
		
		if ($this->request->post['user_id'] != '') {
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if(empty($user_info)){
				$json['warning'] = 'incorrect username';
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
		
		
		if ($this->request->post['task_id'] != '') {
			
			$this->load->model('createtask/createtask');
			$result = $this->model_createtask_createtask->getStrikedatadetails($this->request->post['task_id']);
			$task_date = date('m-d-Y', strtotime($result['task_date']));
			
			date_default_timezone_set($this->request->post['facilitytimezone']);
			
			$current_date = date('m-d-Y', strtotime('now'));
			
			if($task_date > $current_date){
				$json['warning'] = "Task cannot be completed before designated time";
			}
			
			if(empty($result)){
				$json['warning'] = "You connot update task because task not exit";
			}
		}
		
		
		
		if($json['warning'] == null && $json['warning'] == ""){
			$data = array();
			
			if($this->request->post['signature'] != null && $this->request->post['signature'] != ""){
				$data['imgOutput'] = $this->request->post['signature'];
			}
			
			$data['notes_pin'] = $this->request->post['notes_pin'];
			$data['user_id'] = $this->request->post['user_id'];
			
			$data['comments'] = $this->request->post['comments'];
			$data['facilitytimezone'] = $this->request->post['facilitytimezone'];
			
			
			
			if($this->request->post['task_id'] !=Null && $this->request->post['task_id']!=""){
				$this->model_createtask_createtask->updatetaskStrike($this->request->post['task_id']);
				$result = $this->model_createtask_createtask->getStrikedatadetails($this->request->post['task_id']);
				$this->model_createtask_createtask->insertDatadetails($result, $data, $this->request->post['facilities_id']);
				$this->model_createtask_createtask->deteteIncomTask($this->request->post['facilities_id']);
			}
			
			$error = true;
			
			$this->data['facilitiess'][] = array(
				'warning'  => '1',
			);
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
					'data' => 'Error in wearservice jsonUpdateStriketask',
				);
				$this->model_activity_activity->addActivity('wear_jsonUpdateStriketask', $activity_data2);
			//echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	
	}

	public function jsonFacilityByUserLogin(){
		
		try{
			
			$this->load->model('activity/activity');
			$this->model_activity_activity->addActivitySave('wearservicejsonFacilityByUserLogink', $this->request->post, 'request');
			
		$this->data['facilitiess'] = array();
		
		/*$this->request->post['facility'] = 'test';
		$this->request->post['password'] = '123456';
		$this->request->post['ipaddress'] = '125';
		$this->request->post['http_host'] = 'servitium.com';
		$this->request->post['http_referer'] = 'servitium.com';
		*/
		
		$json = array();
		
		$this->load->model('user/user'); 
		if(!$this->model_user_user->getUsersByPin($this->request->post['username'],$this->request->post['user_pin'])){
			$json['warning'] = 'Password does not match.';
		}
			
		if($json['warning'] == null && $json['warning'] == ""){
			
			$this->data['facilitiess'][] = array(
				'username'  => $this->request->post['username'],
				'user_pin'  => $this->request->post['user_pin'],
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
					'data' => 'Error in appservices jsonFacilityByUser',
				);
				$this->model_activity_activity->addActivity('app_jsonFacilityByUser', $activity_data2);
			
			//echo 'Caught exception: ',  $e->getMessage(), "\n";
			
			}		
	}
	
}


