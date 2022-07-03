jsonuserLogin<?php 
 header('Access-Control-Allow-Origin:'.$_SERVER['HTTP_ORIGIN']);
 header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
 header('Access-Control-Max-Age: 1000');
 header('Access-Control-Allow-Headers: Content-Type');
 header('Content-type: application/json');
 header('Content-Type: text/html; charset=utf-8');
 header("Content-type: bitmap; charset=utf-8");
 
class Controllerservices2device extends Controller { 
	private $error = array();
	
	public function index(){
		
		
		try{
			
			$this->load->model('activity/activity');
		$this->model_activity_activity->addActivitySave('deviceindex', $this->request->post, 'request');
			
		$this->data['facilitiess'] = array();
		
		/*$this->load->model('api/encrypt');
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
		}*/
		
		$json = array();
		
		$this->load->model('notes/device');
		
		
		if($json['warning'] == null && $json['warning'] == ""){
			
			$this->model_notes_device->jsonadddevice($this->request->post);
		
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
				'data' => 'Error in deviceindex '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('app_deviceindex', $activity_data2);
		
		
		} 
	}
	
	
	public function deactivatedevice(){
		
		try{
			
			$this->load->model('activity/activity');
		$this->model_activity_activity->addActivitySave('deactivatedevice', $this->request->post, 'request');
			
		$this->data['facilitiess'] = array();
		
		$json = array();
		
		$this->load->model('notes/device');
		
		
		$device_info = $this->model_notes_device->getdevice($this->request->post['device_id']);
			
		if (empty($device_info)) {
			$json['warning'] = 'Device does not exit.';
		}
		
		if($json['warning'] == null && $json['warning'] == ""){
			
			$this->model_notes_device->jsondeactivatedevice($this->request->post);
		
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
				'data' => 'Error in deactivatedevice',
			);
			$this->model_activity_activity->addActivity('app_deactivatedevice', $activity_data2);
		
		
		} 
	}
	
	
	
}