<?php
class Modelnotescase extends Model {

	public function getTotalnotessmain($data = array()) {
		
		//date_default_timezone_set($this->session->data['time_zone_1']);
		
		$sql ="SELECT COUNT(DISTINCT n.notes_id) AS total FROM `" . DB_PREFIX . "notes` n ";
		
		$sql .= "left JOIN " . DB_PREFIX . "forms f on f.notes_id=n.notes_id  ";
		$sql .= "left JOIN " . DB_PREFIX . "notes_by_task nb on nb.notes_id=n.notes_id  ";
		$sql .= "left JOIN " . DB_PREFIX . "notes_tags nt on nt.notes_id=n.notes_id  ";
		$sql .= "left JOIN " . DB_PREFIX . "notes_by_keyword nk on nk.notes_id=n.notes_id  ";
		 
		$sql .= ' where 1 = 1 ';
		
		$sql .= " and n.status = '1' ";
		
		
		if ($data['user_id'] != null && $data['user_id'] != "") {
			$sql .= " and n.user_id = '".$this->db->escape($data['user_id'])."'";
		}
		
		/*if($this->session->data['isPrivate'] == '1'){
			//$sql .= " and n.user_id = '".$this->session->data['username']."'";
			$useIds = $this->customer->getPrivateUsersByRole();
			//var_dump($useIds);
			//echo "<hr>";
			
			if($useIds != null && $useIds != ""){
				$sql .= " and n.user_id in ('".$useIds."')";
			}
		}*/
		
		if ($data['emp_tag_id'] != null && $data['emp_tag_id'] != "0") {
			$sql .= " and nt.tags_id = '".$this->db->escape($data['emp_tag_id'])."'";
		}
		
		if ($data['facilities_id'] != null && $data['facilities_id'] != "") {
			$sql .= " and n.facilities_id = '".$data['facilities_id']."'";
		}
		
		if ($data['tasktype'] != null && $data['tasktype'] != "") {
			$sql .= " and n.tasktype = '".$this->db->escape($data['tasktype'])."'";
		}
		
		if($data['review_notes'] == '1'){
			$sql .= " and n.review_notes = '".$data['review_notes']."' ";	
		}
		
		if($data['text_color'] == '1'){
			$sql .= " and n.text_color != '' ";	
		}		
		
		 if($data['task_search'] == 'all'){
			$query21 = $this->db->query("SELECT * FROM " . DB_PREFIX . "tasktype where status='1' ");
	
			$resultss = $query21->rows;
			
			$userIds2 = array();
			foreach($resultss as $result){
				if($result['task_id'] != null && $result['task_id'] != ""){
				$userIds2[] = $result['task_id']; 
				}
			}
			$userIds12 = array_unique($userIds2);
			
			$userIds21 = implode('\',\'',$userIds12); 
			
			if($userIds21 != null && $userIds21 != ""){
			$userIds21 = str_replace("all,","",$userIds21);
			
			$sql .= " and n.tasktype in ('". $userIds21."') ";
			
			}
		}
		 
		if ($data['highlighter'] != null && $data['highlighter'] != "") {
			
			if($data['highlighter'] == 'all'){
				$data3 = array();
				$this->load->model('setting/highlighter');
				$results = $this->model_setting_highlighter->gethighlighters($data3);
				$userIds = array();
				foreach($results as $result){
					if($result['highlighter_id'] != null && $result['highlighter_id'] != ""){
					$userIds[] = $result['highlighter_id'];
					}
				}
				$userIds1 = array_unique($userIds);
				
				$userIds2 = implode(",",$userIds1);
				
				if($userIds2 != null && $userIds2 != ""){
				$userIds2 = str_replace("all,","",$userIds2);
				$sql .= " and n.highlighter_id in (". $userIds2.") ";
				}
			}else{
				$sql .= " and n.highlighter_id = '".$data['highlighter']."'";
			}
		}
		
		if ($data['search_acitvenote_with_keyword'] == "1") {
			$query2 = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "keyword WHERE keyword_id = '" . (int)$data['activenote'] . "'");
			
			$keydata = $query2->row;
			
			$sql .= " and ( nk.keyword_file = '".$keydata['keyword_image']."'";
			
			$sql .= " or ( LOWER(n.notes_description) like '%".$this->db->escape(strtolower($data['keyword']))."%' or LOWER(f.form_description) LIKE '%".$this->db->escape(strtolower($data['keyword']))."%' or LOWER(nb.task_content) LIKE '%".$this->db->escape(strtolower($data['keyword']))."%' )) ";
			
		}else{
		
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

			if ($data['keyword'] != null && $data['keyword'] != "") {
				$sql .= " and ( LOWER(n.notes_description) like '%".$this->db->escape(strtolower($data['keyword']))."%' or LOWER(f.form_description) LIKE '%".$this->db->escape(strtolower($data['keyword']))."%' or LOWER(nb.task_content) LIKE '%".$this->db->escape(strtolower($data['keyword']))."%' ) ";
				//$sql .= " or LOWER(user_id) like '%".strtolower($data['keyword'])."%'";
				//$sql .= " or LOWER(emp_tag_id) like '%".strtolower($data['keyword'])."%') ";
				//$sql .= " or notes_description like '%".strtolower($data['keyword'])."%'";
				//$sql .= " or notes_description like '%".strtoupper($data['keyword'])."%'";
			}
		} 
		
