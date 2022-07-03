<?php  
class Controllersyndbsyndbothermain extends Controller {
	
	public function __construct() {
		global $registry;
		parent::__construct($registry);
			$fnewdb = new DB(FNNEWDB_DRIVER, FNNEWDB_HOSTNAME, FNNEWDB_USERNAME, FNNEWDB_PASSWORD, FNNEWDB_DATABASE);
			$registry->set('fnnewdb', $fnewdb );
 
	}
	

	public function index() {
		$this->load->model('syndb/syndb');
		$this->load->model('activity/activity');
		
		$manual_link = $this->request->get['manual_link'];
		
		$connection = mysql_connect(FNNEWDB_HOSTNAME, FNNEWDB_USERNAME, FNNEWDB_PASSWORD);
		var_dump($connection);
		echo mysql_error();
		mysql_select_db(FNNEWDB_DATABASE, $connection);

		echo $query = "SELECT * FROM ". DB_PREFIX."user limit 0,5 ";
		 
		$result = mysql_query($query);

		var_dump($result);
		if($result)
		{
		  while($row = mysql_fetch_array($result))
		  {
			$name = $row['username'];
			echo "Name: " . $name; 

		  }
		}
		mysql_close($connection);
		echo "<hr>";
		
		$this->load->model('facilities/facilities');
		$this->load->model('setting/timezone');
		
		if ($this->request->get['facilities_id'] != '' && $this->request->get['facilities_id'] != null) {
			$facilities_id = $this->request->get['facilities_id'];
		}
		
		$facility = $this->model_facilities_facilities->getfacilities($facilities_id);
		
		$unique_id = $facility['customer_key'];
		
		
		if ($this->request->get['date_from'] != '' && $this->request->get['date_from'] != null) {
			$date_from = $this->request->get['date_from'];
		}
		
		if ($this->request->get['date_to'] != '' && $this->request->get['date_to'] != null) {
			$date_to = $this->request->get['date_to'];
		}
		
		$this->load->model('customer/customer');
		$customer_info = $this->model_customer_customer->getcustomerid($unique_id);
		$customer_key = $customer_info['activecustomer_id'];
	
					
		$timezone_info = $this->model_setting_timezone->gettimezone($facility['timezone_id']);
		$facilitytimezone = $timezone_info['timezone_value'];
			
		$timezone_name = $facilitytimezone;
						
		date_default_timezone_set($timezone_name);
		
		$update_date = date('Y-m-d H:i:s', strtotime('now'));
		
		if($manual_link == '1'){
			define('MONTH', '12');
			define('DAY', '150');
			
			/*$startDate = date('Y-m-d', strtotime("-".MONTH." Months"));*/
			//$endDate = date('Y-m-d');
			
			
			//$startDate = date('Y-m-d', strtotime("-".DAY." day")) .' 00:00:00';
			
			//$endDate = date('Y-m-d') .' 23:59:59';
			
			if($date_from != ""){
				$startDate = $date_from.' 00:00:00';
				$date_added_val = '1';
			}else{
				$startDate = date('Y-m-d', strtotime("-".DAY." day")) .' 00:00:00';
			}
			
			if($date_to != ""){
				$endDate = $date_to.' 23:59:59';
			}else{
				$endDate = date('Y-m-d') .' 23:59:59';
			}
			
			
		}else{
			define('MONTH', '12');
			define('DAY', '30');
			
			/*$startDate = date('Y-m-d', strtotime("-".MONTH." Months"));*/
			//$endDate = date('Y-m-d', strtotime("-".DAY." day"));
			
			
			//$startDate = date('Y-m-d', strtotime("-".DAY." day")) .' 00:00:00';
			//$endDate = date('Y-m-d') .' 23:59:59';
			
			if($date_from != ""){
				$startDate = $date_from.' 00:00:00';
				$date_added_val = '1';
			}else{
				$startDate = date('Y-m-d', strtotime("-".DAY." day")) .' 00:00:00';
			}
			
			if($date_to != ""){
				$endDate = $date_to.' 23:59:59';
			}else{
				$endDate = date('Y-m-d') .' 23:59:59';
			}
			
		
		}
		
		$config_unique_id = $unique_id;
		
		$datamodel = array();
		
		$datamodel['config_unique_id'] = $config_unique_id;
		
		
		try{
			
			/*$sqlur = "SELECT * FROM `" . DB_PREFIX . "user_group` where user_group_id != '1' ";
			$queryur = $this->fnnewdb->query($sqlur);
		
			$userrs = $queryur->rows;
			
			var_dump($userrs);
			
			if(!empty($userrs)){
				foreach($userrs as $userrole){
					$sqlgur = "SELECT * FROM `" . DB_PREFIX . "user_group` where `name` = '".$userrole['name']."' ";
					$querygur = $this->db->query($sqlgur);
				
					$user_role_info = $querygur->row;
					
					if(empty($user_role_info)){
						$this->db->query("INSERT INTO `" . NEWDB_PREFIX . "user_group` SET name = '" . $user['name'] . "', permission = '" . $user['permission'] . "', description = '" . $user['description'] . "', userview = '" . $user['userview'] . "', useradd = '" . $user['useradd'] . "', useredit = '" . $user['useredit'] . "', userdelete = '" . $user['userdelete'] . "', facilityview = '" . $user['facilityview'] . "', facilityadd = '" . $user['facilityadd'] . "', facilityedit = '" . $user['facilityedit'] . "', facilitydelete = '" . $user['facilitydelete'] . "', is_private = '" . $user['is_private'] . "', user_groupids = '" . $user['user_groupids'] . "', access_dashboard = '" . $user['access_dashboard'] . "', share_notes = '" . $user['share_notes'] . "', customer_key = '" . $customer_key . "' ");
					}
				}
			}*/
			
			$sqlu = "SELECT * FROM `" . DB_PREFIX . "user` where FIND_IN_SET('". $facilities_id."', facilities) and user_id != '1' ";
			$queryu = $this->fnnewdb->query($sqlu);
		
			$users = $queryu->rows;
			
			//var_dump($users);
			
			if(!empty($users)){
				foreach($users as $user){
					$sqlgu = "SELECT * FROM `" . DB_PREFIX . "user` where `activationKey` = '".$user['activationKey']."' ";
					$querygu = $this->db->query($sqlgu);
				
					$user_info = $querygu->row;
					
					if(empty($user_info)){
						$this->db->query("INSERT INTO `" . NEWDB_PREFIX . "user` SET user_group_id = '" . $user['user_group_id'] . "', username = '" . $user['username'] . "', password = '" . $user['password'] . "', salt = '" . $user['salt'] . "', firstname = '" . $user['firstname'] . "', lastname = '" . $user['lastname'] . "', email = '" . $user['email'] . "', code = '" . $user['code'] . "', ip = '" . $user['ip'] . "', status = '" . $user['status'] . "', date_added = '" . $user['date_added'] . "', user_pin = '" . $user['user_pin'] . "', facilities = '" . $user['facilities'] . "', phone_number = '" . $user['phone_number'] . "', parent_id = '" . $user['parent_id'] . "', activationKey = '" . $user['activationKey'] . "', default_facilities_id = '" . $user['default_facilities_id'] . "', default_highlighter_id = '" . $user['default_highlighter_id'] . "', default_color = '" . $user['default_color'] . "', user_otp = '" . $user['user_otp'] . "', message_sid = '" . $user['message_sid'] . "', facilities_display = '" . $user['facilities_display'] . "', customer_key = '" . $customer_key . "' ");
					}
				}
			}
			
			
			
			
			
			echo $sql = "SELECT * FROM `" . DB_PREFIX . "notes` where `date_added` BETWEEN  '".$startDate."' AND  '".$endDate."' and facilities_id='".$facilities_id."' ";
			
			
			$query = $this->fnnewdb->query($sql);
		
			$notes = $query->rows;
			
			//var_dump($notes);
			
			//die;
			if($notes != null && $notes != ""){
				
				
				foreach($notes as $note){
					
					echo $sqlg = "SELECT sync_records FROM `" . DB_PREFIX . "notes` where `sync_records` = '".$note['notes_id']."' and facilities_id = '" . $facilities_id . "' ";
					$queryg = $this->db->query($sqlg);
				
					$note_info = $queryg->row['sync_records'];
					//var_dump($note_info);
					echo "<hr>";
					if($note_info == null && $note_info == ""){
					
					$notes_description = $note['notes_description']; 
					$servernotes_id = $note['notes_id'];
					
					echo $sql = "INSERT INTO `" . NEWDB_PREFIX . "notes` SET facilities_id = '" . $facilities_id . "', notes_description = '" . $this->db->escape($notes_description) . "', highlighter_id = '" . $note['highlighter_id'] . "', notes_pin = '" . $this->db->escape($note['notes_pin']) . "', notes_file = '" . $this->db->escape($note['notes_file']) . "', date_added = '" . $note['date_added'] . "', status = '".$note['status']."', user_id = '" . $this->db->escape($note['user_id']) . "', signature = '".$note['signature']."', signature_image = '" . $note['signature_image'] . "', notetime = '" . $note['notetime'] . "', note_date = '" . $note['note_date'] . "', text_color_cut = '".$note['text_color_cut']."',  text_color = '" .$note['text_color']. "',  strike_user_id = '".$this->db->escape($note['strike_user_id'])."', strike_date_added = '" .$note['strike_date_added']. "', strike_signature = '" .$note['strike_signature']. "', strike_signature_image = '" . $note['strike_signature_image'] . "', strike_pin = '" . $this->db->escape($note['strike_pin']) . "', global_utc_timezone = '" . $note['global_utc_timezone'] . "', keyword_file_url = '" . $note['keyword_file_url'] . "', highlighter_value = '" . $note['highlighter_value'] . "', keyword_file = '" . $note['keyword_file'] . "', taskadded = '" . $note['taskadded'] . "', task_time = '" . $note['task_time'] . "', assign_to = '" . $note['assign_to'] . "', emp_tag_id = '" . $note['emp_tag_id'] . "', notes_type = '" . $note['notes_type'] . "', checklist_status = '" . $note['checklist_status'] . "', snooze_time = '" . $note['snooze_time'] . "', snooze_dismiss = '" . $note['snooze_dismiss'] . "', send_sms = '" . $note['send_sms'] . "', send_email = '" . $note['send_email'] . "', notes_search_keword = '" . $note['notes_search_keword'] . "', strike_note_type = '" . $note['strike_note_type'] . "', audio_attach_url = '" . $note['audio_attach_url'] . "', task_type = '" . $note['task_type'] . "', tags_id = '" . $note['tags_id'] . "', update_date = '" . $update_date . "', medication_attach_url = '" . $note['medication_attach_url'] . "', is_private = '" . $note['is_private'] . "', is_private_strike = '" . $note['is_private_strike'] . "', assessment_id = '" . $note['assessment_id'] . "', review_notes = '" . $note['review_notes'] . "', share_notes = '" . $note['share_notes'] . "', rule_highlighter_task = '" . $note['rule_highlighter_task'] . "', rule_activenote_task = '" . $note['rule_activenote_task'] . "', rule_color_task = '" . $note['rule_color_task'] . "', rule_keyword_task = '" . $note['rule_keyword_task'] . "', is_offline = '" . $note['is_offline'] . "', notes_conut = '0', tasktype = '" . $note['tasktype'] . "', visitor_log = '" . $note['visitor_log'] . "', task_id = '" . $note['task_id'] . "', task_date = '" . $note['task_date'] . "', parent_id = '" . $note['parent_id'] . "', end_perpetual_task = '" . $note['end_perpetual_task'] . "', recurrence = '" . $note['recurrence'] . "', customlistvalues_id = '" . $note['customlistvalues_id'] . "', generate_report = '" . $note['generate_report'] . "', is_android = '" . $note['is_android'] . "', is_census = '" . $note['is_census'] . "', is_tag = '" . $note['is_tag'] . "', form_type = '" . $note['form_type'] . "', tagstatus_id = '" . $note['tagstatus_id'] . "', task_group_by = '" . $note['task_group_by'] . "', end_task = '" . $note['end_task'] . "', form_snooze_dismiss = '" . $note['form_snooze_dismiss'] . "', form_send_sms = '" . $note['form_send_sms'] . "', form_send_email = '" . $note['form_send_email'] . "', form_snooze_time = '" . $note['form_snooze_time'] . "', form_create_task = '" . $note['form_create_task'] . "', form_alert_send_email = '" . $note['form_alert_send_email'] . "', form_alert_send_sms = '" . $note['form_alert_send_sms'] . "', is_archive = '" . $note['is_archive'] . "', phone_device_id = '" . $note['phone_device_id'] . "', original_task_time = '" . $note['original_task_time'] . "', is_forms = '" . $note['is_forms'] . "', is_reminder = '" . $note['is_reminder'] . "', form_trigger_snooze_dismiss = '" . $note['form_trigger_snooze_dismiss'] . "', user_file = '" . $note['user_file'] . "', is_user_face = '" . $note['is_user_face'] . "', is_approval_required_forms_id = '" . $note['is_approval_required_forms_id'] . "', is_casecount = '" . $note['is_casecount'] . "', device_unique_id = '" . $note['device_unique_id'] . "', sync_dashboard = '" . $note['sync_dashboard'] . "', strike_user_file = '" . $note['strike_user_file'] . "', strike_is_user_face = '" . $note['strike_is_user_face'] . "', sync_records = '".$servernotes_id."', unique_id = '".$unique_id."'
					
					";
					
					$this->db->query($sql);
					
					$notes_id = $this->db->getLastId(); 
					
					echo "<hr>";
					
					$sql1 = "SELECT * FROM `" . DB_PREFIX . "notes_media` where `notes_id` ='".$servernotes_id."' ";
					$query1 = $this->fnnewdb->query($sql1);
				
					$attachments = $query1->rows;
					
					
					if($attachments != null && $attachments != ""){
						foreach($attachments as $attachment){
							
						echo $sql = "INSERT INTO " . NEWDB_PREFIX . "notes_media SET notes_file = '" . $attachment['notes_file'] . "', notes_id = '" . $notes_id . "', deleted = '" . $attachment['deleted'] . "', status = '" . $attachment['status'] . "', notes_media_extention = '" . $attachment['notes_media_extention'] . "', media_user_id = '" . $this->db->escape($attachment['media_user_id']) . "', media_date_added = '" . $attachment['media_date_added'] . "', media_signature = '" . $attachment['media_signature'] . "', media_signature_image = '" . $attachment['media_signature_image'] . "', media_pin = '" . $attachment['media_pin'] . "', update_media = '" . $attachment['update_media'] . "', notes_type = '" . $attachment['notes_type'] . "', audio_attach_url = '" . $attachment['audio_attach_url'] . "', audio_attach_type = '" . $attachment['audio_attach_type'] . "', audio_upload_file = '" . $attachment['audio_upload_file'] . "', facilities_id = '" . $facilities_id . "' , speech_name = '" . $attachment['speech_name'] . "', is_updated = '" . $attachment['is_updated'] . "' , phone_device_id = '" . $attachment['phone_device_id'] . "' , is_android = '" . $attachment['is_android'] . "' , sync_dashboard = '" . $attachment['sync_dashboard'] . "' , user_file = '" . $attachment['user_file'] . "' , is_user_face = '" . $attachment['is_user_face'] . "' 
							";
							$this->db->query($sql);
						}
						
						$sql12 = "UPDATE `" . DB_PREFIX . "notes` SET notes_file = '1' WHERE notes_id = '" . (int)$notes_id . "'";
						$this->db->query($sql12);
					}
					
					/*$sql2 = "SELECT * FROM `" . DB_PREFIX . "forms` where `notes_id` ='".$servernotes_id."' ";
					$query2 = $this->fnnewdb->query($sql2);
				
					$forms = $query2->rows;
					
					if($forms != null && $forms != ""){
						foreach($forms as $form){
							
							echo $sql = "INSERT INTO " . NEWDB_PREFIX . "forms SET form_type_id = '" . $form['form_type_id'] . "', form_type = '" . $form['form_type'] . "', form_description = '" . $this->db->escape($form['form_description']) . "', date_added = '" . $form['date_added'] . "', notes_id = '" . $notes_id . "', user_id = '" . $this->db->escape($form['user_id']) . "', signature = '" . $form['signature'] . "', notes_pin = '" . $form['notes_pin'] . "', form_date_added = '" . $form['form_date_added'] . "', incident_number = '" . $this->db->escape($form['incident_number']) . "', facilities_id = '" . $facilities_id . "', notes_type = '" . $form['notes_type'] . "', assessment_id = '" . $form['assessment_id'] . "', custom_form_type = '" . $form['custom_form_type'] . "', design_forms = '" . $this->db->escape($form['design_forms']) . "', date_updated = '" . $update_date . "', upload_file = '" . $form['upload_file'] . "', tags_id = '" . $form['tags_id'] . "', parent_id = '" . $form['parent_id'] . "', is_discharge = '" . $form['is_discharge'] . "', tagstatus_id = '" . $form['tagstatus_id'] . "', rules_form_description = '" . $this->db->escape($form['rules_form_description']) . "', is_archive = '" . $this->db->escape($form['is_archive']) . "', is_final = '" . $this->db->escape($form['is_final']) . "', is_approval_required = '" . $this->db->escape($form['is_approval_required']) . "', is_approved = '" . $this->db->escape($form['is_approved']) . "', unique_id = '".$unique_id."'
							";
							$this->db->query($sql);
						}
						
						$sql12ff = "UPDATE `" . DB_PREFIX . "notes` SET is_forms = '1' WHERE notes_id = '" . (int)$notes_id . "'";
						$this->db->query($sql12ff);
						
					}*/
					
						
					/*	$sql3 = "SELECT * FROM `" . DB_PREFIX . "notes_by_task` where `notes_id` ='".$servernotes_id."' ";
						$query3 = $this->fnnewdb->query($sql3);
				
						$taskforms = $query3->rows;
						
						 
						if($taskforms != null && $taskforms != ""){
							foreach($taskforms as $taskform){
								
								echo $sql = "INSERT INTO " . NEWDB_PREFIX . "notes_by_task SET notes_id = '" . $notes_id . "', locations_id = '" . $taskform['locations_id'] . "', task_type = '" . $taskform['task_type'] . "', task_content = '" . $this->db->escape($taskform['task_content']) . "', user_id = '" . $this->db->escape($taskform['user_id']) . "', date_added = '" . $taskform['date_added'] . "', signature = '" . $taskform['signature'] . "', notes_pin = '" . $taskform['notes_pin'] . "', notes_type = '" . $taskform['notes_type'] . "', task_time = '" . $taskform['task_time'] . "', media_url = '" . $taskform['media_url'] . "', capacity = '" . $taskform['capacity'] . "', location_name = '" . $this->db->escape($taskform['location_name']) . "', location_type = '" . $taskform['location_type'] . "', notes_task_type = '" . $taskform['notes_task_type'] . "', tags_id = '" . $taskform['tags_id'] . "', drug_name = '" . $this->db->escape($taskform['drug_name']) . "', dose = '" . $taskform['dose'] . "', drug_type = '" . $taskform['drug_type'] . "', quantity = '" . $taskform['quantity'] . "', frequency = '" . $taskform['frequency'] . "', instructions = '" . $this->db->escape($taskform['instructions']) . "', count = '" . $taskform['count'] . "', createtask_by_group_id = '" . $taskform['createtask_by_group_id'] . "', task_comments = '" . $this->db->escape($taskform['task_comments']) . "', medication_attach_url = '" . $taskform['medication_attach_url'] . "', medication_file_upload = '" . $taskform['medication_file_upload'] . "' , facilities_id = '" . $facilities_id . "', tags_medication_id = '" . $taskform['tags_medication_id'] . "', tags_medication_details_id = '" . $taskform['tags_medication_details_id'] . "', task_customlistvalues_id = '" . $taskform['task_customlistvalues_id'] . "', tags_ids = '" . $taskform['tags_ids'] . "', room_current_date_time = '" . $taskform['room_current_date_time'] . "', complete_status = '" . $taskform['complete_status'] . "', role_call = '" . $taskform['role_call'] . "', out_tags_ids = '" . $taskform['out_tags_ids'] . "', out_capacity = '" . $taskform['out_capacity'] . "', unique_id = '".$unique_id."'
								";
								$this->db->query($sql);
							}
							
							
						}
						
						
						$sql4 = "SELECT * FROM `" . DB_PREFIX . "notes_tags` where `notes_id` ='".$servernotes_id."' ";
						$query4 = $this->fnnewdb->query($sql4);
				
						$ntags = $query4->rows;
						
						if($ntags != null && $ntags != ""){
							foreach($ntags as $ntag){
								
								echo $sql = "INSERT INTO " . NEWDB_PREFIX . "notes_tags SET emp_tag_id = '" . $ntag['emp_tag_id'] . "', tags_id = '" . $ntag['tags_id'] . "', notes_id = '" . $notes_id . "', user_id = '" . $ntag['user_id'] . "', date_added = '" . $ntag['date_added'] . "', signature = '" . $ntag['signature'] . "', signature_image = '" . $ntag['signature_image'] . "', notes_pin = '" . $ntag['notes_pin'] . "', notes_type = '" . $ntag['notes_type'] . "' , facilities_id = '" . $facilities_id . "', is_census = '" . $ntag['is_census'] . "', lunch = '" . $ntag['lunch'] . "', dinner = '" . $ntag['dinner'] . "', breakfast = '" . $ntag['breakfast'] . "', refused = '" . $ntag['refused'] . "', unique_id = '".$unique_id."'
								";
								$this->db->query($sql);
							}
							
							
						}
						*/
						echo "<hr>";
						
						$sql5 = "SELECT * FROM `" . DB_PREFIX . "notes_by_keyword` where `notes_id` ='".$servernotes_id."' ";
						$query5 = $this->fnnewdb->query($sql5);
				
						$noteskeywords = $query5->rows;
						
						if($noteskeywords != null && $noteskeywords != ""){
							foreach($noteskeywords as $noteskeyword){
								
								echo $sql = "INSERT INTO " . NEWDB_PREFIX . "notes_by_keyword SET notes_id = '" . $notes_id . "', keyword_id = '" . $noteskeyword['keyword_id'] . "', keyword_name = '" . $this->db->escape($noteskeyword['keyword_name']) . "', keyword_file = '" . $noteskeyword['keyword_file'] . "', keyword_file_url = '" . $noteskeyword['keyword_file_url'] . "', keyword_status = '" . $noteskeyword['keyword_status'] . "', active_tag = '" . $noteskeyword['active_tag'] . "', facilities_id = '" . $facilities_id . "', date_added = '" . $noteskeyword['date_added'] . "' , is_monitor_time = '" . $noteskeyword['is_monitor_time'] . "' , user_id = '" . $noteskeyword['user_id'] . "' , override_monitor_time_user_id = '" . $noteskeyword['override_monitor_time_user_id'] . "' , sync_dashboard = '" . $noteskeyword['sync_dashboard'] . "', unique_id = '".$unique_id."'
								
								";
								$this->db->query($sql);
							}
							
							$sql1 = "UPDATE `" . DB_PREFIX . "notes` SET keyword_file = '1' WHERE notes_id = '" . (int)$notes_id . "' ";
							$this->db->query($sql1);
						
						}
						
						
						/*$sql6 = "SELECT * FROM `" . DB_PREFIX . "notes_census_detail` where `notes_id` ='".$servernotes_id."' ";
						$query6 = $this->fnnewdb->query($sql6);
				
						$notescensus_detail = $query6->rows;
						
						if($notescensus_detail != null && $notescensus_detail != ""){
								
							echo $sql = "INSERT INTO " . NEWDB_PREFIX . "notes_census_detail SET notes_id = '" . $notes_id . "', tags_id = '" . $notescensus_detail['tags_id'] . "', shift_id = '" . $notescensus_detail['shift_id'] . "', date_added = '" . $notescensus_detail['date_added'] . "', census_date = '" . $notescensus_detail['census_date'] . "', team_leader = '" . $this->db->escape($notescensus_detail['team_leader']) . "', direct_care = '" . $this->db->escape($notescensus_detail['direct_care']) . "', comment_box = '" . $this->db->escape($notescensus_detail['comment_box']) . "', spm = '" . $this->db->escape($notescensus_detail['spm']) . "', as_spm = '" . $this->db->escape($notescensus_detail['as_spm']) . "', case_manager = '" . $this->db->escape($notescensus_detail['case_manager']) . "', food_services = '" . $this->db->escape($notescensus_detail['food_services']) . "', educational_staff = '" . $this->db->escape($notescensus_detail['educational_staff']) . "', screenings = '" . $this->db->escape($notescensus_detail['screenings']) . "', intakes = '" . $this->db->escape($notescensus_detail['intakes']) . "', discharge = '" . $this->db->escape($notescensus_detail['discharge']) . "', offsite = '" . $this->db->escape($notescensus_detail['offsite']) . "', in_house = '" . $this->db->escape($notescensus_detail['in_house']) . "', males = '" . $this->db->escape($notescensus_detail['males']) . "', females = '" . $this->db->escape($notescensus_detail['females']) . "', total = '" . $this->db->escape($notescensus_detail['total']) . "', end_of_shift_status = '" . $this->db->escape($notescensus_detail['end_of_shift_status']) . "', staff = '" . $this->db->escape($notescensus_detail['staff']) . "', facilities_id = '" . $facilities_id . "' , unique_id = '".$unique_id."'
							";
							$this->db->query($sql);
							
							
							
						}*/
						
					}
				} 
			
				
				
				
			}
			
			if($manual_link == '1'){
				echo json_encode(1);
			}else{
				echo "Success";
			}
				
		}catch(Exception $e){
		
		
		}
		
		 
	}
	
}