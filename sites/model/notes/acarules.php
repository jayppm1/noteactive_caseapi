<?php
class Modelnotesacarules extends Model {

	public function getacaruleautonote() {
		
		$sql = "SELECT a.rules_id,a.rules_name,a.facilities_id,at.rules_module,a.rules_start_time,a.rules_end_time,a.rules_operation,a.recurnce_week_from,a.recurnce_week_to,a.rule_action_content,a.rule_action, a.recurnce_day_from,a.recurnce_day_to,a.shift_id,a.missed_count,a.facilities_id!='' AND a.facilities_id IS NOT NULL FROM " . DB_PREFIX . "acarules AS a 
		
		INNER JOIN " . DB_PREFIX . "acarules_tigger AS at ON a.rules_id=at.rules_id AND status=1
		
		GROUP BY a.rules_id";
		
		//echo $sql;
		
		$query = $this->db->query ( $sql );
		
		if ($query->num_rows) {
			foreach ($query->rows as $result) {
				//echo '<pre>xxx'; print_r($result); echo '</pre>'; //die;
				
				$rules = unserialize($result['rules_module']);
				
				$rule_action = unserialize($result['rule_action_content']);
				
				if(!empty($rule_action['auser_roles'])){
					$user_roles = implode(',',$rule_action['auser_roles']);
				}else{
					$user_roles='';
				}
				
				if(!empty($rule_action['auserids'])){
					$auserids = implode(',',$rule_action['auserids']);
				}else{
					$auserids='';
				}
				
				if(!empty($rules['rule_action'])){
					$rule_action = implode(',',$rules['rule_action']);
				}else{
					$rule_action='';
				}
				
				if($rules['rules_type']!='' && $rules['rules_type']==2){
					$sql = "SELECT keyword_id, keyword_name FROM `" . DB_PREFIX . "keyword` WHERE keyword_id = '" . $rules['keyword_id'] . "'";
				}
				
				
				
				if($rules['rules_type']!='' && $rules['rules_type']==4){
					$sql = "SELECT forms_id AS keyword_id, form_name AS keyword_name FROM `" . DB_PREFIX . "forms_design` WHERE forms_id = '" . $rules['forms_id'] . "'";
				}
				
				
				
				if($rules['rules_type']!='' && $rules['rules_type']==5){
					$sql = "SELECT locations_id AS keyword_id, location_name AS keyword_name FROM `" . DB_PREFIX . "locations` WHERE locations_id = '" . $rules['locations_id'] . "'";	
				}
				
				
				 
				//echo $sql;
				$query = $this->db->query ( $sql );
				$keyword = $query->row;
				
				//echo '<pre>'; print_r($keyword); echo '</pre>'; //die;
				
				$rules_data[] = array( 
					'keyword_id2' => $custom_list['keyword_id2'],
					'keyword_name2' => $custom_list['keyword_name2'],
					'rules_name' => $result['rules_name'],
					'keyword_name' => $keyword['keyword_name'],
					'keyword_id' => $keyword['keyword_id'],
					'rules_type' => $rules['rules_type'],
					'no_of_recurrence' => $rules['no_of_recurrence'],
					'hints' => $rules['hints'],
					'interval' => $rules['interval'],
					'duration_type' => $rules['duration_type'],
					'facilities_id' => $result['facilities_id'],
					'missed_time' => $rules['missed_time'],
					'missed_duration_type' => $rules['missed_duration_type'],
					'rules_start_time' =>  $result['rules_start_time'],
					'rules_end_time' =>  $result['rules_end_time'],
					'rules_operation' => $result['rules_operation'],
					'recurnce_week_from' => $result['recurnce_week_from'],
					'recurnce_week_to' => $result['recurnce_week_to'],
					'recurnce_day_from' => $result['recurnce_day_from'],
					'recurnce_day_to' => $result['recurnce_day_to'],
					'shift_id' => $result['shift_id'],
					'rules_id'=> $result['rules_id'],
					'missed_count' => $result['missed_count'],
					'is_missed'=> $rules['is_missed'],
					'user_roles'=> $user_roles,
					'auserids'=> $auserids,
					'rule_action' => $rule_action,
					'is_missed_notification' => $rules['is_missed_notification'],
					'notification_interval' => $rules['notification_interval'],
					'notification_duration_type' => $rules['notification_duration_type'],
					'is_task_rule' => $rules['is_task_rule'],
					'is_custom_offset' => $rules['is_custom_offset'],
					'is_custom_offset_duration_type' => $rules['is_custom_offset_duration_type']
				);
			}
			return $rules_data;
		} else {
			return false;	
		}
	}
	