		if($data['form_search']){
			if($data['form_search'] == 'IncidentForm'){
				$sql .= " and f.form_type = '1'";
			}
			if($data['form_search'] == 'ChecklistForm'){
				$sql .= " and f.form_type = '2'";
			}
			
			if($data['form_search'] == 'BedCheckForm'){
				$sql .= " and n.task_type = '1'";
			}
			
			if($data['form_search'] == 'MedicationForm'){
				$sql .= " and n.task_type = '2'";
			}
			
			if($data['form_search'] == 'TransportationForm'){
				$sql .= " and n.task_type = '3'";
			}
			
			
			if($data['form_search'] == 'Intake'){
				$sql .= " and n.is_tag != '0' and n.form_type = '2' ";
			}
			if($data['form_search'] == 'HealthForm'){
				$sql .= " and n.is_tag != '0' and n.form_type = '1'";
			}
			
			 if (is_numeric($data['form_search'])) {
				$sql .= " and f.custom_form_type = '".$data['form_search']."'";
			 }
			 
			  if($data['form_search'] == 'all'){
					$query21 = $this->db->query("SELECT * FROM " . DB_PREFIX . "forms_design where status='1' ");
			
					$resultss = $query21->rows;
					
					$userIds2 = array();
					foreach($resultss as $result){
						if($result['forms_id'] != null && $result['forms_id'] != ""){
						$userIds2[] = $result['forms_id'];
						}
					}
					$userIds12 = array_unique($userIds2);
					
					$userIds21 = implode('\',\'',$userIds12); 
					
					if($userIds21 != null && $userIds21 != ""){
					$userIds21 = str_replace("all,","",$userIds21);
					
					$sql .= " and f.custom_form_type in ('". $userIds21."') ";
					
					}
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
			$sql .= " and ( n.`date_added` BETWEEN '".$startDate." ".$startTimeFrom." ' AND '".$endDate." ".$startTimeTo."' or f.`date_added` BETWEEN '".$startDate." ".$startTimeFrom." ' AND '".$endDate." ".$startTimeTo."' ) ";
			
			
			
		}
		
		
		if($data['searchdate_app'] == '1'){
			if ($data['searchdate'] != null && $data['searchdate'] != "") {
				$date = str_replace('-', '/', $data['searchdate']);
				$res = explode("/", $date);
				$changedDate = $res[2]."-".$res[0]."-".$res[1];
			
				$startDate = $changedDate;/*date('Y-m-d', strtotime($data['searchdate']));*/
				/*$endDate = date('Y-m-d');*/
				$endDate = $changedDate;/*date('Y-m-d', strtotime($data['searchdate']));*/
				
				//$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
				$sql .= " and ( n.`date_added` BETWEEN '".$startDate." 00:00:00 ' AND '".$endDate." 23:59:59' or f.`date_added` BETWEEN '".$startDate." 00:00:00 ' AND '".$endDate." 23:59:59' ) ";
			}else{
				if($data['advance_searchapp'] != '1' && $data['advance_search'] != '1'){
				
				$timezone_name = $this->customer->isTimezone();
				date_default_timezone_set($timezone_name);	
				
				$startDate = date('Y-m-d');
				$endDate = date('Y-m-d');
				if($data['case_detail'] == '2'){
				//$sql .= " and `date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59' ";
				$sql .= " and ( n.`date_added` BETWEEN '".$startDate." 00:00:00 ' AND '".$endDate." 23:59:59' or f.`date_added` BETWEEN '".$startDate." 00:00:00 ' AND '".$endDate." 23:59:59' ) ";
				
				}
				}
			}
		}
		
		if($data['searchdate_app'] != "1"){
			if ($data['searchdate'] != null && $data['searchdate'] != "") {
			
				$startDate = date('Y-m-d', strtotime($data['searchdate']));
				/*$endDate = date('Y-m-d');*/
				$endDate = date('Y-m-d', strtotime($data['searchdate']));
				
				//$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
				$sql .= " and ( n.`date_added` BETWEEN '".$startDate." 00:00:00 ' AND '".$endDate." 23:59:59' or f.`date_added` BETWEEN '".$startDate." 00:00:00 ' AND '".$endDate." 23:59:59' ) ";
			}
		}
		
		
		if($data['sync_data'] == "2"){
			
			date_default_timezone_set($data['facilities_timezone']);
			
			if ($data['searchdate'] != null && $data['searchdate'] != "") {
				//$startDate = date('Y-m-d', strtotime($data['searchdate']));
				//$endDate = date('Y-m-d', strtotime($data['searchdate']));
				$endDate = date('Y-m-d', strtotime('now'));
				
				$time = date('H:i:s');
				
				if($data['notetime'] != null && $data['notetime'] !=""){
					$notetime = $data['notetime'];
				}else{
					$notetime = '00:00:00';
				}
				
				
				$date_end = $endDate .' '.$time;
				$date_start = $endDate .' '.$notetime;
				$sql .= " and ( n.`update_date` BETWEEN '".$date_start."' AND '".$date_end."') ";
			}
		}
		
		
		
		
		
		//var_dump($sql);
		//echo "<hr>";
		//echo $sql;
		
		//echo $sql; 
		
		
		
      	$query = $this->db->query($sql);
		
		//var_dump($query->row['total']);
		
		return $query->row['total'];
	}

	
	public function getnotessmain($data = array()) {
		//var_dump($data); 
		//date_default_timezone_set($this->session->data['time_zone_1']);
		
		$sql = "select DISTINCT n.* from `" . DB_PREFIX . "notes` n ";
		$sql .= "left JOIN " . DB_PREFIX . "forms f on f.notes_id=n.notes_id  "; 
		$sql .= "left JOIN " . DB_PREFIX . "notes_by_task nb on nb.notes_id=n.notes_id  ";
		$sql .= "left JOIN " . DB_PREFIX . "notes_tags nt on nt.notes_id=n.notes_id  ";
		$sql .= "left JOIN " . DB_PREFIX . "notes_by_keyword nk on nk.notes_id=n.notes_id  ";
		
		$sql .= ' where 1 = 1 ';
		
		$sql .= " and n.status = '1' ";
		
		
		if ($data['notes_id'] != null && $data['notes_id'] != "") {
			$sql .= " and n.notes_id > '".$data['notes_id']."'";
		}
		
		if ($data['user_id'] != null && $data['user_id'] != "") {
			$sql .= " and n.user_id = '".$this->db->escape($data['user_id'])."'";
		}
		
		if ($data['customer_key'] != null && $data['customer_key'] != "") {
			$sql .= " and n.unique_id = '".$this->db->escape($data['customer_key'])."'";
		}
		
		if ($data['emp_tag_id'] != null && $data['emp_tag_id'] != "0") {
			$sql .= " and nt.tags_id = '".$this->db->escape($data['emp_tag_id'])."'";
		}
		
		if ($data['facilities_id'] != null && $data['facilities_id'] != "") {
			$sql .= " and n.facilities_id = '".$data['facilities_id']."'";
		}
		if ($data['tasktype'] != null && $data['tasktype'] != "") {
			$sql .= " and n.tasktype = '".$this->db->escape($data['tasktype'])."'";
		}
		
		 if($data['task_search'] == 'all'){
			$query21 = $this->db->query("SELECT * FROM " . DB_PREFIX . "tasktype where status='1' ");
	
			$resultss = $query21->rows;
			
			$userIds2 = array();
			foreach($resultss as $result){
				if($result['task_id'] != null && $result['task_id'] != ""){
				$userIds2[] = $result['task_id']; 
				}
			}
			$userIds12 = array_unique($userIds2);
			
			$userIds21 = implode('\',\'',$userIds12); 
			
			if($userIds21 != null && $userIds21 != ""){
			$userIds21 = str_replace("all,","",$userIds21);
			
			$sql .= " and n.tasktype in ('". $userIds21."') ";
			
			}
		}
		
		if ($data['tagstatus_id'] != null && $data['tagstatus_id'] != "") {
			$sql .= " and n.tagstatus_id != '0'";
		}
		
		/*if ($data['is_bedchk'] != null && $data['is_bedchk'] != "") {
			$sql .= " and n.tasktype = '11'";
		}*/
		
		if ($data['notesid'] != null && $data['notesid'] != "") {
			$sql .= " and n.parent_id = '".$data['notesid']."'";
		}
		
		/*//if($this->session->data['isPrivate'] == '1'){
			//$sql .= " and n.user_id = '".$this->session->data['username']."'";
			$useIds = $this->customer->getPrivateUsersByRole();
			//var_dump($useIds);
			//echo "<hr>";
			
			if($useIds != null && $useIds != ""){
				$sql .= " and n.user_id in ('".$useIds."')";
			}
		//}*/
		
		
		if ($data['highlighter'] != null && $data['highlighter'] != "") {
			
			if($data['highlighter'] == 'all'){
				$data3 = array();
				$this->load->model('setting/highlighter');
				$results = $this->model_setting_highlighter->gethighlighters($data3);
				$userIds = array();
				foreach($results as $result){
					if($result['highlighter_id'] != null && $result['highlighter_id'] != ""){
					$userIds[] = $result['highlighter_id'];
					}
				}
				$userIds1 = array_unique($userIds);
				
				$userIds2 = implode(",",$userIds1);
				
				if($userIds2 != null && $userIds2 != ""){
				$userIds2 = str_replace("all,","",$userIds2);
				$sql .= " and n.highlighter_id in (". $userIds2.") ";
				}
			}else{
				$sql .= " and n.highlighter_id = '".$data['highlighter']."'";
			}
		}
		
		
		if ($data['search_acitvenote_with_keyword'] == "1") {
			$query2 = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "keyword WHERE keyword_id = '" . (int)$data['activenote'] . "'");
			
			$keydata = $query2->row;
			
			$sql .= " and ( nk.keyword_file = '".$keydata['keyword_image']."'";
			
			$sql .= " or ( LOWER(n.notes_description) like '%".$this->db->escape(strtolower($data['keyword']))."%' or LOWER(f.form_description) LIKE '%".$this->db->escape(strtolower($data['keyword']))."%' or LOWER(nb.task_content) LIKE '%".$this->db->escape(strtolower($data['keyword']))."%' )) ";
			
		}else{
		
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
				
			if ($data['keyword'] != null && $data['keyword'] != "") {
				$sql .= " and ( LOWER(n.notes_description) like '%".$this->db->escape(strtolower($data['keyword']))."%' or LOWER(f.form_description) LIKE '%".$this->db->escape(strtolower($data['keyword']))."%' or LOWER(nb.task_content) LIKE '%".$this->db->escape(strtolower($data['keyword']))."%' ) ";
				//$sql .= " or LOWER(user_id) like '%".strtolower($data['keyword'])."%'";
				//$sql .= " or LOWER(emp_tag_id) like '%".strtolower($data['keyword'])."%') ";
				//$sql .= " or notes_description like '%".strtolower($data['keyword'])."%'";
				//$sql .= " or notes_description like '%".strtoupper($data['keyword'])."%'";
			} 
		}
			
			
		if($data['review_notes'] == '1'){
		$sql .= " and n.review_notes = '".$data['review_notes']."' ";	
		}
		
