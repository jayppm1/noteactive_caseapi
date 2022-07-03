<?php
class Controllernotessmsapi extends Controller {
	public function addNotes() {
		$request = array_merge ( $_GET, $_POST );
		
		if ($request ['From'] != null && $request ['From'] != "") {
			
			$this->load->model ( 'notes/notes' );
			
			$to = str_replace ( "+", "", $request ['To'] );
			$from = str_replace ( "+", "", $request ['From'] );
			$sid = $request ['MessageSid'];
			$Accountid = $request ['AccountSid'];
			$notedata = $request ['Body'];
			$attachment = $request ['MediaUrl0'];
			
			require_once (DIR_SYSTEM . 'library/twilio-php-master/smsconfig.php');
			
			$this->load->model ( 'facilities/facilities' );
			$query1 = "SELECT * FROM " . DB_PREFIX . "facilities where sms_number = '" . $to . "' ";
			$facility_Data = $this->db->query ( $query1 );
			if ($facility_Data->row == null && $facility_Data->row == "") {
				// error_log('Your number is not activate');
				$number = '+' . $from;
				$smstext = "Your from number is not activate!";
				$from = "+" . $to;
				
				$response = $client->messages->create ( $number, array (
						'from' => $from,
						'body' => $smstext 
				) );
				return;
			}
			
			$this->load->model ( 'setting/timezone' );
			
			$query12 = "SELECT * FROM " . DB_PREFIX . "timezone where timezone_id = '" . $facility_Data->row ['timezone_id'] . "' ";
			$timezone_Data = $this->db->query ( $query12 );
			
			// var_dump($timezone_Data->row);
			
			$this->load->model ( 'user/user' );
			$query = "SELECT * FROM " . DB_PREFIX . "user where phone_number = '" . $from . "' ";
			$user_Data = $this->db->query ( $query );
			
			// var_dump($user_Data);
			
			if ($user_Data->row == null && $user_Data->row == "") {
				// error_log('Your number is not activate');
				$number = '+' . $from;
				$smstext = "Your number is not activate!";
				$from = "+" . $to;
				
				$response = $client->messages->create ( $number, array (
						'from' => $from,
						'body' => $smstext 
				) );
				return;
			}
			
			date_default_timezone_set ( $timezone_Data->row ['timezone_value'] );
			
			// var_dump($timezone_Data->row['timezone_value']);
			
			$notetime = date ( 'H:i:s' );
			$timestamp = date ( 'd-m-Y' );
			$createdate1 = date ( 'Y-m-d', strtotime ( $timestamp ) );
			$createtime1 = date ( 'H:i:s' );
			$createDate2 = $createdate1 . $createtime1;
			$createDate = date ( 'Y-m-d H:i:s', strtotime ( $createDate2 ) );
			// $notes_description = str_replace("'","&#039;", html_entity_decode($notedata, ENT_QUOTES));
			$notes_description = $notedata;
			
			$datasss = $notes_description;
			// $replynote_ID = substr($datasss, strpos($datasss, "@") + 1, 3);
			// echo $replynote_ID;
			
			$str = $datasss;
			$s3 = explode ( "@", $str );
			
			$pos = strpos ( $str, '@' );
			
			// require_once(DIR_SYSTEM . 'library/twilio-php/Services/Twilio.php');
			
			// die;
			if ($pos === false) {
				$data ['highlighter_id'] = '';
				$data ['highlighter_value'] = '';
				$data ['notes_description'] = $notes_description;
				$data ['notes_pin'] = $user_Data->row ['user_pin'];
				$data ['user_id'] = $user_Data->row ['user_id'];
				$data ['notetime'] = $notetime;
				$data ['text_color'] = '';
				$data ['note_date'] = $createDate;
				$data ['notes_file'] = '';
				$data ['facilitytimezone'] = $timezone_Data->row ['timezone_value'];
				$data ['keyword_file'] = '';
				$data ['offline'] = '';
				$data ['emp_tag_id'] = '';
				$data ['date_added'] = $createDate;
				$notes_file_url = '';
				
				
				$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $facility_Data->row ['facilities_id'] );
				$sql = "update `" . DB_PREFIX . "notes` set notes_type = '2' where notes_id='" . $notes_id . "'";
				$this->db->query ( $sql );
				
				/*
				 * $account_sid = 'ACb2109ae2269141cc5bb29983d03dfa66';
				 * $auth_token = 'b88f54390acfa7e61d3c9b86a84ecb05';
				 * $client = new Services_Twilio($account_sid, $auth_token);
				 */
				$number = '+' . $from;
				$smstext = "Your note has been added successfully!";
				$from = "+" . $to;
				
				$response = $client->messages->create ( $number, array (
						'from' => $from,
						'body' => $smstext 
				) );
				
				// $response = $client->account->sms_messages->create($from,$number,$text);
			} else {
				// $s3 = strstr($datasss, '@', true);
				$satya = "SELECT * FROM " . DB_PREFIX . "createtask where id = '" . $s3 [0] . "'  and taskadded = '0'";
				$message_Data = $this->db->query ( $satya );
				
				if ($message_Data->row ['task_time'] == null && $message_Data->row ['task_time'] == "") {
					/*
					 * echo "BBBb";
					 * $account_sid = 'ACb2109ae2269141cc5bb29983d03dfa66';
					 * $auth_token = 'b88f54390acfa7e61d3c9b86a84ecb05';
					 * $client = new Services_Twilio($account_sid, $auth_token);
					 */
					$number = '+' . $from;
					$smstext = "This Task ID is no longer active. Please refer to the logbook for details.";
					$from = "+" . $to;
					$response = $client->messages->create ( $number, array (
							'from' => $from,
							'body' => $smstext 
					) );
					// $response = $client->account->sms_messages->create($from,$number,$text);
					return;
				}
				
				$config_task_complete = $this->config->get ( 'config_task_complete' );
				
				if ($config_task_complete == '5min') {
					$addTime = '5';
				} else if ($config_task_complete == '10min') {
					$addTime = '10';
				} else if ($config_task_complete == '15min') {
					$addTime = '15';
				} else if ($config_task_complete == '20min') {
					$addTime = '20';
				} else if ($config_task_complete == '25min') {
					$addTime = '25';
				} else if ($config_task_complete == '30min') {
					$addTime = '30';
				} else if ($config_task_complete == '45min') {
					$addTime = '45';
				}
				
				$timezone_name = $timezone_Data->row ['timezone_value'];
				date_default_timezone_set ( $timezone_name );
				
				$currenttime = date ( 'H:i:s', strtotime ( 'now' ) );
				$currenttimePlus = date ( 'H:i:s', strtotime ( ' +' . $addTime . ' minutes', strtotime ( 'now' ) ) );
				$currentdate = date ( 'Y-m-d', strtotime ( 'now' ) );
				
				$taskstarttime = date ( 'H:i:s', strtotime ( $message_Data->row ['task_time'] ) );
				
				// echo $currenttimePlus .'>='. $taskstarttime;
				
				if ($currenttimePlus >= $taskstarttime) {
					// echo "2222";
					$taskDuration = '1';
				} else {
					// echo "1111";
					/*
					 * $account_sid = 'ACb2109ae2269141cc5bb29983d03dfa66';
					 * $auth_token = 'b88f54390acfa7e61d3c9b86a84ecb05';
					 * $client = new Services_Twilio($account_sid, $auth_token);
					 */
					$number = '+' . $from;
					$smstext = "You can not complete task before Designated time!";
					$from = "+" . $to;
					$response = $client->messages->create ( $number, array (
							'from' => $from,
							'body' => $smstext 
					) );
					// $response = $client->account->sms_messages->create($from,$number,$text);
					return;
				}
				
				if ($message_Data->row ['id'] != NULL && $message_Data->row ['id'] != '') {
					
					$this->load->model ( 'createtask/createtask' );
					$result = $this->model_createtask_createtask->getStrikedatadetails ( $message_Data->row ['id'] );
					$postdata = array ();
					$postdata ['comments'] = $s3 [1];
					$postdata ['notes_pin'] = $user_Data->row ['user_pin'];
					$postdata ['user_id'] = $user_Data->row ['username'];
					$postdata ['facilitytimezone'] = $timezone_Data->row ['timezone_value'];
					
					$facilities_id = $result ['facilityId'];
					
					$notes_id = $this->model_createtask_createtask->inserttask ( $result, $postdata, $facilities_id );
					$this->model_createtask_createtask->updatetaskNote ( $message_Data->row ['id'] );
					$this->model_createtask_createtask->deteteIncomTask ( $facilities_id );
					
					if ($result ['medication_tags']) {
						$this->load->model ( 'setting/tags' );
						
						$medication_tags1 = explode ( ',', $result ['medication_tags'] );
						
						$timezone_name = $this->customer->isTimezone ();
						
						date_default_timezone_set ( $timezone_name );
						$date_added = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
						
						foreach ( $medication_tags1 as $medicationtag ) {
							$tags_info1 = $this->model_setting_tags->getTag ( $medicationtag );
							
							if ($tags_info1) {
								
								$drugs = array ();
								
								$mdrugs = $this->model_setting_tags->getTagsMedicationdetailsByID ( $result ['id'], $medicationtag );
								
								foreach ( $mdrugs as $tasklocation ) {
									
									$mdrug_info = $this->model_setting_tags->getTagsMedicationdruglByID ( $tasklocation ['tags_medication_details_id'] );
									
									$task_content = 'Resident ' . $tags_info1 ['emp_tag_id'] . ':' . $tags_info1 ['emp_first_name'];
									
									$tdata1 = array ();
									$tdata1 ['notes_id'] = $notes_id;
									$tdata1 ['task_content'] = $task_content;
									$tdata1 ['date_added'] = $date_added;
									$tdata1 ['tags_id'] = $tags_info1 ['tags_id'];
									$tdata1 ['drug_name'] = $mdrug_info ['drug_name'];
									$tdata1 ['dose'] = $mdrug_info ['dose'];
									$tdata1 ['drug_type'] = $mdrug_info ['drug_type'];
									$tdata1 ['frequency'] = $mdrug_info ['frequency'];
									$tdata1 ['instructions'] = $mdrug_info ['instructions'];
									$tdata1 ['count'] = $mdrug_info ['count'];
									$tdata1 ['task_type'] = '2';
									
									$this->model_createtask_createtask->insertTaskmedicine ( $tasklocation, $this->request->post, $tdata1 );
								}
								
								$update_date = date ( 'Y-m-d H:i:s', strtotime ( 'now' ) );
								
								if ($tags_info1 ['emp_tag_id'] != null && $tags_info1 ['emp_tag_id'] != "") {
									$this->load->model ( 'notes/notes' );
									$tadata = array ();
									$this->model_notes_notes->updateNotesTag ( $tags_info1 ['emp_tag_id'], $notes_id, $tags_info1 ['tags_id'], $update_date, $tadata );
								}
							}
						}
					}
					
					$this->load->model ( 'createtask/createtask' );
					$tasktype_info = $this->model_createtask_createtask->gettasktyperowByName ( $result ['tasktype'], $result ['facilityId'] );
					$relation_keyword_id = $tasktype_info ['relation_keyword_id'];
					
					if ($relation_keyword_id) {
						$this->load->model ( 'notes/notes' );
						$noteDetails = $this->model_notes_notes->getnotes ( $notes_id );
						
						$this->load->model ( 'setting/keywords' );
						$keyword_info = $this->model_setting_keywords->getkeywordDetail ( $relation_keyword_id );
						
						$data3 = array ();
						$data3 ['keyword_file'] = $keyword_info ['keyword_image'];
						$data3 ['notes_description'] = $noteDetails ['notes_description'];
						
						$this->model_notes_notes->addactiveNote ( $data3, $notes_id );
					}
					
					$sql = "update `" . DB_PREFIX . "notes` set notes_type = '2' where notes_id='" . $notes_id . "'";
					$this->db->query ( $sql );
					
					// require_once(DIR_SYSTEM . 'library/twilio-php/Services/Twilio.php');
					/*
					 * $account_sid = 'ACb2109ae2269141cc5bb29983d03dfa66';
					 * $auth_token = 'b88f54390acfa7e61d3c9b86a84ecb05';
					 * $client = new Services_Twilio($account_sid, $auth_token);
					 */
					$number = '+' . $from;
					$smstext = "Your Task has been marked as complete";
					$from = "+" . $to;
					$response = $client->messages->create ( $number, array (
							'from' => $from,
							'body' => $smstext 
					) );
					
					// $response = $client->account->sms_messages->create($from,$number,$text);
				} else {
					
					// require_once(DIR_SYSTEM . 'library/twilio-php/Services/Twilio.php');
					/*
					 * $account_sid = 'ACb2109ae2269141cc5bb29983d03dfa66';
					 * $auth_token = 'b88f54390acfa7e61d3c9b86a84ecb05';
					 * $client = new Services_Twilio($account_sid, $auth_token);
					 */
					
					$number = '+' . $from;
					$smstext = "This Task ID is no longer active. Please refer to the logbook for details.";
					$from = "+" . $to;
					$response = $client->messages->create ( $number, array (
							'from' => $from,
							'body' => $smstext 
					) );
					// $response = $client->account->sms_messages->create($from,$number,$text);
				}
			}
			
			$this->data ['facilitiess'] [] = array (
					'warning' => '1',
					'notes_id' => $notes_id 
			);
			$error = true;
		} else {
			$this->data ['facilitiess'] [] = array (
					'warning' => 'blank data' 
			);
			$error = false;
		}
		
