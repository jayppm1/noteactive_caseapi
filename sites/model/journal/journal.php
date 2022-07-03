<?php
class Modeljournaljournal extends Model {
	
	public function getnotess($data = array()) {
		
			$sql = "select DISTINCT n.* from `" . DB_PREFIX . "notes` n ";
			$sql .= "left JOIN " . DB_PREFIX . "forms f on f.notes_id=n.notes_id  ";
			$sql .= "left JOIN " . DB_PREFIX . "notes_by_task nb on nb.notes_id=n.notes_id  ";
			
			$sql .= "left JOIN " . DB_PREFIX . "notes_tags nt on nt.notes_id=n.notes_id  ";
			$sql .= "left JOIN " . DB_PREFIX . "notes_by_keyword nk on nk.notes_id=n.notes_id  ";
			 
			$sql .= ' where 1 = 1 and n.status = 1 ';
			
			if ($data['status'] != null && $data['status'] != "2") {
				$n_status  = $data['status'];
			}else{
				$n_status  = '1';
			}
			
			if ($data['facilities_id'] != null && $data['facilities_id'] != "") {
				
				if($data['child_facility_search']  == '1'){
					$this->load->model('facilities/facilities');
					$facilities_info = $this->model_facilities_facilities->getfacilities($data['facilities_id']);
					
					if ($facilities_info ['notes_facilities_ids'] != null && $facilities_info ['notes_facilities_ids'] != "") {
						$ddss [] = $facilities_info ['notes_facilities_ids'];
						
						$ddss [] = $data['facilities_id'];
						$fffa = implode(',', $ddss);
						$n_facilities_ids = implode(',', $ddss);
						$sql .= " and n.facilities_id IN (".$fffa.")";
						
					}else{
						$sql .= " and n.facilities_id = '".$data['facilities_id']."'";
						$n_facilities_id = $data['facilities_id'];
					}
				}else{
					$sql .= " and n.facilities_id = '".$data['facilities_id']."'";
					$n_facilities_id = $data['facilities_id'];
				}
			}else{
				if ($data['customer_key'] != null && $data['customer_key'] != "") {
					
					$this->load->model('customer/customer');
					$customer_info = $this->model_customer_customer->getcustomer($data['customer_key']);
					
					$this->load->model('facilities/facilities');
					$dddata = array(
						//'filter_name'  => $this->request->get['filter_name'],
						'customer_key'  => $customer_info['customer_key'],
						'status' => '1',
						//'start'       => 0,
						//'limit'       => 20
					);

				
					$ffresults = $this->model_facilities_facilities->getfacilitiess($dddata);
					$ddd = array();
					if(!empty($ffresults)){
						
						foreach($ffresults as $ffresult){
							$ddd[] = $ffresult['facilities_id'];
						}
					
						if(!empty($ddd)){
							$fffa = implode('\',\'', $ddd);
							$n_facilities_ids = implode(',', $ddd);
							$sql .= " and n.facilities_id IN ('".$fffa."')";
						}
					}
					
					
					$sql .= " and n.unique_id = '".$this->db->escape($customer_info['customer_key'])."'";
					
				}
				
			}
			
			//var_dump($n_facilities_ids);
			
			if ($data['user_id'] != null && $data['user_id'] != "") {
				$sql .= " and n.user_id = '".$data['user_id']."'";
				$n_user_id = $this->db->escape($data['user_id']);
			}
			
			if ($data['emp_tag_id'] != null && $data['emp_tag_id'] != "0") {
				$sql .= " and nt.tags_id = '".$data['emp_tag_id']."'";
				$n_tags_id = $this->db->escape($data['emp_tag_id']);
			}
			
			if ($data['task_type'] != null && $data['task_type'] != "0") {
				$sql .= " and n.tasktype = '".$data['task_type']."'";
				$n_tasktype = $this->db->escape($data['task_type']);
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
			$facilityids2ss = implode(",",$facilityids);
			$wheredfac = "";
			if(!empty($facilityids)){
				//$sql .= " and n.`facilities_id` NOT IN ('".$facilityids2."') ";
				
				$n_not_facilities_ids = $facilityids2ss;
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
					
					$n_highlighter_ids = $userIds2;
					$sql .= " and n.highlighter_id in (". $userIds2.") ";
					
					}
				}else{
					$sql .= " and n.highlighter_id = '".$data['highlighter_id']."'";
					$n_highlighter_id = $data['highlighter_id'];
				}
			}
			
			if ($data['task_search'] == "1") {
				$sql .= " and n.taskadded = '1'";
				$n_taskadded = '1';
			}
			
			if ($data['task_search'] == "2") {
				$sql .= " and n.taskadded = '2'";
				$n_taskadded = '2';
			}
			
			if ($data['task_search'] == "3") {
				$sql .= " and n.taskadded = '3'";
				$n_taskadded = '3';
			}
			
			if ($data['task_search'] == "4") {
				$sql .= " and n.taskadded = '4'";
				$n_taskadded = '4';
			}
			
			if ($data['assign_to'] != null && $data['assign_to'] != "0") {
				$sql .= " and n.assign_to = '".$data['assign_to']."'";
				
				$n_assign_to = $data['assign_to'];
			}
			
			
			if($data['form_search']){
				if($data['form_search'] == 'IncidentForm'){
					$sql .= " and f.form_type = '1'";
					$n_form_type = '1';
				}
				if($data['form_search'] == 'ChecklistForm'){
					$sql .= " and f.form_type = '2'";
					$n_form_type = '2';
				}
				
				if($data['form_search'] == 'BedCheckForm'){
					$sql .= " and n.task_type = '1'";
					$n_task_type = '1';
				}
				
				if($data['form_search'] == 'MedicationForm'){
					$sql .= " and n.task_type = '2'";
					$n_task_type = '2';
				}
				
				if($data['form_search'] == 'TransportationForm'){
					$sql .= " and n.task_type = '3'";
					$n_task_type = '3';
				}
				
				if($data['form_search'] == 'Intake'){
					$sql .= " and n.is_tag != '0' and n.form_type = '2' ";
					$n_is_tag = '2';
				}
				if($data['form_search'] == 'HealthForm'){
					$sql .= " and n.is_tag != '0' and n.form_type = '1'";
					$n_is_tag = '1';
				}
				
				
				 if (is_numeric($data['form_search'])) {
					$sql .= " and f.custom_form_type = '".$data['form_search']."'";
					$n_custom_form_type = $data['form_search'];
				 }
			}
			
			
			
			
			if ($data['search_keyword'] != null && $data['search_keyword'] != "") {
				$sql .= " and ( LOWER(n.notes_description) like '%".$this->db->escape(strtolower($data['search_keyword']))."%' or LOWER(f.form_description) LIKE '%".$this->db->escape(strtolower($data['search_keyword']))."%' or LOWER(nb.task_content) LIKE '%".$this->db->escape(strtolower($data['search_keyword']))."%' ) ";
				
				$n_keyword = $this->db->escape(strtolower($data['search_keyword']));
			}
			
