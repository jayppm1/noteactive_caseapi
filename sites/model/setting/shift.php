<?php
class Modelsettingshift extends Model {
	
	
	public function getshift($shift_id) {
		$query = $this->db->query("SELECT DISTINCT shift_id,facilities_id,shift_name,status,shift_startdate,shift_enddate,shift_starttime_hour	,shift_starttime_minutes,shift_endtime_hour,shift_endtime_minutes,sort_order,date_added,shift_starttime,shift_endtime FROM " . DB_PREFIX . "shift WHERE shift_id = '" . (int)$shift_id . "'");
		
		return $query->row;
	}
	
	public function getshifts($data = array()) {
		$sql = "SELECT shift_id,facilities_id,shift_name,status,shift_startdate,shift_enddate,shift_starttime_hour	,shift_starttime_minutes,shift_endtime_hour,shift_endtime_minutes,sort_order,date_added,shift_starttime,shift_endtime FROM " . DB_PREFIX . "shift ";
		
		$sql .= ' where 1 = 1 ';
		if ($data['facilities_id'] != null && $data['facilities_id'] != "") {
			$sql .= " and facilities_id = '".$data['facilities_id']."' ";
		}
		
		
		if ($data['status'] != null && $data['status'] != "") {
			$sql .= " and status = '".$data['status']."'";
		}
		
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
	
	public function getTotalshifts($data = array()) {
		
		$sql .= ' where 1 = 1 ';
		if ($data['facilities_id'] != null && $data['facilities_id'] != "") {
			$sql .= " and facilities_id = '".$data['facilities_id']."' ";
		}
		
		
		if ($data['status'] != null && $data['status'] != "") {
			$sql .= " and status = '".$data['status']."'";
		}
		
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "shift ".$sql." ");
		
		return $query->row['total'];
	}	
	
	
}
?>