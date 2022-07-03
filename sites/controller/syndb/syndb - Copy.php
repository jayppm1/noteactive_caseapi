<?php  
class Controllersyndbsyndb extends Controller {
	public function index() {
		$this->load->model('syndb/syndb');
		$this->load->model('activity/activity');
		
		$manual_link = $this->request->get['manual_link'];
		
		if($manual_link == '1'){
			define('MONTH', '12');
			define('DAY', '1');
			
			/*$startDate = date('Y-m-d', strtotime("-".MONTH." Months"));*/
			$startDate = date('Y-m-d', strtotime("-".DAY." day"));
			//$endDate = date('Y-m-d');
			$endDate = date('Y-m-d');
			
		}else{
			define('MONTH', '12');
			define('DAY', '1');
			
			/*$startDate = date('Y-m-d', strtotime("-".MONTH." Months"));*/
			$startDate = date('Y-m-d', strtotime("-".DAY." day"));
			$endDate = date('Y-m-d');
			//$endDate = date('Y-m-d', strtotime("-".DAY." day"));
		
		}
		
		
		
		
		//$config_unique_id = $this->config->get('config_unique_id');
		
		$datamodel = array();
		
		$datamodel['startDate'] = $startDate;
		$datamodel['endDate'] = $endDate;
		$datamodel['config_unique_id'] = $config_unique_id;
		
		try{
			/*$hostname = NEWDB_HOSTNAME;
			$username = NEWDB_USERNAME;
			$password = NEWDB_PASSWORD;
			$dbname = NEWDB_DATABASE;

			$connection = mysql_connect($hostname, $username, $password);
			var_dump($connection);
			echo mysql_error();
			mysql_select_db($dbname, $connection);
			
			//Setup our query
			echo $query = "SELECT * FROM ". NEWDB_PREFIX."user ";
			 
			//Run the Query
			$result = mysql_query($query);
			 
			//If the query returned results, loop through
			// each result
			var_dump($result);
			
			while($row = mysql_fetch_array($result))
			  {
				$name = $row['username'];
				echo "Name: " . $name; 

			  }*/
		
		/*
		$livefacilities = $this->model_syndb_syndb->getgetfacilitiesByMain($datamodel);
		
		//var_dump($livefacilities);die;
		
		if($livefacilities != null && $livefacilities != ""){
			$this->model_syndb_syndb->deletegetfacilitiesMain($datamodel);
		}
		

		$facilities = $this->model_syndb_syndb->getfacilities($datamodel);
		
		if($facilities != null && $facilities != ""){
			foreach($facilities as $facility){
				
				$this->newdb->query("INSERT INTO `" . NEWDB_PREFIX . "facilities` SET facilities_id = '" . $facility['facilities_id'] . "', timezone_id = '" . $facility['timezone_id'] . "', facility = '" . $facility['facility'] . "', password = '" . $facility['password'] . "', salt = '" . $facility['salt'] . "', firstname = '" . $facility['firstname'] . "', lastname = '" . $facility['lastname'] . "', email = '" . $facility['email'] . "', code = '".$facility['code']."', ip = '" . $facility['ip'] . "', status = '" . $facility['status'] . "', date_added = '" . $facility['date_added'] . "', description = '" . $facility['description'] . "', address = '" . $facility['address'] . "', location = '" . $facility['location'] . "', country_id = '" . $facility['country_id'] . "', zone_id = '" . $facility['zone_id'] . "', zipcode = '" . $facility['zipcode'] . "', users = '" . $facility['users'] . "', config_task_status = '" . $facility['config_task_status'] . "', config_tag_status = '" . $facility['config_tag_status'] . "', sms_number = '" . $facility['sms_number'] . "', config_taskform_status = '" . $facility['config_taskform_status'] . "', config_noteform_status = '" . $facility['config_noteform_status'] . "', config_rules_status = '" . $facility['config_rules_status'] . "', config_display_camera = '" . $facility['config_display_camera'] . "', latitude = '" . $facility['latitude'] . "', longitude = '" . $facility['longitude'] . "', config_display_dashboard = '" . $facility['config_display_dashboard'] . "', config_share_notes = '" . $facility['config_share_notes'] . "', config_sharepin_status = '" . $facility['config_sharepin_status'] . "', unique_id = '" . $config_unique_id . "' ");
			}
			 
			$activity_data1 = array(
				'data' => 'sync facilities data successfully in warehouse ',
			);
			$this->model_activity_activity->addActivity('facilities', $activity_data1);
			
		}else{
			$activity_data1 = array(
				'data' => 'no facilities data sync in warehouse because no data in given date',
			);
			$this->model_activity_activity->addActivity('facilities', $activity_data1);
			
		}
		
		//echo "11111111111111";
		
		$liveusers = $this->model_syndb_syndb->getgetusersByMain($datamodel);
		if($liveusers != null && $liveusers != ""){
			$this->model_syndb_syndb->deleteusersMain($datamodel);
		}
		
		$users = $this->model_syndb_syndb->getusers($datamodel);
		
		if($users != null && $users != ""){
			foreach($users as $user){
				
				$this->newdb->query("INSERT INTO `" . NEWDB_PREFIX . "user` SET user_id = '" . $user['user_id'] . "', user_group_id = '" . $user['user_group_id'] . "', username = '" . $user['username'] . "', password = '" . $user['password'] . "', salt = '" . $user['salt'] . "', firstname = '" . $user['firstname'] . "', lastname = '" . $user['lastname'] . "', email = '" . $user['email'] . "', code = '" . $user['code'] . "', ip = '" . $user['ip'] . "', status = '" . $user['status'] . "', date_added = '" . $user['date_added'] . "', user_pin = '" . $user['user_pin'] . "', facilities = '" . $user['facilities'] . "', phone_number = '" . $user['phone_number'] . "', parent_id = '" . $user['parent_id'] . "', activationKey = '" . $user['activationKey'] . "', default_facilities_id = '" . $user['default_facilities_id'] . "', default_highlighter_id = '" . $user['default_highlighter_id'] . "', default_color = '" . $user['default_color'] . "', user_otp = '" . $user['user_otp'] . "', message_sid = '" . $user['message_sid'] . "', facilities_display = '" . $user['facilities_display'] . "', unique_id = '" . $config_unique_id . "' ");
			}
			$activity_data2 = array(
				'data' => 'sync users data successfully in warehouse ',
			);
			$this->model_activity_activity->addActivity('users', $activity_data2);
			
		}else{
			
			$activity_data2 = array(
				'data' => 'no users data sync in warehouse because no data in given date',
			);
			$this->model_activity_activity->addActivity('users', $activity_data2);
		}
		//echo "222222222222222222";
		
		$livekeywords = $this->model_syndb_syndb->getkeywordsByMain($datamodel);
		if($livekeywords != null && $livekeywords != ""){
			$this->model_syndb_syndb->deletegetkeywordsMain($datamodel);
		}
		
		$keywords = $this->model_syndb_syndb->getKeywords($datamodel);
		
		if($keywords != null && $keywords != ""){
			foreach($keywords as $keyword){
				
				$sql = "INSERT INTO " . NEWDB_PREFIX . "keyword SET keyword_id = '" . $keyword['keyword_id'] . "', keyword_name = '" . $keyword['keyword_name'] . "', keyword_value = '" . $keyword['keyword_value'] . "', sort_order = '" . $keyword['sort_order'] . "', status = '" . $keyword['status'] . "', date_added = '" . $keyword['date_added'] . "', keyword_image = '" . $keyword['keyword_image'] . "', active_tag = '" . $keyword['active_tag'] . "', facilities_id = '" . $keyword['facilities_id'] . "', relation_keyword_id = '" . $keyword['relation_keyword_id'] . "', unique_id = '" . $config_unique_id . "' ";
				$this->newdb->query($sql);
			}
			$activity_data3 = array(
				'data' => 'sync keywords data successfully in warehouse ',
			);
			$this->model_activity_activity->addActivity('keywords', $activity_data3);
			
		}else{
			
			$activity_data3 = array(
				'data' => 'no keywords data sync in warehouse because no data in given date',
			);
			$this->model_activity_activity->addActivity('keywords', $activity_data3);
		}
		
		//echo "3333333333333333333";
		$livehighliters = $this->model_syndb_syndb->gethighlitersByMain($datamodel);
		if($livehighliters != null && $livehighliters != ""){
			$this->model_syndb_syndb->deletegethighlitersMain($datamodel);
		}
		
		$highliters = $this->model_syndb_syndb->gethighliters($datamodel);
		
		if($highliters != null && $highliters != ""){
			foreach($highliters as $highliter){
				
				$sql = "INSERT INTO " . NEWDB_PREFIX . "highlighter SET highlighter_id = '" . $highliter['highlighter_id'] . "', highlighter_name = '" . $highliter['highlighter_name'] . "', highlighter_value = '" . $highliter['highlighter_value'] . "', sort_order = '" . $highliter['sort_order'] . "', status = '" . $highliter['status'] . "',  date_added = '" . $highliter['date_added'] . "', highlighter_icon = '" . $highliter['highlighter_icon'] . "', unique_id = '" . $config_unique_id . "' ";
				$this->newdb->query($sql);
			}
			$activity_data4 = array(
				'data' => 'sync highliters data successfully in warehouse ',
			);
			$this->model_activity_activity->addActivity('highliters', $activity_data4);
			
		}else{
			
			$activity_data4 = array(
				'data' => 'no highliters data sync in warehouse because no data in given date',
			);
			$this->model_activity_activity->addActivity('highliters', $activity_data4);
		}
		
		*/
		
		
		//echo "4444444444444444444444";
		
		/*$livenotes = $this->model_syndb_syndb->getNotesByMain($datamodel);
		
		
		if($livenotes != null && $livenotes != ""){
			$this->model_syndb_syndb->deleteNotesMain($datamodel);
		}*/
		
		$notes = $this->model_syndb_syndb->getnotes($datamodel);
		 
		if($notes != null && $notes != ""){
			foreach($notes as $note1){
				$this->model_syndb_syndb->deleteNotesMain($note1['notes_id'], $config_unique_id);
			}
			
			
			$facilities = array();
			foreach($notes as $note){
				
				$facilities[] = $note['facilities_id'];
				$notes_description = $note['notes_description']; 
				
				$config_unique_id = $note['unique_id']; 
				
				$sql = "INSERT INTO `" . NEWDB_PREFIX . "notes` SET notes_id = '" . $note['notes_id'] . "', facilities_id = '" . $note['facilities_id'] . "', notes_description = '" . $this->db->escape($notes_description) . "', highlighter_id = '" . $note['highlighter_id'] . "', notes_pin = '" . $this->db->escape($note['notes_pin']) . "', notes_file = '" . $this->db->escape($note['notes_file']) . "', date_added = '" . $note['date_added'] . "', status = '".$note['status']."', user_id = '" . $this->db->escape($note['user_id']) . "', signature = '".$note['signature']."', signature_image = '" . $note['signature_image'] . "', notetime = '" . $note['notetime'] . "', note_date = '" . $note['note_date'] . "', text_color_cut = '".$note['text_color_cut']."',  text_color = '" .$note['text_color']. "',  strike_user_id = '".$this->db->escape($note['strike_user_id'])."', strike_date_added = '" .$note['strike_date_added']. "', strike_signature = '" .$note['strike_signature']. "', strike_signature_image = '" . $note['strike_signature_image'] . "', strike_pin = '" . $this->db->escape($note['strike_pin']) . "', global_utc_timezone = '" . $note['global_utc_timezone'] . "', keyword_file_url = '" . $note['keyword_file_url'] . "', highlighter_value = '" . $note['highlighter_value'] . "', keyword_file = '" . $note['keyword_file'] . "', taskadded = '" . $note['taskadded'] . "', task_time = '" . $note['task_time'] . "', assign_to = '" . $note['assign_to'] . "', emp_tag_id = '" . $note['emp_tag_id'] . "', notes_type = '" . $note['notes_type'] . "', checklist_status = '" . $note['checklist_status'] . "', snooze_time = '" . $note['snooze_time'] . "', snooze_dismiss = '" . $note['snooze_dismiss'] . "', send_sms = '" . $note['send_sms'] . "', send_email = '" . $note['send_email'] . "', notes_search_keword = '" . $note['notes_search_keword'] . "', unique_id = '" . $config_unique_id . "', strike_note_type = '" . $note['strike_note_type'] . "', audio_attach_url = '" . $note['audio_attach_url'] . "', task_type = '" . $note['task_type'] . "', tags_id = '" . $note['tags_id'] . "', update_date = '" . $note['update_date'] . "', medication_attach_url = '" . $note['medication_attach_url'] . "', is_private = '" . $note['is_private'] . "', is_private_strike = '" . $note['is_private_strike'] . "', assessment_id = '" . $note['assessment_id'] . "', review_notes = '" . $note['review_notes'] . "', share_notes = '" . $note['share_notes'] . "', rule_highlighter_task = '" . $note['rule_highlighter_task'] . "', rule_activenote_task = '" . $note['rule_activenote_task'] . "', rule_color_task = '" . $note['rule_color_task'] . "', rule_keyword_task = '" . $note['rule_keyword_task'] . "', is_offline = '" . $note['is_offline'] . "', notes_conut = '" . $note['notes_conut'] . "', tasktype = '" . $note['tasktype'] . "', visitor_log = '" . $note['visitor_log'] . "', task_id = '" . $note['task_id'] . "', task_date = '" . $note['task_date'] . "', parent_id = '" . $note['parent_id'] . "', end_perpetual_task = '" . $note['end_perpetual_task'] . "', recurrence = '" . $note['recurrence'] . "', customlistvalues_id = '" . $note['customlistvalues_id'] . "', generate_report = '" . $note['generate_report'] . "', is_android = '" . $note['is_android'] . "', is_census = '" . $note['is_census'] . "', is_tag = '" . $note['is_tag'] . "', form_type = '" . $note['form_type'] . "', tagstatus_id = '" . $note['tagstatus_id'] . "', task_group_by = '" . $note['task_group_by'] . "', end_task = '" . $note['end_task'] . "', form_snooze_dismiss = '" . $note['form_snooze_dismiss'] . "', form_send_sms = '" . $note['form_send_sms'] . "', form_send_email = '" . $note['form_send_email'] . "', form_snooze_time = '" . $note['form_snooze_time'] . "', form_create_task = '" . $note['form_create_task'] . "', form_alert_send_email = '" . $note['form_alert_send_email'] . "', form_alert_send_sms = '" . $note['form_alert_send_sms'] . "', is_archive = '" . $note['is_archive'] . "', phone_device_id = '" . $note['phone_device_id'] . "', original_task_time = '" . $note['original_task_time'] . "', is_forms = '" . $note['is_forms'] . "', is_reminder = '" . $note['is_reminder'] . "', form_trigger_snooze_dismiss = '" . $note['form_trigger_snooze_dismiss'] . "', user_file = '" . $note['user_file'] . "', is_user_face = '" . $note['is_user_face'] . "', is_approval_required_forms_id = '" . $note['is_approval_required_forms_id'] . "', is_casecount = '" . $note['is_casecount'] . "', device_unique_id = '" . $note['device_unique_id'] . "', sync_dashboard = '" . $note['sync_dashboard'] . "', strike_user_file = '" . $note['strike_user_file'] . "', strike_is_user_face = '" . $note['strike_is_user_face'] . "'
				
				ON DUPLICATE KEY UPDATE 
				
				facilities_id = '" . $note['facilities_id'] . "', notes_description = '" . $this->db->escape($notes_description) . "', highlighter_id = '" . $note['highlighter_id'] . "', notes_pin = '" . $note['notes_pin'] . "', notes_file = '" . $note['notes_file'] . "', date_added = '" . $note['date_added'] . "', status = '".$note['status']."', user_id = '" . $this->db->escape($note['user_id']) . "', signature = '".$note['signature']."', signature_image = '" . $note['signature_image'] . "', notetime = '" . $note['notetime'] . "', note_date = '" . $note['note_date'] . "', text_color_cut = '".$note['text_color_cut']."',  text_color = '" .$note['text_color']. "',  strike_user_id = '".$this->db->escape($note['strike_user_id'])."', strike_date_added = '" .$note['strike_date_added']. "', strike_signature = '" .$note['strike_signature']. "', strike_signature_image = '" . $note['strike_signature_image'] . "', strike_pin = '" . $note['strike_pin'] . "', global_utc_timezone = '" . $note['global_utc_timezone'] . "', keyword_file_url = '" . $note['keyword_file_url'] . "', highlighter_value = '" . $note['highlighter_value'] . "', keyword_file = '" . $note['keyword_file'] . "', taskadded = '" . $note['taskadded'] . "', task_time = '" . $note['task_time'] . "', assign_to = '" . $note['assign_to'] . "', emp_tag_id = '" . $note['emp_tag_id'] . "', notes_type = '" . $note['notes_type'] . "', checklist_status = '" . $note['checklist_status'] . "', snooze_time = '" . $note['snooze_time'] . "', snooze_dismiss = '" . $note['snooze_dismiss'] . "', send_sms = '" . $note['send_sms'] . "', send_email = '" . $note['send_email'] . "', notes_search_keword = '" . $note['notes_search_keword'] . "', unique_id = '" . $config_unique_id . "', strike_note_type = '" . $note['strike_note_type'] . "', audio_attach_url = '" . $note['audio_attach_url'] . "', task_type = '" . $note['task_type'] . "', tags_id = '" . $note['tags_id'] . "', update_date = '" . $note['update_date'] . "', medication_attach_url = '" . $note['medication_attach_url'] . "', is_private = '" . $note['is_private'] . "', is_private_strike = '" . $note['is_private_strike'] . "', assessment_id = '" . $note['assessment_id'] . "', review_notes = '" . $note['review_notes'] . "', share_notes = '" . $note['share_notes'] . "', rule_highlighter_task = '" . $note['rule_highlighter_task'] . "', rule_activenote_task = '" . $note['rule_activenote_task'] . "', rule_color_task = '" . $note['rule_color_task'] . "', rule_keyword_task = '" . $note['rule_keyword_task'] . "', is_offline = '" . $note['is_offline'] . "', notes_conut = '" . $note['notes_conut'] . "', tasktype = '" . $note['tasktype'] . "', visitor_log = '" . $note['visitor_log'] . "', task_id = '" . $note['task_id'] . "', task_date = '" . $note['task_date'] . "', parent_id = '" . $note['parent_id'] . "', end_perpetual_task = '" . $note['end_perpetual_task'] . "', recurrence = '" . $note['recurrence'] . "', customlistvalues_id = '" . $note['customlistvalues_id'] . "', generate_report = '" . $note['generate_report'] . "', is_android = '" . $note['is_android'] . "', is_census = '" . $note['is_census'] . "', is_tag = '" . $note['is_tag'] . "', form_type = '" . $note['form_type'] . "', tagstatus_id = '" . $note['tagstatus_id'] . "', task_group_by = '" . $note['task_group_by'] . "', end_task = '" . $note['end_task'] . "', form_snooze_dismiss = '" . $note['form_snooze_dismiss'] . "', form_send_sms = '" . $note['form_send_sms'] . "', form_send_email = '" . $note['form_send_email'] . "', form_snooze_time = '" . $note['form_snooze_time'] . "', form_create_task = '" . $note['form_create_task'] . "', form_alert_send_email = '" . $note['form_alert_send_email'] . "', form_alert_send_sms = '" . $note['form_alert_send_sms'] . "', is_archive = '" . $note['is_archive'] . "', phone_device_id = '" . $note['phone_device_id'] . "', original_task_time = '" . $note['original_task_time'] . "', is_forms = '" . $note['is_forms'] . "', is_reminder = '" . $note['is_reminder'] . "', form_trigger_snooze_dismiss = '" . $note['form_trigger_snooze_dismiss'] . "', user_file = '" . $note['user_file'] . "', is_user_face = '" . $note['is_user_face'] . "', is_approval_required_forms_id = '" . $note['is_approval_required_forms_id'] . "', is_casecount = '" . $note['is_casecount'] . "', device_unique_id = '" . $note['device_unique_id'] . "', sync_dashboard = '" . $note['sync_dashboard'] . "', strike_user_file = '" . $note['strike_user_file'] . "', strike_is_user_face = '" . $note['strike_is_user_face'] . "' ";
		
				$this->newdb->query($sql);
				
				$liveattachmentss = $this->model_syndb_syndb->getattachmentsByMain($note['notes_id'], $config_unique_id);
		
				if($liveattachmentss != null && $liveattachmentss != ""){
					$this->model_syndb_syndb->deletegetattachmentsMain($note['notes_id'], $config_unique_id);
				}
				
				$attachments = $this->model_syndb_syndb->getattahmentds($note['notes_id'], $config_unique_id);
				
				if($attachments != null && $attachments != ""){
					foreach($attachments as $attachment){
						
						$sql = "INSERT INTO " . NEWDB_PREFIX . "notes_media SET notes_media_id = '" . $attachment['notes_media_id'] . "', notes_file = '" . $attachment['notes_file'] . "', notes_id = '" . $attachment['notes_id'] . "', deleted = '" . $attachment['deleted'] . "', status = '" . $attachment['status'] . "', notes_media_extention = '" . $attachment['notes_media_extention'] . "', media_user_id = '" . $this->db->escape($attachment['media_user_id']) . "', media_date_added = '" . $attachment['media_date_added'] . "', media_signature = '" . $attachment['media_signature'] . "', media_signature_image = '" . $attachment['media_signature_image'] . "', media_pin = '" . $attachment['media_pin'] . "', update_media = '" . $attachment['update_media'] . "', unique_id = '" . $config_unique_id . "', notes_type = '" . $attachment['notes_type'] . "', audio_attach_url = '" . $attachment['audio_attach_url'] . "', audio_attach_type = '" . $attachment['audio_attach_type'] . "', audio_upload_file = '" . $attachment['audio_upload_file'] . "', facilities_id = '" . $attachment['facilities_id'] . "', speech_name = '" . $attachment['speech_name'] . "', is_updated = '" . $attachment['is_updated'] . "' , phone_device_id = '" . $attachment['phone_device_id'] . "' , is_android = '" . $attachment['is_android'] . "' , sync_dashboard = '" . $attachment['sync_dashboard'] . "' , user_file = '" . $attachment['user_file'] . "' , is_user_face = '" . $attachment['is_user_face'] . "' 
						ON DUPLICATE KEY UPDATE 
						notes_file = '" . $attachment['notes_file'] . "', notes_id = '" . $attachment['notes_id'] . "', deleted = '" . $attachment['deleted'] . "', status = '" . $attachment['status'] . "', notes_media_extention = '" . $attachment['notes_media_extention'] . "', media_user_id = '" . $this->db->escape($attachment['media_user_id']) . "', media_date_added = '" . $attachment['media_date_added'] . "', media_signature = '" . $attachment['media_signature'] . "', media_signature_image = '" . $attachment['media_signature_image'] . "', media_pin = '" . $attachment['media_pin'] . "', update_media = '" . $attachment['update_media'] . "', unique_id = '" . $config_unique_id . "', notes_type = '" . $attachment['notes_type'] . "', audio_attach_url = '" . $attachment['audio_attach_url'] . "', audio_attach_type = '" . $attachment['audio_attach_type'] . "', audio_upload_file = '" . $attachment['audio_upload_file'] . "', facilities_id = '" . $attachment['facilities_id'] . "', speech_name = '" . $attachment['speech_name'] . "', is_updated = '" . $attachment['is_updated'] . "' , phone_device_id = '" . $attachment['phone_device_id'] . "' , is_android = '" . $attachment['is_android'] . "' , sync_dashboard = '" . $attachment['sync_dashboard'] . "' , user_file = '" . $attachment['user_file'] . "' , is_user_face = '" . $attachment['is_user_face'] . "'    ";
						$this->newdb->query($sql);
					}
					$activity_data3 = array(
						'data' => 'sync Notes media data successfully in warehouse ',
					);
					$this->model_activity_activity->addActivity('notesmedia', $activity_data3);
					
				}
				
				$liveforms = $this->model_syndb_syndb->getformsByMain($note['notes_id'], $config_unique_id);
		
				if($liveforms != null && $liveforms != ""){
					$this->model_syndb_syndb->deletegetformsMain($note['notes_id'], $config_unique_id);
				}
				
				$forms = $this->model_syndb_syndb->getforms($note['notes_id'], $config_unique_id);
				 
				if($forms != null && $forms != ""){
					foreach($forms as $form){
						
						$sql = "INSERT INTO " . NEWDB_PREFIX . "forms SET forms_id = '" . $form['forms_id'] . "', form_type_id = '" . $form['form_type_id'] . "', form_type = '" . $form['form_type'] . "', form_description = '" . $this->db->escape($form['form_description']) . "', date_added = '" . $form['date_added'] . "', notes_id = '" . $form['notes_id'] . "', user_id = '" . $this->db->escape($form['user_id']) . "', signature = '" . $form['signature'] . "', notes_pin = '" . $form['notes_pin'] . "', form_date_added = '" . $form['form_date_added'] . "', incident_number = '" . $this->db->escape($form['incident_number']) . "', facilities_id = '" . $form['facilities_id'] . "', notes_type = '" . $form['notes_type'] . "', assessment_id = '" . $form['assessment_id'] . "', custom_form_type = '" . $form['custom_form_type'] . "', design_forms = '" . $this->db->escape($form['design_forms']) . "', date_updated = '" . $form['date_updated'] . "', unique_id = '" . $config_unique_id . "', upload_file = '" . $form['upload_file'] . "', tags_id = '" . $form['tags_id'] . "', parent_id = '" . $form['parent_id'] . "', is_discharge = '" . $form['is_discharge'] . "', tagstatus_id = '" . $form['tagstatus_id'] . "', rules_form_description = '" . $this->db->escape($form['rules_form_description']) . "', is_archive = '" . $this->db->escape($form['is_archive']) . "', is_final = '" . $this->db->escape($form['is_final']) . "', is_approval_required = '" . $this->db->escape($form['is_approval_required']) . "', is_approved = '" . $this->db->escape($form['is_approved']) . "', phone_device_id = '" . $this->db->escape($form['phone_device_id']) . "', is_android = '" . $this->db->escape($form['is_android']) . "', form_parent_id = '" . $this->db->escape($form['form_parent_id']) . "', form_design_parent_id = '" . $this->db->escape($form['form_design_parent_id']) . "', page_number = '" . $this->db->escape($form['page_number']) . "', status = '" . $this->db->escape($form['status']) . "', sync_dashboard = '" . $this->db->escape($form['sync_dashboard']) . "', user_file = '" . $this->db->escape($form['user_file']) . "', is_user_face = '" . $this->db->escape($form['is_user_face']) . "'
						ON DUPLICATE KEY UPDATE 
						form_type_id = '" . $form['form_type_id'] . "', form_type = '" . $form['form_type'] . "', form_description = '" . $this->db->escape($form['form_description']) . "', date_added = '" . $form['date_added'] . "', notes_id = '" . $form['notes_id'] . "', user_id = '" . $this->db->escape($form['user_id']) . "', signature = '" . $form['signature'] . "', notes_pin = '" . $form['notes_pin'] . "', form_date_added = '" . $form['form_date_added'] . "', incident_number = '" . $this->db->escape($form['incident_number']) . "', facilities_id = '" . $form['facilities_id'] . "', notes_type = '" . $form['notes_type'] . "', assessment_id = '" . $form['assessment_id'] . "', custom_form_type = '" . $form['custom_form_type'] . "', design_forms = '" . $this->db->escape($form['design_forms']) . "', date_updated = '" . $form['date_updated'] . "', unique_id = '" . $config_unique_id . "', upload_file = '" . $form['upload_file'] . "', tags_id = '" . $form['tags_id'] . "', parent_id = '" . $form['parent_id'] . "', is_discharge = '" . $form['is_discharge'] . "', tagstatus_id = '" . $form['tagstatus_id'] . "', rules_form_description = '" . $this->db->escape($form['rules_form_description']) . "', is_archive = '" . $this->db->escape($form['is_archive']) . "', is_final = '" . $this->db->escape($form['is_final']) . "', is_approval_required = '" . $this->db->escape($form['is_approval_required']) . "', is_approved = '" . $this->db->escape($form['is_approved']) . "', phone_device_id = '" . $this->db->escape($form['phone_device_id']) . "', is_android = '" . $this->db->escape($form['is_android']) . "', form_parent_id = '" . $this->db->escape($form['form_parent_id']) . "', form_design_parent_id = '" . $this->db->escape($form['form_design_parent_id']) . "', page_number = '" . $this->db->escape($form['page_number']) . "', status = '" . $this->db->escape($form['status']) . "', sync_dashboard = '" . $this->db->escape($form['sync_dashboard']) . "', user_file = '" . $this->db->escape($form['user_file']) . "', is_user_face = '" . $this->db->escape($form['is_user_face']) . "' ";
						$this->newdb->query($sql);
					}
					$activity_data3 = array(
						'data' => 'sync forms data successfully in warehouse ',
					);
					$this->model_activity_activity->addActivity('forms', $activity_data3);
					
				}
				
					
					$livetaskforms = $this->model_syndb_syndb->gettaskformsByMain($note['notes_id'], $config_unique_id);
		
					if($livetaskforms != null && $livetaskforms != ""){
						$this->model_syndb_syndb->deletegettaskformsMain($note['notes_id'], $config_unique_id);
					}
					
					$taskforms = $this->model_syndb_syndb->gettaskforms($note['notes_id'], $config_unique_id);
					 
					if($taskforms != null && $taskforms != ""){
						foreach($taskforms as $taskform){
							
							$sql = "INSERT INTO " . NEWDB_PREFIX . "notes_by_task SET notes_by_task_id = '" . $taskform['notes_by_task_id'] . "', notes_id = '" . $taskform['notes_id'] . "', locations_id = '" . $taskform['locations_id'] . "', task_type = '" . $taskform['task_type'] . "', task_content = '" . $this->db->escape($taskform['task_content']) . "', user_id = '" . $this->db->escape($taskform['user_id']) . "', date_added = '" . $taskform['date_added'] . "', signature = '" . $taskform['signature'] . "', notes_pin = '" . $taskform['notes_pin'] . "', notes_type = '" . $taskform['notes_type'] . "', task_time = '" . $taskform['task_time'] . "', media_url = '" . $taskform['media_url'] . "', capacity = '" . $taskform['capacity'] . "', location_name = '" . $this->db->escape($taskform['location_name']) . "', location_type = '" . $taskform['location_type'] . "', notes_task_type = '" . $taskform['notes_task_type'] . "', tags_id = '" . $taskform['tags_id'] . "', drug_name = '" . $this->db->escape($taskform['drug_name']) . "', dose = '" . $taskform['dose'] . "', drug_type = '" . $taskform['drug_type'] . "', quantity = '" . $taskform['quantity'] . "', frequency = '" . $taskform['frequency'] . "', instructions = '" . $this->db->escape($taskform['instructions']) . "', count = '" . $taskform['count'] . "', createtask_by_group_id = '" . $taskform['createtask_by_group_id'] . "', task_comments = '" . $this->db->escape($taskform['task_comments']) . "', medication_attach_url = '" . $taskform['medication_attach_url'] . "', medication_file_upload = '" . $taskform['medication_file_upload'] . "', unique_id = '" . $config_unique_id . "' , facilities_id = '" . $taskform['facilities_id'] . "', tags_medication_id = '" . $taskform['tags_medication_id'] . "', tags_medication_details_id = '" . $taskform['tags_medication_details_id'] . "', task_customlistvalues_id = '" . $taskform['task_customlistvalues_id'] . "', tags_ids = '" . $taskform['tags_ids'] . "', room_current_date_time = '" . $taskform['room_current_date_time'] . "', complete_status = '" . $taskform['complete_status'] . "', role_call = '" . $taskform['role_call'] . "', out_tags_ids = '" . $taskform['out_tags_ids'] . "', out_capacity = '" . $taskform['out_capacity'] . "', sync_dashboard = '" . $taskform['sync_dashboard'] . "'
							ON DUPLICATE KEY UPDATE 
							notes_id = '" . $taskform['notes_id'] . "', locations_id = '" . $taskform['locations_id'] . "', task_type = '" . $taskform['task_type'] . "', task_content = '" . $this->db->escape($taskform['task_content']) . "', user_id = '" . $this->db->escape($taskform['user_id']) . "', date_added = '" . $taskform['date_added'] . "', signature = '" . $taskform['signature'] . "', notes_pin = '" . $taskform['notes_pin'] . "', notes_type = '" . $taskform['notes_type'] . "', task_time = '" . $taskform['task_time'] . "', media_url = '" . $taskform['media_url'] . "', capacity = '" . $taskform['capacity'] . "', location_name = '" . $this->db->escape($taskform['location_name']) . "', location_type = '" . $taskform['location_type'] . "', notes_task_type = '" . $taskform['notes_task_type'] . "', tags_id = '" . $taskform['tags_id'] . "', drug_name = '" . $this->db->escape($taskform['drug_name']) . "', dose = '" . $taskform['dose'] . "', drug_type = '" . $taskform['drug_type'] . "', quantity = '" . $taskform['quantity'] . "', frequency = '" . $taskform['frequency'] . "', instructions = '" . $this->db->escape($taskform['instructions']) . "', count = '" . $taskform['count'] . "', createtask_by_group_id = '" . $taskform['createtask_by_group_id'] . "', task_comments = '" . $this->db->escape($taskform['task_comments']) . "', medication_attach_url = '" . $taskform['medication_attach_url'] . "', medication_file_upload = '" . $taskform['medication_file_upload'] . "', unique_id = '" . $config_unique_id . "', facilities_id = '" . $taskform['facilities_id'] . "', tags_medication_id = '" . $taskform['tags_medication_id'] . "', tags_medication_details_id = '" . $taskform['tags_medication_details_id'] . "', task_customlistvalues_id = '" . $taskform['task_customlistvalues_id'] . "', tags_ids = '" . $taskform['tags_ids'] . "', room_current_date_time = '" . $taskform['room_current_date_time'] . "', complete_status = '" . $taskform['complete_status'] . "', role_call = '" . $taskform['role_call'] . "', out_tags_ids = '" . $taskform['out_tags_ids'] . "', out_capacity = '" . $taskform['out_capacity'] . "', sync_dashboard = '" . $taskform['sync_dashboard'] . "' ";
							$this->newdb->query($sql);
						}
						$activity_data3 = array(
							'data' => 'sync task form data successfully in warehouse ',
						);
						$this->model_activity_activity->addActivity('taskforms', $activity_data3);
						
					}
					
					
					$livetags = $this->model_syndb_syndb->gettagsByMain($note['notes_id'], $config_unique_id);
		
					if($livetags != null && $livetags != ""){
						$this->model_syndb_syndb->deletegettagsMain($note['notes_id'], $config_unique_id);
					}
					
					$ntags = $this->model_syndb_syndb->gettags($note['notes_id'], $config_unique_id);
					 
					if($ntags != null && $ntags != ""){
						foreach($ntags as $ntag){
							
							$sql = "INSERT INTO " . NEWDB_PREFIX . "notes_tags SET notes_tags_id = '" . $ntag['notes_tags_id'] . "', emp_tag_id = '" . $ntag['emp_tag_id'] . "', tags_id = '" . $ntag['tags_id'] . "', notes_id = '" . $ntag['notes_id'] . "', user_id = '" . $ntag['user_id'] . "', date_added = '" . $ntag['date_added'] . "', signature = '" . $ntag['signature'] . "', signature_image = '" . $ntag['signature_image'] . "', notes_pin = '" . $ntag['notes_pin'] . "', notes_type = '" . $ntag['notes_type'] . "', unique_id = '" . $config_unique_id . "' , facilities_id = '" . $ntag['facilities_id'] . "', is_census = '" . $ntag['is_census'] . "', lunch = '" . $ntag['lunch'] . "', dinner = '" . $ntag['dinner'] . "', breakfast = '" . $ntag['breakfast'] . "', refused = '" . $ntag['refused'] . "', phone_device_id = '" . $ntag['phone_device_id'] . "', is_android = '" . $ntag['is_android'] . "', forms_id = '" . $ntag['forms_id'] . "', user_file = '" . $ntag['user_file'] . "', is_user_face = '" . $ntag['is_user_face'] . "'
							ON DUPLICATE KEY UPDATE 
							emp_tag_id = '" . $ntag['emp_tag_id'] . "', tags_id = '" . $ntag['tags_id'] . "', notes_id = '" . $ntag['notes_id'] . "', user_id = '" . $ntag['user_id'] . "', date_added = '" . $ntag['date_added'] . "', signature = '" . $ntag['signature'] . "', signature_image = '" . $ntag['signature_image'] . "', notes_pin = '" . $ntag['notes_pin'] . "', notes_type = '" . $ntag['notes_type'] . "', unique_id = '" . $config_unique_id . "' , facilities_id = '" . $ntag['facilities_id'] . "', is_census = '" . $ntag['is_census'] . "', lunch = '" . $ntag['lunch'] . "', dinner = '" . $ntag['dinner'] . "', breakfast = '" . $ntag['breakfast'] . "', refused = '" . $ntag['refused'] . "', phone_device_id = '" . $ntag['phone_device_id'] . "', is_android = '" . $ntag['is_android'] . "', forms_id = '" . $ntag['forms_id'] . "', user_file = '" . $ntag['user_file'] . "', is_user_face = '" . $ntag['is_user_face'] . "' ";
							$this->newdb->query($sql);
						}
						$activity_data3 = array(
							'data' => 'sync notes tags data successfully in warehouse ',
						);
						$this->model_activity_activity->addActivity('notestags', $activity_data3);
						
					}
					
					
					
					$livenoteskeywords = $this->model_syndb_syndb->getnoteskeywordsByMain($note['notes_id'], $config_unique_id);
		
					if($livenoteskeywords != null && $livenoteskeywords != ""){
						$this->model_syndb_syndb->deletenoteskeywordsMain($note['notes_id'], $config_unique_id);
					}
					
					$noteskeywords = $this->model_syndb_syndb->genoteskeywords($note['notes_id'], $config_unique_id);
					 
					if($noteskeywords != null && $noteskeywords != ""){
						foreach($noteskeywords as $noteskeyword){
							
							$sql = "INSERT INTO " . NEWDB_PREFIX . "notes_by_keyword SET notes_by_keyword_id = '" . $noteskeyword['notes_by_keyword_id'] . "', notes_id = '" . $noteskeyword['notes_id'] . "', keyword_id = '" . $noteskeyword['keyword_id'] . "', keyword_name = '" . $this->db->escape($noteskeyword['keyword_name']) . "', keyword_file = '" . $noteskeyword['keyword_file'] . "', keyword_file_url = '" . $noteskeyword['keyword_file_url'] . "', keyword_status = '" . $noteskeyword['keyword_status'] . "', active_tag = '" . $noteskeyword['active_tag'] . "', facilities_id = '" . $noteskeyword['facilities_id'] . "', date_added = '" . $noteskeyword['date_added'] . "', unique_id = '" . $config_unique_id . "' , is_monitor_time = '" . $noteskeyword['is_monitor_time'] . "' , user_id = '" . $noteskeyword['user_id'] . "' , override_monitor_time_user_id = '" . $noteskeyword['override_monitor_time_user_id'] . "', sync_dashboard = '" . $noteskeyword['sync_dashboard'] . "'
							ON DUPLICATE KEY UPDATE 
							notes_id = '" . $noteskeyword['notes_id'] . "', keyword_id = '" . $noteskeyword['keyword_id'] . "', keyword_name = '" . $this->db->escape($noteskeyword['keyword_name']) . "', keyword_file = '" . $noteskeyword['keyword_file'] . "', keyword_file_url = '" . $noteskeyword['keyword_file_url'] . "', keyword_status = '" . $noteskeyword['keyword_status'] . "', active_tag = '" . $noteskeyword['active_tag'] . "', facilities_id = '" . $noteskeyword['facilities_id'] . "', date_added = '" . $noteskeyword['date_added'] . "', unique_id = '" . $config_unique_id . "', is_monitor_time = '" . $noteskeyword['is_monitor_time'] . "' , user_id = '" . $noteskeyword['user_id'] . "' , override_monitor_time_user_id = '" . $noteskeyword['override_monitor_time_user_id'] . "', sync_dashboard = '" . $noteskeyword['sync_dashboard'] . "'
							";
							$this->newdb->query($sql);
						}
						$activity_data3 = array(
							'data' => 'sync notes keyword data successfully in warehouse ',
						);
						$this->model_activity_activity->addActivity('notestags', $activity_data3);
						
					}
					
					
					
					
					$notescensus_detail = $this->model_syndb_syndb->genotesscensus_details($note['notes_id'], $config_unique_id);
					 
					if($notescensus_detail != null && $notescensus_detail != ""){
							
						$sql = "INSERT INTO " . NEWDB_PREFIX . "notes_census_detail SET notes_census_detail_id = '" . $notescensus_detail['notes_census_detail_id'] . "', notes_id = '" . $notescensus_detail['notes_id'] . "', tags_id = '" . $notescensus_detail['tags_id'] . "', shift_id = '" . $notescensus_detail['shift_id'] . "', date_added = '" . $notescensus_detail['date_added'] . "', census_date = '" . $notescensus_detail['census_date'] . "', team_leader = '" . $this->db->escape($notescensus_detail['team_leader']) . "', direct_care = '" . $this->db->escape($notescensus_detail['direct_care']) . "', comment_box = '" . $this->db->escape($notescensus_detail['comment_box']) . "', spm = '" . $this->db->escape($notescensus_detail['spm']) . "', as_spm = '" . $this->db->escape($notescensus_detail['as_spm']) . "', case_manager = '" . $this->db->escape($notescensus_detail['case_manager']) . "', food_services = '" . $this->db->escape($notescensus_detail['food_services']) . "', educational_staff = '" . $this->db->escape($notescensus_detail['educational_staff']) . "', screenings = '" . $this->db->escape($notescensus_detail['screenings']) . "', intakes = '" . $this->db->escape($notescensus_detail['intakes']) . "', discharge = '" . $this->db->escape($notescensus_detail['discharge']) . "', offsite = '" . $this->db->escape($notescensus_detail['offsite']) . "', in_house = '" . $this->db->escape($notescensus_detail['in_house']) . "', males = '" . $this->db->escape($notescensus_detail['males']) . "', females = '" . $this->db->escape($notescensus_detail['females']) . "', total = '" . $this->db->escape($notescensus_detail['total']) . "', end_of_shift_status = '" . $this->db->escape($notescensus_detail['end_of_shift_status']) . "', staff = '" . $this->db->escape($notescensus_detail['staff']) . "', facilities_id = '" . $notescensus_detail['facilities_id'] . "', unique_id = '" . $config_unique_id . "' 
						ON DUPLICATE KEY UPDATE 
						notes_id = '" . $notescensus_detail['notes_id'] . "', tags_id = '" . $notescensus_detail['tags_id'] . "', shift_id = '" . $notescensus_detail['shift_id'] . "', date_added = '" . $notescensus_detail['date_added'] . "', census_date = '" . $notescensus_detail['census_date'] . "', team_leader = '" . $this->db->escape($notescensus_detail['team_leader']) . "', direct_care = '" . $this->db->escape($notescensus_detail['direct_care']) . "', comment_box = '" . $this->db->escape($notescensus_detail['comment_box']) . "', spm = '" . $this->db->escape($notescensus_detail['spm']) . "', as_spm = '" . $this->db->escape($notescensus_detail['as_spm']) . "', case_manager = '" . $this->db->escape($notescensus_detail['case_manager']) . "', food_services = '" . $this->db->escape($notescensus_detail['food_services']) . "', educational_staff = '" . $this->db->escape($notescensus_detail['educational_staff']) . "', screenings = '" . $this->db->escape($notescensus_detail['screenings']) . "', intakes = '" . $this->db->escape($notescensus_detail['intakes']) . "', discharge = '" . $this->db->escape($notescensus_detail['discharge']) . "', offsite = '" . $this->db->escape($notescensus_detail['offsite']) . "', in_house = '" . $this->db->escape($notescensus_detail['in_house']) . "', males = '" . $this->db->escape($notescensus_detail['males']) . "', females = '" . $this->db->escape($notescensus_detail['females']) . "', total = '" . $this->db->escape($notescensus_detail['total']) . "', end_of_shift_status = '" . $this->db->escape($notescensus_detail['end_of_shift_status']) . "', staff = '" . $this->db->escape($notescensus_detail['staff']) . "', facilities_id = '" . $notescensus_detail['facilities_id'] . "', unique_id = '" . $config_unique_id . "' 
							";
						$this->newdb->query($sql);
						
						$activity_data3 = array(
							'data' => 'sync notes_census_detail data successfully in warehouse ',
						);
						$this->model_activity_activity->addActivity('notes_census_detail', $activity_data3);
						
					}
					
					
					
					$livesharenotes = $this->model_syndb_syndb->getsharenotesByMain($note['notes_id'], $config_unique_id);
		
					if($livesharenotes != null && $livesharenotes != ""){
						$this->model_syndb_syndb->deletesharenotesMain($note['notes_id'], $config_unique_id);
					}
					
					$sharenotes = $this->model_syndb_syndb->gesharenotes($note['notes_id'], $config_unique_id);
					 
					if($sharenotes != null && $sharenotes != ""){
						foreach($sharenotes as $sharenote){
							
							$sql = "INSERT INTO " . NEWDB_PREFIX . "share_notes SET share_notes_id = '" . $sharenote['share_notes_id'] . "', notes_id = '" . $sharenote['notes_id'] . "', user_id = '" . $this->db->escape($sharenote['user_id']) . "', notes_pin = '" . $sharenote['notes_pin'] . "', email = '" . $sharenote['email'] . "', date_added = '" . $sharenote['date_added'] . "', share_type = '" . $sharenote['share_type'] . "', share_notes_otp = '" . $sharenote['share_notes_otp'] . "', phone_device_id = '" . $sharenote['phone_device_id'] . "', device_unique_id = '" . $sharenote['device_unique_id'] . "', is_android = '" . $sharenote['is_android'] . "'
							
							, unique_id = '" . $config_unique_id . "'
							ON DUPLICATE KEY UPDATE 
							notes_id = '" . $sharenote['notes_id'] . "', user_id = '" . $this->db->escape($sharenote['user_id']) . "', notes_pin = '" . $sharenote['notes_pin'] . "', email = '" . $sharenote['email'] . "', date_added = '" . $sharenote['date_added'] . "', share_type = '" . $sharenote['share_type'] . "', share_notes_otp = '" . $sharenote['share_notes_otp'] . "', phone_device_id = '" . $sharenote['phone_device_id'] . "', device_unique_id = '" . $sharenote['device_unique_id'] . "', is_android = '" . $sharenote['is_android'] . "'
							
							, unique_id = '" . $config_unique_id . "' ";
							$this->newdb->query($sql);
						}
						$activity_data3 = array(
							'data' => 'sync share notes data successfully in warehouse ',
						);
						$this->model_activity_activity->addActivity('sharenotes', $activity_data3);
						
					}
					
					
					
					$liveapproval_tasknotes = $this->model_syndb_syndb->getapproval_tasknotesByMain($note['notes_id'], $config_unique_id);
		
					if($liveapproval_tasknotes != null && $liveapproval_tasknotes != ""){
						$this->model_syndb_syndb->deleteapproval_tasknotesMain($note['notes_id'], $config_unique_id);
					}
					
					$approval_tasknotes = $this->model_syndb_syndb->geapproval_tasknotes($note['notes_id'], $config_unique_id);
					 
					if($approval_tasknotes != null && $approval_tasknotes != ""){
						foreach($approval_tasknotes as $approval_tasknote){
							
							$sql = "INSERT INTO " . NEWDB_PREFIX . "notes_by_approval_task SET 
							id = '" . $approval_tasknote['id'] . "'
							, facilities_id = '" . $approval_tasknote['facilities_id'] . "'
							, task_date = '" . $this->db->escape($approval_tasknote['task_date']) . "'
							, task_time = '" . $this->db->escape($approval_tasknote['task_time']) . "'
							, date_added = '" . $this->db->escape($approval_tasknote['date_added']) . "'
							, tasktype = '" . $this->db->escape($approval_tasknote['tasktype']) . "'
							, description = '" . $this->db->escape($approval_tasknote['description']) . "'
							, assign_to = '" . $this->db->escape($approval_tasknote['assign_to']) . "'
							
							, recurrence = '" . $this->db->escape($approval_tasknote['recurrence']) . "'
							, end_recurrence_date = '" . $this->db->escape($approval_tasknote['end_recurrence_date']) . "'
							, recurnce_hrly = '" . $this->db->escape($approval_tasknote['recurnce_hrly']) . "'
							, recurnce_week = '" . $this->db->escape($approval_tasknote['recurnce_week']) . "'
							, recurnce_month = '" . $this->db->escape($approval_tasknote['recurnce_month']) . "'
							, recurnce_day = '" . $this->db->escape($approval_tasknote['recurnce_day']) . "'
							, taskadded = '" . $this->db->escape($approval_tasknote['taskadded']) . "'
							, endtime = '" . $this->db->escape($approval_tasknote['endtime']) . "'
							, task_alert = '" . $this->db->escape($approval_tasknote['task_alert']) . "'
							, alert_type_none = '" . $this->db->escape($approval_tasknote['alert_type_none']) . "'
							, alert_type_sms = '" . $this->db->escape($approval_tasknote['alert_type_sms']) . "'
							, alert_type_notification = '" . $this->db->escape($approval_tasknote['alert_type_notification']) . "'
							, alert_type_email = '" . $this->db->escape($approval_tasknote['alert_type_email']) . "'
							, checklist = '" . $this->db->escape($approval_tasknote['checklist']) . "'
							, snooze_time = '" . $this->db->escape($approval_tasknote['snooze_time']) . "'
							, snooze_dismiss = '" . $this->db->escape($approval_tasknote['snooze_dismiss']) . "'
							
							, rules_task = '" . $this->db->escape($approval_tasknote['rules_task']) . "'
							, message_sid = '" . $this->db->escape($approval_tasknote['message_sid']) . "'
							, send_sms = '" . $this->db->escape($approval_tasknote['send_sms']) . "'
							, send_email = '" . $this->db->escape($approval_tasknote['send_email']) . "'
							, send_notification = '" . $this->db->escape($approval_tasknote['send_notification']) . "'
							, task_form_id = '" . $this->db->escape($approval_tasknote['task_form_id']) . "'
							, tags_id = '" . $this->db->escape($approval_tasknote['tags_id']) . "'
							, pickup_facilities_id = '" . $this->db->escape($approval_tasknote['pickup_facilities_id']) . "'
							, pickup_locations_address = '" . $this->db->escape($approval_tasknote['pickup_locations_address']) . "'
							, pickup_locations_time = '" . $this->db->escape($approval_tasknote['pickup_locations_time']) . "'
							, pickup_locations_latitude = '" . $this->db->escape($approval_tasknote['pickup_locations_latitude']) . "'
							, pickup_locations_longitude = '" . $this->db->escape($approval_tasknote['pickup_locations_longitude']) . "'
							, dropoff_facilities_id = '" . $this->db->escape($approval_tasknote['dropoff_facilities_id']) . "'
							, dropoff_locations_address = '" . $this->db->escape($approval_tasknote['dropoff_locations_address']) . "'
							, dropoff_locations_time = '" . $this->db->escape($approval_tasknote['dropoff_locations_time']) . "'
							
							, dropoff_locations_latitude = '" . $this->db->escape($approval_tasknote['dropoff_locations_latitude']) . "'
							, dropoff_locations_longitude = '" . $this->db->escape($approval_tasknote['dropoff_locations_longitude']) . "'
							, transport_tags = '" . $this->db->escape($approval_tasknote['transport_tags']) . "'
							, locations_id = '" . $this->db->escape($approval_tasknote['locations_id']) . "'
							, task_complettion = '" . $this->db->escape($approval_tasknote['task_complettion']) . "'
							, device_id = '" . $this->db->escape($approval_tasknote['device_id']) . "'
							, customs_forms_id = '" . $this->db->escape($approval_tasknote['customs_forms_id']) . "'
							, emp_tag_id = '" . $this->db->escape($approval_tasknote['emp_tag_id']) . "'
							, medication_tags = '" . $this->db->escape($approval_tasknote['medication_tags']) . "'
							, completion_alert = '" . $this->db->escape($approval_tasknote['completion_alert']) . "'
							, completion_alert_type_sms = '" . $this->db->escape($approval_tasknote['completion_alert_type_sms']) . "'
							, completion_alert_type_email = '" . $this->db->escape($approval_tasknote['completion_alert_type_email']) . "'
							, user_roles = '" . $this->db->escape($approval_tasknote['user_roles']) . "'
							, userids = '" . $this->db->escape($approval_tasknote['userids']) . "'
							, recurnce_hrly_perpetual = '" . $this->db->escape($approval_tasknote['recurnce_hrly_perpetual']) . "'
							, due_date_time = '" . $this->db->escape($approval_tasknote['due_date_time']) . "'
							, task_status = '" . $this->db->escape($approval_tasknote['task_status']) . "'
							, task_completed = '" . $this->db->escape($approval_tasknote['task_completed']) . "'
							, recurnce_hrly_recurnce = '" . $this->db->escape($approval_tasknote['recurnce_hrly_recurnce']) . "'
							, visitation_tags = '" . $this->db->escape($approval_tasknote['visitation_tags']) . "'
							, visitation_tag_id = '" . $this->db->escape($approval_tasknote['visitation_tag_id']) . "'
							, visitation_start_facilities_id = '" . $this->db->escape($approval_tasknote['visitation_start_facilities_id']) . "'
							, visitation_start_address = '" . $this->db->escape($approval_tasknote['visitation_start_address']) . "'
							, visitation_start_time = '" . $this->db->escape($approval_tasknote['visitation_start_time']) . "'
							, visitation_start_address_latitude = '" . $this->db->escape($approval_tasknote['visitation_start_address_latitude']) . "'
							, visitation_start_address_longitude = '" . $this->db->escape($approval_tasknote['visitation_start_address_longitude']) . "'
							, visitation_appoitment_facilities_id = '" . $this->db->escape($approval_tasknote['visitation_appoitment_facilities_id']) . "'
							, visitation_appoitment_address = '" . $this->db->escape($approval_tasknote['visitation_appoitment_address']) . "'							
							, visitation_appoitment_time = '" . $this->db->escape($approval_tasknote['visitation_appoitment_time']) . "'
							, visitation_appoitment_address_latitude = '" . $this->db->escape($approval_tasknote['visitation_appoitment_address_latitude']) . "'
							, visitation_appoitment_address_longitude = '" . $this->db->escape($approval_tasknote['visitation_appoitment_address_longitude']) . "'
							, completed_times = '" . $this->db->escape($approval_tasknote['completed_times']) . "'							
							, completed_alert = '" . $this->db->escape($approval_tasknote['completed_alert']) . "'
							, completed_late_alert = '" . $this->db->escape($approval_tasknote['completed_late_alert']) . "'
							, incomplete_alert = '" . $this->db->escape($approval_tasknote['incomplete_alert']) . "'
							, deleted_alert = '" . $this->db->escape($approval_tasknote['deleted_alert']) . "'
							, end_perpetual_task = '" . $this->db->escape($approval_tasknote['end_perpetual_task']) . "'
							, is_transport = '" . $this->db->escape($approval_tasknote['is_transport']) . "'
							, parent_id = '" . $this->db->escape($approval_tasknote['parent_id']) . "'
							, is_send_reminder = '" . $this->db->escape($approval_tasknote['is_send_reminder']) . "'
							, attachement_form = '" . $this->db->escape($approval_tasknote['attachement_form']) . "'
							, tasktype_form_id = '" . $this->db->escape($approval_tasknote['tasktype_form_id']) . "'
							, tagstatus_id = '" . $this->db->escape($approval_tasknote['tagstatus_id']) . "'
							, task_group_by = '" . $this->db->escape($approval_tasknote['task_group_by']) . "'
							, end_task = '" . $this->db->escape($approval_tasknote['end_task']) . "'
							, formrules_id = '" . $this->db->escape($approval_tasknote['formrules_id']) . "'
							, task_random_id = '" . $this->db->escape($approval_tasknote['task_random_id']) . "'
							, form_due_date = '" . $this->db->escape($approval_tasknote['form_due_date']) . "'
							, form_due_date_after = '" . $this->db->escape($approval_tasknote['form_due_date_after']) . "'
							, recurnce_m = '" . $this->db->escape($approval_tasknote['recurnce_m']) . "'
							, phone_device_id = '" . $this->db->escape($approval_tasknote['phone_device_id']) . "'
							, enable_requires_approval = '" . $this->db->escape($approval_tasknote['enable_requires_approval']) . "'
							, approval_taskid = '" . $this->db->escape($approval_tasknote['approval_taskid']) . "'
							, notes_id = '" . $this->db->escape($approval_tasknote['notes_id']) . "'
							, status = '" . $this->db->escape($approval_tasknote['status']) . "'
							
							, iswaypoint = '" . $this->db->escape($approval_tasknote['iswaypoint']) . "'
							, original_task_time = '" . $this->db->escape($approval_tasknote['original_task_time']) . "'
							
							, response = '" . $this->db->escape($approval_tasknote['response']) . "'
							, distance_text = '" . $this->db->escape($approval_tasknote['distance_text']) . "'
							, distance_value = '" . $this->db->escape($approval_tasknote['distance_value']) . "'
							, duration_text = '" . $this->db->escape($approval_tasknote['duration_text']) . "'
							, duration_value = '" . $this->db->escape($approval_tasknote['duration_value']) . "'
							
							, bed_check_location_ids = '" . $this->db->escape($approval_tasknote['bed_check_location_ids']) . "'
							
							, is_approval_required_forms_id = '" . $this->db->escape($approval_tasknote['is_approval_required_forms_id']) . "'
							, is_approval_required_tags_id = '" . $this->db->escape($approval_tasknote['is_approval_required_tags_id']) . "'
							, is_android = '" . $this->db->escape($approval_tasknote['is_android']) . "'
							, unique_id = '" . $config_unique_id . "'
							 
							 ON DUPLICATE KEY UPDATE  
							 
							 facilities_id = '" . $approval_tasknote['facilities_id'] . "'
							, task_date = '" . $this->db->escape($approval_tasknote['task_date']) . "'
							, task_time = '" . $this->db->escape($approval_tasknote['task_time']) . "'
							, date_added = '" . $this->db->escape($approval_tasknote['date_added']) . "'
							, tasktype = '" . $this->db->escape($approval_tasknote['tasktype']) . "'
							, description = '" . $this->db->escape($approval_tasknote['description']) . "'
							, assign_to = '" . $this->db->escape($approval_tasknote['assign_to']) . "'
							
							, recurrence = '" . $this->db->escape($approval_tasknote['recurrence']) . "'
							, end_recurrence_date = '" . $this->db->escape($approval_tasknote['end_recurrence_date']) . "'
							, recurnce_hrly = '" . $this->db->escape($approval_tasknote['recurnce_hrly']) . "'
							, recurnce_week = '" . $this->db->escape($approval_tasknote['recurnce_week']) . "'
							, recurnce_month = '" . $this->db->escape($approval_tasknote['recurnce_month']) . "'
							, recurnce_day = '" . $this->db->escape($approval_tasknote['recurnce_day']) . "'
							, taskadded = '" . $this->db->escape($approval_tasknote['taskadded']) . "'
							, endtime = '" . $this->db->escape($approval_tasknote['endtime']) . "'
							, task_alert = '" . $this->db->escape($approval_tasknote['task_alert']) . "'
							, alert_type_none = '" . $this->db->escape($approval_tasknote['alert_type_none']) . "'
							, alert_type_sms = '" . $this->db->escape($approval_tasknote['alert_type_sms']) . "'
							, alert_type_notification = '" . $this->db->escape($approval_tasknote['alert_type_notification']) . "'
							, alert_type_email = '" . $this->db->escape($approval_tasknote['alert_type_email']) . "'
							, checklist = '" . $this->db->escape($approval_tasknote['checklist']) . "'
							, snooze_time = '" . $this->db->escape($approval_tasknote['snooze_time']) . "'
							, snooze_dismiss = '" . $this->db->escape($approval_tasknote['snooze_dismiss']) . "'
							
							, rules_task = '" . $this->db->escape($approval_tasknote['rules_task']) . "'
							, message_sid = '" . $this->db->escape($approval_tasknote['message_sid']) . "'
							, send_sms = '" . $this->db->escape($approval_tasknote['send_sms']) . "'
							, send_email = '" . $this->db->escape($approval_tasknote['send_email']) . "'
							, send_notification = '" . $this->db->escape($approval_tasknote['send_notification']) . "'
							, task_form_id = '" . $this->db->escape($approval_tasknote['task_form_id']) . "'
							, tags_id = '" . $this->db->escape($approval_tasknote['tags_id']) . "'
							, pickup_facilities_id = '" . $this->db->escape($approval_tasknote['pickup_facilities_id']) . "'
							, pickup_locations_address = '" . $this->db->escape($approval_tasknote['pickup_locations_address']) . "'
							, pickup_locations_time = '" . $this->db->escape($approval_tasknote['pickup_locations_time']) . "'
							, pickup_locations_latitude = '" . $this->db->escape($approval_tasknote['pickup_locations_latitude']) . "'
							, pickup_locations_longitude = '" . $this->db->escape($approval_tasknote['pickup_locations_longitude']) . "'
							, dropoff_facilities_id = '" . $this->db->escape($approval_tasknote['dropoff_facilities_id']) . "'
							, dropoff_locations_address = '" . $this->db->escape($approval_tasknote['dropoff_locations_address']) . "'
							, dropoff_locations_time = '" . $this->db->escape($approval_tasknote['dropoff_locations_time']) . "'
							
							, dropoff_locations_latitude = '" . $this->db->escape($approval_tasknote['dropoff_locations_latitude']) . "'
							, dropoff_locations_longitude = '" . $this->db->escape($approval_tasknote['dropoff_locations_longitude']) . "'
							, transport_tags = '" . $this->db->escape($approval_tasknote['transport_tags']) . "'
							, locations_id = '" . $this->db->escape($approval_tasknote['locations_id']) . "'
							, task_complettion = '" . $this->db->escape($approval_tasknote['task_complettion']) . "'
							, device_id = '" . $this->db->escape($approval_tasknote['device_id']) . "'
							, customs_forms_id = '" . $this->db->escape($approval_tasknote['customs_forms_id']) . "'
							, emp_tag_id = '" . $this->db->escape($approval_tasknote['emp_tag_id']) . "'
							, medication_tags = '" . $this->db->escape($approval_tasknote['medication_tags']) . "'
							, completion_alert = '" . $this->db->escape($approval_tasknote['completion_alert']) . "'
							, completion_alert_type_sms = '" . $this->db->escape($approval_tasknote['completion_alert_type_sms']) . "'
							, completion_alert_type_email = '" . $this->db->escape($approval_tasknote['completion_alert_type_email']) . "'
							, user_roles = '" . $this->db->escape($approval_tasknote['user_roles']) . "'
							, userids = '" . $this->db->escape($approval_tasknote['userids']) . "'
							, recurnce_hrly_perpetual = '" . $this->db->escape($approval_tasknote['recurnce_hrly_perpetual']) . "'
							, due_date_time = '" . $this->db->escape($approval_tasknote['due_date_time']) . "'
							, task_status = '" . $this->db->escape($approval_tasknote['task_status']) . "'
							, task_completed = '" . $this->db->escape($approval_tasknote['task_completed']) . "'
							, recurnce_hrly_recurnce = '" . $this->db->escape($approval_tasknote['recurnce_hrly_recurnce']) . "'
							, visitation_tags = '" . $this->db->escape($approval_tasknote['visitation_tags']) . "'
							, visitation_tag_id = '" . $this->db->escape($approval_tasknote['visitation_tag_id']) . "'
							, visitation_start_facilities_id = '" . $this->db->escape($approval_tasknote['visitation_start_facilities_id']) . "'
							, visitation_start_address = '" . $this->db->escape($approval_tasknote['visitation_start_address']) . "'
							, visitation_start_time = '" . $this->db->escape($approval_tasknote['visitation_start_time']) . "'
							, visitation_start_address_latitude = '" . $this->db->escape($approval_tasknote['visitation_start_address_latitude']) . "'
							, visitation_start_address_longitude = '" . $this->db->escape($approval_tasknote['visitation_start_address_longitude']) . "'
							, visitation_appoitment_facilities_id = '" . $this->db->escape($approval_tasknote['visitation_appoitment_facilities_id']) . "'
							, visitation_appoitment_address = '" . $this->db->escape($approval_tasknote['visitation_appoitment_address']) . "'							
							, visitation_appoitment_time = '" . $this->db->escape($approval_tasknote['visitation_appoitment_time']) . "'
							, visitation_appoitment_address_latitude = '" . $this->db->escape($approval_tasknote['visitation_appoitment_address_latitude']) . "'
							, visitation_appoitment_address_longitude = '" . $this->db->escape($approval_tasknote['visitation_appoitment_address_longitude']) . "'
							, completed_times = '" . $this->db->escape($approval_tasknote['completed_times']) . "'							
							, completed_alert = '" . $this->db->escape($approval_tasknote['completed_alert']) . "'
							, completed_late_alert = '" . $this->db->escape($approval_tasknote['completed_late_alert']) . "'
							, incomplete_alert = '" . $this->db->escape($approval_tasknote['incomplete_alert']) . "'
							, deleted_alert = '" . $this->db->escape($approval_tasknote['deleted_alert']) . "'
							, end_perpetual_task = '" . $this->db->escape($approval_tasknote['end_perpetual_task']) . "'
							, is_transport = '" . $this->db->escape($approval_tasknote['is_transport']) . "'
							, parent_id = '" . $this->db->escape($approval_tasknote['parent_id']) . "'
							, is_send_reminder = '" . $this->db->escape($approval_tasknote['is_send_reminder']) . "'
							, attachement_form = '" . $this->db->escape($approval_tasknote['attachement_form']) . "'
							, tasktype_form_id = '" . $this->db->escape($approval_tasknote['tasktype_form_id']) . "'
							, tagstatus_id = '" . $this->db->escape($approval_tasknote['tagstatus_id']) . "'
							, task_group_by = '" . $this->db->escape($approval_tasknote['task_group_by']) . "'
							, end_task = '" . $this->db->escape($approval_tasknote['end_task']) . "'
							, formrules_id = '" . $this->db->escape($approval_tasknote['formrules_id']) . "'
							, task_random_id = '" . $this->db->escape($approval_tasknote['task_random_id']) . "'
							, form_due_date = '" . $this->db->escape($approval_tasknote['form_due_date']) . "'
							, form_due_date_after = '" . $this->db->escape($approval_tasknote['form_due_date_after']) . "'
							, recurnce_m = '" . $this->db->escape($approval_tasknote['recurnce_m']) . "'
							, phone_device_id = '" . $this->db->escape($approval_tasknote['phone_device_id']) . "'
							, enable_requires_approval = '" . $this->db->escape($approval_tasknote['enable_requires_approval']) . "'
							, approval_taskid = '" . $this->db->escape($approval_tasknote['approval_taskid']) . "'
							, notes_id = '" . $this->db->escape($approval_tasknote['notes_id']) . "'
							, status = '" . $this->db->escape($approval_tasknote['status']) . "'
							, iswaypoint = '" . $this->db->escape($approval_tasknote['iswaypoint']) . "'
							, original_task_time = '" . $this->db->escape($approval_tasknote['original_task_time']) . "'
							
							, response = '" . $this->db->escape($approval_tasknote['response']) . "'
							, distance_text = '" . $this->db->escape($approval_tasknote['distance_text']) . "'
							, distance_value = '" . $this->db->escape($approval_tasknote['distance_value']) . "'
							, duration_text = '" . $this->db->escape($approval_tasknote['duration_text']) . "'
							, duration_value = '" . $this->db->escape($approval_tasknote['duration_value']) . "'
							
							, bed_check_location_ids = '" . $this->db->escape($approval_tasknote['bed_check_location_ids']) . "'
							
							, is_approval_required_forms_id = '" . $this->db->escape($approval_tasknote['is_approval_required_forms_id']) . "'
							, is_approval_required_tags_id = '" . $this->db->escape($approval_tasknote['is_approval_required_tags_id']) . "'
							, is_android = '" . $this->db->escape($approval_tasknote['is_android']) . "'
							
							, unique_id = '" . $config_unique_id . "'
							";
							$this->newdb->query($sql);
						}
						$activity_data3 = array(
							'data' => 'sync approval_tasknote  data successfully in warehouse ',
						);
						$this->model_activity_activity->addActivity('approval_tasknote', $activity_data3);
						
					}
					
					
					$livetravel_tasknotes = $this->model_syndb_syndb->gettravel_tasknotesByMain($note['notes_id'], $config_unique_id);
		
					if($livetravel_tasknotes != null && $livetravel_tasknotes != ""){
						$this->model_syndb_syndb->deletetravel_tasknotesMain($note['notes_id'], $config_unique_id);
					}
					
					$travel_tasknotes = $this->model_syndb_syndb->getravel_tasknotes($note['notes_id'], $config_unique_id);
					 
					if($travel_tasknotes != null && $travel_tasknotes != ""){
						foreach($travel_tasknotes as $travel_tasknote){
							
							$sql = "INSERT INTO " . NEWDB_PREFIX . "notes_by_travel_task SET 
							travel_task_id = '" . $travel_tasknote['travel_task_id'] . "'
							, notes_id = '" . $travel_tasknote['notes_id'] . "'
							, keyword_id = '" . $this->db->escape($travel_tasknote['keyword_id']) . "'
							, pickup_locations_address = '" . $this->db->escape($travel_tasknote['pickup_locations_address']) . "'
							, pickup_locations_latitude = '" . $this->db->escape($travel_tasknote['pickup_locations_latitude']) . "'
							, pickup_locations_longitude = '" . $this->db->escape($travel_tasknote['pickup_locations_longitude']) . "'
							, dropoff_locations_address = '" . $this->db->escape($travel_tasknote['dropoff_locations_address']) . "'
							, dropoff_locations_latitude = '" . $this->db->escape($travel_tasknote['dropoff_locations_latitude']) . "'
							
							, dropoff_locations_longitude = '" . $this->db->escape($travel_tasknote['dropoff_locations_longitude']) . "'
							, current_locations_address = '" . $this->db->escape($travel_tasknote['current_locations_address']) . "'
							, current_locations_latitude = '" . $this->db->escape($travel_tasknote['current_locations_latitude']) . "'
							, current_locations_longitude = '" . $this->db->escape($travel_tasknote['current_locations_longitude']) . "'
							, facilities_id = '" . $this->db->escape($travel_tasknote['facilities_id']) . "'
							, date_added = '" . $this->db->escape($travel_tasknote['date_added']) . "'
							, type = '" . $this->db->escape($travel_tasknote['type']) . "'
							, google_url = '" . $this->db->escape($travel_tasknote['google_url']) . "'
							
							, current_google_url = '" . $this->db->escape($travel_tasknote['current_google_url']) . "'
							, tags_id = '" . $this->db->escape($travel_tasknote['tags_id']) . "'
							, travel_state = '" . $this->db->escape($travel_tasknote['travel_state']) . "'
							, location_tracking_url = '" . $this->db->escape($travel_tasknote['location_tracking_url']) . "'
							, location_tracking_time_start = '" . $this->db->escape($travel_tasknote['location_tracking_time_start']) . "'
							, location_tracking_time_end = '" . $this->db->escape($travel_tasknote['location_tracking_time_end']) . "'
							, location_tracking_route = '" . $this->db->escape($travel_tasknote['location_tracking_route']) . "'
							, waypoint_google_url = '" . $this->db->escape($travel_tasknote['waypoint_google_url']) . "'
							, google_map_image_url = '" . $this->db->escape($travel_tasknote['google_map_image_url']) . "'
							
							, pickup_locations_address_2 = '" . $this->db->escape($travel_tasknote['pickup_locations_address_2']) . "'
							, pickup_locations_latitude_2 = '" . $this->db->escape($travel_tasknote['pickup_locations_latitude_2']) . "'
							, pickup_locations_longitude_2 = '" . $this->db->escape($travel_tasknote['pickup_locations_longitude_2']) . "'
							, dropoff_locations_address_2 = '" . $this->db->escape($travel_tasknote['dropoff_locations_address_2']) . "'
							, dropoff_locations_latitude_2 = '" . $this->db->escape($travel_tasknote['dropoff_locations_latitude_2']) . "'
							, dropoff_locations_longitude_2 = '" . $this->db->escape($travel_tasknote['dropoff_locations_longitude_2']) . "'
							, pick_up_tags_id = '" . $this->db->escape($travel_tasknote['pick_up_tags_id']) . "'
							, is_pick_up = '" . $this->db->escape($travel_tasknote['is_pick_up']) . "'
							, is_drop_off = '" . $this->db->escape($travel_tasknote['is_drop_off']) . "'
							
							, unique_id = '" . $config_unique_id . "'
							 ON DUPLICATE KEY UPDATE  
							 notes_id = '" . $travel_tasknote['notes_id'] . "'
							 , keyword_id = '" . $this->db->escape($travel_tasknote['keyword_id']) . "'
							, pickup_locations_address = '" . $this->db->escape($travel_tasknote['pickup_locations_address']) . "'
							, pickup_locations_latitude = '" . $this->db->escape($travel_tasknote['pickup_locations_latitude']) . "'
							, pickup_locations_longitude = '" . $this->db->escape($travel_tasknote['pickup_locations_longitude']) . "'
							, dropoff_locations_address = '" . $this->db->escape($travel_tasknote['dropoff_locations_address']) . "'
							, dropoff_locations_latitude = '" . $this->db->escape($travel_tasknote['dropoff_locations_latitude']) . "'
							
							, dropoff_locations_longitude = '" . $this->db->escape($travel_tasknote['dropoff_locations_longitude']) . "'
							, current_locations_address = '" . $this->db->escape($travel_tasknote['current_locations_address']) . "'
							, current_locations_latitude = '" . $this->db->escape($travel_tasknote['current_locations_latitude']) . "'
							, current_locations_longitude = '" . $this->db->escape($travel_tasknote['current_locations_longitude']) . "'
							, facilities_id = '" . $this->db->escape($travel_tasknote['facilities_id']) . "'
							, date_added = '" . $this->db->escape($travel_tasknote['date_added']) . "'
							, type = '" . $this->db->escape($travel_tasknote['type']) . "'
							, google_url = '" . $this->db->escape($travel_tasknote['google_url']) . "'
							
							, current_google_url = '" . $this->db->escape($travel_tasknote['current_google_url']) . "'
							, tags_id = '" . $this->db->escape($travel_tasknote['tags_id']) . "'
							, travel_state = '" . $this->db->escape($travel_tasknote['travel_state']) . "'
							, location_tracking_url = '" . $this->db->escape($travel_tasknote['location_tracking_url']) . "'
							, location_tracking_time_start = '" . $this->db->escape($travel_tasknote['location_tracking_time_start']) . "'
							, location_tracking_time_end = '" . $this->db->escape($travel_tasknote['location_tracking_time_end']) . "'
							, location_tracking_route = '" . $this->db->escape($travel_tasknote['location_tracking_route']) . "'
							, waypoint_google_url = '" . $this->db->escape($travel_tasknote['waypoint_google_url']) . "'
							, google_map_image_url = '" . $this->db->escape($travel_tasknote['google_map_image_url']) . "'
							
							, pickup_locations_address_2 = '" . $this->db->escape($travel_tasknote['pickup_locations_address_2']) . "'
							, pickup_locations_latitude_2 = '" . $this->db->escape($travel_tasknote['pickup_locations_latitude_2']) . "'
							, pickup_locations_longitude_2 = '" . $this->db->escape($travel_tasknote['pickup_locations_longitude_2']) . "'
							, dropoff_locations_address_2 = '" . $this->db->escape($travel_tasknote['dropoff_locations_address_2']) . "'
							, dropoff_locations_latitude_2 = '" . $this->db->escape($travel_tasknote['dropoff_locations_latitude_2']) . "'
							, dropoff_locations_longitude_2 = '" . $this->db->escape($travel_tasknote['dropoff_locations_longitude_2']) . "'
							, pick_up_tags_id = '" . $this->db->escape($travel_tasknote['pick_up_tags_id']) . "'
							, is_pick_up = '" . $this->db->escape($travel_tasknote['is_pick_up']) . "'
							, is_drop_off = '" . $this->db->escape($travel_tasknote['is_drop_off']) . "'
							
							, unique_id = '" . $config_unique_id . "' 
							
							";
							$this->newdb->query($sql);
						}
						$activity_data3 = array(
							'data' => 'sync travel_task  data successfully in warehouse ',
						);
						$this->model_activity_activity->addActivity('travel_task', $activity_data3);
						
					}
					
					
					$livecreatetask_by_transportnotes = $this->model_syndb_syndb->getcreatetask_by_transportnotesByMain($note['notes_id'], $config_unique_id);
		
					if($livecreatetask_by_transportnotes != null && $livecreatetask_by_transportnotes != ""){
						$this->model_syndb_syndb->deletecreatetask_by_transportnotesMain($note['notes_id'], $config_unique_id);
					}
					
					$createtask_by_transportnotes = $this->model_syndb_syndb->gecreatetask_by_transportnotes($note['notes_id'], $config_unique_id);
					 
					if($createtask_by_transportnotes != null && $createtask_by_transportnotes != ""){
						foreach($createtask_by_transportnotes as $createtask_by_transportnote){
							
							$sql = "INSERT INTO " . NEWDB_PREFIX . "notes_createtask_by_transport SET 
							createtask_by_transport_id = '" . $createtask_by_transportnote['createtask_by_transport_id'] . "'
							, id = '" . $createtask_by_transportnote['id'] . "'
							, locations_address = '" . $this->db->escape($createtask_by_transportnote['locations_address']) . "'
							, latitude = '" . $this->db->escape($createtask_by_transportnote['latitude']) . "'
							, longitude = '" . $this->db->escape($createtask_by_transportnote['longitude']) . "'
							, complete_status = '" . $this->db->escape($createtask_by_transportnote['complete_status']) . "'
							, place_id = '" . $this->db->escape($createtask_by_transportnote['place_id']) . "'
							, notes_id = '" . $this->db->escape($createtask_by_transportnote['notes_id']) . "'
							, unique_id = '" . $config_unique_id . "'
							 ON DUPLICATE KEY UPDATE  
							 id = '" . $createtask_by_transportnote['id'] . "'
							, locations_address = '" . $this->db->escape($createtask_by_transportnote['locations_address']) . "'
							, latitude = '" . $this->db->escape($createtask_by_transportnote['latitude']) . "'
							, longitude = '" . $this->db->escape($createtask_by_transportnote['longitude']) . "'
							, complete_status = '" . $this->db->escape($createtask_by_transportnote['complete_status']) . "'
							, place_id = '" . $this->db->escape($createtask_by_transportnote['place_id']) . "'
							, notes_id = '" . $this->db->escape($createtask_by_transportnote['notes_id']) . "'
							, unique_id = '" . $config_unique_id . "' 
							
							";
							$this->newdb->query($sql);
						}
						$activity_data3 = array(
							'data' => 'sync createtask_by_transportnote  data successfully in warehouse ',
						);
						$this->model_activity_activity->addActivity('createtask_by_transportnote', $activity_data3);
						
					}
					
					
					
					$sqlc = "UPDATE `" . DB_PREFIX . "notes` SET  notes_conut='1' WHERE notes_id = '" . (int)$note['notes_id'] . "' ";
		
					$this->db->query($sqlc);
					
			} 
			
			$activity_data5 = array(
				'data' => 'sync notes data successfully in warehouse ',
			);
			$this->model_activity_activity->addActivity('notes', $activity_data5);
			
			$subjet = 'Warehouse Status Report';
			
			$message = array();
			
			$facilities = array_unique($facilities);
			
			$message['facilities'] = $facilities;
			$message['startDate'] = $startDate;
			$message['endDate'] = $endDate;
			$message['config_unique_id'] = $config_unique_id;
			
			$this->sendMail($subjet, $message);
			
		}else{
			$subjet = 'Warehouse Status Report';
			
			$message = array();
			$facilities = array_unique($facilities);
			
			$message['facilities'] = $facilities;
			$message['startDate'] = $startDate;
			$message['endDate'] = $endDate;
			$message['config_unique_id'] = $config_unique_id;
			
			
			$this->sendMail($subjet, $message);
			
			$activity_data5 = array(
				'data' => 'no notes data sync in warehouse because no data in given date'.$startDate.'',
			);
			$this->model_activity_activity->addActivity('notes', $activity_data5);
		}
		
		
		//echo "55555555555555555555555";
		//echo "66666666666666666666666";
		
		
		
		$threemonth = date('Y-m-d', strtotime('-3 Months'));
		
		$last_date  = date('Y-m-d', strtotime($threemonth.'-1 day'));
		//var_dump($last_date);
		 
		$this->model_syndb_syndb->deleteNotes($last_date);
		
	
		
		if($manual_link == '1'){
			echo json_encode(1);
		}else{
			echo "Success";
		}
		
		
		//var_dump($backupDatas);
		//die;
		
		}catch(Exception $e){
			$subjet = 'Warehouse Alert';
			
			$message = array();
			$message['startDate'] = $startDate;
			$message['endDate'] = $endDate;
			$message['config_unique_id'] = $config_unique_id;
			$message['manual_link'] = $manual_link;
			
			
			$this->sendMail($subjet, $message);
			
			$activity_data5 = array(
				'data' => 'warehouse sync error',
			);
			$this->model_activity_activity->addActivity('notes', $activity_data5);
		
		}
		 
	}
	
