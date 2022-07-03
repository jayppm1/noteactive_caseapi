<?php 
 header('Access-Control-Allow-Origin:'.$_SERVER['HTTP_ORIGIN']);
 header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
 header('Access-Control-Max-Age: 1000');
 header('Access-Control-Allow-Headers: Content-Type');
 header('Content-type: application/json');
 header('Content-Type: text/html; charset=utf-8');
 header("Content-type: bitmap; charset=utf-8");
class Controllerservices2clientlist extends Controller { 
	
	
	public function index(){
		
		
		try{
			
		$this->data['facilitiess'] = array();
		$this->load->model('facilities/facilities');
		
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
		
		$data2 = array();
		$data2['facilities'] = $this->request->post['facilities'];  
		
		
			$task_form_id = $this->request->post['task_form_id'];
			
			
			$bedcheck_occupancy = $this->request->post['bedcheck_occupancy'];
			
			$this->load->model('setting/bedchecktaskform');
			$this->load->model('setting/tags');
			
			$taskformlocs = $this->model_setting_bedchecktaskform->getruleModule($task_form_id);
			
			foreach($taskformlocs['bctf_module'] as $result){
				
				$locations_ids = $this->model_setting_tags->gettotalcountbyroom($result['locations_id']);
				
				
				if( $locations_ids >= "1" ){
					$no_clients = "1";
				}else{
					$no_clients = "0";
				}
				
				$this->data['facilitiess'][] = array(
					'task_form_location_id'             => $result['task_form_location_id'],
					'task_form_id'      => $result['task_form_id'],
					'location_name'       => $result['location_name'],
					'locations_id'       => $result['locations_id'],
					'location_detail' => $result['location_detail'],
					'current_occupency'     => $result['current_occupency'],
					'sort_order'              => $result['sort_order'],
					'locations_ids'              => $locations_ids,
					'no_clients'              => $no_clients,
				);
				
			}
			
			$value = array('results'=>$this->data['facilitiess'],'status'=>true);
			/*echo json_encode($value);*/
			$this->response->setOutput(json_encode($value));
			
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices jsonClientlist '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('app_jsonClientlist', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		} 	
	
	}
	
	
	public function location(){
		
		try{
	    
		$this->data['facilitiess'] = array();
		$this->load->model('facilities/facilities');
		/*
		$this->load->model('api/encrypt');
		
		$cre_array = array();
		$cre_array['phone_device_id'] = $this->request->post['phone_device_id'];
		$cre_array['facilities_id'] = $this->request->post['facilities_id'];
		
		$api_device_info = $this->model_api_encrypt->getdevicedetails($cre_array);
		
	 
		if($api_device_info == false){
			//$errorMessage = $this->model_api_encrypt->errorMessage();
			//return $errorMessage;
		}
		
		$api_header_value = $this->model_api_encrypt->getallheaders1();
		
		if($api_header_value == false){
			//$errorMessage = $this->model_api_encrypt->errorMessage();
			//return $errorMessage;
		}
		
		*/
		
		if($this->config->get ( 'all_sync_pagination' ) != null && $this->config->get ( 'all_sync_pagination' ) != ""){
			$config_admin_limit = $this->config->get ( 'all_sync_pagination' );
		}else{
			$config_admin_limit = "50";
		}
		
		if (isset($this->request->post['page'])) {
			$page = $this->request->post['page'];
		} else {
			$page = 1;
		}
		
		$this->load->model('facilities/facilities');
			
		$facilities_info = $this->model_facilities_facilities->getfacilities($this->request->post['facilities_id']);
				
		$this->load->model('setting/timezone');
				
		$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
		$facilitytimezone = $timezone_info['timezone_value'];
		
		$current_date_user =  date('Y-m-d');
		
		$data2 = array();
		$data2['facilities_id'] = $this->request->post['facilities_id'];  
		$data2['type'] = 'bedcheck';  
		$data2['start'] = ($page - 1) * $config_admin_limit;  
		$data2['limit'] = $config_admin_limit;
		$data2['app_user_date'] = $this->request->post['date_added'];
		$data2['current_date_user'] = $current_date_user;
		$data2['is_master'] = 1;
		$data2['is_submaster'] = 1;
		
		
		$this->load->model('setting/locations');
		
			$all_total = $this->model_setting_locations->getTotallocations($data2);
			
			$locations = $this->model_setting_locations->getlocations($data2);
			
			//var_dump($locations);
			
				foreach($locations as $result){
				
				$this->data['facilitiess'][] = array(
					'locations_id'             => $result['locations_id'],
					'location_name'      => $result['location_name'],
					'location_address'       => $result['location_address'],
					'location_detail'       => $result['location_detail'],
					'capacity' => $result['capacity'],
					'location_type'     => $result['location_type'],
					'nfc_location_tag_required'              => $result['nfc_location_tag_required'],
					'gps_location_tag'              => $result['gps_location_tag'],
					'gps_location_tag_required'              => $result['gps_location_tag_required'],
					'latitude'              => $result['latitude'],
					'longitude'              => $result['longitude'],
					'other_location_tag'              => $result['other_location_tag'],
					'other_location_tag_required'              => $result['other_location_tag_required'],
					'other_type_id'              => $result['other_type_id'],
					'facilities_id'              => $result['facilities_id'],
					'upload_file'              => $result['upload_file'],
					'customlistvalues_id'              => $result['customlistvalues_id'],
					
				);
				
			}
			
			$value = array('results'=>$this->data['facilitiess'],'all_total'=>$all_total,'status'=>true);
			/*echo json_encode($value);*/
			$this->response->setOutput(json_encode($value));
			
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices clientlocation '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('app_clientlocation', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		} 
		
	   
    }
}