		if($data['text_color'] == '1'){
			$sql .= " and n.text_color != '' ";	
		}
		
		if($data['form_search']){
			if($data['form_search'] == 'IncidentForm'){
				$sql .= " and f.form_type = '1'";
			}
			if($data['form_search'] == 'ChecklistForm'){
				$sql .= " and f.form_type = '2'";
			}
			
			if($data['form_search'] == 'BedCheckForm'){
				$sql .= " and n.task_type = '1'";
			}
			
			if($data['form_search'] == 'MedicationForm'){
				$sql .= " and n.task_type = '2'";
			}
			
			if($data['form_search'] == 'TransportationForm'){
				$sql .= " and n.task_type = '3'";
			}
			if($data['form_search'] == 'Intake'){
				$sql .= " and n.is_tag != '0' and n.form_type = '2' ";
			}
			if($data['form_search'] == 'HealthForm'){
				$sql .= " and n.is_tag != '0' and n.form_type = '1'";
			}
			
			 if (is_numeric($data['form_search'])) {
				$sql .= " and f.custom_form_type = '".$data['form_search']."'";
			 }
			 
			  if($data['form_search'] == 'all'){
					$query21 = $this->db->query("SELECT * FROM " . DB_PREFIX . "forms_design where status='1' ");
			
					$resultss = $query21->rows;
					
					$userIds2 = array();
					foreach($resultss as $result){
						if($result['forms_id'] != null && $result['forms_id'] != ""){
						$userIds2[] = $result['forms_id'];
						}
					}
					$userIds12 = array_unique($userIds2);
					
					$userIds21 = implode('\',\'',$userIds12); 
					
					if($userIds21 != null && $userIds21 != ""){
					$userIds21 = str_replace("all,","",$userIds21);
					
					$sql .= " and f.custom_form_type in ('". $userIds21."') ";
					
					}
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
			$sql .= " and ( n.`date_added` BETWEEN '".$startDate." ".$startTimeFrom."' AND '".$endDate." ".$startTimeTo."' or f.`date_added` BETWEEN '".$startDate." ".$startTimeFrom."' AND '".$endDate." ".$startTimeTo."' ) ";
		}
		
		
		if($data['searchdate_app'] == '1'){
			if ($data['searchdate'] != null && $data['searchdate'] != "") {
				$date = str_replace('-', '/', $data['searchdate']);
				$res = explode("/", $date);
				$changedDate = $res[2]."-".$res[0]."-".$res[1];
			
				$startDate = $changedDate;/*date('Y-m-d', strtotime($data['searchdate']));*/
				/*$endDate = date('Y-m-d');*/
				$endDate = $changedDate;/*date('Y-m-d', strtotime($data['searchdate']));*/
				
				//$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
				$sql .= " and ( n.`date_added` BETWEEN '".$startDate." 00:00:00 ' AND '".$endDate." 23:59:59' or f.`date_added` BETWEEN '".$startDate." 00:00:00 ' AND '".$endDate." 23:59:59' ) ";
			}else{
				if($data['advance_searchapp'] != '1' && $data['advance_search'] != '1'){
				$timezone_name = $this->customer->isTimezone();
				date_default_timezone_set($timezone_name);	
				$startDate = date('Y-m-d');
				$endDate = date('Y-m-d');
				
				
				if($data['case_detail'] == '2'){
				//$sql .= " and `date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59' ";
				$sql .= " and ( n.`date_added` BETWEEN '".$startDate." 00:00:00 ' AND '".$endDate." 23:59:59' or f.`date_added` BETWEEN '".$startDate." 00:00:00 ' AND '".$endDate." 23:59:59' ) ";
				
				}
				}
			}
		}
		
		if($data['searchdate_app'] != "1"){
			if ($data['searchdate'] != null && $data['searchdate'] != "") {
			
				$startDate = date('Y-m-d', strtotime($data['searchdate']));
				/*$endDate = date('Y-m-d');*/
				$endDate = date('Y-m-d', strtotime($data['searchdate']));
				
				//$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
				$sql .= " and ( n.`date_added` BETWEEN '".$startDate." 00:00:00 ' AND '".$endDate." 23:59:59' or f.`date_added` BETWEEN '".$startDate." 00:00:00 ' AND '".$endDate." 23:59:59' ) ";
			}
		}
		
		//var_dump($data['sync_data']);
		if($data['sync_data'] == "2"){
			
			date_default_timezone_set($data['facilities_timezone']);
			
			if ($data['searchdate'] != null && $data['searchdate'] != "") {
				//$startDate = date('Y-m-d', strtotime($data['searchdate']));
				//$endDate = date('Y-m-d', strtotime($data['searchdate']));
				$endDate = date('Y-m-d', strtotime('now'));
				
				$time = date('H:i:s');
				
				if($data['notetime'] != null && $data['notetime'] !=""){
					$notetime = $data['notetime'];
				}else{
					$notetime = '00:00:00';
				}
				
				
				$date_end = $endDate .' '.$time;
				$date_start = $endDate .' '.$notetime;
				$sql .= " and ( n.`update_date` BETWEEN '".$date_start."' AND '".$date_end."') ";
			}
		}
		
		
		if($data['group'] == '1'){
			$sql .= " GROUP BY n.update_date ";
			//$sql .= " ORDER BY n.date_added DESC";
		}
		
		
		if($data['sync_data'] != "2"){
		if($data['searchdate_app'] == "1"){
			if ($data['advance_searchapp'] == "1") {
				$sql .= " ORDER BY date_added ASC";
			}else{
				if($data['group'] != '1'){
				$sql .= " ORDER BY notetime ASC";
				}	 
			}
		}else{
			if ($data['advance_search'] == "1") {
				
				if ($data['advance_date_desc'] == "1") {
					$sql .= " ORDER BY date_added DESC";
				}else{
					$sql .= " ORDER BY date_added ASC";
				}
				
			}else{
				$sql .= " ORDER BY notetime ASC";
			}
		}
		}else{
			
			$sql .= " ORDER BY update_date ASC";
		}
		
		if($data['current_date'] != '1'){
			if($data['advance_searchapp'] == '1'){
				if (isset($data['start']) || isset($data['limit'])) {
					if ($data['start'] < 0) {
						$data['start'] = 0;
					}			
					
					if ($data['limit'] < 1) {
						$data['limit'] = 20;
					}	
					
					$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
				}
			}
			
			if($data['advance_searchapp'] != '1'){
				if (isset($data['start']) || isset($data['limit'])) {
					if ($data['start'] < 0) {
						$data['start'] = 0;
					}			
					
					if ($data['limit'] < 1) {
						$data['limit'] = 20;
					}	
					
					$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
				}
			}
		}
		
		/*$sql .= " LIMIT 0, 5";
		
		
		*/
		//echo "<hr>";
		//echo $sql;   
		//echo "<hr>";
		
		$notes_data = array();
		
		$query = $this->db->query($sql);
		
		/*
		foreach ($query->rows as $result) {
			$notes_data[$result['notes_id']] = $this->getNote($result['notes_id']);
		}
	
		return $notes_data;
		*/
		return $query->rows;
	}
	
	
	public function getTotalnotess($data = array()) {
		
		//date_default_timezone_set($this->session->data['time_zone_1']);
		
		$sql ="SELECT COUNT(DISTINCT n.notes_id) AS total FROM `" . DB_PREFIX . "notes` n ";
		
		$sql .= "left JOIN " . DB_PREFIX . "forms f on f.notes_id=n.notes_id  ";
		$sql .= "left JOIN " . DB_PREFIX . "notes_by_task nb on nb.notes_id=n.notes_id  ";
		$sql .= "left JOIN " . DB_PREFIX . "notes_tags nt on nt.notes_id=n.notes_id  ";
		$sql .= "left JOIN " . DB_PREFIX . "notes_by_keyword nk on nk.notes_id=n.notes_id  ";
		 
		$sql .= ' where 1 = 1 ';
		
		$sql .= " and n.status = '1' ";
		
		
		if ($data['user_id'] != null && $data['user_id'] != "") {
			$sql .= " and n.user_id = '".$this->db->escape($data['user_id'])."'";
		}
		
		/*if($this->session->data['isPrivate'] == '1'){
			//$sql .= " and n.user_id = '".$this->session->data['username']."'";
			$useIds = $this->customer->getPrivateUsersByRole();
			//var_dump($useIds);
			//echo "<hr>";
			
			if($useIds != null && $useIds != ""){
				$sql .= " and n.user_id in ('".$useIds."')";
			}
		}*/
		
		if ($data['customer_key'] != null && $data['customer_key'] != "") {
			$sql .= " and n.unique_id = '".$this->db->escape($data['customer_key'])."'";
		}
		
		if ($data['emp_tag_id'] != null && $data['emp_tag_id'] != "0") {
			$sql .= " and nt.tags_id = '".$this->db->escape($data['emp_tag_id'])."'";
		}
		
		if ($data['facilities_id'] != null && $data['facilities_id'] != "") {
			$sql .= " and n.facilities_id = '".$data['facilities_id']."'";
		}
		
		if ($data['tasktype'] != null && $data['tasktype'] != "") {
			$sql .= " and n.tasktype = '".$this->db->escape($data['tasktype'])."'";
		}
		
		if($data['review_notes'] == '1'){
			$sql .= " and n.review_notes = '".$data['review_notes']."' ";	
		}
		
		if($data['text_color'] == '1'){
			$sql .= " and n.text_color != '' ";	
		}		
		
		 if($data['task_search'] == 'all'){
			$query21 = $this->db->query("SELECT * FROM " . DB_PREFIX . "tasktype where status='1' ");
	
			$resultss = $query21->rows;
			
			$userIds2 = array();
			foreach($resultss as $result){
				if($result['task_id'] != null && $result['task_id'] != ""){
				$userIds2[] = $result['task_id']; 
				}
			}
			$userIds12 = array_unique($userIds2);
			
			$userIds21 = implode('\',\'',$userIds12); 
			
			if($userIds21 != null && $userIds21 != ""){
			$userIds21 = str_replace("all,","",$userIds21);
			
			$sql .= " and n.tasktype in ('". $userIds21."') ";
			
			}
		}
		 
		if ($data['highlighter'] != null && $data['highlighter'] != "") {
			
			if($data['highlighter'] == 'all'){
				$data3 = array();
				$this->load->model('setting/highlighter');
				$results = $this->model_setting_highlighter->gethighlighters($data3);
				$userIds = array();
				foreach($results as $result){
					if($result['highlighter_id'] != null && $result['highlighter_id'] != ""){
					$userIds[] = $result['highlighter_id'];
					}
				}
				$userIds1 = array_unique($userIds);
				
				$userIds2 = implode(",",$userIds1);
				
				if($userIds2 != null && $userIds2 != ""){
				$userIds2 = str_replace("all,","",$userIds2);
				$sql .= " and n.highlighter_id in (". $userIds2.") ";
				}
			}else{
				$sql .= " and n.highlighter_id = '".$data['highlighter']."'";
			}
		}
		
		if ($data['search_acitvenote_with_keyword'] == "1") {
			$query2 = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "keyword WHERE keyword_id = '" . (int)$data['activenote'] . "'");
			
			$keydata = $query2->row;
			
			$sql .= " and ( nk.keyword_file = '".$keydata['keyword_image']."'";
			
			$sql .= " or ( LOWER(n.notes_description) like '%".$this->db->escape(strtolower($data['keyword']))."%' or LOWER(f.form_description) LIKE '%".$this->db->escape(strtolower($data['keyword']))."%' or LOWER(nb.task_content) LIKE '%".$this->db->escape(strtolower($data['keyword']))."%' )) ";
			
		}else{
		
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

			if ($data['keyword'] != null && $data['keyword'] != "") {
				$sql .= " and ( LOWER(n.notes_description) like '%".$this->db->escape(strtolower($data['keyword']))."%' or LOWER(f.form_description) LIKE '%".$this->db->escape(strtolower($data['keyword']))."%' or LOWER(nb.task_content) LIKE '%".$this->db->escape(strtolower($data['keyword']))."%' ) ";
				//$sql .= " or LOWER(user_id) like '%".strtolower($data['keyword'])."%'";
				//$sql .= " or LOWER(emp_tag_id) like '%".strtolower($data['keyword'])."%') ";
				//$sql .= " or notes_description like '%".strtolower($data['keyword'])."%'";
				//$sql .= " or notes_description like '%".strtoupper($data['keyword'])."%'";
			}
		} 
		
