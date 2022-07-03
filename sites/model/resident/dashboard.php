<?php
class Modelresidentdashboard extends Model {
	
	public function gettasks($data = array()){
		$sql = "SELECT * FROM " . DB_PREFIX . "createtask" ;
		$sql .= " where 1 = 1 and taskadded ='0' ";
		
		if($data['searchdate'] != null && $data['searchdate'] != ""){
			$date = str_replace('-', '/', $data['searchdate']);
			$res = explode("/", $date);
			$changedDate = $res[2]."-".$res[1]."-".$res[0];
			
			$startDate = $changedDate;
			
			$date2 = str_replace('-', '/', $data['enddate']);
			$res2 = explode("/", $date2);
			$changedDate2 = $res2[2]."-".$res2[1]."-".$res2[0];
			$endDate = $changedDate2;
			
			$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59') ";
		}
		
		if ($data['facilities_id'] != null && $data['facilities_id'] != "") {
			$sql .= " and facilityId = '".$data['facilities_id']."'";
		} 
		
		$query = $this->db->query($sql);
		return $query->rows;
		
	
		
	}
	
	
	public function getTotalTasks($data = array()) {
		
		$sql = "SELECT COUNT(DISTINCT id) AS total FROM " . DB_PREFIX . "createtask where 1 = 1 and taskadded ='0' " ;
		
		if($data['searchdate'] != null && $data['searchdate'] != ""){
			$date = str_replace('-', '/', $data['searchdate']);
			$res = explode("/", $date);
			$changedDate = $res[2]."-".$res[1]."-".$res[0];
			
			$startDate = $changedDate;
			
			$date2 = str_replace('-', '/', $data['enddate']);
			$res2 = explode("/", $date2);
			$changedDate2 = $res2[2]."-".$res2[1]."-".$res2[0];
			$endDate = $changedDate2;
				
			$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59') ";
		}
		
		if ($data['facilities_id'] != null && $data['facilities_id'] != "") {
			$sql .= " and facilityId = '".$data['facilities_id']."'";
		} 
		
		$query = $this->db->query($sql);
		return $query->row['total'];
		
	}
	
	
	public function getcriticaltasks($data = array()){
		$sql = "SELECT * FROM " . DB_PREFIX . "createtask" ;
		$sql .= " where 1 = 1 and taskadded ='0' ";
		
		if($data['searchdate'] != null && $data['searchdate'] != ""){
			$date = str_replace('-', '/', $data['searchdate']);
			$res = explode("/", $date);
			$changedDate = $res[2]."-".$res[1]."-".$res[0];
			
			$startDate = $changedDate;
			
			
			$date2 = str_replace('-', '/', $data['enddate']);
			$res2 = explode("/", $date2);
			$changedDate2 = $res2[2]."-".$res2[1]."-".$res2[0];
			$endDate = $changedDate2;
				
			$sql .= " and (`end_recurrence_date` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59') ";
		}
		
		if ($data['facilities_id'] != null && $data['facilities_id'] != "") {
			$sql .= " and facilityId = '".$data['facilities_id']."'";
		}
		
		$query = $this->db->query($sql);
		return $query->rows;
		
	}
	
	public function gettotalCriticalTasks($data = array()) {
		
		$sql = "SELECT COUNT(DISTINCT id) AS total FROM " . DB_PREFIX . "createtask" ;
		$sql .= " where 1 = 1 and taskadded ='0' ";
		
		if($data['searchdate'] != null && $data['searchdate'] != ""){
			$date = str_replace('-', '/', $data['searchdate']);
			$res = explode("/", $date);
			$changedDate = $res[2]."-".$res[1]."-".$res[0];
			
			$startDate = $changedDate;
			
			
			$date2 = str_replace('-', '/', $data['enddate']);
			$res2 = explode("/", $date2);
			$changedDate2 = $res2[2]."-".$res2[1]."-".$res2[0];
			$endDate = $changedDate2;
				
			$sql .= " and (`end_recurrence_date` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59') ";
		}
		
		if ($data['facilities_id'] != null && $data['facilities_id'] != "") {
			$sql .= " and facilityId = '".$data['facilities_id']."'";
		}
		
		$query = $this->db->query($sql);
		return $query->row['total'];
		
	}
	
	
	public function getmoderatetasks($data = array()){
		$sql = "SELECT * FROM " . DB_PREFIX . "createtask" ;
		$sql .= " where 1 = 1 and taskadded ='0' ";
		
		if($data['searchdate'] != null && $data['searchdate'] != ""){
			$date = str_replace('-', '/', $data['searchdate']);
			$res = explode("/", $date);
			$changedDate = $res[2]."-".$res[1]."-".$res[0];
			
			$startDate = $changedDate;
			
			$date2 = str_replace('-', '/', $data['enddate']);
			$res2 = explode("/", $date2);
			$changedDate2 = $res2[2]."-".$res2[1]."-".$res2[0];
			$endDate = $changedDate2;
				
			$sql .= " and (`end_recurrence_date` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59') ";
		}
		
		if ($data['facilities_id'] != null && $data['facilities_id'] != "") {
			$sql .= " and facilityId = '".$data['facilities_id']."'";
		}
		
		$query = $this->db->query($sql);
		return $query->rows;
		
	}
	
	public function getTotalModerateTasks($data = array()) {
		
		$sql = "SELECT COUNT(DISTINCT id) AS total FROM " . DB_PREFIX . "createtask" ;
		$sql .= " where 1 = 1 and taskadded ='0' ";
		
		if($data['searchdate'] != null && $data['searchdate'] != ""){
			$date = str_replace('-', '/', $data['searchdate']);
			$res = explode("/", $date);
			$changedDate = $res[2]."-".$res[1]."-".$res[0];
			
			$startDate = $changedDate;
			
			$date2 = str_replace('-', '/', $data['enddate']);
			$res2 = explode("/", $date2);
			$changedDate2 = $res2[2]."-".$res2[1]."-".$res2[0];
			$endDate = $changedDate2;
				
			$sql .= " and (`end_recurrence_date` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59') ";
		}
		
		if ($data['facilities_id'] != null && $data['facilities_id'] != "") {
			$sql .= " and facilityId = '".$data['facilities_id']."'";
		}
		
		$query = $this->db->query($sql);
		return $query->row['total'];
		
	}
	
	
	public function getdaysTasks($date) {
	
		$sql = "SELECT COUNT(DISTINCT id) AS total FROM " . DB_PREFIX . "createtask" ;
		$sql .= " where 1 = 1 and taskadded ='0' ";
		$sql .= " and `date_added` BETWEEN  '".$date." 00:00:00 ' AND  '".$date." 23:59:59' ";
		
		$query = $this->db->query($sql);
		return $query->row['total'];		
	
	}
	
	
	public function getfirstNote($date) {
		
		$sql = "SELECT * FROM " . DB_PREFIX . "notes";
		
		$sql .= " where 1 = 1 and taskadded ='0' ";
		$sql .= " and `date_added` BETWEEN  '".$date." 00:00:00 ' AND  '".$date." 23:59:59'  order by notes_id ASC limit 0,1  ";
		
		$query = $this->db->query($sql);
		return $query->row;
	}
	
}