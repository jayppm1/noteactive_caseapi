<?php
class Controllerformformnotification extends Controller {
	private $error = array();
	public function index() {
		
		$this->load->model('facilities/online');
		$datafa = array();
		$datafa['username'] = $this->session->data['webuser_id'];
		$datafa['activationkey'] = $this->session->data['activationkey'];
		$datafa['facilities_id'] = $this->customer->getId();
		$datafa['ip'] = $this->request->server['REMOTE_ADDR'];
		
		$this->model_facilities_online->updatefacilitiesOnline2($datafa);
		
		
		$this->load->model('notes/notes');
		$this->load->model('notes/rules');
		$this->load->model('facilities/facilities');
		
		$this->load->model('setting/highlighter');
		$this->load->model('setting/country');
		$this->load->model('setting/zone');
		$this->load->model('setting/timezone');
		$this->load->model('facilities/facilities');
		$this->load->model('user/user');
		
		$this->load->model('createtask/createtask');
		$this->load->model('form/form');
		
		//require_once(DIR_SYSTEM . 'library/twilio-php-master/smsconfig.php');
		//require_once(DIR_SYSTEM . 'library/PHPMailer-master/PHPMailerAutoload.php');
		
		$this->load->model('api/emailapi');
		$this->load->model('api/smsapi');
		
		
		$data3 = array(
			'facilities_id' => $this->customer->getId(),
		);
		
		$facilities_id = $this->customer->getId();
		
		$config_noteform_status = $this->customer->isNoteform();
		
		if($config_noteform_status == '1'){
			$rules = $this->model_form_form->getRules($data3);
		}
		
		
		$json = array();
		$notesIds = array();
		$tnotesIds = array();
		$facilityDetails = array();
		
		$andRuleArray = array();
		$nrulesvalue = "";
		$rulename = "";
		$rulesvalue = "";
		$andrulesValues = array();
		$andrulesTaskValues = array();
		$andrulesActionValues = array();
		$andrulesActionValues2 = array();
		
		$notesIdsemail = array();
		$andRuleArrayemail = array();
		
		$notesIdssms = array();
		$andRuleArraysms = array();
		
		$notesIdstask = array();
		$andRuleArraytask = array();
		
		$rowModule = array();
		$rulename = '';
		
		if($rules){
			
			$timezone_name = $this->customer->isTimezone();
			date_default_timezone_set($timezone_name);
			$searchdate =  date('m-d-Y');
			
			$current_date = date('Y-m-d', strtotime('now'));
			$current_time = date('H:i', strtotime('now'));
			
			$facility = $this->model_facilities_facilities->getfacilities($facilities_id);
			
			if($facility['web_audio_file'] !=NULL && $facility['web_audio_file'] !=""){
				$facility_web_audio_file = HTTP_SERVER .'image/ringtone/'.$facility['web_audio_file']; 
			}else{
				$facility_web_audio_file = '';
			}
					
			$country_info = $this->model_setting_country->getCountry($facility['country_id']);
			$zone_info = $this->model_setting_zone->getZone($facility['zone_id']);
			
			foreach($rules as $rule){
				
				$allnotesIds = array();
				
				$rulename = $rule['rules_name'];
				
				//var_dump($rule['forms_id']);
				/* Trigger */
				if($rule['rules_operation'] == '1'){
					
					
					foreach($rule['rules_module'] as $rules_module){
						
						//var_dump($rules_module);
						//echo "<hr>";
						
						$forms_fields_search = '';
						
						$sqls = "select DISTINCT n.*,f.custom_form_type,f.forms_id,f.tags_id,f.design_forms from `" . DB_PREFIX . "notes` n ";
						$sqls .= "left JOIN " . DB_PREFIX . "forms f on f.notes_id=n.notes_id  "; 
		
						$sqls .= 'where 1 = 1 ';
						
						
						//var_dump($rules_module['forms_fields_values']);
						if($rules_module['forms_fields_values'] != null && $rules_module['forms_fields_values'] != ""){
							if (is_array($rules_module['forms_fields_values'])){
								$i=0;
								$sqls .= " and ( ";
								foreach($rules_module['forms_fields_values'] as $key2=>$b){
									
									$forms_fields = explode("##",$rules_module['forms_fields_value']);
									
									//var_dump($forms_fields);
									
									$fkeyword = $forms_fields[1].':'.$b;
									
									
									if($i != '0'){
										$sqls .= ' or ';
									}
									
									$sqls .= "  LOWER(f.rules_form_description) LIKE '%".strtolower($fkeyword)."%' ";
									$i++;
									
									$forms_fields_search .= $forms_fields[2] .' | '.$b .' ';
								}
								
								$sqls .= " ) ";
								
								$i=0;
							}
							
						}
						
						
						if($rules_module['forms_fields_search'] != null && $rules_module['forms_fields_search'] != ""){
							
							$forms_fields = explode("##",$rules_module['forms_fields_value']);
							
							$fkeyword = $forms_fields[1].':'.$rules_module['forms_fields_search'];
							
							$sqls .= " and ( LOWER(f.rules_form_description) LIKE '%".strtolower($fkeyword)."%' ) ";
							
							
							$forms_fields_search = $forms_fields[2] .' | '. $rules_module['forms_fields_search'];
						}
						
						if ($facility ['task_facilities_ids'] != null && $facility ['task_facilities_ids'] != "") {
							$ddss [] = $facility ['task_facilities_ids'];
							$ddss [] = $facilities_id;
							$sssssdd = implode ( ",", $ddss );
							$faculities_ids = $sssssdd;
							$sqls .= " and n.facilities_id in  (" . $faculities_ids . ") ";
						} else {
							$sqls .= " and n.facilities_id = '".$facility['facilities_id']."'";
						}
						//$sqls .= " and n.facilities_id = '".$facility['facilities_id']."'";
						$sqls .= " and f.custom_form_type = '".$rule['forms_id']."'";
						$sqls .= " and f.is_discharge = '0'";
						$sqls .= " and n.form_trigger_snooze_dismiss != '2' ";
										
						$date = str_replace('-', '/', $searchdate);
						$res = explode("/", $date);
						$changedDate = $res[2]."-".$res[0]."-".$res[1];
										
						$startDate = $changedDate;
						$endDate = $changedDate;
						
						$sqls .= " and ( n.`date_added` BETWEEN '".$startDate." 00:00:00 ' AND '".$endDate." 23:59:59' or f.`date_added` BETWEEN '".$startDate." 00:00:00 ' AND '".$endDate." 23:59:59' ) ";
						
						$sqls .= " and n.status = '1' ORDER BY n.notetime DESC  ";
						
						//var_dump($forms_fields_search);
						//echo $sqls;
						//echo "<hr>";
								
						$query = $this->db->query($sqls);
										
						if ($query->num_rows) {
							//var_dump($query->rows);
							//echo "<hr>";
							foreach($query->rows as $result){
								
								$date_added = $result['date_added'];
								//var_dump($form_due_date_after);
								
								$newdate = date('Y-m-d',strtotime($date_added));
								
								$allnotesIds[] = array(
									'notes_id' => $result['notes_id'],
									'rules_type' => $rule['rules_name'],
									'rules_value' => $forms_fields_search,
									'user_roles' => '',
									'userids' => '',
									'date_added' => $date_added,
									'form_due_date_after' => '',
									'newdate' => $newdate,
									'new_time' => '',
									'form_due_date' => '',
									'rules_operation' => $rule['rules_operation'],
								);
								
								
								if(in_array('4', $rule['rule_action'])){
									
									//var_dump($rule['rule_action_content']['task_random_id']);
									//echo "<hr>";
									
									$thestime6 = date('H:i:s');
									//var_dump($thestime6);
									$snooze_time7 = 60;
									$stime8 = date("h:i A",strtotime("+".$snooze_time7." minutes",strtotime($thestime6)));
									
									$sqls23 = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "createtask` where form_due_date = '".$rules_module['form_due_date']."' and form_due_date_after = '".$rules_module['form_due_date_after']."' and rules_task = '".$result['notes_id']."' and form_rules_operation = '".$rule['rules_operation']."' ";
									
									$query4 = $this->db->query($sqls23);
									
									
									if($query4->row['total'] == 0){
										
										$addtask = array();
										
										$snooze_time71 = 0;
										$thestime61 = date('H:i:s');
										
										$taskTime = date("H:i:s",strtotime("+".$snooze_time71." minutes",strtotime($thestime61)));
										
										if($date_wise_task_time == '1'){
											$taskTime1 = $newdate;
											
											$thestime61 = date('H:i:s', strtotime($taskTime1));
											//var_dump($thestime6);
											$snooze_time71 = 60;
											$stime81 = date("h:i A",strtotime("+".$snooze_time71." minutes",strtotime($thestime61)));
											
										}else{
											$taskTime1 = $taskTime;
											$stime81 = $stime8;
										}
										
										if($date_wise_task == '1'){
											$taskDate = date('m-d-Y', strtotime($newdate));
											$end_recurrence_date = date('m-d-Y', strtotime($newdate));
										}else{
											$taskDate = date('m-d-Y', strtotime($result['date_added']));
											$end_recurrence_date = date('m-d-Y', strtotime($result['date_added']));
										}
										
										$addtask['taskDate'] = $taskDate;
										$addtask['end_recurrence_date'] = $end_recurrence_date;
										$addtask['recurrence'] = $rule['rule_action_content']['recurrence'];
										$addtask['recurnce_week'] = $rule['rule_action_content']['recurnce_week'];
										$addtask['recurnce_hrly'] = $rule['rule_action_content']['recurnce_hrly'];
										$addtask['recurnce_month'] = $rule['rule_action_content']['recurnce_month'];
										$addtask['recurnce_day'] = $rule['rule_action_content']['recurnce_day'];
										$addtask['taskTime'] = $taskTime1; //date('H:i:s');
										$addtask['endtime'] = $stime81;
										$addtask['description'] = $forms_fields_search .' | '.$rule['rule_action_content']['description'].' '.$result['notes_description'];
										
										if($rule['rule_action_content']['assign_to']){
											$addtask['assignto'] = $rule['rule_action_content']['assign_to'];
										}else{
											$addtask['assignto'] = $result['user_id'];
										}
																		
										$addtask['facilities_id'] = $facilities_id;
										$addtask['task_form_id'] = $rule['rule_action_content']['task_form_id'];
										
																						
										$addtask['pickup_facilities_id'] = $rule['rule_action_content']['pickup_facilities_id'];
										$addtask['pickup_locations_address'] = $rule['rule_action_content']['pickup_locations_address'];
										$addtask['pickup_locations_time'] = $rule['rule_action_content']['pickup_locations_time'];
																						
										$addtask['dropoff_facilities_id'] = $rule['rule_action_content']['dropoff_facilities_id'];
										$addtask['dropoff_locations_address'] = $rule['rule_action_content']['dropoff_locations_address'];
										$addtask['dropoff_locations_time'] = $rule['rule_action_content']['dropoff_locations_time'];
										
										$addtask['tasktype'] = $rule['rule_action_content']['tasktype'];
										$addtask['numChecklist'] = $rule['rule_action_content']['numChecklist'];
										$addtask['task_alert'] = $rule['rule_action_content']['task_alert'];
										$addtask['alert_type_sms'] = $rule['rule_action_content']['alert_type_sms'];
										$addtask['alert_type_notification'] = $rule['rule_action_content']['alert_type_notification'];
										$addtask['alert_type_email'] = $rule['rule_action_content']['alert_type_email'];
										$addtask['rules_task'] = $result['notes_id'];
										
										
										$addtask['recurnce_hrly_recurnce'] = $rule['rule_action_content']['recurnce_hrly_recurnce'];
										$addtask['daily_endtime'] = $rule['rule_action_content']['daily_endtime'];
										
										if($rule['rule_action_content']['daily_times'] != null && $rule['rule_action_content']['daily_times'] !=""){
											$addtask['daily_times'] =  explode(',',$rule['rule_action_content']['daily_times']);
										}
										
										if($rule['rule_action_content']['medication_tags'] != null && $rule['rule_action_content']['medication_tags'] !=""){
											$addtask['medication_tags'] =  explode(',',$rule['rule_action_content']['medication_tags']);
										
										
											$aa  = urldecode($rule['rule_action_content']['tags_medication_details_ids']); 
											$aa1  = unserialize($aa); 
															
											$tags_medication_details_ids = array();
											foreach($aa1 as $key=>$mresult){
												$tags_medication_details_ids[$key] = $mresult;
											}
											$addtask['tags_medication_details_ids'] = $tags_medication_details_ids;
										
										}
										
										
										//var_dump($result);
										
										/*if($rule['rule_action_content']['emp_tag_id']){
											$addtask['emp_tag_id'] = $rule['rule_action_content']['emp_tag_id'];
										}else */
										if($result['tags_id'] > 0 ){
											$addtask['emp_tag_id'] = $result['tags_id'];
										}else{
											
											//$form_info = $this->model_form_form->getFormDatas($result['forms_id']);	
											
											///$design_forms = unserialize($form_info['design_forms']);
											//var_dump($design_forms);
											//$tags_id = $design_forms[0][0]['tags_id'];
											
											$formdata = unserialize($result['design_forms']);
								
											foreach($formdata as $design_forms){
												foreach($design_forms as $key=>$design_form){
													foreach($design_form as $key2=>$b){
														
														$arrss = explode("_1_", $key2);
														//var_dump($arrss);
														//echo "<hr>";
														if($arrss[1] == 'tags_id'){
															//var_dump($design_form[$arrss[0].'_1_'.$arrss[1]]);
															//var_dump($design_form[$arrss[0]]);
															//echo "<hr>";
															if($design_form[$arrss[0]] != null && $design_form[$arrss[0]] != ""){
																if($design_form[$arrss[0].'_1_'.$arrss[1]] != null && $design_form[$arrss[0].'_1_'.$arrss[1]] != ""){
																	
																	//var_dump($design_form[$arrss[0].'_1_'.$arrss[1]]);
																	
																	$tags_id =  $design_form[$arrss[0].'_1_'.$arrss[1]];
																}
															}
														}
														
														if($arrss[1] == 'tags_ids'){
															//var_dump($design_form[$arrss[0].'_1_'.$arrss[1]]);
															if(!empty($design_form[$arrss[0].'_1_'.$arrss[1]])){
																foreach($design_form[$arrss[0].'_1_'.$arrss[1]] as $idst){
																	
																	//var_dump($idst);
																	//$tags_id = $idst;
																}
															}
															//echo "<hr>";
														}
													}
												}
											}
											
											$addtask['emp_tag_id'] = $tags_id;
										}
										if($rule['rule_action_content']['transport_tags'] != null && $rule['rule_action_content']['transport_tags'] !=""){
											//$addtask['transport_tags'] = explode(',',$rule['rule_action_content']['transport_tags']);
										}
										$tagss = array();
										if($rule['rule_action_content']['transport_tags'] != null && $rule['rule_action_content']['transport_tags'] !=""){
											$tagss[] = explode(',',$rule['rule_action_content']['transport_tags']);
										}
										
										if($result['emp_tag_id'] == '1'){
											$alltags = $this->model_notes_notes->getNotesTagsmultiple ( $result['notes_id']);
											foreach($alltags as $alltag){
												$tagss[] = $alltag['tags_id'];
											}
										}
										
										$tagss1 = array_unique($tagss);
										
										$addtask['transport_tags'] = $tagss1;
												
										$addtask['recurnce_hrly_perpetual'] = $rule['rule_action_content']['recurnce_hrly_perpetual'];
										$addtask['completion_alert'] = $rule['rule_action_content']['completion_alert'];
										$addtask['completion_alert_type_sms'] = $rule['rule_action_content']['completion_alert_type_sms'];
										$addtask['completion_alert_type_email'] = $rule['rule_action_content']['completion_alert_type_email'];
										
										if($rule['rule_action_content']['user_roles'] != null && $rule['rule_action_content']['user_roles'] !=""){
											$addtask['user_roles'] =  explode(',',$rule['rule_action_content']['user_roles']);
										}
										
										if($rule['rule_action_content']['userids'] != null && $rule['rule_action_content']['userids'] !=""){
											$addtask['userids'] =  explode(',',$rule['rule_action_content']['userids']);
										}
										$addtask['task_status'] = $rule['rule_action_content']['task_status'];
										
										$addtask['visitation_tag_id'] = $rule['rule_action_content']['visitation_tag_id'];
										
										if($rule['rule_action_content']['visitation_tags'] != null && $rule['rule_action_content']['visitation_tags'] !=""){
											$addtask['visitation_tags'] =  explode(',',$rule['rule_action_content']['visitation_tags']);
										}
										$addtask['visitation_start_facilities_id'] = $rule['rule_action_content']['visitation_start_facilities_id'];
										$addtask['visitation_start_address'] = $rule['rule_action_content']['visitation_start_address'];
										$addtask['visitation_start_time'] = $rule['rule_action_content']['visitation_start_time'];
										$addtask['visitation_appoitment_facilities_id'] = $rule['rule_action_content']['visitation_appoitment_facilities_id'];
										$addtask['visitation_appoitment_address'] = $rule['rule_action_content']['visitation_appoitment_address'];
										$addtask['visitation_appoitment_time'] = $rule['rule_action_content']['visitation_appoitment_time'];
										$addtask['complete_endtime'] = $rule['rule_action_content']['complete_endtime'];
										
										if($rule['rule_action_content']['completed_times'] != null && $rule['rule_action_content']['completed_times'] !=""){
											$addtask['completed_times'] =  explode(',',$rule['rule_action_content']['completed_times']);
										}
										$addtask['completed_alert'] = $rule['rule_action_content']['completed_alert'];
										$addtask['completed_late_alert'] = $rule['rule_action_content']['completed_late_alert'];
										$addtask['incomplete_alert'] = $rule['rule_action_content']['incomplete_alert'];
										$addtask['deleted_alert'] = $rule['rule_action_content']['deleted_alert'];
										$addtask['attachement_form'] = $rule['rule_action_content']['attachement_form'];
										$addtask['tasktype_form_id'] = $rule['rule_action_content']['tasktype_form_id'];
										
										$addtask['reminder_alert'] = $rule['reminder_alert'];
										if($rule['reminderminus'] != null && $rule['reminderminus'] !=""){
											$addtask['reminderminus'] =  explode(',',$rule['reminderminus']);
										}
										
										if($rule['reminderplus'] != null && $rule['reminderplus'] !=""){
											$addtask['reminderplus'] =  explode(',',$rule['reminderplus']);
										}
										
										$addtask['assign_to_type'] = $rule['assign_to_type'];
										if($rule['user_assign_to'] != null && $rule['user_assign_to'] !=""){
											$addtask['assign_to'] =  explode(',',$rule['user_assign_to']);
										}
										if($rule['user_role_assign_ids'] != null && $rule['user_role_assign_ids'] !=""){
											$addtask['user_role_assign_ids'] =  explode(',',$rule['user_role_assign_ids']);
										}
										
										//var_dump($addtask);
										//echo "<hr>";
										
										
										//$sqlw = "update `" . DB_PREFIX . "notes` set form_snooze_dismiss = '2', form_create_task = '1' where notes_id ='".$result['notes_id']."'";
										//$this->db->query($sqlw); 
										
											
										$task_id = $this->model_createtask_createtask->addcreatetask($addtask, $facilities_id);
										
										$fdat6a= array();
										$fdat6a['formrules_id'] = $rule['rules_id'];
										$fdat6a['form_due_date'] = $rules_module['form_due_date'];
										$fdat6a['form_due_date_after'] = $rules_module['form_due_date_after'];
										$fdat6a['task_id'] = $task_id;
										$fdat6a['form_rules_operation'] = $rule['rules_operation'];
										$this->model_createtask_createtask->updateformruletask($fdat6a);
										
										
										
									}
								}
								
								
							}
						}
					}
				}
				
				/* TASK */
				
				
				if($rule['rules_operation'] == '2'){
					
					
					$forms_fields_search = 'Task';
					foreach($rule['onschedule_rules_module'] as $onschedule_rules_module){
						$sqls = "select DISTINCT n.*,f.custom_form_type,f.forms_id,f.tags_id from `" . DB_PREFIX . "notes` n ";
						$sqls .= "left JOIN " . DB_PREFIX . "forms f on f.notes_id=n.notes_id  "; 
						$sqls .= 'where 1 = 1 ';
						
						if ($facility ['task_facilities_ids'] != null && $facility ['task_facilities_ids'] != "") {
							$ddss [] = $facility ['task_facilities_ids'];
							$ddss [] = $facilities_id;
							$sssssdd = implode ( ",", $ddss );
							$faculities_ids = $sssssdd;
							$sqls .= " and n.facilities_id in  (" . $faculities_ids . ") ";
						} else {
							$sqls .= " and n.facilities_id = '".$facility['facilities_id']."'";
						}
						
						//$sqls .= " and n.facilities_id = '".$facility['facilities_id']."'";
						$sqls .= " and f.custom_form_type = '".$rule['forms_id']."'";
						$sqls .= " and n.form_snooze_dismiss != '2' ";
										
						$date = str_replace('-', '/', $searchdate);
						$res = explode("/", $date);
						$changedDate = $res[2]."-".$res[0]."-".$res[1];
						$startDate = $changedDate;
						$endDate = $changedDate;
						$sqls .= " and ( n.`date_added` BETWEEN '".$startDate." 00:00:00 ' AND '".$endDate." 23:59:59' or f.`date_added` BETWEEN '".$startDate." 00:00:00 ' AND '".$endDate." 23:59:59' ) ";
						$sqls .= " and n.status = '1' ORDER BY n.notetime DESC  ";
						
						
						$query = $this->db->query($sqls);
						//var_dump($query->num_rows);
						
						if ($query->num_rows) {
							
							foreach($query->rows as $result){
								$date_added = $result['date_added'];
								$newdate = date('Y-m-d',strtotime($date_added));
								$form_due_date_after = $onschedule_rules_module['form_due_date_after'] ; 
								//var_dump($result['notes_id']);
								//var_dump($form_due_date_after);
								
								switch ($onschedule_rules_module['form_due_date']){
									case 'Month' : 
												
										$newdate = date("Y-m-d",strtotime(date("Y-m-d H:i", strtotime($date_added)) . " +".$form_due_date_after." month")); 
										$date_wise_task = '1';
										
										
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
											'form_due_date' => $onschedule_rules_module['form_due_date'],
											'onschedule_action' => $onschedule_rules_module['onschedule_action'],
											'rules_operation' => $rule['rules_operation'],
										);
												
									break;	

									case 'Days' : 
												
										$newdate = date("Y-m-d",strtotime(date("Y-m-d H:i", strtotime($date_added)) . " +".$form_due_date_after." day")); 
										
										$date_wise_task = '1';
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
												'form_due_date' => $onschedule_rules_module['form_due_date'],
												'onschedule_action' => $onschedule_rules_module['onschedule_action'],
												'rules_operation' => $rule['rules_operation'],
											);
										
									break;
									
									case 'Date' : 
										if($form_due_date_after == $current_date){
											
										}
									break;
									case 'Hours' : 
										$newdate = date('H:i',strtotime('+'.$form_due_date_after.' hour',strtotime($date_added)));
										
										$date_wise_task_time= '1';
										$allnotesIds[] = array(
												'notes_id' => $result['notes_id'],
												'rules_type' => $rule['rules_name'],
												'rules_value' => $forms_fields_search,
												'user_roles' => $formalerts['user_roles'],
												'userids' => $formalerts['userids'],
												'date_added' => $date_added,
												'form_due_date_after' => $form_due_date_after,
												'newdate' => '',
												'new_time' => $newdate,
												'form_due_date' => $onschedule_rules_module['form_due_date'],
												'onschedule_action' => $onschedule_rules_module['onschedule_action'],
												'rules_operation' => $rule['rules_operation'],
											);
										
									break;
									case 'Minutes' : 
										$newdate = date('H:i',strtotime('+'.$form_due_date_after.' minutes',strtotime($date_added)));
										
										$date_wise_task_time= '1';
										
										$allnotesIds[] = array(
												'notes_id' => $result['notes_id'],
												'rules_type' => $rule['rules_name'],
												'rules_value' => $forms_fields_search,
												'user_roles' => $formalerts['user_roles'],
												'userids' => $formalerts['userids'],
												'date_added' => $date_added,
												'form_due_date_after' => $form_due_date_after,
												'newdate' => '',
												'new_time' => $newdate,
												'form_due_date' => $onschedule_rules_module['form_due_date'],
												'onschedule_action' => $onschedule_rules_module['onschedule_action'],
												'rules_operation' => $rule['rules_operation'],
											);
										
									break;
									case 'is submitted' : 
										$newdate = date('Y-m-d',strtotime($date_added));
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
												'form_due_date' => $onschedule_rules_module['form_due_date'],
												'onschedule_action' => $onschedule_rules_module['onschedule_action'],
												'rules_operation' => $rule['rules_operation'],
												
											);
											
									break;
									
									case 'is updated' : 
										$newdate = date('Y-m-d',strtotime($date_added));
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
												'form_due_date' => $onschedule_rules_module['form_due_date'],
												'onschedule_action' => $onschedule_rules_module['onschedule_action'],
												'rules_operation' => $rule['rules_operation'],
											);
										
									break;
									
								}
								
								
								
								if($onschedule_rules_module['onschedule_action'] == '4'){
									
									$thestime6 = date('H:i:s');
									//var_dump($thestime6);
									$snooze_time7 = 60;
									$stime8 = date("h:i A",strtotime("+".$snooze_time7." minutes",strtotime($thestime6)));
									
									$sqls23 = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "createtask` where task_random_id = '".$onschedule_rules_module['task_random_id']."' and rules_task = '".$result['notes_id']."' and form_rules_operation = '".$rule['rules_operation']."' ";
									$query4 = $this->db->query($sqls23);
									
									if($query4->row['total'] == 0){
										
										$addtask = array();
										
										$snooze_time71 = 0;
										$thestime61 = date('H:i:s');
										
										$taskTime = date("H:i:s",strtotime("+".$snooze_time71." minutes",strtotime($thestime61)));
										
										if($date_wise_task_time == '1'){
											$taskTime1 = $taskTime;
											
											$thestime61 = date('H:i:s', strtotime($taskTime1));
											//var_dump($thestime6);
											$snooze_time71 = 60;
											$stime81 = date("h:i A",strtotime("+".$snooze_time71." minutes",strtotime($thestime61)));
											
										}else{
											$taskTime1 = $taskTime;
											$stime81 = $stime8;
										}
										
										if($date_wise_task == '1'){
											$taskDate = date('m-d-Y', strtotime($newdate));
											$end_recurrence_date = date('m-d-Y', strtotime($newdate));
										}else{
											$taskDate = date('m-d-Y', strtotime($result['date_added']));
											$end_recurrence_date = date('m-d-Y', strtotime($result['date_added']));
										}
										
										$addtask['taskDate'] = $taskDate;
										
										if($rule['rules_operation_recurrence'] == '1'){
											$addtask['end_recurrence_date'] = $end_recurrence_date;
										}
										
										if($rule['rules_operation_recurrence'] == '3'){
											
											if($rule['end_recurrence_date'] != null && $rule['end_recurrence_date'] != "0000-00-00 00:00:00"){
												$addtask['end_recurrence_date'] = date('m-d-Y', strtotime($rule['end_recurrence_date']));
											}else{
												$addtask['end_recurrence_date'] = $end_recurrence_date;
											}
											
											
										}
										
										$addtask['recurrence'] = $onschedule_rules_module['recurrence'];
										$addtask['recurnce_week'] = $onschedule_rules_module['recurnce_week'];
										$addtask['recurnce_hrly'] = $onschedule_rules_module['recurnce_hrly'];
										$addtask['recurnce_month'] = $onschedule_rules_module['recurnce_month'];
										$addtask['recurnce_day'] = $onschedule_rules_module['recurnce_day'];
										$addtask['taskTime'] = $taskTime1; //date('H:i:s');
										$addtask['endtime'] = $stime81;
										$addtask['description'] = $onschedule_rules_module['description'].' '.$result['notes_description'];
										
										if($onschedule_rules_module['assign_to']){
											$addtask['assignto'] = $onschedule_rules_module['assign_to'];
										}else{
											$addtask['assignto'] = $result['user_id'];
										}
																		
										$addtask['facilities_id'] = $facilities_id;
										$addtask['task_form_id'] = $onschedule_rules_module['task_form_id'];
										if($onschedule_rules_module['transport_tags'] != null && $onschedule_rules_module['transport_tags'] !=""){
											//$addtask['transport_tags'] = explode(',',$onschedule_rules_module['transport_tags']);
										}
										
										$tagss = array();
										if($onschedule_rules_module['transport_tags'] != null && $onschedule_rules_module['transport_tags'] !=""){
											$tagss[] = explode(',',$onschedule_rules_module['transport_tags']);
										}
										
										if($result['emp_tag_id'] == '1'){
											$alltags = $this->model_notes_notes->getNotesTagsmultiple ( $result['notes_id']);
											foreach($alltags as $alltag){
												$tagss[] = $alltag['tags_id'];
											}
										}
										
										$tagss1 = array_unique($tagss);
										
										$addtask['transport_tags'] = $tagss1;
																						
										$addtask['pickup_facilities_id'] = $onschedule_rules_module['pickup_facilities_id'];
										$addtask['pickup_locations_address'] = $onschedule_rules_module['pickup_locations_address'];
										$addtask['pickup_locations_time'] = $onschedule_rules_module['pickup_locations_time'];
																						
										$addtask['dropoff_facilities_id'] = $onschedule_rules_module['dropoff_facilities_id'];
										$addtask['dropoff_locations_address'] = $onschedule_rules_module['dropoff_locations_address'];
										$addtask['dropoff_locations_time'] = $onschedule_rules_module['dropoff_locations_time'];
										
										$addtask['tasktype'] = $onschedule_rules_module['tasktype'];
										$addtask['numChecklist'] = $onschedule_rules_module['numChecklist'];
										$addtask['task_alert'] = $onschedule_rules_module['task_alert'];
										$addtask['alert_type_sms'] = $onschedule_rules_module['alert_type_sms'];
										$addtask['alert_type_notification'] = $onschedule_rules_module['alert_type_notification'];
										$addtask['alert_type_email'] = $onschedule_rules_module['alert_type_email'];
										$addtask['rules_task'] = $result['notes_id'];
										
										
										$addtask['recurnce_hrly_recurnce'] = $onschedule_rules_module['recurnce_hrly_recurnce'];
										$addtask['daily_endtime'] = $onschedule_rules_module['daily_endtime'];
										
										if($onschedule_rules_module['daily_times'] != null && $onschedule_rules_module['daily_times'] !=""){
											$addtask['daily_times'] =  explode(',',$onschedule_rules_module['daily_times']);
										}
										
										if($onschedule_rules_module['medication_tags'] != null && $onschedule_rules_module['medication_tags'] !=""){
											$addtask['medication_tags'] =  explode(',',$onschedule_rules_module['medication_tags']);
										
										
											$aa  = urldecode($onschedule_rules_module['tags_medication_details_ids']); 
											$aa1  = unserialize($aa); 
															
											$tags_medication_details_ids = array();
											foreach($aa1 as $key=>$mresult){
												$tags_medication_details_ids[$key] = $mresult;
											}
											$addtask['tags_medication_details_ids'] = $tags_medication_details_ids;
										
										}
										
										
										
										if($onschedule_rules_module['emp_tag_id']){
											$addtask['emp_tag_id'] = $onschedule_rules_module['emp_tag_id'];
										}elseif($result['tags_id'] > 0 ){
											$addtask['emp_tag_id'] = $result['tags_id'];
										}else{
											
											/*$form_info = $this->model_form_form->getFormDatas($result['forms_id']);	
											
											$design_forms = unserialize($form_info['design_forms']);
											//var_dump($design_forms);
											$tags_id = $design_forms[0][0]['tags_id'];
											*/
											
											$formdata = unserialize($result['design_forms']);
								
											foreach($formdata as $design_forms){
												foreach($design_forms as $key=>$design_form){
													foreach($design_form as $key2=>$b){
														
														$arrss = explode("_1_", $key2);
														//var_dump($arrss);
														//echo "<hr>";
														if($arrss[1] == 'tags_id'){
															//var_dump($design_form[$arrss[0].'_1_'.$arrss[1]]);
															//var_dump($design_form[$arrss[0]]);
															//echo "<hr>";
															if($design_form[$arrss[0]] != null && $design_form[$arrss[0]] != ""){
																if($design_form[$arrss[0].'_1_'.$arrss[1]] != null && $design_form[$arrss[0].'_1_'.$arrss[1]] != ""){
																	
																	//var_dump($design_form[$arrss[0].'_1_'.$arrss[1]]);
																	
																	$tags_id =  $design_form[$arrss[0].'_1_'.$arrss[1]];
																}
															}
														}
														
														if($arrss[1] == 'tags_ids'){
															//var_dump($design_form[$arrss[0].'_1_'.$arrss[1]]);
															if(!empty($design_form[$arrss[0].'_1_'.$arrss[1]])){
																foreach($design_form[$arrss[0].'_1_'.$arrss[1]] as $idst){
																	
																	//var_dump($idst);
																	$tags_id = $idst;
																}
															}
															//echo "<hr>";
														}
													}
												}
											}
											$addtask['emp_tag_id'] = $tags_id;
										}
										
										
										
										$addtask['recurnce_hrly_perpetual'] = $onschedule_rules_module['recurnce_hrly_perpetual'];
										$addtask['completion_alert'] = $onschedule_rules_module['completion_alert'];
										$addtask['completion_alert_type_sms'] = $onschedule_rules_module['completion_alert_type_sms'];
										$addtask['completion_alert_type_email'] = $onschedule_rules_module['completion_alert_type_email'];
										
										if($onschedule_rules_module['user_roles'] != null && $onschedule_rules_module['user_roles'] !=""){
											$addtask['user_roles'] =  explode(',',$onschedule_rules_module['user_roles']);
										}
										
										if($onschedule_rules_module['userids'] != null && $onschedule_rules_module['userids'] !=""){
											$addtask['userids'] =  explode(',',$onschedule_rules_module['userids']);
										}
										$addtask['task_status'] = $onschedule_rules_module['task_status'];
										
										$addtask['visitation_tag_id'] = $onschedule_rules_module['visitation_tag_id'];
										
										if($onschedule_rules_module['visitation_tags'] != null && $onschedule_rules_module['visitation_tags'] !=""){
											$addtask['visitation_tags'] =  explode(',',$onschedule_rules_module['visitation_tags']);
										}
										$addtask['visitation_start_facilities_id'] = $onschedule_rules_module['visitation_start_facilities_id'];
										$addtask['visitation_start_address'] = $onschedule_rules_module['visitation_start_address'];
										$addtask['visitation_start_time'] = $onschedule_rules_module['visitation_start_time'];
										$addtask['visitation_appoitment_facilities_id'] = $onschedule_rules_module['visitation_appoitment_facilities_id'];
										$addtask['visitation_appoitment_address'] = $onschedule_rules_module['visitation_appoitment_address'];
										$addtask['visitation_appoitment_time'] = $onschedule_rules_module['visitation_appoitment_time'];
										$addtask['complete_endtime'] = $onschedule_rules_module['complete_endtime'];
										
										if($onschedule_rules_module['completed_times'] != null && $onschedule_rules_module['completed_times'] !=""){
											$addtask['completed_times'] =  explode(',',$onschedule_rules_module['completed_times']);
										}
										$addtask['completed_alert'] = $onschedule_rules_module['completed_alert'];
										$addtask['completed_late_alert'] = $onschedule_rules_module['completed_late_alert'];
										$addtask['incomplete_alert'] = $onschedule_rules_module['incomplete_alert'];
										$addtask['deleted_alert'] = $onschedule_rules_module['deleted_alert'];
										$addtask['attachement_form'] = $onschedule_rules_module['attachement_form'];
										$addtask['tasktype_form_id'] = $onschedule_rules_module['tasktype_form_id'];
										
										$addtask['reminder_alert'] = $onschedule_rules_module['reminder_alert'];
										if($onschedule_rules_module['reminderminus'] != null && $onschedule_rules_module['reminderminus'] !=""){
											$addtask['reminderminus'] =  explode(',',$onschedule_rules_module['reminderminus']);
										}
										
										if($onschedule_rules_module['reminderplus'] != null && $onschedule_rules_module['reminderplus'] !=""){
											$addtask['reminderplus'] =  explode(',',$onschedule_rules_module['reminderplus']);
										}
										$addtask['assign_to_type'] = $onschedule_rules_module['assign_to_type'];
										if($onschedule_rules_module['user_assign_to'] != null && $onschedule_rules_module['user_assign_to'] !=""){
											$addtask['assign_to'] =  explode(',',$onschedule_rules_module['user_assign_to']);
										}
										if($onschedule_rules_module['user_role_assign_ids'] != null && $onschedule_rules_module['user_role_assign_ids'] !=""){
											$addtask['user_role_assign_ids'] =  explode(',',$onschedule_rules_module['user_role_assign_ids']);
										}
										
										//$sqlw = "update `" . DB_PREFIX . "notes` set form_snooze_dismiss = '2', form_create_task = '1' where notes_id ='".$result['notes_id']."'";
										//$this->db->query($sqlw); 
										
											
										$task_id = $this->model_createtask_createtask->addcreatetask($addtask, $facilities_id);
										
										$fdat6a= array();
										$fdat6a['formrules_id'] = $rule['rules_id'];
										$fdat6a['task_random_id'] = $onschedule_rules_module['task_random_id'];
										$fdat6a['task_id'] = $task_id;
										$fdat6a['rules_operation_recurrence'] = $rule['rules_operation_recurrence'];
										$fdat6a['form_rules_operation'] = $rule['rules_operation'];
										$this->model_createtask_createtask->updateformruletask2($fdat6a);
										
										
										
										
										
									}
								}
								
								
							}
						}
					}
				}
			
			
			
				/**************** ACTION *********************/
				
				//var_dump($allnotesIds);
				//echo "<hr>";
				
				
				
				
				if($allnotesIds != null && $allnotesIds != ""){
					
					if(in_array('3', $rule['rule_action'])){
						foreach($allnotesIds as $allnotesId){
							$notesIds[] = $allnotesId['notes_id'];
							$andRuleArray[] = $rule['rules_name'] .' '.$allnotesId['rules_value'];
						}
					}
					
					
					
					if(in_array('4', $rule['rule_action'])){
						foreach($allnotesIds as $allnotesId){
							
							if($allnotesId['rules_operation'] == '1'){
								$this->model_notes_notes->updatenotesrule($allnotesId['notes_id']);
							}
							
							if($allnotesId['rules_operation'] == '2'){
								$this->model_notes_notes->updatenotesruletask($allnotesId['notes_id']);
							}
							
						}
					}
					
					if($onschedule_rules_module['onschedule_action'] == '4'){
						foreach($allnotesIds as $allnotesId){
							if($allnotesId['rules_operation'] == '1'){
								$this->model_notes_notes->updatenotesrule($allnotesId['notes_id']);
							}
							
							if($allnotesId['rules_operation'] == '2'){
								$this->model_notes_notes->updatenotesruletask($allnotesId['notes_id']);
							}
							
						}
					}
					
					
					
					if(in_array('1', $rule['rule_action'])){
						
						foreach($allnotesIds as $allnotesId){
							
							if($allnotesId['onschedule_action'] != '4'){
								
							$note_info = $this->model_notes_notes->getnotes_by_form($allnotesId['notes_id']);
							
							
							if ($note_info['notes_id'] != null && $note_info['notes_id'] != "") {
								$message = "Rules Created \n";
								$message .= date('h:i A', strtotime($note_info['notetime']))."\n";
								$message .= $rule['rules_name'] .'-'.$allnotesId['rules_type'].'-'.$allnotesId['rules_value']."\n";
								$message .= substr($note_info['notes_description'], 0, 150) .((strlen($note_info['notes_description']) > 150) ? '..' : '');
								
								//$user_info = $this->model_user_user->getUserByUsername($note_info['user_id']);
								$user_info = $this->model_user_user->getUserByUsernamebynotes($note_info['user_id'], $note_info['facilities_id']);
								
								if($user_info['phone_number'] != null && $user_info['phone_number'] != '0'){
									$phone_number = $user_info['phone_number'];
								}
								
								$sql3e = "UPDATE `" . DB_PREFIX . "notes` SET form_send_sms = '1' WHERE notes_id = '".$allnotesId['notes_id']."'";			
								$query = $this->db->query($sql3e);
								
								
								$sdata = array();
								$sdata['message'] = $message;
								$sdata['phone_number'] = $phone_number;
								$sdata['facilities_id'] = $facilities_id;
									
								$response = $this->model_api_smsapi->sendsms($sdata);
								
								
								
								if($rule['rule_action_content']['auser_roles'] != null && $rule['rule_action_content']['auser_roles'] != ""){
									
									$user_roles1 = $rule['rule_action_content']['auser_roles'];
									
									foreach ($user_roles1 as $user_role) {
										$urole = array();
										$urole['user_group_id'] = $user_role;
										$tusers = $this->model_user_user->getUsers($urole);
										
										if($tusers){
											foreach ($tusers as $tuser) {
												if($tuser['phone_number'] != null && $tuser['phone_number'] != ""){
													$sdata = array();
													$sdata['message'] = $message;
													$sdata['phone_number'] = $tuser['phone_number'];
													$sdata['facilities_id'] = $facilities_id;
														
													$response = $this->model_api_smsapi->sendsms($sdata);
													
												}
											}
										}
									}
									
								}
								
								if($rule['rule_action_content']['auserids'] != null && $rule['rule_action_content']['auserids'] != ""){
									$userids1 = $rule['rule_action_content']['auserids'];
			
									foreach ($userids1 as $userid) {
										$user_info = $this->model_user_user->getUserbyupdate($userid);
										if ($user_info) {
											if($user_info['phone_number'] != 0){
												$sdata = array();
												$sdata['message'] = $message;
												$sdata['phone_number'] = $user_info['phone_number'];
												$sdata['facilities_id'] = $facilities_id;
													
												$response = $this->model_api_smsapi->sendsms($sdata);
												
												
											}
										}
									}
									
								}
								
								
								
							}
							}
						}
						
					}
					
					if(in_array('2', $rule['rule_action'])){
						foreach($allnotesIds as $allnotesId){
							if($allnotesId['onschedule_action'] != '4'){
								
							$note_info = $this->model_notes_notes->getnotes_by_form2($allnotesId['notes_id']);
							
							
							if ($note_info['notes_id'] != null && $note_info['notes_id'] != "") {
								
								//$user_info = $this->model_user_user->getUserByUsername($note_info['user_id']);
								$user_info = $this->model_user_user->getUserByUsernamebynotes($note_info['user_id'], $note_info['facilities_id']);
								
								$facilityDetails['username'] = $result['user_id'];
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
								$facilityDetails['rules_name'] = $rule['rules_name'];
								$facilityDetails['rules_type'] = $allnotesId['rules_type'];
								$facilityDetails['rules_value'] = $allnotesId['rules_value'];
								
								
								$message33 = "";
																
								$message33 .= $this->sendEmailtemplate($note_info, $rule['rules_name'], $allnotesId['rules_type'], $allnotesId['rules_value'], $facilityDetails);
								
								$useremailids = array();
								
								$sql3e = "UPDATE `" . DB_PREFIX . "notes` SET form_send_email = '1' WHERE notes_id = '".$allnotesId['notes_id']."'";			
								$query = $this->db->query($sql3e);
								
								if($rule['rule_action_content']['auser_roles'] != null && $rule['rule_action_content']['auser_roles'] != ""){
													
										$user_roles1 = $rule['rule_action_content']['auser_roles'];

										foreach ($user_roles1 as $user_role) {
											$urole = array();
											$urole['user_group_id'] = $user_role;
											$tusers = $this->model_user_user->getUsers($urole);
											
											if($tusers){
												foreach ($tusers as $tuser) {
													if($tuser['email'] != null && $tuser['email'] != ""){
														
														$useremailids[] = $tuser['email'];
													}
												}
											}
										}
										
									}
									
									if($rule['rule_action_content']['auserids'] != null && $rule['rule_action_content']['auserids'] != ""){
										$userids1 = $rule['rule_action_content']['auserids'];
				
										foreach ($userids1 as $userid) {
											$user_info = $this->model_user_user->getUserbyupdate($userid);
											if ($user_info) {
												if($user_info['email']){
													$useremailids[] = $user_info['email'];
												}
											}
										}
										
									}
									
									
									if($user_info['email'] != null && $user_info['email'] != ""){
										$user_email = $user_info['email'];
									}
								
								/*$edata = array();
								$edata['message'] = $message33;
								$edata['subject'] = 'This is an Automated Alert Email.';
								$edata['useremailids'] = $useremailids;
								$edata['user_email'] = $user_email;*/

								$edata = array();
								$edata['message'] = $message33;
								$edata['subject'] = 'This is an Automated Alert Email.';
                               
                                $edata['notes_description'] = $note_info['notes_description'];
								$edata['useremailids'] = $useremailids;
								$edata['user_email'] = $user_email;
                                $edata['username'] = $result['user_id'];
								$edata['email'] = $user_info['email'];
								$edata['phone_number'] = $user_info['phone_number'];
								$edata['sms_number'] = $facility['sms_number'];
								$edata['facility'] = $facility['facility'];
								$edata['address'] = $facility['address'];
								$edata['location'] = $facility['location'];
								$edata['zipcode']= $facility['zipcode'];
								$edata['contry_name'] = $country_info['name'];
								$edata['zone_name'] = $zone_info['name'];
								$edata['href'] = $this->url->link('common/login', '', 'SSL');
								$edata['rules_name'] = $rule['rules_name'];
								$edata['rules_type'] = $allnotesId['rules_type'];
								$edata['rules_value'] = $allnotesId['rules_value'];
								$edata['who_user'] = $result['user_id'];
								//$edata['when_date'] = date("Y-M-d H:i:s",strtotime($notes_info['note_date']));
								$edata['type'] = '14';	
								$edata['href'] = $notes_info['href'];
                                $edata['date_added'] = $notes_info['date_added'];
                                $edata['notetime'] = $notes_info['notetime'];								
								//$email_status = $this->model_api_emailapi->createMails($edata);	
									
								$email_status = $this->model_api_emailapi->sendmail($edata);
								
								
								
									
								
							}
							}
						}
						
					}
					
					
					/*
					if(in_array('4', $rule['rule_action'])){
						
						foreach($allnotesIds as $allnotesId){
							//$tnotesIds[] = $allnotesId['notes_id'];
							
							if($allnotesId['onschedule_action'] != '4'){
							
							if($allnotesId['newdate'] != null && $allnotesId['newdate'] != ""){
								$taskDate = date('m-d-Y', strtotime($allnotesId['newdate']));
								$end_recurrence_date = $taskDate;
								$date_wise_task = '1';
							}else{
								$taskDate = $rule['rule_action_content']['taskDate'];
								$end_recurrence_date = $rule['rule_action_content']['end_recurrence_date'];
								$date_wise_task = '2';
							}
							if($allnotesId['new_time'] != null && $allnotesId['new_time'] != ""){
								$taskTime = date('H:i:s', strtotime($allnotesId['new_time']));
								$date_wise_task_time = '1';
							}else{
								$taskTime = $rule['rule_action_content']['taskTime'];
								$date_wise_task_time = '2';
							}
							
							//var_dump($taskTime);
							//var_dump($rule['rule_action_content']['taskTime']);
							
							$rowModule['date_wise_task'] = $date_wise_task;
							$rowModule['date_wise_task_time'] = $date_wise_task_time;
							$rowModule['taskDate'] = $taskDate;
							$rowModule['recurrence'] = $rule['rule_action_content']['recurrence'];
							$rowModule['recurnce_week'] = $rule['rule_action_content']['recurnce_week'];
							$rowModule['recurnce_hrly'] = $rule['rule_action_content']['recurnce_hrly'];
							$rowModule['recurnce_month'] = $rule['rule_action_content']['recurnce_month'];
							$rowModule['recurnce_day'] = $rule['rule_action_content']['recurnce_day'];
							$rowModule['end_recurrence_date'] = $end_recurrence_date;
							$rowModule['taskTime'] = $taskTime;
							$rowModule['endtime'] = $rule['rule_action_content']['endtime'];
							$rowModule['tasktype'] = $rule['rule_action_content']['tasktype'];
							$rowModule['numChecklist'] = $rule['rule_action_content']['numChecklist'];
							$rowModule['task_alert'] = $rule['rule_action_content']['task_alert'];
							$rowModule['alert_type_sms'] = $rule['rule_action_content']['alert_type_sms'];
							$rowModule['alert_type_notification'] = $rule['rule_action_content']['alert_type_notification'];
							$rowModule['alert_type_email'] = $rule['rule_action_content']['alert_type_email'];
							$rowModule['description'] = $rule['rule_action_content']['description'] .' | '. $allnotesId['rules_value'];
							
							$rowModule['assignto'] = $rule['rule_action_content']['assign_to'];
							$rowModule['facilities_id'] = $rule['rule_action_content']['facilities_id'];
							$rowModule['task_form_id'] = $rule['rule_action_content']['task_form_id'];
							$rowModule['transport_tags'] = $rule['rule_action_content']['transport_tags'];
							$rowModule['pickup_facilities_id'] = $rule['rule_action_content']['pickup_facilities_id'];
							$rowModule['pickup_locations_address'] = $rule['rule_action_content']['pickup_locations_address'];
							$rowModule['pickup_locations_time'] = $rule['rule_action_content']['pickup_locations_time'];
							$rowModule['dropoff_facilities_id'] = $rule['rule_action_content']['dropoff_facilities_id'];
							$rowModule['dropoff_locations_address'] = $rule['rule_action_content']['dropoff_locations_address'];
							$rowModule['dropoff_locations_time'] = $rule['rule_action_content']['dropoff_locations_time'];
							
							
							$rowModule['recurnce_hrly_recurnce'] = $rule['rule_action_content']['recurnce_hrly_recurnce'];
							$rowModule['daily_endtime'] = $rule['rule_action_content']['daily_endtime'];
							$rowModule['daily_times'] = $rule['rule_action_content']['daily_times'];
							$rowModule['medication_tags'] = $rule['rule_action_content']['medication_tags'];
							$rowModule['tags_medication_details_ids'] = $rule['rule_action_content']['tags_medication_details_ids'];
							$rowModule['emp_tag_id'] = $rule['rule_action_content']['emp_tag_id'];
							$rowModule['recurnce_hrly_perpetual'] = $rule['rule_action_content']['recurnce_hrly_perpetual'];
							$rowModule['completion_alert'] = $rule['rule_action_content']['completion_alert'];
							$rowModule['completion_alert_type_sms'] = $rule['rule_action_content']['completion_alert_type_sms'];
							$rowModule['completion_alert_type_email'] = $rule['rule_action_content']['completion_alert_type_email'];
							$rowModule['user_roles'] = $rule['rule_action_content']['user_roles'];
							$rowModule['userids'] = $rule['rule_action_content']['userids'];
							$rowModule['task_status'] = $rule['rule_action_content']['task_status'];
							$rowModule['visitation_tag_id'] = $rule['rule_action_content']['visitation_tag_id'];
							$rowModule['visitation_tags'] = $rule['rule_action_content']['visitation_tags'];
							$rowModule['visitation_start_facilities_id'] = $rule['rule_action_content']['visitation_start_facilities_id'];
							$rowModule['visitation_start_address'] = $rule['rule_action_content']['visitation_start_address'];
							$rowModule['visitation_start_time'] = $rule['rule_action_content']['visitation_start_time'];
							$rowModule['visitation_appoitment_facilities_id'] = $rule['rule_action_content']['visitation_appoitment_facilities_id'];
							$rowModule['visitation_appoitment_address'] = $rule['rule_action_content']['visitation_appoitment_address'];
							$rowModule['visitation_appoitment_time'] = $rule['rule_action_content']['visitation_appoitment_time'];
							$rowModule['complete_endtime'] = $rule['rule_action_content']['complete_endtime'];
							$rowModule['completed_times'] = $rule['rule_action_content']['completed_times'];
							
							$rowModule['completed_alert'] = $rule['rule_action_content']['completed_alert'];
							$rowModule['completed_late_alert'] = $rule['rule_action_content']['completed_late_alert'];
							$rowModule['incomplete_alert'] = $rule['rule_action_content']['incomplete_alert'];
							$rowModule['deleted_alert'] = $rule['rule_action_content']['deleted_alert'];
							$rowModule['attachement_form'] = $rule['rule_action_content']['attachement_form'];
							$rowModule['tasktype_form_id'] = $rule['rule_action_content']['tasktype_form_id'];
							$rowModule['rules_id'] = $rule['rules_id'];
							$rowModule['task_random_id'] = $rule['rule_action_content']['task_random_id'];
							
							
							$thestime6 = date('H:i:s');
							//var_dump($thestime6);
							$snooze_time7 = 60;
							$stime8 = date("h:i A",strtotime("+".$snooze_time7." minutes",strtotime($thestime6)));
							
							$sqls23 = "SELECT * FROM `" . DB_PREFIX . "createtask` where task_random_id = '".$rule['rule_action_content']['task_random_id']."' ";
							$query4 = $this->db->query($sqls23);
							if($query4->num_rows == 0){
								$addtask = array();
								
								$snooze_time71 = 0;
								$thestime61 = date('H:i:s');
								
								$taskTime = date("H:i:s",strtotime("+".$snooze_time71." minutes",strtotime($thestime61)));
								
								if($rowModule['date_wise_task_time'] == '1'){
									$taskTime1 = $rowModule['taskTime'];
									
									$thestime61 = date('H:i:s', strtotime($taskTime1));
									//var_dump($thestime6);
									$snooze_time71 = 60;
									$stime81 = date("h:i A",strtotime("+".$snooze_time71." minutes",strtotime($thestime61)));
									
									$end_recurrence_date = $rowModule['end_recurrence_date'];
								}else{
									$taskTime1 = $taskTime;
									$stime81 = $stime8;
								}
								
								if($rowModule['date_wise_task'] == '1'){
									$taskDate = $rowModule['taskDate'];
									$end_recurrence_date = $rowModule['end_recurrence_date'];
								}else{
									$taskDate = date('m-d-Y', strtotime($tresult['date_added']));
									$end_recurrence_date = date('m-d-Y', strtotime($tresult['date_added']));
								}
								
								$addtask['taskDate'] = $taskDate;
								$addtask['end_recurrence_date'] = $end_recurrence_date;
								$addtask['recurrence'] = $rowModule['recurrence'];
								$addtask['recurnce_week'] = $rowModule['recurnce_week'];
								$addtask['recurnce_hrly'] = $rowModule['recurnce_hrly'];
								$addtask['recurnce_month'] = $rowModule['recurnce_month'];
								$addtask['recurnce_day'] = $rowModule['recurnce_day'];
								$addtask['taskTime'] = $taskTime1; //date('H:i:s');
								$addtask['endtime'] = $stime81;
								$addtask['description'] = $rowModule['description'].' '.$tresult['notes_description'];
								
								if($rowModule['assign_to']){
									$addtask['assignto'] = $rowModule['assign_to'];
								}else{
									$addtask['assignto'] = $result['user_id'];
								}
																
								$addtask['facilities_id'] = $rowModule['facilities_id'];
								$addtask['task_form_id'] = $rowModule['task_form_id'];
								if($rowModule['transport_tags'] != null && $rowModule['transport_tags'] !=""){
									$addtask['transport_tags'] = explode(',',$rowModule['transport_tags']);
								}
																				
								$addtask['pickup_facilities_id'] = $rowModule['pickup_facilities_id'];
								$addtask['pickup_locations_address'] = $rowModule['pickup_locations_address'];
								$addtask['pickup_locations_time'] = $rowModule['pickup_locations_time'];
																				
								$addtask['dropoff_facilities_id'] = $rowModule['dropoff_facilities_id'];
								$addtask['dropoff_locations_address'] = $rowModule['dropoff_locations_address'];
								$addtask['dropoff_locations_time'] = $rowModule['dropoff_locations_time'];
								
								$addtask['tasktype'] = $rowModule['tasktype'];
								$addtask['numChecklist'] = $rowModule['numChecklist'];
								$addtask['task_alert'] = $rowModule['task_alert'];
								$addtask['alert_type_sms'] = $rowModule['alert_type_sms'];
								$addtask['alert_type_notification'] = $rowModule['alert_type_notification'];
								$addtask['alert_type_email'] = $rowModule['alert_type_email'];
								$addtask['rules_task'] = $tresult['notes_id'];
								
								
								$addtask['recurnce_hrly_recurnce'] = $rowModule['recurnce_hrly_recurnce'];
								$addtask['daily_endtime'] = $rowModule['daily_endtime'];
								
								if($rowModule['daily_times'] != null && $rowModule['daily_times'] !=""){
									$addtask['daily_times'] =  explode(',',$rowModule['daily_times']);
								}
								
								if($rowModule['medication_tags'] != null && $rowModule['medication_tags'] !=""){
									$addtask['medication_tags'] =  explode(',',$rowModule['medication_tags']);
								
								
									$aa  = urldecode($rowModule['tags_medication_details_ids']); 
									$aa1  = unserialize($aa); 
													
									$tags_medication_details_ids = array();
									foreach($aa1 as $key=>$mresult){
										$tags_medication_details_ids[$key] = $mresult;
									}
									$addtask['tags_medication_details_ids'] = $tags_medication_details_ids;
								
								}
								
								$addtask['emp_tag_id'] = $rowModule['emp_tag_id'];
								
								$addtask['recurnce_hrly_perpetual'] = $rowModule['recurnce_hrly_perpetual'];
								$addtask['completion_alert'] = $rowModule['completion_alert'];
								$addtask['completion_alert_type_sms'] = $rowModule['completion_alert_type_sms'];
								$addtask['completion_alert_type_email'] = $rowModule['completion_alert_type_email'];
								
								if($rowModule['user_roles'] != null && $rowModule['user_roles'] !=""){
									$addtask['user_roles'] =  explode(',',$rowModule['user_roles']);
								}
								
								if($rowModule['userids'] != null && $rowModule['userids'] !=""){
									$addtask['userids'] =  explode(',',$rowModule['userids']);
								}
								$addtask['task_status'] = $rowModule['task_status'];
								
								$addtask['visitation_tag_id'] = $rowModule['visitation_tag_id'];
								
								if($rowModule['visitation_tags'] != null && $rowModule['visitation_tags'] !=""){
									$addtask['visitation_tags'] =  explode(',',$rowModule['visitation_tags']);
								}
								$addtask['visitation_start_facilities_id'] = $rowModule['visitation_start_facilities_id'];
								$addtask['visitation_start_address'] = $rowModule['visitation_start_address'];
								$addtask['visitation_start_time'] = $rowModule['visitation_start_time'];
								$addtask['visitation_appoitment_facilities_id'] = $rowModule['visitation_appoitment_facilities_id'];
								$addtask['visitation_appoitment_address'] = $rowModule['visitation_appoitment_address'];
								$addtask['visitation_appoitment_time'] = $rowModule['visitation_appoitment_time'];
								$addtask['complete_endtime'] = $rowModule['complete_endtime'];
								
								if($rowModule['completed_times'] != null && $rowModule['completed_times'] !=""){
									$addtask['completed_times'] =  explode(',',$rowModule['completed_times']);
								}
								$addtask['completed_alert'] = $rowModule['completed_alert'];
								$addtask['completed_late_alert'] = $rowModule['completed_late_alert'];
								$addtask['incomplete_alert'] = $rowModule['incomplete_alert'];
								$addtask['deleted_alert'] = $rowModule['deleted_alert'];
								$addtask['attachement_form'] = $rowModule['attachement_form'];
								$addtask['tasktype_form_id'] = $rowModule['tasktype_form_id'];
								
								
								
								
								
								$sqlw = "update `" . DB_PREFIX . "notes` set form_create_task = '1' where notes_id ='".$tresult['notes_id']."'";
								$this->db->query($sqlw); 
								
									
								$task_id = $this->model_createtask_createtask->addcreatetask($addtask, $facilities_id);
								$sqlw2 = "update `" . DB_PREFIX . "createtask` set formrules_id = '".$rowModule['rules_id']."', task_random_id = '".$rowModule['task_random_id']."' where id ='".$task_id."'";
								$this->db->query($sqlw2); 
								
								
								$rowModule = array();
								
							}
							}	
						}
					}*/
					
					if(in_array('5', $rule['rule_action'])){
						
						foreach($allnotesIds as $allnotesId){
							if( $rule['rule_action_content']['highlighter_id'] != null &&  $rule['rule_action_content']['highlighter_id'] != ""){
								$update_date = date('Y-m-d H:i:s', strtotime('now')); 
								$this->model_notes_notes->updateNoteHigh($allnotesId['notes_id'], $rule['rule_action_content']['highlighter_id'], $update_date);
							}
						}
						
					}
					
					if(in_array('6', $rule['rule_action'])){
						foreach($allnotesIds as $allnotesId){
							if( $rule['rule_action_content']['color_id'] != null &&  $rule['rule_action_content']['color_id'] != ""){
								$update_date = date('Y-m-d H:i:s', strtotime('now')); 
								$this->model_notes_notes->updateNoteColor($allnotesId['notes_id'], $rule['rule_action_content']['color_id'], $update_date);
							}
							
						}
						
					}
						
				}
			
			
			}
		}
		
		
		//var_dump($rowModule);
		//echo "<hr>";
		//var_dump($notesIdsemail);
		//echo "<hr>";
		//var_dump($tnotesIds);
		
		
		
		$notesIdssms = array_unique($notesIdssms);
		if($notesIdssms != null && $notesIdssms != ""){
			foreach($notesIdssms as $notes_id){
				/*
				$sqlsnote = "SELECT * FROM `" . DB_PREFIX . "notes` where notes_id = '".$notes_id."' and form_send_sms = '0' ";
				$query = $this->db->query($sqlsnote);
					
				$note_info = $query->row;
				*/
				$note_info = $this->model_notes_notes->getnotes_by_form($notes_id);
							
							
				if ($note_info['notes_id'] != null && $note_info['notes_id'] != "") {
					
					//$user_info = $this->model_user_user->getUserByUsername($note_info['user_id']);
					$user_info = $this->model_user_user->getUserByUsernamebynotes($note_info['user_id'], $note_info['facilities_id']);
					$facility = $this->model_facilities_facilities->getfacilities($note_info['facilities_id']);
					$country_info = $this->model_setting_country->getCountry($facility['country_id']);
					
					$message = "Form Rule Created \n";
					$message .= date('h:i A', strtotime($note_info['notetime']))."\n";
					$message .= $rulename .'-Form Rule-'.$andRuleArraysms[$note_info['notes_id']]."\n";
					$message .= $note_info['notes_description'];
					
					if($user_info['phone_number'] != null && $user_info['phone_number'] != '0'){
						$phone_number = $user_info['phone_number'];
					}
					
					$sql3e = "UPDATE `" . DB_PREFIX . "notes` SET form_send_sms = '1' WHERE notes_id = '".$note_info['notes_id']."'";			
					$query = $this->db->query($sql3e);	
						
					$sdata = array();
					$sdata['message'] = $message;
					$sdata['phone_number'] = $phone_number;
					$sdata['facilities_id'] = $facilities_id;
						
					$response = $this->model_api_smsapi->sendsms($sdata);
															
					
				}	
			}
		}
		
		
		
		/****EMAIL CODE ****/
		$notesIdsemail = array_unique($notesIdsemail);
		//var_dump($notesIdsemail);
		if($notesIdsemail != null && $notesIdsemail != ""){
			foreach($notesIdsemail as $notes_id){
				/*
				$sqlsnote = "SELECT * FROM `" . DB_PREFIX . "notes` where notes_id = '".$notes_id."' and form_send_email = '0' ";
				$query = $this->db->query($sqlsnote);
					
				$note_info = $query->row;
				*/
				$note_info = $this->model_notes_notes->getnotes_by_form2($notes_id);
							
							
				if ($note_info['notes_id'] != null && $note_info['notes_id'] != "") {
				
				
				
					//$user_info = $this->model_user_user->getUserByUsername($note_info['user_id']);
					$user_info = $this->model_user_user->getUserByUsernamebynotes($note_info['user_id'], $note_info['facilities_id']);
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
					$facilityDetails['rules_value'] = $andRuleArrayemail[$note_info['notes_id']];
					
					$message33 = "";
					$message33 .= $this->sendEmailtemplate($note_info, $rulename, 'Form Rule',$andRuleArrayemail[$note_info['notes_id']], $facilityDetails);
					
					if($user_info['email'] != null && $user_info['email'] != ""){
						$user_email = $user_info['email'];
					}
					
					$sql3e = "UPDATE `" . DB_PREFIX . "notes` SET form_send_email = '1' WHERE notes_id = '".$note_info['notes_id']."'";			
					$query = $this->db->query($sql3e);
					
					$edata = array();
					$edata['message'] = $message33;
					$edata['subject'] = 'This is an Automated Alert Email.';
					$edata['user_email'] = $user_email;
					$edata['who_user'] = $note_info['user_id'];
					$edata['username'] = $note_info['user_id'];
					$edata['email'] = $user_info['email'];
					$edata['phone_number'] = $user_info['phone_number'];
					$edata['sms_number'] = $facility['sms_number'];
					$edata['facility'] = $facility['facility'];
					$edata['address'] = $facility['address'];
					$edata['location'] = $facility['location'];
					$edata['zipcode']= $facility['zipcode'];
					$edata['contry_name'] = $country_info['name'];
					$edata['zone_name'] = $zone_info['name'];
					//$edata['href'] = $this->url->link('common/login', '', 'SSL');
					$edata['rules_name'] = $rulename;
					$edata['rules_type'] = 'Form Rule';
					$edata['rules_value'] = $andRuleArrayemail[$note_info['notes_id']];
					$edata['type'] = '11';
					 $edata['href'] = $notes_info['href'];
                      $edata['date_added'] = $notes_info['date_added'];
                      $edata['notetime'] = $notes_info['notetime'];
						
					$email_status = $this->model_api_emailapi->sendmail($edata);
					
					
					
					
				}
					
			}
		}
		
		//var_dump($facilityDetails);
		//var_dump($json['rulenotes']);
		
		//echo "<hr>";
		//var_dump($andRuleArray);
		//echo "<hr>";
		//var_dump($rowModule);
		$tnotesIds = array_unique($tnotesIds);
		//echo "<hr>";
		//var_dump($tnotesIds);
		//die;
		
		
		if($tnotesIds != null && $tnotesIds != ""){
			$this->load->model('createtask/createtask');
			$sqlst2 = "SELECT notes_id, emp_tag_id, facilities_id, notes_description, highlighter_id, date_added, user_id, notetime, note_date, form_snooze_time FROM `" . DB_PREFIX . "notes` where notes_id in (".implode(',',$tnotesIds).") and status = '1' and text_color_cut = '0' and `form_snooze_dismiss` != '2' and `form_create_task` = '0' ";
			
			$query2 = $this->db->query($sqlst2);
			
			$thestime6 = date('H:i:s');
			//var_dump($thestime6);
			$snooze_time7 = 60;
			$stime8 = date("h:i A",strtotime("+".$snooze_time7." minutes",strtotime($thestime6)));
			//var_dump($stime8);
			
			
			foreach($query2->rows as $tresult){
				
				$sqls23 = "SELECT * FROM `" . DB_PREFIX . "createtask` where rules_task = '".$tresult['notes_id']."' ";
				$query4 = $this->db->query($sqls23);
				if($query4->num_rows == 0){
					$addtask = array();
					
					/*if($rowModule['taskTime'] != null && $rowModule['taskTime'] != ""){
						$snooze_time71 = 0;
						$thestime61 = $rowModule['taskTime'];
					}else{
						$snooze_time71 = 10;
						$thestime61 = date('H:i:s');
					}*/
					
					$snooze_time71 = 0;
					$thestime61 = date('H:i:s');
					
					$taskTime = date("H:i:s",strtotime("+".$snooze_time71." minutes",strtotime($thestime61)));
					
					if($rowModule['date_wise_task_time'] == '1'){
						$taskTime1 = $rowModule['taskTime'];
						
						$thestime61 = date('H:i:s', strtotime($taskTime1));
						//var_dump($thestime6);
						$snooze_time71 = 60;
						$stime81 = date("h:i A",strtotime("+".$snooze_time71." minutes",strtotime($thestime61)));
						
						
					}else{
						$taskTime1 = $taskTime;
						$stime81 = $stime8;
					}
					
					if($rowModule['date_wise_task'] == '1'){
						$taskDate = $rowModule['taskDate'];
						$end_recurrence_date = $rowModule['end_recurrence_date'];
					}else{
						$taskDate = date('m-d-Y', strtotime($tresult['date_added']));
						$end_recurrence_date = date('m-d-Y', strtotime($tresult['date_added']));
					}
					
					$addtask['taskDate'] = $taskDate;
					$addtask['end_recurrence_date'] = $end_recurrence_date;
					$addtask['recurrence'] = $rowModule['recurrence'];
					$addtask['recurnce_week'] = $rowModule['recurnce_week'];
					$addtask['recurnce_hrly'] = $rowModule['recurnce_hrly'];
					$addtask['recurnce_month'] = $rowModule['recurnce_month'];
					$addtask['recurnce_day'] = $rowModule['recurnce_day'];
					$addtask['taskTime'] = $taskTime1; //date('H:i:s');
					$addtask['endtime'] = $stime81;
					$addtask['description'] = $rowModule['description'].' '.$tresult['notes_description'];
					
					if($rowModule['assign_to']){
						$addtask['assignto'] = $rowModule['assign_to'];
					}else{
						$addtask['assignto'] = $tresult['user_id'];
					}
													
					$addtask['facilities_id'] = $rowModule['facilities_id'];
					$addtask['task_form_id'] = $rowModule['task_form_id'];
					if($rowModule['transport_tags'] != null && $rowModule['transport_tags'] !=""){
						//$addtask['transport_tags'] = explode(',',$rowModule['transport_tags']);
					}
					
					$tagss = array();
					if($rowModule['transport_tags'] != null && $rowModule['transport_tags'] !=""){
						$tagss[] = explode(',',$rowModule['transport_tags']);
					}
					
					if($tresult['emp_tag_id'] == '1'){
						$alltags = $this->model_notes_notes->getNotesTagsmultiple ( $tresult['notes_id']);
						foreach($alltags as $alltag){
							$tagss[] = $alltag['tags_id'];
						}
					}
					
					$tagss1 = array_unique($tagss);
					
					$addtask['transport_tags'] = $tagss1;
																	
					$addtask['pickup_facilities_id'] = $rowModule['pickup_facilities_id'];
					$addtask['pickup_locations_address'] = $rowModule['pickup_locations_address'];
					$addtask['pickup_locations_time'] = $rowModule['pickup_locations_time'];
																	
					$addtask['dropoff_facilities_id'] = $rowModule['dropoff_facilities_id'];
					$addtask['dropoff_locations_address'] = $rowModule['dropoff_locations_address'];
					$addtask['dropoff_locations_time'] = $rowModule['dropoff_locations_time'];
					
					$addtask['tasktype'] = $rowModule['tasktype'];
					$addtask['numChecklist'] = $rowModule['numChecklist'];
					$addtask['task_alert'] = $rowModule['task_alert'];
					$addtask['alert_type_sms'] = $rowModule['alert_type_sms'];
					$addtask['alert_type_notification'] = $rowModule['alert_type_notification'];
					$addtask['alert_type_email'] = $rowModule['alert_type_email'];
					$addtask['rules_task'] = $tresult['notes_id'];
					
					
					$addtask['recurnce_hrly_recurnce'] = $rowModule['recurnce_hrly_recurnce'];
					$addtask['daily_endtime'] = $rowModule['daily_endtime'];
					
					if($rowModule['daily_times'] != null && $rowModule['daily_times'] !=""){
						$addtask['daily_times'] =  explode(',',$rowModule['daily_times']);
					}
					
					if($rowModule['medication_tags'] != null && $rowModule['medication_tags'] !=""){
						$addtask['medication_tags'] =  explode(',',$rowModule['medication_tags']);
					
					
						$aa  = urldecode($rowModule['tags_medication_details_ids']); 
						$aa1  = unserialize($aa); 
										
						$tags_medication_details_ids = array();
						foreach($aa1 as $key=>$mresult){
							$tags_medication_details_ids[$key] = $mresult;
						}
						$addtask['tags_medication_details_ids'] = $tags_medication_details_ids;
					
					}
					
					$addtask['emp_tag_id'] = $rowModule['emp_tag_id'];
					
					$addtask['recurnce_hrly_perpetual'] = $rowModule['recurnce_hrly_perpetual'];
					$addtask['completion_alert'] = $rowModule['completion_alert'];
					$addtask['completion_alert_type_sms'] = $rowModule['completion_alert_type_sms'];
					$addtask['completion_alert_type_email'] = $rowModule['completion_alert_type_email'];
					
					if($rowModule['user_roles'] != null && $rowModule['user_roles'] !=""){
						$addtask['user_roles'] =  explode(',',$rowModule['user_roles']);
					}
					
					if($rowModule['userids'] != null && $rowModule['userids'] !=""){
						$addtask['userids'] =  explode(',',$rowModule['userids']);
					}
					$addtask['task_status'] = $rowModule['task_status'];
					
					$addtask['visitation_tag_id'] = $rowModule['visitation_tag_id'];
					
					if($rowModule['visitation_tags'] != null && $rowModule['visitation_tags'] !=""){
						$addtask['visitation_tags'] =  explode(',',$rowModule['visitation_tags']);
					}
					$addtask['visitation_start_facilities_id'] = $rowModule['visitation_start_facilities_id'];
					$addtask['visitation_start_address'] = $rowModule['visitation_start_address'];
					$addtask['visitation_start_time'] = $rowModule['visitation_start_time'];
					$addtask['visitation_appoitment_facilities_id'] = $rowModule['visitation_appoitment_facilities_id'];
					$addtask['visitation_appoitment_address'] = $rowModule['visitation_appoitment_address'];
					$addtask['visitation_appoitment_time'] = $rowModule['visitation_appoitment_time'];
					$addtask['complete_endtime'] = $rowModule['complete_endtime'];
					
					if($rowModule['completed_times'] != null && $rowModule['completed_times'] !=""){
						$addtask['completed_times'] =  explode(',',$rowModule['completed_times']);
					}
					$addtask['completed_alert'] = $rowModule['completed_alert'];
					$addtask['completed_late_alert'] = $rowModule['completed_late_alert'];
					$addtask['incomplete_alert'] = $rowModule['incomplete_alert'];
					$addtask['deleted_alert'] = $rowModule['deleted_alert'];
					$addtask['attachement_form'] = $rowModule['attachement_form'];
					$addtask['tasktype_form_id'] = $rowModule['tasktype_form_id'];
					
					$addtask['reminder_alert'] = $rowModule['reminder_alert'];
					if($rowModule['reminderminus'] != null && $rowModule['reminderminus'] !=""){
						$addtask['reminderminus'] =  explode(',',$rowModule['reminderminus']);
					}
					
					if($rowModule['reminderplus'] != null && $rowModule['reminderplus'] !=""){
						$addtask['reminderplus'] =  explode(',',$rowModule['reminderplus']);
					}
					
					$addtask['assign_to_type'] = $rowModule['assign_to_type'];
					if($rowModule['user_assign_to'] != null && $rowModule['user_assign_to'] !=""){
						$addtask['assign_to'] =  explode(',',$rowModule['user_assign_to']);
					}
					
					if($rowModule['user_role_assign_ids'] != null && $rowModule['user_role_assign_ids'] !=""){
						$addtask['user_role_assign_ids'] =  explode(',',$rowModule['user_role_assign_ids']);
					}
					
					$sqlw = "update `" . DB_PREFIX . "notes` set form_snooze_dismiss = '2',snooze_dismiss = '2', form_create_task = '1' where notes_id ='".$tresult['notes_id']."'";
					$this->db->query($sqlw); 
					
						
					$task_id = $this->model_createtask_createtask->addcreatetask($addtask, $facilities_id);
					$sqlw2 = "update `" . DB_PREFIX . "createtask` set formrules_id = '".$rowModule['rules_id']."' where id ='".$task_id."'";
					$this->db->query($sqlw2); 
					
					
					$rowModule = array();
				}
			}
		}
		
	
		$notesIds = array_unique($notesIds);
	
		if($notesIds != null && $notesIds != ""){
			
			$thestime = date('H:i:s');
			//var_dump($thestime);
			$snooze_time = 0;
			$stime = date("H:i:s",strtotime("+".$snooze_time." minutes",strtotime($thestime)));
			
			//var_dump($stime);
					
			$sqls2 = "SELECT notes_id, emp_tag_id, facilities_id, notes_description, highlighter_id, date_added, user_id, notetime, note_date, form_snooze_time,send_sms,send_email FROM `" . DB_PREFIX . "notes` where notes_id in (".implode(',',$notesIds).") and form_snooze_dismiss != '2' and status = '1' and text_color_cut = '0' ";
			
			$query = $this->db->query($sqls2);
			
			$config_tag_status = $this->customer->isTag();
			
			$this->load->model('notes/tags');
			if ($query->num_rows) {
				
				foreach($query->rows as $result){
					
				
					
					//echo $thestime.'<='.$result['snooze_time'];
					if($thestime >= $result['form_snooze_time']){
						$highlighterData = $this->model_setting_highlighter->gethighlighter($result['highlighter_id']);
						//$user_info = $this->model_user_user->getUserByUsername($result['user_id']);
						$user_info = $this->model_user_user->getUserByUsernamebynotes($result['user_id'], $result['facilities_id']);
						
						if ($config_tag_status == '1') {
							if($result['emp_tag_id'] != null && $result['emp_tag_id'] != ""){
								$tagdata = $this->model_notes_tags->getTagbyEMPID($result['emp_tag_id']);
								$privacy = $tagdata['privacy'];
								
								$emp_tag_id = $result['emp_tag_id'].': ';
							}else{
								$emp_tag_id = '';
								$privacy = '';
							}
						}
						
						if($privacy == '2'){
							if($this->session->data['unloack_success'] == '1'){
								$notes_description = $keyImageSrc1 .'&nbsp;'. $emp_tag_id . $result['notes_description'];
							}else{
								$notes_description = $emp_tag_id;
							}
						}else{
							$notes_description = $keyImageSrc1 .'&nbsp;'. $emp_tag_id . $result['notes_description'];
						}
						
						if(!empty($andRuleArray)){
							$note_d = $andRuleArray[0] .' ';
						}
						
						$json['rulenotes'][] = array(
							'notes_id'    => $result['notes_id'],
							'rules_id'    => '',
							'highlighter_value'   => $highlighterData['highlighter_value'],
							'notes_description'   => $note_d.$notes_description,
							'date_added' => date('j, F Y', strtotime($result['date_added'])),
							'note_date'   => date('j, F Y h:i A', strtotime($result['note_date'])),
							'notetime'   => date('h:i A', strtotime($result['notetime'])),
							'username'      => $result['user_id'],
							'email'      => $user_info['email'],
							'facility'     => $facility['facility'],
							
							'web_audio_file' =>$facility_web_audio_file,
							'is_web_notification' =>$facility['is_web_notification'],
							'web_is_snooze' =>$facility['web_is_snooze'],
							'web_is_dismiss' =>$facility['web_is_dismiss'],
						);
						
						$json['total'] = '1'; 
					}else{
						if($json['rulenotes'] == null && $json['rulenotes'] == ""){
							$json['rulenotes'] = array();
							$json['total'] = '0'; 
						}
					}
					
				}
				
			}else{
				$json['rulenotes'] = array();
				$json['total'] = '0'; 
			}
			
		}else{
			if($json['rulenotes'] == null && $json['rulenotes'] == ""){
				$json['rulenotes'] = array();
				$json['total'] = '0'; 
			}
		}
		
		
		/*if($this->config->get('active_notification') == '1'){
			if(!empty($json['rulenotes'])) {
				$this->load->model('api/notify');
				
				$this->load->model('notes/device');
				$device_detail = $this->model_notes_device->getdeviceweb($this->session->data['session_key'], $this->session->data['activationkey'], $this->customer->getId());
				
				$this->model_api_notify->websendnotification($json, $device_detail['token']);
			}
		}*/
		
		if($this->config->get('active_notification') == '2'){
			$this->response->setOutput(json_encode($json));
		}
		
		
		
	}
	
	
	public function updateNotification(){
		$json = array();
		
		
		$this->load->model('facilities/online');
		$datafa = array();
		$datafa['username'] = $this->session->data['webuser_id'];
		$datafa['activationkey'] = $this->session->data['activationkey'];
		$datafa['facilities_id'] = $this->customer->getId();
		$datafa['ip'] = $this->request->server['REMOTE_ADDR'];
		
		$this->model_facilities_online->updatefacilitiesOnline2($datafa);
		
		
		if(($this->request->post["notes_id"] == null && $this->request->post["notes_id"] == "") && ($this->request->post["task_id"] == null && $this->request->post["task_id"] == "") && ($this->request->post["rules_id"] == null && $this->request->post["rules_id"] == "")){
			$json['warning'] = 'Please check the checkbox';
		}
		
		if($json['warning'] == null && $json['warning'] == ""){
			if($this->request->post["notes_id"] != null && $this->request->post["notes_id"] != ""){
				if($this->request->get["type"] == "1"){
					
					$timezone_name = $this->customer->isTimezone();
					date_default_timezone_set($timezone_name);
						
					foreach ($this->request->post['notes_id'] as $notes_id) {
						$snooze_time = $this->request->post['snooze_time'];
						
						
						$thestime = date('H:i:s');
				
						$stime = date("H:i:s",strtotime("+".$snooze_time." minutes",strtotime($thestime)));
					
						$sqlsn = "UPDATE `" . DB_PREFIX . "notes` SET form_snooze_time = '" . $stime . "' WHERE notes_id = '" . (int)$notes_id . "' ";
						
						
						$this->db->query($sqlsn);
						
					}
					
					$json['success'] = '1';
					$json['message'] = 'You have update rules successfully!';
				}
				
				if($this->request->get["type"] == "2"){
					foreach ($this->request->post['notes_id'] as $notes_id) {
					
						$sqlsn = "UPDATE `" . DB_PREFIX . "notes` SET form_snooze_dismiss = '2',snooze_dismiss = '2' WHERE notes_id = '" . (int)$notes_id . "' ";
						$this->db->query($sqlsn);
					}
					
					$json['success'] = '1';
					$json['message'] = 'You have dismiss rules successfully!';
				}
			}
			
			
			if($this->request->post["rules_id"] != null && $this->request->post["rules_id"] != ""){
				if($this->request->get["type"] == "1"){
					
					$timezone_name = $this->customer->isTimezone();
					date_default_timezone_set($timezone_name);
						
					foreach ($this->request->post['rules_id'] as $rules_id) {
						
						$sql = "SELECT * FROM " . DB_PREFIX . "formrules where rules_id = '" . (int)$rules_id . "' and status='1' and form_snooze_dismiss != '2' ";
						$query = $this->db->query($sql);
						
						$snooze_time = $this->request->post['snooze_time'];
						
						$thestime = $query->row['rules_operation_time'] ; //date('H:i:s');
				
						$stime = date("H:i:s",strtotime("+".$snooze_time." minutes",strtotime($thestime)));
					
						$sqlsn = "UPDATE `" . DB_PREFIX . "formrules` SET rules_operation_time = '" . $stime . "' WHERE rules_id = '" . (int)$rules_id . "' ";
						
						
						$this->db->query($sqlsn);
						
					}
					
					$json['success'] = '1';
					$json['message'] = 'You have update rules successfully!';
				}
				
				if($this->request->get["type"] == "2"){
					foreach ($this->request->post['rules_id'] as $rules_id) {
					
						$sqlsn = "UPDATE `" . DB_PREFIX . "formrules` SET form_snooze_dismiss = '2' WHERE rules_id = '" . (int)$rules_id . "' ";
						$this->db->query($sqlsn);
					}
					
					$json['success'] = '1';
					$json['message'] = 'You have dismiss rules successfully!';
				}
			}
			
			
		}
		
		
		$this->response->setOutput(json_encode($json));
	}
	
	public function taskemailtemplate($result, $taskDate, $taskeTiming){
		$html = "";
		$html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Task has been assigned to you</title>

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
						<td align="right" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">Task has been assigned to you</h6></td>
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
							
							<h1 style="font-weight: 200;font-size: 30px;padding: 0;margin: 0;">Hello '.$result['assignto'].'!</h1>
							<p class="lead" style="font-size: 14px;margin-bottom: 10px;font-weight: normal;line-height: 1.6;">Task has been assigned to you '.$result['tasktype'].'. Please review the details below for further information or actions:</p>
							
						</td>
					</tr>
				</table>
			</div>
			<div class="content" style="max-width: 600px;margin: 0 auto;display: block; background: #fa801b ">
				
				<table bgcolor="#fa801b" style="width: 100%;padding: 15px;">
					<tr>
						<td class="small" width="10%" style="vertical-align: top; padding-right:10px;"><a target="_blank" href="'.$result['href'].'">
						<img src="'.HTTP_SERVER.'sites/view/digitalnotebook/stylesheet/email/what.png" style="width:75px;" /></a></td>
						<td>
							<h4 style="font-weight: 500;font-size: 23px;padding: 0;margin: 0;">What <small style=" font-size: 60%; color: #fff;line-height: 0; text-transform: none;vertical-align: middle;">Task Created</small></h4>
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							'.$result['description'].'
							</p>
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

	public function sendEmailtemplate($result, $ruleName, $ruleType, $rulevalue, $facilityData){
		$html = "";
		$html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>This is an Automated Alert Email</title>

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
						<td align="right" style="padding: 0;margin: 0;vertical-align: bottom;"><h6 class="collapse" style="margin: 0!important;font-weight: 900; font-size: 20px;text-transform: uppercase; color: #fa801b;">This is an Automated Alert Email</h6></td>
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
							<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							'.$result['notes_description'].'
							</p>
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
						<p style="margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
						'.date('j, F Y', strtotime($result['date_added'])).'&nbsp;'.date('h:i A', strtotime($result['notetime'])).'
						</p>
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