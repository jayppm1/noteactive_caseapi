<?php
class Modeltasktasktype extends Model {
	
	public function addtasktype($data, $fdata) {
		
		$alerttype  = implode(",",$data['alert_type']);
		$customlist_id = implode(',',$data['customlist_id']);
		$customlistvalueids = implode(',',$data['customlistvalueids']);
		
		$sql = "INSERT INTO " . DB_PREFIX . "tasktype SET tasktype_name = '" . $this->db->escape($data['tasktype_name']) . "',  alert_type = '" . $alerttype . "',  status = '" . $this->db->escape($data['status']) . "',  sort_order = '" . $this->db->escape($data['sort_order']) . "',  relation_keyword_id = '" . $this->db->escape($data['relation_keyword_id']) . "',  custom_completion_rule = '" . $this->db->escape($data['custom_completion_rule']) . "',  config_task_complete = '" . $this->db->escape($data['config_task_complete']) . "',  config_task_after_complete = '" . $this->db->escape($data['config_task_after_complete']) . "',  config_task_deleted_time = '" . $this->db->escape($data['config_task_deleted_time']) . "', generate_report = '" . $this->db->escape($data['generate_report']) . "', forms_id = '" . $this->db->escape($data['forms_id']) . "', display_custom_list = '" . $this->db->escape($data['display_custom_list']) . "', customlist_id = '" . $this->db->escape($customlist_id) . "', customlistvalueids = '" . $this->db->escape($customlistvalueids) . "',  config_task_minandmax_time = '" . $this->db->escape($data['config_task_minandmax_time']) . "',  config_task_minandmax_after_time = '" . $this->db->escape($data['config_task_minandmax_after_time']) . "',  enable_location = '" . $this->db->escape($data['enable_location']) . "',  enable_location_tracking = '" . $this->db->escape($data['enable_location_tracking']) . "',  enable_requires_approval = '" . $this->db->escape($data['enable_requires_approval']) . "',  auto_extend = '" . $this->db->escape($data['auto_extend']) . "',  auto_extend_time = '" . $this->db->escape($data['auto_extend_time']) . "'

		, is_web_notification = '" . $this->db->escape($data['is_web_notification']) . "'
		, web_is_snooze = '" . $this->db->escape($data['web_is_snooze']) . "'
		, web_is_dismiss = '" . $this->db->escape($data['web_is_dismiss']) . "'
		, is_android_notification = '" . $this->db->escape($data['is_android_notification']) . "'
		, is_android_snooze = '" . $this->db->escape($data['is_android_snooze']) . "'
		, is_android_dismiss = '" . $this->db->escape($data['is_android_dismiss']) . "'
		, is_ios_notification = '" . $this->db->escape($data['is_ios_notification']) . "'
		, is_ios_snooze = '" . $this->db->escape($data['is_ios_snooze']) . "'
		, is_ios_dismiss = '" . $this->db->escape($data['is_ios_dismiss']) . "'
		, is_buffer = '" . $this->db->escape($data['is_buffer']) . "'
		, is_display_report = '" . $this->db->escape($data['is_display_report']) . "'
		, client_required = '" . $this->db->escape($data['client_required']) . "'
		, field_required = '" . $this->db->escape($data['field_required']) . "'
		, is_task_rule = '" . $this->db->escape($data['is_task_rule']) . "'
		, is_custom_offset = '" . $this->db->escape($data['is_custom_offset']) . "'
		, type = '" . $this->db->escape($data['type']) . "'
		, customer_key = '" . $this->session->data['customer_key'] . "'
		,is_facility = '" . $this->db->escape($data['is_facility']) . "'
		,facility_type = '" . $this->db->escape($data['facility_type']) . "'
		, config_task_complete_max = '" . $this->db->escape($data['config_task_complete_max']) . "'
		, date_added = NOW(), update_date = NOW()
		";
		
		$this->db->query($sql);
		
		$task_id = $this->db->getLastId();
		
		if($fdata['web_audio_file'] != null && $fdata['web_audio_file'] != ""){
			$this->db->query("UPDATE " . DB_PREFIX . "tasktype SET web_audio_file = '" . $this->db->escape($fdata['web_audio_file']) . "' WHERE task_id = '" . (int)$task_id . "'");
		}
		
		if($fdata['android_audio_file'] != null && $fdata['android_audio_file'] != ""){
			$this->db->query("UPDATE " . DB_PREFIX . "tasktype SET android_audio_file = '" . $this->db->escape($fdata['android_audio_file']) . "' WHERE task_id = '" . (int)$task_id . "'");
		}
		
		if($fdata['ios_audio_file'] != null && $fdata['ios_audio_file'] != ""){
			$this->db->query("UPDATE " . DB_PREFIX . "tasktype SET ios_audio_file = '" . $this->db->escape($fdata['ios_audio_file']) . "' WHERE task_id = '" . (int)$task_id . "'");
		}
		
		$this->load->model('api/cache');
		$this->model_api_cache->deletecache('getTaskdetails');
	}
	