			if ($data['keyword_id'] != null && $data['keyword_id'] != "") {
				
				
				if($data['relation_search'] == '1'){
					
					$query2 = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "keyword WHERE keyword_id = '" . (int)$data['keyword_id'] . "'");
			
					$keydata = $query2->row;
					
					
					//echo "<hr>";
					
					$query21 = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "keyword WHERE keyword_id = '" . (int)$keydata['relation_keyword_id'] . "'");
			
					$keydata2 = $query21->row;
					
					if($keydata2['keyword_image'] != null && $keydata2['keyword_image'] != ""){
						$sql .= " and ( nk.keyword_file = '".$keydata['keyword_image']."'";
						$sql .= " or nk.keyword_file = '".$keydata2['keyword_image']."') ";
						
						$n_keyword_file_monitor_1 = $keydata['keyword_image'];
						$n_keyword_file_monitor_2 = $keydata2['keyword_image'];
						
					}elseif($keydata['monitor_time'] == '1'){
						if($keydata['monitor_time_image'] != null && $keydata['monitor_time_image'] != ""){
						$sql .= " and ( nk.keyword_file = '".$keydata['keyword_image']."'";
						$sql .= " or nk.keyword_file = '".$keydata['monitor_time_image']."') ";
						
						$n_keyword_file_monitor_1 = $keydata['keyword_image'];
						$n_keyword_file_monitor_2 = $keydata['monitor_time_image'];
						}
					}else{
						$sql .= " and nk.keyword_file = '".$keydata['keyword_image']."'";
						
						$n_keyword_file = $keydata['keyword_image'];
					}
					
				}else{
				
					if($data['keyword_id'] == 'all'){
						
						$query2 = $this->db->query("SELECT * FROM " . DB_PREFIX . "keyword ");
				
						$results = $query2->rows;
						
						$userIds2 = array();
						foreach($results as $result){
							if($result['keyword_id'] != null && $result['keyword_id'] != ""){
							$userIds2[] = $result['keyword_id'];
							}
						}
						$userIds12 = array_unique($userIds2);
						
						//$userIds21 = implode('\',\'',$userIds12); 
						
						$userIds21 = implode(',',$userIds12); 
						
						if($userIds21 != null && $userIds21 != ""){
						$userIds21 = str_replace("all,","",$userIds21);
						
						$sql .= " and nk.keyword_id in (". $userIds21.") ";
						$n_keyword_files = $userIds21;
						}
						
					}else{
						
						$query2 = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "keyword WHERE keyword_id = '" . (int)$data['keyword_id'] . "'");
				
						$keydata = $query2->row;
				
						$sql .= " and nk.keyword_file = '".$keydata['keyword_image']."'";
						
						$n_keyword_file = $keydata['keyword_image'];
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
					
					$startTimeFrom_o = '00:00:00';
					$startTimeTo_o =  '23:59:59';
					
					$n_note_date_from_t = $startDate." ".$startTimeFrom_o;
					$n_note_date_to_t = $endDate." ".$startTimeTo_o;
					$n_note_date_from_time = $startTimeFrom;
					$n_note_date_to_time = $startTimeTo;
					
					
					$datediffernt = $this->dateDiffInDays($startDate, $endDate);
					
					if($datediffernt == '1'){
						$sql .= " and ( n.`date_added` BETWEEN '".$startDate." ".$startTimeFrom." ' AND '".$endDate." ".$startTimeTo."' or f.`date_added` BETWEEN '".$startDate." ".$startTimeFrom." ' AND '".$endDate." ".$startTimeTo."' ) ";
					}else{
					
						/*$sql .='AND (
						(
						  n.`date_added` BETWEEN "'.$n_note_date_from_t.'"  
						  AND "'.$n_note_date_to_t.'"  
						  AND (
							DATE_FORMAT(n.date_added, "%H:%i:%s") >= "'.$startTimeFrom.'" 
							AND DATE_FORMAT(
							  DATE_ADD(n.date_added, INTERVAL 1 DAY),
							  "%H:%i:%s"
							) <= "'.$startTimeTo.'"
						  )
						) 
						OR (
						  f.`date_added` BETWEEN "'.$n_note_date_from_t.'"
						  AND "'.$n_note_date_to_t.'" 
						  AND (
							DATE_FORMAT(f.date_added, "%H:%i:%s") >= "'.$startTimeFrom.'" 
							AND DATE_FORMAT(
							  DATE_ADD(f.date_added, INTERVAL 1 DAY),
							  "%H:%i:%s"
							) <= "'.$startTimeTo.'"
						  )
						)
					  )';*/
						
						$sql .='AND (
						(
						  n.`date_added` BETWEEN "'.$n_note_date_from_t.'" 
						  AND "'.$n_note_date_to_t.'" 
						  AND (
							notetime >= "'.$startTimeFrom.'" 
							AND notetime <= "'.$startTimeTo.'"
						  )
						) 
						OR (
						  f.`date_added` BETWEEN "'.$n_note_date_from_t.'" 
						  AND "'.$n_note_date_to_t.'" 
						  AND (
							notetime >= "'.$startTimeFrom.'" 
							AND notetime <= "'.$startTimeTo.'"
						  )
						)
					  )';
					
					}
				}else{
					$startTimeFrom = '00:00:00';
					$startTimeTo =  '23:59:59';
					
					$n_note_date_from = $startDate." ".$startTimeFrom;
					$n_note_date_to = $endDate." ".$startTimeTo;
					
					$sql .= " and ( n.`date_added` BETWEEN '".$startDate." ".$startTimeFrom." ' AND '".$endDate." ".$startTimeTo."' or f.`date_added` BETWEEN '".$startDate." ".$startTimeFrom." ' AND '".$endDate." ".$startTimeTo."' ) ";
				}
				
				
				
			}
			
			
			if(($data['filter_date_start'] == null && $data['filter_date_start'] == "") && ($data['filter_date_end'] == null && $data['filter_date_end'] == "") && ($data['user_id'] == null && $data['user_id'] == "") && ($data['search_keyword'] == null && $data['search_keyword'] == "") && ($data['facilities_id'] == null && $data['facilities_id'] == "") && ($data['shift_starttime_hour'] == null && $data['shift_starttime_hour'] == "") && ($data['shift_endtime_hour'] == null && $data['shift_endtime_hour'] == "") && ($data['keyword_id'] == null && $data['keyword_id'] == "") && ($data['highlighter_id'] == null && $data['highlighter_id'] == "")){
					
					$startDate = date('Y-m-d');
					$endDate = date('Y-m-d');
					
					$sql .= " and ( n.`date_added` BETWEEN '".$startDate." 00:00:00 ' AND '".$endDate." 23:59:59' or f.`date_added` BETWEEN '".$startDate." 00:00:00 ' AND '".$endDate." 23:59:59' ) ";
					
					
				$n_note_date_from = $startDate." 00:00:00";
				$n_note_date_to = $endDate." 23:59:59";
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
				
				$n_group = " 1 ";
			}
			
			
			if($data['relation_search'] == '1'){
				$sql .= " ORDER BY user_id,notes_id";	
				$n_orderby = " user_id, notes_id";	
			}else{
				if (isset($data['sort'])) {
					$n_orderby =  $data['sort'];	
				} else {

					$n_orderby = " note_date";	
				}
			}
					
				if (isset($data['order']) && ($data['order'] == 'DESC')) {
					$n_orderby .= " DESC";
				} else {
					$n_orderby .= " ASC";
				}
				
				$sql .= " ORDER BY ".$n_orderby ;
			
			
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}			
				
				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}	
				
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
				
				$n_start = $data['start'];
				$n_limit = $data['limit'];
			}
			  
			
			if(IS_WAREHOUSE == '1'){
				$sql1 = "CALL getNotes('".$n_status."','".$n_facilities_id."','".$n_user_id."','".$n_tags_id."','".$n_tasktype."','".$n_facilities_ids."','".$n_not_facilities_ids."','".$n_highlighter_ids."','".$n_highlighter_id."','".$n_taskadded."','".$n_assign_to."','".$n_form_type."','".$n_task_type."','".$n_is_tag."','".$n_custom_form_type."','".$n_keyword."','".$n_keyword_file_monitor_1."', '".$n_keyword_file_monitor_2."', '".$n_keyword_files."', '".$n_keyword_file."', '".$n_note_date_from."','".$n_note_date_to."', '".$n_note_date_from_t."', '".$n_note_date_to_t."', '".$n_note_date_from_time."', '".$n_note_date_to_time."','".$n_group."','".$n_orderby."','".$n_start."', '".$n_limit."')";
				
				
				
				$query = $this->newdb->query($sql);
			}else{
				$query = $this->db->query($sql);
			}
			
			
			$barrayr = array(); 
			$barrayr['sql']= $sql;
			$this->load->model('activity/activity');
			$this->model_activity_activity->addActivitySave('schedulerquery', $barrayr, 'query');
		
		return $query->rows;
	}

	public function getTotalnotess($data = array()) {
		
			$sql ="SELECT DISTINCT COUNT(DISTINCT n.notes_id) AS total FROM `" . DB_PREFIX . "notes` n ";
			$sql .= "left JOIN dg_forms f on f.notes_id=n.notes_id  ";
			$sql .= "left JOIN " . DB_PREFIX . "notes_by_task nb on nb.notes_id=n.notes_id  ";
			
			$sql .= "left JOIN " . DB_PREFIX . "notes_tags nt on nt.notes_id=n.notes_id  ";
			$sql .= "left JOIN " . DB_PREFIX . "notes_by_keyword nk on nk.notes_id=n.notes_id  ";
			 
			$sql .= ' where 1 = 1 and n.status = 1 '; 
			
			if ($data['status'] != null && $data['status'] != "2") {
				$n_status  = $data['status'];
			}else{
				$n_status  = '1';
			}
			
		
			if ($data['facilities_id'] != null && $data['facilities_id'] != "") {
				
				
				
				if($data['child_facility_search']  == '1'){
					$this->load->model('facilities/facilities');
					$facilities_info = $this->model_facilities_facilities->getfacilities($data['facilities_id']);
					
					if ($facilities_info ['notes_facilities_ids'] != null && $facilities_info ['notes_facilities_ids'] != "") {
						$ddss [] = $facilities_info ['notes_facilities_ids'];
						
						$ddss [] = $data['facilities_id'];
						$fffa = implode(',', $ddss);
						$n_facilities_ids = implode(',', $ddss);
						$sql .= " and n.facilities_id IN (".$fffa.")";
						
					}else{
						$sql .= " and n.facilities_id = '".$data['facilities_id']."'";
						$n_facilities_id = $data['facilities_id'];
					}
				}else{
					$sql .= " and n.facilities_id = '".$data['facilities_id']."'";
					$n_facilities_id = $data['facilities_id'];
				}
				
			}else{
				if ($data['customer_key'] != null && $data['customer_key'] != "") {
					
					$this->load->model('customer/customer');
					$customer_info = $this->model_customer_customer->getcustomer($data['customer_key']);
					
					$this->load->model('facilities/facilities');
					$dddata = array(
						//'filter_name'  => $this->request->get['filter_name'],
						'customer_key'  => $customer_info['customer_key'],
						'status' => '1',
						//'start'       => 0,
						//'limit'       => 20
					);

				
					$ffresults = $this->model_facilities_facilities->getfacilitiess($dddata);
					$ddd = array();
					if(!empty($ffresults)){
						
						foreach($ffresults as $ffresult){
							$ddd[] = $ffresult['facilities_id'];
						}
					
						if(!empty($ddd)){
							$fffa = implode('\',\'', $ddd);
							$n_facilities_ids = implode(',', $ddd);
							$sql .= " and n.facilities_id IN ('".$fffa."')";
						}
					}
				}
				
			}
			
			if ($data['user_id'] != null && $data['user_id'] != "") {
				$sql .= " and n.user_id = '".$data['user_id']."'";
				$n_user_id = $this->db->escape($data['user_id']);
			}
			
			if ($data['emp_tag_id'] != null && $data['emp_tag_id'] != "0") {
				$sql .= " and nt.tags_id = '".$data['emp_tag_id']."'";
				$n_tags_id = $this->db->escape($data['emp_tag_id']);
			}
			
			if ($data['task_type'] != null && $data['task_type'] != "0") {
				$sql .= " and n.tasktype = '".$data['task_type']."'";
				$n_tasktype = $this->db->escape($data['task_type']);
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
			
			$facilityids2ss = implode(",",$facilityids);
			$wheredfac = "";
			if(!empty($facilityids)){
				//$sql .= " and n.`facilities_id` NOT IN ('".$facilityids2."') ";
				
				$n_not_facilities_ids = $facilityids2ss;
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
					$n_highlighter_ids = $userIds2;
					
					}
				}else{
					$sql .= " and n.highlighter_id = '".$data['highlighter_id']."'";
					$n_highlighter_id = $data['highlighter_id'];
				}
				
			
			}
			
			
			if ($data['task_search'] == "1") {
				$sql .= " and n.taskadded = '1'";
				$n_taskadded = '1';
			}
			
			if ($data['task_search'] == "2") {
				$sql .= " and n.taskadded = '2'";
				$n_taskadded = '2';
			}
			
			if ($data['task_search'] == "3") {
				$sql .= " and n.taskadded = '3'";
				$n_taskadded = '3';
			}
			
			if ($data['task_search'] == "4") {
				$sql .= " and n.taskadded = '4'";
				$n_taskadded = '4';
			}
			
			if ($data['assign_to'] != null && $data['assign_to'] != "0") {
				$sql .= " and n.assign_to = '".$data['assign_to']."'";
				
				$n_assign_to = $data['assign_to'];
			}
			
			
			if($data['form_search']){
				if($data['form_search'] == 'IncidentForm'){
					$sql .= " and f.form_type = '1'";
					$n_form_type = '1';
				}
				if($data['form_search'] == 'ChecklistForm'){
					$sql .= " and f.form_type = '2'";
					$n_form_type = '2';
				}
				
				if($data['form_search'] == 'BedCheckForm'){
					$sql .= " and n.task_type = '1'";
					$n_task_type = '1';
				}
				
				if($data['form_search'] == 'MedicationForm'){
					$sql .= " and n.task_type = '2'";
					$n_task_type = '2';
				}
				
				if($data['form_search'] == 'TransportationForm'){
					$sql .= " and n.task_type = '3'";
					$n_task_type = '3';
				}
				
				if($data['form_search'] == 'Intake'){
					$sql .= " and n.is_tag != '0' and n.form_type = '2' ";
					$n_is_tag = '2';
				}
				if($data['form_search'] == 'HealthForm'){
					$sql .= " and n.is_tag != '0' and n.form_type = '1'";
					$n_is_tag = '1';
				}
				
				
				 if (is_numeric($data['form_search'])) {
					$sql .= " and f.custom_form_type = '".$data['form_search']."'";
					$n_custom_form_type = $data['form_search'];
				 }
			}
			
			
			
			
			if ($data['search_keyword'] != null && $data['search_keyword'] != "") {
				$sql .= " and ( LOWER(n.notes_description) like '%".$this->db->escape(strtolower($data['search_keyword']))."%' or LOWER(f.form_description) LIKE '%".$this->db->escape(strtolower($data['search_keyword']))."%' or LOWER(nb.task_content) LIKE '%".$this->db->escape(strtolower($data['search_keyword']))."%' ) ";
				
				$n_keyword = $this->db->escape(strtolower($data['search_keyword']));
			}
			
			if ($data['keyword_id'] != null && $data['keyword_id'] != "") {
				
				
				if($data['relation_search'] == '1'){
					
					$query2 = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "keyword WHERE keyword_id = '" . (int)$data['keyword_id'] . "'");
			
					$keydata = $query2->row;
					
					
					//echo "<hr>";
					
					$query21 = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "keyword WHERE keyword_id = '" . (int)$keydata['relation_keyword_id'] . "'");
			
					$keydata2 = $query21->row;
					
					if($keydata2['keyword_image'] != null && $keydata2['keyword_image'] != ""){
						$sql .= " and ( nk.keyword_file = '".$keydata['keyword_image']."'";
						$sql .= " or nk.keyword_file = '".$keydata2['keyword_image']."') ";
						
						$n_keyword_file_monitor_1 = $keydata['keyword_image'];
						$n_keyword_file_monitor_2 = $keydata2['keyword_image'];
						
					}elseif($keydata['monitor_time'] == '1'){
						if($keydata['monitor_time_image'] != null && $keydata['monitor_time_image'] != ""){
						$sql .= " and ( nk.keyword_file = '".$keydata['keyword_image']."'";
						$sql .= " or nk.keyword_file = '".$keydata['monitor_time_image']."') ";
						
						$n_keyword_file_monitor_1 = $keydata['keyword_image'];
						$n_keyword_file_monitor_2 = $keydata['monitor_time_image'];
						}
					}else{
						$sql .= " and nk.keyword_file = '".$keydata['keyword_image']."'";
						
						$n_keyword_file = $keydata['keyword_image'];
					}
					
				}else{
				
					if($data['keyword_id'] == 'all'){
						
						$query2 = $this->db->query("SELECT * FROM " . DB_PREFIX . "keyword ");
				
						$results = $query2->rows;
						
						$userIds2 = array();
						foreach($results as $result){
							if($result['keyword_id'] != null && $result['keyword_id'] != ""){
							$userIds2[] = $result['keyword_id'];
							}
						}
						$userIds12 = array_unique($userIds2);
						
						//$userIds21 = implode('\',\'',$userIds12); 
						
						$userIds21 = implode(',',$userIds12); 
						
						if($userIds21 != null && $userIds21 != ""){
						$userIds21 = str_replace("all,","",$userIds21);
						
						$sql .= " and nk.keyword_id in (". $userIds21.") ";
						$n_keyword_files = $userIds21;
						}
						
					}else{
						
						$query2 = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "keyword WHERE keyword_id = '" . (int)$data['keyword_id'] . "'");
				
						$keydata = $query2->row;
				
						$sql .= " and nk.keyword_file = '".$keydata['keyword_image']."'";
						
						$n_keyword_file = $keydata['keyword_image'];
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
					
					$startTimeFrom_o = '00:00:00';
					$startTimeTo_o =  '23:59:59';
					
					$n_note_date_from_t = $startDate." ".$startTimeFrom_o;
					$n_note_date_to_t = $endDate." ".$startTimeTo_o;
					$n_note_date_from_time = $startTimeFrom;
					$n_note_date_to_time = $startTimeTo;
					
					$datediffernt = $this->dateDiffInDays($startDate, $endDate);
					
					if($datediffernt == '1'){
						$sql .= " and ( n.`date_added` BETWEEN '".$startDate." ".$startTimeFrom." ' AND '".$endDate." ".$startTimeTo."' or f.`date_added` BETWEEN '".$startDate." ".$startTimeFrom." ' AND '".$endDate." ".$startTimeTo."' ) ";
					}else{
					
						/*$sql .='AND (
						(
						  n.`date_added` BETWEEN "'.$n_note_date_from_t.'"  
						  AND "'.$n_note_date_to_t.'"  
						  AND (
							DATE_FORMAT(n.date_added, "%H:%i:%s") >= "'.$startTimeFrom.'" 
							AND DATE_FORMAT(
							  DATE_ADD(n.date_added, INTERVAL 1 DAY),
							  "%H:%i:%s"
							) <= "'.$startTimeTo.'"
						  )
						) 
						OR (
						  f.`date_added` BETWEEN "'.$n_note_date_from_t.'"
						  AND "'.$n_note_date_to_t.'" 
						  AND (
							DATE_FORMAT(f.date_added, "%H:%i:%s") >= "'.$startTimeFrom.'" 
							AND DATE_FORMAT(
							  DATE_ADD(f.date_added, INTERVAL 1 DAY),
							  "%H:%i:%s"
							) <= "'.$startTimeTo.'"
						  )
						)
					  )';*/
						
						$sql .='AND (
						(
						  n.`date_added` BETWEEN "'.$n_note_date_from_t.'" 
						  AND "'.$n_note_date_to_t.'" 
						  AND (
							notetime >= "'.$startTimeFrom.'" 
							AND notetime <= "'.$startTimeTo.'"
						  )
						) 
						OR (
						  f.`date_added` BETWEEN "'.$n_note_date_from_t.'" 
						  AND "'.$n_note_date_to_t.'" 
						  AND (
							notetime >= "'.$startTimeFrom.'" 
							AND notetime <= "'.$startTimeTo.'"
						  )
						)
					  )';
					
					}
					
					/*$sql .='AND (
					(
					  n.`date_added` BETWEEN "'.$n_note_date_from_t.'" 
					  AND "'.$n_note_date_to_t.'" 
					  AND (
						notetime >= "'.$startTimeFrom.'" 
						AND notetime <= "'.$startTimeTo.'"
					  )
					) 
					OR (
					  f.`date_added` BETWEEN "'.$n_note_date_from_t.'" 
					  AND "'.$n_note_date_to_t.'" 
					  AND (
						notetime >= "'.$startTimeFrom.'" 
						AND notetime <= "'.$startTimeTo.'"
					  )
					)
				  )';*/
					
				}else{
					$startTimeFrom = '00:00:00';
					$startTimeTo =  '23:59:59';
					
					$n_note_date_from = $startDate." ".$startTimeFrom;
					$n_note_date_to = $endDate." ".$startTimeTo;
					
					$sql .= " and ( n.`date_added` BETWEEN '".$startDate." ".$startTimeFrom." ' AND '".$endDate." ".$startTimeTo."' or f.`date_added` BETWEEN '".$startDate." ".$startTimeFrom." ' AND '".$endDate." ".$startTimeTo."' ) ";
				}
				
			}
			
			
			if(($data['filter_date_start'] == null && $data['filter_date_start'] == "") && ($data['filter_date_end'] == null && $data['filter_date_end'] == "") && ($data['user_id'] == null && $data['user_id'] == "") && ($data['search_keyword'] == null && $data['search_keyword'] == "") && ($data['facilities_id'] == null && $data['facilities_id'] == "") && ($data['shift_starttime_hour'] == null && $data['shift_starttime_hour'] == "") && ($data['shift_endtime_hour'] == null && $data['shift_endtime_hour'] == "") && ($data['keyword_id'] == null && $data['keyword_id'] == "") && ($data['highlighter_id'] == null && $data['highlighter_id'] == "")){
					
					$startDate = date('Y-m-d');
					$endDate = date('Y-m-d');
					
					$sql .= " and ( n.`date_added` BETWEEN '".$startDate." 00:00:00 ' AND '".$endDate." 23:59:59' or f.`date_added` BETWEEN '".$startDate." 00:00:00 ' AND '".$endDate." 23:59:59' ) ";
					
					
				$n_note_date_from = $startDate." 00:00:00";
				$n_note_date_to = $endDate." 23:59:59";
				
				
			}
			
			if(IS_WAREHOUSE == '1'){  
				$sql1 = "CALL getTotalNotes('".$n_status."','".$n_facilities_id."','".$n_user_id."','".$n_tags_id."','".$n_tasktype."','".$n_facilities_ids."','".$n_not_facilities_ids."','".$n_highlighter_ids."','".$n_highlighter_id."','".$n_taskadded."','".$n_assign_to."','".$n_form_type."','".$n_task_type."','".$n_is_tag."','".$n_custom_form_type."','".$n_keyword."','".$n_keyword_file_monitor_1."', '".$n_keyword_file_monitor_2."', '".$n_keyword_files."', '".$n_keyword_file."', '".$n_note_date_from."','".$n_note_date_to."', '".$n_note_date_from_t."', '".$n_note_date_to_t."', '".$n_note_date_from_time."', '".$n_note_date_to_time."')";
				
				//echo "<hr>";
				//echo $sql;
				
				$query = $this->newdb->query($sql);
			}else{
				$query = $this->db->query($sql);
			}
		
		
		return $query->row['total'];
	}	

	public function dateDiffInDays($date1, $date2)  
	{ 
		$diff = strtotime($date2) - strtotime($date1); 
		return abs(round($diff / 86400)); 
	}
	
	
	public function generatereport($data = array()) {
		
		$this->load->model('facilities/facilities');
		$this->load->model('setting/timezone');
		$this->load->model('notes/notes');
		$this->load->model('journal/journal');
		$this->load->model('setting/highlighter');
		$this->load->model('api/emailapi');
		
		$this->language->load('notes/notes');
		
		$ffdata = array(
			'sort' => $data['sort'],
			'order' => $data['order'],
			'filter_date_start'	     => $data['filter_date_start'], 
			'filter_date_end'	     => $data['filter_date_end'], 
			'facilities_id'           => $data['facilities_id'],
			'task_type'           => $data['task_type'],
			'highlighter_id' => $data['highlighter_id'],
			'task_search' => $data['task_search'],
			'form_search' => $data['form_search'],
			'assign_to' => $data['assign_to'],
			'emp_tag_id' => $data['emp_tag_id1'],
			'emp_tag_id1' => $data['emp_tag_id1'],
			'user_id'           => $data['username'],
			'search_keyword' => $data['search_keyword'],
			'search_time_start' => $data['search_time_start'],
			'search_time_to' => $data['search_time_to'],
			'keyword_id' => $data['keyword_id'],
			'customer_key' => $data['customer_key'],
			'start' => 0,
			'limit' => 50000
		);
		
		$nnotes = $this->model_journal_journal->getnotess($ffdata);
		//die;
		foreach($nnotes as $nnote){
	
			$result_info =  $this->model_facilities_facilities->getfacilities($nnote['facilities_id']);
			$keyImageSrc11 = "";
			
			if ($nnote['keyword_file'] == '1') {
				$allkeywords = $this->model_notes_notes->getnoteskeywors($nnote['notes_id']);
				foreach ($allkeywords as $keyword) {
					$keyImageSrc11 .= '<img src="' . $keyword['keyword_file_url'] . '" style="width:35px;height35px">';
					$noteskeywords[] = array(
							'keyword_file_url' => $keyword['keyword_file_url']
					);
				}
			}
			if ($nnote['highlighter_id'] > 0) {
				$highlighterData = $this->model_setting_highlighter->gethighlighter($nnote['highlighter_id']);
			} else {
				$highlighterData = array();
			}
			
			$images = array();
			if ($nnote['notes_file'] == '1') {
				$allimages = $this->model_notes_notes->getImages($nnote['notes_id']);
				
				foreach ($allimages as $image) {
					$images[] = array(
							'media_user_id' => $image['media_user_id'],
							'notes_type' => $image['notes_type'],
							'media_date_added' => date($this->language->get('date_format_short_2'), strtotime($image['media_date_added'])),
							'media_signature' => $image['media_signature'],
							'media_pin' => $image['media_pin'],
							'notes_file_url' => $this->url->link('notes/notes/displayFile', '' . '&notes_media_id=' . $image['notes_media_id'], 'SSL')
					);
				}
			}
			
			$alltag = array();
			if ($nnote['emp_tag_id'] == '1') {
				$alltag = $this->model_notes_notes->getNotesTags($nnote['notes_id']);
			} else {
				$alltag = array();
			}
			
			if ($nnote['notes_pin'] != null && $nnote['notes_pin'] != "") {
				$userPin = $nnote['notes_pin'];
			} else {
				$userPin = '';
			}
			
			if ($nnote['task_time'] != null && $nnote['task_time'] != "00:00:00") {
				$task_time = date('h:i A', strtotime($nnote['task_time']));
			} else {
				$task_time = "";
			}
			
			$notestasks = array();
			$grandtotal = 0;
			
			$ograndtotal = 0;
			
			if ($nnote['task_type'] == '1') {
				$alltasks = $this->model_notes_notes->getnotesBytasks($nnote['notes_id'], '1');
				
				foreach ($alltasks as $alltask) {
					$grandtotal = $grandtotal + $alltask['capacity'];
					$tags_ids_names = '';
					
					if ($alltask['tags_ids'] != null && $alltask['tags_ids'] != "") {
						$tags_ids1 = explode(',', $alltask['tags_ids']);
						
						foreach ($tags_ids1 as $tag1) {
							$tags_info1 = $this->model_setting_tags->getTag($tag1);
							
							if ($tags_info1['emp_first_name']) {
								$emp_tag_id = $tags_info1['emp_tag_id'] . ':' . $tags_info1['emp_first_name'];
							} else {
								$emp_tag_id = $tags_info1['emp_tag_id'];
							}
							
							if ($tags_info1) {
								$tags_ids_names .= $emp_tag_id . ', ';
							}
						}
					}
					
					$out_tags_ids_names = "";
					$ograndtotal = $ograndtotal + $alltask['out_capacity'];
					
					if ($alltask['out_tags_ids'] != null && $alltask['out_tags_ids'] != "") {
						$tags_ids1 = explode(',', $alltask['out_tags_ids']);
						$i = 0;
						
						$ooout = '1';
						// var_dump($tags_ids1);
						
						foreach ($tags_ids1 as $tag1) {
							
							$tags_info12 = $this->model_setting_tags->getTag($tag1);
							
							if ($tags_info12['emp_first_name']) {
								$emp_tag_id = $tags_info12['emp_tag_id'] . ':' . $tags_info1['emp_first_name'];
							} else {
								$emp_tag_id = $tags_info12['emp_tag_id'];
							}
							
							if ($tags_info12) {
								$out_tags_ids_names .= $emp_tag_id . ', ';
							}
							
							$i ++;
						}
						
						// $ograndtotal = $i;
					}else{
						$ooout = '2';
					}
					
					// var_dump($ograndtotal);
					
					if($alltask['media_url'] != null && $alltask['media_url'] != ""){
						$media_url = $this->url->link('notes/notes/displayFilemedia&media=1', '' . '&notes_by_task_id=' . $alltask['notes_by_task_id'], 'SSL');
					}else{
						$media_url = "";
					}
					
					if($alltask['medication_attach_url'] != null && $alltask['medication_attach_url'] != ""){
						$medication_attach_url = $this->url->link('notes/notes/displayFilemedia&media=2', '' . '&notes_by_task_id=' . $alltask['notes_by_task_id'], 'SSL');
					}else{
						$medication_attach_url = "";
					}
					
					$notestasks[] = array(
							'notes_by_task_id' => $alltask['notes_by_task_id'],
							'locations_id' => $alltask['locations_id'],
							'task_type' => $alltask['task_type'],
							'task_content' => $alltask['task_content'],
							'user_id' => $alltask['user_id'],
							'signature' => $alltask['signature'],
							'notes_pin' => $alltask['notes_pin'],
							'task_time' => $alltask['task_time'],
							//'media_url' => $alltask['media_url'],
							'media_url' => $media_url,
							'capacity' => $alltask['capacity'],
							'location_name' => $alltask['location_name'],
							'location_type' => $alltask['location_type'],
							'notes_task_type' => $alltask['notes_task_type'],
							'task_comments' => $alltask['task_comments'],
							'role_call' => $alltask['role_call'],
							'medication_attach_url' => $medication_attach_url,
							'date_added' => date($this->language->get('date_format_short_2'), strtotime($alltask['date_added'])),
							'room_current_date_time' => date('h:i A', strtotime($alltask['room_current_date_time'])),
							'tags_ids_names' => $tags_ids_names,
							'out_tags_ids_names' => $out_tags_ids_names
					);
				}
			}
			
			$notesmedicationtasks = array();
			if ($nnote['task_type'] == '2') {
				$alltmasks = $this->model_notes_notes->getnotesBytasks($nnote['notes_id'], '2');
				
				foreach ($alltmasks as $alltmask) {
					
					if ($alltmask['task_time'] != null && $alltmask['task_time'] != '00:00:00') {
						$taskTime = date('h:i A', strtotime($alltmask['task_time']));
					}
					
					if($alltmask['media_url'] != null && $alltmask['media_url'] != ""){
						$media_url =$this->url->link('notes/notes/displayFilemedia&media=1', '' . '&notes_by_task_id=' . $alltmask['notes_by_task_id'], 'SSL');
					}else{
						$media_url = "";
					}
					
					if($alltmask['medication_attach_url'] != null && $alltmask['medication_attach_url'] != ""){
						$medication_attach_url = $this->url->link('notes/notes/displayFilemedia&media=2', '' . '&notes_by_task_id=' . $alltmask['notes_by_task_id'], 'SSL');
					}else{
						$medication_attach_url = "";
					}
					
					$notesmedicationtasks[] = array(
							'notes_by_task_id' => $alltmask['notes_by_task_id'],
							'locations_id' => $alltmask['locations_id'],
							'task_type' => $alltmask['task_type'],
							'task_content' => $alltmask['task_content'],
							'user_id' => $alltmask['user_id'],
							'signature' => $alltmask['signature'],
							'notes_pin' => $alltmask['notes_pin'],
							'task_time' => $taskTime,
							//'media_url' => $alltmask['media_url'],
							'media_url' => $media_url,
							'capacity' => $alltmask['capacity'],
							'location_name' => $alltmask['location_name'],
							'location_type' => $alltmask['location_type'],
							'notes_task_type' => $alltmask['notes_task_type'],
							'tags_id' => $alltmask['tags_id'],
							'drug_name' => $alltmask['drug_name'],
							'dose' => $alltmask['dose'],
							'drug_type' => $alltmask['drug_type'],
							'quantity' => $alltmask['quantity'],
							'frequency' => $alltmask['frequency'],
							'instructions' => $alltmask['instructions'],
							'count' => $alltmask['count'],
							'createtask_by_group_id' => $alltmask['createtask_by_group_id'],
							'task_comments' => $alltmask['task_comments'],
							'role_call' => $alltmask['role_call'],
							'medication_file_upload' => $alltmask['medication_file_upload'],
							'date_added' => date($this->language->get('date_format_short_2'), strtotime($alltmask['date_added']))
					);
				}
			}
			
			if ($nnote['task_type'] == '6') {
				$approvaltask = $this->model_notes_notes->getapprovaltask($nnote['task_id']);
			} else {
				$approvaltask = array();
			}
			
			if ($nnote['task_type'] == '3') {
				$geolocation_info = $this->model_notes_notes->getGeolocation($nnote['notes_id']);
			} else {
				$geolocation_info = array();
			}
			
			if ($nnote['original_task_time'] != null && $nnote['original_task_time'] != "00:00:00") {
				$original_task_time = date('h:i A', strtotime($nnote['original_task_time']));
			} else {
				$original_task_time = "";
			}
			
			if($nnote['user_file'] != null && $nnote['user_file'] != ""){
				$user_file = $this->url->link('notes/notes/displayFilemedia&media=3', '' . '&notes_id=' . $nnote['notes_id'], 'SSL');
			}else{
				$user_file = "";
			}
			
			$notescomments = array();
			if($nnote['is_comment'] == '1'){
				$allcomments = $this->model_notes_notescomment->getcomments($nnote['notes_id']);
			}else{
				$allcomments = array();
			}
			
			if($allcomments){
				foreach ($allcomments as $allcomment) {
					$commentskeywords = array();
					if($allcomment['keyword_file'] == '1'){
						$aallkeywords = $this->model_notes_notescomment->getcommentskeywors($allcomment['comment_id']);
					}else{
						$aallkeywords = array();
					}
					
					if($aallkeywords){
						$keyImageSrc12 = array();
						$keyname = array();					
						foreach ($aallkeywords as $callkeyword) {
							$commentskeywords[] = array(
								'notes_by_keyword_id' => $callkeyword['notes_by_keyword_id'],
								'notes_id' => $callkeyword['notes_id'],
								'comment_id' => $callkeyword['comment_id'],
								'keyword_id' => $callkeyword['keyword_id'],
								'keyword_name' => $callkeyword['keyword_name'],
								'keyword_file_url' => $callkeyword['keyword_file_url'],
								'keyword_image' => $callkeyword['keyword_image'],
								'img_icon' => $callkeyword['keyword_file_url'],
							);
							
							
						}
					}
					$notescomments[] = array(
						'comment_id' => $allcomment['comment_id'],
						'notes_id' => $allcomment['notes_id'],
						'facilities_id' => $allcomment['facilities_id'],
						'comment' => $allcomment['comment'],
						'user_id' => $allcomment['user_id'],
						'notes_pin' => $allcomment['notes_pin'],
						'signature' => $allcomment['signature'],
						'user_file' => $allcomment['user_file'],
						'is_user_face' => $allcomment['is_user_face'],
						'date_added' => $allcomment['date_added'],
						'comment_date' => $allcomment['comment_date'],
						'notes_type' => $allcomment['notes_type'],
						'commentskeywords' => $commentskeywords,
					);
				}
			}
			
			$allforms = $this->model_notes_notes->getforms($nnote['notes_id']);
			$forms = array();
			foreach ($allforms as $allform) {
				
				if($allform['form_type'] == '3'){
					$form_url =	HTTPS_SERVER . 'index.php?route=form/form/printform'. '&forms_id=' . $allform['forms_id']. '&forms_design_id=' . $allform['custom_form_type']. '&facilities_id=' . $allform['facilities_id']. '&notes_id=' . $allform['notes_id'];
				}
				
				$forms[] = array(
						'form_type_id' => $allform['form_type_id'],
						'notes_id' => $allform['notes_id'],
						'form_type' => $allform['form_type'],
						'custom_form_type' => $allform['custom_form_type'],
						'user_id' => $allform['user_id'],
						'signature' => $allform['signature'],
						'notes_pin' => $allform['notes_pin'],
						'incident_number' => $allform['incident_number'],
						'form_date_added' => date($this->language->get('date_format_short_2'), strtotime($allform['form_date_added'])),
						'href'        => $form_url
						
					); 
			}
			//var_dump($forms);
			
			$notess[] = array(
				'notes_id' => $nnote['notes_id'],
				'notes_description' => $keyImageSrc11.' '. $nnote['notes_description'],
				'noteskeywords' => $noteskeywords,
				'notescomments' => $notescomments,
				'forms' => $forms,
				'ooout' => $ooout,
				'images' => $images,
				'facility' => $result_info['facility'],
				'highlighter_value' => $highlighterData['highlighter_value'],
				'text_color' => $nnote['text_color'],
				'text_color_cut' => $nnote['text_color_cut'],
				'username' => $nnote['user_id'],
				'notes_pin'   => $userPin,
				'signature' => $nnote['signature'],
				'date_added' => date('j, F Y h:i A', strtotime($nnote['date_added'])),
				'note_date'   => date('j, F Y h:i A', strtotime($nnote['note_date'])),
				'note_date_time'   => date('h:i A', strtotime($nnote['note_date'])),
				'date_added2' => date('D F j, Y', strtotime($nnote['date_added'])),
				'notetime'   => date('h:i A', strtotime($nnote['notetime'])),
				'is_offline' => $nnote['is_offline'],
				'taskadded' => $nnote['taskadded'],
				'checklist_status' => $nnote['checklist_status'],
				'is_private' => $nnote['is_private'],
				'share_notes' => $nnote['share_notes'],
				'review_notes' => $nnote['review_notes'],
				'is_private_strike' => $nnote['is_private_strike'],
				'notes_type' => $nnote['notes_type'],
				'strike_note_type' => $nnote['strike_note_type'],
				'task_time' => $task_time,
				'assign_to' => $nnote['assign_to'],
				'notestasks' => $notestasks,
				'notesmedicationtasks' => $notesmedicationtasks,
				
				'grandtotal' => $grandtotal,
				'ograndtotal' => $ograndtotal,
				'user_file' => $user_file,
				'is_user_face' => $nnote['is_user_face'],
				'is_approval_required_forms_id' => $nnote['is_approval_required_forms_id'],
				'original_task_time' => $original_task_time,
				'geolocation_info' => $geolocation_info,
				'approvaltask' => $approvaltask,
				'notes_file' => $nnote['notes_file'],
				'keyword_file' => $nnote['keyword_file'],
				'emp_tag_id' => $nnote['emp_tag_id'],
				'is_forms' => $nnote['is_forms'],
				'is_reminder' => $nnote['is_reminder'],
				'task_type' => $nnote['task_type'],
				'visitor_log' => $nnote['visitor_log'],
				'is_tag' => $nnote['is_tag'],
				'is_archive' => $nnote['is_archive'],
				'form_type' => $nnote['form_type'],
				'generate_report' => $nnote['generate_report'],
				'is_census' => $nnote['is_census'],
				'is_android' => $nnote['is_android'],
				'alltag' => $alltag,
				'remdata' => $remdata,
				
				'strike_user_name' => $nnote['strike_user_id'],
				'strike_pin' => $nnote['strike_pin'],
				'strike_signature' => $nnote['strike_signature'],
				'strike_date_added' => date($this->language->get('date_format_short_2'), strtotime($nnote['strike_date_added'])),
			   
			);
		}
		
		
		$template = new Template();
		$template->data['parent_id'] = $data['scheduler_report_id'];
		$template->data['journals'] = $notess;
		$template->data['facility'] = $facility;
		$template->data['note_info'] = $note_info;
		$template->data['t2facility'] = $t2facility;
		$template->data['PDF_HEADER_TITLE'] = $data['headertitle'];
		$template->data['headerString'] = $data['headersubtitle'];
		$template->data['recurrence'] = $data['recurrence'];
		$template->data['load'] = $this->load;
		
		if($data['report_format'] == '1'){
			$html = $template->fetch($this->config->get('config_template') . '/template/report/dailyactivitylog.php');
		}
		if($data['report_format'] == '0'){
			$html = $template->fetch($this->config->get('config_template') . '/template/report/default.php');
		}
		
		
		$filename = 'report_'.date('Ymd').'_'.rand().'.html';
		$outputfolder222 = DIR_IMAGE.'files/';
		
		$file_dir = $outputfolder222.$filename;
		
		$fh = fopen($file_dir, 'w'); 
		fwrite($fh, $html);
		fclose($fh);
		
		//echo "<hr>";
		$notes_file = $filename;
		$outputFolder = $file_dir;
		$s3file = "";
		if($this->config->get('enable_storage') == '1'){
			
			$s3file = $this->awsimageconfig->uploadFile($notes_file, $outputFolder, $nform['facilities_id']);
			
			//var_dump($s3file);
		}
		
		if($this->config->get('enable_storage') == '2'){
			
			require_once(DIR_SYSTEM . 'library/azure_storage/config.php');					
			//uploadBlobSample($blobClient, $outputFolder, $notes_file);
			$s3file = AZURE_URL. $notes_file;
		}
		
		if($this->config->get('enable_storage') == '3'){
			$s3file = HTTP_SERVER . 'image/files/'.$notes_file;
		}
	}
}
?>