		$value = array (
				'results' => $this->data ['facilitiess'],
				'status' => $error 
		);
		
		$this->response->setOutput ( json_encode ( $value ) );
	}
	public function getResponse() {
		$request = array_merge ( $_GET, $_POST );
		var_dump ( $request );
	}
	public function addactiveNotes() {
		$request = array_merge ( $_GET, $_POST );
		
		if ($request ['From'] != null && $request ['From'] != "") {
			
			$this->load->model ( 'notes/notes' );
			
			$to = str_replace ( "+", "", $request ['To'] );
			$from = str_replace ( "+", "", $request ['From'] );
			$sid = $request ['MessageSid'];
			$Accountid = $request ['AccountSid'];
			$notedata = $request ['Body'];
			$attachment = $request ['MediaUrl0'];
			
			require_once (DIR_SYSTEM . 'library/twilio-php-master/smsconfig.php');
			
			$this->load->model ( 'facilities/facilities' );
			$query1 = "SELECT * FROM " . DB_PREFIX . "facilities where sms_number = '" . $to . "' ";
			$facility_Data = $this->db->query ( $query1 );
			if ($facility_Data->row == null && $facility_Data->row == "") {
				// error_log('Your number is not activate');
				$number = '+' . $from;
				$smstext = "Your from number is not activate!";
				$from = "+" . $to;
				
				$response = $client->messages->create ( $number, array (
						'from' => $from,
						'body' => $smstext 
				) );
				return;
			}
			
			$this->load->model ( 'setting/timezone' );
			
			$query12 = "SELECT * FROM " . DB_PREFIX . "timezone where timezone_id = '" . $facility_Data->row ['timezone_id'] . "' ";
			$timezone_Data = $this->db->query ( $query12 );
			
			// var_dump($timezone_Data->row);
			
			$this->load->model ( 'user/user' );
			$query = "SELECT * FROM " . DB_PREFIX . "user where phone_number = '" . $from . "' ";
			$user_Data = $this->db->query ( $query );
			
			// var_dump($user_Data);
			
			if ($user_Data->row == null && $user_Data->row == "") {
				// error_log('Your number is not activate');
				$number = '+' . $from;
				$smstext = "Your number is not activate!";
				$from = "+" . $to;
				
				$response = $client->messages->create ( $number, array (
						'from' => $from,
						'body' => $smstext 
				) );
				return;
			}
			
			date_default_timezone_set ( $timezone_Data->row ['timezone_value'] );
			
			// var_dump($timezone_Data->row['timezone_value']);
			
			$notetime = date ( 'H:i:s' );
			$timestamp = date ( 'd-m-Y' );
			$createdate1 = date ( 'Y-m-d', strtotime ( $timestamp ) );
			$createtime1 = date ( 'H:i:s' );
			$createDate2 = $createdate1 . $createtime1;
			$createDate = date ( 'Y-m-d H:i:s', strtotime ( $createDate2 ) );
			// $notes_description = str_replace("'","&#039;", html_entity_decode($notedata, ENT_QUOTES));
			$notes_description = $notedata;
			
			$datasss = $notes_description;
			// $replynote_ID = substr($datasss, strpos($datasss, "@") + 1, 3);
			// echo $replynote_ID;
			
			$str = $datasss;
			$s3 = explode ( "@", $str );
			
			$pos = strpos ( $str, '@' );
			
			// require_once(DIR_SYSTEM . 'library/twilio-php/Services/Twilio.php');
			
			// die;
			
			$data ['highlighter_id'] = '';
			$data ['highlighter_value'] = '';
			$data ['notes_description'] = $notes_description;
			$data ['notes_pin'] = $user_Data->row ['user_pin'];
			$data ['user_id'] = $user_Data->row ['user_id'];
			$data ['notetime'] = $notetime;
			$data ['text_color'] = '';
			$data ['note_date'] = $createDate;
			$data ['notes_file'] = '';
			$data ['facilitytimezone'] = $timezone_Data->row ['timezone_value'];
			$data ['keyword_file'] = '';
			$data ['offline'] = '';
			$data ['emp_tag_id'] = '';
			$data ['date_added'] = $createDate;
			$notes_file_url = '';
			$data ['monitor_time_1'] = '2';
			
			$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $facility_Data->row ['facilities_id'] );
			$sql = "update `" . DB_PREFIX . "notes` set notes_type = '2' where notes_id='" . $notes_id . "'";
			$this->db->query ( $sql );
			
			/*
			 * $account_sid = 'ACb2109ae2269141cc5bb29983d03dfa66';
			 * $auth_token = 'b88f54390acfa7e61d3c9b86a84ecb05';
			 * $client = new Services_Twilio($account_sid, $auth_token);
			 */
			$number = '+' . $from;
			$smstext = "Your note has been added successfully!";
			$from = "+" . $to;
			
			$response = $client->messages->create ( $number, array (
					'from' => $from,
					'body' => $smstext 
			) );
			
			// $response = $client->account->sms_messages->create($from,$number,$text);
			
			$this->data ['facilitiess'] [] = array (
					'warning' => '1',
					'notes_id' => $notes_id 
			);
			$error = true;
		} else {
			$this->data ['facilitiess'] [] = array (
					'warning' => 'blank data' 
			);
			$error = false;
		}
		
		$value = array (
				'results' => $this->data ['facilitiess'],
				'status' => $error 
		);
		
		$this->response->setOutput ( json_encode ( $value ) );
	}
}