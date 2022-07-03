<?php  
class Controllersyndbsyndbmain extends Controller {

	public function index() {
		$this->load->model('syndb/syndb');
		$this->load->model('activity/activity');
		
		$this->load->model('customer/customer');
		
		$manual_link = $this->request->get['manual_link'];
		
		
		$this->load->model('facilities/facilities');
		$this->load->model('setting/timezone');
		
		
		if ($this->request->get['date_from'] != '' && $this->request->get['date_from'] != null) {
			$date_from = $this->request->get['date_from'];
			$startDate = $date_from.' 00:00:00';
		}
		
		if ($this->request->get['date_to'] != '' && $this->request->get['date_to'] != null) {
			$date_to = $this->request->get['date_to'];
			$endDate = $date_to.' 23:59:59';
		}
		
		try{
			
			if ($this->request->get['date_from'] != '' && $this->request->get['date_from'] != null) {
				echo $sql = "SELECT * FROM `" . DB_PREFIX . "notes` where `date_added` BETWEEN  '".$startDate."' AND  '".$endDate."' and notes_conut='0' ";
			}else{
				echo $sql = "SELECT * FROM `" . DB_PREFIX . "notes` where notes_conut='0' ";
			}
			
			$query = $this->db->query($sql);
		
			$notes = $query->rows;
			
			
			if($notes != null && $notes != ""){
				
				
				foreach($notes as $note){
					
					
					$notes_description = $note['notes_description']; 
					$servernotes_id = $note['notes_id'];
					
					echo $sql = "INSERT INTO `" . NEWDB_PREFIX . "notes` SET notes_id = '" . $note['notes_id'] . "', facilities_id = '" . $note['facilities_id'] . "', notes_description = '" . $this->db->escape($notes_description) . "', highlighter_id = '" . $note['highlighter_id'] . "', notes_pin = '" . $this->db->escape($note['notes_pin']) . "', notes_file = '" . $this->db->escape($note['notes_file']) . "', date_added = '" . $note['date_added'] . "', status = '".$note['status']."', user_id = '" . $this->db->escape($note['user_id']) . "', signature = '".$note['signature']."', signature_image = '" . $note['signature_image'] . "', notetime = '" . $note['notetime'] . "', note_date = '" . $note['note_date'] . "', text_color_cut = '".$note['text_color_cut']."',  text_color = '" .$note['text_color']. "',  strike_user_id = '".$this->db->escape($note['strike_user_id'])."', strike_date_added = '" .$note['strike_date_added']. "', strike_signature = '" .$note['strike_signature']. "', strike_signature_image = '" . $note['strike_signature_image'] . "', strike_pin = '" . $this->db->escape($note['strike_pin']) . "', global_utc_timezone = '" . $note['global_utc_timezone'] . "', keyword_file_url = '" . $note['keyword_file_url'] . "', highlighter_value = '" . $note['highlighter_value'] . "', keyword_file = '" . $note['keyword_file'] . "', taskadded = '" . $note['taskadded'] . "', task_time = '" . $note['task_time'] . "', assign_to = '" . $note['assign_to'] . "', emp_tag_id = '" . $note['emp_tag_id'] . "', notes_type = '" . $note['notes_type'] . "', checklist_status = '" . $note['checklist_status'] . "', snooze_time = '" . $note['snooze_time'] . "', snooze_dismiss = '" . $note['snooze_dismiss'] . "', send_sms = '" . $note['send_sms'] . "', send_email = '" . $note['send_email'] . "', notes_search_keword = '" . $note['notes_search_keword'] . "', unique_id = '" . $config_unique_id . "', strike_note_type = '" . $note['strike_note_type'] . "', audio_attach_url = '" . $note['audio_attach_url'] . "', task_type = '" . $note['task_type'] . "', tags_id = '" . $note['tags_id'] . "', update_date = '" . $note['update_date'] . "', medication_attach_url = '" . $note['medication_attach_url'] . "', is_private = '" . $note['is_private'] . "', is_private_strike = '" . $note['is_private_strike'] . "', assessment_id = '" . $note['assessment_id'] . "', review_notes = '" . $note['review_notes'] . "', share_notes = '" . $note['share_notes'] . "', rule_highlighter_task = '" . $note['rule_highlighter_task'] . "', rule_activenote_task = '" . $note['rule_activenote_task'] . "', rule_color_task = '" . $note['rule_color_task'] . "', rule_keyword_task = '" . $note['rule_keyword_task'] . "', is_offline = '" . $note['is_offline'] . "', notes_conut = '" . $note['notes_conut'] . "', tasktype = '" . $note['tasktype'] . "', visitor_log = '" . $note['visitor_log'] . "', task_id = '" . $note['task_id'] . "', task_date = '" . $note['task_date'] . "', parent_id = '" . $note['parent_id'] . "', end_perpetual_task = '" . $note['end_perpetual_task'] . "', recurrence = '" . $note['recurrence'] . "', customlistvalues_id = '" . $note['customlistvalues_id'] . "', generate_report = '" . $note['generate_report'] . "', is_android = '" . $note['is_android'] . "', is_census = '" . $note['is_census'] . "', is_tag = '" . $note['is_tag'] . "', form_type = '" . $note['form_type'] . "', tagstatus_id = '" . $note['tagstatus_id'] . "', task_group_by = '" . $note['task_group_by'] . "', end_task = '" . $note['end_task'] . "', form_snooze_dismiss = '" . $note['form_snooze_dismiss'] . "', form_send_sms = '" . $note['form_send_sms'] . "', form_send_email = '" . $note['form_send_email'] . "', form_snooze_time = '" . $note['form_snooze_time'] . "', form_create_task = '" . $note['form_create_task'] . "', form_alert_send_email = '" . $note['form_alert_send_email'] . "', form_alert_send_sms = '" . $note['form_alert_send_sms'] . "', is_archive = '" . $note['is_archive'] . "', phone_device_id = '" . $note['phone_device_id'] . "', original_task_time = '" . $note['original_task_time'] . "', is_forms = '" . $note['is_forms'] . "', is_reminder = '" . $note['is_reminder'] . "', form_trigger_snooze_dismiss = '" . $note['form_trigger_snooze_dismiss'] . "', user_file = '" . $note['user_file'] . "', is_user_face = '" . $note['is_user_face'] . "', is_approval_required_forms_id = '" . $note['is_approval_required_forms_id'] . "', is_casecount = '" . $note['is_casecount'] . "', device_unique_id = '" . $note['device_unique_id'] . "', sync_dashboard = '" . $note['sync_dashboard'] . "', strike_user_file = '" . $note['strike_user_file'] . "', strike_is_user_face = '" . $note['strike_is_user_face'] . "', linked_id = '" . $note['linked_id'] . "', parent_facilities_id = '" . $note['parent_facilities_id'] . "', task_form_id = '" . $note['task_form_id'] . "', shift_id = '" . $note['shift_id'] . "'
				
					ON DUPLICATE KEY UPDATE 
					
					facilities_id = '" . $note['facilities_id'] . "', notes_description = '" . $this->db->escape($notes_description) . "', highlighter_id = '" . $note['highlighter_id'] . "', notes_pin = '" . $note['notes_pin'] . "', notes_file = '" . $note['notes_file'] . "', date_added = '" . $note['date_added'] . "', status = '".$note['status']."', user_id = '" . $this->db->escape($note['user_id']) . "', signature = '".$note['signature']."', signature_image = '" . $note['signature_image'] . "', notetime = '" . $note['notetime'] . "', note_date = '" . $note['note_date'] . "', text_color_cut = '".$note['text_color_cut']."',  text_color = '" .$note['text_color']. "',  strike_user_id = '".$this->db->escape($note['strike_user_id'])."', strike_date_added = '" .$note['strike_date_added']. "', strike_signature = '" .$note['strike_signature']. "', strike_signature_image = '" . $note['strike_signature_image'] . "', strike_pin = '" . $note['strike_pin'] . "', global_utc_timezone = '" . $note['global_utc_timezone'] . "', keyword_file_url = '" . $note['keyword_file_url'] . "', highlighter_value = '" . $note['highlighter_value'] . "', keyword_file = '" . $note['keyword_file'] . "', taskadded = '" . $note['taskadded'] . "', task_time = '" . $note['task_time'] . "', assign_to = '" . $note['assign_to'] . "', emp_tag_id = '" . $note['emp_tag_id'] . "', notes_type = '" . $note['notes_type'] . "', checklist_status = '" . $note['checklist_status'] . "', snooze_time = '" . $note['snooze_time'] . "', snooze_dismiss = '" . $note['snooze_dismiss'] . "', send_sms = '" . $note['send_sms'] . "', send_email = '" . $note['send_email'] . "', notes_search_keword = '" . $note['notes_search_keword'] . "', unique_id = '" . $config_unique_id . "', strike_note_type = '" . $note['strike_note_type'] . "', audio_attach_url = '" . $note['audio_attach_url'] . "', task_type = '" . $note['task_type'] . "', tags_id = '" . $note['tags_id'] . "', update_date = '" . $note['update_date'] . "', medication_attach_url = '" . $note['medication_attach_url'] . "', is_private = '" . $note['is_private'] . "', is_private_strike = '" . $note['is_private_strike'] . "', assessment_id = '" . $note['assessment_id'] . "', review_notes = '" . $note['review_notes'] . "', share_notes = '" . $note['share_notes'] . "', rule_highlighter_task = '" . $note['rule_highlighter_task'] . "', rule_activenote_task = '" . $note['rule_activenote_task'] . "', rule_color_task = '" . $note['rule_color_task'] . "', rule_keyword_task = '" . $note['rule_keyword_task'] . "', is_offline = '" . $note['is_offline'] . "', notes_conut = '" . $note['notes_conut'] . "', tasktype = '" . $note['tasktype'] . "', visitor_log = '" . $note['visitor_log'] . "', task_id = '" . $note['task_id'] . "', task_date = '" . $note['task_date'] . "', parent_id = '" . $note['parent_id'] . "', end_perpetual_task = '" . $note['end_perpetual_task'] . "', recurrence = '" . $note['recurrence'] . "', customlistvalues_id = '" . $note['customlistvalues_id'] . "', generate_report = '" . $note['generate_report'] . "', is_android = '" . $note['is_android'] . "', is_census = '" . $note['is_census'] . "', is_tag = '" . $note['is_tag'] . "', form_type = '" . $note['form_type'] . "', tagstatus_id = '" . $note['tagstatus_id'] . "', task_group_by = '" . $note['task_group_by'] . "', end_task = '" . $note['end_task'] . "', form_snooze_dismiss = '" . $note['form_snooze_dismiss'] . "', form_send_sms = '" . $note['form_send_sms'] . "', form_send_email = '" . $note['form_send_email'] . "', form_snooze_time = '" . $note['form_snooze_time'] . "', form_create_task = '" . $note['form_create_task'] . "', form_alert_send_email = '" . $note['form_alert_send_email'] . "', form_alert_send_sms = '" . $note['form_alert_send_sms'] . "', is_archive = '" . $note['is_archive'] . "', phone_device_id = '" . $note['phone_device_id'] . "', original_task_time = '" . $note['original_task_time'] . "', is_forms = '" . $note['is_forms'] . "', is_reminder = '" . $note['is_reminder'] . "', form_trigger_snooze_dismiss = '" . $note['form_trigger_snooze_dismiss'] . "', user_file = '" . $note['user_file'] . "', is_user_face = '" . $note['is_user_face'] . "', is_approval_required_forms_id = '" . $note['is_approval_required_forms_id'] . "', is_casecount = '" . $note['is_casecount'] . "', device_unique_id = '" . $note['device_unique_id'] . "', sync_dashboard = '" . $note['sync_dashboard'] . "', strike_user_file = '" . $note['strike_user_file'] . "', strike_is_user_face = '" . $note['strike_is_user_face'] . "', linked_id = '" . $note['linked_id'] . "', parent_facilities_id = '" . $note['parent_facilities_id'] . "', task_form_id = '" . $note['task_form_id'] . "', shift_id = '" . $note['shift_id'] . "' ";
					
					
					$this->newdb->query($sql);
					
					$notes_id = $note['notes_id']; 
					
					echo "<hr>";
					
					$sql1 = "SELECT * FROM `" . DB_PREFIX . "notes_media` where `notes_id` ='".$servernotes_id."' ";
					$query1 = $this->db->query($sql1);
				
					$attachments = $query1->rows;
					
					
					if($attachments != null && $attachments != ""){
						foreach($attachments as $attachment){
							
						echo $sql = "INSERT INTO " . NEWDB_PREFIX . "notes_media SET notes_media_id = '" . $attachment['notes_media_id'] . "', notes_file = '" . $attachment['notes_file'] . "', notes_id = '" . $attachment['notes_id'] . "', deleted = '" . $attachment['deleted'] . "', status = '" . $attachment['status'] . "', notes_media_extention = '" . $attachment['notes_media_extention'] . "', media_user_id = '" . $this->db->escape($attachment['media_user_id']) . "', media_date_added = '" . $attachment['media_date_added'] . "', media_signature = '" . $attachment['media_signature'] . "', media_signature_image = '" . $attachment['media_signature_image'] . "', media_pin = '" . $attachment['media_pin'] . "', update_media = '" . $attachment['update_media'] . "', unique_id = '" . $config_unique_id . "', notes_type = '" . $attachment['notes_type'] . "', audio_attach_url = '" . $attachment['audio_attach_url'] . "', audio_attach_type = '" . $attachment['audio_attach_type'] . "', audio_upload_file = '" . $attachment['audio_upload_file'] . "', facilities_id = '" . $attachment['facilities_id'] . "', speech_name = '" . $attachment['speech_name'] . "', is_updated = '" . $attachment['is_updated'] . "' , phone_device_id = '" . $attachment['phone_device_id'] . "' , is_android = '" . $attachment['is_android'] . "' , sync_dashboard = '" . $attachment['sync_dashboard'] . "' , user_file = '" . $attachment['user_file'] . "' , is_user_face = '" . $attachment['is_user_face'] . "' 
						ON DUPLICATE KEY UPDATE 
						notes_file = '" . $attachment['notes_file'] . "', notes_id = '" . $attachment['notes_id'] . "', deleted = '" . $attachment['deleted'] . "', status = '" . $attachment['status'] . "', notes_media_extention = '" . $attachment['notes_media_extention'] . "', media_user_id = '" . $this->db->escape($attachment['media_user_id']) . "', media_date_added = '" . $attachment['media_date_added'] . "', media_signature = '" . $attachment['media_signature'] . "', media_signature_image = '" . $attachment['media_signature_image'] . "', media_pin = '" . $attachment['media_pin'] . "', update_media = '" . $attachment['update_media'] . "', unique_id = '" . $config_unique_id . "', notes_type = '" . $attachment['notes_type'] . "', audio_attach_url = '" . $attachment['audio_attach_url'] . "', audio_attach_type = '" . $attachment['audio_attach_type'] . "', audio_upload_file = '" . $attachment['audio_upload_file'] . "', facilities_id = '" . $attachment['facilities_id'] . "', speech_name = '" . $attachment['speech_name'] . "', is_updated = '" . $attachment['is_updated'] . "' , phone_device_id = '" . $attachment['phone_device_id'] . "' , is_android = '" . $attachment['is_android'] . "' , sync_dashboard = '" . $attachment['sync_dashboard'] . "' , user_file = '" . $attachment['user_file'] . "' , is_user_face = '" . $attachment['is_user_face'] . "'    ";
							$this->newdb->query($sql);
						}
						
					}
				
						echo "<hr>";
						
						$sql5 = "SELECT * FROM `" . DB_PREFIX . "notes_by_keyword` where `notes_id` ='".$servernotes_id."' ";
						$query5 = $this->db->query($sql5);
				
						$noteskeywords = $query5->rows;
						
						if($noteskeywords != null && $noteskeywords != ""){
							foreach($noteskeywords as $noteskeyword){
								
								echo $sql = "INSERT INTO " . NEWDB_PREFIX . "notes_by_keyword SET notes_by_keyword_id = '" . $noteskeyword['notes_by_keyword_id'] . "', notes_id = '" . $noteskeyword['notes_id'] . "', keyword_id = '" . $noteskeyword['keyword_id'] . "', keyword_name = '" . $this->db->escape($noteskeyword['keyword_name']) . "', keyword_file = '" . $noteskeyword['keyword_file'] . "', keyword_file_url = '" . $noteskeyword['keyword_file_url'] . "', keyword_status = '" . $noteskeyword['keyword_status'] . "', active_tag = '" . $noteskeyword['active_tag'] . "', facilities_id = '" . $noteskeyword['facilities_id'] . "', date_added = '" . $noteskeyword['date_added'] . "', unique_id = '" . $config_unique_id . "' , is_monitor_time = '" . $noteskeyword['is_monitor_time'] . "' , user_id = '" . $noteskeyword['user_id'] . "' , override_monitor_time_user_id = '" . $noteskeyword['override_monitor_time_user_id'] . "', sync_dashboard = '" . $noteskeyword['sync_dashboard'] . "', type = '" . $noteskeyword['type'] . "', comment_id = '" . $noteskeyword['comment_id'] . "'
								ON DUPLICATE KEY UPDATE 
								notes_id = '" . $noteskeyword['notes_id'] . "', keyword_id = '" . $noteskeyword['keyword_id'] . "', keyword_name = '" . $this->db->escape($noteskeyword['keyword_name']) . "', keyword_file = '" . $noteskeyword['keyword_file'] . "', keyword_file_url = '" . $noteskeyword['keyword_file_url'] . "', keyword_status = '" . $noteskeyword['keyword_status'] . "', active_tag = '" . $noteskeyword['active_tag'] . "', facilities_id = '" . $noteskeyword['facilities_id'] . "', date_added = '" . $noteskeyword['date_added'] . "', unique_id = '" . $config_unique_id . "', is_monitor_time = '" . $noteskeyword['is_monitor_time'] . "' , user_id = '" . $noteskeyword['user_id'] . "' , override_monitor_time_user_id = '" . $noteskeyword['override_monitor_time_user_id'] . "', sync_dashboard = '" . $noteskeyword['sync_dashboard'] . "', type = '" . $noteskeyword['type'] . "', comment_id = '" . $noteskeyword['comment_id'] . "'
								";
								$this->newdb->query($sql);
							}
							
						
						}
						
						
					$sqlc = "UPDATE `" . DB_PREFIX . "notes` SET  notes_conut='1' WHERE notes_id = '" . (int)$servernotes_id . "' ";
		
					$this->db->query($sqlc);
					
				} 
				
			}
			
			echo "Success";
			
		}catch(Exception $e){
		
		
		}
		
		 
	}
	
}