	public function gettasktype($data = array()) {
		
		$sql = "SELECT * FROM " . DB_PREFIX . "tasktype";
		$sql .= ' where 1 = 1 ';
		
		if ($data['tasktype_name'] != null && $data['tasktype_name'] != "") {
			$sql .= " and tasktype_name like '%".$data['tasktype_name']."%'";
		}
		
		if ($data['tasks_ids'] != null && $data['tasks_ids'] != "") {
				$sql .= " and task_id IN (". $data['tasks_ids'].")";
		}
		
		
		
		if ($data['status'] != null && $data['status'] != "") {
			$sql .= " and status = '".$data['status']."'";
		}
		if(ALLTASKTYPE == '1'){
			if ($this->session->data['customer_key'] != null && $this->session->data['customer_key'] != "") {
				$sql .= " and customer_key = '".$this->session->data['customer_key']."'";
			}
		}
		
		$sql .= " Order by sort_order ASC ";
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}			

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getactivetasktype() {
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tasktype where status = '1' ");
		return $query->rows;
	}
	
	
	public function gettasktyperow($task_id) {
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tasktype WHERE task_id = '" . (int)$task_id . "'");
		return $query->row;
	}
	
	public function deletetasktype($task_id){
	 $this->db->query("DELETE FROM " . DB_PREFIX . "tasktype WHERE task_id = '" . (int)$task_id . "'");	
	 $this->load->model('api/cache');
		$this->model_api_cache->deletecache('getTaskdetails');
	}
	
	
	public function edittasktype($task_id, $data, $fdata){
		
		$alerttype  = implode(",",$data['alert_type']);
		$customlist_id = implode(',',$data['customlist_id']);
		$customlistvalueids = implode(',',$data['customlistvalueids']);
		
		 $sql = "UPDATE " . DB_PREFIX . "tasktype SET tasktype_name = '" . $this->db->escape($data['tasktype_name']) . "', alert_type = '" . $alerttype . "', status = '" . $this->db->escape($data['status']) . "',  sort_order = '" . $this->db->escape($data['sort_order']) . "' , relation_keyword_id = '" . $this->db->escape($data['relation_keyword_id']) . "',  custom_completion_rule = '" . $this->db->escape($data['custom_completion_rule']) . "',  config_task_complete = '" . $this->db->escape($data['config_task_complete']) . "',  config_task_after_complete = '" . $this->db->escape($data['config_task_after_complete']) . "',  config_task_deleted_time = '" . $this->db->escape($data['config_task_deleted_time']) . "', generate_report = '" . $this->db->escape($data['generate_report']) . "', forms_id = '" . $this->db->escape($data['forms_id']) . "', display_custom_list = '" . $this->db->escape($data['display_custom_list']) . "', customlist_id = '" . $this->db->escape($customlist_id) . "', customlistvalueids = '" . $this->db->escape($customlistvalueids) . "',  config_task_minandmax_time = '" . $this->db->escape($data['config_task_minandmax_time']) . "',  config_task_minandmax_after_time = '" . $this->db->escape($data['config_task_minandmax_after_time']) . "',  enable_location = '" . $this->db->escape($data['enable_location']) . "', enable_location_tracking = '" . $this->db->escape($data['enable_location_tracking']) . "', enable_requires_approval = '" . $this->db->escape($data['enable_requires_approval']) . "',  auto_extend = '" . $this->db->escape($data['auto_extend']) . "',  auto_extend_time = '" . $this->db->escape($data['auto_extend_time']) . "'
		
		, is_web_notification = '" . $this->db->escape($data['is_web_notification']) . "'
		, web_is_snooze = '" . $this->db->escape($data['web_is_snooze']) . "'
		, web_is_dismiss = '" . $this->db->escape($data['web_is_dismiss']) . "'
		, is_android_notification = '" . $this->db->escape($data['is_android_notification']) . "'
		, is_android_snooze = '" . $this->db->escape($data['is_android_snooze']) . "'
		, is_android_dismiss = '" . $this->db->escape($data['is_android_dismiss']) . "'
		, is_ios_notification = '" . $this->db->escape($data['is_ios_notification']) . "'
		, is_ios_snooze = '" . $this->db->escape($data['is_ios_snooze']) . "'
		, is_ios_dismiss = '" . $this->db->escape($data['is_ios_dismiss']) . "'
		, is_buffer = '" . $this->db->escape($data['is_buffer']) . "'
		, client_required = '" . $this->db->escape($data['client_required']) . "'
		, field_required = '" . $this->db->escape($data['field_required']) . "'
		, is_display_report = '" . $this->db->escape($data['is_display_report']) . "'
		, is_task_rule = '" . $this->db->escape($data['is_task_rule']) . "'
		, is_custom_offset = '" . $this->db->escape($data['is_custom_offset']) . "'
		, type = '" . $this->db->escape($data['type']) . "'
		, customer_key = '" . $this->session->data['customer_key'] . "'
		,is_facility = '" . $this->db->escape($data['is_facility']) . "'
		,facility_type = '" . $this->db->escape($data['facility_type']) . "'
		, config_task_complete_max = '" . $this->db->escape($data['config_task_complete_max']) . "'
		, update_date = NOW()

		 WHERE task_id = '" . (int)$task_id . "'";
		
		
		$this->db->query($sql);
		
		
		if($fdata['web_audio_file'] != null && $fdata['web_audio_file'] != ""){
			$this->db->query("UPDATE " . DB_PREFIX . "tasktype SET web_audio_file = '" . $this->db->escape($fdata['web_audio_file']) . "' WHERE task_id = '" . (int)$task_id . "'");
		}
		
		if($fdata['android_audio_file'] != null && $fdata['android_audio_file'] != ""){
			$this->db->query("UPDATE " . DB_PREFIX . "tasktype SET android_audio_file = '" . $this->db->escape($fdata['android_audio_file']) . "' WHERE task_id = '" . (int)$task_id . "'");
		}
		
		if($fdata['ios_audio_file'] != null && $fdata['ios_audio_file'] != ""){
			$this->db->query("UPDATE " . DB_PREFIX . "tasktype SET ios_audio_file = '" . $this->db->escape($fdata['ios_audio_file']) . "' WHERE task_id = '" . (int)$task_id . "'");
		}
		
		$this->load->model('api/cache');
		$this->model_api_cache->deletecache('getTaskdetails'); 
	}
	
	
	public function getTotaltasktype($data = array()) {
		$sql .= ' where 1 = 1 ';
		
		if ($data['tasktype_name'] != null && $data['tasktype_name'] != "") {
			$sql .= " and tasktype_name like '%".$data['tasktype_name']."%'";
		}
		
		if ($data['status'] != null && $data['status'] != "") {
			$sql .= " and status = '".$data['status']."'";
		}
		
		if(ALLTASKTYPE == '1'){
			if ($this->session->data['customer_key'] != null && $this->session->data['customer_key'] != "") {
				$sql .= " and customer_key = '".$this->session->data['customer_key']."'";
			}
		}
		
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "tasktype ".$sql."");
		
		return $query->row['total'];
	}	
	
	public function getTaskByName($taskname) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "tasktype` WHERE tasktype_name = '" . $this->db->escape($taskname) . "' and customer_key = '" . $this->session->data['customer_key'] . "' ");

		return $query->row;
	}
	
	public function gettasktype2($data = array()) {
		
		if ($data['tasks_ids'] != "") {
			$sql = "SELECT f.tasktype_name, f.task_id FROM " . DB_PREFIX . "notes n LEFT JOIN dg_tasktype f ON f.task_id = n.tasktype LEFT JOIN dg_notes_tags nt ON nt.notes_id = n.notes_id ";
			$sql .= ' where 1 = 1 ';
			
			$sql .= " and nt.tags_id = '". $data['tags_id']."' ";
			
			$sql .= " and n.tasktype IN (". $data['tasks_ids'].") group by tasktype ";
			
		
			$query = $this->db->query($sql);
		
			return $query->rows;
		}
	}
	
	
}