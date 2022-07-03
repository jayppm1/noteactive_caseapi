<?php
class Modelnotescaseservices extends Model {


	public function totalNotes($data) {
		
		$sql ="SELECT COUNT(DISTINCT n.notes_id) AS total FROM `" . DB_PREFIX . "notes` n ";
		$sql .= "left JOIN " . DB_PREFIX . "notes_tags nt on nt.notes_id=n.notes_id  ";
		$sql .= ' where n.status = 1 ';
		
		if ($data['customer_key'] != null && $data['customer_key'] != "") {
			$sql .= " and n.unique_id = '".$this->db->escape($data['customer_key'])."'";
		}
		
		if ($data['emp_tag_id'] != null && $data['emp_tag_id'] != "0") {
			$sql .= " and nt.tags_id = '".$this->db->escape($data['emp_tag_id'])."'";
		}
		
		if ($data['facilities_id'] != null && $data['facilities_id'] != "") {
			$sql .= " and n.facilities_id = '".$data['facilities_id']."'";
		}
		
		
		if (($data['note_date_from'] != null && $data['note_date_from'] != "") && ($data['note_date_to'] != null && $data['note_date_to'] != "")) {
			$startDate = date('Y-m-d', strtotime($data['note_date_from']));
			$endDate = date('Y-m-d', strtotime($data['note_date_to']));
			
			if (($data['search_time_start'] != null && $data['search_time_start'] != "") && ($data['search_time_to'] != null && $data['search_time_to'] != "")) {
					
				$startTimeFrom =  date('H:i:s', strtotime($data['search_time_start'])) ;
				$startTimeTo =  date('H:i:s', strtotime($data['search_time_to'])) ;
					
				//$sql .= " and (n.`notetime` BETWEEN  '".$startTimeFrom."' AND  '".$startTimeTo."') ";
			}else{
				$startTimeFrom = '00:00:00';
				$startTimeTo =  '23:59:59';
			}
			
			//$sql .= " and `date_added` BETWEEN  '".$startDate." 00:00:00 ' AND  '".$endDate." 23:59:59' ";
			$sql .= " and ( n.`date_added` BETWEEN '".$startDate." ".$startTimeFrom." ' AND '".$endDate." ".$startTimeTo."'  ) ";
			
		}
		//echo $sql;
		$query = $this->db->query($sql);
		
		return $query->row['total'];
		
		
	}
	
	
	
	public function totalForms($data) {
		
		
		$sql = "SELECT COUNT(DISTINCT f.forms_id) as total FROM " . DB_PREFIX . "forms f  ";
			
			$sql .= "left JOIN " . DB_PREFIX . "notes_tags nt on nt.notes_id=f.notes_id  ";
			
			$sql .= " where 1 = 1 and form_design_parent_id = 0 ";
			
			
			
			if ($data['emp_tag_id'] != null && $data['emp_tag_id'] != "0") {
				$sql .= " and nt.tags_id = '".$this->db->escape($data['emp_tag_id'])."'";
			}
			
			if ($data['facilities_id'] != null && $data['facilities_id'] != "") {
				$sql .= " and f.facilities_id = '".$data['facilities_id']."'";
			}
			
			
			if (($data['note_date_from'] != null && $data['note_date_from'] != "") && ($data['note_date_to'] != null && $data['note_date_to'] != "")) {
			$startDate = date('Y-m-d', strtotime($data['note_date_from']));
			$endDate = date('Y-m-d', strtotime($data['note_date_to']));
			
			if (($data['search_time_start'] != null && $data['search_time_start'] != "") && ($data['search_time_to'] != null && $data['search_time_to'] != "")) {
					
				$startTimeFrom =  date('H:i:s', strtotime($data['search_time_start'])) ;
				$startTimeTo =  date('H:i:s', strtotime($data['search_time_to'])) ;
					
				//$sql .= " and (n.`notetime` BETWEEN  '".$startTimeFrom."' AND  '".$startTimeTo."') ";
			}else{
				$startTimeFrom = '00:00:00';
				$startTimeTo =  '23:59:59';
			}
			
			//$sql .= " and `date_added` BETWEEN  '".$startDate." 00:00:00 ' AND  '".$endDate." 23:59:59' ";
			$sql .= " and ( f.`date_added` BETWEEN '".$startDate." ".$startTimeFrom." ' AND '".$endDate." ".$startTimeTo."'  ) ";
		}
		
		$query = $this->db->query($sql);
		return $query->row['total'];
	}
	
	
	public function totalKeywords($data) {
		
		$sql ="SELECT COUNT(DISTINCT n.notes_id) AS total FROM `" . DB_PREFIX . "notes` n ";
		$sql .= "left JOIN " . DB_PREFIX . "notes_tags nt on nt.notes_id=n.notes_id  ";
		$sql .= "left JOIN " . DB_PREFIX . "notes_by_keyword nk on nk.notes_id=n.notes_id  ";
		$sql .= ' where n.status = 1 ';
		
		if ($data['customer_key'] != null && $data['customer_key'] != "") {
			$sql .= " and n.unique_id = '".$this->db->escape($data['customer_key'])."'";
		}
		
		if ($data['emp_tag_id'] != null && $data['emp_tag_id'] != "0") {
			$sql .= " and nt.tags_id = '".$this->db->escape($data['emp_tag_id'])."'";
		}
		
		if ($data['facilities_id'] != null && $data['facilities_id'] != "") {
			$sql .= " and n.facilities_id = '".$data['facilities_id']."'";
		}
		
		
		
			
			if ($data['activenote'] != null && $data['activenote'] != "") {
				
				if($data['activenote'] == 'all'){
					
					$query2 = $this->db->query("SELECT * FROM " . DB_PREFIX . "keyword ");
			
					$results = $query2->rows;
					
					$userIds2 = array();
					foreach($results as $result){
						if($result['keyword_image'] != null && $result['keyword_image'] != ""){
						$userIds2[] = $result['keyword_image'];
						}
					}
					$userIds12 = array_unique($userIds2);
					
					$userIds21 = implode('\',\'',$userIds12); 
					
					if($userIds21 != null && $userIds21 != ""){
					$userIds21 = str_replace("all,","",$userIds21);
					
					$sql .= " and nk.keyword_file in ('". $userIds21."') ";
					
					}
					
				}else{
					$query2 = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "keyword WHERE keyword_id = '" . (int)$data['activenote'] . "'");
			
					$keydata = $query2->row;
			
					$sql .= " and nk.keyword_file = '".$keydata['keyword_image']."'";
				}
			}
					
					
					
		
		