		if($data['form_search']){
			if($data['form_search'] == 'IncidentForm'){
				$sql .= " and f.form_type = '1'";
			}
			if($data['form_search'] == 'ChecklistForm'){
				$sql .= " and f.form_type = '2'";
			}
			
			if($data['form_search'] == 'BedCheckForm'){
				$sql .= " and n.task_type = '1'";
			}
			
			if($data['form_search'] == 'MedicationForm'){
				$sql .= " and n.task_type = '2'";
			}
			
			if($data['form_search'] == 'TransportationForm'){
				$sql .= " and n.task_type = '3'";
			}
			
			
			if($data['form_search'] == 'Intake'){
				$sql .= " and n.is_tag != '0' and n.form_type = '2' ";
			}
			if($data['form_search'] == 'HealthForm'){
				$sql .= " and n.is_tag != '0' and n.form_type = '1'";
			}
			
			 if (is_numeric($data['form_search'])) {
				$sql .= " and f.custom_form_type = '".$data['form_search']."'";
			 }
			 
			  if($data['form_search'] == 'all'){
					$query21 = $this->db->query("SELECT * FROM " . DB_PREFIX . "forms_design where status='1' ");
			
					$resultss = $query21->rows;
					
					$userIds2 = array();
					foreach($resultss as $result){
						if($result['forms_id'] != null && $result['forms_id'] != ""){
						$userIds2[] = $result['forms_id'];
						}
					}
					$userIds12 = array_unique($userIds2);
					
					$userIds21 = implode('\',\'',$userIds12); 
					
					if($userIds21 != null && $userIds21 != ""){
					$userIds21 = str_replace("all,","",$userIds21);
					
					$sql .= " and f.custom_form_type in ('". $userIds21."') ";
					
					}
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
			$sql .= " and ( n.`date_added` BETWEEN '".$startDate." ".$startTimeFrom." ' AND '".$endDate." ".$startTimeTo."' or f.`date_added` BETWEEN '".$startDate." ".$startTimeFrom." ' AND '".$endDate." ".$startTimeTo."' ) ";
			
			
			
		}
		
		
		if($data['searchdate_app'] == '1'){
			if ($data['searchdate'] != null && $data['searchdate'] != "") {
				$date = str_replace('-', '/', $data['searchdate']);
				$res = explode("/", $date);
				$changedDate = $res[2]."-".$res[0]."-".$res[1];
			
				$startDate = $changedDate;/*date('Y-m-d', strtotime($data['searchdate']));*/
				/*$endDate = date('Y-m-d');*/
				$endDate = $changedDate;/*date('Y-m-d', strtotime($data['searchdate']));*/
				
				//$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
				$sql .= " and ( n.`date_added` BETWEEN '".$startDate." 00:00:00 ' AND '".$endDate." 23:59:59' or f.`date_added` BETWEEN '".$startDate." 00:00:00 ' AND '".$endDate." 23:59:59' ) ";
			}else{
				if($data['advance_searchapp'] != '1' && $data['advance_search'] != '1'){
				
				$timezone_name = $this->customer->isTimezone();
				date_default_timezone_set($timezone_name);	
				
				$startDate = date('Y-m-d');
				$endDate = date('Y-m-d');
				if($data['case_detail'] == '2'){
				//$sql .= " and `date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59' ";
				$sql .= " and ( n.`date_added` BETWEEN '".$startDate." 00:00:00 ' AND '".$endDate." 23:59:59' or f.`date_added` BETWEEN '".$startDate." 00:00:00 ' AND '".$endDate." 23:59:59' ) ";
				
				}
				}
			}
		}
		
		if($data['searchdate_app'] != "1"){
			if ($data['searchdate'] != null && $data['searchdate'] != "") {
			
				$startDate = date('Y-m-d', strtotime($data['searchdate']));
				/*$endDate = date('Y-m-d');*/
				$endDate = date('Y-m-d', strtotime($data['searchdate']));
				
				//$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
				$sql .= " and ( n.`date_added` BETWEEN '".$startDate." 00:00:00 ' AND '".$endDate." 23:59:59' or f.`date_added` BETWEEN '".$startDate." 00:00:00 ' AND '".$endDate." 23:59:59' ) ";
			}
		}
		
		
		if($data['sync_data'] == "2"){
			
			date_default_timezone_set($data['facilities_timezone']);
			
			if ($data['searchdate'] != null && $data['searchdate'] != "") {
				//$startDate = date('Y-m-d', strtotime($data['searchdate']));
				//$endDate = date('Y-m-d', strtotime($data['searchdate']));
				$endDate = date('Y-m-d', strtotime('now'));
				
				$time = date('H:i:s');
				
				if($data['notetime'] != null && $data['notetime'] !=""){
					$notetime = $data['notetime'];
				}else{
					$notetime = '00:00:00';
				}
				
				
				$date_end = $endDate .' '.$time;
				$date_start = $endDate .' '.$notetime;
				$sql .= " and ( n.`update_date` BETWEEN '".$date_start."' AND '".$date_end."') ";
			}
		}
		
		
		
		
		
		//var_dump($sql);
		//echo "<hr>";
		//echo $sql;
		
		//echo $sql; 
		
		
		if(IS_WAREHOUSE == '1'){
			$query = $this->newdb->query($sql);
		}else{
			$query = $this->db->query($sql);
		}
      	
		
		//var_dump($query->row['total']);
		
		return $query->row['total'];
	}

	
	public function getnotess($data = array()) {
		//var_dump($data); 
		//date_default_timezone_set($this->session->data['time_zone_1']);
		
		$sql = "select DISTINCT n.* from `" . DB_PREFIX . "notes` n ";
		$sql .= "left JOIN " . DB_PREFIX . "forms f on f.notes_id=n.notes_id  "; 
		$sql .= "left JOIN " . DB_PREFIX . "notes_by_task nb on nb.notes_id=n.notes_id  ";
		$sql .= "left JOIN " . DB_PREFIX . "notes_tags nt on nt.notes_id=n.notes_id  ";
		$sql .= "left JOIN " . DB_PREFIX . "notes_by_keyword nk on nk.notes_id=n.notes_id  ";
		
		$sql .= ' where 1 = 1 ';
		
		$sql .= " and n.status = '1' ";
		
		
		if ($data['notes_id'] != null && $data['notes_id'] != "") {
			$sql .= " and n.notes_id > '".$data['notes_id']."'";
		}
		
		if ($data['user_id'] != null && $data['user_id'] != "") {
			$sql .= " and n.user_id = '".$this->db->escape($data['user_id'])."'";
		}
		
		if ($data['emp_tag_id'] != null && $data['emp_tag_id'] != "0") {
			$sql .= " and nt.tags_id = '".$this->db->escape($data['emp_tag_id'])."'";
		}
		
		if ($data['facilities_id'] != null && $data['facilities_id'] != "") {
			$sql .= " and n.facilities_id = '".$data['facilities_id']."'";
		}
		if ($data['tasktype'] != null && $data['tasktype'] != "") {
			$sql .= " and n.tasktype = '".$this->db->escape($data['tasktype'])."'";
		}
		
		 if($data['task_search'] == 'all'){
			$query21 = $this->db->query("SELECT * FROM " . DB_PREFIX . "tasktype where status='1' ");
	
			$resultss = $query21->rows;
			
			$userIds2 = array();
			foreach($resultss as $result){
				if($result['task_id'] != null && $result['task_id'] != ""){
				$userIds2[] = $result['task_id']; 
				}
			}
			$userIds12 = array_unique($userIds2);
			
			$userIds21 = implode('\',\'',$userIds12); 
			
			if($userIds21 != null && $userIds21 != ""){
			$userIds21 = str_replace("all,","",$userIds21);
			
			$sql .= " and n.tasktype in ('". $userIds21."') ";
			
			}
		}
		
		if ($data['tagstatus_id'] != null && $data['tagstatus_id'] != "") {
			$sql .= " and n.tagstatus_id != '0'";
		}
		
		/*if ($data['is_bedchk'] != null && $data['is_bedchk'] != "") {
			$sql .= " and n.tasktype = '11'";
		}*/
		
		if ($data['notesid'] != null && $data['notesid'] != "") {
			$sql .= " and n.parent_id = '".$data['notesid']."'";
		}
		
		/*//if($this->session->data['isPrivate'] == '1'){
			//$sql .= " and n.user_id = '".$this->session->data['username']."'";
			$useIds = $this->customer->getPrivateUsersByRole();
			//var_dump($useIds);
			//echo "<hr>";
			
			if($useIds != null && $useIds != ""){
				$sql .= " and n.user_id in ('".$useIds."')";
			}
		//}*/
		
		
		if ($data['highlighter'] != null && $data['highlighter'] != "") {
			
			if($data['highlighter'] == 'all'){
				$data3 = array();
				$this->load->model('setting/highlighter');
				$results = $this->model_setting_highlighter->gethighlighters($data3);
				$userIds = array();
				foreach($results as $result){
					if($result['highlighter_id'] != null && $result['highlighter_id'] != ""){
					$userIds[] = $result['highlighter_id'];
					}
				}
				$userIds1 = array_unique($userIds);
				
				$userIds2 = implode(",",$userIds1);
				
				if($userIds2 != null && $userIds2 != ""){
				$userIds2 = str_replace("all,","",$userIds2);
				$sql .= " and n.highlighter_id in (". $userIds2.") ";
				}
			}else{
				$sql .= " and n.highlighter_id = '".$data['highlighter']."'";
			}
		}
		
		
		if ($data['search_acitvenote_with_keyword'] == "1") {
			$query2 = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "keyword WHERE keyword_id = '" . (int)$data['activenote'] . "'");
			
			$keydata = $query2->row;
			
			$sql .= " and ( nk.keyword_file = '".$keydata['keyword_image']."'";
			
			$sql .= " or ( LOWER(n.notes_description) like '%".$this->db->escape(strtolower($data['keyword']))."%' or LOWER(f.form_description) LIKE '%".$this->db->escape(strtolower($data['keyword']))."%' or LOWER(nb.task_content) LIKE '%".$this->db->escape(strtolower($data['keyword']))."%' )) ";
			
		}else{
		
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
				
			if ($data['keyword'] != null && $data['keyword'] != "") {
				$sql .= " and ( LOWER(n.notes_description) like '%".$this->db->escape(strtolower($data['keyword']))."%' or LOWER(f.form_description) LIKE '%".$this->db->escape(strtolower($data['keyword']))."%' or LOWER(nb.task_content) LIKE '%".$this->db->escape(strtolower($data['keyword']))."%' ) ";
				//$sql .= " or LOWER(user_id) like '%".strtolower($data['keyword'])."%'";
				//$sql .= " or LOWER(emp_tag_id) like '%".strtolower($data['keyword'])."%') ";
				//$sql .= " or notes_description like '%".strtolower($data['keyword'])."%'";
				//$sql .= " or notes_description like '%".strtoupper($data['keyword'])."%'";
			} 
		}
			
			
		if($data['review_notes'] == '1'){
		$sql .= " and n.review_notes = '".$data['review_notes']."' ";	
		}
		
