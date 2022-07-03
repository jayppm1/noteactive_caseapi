<?php
class Modelnotesdevice extends Model {

	public function jsonadddevice($data) {
		
		$device_info = $this->getdevice($data['device_id']);
		
		if($device_info['device_id'] != null && $device_info['device_id'] != ""){
			$this->db->query("UPDATE `" . DB_PREFIX . "device_details` SET model = '".$this->db->escape($data['model'])."',version = '" . $this->db->escape($data['version']) . "',manufacture = '" . $this->db->escape($data['manufacture']) . "',imei = '" . $this->db->escape($data['imei']) . "',device_type = '" . $this->db->escape($data['device_type']) . "',registration_id = '" . $this->db->escape($data['registration_id']) . "',application_version = '" . $this->db->escape($data['application_version']) . "'
			
			,company_name = '" . $this->db->escape($data['company_name']) . "'
			,activitation_key = '" . $this->db->escape($data['activitation_key']) . "'
			,customer_id = '" . $this->db->escape($data['customer_id']) . "'
			,asset_id = '" . $this->db->escape($data['asset_id']) . "'
			,facilities_id = '" . $this->db->escape($data['facilities_id']) . "'
			,version_url = '" . $this->db->escape(HTTP_SERVER) . "'
			, date_updated = NOW() where device_id = '".$this->db->escape($data['device_id'])."' and device_details_id = '".$this->db->escape($device_info['device_details_id'])."' ");
			
			$device_details_id = $device_info['device_details_id'];
			
		}else{
			$sql = "INSERT INTO `" . DB_PREFIX . "device_details` SET device_id = '" . $this->db->escape($data['device_id']) . "',model = '".$this->db->escape($data['model'])."',version = '" . $this->db->escape($data['version']) . "',manufacture = '" . $this->db->escape($data['manufacture']) . "',imei = '" . $this->db->escape($data['imei']) . "',device_type = '" . $this->db->escape($data['device_type']) . "',registration_id = '" . $this->db->escape($data['registration_id']) . "',application_version = '" . $this->db->escape($data['application_version']) . "'
			
			,company_name = '" . $this->db->escape($data['company_name']) . "'
			,activitation_key = '" . $this->db->escape($data['activitation_key']) . "'
			,customer_id = '" . $this->db->escape($data['customer_id']) . "'
			,asset_id = '" . $this->db->escape($data['asset_id']) . "'
			,facilities_id = '" . $this->db->escape($data['facilities_id']) . "'
			,version_url = '" . $this->db->escape(HTTP_SERVER) . "'
			
			,date_added = NOW(),status='1' ";
		
			$this->db->query($sql);
			$device_details_id = $this->db->getLastId();
		}
		
		if($data['facilities_id']> 0){
			$this->load->model('facilities/facilities');
			$facility = $this->model_facilities_facilities->getfacilities($data['facilities_id']);
			$unique_id = $facility['customer_key'];
			
			$this->load->model('customer/customer');
			$customer_info = $this->model_customer_customer->getcustomerid($unique_id);
			
			$this->db->query("UPDATE `" . DB_PREFIX . "device_details` SET customer_key = '".$customer_info['activecustomer_id']."' where device_details_id = '".$device_details_id."' ");
		}
		
		$this->load->model('activity/activity');
		$adata['device_id'] = $data['device_id'];
		$adata['model'] = $data['model'];
		$adata['version'] = $data['version'];
		$adata['manufacture'] = $data['manufacture'];
		$adata['imei'] = $data['imei'];
		$adata['device_type'] = $data['device_type'];		
		$adata['registration_id'] = $data['registration_id'];
		$adata['application_version'] = $data['application_version'];
		$adata['company_name'] = $data['company_name'];
		$adata['activitation_key'] = $data['activitation_key'];
		$adata['customer_id'] = $data['customer_id'];
		$adata['asset_id'] = $data['asset_id'];
		$adata['version_url'] = $data['version_url'];
		$adata['facilities_id'] = $data['facilities_id'];
		$adata['date_added'] = date('Y-m-d H:i:s');
		$this->model_activity_activity->addActivitySave('jsonadddevice', $adata, 'query');
		
	}
	
	
	public function jsondeactivatedevice($data) {
		
		$query1 = $this->db->query("SELECT device_id,device_details_id FROM `" . DB_PREFIX . "device_details` WHERE device_id = '".$this->db->escape($data['device_id'])."' and status='1' and is_deletd='0' ");
		
		if($query1->num_rows > 0){
			$this->db->query("UPDATE `" . DB_PREFIX . "device_details` SET status='0' where device_id = '".$this->db->escape($data['device_id'])."' and device_details_id = '".$this->db->escape($query1->row['device_details_id'])."' ");
			
		}
	}
	
	
	public function getdevice($device_id) {
		
		$query = $this->db->query("SELECT device_id,device_details_id FROM `" . DB_PREFIX . "device_details` WHERE device_id = '".$this->db->escape($device_id)."' and status='1' and is_deletd='0' ");
		
		return $query->row;
	}
	
	public function getdevicesby($device_id) {
		if($device_id != null && $device_id != null){
			$query = $this->db->query("SELECT device_id,device_details_id,registration_id FROM `" . DB_PREFIX . "device_details` WHERE device_details_id  IN (".$this->db->escape($device_id).") and status='1' and is_deletd='0' ");
		
			return $query->row;
		}
	}
	
	
	public function registerwebdevice($data){
		$device_info = $this->getdeviceweb($data['session_id'],$data['activationKey'], $data['facilities_id']);
		
		
		if($device_info['web_token_id'] != null && $device_info['web_token_id'] != ""){
			
			$sql = "UPDATE `" . DB_PREFIX . "web_token` SET facilities_id = '".$this->db->escape($data['facilities_id'])."',token = '" . $this->db->escape($data['token']) . "',browser = '" . $this->db->escape($data['browser']) . "',session_id = '" . $this->db->escape($data['session_id']) . "',activationKey = '" . $this->db->escape($data['activationKey']) . "',ip = '" . $this->db->escape($data['ip']) . "',date_updated = NOW(),type='1' where web_token_id = '".$this->db->escape($device_info['web_token_id'])."' ";
			
			$this->db->query($sql);
			
		}else{
			$sql = "INSERT INTO `" . DB_PREFIX . "web_token` SET facilities_id = '" . $this->db->escape($data['facilities_id']) . "',token = '" . $this->db->escape($data['token']) . "',browser = '" . $this->db->escape($data['browser']) . "',session_id = '" . $this->db->escape($data['session_id']) . "',activationKey = '" . $this->db->escape($data['activationKey']) . "',ip = '" . $this->db->escape($data['ip']) . "',date_added = NOW(),type='1',status='1' ";
		
			$this->db->query($sql);
		}
		
		$this->load->model('activity/activity');
		$adata['facilities_id'] = $data['facilities_id'];
		$adata['token'] = $data['token'];
		$adata['browser'] = $data['browser'];
		$adata['session_id'] = $data['session_id'];
		$adata['activationKey'] = $data['activationKey'];
		$adata['ip'] = $data['ip'];		
		$adata['date_added'] = date('Y-m-d H:i:s');
		$this->model_activity_activity->addActivitySave('registerwebdevice', $adata, 'query');
	}
	
	public function getdeviceweb($session_id, $activationKey, $facilities_id) {
		
		$query = $this->db->query("SELECT web_token_id,token FROM `" . DB_PREFIX . "web_token` WHERE session_id = '".$this->db->escape($session_id)."' and activationKey = '".$this->db->escape($activationKey)."'  and facilities_id = '".$this->db->escape($facilities_id)."' and status='1' ");
		
		return $query->row;
	}
	
}
?>