	public function sendMail($subjet, $results){
		/*$message33 = 'Hello,';
		$message33 .= '<br><br>';
		$message33 .= $message.' <br><br>';
		$message33 .='Thank You<br> '.$this->config->get('config_name').'';
		*/
		
		if($results['manual_link'] == '1'){
			$message33 = $this->alertemailtemplate($results);
		}else{
			$message33 = $this->emailtemplate($results);
			
		}
		
			$this->load->model('api/emailapi');
			
			$edata = array();
			$edata['message'] = $message33;
			$edata['subject'] = $subjet;
			$edata['user_email'] = 'app-monitoring@noteactive.com';
				
			$email_status = $this->model_api_emailapi->sendmail($edata);
			
	}

	public function testCronjob(){
		
		$this->db->query("INSERT INTO `" . DB_PREFIX . "currency` SET `title` = 'test', `date_modified` = NOW()");
	}
	
	public function persecondScript(){
		$this->load->model('notes/notes');
		$this->load->model('notes/rules');
		$this->load->model('facilities/facilities');
		
		$this->load->model('setting/highlighter');
		$this->load->model('setting/country');
		$this->load->model('setting/zone');
		$this->load->model('setting/timezone');
		$this->load->model('user/user');
		$this->load->model('notes/tags');
		
		$this->db->query("DELETE FROM `" . DB_PREFIX . "session` WHERE data = 'false' ");
		
		
		//date_default_timezone_set('US/Eastern');
		
		/*
				
		$sqlt = "SELECT * FROM `" . DB_PREFIX . "createtask` WHERE ";
		$sqlt .= " is_transport = '1' and tasktype = 'TRAVEL' ";
		
		$bedt = $this->db->query($sqlt);
	
		
		if($bedt->num_rows > 0){
			foreach($bedt->rows as $rowt){
				
				if($rowt['duration_value'] == "0"){
					$this->load->model('createtask/createtask');
					$travelWaypoint1s = $this->model_createtask_createtask->gettravelWaypoints($rowt['id']);
					if($travelWaypoint1s != null && $travelWaypoint1s != ""){
					
						$newadd = '&waypoints=optimize:true|';
						$newadd22 = "";
						$numItems = count($travelWaypoint1s)-1;
					
						$ik = 0;
					
						foreach ($travelWaypoint1s as $location) {
							if($ik == $numItems) {
								$newadd .= str_replace(' ', '+', $location['locations_address']);
								$newadd22 .= $location['latitude'].','.$location['longitude'].'|';
							}else{
								$newadd .= str_replace(' ', '+', $location['locations_address']) .'|';
								$newadd22 .= $location['latitude'].','.$location['longitude'].'|';
							}
							  
							$ik++;  
						}
					}
					
					$url = "https://maps.googleapis.com/maps/api/directions/json?origin=".str_replace(' ', '+', $rowt['pickup_locations_address'])."&destination=".str_replace(' ', '+', $rowt['dropoff_locations_address'])."".$newadd."&key=".GOOGLE_API_KEY."";
					
					//var_dump($url);
					//echo "<hr>";
					
					$geo = file_get_contents($url);
					
					$response_all = json_decode($geo, true);
					
					//echo "<hr>";
					
					//var_dump($response_all['routes'][0]['legs'][0]['distance']);
					$distance = $response_all['routes'][0]['legs'][0]['distance']['text'];
					$distancev = $response_all['routes'][0]['legs'][0]['distance']['value'];
					//var_dump($distance);
					//var_dump($distancev);
					
					//$duration = $response_all->routes[0]->legs[0]->duration->text;
					//$durationv = $response_all->routes[0]->legs[0]->duration->value;
					//var_dump($duration);
					//var_dump($durationv);
					//echo "<hr>";
					
					//var_dump($response_all);
					$durationv = 0;
					$durationv1 = 0;
					
					foreach($response_all['routes'][0]['legs'] as $a){
						
						//var_dump($i);
						
						//var_dump($a['steps']);
						//var_dump($a['duration']['text']);
						
						foreach($a['steps'] as $b){
							
							$durationv1 = $durationv1 + $b['duration']['value'];
						}
						
						$duration .= $a['duration']['text'].',';
						$durationv = $durationv + $a['duration']['value'] + $durationv1;
						//echo "<hr>";
					
					}
					
					var_dump($durationv);
					echo "<hr>";
				}
				
			}
		}*/
		
		
		if($this->config->get('config_transcription') == '1'){
			
			$this->load->model('notes/notes');
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "notes_media where is_updated = '1' ");
			$numrow = $query->num_rows;
			
			if($numrow > 0){
			// $stturl = "https://speech.googleapis.com/v1beta1/speech:syncrecognize?key=AIzaSyA9iL7srWZ8-jUKeoVQT64NFj7RDLs583o";
			
			 $stturl = "https://speech.googleapis.com/v1p1beta1/speech:longrunningrecognize?key=AIzaSyA9iL7srWZ8-jUKeoVQT64NFj7RDLs583o";
			
				foreach($query->rows as $row){
					
						$speech_name = $row['speech_name']; 
						
						$url ="https://speech.googleapis.com/v1/operations/{$speech_name}?key=AIzaSyA9iL7srWZ8-jUKeoVQT64NFj7RDLs583o";
						
						$geo = file_get_contents($url);
						
						$response_all = json_decode($geo, true);
						/*var_dump($contents);
						$ndata = array();
						foreach($contents["results"] as $content){
							foreach($content['alternatives'] as $b){
								$ndata[] = $b['transcript'];
							}
						}
						
						$ndata = array();
						foreach($response_all as $all){
							$startTime = $all['startTime'];
							$lastUpdateTime = $all['lastUpdateTime'];
							foreach($all['results'] as $results){
								foreach($results['alternatives'] as $alternatives){
									$ncontent .= $alternatives['transcript'] .' | ';
									foreach($alternatives['words'] as $wd){
										//var_dump($wd['speakerTag']);
									}
								}
							}
						}
						
						
						//$ncontent = implode(" ",$ndata);
						*/
						
						$sssend = end($response_all['response']['results']);
						//var_dump($sssend);
						foreach($sssend['alternatives'] as $alternatives){
							$sssss = "1";
							$dddd = array();
							$a = " ";
							foreach($alternatives['words'] as $wd){
								
								if($sssss != $wd['speakerTag']){
									$dddd = array();
									$sssss = $wd['speakerTag'];
									
									$a .= "\n Speaker: " .$wd['speakerTag'] ." | ";
								}
								
								if($sssss == $wd['speakerTag']){
									$a .= $wd['word'] ." ";
								}
								
								$sssss = $wd['speakerTag'];
							}
							
						}
						
					
						if(!empty($response_all['response']['results'])){
							$notes_data = $this->model_notes_notes->getnotes($row['notes_id']);
							
							$notes_description = $notes_data['notes_description'];
							$facilities_id = $notes_data['facilities_id'];
							$date_added = $notes_data['date_added'];
							
							$notesContent = $notes_description.' | Voice Transcript: '.$a.' | ';
							$formData = array();
							$formData['notes_description'] = $notesContent;
							$formData['facilities_id'] = $facilities_id;
							$formData['date_added'] = $date_added;
						
							
							$slq1 = "UPDATE " . DB_PREFIX . "notes_media SET is_updated = '2' where notes_media_id = '".$row['notes_media_id']."'";
							$this->db->query($slq1);
							
							$this->model_notes_notes->updateNotesContent($row['notes_id'], $formData);
						}
						
					}
			
			}
		
		}
		
		
		$results = $this->model_facilities_facilities->getfacilitiess($data);
    	