	public function getacarule($data) {
		
		$sql = "SELECT a.rules_id,a.rules_name,a.facilities_id,at.rules_module,a.rules_start_time,a.rules_end_time, a.rules_operation,a.recurnce_week_from,a.recurnce_week_to,a.rule_action_content,a.rule_action, a.recurnce_day_from,a.recurnce_day_to,a.shift_id,a.missed_count,a.facilities_id!='' AND a.facilities_id IS NOT NULL FROM " . DB_PREFIX . "acarules AS a 
		
		INNER JOIN " . DB_PREFIX . "acarules_tigger AS at ON a.rules_id=at.rules_id

		and FIND_IN_SET('" . $data ['facilities_id'] . "', a.facilities_id) AND status=1
		
		GROUP BY a.rules_id";
		
		
		
		//echo $sql;
		
		$query = $this->db->query ( $sql );
		
		if ($query->num_rows) {
			
			foreach ($query->rows as $result) {
				
				$rules = unserialize($result['rules_module']);
				
				//echo '<pre>xxx'; print_r($rules); echo '</pre>'; //die;
				
				$rule_action = unserialize($result['rule_action_content']);
				
				if(!empty($rule_action['auser_roles'])){
					$user_roles = implode(',',$rule_action['auser_roles']);
				}else{
					$user_roles='';
				}
				
				if(!empty($rule_action['auserids'])){
					$auserids = implode(',',$rule_action['auserids']);
				}else{
					$auserids='';
				}
				
				if(!empty($rules['rule_action'])){
					$rule_action = implode(',',$rules['rule_action']);
				}else{
					$rule_action='';
				}
				
				
				//$sql = "SELECT keyword_id, keyword_name FROM `" . DB_PREFIX . "keyword` WHERE keyword_id = '" . $rules['keyword_id'] . "' AND FIND_IN_SET('" . $data ['facilities_id'] . "',facilities_id)";
				
				$sql = "SELECT keyword_id, keyword_name FROM `" . DB_PREFIX . "keyword` WHERE keyword_id = '" . $rules['keyword_id'] . "'";
				
				//echo '<br>'.$sql;
				
				$query = $this->db->query ( $sql );
				$keyword = $query->row;
				
				//echo '<pre>'; print_r($keyword); echo '</pre>'; //die;
				
				$rules_data[] = array( 
					'keyword_id2' => $custom_list['keyword_id2'],
					'keyword_name2' => $custom_list['keyword_name2'],
					'rules_name' => $result['rules_name'],
					'keyword_name' => $keyword['keyword_name'],
					'keyword_id' => $keyword['keyword_id'],
					'rules_type' => $rules['rules_type'],
					'no_of_recurrence' => $rules['no_of_recurrence'],
					'hints' => $rules['hints'],
					'interval' => $rules['interval'],
					'duration_type' => $rules['duration_type'],
					'facilities_id' => $result['facilities_id'],
					'missed_time' => $rules['missed_time'],
					'missed_duration_type' => $rules['missed_duration_type'],
					'rules_start_time' =>  $result['rules_start_time'],
					'rules_end_time' =>  $result['rules_end_time'],
					'rules_operation' => $result['rules_operation'],
					'recurnce_week_from' => $result['recurnce_week_from'],
					'recurnce_week_to' => $result['recurnce_week_to'],
					'recurnce_day_from' => $result['recurnce_day_from'],
					'recurnce_day_to' => $result['recurnce_day_to'],
					'shift_id' => $result['shift_id'],
					'rules_id'=> $result['rules_id'],
					'missed_count' => $result['missed_count'],
					'is_missed'=> $rules['is_missed'],
					'user_roles'=> $user_roles,
					'auserids'=> $auserids,
					'rule_action' => $rule_action,
					'is_task_rule' => $rules['is_task_rule'],
					'is_custom_offset' => $rules['is_custom_offset'],
					'is_custom_offset_duration_type' => $rules['is_custom_offset_duration_type']
				);
			}
			return $rules_data;
		} else {
			return false;	
		}
	}

