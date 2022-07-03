<?php  
class Controllernotesrules extends Controller {  
	private $error = array();
   
  	public function index() {
		
		$this->load->model('notes/notes');
		$this->load->model('notes/rules');
		$this->load->model('facilities/facilities');
		
		$this->load->model('setting/highlighter');
		$this->load->model('setting/country');
		$this->load->model('setting/zone');
		$this->load->model('setting/timezone');
		$this->load->model('facilities/facilities');
		$this->load->model('user/user');
		
		$rules = $this->model_notes_rules->getRules();
		//var_dump($rules);
		//echo "<hr>";
		
		foreach($rules as $rule){
			//$facilitiesids = explode(",", $rule['facilities_id']);
			//var_dump($rule['facilities_id']);
			if($rule['facilities_id'] != null && $rule['facilities_id'] != ""){
				$facilities = $this->model_facilities_facilities->getfacilityByID($rule['facilities_id']);
				//var_dump($facilities);
				//echo "<hr>";
				foreach($facilities as $facility){
					$timezone_info = $this->model_setting_timezone->gettimezone($facility['timezone_id']);
					
					//var_dump($timezone_info['timezone_value']);
					//echo "<hr>";
					date_default_timezone_set($timezone_info['timezone_value']);
					$searchdate =  date('m-d-Y');
					
					$country_info = $this->model_setting_country->getCountry($facility['country_id']);
					$zone_info = $this->model_setting_zone->getZone($facility['zone_id']);
				
					//var_dump($rule['rules_operation']);
					//echo "<hr>";
					foreach($rule['rules_module'] as $rules_module){
						
						/*   highlighter  */
						if($rules_module['rules_type'] == '1'){
							
							if($rules_module['highlighter_id'] != null && $rules_module['highlighter_id'] != ""){
								$sql = "SELECT notes_id,facilities_id,notes_description,highlighter_id,date_added,user_id,notetime,note_date FROM `" . DB_PREFIX . "notes`";
								
								$sql .= 'where 1 = 1 ';
								
								$sql .= " and highlighter_id = '".$rules_module['highlighter_id']."'";
								$sql .= " and facilities_id = '".$facility['facilities_id']."'";
								
								if($rule['rules_operation'] == '2'){
									if($rule['rules_operation_recurrence'] == '1'){
										$date = str_replace('-', '/', $searchdate);
										$res = explode("/", $date);
										$changedDate = $res[2]."-".$res[0]."-".$res[1];
									
										$startDate = $changedDate;
										$endDate = $changedDate;
										$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
									}
									
									if($rule['rules_operation_recurrence'] == '2'){
										$current_dayname = date("l"); 
										$weeksdate = date("Y-m-d",strtotime('monday this week'));
										$weekedate = date("Y-m-d",strtotime("$current_dayname this week"));
										$sql .= " and (`date_added` BETWEEN  '".$weeksdate." 00:00:00' AND  '".$weekedate." 23:59:59')";
									}
									
									if($rule['rules_operation_recurrence'] == '3'){
										$startDate = date("Y-m-01");
										$endDate = date("Y-m-d");
										$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
									}
									
									if($rule['rules_operation_recurrence'] == '4'){
										$startDate = date('Y-m-d', strtotime('-3 Months'));
										$endDate  = date('Y-m-d');
										$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
									}
									
								}else{
									$date = str_replace('-', '/', $searchdate);
									$res = explode("/", $date);
									$changedDate = $res[2]."-".$res[0]."-".$res[1];
								
									$startDate = $changedDate;
									$endDate = $changedDate;
									
									$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
									
								}
								
								$sql .= " and status = '1' ORDER BY notetime DESC  ";
								
								//echo $sql;
								//echo "<hr>";
								
								$query = $this->db->query($sql);
								
								if ($query->num_rows) {
									
									foreach($query->rows as $result){
										$journals = array();
										$highlighterData = $this->model_setting_highlighter->gethighlighter($result['highlighter_id']);
										$user_info = $this->model_user_user->getUserByUsername($result['user_id']);
										
										$journals[] = array(
											'notes_id'    => $result['notes_id'],
											'highlighter_value'   => $highlighterData['highlighter_value'],
											'notes_description'   => $result['notes_description'],
											'date_added' => date('j, F Y', strtotime($result['date_added'])),
											'note_date'   => date('j, F Y h:i A', strtotime($result['note_date'])),
											'notetime'   => date('h:i A', strtotime($result['notetime'])),
											'username'      => $result['user_id'],
											'email'      => $user_info['email'],
											'phone_number'      => $user_info['phone_number'],
											'sms_number'     => $facility['sms_number'],
											'facility'     => $facility['facility'],
											'address'     => $facility['address'],
											'location'     => $facility['location'],
											'zipcode'     => $facility['zipcode'],
											'contry_name'     => $country_info['name'],
											'zone_name'     => $zone_info['name'],
											'href'     => $this->url->link('common/login', '', 'SSL'),
										);
										
										/*   SMS  */
										if(in_array('1', $rules_module['action'])){
											$this->sendSMS($journals, $rule['rules_name'], 'Highlighter', $highlighterData['highlighter_name']);
										}
										
										/*   Email  */
										if(in_array('2', $rules_module['action'])){
											
											$this->sendEmail($journals, $rule['rules_name'], 'Highlighter', $highlighterData['highlighter_name']);
										}
										/*   Notification  */
										
									}
									
									/*if(in_array('3', $rules_module['action'])){
											//var_dump($journals);
										$this->sendNotification($query->rows);
									}*/
									
								}
							}
							
						}
						
						if($rules_module['rules_type'] == '2'){
							
							if($rules_module['keyword_id'] != null && $rules_module['keyword_id'] != ""){
								$querya = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "keyword WHERE keyword_name = '" . $rules_module['keyword_id'] . "'");
		
								$active_tagdata = $querya->row;
								
								
								$sql = "SELECT notes_id,facilities_id,notes_description,highlighter_id,date_added,user_id,notetime,note_date FROM `" . DB_PREFIX . "notes`";
								
								$sql .= 'where 1 = 1 ';
								
								$sql .= " and keyword_file = '".$active_tagdata['keyword_image']."'";
								
								$sql .= " and facilities_id = '".$facility['facilities_id']."'";
								
								if($rule['rules_operation'] == '2'){
									if($rule['rules_operation_recurrence'] == '1'){
										$date = str_replace('-', '/', $searchdate);
										$res = explode("/", $date);
										$changedDate = $res[2]."-".$res[0]."-".$res[1];
									
										$startDate = $changedDate;
										$endDate = $changedDate;
										$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
									}
									
									if($rule['rules_operation_recurrence'] == '2'){
										$current_dayname = date("l"); 
										$weeksdate = date("Y-m-d",strtotime('monday this week'));
										$weekedate = date("Y-m-d",strtotime("$current_dayname this week"));
										$sql .= " and (`date_added` BETWEEN  '".$weeksdate." 00:00:00' AND  '".$weekedate." 23:59:59')";
									}
									
									if($rule['rules_operation_recurrence'] == '3'){
										$startDate = date("Y-m-01");
										$endDate = date("Y-m-d");
										$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
									}
									
									if($rule['rules_operation_recurrence'] == '4'){
										$startDate = date('Y-m-d', strtotime('-3 Months'));
										$endDate  = date('Y-m-d');
										$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
									}
									
								}else{
									$date = str_replace('-', '/', $searchdate);
									$res = explode("/", $date);
									$changedDate = $res[2]."-".$res[0]."-".$res[1];
								
									$startDate = $changedDate;
									$endDate = $changedDate;
									
									$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
									
								}
								
								$sql .= " and status = '1' ORDER BY notetime DESC  ";
								
								//echo $sql;
								//echo "<hr>";
								
								$query = $this->db->query($sql);
								
								if ($query->num_rows) {
									
									foreach($query->rows as $result){
										
										$journals = array();
										$highlighterData = $this->model_setting_highlighter->gethighlighter($result['highlighter_id']);
										$user_info = $this->model_user_user->getUserByUsername($result['user_id']);
										
										$journals[] = array(
											'notes_id'    => $result['notes_id'],
											'highlighter_value'   => $highlighterData['highlighter_value'],
											'notes_description'   => $result['notes_description'],
											'date_added' => date('j, F Y', strtotime($result['date_added'])),
											'note_date'   => date('j, F Y h:i A', strtotime($result['note_date'])),
											'notetime'   => date('h:i A', strtotime($result['notetime'])),
											'username'      => $result['user_id'],
											'email'      => $user_info['email'],
											'phone_number'      => $user_info['phone_number'],
											'sms_number'     => $facility['sms_number'],
											'facility'     => $facility['facility'],
											'address'     => $facility['address'],
											'location'     => $facility['location'],
											'zipcode'     => $facility['zipcode'],
											'contry_name'     => $country_info['name'],
											'zone_name'     => $zone_info['name'],
											'href'     => $this->url->link('common/login', '', 'SSL'),
										);
										
										/*   SMS  */
										if(in_array('1', $rules_module['action'])){
											$this->sendSMS($journals, $rule['rules_name'], 'ActiveNote', $rules_module['keyword_id']);
										}
										
										/*   Email  */
										if(in_array('2', $rules_module['action'])){
											
											$this->sendEmail($journals, $rule['rules_name'], 'ActiveNote', $rules_module['keyword_id']);
										}
										/*   Notification  */
										
									}
									
									/*if(in_array('3', $rules_module['action'])){
											//var_dump($journals);
										$this->sendNotification($query->rows);
									}*/
									
								}
							}
						}
						
						if($rules_module['rules_type'] == '3'){
							//var_dump($rules_module['color_id']);
							if($rules_module['color_id'] != null && $rules_module['color_id'] != ""){
								$sql = "SELECT notes_id,facilities_id,notes_description,highlighter_id,date_added,user_id,notetime,note_date FROM `" . DB_PREFIX . "notes`";
								
								$sql .= 'where 1 = 1 ';
								
								$sql .= " and text_color = '#".$rules_module['color_id']."'";
								$sql .= " and facilities_id = '".$facility['facilities_id']."'";
								
								if($rule['rules_operation'] == '2'){
									if($rule['rules_operation_recurrence'] == '1'){
										$date = str_replace('-', '/', $searchdate);
										$res = explode("/", $date);
										$changedDate = $res[2]."-".$res[0]."-".$res[1];
									
										$startDate = $changedDate;
										$endDate = $changedDate;
										$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
									}
									
									if($rule['rules_operation_recurrence'] == '2'){
										$current_dayname = date("l"); 
										$weeksdate = date("Y-m-d",strtotime('monday this week'));
										$weekedate = date("Y-m-d",strtotime("$current_dayname this week"));
										$sql .= " and (`date_added` BETWEEN  '".$weeksdate." 00:00:00' AND  '".$weekedate." 23:59:59')";
									}
									
									if($rule['rules_operation_recurrence'] == '3'){
										$startDate = date("Y-m-01");
										$endDate = date("Y-m-d");
										$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
									}
									
									if($rule['rules_operation_recurrence'] == '4'){
										$startDate = date('Y-m-d', strtotime('-3 Months'));
										$endDate  = date('Y-m-d');
										$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
									}
									
								}else{
									$date = str_replace('-', '/', $searchdate);
									$res = explode("/", $date);
									$changedDate = $res[2]."-".$res[0]."-".$res[1];
								
									$startDate = $changedDate;
									$endDate = $changedDate;
									
									$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
									
								}
								
								$sql .= " and status = '1' ORDER BY notetime DESC  ";
								
								//echo $sql;
								//echo "<hr>";
								
								$query = $this->db->query($sql);
								
								if ($query->num_rows) {
									
									foreach($query->rows as $result){
										$journals = array();
										$highlighterData = $this->model_setting_highlighter->gethighlighter($result['highlighter_id']);
										$user_info = $this->model_user_user->getUserByUsername($result['user_id']);
										
										$journals[] = array(
											'notes_id'    => $result['notes_id'],
											'highlighter_value'   => $highlighterData['highlighter_value'],
											'notes_description'   => $result['notes_description'],
											'date_added' => date('j, F Y', strtotime($result['date_added'])),
											'note_date'   => date('j, F Y h:i A', strtotime($result['note_date'])),
											'notetime'   => date('h:i A', strtotime($result['notetime'])),
											'username'      => $result['user_id'],
											'email'      => $user_info['email'],
											'phone_number'      => $user_info['phone_number'],
											'sms_number'     => $facility['sms_number'],
											'facility'     => $facility['facility'],
											'address'     => $facility['address'],
											'location'     => $facility['location'],
											'zipcode'     => $facility['zipcode'],
											'contry_name'     => $country_info['name'],
											'zone_name'     => $zone_info['name'],
											'href'     => $this->url->link('common/login', '', 'SSL'),
										);
										
										/*   SMS  */
										if(in_array('1', $rules_module['action'])){
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
											
											$this->sendSMS($journals, $rule['rules_name'], 'Color', $color_id);
										}
										
										/*   Email  */
										if(in_array('2', $rules_module['action'])){
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
											
											$this->sendEmail($journals, $rule['rules_name'], 'Color', $color_id);
										}
										/*   Notification  */
										
									}
									
									/*if(in_array('3', $rules_module['action'])){
											//var_dump($journals);
										$this->sendNotification($query->rows);
									}*/
									
								}
							}
							
						}
						
						if($rules_module['rules_type'] == '4'){
							$data4 = array(
								'searchdate' => $searchdate,
								'task_id' => $rules_module['task_id'],
								'searchdate_app' => '1',
							);
							//var_dump($data4);
							$results = $this->model_notes_notes->getnotess($data4);
						}
						
						if($rules_module['rules_type'] == '5'){
							//var_dump($rules_module['keyword_search']);
							if($rules_module['keyword_search'] != null && $rules_module['keyword_search'] != ""){
								$sqls = "SELECT notes_id,facilities_id,notes_description,highlighter_id,date_added,user_id,notetime,note_date FROM `" . DB_PREFIX . "notes`";
								
								$sqls .= 'where 1 = 1 ';
								
								$sqls .= " and LOWER(notes_description) like '%".strtolower($rules_module['keyword_search'])."%'";
								$sqls .= " and facilities_id = '".$facility['facilities_id']."'";
								
								if($rule['rules_operation'] == '2'){
									if($rule['rules_operation_recurrence'] == '1'){
										$date = str_replace('-', '/', $searchdate);
										$res = explode("/", $date);
										$changedDate = $res[2]."-".$res[0]."-".$res[1];
									
										$startDate = $changedDate;
										$endDate = $changedDate;
										$sqls .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
									}
									
									if($rule['rules_operation_recurrence'] == '2'){
										$current_dayname = date("l"); 
										$weeksdate = date("Y-m-d",strtotime('monday this week'));
										$weekedate = date("Y-m-d",strtotime("$current_dayname this week"));
										$sqls .= " and (`date_added` BETWEEN  '".$weeksdate." 00:00:00' AND  '".$weekedate." 23:59:59')";
									}
									
									if($rule['rules_operation_recurrence'] == '3'){
										$startDate = date("Y-m-01");
										$endDate = date("Y-m-d");
										$sqls .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
									}
									
									if($rule['rules_operation_recurrence'] == '4'){
										$startDate = date('Y-m-d', strtotime('-3 Months'));
										$endDate  = date('Y-m-d');
										$sqls .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
									}
									
								}else{
									$date = str_replace('-', '/', $searchdate);
									$res = explode("/", $date);
									$changedDate = $res[2]."-".$res[0]."-".$res[1];
								
									$startDate = $changedDate;
									$endDate = $changedDate;
									
									$sqls .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
									
								}
								
								$sqls .= " and status = '1' ORDER BY notetime DESC  ";
								
								//echo $sqls;
								//echo "<hr>";
								
								$query = $this->db->query($sqls);
								
								if ($query->num_rows) {
									//var_dump($query->rows);
									foreach($query->rows as $result){
										$journals = array();
										$highlighterData = $this->model_setting_highlighter->gethighlighter($result['highlighter_id']);
										$user_info = $this->model_user_user->getUserByUsername($result['user_id']);
										
										$journals[] = array(
											'notes_id'    => $result['notes_id'],
											'highlighter_value'   => $highlighterData['highlighter_value'],
											'notes_description'   => $result['notes_description'],
											'date_added' => date('j, F Y', strtotime($result['date_added'])),
											'note_date'   => date('j, F Y h:i A', strtotime($result['note_date'])),
											'notetime'   => date('h:i A', strtotime($result['notetime'])),
											'username'      => $result['user_id'],
											'email'      => $user_info['email'],
											'phone_number'      => $user_info['phone_number'],
											'sms_number'     => $facility['sms_number'],
											'facility'     => $facility['facility'],
											'address'     => $facility['address'],
											'location'     => $facility['location'],
											'zipcode'     => $facility['zipcode'],
											'contry_name'     => $country_info['name'],
											'zone_name'     => $zone_info['name'],
											'href'     => $this->url->link('common/login', '', 'SSL'),
										);
										
										/*   SMS  */
										if(in_array('1', $rules_module['action'])){
											$this->sendSMS($journals, $rule['rules_name'], 'Keyword', $rules_module['keyword_search']);
										}
										
										/*   Email  */
										if(in_array('2', $rules_module['action'])){
											
											$this->sendEmail($journals, $rule['rules_name'], 'Keyword', $rules_module['keyword_search']);
										}
										/*   Notification  */
										
									}
									
									
									
								}
							}
						}
					
						//var_dump($results);
						
						//$keyarrays[] = $rule['rules_module'];
						//var_dump($rule['rules_module']);
						//echo "<hr>";
					}
				}
				
			}
	
		}
		//echo "<hr>";
		
		
		
  	}
	
	public function sendSMS($results) {
		foreach($results as $result){
			//var_dump($result);
			//echo "<hr>";
			
			if($result['phone_number'] != null && $result['phone_number'] != '0'){
				$message = "Rules Created\n";
				$message .= $result['notetime']."\n";
				$message .= $ruleName .'-'.$ruleType.'-'.$rulevalue."\n";
				$message .= $result['notes_description'];
				
				$url = 'https://rest.nexmo.com/sms/json?' . http_build_query(
					[
						'api_key' =>  '3d77d86f',
						'api_secret' => 'ec615e849045a195',
						'to' => $result['phone_number'],
						'from' => $result['sms_number'],
						'text' => $message
					]
					);
						 
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$response = curl_exec($ch);
				//var_dump($response);
			}
		}
	}
	
	public function sendEmail($results, $ruleName, $ruleType, $rulevalue) {
		
		if($this->config->get('config_mail_protocol')  == 'smtp'){				
					
			require_once(DIR_SYSTEM . 'library/PHPMailer-master/PHPMailerAutoload.php');
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
			$mail->setFrom('support@noteactive.com', 'Servitium');  
			$mail->addReplyTo('support@noteactive.com', 'Servitium');  
		}
		
		//var_dump($mail);
		//echo "<hr>";
		//var_dump($results);
		
		foreach($results as $result){
			
			$message33 = "";
			$message33 .= $this->emailtemplate($result, $ruleName, $ruleType, $rulevalue);
			
			//var_dump($message33);	 
			if($this->config->get('config_mail_protocol')  == 'smtp'){				
				
				//$mail->addAddress($result['email']); 
				$mail->addAddress('app-monitoring@noteactive.com'); 
				
				$mail->WordWrap = 50;                               
				$mail->isHTML(true);                       
						 
				$mail->Subject = 'This is an Automated Alert Email.';
						 
				$mail->msgHTML($message33);
				$mail->send();
				
				/*if(!$mail->send()) {
					echo 'Task Created';
					echo 'Mailer Error: ' . $mail->ErrorInfo;
					exit;
				}
						 
				echo 'Message has been sent';
				die;
				*/
			
			}
		}
		
		
	}
	
	public function sendNotification($results) {
		
		
		if($results != null && $results != ""){
			$this->load->model('setting/highlighter');
			$this->load->model('facilities/facilities');
			$this->load->model('user/user');
			
			foreach($results as $result){
				//var_dump($result);
				//echo "<hr>";
				$highlighterData = $this->model_setting_highlighter->gethighlighter($result['highlighter_id']);
				$facilities_info = $this->model_facilities_facilities->getfacilities($result['facilities_id']);
				$user_info = $this->model_user_user->getUserByUsername($result['user_id']);
								
				$json['rulenotes'][] = array(
					'notes_id'    => $result['notes_id'],
					'highlighter_value'   => $highlighterData['highlighter_value'],
					'notes_description'   => $result['notes_description'],
					'date_added' => date('j, F Y', strtotime($result['date_added'])),
					'note_date'   => date('j, F Y h:i A', strtotime($result['note_date'])),
					'notetime'   => date('h:i A', strtotime($result['notetime'])),
					'username'      => $result['user_id'],
					'email'      => $user_info['email'],
					'facility'     => $facilities_info['facility'],
				);
			}
			//var_dump($json);
			$json['total'] = '1'; 
		}else{ 
			$json['total'] = '0';
		}
		 
		//var_dump($json);
		
		
		$this->response->setOutput(json_encode($json));
	}

	public function emailtemplate($result, $ruleName, $ruleType, $rulevalue){
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
							
							<h1 style="font-weight: 200;font-size: 30px;padding: 0;margin: 0;">Hello '.$result['username'].'!</h1>
							<p class="lead" style="font-size: 14px;margin-bottom: 10px;font-weight: normal;line-height: 1.6;">This is an automated email generated by NoteActive '.$ruleName.'! Please review the details below for further information or actions:</p>
							
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
						'.$result['date_added'].'&nbsp;'.$result['notetime'].'
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
						'.$result['facility'].'&nbsp;'.$result['address'].'&nbsp;'.$result['location'].'&nbsp;'.$result['zone_name'].'&nbsp;'.$result['zipcode'].', '.$result['contry_name'].'
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
		
		$rules = $this->model_notes_rules->getRules();
		//var_dump($rules);
		//echo "<hr>";
		$json = array();
		$notesIds = array();
		$tnotesIds = array();
		foreach($rules as $rule){
			
			$facilitiesids = explode(",", $rule['facilities_id']);
			//var_dump($facilitiesids);
			//echo "<hr>";
			$facilities_id = $this->customer->getId();
			//var_dump($facilities_id);
			
			if($rule['facilities_id'] != null && $rule['facilities_id'] != ""){
				
				if (in_array($facilities_id, $facilitiesids)){
					
					$timezone_name = $this->customer->isTimezone();
					date_default_timezone_set($timezone_name);
					
					//$facilities = $this->model_facilities_facilities->getfacilityByID($rule['facilities_id']);
					//var_dump($facilities);
					//echo "<hr>";
					//foreach($facilities as $facility){
						//$timezone_info = $this->model_setting_timezone->gettimezone($facility['timezone_id']);
						
						//var_dump($timezone_info['timezone_value']);
						//echo "<hr>";
						//date_default_timezone_set($timezone_info['timezone_value']);
						$timezone_name = $this->customer->isTimezone();
						date_default_timezone_set($timezone_name);
						$searchdate =  date('m-d-Y');
						
						$facility = $this->model_facilities_facilities->getfacilities($facilities_id);
						
						$country_info = $this->model_setting_country->getCountry($facility['country_id']);
						$zone_info = $this->model_setting_zone->getZone($facility['zone_id']);
					
						//var_dump($rule['rules_operation']);
						//echo "<hr>";
						
						
						foreach($rule['rules_module'] as $rules_module){
							$rowModule = array();
							/*   highlighter  */
							if($rules_module['rules_type'] == '1'){
								
								if($rules_module['highlighter_id'] != null && $rules_module['highlighter_id'] != ""){
									$sql = "SELECT notes_id FROM `" . DB_PREFIX . "notes`";
									
									$sql .= 'where 1 = 1 ';
									
									$sql .= " and highlighter_id = '".$rules_module['highlighter_id']."'";
									$sql .= " and facilities_id = '".$facility['facilities_id']."'";
									
									if($rule['rules_operation'] == '2'){
										if($rule['rules_operation_recurrence'] == '1'){
											$date = str_replace('-', '/', $searchdate);
											$res = explode("/", $date);
											$changedDate = $res[2]."-".$res[0]."-".$res[1];
										
											$startDate = $changedDate;
											$endDate = $changedDate;
											$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
										}
										
										if($rule['rules_operation_recurrence'] == '2'){
											$current_dayname = date("l"); 
											$weeksdate = date("Y-m-d",strtotime('monday this week'));
											$weekedate = date("Y-m-d",strtotime("$current_dayname this week"));
											$sql .= " and (`date_added` BETWEEN  '".$weeksdate." 00:00:00' AND  '".$weekedate." 23:59:59')";
										}
										
										if($rule['rules_operation_recurrence'] == '3'){
											$startDate = date("Y-m-01");
											$endDate = date("Y-m-d");
											$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
										}
										
										if($rule['rules_operation_recurrence'] == '4'){
											$startDate = date('Y-m-d', strtotime('-3 Months'));
											$endDate  = date('Y-m-d');
											$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
										}
										
									}else{
										$date = str_replace('-', '/', $searchdate);
										$res = explode("/", $date);
										$changedDate = $res[2]."-".$res[0]."-".$res[1];
									
										$startDate = $changedDate;
										$endDate = $changedDate;
										
										$sql .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
										
									}
									
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
											if(in_array('3', $rules_module['action'])){
												$notesIds[] = $result['notes_id'];
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
									
									
									$sql2 = "SELECT notes_id FROM `" . DB_PREFIX . "notes`";
									
									$sql2 .= 'where 1 = 1 ';
									
									$sql2 .= " and keyword_file = '".$active_tagdata['keyword_image']."'";
									
									$sql2 .= " and facilities_id = '".$facility['facilities_id']."'";
									
									if($rule['rules_operation'] == '2'){
										if($rule['rules_operation_recurrence'] == '1'){
											$date = str_replace('-', '/', $searchdate);
											$res = explode("/", $date);
											$changedDate = $res[2]."-".$res[0]."-".$res[1];
										
											$startDate = $changedDate;
											$endDate = $changedDate;
											$sql2 .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
										}
										
										if($rule['rules_operation_recurrence'] == '2'){
											$current_dayname = date("l"); 
											$weeksdate = date("Y-m-d",strtotime('monday this week'));
											$weekedate = date("Y-m-d",strtotime("$current_dayname this week"));
											$sql2 .= " and (`date_added` BETWEEN  '".$weeksdate." 00:00:00' AND  '".$weekedate." 23:59:59')";
										}
										
										if($rule['rules_operation_recurrence'] == '3'){
											$startDate = date("Y-m-01");
											$endDate = date("Y-m-d");
											$sql2 .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
										}
										
										if($rule['rules_operation_recurrence'] == '4'){
											$startDate = date('Y-m-d', strtotime('-3 Months'));
											$endDate  = date('Y-m-d');
											$sql2 .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
										}
										
									}else{
										$date = str_replace('-', '/', $searchdate);
										$res = explode("/", $date);
										$changedDate = $res[2]."-".$res[0]."-".$res[1];
									
										$startDate = $changedDate;
										$endDate = $changedDate;
										
										$sql2 .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
										
									}
									
									$sql2 .= " and status = '1' ORDER BY notetime DESC  ";
									
									//echo $sql;
									//echo "<hr>";
									
									$query = $this->db->query($sql2);
									//var_dump($query->rows);
									//echo "<hr>";
									if ($query->num_rows) {
										
										foreach($query->rows as $result){
											if(in_array('3', $rules_module['action'])){
												$notesIds[] = $result['notes_id'];
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
											}
										}
										
									}
								}
							}
							
							if($rules_module['rules_type'] == '3'){
								//var_dump($rules_module['color_id']);
								if($rules_module['color_id'] != null && $rules_module['color_id'] != ""){
									$sql3 = "SELECT notes_id FROM `" . DB_PREFIX . "notes`";
									
									$sql3 .= 'where 1 = 1 ';
									
									$sql3 .= " and text_color = '#".$rules_module['color_id']."'";
									$sql3 .= " and facilities_id = '".$facility['facilities_id']."'";
									
									if($rule['rules_operation'] == '2'){
										if($rule['rules_operation_recurrence'] == '1'){
											$date = str_replace('-', '/', $searchdate);
											$res = explode("/", $date);
											$changedDate = $res[2]."-".$res[0]."-".$res[1];
										
											$startDate = $changedDate;
											$endDate = $changedDate;
											$sql3 .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
										}
										
										if($rule['rules_operation_recurrence'] == '2'){
											$current_dayname = date("l"); 
											$weeksdate = date("Y-m-d",strtotime('monday this week'));
											$weekedate = date("Y-m-d",strtotime("$current_dayname this week"));
											$sql3 .= " and (`date_added` BETWEEN  '".$weeksdate." 00:00:00' AND  '".$weekedate." 23:59:59')";
										}
										
										if($rule['rules_operation_recurrence'] == '3'){
											$startDate = date("Y-m-01");
											$endDate = date("Y-m-d");
											$sql3 .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
										}
										
										if($rule['rules_operation_recurrence'] == '4'){
											$startDate = date('Y-m-d', strtotime('-3 Months'));
											$endDate  = date('Y-m-d');
											$sql3 .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
										}
										
									}else{
										$date = str_replace('-', '/', $searchdate);
										$res = explode("/", $date);
										$changedDate = $res[2]."-".$res[0]."-".$res[1];
									
										$startDate = $changedDate;
										$endDate = $changedDate;
										
										$sql3 .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
										
									}
									
									$sql3 .= " and status = '1' ORDER BY notetime DESC  ";
									
									//echo $sql3;
									//echo "<hr>";
									
									$query = $this->db->query($sql3);
									
									if ($query->num_rows) {
										//var_dump($query->rows);
										//echo "<hr>";
										
										foreach($query->rows as $result){
											if(in_array('3', $rules_module['action'])){
												$notesIds[] = $result['notes_id'];
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
									$sqls = "SELECT notes_id FROM `" . DB_PREFIX . "notes`";
									
									$sqls .= 'where 1 = 1 ';
									
									$sqls .= " and LOWER(notes_description) like '%".strtolower($rules_module['keyword_search'])."%'";
									$sqls .= " and facilities_id = '".$facility['facilities_id']."'";
									
									if($rule['rules_operation'] == '2'){
										if($rule['rules_operation_recurrence'] == '1'){
											$date = str_replace('-', '/', $searchdate);
											$res = explode("/", $date);
											$changedDate = $res[2]."-".$res[0]."-".$res[1];
										
											$startDate = $changedDate;
											$endDate = $changedDate;
											$sqls .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
										}
										
										if($rule['rules_operation_recurrence'] == '2'){
											$current_dayname = date("l"); 
											$weeksdate = date("Y-m-d",strtotime('monday this week'));
											$weekedate = date("Y-m-d",strtotime("$current_dayname this week"));
											$sqls .= " and (`date_added` BETWEEN  '".$weeksdate." 00:00:00' AND  '".$weekedate." 23:59:59')";
										}
										
										if($rule['rules_operation_recurrence'] == '3'){
											$startDate = date("Y-m-01");
											$endDate = date("Y-m-d");
											$sqls .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
										}
										
										if($rule['rules_operation_recurrence'] == '4'){
											$startDate = date('Y-m-d', strtotime('-3 Months'));
											$endDate  = date('Y-m-d');
											$sqls .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
										}
										
									}else{
										$date = str_replace('-', '/', $searchdate);
										$res = explode("/", $date);
										$changedDate = $res[2]."-".$res[0]."-".$res[1];
									
										$startDate = $changedDate;
										$endDate = $changedDate;
										
										$sqls .= " and (`date_added` BETWEEN  '".$startDate." 00:00:00' AND  '".$endDate." 23:59:59')";
										
									}
									
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
					//}
				}
				
			}
	
		}
		
		//var_dump($rowModule);
		$tnotesIds = array_unique($tnotesIds);
		//var_dump($tnotesIds);
		
		if($tnotesIds != null && $tnotesIds != ""){
			$this->load->model('createtask/createtask');
			$sqlst2 = "SELECT notes_id, emp_tag_id, facilities_id, notes_description, highlighter_id, date_added, user_id, notetime, note_date, snooze_time FROM `" . DB_PREFIX . "notes` where notes_id in (".implode(',',$tnotesIds).") and status = '1' and text_color_cut = '0' ";
			
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
					
					$thestime61 = date('H:i:s');
					//var_dump($thestime6);
					$snooze_time71 = 10;
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
					$addtask['description'] = $tresult['notes_description'];
					$addtask['assignto'] = $tresult['user_id'];
					$addtask['tasktype'] = $rowModule['tasktype'];
					$addtask['numChecklist'] = $rowModule['numChecklist'];
					$addtask['task_alert'] = $rowModule['task_alert'];
					$addtask['alert_type_sms'] = $rowModule['alert_type_sms'];
					$addtask['alert_type_notification'] = $rowModule['alert_type_notification'];
					$addtask['alert_type_email'] = $rowModule['alert_type_email'];
					$addtask['rules_task'] = $tresult['notes_id'];
					
					
					$this->model_createtask_createtask->addcreatetask($addtask, $this->customer->getId());
					
					$rowModule = array();
				}
			}
		}
		
		//var_dump($notesIds);
		$notesIds = array_unique($notesIds);
		
		if($notesIds != null && $notesIds != ""){
			
			$thestime = date('H:i:s');
			//var_dump($thestime);
			$snooze_time = 0;
			$stime = date("H:i:s",strtotime("+".$snooze_time." minutes",strtotime($thestime)));
			
			//var_dump($stime);
					
			$sqls2 = "SELECT notes_id, emp_tag_id, facilities_id, notes_description, highlighter_id, date_added, user_id, notetime, note_date, snooze_time FROM `" . DB_PREFIX . "notes` where notes_id in (".implode(',',$notesIds).") and snooze_dismiss != '2' and status = '1' and text_color_cut = '0' ";
			
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
						
						if($privacy == '2'){
							if($this->session->data['unloack_success'] == '1'){
								$notes_description = $keyImageSrc1 .'&nbsp;'. $emp_tag_id . $result['notes_description'];
							}else{
								$notes_description = $emp_tag_id;
							}
						}else{
							$notes_description = $keyImageSrc1 .'&nbsp;'. $emp_tag_id . $result['notes_description'];
						}
						
						$json['rulenotes'][] = array(
							'notes_id'    => $result['notes_id'],
							'highlighter_value'   => $highlighterData['highlighter_value'],
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
						$json['rulenotes'] = array();
						$json['total'] = '0'; 
					}
					
				}
				
			}else{
				$json['rulenotes'] = array();
			}
			
		}else{
			$json['rulenotes'] = array();
			$json['total'] = '0'; 
		}
		
		
		$timezone_name = $this->customer->isTimezone();
			
		$timeZone = date_default_timezone_set($timezone_name);
				
		$this->load->model('createtask/createtask');
				
		$data1 = array();
				
		$currentdate = date('d-m-Y');
		$data1['currentdate'] = $currentdate;
		$data1['notification'] = '1';
		$data1['top'] = '1';
		$data1['snooze_dismiss'] = '2';
		$data1['facilities_id'] = $this->customer->getId();
				
		$compltetecountTaskLists = $this->model_createtask_createtask->getCountallTaskLists($data1); 
				
		$complteteTaskLists = $this->model_createtask_createtask->getallTaskLists($data1);
		
		$tthestime = date('H:i:s');
		//var_dump($tthestime);
		
		$snooze_time = 0;
		$tstime = date("H:i:s",strtotime("+".$snooze_time." minutes",strtotime($tthestime)));
		//var_dump($tstime);		
		
		if($compltetecountTaskLists > 0){
			foreach($complteteTaskLists as $list){
				
				
				
				if($tthestime >= $list['snooze_time']){
					
					if($list['checklist'] == "incident_form"){
						$incident_form_href = str_replace('&amp;', '&', $this->url->link('notes/noteform/taskforminsert', '' . 'task_id=' . $list['id']));
					}elseif($list['checklist'] == "bed_check"){
						$bed_check_href = str_replace('&amp;', '&', $this->url->link('notes/createtask/checklistform', '' . 'task_id=' . $list['id']));
					}else{
						$insert_href = str_replace('&amp;', '&', $this->url->link('notes/createtask/inserttask', '' . 'task_id=' . $list['id']));
					}
					
					$json['tasklits'][] = array(
					'assign_to' =>$list['assign_to'],
					'tasktype' =>$list['tasktype'],
					'checklist' =>$list['checklist'],
					'date' => date('j, M Y', strtotime($list['task_date'])),
					'id' =>$list['id'],
					'description' =>$list['description'],
					'task_time' =>date('h:i A', strtotime($list['task_time'])),
					'strice_href' => str_replace('&amp;', '&', $this->url->link('notes/createtask/updateStriketask', '' . 'task_id=' . $list['id'])),
					'incident_form_href' => $incident_form_href,
					'bed_check_href' => $bed_check_href,
					'insert_href' => $insert_href,
					);
				}
			}
					
			$json['total'] = $compltetecountTaskLists;
		}else{
			$json['tasklits'] = array();
		}
		
		$json['status'] = true;
		
		$this->response->setOutput(json_encode($json));
  	}
	
	public function updateNotification(){
		$json = array();
		
		if(($this->request->post["notes_id"] == null && $this->request->post["notes_id"] == "") && ($this->request->post["task_id"] == null && $this->request->post["task_id"] == "")){
			$json['warning'] = 'Please check the checkbox';
		}
		
		if($json['warning'] == null && $json['warning'] == ""){
			if($this->request->post["notes_id"] != null && $this->request->post["notes_id"] != ""){
				if($this->request->get["type"] == "1"){
					
					$timezone_name = $this->customer->isTimezone();
					date_default_timezone_set($timezone_name);
						
					foreach ($this->request->post['notes_id'] as $notes_id) {
						$snooze_time = $this->request->post['snooze_time'];
						
						/*if($snoozeTime == '5min'){
							$snooze_time = '5';
						}else
						if($snoozeTime == '10min'){
							$snooze_time = '10';
						}
						else
						if($snoozeTime == '15min'){
							$snooze_time = '15';
						}else
						if($snoozeTime == '20min'){
							$snooze_time = '20';
						}else
						if($snoozeTime == '25min'){
							$snooze_time = '25';
						}else
						if($snoozeTime == '30min'){
							$snooze_time = '30';
						}else
						if($snoozeTime == '45min'){
							$snooze_time = '45';
						}*/
						
						$thestime = date('H:i:s');
				
						$stime = date("H:i:s",strtotime("+".$snooze_time." minutes",strtotime($thestime)));
					
						$sqlsn = "UPDATE `" . DB_PREFIX . "notes` SET snooze_time = '" . $stime . "' WHERE notes_id = '" . (int)$notes_id . "' ";
						
						
						$this->db->query($sqlsn);
						
					}
					
					$json['success'] = '1';
					$json['message'] = 'You have update rules successfully!';
				}
				
				if($this->request->get["type"] == "2"){
					foreach ($this->request->post['notes_id'] as $notes_id) {
					
						$sqlsn = "UPDATE `" . DB_PREFIX . "notes` SET snooze_dismiss = '2' WHERE notes_id = '" . (int)$notes_id . "' ";
						$this->db->query($sqlsn);
					}
					
					$json['success'] = '1';
					$json['message'] = 'You have dismiss rules successfully!';
				}
			}
			
			if($this->request->post["task_id"] != null && $this->request->post["task_id"] != ""){
				if($this->request->get["type"] == "1"){
					
					$timezone_name = $this->customer->isTimezone();
					date_default_timezone_set($timezone_name);
						
					foreach ($this->request->post['task_id'] as $task_id) {
						$snooze_time = $this->request->post['snooze_time'];
						
						/*if($snoozeTime == '5min'){
							$snooze_time = '5';
						}else
						if($snoozeTime == '10min'){
							$snooze_time = '10';
						}
						else
						if($snoozeTime == '15min'){
							$snooze_time = '15';
						}else
						if($snoozeTime == '20min'){
							$snooze_time = '20';
						}else
						if($snoozeTime == '25min'){
							$snooze_time = '25';
						}else
						if($snoozeTime == '30min'){
							$snooze_time = '30';
						}else
						if($snoozeTime == '45min'){
							$snooze_time = '45';
						}*/
						
						$thestime = date('H:i:s');
				
						$stime = date("H:i:s",strtotime("+".$snooze_time." minutes",strtotime($thestime)));
					
						 $sqlsn = "UPDATE `" . DB_PREFIX . "createtask` SET snooze_time = '" . $stime . "' WHERE id = '" . (int)$task_id . "' ";
						
						
						$this->db->query($sqlsn);
						
					}
					
					$json['success'] = '1';
					$json['message'] = 'You have update task successfully!';
				}
				
				if($this->request->get["type"] == "2"){
					foreach ($this->request->post['task_id'] as $task_id) {
						$sqlsn = "UPDATE `" . DB_PREFIX . "createtask` SET snooze_dismiss = '2' WHERE id = '" . (int)$task_id . "' ";
						$this->db->query($sqlsn);
					}
					
					$json['success'] = '1';
					$json['message'] = 'You have dismiss task successfully!';
				}
			}
		}
		
		
		$this->response->setOutput(json_encode($json));
	}
}
?>