		if($results != null && $results != ""){
			foreach ($results as $tresult) {
				
				$timezone_info = $this->model_setting_timezone->gettimezone($tresult['timezone_id']);
				
				/*
				$config_task_after_complete = $this->config->get('config_task_after_complete');
				$config_task_deleted_time = $this->config->get('config_task_deleted_time');
				$deleteTime = $config_task_after_complete + $config_task_deleted_time;
				*/
				
					/*if($config_task_deleted_time == '6min'){
						$deleteTime = '6';
					}else
					if($config_task_deleted_time == '10min'){
						$deleteTime = '10';
					}
					else
					if($config_task_deleted_time == '15min'){
						$deleteTime = '15';
					}else
					if($config_task_deleted_time == '20min'){
						$deleteTime = '20';
					}else
					if($config_task_deleted_time == '25min'){
						$deleteTime = '25';
					}else
					if($config_task_deleted_time == '30min'){
						$deleteTime = '30';
					}else
					if($config_task_deleted_time == '45min'){
						$deleteTime = '45';
					}*/
			
				//var_dump($timezone_info['timezone_value']); 
				date_default_timezone_set($timezone_info['timezone_value']);
				
				
				
				$delete_startDate = date('Y-m-d H:i:s', strtotime('now'));
				$delete_date = date('Y-m-d', strtotime('now'));
				
				if($delete_startDate >= ''.$delete_date.' 23:50:00' && $delete_startDate <= ''.$delete_date.' 23:59:59'){
					$sqlu1 = "UPDATE `" . DB_PREFIX . "medication_time` SET create_task = '0' ";
					$this->db->query($sqlu1);
					
					//$sqlu12 = "UPDATE `" . DB_PREFIX . "tags_medication_details` SET create_task = '0' ";
					//$this->db->query($sqlu12);
					
					$sqlu122 = "UPDATE `" . DB_PREFIX . "notes` SET form_alert_send_email='0',form_alert_send_sms='0' WHERE form_alert_send_email='1' and form_snooze_dismiss='2' ";
					$this->db->query($sqlu122);
					
					$sql221 = "DELETE FROM `" . DB_PREFIX . "tags` WHERE status = 0 ";
					$this->db->query($sql221);
					
				}
				
				if($delete_startDate >= ''.$delete_date.' 23:58:00' && $delete_startDate <= ''.$delete_date.' 23:59:59'){
					$ddddf = DIR_IMAGE.'facerecognition';
					$filesss = glob($ddddf.'/*'); 
					foreach($filesss as $filess){
						if(is_file($filess))
						unlink($filess); 
					}
					
					$ddddfd = DIR_IMAGE.'files';
					$filesdss = glob($ddddfd.'/*'); 
					foreach($filesdss as $fidless){
						if(is_file($fidless))
						unlink($fidless); 
					}
				}
				
				$this->load->model('createtask/createtask');
				$currentdate = date('d-m-Y');
				//var_dump($tresult['facilities_id']);
				//var_dump($currentdate);
				
				$complteteTaskLists = $this->model_createtask_createtask->gettaskLists($currentdate, $tresult['facilities_id']);
				//var_dump($complteteTaskLists);
				if($complteteTaskLists != null && $complteteTaskLists != ""){
				
					foreach($complteteTaskLists as $complteteTaskList){
						$result = array(); 
						/*
						echo $deleteTime;
						echo "<hr>";
						*/
						
						$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName($complteteTaskList['tasktype']);
						
						if($tasktype_info['auto_extend'] == '0'){
					
							if($tasktype_info['custom_completion_rule'] == '1'){
								$config_task_after_complete = $tasktype_info['config_task_after_complete'];
								$config_task_deleted_time = $tasktype_info['config_task_deleted_time'];
								$deleteTime = $config_task_after_complete + $config_task_deleted_time;
								
							}else{
								$config_task_after_complete = $this->config->get('config_task_after_complete');
								$config_task_deleted_time = $this->config->get('config_task_deleted_time');
								$deleteTime = $config_task_after_complete + $config_task_deleted_time;
							}
							
							//echo date('H:i:s', strtotime($complteteTaskList['task_time']));
							//echo "<hr>"; 
							
							$taskstarttime = date('Y-m-d H:i:s', strtotime(' +'.$deleteTime.' minutes',strtotime($complteteTaskList['task_time'])));
							//var_dump($taskstarttime);
							//echo "<hr>";
							
							
							
							$currenttime = date('Y-m-d H:i:s', strtotime('now'));
							
							/*echo "TASK TIME ". $taskstarttime . " =========== CURRENT TIME ".$currenttime;
							echo "<hr>";*/
							
							$result['facilityId'] = $complteteTaskList['facilityId'];
							$result['description'] = $complteteTaskList['description'];
							$result['date_added'] = $complteteTaskList['date_added'];
							$result['task_time'] = $complteteTaskList['task_time'];
							$result['id'] = $complteteTaskList['id'];
							$result['assign_to'] = $complteteTaskList['assign_to'];
							$result['checklist'] = $complteteTaskList['checklist'];
							$result['tasktype'] = $complteteTaskList['tasktype'];
							$result['facilitytimezone'] = $timezone_info['timezone_value'];
							
							if($currenttime > $taskstarttime){
								//var_dump($complteteTaskLists);
								//echo "TRUE ";
								
								//var_dump($tresult['facilities_id']); 
								
								
								if($complteteTaskList['enable_requires_approval'] == '2'){
						
									$declineTaskLists = $this->model_createtask_createtask->getdeclinetasksLists($complteteTaskList['id']);
						
									$approvaltaskdate = date('Y-m-d H:i',strtotime($complteteTaskList['date_added']));
									$declinetaskdate =	date('Y-m-d H:i',strtotime($declineTaskLists['date_added']));
						
									if( $approvaltaskdate ==  $declinetaskdate){
										$notes_id = $this->model_createtask_createtask->insertTaskLists($result, $tresult['facilities_id'], '0');
										
										
										if($complteteTaskList['medication_tags']){
											$this->load->model('setting/tags');
											
											$medication_tags1 = explode(',',$complteteTaskList['medication_tags']);
											
											
											
											date_default_timezone_set($timezone_info['timezone_value']);
											
											
											$date_added = date('Y-m-d H:i:s', strtotime('now'));
											
											foreach ($medication_tags1 as $medicationtag) {
												$tags_info1 = $this->model_setting_tags->getTag($medicationtag);

												if($tags_info1['emp_first_name']){
													$emp_tag_id = $tags_info1['emp_tag_id'].': '.$tags_info1['emp_first_name'].' '. $tags_info1['emp_last_name'];
												}else{
													$emp_tag_id = $tags_info1['emp_tag_id'];
												}
												
												if ($tags_info1) {
													
													$drugs = array();
													
													$mdrugs = $this->model_setting_tags->getTagsMedicationdetailsByID($result['id'], $medicationtag);
													
													foreach($mdrugs as $tasklocation){
														
														$mdrug_info = $this->model_setting_tags->getTagsMedicationdruglByID($tasklocation['tags_medication_details_id']);
														
														$task_content = 'Resident '.$tags_info1['emp_tag_id'].':'.$tags_info1['emp_first_name'];
											
										
													
													 $sql2 = "INSERT INTO `" . DB_PREFIX . "notes_by_task` SET 
														notes_id = '".$notes_id."', locations_id ='".$tasklocation['locations_id']."', task_type= '2', task_content = '".$this->db->escape($task_content)."', signature= '".$tasklocation['medication_signature']."', user_id= '".$tasklocation['medication_user_id']."', date_added = '".$date_added."', notes_pin = '".$tasklocation['medication_notes_pin']."', notes_type = '".$this->request->post['notes_type']."', task_time = '".$tasklocation['task_time']."' , media_url = '".$tasklocation['media_url']."', capacity = '".$tasklocation['capacity']."', location_name = '".$tasklocation['location_name']."', location_type = '".$tasklocation['location_type']."', notes_task_type = '2', tags_id = '".$tags_info1['tags_id']."', drug_name = '".$mdrug_info['drug_name']."', dose = '".$mdrug_info['dose']."', drug_type = '".$mdrug_info['drug_type']."', quantity = '".$tasklocation['quantity']."', frequency = '".$mdrug_info['frequency']."', instructions = '".$mdrug_info['instructions']."', count = '".$mdrug_info['count']."', createtask_by_group_id = '".$tasklocation['createtask_by_group_id']."', task_comments = '".$tasklocation['comments']."', medication_attach_url = '".$tasklocation['medication_attach_url']."',medication_file_upload='1' , tags_medication_details_id = '".$tasklocation['tags_medication_details_id']."' , tags_medication_id = '".$tasklocation['tags_medication_id']."'  ";
													
														$this->db->query($sql2);
														$notes_by_task_id = $this->db->getLastId();
														
													
													}
													
												}
											}
											
											
											$update_date = date('Y-m-d H:i:s', strtotime('now'));
									
											if($tags_info1['emp_tag_id'] != null && $tags_info1['emp_tag_id'] != ""){
												$this->load->model('notes/notes');
											 
												$this->model_notes_notes->updateNotesTag($tags_info1['emp_tag_id'], $notes_id, $tags_info1['tags_id'], $update_date);
											}
										}
										
										$this->load->model('createtask/createtask');
										$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName($result['tasktype']);
										$relation_keyword_id = $tasktype_info['relation_keyword_id'];
										
										
										if($relation_keyword_id){
											$this->load->model('notes/notes');
											$noteDetails = $this->model_notes_notes->getnotes($notes_id);
											
											$this->load->model('setting/keywords');
											$keyword_info = $this->model_setting_keywords->getkeywordDetail($relation_keyword_id);
											
											$data3 = array();
											$data3['keyword_file'] = $keyword_info['keyword_image'];
											$data3['notes_description'] = $noteDetails['notes_description'];
											
											$this->model_notes_notes->addactiveNote($data3, $notes_id);
										}
										
										$this->model_createtask_createtask->updateIncomtaskNote($complteteTaskList['id'], $tresult['facilities_id']);
										
										$this->model_createtask_createtask->deteteIncomTask($tresult['facilities_id']);
										
									}
							
								}else{
									$notes_id = $this->model_createtask_createtask->insertTaskLists($result, $tresult['facilities_id'], '0');
								
								
									if($complteteTaskList['medication_tags']){
										$this->load->model('setting/tags');
										
										$medication_tags1 = explode(',',$complteteTaskList['medication_tags']);
										
										
										
										date_default_timezone_set($timezone_info['timezone_value']);
										
										
										$date_added = date('Y-m-d H:i:s', strtotime('now'));
										
										foreach ($medication_tags1 as $medicationtag) {
											$tags_info1 = $this->model_setting_tags->getTag($medicationtag);

											if($tags_info1['emp_first_name']){
												$emp_tag_id = $tags_info1['emp_tag_id'].': '.$tags_info1['emp_first_name'].' '. $tags_info1['emp_last_name'];
											}else{
												$emp_tag_id = $tags_info1['emp_tag_id'];
											}
											
											if ($tags_info1) {
												
												$drugs = array();
												
												$mdrugs = $this->model_setting_tags->getTagsMedicationdetailsByID($result['id'], $medicationtag);
												
												foreach($mdrugs as $tasklocation){
													
													$mdrug_info = $this->model_setting_tags->getTagsMedicationdruglByID($tasklocation['tags_medication_details_id']);
													
													$task_content = 'Resident '.$tags_info1['emp_tag_id'].':'.$tags_info1['emp_first_name'];
										
									
												
												 $sql2 = "INSERT INTO `" . DB_PREFIX . "notes_by_task` SET 
													notes_id = '".$notes_id."', locations_id ='".$tasklocation['locations_id']."', task_type= '2', task_content = '".$this->db->escape($task_content)."', signature= '".$tasklocation['medication_signature']."', user_id= '".$tasklocation['medication_user_id']."', date_added = '".$date_added."', notes_pin = '".$tasklocation['medication_notes_pin']."', notes_type = '".$this->request->post['notes_type']."', task_time = '".$tasklocation['task_time']."' , media_url = '".$tasklocation['media_url']."', capacity = '".$tasklocation['capacity']."', location_name = '".$tasklocation['location_name']."', location_type = '".$tasklocation['location_type']."', notes_task_type = '2', tags_id = '".$tags_info1['tags_id']."', drug_name = '".$mdrug_info['drug_name']."', dose = '".$mdrug_info['dose']."', drug_type = '".$mdrug_info['drug_type']."', quantity = '".$tasklocation['quantity']."', frequency = '".$mdrug_info['frequency']."', instructions = '".$mdrug_info['instructions']."', count = '".$mdrug_info['count']."', createtask_by_group_id = '".$tasklocation['createtask_by_group_id']."', task_comments = '".$tasklocation['comments']."', medication_attach_url = '".$tasklocation['medication_attach_url']."',medication_file_upload='1' , tags_medication_details_id = '".$tasklocation['tags_medication_details_id']."' , tags_medication_id = '".$tasklocation['tags_medication_id']."'  ";
												
													$this->db->query($sql2);
													$notes_by_task_id = $this->db->getLastId();
													
												
												}
												
											}
										}
										
										
										$update_date = date('Y-m-d H:i:s', strtotime('now'));
								
										if($tags_info1['emp_tag_id'] != null && $tags_info1['emp_tag_id'] != ""){
											$this->load->model('notes/notes');
										 
											$this->model_notes_notes->updateNotesTag($tags_info1['emp_tag_id'], $notes_id, $tags_info1['tags_id'], $update_date);
										}
									}
									
									$this->load->model('createtask/createtask');
									$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName($result['tasktype']);
									$relation_keyword_id = $tasktype_info['relation_keyword_id'];
									
									
									if($relation_keyword_id){
										$this->load->model('notes/notes');
										$noteDetails = $this->model_notes_notes->getnotes($notes_id);
										
										$this->load->model('setting/keywords');
										$keyword_info = $this->model_setting_keywords->getkeywordDetail($relation_keyword_id);
										
										$data3 = array();
										$data3['keyword_file'] = $keyword_info['keyword_image'];
										$data3['notes_description'] = $noteDetails['notes_description'];
										
										$this->model_notes_notes->addactiveNote($data3, $notes_id);
									}
									
									$this->model_createtask_createtask->updateIncomtaskNote($complteteTaskList['id'], $tresult['facilities_id']);
									
									$this->model_createtask_createtask->deteteIncomTask($tresult['facilities_id']);
									
								}
								
								
							} 
							
						}
					}
				}
				
				
				$config_session_time_out = $this->config->get('config_session_time_out');
		
		
				if($config_session_time_out == '5min'){
					$inactive = 5;
				}else
				if($config_session_time_out == '10min'){
					$inactive = 10;
				}else
				if($config_session_time_out == '15min'){
					$inactive = 15;
				}else
				if($config_session_time_out == '20min'){
					$inactive = 20;
				}else
				if($config_session_time_out == '25min'){
					$inactive = 25;
				}else
				if($config_session_time_out == '30min'){
					$inactive = 30;
				}else
				if($config_session_time_out == '45min'){
					$inactive = 45;
				}else
				if($config_session_time_out == '1hour'){
					$inactive = 60;
				}else
				if($config_session_time_out == '2hour'){
					$inactive = 120;
				}else
				if($config_session_time_out == '3hour'){
					$inactive = 180;
				}else
				if($config_session_time_out == '4hour'){
					$inactive = 240;
				}else
				if($config_session_time_out == '5hour'){
					$inactive = 300;
				}else
				if($config_session_time_out == '6hour'){
					$inactive = 360;
				}else
				if($config_session_time_out == '7hour'){
					$inactive = 420;
				}else
				if($config_session_time_out == '8hour'){
					$inactive = 480;
				}else{
					$inactive = 600;
				}
				
				
				$this->load->model('licence/licence');
		
				//var_dump($timezone_info['timezone_value']);
				date_default_timezone_set($timezone_info['timezone_value']);
				
			
				//var_dump($inactive);
				//echo "<hr>";
				$thestime = date('H:i:s');
				$stime = date("H:i:s",strtotime("-".$inactive." minutes",strtotime($thestime)));
				
				$noteDate2 = date('Y-m-d', strtotime('now'));
				$noteDate = $noteDate2.' '.$stime;
				
				$faresults = $this->model_licence_licence->getfacilitiesOnline2($tresult['facilities_id']);
				//var_dump($faresults);
				//echo "<hr>";
				$webkey = array();
				$redata = array();
				foreach($faresults as $faresult){
					$date_added = date('Y-m-d H:i:s', strtotime($faresult['date_added']));
					//var_dump($date_added);
					//echo "<hr>";
					//echo $date_added .'<'. $noteDate;
					//echo "<hr>";
					
					if($date_added < $noteDate){
						//echo $date_added .'<'. $noteDate;
						//echo "<hr>";
						$this->model_licence_licence->updateSession($faresult['facility_login']);
						
						//$sqlu = "UPDATE `" . DB_PREFIX . "facility_online` SET facility_count = '0' WHERE facilities_id = '" . (int)$faresult['facilities_id'] . "' and username = '".$faresult['username']."' ";
						$sqlu = "UPDATE `" . DB_PREFIX . "facility_online` SET facility_count = '0' WHERE facility_online_id = '" . (int)$faresult['facility_online_id'] . "' ";
		
						$this->db->query($sqlu);
						
						$webkey[] = $faresult['facility_login'];
						$redata['facilities_id'][] = $faresult['facilities_id'];
						$redata['facilities_id'][] = $faresult['facilities_id'];
						$redata['facilities_id'][] = $faresult['facilities_id'];
					}
				}
				//var_dump($webkey);
				//$newKey = implode(',', $webkey);
				  
				//var_dump($newKey);
				//echo "<hr>";
				/*if($newKey != null && $newKey != ""){
					$this->model_licence_licence->updateSession($newKey);
				}*/
				
				
				
				$noteDate2 = date('Y-m-d', strtotime('now'));
			
				$sqlm = "SELECT * from " . DB_PREFIX . "tags_medication_details where is_schedule_medication = '1' and is_discharge = '0' and status = '1' and create_task = '0' and `date_to` >= '".$noteDate2."' ";
				$qm = $this->db->query($sqlm);
					
				$this->load->model('notes/notes');
				$this->load->model('facilities/facilities');
				$this->load->model('setting/tags');
				$this->load->model('createtask/createtask');
				/*
				var_dump($qm->num_rows );
				echo "<hr>";
				die;*/
				if($qm->num_rows > 0){
					foreach($qm->rows as $rowm){
						
						$tag_info = $this->model_setting_tags->getTag($rowm['tags_id']);
						
						if($tag_info){
							if($tag_info['discharge'] == '0'){
								
								if($rowm['tags_medication_details_id'] > 0){
								
									$addtaskw = array();
									$facilities_info = $this->model_facilities_facilities->getfacilities($tag_info['facilities_id']);
										
									$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
										
									date_default_timezone_set($timezone_info['timezone_value']);
										
									$noteDate = date('Y-m-d H:i:s', strtotime('now'));
									$date_added = date('Y-m-d', strtotime('now'));
									
									//$taskTime = date('h:i A', strtotime('now'));
									
									$snooze_time71 = 3;
									$thestime61 = date('H:i:s');
									$taskTime = date("h:i A",strtotime("+".$snooze_time71." minutes",strtotime($thestime61)));
									
									$current_time = date("H:i:s");
									
									$time1 = date('H:i:s');
									
						
									//var_dump($taskTime);
									
									/*$sqlmt2 = "SELECT * from " . DB_PREFIX . "createtask_medications where tags_medication_details_id = '".$rowm['tags_medication_details_id']."' and (`date_added` BETWEEN  '".$date_added." 00:00:00' AND  '".$date_added." 23:59:59') and complete_status = '1' ";
									$qm23 = $this->db->query($sqlmt2);
								
									if($qm23->num_rows == 0){*/
										
										/*$sqlm2 = "SELECT * from " . DB_PREFIX . "notes_by_task where tags_medication_details_id = '".$rowm['tags_medication_details_id']."' and (`date_added` BETWEEN  '".$date_added." 00:00:00' AND  '".$date_added." 23:59:59') and complete_status = '1' ";
										$qm2 = $this->db->query($sqlm2);
										
										if($qm2->num_rows == 0){*/
											
											$daily_times = array();
											$daily_times1 = explode(",",$rowm['daily_times']);
											//var_dump($daily_times1);
											if($rowm['daily_times'] != NULL && $rowm['daily_times'] != ""){
												foreach($daily_times1 as $daily_time1){
													
													$int_time1 = date('H:i:s', strtotime($daily_time1));
													
													if($current_time <= $int_time1){
														
														$daily_times[] = $daily_time1;
													}else{
														$daily_times[] = $daily_time1;
													}
													
												}
											}else{
												$daily_times[] = $taskTime;
											}
											
											/*var_dump($rowm['tags_id']);
											var_dump($tag_info['facilities_id']);
											
											var_dump($rowm['tags_medication_details_id']);
											var_dump($rowm['tags_medication_details_ids']);
											
											
											var_dump($rowm['is_updated']);
											
											echo "<hr>";
											*/
											
											$end_recurrence_date = date('m-d-Y', strtotime($rowm['date_to']));
											
											//echo "<hr>";
											
											if($rowm['is_updated'] == '1'){
												
												if($rowm['daily_times'] != null && $rowm['daily_times'] != ""){
													
													$daily_times1_1 = explode(",",$rowm['daily_times']);
													//var_dump($daily_times1_1);
													$daily_tim11es = array();
													foreach($daily_times1_1 as $daily_times1_11){
														$int_timaae1 = date('H:i:s', strtotime($daily_times1_11));
														$sqlt21 = "SELECT id,date_added,task_time from " . DB_PREFIX . "createtask where complete_status = '".$rowm['is_schedule_id']."' and task_time ='".$int_timaae1."' and medication_tags = '".$rowm['tags_id']."' ";
														$qt21 = $this->db->query($sqlt21);
														
														$task_id = $qt21->row['id'];
														$date_added = $qt21->row['date_added'];
														$task_time = $qt21->row['task_time'];
														//var_dump($qt21->row);
														//echo "<hr>";
														
														
														
														
														$ssss = array();
														$sssa1a = array();
														$sssa1a[] = $rowm['tags_medication_details_id'];
														$tags_medication_details_ids = explode(",",$rowm['tags_medication_details_ids']);
														
														$arrr_mss = array();
														$arrr_mss = array_merge($sssa1a,$tags_medication_details_ids);
													
														$ssss = array_unique($arrr_mss);
														
														//var_dump($ssss);
														//echo "<hr>";
														
														if($task_id > 0){
															$sqldw = "DELETE FROM `" . DB_PREFIX . "createtask_medications` where id = '".$task_id."' ";
															$this->db->query($sqldw);	
															
															foreach($ssss as $ssssss){
																$sql22 = "INSERT INTO `" . DB_PREFIX . "createtask_medications` SET id = '" . $task_id . "', facilities_id = '" . $this->db->escape($tag_info['facilities_id']) . "', tags_id = '" . $this->db->escape($rowm['tags_id']) . "', tags_medication_details_id = '" . $this->db->escape($ssssss) . "', date_added = '" . $this->db->escape($date_added) . "', complete_status = '".$rowm['is_schedule_id']."' ";
																$this->db->query($sql22);
															}
															
															
															$sqlu = "UPDATE `" . DB_PREFIX . "tags_medication_details` SET create_task = '1', is_updated = '0' where tags_medication_details_id = '".$rowm['tags_medication_details_id']."' ";
															$this->db->query($sqlu);
															
														}else{
															$sqlu = "UPDATE `" . DB_PREFIX . "tags_medication_details` SET create_task = '0', is_updated = '0' where tags_medication_details_id = '".$rowm['tags_medication_details_id']."' ";
															$this->db->query($sqlu);
															
															$sqld3w = "DELETE FROM " . DB_PREFIX . "createtask where complete_status = '".$rowm['is_schedule_id']."' and medication_tags = '".$rowm['tags_id']."' ";
															$this->db->query($sqld3w);	
															
															$sqldw22 = "DELETE FROM " . DB_PREFIX . "createtask_medications where complete_status = '".$rowm['is_schedule_id']."' and tags_id = '".$rowm['tags_id']."' ";
															$this->db->query($sqldw22);	
															
														}
														
														
														/*
														*/
														/*if($rowm['tags_medication_details_id']){
															echo $sqlt1 = "SELECT * from " . DB_PREFIX . "createtask_medications where tags_medication_details_id = '".$rowm['tags_medication_details_id']."' and id = '".$task_id ."' ";
															$qt1 = $this->db->query($sqlt1);
														}
														
														var_dump($qt1->row);
														echo "<hr>";
														
														if($rowm['tags_medication_details_ids'] != null && $rowm['tags_medication_details_ids'] != ""){
															$sqlt2 = "SELECT * from " . DB_PREFIX . "createtask_medications where tags_medication_details_id in (".$rowm['tags_medication_details_ids'].") and id = '".$task_id ."' ";
															$qt2 = $this->db->query($sqlt2);
														}
														
														var_dump($qt2->row);
														*/
													}
													
												}
												
												
												
											}else{
											
												$addtaskw['taskDate'] = date('m-d-Y', strtotime($noteDate));
												//$addtaskw['end_recurrence_date'] = date('m-d-Y', strtotime($noteDate));
												$addtaskw['end_recurrence_date'] = $end_recurrence_date;
												$addtaskw['recurrence'] = 'none';
												$addtaskw['recurnce_week'] = '';
												$addtaskw['recurnce_hrly'] = '';
												$addtaskw['recurnce_month'] = '';
												$addtaskw['recurnce_day'] = '';
												$addtaskw['taskTime'] = $taskTime; //date('H:i:s');
												$addtaskw['endtime'] = $taskTime;
												$addtaskw['description'] = 'Medication for '.$tag_info['emp_first_name'].' '.$tag_info['emp_last_name'];
												$addtaskw['assignto'] = '';
												$addtaskw['tasktype'] = '2';
												$addtaskw['numChecklist'] = '';
												$addtaskw['task_alert'] = '1';
												$addtaskw['alert_type_sms'] = '';
												$addtaskw['alert_type_notification'] = '1';
												$addtaskw['alert_type_email'] = '';
												$addtaskw['rules_task'] = '';
																
												$addtaskw['locations_id'] = '';
												$addtaskw['facilities_id'] = $tag_info['facilities_id'];
												$addtaskw['medication_tags'] = explode(",",$rowm['tags_id']);		
												//$addtaskw['daily_times'] = explode(",",$rowm['daily_times']);		
												$addtaskw['daily_times'] = $daily_times;		
												
												
												$sss = array();
												$sssaa = array();
												$sssaa[] = $rowm['tags_medication_details_id'];
												
												if($rowm['tags_medication_details_ids'] != null && $rowm['tags_medication_details_ids'] != ""){
													$tags_medication_details_ids = explode(",",$rowm['tags_medication_details_ids']);
												}else{
													$tags_medication_details_ids = array();	
												}
												
												
												$arrr_m = array();
												$arrr_m = array_merge($sssaa,$tags_medication_details_ids);
											
												$sss = array_unique($arrr_m);
												
												
												$tags_medication_details_ids = array();
												//$tags_medication_details_ids[$rowm['tags_id']][] = $rowm['tags_medication_details_id'];
												$tags_medication_details_ids[$rowm['tags_id']] = $sss;
												
												$addtaskw['tags_medication_details_ids'] = $tags_medication_details_ids;
												
												if($rowm['complete_status'] > 0){
													$complete_status = $rowm['complete_status'];
												}else{
													$complete_status = rand();
												}
												
												
												$addtaskw['complete_status'] = $complete_status;

											
												//var_dump($addtaskw);
												//echo "<hr>";
												$sqlu = "UPDATE `" . DB_PREFIX . "tags_medication_details` SET create_task = '1', is_updated = '0' , is_schedule_id = '".$complete_status."' where tags_medication_details_id = '".$rowm['tags_medication_details_id']."' ";
												$this->db->query($sqlu);
												
												$task_id = $this->model_createtask_createtask->addcreatetask($addtaskw, $tag_info['facilities_id']);
												
											}
											
											
										
										/*}*/
									/*}*/
								}
							}
						}
					}
				}
				
				$noteDate3 = date('Y-m-d', strtotime('now'));
					
				$sqlt = "SELECT * from " . DB_PREFIX . "createtask where completion_alert = '1' and taskadded = '0' and is_send_reminder = '0' and facilityId = '".$tresult['facilities_id']."' and (`date_added` BETWEEN  '".$noteDate3." 00:00:00' AND  '".$noteDate3." 23:59:59') ";
				$qt = $this->db->query($sqlt);
					
				$this->load->model('notes/notes');
				$this->load->model('facilities/facilities');
				
				if($qt->num_rows > 0){
					foreach($qt->rows as $result1){
						
						
						$completed_times = explode(",",$result1['completed_times']);
						
						$facilities_info = $this->model_facilities_facilities->getfacilities($result1['facilityId']);
							
						$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
							
						date_default_timezone_set($timezone_info['timezone_value']);
							
						$currentTime = date('H:i', strtotime('now'));
						
						foreach($completed_times as $completed_time){
							$completed_time1 = date('H:i', strtotime($completed_time));
							//var_dump($currentTime);
							//var_dump($completed_time1);
							//echo "<hr>";
							
							if($currentTime == $completed_time1){
							
							if($result1['user_roles'] != null && $result1['user_roles'] != ""){
								$user_roles1 = explode(',',$result1['user_roles']);
								
								$this->load->model('user/user_group');
								$this->load->model('user/user');
								$this->load->model('setting/tags');
								
								$this->load->model('api/emailapi');
								$this->load->model('api/smsapi');

								foreach ($user_roles1 as $user_role) {
									
									$urole = array();
									$urole['user_group_id'] = $user_role;
									$tusers = $this->model_user_user->getUsers($urole);
									
									if($tusers){
										foreach ($tusers as $tuser) {
											
											if($tuser['phone_number']){
												if($result1['completion_alert_type_sms'] == '1'){
													$message = "Task Reminder ".date('h:i A',strtotime($result1['task_time']))."...\n";
													$message .= "Task Type: ". $result1['tasktype']."\n";
													
													
													if($result['emp_tag_id'] != null && $result['emp_tag_id'] != ""){
															$tags_info1 = $this->model_setting_tags->getTag($result['emp_tag_id']);
														
															if($tags_info1['emp_first_name']){
																$emp_tag_id = $tags_info1['emp_tag_id'].':'.$tags_info1['emp_first_name'];
															}else{
																$emp_tag_id = $tags_info1['emp_tag_id'];
															}
																
															if ($tags_info1) {
																$message .= "Client Name: ". $emp_tag_id."\n";
															}
													}
													
													if($result['medication_tags'] != null && $result['medication_tags'] != ""){
														$tags_info1 = $this->model_setting_tags->getTag($result['medication_tags']);
															if($tags_info1['emp_first_name']){
																$emp_tag_id = $tags_info1['emp_tag_id'].':'.$tags_info1['emp_first_name'];
															}else{
																$emp_tag_id = $tags_info1['emp_tag_id'];
															}
																
															if ($tags_info1) {
																$message .= "Client Name: ". $emp_tag_id."\n";
															}
													}
													if($result['visitation_tag_id'] != null && $result['visitation_tag_id'] != ""){
														$tags_info1 = $this->model_setting_tags->getTag($result['visitation_tag_id']);
															if($tags_info1['emp_first_name']){
																$emp_tag_id = $tags_info1['emp_tag_id'].':'.$tags_info1['emp_first_name'];
															}else{
																$emp_tag_id = $tags_info1['emp_tag_id'];
															}
																
															if ($tags_info1) {
																$message .= "Client Name: ". $emp_tag_id."\n";
															}
													}
													if($result['transport_tags'] != null && $result['transport_tags'] != ""){
														
														$transport_tags1 = explode(',',$result['transport_tags']);
														
														$transport_tags = '';
														foreach ($transport_tags1 as $tag1) {
															$tags_info1 = $this->model_setting_tags->getTag($tag1);

															if($tags_info1['emp_first_name']){
																$emp_tag_id = $tags_info1['emp_tag_id'].':'.$tags_info1['emp_first_name'];
															}else{
																$emp_tag_id = $tags_info1['emp_tag_id'];
															}
																
															if ($tags_info1) {
																$transport_tags .= $emp_tag_id.', ';

															}
														}
														
														$message .= "Client Name: ". $transport_tags."\n";
													}
													$message .= "Description: ".substr($result1['description'], 0, 150) .((strlen($result1['description']) > 150) ? '..' : '')."\n";
													//$message .= "Description: ".$result1['description']."\n";
													
													$sdata = array();
													$sdata['message'] = $message;
													$sdata['phone_number'] = $tuser['phone_number'];
													$sdata['facilities_id'] = $result1['facilityId'];		
													$response = $this->model_api_smsapi->sendsms($sdata);
														
													
												}
											}
											
											
											if($tuser['email']){
												if($result1['completion_alert_type_email'] == '1'){
													
													$message33 = "";
													$messagebody = 'Task Reminder';
													$messagebody1 = 'The following task details.';
													$message33 .= $this->completeemailtemplate($result1, $result1['date_added'], $result1['task_time'], $messagebody, $messagebody1);
													
													//var_dump($message33);
													//die;
													
													$edata = array();
													$edata['message'] = $message33;
													$edata['subject'] = 'Task Reminder';
													$edata['user_email'] = $tuser['email'];
														
													$email_status = $this->model_api_emailapi->sendmail($edata);
												
													
												}
											}
											
											
										}
									}
									
									
								}
							}
							
							if($result1['userids'] != null && $result1['userids'] != ""){
								$userids1 = explode(',',$result1['userids']);
								
								$this->load->model('user/user');
								$this->load->model('setting/tags');
								
								$this->load->model('api/emailapi');
								$this->load->model('api/smsapi');

								foreach ($userids1 as $userid) {
									
									$user_info = $this->model_user_user->getUserbyupdate($userid);
									
									if ($user_info) {
										
										if($user_info['phone_number']){
											if($result1['completion_alert_type_sms'] == '1'){
												$message = "Task Reminder ".date('h:i A',strtotime($result1['task_time']))."...\n";
												$message .= "Task Type: ". $result1['tasktype']."\n";
												
												
													if($result['emp_tag_id'] != null && $result['emp_tag_id'] != ""){
															$tags_info1 = $this->model_setting_tags->getTag($result['emp_tag_id']);
														
															if($tags_info1['emp_first_name']){
																$emp_tag_id = $tags_info1['emp_tag_id'].':'.$tags_info1['emp_first_name'];
															}else{
																$emp_tag_id = $tags_info1['emp_tag_id'];
															}
																
															if ($tags_info1) {
																$message .= "Client Name: ". $emp_tag_id."\n";
															}
													}
													
													if($result['medication_tags'] != null && $result['medication_tags'] != ""){
														$tags_info1 = $this->model_setting_tags->getTag($result['medication_tags']);
															if($tags_info1['emp_first_name']){
																$emp_tag_id = $tags_info1['emp_tag_id'].':'.$tags_info1['emp_first_name'];
															}else{
																$emp_tag_id = $tags_info1['emp_tag_id'];
															}
																
															if ($tags_info1) {
																$message .= "Client Name: ". $emp_tag_id."\n";
															}
													}
													if($result['visitation_tag_id'] != null && $result['visitation_tag_id'] != ""){
														$tags_info1 = $this->model_setting_tags->getTag($result['visitation_tag_id']);
															if($tags_info1['emp_first_name']){
																$emp_tag_id = $tags_info1['emp_tag_id'].':'.$tags_info1['emp_first_name'];
															}else{
																$emp_tag_id = $tags_info1['emp_tag_id'];
															}
																
															if ($tags_info1) {
																$message .= "Client Name: ". $emp_tag_id."\n";
															}
													}
													if($result['transport_tags'] != null && $result['transport_tags'] != ""){
														
														$transport_tags1 = explode(',',$result['transport_tags']);
														
														$transport_tags = '';
														foreach ($transport_tags1 as $tag1) {
															$tags_info1 = $this->model_setting_tags->getTag($tag1);

															if($tags_info1['emp_first_name']){
																$emp_tag_id = $tags_info1['emp_tag_id'].':'.$tags_info1['emp_first_name'];
															}else{
																$emp_tag_id = $tags_info1['emp_tag_id'];
															}
																
															if ($tags_info1) {
																$transport_tags .= $emp_tag_id.', ';

															}
														}
														
														$message .= "Client Name: ". $transport_tags."\n";
													}
												//$message .= "Description: ".$result1['description']."\n";
													
													$message .= "Description: ".substr($result1['description'], 0, 150) .((strlen($result1['description']) > 150) ? '..' : '')."\n";
													
													$sdata = array();
													$sdata['message'] = $message;
													$sdata['phone_number'] = $user_info['phone_number'];
													$sdata['facilities_id'] = $result1['facilityId'];		
													$response = $this->model_api_smsapi->sendsms($sdata);
													
												
											}
										}
										
										if($user_info['email']){
											if($result1['completion_alert_type_email'] == '1'){
												
												$message33 = "";
												$messagebody = 'Task Reminder ';
												$messagebody1 = 'The following task details.';
												$message33 .= $this->completeemailtemplate($result1, $result1['date_added'], $result1['task_time'], $messagebody, $messagebody1);
												
												//var_dump($message33);
												//	die;
												
												$edata = array();
												$edata['message'] = $message33;
												$edata['subject'] = 'Task Reminder';
												$edata['user_email'] = $user_info['email'];
													
												$email_status = $this->model_api_emailapi->sendmail($edata);
											
												
											}
										}
									}
								}
							}
							}
							/*
							$sqlut = "UPDATE `" . DB_PREFIX . "createtask` SET is_send_reminder = '1' where id = '".$result1['id']."' ";
							$this->db->query($sqlut);
							*/
						
						
						}
					}
				}
				
				
					//$noteDate = date('Y-m-d', strtotime('now'));
					$noteDate = date('Y-m-d', strtotime('now'));
						
					 $sqlbedinfo = "SELECT max(id) as id FROM `" . DB_PREFIX . "createtask` WHERE ";
					//$sqlbedinfo .= " `end_recurrence_date` BETWEEN  '".$noteDate." 00:00:00' AND  '".$noteDate." 23:59:59' group by task_group_by ";
					$sqlbedinfo .= " `task_date` BETWEEN  '".$noteDate." 00:00:00' AND  '".$noteDate." 23:59:59' and facilityId = '".$tresult['facilities_id']."' group by task_group_by ";
					$sqlbedinfo .= " ORDER BY `task_time` DESC " ;
					
					
					$bed = $this->db->query($sqlbedinfo);
				
					
					if($bed->num_rows > 0){
						foreach($bed->rows as $row){
							
							$sqlt = "SELECT * from " . DB_PREFIX . "createtask WHERE id = '".$row['id']."' ";
							$qts = $this->db->query($sqlt);
							
							$sqltn = "SELECT DISTINCT COUNT(DISTINCT notes_id) AS total FROM " . DB_PREFIX . "notes WHERE task_group_by = '".$qts->row['task_group_by']."' and end_task = '1' ";
							$qtsn = $this->db->query($sqltn);
							
							if($qtsn->row['total'] == '0'){
								$sql4 = "UPDATE `" . DB_PREFIX . "createtask` SET end_task = '1' WHERE id = '".$row['id']."'";			
								$query = $this->db->query($sql4);	
							}
						}
					}
					
					
					$this->load->model('createtask/createtask');
					$tasktype_info = $this->model_createtask_createtask->gettasktyperow('11');
					
					if($tasktype_info['generate_report'] == '1'){
						$sqlbed = "SELECT * from " . DB_PREFIX . "notes where task_type = '1' and generate_report = '0' and tasktype = '11' and end_task = '1' and ( `date_added` BETWEEN '".$noteDate." 00:00:00 ' AND '".$noteDate." 23:59:59' ) and user_id != '".SYSTEM_GENERATED."' and facilities_id = '".$tresult['facilities_id']."' ";
						
						$q1 = $this->db->query($sqlbed);
						
						if($q1->num_rows > 0){
							
							foreach($q1->rows as $row){
								
								$facilities_info = $this->model_facilities_facilities->getfacilities($row['facilities_id']);
								$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
								
								date_default_timezone_set($timezone_info['timezone_value']);
								
								$noteDate = date('Y-m-d H:i:s', strtotime('now'));
								$date_added = (string) $noteDate;
								
								$noteDate1 = date('m-d-Y', strtotime('now'));
								$time1 = date('h:i A', strtotime('now'));
								
								$data = array();
								
								$notetime = date('H:i:s', strtotime('now'));
								$data['imgOutput'] = '';
								
								
								$data['notes_pin'] = SYSTEM_GENERATED_PIN;
								$data['user_id'] = SYSTEM_GENERATED;
								
								$data['notetime'] = $notetime;
								$data['note_date'] = $date_added;
								$data['facilitytimezone'] = $timezone_name;
							
								
								$data['date_added'] = $date_added;
								
								$sql2 = "SELECT * from " . DB_PREFIX . "notes_tags where notes_id = '".$row['notes_id']."'";
								$q2 = $this->db->query($sql2);
								
								$tags_id =  $q2->row['tags_id'];
								
								$this->load->model('setting/tags');
								$tags_info = $this->model_setting_tags->getTag($tags_id);
								
								$data['emp_tag_id'] = $tags_info['emp_tag_id'];
								$data['tags_id'] = $tags_info['tags_id'];
								
								$data['notes_description'] = ' REPORT Auto Generated | Bed Check ';
							
								$notes_id = $this->model_notes_notes->jsonaddnotes($data, $row['facilities_id']);
								
								
								
								$slq1 = "UPDATE " . DB_PREFIX . "notes SET generate_report = '1' where notes_id = '".$row['notes_id']."'";
								$this->db->query($slq1);
								
								
								$slq1 = "UPDATE " . DB_PREFIX . "notes SET generate_report = '3', task_group_by = '".$row['task_group_by']."', parent_id = '".$row['parent_id']."', task_type = '".$row['task_type']."' where notes_id = '".$notes_id."'";
								$this->db->query($slq1);
								
							}
						}
					}
					
					
					$noteDate = date('Y-m-d', strtotime('now'));
						
					$sqlbedinfo1 = "SELECT * FROM `" . DB_PREFIX . "createtask` WHERE ";
					$sqlbedinfo1 .= " `task_date` BETWEEN  '".$noteDate." 00:00:00' AND  '".$noteDate." 23:59:59' and is_transport = '1' and facilityId = '".$tresult['facilities_id']."' ";
					
					$beddd = $this->db->query($sqlbedinfo1);
					
					$this->load->model('createtask/createtask');
					$this->load->model('setting/timezone');
					$this->load->model('facilities/facilities');
				
				
					if($beddd->num_rows > 0){
						foreach($beddd->rows as $alltask){
							
							$task_info = $this->model_createtask_createtask->gettasktyperowByName($alltask['tasktype']);
							
							if($task_info['auto_extend'] == '1'){

								$originaltasktime = $alltask['task_time'];
								$new_task_time = date("H:i:s",strtotime("+".$task_info['auto_extend_time']." minutes",strtotime($alltask['task_time'])));
							
							
								$tasktime = date("H:i",strtotime("-3 minutes",strtotime($alltask['task_time'])));
								
								$facility = $this->model_facilities_facilities->getfacilities($alltask['facilityId']);
								$timezone_info = $this->model_setting_timezone->gettimezone($facility['timezone_id']);
								date_default_timezone_set($timezone_info['timezone_value']);
				   
								$currenttime = date("H:i");
							
							
								if($tasktime <= $currenttime){
									if($alltask['original_task_time'] == '00:00:00'){
									
										$sql3 = "UPDATE `" . DB_PREFIX . "createtask` SET original_task_time = '".$originaltasktime."',task_time = '".$new_task_time."'  WHERE id = '".$alltask['id']."'";			
									
										$query = $this->db->query($sql3);	
									
									}else{
									
										$sql3 = "UPDATE `" . DB_PREFIX . "createtask` SET task_time = '".$new_task_time."'  WHERE id = '".$alltask['id']."'";			
										$query = $this->db->query($sql3);
									
										
									}
								
								}
								
							}
							
						}
					}
					
					
					$noteDate = date('Y-m-d', strtotime('now'));
			
					$sql = "SELECT * from " . DB_PREFIX . "notes where end_perpetual_task = '2' and generate_report = '0' and recurrence = 'Perpetual' and ( `date_added` BETWEEN '".$noteDate." 00:00:00 ' AND '".$noteDate." 23:59:59' ) and facilities_id = '".$tresult['facilities_id']."' ";
					$q = $this->db->query($sql);
					
					$this->load->model('notes/notes');
					$this->load->model('facilities/facilities');
					
					//var_dump($q->num_rows);
					
					if($q->num_rows > 0){
						foreach($q->rows as $row){
							
							if($row['tasktype'] == '25'){
								$facilities_info = $this->model_facilities_facilities->getfacilities($row['facilities_id']);
								
								$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
								
								date_default_timezone_set($timezone_info['timezone_value']);
								
								$noteDate = date('Y-m-d H:i:s', strtotime('now'));
								$date_added = (string) $noteDate;
								
								$noteDate1 = date('m-d-Y', strtotime('now'));
								$time1 = date('h:i A', strtotime('now'));
								
								$data = array();
								
								$notetime = date('H:i:s', strtotime('now'));
								$data['imgOutput'] = '';
								
								$data['notes_pin'] = SYSTEM_GENERATED_PIN;
								$data['user_id'] = SYSTEM_GENERATED;
								
								
								$data['notetime'] = $notetime;
								$data['note_date'] = $date_added;
								$data['facilitytimezone'] = $timezone_name;
							
								
								$data['date_added'] = $date_added;
								
								$sql2 = "SELECT * from " . DB_PREFIX . "notes_tags where notes_id = '".$row['notes_id']."'";
								$q2 = $this->db->query($sql2);
								
								$tags_id =  $q2->row['tags_id'];
								
								$this->load->model('setting/tags');
								$tags_info = $this->model_setting_tags->getTag($tags_id);
								
								$data['emp_tag_id'] = $tags_info['emp_tag_id'];
								$data['tags_id'] = $tags_info['tags_id'];
								
								$data['notes_description'] = ' REPORT Auto Generated | Sight and Sound ';
							
								$notes_id = $this->model_notes_notes->jsonaddnotes($data, $row['facilities_id']);
								
								
								$facilities_info = $this->model_facilities_facilities->getfacilities($row['facilities_id']);
								
								
								$data2 = array();
								$data2['design_forms'][0][0]['date_93638826'] = $noteDate1;
								$data2['design_forms'][0][0]['time_33135211'] = $time1;
								$data2['design_forms'][0][0]['select_35510589'] = 'Yes';
								$data2['design_forms'][0][0]['select_93830432'] = 'Yes';
								$data2['design_forms'][0][0]['text_61453229'] =  $tags_info['emp_first_name'] .' '.$tags_info['emp_last_name'];
								$data2['design_forms'][0][0]['text_61453229_1_tags_id'] =  $tags_info['tags_id'];
								
								$data2['design_forms'][0][0]['date_82208178'] = date('m-d-Y',strtotime($tags_info['dob']));
								
								$data2['design_forms'][0][0]['text8'] =  '';
								$data2['design_forms'][0][0]['text9'] =  '';
								
								if($tags_info['select_82298274'] == '1'){
									$select10 = 'Male';
								}else{
									$select10 = 'Female';
								}
								
								/*$sql2 = "SELECT * from " . DB_PREFIX . "notes where parent_id = '".$row['parent_id']."'";
								$q22 = $this->db->query($sql2);
								
								//var_dump($q22->rows);
								
								foreach($q22->rows as $row1){
									$customlistvalues_id = $row1['customlistvalues_id'];
									
								
								
									if($customlistvalues_id == '4'){
										$data2['design_forms']['customlist25'] =  $customlistvalues_id;
									}
									
									if($customlistvalues_id == '6'){
										$data2['design_forms']['customlist26'] =  $customlistvalues_id;
									}
								}*/
								
								$data2['design_forms'][0][0]['select_82298274'] =  $select10;
								$data2['design_forms'][0][0]['text_64107947'] =  $facilities_info['facility'];
								$data2['design_forms'][0][0]['text12'] =  '';
								
								$data2['design_forms'][0][0]['0']['checkbox_45658071'] =  'Sucide Risk';
								$data2['design_forms'][0][0]['1']['checkbox_45658071'] =  '';
								$data2['design_forms'][0][0]['2']['checkbox_45658071'] =  '';
								$data2['design_forms'][0][0]['3']['checkbox_45658071'] =  '';
								$data2['design_forms'][0][0]['4']['checkbox_45658071'] =  '';
								
								
								
								$data2['design_forms'][0][0]['date_48860525'] =   $noteDate1;
								$data2['design_forms'][0][0]['time_41789102'] =   $time1;
								
								
								$data2['signature'][0][0]['signature14'] =   '';
								$data2['signature'][0][0]['signature18'] =   '';
								
								$data2['design_forms'][0][0]['date_31171166'] =  $noteDate1;
								$data2['design_forms'][0][0]['time_88128841'] =   $time1;
								
								$data2['design_forms'][0][0]['text21'] =   $tags_info['emp_first_name'] .' '.$tags_info['emp_last_name'];
								$data2['design_forms'][0][0]['text22'] =   '';//$tags_info['ssn'];
								
								$data23 = array();
								$data23['forms_design_id'] = '13';
								$data23['notes_id'] = $notes_id;
								$data23['tags_id'] = $tags_id;
								$data23['facilities_id'] = $row['facilities_id'];
								
								
								
								
								$this->load->model('form/form');
								$formreturn_id = $this->model_form_form->addFormdata($data2, $data23);	
								
								$slq1 = "UPDATE " . DB_PREFIX . "forms SET tags_id = '".$tags_id."',parent_id = '".$row['parent_id']."' where forms_id = '".$formreturn_id."'";
								$this->db->query($slq1);
								
								
								$slq1 = "UPDATE " . DB_PREFIX . "notes SET generate_report = '1' where notes_id = '".$row['notes_id']."'";
								$this->db->query($slq1);
								
								
								$slq1 = "UPDATE " . DB_PREFIX . "notes SET generate_report = '2', is_forms = '1' where notes_id = '".$notes_id."'";
								$this->db->query($slq1);
								
							}
						}
					}
				
			}
			
		}
		
		
		echo "Success";
	}
	
	
	public function completeemailtemplate($result, $taskDate, $taskeTiming, $headerbody, $messagebody1){
		
		$this->load->model('setting/locations');
		$bedcheckdata = array();
				
		if($result['task_form_id'] != 0 && $result['task_form_id'] != NULL ){
			$formDatas = $this->model_setting_locations->getformid($result['task_form_id']);	
							
			foreach($formDatas as $formData){
				$locData = $this->model_setting_locations->getlocation($formData['locations_id']);
						
				$locationDatab = array();
				$location_type = "";
							
				$location_typea = $locData['location_type'];
				if($location_typea == '1'){
					$location_type .= "Boys";
				} 
								
				if($location_typea == '2'){
					$location_type .= "Girls";
				}
								
				if($location_typea == '3'){
					$location_type .= "Inmates";
				}
							
				if($locData['upload_file'] != null && $locData['upload_file'] != ""){
					$upload_file = $locData['upload_file'];
				}else{
					$upload_file = "";
				}
				$locationDatab[] = array(
					'locations_id' =>$locData['locations_id'],
					'location_name' =>$locData['location_name'],
					'location_address' =>$locData['location_address'],
					'location_detail' =>$locData['location_detail'],
					'capacity' =>$locData['capacity'],
					'location_type' =>$location_type,
					'upload_file' =>$upload_file,
					'nfc_location_tag' =>$locData['nfc_location_tag'],
					'nfc_location_tag_required' =>$locData['nfc_location_tag_required'],
					'gps_location_tag' =>$locData['gps_location_tag'],
					'gps_location_tag_required' =>$locData['gps_location_tag_required'],
					'latitude' =>$locData['latitude'],
					'longitude' =>$locData['longitude'],
					'other_location_tag' =>$locData['other_location_tag'],
					'other_location_tag_required' =>$locData['other_location_tag_required'],
					'other_type_id' =>$locData['other_type_id'],
					'facilities_id' =>$locData['facilities_id']
									
				);
							
							
							  
				$bedcheckdata[] = array(
					'task_form_location_id' =>$formData['task_form_location_id'],
					'location_name' =>$formData['location_name'],
					'location_detail' =>$formData['location_detail'],
					'current_occupency' =>$formData['current_occupency'],
					'bedcheck_locations' =>$locationDatab
				);
			}
					
			/*$this->load->model('setting/bedchecktaskform');
			$taskformData = $this->model_setting_bedchecktaskform->getbedchecktaskform($list['task_form_id']);
					
			foreach($taskformData  as $frmData){
				$taskformsData[] = array(
					'task_form_name' =>$frmData['task_form_name'],
					'facilities_id' =>$frmData['facilities_id'],
					'form_type' =>$frmData['form_type']
				);
			}*/
					 
		}
		
		//var_dump($bedcheckdata);
		
		
		$transport_tags = array();
		$this->load->model('setting/tags');
					
		if (!empty($result['transport_tags'])) {		
			$transport_tags1 = explode(',',$result['transport_tags']);
		} else {
			$transport_tags1 = array();
		}

		foreach ($transport_tags1 as $tag1) {
			$tags_info = $this->model_setting_tags->getTag($tag1);

			if($tags_info['emp_first_name']){
				$emp_tag_id = $tags_info['emp_tag_id'].': '.$tags_info['emp_first_name'];
			}else{
				$emp_tag_id = $tags_info['emp_tag_id'];
			}
						
			if ($tags_info) {
				$transport_tags[] = array(
					'tags_id' => $tags_info['tags_id'],
					'emp_tag_id'        => $emp_tag_id
				);
			}
		}
					
					
		$medication_tags = array();
		$this->load->model('setting/tags');
					
		if (!empty($result['medication_tags'])) {		
			$medication_tags1 = explode(',',$result['medication_tags']);
		} else {
			$medication_tags1 = array();
		}

		foreach ($medication_tags1 as $medicationtag) {
			$tags_info1 = $this->model_setting_tags->getTag($medicationtag);

			if($tags_info1['emp_first_name']){
				$emp_tag_id = $tags_info1['emp_tag_id'].': '.$tags_info1['emp_first_name'];
			}else{
				$emp_tag_id = $tags_info1['emp_tag_id'];
			}
						
			if ($tags_info1) {
							
				$drugs = array();
							
				$mdrugs = $this->model_setting_tags->getTagsMedicationdetailsByID($result['id'], $medicationtag);
							
				foreach($mdrugs as $mdrug){
								
					$mdrug_info = $this->model_setting_tags->getTagsMedicationdruglByID($mdrug['tags_medication_details_id']);
								
					$drugs[] = array(
						'drug_name' =>$mdrug_info['drug_name']
					);
				}
							
							
				$medication_tags[] = array(
					'tags_id' => $tags_info1['tags_id'],
					'emp_tag_id'        => $emp_tag_id,
					'tagsmedications' => $drugs, 
				);
			}
		}
					
					
		if($result['visitation_tag_id']){
			$visitation_tag = $this->model_setting_tags->getTag($result['visitation_tag_id']);
						
			if($visitation_tag['emp_first_name']){
				$visitation_tag_id = $visitation_tag['emp_tag_id'].': '.$visitation_tag['emp_first_name'];
			}else{
				$visitation_tag_id = $visitation_tag['emp_tag_id'];
			}
		}else{
			$visitation_tag_id = "";
		}
		
		
		//die;
		
		$html = "";
		$html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>'.$headerbody.'</title>

<style>
@media screen and (max-width:500px) {
   h6 {
        font-size: 12px !important;
    }
}
</style>
</head>
 
<body bgcolor="#FFFFFF" topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style=" -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none;width: 100%!important;height: 100%;padding: 0;margin: 0;font-family: Open Sans, sans-serif;">

<table class="head-wrap" style="width: 100%;background: #fff; border-spacing: 0;">
	<tr>
		<td></td>
		<td class="header container" align="" style="display: block!important;max-width: 600px!important;margin: 0 auto!important; clear: both!important;">
			

			<div class="content" style="padding: 15px;max-width: 600px;margin: 0 auto;display: block;padding-right: 0;padding-left: 0;">
				<table style="width: 100%;">
					<tr>
						<td><img src="'.HTTP_SERVER.'sites/view/digitalnotebook/stylesheet/email/logo.png" style="width: 100%;" /></td>
						<td align="right" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">'.$headerbody.'</h6></td>
					</tr>
				</table>
			</div>
			
		</td>
		<td></td>
	</tr>
</table>

<table class="body-wrap" bgcolor="" style="width: 100%;    border-spacing: 0;">
	<tr>
		<td></td>
		<td class="container" align="" bgcolor="#c1c1c1" style="display: block!important;max-width: 600px!important;margin: 0 auto!important; clear: both!important;">

			<div class="content" style="padding: 15px;max-width: 600px;margin: 0 auto;display: block; background: #c1c1c1;border-bottom: 2px solid #2c3742;">
				<table>
					<tr>
						<td>
							
							<h1 style="font-weight: 200;font-size: 30px;padding: 0;margin: 0;">Hello '.$result['assign_to'].'!</h1>
							<p class="lead" style="font-size: 14px;margin-bottom: 10px;font-weight: normal;line-height: 1.6;">'.$headerbody.' - '.$messagebody1.'</p>
							
						</td>
					</tr>
				</table>
			</div>';
			
			if(($medication_tags != null && $medication_tags != "") || ($result['pickup_locations_address'] != null && $result['pickup_locations_address'] != "") || ($visitation_tag_id != null && $visitation_tag_id != "") || ($bedcheckdata != null && $bedcheckdata != "")){
			$html .= '<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="'.$result['href'].'">
						<img src="'.HTTP_SERVER.'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Who </h4>';
							
							$html .= '<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">';
							//$html .= $result['description'];
							
							if($medication_tags != null && $medication_tags != ""){
								foreach($medication_tags as $medication_tag){
									$html .= 'Client Name: '.$medication_tag['emp_tag_id'].'<br>';
									foreach($medication_tag['tagsmedications'] as $drug){
										$html .= 'Drug Name: '.$drug['drug_name'].'';
										$html .= "<div style='border-bottom:1px solid #eee;'></div>";
									}
								}
							}
												
							if($medications != null && $medications != ""){
								foreach($medications as $medication){
									foreach($medication['medications_drugs'] as $drug){
										$html .= 'Drug Name: '.$drug['drug_name'].'<br>';
										$html .= 'Dose: '.$drug['dose'].'<br>';
										$html .= 'Drug Type: '.$drug['drug_type'].'<br>';
										$html .= 'Quantity: '.$drug['quantity'].'<br>';
										$html .= 'Instructions: '.$drug['instructions'].'<br>';
										$html .= 'Count: '.$drug['count'];
										$html .= "<div style='border-bottom:1px solid #eee;'></div>";
									}
								}
							}
												
							//var_dump($tasklist['transport_tags']);
												
							if($result['pickup_locations_address'] != null && $result['pickup_locations_address'] != ""){
								if($transport_tags){
									foreach($transport_tags as $tag){
										$html .= 'Client Name: '.$tag['emp_tag_id'].'<br>';
									}
								}
													
								$html .= '<br>Pickup Address: '.$result['pickup_locations_address'].'<br>';
								$html .= 'Pickup Time: '.date('h:i A',strtotime($result['pickup_locations_time'])).'<br>';
								$html .= 'Dropoff Address: '.$result['dropoff_locations_address'].'<br>';
								$html .= 'Dropoff Time: '.date('h:i A',strtotime($result['dropoff_locations_time'])).'<br>';
														
								$html .= "<div style='border-bottom:1px solid #eee;'></div>";
							}
												
												
							if($visitation_tag_id != null && $visitation_tag_id != ""){
														
								$html .= 'Client Name: '.$visitation_tag_id.'<br>';
													
								$html .= '<br>Start Address: '.$result['visitation_start_address'].'<br>';
								$html .= 'Start Time: '.date('h:i A',strtotime($taskeTiming)).'<br>';
								$html .= 'Appoitment Address: '.$result['visitation_appoitment_address'].'<br>';
								$html .= 'Appoitment Time: '.date('h:i A',strtotime($result['visitation_appoitment_time'])).'<br>';
														
								$html .= "<div style='border-bottom:1px solid #eee;'></div>";
							}
							
							if($bedcheckdata != null && $bedcheckdata != ""){
								foreach($bedcheckdata as $bedcheckda){
									
									$html .= 'Location Name: '.$bedcheckda['location_name'].'<br>';
									foreach($bedcheckda['bedcheck_locations'] as $bedcheck_location){
										//$html .= 'Location Name: '.$bedcheck_location['location_name'].'<br>';
										$html .= 'Capacity: '.$bedcheck_location['capacity'].'<br>';
										$html .= 'Type: '.$bedcheck_location['location_type'].'<br>';
										$html .= 'Location Detail: '.$bedcheck_location['location_detail'].'<br>';
										$html .= 'Location Address: '.$bedcheck_location['location_address'].'';
										$html .= "<div style='border-bottom:1px solid #eee;'></div>";
									}
								}
							}
							
							
							$html .= '</p>';
							
							
						$html .= '</td>
					</tr>
				</table>
			
			</div>';
			}
			
			$html .= '<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="'.$result['href'].'">
						<img src="'.HTTP_SERVER.'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">What <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">Task Type: '.$result['tasktype'].'</small></h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							'.$result['description'].'
							</p>
						</td>
					</tr>
				</table>
			
			</div>';
			
			
			
			
			
			$html .= '<div class="content" style="max-width: 600px;margin: 0 auto;display: block;    background: #fa801b;">
			<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
				<tr>
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="'.$result['href'].'">
					<img src="'.HTTP_SERVER.'sites/view/digitalnotebook/stylesheet/email/when.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">When</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
						'.date('j, F Y', strtotime($taskDate)).'&nbsp;'.date('h:i A',strtotime($taskeTiming)).'
						</p>
					</td>
				</tr>
			</table></div>
			

		</td>
		<td></td>
	</tr>
</table>

</body>
</html>';



return $html;
}											

	
	public function emailtemplate($result){
		
		//var_dump($result); 
		$html = "";
		$html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html>
	<head>
	<meta name="viewport" content="width=device-width" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Warehouse Status Report</title>

	<style>
	@media screen and (max-width:500px) {
	   h6 {
			font-size: 12px !important;
		}
	}
	</style>
	</head>
	 
	<body bgcolor="#FFFFFF" topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style=" -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none;width: 100%!important;height: 100%;padding: 0;margin: 0;font-family: Open Sans, sans-serif;">

	<table class="head-wrap" style="width: 100%;background: #fff; border-spacing: 0;">
		<tr>
			<td></td>
			<td class="header container" align="" style="display: block!important;max-width: 600px!important;margin: 0 auto!important; clear: both!important;">
				

				<div class="content" style="padding: 15px;max-width: 600px;margin: 0 auto;display: block;padding-right: 0;padding-left: 0;">
					<table style="width: 100%;">
						<tr>
							<td><img src="'.HTTP_SERVER.'sites/view/digitalnotebook/stylesheet/email/logo.png" style="width: 100%;" /></td>
							<td align="right" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">Warehouse Status Report</h6></td>
						</tr>
					</table>
				</div>
				
			</td>
			<td></td>
		</tr>
	</table>

	<table class="body-wrap" bgcolor="" style="width: 100%;    border-spacing: 0;">
		<tr>
			<td></td>
			<td class="container" align="" bgcolor="#c1c1c1" style="display: block!important;max-width: 600px!important;margin: 0 auto!important; clear: both!important;">

				<div class="content" style="padding: 15px;max-width: 600px;margin: 0 auto;display: block; background: #c1c1c1;border-bottom: 2px solid #2c3742;">
					<table>
						<tr>
							<td>
								
								<h1 style="font-weight: 200;font-size: 30px;padding: 0;margin: 0;">Hello Admin!</h1>
								<p class="lead" style="font-size: 14px;margin-bottom: 10px;font-weight: normal;line-height: 1.6;">Data has been transferred to warehouse successfully the following is the details :</p>
								
							</td>
						</tr>
					</table>
				</div>';
				foreach($result['facilities'] as $result_f){
					
					$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "facilities` WHERE facilities_id = '" . (int)$result_f . "'");
					$facility_info = $query->row;
					
					
					$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "notes` where `update_date` BETWEEN  '".$result['endDate']." 00:00:00 ' AND  '".$result['endDate']." 23:59:59' and facilities_id = '" . (int)$result_f . "' and notes_conut = '1' ";
					$query2 = $this->db->query($sql);
					$facility_notes = $query2->row['total'];
					
					
					$html .= '<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
						
						<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
							<tr>
								<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="#">
								<img src="'.HTTP_SERVER.'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
								<td>
									<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">What <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">Total No. Records: '.$facility_notes.'</small></h4>
									<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
									'.$facility_info['facility'].'
									</p>
								</td>
							</tr>
						</table>
					
					</div>';
					
				}
				
				$html .= '<div class="content" style="max-width: 600px;margin: 0 auto;display: block;    background: #fa801b;">
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="#">
						<img src="'.HTTP_SERVER.'sites/view/digitalnotebook/stylesheet/email/when.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">When</h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							'.date('j, F Y', strtotime($result['endDate'])).'
							</p>
						</td>
					</tr>
				</table></div>
				

			</td>
			<td></td>
		</tr>
	</table>

	</body>
	</html>';
	return $html;

	}

	public function alertemailtemplate(){
		$html = "";
		$html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html>
		<head>
		<meta name="viewport" content="width=device-width" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Warehouse Alert</title>

		<style>
		@media screen and (max-width:500px) {
		   h6 {
				font-size: 12px !important;
			}
		}
		</style>
		</head>
		 
		<body bgcolor="#FFFFFF" topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style=" -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none;width: 100%!important;height: 100%;padding: 0;margin: 0;font-family: Open Sans, sans-serif;">

		<table class="head-wrap" style="width: 100%;background: #fff; border-spacing: 0;">
			<tr>
				<td></td>
				<td class="header container" align="" style="display: block!important;max-width: 600px!important;margin: 0 auto!important; clear: both!important;">
					

					<div class="content" style="padding: 15px;max-width: 600px;margin: 0 auto;display: block;padding-right: 0;padding-left: 0;">
						<table style="width: 100%;">
							<tr>
								<td><img src="'.HTTP_SERVER.'sites/view/digitalnotebook/stylesheet/email/logo.png" style="width: 100%;" /></td>
								<td align="right" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">Warehouse Alert</h6></td>
							</tr>
						</table>
					</div>
					
				</td>
				<td></td>
			</tr>
		</table>

		<table class="body-wrap" bgcolor="" style="width: 100%;    border-spacing: 0;">
			<tr>
				<td></td>
				<td class="container" align="" bgcolor="#c1c1c1" style="display: block!important;max-width: 600px!important;margin: 0 auto!important; clear: both!important;">

					<div class="content" style="padding: 15px;max-width: 600px;margin: 0 auto;display: block; background: #c1c1c1;border-bottom: 2px solid #2c3742;">
						<table>
							<tr>
								<td>
									
									<h1 style="font-weight: 200;font-size: 30px;padding: 0;margin: 0;">Hello Admin!</h1>
									<p class="lead" style="font-size: 14px;margin-bottom: 10px;font-weight: normal;line-height: 1.6;">Data has been transferred to warehouse error</p>
									
								</td>
							</tr>
						</table>
					</div>';
					
					
					$html .= '<div class="content" style="max-width: 600px;margin: 0 auto;display: block;    background: #fa801b;">
					<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
						<tr>
							<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="#">
							<img src="'.HTTP_SERVER.'sites/view/digitalnotebook/stylesheet/email/when.png" style="width:75px;" /></a></td>
							<td>
								<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">When</h4>
								<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
								'.date('j, F Y', strtotime($result['startDate'])).'
								</p>
							</td>
						</tr>
					</table></div>
					

				</td>
				<td></td>
			</tr>
		</table>

		</body>
		</html>';
		return $html;
	}
	
	/*
	public function speechToText(){
		if($this->config->get('config_transcription') == '1'){
			$this->load->model('notes/notes');
			$query = $this->db->query("SELECT * FROM dg_notes_media where audio_attach_type = '1' ");
			$numrow = $query->num_rows;
			
			if($numrow > 0){
			
			//var_dump($query->rows);
			
			 $username = '70b54a4a-187f-46e4-9685-0f80cddc8b0c';
			 $password = 'bWpYdfVSCWnF';
			 $url = 'https://stream.watsonplatform.net/speech-to-text/api/v1/recognize?model=en-US_BroadbandModel&continuous=true';
			 
			// require_once(DIR_SYSTEM . 'library/awsstorage/s3_config.php');
			 
			// echo rand();
			// var_dump($query->rows);
				foreach($query->rows as $row){
					
					$urrl = $row['audio_attach_url']; 
					
					
					
					$filePath = DIR_IMAGE.'audio/';
					$filename = $filePath.$row['audio_upload_file']; 
					 
					$file = fopen($filename, 'r');
					
					
					$size = filesize($filename); 
	  
					 $fildata = fread($file,$size);
					// var_dump($fildata);
					 $headers = array(    "Content-Type: audio/wav",
										  "Transfer-Encoding: chunked");
										  
					 $ch = curl_init();
					 curl_setopt($ch, CURLOPT_URL, $url);
					 curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
					 curl_setopt($ch, CURLOPT_POST, TRUE);
					 curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
					 curl_setopt($ch, CURLOPT_BINARYTRANSFER, TRUE);
					 curl_setopt($ch, CURLOPT_POSTFIELDS, $fildata);
					 curl_setopt($ch, CURLOPT_INFILE, $file);
					 curl_setopt($ch, CURLOPT_INFILESIZE, $size);
					 curl_setopt($ch, CURLOPT_VERBOSE, true);
					 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					 $executed = curl_exec($ch);
					 curl_close($ch);
					
					 $contents = json_decode($executed, true);
	 
						 $ndata = array();
						 foreach($contents as $content){
							foreach($content as $a){
								foreach($a['alternatives'] as $b){
									
									$ndata[] = $b['transcript'];
								}
							}
						 }
						 
						 $ncontent = implode(" ",$ndata);
					
					$notes_data = $this->model_notes_notes->getnotes($row['notes_id']);
					
					$notes_description = $notes_data['notes_description'];
					$facilities_id = $notes_data['facilities_id'];
					$date_added = $notes_data['date_added'];
					
					$notesContent = $notes_description.' | Voice Transcript: '.$ncontent.'| ';
					$formData = array();
					$formData['notes_description'] = $notesContent;
					$formData['facilities_id'] = $facilities_id;
					$formData['date_added'] = $date_added;
					
					
					$slq1 = "UPDATE dg_notes_media SET audio_attach_type = '2' where notes_media_id = '".$row['notes_media_id']."'";
					$this->db->query($slq1);
					
					$this->model_notes_notes->updateNotesContent($row['notes_id'], $formData);

					
					
					unlink($filename);
					echo "Success";
					
				}
			
			}
		
		}
	}*/
	
	
	
	public function speechToText(){
		
		if($this->config->get('config_transcription') == '1'){
			
			$this->load->model('notes/notes');
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "notes_media where audio_attach_type = '1' ");
			$numrow = $query->num_rows;
			
			if($numrow > 0){
			// $stturl = "https://speech.googleapis.com/v1beta1/speech:syncrecognize?key=AIzaSyA9iL7srWZ8-jUKeoVQT64NFj7RDLs583o";
			
			 $stturl = "https://speech.googleapis.com/v1p1beta1/speech:longrunningrecognize?key=AIzaSyA9iL7srWZ8-jUKeoVQT64NFj7RDLs583o";
			
				foreach($query->rows as $row){
					
					$urrl = $row['audio_attach_url']; 
					
					//$upload = file_get_contents($filename);
					$upload = file_get_contents($urrl);
					$upload = base64_encode($upload);
						$data = array(
							"config"    =>  array(
								/*"encoding"  => "FLAC",
								"sampleRateHertz" => 16000,
								"enableSeparateRecognitionPerChannel" => true,
								"languageCode" => "en-US",
								"enableAutomaticPunctuation" => true,
								"enableSpeakerDiarization" => true,
								"enableWordTimeOffsets" => true,
								"diarizationSpeakerCount" =>  2,
								"useEnhanced" => true,
								"alternativeLanguageCodes" => ["fr-FR", "de-DE"],
								*/
								
								"sampleRateHertz" => 16000,
								'encoding'=> 'FLAC',
								'languageCode'=> 'en-US',
								'enableWordTimeOffsets'=> false,
								'enableAutomaticPunctuation'=> true,
								'useEnhanced'=> true,
								"enableSpeakerDiarization" => true,
								"diarizationSpeakerCount" => 2,
								"model" => "phone_call"
							),
							"audio"     =>  array(
								"content"       =>  $upload,
							)
						);

						$jsonData = json_encode($data);
						//$headers = array(    "Content-Type: audio/flac", "Transfer-Encoding: chunked");
						
						$headers = array( "Content-Type: application/json");
						$ch = curl_init();
 
						curl_setopt($ch, CURLOPT_URL, $stturl);
						curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
						curl_setopt($ch, CURLOPT_POST, TRUE);
						curl_setopt($ch, CURLOPT_BINARYTRANSFER, TRUE);
						curl_setopt($ch, CURLOPT_POST, true);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

						$results = curl_exec($ch);

						//var_dump($results);

						$contents = json_decode($results,true);
						
						$speech_name = $contents['name']; 
						
						$slq1 = "UPDATE " . DB_PREFIX . "notes_media SET audio_attach_type = '2',speech_name = '".$this->db->escape($speech_name)."',is_updated = '1' where notes_media_id = '".$row['notes_media_id']."'";
						$this->db->query($slq1);
						
						
						$slq122 = "UPDATE " . DB_PREFIX . "notes SET notes_conut = '0' where notes_id = '".$row['notes_id']."'";
						$this->db->query($slq122);
						
						echo "Success";
						
						/*
						$ndata = array();
						foreach($contents["results"] as $content){
							foreach($content['alternatives'] as $b){
								$ndata[] = $b['transcript'];
							}
							
						}
						
						
						$ncontent = implode(" ",$ndata);
					
						$notes_data = $this->model_notes_notes->getnotes($row['notes_id']);
						
						$notes_description = $notes_data['notes_description'];
						$facilities_id = $notes_data['facilities_id'];
						$date_added = $notes_data['date_added'];
						
						$notesContent = $notes_description.' | Voice Transcript: '.$ncontent.'| ';
						$formData = array();
						$formData['notes_description'] = $notesContent;
						$formData['facilities_id'] = $facilities_id;
						$formData['date_added'] = $date_added;
					
					
						$slq1 = "UPDATE dg_notes_media SET audio_attach_type = '2' where notes_media_id = '".$row['notes_media_id']."'";
						$this->db->query($slq1);
						
						$this->model_notes_notes->updateNotesContent($row['notes_id'], $formData);

						
						
						unlink($filename);
						echo "Success";
						*/
					}
			
			}
		
		}
		
		
		$sqlta = "SELECT * from " . DB_PREFIX . "tags_enroll where upload_file_thumb = '' ";
		$qtt = $this->db->query($sqlta);
		
		if($qtt->num_rows > 0){
			foreach($qtt->rows as $client){
				
				if($client['enroll_image'] != null && $client['enroll_image'] != ""){
					
					$url_to_image = $client['enroll_image'];
					 
					$my_save_dir = DIR_IMAGE.'files/';
					$filename = basename($url_to_image);
					//var_dump($filename);
					$extension = end(explode(".", $filename));
					//var_dump($extension);
					$picture_filename = pathinfo($filename, PATHINFO_FILENAME);
					//var_dump($picture_filename);
					if($this->config->get('thumb_image_size') != null && $this->config->get('thumb_image_size') != ""){
						$width = $this->config->get('thumb_image_size');
					}else{
						$width = 100;
					}
					
					if($this->config->get('thumb_image_size_height') != null && $this->config->get('thumb_image_size_height') != ""){
						$height = $this->config->get('thumb_image_size_height');
					}else{
						$height = 100;
					}
					
					
					
					$path_to_image_directory = 'files/';
					$path_to_thumbs_directory = 'files/';
					
					$new_image_1= "";
					$new_image = $picture_filename.'-'.$width;
					$new_image_1 = $new_image . "." . $extension;
					$outputFolder = DIR_IMAGE .$path_to_thumbs_directory.$new_image_1;
					
					$complete_save_loc = $my_save_dir . $filename;
					//var_dump($complete_save_loc);	
					//if (!file_exists($outputFolder) || !is_file($outputFolder)) {
						
					
					if($client['upload_file_thumb'] == null && $client['upload_file_thumb'] == ""){
					
						if (!file_exists($complete_save_loc) || !is_file($complete_save_loc)) {
							//file_put_contents($outputFolder, file_get_contents($client['upload_file']));

							//copy($client['upload_file'], $complete_save_loc);
							$ch = curl_init($url_to_image);
							$complete_save_loc = $my_save_dir . $filename;
							$fp = fopen($complete_save_loc, 'wb');
							curl_setopt($ch, CURLOPT_FILE, $fp);
							curl_setopt($ch, CURLOPT_HEADER, 0);
							curl_exec($ch);
							curl_close($ch);
							fclose($fp);
							
							//$this->Thumbnail($client['upload_file'], $complete_save_loc);
							//$this->compress($client['upload_file'], $complete_save_loc, 90);
						}
						
						/*if(preg_match('/[.](jpg)$/', $filename)) {
							$im = imagecreatefromjpeg(DIR_IMAGE .$path_to_image_directory.$filename);
						
						} else if (preg_match('/[.](gif)$/', $filename)) {
							$im = imagecreatefromgif(DIR_IMAGE .$path_to_image_directory . $filename);
						} else if (preg_match('/[.](png)$/', $filename)) {
							$im = imagecreatefrompng(DIR_IMAGE .$path_to_image_directory . $filename);
						}
						
						$ox = imagesx($im);
						$oy = imagesy($im);
						 
						$nx = $final_width_of_image;
						$ny = floor($oy * ($final_width_of_image / $ox));
						 
						$nm = imagecreatetruecolor($nx, $ny);
					   
						imagecopyresized($nm, $im, 0,0,0,0,$nx,$ny,$ox,$oy);
						

						echo "<hr>";
						echo DIR_IMAGE.$path_to_thumbs_directory.$new_image_1;
						echo "<hr>";
						imagejpeg($nm, DIR_IMAGE .$path_to_thumbs_directory . $new_image_1);
						
						*/
						
						//copy(DIR_IMAGE .$path_to_thumbs_directory. $filename, DIR_IMAGE .$path_to_thumbs_directory. $new_image_1);
						
						
						/*
						echo "<hr>";
						var_dump(DIR_IMAGE .$path_to_thumbs_directory. $filename);
						
						echo "<hr>";
						var_dump(DIR_IMAGE .$path_to_thumbs_directory. $new_image_1);
						echo "<hr>";
						*/
						
						//header('Content-type: image/jpeg');
						//$myimage = $this->resizeImage(DIR_IMAGE .$path_to_thumbs_directory. $filename, '150', '120');
						//print $myimage;
						
						//$quality = 100;
						//$this->image_handler(DIR_IMAGE .$path_to_thumbs_directory. $filename,DIR_IMAGE .$path_to_thumbs_directory. $new_image_1,'500','500',$quality,$wmsource);
						
						$image = new Image(DIR_IMAGE .$path_to_thumbs_directory. $filename);
						$image->resize($width, $height,  "h");
						$image->save(DIR_IMAGE .$path_to_thumbs_directory. $new_image_1);
						
						
						$file16 = $path_to_thumbs_directory. $filename;
						
						//$this->load->model('setting/image');
						//$newfile84 = $this->model_setting_image->resize($file16, 50, 50);
					
						//var_dump($newfile84);
						//echo "<hr>";
						
						$notes_file = $new_image_1;
						$outputFolder = DIR_IMAGE .$path_to_thumbs_directory.$new_image_1;
						
						//var_dump($notes_file);
						//var_dump($outputFolder);
						$s3file = "";
						if($this->config->get('enable_storage') == '1'){
							
							//require_once(DIR_SYSTEM . 'library/awsstorage/s3_config.php');
							
							$s3file = $this->awsimageconfig->uploadFile($notes_file, $outputFolder);
							
							//var_dump($s3file);
						}
						
						if($this->config->get('enable_storage') == '2'){
							/* AZURE */
							
							require_once(DIR_SYSTEM . 'library/azure_storage/config.php');					
							//uploadBlobSample($blobClient, $outputFolder, $notes_file);
							$s3file = AZURE_URL. $notes_file;
						}
						
						if($this->config->get('enable_storage') == '3'){
							$s3file = HTTP_SERVER . 'image/files/'.$notes_file;
						}
						
						
						 $sqluf122 = "UPDATE `" . DB_PREFIX . "tags_enroll` SET upload_file_thumb = '" . $this->db->escape($s3file) . "' WHERE tags_enroll_id = '".$client['tags_enroll_id']."' ";
						$this->db->query($sqluf122);
						//echo "<hr>";
						if($this->config->get('enable_storage') != '3'){
							unlink($complete_save_loc);
							unlink($outputFolder);
						}
						
					}
					
				}
			}
			
		}
		
		
		$sqltaw = "SELECT * from " . DB_PREFIX . "tags where tags_status_in = 'Wait listed' and is_wait_list_task = '0' ";
		$qttw = $this->db->query($sqltaw);
		
		if($qttw->num_rows > 0){
			
			$this->load->model('facilities/facilities');
			$this->load->model('setting/tags');
			$this->load->model('setting/timezone');
			$this->load->model('createtask/createtask');
			
			foreach($qttw->rows as $clientw){
				
				if($clientw['is_wait_list_task'] == '0'){
					
					
					
					if($clientw['reminder_date'] != '0000-00-00 00:00:00'){
						//var_dump($clientw['reminder_date']);
						
						$reminder_date = date('Y-m-d', strtotime($clientw['reminder_date']));
					
						$addtaskw = array();
						$facilities_info = $this->model_facilities_facilities->getfacilities($clientw['facilities_id']);
							
						$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
							
						date_default_timezone_set($timezone_info['timezone_value']);
							
						$noteDate = date('Y-m-d H:i:s', strtotime('now'));
						$date_added = date('Y-m-d', strtotime('now'));
						
						
						if($reminder_date >= $date_added){
							//var_dump($reminder_date);
							//$taskTime = date('h:i A', strtotime('now'));
							
							$snooze_time71 = 3;
							$thestime61 = $clientw['reminder_time'];//date('H:i:s');
							$taskTime = date("h:i A",strtotime("+".$snooze_time71." minutes",strtotime($thestime61)));
							
							$current_time = date("H:i:s");
							
							$time1 = date('H:i:s');
							
							$addtaskw['taskDate'] = date('m-d-Y', strtotime($clientw['reminder_date']));
							$addtaskw['end_recurrence_date'] = date('m-d-Y', strtotime($clientw['reminder_date']));
							
							$addtaskw['recurrence'] = 'none';
							$addtaskw['recurnce_week'] = '';
							$addtaskw['recurnce_hrly'] = '';
							$addtaskw['recurnce_month'] = '';
							$addtaskw['recurnce_day'] = '';
							$addtaskw['taskTime'] = $taskTime; //date('H:i:s');
							$addtaskw['endtime'] = $taskTime;
							$addtaskw['description'] = $clientw['emp_first_name'].' '.$clientw['emp_last_name'] . ' Scheduled call back for wait listed screening';
							$addtaskw['assignto'] = '';
							$addtaskw['tasktype'] = '1';
							$addtaskw['numChecklist'] = '';
							$addtaskw['task_alert'] = '1';
							$addtaskw['alert_type_sms'] = '';
							$addtaskw['alert_type_notification'] = '1';
							$addtaskw['alert_type_email'] = '';
							$addtaskw['rules_task'] = '';
											
							$addtaskw['locations_id'] = '';
							$addtaskw['facilities_id'] = $clientw['facilities_id'];
							$addtaskw['emp_tag_id'] = $clientw['tags_id'];
							
							//var_dump($addtaskw);
							//echo "<hr>";
							$sqlu = "UPDATE `" . DB_PREFIX . "tags` SET is_wait_list_task = '1' where tags_id = '".$clientw['tags_id']."' ";
							$this->db->query($sqlu);
							
							$task_id = $this->model_createtask_createtask->addcreatetask($addtaskw, $clientw['facilities_id']);
						}
					}
				}
				
			}
		}
		
		
	}
	
	
	function image_handler($source_image,$destination,$tn_w = 100,$tn_h = 100,$quality = 80,$wmsource = false) {
  // The getimagesize functions provides an "imagetype" string contstant, which can be passed to the image_type_to_mime_type function for the corresponding mime type
  $info = getimagesize($source_image);
  $imgtype = image_type_to_mime_type($info[2]);
  // Then the mime type can be used to call the correct function to generate an image resource from the provided image
  switch ($imgtype) {
  case 'image/jpeg':
    $source = imagecreatefromjpeg($source_image);
    break;
  case 'image/gif':
    $source = imagecreatefromgif($source_image);
    break;
  case 'image/png':
    $source = imagecreatefrompng($source_image);
    break;
  default:
    die('Invalid image type.');
  }
  // Now, we can determine the dimensions of the provided image, and calculate the width/height ratio
  $src_w = imagesx($source);
  $src_h = imagesy($source);
  $src_ratio = $src_w/$src_h;
  // Now we can use the power of math to determine whether the image needs to be cropped to fit the new dimensions, and if so then whether it should be cropped vertically or horizontally. We're just going to crop from the center to keep this simple.
  if ($tn_w/$tn_h > $src_ratio) {
  $new_h = $tn_w/$src_ratio;
  $new_w = $tn_w;
  } else {
  $new_w = $tn_h*$src_ratio;
  $new_h = $tn_h;
  }
  $x_mid = $new_w/2;
  $y_mid = $new_h/2;
  // Now actually apply the crop and resize!
  $newpic = imagecreatetruecolor(round($new_w), round($new_h));
  imagecopyresampled($newpic, $source, 0, 0, 0, 0, $new_w, $new_h, $src_w, $src_h);
  $final = imagecreatetruecolor($tn_w, $tn_h);
  imagecopyresampled($final, $newpic, 0, 0, ($x_mid-($tn_w/2)), ($y_mid-($tn_h/2)), $tn_w, $tn_h, $tn_w, $tn_h);
  // If a watermark source file is specified, get the information about the watermark as well. This is the same thing we did above for the source image.
  if($wmsource) {
  $info = getimagesize($wmsource);
  $imgtype = image_type_to_mime_type($info[2]);
  switch ($imgtype) {
    case 'image/jpeg':
      $watermark = imagecreatefromjpeg($wmsource);
      break;
    case 'image/gif':
      $watermark = imagecreatefromgif($wmsource);
      break;
    case 'image/png':
      $watermark = imagecreatefrompng($wmsource);
      break;
    default:
      die('Invalid watermark type.');
  }
  // Determine the size of the watermark, because we're going to specify the placement from the top left corner of the watermark image, so the width and height of the watermark matter.
  $wm_w = imagesx($watermark);
  $wm_h = imagesy($watermark);
  // Now, figure out the values to place the watermark in the bottom right hand corner. You could set one or both of the variables to "0" to watermark the opposite corners, or do your own math to put it somewhere else.
  $wm_x = $tn_w - $wm_w;
  $wm_y = $tn_h - $wm_h;
  // Copy the watermark onto the original image
  // The last 4 arguments just mean to copy the entire watermark
  imagecopy($final, $watermark, $wm_x, $wm_y, 0, 0, $tn_w, $tn_h);
  }
  // Ok, save the output as a jpeg, to the specified destination path at the desired quality.
  // You could use imagepng or imagegif here if you wanted to output those file types instead.
  if(Imagejpeg($final,$destination,$quality)) {
  return true;
  }
  // If something went wrong
  return false;
}
	
	function resizeImage($filename, $newwidth, $newheight){
		list($width, $height) = getimagesize($filename);
		if($width > $height && $newheight < $height){
			$newheight = $height / ($width / $newwidth);
		} else if ($width < $height && $newwidth < $width) {
			$newwidth = $width / ($height / $newheight);    
		} else {
			$newwidth = $width;
			$newheight = $height;
		}
		$thumb = imagecreatetruecolor($newwidth, $newheight);
		$source = imagecreatefromjpeg($filename);
		imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
		return imagejpeg($thumb);
	}
	
	
	function Thumbnail($url, $filename, $width = 350, $height = true) {

	 // download and create gd image
	 $image = ImageCreateFromString(file_get_contents($url));

	 // calculate resized ratio
	 // Note: if $height is set to TRUE then we automatically calculate the height based on the ratio
	 $height = $height === true ? (ImageSY($image) * $width / ImageSX($image)) : $height;

	 // create image 
	 $output = ImageCreateTrueColor($width, $height);
	 ImageCopyResampled($output, $image, 0, 0, 0, 0, $width, $height, ImageSX($image), ImageSY($image));

	 // save image
	 ImageJPEG($output, $filename, 95); 

	 // return resized image
	 return $output; // if you need to use it
	}

	function compress($source, $destination, $quality) {

		$info = getimagesize($source);

		if ($info['mime'] == 'image/jpeg') 
			$image = imagecreatefromjpeg($source);

		elseif ($info['mime'] == 'image/gif') 
			$image = imagecreatefromgif($source);

		elseif ($info['mime'] == 'image/png') 
			$image = imagecreatefrompng($source);

		imagejpeg($image, $destination, $quality);

		return $destination;
	}
	
	
	
	public function casedashboard(){
	
		$this->load->model('notes/notes');
		
		$this->load->model('setting/tags');
		$this->load->model('setting/timezone');
		$this->load->model('notes/case');
		$this->load->model('facilities/facilities');
			
		
		
		
		$data = array(
			'status'        => 1,
			'discharge' => 1,
			'all_record' => 1,
			'sort' => 'emp_tag_id',
			'order' => 'ASC',
			
		);
		
		$tags = $this->model_setting_tags->getTags($data);
		
	
		foreach($tags as $tag){
			$facility = $this->model_facilities_facilities->getfacilities($tag['facilities_id']);
			$timezone_info = $this->model_setting_timezone->gettimezone($facility['timezone_id']);
			
			date_default_timezone_set($timezone_info['timezone_value']);
			
			$startDate = date('Y-m-d', strtotime('-1 day', strtotime('now')));
			$endDate = date('Y-m-d', strtotime('-1 day', strtotime('now')));
			
			$start_date = date('Y-m-d', strtotime('-1 day', strtotime('now')));
			$current_date = date('Y-m-d H:i:s', strtotime('-1 day', strtotime('now')));
			
			
			//var_dump($tag['tags_id']);
			$data2 = array(
				'note_date_from' => $startDate,
				'note_date_to' => $endDate,
				'emp_tag_id' => $tag['tags_id'],
				'facilities_id' => $tag['facilities_id'],
			);
			$ttotalnotes = $this->model_notes_case->getTotalnotessmain($data2); 
		
		
		
			$data12 = array(
				'note_date_from' => $startDate,
				'note_date_to' => $endDate,
				'emp_tag_id' => $tag['tags_id'],
				'form_search' => 'all',
				'facilities_id' => $tag['facilities_id'],
			);
			$ttotalforms = $this->model_notes_case->getTotalnotessmain($data12); 
			
		
			$data1dd2 = array(
				'note_date_from' => $startDate,
				'note_date_to' => $endDate,
				'emp_tag_id' => $tag['tags_id'],
				'task_search' => 'all',
				'facilities_id' => $tag['facilities_id'],
			);
			
			$ttotaltasks = $this->model_notes_case->getTotalnotessmain($data1dd2); 
		
		
			$data3 = array(
				'note_date_from' => $startDate,
				'note_date_to' => $endDate,
				//'discharge' => '1',
				'tags_id' => $tag['tags_id'],
				'facilities_id' => $tag['facilities_id'],
			);
			$intakecount = $this->model_setting_tags->getTotalTags($data3);
		
	
		
			$data4 = array(
				'note_date_from' => $startDate,
				'note_date_to' => $endDate,
				'discharge' => '2',
				'tags_id' => $tag['tags_id'],
				'facilities_id' => $tag['facilities_id'],
			);
		
			$dischargecount = $this->model_setting_tags->getTotalTags($data4); 
	
		
			$data5 = array(
				'note_date_from' => $startDate,
				'note_date_to' => $endDate,
				'activenote' => '44',
				'keyword' => 'incident',
				'search_acitvenote_with_keyword' => '1',
				'emp_tag_id' => $tag['tags_id'],
				'facilities_id' => $tag['facilities_id'],
			);
			
			$incidentcount = $this->model_notes_case->getTotalnotessmain($data5); 
		
			$data11 = array(
					'note_date_from' => $startDate,
					'note_date_to' => $endDate,
					'activenote' => '38',
					'keyword' => 'medication',
					'search_acitvenote_with_keyword' => '1',
					'emp_tag_id' => $tag['tags_id'],
					'facilities_id' => $tag['facilities_id'],
			);
		
			$pillcallcount = $this->model_notes_case->getTotalnotessmain($data11);
		
			$data6 = array(
				'note_date_from' => $startDate,
				'note_date_to' => $endDate,
				'tasktype'  => '25',
				'emp_tag_id' => $tag['tags_id'],
				'facilities_id' => $tag['facilities_id'],
			);
			
			$sightandsoundcount = $this->model_notes_case->getTotalnotessmain($data6); 
			
			$data7 = array(
				'note_date_from' => $startDate,
				'note_date_to' => $endDate,
				'highlighter'  => 'all',
				'emp_tag_id' => $tag['tags_id'],
				'facilities_id' => $tag['facilities_id'],
			);
			
			$highlightercount = $this->model_notes_case->getTotalnotessmain($data7); 
		
			$data8 = array(
				'note_date_from' => $startDate,
				'note_date_to' => $endDate,
				'text_color'  => '1',
				'emp_tag_id' => $tag['tags_id'],
				'facilities_id' => $tag['facilities_id'],
			);
			
			$colorcount = $this->model_notes_case->getTotalnotessmain($data8); 
		
			$data9 = array(
				'note_date_from' => $startDate,
				'note_date_to' => $endDate,
				'review_notes'  => '1',  
				'emp_tag_id' => $tag['tags_id'],
				'facilities_id' => $tag['facilities_id'],
			);
			
			$reviewcount = $this->model_notes_case->getTotalnotessmain($data9); 
		
		
			$data10 = array(
				'note_date_from' => $startDate,
				'note_date_to' => $endDate,
				'activenote'  => 'all',
				'emp_tag_id' => $tag['tags_id'],
				'facilities_id' => $tag['facilities_id'],
			);
		
			$activenotecount = $this->model_notes_case->getTotalnotessmain($data10); 
		
		
			$data11 = array(
				'note_date_from' => $startDate,
				'note_date_to' => $endDate,
				'tasktype'  => '11',
				'emp_tag_id' => $tag['tags_id'],
				'facilities_id' =>$tag['facilities_id'],
			);
			
			$becdcheckcount = $this->model_notes_case->getTotalnotessmain($data11); 
			
			
			$casedata = array(
				'ttotaltasks' => $ttotaltasks,
				'ttotalnotes' => $ttotalnotes,
				'ttotalforms' => $ttotalforms,
				'intakecount' => $intakecount,
				'sightandsoundcount' => $sightandsoundcount,
				'incidentcount' => $incidentcount,
				'highlightercount' => $highlightercount,
				'colorcount' => $colorcount,
				'activenotecount' => $activenotecount,
				'medicationcount' => $pillcallcount,
				'bedcheckcount' => $becdcheckcount,
				'facilities_id' => $tag['facilities_id'],
				'intake_date' => $tag['date_added'],
				'discharge_date' => $tag['discharge_date'],
				'roll_call' => $tag['role_call'],
				'tags_id' => $tag['tags_id'],
				'discharge' => $tag['discharge'],
				'date_added' => $current_date,
				'date_updated' => $current_date,
				'start_date' => $start_date,
				'reviewcount' => $reviewcount,
			
			);	

			//var_dump($casedata);
		
			$this->model_notes_case->insertTotal($casedata);
			
			//$sql = "UPDATE `" . DB_PREFIX . "notes`  SET  is_casecount = '1' where tags_id = '".$tag['tags_id']."' ";
			//$query = $this->db->query($sql);
		
		}
			
		echo "Success";	 
	}
	
	public function getMondaysInRange($dateFromString, $dateToString){
		$dateFrom = new \DateTime($dateFromString);
		$dateTo = new \DateTime($dateToString);
		$dates = [];

		if ($dateFrom > $dateTo) {
			return $dates;
		}

		if (1 != $dateFrom->format('N')) {
			$dateFrom->modify('next monday');
		}

		while ($dateFrom <= $dateTo) {
			$dates[] = $dateFrom->format('Y-m-d');
			$dateFrom->modify('+1 week');
		}

		return $dates;
	}

	public function futuretaskupdate(){
		
		
		$this->load->model('notes/notes');
		$this->load->model('notes/rules');
		$this->load->model('facilities/facilities');
		$this->load->model('createtask/createtask');
		
		$this->load->model('setting/highlighter');
		$this->load->model('setting/country');
		$this->load->model('setting/zone');
		$this->load->model('setting/timezone');
		
		$this->load->model('notes/tags');
		
		$results = $this->model_facilities_facilities->getfacilitiess($data);
    	
		
		if(!empty($results)){
			foreach($results as $tresult) {
				
				$timezone_info = $this->model_setting_timezone->gettimezone($tresult['timezone_id']);
				date_default_timezone_set($timezone_info['timezone_value']);
				$searchdate = date('Y-m-d');
				/*and DATE_FORMAT(date_added, '%Y-%M-%D') != DATE_FORMAT(end_recurrence_date, '%Y-%M-%D')*/
				$sqlt = "SELECT * from " . DB_PREFIX . "createtask where facilityId = '".$tresult['facilities_id']."' and (`end_recurrence_date` >  '".$searchdate." 23:59:59') and is_create_task = '0' ";
				$qt = $this->db->query($sqlt);
				
				//var_dump($qt->num_rows);
				//echo "<hr>";
				if($qt->num_rows > 0){
					foreach($qt->rows as $tasks){
						
						$start_date = $tasks['date_added'];
						$start_date_time = date ("H:i:s", strtotime($tasks['date_added']));
						$end_date = $tasks['end_recurrence_date'];
						
						$s_date = date ("Y-m-d", strtotime($start_date));
						$e_date = date ("Y-m-d", strtotime($end_date));
						
						
						if($tasks['recurrence'] =="hourly"){
							if($tasks['recurnce_hrly_recurnce'] == "Daily" ){
								if(!empty($tasks['weekly_interval'])){
									$intervalday = explode(',',$tasks['weekly_interval']);
								}
							}
						}
						
						$ss_date = date ("Y-m-d", strtotime("+1 day", strtotime($s_date)));
						$iv = 0;
						sort($intervalday);
						
						while(strtotime($ss_date) <= strtotime($e_date)){
						
							if(!empty($intervalday)){
								foreach($intervalday as $day111){
									
									$day_of_week = date('w', strtotime($day111));
									//var_dump($day_of_week);
									
									
									$day = date('w', strtotime($ss_date));
									//var_dump($this->getMondaysInRange($s_date, $e_date));
									
									//$ss_date = date("Y-m-d", strtotime('next '.$day111));
									
									var_dump($ss_date);
									
									$ss_date = date("Y-m-d", strtotime($day111));
									
									/*if($cur_mon == "" && $cur_mon == null){
										$cur_mon1 = $day111;
									}else{
										$cur_mon1 = $cur_mon;
									}
									
									var_dump($cur_mon1);
									
									$ss_date = date('Y-m-d', $cur_mon1);
									*/
									//$ss_date = $this->getMondaysInRange($s_date, $e_date);
									
									$s_date1 = $ss_date .' '.$start_date_time;
									
									$cur_mon = date('Y-m-d', strtotime("next ".$day111."",$cur_mon1));
									
									$fdate = $ss_date;
									$data = array(
										'date_added' => date ("Y-m-d H:i:s", strtotime($s_date1)),
										'end_recurrence_date' => date ("Y-m-d H:i:s", strtotime($s_date1)),
										'facilityId' => $tasks['facilityId'],
										'task_date' => date ("Y-m-d H:i:s", strtotime($s_date1)),
										'task_time' => $tasks['task_time'],
										'tasktype' => $tasks['tasktype'],
										'description' => $tasks['description'],
										'assign_to' => $tasks['assign_to'],
										'recurrence' => $tasks['recurrence'],
										'recurnce_hrly' => $tasks['recurnce_hrly'],
										'recurnce_week' => $tasks['recurnce_week'],
										'recurnce_month' => $tasks['recurnce_month'],
										'recurnce_day' => $tasks['recurnce_day'],
										'taskadded' => $tasks['taskadded'],
										'endtime' => $tasks['endtime'],
										'task_alert' => $tasks['task_alert'],
										'alert_type_none' => $tasks['alert_type_none'],
										'alert_type_sms' => $tasks['alert_type_sms'],
										'alert_type_notification' => $tasks['alert_type_notification'],
										'alert_type_email' => $tasks['alert_type_email'],
										'checklist' => $tasks['checklist'],
										'snooze_time' => $tasks['snooze_time'],
										'snooze_dismiss' => $tasks['snooze_dismiss'],
										'rules_task' => $tasks['rules_task'],
										'task_form_id' => $tasks['task_form_id'],
										'tags_id' => $tasks['tags_id'],
										'pickup_locations_address' => $tasks['pickup_locations_address'],
										'pickup_locations_time' => $tasks['pickup_locations_time'],
										'pickup_locations_latitude' => $tasks['pickup_locations_latitude'],
										'pickup_locations_longitude' => $tasks['pickup_locations_longitude'],
										'dropoff_locations_address' => $tasks['dropoff_locations_address'],
										'dropoff_locations_time' => $tasks['dropoff_locations_time'],
										'dropoff_locations_latitude' => $tasks['dropoff_locations_latitude'],
										'dropoff_locations_longitude' => $tasks['dropoff_locations_longitude'],
										'transport_tags' => $tasks['transport_tags'],
										'locations_id' => $tasks['locations_id'],
										'task_complettion' => $tasks['task_complettion'],
										'customs_forms_id' => $tasks['customs_forms_id'],
										'emp_tag_id' => $tasks['emp_tag_id'],
										'medication_tags' => $tasks['medication_tags'],
										'completion_alert' => $tasks['completion_alert'],
										'completion_alert_type_sms' => $tasks['completion_alert_type_sms'],
										'completion_alert_type_email' => $tasks['completion_alert_type_email'],
										'user_roles' => $tasks['user_roles'],
										'userids' => $tasks['userids'],
										'recurnce_hrly_perpetual' => $tasks['recurnce_hrly_perpetual'],
										'due_date_time' => $tasks['due_date_time'],
										'task_status' => $tasks['task_status'],
										'task_completed' => $tasks['task_completed'],
										'recurnce_hrly_recurnce' => $tasks['recurnce_hrly_recurnce'],
										'completed_times' => $tasks['completed_times'],
										'completed_alert' => $tasks['completed_alert'],
										'completed_late_alert' => $tasks['completed_late_alert'],
										'incomplete_alert' => $tasks['incomplete_alert'],
										'deleted_alert' => $tasks['deleted_alert'],
										'end_perpetual_task' => $tasks['end_perpetual_task'],
										'is_transport' => $tasks['is_transport'],
										'parent_id' => $tasks['parent_id'],
										'is_send_reminder' => $tasks['is_send_reminder'],
										'attachement_form' => $tasks['attachement_form'],
										'tasktype_form_id' => $tasks['tasktype_form_id'],
										'tagstatus_id' => $tasks['tagstatus_id'],
										'task_group_by' => $tasks['task_group_by'],
										'end_task' => $tasks['end_task'],
										'formrules_id' => $tasks['formrules_id'],
										'task_random_id' => $tasks['task_random_id'],
										'form_due_date' => $tasks['form_due_date'],
										'form_due_date_after' => $tasks['form_due_date_after'],
										'recurnce_m' => $tasks['recurnce_m'],
										'enable_requires_approval' => $tasks['enable_requires_approval'],
										'approval_taskid' => $tasks['approval_taskid'],
										'iswaypoint' => $tasks['iswaypoint'],
										'original_task_time' => $tasks['original_task_time'],
										'device_id' => $tasks['device_id'],
										'is_approval_required_forms_id' => $tasks['is_approval_required_forms_id'],
										'bed_check_location_ids' => $tasks['bed_check_location_ids'],
										'complete_status' => $tasks['complete_status'],
										'id' => $tasks['id'],
										
									);
										
									var_dump($data);
									echo "<hr>";
									
									/*$sqltc = "SELECT * from " . DB_PREFIX . "createtask where facilityId = '".$tresult['facilities_id']."' and (`date_added` BETWEEN  '".$ss_date." 00:00:00' AND  '".$ss_date." 23:59:59') and is_create_task = '".$tasks['id']."' ";
									$qtc = $this->db->query($sqltc);
									
									if($qtc->num_rows == 0){
										$alltasks = $this->model_createtask_createtask->addcreatetask2($data, $tresult['facilities_id']);
									}*/
									
								}
							}else{
								$ss_date = date ("Y-m-d", strtotime("+1 day", strtotime($ss_date)));
								
								$s_date1 = $ss_date .' '.$start_date_time;
								$data = array(
									'date_added' => date ("Y-m-d H:i:s", strtotime($s_date1)),
									'end_recurrence_date' => date ("Y-m-d H:i:s", strtotime($s_date1)),
									'facilityId' => $tasks['facilityId'],
									'task_date' => date ("Y-m-d H:i:s", strtotime($s_date1)),
									'task_time' => $tasks['task_time'],
									'tasktype' => $tasks['tasktype'],
									'description' => $tasks['description'],
									'assign_to' => $tasks['assign_to'],
									'recurrence' => $tasks['recurrence'],
									'recurnce_hrly' => $tasks['recurnce_hrly'],
									'recurnce_week' => $tasks['recurnce_week'],
									'recurnce_month' => $tasks['recurnce_month'],
									'recurnce_day' => $tasks['recurnce_day'],
									'taskadded' => $tasks['taskadded'],
									'endtime' => $tasks['endtime'],
									'task_alert' => $tasks['task_alert'],
									'alert_type_none' => $tasks['alert_type_none'],
									'alert_type_sms' => $tasks['alert_type_sms'],
									'alert_type_notification' => $tasks['alert_type_notification'],
									'alert_type_email' => $tasks['alert_type_email'],
									'checklist' => $tasks['checklist'],
									'snooze_time' => $tasks['snooze_time'],
									'snooze_dismiss' => $tasks['snooze_dismiss'],
									'rules_task' => $tasks['rules_task'],
									'task_form_id' => $tasks['task_form_id'],
									'tags_id' => $tasks['tags_id'],
									'pickup_locations_address' => $tasks['pickup_locations_address'],
									'pickup_locations_time' => $tasks['pickup_locations_time'],
									'pickup_locations_latitude' => $tasks['pickup_locations_latitude'],
									'pickup_locations_longitude' => $tasks['pickup_locations_longitude'],
									'dropoff_locations_address' => $tasks['dropoff_locations_address'],
									'dropoff_locations_time' => $tasks['dropoff_locations_time'],
									'dropoff_locations_latitude' => $tasks['dropoff_locations_latitude'],
									'dropoff_locations_longitude' => $tasks['dropoff_locations_longitude'],
									'transport_tags' => $tasks['transport_tags'],
									'locations_id' => $tasks['locations_id'],
									'task_complettion' => $tasks['task_complettion'],
									'customs_forms_id' => $tasks['customs_forms_id'],
									'emp_tag_id' => $tasks['emp_tag_id'],
									'medication_tags' => $tasks['medication_tags'],
									'completion_alert' => $tasks['completion_alert'],
									'completion_alert_type_sms' => $tasks['completion_alert_type_sms'],
									'completion_alert_type_email' => $tasks['completion_alert_type_email'],
									'user_roles' => $tasks['user_roles'],
									'userids' => $tasks['userids'],
									'recurnce_hrly_perpetual' => $tasks['recurnce_hrly_perpetual'],
									'due_date_time' => $tasks['due_date_time'],
									'task_status' => $tasks['task_status'],
									'task_completed' => $tasks['task_completed'],
									'recurnce_hrly_recurnce' => $tasks['recurnce_hrly_recurnce'],
									'completed_times' => $tasks['completed_times'],
									'completed_alert' => $tasks['completed_alert'],
									'completed_late_alert' => $tasks['completed_late_alert'],
									'incomplete_alert' => $tasks['incomplete_alert'],
									'deleted_alert' => $tasks['deleted_alert'],
									'end_perpetual_task' => $tasks['end_perpetual_task'],
									'is_transport' => $tasks['is_transport'],
									'parent_id' => $tasks['parent_id'],
									'is_send_reminder' => $tasks['is_send_reminder'],
									'attachement_form' => $tasks['attachement_form'],
									'tasktype_form_id' => $tasks['tasktype_form_id'],
									'tagstatus_id' => $tasks['tagstatus_id'],
									'task_group_by' => $tasks['task_group_by'],
									'end_task' => $tasks['end_task'],
									'formrules_id' => $tasks['formrules_id'],
									'task_random_id' => $tasks['task_random_id'],
									'form_due_date' => $tasks['form_due_date'],
									'form_due_date_after' => $tasks['form_due_date_after'],
									'recurnce_m' => $tasks['recurnce_m'],
									'enable_requires_approval' => $tasks['enable_requires_approval'],
									'approval_taskid' => $tasks['approval_taskid'],
									'iswaypoint' => $tasks['iswaypoint'],
									'original_task_time' => $tasks['original_task_time'],
									'device_id' => $tasks['device_id'],
									'is_approval_required_forms_id' => $tasks['is_approval_required_forms_id'],
									'bed_check_location_ids' => $tasks['bed_check_location_ids'],
									'complete_status' => $tasks['complete_status'],
									'id' => $tasks['id'],
									
								);
									
								//var_dump($data);
								//echo "<hr>";
								
								$sqltc = "SELECT * from " . DB_PREFIX . "createtask where facilityId = '".$tresult['facilities_id']."' and (`date_added` BETWEEN  '".$ss_date." 00:00:00' AND  '".$ss_date." 23:59:59') and is_create_task = '".$tasks['id']."' ";
								$qtc = $this->db->query($sqltc);
								
								if($qtc->num_rows == 0){
									$alltasks = $this->model_createtask_createtask->addcreatetask2($data, $tresult['facilities_id']);
								}
							}
							
							
						
							
							$iv++;	
						}
						
						
						$slq1u = "UPDATE " . DB_PREFIX . "createtask SET end_recurrence_date = '".$tasks['task_date']."' where id = '".$tasks['id']."'";
						//$this->db->query($slq1u);
					}
				}
				
			}
		}
		
	}
	
	
	
	public function dashbordactivity(){
		
		$this->load->model('facilities/facilities');
		$this->load->model('setting/highlighter');
		$this->load->model('setting/timezone');
		$this->load->model('notes/tags');
		$this->load->model('syndb/syndb');
		
		$results = $this->model_facilities_facilities->getfacilitiess($data);
    	
		
		if(!empty($results)){
			foreach($results as $tresult) {
				
				$tnotes_total = array();
				$timezone_info = $this->model_setting_timezone->gettimezone($tresult['timezone_id']);
				date_default_timezone_set($timezone_info['timezone_value']);
				$searchdate = date('Y-m-d');
				
				$startDate = date('Y-m-d', strtotime("-1 day"));
				$endDate = date('Y-m-d');
				
				$date_added = date('Y-m-d H:i:s', strtotime("-1 day"));
				
				
				$sqltnt = "SELECT DISTINCT COUNT(DISTINCT notes_id) AS total FROM " . DB_PREFIX . "notes where facilities_id = '".$tresult['facilities_id']."' and `date_added` BETWEEN  '".$startDate." 00:00:00 ' AND  '".$startDate." 23:59:59' and sync_dashboard = '0' and status = '1' ";				
				$query = $this->db->query($sqltnt);		
				$total_notes = $query->row['total'];

				$sqltnta = "SELECT DISTINCT COUNT(DISTINCT notes_id) AS total FROM " . DB_PREFIX . "notes_by_keyword where facilities_id = '".$tresult['facilities_id']."' and `date_added` BETWEEN  '".$startDate." 00:00:00 ' AND  '".$startDate." 23:59:59' and sync_dashboard = '0' and notes_id > '0' ";				
				$querya = $this->db->query($sqltnta);		
				$total_activenote = $querya->row['total'];
				
				$sqltnth = "SELECT DISTINCT COUNT(DISTINCT notes_id) AS total FROM " . DB_PREFIX . "notes where facilities_id = '".$tresult['facilities_id']."' and `date_added` BETWEEN  '".$startDate." 00:00:00 ' AND  '".$startDate." 23:59:59' and sync_dashboard = '0' and status = '1' and highlighter_id > '0' ";				
				$queryh = $this->db->query($sqltnth);		
				$total_highlighter = $queryh->row['total'];
				
				$sqltntu = "SELECT DISTINCT COUNT(DISTINCT user_id) AS total FROM " . DB_PREFIX . "notes where facilities_id = '".$tresult['facilities_id']."' and `date_added` BETWEEN  '".$startDate." 00:00:00 ' AND  '".$startDate." 23:59:59' and sync_dashboard = '0' and status = '1' and user_id != '' ";				
				$queryu = $this->db->query($sqltntu);		
				$total_active_user = $queryu->row['total'];
				
				$sqltntt = "SELECT DISTINCT COUNT(DISTINCT notes_id) AS total FROM " . DB_PREFIX . "notes where facilities_id = '".$tresult['facilities_id']."' and `date_added` BETWEEN  '".$startDate." 00:00:00 ' AND  '".$startDate." 23:59:59' and sync_dashboard = '0' and is_tag > 0 and form_type = '2' ";				
				$queryt = $this->db->query($sqltntt);		
				$total_intake_tags = $queryt->row['total'];
				
				$sqltntf = "SELECT DISTINCT COUNT(DISTINCT notes_id) AS total FROM " . DB_PREFIX . "forms where facilities_id = '".$tresult['facilities_id']."' and `date_added` BETWEEN  '".$startDate." 00:00:00 ' AND  '".$startDate." 23:59:59' and sync_dashboard = '0' and notes_id > '0' ";				
				$queryf = $this->db->query($sqltntf);		
				$total_forms = $queryf->row['total'];
				
				$sqltntfi = "SELECT DISTINCT COUNT(DISTINCT notes_id) AS total FROM " . DB_PREFIX . "forms where facilities_id = '".$tresult['facilities_id']."' and `date_added` BETWEEN  '".$startDate." 00:00:00 ' AND  '".$startDate." 23:59:59' and sync_dashboard = '0' and notes_id > '0' and custom_form_type = '".CUSTOME_INTAKEID."' ";				
				$queryfi = $this->db->query($sqltntfi);		
				$total_screening = $queryfi->row['total'];
				
				$sqltntai = "SELECT DISTINCT COUNT(DISTINCT notes_id) AS total FROM " . DB_PREFIX . "notes_by_keyword where facilities_id = '".$tresult['facilities_id']."' and `date_added` BETWEEN  '".$startDate." 00:00:00 ' AND  '".$startDate." 23:59:59' and sync_dashboard = '0' and keyword_id = '44' and notes_id > '0' ";				
				$queryai = $this->db->query($sqltntai);		
				$total_incident = $queryai->row['total'];
				
				$sqltntait = "SELECT DISTINCT COUNT(DISTINCT notes_id) AS total FROM " . DB_PREFIX . "notes_by_keyword where facilities_id = '".$tresult['facilities_id']."' and `date_added` BETWEEN  '".$startDate." 00:00:00 ' AND  '".$startDate." 23:59:59' and sync_dashboard = '0' and is_monitor_time = '1' and notes_id > '0' ";				
				$queryait = $this->db->query($sqltntait);		
				$total_timed_activenote = $queryait->row['total'];
				
				
				$sqltntc = "SELECT DISTINCT COUNT(DISTINCT notes_id) AS total FROM " . DB_PREFIX . "notes where facilities_id = '".$tresult['facilities_id']."' and `date_added` BETWEEN  '".$startDate." 00:00:00 ' AND  '".$startDate." 23:59:59' and sync_dashboard = '0' and status = '1' and text_color != '' ";				
				$queryc = $this->db->query($sqltntc);		
				$total_colour = $queryc->row['total'];
				
				$sqltntm = "SELECT DISTINCT COUNT(DISTINCT notes_id) AS total FROM " . DB_PREFIX . "notes_media where facilities_id = '".$tresult['facilities_id']."' and `media_date_added` BETWEEN  '".$startDate." 00:00:00 ' AND  '".$startDate." 23:59:59' and sync_dashboard = '0' and notes_id > '0' ";				
				$querytm = $this->db->query($sqltntm);		
				$total_media = $querytm->row['total'];
				
				$sqltntc = "SELECT DISTINCT COUNT(DISTINCT notes_id) AS total FROM " . DB_PREFIX . "notes where facilities_id = '".$tresult['facilities_id']."' and `date_added` BETWEEN  '".$startDate." 00:00:00 ' AND  '".$startDate." 23:59:59' and sync_dashboard = '0' and status = '1' and task_id > '0' ";				
				$queryc = $this->db->query($sqltntc);		
				$total_task = $queryc->row['total'];
				
				
				$tnotes_total = array(
					'total_notes' => $total_notes,
					'total_activenote' => $total_activenote,
					'total_highlighter' => $total_highlighter,
					'total_active_user' => $total_active_user,
					'total_intake_tags' => $total_intake_tags,
					'total_forms' => $total_forms,
					'total_screening' => $total_screening,
					'total_incident' => $total_incident,
					'total_timed_activenote' => $total_timed_activenote,
					'total_colour' => $total_colour,
					'total_media' => $total_media,
					'total_task' => $total_task,
					'facilities_id' => $tresult['facilities_id'],
					'date_added' => $date_added,
					'date_updated' => $date_added,
					'status' => 1,
				);	
				
				//var_dump($tnotes_total);
				//echo "<hr>";
				
				$sqla = "Select dashboard_activity_id from `" . DB_PREFIX . "dashboard_activity` where facilities_id = '" . $tresult['facilities_id'] . "' and `date_added` BETWEEN  '".$startDate." 00:00:00 ' AND  '".$startDate." 23:59:59' " ;
				$querya = $this->db->query($sqla);
				
				$activity_info = $querya->row;
				
				if($activity_info['dashboard_activity_id'] != null && $activity_info['dashboard_activity_id'] != ""){
					$this->model_syndb_syndb->updateTotal($tnotes_total, $activity_info['dashboard_activity_id']);
					
					$dashboard_activity_id = $activity_info['dashboard_activity_id'];
				}else{
					$dashboard_activity_id = $this->model_syndb_syndb->insertTotal($tnotes_total);
				}
				
				$sqltn = "SELECT notes_id,notes_description,date_added from " . DB_PREFIX . "notes where facilities_id = '".$tresult['facilities_id']."' and `date_added` BETWEEN  '".$startDate." 00:00:00 ' AND  '".$startDate." 23:59:59' and sync_dashboard = '0' ";
				$qtno = $this->db->query($sqltn);
				
				//var_dump($qtno->num_rows);
				//echo "<hr>";
				if($qtno->num_rows > 0){
					foreach($qtno->rows as $note){
						//var_dump($note);
						//echo "<hr>";
						
						$sqlac = "Select dashboard_activity_keywords_id from `" . DB_PREFIX . "dashboard_activity_keywords` where facilities_id = '" . $tresult['facilities_id'] . "' and `date_added` BETWEEN  '".$startDate." 00:00:00 ' AND  '".$startDate." 23:59:59' and notes_id = '" . $note['notes_id'] . "' " ;
						$queryac = $this->db->query($sqlac);
						
						$dactivity_info = $queryac->row;
						
						$sqltnnt = "SELECT task_content from " . DB_PREFIX . "notes_by_task where facilities_id = '".$tresult['facilities_id']."' and notes_id = '" . $note['notes_id'] . "' ";						
						$qtnont = $this->db->query($sqltnnt);						
						$taskcontent = "";
						if($qtnont->num_rows > 0){
							foreach($qtnont->rows as $notetask){
								$taskcontent .= $notetask['task_content'].' ';
							}
						}
						
						$sqltnf = "SELECT form_description from " . DB_PREFIX . "forms where facilities_id = '".$tresult['facilities_id']."' and notes_id = '" . $note['notes_id'] . "' ";
						$qtnof = $this->db->query($sqltnf);
						
						//var_dump($qtno->num_rows);
						//echo "<hr>";
						$form_description = "";
						if($qtnof->num_rows > 0){
							foreach($qtnof->rows as $noteform){
								$form_description .= $noteform['form_description'].' ';
							}
						}
						
						
						if($dactivity_info['dashboard_activity_keywords_id'] != null && $dactivity_info['dashboard_activity_keywords_id'] != ""){
							
							$usqla = "UPDATE `" . DB_PREFIX . "dashboard_activity_keywords` SET 
							notes_description = '".$this->db->escape($note['notes_description'])."'
							,task_content = '".$this->db->escape($taskcontent)."'
							,form_description = '".$this->db->escape($form_description)."'							
							where dashboard_activity_keywords_id = '".$this->db->escape($dactivity_info['dashboard_activity_keywords_id'])."' ";
							$this->db->query($usqla);
							
						}else{
							
							$sqla = "INSERT INTO `" . DB_PREFIX . "dashboard_activity_keywords` SET 
							dashboard_activity_id = '".$this->db->escape($dashboard_activity_id)."'
							,notes_description = '".$this->db->escape($note['notes_description'])."'
							,task_content = '".$this->db->escape($taskcontent)."'
							,form_description = '".$this->db->escape($form_description)."'
							,date_added = '".$this->db->escape($note['date_added'])."'
							,date_updated = '".$this->db->escape($note['date_added'])."'
							,facilities_id = '" . $tresult['facilities_id'] . "'
							,notes_id = '" . $note['notes_id'] . "'							
							";
							//echo "<hr>";
							$query = $this->db->query($sqla);
						}
						
						
						$sql = "UPDATE `" . DB_PREFIX . "notes` SET sync_dashboard = '1' where notes_id = '".$note['notes_id']."' ";
						$query = $this->db->query($sql);
						
						$sqlk = "UPDATE `" . DB_PREFIX . "notes_by_keyword` SET sync_dashboard = '1' where notes_id = '".$note['notes_id']."' ";
						$query = $this->db->query($sqlk);
						
						$sqlkf = "UPDATE `" . DB_PREFIX . "forms` SET sync_dashboard = '1' where notes_id = '".$note['notes_id']."' ";
						$query = $this->db->query($sqlkf);
						
						$sqlkfm = "UPDATE `" . DB_PREFIX . "notes_media` SET sync_dashboard = '1' where notes_id = '".$note['notes_id']."' ";
						$query = $this->db->query($sqlkfm);
					}
				}
				
				
				
				/* current day */
				
				$cstartDate = date('Y-m-d', strtotime('now'));
				$cendDate = date('Y-m-d');
				
				$cdate_added = date('Y-m-d H:i:s', strtotime('now'));
				
				$sqltnt = "SELECT DISTINCT COUNT(DISTINCT notes_id) AS total FROM " . DB_PREFIX . "notes where facilities_id = '".$tresult['facilities_id']."' and `date_added` BETWEEN  '".$cstartDate." 00:00:00 ' AND  '".$cstartDate." 23:59:59' and sync_dashboard = '0' and status = '1' ";				
				$query = $this->db->query($sqltnt);		
				$total_notes = $query->row['total'];

				$sqltnta = "SELECT DISTINCT COUNT(DISTINCT notes_id) AS total FROM " . DB_PREFIX . "notes_by_keyword where facilities_id = '".$tresult['facilities_id']."' and `date_added` BETWEEN  '".$cstartDate." 00:00:00 ' AND  '".$cstartDate." 23:59:59' and sync_dashboard = '0' and notes_id > '0' ";				
				$querya = $this->db->query($sqltnta);		
				$total_activenote = $querya->row['total'];
				
				$sqltnth = "SELECT DISTINCT COUNT(DISTINCT notes_id) AS total FROM " . DB_PREFIX . "notes where facilities_id = '".$tresult['facilities_id']."' and `date_added` BETWEEN  '".$cstartDate." 00:00:00 ' AND  '".$cstartDate." 23:59:59' and sync_dashboard = '0' and status = '1' and highlighter_id > '0' ";				
				$queryh = $this->db->query($sqltnth);		
				$total_highlighter = $queryh->row['total'];
				
				$sqltntu = "SELECT DISTINCT COUNT(DISTINCT user_id) AS total FROM " . DB_PREFIX . "notes where facilities_id = '".$tresult['facilities_id']."' and `date_added` BETWEEN  '".$cstartDate." 00:00:00 ' AND  '".$cstartDate." 23:59:59' and sync_dashboard = '0' and status = '1' and user_id != '' ";				
				$queryu = $this->db->query($sqltntu);		
				$total_active_user = $queryu->row['total'];
				
				$sqltntt = "SELECT DISTINCT COUNT(DISTINCT notes_id) AS total FROM " . DB_PREFIX . "notes where facilities_id = '".$tresult['facilities_id']."' and `date_added` BETWEEN  '".$cstartDate." 00:00:00 ' AND  '".$cstartDate." 23:59:59' and sync_dashboard = '0' and is_tag > 0 and form_type = '2' ";				
				$queryt = $this->db->query($sqltntt);		
				$total_intake_tags = $queryt->row['total'];
				
				$sqltntf = "SELECT DISTINCT COUNT(DISTINCT notes_id) AS total FROM " . DB_PREFIX . "forms where facilities_id = '".$tresult['facilities_id']."' and `date_added` BETWEEN  '".$cstartDate." 00:00:00 ' AND  '".$cstartDate." 23:59:59' and sync_dashboard = '0' and notes_id > '0' ";				
				$queryf = $this->db->query($sqltntf);		
				$total_forms = $queryf->row['total'];
				
				$sqltntfi = "SELECT DISTINCT COUNT(DISTINCT notes_id) AS total FROM " . DB_PREFIX . "forms where facilities_id = '".$tresult['facilities_id']."' and `date_added` BETWEEN  '".$cstartDate." 00:00:00 ' AND  '".$cstartDate." 23:59:59' and sync_dashboard = '0' and notes_id > '0' and custom_form_type = '".CUSTOME_INTAKEID."' ";				
				$queryfi = $this->db->query($sqltntfi);		
				$total_screening = $queryfi->row['total'];
				
				$sqltntai = "SELECT DISTINCT COUNT(DISTINCT notes_id) AS total FROM " . DB_PREFIX . "notes_by_keyword where facilities_id = '".$tresult['facilities_id']."' and `date_added` BETWEEN  '".$cstartDate." 00:00:00 ' AND  '".$cstartDate." 23:59:59' and sync_dashboard = '0' and keyword_id = '44' and notes_id > '0' ";				
				$queryai = $this->db->query($sqltntai);		
				$total_incident = $queryai->row['total'];
				
				$sqltntait = "SELECT DISTINCT COUNT(DISTINCT notes_id) AS total FROM " . DB_PREFIX . "notes_by_keyword where facilities_id = '".$tresult['facilities_id']."' and `date_added` BETWEEN  '".$cstartDate." 00:00:00 ' AND  '".$cstartDate." 23:59:59' and sync_dashboard = '0' and is_monitor_time = '1' and notes_id > '0' ";				
				$queryait = $this->db->query($sqltntait);		
				$total_timed_activenote = $queryait->row['total'];
				
				
				$sqltntc = "SELECT DISTINCT COUNT(DISTINCT notes_id) AS total FROM " . DB_PREFIX . "notes where facilities_id = '".$tresult['facilities_id']."' and `date_added` BETWEEN  '".$cstartDate." 00:00:00 ' AND  '".$cstartDate." 23:59:59' and sync_dashboard = '0' and status = '1' and text_color != '' ";				
				$queryc = $this->db->query($sqltntc);		
				$total_colour = $queryc->row['total'];
				
				$sqltntm = "SELECT DISTINCT COUNT(DISTINCT notes_id) AS total FROM " . DB_PREFIX . "notes_media where facilities_id = '".$tresult['facilities_id']."' and `media_date_added` BETWEEN  '".$cstartDate." 00:00:00 ' AND  '".$cstartDate." 23:59:59' and sync_dashboard = '0' and notes_id > '0' ";				
				$querytm = $this->db->query($sqltntm);		
				$total_media = $querytm->row['total'];
				
				$sqltntc = "SELECT DISTINCT COUNT(DISTINCT notes_id) AS total FROM " . DB_PREFIX . "notes where facilities_id = '".$tresult['facilities_id']."' and `date_added` BETWEEN  '".$cstartDate." 00:00:00 ' AND  '".$cstartDate." 23:59:59' and sync_dashboard = '0' and status = '1' and task_id > '0' ";				
				$queryc = $this->db->query($sqltntc);		
				$total_task = $queryc->row['total'];
				
				
				$tnotes_total = array(
					'total_notes' => $total_notes,
					'total_activenote' => $total_activenote,
					'total_highlighter' => $total_highlighter,
					'total_active_user' => $total_active_user,
					'total_intake_tags' => $total_intake_tags,
					'total_forms' => $total_forms,
					'total_screening' => $total_screening,
					'total_incident' => $total_incident,
					'total_timed_activenote' => $total_timed_activenote,
					'total_colour' => $total_colour,
					'total_media' => $total_media,
					'total_task' => $total_task,
					'facilities_id' => $tresult['facilities_id'],
					'date_added' => $cdate_added,
					'date_updated' => $cdate_added,
					'status' => 1,
				);	
				
				//var_dump($tnotes_total);
				//echo "<hr>";
				
				$sqla = "Select dashboard_activity_id from `" . DB_PREFIX . "dashboard_activity` where facilities_id = '" . $tresult['facilities_id'] . "' and `date_added` BETWEEN  '".$cstartDate." 00:00:00 ' AND  '".$cstartDate." 23:59:59' " ;
				$querya = $this->db->query($sqla);
				
				$activity_info = $querya->row;
				
				if($activity_info['dashboard_activity_id'] != null && $activity_info['dashboard_activity_id'] != ""){
					$this->model_syndb_syndb->updateTotal($tnotes_total, $activity_info['dashboard_activity_id']);
					
					$dashboard_activity_id = $activity_info['dashboard_activity_id'];
				}else{
					$dashboard_activity_id = $this->model_syndb_syndb->insertTotal($tnotes_total);
				}
				
				$sqltn = "SELECT notes_id,notes_description,date_added from " . DB_PREFIX . "notes where facilities_id = '".$tresult['facilities_id']."' and `date_added` BETWEEN  '".$cstartDate." 00:00:00 ' AND  '".$cstartDate." 23:59:59' and sync_dashboard = '0' ";
				$qtno = $this->db->query($sqltn);
				
				//var_dump($qtno->num_rows);
				//echo "<hr>";
				if($qtno->num_rows > 0){
					foreach($qtno->rows as $note){
						//var_dump($note);
						//echo "<hr>";
						
						$sqlac = "Select dashboard_activity_keywords_id from `" . DB_PREFIX . "dashboard_activity_keywords` where facilities_id = '" . $tresult['facilities_id'] . "' and `date_added` BETWEEN  '".$cstartDate." 00:00:00 ' AND  '".$cstartDate." 23:59:59' and notes_id = '" . $note['notes_id'] . "' " ;
						$queryac = $this->db->query($sqlac);
						
						$dactivity_info = $queryac->row;
						
						$sqltnnt = "SELECT task_content from " . DB_PREFIX . "notes_by_task where facilities_id = '".$tresult['facilities_id']."' and notes_id = '" . $note['notes_id'] . "' ";						
						$qtnont = $this->db->query($sqltnnt);						
						$taskcontent = "";
						if($qtnont->num_rows > 0){
							foreach($qtnont->rows as $notetask){
								$taskcontent .= $notetask['task_content'].' ';
							}
						}
						
						$sqltnf = "SELECT form_description from " . DB_PREFIX . "forms where facilities_id = '".$tresult['facilities_id']."' and notes_id = '" . $note['notes_id'] . "' ";
						$qtnof = $this->db->query($sqltnf);
						
						//var_dump($qtno->num_rows);
						//echo "<hr>";
						$form_description = "";
						if($qtnof->num_rows > 0){
							foreach($qtnof->rows as $noteform){
								$form_description .= $noteform['form_description'].' ';
							}
						}
						
						
						if($dactivity_info['dashboard_activity_keywords_id'] != null && $dactivity_info['dashboard_activity_keywords_id'] != ""){
							
							$usqla = "UPDATE `" . DB_PREFIX . "dashboard_activity_keywords` SET 
							notes_description = '".$this->db->escape($note['notes_description'])."'
							,task_content = '".$this->db->escape($taskcontent)."'
							,form_description = '".$this->db->escape($form_description)."'							
							where dashboard_activity_keywords_id = '".$this->db->escape($dactivity_info['dashboard_activity_keywords_id'])."' ";
							$this->db->query($usqla);
							
						}else{
							
							$sqla = "INSERT INTO `" . DB_PREFIX . "dashboard_activity_keywords` SET 
							dashboard_activity_id = '".$this->db->escape($dashboard_activity_id)."'
							,notes_description = '".$this->db->escape($note['notes_description'])."'
							,task_content = '".$this->db->escape($taskcontent)."'
							,form_description = '".$this->db->escape($form_description)."'
							,date_added = '".$this->db->escape($note['date_added'])."'
							,date_updated = '".$this->db->escape($note['date_added'])."'
							,facilities_id = '" . $tresult['facilities_id'] . "'
							,notes_id = '" . $note['notes_id'] . "'							
							";
							//echo "<hr>";
							$query = $this->db->query($sqla);
						}
						
						
						$sql = "UPDATE `" . DB_PREFIX . "notes` SET sync_dashboard = '1' where notes_id = '".$note['notes_id']."' ";
						$query = $this->db->query($sql);
						
						$sqlk = "UPDATE `" . DB_PREFIX . "notes_by_keyword` SET sync_dashboard = '1' where notes_id = '".$note['notes_id']."' ";
						$query = $this->db->query($sqlk);
						
						$sqlkf = "UPDATE `" . DB_PREFIX . "forms` SET sync_dashboard = '1' where notes_id = '".$note['notes_id']."' ";
						$query = $this->db->query($sqlkf);
						
						$sqlkfm = "UPDATE `" . DB_PREFIX . "notes_media` SET sync_dashboard = '1' where notes_id = '".$note['notes_id']."' ";
						$query = $this->db->query($sqlkfm);
					}
				}
				
			}
		}
		echo "1";
	}
	
	/*	
	google API
	public function speechToText(){
		
		if($this->config->get('config_transcription') == '1'){
			
			$this->load->model('notes/notes');
			$query = $this->db->query("SELECT * FROM dg_notes_media where audio_attach_type = '1' ");
			$numrow = $query->num_rows;
			
			if($numrow > 0){
			 $stturl = "https://speech.googleapis.com/v1beta1/speech:syncrecognize?key=AIzaSyA9iL7srWZ8-jUKeoVQT64NFj7RDLs583o";
				foreach($query->rows as $row){
					
					$urrl = $row['audio_attach_url']; 
					
					//$upload = file_get_contents($filename);
					$upload = file_get_contents($urrl);
					$upload = base64_encode($upload);
						$data = array(
							"config"    =>  array(
								"encoding"      =>  "FLAC",
								"sampleRate"    =>  16000,
								"languageCode"  =>  "en-US",
							),
							"audio"     =>  array(
								"content"       =>  $upload,
							)
						);

						$jsonData = json_encode($data);
						//$headers = array(    "Content-Type: audio/flac", "Transfer-Encoding: chunked");
						
						$headers = array( "Content-Type: application/json");
						$ch = curl_init();
 
						curl_setopt($ch, CURLOPT_URL, $stturl);
						curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
						curl_setopt($ch, CURLOPT_POST, TRUE);
						curl_setopt($ch, CURLOPT_BINARYTRANSFER, TRUE);
						curl_setopt($ch, CURLOPT_POST, true);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

						$results = curl_exec($ch);

						//var_dump($results);

						$contents = json_decode($results,true);
	 
						$ndata = array();
						
						
						foreach($contents["results"] as $content){
							foreach($content['alternatives'] as $b){
								$ndata[] = $b['transcript'];
							}
							
						}
						
						
						$ncontent = implode(" ",$ndata);
					
						$notes_data = $this->model_notes_notes->getnotes($row['notes_id']);
						
						$notes_description = $notes_data['notes_description'];
						$facilities_id = $notes_data['facilities_id'];
						$date_added = $notes_data['date_added'];
						
						$notesContent = $notes_description.' | Voice Transcript: '.$ncontent.'| ';
						$formData = array();
						$formData['notes_description'] = $notesContent;
						$formData['facilities_id'] = $facilities_id;
						$formData['date_added'] = $date_added;
					
					
						$slq1 = "UPDATE dg_notes_media SET audio_attach_type = '2' where notes_media_id = '".$row['notes_media_id']."'";
						$this->db->query($slq1);
						
						$this->model_notes_notes->updateNotesContent($row['notes_id'], $formData);

						
						
						unlink($filename);
						echo "Success";
						
					}
			
			}
		
		}
	}*/
	
	
	public function medicationTask(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "tags` where status = '1' ";
		$query = $this->db->query($sql);
		
		define('HOUR', '1');
		define('MINUTES', '30');
		define('DAY', '1');
		
		date_default_timezone_set('US/Eastern');
		
		$startDate = date('Y-m-d', strtotime('now'));
		$endDate = date('Y-m-d', strtotime('now'));
		
		
		$current_stime = date('H:i:s', strtotime('now'));
		
		
		//var_dump($current_stime);
		
		//$current_etime = date('H:i:s', strtotime("+".HOUR." hour"));
		$current_etime = date('H:i:s', strtotime("+".MINUTES." minutes"));
		
		
		$this->load->model('createtask/createtask');
		
		if($query->num_rows > 0){
			
			foreach($query->rows as $row){
				//var_dump($row);
				//echo "<hr>";
				
				//$sql2 = "SELECT * FROM `" . DB_PREFIX . "medication` where create_task = '0' and status = '1' and tags_id = '".$row['tags_id']."' and `start_date` <=  '".$startDate."' AND end_date >=  '".$endDate."' "; 
				
				
				//$sql2 = "select n.*,m.* from `" . DB_PREFIX . "medication` n left JOIN `" . DB_PREFIX . "medication_time` m on m.medication_id=n.medication_id where n.create_task = '0' and n.status = '1' and n.tags_id = '".$row['tags_id']."' and n.start_date <=  '".$startDate."' AND n.end_date >=  '".$endDate."' order by m.start_time ";
				
				
				$sql2 = "SELECT n.*, m.start_time as m_start_time, GROUP_CONCAT(m.medication_id SEPARATOR ',') as m_medication_id, GROUP_CONCAT(m.medication_time_id SEPARATOR ',') as m_medication_time_id FROM " . DB_PREFIX . "medication n JOIN " . DB_PREFIX . "medication_time m ON (m.medication_id=n.medication_id) where m.create_task = '0' and n.status = '1' and n.tags_id = '".$row['tags_id']."' and n.start_date <=  '".$startDate."' AND n.end_date >=  '".$endDate."'  and (m.`start_time` BETWEEN  '".$current_stime."' AND  '".$current_etime."') GROUP BY m.start_time order by m.start_time";
				
				//echo "<hr>";  
 
				$query2 = $this->db->query($sql2);  
				//var_dump($query2->num_rows);
				
				
				if($query2->num_rows > 0){
					$medicineArray  = array();
					foreach($query2->rows as $row2){
						//var_dump($row2);
						//echo "<hr>"; 

						$addtaskw = array();
								
						if($row2['m_start_time'] != null && $row2['m_start_time'] != ""){
							$snooze_time71 = 0;
							$thestime61 = $row2['m_start_time'];
						}else{
							$snooze_time71 = 0;
							$thestime61 = date('H:i:s');
						}

						$taskTime = date("H:i:s",strtotime("+".$snooze_time71." minutes",strtotime($thestime61)));
								
						//$start_date = date('m-d-Y',strtotime($row2['start_date']));						
						$start_date = date('m-d-Y',strtotime('now'));						
								
						$date = str_replace('-', '/', $start_date);
						$res = explode("/", $date);
						$taskDate = $res[1]."-".$res[0]."-".$res[2];
								
																		
						$end_date = date('m-d-Y',strtotime($row2['end_date']));											
						$date2 = str_replace('-', '/', $end_date);
						$res2 = explode("/", $date2);
						$end_recurrence_date = $res2[1]."-".$res2[0]."-".$res2[2];
								
								
						$addtaskw['taskDate'] = date('m-d-Y', strtotime($taskDate));
						$addtaskw['end_recurrence_date'] = date('m-d-Y', strtotime($taskDate));
						$addtaskw['recurrence'] = 'none';
						$addtaskw['recurnce_week'] = '';
						$addtaskw['recurnce_hrly'] = '';
						$addtaskw['recurnce_month'] = '';
						$addtaskw['recurnce_day'] = '';
						$addtaskw['taskTime'] = $taskTime; //date('H:i:s');
						$addtaskw['endtime'] = $stime8;
						$addtaskw['description'] = 'Medication for '.$row['emp_first_name'].' '.$row['emp_last_name'];
						$addtaskw['assignto'] = '';
						$addtaskw['tasktype'] = '2';
						$addtaskw['numChecklist'] = '';
						$addtaskw['task_alert'] = '1';
						$addtaskw['alert_type_sms'] = '';
						$addtaskw['alert_type_notification'] = '1';
						$addtaskw['alert_type_email'] = '';
						$addtaskw['rules_task'] = '';
								
						$addtaskw['locations_id'] = $row['locations_id'];
						$addtaskw['facilities_id'] = $row['facilities_id'];
						$addtaskw['tags_id'] = $row['tags_id'];
								
						//var_dump($addtaskw);
						//	echo "<hr>";		
						$task_id = $this->model_createtask_createtask->addcreatetask($addtaskw, $row['facilities_id']);
						//var_dump($row2['m_medication_id']);
						$medicationids = explode(",",$row2['m_medication_id']);
						//var_dump($medicationtimeids);
						
						
						foreach($medicationids as $medicationid){
							$sql2m = "SELECT * FROM `" . DB_PREFIX . "medication` where medication_id = '".$medicationid."' ";
							$querym = $this->db->query($sql2m);  
							//var_dump($querym->row);
							//echo "<hr>";
							
							$sql = "INSERT INTO `" . DB_PREFIX . "createtask_by_medication` SET id = '".$task_id."', facilities_id = '" . $row['facilities_id']. "', locations_id = '" . $row['locations_id']. "', tags_id = '" . $row['tags_id'] . "', medication_id = '" . $querym->row['medication_id']. "', drug_name = '" . $querym->row['drug_name']. "', dose = '" . $querym->row['dose']. "', drug_type = '" . $querym->row['drug_type']. "', quantity = '" . $querym->row['quantity']. "', frequency = '" . $querym->row['frequency']. "', start_time = '" . $taskTime. "', instructions = '" . $this->db->escape($querym->row['instructions']). "', count = '" . $querym->row['count']. "', complete_status = '0' ";
							
							$this->db->query($sql); 
							//echo "<hr>";
							$sqlu = "UPDATE `" . DB_PREFIX . "medication_time` SET create_task = '1' where medication_id = '".$medicationid."' ";
							$this->db->query($sqlu);
						}
						
					}
					
				}
				
			}
		} 
		
		echo "Success";
	}
	
	/*public function medicationTask(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "tags` where status = '1' ";
		$query = $this->db->query($sql);
		
		define('HOUR', '1');
		define('DAY', '1');
		
		$startDate = date('Y-m-d', strtotime('now'));
		$endDate = date('Y-m-d', strtotime('now'));
		
		
		$current_stime = date('H:i:s', strtotime('now'));
		$current_etime = date('H:i:s', strtotime("+".HOUR." hour"));
		
		
		$this->load->model('createtask/createtask');
		
		if($query->num_rows > 0){
			
			foreach($query->rows as $row){
				//var_dump($row);
				//echo "<hr>";
				
				//$sql2 = "SELECT * FROM `" . DB_PREFIX . "medication` where create_task = '0' and status = '1' and tags_id = '".$row['tags_id']."' and `start_date` <=  '".$startDate."' AND end_date >=  '".$endDate."' "; 
				
				
				$sql2 = "select n.*,m.* from `" . DB_PREFIX . "medication` n left JOIN `" . DB_PREFIX . "medication_time` m on m.medication_id=n.medication_id where n.create_task = '0' and n.status = '1' and n.tags_id = '".$row['tags_id']."' and n.start_date <=  '".$startDate."' AND n.end_date >=  '".$endDate."' order by m.start_time ";
				
				//echo "<hr>";  
 
				$query2 = $this->db->query($sql2);  
				//var_dump($query2->num_rows);
				 
				
				if($query2->num_rows > 0){
					$medicineArray  = array();
					foreach($query2->rows as $row2){
						//var_dump($row2);
						if($row2['start_time'] != null && $row2['start_time'] != ""){
							$snooze_time71 = 0;
							$thestime61 = $row2['start_time'];
						}else{
							$snooze_time71 = 0;
							$thestime61 = date('H:i:s');
						}
						
						$taskTime = date("H:i:s",strtotime("+".$snooze_time71." minutes",strtotime($thestime61)));
								
						$sql = "INSERT INTO `" . DB_PREFIX . "createtask_by_medication` SET id = '', facilities_id = '" . $row['facilities_id']. "', locations_id = '" . $row['locations_id']. "', tags_id = '" . $row['tags_id'] . "', medication_id = '" . $row2['medication_id']. "', drug_name = '" . $row2['drug_name']. "', dose = '" . $row2['dose']. "', drug_type = '" . $row2['drug_type']. "', quantity = '" . $row2['quantity']. "', frequency = '" . $row2['frequency']. "', start_time = '" . $taskTime. "', instructions = '" . $this->db->escape($row2['instructions']). "', count = '" . $row2['count']. "', complete_status = '0' ";
						//$this->db->query($sql); 
						
						$medicineArray[] = $row2['medication_id'];
						
						
					}
					
					var_dump($medicineArray);
					
					echo "<hr>";
				}
				
			}
		} 
	}*/
	
	
	public function formrulenotification(){
		$this->load->model('notes/notes');
		$this->load->model('notes/rules');
		$this->load->model('setting/highlighter');
		$this->load->model('setting/country');
		$this->load->model('setting/zone');
		$this->load->model('setting/timezone');
		$this->load->model('facilities/facilities');
		$this->load->model('user/user');
		
		$this->load->model('form/form');
		
		
		//require_once(DIR_SYSTEM . 'library/twilio-php-master/smsconfig.php');
		require_once(DIR_SYSTEM . 'library/PHPMailer-master/PHPMailerAutoload.php');
		
		
		$sql = "SELECT * FROM " . DB_PREFIX . "formrules r LEFT JOIN " . DB_PREFIX . "formrules_tigger rt ON (r.rules_id = rt.rules_id) where r.status='1' ";
		$query = $this->db->query($sql);
		
		if ($query->num_rows) {
			foreach ($query->rows as $rule) {
				
				$allnotesIds = array();
				
				$rulename = $rule['rules_name'];
				$rules_id = $rule['rules_id'];
				//var_dump($rules_id);
				
				
				$facility = $this->model_facilities_facilities->getfacilities($rule['facilities_id']);
							
				$timezone_info = $this->model_setting_timezone->gettimezone($facility['timezone_id']);
							
				date_default_timezone_set($timezone_info['timezone_value']);
							
				$current_date = date('Y-m-d', strtotime('now'));
						
				$current_time = date('Y-m-d H:i', strtotime('now'));
				
				
				
				
				
				if($rule['rules_operation'] == '2'){
					$onschedule_rules_modules = unserialize($rule['onschedule_rules_module']);
					$forms_fields_search = 'Task';
					foreach($onschedule_rules_modules as $rules_module){
						$sqls = "select DISTINCT n.*,f.custom_form_type,f.forms_id from `" . DB_PREFIX . "notes` n ";
						$sqls .= "left JOIN " . DB_PREFIX . "forms f on f.notes_id=n.notes_id  "; 
						$sqls .= 'where 1 = 1 ';
						$sqls .= " and n.facilities_id = '".$facility['facilities_id']."'";
						$sqls .= " and f.custom_form_type = '".$rule['forms_id']."'";
						$sqls .= " and f.is_discharge = '0'";
						$sqls .= " and n.form_alert_send_email = '0' ";
						$sqls .= " and n.form_alert_send_sms = '0' ";
						$sqls .= " and n.form_snooze_dismiss = '2' ";		
										
						
						$sqls .= " and n.status = '1' ORDER BY n.notetime DESC  ";
							
						$query = $this->db->query($sqls);
						//var_dump($query);
						
						if ($query->num_rows) {
							
							foreach($query->rows as $result){
								
								//var_dump($result);
								
								$date_added = $result['date_added'];
								$form_due_date_after = $rules_module['form_due_date_after'] ; 
								
								
								
								switch ($rules_module['form_due_date']){
									
									
									case 'Month' : 
											
									$newdate = date("Y-m-d",strtotime(date("Y-m-d H:i", strtotime($date_added)) . " +".$form_due_date_after." month")); 
									
									//var_dump($newdate);
									//if($newdate == $current_date){
										
										if($rules_module['formalerts'] !=NULL && $rules_module['formalerts'] !=""){
											foreach($rules_module['formalerts'] as $formalerts){
												$task_alertselection = $formalerts['task_alertselection'];
												
												//var_dump($formalerts);
												
												
												
												switch ($formalerts['task_alertselection_before']){
											
													case 'Month' : 
													//var_dump($form_due_date_after);
													
														$newdate = date("Y-m-d",strtotime(date("Y-m-d H:i", strtotime($date_added)) . " +".$form_due_date_after." month")); 
														$newdatebefore = date("Y-m-d",strtotime(date("Y-m-d H:i", strtotime($newdate)) . "-".$task_alertselection." month")); 
														
														if($current_date == $newdatebefore){
															
															$allnotesIds[] = array(
																'notes_id' => $result['notes_id'],
																'rules_type' => $rule['rules_name'],
																'rules_value' => $forms_fields_search,
																'user_roles' => $formalerts['user_roles'],
																'userids' => $formalerts['userids'],
																'date_added' => $date_added,
																'form_due_date_after' => $form_due_date_after,
																'newdate' => $newdate,
																'new_time' => '',
																'form_due_date' => $rules_module['form_due_date'],
																'rules_id' => $rules_id,
																'rule_action' => $formalerts['rule_action'],
															);
															
															/*
															$this->formSendEmail($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
															
															if(in_array('1', $rules_module['action'])){
																$this->formSendSMS($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
															}*/
																		
														}
														break;
																
														case 'Days' : 
															
														
															$newdate = date("Y-m-d",strtotime(date("Y-m-d H:i", strtotime($date_added)) . " +".$form_due_date_after." month")); 
															$newdatebefore = date("Y-m-d",strtotime(date("Y-m-d H:i", strtotime($newdate)) . "-".$task_alertselection." day")); 
															
															//var_dump($newdatebefore);
															if($current_date == $newdatebefore){
																
																$allnotesIds[] = array(
																	'notes_id' => $result['notes_id'],
																	'rules_type' => $rule['rules_name'],
																	'rules_value' => $forms_fields_search,
																	'user_roles' => $formalerts['user_roles'],
																	'userids' => $formalerts['userids'],
																	'date_added' => $date_added,
																	'form_due_date_after' => $form_due_date_after,
																	'newdate' => $newdate,
																	'new_time' => '',
																	'form_due_date' => $rules_module['form_due_date'],
																	'rules_id' => $rules_id,
																	'rule_action' => $formalerts['rule_action'],
																);
																
																/*$this->formSendEmail($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
																
																if(in_array('1', $rules_module['action'])){
																	$this->formSendSMS($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
																}*/
															}
														break;
													
														case 'Hours' :
															
															$newdate = date("Y-m-d H:i",strtotime(date("Y-m-d H:i", strtotime($date_added)) . " +".$form_due_date_after." month"));
															//$newdate = date('H:i',strtotime('+'.$form_due_date_after.' hour',strtotime($date_added)));
															$newdateafter = date('Y-m-d H:i',strtotime('-'.$task_alertselection.' hour',strtotime($newdate)));
															
															if($newdateafter == $current_time){
																$allnotesIds[] = array(
																	'notes_id' => $result['notes_id'],
																	'rules_type' => $rule['rules_name'],
																	'rules_value' => $forms_fields_search,
																	'user_roles' => $formalerts['user_roles'],
																	'userids' => $formalerts['userids'],
																	'date_added' => $date_added,
																	'form_due_date_after' => $form_due_date_after,
																	'newdate' => $newdate,
																	'new_time' => '',
																	'form_due_date' => $rules_module['form_due_date'],
																	'rules_id' => $rules_id,
																	'rule_action' => $formalerts['rule_action'],
																);
																
																/*$this->formSendEmail($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
																
																if(in_array('1', $rules_module['action'])){
																	$this->formSendSMS($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
																}*/
															}
														break;
														case 'Minutes' : 
																	
															$newdate = date("Y-m-d H:i",strtotime(date("Y-m-d H:i", strtotime($date_added)) . " +".$form_due_date_after." month"));
															$newdateafter = date('Y-m-d H:i',strtotime('-'.$task_alertselection.' minutes',strtotime($newdate)));
															
															if($newdateafter == $current_time){
																$allnotesIds[] = array(
																	'notes_id' => $result['notes_id'],
																	'rules_type' => $rule['rules_name'],
																	'rules_value' => $forms_fields_search,
																	'user_roles' => $formalerts['user_roles'],
																	'userids' => $formalerts['userids'],
																	'date_added' => $date_added,
																	'form_due_date_after' => $form_due_date_after,
																	'newdate' => $newdate,
																	'new_time' => '',
																	'form_due_date' => $rules_module['form_due_date'],
																	'rules_id' => $rules_id,
																	'rule_action' => $formalerts['rule_action'],
																);
																
																/*$this->formSendEmail($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
																
																if(in_array('1', $rules_module['action'])){
																	$this->formSendSMS($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
																}*/
															}
															break;
														case 'is submitted' : 
															$newdate = date('Y-m-d',strtotime($date_added));
															if($newdate == $current_date){
																$allnotesIds[] = array(
																	'notes_id' => $result['notes_id'],
																	'rules_type' => $rule['rules_name'],
																	'rules_value' => $forms_fields_search,
																	'user_roles' => $formalerts['user_roles'],
																	'userids' => $formalerts['userids'],
																	'date_added' => $date_added,
																	'form_due_date_after' => $form_due_date_after,
																	'newdate' => $newdate,
																	'new_time' => '',
																	'form_due_date' => $rules_module['form_due_date'],
																	'rules_id' => $rules_id,
																	'rule_action' => $formalerts['rule_action'],
																);
																/*
																$this->formSendEmail($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
																
																if(in_array('1', $rules_module['action'])){
																	$this->formSendSMS($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
																}*/
															}
														break;
														case 'is updated' : 
															$newdate = date('Y-m-d',strtotime($date_added));
															if($newdate == $current_date){
																$allnotesIds[] = array(
																	'notes_id' => $result['notes_id'],
																	'rules_type' => $rule['rules_name'],
																	'rules_value' => $forms_fields_search,
																	'user_roles' => $formalerts['user_roles'],
																	'userids' => $formalerts['userids'],
																	'date_added' => $date_added,
																	'form_due_date_after' => $form_due_date_after,
																	'newdate' => $newdate,
																	'new_time' => '',
																	'form_due_date' => $rules_module['form_due_date'],
																	'rules_id' => $rules_id,
																	'rule_action' => $formalerts['rule_action'],
																);
																
																/*$this->formSendEmail($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
																
																if(in_array('1', $rules_module['action'])){
																	$this->formSendSMS($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
																}*/
															}
														break;
												}
												
											}
										}
													
													
									//}
												
									break;	

									case 'Days' : 
									
										//var_dump($date_added);
										//var_dump($form_due_date_after);
										//echo "<hr>";
											
										$newdate = date("Y-m-d",strtotime(date("Y-m-d H:i", strtotime($date_added)) . " +".$form_due_date_after." day")); 
										
										//var_dump($newdate);
										
										if($rules_module['formalerts'] !=NULL && $rules_module['formalerts'] !=""){
											
											foreach( $rules_module['formalerts'] as $formalerts){
												$task_alertselection = $formalerts['task_alertselection'];
												switch ($formalerts['task_alertselection_before']){
																
														case 'Days' : 
															
															
															//var_dump($task_alertselection);
															//$newdate = date("Y-m-d",strtotime(date("Y-m-d H:i", strtotime($date_added)) . " +".$form_due_date_after." day")); 
															$newdatebefore = date("Y-m-d",strtotime(date("Y-m-d H:i", strtotime($newdate)) . "-".$task_alertselection." day")); 
															
															//var_dump($newdatebefore );
															
															if($newdatebefore == $current_date){
																
																$allnotesIds[] = array(
																	'notes_id' => $result['notes_id'],
																	'rules_type' => $rule['rules_name'],
																	'rules_value' => $forms_fields_search,
																	'user_roles' => $formalerts['user_roles'],
																	'userids' => $formalerts['userids'],
																	'date_added' => $date_added,
																	'form_due_date_after' => $form_due_date_after,
																	'newdate' => $newdate,
																	'new_time' => '',
																	'form_due_date' => $rules_module['form_due_date'],
																	'rules_id' => $rules_id,
																	'rule_action' => $formalerts['rule_action'],
																);
																
																/*$this->formSendEmail($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
																
																if(in_array('1', $rules_module['action'])){
																	$this->formSendSMS($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
																}*/
															}
														break;
													
														case 'Hours' : 
															$newdate = date("Y-m-d H:i",strtotime(date("Y-m-d H:i", strtotime($date_added)) . " +".$form_due_date_after." day")); 
															$newdateafter = date('Y-m-d H:i',strtotime('-'.$task_alertselection.' hour',strtotime($newdate)));
															
															if($newdateafter == $current_time){
																$allnotesIds[] = array(
																	'notes_id' => $result['notes_id'],
																	'rules_type' => $rule['rules_name'],
																	'rules_value' => $forms_fields_search,
																	'user_roles' => $formalerts['user_roles'],
																	'userids' => $formalerts['userids'],
																	'date_added' => $date_added,
																	'form_due_date_after' => $form_due_date_after,
																	'newdate' => $newdate,
																	'new_time' => '',
																	'form_due_date' => $rules_module['form_due_date'],
																	'rules_id' => $rules_id,
																	'rule_action' => $formalerts['rule_action'],
																);
																
																/*$this->formSendEmail($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
																
																if(in_array('1', $rules_module['action'])){
																	$this->formSendSMS($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
																}*/
															}
														break;
														case 'Minutes' : 
																	
															$newdate = date("Y-m-d H:i",strtotime(date("Y-m-d H:i", strtotime($date_added)) . " +".$form_due_date_after." day"));
															$newdateafter = date('Y-m-d H:i',strtotime('-'.$task_alertselection.' minutes',strtotime($newdate)));
															

															
															if($newdateafter == $current_time){
																$allnotesIds[] = array(
																	'notes_id' => $result['notes_id'],
																	'rules_type' => $rule['rules_name'],
																	'rules_value' => $forms_fields_search,
																	'user_roles' => $formalerts['user_roles'],
																	'userids' => $formalerts['userids'],
																	'date_added' => $date_added,
																	'form_due_date_after' => $form_due_date_after,
																	'newdate' => $newdate,
																	'new_time' => '',
																	'form_due_date' => $rules_module['form_due_date'],
																	'rules_id' => $rules_id,
																	'rule_action' => $formalerts['rule_action'],
																);
																
																/*$this->formSendEmail($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
																
																if(in_array('1', $rules_module['action'])){
																	$this->formSendSMS($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
																}*/
															}
															break;
														case 'is submitted' : 
															$newdate = date('Y-m-d',strtotime($date_added));
															if($newdate == $current_date){
																$allnotesIds[] = array(
																	'notes_id' => $result['notes_id'],
																	'rules_type' => $rule['rules_name'],
																	'rules_value' => $forms_fields_search,
																	'user_roles' => $formalerts['user_roles'],
																	'userids' => $formalerts['userids'],
																	'date_added' => $date_added,
																	'form_due_date_after' => $form_due_date_after,
																	'newdate' => $newdate,
																	'new_time' => '',
																	'form_due_date' => $rules_module['form_due_date'],
																	'rules_id' => $rules_id,
																	'rule_action' => $formalerts['rule_action'],
																);
																
																/*$this->formSendEmail($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
																
																if(in_array('1', $rules_module['action'])){
																	$this->formSendSMS($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
																}*/
															}
														break;
														case 'is updated' : 
															$newdate = date('Y-m-d',strtotime($date_added));
															if($newdate == $current_date){
																$allnotesIds[] = array(
																	'notes_id' => $result['notes_id'],
																	'rules_type' => $rule['rules_name'],
																	'rules_value' => $forms_fields_search,
																	'user_roles' => $formalerts['user_roles'],
																	'userids' => $formalerts['userids'],
																	'date_added' => $date_added,
																	'form_due_date_after' => $form_due_date_after,
																	'newdate' => $newdate,
																	'new_time' => '',
																	'form_due_date' => $rules_module['form_due_date'],
																	'rules_id' => $rules_id,
																	'rule_action' => $formalerts['rule_action'],
																);
																
																/*$this->formSendEmail($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
																
																if(in_array('1', $rules_module['action'])){
																	$this->formSendSMS($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
																}*/
															}
														break;
												}
												
											}
										}
													
									
									break;
									
									case 'Hours' : 
										$newdate = date('H:i',strtotime('+'.$form_due_date_after.' hour',strtotime($date_added)));
										
										if($rules_module['formalerts'] !=NULL && $rules_module['formalerts'] !=""){
											
											foreach( $rules_module['formalerts'] as $formalerts){
												$task_alertselection = $formalerts['task_alertselection'];
												switch ($formalerts['task_alertselection_before']){
																
														case 'Hours' : 
															$newdate = date("Y-m-d H:i",strtotime(date("Y-m-d H:i", strtotime($date_added)) . " +".$form_due_date_after." day")); 
															$newdateafter = date('Y-m-d H:i',strtotime('-'.$task_alertselection.' hour',strtotime($newdate)));
															
															if($newdateafter == $current_time){
																$allnotesIds[] = array(
																	'notes_id' => $result['notes_id'],
																	'rules_type' => $rule['rules_name'],
																	'rules_value' => $forms_fields_search,
																	'user_roles' => $formalerts['user_roles'],
																	'userids' => $formalerts['userids'],
																	'date_added' => $date_added,
																	'form_due_date_after' => $form_due_date_after,
																	'newdate' => $newdate,
																	'new_time' => '',
																	'form_due_date' => $rules_module['form_due_date'],
																	'rules_id' => $rules_id,
																	'rule_action' => $formalerts['rule_action'],
																);
																
																/*$this->formSendEmail($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
																
																if(in_array('1', $rules_module['action'])){
																	$this->formSendSMS($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
																}*/
															}
														break;
														case 'Minutes' : 
																	
															$newdate = date("Y-m-d H:i",strtotime(date("Y-m-d H:i", strtotime($date_added)) . " +".$form_due_date_after." day"));
															$newdateafter = date('Y-m-d H:i',strtotime('-'.$task_alertselection.' minutes',strtotime($newdate)));
															

															
															if($newdateafter == $current_time){
																$allnotesIds[] = array(
																	'notes_id' => $result['notes_id'],
																	'rules_type' => $rule['rules_name'],
																	'rules_value' => $forms_fields_search,
																	'user_roles' => $formalerts['user_roles'],
																	'userids' => $formalerts['userids'],
																	'date_added' => $date_added,
																	'form_due_date_after' => $form_due_date_after,
																	'newdate' => $newdate,
																	'new_time' => '',
																	'form_due_date' => $rules_module['form_due_date'],
																	'rules_id' => $rules_id,
																	'rule_action' => $formalerts['rule_action'],
																);
																
																/*$this->formSendEmail($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
																
																if(in_array('1', $rules_module['action'])){
																	$this->formSendSMS($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
																}*/
															}
															break;
														case 'is submitted' : 
															$newdate = date('Y-m-d',strtotime($date_added));
															if($newdate == $current_date){
																$allnotesIds[] = array(
																	'notes_id' => $result['notes_id'],
																	'rules_type' => $rule['rules_name'],
																	'rules_value' => $forms_fields_search,
																	'user_roles' => $formalerts['user_roles'],
																	'userids' => $formalerts['userids'],
																	'date_added' => $date_added,
																	'form_due_date_after' => $form_due_date_after,
																	'newdate' => $newdate,
																	'new_time' => '',
																	'form_due_date' => $rules_module['form_due_date'],
																	'rules_id' => $rules_id,
																	'rule_action' => $formalerts['rule_action'],
																);
																
																/*$this->formSendEmail($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
																
																if(in_array('1', $rules_module['action'])){
																	$this->formSendSMS($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
																}*/
															}
														break;
														case 'is updated' : 
															$newdate = date('Y-m-d',strtotime($date_added));
															if($newdate == $current_date){
																$allnotesIds[] = array(
																	'notes_id' => $result['notes_id'],
																	'rules_type' => $rule['rules_name'],
																	'rules_value' => $forms_fields_search,
																	'user_roles' => $formalerts['user_roles'],
																	'userids' => $formalerts['userids'],
																	'date_added' => $date_added,
																	'form_due_date_after' => $form_due_date_after,
																	'newdate' => $newdate,
																	'new_time' => '',
																	'form_due_date' => $rules_module['form_due_date'],
																	'rules_id' => $rules_id,
																	'rule_action' => $formalerts['rule_action'],
																);
																
																/*$this->formSendEmail($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
																
																if(in_array('1', $rules_module['action'])){
																	$this->formSendSMS($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
																}*/
															}
														break;
												}
												
											}
										}
											
										
										
										
									break;
									case 'Minutes' : 
										$newdate = date('H:i',strtotime('+'.$form_due_date_after.' minutes',strtotime($date_added)));
										if($rules_module['formalerts'] !=NULL && $rules_module['formalerts'] !=""){
											
											foreach( $rules_module['formalerts'] as $formalerts){
												$task_alertselection = $formalerts['task_alertselection'];
												switch ($formalerts['task_alertselection_before']){
																
														case 'Hours' : 
															$newdate = date("Y-m-d H:i",strtotime(date("Y-m-d H:i", strtotime($date_added)) . " +".$form_due_date_after." day")); 
															$newdateafter = date('Y-m-d H:i',strtotime('-'.$task_alertselection.' hour',strtotime($newdate)));
															
															if($newdateafter == $current_time){
																$allnotesIds[] = array(
																	'notes_id' => $result['notes_id'],
																	'rules_type' => $rule['rules_name'],
																	'rules_value' => $forms_fields_search,
																	'user_roles' => $formalerts['user_roles'],
																	'userids' => $formalerts['userids'],
																	'date_added' => $date_added,
																	'form_due_date_after' => $form_due_date_after,
																	'newdate' => $newdate,
																	'new_time' => '',
																	'form_due_date' => $rules_module['form_due_date'],
																	'rules_id' => $rules_id,
																	'rule_action' => $formalerts['rule_action'],
																);
																
																/*$this->formSendEmail($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
																
																if(in_array('1', $rules_module['action'])){
																	$this->formSendSMS($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
																}*/
															}
														break;
														case 'Minutes' : 
																	
															$newdate = date("Y-m-d H:i",strtotime(date("Y-m-d H:i", strtotime($date_added)) . " +".$form_due_date_after." day"));
															$newdateafter = date('Y-m-d H:i',strtotime('-'.$task_alertselection.' minutes',strtotime($newdate)));
															

															
															if($newdateafter == $current_time){
																$allnotesIds[] = array(
																	'notes_id' => $result['notes_id'],
																	'rules_type' => $rule['rules_name'],
																	'rules_value' => $forms_fields_search,
																	'user_roles' => $formalerts['user_roles'],
																	'userids' => $formalerts['userids'],
																	'date_added' => $date_added,
																	'form_due_date_after' => $form_due_date_after,
																	'newdate' => $newdate,
																	'new_time' => '',
																	'form_due_date' => $rules_module['form_due_date'],
																	'rules_id' => $rules_id,
																	'rule_action' => $formalerts['rule_action'],
																);
																
																/*$this->formSendEmail($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
																
																if(in_array('1', $rules_module['action'])){
																	$this->formSendSMS($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
																}*/
															}
															break;
														case 'is submitted' : 
															$newdate = date('Y-m-d',strtotime($date_added));
															if($newdate == $current_date){
																$allnotesIds[] = array(
																	'notes_id' => $result['notes_id'],
																	'rules_type' => $rule['rules_name'],
																	'rules_value' => $forms_fields_search,
																	'user_roles' => $formalerts['user_roles'],
																	'userids' => $formalerts['userids'],
																	'date_added' => $date_added,
																	'form_due_date_after' => $form_due_date_after,
																	'newdate' => $newdate,
																	'new_time' => '',
																	'form_due_date' => $rules_module['form_due_date'],
																	'rules_id' => $rules_id,
																	'rule_action' => $formalerts['rule_action'],
																);
																
																/*$this->formSendEmail($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
																
																if(in_array('1', $rules_module['action'])){
																	$this->formSendSMS($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
																}*/
															}
														break;
														case 'is updated' : 
															$newdate = date('Y-m-d',strtotime($date_added));
															if($newdate == $current_date){
																$allnotesIds[] = array(
																	'notes_id' => $result['notes_id'],
																	'rules_type' => $rule['rules_name'],
																	'rules_value' => $forms_fields_search,
																	'user_roles' => $formalerts['user_roles'],
																	'userids' => $formalerts['userids'],
																	'date_added' => $date_added,
																	'form_due_date_after' => $form_due_date_after,
																	'newdate' => $newdate,
																	'new_time' => '',
																	'form_due_date' => $rules_module['form_due_date'],
																	'rules_id' => $rules_id,
																	'rule_action' => $formalerts['rule_action'],
																);
																
																/*$this->formSendEmail($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
																
																if(in_array('1', $rules_module['action'])){
																	$this->formSendSMS($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
																}*/
															}
														break;
												}
												
											}
										}
										
									break;
									
									case 'is submitted' : 
										$newdate = date('Y-m-d',strtotime($date_added));
										
										
										
										if($rules_module['formalerts'] !=NULL && $rules_module['formalerts'] !=""){
											
											foreach( $rules_module['formalerts'] as $formalerts){
												$task_alertselection = $formalerts['task_alertselection'];
												switch ($formalerts['task_alertselection_before']){
																
														case 'is submitted' : 
															$newdate = date('Y-m-d',strtotime($date_added));
															if($newdate == $current_date){
																$allnotesIds[] = array(
																	'notes_id' => $result['notes_id'],
																	'rules_type' => $rule['rules_name'],
																	'rules_value' => $forms_fields_search,
																	'user_roles' => $formalerts['user_roles'],
																	'userids' => $formalerts['userids'],
																	'date_added' => $date_added,
																	'form_due_date_after' => $form_due_date_after,
																	'newdate' => $newdate,
																	'new_time' => '',
																	'form_due_date' => $rules_module['form_due_date'],
																	'rules_id' => $rules_id,
																	'rule_action' => $formalerts['rule_action'],
																);
																
																/*$this->formSendEmail($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
																
																if(in_array('1', $rules_module['action'])){
																	$this->formSendSMS($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
																}*/
															}
														break;
														
												}
												
											}
										}
										
										
										
									
									break;
									
									case 'is updated' : 
										$newdate = date('Y-m-d',strtotime($date_added));
										if($newdate == $current_date){
											$allnotesIds[] = array(
												'notes_id' => $result['notes_id'],
												'rules_type' => $rule['rules_name'],
												'rules_value' => $forms_fields_search,
												'user_roles' => $formalerts['user_roles'],
												'userids' => $formalerts['userids'],
												'date_added' => $date_added,
												'form_due_date_after' => $form_due_date_after,
												'newdate' => $newdate,
												'new_time' => '',
												'form_due_date' => $rules_module['form_due_date'],
												'rules_id' => $rules_id,
												'rule_action' => $formalerts['rule_action'],
											);
											
											/*$this->formSendEmail($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
																
											if(in_array('1', $rules_module['action'])){
												$this->formSendSMS($result['notes_id'], $rulename, $forms_fields_search, $formalerts['user_roles'], $formalerts['userids'], $rules_id);
											}*/
										}
										
									break;
									
								}
								
							}
							
							
							
							
						}
					}
				}
				
				
				
				//var_dump($allnotesIds);
				//echo "<hr>";
				
				if($allnotesIds != null && $allnotesIds != ""){
					
					foreach($allnotesIds as $allnotesId){
						$this->formSendEmail($allnotesId['notes_id'], $allnotesId['rules_type'], $allnotesId['rules_value'], $allnotesId['user_roles'], $allnotesId['userids'], $allnotesId['rules_id']);
							
						if(in_array('1', $allnotesId['rule_action'])){
							$this->formSendSMS($allnotesId['notes_id'], $allnotesId['rules_type'], $allnotesId['rules_value'], $allnotesId['user_roles'], $allnotesId['userids'], $allnotesId['rules_id']);
						}
					}
					
					
					foreach($allnotesIds as $allnotesId){
						$sql3e = "UPDATE `" . DB_PREFIX . "notes` SET form_alert_send_email = '1' WHERE notes_id = '".$allnotesId['notes_id']."'";			
						$query = $this->db->query($sql3e);
					}
					
					if(in_array('1', $allnotesId['rule_action'])){
						foreach($allnotesIds as $allnotesId){
							$sql32e = "UPDATE `" . DB_PREFIX . "notes` SET form_alert_send_sms = '1' WHERE notes_id = '".$allnotesId['notes_id']."'";			
							$query = $this->db->query($sql32e);
						}
					}
				}
				
			}
		}
		
		
	
	}
	
	public function formSendSMS($notes_id, $rulename, $forms_fields_search, $user_roles, $userids, $rules_id){
		$sqlsnote = "SELECT * FROM `" . DB_PREFIX . "notes` where notes_id = '".$notes_id."' and form_alert_send_sms = '0' ";
			$query = $this->db->query($sqlsnote);
				
			$note_info = $query->row;
			if($note_info != null && $note_info != ""){
				$user_info = $this->model_user_user->getUserByUsername($note_info['user_id']);
				$facility = $this->model_facilities_facilities->getfacilities($note_info['facilities_id']);
				$country_info = $this->model_setting_country->getCountry($facility['country_id']);
				$zone_info = $this->model_setting_zone->getZone($facility['zone_id']);
				$facilityDetails['username'] = $note_info['user_id'];
				$facilityDetails['email'] = $user_info['email'];
				$facilityDetails['phone_number'] = $user_info['phone_number'];
				$facilityDetails['sms_number'] = $facility['sms_number'];
				$facilityDetails['facility'] = $facility['facility'];
				$facilityDetails['address'] = $facility['address'];
				$facilityDetails['location'] = $facility['location'];
				$facilityDetails['zipcode']= $facility['zipcode'];
				$facilityDetails['contry_name'] = $country_info['name'];
				$facilityDetails['zone_name'] = $zone_info['name'];
				$facilityDetails['href'] = $this->url->link('common/login', '', 'SSL');
				$facilityDetails['rules_name'] = $rulename;
				$facilityDetails['rules_type'] = 'Form Rule';
				$facilityDetails['rules_value'] = $forms_fields_search;
				
				
				$sqlsnot1e = "SELECT * FROM `" . DB_PREFIX . "createtask` where formrules_id = '".$rules_id."' and rules_task = '".$notes_id."'  ";
				$query1 = $this->db->query($sqlsnot1e);
					
				if($query1->row != null && $query1->row != ""){
					$note_info1 = $query1->row;
				}else{
					$note_info1 = '';
				}
				
				$message = "Form Rule Reminder \n";
				
				if($note_info1 != null && $note_info1 != ""){
					$message .= date('h:i A', strtotime($note_info1['task_time']))."\n";
				}else{
					$message .= date('h:i A', strtotime($note_info['notetime']))."\n";
				}
						
				
				$message .= $rulename .' | '.$forms_fields_search."\n";
				
				
				if($note_info1 != null && $note_info1 != ""){
					$message .= substr($note_info1['description'], 0, 150) .((strlen($note_info1['description']) > 150) ? '..' : '');
				}else{
					$message .= substr($note_info['notes_description'], 0, 150) .((strlen($note_info['notes_description']) > 150) ? '..' : '');
				}
				
				
				
				//$message .= $note_info['notes_description'];
				
				
					if($user_roles != null && $user_roles != ""){
						$user_roles1 = $user_roles;//explode(',',$result['user_roles']);
						
						$this->load->model('user/user_group');
						$this->load->model('user/user');
						$this->load->model('setting/tags');
						
						
						$this->load->model('api/smsapi');

						foreach ($user_roles1 as $user_role) {
							
							$urole = array();
							$urole['user_group_id'] = $user_role;
							$tusers = $this->model_user_user->getUsers($urole);
							
							if($tusers){
								foreach ($tusers as $tuser) {
									//var_dump($tuser);
									if($tuser['phone_number']){
										$sdata = array();
										$sdata['message'] = $message;
										$sdata['phone_number'] = $tuser['phone_number'];
										$sdata['facilities_id'] = $note_info['facilities_id'];	
										$response = $this->model_api_smsapi->sendsms($sdata);
										
										
									}
								}
							}
						}
					}
					
					if($userids != null && $userids != ""){
						$userids1 = $userids;//explode(',',$result['userids']);
						
						$this->load->model('user/user');
						$this->load->model('setting/tags');
						
						$this->load->model('api/smsapi');

						foreach ($userids1 as $userid) {
							$user_info = $this->model_user_user->getUserbyupdate($userid);
							
							if ($user_info) {
								if($user_info['phone_number']){
									
									$sdata = array();
									$sdata['message'] = $message;
									$sdata['phone_number'] = $user_info['phone_number'];
									$sdata['facilities_id'] = $note_info['facilities_id'];	
									$response = $this->model_api_smsapi->sendsms($sdata);
									
								}
							}
						}
						
					}
											
				
			}	
	}
	
	public function formSendEmail($notes_id, $rulename, $forms_fields_search, $user_roles, $userids, $rules_id){
		
			$sqlsnote = "SELECT * FROM `" . DB_PREFIX . "notes` where notes_id = '".$notes_id."' and form_alert_send_email = '0' ";
			$query = $this->db->query($sqlsnote);
				
			$note_info = $query->row;
			
			
			$sqlsnot1e = "SELECT * FROM `" . DB_PREFIX . "createtask` where formrules_id = '".$rules_id."' and rules_task = '".$notes_id."' ";
			$query1 = $this->db->query($sqlsnot1e);
				
			if($query1->row != null && $query1->row != ""){
				$note_info1 = $query1->row;
			}else{
				$note_info1 = '';
			}
			
			//var_dump($note_info1);
				
			if($note_info != null && $note_info != ""){
				$user_info = $this->model_user_user->getUserByUsername($note_info['user_id']);
				$facility = $this->model_facilities_facilities->getfacilities($note_info['facilities_id']);
				$country_info = $this->model_setting_country->getCountry($facility['country_id']);
				$zone_info = $this->model_setting_zone->getZone($facility['zone_id']);
				$facilityDetails['username'] = $note_info['user_id'];
				$facilityDetails['email'] = $user_info['email'];
				$facilityDetails['phone_number'] = $user_info['phone_number'];
				$facilityDetails['sms_number'] = $facility['sms_number'];
				$facilityDetails['facility'] = $facility['facility'];
				$facilityDetails['address'] = $facility['address'];
				$facilityDetails['location'] = $facility['location'];
				$facilityDetails['zipcode']= $facility['zipcode'];
				$facilityDetails['contry_name'] = $country_info['name'];
				$facilityDetails['zone_name'] = $zone_info['name'];
				$facilityDetails['href'] = $this->url->link('common/login', '', 'SSL');
				$facilityDetails['rules_name'] = $rulename;
				$facilityDetails['rules_type'] = '';
				$facilityDetails['rules_value'] = $forms_fields_search;
				
				
				$message33 = "";
				$message33 .= $this->sendEmailtemplate($note_info, $rulename, 'Alerts',$forms_fields_search, $facilityDetails, $note_info1);
				
				
				if($user_roles != null && $user_roles != ""){
					$user_roles1 = $user_roles;//explode(',',$result['user_roles']);
					
					$this->load->model('user/user_group');
					$this->load->model('user/user');
					$this->load->model('setting/tags');
					
					$useremailids = array();

					foreach ($user_roles1 as $user_role) {
						
						$urole = array();
						$urole['user_group_id'] = $user_role;
						$tusers = $this->model_user_user->getUsers($urole);
						
						if($tusers){
							foreach ($tusers as $tuser) {
								//var_dump($tuser);
								if($tuser['email']){
									$useremailids[] = $tuser['email'];
								}
							}
						}
					}
				}
				
				if($userids != null && $userids != ""){
					$userids1 = $userids;//explode(',',$result['userids']);
					
					$this->load->model('user/user');
					$this->load->model('setting/tags');

					
					foreach ($userids1 as $userid) {
						$user_info = $this->model_user_user->getUserbyupdate($userid);
						
						if ($user_info) {
							if($user_info['email']){
								$useremailids[] = $user_info['email'];
							}
						}
					}
					
				}
				
				$this->load->model('api/emailapi');
				
				
				$edata = array();
				$edata['message'] = $message33;
				$edata['subject'] = 'Form Rule Reminder';
				$edata['useremailids'] = $useremailids;
					
				$email_status = $this->model_api_emailapi->sendmail($edata);
				
			
			}
				
				
				
		
	}
	
	public function sendEmailtemplate($result, $ruleName, $ruleType, $rulevalue, $facilityData, $note_info1){
		$html = "";
		$html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Form Rule Reminder</title>

<style>
@media screen and (max-width:500px) {
   h6 {
        font-size: 12px !important;
    }
}
</style>
</head>
 
<body bgcolor="#FFFFFF" topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style=" -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none;width: 100%!important;height: 100%;padding: 0;margin: 0;font-family: Open Sans, sans-serif;">

<table class="head-wrap" style="width: 100%;background: #fff; border-spacing: 0;">
	<tr>
		<td></td>
		<td class="header container" align="" style="display: block!important;max-width: 600px!important;margin: 0 auto!important; clear: both!important;">
			

			<div class="content" style="padding: 15px;max-width: 600px;margin: 0 auto;display: block;padding-right: 0;padding-left: 0;">
				<table style="width: 100%;">
					<tr>
						<td><img src="'.HTTP_SERVER.'sites/view/digitalnotebook/stylesheet/email/logo.png" style="width: 100%;" /></td>
						<td align="right" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">Form Rule Reminder</h6></td>
					</tr>
				</table>
			</div>
			
		</td>
		<td></td>
	</tr>
</table>

<table class="body-wrap" bgcolor="" style="width: 100%;    border-spacing: 0;">
	<tr>
		<td></td>
		<td class="container" align="" bgcolor="#c1c1c1" style="display: block!important;max-width: 600px!important;margin: 0 auto!important; clear: both!important;">

			<div class="content" style="padding: 15px;max-width: 600px;margin: 0 auto;display: block; background: #c1c1c1;border-bottom: 2px solid #2c3742;">
				<table>
					<tr>
						<td>
							
							<h1 style="font-weight: 200;font-size: 30px;padding: 0;margin: 0;">Hello '.$facilityData['username'].'!</h1>
							<p class="lead" style="font-size: 14px;margin-bottom: 10px;font-weight: normal;line-height: 1.6;">This is an automated email generated by NoteActive '.$ruleName.'! Please review the details below for further information or actions:</p>
							
						</td>
					</tr>
				</table>
			</div>
			<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="'.$facilityData['href'].'">
						<img src="'.HTTP_SERVER.'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">What <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">'.$ruleType.'- '.$rulevalue.'</small></h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">';
							
							if($note_info1 != null && $note_info1 != ""){
								$html .= $note_info1['description'];
							}else{
								$html .= $result['notes_description'];
							}
						
							
							$html .= '</p>
						</td>
					</tr>
				</table>
			
			</div>
			<div class="content" style="max-width: 600px;margin: 0 auto;display: block;    background: #fa801b;">
			<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
				<tr>
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="'.$result['href'].'">
					<img src="'.HTTP_SERVER.'sites/view/digitalnotebook/stylesheet/email/when.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">When</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">';
						if($note_info1 != null && $note_info1 != ""){
							$html .= date('j, F Y', strtotime($note_info1['task_date'])).'&nbsp;'.date('h:i A', strtotime($note_info1['task_time']));
						}else{
							$html .= date('j, F Y', strtotime($result['date_added'])).'&nbsp;'.date('h:i A', strtotime($result['notetime']));
						}
						
						
						$html .= '</p>
					</td>
				</tr>
			</table></div>
			<div class="content" style="max-width: 600px;margin: 0 auto;display: block;    background: #fa801b;">
			<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
				<tr>
					<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="'.$result['href'].'">
					<img src="'.HTTP_SERVER.'sites/view/digitalnotebook/stylesheet/email/where.png" style="width:75px;" /></a></td>
					<td>
						<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">Where</h4>
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
						'.$facilityData['facility'].'&nbsp;'.$facilityData['address'].'&nbsp;'.$facilityData['location'].'&nbsp;'.$facilityData['zone_name'].'&nbsp;'.$facilityData['zipcode'].', '.$facilityData['contry_name'].'
						</p>
					</td>
				</tr>
			</table></div>
			

		</td>
		<td></td>
	</tr>
</table>

</body>
</html>';
return $html;
	}

	
	
} 
	

/*
$hostname = "166.62.28.137";
			$username = "power_bi_user";
			$password = "power_bi_user";
			$dbname = "power_bi_db";

			$connection = mysql_connect($hostname, $username, $password);
			var_dump($connection);
			echo mysql_error();
			mysql_select_db($dbname, $connection);
			
			//Setup our query
			echo $query = "SELECT * FROM ". DB_PREFIX."user ";
			 
			//Run the Query
			$result = mysql_query($query);
			 
			//If the query returned results, loop through
			// each result
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
*/