	public function getnoteactivedetails2($data){
		
		//echo '<pre>tttt'; print_r($data); echo '</pre>'; //die;
		
		$sql = "SELECT nk.notes_by_keyword_id, nt.date_added,nk.notes_id,nt.facilities_id FROM " . DB_PREFIX . "notes_by_keyword AS nk INNER JOIN " . DB_PREFIX . "notes AS nt ON nt.notes_id = nk.notes_id AND nk.keyword_id='".$data['keyword_id']."' ";
		
		
		
		
		if($data['facilities_id']!="" && $data['facilities_id']!=""){
			$sql .= " AND nt.facilities_id =  '" . $data['facilities_id'] . "'";
		}
		
		if($data['facilities_ids']!="" && $data['facilities_ids']!=""){
			$sql .= " AND nt.facilities_id IN  (".$data['facilities_ids'].")";
		}

		$sql .= " AND nt.facilities_id !='' and nt.facilities_id IS NOT NULL ";
		
		if($data['shift_id']!="" && $data['shift_id']!=""){
			//$sql .= " AND nt.shift_id =  '" . $data['shift_id'] . "'";
		}
		
		
		$sql .= " ORDER BY nt.date_added DESC LIMIT 1";
		
		//echo $sql;
		
		$query = $this->db->query ( $sql );
		
		if($query->num_rows){
			return $query->row;
		}else{
			return 0;
		}	
	}
	
	public function getnoteactivedetails($data){
		
		//echo '<pre>tttt'; print_r($data); echo '</pre>'; //die;
		
		$current_date = date('Y-m-d');
		
		if($data['rules_operation']!="" && $data['rules_operation']==1){ // On time
			if($data['rules_start_time'] !='' && $data['rules_end_time']){
				$start_time = $data['rules_start_time'];
				$end_time = $data['rules_end_time'];
				$start_date_time = date('Y-m-d H:i:s',strtotime($current_date.' '.$start_time));
				$end_date_time = date('Y-m-d H:i:s',strtotime($current_date.' '.$end_time));
			}else{
				$start_date_time = $current_date.' 00:00:00';
				$end_date_time = $current_date.' 23:59:59';
			}
			
		}else if($data['rules_operation']!="" && $data['rules_operation']==2){ // Shift	
		
			if($data['rules_start_time'] !='' && $data['rules_end_time']){
				$start_time = $data['rules_start_time'];
				$end_time = $data['rules_end_time'];
				$start_date_time = date('Y-m-d H:i:s',strtotime($current_date.' '.$start_time));
				$end_date_time = date('Y-m-d H:i:s',strtotime($current_date.' '.$end_time));
			}else{
				$start_date_time = $current_date.' 00:00:00';
				$end_date_time = $current_date.' 23:59:59';
			}	
			
			
		}else if($data['rules_operation']!="" && $data['rules_operation']==3){ // Daily
			if($data['rules_start_time'] !='' && $data['rules_end_time']){
				$start_time = $data['rules_start_time'];
				$end_time = $data['rules_end_time'];
				$start_date_time = date('H:i:s',strtotime($current_date.' '.$start_time));
				$end_date_time = date('H:i:s',strtotime($current_date.' '.$end_time));
			}else{
				$start_date_time = $current_date.' 00:00:00';
				$end_date_time = $current_date.' 23:59:59';
			}
			
		}elseif($data['rules_operation'] == 4 && $data['recurnce_week_from']!='' && $data['recurnce_week_to']!=""){ //weekly
			
			$date = new DateTime();
			
			$recurnce_week_from = $date->modify('prev '.$data['recurnce_week_from']);
			
			$recurnce_week_from = $date->format('Y-m-d');
			
			$recurnce_week_to = $date->modify('next '.$data['recurnce_week_to']);
			
			$recurnce_week_to = $date->format('Y-m-d');
			
			if($data['rules_start_time']!='' && $data['rules_end_time']!=''){
				$start_date_time = $recurnce_week_from.' '.$data['rules_start_time'];
				$end_date_time = $recurnce_week_to.' '.$data['rules_end_time'];
			}else{ 
				$start_date_time = $recurnce_week_from.' 00:00:00';
				$end_date_time = $recurnce_week_to.' 23:59:59';
			}
			
			
		}else if($data['rules_operation']!="" && $data['rules_operation']==5 && $data['recurnce_day_from']!='' && $data['recurnce_day_to']!=""){ // Monthly
			if($data['rules_start_time']!='' && $data['rules_end_time']!=''){
				$start_date_time = date('Y-m').'-'.$data['recurnce_day_from'].' '.$data['rules_start_time'];
				$end_date_time = date('Y-m').'-'.$data['recurnce_day_to'].' '.$data['rules_end_time'];
			}else{ 
				$start_date_time = $data['recurnce_day_from'].' 00:00:00';
				$end_date_time = $data['recurnce_day_to'].' 23:59:59';
			}
		
		}
		
		//echo '<br>';
		
	
		$sql = "SELECT nk.notes_by_keyword_id, nt.date_added,nk.notes_id,nt.facilities_id FROM " . DB_PREFIX . "notes_by_keyword AS nk INNER JOIN " . DB_PREFIX . "notes AS nt ON nt.notes_id = nk.notes_id AND nk.keyword_id='".$data['keyword_id']."' ";
		
		
		if($data['rules_operation']!="" && $data['rules_operation']==3){
			$sql .= " AND nt.notetime BETWEEN '".$start_date_time."' AND '".$end_date_time."'";
		}else{
			$sql .= " and nt.date_added BETWEEN  '" . $start_date_time . "' AND  '" . $end_date_time . "'";
		}
		
		if($data['facilities_id']!="" && $data['facilities_id']!=""){
			$sql .= " AND nt.facilities_id =  '" . $data['facilities_id'] . "'";
		}
		
		if($data['facilities_ids']!="" && $data['facilities_ids']!=""){
			$sql .= " AND nt.facilities_id IN  (".$data['facilities_ids'].")";
		}

		$sql .= " AND nt.facilities_id !='' and nt.facilities_id IS NOT NULL ";
		
		if($data['shift_id']!="" && $data['shift_id']!=""){
			//$sql .= " AND nt.shift_id =  '" . $data['shift_id'] . "'";
		}
		
		
		$sql .= " ORDER BY nt.date_added DESC LIMIT 1";
		
		
		
		$query = $this->db->query ( $sql );
		
		if($query->num_rows){
			return $query->row;
		}else{
			return 0;
		}	
	}
	
