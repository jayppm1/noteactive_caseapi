<?php
class Modelsettinglocations extends Model {
	
	
	public function getlocation($locations_id) {
		$query = $this->db->query("SELECT DISTINCT locations_id,location_name,location_address,location_detail,capacity,location_type,nfc_location_tag,nfc_location_tag_required,	gps_location_tag,	gps_location_tag_required,latitude	,longitude,other_location_tag,other_location_tag_required,other_type_id,facilities_id,upload_file,customlistvalues_id FROM " . DB_PREFIX . "locations WHERE locations_id = '" . (int)$locations_id . "'");
		
		return $query->row;
	}


	public function getformid($task_form_id) {
		$sql = "SELECT DISTINCT task_form_location_id,task_form_id,location_name,locations_id,location_detail,current_occupency  FROM " . DB_PREFIX . "task_form_location WHERE task_form_id = '" . (int)$task_form_id . "' order by sort_order asc";
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	
	public function getformid2($bed_check_location_ids) {
		$sql = "SELECT DISTINCT task_form_location_id,task_form_id,location_name,locations_id,location_detail,current_occupency  FROM " . DB_PREFIX . "task_form_location WHERE task_form_location_id in (" . $bed_check_location_ids . ") order by sort_order asc";
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function getlocationbyName($location_name,$facilities_id) {
		$query = $this->db->query("SELECT DISTINCT locations_id,location_name,location_address,location_detail,capacity,location_type,nfc_location_tag,nfc_location_tag_required,	gps_location_tag,gps_location_tag_required,latitude	,longitude,other_location_tag,other_location_tag_required,other_type_id,facilities_id,upload_file,customlistvalues_id FROM " . DB_PREFIX . "locations WHERE location_name = '" . $location_name . "' and facilities_id = '".$facilities_id."' ");
		
		return $query->row;
	} 
	
	
	
	public function getlocations($data = array()) {
		$sql = "SELECT locations_id,location_name,location_address,location_detail,capacity,location_type,nfc_location_tag,nfc_location_tag_required,	gps_location_tag,gps_location_tag_required,latitude	,longitude,other_location_tag,other_location_tag_required,other_type_id,facilities_id,upload_file,customlistvalues_id FROM " . DB_PREFIX . "locations";
		
		$sql.= " where 1 = 1 ";
		
		if ($data ['q'] !=null && $data ['q'] !="") {
			$sql .= " and LOWER(location_name) like '%" . strtolower ( $data ['q'] ) . "%'";
		}

		
		if($data['location_name'] != null && $data['location_name'] != ""){
			$sql.= " and location_name like '%".$data['location_name']."%'";
		}
		
		if($data['status'] != null && $data['status'] != ""){
			$sql.= " and status = '".$data['status']."'";
		}
		
		if($data['type'] != null && $data['type'] != ""){
			$sql.= " and type = '".$data['type']."'";
		}
		
		if($data['facilities_id'] != null && $data['facilities_id'] != ""){
			//$sql.= " and facilities_id = '".$data['facilities_id']."'";
		}
		
		if ($data['facilities'] != null && $data['facilities'] != "") {
			$sql .= " and facilities_id in (". $data['facilities'].") ";  
		}
		
		if($data['app_user_date']!='' && $data['current_date_user']!=''){
			$sql .= " and `update_date` BETWEEN  '" . $data ['app_user_date'] . "' AND  '" . $data ['current_date_user'] . " 23:59:59' ";
		}
		
	
		$this->load->model('facilities/facilities');
		$facility_info = $this->model_facilities_facilities->getfacilities($data['facilities_id']);
		$ddss = array();
		if ( $facility_info['client_facilities_ids'] != null && $facility_info['client_facilities_ids'] != "" ) {
			
			$ddss[] = $facility_info['client_facilities_ids'];
			
			$ddss[] = $data['facilities_id'];
			
			if($data['is_submaster'] == '1'){
				$sssssddsg = explode(",",$facility_info ['client_facilities_ids']);
				$abdcg = array_unique($sssssddsg);
				$cids = array();
				foreach($abdcg as $fid){
					$cids[] = $fid;
				}
				$abdcgs = array_unique($cids);
				foreach($abdcgs as $fid2){
					$facilityinfo = $this->model_facilities_facilities->getfacilities ( $fid2 );
					if ( $facilityinfo['client_facilities_ids'] != null && $facilityinfo['client_facilities_ids'] != "" ) {
						$ddss[] = $facilityinfo['client_facilities_ids'];
					}
				}
			}
			
			$sssssdd = implode(",",$ddss);
			$sql.= " and facilities_id in (" . $sssssdd . ") ";
			$faculities_ids = $sssssdd;
		}else{
			if ($data['facilities_id'] != null && $data['facilities_id'] != "") {
				$sql .= " and facilities_id in (". $data['facilities_id'].") ";  
			}
		}
		
		
		$sql .= " ORDER BY location_name";	
			
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
	
	public function getTotallocations($data = array()) {
		
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "locations` ";
		$sql .= 'where 1 = 1 ';
		
		if($data['location_name'] != null && $data['location_name'] != ""){
			$sql.= " and location_name like '%".$data['location_name']."%'";
		}
		
		if($data['status'] != null && $data['status'] != ""){
			$sql.= " and status = '".$data['status']."'";
		}
		
		if($data['type'] != null && $data['type'] != ""){
			$sql.= " and type = '".$data['type']."'";
		}
		
		if($data['is_master'] == '1'){
			$this->load->model('facilities/facilities');
			$facility_info = $this->model_facilities_facilities->getfacilities($data['facilities_id']);
			$ddss = array();
			if ( $facility_info['client_facilities_ids'] != null && $facility_info['client_facilities_ids'] != "" ) {
				
				$ddss[] = $facility_info['client_facilities_ids'];
				
				$ddss[] = $data['facilities_id'];
				
				if($data['is_submaster'] == '1'){
					$sssssddsg = explode(",",$facility_info ['client_facilities_ids']);
					$abdcg = array_unique($sssssddsg);
					$cids = array();
					foreach($abdcg as $fid){
						$cids[] = $fid;
					}
					$abdcgs = array_unique($cids);
					foreach($abdcgs as $fid2){
						$facilityinfo = $this->model_facilities_facilities->getfacilities ( $fid2 );
						if ( $facilityinfo['client_facilities_ids'] != null && $facilityinfo['client_facilities_ids'] != "" ) {
							$ddss[] = $facilityinfo['client_facilities_ids'];
						}
					}
				}
				
				$sssssdd = implode(",",$ddss);
				$sql.= " and facilities_id in (" . $sssssdd . ") ";
				$faculities_ids = $sssssdd;
			}else{
				if ($data['facilities_id'] != null && $data['facilities_id'] != "") {
					$sql .= " and facilities_id in (". $data['facilities_id'].") ";  
				}
			}
		}else{
			if($data['facilities_id'] != null && $data['facilities_id'] != ""){
				$sql .= " and facilities_id in (". $data['facilities_id'].") ";  
			}
		}
		
		if($data['app_user_date']!='' && $data['current_date_user']!=''){
			//$sql .= " and `update_date` BETWEEN  '" . $data ['app_user_date'] . "' AND  '" . $data ['current_date_user'] . " 23:59:59' ";
		}
		
		//echo $sql;
		//echo "<hr>";
		
		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	
	
}
?>