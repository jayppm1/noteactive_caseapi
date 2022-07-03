<?php 
 header('Access-Control-Allow-Origin:'.$_SERVER['HTTP_ORIGIN']);
 header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
 header('Access-Control-Max-Age: 1000');
 header('Access-Control-Allow-Headers: Content-Type');
 header('Content-type: application/json');
 header('Content-Type: text/html; charset=utf-8');
 header("Content-type: bitmap; charset=utf-8");
 
class Controllercaseservicesformrule extends Controller { 
	private $error = array();
	public function index(){
		try{
			
			$this->load->model('api/encrypt');
			$cre_array = array();
			$cre_array['phone_device_id'] = $this->request->post['phone_device_id'];
			$cre_array['facilities_id'] = $this->request->post['facilities_id'];
			
			$api_device_info = $this->model_api_encrypt->getdevicedetails($cre_array);
			
			if($api_device_info == false){
				$errorMessage = $this->model_api_encrypt->errorMessage();
				return $errorMessage;
			}
			
			$api_header_value = $this->model_api_encrypt->getallheaders1();
			
			if($api_header_value == false){
				$errorMessage = $this->model_api_encrypt->errorMessage();
				return $errorMessage;
			}
		
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
		
		require_once(DIR_SYSTEM . 'library/twilio-php-master/smsconfig.php');
		require_once(DIR_SYSTEM . 'library/PHPMailer-master/PHPMailerAutoload.php');
		
		
		$data3 = array(
			'facilities_id' => $this->request->post['facilities_id'],
		);
		
		$facilities_id = $this->request->post['facilities_id'];
		$facility = $this->model_facilities_facilities->getfacilities($facilities_id);
		
		
		if($facility['config_taskform_status'] == '1'){
			$rules = $this->model_form_form->getRules($data3);
		}
		
		//var_dump($rules);
		
		
		$json = array();
		$fnotesIds = array();
		$ftnotesIds = array();
		$facilityDetails = array();
		
		$andRuleArray = array();
		$nrulesvalue = "";
		$rulename = "";
		$rulesvalue = "";
		$andrulesValues = array();
		$andrulesTaskValues = array();
		$andrulesActionValues = array();
		$andrulesActionValues2 = array();
		
		$fnotesIdsemail = array();
		$andRuleArrayemail = array();
		
		$fnotesIdssms = array();
		$andRuleArraysms = array();
		
		$fnotesIdstask = array();
		$andRuleArraytask = array();
		
		$rowModule = array();
		$rulename = '';
		
		if($rules){
			$timezone_name = $this->request->post['facilitytimezone'];
			date_default_timezone_set($timezone_name);
			
			$searchdate =  date('m-d-Y');
			
			$current_date = date('Y-m-d', strtotime('now'));
			$current_time = date('H:i', strtotime('now'));
					
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
						
						$sqls = "select DISTINCT n.*,f.custom_form_type,f.forms_id,f.tags_id from `" . DB_PREFIX . "notes` n ";
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
						
						
						
						$sqls .= " and n.facilities_id = '".$facility['facilities_id']."'";
						$sqls .= " and f.custom_form_type = '".$rule['forms_id']."'";
						$sqls .= " and f.is_discharge = '0'";
						$sqls .= " and n.form_snooze_dismiss != '2' ";
										
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
								);
								
								
								
								
								if(in_array('4', $rule['rule_action'])){
									
									//var_dump($rule['rule_action_content']['task_random_id']);
									//echo "<hr>";
									
									$thestime6 = date('H:i:s');
									//var_dump($thestime6);
									$snooze_time7 = 60;
									$stime8 = date("h:i A",strtotime("+".$snooze_time7." minutes",strtotime($thestime6)));
									
									$sqls23 = "SELECT * FROM `" . DB_PREFIX . "createtask` where form_due_date = '".$rules_module['form_due_date']."' and form_due_date_after = '".$rules_module['form_due_date_after']."' and rules_task = '".$result['notes_id']."' ";
									
									$query4 = $this->db->query($sqls23);
									if($query4->num_rows == 0){
										
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
																		
										$addtask['facilities_id'] = $rule['rule_action_content']['facilities_id'];
										$addtask['task_form_id'] = $rule['rule_action_content']['task_form_id'];
										if($rule['rule_action_content']['transport_tags'] != null && $rule['rule_action_content']['transport_tags'] !=""){
											$addtask['transport_tags'] = explode(',',$rule['rule_action_content']['transport_tags']);
										}
																						
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
										
										
										if($rule['rule_action_content']['emp_tag_id']){
											$addtask['emp_tag_id'] = $rule['rule_action_content']['emp_tag_id'];
										}else{
											$addtask['emp_tag_id'] = $result['tags_id'];
										}
										
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
										
										//var_dump($addtask);
										//echo "<hr>";
										
										
										//$sqlw = "update `" . DB_PREFIX . "notes` set form_snooze_dismiss = '2', form_create_task = '1' where notes_id ='".$result['notes_id']."'";
										//$this->db->query($sqlw); 
										
											
										$task_id = $this->model_createtask_createtask->addcreatetask($addtask, $facilities_id);
										$sqlw2 = "update `" . DB_PREFIX . "createtask` set formrules_id = '".$rule['rules_id']."', form_due_date = '".$rules_module['form_due_date']."', form_due_date_after = '".$rules_module['form_due_date_after']."' where id ='".$task_id."'";
										$this->db->query($sqlw2); 
										
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
						$sqls .= " and n.facilities_id = '".$facility['facilities_id']."'";
						$sqls .= " and f.custom_form_type = '".$rule['forms_id']."'";
						$sqls .= " and n.form_snooze_dismiss != '2' ";
										
						$date = str_replace('-', '/', $searchdate);
						$res = explode("/", $date);
						$changedDate = $res[2]."-".$res[0]."-".$res[1];
						$startDate = $changedDate;
						$endDate = $changedDate;
						$sqls .= " and ( n.`date_added` BETWEEN '".$startDate." 00:00:00 ' AND '".$endDate." 23:59:59' or f.`date_added` BETWEEN '".$startDate." 00:00:00 ' AND '".$endDate." 23:59:59' ) ";
						$sqls .= " and n.status = '1' ORDER BY n.notetime DESC  ";
						
						//echo $sqls;
						//echo "<hr>";
						
						$query = $this->db->query($sqls);
						//var_dump($query->num_rows);
						
						if ($query->num_rows) {
							
							foreach($query->rows as $result){
								$date_added = $result['date_added'];
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
											);
										
									break;
									
								}
								
								
								
								
								if($onschedule_rules_module['onschedule_action'] == '4'){
									//var_dump($newdate);
									//echo "<hr>";
									
									$thestime6 = date('H:i:s');
									//var_dump($thestime6);
									$snooze_time7 = 60;
									$stime8 = date("h:i A",strtotime("+".$snooze_time7." minutes",strtotime($thestime6)));
									
									$sqls23 = "SELECT * FROM `" . DB_PREFIX . "createtask` where task_random_id = '".$onschedule_rules_module['task_random_id']."' ";
									$query4 = $this->db->query($sqls23);
									if($query4->num_rows == 0){
										
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
																		
										$addtask['facilities_id'] = $onschedule_rules_module['facilities_id'];
										$addtask['task_form_id'] = $onschedule_rules_module['task_form_id'];
										if($onschedule_rules_module['transport_tags'] != null && $onschedule_rules_module['transport_tags'] !=""){
											$addtask['transport_tags'] = explode(',',$onschedule_rules_module['transport_tags']);
										}
																						
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
										}else{
											$addtask['emp_tag_id'] = $result['tags_id'];
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
										
										
										
										
										//$sqlw = "update `" . DB_PREFIX . "notes` set form_snooze_dismiss = '2', form_create_task = '1' where notes_id ='".$result['notes_id']."'";
										//$this->db->query($sqlw); 
										
											
										$task_id = $this->model_createtask_createtask->addcreatetask($addtask, $facilities_id);
										$sqlw2 = "update `" . DB_PREFIX . "createtask` set formrules_id = '".$rule['rules_id']."', task_random_id = '".$onschedule_rules_module['task_random_id']."' where id ='".$task_id."'";
										$this->db->query($sqlw2); 
										
										
										if($rule['rules_operation_recurrence'] == '1'){
											$sqlw24 = "update `" . DB_PREFIX . "createtask` set recurnce_m = '1' where id ='".$task_id."'";
											$this->db->query($sqlw24); 
										}
										
										
										
									}
								}
								
								
							}
						}
					}
				}
			
			
			