	public function getotalnote($data){
	
		$current_date = date('Y-m-d');
		
		
		if($data['rules_operation']!="" && $data['rules_operation']==1){ // On time
			if($data['rules_start_time'] !='' && $data['rules_end_time']){
				$start_time = $data['rules_start_time'];
				$end_time = $data['rules_end_time'];
				$start_date_time = date('Y-m-d H:i:s',strtotime($current_date.' '.$start_time));
				$end_date_time = date('Y-m-d H:i:s',strtotime($current_date.' '.$end_time));
			}else{
				$start_date_time = $current_date.' 00:00:00';
				$end_date_time = $current_date.' 23:59:59';
			}
		}else if($data['rules_operation']!="" && $data['rules_operation']==2){ // Shift	
		
			if($data['rules_start_time'] !='' && $data['rules_end_time']){
				$start_time = $data['rules_start_time'];
				$end_time = $data['rules_end_time'];
				$start_date_time = date('Y-m-d H:i:s',strtotime($current_date.' '.$start_time));
				$end_date_time = date('Y-m-d H:i:s',strtotime($current_date.' '.$end_time));
			}else{
				$start_date_time = $current_date.' 00:00:00';
				$end_date_time = $current_date.' 23:59:59';
			}
			
			
		}else if($data['rules_operation']!="" && $data['rules_operation']==3){ // Daily
			if($data['rules_start_time'] !='' && $data['rules_end_time']){
				$start_time = $data['rules_start_time'];
				$end_time = $data['rules_end_time'];
				$start_date_time = date('Y-m-d H:i:s',strtotime($current_date.' '.$start_time));
				$end_date_time = date('Y-m-d H:i:s',strtotime($current_date.' '.$end_time));
			}else{
				$start_date_time = $current_date.' 00:00:00';
				$end_date_time = $current_date.' 23:59:59';
			}
			
		}elseif($data['rules_operation'] == 4 && $data['recurnce_week_from']!='' && $data['recurnce_week_to']!=""){ //weekly
			
			$date = new DateTime();
			$recurnce_week_from = $date->modify('prev '.$data['recurnce_week_from']);
			$recurnce_week_from = $date->format('Y-m-d');
			$recurnce_week_to = $date->modify('next '.$data['recurnce_week_to']);
			$recurnce_week_to = $date->format('Y-m-d');
			
			if($data['rules_start_time']!='' && $data['rules_end_time']!=''){
				$start_date_time = $recurnce_week_from.' '.$data['rules_start_time'];
				$end_date_time = $recurnce_week_to.' '.$data['rules_end_time'];
			}else{ 
				$start_date_time = $recurnce_week_from.' 00:00:00';
				$end_date_time = $recurnce_week_to.' 23:59:59';
			}
		}else if($data['rules_operation']!="" && $data['rules_operation']==5 && $data['recurnce_day_from']!='' && $data['recurnce_day_to']!=""){ // monthly
			if($data['rules_start_time']!='' && $data['rules_end_time']!=''){
				$start_date_time = date('Y-m').'-'.$data['recurnce_day_from'].' '.$data['rules_start_time'];
				$end_date_time = date('Y-m').'-'.$data['recurnce_day_to'].' '.$data['rules_end_time'];
			}else{ 
				$start_date_time = $data['recurnce_day_from'].' 00:00:00';
				$end_date_time = $data['recurnce_day_to'].' 23:59:59';
			}
		}
		
			
		$sql = "SELECT count(nk.notes_by_keyword_id) AS total, nt.date_added,nk.notes_id,nk.keyword_file FROM " . DB_PREFIX . "notes_by_keyword AS nk INNER JOIN " . DB_PREFIX . "notes AS nt ON nt.notes_id = nk.notes_id AND nk.keyword_id='".$data['keyword_id']."'";
		
		//if($data['rules_operation']!="" && $data['rules_operation']==3){
		//	$sql .= " AND nt.notetime BETWEEN '".$start_date_time."' AND '".$end_date_time."'";
		//}else{
			$sql .= " and nt.date_added BETWEEN  '" . $start_date_time . "' AND  '" . $end_date_time . "'";
		//}
		
		
		if($data['facilities_id']!="" && $data['facilities_id']!=""){
			$sql .= " and nt.facilities_id =  '" . $data['facilities_id'] . "'";
		}
		
		if($data['facilities_ids']!="" && $data['facilities_ids']!=""){
			$sql .= " AND nt.facilities_id IN  (".$data['facilities_ids'].")";
		}
		
		$sql .= " AND nt.facilities_id !='' and nt.facilities_id IS NOT NULL ";
		
		$sql .= " ORDER BY nt.date_added DESC";
	
	
		
		if($data['shift_id']!="" && $data['shift_id']!=""){
		//	$sql .= " and nt.shift_id =  '" . $data['shift_id'] . "'";
		}
		
		//echo '<br>'.$sql;
		$query = $this->db->query ( $sql );
		return $query->row;
		
	}

