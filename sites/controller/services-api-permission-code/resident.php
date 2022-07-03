<?php 
 header('Access-Control-Allow-Origin:'.$_SERVER['HTTP_ORIGIN']);
 header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
 header('Access-Control-Max-Age: 1000');
 header('Access-Control-Allow-Headers: Content-Type');
 header('Content-type: application/json');
 header('Content-Type: text/html; charset=utf-8');
 header("Content-type: bitmap; charset=utf-8");
 
class Controllerservicesresident extends Controller { 
	private $error = array();
	public function jsonClientList(){
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
			
			$this->language->load('notes/notes');
			$this->load->model('setting/shift');
			
			
			
			$data = array();
			$data['facilities_id'] = $this->request->post['facilities_id'];
			
			$data['status'] = '1';
			$this->data['shifts'] = $this->model_setting_shift->getshifts($data);
			
			$this->data['facilitiess'] = array(); 
			$this->load->model('setting/tags');
			$this->load->model('form/form');
			
			$this->load->model('setting/image');
			
			$currentdate = $this->request->post['date_added'];
			
			$date = str_replace('-', '/', $currentdate);
			$res = explode("/", $date);
			$changedDate = $res[2]."-".$res[1]."-".$res[0];
			
			
			$this->load->model('facilities/facilities');
			
			$facilities_info = $this->model_facilities_facilities->getfacilities($this->request->post['facilities_id']);
					
			$this->load->model('setting/timezone');
					
			$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
			$facilitytimezone = $timezone_info['timezone_value'];
			
			
			$datat3 = array();
			$datat3 = array(
				'status' => 1,
				'discharge' => '1',
				'role_call' => '1',
				//'searchdate' => $currentDate,
				'gender2' => $this->request->post['gender'],
				'sort' => 'emp_first_name',
				'facilities_id' => $this->request->post['facilities_id'],
				'emp_tag_id_2' => $this->request->post['search_tags'],
				//'wait_list' => $this->request->post['wait_list'],
				//'all_record' => '1',
			);
			
			$tags = $this->model_setting_tags->getTags($datat3);
			
			$this->load->model('resident/resident');
		
			$this->load->model('createtask/createtask');
			$this->load->model('notes/notes');
			$this->load->model('form/form');
			
			$this->data['tags'] = array();
			
			foreach ($tags as $tag) {
				
				$allform_info = $this->model_form_form->gettagsforma($tag['tags_id']);
			
				if($allform_info != null && $allform_info != ""){
					$screenig_url = $this->url->link('services/form', '' . '&tags_forms_id='.$allform_info['tags_forms_id']. '&tags_id='.$allform_info['tags_id']. '&notes_id='.$allform_info['notes_id']. '&forms_design_id='.$allform_info['custom_form_type']. '&forms_id='.$allform_info['forms_id']. '&facilities_id='.$allform_info['facilities_id'], 'SSL');
				}else{
					$screenig_url = '';
				}
				
				$allforms = $this->model_resident_resident->gettagsforms($tag['tags_id']);
				$forms = array();
				foreach ($allforms as $allform) {
							
					$forms[] = array(
						'forms_id' => $allform['forms_id'],
						'forms_design_id' => $allform['forms_design_id'],
						'form_href' => $this->url->link('form/form', '' . '&forms_id='.$allform['forms_id']. '&tags_id='.$allform['tags_id']. '&notes_id='.$allform['notes_id']. '&forms_design_id='.$allform['forms_design_id']. '&forms_id='.$allform['forms_id'], 'SSL'),			
					); 
				}
				
				$tagcolors = array();
				/*$alltagcolors = $this->model_resident_resident->getagsColors($tag['tags_id']);
				
				foreach ($alltagcolors as $alltagcolor) {
							
					$tagcolors[] = array(
						'color_id' => $alltagcolor['color_id'],
						'text_highliter_div_cl' => $alltagcolor['text_highliter_div_cl'],
					); 
				}*/
				
				$role_call = $tag['role_call'];
				
				if($tag['privacy'] == '2'){
					$upload_file_thumb_1 = '';
					$image_url1 = '';
					$emp_last_name = mb_substr($tag['emp_last_name'], 0, 1);
				}else{
					if($tag['upload_file_thumb'] != null && $tag['upload_file_thumb'] != ""){
						$upload_file_thumb_1 = $tag['upload_file_thumb'];
					}else{
						$upload_file_thumb_1 = $tag['upload_file'];
					}
					
					$emp_last_name = $tag['emp_last_name'];
					
					//$image_url = file_get_contents($upload_file);
					$image_url1 = ''; //'data:image/jpg;base64,'.base64_encode($image_url);
					
					$check_img = $this->model_setting_image->checkresize($tag['upload_file']);
					
				}
				
				
				   $tasksinfo = $this->model_createtask_createtask->getTaskas($tag['tags_id'], $changedDate);
			
					if($tasksinfo){
						$tasksinfo1 = $tasksinfo*100;
				   }else{
					   $tasksinfo1 = '';
				   }
				// var_dump($tasksinfo1);
				if($tag['privacy'] == '2'){
					$upload_file_thumb_1 = '';
					$image_url1 = '';
					$emp_last_name = mb_substr($tag['emp_last_name'], 0, 1);
				}else{
					
					if($tag['upload_file_thumb'] != null && $tag['upload_file_thumb'] != ""){
						$upload_file_thumb_1 = $tag['upload_file_thumb'];
					}else{
						$upload_file_thumb_1 = $tag['upload_file'];
					}
					
					//$upload_file = $tag['upload_file_thumb'];
					$emp_last_name = $tag['emp_last_name'];
					
					//$image_url = file_get_contents($upload_file);
					$image_url1 = ''; // 'data:image/jpg;base64,'.base64_encode($image_url);
					
					$check_img = $this->model_setting_image->checkresize($tag['upload_file']);
				}
				
				$addTime = $this->config->get('config_task_complete');
			
				
				
				$top = '2';
			
				$tasktypes = $this->model_createtask_createtask->getTaskdetails();
			
				
				foreach($tasktypes as $tasktype){
					$taskTotal1 = 0;
					$taskTotal = 0;
				
					$taskTotal1 = $this->model_createtask_createtask->getCountTasklist($this->request->post['facilities_id'], $changedDate, $top, $facilitytimezone, $tag['tags_id']);
					
					$taskTotal = $taskTotal + $taskTotal1;
				}
				
				if($taskTotal){
					$tttaskTotal = $taskTotal;
				}else{
					$tttaskTotal = '';
				}
				//var_dump($taskTotal);
				
				/*$d = array();
				$d['emp_tag_id'] = $tag['tags_id'];
				$d['searchdate'] = $currentdate;
				$d['start'] = 0;
				$d['limit'] = 1;
				$d['advance_search'] = 1;
				$d['advance_date_desc'] = 1;
				$d['facilities_id'] = $this->request->post['facilities_id'];
				
				$lastnotesinfo = $this->model_notes_notes->getnotess($d);
				
				if($lastnotesinfo[0]['notes_description']){
					$nnotes_description = $lastnotesinfo[0]['notes_description'];
				}else{
					$nnotes_description = ''; 
				}*/
				
				$nnotes_description = ''; 
			
				/*$recenttasksinfos = $this->model_createtask_createtask->getrecentTaskdetails($d);
				
				if($recenttasksinfos['description']){
					$tdescription = $recenttasksinfos['description'];
				}else{
					$tdescription = '';
				}*/
				
				$tdescription = '';
				
				/*$form_info = $this->model_form_form->gettagsformav($tag['tags_id']);
				if($form_info){
					$ndate_added = date('D F j, Y', strtotime($form_info['date_added'] .' +90 day'));
					
				}else{
					$ndate_added = '';
				}*/
				
				$ndate_added = '';
				
				$client_medicine = $this->model_resident_resident->gettagModule($tag['tags_id']);
				
				if($client_medicine != null && $client_medicine != ""){
					$tagmed = '1';
				}else{
					$tagmed = '2';
				}
				
				$tagstatusinfo = $this->model_resident_resident->getTagstatusbyId($tag['tags_id']);
			
				if($tagstatusinfo !=NULL && $tagstatusinfo !=""){
					
					$status = $tagstatusinfo['status'];
				}else{
					$status = '';
				}
				
				$alldata = $this->model_createtask_createtask->getalltaskbyid($tag['tags_id']);
				if($alldata !=NULL && $alldata !=""){
					$confirm_alert = "1";
				}else{
					$confirm_alert = "2";
				}
				
				$this->data['tags'][] = array(
					'name' => $tag['emp_first_name'].' '.$emp_last_name,
					'emp_first_name' => $tag['emp_first_name'],
					'emp_tag_id' => $tag['emp_tag_id'],
					'age' => $tag['age'],
					'tags_id' => $tag['tags_id'],
					'gender' => $tag['gender'],
					
					'upload_file' => $tag['upload_file'],
					'upload_file_thumb' => $tag['upload_file_thumb'],
					'upload_file_thumb_1' => $upload_file_thumb_1,
					'check_img' => $check_img,
					//'upload_file' => $upload_file,
					'image_url1' => $image_url1,
					'privacy' => $tag['privacy'],
					'stickynote' => $tag['stickynote'],
					'role_call' => $role_call,
					'tagallforms' => $forms,
					'tagcolors' => $tagcolors,
					'age' => $tag['age'],
					'tagstatus' => $tag['tagstatus'],
					'tags_status_in' => $tag['tags_status_in'],
					'med_mental_health' => $tag['med_mental_health'],
					'alert_info' => $tag['alert_info'],
					'prescription' => $tag['prescription'],
					'restriction_notes' => $tag['restriction_notes'],
					'room' => $tag['room'],
					'date_added' => date('m-d-Y',strtotime($tag['date_added'])),
					'sticky_href' => $this->url->link('resident/resident/getstickynote', ''. '&tags_id='.$tag['tags_id'], 'SSL'),
					'tasksinfo' => $tasksinfo1,
					'tagstatus_info' => $status,
					'taskTotal' => $tttaskTotal,
					'recentnote' => $nnotes_description,
					'recenttasks' => $tdescription ,
					'ndate_added' => $ndate_added ,
					'client_medicine' => $tagmed ,
					'screenig_url' => $screenig_url ,
					'confirm_alert' => $confirm_alert ,
					'discharge_href' => $this->url->link('notes/case&isandroid=1', '' . '&tags_id='.$tag['tags_id']. '&facilities_id=' . $tag['facilities_id'], 'SSL'),
				);	
			}
			
			
			
			$this->load->model('form/form');

			$custom_forms = $this->model_form_form->getforms();
			
			$this->data['custom_forms'] = array();
			foreach ($custom_forms as $custom_form) {

				$this->data['custom_forms'][] = array(
					'forms_id'  => $custom_form['forms_id'],
					'form_name' => $custom_form['form_name'],
					'form_href' => $this->url->link('resident/resident/tagform', '' . '&forms_design_id='.$custom_form['forms_id'], 'SSL'),
				);
			}
			
			
			$this->data['highlighters'] = array();
			
			/*$this->load->model('setting/highlighter');
			$this->load->model('notes/image');

			$highlighters = $this->model_setting_highlighter->gethighlighters();
			
			
			foreach ($highlighters as $highlighter) {

				if ($highlighter['highlighter_icon'] && file_exists(DIR_IMAGE . 'highlighter/'.$highlighter['highlighter_icon'])) {
					$image = $this->model_notes_image->resize('highlighter/'.$highlighter['highlighter_icon'], 50, 50);
				} 
				
				$this->data['highlighters'][] = array(
					'highlighter_id'  => $highlighter['highlighter_id'],
					'highlighter_icon'  => $image,
					'highlighter_name' => $highlighter['highlighter_name'],
					'highlighter_value' => $highlighter['highlighter_value'],
				);
			}
			*/
			

			$this->data['keywords'] = array();
			
			/*
			$this->load->model('setting/keywords');
			$data3 = array(
				'facilities_id' => $this->request->post['facilities_id'],
			);
			
			$keywords = $this->model_setting_keywords->getkeywords($data3);

			
			foreach ($keywords as $keyword) {
				if ($keyword['keyword_image'] && file_exists(DIR_IMAGE . 'icon/'.$keyword['keyword_image'])) {
					$image = $this->model_notes_image->resize('icon/'.$keyword['keyword_image'], 35, 35);
				} 
				$this->data['keywords'][] = array(
					'keyword_id'    => $keyword['keyword_id'],
					'keyword_name'   => $keyword['keyword_name'],
					'keyword_name2'   =>  str_replace(array("\r", "\n"), '', $keyword['keyword_name']),
					'keyword_image'   => $keyword['keyword_image'],
					'img_icon'   => $image,
				);
			}*/
			
			$datai = array();
			$datai = array(
				'status' => 1,
				'discharge' => 1,
				//'role_call' => '1',
				'searchdate' => $currentdate,
				//'gender2' => $this->request->post['gender'],
				'sort' => 'emp_first_name',
				'facilities_id' => $this->request->post['facilities_id'],
				//'all_record' => '1',
				
			);
			
			$intakes_total = $this->model_setting_tags->getTotalTags($datai);
			
			
			$data7 = array();
			$data7 = array(
				'status' => 1,
				'discharge' => 2,
				'searchdate_2' => $currentdate,
				//'role_call' => '2',
				'facilities_id' => $this->request->post['facilities_id'],
				//'all_record' => '1',
				
			);
			
			$dischargetags_total = $this->model_setting_tags->getTotalTags($data7);
			
			$data6 = array();
			$data6 = array(
				'status' => 1,
				//'searchdate' => $currentDate,
				'discharge' => 1,
				'role_call' => '2',
				'facilities_id' =>  $this->request->post['facilities_id'],
				'all_record' => '1',
				
			);
			
			$offsitetags_total = $this->model_setting_tags->getTotalTags($data6);
			
			$data3 = array();
			$data3 = array(
				'status' => 1,
				'discharge' => 1,
				'role_call' => '1',
				//'searchdate' => $currentDate,
				//'gender2' => $this->request->get['gender'],
				'sort' => 'emp_first_name',
				'facilities_id' => $this->request->post['facilities_id'],
				'all_record' => '1',
				
			);
			
			$inhouse_total = $this->model_setting_tags->getTotalTags($data3);
			
			$data4 = array();
			$data4 = array(
				'status' => 1,
				'discharge' => 1,
				'date_added' => $currentdate,
				'gender' => '1',
				//'role_call' => '1',
				'facilities_id' => $this->request->post['facilities_id'],
				'all_record' => '1',
			);
			
			$males_total = $this->model_setting_tags->getTotalTags($data4);
			
			$data5 = array();
			$data5 = array(
				'status' => 1,
				'date_added' => $currentdate,
				'discharge' => 1,
				'gender' => '2',
				//'role_call' => '1',
				'facilities_id' => $this->request->post['facilities_id'],
				'all_record' => '1',
			);
			
			$females_total = $this->model_setting_tags->getTotalTags($data5);
			
			
			
			$data8 = array();
			$data8 = array(
				'status' => 1,
				'discharge' => 1,
				'date_added' => $currentdate,
				'facilities_id' => $this->request->post['facilities_id'],
				'all_record' => '1',
			);
			
			$all_total = $this->model_setting_tags->getTotalTags($data8);
			
			
			
			$data9 = array();
			$data9 = array(
				'status' => 1,
				'discharge' => 1,
				'role_call' => '1',
				'gender' => '1',
				'date_added' => $currentdate,
				'facilities_id' => $this->request->post['facilities_id'],
				'emp_tag_id_2' => $this->request->post['search_tags'],
				//'wait_list' => $this->request->post['wait_list'],
				//'all_record' => '1',
			);
			
			$ihouse_male = $this->model_setting_tags->getTotalTags($data9);
			
			$data10 = array();
			$data10 = array(
				'status' => 1,
				'discharge' => 1,
				'role_call' => '1',
				'gender' => '2',
				'date_added' => $currentdate,
				'facilities_id' => $this->request->post['facilities_id'],
				'emp_tag_id_2' => $this->request->post['search_tags'],
				//'wait_list' => $this->request->post['wait_list'],
				//'all_record' => '1',
			);
			
			$ihouse_female = $this->model_setting_tags->getTotalTags($data10);
			
			$data11 = array();
			$data11 = array(
				'status' => 1,
				'discharge' => 1,
				'role_call' => '1',
				'date_added' => $currentdate,
				'facilities_id' => $this->request->post['facilities_id'],
				'emp_tag_id_2' => $this->request->post['search_tags'],
				//'wait_list' => $this->request->post['wait_list'],
				//'all_record' => '1',
			);
			
			$ihouse_total = $this->model_setting_tags->getTotalTags($data11);
			
			$data51 = array();
			$data51 = array(
				'status' => 1,
				'date_added' => $currentDate,
				'discharge' => 1,
				'gender' => '3',
				//'role_call' => '1',
				'facilities_id' => $this->request->post['facilities_id'],
			);
			
			$non_specific_total = $this->model_setting_tags->getTotalTags($data51);
			
			$datais = array();
			$datais = array(
				'status' => 1,
				'discharge' => 1,
				'form_type' => CUSTOME_INTAKEID,
				'currentdate' => $currentdate,
				//'gender2' => $this->request->get['gender'],
				'sort' => 'emp_first_name',
				'facilities_id' => $this->request->post['facilities_id'],
			);
			
			$screenings_total = $this->model_form_form->gettotalformstatussc($datais);
			
			$this->data['facilitiess'][] = array(
				
				'tags' => $this->data['tags'],
				'custom_forms' => $this->data['custom_forms'],
				'highlighters' => $this->data['highlighters'],
				'keywords' => $this->data['keywords'],
				
				'shifts' => $this->data['shifts'],
				
				'intakes_total' => $intakes_total,
				'discharge_total' => $dischargetags_total,
				'offsite_total' => $offsitetags_total,
				'inhouse_total' => $inhouse_total,
				'maletags_total' => $males_total,
				'femaletags_total' => $females_total,
				'non_specific_total' => $non_specific_total,
				'all_total' => $all_total,
				'ihouse_male' => $ihouse_male,
				'ihouse_female' => $ihouse_female,
				'ihouse_total' => $ihouse_total,
				'screenings_total' => $screenings_total,
				
			);
		
		$value = array('results'=>$this->data['facilitiess'],'status'=>true);
		/*echo json_encode($value);*/
		$this->response->setOutput(json_encode($value));
		
		}catch(Exception $e){
				$this->load->model('activity/activity');
				$activity_data2 = array(
					'data' => 'Error in apptask ClientList',
				);
				$this->model_activity_activity->addActivity('app_jsonClientList', $activity_data2);
		}
	
	}
	
	
	public function jsonAddCensus(){
		
		try{
		$this->data['facilitiess'] = array();
		
		
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
		
		$json = array();
		
		$this->load->model('notes/notes');
		$this->language->load('notes/notes');
		
		if($this->request->post['notes_pin'] != null && $this->request->post['notes_pin'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if (($this->request->post['notes_pin'] != $user_info['user_pin'])) {
				$json['warning'] = 'User Pin not valid!.';
			}
		}
		
		if($this->request->post['user_id'] != null && $this->request->post['user_id'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if (($user_info['status'] == '0')) {
				$json['warning'] = 'User not exit!';
			}
		}
		
		if($json['warning'] == null && $json['warning'] == ""){
			$data = array();
			
			
			$this->load->model('notes/notes');
			$this->load->model('form/form');

			$this->load->model('notes/notes');
			
			$timezone_name = $this->request->post['facilitytimezone'];
			$timeZone = date_default_timezone_set($timezone_name);
			$noteDate = date('Y-m-d H:i:s', strtotime('now'));
			$date_added = (string) $noteDate;
			
			
			$notetime = date('H:i:s', strtotime('now'));
			if($this->request->post['signature'] != null && $this->request->post['signature'] != ""){
				$data['imgOutput'] = $this->request->post['signature'];
			}
			
			$data['notes_pin'] = $this->request->post['notes_pin'];
			$data['user_id'] = $this->request->post['user_id'];
			
			$data['notetime'] = $notetime;
			$data['note_date'] = $date_added;
			$data['facilitytimezone'] = $timezone_name;
			
			
			if($this->request->post['comments'] != null && $this->request->post['comments']){
				$comments = ' | '.$this->request->post['comments'];
			}
			
			$data['notes_description'] = 'Daily Census has been added' . $comments;
			
			$data['date_added'] = $date_added;
			
			$data['phone_device_id'] = $this->request->post['phone_device_id'];
						
			if($this->request->post['is_android'] != null && $this->request->post['is_android'] != ""){
				$data['is_android'] = $this->request->post['is_android'];
			}else{
				$data['is_android'] = '1';
			}
			
			$notes_id = $this->model_notes_notes->jsonaddnotes($data , $this->request->post['facilities_id']);
			
			
			$this->load->model('setting/tags');
			$date_added = date('Y-m-d H:i:s', strtotime('now'));
			$this->model_setting_tags->addCensus($this->request->post, $notes_id,$date_added, $this->request->post['facilities_id'], $timezone_name);
			
			
			$this->model_notes_notes->updatetagscences($notes_id);
			
			
		
			$this->data['facilitiess'][] = array(
				'warning'  => '1',
				'notes_id'  => $notes_id,
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
				'data' => 'Error in addcencus jsonAddCensus',
			);
			$this->model_activity_activity->addActivity('app_addcencus', $activity_data2);
		
		
		} 
	}

	public function addclient(){
		
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
		
		if (($this->request->post['form_submit'] == '1') && $this->validateForm()) {
		
			$this->load->model('setting/tags');
			if($this->request->post['emp_tag_id'] != null && $this->request->post['emp_tag_id'] != ""){
				
				$this->model_setting_tags->updatexittag($this->request->post, $this->request->get['facilities_id']);
				
				$this->model_setting_tags->editTags($this->request->post['emp_tag_id'], $this->request->post, $this->request->get['facilities_id']);
				
				$tags_id = $this->request->post['emp_tag_id'];
			}else{
				$tags_id = $this->model_setting_tags->addTags($this->request->post, $this->request->get['facilities_id']);
			}
			
			$url2 = "";
			$url2 .= '&tags_id=' . $tags_id;
			$url2 .= '&new_form=2';
			
			if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
				$url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
			}
			
			if ($this->request->get['is_html'] != null && $this->request->get['is_html'] != "") {
				$url2 .= '&is_html=' . $this->request->get['is_html'];
			}
			
			$this->redirect($this->url->link('services/resident/jsoncustomsForm', '' . $url2, 'SSL'));
			
		}
		
		$this->getForm();
	}
	
	public function updateclient(){
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
		
		if (($this->request->post['form_submit'] == '1') && $this->validateForm()) {
		
			$this->load->model('setting/tags');
			
			$tag_info = $this->model_setting_tags->getTag($this->request->get['tags_id']);
			
			
			if($tag_info['tags_status_in'] == $this->request->post['tags_status_in']){
				$tags_status_in_change = '1';
			}else{
				$tags_status_in_change = '2';
			}
			
			$archive_tags_id = $this->model_setting_tags->editTags($this->request->get['tags_id'], $this->request->post, $this->request->get['facilities_id']);
			
			$url2 = "";
			$url2 .= '&tags_id=' . $this->request->get['tags_id'];
			$url2 .= '&new_form=1';
			
			$url2 .= '&tags_status_in_change=' . $tags_status_in_change;
			
			if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
				$url2 .= '&notes_id=' . $this->request->get['notes_id'];
			}
			
			if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
				$url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
			}
			
			if ($this->request->get['is_html'] != null && $this->request->get['is_html'] != "") {
				$url2 .= '&is_html=' . $this->request->get['is_html'];
			}
			
			$url2 .= '&archive_tags_id=' . $archive_tags_id;
				
			$this->redirect($this->url->link('services/resident/jsoncustomsForm', '' . $url2, 'SSL'));
			
		}
		
		$this->getForm();
	}
	
	protected function validateForm() {
		
		$emp_first_name = preg_replace('/\s+/','',$this->request->post['emp_first_name']);
	
		if ($emp_first_name == "" && $emp_first_name == null) {
			$this->error['emp_first_name'] = "This is required field!";
			
		}
		
		
		$emp_last_name = preg_replace('/\s+/','',$this->request->post['emp_last_name']);
		
		if ($emp_last_name == "" && $emp_last_name == null) {
			$this->error['emp_last_name'] = "This is required field!";
		}
		
		/*if ($this->request->post['location_address'] == "") {
			$this->error['location_address'] = "This is required field!";
		}*/
		
		
		$ssn = preg_replace('/\s+/','',$this->request->post['ssn']);
		
		if ($ssn != '') {
			
			$this->load->model('setting/tags');
			$tag_info = $this->model_setting_tags->getTagsbySSN($this->request->post['ssn']);
			
			$url2 .= '&tags_id=' . $tag_info['tags_id'];
			$action2 = $this->url->link('services/resident/updateclient', '' . $url2, 'SSL');
			
			if (!isset($this->request->get['tags_id'])) {
				
				if($tag_info){
					$this->error['warning'] = 'This Record already exists in the System. Would you like to use this information for the Intake?  <a href="'.$action2.'">Yes</a> / No';
				}
			} else {
				
				if ($tag_info && ($this->request->get['tags_id'] != $tag_info['tags_id'])) {
					$this->error['warning'] = 'This Record already exists in the System. Would you like to use this information for the Intake?  <a href="'.$action2.'">Yes</a> / No';
				}
			}
		}
		
		if ($emp_first_name != '') {
			
			$this->load->model('setting/tags');
			$tag_info = $this->model_setting_tags->getTagsbyName($this->request->post['emp_first_name'], $this->request->post['emp_last_name']);
			
			$url2 .= '&tags_id=' . $tag_info['tags_id'];
			$action2 = $this->url->link('services/resident/updateclient', '' . $url2, 'SSL');
			
			if (!isset($this->request->get['tags_id'])) {
				
				if($tag_info){
					$this->error['warning'] = 'This Record already exists in the System. Would you like to use this information for the Intake?  <a href="'.$action2.'">Yes</a> / No';
				}
			} else {
				
				if ($tag_info && ($this->request->get['tags_id'] != $tag_info['tags_id'])) {
					$this->error['warning'] = 'This Record already exists in the System. Would you like to use this information for the Intake?  <a href="'.$action2.'">Yes</a> / No';
				}
			}
		}

		/*
		if ($this->request->post['ssn'] == "") {
			$this->error['ssn'] = "This is required field!";
		}*/
		
		/*
		if ($this->request->post['dob'] == "") {
			$this->error['dob'] = "This is required field!";
		}*/
		
		if ($this->request->post['gender'] == "") {
			$this->error['gender'] = "This is required field!";
		}

		if (($this->request->post['month_1'] == "") || ($this->request->post['day_1'] == "") || ($this->request->post['year_1'] == "")) {
			$this->error['dob'] = "This is required field!";
		}
		
		if($this->request->post['zipcode'] != null && $this->request->post['zipcode'] != ""){
			if ((utf8_strlen(trim($this->request->post['zipcode'])) < 2 || utf8_strlen(trim($this->request->post['zipcode'])) > 10)) {
				$this->error['postal_code'] = "Please enter valid ZIP";
			}
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	
	protected function getForm() {
		
		
		if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
			$this->data['facilities_id_url'] = '&facilities_id=' . $this->request->get['facilities_id'];
		}
		
		if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
			//$this->load->model('setting/tags');
			//$taginfo = $this->model_setting_tags->getTag($this->request->get['tags_id']);
			
			$this->load->model('setting/tags');
			$taginfo = $this->model_setting_tags->getTaga($this->request->get['tags_id'], $this->request->get['is_archive'], $this->request->get['notes_id']);
			
			$this->data['archive_is_archive'] = $taginfo['is_archive'];
		}
		
		if ($this->request->get['tags_id'] == null && $this->request->get['tags_id'] == "") {
			$url2 = "";
			
			if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
				$url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
			}
			
			if ($this->request->get['is_html'] != null && $this->request->get['is_html'] != "") {
				$url2 .= '&is_html=' . $this->request->get['is_html'];
			}
			
			$this->data['action'] = $this->url->link('services/resident/addclient', '' . $url2, 'SSL');
		} else {
			
			$url2 = "";
			$url2 .= '&tags_id=' . $this->request->get['tags_id'];
			
			if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
				$url2 .= '&notes_id=' . $this->request->get['notes_id'];
			}
			
			if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
				$url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
			}
			
			if ($this->request->get['is_html'] != null && $this->request->get['is_html'] != "") {
				$url2 .= '&is_html=' . $this->request->get['is_html'];
			}
			
			if ($this->request->get['is_archive'] != null && $this->request->get['is_archive'] != "") {
				$url2 .= '&is_archive=' . $this->request->get['is_archive'];
				$this->data['is_archive'] = $this->request->get['is_archive'];
			}
			
			if($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != ""){
				$this->load->model('notes/notes');
				$notes_info = $this->model_notes_notes->getNote($this->request->get['notes_id']);
			
				$this->data['note_date_added'] = date('m-d-Y h:i A', strtotime($notes_info['date_added']));
			}
			
			$this->data['currentt_url'] = str_replace('&amp;', '&',$this->url->link('services/resident/updateclient', '' . $url3, 'SSL'));
			
			$this->data['action'] = $this->url->link('services/resident/updateclient', '' . $url2, 'SSL');
		}
		
		$this->load->model('notes/notes');
		if($this->request->get['notes_id']){
			$notes_id = $this->request->get['notes_id'];
		}else{
			$notes_id = $this->request->get['updatenotes_id'];
		}
		
		
		
		//$this->data['notes_id'] = $this->request->get['notes_id'];
		
		if($this->request->get['saveclient'] != '1'){
			$this->data['updatenotes_id'] = $notes_id;
		}
		
		$this->data['tags_id'] = $this->request->get['tags_id'];
		
		if (isset($this->session->data['success2'])) {
			$this->data['success2'] = $this->session->data['success2'];

			unset($this->session->data['success2']);
		} else {
			$this->data['success2'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
		if (isset($this->session->data['success_add_form'])) {
			$this->data['success_add_form'] = $this->session->data['success_add_form'];

			unset($this->session->data['success_add_form']);
		} else {
			$this->data['success_add_form'] = '';
		}
		
		if (isset($this->error['ssn'])) {
			$this->data['error_ssn'] = $this->error['ssn'];
		} else {
			$this->data['error_ssn'] = '';
		}
		if (isset($this->error['postal_code'])) {
			$this->data['error_postal_code'] = $this->error['postal_code'];
		} else {
			$this->data['error_postal_code'] = '';
		}
		
		if (isset($this->error['dob'])) {
			$this->data['error_dob'] = $this->error['dob'];
		} else {
			$this->data['error_dob'] = '';
		}
		
		if (isset($this->error['emp_first_name'])) {
			$this->data['error_emp_first_name'] = $this->error['emp_first_name'];
		} else {
			$this->data['error_emp_first_name'] = '';
		}
		
		//var_dump($this->data['error_emp_first_name']);

		if (isset($this->error['emp_last_name'])) {
			$this->data['error_emp_last_name'] = $this->error['emp_last_name'];
		} else {
			$this->data['error_emp_last_name'] = '';
		}
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->error['location_address'])) {
			$this->data['error_location_address'] = $this->error['location_address'];
		} else {
			$this->data['error_location_address'] = '';
		}
		if (isset($this->error['gender'])) {
			$this->data['error_gender'] = $this->error['gender'];
		} else {
			$this->data['error_gender'] = '';
		}
		
		
		if (isset($this->request->post['imageName_url'])) {
			$this->data['imageName_url'] = $this->request->post['imageName_url'];
		} elseif (!empty($taginfo)) {
			
			$this->data['upload_file'] = $taginfo['upload_file'];
			$this->data['upload_file_thumb'] = $taginfo['upload_file_thumb'];
			
			if($taginfo['upload_file_thumb'] != null && $taginfo['upload_file_thumb'] != ""){
				$this->data['imageName_url'] = $taginfo['upload_file_thumb'];
			}else{
				$this->data['imageName_url'] = $taginfo['upload_file'];
			}
			$this->load->model('setting/image');
			$this->data['check_img'] = $this->model_setting_image->checkresize($taginfo['upload_file']);
			
			
		} else {
			$this->data['imageName_url'] = '';
		}
		
		if (isset($this->request->post['imageName_path'])) {
			$this->data['imageName_path'] = $this->request->post['imageName_path'];
		} else {
			$this->data['imageName_path'] = '';
		}
		
		if (isset($this->request->post['imageName'])) {
			$this->data['imageName'] = $this->request->post['imageName'];
		} else {
			$this->data['imageName'] = '';
		}
		
		if (isset($this->request->post['upload_file'])) {
			$this->data['upload_file'] = $this->request->post['upload_file'];
		} elseif (!empty($taginfo)) {
			$this->data['upload_file'] = $taginfo['upload_file'];
			$this->data['upload_file_thumb'] = $taginfo['upload_file_thumb'];
			
			$this->load->model('setting/image');
			$this->data['check_img'] = $this->model_setting_image->checkresize($taginfo['upload_file']);
			
		} else {
			$this->data['upload_file'] = '';
		}
		
		
		
		if($this->data['upload_file'] != null && $this->data['upload_file'] != ""){
			
			if($taginfo['upload_file_thumb'] != null && $taginfo['upload_file_thumb'] != ""){
				$image_url = file_get_contents($taginfo['upload_file_thumb']);
			}else{
				$image_url = file_get_contents($this->data['upload_file']);
			}
				
			$this->data['image_url1'] = 'data:image/jpg;base64,'.base64_encode($image_url);
			
			
		}
		
		
		if (isset($this->request->post['tags_status_in'])) {
			$this->data['tags_status_in'] = $this->request->post['tags_status_in'];
		} elseif (!empty($taginfo)) {
			$this->data['tags_status_in'] = $taginfo['tags_status_in'];
			
			$this->load->model('setting/tags');
			$taginfo_a = $this->model_setting_tags->gettotalarchivetags($this->request->get['tags_id']);
			
			$this->data['taginfo_a'] = $taginfo_a;
			
		} else {
			$this->data['tags_status_in'] = '';
		}
		if (isset($this->request->post['referred_facility'])) {
			$this->data['referred_facility'] = $this->request->post['referred_facility'];
		} elseif (!empty($taginfo)) {
			$this->data['referred_facility'] = $taginfo['referred_facility'];
		} else {
			$this->data['referred_facility'] = '';
		}
		
		
		if (isset($this->request->post['facilities_id'])) {
			$this->data['facilities_id'] = $this->request->post['facilities_id'];
		} elseif (!empty($taginfo)) {
			
			$this->data['facilities_id'] = $taginfo['facilities_id'];
		} else {
			$this->data['facilities_id'] = $this->request->get['facilities_id'];
		}
		
		
		
		
		if (isset($this->request->post['emp_extid'])) {
			$this->data['emp_extid'] = $this->request->post['emp_extid'];
		} elseif (!empty($taginfo)) {
			$this->data['emp_extid'] = $taginfo['emp_extid'];
		} else {
			$this->data['emp_extid'] = '';
		}
		
		
		if (isset($this->request->post['location_address'])) {
			$this->data['location_address'] = $this->request->post['location_address'];
		} elseif (!empty($taginfo)) {
			$this->data['location_address'] = $taginfo['location_address'];
		} else {
			$this->data['location_address'] = '';
		}
		
		if (isset($this->request->post['latitude'])) {
			$this->data['latitude'] = $this->request->post['latitude'];
		} elseif (!empty($taginfo)) {
			$this->data['latitude'] = $taginfo['latitude'];
		} else {
			$this->data['latitude'] = '';
		}
		if (isset($this->request->post['longitude'])) {
			$this->data['longitude'] = $this->request->post['longitude'];
		} elseif (!empty($taginfo)) {
			$this->data['longitude'] = $taginfo['longitude'];
		} else {
			$this->data['longitude'] = '';
		}
		
		if (isset($this->request->post['address_street2'])) {
			$this->data['address_street2'] = $this->request->post['address_street2'];
		} elseif (!empty($taginfo)) {
			$this->data['address_street2'] = $taginfo['address_street2'];
		} else {
			$this->data['address_street2'] = '';
		}
		
		if (isset($this->request->post['state'])) {
			$this->data['state'] = $this->request->post['state'];
		} elseif (!empty($taginfo)) {
			$this->data['state'] = $taginfo['state'];
		} else {
			$this->data['state'] = '';
		}
		
		
		
		if (isset($this->request->post['city'])) {
			$this->data['city'] = $this->request->post['city'];
		} elseif (!empty($taginfo)) {
			$this->data['city'] = $taginfo['city'];
		} else {
			$this->data['city'] = '';
		}
		
		if (isset($this->request->post['zipcode'])) {
			$this->data['zipcode'] = $this->request->post['zipcode'];
		} elseif (!empty($taginfo)) {
			$this->data['zipcode'] = $taginfo['zipcode'];
		} else {
			$this->data['zipcode'] = '';
		}
		
			if (isset($this->request->post['ssn'])) {
			$this->data['ssn'] = $this->request->post['ssn'];
		} elseif (!empty($taginfo)) {
			$this->data['ssn'] = $taginfo['ssn'];
		} else {
			$this->data['ssn'] = '';
		}
		
		$this->load->model('facilities/facilities');
		$this->load->model('setting/timezone');
		$facilityinfo = $this->model_facilities_facilities->getfacilities($this->request->get['facilities_id']);
		
		$timezone_info = $this->model_setting_timezone->gettimezone($facilityinfo['timezone_id']);
						
		date_default_timezone_set($timezone_info['timezone_value']);
						
		if (isset($this->request->post['date_of_screening'])) {
			$this->data['date_of_screening'] = $this->request->post['date_of_screening'];
		} elseif (!empty($taginfo)) {
			if($taginfo['date_of_screening'] != "0000-00-00"){
				$this->data['date_of_screening'] = date('m-d-Y', strtotime($taginfo['date_of_screening']));
			}else{
				$this->data['date_of_screening'] = date('m-d-Y');
			}
		} else {
			$this->data['date_of_screening'] =  date('m-d-Y');
		}
		
		
		if (isset($this->request->post['person_screening'])) {
			$this->data['person_screening'] = $this->request->post['person_screening'];
		} elseif (!empty($taginfo)) {
			$this->data['person_screening'] = $taginfo['person_screening'];
		} else {
			$this->data['person_screening'] = '';
		}
		
	
		
		if (isset($this->request->post['gender'])) {
			$this->data['gender'] = $this->request->post['gender'];
		} elseif (!empty($taginfo)) {
			$this->data['gender'] = $taginfo['customlistvalues_id'];
		} else {
			$this->data['gender'] = '';
		}
		
		
		if (isset($this->request->post['dob'])) {
			$this->data['dob'] = $this->request->post['dob'];
		} elseif (!empty($taginfo)) {
			if($taginfo['dob'] != "0000-00-00"){
				$this->data['dob'] = date('m-d-Y', strtotime($taginfo['dob']));
			}else{
				$this->data['dob'] = '';
			}
			
		} else {
			$this->data['dob'] = '';
		}
		
		if (isset($this->request->post['month_1'])) {
			$this->data['month_1'] = $this->request->post['month_1'];
		} elseif (!empty($taginfo)) {
			if($taginfo['dob'] != "0000-00-00"){
				$this->data['month_1'] = date('m', strtotime($taginfo['dob']));
			}else{
				$this->data['month_1'] =date('m');
			}
			
		} else {
			$this->data['month_1'] = date('m');
		}
		
		if (isset($this->request->post['day_1'])) {
			$this->data['day_1'] = $this->request->post['day_1'];
		} elseif (!empty($taginfo)) {
			if($taginfo['dob'] != "0000-00-00"){
				$this->data['day_1'] = date('d', strtotime($taginfo['dob']));
			}else{
				$this->data['day_1'] =date('d');
			}
			
		} else {
			$this->data['day_1'] = date('d');
		}
		
		if (isset($this->request->post['year_1'])) {
			$this->data['year_1'] = $this->request->post['year_1'];
		} elseif (!empty($taginfo)) {
			if($taginfo['dob'] != "0000-00-00"){
				$this->data['year_1'] = date('Y', strtotime($taginfo['dob']));
			}else{
				$this->data['year_1'] =date('Y');
			}
			
		} else {
			$this->data['year_1'] = date('Y');
		}
		
		$this->data['current_date'] =  date('m-d-Y');
		$this->data['current_y'] =  date("Y");
		
		
		if (isset($this->request->post['emp_first_name'])) {
			$this->data['emp_first_name'] = $this->request->post['emp_first_name'];
		} elseif (!empty($taginfo)) {
			$this->data['emp_first_name'] = $taginfo['emp_first_name'];
		} else {
			$this->data['emp_first_name'] = '';
		}
		
		if (isset($this->request->post['emp_last_name'])) {
			$this->data['emp_last_name'] = $this->request->post['emp_last_name'];
		} elseif (!empty($taginfo)) {
			$this->data['emp_last_name'] = $taginfo['emp_last_name'];
		} else {
			$this->data['emp_last_name'] = '';
		}
		
		if (isset($this->request->post['emergency_contact'])) {
			$this->data['emergency_contact'] = $this->request->post['emergency_contact'];
		} elseif (!empty($taginfo)) {
			$this->data['emergency_contact'] = $taginfo['emergency_contact'];
		} else {
			$this->data['emergency_contact'] = '';
		}
		
		
		if (isset($this->request->post['room_id'])) {
			$this->data['room_id'] = $this->request->post['room_id'];
		} elseif (!empty($taginfo)) {
			$this->data['room_id'] = $taginfo['room'];
		} else {
			$this->data['room_id'] = '';
		}
		
		if (isset($this->request->post['room'])) {
			$this->data['room'] = $this->request->post['room'];
		}elseif (!empty($taginfo)) {
			$this->load->model('setting/locations');
			$tags_info12 = $this->model_setting_locations->getlocation($taginfo['room']);
			
			$this->data['room'] = $tags_info12['location_name'];
		} else {
			$this->data['room'] = '';
		}
		
			
		if (isset($this->request->post['tagstatus'])) {
			$this->data['tagstatus'] = $this->request->post['tagstatus'];
		} elseif (!empty($taginfo)) {
			$this->data['tagstatus'] = $taginfo['tagstatus'];
		} else {
			$this->data['tagstatus'] = '';
		}
		
		
		if (isset($this->request->post['med_mental_health'])) {
			$this->data['med_mental_health'] = $this->request->post['med_mental_health'];
		} elseif (!empty($taginfo)) {
			$this->data['med_mental_health'] = $taginfo['med_mental_health'];
		} else {
			$this->data['med_mental_health'] = '';
		}
		
		if (isset($this->request->post['constant_sight'])) {
			$this->data['constant_sight'] = $this->request->post['constant_sight'];
		} elseif (!empty($taginfo)) {
			$this->data['constant_sight'] = $taginfo['constant_sight'];
		} else {
			$this->data['constant_sight'] = '';
		}
		
		
		if (isset($this->request->post['alert_info'])) {
			$this->data['alert_info'] = $this->request->post['alert_info'];
		} elseif (!empty($taginfo)) {
			$this->data['alert_info'] = $taginfo['alert_info'];
		} else {
			$this->data['alert_info'] = '';
		}
		
		if (isset($this->request->post['prescription'])) {
			$this->data['prescription'] = $this->request->post['prescription'];
		} elseif (!empty($taginfo)) {
			$this->data['prescription'] = $taginfo['prescription'];
		} else {
			$this->data['prescription'] = '';
		}
		
		
		if (isset($this->request->post['restriction_notes'])) {
			$this->data['restriction_notes'] = $this->request->post['restriction_notes'];
		} elseif (!empty($taginfo)) {
			$this->data['restriction_notes'] = $taginfo['restriction_notes'];
		} else {
			$this->data['restriction_notes'] = '';
		}
		
		
		if (isset($this->request->post['allclients'])) {
			$this->data['allclients'] = $this->request->post['allclients'];
		}  else {
			$this->data['allclients'] = '0';
		}
		
		
		if (isset($this->request->post['is_discharge'])) {
			$this->data['is_discharge'] = $this->request->post['is_discharge'];
		}  else {
			$this->data['is_discharge'] = '';
		}
		
		
		if (isset($this->request->post['reminder_time'])) {
			$this->data['reminder_time'] = $this->request->post['reminder_time'];
		}elseif (!empty($taginfo)) {
			$this->data['reminder_time'] = date('h:i A', strtotime($taginfo['reminder_time']));
		} else {
			$this->data['reminder_time'] = '';
		}
		
		if (isset($this->request->post['reminder_date'])) {
			$this->data['reminder_date'] = $this->request->post['reminder_date'];
		}elseif (!empty($taginfo)) {
			$this->data['reminder_date'] = date('m-d-Y', strtotime($taginfo['reminder_date']));
		} else {
			$this->data['reminder_date'] = '';
		}
		
		
		if (isset($this->request->post['forms_id'])) {
			$this->data['forms_id'] = $this->request->post['forms_id'];
		} elseif (!empty($taginfo)) {
			
			
			$this->load->model('form/form');
			
			if($taginfo['is_archive'] == '3'){
				$tags_info121 = $this->model_form_form->gettagsforma3($this->request->get['tags_id']);
			}else{
				$tags_info121 = $this->model_form_form->gettagsforma($this->request->get['tags_id']);
			}
			
			//$tags_info121 = $this->model_form_form->gettagsforma($this->request->get['tags_id']);
			
			$this->data['forms_id'] = $tags_info121['forms_id'];
		} else {
			$this->data['forms_id'] = '';
		}
		
		if (isset($this->request->post['link_screening'])) {
			$this->data['link_screening'] = $this->request->post['link_screening'];
		}elseif (!empty($tags_info121)) {
				$this->load->model('form/form');
				$tags_info12 = $this->model_form_form->getFormDatas($tags_info121['forms_id']);
			
				$design_forms = unserialize($tags_info12['design_forms']);
			
				$clientname = "";
				if($design_forms[0][0]['text_59815482'] != null && $design_forms[0][0]['text_59815482'] != ""){
					$clientname = $design_forms[0][0]['text_59815482'].' '.$design_forms[0][0]['text_2637670'] . ' | DOB ' .$design_forms[0][0]['date_70767270'] . ' | Screening ' .$design_forms[0][0]['date_21657417'];
				}else{
					$clientname = $tags_info12['incident_number'].' '. date('m-d-Y', strtotime($tags_info12['date_added']));
				}
			
			$this->data['link_screening'] = $clientname;
		} else {
			$this->data['link_screening'] = '';
		}
		
		
		$this->load->model('facilities/facilities');
			$facilityinfo = $this->model_facilities_facilities->getfacilities($this->request->get['facilities_id']);
			$this->load->model('notes/notes');
			
			if($facilityinfo['config_tags_customlist_id'] !=NULL && $facilityinfo['config_tags_customlist_id'] !=""){
			
				$d = array();
				$d['customlist_id'] = $facilityinfo['config_tags_customlist_id'];
				$customlists = $this->model_notes_notes->getcustomlists($d);
				
					if($customlists){
						foreach($customlists as $customlist){
							$d2 = array();
							$d2['customlist_id'] = $customlist['customlist_id'];
							
							$customlistvalues = $this->model_notes_notes->getcustomlistvalues($d2);
							
							$this->data['customlists'][] = array(
							'customlist_id' => $customlist['customlist_id'],
							'customlist_name'  => $customlist['customlist_name'],
							'customlistvalues'  => $customlistvalues,
							);
						}
					}
			
			
			}
			
			
			$this->load->model('facilities/facilities');
			$this->data['sfacilities'] = $this->model_facilities_facilities->getfacilitiess();
		
		
		$this->template = $this->config->get('config_template') . '/template/notes/tagform.tpl';
		$this->children = array(
			'common/headerpopup',
		);
		$this->response->setOutput($this->render());
		
	}
	
	public function jsoncustomsForm(){
		
			$url2 = "";
			
			if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
				$url2 .= '&tags_id=' . $this->request->get['tags_id'];
				$new_form = $this->request->get['new_form'];
				$tags_id = $this->request->get['tags_id'];
				$url2 .= '&new_form='.$this->request->get['new_form'];
			}else{
				$new_form = $this->request->get['new_form'];
				$tags_id = '';
				$url2 .= '&new_form='.$this->request->get['new_form'];
			}
				
			
			if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
				$url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
				$facilities_id = $this->request->get['facilities_id'];
			}else{
				$facilities_id = '';
			}
		
			if ($this->request->get['archive_tags_id'] != null && $this->request->get['archive_tags_id'] != "") {
				$url2 .= '&archive_tags_id=' . $this->request->get['archive_tags_id'];
				$archive_tags_id = $this->request->get['archive_tags_id'];
			}else{
				$archive_tags_id = '';
			}
			
			if ($this->request->get['tags_status_in_change'] != null && $this->request->get['tags_status_in_change'] != "") {
				$url2 .= '&tags_status_in_change=' . $this->request->get['tags_status_in_change'];
				$tags_status_in_change = $this->request->get['tags_status_in_change'];
			}else{
				$tags_status_in_change = '';
			}
		
			
			if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
				$url2 .= '&notes_id=' . $this->request->get['notes_id'];
				$notes_id = $this->request->get['notes_id'];
				
				$signature_url = str_replace('&amp;', '&',$this->url->link('services/resident/insert2', '' . $url2, 'SSL'));
			}else{
				$notes_id = '';
				$signature_url = str_replace('&amp;', '&',$this->url->link('services/resident/insert2', '' . $url2, 'SSL'));
			}
			
			if($this->request->get['tags_id']){
				$tags_id = $this->request->get['tags_id'];
			}elseif($this->request->get['emp_tag_id']){
				$tags_id = $this->request->get['emp_tag_id'];
			}
			if($tags_id != null && $tags_id != ""){
				$this->load->model('setting/tags');
				$tag_info = $this->model_setting_tags->getTag($tags_id);
				$name = $tag_info['emp_tag_id'].' : '.$tag_info['emp_first_name'] .' '.$tag_info['emp_last_name'];
			}else{
				$name = '';
			}
			
			$this->data['facilitiess'][] = array(
				'task_form'    => '',
				'medication_tags'    => '',
				'archive_tags_medication_id'    => '',
				'tags_id'    => $tags_id,
				'emp_tag_id'    => '',
				'archive_tags_id'    => $archive_tags_id,
				'name'    => $name,
				'new_form'    => $new_form,
				'notes_id'    => $notes_id,
				'facilities_id'    => $facilities_id,
				'signature_url'    => $signature_url,
			);
			
			
			if($this->request->get['is_html'] == '1'){
				$this->data['signature_url'] = $signature_url;
				
				$this->template = $this->config->get('config_template') . '/template/form/jsoncustom.tpl';
			
				$this->response->setOutput($this->render());
		}else{
			
			
			$value = array('results'=>$this->data['facilitiess'],'status'=>true);
			
			$this->response->setOutput(json_encode($value));
		}
	}
	
	public function insert2(){
		
		try{
		$this->data['facilitiess'] = array();
		
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
		
		$json = array();
		
		$this->load->model('notes/notes');
		$this->language->load('notes/notes');
		
		if($this->request->post['notes_pin'] != null && $this->request->post['notes_pin'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if (($this->request->post['notes_pin'] != $user_info['user_pin'])) {
				$json['warning'] = 'User Pin not valid!.';
			}
		}
		
		if($this->request->post['user_id'] != null && $this->request->post['user_id'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if (($user_info['status'] == '0')) {
				$json['warning'] = 'User not exit!';
			}
		}
		
		if($json['warning'] == null && $json['warning'] == ""){
			
			if($this->request->get['facilities_id']){
				$this->load->model('facilities/facilities');
					
				$facilities_info = $this->model_facilities_facilities->getfacilities($this->request->get['facilities_id']);
					
				$this->load->model('setting/timezone');
					
				$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
				$facilitytimezone = $timezone_info['timezone_value'];
			}
			
			if($this->request->get['new_form'] == '1'){
				$this->load->model('setting/tags');
				
				$data2 = array();
				$data2['tags_id'] = $this->request->get['tags_id'];
				$data2['notes_id'] = $this->request->get['notes_id'];
				$data2['archive_tags_id'] = $this->request->get['archive_tags_id'];
				$data2['facilities_id'] = $this->request->get['facilities_id'];
				$data2['facilitytimezone'] = $facilitytimezone;
				
				$data2['phone_device_id'] = $this->request->post['phone_device_id'];
				
				$data2['tags_status_in_change'] = $this->request->get['tags_status_in_change'];
						
				if($this->request->post['is_android'] != null && $this->request->post['is_android'] != ""){
					$data2['is_android'] = $this->request->post['is_android'];
				}else{
					$data2['is_android'] = '1';
				}
				
				$notes_id = $this->model_setting_tags->updateclientsign($this->request->post, $data2);
				
			}else{
				
				$this->load->model('setting/tags');
				
				$data2 = array();
				$data2['tags_id'] = $this->request->get['tags_id'];
				$data2['facilities_id'] = $this->request->get['facilities_id'];
				$data2['facilitytimezone'] = $facilitytimezone;
				
				$data2['phone_device_id'] = $this->request->post['phone_device_id'];
				
						
				if($this->request->post['is_android'] != null && $this->request->post['is_android'] != ""){
					$data2['is_android'] = $this->request->post['is_android'];
				}else{
					$data2['is_android'] = '1';
				}
				
				$notes_id = $this->model_setting_tags->addclientsign($this->request->post, $data2);
				
			}
			
		
			$this->data['facilitiess'][] = array(
				'warning'  => '1',
				'notes_id'  => $notes_id,
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
				'data' => 'Error in addclient jsonAddNotes',
			);
			$this->model_activity_activity->addActivity('app_addclient', $activity_data2);
		
		
		} 
	}

	public function tagsmedication(){
		
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
		
		$this->language->load('notes/notes');
		$this->load->model('setting/tags');
		$this->load->model('form/form');
		
		
		if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
			$this->data['facilities_id_url'] = '&facilities_id=' . $this->request->get['facilities_id'];
		
			$this->load->model('setting/timezone');
			$this->load->model('facilities/facilities');
		
			$facility = $this->model_facilities_facilities->getfacilities($this->request->get['facilities_id']);
								
			$timezone_info = $this->model_setting_timezone->gettimezone($facility['timezone_id']);
								
			date_default_timezone_set($timezone_info['timezone_value']);
					
			$this->data['current_time'] = date('h:i A');
		}
		
		if($this->request->get['tags_id']){
			$tags_id = $this->request->get['tags_id'];
		}elseif($this->request->post['emp_tag_id']){
			$tags_id = $this->request->post['emp_tag_id'];
		}
		
		$tag_info = $this->model_setting_tags->getTag($tags_id);
		
		if($tags_id){
			$this->data['name'] = $tag_info['emp_tag_id'].' : '.$tag_info['emp_first_name'] .' '.$tag_info['emp_last_name'];
		}
		
		$this->load->model('createtask/createtask');
		$this->data['taskintervals'] = $this->model_createtask_createtask->getTaskintervals();
		
		
		$this->load->model('resident/resident');
		
		if (($this->request->post['form_submit'] == '1') && $this->validateForms()) {
			
			$archive_tags_medication_id = $this->model_resident_resident->addTagsMedication($this->request->post, $tags_id);

			$url2  = "";
			
			//var_dump($this->request->post['medication']);
			
			if($this->request->post['medication'] != null && $this->request->post['medication'] != ""){
				//$this->session->data['medication'] = $this->request->post['medication'];
				
				
				$medication_tags = implode(',',$this->request->post['medication']);
				
				if ($medication_tags != null && $medication_tags != "") {
					$url2 .= '&medication_tags=' . $medication_tags;
				}
				$url2 .= '&addmedicine=1';
				
				//$this->session->data['success2'] = 'Medication added successfully!';
			}else{
				//$this->session->data['success_add_form'] = 'Medication added successfully!';
				
				$url2 .= '&addmedicine=2';
			}
			
			
			if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
				$url2 .= '&tags_id=' . $this->request->get['tags_id'];
			}
			if ($this->request->post['emp_tag_id'] != null && $this->request->post['emp_tag_id'] != "") {
				$url2 .= '&tags_id=' . $this->request->post['emp_tag_id'];
			}
			
			if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
				$url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
			}
			if ($this->request->get['is_html'] != null && $this->request->get['is_html'] != "") {
				$url2 .= '&is_html=' . $this->request->get['is_html'];
			}
			$url2 .= '&archive_tags_medication_id=' . $archive_tags_medication_id;
			

			$this->redirect($this->url->link('services/resident/jsoncustomsForm2', ''. $url2, 'SSL'));
		}
		
		
		
		if (isset($this->request->post['emp_tag_id'])) {
			$this->data['emp_tag_id'] = $this->request->post['emp_tag_id'];
		}elseif (!empty($tag_info)) {
			$this->data['emp_tag_id'] = $tag_info['tags_id'];
		} else {
			$this->data['emp_tag_id'] = '';
		}
		
		if (isset($this->request->post['emp_tag_id1'])) {
			$this->data['emp_tag_id1'] = $this->request->post['emp_tag_id1'];
		}elseif (!empty($tag_info)) {
			$this->data['emp_tag_id1'] = $tag_info['emp_tag_id'].' : '.$tag_info['emp_first_name'] .' '.$tag_info['emp_last_name'];
		} else {
			$this->data['emp_tag_id1'] = '';
		}
		
		if (isset($this->request->post['new_module'])) {
			$this->data['modules'] = $this->request->post['new_module'];
		}elseif ($this->request->get['tags_id']) { 
			
			$muduled = $this->model_resident_resident->gettagModule($this->request->get['tags_id'], $this->request->get['is_archive'], $this->request->get['notes_id']);
			
			$this->data['modules'] = $muduled['new_module'];
		}elseif ($this->request->post['emp_tag_id']) { 
			
			$muduled = $this->model_resident_resident->gettagModule($this->request->post['emp_tag_id'], $this->request->get['is_archive'], $this->request->get['notes_id']);
			
			$this->data['modules'] = $muduled['new_module'];
		}else{
			$this->data['modules'] = array();
		}
		
		//var_dump($this->data['modules']);
		
		if (isset($this->request->post['medication_fields'])) {
			$this->data['medication_fields'] = $this->request->post['medication_fields'];
		}elseif ($this->request->get['tags_id']) { 
			
			$medicine_info = $this->model_resident_resident->gettagmedicine($this->request->get['tags_id'], $this->request->get['is_archive'], $this->request->get['notes_id']);
			
			$this->data['medication_fields'] = unserialize($medicine_info['medication_fields']);
		}elseif ($this->request->post['emp_tag_id']) { 
			
			$medicine_info = $this->model_resident_resident->gettagmedicine($this->request->post['emp_tag_id'], $this->request->get['is_archive'], $this->request->get['notes_id']);
			
			$this->data['medication_fields'] = unserialize($medicine_info['medication_fields']);
		}else{
			$this->data['medication_fields'] = array();
		}
		
		
		
		if (isset($this->request->post['is_schedule'])) {
			$this->data['is_schedule'] = $this->request->post['is_schedule'];
		}elseif ($medicine_info) { 
			$this->data['is_schedule'] = $medicine_info['is_schedule'];
		}else{
			$this->data['is_schedule'] = '0';
		}
		
		
		if (isset($this->request->post['drug_name'])) {
			$this->data['drug_name'] = $this->request->post['drug_name'];
		}else{
			$this->data['drug_name'] = '';
		}
		
		if (isset($this->request->post['drug_mg'])) {
			$this->data['drug_mg'] = $this->request->post['drug_mg'];
		}else{
			$this->data['drug_mg'] = '';
		}
		
		if (isset($this->request->post['drug_am'])) {
			$this->data['drug_am'] = $this->request->post['drug_am'];
		}else{
			$this->data['drug_am'] = date('h:i A');
		}
		
		if (isset($this->request->post['drug_pm'])) {
			$this->data['drug_pm'] = $this->request->post['drug_pm'];
		}else{
			$this->data['drug_pm'] = date('h:i A');
		}
		
		if (isset($this->request->post['drug_alertnate'])) {
			$this->data['drug_alertnate'] = $this->request->post['drug_alertnate'];
		}else{
			$this->data['drug_alertnate'] = '';
		}
		
		if (isset($this->request->post['drug_prn'])) {
			$this->data['drug_prn'] = $this->request->post['drug_prn'];
		}else{
			$this->data['drug_prn'] = '';
		}
		
		if (isset($this->request->post['instructions'])) {
			$this->data['instructions'] = $this->request->post['instructions'];
		}else{
			$this->data['instructions'] = '';
		}
		
		if (isset($this->request->post['medication'])) {
			$this->data['medication'] = $this->request->post['medication'];
		}else{
			$this->data['medication'] = array();
		}
		
		if (isset($this->session->data['success_add_form'])) {
			$this->data['success_add_form'] = $this->session->data['success_add_form'];

			unset($this->session->data['success_add_form']);
		} else {
			$this->data['success_add_form'] = '';
		}
		
		if (isset($this->session->data['success2'])) {
			$this->data['success2'] = $this->session->data['success2'];

			unset($this->session->data['success2']);
		} else {
			$this->data['success2'] = '';
		}
		
		if (isset($this->session->data['success_add_form1'])) {
			$this->data['success_add_form1'] = $this->session->data['success_add_form1'];

			unset($this->session->data['success_add_form1']);
		} else {
			$this->data['success_add_form1'] = '';
		}
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		
		if (isset($this->error['drug_name'])) {
			$this->data['error_drug_name'] = $this->error['drug_name'];
		} else {
			$this->data['error_drug_name'] = array();;
		}
		
		if (isset($this->error['date_from'])) {
			$this->data['error_date_from'] = $this->error['date_from'];
		} else {
			$this->data['error_date_from'] = array();;
		}
		if (isset($this->error['date_to'])) {
			$this->data['error_date_to'] = $this->error['date_to'];
		} else {
			$this->data['error_date_to'] = array();;
		}
		if (isset($this->error['daily_times'])) {
			$this->data['error_daily_times'] = $this->error['daily_times'];
		} else {
			$this->data['error_daily_times'] = array();;
		}
		
		$url2  = "";
		$url3  = "";
		if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
			$url2 .= '&tags_id=' . $this->request->get['tags_id'];
			$url3 .= '&tags_id=' . $this->request->get['tags_id'];
		}
		
		if ($this->request->get['emp_tag_id'] != null && $this->request->get['emp_tag_id'] != "") {
			$url2 .= '&tags_id=' . $this->request->get['emp_tag_id'];
			$url3 .= '&tags_id=' . $this->request->get['tags_id'];
		}
		if ($this->request->get['medication_tags'] != null && $this->request->get['medication_tags'] != "") {
			$url2 .= '&medication_tags=' . $this->request->get['medication_tags'];
		}
		
		if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
			$url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
			$url3 .= '&facilities_id=' . $this->request->get['facilities_id'];
		}
		if ($this->request->get['is_html'] != null && $this->request->get['is_html'] != "") {
			$url2 .= '&is_html=' . $this->request->get['is_html'];
			$url3 .= '&is_html=' . $this->request->get['is_html'];
		}
		
		if ($this->request->get['notes_id'] != null && $this->request->get['notes_id'] != "") {
			$url2 .= '&notes_id=' . $this->request->get['notes_id'];
			$url3 .= '&notes_id=' . $this->request->get['notes_id'];
		}
		
		if ($this->request->get['archive_tags_medication_id'] != null && $this->request->get['archive_tags_medication_id'] != "") {
			$url2 .= '&archive_tags_medication_id=' . $this->request->get['archive_tags_medication_id'];
		}
		if ($this->request->get['is_archive'] != null && $this->request->get['is_archive'] != "") {
			$url2 .= '&is_archive=' . $this->request->get['is_archive'];
			$this->data['is_archive'] = $this->request->get['is_archive'];
			
		}
		
		$this->data['action'] = $this->url->link('services/resident/tagsmedication', $url2, true);
		$this->data['currentt_url'] = str_replace('&amp;', '&',$this->url->link('services/resident/tagsmedication', '' . $url3, 'SSL'));
		
		
		$this->template = $this->config->get('config_template') . '/template/resident/medication.tpl';
		
		$this->children = array(
			'common/headerpopup',
		);
		
		$this->response->setOutput($this->render());
	}

	
	
	protected function validateForms() {
		
		/*if($this->request->post['new_module'] == null && $this->request->post['new_module'] == ""){
			$this->error['warning'] = 'Warning: Medication is required';
		}*/
		
		if($this->request->post['new_module'] != null && $this->request->post['new_module'] != ""){
			foreach($this->request->post['new_module'] as $new_module){
				if($new_module['drug_name'] == "" && $new_module['drug_name'] == null){
					$this->error['warning'] = 'Warning: Medication is required';
				}
				
				
				if($new_module['is_schedule_medication'] == '1'){
					if($new_module['date_from'] == "" && $new_module['date_from'] == null){
						$this->error['date_from'][$key] = 'Date From is required';
					}
					if($new_module['date_to'] == "" && $new_module['date_to'] == null){
						$this->error['date_to'][$key] = 'Date To is required';
					}
					if($new_module['daily_times'] == "" && $new_module['daily_times'] == null){
						$this->error['daily_times'][$key] = 'Time is required';
					}
				}
			}
		}
		
		
		
		if($this->request->post['emp_tag_id1'] == "" && $this->request->post['emp_tag_id1'] == null){
			$this->error['warning'] = 'Warning: Client is required';
		}
		
		if($this->request->post['drug_name'] != "" && $this->request->post['drug_name'] != null){
			$medication_info = $this->model_resident_resident->get_medicationyname($this->request->post['drug_name'], $this->request->get['tags_id']);
					
			if ($medication_info) {
				$this->error['warning'] = 'Warning: Medication is already in enter!';
			}
					
		}
		
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	public function tagsmedicationsign2(){
		
		
		try{
		$this->data['facilitiess'] = array();
		
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
		
		$json = array();
		
		$this->load->model('notes/notes');
		$this->language->load('notes/notes');
		
		if($this->request->post['notes_pin'] != null && $this->request->post['notes_pin'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if (($this->request->post['notes_pin'] != $user_info['user_pin'])) {
				$json['warning'] = 'User Pin not valid!.';
			}
		}
		
		if($this->request->post['user_id'] != null && $this->request->post['user_id'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if (($user_info['status'] == '0')) {
				$json['warning'] = 'User not exit!';
			}
		}
		
		if($json['warning'] == null && $json['warning'] == ""){
			
			$this->load->model('notes/notes');
			$this->load->model('form/form');

			$this->load->model('notes/notes');
			$this->load->model('resident/resident');

			
			if($this->request->get['facilities_id']){
				$this->load->model('facilities/facilities');
					
				$facilities_info = $this->model_facilities_facilities->getfacilities($this->request->get['facilities_id']);
					
				$this->load->model('setting/timezone');
					
				$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
				$facilitytimezone = $timezone_info['timezone_value'];
			}
			
				$timezone_name = $facilitytimezone;
				$timeZone = date_default_timezone_set($timezone_name);
				$noteDate = date('Y-m-d H:i:s', strtotime('now'));
				$date_added = (string) $noteDate;
				
				
				$notetime = date('H:i:s', strtotime('now'));
				$data['imgOutput'] = $this->request->post['signature'];
				
				$data['notes_pin'] = $this->request->post['notes_pin'];
				$data['user_id'] = $this->request->post['user_id'];
				$data['notes_type'] = $this->request->post['notes_type'];
				
				$this->load->model('setting/tags');
				$tag_info = $this->model_setting_tags->getTag($this->request->get['tags_id']);
				
				$data['emp_tag_id'] = $tag_info['emp_tag_id'];
				$data['tags_id'] = $tag_info['tags_id'];
				
				//$data['keyword_file'] = MEDICATION_ICON;
				
				//$this->load->model('setting/keywords');
				
				//$keywordData2 = $this->model_setting_keywords->getkeywordDetaildesc($data['keyword_file']);
				
				
				
				/*$medicationf = "";
				foreach($this->session->data['medication'] as $key=>$medication){
					
					$medication_info = $this->model_resident_resident->get_medication($medication);
					$medicationf .= $medication_info['drug_name'].', ';
					
				}*/
				
				/*if($this->request->post['comments'] != null && $this->request->post['comments']){
					$comments = ' | '.$this->request->post['comments'];
				}*/
				
				if($tag_info['emp_first_name']){
					$emp_tag_id = $tag_info['emp_tag_id'].':'.$tag_info['emp_first_name'];
				}else{
					$emp_tag_id = $tag_info['emp_tag_id'];
				}
							
				if ($tag_info) {
					$medication_tags .= $emp_tag_id.' ';
				}
				
				$description = '';
				//$description .= $keywordData2['keyword_name'];
				//$description .= ' | ';
				//$description .= ' Completed for | '.date('h:i A', strtotime($notetime)) .' ';
				$description .= ' Health Form updated | ';
				$description .= ' '. $medication_tags;
					
					
				if($this->request->post['comments'] != null && $this->request->post['comments']){
					$description .= ' | '.$this->db->escape($this->request->post['comments']);
				}
				
				//$description .= ' | ';
				
				//$data['notes_description'] = $keywordData2['keyword_name'].' | '. $tag_info['emp_tag_id'].':'.$tag_info['emp_first_name'] .' | '.$medicationf . $comments;
				
				$data['notes_description'] = $description;
				
				$data['date_added'] = $date_added;
				$data['note_date'] = $date_added;
				$data['notetime'] = $notetime;
				
				$data['phone_device_id'] = $this->request->post['phone_device_id'];
						
				if($this->request->post['is_android'] != null && $this->request->post['is_android'] != ""){
					$data['is_android'] = $this->request->post['is_android'];
				}else{
					$data['is_android'] = '1';
				}
				
				//var_dump($data);
				
				//die;
				
				$this->model_notes_notes->updatetagsmedicinearchive1($this->request->get['tags_id']);
				
				
				
				$notes_id = $this->model_notes_notes->jsonaddnotes($data, $this->request->get['facilities_id']);
				
				$archive_tags_medication_id = $this->request->get['archive_tags_medication_id'];
				
				$mdata2 = array();
				$mdata2['notes_id'] = $notes_id;
				$mdata2['tags_id'] = $this->request->get['tags_id'];
				$mdata2['archive_tags_medication_id'] = $archive_tags_medication_id;
				
				$this->model_notes_notes->updatetagsmedicinearchive2($mdata2);
				
			
		
			$this->data['facilitiess'][] = array(
				'warning'  => '1',
				'notes_id'  => $notes_id,
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
				'data' => 'Error in addmedform jsonAddNotes',
			);
			$this->model_activity_activity->addActivity('app_addmedform', $activity_data2);
		
		
		} 
	}
	
	public function tagsmedicationsign(){
	

		try{
		$this->data['facilitiess'] = array();
		
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
		
		$json = array();
		
		$this->load->model('notes/notes');
		$this->language->load('notes/notes');
		
		$this->load->model('form/form');

		$this->load->model('resident/resident');
		
		if($this->request->post['notes_pin'] != null && $this->request->post['notes_pin'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if (($this->request->post['notes_pin'] != $user_info['user_pin'])) {
				$json['warning'] = 'User Pin not valid!.';
			}
		}
		
		if($this->request->post['user_id'] != null && $this->request->post['user_id'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if (($user_info['status'] == '0')) {
				$json['warning'] = 'User not exit!';
			}
		}
		
		if($json['warning'] == null && $json['warning'] == ""){
			
			if($this->request->get['facilities_id']){
					$this->load->model('facilities/facilities');
						
					$facilities_info = $this->model_facilities_facilities->getfacilities($this->request->get['facilities_id']);
						
					$this->load->model('setting/timezone');
						
					$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
					$facilitytimezone = $timezone_info['timezone_value'];
			}
			$timezone_name = $facilitytimezone;
						
			date_default_timezone_set($timezone_name);
			
			$noteDate = date('Y-m-d H:i:s', strtotime('now'));
			$date_added = (string) $noteDate;
			
			$notetime = date('H:i:s', strtotime('now'));
			$data['imgOutput'] = $this->request->post['signature'];
			
			$data['notes_pin'] = $this->request->post['notes_pin'];
			$data['user_id'] = $this->request->post['user_id'];
			$data['notes_type'] = $this->request->post['notes_type'];
			
			$this->load->model('setting/tags');
			$tag_info = $this->model_setting_tags->getTag($this->request->get['tags_id']);
			
			$data['emp_tag_id'] = $tag_info['emp_tag_id'];
			$data['tags_id'] = $tag_info['tags_id'];
			
			$data['keyword_file'] = MEDICATION_ICON;
			
			$this->load->model('setting/keywords');
			
			$keywordData2 = $this->model_setting_keywords->getkeywordDetaildesc($data['keyword_file']);
			
			
			if($tag_info['emp_first_name']){
				$emp_tag_id = $tag_info['emp_tag_id'].':'.$tag_info['emp_first_name'];
			}else{
				$emp_tag_id = $tag_info['emp_tag_id'];
			}
						
			if ($tag_info) {
				$medication_tags .= $emp_tag_id.', ';
			}
			
			$description = '';
			$description .= $keywordData2['keyword_name'];
			$description .= ' | ';
			$description .= ' Completed | '.date('h:i A', strtotime($notetime)) .' ';
			$description .= ' Medication given to | ';
			$description .= ' '. $medication_tags;
				
				
			if($this->request->post['comments'] != null && $this->request->post['comments']){
				$description .= ' | '.$this->db->escape($this->request->post['comments']);
			}
			//$description .= ' | ';
			
			//$data['notes_description'] = $keywordData2['keyword_name'].' | '. $tag_info['emp_tag_id'].':'.$tag_info['emp_first_name'] .' | '.$medicationf . $comments;
			
			$data['notes_description'] = $description;
			
			$data['date_added'] = $date_added;
			$data['note_date'] = $date_added;
			$data['notetime'] = $notetime;
			
			
			$data['phone_device_id'] = $this->request->post['phone_device_id'];
						
			if($this->request->post['is_android'] != null && $this->request->post['is_android'] != ""){
				$data['is_android'] = $this->request->post['is_android'];
			}else{
				$data['is_android'] = '1';
			}
			
			//var_dump($data);
			
			//die;
			
			$notes_id = $this->model_notes_notes->jsonaddnotes($data, $this->request->get['facilities_id']);
			
			if($this->request->get['medication_tags']){
				$this->load->model('setting/tags');
						
				//var_dump($this->request->get['medication_tags']);
						
				$medication_tags1 = explode(',',$this->request->get['medication_tags']);
				
				if($this->request->get['facilities_id']){
					$this->load->model('facilities/facilities');
						
					$facilities_info = $this->model_facilities_facilities->getfacilities($this->request->get['facilities_id']);
						
					$this->load->model('setting/timezone');
						
					$timezone_info = $this->model_setting_timezone->gettimezone($facilities_info['timezone_id']);
					$facilitytimezone = $timezone_info['timezone_value'];
				}
				$timezone_name = $facilitytimezone;
						
				date_default_timezone_set($timezone_name);
				$date_added = date('Y-m-d H:i:s', strtotime('now'));
					
				foreach ($medication_tags1 as $medicationtag) {
					$drugs = array();
					$mdrug_info = $this->model_resident_resident->get_medication($medicationtag);
					
					if ($mdrug_info) {
									
						$task_content = 'Resident '.$tag_info['emp_tag_id'].':'.$tag_info['emp_first_name'];
						
						$sql2 = "INSERT INTO `" . DB_PREFIX . "notes_by_task` SET notes_id = '".$notes_id."', locations_id ='".$mdrug_info['locations_id']."', task_type= '2', task_content = '".$this->db->escape($task_content)."', signature= '".$mdrug_info['medication_signature']."', user_id= '".$this->db->escape($mdrug_info['medication_user_id'])."', date_added = '".$date_added."', notes_pin = '".$this->db->escape($mdrug_info['medication_notes_pin'])."', notes_type = '".$this->request->post['notes_type']."', task_time = '".$mdrug_info['task_time']."' , media_url = '".$mdrug_info['media_url']."', capacity = '".$mdrug_info['capacity']."', location_name = '".$this->db->escape($mdrug_info['location_name'])."', location_type = '".$this->db->escape($mdrug_info['location_type'])."', notes_task_type = '2', tags_id = '".$tag_info['tags_id']."', drug_name = '".$this->db->escape($mdrug_info['drug_name'])."', dose = '".$this->db->escape($mdrug_info['dose'])."', drug_type = '".$mdrug_info['drug_type']."', quantity = '".$mdrug_info['quantity']."', frequency = '".$mdrug_info['frequency']."', instructions = '".$this->db->escape($mdrug_info['instructions'])."', count = '".$mdrug_info['count']."', createtask_by_group_id = '".$mdrug_info['createtask_by_group_id']."', task_comments = '".$this->db->escape($mdrug_info['comments'])."', medication_attach_url = '".$mdrug_info['medication_attach_url']."',medication_file_upload='1' , tags_medication_details_id = '".$mdrug_info['tags_medication_details_id']."' , tags_medication_id = '".$mdrug_info['tags_medication_id']."'  ";
								
						$this->db->query($sql2);
						$notes_by_task_id = $this->db->getLastId();
								
					}
				}
						
					
				$update_date = date('Y-m-d H:i:s', strtotime('now'));
				
				if($tag_info['emp_tag_id'] != null && $tag_info['emp_tag_id'] != ""){
					$this->load->model('notes/notes');
						 
					$this->model_notes_notes->updateNotesTag($tag_info['emp_tag_id'], $notes_id, $tag_info['tags_id'], $update_date);
				}
			}
			
			
			$sql = "update `" . DB_PREFIX . "notes` set task_type = '2' where notes_id='".$notes_id."'";
			$this->db->query($sql);
			
		
			$this->data['facilitiess'][] = array(
				'warning'  => '1',
				'notes_id'  => $notes_id,
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
				'data' => 'Error in addmedform jsonAddNotes',
			);
			$this->model_activity_activity->addActivity('app_addmedform', $activity_data2);
		
		
		} 
	
	}
	
	
	protected function validateForm23() {
			

		if ($this->request->post['user_id'] == '') {
			$this->error['user_id'] = $this->language->get('error_required');
		}

		if ($this->request->post['user_id'] != '') {
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if(empty($user_info)){
				$this->error['user_id'] = $this->language->get('error_required');
			}
		}
		
		
		if ($this->request->post['select_one'] == '') {
			$this->error['select_one'] = $this->language->get('error_required');
		}
		
		if ($this->request->post['select_one'] == '1') {
			if ($this->request->post['notes_pin'] == '') {
				$this->error['notes_pin'] = $this->language->get('error_required');
			}
			if($this->request->post['notes_pin'] != null && $this->request->post['notes_pin'] != ""){
				$this->load->model('user/user');
				
				$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

				
				if (($this->request->post['notes_pin'] != $user_info['user_pin'])) {
					$this->error['warning'] = $this->language->get('error_exists');
				}
			}
		}

		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	
	public function jsoncustomsForm2(){
		
			$url2 = "";
			
			if ($this->request->get['tags_id'] != null && $this->request->get['tags_id'] != "") {
				$url2 .= '&tags_id=' . $this->request->get['tags_id'];
				$new_form = '2';
				$tags_id = $this->request->get['tags_id'];
				$url2 .= '&new_form=2';
			}else{
				$new_form = '1';
				$tags_id = '';
				$url2 .= '&new_form=1';
			}
				
			
			if ($this->request->get['facilities_id'] != null && $this->request->get['facilities_id'] != "") {
				$url2 .= '&facilities_id=' . $this->request->get['facilities_id'];
				$facilities_id = $this->request->get['facilities_id'];
			}else{
				$facilities_id = '';
			}
			
			if ($this->request->get['medication_tags'] != null && $this->request->get['medication_tags'] != "") {
				$url2 .= '&medication_tags=' . $this->request->get['medication_tags'];
				$medication_tags = $this->request->get['medication_tags'];
			}else{
				$medication_tags = '';
			}
			if ($this->request->get['archive_tags_medication_id'] != null && $this->request->get['archive_tags_medication_id'] != "") {
				$url2 .= '&archive_tags_medication_id=' . $this->request->get['archive_tags_medication_id'];
				$archive_tags_medication_id = $this->request->get['archive_tags_medication_id'];
			}else{
				$archive_tags_medication_id = '';
			}
		
			if ($this->request->get['addmedicine'] == "2") {
				$medform_url = str_replace('&amp;', '&',$this->url->link('services/resident/tagsmedicationsign2', '' . $url2, 'SSL'));
			}else{
				$medform_url = str_replace('&amp;', '&',$this->url->link('services/resident/tagsmedicationsign', '' . $url2, 'SSL'));
			}
			
			
						
			if($this->request->get['tags_id']){
				$tags_id = $this->request->get['tags_id'];
			}elseif($this->request->get['emp_tag_id']){
				$tags_id = $this->request->get['emp_tag_id'];
			}
			if($tags_id != null && $tags_id != ""){
				$this->load->model('setting/tags');
				$tag_info = $this->model_setting_tags->getTag($tags_id);
				$name = $tag_info['emp_tag_id'].' : '.$tag_info['emp_first_name'] .' '.$tag_info['emp_last_name'];
			}else{
				$name = '';
			}
		$this->data['facilitiess'][] = array(
			'task_form'    => '',
			'emp_tag_id'    => '',
			'archive_tags_id'    => '',
			'tags_id'    => $tags_id,
			'name'    => $name,
			'new_form'    => $new_form,
			'facilities_id'    => $facilities_id,
			'medication_tags'    => $medication_tags,
			'archive_tags_medication_id'    => $archive_tags_medication_id,
			'signature_url'    => $medform_url,
		);
		
		if($this->request->get['is_html'] == '1'){
			
			$this->template = $this->config->get('config_template') . '/template/form/jsoncustom.tpl';
			$this->response->setOutput($this->render());
			
		}else{
			$value = array('results'=>$this->data['facilitiess'],'status'=>true);
		
			$this->response->setOutput(json_encode($value));
		}
	}
	
	
	
	public function jsonrolecall(){
		
		try{
		$this->data['facilitiess'] = array();
		
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
		
		$json = array();
		
		$this->load->model('notes/notes');
		$this->language->load('notes/notes');
		
		$this->load->model('resident/resident');
		
		if($this->request->post['notes_pin'] != null && $this->request->post['notes_pin'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if (($this->request->post['notes_pin'] != $user_info['user_pin'])) {
				$json['warning'] = 'User Pin not valid!.';
			}
		}
		
		if($this->request->post['user_id'] != null && $this->request->post['user_id'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if (($user_info['status'] == '0')) {
				$json['warning'] = 'User not exit!';
			}
		}
		
		if($json['warning'] == null && $json['warning'] == ""){
			
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
				
				
				$notetime = date('H:i:s', strtotime('now'));
				$data['imgOutput'] = $this->request->post['signature'];
				
				$data['notes_pin'] = $this->request->post['notes_pin'];
				$data['user_id'] = $this->request->post['user_id'];
				$data['notes_type'] = $this->request->post['notes_type'];
				
				
				$this->load->model('setting/tags');
				
				if ($this->request->post['keyword_id']) {
					$tag_info = $this->model_setting_tags->getTag($this->request->post['tags_id']);
			
					$data['emp_tag_id'] = $tag_info['emp_tag_id'];
					$data['tags_id'] = $tag_info['tags_id'];
					
					
					
					$this->load->model('setting/keywords');
					$keywordData2 = $this->model_setting_keywords->getkeywordDetail($this->request->post['keyword_id']);
					
					$data['keyword_file'] = $keywordData2['keyword_image'];
					
					
					if($this->request->post['comments'] != null && $this->request->post['comments']){
						$comments = ' | '.$this->request->post['comments'];
					}
					
					if($this->request->post['customlistvalues_id']){
				
						$this->load->model('notes/notes');
						$custom_info = $this->model_notes_notes->getcustomlistvalue($this->request->post['customlistvalues_id']);
						
						$customlistvalues_name = str_replace("'","&#039;", html_entity_decode($custom_info['customlistvalues_name'], ENT_QUOTES));
						
						$description1 = ' | '.$customlistvalues_name;
						
						
						
					}
					
					if($this->request->post['customlistvalues_ids']){
				
						$this->load->model('notes/notes');
						
						foreach($this->request->post['customlistvalues_ids'] as $customlistvalues_id){
						
						$custom_info = $this->model_notes_notes->getcustomlistvalue($customlistvalues_id);
						
						$customlistvalues_name = $custom_info['customlistvalues_name'];
						
						$description1 .= ' | '.$customlistvalues_name;
						
						}
						
						$data['customlistvalues_ids'] = $this->request->post['customlistvalues_ids'];
						
					}
					
					$data['notes_description'] = $keywordData2['keyword_name'].' | '. $tag_info['emp_tag_id'].':'.$tag_info['emp_first_name'] .''.$description1. $comments;
					
					
					
					$data['date_added'] = $date_added;
					$data['note_date'] = $date_added;
					$data['notetime'] = $notetime;
					
					$data['phone_device_id'] = $this->request->post['phone_device_id'];
						
					if($this->request->post['is_android'] != null && $this->request->post['is_android'] != ""){
						$data['is_android'] = $this->request->post['is_android'];
					}else{
						$data['is_android'] = '1';
					}
					
					$notes_id = $this->model_notes_notes->jsonaddnotes($data, $this->request->post['facilities_id']);
				}
				
				
				if ($this->request->post['discharge']== "1") {
					
					$this->load->model('createtask/createtask');
					$alldatas = $this->model_createtask_createtask->getalltaskbyid($this->request->post['tags_id']);
				
				
					if($alldatas != NULL && $alldatas !=""){
						foreach($alldatas as $alldata){
						$result = $this->model_createtask_createtask->getStrikedatadetails($alldata['id']);
						$taskdeleted_notesid = $this->model_createtask_createtask->insertTaskLists($result, $this->request->post['facilities_id'], '1');
						$this->model_createtask_createtask->updatetaskStrike($alldata['id']);
						$this->model_createtask_createtask->deteteIncomTask($this->request->post['facilities_id']);
						}
					}
					
					$this->load->model('setting/tags');
					$tag_info = $this->model_setting_tags->getTag($this->request->post['tags_id']);
						
					$data['emp_tag_id'] = $tag_info['emp_tag_id'];
					$data['tags_id'] = $tag_info['tags_id'];
						
						
					$data['keyword_file'] = DISCHARGE_ICON;
				
					$this->load->model('setting/keywords');
					$keywordData2 = $this->model_setting_keywords->getkeywordDetaildesc($data['keyword_file']);
						
						
					if($this->request->post['comments'] != null && $this->request->post['comments']){
						$comments = ' | '.$this->request->post['comments'];
					}
					
					if($this->request->post['customlistvalues_id']){
				
						$this->load->model('notes/notes');
						$custom_info = $this->model_notes_notes->getcustomlistvalue($this->request->post['customlistvalues_id']);
						
						$customlistvalues_name = str_replace("'","&#039;", html_entity_decode($custom_info['customlistvalues_name'], ENT_QUOTES));
						
						$description1 = ' | '.$customlistvalues_name;
						
					}
					
					if($this->request->post['customlistvalues_ids']){
				
						$this->load->model('notes/notes');
						
						foreach($this->request->post['customlistvalues_ids'] as $customlistvalues_id){
						
						$custom_info = $this->model_notes_notes->getcustomlistvalue($customlistvalues_id);
						
						$customlistvalues_name = $custom_info['customlistvalues_name'];
						
						$description1 .= ' | '.$customlistvalues_name;
						
						}
						
						$data['customlistvalues_ids'] = $this->request->post['customlistvalues_ids'];
						
					}
						
					//$roleCall = "Discharged to";
						
					$data['notes_description'] = $keywordData2['keyword_name'].' | '. $tag_info['emp_tag_id'].':'.$tag_info['emp_first_name'] .''.$description1.$comments;
						
					$data['date_added'] = $date_added;
					$data['note_date'] = $date_added;
					$data['notetime'] = $notetime;
					
					$data['phone_device_id'] = $this->request->post['phone_device_id'];
						
					if($this->request->post['is_android'] != null && $this->request->post['is_android'] != ""){
						$data['is_android'] = $this->request->post['is_android'];
					}else{
						$data['is_android'] = '1';
					}
						
					$notes_id = $this->model_notes_notes->jsonaddnotes($data, $this->request->post['facilities_id']);
					
					$this->load->model('setting/tags');
					$this->model_setting_tags->addcurrentTagarchive($this->request->post['tags_id']);
					$this->model_setting_tags->updatecurrentTagarchive($this->request->post['tags_id'], $notes_id);
						
					$this->model_resident_resident->updateDischargeTag($this->request->post['tags_id'], $date_added);
						
				}
				
				
				if($this->request->post['role_call']){
					$this->load->model('setting/tags');
					$tag_info = $this->model_setting_tags->getTag($this->request->post['tags_id']);
					
					$data['emp_tag_id'] = $tag_info['emp_tag_id'];
					$data['tags_id'] = $tag_info['tags_id'];
					
					
			
					if($this->request->post['role_call'] == '1'){
						$roleCall = "returned to ";
					}
					
					if($this->request->post['role_call'] == '2'){
						$roleCall = "left ";
					}
					
					
					if($this->request->post['comments'] != null && $this->request->post['comments']){
						$comments = ' | '.$this->request->post['comments'];
					}
					
					if($this->request->post['customlistvalues_id']){
				
						$this->load->model('notes/notes');
						$custom_info = $this->model_notes_notes->getcustomlistvalue($this->request->post['customlistvalues_id']);
						
						$customlistvalues_name = str_replace("'","&#039;", html_entity_decode($custom_info['customlistvalues_name'], ENT_QUOTES));
						
						$description1 = ' | '.$customlistvalues_name;
						
					}
					
					if($this->request->post['customlistvalues_ids']){
				
						$this->load->model('notes/notes');
						
						foreach($this->request->post['customlistvalues_ids'] as $customlistvalues_id){
						
						$custom_info = $this->model_notes_notes->getcustomlistvalue($customlistvalues_id);
						
						$customlistvalues_name = $custom_info['customlistvalues_name'];
						
						$description1 .= ' | '.$customlistvalues_name;
						
						}
						
						$data['customlistvalues_ids'] = $this->request->post['customlistvalues_ids'];
						
					}
					
					$data['notes_description'] = $tag_info['emp_tag_id'].':'.$tag_info['emp_first_name'] .' | '.$form_name.' has '.$roleCall.' the Facility'.$description1. $comments ;
					
					$data['date_added'] = $date_added;
					$data['note_date'] = $date_added;
					$data['notetime'] = $notetime;
					
					$data['phone_device_id'] = $this->request->post['phone_device_id'];
						
					if($this->request->post['is_android'] != null && $this->request->post['is_android'] != ""){
						$data['is_android'] = $this->request->post['is_android'];
					}else{
						$data['is_android'] = '1';
					}
					
					$notes_id = $this->model_notes_notes->jsonaddnotes($data, $this->request->post['facilities_id']);
					
					$this->model_resident_resident->updatetagrolecall($this->request->post['tags_id'], $this->request->post['role_call']);
					
				}
			
			
				if($this->request->post['role_calls']){
					$tagname = "";
					
					$girl1 = 0;
					$boy1 = 0;
					$total1 = '';
					
					$girl12 = 0;
					$boy12 = 0;
					$tagname111 = array();
					foreach($this->request->post['role_calls'] as $key=>$rolecall){
						
						
						$tag_info = $this->model_setting_tags->getTag($key);
						
						
						if($rolecall['role_call'] == '1'){
							$this->model_resident_resident->updatetagrolecall($key, $rolecall['role_call']);
							
							$emp_tag_id = $tag_info['emp_tag_id'];
							$tags_id = $tag_info['tags_id'];
							
							$tagname .= $tag_info['emp_tag_id'].':'.$tag_info['emp_first_name'].',';
							
							//var_dump($tag_info['gender']);
							
							/*if($tag_info['gender'] == '1'){
								$boy1 = $boy1+1 ;
							}
							
							if($tag_info['gender'] == '2'){
								$girl1 = $girl1+1 ;
							}*/
						
						}
						
						//$tagname .= implode(", ",$tagname111);
						$tagname211 = array();
						if($rolecall['role_call'] == '2'){
							$this->model_resident_resident->updatetagrolecall($key, $rolecall['role_call']);
							
							$emp_tag_id = $tag_info['emp_tag_id'];
							$tags_id = $tag_info['tags_id'];
							
							$tagname2 .= $tag_info['emp_tag_id'].':'.$tag_info['emp_first_name'].',';
							
							//var_dump($tag_info['gender']);
							
							/*if($tag_info['gender'] == '1'){
								$girl12 = $girl12+1 ;
							}
							
							if($tag_info['gender'] == '2'){
								$boy12 = $boy12+1 ;
							}*/
						}
						//$tagname2 .= implode(", ",$tagname211);
						
						
					}
					
					
					
					$this->load->model('facilities/facilities');
					$facilityinfo = $this->model_facilities_facilities->getfacilities($this->request->post['facilities_id']);
					$this->load->model('notes/notes');
					
					if($facilityinfo['config_tags_customlist_id'] !=NULL && $facilityinfo['config_tags_customlist_id'] !=""){
						$d2 = array();
						$d2['customlistvalueids'] = $facilityinfo['config_tags_customlist_id'];
						$customlistvalues = $this->model_notes_notes->getcustomlistvalues($d2);
						if($customlistvalues){
							
							foreach($customlistvalues as $customlistvalue){
								
								$customlistvalues_total = $this->model_setting_tags->gettotalcustomlistvaluebyid($customlistvalue['customlistvalues_id'], $customlistvalue['gender'] ,'1', $this->request->post['facilities_id']);
								
								if($customlistvalues_total > 0 ){
									$total1 = $total1 + $customlistvalues_total;
									$boygirl .= $customlistvalues_total .' '.$customlistvalue['customlistvalues_name'].' ';
									
									$boygirl .=  'and ';
								}
								
							}
						}
					}
					
					$boygirl .=  $total1.' Total ';
					
					
					/*if(($boy1 != null && $boy1 != "") && ($girl1 != null && $girl1 != "")){
						$boygirl = $boy1.' Boys and '. $girl1 .' Girls | '. ($boy1 + $girl1) . ' Total';
					}
					
					
					if(($boy1 != null && $boy1 != "") && ($girl1 == null && $girl1 == "") ){
						$boygirl = $boy1.' Boys';
					}
					
					if(($boy1 == null && $boy1 == "") && ($girl1 != null && $girl1 != "")){
						$boygirl = $girl1.' Girls';
					}*/
					
					
					/*if(($boy12 != null && $boy12 != "") && ($girl12 != null && $girl12 != "")){
						$boygirl2 = $boy12.' Boys and '. $girl12 .' Girls | '. ($boy12 + $girl12) . ' Total';
					}
					
					
					if(($boy12 != null && $boy12 != "") && ($girl12 == null && $girl12 == "") ){
						$boygirl2 = $boy12.' Boys';
					}
					
					if(($boy12 == null && $boy12 == "") && ($girl12 != null && $girl12 != "")){
						$boygirl2 = $girl12.' Girls';
					}*/
					
					
					$total12 = 0;
					if($facilityinfo['config_tags_customlist_id'] !=NULL && $facilityinfo['config_tags_customlist_id'] !=""){
						$d2 = array();
						$d2['customlistvalueids'] = $facilityinfo['config_tags_customlist_id'];
						$customlistvalues = $this->model_notes_notes->getcustomlistvalues($d2);
						if($customlistvalues){
							
							foreach($customlistvalues as $customlistvalue){
								
								$customlistvalues_total = $this->model_setting_tags->gettotalcustomlistvaluebyid($customlistvalue['customlistvalues_id'], $customlistvalue['gender'] ,'2', $this->request->post['facilities_id']);
								
								if($customlistvalues_total > 0 ){
									$total12 = $total12 + $customlistvalues_total;
									$boygirl2 .= $customlistvalues_total .' '.$customlistvalue['customlistvalues_name'].' ';
									
									$boygirl2 .=  'and ';
								}
								
							}
						}
					}
					
					$boygirl2 .=  $total12.' Total ';
					
					
					$outtag = ' | '. $tagname2.$boygirl2.' Clients are OUT of the facility';
					
					
					//$data['emp_tag_id'] = $emp_tag_id;
					//$data['tags_id'] = $tags_id;
					
					$data['keyword_file'] = HEADCOUNT_ICON;
					
					$this->load->model('setting/keywords');
					$keywordData2 = $this->model_setting_keywords->getkeywordDetaildesc($data['keyword_file']);
					
					
					if($this->request->post['comments'] != null && $this->request->post['comments']){
						$comments = ' | '.$this->request->post['comments'];
					}
					
					if($this->request->post['customlistvalues_id']){
				
						$this->load->model('notes/notes');
						$custom_info = $this->model_notes_notes->getcustomlistvalue($this->request->post['customlistvalues_id']);
						
						$customlistvalues_name = str_replace("'","&#039;", html_entity_decode($custom_info['customlistvalues_name'], ENT_QUOTES));
						
						$description1 = ' | '.$customlistvalues_name;
						
					}
					
					if($this->request->post['customlistvalues_ids']){
				
						$this->load->model('notes/notes');
						
						foreach($this->request->post['customlistvalues_ids'] as $customlistvalues_id){
						
						$custom_info = $this->model_notes_notes->getcustomlistvalue($customlistvalues_id);
						
						$customlistvalues_name = $custom_info['customlistvalues_name'];
						
						$description1 .= ' | '.$customlistvalues_name;
						
						}
						
						$data['customlistvalues_ids'] = $this->request->post['customlistvalues_ids'];
						
					}
					
					
					$fdataa = array();
					$fdataa['is_monitor_time'] = '1';
					$fdataa['facilities_id'] = $this->request->post['facilities_id'];
					$fdataa['date_added'] = date('Y-m-d', strtotime('now'));
					
					$signnotes_infos = $this->model_notes_notes->getNotebyactivenotes($fdataa);
					
					$sign_users = "";
					$sign_users1 = array();
					if($signnotes_infos != null && $signnotes_infos != ""){
						$sign_users .= " | STAFF ";
						foreach($signnotes_infos as $signnotes_info){
							$sign_users .= $signnotes_info['user_id'].',';
						}
						//$sign_users .= implode(", ",$sign_users1);
					}
					
					$data['notes_description'] = $keywordData2['keyword_name'].' | '. $tagname .' | '.$boygirl.' Clients are in the Facility '. $outtag . $description1.$comments . $sign_users;
					
					
					$data['date_added'] = $date_added;
					$data['note_date'] = $date_added;
					$data['notetime'] = $notetime;
					
					$data['phone_device_id'] = $this->request->post['phone_device_id'];
						
					if($this->request->post['is_android'] != null && $this->request->post['is_android'] != ""){
						$data['is_android'] = $this->request->post['is_android'];
					}else{
						$data['is_android'] = '1';
					}
					
					$notes_id = $this->model_notes_notes->jsonaddnotes($data, $this->request->post['facilities_id']);
				}
				
		
			$this->data['facilitiess'][] = array(
				'warning'  => '1',
				'notes_id'  => $notes_id,
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
				'data' => 'Error in addclient jsonrolecall',
			);
			$this->model_activity_activity->addActivity('app_jsonrolecall', $activity_data2);
		
		
		} 
	}
	
	
	public function jsonclienttagform(){
		try{
			
		$this->data['facilitiess'] = array();
		
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
		
		$this->language->load('notes/notes');
		$this->load->model('setting/tags');
		$this->load->model('form/form');
		$this->load->model('notes/notes'); 
		
		$tags_id = $this->request->get['tags_id'];
		
		$tag_info = $this->model_setting_tags->getTag($tags_id);
		
		$name = $tag_info['emp_tag_id'].': '.$tag_info['emp_first_name'] .' '.$tag_info['emp_last_name'];
		
		if (isset($this->request->post['page'])) {
			$page = $this->request->post['page'];
		} else {
			$page = 1;
		}
		
		
		
		
		$config_admin_limit1 = '5';
		//$this->config->get('config_front_limit');
		
		$config_admin_limit1 = $this->config->get('config_android_front_limit');
		
		if($config_admin_limit1 != null && $config_admin_limit1 != ""){
			$config_admin_limit = $config_admin_limit1;
		}else{
			$config_admin_limit = "25";
		}
		
		
		$data = array(
		'tags_id' => $tags_id,
		'start' => ($page - 1) * $config_admin_limit,
		'limit' => $config_admin_limit
		
		);
		
		$results = $this->model_form_form->gettagsforms($data);
		
		$form_total = $this->model_form_form->getTotalforms2($data);
		
		//$results = $this->model_form_form->getTotalforms2($tags_id);
    	
		foreach ($results as $allform) {
			
			$form_info = $this->model_form_form->getFormdata($allform['custom_form_type']);
			
			if($allform['notes_id'] > 0){
			$note_info = $this->model_notes_notes->getNote($allform['notes_id']);
			}
			
			if($allform['user_id'] != null && $allform['user_id'] != ""){
						$user_id = $allform['user_id'];
						$signature = $allform['signature'];
						$notes_pin = $allform['notes_pin'];
						$notes_type = $allform['notes_type'];
						
						if($allform['form_date_added'] != null && $allform['form_date_added'] != "0000-00-00 00:00:00"){
							$form_date_added = date($this->language->get('date_format_short_2'), strtotime($allform['form_date_added']));
						}else{
							$form_date_added = '';
						}
						
					}else{
						$user_id = $note_info['user_id'];
						$signature = $note_info['signature'];
						$notes_pin = $note_info['notes_pin'];
						$notes_type = $note_info['notes_type'];
						
						if($note_info['note_date'] != null && $note_info['note_date'] != "0000-00-00 00:00:00"){
							$form_date_added = date($this->language->get('date_format_short_2'), strtotime($note_info['note_date']));
						}else{
							$form_date_added = '';
						}
					}
			 
				$form_url =	str_replace('&amp;', '&', $this->url->link('services/form', '' . 'forms_id=' . $allform['forms_id']. '&facilities_id=' . $allform['facilities_id']. '&notes_id=' . $allform['notes_id']. '&forms_design_id=' . $allform['custom_form_type']. '&tags_id=' . $allform['tags_id']));
				
				
					
					if($allform['custom_form_type'] == '13' ){
						$print_url = str_replace('&amp;', '&', $this->url->link('form/form/printformfldjj', '' . 'forms_id=' . $allform['forms_id']. '&facilities_id=' . $allform['facilities_id']. '&notes_id=' . $allform['notes_id']. '&forms_design_id=' . $allform['custom_form_type'], true));
					}elseif($allform['custom_form_type'] == '9' ){
						//$print_url = str_replace('&amp;', '&', $this->url->link('form/form/printmonthly_firredrill', '' . 'forms_id=' . $allform['forms_id']. '&facilities_id=' . $allform['facilities_id']. '&notes_id=' . $allform['notes_id']. '&forms_design_id=' . $allform['custom_form_type'], true));
						$print_url = str_replace('&amp;', '&', $this->url->link('form/form/printform', '' . 'forms_id=' . $allform['forms_id']. '&facilities_id=' . $allform['facilities_id']. '&notes_id=' . $allform['notes_id']. '&forms_design_id=' . $allform['custom_form_type'], true));
					}elseif($allform['custom_form_type'] == '10' ){
						//$print_url = str_replace('&amp;', '&', $this->url->link('form/form/printincidentform', '' . 'forms_id=' . $allform['forms_id']. '&facilities_id=' . $allform['facilities_id']. '&notes_id=' . $allform['notes_id']. '&forms_design_id=' . $allform['custom_form_type'], true));
						$print_url = str_replace('&amp;', '&', $this->url->link('form/form/printform', '' . 'forms_id=' . $allform['forms_id']. '&facilities_id=' . $allform['facilities_id']. '&notes_id=' . $allform['notes_id']. '&forms_design_id=' . $allform['custom_form_type'], true));
					}elseif($allform['custom_form_type'] == '2' ){
						//$print_url = str_replace('&amp;', '&', $this->url->link('form/form/printintakeform', '' . 'forms_id=' . $allform['forms_id']. '&facilities_id=' . $allform['facilities_id']. '&notes_id=' . $allform['notes_id']. '&forms_design_id=' . $allform['custom_form_type'], true));
						$print_url = str_replace('&amp;', '&', $this->url->link('form/form/printform', '' . 'forms_id=' . $allform['forms_id']. '&facilities_id=' . $allform['facilities_id']. '&notes_id=' . $allform['notes_id']. '&forms_design_id=' . $allform['custom_form_type'], true));
					}elseif($allform['custom_form_type'] == '12' ){
						//$print_url = str_replace('&amp;', '&', $this->url->link('form/form/printintakeform', '' . 'forms_id=' . $allform['forms_id']. '&facilities_id=' . $allform['facilities_id']. '&notes_id=' . $allform['notes_id']. '&forms_design_id=' . $allform['custom_form_type'], true));
						$print_url = str_replace('&amp;', '&', $this->url->link('form/form/printform', '' . 'forms_id=' . $allform['forms_id']. '&facilities_id=' . $allform['facilities_id']. '&notes_id=' . $allform['notes_id']. '&forms_design_id=' . $allform['custom_form_type'], true));
					}else{
						$print_url = '';
					}
					
					
					//var_dump($allform);
					
				
				$this->data['facilitiess'][] = array(
					'form_type_id' => $allform['form_type_id'],
							'notes_id' => $allform['notes_id'],
							'form_type' => $allform['form_type'],
							'notes_type' => $notes_type,
							'user_id' => $user_id,
							'signature' => $signature,
							'notes_pin' => $notes_pin,
							'incident_number' => $allform['incident_number'],
							'form_date_added' => $form_date_added,
							'date_added2' => date('D F j, Y', strtotime($allform['date_added'])),
							'href'        => $form_url,
							'print_url'        => $print_url,
							'audio_attach_url' => '',
							'notes_by_task_id' => '',
							'locations_id' => '',
							'task_type' => '',
							'task_content' => '',
							'task_time' => '',
							'media_url' => '',
							'capacity' => '',
							'location_name' => '',
							'location_type' => '',
							'notes_task_type' => '',
							'tags_id' => '',
							'drug_name' => '',
							'dose' => '',
							'drug_type' => '',
							'quantity' =>'',
							'frequency' => '',
							'instructions' => '',
							'count' =>'',
							'createtask_by_group_id' => '',
							'task_comments' => '',
							'medication_file_upload' => '',
							'date_added' => '',
							'is_tag_url' => '',
							'is_census_url' => '',
							
				
				);
		}
		
		$value = array('results'=>$this->data['facilitiess'],'form_total' => $form_total,'status'=>true, 'client_name'=>$name);
		
		$this->response->setOutput(json_encode($value));
		
		}catch(Exception $e){
				$this->load->model('activity/activity');
				$activity_data2 = array(
					'data' => 'Error in apptask jsonclienttagform',
				);
				$this->model_activity_activity->addActivity('app_jsonclienttagform', $activity_data2);
		}
	
	}
	
	
	
	public function jsonAddSticky(){
  
	  try{
	 
	  $this->data['facilitiess'] = array();
	  $json = array();
	  
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
	   
	  if($json['warning'] == null && $json['warning'] == ""){
	   $data = array();
	   
		$data['stickynote'] = $this->request->post['stickynote'];
		$data['tags_id'] = $this->request->post['tags_id'];
		
		$this->load->model('setting/tags');
		$this->model_setting_tags->updateSticky($data);  
		
	   $this->data['facilitiess'][] = array(
		'warning'  => '1',
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
		 'data' => 'Error in apptask jsonAddSticky',
		);
		$this->model_activity_activity->addActivity('app_jsonAddSticky', $activity_data2);
	   //echo 'Caught exception: ',  $e->getMessage(), "\n";
	  }
	 
	 }
	 
	   
	 public function jsonGetSticky(){
	  
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
	  $this->load->model('setting/tags');
	  $stickyinfo = $this->model_setting_tags->getTag($this->request->post['tags_id']);
	  
	  if($stickyinfo['stickynote'] != null && $stickyinfo['stickynote'] != ""){
		$this->data['facilitiess']['stickyinfo']  = $stickyinfo['stickynote'];
	  }else{
		  $this->data['facilitiess']['stickyinfo']  = '';
	  }
	  
	  $value = array('results'=>$this->data['facilitiess'],'status'=>true);
	  
	  $this->response->setOutput(json_encode($value));
	 
	 }catch(Exception $e){
		$this->load->model('activity/activity');
		$activity_data2 = array(
		 'data' => 'Error in apptask jsonGetSticky',
		);
		$this->model_activity_activity->addActivity('app_jsonGetSticky', $activity_data2);
	   //echo 'Caught exception: ',  $e->getMessage(), "\n";
	  }
	 
	 }
	
	
	public function getcustomfieldbyFID(){
		
		try{
			
		$this->data['facilitiess'] = array();
		
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
		
		$json = array();
		
		$this->load->model('facilities/facilities');
		$facilityinfo = $this->model_facilities_facilities->getfacilities($this->request->post['facilities_id']);
		$this->load->model('notes/notes');
		
		if($facilityinfo['config_rolecall_customlist_id'] !=NULL && $facilityinfo['config_rolecall_customlist_id'] !=""){
			
				$d = array();
				
				$d['customlist_id'] = $facilityinfo['config_rolecall_customlist_id'];
				
				$customlists = $this->model_notes_notes->getcustomlists($d);
				
					if($customlists){
						foreach($customlists as $customlist){
							$d2 = array();
							$d2['customlist_id'] = $customlist['customlist_id'];
							$customlistvalues = $this->model_notes_notes->getcustomlistvalues($d2);
							$this->data['facilitiess'][] = array(
							'customlist_id' => $customlist['customlist_id'],
							'customlist_name'  => $customlist['customlist_name'],
							'customlistvalues'  => $customlistvalues,
							);
						}
					}
			
			
		$error = true;
		
		}else{
			$this->data['facilitiess'][] = array(
				'warning'  => $json['warning'],
			);
			$error = true;
		}
		
		$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
		
		$this->response->setOutput(json_encode($value));
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in addclient getcustomfieldbyFID',
			);
			$this->model_activity_activity->addActivity('app_getcustomfieldbyFID', $activity_data2);
		
		
		} 
		
		
	}
	
	
	public function jsonassignteam(){
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
			$this->language->load('notes/notes');
			
			$this->load->model('resident/resident');
			
			$this->load->model('form/form'); 
			 
			$data3 = array();
			$data3['tags_id'] = $this->request->post['tags_id'];
			$data3['is_archive'] = $this->request->post['is_archive'];
			$data3['notes_id'] = $this->request->post['notes_id'];
			$data3['facilities_id'] = $this->request->post['facilities_id'];
			$team_infos = $this->model_resident_resident->getassignteam($data3);
			
			$this->data['user_roles'] = array();
			$this->load->model('user/user_group');
			$this->load->model('user/user');
			
			$this->data['facilitiess'] = array();
			
			if($team_infos != null && $team_infos != ""){
				foreach($team_infos as $team_info){
					//var_dump($team_info);
					$user_role_info = $this->model_user_user_group->getUserGroup($team_info['user_roles']);
				
					if ($user_role_info) {
						$users = array();
						
						$data3u = array();
						$data3u['tags_id'] = $this->request->post['tags_id'];
						$data3u['is_archive'] = $this->request->post['is_archive'];
						$data3u['notes_id'] = $this->request->post['notes_id'];
						$data3u['user_roles'] = $team_info['user_roles'];
						$data3u['facilities_id'] = $this->request->post['facilities_id'];
						
						$uresults = $this->model_resident_resident->getassignteamUsers($data3u);
						
						
						
						if($uresults != null && $uresults != ""){
							foreach ($uresults as $user) {
								$user_info = $this->model_user_user->getUserbyupdate($user['userids']);
								
								if($user_info['user_id']){
									$users[] = array(
										'user_id' => $user_info['user_id'], 
										'username' => strip_tags(html_entity_decode($user_info['username'], ENT_QUOTES, 'UTF-8'))
									);
								}
							}	
						}
						
						$this->data['facilitiess'][] = array(
							'user_group_id' => $team_info['user_roles'],
							'name'        => $user_role_info['name'],
							'users'        => $users,
						);
					}
				}
			}
			
		$value = array('results'=>$this->data['facilitiess'],'status'=>true);
	  
		$this->response->setOutput(json_encode($value));
	 
	 }catch(Exception $e){
		$this->load->model('activity/activity');
		$activity_data2 = array(
		 'data' => 'Error in apptask jsonassignteam',
		);
		$this->model_activity_activity->addActivity('app_jsonassignteam', $activity_data2);
	   
	  }
	}
	
	public function jsonaddassignteam(){
		
		try{
		$this->data['facilitiess'] = array();
		
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
		
		$json = array();
		
		$this->load->model('notes/notes');
		$this->language->load('notes/notes');
		
		$this->load->model('resident/resident');
		
		$this->load->model('form/form');

		
		if($this->request->post['notes_pin'] != null && $this->request->post['notes_pin'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if (($this->request->post['notes_pin'] != $user_info['user_pin'])) {
				$json['warning'] = 'User Pin not valid!.';
			}
		}
		
		if($this->request->post['user_id'] != null && $this->request->post['user_id'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if (($user_info['status'] == '0')) {
				$json['warning'] = 'User not exit!';
			}
		}
		
		if($json['warning'] == null && $json['warning'] == ""){
			
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
			
			
			$notetime = date('H:i:s', strtotime('now'));
			$data['imgOutput'] = $this->request->post['signature'];
			
			$data['notes_pin'] = $this->request->post['notes_pin'];
			$data['user_id'] = $this->request->post['user_id'];
			$data['notes_type'] = $this->request->post['notes_type'];
			
			
			$this->load->model('setting/tags');
			$tag_info = $this->model_setting_tags->getTag($this->request->post['tags_id']);
			
			$data['emp_tag_id'] = $tag_info['emp_tag_id'];
			$data['tags_id'] = $tag_info['tags_id'];
			
			if($tag_info['emp_first_name']){
				$emp_tag_id = $tag_info['emp_tag_id'].':'.$tag_info['emp_first_name'];
			}else{
				$emp_tag_id = $tag_info['emp_tag_id'];
			}
						
			if ($tag_info) {
				$medication_tags .= $emp_tag_id.' ';
			}
			
			$description = '';
			
			$description .= ' Team Assignment Updated. | ';
			$description .= ' '. $medication_tags;
				
				
			if($this->request->post['comments'] != null && $this->request->post['comments']){
				$description .= ' | '.$this->db->escape($this->request->post['comments']);
			}
			
			$data['notes_description'] = $description;
			
			$data['date_added'] = $date_added;
			$data['note_date'] = $date_added;
			$data['notetime'] = $notetime;
			
		
			$data['phone_device_id'] = $this->request->post['phone_device_id'];
						
			if($this->request->post['is_android'] != null && $this->request->post['is_android'] != ""){
				$data['is_android'] = $this->request->post['is_android'];
			}else{
				$data['is_android'] = '1';
			}
			
			
			
			$this->model_notes_notes->updatetagsassign1($this->request->get['tags_id']);
			
			$notes_id = $this->model_notes_notes->jsonaddnotes($data, $this->request->post['facilities_id']);
			
			
			$data2 = array();
			$data2['tags_id'] = $this->request->post['tags_id'];
			$data2['date_added'] = $date_added;
			$data2['facilities_id'] = $this->request->post['facilities_id'];
			
			
			$data3 = array();
			//$data3['user_roles'] = explode(',',$this->request->post['user_roles']);
			//$data3['userids'] = explode(',',$this->request->post['userids']);
			
			$data3['user_roles']  = array_unique($this->request->post['user_roles']);
			$data3['userids']  = array_unique($this->request->post['userids']);
			$data3['notes_id'] = $notes_id;
			
			$this->model_resident_resident->addassignteam($data2, $data3);
			
			$this->model_notes_notes->updatetagsassign23($this->request->get['tags_id'], $notes_id);
			
		
			$this->data['facilitiess'][] = array(
				'warning'  => '1',
				'notes_id'  => $notes_id,
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
				'data' => 'Error in addclient jsonaddassignteam',
			);
			$this->model_activity_activity->addActivity('app_jsonaddassignteam', $activity_data2);
		
		
		} 
	}
	
	public function  jsonresidentstatus(){
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
			$this->language->load('notes/notes');
			
			
			if ($this->request->post['tags_id'] != null && $this->request->post['tags_id'] != "") {
				$timezone_name = $this->request->post['facilitytimezone'];
				$timeZone = date_default_timezone_set($timezone_name);
				date_default_timezone_set($timezone_name);
				$currentdate = date('Y-m-d');
				$data = array(
					'currentdate' => $currentdate,
					'tags_id' => $this->request->post['tags_id'],
				);
				
				$this->load->model('resident/resident');
				$task_infos = $this->model_resident_resident->getResidentstatus($data);
				$totaltask_infos = $this->model_resident_resident->getTotalResidentstatus($data);
				
				$task_info = array();
				$form_info = array();
				$notes = array();
				foreach($task_infos as $taskinfo){
					
					$tagstatus_info = $this->model_resident_resident->getTagstatusbyId($taskinfo['tagstatus_id']);
					$task_info[] = array(
						'tasktype' => $taskinfo['tasktype'],
						'date_added' => date('m-d-Y',strtotime($taskinfo['date_added'])),
						'description' => $taskinfo['description'],
						'assign_to' => $taskinfo['assign_to'],
						'task_time' => date('h:i A',strtotime($taskinfo['task_time'])),
						'task_date' => date('m-d-Y',strtotime($taskinfo['task_date'])),
						'count'=>$totaltask_infos,
						'taskid' => $taskinfo['id'],
						'tagstatus_id' => $tagstatus_info['status'],
					);
				}
				
			
			
			$this->load->model('form/form');
			$form_infos = $this->model_form_form->getformstatus($data);
			$totalform_infos = $this->model_form_form->gettotalformstatus($data);
			
			foreach($form_infos as $formdata){
				$tagstatus_info = $this->model_resident_resident->getTagstatusbyId($formdata['tagstatus_id']);
					
				$form_info[] = array(
					'form_description' => $formdata['form_description'],
					'date_added' => date('m-d-Y',strtotime($formdata['date_added'])),
					'count' => $totalform_infos,
					'forms_id' => $formdata['forms_id'],
					'tagstatus_id' => $tagstatus_info['status'],
				
				);
			}  
			
			
		
			$tagstatusinfo = $this->model_resident_resident->getTagstatusbyId($this->request->post['tags_id']);
			$tagstatus_info1  = $tagstatusinfo['status'];
			
			$currentdate2 = date('d-m-Y');
			
			$this->load->model('createtask/createtask');
			$tasksinfo = $this->model_createtask_createtask->getTaskas($this->request->post['tags_id'], $currentdate2);
			$tasksinfo1  = $tasksinfo*100;
			
			$this->load->model('setting/tags');
			$taginfo = $this->model_setting_tags->getTag($this->request->post['tags_id']);
			
			
			$data = array(
				'sort'  => $sort,
				'order' => $order,
				'searchdate' => $searchdate,
				'searchdate_app' => '1',
				'tagstatus_id' => '1',
				'emp_tag_id' => $this->request->post['tags_id'],
				'facilities_id' => $this->request->post['facilities_id'],
				'start' => 0,
				'limit' => 500
			);
	 
	
			
			
			$this->load->model('notes/image');
			$this->load->model('setting/highlighter');
			$this->load->model('user/user');
			$this->load->model('notes/notes');
			$this->load->model('facilities/facilities');
			
			$this->load->model('notes/tags');
			$notes_total = $this->model_notes_notes->getTotalnotess($data);
			
			
			$last_notesID = $this->model_notes_notes->getLastNotesID($this->request->post['facilities_id'], $searchdate);
			
			
			$this->data['last_notesID'] = $last_notesID['notes_id'];
			
			
			$this->load->model('facilities/facilities');
			$facilities_info = $this->model_facilities_facilities->getfacilities($this->request->post['facilities_id']);
			$results = $this->model_notes_notes->getnotess($data);
			
			//	var_dump($results);
			
			$this->load->model('notes/tags');
			
			
			
			$facilityinfo = $this->model_facilities_facilities->getfacilities($this->request->post['facilities_id']);
			//var_dump($facilityinfo);
			
			//var_dump($results);
			
				foreach ($results as $result) {
					
					if($result['notes_pin'] != null && $result['notes_pin'] != ""){
						$userPin = $result['notes_pin'];
					}else{
						$userPin = '';
					}
					
					if($result['signature'] != null && $result['signature'] != ""){
						$signaturesrc = $result['signature'];
					}else{
						$signaturesrc = '';
					}
					
					$notes[] = array(
						'notes_id'  => $result['notes_id'],
						'notes_type'    => $result['notes_type'],
						'notes_description'   => $result['notes_description'],
						'notetime'   => date('h:i A', strtotime($result['notetime'])),
						'username'      => $result['user_id'],
						'signature'   => $signaturesrc,
						'notes_pin'   => $notesPin,
						'note_date'   => date($this->language->get('date_format_short'), strtotime($result['note_date'])),
					); 
				}
				
				$this->data['facilitiess'][] = array(
					'notes' => $notes,
					'task_info'  => $task_info,
					'form_info'        => $form_info,
					'tagstatus_info1'        => $tagstatus_info1,
					'tasksinfo1'        => $tasksinfo1,
					'taginfo'        => $taginfo,
				);
				
				
			}else{
				$this->data['facilitiess'][] = array(
					'warning'  => 'Please Enter Tags id',
				);
				
			}
			
		$value = array('results'=>$this->data['facilitiess'],'status'=>true);
	  
		$this->response->setOutput(json_encode($value));
	 
	 }catch(Exception $e){
		$this->load->model('activity/activity');
		$activity_data2 = array(
		 'data' => 'Error in apptask jsonresidentstatus',
		);
		$this->model_activity_activity->addActivity('app_jsonresidentstatus', $activity_data2);
	   
	  }
	}
	
	
	public function jsonaddresidentstatus(){
		
		try{
		$this->data['facilitiess'] = array();
		
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
		
		$json = array();
		
		$this->load->model('notes/notes');
		$this->language->load('notes/notes');
		
		$this->load->model('resident/resident');
		
		$this->load->model('form/form');

		
		if($this->request->post['notes_pin'] != null && $this->request->post['notes_pin'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if (($this->request->post['notes_pin'] != $user_info['user_pin'])) {
				$json['warning'] = 'User Pin not valid!.';
			}
		}
		
		if($this->request->post['user_id'] != null && $this->request->post['user_id'] != ""){
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUser($this->request->post['user_id']);

			if (($user_info['status'] == '0')) {
				$json['warning'] = 'User not exit!';
			}
		}
		
		if($json['warning'] == null && $json['warning'] == ""){
			
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
			
			
			$notetime = date('H:i:s', strtotime('now'));
			$data['imgOutput'] = $this->request->post['signature'];
			
			$data['notes_pin'] = $this->request->post['notes_pin'];
			$data['user_id'] = $this->request->post['user_id'];
			$data['notes_type'] = $this->request->post['notes_type'];
			
			
			$data['childstatus'] = $this->request->post['childstatus'];
			$data['facilitytimezone'] = $facilitytimezone;
			
			
			$this->load->model('setting/tags');
			$tag_info = $this->model_setting_tags->getTag($this->request->post['tags_id']);
			
			$data['emp_tag_id'] = $tag_info['emp_tag_id'];
			$data['tags_id'] = $tag_info['tags_id'];
			
			if($tag_info['emp_first_name']){
				$emp_tag_id = $tag_info['emp_tag_id'].':'.$tag_info['emp_first_name'];
			}else{
				$emp_tag_id = $tag_info['emp_tag_id'];
			}
						
			if ($tag_info) {
				$medication_tags .= $emp_tag_id.' ';
			}
			
			
			
			$currentdate = date('Y-m-d');
			
			$tagstatus = array();
			$data2 = array(
			'currentdate' => $currentdate,
			'tags_id' => $this->request->post['tags_id'],
			);
			
			$this->load->model('resident/resident');
			$task_infos = $this->model_resident_resident->getResidentstatus($data2);
			
			
			
			$totaltask_infos = $this->model_resident_resident->getTotalResidentstatus($data2);
			
			foreach($task_infos as $taskinfo){
				$tagstatus_info = $this->model_resident_resident->getTagstatusbyId($taskinfo['tagstatus_id']);
				$tagstatus[] = array(
					'task_id' => $taskinfo['id'],
				);
			}
			
			$this->load->model('form/form');
			$form_infos = $this->model_form_form->getformstatus($data2);
			$totalform_infos = $this->model_form_form->gettotalformstatus($data2s);
			
			foreach($form_infos as $formdata){
				$tagstatus_info = $this->model_resident_resident->getTagstatusbyId($formdata['tagstatus_id']);
					
				$tagstatus[] = array(
					'forms_id' => $formdata['forms_id'],
				
				);
			}
				
			$description = '';	
			if($this->request->post['comments'] != null && $this->request->post['comments']){
				$description .= ' | '.$this->db->escape($this->request->post['comments']);
			}
			
			if($this->request->post['childstatus'] == 'high'){
				$childstatus = 'High';
			}
			
			if($this->request->post['childstatus'] == 'moderate'){
				$childstatus = 'Moderate';
			}
			if($this->request->post['childstatus'] == 'normal'){
				$childstatus = 'Normal';
			}
			
			$data['notes_description'] = ' Client Status turned to '.$childstatus.' '.$comments;
			
			$data['date_added'] = $date_added;
			$data['note_date'] = $date_added;
			$data['notetime'] = $notetime;
			
		
			$data['phone_device_id'] = $this->request->post['phone_device_id'];
						
			if($this->request->post['is_android'] != null && $this->request->post['is_android'] != ""){
				$data['is_android'] = $this->request->post['is_android'];
			}else{
				$data['is_android'] = '1';
			}
			
			
			
			$notes_id = $this->model_notes_notes->jsonaddnotes($data, $this->request->post['facilities_id']);
			
			if($tagstatus != NULL && $tagstatus !=""){
				$this->load->model('resident/resident');
				$tagstatus_id  = $this->model_resident_resident->addTagstatus($tagstatus, $this->request->post['childstatus'], $this->request->post['tags_id'], $notes_id);
				
			}else{
				$this->load->model('resident/resident');
				$tagstatus_id  = $this->model_resident_resident->addTagstatus2($this->request->post['childstatus'], $this->request->post['tags_id'], $notes_id);
			}
			
			
			$this->db->query("UPDATE `" . DB_PREFIX . "notes` SET tagstatus_id = '1' WHERE notes_id = '" . (int)$notes_id . "'");
			
		
			$this->data['facilitiess'][] = array( 
				'warning'  => '1',
				'notes_id'  => $notes_id,
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
				'data' => 'Error in addclient jsonaddresidentstatus',
			);
			$this->model_activity_activity->addActivity('app_jsonaddresidentstatus', $activity_data2);
		
		
		} 
	}
	
	
	public function jsonupdateFile(){
		
		try{
		
		$json = array();
		
		/*$this->load->model('api/encrypt');
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
		}*/
		
		$this->load->model('setting/tags');
			
			
			
		
		if($this->request->files["upload_file"] != null && $this->request->files["upload_file"] != ""){

			if($this->request->post['tags_id'] != null && $this->request->post['tags_id'] != ""){
				$extension = end(explode(".", $this->request->files["upload_file"]["name"]));

				
				if($this->request->files["upload_file"]["size"] < 42214400){
				$neextension  = strtolower($extension);
				//if($neextension != 'mp4' && $neextension != 'mp3' && $neextension != 'flv' && $neextension != '3gp' && $neextension != 'wav' && $neextension != 'mkv' && $neextension != 'avi'){

					/*$notes_file = uniqid( ) . "." . $extension;
					$outputFolder = DIR_IMAGE.'files/' . $notes_file;
					move_uploaded_file($this->request->files["upload_file"]["tmp_name"], $outputFolder);
					
					*/
					
					$notes_file = 'devbolb'.rand().'.'.$extension;
					$outputFolder = $this->request->files["upload_file"]["tmp_name"];
						
					//require_once(DIR_SYSTEM . 'library/azure_storage/config.php');
					
					//require_once(DIR_SYSTEM . 'library/awsstorage/s3_config.php');
					
					if($this->config->get('enable_storage') == '1'){
						/* AWS */
						
						require_once(DIR_SYSTEM . 'library/awsstorage/s3_config.php');
					}
					
					if($this->config->get('enable_storage') == '2'){
						/* AZURE */
						
						require_once(DIR_SYSTEM . 'library/azure_storage/config.php');					
						//uploadBlobSample($blobClient, $outputFolder, $notes_file);
						$s3file = AZURE_URL. $notes_file;
					}
					
					if($this->config->get('enable_storage') == '3'){
						/* LOCAL */
						$outputFolder = DIR_IMAGE.'storage/' . $notes_file;
						move_uploaded_file($this->request->files["upload_file"]["tmp_name"], $outputFolder);
						$s3file = HTTPS_SERVER.'image/storage/' . $notes_file;
					}
					
					
					$notes_media_extention = $extension;
					$notes_file_url = $s3file;
					
						
					date_default_timezone_set($this->request->post['facilitytimezone']);
					$formData['noteDate'] = date('Y-m-d H:i:s', strtotime('now'));
					
					$outputFolder11 = DIR_IMAGE.'files/' . $notes_file;
					move_uploaded_file($this->request->files["upload_file"]["tmp_name"], $outputFolder11);

					
					$this->model_setting_tags->updateTagimage($this->request->post['tags_id'], $s3file);
					
					$error = true;
					
					$this->data['facilitiess'][] = array(
						'success'   => '1',
						'file'   => $s3file,
					);
				
				/*}else{
					$this->data['facilitiess'][] = array(
						'warning'  => 'video or audio file not valid!',
					);
					$error = false;
				}*/
				}else{
					$this->data['facilitiess'][] = array(
						'warning'  => 'Maximum size file upload!',
					);
					$error = false;
				}

			}else{
				$this->data['facilitiess'][] = array(
				'warning'  => 'Note not update please update again',
			);
				$error = false;
			}
		}else{
			$this->data['facilitiess'][] = array(
				'warning'  => 'Please select file!',
			);
			$error = false;
		}

		
		$value = array('results'=>$this->data['facilitiess'],'status'=>$error);
		$this->response->setOutput(json_encode($value));
		
		}catch(Exception $e){
			
			$this->load->model('activity/activity');
			$activity_data2 = array(
				'data' => 'Error in appservices jsonupdateFile',
			);
			$this->model_activity_activity->addActivity('app_jsonupdateFile', $activity_data2);
		
		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		} 
		
	}
}

