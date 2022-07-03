<?php
class Modelmedicationtypemedicationtype extends Model {
	
	public function getmedicationtype($medicationtype_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "medicationtype WHERE medicationtype_id = '" . (int)$medicationtype_id . "'");
		
		return $query->row;
	}
	
	public function getmedicationtypes($data = array()) {
		
		if ($data['facilities_id'] != null && $data['facilities_id'] != "") {
		$sql = "SELECT * FROM " . DB_PREFIX . "medicationtype";
		
		$sql.= " where 1 = 1 ";
		$sql .= " and FIND_IN_SET('".$data['facilities_id']."',facilities_id) ";	
			
		
		$sql .= " ORDER BY type_name";	
			
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
		}
		return $query->rows;
	}

	
}
?>