	public function update_missed_count($data) {
		//echo '<pre>'; print_r($data); echo '</pre>'; //die;
		$sql = "UPDATE " . DB_PREFIX . "acarules SET missed_count='".$data['missed_count']."' WHERE rules_id='".$data['rules_id']."'";
		$query = $this->db->query ( $sql );
		
	}	
	
	public function missed_count($data) {
		
		$sql = "SELECT missed_count FROM `" . DB_PREFIX . "acarules` WHERE rules_id = '" . ( int ) $data['rules_id'] . "'";
		//echo $sql;
		$query = $this->db->query ( $sql );
		return $query->row;
		
	}

	public function missed_flag_insert($data) {
		
		$sql = "UPDATE " . DB_PREFIX . "notes SET is_comment = '" . $this->db->escape($data['is_comment']) . "'  WHERE notes_id = '" . (int)$data['notes_id'] . "'";
		$this->db->query($sql);
		
	}

	public function get_missed_count($data) {
		
		$current_date = date('Y-m-d');
		
		if($data['rules_operation']!="" && $data['rules_operation']==1){ // On time
			if($data['rules_start_time'] !='' && $data['rules_end_time']){
				$start_time = $data['rules_start_time'];
				$end_time = $data['rules_end_time'];
				$start_date_time = date('Y-m-d H:i:s',strtotime($current_date.' '.$start_time));
				$end_date_time = date('Y-m-d H:i:s',strtotime($current_date.' '.$end_time));
			}else{
				$start_date_time = $current_date.' 00:00:00';
				$end_date_time = $current_date.' 23:59:59';
			}
		}else if($data['rules_operation']!="" && $data['rules_operation']==2){ // Shift	
		
			if($data['rules_start_time'] !='' && $data['rules_end_time']){
				$start_time = $data['rules_start_time'];
				$end_time = $data['rules_end_time'];
				$start_date_time = date('Y-m-d H:i:s',strtotime($current_date.' '.$start_time));
				$end_date_time = date('Y-m-d H:i:s',strtotime($current_date.' '.$end_time));
			}else{
				$start_date_time = $current_date.' 00:00:00';
				$end_date_time = $current_date.' 23:59:59';
			}
			
			
		}else if($data['rules_operation']!="" && $data['rules_operation']==3){ // Daily
			if($data['rules_start_time'] !='' && $data['rules_end_time']){
				$start_time = $data['rules_start_time'];
				$end_time = $data['rules_end_time'];
				$start_date_time = date('Y-m-d H:i:s',strtotime($current_date.' '.$start_time));
				$end_date_time = date('Y-m-d H:i:s',strtotime($current_date.' '.$end_time));
			}else{
				$start_date_time = $current_date.' 00:00:00';
				$end_date_time = $current_date.' 23:59:59';
			}
			
		}elseif($data['rules_operation'] == 4 && $data['recurnce_week_from']!='' && $data['recurnce_week_to']!=""){ //weekly
			
			$date = new DateTime();
			$recurnce_week_from = $date->modify('next '.$data['recurnce_week_from']);
			$recurnce_week_from = $date->format('Y-m-d');
			$recurnce_week_to = $date->modify('next '.$data['recurnce_week_to']);
			$recurnce_week_to = $date->format('Y-m-d');
			
			if($data['rules_start_time']!='' && $data['rules_end_time']!=''){
				$start_date_time = $recurnce_week_from.' '.$data['rules_start_time'];
				$end_date_time = $recurnce_week_to.' '.$data['rules_end_time'];
			}else{ 
				$start_date_time = $recurnce_week_from.' 00:00:00';
				$end_date_time = $recurnce_week_to.' 23:59:59';
			}
		}else if($data['rules_operation']!="" && $data['rules_operation']==5 && $data['recurnce_day_from']!='' && $data['recurnce_day_to']!=""){ // Daily
			if($data['rules_start_time']!='' && $data['rules_end_time']!=''){
				$start_date_time = date('Y-m').'-'.$data['recurnce_day_from'].' '.$data['rules_start_time'];
				$end_date_time = date('Y-m').'-'.$data['recurnce_day_to'].' '.$data['rules_end_time'];
			}else{ 
				$start_date_time = $data['recurnce_day_from'].' 00:00:00';
				$end_date_time = $data['recurnce_day_to'].' 23:59:59';
			}
		}
		
		//$start_date_time = $current_date.' 00:00:00';
		//$end_date_time = $current_date.' 23:59:59';
		
		$sql = "SELECT count(nt.is_comment) AS total FROM " . DB_PREFIX . "notes_by_keyword AS nk INNER JOIN " . DB_PREFIX . "notes AS nt ON nt.notes_id = nk.notes_id AND nk.keyword_id='".$data['keyword_id']."'";
		
		if($data['is_recurrence_check']!="" && $data['is_recurrence_check']==1){
			$sql .= " AND nt.is_comment !='4'";
		}else{
			$sql .= " AND nt.is_comment ='4'";
		}
		
		$sql .= " AND nt.date_added BETWEEN  '" . $start_date_time . "' AND  '" . $end_date_time . "'";
		
		if($data['facilities_id']!="" && $data['facilities_id']!=""){
			$sql .= " AND nt.facilities_id =  '" . $data['facilities_id'] . "'";
		}
		
		$sql .= " ORDER BY nt.date_added DESC";
		//echo '<br>'.$sql;
		$query = $this->db->query ( $sql );
		return $query->row;	
	}

	public function getKeywordFileById($keyword_id){
		$sql = "SELECT * FROM " . DB_PREFIX . "keyword WHERE keyword_id='".$keyword_id."'";
		$query = $this->db->query ( $sql );
		return $query->row;
	}
	
	public function getAcarules(){ 
		$sql = "SELECT * FROM " . DB_PREFIX . "acarules WHERE status=1";
		$query = $this->db->query ( $sql );
		return $query->rows;
	}
	
	
	
	
}
?>