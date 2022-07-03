<?php  
class Controllerservicesrules extends Controller {  
	private $error = array();
	
	public function notification() {

		$this->load->model('notes/notes');
		$this->load->model('notes/rules');
		$this->load->model('facilities/facilities');
		
		$this->load->model('setting/highlighter');
		$this->load->model('setting/country');
		$this->load->model('setting/zone');
		$this->load->model('setting/timezone');
		$this->load->model('user/user');
		$this->load->model('notes/tags');
		
		$this->load->model('createtask/createtask');

		
		//require_once(DIR_SYSTEM . 'library/twilio-php/Services/Twilio.php');
		
		require_once(DIR_SYSTEM . 'library/twilio-php-master/smsconfig.php');
		require_once(DIR_SYSTEM . 'library/PHPMailer-master/PHPMailerAutoload.php');
		
		$rules = $this->model_notes_rules->getRules();
		//var_dump($rules);
		//echo "<hr>";
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
		
		$facilities_id = $this->request->post['facilities_id'];
		$timezone_name = $this->request->post['facilities_timezone'];
		
		
		$facilities_info = $this->model_facilities_facilities->getfacilities($facilities_id);
		
		$config_task_status = $facilities_info['config_task_status'];
		$config_rules_status = $facilities_info['config_rules_status'];
		
		foreach($rules as $rule){
			
			$facilitiesids = explode(",", $rule['facilities_id']);
			//var_dump($facilitiesids);
			//echo "<hr>";
			$facilities_id = $this->request->post['facilities_id'];
			//var_dump($facilities_id);
			
			if($rule['facilities_id'] != null && $rule['facilities_id'] != ""){
				
				if (in_array($facilities_id, $facilitiesids)){
					
					//$timezone_name = $this->customer->isTimezone();
					date_default_timezone_set($timezone_name);
					
					
					$currenttimes = date('H:i');
					
					if($currenttimes == '23:59'){
						$sql = "update `" . DB_PREFIX . "rules` set snooze_dismiss = '0' where rules_id ='".$rule['rules_id']."'";
						$this->db->query($sql);
					}
					
					//$facilities = $this->model_facilities_facilities->getfacilityByID($rule['facilities_id']);
					//var_dump($facilities);
					//echo "<hr>";
					//foreach($facilities as $facility){
						//$timezone_info = $this->model_setting_timezone->gettimezone($facility['timezone_id']);
						
						//var_dump($timezone_info['timezone_value']);
						//echo "<hr>";
						//date_default_timezone_set($timezone_info['timezone_value']);
						//$timezone_name = $this->customer->isTimezone();
						date_default_timezone_set($timezone_name);
						$searchdate =  date('m-d-Y');
						
						$facility = $this->model_facilities_facilities->getfacilities($facilities_id);
						
						$country_info = $this->model_setting_country->getCountry($facility['country_id']);
						$zone_info = $this->model_setting_zone->getZone($facility['zone_id']);
					
						
						if($config_rules_status == '1'){
						
						if($rule['rules_operation'] == 2){
							foreach($rule['onschedule_rules_module'] as $onschedule_rules_module){
								//var_dump($onschedule_rules_module);
								
								
								if($rule['rules_operation_recurrence'] == '1'){
									$date = str_replace('-', '/', $searchdate);
									$res = explode("/", $date);
									$changedDate = $res[2]."-".$res[0]."-".$res[1].' '.date('H:i:s');
									
									$snooze_time71 = 0;
									$thestime61 = date('H:i:s');
									$taskTime = date("H:i:s",strtotime("+".$snooze_time71." minutes",strtotime($thestime61)));
									
									//var_dump($changedDate);
									$dailytime = date('H:i');
									
									//var_dump($dailytime);
									
									$rules_operation_time = date('H:i', strtotime($rule['rules_operation_time']));
									
									//var_dump($onschedule_rules_module['onschedule_action']);
									//echo "<hr>";
									if($dailytime == $rules_operation_time){
										
										$onschedule_description = nl2br($onschedule_rules_module['onschedule_description']);
										
										
											/* sms */
											if($onschedule_rules_module['onschedule_action'] == '1'){
												
												/*
												$account_sid = 'ACb2109ae2269141cc5bb29983d03dfa66'; 
												$auth_token = 'b88f54390acfa7e61d3c9b86a84ecb05'; 
												$client = new Services_Twilio($account_sid, $auth_token); 
												*/
												$userData = $this->model_user_user->getUserbyupdate($onschedule_rules_module['user_id']);
																
												if($userData['phone_number'] != 0){
													$number = $userData['phone_number'];
												}else{
													$number = '19045832155';
												}
												//$number = '19045832155';
												$text = $onschedule_description;
												$from = "+19042040577";
												
												$response = $client->messages->create(
													'+'.$number,
													array(
														'from' => '+19042040577',
														'body' => $onschedule_description
													)
												);
												
												//$response = $client->account->sms_messages->create($from,$number,$text);
											}
											
											/* Email */
											if($onschedule_rules_module['onschedule_action'] == '2'){
												
												$resultd = array();
												$resultd['notes_id'] = '';
												$resultd['highlighter_value'] = '';
												$resultd['notes_description'] = $onschedule_description;
												$resultd['date_added'] = date('j, F Y', strtotime($changedDate));
												$resultd['note_date'] = date('j, F Y', strtotime($changedDate));
												$resultd['notetime'] = date('h:i A', strtotime($taskTime));
												$resultd['username'] = $result['user_id'];
												$resultd['email'] = $user_info['email'];
												$resultd['phone_number'] = $user_info['phone_number'];
												$resultd['sms_number'] = $facility['sms_number'];
												$resultd['facility'] = $facility['facility'];
												$resultd['address'] = $facility['address'];
												$resultd['location'] = $facility['location'];
												$resultd['zipcode']= $facility['zipcode'];
												$resultd['contry_name'] = $country_info['name'];
												$resultd['zone_name'] = $zone_info['name'];
												$resultd['href'] = $this->url->link('common/login', '', 'SSL');
												
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

													$userEmail = $this->model_user_user->getUserbyupdate($onschedule_rules_module['user_id']);
													

													if($userEmail['email'] != null && $userEmail['email'] != ""){
														$mail->addAddress($userEmail['email']); 
													}else{
														$mail->addAddress('app-monitoring@noteactive.com'); 
													}
				
													$mail->WordWrap = 50;                               
													$mail->isHTML(true);                       
															 
													$mail->Subject = 'This is an Automated Alert Email.';
													
													$message33 = "";
													
													$rulevalue = date('h:i A', strtotime($taskTime));
													$message33 .= $this->emailtemplate($resultd, $rule['rules_name'], 'Daily', $rulevalue);
													
													$mail->msgHTML($message33);
													$mail->send();
												}
												
												
											}
										
										/* Notification */
										if($onschedule_rules_module['onschedule_action'] == '3'){
											if($rule['snooze_dismiss'] != '2'){
												$json['rulenotes'][] = array(
													'notes_id'    => '',
													'rules_id'    => $rule['rules_id'],
													'highlighter_value'   => '',
													'notes_description'   => $onschedule_description,
													'date_added' => date('j, F Y', strtotime($changedDate)),
													'note_date'   => date('j, F Y h:i A', strtotime($changedDate)),
													'notetime'   => date('h:i A', strtotime($taskTime)),
													'username'      => '',
													'email'      => '',
													'facility'     => '',
												);
												
												$json['total'] = '1'; 
											}
										}
										
										
										
										/* Create Task */
										if($onschedule_rules_module['onschedule_action'] == '4'){
											
											$sqls23d = "SELECT * FROM `" . DB_PREFIX . "createtask` where rules_task = '".$onschedule_rules_module['task_random_id']."' ";
												$query4d = $this->db->query($sqls23d);
												
												if($query4d->num_rows == 0){
													
													$addtaskd = array();
							
													/*if($onschedule_rules_module['taskTime'] != null && $onschedule_rules_module['taskTime'] != ""){
														$snooze_time71 = 0;
														$thestime61 = $onschedule_rules_module['taskTime'];
													}else{
														$snooze_time71 = 10;
														$thestime61 = date('H:i:s');
													}*/
													
													$snooze_time71 = 0;
													$thestime61 = date('H:i:s');
													//var_dump($thestime6);
													
													$taskTime = date("H:i:s",strtotime("+".$snooze_time71." minutes",strtotime($thestime61)));
													
													
													$date = str_replace('-', '/', $onschedule_rules_module['taskDate']);
													$res = explode("/", $date);
													$taskDate = $res[1]."-".$res[0]."-".$res[2];
														
													
													$date2 = str_replace('-', '/', $onschedule_rules_module['end_recurrence_date']);
													$res2 = explode("/", $date2);
													$end_recurrence_date = $res2[1]."-".$res2[0]."-".$res2[2];
													
													
													$addtaskd['taskDate'] = date('m-d-Y', strtotime($taskDate));
													$addtaskd['end_recurrence_date'] = date('m-d-Y', strtotime($end_recurrence_date));
													$addtaskd['recurrence'] = $onschedule_rules_module['recurrence'];
													$addtaskd['recurnce_week'] = $onschedule_rules_module['recurnce_week'];
													$addtaskd['recurnce_hrly'] = $onschedule_rules_module['recurnce_hrly'];
													$addtaskd['recurnce_month'] = $onschedule_rules_module['recurnce_month'];
													$addtaskd['recurnce_day'] = $onschedule_rules_module['recurnce_day'];
													$addtaskd['taskTime'] = $taskTime; //date('H:i:s');
													$addtaskd['endtime'] = $stime8;
													$addtaskd['description'] = $onschedule_rules_module['description'].' '.$onschedule_description;
													$addtaskd['assignto'] = '';
													$addtaskd['tasktype'] = $onschedule_rules_module['tasktype'];
													$addtaskd['numChecklist'] = $onschedule_rules_module['numChecklist'];
													$addtaskd['task_alert'] = $onschedule_rules_module['task_alert'];
													$addtaskd['alert_type_sms'] = $onschedule_rules_module['alert_type_sms'];
													$addtaskd['alert_type_notification'] = $onschedule_rules_module['alert_type_notification'];
													$addtaskd['alert_type_email'] = $onschedule_rules_module['alert_type_email'];
													$addtaskd['rules_task'] = $onschedule_rules_module['task_random_id'];
													
													$this->load->model('createtask/createtask');
													$this->model_createtask_createtask->addcreatetask($addtaskd, $facilities_id);
												}	
										}
									}
											
								}
											
								if($rule['rules_operation_recurrence'] == '2'){
									$onschedule_description = nl2br($onschedule_rules_module['onschedule_description']);
									$date = str_replace('-', '/', $searchdate);
									$res = explode("/", $date);
									$changedDate = $res[2]."-".$res[0]."-".$res[1].' '.date('H:i:s');
									
									
									$snooze_time71 = 0;
									$thestime61 = date('H:i:s');
									$taskTime = date("H:i:s",strtotime("+".$snooze_time71." minutes",strtotime($thestime61)));
									//var_dump($changedDate);
									$dailytime = date('H:i');
									
									//var_dump($dailytime);
									
									$rules_operation_time = date('H:i', strtotime($rule['rules_operation_time']));
									//var_dump($rules_operation_time);
									
									$currentDay = date('l');
									//var_dump($currentDay);
									
									$recurnce_week = $rule['recurnce_week'];
									
									if($currentDay == $recurnce_week){
										//var_dump($recurnce_week);
										//echo "<hr>";
										if($dailytime == $rules_operation_time){
											//var_dump($recurnce_week);
											//echo "<hr>";
											/* sms */
											if($onschedule_rules_module['onschedule_action'] == '1'){
												//require_once(DIR_SYSTEM . 'library/twilio-php/Services/Twilio.php');
												/*
												$account_sid = 'ACb2109ae2269141cc5bb29983d03dfa66'; 
												$auth_token = 'b88f54390acfa7e61d3c9b86a84ecb05'; 
												$client = new Services_Twilio($account_sid, $auth_token); 
												*/
												$userData = $this->model_user_user->getUserbyupdate($onschedule_rules_module['user_id']);
																
												if($userData['phone_number'] != 0){
													$number = $userData['phone_number'];
												}else{
													$number = '19045832155';
												}
												//$number = '19045832155';
												$text = $onschedule_description;
												$from = "+19042040577";
												$response = $client->messages->create(
													'+'.$number,
													array(
														'from' => '+19042040577',
														'body' => $onschedule_description
													)
												);
												
												//$response = $client->account->sms_messages->create($from,$number,$text);
											}
											
											/* Email */
											if($onschedule_rules_module['onschedule_action'] == '2'){
												
												$resultw = array();
												$resultw['notes_id'] = '';
												$resultw['highlighter_value'] = '';
												$resultw['notes_description'] = $onschedule_description;
												$resultw['date_added'] = date('j, F Y', strtotime($changedDate));
												$resultw['note_date'] = date('j, F Y', strtotime($changedDate));
												$resultw['notetime'] = date('h:i A', strtotime($taskTime));
												$resultw['username'] = $result['user_id'];
												$resultw['email'] = $user_info['email'];
												$resultw['phone_number'] = $user_info['phone_number'];
												$resultw['sms_number'] = $facility['sms_number'];
												$resultw['facility'] = $facility['facility'];
												$resultw['address'] = $facility['address'];
												$resultw['location'] = $facility['location'];
												$resultw['zipcode']= $facility['zipcode'];
												$resultw['contry_name'] = $country_info['name'];
												$resultw['zone_name'] = $zone_info['name'];
												$resultw['href'] = $this->url->link('common/login', '', 'SSL');
												
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

													$userEmail = $this->model_user_user->getUserbyupdate($onschedule_rules_module['user_id']);
													

													if($userEmail['email'] != null && $userEmail['email'] != ""){
														$mail->addAddress($userEmail['email']); 
													}else{
														$mail->addAddress('app-monitoring@noteactive.com'); 
													}
				
													$mail->WordWrap = 50;                               
													$mail->isHTML(true);                       
															 
													$mail->Subject = 'This is an Automated Alert Email.';
													
													$message33 = "";
													
													$rulevalue = date('h:i A', strtotime($rule['rules_operation_time']));
													$message33 .= $this->emailtemplate($resultd, $rule['rules_name'], 'Week', $rulevalue);
													
													$mail->msgHTML($message33);
													$mail->send();
												}
												
												
											}
											
											
											
											/* Notification */
											if($onschedule_rules_module['onschedule_action'] == '3'){
												
												if($rule['snooze_dismiss'] != '2'){
													$json['rulenotes'][] = array(
														'notes_id'    => '',
														'rules_id'    => $rule['rules_id'],
														'highlighter_value'   => '',
														'notes_description'   => $onschedule_description,
														'date_added' => date('j, F Y', strtotime($changedDate)),
														'note_date'   => date('j, F Y h:i A', strtotime($changedDate)),
														'notetime'   => date('h:i A', strtotime($taskTime)),
														'username'      => '',
														'email'      => '',
														'facility'     => '',
													);
													
													$json['total'] = '1'; 
												}
												
											}
											
											//var_dump($json['rulenotes']);
											
											/* Create Task */
											if($onschedule_rules_module['onschedule_action'] == '4'){
												
												$sqls23w = "SELECT * FROM `" . DB_PREFIX . "createtask` where rules_task = '".$onschedule_rules_module['task_random_id']."' ";
												$query4w = $this->db->query($sqls23w);
												
												if($query4w->num_rows == 0){
													
													$addtaskw = array();
							
													/*if($onschedule_rules_module['taskTime'] != null && $onschedule_rules_module['taskTime'] != ""){
														$snooze_time71 = 0;
														$thestime61 = $onschedule_rules_module['taskTime'];
													}else{
														$snooze_time71 = 10;
														$thestime61 = date('H:i:s');
													}*/
													
													$snooze_time71 = 0;
													$thestime61 = date('H:i:s');
													//var_dump($thestime6);
													
													$taskTime = date("H:i:s",strtotime("+".$snooze_time71." minutes",strtotime($thestime61)));
													
													
													$date = str_replace('-', '/', $onschedule_rules_module['taskDate']);
													$res = explode("/", $date);
													$taskDate = $res[1]."-".$res[0]."-".$res[2];
														
													
													$date2 = str_replace('-', '/', $onschedule_rules_module['end_recurrence_date']);
													$res2 = explode("/", $date2);
													$end_recurrence_date = $res2[1]."-".$res2[0]."-".$res2[2];
													
													
													$addtaskw['taskDate'] = date('m-d-Y', strtotime($taskDate));
													$addtaskw['end_recurrence_date'] = date('m-d-Y', strtotime($end_recurrence_date));
													$addtaskw['recurrence'] = $onschedule_rules_module['recurrence'];
													$addtaskw['recurnce_week'] = $onschedule_rules_module['recurnce_week'];
													$addtaskw['recurnce_hrly'] = $onschedule_rules_module['recurnce_hrly'];
													$addtaskw['recurnce_month'] = $onschedule_rules_module['recurnce_month'];
													$addtaskw['recurnce_day'] = $onschedule_rules_module['recurnce_day'];
													$addtaskw['taskTime'] = $taskTime; //date('H:i:s');
													$addtaskw['endtime'] = $stime8;
													$addtaskw['description'] = $onschedule_rules_module['description'].' '.$onschedule_description;
													$addtaskw['assignto'] = '';
													$addtaskw['tasktype'] = $onschedule_rules_module['tasktype'];
													$addtaskw['numChecklist'] = $onschedule_rules_module['numChecklist'];
													$addtaskw['task_alert'] = $onschedule_rules_module['task_alert'];
													$addtaskw['alert_type_sms'] = $onschedule_rules_module['alert_type_sms'];
													$addtaskw['alert_type_notification'] = $onschedule_rules_module['alert_type_notification'];
													$addtaskw['alert_type_email'] = $onschedule_rules_module['alert_type_email'];
													$addtaskw['rules_task'] = $onschedule_rules_module['task_random_id'];
													
													
													
													$this->load->model('createtask/createtask');
													$this->model_createtask_createtask->addcreatetask($addtaskw, $facilities_id);
													
												}
											}
											
										}
									}
									
									
								}
											
								if($rule['rules_operation_recurrence'] == '3'){
									$onschedule_description = nl2br($onschedule_rules_module['onschedule_description']);
									$date = str_replace('-', '/', $searchdate);
									$res = explode("/", $date);
									$changedDate = $res[2]."-".$res[0]."-".$res[1].' '.date('H:i:s');
									
									$snooze_time71 = 0;
									$thestime61 = date('H:i:s');
									$taskTime = date("H:i:s",strtotime("+".$snooze_time71." minutes",strtotime($thestime61)));
									
									//var_dump($changedDate);
									$dailytime = date('H:i');
									
									//var_dump($dailytime);
									
									$rules_operation_time = date('H:i', strtotime($rule['rules_operation_time']));
									//var_dump($rules_operation_time);
									
									$currentdate = date('d-m-Y');
									
									$recurnce_day = $rule['recurnce_day'];
									$currentMonth = date('m');
									$currentYear = date('Y');
									$recurnce_day_date = $recurnce_day."-".$currentMonth."-".$currentYear;
									
									
									if($currentdate == $recurnce_day_date){
										//var_dump($recurnce_day);
										//echo "<hr>";
										if($dailytime == $rules_operation_time){
											
											/* sms */
											if($onschedule_rules_module['onschedule_action'] == '1'){
												//require_once(DIR_SYSTEM . 'library/twilio-php/Services/Twilio.php');
												/*
												$account_sid = 'ACb2109ae2269141cc5bb29983d03dfa66'; 
												$auth_token = 'b88f54390acfa7e61d3c9b86a84ecb05'; 
												$client = new Services_Twilio($account_sid, $auth_token); 
												*/
												$userData = $this->model_user_user->getUserbyupdate($onschedule_rules_module['user_id']);
																
												if($userData['phone_number'] != 0){
													$number = $userData['phone_number'];
												}else{
													$number = '19045832155';
												}
												//$number = '19045832155';
												$text = $onschedule_description;
												$from = "+19042040577";
												
												$response = $client->messages->create(
													'+'.$number,
													array(
														'from' => '+19042040577',
														'body' => $onschedule_description
													)
												);
												
												//$response = $client->account->sms_messages->create($from,$number,$text);
											}
											
											/* Email */
											if($onschedule_rules_module['onschedule_action'] == '2'){
												
												$resultm = array();
												$resultm['notes_id'] = '';
												$resultm['highlighter_value'] = '';
												$resultm['notes_description'] = $onschedule_description;
												$resultm['date_added'] = date('j, F Y', strtotime($changedDate));
												$resultm['note_date'] = date('j, F Y', strtotime($changedDate));
												$resultm['notetime'] = date('h:i A', strtotime($taskTime));
												$resultm['username'] = $result['user_id'];
												$resultm['email'] = $user_info['email'];
												$resultm['phone_number'] = $user_info['phone_number'];
												$resultm['sms_number'] = $facility['sms_number'];
												$resultm['facility'] = $facility['facility'];
												$resultm['address'] = $facility['address'];
												$resultm['location'] = $facility['location'];
												$resultm['zipcode']= $facility['zipcode'];
												$resultm['contry_name'] = $country_info['name'];
												$resultm['zone_name'] = $zone_info['name'];
												$resultm['href'] = $this->url->link('common/login', '', 'SSL');
												
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

													$userEmail = $this->model_user_user->getUserbyupdate($onschedule_rules_module['user_id']);
													

													if($userEmail['email'] != null && $userEmail['email'] != ""){
														$mail->addAddress($userEmail['email']); 
													}else{
														$mail->addAddress('app-monitoring@noteactive.com'); 
													}
				
													$mail->WordWrap = 50;                               
													$mail->isHTML(true);                       
															 
													$mail->Subject = 'This is an Automated Alert Email.';
													
													$message33 = "";
													
													$rulevalue = date('h:i A', strtotime($rule['rules_operation_time']));
													$message33 .= $this->emailtemplate($resultd, $rule['rules_name'], 'Month', $rulevalue);
													
													$mail->msgHTML($message33);
													$mail->send();
												}
												
												
											}
											
											/* Notification */
											if($onschedule_rules_module['onschedule_action'] == '3'){
												if($rule['snooze_dismiss'] != '2'){
													$json['rulenotes'][] = array(
														'notes_id'    => '',
														'rules_id'    => $rule['rules_id'],
														'highlighter_value'   => '',
														'notes_description'   => $onschedule_description,
														'date_added' => date('j, F Y', strtotime($changedDate)),
														'note_date'   => date('j, F Y h:i A', strtotime($changedDate)),
														'notetime'   => date('h:i A', strtotime($taskTime)),
														'username'      => '',
														'email'      => '',
														'facility'     => '',
													);
													
													$json['total'] = '1'; 
												} 
											}
											
											//var_dump($json['rulenotes']);
											
											/* Create Task */
											if($onschedule_rules_module['onschedule_action'] == '4'){
												
												$sqls23m = "SELECT * FROM `" . DB_PREFIX . "createtask` where rules_task = '".$onschedule_rules_module['task_random_id']."' ";
												$query4m = $this->db->query($sqls23m);
												
												if($query4m->num_rows == 0){
													$addtaskm = array();
							
													/*if($onschedule_rules_module['taskTime'] != null && $onschedule_rules_module['taskTime'] != ""){
														$snooze_time71 = 0;
														$thestime61 = $onschedule_rules_module['taskTime'];
													}else{
														$snooze_time71 = 10;
														$thestime61 = date('H:i:s');
													}*/
													
													$snooze_time71 = 0;
														$thestime61 = date('H:i:s');
													//var_dump($thestime6);
													
													$taskTime = date("H:i:s",strtotime("+".$snooze_time71." minutes",strtotime($thestime61)));
													
													
													$date = str_replace('-', '/', $onschedule_rules_module['taskDate']);
													$res = explode("/", $date);
													$taskDate = $res[1]."-".$res[0]."-".$res[2];
														
													
													$date2 = str_replace('-', '/', $onschedule_rules_module['end_recurrence_date']);
													$res2 = explode("/", $date2);
													$end_recurrence_date = $res2[1]."-".$res2[0]."-".$res2[2];
													
													
													$addtaskm['taskDate'] = date('m-d-Y', strtotime($taskDate));
													$addtaskm['end_recurrence_date'] = date('m-d-Y', strtotime($end_recurrence_date));
													$addtaskm['recurrence'] = $onschedule_rules_module['recurrence'];
													$addtaskm['recurnce_week'] = $onschedule_rules_module['recurnce_week'];
													$addtaskm['recurnce_hrly'] = $onschedule_rules_module['recurnce_hrly'];
													$addtaskm['recurnce_month'] = $onschedule_rules_module['recurnce_month'];
													$addtaskm['recurnce_day'] = $onschedule_rules_module['recurnce_day'];
													$addtaskm['taskTime'] = $taskTime; //date('H:i:s');
													$addtaskm['endtime'] = $stime8;
													$addtaskm['description'] = $onschedule_rules_module['description'].' '.$onschedule_description;
													$addtaskm['assignto'] = '';
													$addtaskm['tasktype'] = $onschedule_rules_module['tasktype'];
													$addtaskm['numChecklist'] = $onschedule_rules_module['numChecklist'];
													$addtaskm['task_alert'] = $onschedule_rules_module['task_alert'];
													$addtaskm['alert_type_sms'] = $onschedule_rules_module['alert_type_sms'];
													$addtaskm['alert_type_notification'] = $onschedule_rules_module['alert_type_notification'];
													$addtaskm['alert_type_email'] = $onschedule_rules_module['alert_type_email'];
													$addtaskm['rules_task'] = $onschedule_rules_module['task_random_id'];
													
													
													$this->load->model('createtask/createtask');
													$this->model_createtask_createtask->addcreatetask($addtaskm, $facilities_id);
												}
											}
											
										}
									}
								}
											
								
								
							}
							
						}
						
						}
						
						
						if($config_rules_status == '1'){
						
						if($rule['rules_operation'] == '1'){
							
							$andrulesValues = array();
							$andrulesTaskValues = array();
							$andrulesActionValues = array();
							$andrulesActionValues2 = array();
							
							foreach($rule['rules_module'] as $rules_module){
								$rowModule = array();
								/*   highlighter  */
								
								if($rule['rules_operator'] == '1'){
								
									if($rules_module['highlighter_id'] != null && $rules_module['highlighter_id'] != ""){
										$andrulesValues['highlighter_id'] = $rules_module['highlighter_id'];
										
										$andrulesActionValues2[$rules_module['highlighter_id']] = $rules_module['action'];
										
										$andrulesTaskValues[$rules_module['highlighter_id']]['taskDate'] = $rules_module['taskDate'];
										$andrulesTaskValues[$rules_module['highlighter_id']]['recurrence'] = $rules_module['recurrence'];
										$andrulesTaskValues[$rules_module['highlighter_id']]['recurnce_week'] = $rules_module['recurnce_week'];
										$andrulesTaskValues[$rules_module['highlighter_id']]['recurnce_hrly'] = $rules_module['recurnce_hrly'];
										$andrulesTaskValues[$rules_module['highlighter_id']]['recurnce_month'] = $rules_module['recurnce_month'];
										$andrulesTaskValues[$rules_module['highlighter_id']]['recurnce_day'] = $rules_module['recurnce_day'];
										$andrulesTaskValues[$rules_module['highlighter_id']]['end_recurrence_date'] = $rules_module['end_recurrence_date'];
										$andrulesTaskValues[$rules_module['highlighter_id']]['taskTime'] = $rules_module['taskTime'];
										$andrulesTaskValues[$rules_module['highlighter_id']]['endtime'] = $rules_module['endtime'];
										$andrulesTaskValues[$rules_module['highlighter_id']]['tasktype'] = $rules_module['tasktype'];
										$andrulesTaskValues[$rules_module['highlighter_id']]['numChecklist'] = $rules_module['numChecklist'];
										$andrulesTaskValues[$rules_module['highlighter_id']]['task_alert'] = $rules_module['task_alert'];
										$andrulesTaskValues[$rules_module['highlighter_id']]['alert_type_sms'] = $rules_module['alert_type_sms'];
										$andrulesTaskValues[$rules_module['highlighter_id']]['alert_type_notification'] = $rules_module['alert_type_notification'];
										$andrulesTaskValues[$rules_module['highlighter_id']]['alert_type_email'] = $rules_module['alert_type_email'];
										$andrulesTaskValues[$rules_module['highlighter_id']]['description'] = $rules_module['description'];
									}
											
									if($rules_module['keyword_id'] != null && $rules_module['keyword_id'] != ""){
										$querya = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "keyword WHERE keyword_name = '" . $rules_module['keyword_id'] . "'");
					
										$active_tagdata = $querya->row;
										$andrulesValues['keyword_image'] =	$active_tagdata['keyword_image'];

										$andrulesActionValues2[$active_tagdata['keyword_image']] = $rules_module['action'];
										
										$andrulesTaskValues[$active_tagdata['keyword_image']]['taskDate'] = $rules_module['taskDate'];
										$andrulesTaskValues[$active_tagdata['keyword_image']]['recurrence'] = $rules_module['recurrence'];
										$andrulesTaskValues[$active_tagdata['keyword_image']]['recurnce_week'] = $rules_module['recurnce_week'];
										$andrulesTaskValues[$active_tagdata['keyword_image']]['recurnce_hrly'] = $rules_module['recurnce_hrly'];
										$andrulesTaskValues[$active_tagdata['keyword_image']]['recurnce_month'] = $rules_module['recurnce_month'];
										$andrulesTaskValues[$active_tagdata['keyword_image']]['recurnce_day'] = $rules_module['recurnce_day'];
										$andrulesTaskValues[$active_tagdata['keyword_image']]['end_recurrence_date'] = $rules_module['end_recurrence_date'];
										$andrulesTaskValues[$active_tagdata['keyword_image']]['taskTime'] = $rules_module['taskTime'];
										$andrulesTaskValues[$active_tagdata['keyword_image']]['endtime'] = $rules_module['endtime'];
										$andrulesTaskValues[$active_tagdata['keyword_image']]['tasktype'] = $rules_module['tasktype'];
										$andrulesTaskValues[$active_tagdata['keyword_image']]['numChecklist'] = $rules_module['numChecklist'];
										$andrulesTaskValues[$active_tagdata['keyword_image']]['task_alert'] = $rules_module['task_alert'];
										$andrulesTaskValues[$active_tagdata['keyword_image']]['alert_type_sms'] = $rules_module['alert_type_sms'];
										$andrulesTaskValues[$active_tagdata['keyword_image']]['alert_type_notification'] = $rules_module['alert_type_notification'];
										$andrulesTaskValues[$active_tagdata['keyword_image']]['alert_type_email'] = $rules_module['alert_type_email'];
										$andrulesTaskValues[$active_tagdata['keyword_image']]['description'] = $rules_module['description'];
											
									}
											
									if($rules_module['color_id'] != null && $rules_module['color_id'] != ""){
										
										$andrulesValues['color_id'] =	$rules_module['color_id'];
										
										$andrulesActionValues2[$rules_module['color_id']] = $rules_module['action'];
										
										$andrulesTaskValues[$rules_module['color_id']]['taskDate'] = $rules_module['taskDate'];
										$andrulesTaskValues[$rules_module['color_id']]['recurrence'] = $rules_module['recurrence'];
										$andrulesTaskValues[$rules_module['color_id']]['recurnce_week'] = $rules_module['recurnce_week'];
										$andrulesTaskValues[$rules_module['color_id']]['recurnce_hrly'] = $rules_module['recurnce_hrly'];
										$andrulesTaskValues[$rules_module['color_id']]['recurnce_month'] = $rules_module['recurnce_month'];
										$andrulesTaskValues[$rules_module['color_id']]['recurnce_day'] = $rules_module['recurnce_day'];
										$andrulesTaskValues[$rules_module['color_id']]['end_recurrence_date'] = $rules_module['end_recurrence_date'];
										$andrulesTaskValues[$rules_module['color_id']]['taskTime'] = $rules_module['taskTime'];
										$andrulesTaskValues[$rules_module['color_id']]['endtime'] = $rules_module['endtime'];
										$andrulesTaskValues[$rules_module['color_id']]['tasktype'] = $rules_module['tasktype'];
										$andrulesTaskValues[$rules_module['color_id']]['numChecklist'] = $rules_module['numChecklist'];
										$andrulesTaskValues[$rules_module['color_id']]['task_alert'] = $rules_module['task_alert'];
										$andrulesTaskValues[$rules_module['color_id']]['alert_type_sms'] = $rules_module['alert_type_sms'];
										$andrulesTaskValues[$rules_module['color_id']]['alert_type_notification'] = $rules_module['alert_type_notification'];
										$andrulesTaskValues[$rules_module['color_id']]['alert_type_email'] = $rules_module['alert_type_email'];
										$andrulesTaskValues[$rules_module['color_id']]['description'] = $rules_module['description'];
									}
										
									if($rules_module['keyword_search'] != null && $rules_module['keyword_search'] != ""){
										$andrulesValues['keyword_search'] =	$rules_module['keyword_search'];
										$andrulesActionValues2[$rules_module['keyword_search']] = $rules_module['action'];
										
										$andrulesTaskValues[$rules_module['keyword_search']]['taskDate'] = $rules_module['taskDate'];
										$andrulesTaskValues[$rules_module['keyword_search']]['recurrence'] = $rules_module['recurrence'];
										$andrulesTaskValues[$rules_module['keyword_search']]['recurnce_week'] = $rules_module['recurnce_week'];
										$andrulesTaskValues[$rules_module['keyword_search']]['recurnce_hrly'] = $rules_module['recurnce_hrly'];
										$andrulesTaskValues[$rules_module['keyword_search']]['recurnce_month'] = $rules_module['recurnce_month'];
										$andrulesTaskValues[$rules_module['keyword_search']]['recurnce_day'] = $rules_module['recurnce_day'];
										$andrulesTaskValues[$rules_module['keyword_search']]['end_recurrence_date'] = $rules_module['end_recurrence_date'];
										$andrulesTaskValues[$rules_module['keyword_search']]['taskTime'] = $rules_module['taskTime'];
										$andrulesTaskValues[$rules_module['keyword_search']]['endtime'] = $rules_module['endtime'];
										$andrulesTaskValues[$rules_module['keyword_search']]['tasktype'] = $rules_module['tasktype'];
										$andrulesTaskValues[$rules_module['keyword_search']]['numChecklist'] = $rules_module['numChecklist'];
										$andrulesTaskValues[$rules_module['keyword_search']]['task_alert'] = $rules_module['task_alert'];
										$andrulesTaskValues[$rules_module['keyword_search']]['alert_type_sms'] = $rules_module['alert_type_sms'];
										$andrulesTaskValues[$rules_module['keyword_search']]['alert_type_notification'] = $rules_module['alert_type_notification'];
										$andrulesTaskValues[$rules_module['keyword_search']]['alert_type_email'] = $rules_module['alert_type_email'];
										$andrulesTaskValues[$rules_module['keyword_search']]['description'] = $rules_module['description'];
									}
									foreach($rules_module['action'] as $action){
									$andrulesActionValues[] = $action;
									}
											
								}
								
							if($rule['rules_operator'] == '2'){	
								if($rules_module['rules_type'] == '1'){
									
									if($rules_module['highlighter_id'] != null && $rules_module['highlighter_id'] != ""){
										$sql = "SELECT  notes_id, emp_tag_id, facilities_id, notes_description, highlighter_id, date_added, user_id, notetime, note_date, snooze_time,send_sms,send_email  FROM `" . DB_PREFIX . "notes`";
										
										$sql .= 'where 1 = 1 ';
										
										$sql .= " and highlighter_id = '".$rules_module['highlighter_id']."'";
										$sql .= " and facilities_id = '".$facility['facilities_id']."'";
										
										$sql .= " and `snooze_dismiss` != '2' ";
										
										$date = str_replace('-', '/', $searchdate);
										$res = explode("/", $date);
										$changedDate = $res[2]."-".$res[0]."-".$res[1];
										
										$startDate = $changedDate;
										$endDate = $changedDate;
											
										$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
											
										
										
										$sql .= " and status = '1' ORDER BY notetime DESC  ";
										
										//echo $sql;
										//echo "<hr>";
										 
										$query = $this->db->query($sql);
										//var_dump($query->num_rows);
										//echo "<hr>";
										if ($query->num_rows) {
											//var_dump($query->rows);
											//echo "<hr>";
											foreach($query->rows as $result){
												$user_info = $this->model_user_user->getUserByUsername($result['user_id']);
												$highlighterData = $this->model_setting_highlighter->gethighlighter($result['highlighter_id']);
												
												if(in_array('3', $rules_module['action'])){
													$notesIds[] = $result['notes_id'];
												}
												
												
												if($result['send_sms'] == '0'){
													if(in_array('1', $rules_module['action'])){
														//require_once(DIR_SYSTEM . 'library/twilio-php/Services/Twilio.php');

														/*$account_sid = 'ACb2109ae2269141cc5bb29983d03dfa66'; 
														$auth_token = 'b88f54390acfa7e61d3c9b86a84ecb05'; 
														$client = new Services_Twilio($account_sid, $auth_token); 
														*/
										 
														$message = "Rules Created \n";
														$message .= date('h:i A', strtotime($result['notetime']))."\n";
														$message .= $rule['rules_name'] .'-Highlighter-'.$highlighterData['highlighter_name']."\n";
														$message .= $result['notes_description'];
														
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
														
														//$response = $client->account->sms_messages->create($from,$number,$text);
														
														$sql3e = "UPDATE `" . DB_PREFIX . "notes` SET send_sms = '1' WHERE notes_id = '".$result['notes_id']."'";			
														$query = $this->db->query($sql3e);
													}
												}
												
												if($result['send_email'] == '0'){
													if(in_array('2', $rules_module['action'])){
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
														$facilityDetails['rules_type'] = 'Highlighter';
														$facilityDetails['rules_value'] = $highlighterData['highlighter_name'];
														
															
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

															if($user_info['email'] != null && $user_info['email'] != ""){
																$mail->addAddress($user_info['email']);
															}else{
																$mail->addAddress('app-monitoring@noteactive.com'); 
															}
															$mail->WordWrap = 50;                               
															$mail->isHTML(true);                       
																								 
															$mail->Subject = 'This is an Automated Alert Email.';
																						
															$message33 = "";
																						
															$message33 .= $this->sendEmailtemplate($result, $rule['rules_name'], 'Highlighter', $highlighterData['highlighter_name'], $facilityDetails);
															
															$mail->msgHTML($message33);
															$mail->send();
															
															$sql3e = "UPDATE `" . DB_PREFIX . "notes` SET send_email = '1' WHERE notes_id = '".$result['notes_id']."'";			
															$query = $this->db->query($sql3e);
														}
													}
												}
												
												
												if(in_array('4', $rules_module['action'])){
													$tnotesIds[] = $result['notes_id'];
													$rowModule['taskDate'] = $rules_module['taskDate'];
													$rowModule['recurrence'] = $rules_module['recurrence'];
													$rowModule['recurnce_week'] = $rules_module['recurnce_week'];
													$rowModule['recurnce_hrly'] = $rules_module['recurnce_hrly'];
													$rowModule['recurnce_month'] = $rules_module['recurnce_month'];
													$rowModule['recurnce_day'] = $rules_module['recurnce_day'];
													$rowModule['end_recurrence_date'] = $rules_module['end_recurrence_date'];
													$rowModule['taskTime'] = $rules_module['taskTime'];
													$rowModule['endtime'] = $rules_module['endtime'];
													$rowModule['tasktype'] = $rules_module['tasktype'];
													$rowModule['numChecklist'] = $rules_module['numChecklist'];
													$rowModule['task_alert'] = $rules_module['task_alert'];
													$rowModule['alert_type_sms'] = $rules_module['alert_type_sms'];
													$rowModule['alert_type_notification'] = $rules_module['alert_type_notification'];
													$rowModule['alert_type_email'] = $rules_module['alert_type_email'];
													$rowModule['description'] = $rules_module['description'];
												}
												
											
											}
											
											
										}
									}
									
								}
								
								//var_dump($json);
								
								if($rules_module['rules_type'] == '2'){
								
									if($rules_module['keyword_id'] != null && $rules_module['keyword_id'] != ""){
										$querya = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "keyword WHERE keyword_name = '" . $rules_module['keyword_id'] . "'");
				
										$active_tagdata = $querya->row;
										
										
										$sql2 = "SELECT notes_id, emp_tag_id, facilities_id, notes_description, highlighter_id, date_added, user_id, notetime, note_date, snooze_time,send_sms,send_email FROM `" . DB_PREFIX . "notes`";
										
										$sql2 .= 'where 1 = 1 ';
										
										$sql2 .= " and keyword_file = '".$active_tagdata['keyword_image']."'";
										
										$sql2 .= " and facilities_id = '".$facility['facilities_id']."'";
										
										$sql2 .= " and `snooze_dismiss` != '2' ";
										
										$date = str_replace('-', '/', $searchdate);
										$res = explode("/", $date);
										$changedDate = $res[2]."-".$res[0]."-".$res[1];
										
										$startDate = $changedDate;
										$endDate = $changedDate;
											
										$sql2 .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
											
										
										
										$sql2 .= " and status = '1' ORDER BY notetime DESC  ";
										
										//echo $sql;
										//echo "<hr>";
										
										$query = $this->db->query($sql2);
										//var_dump($query->rows);
										//echo "<hr>";
										if ($query->num_rows) {
											
											foreach($query->rows as $result){
												$user_info = $this->model_user_user->getUserByUsername($result['user_id']);
												if(in_array('3', $rules_module['action'])){
													$notesIds[] = $result['notes_id'];
												}
												
												
												if($result['send_sms'] == '0'){
													if(in_array('1', $rules_module['action'])){
														//require_once(DIR_SYSTEM . 'library/twilio-php/Services/Twilio.php');

														/*
														$account_sid = 'ACb2109ae2269141cc5bb29983d03dfa66'; 
														$auth_token = 'b88f54390acfa7e61d3c9b86a84ecb05'; 
														$client = new Services_Twilio($account_sid, $auth_token); */
										 
														$message = "Rules Created \n";
														$message .= date('h:i A', strtotime($result['notetime']))."\n";
														$message .= $rule['rules_name'] .'-ActiveNote-'.$rules_module['keyword_id']."\n";
														$message .= $result['notes_description'];
														
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
														
														//$response = $client->account->sms_messages->create($from,$number,$text);
														
														$sql3e = "UPDATE `" . DB_PREFIX . "notes` SET send_sms = '1' WHERE notes_id = '".$result['notes_id']."'";			
														$query = $this->db->query($sql3e);
													}
												}
												
												if($result['send_email'] == '0'){
													if(in_array('2', $rules_module['action'])){
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
														$facilityDetails['rules_type'] = 'ActiveNote';
														$facilityDetails['rules_value'] = $highlighterData['highlighter_name'];
														
															
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

															if($user_info['email'] != null && $user_info['email'] != ""){
																$mail->addAddress($user_info['email']);
															}else{
																$mail->addAddress('app-monitoring@noteactive.com'); 
															}
													
															$mail->WordWrap = 50;                               
															$mail->isHTML(true);                       
																								 
															$mail->Subject = 'This is an Automated Alert Email.';
																						
															$message33 = "";
																						
															$message33 .= $this->sendEmailtemplate($result, $rule['rules_name'], 'Highlighter', $rules_module['keyword_id'], $facilityDetails);
															
															$mail->msgHTML($message33);
															$mail->send();
															
															$sql3e = "UPDATE `" . DB_PREFIX . "notes` SET send_email = '1' WHERE notes_id = '".$result['notes_id']."'";			
															$query = $this->db->query($sql3e);
														}
													}
												}
												
												
												if(in_array('4', $rules_module['action'])){
													$tnotesIds[] = $result['notes_id'];
													
													$rowModule['taskDate'] = $rules_module['taskDate'];
													$rowModule['recurrence'] = $rules_module['recurrence'];
													$rowModule['recurnce_week'] = $rules_module['recurnce_week'];
													$rowModule['recurnce_hrly'] = $rules_module['recurnce_hrly'];
													$rowModule['recurnce_month'] = $rules_module['recurnce_month'];
													$rowModule['recurnce_day'] = $rules_module['recurnce_day'];
													$rowModule['end_recurrence_date'] = $rules_module['end_recurrence_date'];
													$rowModule['taskTime'] = $rules_module['taskTime'];
													$rowModule['endtime'] = $rules_module['endtime'];
													$rowModule['tasktype'] = $rules_module['tasktype'];
													$rowModule['numChecklist'] = $rules_module['numChecklist'];
													$rowModule['task_alert'] = $rules_module['task_alert'];
													$rowModule['alert_type_sms'] = $rules_module['alert_type_sms'];
													$rowModule['alert_type_notification'] = $rules_module['alert_type_notification'];
													$rowModule['alert_type_email'] = $rules_module['alert_type_email'];
													$rowModule['description'] = $rules_module['description'];
												}
											}
											
										}
									}
								}
								
								if($rules_module['rules_type'] == '3'){
									//var_dump($rules_module['color_id']);
									if($rules_module['color_id'] != null && $rules_module['color_id'] != ""){
										$sql3 = "SELECT notes_id, emp_tag_id, facilities_id, notes_description, highlighter_id, date_added, user_id, notetime, note_date, snooze_time,send_sms,send_email FROM `" . DB_PREFIX . "notes`";
										
										$sql3 .= 'where 1 = 1 ';
										
										$sql3 .= " and text_color = '#".$rules_module['color_id']."'";
										$sql3 .= " and facilities_id = '".$facility['facilities_id']."'";
										
										$sql3 .= " and `snooze_dismiss` != '2' ";
										
										$date = str_replace('-', '/', $searchdate);
										$res = explode("/", $date);
										$changedDate = $res[2]."-".$res[0]."-".$res[1];
										
										$startDate = $changedDate;
										$endDate = $changedDate;
											
										$sql3 .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
											
										
										
										$sql3 .= " and status = '1' ORDER BY notetime DESC  ";
										
										//echo $sql3;
										//echo "<hr>";
										
										$query = $this->db->query($sql3);
										
										if ($query->num_rows) {
											//var_dump($query->rows);
											//echo "<hr>";
											
											foreach($query->rows as $result){
												$user_info = $this->model_user_user->getUserByUsername($result['user_id']);
												if(in_array('3', $rules_module['action'])){
													$notesIds[] = $result['notes_id'];
												}
												
												if($rules_module['color_id'] == '008000'){
														$color_id = "Green";
													}
													if($rules_module['color_id'] == 'FF0000'){
														$color_id = "Red";
													}
													if($rules_module['color_id'] == '0000FF'){
														$color_id = "Blue";
													}
													if($rules_module['color_id'] == '000000'){
														$color_id = "Black";
													}
												
												if($result['send_sms'] == '0'){
													if(in_array('1', $rules_module['action'])){
														//require_once(DIR_SYSTEM . 'library/twilio-php/Services/Twilio.php');

														/*
														$account_sid = 'ACb2109ae2269141cc5bb29983d03dfa66'; 
														$auth_token = 'b88f54390acfa7e61d3c9b86a84ecb05'; 
														$client = new Services_Twilio($account_sid, $auth_token); */
										 
														$message = "Rules Created \n";
														$message .= date('h:i A', strtotime($result['notetime']))."\n";
														$message .= $rule['rules_name'] .'-Color-'.$color_id."\n";
														$message .= $result['notes_description'];
														
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
														
														//$response = $client->account->sms_messages->create($from,$number,$text);
														
														$sql3e = "UPDATE `" . DB_PREFIX . "notes` SET send_sms = '1' WHERE notes_id = '".$result['notes_id']."'";			
														$query = $this->db->query($sql3e);
													}
												}
												
												if($result['send_email'] == '0'){
													if(in_array('2', $rules_module['action'])){
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
														$facilityDetails['rules_type'] = 'Color';
														$facilityDetails['rules_value'] = $color_id;
														
															
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

															if($user_info['email'] != null && $user_info['email'] != ""){
																$mail->addAddress($user_info['email']);
															}else{
																$mail->addAddress('app-monitoring@noteactive.com'); 
															}
													
															$mail->WordWrap = 50;                               
															$mail->isHTML(true);                       
																								 
															$mail->Subject = 'This is an Automated Alert Email.';
																						
															$message33 = "";
																						
															$message33 .= $this->sendEmailtemplate($result, $rule['rules_name'], 'Highlighter', $rules_module['keyword_id'], $facilityDetails);
															
															$mail->msgHTML($message33);
															$mail->send();
															
															$sql3e = "UPDATE `" . DB_PREFIX . "notes` SET send_email = '1' WHERE notes_id = '".$result['notes_id']."'";			
															$query = $this->db->query($sql3e);
														}
													}
												}
												
												
												if(in_array('4', $rules_module['action'])){
													$tnotesIds[] = $result['notes_id'];
													
													$rowModule['taskDate'] = $rules_module['taskDate'];
													$rowModule['recurrence'] = $rules_module['recurrence'];
													$rowModule['recurnce_week'] = $rules_module['recurnce_week'];
													$rowModule['recurnce_hrly'] = $rules_module['recurnce_hrly'];
													$rowModule['recurnce_month'] = $rules_module['recurnce_month'];
													$rowModule['recurnce_day'] = $rules_module['recurnce_day'];
													$rowModule['end_recurrence_date'] = $rules_module['end_recurrence_date'];
													$rowModule['taskTime'] = $rules_module['taskTime'];
													$rowModule['endtime'] = $rules_module['endtime'];
													$rowModule['tasktype'] = $rules_module['tasktype'];
													$rowModule['numChecklist'] = $rules_module['numChecklist'];
													$rowModule['task_alert'] = $rules_module['task_alert'];
													$rowModule['alert_type_sms'] = $rules_module['alert_type_sms'];
													$rowModule['alert_type_notification'] = $rules_module['alert_type_notification'];
													$rowModule['alert_type_email'] = $rules_module['alert_type_email'];
													$rowModule['description'] = $rules_module['description'];
												}
													
											}
											
											
											
										}
									}
									
								}
								
