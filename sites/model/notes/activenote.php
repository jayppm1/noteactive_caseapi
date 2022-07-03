<?php
class Modelnotesactivenote extends Model {

	public function addactivenote($pdata, $fdata) {
		$this->load->model('notes/notes');
		$data = array();
		
		$this->load->model('facilities/facilities');
		$facilities_info = $this->model_facilities_facilities->getfacilities($fdata['facilities_id']);
		if($facilities_info['is_master_facility'] == '1'){
			if($this->session->data['search_facilities_id'] != null && $this->session->data['search_facilities_id'] != ""){
				$facilities_id  = $this->session->data['search_facilities_id']; 
				$facilities_info2 = $this->model_facilities_facilities->getfacilities($facilities_id);
				$this->load->model('setting/timezone');
				$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info2['timezone_id']);
				$timezone_name = $timezone_info['timezone_value'];
			}else{
				$facilities_id = $fdata['facilities_id']; 
			 $timezone_name = $fdata['facilitytimezone'];
			}
		}else{
			 $facilities_id = $fdata['facilities_id']; 
			 $timezone_name = $fdata['facilitytimezone'];
		}
		
		
		
		//$timezone_name = $fdata['facilitytimezone'];
		//$timeZone = date_default_timezone_set($timezone_name);
		
		$noteDate = date('Y-m-d H:i:s', strtotime('now'));
		$date_added = (string) $noteDate;
		
		$notetime = date('H:i:s', strtotime('now'));
		
		if($pdata['imgOutput']){
			$data['imgOutput'] = $pdata['imgOutput'];
		}else{
			$data['imgOutput'] = $pdata['signature'];
		}
		
		$data['notes_pin'] = $pdata['notes_pin'];
		
		
		$this->load->model('user/user');
		$user_info = $this->model_user_user->getUser($pdata['user_id']);
			
			
		$data['user_id'] = $pdata['user_id'];
		
		$update_date = date('Y-m-d H:i:s', strtotime('now'));
		
		//$facilities_id = $fdata['facilities_id'];
		
		$notetime = date('H:i:s', strtotime('now'));
		
		
		if($pdata['tags_id'] != null && $pdata['tags_id'] != ""){
			$this->load->model('setting/tags');
			$tag_info = $this->model_setting_tags->getTag($pdata['tags_id']);
			
			$data['emp_tag_id'] = $tag_info['emp_tag_id'];
			$data['tags_id'] = $tag_info['tags_id'];
		}
		
		
		$this->load->model('setting/keywords');
		$keywordData2 = $this->model_setting_keywords->getkeywordDetail($fdata['keyword_id']);
		
		if($pdata['comments'] != null && $pdata['comments']){
			$comments = ' | '.$pdata['comments'];
		}
		
