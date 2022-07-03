<?php 
 header('Access-Control-Allow-Origin:'.$_SERVER['HTTP_ORIGIN']);
 header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
 header('Access-Control-Max-Age: 1000');
 header('Access-Control-Allow-Headers: Content-Type');
 header('Content-type: application/json');
 header('Content-Type: text/html; charset=utf-8');
 header("Content-type: bitmap; charset=utf-8");
class Controllerservicesclientlist extends Controller { 
	
	
	public function index(){
		
		
		try{
			
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
		$this->load->model('facilities/facilities');
		
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
				'data' => 'Error in appservices jsonClientlist',
			);
			$this->model_activity_activity->addActivity('app_jsonClientlist', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		} 	
	
	}
}