				/**************** ACTION *********************/
				
				//var_dump($allnotesIds);
				//echo "<hr>";
				
				//var_dump($allnotesIds);
				
				
				
				if($allnotesIds != null && $allnotesIds != ""){
					
					if(in_array('3', $rule['rule_action'])){
						
						//var_dump($allnotesIds);
						
						foreach($allnotesIds as $allnotesId){
							$fnotesIds[] = $allnotesId['notes_id'];
							$andRuleArray[] = $rule['rules_name'] .' '.$allnotesId['rules_value'];
						}
					}
					
					
					
					if(in_array('4', $rule['rule_action'])){
						foreach($allnotesIds as $allnotesId){
							$sqlw = "update `" . DB_PREFIX . "notes` set form_snooze_dismiss = '2',snooze_dismiss = '2', form_create_task = '1' where notes_id ='".$allnotesId['notes_id']."'";
							$this->db->query($sqlw); 
						}
					}
					
					if($onschedule_rules_module['onschedule_action'] == '4'){
						foreach($allnotesIds as $allnotesId){
							$sqlw = "update `" . DB_PREFIX . "notes` set form_snooze_dismiss = '2',snooze_dismiss = '2', form_create_task = '1' where notes_id ='".$allnotesId['notes_id']."'";
							$this->db->query($sqlw); 
						}
					}
					
					
					
					if(in_array('1', $rule['rule_action'])){
						
						foreach($allnotesIds as $allnotesId){
							
							if($allnotesId['onschedule_action'] != '4'){
							
							$sqls2 = "SELECT * FROM `" . DB_PREFIX . "notes`";
							$sqls2 .= 'where 1 = 1 ';
							$sqls2 .= " and notes_id = '".$allnotesId['notes_id']."'";
							$sqls2 .= " and form_send_sms = '0'";
							
							$query = $this->db->query($sqls2);
							
							$note_info = $query->row;
							
							if ($query->num_rows) {
								$message = "Rules Created \n";
								$message .= date('h:i A', strtotime($note_info['notetime']))."\n";
								$message .= $rule['rules_name'] .'-'.$allnotesId['rules_type'].'-'.$allnotesId['rules_value']."\n";
								$message .= substr($note_info['notes_description'], 0, 150) .((strlen($note_info['notes_description']) > 150) ? '..' : '');
								
								//$user_info = $this->model_user_user->getUserByUsername($note_info['user_id']);
								
								$user_info = $this->model_user_user->getUserByUsernamebynotes($note_info['user_id'], $note_info['facilities_id']);
								
								if($user_info['phone_number'] != null && $user_info['phone_number'] != '0'){
									$phone_number = $user_info['phone_number'];
								}else{
									$phone_number = '19045832155';
								}
								
								$number = '+'.$phone_number;
								$text = $message;
								$from = "+19042040577";
								
								$response = $client->messages->create(
									'+'.$phone_number,
									array(
										'from' => '+19042040577',
										'body' => $message
									)
								);
								
								
								
								if($rule['rule_action_content']['auser_roles'] != null && $rule['rule_action_content']['auser_roles'] != ""){
									
									$user_roles1 = $rule['rule_action_content']['auser_roles'];
									
									foreach ($user_roles1 as $user_role) {
										$urole = array();
										$urole['user_group_id'] = $user_role;
										$tusers = $this->model_user_user->getUsers($urole);
										
										if($tusers){
											foreach ($tusers as $tuser) {
												if($tuser['phone_number'] != null && $tuser['phone_number'] != ""){
													$number = $tuser['phone_number']; 
													
													$response = $client->messages->create(
														'+'.$number,
														array(
															'from' => '+19042040577',
															'body' => $message
														)
													);
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
												$number = $user_info['phone_number']; 
												
												$response = $client->messages->create(
													'+'.$number,
													array(
														'from' => '+19042040577',
														'body' => $message
													)
												);
											}
										}
									}
									
								}
								
								$sql3e = "UPDATE `" . DB_PREFIX . "notes` SET form_send_sms = '1' WHERE notes_id = '".$allnotesId['notes_id']."'";			
								$query = $this->db->query($sql3e);
								
							}
							}
						}
						
					}
					
					if(in_array('2', $rule['rule_action'])){
						foreach($allnotesIds as $allnotesId){
							if($allnotesId['onschedule_action'] != '4'){
							$sqls2 = "SELECT * FROM `" . DB_PREFIX . "notes`";
							$sqls2 .= 'where 1 = 1 ';
							$sqls2 .= " and notes_id = '".$allnotesId['notes_id']."'";
							$sqls2 .= " and form_send_email = '0'";
							
							$query = $this->db->query($sqls2);
							
							$note_info = $query->row;
							
							if ($query->num_rows) {
								
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
								
									
								if($this->config->get('config_mail_protocol')  == 'smtp'){				
								
									//require_once(DIR_SYSTEM . 'library/PHPMailer-master/PHPMailerAutoload.php');
									$mail = new PHPMailer;
																			 
									$mail->Host = $this->config->get('config_smtp_host');   
																			
									if($this->config->get('config_smtp_auth') == '1'){
										$mail->SMTPAuth = true;                           
									}
																			
									$mail->Username = $this->config->get('config_smtp_username');        
									$mail->Password = $this->config->get('config_smtp_password');               
																			
									if($this->config->get('config_smtp_ssl') == '1'){
										$mail->SMTPSecure = 'tls';                    
									}
																			
									$mail->Port = $this->config->get('config_smtp_port');                            
									$mail->setFrom('support@noteactive.com', $this->config->get('config_name'));  
									$mail->addReplyTo('support@noteactive.com', $this->config->get('config_name')); 

									
									if($rule['rule_action_content']['auser_roles'] != null && $rule['rule_action_content']['auser_roles'] != ""){
													
										$user_roles1 = $rule['rule_action_content']['auser_roles'];

										foreach ($user_roles1 as $user_role) {
											$urole = array();
											$urole['user_group_id'] = $user_role;
											$tusers = $this->model_user_user->getUsers($urole);
											
											if($tusers){
												foreach ($tusers as $tuser) {
													if($tuser['email'] != null && $tuser['email'] != ""){
														$mail->addAddress($tuser['email']); 
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
													$mail->addAddress($user_info['email']); 
												}
											}
										}
										
									}
									
									
									if($user_info['email'] != null && $user_info['email'] != ""){
										$mail->addAddress($user_info['email']);
									}else{
										$mail->addAddress('app-monitoring@noteactive.com'); 
									}
							
									$mail->WordWrap = 50;                               
									$mail->isHTML(true);                       
																		 
									$mail->Subject = 'This is an Automated Alert Email.';
																
									$message33 = "";
																
									$message33 .= $this->sendEmailtemplate($note_info, $rule['rules_name'], $allnotesId['rules_type'], $allnotesId['rules_value'], $facilityDetails);
									
									
									$mail->msgHTML($message33);
									$mail->send();
								
									
									$sql3e = "UPDATE `" . DB_PREFIX . "notes` SET form_send_email = '1' WHERE notes_id = '".$allnotesId['notes_id']."'";			
									$query = $this->db->query($sql3e);
									
								}
							}
							}
						}
						
					}
					
					
					/*
					if(in_array('4', $rule['rule_action'])){
						
						foreach($allnotesIds as $allnotesId){
							//$ftnotesIds[] = $allnotesId['notes_id'];
							
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
		//var_dump($fnotesIdsemail);
		//echo "<hr>";
		//var_dump($ftnotesIds);
		
		
		
		$fnotesIdssms = array_unique($fnotesIdssms);
		if($fnotesIdssms != null && $fnotesIdssms != ""){
			foreach($fnotesIdssms as $notes_id){
				
				
				
				$sqlsnote = "SELECT * FROM `" . DB_PREFIX . "notes` where notes_id = '".$notes_id."' and form_send_sms = '0' ";
				$query = $this->db->query($sqlsnote);
					
				$note_info = $query->row;
				if($note_info != null && $note_info != ""){
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
					}else{
						$phone_number = '19045832155';
					}
															
					$number = '+'.$phone_number;
					$text = $message;
					$from = "+19042040577";
					
					$response = $client->messages->create(
						'+'.$phone_number,
						array(
							'from' => '+19042040577',
							'body' => $message
							)
						);
															
					$sql3e = "UPDATE `" . DB_PREFIX . "notes` SET form_send_sms = '1' WHERE notes_id = '".$note_info['notes_id']."'";			
					$query = $this->db->query($sql3e);	
				}	
			}
		}
		
		
		
		/****EMAIL CODE ****/
		$fnotesIdsemail = array_unique($fnotesIdsemail);
		//var_dump($fnotesIdsemail);
		if($fnotesIdsemail != null && $fnotesIdsemail != ""){
			foreach($fnotesIdsemail as $notes_id){
				
				$sqlsnote = "SELECT * FROM `" . DB_PREFIX . "notes` where notes_id = '".$notes_id."' and form_send_email = '0' ";
				$query = $this->db->query($sqlsnote);
					
				$note_info = $query->row;
				
				
				if($note_info != null && $note_info != ""){
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

					
					if($this->config->get('config_mail_protocol')  == 'smtp'){				
							$mail = new PHPMailer;
							$mail->Host = $this->config->get('config_smtp_host');   
																											
							if($this->config->get('config_smtp_auth') == '1'){
								$mail->SMTPAuth = true;                           
							}
																											
							$mail->Username = $this->config->get('config_smtp_username');        
							$mail->Password = $this->config->get('config_smtp_password');               
																											
							if($this->config->get('config_smtp_ssl') == '1'){
								$mail->SMTPSecure = 'tls';                    
							}
																											
							$mail->Port = $this->config->get('config_smtp_port');                            
							$mail->setFrom('support@noteactive.com', $this->config->get('config_name'));  
							$mail->addReplyTo('support@noteactive.com', $this->config->get('config_name')); 

							if($user_info['email'] != null && $user_info['email'] != ""){
								$mail->addAddress($user_info['email']);
							}else{
								$mail->addAddress('app-monitoring@noteactive.com'); 
							}
															
							$mail->WordWrap = 50;                               
							$mail->isHTML(true);                       
							$mail->Subject = 'This is an Automated Alert Email.';
							$message33 = "";
							$message33 .= $this->sendEmailtemplate($note_info, $rulename, 'Form Rule',$andRuleArrayemail[$note_info['notes_id']], $facilityDetails);
							
							$mail->msgHTML($message33);
							$mail->send();
							$sql3e = "UPDATE `" . DB_PREFIX . "notes` SET form_send_email = '1' WHERE notes_id = '".$note_info['notes_id']."'";			
							$query = $this->db->query($sql3e);
					}
				}
					
			}
		}
		
		//var_dump($facilityDetails);
		//var_dump($json['rulenotes']);
		
		//echo "<hr>";
		//var_dump($andRuleArray);
		//echo "<hr>";
		//var_dump($rowModule);
		$ftnotesIds = array_unique($ftnotesIds);
		//echo "<hr>";
		//var_dump($ftnotesIds);
		//die;
		
		
		if($ftnotesIds != null && $ftnotesIds != ""){
			$this->load->model('createtask/createtask');
			$sqlst2 = "SELECT notes_id, emp_tag_id, facilities_id, notes_description, highlighter_id, date_added, user_id, notetime, note_date, form_snooze_time FROM `" . DB_PREFIX . "notes` where notes_id in (".implode(',',$ftnotesIds).") and status = '1' and text_color_cut = '0' and `form_snooze_dismiss` != '2' and `form_create_task` = '0' ";
			
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
					
					
					
					$sqlw = "update `" . DB_PREFIX . "notes` set form_snooze_dismiss = '2',snooze_dismiss = '2', form_create_task = '1' where notes_id ='".$tresult['notes_id']."'";
					$this->db->query($sqlw); 
					
						
					$task_id = $this->model_createtask_createtask->addcreatetask($addtask, $facilities_id);
					$sqlw2 = "update `" . DB_PREFIX . "createtask` set formrules_id = '".$rowModule['rules_id']."' where id ='".$task_id."'";
					$this->db->query($sqlw2); 
					
					
					$rowModule = array();
				}
			}
		}
		
	
		$fnotesIds = array_unique($fnotesIds);
	
		if($fnotesIds != null && $fnotesIds != ""){
			
			$thestime = date('H:i:s');
			//var_dump($thestime);
			$snooze_time = 0;
			$stime = date("H:i:s",strtotime("+".$snooze_time." minutes",strtotime($thestime)));
			
			//var_dump($stime);
					
			$sqls2 = "SELECT notes_id, emp_tag_id, facilities_id, notes_description, highlighter_id, date_added, user_id, notetime, note_date, form_snooze_time,send_sms,send_email FROM `" . DB_PREFIX . "notes` where notes_id in (".implode(',',$fnotesIds).") and form_snooze_dismiss != '2' and status = '1' and text_color_cut = '0' ";
			
			$query = $this->db->query($sqls2);
			
			$config_tag_status = $facility['config_tag_status'];
			
			
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
						
						$this->data['facilitiess'][] = array(
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
						);
						
						$json['total'] = '1'; 
					}else{
						if($this->data['facilitiess'] == null && $this->data['facilitiess'] == ""){
							$this->data['facilitiess'] = array();
							$json['total'] = '0'; 
						}
					}
					
				}
				
			}else{
				$this->data['facilitiess'] = array();
				$json['total'] = '0'; 
			}
			
		}else{
			if($this->data['facilitiess'] == null && $this->data['facilitiess'] == ""){
				$this->data['facilitiess'] = array();
				$json['total'] = '0'; 
			}
		}
		
		$value = array('results'=>$this->data['facilitiess'],'status'=>true);
		
		$this->response->setOutput(json_encode($value));
		
		}catch(Exception $e){
				$this->load->model('activity/activity');
				$activity_data2 = array(
					'data' => 'Error in Form Rule '.$e->getMessage(),
				);
				$this->model_activity_activity->addActivity('form_rule', $activity_data2);
		}
	
	}

}