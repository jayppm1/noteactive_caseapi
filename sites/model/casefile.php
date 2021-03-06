<?php
class Modelresidentcasefile extends Model {
	
	public function getcasefile($data = array()) {
		
		if ($data ['case_file_id'] != NULL && $data ['case_file_id'] != "") {
			
			$sql = "SELECT * FROM " . DB_PREFIX . "notes_by_case_file";
			
			$sql .= " where 1 = 1 ";
			
			if ($data ['case_file_id'] != "-1") {
				$sql .= " and notes_by_case_file_id = '" . $data ['case_file_id'] . "' ";
			}
			
			if ($data ['facilities_id'] != NULL && $data ['facilities_id'] != "") {
				$sql .= " AND facilities_id='" . $data ['facilities_id'] . "'";
			}
			
			if ($data ['tags_id'] != "") {
				$sql .= " and tags_ids = '" . $data ['tags_id'] . "' ";
			}
			
			if ($data ['status'] != "") {
				$sql .= " and case_status = '" . $data ['status'] . "' ";
			}
			
			if ($data ['case_number'] == "1") {
				$sql .= " and case_number != '' ";
			}
			
			if ($data ['is_case'] == "1") {
				$sql .= " and is_case = '" . $data ['is_case'] . "' ";
			}
			
			$sql .= " ORDER BY date_added";
			
			if (isset ( $data ['order'] ) && ($data ['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}
			
			if (isset ( $data ['start'] ) || isset ( $data ['limit'] )) {
				if ($data ['start'] < 0) {
					$data ['start'] = 0;
				}
				
				if ($data ['limit'] < 1) {
					$data ['limit'] = 20;
				}
				
				$sql .= " LIMIT " . ( int ) $data ['start'] . "," . ( int ) $data ['limit'];
			}
			
			//echo 'VVI-'.$sql;
			
			$query = $this->db->query ( $sql );
			
			return $query->row;
		}
	}
	
	public function getcasefileforviewcase($data = array()) {
		
		if ($data ['case_file_id'] != NULL && $data ['case_file_id'] != "") {
			
			$sql = "SELECT * FROM " . DB_PREFIX . "notes_by_case_file";
			
			$sql .= " where 1 = 1 ";
			
			if ($data ['case_file_id'] != "") {
				$sql .= " and notes_by_case_file_id = '" . $data ['case_file_id'] . "' ";
			}
			
			if ($data ['facilities_id'] != NULL && $data ['facilities_id'] != "") {
				$sql .= " AND facilities_id='" . $data ['facilities_id'] . "'";
			}
			
			if ($data ['tags_id'] != "") {
				$sql .= " and tags_ids = '" . $data ['tags_id'] . "' ";
			}
			
			if ($data ['status'] != "") {
				$sql .= " and case_status = '" . $data ['status'] . "' ";
			}
			
			if ($data ['case_number'] == "1") {
				$sql .= " and case_number != '' ";
			}
			
			if ($data ['is_case'] == "1") {
				$sql .= " and is_case = '" . $data ['is_case'] . "' ";
			}
			
			$sql .= " ORDER BY date_added";
			
			if (isset ( $data ['order'] ) && ($data ['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}
			
			if (isset ( $data ['start'] ) || isset ( $data ['limit'] )) {
				if ($data ['start'] < 0) {
					$data ['start'] = 0;
				}
				
				if ($data ['limit'] < 1) {
					$data ['limit'] = 20;
				}
				
				$sql .= " LIMIT " . ( int ) $data ['start'] . "," . ( int ) $data ['limit'];
			}
			
			//echo $sql;
			
			$query = $this->db->query ( $sql );
			
			return $query->row;
		}
	}
	
	public function getcasefileforviewcase2($data = array()) {
		
		//	echo '<pre>'; print_r($data); echo '</pre>'; 
		
		
		$sql = "SELECT a.*,b.* FROM dg_notes_by_case_file AS a INNER JOIN dg_forms AS b ON a.case_number=b.case_number ";
		
		$sql .= " where 1 = 1 ";
		
		if ($data ['case_file_id'] != "") {
			$sql .= " AND a.notes_by_case_file_id = '" . $data ['case_file_id'] . "' ";
		}
		
		if ($data ['facilities_id'] != NULL && $data ['facilities_id'] != "") {
			$sql .= " AND a.facilities_id='" . $data ['facilities_id'] . "'";
		}
		
		if ($data ['tags_id'] != "") {
			//$sql .= " and a.tags_ids = '" . $data ['tags_id'] . "' ";
			
			$sql .= " and find_in_set('" . $data ['tags_id'] . "',a.tags_ids) ";
		}
		
		if ($data ['status'] != "") {
			$sql .= " and a.case_status = '" . $data ['status'] . "' ";
		}
		
		if ($data ['case_number'] != "") {
			$sql .= " AND a.case_number = '" .$data ['case_number']. "'";
		}
		
		if ($data ['is_case'] == "1") {
			$sql .= " and a.is_case = '" . $data ['is_case'] . "' ";
		}
		
		
		$sql .= " ORDER BY a.date_added";
		
		if (isset ( $data ['order'] ) && ($data ['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		
		if (isset ( $data ['start'] ) || isset ( $data ['limit'] )) {
			if ($data ['start'] < 0) {
				$data ['start'] = 0;
			}
			
			if ($data ['limit'] < 1) {
				$data ['limit'] = 20;
			}
			
			$sql .= " LIMIT " . ( int ) $data ['start'] . "," . ( int ) $data ['limit'];
		}
		//echo $sql;
		$query = $this->db->query ( $sql );
		return $query->rows;
	}
	
	
	public function getcasefiles($data = array()) {
		if ($data ['tags_id'] != NULL && $data ['tags_id'] != "") {
			
			$sql = "SELECT * FROM " . DB_PREFIX . "notes_by_case_file";
			
			$sql .= " where 1 = 1 ";
			
			if ($data ['tags_id'] != "") {
				
				$sql .= " and FIND_IN_SET('".$data ['tags_id']."',tags_ids) ";
			}
			
			if ($data ['status'] != "") {
				$sql .= " and case_status = '" . $data ['status'] . "' ";
			}
			
			if ($data ['case_number'] == "1") {
				$sql .= " and case_number != '' ";
			}
			
			if ($data ['is_case'] == "1") {
				// $sql .= " and is_case = '" . $data ['is_case'] . "' ";
			}
			
			$sql .= " ORDER BY date_added";
			
			if (isset ( $data ['order'] ) && ($data ['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}
			
			if (isset ( $data ['start'] ) || isset ( $data ['limit'] )) {
				if ($data ['start'] < 0) {
					$data ['start'] = 0;
				}
				
				if ($data ['limit'] < 1) {
					$data ['limit'] = 20;
				}
				
				$sql .= " LIMIT " . ( int ) $data ['start'] . "," . ( int ) $data ['limit'];
			}
			
			//echo $sql;
			
			$query = $this->db->query ( $sql );
			if ($data ['is_status'] == 1) {
				return $query->row;
			} else {
				
				return $query->rows;
			}
		}
	}
	
	public function getcasefiles2($data = array()) {
		if ($data ['tags_id'] != NULL && $data ['tags_id'] != "") {
			
			$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "notes_by_case_file";
			
			$sql .= " where 1 = 1 ";
			
			if ($data ['tags_id'] != "") {
				
				$sql .= " and FIND_IN_SET('".$data ['tags_id']."',tags_ids) ";
			}
			
			if ($data ['status'] != "") {
				$sql .= " and case_status = '" . $data ['status'] . "' ";
			}
			
			if ($data ['case_number'] == "1") {
				$sql .= " and case_number != '' ";
			}
			
			if ($data ['is_case'] == "1") {
				// $sql .= " and is_case = '" . $data ['is_case'] . "' ";
			}
			
			$sql .= " ORDER BY date_added";
			
			if (isset ( $data ['order'] ) && ($data ['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}
			
			/*
			if (isset ( $data ['start'] ) || isset ( $data ['limit'] )) {
				if ($data ['start'] < 0) {
					$data ['start'] = 0;
				}
				
				if ($data ['limit'] < 1) {
					$data ['limit'] = 20;
				}
				
				$sql .= " LIMIT " . ( int ) $data ['start'] . "," . ( int ) $data ['limit'];
			}*/
			
			//echo $sql;
			
			$query = $this->db->query ( $sql );
			
			return $query->row ['total'];
		}
	}
	
	public function insertCasefile($data = array()){
		
		//echo '<pre>'; print_r($data); echo '</pre>';
		
		$date_added = ( string ) date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
		
		$sql = "SELECT * FROM " . DB_PREFIX . "notes_by_case_file";
		
		$sql .= " WHERE 1 = 1 AND case_number= '" . $data ['case_number'] . "' ";
		
		$query = $this->db->query ( $sql );
		
		if ($query->num_rows) {
			
			$result = $query->row;
			if($result ['forms_ids'] != null && $result ['forms_ids'] != ""){
				$old_forms_ids = explode ( ',', $result ['forms_ids'] );
			}else{
				$old_forms_ids = array();
			}
			if($data ['forms_ids'] != null && $data ['forms_ids'] != ""){
				$new_forms_ids = explode ( ',', $data ['forms_ids'] );
			}else{
				$new_forms_ids = array();
			}
			
			$merge_arr = array_merge ( $old_forms_ids, $new_forms_ids );
			
			$unique_forms_ids = array_unique ( $merge_arr );
			
			$forms_ids_string = implode ( ',', $unique_forms_ids );
			
			if($result ['tags_ids'] != null && $result ['tags_ids'] != ""){
				$tags_ids_ids = explode ( ',', $result ['tags_ids'] );
			}else{
				$tags_ids_ids = array();
			}
			
			if($data ['tags_ids'] != null && $data ['tags_ids'] != ""){
				$new_tags_ids = explode ( ',', $data ['tags_ids'] );
			}else{
				$new_tags_ids = array();
			}
			
			$smerge_arr = array_merge ( $tags_ids_ids, $new_tags_ids );
			$unique_tags_ids = array_unique ( $smerge_arr );
			$tags_ids = implode ( ',', $unique_tags_ids );
			
			
			 $sql = "UPDATE `" . DB_PREFIX . "notes_by_case_file` SET 
				case_number = '" . $data ['case_number'] . "',
				case_status = '" . $data ['case_status'] . "',
				forms_ids = '" . $forms_ids_string . "',
				notes_id = '" . $data ['notes_id'] . "',  
				facilities_id = '" . $data ['facilities_id'] . "',
				notes_pin = '" . $data ['notes_pin'] . "',
				user_id = '" . $data ['user_id'] . "',
				signature  =  '" . $data ['signature'] . "',
				date_added = '" . $date_added . "',
				
				tags_ids = '" . $tags_ids . "'
				
				WHERE case_number='" . $data ['case_number'] . "' ";
			
			
			$this->db->query ( $sql );
			
			if($data ['case_type'] != null && $data ['case_type'] != ""){
				 $sqlc = "UPDATE `" . DB_PREFIX . "notes_by_case_file` SET
				
				case_type =  '" . $data ['case_type'] . "',
				
				incident_type =  '" . $data ['incident_type'] . "',
				code = '" . $data ['code'] . "',
				user_of_force_name = '" . $data ['user_of_force_name'] . "',
				criminal_charges_filed = '" . $data ['criminal_charges_filed'] . "'
				WHERE case_number='" . $data ['case_number'] . "' ";
				
				
				$this->db->query ( $sqlc );
			}
				
		} else {
			
			$sql = "INSERT INTO `" . DB_PREFIX . "notes_by_case_file` SET 
				case_number = '" . $data ['case_number'] . "',
				case_status = '" . $data ['case_status'] . "',
				forms_ids = '" . $data ['forms_ids'] . "',
				notes_id = '" . $data ['notes_id'] . "',  
				tags_ids = '" . $data ['tags_ids'] . "', 
				facilities_id = '" . $data ['facilities_id'] . "',
				notes_pin = '" . $data ['notes_pin'] . "',
				user_id = '" . $data ['user_id'] . "',
				signature =  '" . $data ['signature'] . "',
				date_added = '" . $date_added . "',
				case_type =  '" . $data ['case_type'] . "',
				incident_type =  '" . $data ['incident_type'] . "',
				code = '" . $data ['code'] . "',
				user_of_force_name = '" . $data ['user_of_force_name'] . "',
				criminal_charges_filed = '" . $data ['criminal_charges_filed'] . "'";
				$unique_forms_ids = explode ( ',', $data ['forms_ids'] );
				
				$this->db->query ( $sql );
		}
		
		//echo $sql; die;
		
		
		
		foreach ( $unique_forms_ids as $val ) {
			$this->db->query ( "UPDATE `" . DB_PREFIX . "forms` SET is_case = '1', date_updated = '" . $this->db->escape ( $date_added ) . "', case_number = '" . $data ['case_number'] . "' WHERE forms_id = '" . ( int ) $val . "'" );
		}
	}
	
	public function getTotalcasefiles() {
		$sql = " where 1 = 1 ";
		
		$query = $this->db->query ( "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "notes_by_case_file " . $sql . " " );
		
		return $query->row ['total'];
	}
	
	public function getUnsignedFormByTagsId($data = array()) {
		
		// echo '<pre>'; print_r($data); echo '</pre>';
		if ($data ['tags_id'] != NULL && $data ['tags_id'] != "") {
			
			$sql = "SELECT  C.date_added, F.* FROM " . DB_PREFIX . "forms AS F

					LEFT JOIN " . DB_PREFIX . "notes_by_case_file AS C ON F.tags_id=C.tags_ids ";
			
			$sql .= " where 1 = 1 ";
			
			$sql .= " AND F.is_case=0 ";
			
			if ($data ['tags_id'] != "") {
				$sql .= " AND F.tags_id= '" . $data ['tags_id'] . "' ";
			}
			
			$sql .= " GROUP BY F.forms_id ORDER BY F.date_added";
			
			if (isset ( $data ['order'] ) && ($data ['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}
			
			if (isset ( $data ['start'] ) || isset ( $data ['limit'] )) {
				if ($data ['start'] < 0) {
					$data ['start'] = 0;
				}
				
				if ($data ['limit'] < 1) {
					$data ['limit'] = 20;
				}
				
				$sql .= " LIMIT " . ( int ) $data ['start'] . "," . ( int ) $data ['limit'];
			}
			
			// echo $sql;
			
			$query = $this->db->query ( $sql );
			
			return $query->rows;
		}
	}
	
	public function getCaseNumber($data = array()) {
		
		//echo '<pre>'; print_r($data); echo '</pre>'; //die;
		
		$sql .= "SELECT * FROM `" . DB_PREFIX . "notes_by_case_file` ";
		
		$sql .= " WHERE 1 = 1 ";
		
		if ($data ['facilities_id'] != NULL && $data ['facilities_id'] != "") {
			
			$sql .= " AND facilities_id='" . $data ['facilities_id'] . "'";
		}
		
		if ($data ['tags_id'] != NULL && $data ['tags_id'] != "") {
			
			//$sql .= "AND tags_ids='" . $data ['tags_id'] . "'";
			
			$sql .= "AND find_in_set(".$data ['tags_id'].",tags_ids) ";
		}
		
		if (isset($data ['status'])) {
			
			$sql .= "AND case_status='" . $data ['status'] . "'";
		}
		
		if ($data ['case_number'] != null && $data ['case_number'] != "") {
			$sql .= " and LOWER(case_number like '%" . strtolower ( $data ['case_number'] ) . "%' ) ";
		}
		
		if ($data ['case_file_id'] != NULL && $data ['case_file_id'] != "") {
			
			$sql .= " AND notes_by_case_file_id='" . $data ['case_file_id'] . "'";
		}
		
		
		if ($data ['case_type'] != null && $data ['case_type'] != "") {
			$sql .= " and FIND_IN_SET(".$data ['case_type'].",case_type) ";
		}
		
		if ($data ['incident_type'] != null && $data ['incident_type'] != "") {
			$sql .= " and FIND_IN_SET(".$data ['incident_type'].",incident_type) ";
		}
		
		if ($data ['code'] != null && $data ['code'] != "") {
			$sql .= " and FIND_IN_SET(".$data ['code'].",code) ";
		}
		
		// $sql1 .= 'GROUP BY case_number';
		
		
		
		$sort_data = array(
			'case_number',
			'tags_ids',
			'case_status',
			'date_added'
		);
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY date_added";	
		}
	
		if (isset ( $data ['order'] ) && ($data ['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		
		if (isset ( $data ['start'] ) || isset ( $data ['limit'] )) {
			if ($data ['start'] < 0) {
				$data ['start'] = 0;
			}
			
			if ($data ['limit'] < 1) {
				$data ['limit'] = 20;
			}
			
			$sql .= " LIMIT " . ( int ) $data ['start'] . "," . ( int ) $data ['limit'];
		}
		
		//echo $sql;
		
		$query = $this->db->query ( $sql );
		
		if ($data ['case_file_id'] != NULL && $data ['case_file_id'] != "") {
			
			return $query->row;
		} else {
			
			return $query->rows;
		}
	}
	
	public function getCaseNumber2 ($data = array()) {
		
		$sql .= "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "notes_by_case_file` ";
		
		$sql .= " WHERE 1 = 1 ";
		
		if ($data ['facilities_id'] != NULL && $data ['facilities_id'] != "") {
			
			$sql .= " AND facilities_id='" . $data ['facilities_id'] . "'";
		}
		
		if ($data ['tags_id'] != NULL && $data ['tags_id'] != "") {
			
			//$sql .= "AND tags_ids='" . $data ['tags_id'] . "'";
			
			$sql .= "AND find_in_set(".$data ['tags_id'].",tags_ids) ";
		}
		
		if (isset($data ['status'])) {
			
			$sql .= "AND case_status='" . $data ['status'] . "'";
		}
		
		if ($data ['case_number'] != null && $data ['case_number'] != "") {
			$sql .= " and LOWER(case_number like '%" . strtolower ( $data ['case_number'] ) . "%' ) ";
		}
		
		if ($data ['case_file_id'] != NULL && $data ['case_file_id'] != "") {
			
			$sql .= " AND notes_by_case_file_id='" . $data ['case_file_id'] . "'";
		}
		
		if ($data ['case_type'] != null && $data ['case_type'] != "") {
			$sql .= " and FIND_IN_SET(".$data ['case_type'].",case_type) ";
		}
		
		if ($data ['incident_type'] != null && $data ['incident_type'] != "") {
			$sql .= " and FIND_IN_SET(".$data ['incident_type'].",incident_type) ";
		}
		
		if ($data ['code'] != null && $data ['code'] != "") {
			$sql .= " and FIND_IN_SET(".$data ['code'].",code) ";
		}
		
		
		$sort_data = array(
			'case_number',
			'tags_ids',
			'case_status',
			'date_added'
		);
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY date_added";	
		}
	
		if (isset ( $data ['order'] ) && ($data ['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
	
		//echo $sql;
		
		$query = $this->db->query ( $sql );
			
		return $query->row ['total'];
	}
	
	public function getcasefileByCasenumber($data = array()) {
		
		// echo '<pre>'; print_r($data); echo '</pre>'; //die;
		if ($data ['case_number'] != NULL && $data ['case_number'] != "") {
			
			$sql = "SELECT * FROM " . DB_PREFIX . "notes_by_case_file";
			
			$sql .= " where 1 = 1 ";
			
			if ($data ['case_number'] != "") {
				$sql .= " and case_number = '" . $data ['case_number'] . "' ";
			}
			
			if ($data ['facilities_id'] != "") {
				$sql .= " and facilities_id = '" . $data ['facilities_id'] . "' ";
			}
			
			$sql .= " ORDER BY date_added";
			
			if (isset ( $data ['order'] ) && ($data ['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}
			
			if (isset ( $data ['start'] ) || isset ( $data ['limit'] )) {
				if ($data ['start'] < 0) {
					$data ['start'] = 0;
				}
				
				if ($data ['limit'] < 1) {
					$data ['limit'] = 20;
				}
				
				$sql .= " LIMIT " . ( int ) $data ['start'] . "," . ( int ) $data ['limit'];
			}
			
			// echo $sql;
			
			$query = $this->db->query ( $sql );
			
			return $query->row;
		}
	}
	
	public function getcasefilesbynotesid($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "notes_by_case_file";
		
		$sql .= " where 1 = 1 and notes_id = '" . $data ['notes_id'] . "' ";
		
		// echo $sql;
		
		$query = $this->db->query ( $sql );
		
		return $query->row;
	}

	public function getinmatelist($data = array()){
		$sql = "SELECT t2.tags_id,t2.emp_first_name,t2.emp_last_name,t1.notes_by_case_file_id,t1.tags_ids FROM dg_notes_by_case_file as t1 RIGHT JOIN dg_tags as t2 ON find_in_set(t2.tags_id,t1.tags_ids) WHERE 1=1 ";
		
		
		if ($data ['emp_tag_id_all'] != null && $data ['emp_tag_id_all'] != "") {
			//$sql .= " and ( ";
			$sql .= " AND LOWER(t2.emp_first_name like '%" . strtolower ( $data ['emp_tag_id_all'] ) . "%'  OR t2.emp_last_name like '%" . strtolower ( $data ['emp_tag_id_all'] ) . "%' ) ";
			
			//$sql .= " ) ";
		}
		
		
		
		$sort_data = array (
				'emp_tag_id',
				'status',
				'emp_first_name',
				'emp_last_name',
				'privacy',
				'sort_order',
				'modify_date',
				'room',
				'l.location_name',
				'date_added' 
		);
		
		
		
		
		
		
		
		if (isset ( $data ['sort'] ) && in_array ( $data ['sort'], $sort_data )) {
			$sql .= " ORDER BY " . $data ['sort'];
			$n_orderby = $data ['sort'];
		} else {
			$sql .= " ORDER BY t.sort_order";
			$n_orderby = " t.sort_order ";
		}
		
		if (isset ( $data ['order'] ) && ($data ['order'] == 'DESC')) {
			$sql .= " DESC";
			$n_orderby .= " DESC";
		} else {
			$sql .= " ASC";
			$n_orderby .= " ASC";
		}
		
		if (isset ( $data ['start'] ) || isset ( $data ['limit'] )) {
			if ($data ['start'] < 0) {
				$data ['start'] = 0;
			}
			
			if ($data ['limit'] < 1) {
				$data ['limit'] = 20;
			}
			
			$sql .= " LIMIT " . ( int ) $data ['start'] . "," . ( int ) $data ['limit'];
			
			$n_start = $data ['start'];
			$n_limit = $data ['limit'];
		}
		
		
		//echo $sql;
		
		$query = $this->db->query ( $sql );
		
		return $query->rows;
		
	}
	
	public function get_last_casenumber(){
		$sql = "SELECT case_number FROM dg_notes_by_case_file ORDER BY notes_by_case_file_id DESC LIMIT 1 ";
		
		/*
		if ($data ['emp_tag_id_all'] != null && $data ['emp_tag_id_all'] != "") {
			//$sql .= " and ( ";
			$sql .= " AND LOWER(t2.emp_first_name like '%" . strtolower ( $data ['emp_tag_id_all'] ) . "%'  OR t2.emp_last_name like '%" . strtolower ( $data ['emp_tag_id_all'] ) . "%' ) ";
			
			//$sql .= " ) ";
		}
		
		
	
		
		if (isset ( $data ['order'] ) && ($data ['order'] == 'DESC')) {
			$sql .= " DESC";
			$n_orderby .= " DESC";
		} else {
			$sql .= " ASC";
			$n_orderby .= " ASC";
		}
		
		if (isset ( $data ['start'] ) || isset ( $data ['limit'] )) {
			if ($data ['start'] < 0) {
				$data ['start'] = 0;
			}
			
			if ($data ['limit'] < 1) {
				$data ['limit'] = 20;
			}
			
			$sql .= " LIMIT " . ( int ) $data ['start'] . "," . ( int ) $data ['limit'];
			
			$n_start = $data ['start'];
			$n_limit = $data ['limit'];
		}*/
		
		
		//echo $sql;
		
		$query = $this->db->query ( $sql );
		
		return $query->row;
		
	}




}