								/*if($rules_module['rules_type'] == '4'){
									$data4 = array(
										'searchdate' => $searchdate,
										'task_id' => $rules_module['task_id'],
										'searchdate_app' => '1',
									);
									//var_dump($data4);
									$results = $this->model_notes_notes->getnotess($data4);
								}*/
								
								if($rules_module['rules_type'] == '5'){
									//var_dump($rules_module['keyword_search']);
									if($rules_module['keyword_search'] != null && $rules_module['keyword_search'] != ""){
										$sqls = "SELECT notes_id, emp_tag_id, facilities_id, notes_description, highlighter_id, date_added, user_id, notetime, note_date, snooze_time,send_sms,send_email FROM `" . DB_PREFIX . "notes`";
										
										$sqls .= 'where 1 = 1 ';
										
										$sqls .= " and LOWER(notes_description) like '%".strtolower($rules_module['keyword_search'])."%'";
										$sqls .= " and facilities_id = '".$facility['facilities_id']."'";
										
										$sqls .= " and `snooze_dismiss` != '2' ";
										
										$date = str_replace('-', '/', $searchdate);
										$res = explode("/", $date);
										$changedDate = $res[2]."-".$res[0]."-".$res[1];
										
										$startDate = $changedDate;
										$endDate = $changedDate;
											
										$sqls .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
											
										
										
										$sqls .= " and status = '1' ORDER BY notetime DESC  ";
										
										//echo $sqls;
										//echo "<hr>";
										
										$query = $this->db->query($sqls);
										
										if ($query->num_rows) {
											//var_dump($query->rows);
											//echo "<hr>";
											
											foreach($query->rows as $result){
												if(in_array('3', $rules_module['action'])){
													$notesIds[] = $result['notes_id'];
												}
												
												if($result['send_sms'] == '0'){
													if(in_array('1', $rules_module['action'])){
														//require_once(DIR_SYSTEM . 'library/twilio-php/Services/Twilio.php');

														/*
														$account_sid = 'ACb2109ae2269141cc5bb29983d03dfa66'; 
														$auth_token = 'b88f54390acfa7e61d3c9b86a84ecb05'; 
														$client = new Services_Twilio($account_sid, $auth_token); */
										 
														$message = "Rules Created \n";
														$message .= date('h:i A', strtotime($result['notetime']))."\n";
														$message .= $rule['rules_name'] .'-Keyword-'.$rules_module['keyword_search']."\n";
														$message .= $result['notes_description'];
														
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
														
														//$response = $client->account->sms_messages->create($from,$number,$text);
														
														$sql3e = "UPDATE `" . DB_PREFIX . "notes` SET send_sms = '1' WHERE notes_id = '".$result['notes_id']."'";			
														$query = $this->db->query($sql3e);
													}
												}
												
												if($result['send_email'] == '0'){
													if(in_array('2', $rules_module['action'])){
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
														$facilityDetails['rules_type'] = 'Keyword';
														$facilityDetails['rules_value'] = $rules_module['keyword_search'];
														
															
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

															if($user_info['email'] != null && $user_info['email'] != ""){
																$mail->addAddress($user_info['email']);
															}else{
																$mail->addAddress('app-monitoring@noteactive.com'); 
															}
													
															$mail->WordWrap = 50;                               
															$mail->isHTML(true);                       
																								 
															$mail->Subject = 'This is an Automated Alert Email.';
																						
															$message33 = "";
																						
															$message33 .= $this->sendEmailtemplate($result, $rule['rules_name'], 'Highlighter', $rules_module['keyword_id'], $facilityDetails);
															
															$mail->msgHTML($message33);
															$mail->send();
															
															$sql3e = "UPDATE `" . DB_PREFIX . "notes` SET send_email = '1' WHERE notes_id = '".$result['notes_id']."'";			
															$query = $this->db->query($sql3e);
														}
													}
												}
												
												if(in_array('4', $rules_module['action'])){
													$tnotesIds[] = $result['notes_id'];
													
													$rowModule['taskDate'] = $rules_module['taskDate'];
													$rowModule['recurrence'] = $rules_module['recurrence'];
													$rowModule['recurnce_week'] = $rules_module['recurnce_week'];
													$rowModule['recurnce_hrly'] = $rules_module['recurnce_hrly'];
													$rowModule['recurnce_month'] = $rules_module['recurnce_month'];
													$rowModule['recurnce_day'] = $rules_module['recurnce_day'];
													$rowModule['end_recurrence_date'] = $rules_module['end_recurrence_date'];
													$rowModule['taskTime'] = $rules_module['taskTime'];
													$rowModule['endtime'] = $rules_module['endtime'];
													$rowModule['tasktype'] = $rules_module['tasktype'];
													$rowModule['numChecklist'] = $rules_module['numChecklist'];
													$rowModule['task_alert'] = $rules_module['task_alert'];
													$rowModule['alert_type_sms'] = $rules_module['alert_type_sms'];
													$rowModule['alert_type_notification'] = $rules_module['alert_type_notification'];
													$rowModule['alert_type_email'] = $rules_module['alert_type_email'];
													$rowModule['description'] = $rules_module['description'];
												}
											}
										}
									}
								}
							
							}
								//var_dump($results);
								//$keyarrays[] = $rule['rules_module'];
								//var_dump($rule['rules_module']);
								//echo "<hr>";
							}
							
							
							if(!empty($andrulesValues)){
								//var_dump($andrulesActionValues);
								//echo "<hr>";
								//$andrulesActionValues = array_unique($andrulesActionValues);
								
								//var_dump($andrulesActionValues);
								//echo "<hr>";
								
										$sql = "SELECT n.* FROM `" . DB_PREFIX . "notes` n ";
										
										if($andrulesValues['keyword_image'] != null && $andrulesValues['keyword_image'] != ""){
											$sql .= "left JOIN " . DB_PREFIX . "notes_by_keyword nk on nk.notes_id=n.notes_id  ";
										}
										
										$sql .= 'where 1 = 1 ';
										
										if($andrulesValues['highlighter_id'] != null && $andrulesValues['highlighter_id'] != ""){
										$sql .= " and n.highlighter_id = '".$andrulesValues['highlighter_id']."'";
										
										}
										
										if($andrulesValues['keyword_image'] != null && $andrulesValues['keyword_image'] != ""){
										
										$sql .= " and nk.keyword_file = '".$andrulesValues['keyword_image']."'";
										
										}
										
										if($andrulesValues['color_id'] != null && $andrulesValues['color_id'] != ""){
											
											$sql .= " and n.text_color = '#".$andrulesValues['color_id']."'";
										}
										
										if($andrulesValues['keyword_search'] != null && $andrulesValues['keyword_search'] != ""){
											$sql .= " and LOWER(n.notes_description) like '%".strtolower($andrulesValues['keyword_search'])."%'";
										}
										
										$sql .= " and n.facilities_id = '".$facility['facilities_id']."'";
										$sql .= " and n.`snooze_dismiss` != '2' ";
										
										$date = str_replace('-', '/', $searchdate);
										$res = explode("/", $date);
										$changedDate = $res[2]."-".$res[0]."-".$res[1];
										
										$startDate = $changedDate;
										$endDate = $changedDate;
											
										$sql .= " and (n.`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
											
										
										
										$sql .= " and n.status = '1' ORDER BY n.notetime DESC  ";
										
										//echo "<hr>";
										//echo $sql;
										//echo "<hr>";
										 
										$query = $this->db->query($sql);
										//var_dump($query->num_rows);
										
										//die;
										//echo "<hr>";
										if ($query->num_rows) {
											//var_dump($query->rows);
											//echo "<hr>";
											
											foreach($query->rows as $result){
												$user_info = $this->model_user_user->getUserByUsername($result['user_id']);
												
												if($andrulesValues['highlighter_id'] != null && $andrulesValues['highlighter_id'] != ""){
													$highlighterData = $this->model_setting_highlighter->gethighlighter($result['highlighter_id']);
												}
												
												if(in_array('3', $andrulesActionValues)){
													$notesIds[] = $result['notes_id'];
													
													
														$nrulesvalue = "";
														
														if($andrulesValues['highlighter_id'] != null && $andrulesValues['highlighter_id'] != ""){
															
															if(in_array('3', $andrulesActionValues2[$andrulesValues['highlighter_id']])){
															
															$nrulesvalue .= 'Highlighter: '.$highlighterData['highlighter_name'].' and ';
															}
														}
														
														
														if($andrulesValues['keyword_image'] != null && $andrulesValues['keyword_image'] != ""){
															
															$querya = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "keyword WHERE keyword_image = '" . $andrulesValues['keyword_image'] . "'");
				
															$active_tagdata = $querya->row;
															
															if(in_array('3', $andrulesActionValues2[$andrulesValues['keyword_image']])){
															
															$nrulesvalue .= ' ActiveNote: '.$active_tagdata['keyword_name'].' and ';
															}
														
														}
														
														if($andrulesValues['color_id'] != null && $andrulesValues['color_id'] != ""){
															
															if($andrulesValues['color_id'] == '008000'){
																$color_id = "Green";
															}
															if($andrulesValues['color_id'] == 'FF0000'){
																$color_id = "Red";
															}
															if($andrulesValues['color_id'] == '0000FF'){
																$color_id = "Blue";
															}
															if($andrulesValues['color_id'] == '000000'){
																$color_id = "Black";
															}
															if(in_array('3', $andrulesActionValues2[$andrulesValues['color_id']])){
															
															$nrulesvalue .= ' Color: '.$color_id.' and ';
															}
														}
														
														if($andrulesValues['keyword_search'] != null && $andrulesValues['keyword_search'] != ""){
															if(in_array('3', $andrulesActionValues2[$andrulesValues['keyword_search']])){
															
															$nrulesvalue .= ' Keyword: '.$andrulesValues['keyword_search'].' ';
															}
														}
														
														//var_dump($nrulesvalue);
														
														$andRuleArray[$result['notes_id']] = $nrulesvalue;
														
														
												}
												
												
												if($result['send_sms'] == '0'){
													if(in_array('1', $andrulesActionValues)){
														
														//require_once(DIR_SYSTEM . 'library/twilio-php/Services/Twilio.php');

														/*$account_sid = 'ACb2109ae2269141cc5bb29983d03dfa66'; 
														$auth_token = 'b88f54390acfa7e61d3c9b86a84ecb05'; 
														$client = new Services_Twilio($account_sid, $auth_token); 
														*/
										 
														$message = "Rules Created \n";
														$message .= date('h:i A', strtotime($result['notetime']))."\n";
														
														
														$message .= $rule['rules_name'];
														
														if($andrulesValues['highlighter_id'] != null && $andrulesValues['highlighter_id'] != ""){
															
															if(in_array('1', $andrulesActionValues2[$andrulesValues['highlighter_id']])){
															$message .= ' -Highlighter- '.$highlighterData['highlighter_name']."\n";
															}
														}
														
														if($andrulesValues['keyword_image'] != null && $andrulesValues['keyword_image'] != ""){
															
															$querya = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "keyword WHERE keyword_image = '" . $andrulesValues['keyword_image'] . "'");
				
															$active_tagdata = $querya->row;
															
															if(in_array('1', $andrulesActionValues2[$andrulesValues['keyword_image']])){
															$message .= ' -ActiveNote- '.$active_tagdata['keyword_name']."\n";
															}
														
														}
														
														if($andrulesValues['color_id'] != null && $andrulesValues['color_id'] != ""){
															
															if($andrulesValues['color_id'] == '008000'){
																$color_id = "Green";
															}
															if($andrulesValues['color_id'] == 'FF0000'){
																$color_id = "Red";
															}
															if($andrulesValues['color_id'] == '0000FF'){
																$color_id = "Blue";
															}
															if($andrulesValues['color_id'] == '000000'){
																$color_id = "Black";
															}
															if(in_array('1', $andrulesActionValues2[$andrulesValues['color_id']])){
															$message .= ' -Color- '.$color_id."\n";
															}
														}
														
														if($andrulesValues['keyword_search'] != null && $andrulesValues['keyword_search'] != ""){
															if(in_array('1', $andrulesActionValues2[$andrulesValues['keyword_search']])){
															$message .= ' -Keyword- '.$andrulesValues['keyword_search']."\n";
															}
														}
														
														$message .= $result['notes_description'];
														
														if($user_info['phone_number'] != null && $user_info['phone_number'] != '0'){
															$phone_number = $user_info['phone_number'];
														}else{
															$phone_number = '19045832155';
														}
														
														//var_dump($message);
														
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
														
														//$response = $client->account->sms_messages->create($from,$number,$text);
														
														$sql3e = "UPDATE `" . DB_PREFIX . "notes` SET send_sms = '1' WHERE notes_id = '".$result['notes_id']."'";			
														$query = $this->db->query($sql3e);
													}
												}
												
												if($result['send_email'] == '0'){
													if(in_array('2', $andrulesActionValues)){
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
														
														
														$rulename = "";
														$rulesvalue = "";
														
														if($andrulesValues['highlighter_id'] != null && $andrulesValues['highlighter_id'] != ""){
															
															if(in_array('2', $andrulesActionValues2[$andrulesValues['highlighter_id']])){
															$rulename .= ' ';
															$rulesvalue .= 'Highlighter: '.$highlighterData['highlighter_name'].' and ';
															}
														}
														
														
														if($andrulesValues['keyword_image'] != null && $andrulesValues['keyword_image'] != ""){
															
															$querya = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "keyword WHERE keyword_image = '" . $andrulesValues['keyword_image'] . "'");
				
															$active_tagdata = $querya->row;
															
															if(in_array('2', $andrulesActionValues2[$andrulesValues['keyword_image']])){
															$rulename .= ' ';
															$rulesvalue .= ' ActiveNote: '.$active_tagdata['keyword_name'].' and ';
															}
														
														}
														
														if($andrulesValues['color_id'] != null && $andrulesValues['color_id'] != ""){
															
															if($andrulesValues['color_id'] == '008000'){
																$color_id = "Green";
															}
															if($andrulesValues['color_id'] == 'FF0000'){
																$color_id = "Red";
															}
															if($andrulesValues['color_id'] == '0000FF'){
																$color_id = "Blue";
															}
															if($andrulesValues['color_id'] == '000000'){
																$color_id = "Black";
															}
															if(in_array('2', $andrulesActionValues2[$andrulesValues['color_id']])){
															$rulename .= ' ';
															$rulesvalue .= ' Color: '.$color_id.' and ';
															}
														}
														
														if($andrulesValues['keyword_search'] != null && $andrulesValues['keyword_search'] != ""){
															if(in_array('2', $andrulesActionValues2[$andrulesValues['keyword_search']])){
															$rulename .= ' ';
															$rulesvalue .= ' Keyword: '.$andrulesValues['keyword_search'].' and ';
															}
														}
														
														
														$facilityDetails['rules_type'] = $rulename;
														$facilityDetails['rules_value'] = $rulesvalue;
															
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

															if($user_info['email'] != null && $user_info['email'] != ""){
																$mail->addAddress($user_info['email']);
															}else{
																$mail->addAddress('app-monitoring@noteactive.com'); 
															}
															$mail->WordWrap = 50;                               
															$mail->isHTML(true);                       
																								 
															$mail->Subject = 'This is an Automated Alert Email.';
																						
															$message33 = "";
															
															$message33 .= $this->sendEmailtemplate($result, $rule['rules_name'], $rulename, $rulesvalue, $facilityDetails);
															//var_dump($message33);
															$mail->msgHTML($message33);
															$mail->send();
															
															$sql3e = "UPDATE `" . DB_PREFIX . "notes` SET send_email = '1' WHERE notes_id = '".$result['notes_id']."'";			
															$query = $this->db->query($sql3e);
														}
													}
												}
												
												
												if(in_array('4', $andrulesActionValues)){
													$tnotesIds[] = $result['notes_id'];
													
													//var_dump($tnotesIds);
													
													if($andrulesValues['highlighter_id'] != null && $andrulesValues['highlighter_id'] != ""){
														
															
															if(in_array('4', $andrulesActionValues2[$andrulesValues['highlighter_id']])){
															
																$rulesvaluet = $rule['rules_name'] .' ';//'Highlighter: '.$highlighterData['highlighter_name'].' and ';
																
																$sqls23 = "SELECT * FROM `" . DB_PREFIX . "notes` where rule_highlighter_task = '0' and notes_id ='".$result['notes_id']."' ";
																$query4 = $this->db->query($sqls23);
																
																//var_dump($query4->num_rows);
																
																if($query4->num_rows > 0){
																	$addtask = array();
																	
																	$snooze_time71 = 0;
																	$thestime61 = date('H:i:s');
																	
																	$taskTime = date("H:i:s",strtotime("+".$snooze_time71." minutes",strtotime($thestime61)));
																	
																	$addtask['taskDate'] = date('m-d-Y', strtotime($result['date_added']));
																	$addtask['end_recurrence_date'] = date('m-d-Y', strtotime($result['date_added']));
																	$addtask['recurrence'] = $andrulesTaskValues[$andrulesValues['highlighter_id']]['recurrence'];
																	$addtask['recurnce_week'] = $andrulesTaskValues[$andrulesValues['highlighter_id']]['recurnce_week'];
																	$addtask['recurnce_hrly'] = $andrulesTaskValues[$andrulesValues['highlighter_id']]['recurnce_hrly'];
																	$addtask['recurnce_month'] = $andrulesTaskValues[$andrulesValues['highlighter_id']]['recurnce_month'];
																	$addtask['recurnce_day'] = $andrulesTaskValues[$andrulesValues['highlighter_id']]['recurnce_day'];
																	$addtask['taskTime'] = $taskTime; //date('H:i:s');
																	$addtask['endtime'] = $stime8;
																	$addtask['description'] = $rulesvaluet . $andrulesTaskValues[$andrulesValues['highlighter_id']]['description'].' '.$result['notes_description'];
																	$addtask['assignto'] = $result['user_id'];
																	$addtask['tasktype'] = $andrulesTaskValues[$andrulesValues['highlighter_id']]['tasktype'];
																	$addtask['numChecklist'] = $andrulesTaskValues[$andrulesValues['highlighter_id']]['numChecklist'];
																	$addtask['task_alert'] = $andrulesTaskValues[$andrulesValues['highlighter_id']]['task_alert'];
																	$addtask['alert_type_sms'] = $andrulesTaskValues[$andrulesValues['highlighter_id']]['alert_type_sms'];
																	$addtask['alert_type_notification'] = $andrulesTaskValues[$andrulesValues['highlighter_id']]['alert_type_notification'];
																	$addtask['alert_type_email'] = $andrulesTaskValues[$andrulesValues['highlighter_id']]['alert_type_email'];
																	$addtask['rules_task'] = $result['notes_id'];
																	
																	
																	$sqlw = "update `" . DB_PREFIX . "notes` set rule_highlighter_task = '1' where notes_id ='".$result['notes_id']."'";
																	$this->db->query($sqlw); 
																	//echo "<hr>11111111111111111111 ";
																	//var_dump($addtask);
																	
																	$this->model_createtask_createtask->addcreatetask($addtask, $facilities_id);
																}
															}
														}
														
														
														if($andrulesValues['keyword_image'] != null && $andrulesValues['keyword_image'] != ""){
															
															$querya = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "keyword WHERE keyword_image = '" . $andrulesValues['keyword_image'] . "'");
				
															$active_tagdata = $querya->row;
															
															if(in_array('4', $andrulesActionValues2[$andrulesValues['keyword_image']])){
															
																$rulesvaluet = $rule['rules_name'] .' '; //' ActiveNote: '.$active_tagdata['keyword_name'].' and ';
																$sqls23 = "SELECT * FROM `" . DB_PREFIX . "notes` where rule_activenote_task = '0' and notes_id ='".$result['notes_id']."' ";
																$query4 = $this->db->query($sqls23);
																
																if($query4->num_rows > 0){
																	$addtask = array();
																		
																	
																	$snooze_time71 = 0;
																	$thestime61 = date('H:i:s');
																	
																	$taskTime = date("H:i:s",strtotime("+".$snooze_time71." minutes",strtotime($thestime61)));
																	
																	$addtask['taskDate'] = date('m-d-Y', strtotime($result['date_added']));
																	$addtask['end_recurrence_date'] = date('m-d-Y', strtotime($result['date_added']));
																	$addtask['recurrence'] = $andrulesTaskValues[$andrulesValues['keyword_image']]['recurrence'];
																	$addtask['recurnce_week'] = $andrulesTaskValues[$andrulesValues['keyword_image']]['recurnce_week'];
																	$addtask['recurnce_hrly'] = $andrulesTaskValues[$andrulesValues['keyword_image']]['recurnce_hrly'];
																	$addtask['recurnce_month'] = $andrulesTaskValues[$andrulesValues['keyword_image']]['recurnce_month'];
																	$addtask['recurnce_day'] = $andrulesTaskValues[$andrulesValues['keyword_image']]['recurnce_day'];
																	$addtask['taskTime'] = $taskTime; //date('H:i:s');
																	$addtask['endtime'] = $stime8;
																	$addtask['description'] = $rulesvaluet . $andrulesTaskValues[$andrulesValues['keyword_image']]['description'].' '.$result['notes_description'];
																	$addtask['assignto'] = $result['user_id'];
																	$addtask['tasktype'] = $andrulesTaskValues[$andrulesValues['keyword_image']]['tasktype'];
																	$addtask['numChecklist'] = $andrulesTaskValues[$andrulesValues['keyword_image']]['numChecklist'];
																	$addtask['task_alert'] = $andrulesTaskValues[$andrulesValues['keyword_image']]['task_alert'];
																	$addtask['alert_type_sms'] = $andrulesTaskValues[$andrulesValues['keyword_image']]['alert_type_sms'];
																	$addtask['alert_type_notification'] = $andrulesTaskValues[$andrulesValues['keyword_image']]['alert_type_notification'];
																	$addtask['alert_type_email'] = $andrulesTaskValues[$andrulesValues['keyword_image']]['alert_type_email'];
																	$addtask['rules_task'] = $result['notes_id'];
																	
																	
																	$sqlw = "update `" . DB_PREFIX . "notes` set rule_activenote_task = '1' where notes_id ='".$result['notes_id']."'";
																	$this->db->query($sqlw); 
																	//echo "<hr>2222222222222222222222 ";
																	//var_dump($addtask);
																	
																	$this->model_createtask_createtask->addcreatetask($addtask, $facilities_id);
																}
															}
														
														}
														
														if($andrulesValues['color_id'] != null && $andrulesValues['color_id'] != ""){
															
															if($andrulesValues['color_id'] == '008000'){
																$color_id = "Green";
															}
															if($andrulesValues['color_id'] == 'FF0000'){
																$color_id = "Red";
															}
															if($andrulesValues['color_id'] == '0000FF'){
																$color_id = "Blue";
															}
															if($andrulesValues['color_id'] == '000000'){
																$color_id = "Black";
															}
															if(in_array('4', $andrulesActionValues2[$andrulesValues['color_id']])){
															
																$rulesvaluet = $rule['rules_name'] .' '; //' Color: '.$color_id.' and ';
																
																$sqls23 = "SELECT * FROM `" . DB_PREFIX . "notes` where rule_color_task = '0' and notes_id ='".$result['notes_id']."' ";
																
																$query4 = $this->db->query($sqls23);
																
																if($query4->num_rows > 0){
																	$addtask = array();
																		
																	
																	$snooze_time71 = 0;
																	$thestime61 = date('H:i:s');
																	
																	$taskTime = date("H:i:s",strtotime("+".$snooze_time71." minutes",strtotime($thestime61)));
																	
																	$addtask['taskDate'] = date('m-d-Y', strtotime($result['date_added']));
																	$addtask['end_recurrence_date'] = date('m-d-Y', strtotime($result['date_added']));
																	$addtask['recurrence'] = $andrulesTaskValues[$andrulesValues['color_id']]['recurrence'];
																	$addtask['recurnce_week'] = $andrulesTaskValues[$andrulesValues['color_id']]['recurnce_week'];
																	$addtask['recurnce_hrly'] = $andrulesTaskValues[$andrulesValues['color_id']]['recurnce_hrly'];
																	$addtask['recurnce_month'] = $andrulesTaskValues[$andrulesValues['color_id']]['recurnce_month'];
																	$addtask['recurnce_day'] = $andrulesTaskValues[$andrulesValues['color_id']]['recurnce_day'];
																	$addtask['taskTime'] = $taskTime; //date('H:i:s');
																	$addtask['endtime'] = $stime8;
																	$addtask['description'] = $rulesvaluet . $andrulesTaskValues[$andrulesValues['color_id']]['description'].' '.$result['notes_description'];
																	$addtask['assignto'] = $result['user_id'];
																	$addtask['tasktype'] = $andrulesTaskValues[$andrulesValues['color_id']]['tasktype'];
																	$addtask['numChecklist'] = $andrulesTaskValues[$andrulesValues['color_id']]['numChecklist'];
																	$addtask['task_alert'] = $andrulesTaskValues[$andrulesValues['color_id']]['task_alert'];
																	$addtask['alert_type_sms'] = $andrulesTaskValues[$andrulesValues['color_id']]['alert_type_sms'];
																	$addtask['alert_type_notification'] = $andrulesTaskValues[$andrulesValues['color_id']]['alert_type_notification'];
																	$addtask['alert_type_email'] = $andrulesTaskValues[$andrulesValues['color_id']]['alert_type_email'];
																	$addtask['rules_task'] = $result['notes_id'];
																	
																	
																	$sqlw = "update `" . DB_PREFIX . "notes` set rule_color_task = '1' where notes_id ='".$result['notes_id']."'";
																	$this->db->query($sqlw); 
																	//echo "<hr>3333333333333333333333 ";
																	//var_dump($addtask);
																	$this->model_createtask_createtask->addcreatetask($addtask, $facilities_id);
																}
															
															}
														}
														
														//var_dump($andrulesValues['keyword_search']);
														
														if($andrulesValues['keyword_search'] != null && $andrulesValues['keyword_search'] != ""){
															
															
															
															if(in_array('4', $andrulesActionValues2[$andrulesValues['keyword_search']])){
															
															$rulesvaluet = $rule['rules_name'] .' '; //' Keyword: '.$andrulesValues['keyword_search'].' and ';
																
																$sqls23 = "SELECT * FROM `" . DB_PREFIX . "notes` where rule_keyword_task = '0' and notes_id ='".$result['notes_id']."' ";
																$query4 = $this->db->query($sqls23);
																
																if($query4->num_rows > 0){
																	$addtask = array();
																		
																	
																	$snooze_time71 = 0;
																	$thestime61 = date('H:i:s');
																	
																	$taskTime = date("H:i:s",strtotime("+".$snooze_time71." minutes",strtotime($thestime61)));
																	
																	$addtask['taskDate'] = date('m-d-Y', strtotime($result['date_added']));
																	$addtask['end_recurrence_date'] = date('m-d-Y', strtotime($result['date_added']));
																	$addtask['recurrence'] = $andrulesTaskValues[$andrulesValues['keyword_search']]['recurrence'];
																	$addtask['recurnce_week'] = $andrulesTaskValues[$andrulesValues['keyword_search']]['recurnce_week'];
																	$addtask['recurnce_hrly'] = $andrulesTaskValues[$andrulesValues['keyword_search']]['recurnce_hrly'];
																	$addtask['recurnce_month'] = $andrulesTaskValues[$andrulesValues['keyword_search']]['recurnce_month'];
																	$addtask['recurnce_day'] = $andrulesTaskValues[$andrulesValues['keyword_search']]['recurnce_day'];
																	$addtask['taskTime'] = $taskTime; //date('H:i:s');
																	$addtask['endtime'] = $stime8;
																	$addtask['description'] = $rulesvaluet . $andrulesTaskValues[$andrulesValues['keyword_search']]['description'].' '.$result['notes_description'];
																	$addtask['assignto'] = $result['user_id'];
																	$addtask['tasktype'] = $andrulesTaskValues[$andrulesValues['keyword_search']]['tasktype'];
																	$addtask['numChecklist'] = $andrulesTaskValues[$andrulesValues['keyword_search']]['numChecklist'];
																	$addtask['task_alert'] = $andrulesTaskValues[$andrulesValues['keyword_search']]['task_alert'];
																	$addtask['alert_type_sms'] = $andrulesTaskValues[$andrulesValues['keyword_search']]['alert_type_sms'];
																	$addtask['alert_type_notification'] = $andrulesTaskValues[$andrulesValues['keyword_search']]['alert_type_notification'];
																	$addtask['alert_type_email'] = $andrulesTaskValues[$andrulesValues['keyword_search']]['alert_type_email'];
																	$addtask['rules_task'] = $result['notes_id'];
																	
																	
																	$sqlw = "update `" . DB_PREFIX . "notes` set rule_keyword_task = '1' where notes_id ='".$result['notes_id']."'";
																	$this->db->query($sqlw); 
																	//echo "<hr>4444444444444 ";
																	//var_dump($addtask);
																	$this->model_createtask_createtask->addcreatetask($addtask, $facilities_id);
																}
															
															}
														}
													
													
												}
												
											
											}
											
											
										}
									
							}
							
						}
						
						}
					//}
				}
				
			}
	
		}
		
		//var_dump($facilityDetails);
		//var_dump($json['rulenotes']);
		
		//var_dump($rowModule);
		$tnotesIds = array_unique($tnotesIds);
		//var_dump($tnotesIds);
		
		if($tnotesIds != null && $tnotesIds != ""){
			$this->load->model('createtask/createtask');
			$sqlst2 = "SELECT notes_id, emp_tag_id, facilities_id, notes_description, highlighter_id, date_added, user_id, notetime, note_date, snooze_time FROM `" . DB_PREFIX . "notes` where notes_id in (".implode(',',$tnotesIds).") and status = '1' and text_color_cut = '0' and `snooze_dismiss` != '2' ";
			
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
					
					$addtask['taskDate'] = date('m-d-Y', strtotime($tresult['date_added']));
					$addtask['end_recurrence_date'] = date('m-d-Y', strtotime($tresult['date_added']));
					$addtask['recurrence'] = $rowModule['recurrence'];
					$addtask['recurnce_week'] = $rowModule['recurnce_week'];
					$addtask['recurnce_hrly'] = $rowModule['recurnce_hrly'];
					$addtask['recurnce_month'] = $rowModule['recurnce_month'];
					$addtask['recurnce_day'] = $rowModule['recurnce_day'];
					$addtask['taskTime'] = $taskTime; //date('H:i:s');
					$addtask['endtime'] = $stime8;
					$addtask['description'] = $rowModule['description'].' '.$tresult['notes_description'];
					$addtask['assignto'] = $tresult['user_id'];
					$addtask['tasktype'] = $rowModule['tasktype'];
					$addtask['numChecklist'] = $rowModule['numChecklist'];
					$addtask['task_alert'] = $rowModule['task_alert'];
					$addtask['alert_type_sms'] = $rowModule['alert_type_sms'];
					$addtask['alert_type_notification'] = $rowModule['alert_type_notification'];
					$addtask['alert_type_email'] = $rowModule['alert_type_email'];
					$addtask['rules_task'] = $tresult['notes_id'];
					
					
					$this->model_createtask_createtask->addcreatetask($addtask, $facilities_id);
					
					$sqlw = "update `" . DB_PREFIX . "notes` set snooze_dismiss = '2' where notes_id ='".$tresult['notes_id']."'";
					$this->db->query($sqlw);
					
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
					
			$sqls2 = "SELECT notes_id, emp_tag_id, facilities_id, notes_description, highlighter_id, date_added, user_id, notetime, note_date, snooze_time,send_sms,send_email FROM `" . DB_PREFIX . "notes` where notes_id in (".implode(',',$notesIds).") and snooze_dismiss != '2' and status = '1' and text_color_cut = '0' ";
			
			$query = $this->db->query($sqls2);
			
			$config_tag_status = $this->customer->isTag();
			
			
			if ($query->num_rows) {
				
				foreach($query->rows as $result){
					
				
					
					//echo $thestime.'<='.$result['snooze_time'];
					if($thestime >= $result['snooze_time']){
						$highlighterData = $this->model_setting_highlighter->gethighlighter($result['highlighter_id']);
						$user_info = $this->model_user_user->getUserByUsername($result['user_id']);
						
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
						
						$notes_description_2 = html_entity_decode(str_replace('&#039;', '\'',$result['notes_description']));
						
						if($privacy == '2'){
							if($this->session->data['unloack_success'] == '1'){
								$notes_description = $keyImageSrc1 .'&nbsp;'. $emp_tag_id . $notes_description_2;
							}else{
								$notes_description = $emp_tag_id;
							}
						}else{
							$notes_description = $keyImageSrc1 .'&nbsp;'. $emp_tag_id . $notes_description_2;
						}
						
						$json['rulenotes'][] = array(
							'notes_id'    => $result['notes_id'],
							'rules_id'    => '',
							'highlighter_value'   => '',
							'notes_description'   => $notes_description,
							'date_added' => date('j, F Y', strtotime($result['date_added'])),
							'note_date'   => date('j, F Y h:i A', strtotime($result['note_date'])),
							'notetime'   => date('h:i A', strtotime($result['notetime'])),
							'username'      => $result['user_id'],
							'email'      => $user_info['email'],
							'facility'     => $facility['facility'],
						);
						
						$json['total'] = '1'; 
					}else{
						if($json['rulenotes'] == null && $json['rulenotes'] == ""){
							$json['rulenotes'] = array();
							$json['total'] = '0'; 
							$json['status'] = true;
						}
					}
					
				}
				
			}else{
				$json['rulenotes'] = array();
				$json['total'] = '0'; 
				$json['status'] = true;
			}
			
		}else{
			if($json['rulenotes'] == null && $json['rulenotes'] == ""){
				$json['rulenotes'] = array();
				$json['total'] = '0'; 
				$json['status'] = true;
			}
		}
		
		
		//$timezone_name = $this->customer->isTimezone();
		
		
		if($config_task_status == '1'){
			
		$timeZone = date_default_timezone_set($timezone_name);
				
		$this->load->model('createtask/createtask');
				
		$data1 = array();
				
		$currentdate = date('d-m-Y');
		$data1['currentdate'] = $currentdate;
		$data1['notification'] = '1';
		$data1['top'] = '1';
		$data1['snooze_dismiss'] = '2';
		$data1['facilities_id'] = $facilities_id;
				
		$compltetecountTaskLists = $this->model_createtask_createtask->getCountallTaskLists($data1); 
				
		$complteteTaskLists = $this->model_createtask_createtask->getallTaskLists($data1);
		
		$tthestime = date('H:i:s');
		//var_dump($tthestime);
		
		$snooze_time = 0;
		$tstime = date("H:i:s",strtotime("+".$snooze_time." minutes",strtotime($tthestime)));
		//var_dump($tstime);		
		
		if($compltetecountTaskLists > 0){
			
			$this->load->model('setting/locations');
			$this->load->model('setting/tags');
			
			foreach($complteteTaskLists as $list){
				if($tthestime >= $list['snooze_time']){
					
					if($list['checklist'] == "incident_form"){
						$insert_href = str_replace('&amp;', '&', $this->url->link('services/noteform/taskforminsert', '' . 'task_id=' . $list['id']. '&facilities_id=' . $list['facilityId'], 'SSL'));
					}elseif($list['checklist'] == "bed_check"){
						$insert_href = str_replace('&amp;', '&', $this->url->link('services/noteform/checklistform', '' . 'task_id=' . $list['id']. '&facilities_id=' . $list['facilityId'], 'SSL'));
					}else{
						$insert_href = str_replace('&amp;', '&', $this->url->link('services/apptask/jsonSavetask', '' . 'task_id=' . $list['id']));
					}
					
					$bedcheckdata = array();
				
					if($list['task_form_id'] != 0 && $list['task_form_id'] != NULL ){
						
						
						$formDatas = $this->model_setting_locations->getformid($list['task_form_id']);	
							
						foreach($formDatas as $formData){
							
							
							$locData = $this->model_setting_locations->getlocation($formData['locations_id']);
						
							$locationDatab = array();
							
								$locationDatab[] = array(
									'locations_id' =>$locData['locations_id'],
									'location_name' =>$locData['location_name'],
									'location_address' =>$locData['location_address'],
									'location_detail' =>$locData['location_detail'],
									'capacity' =>$locData['capacity'],
									'location_type' =>$locData['location_type'],
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
					 
					
					$medications = array();
					
					if($list['tags_id'] != 0 && $list['tags_id'] != NULL ){
						$tags_info = $this->model_setting_tags->getTag($list['tags_id']);
						$locationData = array();
						$locData = $this->model_setting_locations->getlocation($tags_info['locations_id']);
					
							$locationData[] = array(
								'locations_id' =>$locData['locations_id'],
								'location_name' =>$locData['location_name'],
								'location_address' =>$locData['location_address'],
								'location_detail' =>$locData['location_detail'],
								'capacity' =>$locData['capacity'],
								'location_type' =>$locData['location_type'],
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
						
						$medications[] = array(
								'tags_id' =>$tags_info['tags_id'],
								'emp_tag_id' =>$tags_info['emp_tag_id'],
								'emp_first_name' =>$tags_info['emp_first_name'],
								'emp_last_name' =>$tags_info['emp_last_name'],
								'doctor_name' =>$tags_info['doctor_name'],
								'emergency_contact' =>$tags_info['emergency_contact'],
								'dob' =>$tags_info['dob'],
								'medications_locations' =>$locationData
								);
								
					}
					
					$json['tasklits'][] = array(
					'assign_to' =>$list['assign_to'],
					'tasktype' =>$list['tasktype'],
					'checklist' =>$list['checklist'],
					'task_complettion' =>$list['task_complettion'],
					'device_id' =>$list['device_id'],
					'date' => date('j, M Y', strtotime($list['task_date'])),
					'id' =>$list['id'],
					'description' =>html_entity_decode(str_replace('&#039;', '\'',$list['description'])),
					'task_time' =>date('h:i A', strtotime($list['task_time'])),
					'strice_href' => str_replace('&amp;', '&', $this->url->link('services/apptask/jsonUpdateStriketask', '' . 'task_id=' . $list['id'])),
					//'incident_form_href' => $incident_form_href,
					//'bed_check_href' => $bed_check_href,
					'insert_href' => $insert_href,
					'task_form_id' =>  $list['task_form_id'],
					'tags_id' =>$list['tags_id'],
					'pickup_facilities_id' => $list['pickup_facilities_id'],
					'pickup_locations_address' =>$list['pickup_locations_address'],
					'pickup_locations_time' =>$list['pickup_locations_time'],
					'pickup_locations_latitude' =>$list['pickup_locations_latitude'],
					'pickup_locations_longitude' =>$list['pickup_locations_longitude'],
					'dropoff_facilities_id' =>$list['dropoff_facilities_id'],
					'dropoff_locations_address' =>$list['dropoff_locations_address'],
					'dropoff_locations_time' =>$list['dropoff_locations_time'],
					'dropoff_locations_latitude' =>$list['dropoff_locations_latitude'],
					'dropoff_locations_longitude' =>$list['dropoff_locations_longitude'],
					'transport_tags' =>$list['transport_tags'],
					'medications' =>$medications,
					'bedchecks' =>$bedcheckdata
					);
					
					$json['total'] = $compltetecountTaskLists;
					
				}
				/*else{
					$json['tasklits'] = array();
					
				}*/
			}
					
			
		}else{
			$json['status'] = true;
			$json['tasklits'] = array();
		}
		
		$json['status'] = true;
		
		
		$datasms1 = array();
				
		$currentdate = date('d-m-Y');
		$datasms1['currentdate'] = $currentdate;
		$datasms1['alert_type_sms'] = '1';
		$datasms1['top'] = '1';
		//$datasms1['send_sms'] = '1';
		$datasms1['facilities_id'] = $facilities_id;
				
		$compltetecountsmsTaskLists = $this->model_createtask_createtask->getCountallTaskLists($datasms1); 
				
		$compltetesmsTaskLists = $this->model_createtask_createtask->getallTaskLists($datasms1);
		
		$tthestimes = date('H:i:s');
		//var_dump($tthestime);
		
		$snooze_time = 0;
		$tsmsstime = date("H:i:s",strtotime("+".$snooze_time." minutes",strtotime($tthestimes)));
		
		if($compltetecountsmsTaskLists > 0){
			//require_once(DIR_SYSTEM . 'library/twilio-php/Services/Twilio.php');
			foreach($compltetesmsTaskLists as $task){
				
				if($task['send_sms'] == '0'){
					$username = $task['assign_to'];
					$this->load->model('user/user');
					$userData = $this->model_user_user->getUser($username);
									
									
					if($userData['phone_number'] != 0){
						$phone_number = $userData['phone_number'];
					}else{
						$phone_number = '19045832155';
					}
					//var_dump($phone_number);
					
									
					$message = "Task due at ".date('h:i A',strtotime($task['task_time']))."...\n";
					$message .= "Task Type: ". $task['tasktype']."\n";
					$message .= "Description: ".$task['description']."\n";
					$message .= "______________________\n";
					$message .= "REPLY WITH ID ".$task['id']."@\" to mark this task complete.";
					//require_once('twilio-php/Services/Twilio.php'); 
									
					
					/*
					$account_sid = 'ACb2109ae2269141cc5bb29983d03dfa66'; 
					$auth_token = 'b88f54390acfa7e61d3c9b86a84ecb05'; 
					$client = new Services_Twilio($account_sid, $auth_token); 
					*/
	 
					//$number = '+'.$phone_number;
					
					//$text = $message;
					//$from = "+19042040577"; 
					//var_dump($message);
					
					$response = $client->messages->create(
							'+'.$phone_number,
							array(
								'from' => '+19042040577',
								'body' => $message
							)
						);
						
								 
					//$response = $client->account->sms_messages->create($from,$number,$text); 
										
					$sql3 = "UPDATE `" . DB_PREFIX . "createtask` SET message_sid = '".$response->sid."', send_sms = '1' WHERE id = '".$task['id']."'";			
					$query = $this->db->query($sql3);	
				
				}
			}
		}
		
		
		$dataemail1 = array();
				
		$currentdate = date('d-m-Y');
		$dataemail1['currentdate'] = $currentdate;
		$dataemail1['alert_type_email'] = '1';
		$dataemail1['top'] = '1';
		//$dataemail1['send_email'] = '1';
		$dataemail1['facilities_id'] = $facilities_id;
				
		$compltetecountemailTaskLists = $this->model_createtask_createtask->getCountallTaskLists($dataemail1); 
				
		$complteteemailTaskLists = $this->model_createtask_createtask->getallTaskLists($dataemail1);
		
		$tthestimes = date('H:i:s');
		//var_dump($tthestime);
		
		$snooze_time = 0;
		$tsmsstime = date("H:i:s",strtotime("+".$snooze_time." minutes",strtotime($tthestimes)));
		
		if($compltetecountemailTaskLists > 0){
			foreach($complteteemailTaskLists as $task){
				
				if($task['send_email'] == '0'){
					$message33 = "";
					$message33 .= $this->taskemailtemplate($task, $task['date_added'], $task['task_time']);
					
					//var_dump($message33);die;
					
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
						
						if($task['assign_to'] !="" && $task['assign_to']!= NULL){
							$username = $task['assign_to'];
							$this->load->model('user/user');
							$userEmail = $this->model_user_user->getUser($username);

							//$mail->addAddress($userEmail['email']); 
							//$mail->addAddress('app-monitoring@noteactive.com'); 
							if($userEmail['email'] != null && $userEmail['email'] != ""){
								$mail->addAddress($userEmail['email']); 
							}else{
								$mail->addAddress('app-monitoring@noteactive.com'); 
							}
						}
							
						/*if($data['assignto'] == "" && $data['assignto'] == NULL){
							$this->load->model('user/user');
							$allusers = $this->model_user_user->getUsersByFacility($data['facilities_id']);
							foreach($allusers as $alluser){
								
								if($alluser['email'] != null && $alluser['email'] != ""){
									$mail->addAddress($alluser['email']); 
								}else{
									$mail->addAddress('app-monitoring@noteactive.com'); 
								}
								
							}
								
						}*/
						
						$mail->WordWrap = 50;                               
						$mail->isHTML(true);                       
						 
						$mail->Subject = 'Task has been assigned to you';
						 
						$mail->msgHTML($message33);
						$mail->send();
						
					}
					
					$sql3e = "UPDATE `" . DB_PREFIX . "createtask` SET send_email = '1' WHERE id = '".$task['id']."'";			
					$query = $this->db->query($sql3e);
				
				}
			}
		}
		
		}
		
		
		//var_dump($json);
		
		
		$this->response->setOutput(json_encode($json));
  	}
	
	public function updateNotification(){
		$json = array();
		
		
		
		if(($this->request->post["notes_id"] == null && $this->request->post["notes_id"] == "") && ($this->request->post["task_id"] == null && $this->request->post["task_id"] == "") && ($this->request->post["rules_id"] == null && $this->request->post["rules_id"] == "")){
			$json['warning'] = 'Please check the checkbox';
		}
		
		if($json['warning'] == null && $json['warning'] == ""){
			if($this->request->post["notes_id"] != null && $this->request->post["notes_id"] != ""){
				if($this->request->post["type"] == "1"){
					
					$timezone_name = $this->request->post['facilities_timezone'];
					date_default_timezone_set($timezone_name);
						
						
					foreach ($this->request->post['notes_id'] as $notes_id) {
						$snooze_time = $this->request->post['snooze_time'];
						
						
						$thestime = date('H:i:s');
				
						$stime = date("H:i:s",strtotime("+".$snooze_time." minutes",strtotime($thestime)));
					
						$sqlsn = "UPDATE `" . DB_PREFIX . "notes` SET snooze_time = '" . $stime . "' WHERE notes_id = '" . (int)$notes_id . "' ";
						
						
						$this->db->query($sqlsn);
						
					}
					
					$json['success'] = '1';
					$json['message'] = 'You have update rules successfully!';
				}
				
				if($this->request->post["type"] == "2"){
					foreach ($this->request->post['notes_id'] as $notes_id) {
					
						$sqlsn = "UPDATE `" . DB_PREFIX . "notes` SET snooze_dismiss = '2' WHERE notes_id = '" . (int)$notes_id . "' ";
						$this->db->query($sqlsn);
					}
					
					$json['success'] = '1';
					$json['message'] = 'You have dismiss rules successfully!';
				}
			}
			
			
			if($this->request->post["rules_id"] != null && $this->request->post["rules_id"] != ""){
				if($this->request->post["type"] == "1"){
					
					$timezone_name = $this->request->post['facilities_timezone'];
					date_default_timezone_set($timezone_name);
						
					foreach ($this->request->post['rules_id'] as $rules_id) {
						
						$sql = "SELECT * FROM " . DB_PREFIX . "rules where rules_id = '" . (int)$rules_id . "' and status='1' and snooze_dismiss != '2' ";
						$query = $this->db->query($sql);
						
						$snooze_time = $this->request->post['snooze_time'];
						
						$thestime = $query->row['rules_operation_time'] ; //date('H:i:s');
				
						$stime = date("H:i:s",strtotime("+".$snooze_time." minutes",strtotime($thestime)));
					
						$sqlsn = "UPDATE `" . DB_PREFIX . "rules` SET rules_operation_time = '" . $stime . "' WHERE rules_id = '" . (int)$rules_id . "' ";
						
						
						$this->db->query($sqlsn);
						
					}
					
					$json['success'] = '1';
					$json['message'] = 'You have update rules successfully!';
				}
				
				if($this->request->post["type"] == "2"){
					foreach ($this->request->post['rules_id'] as $rules_id) {
					
						$sqlsn = "UPDATE `" . DB_PREFIX . "rules` SET snooze_dismiss = '2' WHERE rules_id = '" . (int)$rules_id . "' ";
						$this->db->query($sqlsn);
					}
					
					$json['success'] = '1';
					$json['message'] = 'You have dismiss rules successfully!';
				}
			}
			
			if($this->request->post["task_id"] != null && $this->request->post["task_id"] != ""){
				if($this->request->post["type"] == "1"){
					
					$timezone_name = $this->request->post['facilities_timezone'];
					date_default_timezone_set($timezone_name);
						
					foreach ($this->request->post['task_id'] as $task_id) {
						$snooze_time = $this->request->post['snooze_time'];
					
						
						$thestime = date('H:i:s');
				
						$stime = date("H:i:s",strtotime("+".$snooze_time." minutes",strtotime($thestime)));
					
						 $sqlsn = "UPDATE `" . DB_PREFIX . "createtask` SET snooze_time = '" . $stime . "' WHERE id = '" . (int)$task_id . "' ";
						
						
						$this->db->query($sqlsn);
						
						$sql32 = "UPDATE `" . DB_PREFIX . "createtask` SET send_notification = '0' WHERE id = '".$task_id."'";			
						$query = $this->db->query($sql32);	
						
					}
					
					$json['success'] = '1';
					$json['message'] = 'You have update task successfully!';
				}
				
				if($this->request->post["type"] == "2"){
					foreach ($this->request->post['task_id'] as $task_id) {
						$sqlsn = "UPDATE `" . DB_PREFIX . "createtask` SET snooze_dismiss = '2' WHERE id = '" . (int)$task_id . "' ";
						$this->db->query($sqlsn);
					}
					
					$json['success'] = '1';
					
					$json['message'] = 'You have dismiss task successfully!';
					
				}
			}

			$json['status'] = true; 
		}else{
			$json['status'] = false;
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

