<?php 
 header('Access-Control-Allow-Origin:'.$_SERVER['HTTP_ORIGIN']);
 header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
 header('Access-Control-Max-Age: 1000');
 header('Access-Control-Allow-Headers: Content-Type');
 header('Content-type: application/json');
 header('Content-Type: text/html; charset=utf-8');
 header("Content-type: bitmap; charset=utf-8");
 
class Controllerservicesactivenote extends Controller { 
	private $error = array();
	
	public function index(){
		
		
		try{
			
			$this->load->model('activity/activity');
		$this->model_activity_activity->addActivitySave('activenoteindex', $this->request->post, 'request');
		
		$this->data['facilitiess'] = array();
		
		$this->load->model('api/encrypt');
		$cre_array = array();
		$cre_array['phone_device_id'] = $this->request->post['phone_device_id'];
		$cre_array['facilities_id'] = $this->request->post['facilities_id'];
		
		$api_device_info = $this->model_api_encrypt->getdevicedetails($cre_array);
		
		
		$json = array();
		
		$this->load->model('notes/notes');
		$this->language->load('notes/notes');
		
		if($this->request->post['notes_pin'] != null && $this->request->post['notes_pin'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if (($this->request->post['notes_pin'] != $user_info['user_pin'])) {
				$json['warning'] = 'User Pin not valid!.';
				$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
			}
		}
		
		if($this->request->post['user_id'] != null && $this->request->post['user_id'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if (($user_info['status'] == '0')) {
				$json['warning'] = 'User not exit!';
				$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
			}
			
			$this->load->model('facilities/facilities');
			$facility = $this->model_facilities_facilities->getfacilities($this->request->post['facilities_id']);
			$unique_id = $facility['customer_key'];
			
			
			$this->load->model('customer/customer');
			$customer_info = $this->model_customer_customer->getcustomerid($unique_id);
			
			if($user_info['customer_key'] != $customer_info['activecustomer_id']){
				$json['warning'] = $this->language->get('error_customer');
				$facilitiessee = array();
						$facilitiessee[] = array(
							'warning'  => $json['warning'],
						);
						$error = false;
						
						$value = array('results'=>$facilitiessee,'status'=>false);

					return $this->response->setOutput(json_encode($value));
			}
		}
		
		if ($this->request->post['override_monitor_time_user_id_checkbox'] == '1') {
			if ($this->request->post['override_monitor_time_user_id'] == '') {
				$json['override_monitor_time_user_id'] = $this->language->get('error_required');
			}
		}
		
		if ($this->request->post['override_monitor_time_user_id'] != null && $this->request->post['override_monitor_time_user_id'] != '') {
			if ($this->request->post['override_monitor_time_user_id_checkbox'] == '') {
				$json['override_monitor_time_user_id_checkbox'] = $this->language->get('error_required');
			}
		}
		
		if($this->request->post['keyword_id'] != null && $this->request->post['keyword_id'] != ""){
			$this->load->model('setting/keywords');
			$keywordData2 = $this->model_setting_keywords->getkeywordDetail($this->request->post['keyword_id']);
			
			if($keywordData2['monitor_time'] == '1'){
				if($this->request->post['override_monitor_time_user_id_checkbox'] != '1'){
					if($keywordData2['end_relation_keyword'] == '1'){
						$a3 = array();
						$a3['keyword_id'] = $keywordData2['relation_keyword_id'];
						$a3['user_id'] = $this->request->post['user_id'];
						$a3['facilities_id'] = $this->request->post['facilities_id'];
						$a3['is_monitor_time'] = '1';
						
						$active_note_info2 = $this->model_notes_notes->getNotebyactivenote($a3);
						
						//var_dump($active_note_info2);	
						
						if(empty($active_note_info2)){
							$json['warning'] = 'End ActiveNote does not exit!';
							$facilitiessee = array();
							$facilitiessee[] = array(
								'warning'  => $json['warning'],
							);
							$error = false;
							
							$value = array('results'=>$facilitiessee,'status'=>false);

						return $this->response->setOutput(json_encode($value));
						}
					}
				}
			}
		}
		
		if($this->request->post['current_enroll_image1'] == "1"){
			$this->load->model('api/facerekognition');
			$fre_array = array();
			$fre_array['current_enroll_image1'] = $this->request->post['current_enroll_image1'];
			$fre_array['facilities_id'] = $this->request->post['facilities_id'];
			$fre_array['user_id'] = $this->request->post['user_id'];
			$facerekognition_response = $this->model_api_facerekognition->checkfacerekognition($fre_array, $this->request->post);
			
			$json['warning'] = $facerekognition_response['warning1'];
			
			$facilitiessee = array();
				$facilitiessee[] = array(
					'warning'  => $json['warning'],
				);
				$error = false;
				
				$value = array('results'=>$facilitiessee,'status'=>false);

			return $this->response->setOutput(json_encode($value));
			}
		
		if($json['warning'] == null && $json['warning'] == ""){
			
			$this->load->model('notes/notes');
			$this->load->model('form/form');

			$this->load->model('notes/notes');
			$this->load->model('resident/resident');

			if($this->request->post['keyword_id'] != null && $this->request->post['keyword_id'] != ""){
			if($this->request->post['facilities_id']){
				$this->load->model('facilities/facilities');
					
				$facilities_info = $this->model_facilities_facilities->getfacilities($this->request->post['facilities_id']);
					
				$this->load->model('setting/timezone');
					
				$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
				$facilitytimezone = $timezone_info['timezone_value'];
			}
			
				$timezone_name = $facilitytimezone;
				$timeZone = date_default_timezone_set($timezone_name);
				$noteDate = date('Y-m-d H:i:s', strtotime('now'));
				$date_added = (string) $noteDate;
				
				
				$facilities_id = $this->request->post['facilities_id'];
				
				$notetime = date('H:i:s', strtotime('now'));
				$data['imgOutput'] = $this->request->post['signature'];
				
				$data['notes_pin'] = $this->request->post['notes_pin'];
				$data['user_id'] = $this->request->post['user_id'];
				$data['notes_type'] = $this->request->post['notes_type'];
				
				if($this->request->post['tags_id'] != null && $this->request->post['tags_id'] != ""){
					$this->load->model('setting/tags');
					$tag_info = $this->model_setting_tags->getTag($this->request->post['tags_id']);
					
					$data['emp_tag_id'] = $tag_info['emp_tag_id'];
					$data['tags_id'] = $tag_info['tags_id'];
				}
				
				
				$this->load->model('setting/keywords');
				$keywordData2 = $this->model_setting_keywords->getkeywordDetail($this->request->post['keyword_id']);
				
				if($this->request->post['comments'] != null && $this->request->post['comments']){
					$comments = ' | '.$this->request->post['comments'];
				}
				
				if($keywordData2['monitor_time'] == '1'){
				if($keywordData2['end_relation_keyword'] == '1'){
					
					$a3 = array();
					$a3['keyword_id'] = $keywordData2['relation_keyword_id'];
					$a3['user_id'] = $this->request->post['user_id'];
					$a3['facilities_id'] = $facilities_id;
					$a3['is_monitor_time'] = '1';
					$active_note_info2 = $this->model_notes_notes->getNotebyactivenote($a3);
					
					//var_dump($active_note_info2);
					
					//echo "<hr>";
					
					if($this->request->post['override_monitor_time_user_id_checkbox'] == '1'){
							
							$note_info = $this->model_notes_notes->getNote($this->request->post['override_monitor_time_user_id']);
							
							//var_dump($note_info);
							//echo "<hr>";
							
							$a3e = array();
							$a3e['notes_id'] = $note_info['notes_id'];
							$a3e['facilities_id'] = $facilities_id;
							$a3e['is_monitor_time'] = '1';
							$active_note_info2e = $this->model_notes_notes->getNotebyactivenote($a3e);
							
							//var_dump($active_note_info2e);
							
							//echo "<hr>";
							
							$keywordData21 = $this->model_setting_keywords->getkeywordDetail($active_note_info2e['keyword_id']);
							
							//var_dump($keywordData21);
							//echo "<hr>";
							$keywordData212 = $this->model_setting_keywords->getkeywordDetail($keywordData21['relation_keyword_id']);
							
							//var_dump($keywordData212);
							
							//echo "<hr>";
							
							/*if($keywordData2['keyword_id'] != $active_note_info2e['keyword_id']){*/
							if($active_note_info2e['keyword_id'] != "" &&  $active_note_info2e['keyword_id'] != null){
								
								$start_date = new DateTime($note_info['date_added']);
								$since_start = $start_date->diff(new DateTime($update_date));
								
								$caltime = "";
								$status_total_time = 0;
							
								if($since_start->y > 0){
									$caltime .= $since_start->y.' years ';
									$status_total_time = 60*24*365*$since_start->y;
								}

								if($since_start->m > 0){
									$caltime .= $since_start->m.' months ';
									$status_total_time += 60*24*30*$since_start->m;
								}

								if($since_start->d > 0){
									$caltime .= $since_start->d.' days ';
									$status_total_time += 60*24*$since_start->d;
								}

								if($since_start->h > 0){
									$caltime .= $since_start->h.' hours ';
									$status_total_time += 60*$since_start->h;
								}

								if($since_start->i > 0){
									$caltime .= $since_start->i.' minutes ';
									$status_total_time += $since_start->i;
								}
								
								
								$keyword_name441 = $keywordData212['keyword_name'] .' | ENDED | '.$caltime.' | Originally Started by '.$note_info['user_id'].' at | '. date('m-d-Y h:i A', strtotime($note_info['date_added'])) ;
								
								//$keywordData23 = $this->model_setting_keywords->getkeywordDetail($active_note_info2e['keyword_id']);
								
								//$data['keyword_file'] = $keywordData2['keyword_image'].','.$keywordData23['keyword_image'];
								$data['keyword_file'] = $keywordData212['keyword_image'];
								
							}
							
						}else{
							
							//var_dump($active_note_info2);
							//echo "<hr>";
							$keywordData21 = $this->model_setting_keywords->getkeywordDetail($active_note_info2['keyword_id']);
							
							//var_dump($keywordData21);
							//echo "<hr>";
							$keywordData212 = $this->model_setting_keywords->getkeywordDetail($keywordData21['relation_keyword_id']);
							
							//var_dump($keywordData212);
							
							//echo "<hr>";
							
							$start_date = new DateTime($active_note_info2['date_added']);
							$since_start = $start_date->diff(new DateTime($update_date));
							
							$caltime = "";
							
						
							$status_total_time = 0;
							
							if($since_start->y > 0){
								$caltime .= $since_start->y.' years ';
								$status_total_time = 60*24*365*$since_start->y;
							}

							if($since_start->m > 0){
								$caltime .= $since_start->m.' months ';
								$status_total_time += 60*24*30*$since_start->m;
							}

							if($since_start->d > 0){
								$caltime .= $since_start->d.' days ';
								$status_total_time += 60*24*$since_start->d;
							}

							if($since_start->h > 0){
								$caltime .= $since_start->h.' hours ';
								$status_total_time += 60*$since_start->h;
							}

							if($since_start->i > 0){
								$caltime .= $since_start->i.' minutes ';
								$status_total_time += $since_start->i;
							}
							
							//var_dump($caltime);
							
							$keyword_name441 = $keywordData212['keyword_name'] .' | ENDED | '.$caltime.' | ';
							
							$data['keyword_file'] = $keywordData212['keyword_image'];
						}
						
						
					
				}else{
					
					$a3 = array();
					$a3['keyword_id'] = $keywordData2['keyword_id'];
					$a3['user_id'] = $this->request->post['user_id'];
					$a3['facilities_id'] = $facilities_id;
					$a3['is_monitor_time'] = '1';
					$active_note_info2 = $this->model_notes_notes->getNotebyactivenote($a3);
					
					//var_dump($active_note_info2);
					
					//echo "<hr>";
					
					if($active_note_info2['keyword_id'] != null && $active_note_info2['keyword_id'] != ""){
						
						$start_date = new DateTime($active_note_info2['date_added']);
						$since_start = $start_date->diff(new DateTime($update_date));
						
						$caltime = "";
						
					
						$status_total_time = 0;
							
						if($since_start->y > 0){
							$caltime .= $since_start->y.' years ';
							$status_total_time = 60*24*365*$since_start->y;
						}

						if($since_start->m > 0){
							$caltime .= $since_start->m.' months ';
							$status_total_time += 60*24*30*$since_start->m;
						}

						if($since_start->d > 0){
							$caltime .= $since_start->d.' days ';
							$status_total_time += 60*24*$since_start->d;
						}

						if($since_start->h > 0){
							$caltime .= $since_start->h.' hours ';
							$status_total_time += 60*$since_start->h;
						}

						if($since_start->i > 0){
							$caltime .= $since_start->i.' minutes ';
							$status_total_time += $since_start->i;
						}
						
						//var_dump($caltime);
						
						$keyword_name441 = $keywordData2['keyword_name'] .' | ENDED | '.$caltime.' | ';
						
						
						if($this->request->post['override_monitor_time_user_id_checkbox'] == '1'){
							
							$note_info = $this->model_notes_notes->getNote($this->request->post['override_monitor_time_user_id']);
							
							//var_dump($note_info);
							//echo "<hr>";
							
							$a3e = array();
							$a3e['notes_id'] = $note_info['notes_id'];
							$a3e['facilities_id'] = $facilities_id;
							$a3e['is_monitor_time'] = '1';
							$active_note_info2e = $this->model_notes_notes->getNotebyactivenote($a3e);
							
							/*if($keywordData2['keyword_id'] != $active_note_info2e['keyword_id']){*/
							if($active_note_info2e['keyword_id'] != "" &&  $active_note_info2e['keyword_id'] != null){
								
								$start_date = new DateTime($note_info['date_added']);
								$since_start = $start_date->diff(new DateTime($update_date));
								
								$caltime = "";
								
							
								$status_total_time = 0;
							
								if($since_start->y > 0){
									$caltime .= $since_start->y.' years ';
									$status_total_time = 60*24*365*$since_start->y;
								}

								if($since_start->m > 0){
									$caltime .= $since_start->m.' months ';
									$status_total_time += 60*24*30*$since_start->m;
								}

								if($since_start->d > 0){
									$caltime .= $since_start->d.' days ';
									$status_total_time += 60*24*$since_start->d;
								}

								if($since_start->h > 0){
									$caltime .= $since_start->h.' hours ';
									$status_total_time += 60*$since_start->h;
								}

								if($since_start->i > 0){
									$caltime .= $since_start->i.' minutes ';
									$status_total_time += $since_start->i;
								}
								
								
								$keyword_name441 = $active_note_info2e['keyword_name'] .' | ENDED | '.$caltime.' | Originally Started by '.$note_info['user_id'].' at | '. date('m-d-Y h:i A', strtotime($note_info['date_added'])) ;
								
								$keywordData23 = $this->model_setting_keywords->getkeywordDetail($active_note_info2e['keyword_id']);
								
								//$data['keyword_file'] = $keywordData2['keyword_image'].','.$keywordData23['keyword_image'];
								$data['keyword_file'] = $keywordData23['keyword_image'];
								
							}else{
								$data['keyword_file'] = $keywordData2['keyword_image'];
							}
							
						}else{
						
							$data['keyword_file'] = $keywordData2['keyword_image'];
						}
						
					}else{
						$keyword_name441 = $keywordData2['keyword_name'] .' | STARTED | ';
						
						//var_dump($this->request->post['override_monitor_time_user_id_checkbox']);
						
						if($this->request->post['override_monitor_time_user_id_checkbox'] == '1'){
							
							$note_info = $this->model_notes_notes->getNote($this->request->post['override_monitor_time_user_id']);
							
							//var_dump($note_info);
							//echo "<hr>";
							
							$a3e = array();
							$a3e['notes_id'] = $note_info['notes_id'];
							$a3e['facilities_id'] = $facilities_id;
							$a3e['is_monitor_time'] = '1';
							$active_note_info2e = $this->model_notes_notes->getNotebyactivenote($a3e);
							
							/*if($keywordData2['keyword_id'] != $active_note_info2e['keyword_id']){*/
							if($active_note_info2e['keyword_id'] != "" &&  $active_note_info2e['keyword_id'] != null){
								
								$start_date = new DateTime($note_info['date_added']);
								$since_start = $start_date->diff(new DateTime($update_date));
								
								$caltime = "";
								
							
								$status_total_time = 0;
							
								if($since_start->y > 0){
									$caltime .= $since_start->y.' years ';
									$status_total_time = 60*24*365*$since_start->y;
								}

								if($since_start->m > 0){
									$caltime .= $since_start->m.' months ';
									$status_total_time += 60*24*30*$since_start->m;
								}

								if($since_start->d > 0){
									$caltime .= $since_start->d.' days ';
									$status_total_time += 60*24*$since_start->d;
								}

								if($since_start->h > 0){
									$caltime .= $since_start->h.' hours ';
									$status_total_time += 60*$since_start->h;
								}

								if($since_start->i > 0){
									$caltime .= $since_start->i.' minutes ';
									$status_total_time += $since_start->i;
								}
								
								
								$keyword_name441 = $active_note_info2e['keyword_name'] .' | ENDED | '.$caltime.' | Originally Started by '.$note_info['user_id'].' at | '. date('m-d-Y h:i A', strtotime($note_info['date_added'])) ;
								
								$keywordData23 = $this->model_setting_keywords->getkeywordDetail($active_note_info2e['keyword_id']);
								
								//$data['keyword_file'] = $keywordData2['keyword_image'].','.$keywordData23['keyword_image'];
								$data['keyword_file'] = $keywordData23['keyword_image'];
								
							}else{
								$data['keyword_file'] = $keywordData2['keyword_image'];
							}
							
						}else{
							$data['keyword_file'] = $keywordData2['keyword_image'];
						}
						
					}
					
				}
			}
				
				
				
				if($this->request->post['tags_id'] != null && $this->request->post['tags_id'] != ""){
					$data['notes_description'] = $keyword_name441. $tag_info['emp_tag_id'].':'.$tag_info['emp_first_name'] .' | '.$comments;
				}else{
					$data['notes_description'] = $keyword_name441.''.$comments;
				}
				
				
				$data['status_total_time'] = $status_total_time;
				$data['date_added'] = $date_added;
				$data['note_date'] = $date_added;
				$data['notetime'] = $notetime;
				
				$data['phone_device_id'] = $this->request->post['phone_device_id'];
				$data['device_unique_id'] = $this->request->post['device_unique_id'];
						
				if($this->request->post['is_android'] != null && $this->request->post['is_android'] != ""){
					$data['is_android'] = $this->request->post['is_android'];
				}else{
					$data['is_android'] = '1';
				}
				
				
				
				$data['override_monitor_time_user_id_checkbox'] = $this->request->post['override_monitor_time_user_id_checkbox'];
				$data['override_monitor_time_user_id'] = $this->request->post['override_monitor_time_user_id'];
				$data['keyword_id'] = $this->request->get['keyword_id'];
				$data['monitor_time'] = $keywordData2['monitor_time'];
				$data['monitor_time_1'] = '1';
				
				//var_dump($data);
				if($this->request->post['device_unique_id'] != null && $this->request->post['device_unique_id'] != ""){
					$exist_note_info = $this->model_notes_notes->getexistnotes($data, $facilities_id);
				
					if(!empty($exist_note_info)){
						$notes_id = $exist_note_info['notes_id'];
						$device_unique_id = $exist_note_info['device_unique_id'];
					}else{
						
						$notes_id = $this->model_notes_notes->jsonaddnotes($data, $facilities_id);
						$device_unique_id = $this->request->post['device_unique_id'];
					}
				}else{
					$notes_id = $this->model_notes_notes->jsonaddnotes($data, $facilities_id);
					$device_unique_id = $this->request->post['device_unique_id'];
				}
				
				$this->load->model('api/facerekognition');
				$fre_array2 = array();
				$fre_array2['face_notes_file'] = $this->request->post['face_notes_file'];
				$fre_array2['outputFolder'] = $this->request->post['outputFolder'];
				$fre_array2['face_not_verify'] = $this->request->post['face_not_verify'];
				$fre_array2['facilities_id'] = $this->request->post['facilities_id'];
				$fre_array2['notes_file'] = $facerekognition_response['imagedata']['notes_file'];
				$fre_array2['outputFolder_1'] = $facerekognition_response['imagedata']['outputFolder'];
				$fre_array2['notes_id'] = $notes_id;
				$s3_url = $this->model_api_facerekognition->savefacerekognitionnotes($fre_array2);
				
				//die;
				if($keywordData2['monitor_time'] == '1'){
					
					if($keywordData2['end_relation_keyword'] == '1'){
						$a3 = array();
						$a3['keyword_id'] = $keywordData2['relation_keyword_id'];
						$a3['user_id'] = $this->request->post['user_id'];
						$a3['facilities_id'] = $facilities_id;
						$a3['is_monitor_time'] = '1';
						$active_note_info2 = $this->model_notes_notes->getNotebyactivenote($a3);
						
						//var_dump($active_note_info2);
						
						//echo "<hr>";
						
					if($this->request->post['override_monitor_time_user_id_checkbox'] == '1'){
							
							$note_info = $this->model_notes_notes->getNote($this->request->post['override_monitor_time_user_id']);
							
							//var_dump($note_info);
							//echo "<hr>";
							
							$a3e = array();
							$a3e['notes_id'] = $note_info['notes_id'];
							$a3e['facilities_id'] = $facilities_id;
							$a3e['is_monitor_time'] = '1';
							$active_note_info2e = $this->model_notes_notes->getNotebyactivenote($a3e);
							
							$keywordData21 = $this->model_setting_keywords->getkeywordDetail($active_note_info2e['keyword_id']);
								
							//var_dump($keywordData21);
							//echo "<hr>";
							$keywordData212 = $this->model_setting_keywords->getkeywordDetail($keywordData21['relation_keyword_id']);
							
							/*if($keywordData2['keyword_id'] != $active_note_info2e['keyword_id']){*/
							if($active_note_info2e['keyword_id'] != "" &&  $active_note_info2e['keyword_id'] != null){
								
								$adata1 = array();
								$adata1['notes_id'] = $active_note_info2e['notes_id'];
								$adata1['keyword_id'] = $active_note_info2e['keyword_id'];
								$adata1['notes_by_keyword_id'] = $active_note_info2e['notes_by_keyword_id'];
								$adata1['facilities_id'] = $facilities_id;
											
								$this->model_notes_notes->updatetnotes_by_keywordm($adata1);
								
								$adata2 = array();
								$adata2['notes_id'] = $notes_id;
								$adata2['override_monitor_time_user_id'] = $note_info['user_id'];
								$adata2['facilities_id'] = $facilities_id;
								$adata2['keyword_id'] = $keywordData212['keyword_id'];
								$this->model_notes_notes->updatetnotes_by_keywordo($adata2);
								
								$adata3 = array();
								$adata3['notes_id'] = $notes_id;
								$adata3['assign_to'] = $note_info['user_id'];
								$adata3['facilities_id'] = $facilities_id;
								$this->model_notes_notes->updatetnotesassign($adata3);
								
								
								
							}
							
						}else{
							
							//var_dump($active_note_info2);
							//echo "<hr>";
							$keywordData21 = $this->model_setting_keywords->getkeywordDetail($active_note_info2['keyword_id']);
							
							//var_dump($keywordData21);
							//echo "<hr>";
							$keywordData212 = $this->model_setting_keywords->getkeywordDetail($keywordData21['relation_keyword_id']);
							
							//var_dump($keywordData212);
							
							//echo "<hr>";
							
							/*$adata1 = array();
							$adata1['notes_id'] = $keywordData212['notes_id'];
							$adata1['keyword_id'] = $keywordData212['keyword_id'];
							$adata1['notes_by_keyword_id'] = $keywordData212['notes_by_keyword_id'];
							$adata1['facilities_id'] = $facilities_id;
							*/
							
							$adata1 = array();
							$adata1['notes_id'] = $active_note_info2['notes_id'];
							$adata1['keyword_id'] = $active_note_info2['keyword_id'];
							$adata1['notes_by_keyword_id'] = $active_note_info2['notes_by_keyword_id'];
							$adata1['facilities_id'] = $facilities_id;
										
							$this->model_notes_notes->updatetnotes_by_keywordm($adata1);
							
							
							$adata3 = array();
							$adata3['notes_id'] = $notes_id;
							$adata3['assign_to'] = $this->request->post['user_id'];
							$adata3['facilities_id'] = $facilities_id;
							$this->model_notes_notes->updatetnotesassign($adata3);
							
							
							
						}
							
					}else{
						
						$a3 = array();
						$a3['keyword_id'] = $keywordData2['keyword_id'];
						$a3['user_id'] = $this->request->post['user_id'];
						$a3['facilities_id'] = $facilities_id;
						$a3['is_monitor_time'] = '1';
						$active_note_info2 = $this->model_notes_notes->getNotebyactivenote($a3);
						
						if($active_note_info2['keyword_id'] != null && $active_note_info2['keyword_id'] != ""){
							
							$adata1 = array();
							$adata1['notes_id'] = $active_note_info2['notes_id'];
							$adata1['keyword_id'] = $active_note_info2['keyword_id'];
							$adata1['notes_by_keyword_id'] = $active_note_info2['notes_by_keyword_id'];
							$adata1['facilities_id'] = $facilities_id;
										
							$this->model_notes_notes->updatetnotes_by_keywordm($adata1);
							
							
							$adata3 = array();
							$adata3['notes_id'] = $notes_id;
							$adata3['assign_to'] = $this->request->post['user_id'];
							$adata3['facilities_id'] = $facilities_id;
							$this->model_notes_notes->updatetnotesassign($adata3);
							
							
							
							if($keywordData2['relation_keyword_id'] != null && $keywordData2['relation_keyword_id'] != "0"){
								$this->load->model('setting/image');
								
								$keywordData23rs = $this->model_setting_keywords->getkeywordDetail($keywordData2['relation_keyword_id']);
											
								/*$file16 = 'icon/'.$keywordData23rs['keyword_image'];
								$newfile84 = $this->model_setting_image->resize($file16, 50, 50);
								$newfile216 = DIR_IMAGE . $newfile84;
								$file124 = HTTP_SERVER . 'image/'.$newfile84;
								
								$imageData132 = base64_encode(file_get_contents($newfile216));
								
								if($newfile84 != null && $newfile84 != ""){
									$keyword_icon = 'data:'.$this->mime_content_type($file124).';base64,'.$imageData132;
								}else{
									$keyword_icon = '';
								}*/
								
								$keyword_file = $keywordData23rs['keyword_image'];
								
								$adata4 = array();
								$adata4['keyword_id'] = $keywordData23rs['keyword_id'];
								$adata4['keyword_name'] = $keywordData23rs['keyword_name'];
								$adata4['keyword_file'] = $keyword_file;
								$adata4['keyword_file_url'] = $keyword_icon;
								$adata4['keyword_id_2'] = $active_note_info2['keyword_id'];
								$adata4['facilities_id'] = $facilities_id;
								$adata4['notes_id'] = $notes_id;
								$this->model_notes_notes->updatenotes_by_keyword_detail($adata4);
								
								
								
								$note_infou = $this->model_notes_notes->getNote($notes_id);
										
								//var_dump($note_infou);
								
								$notes_description2 = $note_infou['notes_description'];
								
								$notes_description2 = str_ireplace($keywordData2['keyword_name'], $keywordData23rs['keyword_name'],$notes_description2);
								
								//var_dump($notes_description2);
								
								$this->model_notes_notes->updatenotecontent($notes_description2, $notes_id);
								
								
								
								
							}
							
							
							if($this->request->post['override_monitor_time_user_id_checkbox'] == '1'){
								
								$note_info = $this->model_notes_notes->getNote($this->request->post['override_monitor_time_user_id']);
								
								//var_dump($note_info);
								//echo "<hr>";
								
								$a3e = array();
								$a3e['notes_id'] = $note_info['notes_id'];
								$a3e['facilities_id'] = $facilities_id;
								$a3e['is_monitor_time'] = '1';
								$active_note_info2e = $this->model_notes_notes->getNotebyactivenote($a3e);
								
								/*if($keywordData2['keyword_id'] != $active_note_info2e['keyword_id']){*/
								if($active_note_info2e['keyword_id'] != "" &&  $active_note_info2e['keyword_id'] != null){
									
									$adata1 = array();
									$adata1['notes_id'] = $active_note_info2e['notes_id'];
									$adata1['keyword_id'] = $active_note_info2e['keyword_id'];
									$adata1['notes_by_keyword_id'] = $active_note_info2e['notes_by_keyword_id'];
									$adata1['facilities_id'] = $facilities_id;
												
									$this->model_notes_notes->updatetnotes_by_keywordm($adata1);
									
									$adata2 = array();
									$adata2['notes_id'] = $notes_id;
									$adata2['override_monitor_time_user_id'] = $note_info['user_id'];
									$adata2['facilities_id'] = $facilities_id;
									$adata2['keyword_id'] = $active_note_info2e['keyword_id'];
									$this->model_notes_notes->updatetnotes_by_keywordo($adata2);
									
									$adata3 = array();
									$adata3['notes_id'] = $notes_id;
									$adata3['assign_to'] = $note_info['user_id'];
									$adata3['facilities_id'] = $facilities_id;
									$this->model_notes_notes->updatetnotesassign($adata3);
									
									
									
									$keywordData23 = $this->model_setting_keywords->getkeywordDetail($active_note_info2e['keyword_id']);
									
									//var_dump($keywordData23);
									//echo "<hr>";
									
									if($keywordData23['relation_keyword_id'] != null && $keywordData23['relation_keyword_id'] != "0"){
										
										$keywordData23r = $this->model_setting_keywords->getkeywordDetail($keywordData23['relation_keyword_id']);
									
										//var_dump($keywordData23r);
										
										/*$this->load->model('setting/image');
													
										$file16 = 'icon/'.$keywordData23r['keyword_image'];
										$newfile84 = $this->model_setting_image->resize($file16, 50, 50);
										$newfile216 = DIR_IMAGE . $newfile84;
										$file124 = HTTP_SERVER . 'image/'.$newfile84;
										
										$imageData132 = base64_encode(file_get_contents($newfile216));
										
										if($newfile84 != null && $newfile84 != ""){
											$keyword_icon = 'data:'.$this->mime_content_type($file124).';base64,'.$imageData132;
										}else{
											$keyword_icon = '';
										}
										*/
										$keyword_file = $keywordData23r['keyword_image'];
										
										$adata4 = array();
										$adata4['keyword_id'] = $keywordData23r['keyword_id'];
										$adata4['keyword_name'] = $keywordData23r['keyword_name'];
										$adata4['keyword_file'] = $keyword_file;
										$adata4['keyword_file_url'] = $keyword_icon;
										$adata4['keyword_id_2'] = $active_note_info2e['keyword_id'];
										$adata4['facilities_id'] = $facilities_id;
										$adata4['notes_id'] = $notes_id;
										$this->model_notes_notes->updatenotes_by_keyword_detail($adata4);
										
										
										
										
										$note_infou = $this->model_notes_notes->getNote($notes_id);
										
										//var_dump($note_infou);
										
										$notes_description2 = $note_infou['notes_description'];
										
										$notes_description2 = str_ireplace($keywordData23['keyword_name'], $keywordData23r['keyword_name'],$notes_description2);
										
										//var_dump($notes_description2);
										
										$this->model_notes_notes->updatenotecontent($notes_description2, $notes_id);
										
										
										
										
									}
									
									
								}
								
							}
							
						}else{
							
							
							if($this->request->post['override_monitor_time_user_id_checkbox'] == '1'){
								
								$note_info = $this->model_notes_notes->getNote($this->request->post['override_monitor_time_user_id']);
								
								//var_dump($note_info);
								//echo "<hr>";
								
								$a3e = array();
								$a3e['notes_id'] = $note_info['notes_id'];
								$a3e['facilities_id'] = $facilities_id;
								$a3e['is_monitor_time'] = '1';
								$active_note_info2e = $this->model_notes_notes->getNotebyactivenote($a3e);
								
								/*if($keywordData2['keyword_id'] != $active_note_info2e['keyword_id']){*/
								if($active_note_info2e['keyword_id'] != "" &&  $active_note_info2e['keyword_id'] != null){
									
									$adata1 = array();
									$adata1['notes_id'] = $active_note_info2e['notes_id'];
									$adata1['keyword_id'] = $active_note_info2e['keyword_id'];
									$adata1['notes_by_keyword_id'] = $active_note_info2e['notes_by_keyword_id'];
									$adata1['facilities_id'] = $facilities_id;
												
									$this->model_notes_notes->updatetnotes_by_keywordm($adata1);
									
									$adata2 = array();
									$adata2['notes_id'] = $notes_id;
									$adata2['override_monitor_time_user_id'] = $note_info['user_id'];
									$adata2['facilities_id'] = $facilities_id;
									$adata2['keyword_id'] = $active_note_info2e['keyword_id'];
									$this->model_notes_notes->updatetnotes_by_keywordo($adata2);
									
									$adata3 = array();
									$adata3['notes_id'] = $notes_id;
									$adata3['assign_to'] = $note_info['user_id'];
									$adata3['facilities_id'] = $facilities_id;
									$this->model_notes_notes->updatetnotesassign($adata3);
									
									
									
									$keywordData23 = $this->model_setting_keywords->getkeywordDetail($active_note_info2e['keyword_id']);
									
									//var_dump($keywordData23);
									//echo "<hr>";
									
									if($keywordData23['relation_keyword_id'] != null && $keywordData23['relation_keyword_id'] != "0"){
										
										$keywordData23r = $this->model_setting_keywords->getkeywordDetail($keywordData23['relation_keyword_id']);
									
										//var_dump($keywordData23r);
										
										/*$this->load->model('setting/image');
													
										$file16 = 'icon/'.$keywordData23r['keyword_image'];
										$newfile84 = $this->model_setting_image->resize($file16, 50, 50);
										$newfile216 = DIR_IMAGE . $newfile84;
										$file124 = HTTP_SERVER . 'image/'.$newfile84;
										
										$imageData132 = base64_encode(file_get_contents($newfile216));
										
										if($newfile84 != null && $newfile84 != ""){
											$keyword_icon = 'data:'.$this->mime_content_type($file124).';base64,'.$imageData132;
										}else{
											$keyword_icon = '';
										}*/
										
										$keyword_file = $keywordData23r['keyword_image'];
										
										$adata4 = array();
										$adata4['keyword_id'] = $keywordData23r['keyword_id'];
										$adata4['keyword_name'] = $keywordData23r['keyword_name'];
										$adata4['keyword_file'] = $keyword_file;
										$adata4['keyword_file_url'] = $keyword_icon;
										$adata4['keyword_id_2'] = $active_note_info2e['keyword_id'];
										$adata4['facilities_id'] = $facilities_id;
										$adata4['notes_id'] = $notes_id;
										$this->model_notes_notes->updatenotes_by_keyword_detail($adata4);
										
										
										
										
										$note_infou = $this->model_notes_notes->getNote($notes_id);
										
										//var_dump($note_infou);
										
										$notes_description2 = $note_infou['notes_description'];
										
										$notes_description2 = str_ireplace($keywordData23['keyword_name'], $keywordData23r['keyword_name'],$notes_description2);
										
										//var_dump($notes_description2);
										
										$this->model_notes_notes->updatenotecontent($notes_description2, $notes_id);
										
										
										
										
									
									}
									
									
								}
								
							}else{
								
								$adata2 = array();
								$adata2['notes_id'] = $notes_id;
								$adata2['user_id'] = $this->request->post['user_id'];
								$adata2['facilities_id'] = $facilities_id;
								$adata2['keyword_id'] = $keywordData2['keyword_id'];
								$this->model_notes_notes->updatetnotes_by_keywordo($adata2);
								
								$adata3 = array();
								$adata3['notes_id'] = $notes_id;
								$adata3['assign_to'] = $this->request->post['user_id'];
								$adata3['facilities_id'] = $facilities_id;
								$this->model_notes_notes->updatetnotesassign($adata3);
							
								
							}
						}
						
					}
				}
				
				
					}else{
						$data = array ();
				
						if ($this->request->post ['signature'] != null && $this->request->post ['signature'] != "") {
							$data ['imgOutput'] = $this->request->post ['signature'];
						}
						
						$data ['highlighter_id'] = $this->request->post ['highlighter_id'];
						$data ['highlighter_value'] = $this->request->post ['highlighter_value'];
						$data ['notes_description'] = $this->request->post ['notes_description'];
						$data ['notes_pin'] = $this->request->post ['notes_pin'];
						$data ['user_id'] = $this->request->post ['user_id'];
						
						$data ['notetime'] = $this->request->post ['notetime'];
						$data ['text_color'] = $this->request->post ['text_color'];
						$data ['note_date'] = $this->request->post ['note_date'];
						
						$data ['notes_file'] = $this->request->post ['notes_file'];
						$data ['facilitytimezone'] = $this->request->post ['facilitytimezone'];
						
						$data ['keyword_file'] = $this->request->post ['keyword_file'];
						$data ['offline'] = $this->request->post ['offline'];
						$data ['emp_tag_id'] = $this->request->post ['emp_tag_id'];
						$data ['tags_id'] = $this->request->post ['tags_id'];
						
						$data ['emp_tag_id_list'] = $this->request->post ['emp_tag_id_list'];
						$data ['tags_id_list'] = $this->request->post ['tags_id_list'];
						
						$data ['date_added'] = $this->request->post ['date_added'];
						$notes_file_url = $this->request->post ['notes_file_url'];
						
						$data ['notes_type'] = $this->request->post ['notes_type'];
						$data ['incidentform_id'] = $this->request->post ['incidentform_id'];
						$data ['checklist_id'] = $this->request->post ['checklist_id'];
						$data ['strike_note_type'] = $this->request->post ['strike_note_type'];
						$data ['formsids'] = $this->request->post ['formsids'];
						
						$data ['override_monitor_time_user_id_checkbox'] = $this->request->post ['override_monitor_time_user_id_checkbox'];
						$data ['override_monitor_time_user_id'] = $this->request->post ['override_monitor_time_user_id'];
						$data ['comments'] = $this->request->post ['comments'];
						$data ['monitor_time_1'] = '2';
						
						$data ['phone_device_id'] = $this->request->post ['phone_device_id'];
						$data ['device_unique_id'] = $this->request->post ['device_unique_id'];
						
						$data ['transcripts'] = $this->request->post ['transcripts'];
						$data ['multipleimages'] = $this->request->post ['multipleimages'];
						$data ['mutikeywords'] = $this->request->post ['mutikeywords'];
						
						$data ['clienttype'] = $this->request->post ['clienttype'];
						$data ['role_call'] = $this->request->post ['role_call'];
						$data ['multifacilities'] = $this->request->post ['multifacilities'];
						
						$data ['in_total'] = $this->request->post ['in_total'];
						$data ['out_total'] = $this->request->post ['out_total'];
						$data ['manual_total'] = $this->request->post ['manual_total'];
						
						if ($this->request->post ['is_android'] != null && $this->request->post ['is_android'] != "") {
							$data ['is_android'] = $this->request->post ['is_android'];
						} else {
							$data ['is_android'] = '1';
						}
						
						$notes_description = $this->request->post['notes_description'];
						
						$jsonData1 = stripslashes ( html_entity_decode ( $_REQUEST ['activenotes'] ) );
						$activenotes = json_decode ( $jsonData1, true );
						
						
						$jsonData2 = stripslashes ( html_entity_decode ( $_REQUEST ['clients'] ) );
						$clients = json_decode ( $jsonData2, true );
						
						$jsonData3 = stripslashes ( html_entity_decode ( $_REQUEST ['locations'] ) );
						$locations = json_decode ( $jsonData3, true );
						
						$jsonData4 = stripslashes ( html_entity_decode ( $_REQUEST ['facilitiesids'] ) );
						$facilitiesids = json_decode ( $jsonData4, true );
						
						$jsonData5 = stripslashes ( html_entity_decode ( $_REQUEST ['multifacilities'] ) );
						$multifacilities = json_decode ( $jsonData5, true );
						
						$jsonData56 = stripslashes ( html_entity_decode ( $_REQUEST ['userids'] ) );
						$userids = json_decode ( $jsonData56, true );
						
						
						
						$this->load->model ( 'setting/keywords' );
						$this->load->model ( 'setting/tags' );
						$this->load->model ( 'setting/locations' );
						$this->load->model ( 'facilities/facilities' );

						$aids = array();
						$aids2 = array();
						
						$aids = array();
						$aactivenotes = array();
						
						foreach($activenotes as $activenote){
							$klocation_info12 = $this->model_setting_keywords->getkeywordDetail($activenote['valueId']);
							
							//$notes_description = str_ireplace($klocation_info12['keyword_name'],"",$notes_description);
							
							/*$aids[$activenote['facilityId']]['activenotes'][] = array (
								'valueId' => $activenote['valueId'],
							);*/
							
							
							$aactivenotes[] = array (
								'valueId' => $activenote['valueId'],
							);
						}
						
						foreach($clients as $client){
							$tag_info = $this->model_setting_tags->getTag($client['valueId']);
							$fname = '['.$tag_info['emp_first_name'] . ' ' . $tag_info ['emp_last_name'].']';
							
							$notes_description = str_replace($fname,"", $notes_description);
							
							$aids[$client['facilityId']]['clients'][] = array (
								'valueId' => $client['valueId'],
								'add_type' => $client['add_type'],
								'rollcall' => $client['rollcall']
							);
						}
						
						//var_dump($notes_description);
						
						foreach($locations as $location){
							$location_info12 = $this->model_setting_locations->getlocation($location['valueId']);
							
							//var_dump($location_info12['location_name']);
							
							$notes_description = str_ireplace('['.$location_info12['location_name'].']',"",$notes_description);
							//var_dump($notes_description);
							if($location['facilityId'] != null && $location['facilityId'] != ""){
								$facilityId = $location['facilityId'];
							}else{
								$facilityId = $this->request->post ['facilities_id'];
							}
							
							$aids[$facilityId]['locations'][] = array (
								'valueId' => $location['valueId'],
							);
						}
						
						//die;
						foreach($userids as $usid){
							$user_info12 = $this->model_user_user->getUser($usid['valueId']);
							
							$notes_description = str_ireplace('['.$user_info12['username'].']',"",$notes_description);
							
							/*$aids[$usid['facilityId']]['usersids'][] = array (
								'valueId' => $usid['valueId'],
							);*/
						}
						
						foreach($facilitiesids as $facilitiesid){
							$facilityinfo = $this->model_facilities_facilities->getfacilities($facilitiesid['valueId']);
							
							$notes_description = str_ireplace('['.$facilityinfo['facility'].']',"", $notes_description);
							
							$aids[$facilitiesid['facilityId']]['facilitiesids'][] = array (
								'valueId' => $facilitiesid['valueId'],
							);
						}
						
						foreach($multifacilities as $facilitiesid1){
							$facilityinfo1 = $this->model_facilities_facilities->getfacilities($facilitiesid1['facilities_id']);
							
							$notes_description = $notes_description;
							
							$aids[$facilityinfo1['facilities_id']]['facilitiesids'][] = array (
								'valueId' => $facilityinfo1['facilities_id'],
								'value' => $facilitiesid1['valueId'],
								'keyword_id' => $facilitiesid1['keyword_id'],
							);
							
							/*$aids[$facilitiesid1['facilities_id']]['activenotes'][] = array (
								'valueId' => $facilitiesid1['keyword_id'],
								'value' => 1,
							);*/
							
							$aactivenotes[] = array (
								'valueId' => $facilitiesid1['keyword_id'],
							);
						}
						
						
						$notesids = array();
						$resulsst = $this->model_facilities_facilities->getfacilities($this->request->post ['facilities_id']);
						if($resulsst['no_distribution'] == '1'){
							if(!empty($clients)){
							$facilities_id = $this->request->post ['facilities_id'];
							if(!empty($aids)){
								foreach($aids as $aid){
									$data['notes_description'] = $notes_description;
									//var_dump($facilities_id);
									$data ['keyword_file1'] = array();
									$data ['tags_id_list2'] = array();
									$data ['locationsid'] = array();
									$aidsss = array();
									$aidsss1 = '';
									
									if($aid['clients'] != null && $aid['clients'] != ""){
										
										foreach($aid['clients'] as $clid){
											$data ['keyword_file1'] = array();
											$data ['tags_id_list2'] = array();
											$data ['locationsid'] = array();
											$aidsss = array();
											$aidsss1 = '';
											if($aid['activenotes'] != null && $aid['activenotes'] != ""){
												foreach($aid['activenotes'] as $acitvid){
													$keywordData2 = $this->model_setting_keywords->getkeywordDetail ($acitvid['valueId']);
													$aidsss[] = $keywordData2['keyword_image'];
												}
												$aidsss1 = implode ( ",", $aidsss );
											}
											
											$data ['keyword_file1'] = $aidsss1;
											
											$tags_id_list = array();
											//$tags_id_list[] = $clid['valueId'];
										
											$data ['tags_id_list2'] = $clid;
											
											
											
											$data['notes_description'] = $notes_description;
										
											$this->load->model ( 'facilities/facilities' );
											$facilities_info = $this->model_facilities_facilities->getfacilities ( $facilities_id );
											
											$this->load->model ( 'setting/timezone' );
										
											$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
											$facilitytimezone = $timezone_info ['timezone_value'];
											
											$data ['facilitytimezone'] = $facilitytimezone;
											
											$data ['config_multiple_activenote'] = $facilities_info ['config_multiple_activenote'];
											
											//var_dump($data);
											
											$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $facilities_id );
											$notesids[] = $notes_id;
												
											$device_unique_id = $this->request->post ['device_unique_id'];
											
											
											$this->load->model ( 'api/facerekognition' );
											$fre_array2 = array ();
											$fre_array2 ['face_notes_file'] = $this->request->post ['face_notes_file'];
											$fre_array2 ['face_not_verify'] = $this->request->post ['face_not_verify'];
											$fre_array2 ['outputFolder'] = $this->request->post ['outputFolder'];
											$fre_array2 ['facilities_id'] = $facilities_id;
											$fre_array2 ['notes_file'] = $facerekognition_response ['imagedata'] ['notes_file'];
											$fre_array2 ['outputFolder_1'] = $facerekognition_response ['imagedata'] ['outputFolder'];
											$fre_array2 ['notes_id'] = $notes_id;
											
											// var_dump($fre_array2);
											
											$s3_url = $this->model_api_facerekognition->savefacerekognitionnotes ( $fre_array2 );
											
										
										}
									}
								}
								
							}
							}
						
						}
						
						
						
						if(!empty($aids)){
							foreach($aids as $facilities_id =>$aid){
								
								$notesdesc = " ";
								$username1 = " ";
								
								$data['notes_description'] = $notes_description;
								//var_dump($facilities_id);
								$data ['keyword_file1'] = array();
								//$data ['tags_id_list1'] = array();
								$data ['tags_id_list2'] = array();
								$data ['locationsid'] = array();
								$aidsss = array();
								$aidsss1 = '';
								$aidsss122 = '';
								
								
								if(!empty($aactivenotes)){
									foreach($aactivenotes as $activenote){
										$keywordData2 = $this->model_setting_keywords->getkeywordDetail($activenote['valueId']);
										$aidsss[] = $keywordData2['keyword_image'];
										$aidsss122 .= $keywordData2['keyword_name'].' ';
									}
									$aidsss1 = implode ( ",", $aidsss );
								}
								$data ['keyword_file1'] = $aidsss1;
								
								$usidss = array();
								if(!empty($userids)){
									foreach($userids as $usid){
										$user_info12 = $this->model_user_user->getUser($usid['valueId']);
										
										$notesdesc .= ' '.$user_info12['username'].' | ';
									
										$usidss[] = $usid['valueId'];
									}
									
									$data['usid'] = $usidss;
									
								}
								
								/*if($aid['activenotes'] != null && $aid['activenotes'] != ""){
									foreach($aid['activenotes'] as $acitvid){
										$keywordData2 = $this->model_setting_keywords->getkeywordDetail ($acitvid['valueId']);
										$aidsss[] = $keywordData2['keyword_image'];
										$aidsss122 .= $keywordData2['keyword_name'].' ';
									}
									$aidsss1 = implode ( ",", $aidsss );
									$notesdesc .= $notes_description;
								}
								$data ['keyword_file1'] = $aidsss1;
								*/
								
								
								
								if($aid['clients'] != null && $aid['clients'] != ""){
									$tags_id_list = array();
									foreach($aid['clients'] as $clid){
										//$tags_id_list[] = $clid['valueId'];
									}
									
									//$data ['tags_id_list1'] = $tags_id_list;
									$data ['tags_id_list2'] = $aid['clients'];
									
									if($data ['role_call'] != null && $data ['role_call'] != ""){
										$this->load->model('notes/clientstatus');
										$clientstatus_info2 = $this->model_notes_clientstatus->getclientstatus($data ['role_call']);
										$roleCallname = $clientstatus_info2['name'];
										$rstatusname = ' Status changed to | '.$roleCallname;
									}
									
									$notesdesc .=  $rstatusname;
								}
								
								if($aid['locations'] != null && $aid['locations'] != ""){
									$locationsid = array();
									$locationname1  = "";
									foreach($aid['locations'] as $locid){
										
										$location_info12 = $this->model_setting_locations->getlocation($locid['valueId']);
										$locationname1 .= ' '.$location_info12['location_name'].' ';
									
										$locationsid[] = $locid['valueId'];
									}
									$data ['locationsid'] = $locationsid;
									$notesdesc .= $locationname1;
								}
								
								/*if($aid['usersids'] != null && $aid['usersids'] != ""){
									$usid = array();
									foreach($aid['usersids'] as $usercid){
										
										$user_info12 = $this->model_user_user->getUser($usercid['valueId']);
										$username1 .= ' '.$user_info12['username'].' | ';
								
										$usid[] = $usercid['valueId'];
									}
									$data['usid'] = $usid;
									
									$notesdesc .= $username1;
								}*/
								
								
								if($aid['facilitiesids'] != null && $aid['facilitiesids'] != ""){
									//$facilitiesid = array();
									foreach($aid['facilitiesids'] as $facid){
										//$facilitiesid[] = $facid['valueId'];
										if($facid['value'] != null && $facid['value'] != ""){
											$notesdesc .=$facid['value'];
										}
									}
									
									/*if(!in_array($facilities_id, $facilitiesid)){
										//echo 222;
									}*/
								}
								if($notesdesc != null && $notesdesc != ""){
									$data['notes_description'] = $notes_description.' | '.$notesdesc;
								}else{
									$data['notes_description'] = $notes_description;
								}
								//$data['notes_description'] = $notes_description.' | '.$notesdesc;
								
								
								$this->load->model ( 'facilities/facilities' );
								$facilities_info = $this->model_facilities_facilities->getfacilities ( $facilities_id );
								
								$this->load->model ( 'setting/timezone' );
							
								$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
								$facilitytimezone = $timezone_info ['timezone_value'];
								
								$data ['facilitytimezone'] = $facilitytimezone;
								
								$data ['config_multiple_activenote'] = $facilities_info ['config_multiple_activenote'];
								
								
								$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $facilities_id );
								$notesids[] = $notes_id;
							
								$device_unique_id = $this->request->post ['device_unique_id'];
								
								$this->load->model ( 'api/facerekognition' );
								$fre_array2 = array ();
								$fre_array2 ['face_notes_file'] = $this->request->post ['face_notes_file'];
								$fre_array2 ['face_not_verify'] = $this->request->post ['face_not_verify'];
								$fre_array2 ['outputFolder'] = $this->request->post ['outputFolder'];
								$fre_array2 ['facilities_id'] = $facilities_id;
								$fre_array2 ['notes_file'] = $facerekognition_response ['imagedata'] ['notes_file'];
								$fre_array2 ['outputFolder_1'] = $facerekognition_response ['imagedata'] ['outputFolder'];
								$fre_array2 ['notes_id'] = $notes_id;
								
								// var_dump($fre_array2);
								
								$s3_url = $this->model_api_facerekognition->savefacerekognitionnotes ( $fre_array2 );
								
								if ($this->request->post ['clienttags_id'] != null && $this->request->post ['clienttags_id'] != "") {
									
									$this->load->model ( 'setting/tags' );
									
									$data2 = array ();
									$data2 ['tags_id'] = $this->request->post ['clienttags_id'];
									$data2 ['facilities_id'] = $facilities_id;
									$data2 ['facilitytimezone'] = $facilitytimezone;
									
									$data ['comments'] = $this->request->post ['notes_description'];
									
									$notes_id = $this->model_setting_tags->addclientsign ( $data, $data2 );
								}
							}
							
						}else if(!empty($facilitiesids)){
							foreach($facilitiesids as $facilitiesid){
								
								$facilities_id = $facilitiesid['valueId'];
								
								$this->load->model ( 'facilities/facilities' );
								$facilities_info = $this->model_facilities_facilities->getfacilities ( $facilities_id );
								
								$this->load->model ( 'setting/timezone' );
							
								$timezone_info = $this->model_setting_timezone->gettimezone ( $facilities_info ['timezone_id'] );
								$facilitytimezone = $timezone_info ['timezone_value'];
								
								$data ['facilitytimezone'] = $facilitytimezone;
								
								$data ['config_multiple_activenote'] = $facilities_info ['config_multiple_activenote'];
								
								$username1 = "";
								$notesdesc = "";
								
								if(!empty($aactivenotes)){
								foreach($aactivenotes as $activenote){
									
									$keywordData2 = $this->model_setting_keywords->getkeywordDetail($activenote['valueId']);
									
									
										$aidsss[] = $keywordData2['keyword_image'];
										$aidsss122 .= $keywordData2['keyword_name'].' ';
									}
									$aidsss1 = implode ( ",", $aidsss );
								}
								$data ['keyword_file1'] = $aidsss1;
								
								$usidss = array();
								if(!empty($userids)){
									foreach($userids as $usid){
										$user_info12 = $this->model_user_user->getUser($usid['valueId']);
										
										$notesdesc .= ' '.$user_info12['username'].' | ';
									
										$usidss[] = $usid['valueId'];
									}
									
									$data['usid'] = $usidss;
									
								}
								
								if($notesdesc != null && $notesdesc != ""){
									$data['notes_description'] = $notes_description.' | '.$notesdesc;
								}else{
									$data['notes_description'] = $notes_description;
								}
								//$data['notes_description'] = $notes_description.' '.$notesdesc;
								
								
								//$data['notes_description'] = $notes_description;
								
								$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $facilities_id );
								$notesids[] = $notes_id;
								$device_unique_id = $this->request->post ['device_unique_id'];
								
								
								$this->load->model ( 'api/facerekognition' );
								$fre_array2 = array ();
								$fre_array2 ['face_notes_file'] = $this->request->post ['face_notes_file'];
								$fre_array2 ['face_not_verify'] = $this->request->post ['face_not_verify'];
								$fre_array2 ['outputFolder'] = $this->request->post ['outputFolder'];
								$fre_array2 ['facilities_id'] = $facilities_id;
								$fre_array2 ['notes_file'] = $facerekognition_response ['imagedata'] ['notes_file'];
								$fre_array2 ['outputFolder_1'] = $facerekognition_response ['imagedata'] ['outputFolder'];
								$fre_array2 ['notes_id'] = $notes_id;
								
								// var_dump($fre_array2);
								
								$s3_url = $this->model_api_facerekognition->savefacerekognitionnotes ( $fre_array2 );
								
								if ($this->request->post ['clienttags_id'] != null && $this->request->post ['clienttags_id'] != "") {
									
									$this->load->model ( 'setting/tags' );
									
									$data2 = array ();
									$data2 ['tags_id'] = $this->request->post ['clienttags_id'];
									$data2 ['facilities_id'] = $facilities_id;
									$data2 ['facilitytimezone'] = $facilitytimezone;
									
									$data ['comments'] = $this->request->post ['notes_description'];
									
									$notes_id = $this->model_setting_tags->addclientsign ( $data, $data2 );
								}
							}
						}else{
							
							$facilities_id = $this->request->post ['facilities_id'];
							if($facilities_id != null && $facilities_id != ""){	
							$this->load->model ( 'facilities/facilities' );
							$facilities_info = $this->model_facilities_facilities->getfacilities ( $facilities_id );
							
							$data ['config_multiple_activenote'] = $facilities_info ['config_multiple_activenote'];
							
							
							$username1 = "";
							$notesdesc = "";
							
							if(!empty($aactivenotes)){
								foreach($aactivenotes as $activenote){
									
									$keywordData2 = $this->model_setting_keywords->getkeywordDetail($activenote['valueId']);
									$aidsss[] = $keywordData2['keyword_image'];
									$aidsss122 .= $keywordData2['keyword_name'].' ';
								}
								$aidsss1 = implode ( ",", $aidsss );
							}
							$data ['keyword_file1'] = $aidsss1;
							$usidss = array();
							if(!empty($userids)){
								foreach($userids as $usid){
									$user_info12 = $this->model_user_user->getUser($usid['valueId']);
									
									$notesdesc .= ' '.$user_info12['username'].' | ';
								
									$usidss[] = $usid['valueId'];
								}
								
								$data['usid'] = $usidss;
								
							}
							if($notesdesc != null && $notesdesc != ""){
								$data['notes_description'] = $notes_description.' | '.$notesdesc;
							}else{
								$data['notes_description'] = $notes_description;
							}
							
							
							
							$notes_id = $this->model_notes_notes->jsonaddnotes ( $data, $this->request->post ['facilities_id'] );
								
							$device_unique_id = $this->request->post ['device_unique_id'];
							
							
							$this->load->model ( 'api/facerekognition' );
							$fre_array2 = array ();
							$fre_array2 ['face_notes_file'] = $this->request->post ['face_notes_file'];
							$fre_array2 ['face_not_verify'] = $this->request->post ['face_not_verify'];
							$fre_array2 ['outputFolder'] = $this->request->post ['outputFolder'];
							$fre_array2 ['facilities_id'] = $this->request->post ['facilities_id'];
							$fre_array2 ['notes_file'] = $facerekognition_response ['imagedata'] ['notes_file'];
							$fre_array2 ['outputFolder_1'] = $facerekognition_response ['imagedata'] ['outputFolder'];
							$fre_array2 ['notes_id'] = $notes_id;
							
							// var_dump($fre_array2);
							
							$s3_url = $this->model_api_facerekognition->savefacerekognitionnotes ( $fre_array2 );
						}
						
						}
						
						
						
						
						
					}
			
			if($s3_url != null && $s3_url != ""){
				$s3urlss = $s3_url;
			}else{
				$s3urlss = "";
			}
			
			$note_info = $this->model_notes_notes->getnotes($notes_id);
			
			
			if(!empty($notesids)){
				$notesids1 = implode(",",$notesids);
			}else{
				$notesids1 = "";
			}
			
			//$notesids = array();
			$notesids[] = $notes_id;
			$this->data['facilitiess'][] = array(
				'warning'  => '1',
				'notes_id'  => $notes_id,
				'device_unique_id'  => $device_unique_id,
				's3_url'  => $s3urlss,
				'update_date'  => $note_info['update_date'],
				'date_added'  => $note_info['date_added'],
				'notetime'  => $note_info['notetime'],
				'note_date'  => $note_info['note_date'],
				'notesids'  => $notes_id,
				'notesidsarray'  => $notesids,
			);
			$error = true;
			
		}else{
			$this->data['facilitiess'][] = array(
				'warning'  => $json['warning'],
			);
			$error = false;
		}
		
		$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
		
		$this->response->setOutput(json_encode($value));
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in activenoteindex '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('app_activenoteindex', $activity_data2);
		
		
		} 
	}
	
	public function mime_content_type($filename) {

		try{
		
        $mime_types = array(

            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );

        $ext = strtolower(array_pop(explode('.',$filename)));
        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        }
        elseif (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);
            return $mimetype;
        }
        else {
            return 'application/octet-stream';
        }
		
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices mime_content_type '.$e->getMessage(),
			);
			$this->model_activity_activity->addActivity('app_mime_content_type', $activity_data2);
		
		
		} 
    }
	
}