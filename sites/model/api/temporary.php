<?php
class Modelapitemporary extends Model {
	
	public function addtemporary($tdata, $tadata) {
		
		$this->load->model('facilities/facilities');
		$facilities_info = $this->model_facilities_facilities->getfacilities($tadata['facilities_id']);
		$this->load->model('setting/timezone');
					
		$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
		$facilitytimezone = $timezone_info['timezone_value'];
		$timeZone = date_default_timezone_set($timezone_name);
		$date_added = date('Y-m-d H:i:s', strtotime('now'));
		
		
		$tsql = "INSERT INTO " . DB_PREFIX . "temporary SET id = '" . $this->db->escape($tadata['id']) . "',facilities_id = '" . $this->db->escape($tadata['facilities_id']) . "',type = '" . $this->db->escape($tadata['type']) . "', phone_device_id = '" . $this->db->escape($tadata['phone_device_id']) . "', parent_id = '" . $this->db->escape($tadata['parent_id']) . "', parent_archive_forms_id = '" . $this->db->escape($tadata['parent_archive_forms_id']) . "', `data` = '" . $this->db->escape(serialize($tdata)) . "', date_added = '".$date_added."' ";
		
		$this->db->query($tsql);
		$temporary_id = $this->db->getLastId();
		
		return $temporary_id;
	}
	
	
	public function gettemporary($temporary_id){
		$sql = "select DISTINCT * from `" . DB_PREFIX . "temporary` ";
		$sql .= " where temporary_id = '".(int)$temporary_id."' ";
		
		$query = $this->db->query($sql);
		
		return $query->row;
	}
	
	public function gettemporaryparent($temporary_id){
		$sql = "select DISTINCT * from `" . DB_PREFIX . "temporary` ";
		$sql .= " where parent_archive_forms_id = '".(int)$temporary_id."' ";
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function deletetemporary($temporary_id){
		//$this->db->query("DELETE FROM `" . DB_PREFIX . "temporary` WHERE temporary_id = '" . (int)$temporary_id . "'");
		$this->db->query("UPDATE `" . DB_PREFIX . "temporary` SET status ='Success' WHERE temporary_id = '" . (int)$temporary_id . "'");
	}
	public function deletetemporary2($temporary_id){
		//$this->db->query("DELETE FROM `" . DB_PREFIX . "temporary` WHERE temporary_id = '" . (int)$temporary_id . "'");
		$this->db->query("UPDATE `" . DB_PREFIX . "temporary` SET status ='Success' WHERE parent_archive_forms_id = '" . (int)$temporary_id . "'");
	}
	
	public function gettemporaryparentrow($temporary_id){
		$sql = "select DISTINCT * from `" . DB_PREFIX . "temporary` ";
		$sql .= " where parent_archive_forms_id = '".(int)$temporary_id."' ";
		
		$query = $this->db->query($sql);
		
		return $query->row;
	}
	
	
	public function deletetemporary3($parent_archive_forms_id){
		$this->db->query("DELETE FROM `" . DB_PREFIX . "temporary` WHERE parent_archive_forms_id = '" . (int)$parent_archive_forms_id . "'");
		
	}
	
}
	
	