<?php
class Modelsettingtimezone extends Model {
	
	public function gettimezone($timezone_id) {
		$query = $this->db->query("SELECT DISTINCT timezone_id,timezone_name,timezone_value FROM " . DB_PREFIX . "timezone WHERE timezone_id = '" . (int)$timezone_id . "'");
		
		return $query->row;
	}
	
	public function gettimezones($data = array()) {
		$sql = "SELECT timezone_id,timezone_name,timezone_value FROM " . DB_PREFIX . "timezone";
		
		$sql .= " ORDER BY sort_order";	
			
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
	
	public function getTotaltimezones() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "timezone");
		
		return $query->row['total'];
	}	
}
?>