		if($keywordData2['monitor_time'] == '1'){
			if($keywordData2['end_relation_keyword'] == '1'){
				
				$a3 = array();
				$a3['keyword_id'] = $keywordData2['relation_keyword_id'];
				$a3['user_id'] = $user_info['username'];
				$a3['facilities_id'] = $facilities_id;
				$a3['is_monitor_time'] = '1';
				$active_note_info2 = $this->model_notes_notes->getNotebyactivenote($a3);
				
				//var_dump($active_note_info2);
				
				//echo "<hr>";
				
				if($pdata['override_monitor_time_user_id_checkbox'] == '1'){
						
						$note_info = $this->model_notes_notes->getNote($pdata['override_monitor_time_user_id']);
						
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
				$a3['user_id'] = $user_info['username'];
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
					
					
					if($pdata['override_monitor_time_user_id_checkbox'] == '1'){
						
						$note_info = $this->model_notes_notes->getNote($pdata['override_monitor_time_user_id']);
						
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
					
					//var_dump($pdata['override_monitor_time_user_id_checkbox']);
					
					if($pdata['override_monitor_time_user_id_checkbox'] == '1'){
						
						$note_info = $this->model_notes_notes->getNote($pdata['override_monitor_time_user_id']);
						
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
		
		
		
		if($pdata['tags_id'] != null && $pdata['tags_id'] != ""){
			$data['notes_description'] = $keyword_name441. $tag_info['emp_tag_id'].':'.$tag_info['emp_first_name'] .' | '.$comments;
		}else{
			$data['notes_description'] = $keyword_name441.''.$comments;
		}
		
		
		$data['status_total_time'] = $status_total_time;
		$data['date_added'] = $date_added;
		$data['note_date'] = $date_added;
		$data['notetime'] = $notetime;
		
		$data['override_monitor_time_user_id_checkbox'] = $pdata['override_monitor_time_user_id_checkbox'];
		$data['override_monitor_time_user_id'] = $pdata['override_monitor_time_user_id'];
		$data['keyword_id'] = $fdata['keyword_id'];
		$data['monitor_time'] = $keywordData2['monitor_time'];
		$data['monitor_time_1'] = '1';
		
		//var_dump($data);
		
		$notes_id = $this->model_notes_notes->jsonaddnotes($data, $facilities_id);
		
		$this->load->model('facilities/facilities');
		$facility = $this->model_facilities_facilities->getfacilities($facilities_id);
			
		
	    if ($facility['is_enable_add_notes_by'] == '1') {
	        $sql122 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '4', notes_conut ='0' WHERE notes_id = '" . (int)$notes_id . "' ";
	        $this->db->query($sql122);
	    }
	    if ($facility['is_enable_add_notes_by'] == '3') {
	        $sql13 = "UPDATE `" . DB_PREFIX . "notes` SET notes_type = '5', notes_conut ='0' WHERE notes_id = '" . (int)$notes_id . "' ";
	        $this->db->query($sql13);
	    }
		
		
	    if ($facility['is_enable_add_notes_by'] == '1') {
	        if ($this->session->data['local_image_dir'] != null && $this->session->data['local_image_dir'] != "") {
	           
	            	
	            $notes_file = $this->session->data['local_notes_file'];
	            $outputFolder = $this->session->data['local_image_dir'];
	            require_once (DIR_SYSTEM . 'library/awsstorage/s3_config.php');
	            $this->load->model('notes/notes');
	            $this->model_notes_notes->updateuserpicture($s3file, $notes_id);
	            
	            if ($this->session->data['username_confirm'] != null && $this->session->data['username_confirm'] != "") {
	                $this->model_notes_notes->updateuserverified('2', $notes_id);
	            }
	            	
	            if ($this->session->data['username_confirm'] == null && $this->session->data['username_confirm'] == "") {
	                $this->model_notes_notes->updateuserverified('1', $notes_id);
	            }
	            	
	            unlink($this->session->data['local_image_dir']);
	            unset($this->session->data['username_confirm']);
	            unset($this->session->data['local_image_dir']);
	            unset($this->session->data['local_image_url']);
	            unset($this->session->data['local_notes_file']);
	        }
	    }
		
		//die;
		if($keywordData2['monitor_time'] == '1'){
			
			if($keywordData2['end_relation_keyword'] == '1'){
				$a3 = array();
				$a3['keyword_id'] = $keywordData2['relation_keyword_id'];
				$a3['user_id'] = $user_info['username'];
				$a3['facilities_id'] = $facilities_id;
				$a3['is_monitor_time'] = '1';
				$active_note_info2 = $this->model_notes_notes->getNotebyactivenote($a3);
				
				//var_dump($active_note_info2);
				
				//echo "<hr>";
				
			if($pdata['override_monitor_time_user_id_checkbox'] == '1'){
					
					$note_info = $this->model_notes_notes->getNote($pdata['override_monitor_time_user_id']);
					
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
					
					
					$adata1 = array();
					$adata1['notes_id'] = $active_note_info2['notes_id'];
					$adata1['keyword_id'] = $active_note_info2['keyword_id'];
					$adata1['notes_by_keyword_id'] = $active_note_info2['notes_by_keyword_id'];
					$adata1['facilities_id'] = $facilities_id;
								
					$this->model_notes_notes->updatetnotes_by_keywordm($adata1);
					
					
					$adata3 = array();
					$adata3['notes_id'] = $notes_id;
					$adata3['assign_to'] = $user_info['username'];
					$adata3['facilities_id'] = $facilities_id;
					$this->model_notes_notes->updatetnotesassign($adata3);
					
					
				}
					
			}else{
				
				$a3 = array();
				$a3['keyword_id'] = $keywordData2['keyword_id'];
				$a3['user_id'] = $user_info['username'];
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
					$adata3['assign_to'] = $user_info['username'];
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
					
					
					if($pdata['override_monitor_time_user_id_checkbox'] == '1'){
						
						$note_info = $this->model_notes_notes->getNote($pdata['override_monitor_time_user_id']);
						
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
								/*
								$this->load->model('setting/image');
											
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
						
					}
					
				}else{
					
					
					if($pdata['override_monitor_time_user_id_checkbox'] == '1'){
						
						$note_info = $this->model_notes_notes->getNote($pdata['override_monitor_time_user_id']);
						
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
								/*
								$this->load->model('setting/image');
											
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
						$adata2['user_id'] = $user_info['username'];
						$adata2['facilities_id'] = $facilities_id;
						$adata2['keyword_id'] = $keywordData2['keyword_id'];
						$this->model_notes_notes->updatetnotes_by_keywordo($adata2);
						
						$adata3 = array();
						$adata3['notes_id'] = $notes_id;
						$adata3['assign_to'] = $user_info['username'];
						$adata3['facilities_id'] = $facilities_id;
						$this->model_notes_notes->updatetnotesassign($adata3);
						
						
						
					}
				}
				
			}
		}
		
		
	
		return $notes_id; 	
	}
	
}
?>