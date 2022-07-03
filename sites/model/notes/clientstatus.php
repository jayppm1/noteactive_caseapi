<?php
class Modelnotesclientstatus extends Model {
	
	public function getclientstatus($tag_status_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "tag_status WHERE tag_status_id = '" . (int)$tag_status_id . "'");		
		return $query->row;
	}
	
	/*
	public function getclientstatuss($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "tag_status";
		
		$sql.= " where 1 = 1 and status = 1  ";
		
		if ($data['facilities_id'] != null && $data['facilities_id'] != "") {
			
			$this->load->model('facilities/facilities');
			$facilities_info = $this->model_facilities_facilities->getfacilities($data['facilities_id']);
			
			if ($facilities_info ['notes_facilities_ids'] != null && $facilities_info ['notes_facilities_ids'] != "") {
				$ddss [] = $facilities_info ['notes_facilities_ids'];
				
				$ddss [] = $data['facilities_id'];
				$fffa = implode(',', $ddss);
				//$sql .= " and facilities_id IN (".$fffa.")";
				$sql .= " and FIND_IN_SET('".$data['facilities_id']."',facilities_id) ";
			}else{
				$sql .= " and FIND_IN_SET('".$data['facilities_id']."',facilities_id) ";
				
				
			}
			
		}
		
		$sql .= " ORDER BY tag_status_id";	
			
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
	}*/
	
	public function getclientstatuss($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "tag_status";
		
		$sql.= " where 1 = 1 and status = 1  ";

		$facility_data = $this->model_facilities_facilities->getfacilities($data['facilities_id']);


		if($facility_data['is_master_facility'] == '1'){
			$this->load->model('facilities/facilities');
			$facility_info = $this->model_facilities_facilities->getfacilities($data['facilities_id']);
			
			$ddss = array();
			if ( $facility_info['client_facilities_ids'] != null && $facility_info['client_facilities_ids'] != "" ) {
				
				$ddss[] = $facility_info['client_facilities_ids'];
				
				$ddss[] = $data['facilities_id'];
				$sssssdd = implode(",",$ddss);

				//$sql.= " and facilities_id in (" . $sssssdd . ") ";
				$sql .= " and FIND_IN_SET('".$data['facilities_id']."',facilities_id) ";
				$faculities_ids = $sssssdd;


			}else{
				if ($data['facilities_id'] != null && $data['facilities_id'] != "") {
				//$sql.= " and facilities_id in (".$data['facilities_id'].")";
				$sql .= " and FIND_IN_SET('".$data['facilities_id']."',facilities_id) ";
				$n_facilities_id = $data['facilities_id'];
				}
			}
		}else{
		
			if($data['facilities_id'] != null && $data['facilities_id'] != ""){
				//$sql.= " and facilities_id in (".$data['facilities_id'].")";
				$sql .= " and FIND_IN_SET('".$data['facilities_id']."',facilities_id) ";
				$n_facilities_id = $data['facilities_id'];
			}
		}
		
		if($data['role_call'] != null && $data['role_call'] != ""){
			$sql.= " and tag_status_id = '".$data['role_call']."'";
		}
		
		
		/*if ($data['facilities_id'] != null && $data['facilities_id'] != "") {
			
			$this->load->model('facilities/facilities');
			$facilities_info = $this->model_facilities_facilities->getfacilities($data['facilities_id']);
			
			if ($facilities_info ['notes_facilities_ids'] != null && $facilities_info ['notes_facilities_ids'] != "") {
				$ddss [] = $facilities_info ['notes_facilities_ids'];
				
				$ddss [] = $data['facilities_id'];
				$fffa = implode(',', $ddss);
				//$sql .= " and facilities_id IN (".$fffa.")";
				$sql .= " and FIND_IN_SET('".$data['facilities_id']."',facilities_id) ";
			}else{
				$sql .= " and FIND_IN_SET('".$data['facilities_id']."',facilities_id) ";
				
				
			}
			
		}*/
		
		$sql .= " ORDER BY tag_status_id";	
			
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

		//echo $sql;
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function getTotalclientsstatus($data = array()) {
		$sql = " where 1 = 1 and status = 1 ";
		
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "tag_status ".$sql." ");
		
		return $query->row['total'];
	}


	public function getclassification($tag_status_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "tag_classification WHERE tag_status_id = '" . (int)$tag_status_id . "'");		
		return $query->row;
	}
	
	public function getclassifications($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "tag_classification";
		
		$sql.= " where 1 = 1 and status = 1  ";
		
		if ($data['facilities_id'] != null && $data['facilities_id'] != "") {
			
			$this->load->model('facilities/facilities');
			$facilities_info = $this->model_facilities_facilities->getfacilities($data['facilities_id']);
			
			if ($facilities_info ['notes_facilities_ids'] != null && $facilities_info ['notes_facilities_ids'] != "") {
				$ddss [] = $facilities_info ['notes_facilities_ids'];
				
				$ddss [] = $data['facilities_id'];
				$fffa = implode(',', $ddss);
				//$sql .= " and facilities_id IN (".$fffa.")";
				$sql .= " and FIND_IN_SET('".$data['facilities_id']."',facilities_id) ";
			}else{
				$sql .= " and FIND_IN_SET('".$data['facilities_id']."',facilities_id) ";
				
				
			}
			
		}
		
		$sql .= " ORDER BY tag_classification_id";	
			
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
	
	public function getTotalclientsclassifications($data = array()) {
		$sql = " where 1 = 1 and status = 1 ";
		
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "tag_classification ".$sql." ");
		
		return $query->row['total'];
	}

  
}
?>