		if($data['text_color'] == '1'){
			$sql .= " and n.text_color != '' ";	
		}
		
		if($data['form_search']){
			if($data['form_search'] == 'IncidentForm'){
				$sql .= " and f.form_type = '1'";
			}
			if($data['form_search'] == 'ChecklistForm'){
				$sql .= " and f.form_type = '2'";
			}
			
			if($data['form_search'] == 'BedCheckForm'){
				$sql .= " and n.task_type = '1'";
			}
			
			if($data['form_search'] == 'MedicationForm'){
				$sql .= " and n.task_type = '2'";
			}
			
			if($data['form_search'] == 'TransportationForm'){
				$sql .= " and n.task_type = '3'";
			}
			if($data['form_search'] == 'Intake'){
				$sql .= " and n.is_tag != '0' and n.form_type = '2' ";
			}
			if($data['form_search'] == 'HealthForm'){
				$sql .= " and n.is_tag != '0' and n.form_type = '1'";
			}
			
			 if (is_numeric($data['form_search'])) {
				$sql .= " and f.custom_form_type = '".$data['form_search']."'";
			 }
			 
			  if($data['form_search'] == 'all'){
					$query21 = $this->db->query("SELECT * FROM " . DB_PREFIX . "forms_design where status='1' ");
			
					$resultss = $query21->rows;
					
					$userIds2 = array();
					foreach($resultss as $result){
						if($result['forms_id'] != null && $result['forms_id'] != ""){
						$userIds2[] = $result['forms_id'];
						}
					}
					$userIds12 = array_unique($userIds2);
					
					$userIds21 = implode('\',\'',$userIds12); 
					
					if($userIds21 != null && $userIds21 != ""){
					$userIds21 = str_replace("all,","",$userIds21);
					
					$sql .= " and f.custom_form_type in ('". $userIds21."') ";
					
					}
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
			$sql .= " and ( n.`date_added` BETWEEN '".$startDate." ".$startTimeFrom."' AND '".$endDate." ".$startTimeTo."' or f.`date_added` BETWEEN '".$startDate." ".$startTimeFrom."' AND '".$endDate." ".$startTimeTo."' ) ";
		}
		
		
		if($data['searchdate_app'] == '1'){
			if ($data['searchdate'] != null && $data['searchdate'] != "") {
				$date = str_replace('-', '/', $data['searchdate']);
				$res = explode("/", $date);
				$changedDate = $res[2]."-".$res[0]."-".$res[1];
			
				$startDate = $changedDate;/*date('Y-m-d', strtotime($data['searchdate']));*/
				/*$endDate = date('Y-m-d');*/
				$endDate = $changedDate;/*date('Y-m-d', strtotime($data['searchdate']));*/
				
				//$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
				$sql .= " and ( n.`date_added` BETWEEN '".$startDate." 00:00:00 ' AND '".$endDate." 23:59:59' or f.`date_added` BETWEEN '".$startDate." 00:00:00 ' AND '".$endDate." 23:59:59' ) ";
			}else{
				if($data['advance_searchapp'] != '1' && $data['advance_search'] != '1'){
				$timezone_name = $this->customer->isTimezone();
				date_default_timezone_set($timezone_name);	
				$startDate = date('Y-m-d');
				$endDate = date('Y-m-d');
				
				
				if($data['case_detail'] == '2'){
				//$sql .= " and `date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59' ";
				$sql .= " and ( n.`date_added` BETWEEN '".$startDate." 00:00:00 ' AND '".$endDate." 23:59:59' or f.`date_added` BETWEEN '".$startDate." 00:00:00 ' AND '".$endDate." 23:59:59' ) ";
				
				}
				}
			}
		}
		
		if($data['searchdate_app'] != "1"){
			if ($data['searchdate'] != null && $data['searchdate'] != "") {
			
				$startDate = date('Y-m-d', strtotime($data['searchdate']));
				/*$endDate = date('Y-m-d');*/
				$endDate = date('Y-m-d', strtotime($data['searchdate']));
				
				//$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
				$sql .= " and ( n.`date_added` BETWEEN '".$startDate." 00:00:00 ' AND '".$endDate." 23:59:59' or f.`date_added` BETWEEN '".$startDate." 00:00:00 ' AND '".$endDate." 23:59:59' ) ";
			}
		}
		
		//var_dump($data['sync_data']);
		if($data['sync_data'] == "2"){
			
			date_default_timezone_set($data['facilities_timezone']);
			
			if ($data['searchdate'] != null && $data['searchdate'] != "") {
				//$startDate = date('Y-m-d', strtotime($data['searchdate']));
				//$endDate = date('Y-m-d', strtotime($data['searchdate']));
				$endDate = date('Y-m-d', strtotime('now'));
				
				$time = date('H:i:s');
				
				if($data['notetime'] != null && $data['notetime'] !=""){
					$notetime = $data['notetime'];
				}else{
					$notetime = '00:00:00';
				}
				
				
				$date_end = $endDate .' '.$time;
				$date_start = $endDate .' '.$notetime;
				$sql .= " and ( n.`update_date` BETWEEN '".$date_start."' AND '".$date_end."') ";
			}
		}
		
		
		if($data['group'] == '1'){
			$sql .= " GROUP BY n.update_date ";
			//$sql .= " ORDER BY n.date_added DESC";
		}
		
		
		if($data['sync_data'] != "2"){
		if($data['searchdate_app'] == "1"){
			if ($data['advance_searchapp'] == "1") {
				$sql .= " ORDER BY date_added ASC";
			}else{
				if($data['group'] != '1'){
				$sql .= " ORDER BY notetime ASC";
				}	 
			}
		}else{
			if ($data['advance_search'] == "1") {
				
				if ($data['advance_date_desc'] == "1") {
					$sql .= " ORDER BY date_added DESC";
				}else{
					$sql .= " ORDER BY date_added ASC";
				}
				
			}else{
				$sql .= " ORDER BY notetime ASC";
			}
		}
		}else{
			
			$sql .= " ORDER BY update_date ASC";
		}
		
		if($data['current_date'] != '1'){
			if($data['advance_searchapp'] == '1'){
				if (isset($data['start']) || isset($data['limit'])) {
					if ($data['start'] < 0) {
						$data['start'] = 0;
					}			
					
					if ($data['limit'] < 1) {
						$data['limit'] = 20;
					}	
					
					$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
				}
			}
			
			if($data['advance_searchapp'] != '1'){
				if (isset($data['start']) || isset($data['limit'])) {
					if ($data['start'] < 0) {
						$data['start'] = 0;
					}			
					
					if ($data['limit'] < 1) {
						$data['limit'] = 20;
					}	
					
					$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
				}
			}
		}
		
		/*$sql .= " LIMIT 0, 5";
		
		
		*/
		//echo "<hr>";
		//echo $sql;   
		//echo "<hr>";
		
		$notes_data = array();
		
		if(IS_WAREHOUSE == '1'){
			$query = $this->newdb->query($sql);
		}else{
			$query = $this->db->query($sql);
		}
		/*
		foreach ($query->rows as $result) {
			$notes_data[$result['notes_id']] = $this->getNote($result['notes_id']);
		}
	
		return $notes_data;
		*/
		return $query->rows;
	}
	