		if (($data['note_date_from'] != null && $data['note_date_from'] != "") && ($data['note_date_to'] != null && $data['note_date_to'] != "")) {
			$startDate = date('Y-m-d', strtotime($data['note_date_from']));
			$endDate = date('Y-m-d', strtotime($data['note_date_to']));
			
			if (($data['search_time_start'] != null && $data['search_time_start'] != "") && ($data['search_time_to'] != null && $data['search_time_to'] != "")) {
					
				$startTimeFrom =  date('H:i:s', strtotime($data['search_time_start'])) ;
				$startTimeTo =  date('H:i:s', strtotime($data['search_time_to'])) ;
					
				//$sql .= " and (n.`notetime` BETWEEN  '".$startTimeFrom."' AND  '".$startTimeTo."') ";
			}else{
				$startTimeFrom = '00:00:00';
				$startTimeTo =  '23:59:59';
			}
			
			//$sql .= " and `date_added` BETWEEN  '".$startDate." 00:00:00 ' AND  '".$endDate." 23:59:59' ";
			$sql .= " and ( n.`date_added` BETWEEN '".$startDate." ".$startTimeFrom." ' AND '".$endDate." ".$startTimeTo."'  ) ";
			
		}
		
		$query = $this->db->query($sql);
		return $query->row['total'];
		}
		
	public function allKeywordsbyTotal($data = array()) {
	
			$kesql = "SELECT * FROM `" . DB_PREFIX . "keyword` WHERE customer_key = '".(int)$data['customer_key']."' and status = '1' ORDER BY keyword_name asc";
			$queryke = $this->db->query($kesql);
						
			$keyworda = array();
				
				foreach($queryke->rows as $keyword){
					$kesql ="SELECT * FROM `" . DB_PREFIX . "notes_by_keyword` nk ";
					$kesql .= "left JOIN " . DB_PREFIX . "notes_tags nt on nt.notes_id=nk.notes_id  ";
					$kesql .= " where 1 = '1' and nk.keyword_file = '".$keyword['keyword_image']."' and nk.facilities_id = '".(int)$data['facilities_id']."' and nt.tags_id = '".(int)$data['emp_tag_id']."'";
					
					
					
					
					if (($data ['note_date_from'] != null && $data ['note_date_from'] != "") && ($data ['note_date_to'] != null && $data ['note_date_to'] != "")) {
					
					$startDate = date ( 'Y-m-d', strtotime ( $data ['note_date_from'] ) );
					$endDate = date ( 'Y-m-d', strtotime ( $data ['note_date_to'] ) );
					
			
					if (($data ['search_time_start'] != null && $data ['search_time_start'] != "") && ($data ['search_time_to'] != null && $data ['search_time_to'] != "")) {
						
						$startTimeFrom = date ( 'H:i:s', strtotime ( $data ['search_time_start'] ) );
						$startTimeTo = date ( 'H:i:s', strtotime ( $data ['search_time_to'] ) );
						
						$startTimeFrom_o = '00:00:00';
						$startTimeTo_o = '23:59:59';
						
						$n_note_date_from_t = $startDate . " " . $startTimeFrom_o;
						$n_note_date_to_t = $endDate . " " . $startTimeTo_o;
						$n_note_date_from_time = $startTimeFrom;
						$n_note_date_to_time = $startTimeTo;
						
						 $sql .= " and (n.`notetime` BETWEEN '".$startTimeFrom."' AND
						 '".$startTimeTo."') ";
						
					
					} else {
						$startTimeFrom = '00:00:00';
						$startTimeTo = '23:59:59';
						
						$n_note_date_from = $startDate . " " . $startTimeFrom;
						$n_note_date_to = $endDate . " " . $startTimeTo;
					}
					
					 $sql .= " and ( nk.`date_added` BETWEEN '".$startDate."
					 ".$startTimeFrom."' AND '".$endDate." ".$startTimeTo."' ) ";
					
					}
		
		
					
							
					$query = $this->db->query($kesql);
							
							$count = array();
							foreach($query->rows as $note){
								$count[] = $note['notes_id'];
							}
							
							if( sizeof($count) > 0){
							$keyworda[] = array(
								'keyword_name' => $keyword['keyword_name'],
								'keywordCount' => sizeof($count),
							);
							
							
							$countNotes = $countNotes + sizeof($count);
							}
						}					//	var_dump($countNotes);
						
						//$data['userKeywords'] = $keyworda;
		
			return $keyworda;
		}
		
	public function BarChartbyTotal($data=array())
	{
			$notesgaphgraph=array();
			
			$wheref = " and facilities_id = '".$data['facilities_id']."'";
			
			if (($data ['note_date_from'] != null && $data ['note_date_from'] != "") && ($data ['note_date_to'] != null && $data ['note_date_to'] != "")) {
					
					$startDate = date ( 'Y-m-d', strtotime ( $data ['note_date_from'] ) );
					$endDate = date ( 'Y-m-d', strtotime ( $data ['note_date_to'] ) );
					
			
					if (($data ['search_time_start'] != null && $data ['search_time_start'] != "") && ($data ['search_time_to'] != null && $data ['search_time_to'] != "")) {
						
						$startTimeFrom = date ( 'H:i:s', strtotime ( $data ['search_time_start'] ) );
						$startTimeTo = date ( 'H:i:s', strtotime ( $data ['search_time_to'] ) );
						
						$startTimeFrom_o = '00:00:00';
						$startTimeTo_o = '23:59:59';
						
						$n_note_date_from_t = $startDate . " " . $startTimeFrom_o;
						$n_note_date_to_t = $endDate . " " . $startTimeTo_o;
						$n_note_date_from_time = $startTimeFrom;
						$n_note_date_to_time = $startTimeTo;
						
						 $wheredfac = " and (`notetime` BETWEEN '".$startTimeFrom."' AND
						 '".$startTimeTo."') ";
						
					
					} else {
						$startTimeFrom = '00:00:00';
						$startTimeTo = '23:59:59';
						
						$n_note_date_from = $startDate . " " . $startTimeFrom;
						$n_note_date_to = $endDate . " " . $startTimeTo;
					}
					
					// $wheredfac = " and ( `date_added` BETWEEN '".$startDate." ".$startTimeFrom."' AND '".$endDate." ".$startTimeTo."' ) ";
					
					}
			
					$sqln = "SELECT ifnull (total, 0) as total
					, start_t FROM `" . DB_PREFIX . "time` as t left outer join 
					(SELECT COUNT(*) AS total, left (time(date_added), 2) as time FROM `" . DB_PREFIX . "notes` WHERE status = '1' ".$wheredf." ".$wheredfac." ".$wheref." GROUP BY left (time(date_added), 2)) as n on (t.start_t = n.time) order by start_t";
				

				
						$queryn = $this->db->query($sqln);
						if ($queryn->num_rows) {
							//echo "<pre>";
							foreach($queryn->rows as $to){
								
							//	
							$notesgaphgraph['notes']['data'][]  = (int)$to['total'];
							$notesgaphgraph['categories'][] = (int)$to['start_t'];
							}
							//echo "</pre>";
						}else{
							$notesgaphgraph['notes']['data'][] = 0;
						}
						
			
						return $notesgaphgraph;
			
						
		
		
			
			
		}
	
	
}
?>