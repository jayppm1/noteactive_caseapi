<?php
class Modelsettingbedchecktaskform extends Model {
	
	public function getbedchecktaskform($task_form_id) {
		$query = $this->db->query("SELECT DISTINCT task_form_location_id,task_form_id,task_form_name,facilities_id,form_type,type FROM " . DB_PREFIX . "task_form WHERE task_form_id = '" . (int)$task_form_id . "'");
		
		return $query->row;
	}
	
	public function getruleModule($task_form_id){
		$query = $this->db->query("SELECT DISTINCT task_form_location_id,task_form_id,location_name,locations_id,location_detail,current_occupency,sort_order,facilities_id FROM " . DB_PREFIX . "task_form_location WHERE task_form_id = '" . (int)$task_form_id . "'");
		$module_data = array();
		if ($query->num_rows) {
			foreach ($query->rows as $result) {
				$module_data['bctf_module'][] = array(
					'task_form_location_id'             => $result['task_form_location_id'],
					'task_form_id'      => $result['task_form_id'],
					'location_name'       => $result['location_name'],
					'locations_id'       => $result['locations_id'],
					'location_detail' => $result['location_detail'],
					'facility_facilities_id' => $result['facilities_id'],
					'current_occupency'     => $result['current_occupency'],
					'sort_order'              => $result['sort_order']
				);
			}
		}

		return $module_data;
		
	}
	
	public function getBCTFs($data = array()) {
		$sql = "SELECT task_form_id,task_form_name,facilities_id,form_type,type FROM " . DB_PREFIX . "task_form";
		
		
		$sql.= " where 1 = 1 ";
		
		if($data['status'] != null && $data['status'] != ""){
			$sql.= " and status = '".$data['status']."'";
		}
		
		if($data['facilities_id'] != null && $data['facilities_id'] != ""){
			$sql.= " and facilities_id = '".$data['facilities_id']."'";
		}
		
		$sql .= " ORDER BY task_form_name";	
			
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		
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
	
	public function getTotalBCTF() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "task_form");
		
		return $query->row['total'];
	}	
	
	
	public function getbedchecktasklocation($task_form_location_id) {
		$query = $this->db->query("SELECT DISTINCT task_form_location_id,task_form_id,location_name,locations_id,location_detail,current_occupency,facilities_id FROM " . DB_PREFIX . "task_form_location WHERE task_form_location_id = '" . (int)$task_form_location_id . "'");
		
		return $query->row;
	}
	
	
	
}
?>