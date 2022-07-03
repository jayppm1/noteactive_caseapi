<?php
class Modeljournaljournal extends Model {
	
	public function getnotess($data = array()) {
		$facilityUser = $this->user->getfacilitypermission();
		$userGroup = $this->user->usergroupid();
		
		if($facilityUser !="" && $facilityUser != NULL ){
			
			//$sql = "SELECT " . DB_PREFIX . "notes.* FROM `" . DB_PREFIX . "notes`";
			
			$sql = "select DISTINCT n.* from `" . DB_PREFIX . "notes` n ";
			$sql .= "left JOIN " . DB_PREFIX . "forms f on f.notes_id=n.notes_id  ";
			$sql .= "left JOIN " . DB_PREFIX . "notes_by_task nb on nb.notes_id=n.notes_id  ";
			
			$sql .= "left JOIN " . DB_PREFIX . "notes_tags nt on nt.notes_id=n.notes_id  ";
			$sql .= "left JOIN " . DB_PREFIX . "notes_by_keyword nk on nk.notes_id=n.notes_id  ";
			 
			$sql .= ' where 1 = 1 ';
			
			
			if($userGroup != USER_ROLE_ID ){
				$sql .= " and n.facilities_id IN (".$facilityUser.")";
			}
			
			if ($data['user_id'] != null && $data['user_id'] != "") {
				$sql .= " and n.user_id = '".$data['user_id']."'";
			}
			
			if ($data['facilities_id'] != null && $data['facilities_id'] != "") {
				$sql .= " and n.facilities_id = '".$data['facilities_id']."'";
			}
			
			
			$data2 = array();
			$data2['config_display_dashboard'] = '0';
			$this->load->model('facilities/facilities');
			$cfacilities = $this->model_facilities_facilities->getfacilitiess($data2);
			$facilityids = array();
			foreach($cfacilities as $cfacility){
				$facilityids[] = $cfacility['facilities_id'];
			}
			
			$facilityids2 = implode("','",$facilityids);
			$wheredfac = "";
			if(!empty($facilityids)){
				$sql .= " and n.`facilities_id` NOT IN ('".$facilityids2."') ";
			}
			
			if ($data['highlighter_id'] != null && $data['highlighter_id'] != "") {
				
				if($data['highlighter_id'] == 'all'){
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
					$sql .= " and n.highlighter_id = '".$data['highlighter_id']."'";
				}
			}
			
			if ($data['task_search'] == "1") {
				$sql .= " and n.taskadded = '1'";
			}
			
			if ($data['task_search'] == "2") {
				$sql .= " and n.taskadded = '2'";
			}
			
			if ($data['task_search'] == "3") {
				$sql .= " and n.taskadded = '3'";
			}
			
			if ($data['task_search'] == "4") {
				$sql .= " and n.taskadded = '4'";
			}
			
			if ($data['assign_to'] != null && $data['assign_to'] != "0") {
				$sql .= " and n.assign_to = '".$data['assign_to']."'";
			}
			
			if ($data['emp_tag_id'] != null && $data['emp_tag_id'] != "0") {
				$sql .= " and nt.tags_id = '".$data['emp_tag_id']."'";
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
				
				 if (is_numeric($data['form_search'])) {
					$sql .= " and f.custom_form_type = '".$data['form_search']."'";
				 }
			}
			
			if ($data['status'] != null && $data['status'] != "2") {
				$sql .= " and n.status = '".$data['status']."'";
			}
			
			
			if ($data['search_keyword'] != null && $data['search_keyword'] != "") {
				$sql .= " and ( LOWER(n.notes_description) like '%".strtolower($data['search_keyword'])."%' or LOWER(f.form_description) LIKE '%".strtolower($data['search_keyword'])."%' or LOWER(nb.task_content) LIKE '%".strtolower($data['search_keyword'])."%' ) ";
				//$sql .= " or LOWER(user_id) like '%".strtolower($data['search_keyword'])."%'";
				//$sql .= " or LOWER(emp_tag_id) like '%".strtolower($data['search_keyword'])."%'";
				//$sql .= " and notes_description like '%".$data['search_keyword']."%'";
				//$sql .= " or notes_description like '%".strtolower($data['search_keyword'])."%'";
				//$sql .= " or notes_description like '%".strtoupper($data['search_keyword'])."%'";
			}
			
			if ($data['keyword_id'] != null && $data['keyword_id'] != "") {
				
				
				if($data['relation_search'] == '1'){
					
					$query2 = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "keyword WHERE keyword_id = '" . (int)$data['keyword_id'] . "'");
			
					$keydata = $query2->row;
					
					$query21 = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "keyword WHERE keyword_id = '" . (int)$keydata['relation_keyword_id'] . "'");
			
					$keydata2 = $query21->row;
					
					if($keydata2['keyword_image'] != null && $keydata2['keyword_image'] != ""){
						$sql .= " and ( nk.keyword_file = '".$keydata['keyword_image']."'";
						$sql .= " or nk.keyword_file = '".$keydata2['keyword_image']."') ";
					}else{
						$sql .= " and nk.keyword_file = '".$keydata['keyword_image']."'";
					}
					
				}else{
				
					if($data['keyword_id'] == 'all'){
						
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
						
						$query2 = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "keyword WHERE keyword_id = '" . (int)$data['keyword_id'] . "'");
				
						$keydata = $query2->row;
				
						$sql .= " and nk.keyword_file = '".$keydata['keyword_image']."'";
					}
				
				
				}
				
			}
			
			if (($data['filter_date_start'] != null && $data['filter_date_start'] != "") && ($data['filter_date_end'] != null && $data['filter_date_end'] != "")) {
				
				
				$date = str_replace('-', '/', $data['filter_date_end']);
				$res = explode("/", $date);
				$endDate = $res[2]."-".$res[0]."-".$res[1];
				
				
				$sdate = str_replace('-', '/', $data['filter_date_start']);
				$sres = explode("/", $sdate);
				$startDate = $sres[2]."-".$sres[0]."-".$sres[1];
				//$startDate = date('Y-m-d', strtotime($data['filter_date_start']));
				//$endDate = date('Y-m-d', strtotime($data['filter_date_end']));
				
				//$sql .= " and `date_added` BETWEEN  '".$startDate." 00:00:00 ' AND  '".$endDate." 23:59:59' ";
				
				if (($data['search_time_start'] != null && $data['search_time_start'] != "") && ($data['search_time_to'] != null && $data['search_time_to'] != "")) {
					
					$startTimeFrom =  date('H:i:s', strtotime($data['search_time_start'])) ;
					$startTimeTo =  date('H:i:s', strtotime($data['search_time_to'])) ;
					
					//$sql .= " and (n.`notetime` BETWEEN  '".$startTimeFrom."' AND  '".$startTimeTo."') ";
				}else{
					$startTimeFrom = '00:00:00';
					$startTimeTo =  '23:59:59';
				}
				
				$sql .= " and ( n.`date_added` BETWEEN '".$startDate." ".$startTimeFrom." ' AND '".$endDate." ".$startTimeTo."' or f.`date_added` BETWEEN '".$startDate." 00:00:00 ' AND '".$endDate." 23:59:59' ) ";
				
				//$sql .= " and ( n.`date_added` BETWEEN '".$startDate." 00:00:00 ' AND '".$endDate." 23:59:59' or f.`date_added` BETWEEN '".$startDate." 00:00:00 ' AND '".$endDate." 23:59:59' ) ";
				
				
			}
			
			
			if(($data['filter_date_start'] == null && $data['filter_date_start'] == "") && ($data['filter_date_end'] == null && $data['filter_date_end'] == "") && ($data['user_id'] == null && $data['user_id'] == "") && ($data['search_keyword'] == null && $data['search_keyword'] == "") && ($data['facilities_id'] == null && $data['facilities_id'] == "") && ($data['shift_starttime_hour'] == null && $data['shift_starttime_hour'] == "") && ($data['shift_endtime_hour'] == null && $data['shift_endtime_hour'] == "") && ($data['keyword_id'] == null && $data['keyword_id'] == "") && ($data['highlighter_id'] == null && $data['highlighter_id'] == "")){
					
					$startDate = date('Y-m-d');
					$endDate = date('Y-m-d');
					//$sql .= " and `date_added` BETWEEN  '".$startDate." 00:00:00 ' AND  '".$endDate." 23:59:59' ";
					
					$sql .= " and ( n.`date_added` BETWEEN '".$startDate." 00:00:00 ' AND '".$endDate." 23:59:59' or f.`date_added` BETWEEN '".$startDate." 00:00:00 ' AND '".$endDate." 23:59:59' ) ";
			}
			
			
			/*if (($data['shift_starttime_hour'] != null && $data['shift_starttime_hour'] != "") && ($data['shift_endtime_hour'] != null && $data['shift_endtime_hour'] != "")) {
				$sTime = $data['shift_starttime_hour'] - 1;
				$eTime = $data['shift_endtime_hour'] - 1;
				
				$shour = $data['shift_starttime_minutes'] -1;
				$ehour = $data['shift_endtime_minutes'] -1;
				
				$sTimestemp = $data['shift_starttime_stamp'];
				
				$eTimestemp = $data['shift_endtime_stamp'];
				
				$startTimeFrom =  date('H:i:s', strtotime($sTime.':'.$shour.':00 '. $sTimestemp)) ;
				$startTimeTo =  date('H:i:s', strtotime($eTime.':'.$ehour.':00 '. $eTimestemp)) ;
				
				$sql .= " and (n.`notetime` BETWEEN  '".$startTimeFrom."' AND  '".$startTimeTo."') ";
			}*/
			
			if($data['relation_search'] == '1'){
				$sql .= " GROUP BY n.notes_description,n.user_id, n.notes_id ";
			}
			
			
			if($data['relation_search'] == '1'){
				$sql .= " ORDER BY user_id,notes_id";	
			}else{
				if (isset($data['sort'])) {
					$sql .= " ORDER BY " . $data['sort'];	
				} else {

					$sql .= " ORDER BY note_date";	
				}
			}
					
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
			  
			
			
			$query = $this->newdb->query($sql);
		}
		return $query->rows;
	}

	public function getTotalnotess($data = array()) {
		$facilityUser = $this->user->getfacilitypermission();
		$userGroup = $this->user->usergroupid();
		
		if($facilityUser !="" && $facilityUser != NULL ){
			
			$sql ="SELECT DISTINCT COUNT(DISTINCT n.notes_id) AS total FROM `" . DB_PREFIX . "notes` n ";
			$sql .= "left JOIN dg_forms f on f.notes_id=n.notes_id  ";
			$sql .= "left JOIN " . DB_PREFIX . "notes_by_task nb on nb.notes_id=n.notes_id  ";
			
			$sql .= "left JOIN " . DB_PREFIX . "notes_tags nt on nt.notes_id=n.notes_id  ";
			$sql .= "left JOIN " . DB_PREFIX . "notes_by_keyword nk on nk.notes_id=n.notes_id  ";
			 
			$sql .= ' where 1 = 1 '; 
			
			
			if($userGroup != USER_ROLE_ID ){
				$sql .= " and n.facilities_id IN (".$facilityUser.")";
			}
			
			if ($data['user_id'] != null && $data['user_id'] != "") {
				$sql .= " and n.user_id = '".$data['user_id']."'";
			}
			
			if ($data['facilities_id'] != null && $data['facilities_id'] != "") {
				$sql .= " and n.facilities_id = '".$data['facilities_id']."'";
			}
			
			$data2 = array();
			$data2['config_display_dashboard'] = '0';
			$this->load->model('facilities/facilities');
			$cfacilities = $this->model_facilities_facilities->getfacilitiess($data2);
			$facilityids = array();
			foreach($cfacilities as $cfacility){
				$facilityids[] = $cfacility['facilities_id'];
			}
			
			$facilityids2 = implode("','",$facilityids);
			$wheredfac = "";
			if(!empty($facilityids)){
				$sql .= " and n.`facilities_id` NOT IN ('".$facilityids2."') ";
			}
			
			if ($data['highlighter_id'] != null && $data['highlighter_id'] != "") {
				
				if($data['highlighter_id'] == 'all'){
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
					$sql .= " and n.highlighter_id = '".$data['highlighter_id']."'";
				}
			}
			
			if ($data['task_search'] == "1") {
				$sql .= " and n.taskadded = '1'";
			}
			
			if ($data['task_search'] == "2") {
				$sql .= " and n.taskadded = '2'";
			}
			
			if ($data['task_search'] == "3") {
				$sql .= " and n.taskadded = '3'";
			}
			
			if ($data['task_search'] == "4") {
				$sql .= " and n.taskadded = '4'";
			}
			
			if ($data['assign_to'] != null && $data['assign_to'] != "0") {
				$sql .= " and n.assign_to = '".$data['assign_to']."'";
			}
			
			if ($data['emp_tag_id'] != null && $data['emp_tag_id'] != "0") {
				$sql .= " and nt.tags_id = '".$data['emp_tag_id']."'";
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
				
				 if (is_numeric($data['form_search'])) {
					$sql .= " and f.custom_form_type = '".$data['form_search']."'";
				 }
			}
			
			if ($data['status'] != null && $data['status'] != "2") {
				$sql .= " and n.status = '".$data['status']."'";
			}
			
			if ($data['search_keyword'] != null && $data['search_keyword'] != "") {
				$sql .= " and ( LOWER(n.notes_description) like '%".strtolower($data['search_keyword'])."%' or LOWER(f.form_description) LIKE '%".strtolower($data['search_keyword'])."%' or LOWER(nb.task_content) LIKE '%".strtolower($data['search_keyword'])."%' ) ";
				//$sql .= " or LOWER(user_id) like '%".strtolower($data['search_keyword'])."%'";
				//$sql .= " or LOWER(emp_tag_id) like '%".strtolower($data['search_keyword'])."%'";
				//$sql .= " and notes_description like '%".$data['search_keyword']."%'";
				//$sql .= " or notes_description like '%".strtolower($data['search_keyword'])."%'";
				//$sql .= " or notes_description like '%".strtoupper($data['search_keyword'])."%'";
			}
			
			if ($data['keyword_id'] != null && $data['keyword_id'] != "") {
				
				
				if($data['relation_search'] == '1'){
					
					$query2 = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "keyword WHERE keyword_id = '" . (int)$data['keyword_id'] . "'");
			
					$keydata = $query2->row;
					
					$query21 = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "keyword WHERE keyword_id = '" . (int)$keydata['relation_keyword_id'] . "'");
			
					$keydata2 = $query21->row;
					
					if($keydata2['keyword_image'] != null && $keydata2['keyword_image'] != ""){
						$sql .= " and ( nk.keyword_file = '".$keydata['keyword_image']."'";
						$sql .= " or nk.keyword_file = '".$keydata2['keyword_image']."') ";
					}else{
						$sql .= " and nk.keyword_file = '".$keydata['keyword_image']."'";
					}
					
				}else{
				
					if($data['keyword_id'] == 'all'){
						
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
						
						$query2 = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "keyword WHERE keyword_id = '" . (int)$data['keyword_id'] . "'");
				
						$keydata = $query2->row;
				
						$sql .= " and nk.keyword_file = '".$keydata['keyword_image']."'";
					}
				
				
				}
				
			}
			
			if (($data['filter_date_start'] != null && $data['filter_date_start'] != "") && ($data['filter_date_end'] != null && $data['filter_date_end'] != "")) {
				
				
				$date = str_replace('-', '/', $data['filter_date_end']);
				$res = explode("/", $date);
				$endDate = $res[2]."-".$res[0]."-".$res[1];
				
				
				$sdate = str_replace('-', '/', $data['filter_date_start']);
				$sres = explode("/", $sdate);
				$startDate = $sres[2]."-".$sres[0]."-".$sres[1];
				//$startDate = date('Y-m-d', strtotime($data['filter_date_start']));
				//$endDate = date('Y-m-d', strtotime($data['filter_date_end']));
				
				if (($data['search_time_start'] != null && $data['search_time_start'] != "") && ($data['search_time_to'] != null && $data['search_time_to'] != "")) {
					
					$startTimeFrom =  date('H:i:s', strtotime($data['search_time_start'])) ;
					$startTimeTo =  date('H:i:s', strtotime($data['search_time_to'])) ;
					
					//$sql .= " and (n.`notetime` BETWEEN  '".$startTimeFrom."' AND  '".$startTimeTo."') ";
				}else{
					$startTimeFrom = '00:00:00';
					$startTimeTo =  '23:59:59';
				}
				
				$sql .= " and ( n.`date_added` BETWEEN '".$startDate." ".$startTimeFrom." ' AND '".$endDate." ".$startTimeTo."' or f.`date_added` BETWEEN '".$startDate." 00:00:00 ' AND '".$endDate." 23:59:59' ) ";
				
				//$sql .= " and `date_added` BETWEEN  '".$startDate." 00:00:00 ' AND  '".$endDate." 23:59:59' ";
				//$sql .= " and ( n.`date_added` BETWEEN '".$startDate." 00:00:00 ' AND '".$endDate." 23:59:59' or f.`date_added` BETWEEN '".$startDate." 00:00:00 ' AND '".$endDate." 23:59:59' ) ";
				
				
				
				
			}
			
			
			if(($data['filter_date_start'] == null && $data['filter_date_start'] == "") && ($data['filter_date_end'] == null && $data['filter_date_end'] == "") && ($data['user_id'] == null && $data['user_id'] == "") && ($data['search_keyword'] == null && $data['search_keyword'] == "") && ($data['facilities_id'] == null && $data['facilities_id'] == "") && ($data['shift_starttime_hour'] == null && $data['shift_starttime_hour'] == "") && ($data['shift_endtime_hour'] == null && $data['shift_endtime_hour'] == "") && ($data['keyword_id'] == null && $data['keyword_id'] == "") && ($data['highlighter_id'] == null && $data['highlighter_id'] == "")){
					
					$startDate = date('Y-m-d');
					$endDate = date('Y-m-d');
					//$sql .= " and `date_added` BETWEEN  '".$startDate." 00:00:00 ' AND  '".$endDate." 23:59:59' ";
					
					$sql .= " and ( n.`date_added` BETWEEN '".$startDate." 00:00:00 ' AND '".$endDate." 23:59:59' or f.`date_added` BETWEEN '".$startDate." 00:00:00 ' AND '".$endDate." 23:59:59' ) ";
			}
			
			/*
			if (($data['shift_starttime_hour'] != null && $data['shift_starttime_hour'] != "") && ($data['shift_endtime_hour'] != null && $data['shift_endtime_hour'] != "")) {
				$sTime = $data['shift_starttime_hour'] - 1;
				$eTime = $data['shift_endtime_hour'] - 1;
				
				$shour = $data['shift_starttime_minutes'] -1;
				$ehour = $data['shift_endtime_minutes'] -1;
				
				$sTimestemp = $data['shift_starttime_stamp'];
				
				$eTimestemp = $data['shift_endtime_stamp'];
				
				$startTimeFrom =  date('H:i:s', strtotime($sTime.':'.$shour.':00 '. $sTimestemp)) ;
				$startTimeTo =  date('H:i:s', strtotime($eTime.':'.$ehour.':00 '. $eTimestemp)) ;
				
				$sql .= " and (n.`notetime` BETWEEN  '".$startTimeFrom."' AND  '".$startTimeTo."') ";
			}
			*/
			
			
			
			$query = $this->newdb->query($sql);
		}
		
		return $query->row['total'];
	}	

	public function gethighlighterTotal($highlighter_id){
		$query = $this->newdb->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "notes` where highlighter_id= '".$highlighter_id."' ");
		
		return $query->row['total'];
	}
	
	
	public function getTotalNotesByurerId($user_id) {
		$query = $this->newdb->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "notes WHERE user_id = '" . (int) $user_id . "'");

		return $query->row['total'];
	}
	
	public function getTotalNotesByFacilityId($facilities_id) {
		$query = $this->newdb->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "notes WHERE facilities_id = '" . (int) $facilities_id . "'");

		return $query->row['total'];
	}
	
	
	public function getnotesTotal($keyword_name){
		$query = $this->newdb->query("SELECT * FROM `" . DB_PREFIX . "notes` ");
		
		$count = 0;
		foreach($query->rows as $note){
			
			$valCount = substr_count($note['notes_description'],$keyword_name);
			
			$count += $valCount;
		}
		return $count;
	}
	
	
	public function getnotesTotalByUserID($user_id){
		$query = $this->newdb->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "notes` where user_id= '".$user_id."' ");
		return $query->row['total'];
	}
	
	public function getNotesByUserID($user_id) {
		
		$sql = "SELECT notes_description FROM `" . DB_PREFIX . "notes`";
		$sql .= 'where 1 = 1 ';
		if ($user_id != null && $user_id != "") {
			$sql .= " and user_id = '".$user_id."'";
		}
		$sql .= " ORDER BY date_added DESC";	
		
		$sql .= " LIMIT 0, 1";
		
		$query = $this->newdb->query($sql);
	
		return $query->rows;
	}
	
	public function getnotesTotalByUserIDBYFacility($user_id, $facilities_id, $rang, $dateRange){
		$where = "";
		if($facilities_id != '0'){
			$where .= " and facilities_id = '".$facilities_id."'";
		}
		
		if($rang == 'day'){
			$where .= " and `date_added` BETWEEN  '".$dateRange." 00:00:00 ' AND  '".$dateRange." 23:59:59' ";
		}
		
		if($rang == 'week'){
			$where .= " and WEEK( date_added ) = WEEK( CURDATE( ) ) ";
		}
		
		if($rang == 'month'){
			$where .= " and MONTH( date_added ) = MONTH( CURDATE( ) ) ";
		}
		
		if($rang == 'year'){
			$where .= " and YEAR( date_added ) = YEAR( CURDATE( ) ) ";
		}
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "notes` where user_id= '".$user_id."' ".$where." ";
		$query = $this->newdb->query($sql);
		return $query->row['total'];
	}
	
	
	public function getnotesTotalByUserIDBYFacility2($data){
		$where = "";
		if($data['facilities_id'] != '0'){
			$where .= " and facilities_id = '".$data['facilities_id']."'";
		}
		
		if($data['rang'] == 'day'){
			$where .= " and (`date_added` BETWEEN '".$data['start_date']."' AND '".$data['end_date']."') ";
		}
		
		if($data['rang'] == 'week'){
			$where .= " and (`date_added` BETWEEN '".$data['start_date']."' AND '".$data['end_date']."') ";
		}
		
		if($data['rang'] == 'month'){
			$where .= " and (`date_added` BETWEEN '".$data['start_date']."' AND '".$data['end_date']."') ";
		}
		
		if($data['rang'] == 'year'){
			//$where .= " and YEAR( date_added ) = YEAR( CURDATE( ) ) ";
			$where .= " and (`date_added` BETWEEN '".$data['start_date']."' AND '".$data['end_date']."') ";
		}
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "notes` where user_id= '".$data['user_id']."' ".$where." ";
		$query = $this->newdb->query($sql);
		return $query->row['total'];
	}
	
	
	
	/*public function getnotesTotal(){
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "notes` ");
		
		return $query->row['total'];
	}*/
	
	
	public function getImages($notes_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "notes_media` WHERE notes_id = '" . (int)$notes_id . "' and status = '1' ";
		$query = $this->newdb->query($sql);
		return $query->rows;
	}
	
	public function getImage($notes_media_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "notes_media` WHERE notes_media_id = '" . (int)$notes_media_id . "' and status = '1' ";
		$query = $this->newdb->query($sql);
		return $query->row;
	}
	
	public function getassigns($notes_id){
		$sql = "SELECT DISTINCT assign_to FROM `" . DB_PREFIX . "notes` WHERE status = '1' and assign_to != '' ORDER BY assign_to asc ";
		$query = $this->newdb->query($sql);
		return $query->rows;
	}
	
	public function gettagassigns($notes_id){
		$sql = "SELECT DISTINCT emp_tag_id,tags_id FROM `" . DB_PREFIX . "notes` WHERE status = '1' and emp_tag_id != '' ORDER BY emp_tag_id asc ";
		$query = $this->newdb->query($sql);
		return $query->rows;
	}
	
	public function getforms($notes_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "forms` WHERE notes_id = '" . (int)$notes_id . "' ";
		$query = $this->newdb->query($sql);
		return $query->rows;
	}
	
	public function getnotesBytasks($notes_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "notes_by_task` WHERE notes_id = '" . (int)$notes_id . "' ";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getnoteskeywors($notes_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "notes_by_keyword` WHERE notes_id = '" . (int)$notes_id . "' and keyword_status = '1' ";
		$query = $this->newdb->query($sql);
		return $query->rows;
	} 
}
?>