	public function insertTotal($data = array()) {
		
		$case_info = $this->getcasedetail($data);
		
		if($case_info['case_dashboard_id'] != null && $case_info['case_dashboard_id'] != ""){
			$usql = "UPDATE `" . DB_PREFIX . "case_dashboard` SET 
			tags_id = '".$this->db->escape($data['tags_id'])."'
			,sightandsoundcount = '".$this->db->escape($data['sightandsoundcount'])."'
			,incidentcount = '".$this->db->escape($data['incidentcount'])."'
			,highlightercount = '".$this->db->escape($data['highlightercount'])."'
			,colourcount = '".$this->db->escape($data['colorcount'])."'
			,activenotecount = '".$this->db->escape($data['activenotecount'])."'
			,taskcount = '".$this->db->escape($data['ttotaltasks'])."'
			,date_updated = '".$this->db->escape($data['date_added'])."'
			,bedcheckcount = '".$this->db->escape($data['bedcheckcount'])."'
			,formscount = '".$this->db->escape($data['ttotalforms'])."'
			,notescount = '".$this->db->escape($data['ttotalnotes'])."'
			,medicationcount = '".$this->db->escape($data['medicationcount'])."'
			,becdcheckcount = '".$this->db->escape($data['becdcheckcount'])."'
			,pillcallcount = '".$this->db->escape($data['pillcallcount'])."'
			,reviewcount = '".$this->db->escape($data['reviewcount'])."'
			,facilities_id = '" . $data['facilities_id'] . "'
			,intake_date = '" . $data['intake_date'] . "'
			,discharge_date = '" . $data['discharge_date'] . "'
			,roll_call = '" . $data['roll_call'] . "'
			,discharge = '" . $data['discharge'] . "'
			where case_dashboard_id = '".$this->db->escape($case_info['case_dashboard_id'])."' ";
			$this->db->query($usql);
			//echo "<hr>";
			
		}else{
			
			$sql = "INSERT INTO `" . DB_PREFIX . "case_dashboard` SET 
			tags_id = '".$this->db->escape($data['tags_id'])."'		
			,sightandsoundcount = '".$this->db->escape($data['sightandsoundcount'])."'
			,incidentcount = '".$this->db->escape($data['incidentcount'])."'
			,highlightercount = '".$this->db->escape($data['highlightercount'])."'
			,colourcount = '".$this->db->escape($data['colorcount'])."'
			,activenotecount = '".$this->db->escape($data['activenotecount'])."'
			,taskcount = '".$this->db->escape($data['ttotaltasks'])."'
			,date_added = '".$this->db->escape($data['date_added'])."'
			,bedcheckcount = '".$this->db->escape($data['bedcheckcount'])."'
			,formscount = '".$this->db->escape($data['ttotalforms'])."'
			,notescount = '".$this->db->escape($data['ttotalnotes'])."'
			,medicationcount = '".$this->db->escape($data['medicationcount'])."'
			,becdcheckcount = '".$this->db->escape($data['becdcheckcount'])."'
			,pillcallcount = '".$this->db->escape($data['pillcallcount'])."'
			,reviewcount = '".$this->db->escape($data['reviewcount'])."'
			,facilities_id = '" . $data['facilities_id'] . "'
			,intake_date = '" . $data['intake_date'] . "'
			,discharge_date = '" . $data['discharge_date'] . "'
			,roll_call = '" . $data['roll_call'] . "'
			,discharge = '" . $data['discharge'] . "'
			";
			//echo "<hr>";
			$query = $this->db->query($sql);
		}
		
		
		$this->load->model('activity/activity');
		$adata['tags_id'] = $data['tags_id'];
		$adata['sightandsoundcount'] = $data['sightandsoundcount'];
		$adata['incidentcount'] = $data['incidentcount'];
		$adata['highlightercount'] = $data['highlightercount'];
		$adata['colorcount'] = $data['colorcount'];
		$adata['activenotecount'] = $data['activenotecount'];		
		$adata['facilities_id'] = $data['facilities_id'];
		$adata['date_added'] = $data['date_added'];
		$adata['ttotaltasks'] = $data['ttotaltasks'];
		$adata['bedcheckcount'] = $data['bedcheckcount'];
		$adata['ttotalforms'] = $data['ttotalforms'];
		$adata['ttotalnotes'] = $data['ttotalnotes'];
		$adata['medicationcount'] = $data['medicationcount'];
		$adata['becdcheckcount'] = $data['becdcheckcount'];
		$adata['pillcallcount'] = $data['pillcallcount'];
		$adata['reviewcount'] = $data['reviewcount'];
		$adata['intake_date'] = $data['intake_date'];
		$adata['discharge_date'] = $data['discharge_date'];
		$adata['roll_call'] = $data['roll_call'];
		$adata['discharge'] = $data['discharge'];
		$this->model_activity_activity->addActivitySave('insertTotalcase', $adata, 'query');
		
	}
	
