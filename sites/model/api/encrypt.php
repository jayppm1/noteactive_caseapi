<?php
class Modelapiencrypt extends Model {
	
	public function encrypt($edata) {
		
		$eresult = $this->encryption->encrypt($edata);
		
		return $eresult;
	}
	
	public function decrypt($edata) {
		$eresult = $this->encryption->decrypt($edata);
		
		return $eresult;
	}
	
	/*
	public function encrypt1($edata) {
		$key = noteactive_device;
		$eresult = $this->encryption->encrypt1($edata, $key);
		
		return $eresult;
	}
	
	public function decrypt1($edata) {
		$key = noteactive_device;
		$eresult = $this->encryption->decrypt1($edata, $key);
		
		return $eresult;
	}
	*/
	
	
	 
	public function getallheaders1(){
		$apiheader = array();
		foreach (getallheaders() as $name => $value) {
			
			if($name == 'device_username'){
				//$apiheader[$name] = $value;
				
				if($value == "" && $value == null){
					return false;
				}
				
				$de_device_username = $this->decrypt($value);
				
				if($de_device_username != $this->config->get('device_username')){
					return false;
				}
			}
			
			if($name == 'device_token'){
				//$apiheader[$name] = $value;
				if($value == "" && $value == null){
					return false;
				}
				$de_device_token = $this->decrypt($value);
				
				if($de_device_token != $this->config->get('device_token')){
					return false;
				}
			}
		}
	   return true; 
	} 
	
	public function getdevicedetails($pdata){
		
		if($pdata['phone_device_id'] == "" && $pdata['phone_device_id'] == null){
			return false;
		}
		
		if($pdata['facilities1'] != '1'){
			if($pdata['facilities_id'] == "" && $pdata['facilities_id'] == null){
				return false;
			}
		}
				
		if($pdata['phone_device_id'] != '' && $pdata['phone_device_id'] != null){
			$this->load->model('notes/device');
			$device_info = $this->model_notes_device->getdevice($pdata['phone_device_id']);
			
			if($device_info['device_details_id'] == null && $device_info['device_details_id'] == ""){
				return false;
			}
		}
		
		if($pdata['facilities_id'] != '' && $pdata['facilities_id'] != null){
			$this->load->model('facilities/facilities');
			$facilities_info = $this->model_facilities_facilities->getfacilities($pdata['facilities_id']);
			
			if($facilities_info['facilities_id'] == null && $facilities_info['facilities_id'] == ""){
				return false;
			}
		}
		
		return true; 
	}
	
	public function errorMessage(){
		
		$facilitiess = array();
		
		$facilitiess[] = array(
			'warning'  => "Unauthorized error",
		);
		$value = array('results'=>$facilitiess,'status'=>false);
		
		return $this->response->setOutput(json_encode($value));
	}
	
}