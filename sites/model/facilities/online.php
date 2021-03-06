<?php
class Modelfacilitiesonline extends Model {	
	public function whosonline($data = array()) {
		//$this->db->query("DELETE FROM `" . DB_PREFIX . "facility_online` WHERE (UNIX_TIMESTAMP(`date_added`) + 3600) < UNIX_TIMESTAMP(NOW()) and `facilities_id` = '" . (int)$facilities_id . "'");

		//$this->db->query("REPLACE INTO `" . DB_PREFIX . "facility_online` SET `ip` = '" . $this->db->escape($ip) . "', `facilities_id` = '" . (int)$facilities_id . "', `url` = '" . $this->db->escape($url) . "', `referer` = '" . $this->db->escape($referer) . "', `date_added` = NOW()");
		
		$this->load->model('facilities/facilities');
		$facilities_info = $this->model_facilities_facilities->getfacilities($data['facilities_id']);
		$this->load->model('setting/timezone');
		$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
		date_default_timezone_set($timezone_info['timezone_value']);
		$noteDate = date('Y-m-d H:i:s', strtotime('now'));
		
		if($data['type'] == '1'){
			//$facilityOnline = $this->getfacilitiesOnline2($data);
			//if(empty($facilityOnline)){
				$sql = "INSERT INTO `" . DB_PREFIX . "facility_online` (ip, facilities_id, url, referer, date_added, facility_count) VALUES ('".$this->db->escape($data['ip'])."','".(int)$data['facilities_id']."', '".$this->db->escape($data['url'])."', '".$this->db->escape($data['referer'])."', '".$noteDate."', '1') "; 
				
				$this->db->query($sql);
			/*}else{
				$sql = "UPDATE `" . DB_PREFIX . "facility_online` SET facility_count = '1', date_added = '".$noteDate."' WHERE facilities_id = '" . (int)$data['facilities_id'] . "' and ip = '".$data['ip']."' ";
		
				$this->db->query($sql);
			}*/
		}
		
		
		if($data['type'] == '2'){
			//$facilityOnline1 = $this->getfacilitiesOnline23($data);
						
			//if(empty($facilityOnline1)){
				$sql = "INSERT INTO `" . DB_PREFIX . "facility_online` (ip,facilities_id, url,referer,date_added,username, facility_login, facility_count) VALUES ('".$this->db->escape($data['ip'])."','".(int)$data['facilities_id']."', '".$this->db->escape($data['url'])."', '".$this->db->escape($data['referer'])."', '".$noteDate."', '".$data['username']."', '".$data['activationkey']."', '1') "; 
				
				$this->db->query($sql);
			/*}else{
				$sql = "UPDATE `" . DB_PREFIX . "facility_online` SET facility_count = '1', date_added = '".$noteDate."' WHERE facilities_id = '" . (int)$data['facilities_id'] . "' and  username = '".$data['username']."' ";
		
				$this->db->query($sql);
			}*/
		}
		
		
	}
	
	public function getfacilitiesOnline23($data = array()) {
		//$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "facility_online` WHERE facilities_id = '" . $this->db->escape($data['facilities_id']) . "' and ip= '".$data['ip']."' and username= '".$data['username']."' ");
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "facility_online` WHERE facilities_id = '" . $this->db->escape($data['facilities_id']) . "' and username= '".$data['username']."' ");
	
		return $query->row;
	}
	
	
	public function getfacilitiesOnline2($data = array()) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "facility_online` WHERE facilities_id = '" . $this->db->escape($data['facilities_id']) . "' and ip= '".$data['ip']."' ");
	
		return $query->row;
	}
	
	
	public function updatefacilitiesOnline2($data = array()) {
		//var_dump(data['facilities_id']);
		
		//die;
		$this->load->model('facilities/facilities');
		$facilities_info = $this->model_facilities_facilities->getfacilities($data['facilities_id']);
		$this->load->model('setting/timezone');
		$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
		date_default_timezone_set($timezone_info['timezone_value']);
		$noteDate = date('Y-m-d H:i:s', strtotime('now'));
			
		//$sql = "UPDATE `" . DB_PREFIX . "facility_online` SET date_added = '".$noteDate."' WHERE facilities_id = '" . (int)$data['facilities_id'] . "' and ip = '".$data['ip']."' and  username = '".$data['username']."' ";
		//$query = $this->db->query($sql);
	
	}
}
?>