	public function getcasedetail($data = array()){
		
		$sql = "Select * from `" . DB_PREFIX . "case_dashboard` where tags_id = '" . $data['tags_id'] . "' and `date_added` BETWEEN  '".$data['start_date']." 00:00:00 ' AND  '".$data['start_date']." 23:59:59' " ;
		$query = $this->db->query($sql);
		//echo "<hr>";
		return $query->row;
	}
	
	
	public function getcasedetails($data = array()){
		
		$startDate = date('Y-m-d', strtotime($data['note_date_from']));
		$endDate = date('Y-m-d', strtotime($data['note_date_to']));
		$startTimeFrom = '00:00:00';
		$startTimeTo =  '23:59:59';
		
			
		$sql = "Select * from `" . DB_PREFIX . "case_dashboard` where tags_id = '" . $data['emp_tag_id'] . "' and facilities_id = '" . $data['facilities_id'] . "' and `date_added` BETWEEN  '".$startDate." ".$startTimeFrom."' AND  '".$endDate." ".$startTimeTo."' " ;
		//echo $sql;
		//echo "<hr>";
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function getcasedetailid($data = array()){
		
		$startDate = date('Y-m-d', strtotime($data['note_date_from']));
		$endDate = date('Y-m-d', strtotime($data['note_date_to']));
		$startTimeFrom = '00:00:00';
		$startTimeTo =  '23:59:59';
		
			
		$sql = "Select intake_date,discharge_date,roll_call from `" . DB_PREFIX . "case_dashboard` where tags_id = '" . $data['tags_id'] . "' and facilities_id = '" . $data['facilities_id'] . "' and `date_added` BETWEEN  '".$startDate." ".$startTimeFrom."' AND  '".$endDate." ".$startTimeTo."' " ;
		//echo $sql;
		//echo "<hr>";
		$query = $this->db->query($sql);
		
		return $query->rows;
	}

	public function getcasedetailbytagid($tags_id){

		//var_dump($data);

		$sql = "select * from `" . DB_PREFIX . "case_dashboard` where tags_id = '" . $tags_id . "' GROUP BY intake_date ORDER  BY intake_date DESC";
		$query = $this->db->query($sql);
		//echo "<hr>";
		return $query->rows;
	}
	
	
}
?>